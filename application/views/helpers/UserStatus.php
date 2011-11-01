<?php

/**
 * Zwraca nazwy statusów usera
 */
class View_Helper_UserStatus extends Zend_View_Helper_Abstract
{
	/**
	 * Opisy statusów
	 *
	 * @var	string
	 */
	protected $aDescription = array(
		User::STATUS_ACTIVE 	=> 'aktywny',
		User::STATUS_INACTIVE	=> 'nieaktywny',
		User::STATUS_DELETED	=> 'usunięty'
	);

	/**
	 * Funkcja helpera
	 *
	 * @param	User	$oUser	obiekt usera
	 * @return	string
	 */
	public function userStatus(User $oUser)
	{
		return $this->aDescription[$oUser->getStatus()];
	}
}