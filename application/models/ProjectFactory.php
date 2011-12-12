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
	 * @param	User	$oUser		obiekt usera
	 * @return	Project
	 */
	public function create($sName, User $oUser)
	{
	// utworzenie nowego projektu
		$aData = array(
			'name' 			=> $sName,
			'author_id' 	=> $oUser->getId(),
			'creation_time' => $_SERVER['REQUEST_TIME']
		);

		$this->oDb->insert($this->getTableName(), $aData);
		$aData['project_id'] = $this->oDb->lastInsertId('projects', 'project_id');

		$oProject =  $this->createObject($aData);

	// utworzenie grupy administratorów projektu
		$oGroupF = new GroupFactory();
		$oAdmGroup = $oGroupF->create(
			'Administratorzy',
			'grupa administratorów projektu',
			$oProject->getId()
		);
		$oAdmGroup->setPrivileges(array(Privileges::PROJ_ADM));

	// utworzenie grupy zwykłych userów
		$oUsersGroup = $oGroupF->create(
			'Uczestnicy',
			'standardowi uczestnicy projektu',
			$oProject->getId()
		);
		$oUsersGroup->setPrivileges(array(
			Privileges::PROJ_TASKLIST, Privileges::PROJ_TASK, Privileges::PROJ_TASK_ALOC
		));

	// dodanie autora do utworzonych grup
		$oUser->resetGroups(
			array($oAdmGroup->getId(), $oUsersGroup->getId()),
			$oProject
		);

		return $oProject;
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

