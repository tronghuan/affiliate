<?php
class HN_Affiliate_Model_Observer {
	const COOKIE_KEY_SOURCE = 'mag_affiliate';
	
	public function orderCommitListener($observer) {
		$order = $observer->getEvent ()->getOrder ();
		/* @var $order Mage_Sales_Model_Order */
        $affiliate_programs = array();
		$baseGrandTotal = $order->getBaseGrandTotal();
		if ($order->getData ( 'applied_rule_ids' ) == '')
			return $observer;
		 
		$applied_rule_ids = explode ( ',', $order->getData ( 'applied_rule_ids' ) );
		
		$transResource = Mage::getResourceModel('affiliate/transaction');
		/* @var $transResource HN_Affiliate_Model_Resource_Transaction */
		
		$transactionArr = $transResource->getTransactionByOrderNo($order->getIncrementId());
		if ($transactionArr && !empty($transactionArr)) {
			return $observer;
		}
		Mage::log('applied rule id', null, 'aff.log' , true);
		Mage::log($applied_rule_ids, null, 'aff.log' , true);
		if (!empty($applied_rule_ids)) {
			foreach ($applied_rule_ids as $rule_id) {
				if ($rule_id) {
					$salesrule = Mage::getModel('salesrule/rule')->load($rule_id);
					
					$affiliate_program = $salesrule->getProgramId();
					if ($affiliate_program) {
						$affiliate_programs[] = $affiliate_program;
						Mage::log('affiliate_program', null, 'aff.log' , true);
						
		         Mage::log($affiliate_programs, null, 'aff.log' , true);
												
					}
				}
			}
			
		}
		//capture affiliate cookie
		$affiliate_cookie = Mage::getSingleton('core/cookie')->get(self::COOKIE_KEY_SOURCE);
		
		if (!$affiliate_cookie || $affiliate_cookie =='') {
			return $observer;
			
		}
		
		if ($affiliate_cookie) {
			$utm = 	Mage::helper ( 'core' )->decrypt ( $affiliate_cookie );
			Mage::log('utm '.$utm, null, 'aff.log' , true);
				
			$data = explode('_', $utm);
			
			$source  = '';
			$programId = $data[0];
			if (!in_array($programId, $affiliate_programs)) {
				return $observer;
			}
			$customerId = $data[1]; // it is customer id of affiliate account
			
			if (isset($data[2])) $source = $data[2];
			
			$log_mess = "program id {$programId} Customer id {$customerId} utm is {$utm} ";
			Mage::log($log_mess , null, 'aff.log' ,true);
			
			# find the information of campaigns id and crreate transaction
			#validate the program id and affiliate account first
			
			$affiliate_account = Mage::getResourceModel('affiliate/account')->getDetailByCustomerId($customerId);
			$log_mess = 'affiliate_account';
			Mage::log($log_mess , null, 'aff.log' ,true);
				
			Mage::log($affiliate_account , null, 'aff.log' ,true);
				
			if (!empty($affiliate_account)) {
				
				//$affiliate_account
				$programModel = Mage::getModel('affiliate/program')->load($programId);
				
				$rate_type = $programModel->getData('rate');
				$rate_amount = $programModel->getData('rate_amount');
				
				if ($rate_type == 'percent_a') {
					$earn_amount = $baseGrandTotal * $rate_amount/100;
				} else if ($rate_type == 'fixed_a') {
					$earn_amount = $rate_amount;
				}
			
				#LOG
				$message = "rate type {$rate_type} rate amout {$rate_amount} ";
				Mage::log($message ,null, 'aff.log' ,true);
				
					$transactionData = array(
						'program_id' => $programModel->getId(),
							'earn_amount' => $earn_amount,
							'rate' => $rate_amount,
							'order_increment_id' => $order->getIncrementId(),
							'created_at' => now(),
							'attrached_amount' => $baseGrandTotal,
							'affiliate_id' =>$affiliate_account[0]['id']
					);
					
					Mage::log($transactionData , null, 'aff.log' ,true);
					
					Mage::getModel('affiliate/transaction')->setData($transactionData)->save();
					
					# Add earned commission amount to affiliate account
					$hoding_period_config = Mage::getStoreConfig('affiliate/withdrawalholding_period');
					
					$affiliate_account_bean = Mage::getModel('affiliate/account')->load($affiliate_account[0]['id']);
					
					if ($hoding_period_config && $hoding_period_config > 0) {
						$affiliate_account_bean->setData('pending_balance' ,$affiliate_account_bean>getData('pending_balance') +  $earn_amount);
					} else {
						$affiliate_account_bean->setData('balance' , $affiliate_account_bean>getData('balance') + $earn_amount);
					}
					
					$affiliate_account_bean->setData('lifetime_balance', $affiliate_account_bean>getData('lifetime_balance') + $earn_amount);
					$affiliate_account_bean->save();
				}
			}
		
		
		return $observer;
	}
	public function captureReferral(Varien_Event_Observer $observer) {
		$frontController = $observer->getEvent ()->getFront ();
		
		$request = $frontController->getRequest ();
		$pathInfo = $request->getPathInfo() ;
		
        if (strpos($pathInfo, 'customer/account/login/next/affiliate') > 1 
            || strpos($pathInfo, 'customer/account/create/next/affiliate') > 1)  {
        	
        	Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('affiliate/account/index' , array('rf' =>1) ) );
        	 
        }		
		
		$utmSource = $frontController->getRequest ()->getParam ( 'ats', false );
		
		Mage::log($utmSource , null, 'affiliate.log' ,true);
		
		if ($utmSource) {
			$utm = Mage::getModel('core/cookie')->get(self::COOKIE_KEY_SOURCE);
			$is_allow_rewrite_cookie = Mage::getStoreConfig('affiliate/general/cookie_overwrite');
			
			if ($utm == '' || $is_allow_rewrite_cookie == 1) {
				Mage::getModel ( 'core/cookie' )->set ( self::COOKIE_KEY_SOURCE, $utmSource, $this->_getCookieLifetime () );
				
			}
		}
		return $observer;
	}
	
	protected function _getCookieLifetime()
	{
		$days = Mage::getStoreConfig('affiliate/general/cookie_lifetime');
	     
		// convert to seconds
		return (int)86400 * $days;
	}
}