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
	const MSG_OK 	= 'msg-ok';
	const MSG_ERROR = 'msg-error';

	/**
	 * Czy pozwalać na "wizytę" osób niezalogowanych
	 *
	 * @var	bool
	 */
	protected $bAllowGuest = false;

	/**
	 * Obiekt zalogowanego usera
	 *
	 * @var	User
	 */
	protected $oUser = null;

	/**
	 * Uprawnienia wymagana dla poszczególnych akcji
	 *
	 * @var	array
	 */
	private $aPrivileges = array();

	/**
	 * Id wybranego projektu
	 *
	 * @var	int
	 */
	protected $iProjectId = null;

	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init()
	{
		parent::init();

		// porbanie zalogowanego usera
		if(Core_Auth::getInstance()->hasIdentity())
		{
			$this->oUser = Core_Auth::getInstance()->getUser();
		}

		// jeśli strefa tylko dla zalogowanych i user nie jest zalogowany
		if(!$this->bAllowGuest && !isset($this->oUser))
		{
			$this->_redirect('/');
		}

		if(isset($this->oUser) && !$this->bAllowGuest)
		{
			// sprawdzenie uprawnień
			$oFront = Zend_Controller_Front::getInstance();
			$sAction = $oFront->getRequest()->getActionName();
			$sActionMethod = $oFront->getDispatcher()->formatActionName($sAction);

			// ładujemy info o metodach klasy
			if($this->_classMethods === null)
			{
				$this->_classMethods = get_class_methods($this);
			}

			// jeśli akcja istnieje
			if(in_array($sActionMethod, $this->_classMethods))
			{
				 // jeśli brak wpisu o tej akcji == brak dostępu
				if(!isset($this->aPrivileges[$sAction]))
				{
					throw new Zend_Controller_Action_Exception(
						'No ACL entry for: '. $this->_request->getRequestUri(),
						403
					);
				}

				// pobranie uprawnień usera
				$aPriv = $this->oUser->getPrivileges($this->iProjectId);

				// sprawdzenie czy user ma wszystkie wymagane uprawnienia
				foreach($this->aPrivileges[$sAction] as $sPriv)
				{
					if(!in_array($sPriv, $aPriv)) // jeśli brak uprawnienia
					{
						throw new Zend_Controller_Action_Exception(
							$this->_request->getRequestUri() . ' - requires: '. $sPriv,
							403
						);
					}
				}

			}
		}
	}

	/**
	 * Dodaje wpis do tablicy z wymaganymi uprawnieniami
	 *
	 * @param	mixed	$sAction		akcja/tablica akcji
	 * @param	array 	$aPrivileges	tablica z wymaganymi uprawnieniami
	 * @return	Core_Controller_Action
	 */
	protected function setAcl($mAction, array $aPrivileges)
	{
		if(is_array($mAction)) // jeśli tablica akcji
		{
			foreach($mAction as $sAction)
			{
				$this->aPrivileges[$sAction] = $aPrivileges;
			}
		}
		else // jesli pojedyńcza akcja
		{
			$this->aPrivileges[$mAction] = $aPrivileges;
		}

		return $this;
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