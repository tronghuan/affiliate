<?php
class HN_Affiliate_Block_Adminhtml_Affiliate_Edit_Tab_Withdrawal extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'programaffiliateGrid' );
		$this->setDefaultSort ( 'id' );
		$this->setSaveParametersInSession ( true );
	}
	
	protected function _prepareCollection() {
		$collection = Mage::getModel ( 'affiliate/withdrawal' )->getCollection ();
		$collection->getSelect ()->where('main_table.account_id = ?' , $this->getRequest()->getParam('id'));
		$this->setCollection ( $collection );
	
		return parent::_prepareCollection ();
	}
	
	public function formatTime($time = null, $format =  Mage_Core_Model_Locale::FORMAT_TYPE_SHORT, $showDate = false) {
		//if (!$time ) {
			return '';
		//} 
		//parent::formatTime($time, format , $showdate);
		
	}
	
	protected function _prepareColumns() {
		$this->addColumn ( 'created_time', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Created time' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'created_time',
				'type'=>'datetime',
		)
		);
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
				'type'=>'currency',
				'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),
		)
		);
		$this->addColumn ( 'amount_paid', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Amount paid' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'amount_paid',
				'type'=>'currency',
				'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),
				
		)
		);
		$this->addColumn ( 'comment', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Comment' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'comment',
		)
		);
		$this->addColumn ( 'status', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Status' ),
				'align' => 'right',
				'width' => '50px',
				'type'=>'options',
				 'options'=>array(
			       0 => $this->__('Pending'),
			       1 => $this->__('Paid'),
			       3 => $this->__('Rejected'),
			       4 => $this->__('Failed')
 		),
				'index' => 'status',
		)
		);
	}
}