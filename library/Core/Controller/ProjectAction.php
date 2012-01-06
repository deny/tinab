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
	 * Uczestnicy projektu
	 *
	 * @var	array
	 */
	private $aUsers = null;

	/**
	 * Środowiska
	 *
	 * @var	array
	 */
	private $aEnvs = null;

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

	/**
	 * Zwraca listę członków projektu
	 *
	 * @return	array
	 */
	protected function getProjectUsers()
	{
		if($this->aUsers === null)
		{
			$this->aUsers =  UserFactory::getNew()->getForProject(
				$this->oProject,
				array(Privileges::PROJ_TASK),
				true
			);

			$this->aUsers[$this->oUser->getId()] = 'Ja';
		}

		return $this->aUsers;
	}

	/**
	 * Zwraca środowiska produkcyjne
	 *
	 * @return	array
	 */
	protected function getEnvs()
	{
		if($this->aEnvs === null)
		{
			$this->aEnvs = EnvironmentFactory::getNew()->getList();
		}

		return $this->aEnvs;
	}
}