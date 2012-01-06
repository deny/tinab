<?php

/**
 * Helper zwracający opis dla statusu taska
 */
class View_Helper_Task_Status extends Zend_View_Helper_Abstract
{
	/**
	 * Opisy statusów
	 *
	 * @var	array
	 */
	protected $aStatus = array(
		Task::STATUS_NEW 		=> array('Nowe', 'Nowe zadania'),
		Task::STATUS_ACTIVE 	=> array('Aktywne', 'Aktywne zadania'),
		Task::STATUS_SUSPEND 	=> array('Zawieszone', 'Zawieszone zadania'),
		Task::STATUS_TEST		=> array('Testy', 'Testowane zadania'),
		Task::STATUS_CR			=> array('Code review', 'Zadania do code review'),
		Task::STATUS_TO_ACC		=> array('W akceptacji', 'Zadania do akceptacji'),
		Task::STATUS_ACCEPTED	=> array('Zaakceptowane', 'Zaakceptowane zadania'),
		Task::STATUS_FINISHED	=> array('Zakończone', 'Zadania zakończone')
	);

	/**
	 * Funkcja helpera
	 *
	 * @param	string	$sStatus	status taska (Task::STATUS_*)
	 * @param	bool	$bGroup		czy zwrócić nazwę dla grupy zadań
	 * @return	string
	 */
	public function task_Status($sStatus = null, $bGroup = false)
	{
		if($sStatus == null)
		{
			return $this;
		}

		if(!isset($this->aStatus[$sStatus]))
		{
			throw new Exception('Wrong task status');
		}

		$iPos = $bGroup ? 1 : 0;

		return $this->aStatus[$sStatus][$iPos];
	}

	/**
	 * Zwraca liste statusów taska
	 *
	 * @return	array
	 */
	public function getList()
	{
		$aTmp = array();

		foreach($this->aStatus as $sStatus => $aInfo)
		{
			$aTmp[$sStatus] = $aInfo[0];
		}

		return $aTmp;
	}
}