<?php
/**
 * Book or Rent
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    BookorRent
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class FormFieldStepsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'FormFieldSteps';
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'FormFieldStep'
    );
    public function beforeFilter() 
    {
        $this->Security->validatePost = false;
        parent::beforeFilter();
    }
    public function admin_index($id = null) 
    {
        $this->pageTitle = __l('Form Field Steps');
        $this->paginate = array(
            'conditions' => array(
                'FormFieldStep.category_id' => $id
            ) ,
            'order' => array(
                'FormFieldStep.order' => 'ASC'
            ) ,
            'recursive' => -1
        );
        $this->set('FormFieldSteps', $this->paginate());
        $this->set('displayFields', $this->FormFieldStep->displayFields());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Form Field Step');
        $payment_step_avail = $this->FormFieldStep->find('all', array(
            'conditions' => array(
                'FormFieldStep.category_id' => $this->request->params['named']['type_id'],
                'FormFieldStep.is_payment_step' => 1
            ) ,
            'recursive' => -1
        ));
        $this->set('payment_step_avail', $payment_step_avail);
        $payout_step_avail = $this->FormFieldStep->find('all', array(
            'conditions' => array(
                'FormFieldStep.category_id' => $this->request->params['named']['type_id'],
                'FormFieldStep.is_payout_step' => 1
            ) ,
            'recursive' => -1
        ));
        $this->set('payout_step_avail', $payout_step_avail);
        if (!empty($this->request->data)) {
            $this->FormFieldStep->create();
            $formFieldStep = $this->FormFieldStep->find('all', array(
                'conditions' => array(
                    'FormFieldStep.category_id' => $this->request->data['FormFieldStep']['category_id']
                ) ,
                'fields' => array(
                    'MAX(FormFieldStep.order) AS step'
                ) ,
                'recursive' => -1
            ));
            $this->request->data['FormFieldStep']['order'] = $formFieldStep[0][0]['step']+1;
            if ($this->FormFieldStep->save($this->request->data)) {
                $this->Session->setFlash(__l('Form Field Step has been added') , 'default', null, 'success');
                if ($this->RequestHandler->isAjax()) {
                    echo "success";
                    exit;
                } else {
                    $this->redirect(array(
                        'controller' => 'categories',
                        'action' => 'form_field_edit',
                        $this->request->params['named']['type_id']
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Form Field Step could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        if (!empty($this->request->params['named']['type_id'])) {
            $this->request->data['FormFieldStep']['category_id'] = $this->request->params['named']['type_id'];
        }
    }
    public function admin_edit($id = null, $type_id = null) 
    {
        $this->pageTitle = __l('Edit Form Field Step');
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__l('Invalid Form Field Step') , 'default', null, 'error');
            $this->redirect(array(
                'action' => 'index',
            ));
        }
        $payment_step_avail = $this->FormFieldStep->find('all', array(
            'conditions' => array(
                'FormFieldStep.category_id' => $type_id,
                'FormFieldStep.is_payment_step' => 1
            ) ,
            'recursive' => -1
        ));
        $this->set('payment_step_avail', $payment_step_avail);
        $payout_step_avail = $this->FormFieldStep->find('all', array(
            'conditions' => array(
                'FormFieldStep.category_id' => $type_id,
                'FormFieldStep.is_payout_step' => 1
            ) ,
            'recursive' => -1
        ));
        $this->set('payout_step_avail', $payout_step_avail);
        if (!empty($this->request->data)) {
            if ($this->FormFieldStep->save($this->request->data)) {
                $this->Session->setFlash(__l('Form Field Step has been updated') , 'default', null, 'success');
                if ($this->RequestHandler->isAjax()) {
                    echo "success";
                    exit;
                } else {
                    $this->redirect(array(
                        'controller' => 'categories',
                        'action' => 'form_field_edit',
                        $this->request->data['FormFieldStep']['category_id']
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Form Field Step could not be updated. Please, try again.'), 'default', null, 'error');
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->FormFieldStep->read(null, $id);
        }
        $this->pageTitle.= ' - ' . $this->request->data['FormFieldStep']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (!$id) {
            $this->Session->setFlash(__l('Invalid Form Field Step') , 'default', null, 'error');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $formFieldStep = $this->FormFieldStep->find('first', array(
            'conditions' => array(
                'FormFieldStep.id' => $id,
                'FormFieldStep.is_deletable' => 1
            ) ,
            'recursive' => -1
        ));
        $type_id = $formFieldStep['FormFieldStep']['category_id'];
        if (empty($formFieldStep)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->FormFieldStep->delete($id)) {
            $this->loadModel('Items.FormFieldGroup');
            $this->loadModel('Items.FormField');
            $form_field_groups = $this->FormFieldGroup->find('all', array(
                'conditions' => array(
                    'FormFieldGroup.form_field_step_id' => $id
                )
            ));
            foreach($form_field_groups as $form_field_group) {
                $this->FormFieldGroup->delete($form_field_group['FormFieldGroup']['id']);
                $form_fields = $this->FormField->find('all', array(
                    'conditions' => array(
                        'FormField.form_field_group_id' => $form_field_group['FormFieldGroup']['id']
                    )
                ));
                foreach($form_fields as $form_field) {
                    $this->FormField->delete($form_field['FormField']['id']);
                }
            }
            $updateformFieldStep = $this->FormFieldStep->find('all', array(
                'conditions' => array(
                    'FormFieldStep.category_id' => $type_id,
                ) ,
                'recursive' => -1,
                'order' => array(
                    'FormFieldStep.order' => 'ASC'
                ) ,
            ));
            if (!empty($updateformFieldStep)) {
                $order = 1;
                foreach($updateformFieldStep as $field) {
                    $this->FormFieldStep->id = $field['FormFieldStep']['id'];
                    $this->FormFieldStep->saveField('order', $order);
                    $order++;
                }
            }
            $this->Session->setFlash(__l('Form Field Step deleted'), 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'categories',
                'action' => 'form_field_edit',
                $formFieldStep['FormFieldStep']['category_id']
            ));
        }
    }
    public function admin_sort() 
    {
        if ($this->RequestHandler->isAjax()) {
            $order = 1;
            foreach($this->request->data['FormFieldStep'] as $field) {
                $this->FormFieldStep->create();
                $this->FormFieldStep->id = $field['id'];
                $this->FormFieldStep->saveField('order', $order);
                $order++;
            }
            $this->set('response', 'success');
            $this->render('../Elements/ajax_reponse');
        }
    }
}
