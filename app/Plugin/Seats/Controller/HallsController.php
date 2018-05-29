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
class HallsController extends AppController
{
    public $name = 'Halls';
    public function beforeFilter()
    {
		// form once submission check
		$this->Security->csrfCheck = true;
        $this->Security->disabledFields = array(
			'Hall.user_id',
        );
		if ((!empty($this->request->params['action']) and ($this->request->params['action'] == 'index'))) {
            $this->Security->validatePost = false;
        }
        parent::beforeFilter();
    }
    public function index()
    {
		$this->pageTitle = __l('Halls');
		$conditions = array();
		$this->set('active', $this->Hall->find('count', array(
            'conditions' => array(
				'Hall.user_id' => $this->Auth->user('id'),
                'Hall.is_active' => 1
            ) ,
            'recursive' => -1
        )));	
		$this->set('inactive', $this->Hall->find('count', array(
            'conditions' => array(
				'Hall.user_id' => $this->Auth->user('id'),
                'Hall.is_active' => 0
            ) ,
            'recursive' => -1
        )));		
		$conditions['Hall.user_id'] = $this->Auth->user('id');
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Hall.is_active'] = 1;				
                $this->pageTitle.= ' - '.__l('Active');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Hall.is_active'] = 0;
                $this->pageTitle.= ' - '.__l('Inactive');
            } 
        } 		
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'User' => array(
					'fields' => array(
						'User.id',
						'User.username',
					)
				)
			),
            'order' => array(
                'Hall.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('halls', $this->paginate());
        $moreActions = $this->Hall->moreActions;
        $this->set(compact('moreActions'));	
		if ($this->RequestHandler->prefers('json')) {
			Cms::dispatchEvent('Controller.Hall.Listing', $this, array());
		}
    }
    public function add()
    {
        $this->pageTitle = __l('Add Hall');
        if (!empty($this->request->data)) {
			if ($this->RequestHandler->prefers('json')) {
				if(!empty($this->request->data)){
					$this->request->data['Hall'] = $this->request->data;
				}
			}
            $this->Hall->create();
			if($this->Auth->user('role_id') != ConstUserTypes::Admin){
				$this->request->data['Hall']['user_id'] = $this->Auth->user('id');
			}
            if ($this->Hall->save($this->request->data)) {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Hall has been added'), "error" => 0));
				} else {
					$this->Session->setFlash(__l('Hall has been added') , 'default', null, 'success');
					$this->redirect(array(
						'action' => 'index'
					));
				}
            } else {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Hall could not be added. Please, try again.'), "error" => 0));
				}
                $this->Session->setFlash(__l('Hall could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
		if ($this->RequestHandler->prefers('json')) {
			Cms::dispatchEvent('Controller.Hall.HallAdd', $this, array());
		}
    }
    public function edit($id = null)
    {
        $this->pageTitle = __l('Edit Hall');
        if (is_null($id)) {
            if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		if ($this->RequestHandler->prefers('json') && $this->request->is('get')) {
			unset($this->request->data['User']);
		}
        if (!empty($this->request->data)) {
			if ($this->RequestHandler->prefers('json')) {
				if(!empty($this->request->data)){
					$this->request->data['Hall'] = $this->request->data;
				}
			}
			if($this->Auth->user('role_id') != ConstUserTypes::Admin){
				$this->request->data['Hall']['user_id'] = $this->Auth->user('id');
			}
            if ($this->Hall->save($this->request->data)) {
                $this->set('iphone_response', array("message" => __l('Hall has been updated'), "error" => 1));
				$this->Session->setFlash(__l('Hall has been updated') , 'default', null, 'success');
                if (!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
						'action' => 'index'
					));
				}
            } else {
				$this->set('iphone_response', array("message" => __l('Hall could not be updated. Please, try again.'), "error" => 1));
                $this->Session->setFlash(__l('Hall could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
			$this->request->data = $this->Hall->find('first', array(
                'conditions' => array(
                    'Hall.id' => $id,
                ) ,
                'contain' => array(
					'User' => array(
						'fields' => array(
							'User.id',
							'User.username',
						)
					)
                ),
                'recursive' => 0
            ));
            $this->request->data['Hall']['username'] = $this->request->data['User']['username'];
            if (empty($this->request->data)) {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
            }
			$hall = $this->request->data;
			$this->set('hall',$hall);
        }
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && $this->request->is('post')) {
			$response = Cms::dispatchEvent('Controller.Hall.Edit', $this, array());
		} else if ($this->RequestHandler->prefers('json') && $this->request->is('get')) {
			Cms::dispatchEvent('Controller.Hall.GetEdit', $this, array(
				'hall' => $hall
			));
		}
    }	
	public function update() {
		$this->autoRender = false;
        if (!empty($this->request->data[$this->modelClass])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $selectedIds = array();
            foreach($this->request->data[$this->modelClass] as $primary_key_id => $is_checked) {
                if ($is_checked['id']) {
                    $selectedIds[] = $primary_key_id;
                }
            }
            if ($actionid && !empty($selectedIds)) {
				$custom_price_per_night_count = $this->{$this->modelClass}->find('count', array(
					'conditions' => array(
						$this->modelClass . '.id' => $selectedIds,
						$this->modelClass . '.custom_price_per_night_count >' => 0
					),
					'recursive' => -1
				));				
                if ($actionid == ConstMoreAction::Inactive) {
					if($custom_price_per_night_count <= 0){
						$this->{$this->modelClass}->updateAll(array(
							$this->modelClass . '.is_active' => 0
						) , array(
							$this->modelClass . '.id' => $selectedIds
						));
						$this->set('iphone_response', array("message" => __l('Checked request has been disabled'), "error" => 1));
						$this->Session->setFlash(__l('Checked request has been disabled') , 'default', null, 'success');
					}else{
						$this->set('iphone_response', array("message" => __l('Hall already assign to items, So unable to disable'), "error" => 1));
						$this->Session->setFlash(__l('Hall already assign to items, So unable to disable') , 'default', null, 'error');
					}
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->{$this->modelClass}->updateAll(array(
                        $this->modelClass . '.is_active' => 1
                    ) , array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->set('iphone_response', array("message" => __l('Checked request has been enabled'), "error" => 1));
					$this->Session->setFlash(__l('Checked request has been enabled') , 'default', null, 'success');
                } elseif ($actionid == ConstMoreAction::Delete) {
					if($custom_price_per_night_count <= 0){
						$this->{$this->modelClass}->deleteAll(array(
							$this->modelClass . '.id' => $selectedIds
						));
						$this->set('iphone_response', array("message" => __l('Checked request has been deleted'), "error" => 1));
						$this->Session->setFlash(__l('Checked request has been deleted') , 'default', null, 'success');
					}else{
						$this->set('iphone_response', array("message" => __l('Hall already assign to items, So unable to delete'), "error" => 1));
						$this->Session->setFlash(__l('Hall already assign to items, So unable to delete') , 'default', null, 'error');
					}
                }
            }
        }
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(Router::url('/', true) . $r);
        } else {
            $this->redirect($r);
        }
	}
	public function delete($id = null)
    {
        if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			} else {
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        if ($this->Hall->delete($id)) {
			$this->set('iphone_response', array("message" => __l('Hall deleted'), "error" => 0));
            $this->Session->setFlash(__l('Hall deleted') , 'default', null, 'success');
            if (!$this->RequestHandler->prefers('json')) {
				$this->redirect(array(
					'action' => 'index'
				));
			}
        } else {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			} else {
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		if ($this->RequestHandler->prefers('json')) {
				Cms::dispatchEvent('Controller.Hall.Delete', $this, array());
		}
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Halls');
        $conditions = array();
        $this->set('active', $this->Hall->find('count', array(
            'conditions' => array(
                'Hall.is_active' => 1
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Hall->find('count', array(
            'conditions' => array(
                'Hall.is_active' => 0
            ) ,
            'recursive' => -1
        )));
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Hall.is_active'] = 1;
                $this->pageTitle.= ' - '.__l('Active');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Hall.is_active'] = 0;
                $this->pageTitle.= ' - '.__l('Inactive');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'User' => array(
					'fields' => array(
						'User.id',
						'User.username',
					)
				)
			),
            'order' => array(
                'Hall.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('halls', $this->paginate());
        $moreActions = $this->Hall->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Hall->delete($id)) {
            $this->Session->setFlash(__l('Hall deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null)
    {
        $this->setAction('edit', $id);
    }
}
?>