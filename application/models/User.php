<?php

/**
 * Klasa użytkonika
 */
class User extends Core_DataObject
{
	//Statusy konta
	const STATUS_ACTIVE 	= 'active';
	const STATUS_INACTIVE 	= 'inactive';
	const STATUS_DELETED 	= 'deleted';

	/**
	 * Id usera
	 *
	 * @var int
	 */
	protected $iId;

	/**
	 * Status
	 *
	 * @var string
	 */
	protected $sStatus;

	/**
	 * Email
	 *
	 * @var string
	 */
	protected $sEmail;

	/**
	 * Hash hasła
	 *
	 * @var string
	 */
	protected $sPasswd;

	/**
	 * Sól
	 *
	 * @var string
	 */
	protected $sSalt;

	/**
	 * Imię
	 *
	 * @var string
	 */
	protected $sName;

	/**
	 * Nazwisko
	 *
	 * @var string
	 */
	protected $sSurname;

	/**
	 * Tablica na obiekty grup globalnych do których należy user
	 *
	 * @var	array
	 */
	protected $aGlobalGroups = null;

	/**
	 * Konstruktor
	 *
	 * @param	int		$iId		id usera
	 * @param	string	$sStatus	status
	 * @param	string	$sEmail		email
	 * @param	string	$sPasswd	hasło
	 * @param	string	$sSalt		sól
	 * @param	string	$sName		imię
	 * @param	string	$sSurname	nazwisko
	 * @param	array	$aPreload	tablica z preloadem
	 * @return	User
	 */
	public function __construct($iId, $sStatus, $sEmail, $sPasswd, $sSalt, $sName, $sSurname, $aPreload = array())
	{
		parent::__construct('users', array('user_id' => $iId));

		$this->iId = $iId;
		$this->sStatus = $sStatus;
		$this->sEmail = $sEmail;
		$this->sPasswd = $sPasswd;
		$this->sSalt = $sSalt;
		$this->sName = $sName;
		$this->sSurname = $sSurname;

		if(isset($aPreload['globalGroups']))
		{
			$this->aGlobalGroups = $aPreload['globalGroups'];
		}
	}

// gettery

	/**
	 * Zwraca id
	 *
	 * @return	int
	 */
	public function getId()
	{
		return $this->iId;
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
	 * Zwraca email
	 *
	 * @return	string
	 */
	public function getEmail()
	{
		return $this->sEmail;
	}

	/**
	 * Zwraca imię
	 *
	 * @return	string
	 */
	public function getName()
	{
		return $this->sName;
	}

	/**
	 * Zwraca naswisko
	 *
	 * @return	string
	 */
	public function getSurname()
	{
		return $this->sSurname;
	}

	/**
	 * Zwraca nicka (imię + nazwisko)
	 *
	 * @return	string
	 */
	public function getNick()
	{
		return $this->sName . ' ' . $this->sSurname;
	}

	/**
	 * Zwraca zbiór uprawnień przydzielnoych temu userowi
	 *
	 * @param	int|null	$iProjectId	id projektu z którym mają być zwiazane uprawnienia
	 * @return	array
	 */
	public function getPrivileges($iProjectId = null)
	{
		return Privileges::getUserPrivileges($this, $iProjectId);
	}

	/**
	 * Zwraca grupy do których należy user
	 *
	 * @return	array
	 */
	public function getGlobalGroups()
	{
		if($this->aGlobalGroups === null)
		{
			$this->aGlobalGroups = GroupFactory::getNew()->getForUser($this);
		}

		return $this->aGlobalGroups;
	}

	/**
	 * Sprawdza czy hasło jest poprawne
	 *
	 * @param	string	$sPasswd	testowane hasło
	 * @return	bool
	 */
	public function isPasswdMatch($sPasswd)
	{
		return $this->sPasswd == UserFactory::hashPassword($sPasswd, $this->sSalt);
	}

// settery

	/**
	 * Zmienia status konta
	 *
	 * @param	string	$sStatus	nowy status (User::STATUS_*)
	 * @return	void
	 */
	public function setStatus($sStatus)
	{
		$this->sStatus = $sStatus;
		$this->setDataValue('status', $sStatus);
	}

	/**
	 * Ustawia email
	 *
	 * @param	string	$sEmail	nowy email
	 * @return	void
	 */
	public function setEmail($sEmail)
	{
		$this->sEmail = $sEmail;
		$this->setDataValue('email', $sEmail);
	}

	/**
	 * Ustawia nowe hasło
	 *
	 * @param	string	$sPasswd	nowe hasło
	 * @return	void
	 */
	public function setPasswd($sPasswd)
	{
		$this->sSalt = UserFactory::generateSalt();
		$this->sPasswd = UserFactory::hashPassword($sPasswd, $this->sSalt);
		$this->setDataValue('passwd', $this->sPasswd);
		$this->setDataValue('salt', $this->sSalt);
	}

	/**
	 * Ustawia nowe imie
	 *
	 * @param	string	$sName	nowe imie
	 * @return	void
	 */
	public function setName($sName)
	{
		$this->sName = $sName;
		$this->setDataValue('name', $sName);
	}

	/**
	 * Ustawia nowe nazwisko
	 *
	 * @param	string	$sSurname	nowe nazwisko
	 * @return	void
	 */
	public function setSurname($sSurname)
	{
		$this->sSurname = $sSurname;
		$this->setDataValue('surname', $sSurname);
	}

	/**
	 * Ustawia ponownie globalne grupy usera
	 *
	 * @param	array	$aGroups	tablica z numerami ID grup
	 * @return	void
	 */
	public function resetGlobalGroups(array $aGroups)
	{
		// wykonanie transakcji uaktualniającej globalne grupy usera
		try
		{
			$this->oDb->beginTransaction();

			// usunięcie dotychczasowych grup globalnych
			$this->oDb->query(
				'DELETE user_groups '.
				'FROM user_groups JOIN groups ON groups.group_id = user_groups.group_id '.
				'WHERE user_groups.user_id = '. $this->iId .' AND groups.project_id IS NULL'
			);

			if(!empty($aGroups))
			{
				// przygotowanie zapytania z insertem
				$sInsert = 'INSERT INTO user_groups VALUES ';
				foreach($aGroups as $iGroupId)
				{
					$sInsert .= '(' . $this->iId . ', ' . $iGroupId . '),';
				}
				$sInsert = rtrim($sInsert, ',');

				$this->oDb->query($sInsert);
			}

			$this->oDb->commit();
			$this->aGlobalGroups = null;
		}
		catch(Exception $oExc)
		{
			$this->oDb->rollBack();
			throw $oExc;
		}
	}

}

