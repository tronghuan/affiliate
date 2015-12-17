<?php
class HN_Affiliate_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * @param Mage_Customer_Model_Custome $customer
	 */
	public function isAffiliate($customer) {
		$resource = Mage::getResourceModel('affiliate/account');
		$re = $resource->isAffiliate($customer);
		
		if (empty($re)) {
			return false;
			
		}
		return  true;
	}
	
	/**
	 * get affiliate information from cookie
	 */
	public function getAffiliateInfo($cookie_value) {
		# $utm in in format  $websiteId . '_' . $customerId . '_' . $source;
	 $utm = 	Mage::helper ( 'core' )->decrypt ( $cookie_value );  
	 $data = explode('_', $utm);
	 
	 $source  = '';
	 $websiteId = $data[0];
	 $customerId = $data[1];
	 
	 if (isset($data[2])) $source = $data[2]; 
	}
}