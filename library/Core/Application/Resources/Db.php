<?php 

/**
 * Resource ładujący połączenie z bazą do rejestru
 */
class Core_Application_Resources_Db extends Zend_Application_Resource_Db
{
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Application_Resource_Db::init()
	 */
	public function init()
	{
		$oDb = parent::init();
		Zend_Registry::set('db', $oDb);
		
		return $oDb;
	}	
}