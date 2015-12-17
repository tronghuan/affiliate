<?php
class HN_Affiliate_Block_Grid_Renderer_Datetime extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Datetime {
	public function render(Varien_Object $row)
	{
		if ($data = $this->_getValue($row)) {
			if (!$data || intval($data) == 0) {
				return ' ';
	
			}
			$format = $this->_getFormat();
			try {
				$data = Mage::app()->getLocale()
				->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
			}
			catch (Exception $e)
			{
				$data = Mage::app()->getLocale()
				->date($data, Varien_Date::DATETIME_INTERNAL_FORMAT)->toString($format);
			}
			return $data;
		}
		 $this->getColumn()->getDefault();
	}
}
