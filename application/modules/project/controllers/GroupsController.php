<?php

/**
 * Grupy w projekcie
 */
class Project_GroupsController extends Core_Controller_ProjectAction
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
				'list', 'add', 'edit', 'delete', 'users'
			),
			array(Privileges::PROJ_ADM)
		);

		parent::init();
	}

	/**
	 * Zarządzanie grupami
	 */
	public function listAction()
	{
		$iPage = $this->_request->getParam('page', 1);

		$oPaginator = GroupFactory::getNew()->getProjectPaginator($iPage, self::COUNT, $this->iProjectId);

		$this->view->assign('oPaginator', $oPaginator);
	}

	/**
	 * Dodawanie nowej grupy
	 */
	public function addAction()
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
					$aValues['desc'],
					$this->iProjectId
				);

				// nadanie uprawnień (jeśli trzeba)
				if(!empty($aValues['priv']))
				{
					$oGroup->setPrivileges($aValues['priv']);
				}

				// przeniesienie na stronę grup
				$this->addMessage('Pomyślnie utworzono grupę "'. $aValues['name'] .'"', self::MSG_OK);
				$this->_redirect('/project/groups/list/id/'. $this->iProjectId);
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
	public function editAction()
	{
		// wyjęcie edytowanej grupy
		$oGroup = $this->getGroup();

		if($oGroup->isMain()) // jeśli jest to grupa administratorów projektu
		{
			$this->addMessage('Grupa administratorów projektu nie może być edytowana', self::MSG_ERROR);
			$this->_redirect('/project/groups/list/id/'. $this->iProjectId);
			exit();
		}

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
				$this->_redirect('/project/groups/list/id/'. $this->iProjectId);
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
	public function deleteAction()
	{
		$oGroup = $this->getGroup();

		if($oGroup->isMain()) // jeśli jest to grupa administratorów projektu
		{
			$this->addMessage('Grupa administratorów projektu nie może być usunięta', self::MSG_ERROR);
			$this->_redirect('/project/groups/list/id/'. $this->iProjectId);
			exit();
		}

		$oGroup->delete();

		$this->addMessage('Pomyślnie usunięto grupę "'. $oGroup->getName() .'"', self::MSG_OK);
		$this->_redirect('/administration/groups');
	}

	/**
	 * Zarządzanie userami należącymi do grupy
	 */
	public function usersAction()
	{
		$oGroup = $this->getGroup();
		$aUsers = UserFactory::getNew()->getList();

		if($this->_request->isPost())
		{
			$aNewIds = $this->_request->getPost('users', array());

			// wyjmujemy tylko poprawne ID
			$aNewIds = array_intersect($aNewIds, array_keys($aUsers));

			if(!($oGroup->isMain() && empty($aNewIds)))
			{
				$oGroup->resetUsers($aNewIds);
				$this->_redirect('/project/groups/list/id/'. $this->iProjectId);
				exit();
			}

			$this->addMessage('Grupa administratorów projektu nie może być pusta', self::MSG_ERROR, true);
		}
		else // jeśli brak posta to przekazujemy do formularza dane grupy
		{
			$aIds = array();
			foreach($oGroup->getUsers() as $oUser)
			{
				$aIds[] = $oUser->getId();
			}

			$this->view->assign('aValues', array(
				'users' => $aIds
			));
		}

		$this->view->assign('oGroup', $oGroup);
		$this->view->assign('aUsers', $aUsers);
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
			$iId = (int) $this->_request->getParam('group', 0);
			$oGroup = GroupFactory::getNew()->getOne($iId);

			if($oGroup->getProjectId() != $this->iProjectId) // jeśli nie jest to grupa należąca do projektu
			{
				throw new Exception();
			}
		}
		catch(Exception $oExc)
		{
			$this->addMessage('Nie znaleziono wybranej grupy', self::MSG_ERROR);
			$this->_redirect('/project/groups/list/id/'. $this->iProjectId);
			exit();
		}

		return $oGroup;
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
				new Core_Validate_InArray(array_keys(Privileges::getProject())),
				'allowEmpty' 	=> true,
				'presence'		=> false
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}