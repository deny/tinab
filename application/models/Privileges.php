<?php

/**
 * Klasa abstrakcyjna ze zbiorem uprawnień
 */
abstract class Privileges
{
	// uprawnienia globalne
	const ADMIN 		= 'adm';
	const PROJ_CRATE 	= 'proj_crate';

	// uprawnienia projektowe
	const PROJ_ADM 		= 'proj_adm';

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
		self::PROJ_ADM 		=> 'administracja projektem'
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
	 * Zwraca opisy uprawnień
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