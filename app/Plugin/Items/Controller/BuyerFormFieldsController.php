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
class BuyerFormFieldsController extends AppController
{
    public $name = 'BuyerFormFields';
    public function beforeFilter() 
    {
        $this->Security->validatePost = false;
        parent::beforeFilter();
    }
    public function index($item_id)
	{
		if (is_null($item_id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$item = $this->BuyerFormField->Item->find('first', array(
			'conditions' => array(
				'Item.id' => $item_id,
			),
			'recursive' => -1
		));
		if(empty($item) || $item['Item']['user_id'] != $this->Auth->user('id')) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$this->pageTitle = __l('Collect data from buyers') . ' - ' . $item['Item']['title'];
		$conditions = array();
		$conditions['BuyerFormField.item_id'] = $item_id;
		$this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'Item',
			),
            'order' => array(
                'BuyerFormField.id' => 'desc'
            ) ,
            'recursive' => 0
        );
		$this->set('buyer_form_fields', $this->paginate());
		$this->set('item', $item);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.BuyerFormField.Index', $this, array());
		}			
	}
	public function add($item_id)
	{
		if (is_null($item_id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$item = $this->BuyerFormField->Item->find('first', array(
			'conditions' => array(
				'Item.id' => $item_id,
			),
			'recursive' => -1
		));
		if(empty($item) || $item['Item']['user_id'] != $this->Auth->user('id')) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		if (!empty($this->request->data)) {
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['BuyerFormField'] = $this->request->data;
			}		
            $this->BuyerFormField->create();
			$this->request->data['BuyerFormField']['name'] = Inflector::slug(strtolower($this->request->data['BuyerFormField']['label']), '_');
            if ($this->BuyerFormField->save($this->request->data)) {
                $this->Session->setFlash(__l('Buyer Form Field has been added') , 'default', null, 'success');
				$this->set('iphone_response', array("message" => __l('Buyer Form Field has been added'), "error" => 0));
				if (!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
						'action' => 'index',
						$item['Item']['id']
					));
				}
            } else {
                $this->Session->setFlash(__l('Buyer Form Field could not be added. Please, try again.') , 'default', null, 'error');
				$this->set('iphone_response', array("message" => __l('Buyer Form Field could not be added. Please, try again.'), "error" => 0));
            }
        }
		$this->set('item', $item);
		$types = $this->BuyerFormField->types;
        $this->set('types', $types);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.BuyerFormField.Add', $this, array());
		}		
	}
	public function edit($id = null)
    {
        $this->pageTitle = __l('Edit Buyer Form Field');
        if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        if (!empty($this->request->data)) {
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['BuyerFormField'] = $this->request->data;
			}			
            if ($this->BuyerFormField->save($this->request->data)) {
                $this->Session->setFlash(__l('Buyer Form Field has been updated') , 'default', null, 'success');
				$this->set('iphone_response', array("message" => __l('Buyer Form Field has been updated'), "error" => 0));
				if (!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
						'action' => 'index',
						$this->request->data['BuyerFormField']['item_id']
					));
				}
            } else {
                $this->Session->setFlash(__l('Buyer Form Field could not be updated. Please, try again.') , 'default', null, 'error');
				$this->set('iphone_response', array("message" => __l('Buyer Form Field could not be updated. Please, try again.'), "error" => 0));
            }
        } else {
            $this->request->data = $this->BuyerFormField->find('first', array(
				'conditions' => array(
					'BuyerFormField.id' => $id
				),
				'contain' => array(
					'Item'
				),
				'recursive' => 0
			));
            if (empty($this->request->data)) {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
            }
			$this->set('buyer_form_field', $this->request->data);
        }
		if(!empty($this->request->data['BuyerFormField']['name']))
			$this->pageTitle.= ' - ' . $this->request->data['BuyerFormField']['name'];
		$types = $this->BuyerFormField->types;
        $this->set('types', $types);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.BuyerFormField.Edit', $this, array());
		}		
    }
	public function delete($id = null)
    {
		$status = true;
        if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$status = false;
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{		
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		if($status){
			$buyer_form_field = $this->BuyerFormField->find('first', array(
				'conditions' => array(
					'BuyerFormField.id' => $id,
				),
				'recursive' => -1
			));
			$item_id = $buyer_form_field['BuyerFormField']['item_id'];
			if ($this->BuyerFormField->delete($id)) {
				$this->set('iphone_response', array("message" => __l('Buyer Form Field deleted'), "error" => 0));
				$this->Session->setFlash(__l('Buyer Form Field deleted') , 'default', null, 'success');
				if (!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
						'action' => 'index',
						$item_id
					));
				}
			} else {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
			}
		}
		if ($this->RequestHandler->prefers('json')) {
				Cms::dispatchEvent('Controller.Record.Delete', $this, array());
		}
    }
}
?>