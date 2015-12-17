<?php
class HN_Affiliate_Block_Adminhtml_Program_Edit_Tab_Rate_Tier extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface {
	
	public function __construct() {
		$this->setTemplate ( 'hn/affiliate/program/tier-amount.phtml' );
	}
	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$this->setElement($element);
		return $this->toHtml();
	}
}