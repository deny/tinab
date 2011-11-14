<?php

/**
 * Helper zwracający sidebara dla adminki
 */
class View_Helper_Sidebar_Admin extends Zend_View_Helper_Abstract
{
	/**
	 * Funkcja helpera
	 *
	 * @return	string
	 */
	public function sidebar_Admin()
	{
		return '<div class="sidebar">
	<div class="box">
		<header>
			<h3>Na skróty</h3>
		</header>
		<div class="box-content adm">
			<ul>
				<li><a href="/administration/users-add">dodaj użytkownika</a></li>
				<li><a href="/administration/groups-add">dodaj grupę</a></li>
				<li><a href="#">wyślij wiadomość</a></li>
				<li>---</li>
				<li><a href="/administration/users">użytkownicy</a></li>
				<li><a href="/administration/groups">grupy</a></li>
				<li><a href="#">statystyka</a></li>
			</ul>
		</div>
	</div>
</div>';
	}
}