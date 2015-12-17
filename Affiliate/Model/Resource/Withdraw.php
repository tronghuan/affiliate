<?php
class HN_Affiliate_Model_Resource_Withdraw  extends Mage_Core_Model_Resource_Db_Abstract
{

	/**
	 * Initialize main table and table id field
	*/
	protected function _construct()
	{
		$this->_init('affiliate/withdrawal', 'id');
	}

	
}

