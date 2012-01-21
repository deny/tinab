<?php

/**
 * Helper zwracający sidebara dla tasków
 */
class View_Helper_Sidebar_Task extends Zend_View_Helper_Abstract
{
	/**
	 * Funkcja helpera
	 *
	 * @return	string
	 */
	public function sidebar_Task()
	{
		$sResult = '<div class="sidebar">
	<div class="box">
		<header>
			<h3>Operacje na zadaniach</h3>
		</header>
		<div class="box-content box-r">
			<ul>';

	// dodawanie zadań
		$sResult .= '<li><strong>Zadania</strong></li>';
		$sResult .= '<li><a href="#" class="task-add">nowe zadanie</a></li><br />';

	// zmiana środowiska
		$sResult .= '<li><strong>Zmiana środowiska</strong></li>';
		$sResult .= '<li><a class="env-ch" href="#" data-id="0">Brak</a></li>';
		foreach(EnvironmentFactory::getNew()->getList() as $iId => $sEnv)
		{
			$sResult .= '<li><a class="env-ch" href="#" data-id="'. $iId .'">'. $sEnv .'</a></li>';
		}
		$sResult .= '<br />';

	// zmiana statusu
		$sResult .= '<li><strong>Zmiana statusu</strong></li>';
		foreach($this->view->task_Status()->getList() as $sId => $sStatus)
		{
			$sResult .= '<li><a class="status-ch" href="#" data-id="'. $sId .'">'. $sStatus .'</a></li>';
		}
		$sResult .= '<br />';

	// zmiana osoby odpowiedzialnej
		$sResult .= '<li><strong>Zmiana osoby odpowiedzialnej</strong></li>';
		$sResult .= '<li><a class="user-ch" href="#" data-id="0">DLA WSZYSTKICH</a></li>';
		foreach($this->view->aUsers as $sId => $sName)
		{
			$sResult .= '<li><a class="user-ch" href="#" data-id="'. $sId .'">'. $sName .'</a></li>';
		}

		$sResult .= '
			</ul>
		</div>
	</div>
</div>';

		return $sResult;
	}
}