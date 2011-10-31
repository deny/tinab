<?php

/**
 * Obiekt grupy
 */
class Group extends Core_DataObject
{
	/**
	 * Id grupy
	 *
	 * @var int
	 */
	protected $iId;

	/**
	 * Nazwa grupy
	 *
	 * @var string
	 */
	protected $sName;

	/**
	 * Id projektu (jeśli brak to grupa globalna)
	 *
	 * @var int
	 */
	protected $iProjectId;

	/**
	 * Konstruktor
	 *
	 * @param	int		$iId			id grupy
	 * @param	string	$sName			nazwa grupy
	 * @param	int		$iProjectId		id projektu
	 * @return	Group
	 */
	public function __construct($iId, $sName, $iProjectId = null)
	{
		parent::__construct('groups', array('group_id' => $iId));

		$this->iId = $iId;
		$this->sName = $sName;
		$this->iProjectId = $iProjectId;
	}

// gettery

	/**
	 * Zwraca id grupy
	 *
	 * @return	int
	 */
	public function getId()
	{
		return $this->iGroupId;
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
	 * Ustawia id projektu
	 *
	 * @param	int		$iProjectId	nowe id projektu
	 * @return	void
	 */
	public function setProjectId($iProjectId)
	{
		$this->iProjectId = $iProjectId;
		$this->setDataValue('project_id', $iProjectId);
	}

}

