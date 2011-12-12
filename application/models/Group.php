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
	 * Czy grupa główna projektu
	 *
	 * @var	bool
	 */
	protected $bMain;

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
	 * Tablica na userów należących do grupy
	 *
	 * @var	array
	 */
	protected $aUsers = null;

	/**
	 * Konstruktor
	 *
	 * @param	int		$iId			id grupy
	 * @param	string	$sName			nazwa grupy
	 * @param	string	$sDesc			opis grupy
	 * @param	int		$iProjectId		id projektu
	 * @param	bool	$bMain			czy grupa główna projektu
	 * @param	array	$aPreload		tablica z preloadem
	 * @return	Group
	 */
	public function __construct($iId, $sName, $sDesc, $iProjectId = null, $bMain = false, $aPreload = array())
	{
		parent::__construct('groups', array('group_id' => $iId));

		$this->iId = $iId;
		$this->sName = $sName;
		$this->sDesc = $sDesc;
		$this->iProjectId = $iProjectId;
		$this->bMain = $bMain;

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
		if($this->aPrivileges === null)
		{
			$this->aPrivileges = Privileges::getGroupPrivileges($this);
		}

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

	/**
	 * Wyświetlanie grupy
	 *
	 * @return	string
	 */
	public function __toString()
	{
		return $this->getName();
	}

	/**
	 * Zwraca userów z grupy
	 *
	 * @return	array
	 */
	public function getUsers()
	{
		if($this->aUsers === null)
		{
			$this->aUsers = UserFactory::getNew()->getForGroup($this);
		}

		return $this->aUsers;
	}

	/**
	 * Czy jest to grupa główna
	 *
	 * @return	bool
	 */
	public function isMain()
	{
		return $this->bMain;
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

	/**
	 * Ustawia uprawnienia grupy
	 *
	 * @return	void
	 */
	public function setPrivileges(array $aPriv)
	{
		$this->aPrivileges = $aPriv;

		// tworzenie zapytania
		$sQuery = 'INSERT INTO group_privileges VALUES ';
		foreach($aPriv as $sPriv)
		{
			$sQuery .= '(' . $this->iId . ', "'. $sPriv . '"),';
		}
		$sQuery = rtrim($sQuery, ',');

		// transkacja ustawiająca nowe uprawnienia
		try
		{
			$this->oDb->beginTransaction();
			$this->oDb->delete('group_privileges', 'group_id = ' . $this->getId());
			$this->oDb->query($sQuery);
			$this->oDb->commit();
		}
		catch(Exception $oExc)
		{
			$this->oDb->rollBack();
			throw $oExc;
		}
	}

	/**
	 * Ustawia ponownie członków grupy
	 *
	 * @param	array	$aUsers	tablica z numerami ID członków grupy
	 * @return	void
	 */
	public function resetUsers(array $aUsers)
	{
		// wykonanie transakcji uaktualniającej grupę
		try
		{
			$this->oDb->beginTransaction();

			// usunięcie dotychczasowych userów
			$this->oDb->delete('user_groups', 'group_id = '. $this->iId);

			if(!empty($aUsers))
			{
				// przygotowanie zapytania z insertem
				$sInsert = 'INSERT INTO user_groups VALUES ';
				foreach($aUsers as $iUserId)
				{
					$sInsert .= '('. $iUserId .', '. $this->iId .' ),';
				}
				$sInsert = rtrim($sInsert, ',');

				$this->oDb->query($sInsert);
			}

			$this->oDb->commit();
			$this->aUsers = null;
		}
		catch(Exception $oExc)
		{
			$this->oDb->rollBack();
			throw $oExc;
		}
	}

}

