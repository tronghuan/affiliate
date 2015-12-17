<?php
class HN_Affiliate_Model_Session extends Mage_Core_Model_Session_Abstract{
	
	/**
	 * Account object
	 *
	 * @var HN_Affiliate_Model_Account
	 */
	protected $_account;
	
	public function setAccount($account) {
		$this->_account = $account;
		$this->setId($account->getId());
	}
	
	public function setRegister($register) {
		$this->setRegister($register);
	}
}