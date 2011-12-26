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
	 * @param	User	$oUser		user odpowiedzialny
	 * @return	Task
	 */
	public function create(Project $oProject, $sTask, $aTags, User $oUser = null)
	{
		$aData = array(
			'project_id'	=> $oProject->getId(),
			'resp_user' 	=> isset($oUser) ? $oUser->getId() : null,
			'env_id' 		=> null,
			'pos' 			=> 0,
			'task' 			=> $sTask,
			'tags'	 		=> implode('|', $aTags),
		);

		$this->oDb->insert($this->getTableName(), $aData);
		$aData['task_id'] = $this->oDb->lastInsertId('tasks', 'task_id');

		return $this->createObject($aData);
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
			explode('|', $aRow['tags'])
		);
	}
}

