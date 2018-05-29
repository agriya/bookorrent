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
class ItemUserFeedbacksController extends AppController
{
    public $name = 'ItemUserFeedbacks';
    public $components = array(
        'Email',
    );
    public $helpers = array(
        'Embed'
    );
	public $permanentCacheAction = array(
		'user' => array(
			'add',
		) ,
		'public' => array(
			'index',
		) ,
    );
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'Attachment',
            'Attachment.file',
            'ItemUserFeedback',
        );
        parent::beforeFilter();
    }
    public function add()
    {
        $this->pageTitle = __l('Review');
        if (!empty($this->request->data)) {
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['ItemUserFeedback'] = $this->request->data;
			}		
            $this->ItemUserFeedback->create();
            $this->request->data['ItemUserFeedback']['ip_id'] = $this->ItemUserFeedback->toSaveIp();
            if ($this->ItemUserFeedback->validates($this->request->data)) {
                $this->ItemUserFeedback->save($this->request->data);
                //send
                if (Configure::read('messages.is_send_internal_message')) {
                    /* quick fix for json load for long time while adding review - 505 time out error */
                    if(!$this->RequestHandler->prefers('json')){
                    $message_id = $this->ItemUserFeedback->ItemUser->User->Message->sendNotifications($this->request->data['ItemUserFeedback']['item_user_user_id'], $this->Auth->user('username') . ' has left a feedback about you', $this->request->data['ItemUserFeedback']['feedback'], $this->request->data['ItemUserFeedback']['item_user_id'], $is_review = 0, $this->request->data['ItemUserFeedback']['item_id'], ConstItemUserStatus::HostReviewed);
                    if (Configure::read('messages.is_send_email_on_new_message')) {
                        $content['subject'] = $this->Auth->user('username') . ' has left a feedback about you';
                        $content['message'] = $this->Auth->user('username') . ' has left a feedback about you';
                        if (!empty($this->request->data['ItemUserFeedback']['item_order_user_email'])) {
                            if ($this->ItemUserFeedback->_checkUserNotifications($this->request->data['ItemUserFeedback']['item_user_id'], ConstItemUserStatus::Completed, 0)) { // (to_user_id, order_status,is_sender);
                                $this->ItemUserFeedback->_sendAlertOnNewMessage($this->request->data['ItemUserFeedback']['item_order_user_email'], $content, $message_id, 'Booking Alert Mail');
                            }
                        }
                    }
                  }
                }
                $data = array();
                $data['ItemUser']['id'] = $this->request->data['ItemUserFeedback']['item_order_id'];
                $data['ItemUser']['is_host_reviewed'] = 1;
                $this->ItemUserFeedback->ItemUser->save($data, false);
				$this->set('iphone_response', array("message" => __l('Feedback added.'), "error" => 0));
				if(!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
						'controller' => 'item_users',
						'action' => 'index',
						'type' => 'myworks',
						'status' => 'waiting_for_review',
					));
				}
            } else {
				$this->set('iphone_response', array("message" => __l('Feedback could not be added. Please, try again.'), "error" => 1));
                $this->Session->setFlash(__l('Feedback could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        if (!empty($this->request->params['named']) || !empty($this->request->data['ItemUserFeedback']['item_order_id'])) {
			// todo iphone api: condition add
			if(!$this->RequestHandler->prefers('json') && (!$this->request->is('post'))){
				$itemInfo = $this->ItemUserFeedback->ItemUser->find('first', array(
					'conditions' => array(
						'ItemUser.id =' => !empty($this->request->data['ItemUserFeedback']['item_order_id']) ? $this->request->data['ItemUserFeedback']['item_order_id'] : $this->request->params['named']['item_order_id'],
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::WaitingforReview,
							ConstItemUserStatus::Completed
						) ,
						'ItemUser.is_host_reviewed' => 0,
						'ItemUser.owner_user_id' => $this->Auth->user('id')
					) ,
					'contain' => array(
						'Item' => array(
							'User',
							'Attachment',
							'City' => array(
								'fields' => array(
									'City.id',
									'City.name',
									'City.slug',
								)
							) ,
							'Country' => array(
								'fields' => array(
									'Country.name',
									'Country.iso_alpha2'
								)
							) ,
						)
					) ,
					'recursive' => 3,
				));
				if (empty($itemInfo) || ($itemInfo['ItemUser']['owner_user_id'] != $this->Auth->user('id'))) {
					if (!$this->RequestHandler->prefers('json')) {
						throw new NotFoundException(__l('Invalid request'));
					}
				}
				$booker = $this->ItemUserFeedback->ItemUser->User->find('first', array(
					'conditions' => array(
						'User.id' => $itemInfo['ItemUser']['user_id']
					) ,
					'recursive' => -1
				));
				$this->set('booker', $booker);
				$message['item_id'] = $itemInfo['ItemUser']['item_id'];
				$message['item_order_id'] = $itemInfo['ItemUser']['id'];
				$message['item_user_user_id'] = $itemInfo['ItemUser']['user_id'];
				$message['item_user_status_id'] = $itemInfo['ItemUser']['item_user_status_id'];
				$message['item_seller_username'] = $itemInfo['Item']['User']['username'];
				$message['item_user_id'] = $itemInfo['ItemUser']['id'];
				$message['item_booker_email'] = $booker['User']['email'];
				$message['booker_username'] = $booker['User']['username'];
				$message['item_username'] = $itemInfo['Item']['User']['username'];
				$this->set('message', $message);
				$this->set('itemInfo', $itemInfo);
			}
        }
        if (empty($this->request->data['ItemUserFeedback'])) {
            $this->request->data['ItemUserFeedback']['is_satisfied'] = '1';
        }
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			Cms::dispatchEvent('Controller.ItemUserFeedback.Add', $this, array());
		}					
		
    }
    public function index()
    {
        $conditions = array();
        $this->pageTitle = __l('Feedbacks');
        $this->ItemUserFeedback->recursive = 1;
		if(empty($this->request->params['named'])) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
        if (!empty($this->request->params['named']['user_id'])) {
            $conditions['ItemUserFeedback.booker_user_id'] = $this->request->params['named']['user_id'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
					'fields' => array(
						'User.username',
						'User.role_id',
						'User.attachment_id',
						'User.facebook_user_id',
						'User.twitter_avatar_url',
						'User.user_avatar_source_id'
					)
				)
            ) ,
            'fields' => array(
                'ItemUserFeedback.id',
                'ItemUserFeedback.created',
                'ItemUserFeedback.item_user_id',
                'ItemUserFeedback.item_id',
                'ItemUserFeedback.feedback',
                'ItemUserFeedback.video_url',
                'ItemUserFeedback.is_satisfied',
            ) ,
            'order' => array(
                'ItemUserFeedback.id' => 'desc'
            ) ,
            'recursive' => 2,
        );
        $this->set('itemUserFeedbacks', $this->paginate());
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.ItemUserFeedback.Index', $this, array());
        }
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Feedback To') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps');
        $this->_redirectGET2Named(array(
            'q',
        ));
        $conditions = array();
        if (!empty($this->request->params['named']['item']) || !empty($this->request->params['named']['item_id'])) {
            $itemConditions = !empty($this->request->params['named']['item']) ? array(
                'Item.slug' => $this->request->params['named']['item']
            ) : array(
                'Item.id' => $this->request->params['named']['item_id']
            );
            $item = $this->ItemUserFeedback->Item->find('first', array(
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
            $user = $this->ItemUserFeedback->ItemUser->User->find('first', array(
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
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['ItemUserFeedback']['q'] = $this->request->params['named']['q'];
			$conditions['AND']['OR'][]['Item.title LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['ItemUserFeedback.feedback LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['ItemUserFeedback.admin_comments LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->set('page_title', $this->pageTitle);
        $this->ItemUserFeedback->recursive = 2;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
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
                'Item' => array(
                    'User' => array(
                        'fields' => array(
                            'User.id',
                            'User.username',
                        ) ,
                    )
                ) ,
                'ItemUser' => array(
                    'User' => array(
                        'fields' => array(
                            'User.id',
                            'User.username',
                        ) ,
                    )
                ) ,
            ) ,
            'order' => array(
                'ItemUserFeedback.id' => 'desc'
            )
        );
        if (isset($this->request->data['ItemUserFeedback']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['ItemUserFeedback']['q']
            ));
        }
        $moreActions = $this->ItemUserFeedback->moreActions;
        $this->set(compact('moreActions'));
        $this->set('itemFeedbacks', $this->paginate());
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Feedback');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->ItemUserFeedback->save($this->request->data)) {
                $this->Session->setFlash(__l('Feedback has been updated.') , 'default', null, 'success');
				$this->redirect(array(
					'action' => 'index'
				));				
            } else {
                $this->Session->setFlash(__l('Feedback could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->ItemUserFeedback->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ItemUserFeedback->delete($id)) {
            $this->Session->setFlash(sprintf(__l('%s User Feedback deleted'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>
