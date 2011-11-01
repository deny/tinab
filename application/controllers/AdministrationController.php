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
			array('index', 'groups'),
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
}