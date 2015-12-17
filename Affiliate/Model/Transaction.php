<?php
class HN_Affiliate_Model_Transaction extends Mage_Core_Model_Abstract {
	protected function _construct()
	{
		parent::_construct();
		$this->_init('affiliate/transaction');
	}
}