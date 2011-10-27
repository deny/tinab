<?php 

/**
 * Podstawa do budowy kontrolerów
 */
class Core_Controller_Action extends Zend_Controller_Action
{
	/**
	 * Przekazuje do widoku niezbędne dane z formularzy 
	 *
	 * @param 	Zend_Filter_Input	$oFilter	obiekt filtra
	 * @return	void
	 */
	protected function showFormMessages(Zend_Filter_Input $oFilter = null)
	{
		$this->view->assing('aValues', $this->_request->getPost());
		$this->view->assing('aErrors', $oFilter->getMessages());
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