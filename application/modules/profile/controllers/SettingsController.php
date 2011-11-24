<?php

/**
 * Zarządzanie profilem
 */
class Profile_SettingsController extends Core_Controller_Action
{
	/**
	 * (non-PHPdoc)
	 * @see Core_Controller_Action::init()
	 */
	public function init()
	{
		// wymagane uprawnienia
		$this->setAcl(array(
			'password'
		));

		parent::init();
		$this->_helper->layout()->setLayout('profile');
	}

	/**
	 * Zmiana hasła
	 */
	public function passwordAction()
	{
		if($this->_request->getPost())
		{
			$oFilter = $this->getPasswdFilter();

			if($oFilter->isValid())
			{
				$this->oUser->setPasswd($oFilter->getEscaped('passwd'));
				$this->oUser->save();

				$this->addMessage('Hasło zostało zmienione', self::MSG_OK);

				$this->_redirect('/profile/settings/password');
				exit();
			}

			$this->showFormMessages($oFilter);
		}
	}

// FILTRY

	/**
	 * Zwraca filtr do walidacji zmienianego hasła
	 *
	 * @return	Core_Filter_Input
	 */
	protected function getPasswdFilter()
	{
		$aValues = $this->_request->getPost();

		$oPasswd = new Zend_Validate_Identical($aValues['passwd']);
		$oPasswd->setMessage('Podane hasła są różne', Zend_Validate_Identical::NOT_SAME);

		$aValidators = array(
			'old_passwd' => array(
				new Core_Validate_Callback(array(
					'message'	=> 'błędne hasło',
					'options'	=> array($this->oUser),
					'callback'	=> function($sValue, $oUser){
						return $oUser->isPasswdMatch($sValue);
					}
				))
			),
			'passwd' => array(
				new Core_Validate_StringLength(array('min' => 8)),
			),
			'passwd2' => array(
				new Core_Validate_StringLength(array('min' => 8)),
				$oPasswd
			)

		);

		// filtr
		return new Core_Filter_Input(null, $aValidators, $aValues);
	}
}