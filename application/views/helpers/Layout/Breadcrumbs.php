<?php

/**
 * Helper do okruszków
 */
class View_Helper_Layout_Breadcrumbs extends Zend_View_Helper_Abstract
{
	/**
	 * Tablica na okruszki
	 *
	 * @var	array
	 */
	protected $aBreadcrumbs = array();

	/**
	 * Funkcja helpera
	 *
	 * @return	View_Helper_Layout_Breadcrumbs
	 */
	public function layout_Breadcrumbs()
	{
		return $this;
	}

	/**
	 * Ustawia okruszki
	 *
	 * @param 	array 	$aBreadcrumbs	owe okruszki
	 * @return	void
	 */
	public function set(array $aBreadcrumbs)
	{
		$this->aBreadcrumbs = $aBreadcrumbs;
	}

	/**
	 * Renderuje okruszki
	 *
	 * @return	string
	 */
	public function render()
	{
		$sResult = '<div class="breadcrumbs"><ul><li>&raquo; ';

		if(empty($this->aBreadcrumbs))
		{
			$sResult .= 'Main<li>';
		}
		else
		{
			$sResult .= '<a href="/">Main</a><li>';
		}

		foreach($this->aBreadcrumbs as $sLink => $sAddress)
		{
			$sResult .= '<li>&raquo; ';
			if(empty($sAddress))
			{
				$sResult .= $sLink;
			}
			else
			{
				$sResult .= '<a href="'. $sAddress .'">'. $sLink . '</a>';
			}
			$sResult .= '</li>';
		}

		return $sResult . '</ul></div>';
	}
}