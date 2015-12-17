<?php
class HN_Affiliate_TestController extends Mage_Core_Controller_Front_Action {
	
	public function indexAction() {
				$program = Mage::getModel('affiliate/program')->load(4);
		
	}
	
	public function transactionAction() {
		$model = Mage::getModel('affiliate/transaction');
		$model->setData(array('earn_amount' , 10));
		$model->save();
	}
	
	public function getruleAction() {
		$a = new HN_Affiliate_Block_Account();
		$x = $a->getActiveProgramsForCustomer();
		echo get_class($x);
		echo $x->getSize();
		foreach ($x as $rule) {
			echo $rule->getName();
			
		}
	}
	
	public function testAction() {
// 		$keys = array('foo', 5, 10, 'bar');
// 		$a = array_fill_keys($keys, array('banana' ,'chuoi'));
// 		print_r($a);
// 		array_intersect_key($array1, $array2);
		//array_intersect($array1, $array2)
		$g = new Mage_Adminhtml_Model_System_Config_Source_Customer_Group();
		$gs = 	$g->toOptionArray();
		foreach ($gs as $group) {
			var_dump($group);
		}
		//Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('affiliate/account/index' ,array('test' => 'thuy')) );
	}
	
	public function isAffiliateAction() {
		$resource = Mage::getResourceModel('affiliate/account');
		$c = Mage::getModel('customer/customer')->load(14);
		var_dump($resource->isAffiliate($c));
		
		
	}
	
}