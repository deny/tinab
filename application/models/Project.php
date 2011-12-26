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
	 * Opius projektu
	 *
	 * @var	string
	 */
	protected $sDesc;

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
	 * @param	strign	$sDesc			opis projektu
	 * @param	int		$iAuthorId		autor projektu
	 * @param	int		$iCreationTime	czas utworzenia
	 * @return	Project
	 */
	public function __construct($iId, $sName, $sDesc, $iAuthorId, $iCreationTime)
	{
		parent::__construct('projects', array('project_id' => $iId));

		$this->iId = $iId;
		$this->sName = $sName;
		$this->sDesc = $sDesc;
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
	 * Zwraca opis projektu
	 *
	 * @return	string
	 */
	public function getDesc()
	{
		return $this->sDesc;
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
	 * Ustawia opis projektu
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

// inne

	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject::delete()
	 */
	public function delete()
	{
		try
		{
			$this->oDb->delete('groups', 'project_id = ' .(int) $this->iId);
			parent::delete();
		}
		catch(Exception $oExc)
		{
			$this->oDb->rollBack();
			throw new $oExc;
		}
	}
}

