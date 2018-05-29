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
class ItemUsersController extends AppController
{
    public $name = 'ItemUsers';
	public $permanentCacheAction = array(
		'user' => array(
			'index',
			'view',
			'add',
			'manage',
			'process_fromto',
			'check_qr',
			'check_availability',
		) ,
    );
    public function beforeFilter()
    {
        if (in_array($this->request->action, array(
			'index',
			'add',
            'update_item'
        ))) {
            $this->Security->validatePost = false;
        }
        $this->Security->disabledFields = array(
            'ItemUser.item_id',
            'ItemUser.item_slug',
            'ItemUser.fromto.day',
            'ItemUser.fromto.month',
            'ItemUser.fromto.year',
            'ItemUser.contact',
            'Payment.contact',
            'Item.item',
            'Item.price_per_day',
            'Item.price_per_hour',
            'Item.price_per_week',
            'Item.price_per_month',
            'Item.type',
            'Item.status',
			'ItemUser.price',
			'ItemUser.request',
			'ItemUser.bookit'			
        );
        parent::beforeFilter();
    }
    public function index()
    {
        // @todo "iCalendar"
        $order = array(
            'ItemUser.from' => 'asc'
        );
        $this->pageTitle = Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Users');
        if (!empty($this->request->data)) {
            $item_ids = array();
			if(!empty($this->request->data['Item'])){
				foreach($this->request->data['Item'] as $items) {
					if (!empty($items['item']) && is_array($items)) {
						$item_ids[] = $items['item'];
					}
				}
			}
            if (!empty($item_ids)) {
                $this->request->params['named']['item_id'] = implode(',', $item_ids);
            }
            if (!empty($this->request->data['ItemUser']['type'])) {
                $this->request->params['named']['type'] = $this->request->data['ItemUser']['type'];
            }
            if (!empty($this->request->data['ItemUser']['status'])) {
                $this->request->params['named']['status'] = $this->request->data['ItemUser']['status'];
            }
        }
        if (!empty($this->request->params['named']['type'])) { // Type
            $conditions = array();
            $filter_count = $this->ItemUser->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1
            ));
            if ($this->request->params['named']['type'] == 'mytours') { // Buyer
                $all_count = $filter_count['User']['booking_expired_count']+$filter_count['User']['booking_rejected_count']+$filter_count['User']['booking_canceled_count']+$filter_count['User']['booking_review_count']+$filter_count['User']['booking_completed_count']+$filter_count['User']['booking_confirmed_count']+$filter_count['User']['booking_waiting_for_acceptance_count']+$filter_count['User']['booking_payment_pending_count'];
                $this->set('all_count', $all_count);
                $booking_request_confirmed_count = $this->ItemUser->find('count', array(
					'conditions' => array(
						'ItemUser.user_id' => $this->Auth->user('id'),
						'ItemUser.is_booking_request' => 1,
						'ItemUser.item_user_status_id' => ConstItemUserStatus::PaymentPending
					) ,
					'recursive' => -1
				));
				$booking_request_rejected_count = $this->ItemUser->find('count', array(
					'conditions' => array(
						'ItemUser.user_id' => $this->Auth->user('id'),
						'ItemUser.is_booking_request' => 1,
						'ItemUser.item_user_status_id' => ConstItemUserStatus::BookingRequestRejected
					) ,
					'recursive' => -1
				));
				$status_count = array(
                    __l('Current / Upcoming') . ': ' . ($filter_count['User']['booking_confirmed_count']) => 'in_progress',
                    __l('Waiting For Review') . ': ' . $filter_count['User']['booking_review_count'] => 'waiting_for_review',
                    __l('Past') . ': ' . $filter_count['User']['booking_completed_count'] => 'completed',
                    __l('Waiting For Acceptance') . ': ' . $filter_count['User']['booking_waiting_for_acceptance_count'] => 'waiting_for_acceptance',
                    __l('Canceled') . ': ' . $filter_count['User']['booking_canceled_count'] => 'canceled',
                    __l('Rejected') . ': ' . $filter_count['User']['booking_rejected_count'] => 'rejected',
                    __l('Expired') . ': ' . $filter_count['User']['booking_expired_count'] => 'expired',
                    __l('Payment Pending') . ': ' . $filter_count['User']['booking_payment_pending_count'] => 'payment_pending',
                    __l('Booking Requested') . ': ' . $filter_count['User']['booker_booking_request_count'] => 'booking_request',
                );
                $this->set('moreActions', $status_count);
                if (!empty($this->request->params['named']['status'])) {
                    $order_status = array(
                        'waiting_for_acceptance' => 1,
                        'confirmed' => 2,
                        'rejected' => 3,
                        'canceled' => 4,
                        'arrived' => 5,
                        'waiting_for_review' => 6,
                        'payment_cleared' => 7,
                        'completed' => 8,
                        'Expired' => 9,
                        'booking_requested' => 10,
                        'booking_request_rejected' => 11,
                        'booking_request_confirmed' => 12,
                        'arrived' => 13,
                        'payment_pending' => 14,
                    );
                    $itemStatusClass = array(
                        'waiting_for_acceptance' => 'waitingforacceptance',
                        'confirmed' => 'confirmed',
                        'completed' => 'completed',
                        'canceled' => 'cancelled',
                        'rejected' => 'rejected',
                        'expired' => 'expired',
                        'waiting_for_review' => 'waitingforyourreview',
                        'in_progress' => 'currentorupcoming',
                        'booking_request' => 'bookingrequested',
                        'booking_request_confirmed' => 'bookingrequestconfirmed',
                        'booking_request_rejected' => 'bookingrequestrejected',
                        'arrived' => 'arrived',
                        'payment_pending' => 'paymentpending',
                    );
                    $this->set('itemStatusClass', $itemStatusClass);
                    if ($this->request->params['named']['status'] == 'completed') {
                        // @todo "Auto review" add condition CompletedAndClosedByAdmin
                        $status = array(
                            ConstItemUserStatus::Completed,
                        );
                    } else if ($this->request->params['named']['status'] == 'rejected') {
                        $status = array(
                            ConstItemUserStatus::Rejected,
                        );
                    } else if ($this->request->params['named']['status'] == 'canceled') {
                        $status = array(
                            ConstItemUserStatus::Canceled,
                            ConstItemUserStatus::CanceledByAdmin,
                        );
                    } else if ($this->request->params['named']['status'] == 'expired') {
                        $status = array(
                            ConstItemUserStatus::Expired,
                        );
                    } else if ($this->request->params['named']['status'] == 'in_progress') {
                        $status = array(
                            ConstItemUserStatus::Confirmed,
                        );
                    } else if ($this->request->params['named']['status'] == 'payment_pending') {
                        $status = array(
                            ConstItemUserStatus::PaymentPending,
                        );
                    } else if ($this->request->params['named']['status'] == 'payment_cleared') {
                        $conditions['ItemUser.is_payment_cleared'] = 1;
                    } else if ($this->request->params['named']['status'] == 'waiting_for_review') {
                        $status = array(
                            ConstItemUserStatus::WaitingforReview,
                        );
                    } else if ($this->request->params['named']['status'] == 'waiting_for_acceptance') {
                        $status = array(
                            ConstItemUserStatus::WaitingforAcceptance,
                        );
                    } else {
                        $status = strtr($this->request->params['named']['status'], $order_status);
                    }
                    if ($this->request->params['named']['status'] == 'booking_request') {
                        $conditions['ItemUser.is_booking_request'] = 1;
                        $conditions['ItemUser.user_id >'] = 0;
                        $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::BookingRequest;
                    } elseif ($this->request->params['named']['status'] == 'booking_request_confirmed') {
                        $conditions['ItemUser.is_booking_request'] = 1;
                        $conditions['ItemUser.user_id >'] = 0;
                        $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::PaymentPending;
					} elseif ($this->request->params['named']['status'] == 'booking_request_rejected') {
                        $conditions['ItemUser.is_booking_request'] = 1;
                        $conditions['ItemUser.user_id >'] = 0;
                        $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::BookingRequestRejected;
					} elseif (!empty($status) && $status != 'all') {
                        $conditions['ItemUser.item_user_status_id'] = $status;
                    }
                } else {
                    $conditions['OR'][]['ItemUser.item_user_status_id'] = ConstItemUserStatus::Confirmed;
                }
                $conditions['Not']['ItemUser.item_user_status_id'] = array(
                    0
                );
                $this->pageTitle = __l('Bookings');
                $conditions['ItemUser.user_id'] = $this->Auth->user('id');
            }
            if ($this->request->params['named']['type'] == 'myworks') { // Seller
                $countcal_conditions['OR']['NOT']['ItemUser.item_user_status_id'] = 0;
                $countcal_conditions['OR']['NOT']['ItemUser.item_user_status_id'] = array(
                            0,
                            ConstItemUserStatus::PaymentPending
                        );
                        $countcal_conditions['ItemUser.user_id >'] = 0;
                        $countcal_conditions['OR']['AND'] = array(
                            'ItemUser.item_user_status_id' => ConstItemUserStatus::PaymentPending,
                            'ItemUser.is_booking_request' => 1
                        );
                 $countcal_conditions['ItemUser.owner_user_id'] = $this->Auth->user('id');
                 $all_count = $this->ItemUser->find('count', array(
                 'conditions' => $countcal_conditions,
                 'recursive' => -1
                   ));
                $this->set('all_count', $all_count);
				$host_booking_request_confirmed_count = $this->ItemUser->find('count', array(
					'conditions' => array(
						'ItemUser.owner_user_id' => $this->Auth->user('id'),
						'ItemUser.is_booking_request' => 1,
						'ItemUser.item_user_status_id' => ConstItemUserStatus::PaymentPending
					) ,
					'recursive' => -1
				));
				$host_booking_request_rejected_count = $this->ItemUser->find('count', array(
					'conditions' => array(
						'ItemUser.owner_user_id' => $this->Auth->user('id'),
						'ItemUser.is_booking_request' => 1,
						'ItemUser.item_user_status_id' => ConstItemUserStatus::BookingRequestRejected,
					) ,
					'recursive' => -1
				));
                $status_count = array(
                    __l('Waiting For Acceptance') . ': ' . $filter_count['User']['host_waiting_for_acceptance_count'] => 'waiting_for_acceptance',
                    __l('Waiting For Review') . ': ' . $filter_count['User']['host_review_count'] => 'waiting_for_review',
                    __l('Confirmed') . ': ' . $filter_count['User']['host_confirmed_count'] => 'confirmed',
                    __l('Past') . ': ' . $filter_count['User']['host_completed_count'] => 'completed',
                    __l('Canceled') . ': ' . $filter_count['User']['host_canceled_count'] => 'canceled',
                    __l('Rejected') . ': ' . $filter_count['User']['host_rejected_count'] => 'rejected',
                    __l('Expired') . ': ' . $filter_count['User']['host_expired_count'] => 'expired',
                    __l('Booking Request') . ': ' . $filter_count['User']['host_booking_request_count'] => 'booking_request',
                    __l('Payment Cleared') . ': ' . $filter_count['User']['host_payment_cleared_count'] => 'payment_cleared',
                );
                $data_status_count = array(
                    'waiting_for_acceptance' => array(
                        'label' => __l('Waiting For Acceptance') ,
                        'count' => $filter_count['User']['host_waiting_for_acceptance_count']
                    ) ,
                    'waiting_for_review' => array(
                        'label' => __l('Waiting For Review') ,
                        'count' => $filter_count['User']['host_review_count']
                    ) ,
                    'confirmed' => array(
                        'label' => __l('Confirmed') ,
                        'count' => $filter_count['User']['host_confirmed_count']
                    ) ,
                    'completed' => array(
                        'label' => __l('Past') ,
                        'count' => $filter_count['User']['host_completed_count']
                    ) ,
                    'canceled' => array(
                        'label' => __l('Canceled') ,
                        'count' => $filter_count['User']['host_canceled_count']
                    ) ,
                    'rejected' => array(
                        'label' => __l('Rejected') ,
                        'count' => $filter_count['User']['host_rejected_count']
                    ) ,
                    'expired' => array(
                        'label' => __l('Expired') ,
                        'count' => $filter_count['User']['host_expired_count']
                    ) ,
                    'booking_request' => array(
                        'label' => __l('Booking Request') ,
                        'count' => $filter_count['User']['host_booking_request_count']
                    ),
                );
                $this->set('data_status_count', $data_status_count);
                $itemStatusClass = array(
                    'waiting_for_acceptance' => 'waitingforacceptance',
                    'confirmed' => 'confirmed',
                    'completed' => 'completed',
                    'canceled' => 'cancelled',
                    'rejected' => 'rejected',
                    'expired' => 'expired',
                    'waiting_for_review' => 'waitingforyourreview',
                    'in_progress' => 'currentorupcoming',
                    'booking_request' => 'bookingrequested',
                    'arrived' => 'arrived',
                    'payment_pending' => 'paymentpending',
                    'payment_cleared' => 'confirmed',
                );
                $this->set('itemStatusClass', $itemStatusClass);
                $this->set('moreActions', $status_count);
                if (!empty($this->request->params['named']['status'])) {
                    $order_status = array(
                        'waiting_for_acceptance' => 1,
                        'confirmed' => 2,
                        'rejected' => 3,
                        'canceled' => 4,
                        'arrived' => 5,
                        'waiting_for_review' => 6,
                        'payment_cleared' => 7,
                        'completed' => 8,
                        'Expired' => 9,
                        'booking_requested' => 10,
                        'payment_cleared' => 13,
                    );
                    if ($this->request->params['named']['status'] == 'completed') {
                        // @todo "Auto review" add condition CompletedAndClosedByAdmin
                        $status = array(
                            ConstItemUserStatus::Completed,
                        );
                    } else if ($this->request->params['named']['status'] == 'rejected') {
                        $status = array(
                            ConstItemUserStatus::Rejected,
                        );
                    } else if ($this->request->params['named']['status'] == 'canceled') {
                        $status = array(
                            ConstItemUserStatus::Canceled,
                            ConstItemUserStatus::CanceledByAdmin,
                        );
                    } else if ($this->request->params['named']['status'] == 'expired') {
                        $status = array(
                            ConstItemUserStatus::Expired,
                        );
                    } else if ($this->request->params['named']['status'] == 'confirmed') {
                        $status = array(
                            ConstItemUserStatus::Confirmed,
                        );
                    } else if ($this->request->params['named']['status'] == 'pipeline') {
                        $status = array(
							ConstItemUserStatus::Confirmed,
							ConstItemUserStatus::WaitingforReview,
                            ConstItemUserStatus::Completed,
                        );
						$conditions['ItemUser.is_payment_cleared'] = 0;
                    } else if ($this->request->params['named']['status'] == 'waiting_for_acceptance') {
                        $status = array(
                            ConstItemUserStatus::WaitingforAcceptance,
                        );
                    } else if ($this->request->params['named']['status'] == 'payment_cleared') {
                        $conditions['ItemUser.is_payment_cleared'] = 1;
                    } else if ($this->request->params['named']['status'] == 'waiting_for_review') {
                        $status = array(
                            ConstItemUserStatus::WaitingforReview,
                            ConstItemUserStatus::Completed,
                        );
                        $conditions['ItemUser.is_host_reviewed'] = 0;
                    } else {
                        $status = strtr($this->request->params['named']['status'], $order_status);
                    }
                    if ($this->request->params['named']['status'] == 'booking_request') {
                        $conditions['ItemUser.is_booking_request'] = 1;
                        $conditions['ItemUser.user_id >'] = 0;
                        $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::BookingRequest;
                    } elseif ($this->request->params['named']['status'] == 'booking_request_confirmed') {
                        $conditions['ItemUser.is_booking_request'] = 1;
                        $conditions['ItemUser.user_id >'] = 0;
                        $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::PaymentPending;
					} elseif ($this->request->params['named']['status'] == 'booking_request_rejected') {
                        $conditions['ItemUser.is_booking_request'] = 1;
                        $conditions['ItemUser.user_id >'] = 0;
                        $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::BookingRequestRejected;
					}  elseif (!empty($status) && $status != 'all') {
                        $conditions['ItemUser.item_user_status_id'] = $status;
                    }
                } else {
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::WaitingforAcceptance;
                }
                if (!empty($this->request->params['named']['slug'])) {
                    $conditions['Item.slug'] = $this->request->params['named']['slug'];
                }
                if (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] != 'booking_request') {
                    $conditions['OR']['NOT']['ItemUser.item_user_status_id'] = 0;
                    if ($this->request->params['named']['status'] == 'all') {
                        $conditions['OR']['NOT']['ItemUser.item_user_status_id'] = array(
                            0,
                            ConstItemUserStatus::PaymentPending
                        );
                        $conditions['ItemUser.user_id >'] = 0;
                        $conditions['OR']['AND'] = array(
                            'ItemUser.item_user_status_id' => ConstItemUserStatus::PaymentPending,
                            'ItemUser.is_booking_request' => 1
                        );
                        
                    }
                }
                $this->pageTitle = __l('Calendar');
                $conditions['ItemUser.owner_user_id'] = $this->Auth->user('id');
            }
        } else {
            $conditions['Item.user_id'] = $this->Auth->user('id');
			// todo -> type missing that Invalid request set
			throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->params['named']['item_id'])) {
			$itemIds = explode(',', $this->request->params['named']['item_id']);
            $item = $this->ItemUser->Item->find('count', array(
                'conditions' => array(
                    'Item.id' => $itemIds,
                    'Item.user_id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1
            ));
            if (!$item) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Item.id'] = explode(',', $this->request->params['named']['item_id']);
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email',
                        'User.blocked_amount',
                        'User.cleared_amount',
                    )
                ) ,
                'ItemUserStatus' => array(
                    'fields' => array(
                        'ItemUserStatus.id',
                        'ItemUserStatus.name',
                        'ItemUserStatus.item_user_count',
                        'ItemUserStatus.slug',
                    )
                ) ,
                'Message',
                'Item' => array(
                    'fields' => array(
                        'Item.id',
                        'Item.created',
                        'Item.title',
                        'Item.slug',
                        'Item.user_id',
                        'Item.description',
                        'Item.user_id',
                        'Item.latitude',
                        'Item.longitude',
                        'Item.address',
                        'Item.item_view_count',
                        'Item.item_feedback_count',
                        'Item.positive_feedback_count',
                        'Item.is_people_can_book_my_time',
                        'Item.price_per_hour',
                        'Item.price_per_day',
                        'Item.price_per_week',
                        'Item.price_per_month',
                        'Item.is_sell_ticket',
                        'Item.is_have_definite_time',
                        'Item.is_user_can_request',
                        'Item.minimum_price',
                        'Item.additional_fee_name',
                        'Item.additional_fee_percentage'
                    ) ,
                    'User' => array(
                        'UserAvatar',
                        'fields' => array(
                            'User.id',
                            'User.username',
                            'User.email',
                            'User.blocked_amount',
                            'User.cleared_amount',
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                            'Country.iso_alpha2'
                        )
                    ) ,
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height'
                        ) ,
                        'limit' => 1,
                    ) ,
                ) ,
            ) ,
            'order' => $order,
            'recursive' => 3,
            'limit' => 15,
        );
        $itemUsers = $this->paginate();
        $this->set('itemUsers', $itemUsers);
        // <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.ItemUser.item', $this, array(
				'data' => $itemUsers
			));
        }
        $user = $this->ItemUser->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id')
            ) ,
            'recursive' => -1,
        ));
        $this->set('user', $user);
        // For iPhone App code -->
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myworks') {
            $this->pageTitle = __l('Calendar');
            $this->render('index');
        } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'mytours') {
            $this->pageTitle = __l('Bookings');
            if (isset($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'list') {
                   $this->render('my_orders');
            } else {
				$this->render('my_order_lists');
            }
        }
    }
    public function update_item()
    {
        if (!empty($this->request->data)) {
            $data = array();
            foreach($this->request->data['Item'] as $item) {
                if (isset($item['id'])) {
                    $data['Item']['id'] = $item['id'];
                    $data['Item']['price_per_day'] = $item['price_per_day'];
                    $data['Item']['price_per_week'] = $item['price_per_week'];
                    $data['Item']['price_per_month'] = $item['price_per_month'];
                    $this->ItemUser->Item->save($data, false);
                }
            }
			$this->set('iphone_response', array("message" => sprintf(__l('%s info updated successfully'), Configure::read('item.alt_name_for_item_singular_caps')), "error" => 0));
            $this->Session->setFlash(sprintf(__l('%s info updated successfully'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
			if (!$this->RequestHandler->prefers('json')) {
				if (!$this->RequestHandler->isAjax()) {
					$this->redirect(array(
						'controller' => 'item_users',
						'action' => 'index',
						'type' => 'myworks',
						'status' => 'waiting_for_acceptance'
					));
				} else {
					exit;
				}
			}
        }else{
			$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
		}
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.ItemUser.UpdateItem', $this, array());
		}
    }
    public function view($id = null)
    {
        $this->pageTitle = __l('Ticket');
        if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        $conditions['ItemUser.id'] = $id;
        if ($this->Auth->user('role_id') == ConstUserTypes::User) {
            $conditions['ItemUser.user_id'] = $this->Auth->user('id');
        }
		$contain = array(
			'Item' => array(
				'Attachment',
				'User' => array(
					'UserProfile'
				) ,
				'City' => array(
					'fields' => array(
						'City.id',
						'City.name',
					)
				) ,
				'State' => array(
					'fields' => array(
						'State.id',
						'State.name',
					)
				) ,
				'Country' => array(
					'fields' => array(
						'Country.id',
						'Country.name',
						'Country.iso_alpha2',
					)
				) ,
			) ,
			'User' => array(
				'UserProfile' => array(
					'City' => array(
						'fields' => array(
							'City.id',
							'City.name',
						)
					) ,
					'State' => array(
						'fields' => array(
							'State.id',
							'State.name',
						)
					) ,
					'Country' => array(
						'fields' => array(
							'Country.id',
							'Country.name',
						)
					) ,
				) ,
			),
			'CustomPricePerNight',
			'CustomPricePerTypeItemUser' => array(
				'CustomPricePerType',
			)
		);
		if(isPluginEnabled('Seats')){
			$contain['CustomPricePerTypesSeat'] = array('Partition');
			$contain['CustomPricePerNight'] = 'Hall';
		}
        $itemUser = $this->ItemUser->find('first', array(
            'conditions' => $conditions,
            'contain' => $contain,
            'recursive' => 3
        ));
		$seats = array();
		if(isPluginEnabled('Seats')){
			foreach($itemUser['CustomPricePerTypesSeat'] as $seat){
				$seats[] = $seat;
			}
		}
        if (empty($itemUser)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        $this->pageTitle.= ' - ' . $itemUser['Item']['title'];
        $this->set('itemUser', $itemUser);				
		$this->set('seats', $seats);				
		if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'print') {
			if(empty($itemUser['ItemUser']['is_payment_cleared'])){
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
			}
			if (!$this->RequestHandler->prefers('json')) {
				$this->layout = 'print';
				$this->render('view-print');
			}
		}
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			Cms::dispatchEvent('Controller.ItemUser.View', $this, array());
		}		
    }
    public function add()
    {
		$mssage = array();
        $this->pageTitle = sprintf(__l('Add %s User'), Configure::read('item.alt_name_for_item_singular_caps'));		
        if (!empty($this->request->data)) {
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['ItemUser'] = $this->request->data;
                $this->request->data['ItemUser']['message'] = $this->request->data['hostmessage'];
                if(!empty($this->request->data['ItemUser']['from'])){
                $schfrom = explode(" ", $this->request->data['ItemUser']['from']);
                $schfrom_date = explode(".", $schfrom[0]);
                $schfrom_time = explode(":", $schfrom[1]);
                $this->request->data['ItemUser']['from'] = array(
				   'year' => $schfrom_date[0],
				   'month' => $schfrom_date[1],
				   'day' => $schfrom_date[2],
				   'hour' => $schfrom_time[0],
				   'min' => $schfrom_time[1],
				   'meridian' => strtolower($schfrom_time[2])
				   );
                }
                if(!empty($this->request->data['ItemUser']['to'])){
                $schto = explode(" ", $this->request->data['ItemUser']['to']);
                $schto_date = explode(".", $schto[0]);
                $schto_time = explode(":", $schto[1]);
                $this->request->data['ItemUser']['to'] = array(
				   'year' => $schto_date[0],
				   'month' => $schto_date[1],
				   'day' => $schto_date[2],
				   'hour' => $schto_time[0],
				   'min' => $schto_time[1],
				   'meridian' => strtolower($schto_time[2])
				   );
                }
                if(!empty($this->request->data['ItemUser']['request_from'])){
                $reqfrom = explode(" ", $this->request->data['ItemUser']['request_from']);
                $reqfrom_date = explode(".", $reqfrom[0]);
                $reqfrom_time = explode(":", $reqfrom[1]);
                $this->request->data['ItemUser']['request_from'] = array(
				   'year' => $reqfrom_date[0],
				   'month' => $reqfrom_date[1],
				   'day' => $reqfrom_date[2],
				   'hour' => $reqfrom_time[0],
				   'min' => $reqfrom_time[1],
				   'meridian' => strtolower($reqfrom_time[2])
				   );
                }
                if(!empty($this->request->data['ItemUser']['request_to'])){
                $reqto = explode(" ", $this->request->data['ItemUser']['request_to']);
                $reqto_date = explode(".", $reqto[0]);
                $reqto_time = explode(":", $reqto[1]);
                $this->request->data['ItemUser']['request_to'] = array(
					 'year' => $reqto_date[0],
					 'month' => $reqto_date[1],
					 'day' => $reqto_date[2],
					 'hour' => $reqto_time[0],
					 'min' => $reqto_time[1],
					 'meridian' => strtolower($reqto_time[2])
					 );
                }
                //Format Changed for custom Price
                if(!empty($this->request->data['ItemUser']['custom_price_per_type'])){
                    $j = 0;
                    foreach($this->request->data['ItemUser']['custom_price_per_type'] As $price_types) {
                        foreach($price_types As $key=> $price_type){
                         $this->request->data['ItemUser']['custom_price_per_type'][$key] = $price_type;
                         unset($this->request->data['ItemUser']['custom_price_per_type'][$j]);
                         $j++;
                        }
                    }
                }
			}
			$item = $this->ItemUser->Item->find('first', array(
                'conditions' => array(
                    'Item.id' => $this->request->data['ItemUser']['item_id']
                ) ,
                'recursive' => -1
            ));
            $this->ItemUser->create();
            if ($this->Auth->user('id')) {
                $this->request->data['ItemUser']['user_id'] = $this->Auth->user('id');
            }
            // @todo "What goodies I want (Host)"
            $this->request->data['ItemUser']['is_delayed_chained_payment'] = 0;
            $this->request->data['ItemUser']['is_preapproval_payment'] = 0;
            $this->request->data['ItemUser']['owner_user_id'] = $item['Item']['user_id'];
            $this->request->data['ItemUser']['top_code'] = $this->_uuid();
            $this->request->data['ItemUser']['bottom_code'] = $this->_unum();
            if (isset($this->request->data['ItemUser']['request'])) {
				$from = $this->request->data['ItemUser']['request_from']['year'] . '-' . $this->request->data['ItemUser']['request_from']['month'] . '-' . $this->request->data['ItemUser']['request_from']['day'];
				$to = $this->request->data['ItemUser']['request_to']['year'] . '-' . $this->request->data['ItemUser']['request_to']['month'] . '-' . $this->request->data['ItemUser']['request_to']['day'];
				
				$from_time = (($this->request->data['ItemUser']['request_from']['meridian'] == 'am') ? $this->request->data['ItemUser']['request_from']['hour'] : $this->request->data['ItemUser']['request_from']['hour'] + 12) . ':' . $this->request->data['ItemUser']['request_from']['min'] . ':00';
				$to_time = (($this->request->data['ItemUser']['request_to']['meridian'] == 'am') ? $this->request->data['ItemUser']['request_to']['hour'] : $this->request->data['ItemUser']['request_to']['hour'] + 12) . ':' . $this->request->data['ItemUser']['request_to']['min'] . ':00';
				$check_start_date = $from . ' ' . $from_time;
				$check_end_date = $to . ' ' . $to_time;
				if (strtotime($check_start_date) >= strtotime(date('Y-m-d H:s')) && strtotime($check_start_date) <= strtotime($check_end_date)) {
					$this->request->data['ItemUser']['from'] = $this->request->data['ItemUser']['request_from'];
					$this->request->data['ItemUser']['to'] = $this->request->data['ItemUser']['request_to'];
					$this->request->data['ItemUser']['is_booking_request'] = 1;
					$this->request->data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::BookingRequest;
				} else {
					$this->Session->setFlash(__l('Request given date is not valid. Please, try for some other dates.') , 'default', null, 'error');
					$mssage = array("message" => __l('Request given date is not valid. Please, try for some other dates.'), "error" => 1);
					if (!$this->RequestHandler->prefers('json')) {
						$this->redirect(array(
							'controller' => 'items',
							'action' => 'view',
							$item['Item']['slug']
						));
					}
				}
            } else if(isset($this->request->data['ItemUser']['bookit'])) { 
				$total_quantity = 0;
				$total_price = 0;
				if ($item['Item']['is_sell_ticket']) {
					if(!empty($this->request->data['ItemUser']['custom_price_per_type'])) {
						$is_quantity_check = true;
						$is_quantity_select = false;
						$sub_price = 0;
						foreach($this->request->data['ItemUser']['custom_price_per_type'] As $key => $price_type) {
							$_data = array();
							if(!empty($price_type)) {
								$is_quantity_select = true;
								$custom_price_per_type = $this->ItemUser->Item->CustomPricePerType->find('first', array(
									'conditions' => array(
										'CustomPricePerType.id' => $key,
									),
									'recursive' => -1,
								));
								if($custom_price_per_type) {
									if((empty($custom_price_per_type['CustomPricePerType']['min_number_per_order']) || (!empty($custom_price_per_type['CustomPricePerType']['min_number_per_order']) && $custom_price_per_type['CustomPricePerType']['min_number_per_order'] <= $price_type)) && (empty($custom_price_per_type['CustomPricePerType']['max_number_per_order']) || (!empty($custom_price_per_type['CustomPricePerType']['max_number_per_order']) && $custom_price_per_type['CustomPricePerType']['max_number_per_order'] >= $price_type))) {
										$total_quantity = $total_quantity + $price_type;
										$sub_price = $sub_price + $custom_price_per_type['CustomPricePerType']['price'];
										$total_price = $total_price + ($custom_price_per_type['CustomPricePerType']['price'] * $price_type);
									} else {
										$is_quantity_check = false;
									}
								}
							}
						}
						if(!$is_quantity_select || !$is_quantity_check) {
							if(!$is_quantity_select) {
								$mssage = array("message" => __l('Must be select the atleast one ticket or minimum quantity per order.'), "error" => 1);
								$this->Session->setFlash(__l('Must be select the atleast one ticket or minimum quantity per order.') , 'default', null, 'error');
							} else if(!$is_quantity_check) {
								$mssage = array("message" => __l('Booking ticket quantity should be greater than the minimum quantity or less than the maximum quantity per order.'), "error" => 1);
								$this->Session->setFlash(__l('Booking ticket quantity should be greater than the minimum quantity or less than the maximum quantity per order.') , 'default', null, 'error');
							}
							if (!$this->RequestHandler->prefers('json')) {
								$this->redirect(array(
									'controller' => 'items',
									'action' => 'view',
									$item['Item']['slug']
								));
							}
						}
						
						if(isset($this->request->data['ItemUser']['parent_id']) && !empty($this->request->data['ItemUser']['parent_id'])) {
							$custom_night = $this->ItemUser->Item->CustomPricePerNight->find('first', array(
								'conditions' => array(
									'CustomPricePerNight.id' => $this->request->data['ItemUser']['custom_price_per_night_id'],
								),
								'contain' => array(
									'CustomPricePerType',
								),
								'recursive' => 1,
							));
							
							$data = array();
							$data['CustomPricePerNight']['item_id'] = $custom_night['CustomPricePerNight']['item_id'];
							$data['CustomPricePerNight']['parent_id'] = $custom_night['CustomPricePerNight']['id'];
							$data['CustomPricePerNight']['start_date'] = $this->request->data['ItemUser']['start_date'];
							$data['CustomPricePerNight']['start_time'] = $custom_night['CustomPricePerNight']['start_time'];
							$data['CustomPricePerNight']['end_date'] = $this->request->data['ItemUser']['end_date'];
							$data['CustomPricePerNight']['end_time'] = $custom_night['CustomPricePerNight']['end_time'];
							$data['CustomPricePerNight']['is_available'] = 1;
							$data['CustomPricePerNight']['minimum_price'] = $custom_night['CustomPricePerNight']['minimum_price'];
							$data['CustomPricePerNight']['is_tipped'] = $custom_night['CustomPricePerNight']['is_tipped'];
							$data['CustomPricePerNight']['total_available_count'] = $custom_night['CustomPricePerNight']['total_available_count'];
							$data['CustomPricePerNight']['total_booked_count'] = 0;
							$data['CustomPricePerNight']['repeat_days'] = $custom_night['CustomPricePerNight']['repeat_days'];
							$data['CustomPricePerNight']['is_custom'] = 1;
							//$data['CustomPricePerNight']['repeat_end_date'] =  $custom_night['CustomPricePerNight']['repeat_end_date'];
							if(isPluginEnabled('Seats') && $custom_night['CustomPricePerNight']['is_seating_selection']){
								$data['CustomPricePerNight']['is_seating_selection'] = $custom_night['CustomPricePerNight']['is_seating_selection'];
								$data['CustomPricePerNight']['hall_id'] = $custom_night['CustomPricePerNight']['hall_id'];
							}							
							$this->ItemUser->Item->CustomPricePerNight->create();
							$this->ItemUser->Item->CustomPricePerNight->save($data);
							$custom_price_per_night_id = $this->ItemUser->Item->CustomPricePerNight->getLastInsertId();
							$replace_arr = array();
							$this->request->data['ItemUser']['custom_price_per_night_id'] = $custom_price_per_night_id;
							foreach($custom_night['CustomPricePerType'] As $custom_type) {
								$_data = array();
								$_data['CustomPricePerType']['item_id'] = $custom_type['item_id'];
								$_data['CustomPricePerType']['custom_price_per_night_id'] = $custom_price_per_night_id;
								$_data['CustomPricePerType']['name'] = $custom_type['name'];
								$_data['CustomPricePerType']['description'] = $custom_type['description'];
								$_data['CustomPricePerType']['price'] = $custom_type['price'];
								$_data['CustomPricePerType']['max_number_of_quantity'] = $custom_type['max_number_of_quantity'];
								$_data['CustomPricePerType']['min_number_per_order'] = $custom_type['min_number_per_order'];
								$_data['CustomPricePerType']['max_number_per_order'] = $custom_type['max_number_per_order'];
								$_data['CustomPricePerType']['is_advanced_enabled'] = 0;
								$_data['CustomPricePerType']['booked_quantity'] = 0;
								$_data['CustomPricePerType']['start_time'] = $custom_type['start_time'];
								$_data['CustomPricePerType']['end_time'] = $custom_type['end_time'];
								 if(isPluginEnabled('Seats') && $custom_night['CustomPricePerNight']['is_seating_selection']){
									$_data['CustomPricePerType']['partition_id'] = $custom_type['partition_id'];
								}
								$this->ItemUser->Item->CustomPricePerType->create();
								$this->ItemUser->Item->CustomPricePerType->save($_data);								
								$custom_price_per_type_id = $this->ItemUser->Item->CustomPricePerType->getLastInsertId();
								$replace_arr[$custom_type['id']] = $custom_price_per_type_id ;
								 if(isPluginEnabled('Seats') && $custom_night['CustomPricePerNight']['is_seating_selection']){
									if(!empty($data['CustomPricePerNight']['is_seating_selection'])){
										$seats = $this->ItemUser->CustomPricePerTypesSeat->Seat->find('all', array(
											'conditions' => array(
												'Seat.hall_id' => $data['CustomPricePerNight']['hall_id'],
												'Seat.partition_id' => $_data['CustomPricePerType']['partition_id'],
											),
											'order' => array(
												'Seat.id' => 'ASC',
											) ,                
											'recursive' => -1
										));	
										$stored = array('CustomPricePerTypesSeat' => array());
										foreach($seats as $seat){
											$tmp = array();
											$tmp['item_id'] = $_data['CustomPricePerType']['item_id'];
											$tmp['custom_price_per_type_id'] = $custom_price_per_type_id;
											$tmp['seat_id'] = $seat['Seat']['id'];
											$tmp['hall_id'] = $seat['Seat']['hall_id'];
											$tmp['partition_id'] = $seat['Seat']['partition_id'];
											$tmp['name'] = $seat['Seat']['name'];
											$tmp['seat_status_id'] = $seat['Seat']['seat_status_id'];
											$tmp['position'] = $seat['Seat']['position'];
											$tmp['name'] = $seat['Seat']['name'];
											$stored['CustomPricePerTypesSeat'][] = $tmp;
										}
										$this->ItemUser->CustomPricePerTypesSeat->saveAll($stored['CustomPricePerTypesSeat']);
										$this->Session->delete('SeatBlockTime');
									}
								}								
							}
							$quantity_arr = array();
							foreach($this->request->data['ItemUser']['custom_price_per_type'] As $key => $price_type) {
								$quantity_arr[$replace_arr[$key]] = $price_type;
							}
							$this->request->data['ItemUser']['custom_price_per_type'] = $quantity_arr;
						}
						$custom_price_per_night = $this->ItemUser->Item->CustomPricePerNight->find('first', array(
							'conditions' => array(
								'CustomPricePerNight.id' => $this->request->data['ItemUser']['custom_price_per_night_id'],
								'CustomPricePerNight.is_available' => 1,
							),
							'recursive' => -1,
						));
						if(!empty($custom_price_per_night)) {
							$from = $custom_price_per_night['CustomPricePerNight']['start_date'];
							$to = $custom_price_per_night['CustomPricePerNight']['end_date'];
							foreach($this->request->data['ItemUser']['custom_price_per_type'] As $key => $date) {
								$custom_price_per_type = $this->ItemUser->Item->CustomPricePerType->find('first', array(
									'conditions' => array(
										'CustomPricePerType.id' => $key
									),
									'recursive' => -1,
								));
							}
							$from_time = $custom_price_per_type['CustomPricePerType']['start_time'];
							$to_time = $custom_price_per_type['CustomPricePerType']['end_time'];
							$this->request->data['ItemUser']['from'] = $from . ' ' . $from_time;
							$this->request->data['ItemUser']['to'] = $to . ' ' . $to_time;
						}
					}
				} else if ($item['Item']['is_people_can_book_my_time']) {
					$price_type_valid = true;
					$price_type_cnt = count($this->request->data['CustomPricePerNight']);
					if(empty($this->request->data['CustomPricePerNight'])){
						$price_type_valid = false;
						$message = __l('Please select any one price');
					}
					if(!$price_type_valid || $price_type_cnt == 0){
						$mssage = array($message, "error" => 1);
						$this->Session->setFlash($message, 'default', null, 'error');
						if (!$this->RequestHandler->prefers('json')) {
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'view',
								$item['Item']['slug']
							));
						}
					}
					$from = $this->request->data['ItemUser']['start_date']['year'] . '-' . $this->request->data['ItemUser']['start_date']['month'] . '-' . $this->request->data['ItemUser']['start_date']['day'];
					$to = $this->request->data['ItemUser']['end_date']['year'] . '-' . $this->request->data['ItemUser']['end_date']['month'] . '-' . $this->request->data['ItemUser']['end_date']['day'];
					if(strtolower($this->request->data['ItemUser']['start_time']['meridian']) == 'am' && $this->request->data['ItemUser']['start_time']['hour'] == '12'){
						$this->request->data['ItemUser']['start_time']['hour'] = "00";
					} elseif(strtolower($this->request->data['ItemUser']['start_time']['meridian']) == 'pm' && $this->request->data['ItemUser']['start_time']['hour'] < '12') {
						$this->request->data['ItemUser']['start_time']['hour'] = $this->request->data['ItemUser']['start_time']['hour'] + 12;
					}
					if(strtolower($this->request->data['ItemUser']['end_time']['meridian']) == 'am' && $this->request->data['ItemUser']['end_time']['hour'] == '12'){
						$this->request->data['ItemUser']['end_time']['hour'] = "00";
					} elseif(strtolower($this->request->data['ItemUser']['end_time']['meridian']) == 'pm' && $this->request->data['ItemUser']['end_time']['hour'] < '12') {
						$this->request->data['ItemUser']['end_time']['hour'] = $this->request->data['ItemUser']['end_time']['hour'] + 12;
					}
					$from_time = $this->request->data['ItemUser']['start_time']['hour'].':' . $this->request->data['ItemUser']['start_time']['min'] . ':00';
					$to_time = $this->request->data['ItemUser']['end_time']['hour'].':' . $this->request->data['ItemUser']['end_time']['min'] . ':00';
					$check_start_date = $from . ' ' . $from_time;
					$check_end_date = $to . ' ' . $to_time;
					$this->request->data['ItemUser']['from'] = $from . ' ' . $from_time;
					$this->request->data['ItemUser']['to'] = $to . ' ' . $to_time;
						$total_price = 0;
						$total_quantity = 0;
						$custom_price_per_night = $this->ItemUser->CustomPricePerNight->find('first', array(
							'conditions' => array(
								'CustomPricePerNight.id' => $this->request->data['ItemUser']['custom_price_per_night_id']
							)
						));
						$min_hours = $custom_price_per_night['CustomPricePerNight']['min_hours'];
						$sub_price = 0;
						foreach($this->request->data['CustomPricePerNight'] as $key => $val){
							$tot_price = 0;
							$custom_price_per_night_id = $key;
							$price = $this->ItemUser->getCustomPrice($from, $from_time, $to, $to_time, $item['Item']['id'], $custom_price_per_night_id, $min_hours);
							$sub_price = $sub_price + $price;
							$tot_price = $price * $val;
							$total_price = $total_price + $tot_price;
							$total_quantity += $val;
						}
				}
				$this->request->data['ItemUser']['original_price'] = $total_price;
				$this->request->data['ItemUser']['quantity'] = $total_quantity;
				$this->request->data['ItemUser']['price'] = $sub_price;
				$this->request->data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::PaymentPending;
				$this->request->data['ItemUser']['additional_fee_amount'] = 0;
				if (!empty($item['Item']['is_additional_fee_to_buyer'])) {
					$this->request->data['ItemUser']['additional_fee_amount'] = $this->request->data['ItemUser']['original_price'] * ($item['Item']['additional_fee_percentage'] /100);
				}
				// seat selection commission calculation
				if(isPluginEnabled('Seats') && !empty($this->request->data['ItemUser']['is_seating_selection'])){
					$this->request->data['ItemUser']['seat_selection_amount'] = Configure::read('seat.seat_selection_fee');
					if(Configure::read('seat.seat_selection_fee_payer') == 'User'){
						$this->request->data['ItemUser']['original_price'] = $total_price + $this->request->data['ItemUser']['seat_selection_amount'];
					} 
					if(!empty($this->request->data['ItemUser']['host_service_amount'])){
						$this->request->data['ItemUser']['host_service_amount'] = $this->request->data['ItemUser']['host_service_amount'] + $this->request->data['ItemUser']['seat_selection_amount'];
					} else{
						$this->request->data['ItemUser']['host_service_amount'] = $this->request->data['ItemUser']['seat_selection_amount'];
					}
					
				}
				 // end
				$this->request->data['ItemUser']['booker_service_amount'] = ($this->request->data['ItemUser']['price'] + $this->request->data['ItemUser']['additional_fee_amount']) * (Configure::read('item.booking_service_fee') /100);
				$hosting_fee = ($this->request->data['ItemUser']['original_price'] + $this->request->data['ItemUser']['additional_fee_amount']) * (Configure::read('item.host_commission_amount') /100);
				if (!empty($item['Item']['is_buyer_as_fee_payer'])) {
					$this->request->data['ItemUser']['booker_service_amount'] += $hosting_fee;
					$this->request->data['ItemUser']['host_service_amount'] = 0;
				} else {
					$this->request->data['ItemUser']['host_service_amount'] = $hosting_fee;
				}
				
				
			}
            $this->ItemUser->set($this->request->data);
            if ($this->ItemUser->validates() && empty($mssage)) {
                $this->ItemUser->save($this->request->data);
                $item_user_id = $this->ItemUser->getLastInsertId();
				// Save Message to host //
				if (!empty($this->request->data['ItemUser']['message'])) {
					$message_sender_user_id = $this->request->data['ItemUser']['owner_user_id'];
					$subject = 'Message to host';
					$message = $this->request->data['ItemUser']['message'];
					$item_id = $item['Item']['id'];
					$order_id = $item_user_id;
					$message_id = $this->ItemUser->Message->sendNotifications($message_sender_user_id, $subject, $message, $order_id, $is_review = 0, $item_id, ConstItemUserStatus::BookingRequest);
				} 
               	//CustomPricePerTypeItemUser update
				if (isset($this->request->data['ItemUser']['request'])) {
				} else if($this->request->data['ItemUser']['bookit']) {
					if ($item['Item']['is_sell_ticket']) {
						if(!empty($this->request->data['ItemUser']['custom_price_per_type'])) {
							foreach($this->request->data['ItemUser']['custom_price_per_type'] As $key => $price_type) {
								$_data = array();
								if(!empty($price_type) && $price_type > 0) {
									$custom_price_per_type = $this->ItemUser->Item->CustomPricePerType->find('first', array(
										'conditions' => array(
											'CustomPricePerType.id' => $key,
										),
										'recursive' => -1,
									));
									if($custom_price_per_type) {								
										$_data['CustomPricePerTypeItemUser']['item_user_id'] = $item_user_id;
										$_data['CustomPricePerTypeItemUser']['custom_price_per_type_id'] = $custom_price_per_type['CustomPricePerType']['id'];
										$_data['CustomPricePerTypeItemUser']['number_of_quantity'] = $price_type;
										$_data['CustomPricePerTypeItemUser']['price'] = $custom_price_per_type['CustomPricePerType']['price'];
										$_data['CustomPricePerTypeItemUser']['total_price'] = $custom_price_per_type['CustomPricePerType']['price'] * $price_type;
										$this->ItemUser->CustomPricePerTypeItemUser->create();
										$this->ItemUser->CustomPricePerTypeItemUser->save($_data);
									}
								}
							}
						}
					} else if ($item['Item']['is_people_can_book_my_time']) {
						if(!empty($this->request->data['CustomPricePerNight'])) {
							foreach($this->request->data['CustomPricePerNight'] As $key => $price_type) {
								$_data = array();
								if(!empty($price_type)) {
									$custom_price_per_night = $this->ItemUser->Item->CustomPricePerNight->find('first', array(
										'conditions' => array(
											'CustomPricePerNight.id' => $key,
										),
										'recursive' => -1,
									));
									if($custom_price_per_night) {	
										$custom_price_per_night_id = $key;
										$_data['CustomPricePerTypeItemUser']['item_user_id'] = $item_user_id;
										$_data['CustomPricePerTypeItemUser']['custom_price_per_night_id'] = $custom_price_per_night_id;
										$_data['CustomPricePerTypeItemUser']['number_of_quantity'] = $price_type;
										$price = $this->ItemUser->getCustomPrice($from, $from_time, $to, $to_time, $item['Item']['id'], $custom_price_per_night_id, $min_hours);
										$_data['CustomPricePerTypeItemUser']['price'] = $price;
										$_data['CustomPricePerTypeItemUser']['total_price'] = $price * $price_type;
										$this->ItemUser->CustomPricePerTypeItemUser->create();
										$this->ItemUser->CustomPricePerTypeItemUser->save($_data);
									}
								}
							}	
						}
					}
				}
				if ($this->RequestHandler->isAjax()) {
                    if (isset($this->request->data['ItemUser']['request'])) {
                        $this->Session->setFlash(__l('Your Request has been posted successfully') , 'default', null, 'success');
						if(empty($mssage))
							$mssage = array("message" => __l('Your Request has been posted successfully'), "error" => 0);
						if(!$this->RequestHandler->prefers('json')){
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'view',
								$this->request->data['ItemUser']['item_slug']
							));
						}
                    } else {
						$this->Session->setFlash(sprintf(__l('%s has been booked'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
						if(empty($mssage))
							$mssage = array("message" => sprintf(__l('%s has been booked'), Configure::read('item.alt_name_for_item_singular_caps')), "error" => 0, "order_id" => $item_user_id);
						if(!$this->RequestHandler->prefers('json')){
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'order',
								$this->request->data['ItemUser']['item_id'],
								'order_id' => $item_user_id,
							));
						}
                    }
                } else {
                    if (isset($this->request->data['ItemUser']['request'])) {
                        $this->Session->setFlash(__l('Your Request has been posted successfully') , 'default', null, 'success');
						if(empty($mssage))
							$mssage = array("message" => __l('Your Request has been posted successfully'), "error" => 0);
						if(!$this->RequestHandler->prefers('json')){
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'view',
								$this->request->data['ItemUser']['item_slug']
							));
						}
                    } else {
                        if(empty($mssage))
							$mssage = array("message" => sprintf(__l('%s has been booked'), Configure::read('item.alt_name_for_item_singular_caps')), "error" => 0, "order_id" => $item_user_id);
							if(isPluginEnabled('Seats') && !empty($this->request->data['ItemUser']['is_seating_selection'])){ 
								$this->redirect(array(
										'controller' => 'seats',
										'action' => 'selection',
										$item_user_id
									));
							} else {
								if(!$this->RequestHandler->prefers('json')){
									$this->redirect(array(
										'controller' => 'items',
										'action' => 'order',
										$this->request->data['ItemUser']['item_id'],
										'order_id' => $item_user_id,
									));
								}
						}
                    }
                }
            } else {
				$this->Session->setFlash(sprintf(__l('%s not available on the specified date . Please, try for some other dates.'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
				$mssage = array("message" => sprintf(__l('%s not available on the specified date . Please, try for some other dates.'), Configure::read('item.alt_name_for_item_singular_caps')), "error" => 1);
				if(!$this->RequestHandler->prefers('json')){
					$this->redirect(array(
						'controller' => 'items',
						'action' => 'view',
						$this->request->data['ItemUser']['item_slug']
					));
				}
            }
        }
        // <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
            if(empty($mssage)){
                $mssage = array("message" => __l('Invalid Request'), "error" => 0);
            }
			$response = Cms::dispatchEvent('Controller.ItemUser.Add', $this, array('message' => $mssage));
		}
        $paymentGateways = $this->ItemUser->PaymentGateway->find('list');
        $this->set(compact('paymentGateways'));
    }
    public function admin_index()
    {
        $this->_redirectGET2Named(array(
            'role_id',
            'filter_id',
            'q'
        ));
        $this->pageTitle = Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Bookings');
        $conditions = array();
        if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'booker_cleared') {
            $conditions['ItemUser.item_user_status_id !='] = ConstItemUserStatus::PaymentPending;
        } elseif (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'host_cleared') {
            // @todo "Auto review" add condition CompletedAndClosedByAdmin
            $conditions['or'] = array(
                array(
                    'ItemUser.item_user_status_id' => ConstItemUserStatus::Completed,
                ) ,
            );
        } elseif (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'booker_pipeline') {
            $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::PaymentPending;
        } elseif (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'host_pipeline') {
            $conditions['or'] = array(
                array(
                    'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed,
                ) ,
            );
        } elseif (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'host_lost') {
            $conditions['or'] = array(
                array(
                    'ItemUser.item_user_status_id' => ConstItemUserStatus::Rejected,
                ) ,
                array(
                    'ItemUser.item_user_status_id' => ConstItemUserStatus::Canceled,
                ) ,
                array(
                    'ItemUser.item_user_status_id' => ConstItemUserStatus::Expired,
                ) ,
                array(
                    'ItemUser.item_user_status_id' => ConstItemUserStatus::CanceledByAdmin,
                )
            );
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['ItemUser.created ='] = date('Y-m-d', strtotime('now'));
            $this->pageTitle.= ' '.__l('booked today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['ItemUser.created >='] = date('Y-m-d', strtotime('now -7 days'));
            $this->pageTitle.= ' '.__l('booked in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['ItemUser.created >='] = date('Y-m-d', strtotime('now -30 days'));
            $this->pageTitle.= ' '.__l('booked in this month');
        }
        if (isset($this->request->params['named']['item_id'])) {
            $conditions['ItemUser.item_id'] = $this->request->params['named']['item_id'];
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            $this->request->data['ItemUser']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data['ItemUser']['filter_id'])) {
            switch ($this->request->data['ItemUser']['filter_id']) {
                case ConstItemUserStatus::PaymentPending:
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::PaymentPending;
                    $this->pageTitle.= ' '.__l('Payment Pending');
                    break;

                case ConstItemUserStatus::WaitingforAcceptance:
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::WaitingforAcceptance;
                    $this->pageTitle.= ' '.__l('Waiting for Acceptance');
                    break;

                case ConstItemUserStatus::Confirmed:
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::Confirmed;
                    $this->pageTitle.= ' '.__l('Confirmed');
                    break;

                case ConstItemUserStatus::Rejected:
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::Rejected;
                    $this->pageTitle.= ' '.__l('Rejected');
                    break;

                case ConstItemUserStatus::Canceled:
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::Canceled;
                    $this->pageTitle.= ' '.__l('Canceled');
                    break;

                case ConstItemUserStatus::WaitingforReview:
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::WaitingforReview;
                    $this->pageTitle.= ' '.__l('Waiting for Review');
                    break;

                case ConstItemUserStatus::Completed:
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::Completed;
                    $this->pageTitle.= ' '.__l('Completed');
                    break;

                case ConstItemUserStatus::Expired:
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::Expired;
                    $this->pageTitle.= ' '.__l('Expired');
                    break;

                case ConstItemUserStatus::CanceledByAdmin:
                    $conditions['ItemUser.item_user_status_id'] = ConstItemUserStatus::CanceledByAdmin;
                    $this->pageTitle.= ' '.__l('Canceled by Admin');
                    break;

                case ConstItemUserStatus::HostReviewed:
                    $conditions['ItemUser.is_host_reviewed'] = 1;
                    $this->pageTitle.= ' '.__l('Payment Cleared');
                    break;
                    // @todo "Auto review" add new case CompletedAndClosedByAdmin

            }
            $this->request->params['named']['filter_id'] = $this->request->data['ItemUser']['filter_id'];
		}
		if (isset($this->request->params['named']['q'])) {
			$conditions['AND']['OR'][]['Item.title LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['ItemUserStatus.name LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $conditions['AND']['OR'][]['User.username LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['OwnerUser.username LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$this->request->data['ItemUser']['q'] = $this->request->params['named']['q'];
			$this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
		}
		$this->paginate = array(
			'conditions' => $conditions,
				'contain' => array(
					'User' => array(
						'fields' => array(
							'User.id',
							'User.username',
							'User.blocked_amount',
							'User.cleared_amount',
						)
					) ,
					'OwnerUser' => array(
						'fields' => array(
							'OwnerUser.id',
							'OwnerUser.username',
						)
					) ,
					'ItemUserStatus' => array(
						'fields' => array(
							'ItemUserStatus.id',
							'ItemUserStatus.name',
							'ItemUserStatus.item_user_count',
							'ItemUserStatus.slug',
						)
					) ,
					'Message',
					'Item' => array(
						'fields' => array(
							'Item.id',
							'Item.created',
							'Item.title',
							'Item.slug',
							'Item.user_id',
							'Item.description',
							'Item.user_id',
							'Item.latitude',
							'Item.longitude',
							'Item.address',
						) ,
						'User' => array(
							'UserAvatar',
							'fields' => array(
								'User.id',
								'User.username',
								'User.blocked_amount',
								'User.cleared_amount',
							)
						) ,
						'Country' => array(
							'fields' => array(
								'Country.name',
								'Country.iso_alpha2'
							)
						) ,
						'Attachment' => array(
							'fields' => array(
								'Attachment.id',
								'Attachment.filename',
								'Attachment.dir',
								'Attachment.width',
								'Attachment.height'
							) ,
							'limit' => 1,
						) ,
					) ,
				) ,
			'recursive' => 2,
			'order' => array(
				'ItemUser.id' => 'desc'
			)
		);
		$itemUserStatuses = $this->ItemUser->ItemUserStatus->find('list');
		foreach($itemUserStatuses as $id => $itemUserStatus) {
			$count_conditions = array();
			$count_conditions['ItemUser.item_user_status_id'] = $id;
			$itemUserStatusesCount[$id] = $this->ItemUser->find('count', array(
				'conditions' => $count_conditions,
				'recursive' => -1
			));
		}
		$count_conditions = array();
		$count_conditions['ItemUser.is_host_reviewed'] = 1;
		$itemUserStatusesCount[ConstItemUserStatus::HostReviewed] = $this->ItemUser->find('count', array(
			'conditions' => $count_conditions,
			'recursive' => -1
		));
		$this->set('itemUserStatusesCount', $itemUserStatusesCount);
		$this->set('itemUserStatuses', $itemUserStatuses);
		$this->set('itemUsers', $this->paginate());
		$this->set('total_count', $this->ItemUser->find('count'));
		$filters = $this->ItemUser->isFilterOptions;
		$moreActions = $this->ItemUser->moreActions;
		$this->set(compact('moreActions', 'filters'));
	}
	public function admin_delete($id = null)
	{
		if (is_null($id)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$conditions = array();
		$conditions['ItemUser.id'] = $id;
		$conditions['ItemUser.item_user_status_id'] = array(
			ConstItemUserStatus::WaitingforAcceptance,
			ConstItemUserStatus::Confirmed
		);
		$check_order = $this->ItemUser->find('first', array(
			'conditions' => $conditions,
			'recursive' => -1
		));
		if (empty($check_order)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		if ($this->ItemUser->updateStatus($id, ConstItemUserStatus::CanceledByAdmin)) {
			$this->Session->setFlash(__l('Booking has been canceled and refunded.') , 'default', null, 'success');
			$this->redirect(array(
				'controller' => 'item_users',
				'action' => 'index',
				'admin' => true
			));
		} else {
			throw new NotFoundException(__l('Invalid request'));
		}
	}
	public function track_order($id = null)
	{
		if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$itemOrder = $this->ItemUser->find('first', array(
			'conditions' => array(
				'ItemUser.id' => $id,
				'ItemUser.user_id' => $this->Auth->user('id')
			) ,
			'contain' => array(
				'Item' => array(					
					'User' => array(
						'fields' => array(
							'User.id',
							'User.username',
							'User.email',
							'User.blocked_amount',
							'User.cleared_amount',
						)
					)
				) ,
				'User' => array(
					'fields' => array(
						'User.id',
						'User.username',
						'User.email',
						'User.blocked_amount',
						'User.cleared_amount',
					)
				) ,
			) ,
			'recursive' => 2,
		));
		$relatedMessages = $this->ItemUser->Message->find('all', array(
			'conditions' => array(
				'Message.item_user_id =' => $id,
				'Message.is_review =' => 1,
				'Message.is_sender =' => 0
			) ,
			'contain' => array(
				'MessageContent' => array(
					'fields' => array(
						'MessageContent.id',
						'MessageContent.created',
						'MessageContent.subject',
						'MessageContent.message',
					) ,
					'Attachment'
				) ,
				'Item' => array(
					'User' => array(
						'fields' => array(
							'User.id',
							'User.username',
							'User.email',
						)
					) ,
					'fields' => array(
						'Item.id',
						'Item.created',
						'Item.user_id',
					)
				)
			) ,
			'recursive' => 2,
		));
		if (empty($itemOrder)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$this->pageTitle = __l('Status of your booking No') . '#' . $itemOrder['ItemUser']['id'];
		if (!$this->RequestHandler->prefers('json')) {
			if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'print') {
				$this->pageTitle = __l('Booking') . '# ' . $itemOrder['ItemUser']['id'];
				$this->layout = 'print';
			}
		}
		$this->set(compact('itemOrder', 'relatedMessages'));
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.ItemUser.TrackOrder', $this, array());
		}		
	}
	public function manage()
	{
		if ($this->RequestHandler->prefers('json') && $this->request->is('get')){
			unset($this->request->data['User']);
		}
		$conditions = array();
		if (!empty($this->request->data)) {
			$is_sucess = false;
			$conditions['ItemUser.id'] = $this->request->data['ItemUser']['id'];
			$order = $this->ItemUser->find('first', array(
				'conditions' => $conditions,
				'contain' => array(
					'User' => array(
						'fields' => array(
							'User.id',
							'User.username',
							'User.email',
						) ,
					) ,
					'OwnerUser' => array(
						'fields' => array(
							'OwnerUser.id',
							'OwnerUser.username',
							'OwnerUser.email',
						) ,
					) ,
					'Item' => array(
						'fields' => array(
							'Item.id',
							'Item.title',
							'Item.slug',
						) ,
					) ,
				) ,
				'recursive' => 2
			));
		} else {
			$conditions['ItemUser.id'] = $this->request->params['named']['item_user_id'];
			$this->request->data['ItemUser']['id'] = $this->request->params['named']['item_user_id'];
		}
		$order = $this->ItemUser->find('first', array(
			'conditions' => $conditions,
			'contain' => array(
				'User' => array(
					'fields' => array(
						'User.id',
						'User.username',
					)
				) ,
				'Item' => array(					
					'User' => array(
						'fields' => array(
							'User.id',
							'User.username',
						)
					) ,
				) ,
				'ItemUserStatus'
			) ,
			'recursive' => 2
		));
		if (empty($order)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$this->request->data['ItemUser']['report_id'] = 0;
		$this->set('order', $order);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.ItemUser.Manage', $this, array());
		}
	}
	public function update_order($item_user_id, $order, $from = null)
	{
		$itemUser = $this->ItemUser->find('first', array(
			'conditions' => array(
				'ItemUser.id' => $item_user_id
			) ,
			'contain' => array(
				'Item',
				'CustomPricePerTypeItemUser',
				'CustomPricePerNight' => array(
					'CustomPricePerType'
				),
			),
			'recursive' => 2
		));	
		if (empty($itemUser)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$success = '0';
		App::import('Model', 'Payment');
		$this->Payment = new Payment();	
		if (!empty($order) && !empty($itemUser)) {
			$processed_order = array();
			if($order == 'accept') {
				if($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance) {
					foreach($itemUser['CustomPricePerNight']['CustomPricePerType'] as $key=>$value) {
						if(!empty($itemUser['CustomPricePerTypeItemUser'][$key]['number_of_quantity']) && $value['max_number_of_quantity'] > 0) {
							$booked_quantity = $value['booked_quantity'] + $itemUser['CustomPricePerTypeItemUser'][$key]['number_of_quantity'];	
							if($booked_quantity > $value['max_number_of_quantity']) {								
								$this->Session->setFlash(__l('No availability') , 'default', null, 'error');
								$this->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::Expired);
								$this->redirect(array(
									'controller' => 'item_users',
									'action' => 'index',
									'type' => 'myworks',
									'status' => 'expired'									
								));								
							}
						}
					}
					$processed_order['redirect'] = 'myworks';
					$processed_order['flash_message'] = __l('You have successfully accepted the booking request');
					$processed_order['ajax_repsonse'] = 'accepted';
					$processed_order['status'] = 'confirmed';
                    $processed_order['error'] = '0';
					$this->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::Confirmed);
				} elseif($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::BookingRequest) {
					$_data = array();
					$_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
					$total_quantity = 0;
					$total_price = 0;
					if ($itemUser['Item']['is_sell_ticket']) {
						$_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::PaymentPending;
					} else if ($itemUser['Item']['is_people_can_book_my_time']) {
						$start = explode(' ', $itemUser['ItemUser']['from']);
						$end = explode(' ', $itemUser['ItemUser']['to']);
						$from = $start[0];
						$to = $end[0];
						$from_time = $start[1];
						$to_time = $end[1];
						$total_price = $this->ItemUser->getCustomPrice($from, $from_time, $to, $to_time, $itemUser['Item']['id']);
						$_data['ItemUser']['original_price'] = $total_price;
						$_data['ItemUser']['quantity'] = $total_quantity;
						$_data['ItemUser']['price'] = $price;
						$_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::PaymentPending;
						$_data['ItemUser']['additional_fee_amount'] = 0;
						if (!empty($itemUser['Item']['is_additional_fee_to_buyer'])) {
							$_data['ItemUser']['additional_fee_amount'] = $this->request->data['ItemUser']['price'] * ($itemUser['Item']['additional_fee_percentage'] /100);
						}
						$_data['ItemUser']['booker_service_amount'] = ($_data['ItemUser']['price'] + $_data['ItemUser']['additional_fee_amount']) * (Configure::read('item.booking_service_fee') /100);
						$hosting_fee = ($_data['ItemUser']['price'] + $_data['ItemUser']['additional_fee_amount']) * (Configure::read('item.host_commission_amount') /100);
						if (!empty($itemUser['Item']['is_buyer_as_fee_payer'])) {
							$_data['ItemUser']['booker_service_amount'] = $hosting_fee;
							$_data['ItemUser']['host_service_amount'] = 0;
						} else {
							$_data['ItemUser']['host_service_amount'] = $hosting_fee;
						}
					}
					$this->ItemUser->save($_data);
					$processed_order['redirect'] = 'myworks';
					$processed_order['flash_message'] = __l('Booking request have been successfully accepted');
					$processed_order['ajax_repsonse'] = 'accepted';
					$processed_order['status'] = 'confirmed';
                    $processed_order['error'] = '0';
				}
			} else if($order == 'cancel') {
				$processed_order['redirect'] = 'mytours';
				$processed_order['flash_message'] = __l('You have successfully canceled your booking.');
				$processed_order['ajax_repsonse'] = 'canceled';
                $processed_order['error'] = '0';
				$this->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::Canceled);
			} else if($order == 'reject') {
				if($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::BookingRequest) {
					$_data = array();
					$_data['ItemUser']['id'] = $item['ItemUser']['id'];
					$_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::BookingRequestRejected;
					$this->ItemUser->save($_data);
					
					$processed_order['redirect'] = 'myworks';
					$processed_order['flash_message'] = __l('Booking request have been rejected successfully');
					$processed_order['ajax_repsonse'] = 'rejected';
					$processed_order['status'] = 'rejected';
                    $processed_order['error'] = '0';
				} else {
					$processed_order['redirect'] = 'myworks';
					$processed_order['flash_message'] = __l('You have rejected the booking successfully');
					$processed_order['ajax_repsonse'] = 'rejected';
					$processed_order['status'] = 'rejected';
                    $processed_order['error'] = '0';
					$this->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::Rejected);
				}
			} else if($order == 'review') {
				$processed_order['redirect'] = 'myworks';
				$processed_order['flash_message'] = __l('Your work has been delivered successfully!');
				$processed_order['ajax_repsonse'] = 'waiting_for_Review';
				$processed_order['status'] = 'waiting_for_Review';
                $processed_order['error'] = '0';
				$this->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::WaitingforReview);
			} else if($order == 'completed') {
				$processed_order['redirect'] = 'mytours';
				$processed_order['flash_message'] = sprintf(__l('You have completed the booking, please give review and rate the %s!'), Configure::read('item.alt_name_for_item_singular_small'));
				$processed_order['ajax_repsonse'] = 'completed';
				$processed_order['status'] = 'completed';
                $processed_order['error'] = '0';
				$this->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::Completed);
			}
			if (!empty($processed_order['redirect'])) {
				// <-- For iPhone App code
				if ($this->RequestHandler->prefers('json')) {
					Cms::dispatchEvent('Controller.ItemUser.UpdateOrder', $this, array('processed_order' => $processed_order));
				} // For iPhone App code -->
				else {
					if (!empty($processed_order['flash_message'])) {
						$this->Session->setFlash($processed_order['flash_message'], 'default', null, 'success');
					}
					if ($this->RequestHandler->isAjax() && ($processed_order['ajax_repsonse'] == 'failed' || $this->request->params['named']['view_type'] == 'activities')) {
						$ajax_url = Router::url(array(
							'controller' => 'messages',
							'action' => 'activities',
							'order_id' => $processed_order['order_id'],
							'type' => $processed_order['redirect'],
							'status' => !empty($processed_order['status']) ? $processed_order['status'] : __l('all') ,
						) , true);
						$success_msg = 'redirect*' . $ajax_url;
						echo $success_msg;
						exit;
					}
					if ($this->RequestHandler->isAjax() && $processed_order['ajax_repsonse'] != 'failed') {
						echo $processed_order['ajax_repsonse'];
						exit;
					}
					$this->redirect(array(
						'action' => 'index',
						'type' => $processed_order['redirect'],
						'status' => !empty($processed_order['status']) ? $processed_order['status'] : __l('all')
					));
				}
			} else {
				// <-- For iPhone App code
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => (!empty($processed_order['flash_message'])? $processed_order['flash_message'] : ""), "error" => 1));
					Cms::dispatchEvent('Controller.ItemUser.UpdateOrder', $this, array());
				} // For iPhone App code -->
				else {
					if (!empty($processed_order['flash_message'])) {
						$this->Session->setFlash($processed_order['flash_message'], 'default', null, 'error');
					}
					$this->redirect(array(
						'action' => 'index',
						'type' => $processed_order['redirect'],
						'status' => !empty($processed_order['status']) ? $processed_order['status'] : __l('all')
					));
				}
			}
		} else {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				Cms::dispatchEvent('Controller.ItemUser.UpdateOrder', $this, array());
			}else{
				$this->redirect(array(
					'controller' => 'items',
					'action' => 'index',
				));
			}
		}
	}
	public function process_fromto()
	{
		$this->ItemUser->set($this->request->data);
		if (!empty($this->request->data)) {
			$item_user = $this->ItemUser->find('first', array(
				'conditions' => array(
					'ItemUser.id' => $this->request->data['ItemUser']['order_id']
				) ,
				'recursive' => -1
			));
			if (empty($item_user)) {
				throw new NotFoundException(__l('Invalid request'));
			}
			if ($this->ItemUser->validates()) {
				$data = array();
				if (isset($this->request->data['ItemUser']['via'])) {
					$data['via'] = $this->request->data['ItemUser']['via'];
				}
				$str = $this->request->data['ItemUser']['fromto']['year'] . '-' . $this->request->data['ItemUser']['fromto']['month'] . '-' . $this->request->data['ItemUser']['fromto']['day'] . ' ' . (!empty($this->request->data['ItemUser']['fromto']['hour']) ? $this->request->data['ItemUser']['fromto']['hour'] : '') . (!empty($this->request->data['ItemUser']['fromto']['min']) ? ':' . $this->request->data['ItemUser']['fromto']['min'] : '') . ' ' . (!empty($this->request->data['ItemUser']['fromto']['meridian']) ? $this->request->data['ItemUser']['fromto']['meridian'] : '');
				$data['fromto'] = _formatDate('Y-m-d H:i:s', $str, true);
				if (!empty($this->request->data['ItemUser']['p_action']) && $this->request->data['ItemUser']['p_action'] == 'check_in' && in_array($item_user['ItemUser']['item_user_status_id'], array(ConstItemUserStatus::Confirmed))) {
					//$this->ItemUser->updateStatus($this->request->data['ItemUser']['order_id'], ConstItemUserStatus::Arrived, '', $data);
				}
				if (!empty($this->request->data['ItemUser']['p_action']) && $this->request->data['ItemUser']['p_action'] == 'check_out' && in_array($item_user['ItemUser']['item_user_status_id'], array(ConstItemUserStatus::WaitingforReview))) {
					$this->ItemUser->updateStatus($this->request->data['ItemUser']['order_id'], ConstItemUserStatus::Completed, '', $data);
				}
				// Save Private Note //
				if (!empty($this->request->data['ItemUser']['private_note'])) {
					$message_sender_user_id = $this->Auth->user('id');
					$subject = __l('Private Note');
					$message = $this->request->data['ItemUser']['private_note'];
					$item_id = $item_user['ItemUser']['item_id'];
					$order_id = $item_user['ItemUser']['id'];
					$message_id = $this->ItemUser->Message->sendNotifications($message_sender_user_id, $subject, $message, $order_id, $is_review = 0, $item_id, ConstItemUserStatus::PrivateConversation);
				}
				$this->Session->setFlash(__l('Status changed successfully') , 'default', null, 'success');
				if ($this->RequestHandler->isAjax()) {
					if (isset($this->request->data['ItemUser']['via']) && $this->request->data['ItemUser']['via'] == 'ticket') {
						$ajax_url = Router::url(array(
							'controller' => 'item_users',
							'action' => 'check_qr',
							$item_user['ItemUser']['top_code'],
							$item_user['ItemUser']['bottum_code'],
							'admin' => false,
						) , true);
					} else {
						$ajax_url = Router::url(array(
							'controller' => 'messages',
							'action' => 'activities',
							'order_id' => $item_user['ItemUser']['id'],
							'admin' => false,
						) , true);
					}
					$success_msg = 'redirect*' . $ajax_url;
					echo $success_msg;
					exit;
				} else {
					if (isset($this->request->data['ItemUser']['via']) && $this->request->data['ItemUser']['via'] == 'ticket') {
						$ajax_url = Router::url(array(
							'controller' => 'item_users',
							'action' => 'check_qr',
							$item_user['ItemUser']['top_code'],
							$item_user['ItemUser']['bottum_code'],
							'admin' => false,
						) , true);
						$this->redirect(array(
							'controller' => 'item_users',
							'action' => 'check_qr',
							$item_user['ItemUser']['top_code'],
							$item_user['ItemUser']['bottum_code'],
							'admin' => false,
						));
					} else {
						$this->redirect(array(
							'controller' => 'messages',
							'action' => 'activities',
							'order_id' => $item_user['ItemUser']['id'],
							'admin' => false,
						));
					}
				}
			} else {
				if ($item_user['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Confirmed) {
					$this->Session->setFlash(__l('Selected check in date should be greater the booked check in date') , 'default', null, 'error');
				}
				if ($item_user['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforReview) {
					$this->Session->setFlash(__l('Selected check out date should be greater the booked check out date') , 'default', null, 'error');
				}
			}
		}
		if (empty($this->request->data)) {
			if (!empty($this->request->params['named']['order_id'])) {
				$itemUser = $this->ItemUser->find('first', array(
					'conditions' => array(
						'ItemUser.id' => $this->request->params['named']['order_id']
					) ,
					'recursive' => -1
				));
				if (!empty($itemUser)) {
					if ($this->Auth->user('id') == $itemUser['ItemUser']['owner_user_id']) {
						$message = !empty($itemUser['ItemUser']['host_private_note']) ? $itemUser['ItemUser']['host_private_note'] : '';
					} elseif ($this->Auth->user('id') == $itemUser['ItemUser']['user_id']) {
						$message = !empty($itemUser['ItemUser']['booker_private_note']) ? $itemUser['ItemUser']['booker_private_note'] : '';
					}
					$this->request->data['ItemUser']['private_note'] = $message;
				}
			}
			$this->request->data['ItemUser']['fromto'] = date('Y-m-d H:i:s');
			if (!empty($this->request->params['named']['via'])) {
				$this->request->data['ItemUser']['via'] = $this->request->params['named']['via'];
			}
		}
	}
	public function check_qr()
	{
		$top_code = $this->request->params['pass'][0];
		$bottom_code = $this->request->params['pass'][1];
		if (is_null($top_code) || is_null($bottom_code)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$this->pageTitle = Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('user ticket');
		$conditions['ItemUser.top_code'] = $top_code;
		$conditions['ItemUser.bottom_code'] = $bottom_code;
		$itemUser = $this->ItemUser->find('first', array(
			'conditions' => $conditions,
			'contain' => array(
				'User',
				'Item',
				'ItemUserStatus',
				'CustomPricePerNight',
				'CustomPricePerTypeItemUser' => array(
					'CustomPricePerType',
				),
			) ,
			'recursive' => 3
		));
		if (empty($itemUser)) {
			$this->Session->setFlash(__l('Invalid ticket') , 'default', null, 'error');
			$this->redirect(Router::url('/', true));
		}
		if ($this->Auth->user('id') != $itemUser['ItemUser']['owner_user_id']) {
			$this->Session->setFlash(__l('You have no authorized to view this page') , 'default', null, 'error');
			$this->redirect(Router::url('/', true));
		}
		$this->set('itemUser', $itemUser);
	}
	public function check_availability()
	{
		$data = $this->request->data;
		if ($this->RequestHandler->prefers('json')) {
			$data['ItemUser'] = $data;
			$data['ItemUser']['start_date']['month'] = $data['start_date_month'];
			$data['ItemUser']['start_date']['day'] = $data['start_date_day'];
			$data['ItemUser']['start_date']['year'] = $data['start_date_year'];
			$data['ItemUser']['end_date']['month'] = $data['end_date_month'];
			$data['ItemUser']['end_date']['day'] = $data['end_date_day'];
			$data['ItemUser']['end_date']['year'] = $data['end_date_year'];
			$data['ItemUser']['start_time']['hour'] = $data['start_time_hour'];
			$data['ItemUser']['start_time']['min'] = $data['start_time_min'];
			$data['ItemUser']['start_time']['meridian'] = $data['start_time_meridian'];
			$data['ItemUser']['end_time']['hour'] = $data['end_time_hour'];
			$data['ItemUser']['end_time']['min'] = $data['end_time_min'];
			$data['ItemUser']['end_time']['meridian'] = $data['end_time_meridian'];
		}
		$start_hour = $data['ItemUser']['start_time']['hour'];
		$end_hour = $data['ItemUser']['end_time']['hour'];
		if(strtolower($data['ItemUser']['start_time']['meridian']) == 'am' && $data['ItemUser']['start_time']['hour'] == '12'){
			$start_hour = '00';
		} elseif(strtolower($data['ItemUser']['start_time']['meridian']) == 'pm' && $data['ItemUser']['start_time']['hour'] < '12'){
			$start_hour = $data['ItemUser']['start_time']['hour'] + 12;
		}
		if(strtolower($data['ItemUser']['end_time']['meridian']) == 'am' && $data['ItemUser']['end_time']['hour'] == '12'){
			$end_hour = '00';
		} elseif(strtolower($data['ItemUser']['end_time']['meridian']) == 'pm' && $data['ItemUser']['end_time']['hour'] < '12'){
			$end_hour = $data['ItemUser']['end_time']['hour'] + 12;
		}
		$request_start_date = $data['ItemUser']['start_date']['year'] . '-' . $data['ItemUser']['start_date']['month'] . '-'. $data['ItemUser']['start_date']['day'];
		$request_start_time = $start_hour . ':' . $data['ItemUser']['start_time']['min'];
		$request_end_date = $data['ItemUser']['end_date']['year'] . '-' . $data['ItemUser']['end_date']['month'] . '-'. $data['ItemUser']['end_date']['day'];
		$request_end_time = $end_hour . ':' . $data['ItemUser']['end_time']['min'];
		$requested_values = array();
		$custom_price_per_night_parent = $this->ItemUser->CustomPricePerNight->find('all', array(
			'conditions' => array(
				'CustomPricePerNight.item_id' => $data['ItemUser']['item_id'],
				'CustomPricePerNight.parent_id' => 0
			) ,
			'recursive' => 1
		));
		$is_timing = $custom_price_per_night_parent[0]['CustomPricePerNight']['is_timing'];
		$CustomPricePerNight_parent = $custom_price_per_night_parent[0]['CustomPricePerNight']['id'];
		$custom_price_per_nights = $this->ItemUser->CustomPricePerNight->find('all', array(
			'conditions' => array(
				'CustomPricePerNight.item_id' => $data['ItemUser']['item_id'],
				'CustomPricePerNight.parent_id' => $custom_price_per_night_parent[0]['CustomPricePerNight']['id']
			) ,
			'order' => array(
				'CustomPricePerNight.start_date' => 'ASC'
			),
			'recursive' => -1
		));		
		$available_bookings = array();
		$request_st = $request_start_date . ' '. $request_start_time;
		$request_end = $request_end_date . ' '. $request_end_time;
		if(strtotime($request_st) >= strtotime(date('Y-m-d H:i')) &&  strtotime($request_st) < strtotime($request_end)){
			foreach($custom_price_per_nights as $key => $custom_price_per_night){
				$avail_st_dt = $custom_price_per_night['CustomPricePerNight']['start_date'];
				$avail_end_dt = $custom_price_per_night['CustomPricePerNight']['end_date'];
				$avail_st_time = $custom_price_per_night['CustomPricePerNight']['start_time'];
				$avail_end_time = $custom_price_per_night['CustomPricePerNight']['end_time'];
				$str_end_date = strtotime($avail_end_dt);
				$repeat_end_date = $custom_price_per_night['CustomPricePerNight']['repeat_end_date'];
				if(!$is_timing){
				// all time
					$avail_st = $avail_st_dt . ' '. $avail_st_time;
					$avail_end = $avail_end_dt . ' '. $avail_end_time;
					if(!empty($str_end_date)){
						// end date available
						if(strtotime($request_st) >= strtotime($avail_st) && (strtotime($request_end) <= strtotime($avail_end) || strtotime($request_end_date) <= strtotime($repeat_end_date))){
							$available_bookings[] = $custom_price_per_night;
						}
					} else {
						// end date not available
						if(strtotime($request_st) >= strtotime($avail_st) && strtotime($request_end_date) <= strtotime($repeat_end_date)){
							$available_bookings[] = $custom_price_per_night;
						}
					}
				} else {
				// specific timing
					if(!empty($str_end_date)){
						// not empty of end date
						if(strtotime($request_start_date) >= strtotime($avail_st_dt) && (strtotime($request_end_date) <= strtotime($avail_end_dt) || strtotime($request_end_date) <= strtotime($repeat_end_date)) && strtotime($request_start_time) >= strtotime($avail_st_time) && strtotime($request_end_time) <= strtotime($avail_end_time) ){
							$available_bookings[] = $custom_price_per_night;
						}
					} else {
						// empty of end date
						if(strtotime($request_start_date) >= strtotime($avail_st_dt) && strtotime($request_start_time) >= strtotime($avail_st_time) && strtotime($request_end_time) <= strtotime($avail_end_time) && strtotime($request_end_date) <= strtotime($repeat_end_date)){
							$available_bookings[] = $custom_price_per_night;
						}
					}
				}
			}
		}
		if(!empty($available_bookings)){
			$day_of_the_week = array(
				1 => 'M',
				'Tu',
				'W',
				'Th',
				'F',
				'Sa',
				'Su'
			);
			$from = $request_start_date. ' ' . $request_start_time;
			$to = $request_end_date. ' '. $request_end_time;
			$total_days = (strtotime($to) - strtotime($from)) /(60*60*24);
			$repeat_days = array();
			$bookings_available = array();
			$not_avaliable = array();
			foreach($available_bookings As $key => $custom_price_per_night) {
				$repeat_end_date = $custom_price_per_night['CustomPricePerNight']['repeat_end_date'];
				if(!empty($custom_price_per_night['CustomPricePerNight']['repeat_days'])) {
					$repeat_days_arr = explode(',', $custom_price_per_night['CustomPricePerNight']['repeat_days']);
					for ($i = 0; $i <= $total_days; $i++) {
						$day = date('Y-m-d', strtotime($from . "+" . $i . " day"));
						$day_of_day = $day_of_the_week[date('N', strtotime($day))];
						$repeat_chk_st_dt = strtotime($day);
						$repeat_chk_end_dt = strtotime($repeat_end_date);
						if (!in_array($day_of_day, $repeat_days_arr)) {
							$not_avaliable[] = $key;
						} else if($repeat_chk_st_dt > $repeat_chk_end_dt){
							$not_avaliable[] = $key;
						}
					}
				}
			}
			foreach($not_avaliable as $val){
				unset($available_bookings[$val]);
			}
		} else {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('There is no booking available'), "error" => 0));
			}
		}	
		$this->set('available_bookings', $available_bookings);
		$this->set('custom_price_per_night_parent', $custom_price_per_night_parent);		
		$this->request->data['ItemUser']['custom_price_per_night_id'] = $CustomPricePerNight_parent;
		if ($this->RequestHandler->prefers('json')) {
			Cms::dispatchEvent('Controller.ItemUser.CheckAvail', $this, array(
				'available_bookings' => $available_bookings
			));
		}
	}
	/**
	 *
	 * @Description: Booking canceled. [Note: itemusers table remove booking]
	 *
	 */
	public function delete($id = null) {
		if (is_null($id)) {
            if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$itemUser = $this->ItemUser->find('first', array(
			'conditions' => array(
				'ItemUser.id' => $id
			),
			'contain' => array(
				'Item' => array(
					'fields' => array(
						'Item.slug'
					)
				),
			),
			'fields' => array(
				'ItemUser.item_id'
			)
		));
        if ($this->ItemUser->delete($id)) {
			$this->Session->delete('SeatBlockTime');
			if($this->request->params['named']['type'] == 'cancel_booking') {
				$this->Session->setFlash(__l('Booking Canceled') , 'default', null, 'success');
			} 
			if($this->request->params['named']['type'] == 'booking_timeout') {
				$this->Session->setFlash(__l('Booking time expired.Please Try again') , 'default', null, 'success');
			}
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Booking Canceled'), "error" => 0));
            } else {
				$this->redirect(array(
					'controller' => 'items',
					'action' => 'view',
					$itemUser['Item']['slug']
				));
			}
        } else {
            if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		if ($this->RequestHandler->prefers('json')) {
				Cms::dispatchEvent('Controller.ItemUser.Delete', $this, array());
		}
	}

}
?>