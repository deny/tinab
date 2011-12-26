<?php

/**
 * Fabryka środowisk
 */
class EnvironmentFactory extends Core_DataObject_Factory
{
	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		parent::__construct('environments', array('env_id'));
	}

	/**
	 * Tworzy nowe środowisko
	 *
	 * @param	string	$sName	nazwa środowiska
	 * @return	Environment
	 */
	public function create($sName)
	{
		$aData = array(
			'env_pos' 	=> 0,
			'name' 		=> $sName,
		);

		$this->oDb->insert($this->getTableName(), $aData);
		$aData['env_id'] = $this->oDb->lastInsertId('environments', 'env_id');

		return $this->createObject($aData);
	}

	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::createObject()
	 */
	protected function createObject(array $aRow, $mOption = null)
	{
		return new Environment(
			$aRow['env_id'],
			$aRow['env_pos'],
			$aRow['name']
		);
	}
}

