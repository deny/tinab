<?php

/**
 * Podsumowanie projektu
 */
class Project_SummaryController extends Core_Controller_ProjectAction
{
	const COUNT = 10;

	/**
	 * (non-PHPdoc)
	 * @see Core_Controller_Action::init()
	 */
	public function init()
	{
		// wymagane uprawnienia
		$this->setAcl(
			array(
				'index'
			),
			array(Privileges::PROJ_ADM)
		);

		parent::init();
	}

	/**
	 * Wyświetla informacje o wybranym projekcie
	 */
	public function indexAction()
	{
	}
}