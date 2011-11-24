<?php

/**
 * Plugin zmieniający ścieżkę
 *
 * @author Daniel Kózka
 */
class Core_Controller_Plugin_ScriptPath extends Zend_Controller_Plugin_Abstract
{
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Plugin_Abstract::dispatchLoopStartup()
	 */
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $oRequest)
	{
		$oView = Zend_Controller_Action_HelperBroker::getExistingHelper('ViewRenderer')->view;

		if($oRequest->getControllerName() == 'Error')
		{
			$sScriptPath = sprintf('%s/modules/default/views', APPLICATION_PATH);
		}
		else
		{
			$sScriptPath = sprintf('%s/modules/%s/views', APPLICATION_PATH, $oRequest->getModuleName());
		}

		if(file_exists($sScriptPath))
		{
			$oView->addScriptPath($sScriptPath);
        }
    }
}
