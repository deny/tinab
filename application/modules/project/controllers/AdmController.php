<?php

/**
 * Administracja projektem
 */
class Project_AdmController extends Core_Controller_Action
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
		$this->iProjectId = $this->_request->getParam('id', null);

		// wymagane uprawnienia
		$this->setAcl(array('add'), array(Privileges::PROJ_CRATE));
		$this->setAcl(array('show', 'edit', 'delete'), array(Privileges::PROJ_ADM));

		parent::init();

		$this->oFactory = new ProjectFactory();
	}

	/**
	 * Administracja projektem
	 */
	public function showAction()
	{
		$oProject = $this->getItem();
		$this->view->assign('oProject', $oProject);
	}

	/**
	 * Tworzenie nowego projektu
	 */
	public function addAction()
	{
		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter();

			if($oFilter->isValid())
			{
				$aValues = $oFilter->getEscaped();

				$oProject = $this->oFactory->create(
					$aValues['name'],
					$this->oUser
				);

				$this->addMessage('Projekt został utworzony', self::MSG_OK);
				$this->_redirect('/project/adm/show/id/' . $oProject->getId());
				exit();
			}

			$this->showFormMessages($oFilter);
		}
	}

	/**
	 * Edycja projektu
	 */
	public function editAction()
	{
		$oProject = $this->getItem();

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter($oProject);

			if($oFilter->isValid())
			{
				$aValues = $oFilter->getEscaped();

				$oProject->setName($aValues['name']);
				$oProject->save();

				$this->addMessage('Projekt został zmieniony', self::MSG_OK);
				$this->_redirect('/project/adm/show/id/' . $oProject->getId());
				exit();
			}

			$this->showFormMessages($oFilter);
		}
	}

	/**
	 * Usuwanie projektu
	 */
	public function deleteAction()
	{
		$oProject = $this->getItem();

		$oProject->delete();

		$this->addMessage('Projekt został usunięty', self::MSG_OK);
		$this->_redirect('/project');
	}

// INNE

	/**
	 * Zwraca obiekt do edycji/usuwania
	 *
	 * @return	Project
	 */
	protected function getItem()
	{
		try
		{
			$iId = $this->_request->getParam('id', 0);
			$oItem = $this->oFactory->getOne($iId);
		}
		catch(Core_DataObject_Exception $oExc)
		{
			$this->addMessage('Wybrany elment nie istnieje', self::MSG_ERROR);
			$this->_redirect('/project');
			exit();
		}

		return $oItem;
	}

// FILTRY

	public function getFilter(Project $oProject = null)
	{
		$aValues = $this->_request->getPost();

    	// walidatory
		$aValidators = array(
			'name' => array(
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}