<?php
class HN_Affiliate_Adminhtml_WithdrawController extends Mage_Adminhtml_Controller_Action {
	public function indexAction() {
		$this->loadLayout();
		$this->_addContent ( $this->getLayout ()->createBlock ( 'affiliate/adminhtml_withdraw_grid' ) );
		
		$this->renderLayout();
	}
}