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
class ItemUser extends AppModel
{
    public $name = 'ItemUser';
    public $actsAs = array(
        'Aggregatable'
    );
    /*var $aggregatingFields = array(
        'message_count' => array(
            'mode' => 'real',
            'key' => 'item_user_id',
            'foreignKey' => 'item_user_id',
            'model' => 'Items.Message',
            'function' => 'COUNT(Message.item_user_id)',
        )
    );*/
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'OwnerUser' => array(
            'className' => 'User',
            'foreignKey' => 'owner_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Item' => array(
            'className' => 'Items.Item',
            'foreignKey' => 'item_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'PaymentGateway' => array(
            'className' => 'PaymentGateway',
            'foreignKey' => 'payment_gateway_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'ItemUserStatus' => array(
            'className' => 'Items.ItemUserStatus',
            'foreignKey' => 'item_user_status_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'CustomPricePerNight' => array(
            'className' => 'Items.CustomPricePerNight',
            'foreignKey' => 'custom_price_per_night_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public $hasMany = array(
        'ItemFeedback' => array(
            'className' => 'Items.ItemFeedback',
            'foreignKey' => 'item_user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Message' => array(
            'className' => 'Items.Message',
            'foreignKey' => 'message_content_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'CustomPricePerTypeItemUser' => array(
            'className' => 'Items.CustomPricePerTypeItemUser',
            'foreignKey' => 'item_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'BuyerSubmission' => array(
            'className' => 'Items.BuyerSubmission',
            'foreignKey' => 'item_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
    );
    public $hasOne = array(
        'ItemFeedback' => array(
            'className' => 'Items.ItemFeedback',
            'foreignKey' => 'item_user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->_permanentCacheAssociatedUsers = array(
            'owner_user_id',
            'user_id',
        );
        $this->_permanentCacheAssociations = array(
            'User',
            'Item',
            'Chart',
        );
        $this->validate = array();
        $conditions = array();
        $conditions['Message.is_sender'] = 0;
        $conditions['NOT']['Message.item_user_status_id'] = array(
            ConstItemUserStatus::SenderNotification,
            ConstItemUserStatus::HostReviewed,
            ConstItemUserStatus::BookingRequest,
        );
        //$this->aggregatingFields['message_count']['conditions'] = $conditions;
        $this->isFilterOptions = array(
            ConstItemUserStatus::PaymentPending => __l('Payment Pending') ,
            ConstItemUserStatus::WaitingforAcceptance => __l('Waiting for acceptance') ,
            ConstItemUserStatus::Confirmed => __l('Confirmed') ,
            ConstItemUserStatus::WaitingforReview => sprintf(__l('Waiting for % review') , Configure::read('item.alt_name_for_booker_singular_small')) ,
            ConstItemUserStatus::Completed => __l('Completed') ,
            ConstItemUserStatus::Canceled => __l('Canceled by') . ' ' . Configure::read('item.alt_name_for_booker_singular_small') ,
            ConstItemUserStatus::Rejected => __l('Rejected') ,
            ConstItemUserStatus::Expired => __l('Expired') ,
            ConstItemUserStatus::CanceledByAdmin => __l('Canceled by Admin') ,
        );
        $this->moreActions = array(
            ConstMoreAction::WaitingforAcceptance => __l('Waiting for acceptance') ,
            ConstMoreAction::InProgress => __l('In progress') ,
            ConstMoreAction::Completed => __l('Completed') ,
            ConstMoreAction::Canceled => __l('Canceled') ,
            ConstMoreAction::Rejected => __l('Rejected') ,
            ConstMoreAction::PaymentCleared => __l('Payment Cleared') ,
            ConstMoreAction::Delete => __l('Delete') ,
        );
    }
    function _isFromToValid()
    {
        $itemuser = $this->find('first', array(
            'conditions' => array(
                'ItemUser.id' => $this->data[$this->name]['order_id']
            ) ,
            'recursive' => -1
        ));
        if (!empty($itemuser)) {
            $fromto = $this->data[$this->name]['fromto']['year'] . '-' . $this->data[$this->name]['fromto']['month'] . '-' . $this->data[$this->name]['fromto']['day'];
            if ($itemuser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Confirmed) {
                if ($fromto >= $itemuser['ItemUser']['from']) {
                    return true;
                }
            }
            if ($itemuser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforReview) {
                if ($fromto >= $itemuser['ItemUser']['to']) {
                    return true;
                }
            }
        }
        return false;
    }
    function _isCheckGuest()
    {
        $item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.id' => $this->data[$this->name]['item_id']
            ) ,
            'recursive' => -1
        ));
        if (!empty($item)) {
            if ($item['Item']['accommodates'] == 0) {
                return true;
            } else if ($this->data[$this->name]['guests'] > $item['Item']['accommodates']) {
                return false;
            } else {
                return true;
            }
        }
        return false;
    }
    function _isFromDateAvailable()
    {
        $return = true;
        $itemusers = $this->find('all', array(
            'conditions' => array(
                'NOT' => array(
                    'ItemUser.item_user_status_id ' => array(
                        ConstItemUserStatus::WaitingforAcceptance,
                        ConstItemUserStatus::Rejected,
                        ConstItemUserStatus::Canceled,
                        ConstItemUserStatus::CanceledByAdmin,
                        ConstItemUserStatus::PaymentPending,
                        ConstItemUserStatus::Expired,
                        0,
                    ) ,
                ) ,
                'ItemUser.item_id' => $this->data[$this->name]['item_id']
            ) ,
            'recursive' => -1
        ));
        if (!empty($itemusers)) {
            foreach($itemusers as $itemuser) {
                if (strtotime($this->data[$this->name]['from']) >= strtotime($itemuser['ItemUser']['from']) && strtotime($this->data[$this->name]['from']) <= strtotime($itemuser['ItemUser']['to'])) {
                    $return = false;
                }
            }
        }
        return $return;
    }
    function _isToDateAvailable()
    {
        $return = true;
        $itemusers = $this->find('all', array(
            'conditions' => array(
                'NOT' => array(
                    'ItemUser.item_user_status_id ' => array(
                        ConstItemUserStatus::WaitingforAcceptance,
                        ConstItemUserStatus::Rejected,
                        ConstItemUserStatus::Canceled,
                        ConstItemUserStatus::CanceledByAdmin,
                        ConstItemUserStatus::PaymentPending,
                        ConstItemUserStatus::Expired,
                        0,
                    ) ,
                ) ,
                'ItemUser.item_id' => $this->data[$this->name]['item_id']
            ) ,
            'recursive' => -1
        ));
        if (!empty($itemusers)) {
            foreach($itemusers as $itemuser) {
                if (strtotime($this->data[$this->name]['to']) >= strtotime($itemuser['ItemUser']['from']) && strtotime($this->data[$this->name]['to']) <= strtotime($itemuser['ItemUser']['to'])) {
                    $return = false;
                }
            }
        }
        return $return;
    }
    function _isValidFromDate()
    {
        if (strtotime($this->data[$this->name]['from']) >= strtotime(date('Y-m-d')) && strtotime($this->data[$this->name]['from']) <= strtotime($this->data[$this->name]['to'])) {
            return true;
        } else {
            return false;
        }
    }
    function _isValidToDate()
    {
        if (strtotime($this->data[$this->name]['to']) >= strtotime($this->data[$this->name]['from'])) {
            return true;
        } else {
            return false;
        }
    }
    // After save to update sales and purchase related information after every status gets saved.
    function afterSave($created)
    {
        /* Quick Fix */
        if (!empty($this->data['ItemUser']['id'])) {
            $proprtyUser = $this->find('first', array(
                'conditions' => array(
                    'ItemUser.id' => $this->data['ItemUser']['id'],
                ) ,
                'fields' => array(
                    'ItemUser.user_id',
                ) ,
                'recursive' => -1,
            ));
            $payment_pending_count = $this->find('count', array(
                'conditions' => array(
                    'ItemUser.user_id' => $proprtyUser['ItemUser']['user_id'],
                    'ItemUser.item_user_status_id' => ConstItemUserStatus::PaymentPending,
                ) ,
                'recursive' => -1,
            ));
            $this->User->updateAll(array(
                'User.booking_payment_pending_count' => $payment_pending_count
            ) , array(
                'User.id' => $proprtyUser['ItemUser']['user_id']
            ));
        }
		return true;
    }
    // common function to get item details //
    function get_item($item_user_id)
    {
        $item = $this->find('first', array(
            'conditions' => array(
                'ItemUser.id' => $item_user_id
            ) ,
            'contain' => array(
                'Item' => array(
                    'fields' => array(
                        'Item.id',
                        'Item.user_id',
                    ) ,
                )
            ) ,
            'recursive' => 1
        ));
        return $item;
    }
    // common function to get item counts for various conditions passed //
    function item_count($conditions)
    {
        $item_user_count = $this->find('count', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));
        return $item_user_count;
    }
    function _getCalendarMontlyBooking($item_id, $month, $year)
    {
        $conditions = array();
        $conditions['ItemUser.item_id'] = $item_id;
        // from must be within the given month n year //
        $conditions['ItemUser.from <= '] = $year . '-' . $month . '-' . '31' . ' 00:00:00';
        $conditions['ItemUser.to >= '] = $year . '-' . $month . '-' . '01' . ' 00:00:00';
        // must be active status //
        $conditions['ItemUser.item_user_status_id'] = array(
            ConstItemUserStatus::Confirmed,
            ConstItemUserStatus::WaitingforReview,
        );
        $item_users = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'ItemUser.id',
                'ItemUser.from',
                'ItemUser.to',
                'ItemUser.price',
            ) ,
            'order' => array(
                'ItemUser.from' => 'ASC'
            ) ,
            'recursive' => -1
        ));
        return $item_users;
    }
    function _getCalendarWeekBooking($item_id, $from, $to)
    {
        $conditions = array();
        $conditions['ItemUser.item_id'] = $item_id;
        // from must be within the given month n year //
        $conditions['ItemUser.from <= '] = $to;
        $conditions['ItemUser.from >= '] = $from;
        // must be active status //
        $conditions['ItemUser.item_user_status_id'] = array(
            ConstItemUserStatus::Confirmed,
            ConstItemUserStatus::WaitingforReview,
        );
        $item_users = $this->find('count', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));
        return $item_users;
    }
    function getCustomPrice($from, $from_time, $to, $to_time, $item_id, $custom_price_per_night_id, $min_hours = 0)
    {
		$price = 0;
		$custom_price_per_night = $this->CustomPricePerNight->find('first', array(
			'conditions' => array(
				'CustomPricePerNight.id' => $custom_price_per_night_id,
				'CustomPricePerNight.item_id' => $item_id,
				'CustomPricePerNight.is_available' => 1,
			),
			'recursive' => -1,
		));
		$start_datetime = $from . ' ' . $from_time;
		$end_datetime = $to . ' ' . $to_time;
		$default_date_diff = $this->getDateDiff($start_datetime, $end_datetime);
		$default_date_diff['week'] = 0;
			$minute = $default_date_diff['minuts'];
			$second = $default_date_diff['second'];
			if ($minute > 0 || $second > 0) {
                //increase hour by 1 if minute>0
				$default_date_diff['hour'] = $default_date_diff['hour']+1;
                $default_date_diff['minuts'] = 0;
                $default_date_diff['second'] = 0;
			}
			$date_diff = $this->price_date_calcaultion($default_date_diff, $custom_price_per_night['CustomPricePerNight']);
			if ($date_diff['month'] > 0) {
				$price = $price + ($date_diff['month'] * $custom_price_per_night['CustomPricePerNight']['price_per_month']);
			}
			if ($date_diff['week'] > 0) {
				$price = $price + ($date_diff['week'] * $custom_price_per_night['CustomPricePerNight']['price_per_week']);
			}
			if ($date_diff['day'] > 0) {
				$price = $price + ($date_diff['day'] * $custom_price_per_night['CustomPricePerNight']['price_per_day']);
			}
			if ($date_diff['hour'] > 0) {
				if($date_diff['hour'] < $min_hours && $price == 0) {
					$price = $price + ($min_hours * $custom_price_per_night['CustomPricePerNight']['price_per_hour']);
				} else {
					$price = $price + ($date_diff['hour'] * $custom_price_per_night['CustomPricePerNight']['price_per_hour']);
				}
			}
        return $price;
    }
    public function getReceiverdata($foreign_id, $transaction_type, $payee_account)
    {
        $ItemUser = $this->find('first', array(
            'conditions' => array(
                'ItemUser.id' => $foreign_id
            ) ,
            'contain' => array(
                'User',
                'OwnerUser',
            ) ,
            'recursive' => 0,
        ));
        $return['receiverEmail'] = array(
            $payee_account
        );
        $amount = ($ItemUser['ItemUser']['price']+$ItemUser['ItemUser']['booker_service_amount']+$ItemUser['ItemUser']['additional_fee_amount']) - $ItemUser['ItemUser']['coupon_discount_amont'];
		$service_amount = !empty($ItemUser['ItemUser']['host_service_amount'])?$ItemUser['ItemUser']['host_service_amount']:$ItemUser['ItemUser']['booker_service_amount'];
		$return['amount'] = array(
            $amount,
			$service_amount
        );
        $return['fees_payer'] = 'buyer';
        if (Configure::read('item.payment_gateway_fee_id') == 'Site') {
            $return['fees_payer'] = 'merchant';
	}
        $return['sudopay_gateway_id'] = $ItemUser['ItemUser']['sudopay_gateway_id'];
		$return['sudopay_receiver_account_id'] = $ItemUser['OwnerUser']['sudopay_receiver_account_id'];
        $return['action'] = 'MarketplaceAuth';
        $return['buyer_email'] = $ItemUser['User']['email'];
        return $return;
    }
    public function updateStatus($order_id, $item_user_status_id, $payment_gateway_id = null, $data = null)
    {
		$contain = array(
			'Item' => array(
				'User',
			) ,
			'User',
			'CustomPricePerNight'
		);
		if(isPluginEnabled('Seats')){
			$contain[] = 'CustomPricePerTypesSeat';
		}
        $itemUser = $this->Item->ItemUser->find('first', array(
            'conditions' => array(
                'ItemUser.id' => $order_id
            ) ,
            'contain' => $contain,
            'recursive' => 2
        ));
        if (empty($itemUser)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->
        {
            'processStatus' . $item_user_status_id}($itemUser, $payment_gateway_id, $data);
            return true;
        }
        // WaitingForAcceptance //
        function processStatus4($itemUser, $payment_gateway_id, $data = null)
        {
            if (in_array($itemUser['ItemUser']['item_user_status_id'], array(
                ConstItemUserStatus::PaymentPending
            ))) {
                $this->User->Transaction->log($itemUser['ItemUser']['id'], 'Items.ItemUser', $itemUser['ItemUser']['payment_gateway_id'], ConstTransactionTypes::BookItem);
                $_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::WaitingforAcceptance;
                $_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
                $this->save($_data, false);
                if (isPluginEnabled('Coupons') && !empty($itemUser['ItemUser']['coupon_id'])) {
                    $this->updateCoupon($itemUser['ItemUser']['id']);
                }
				// update order status to waiting for acceptance
				$item_user_id = $itemUser['ItemUser']['id'];
				if(isPluginEnabled('Seats')){
					App::import('Model', 'Seats.CustomPricePerTypesSeat');
					$this->CustomPricePerTypesSeat = new CustomPricePerTypesSeat();
					$seats = $this->CustomPricePerTypesSeat->find('all', array(
						'conditions' => array(
							'CustomPricePerTypesSeat.item_user_id' => $item_user_id
						),
						'recursive' => -1
					));	
					$updateSeats = array('CustomPricePerTypesSeat' => array());
					foreach($seats as $seat){
						$temp = array();
						$temp['id'] = $seat['CustomPricePerTypesSeat']['id'];
						$temp['seat_status_id'] = ConstSeatStatus::WaitingForAcceptance;
						$updateSeats['CustomPricePerTypesSeat'][] = $temp;
					}
					if(!empty($updateSeats['CustomPricePerTypesSeat'])){
						$this->CustomPricePerTypesSeat->saveAll($updateSeats['CustomPricePerTypesSeat']);
					}
				}
				// end
                $this->_processStatusSendMail($itemUser, 'New Booking Message To Host', ConstItemUserStatus::WaitingforAcceptance, true);
                $this->_processStatusSendMail($itemUser, 'New Booking Message To Booker', ConstItemUserStatus::WaitingforAcceptance, false);
				
				$this->_itemStatusChangeMail($itemUser, $itemUser['ItemUser']['item_user_status_id'], ConstItemUserStatus::WaitingforAcceptance);
            }
            return true;
        }
        // Confirmed //
        function processStatus9($itemUser, $payment_gateway_id, $data = null)
        {
			if ($itemUser['Item']['user_id'] != $_SESSION['Auth']['User']['id'] && ($itemUser['ItemUser']['user_id'] != $_SESSION['Auth']['User']['id'] && $itemUser['Item']['is_auto_approve'] == 0)) {							
                throw new NotFoundException(__l('Invalid request'));
            }		
			// update order status to booked
			$item_user_id = $itemUser['ItemUser']['id'];
			if(isPluginEnabled('Seats')){
				App::import('Model', 'Seats.CustomPricePerTypesSeat');
				$this->CustomPricePerTypesSeat = new CustomPricePerTypesSeat();
				$seats = $this->CustomPricePerTypesSeat->find('all', array(
					'conditions' => array(
						'CustomPricePerTypesSeat.item_user_id' => $item_user_id
					),
					'recursive' => -1
				));	
				$updateSeats = array('CustomPricePerTypesSeat' => array());
				foreach($seats as $seat){
					$temp = array();
					$temp['id'] = $seat['CustomPricePerTypesSeat']['id'];
					$temp['seat_status_id'] = ConstSeatStatus::Booked;
					$updateSeats['CustomPricePerTypesSeat'][] = $temp;
				}
				if(!empty($updateSeats['CustomPricePerTypesSeat'])){
					$this->CustomPricePerTypesSeat->saveAll($updateSeats['CustomPricePerTypesSeat']);
				}
			}
			// end
            if ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance || $itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::PaymentPending || $itemUser['Item']['is_auto_approve'] == 1 ) {					
				if($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::PaymentPending || $itemUser['Item']['is_auto_approve'] == 1 ) {
					if (isPluginEnabled('Coupons') && !empty($itemUser['ItemUser']['coupon_id'])) {
						$this->updateCoupon($itemUser['ItemUser']['id']);
					}
				}
                $return['error'] = 0;
                $_data = array();
                if ((!empty($itemUser['Item']['is_tipping_point']) && $itemUser['CustomPricePerNight']['total_booked_count'] < $itemUser['Item']['min_number_of_ticket'])) {
					$return['error'] = 1;
                    $_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
                    $_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::Confirmed;
                    $_data['ItemUser']['accepted_date'] = date('Y-m-d H:i:s');
                    $this->save($_data, false);
					$this->_itemStatusChangeMail($itemUser, $itemUser['ItemUser']['item_user_status_id'], ConstItemUserStatus::Confirmed);
                    $this->updateTippingProcess($itemUser['ItemUser']['id']);
                } else {
					$return['error'] = 0;
	                $is_update_in_wallet = 1;
					if ($itemUser['ItemUser']['payment_gateway_id'] == ConstPaymentGateways::SudoPay) {
						$sudopayGatewayDetails = $this->_getSudopayGatewayDetails($itemUser['ItemUser']['sudopay_gateway_id']);
						if (!empty($sudopayGatewayDetails['SudopayPaymentGateway']['is_marketplace_supported'])) {
							App::import('Model', 'Sudopay.Sudopay');
							$this->Sudopay = new Sudopay();
							$return = $this->Sudopay->processPreapprovalPayment($itemUser['ItemUser']['id']);
							$is_update_in_wallet = 0;
						}
                    }
                }
                if (empty($return['error'])) {
                    $_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
                    $_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::Confirmed;
                    $_data['ItemUser']['accepted_date'] = date('Y-m-d H:i:s');
                    $_data['ItemUser']['is_payment_cleared'] = 1;
                    $this->save($_data, false);
					if (!empty($is_update_in_wallet)) {
						$this->User->updateAll(array(
							'User.available_wallet_amount' => 'User.available_wallet_amount + ' . ($itemUser['ItemUser']['original_price']-$itemUser['ItemUser']['host_service_amount']) ,
						) , array(
							'User.id' => $itemUser['Item']['user_id']
						));
						$this->User->Transaction->log($itemUser['ItemUser']['id'], 'Items.ItemUser', $itemUser['ItemUser']['payment_gateway_id'], ConstTransactionTypes::HostAmountCleared);
					}
                    $this->_processStatusSendMail($itemUser, 'Accepted Booking Message To Host', ConstItemUserStatus::Confirmed, true);
                    $this->_processStatusSendMail($itemUser, 'Accepted Booking Message To Booker', ConstItemUserStatus::Confirmed, false);
					$this->_itemStatusChangeMail($itemUser, $itemUser['ItemUser']['item_user_status_id'], ConstItemUserStatus::Confirmed);
					$this->updateBookingsCount($itemUser['ItemUser']['id']);
                }
            }
        }
        // Rejected //
        function processStatus5($itemUser, $payment_gateway_id, $data = null)
        {
            if ($itemUser['Item']['user_id'] != $_SESSION['Auth']['User']['id']) {
                throw new NotFoundException(__l('Invalid request'));
            }
			$return = $this->_refundProcess($itemUser);
			if (empty($return['error'])) {
				$_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
				$_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::Rejected;
				$this->save($_data, false);
				$this->User->Transaction->log($itemUser['ItemUser']['id'], 'Items.ItemUser', $itemUser['ItemUser']['payment_gateway_id'], ConstTransactionTypes::RefundForRejectedBooking);
				$this->_itemStatusChangeMail($itemUser, ConstItemUserStatus::WaitingforAcceptance, ConstItemUserStatus::Rejected);
			}
        }
        // Canceled //
        function processStatus6($itemUser, $payment_gateway_id, $data = null)
        {
			if (!empty($_SESSION['Auth']['User']['id']) && $itemUser['Item']['user_id'] == $_SESSION['Auth']['User']['id'] && $_SESSION['Auth']['User']['id'] != ConstUserIds::Admin) {
				throw new NotFoundException(__l('Invalid request'));
			}
			$return = $this->_refundProcess($itemUser);
			if (empty($return['error'])) {
				$_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
                $_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::Canceled;
                $this->save($_data, false);
				$this->User->Transaction->log($itemUser['ItemUser']['id'], 'Items.ItemUser', $itemUser['ItemUser']['payment_gateway_id'], ConstTransactionTypes::RefundForCanceledBooking);
				$this->_itemStatusChangeMail($itemUser, $itemUser['ItemUser']['item_user_status_id'], ConstItemUserStatus::Canceled);
			}
        }
        // WaitingforReview //
        function processStatus10($itemUser, $payment_gateway_id, $data = null)
        {
            if ((strtotime('now') -strtotime($itemUser['ItemUser']['to'])) > 0) {
                $_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
                $_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::WaitingforReview;
                $this->save($_data, false);
                if ($itemUser['ItemUser']['item_user_status_id'] != ConstItemUserStatus::WaitingforReview) {
                    $this->_itemStatusChangeMail($itemUser, $itemUser['ItemUser']['item_user_status_id'], ConstItemUserStatus::WaitingforReview);
                }
            }
        }
        // Completed //
        function processStatus13($itemUser, $payment_gateway_id, $data = null)
        {
            if (in_array($itemUser['ItemUser']['item_user_status_id'], array(
                ConstItemUserStatus::WaitingforReview
            ))) {
                $_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
                $_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::Completed;
                $this->save($_data, false);
                $this->_itemStatusChangeMail($itemUser, $itemUser['ItemUser']['item_user_status_id'], ConstItemUserStatus::Completed);
            }
            return true;
        }
        // Expired //
        function processStatus8($itemUser, $payment_gateway_id, $data = null)
        {
			$return = $this->_refundProcess($itemUser);
			if (empty($return['error'])) {
                $_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
                $_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::Expired;
                $this->save($_data, false);
                $this->User->Transaction->log($itemUser['ItemUser']['id'], 'Items.ItemUser', $itemUser['ItemUser']['payment_gateway_id'], ConstTransactionTypes::RefundForExpiredBooking);
                $this->_itemStatusChangeMail($itemUser, $itemUser['ItemUser']['item_user_status_id'], ConstItemUserStatus::Expired);
            }
        }
        // CanceledByAdmin //
        function processStatus7($itemUser, $payment_gateway_id, $data = null)
        {
			if (!empty($_SESSION['Auth']['User']['id']) && $_SESSION['Auth']['User']['id'] != ConstUserIds::Admin) {
				throw new NotFoundException(__l('Invalid request'));
			}
			$return = $this->_refundProcess($itemUser);
			if (empty($return['error'])) {
				$_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
                $_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::CanceledByAdmin;
                $this->save($_data, false);
				$this->User->Transaction->log($itemUser['ItemUser']['id'], 'Items.ItemUser', $itemUser['ItemUser']['payment_gateway_id'], ConstTransactionTypes::RefundForBookingCanceledByAdmin);
				$this->_itemStatusChangeMail($itemUser, $itemUser['ItemUser']['item_user_status_id'], ConstItemUserStatus::CanceledByAdmin);
			}
        }
        private function _refundProcess($itemUser)
        {
			$return['error'] = 1;
            if (!empty($itemUser['ItemUser']) && empty($itemUser['ItemUser']['is_payment_cleared'])) {
				$return['error'] = 0;
                $is_update_in_wallet = 1;
                if ($itemUser['ItemUser']['payment_gateway_id'] == ConstPaymentGateways::SudoPay) {
                    $sudopayGatewayDetails = $this->_getSudopayGatewayDetails($itemUser['ItemUser']['sudopay_gateway_id']);
                    if (!empty($sudopayGatewayDetails['SudopayPaymentGateway']['is_marketplace_supported'])) {
                        App::import('Model', 'Sudopay.Sudopay');
                        $this->Sudopay = new Sudopay();
                        $return = $this->Sudopay->cancelPreapprovalPayment($itemUser['ItemUser']['id']);
                        $is_update_in_wallet = 0;
                    }
                }
                if (empty($return['error'])) {
                    if (!empty($is_update_in_wallet)) {
						$refund_amount = ($itemUser['User']['available_wallet_amount'] + $itemUser['ItemUser']['original_price'] + $itemUser['ItemUser']['booker_service_amount'] + $itemUser['ItemUser']['additional_fee_amount']) - $itemUser['ItemUser']['coupon_discount_amont'];
                        $this->User->updateAll(array(
                            'User.available_wallet_amount' => $refund_amount
                        ) , array(
                            'User.id' => $itemUser['ItemUser']['user_id']
                        ));
                    }
					// update order status to available
					if(isPluginEnabled('Seats')){
						App::import('Model', 'Seats.CustomPricePerTypesSeat');
						$this->CustomPricePerTypesSeat = new CustomPricePerTypesSeat();
						$seats = $this->CustomPricePerTypesSeat->find('all', array(
							'conditions' => array(
								'CustomPricePerTypesSeat.item_user_id' => $itemUser['ItemUser']['id']
							),
							'recursive' => -1
						));	
						$updateSeats = array('CustomPricePerTypesSeat' => array());
						foreach($seats as $seat){
							$temp = array();
							$temp['id'] = $seat['CustomPricePerTypesSeat']['id'];
							$temp['seat_status_id'] = ConstSeatStatus::Available;
							$temp['item_user_id'] = null;
							$temp['custom_price_per_type_item_user_id'] = null;
							$temp['booking_start_time'] = null;
							$temp['blocked_user_id'] = null;
							$updateSeats['CustomPricePerTypesSeat'][] = $temp;
						}
						if(!empty($updateSeats['CustomPricePerTypesSeat'])){
							$this->CustomPricePerTypesSeat->saveAll($updateSeats['CustomPricePerTypesSeat']);
						}
					}
					// end					
                }
			}
			$this->updateBookingsCount($itemUser['ItemUser']['id']);
			return $return;
        }
        private function _processStatusSendMail($itemUser, $template, $to_status, $to_host)
        {
            $getHostrating = $this->getRatingCount($itemUser['Item']['user_id']);
            App::import('Model', 'EmailTemplate');
            $this->EmailTemplate = new EmailTemplate();
            $email_template = $this->EmailTemplate->selectTemplate($template);
            $emailFindReplace = array(
                '##USERNAME##' => ($to_host) ? $itemUser['Item']['User']['username'] : $itemUser['User']['username'],
                '##ITEM_NAME##' => "<a href=" . Router::url(array(
                    'controller' => 'items',
                    'action' => 'view',
                    $itemUser['Item']['slug'],
                ) , true) . ">" . $itemUser['Item']['title'] . "</a>",
                '##BOOKER_USERNAME##' => $itemUser['User']['username'],
                '##ACCEPT_URL##' => "<a href=" . Router::url(array(
                    'controller' => 'item_users',
                    'action' => 'update_order',
                    $itemUser['ItemUser']['id'],
                    __l('accept') ,
                ) , true) . ">" . __l('Accept your booking') . "</a>",
                '##REJECT_URL##' => "<a href=" . Router::url(array(
                    'controller' => 'item_users',
                    'action' => 'update_order',
                    $itemUser['ItemUser']['id'],
                    __l('reject') ,
                ) , true) . ">" . __l('Reject your booking') . "</a>",
                '##CANCEL_URL##' => "<a href=" . Router::url(array(
                    'controller' => 'item_users',
                    'action' => 'update_order',
                    $itemUser['ItemUser']['id'],
                    __l('cancel') ,
                ) , true) . ">" . __l('Cancel your booking') . "</a>",
                '##ORDER_NO##' => $itemUser['ItemUser']['id'],
                '##ORDERNO##' => $itemUser['ItemUser']['id'],
                '##ITEM_FULL_NAME##' => "<a href=" . Router::url(array(
                    'controller' => 'items',
                    'action' => 'view',
                    $itemUser['Item']['slug']
                ) , true) . ">" . $itemUser['Item']['title'] . "</a>",
                '##ITEM_DESCRIPTION##' => $itemUser['Item']['description'],
                '##HOST_NAME##' => $itemUser['Item']['User']['username'],
                '##HOST_RATING##' => (!empty($getHostrating) && is_numeric($getHostrating)) ? $getHostrating . '% ' . __l('Positive') : __l('Not Rated Yet') ,
                '##HOST_CONTACT_LINK##' => "<a href=" . Router::url(array(
                    'controller' => 'messages',
                    'action' => 'compose',
                    'type' => 'contact',
                    'to' => $itemUser['Item']['User']['username'],
                    'slug' => $itemUser['Item']['slug'],
                ) , true) . ">" . 'contact host' . "</a>",
                '##FROM_DATE##' => $itemUser['ItemUser']['from'],
                '##TO_DATE##' => $itemUser['ItemUser']['to'] ,
                '##ITEM_AUTO_EXPIRE_DATE##' => Configure::read('item.auto_expire') ,
                '##SITE_NAME##' => Configure::read('site.name') ,
                '##SITE_URL##' => Router::url('/', true) ,
                '##UNSUBSCRIBE_LINK##' => Router::url(array(
                    'controller' => 'user_notifications',
                    'action' => 'edit',
                    'admin' => false
                ) , true) ,
                '##CONTACT_URL##' => Router::url(array(
                    'controller' => 'contacts',
                    'action' => 'add',
                    'admin' => false
                ) , true) ,
                '##FROM_EMAIL##' => ($email_template['from'] == '##FROM_EMAIL##') ? Configure::read('site.from_email') : $email_template['from'],
            );
            $message = strtr($email_template['email_text_content'], $emailFindReplace);
            $subject = strtr($email_template['subject'], $emailFindReplace);
            if ($to_host) {
                $this->Item->ItemUser->_sendEmail($email_template, $emailFindReplace, $itemUser['Item']['User']['email']);
            } else {
                $this->Item->ItemUser->_sendEmail($email_template, $emailFindReplace, $itemUser['User']['email']);
                //$this->Item->ItemUser->User->Message->sendNotifications($itemUser['ItemUser']['user_id'], $subject, $message, $itemUser['ItemUser']['id'], $is_review = 0, $itemUser['Item']['id'], $to_status);
            }
        }
        private function _itemStatusChangeMail($itemUser, $from_status, $to_status)
        {
            App::import('Model', 'EmailTemplate');
            $this->EmailTemplate = new EmailTemplate();
            $item_user_statuses = $this->ItemUserStatus->find('list', array(
                'recursive' => -1
            ));
			$seats = '';
			if(isPluginEnabled('Seats') && !empty($itemUser['CustomPricePerTypesSeat'])){
				$inc = 1;
				foreach($itemUser['CustomPricePerTypesSeat'] as $key => $customPricePerTypesSeat) {
					if($key > 0){
						$seats.= ', '.$customPricePerTypesSeat['name'];
					} else {
						$seats = $customPricePerTypesSeat['name'];
					}
				
					$inc++;
				}
			}
            $emailTemplate = $this->EmailTemplate->selectTemplate('Item User Change Status Alert');
            $emailFindAndReplace = array(
                '##PREVIOUS_STATUS##' => (!empty($itemUser['Item']['is_free']) && $itemUser['Item']['is_free'] && $from_status == ConstItemUserStatus::PaymentPending)?'New Booking':$item_user_statuses[$from_status],
                '##CURRENT_STATUS##' => $item_user_statuses[$to_status],
                '##ITEM##' => $itemUser['Item']['title'],
                '##ITEM_NAME##' => $itemUser['Item']['title'],
				'##ITEM_URL##' => Router::url(array(
                    'controller' => 'items',
                    'action' => 'view',
                    $itemUser['Item']['slug'],
                ) , true),
                '##SITE_NAME##' => Configure::read('site.name') ,
                '##SITE_URL##' => Router::url('/', true) ,
                '##UNSUBSCRIBE_LINK##' => Router::url(array(
                    'controller' => 'user_notifications',
                    'action' => 'edit',
                    'admin' => false
                ) , true) ,
                '##FROM_EMAIL##' => ($emailTemplate['from'] == '##FROM_EMAIL##') ? Configure::read('site.from_email') : $emailTemplate['from'],
                '##CONTACT_URL##' => Router::url(array(
                    'controller' => 'contacts',
                    'action' => 'add',
                    'admin' => false
                ) , true) ,
				'##SEATS##' => __l('Seats'). ': '. $seats ,
            );
            $message = strtr($emailTemplate['email_text_content'], $emailFindAndReplace);
            $subject = strtr($emailTemplate['subject'], $emailFindAndReplace);
            $this->Item->ItemUser->_sendEmail($emailTemplate, $emailFindAndReplace, $itemUser['User']['email']);
            $this->Item->ItemUser->User->Message->sendNotifications($itemUser['User']['id'], $subject, $message, $itemUser['ItemUser']['id'], $is_review = 0, $itemUser['Item']['id'], $to_status);
            $this->Item->ItemUser->_sendEmail($emailTemplate, $emailFindAndReplace, $itemUser['Item']['User']['email']);
        }
        private function _getSudopayGatewayDetails($sudopay_gateway_id)
        {
            App::import('Model', 'Sudopay.SudopayPaymentGateway');
            $this->SudopayPaymentGateway = new SudopayPaymentGateway();
            return $this->SudopayPaymentGateway->find('first', array(
                'conditions' => array(
                    'SudopayPaymentGateway.sudopay_gateway_id' => $sudopay_gateway_id
                ) ,
                'recursive' => -1
            ));
        }
        function updateCoupon($item_user_id)
        {
            $itemUser = $this->Item->ItemUser->find('first', array(
                'conditions' => array(
                    'ItemUser.id' => $item_user_id
                ) ,
                'recursive' => -1
            ));
            if (!empty($itemUser)) {
                App::import('Model', 'Coupons.Coupon');
                $this->Coupon = new Coupon();
                $coupon = $this->Coupon->find('first', array(
                    'conditions' => array(
                        'Coupon.id' => $itemUser['ItemUser']['coupon_id'],
                    ) ,
                    'recursive' => -1
                ));
                $_data = array();
                $_data['Coupon']['id'] = $coupon['Coupon']['id'];
                $number_of_quantity_used = $coupon['Coupon']['number_of_quantity_used']+1;
                $_data['Coupon']['number_of_quantity_used'] = $number_of_quantity_used;
                if (!empty($coupon['Coupon']['number_of_quantity']) && $number_of_quantity_used == $coupon['Coupon']['number_of_quantity']) {
                    $_data['Coupon']['is_active'] = 0;
                }
                $this->Coupon->save($_data);
            }
        }
        function updateTippingProcess($order_id)
        {
            $this->updateBookingsCount($order_id);
			$item_user = $this->Item->ItemUser->find('first', array(
				'conditions' => array(
					'ItemUser.id' => $order_id
				) ,
				'contain' => array(
					'Item' => array(
						'User',
					) ,
					'User',
					'CustomPricePerNight',
				) ,
				'recursive' => 2
			));
            if ((!empty($item_user['Item']['is_tipping_point']) && $item_user['CustomPricePerNight']['total_booked_count'] == $item_user['Item']['min_number_of_ticket'])) {
                $itemUsers = $this->Item->ItemUser->find('all', array(
                    'conditions' => array(
                        'ItemUser.item_id' => $item_id,
                        'ItemUser.is_payment_cleared' => 0,
                        'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed
                    ) ,
					'contain' => array(
						'Item' => array(
							'User',
						) ,
						'User',
						'CustomPricePerNight',
					) ,
					'recursive' => 2
                ));
                if (!empty($itemUsers)) {
                    App::import('Model', 'Sudopay.Sudopay');
                    $this->Sudopay = new Sudopay();
                    foreach($itemUsers As $itemUser) {
                        $return['error'] = 0;
						$is_update_in_wallet = 1;
	                    if ($itemUser['ItemUser']['payment_gateway_id'] == ConstPaymentGateways::SudoPay) {
							$sudopayGatewayDetails = $this->_getSudopayGatewayDetails($itemUser['ItemUser']['sudopay_gateway_id']);
							if (!empty($sudopayGatewayDetails['SudopayPaymentGateway']['is_marketplace_supported'])) {
								App::import('Model', 'Sudopay.Sudopay');
								$this->Sudopay = new Sudopay();
				                $return = $this->Sudopay->processPreapprovalPayment($itemUser['ItemUser']['id']);
								$is_update_in_wallet = 0;
							}
                        }
                        if (empty($return['error'])) {
                            $_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
                            $_data['ItemUser']['is_payment_cleared'] = 1;
                            $this->save($_data, false);
							if (!empty($is_update_in_wallet)) {
								$this->User->updateAll(array(
									'User.available_wallet_amount' => 'User.available_wallet_amount + ' . ($itemUser['ItemUser']['price']-$itemUser['ItemUser']['host_service_amount']) ,
								) , array(
									'User.id' => $item_user['Item']['user_id']
								));
								$this->User->Transaction->log($itemUser['ItemUser']['id'], 'Items.ItemUser', $itemUser['ItemUser']['payment_gateway_id'], ConstTransactionTypes::HostAmountCleared);
							}
							$this->_processStatusSendMail($itemUser, 'Accepted Booking Message To Host', ConstItemUserStatus::Confirmed, true);
							$this->_processStatusSendMail($itemUser, 'Accepted Booking Message To Booker', ConstItemUserStatus::Confirmed, false);
                        }
                    }
                }
            }
        }
        function updateBookingsCount($item_user_id)
        {
			$item_user = $this->Item->ItemUser->find('first', array(
                'conditions' => array(
                    'ItemUser.id' => $item_user_id,
                    'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed,
                ) ,
                'contain' => array(
                    'CustomPricePerNight' => array(
                        'CustomPricePerType',
                    ) ,
                ) ,
                'recursive' => 3
            ));
            if (!empty($item_user)) {
                // coupons
                if (!empty($item_user['ItemUser']['coupon_id'])) {
                    App::import('Model', 'Coupons.Coupon');
                    $this->Coupon = new Coupon();
                    $coupon = $this->Coupon->find('first', array(
                        'conditions' => array(
                            'Coupon.id' => $item_user['ItemUser']['coupon_id'],
                            'Coupon.is_active' => 1,
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($coupon)) {
                        $cupon_count = $this->Item->ItemUser->find('count', array(
                            'conditions' => array(
                                'ItemUser.coupon_id' => $item_user['ItemUser']['coupon_id'],
                                'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed,
                            ) ,
                            'recursive' => -1,
                        ));
                        $_data = array();
                        $_data['Coupon']['id'] = $coupon['Coupon']['id'];
                        $_data['Coupon']['number_of_quantity_used'] = $cupon_count;
                        if (!empty($coupon['Coupon']['number_of_quantity']) && $coupon['Coupon']['number_of_quantity'] == $cupon_count) {
                            $_data['Coupon']['is_active'] = 0;
                        }
                        $this->Coupon->save($_data);
                    }
                }
				if(!empty($item_user['CustomPricePerNight'])) {				
					$total_night_booking_count = 0;
                    if(!empty($item_user['CustomPricePerNight']['CustomPricePerType'])) {
						foreach($item_user['CustomPricePerNight']['CustomPricePerType'] As $custom_price_per_type) {						
							$conditions['or'] = array(
								array(
									'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed,
								) ,
								array(
									'ItemUser.item_user_status_id' => ConstItemUserStatus::WaitingforReview,
								) ,
								array(
									'ItemUser.item_user_status_id' => ConstItemUserStatus::BookerReviewed,
								),
								array(
									'ItemUser.item_user_status_id' => ConstItemUserStatus::HostReviewed,
								),
								array(
									'ItemUser.item_user_status_id' => ConstItemUserStatus::Completed,
								)
							);		
							$custom_price_per_type_count = $this->Item->ItemUser->CustomPricePerTypeItemUser->find('all', array(
								'conditions' => array(
									'CustomPricePerTypeItemUser.custom_price_per_type_id' => $custom_price_per_type['id'],
									$conditions	
								) ,		
									'contain' => array(
									'ItemUser'
									),
								'fields' => array(							
									'CustomPricePerTypeItemUser.item_user_id',
									'CustomPricePerTypeItemUser.number_of_quantity',
									'ItemUser.item_user_status_id'
								) ,
								'recursive' => 1,
							));
							$total_quantity = 0;
							foreach($custom_price_per_type_count as $item) {
								
								$total_quantity += $item['CustomPricePerTypeItemUser']['number_of_quantity'];
							}							
							$total_night_booking_count = $total_night_booking_count + $total_quantity;
							$_data = array();
							$_data['CustomPricePerType']['id'] = $custom_price_per_type['id'];
							$_data['CustomPricePerType']['booked_quantity'] = $total_quantity;
							$this->Item->ItemUser->CustomPricePerTypeItemUser->CustomPricePerType->save($_data);
						}
					} else {
						$custom_price_per_night_count = $this->Item->ItemUser->find('count', array(
							'conditions' => array(
								'ItemUser.custom_price_per_night_id' => $item_user['CustomPricePerNight']['id'],
								'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed,
								'ItemUser.is_payment_cleared' => 1,
							) ,
							'recursive' => -1,
						));
						$total_night_booking_count = $custom_price_per_night_count;
					}
					$_data = array();
                    $_data['CustomPricePerNight']['id'] = $item_user['CustomPricePerNight']['id'];
                    $_data['CustomPricePerNight']['total_booked_count'] = $total_night_booking_count;
                    if (!empty($item_user['CustomPricePerNight']['total_available_count']) && $item_user['CustomPricePerNight']['total_available_count'] == $total_night_booking_count) {
                        $_data['CustomPricePerNight']['is_available'] = 0;
                    }
                    $this->Item->ItemUser->CustomPricePerNight->save($_data);
				}
            }
        }
		public function checkAvalibities($item_id, $from, $to)
		{
			$day_of_the_week = array(
				'M' => 1,
				'Tu' => 2,
				'W' => 3,
				'Th' => 4,
				'F' => 5,
				'Sa' => 6,
				'Su' => 7
			);
			$total_days = (strtotime($to) - strtotime($from)) /(60*60*24);
			$custom_price_per_nights = $this->Item->CustomPricePerNight->find('all', array(
                'conditions' => array(
                    'CustomPricePerNight.item_id' => $item_id,
                    'CustomPricePerNight.is_available' => 1,
                    'CustomPricePerNight.is_custom' => 0,
                ) ,
                'recursive' => -1
            ));
			$repeat_days = array();
			foreach($custom_price_per_nights As $custom_price_per_night) {
				$repeat_days_arr = explode(',', $custom_price_per_night['CustomPricePerNight']['repeat_days']);
                foreach($repeat_days_arr as $repeat_day) {
                    $repeat_days[] = $day_of_the_week[$repeat_day];
                }
			}
			$not_avaliable = array();
			for ($i = 0; $i <= $total_days; $i++) {
				$day = date('Y-m-d', strtotime($from . "+" . $i . " day"));
				$day_of_day = date('N', strtotime($day));
				if (!in_array($day_of_day, $repeat_days)) {
					$not_avaliable[] = $day;
				}
			}
			if(empty($not_avaliable)) {
				return true;
			}
			return false;
		}
		function price_date_calcaultion($date_diff, $price){
			$date_diff['week'] = 0;
			if($date_diff['year'] > 0){
				if(!empty($price['price_per_month'])){
					$date_diff['month'] = $date_diff['month'] + $date_diff['year'] * 12;
				}else if(!empty($price['price_per_week'])){
					$date_diff['week'] = $date_diff['week'] + floor(($date_diff['year'] * 365) / 7);
					$date_diff['day'] = $date_diff['day'] + (($date_diff['year'] * 365) % 7);
				}else if(!empty($price['price_per_day'])){
					$date_diff['day'] = $date_diff['day'] + $date_diff['year'] * 365;
				}else if(!empty($price['price_per_hour'])){
					$date_diff['hour'] = $date_diff['hour'] + ($date_diff['year'] * 365 * 24);
				}
				unset($date_diff['year']);
			}
			if($date_diff['month'] > 0){
				if(empty($price['price_per_month'])){
					if(!empty($price['price_per_week'])){
						$week = floor(($date_diff['month'] * 30) / 7) ;
						$day = ($date_diff['month'] * 30) % 7 ;
						$date_diff['week'] = $date_diff['week'] + $week;
						$date_diff['day'] = $date_diff['day'] + $day;
						$date_diff['month'] = 0;
					}else if(!empty($price['price_per_day'])){
						$date_diff['day'] = $date_diff['day'] + ($date_diff['month'] * 30);
						$date_diff['month'] = 0;
					}else if(!empty($price['price_per_hour'])){
						$date_diff['hour'] = $date_diff['hour'] + (($date_diff['month'] * 30) * 24);
						$date_diff['month'] = 0;
					}
				}
			}
			if( ($date_diff['day'] + floor($date_diff['hour'] / 24)) >= 7 ){
				$date_diff['week'] = $date_diff['week'] + floor(($date_diff['day'] + floor($date_diff['hour'] / 24)) / 7 );
				$date_diff['day'] = ($date_diff['day'] + floor($date_diff['hour'] / 24)) % 7;
				$date_diff['hour'] = $date_diff['hour'] % 24;
			}
			if($date_diff['week'] > 0){
				if(empty($price['price_per_week'])){
					if(!empty($price['price_per_day'])){
						$date_diff['day'] = $date_diff['day'] + ($date_diff['week'] * 7);
						$date_diff['week'] = 0;
					}else if(!empty($price['price_per_hour'])){
						$date_diff['hour'] = $date_diff['hour'] + (($date_diff['week'] * 7) * 24);
						$date_diff['week'] = 0;
					}else if(!empty($price['price_per_month'])){
						$day = $date_diff['week'] * 7;
						$date_diff['month'] = $date_diff['month'] + floor($day / 30);
						$date_diff['day'] = $date_diff['day'] + ($day % 30);
						$date_diff['week'] = 0;
					}
				}
			}
			if($date_diff['day'] > 0){
				if(empty($price['price_per_day'])){
					if(!empty($price['price_per_hour'])){
						$date_diff['hour'] = $date_diff['hour'] + $date_diff['day'] * 24;
						if(floor($date_diff['hour']/(24*7)) > 0 && !empty($price['price_per_week'])){				
							$date_diff['week'] = $date_diff['week'] + floor($date_diff['hour']/(24*7));
							$date_diff['hour'] = $date_diff['hour'] - (floor($date_diff['hour']/(24*7)) * 7) ;
						}
						$date_diff['day'] = 0;
					} else if(!empty($price['price_per_month']) && !empty($price['price_per_week'])){
						$day = $date_diff['day'] + $date_diff['week'] * 7;
						if($day > 30){
							$date_diff['month'] = $date_diff['month'] + floor($day / 30);
							$balance_day = $day % 30;
							if($balance_day > 7){
								$date_diff['week'] = floor($balance_day / 7) ;
								$date_diff['week'] = ($balance_day % 7) > 0 ?  $date_diff['week'] + 1 : $date_diff['week'];
							}
						}else{
							$date_diff['week'] = $date_diff['week'] + floor($day / 7) ;
							$date_diff['hour'] = $date_diff['hour'] + ($day % 7) * 24;
						}
						$date_diff['day'] = 0;
					}else if(!empty($price['price_per_month']) && empty($price['price_per_week'])){
						if($date_diff['day'] > 30){
							$date_diff['month'] = $date_diff['month'] + floor($date_diff['day'] / 30);
							$date_diff['month'] = ($date_diff['day'] % 30) > 0 ?  $date_diff['month'] + 1 : $date_diff['month'];
							$date_diff['hour'] = ($date_diff['day'] % 30) * 24;
						}else if($date_diff['day']  > 0){
							$date_diff['hour'] =$date_diff['day'] * 24;
						}			
						$date_diff['day'] = 0;
					}else if(empty($price['price_per_month']) && !empty($price['price_per_week'])){
						if($date_diff['day'] > 7){
							$date_diff['week'] = $date_diff['week'] + floor($date_diff['day'] / 7);
							if(empty($date_diff['hour'])){
								$date_diff['week'] = ($date_diff['day'] % 7) > 0 ?  $date_diff['week'] + 1 : $date_diff['week'];
							}else{
								$date_diff['hour'] = $date_diff['hour'] + ($date_diff['day'] - (floor($date_diff['day'] / 7) * 7)) * 24 ; 
							}
						}else if($date_diff['day'] > 0){
							if(empty($date_diff['hour'])){
								$date_diff['week'] = $date_diff['week'] + 1;
							}else{
								$date_diff['hour'] = $date_diff['hour'] + $date_diff['day'] * 24;
							}
						}			
						$date_diff['day'] = 0;
					}
				}
			}
			if($date_diff['hour'] > 0){
				if(empty($price['price_per_hour'])){
					$day = floor($date_diff['hour'] / 24);
					$day = ($date_diff['hour'] % 24 > 0)? $day + 1 : $day ;
					if(!empty($price['price_per_day'])){
						if(!empty($price['price_per_month']) && (($day + $date_diff['day'] + ($date_diff['week'] * 7)) >= 30)){
							$date_diff['month'] = $date_diff['month'] +  floor(($day + $date_diff['day'] + ($date_diff['week'] * 7)) / 30) ;
							$balance_day = (($day + $date_diff['day'] + ($date_diff['week'] * 7)) % 30) ;
							if(($balance_day > 7) && !empty($price['price_per_week'])){
								$date_diff['week'] =  floor($balance_day / 7) ;	
								$date_diff['day'] =  ($balance_day % 7);
							}else{
								$date_diff['week'] = 0;
								$date_diff['day'] =  $balance_day;
							}
						}else if(!empty($price['price_per_week']) && (($day + $date_diff['day'] ) >= 7)){
							$date_diff['week'] =  $date_diff['week'] + floor(($day + $date_diff['day']) / 7) ;	
							$date_diff['day'] =   (($day + $date_diff['day']) % 7);
						}else{
							$date_diff['day'] = $day + $date_diff['day'] ;
						}
						$date_diff['hour'] = 0;
					}else if(!empty($price['price_per_week'])){
						if(!empty($price['price_per_month']) && (($day + $date_diff['day'] + ($date_diff['week'] * 7)) >= 30)){
							$date_diff['month'] = $date_diff['month'] +  floor(($day + $date_diff['day'] + ($date_diff['week'] * 7)) / 30) ;
							$balance_day = (($day + $date_diff['day'] + ($date_diff['week'] * 7)) % 30) ;
							if(($balance_day > 7)){
								$date_diff['week'] =  floor($balance_day / 7) ;	
								$date_diff['week'] =  (($balance_day % 7) > 0) ? $date_diff['week'] + 1 : $date_diff['week'];
							}else{
								$date_diff['week'] = 1;
							}
						}else if((($day + $date_diff['day'] ) >= 7)){
							$date_diff['week'] = $date_diff['week'] + floor(($day + $date_diff['day']) / 7) ;	
							$date_diff['week'] = ((($day + $date_diff['day']) % 7) > 0)? $date_diff['week'] + 1 : $date_diff['week'];
						}else if((($day + $date_diff['day'] ) > 0)){
							$date_diff['week'] =  $date_diff['week'] + 1 ;	
							$date_diff['day'] =   0;			
						}
						$date_diff['hour'] = 0;
					}else if(!empty($price['price_per_month'])){
						if((($day + $date_diff['day'] + ($date_diff['week'] * 7)) >= 30)){
							$date_diff['month'] = $date_diff['month'] +  floor(($day + $date_diff['day'] + ($date_diff['week'] * 7)) / 30) ;
							$date_diff['month'] = ((($day + $date_diff['day'] + ($date_diff['week'] * 7)) % 30) > 0) ? $date_diff['month'] + 1 : $date_diff['month'] ;
						}else if((($day + $date_diff['day'] + ($date_diff['week'] * 7)) >= 0)){
							$date_diff['month'] =  $date_diff['month'] + 1 ;	
						}
						$date_diff['day'] = 0;
						$date_diff['week'] = 0;
						$date_diff['hour'] = 0;
					}
				}
			}	
			return $date_diff;
		}

    }
?>