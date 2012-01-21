<?php

/**
 * Zadania w projekcie
 */
class Project_TasksController extends Core_Controller_ProjectAction
{
	/**
	 * Fabryka zdań
	 *
	 * @var	TaskFactory
	 */
	protected $oFactory;

	/**
	 * (non-PHPdoc)
	 * @see Core_Controller_Action::init()
	 */
	public function init()
	{
		// wymagane uprawnienia
		$this->setAcl(
			array(
				'index', 'index-env', 'add', 'edit'
			),
			array(Privileges::PROJ_TASK)
		);

		parent::init();

		$this->oFactory = new TaskFactory();
	}

	/**
	 * Lista zadań w projecie
	 */
	public function indexAction()
	{
		$this->view->assign('aTasks', $this->oFactory->getActiveList($this->oProject));
		$this->view->assign('aUsers', $this->getProjectUsers());
		$this->view->assign('aEnvs', $this->getEnvs());
	}

	/**
	 * Lista zadań wg środowiska
	 */
	public function indexEnvAction()
	{
		$this->view->assign('aTasks', $this->oFactory->getActiveList(
			$this->oProject,
			array('env_pos', 'pos'),
			array('env' => true)
		));
		$this->view->assign('aUsers', $this->getProjectUsers());
		$this->view->assign('aEnvs', $this->getEnvs());
	}

	/**
	 * Obsługa dodawania taska
	 */
	public function addAction()
	{
//		// @todo zamienić na wersję w ajax
//
//		if($this->_request->isPost())
//		{
//			$oFilter = $this->getFilter();
//
//			if($oFilter->isValid())
//			{
//				$aValues = $oFilter->getEscaped();
//
//				$this->oFactory->create(
//					$this->oProject,
//					$aValues['task'],
//					explode(',', $aValues['labels']),
//					empty($aValues['users']) ? null : (int) $aValues['users']
//				);
//
//				$this->addMessage('Zadanie zostało dodane', self::MSG_OK);
//				$this->_redirect('/project/tasks/index/id/'. $this->oProject->getId());
//				exit();
//
//			}
//
//			// obróbka komunikatów błędów
//			$aMsg = $oFilter->getMessages();
//			$aTmp = array();
//			foreach($aMsg as $sField => $aField)
//			{
//				$aTmp[$sField] = array();
//				foreach($aField as $sError)
//				{
//					$aTmp[$sField][] = $sError;
//				}
//			}
//
//			// @todo zwracamy JSON-em do skryptu JS
//			$sMsg = '';
//			foreach($aTmp as $sField => $aMsgs)
//			{
//				if(!empty($aMsgs))
//				{
//					$sMsg = $sField . ': ' . implode(', ', $aMsgs);
//				}
//			}
//
//			$this->addMessage($sMsg, self::MSG_ERROR, true);
//		}
//
//		$this->_forward('index');
	}

	/**
	 * Obsługa dodawania taska
	 */
	public function editAction()
	{
		// @todo zamienić na wersję w ajax
		$oTask = $this->getTask();

		if($this->_request->isPost())
		{
			$oFilter = $this->getFilter($oTask);

			if($oFilter->isValid())
			{
				$aValues = $oFilter->getEscaped();

				$oTask->setTask($aValues['task']);
				$oTask->setRespUserId(empty($aValues['users']) ? null : (int) $aValues['users']);
				$oTask->setEnvId(isset($aValues['env']) ? (int) $aValues['env'] : null);
				$oTask->setTags(explode(',', $aValues['labels']));
				$oTask->setStatus($aValues['status']);
				$oTask->save();

				$this->addMessage('Zadanie zostało zapisane', self::MSG_OK);
				$this->_redirect('/project/tasks/index/id/'. $this->oProject->getId());
				exit();
			}

			// @todo zwracamy JSON-em do skryptu JS
			$this->showFormMessages($oFilter);
		}
		else // @todo po przerobieniu na ajax wywalić
		{
			$this->view->assign('aValues', array(
				'task' 	=> $oTask->getTask(),
				'status'=> $oTask->getStatus(),
				'users'	=> $oTask->getRespUserId(),
				'labels'=> implode(', ', $oTask->getTags()),
				'env'	=> $oTask->getEnvId()
			));
		}

		$this->view->assign('oTask', $oTask);
		$this->view->assign('aUsers', $this->getProjectUsers());
		$this->view->assign('aEnvs', $this->getEnvs());
	}

	/**
	 * Zwraca obiekt taska
	 *
	 * @return	Task
	 */
	protected function getTask()
	{
		try
		{
			$iId = $this->_request->getParam('task', 0);
			$oTask = $this->oFactory->getOne($iId);
		}
		catch(Core_DataObject_Exception $oExc)
		{
			$this->moveTo404();
		}

		return $oTask;
	}

	/**
	 * Zwraca filtr do walidacji tasków
	 *
	 * @param	Task	$oTask	edytowny task
	 * @return	Core_Filter_Input
	 */
	protected function getFilter($oTask = null)
	{
		$aValues = $this->_request->getPost();

    	// walidatory
		$aValidators = array(
			'task' => array(
				new Core_Validate_StringLength(array('min' => 1, 'max' => 255)),
			),
			'labels' => array(
				'allowEmpty' => true
			),
			'users' => array(
				'allowEmpty' => true,
				new Core_Validate_InArray(array_keys($this->getProjectUsers()))
			)
		);

		if(isset($oTask)) // jeśli edycja
		{
			$aValidators  += array(
				'env' => array(
					new Core_Validate_InArray(array_keys($this->getEnvs()))
				),
				'status' => array(
					new Core_Validate_InArray(array_keys($this->view->task_Status()->getList()))
				)
			);
		}

		// dodatkowe filtrowanie tagów
		$aFilters = array(
			'labels' => array(
				new Zend_Filter_Callback(array(
					'callback' => function($sValue) {

						$aTags = explode(',', $sValue);
						foreach($aTags as $iKey => &$sValue)
						{
							$sValue = trim($sValue);
						}

						return implode(',', $aTags);
					}
				))
			)
		);

		// filtr
		return new Core_Filter_Input($aFilters, $aValidators, $aValues);
	}
}