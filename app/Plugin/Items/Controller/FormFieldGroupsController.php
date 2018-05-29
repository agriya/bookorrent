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
class FormFieldGroupsController extends AppController
{
    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'FormFieldGroups';
    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array(
        'FormFieldGroup'
    );
    public function beforeFilter() 
    {
        $this->Security->validatePost = false;
        parent::beforeFilter();
    }
    public function admin_index($id = null) 
    {
        $this->pageTitle = __l('Form Field Groups');
        $this->paginate = array(
            'conditions' => array(
                'FormFieldGroup.category_id' => $id
            ) ,
            'order' => array(
                'FormFieldGroup.order' => 'ASC'
            ) ,
            'recursive' => -1
        );
        $this->set('FormFieldGroups', $this->paginate());
        $this->set('displayFields', $this->FormFieldGroup->displayFields());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Form Field Group');
        if (!empty($this->request->data)) {
            $this->FormFieldGroup->create();
            if ($this->FormFieldGroup->save($this->request->data)) {
                $this->Session->setFlash(__l('Form Field Group has been added') , 'default', null, 'success');
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
                $this->Session->setFlash(__l('Form Field Group could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            if (!empty($this->request->params['named']['type_id'])) {
                $this->request->data['FormFieldGroup']['category_id'] = $this->request->params['named']['type_id'];
            }
            if (!empty($this->request->params['named']['step_id'])) {
                $this->request->data['FormFieldGroup']['form_field_step_id'] = $this->request->params['named']['step_id'];
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Form Field Group');
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__l('Invalid Form Field Group') , 'default', null, 'error');
            $this->redirect(array(
                'action' => 'index',
            ));
        }
        if (!empty($this->request->data)) {
            if ($this->FormFieldGroup->save($this->request->data)) {
                $this->Session->setFlash(__l('Form Field Group has been updated') , 'default', null, 'success');
                if ($this->RequestHandler->isAjax()) {
                    echo "success";
                    exit;
                } else {
                    $this->redirect(array(
                        'controller' => 'categories',
                        'action' => 'form_field_edit',
                        $this->request->data['FormFieldGroup']['category_id']
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Form Field Group could not be updated. Please, try again.') , 'default', null, 'error');
            }
        }
        if (empty($this->request->data)) {
            $this->request->data = $this->FormFieldGroup->read(null, $id);
        }
        $this->pageTitle.= ' - ' . $this->request->data['FormFieldGroup']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (!$id) {
            $this->Session->setFlash(__l('Invalid Form Field Group') , 'default', null, 'error');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $formFieldGroup = $this->FormFieldGroup->find('first', array(
            'conditions' => array(
                'FormFieldGroup.id' => $id,
                'FormFieldGroup.is_deletable' => 1
            ) ,
            'recursive' => -1
        ));
        if (empty($formFieldGroup)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->FormFieldGroup->delete($id)) {
            $this->loadModel('Items.FormField');
            $form_fields = $this->FormField->find('all', array(
                'conditions' => array(
                    'FormField.form_field_group_id' => $id
                )
            ));
            foreach($form_fields as $form_field) {
                $this->FormField->delete($form_field['FormField']['id']);
            }
            $this->Session->setFlash(__l('Form Field Group deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'categories',
                'action' => 'form_field_edit',
                $formFieldGroup['FormFieldGroup']['category_id']
            ));
        }
    }
    public function admin_sort() 
    {
        if ($this->RequestHandler->isAjax()) {
            $order = 0;
            foreach($this->request->data['FormFieldGroup'] as $field) {
                $this->FormFieldGroup->create();
                $this->FormFieldGroup->id = $field['id'];
                $this->FormFieldGroup->saveField('order', $order);
                $order++;
            }
            $this->set('response', 'success');
            $this->render('../Elements/ajax_reponse');
        }
    }
}
