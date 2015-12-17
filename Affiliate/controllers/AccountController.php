<?php
class HN_Affiliate_AccountController extends Mage_Core_Controller_Front_Action {
	
	/**
	 * Action predispatch
	 *
	 * Check customer authentication for some actions
	 */
	public function preDispatch() {
		// a brute-force protection here would be nice
		parent::preDispatch ();
		
		if (! $this->getRequest ()->isDispatched ()) {
			return;
		}
		
		$action = $this->getRequest ()->getActionName ();
		$openActions = array (
				'generatelink',
				'withdrawal' 
		);
		$pattern = '/^(' . implode ( '|', $openActions ) . ')/i';
		
		if (preg_match ( $pattern, $action )) {
			
			if (! $this->_getSession ()->authenticate ( $this )) {
				$this->setFlag ( '', 'no-dispatch', true );
			}
		} else {
			$this->_getSession ()->setNoReferer ( true );
		}
	}
	protected function _getSession() {
		return Mage::getSingleton ( 'customer/session' );
	}
	public function indexAction() {
		$this->loadLayout ();
		$this->renderLayout ();
	}
	public function registerAction() {
		$this->loadLayout ();
		$this->renderLayout ()

		;
	}
	public function checkemailregister() {
	}
	
	/**
	 *
	 * @param HN_Affiliate_Model_Account $affiliate        	
	 */
	protected function validate($params) {
		$model = Mage::getModel ( 'affiliate/account' );
		var_dump ( $model );
		$data = $model->getData ();
		
		print_r ( $data );
		// $params = $this->getRequest()->getParams();
		print_r ( $params );
		$filterData = array_intersect_key ( $params, $data );
		print_r ( $filterData );
		print_r ( $filterData );
	}
	public function createPostoldAction() {
		$params = $this->getRequest ()->getParams ();
		
		$this->validate ( $params );
		
		$model = Mage::getModel ( 'affiliate/account' );
		$model->setData ( array (
				'firstname' => $params ['firstname'],
				'lastname' => $params ['lastname'],
				'email' => $params ['email'],
				'password' => md5 ( $params ['password'] ),
				'paypal_email' => $params ['paypal_email'],
				'notification' => $params ['notification'] 
		) );
		
		if (Mage::getSingleton ( 'customer/session' )->isLoggedIn ())
			$model->setData ( 'customer_id', Mage::getSingleton ( 'customer/session' )->getCustomer ()->getId () );
		$model->save ();
	}
	public function createPostAction() {
		$params = $this->getRequest ()->getParams ();
		
		//$this->validate ( $params );
		
		$customer = Mage::getSingleton ( 'customer/session' )->getCustomer ();
		$customerId = $customer->getId ();
		$model = Mage::getModel ( 'affiliate/account' );
		
		$model->setData ( array (
				'paypal_email' => $params ['paypal_email'],
				'notification' => $params ['notification'] ,
				'customer_id'=> $customerId
		) );
		
		$model->save ();
	}
	public function linkAction() {
		$program_id = $this->getRequest ()->getParam ( 'id' );
		$model = Mage::getModel ( 'affiliate/program' )->load ( $program_id );
		
		Mage::register ( 'program', $model );
		
		$websiteId = Mage::app ()->getWebsite ()->getId ();
		$customerId = Mage::getSingleton ( 'customer/session' )->getCustomer ()->getId ();
		$couponCode = '';
		
		$this->loadLayout ();
		$this->renderLayout ();
	}
	public function generatelinkAction() {
		$source = $this->getRequest ()->getParam ( 'source' );
		$id = $this->getRequest ()->getParam ( 'id' );
		$websiteId = Mage::app ()->getWebsite ()->getId ();
		$customerId = Mage::getSingleton ( 'customer/session' )->getCustomer ()->getId ();
		$ats = $id . '_' . $customerId . '_' . $source;
		echo Mage::helper ( 'core' )->encrypt ( $ats );
	}
	public function withdrawalAction() {
		$this->loadLayout ();
		$this->renderLayout ();
	}
	public function withdrawalRequestCreateAction() {
		$params = $this->getRequest ()->getParams ();
		$account_id=0;
		$customerId = Mage::getSingleton ( 'customer/session' )->getCustomer ()->getId ();
		$account_detail = Mage::getResourceModel('affiliate/account')->getDetailByCustomerId($customerId);
		print_r($params);
		var_dump($account_detail);
		if (!$account_detail || empty($account_detail)) {
			return;
			
		}
		
		$account_id = $account_detail[0]['id'];
		
		if (! isset ( $params ['amount'] ) || $params ['amount'])
			
			$withdraw = Mage::getModel ( 'affiliate/withdrawal' )->setAmount ( $params ['amount'] )
		->setData('comment' , $params['comment']) ->setCreatedTime ( now () ) 
		->setData('account_id', $account_id)
		->setData('status',0)
		
		->save();
		$session = Mage::getSingleton ( 'customer/session' );
		/* @var $session Mage_Customer_Model_Session */
		$session->addSuccess($this->__('Your withdrawal request are sent'));
		
		$this->getResponse()->setRedirect(Mage::getUrl('affiliate/account/index'));
	}
}