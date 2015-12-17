<?php
class HN_Affiliate_Adminhtml_AffiliateController extends Mage_Adminhtml_Controller_Action {
	public function indexAction() {
		$this->_title ( $this->__ ( 'Affiliate' ) )->_title ( $this->__ ( 'Manage Affiliates' ) );
	
		$this->loadLayout ();
		$this->_addContent ( $this->getLayout ()->createBlock ( 'affiliate/adminhtml_affiliate' ) );
	
		$this->renderLayout ();
	}
	
	public function editAction() {
		$id = $this->getRequest ()->getParam ( 'id' );
		$model = Mage::getModel ( 'affiliate/account' );
	
		if ($id) {
			$model->load ( $id );
			if (! $model->getId ()) {
				Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'core' )->__ ( 'This affiliate account no longer exists.' ) );
				$this->_redirect ( '*/*' );
				return;
			}
		}
		
		$resource = Mage::getResourceModel('affiliate/account')->getDetail($id);
		
		//var_dump($resource);
		$model = $resource[0];
		$model['customer' ]=  $model['first_name'] . ' ' .$model['last_name'] . ' <' . $model['email'] . ' >' ;
	    Mage::register('model', $model);
		$this->_title (  $this->__ ( 'Edit Affiliate Account' ) );
		$this->loadLayout ();
		//$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
	
			$this->_addContent($this->getLayout()->createBlock('affiliate/adminhtml_affiliate_edit'))
			->_addLeft($this->getLayout()->createBlock('affiliate/adminhtml_affiliate_edit_tabs'));
	
			$this->renderLayout();
	}
}