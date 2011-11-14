<?php

/**
 * Helper do zmiany klas w layoucie
 */
class View_Helper_Layout_Class extends Zend_View_Helper_Abstract
{
	/**
	 * Domyślna klasa
	 *
	 * @var	string
	 */
	protected $sContentWrapper = 'content-wrapper';

	/**
	 * Funkcja helpera (zwraca obiekt helpera
	 *
	 * @return	View_Helper_Layout_Class
	 */
	public function layout_Class()
	{
		return $this;
	}

	/**
	 * Jeśli podano dadtkowe klasy to dodaje je do aktualnych i zwraca obiekt helpera
	 * Wywołąna bez parametrów zwraca ustowione klasy
	 *
	 * @param	string	$sClass	dodatkowe klasy
	 * @return	string|View_Helper_Layout_Class
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