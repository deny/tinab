<?php

/**
 * Dodane wywoływanie JS
 */
class View_Helper_HeadScript extends Zend_View_Helper_HeadScript
{
	/**
	 * Wywołanie funkcji JS
	 *
	 * @return	View_Helper_HeadScript
	 */
	public function callJs()
	{
		$aParams	= func_get_args();
		if(count($aParams) > 0)
		{
			$sFunc = array_shift($aParams);

			foreach($aParams as $iKey => $mParam)
			{
				$aParams[$iKey] = $this->jsParams($mParam);
			}

			$this->appendScript($sFunc . '(' . implode($aParams, ', ') . ');');
		}

		return $this;
	}

	/**
	 * Koduje parametr do wersji akceptowanej przez JS
	 *
	 * @param	mixed	$mParam	kodowany parametr
	 * @return	string
	 */
	protected function jsParams($mParam)
	{
		if(is_bool($mParam))
		{
			return $mParam ? 'true' : 'false';
		}
		elseif(is_string($mParam))
		{
			return '"' . $mParam . '"';
		}
		elseif(is_array($mParam))
		{
			$sTmp = '';
			if($this->isAssoc($mParam)) // jeśli to tablica asocjacyjna
			{
				$sTmp = '{';
				foreach($mParam as $sKey => $mArrayParam)
				{
					$sTmp .= '"'. $sKey .'": ' . $this->jsParams($mArrayParam) .', ';
				}
				$sTmp = rtrim($sTmp, ', ') . '}';
			}
			else // tablica zwykła
			{
				$sTmp = '[';
				foreach($mParam as $sKey => $mArrayParam)
				{
					$sTmp .= $this->jsParams($mArrayParam) .', ';
				}
				$sTmp = rtrim($sTmp, ', ') . ']';
			}
			return $sTmp;
		}

		return $mParam;
	}

	/**
	 * Sprawdza czy tablica jest tablicą asocjacyjną
	 *
	 * @param 	array $aArray	testowana tablica
	 * @return 	bool
	 */
	protected function isAssoc(array $aArray)
	{
	    $aKeys = array_keys($aArray);
	    return ($aKeys !== array_keys($aKeys));
	}
}