<?php

/**
 * Klasa projektu
 */
class Project extends Core_DataObject
{
	/**
	 * Id projektu
	 *
	 * @var int
	 */
	protected $iId;

	/**
	 * Nazwa projektu
	 *
	 * @var string
	 */
	protected $sName;

	/**
	 * Autor projektu
	 *
	 * @var int
	 */
	protected $iAuthorId;

	/**
	 * Czas utworzenia 
	 *
	 * @var int
	 */
	protected $iCreationTime;

	/**
	 * Konstruktor
	 * 
	 * @param	int		$iId			id projektu	
	 * @param	string	$sName			nazwa projektu
	 * @param	int		$iAuthorId		autor projektu
	 * @param	int		$iCreationTime	czas utworzenia	
	 * @return	Project
	 */
	public function __construct($iId, $sName, $iAuthorId, $iCreationTime)
	{
		parent::__construct('projects', array('project_id' => $iId));

		$this->iId = $iId;
		$this->sName = $sName;
		$this->iAuthorId = $iAuthorId;
		$this->iCreationTime = $iCreationTime;
	}

// gettery

	/**
	 * Zwraca id projektu
	 *
	 * @return	int
	 */
	public function getId()
	{
		return $this->iId;
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
	 * Zwraca id autora
	 *
	 * @return	int
	 */
	public function getAuthorId()
	{
		return $this->iAuthorId;
	}

	/**
	 * Zwraca czas utworzenia
	 *
	 * @return	int
	 */
	public function getCreationTime()
	{
		return $this->iCreationTime;
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
	 * Ustawia id autora
	 *
	 * @param	int		$iAuthorId	nowe id
	 * @return	void
	 */
	public function setAuthorId($iAuthorId)
	{
		$this->iAuthorId = $iAuthorId;
		$this->setDataValue('author_id', $iAuthorId);
	}
}

