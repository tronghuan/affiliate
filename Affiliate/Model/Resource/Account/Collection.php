<?php
class HN_Affiliate_Model_Resource_Account_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
	/**
	 * Define resource model
	 *
	 */
	protected function _construct()
	{
		$this->_init('affiliate/account');
	}
}