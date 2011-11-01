<?php

/**
 * Klasa abstrakcyjna ze zbiorem uprawnień
 */
abstract class Privileges
{
	const ADMIN 		= 'adm';
	const PROJ_CRATE 	= 'proj_crate';
	const PROJ_ADM 		= 'proj_adm';

	/**
	 * Opisy uprawnień
	 *
	 * @var	array
	 */
	protected static $aDescriptions = array(
		self::ADMIN 		=> 'administracja',
		self::PROJ_CRATE 	=> 'tworzenie projektów',
		self::PROJ_ADM 		=> 'administracja projektem'
	);

	/**
	 * Zwraca opisy uprawnień
	 *
	 * @return	array
	 */
	static public function getDescriptions()
	{
		return self::$aDescriptions;
	}

	/**
	 * Zwraca tablicę z uprawnieniami usera dla poszczególnego projektu
	 *
	 * @param 	User 		$oUser			obiekt usera
	 * @param	int|null	$iProjectId		id projektu
	 * @return	array
	 */
	static public function getUserPrivileges(User $oUser, $iProjectId = null)
	{
		$oDb = Zend_Registry::get('db');

		$oSelect = $oDb->select()
							->from('group_privileges AS gp', 'gp.privilege')
							->join('groups AS g', 'g.group_id = gp.group_id', '')
							->join('user_groups AS ug', 'ug.group_id = g.group_id', '')
							->where('ug.user_id = ?', $oUser->getId());

		if(isset($iProjectId))
		{
			$oSelect->where('g.project_id = ?', $iProjectId);
		}
		else
		{
			$oSelect->where('g.project_id IS NULL');
		}

		$aDbRes = $oSelect->distinct()->query()->fetchAll(Zend_Db::FETCH_COLUMN);

		return $aDbRes;
	}
}