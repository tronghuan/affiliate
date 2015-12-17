<?php
class HN_Affiliate_Block_Adminhtml_Affiliate_Edit_Tab_Commision extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'programaffiliateGrid' );
		$this->setDefaultSort ( 'id' );
		$this->setSaveParametersInSession ( true );
	}
	protected function _prepareCollection() {
		$collection = Mage::getModel ( 'affiliate/transaction' )->getCollection ();
		$resource = Mage::getSingleton ( 'core/resource' );
		$orderTbl = $resource->getTableName ( 'sales/order' );
		
		$collection->getSelect ()->joinInner ( array (
				'o' => $orderTbl
		), 'o.increment_id = main_table.order_increment_id', array (
				'email' => 'o.customer_email',
				'name' => new Zend_Db_Expr('concat(o.customer_firstname, " ",o.customer_lastname)'),
				'ip' => 'o.remote_ip'
		))
		->where('main_table.affiliate_id = ?' , $this->getRequest()->getParam('id'));
			;	
		$this->setCollection ( $collection );
		
		return parent::_prepareCollection ();
	}
	protected function _prepareColumns() {
		$this->addColumn ( 'created_at', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Created time' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'created_at',
				'type'=>'datetime',
				'filter_index' => 'main_table.created_at'
		)
		);
		$this->addColumn ( 'program_id', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Program id' ),
				'align' => 'right',
				'width' => '20px',
				'index' => 'program_id',
				'filter_index' => 'main_table.program_id'
		)
		);
		$this->addColumn ( 'earn_amount', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Currency' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'earn_amount',
				'type'=>'currency',
				'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),
				'filter_index' => 'main_table.earn_amount'
		)
		);
		$this->addColumn ( 'order_increment_id', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Order' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'order_increment_id',
				'filter_index' => 'main_table.order_increment_id'
		)
		);
		$this->addColumn ( 'rate', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Rate' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'rate',
				'filter_index' => 'main_table.rate'
		)
		);
		$this->addColumn ( 'attrached_amount', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Attrached amount' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'attrached_amount',
				'type'=>'currency',
				'currency_code' => Mage::app()->getStore()->getBaseCurrency()->getCode(),
				'filter_index' => 'main_table.attrached_amount'
		)
		);
		$this->addColumn ( 'email', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Customer Email' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'email',
				'filter_index' => 'main_table.email'
		)
		);
		$this->addColumn ( 'name', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Customer name' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'name',
				'filter_index' => 'main_table.name'
		)
		);
		$this->addColumn ( 'ip', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'From IP' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'ip',
				'filter_index' => 'o.remote_ip'
		)
		);
		$this->addColumn ( 'comment', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Comment' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'comment',
				'filter_index' => 'main_table.comment'
		)
		);
	}
	
}