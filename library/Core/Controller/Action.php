<?php

/**
 * Podstawa do budowy kontrolerów
 */
class Core_Controller_Action extends Zend_Controller_Action
{
	/**
	 * Stałę dla flash messenegera
	 *
	 * @var	string
	 */
	const MSG_OK = 'msg-ok';
	const MSG_ERROR = 'msg-error';

	/**
	 * Obiekt zalogowanego usera
	 *
	 * @var	User
	 */
	protected $oUser = null;

	public function init()
	{
		parent::init();

		if(Core_Auth::getInstance()->hasIdentity())
		{
			$this->oUser = Core_Auth::getInstance()->getUser();
		}
	}

	/**
	 * Przekazuje do widoku niezbędne dane z formularzy
	 *
	 * @param 	Zend_Filter_Input	$oFilter	obiekt filtra
	 * @return	void
	 */
	protected function showFormMessages(Zend_Filter_Input $oFilter = null)
	{
		$this->view->assign('aValues', $this->_request->getPost());
		$this->view->assign('aErrors', $oFilter->getMessages());
	}

	/**
	 * Dodaje komunikat do Flash Messengera
	 *
	 * @param	string	$sMessage	treść wiadomości
	 * @param	strng	$sType		typ wiadomości (stałe Core_Controller_Action::MSG_*)
	 * @param	bool	$bNow		czy wiadomośc powinna pojawiż się od razu
	 * @return	void
	 */
	protected function addMessage($sMessage, $sType = self::MSG_OK, $bNow = false)
	{
		if($bNow)
		{
			$this->_helper->flashMessenger->addCurrentMsg($sMessage, $sType);
		}
		else
		{
			$this->_helper->flashMessenger->addMsg($sMessage, $sType);
		}
	}

	/**
	 * Przeniesienie na 404
	 *
	 * @throws Zend_Controller_Action_Exception
	 */
	protected function moveTo404()
	{
		throw new Zend_Controller_Action_Exception('Page not found', 404);
	}
}