<?php
class HN_Affiliate_Block_Account_Mywithdraw extends Mage_Core_Block_Template {
	public function __construct()
	{
		
		parent::__construct();
		$customerId =  Mage::getSingleton('customer/session')->getCustomer()->getId();
		$affiliate_account_ids = Mage::getResourceModel('affiliate/account')->getDetailByCustomerId($customerId);
		$affiliate_account_id = $affiliate_account_ids[0]['id'];
		$collection = Mage::getModel('affiliate/withdrawal')->getCollection()->addFilter('account_id',$affiliate_account_id);
		//$collection = Mage::getModel('affiliate/withdraw')->getCollection()->addFilter('customer_id', $customerId);
		/* @var $collection Varien_Data_Collection */
		$this->setCollection($collection);
	}
	
	protected function _prepareLayout()
	{
		parent::_prepareLayout();
	
		$pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
		$pager->setAvailableLimit(array(5=>5,10=>10,20=>20,'all'=>'all'));
		$pager->setCollection($this->getCollection());
		$this->setChild('pager', $pager);
		$this->getCollection()->load();
		return $this;
	}
	
	public function getPagerHtml()
	{
		return $this->getChildHtml('pager');
	}
}