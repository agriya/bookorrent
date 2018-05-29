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
class User extends AppModel
{
    public $name = 'User';
    public $displayField = 'username';
    public $belongsTo = array(
        'Role' => array(
            'className' => 'Role',
            'foreignKey' => 'role_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
       'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
		'LastLoginIp' => array(
            'className' => 'Ip',
            'foreignKey' => 'last_login_ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
       'ReferredByUser' => array(
            'className' => 'User',
            'foreignKey' => 'referred_by_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        )
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $hasMany = array(		
        'UserFacebookFriend' => array(
            'className' => 'UserFacebookFriend',
            'foreignKey' => 'user_id',
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
        'UserComment' => array(
            'className' => 'UserComment',
            'foreignKey' => 'user_id',
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
        'CkSession' => array(
            'className' => 'CkSession',
            'foreignKey' => 'user_id',
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
        'UserOpenid' => array(
            'className' => 'UserOpenid',
            'foreignKey' => 'user_id',
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
       'UserLogin' => array(
            'className' => 'UserLogin',
            'foreignKey' => 'user_id',
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
        'UserView' => array(
            'className' => 'UserView',
            'foreignKey' => 'user_id',
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
        'Transaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    public $hasOne = array(
        'UserProfile' => array(
            'className' => 'UserProfile',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'UserAvatar' => array(
            'className' => 'UserAvatar',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'UserAvatar.class' => 'UserAvatar',
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'CkSession' => array(
            'className' => 'CkSession',
            'foreignKey' => 'user_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
		$db = ConnectionManager::getDataSource($this->useDbConfig);
		if($db->config['datasource'] == 'Database/Mysql') {
			$this->aggregatingFields = array(
				'item_pending_approval_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.Item',
					'function' => 'COUNT(Item.user_id)',
					'conditions' => array(
						'Item.is_approved' => 0
					)
				) ,
				'item_inactive_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.Item',
					'function' => 'COUNT(Item.user_id)',
					'conditions' => array(
						'Item.is_active' => 0
					)
				) ,
				'host_expired_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::Expired,
					)
				) ,
				'host_canceled_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Canceled,
							ConstItemUserStatus::CanceledByAdmin,
						)
					)
				) ,
				'host_rejected_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::Rejected,
					)
				) ,
				'host_completed_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::Completed,
					)
				) ,
				'host_review_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::WaitingforReview,
							ConstItemUserStatus::Completed,
						) ,
						'ItemUser.is_host_reviewed' => 0,
					)
				) ,
				'host_confirmed_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed,
					)
				) ,
				'host_waiting_for_acceptance_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::WaitingforAcceptance,
					)
				) ,
				'host_payment_cleared_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.is_payment_cleared' => 1,
					)
				) ,
				'host_booking_request_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.is_booking_request' => 1,
						'ItemUser.user_id >' => 0,
						'ItemUser.item_user_status_id' => ConstItemUserStatus::BookingRequest,
					)
				) ,
				'host_total_booked_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::WaitingforAcceptance,
							ConstItemUserStatus::Confirmed,
							ConstItemUserStatus::WaitingforReview,
							ConstItemUserStatus::Completed,
						)
					)
				) ,
				'host_total_pipeline_amount' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(' . $db->startQuote . 'ItemUser' . $db->endQuote . '.' . $db->startQuote . 'price' . $db->endQuote . ' - ' . $db->startQuote . 'ItemUser' . $db->endQuote . '.' . $db->startQuote . 'host_service_amount' . $db->endQuote . ')',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Confirmed,
							ConstItemUserStatus::WaitingforReview,
							ConstItemUserStatus::Completed
						) ,
						'ItemUser.is_payment_cleared' => 0,
					)
				) ,
				'host_total_lost_booked_count' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.owner_user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Rejected,
							ConstItemUserStatus::Canceled,
							ConstItemUserStatus::Expired,
							ConstItemUserStatus::CanceledByAdmin
						)
					)
				) ,
				'host_total_lost_amount' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(' . $db->startQuote . 'ItemUser' . $db->endQuote . '.' . $db->startQuote . 'price' . $db->endQuote . ' - ' . $db->startQuote . 'ItemUser' . $db->endQuote . '.' . $db->startQuote . 'host_service_amount' . $db->endQuote . ')',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Rejected,
							ConstItemUserStatus::Canceled,
							ConstItemUserStatus::Expired,
							ConstItemUserStatus::CanceledByAdmin
						)
					)
				) ,
				'host_total_earned_amount' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(' . $db->startQuote . 'ItemUser' . $db->endQuote . '.' . $db->startQuote . 'price' . $db->endQuote . ' - ' . $db->startQuote . 'ItemUser' . $db->endQuote . '.' . $db->startQuote . 'host_service_amount' . $db->endQuote . ')',
					'conditions' => array(
						'ItemUser.is_payment_cleared' => 1
					)
				) ,
				'host_total_site_revenue' => array(
					'mode' => 'real',
					'key' => 'owner_user_id',
					'foreignKey' => 'owner_user_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(ItemUser.host_service_amount)',
					'conditions' => array(
						'ItemUser.is_payment_cleared' => 1
					)
				) ,
				'booker_booking_request_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.is_booking_request' => 1,
						'ItemUser.user_id >' => 0,
						'ItemUser.item_user_status_id' => ConstItemUserStatus::BookingRequest,
					)
				) ,
				'booking_expired_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::Expired,
					)
				) ,
				'booking_canceled_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Canceled,
							ConstItemUserStatus::CanceledByAdmin,
						)
					)
				) ,
				'booking_rejected_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::Rejected,
					)
				) ,
				'booking_payment_pending_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::PaymentPending,
					)
				) ,
				'booking_payment_cleared_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed,
					)
				) ,
				'booking_completed_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::Completed,
						// @todo "Auto review" add condition CompletedAndClosedByAdmin

					)
				) ,
				'booking_review_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::WaitingforReview,
					)
				) ,
				'booking_confirmed_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed,
					)
				) ,
				'booker_positive_feedback_count' => array(
					'mode' => 'real',
					'key' => 'booker_user_id',
					'foreignKey' => 'booker_user_id',
					'model' => 'Items.ItemUserFeedback',
					'function' => 'COUNT(ItemUserFeedback.booker_user_id)',
					'conditions' => array(
						'ItemUserFeedback.is_satisfied' => 1,
					)
				) ,
				'booker_item_user_count' => array(
					'mode' => 'real',
					'key' => 'booker_user_id',
					'foreignKey' => 'booker_user_id',
					'model' => 'Items.ItemUserFeedback',
					'function' => 'COUNT(ItemUserFeedback.booker_user_id)',
				) ,
				'booking_waiting_for_acceptance_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => ConstItemUserStatus::WaitingforAcceptance,
					)
				) ,
				'booking_total_booked_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::WaitingforAcceptance,
							ConstItemUserStatus::Confirmed,
							ConstItemUserStatus::WaitingforReview,
							ConstItemUserStatus::Completed,
						)
					)
				) ,
				'booking_total_booked_amount' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(ItemUser.price)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::WaitingforAcceptance,
							ConstItemUserStatus::Confirmed,
							ConstItemUserStatus::WaitingforReview,
							ConstItemUserStatus::Completed,
						)
					)
				) ,
				'booking_total_lost_booked_count' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.user_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Rejected,
							ConstItemUserStatus::Canceled,
							ConstItemUserStatus::Expired,
							ConstItemUserStatus::CanceledByAdmin
						)
					)
				) ,
				'booking_total_site_revenue' => array(
					'mode' => 'real',
					'key' => 'user_id',
					'foreignKey' => 'user_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(ItemUser.booker_service_amount)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Confirmed,
							ConstItemUserStatus::WaitingforReview,
							ConstItemUserStatus::Completed,
							ConstItemUserStatus::Canceled,
							ConstItemUserStatus::CanceledByAdmin
						)
					)
				) ,
			);
		}
		$this->_memcacheModels = array(
			'Item',
			'ItemUser',
			'Request',
			'Image'
		);
		$this->_permanentCacheAssociations = array(
			'Chart',
			'UserProfile',
            'SocialMarketing',
            'Transaction',
            'Wallet',
            'AffiliateRequest',
		);
        $this->validate = array(
            'user_id' => array(
                'rule1' => array(
                    'rule' => 'numeric',
                    'message' => __l('Required')
                )
            ) ,
			'username' => array(
                'rule5' => array(
                    'rule' => array(
                        'between',
                        3,
						20
                    ) ,
                    'message' => __l('Must be between of 3 to 20 characters')
                ) ,
                'rule4' => array(
                    'rule' => 'alphaNumeric',
                    'message' => __l('Only use letters and numbers.')
                ) ,
                'rule3' => array(
                    'rule' => 'isUnique',
                    'message' => __l('Username is already exist')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'custom',
                        '/^[a-zA-Z]/'
                    ) ,
                    'message' => __l('Must be start with an alphabets')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
 			'email' => array(
                'rule3' => array(
                    'rule' => 'isUnique',
                    'on' => 'create',
                    'message' => __l('Email address already exists')
                ) ,
                'rule2' => array(
                    'rule' => 'email',
                    'message' => __l('Must be a valid email')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'passwd' => array(
                'rule2' => array(
                    'rule' => array(
                        'minLength',
                        6
                    ) ,
                    'message' => __l('Must be at least 6 characters')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'old_password' => array(
                'rule3' => array(
                    'rule' => array(
                        '_checkOldPassword',
                        'old_password'
                    ) ,
                    'message' => __l('Your old password is incorrect, please try again')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'minLength',
                        6
                    ) ,
                    'message' => __l('Must be at least 6 characters')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'confirm_password' => array(
                'rule3' => array(
                    'rule' => array(
                        '_isPasswordSame',
                        'passwd',
                        'confirm_password'
                    ) ,
                    'message' => __l('New and confirm password field must match, please try again')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'minLength',
                        6
                    ) ,
                    'message' => __l('Must be at least 6 characters')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'captcha' => array(
                'rule2' => array(
                    'rule' => array(
                        '_isValidCaptcha',
                        'register',
                        'captcha'
                    ) ,
                    'message' => __l('Please enter valid captcha')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'ajax_captcha' => array(
                'rule2' => array(
                    'rule' => array(
                        '_isValidCaptcha',
                        'ajax_register',
                        'ajax_captcha'
                    ) ,
                    'message' => __l('Please enter valid captcha')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'is_agree_terms_conditions' => array(
                'rule' => array(
                    'equalTo',
                    '1'
                ) ,
                'message' => __l('You must agree to the terms and conditions')
            ) ,
            'message' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'amount' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'subject' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'send_to' => array(
                'rule1' => array(
                    'rule' => 'checkMultipleEmail',
                    'message' => __l('Must be a valid email') ,
                    'allowEmpty' => true
                )
            ) ,
            'amount' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ),
			'security_question_id' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'security_answer' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
        );
        $this->validateCreditCard = array(
            'firstName' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'lastName' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'creditCardNumber' => array(
                'rule2' => array(
                    'rule' => 'numeric',
                    'message' => __l('Should be numeric') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'expiration_month' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'expiration_year' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'cvv2Number' => array(
                'rule2' => array(
                    'rule' => 'numeric',
                    'message' => __l('Should be numeric') ,
                    'allowEmpty' => false
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'zip' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'address' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'city' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'state' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'country' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
        );
        // filter options in admin index
        $this->isFilterOptions = array(
		 ConstMoreAction::Active => __l('Active') ,
            ConstMoreAction::Inactive => __l('Inactive') ,
            ConstMoreAction::OpenID => __l('OpenID') ,
            ConstMoreAction::Facebook => __l('Facebook') ,
            ConstMoreAction::Twitter => __l('Twitter')
        );
        $this->moreActions = array(
			ConstMoreAction::Active => __l('Active') ,
            ConstMoreAction::Inactive => __l('Inactive') ,
            ConstMoreAction::Delete => __l('Delete') ,
            ConstMoreAction::Export => __l('Export')
        );
        $this->bulkMailOptions = array(
            1 => __l('All Users') ,
            2 => __l('Inactive Users') ,
            3 => __l('Active Users')
        );
    }
    // check the new and confirm password
    function _isPasswordSame($field1 = array() , $field2 = null, $field3 = null)
    {
        if ($this->data[$this->name][$field2] == $this->data[$this->name][$field3]) {
            return true;
        }
        return false;
    }
    // check the old password field with database
    function _checkOldPassword($field1 = array() , $field2 = null)
    {
        $user = $this->find('first', array(
            'conditions' => array(
                'User.id' => $_SESSION['Auth']['User']['id']
            ) ,
            'recursive' => -1
        ));
        if (crypt($this->data[$this->name][$field2], $user['User']['password']) == $user['User']['password']) {
            return true;
        }
        return false;
    }
    // hash for forgot password mail
    function getResetPasswordHash($user_id = null)
    {
        return md5($user_id . '-' . date('y-m-d') . Configure::read('Security.salt'));
    }
    // check the forgot password hash
    function isValidResetPasswordHash($user_id = null, $hash = null)
    {
        return (md5($user_id . '-' . date('y-m-d') . Configure::read('Security.salt')) == $hash);
    }
    // hash for activate mail
    function getActivateHash($user_id = null)
    {
        return md5($user_id . '-' . Configure::read('Security.salt'));
    }
    // check the activate mail
    function isValidActivateHash($user_id = null, $hash = null)
    {
        return (md5($user_id . '-' . Configure::read('Security.salt')) == $hash);
    }
    // hash for resend activate mail
    function getResendActivateHash($user_id = null)
    {
        return md5(Configure::read('Security.salt') . '-' . $user_id);
    }
    // check the resend activate hash
    function isValidResendActivateHash($user_id = null, $hash = null)
    {
        return (md5(Configure::read('Security.salt') . '-' . $user_id) == $hash);
    }
   function checkMultipleEmail()
    {
        $validation = new Validation();
        $multipleEmails = explode(',', $this->data['User']['send_to']);
        foreach($multipleEmails as $key => $singleEmail) {
            if (!$validation->email(trim($singleEmail))) {
                return false;
            }
        }
        return true;
     }
    function getUserIdHash($user_ids = null)
    {
        return md5($user_ids . Configure::read('Security.salt'));
    }
    function isValidUserIdHash($user_ids = null, $hash = null)
    {
        return (md5($user_ids . Configure::read('Security.salt')) == $hash);
    }
    function checkUserBalance($user_id = null)
    {
        $user = $this->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'fields' => array(
                'User.available_wallet_amount',
                'User.blocked_amount',
                'User.cleared_amount',
            ) ,
            'recursive' => -1
        ));
        if ($user['User']['available_wallet_amount']) {
            return $user['User']['available_wallet_amount'];
        }
        return false;
    }
    function checkUsernameAvailable($username)
    {
        $user = $this->find('count', array(
            'conditions' => array(
                'User.username' => $username
            ) ,
            'recursive' => -1
        ));
        if (!empty($user)) {
            return false;
        }
        return $username;
    }
    function afterSave($created)
    {
       if (!empty($this->data['User']['referred_by_user_id'])) { // Updating referred user count
            $user_refer_count = $this->find('count', array(
                'conditions' => array(
                    'User.referred_by_user_id' => $this->data['User']['referred_by_user_id']
                ) ,
                'recursive' => -1
            ));
			$data['User']['id']=$this->data['User']['referred_by_user_id'];
			$data['User']['user_referred_count']=$user_refer_count;
			$this->save($data,array('callbacks' => false));
        }
		if (isPluginEnabled('Items')) {
			// Saving notifications during registerations
			$notify = array();
			App::import('Model', 'Items.UserNotification');
			$this->UserNotification = new UserNotification();
			$check_user_notification_exist = $this->UserNotification->find('first', array(
				'conditions' => array(
					'UserNotification.user_id' => $this->id
				) ,
				'recursive' => -1
			));
			if (empty($check_user_notification_exist) && !empty($this->id)) {
				$notify['UserNotification']['user_id'] = $this->id;
				$this->UserNotification->save($notify['UserNotification']);
			}
		}
		return true;
    }
    function _checkMinimumAmount()
    {
        $amount = $this->data['User']['amount'];
        if (!empty($amount) && $amount < Configure::read('wallet.min_wallet_amount')) {
            return false;
        }
        return true;
    }
    function _checkamount($amount)
    {
        if (!empty($amount) && !is_numeric($amount)) {
            $this->validationErrors['amount'] = __l('Amount should be Numeric');
        }
        if (empty($amount)) {
            $this->validationErrors['amount'] = __l('Required');
        }
        if (!empty($amount) && $amount < Configure::read('wallet.min_wallet_amount')) {
            $this->validationErrors['amount'] = __l('Amount should be greater than minimum amount');
        }
        if (Configure::read('wallet.max_wallet_amount') && !empty($amount) && $amount > Configure::read('wallet.max_wallet_amount')) {
            $currency_code = Configure::read('site.currency_id');
            Configure::write('site.currency', $GLOBALS['currencies'][$currency_code]['Currency']['symbol']);
            $this->validationErrors['amount'] = sprintf(__l('Given amount should lies from  %s%s to %s%s') , Configure::read('site.currency') , Configure::read('wallet.min_wallet_amount') , Configure::read('site.currency') , Configure::read('wallet.max_wallet_amount'));
        }
        return false;
    }
    function _checkMAximumAmount()
    {
        $amount = $this->data['User']['amount'];
        if (Configure::read('wallet.max_wallet_amount') && !empty($amount) && $amount > Configure::read('wallet.max_wallet_amount')) {
            return false;
        }
        return true;
    }
	public function updateSocialContact($social_profile, $social_type)
    {
        $identifier = $social_profile->identifier;
        $_data['User']['id'] = $_SESSION['Auth']['User']['id'];
        $session_data = $_SESSION['HA::STORE'];
        $stored_access_token = $session_data['hauth_session.' . $social_type . '.token.access_token'];
        $temp_access_token = explode(":", $stored_access_token);
        $temp_access_token = str_replace('"', '', $temp_access_token);
        $temp_access_token = str_replace(';', '', $temp_access_token);
        $access_token = $temp_access_token[2];
        if ($social_type == 'facebook') {
            $_data['User']['is_facebook_connected'] = 1;
            $_data['User']['facebook_access_token'] = $access_token;
            $_data['User']['facebook_user_id'] = $identifier;
        } elseif ($social_type == 'twitter') {
            $_data['User']['is_twitter_connected'] = 1;
            $_data['User']['twitter_access_token'] = $access_token;
            $_data['User']['twitter_user_id'] = $identifier;
            $_data['User']['twitter_avatar_url'] = $social_profile->photoURL;
        } elseif ($social_type == 'google') {
            $_data['User']['is_google_connected'] = 1;
            $_data['User']['google_user_id'] = $identifier;
        } elseif ($social_type == 'googleplus') {
            $_data['User']['is_googleplus_connected'] = 1;
            $_data['User']['googleplus_user_id'] = $identifier;
        } elseif ($social_type == 'yahoo') {
            $_data['User']['is_yahoo_connected'] = 1;
            $_data['User']['yahoo_user_id'] = $identifier;
        } elseif ($social_type == 'linkedin') {
            $_data['User']['is_linkedin_connected'] = 1;
            $_data['User']['linkedin_access_token'] = $access_token;
            $_data['User']['linkedin_user_id'] = $identifier;
        }
        $this->save($_data);
    }
    public function _checkConnection($social_profile, $social_type)
    {
        $identifier = $social_profile->identifier;
        $conditions = array();
		$conditions['User.' . $social_type . '_user_id'] = $identifier;
		$conditions['OR'] = array(
			'User.is_' . $social_type . '_register' => 1,
			'User.is_' . $social_type . '_connected' => 1
		);
        $user = $this->find('first', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));
        if (empty($user)) {
            return true;
        } else {
            if ($user['User']['id'] == $_SESSION['Auth']['User']['id']) {
                return true;
            } else {
                return false;
            }
        }
    }
	public function getReceiverdata($foreign_id, $transaction_type, $payee_account) 
    {
        $user = $this->find('first', array(
            'conditions' => array(
                'User.id' => $foreign_id
            ) ,
            'recursive' => -1
        ));
        $return['receiverEmail'] = array(
            $payee_account
        );
        $return['amount'] = array(
            Configure::read('user.signup_fee')
        );
		$return['fees_payer'] = 'buyer';
        if (Configure::read('user.signup_fee_payer') == 'Site') {
            $return['fees_payer'] = 'merchant';
        }
        $return['action'] = 'Capture';
        $return['buyer_email'] = $user['User']['email'];
        $return['sudopay_gateway_id'] = $user['User']['sudopay_gateway_id'];
        return $return;
    }
	function _checkUserBalance($user_id = null)
    {
        $user = $this->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'fields' => array(
                'User.available_wallet_amount',
                'User.blocked_amount',
            ) ,
            'recursive' => -1
        ));
        if ($user['User']['available_wallet_amount']) {
            return $user['User']['available_wallet_amount'];
        }
        return false;
    }
}
?>