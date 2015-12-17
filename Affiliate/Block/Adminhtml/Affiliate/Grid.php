<?php
class HN_Affiliate_Block_Adminhtml_Affiliate_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct ();
		$this->setId ( 'programaffiliateGrid' );
		$this->setDefaultSort ( 'id' );
		$this->setSaveParametersInSession ( true );
	}
	protected function _prepareCollection() {
		$customerEntityTypeID = Mage::getModel ( 'eav/entity_type' )->loadByCode ( 'customer' )->getId ();
		
		$customerFirstNameAttributeId = Mage::getModel ( 'eav/entity_attribute' )->loadByCode ( $customerEntityTypeID, 'firstname' )->getId ();
		$lastNanemAttId = Mage::getModel ( 'eav/entity_attribute' )->loadByCode ( $customerEntityTypeID, 'lastname' )->getId ();
		$collection = Mage::getModel ( 'affiliate/account' )->getCollection ();
		
		$resource = Mage::getSingleton ( 'core/resource' );
		
		$customerTbl = $resource->getTableName ( 'customer/entity' );
		$customerGroupTbl ='customer_group'; //$resource->getTableName ( 'customer/group' );
		$nameTbl = $customerTbl . '_varchar';
		
		$collection->getSelect ()->joinInner ( array (
				'c' => $customerTbl 
		), 'c.entity_id = main_table.customer_id', array (
				'email' => 'c.email',
				'group_id' => 'c.group_id' 
		)
				
		 )->join ( array (
				'v' => $nameTbl 
		), "v.entity_id = c.entity_id and v.attribute_id = {$customerFirstNameAttributeId}", array (
				'first_name' => 'v.value' 
		) )
		->join ( array (
				'l' => $nameTbl
		), "l.entity_id = c.entity_id and l.attribute_id = {$lastNanemAttId}", array (
				'last_name' => 'l.value'
		) )
		->join(array('g'=>$customerGroupTbl), 'g.customer_group_id = c.group_id' , array('group'=> 'g.customer_group_code'))
		->group ( 'main_table.id' );
		$this->setCollection ( $collection );
		
		return parent::_prepareCollection ();
	}
	protected function _prepareColumns() {
		$this->addColumn ( 'id', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Id' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'id',
				'filter_index' => 'main_table.id' 
		)
		 );
		$this->addColumn ( 'first_name', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'First Name' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'first_name',
				'filter_index' => 'v.value' 
		)
		 );
		$this->addColumn ( 'last_name', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Last Name' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'last_name',
				'filter_index' => 'l.value' 
		)
		 );
		$this->addColumn ( 'email', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Email' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'email',
				'filter_index' => 'c.email' 
		)
		 );
		$this->addColumn ( 'group', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Customer group' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'group',
				'filter_index' => 'g.customer_group_code' 
		)
		 );
		
		$options = array('0'=> $this->__('In active') , '1'=> $this->__('Active') );
		
		$this->addColumn ( 'is_active', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Status' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'is_active',
				'type'=>'option' ,
				'options' =>$options,
				'filter_index' => 'main_table.is_active' 
		)
		 );
		$this->addColumn ( 'balance', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Balance' ),
				'align' => 'right',
				'width' => '50px',
				'index' => 'balance',
				'type' =>'currency',
				'filter_index' => 'main_table.balance' 
		)
		 );
		
		$this->addColumn ( 'action', array (
				'header' => Mage::helper ( 'affiliate' )->__ ( 'Action' ),
				'width' => '100',
				'type' => 'action',
				'getter' => 'getId',
				'actions' => array (
						array (
								'caption' => Mage::helper ( 'affiliate' )->__ ( 'Edit' ),
								'url' => array (
										'base' => '*/*/edit'
								),
								'field' => 'id'
						)
				),
				'filter' => false,
				'sortable' => false,
				'index' => 'stores',
				'is_system' => true
		) );
	/* Mage_Adminhtml_Block_Widget_Grid_Container  */	
	}
	
	public function getRowUrl($row) {
		return $this->getUrl ( '*/*/edit', array (
				'id' => $row->getId ()
		) );
	}
}