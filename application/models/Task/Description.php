<?php

/**
 * Opis statusu zadanai
 */
class Task_Description extends Core_DataObject
{
	/**
	 * ID taska
	 *
	 * @var int
	 */
	protected $iTaskId;

	/**
	 * Data dodania
	 *
	 * @var int
	 */
	protected $iEntryDate;

	/**
	 * Id autora
	 *
	 * @var int
	 */
	protected $iAuthorId;

	/**
	 * Treść wpisu
	 *
	 * @var string
	 */
	protected $sText;

	/**
	 * Konstruktor
	 *
	 * @param	int		$iTaskId	id zadania
	 * @param	int		$iEntryDate	data dodania
	 * @param	int		$iAuthorId	autor
	 * @param	string	$sText		treśc wpisu
	 * @param	array	$aPreload	tablica z preloadem
	 * @return	Task_Description
	 */
	public function __construct($iTaskId, $iEntryDate, $iAuthorId, $sText, array $aPreload = array())
	{
		parent::__construct('task_descriptions', array('task_id' => $iTaskId, 'entry_date' => $iEntryDate));

		$this->iTaskId = $iTaskId;
		$this->iEntryDate = $iEntryDate;
		$this->iAuthorId = $iAuthorId;
		$this->sText = $sText;
	}

// gettery

	/**
	 * Zwraca
	 *
	 * @return	int
	 */
	public function getTaskId()
	{
		return $this->iTaskId;
	}

	/**
	 * Zwraca
	 *
	 * @return	int
	 */
	public function getEntryDate()
	{
		return $this->iEntryDate;
	}

	/**
	 * Zwraca
	 *
	 * @return	int
	 */
	public function getAuthorId()
	{
		return $this->iAuthorId;
	}

	/**
	 * Zwraca
	 *
	 * @return	string
	 */
	public function getText()
	{
		return $this->sText;
	}

// settery

	/**
	 *
	 *
	 * @param	string	$sText
	 * @return	void
	 */
	public function setText($sText)
	{
		$this->sText = $sText;
		$this->setDataValue('text', $sText);
	}

}

