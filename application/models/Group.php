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
	 * Opis grupy
	 *
	 * @var string
	 */
	protected $sDesc;

	/**
	 * Id projektu (jeśli brak to grupa globalna)
	 *
	 * @var int
	 */
	protected $iProjectId;

	/**
	 * Tablica z uprawnieniami grupy
	 *
	 * @var	array
	 */
	protected $aPrivileges = null;

	/**
	 * Ilość userów należących do grupy
	 *
	 * @var	int
	 */
	protected $iUsersCount = null;

	/**
	 * Konstruktor
	 *
	 * @param	int		$iId			id grupy
	 * @param	string	$sName			nazwa grupy
	 * @param	string	$sDesc			opis grupy
	 * @param	int		$iProjectId		id projektu
	 * @param	array	$aPreload		tablica z preloadem
	 * @return	Group
	 */
	public function __construct($iId, $sName, $sDesc, $iProjectId = null, $aPreload = array())
	{
		parent::__construct('groups', array('group_id' => $iId));

		$this->iId = $iId;
		$this->sName = $sName;
		$this->sDesc = $sDesc;
		$this->iProjectId = $iProjectId;

		if(isset($aPreload['privileges']))
		{
			$this->aPrivileges = $aPreload['privileges'];
		}

		if(isset($aPreload['users_count']))
		{
			$this->iUsersCount = $aPreload['users_count'];
		}
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
	 * Zwraca opis
	 *
	 * @return	string
	 */
	public function getDescription()
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

	/**
	 * Zwraca uprawnienia
	 *
	 * @return	array
	 */
	public function getPrivileges()
	{
		return $this->aPrivileges;
	}

	/**
	 * Zwraca liczników userów
	 *
	 * @return	int
	 */
	public function getUsersCount()
	{
		return $this->iUsersCount;
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
	 * Ustawia opis
	 *
	 * @param	string	$sDesc	nowy opis
	 * @return	void
	 */
	public function setDescription($sDesc)
	{
		$this->sDesc = $sDesc;
		$this->setDataValue('desc', $sDesc);
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

