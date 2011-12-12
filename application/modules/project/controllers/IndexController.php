<?php

/**
 * Lista projektów
 */
class Project_IndexController extends Core_Controller_Action
{
	/**
	 * (non-PHPdoc)
	 * @see Core_Controller_Action::init()
	 */
	public function init()
	{
		// wymagane uprawnienia
		$this->setAcl(array(
			'index'
		));

		parent::init();
	}

	/**
	 * Wyświetla liste projektów usera
	 */
	public function indexAction()
	{

	}
}