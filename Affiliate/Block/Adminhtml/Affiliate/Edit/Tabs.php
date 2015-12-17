<?php
class HN_Affiliate_Block_Adminhtml_Affiliate_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {
	/**
	 */
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'affiliate_rule_tabs' );
		$this->setDestElementId ( 'edit_form' );
		$this->setTitle ( Mage::helper ( 'affiliate' )->__ ( 'Affiliate Account' ) );
	}

	/*
	 * (non-PHPdoc) @see Mage_Adminhtml_Block_Widget_Tabs::_beforeToHtml()
	*/
	protected function _beforeToHtml() {
		$this->addTab ( 'form_section_general', array (
				'label' => Mage::helper ( 'affiliate' )->__ ( 'General' ),
				'title' => Mage::helper ( 'affiliate' )->__ ( 'General' ),
				'content' => $this->getLayout ()->createBlock ( 'affiliate/adminhtml_affiliate_edit_tab_general' )->toHtml ()
		) );

		$this->addTab ( 'form_section_condition', array (
				'label' => Mage::helper ( 'affiliate' )->__ ( 'Commision' ),
				'title' => Mage::helper ( 'affiliate' )->__ ( 'Commision' ),
				'content' => $this->getLayout ()->createBlock ( 'affiliate/adminhtml_affiliate_edit_tab_commision' )->toHtml ()
		) );
		$this->addTab ( 'form_section_withdraw', array (
				'label' => Mage::helper ( 'affiliate' )->__ ( 'Withdrawals' ),
				'title' => Mage::helper ( 'affiliate' )->__ ( 'Withdrawals' ),
				'content' => $this->getLayout ()->createBlock ( 'affiliate/adminhtml_affiliate_edit_tab_withdraw' )->toHtml ()
		) );


		return parent::_beforeToHtml ();
	}
}
