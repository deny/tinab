<?php

/**
 * Administracja userami, grupami
 */
class AdministrationController extends Core_Controller_Action
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
				'index',
				'groups', 'groups-add', 'groups-edit', 'groups-delete',
				'users', 'users-add', 'users-edit', 'users-delete', 'users-status', 'users-groups'
			),
			array(Privileges::ADMIN)
		);

		parent::init();
	}

	/**
	 * Spis opcji
	 */
	public function indexAction()
	{

	}

// ---------------  USERZY ---------------------

	/**
	 * Lista userów oraz akcji na nich
	 */
	public function usersAction()
	{
		$iPage = $this->_request->getParam('page', 1);

		$oPaginator = UserFactory::getNew()->getActivePaginator($iPage, self::COUNT);

		$this->view->assign('oPaginator', $oPaginator);
	}

	/**
	 * Dodawanie usera
	 */
	public function usersAddAction()
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
				$this->addMessage('Pomyślnie utworzono usera "'. $oUser->getNick() .'"', self::MSG_OK);
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
	public function usersEditAction()
	{
		// wyjęcie edytowanej grupy
		$oUser = $this->getUser();

		if($this->_request->isPost()) // submit formularza
		{
			$oFilter = $this->getUserFilter($oUser);

			if($oFilter->isValid()) // dane poprawne
			{
				$aValues = $oFilter->getEscaped();

				// utworzenie grupy
				$oUser->setName($aValues['name']);
				$oUser->setDescription($aValues['surname']);
				$oUser->setEmail($aValues['email']);

				if(!empty($aValues['passwd'])) // jeśli zmieniano hasło
				{
					$oUser->setPasswd($aValues['passwd']);
				}

				$oUser->save();

				// przeniesienie na stronę userół
				$this->addMessage('Pomyślnie zapisano zmiany usera "'. $oUser->getNick() .'"', self::MSG_OK);
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
	 * Usuwanie usera
	 */
	public function usersStatusAction()
	{
		$oUser = $this->getUser();

		if($oUser->getStatus() == User::STATUS_ACTIVE)
		{
			$oUser->setStatus(User::STATUS_INACTIVE);
			$this->addMessage('User "'. $oUser->getNick().'" został zdezaktywowany', self::MSG_OK);
		}
		else
		{
			$oUser->setStatus(User::STATUS_ACTIVE);
			$this->addMessage('User "'. $oUser->getNick().'" został aktywowany', self::MSG_OK);
		}
		$oUser->save();

		$this->_redirect('/administration/users');
	}

	/**
	 * Usuwanie usera
	 */
	public function usersDeleteAction()
	{
		$oUser = $this->getUser();
		$oUser->setStatus(User::STATUS_DELETED);
		$oUser->save();

		$this->addMessage('Pomyślnie usunięto usera "'. $oUser->getNick().'"', self::MSG_OK);
		$this->_redirect('/administration/users');
	}

	/**
	 *
	 * Enter description here ...
	 */
	public function usersGroupsAction()
	{
		$oUser = $this->getUser();

		if($this->_request->isPost())
		{

		}
		else // jeśli brak posta to przekazujemy do formularza dane usera
		{
			$aIds = array();
			foreach($oUser->getGlobalGroups() as $oGroup)
			{
				$aIds[] = $oGroup->getId();
			}

			$this->view->assign('aValues', array(
				'groups' => $aIds
			));
		}
		$this->view->assign('oUser', $oUser);
		$this->view->assign('aGroups', GroupFactory::getNew()->getList());
	}

// ---------------  GRUPY ----------------------

	/**
	 * Zarządzanie grupami
	 */
	public function groupsAction()
	{
		$iPage = $this->_request->getParam('page', 1);

		$oPaginator = GroupFactory::getNew()->getProjectPaginator($iPage, self::COUNT);

		$this->view->assign('oPaginator', $oPaginator);
	}

	/**
	 * Dodawanie nowej grupy
	 */
	public function groupsAddAction()
	{
		if($this->_request->isPost())  // submit formularza
		{
			$oFilter = $this->getGroupFilter();

			if($oFilter->isValid()) // dane poprawne
			{
				$aValues = $oFilter->getEscaped();

				// utworzenie grupy
				$oGroup = GroupFactory::getNew()->create(
					$aValues['name'],
					$aValues['desc']
				);

				// nadanie uprawnień (jeśli trzeba)
				if(!empty($aValues['priv']))
				{
					$oGroup->setPrivileges($aValues['priv']);
				}

				// przeniesienie na stronę grup
				$this->addMessage('Pomyślnie utworzono grupę "'. $aValues['name'] .'"', self::MSG_OK);
				$this->_redirect('/administration/groups');
				exit();
			}

			$this->showFormMessages($oFilter); // dane niepoprawne
		}

		$this->view->assign('bEdit', false);
		$this->_helper->viewRenderer('groups-form');
	}

	/**
	 * Edycja grupy
	 */
	public function groupsEditAction()
	{
		// wyjęcie edytowanej grupy
		$oGroup = $this->getGroup();

		if($this->_request->isPost()) // submit formularza
		{
			$oFilter = $this->getGroupFilter();

			if($oFilter->isValid()) // dane poprawne
			{
				$aValues = $oFilter->getEscaped();

				// utworzenie grupy
				$oGroup->setName($aValues['name']);
				$oGroup->setDescription($aValues['desc']);
				$oGroup->save();

				// nadanie uprawnień (jeśli trzeba)
				if(!empty($aValues['priv']))
				{
					$oGroup->setPrivileges($aValues['priv']);
				}

				// przeniesienie na stronę grup
				$this->addMessage('Pomyślnie zapisano zmiany w grupie "'. $aValues['name'] .'"', self::MSG_OK);
				$this->_redirect('/administration/groups');
				exit();
			}

			$this->showFormMessages($oFilter); // dane niepoprawne
		}
		else // pierwsze wejście - aktualne dane
		{
			$aValues = array(
				'name'	=> $oGroup->getName(),
				'desc'	=> $oGroup->getDescription(),
				'priv'	=> $oGroup->getPrivileges()
			);
			$this->view->assign('aValues', $aValues);
		}

		$this->view->assign('bEdit', true);
		$this->_helper->viewRenderer('groups-form');
	}

	/**
	 * Akcja usuwania grupy
	 */
	public function groupsDeleteAction()
	{
		$oGroup = $this->getGroup();
		$oGroup->delete();

		$this->addMessage('Pomyślnie usunięto grupę "'. $oGroup->getName() .'"', self::MSG_OK);
		$this->_redirect('/administration/groups');
	}

// FUNKCJE POMOCNICZE

	/**
	 * Zwraca obiekt grupy na podstawie ID przkazanego GET'em
	 *
	 * @return	Group
	 */
	protected function getGroup()
	{
		try
		{
			$iId = (int) $this->_request->getParam('id', 0);
			$oGroup = GroupFactory::getNew()->getOne($iId);

			if($oGroup->getProjectId() != null) // jeśli nie ejst to grupa globalna
			{
				throw new Exception();
			}
		}
		catch(Exception $oExc)
		{
			$this->addMessage('Nie znaleziono wybranej grupy', self::MSG_ERROR);
			$this->_redirect('/administration/groups');
			exit();
		}

		return $oGroup;
	}

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
			$this->addMessage('Nie znaleziono wybranego usera', self::MSG_ERROR);
			$this->_redirect('/administration/users');
			exit();
		}

		return $oUser;
	}

// FILTRY

	/**
	 * Zwraca filtr dla grup
	 *
	 * @return	Zend_Filter_Input
	 */
	protected function getGroupFilter()
	{
		$aValues = $this->_request->getPost();

    	// walidatory
		$aValidators = array(
			'name'	=> array(
				new Core_Validate_StringLength(array('min' => 1, 'max' => 128)),
				'emptyMsg' => 'Musisz podać nazwę grupy'
			),
			'desc' => array(
				new Core_Validate_StringLength(array('min' => 1, 'max' => 255)),
				'allowEmpty' => true
			),
			'priv' => array(
				new Core_Validate_InArray(array_keys(Privileges::getGlobal())),
				'allowEmpty' 	=> true,
				'presence'		=> false
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}

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