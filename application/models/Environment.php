<?php

/**
 * Środowisko produkcyjne
 */
class Environment extends Core_DataObject
{
	/**
	 * Id środowiska
	 *
	 * @var int
	 */
	protected $iId;

	/**
	 * Pozycja środowiska
	 *
	 * @var int
	 */
	protected $iPos;

	/**
	 * Nazwa środowiska
	 *
	 * @var string
	 */
	protected $sName;

	/**
	 * Konstruktor
	 *
	 * @param	int		$iId		id środowiska
	 * @param	int		$iEnvPos	pozycja środowiska
	 * @param	string	$sName		nazwa środowiska
	 * @return	Environment
	 */
	public function __construct($iId, $iPos, $sName)
	{
		parent::__construct('environments', array('env_id' => $iId));

		$this->iId = $iId;
		$this->iPos = $iPos;
		$this->sName = $sName;
	}

// gettery

	/**
	 * Zwraca id środowiska
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
	 * Zwraca nazwę środowiska
	 *
	 * @return	string
	 */
	public function getName()
	{
		return $this->sName;
	}

// settery

	/**
	 * Ustawia pozycję środowiska
	 *
	 * @param	int	$iPos	pozycja środowiska
	 * @return	void
	 */
	public function setPos($iPos)
	{
		$this->iPos = $iPos;
		$this->setDataValue('env_pos', $iPos);
	}

	/**
	 * Ustawia nazwę środowiska
	 *
	 * @param	string	$sName	nowa nazwa
	 * @return	void
	 */
	public function setName($sName)
	{
		$this->sName = $sName;
		$this->setDataValue('name', $sName);
	}

}

