<?php
class HN_Affiliate_Block_Adminhtml_Program_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'programaffiliateGrid' );
		$this->setDefaultSort ( 'id' );
		$this->setSaveParametersInSession ( true );
	}
	
	protected function _prepareCollection() {
		$collection = Mage::getModel ( 'affiliate/program' )->getCollection ();
	
		$resource = Mage::getSingleton('core/resource');
		
		$salesrule = $resource->getTableName('salesrule/rule');
		
		
		$collection->getSelect ()
		->joinInner ( array (
				's' => $salesrule
		), 's.program_id = main_table.program_id', array (
				'name'=>'s.name',
				'rule_id'=> 's.rule_id',
				'from_date' =>'s.from_date',
				'to_date' =>'s.to_date',
				'is_active' =>'s.is_active'
		) )
		
		->group ( 'main_table.program_id' );
		$this->setCollection ( $collection );
		
		return parent::_prepareCollection ();
	}
	
	
	protected function _prepareColumns() {
	
		$this->addColumn ( 'program_id', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Id' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'program_id' ,
				'filter_index'=>'main_table.program_id'
	
		) );
		$this->addColumn ( 'name', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Name' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'name' ,
				'filter_index'=>'s.name'
	
		) );
		$this->addColumn ( 'from_date', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Active from' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'from_date' ,
				'type' =>'date',
				'filter_index'=>'s.from_date'
	
		) );
		$this->addColumn ( 'to_date', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Active to' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'to_date' ,
				'type' =>'date',
				'filter_index'=>'s.to_date'
	
		) );
		
		$options = array(
			'0' => Mage::helper('affiliate')->__('Inactive'),
			'1' => Mage::helper('affiliate')->__('Active'),
		);
		$this->addColumn ( 'is_active', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Active' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'is_active' ,
				'type' =>'options',
				'options' =>$options,
				'filter_index'=>'s.is_active'
	
		) );
		
		$options = array(
				'fixed_a' => Mage::helper('affiliate')->__('Fixed amount'),
				'percent_a' => Mage::helper('affiliate')->__('Percent amount'),
		);
		$this->addColumn ( 'rate', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Rate' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'rate' ,
				'type' =>'options',
				'options' =>$options,
				'filter_index'=>'main_table.rate'
	
		) );
	}
	
	public function getRowUrl($row) {
		return $this->getUrl ( '*/*/edit', array (
				'id' => $row->getRuleId () 
		) );
	}
	
	protected function _prepareMassaction() {
		$this->setMassactionIdField ( 'id' );
		$this->getMassactionBlock ()->setFormFieldName ( 'id' );
		$this->getMassactionBlock ()->setUseSelectAll ( true );
	
	
		$this->getMassactionBlock ()->addItem ( 'delete', array (
				'label' => Mage::helper ( 'affiliate' )->__ ( 'Delete' ),
				'url' => $this->getUrl ( '*/adminhtml_program/delete' )
		) );
	
	
	}
}