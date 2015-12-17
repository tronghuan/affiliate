<?php
class HN_Affiliate_Model_Program extends Mage_Core_Model_Abstract {
	protected function _construct()
	{
		parent::_construct();
		$this->_init('affiliate/program');
		$this->setIdFieldName('program_id');
	}
	public function getConditionsInstance() {
	
	}
	
	public function getActionsInstance( ) {
	
	}
}