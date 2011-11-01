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
	 * @param	string	$sDesc			opis
	 * @return	Group
	 */
	public function create($sName, $sDesc, $iProjectId = null)
	{
		$aData = array(
			'name' 			=> $sName,
			'project_id' 	=> $iProjectId,
			'desc'			=> $sDesc
		);

		$this->oDb->insert($this->getTableName(), $aData);
		$aData['group_id'] = $this->oDb->lastInsertId('groups', 'group_id');

		return $this->createObject($aData);
	}

	/**
	 * Zwraca paginator z grupami dla wybranego projektu
	 *
	 * @param	int		$iPage		numer strony
	 * @param	int		$iCount		ilość elementów na stronę
	 * @param	int		$iProjectId	id projektu
	 * @return	Zend_Paginator
	 */
	public function getProjectPaginator($iPage, $iCount, $iProjectId = null)
	{
		if($iProjectId === null)
		{
			$oWhere = new Core_DataObject_Where('project_id IS NULL');
		}
		else
		{
			$oWhere = new Core_DataObject_Where('project_id = ?', $iProjectId);
		}

		return parent::getPaginator($iPage, $iCount, array('name'), $oWhere, array('projPag'));
	}

	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::getCountSelect()
	 */
	public function getCountSelect($mOption = null)
	{
		if(is_array($mOption) && in_array('projPag', $mOption))
		{
			return parent::getSelect()
							->reset(Zend_Db_Select::COLUMNS)
							->columns(new Zend_Db_Expr('COUNT(*)'));
		}
		else
		{
			return parent::getCountSelect();
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::getSelect()
	 */
	public function getSelect($mFields = '*', $mOption = null)
	{
		$oSelect = parent::getSelect($mFields, $mOption);

		if(is_array($mOption) && in_array('projPag', $mOption))
		{
			$oSelect->joinLeft(
				'group_privileges AS gp',
				'groups.group_id = gp.group_id',
				new Zend_Db_Expr("GROUP_CONCAT(DISTINCT gp.privilege SEPARATOR ';') AS priv")
			)->joinLeft(
				'user_groups AS ug',
				'groups.group_id = ug.group_id',
				new Zend_Db_Expr('COUNT(ug.user_id) AS users_count')
			)->group('groups.group_id');
		}

		return $oSelect;
	}


	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::createObject()
	 */
	protected function createObject(array $aRow, $mOption = null)
	{
		$aPreload = array();
		if(is_array($mOption) && in_array('projPag', $mOption))
		{
			$aPreload = array(
				'privileges' 	=> explode(';', $aRow['priv']),
				'users_count'	=> $aRow['users_count']
			);
		}

		return new Group(
			$aRow['group_id'],
			$aRow['name'],
			$aRow['desc'],
			$aRow['project_id'],
			$aPreload
		);
	}
}

