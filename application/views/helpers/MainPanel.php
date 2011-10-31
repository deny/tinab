<?php

/**
 * Wyświetla główny (górny) panel nawigacyjny serwisu
 */
class View_Helper_MainPanel extends Zend_View_Helper_Abstract
{
	/**
	 * Funkcja helpera
	 *
	 * @return	string
	 */
	public function mainPanel()
	{
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
		return '<div class="main-nav">
			<ul>
				<li class="current"><a href="/">Podsumowanie</a></li>
				<li><a href="/tasks">Zadania</a></li>
			</ul>
		</div>';
	}

	/**
	 * Zwraca panel z menu usera
	 *
	 * @return	string
	 */
	protected function getUserPanel()
	{
		return '<div class="main-panel"><ul>
				<li>deny1@test.pl</li>
				<li><a href="">ustawienia</a></li>
				<li><a href="/index/logout">wyloguj</a></li>
			</ul>
		</div>';
	}
}