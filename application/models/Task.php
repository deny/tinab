<?php

/**
 * Klasa zadanai
 */
class Task extends Core_DataObject
{
	/**
	 * Statusy zadania
	 *
	 * @var	string
	 */
	const STATUS_NEW = 'new';
	const STATUS_ACTIVE = 'active';
	const STATUS_SUSPEND = 'suspend';
	const STATUS_TEST = 'test';
	const STATUS_CR = 'code_review';
	const STATUS_TO_ACC = 'to_accept';
	const STATUS_ACCEPTED = 'accepted';
	const STATUS_FINISHED = 'finished';

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
	protected $iRespUserId = null;

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
	 * Status zadania
	 *
	 * @var	string
	 */
	protected $sStatus;

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
	 * @param	string	$sStatus	status zadania
	 * @param	array	$aTags		tablica z tagami
	 * @param	array	$aPreload	tablica z preloadem
	 * @return	Task
	 */
	public function __construct($iId, $iProjectId, $iRespUser, $iEnvId, $iPos,
								$sTask, $sStatus, $aTags, array $aPreload = array())
	{
		parent::__construct('tasks', array('task_id' => $iId));

		$this->iId = $iId;
		$this->iProjectId = $iProjectId;
		$this->iRespUserId = $iRespUser;
		$this->iEnvId = $iEnvId;
		$this->iPos = $iPos;
		$this->sTask = $sTask;
		$this->sStatus = $sStatus;
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
	public function getRespUserId()
	{
		return $this->iRespUserId;
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
	 * Zwraca status zadania
	 *
	 * @return	string
	 */
	public function getStatus()
	{
		return $this->sStatus;
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
	 * @param	int	$iRespUserId	id usera
	 * @return	void
	 */
	public function setRespUserId($iRespUserId)
	{
		$this->iRespUserId = $iRespUserId;
		$this->setDataValue('resp_user', $iRespUserId);
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
	 * Ustawia nowy status zadania
	 *
	 * @param	string	$sStatus	nowy satus
	 * @return	void
	 */
	public function setStatus($sStatus)
	{
		$this->sStatus = $sStatus;
		$this->setDataValue('task_status', $sStatus);
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

