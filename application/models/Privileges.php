<?php

/**
 * Klasa abstrakcyjna ze zbiorem uprawnień
 */
abstract class Privileges
{
	// uprawnienia globalne
	const ADMIN 		= 'adm';					// administracja
	const PROJ_CRATE 	= 'proj_crate';				// tworzenie projektów

	// uprawnienia projektowe
	const PROJ_ADM 			= 'proj_adm';			// administracja projektem
	const PROJ_TASKLIST 	= 'proj_tasklist';		// tworzenie/edycja/usuwanie list zadań
	const PROJ_TASK 		= 'proj_task';			// tworzenie/edycja/usuwanie zadań
	const PROJ_TASK_ALOC 	= 'proj_task_aloc';		// przydzielanie zadań

	/**
	 * Opisy globalnych uprawnień
	 *
	 * @var	array
	 */
	protected static $aGlobalDesc = array(
		self::ADMIN 		=> 'administracja',
		self::PROJ_CRATE 	=> 'tworzenie projektów',
	);

	/**
	 * Opisy projektowych uprawnień
	 *
	 * @var	array
	 */
	protected static $aProjectDesc = array(
		self::PROJ_ADM 			=> 'administracja projektem',
		self::PROJ_TASKLIST		=> 'tworzenie/edycja/usuwanie list zadań',
		self::PROJ_TASK			=> 'tworzenie/edycja/usuwanie zadań',
		self::PROJ_TASK_ALOC	=> 'przydzielanie zadań'
	);

	/**
	 * Zwraca listę globalnych uprawnień wraz z ich opisami
	 *
	 * @return	array
	 */
	static public function getGlobal()
	{
		return self::$aGlobalDesc;
	}

	/**
	 * Zwraca listę projektowych uprawnień wraz z ich opisami
	 *
	 * @return	array
	 */
	static public function getProject()
	{
		return self::$aProjectDesc;
	}

	/**
	 * Zwraca opisy wszystkich uprawnień
	 *
	 * @return	array
	 */
	static public function getDescriptions()
	{
		return self::$aGlobalDesc + self::$aProjectDesc;
	}

	/**
	 * Zwraca tablicę z uprawnieniami grupy
	 *
	 * @param 	Group	$oGroup	obiekt grupy
	 * @return	array
	 */
	static public function getGroupPrivileges(Group $oGroup)
	{
		$oDb = Zend_Registry::get('db');

		$aDbRes = $oDb->select()
							->from('group_privileges', 'privilege')
							->where('group_id = ?', $oGroup->getId())
							->distinct()->query()->fetchAll(Zend_Db::FETCH_COLUMN);

		return $aDbRes;
	}

	/**
	 * Zwraca tablicę z uprawnieniami usera dla poszczególnego projektu
	 *
	 * @param 	User 			$oUser			obiekt usera
	 * @param	int|null|array	$mProjectId		id projektu/tablica id projektów
	 * @return	array
	 */
	static public function getUserPrivileges(User $oUser, $mProjectId = null)
	{
		$oDb = Zend_Registry::get('db');

		$oSelect = $oDb->select()
							->from('group_privileges AS gp', 'gp.privilege')
							->join('groups AS g', 'g.group_id = gp.group_id', '')
							->join('user_groups AS ug', 'ug.group_id = g.group_id', '')
							->where('ug.user_id = ?', $oUser->getId());

		if(is_array($mProjectId)) // jeśli podano tablicę z numerami ID
		{
			if(empty($mProjectId))
			{
				return array();
			}

			$oSelect->reset(Zend_Db_Select::COLUMNS)
						->columns(array('g.project_id', 'gp.privilege'))
						->where('g.project_id IS NULL OR g.project_id IN (?)', $mProjectId);
			$aDbRes = $oSelect->distinct()->query()->fetchAll();

			$aResult = array_fill_keys($mProjectId, array());
			$aResult['global'] = array();
			foreach($aDbRes as $aRow)
			{
				$sKey = $aRow['project_id'] === null ? 'global' : $aRow['project_id'];
				$aResult[$sKey][] = $aRow['privilege'];
			}

			return $aResult;
		}
		elseif(isset($mProjectId)) // jeśli podano projekt to wyjmujemy globalne + projektowe
		{
			$oSelect->where('g.project_id IS NULL OR g.project_id = ?', $mProjectId);
		}
		else
		{
			$oSelect->where('g.project_id IS NULL');
		}

		$aDbRes = $oSelect->distinct()->query()->fetchAll(Zend_Db::FETCH_COLUMN);

		return $aDbRes;
	}
}