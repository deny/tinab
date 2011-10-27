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
	 * Konstruktor
	 * 
	 * @param	int		$iId		id usera	
	 * @param	string	$sStatus	status
	 * @param	string	$sEmail		email
	 * @param	string	$sPasswd	hasło
	 * @param	string	$sSalt		sól
	 * @param	string	$sName		imię
	 * @param	string	$sSurname	nazwisko
	 * @return	User
	 */
	public function __construct($iId, $sStatus, $sEmail, $sPasswd, $sSalt, $sName, $sSurname)
	{
		parent::__construct('users', array('user_id' => $iId));

		$this->iId = $iId;
		$this->sStatus = $sStatus;
		$this->sEmail = $sEmail;
		$this->sPasswd = $sPasswd;
		$this->sSalt = $sSalt;
		$this->sName = $sName;
		$this->sSurname = $sSurname;
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
		$this->setDataValue('passwd', $this->$sPasswd);
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

}

