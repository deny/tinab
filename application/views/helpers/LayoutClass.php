<?php

/**
 * Helper do zmiany klas w layoucie
 */
class View_Helper_LayoutClass extends Zend_View_Helper_Abstract
{
	protected $sContentWrapper = 'content-wrapper';

	/**
	 * Funkcja helpera (zwraca obiekt helpera
	 *
	 * @return	View_Helper_LayoutClass
	 */
	public function layoutClass()
	{
		return $this;
	}

	/**
	 * Jeśli podano dadtkowe klasy to dodaje je do aktualnych i zwraca obiekt helpera
	 * Wywołąna bez parametrów zwraca ustowione klasy
	 *
	 * @param	string	$sClass	dodatkowe klasy
	 * @return	string|View_Helper_LayoutClass
	 */
	public function contentWrapper($sClass = null)
	{
		if(!isset($sClass))
		{
			return $this->sContentWrapper;
		}

		$this->sContentWrapper .= ' ' . $sClass;

		return $this;
	}
}