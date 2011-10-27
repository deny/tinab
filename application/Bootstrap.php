<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Ustawienia autoloadera
	 */
	protected function _initAutoload()
	{
		Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
	}

}

