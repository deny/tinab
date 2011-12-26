<?php

/**
 * Klasa funkcjonalności
 */
class Feature extends Core_DataObject
{
	/**
	 *
	 *
	 * @var string
	 */
	const STATUS_AVTIVE = 'avtive';
	const FINISHED = 'finished';

	/**
	 * Id funkcjonalności
	 *
	 * @var int
	 */
	protected $iId;

	/**
	 * Pozycja na liście
	 *
	 * @var int
	 */
	protected $iPos;

	/**
	 * Status
	 *
	 * @var string
	 */
	protected $sStatus;

	/**
	 * Nazwa
	 *
	 * @var string
	 */
	protected $sName;

	/**
	 * Opis
	 *
	 * @var string
	 */
	protected $sDesc;

	/**
	 * Id projektu
	 *
	 * @var int
	 */
	protected $iProjectId;

	/**
	 * Konstruktor
	 *
	 * @param	int		$iId		id funkcjonalności
	 * @param	int		$iPos		pozycja na liście
	 * @param	string	$sStatus	status
	 * @param	string	$sName		nazwa
	 * @param	string	$sDesc		opis
	 * @param	int		$iProjectId	id projektu
	 * @param	array	$aPreload	tablica z preloadem
	 * @return	Feature
	 */
	public function __construct($iId, $iPos, $sStatus, $sName, $sDesc, $iProjectId)
	{
		parent::__construct('features', array('feature_id' => $iId));

		$this->iFeatureId = $iFeatureId;
		$this->iFeaturePos = $iFeaturePos;
		$this->sFeatureStatus = $sFeatureStatus;
		$this->sName = $sName;
		$this->sDesc = $sDesc;
		$this->iProjectId = $iProjectId;
	}

// gettery

	/**
	 * Zwraca id funkcjonalności
	 *
	 * @return	int
	 */
	public function getId()
	{
		return $this->iId;
	}

	/**
	 * Zwraca pozycję
	 *
	 * @return	int
	 */
	public function getPos()
	{
		return $this->iPos;
	}

	/**
	 * Zwraca status
	 *
	 * @return	string
	 */
	public function getStatus()
	{
		return $this->sStatus;
	}

	/**
	 * Zwraca nazwę
	 *
	 * @return	string
	 */
	public function getName()
	{
		return $this->sName;
	}

	/**
	 * Zwraca opis
	 *
	 * @return	string
	 */
	public function getDesc()
	{
		return $this->sDesc;
	}

	/**
	 * Zwraca id projektu
	 *
	 * @return	int
	 */
	public function getProjectId()
	{
		return $this->iProjectId;
	}

// settery

	/**
	 * Ustawia pozycję
	 *
	 * @param	int		$iPos	nowa pozycja
	 * @return	void
	 */
	public function setPos($iPos)
	{
		$this->iPos = $iPos;
		$this->setDataValue('feature_pos', $iPos);
	}

	/**
	 * Ustawia status
	 *
	 * @param	string	$sStatus	nowy status
	 * @return	void
	 */
	public function setStatus($sStatus)
	{
		$this->sStatus = $sStatus;
		$this->setDataValue('feature_status', $sStatus);
	}

	/**
	 * Ustawia nazwę
	 *
	 * @param	string	$sName	nowa nazwa
	 * @return	void
	 */
	public function setName($sName)
	{
		$this->sName = $sName;
		$this->setDataValue('name', $sName);
	}

	/**
	 * Ustawia opis
	 *
	 * @param	string	$sDesc	nowy opis
	 * @return	void
	 */
	public function setDesc($sDesc)
	{
		$this->sDesc = $sDesc;
		$this->setDataValue('desc', $sDesc);
	}

	/**
	 * Ustawia id projektu
	 *
	 * @param	int	$iProjectId
	 * @return	void
	 */
	public function setProjectId($iProjectId)
	{
		$this->iProjectId = $iProjectId;
		$this->setDataValue('project_id', $iProjectId);
	}

}

