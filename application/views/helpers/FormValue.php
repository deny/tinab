<?php 

/**
 * Helper zwracajacy wartość dla pola formularza
 */
class View_Helper_FormValue extends Zend_View_Helper_Abstract
{
	/**
	 * Funkcja helpera
	 * 
	 * @param	string	$sField		nazwa pola
	 * @param	mixed	$mDefault	wartość domyślna
	 * @return	mixed
	 */
	public function formValue($sField, $mDefault = null)
	{
		if(isset($this->view->aValues) && isset($this->view->aValues[$sField]))
		{
			return $this->view->aValues[$sField];
		}
		
		return $mDefault;
	}
}