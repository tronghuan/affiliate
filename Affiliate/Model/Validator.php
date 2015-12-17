<?php
class HN_Affiliate_Model_Validator extends Mage_SalesRule_Model_Validator {
	public function init($websiteId, $customerGroupId, $couponCode) {
		$this->setWebsiteId ( $websiteId )->setCustomerGroupId ( $customerGroupId )->setCouponCode ( $couponCode );
		
		$key = $websiteId . '_' . $customerGroupId . '_' . $couponCode;
		if (! isset ( $this->_rules [$key] )) {
			$this->_rules [$key] = Mage::getResourceModel ( 'salesrule/rule_collection' )->setValidationFilter ( $websiteId, $customerGroupId, $couponCode )->load ();
		}
		return $this;
	}
	public function processAffiliate() {
	}
}