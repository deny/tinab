<?php

/**
 * Wyświetla główny (górny) panel nawigacyjny serwisu
 */
class View_Helper_Layout_MainPanel extends Zend_View_Helper_Abstract
{
	/**
	 * Główne menu
	 *
	 * @var	array
	 */
	protected $aMenu = array(
		'default/summary' 	=> array('Podsumowanie', '/summary'),
		'#1' 				=> array('Zadania', '#'),
		'#2' 				=> array('Wiadomości', '#')
	);

	/**
	 * Obiekt zalogowanego usera
	 *
	 * @var	User
	 */
	protected $oUser;

	/**
	 * Funkcja helpera
	 *
	 * @return	string
	 */
	public function layout_MainPanel()
	{
		$this->oUser = Core_Auth::getInstance()->getUser();

		// pobranie globalnych uprawnień
		$aPriv = $this->oUser->getPrivileges();

		if(in_array(Privileges::ADMIN, $aPriv))
		{
			$this->aMenu['administration/*'] = array('Administracja', '/administration');
		}

		// wygenerowanie HTML'a
		$sResult = '';
		$sResult .= $this->getUserPanel();
		$sResult .= $this->getMenu();

		return $sResult;
	}

	/**
	 * Zwraca HTML'a menu
	 *
	 * @return	string
	 */
	protected function getMenu()
	{
		$oRequest = Zend_Controller_Front::getInstance()->getRequest();
		$sMod = $oRequest->getModuleName();
		$sCon = $oRequest->getControllerName();

		$sResult = '<div class="main-nav"><ul>';

		$sCurrent = '';

		if(isset($this->aMenu[$sMod .'/'. $sCon]))
		{
			$sCurrent = $sMod .'/'. $sCon;
		}
		elseif(isset($this->aMenu[$sMod .'/*']))
		{
			$sCurrent = $sMod .'/*';
		}

		foreach($this->aMenu as $sController => $aInfo)
		{
			$sClass = '';
			if( $sCurrent == $sController)
			{
				$sClass = ' class="current" ';
			}

			$sResult .= '<li' . $sClass . '><a href="'. $aInfo[1] . '">' . $aInfo[0] . '</a></li> ';
		}

		$sResult .= '</ul></div>';

		return $sResult;
	}

	/**
	 * Zwraca panel z menu usera
	 *
	 * @return	string
	 */
	protected function getUserPanel()
	{
		return '<div class="main-panel"><ul>
				<li>'. $this->oUser->getEmail() .'</li>
				<li><a href="/settings">ustawienia</a></li>
				<li><a href="/index/logout">wyloguj</a></li>
			</ul>
		</div>';
	}
}