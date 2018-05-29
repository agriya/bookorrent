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
class ItemFeedbacksController extends AppController
{
    public $name = 'ItemFeedbacks';
    public $components = array(
        'Email',
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
            'ItemFeedback',
        );
        parent::beforeFilter();
    }
    public function index($item_id = null)
    {
        $conditions = array();
        $this->pageTitle = __l('Feedbacks');
        $this->ItemFeedback->recursive = 1;
		if(empty($this->request->params['named'])) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
        if (!empty($this->request->params['named']['item_id']) && empty($this->request->params['named']['user_id'])) {
            $conditions['ItemFeedback.item_id'] = $this->request->params['named']['item_id'];
        }
        if (!empty($this->request->params['named']['item_id']) && !empty($this->request->params['named']['user_id'])) {
            $item_ids = $this->ItemFeedback->Item->find('all', array(
                'conditions' => array(
                    'Item.user_id' => $this->request->params['named']['user_id'],
                    'Item.id !=' => $this->request->params['named']['item_id'],
                ) ,
                'fields' => array(
                    'Item.id',
                ) ,
                'recursive' => -1
            ));
            $itemids = array();
            foreach($item_ids as $item_id) {
                $itemids[$item_id['Item']['id']] = $item_id['Item']['id'];
            }
            $conditions['ItemFeedback.item_id'] = $itemids;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'ItemUser' => array(
                    'User' => array(
						'UserAvatar'						
					)
                ) ,
                'Attachment',
                'Item' => array(
                    'Attachment',
                )
            ) ,
            'order' => array(
                'ItemFeedback.id' => 'desc'
            ) ,
            'recursive' => 3,
        );
        $this->set('itemFeedbacks', $this->paginate());
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.ItemFeedback.Index', $this, array());
        }else{
			if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'photos') {
				$this->autoRender = false;
				$this->render('photos');
			}
			if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'videos') {
				$this->autoRender = false;
				$this->render('videos');
			}
		}
    }
    public function add()
    {
        $this->pageTitle = __l('Review');
        if (!empty($this->request->data)) {
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['ItemFeedback'] = $this->request->data;
			}
           // $this->ItemFeedback->create();
            $uploaded_photo_count = 0;
			$attachmentValidationError = array();
			$is_upload_valid = true;
            for ($i = 0; $i < count($this->request->data['Attachment'][0]); $i++) {
                if (!empty($this->request->data['Attachment'][0][$i]['tmp_name'])) {
                    $uploaded_photo_count = 1;
					$data = array();
					$image_info = getimagesize($this->request->data['Attachment'][0][$i]['tmp_name']);
					$data['Attachment']['filename'] = $this->request->data['Attachment'][0][$i];
					$data['Attachment']['filename']['type'] = $image_info['mime'];
					 $data['Attachment']['description'] = $this->request->data['Attachment'][$i]['description'];
					$this->request->data['Attachment'][$i]['filename']['type'] = $image_info['mime'];
					$this->ItemFeedback->Attachment->Behaviors->attach('ImageUpload', Configure::read('item.file'));
					$this->ItemFeedback->Attachment->set($data);
					if (!$this->ItemFeedback->Attachment->validates()) {
						$is_upload_valid = false;
						$attachmentValidationError[$i] = $this->ItemFeedback->Attachment->validationErrors;
					}
                }
            }
			if (!empty($attachmentValidationError)) {
				foreach($attachmentValidationError as $key => $error) {
					$this->ItemFeedback->Attachment->validationErrors[0][$key] = $error['filename'][0];
				}
			}			
            $this->request->data['ItemFeedback']['ip_id'] = $this->ItemFeedback->toSaveIp();
            if ($this->ItemFeedback->validates($this->request->data) && $is_upload_valid) {
                $this->ItemFeedback->save($this->request->data);
                if ($uploaded_photo_count) // attachment is there then
                {
                    $item_feedback_id = $this->ItemFeedback->getLastInsertId();
                    $this->ItemFeedback->Attachment->create();
					// Normal Upload
					$upload_photo_count = 0;
					for ($i = 0; $i < count($this->request->data['Attachment'][0]); $i++) {
						if (!empty($this->request->data['Attachment'][0][$i]['tmp_name'])) {
							$data = array();
							$upload_photo_count++;
							$image_info = getimagesize($this->request->data['Attachment'][0][$i]['tmp_name']);
							$data['Attachment']['filename'] = $this->request->data['Attachment'][0][$i];
							$data['Attachment']['filename']['type'] = $image_info['mime'];
							 $data['Attachment']['description'] = $this->request->data['Attachment'][$i]['description'];
							$this->request->data['Attachment'][$i]['filename']['type'] = $image_info['mime'];
							$this->ItemFeedback->Attachment->Behaviors->attach('ImageUpload', Configure::read('item.file'));
							$this->ItemFeedback->Attachment->set($data);
							if ($this->ItemFeedback->Attachment->validates()) {
								$data['Attachment']['foreign_id'] = $item_feedback_id;
								$data['Attachment']['class'] = 'ItemFeedback';
								$this->ItemFeedback->Attachment->create();
								$this->ItemFeedback->Attachment->Behaviors->attach('ImageUpload', Configure::read('item.file'));
								$this->ItemFeedback->Attachment->set($data);
								$this->ItemFeedback->Attachment->save($data);
							}
						}
					}

                } 
                //send
                if (Configure::read('messages.is_send_internal_message')) {
                    $message_id = $this->ItemFeedback->ItemUser->User->Message->sendNotifications($this->request->data['ItemFeedback']['item_user_user_id'], $this->Auth->user('username') . ' has left a feedback on your item', $this->request->data['ItemFeedback']['feedback'], $this->request->data['ItemFeedback']['item_user_id'], $is_review = 0, $this->request->data['ItemFeedback']['item_id'], ConstItemUserStatus::Completed);
                    if (Configure::read('messages.is_send_email_on_new_message')) {
                        $content['subject'] = $this->Auth->user('username') . ' '.__l('has left a feedback on your item');
                        $content['message'] = $this->Auth->user('username') . ' '.__l('has left a feedback on your item');
                        if (!empty($this->request->data['ItemFeedback']['item_order_user_email'])) {
                            if ($this->ItemFeedback->_checkUserNotifications($this->request->data['ItemFeedback']['item_user_id'], ConstItemUserStatus::Completed, 0)) { // (to_user_id, order_status,is_sender);
                                $this->ItemFeedback->_sendAlertOnNewMessage($this->request->data['ItemFeedback']['item_order_user_email'], $content, $message_id, 'Booking Alert Mail');
                            }
                        }
                    }                    
                }
				$data = array();
				$data['ItemUser']['id'] = $this->request->data['ItemFeedback']['item_order_id'];
				$data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::Completed;
				$this->ItemFeedback->ItemUser->save($data);
				// <-- For iPhone App code
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Feedback added.'), "error" => 0));
				}else{
					$this->redirect(array(
						'controller' => 'item_users',
						'action' => 'index',
						'type' => 'mytours',
						'status' => 'waiting_for_review',
						'view' => 'list'
					));
				}
            } else {
				$this->set('iphone_response', array("message" => __l('Feedback could not be added. Please, try again.'), "error" => 1));
                $this->Session->setFlash(__l('Feedback could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        if (!empty($this->request->params['named']) || !empty($this->request->data['ItemFeedback']['item_order_id'])) {
			// todo iphone api: condition add
			if(!$this->RequestHandler->prefers('json')){
				$itemInfo = $this->ItemFeedback->Item->ItemUser->find('first', array(
					'conditions' => array(
						'ItemUser.id' => !empty($this->request->data['ItemFeedback']['item_order_id']) ? $this->request->data['ItemFeedback']['item_order_id'] : $this->request->params['named']['item_order_id'],
						'ItemUser.item_user_status_id' => ConstItemUserStatus::WaitingforReview,
						'ItemUser.user_id' => $this->Auth->user('id')
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
				if (empty($itemInfo) || ($itemInfo['ItemUser']['user_id'] != $this->Auth->user('id'))) {
					if (!$this->RequestHandler->prefers('json')) {
						throw new NotFoundException(__l('Invalid request'));
					}
				}
				$message['item_id'] = $itemInfo['ItemUser']['item_id'];
				$message['item_order_id'] = $itemInfo['ItemUser']['id'];
				$message['item_user_user_id'] = $itemInfo['ItemUser']['owner_user_id'];
				$message['item_user_status_id'] = $itemInfo['ItemUser']['item_user_status_id'];
				$message['item_seller_username'] = $itemInfo['Item']['User']['username'];
				$message['item_user_id'] = $itemInfo['ItemUser']['id'];
				$message['item_seller_email'] = $itemInfo['Item']['User']['email'];
				$message['item_username'] = $itemInfo['Item']['User']['username'];
				$this->set('message', $message);
				$this->set('itemInfo', $itemInfo);
			}
        }
        if (empty($this->request->data['ItemFeedback'])) {
            $this->request->data['ItemFeedback']['is_satisfied'] = '1';
        }
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.ItemFeedback.Add', $this, array());
		}
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Feedback To Host');
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
            $item = $this->ItemFeedback->Item->find('first', array(
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
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['ItemFeedback']['q'] = $this->request->params['named']['q'];
			$conditions['AND']['OR'][]['Item.title LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['ItemFeedback.feedback LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['ItemFeedback.admin_comments LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->set('page_title', $this->pageTitle);
        $this->ItemFeedback->recursive = 2;
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
                'ItemFeedback.id' => 'desc'
            )
        );
        $moreActions = $this->ItemFeedback->moreActions;
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
            if ($this->ItemFeedback->save($this->request->data)) {
                $this->Session->setFlash(__l('Feedback has been updated.') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Feedback could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->ItemFeedback->read(null, $id);
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
        if ($this->ItemFeedback->delete($id)) {
            $this->Session->setFlash(__l('Feedback deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>
