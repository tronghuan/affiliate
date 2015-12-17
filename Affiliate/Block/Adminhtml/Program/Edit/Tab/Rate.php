<?php
class HN_Affiliate_Block_Adminhtml_Program_Edit_Tab_Rate
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare content for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('affiliate')->__('Rate');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('affiliate')->__('Rate');
    }

    /**
     * Returns status flag about this tab can be showed or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('program');

        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('rule_');

        $fieldset = $form->addFieldset('rate_fieldset',
            array('legend' => Mage::helper('affiliate')->__('Rate Information'))
        );

        
        
        $option = array(
        	'fixed_a' => Mage::helper('affiliate')->__('Fixed amount'), 
            'percent_a' => Mage::helper('affiliate')->__('Percentage amount')
        );
        $fieldset->addField('rate', 'select', array(
            'name' => 'rate',
            'label' => Mage::helper('affiliate')->__('Rate'),
            'title' => Mage::helper('affiliate')->__('Rate'),
            'required' => true,
        	'onchange' => 'programControl.reloadRate(event)',
        	'options'=>$option
        ));
        $fieldset->addField('rate_amount', 'text', array(
            'name' => 'rate_amount',
            'label' => Mage::helper('affiliate')->__('Amount'),
            'title' => Mage::helper('affiliate')->__('Amount'),
            'required' => true,
        ));
        $fieldset->addField('is_affiliate', 'hidden', array(
            'name' => 'is_affiliate',
        		'value' =>1,
            'label' => Mage::helper('affiliate')->__('Rate'),
            'title' => Mage::helper('affiliate')->__('Rate'),
            'required' => true,
        ));
        $option = array(
        		'last_month' => Mage::helper('affiliate')->__('Last month'),
        		'all_time' => Mage::helper('affiliate')->__('All time')
        );
        $fieldset->addField('rate_calculation_type', 'select', array(
        		'name' => 'rate_calculation_type',
        		'class' =>'tier',
        		'label' => Mage::helper('affiliate')->__('Rate'),
        		'title' => Mage::helper('affiliate')->__('Rate'),
        		'required' => true,
        	  'onchange' => 'programControl.reloadRate(event)',
        		'options'=>$option
        ));
        


        # tier field
        $fieldset->addField('tier_amount', 'text', array(
        'label' => $this->__('Amount'),
        'name' => 'tier_amount',
        'required' => true,
        'class' => 'requried-entry'
        		));
        
       $form->getElement('tier_amount')->setRenderer($this->getLayout()->createBlock('affiliate/adminhtml_program_edit_tab_rate_tier'));
        # end tier
        
        $renderer = Mage::getBlockSingleton('adminhtml/widget_form_renderer_fieldset')
        ->setTemplate('hn/affiliate/program/rate/fieldset.phtml');
        
        $fieldset = $form->addFieldset('tier_fieldset', array(
        		'legend'=>Mage::helper('salesrule')->__('Apply the rule only if the following conditions are met (leave blank for all products)')
        ))->setRenderer($renderer);
        $model->addData(array('is_affiliate' =>1));
        $form->setValues($model->getData());
        $this->setForm($form);
        

        return parent::_prepareForm();
    }
}
