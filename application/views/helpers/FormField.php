<?php 

/**
 * Helper wyświetlający pole formularza
 */
class View_Helper_FormField extends Zend_View_Helper_Abstract
{
	/**
	 * Funkcja helpera
	 *
	 * @param	string	$sLabel		opis pola
	 * @param	string	$sName		nazwa pola
	 * @param	string	$sField		pole formularza
	 * @param	array	$aAttribs	tablica z atrybutami 
	 * @param	array	$aOptions	opcje dodatkowe
	 * @return	string
	 */
	public function formField($sLabel, $sName, $sField, $aAttribs = array(), $aOptions = array())
	{
		// pobranie błędów walidacji
		$sErrors =  $this->view->formFieldErrors($sName);
		
		if(!empty($sErrors))
		{
			if(isset($aAttribs['class']))
			{
				$aAttribs['class'] .= ' error'; 
			}
			else
			{
				$aAttribs['class'] = 'error';
			}
		}
		 
		// tag otwierający pole
		$sResult = '<div' . $this->getAttribs($aAttribs, !empty($sErrors)) . ' >';		
		
			// opis i pole
			$sLabel = '<label for="' . $sName . '">' . $sLabel . '</label>';
			$sField = '<p>'. $sField .'</p>';
			
			// dodanie do wyniku (odwrotna kolejność label<->pole)
			$sResult .= isset($aOptions['flip']) && $aOptions['flip'] ? $sField . $sLabel : $sLabel . $sField;
			
			// błędy
			$sResult .= $sErrors;
			
		$sResult .= '</div>';
		
		return $sResult;
	}
	
	/**
	 * Zwraca stringa z atrubutami HTML'a
	 * 
	 * @param 	array	$aAttribs	tablica atrybutów
	 * @param	bool	$bError		czy są błędy walidacji
	 * @return	string
	 */
	protected function getAttribs(array $aAttribs, $bError)
	{
		if($bError) // jeśli są błędy
		{
			if(isset($aAttribs['class'])) // jeśli podano klasę to dopisujemy klasę error
			{
				$aAttribs['class'] .= ' error'; 
			}
			else // jeśli nie podano klasy
			{
				$aAttribs['class'] = 'error';
			}
		}

		$sResult = '';
		foreach($aAttribs as $sName => $sValue)
		{
			$sResult .= ' ' . $sName . '="' . $sValue . '"';
		}
		
		return $sResult;
	}
}