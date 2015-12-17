<?php
class HN_Affiliate_Model_Resource_Account extends Mage_Core_Model_Resource_Db_Abstract {
	
	/**
	 * Initialize main table and table id field
	 */
	protected function _construct() {
		$this->_init ( 'affiliate/account', 'id' );
	}
	public function isAffiliate($customer) {
		$this->getMainTable();
		$adapter = $this->_getReadAdapter ();
		$select = $adapter->select ()->from (array('main_table'=> $this->getMainTable()) )
		->join(array('c'=> $this->getTable('customer/entity')) , 'c.entity_id = main_table.customer_id' , array('entity_id')) 
		->where ( 'main_table.customer_id = ?', $customer->getId() );
		//$adapter->fetchPairs ( $select );
		//echo $select;
		$result = $adapter->fetchAll($select);
		return $result;
	}
	
	public function getDetail($id) {
		$customerEntityTypeID = Mage::getModel ( 'eav/entity_type' )->loadByCode ( 'customer' )->getId ();
		
		$customerFirstNameAttributeId = Mage::getModel ( 'eav/entity_attribute' )->loadByCode ( $customerEntityTypeID, 'firstname' )->getId ();
		$lastNanemAttId = Mage::getModel ( 'eav/entity_attribute' )->loadByCode ( $customerEntityTypeID, 'lastname' )->getId ();
		
		$resource = Mage::getSingleton ( 'core/resource' );
		
		$customerTbl = $resource->getTableName ( 'customer/entity' );
		$customerGroupTbl ='customer_group'; //$resource->getTableName ( 'customer/group' );
		$nameTbl = $customerTbl . '_varchar';
		$adapter = $this->_getReadAdapter ();
		
		$select = $adapter->select ()->from (array('main_table'=> $this->getMainTable()) )
		->joinInner ( array (
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
		->where('main_table.id=?' , $id)
		->group ( 'main_table.id' );
		
		
		$result = $adapter->fetchAll($select);
		//var_dump($result);
		return $result;
	}
	
	public function getDetailByCustomerId($customer_id) {
				$customerEntityTypeID = Mage::getModel ( 'eav/entity_type' )->loadByCode ( 'customer' )->getId ();
		
		$customerFirstNameAttributeId = Mage::getModel ( 'eav/entity_attribute' )->loadByCode ( $customerEntityTypeID, 'firstname' )->getId ();
		$lastNanemAttId = Mage::getModel ( 'eav/entity_attribute' )->loadByCode ( $customerEntityTypeID, 'lastname' )->getId ();
		
		$resource = Mage::getSingleton ( 'core/resource' );
		
		$customerTbl = $resource->getTableName ( 'customer/entity' );
		$customerGroupTbl ='customer_group'; //$resource->getTableName ( 'customer/group' );
		$nameTbl = $customerTbl . '_varchar';
		$adapter = $this->_getReadAdapter ();
		
		$select = $adapter->select ()->from (array('main_table'=> $this->getMainTable()) )
		->joinInner ( array (
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
		->where('main_table.customer_id=?' , $customer_id)
		->group ( 'main_table.id' );
		
		
		$result = $adapter->fetchAll($select);
		//var_dump($result);
		return $result;
	}
}

