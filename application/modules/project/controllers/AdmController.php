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
					$aValues['desc'],
					$this->oUser
				);

				$this->addMessage('Projekt został utworzony', self::MSG_OK);
				$this->_redirect('/project/adm/show/id/' . $oProject->getId());
				exit();
			}

			$this->showFormMessages($oFilter);
		}

		$this->_helper->viewRenderer('form-project');
		$this->view->assign('bEdit', false);
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
				$oProject->setDesc($aValues['desc']);
				$oProject->save();

				$this->addMessage('Projekt został zmieniony', self::MSG_OK);
				$this->_redirect('/project/adm/show/id/' . $oProject->getId());
				exit();
			}

			$this->showFormMessages($oFilter);
		}
		else
		{
			$this->view->assign('aValues', array(
				'name'	=> $oProject->getName(),
				'desc'	=> $oProject->getDesc()
			));
		}

		$this->_helper->viewRenderer('form-project');
		$this->view->assign('bEdit', true);
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
				new Core_Validate_StringLength(array('min' => 1, 'max' => 255)),
				new Core_Validate_Callback(array(
					'message' => 'Projekt o takiej nazwie już istnieje',
					'options' => array($oProject),
					'callback' => function($sValue, $oProject) {

						if(isset($oProject) && $oProject->getName() == $sValue)
						{
							return true;
						}

						$aDbRes = Zend_Registry::get('db')->select()
										->from('projects', 'project_id')
										->where('name = ?', $sValue)
										->limit(1)->query()->fetchAll(Zend_Db::FETCH_COLUMN);

						return empty($aDbRes);
					}
				))
			),
			'desc' => array(
				new Core_Validate_StringLength(array('min' => 1, 'max' => 255))
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}