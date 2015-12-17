<?php 
class HN_Affiliate_Block_Adminhtml_Affiliate_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form {
	protected   $_form = null;
 protected function _prepareForm()
    {
        $model = Mage::registry('model');
      
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('rate_fieldset',
            array('legend' => Mage::helper('affiliate')->__('General Information'))
        );

        
        
        $fieldset->addField('customer', 'link', array(
        		'name' => 'customer',
        		'label' => Mage::helper('affiliate')->__('Customer'),
        		'title' => Mage::helper('affiliate')->__('Customer'),
        		'href' => Mage::helper("adminhtml")->getUrl('adminhtml/customer/edit' , array('id' =>$model['customer_id'])),
        		
        ));
        
        $fieldset->addField('balance', 'label', array(
        		'name' => 'balance',
        		'label' => Mage::helper('affiliate')->__('Available balane'),
        		'title' => Mage::helper('affiliate')->__('Available balane'),
        		
        ));
        $fieldset->addField('pending_balance', 'label', array(
        		'name' => 'pending_balance',
        		'label' => Mage::helper('affiliate')->__('Pending balance'),
        		'title' => Mage::helper('affiliate')->__('Pending balance'),
        		
        ));
        $fieldset->addField('lifetime_balance', 'label', array(
        		'name' => 'lifetime_balance',
        		'label' => Mage::helper('affiliate')->__('Lifetime balance'),
        		'title' => Mage::helper('affiliate')->__('Lifetime balance'),
        		
        ));
        $fieldset->addField('group', 'label', array(
        		'name' => 'group',
        		'label' => Mage::helper('affiliate')->__('Group'),
        		'title' => Mage::helper('affiliate')->__('Group'),
        		
        ));
        
//         $option = array(
//         	'1' => Mage::helper('affiliate')->__('Active'), 
//             '0' => Mage::helper('affiliate')->__('InActive')
//         );
//         $fieldset->addField('is_active', 'select', array(
//             'name' => 'is_active',
//             'label' => Mage::helper('affiliate')->__('Status'),
//             'title' => Mage::helper('affiliate')->__('Status'),
//             'required' => true,
//         	'options'=>$option
//         ));
       
        $form->setValues($model);
        $this->setForm($form);
        

        return parent::_prepareForm();
    }
}
