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
class ItemFlagsController extends AppController
{
    public $name = 'ItemFlags';
    public $permanentCacheAction = array(
		'user' => array(
			'add',
		) ,
    );
	public function beforeFilter()
	{
		$this->Security->disabledFields = array(
            'ItemFlag.user_id',
			'ItemFlag.item_id'
			);
		parent::beforeFilter();
	}
    public function add($item_id = null)
    {
        if (!empty($this->request->data)) {
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['Item']['id'] = $this->request->data['id'];
				unset($this->request->data['id']);
				$this->request->data['ItemFlag'] = $this->request->data;
			}			
            $this->ItemFlag->create();
            if ($this->Auth->user('role_id') != ConstUserTypes::Admin) {
                $this->request->data['ItemFlag']['user_id'] = $this->Auth->user('id');
            }
            $this->request->data['ItemFlag']['item_id'] = $this->request->data['Item']['id'];
            $this->request->data['ItemFlag']['ip_id'] = $this->ItemFlag->toSaveIp();
            if ($this->ItemFlag->save($this->request->data)) {
				$_Data['Item']['id'] = $this->request->data['Item']['id'];
				$_Data['Item']['is_user_flagged'] = 1;
				$this->ItemFlag->Item->save($_Data);
				Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
                    '_trackEvent' => array(
                        'category' => 'User',
                        'action' => 'Flagged',
                        'label' => $this->Auth->user('username') ,
                        'value' => '',
                    ) ,
                    '_setCustomVar' => array(
                        'ud' => $this->Auth->user('id') ,
                        'rud' => $this->Auth->user('referred_by_user_id') ,
                    )
                ));
                Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
                    '_trackEvent' => array(
                        'category' => 'ItemFlag',
                        'action' => 'Flagged',
                        'label' => $_Data['Item']['id'],
                        'value' => '',
                    ) ,
                    '_setCustomVar' => array(
                        'pd' => $_Data['Item']['id'],
                        'ud' => $this->Auth->user('id') ,
                        'rud' => $this->Auth->user('referred_by_user_id') ,
                    )
                ));
                $this->Session->setFlash(__l('Flag has been added') , 'default', null, 'success');
                $item = $this->ItemFlag->Item->find('first', array(
                    'conditions' => array(
                        'Item.id' => $this->request->data['Item']['id'],
                    ) ,
                    'fields' => array(
                        'Item.slug',
                    ) ,
                    'recursive' => -1
                ));
				if ($this->RequestHandler->prefers('json')) {
					$message = array("message" => __l('Flag has been added'), "error" => 0);
				}else{
					if ($this->RequestHandler->isAjax()) {
						echo "redirect*" . Router::url(array(
							'controller' => 'items',
							'action' => 'view',
							$item['Item']['slug'],
							'admin' => false
						) , true);
						exit;
					} else {
						$this->redirect(array(
							'controller' => 'items',
							'action' => 'view',
							$item['Item']['slug'],
							'admin' => false
						));
					}
				}
            } else {
                $this->request->data = $this->ItemFlag->Item->find('first', array(
                    'conditions' => array(
                        'Item.id' => $this->request->data['Item']['id'],
                    ) ,
                    'recursive' => -1
                ));
				if ($this->RequestHandler->prefers('json')) {
					$message = array("message" => __l('Flag could not be added. Please, try again.'), "error" => 1);
				}else{
					$this->Session->setFlash(__l('Flag could not be added. Please, try again.') , 'default', null, 'error');
				}
            }
        } else {
            $this->request->data = $this->ItemFlag->Item->find('first', array(
                'conditions' => array(
                    'Item.id' => $item_id,
                ) ,
                'recursive' => -1
            ));
            if (empty($this->request->data)) {
				if ($this->RequestHandler->prefers('json')) {
					$message = array("message" => __l('Invalid request'), "error" => 1);
				}else{
					 throw new NotFoundException(__l('Invalid request'));
				}               
            }
        }
        $itemFlagCategories = $this->ItemFlag->ItemFlagCategory->find('list', array(
            'conditions' => array(
                'ItemFlagCategory.is_active' => 1
            ),
			'recursive' => -1,
        ));
        if ($this->Auth->user('role_id') == ConstUserTypes::Admin) {
            $users = $this->ItemFlag->User->find('list');
            $this->set(compact('users'));
        }
        $this->set(compact('itemFlagCategories'));
		// <--- Iphone json code
		if ($this->RequestHandler->prefers('json')) {
			$response = Cms::dispatchEvent('Controller.ItemFlag.Add', $this, array(
				'message' => $message
			));
		}		
		
    }

    public function admin_index()
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Flags');
        $conditions = array();
        if (!empty($this->request->params['named']['item_flag_category_id '])) {
            $itemFlagCategory = $this->{$this->modelClass}->ItemFlagCategory->find('first', array(
                'conditions' => array(
                    'ItemFlagCategory.id' => $this->request->params['named']['item_flag_category_id ']
                ) ,
                'fields' => array(
                    'ItemFlagCategory.id',
                    'ItemFlagCategory.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($itemFlagCategory)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['ItemFlagCategory.id'] = $itemFlagCategory['ItemFlagCategory']['id'];
            $this->pageTitle.= sprintf(' - '.__l('Category - %s') , $itemFlagCategory['ItemFlagCategory']['name']);
        }
        if (!empty($this->request->params['named']['item']) || !empty($this->request->params['named']['item_id'])) {
            $itemConditions = !empty($this->request->params['named']['item']) ? array(
                'Item.slug' => $this->request->params['named']['item']
            ) : array(
                'Item.id' => $this->request->params['named']['item_id']
            );
            $item = $this->{$this->modelClass}->Item->find('first', array(
                'conditions' => $itemConditions,
                'fields' => array(
                    'Item.id',
                    'Item.title'
                ) ,
                'recursive' => -1
            ));
            if (empty($item)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Item.id'] = $this->request->data[$this->modelClass]['item_id'] = $item['Item']['id'];
            $this->pageTitle.= ' - ' . $item['Item']['title'];
        }
        if (!empty($this->request->params['named']['username']) || !empty($this->request->params['named']['user_id'])) {
            $userConditions = !empty($this->request->params['named']['username']) ? array(
                'User.username' => $this->request->params['named']['username']
            ) : array(
                'User.id' => $this->request->params['named']['user_id']
            );
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => $userConditions,
                'fields' => array(
                    'User.id',
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['User.id'] = $this->request->data[$this->modelClass]['user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['Item']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['ItemFlag.created ='] = date('Y-m-d', strtotime('now'));
            $this->pageTitle.= ' - '.__l('Added today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['ItemFlag.created >='] = date('Y-m-d', strtotime('now -7 days'));
            $this->pageTitle.= ' - '.__l('Added in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['ItemFlag.created >='] = date('Y-m-d', strtotime('now -30 days'));
            $this->pageTitle.= ' - '.__l('Added in this month');
        }
		if (isset($this->request->params['named']['q'])) {
            $this->request->data['ItemFlag']['q'] = $this->request->params['named']['q'];
			$conditions['AND']['OR'][]['ItemFlag.message LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['ItemFlagCategory.name LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['Item.title LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['User.username LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->data['Item']['filter_id'])) {
            if ($this->request->data['Item']['filter_id'] == ConstMoreAction::UserFlagged) {
                $conditions['Item.item_flag_count'] != 0;
                $conditions['Item.admin_suspend'] = 0;
                $this->pageTitle.= ' - '.__l('User Flagged');
            }
            $this->request->params['named']['filter_id'] = $this->request->data['Item']['filter_id'];
        }
        $this->ItemFlag->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'ItemFlagCategory' => array(
                    'fields' => array(
                        'ItemFlagCategory.name'
                    )
                ) ,
                'Item' => array(
                    'fields' => array(
                        'Item.title',
                        'Item.slug',
                        'Item.id',
                    ) ,
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height',
                        )
                    )
                ) ,
                 'Ip' => array(
                    'City' => array(
                        'fields' => array(
                            'City.name',
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name',
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                            'Country.iso_alpha2',
                        )
                    ) ,
                    'Timezone' => array(
                        'fields' => array(
                            'Timezone.name',
                        )
                    ) ,
                    'fields' => array(
                        'Ip.ip',
                        'Ip.latitude',
                        'Ip.longitude',
                        'Ip.host',
                    )
                ) ,
            ) ,
            'order' => array(
                'ItemFlag.id' => 'desc'
            )
        );
        $this->set('itemFlags', $this->paginate());
        $moreActions = $this->ItemFlag->moreActions;
        $this->set(compact('moreActions'));
        $this->set('page_title', $this->pageTitle);
    }
    function admin_edit($id = null)
    {
        $this->pageTitle = sprintf(__l('Edit %s Flag'), Configure::read('item.alt_name_for_item_singular_caps'));
        if (is_null($id)) {
            throw new NotFoundException();
        }
        if (!empty($this->request->data)) {
            if ($this->ItemFlag->save($this->request->data)) {
                $this->Session->setFlash(sprintf(__l('%s Flag has been updated'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
            } else {
                $this->Session->setFlash(sprintf(__l('%s Flag could not be updated. Please, try again.'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->ItemFlag->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException();
            }
        }
        $users = $this->ItemFlag->User->find('list');
        $items = $this->ItemFlag->Item->find('list');
        $itemFlagCategories = $this->ItemFlag->ItemFlagCategory->find('list', array(
			'conditions' => array(
				'ItemFlagCategory.is_active' => 1
			),
			'recursive' => -1,
		));
        $this->set(compact('users', 'items', 'itemFlagCategories'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ItemFlag->delete($id)) {
            $this->Session->setFlash(__l('Flag has been cleared') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>