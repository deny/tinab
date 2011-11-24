<?php

/**
 * Strona główna administracji
 */
class Administration_IndexController extends Core_Controller_Action
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
			array('index'),
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
}