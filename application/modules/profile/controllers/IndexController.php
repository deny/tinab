<?php

/**
 * Kontroler strony głównej
 */
class Profile_IndexController extends Core_Controller_Action
{
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init()
	{
		// wymagane uprawnienia
		$this->setAcl(array(
			'index'
		));

		parent::init();
		$this->_helper->layout()->setLayout('profile');
	}

	/**
	 * Strona główna profilu
	 */
	public function indexAction()
	{
    }
}

