<?php

/**
 * Administracja userami
 */
class Administration_UsersController extends Core_Controller_Action
{
	const COUNT = 10;

	/**
	 * (non-PHPdoc)
	 * @see Core_Controller_Action::init()
	 */
	public function init()
	{
		// wymagane uprawnienia
		$this->setAcl(
			array(
				'index', 'add', 'edit', 'delete', 'status', 'groups'
			),
			array(Privileges::ADMIN)
		);

		parent::init();
	}

	/**
	 * Lista userów oraz akcji na nich
	 */
	public function indexAction()
	{
		$iPage = $this->_request->getParam('page', 1);

		$oPaginator = UserFactory::getNew()->getActivePaginator($iPage, self::COUNT);

		$this->view->assign('oPaginator', $oPaginator);
	}

	/**
	 * Dodawanie usera
	 */
	public function addAction()
	{
		if($this->_request->isPost())  // submit formularza
		{
			$oFilter = $this->getUserFilter();

			if($oFilter->isValid()) // dane poprawne
			{
				$aValues = $oFilter->getEscaped();

				// utworzenie grupy
				$oUser = UserFactory::getNew()->create(
					$aValues['email'],
					$aValues['passwd'],
					$aValues['name'],
					$aValues['surname']
				);

				// przeniesienie na stronę userów
				$this->addMessage('Pomyślnie utworzono użytkownika "'. $oUser->getNick() .'"', self::MSG_OK);
				$this->_redirect('/administration/users');
				exit();
			}

			$this->showFormMessages($oFilter); // dane niepoprawne
		}

		$this->view->assign('bEdit', false);
		$this->_helper->viewRenderer('users-form');
	}

	/**
	 * Edycja usera
	 */
	public function editAction()
	{
		// wyjęcie edytowanej grupy
		$oUser = $this->getUser();

		if($this->_request->isPost()) // submit formularza
		{
			$oFilter = $this->getUserFilter($oUser);

			if($oFilter->isValid()) // dane poprawne
			{
				$aValues = $oFilter->getEscaped();

				// utworzenie user
				$oUser->setName($aValues['name']);
				$oUser->setSurname($aValues['surname']);
				$oUser->setEmail($aValues['email']);

				if(!empty($aValues['passwd'])) // jeśli zmieniano hasło
				{
					$oUser->setPasswd($aValues['passwd']);
				}

				$oUser->save();

				// przeniesienie na stronę userów
				$this->addMessage('Pomyślnie zapisano zmiany w użytkowniku "'. $oUser->getNick() .'"', self::MSG_OK);
				$this->_redirect('/administration/users');
				exit();
			}

			$this->showFormMessages($oFilter); // dane niepoprawne
		}
		else // pierwsze wejście - aktualne dane
		{
			$aValues = array(
				'email'		=> $oUser->getEmail(),
				'name'		=> $oUser->getName(),
				'surname'	=> $oUser->getSurname()
			);

			$this->view->assign('aValues', $aValues);
		}

		$this->view->assign('bEdit', true);
		$this->_helper->viewRenderer('users-form');
	}

	/**
	 * Zmiana statusu usera
	 */
	public function statusAction()
	{
		$oUser = $this->getUser();

		if($oUser->getStatus() == User::STATUS_ACTIVE)
		{
			$oUser->setStatus(User::STATUS_INACTIVE);
			$this->addMessage('Użytkownik "'. $oUser->getNick().'" został zdezaktywowany', self::MSG_OK);
		}
		else
		{
			$oUser->setStatus(User::STATUS_ACTIVE);
			$this->addMessage('Użytkownik "'. $oUser->getNick().'" został aktywowany', self::MSG_OK);
		}
		$oUser->save();

		$this->_redirect('/administration/users');
	}

	/**
	 * Usuwanie usera
	 */
	public function deleteAction()
	{
		$oUser = $this->getUser();
		$oUser->setStatus(User::STATUS_DELETED);
		$oUser->save();

		$this->addMessage('Pomyślnie usunięto użytkownika "'. $oUser->getNick().'"', self::MSG_OK);
		$this->_redirect('/administration/users');
	}

	/**
	 * Zarządzanie grupami usera
	 */
	public function groupsAction()
	{
		$oUser = $this->getUser();
		$aGroups = GroupFactory::getNew()->getList();

		if($this->_request->isPost())
		{
			$aNewIds = $this->_request->getPost('groups', array());

			// wyjmujemy tylko poprawne ID
			$aNewIds = array_intersect($aNewIds, array_keys($aGroups));

			$oUser->resetGroups($aNewIds);
			$this->_redirect('/administration/users');
			exit();
		}
		else // jeśli brak posta to przekazujemy do formularza dane usera
		{
			$aIds = array();
			foreach($oUser->getGroups() as $oGroup)
			{
				$aIds[] = $oGroup->getId();
			}

			$this->view->assign('aValues', array(
				'groups' => $aIds
			));
		}

		$this->view->assign('oUser', $oUser);
		$this->view->assign('aGroups', $aGroups);
	}

// FUNKCJE POMOCNICZE

	/**
	 * Zwraca obiekt usera na podstawie ID przkazanego GET'em
	 *
	 * @return	User
	 */
	protected function getUser()
	{
		try
		{
			$iId = (int) $this->_request->getParam('id', 0);
			$oUser = UserFactory::getNew()->getOne($iId);

			if($oUser->getStatus() == User::STATUS_DELETED)
			{
				throw new Exception();
			}
		}
		catch(Exception $oExc)
		{
			$this->addMessage('Nie znaleziono wybranego użytkownika', self::MSG_ERROR);
			$this->_redirect('/administration/users');
			exit();
		}

		return $oUser;
	}

// FILTRY

	/**
	 * Zwraca filtr dla userów
	 *
	 * @param	User	$oUser	obiekt edytowanego usera
	 * @return	Zend_Filter_Input
	 */
	protected function getUserFilter($oUser = null)
	{
		$aValues = $this->_request->getPost();

		$oPasswd = new Zend_Validate_Identical($aValues['passwd']);
		$oPasswd->setMessage('Podane hasła są różne', Zend_Validate_Identical::NOT_SAME);

    	// walidatory
		$aValidators = array(
			'email'	=> array(
				new Core_Validate_EmailAddress(),
				new Core_Validate_EmailUnique($oUser),
				new Core_Validate_StringLength(array('min' => 1, 'max' => 50))
			),
			'name'	=> array(
				new Core_Validate_StringLength(array('min' => 1, 'max' => 20)),
			),
			'surname' => array(
				new Core_Validate_StringLength(array('min' => 1, 'max' => 30)),
			),
			'passwd' => array(
				new Core_Validate_StringLength(array('min' => 8)),
			),
			'passwd2' => array(
				new Core_Validate_StringLength(array('min' => 8)),
				$oPasswd
			)
		);

		if(isset($oUser))
		{
			$aValidators['passwd']['allowEmpty'] = true;
			$aValidators['passwd2']['allowEmpty'] = true;
		}

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}