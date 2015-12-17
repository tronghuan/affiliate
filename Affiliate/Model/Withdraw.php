<?php
class HN_Affiliate_Model_Withdraw extends Mage_Core_Model_Abstract {
	protected function _construct()
	{
		parent::_construct();
		$this->_init('affiliate/withdrawal');
	}
}