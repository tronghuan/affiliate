<?php
class HN_Affiliate_Block_Account extends Mage_Core_Block_Template {
	protected $_affiliate;
	public function getActiveProgramsForCustomer() {
		Mage::getSingleton ( 'customer/session' )->isLoggedIn ();
		
		$websiteId = Mage::app ()->getWebsite ()->getId ();
		$customerGroupId = Mage::getSingleton ( 'customer/session' )->getCustomer ()->getData ( 'group_id' );
		$couponCode = '';
		
		$collection = Mage::getResourceModel ( 'salesrule/rule_collection' )->setValidationFilter ( $websiteId, $customerGroupId, $couponCode )->addFilter ( 'is_affiliate', 1 )->load ();
		return $collection;
	}
	public function isLoggin() {
		return Mage::getSingleton ( 'customer/session' )->isLoggedIn ();
	}
	public function isAffiliate() {
		$resource = Mage::getResourceModel ( 'affiliate/account' );
		$re = $resource->isAffiliate ( Mage::getSingleton ( 'customer/session' )->getCustomer () );
		
		if (empty ( $re )) {
			return false;
		}
		return true;
	}
	/**
	 * @return array
	 */
	public function getAffiliateData() {
		if (! $this->_affiliate) {
			$resource = Mage::getResourceModel ( 'affiliate/account' );
			
			/* @var $resource HN_Affiliate_Model_Resource_Account */
			$re = $resource->isAffiliate ( Mage::getSingleton ( 'customer/session' )->getCustomer () );
			$this->_affiliate = $re;
		}
		return $this->_affiliate;
	}
	
	public function displayCommission($input) {
		switch ($input) {
			case 'fixed_a': {
				return $this->__('Fixed');
					}
			;
			break;
			case 'percent_a': {
				return $this->__('Percentage');
					}
			;
			break;
			
			default:
				;
			break;
		}
		//fixed_a' => Mage::helper('affiliate')->__('Fixed amount'), 
		
	}
}