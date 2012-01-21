<?php

/**
 * AJAX: Zadania
 */
class Ajax_TaskController extends Core_Controller_ProjectAction
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
				'get-one', 'create', 'save', 'delete'
			),
			array(Privileges::PROJ_TASK)
		);

		parent::init();

		$this->oFactory = new TaskFactory();
		$this->_helper->layout()->disableLayout();
	}

	public function getOneAction()
	{

		$iId = $this->_request->getParam('itemid', 0);

		$oTask = $this->oFactory->getOne($iId);

		exit(json_encode($this->toRaw($oTask)));
	}

	/**
	 * Obsługa dodawania taska
	 */
	public function createAction()
	{
		if(!$this->_request->isPost())
		{
			$this->moveTo404();
		}

		$oFilter = $this->getFilter();

		if($oFilter->isValid())
		{
			$aValues = $oFilter->getEscaped();

			$oTask = $this->oFactory->create(
				$this->oProject,
				$aValues['task'],
				explode(',', $aValues['labels']),
				empty($aValues['users']) ? null : (int) $aValues['users']
			);

			exit(json_encode(array('success' => true, 'object' => $this->toRaw($oTask))));
		}

		exit(json_encode(array('success' => false, 'msg' => $this->preapareMsg($oFilter))));
	}

	/**
	 * Obsługa zapisu taska
	 */
	public function saveAction()
	{
		if(!$this->_request->isPost())
		{
			$this->moveTo404();
		}

		$oTask = $this->getTask();

		$oFilter = $this->getFilter($oTask);

		if($oFilter->isValid())
		{
			$aValues = $oFilter->getEscaped();

			$oTask->setTask($aValues['task']);
			$oTask->setRespUserId(empty($aValues['users']) ? null : (int) $aValues['users']);
			$oTask->setEnvId(empty($aValues['env']) ? null : (int) $aValues['env']);
			$oTask->setTags(empty($aValues['labels']) ? array() : explode(',', $aValues['labels']));
			$oTask->setStatus($aValues['status']);
			$oTask->save();

			exit(json_encode(array('success' => true, 'object' => $this->toRaw($oTask))));
		}

		exit(json_encode(array('success' => false, 'msg' => $this->preapareMsg($oFilter))));
	}

	/**
	 * Obsługa usuwania taska
	 */
	public function deleteAction()
	{
		if(!$this->_request->isPost())
		{
			$this->moveTo404();
		}

		try
		{
			$oTask = $this->getTask();
			$oTask->delete();

			exit(json_encode(array('success' => true)));
		}
		catch(Exception $oExc) {}

		exit(json_encode(array('success' => false)));
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
			$iId = $this->_request->getParam('itemid', 0);
			$oTask = $this->oFactory->getOne($iId);
		}
		catch(Core_DataObject_Exception $oExc)
		{
			$this->moveTo404();
		}

		return $oTask;
	}

	/**
	 * Przygotowuje błędy do wysłania
	 *
	 * @param	Zend_Filter_Input	$oFilter	filtr
	 * @return	array
	 */
	protected function preapareMsg($oFilter)
	{
	// obróbka komunikatów błędów
		$aMsg = $oFilter->getMessages();
		$aTmp = array();
		foreach($aMsg as $sField => $aField)
		{
			$aTmp[$sField] = array();
			foreach($aField as $sError)
			{
				$aTmp[$sField][] = $sError;
			}
		}

		return $aTmp;
	}

	protected function toRaw(Task $oTask)
	{
		$aUser = null;
		if($oTask->getRespUserId() != null)
		{
			$aUsers = $this->getProjectUsers();
			$aUser = array(
				'id' 	=> $oTask->getRespUserId(),
				'name'	=> $aUsers[$oTask->getRespUserId()]
			);
		}

		$aEnv = null;
		if($oTask->getEnvId() != null)
		{
			$aEnvs = $this->getEnvs();
			$aEnv = array(
				'id'	=> $oTask->getEnvId(),
				'name'	=> $aEnvs[$oTask->getEnvId()]
			);
		}


		return array(
			'id'	=> $oTask->getId(),
			'task'	=> $oTask->getTask(),
			'env'	=> $aEnv,
			'status'=> $oTask->getStatus(),
			'labels'=> $oTask->getTags(),
			'user'	=> $aUser
		);

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
					'allowEmpty' => true,
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