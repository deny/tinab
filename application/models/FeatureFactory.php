<?php

/**
 * Fabryka funkcjonalności
 */
class FeatureFactory extends Core_DataObject_Factory
{
	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		parent::__construct('features', array('feature_id'));
	}

	/**
	 * Tworzy
	 *
	 * @param	int	$iFeaturePos
	 * @param	string	$sFeatureStatus
	 * @param	string	$sName
	 * @param	string	$sDesc
	 * @param	int	$iProjectId
	 * @return	Feature
	 */
	public function create($iFeaturePos, $sFeatureStatus, $sName, $sDesc, $iProjectId)
	{
		$aData = array(
			'feature_pos' => $iFeaturePos,
			'feature_status' => $sFeatureStatus,
			'name' => $sName,
			'desc' => $sDesc,
			'project_id' => $iProjectId,
		);

		$this->oDb->insert($this->getTableName(), $aData);
		$aData['feature_id'] = $this->oDb->lastInsertId('features', 'feature_id');

		return $this->createObject($aData);
	}

	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::createObject()
	 */
	protected function createObject(array $aRow, $mOption = null)
	{
		return new Feature(
			$aRow['feature_id'],
			$aRow['feature_pos'],
			$aRow['feature_status'],
			$aRow['name'],
			$aRow['desc'],
			$aRow['project_id']
		);
	}
}

