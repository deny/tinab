<?php

/**
 * Helper zwracający menu dla profilu
 *
 * @author	Daniel Kózka
 */
class View_Helper_Layout_ProfileMenu extends Zend_View_Helper_Abstract
{
	/**
	 * Menu zarządzania profilem
	 *
	 * @var	array
	 */
	protected $aMenu = array(
		'index/index'		=> array('Informacje', '/profile'),
		'settings/info'		=> array('Dane osobowe', '/profile/settings/info'),
		'settings/password'	=> array('Hasło', '/profile/settings/password'),
		'settings/notices'	=> array('Powiadomienia', '/profile/settings/notices'),
	);

	/**
	 * Funkcja helpera
	 *
	 * @return	string
	 */
	public function layout_ProfileMenu()
	{
		$oRequest = Zend_Controller_Front::getInstance()->getRequest();
		$sCurrent = $oRequest->getControllerName() .'/'. $oRequest->getActionName();

		$sResult = '<ul>';

		foreach($this->aMenu as $sAction => $aInfo)
		{
			$sClass = '';
			if( $sCurrent == $sAction)
			{
				$sClass = ' class="current" ';
			}

			$sResult .= '<li><a ' . $sClass . ' href="'. $aInfo[1] . '">' . $aInfo[0] . '</a></li> ';
		}

		$sResult .= '</ul>';

		return $sResult;
	}
}