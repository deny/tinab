<?php

/**
 * Widok z podsumowaniem zdarzeń w projektach
 */
class SummaryController extends Core_Controller_Action
{
	public function init()
	{
		$this->setAcl('index', array());

		parent::init();
	}

	/**
	 * Podsumowanie
	 */
	public function indexAction()
	{

	}
}