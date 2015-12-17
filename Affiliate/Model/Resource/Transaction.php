<?php
class HN_Affiliate_Model_Resource_Transaction  extends Mage_Core_Model_Resource_Db_Abstract
{

	/**
	 * Initialize main table and table id field
	*/
	protected function _construct()
	{
		$this->_init('affiliate/transaction', 'id');
	}

	/**
	 * @param string $orderNo
	 * @return Ambigous <multitype:, multitype:mixed Ambigous <string, boolean, mixed> >
	 */
	public function getTransactionByOrderNo($orderNo) {
		$this->getMainTable();
		$adapter = $this->_getReadAdapter ();
		$select = $adapter->select ()->from (array('main_table'=> $this->getMainTable()) )
		->where ( 'main_table.order_increment_id= ?', $orderNo );
		$result = $adapter->fetchAll($select);
		return $result;
	}
}

