<?php

/**
 * Fabryka projektów
 */
class ProjectFactory extends Core_DataObject_Factory
{
	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		parent::__construct('projects', array('project_id'));
	}

	/**
	 * Tworzy nowy projekt
	 *
	 * @param	string	$sName		nazwa projektu	
	 * @param	int		$iAuthorId	id autora
	 * @return	Project
	 */
	public function create($sName, $iAuthorId)
	{
		$aData = array(
			'name' 			=> $sName,	
			'author_id' 	=> $iAuthorId,	
			'creation_time' => $_SERVER['REQUEST_TIME']	
		);
		
		$this->oDb->insert($this->getTableName(), $aData);
		$aData['project_id'] = $this->oDb->lastInsertId('projects', 'project_id');
	
		return $this->createObject($aData);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::createObject()
	 */
	protected function createObject(array $aRow, $mOption = null)
	{
		return new Project(
			$aRow['project_id'],
			$aRow['name'],
			$aRow['author_id'],
			$aRow['creation_time']
		);
	}
}

