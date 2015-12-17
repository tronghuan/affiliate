<?php
class HN_Affiliate_Block_Adminhtml_Program extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{

// 		$this->_addButton('importcsvtext', array(
// 				'label'     => Mage::helper('catalogrule')->__('Import text PIN '),
// 				'onclick'   => "location.href='".$this->getUrl('*/*/importcsvtext')."'",
// 				'class'     => '',
// 		));


		$this->_controller = 'adminhtml_program';
		$this->_blockGroup = 'affiliate';
		$this->_headerText = Mage::helper('affiliate')->__('Affiliate Program');
		parent::__construct();

	}
	
	
}
