<?php

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
			array('index', 'groups', 'groups-add', 'groups-edit', 'groups-delete'),
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
}