<?php

/**
 * Zwraca nazwy uprawnień
 */
class View_Helper_PrivilegesDesc extends Zend_View_Helper_Abstract
{
	/**
	 * Opisy uprawnień
	 *
	 * @var	array
	 */
	protected $aDesc;

	/**
	 * Konstruktor
	 *
	 * @return	View_Helper_PrivilegesDesc
	 */
	public function __construct()
	{
		$this->aDesc = Privileges::getDescriptions();
	}

	/**
	 * Funkcja helpera - jeśli podano jedno uprawnienie, to zwraca jego nazwę
	 * Jeśli podano tablicę uprawnień to zwraca tablice z nazwami
	 *
	 * @param	mixed	$mPriv	uprawnienia
	 * @return	mixed
	 */
	public function privilegesDesc($mPriv)
	{
		if(is_array($mPriv))
		{
			$aResult = array();
			foreach($mPriv as $sPriv)
			{
				$aResult[] = $this->getDesc($sPriv);
			}

			return $aResult;
		}

		return $this->getDesc($mPriv);
	}

	/**
	 * Zwraca opis uprawnienia
	 *
	 * @param	string	$sPriv	uprawnienie
	 * @return	string
	 */
	protected function getDesc($sPriv)
	{
		if(!isset($this->aDesc[$sPriv]))
		{
			throw new Exception('Brak opisu uprawnienia ' . $sPriv);
		}

		return $this->aDesc[$sPriv];
	}
}