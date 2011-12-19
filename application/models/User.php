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
	 * Tablica na obiekty grup projektowych
	 *
	 * @var	array
	 */
	protected $aProjectGroups = array();

	/**
	 * Tablica na załadowane uprawnienia
	 *
	 * @var	array
	 */
	protected $aPivileges = array();

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
		$sKey = $iProjectId === null ? 'global' : $iProjectId;

		if(!isset($this->aPivileges[$sKey]))
		{
			$this->aPivileges[$sKey] = Privileges::getUserPrivileges($this, $iProjectId);
		}

		return $this->aPivileges[$sKey];
	}

	/**
	 * Sprawdza czy user posiada podane prawnienie
	 *
	 * @param	string	$sPriv			uprawnienie
	 * @param	int		$iProjectId		id projektu
	 * @return	bool
	 */
	public function hasPrivilege($sPriv, $iProjectId = null)
	{
		return in_array($sPriv, $this->getPrivileges($iProjectId));
	}

	/**
	 * Zwraca grupy do których należy user
	 *
	 * @return	array
	 */
	public function getGroups(Project $oProject = null)
	{
		if(isset($oProject)) // jeśli podano projekt
		{
			if(!isset($this->aProjectGroups[$oProject->getId()]))
			{
				$this->aProjectGroups[$oProject->getId()] = GroupFactory::getNew()->getForUser($this, $oProject->getId());
			}
			$aTmp = &$this->aProjectGroups[$oProject->getId()];
		}
		else // grupy globalne
		{
			if($this->aGlobalGroups === null)
			{
				$this->aGlobalGroups = GroupFactory::getNew()->getForUser($this);
			}
			$aTmp = &$this->aGlobalGroups;
		}

		return $aTmp;
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
	 * Ustawia ponownie grupy usera
	 *
	 * @param	array		$aGroups	tablica z numerami ID grup
	 * @param	Procject	$oProject	obiekt projektu
	 * @return	void
	 */
	public function resetGroups(array $aGroups, Project $oProject = null)
	{
		$sProject = isset($oProject) ? ' = '. $oProject->getId() : ' IS NULL';

		// wykonanie transakcji uaktualniającej globalne grupy usera
		try
		{
			$this->oDb->beginTransaction();

			// usunięcie dotychczasowych grup globalnych
			$this->oDb->query(
				'DELETE user_groups '.
				'FROM user_groups JOIN groups ON groups.group_id = user_groups.group_id '.
				'WHERE user_groups.user_id = '. $this->iId .' AND groups.project_id' . $sProject
			);

			// dodanie usera do grup - jeśli trzeba
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

			// wyczyszczenie zmiennych
			if(isset($oProject) && isset($this->aProjectGroups[$oProject->getId()]))
			{
				unset($this->aProjectGroups[$oProject->getId()]);
			}
			else
			{
				$this->aGlobalGroups = null;
			}
		}
		catch(Exception $oExc)
		{
			$this->oDb->rollBack();
			throw $oExc;
		}
	}
}

