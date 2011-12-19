<?php

/**
 * Lista projektów
 */
class Project_IndexController extends Core_Controller_Action
{
	/**
	 * Fabryka projektów
	 *
	 * @var	ProjectFactory
	 */
	protected $oFactory;

	/**
	 * (non-PHPdoc)
	 * @see Core_Controller_Action::init()
	 */
	public function init()
	{
		// wymagane uprawnienia
		$this->setAcl(array(
			'index'
		));

		parent::init();

		$this->oFactory = new ProjectFactory();
	}

	/**
	 * Wyświetla liste projektów usera
	 */
	public function indexAction()
	{
		$iPage = $this->_request->getParam('page', 1);

		if($iPage < 1)
		{
			$this->moveTo404();
		}

		$oPaginator = $this->oFactory->getUserPaginator($this->oUser, $iPage, 20);

		if($oPaginator->count() > 0 && $iPage > $oPaginator->count())
		{
			$this->moveTo404();
		}

		$this->view->assign('oPaginator', $oPaginator);
	}
}