<?php

/**
 * Klasa autoryzacji
 */
class Core_Auth extends Zend_Auth
{
	/**
	 * Instancja obiektu Core_Auth
	 *
	 * @var Core_Auth
	 */
	protected static $_instance;

	/**
	 * Obiekt zalogowanego usera
	 *
	 * @var	User
	 */
	protected $oUser = null;
	
	/**
	 * (non-PHPdoc)
	 * @see Zend_Auth::hasIdentity()
	 */
	public function hasIdentity()
	{
		if(parent::hasIdentity())
		{
			try
			{
				$iId = (int) $this->getIdentity();
				$oUser = UserFactory::getNew()->getOne($iId);
				
				if($oUser->getStatus() != User::STATUS_ACTIVE)
				{
					return false;
				}
				
				$this->oUser = $oUser;
				return true;
			}
			catch(Core_DataObject_Exception $oExc) // brak usera - wylogowanie
			{
				$this->clearIdentity();
				return false;
			}
		}
		
		return false;
	}

	/**
	 * Zwraca zalogowanego usera
	 *
	 * @return User
	 */
	public function getUser()
	{
		if(!$this->hasIdentity())
		{
			throw new Core_AuthException('User nie jest zalogowany');
		}

		return $this->oUser;
	}

 	/**
     * Singleton
     *
     * @return Core_Auth
     */
	public static function getInstance()
	{
		if(self::$_instance === null) 
		{
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}

class Core_AuthException extends Exception {}