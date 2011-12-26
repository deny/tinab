<?php

/**
 * Klasa zadanai
 */
class Task extends Core_DataObject
{
	/**
	 * Id zadania
	 *
	 * @var int
	 */
	protected $iId;

	/**
	 * Id projektu
	 *
	 * @var int
	 */
	protected $iProjectId;

	/**
	 * Id osoby przypisanej do zadania
	 *
	 * @var int
	 */
	protected $iRespUser = null;

	/**
	 * Id środowiska
	 *
	 * @var int
	 */
	protected $iEnvId = null;

	/**
	 * Pozycja taska na liście
	 *
	 * @var int
	 */
	protected $iPos;

	/**
	 * Opis zadania
	 *
	 * @var string
	 */
	protected $sTask;

	/**
	 * Tablica z tagami dodatkowymi
	 *
	 * @var	array
	 */
	protected $aTags;

	/**
	 * Konstruktor
	 *
	 * @param	int		$iId		id zadania
	 * @param	int		$iProjectId	id projektu
	 * @param	int		$iRespUser	odpowiedzialny usera
	 * @param	int		$iEnvId		id środowiska
	 * @param	int		$iPos		pozycja na liście
	 * @param	string	$sTask		opis zadania
	 * @param	array	$aTags		tablica z tagami
	 * @param	array	$aPreload	tablica z preloadem
	 * @return	Task
	 */
	public function __construct($iId, $iProjectId, $iRespUser, $iEnvId, $iPos, $sTask, $aTags, array $aPreload = array())
	{
		parent::__construct('tasks', array('task_id' => $iId));

		$this->iId = $iId;
		$this->iProjectId = $iProjectId;
		$this->iRespUser = $iRespUser;
		$this->iEnvId = $iEnvId;
		$this->iPos = $iPos;
		$this->sTask = $sTask;
		$this->aTags = $aTags;
	}

// gettery

	/**
	 * Zwraca id zadania
	 *
	 * @return	int
	 */
	public function getId()
	{
		return $this->iId;
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

	/**
	 * Zwraca id odpowiedzialnego usera
	 *
	 * @return	int
	 */
	public function getRespUser()
	{
		return $this->iRespUser;
	}

	/**
	 * Zwraca id środowiska
	 *
	 * @return	int
	 */
	public function getEnvId()
	{
		return $this->iEnvId;
	}

	/**
	 * Zwraca pozycję na liście
	 *
	 * @return	int
	 */
	public function getPos()
	{
		return $this->iPos;
	}

	/**
	 * Zwraca opis taska
	 *
	 * @return	string
	 */
	public function getTask()
	{
		return $this->sTask;
	}

	/**
	 * Zwraca tagi
	 *
	 * @return	array
	 */
	public function getTags()
	{
		return $this->aTags;
	}

// settery

	/**
	 * Ustawia odpowiedzialnego usera
	 *
	 * @param	int	$iRespUser	id usera
	 * @return	void
	 */
	public function setRespUser($iRespUser)
	{
		$this->iRespUser = $iRespUser;
		$this->setDataValue('resp_user', $iRespUser);
	}

	/**
	 * Ustawia środowisko
	 *
	 * @param	int		$iEnvId	id środowiska
	 * @return	void
	 */
	public function setEnvId($iEnvId)
	{
		$this->iEnvId = $iEnvId;
		$this->setDataValue('env_id', $iEnvId);
	}

	/**
	 * Ustawia pozycję
	 *
	 * @param	int		$iPos	pozycja
	 * @return	void
	 */
	public function setPos($iPos)
	{
		$this->iPos = $iPos;
		$this->setDataValue('pos', $iPos);
	}

	/**
	 * Ustawia opis taska
	 *
	 * @param	string	$sTask
	 * @return	void
	 */
	public function setTask($sTask)
	{
		$this->sTask = $sTask;
		$this->setDataValue('task', $sTask);
	}

	/**
	 * Ustawia tagi
	 *
	 * @param	array	$aTags
	 * @return	void
	 */
	public function setTags(array $aTags)
	{
		$this->aTags = $aTags;
		$this->setDataValue('tags', implode('|', $aTags));
	}

}

