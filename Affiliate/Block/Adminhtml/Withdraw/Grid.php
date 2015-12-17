<?php
class HN_Affiliate_Block_Adminhtml_Withdraw_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'programaffiliateGrid' );
		$this->setDefaultSort ( 'id' );
		$this->setSaveParametersInSession ( true );
	}
	protected function _prepareCollection() {
		$collection = Mage::getModel ( 'affiliate/withdrawal' )->getCollection ();
		//$collection->getSelect ()->where ( 'main_table.account_id = ?', $this->getRequest ()->getParam ( 'id' ) );
		$this->setCollection ( $collection );
		
		return parent::_prepareCollection ();
	}
	protected function _prepareColumns() {
		$this->addColumn ( 'created_time', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Created time' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'created_time',
				'type' => 'datetime' 
		) );
		$this->addColumn ( 'update_time', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Processed time' ),
				'align' => 'right',
				'width' => '20px',
				'index' => 'update_time',
				'renderer'=>'HN_Affiliate_Block_Grid_Renderer_Datetime',
				 
		)
		 );
		$this->addColumn ( 'amount', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Amount request' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'amount',
				'type' => 'currency' ,
				'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),
				
		) );
		$this->addColumn ( 'amount_paid', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Amount paid' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'amount_paid',
				'type' => 'currency' ,
				'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),
				
		) );
		$this->addColumn ( 'comment', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Comment' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'comment' 
		) );
		$this->addColumn ( 'status', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Status' ),
				'align' => 'right',
				'width' => '50px',
				'type' => 'options',
				'options' => array (
						0 => $this->__ ( 'Pending' ),
						1 => $this->__ ( 'Paid' ),
						3 => $this->__ ( 'Rejected' ),
						4 => $this->__ ( 'Failed' ) 
				),
				'index' => 'status' 
		) );
	}
	
	protected function _prepareMassaction()
	{
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('id');
		$this->getMassactionBlock()->setUseSelectAll(true);
		
		$this->getMassactionBlock()->addItem('changestatus_pin', array(
				'label'=> Mage::helper('sales')->__('Change Status'),
				'url'  => $this->getUrl('*/adminhtml_withdraw/changeStatus'),
				'additional' => array(
						'visibility' => array(
								'name' => 'status',
								'type' => 'select',
								'class' => 'required-entry',
								'label' => Mage::helper('catalog')->__('Status'),
								'values' => array(
							      array('label'=>$this->__('Pending') , 'value' => 0 ),
							      array('label'=>$this->__('Paid') , 'value' => 1 ),
							      array('label'=>$this->__('Rejected') , 'value' => 2 ),
							      array('label'=>$this->__('Failed') , 'value' => 3 ),
						)
						)
				)));
	}
}