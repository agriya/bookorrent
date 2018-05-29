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
class CouponsController extends AppController
{
    public $name = 'Coupons';
    public function beforeFilter()
    {
		$this->Security->disabledFields = array(
			'Coupon.item_id',
        );
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
		$item = $this->Coupon->Item->find('first', array(
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
		$this->pageTitle = __l('Coupons') . ' - ' . $item['Item']['title'];
		$conditions = array();
		$conditions['Coupon.item_id'] = $item_id;
		$this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'Item',
			),
            'order' => array(
                'Coupon.id' => 'desc'
            ) ,
            'recursive' => 0
        );
		$this->set('coupons', $this->paginate());
		$this->set('item', $item);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.Coupon.Index', $this, array());
		}		
	}
	public function add($item_id)
	{
		if (is_null($item_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$item = $this->Coupon->Item->find('first', array(
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
				$this->request->data['Coupon'] = $this->request->data;
			}			
            $this->Coupon->create();
            if ($this->Coupon->save($this->request->data)) {
                $this->Session->setFlash(__l('Coupon has been added') , 'default', null, 'success');
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response',  array("message" =>__l('Coupon has been added'), "error" => 0));
				}else{
					$this->redirect(array(
						'action' => 'index',
						$item['Item']['id']
					));
				}
            } else {
                $this->Session->setFlash(__l('Coupon could not be added. Please, try again.') , 'default', null, 'error');
				$this->set('iphone_response',  array("message" =>__l('Coupon could not be added. Please, try again.'), "error" => 1));
            }
        }
		$this->set('item', $item);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json')) {
			$response = Cms::dispatchEvent('Controller.Coupon.Add', $this, array());
		}		
	}
	public function edit($id = null)
    {
        $this->pageTitle = __l('Edit Coupon');
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
				$this->request->data['Coupon'] = $this->request->data;
			}
            if ($this->Coupon->save($this->request->data)) {
                $this->Session->setFlash(__l('Coupon has been updated') , 'default', null, 'success');
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Coupon has been updated'), "error" => 0));
				}else{
					$this->redirect(array(
						'action' => 'index',
						$this->request->data['Coupon']['item_id']
					));
				}
            } else {
                $this->Session->setFlash(__l('Coupon could not be updated. Please, try again.') , 'default', null, 'error');
				$this->set('iphone_response', array("message" => __l('Coupon could not be updated. Please, try again.'), "error" => 1));
            }
        } else {
            $this->request->data = $this->Coupon->find('first', array(
				'conditions' => array(
					'Coupon.id' => $id
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
			$this->set('coupon', $this->request->data);
        }
		if(!empty($this->request->data['Coupon']['name']))
			$this->pageTitle.= ' - ' . $this->request->data['Coupon']['name'];
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json')) {
			$response = Cms::dispatchEvent('Controller.Coupon.Edit', $this, array());
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
			$coupon = $this->Coupon->find('first', array(
				'conditions' => array(
					'Coupon.id' => $id,
				),
				'recursive' => -1
			));
			$item_id = $coupon['Coupon']['item_id'];
			if ($this->Coupon->delete($id)) {
				$this->set('iphone_response', array("message" => __l('Coupon deleted'), "error" => 0));
				$this->Session->setFlash(__l('Coupon deleted') , 'default', null, 'success');
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
    public function admin_index()
    {
        $this->pageTitle = __l('Listing Coupons');
        $conditions = array();
        $this->set('active', $this->Coupon->find('count', array(
            'conditions' => array(
                'Coupon.is_active' => 1
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Coupon->find('count', array(
            'conditions' => array(
                'Coupon.is_active' => 0
            ) ,
            'recursive' => -1
        )));
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Coupon.is_active'] = 1;
                $this->pageTitle.= ' '.__l('Active');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Coupon.is_active'] = 0;
                $this->pageTitle.= ' '.__l('Inactive');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'Item',
			),
            'order' => array(
                'Coupon.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('coupons', $this->paginate());
        $filters = $this->Coupon->isFilterOptions;
        $moreActions = $this->Coupon->moreActions;
        $this->set(compact('moreActions', 'filters'));
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add') . ' ' . __l('Coupon');
        if (!empty($this->request->data)) {
            $this->Coupon->create();
            if ($this->Coupon->save($this->request->data)) {
                $this->Session->setFlash(__l('Coupon has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Coupon could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
		$items = $this->Coupon->Item->find('list', array(
			'conditions' => array(
				'Item.is_approved' => 1,
				'Item.is_active' => 1,
			),
			'fields' => array(
				'Item.id',
				'Item.title',
			),
			'recursive' => -1
		));
		$this->set(compact('items'));
        // Quick Fix // initialize model to use in form helper
        $moreActions = $this->Coupon->moreActions;
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Coupon');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$coupon= $this->Coupon->find('first', array(
            'conditions' => array(
                'Coupon.id' => $id,
            ) ,
			'contain' => array(
				'Item',
			),
            'recursive' => 0
        ));
        if (!empty($this->request->data)) {
			 if (empty($this->request->data['Coupon']['item_id'])) {
                $this->request->data['Coupon']['item_id'] = $coupon['Coupon']['item_id'];
			 }
            if ($this->Coupon->save($this->request->data)) {
                $this->Session->setFlash(__l('Coupon has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Coupon could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Coupon->read(null, $id);
			$this->request->data['Item']['title']=$coupon['Item']['title'];
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['Coupon']['name'];
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Coupon->delete($id)) {
            $this->Session->setFlash(__l('Coupon deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
	public function apply_coupon()
	{
		$order_id = $this->request->params['named']['order_id'];
		$coupon_code = $this->request->data['ItemUser']['coupon_code'];
		$item_id = $this->request->params['pass'][0];
		if (is_null($order_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		if(!empty($coupon_code)){
			// get coupon details
			$coupon_details = $this->Coupon->find('first', array(
				'conditions' => array(
					'Coupon.item_id' => $item_id,
					'Coupon.name' => $coupon_code,
					'Coupon.is_active' => 1
				),
				'contain' => array(
					'Item' => array(
						'ItemUser' => array(
							'conditions' => array(
								'ItemUser.id' => $order_id
							)
						)
					)
				),
				'recursive' => 2
			));	
			if(!empty($coupon_details)){
				$total_amount = $coupon_details['Item']['ItemUser'][0]['booker_service_amount'] + $coupon_details['Item']['ItemUser'][0]['additional_fee_amount'] + $coupon_details['Item']['ItemUser'][0]['original_price'];
				$_data = array();
				$_data['ItemUser']['id'] = $order_id;
				$_data['ItemUser']['coupon_id'] = $coupon_details['Coupon']['id'];
				$coupon_discount_amont = $total_amount*($coupon_details['Coupon']['discount']/100);
				$_data['ItemUser']['coupon_discount_amont'] = $coupon_discount_amont;
				$this->Coupon->Item->ItemUser->save($_data);
				$this->Session->setFlash(__l('Coupon code applied successfully') , 'default', null, 'success');
			} else {
				$this->Session->setFlash(__l('Invalid Coupon code') , 'default', null, 'error');
			}
		} else if(empty($coupon_code)){
			$this->Session->setFlash(__l('Invalid Coupon code') , 'default', null, 'error');
			
		}
		$this->redirect(array(
			'controller' => 'items',
			'action' => 'order',
			$item_id,
			'order_id' => $order_id
		));
	}
}
?>