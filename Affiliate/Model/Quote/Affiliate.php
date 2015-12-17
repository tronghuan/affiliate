<?php
class HN_Affiliate_Model_Quote_Affiliate extends Mage_Sales_Model_Quote_Address_Total_Abstract {
	/**
	 * Discount calculation object
	 *
	 * @var HN_Affiliate_Model_Validator
	 */
	protected $_calculator;
	
	public function __construct()
	{
		$this->setCode('affiliate');
		$this->_calculator = Mage::getSingleton('affiliate/validator');
	}
	
	/**
	 * Collect information about free shipping for all address items
	 *
	 * @param   Mage_Sales_Model_Quote_Address $address
	 * @return  Mage_SalesRule_Model_Quote_Freeshipping
	 */
 public function collect(Mage_Sales_Model_Quote_Address $address)
    {
        parent::collect($address);
        $quote = $address->getQuote();
        $store = Mage::app()->getStore($quote->getStoreId());
        $this->_calculator->reset($address);

        $items = $this->_getAddressItems($address);
        if (!count($items)) {
            return $this;
        }

        $eventArgs = array(
            'website_id'        => $store->getWebsiteId(),
            'customer_group_id' => $quote->getCustomerGroupId(),
            'coupon_code'       => $quote->getCouponCode(),
        );

        $this->_calculator->init($store->getWebsiteId(), $quote->getCustomerGroupId(), $quote->getCouponCode());
        $this->_calculator->initTotals($items, $address);

        $address->setDiscountDescription(array());

        foreach ($items as $item) {
            if ($item->getNoDiscount()) {
                $item->setDiscountAmount(0);
                $item->setBaseDiscountAmount(0);
            }
            else {
                /**
                 * Child item discount we calculate for parent
                 */
                if ($item->getParentItemId()) {
                    continue;
                }

                $eventArgs['item'] = $item;
                Mage::dispatchEvent('sales_quote_address_discount_item', $eventArgs);

                if ($item->getHasChildren() && $item->isChildrenCalculated()) {
                    foreach ($item->getChildren() as $child) {
                        $this->_calculator->process($child);
                        $eventArgs['item'] = $child;
                        Mage::dispatchEvent('sales_quote_address_discount_item', $eventArgs);

                        $this->_aggregateItemDiscount($child);
                    }
                } else {
                    $this->_calculator->process($item);
                    $this->_aggregateItemDiscount($item);
                }
            }
        }

        /**
         * process weee amount
         */
        if (Mage::helper('weee')->isEnabled() && Mage::helper('weee')->isDiscounted($store)) {
            $this->_calculator->processWeeeAmount($address, $items);
        }

        /**
         * Process shipping amount discount
         */
        $address->setShippingDiscountAmount(0);
        $address->setBaseShippingDiscountAmount(0);
        if ($address->getShippingAmount()) {
            $this->_calculator->processShippingAmount($address);
            $this->_addAmount(-$address->getShippingDiscountAmount());
            $this->_addBaseAmount(-$address->getBaseShippingDiscountAmount());
        }

        $this->_calculator->prepareDescription($address);
        return $this;
    }
	
	/**
	 * Add information about free shipping for all address items to address object
	 * By default we not present such information
	 *
	 * @param   Mage_Sales_Model_Quote_Address $address
	 * @return  Mage_SalesRule_Model_Quote_Freeshipping
	 */
	public function fetch(Mage_Sales_Model_Quote_Address $address)
	{
		return $this;
	}
}