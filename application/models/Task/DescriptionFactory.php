<?php

/**
 * Fabryka opisów zadań
 */
class Task_DescriptionFactory extends Core_DataObject_Factory
{
	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		parent::__construct('task_descriptions', array('task_id', 'entry_date'));
	}

	/**
	 * Tworzy nowy opis do zadania
	 *
	 * @param	User	$oUser	obiekt usera
	 * @param	Task	$oTask	obiekt zadania
	 * @param	strign	$sText	opis
	 * @return	Task_Description
	 */
	public function create(User $oUser, Task $oTask, $sText)
	{
		$aData = array(
			'task_id' 		=> $oTask->getId(),
			'entry_date' 	=> $_SERVER['REQUEST_TIME'],
			'author_id' 	=> $oUser->getId(),
			'text' 			=> $sText,
		);

		$this->oDb->insert($this->getTableName(), $aData);

		return $this->createObject($aData);
	}

	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::createObject()
	 */
	protected function createObject(array $aRow, $mOption = null)
	{
		return new Task_Description(
			$aRow['task_id'],
			$aRow['entry_date'],
			$aRow['author_id'],
			$aRow['text']
		);
	}
}

