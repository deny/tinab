<?php

/**
 * Bootstrap
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Ustawienia autoloadera
	 */
	protected function _initAutoload()
	{
		Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
	}

	/**
	 * Opcje
	 */
	protected function _initOptions()
	{
		Zend_Controller_Action_HelperBroker::addPrefix('Core_Controller_Action_Helper'); // Action Helpery

		$this->bootstrap('view');
		$this->getResource('view')->doctype('XHTML1_STRICT');	// format HTML'a
	}

}

