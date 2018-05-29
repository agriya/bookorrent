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
class FormFieldsController extends AppController
{
    public $name = 'FormFields';
    public function beforeFilter() 
    {
        $this->Security->validatePost = false;
        parent::beforeFilter();
    }
    public function admin_add($formId = null) 
    {
        $response = false;
        if (!empty($this->request->data)) {
            $this->FormField->create();
            $this->request->data['FormField']['name'] = Inflector::slug($this->request->data['FormField']['label'], '_');
            $count = $this->FormField->find('count', array(
                'conditions' => array(
                    'OR' => array(
                        'FormField.name' => $this->request->data['FormField']['name'],
                        'FormField.name LIKE' => $this->request->data['FormField']['name'] . '%'
                    )
                )
            ));
            if (!empty($count)) {
                $name = $this->request->data['FormField']['name'] . '_' . ($count+1);
                $this->request->data['FormField']['name'] = $name;
            }
            if ($this->FormField->save($this->request->data)) {
                if ($this->RequestHandler->isAjax()) {
                    $response = $this->FormField->id;
                    echo 'success*' . $response . '*' . $this->request->data['FormField']['form_field_group_id'];
                    exit;
                } else {
                    $this->Session->setFlash(__l('Form field added.') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'categories',
                        'action' => 'form_field_edit',
                        $this->request->data['FormField']['category_id'],
                        'type' => 'form_fields'
                    ));
                }
            } else {
                $types = $this->FormField->types;
                $this->set('types', $types);
                $this->render('admin_add');
            }
        } elseif ($formId) {
            $this->request->data['FormField']['category_id'] = $formId;
            $types = $this->FormField->types;
            $this->set('types', $types);
        }
        $this->request->data['FormField']['form_field_group_id'] = $this->request->params['named']['group_id'];
    }
    public function admin_get_row($id) 
    {
        $field = $this->FormField->find('first', array(
            'conditions' => array(
                'FormField.id' => $id
            ) ,
            'recursive' => 2
        ));
        $field['FormField']['is_active'] = true;
        $field = $field['FormField'];
        $multiTypes = $this->FormField->multiTypes;
        $types = $this->FormField->types;
        $key = $this->FormField->find('count', array(
            'conditions' => array(
                'FormField.category_id' => $field['category_id']
            )
        ));
        $this->set(compact('field', 'multiTypes', 'key', 'types'));
        $this->render('../Elements/form_field_row');
    }
    public function admin_edit($id = null) 
    {
        //  if ($this->RequestHandler->isAjax()) {
        if (!$id && empty($this->request->data)) {
            $this->set('response', null);
            $this->render('../Elements/ajax_reponse');
        } elseif (!empty($this->request->data)) {
            if (!empty($this->request->data['FormField']['is_dynamic_field'])) {
                $this->request->data['FormField']['name'] = Inflector::slug($this->request->data['FormField']['label'], '_');
            }
            if ($this->FormField->save($this->request->data, false)) {
                $this->set('response', 'success');
                $this->Session->setFlash(__l('Form Field has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'categories',
                    'action' => 'form_field_edit',
                    $this->request->data['FormField']['category_id']
                ));
            } else {
                $this->set('response', null);
            }
            $this->render('../Elements/ajax_reponse');
        } else {
            $form_field = $this->FormField->read(null, $id);
            $dependson_fields = $this->FormField->find('list', array(
                'conditions' => array(
                    'FormField.category_id' => $form_field['FormField']['category_id']
                ) ,
                'fields' => array(
                    'FormField.name',
                    'FormField.label'
                )
            ));
            $this->set('dependson_fields', $dependson_fields);
            $this->request->data = $form_field;
        }
        return true;
        // } else {
        //      $this->redirect('/');
        //  }
        
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->RequestHandler->isAjax()) {
            $response = 'failure';
            if ($id) {
                if ($this->FormField->delete($id)) {
                    $response = 'success';
                }
            }
            $this->set('response', $response);
            $this->render('../Elements/ajax_reponse');
            return true;
        }
        if ($this->FormField->delete($id)) {
            $this->Session->setFlash(__l('FormField deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'categories',
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_sort() 
    {
        if ($this->RequestHandler->isAjax()) {
            $order = 0;
            foreach($this->request->data['FormField'] as $field) {
                $this->FormField->create();
                $this->FormField->id = $field['id'];
                $this->FormField->saveField('order', $order);
                $order++;
            }
            $this->set('response', 'success');
            $this->render('../Elements/ajax_reponse');
            return true;
        }
    }
}
?>