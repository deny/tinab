<?php

/**
 * Fabryka grup
 */
class GroupFactory extends Core_DataObject_Factory
{
	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		parent::__construct('groups', array('group_id'));
	}

	/**
	 * Tworzy 
	 *
	 * @param	string	$sName			nazwa grupy	
	 * @param	int		$iProjectId		id projektu
	 * @return	Group
	 */
	public function create($sName, $iProjectId = null)
	{
		$aData = array(
			'name' => $sName,	
			'project_id' => $iProjectId,	
		);
		
		$this->oDb->insert($this->getTableName(), $aData);
		$aData['group_id'] = $this->oDb->lastInsertId('groups', 'group_id');
	
		return $this->createObject($aData);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::createObject()
	 */
	protected function createObject(array $aRow, $mOption = null)
	{
		return new Group(
			$aRow['group_id'],
			$aRow['name'],
			$aRow['project_id']
		);
	}
}

