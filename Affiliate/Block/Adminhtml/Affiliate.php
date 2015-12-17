<?php
class HN_Affiliate_Block_Adminhtml_Affiliate extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{

		$this->_controller = 'adminhtml_affiliate';
		$this->_blockGroup = 'affiliate';
		$this->_headerText = Mage::helper('affiliate')->__('Affiliate Account');
		parent::__construct();

	}
	
	
}
