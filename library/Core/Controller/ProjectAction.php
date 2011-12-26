<?php

/**
 * Podstawa dla kontrolerów w projekcie
 */
class Core_Controller_ProjectAction extends Core_Controller_Action
{
	/**
	 * Obiekt projektu
	 *
	 * @var	Project
	 */
	protected $oProject;

	/**
	 * (non-PHPdoc)
	 * @see Core_Controller_Action::init()
	 */
	public function init()
	{
		$this->iProjectId = $this->_request->getParam('id', 0);

		try
		{
			$this->oProject = ProjectFactory::getNew()->getOne($this->iProjectId);
		}
		catch(Core_DataObject_Exception $oExc)
		{
			$this->moveTo404();
		}

		$this->view->assign('oProject', $this->oProject);
		$this->view->assign('iProjectId', $this->iProjectId);

		parent::init();
	}
}