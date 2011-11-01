<?php

/**
 * Fabryka userów
 */
class UserFactory extends Core_DataObject_Factory
{
	/**
	 * Konstruktor
	 */
	public function __construct()
	{
		parent::__construct('users', array('user_id'));
	}

	/**
	 * Tworzy nowego usera
	 *
	 * @param	string	$sEmail
	 * @param	string	$sPasswd
	 * @param	string	$sName
	 * @param	string	$sSurname
	 * @return	User
	 */
	public function create($sEmail, $sPasswd, $sName, $sSurname)
	{
		$sSalt = self::generateSalt();

		$aData = array(
			'status' 	=> User::STATUS_ACTIVE,
			'email' 	=> $sEmail,
			'passwd' 	=> self::hashPassword($sPasswd, $sSalt),
			'salt' 		=> $sSalt,
			'name' 		=> $sName,
			'surname' 	=> $sSurname,
		);

		$this->oDb->insert($this->getTableName(), $aData);
		$aData['user_id'] = $this->oDb->lastInsertId('users', 'user_id');

		return $this->createObject($aData);
	}

	/**
	 * Zwraca obiekt usera na podstawie emaila
	 *
	 * @param	string	$sEmail	adres email
	 * @return	User
	 */
	public function getByEmail($sEmail)
	{
		$aDbRes = $this->getSelect()
						->where('email = ?', $sEmail)
						->limit(1)->query()->fetchAll();

		if(empty($aDbRes))
		{
			throw new Core_DataObject_Exception('Brak user o podanym emailu');
		}

		return $this->createObject($aDbRes[0]);
	}

	/**
	 * Zwraca paginator userów z nieusuniętymi kontami
	 *
	 * @param	int		$iPage	strona
	 * @param	int		$iCount	ilość elementów na stronę
	 * @return	Zend_Paginator
	 */
	public function getActivePaginator($iPage, $iCount)
	{
		$oWhere = new Core_DataObject_Where('users.status != ?', User::STATUS_DELETED);

		return $this->getPaginator($iPage, $iCount, array('email'), $oWhere);
	}

// PRYWATNE FUNKCJE FABRYKI

	/**
	 * (non-PHPdoc)
	 * @see Core_DataObject_Factory::createObject()
	 */
	protected function createObject(array $aRow, $mOption = null)
	{
		return new User(
			$aRow['user_id'],
			$aRow['status'],
			$aRow['email'],
			$aRow['passwd'],
			$aRow['salt'],
			$aRow['name'],
			$aRow['surname']
		);
	}

// FUNKCJE STATYCZNE

	/**
	 * Generuje sól dla usera
	 *
	 * @return	string
	 */
	public static function generateSalt()
	{
		return sha1(rand(1, 20000) .'_t1n4b_');	// generuję sól
	}

	/**
	 * Soli hasło
	 *
	 * @param	string	$sPasswd	hasło
	 * @param	string	$sSalt		sól
	 * @return	string
	 */
	public static function hashPassword($sPasswd, $sSalt)
	{
		return sha1('c0n5T'. sha1($sSalt . '_7in4B_'. $sPasswd) . $sSalt . $sPasswd);
	}
}

