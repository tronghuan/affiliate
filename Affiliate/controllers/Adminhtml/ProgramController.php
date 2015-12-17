<?php
class HN_Affiliate_Adminhtml_ProgramController extends Mage_Adminhtml_Controller_Action {
	public function indexAction() {
		$this->_title ( $this->__ ( 'Affiliate' ) )->_title ( $this->__ ( 'Program Rules' ) );
		
		$this->loadLayout ();
		$this->_addContent ( $this->getLayout ()->createBlock ( 'affiliate/adminhtml_program' ) );
		
		$this->renderLayout ();
		// $this->_initAction()
		// ->_addBreadcrumb(Mage::helper('salesrule')->__('Catalog'), Mage::helper('salesrule')->__('Catalog'))
		// ->renderLayout();
	}
	public function newAction() 

	{
		// $this->_forward('edit');
		$this->_redirect ( '*/*/edit' );
		// $this->_forward('edit' , 'HN_Affiliate_Adminhtml_ProgramController','HN_Affiliate');
	}
	public function editAction() {
		$id = $this->getRequest ()->getParam ( 'id' );
		$model = Mage::getModel ( 'salesrule/rule' );
		
		if ($id) {
			$model->load ( $id );
			if (! $model->getRuleId ()) {
				Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'salesrule' )->__ ( 'This rule no longer exists.' ) );
				$this->_redirect ( '*/*' );
				return;
			}
		}
		
		$this->_title ( $model->getRuleId () ? $model->getName () : $this->__ ( 'New Rule' ) );
		
		// set entered data if was error when we do save
		$data = Mage::getSingleton ( 'adminhtml/session' )->getPageData ( true );
		if (! empty ( $data )) {
			$model->addData ( $data );
		}
		
		$model->getConditions ()->setJsFormObject ( 'rule_conditions_fieldset' );
		$model->getActions ()->setJsFormObject ( 'rule_actions_fieldset' );
		// var_dump($model->getData('program_id'));
		$program = Mage::getModel ( 'affiliate/program' )->load ( $model->getData ( 'program_id' ) );
		// $model->addData($program->getData());
		Mage::register ( 'current_promo_quote_rule', $model );
		
		Mage::register ( 'program', $program );
		
		// $this->_initAction()->getLayout()->getBlock('promo_quote_edit')
		// ->setData('action', $this->getUrl('*/*/save'));
		
		// $this
		// ->_addBreadcrumb(
		// $id ? Mage::helper('salesrule')->__('Edit Rule')
		// : Mage::helper('salesrule')->__('New Rule'),
		// $id ? Mage::helper('salesrule')->__('Edit Rule')
		// : Mage::helper('salesrule')->__('New Rule'))
		// ->renderLayout();
		
		$this->loadLayout ();
		$this->renderLayout ();
	}
	public function newConditionHtml() {
	}
	protected function _isAllowed() {
		return Mage::getSingleton ( 'admin/session' )->isAllowed ( 'affiliate/items' );
	}
	public function saveAction() {
		$params = $this->getRequest ()->getParams ();
		var_dump ( $params );
		
		$data = array ();
		$data ['rate'] = $params ['rate'];
		$data ['rate_calculation_type'] = $params ['rate_calculation_type'];
		$data['rate_amount'] = $params['rate_amount'];
		/* @var $model HN_Affiliate_Model_Program */
		$model = Mage::getModel ( 'affiliate/program' );
		$id = $this->getRequest ()->getParam ( 'program_id' );
		
		if ($id) {
			$data ['program_id'] = $params ['program_id'];
		}
		
		try {
			
			$model->setData ( $data )->save ();
			
			$program_id = $model->getId ();
			$this->saveRule ( $program_id );
		} catch ( Exception $exception ) {
			Mage::logException ( $exception );
			Mage::getSingleton ( 'adminhtml/session' )->addError ( $exception->getMessage () );
		}
		
		//Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( Mage::helper ( 'affiliate' )->__ ( 'Program was successfully saved' ) );
		
		$this->_redirect ( '*/*/' );
	}
	public function saveRule($program_id) {
		if ($this->getRequest ()->getPost ()) {
			try {
				/**
				 *
				 * @var $model Mage_SalesRule_Model_Rule
				 */
				$model = Mage::getModel ( 'salesrule/rule' );
				Mage::dispatchEvent ( 'adminhtml_controller_salesrule_prepare_save', array (
						'request' => $this->getRequest () 
				) );
				$data = $this->getRequest ()->getPost ();
				
				unset ( $data ['rate_calculation_type'] );
				unset ( $data ['rate'] );
				unset ( $data ['program_id'] );
				$data ['program_id'] = $program_id;
				$data = $this->_filterDates ( $data, array (
						'from_date',
						'to_date' 
				) );
				$id = $this->getRequest ()->getParam ( 'rule_id' );
				if ($id) {
					$model->load ( $id );
					if ($id != $model->getId ()) {
						Mage::throwException ( Mage::helper ( 'salesrule' )->__ ( 'Wrong rule specified.' ) );
					}
				}
				
				$session = Mage::getSingleton ( 'adminhtml/session' );
				
				$validateResult = $model->validateData ( new Varien_Object ( $data ) );
				if ($validateResult !== true) {
					foreach ( $validateResult as $errorMessage ) {
						$session->addError ( $errorMessage );
					}
					$session->setPageData ( $data );
					// $this->_redirect('*/*/edit', array('id'=>$model->getId()));
					return;
				}
				
				if (isset ( $data ['simple_action'] ) && $data ['simple_action'] == 'by_percent' && isset ( $data ['discount_amount'] )) {
					$data ['discount_amount'] = min ( 100, $data ['discount_amount'] );
				}
				if (isset ( $data ['rule'] ['conditions'] )) {
					$data ['conditions'] = $data ['rule'] ['conditions'];
				}
				if (isset ( $data ['rule'] ['actions'] )) {
					$data ['actions'] = $data ['rule'] ['actions'];
				}
				unset ( $data ['rule'] );
				$model->loadPost ( $data );
				
				$useAutoGeneration = ( int ) ! empty ( $data ['use_auto_generation'] );
				$model->setUseAutoGeneration ( $useAutoGeneration );
				
				$session->setPageData ( $model->getData () );
				
				$model->save ();
				$session->addSuccess ( Mage::helper ( 'salesrule' )->__ ( 'The rule has been saved.' ) );
				$session->setPageData ( false );
				if ($this->getRequest ()->getParam ( 'back' )) {
					// $this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				// $this->_redirect('*/*/');
				return;
			} catch ( Mage_Core_Exception $e ) {
				$this->_getSession ()->addError ( $e->getMessage () );
				$id = ( int ) $this->getRequest ()->getParam ( 'rule_id' );
				if (! empty ( $id )) {
					// $this->_redirect('*/*/edit', array('id' => $id));
				} else {
					// $this->_redirect('*/*/new');
				}
				return;
			} catch ( Exception $e ) {
				$this->_getSession ()->addError ( Mage::helper ( 'catalogrule' )->__ ( 'An error occurred while saving the rule data. Please review the log and try again.' ) );
				Mage::logException ( $e );
				Mage::getSingleton ( 'adminhtml/session' )->setPageData ( $data );
				// $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('rule_id')));
				return;
			}
		}
	}
	
	public function deleteAction() {
		$ids = $this->getRequest ()->getParam ( 'id' );
		if (! is_array ( $ids )) {
			Mage::getSingleton ( 'adminhtml/session' )->addError ( Mage::helper ( 'adminhtml' )->__ ( 'Please select item(s)' ) );
		} else {
			try {
				foreach ( $ids as $id ) {
					$model = Mage::getModel ( 'affiliate/program' )->load ( $id );
					$model->delete ();
				}
				Mage::getSingleton ( 'adminhtml/session' )->addSuccess ( Mage::helper ( 'adminhtml' )->__ ( 'Total of %d record(s) were successfully deleted', count ( $ids ) ) );
			} catch ( Exception $e ) {
				Mage::getSingleton ( 'adminhtml/session' )->addError ( $e->getMessage () );
			}
		}
		$this->_redirect ( '*/*/index' );
	}
}
