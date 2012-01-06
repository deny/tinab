<?php

/**
 * Fabryka zdań
 */
class TaskFactory extends Core_DataObject_Factory
{
	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		parent::__construct('tasks', array('task_id'));
	}

	/**
	 * Tworzy nowe zadanie
	 *
	 * @param	Project	$oProject	obiekt projektu
	 * @param	string	$sTask		opis zadania
	 * @param	array	$aTags		dodatkowe tagi
	 * @param	int		$iUserId	id usera odpowiedzialnego
	 * @return	Task
	 */
	public function create(Project $oProject, $sTask, $aTags, $iUserId = null)
	{
		$aData = array(
			'project_id'	=> $oProject->getId(),
			'resp_user' 	=> $iUserId,
			'env_id' 		=> null,
			'pos' 			=> 0,
			'task' 			=> $sTask,
			'task_status'	=> Task::STATUS_NEW,
			'tags'	 		=> implode('|', $aTags),
		);

		$this->oDb->insert($this->getTableName(), $aData);
		$aData['task_id'] = $this->oDb->lastInsertId('tasks', 'task_id');

		return $this->createObject($aData);
	}

	/**
	 * Zwraca listę tasków w danym projekcie
	 *
	 * @param 	Project $oProject	obiekt projektu
	 * @return	array
	 */
	public function getActiveList(Project $oProject)
	{
		return $this->getFromWhere(
			new Core_DataObject_Where('task_status != ?', Task::STATUS_FINISHED),
			array('task_status', 'pos')
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::createObject()
	 */
	protected function createObject(array $aRow, $mOption = null)
	{
		return new Task(
			$aRow['task_id'],
			$aRow['project_id'],
			$aRow['resp_user'],
			$aRow['env_id'],
			$aRow['pos'],
			$aRow['task'],
			$aRow['task_status'],
			explode('|', $aRow['tags'])
		);
	}
}

