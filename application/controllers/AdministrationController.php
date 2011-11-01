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
			array('index', 'groups', 'groups-add'),
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

	/**
	 * Zarządzanie grupami
	 */
	public function groupsAction()
	{
		$oPaginator = GroupFactory::getNew()->getProjectPaginator(1, self::COUNT);

		$this->view->assign('oPaginator', $oPaginator);
	}

	/**
	 * Dodawanie nowej grupy
	 */
	public function groupsAddAction()
	{
		if($this->_request->isPost())
		{
			$oFilter = $this->getFrontController();

			if($oFilter->isValid())
			{

			}

			$this->showFormMessages($oFilter);
		}
	}

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
				new Core_Validate_EmailAddress(),
				'emptyMsg' => 'Musisz podać nazwę grupy'
			),
			'passwd' => array(
				'emptyMsg' => 'Musisz podać hasło'
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}