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
class Item extends AppModel
{
    public $name = 'Item';
    public $displayField = 'title';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'title'
            )
        ) ,
        'SuspiciousWordsDetector' => array(
            'fields' => array(
                'title',
                'description',
                'tag',
                'address',
                'mobile',
                'house_rules',
                'house_manual'
            )
        ) ,
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
		'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true,
            'counterScope' => array(
                'Item.is_paid' => 1,
                'Item.is_active' => 1,
                'Item.is_approved' => 1
            )
        ) ,
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
		'Category' => array(
            'className' => 'Items.Category',
            'foreignKey' => 'category_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
			'counterCache' => true,
        ),
		'CategoryType' => array(
            'className' => 'Items.CategoryType',
            'foreignKey' => 'category_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
	public $hasOne = array(
        'Submission' => array(
            'className' => 'Items.Submission',
            'foreignKey' => 'item_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
    );
    public $hasMany = array(
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'Item'
            ) ,
            'dependent' => true
        ) ,
        'CustomPricePerNight' => array(
            'className' => 'Items.CustomPricePerNight',
            'foreignKey' => 'item_id',
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
		'CustomPricePerType' => array(
            'className' => 'Items.CustomPricePerType',
            'foreignKey' => 'item_id',
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
            'foreignKey' => 'item_id',
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
        'ItemFeedback' => array(
            'className' => 'Items.ItemFeedback',
            'foreignKey' => 'item_id',
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
        'ItemFlag' => array(
            'className' => 'ItemFlags.ItemFlag',
            'foreignKey' => 'item_id',
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
        'ItemUserFeedback' => array(
            'className' => 'Items.ItemUserFeedback',
            'foreignKey' => 'item_id',
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
        'ItemFavorite' => array(
            'className' => 'ItemFavorites.ItemFavorite',
            'foreignKey' => 'item_id',
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
        'ItemUser' => array(
            'className' => 'Items.ItemUser',
            'foreignKey' => 'item_id',
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
        'ItemView' => array(
            'className' => 'Items.ItemView',
            'foreignKey' => 'item_id',
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
		'BuyerFormField' => array(
            'className' => 'Items.BuyerFormField',
            'foreignKey' => 'item_id',
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
    public $hasAndBelongsToMany = array(
        'Collection' => array(
            'className' => 'Collections.Collection',
            'joinTable' => 'collections_items',
            'foreignKey' => 'item_id',
            'counterCache' => true,
            'associationForeignKey' => 'collection_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
		$db = ConnectionManager::getDataSource($this->useDbConfig);
		if($db->config['datasource'] == 'Database/Mysql') {
			$this->aggregatingFields = array(
				'sales_cleared_count' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.item_id)',
					'conditions' => array(
						'ItemUser.is_payment_cleared' => 1
					)
				) ,
				'sales_pending_count' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.item_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::WaitingforAcceptance
						)
					)
				) ,
				'sales_pipeline_count' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.item_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Confirmed,
						) ,
						'ItemUser.is_payment_cleared' => 0
					)
				) ,
				'sales_completed_count' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.item_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Completed,
							// @todo "Auto review" add condition CompletedAndClosedByAdmin

						)
					)
				) ,
				'sales_rejected_count' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.item_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Rejected
						)
					)
				) ,
				'sales_canceled_count' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.item_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Canceled,
							ConstItemUserStatus::CanceledByAdmin,
						)
					)
				) ,
				'sales_lost_count' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.item_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Rejected,
							ConstItemUserStatus::Canceled,
							ConstItemUserStatus::Expired,
							ConstItemUserStatus::CanceledByAdmin,
						)
					)
				) ,
				'sales_expired_count' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'COUNT(ItemUser.item_id)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Expired
						)
					)
				) ,
				'sales_cleared_amount' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(ItemUser.price)',
					'conditions' => array(
						'ItemUser.is_payment_cleared' => 1
					)
				) ,
				'revenue' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(' . $db->startQuote . 'ItemUser' . $db->endQuote . '.' . $db->startQuote . 'price' . $db->endQuote . ' - ' . $db->startQuote . 'ItemUser' . $db->endQuote . '.' . $db->startQuote . 'host_service_amount' . $db->endQuote . ')',
					'conditions' => array(
						'ItemUser.is_payment_cleared' => 1
					)
				) ,
				'sales_pipeline_amount' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(ItemUser.price)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::WaitingforAcceptance,
						) ,
						'ItemUser.is_payment_cleared' => 0
					)
				) ,
				'sales_lost_amount' => array(
					'mode' => 'real',
					'key' => 'item_id',
					'foreignKey' => 'item_id',
					'model' => 'Items.ItemUser',
					'function' => 'SUM(ItemUser.price)',
					'conditions' => array(
						'ItemUser.item_user_status_id' => array(
							ConstItemUserStatus::Rejected,
							ConstItemUserStatus::Canceled,
							ConstItemUserStatus::Expired,
							ConstItemUserStatus::CanceledByAdmin,
						)
					)
				) ,
			);
		}
		$this->_memcacheModels = array(
			'Image'
		);
		$this->_permanentCacheAssociations = array(
			'User',
			'ItemUser',
			'City',
			'Collection',
			'Chart',
		);
        $this->validate = array(
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
			'username' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'title' => array(
                'rule2' => array(
                    'rule' => array(
                        '_validateTitle',
                        'title'
                    ) ,
                    'message' => sprintf(__l('Must be between of %s to %s') , Configure::read('item.minimum_title_length') , Configure::read('item.maximum_title_length'))
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                ) ,
            ) ,
            'description' => array(
				'rule' => 'notempty',
				'allowEmpty' => false,
				'message' => __l('Required')
            ) ,
            'category_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'sub_category_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'city_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'state_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'country_id' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'slug' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'address' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Please select proper address')
            ) ,
            'address1' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Please enter the address')
            ) ,
            'street_view' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'accommodates' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ),
            'phone' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'video_url' => array(
                'rule' => '_validateVideoUrl',
                'message' => __l('Must be a valid video URL') ,
                'allowEmpty' => true,
            ) ,
			'item_type_id' => array(
				'rule' => 'notempty',
				'allowEmpty' => false,
				'message' => __l('Required')
            ) ,
			'is_auto_approve' => array(
				'rule' => 'notempty',
				'allowEmpty' => false,
				'message' => __l('Required')
            ) ,
			'is_buyer_as_fee_payer' => array(
				'rule' => 'notempty',
				'allowEmpty' => false,
				'message' => __l('Required')
            ) ,
			'is_additional_fee_to_buyer' => array(
				'rule' => 'notempty',
				'allowEmpty' => false,
				'message' => __l('Required')
            ) ,
			'video_url' => array(
                'rule' => '_validateVideoUrl',
                'message' => __l('Must be a valid video URL') ,
                'allowEmpty' => true,
            ) ,
        );
        $this->moreStreetActions = array(
            ConstStreetAction::Hidestreetview => __l('Hide street view') ,
            ConstStreetAction::Closesttoaddress => __l('Closest to my address') ,
            ConstStreetAction::Nearby => __l('Nearby (within 2 blocks)') ,
        );
        $this->moreMeasureActions = array(
            ConstMeasureAction::Squarefeet => __l('Square Feet') ,
            ConstMeasureAction::Squaremeasures => __l('Square Meters')
        );
        $this->moreMyItemsActions = array(
			ConstMoreAction::Active => __l('Enable') ,
            ConstMoreAction::Inactive => __l('Disable') ,
        );
        $this->moreActionitems = array(
            ConstMoreAction::Suspend => __l('Suspend') ,
            ConstMoreAction::Unsuspend => __l('Unsuspend') ,
            ConstMoreAction::Flagged => __l('Flag') ,
            ConstMoreAction::Unflagged => __l('Clear flag') ,
            ConstMoreAction::Delete => __l('Delete') ,
        );
		if(isPluginEnabled('Collections')){
			$this->moreActionitems[ConstMoreAction::Collection]  = __l('Add in collection');
		}
		
    }
    function _validateTitle($field1 = array() , $field2 = null)
    {
        if (strlen($this->data[$this->name][$field2]) <= Configure::read('item.maximum_title_length') && strlen($this->data[$this->name][$field2]) >= Configure::read('item.minimum_title_length')) {
            return true;
        } else {
            return false;
        }
    }
    function _validateVideoUrl()
    {
        App::import('Helper', 'Embed');
        $this->Embed = new EmbedHelper();
        if (!(!empty($this->data['Item']['video_url']) && $this->Embed->parseUrl($this->data['Item']['video_url']))) {
            return false;
        }
        return true;
    }
    function _validateGuestCount($field1 = array())
    {
        if (!empty($this->data[$this->name]['additional_guest']) && !empty($this->data[$this->name]['accommodates']) && !empty($this->data[$this->name]['additional_guest_price']) && $this->data[$this->name]['additional_guest'] >= $this->data[$this->name]['accommodates']) {
            return false;
        }
        return true;
    }
    function _validateAdditionalGuest($field1 = array())
    {
		if(!empty($this->data[$this->name]['accommodates']) && $this->data[$this->name]['accommodates'] > 1)
        if (empty($this->data[$this->name]['additional_guest']) && !empty($this->data[$this->name]['additional_guest_price'])) {
            return false;
        }
        return true;
    }
    function _validateAdditionalGuestPriceCompare($field1 = array())
    {
		if(!empty($this->data[$this->name]['accommodates']) && $this->data[$this->name]['accommodates'] > 1)
        if (!empty($this->data[$this->name]['additional_guest_price']) && $this->data[$this->name]['additional_guest_price'] <= 0) {
            return false;
        }
        return true;
    }
	function _validateAdditionalGuestPrice($field1 = array())
    {
		if(!empty($this->data[$this->name]['accommodates']) && $this->data[$this->name]['accommodates'] > 1)
        if (!empty($this->data[$this->name]['additional_guest']) && empty($this->data[$this->name]['additional_guest_price'])) {
            return false;
        }
        return true;
    }
    function _validateNights($field1 = array())
    {
        if (!empty($this->data[$this->name]['minimum_nights']) && !empty($this->data[$this->name]['maximum_nights']) && $this->data[$this->name]['minimum_nights'] > $this->data[$this->name]['maximum_nights']) {
            return false;
        }
        return true;
    }
    function _validatePrice($field1 = array() , $field2 = null)
    {
        if (isset($this->data[$this->name][$field2]) && $this->data[$this->name][$field2] < 0) {
            return false;
        }
        return true;
    }
    function checkCustomWeekPrice($from, $to, $item_id, $additional_guest, $convert = false)
    {
        if ($from > $to || $from < date('Y-m-d')) {
            return 0;
        }
        App::import('Model', 'Items.CustomPricePerWeek');
        $this->CustomPricePerWeek = new CustomPricePerWeek();
        $days = getFromToDiff($from, $to);
        $no_weeks = round($days/7);
        $cutom_week_prices = $this->CustomPricePerWeek->find('all', array(
            'conditions' => array(
                'CustomPricePerWeek.start_date <=' => $to,
                'CustomPricePerWeek.end_date >=' => $from,
                'CustomPricePerWeek.item_id' => $item_id,
            ) ,
            'fields' => array(
                'count(CustomPricePerWeek.price) as records',
                'sum(CustomPricePerWeek.price) as total_price',
            ) ,
            'group' => array(
                'CustomPricePerWeek.item_id',
            ) ,
            'recursive' => -1,
        ));
        $item = $this->find('first', array(
            'conditions' => array(
                'Item.id' => $item_id,
            ) ,
            'recursive' => -1,
        ));
        $records = 0;
        $cstom_week_price = 0;
        if (!empty($cutom_week_prices)) {
            $records = $cutom_week_prices[0][0]['records'];
            $cstom_week_price = $cutom_week_prices[0][0]['total_price'];
        }
        //additional guest price calculations
        $additional_guest = (($additional_guest-$item['Item']['additional_guest']) > 0) ? ($additional_guest-$item['Item']['additional_guest']) : 0;
        $additional_guest_price = 0;
        if ($additional_guest > 0) {
            $additional_guest_price = ($additional_guest*$item['Item']['additional_guest_price']) *$days;
        }
        $remaining_weeks = $no_weeks-$records;
        $price = 0;
        if ($remaining_weeks > 0) {
            $item_price = !empty($item['Item']['price_per_week']) ? $item['Item']['price_per_week'] : $item['Item']['price_per_day'];
            if (!empty($item['Item']['price_per_week'])) {
                $price = $item_price*($remaining_weeks);
            } else {
                $price = $item_price*(7*$remaining_weeks);
            }
        }
        //appening custom price
        $price = $price+$cstom_week_price;
        //appending the addition guest price
        $price = $price+$additional_guest_price;
        if ($convert) {
            return $this->siteWithCurrencyFormat($price, false);
        } else {
            return $price;
        }
    }
    function checkCustomMonthPrice($from, $to, $item_id, $additional_guest, $convert = false)
    {
        if ($from > $to || $from < date('Y-m-d')) {
            return 0;
        }
        App::import('Model', 'Items.CustomPricePerMonth');
        $this->CustomPricePerMonth = new CustomPricePerMonth();
        $days = getFromToDiff($from, $to);
        $no_months = round($days/30);
        $cutom_month_prices = $this->CustomPricePerMonth->find('all', array(
            'conditions' => array(
                'CustomPricePerMonth.start_date <=' => $to,
                'CustomPricePerMonth.end_date >=' => $from,
                'CustomPricePerMonth.item_id' => $item_id,
            ) ,
            'fields' => array(
                'count(CustomPricePerMonth.price) as records',
                'sum(CustomPricePerMonth.price) as total_price',
            ) ,
            'group' => array(
                'CustomPricePerMonth.item_id',
            ) ,
            'recursive' => -1,
        ));
        $item = $this->find('first', array(
            'conditions' => array(
                'Item.id' => $item_id,
            ) ,
            'recursive' => -1,
        ));
        $records = 0;
        $cstom_month_price = 0;
        if (!empty($cutom_month_prices)) {
            $records = $cutom_month_prices[0][0]['records'];
            $cstom_month_price = $cutom_month_prices[0][0]['total_price'];
        }
        //additional guest price calculations
        $additional_guest = (($additional_guest-$item['Item']['additional_guest']) > 0) ? ($additional_guest-$item['Item']['additional_guest']) : 0;
        $additional_guest_price = 0;
        if ($additional_guest > 0) {
            $additional_guest_price = ($additional_guest*$item['Item']['additional_guest_price']) *$days;
        }
        $remaining_months = $no_months-$records;
        $price = 0;
        if ($remaining_months > 0) {
            $item_price = !empty($item['Item']['price_per_month']) ? $item['Item']['price_per_month'] : $item['Item']['price_per_day'];
            if (!empty($item['Item']['price_per_month'])) {
                $price = $item_price*($remaining_months);
            } else {
                $price = $item_price*(30*$remaining_months);
            }
        }
        //appening custom price
        $price = $price+$cstom_month_price;
        //appending the addition guest price
        $price = $price+$additional_guest_price;
        if ($convert) {
            return $this->siteWithCurrencyFormat($price, false);
        } else {
            return $price;
        }
    }
    function checkCustomPrice($from, $to, $item_id, $additional_guest, $convert = false)
    {
        if ($from > $to || $from < date('Y-m-d')) {
            return 0;
        }
        App::import('Model', 'Items.CustomPricePerNight');
        $this->CustomPricePerNight = new CustomPricePerNight();
        $days = getFromToDiff($from, $to);
        $no_days = $days;
        $customPricePerNights = $this->CustomPricePerNight->find('all', array(
            'conditions' => array(
                'CustomPricePerNight.item_id' => $item_id,
            ) ,
            'recursive' => -1,
        ));
        $item = $this->find('first', array(
            'conditions' => array(
                'Item.id' => $item_id,
            ) ,
            'recursive' => -1,
        ));		
        $from_to_date_diff = getFromToDiff($from, $to);
        $found = $custom_daily_price = $additional_guest_price = 0;
        for ($i = 0; $i < $from_to_date_diff; $i++) {
            if ($i == 0) {
                $tmp_from = $from;
            } else {
                $tmp_from = date('Y-m-d', strtotime($tmp_from . ' +1 day'));
            }
            foreach($customPricePerNights as $customPricePerNight) {
                if ((strtotime($tmp_from) >= strtotime($customPricePerNight['CustomPricePerNight']['start_date'])) && (strtotime($tmp_from) <= strtotime($customPricePerNight['CustomPricePerNight']['end_date']))) {
                    $custom_daily_price+= $customPricePerNight['CustomPricePerNight']['price'];
                    $found++;
                    break;
                }
            }
        }
        //additional guest price calculations
        $additional_guest = (($additional_guest-$item['Item']['additional_guest']) > 0) ? ($additional_guest-$item['Item']['additional_guest']) : 0;
        if ($additional_guest > 0) {
            $additional_guest_price = ($additional_guest*$item['Item']['additional_guest_price']) *$no_days;
        }
        $remaining_days = $no_days-$found;
        $price = ($remaining_days*$item['Item']['price_per_day']) +$custom_daily_price+$additional_guest_price;
        if ($convert) {
            return $this->siteWithCurrencyFormat($price, false);
        } else {
            return $price;
        }
    }
    function getSearchKeywords($hash_keyword = '', $salt = '')
    {
        App::import('Model', 'Items.SearchKeyword');
        $this->SearchKeyword = new SearchKeyword();
        if (!empty($hash_keyword) && !empty($salt)) {
            //decoding
            $keyword_id = hexdec($hash_keyword);
            //checking valid one using encoding format
            $check_hash = dechex($keyword_id);
            $salty = $keyword_id+786;
            $check_salt = substr(dechex($salty) , 0, 2);
            if ($check_hash == $hash_keyword && $check_salt == $salt) {
                $searchKeyword = $this->SearchKeyword->find('first', array(
                    'conditions' => array(
                        'SearchKeyword.id =' => $keyword_id
                    ) ,
                    'fields' => array(
                        'SearchKeyword.id',
                        'SearchKeyword.keyword',
                    ) ,
                    'recursive' => -1,
                ));
                $querystring = $searchKeyword['SearchKeyword']['keyword'];
                $query_string_array = explode('/', $querystring);
                $query_string_array = array_filter($query_string_array);
                $named_array = array();
                foreach($query_string_array as $key => $val) {
                    $split_array = explode(':', $val);
                    if(sizeof($split_array)>1){
                    $named_array[$split_array[0]] = $split_array[1];
                    unset($split_array);
                       }
                }
                return $named_array;
            } else {
                $this->redirect(array(
                    'controller' => 'items',
                    'action' => 'index',
                    'admin' => false
                ));
            }
        }
    }
    function getCalendarData($year, $month, $conditions)
    {
        $sites = $this->find('all', array(
            'conditions' => array(
                'Item.is_active != ' => 1
            ) ,
            'fields' => array(
                'Item.id'
            ) ,
            'recursive' => 0
        ));
        if (!empty($sites)) {
            foreach($sites as $site) {
                $unverified[] = $site['Item']['id'];
            }
            $conditions['NOT']['Item.id'] = $unverified;
        }
        for ($i = 1, $j = 1; $i <= 31; $i++, $j++) {
            if ($i < 10) {
                $i = '0' . $i;
            }
            if ($month < 10) {
                $month = '0' . $month;
            }
            $day[$year][$j] = $this->find('count', array(
                'conditions' => array(
                    $conditions,
                    'and' => array(
                        'Item.from >= ' => $year . '-' . $month . '-' . $i . ' 00:00:00',
                        'Item.to <= ' => $year . '-' . $month . '-' . $i . ' 23:59:59',
                        'Item.is_active' => 1
                    )
                ) ,
                'recursive' => -1
            ));
        }
        return $day;
    }
    function _updateCityItemCount()
    {
        App::import('Model', 'City');
        $this->City = new City();
        $cityItems = $this->City->find('all', array(
            'conditions' => array(
                'City.is_approved' => 1,
            ) ,
            'contain' => array(
                'Item' => array(
                    'conditions' => array(
                        'Item.is_active' => 1,
                        'Item.is_approved' => 1,
                    ) ,
                    'fields' => array(
                        'Item.id',
                    ) ,
                ) ,
            ) ,
            'recursive' => 2
        ));
        $this->City->updateAll(array(
            'City.item_count' => 0,
        ), array(
		));
        foreach($cityItems as $cityItem) {
            if (!empty($cityItem['Item'])) {
                $this->City->updateAll(array(
                    'City.item_count' => count($cityItem['Item']) ,
                ) , array(
                    'City.id' => $cityItem['City']['id']
                ));
            }
        }
    }
    function __updateItemRequest($request_id, $item_id)
    {
        App::import('Model', 'Requests.ItemsRequest');
        $this->ItemsRequest = new ItemsRequest();
       App::import('Model', 'Requests.Request');
        $this->Request = new Request();
        $item_request = $this->ItemsRequest->find('count', array(
            'conditions' => array(
                'ItemsRequest.item_id' => $item_id,
                'ItemsRequest.request_id' => $request_id,
            ) ,
            'recursive' => -1
        ));
        if (!$item_request) {
            $this->data['ItemsRequest']['request_id'] = $request_id;
            $this->data['ItemsRequest']['item_id'] = $item_id;
            $this->ItemsRequest->save($this->data['ItemsRequest']);
            $request_count = $this->ItemsRequest->find('count', array(
                'conditions' => array(
                    'ItemsRequest.item_id' => $item_id,
                ) ,
                'recursive' => -1
            ));
            $item_count = $this->ItemsRequest->find('count', array(
                'conditions' => array(
                    'ItemsRequest.request_id' => $request_id,
                ) ,
                'recursive' => -1
            ));
            $this->Request->updateAll(array(
                'Request.item_count' => $item_count,
            ) , array(
                'Request.id' => $request_id
            ));
            $this->updateAll(array(
                'Item.request_count' => $request_count,
            ) , array(
                'Item.id' => $item_id
            ));
            $this->_requestItemNotificationMail($request_id, $item_id);
            return $this->ItemsRequest->getLastInsertId();
        }
        return false;
    }
    // For iPhone App code -->
    function _requestItemNotificationMail($request_id, $item_id)
    {
        App::import('Model', 'Items.Message');
        $this->Message = new Message();
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        $request_item = $this->find('first', array(
            'conditions' => array(
                'Item.id' => $item_id
            ) ,
            'contain' => array(
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
            'recursive' => 0
        ));
        $request_user = $this->Request->find('first', array(
            'conditions' => array(
                'Request.id' => $request_id
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email'
                    )
                ) ,
            ) ,
            'recursive' => 0
        ));
        // @todo "User activation" check user.is_send_email_notifications_only_to_verified_email_account
        $email = $this->EmailTemplate->selectTemplate('Requested Item Notification');
        $emailFindReplace = array(
            '##USERNAME##' => $request_user['User']['username'],
            '##ACTIVATION_URL##' => Router::url(array(
                'controller' => 'items',
                'action' => 'view',
                $request_item['Item']['slug'],
            ) , true) ,
            '##REQUEST_URL##' => Router::url(array(
                'controller' => 'requests',
                'action' => 'view',
                $request_user['Request']['slug']
            ) , true) ,
            '##ITEM_URL##' => Router::url(array(
                'controller' => 'items',
                'action' => 'view',
                $request_item['Item']['slug']
            ) , true) ,
            '##FROM_EMAIL##' => ($email['from'] == '##FROM_EMAIL##') ? Configure::read('site.from_email') : $email['from'],
			'##UNSUBSCRIBE_LINK##' => Router::url(array(
				'controller' => 'user_notifications',
				'action' => 'edit',
				'admin' => false
			), true),
			'##CONTACT_URL##' => Router::url(array(
				'controller' => 'contacts',
				'action' => 'add',
				'admin' => false
			), true),
        );
        $sender_email = $request_item['User']['email'];
        $to = $request_user['User']['id'];
        $subject = strtr($email['subject'], $emailFindReplace);
        $message = strtr($email['email_text_content'], $emailFindReplace);
        if (Configure::read('messages.is_send_internal_message')) {
            $message_id = $this->Message->sendNotifications($to, $subject, $message, 0, 0, $item_id, 0);
            if (Configure::read('messages.is_send_email_on_new_message')) {
				$this->_sendEmail($email,$emailFindReplace,$request_user['User']['email']);
            }
        }
    }
	function autofacebookpost($item_id){
		$item = $this->find('first', array(
                'conditions' => array(
                    'Item.id' => $item_id
                ) ,
                'contain' => array(
                    'Attachment',
					'User'
                ) ,
                'recursive' => 1
            ));
		$message = $item['User']['username'] . ' ' . __l('listed a new item "') . '' . $item['Item']['title'] . __l('" in ') . Configure::read('site.name');
		$slug = $item['Item']['slug'];
		$description = $item['Item']['description'];
		$image_options = array(
			'dimension' => 'small_big_thumb',
			'class' => '',
			'alt' => $item['Item']['title'],
			'title' => $item['Item']['title'],
			'type' => 'png',
			'full_url' => true
		);
			//$facebook_dest_user_id = Configure::read('facebook.fb_user_id');	// Site USER ID
			$facebook_dest_user_id = Configure::read('facebook.page_id'); // Site Page ID
			$facebook_dest_access_token = Configure::read('facebook.fb_access_token');
	   
		App::import('Vendor', 'facebook/facebook');
		$this->facebook = new Facebook(array(
			'appId' => Configure::read('facebook.app_id') ,
			'secret' => Configure::read('facebook.fb_secrect_key') ,
			'cookie' => true
		));
	  $image_url = getImageUrl('Item', $item['Attachment'][0], $image_options);
		
		$image_link = Router::url(array(
			'controller' => 'items',
			'action' => 'view',
			'admin' => 'false',
			$slug
		) , true);
		try {
			$getPostCheck = $this->facebook->api('/' . $facebook_dest_user_id . '/feed', 'POST', array(
				'access_token' => $facebook_dest_access_token,
				'message' => $message,
				'picture' => $image_url,
				'icon' => $image_url,
				'link' => $image_link,
				'description' => $description
			));
			$this->log('Posted on facebook');
		}
		catch(Exception $e) {
			$this->log('Post on facebook error');
			return 2;
		}
	}
    function _getCalendarBookingDates($item_id, $month, $year)
    {
        $booked = array();
        $available_week = array();
        // Find propery_user and custom_nights monthly booking //
        $item_user_monthly_bookings = $this->ItemUser->_getCalendarMontlyBooking($item_id, $month, $year);
        $custom_night_monthly_bookings = $this->CustomPricePerNight->_getCalendarMontlyBooking($item_id, $month, $year);
        foreach($item_user_monthly_bookings as $item_user_monthly_booking) {
            $booked[] = array(
                'start_date' => $item_user_monthly_booking['ItemUser']['from'],
                'end_date' => $item_user_monthly_booking['ItemUser']['to'],
                'price' => $item_user_monthly_booking['ItemUser']['price'],
                'is_custom_nights' => 0,
                'color' => '#FFA2B7'
            );
        }
        foreach($custom_night_monthly_bookings as $custom_night_monthly_booking) {
            $booked[] = array(
                'start_date' => $custom_night_monthly_booking['CustomPricePerNight']['start_date'],
                'end_date' => $custom_night_monthly_booking['CustomPricePerNight']['end_date'],
                'price' => $custom_night_monthly_booking['CustomPricePerNight']['price_per_day'],
                'is_custom_nights' => 1,
                'is_available' => $custom_night_monthly_booking['CustomPricePerNight']['is_available'],
                'color' => ($custom_night_monthly_booking['CustomPricePerNight']['is_available']) ? '#98CF67' : '#FFE3F2',
                'pastdate' => (strtotime('now') > strtotime($custom_night_monthly_booking['CustomPricePerNight']['start_date'])) ? 1 : 0
            );
        }
        $conditions = array();
        $conditions['Item.id'] = $item_id;
        $item = $this->find('first', array(
            'conditions' => $conditions,
            'fields' => array(
                'Item.id',
                'Item.title',
                'Item.slug',
                'Item.price_per_hour',
                'Item.price_per_day',
                'Item.price_per_week',
                'Item.price_per_month',
            ) ,
            'recursive' => -1
        ));
        $ts = strtotime(date("Y-m-d", mktime(0, 0, 0, $month, 1, $year)));
        for ($i = 0; $i < 6; $i++) {
            $weekcnt = $i+1;
            $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
            $start_date = date('Y-m-d', $start);
            $end_date = date('Y-m-d', strtotime('next saturday', $start));
            $temp = explode('-', $end_date);
            $ts = strtotime(date("Y-m-d", mktime(0, 0, 0, $month, $temp[2]+1, $year)));
            if (strtotime(date('Y-m-d')) >= strtotime(date("Y-m-d", $start))) {
                $booked['week'][$weekcnt]['is_available'] = 2;
            } else {
                $booked_status = $this->ItemUser->_getCalendarWeekBooking($item_id, $start_date, $end_date);
                if ($booked_status) {
                    $booked['week'][$weekcnt]['is_available'] = 0;
                } else {
                    $booked['week'][$weekcnt]['is_available'] = 1;
                }
            }
            $booked['week'][$weekcnt]['start_date'] = $start_date;
            $booked['week'][$weekcnt]['end_date'] = $end_date;
            $booked['week'][$weekcnt]['price'] = $item['Item']['price_per_week'];
        }
        foreach($booked['week'] as $key => $week) {
            if (empty($available_week[$key])) {
                $available_week['week' . $key] = $week;
            } else {
                $available_week['week' . $key] = $available_week[$key];
                $available_week['week' . $key]['is_available'] = $week['is_available'];
                unset($available_week[$key]);
            }
        }
        $booked['week'] = $available_week;
        $booked['item_id'] = $item['Item']['id'];
        $booked['weekly_price'] = !empty($item['Item']['price_per_week']) ? $item['Item']['price_per_week'] : ($item['Item']['price_per_day']*7);
        $booked['night_price'] = $item['Item']['price_per_day'];
        $booked['monthly'] = !empty($item['Item']['price_per_month']) ? $item['Item']['price_per_month'] : ($item['Item']['price_per_day']*30);
        return $booked;
    }
    public function getFacebookFriendLevel($user_ids)
    {
        $userFacebookFriends = $this->_getUserFacebookFriendsList($_SESSION['Auth']['User']['id']);
        $network_level = array();
        if (!empty($userFacebookFriends)) {
            $bookerFacebookFriends = $bookerFirstFacebookFriends = array_keys($userFacebookFriends[$_SESSION['Auth']['User']['id']]);
            if (!empty($bookerFirstFacebookFriends)) {
                foreach($user_ids as $user_id => $fb_user_id) {
                    if (in_array($fb_user_id, $bookerFirstFacebookFriends)) {
                        $network_level[$user_id] = 1;
                        continue;
                    } else {
                        for ($i = 2; $i <= Configure::read('item.network_level'); $i++) {
                            $userIds = $this->_getUserIds($bookerFacebookFriends);
                            $bookerFacebookFriends = array();
                            if (!empty($userIds)) {
                                $tmpUserFacebookFriends = $this->_getUserFacebookFriendsList(array_keys($userIds));
                                if (!empty($tmpUserFacebookFriends)) {
                                    foreach($tmpUserFacebookFriends as $tmpUserFacebookFriend) {
                                        $bookerFacebookFriends = array_keys($tmpUserFacebookFriend);
                                    }
                                    if (!empty($bookerFacebookFriends)) {
                                        if (in_array($fb_user_id, $bookerFacebookFriends)) {
                                            $network_level[$user_id] = $i;
                                            break;
                                        }
                                    } else {
                                        break;
                                    }
                                } else {
                                    break;
                                }
                            } else {
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $network_level;
    }
    function getMutualFriends($booker_user_id, $host_user_id)
    {
        return $this->_getUserFacebookFriendsList(array(
            $booker_user_id,
            $host_user_id
        ));
    }
    function _getUserFacebookFriendsList($user_ids)
    {
        $userFacebookFriends = $this->User->UserFacebookFriend->find('list', array(
            'conditions' => array(
                'UserFacebookFriend.user_id' => $user_ids
            ) ,
            'fields' => array(
                'UserFacebookFriend.facebook_friend_id',
                'UserFacebookFriend.facebook_friend_name',
                'UserFacebookFriend.user_id',
            ) ,
            'recursive' => -1
        ));
        return $userFacebookFriends;
    }
    function _getUserIds($facebook_user_ids)
    {
        $userIds = $this->User->find('list', array(
            'conditions' => array(
                'User.network_fb_user_id' => $facebook_user_ids
            ) ,
            'fields' => array(
                'User.id',
                'User.username',
            ) ,
            'recursive' => -1
        ));
        return $userIds;
    }
	function processPayment($item_id, $total_amount, $gateway_id, $transaction_type, $item_user_id = null)
    {
        $item = $this->find('first', array(
            'conditions' => array(
                'Item.id' => $item_id
            ) ,
            'contain' => array(
                'User',
            ) ,
            'recursive' => 0,
        ));
        if ($transaction_type == ConstPaymentType::ItemListingFee) {
			$_Data['Item']['id'] = $item['Item']['id'];
			$_Data['Item']['is_paid'] = 1;
			$_Data['Item']['item_fee'] = $total_amount;
			$_Data['Item']['is_approved'] = (Configure::read('item.is_auto_approve')) ? 1 : 0;
			$this->save($_Data);
			$host_total_site_revenue = $item['User']['host_total_site_revenue'] + $total_amount;
			$this->User->updateAll(array(
				'User.host_total_site_revenue' => $host_total_site_revenue
			) , array(
				'User.id' => $item['User']['id']
			));
			$this->User->Transaction->log($item['Item']['id'], 'Items.Item', $gateway_id, ConstTransactionTypes::ItemListingFee);
        } else if($transaction_type == ConstPaymentType::BookingAmount) {
			$itemUser = $this->ItemUser->find('first', array(
                'conditions' => array(
                    'ItemUser.id' => $item_user_id
                ) ,
                'recursive' => 0
            ));
			$host_total_site_revenue = $item['User']['host_total_site_revenue'] + $total_amount;
			$this->User->updateAll(array(
				'User.host_total_site_revenue' => $host_total_site_revenue
			) , array(
				'User.id' => $item['User']['id']
			));
			if($itemUser['Item']['is_auto_approve'] == 1) {				
				$this->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::Confirmed, $gateway_id);
			} else {				
				$this->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::WaitingforAcceptance, $gateway_id);
			}
		}
    }
	public function getReceiverdata($foreign_id, $transaction_type, $payee_account)
    {
        $item = $this->find('first', array(
            'conditions' => array(
                'Item.id' => $foreign_id
            ) ,
            'contain' => array(
                'User'
            ) ,
            'recursive' => 0,
        ));
        $return['receiverEmail'] = array(
            $payee_account
        );
        if($transaction_type == ConstPaymentType::ItemListingFee) {
			$amount = Configure::read('item.item_fee');
			$amount = round($amount, 2);
			$return['amount'] = array(
				$amount
			);
			$return['fees_payer'] = 'buyer';
			if (Configure::read('item.item_fee_payer') == 'Site') {
				$return['fees_payer'] = 'merchant';
			}
			$return['sudopay_gateway_id'] = $item['Item']['item_sudopay_gateway_id'];
		}
        $return['action'] = 'Capture';
        $return['buyer_email'] = $item['User']['email'];
        return $return;
    }
	public function check_availability($request_data = array())
	{
		$data = array();		
		$data = $request_data;
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
		if($data['ItemUser']['is_sell_ticket']){
			$custom_price_per_night_parent = $this->ItemUser->CustomPricePerNight->find('all', array(
				'conditions' => array(
					'CustomPricePerNight.item_id' => $data['ItemUser']['item_id'],
					'CustomPricePerNight.parent_id' => 0,
					'CustomPricePerNight.start_date <=' => $request_start_date,
					'CustomPricePerNight.end_date >=' => $request_end_date
				) ,
				'order' => array(
					'CustomPricePerNight.start_date' => 'ASC'
				),
				'recursive' => 1
			));	
			$availabilities = array();
			foreach($custom_price_per_night_parent as $parent){
				if(empty($availabilities)){
					$availabilities[] = $parent['CustomPricePerType'][0];
					break;
				}
			}
			return $availabilities;
		}
		if($data['ItemUser']['is_people_can_book_my_time']){
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
			if(strtotime($request_st) > strtotime(date('Y-m-d')) &&  strtotime($request_st) < strtotime($request_end)){
				foreach($custom_price_per_nights as $key => $custom_price_per_night){
					$avail_st_dt = $custom_price_per_night['CustomPricePerNight']['start_date'];
					$avail_end_dt = $custom_price_per_night['CustomPricePerNight']['end_date'];
					$avail_st_time = $custom_price_per_night['CustomPricePerNight']['start_time'];
					$avail_end_time = $custom_price_per_night['CustomPricePerNight']['end_time'];
					$str_end_date = strtotime($avail_end_dt);
					if(!$is_timing){
					// all time
						$avail_st = $avail_st_dt . ' '. $avail_st_time;
						$avail_end = $avail_end_dt . ' '. $avail_end_time;
						if(!empty($str_end_date)){
							// end date available
							if(strtotime($request_st) >= strtotime($avail_st) && strtotime($request_end) <= strtotime($avail_end)){
								$available_bookings[] = $custom_price_per_night;
							}
						} else {
							// end date not available
							if(strtotime($request_st) >= strtotime($avail_st)){
								$available_bookings[] = $custom_price_per_night;
							}
						}
					} else {
					// specific timing
						if(!empty($str_end_date)){
							// not empty of end date
							if(strtotime($request_start_date) >= strtotime($avail_st_dt) && strtotime($request_end_date) <= strtotime($avail_end_dt) && strtotime($request_start_time) >= strtotime($avail_st_time) && strtotime($request_end_time) <= strtotime($avail_end_time) ){
								$available_bookings[] = $custom_price_per_night;
							}
						} else {
							// empty of end date
							if(strtotime($request_start_date) >= strtotime($avail_st_dt) && strtotime($request_start_time) >= strtotime($avail_st_time) && strtotime($request_end_time) <= strtotime($avail_end_time)){
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
					if(!empty($custom_price_per_night['CustomPricePerNight']['repeat_days'])) {
						$repeat_days_arr = explode(',', $custom_price_per_night['CustomPricePerNight']['repeat_days']);
						for ($i = 0; $i <= $total_days; $i++) {
							$day = date('Y-m-d', strtotime($from . "+" . $i . " day"));
							$day_of_day = $day_of_the_week[date('N', strtotime($day))];
							if (!in_array($day_of_day, $repeat_days_arr)) {
								$not_avaliable[] = $key;
							}
						}
					}
				}
				foreach($not_avaliable as $val){
					unset($available_bookings[$val]);
				}
			}				
			return $available_bookings;	
		}
	}
	public function update_minimum_price($item_id) {
		$custom_min_price = $this->CustomPricePerNight->find('first', array(
			'conditions' => array(
				'CustomPricePerNight.item_id' => $item_id,
				'CustomPricePerNight.parent_id' => 0
			),
			'recursive' => -1
		));
		
		$custom_price_per_night = $this->CustomPricePerNight->find('all', array(
			'conditions' => array(
				'CustomPricePerNight.item_id' => $item_id,
				'CustomPricePerNight.parent_id !=' => 0
			),
			'order' => array(
				'CustomPricePerNight.id' => 'ASC'
			) ,
			'recursive' => -1
		));
		$price = $custom_price_per_night[0]['CustomPricePerNight'];
		$minimum_price = min($price['price_per_hour'], $price['price_per_day'], $price['price_per_week'], $price['price_per_month']);
		$custom_price = array();
		$custom_price['id'] = $custom_min_price['CustomPricePerNight']['id'];
		$custom_price['minimum_price'] = $minimum_price;
		$this->CustomPricePerNight->save($custom_price);
		
		$item_arr = array();
		$item_arr['Item']['id'] = $item_id;
		$item_arr['Item']['minimum_price'] = $minimum_price;
		$this->save($item_arr);
	}
}
?>