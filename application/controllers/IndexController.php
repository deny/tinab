<?php

/**
 * Kontroler strony głównej
 */
class IndexController extends Core_Controller_Action
{
	/**
	 * (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init()
	{
		$this->bAllowGuest = true;
		parent::init();
		$this->_helper->layout()->setLayout('login');
	}

	/**
	 * Logowanie
	 */
	public function indexAction()
	{
		if(isset($this->oUser)) // jeśli user jest zalogwany
    	{
    		$this->_redirect('/summary');
    	}

		if($this->_request->isPost()) // jeśli wysłano formularz
		{
			$oFilter = $this->getLoginFilter();

			if($oFilter->isValid()) // jeśli wpisano sensowne dane
			{
				// próba zalogowania
				$oResult = Core_Auth::getInstance()->authenticate(
					new Core_Auth_Adapter(
						$oFilter->getEscaped('email'),
						$oFilter->getEscaped('passwd')
					)
				);

				if($oResult->isValid()) // udane logowanie
				{
					if($oFilter->getEscaped('remember') == 1)
					{
						Zend_Session::rememberMe();
					}

					$this->_redirect('/summary');
					exit();
				}
				else // nieudane logowanie
				{
					$this->addMessage('Niepoprawny login bądź hasło', self::MSG_ERROR, true);
				}
			}
			else // niepoprawne dane w formularzu
			{
				$this->showFormMessages($oFilter);
			}
		}
    }

    /**
     * Wylogowanie aktualonego usera
     */
    public function logoutAction()
    {
    	if(isset($this->oUser)) // jeśli user jest zalogwany
    	{
	    	Core_Auth::getInstance()->clearIdentity();
	    	Zend_Session::destroy(true);
    	}

    	$this->_redirect('/');
    }

    /**
     * Zwraca filtr logowania
     *
     * @return	Zend_Filter_Input
     */
    protected function getLoginFilter()
    {
		$aValues = $this->_request->getPost();

    	// walidatory
		$aValidators = array(
			'email'	=> array(
				new Core_Validate_EmailAddress(),
				'emptyMsg' => 'Musisz podać adres email'
			),
			'passwd' => array(
				'emptyMsg' => 'Musisz podać hasło'
			)
		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
    }
}

