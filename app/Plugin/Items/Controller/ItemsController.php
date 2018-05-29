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
class ItemsController extends AppController
{
    public $name = 'Items';
    public $lastDays;
    public $helpers = array(
        'Text'
    );
    public $permanentCacheAction = array(
        'user' => array(
            'calendar_edit',
            'calendar',
            'datafeed',
            'item_calendar',
            'manage_item',
            'my_items',
            'import',
            'item_pay_now',
        ) ,
        'public' => array(
            'index',
            'search',
            'map',
            //'order',
        ) ,
        'admin' => array(
            'add',
            'edit',
            'admin_add',
            'admin_edit',
        ) ,
        'is_view_count_update' => true
    );
    public function beforeFilter()
    {
        if (in_array($this->request->action, array(
            'update_price',
            'update_view_count',
            'datafeed',
            'add_attachment',
            'sort_attachments',
			'add_simple'
        ))) {
            $this->Security->validatePost = false;
        }
        $this->Security->disabledFields = array(
            'Attachment',
            'Attachment.file',
            'Item.latitude',
            'Item.longitude',
            'Item.ne_latitude',
            'Item.ne_longitude',
            'Item.sw_latitude',
            'Item.sw_longitude',
            'Item.zoom_level',
            'Item.country_id',
            'ItemUser.item_id',
            'ItemUser.item_slug',
            'Item.request_id',
            'Item.is_street_view',
            'Item.jscity',
            'Item.cityName',
            'ItemUser.type',
            'City.id',
            'State.id',
            'State.name',
            'City.name',
            'ids',
            'showdate',
            'timezone',
            'viewtype',
            'Item.id',
            'Item.payment_gateway_id',
            'Item.sudopay_gateway_id',
            'Item.wallet',
            'Item.normal',
            'ItemUser.payment_gateway_id',
            'ItemUser.sudopay_gateway_id',
            'ItemUser.wallet',
            'ItemUser.normal',
            'ItemUser.free',
            'ItemUser.request',
            'ItemUser.bookit',
            'Item.contact',
            'Item.accept',
            'Item.title',
            'Item.description',
            'Item.address',
            'Item.address1',
            'Item.is_auto_approve',
            'Item.is_user_can_request',
            'Item.category_id',
            'Item.sub_category_id',
            'Item.item_type_id',
            'Item.price_per_day',
            'Item.price_per_hour',
            'Item.price_per_month',
            'Item.price_per_week',
            'Item.additional_fee_name',
            'Item.additional_fee_percentage',
            'Item.is_additional_fee_to_buyer',
            'Item.is_buyer_as_fee_payer',
            'Item.min_number_of_ticket',
            'Item.is_have_definite_time',
            'Item.is_people_can_book_my_time',
            'Item.is_sell_ticket',
            'Item.Category',
            'Item.price_type',
            'Item.video_url',
            'Sudopay',
            'Form',
            'FormField',
            'CustomPricePerNight',
            'CustomPricePerType',
            'BuyerFormFieldName',
            'Item.category_type_id',
			'Item.keyword',
			'Item.language',
			'Item.range_from',
			'Item.range_to',
			'Item.parent_category_id',
			'Item.city_name',
			'Item.state_name',
			'Item.country_iso2',
			'Item.type',
			'Item.user_id',
			'Item.username',
			'Item.booking_type',
			'wysihtml5_mode'
        );
		if ((!empty($this->request->params['action']) and ($this->request->params['action'] == 'index'))) {
            $this->Security->validatePost = false;
        }
        parent::beforeFilter();
    }
    public function search()
    {
        $this->pageTitle = __l('Home');
        $this->request->data['Item']['to'] = getToDate(date('Y-m-d'));
        if ((Configure::read('site.launch_mode') == 'Pre-launch' && $this->Auth->user('role_id') != ConstUserTypes::Admin) || (Configure::read('site.launch_mode') == 'Private Beta' && !$this->Auth->user('id'))) {
            if (!empty($this->request->params['ext']) && $this->request->params['ext'] == 'rss') {
                $this->redirect(array(
                    'controller' => 'items',
                    'action' => 'search',
                    'admin' => false
                ));
            }
            $this->layout = 'subscription';
            $this->pageTitle = Configure::read('site.launch_mode');
        }
    }
    public function index($hash_keyword = '', $salt = '')
    {
        // <-- For iPhone App code
        if ($this->RequestHandler->prefers('json') && ($this->request->is('post'))){
            $this->request->data['Item'] = $this->request->data;
            if(count($this->request->data['Item']['category']) > 0){
                $cat_array = $par_cat = array();
                foreach($this->request->data['Item']['category'] as $catgory){
                  $sub_categories = $this->Item->Category->find('list', array(
					'conditions' => array(
						'Category.parent_id' => $catgory,
						'Category.is_active' => 1
					) ,
					'fields' => array(	
                        'Category.id'
					),
                    'order' => array(
						'Category.id' => 'ASC',
					) ,
					'recursive' => -1   
				));
                 $cat_array[$catgory] = array_values($sub_categories);
                 $par_cat[$catgory] = 1;
                }
                $this->request->data['Item']['parent_category_id'] = $par_cat;
                $this->request->data['Item']['Category'] = $cat_array;
            }
		}
		$hash = $this->_redirectPOST2NamedJson(array(
			 'cityName',
			 'city',
			 'latitude',
			 'longitude',
			 'sw_latitude',
			 'ne_longitude',
			 'sw_longitude',
			 'ne_latitude',
			 'slug',
			 'from',
			 'keyword',
			 'to',
			 'additional_guest',
			 'type',
			 'language',
			 'Category',
			 'network_level',
			 'is_flexible',
			 'range_from',
			 'range_to',
		 ));
		if ($this->RequestHandler->prefers('json') && ($this->request->is('post'))){
			$hash_results = explode('/',$hash);
			$hash_keyword = $hash_results[0];
			$salt = $hash_results[1];
		}
        $this->pageTitle = Configure::read('item.alt_name_for_item_plural_caps');
        $search_keyword = array();
        if ($this->RequestHandler->isAjax() && !isset($this->request->params['named']['share']) && ((!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'lst_my_items') || empty($this->request->params['named']['type']))) {
            $this->set('search', 'map');
        }
        $is_city = $is_searching = true;
        $current_latitude = $current_longitude = $query_string = '';
        if (!empty($hash_keyword) && !empty($salt)) {
            $salt1 = hexdec($hash_keyword) +786;
            $salt1 = substr(dechex($salt1) , 0, 2);
            if ($salt1 != $salt) {
                $this->redirect(array(
                    'controller' => 'items',
                    'action' => 'search',
                ));
            }
            $named_array = $this->Item->getSearchKeywords($hash_keyword, $salt);
            $search_keyword['named'] = array_merge($this->request->params['named'], $named_array);
            $this->request->params['named']['type'] = !empty($search_keyword['named']['type']) ? $search_keyword['named']['type'] : 'search';
            $is_city = false;
        } else {
            $CityList = array();
            if (!empty($this->request->params['named']['city'])) {
                $CityList = $this->Item->City->find('first', array(
                    'conditions' => array(
                        'City.slug' => $this->request->params['named']['city'],
                    ) ,
                    'recursive' => -1
                ));
            }
            //direct url access without hash ans salt, so we are forming querystring and stroed in search for as normal process
            if (!empty($CityList) && $this->request->params['named']['city'] != 'all') {
                $query_string = '/city:' . $CityList['City']['name'];
                $query_string.= '/cityname:' . $CityList['City']['name'];
                $query_string.= '/latitude:' . $CityList['City']['latitude'];
                $query_string.= '/longitude:' . $CityList['City']['longitude'];
            } else {
                $is_searching = false;
                $query_string = '/city:';
                $query_string.= '/cityname:';
                $query_string.= '/latitude:';
                $query_string.= '/longitude:';
            }
            $query_string.= '/from:' . date('Y-m-d');
            $query_string.= '/to:' . getToDate(date('Y-m-d'));
            $query_string.= '/additional_guest:1';
            $query_string.= '/range_from:0';
            $query_string.= '/deposit_from:0';
            $query_string.= '/is_flexible:1';
            if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'collection') {
                $query_string.= '/type:collection';
                if (!empty($this->request->params['named']['slug'])) {
                    $query_string.= '/slug:' . $this->request->params['named']['slug'];
                }
            } else if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'user') {
                $query_string.= '/type:user';
                if (!empty($this->request->params['named']['slug'])) {
                    $query_string.= '/slug:' . $this->request->params['named']['slug'];
                }
            } else {
                $query_string.= '/type:search';
                $this->request->params['named']['type'] = !empty($this->request->params['named']['type']) ? $this->request->params['named']['type'] : '';
            }
            $searchkeyword['SearchKeyword']['keyword'] = $query_string;
            App::import('Model', 'Items.SearchKeyword');
            $this->SearchKeyword = new SearchKeyword();
            $this->SearchKeyword->save($searchkeyword, false);
            $keyword_id = $this->SearchKeyword->getLastInsertId();
            //maintain in search log
            $searchlog = array();
            $searchlog['SearchLog']['search_keyword_id'] = $keyword_id;
            App::import('Model', 'Items.SearchLog');
            $this->SearchLog = new SearchLog();
            $searchlog['SearchLog']['ip_id'] = $this->SearchLog->toSaveIp();
            if ($this->Auth->user('id')) {
                $searchlog['SearchLog']['user_id'] = $this->Auth->user('id');
            }
            $this->SearchLog->save($searchlog, false);
            $salt = $keyword_id+786;
            $hash_query_string = '/' . dechex($keyword_id) . '/' . substr(dechex($salt) , 0, 2);
            if (empty($this->request->params['named']['city']) ) {
                $this->request->params['pass']['0'] = dechex($keyword_id);
                $this->request->params['pass']['1'] = substr(dechex($salt) , 0, 2);
            }
            if (!empty($CityList) && $this->request->params['named']['city'] != 'all') {
                $search_keyword['named']['cityname'] = $CityList['City']['name'];
                $search_keyword['named']['latitude'] = $CityList['City']['latitude'];
                $search_keyword['named']['longitude'] = $CityList['City']['longitude'];
            } else {
                $search_keyword['named']['cityname'] = '';
                $search_keyword['named']['latitude'] = '';
                $search_keyword['named']['longitude'] = '';
            }
            $search_keyword['named']['from'] = date('Y-m-d');
            $search_keyword['named']['to'] = getToDate(date('Y-m-d'));
            $search_keyword['named']['is_flexible'] = 1; // default flexible
        }
        $conditions = array();
        $conditions['Item.admin_suspend'] = 0;
        $conditions['Item.is_approved'] = 1;
        $conditions['Item.is_available'] = 1;
        $conditions['Item.is_active'] = 1;
        $conditions['Item.is_paid'] = 1;
        $conditions['Item.is_completed'] = 1;
        if (isPluginEnabled('SocialMarketing') && $this->Auth->user('id')) {		
            $user_followers = $this->Item->User->UserFollower->find('list', array(
                'conditions' => array(
                    'UserFollower.user_id ' => $this->Auth->user('id') ,
                ) ,
                'fields' => array(
                    'UserFollower.id',
                    'UserFollower.followed_user_id',
                ) ,
                'recursive' => -1
            ));
            $conditions['AND'][] = array(
                'OR' => array(
                    array(
                        'Item.item_type_id' => 2,
                        'Item.user_id' => $user_followers
                    ) ,
                    array(
                        'Item.item_type_id' => 0
                    ) ,
                ) ,
            );
        } else {		
			$conditions ['Item.item_type_id'] = 0;			
			if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myitems' && $this->Auth->user('id')) {
				unset($conditions ['Item.item_type_id']);
			}			
        }
        $exact_match = array();
        $limit = !empty($this->request->params['named']['limit']) ? $this->request->params['named']['limit'] : '20';
        if (!empty($this->request->params['named']['user']) && !empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'user') {
            $is_searching = true;
            $conditions['Item.user_id'] = $this->request->params['named']['user'];
        }
        if (!empty($this->request->params['named']['category_id']) || !empty($this->request->params['named']['category'])) {
			if(!empty($this->request->params['named']['category_id'])) {
				$category_condition['Category.id'] = $this->request->params['named']['category_id'];
			}
			if(!empty($this->request->params['named']['category'])) {
				$category_condition['Category.slug'] = $this->request->params['named']['category'];
			}
            $filter_category = $this->Item->Category->find('first', array(
				'conditions' => $category_condition,
                'recursive' => -1
            ));
            if (!empty($filter_category)) {
                if ($filter_category['Category']['parent_id'] != 0) {
                    $conditions['Item.category_id'] = $filter_category['Category']['id'];
                } else {
                    $categories = $this->Item->Category->find('list', array(
                        'conditions' => array(
                            'Category.parent_id' => $filter_category['Category']['id'],
							'Category.is_active' => 1
                        ) ,
                        'recursive' => -1
                    ));
                    $conditions['Item.category_id'] = array_keys($categories);
                }
            }
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'collection') {
            $slug = !empty($this->request->params['named']['slug']) ? $this->request->params['named']['slug'] : (!empty($search_keyword['named']['slug']) ? $search_keyword['named']['slug'] : '');
            if (!empty($slug)) {
                $is_searching = true;
                $collection = array();
                if (isPluginEnabled('Collections')) {
                    $collection = $this->Item->Collection->find('first', array(
                        'conditions' => array(
                            'Collection.slug' => $slug
                        ) ,
                        'fields' => array(
                            'Collection.id',
                        ) ,
                        'recursive' => -1
                    ));
					if (empty($collection)) {
						if ($this->RequestHandler->prefers('json')) {
							$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
						} else {
							throw new NotFoundException(__l('Invalid request'));
						}
					}
					$item_ids = $this->Item->CollectionsItem->find('list', array(
						'conditions' => array(
							'CollectionsItem.collection_id' => $collection['Collection']['id'],
						) ,
						'fields' => array(
							'CollectionsItem.id',
							'CollectionsItem.item_id',
						) ,
						'recursive' => -1
					));
					if (count($item_ids) > 0) {
						$conditions['Item.id'] = $item_ids;
					}					
					$collections = array();
                    $collections = $this->Item->Collection->find('first', array(
                        'conditions' => array(
                            'Collection.id' => $collection['Collection']['id'],
                        ) ,
                        'fields' => array(
                            'Collection.title',
                            'Collection.slug',
                            'Collection.description',
                            'Collection.item_count',
                            'Collection.country_count',
                            'Collection.city_count',
                        ) ,
                        'recursive' => -1
                    ));
					$this->set('collections', $collections);
					$this->set('collection_description', $collections['Collection']['description']);
					$this->set('item_count', $collections['Collection']['item_count']);
					$this->set('country_count', $collections['Collection']['country_count']);
					$this->set('city_count', $collections['Collection']['city_count']);
					$this->pageTitle = 'Collection - ' . $collections['Collection']['title'];					
                }
                // filter search for collection
                $language = isset($search_keyword['named']['language']) ? $search_keyword['named']['language'] : '';
                $category = isset($search_keyword['named']['category']) ? $search_keyword['named']['category'] : '';
                $network_level = isset($search_keyword['named']['network_level']) ? $search_keyword['named']['network_level'] : '';
                $rangefrom = isset($search_keyword['named']['range_from']) ? $search_keyword['named']['range_from'] : '0';
                $rangeto = isset($search_keyword['named']['range_to']) ? $search_keyword['named']['range_to'] : '300+';
                $depositfrom = isset($search_keyword['named']['deposit_from']) ? $search_keyword['named']['deposit_from'] : '0';
                $depositto = isset($search_keyword['named']['deposit_to']) ? $search_keyword['named']['deposit_to'] : '300+';
                if (!empty($language)) {
                    $this->request->data['Item']['language'] = explode(',', $language);
                    $host_languages = $this->Item->User->UserProfile->find('list', array(
                        'conditions' => array(
                            'UserProfile.language_id' => $this->request->data['Item']['language']
                        ) ,
                        'fields' => array(
                            'UserProfile.id',
                            'UserProfile.user_id'
                        ) ,
                        'recursive' => -1,
                    ));
                    $conditions['Item.user_id'] = $host_languages;
                }
                if (!empty($network_level)) {
                    $this->request->data['Item']['network_level'] = explode(',', $network_level);
                    $tmp_user_ids = array();
                    foreach($this->request->data['Item']['network_level'] as $tmp_network_level) {
                        if (!empty($_SESSION['network_level'][$tmp_network_level])) {
                            foreach($_SESSION['network_level'][$tmp_network_level] as $session_network_level) {
                                $tmp_user_ids[] = $session_network_level;
                            }
                        }
                    }
                    if (!empty($tmp_user_ids)) {
                        $conditions['Item.user_id'] = $tmp_user_ids;
                    } else {
                        $conditions['Item.user_id'] = '';
                    }
                }
                if (!empty($item_list) && count($item_list) > 0) {
                    $conditions['Item.id'] = array_intersect($item_ids, $item_list);
                }
                if (!empty($rangefrom)) {
                    $conditions['AND'][] = array(
                        'OR' => array(
                            array(
                                'Item.is_people_can_book_my_time' => 1,
                                'Item.minimum_price >=' => $rangefrom,
                                'Item.minimum_price <=' => (!empty($rangeto) && $rangeto != '300+') ? $rangeto : '100000',
                            ) ,
                            array(
                                'Item.is_sell_ticket' => 1,
                                'Item.minimum_price >=' => $rangefrom,
                                'Item.minimum_price <=' => (!empty($rangeto) && $rangeto != '300+') ? $rangeto : '100000',
                            ) ,
                        ) ,
                    );
                    $exact_match['Item.minimum_price >='] = $rangefrom;
                }
                if (!empty($search_keyword['named']['keyword'])) {
                    $conditions['Item.title LIKE '] = '%' . $search_keyword['named']['keyword'] . '%';
                }
                if (!empty($category)) {
                    $this->request->data['Item']['Category'] = explode(',', $category);
                    if (count($this->request->data['Item']['Category']) > 0) {
                        $conditions['Item.category_id'] = $this->request->data['Item']['Category'];
                    }
                }
                if (!empty($search_keyword['named']['sw_latitude'])) {
                    $lon1 = round($search_keyword['named']['sw_longitude'], 6);
                    $lon2 = round($search_keyword['named']['ne_longitude'], 6);
                    $lat1 = round($search_keyword['named']['sw_latitude'], 6);
                    $lat2 = round($search_keyword['named']['ne_latitude'], 6);
                    $conditions['Item.latitude BETWEEN ? AND ?'] = array(
                        $lat1,
                        $lat2
                    );
                    $conditions['Item.longitude BETWEEN ? AND ?'] = array(
                        $lon1,
                        $lon2
                    );
                }
            }
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'favorite') {
            if (!$this->Auth->user('id')) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $is_searching = true;
            $itemFavorites = array();
            if (isPluginEnabled('ItemFavorites')) {
                $itemFavorites = $this->Item->ItemFavorite->find('list', array(
                    'conditions' => array(
                        'ItemFavorite.user_id' => $this->Auth->user('id')
                    ) ,
                    'fields' => array(
                        'ItemFavorite.item_id'
                    ) ,
                    'recursive' => -1,
                ));
            }
            //if (!empty($itemFavorites)) {
            $conditions['Item.id'] = $itemFavorites;
            //}
            $user = $this->Item->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1,
            ));
            $this->set('user', $user);
        }
        if (!empty($this->request->params['named']['user_id']) && !empty($this->request->params['named']['item_id'])) {
            $conditions['Item.user_id'] = $this->request->params['named']['user_id'];
            $conditions['Item.id !='] = $this->request->params['named']['item_id'];
        }
        if (!empty($this->request->params['named']['item']) && $this->request->params['named']['item'] == 'my_items') {
            $requests = array();
            if (isPluginEnabled('Requests')) {
                $requests = $this->Item->ItemsRequest->Request->find('first', array(
                    'conditions' => array(
                        'Request.id' => $this->request->params['named']['request_id'],
                    ) ,
                    'recursive' => -1
                ));
            }
            $this->set('request_name', $requests['Request']['title']);
            $conditions['Item.user_id'] = $this->Auth->user('id');
            //distance based search
            $dist = Configure::read('site.exact_distance_limit') /1.60934; // 2 kms
			$lat1 = $lat2 = $lon1 = $lon2 = '';
			if(!empty($this->request->params['named']['request_latitude'])) {
				$this->request->params['named']['request_latitude'] = round($this->request->params['named']['request_latitude'], 6);
				$this->request->params['named']['request_longitude'] = round($this->request->params['named']['request_longitude'], 6);
				$lon1 = $this->request->params['named']['request_longitude']-$dist/abs(cos(deg2rad($this->request->params['named']['request_latitude'])) *69);
				$lon2 = $this->request->params['named']['request_longitude']+$dist/abs(cos(deg2rad($this->request->params['named']['request_latitude'])) *69);
				$lat1 = $this->request->params['named']['request_latitude']-($dist/69);
				$lat2 = $this->request->params['named']['request_latitude']+($dist/69);
			}
            $conditions['Item.latitude BETWEEN ? AND ?'] = array(
                $lat1,
                $lat2
            );
            $conditions['Item.longitude BETWEEN ? AND ?'] = array(
                $lon1,
                $lon2
            );
            //exact match items finder
            $match_conditions = array();
            $match_conditions['Item.user_id'] = $this->Auth->user('id');
            $match_conditions['Item.is_active'] = 1;
            $match_conditions['Item.latitude BETWEEN ? AND ?'] = array(
                $lat1,
                $lat2
            );
            $match_conditions['Item.longitude BETWEEN ? AND ?'] = array(
                $lon1,
                $lon2
            );
            $days = getFromToDiff($requests['Request']['from'], getToDate($requests['Request']['to']));
            $match_conditions['Item.accommodates >='] = $requests['Request']['accommodates'];
            $match_conditions['Item.accommodates !='] = 0;
            // check from date booked or not for this request
            $booking_conditions = array();
            $booking_conditions['ItemUser.item_user_status_id'] = array(
                ConstItemUserStatus::Confirmed,
            );
            $booking_conditions['ItemUser.from <='] = getToDate($requests['Request']['to']);
            $booking_conditions['ItemUser.to >='] = $requests['Request']['from'];
            $booking_list = $this->Item->ItemUser->find('list', array(
                'conditions' => $booking_conditions,
                'fields' => array(
                    'ItemUser.id',
                    'ItemUser.item_id'
                ) ,
                'recursive' => -1
            ));
            $custom_conditions['CustomPricePerNight.is_available'] = ConstItemStatus::Available;
            $custom_conditions['CustomPricePerNight.start_date <='] = getToDate($requests['Request']['to']);
            $custom_conditions['CustomPricePerNight.end_date >='] = $requests['Request']['from'];
            $not_available_list = $this->Item->CustomPricePerNight->find('list', array(
                'conditions' => $custom_conditions,
                'fields' => array(
                    'CustomPricePerNight.id',
                    'CustomPricePerNight.item_id'
                ) ,
                'recursive' => -1
            ));
            $booking_list = array_merge($booking_list, $not_available_list);
            if (!empty($booking_list)) {
                $match_conditions['NOT']['Item.id'] = $booking_list;
            }
            $available_list = $this->Item->find('list', array(
                'conditions' => $match_conditions,
                'fields' => array(
                    'Item.id'
                ) ,
                'recursive' => -1
            ));
            $this->set('available_list', $available_list);
            $limit = 9;
        }
        $fields = '';
        //Nearby items
        if (!empty($this->request->params['named']['city_id']) && !empty($this->request->params['named']['item_id'])) {
            $conditions['Item.id !='] = $this->request->params['named']['item_id'];
            $nearby_item = $this->Item->find('first', array(
                'conditions' => array(
                    'Item.id' => $this->request->params['named']['item_id']
                ) ,
                'recursive' => -1,
            ));
            //distance based search
            $nearby_dist = Configure::read('site.distance_limit') /1.60934; // 10 kms
            $nearby_item['Item']['latitude'] = round($nearby_item['Item']['latitude'], 6);
            $nearby_item['Item']['longitude'] = round($nearby_item['Item']['longitude'], 6);
            $nearby_lon1 = $nearby_item['Item']['longitude']-$nearby_dist/abs(cos(deg2rad($nearby_item['Item']['latitude'])) *69);
            $nearby_lon2 = $nearby_item['Item']['longitude']+$nearby_dist/abs(cos(deg2rad($nearby_item['Item']['latitude'])) *69);
            $nearby_lat1 = $nearby_item['Item']['latitude']-($nearby_dist/69);
            $nearby_lat2 = $nearby_item['Item']['latitude']+($nearby_dist/69);
            $conditions['Item.latitude BETWEEN ? AND ?'] = array(
                $nearby_lat1,
                $nearby_lat2
            );
            $conditions['Item.longitude BETWEEN ? AND ?'] = array(
                $nearby_lon1,
                $nearby_lon2
            );
            $db = ConnectionManager::getDataSource('default');
            $fields = '3956 * 2 * ASIN(SQRT(  POWER(SIN((' . $db->startQuote . 'Item' . $db->endQuote . '.' . $db->startQuote . 'latitude' . $db->endQuote . ' - ' . $nearby_item['Item']['latitude'] . ') * pi()/180 / 2), 2) + COS(' . $db->startQuote . 'Item' . $db->endQuote . '.' . $db->startQuote . 'latitude' . $db->endQuote . ' * pi()/180) *  COS(' . $nearby_item['Item']['latitude'] . ' * pi()/180) * POWER(SIN((' . $db->startQuote . 'Item' . $db->endQuote . '.' . $db->startQuote . 'longitude' . $db->endQuote . ' - ' . $nearby_item['Item']['longitude'] . ') * pi()/180 / 2), 2)  )) as distance';
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myitems') {
            $this->request->params['pass'] = array();
            $this->pageTitle = __l('My') . ' ' . Configure::read('item.alt_name_for_item_plural_caps');
            if (!$this->Auth->user('id')) {
				 $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login',
                    'admin' => false
                ));
            }
            $conditions['Item.user_id'] = $this->Auth->user('id');
            $is_city = false;
            //unset default conditions
            unset($conditions['Item.admin_suspend']);
            unset($conditions['Item.is_approved']);
            unset($conditions['Item.is_active']);
            unset($conditions['Item.is_paid']);
			unset($conditions['AND']);
            if (isset($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'pending') {
                $conditions['Item.is_paid'] = 0;
            } elseif (isset($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'active') {
                $conditions['Item.is_active'] = 1;
            } elseif (isset($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'inactive') {
                $conditions['Item.is_active'] = 0;
            } elseif (isset($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'waiting_for_approval') {
                $conditions['Item.is_approved'] = 0;
            }
            //Count Querys
            $this->set('all_count', $this->Item->find('count', array(
                'conditions' => array(
                    'Item.user_id' => $this->Auth->user('id'),
                    'Item.is_available' => 1,
					'Item.is_completed' => 1
                ) ,
                'recursive' => -1
            )));
            $this->set('active_count', $this->Item->find('count', array(
                'conditions' => array(
                    'Item.user_id' => $this->Auth->user('id') ,
                    'Item.is_active' => 1,
                    'Item.is_available' => 1,
					'Item.is_completed' => 1
                ) ,
                'recursive' => -1
            )));
            $this->set('inactive_count', $this->Item->find('count', array(
                'conditions' => array(
                    'Item.user_id' => $this->Auth->user('id') ,
                    'Item.is_active' => 0,
                    'Item.is_available' => 1,
					'Item.is_completed' => 1
                ) ,
                'recursive' => -1
            )));
            $this->set('waiting_for_approval_count', $this->Item->find('count', array(
                'conditions' => array(
                    'Item.user_id' => $this->Auth->user('id') ,
                    'Item.is_approved' => 0,
                    'Item.is_available' => 1,
					'Item.is_completed' => 1
                ) ,
                'recursive' => -1
            )));
            $this->set('pending_count', $this->Item->find('count', array(
                'conditions' => array(
                    'Item.user_id' => $this->Auth->user('id') ,
                    'Item.is_paid' => 0,
                    'Item.is_available' => 1,
					'Item.is_completed' => 1
                ) ,
                'recursive' => -1
            )));
        } else {
            $exact_match = $conditions;
        }
        if (!empty($this->_prefixId) && $is_city) {
            if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'user') {
                $conditions['Item.city_id'] = $this->_prefixId;
            }
        }
        $this->Item->recursive = 2;
        $order = array();
        $this->set('search', 'normal');
        $conditions_fav = array();
        if ($this->Auth->user()) {
            $conditions_fav['ItemFavorite.user_id'] = $this->Auth->user('id');
        }
        // its called from item_users index
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'lst_my_items') {
            $conditions['Item.user_id'] = $this->Auth->user('id');;
        } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'related') {
            $is_searching = true;
            // @todo "What goodies I can provide (guest)"
            if (!empty($this->request->params['named']['request_latitude']) && !empty($this->request->params['named']['request_longitude'])) {
                //distance based search
                $dist = 10; // 10 kms
                $this->request->params['named']['request_longitude'] = round($this->request->params['named']['request_longitude'], 6);
                $this->request->params['named']['request_latitude'] = round($this->request->params['named']['request_latitude'], 6);
                $lon1 = $this->request->params['named']['request_longitude']-$dist/abs(cos(deg2rad($this->request->params['named']['request_latitude'])) *69);
                $lon2 = $this->request->params['named']['request_longitude']+$dist/abs(cos(deg2rad($this->request->params['named']['request_latitude'])) *69);
                $lat1 = $this->request->params['named']['request_latitude']-($dist/69);
                $lat2 = $this->request->params['named']['request_latitude']+($dist/69);
                $conditions['Item.latitude BETWEEN ? AND ?'] = array(
                    $lat1,
                    $lat2
                );
                $conditions['Item.longitude BETWEEN ? AND ?'] = array(
                    $lon1,
                    $lon2
                );
            }
            $limit = 5;
            $conditions['Item.user_id !='] = $this->Auth->user('id');
            $this->set('search', 'map');
        }
        if (!empty($this->request->params['named']['sortby'])) {
            if ($this->request->params['named']['sortby'] == 'distance') {
                $order = array(
                    'distance' => 'asc'
                );
            } elseif ($this->request->params['named']['sortby'] == 'favorites') {
                $order = array(
                    'Item.item_favorite_count' => 'DESC'
                );
            } elseif ($this->request->params['named']['sortby'] == 'high') {
                $order = array(
                    'Item.minimum_price' => 'ASC'
                );
            } elseif ($this->request->params['named']['sortby'] == 'low') {
                $order = array(
                    'Item.minimum_price' => 'DESC'
                );
            } elseif ($this->request->params['named']['sortby'] == 'recent') {
                $order = array(
                    'Item.id' => 'DESC'
                );
            } elseif ($this->request->params['named']['sortby'] == 'featured') {
                $order = array(
                    'Item.is_featured' => 'DESC'
                );
            } elseif ($this->request->params['named']['sortby'] == 'reviews') {
                $order = array(
                    'Item.positive_feedback_count' => 'desc'
                );
            }
        } else {
            if (!empty($this->request->params['named']['latitude']) && !empty($this->request->params['named']['longitude'])) {
                $order = array(
                    'distance' => 'asc'
                );
            }
        }
        if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'search') {
            $booking_conditions = array();
            $current_latitude = !empty($search_keyword['named']['latitude']) ? round($search_keyword['named']['latitude'], 6) : '';
            $current_longitude = !empty($search_keyword['named']['longitude']) ? round($search_keyword['named']['longitude'], 6) : '';
            $from = !empty($search_keyword['named']['from']) ? $search_keyword['named']['from'] : '';
            $to = !empty($search_keyword['named']['to']) ? $search_keyword['named']['to'] : '';
            $to_cdn = $to;
            if (!empty($to) && !empty($from)) $days = getFromToDiff($from, $to);
            if (!empty($search_keyword['named']['latitude'])) {
                $this->pageTitle.= ' - Search - ' . $search_keyword['named']['cityname'];
            }
            $additional_guest = isset($search_keyword['named']['additional_guest']) ? $search_keyword['named']['additional_guest'] : 1;
            $category = isset($search_keyword['named']['category']) ? $search_keyword['named']['category'] : '';
            $language = isset($search_keyword['named']['language']) ? $search_keyword['named']['language'] : '';
            $network_level = isset($search_keyword['named']['network_level']) ? $search_keyword['named']['network_level'] : '';
            $rangefrom = isset($search_keyword['named']['range_from']) ? $search_keyword['named']['range_from'] : '0';
            $rangeto = isset($search_keyword['named']['range_to']) ? $search_keyword['named']['range_to'] : '300+';
            $depositfrom = isset($search_keyword['named']['deposit_from']) ? $search_keyword['named']['deposit_from'] : '0';
            $depositto = isset($search_keyword['named']['deposit_to']) ? $search_keyword['named']['deposit_to'] : '300+';
            if (!empty($language)) {
                $this->request->data['Item']['language'] = explode(',', $language);
                $host_languages = $this->Item->User->UserProfile->find('list', array(
                    'conditions' => array(
                        'UserProfile.language_id' => $this->request->data['Item']['language']
                    ) ,
                    'fields' => array(
                        'UserProfile.id',
                        'UserProfile.user_id'
                    )
                ));
                $conditions['Item.user_id'] = $host_languages;
            }
            if (!empty($network_level)) {
                $this->request->data['Item']['network_level'] = explode(',', $network_level);
                $tmp_user_ids = array();
                foreach($this->request->data['Item']['network_level'] as $tmp_network_level) {
                    if (!empty($_SESSION['network_level'][$tmp_network_level])) {
                        foreach($_SESSION['network_level'][$tmp_network_level] as $session_network_level) {
                            $tmp_user_ids[] = $session_network_level;
                        }
                    }
                }
                if (!empty($tmp_user_ids)) {
                    $conditions['Item.user_id'] = $tmp_user_ids;
                } else {
                    $conditions['Item.user_id'] = '';
                }
            }
            if (!empty($rangefrom)) {
                $conditions['AND'][] = array(
                    'OR' => array(
                        array(
                            'Item.is_people_can_book_my_time' => 1,
                            'Item.minimum_price >=' => $rangefrom,
                            'Item.minimum_price <=' => (!empty($rangeto) && $rangeto != '300+') ? $rangeto : '100000',
                        ) ,
                        array(
                            'Item.is_sell_ticket' => 1,
                            'Item.minimum_price >=' => $rangefrom,
                            'Item.minimum_price <=' => (!empty($rangeto) && $rangeto != '300+') ? $rangeto : '100000',
                        ) ,
                    ) ,
                );
            }
			if($from != $to){
				$custom_conditions['AND'][] = array(
					'OR' => array(
						array(
							'CustomPricePerNight.end_date IS NULL'
						) ,
						array(
							'CustomPricePerNight.end_date >=' => $to
						) ,
					)
				);
				if($search_keyword['named']['is_flexible']){
					$custom_conditions['AND']['CustomPricePerNight.start_date <='] = $from;
				} else {
					$custom_conditions['AND']['CustomPricePerNight.start_date >='] = $from;
				}
				$custom_items = $this->Item->CustomPricePerNight->find('list', array(
					'conditions' => $custom_conditions,
					'fields' => array(
						'CustomPricePerNight.item_id',
						'CustomPricePerNight.item_id'
					),
					'recursive' => 1
				));
				if (count($custom_items) > 0) {
					$conditions['Item.id'] = $custom_items;
				}
			}
            if (!empty($category)) {
                $this->request->data['Item']['Category'] = explode(',', $category);
                if (count($this->request->data['Item']['Category']) > 0) {
                    $conditions['Item.category_id'] = $this->request->data['Item']['Category'];
                }
            }
            if (!empty($search_keyword['named']['latitude']) && !empty($search_keyword['named']['longitude'])) {
                //distance calcuation based on lat and lng
				$db = ConnectionManager::getDataSource('default');			
                $fields = '3956 * 2 * ASIN(SQRT(  POWER(SIN((' . $db->startQuote . 'Item' . $db->endQuote . '.' . $db->startQuote . 'latitude' . $db->endQuote . ' - ' . $current_latitude . ') * pi()/180 / 2), 2) + COS(' . $db->startQuote . 'Item' . $db->endQuote . '.' . $db->startQuote . 'latitude' . $db->endQuote . ' * pi()/180) *  COS(' . $current_latitude . ' * pi()/180) * POWER(SIN((' . $db->startQuote . 'Item' . $db->endQuote . '.' . $db->startQuote . 'longitude' . $db->endQuote . ' - ' . $current_longitude . ') * pi()/180 / 2), 2)  )) as distance';
            }
            if (!empty($search_keyword['named']['latitude']) && !empty($search_keyword['named']['longitude']) && empty($search_keyword['named']['sw_latitude'])) {
                if (isset($search_keyword['named']['latitude'])) {
                    $this->request->data['Item']['latitude'] = $search_keyword['named']['latitude'];
                }
                if (isset($search_keyword['named']['longitude'])) {
                    $this->request->data['Item']['longitude'] = $search_keyword['named']['longitude'];
                }
                if (isset($search_keyword['named']['cityname'])) {
                    $this->request->data['Item']['cityName'] = $search_keyword['named']['cityname'];
                }
                //distance based search
                if (isset($search_keyword['named']['is_flexible']) && !$search_keyword['named']['is_flexible']) {
                    $dist = Configure::read('site.exact_distance_limit') /1.60934;
                } else {
                    $dist = Configure::read('site.distance_limit') /1.60934; // 10 kms
                }
                $exact_dist = Configure::read('site.exact_distance_limit') /1.60934; // 10 kms
                $lon1 = $current_longitude-$dist/abs(cos(deg2rad($current_latitude)) *69);
                $lon2 = $current_longitude+$dist/abs(cos(deg2rad($current_latitude)) *69);
                $lat1 = $current_latitude-($dist/69);
                $lat2 = $current_latitude+($dist/69);
                //exact match finder
                $exact_lon1 = $current_longitude-$exact_dist/abs(cos(deg2rad($current_latitude)) *69);
                $exact_lon2 = $current_longitude+$exact_dist/abs(cos(deg2rad($current_latitude)) *69);
                $exact_lat1 = $current_latitude-($exact_dist/69);
                $exact_lat2 = $current_latitude+($exact_dist/69);
                if (!isset($conditions['Item.city_id'])) {
                    $conditions['Item.latitude BETWEEN ? AND ?'] = array(
                        $lat1,
                        $lat2
                    );
                    $conditions['Item.longitude BETWEEN ? AND ?'] = array(
                        $lon1,
                        $lon2
                    );
                    //exact match
                    $exact_match['Item.latitude BETWEEN ? AND ?'] = array(
                        $exact_lat1,
                        $exact_lat2
                    );
                    $exact_match['Item.longitude BETWEEN ? AND ?'] = array(
                        $exact_lon1,
                        $exact_lon2
                    );
                }
            } else {
                if (!empty($search_keyword['named']['sw_latitude'])) {
                    $lon1 = round($search_keyword['named']['sw_longitude'], 6);
                    $lon2 = round($search_keyword['named']['ne_longitude'], 6);
                    $lat1 = round($search_keyword['named']['sw_latitude'], 6);
                    $lat2 = round($search_keyword['named']['ne_latitude'], 6);
                    $conditions['Item.latitude BETWEEN ? AND ?'] = array(
                        $lat1,
                        $lat2
                    );
                    $conditions['Item.longitude BETWEEN ? AND ?'] = array(
                        $lon1,
                        $lon2
                    );
                }
            }
            if (!empty($search_keyword['named']['latitude']) && !empty($search_keyword['named']['longitude']) && empty($search_keyword['named']['sw_latitude'])) {
				$order['distance'] = 'ASC';
				$order['is_featured'] = 'desc';
            }
            if (!empty($this->request->params['named']['search'])) {
                $lon1 = round($this->request->params['named']['sw_longitude'], 6);
                $lon2 = round($this->request->params['named']['ne_longitude'], 6);
                $lat1 = round($this->request->params['named']['sw_latitude'], 6);
                $lat2 = round($this->request->params['named']['ne_latitude'], 6);
                $conditions['Item.latitude BETWEEN ? AND ?'] = array(
                    $lat1,
                    $lat2
                );
                $conditions['Item.longitude BETWEEN ? AND ?'] = array(
                    $lon1,
                    $lon2
                );
                $this->set('search', 'map');
            } else {
                if (!empty($this->request->params['named']['latitude']) && !empty($this->request->params['named']['longitude'])) {
                    //distance based search
                    $dist = Configure::read('site.distance_limit') /1.60934; // 10 kms
                    $lon1 = $current_longitude-$dist/abs(cos(deg2rad($current_latitude)) *69);
                    $lon2 = $current_longitude+$dist/abs(cos(deg2rad($current_latitude)) *69);
                    $lat1 = $current_latitude-($dist/69);
                    $lat2 = $current_latitude+($dist/69);
                    $conditions['Item.latitude BETWEEN ? AND ?'] = array(
                        $lat1,
                        $lat2
                    );
                    $conditions['Item.longitude BETWEEN ? AND ?'] = array(
                        $lon1,
                        $lon2
                    );
                }
            }
            if (!empty($search_keyword['named']['keyword'])) {
                $conditions['Item.title LIKE '] = '%' . $search_keyword['named']['keyword'] . '%';
            }
            //if dates are not flexible then strictly search given creteria
            $custom_conditions = array();
            if ((isset($this->request->params['named']['is_flexible']) && !$this->request->params['named']['is_flexible']) || (isset($search_keyword['named']['is_flexible']) && !$search_keyword['named']['is_flexible'])) {
                $booking_conditions['ItemUser.item_user_status_id'] = array(
                    ConstItemUserStatus::Confirmed,
                );
                $booking_conditions['ItemUser.from <='] = $to_cdn;
                $booking_conditions['ItemUser.to >='] = $from;
                $booking_list = $this->Item->ItemUser->find('list', array(
                    'conditions' => $booking_conditions,
                    'fields' => array(
                        'ItemUser.id',
                        'ItemUser.item_id'
                    ) ,
                    'recursive' => -1
                ));
                $custom_conditions['CustomPricePerNight.is_available'] = ConstItemStatus::Available;
                $custom_conditions['CustomPricePerNight.start_date <='] = $to_cdn;
                $custom_conditions['CustomPricePerNight.end_date >='] = $from;
                $not_available_list = $this->Item->CustomPricePerNight->find('list', array(
                    'conditions' => $custom_conditions,
                    'fields' => array(
                        'CustomPricePerNight.id',
                        'CustomPricePerNight.item_id'
                    ) ,
                    'recursive' => -1
                ));
                $booking_list = array_merge($booking_list, $not_available_list);
                $booked_ids = array();
                if (count($booking_list) > 0) {
                    foreach($booking_list as $booking) {
                        $booked_ids[] = $booking;
                    }
                }
                if (count($booked_ids) > 0) {
                    $conditions['NOT']['Item.id'] = $booked_ids;
                }
            }
            /*Exact match calculation creteria */
            // ----------------Start --------------
            $custom_conditions['CustomPricePerNight.is_available'] = ConstItemStatus::Available;
            $custom_conditions['CustomPricePerNight.start_date <='] = $to_cdn;
            $custom_conditions['CustomPricePerNight.end_date >='] = $from;
            $not_available_list = $this->Item->CustomPricePerNight->find('list', array(
                'conditions' => $custom_conditions,
                'fields' => array(
                    'CustomPricePerNight.id',
                    'CustomPricePerNight.item_id'
                ) ,
                'recursive' => -1
            ));
            $booking_list = $not_available_list;
            $booked_ids = array();
            if (count($booking_list) > 0) {
                foreach($booking_list as $booking) {
                    $booked_ids[] = $booking;
                }
            }
            $booked_ids = array_unique($booked_ids);
            $this->set('booked_item_ids', $booked_ids);
            $exact_ids = $this->Item->find('list', array(
                'conditions' => $exact_match,
                'fields' => array(
                    'Item.id',
                    'Item.id'
                ) ,
                'recursive' => -1,
            ));
            $exact_ids = array_unique($exact_ids);
            $this->set('exact_ids', $exact_ids);
            // --------------- ENd ------------------------
            $contain = array(
                'User' => array(
                    'fields' => array(
                        'User.username',
                        'User.id',
                    ) ,
                    'UserAvatar',
                    'UserComment' => array(
                        'PostedUser',
                        'limit' => 6,
                        'order' => array(
                            'UserComment.id DESC'
                        ) ,
                    ) ,
                ) ,
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
                'CustomPricePerNight'=> array(
					'CustomPricePerType',
                    'order' => array(
                        'CustomPricePerNight.id ASC'
                    )
                ),
            );
            if (isPluginEnabled('ItemFavorites')) {
                $contain['ItemFavorite'] = array(
                    'conditions' => $conditions_fav,
                    'fields' => array(
                        'ItemFavorite.id',
                        'ItemFavorite.user_id',
                        'ItemFavorite.item_id',
                    )
                );
            }
            if (!empty($this->request->params['ext']) && $this->request->params['ext'] == 'rss') {
                $total_item_count = $this->Item->find('count', array(
                    'conditions' => $conditions,
                    'recursive' => 3
                ));
                $limit = $total_item_count;
            }
            $this->paginate = array(
                'conditions' => array(
                    $conditions
                ) ,
                'contain' => $contain,
                'fields' => array(
                    'Item.id',
                    'Item.created',
                    'Item.modified',
                    'Item.user_id',
                    'Item.city_id',
                    'Item.state_id',
                    'Item.title',
                    'Item.slug',
                    'Item.description',
                    'Item.street_view',
                    'Item.accommodates',
                    'Item.address',
                    'Item.unit',
                    'Item.phone',
                    'Item.price_per_hour',
                    'Item.price_per_day',
                    'Item.price_per_week',
                    'Item.price_per_month',
                    'Item.additional_guest',
                    'Item.latitude',
                    'Item.longitude',
                    'Item.zoom_level',
                    'Item.item_view_count',
                    'Item.item_favorite_count',
                    'Item.item_feedback_count',
                    'Item.positive_feedback_count',
                    'Item.item_view_count',
                    'Item.is_system_flagged',
                    'Item.is_active',
                    'Item.is_paid',
                    'Item.is_featured',
                    'Item.is_people_can_book_my_time',
                    'Item.is_sell_ticket',
                    'Item.minimum_price',
                    'Item.is_have_definite_time',
                    $fields,
                ) ,
                'order' => $order,
                'limit' => $limit,
                'recursive' => 3,
            );
        } else {
            if (empty($order)) {
                $order = array(
                    'Item.id' => 'DESC'
                );
            }
            $this->paginate = array(
                'conditions' => array(
                    $conditions
                ) ,
                'contain' => array(
                    'User' => array(
                        'UserComment' => array(
                            'PostedUser',
                            'limit' => 6,
                            'order' => array(
                                'UserComment.id DESC'
                            ) ,
                        ) ,
                    ) ,
                    'ItemFavorite' => array(
                        'conditions' => $conditions_fav,
                        'fields' => array(
                            'ItemFavorite.id',
                            'ItemFavorite.user_id',
                            'ItemFavorite.item_id',
                        )
                    ) ,
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
                    'CustomPricePerNight'=> array(
						'order' => array(
							'CustomPricePerNight.id ASC'
						),
						'CustomPricePerType' => array(
							'limit' => 1
						)
					),
                ) ,
                'order' => $order,
                'limit' => $limit,
                'recursive' => 3,
            );
            $booked_ids = array();
            $exact_ids = array();
            $this->set('booked_item_ids', $booked_ids);
            $this->set('exact_ids', $exact_ids);
            $this->request->data['Item']['is_flexible'] = 1;
        }
        $total_item_count = $this->Item->find('count', array(
            'conditions' => $conditions,
            'recursive' => 0
        ));
        $this->set('total_result', $total_item_count);
        $this->set('search_keyword', $search_keyword);
        $language_lists = $this->Item->find('list', array(
            'conditions' => array(
                'Item.language_id !=' => 0
            ) ,
            'fields' => array(
                'Item.language_id'
            ) ,
            'recursive' => -1,
        ));
        $languages = $this->Item->User->UserProfile->Language->find('list', array(
            'conditions' => array(
                'Language.id' => $language_lists
            ) ,
            'order' => array(
                'Language.name' => 'asc'
            ) ,
            'recursive' => -1,
        ));
        $range_from = array();
        $range_to = array();
        $minimum = array();
        for ($j = 1; $j <= 10; $j = $j+1) {
            $minimum[$j] = $j;
        }
        for ($i = 1; $i <= 300; $i = $i+5) {
            $range_from[$i] = $i;
            $range_to[$i] = $i;
        }
        $range_to['300+'] = '300+';
        $range_from['300+'] = '300+';
        $this->set(compact('languages'));
        $this->set('range_from', $range_from);
        $this->set('range_to', $range_to);
        $this->set('minimum', $minimum);
        $this->set('current_latitude', $current_latitude);
        $this->set('current_longitude', $current_longitude);
        if (!Configure::read('item.is_enable_item_count')) {
            $is_searching = true;
        }
        $this->set('is_searching', $is_searching);
        // <-- For iPhone App code
        if ($this->RequestHandler->prefers('json') && $is_searching === true) {
            $languages = $this->Item->User->UserProfile->Language->find('all', array(
                                                            'conditions' => array(
                                                              'Language.id' => $language_lists
                                                            ) ,
                                                              'order' => array(
                                                                'Language.name' => 'asc'
                                                              ) ,
                                                                'recursive' => -1,
                                                            ));
            $response = Cms::dispatchEvent('Controller.Item.item', $this, array(
                'page' => 'search',
                'languages' => $languages
            ));
        }
        // For iPhone App code -->
        if ($this->RequestHandler->isAjax() && env('HTTP_X_PJAX') != 'true') {
            $this->set('search', 'map');
        }
        if (!isset($search_keyword['named']['range_to'])) {
            $this->request->data['Item']['range_to'] = '301';
        } else {
            $this->request->data['Item']['range_to'] = $search_keyword['named']['range_to'];
        }
        if (isset($search_keyword['named']['range_from'])) {
            $this->request->data['Item']['range_from'] = $search_keyword['named']['range_from'];
        }
        if (!empty($search_keyword['named']['is_flexible'])) {
            $this->request->data['Item']['is_flexible'] = $search_keyword['named']['is_flexible'];
        }
        if (!empty($search_keyword['named']['is_flexible'])) {
            $this->request->data['Item']['is_flexible'] = $search_keyword['named']['is_flexible'];
        }
        if (!empty($search_keyword['named']['additional_guest'])) {
            $this->request->data['Item']['additional_guest'] = $search_keyword['named']['additional_guest'];
        }
        if (isset($search_keyword['named']['from'])) {
            $this->request->data['Item']['from'] = $search_keyword['named']['from'];
        }
        if (isset($search_keyword['named']['to'])) {
            $this->request->data['Item']['to'] = $search_keyword['named']['to'];
        }
        if (isset($search_keyword['named']['latitude'])) {
            $this->request->data['Item']['latitude'] = $search_keyword['named']['latitude'];
        }
        if (isset($search_keyword['named']['longitude'])) {
            $this->request->data['Item']['longitude'] = $search_keyword['named']['longitude'];
        }
        if (isset($search_keyword['named']['cityname'])) {
            $this->request->data['Item']['cityName'] = $search_keyword['named']['cityname'];
        }
        if (!empty($this->request->params['named']['request_id'])) {
            $this->request->data['Item']['request_id'] = $this->request->params['named']['request_id'];
        }
        //From/to date valid check
        if (isset($this->request->data['Item']['from']) && isset($this->request->data['Item']['to'])) if ($this->request->data['Item']['from'] < date('Y-m-d') || $this->request->data['Item']['from'] > $this->request->data['Item']['to'] || $this->request->data['Item']['to'] < date('Y-m-d')) {
            if ($this->RequestHandler->prefers('json')) {
                $response = array(
                    'error' => 1,
                    'message' => __l('From/To date is invalid')
                );
                $this->view = 'Json';
                $this->set('json', $response);
            } else {
                $this->Session->setFlash(__l('From/To date is invalid') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'items',
                    'action' => 'index',
                ));
            }
        }
		if(isset($this->request->params['named']['type']) && empty($this->request->params['named']['type'])){
			unset($this->request->params['named']['type']);
		}
        $items = $this->paginate();
        // social connections list
        if ($this->Auth->user('id') && $this->Auth->user('is_show_facebook_friends') && $this->Auth->user('is_facebook_friends_fetched')) {
            $social_conditions['Item.user_id != '] = $this->Auth->user('id');
            $social_conditions = array_merge($conditions, $social_conditions);
            $tmpItems = $this->Item->find('list', array(
                'conditions' => $social_conditions,
                'fields' => array(
                    'Item.id',
                    'Item.user_id',
                ) ,
                'recursive' => -1
            ));
            $tmpUserItemCount = array_count_values($tmpItems);
            if (!empty($tmpItems)) {
                $user_ids = $this->Item->User->find('list', array(
                    'conditions' => array(
                        'User.id' => array_keys($tmpUserItemCount) ,
                        'User.is_facebook_friends_fetched' => 1
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.network_fb_user_id',
                    ) ,
                    'recursive' => -1,
                ));
                if (!empty($user_ids)) {
                    $network_level = $this->Item->getFacebookFriendLevel($user_ids);
                    $this->set('network_level', $network_level);
                    $network_item_count = array();
                    $network_level_session = array();
                    foreach($network_level as $tmp_user_id => $level) {
                        if (isset($network_item_count[$level])) {
                            $network_item_count[$level]+= $tmpUserItemCount[$tmp_user_id];
                        } else {
                            $network_level_session[$level][] = $tmp_user_id;
                            $network_item_count[$level] = $tmpUserItemCount[$tmp_user_id];
                        }
                    }
                    if (empty($_SESSION['network_level'])) {
                        $_SESSION['network_level'] = $network_level_session;
                    }
                    $this->set('network_item_count', $network_item_count);
                }
            }
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'collection') {
            $slug = !empty($this->request->params['named']['slug']) ? $this->request->params['named']['slug'] : (!empty($search_keyword['named']['slug']) ? $search_keyword['named']['slug'] : '');
            $collection = array();
            if (isPluginEnabled('Collections')) {
                $collection = $this->Item->Collection->find('first', array(
                    'conditions' => array(
                        'Collection.slug' => $slug
                    ) ,
                    'fields' => array(
                        'Collection.id',
                    ) ,
                    'recursive' => -1
                ));
            }
            $i = 0;
            if (!empty($items)) {
                foreach($items as $item) {
                    $collections = $this->Item->CollectionsItem->find('first', array(
                        'conditions' => array(
                            'CollectionsItem.item_id = ' => $item['Item']['id'],
                            'CollectionsItem.collection_id = ' => $collection['Collection']['id']
                        ) ,
                        'fields' => array(
                            'CollectionsItem.display_order',
                        ) ,
                        'recursive' => -1,
                    ));
                    $items[$i]['Item']['display_order'] = $collections['CollectionsItem']['display_order'];
                    $i++;
                }
            }
            //Sorting code start here
            // compare function
            function cmpi($a, $b)
            {
                global $sort_field;
                return strcmp($a['Item']['display_order'], $b['Item']['display_order']);
            }
            // do the array sorting
            if (!isset($this->request->params['named']['sortby']) && !empty($items)) {
                usort($items, 'cmpi');
            }
            //sorting code ends here

        }
        $this->set('items', $items);
        $categories = $this->Item->Category->find('all', array(
            'conditions' => array(
                'Category.parent_id' => 0,
				'Category.is_active' => 1
            ) ,
			'order' => array(
				'Category.name' => 'ASC'
			),
            'recursive' => -1
        ));
        $this->set('categories', $categories);
        if ($this->Auth->user('id') && !$this->Auth->user('is_facebook_friends_fetched')) {
            App::import('Vendor', 'facebook/facebook');
            $this->facebook = new Facebook(array(
                'appId' => Configure::read('facebook.app_id') ,
                'secret' => Configure::read('facebook.secrect_key') ,
                'cookie' => true
            ));
            $fb_return_url = Router::url(array(
                'controller' => 'users',
                'action' => 'fb_update',
                'admin' => false
            ) , true);
            $this->Session->write('fb_return_url', $fb_return_url);
            $fb_login_url = $this->facebook->getLoginUrl(array(
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true) ,
                'scope' => 'email,offline_access,publish_stream'
            ));
            $this->set('fb_login_url', $fb_login_url);
        }
        if (!empty($this->request->params['named']['user']) && !empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'user') {
            $this->render('index');
        }
        if ((!empty($this->request->params['named']['user_id']) && !empty($this->request->params['named']['item_id'])) || (!empty($this->request->params['named']['city_id']) && !empty($this->request->params['named']['item_id']))) {
            $this->set('near_by', 1);
            $this->render('index');
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'favorite') {
            $this->pageTitle = __l('Liked') . ' ' . Configure::read('item.alt_name_for_item_plural_caps');
            $this->render('index');
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myitems') {
            $chart_data = $this->Item->getBookingChart($this->Auth->user('id') , null);
            $moreActions = $this->Item->moreMyItemsActions;
            $this->set(compact('moreActions', 'chart_data'));
            $this->render('my_items');
        }
        if (!empty($this->request->params['named']['item']) && $this->request->params['named']['item'] == 'my_items') {
            $this->pageTitle = __l('Make an offer');
            $this->render('my-items-compact');
        }
        // its called from item_users index
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'lst_my_items') {
            $this->render('lst_my_items');
        }
    }
    function streetview($lat, $lng)
    {
        $this->set('lat', $lat);
        $this->set('lng', $lng);
    }
    function review_index()
    {
        $this->set('item_id', $this->request->params['named']['item_id']);
    }
    public function calendar_edit()
    {
        $id = $this->request->query['id'];
        $start = $this->request->query['start'];
        $end = $this->request->query['end'];
        $title = $this->request->query['title'];
        $description = isset($this->request->query['description']) ? $this->request->query['description'] : '';
        $model = isset($this->request->query['model']) ? $this->request->query['model'] : '';
        $item_id = isset($this->request->query['item_id']) ? $this->request->query['item_id'] : '';
        $current_status = isset($this->request->query['current_status']) ? $this->request->query['current_status'] : '';
        $price = isset($this->request->query['price']) ? $this->request->query['price'] : '';
        if (!empty($current_status)) {
            $this->request->data['status'] = $current_status;
        }
        $item_status_list = $this->Item->ItemUser->ItemUserStatus->find('list', array(
            'conditions' => array(
                'ItemUserStatus.id' => array(
                    16,
                    17,
                    18,
                    6
                ) ,
            ) ,
            'fields' => array(
                'ItemUserStatus.id',
                'ItemUserStatus.name'
            ) ,
            'recursive' => -1
        ));
        $this->set('id', $id);
        $this->set('item_start', $start);
        $this->set('item_end', $end);
        $this->set('item_title', $title);
        $this->set('item_id', $item_id);
        $this->set('item_description', $description);
        $this->set('item_model', $model);
        $this->set('price', $price);
        $this->set('current_status', $current_status);
        $this->set('item_status_list', $item_status_list);
    }
    public function calendar($type)
    {
        if (is_null($type)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        if (!empty($this->request->params['named']['item_id'])) {
            $id = $this->request->params['named']['item_id'];
        } else if (!empty($this->request->params['named']['ids'])) {
            $id = $this->request->params['named']['ids'];
        } else {
            $id = '';
        }
        $conditions = array();
        if (!empty($id)) {
            $conditions['Item.id'] = explode(',', $id);
        } else {
            if (!$this->Auth->user()) {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
            }
        }
        if (!empty($type) && $type == 'guest') {
            if (!empty($this->request->params['named']['month'])) {
                $month = $this->request->params['named']['month'];
            } else {
                $month = date('m');
            }
            if (!empty($this->request->params['named']['year'])) {
                $year = $this->request->params['named']['year'];
            } else {
                $year = date('Y');
            }
            // $data = $this->Item->_getCalendarBookingDates($id, $month, $year);
            $this->set('month', $month);
            $this->set('year', $year);
            $this->set('id', $id);
        } else if (!empty($type) && $type == 'guest_list') {
            if (!empty($this->request->params['named']['month'])) {
                $month = $this->request->params['named']['month'];
            } else {
                $month = date('m');
            }
            if (!empty($this->request->params['named']['year'])) {
                $year = $this->request->params['named']['year'];
            } else {
                $year = date('Y');
            }
            for ($i = 0; $i < 12; $i++) {
                $guest_lists[$i]['month'] = $month;
                $guest_lists[$i]['year'] = $year;
                $guest_lists[$i]['id'] = $id;
                $guest_lists[$i]['data'] = $this->Item->_getCalendarBookingDates($id, $month, $year);
                if ($month == 12) {
                    $month = 1;
                    $year = $year+1;
                } else {
                    $month++;
                }
            }
            $this->set('guest_lists', $guest_lists);
			if (!$this->RequestHandler->prefers('json')) {
				$this->render('guest_list_calendar');
			}
        }
        $this->set('id', $id);
        $this->set('type', $type);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json')) {
			$response = Cms::dispatchEvent('Controller.Item.calendar', $this, array());
		}		
    }
    public function datafeed()
    {
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Items.Wdcalendar');
        $this->Wdcalendar = new WdcalendarComponent($collection);
        App::import('Model', 'Items.ItemUser');
        $this->ItemUser = new ItemUser();
        if ((isset($this->request->params['named']['method'])) && $this->request->params['named']['method'] == 'guest') {
            $method = 'guest';
            $id = $this->request->params['named']['item_id'];
        } else {
            $method = $this->request->query['method'];
            $id = $this->request->params['named']['item_id'];
        }
        switch ($method) {
            case 'add':
				if(isset($_POST['is_sell_ticket']) && !empty($_POST['is_sell_ticket'])) {
					$sell_tickets = (array) json_decode($_POST['sell_tickets']);
					$custom_price_per_night_id = 0;
					$ret = $this->Wdcalendar->addAndUpdateEventCalendar($custom_price_per_night_id, $_POST['item_id'],  $sell_tickets, $_POST['is_available'], $_POST['custom_source_id']);
				} else {
					$ret = $this->Wdcalendar->addCalendar($_POST['CalendarStartTime'], $_POST['CalendarEndTime'], $_POST['CalendarTitle'], $_POST['CalendarTitle1'], $_POST['IsAllDayEvent'], $_POST['item_id'], $_POST['is_available'], $_POST['fromdt'], $_POST['todt'], $_POST['fromdt_time'], $_POST['todt_time'], $_POST['custom_source_id']);
				}
                break;

            case 'list':
                $view = $_POST['viewtype'];
                $return = $this->Wdcalendar->getDateIntervals($_POST['showdate'], $_POST['viewtype']);
				$viewObj = new View($this);
                if (!empty($id)) {
                    $ret = $this->Wdcalendar->listCalendar($return['start_date'], $return['end_date'], 'host', $view, $id, $viewObj);
                } else {
                    $ret = $this->Wdcalendar->listCalendar($return['start_date'], $return['end_date'], 'host', $view, null, $viewObj);
                }
                break;

            case 'remove':
                $ret = $this->Wdcalendar->removeCalendar($_POST['calendarId']);
                break;

            case 'update':
                if (!empty($_POST)) {
					if(isset($_POST['is_sell_ticket']) && !empty($_POST['is_sell_ticket'])) {
						if($_POST['status'] == ConstItemUserStatus::Confirmed || $_POST['status'] == ConstItemUserStatus::Rejected) {
							$order_id = $_POST['id'];
							$item_user_status_id = $_POST['status'];
							$this->ItemUser->updateStatus($order_id, $item_user_status_id);
							$ret = array();
							$ret['IsSuccess'] = true;
							$ret['Msg'] = 'Updated successfully';
						} else {
							$sell_tickets = (array) json_decode($_POST['sell_tickets']);
							$custom_price_per_night_id = $_POST['id'];
							$st = $_POST['stpartdate'];
							$et = $_POST['etpartdate'];
							$ret = $this->Wdcalendar->addAndUpdateEventCalendar($custom_price_per_night_id, $_POST['item_id'],  $sell_tickets, $_POST['status'], $_POST['custom_source_id'], $st, $et, $_POST['parent_id']);
						}
					} else {
						$item_id = $_POST['item_id'];
						$id = $_POST['id'];
						$st = $_POST['stpartdate'];
						$et = $_POST['etpartdate'];
						$fromdt = $_POST['stpartdate'];
						$todt = $_POST['etpartdate'];
						$price = 0;
						if (isset($_POST['price']) && !empty($_POST['price'])) {
							$price = $_POST['price'];
						}
						$price1 = 0;
						if (isset($_POST['price1']) && !empty($_POST['price1'])) {
							$price1 = $_POST['price1'];
						}
						$price2 = 0;
						if (isset($_POST['price2']) && !empty($_POST['price2'])) {
							$price2 = $_POST['price2'];
						}
						$price3 = 0;
						if (isset($_POST['price3']) && !empty($_POST['price3'])) {
							$price3 = $_POST['price3'];
						}
						$ret = $this->Wdcalendar->updateDetailedCalendar($id, $item_id, $st, $et, $price, $price1, $price2, $price3, $_POST['status'], $_POST['Description'], $_POST['model'], $_POST['colorvalue'], $_POST['timezone'],  $_POST['custom_source_id']);
					}
                }
                break;
        }
        if ((isset($this->request->params['named']['method'])) && $this->request->params['named']['method'] == 'guest') {
            $this->set('data', $data);
            $this->set('year', $year);
            $this->set('month', $month);
            $this->render('guest');
        }
        $this->view = 'Json';
        $this->set('json', $ret);
    }
    public function weather()
    {
        $city = $this->request->params['named']['city'];
        $request_url = 'http://www.google.com/ig/api?weather=' . $city . '';
        $results = array();
        $xml = simplexml_load_file($request_url) or die("Google Weather feed not loading");
        if (!isset($xml->weather->problem_cause)) {
            //Parse current conditions XML
            $results['current']['condition'] = (array)$xml->weather->current_conditions->condition['data'];
            $results['current']['temp'] = (array)$xml->weather->current_conditions->temp_f['data'];
            $results['current']['humidity'] = (array)$xml->weather->current_conditions->humidity['data'];
            $results['current']['wind'] = (array)$xml->weather->current_conditions->wind_condition['data'];
            $results['current']['icon'] = (array)$xml->weather->current_conditions->icon['data'];
            $results['current']['city'] = (array)$xml->weather->forecast_information->city['data'];
            //Parse four day outlook XML
            for ($i = 0; $i <= 3; $i++) {
                $results[$i]['day'] = (array)$xml->weather->forecast_conditions->$i->day_of_week['data'];
                $results[$i]['condition'] = (array)$xml->weather->forecast_conditions->$i->condition['data'];
                $results[$i]['low'] = (array)$xml->weather->forecast_conditions->$i->low['data'];
                $results[$i]['high'] = (array)$xml->weather->forecast_conditions->$i->high['data'];
                $results[$i]['icon'] = (array)$xml->weather->forecast_conditions->$i->icon['data'];
            }
        }
        $this->view = 'Json';
        $this->set('json', $results);
    }
    public function get_info($id)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.id' => $id
            ) ,
            'contain' => array(
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.dir',
                        'Attachment.filename',
                        'Attachment.width',
                        'Attachment.height',
                        'Attachment.description'
                    )
                ) ,
                'Country' => array(
                    'fields' => array(
                        'Country.name',
                        'Country.iso_alpha2'
                    )
                ) ,
            ) ,
            'recursive' => 2,
        ));
		if(empty($item)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			} else {
				throw new NotFoundException(__l('Invalid request'));
			}
		}
        $this->set('item', $item);
        $this->layout = 'ajax';
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json')) {
			Cms::dispatchEvent('Controller.Item.GetItemInfo', $this, array());
		}
    }
    public function bookit($slug = null, $hash = null, $salt = null)
    {
		if (!$this->RequestHandler->isAjax() && !$this->RequestHandler->prefers('json')){
			$this->redirect(array(
				'controller' => 'items',
				'action' => 'view',
				$slug
			));			
		}
        if (is_null($slug)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        $conditions_fav = array();
        if ($this->Auth->user()) {
            $conditions_fav['ItemFavorite.user_id'] = $this->Auth->user('id');
        }
		if (!empty($hash) && !empty($salt)) {
            $salt1 = hexdec($hash) +786;
            $salt1 = substr(dechex($salt1) , 0, 2);
            if ($salt1 != $salt) {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					$this->redirect(array(
						'controller' => 'items',
						'action' => 'view',
						$slug
					));
				}
            }
            $named_array = $this->Item->getSearchKeywords($hash, $salt);
            $this->request->params['named'] = array_merge($this->request->params['named'], $named_array);
        }
        $contain = array(
            'User',
        );
        if (isPluginEnabled('ItemFavorites')) {
            $contain['ItemFavorite'] = array(
                'conditions' => $conditions_fav,
                'fields' => array(
                    'ItemFavorite.id',
                    'ItemFavorite.user_id',
                    'ItemFavorite.item_id',
                )
            );
        }
        $item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.slug' => $slug
            ) ,
            'contain' => $contain,
            'recursive' => 2,
        ));
		$chart_data = $this->Item->getBookingChart(null, $item['Item']['id']);
        $this->set('chart_data', $chart_data);
        $this->request->data['ItemUser']['item_id'] = $item['Item']['id'];
        $this->request->data['ItemUser']['price'] = $item['Item']['price_per_day'];
        $this->request->data['ItemUser']['item_slug'] = $item['Item']['slug'];
        $this->request->data['ItemUser']['item_name'] = $item['Item']['title'];
        $this->request->data['ItemUser']['booking_option'] = 'price_per_day';
		$this->set(compact('item'));
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$this->set('ItemUser', $this->request->data['ItemUser']);
			$response = Cms::dispatchEvent('Controller.Item.BookIt', $this, array());
		}		
    }
    public function view($slug = null, $hash = null, $salt = null)
    {
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = Configure::read('item.alt_name_for_item_singular_caps');
        $this->set('distance_view', true);
        if (!empty($hash) && !empty($salt)) {
            $salt1 = hexdec($hash) +786;
            $salt1 = substr(dechex($salt1) , 0, 2);
            if ($salt1 != $salt) {
                $this->redirect(array(
                    'controller' => 'items',
                    'action' => 'view',
                    $slug
                ));
            }
            $named_array = $this->Item->getSearchKeywords($hash, $salt);
            $this->request->params['named'] = array_merge($this->request->params['named'], $named_array);
            $is_city = false;
            if (empty($this->request->params['named']['cityname'])) {
                $this->set('distance_view', false);
            }
        }
        $conditions_fav = array();
        if ($this->Auth->user()) {
            $conditions_fav['ItemFavorite.user_id'] = $this->Auth->user('id');
        }
        $contain = array(
            'Attachment' => array(
                'fields' => array(
                    'Attachment.id',
                    'Attachment.dir',
                    'Attachment.filename',
                    'Attachment.width',
                    'Attachment.height',
                    'Attachment.description',
                    'Attachment.thumb',
					'Attachment.amazon_s3_thumb_url',
                    'Attachment.amazon_s3_original_url'
                ),
				'order' => array(
					'Attachment.id' => 'ASC'
				) ,
            ) ,
            'User',
            'Country' => array(
                'fields' => array(
                    'Country.name',
                    'Country.iso_alpha2'
                )
            ) ,
            'State' => array(
                'fields' => array(
                    'State.name'
                )
            ) ,
            'City' => array(
                'fields' => array(
                    'City.name',
                    'City.id',
                )
            ) ,
            'Submission' => array(
                'SubmissionField' => array(
                    'ItemCloneThumb',
                    'SubmissionThumb',
                    'FormField'
                ) ,
			),
			'CustomPricePerNight' => array(
			    'fields' => array(
					'CustomPricePerNight.name',
					'CustomPricePerNight.description',
					'CustomPricePerNight.item_id',
					'CustomPricePerNight.parent_id',
					'CustomPricePerNight.start_date',
					'CustomPricePerNight.start_time',
					'CustomPricePerNight.end_date',
					'CustomPricePerNight.end_time',
					'CustomPricePerNight.price_per_hour',
					'CustomPricePerNight.price_per_day',
					'CustomPricePerNight.price_per_week',
					'CustomPricePerNight.price_per_month',
					'CustomPricePerNight.quantity',
					'CustomPricePerNight.total_booked_count',
					'CustomPricePerNight.min_hours',
					'CustomPricePerNight.is_timing',
				),
				'order' => array(
					'CustomPricePerNight.id' => 'ASC'
				),
				'CustomPricePerType' => array(
							'limit' => 1
				),
			),
			'Category' => array(
				'fields' => array(
					'Category.id',
					'Category.parent_id',
					'Category.name',
					'Category.slug'
				),
				'ParentCategory' => array(
					'fields' => array(
						'ParentCategory.id',
						'ParentCategory.parent_id',
						'ParentCategory.name',
						'ParentCategory.slug'
					)
				)
			)
        );
		
        if (isPluginEnabled('ItemFavorites')) {
            $contain['ItemFavorite'] = array(
                'conditions' => $conditions_fav,
                'fields' => array(
                    'ItemFavorite.id',
                    'ItemFavorite.user_id',
                    'ItemFavorite.item_id',
                )
            );
        }
		 if (isPluginEnabled('Seats')) {
            $contain['CustomPricePerNight'] = array('Hall','CustomPricePerType' => array('Partition'));
        }
        $item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.slug' => $slug
            ) ,
            'contain' => $contain,
            'recursive' => 3,
        ));
		$custom_prices = $this->Item->CustomPricePerNight->find('all', array(
			'conditions' => array(
				'CustomPricePerNight.item_id' => $item['Item']['id'],
				'CustomPricePerNight.parent_id !=' => 0
			) ,
			'order' => array(
					'CustomPricePerNight.id' => 'ASC'
			),
			'recursive' => 0,
		));
		
		// fixed form
		
		$fixed_contain = array(
			'CustomPricePerType'
		);
		if (isPluginEnabled('Seats')) {
            $fixed_contain[] = 'Hall';
			$fixed_contain['CustomPricePerType'] = 'Partition';
        }
		$custom_price_types = $this->Item->CustomPricePerNight->find('all', array(
			'conditions' => array(
				'CustomPricePerNight.item_id' => $item['Item']['id'],
				'CustomPricePerNight.parent_id' => 0
			) ,
			'contain' => $fixed_contain,
			'order' => array(
				'CustomPricePerNight.id' => 'ASC'
			),
			'recursive' => 2,
		));
        if (empty($item) || (empty($item['Item']['is_active']) || !empty($item['Item']['admin_suspend']) || empty($item['Item']['is_approved'])) && ($this->Auth->user('id') != $item['Item']['user_id']) && $this->Auth->user('role_id') != ConstUserTypes::Admin) {
            if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			} else {
				throw new NotFoundException(__l('Invalid request'));
			}
        }		
		$submissionFieldLabel = array();
		$submissionFieldOption = array();
		$submissionFieldDisplay = array();
		$this->loadModel('Items.FormField');
		if (!empty($item['Item']['category_id'])) {
			$FormFields = $this->FormField->find('all', array(
				'conditions' => array(
					'FormField.category_id' => $item['Item']['category_id'],
					'FormField.is_active' => 1
				) ,
				'order' => array(
					'FormField.name' => 'asc'
				)
			));
		}
		if (!empty($FormFields)) {
			foreach($FormFields as $key => $formField) {
				$submissionFieldLabel[$formField['FormField']['name']] = $formField['FormField']['label'];
				$submissionFieldOption[$formField['FormField']['name']] = $formField['FormField']['options'];
				$submissionFieldDisplay[$formField['FormField']['name']] = (!empty($formField['FormField']['display_text']) ? $formField['FormField']['display_text'] : '');
			}
		}
		$this->set('submissionFieldLabel', $submissionFieldLabel);
		$this->set('submissionFieldOption', $submissionFieldOption);
		$this->set('submissionFieldDisplay', $submissionFieldDisplay);
		
        if (empty($hash) && empty($salt)) {
            //generating keyword id
            $query_string = '/city:';
            $query_string.= '/cityname:';
            $query_string.= '/latitude:' . $item['Item']['latitude'];
            $query_string.= '/longitude:' . $item['Item']['longitude'];
            $query_string.= '/from:' . date('Y-m-d');
            $query_string.= '/to:' . getToDate(date('Y-m-d'));
            $query_string.= '/additional_guest:1';
            $query_string.= '/range_from:10';
            $query_string.= '/range_from:0';
            $query_string.= '/is_flexible:1';
            $query_string.= '/type:search';
            $query_string.= '/type:search';
            $searchkeyword['SearchKeyword']['keyword'] = $query_string;
            App::import('Model', 'Items.SearchKeyword');
            $this->SearchKeyword = new SearchKeyword();
            $this->SearchKeyword->save($searchkeyword, false);
            $keyword_id = $this->SearchKeyword->getLastInsertId();
            //maintain in search log
            $searchlog = array();
            $searchlog['SearchLog']['search_keyword_id'] = $keyword_id;
            App::import('Model', 'Items.SearchLog');
            $this->SearchLog = new SearchLog();
            $searchlog['SearchLog']['ip_id'] = $this->SearchLog->toSaveIp();
            if ($this->Auth->user('id')) {
                $searchlog['SearchLog']['user_id'] = $this->Auth->user('id');
            }
            $this->SearchLog->save($searchlog, false);
            $salt = $keyword_id+786;
            $hash_query_string = '/' . dechex($keyword_id) . '/' . substr(dechex($salt) , 0, 2);
            $this->request->params['pass']['1'] = dechex($keyword_id);
            $this->request->params['pass']['2'] = substr(dechex($salt) , 0, 2);
            $this->set('distance_view', false);
        }
        if (isset($this->request->params['named']['from']) && isset($this->request->params['named']['to'])) {
            $this->request->data['ItemUser']['from'] = $this->request->params['named']['from'];
            $this->request->data['ItemUser']['to'] = getToDate($this->request->params['named']['to']);
        }
        // Set the meta value for View Item
        $meta_keyword = '';
        // Metas Settings
        if (!empty($item['Attachment'][0])) {
            $image_options = array(
                'dimension' => 'medium_thumb',
                'class' => '',
                'alt' => $item['Item']['title'],
                'title' => $item['Item']['title'],
                'type' => 'png'
            );
            $item_image = Router::url('/', true) . getImageUrl('Item', $item['Attachment'][0], $image_options, true);
            Configure::write('meta.view_image', $item_image);
        }
        $meta_description = 'Book ' . $meta_keyword . ' item at ' . $item['Item']['price_per_day'] . ' in ' . (!empty($item['City']['name']) ? $item['City']['name'] : '') . ', ' . $item['Item']['title'];
        Configure::write('meta.description', $meta_description);
        Configure::write('meta.keywords', $meta_keyword);
        Configure::write('meta.item_name', $item['Item']['title']);
        if (isset($this->request->params['named']['order_id'])) {
            $itemUser = $this->Item->ItemUser->find('first', array(
                'conditions' => array(
                    'ItemUser.id = ' => $this->request->params['named']['order_id'],
                ) ,
                'recursive' => -1,
            ));
            $this->set('itemUser', $itemUser);
        }
        if (!empty($this->request->params['named']['view_type']) && $this->request->params['named']['view_type'] == 'sidebar-view') {
            //Log the item view
            $this->request->data['ItemUser']['price'] = $item['Item']['price_per_day'];
            $this->request->data['ItemUser']['item_name'] = $item['Item']['title'];
            $this->request->data['ItemUser']['item_slug'] = $item['Item']['slug'];
            $this->request->data['ItemUser']['booking_option'] = 'price_per_day';
		} else {
            $this->request->data['ItemView']['user_id'] = $this->Auth->user('id');
            $this->request->data['ItemView']['item_id'] = $item['Item']['id'];
            $this->request->data['ItemView']['ip_id'] = $this->Item->ItemView->toSaveIp();
            $this->Item->ItemView->create();
            $this->Item->ItemView->save($this->request->data);
        }
        $this->pageTitle.= ' - ' . $item['Item']['title'];
        $this->set('item', $item);
		$this->set('custom_prices', $custom_prices);
		$this->set('custom_price_types', $custom_price_types);
        // social connections list
        if ($this->Auth->user('id') && $this->Auth->user('is_show_facebook_friends') && $this->Auth->user('is_facebook_friends_fetched')) {
            $social_conditions['Item.user_id != '] = $this->Auth->user('id');
            $host_user_id = $this->Item->User->find('list', array(
                'conditions' => array(
                    'User.id' => $item['Item']['user_id'],
                    'User.is_facebook_friends_fetched' => 1
                ) ,
                'fields' => array(
                    'User.id',
                    'User.network_fb_user_id',
                ) ,
                'recursive' => -1,
            ));
            if (!empty($host_user_id)) {
                $network_level = $this->Item->getFacebookFriendLevel($host_user_id);
                $this->set('network_level', $network_level);
                $userFacebookFriends = $this->Item->getMutualFriends($this->Auth->user('id') , $item['Item']['user_id']);
                if (!empty($userFacebookFriends[$item['Item']['user_id']]) && !empty($userFacebookFriends[$this->Auth->user('id') ])) {
                    $this->set('common_friends', array_intersect($userFacebookFriends[$this->Auth->user('id') ], $userFacebookFriends[$item['Item']['user_id']]));
                }
            }
        }
        if ($this->Auth->user('id') && !$this->Auth->user('is_facebook_friends_fetched')) {
            App::import('Vendor', 'facebook/facebook');
            $this->facebook = new Facebook(array(
                'appId' => Configure::read('facebook.app_id') ,
                'secret' => Configure::read('facebook.secrect_key') ,
                'cookie' => true
            ));
            $fb_return_url = Router::url(array(
                'controller' => 'users',
                'action' => 'fb_update',
                'admin' => false
            ) , true);
            $this->Session->write('fb_return_url', $fb_return_url);
            $fb_login_url = $this->facebook->getLoginUrl(array(
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true) ,
                'scope' => 'email,offline_access,publish_stream'
            ));
            $this->set('fb_login_url', $fb_login_url);
        }
        if (isPluginEnabled('SocialMarketing')) {
            $url = Cms::dispatchEvent('Controller.SocialMarketing.getShareUrl', $this, array(
                'data' => $item['Item']['id'],
                'publish_action' => 'add',
            ));
            $this->set('share_url', $url->data['social_url']);
        }
        // <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.Item.view', $this, array(
                'item' => $item
            ));
        }
        $chart_data = $this->Item->getBookingChart(null, $item['Item']['id']);
        $this->set('chart_data', $chart_data);
        // For iPhone App code -->
        if (!empty($this->request->params['named']['view_type']) && $this->request->params['named']['view_type'] == 'simple-view') {
            $this->render('simple-view');
        }else if (!empty($this->request->params['named']['view_type']) && $this->request->params['named']['view_type'] == 'price-view' && !empty($this->request->params['isAjax'])) {		
            $this->render('price_view');
        } else if (!empty($this->request->params['named']['view_type']) && $this->request->params['named']['view_type'] == 'sidebar-view') {
			if(!empty($this->request->params['named']['order_id'])) {
				$itemUser = $this->Item->ItemUser->find('first', array(
					'conditions' => array(
						'ItemUser.id' => $this->request->params['named']['order_id'],
					),
					'recursive' => -1,
				));
				$this->set('itemUser', $itemUser);
			}
            $this->render('sidebar-view');
        }
    }
    public function sort_attachments() {
		if ($this->RequestHandler->isAjax()) {
            $order = 1;
            foreach($this->request->data['Attachment'] as $attach) {
                $this->Item->Attachment->id = $attach['id'];
                $this->Item->Attachment->saveField('display_order', $order);
                $order++;
            }
            $this->set('response', 'success');
            $this->render('../Elements/ajax_reponse');
        }
	}
    public function add_attachment() {
		//todo: swagger api call need to fix
		if ($this->RequestHandler->prefers('json')) {
			$this->request->data = $_POST;
			$this->request->data['Attachment']['filename'][0] = $_FILES['filename'];
		}
		if(!empty($this->request->data['id'])) {
			$item_id = $this->request->data['id'];
		} else {
			$item_id = $this->request->data['Item']['id'];
		}
		$this->request->data['Attachment']['filename'][0]['type'] = get_mime($this->request->data['Attachment']['filename'][0]['tmp_name']);	
		$this->Item->Attachment->create();
		$data = array();
		$data['Attachment']['filename'] = $this->request->data['Attachment']['filename'][0];
		$data['Attachment']['foreign_id'] = $item_id;
		$data['Attachment']['class'] = 'Item';
		$data['Attachment']['dir'] = 'Item/' . $item_id;			
		$this->Item->Attachment->Behaviors->attach('ImageUpload', Configure::read('photo.file'));
		$this->Item->Attachment->set($data['Attachment']);
		$this->Item->Attachment->save($data['Attachment']);
		$attachment_id = $this->Item->Attachment->getLastInsertId();
		$attachment = $this->Item->Attachment->find('first', array(
			'conditions' => array(
				'Attachment.id' => $attachment_id,
			) ,
			'recursive' => -1 ,
		));
		$this->set('attachment', $attachment);
		if ($this->RequestHandler->prefers('json')) {
            Cms::dispatchEvent('Controller.Item.ItemAddAttachment', $this, array());
        }
	}
    public function add_simple() {		
		//todo: swagger api call need to fix
		if ($this->RequestHandler->prefers('json')) {
			$_POST = $this->request->data;
		}else{
			$this->autoRender = false;
		}
		$session_key = $_POST['key'];
		if (empty($_SESSION[$session_key])) {
			$_data = array();
			$_data['Item'] = $_POST;
			$_data['Item']['user_id'] = $this->Auth->user('id');
			$this->Item->create();
			$this->Item->save($_data, false);
			$item_id = $this->Item->getLastInsertId();
			$_SESSION[$session_key] = $item_id;
		} else {
			$item_id = $_SESSION[$session_key];
		}
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            Cms::dispatchEvent('Controller.Item.ItemAddSimple', $this, array(
                'message' => array('item_id' => $item_id)
            ));
        }else{		
			echo $item_id;
		}
	}
    public function add($request = null, $request_id = null)
    {
		$this->loadModel('Items.CustomPricePerType');
		$this->loadModel('Items.CustomPricePerNight');
		$custom_night_validate = $this->Item->CustomPricePerNight->validate;				
		$custom_type_validate = $this->Item->CustomPricePerType->validate;				
		$validation_error = array();
		$this->pageTitle = sprintf(__l('Post a %s') , Configure::read('item.alt_name_for_item_singular_caps'));
		/*Check receiver id */
        $userDetails = $this->Item->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id')
            ) ,
            'recursive' => -1
        ));
		$is_payout_error = 0;
        if (isPluginEnabled('Sudopay')) {
            $this->loadModel('Sudopay.Sudopay');
            $user_id = $this->Auth->user('id');
            $is_having_pending_gateways_connect = $this->Sudopay->isHavingPendingGatewayConnect($user_id);
            $connected_gateways = $this->Sudopay->GetUserConnectedGateways($user_id);
            if (!empty($is_having_pending_gateways_connect)) {
                $is_payout_error = 1;
            }
			if (!empty($this->request->params['named']['step'])) {
                $is_payout_error = 0;
            }
        }
        $this->set(compact('is_payout_error', 'userDetails'));
        if (!empty($this->request->data)) {
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['Item'] = $this->request->data;
				$this->request->data['State']['name'] = $this->request->data['Item']['state_name'];
				$this->request->data['City']['name'] = $this->request->data['Item']['city_name'];
			}
            $this->request->data['Item']['user_id'] = !empty($this->request->data['Item']['user_id']) ? $this->request->data['Item']['user_id'] : $this->Auth->user('id');
            $this->request->data['Item']['is_active'] = 0;
            $this->request->data['Item']['ip_id'] = $this->Item->toSaveIp();
            //state and country looking
            if (!empty($this->request->data['Item']['country_id'])) {
                $this->request->data['Item']['country_id'] = $this->Item->Country->findCountryId($this->request->data['Item']['country_id']);
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Item']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Item->State->findOrSaveAndGetId($this->request->data['State']['name'], $this->request->data['Item']);
            }
            if (!empty($this->request->data['City']['name'])) {
                $this->request->data['Item']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Item->City->findOrSaveAndGetId($this->request->data['City']['name'], $this->request->data['Item']);
            }
            if (empty($this->request->data['Item']['min_number_of_ticket'])) {
                $this->request->data['Item']['is_tipping_point'] = 0;
                $this->request->data['Item']['min_number_of_ticket'] = 0;
            } else {
                $this->request->data['Item']['is_tipping_point'] = 1;
            }
            $check_have_and_request = 1;
            if (empty($this->request->data['Item']['is_have_definite_time']) && empty($this->request->data['Item']['is_user_can_request'])) {
                $check_have_and_request = 0;
            }
            $check_price = true;
            $check_price_type = true;
			if(isset($custom_price_pre_night['id']) && !isset($custom_price_pre_night['start_date'])) {
				continue;
			}
            if (!empty($this->request->data['Item']['is_have_definite_time']) && (!empty($this->request->data['Item']['is_people_can_book_my_time']) || !empty($this->request->data['Item']['is_sell_ticket']))) {
                if (!empty($this->request->data['Item']['is_sell_ticket']) && !empty($this->request->data['CustomPricePerNight']['SellTicket'])) {
					unset($this->request->data['CustomPricePerNight']['SellTicket'][0]);
					foreach($this->request->data['CustomPricePerNight']['SellTicket'] as $key => $custom_price_pre_night){
						$custom_data_val = array();						
						$custom_data_val['CustomPricePerNight']['repeat_end_date'] = !empty($custom_price_pre_night['repeat_end_date']) ? $custom_price_pre_night['repeat_end_date'] : '';
						$c_start_date = $custom_price_pre_night['start_date']['year'] . '-' . $custom_price_pre_night['start_date']['month'] . '-' . $custom_price_pre_night['start_date']['day'];
                        $c_end_date = $custom_price_pre_night['end_date']['year'] . '-' . $custom_price_pre_night['end_date']['month'] . '-' . $custom_price_pre_night['end_date']['day'];
						$type_start_date = $c_start_date;
						$type_end_date = $c_end_date;
						unset($this->Item->CustomPricePerNight->validate['name']);
						unset($this->Item->CustomPricePerNight->validate['quantity']);
						 if(empty($custom_price_pre_night['recurring_day']) || count($custom_price_pre_night['recurring_day']) <= 0){
							unset($this->Item->CustomPricePerNight->validate['repeat_end_date']);
							unset($this->request->data['CustomPricePerNight']['SellTicket'][$key]['repeat_end_date']);
						 }
						$this->CustomPricePerNight->create();
						$this->CustomPricePerNight->set($custom_data_val);
						$errors = $this->CustomPricePerNight->invalidFields(); 
						if(!empty($errors)){
							foreach($errors as $field => $error){
								$validation_error['SellTicket'][$key][$field] = $this->CustomPricePerNight->validationErrors[$field][0];
							}
							$check_price = false;
						}
                       if (strtotime($type_start_date) <= strtotime(date('Y-m-d'))) {
							$validation_error['SellTicket'][$key]['start_date'] = __l
							('Start Date should be greater than current date');
                            $check_price = false;
                        } elseif(strtotime($type_start_date) > strtotime($type_end_date)){
							$validation_error['SellTicket'][$key]['start_date'] = __l
							('Start Date should be less than End date');
                            $check_price = false;
						}
						if(empty($custom_price_pre_night['start_date']['day'])){
							$validation_error['SellTicket'][$key]['start_date'] = __l
							('Required');
                            $check_price = false;
						}
						if(empty($custom_price_pre_night['end_date']['day'])){
							$validation_error['SellTicket'][$key]['end_date'] = __l('Required');
                            $check_price = false;
						}
						if(isPluginEnabled('Seats')){
							if(!emptY($custom_price_pre_night['is_seating_selection']) && empty($custom_price_pre_night['hall_id'])){
								$validation_error['SellTicket'][$key]['hall_id'] = __l('Required');
								$check_price = false;
							}
						}
						if(!empty($custom_price_pre_night['repeat_end_date']['day']) && !empty($custom_price_pre_night['repeat_end_date']['month']) && !empty($custom_price_pre_night['repeat_end_date']['year'])){
							$repeat_end_date = $custom_price_pre_night['repeat_end_date']['year'] . '-' . $custom_price_pre_night['repeat_end_date']['month'] . '-' . $custom_price_pre_night['repeat_end_date']['day'];
							$repeat_chk_end_dt = strtotime($repeat_end_date);
							if(!empty($custom_price_pre_night['recurring_day']) && count($custom_price_pre_night['recurring_day']) > 0 && !empty($repeat_chk_end_dt) && $repeat_chk_end_dt < strtotime(date('Y-m-d'))){
								$validation_error['SellTicket'][$key]['repeat_end_date'] = __l('Repeat end date should be greater than current date');
								$check_price = false;
							} elseif(!empty($custom_price_pre_night['recurring_day']) && count($custom_price_pre_night['recurring_day']) > 0 && !empty($repeat_chk_end_dt) &&  strtotime($type_start_date) >= $repeat_chk_end_dt){
								$validation_error['SellTicket'][$key]['repeat_end_date'] = __l('Repeat end date should be greater than start date');
								$check_price = false;
							}
						}
						if(!empty($this->request->data['CustomPricePerType'])){
							unset($this->request->data['CustomPricePerType'][$key][0]);
							foreach($this->request->data['CustomPricePerType'][$key] as $sub_key => $custom_price_pre_type){
								if(isPluginEnabled('Seats')){
									App::import('Model', 'Seats.Partition');
									$this->Partition = new Partition();
									$partitions = $this->Partition->find('list', array(
										'conditions' => array(
											'Partition.hall_id' => $custom_price_pre_night['hall_id'],
											'Partition.is_active' => 1
										) ,
										'order' => array(
											'Partition.name' => 'ASC',
										) ,
										'recursive' => -1
									));
									$this->request->data['CustomPricePerType'][$key][$sub_key]['partitions'] = $partitions;
								} 
								$custom_price_type_data = array();
								$custom_price_type_data['CustomPricePerType']['name'] = !empty($custom_price_pre_type['name']) ? $custom_price_pre_type['name'] : '';
								$custom_price_type_data['CustomPricePerType']['max_number_of_quantity'] = !empty($custom_price_pre_type['max_number_of_quantity']) ? $custom_price_pre_type['max_number_of_quantity'] : 0;
								$custom_price_type_data['CustomPricePerType']['price'] = !empty($custom_price_pre_type['price']) ? $custom_price_pre_type['price'] : 0.00;
								$this->CustomPricePerType->validate = $custom_type_validate;		
			
								$this->CustomPricePerType->create();
								$this->CustomPricePerType->set($custom_price_type_data);
								$errors = $this->CustomPricePerType->invalidFields();
								 if (!empty($errors)){
									foreach($errors as $field => $error){
										$validation_error[$key][$sub_key][$field] = $this->CustomPricePerType->validationErrors[$field][0];
									}							
									$check_price_type = false;
								 }								 
								 if(empty($custom_price_pre_type['start_time']['hour']) && empty($custom_price_pre_type['start_time']['min']) && empty($custom_price_pre_type['start_time']['meridian'])){
									$validation_error[$key][$sub_key]['start_time'] = __l('Required');
									$check_price_type = false;
								}
								if(empty($custom_price_pre_type['end_time']['hour']) && empty($custom_price_pre_type['end_time']['min']) && empty($custom_price_pre_type['end_time']['meridian'])){
									$validation_error[$key][$sub_key]['end_time'] = __l('Required');
									$check_price_type = false;
								}
								if(strtotime($type_start_date) == strtotime($type_end_date)){
									$srt_time = $custom_price_pre_type['start_time']['hour'].':'.$custom_price_pre_type['start_time']['min'].' '. $custom_price_pre_type['start_time']['meridian'];
									$srt_time = strtotime($srt_time);
									$end_time = $custom_price_pre_type['end_time']['hour'].':'.$custom_price_pre_type['end_time']['min'].' '. $custom_price_pre_type['end_time']['meridian'];
									$end_time = strtotime($end_time);
									if($end_time <= $srt_time){
										$validation_error[$key][$sub_key]['start_time'] = __l('Start time should be less than end time');
										$check_price_type = false;
									}
								}
								if(isPluginEnabled('Seats')){
									if(!emptY($custom_price_pre_night['is_seating_selection']) && empty($custom_price_pre_type['partition_id'])){
										$validation_error[$key][$sub_key]['partition_id'] = __l('Required');
										$check_price_type = false;
									}
								}
							}
							
						}
					}												
                }
            }
			// flexible form validates
			$main_detail_valid = true;
			$sub_detail_valid = true;
			$sub_detail_price_valid = true;			
			$is_form_valid = true;
			if (!empty($this->request->data['Item']['is_have_definite_time']) && (!empty($this->request->data['Item']['is_people_can_book_my_time']))) {
				if(!empty($this->request->data['CustomPricePerNight']['main_details'])){
					unset($this->Item->CustomPricePerNight->validate['name']);					
					unset($this->Item->CustomPricePerNight->validate['repeat_end_date']);
					unset($this->Item->CustomPricePerNight->validate['price_per_hour']);
					unset($this->Item->CustomPricePerNight->validate['price_per_day']);
					unset($this->Item->CustomPricePerNight->validate['price_per_week']);
					unset($this->Item->CustomPricePerNight->validate['price_per_month']);
					$custom_data_val['CustomPricePerNight']['min_hours'] = $this->request->data['CustomPricePerNight']['main_details']['min_hours'];
					$this->CustomPricePerNight->create();					
					$this->CustomPricePerNight->set($custom_data_val);
					$errors = $this->CustomPricePerNight->invalidFields(); 
					if(!empty($errors)){
						foreach($errors as $field => $error){
							$validation_error['main_details'][$field] = $this->CustomPricePerNight->validationErrors[$field][0];
						}
						$main_detail_valid = false;
					}
				} 
				if (!empty($this->request->data['CustomPricePerNight']['price_detail'])) {
					unset($this->request->data['CustomPricePerNight']['price_detail'][0]);
					unset($this->Item->CustomPricePerNight->validate['min_hours']);
					$tot_sub_qty = 0;
					foreach($this->request->data['CustomPricePerNight']['price_detail'] as $key => $custom_price_per_night){
						$custom_price_data = array();
						 $custom_price_data['CustomPricePerNight']['name'] = !empty($custom_price_per_night['name']) ? $custom_price_per_night['name'] : '';
						 $custom_price_data['CustomPricePerNight']['price_per_hour'] = !empty($custom_price_per_night['price_per_hour']) ? $custom_price_per_night['price_per_hour'] : 0.00;
						 $custom_price_data['CustomPricePerNight']['price_per_day'] = !empty($custom_price_per_night['price_per_day']) ? $custom_price_per_night['price_per_day'] : 0.00;
						 $custom_price_data['CustomPricePerNight']['price_per_week'] = !empty($custom_price_per_night['price_per_week']) ? $custom_price_per_night['price_per_week'] : 0.00;
						 $custom_price_data['CustomPricePerNight']['price_per_month'] = !empty($custom_price_per_night['price_per_hour']) ? $custom_price_per_night['price_per_hour'] : 0.00;						 
						 $custom_price_data['CustomPricePerNight']['repeat_end_date'] = !empty($custom_price_per_night['repeat_end_date']) ? $custom_price_per_night['repeat_end_date'] : '';
						 
						 if($custom_price_per_night['type'] == 0){
							 if($custom_price_per_night['price_per_hour'] <= 0 && $custom_price_per_night['price_per_day'] <= 0 && $custom_price_per_night['price_per_week'] <= 0 && $custom_price_per_night['price_per_month'] <= 0) {
								$sub_detail_valid = false;
								$sub_detail_price_valid = false;							
								$validation_error['price_detail'][$key]['price_per_hour'] = __l('Any one price is Required');
							 }
						 }
						 if(empty($custom_price_per_night['repeat_days']) || count($custom_price_per_night['repeat_days']) <= 0){
							unset($this->Item->CustomPricePerNight->validate['repeat_end_date']);
							unset($custom_price_data['CustomPricePerNight']['repeat_end_date']);
						 }
						$this->CustomPricePerNight->create();
						$this->CustomPricePerNight->set($custom_price_data);
						$errors = $this->CustomPricePerNight->invalidFields();
						 if (!empty($errors)){
							foreach($errors as $field => $error){
								$validation_error['price_detail'][$key][$field] = $this->CustomPricePerNight->validationErrors[$field][0];
							}							
							$sub_detail_valid = false;
						 }
						if(!empty($custom_price_per_night['start_date']['day']) && !empty($custom_price_per_night['end_date']['day'])){
							$c_start_date = $custom_price_per_night['start_date']['year'] . '-' . $custom_price_per_night['start_date']['month'] . '-' . $custom_price_per_night['start_date']['day'];
							$c_end_date = $custom_price_per_night['end_date']['year'] . '-' . $custom_price_per_night['end_date']['month'] . '-' . $custom_price_per_night['end_date']['day'];
							$type_start_date = $c_start_date;
							$type_end_date = $c_end_date;	
							if (strtotime($type_start_date) <= strtotime(date('Y-m-d'))) {
								$validation_error['price_detail'][$key]['start_date'] = __l
								('Start Date should be greater than current date');
								$sub_detail_valid = false;
							} elseif(strtotime($type_start_date) >= strtotime($type_end_date)){
								$validation_error['price_detail'][$key]['start_date'] = __l
								('Start Date should be less than End date');
								$sub_detail_valid = false;
							}
						}
						if(!empty($custom_price_per_night['repeat_end_date']['day']) && !empty($custom_price_per_night['repeat_end_date']['month']) && !empty($custom_price_per_night['repeat_end_date']['year'])){
							$repeat_end_date = $custom_price_per_night['repeat_end_date']['year'] . '-' . $custom_price_per_night['repeat_end_date']['month'] . '-' . $custom_price_per_night['repeat_end_date']['day'];
							$repeat_chk_end_dt = strtotime($repeat_end_date);
							if(!empty($custom_price_per_night['repeat_days']) && count($custom_price_per_night['repeat_days']) <= 0 && $repeat_chk_end_dt < strtotime(date('Y-m-d'))){
								$validation_error['price_detail'][$key]['repeat_end_date'] = __l('Repeat end date should be greater than current date');
								$check_price = false;
							} elseif(!empty($custom_price_per_night['repeat_days']) && count($custom_price_per_night['repeat_days']) <= 0 && !empty($repeat_chk_end_dt) &&  strtotime($type_start_date) >= $repeat_chk_end_dt){
								$validation_error['price_detail'][$key]['repeat_end_date'] = __l('Repeat end date should be greater than start date');
								$check_price = false;
							}
						}
					}
				}
			}
			/// Flexible form validate ends
			if(empty($this->request->data['Item']['id'])) {
				$this->Item->create();
			}
            $this->Item->set($this->request->data);
			if(isset($this->request->data['Attachment']['filename']) && count($this->request->data['Attachment']) == 1) {
				$is_form_valid = false;
			}
			$this->Item->CustomPricePerNight->validationErrors = $validation_error;
			$this->Item->CustomPricePerType->validationErrors = $validation_error;
			if ($is_form_valid &$this->Item->validates($this->request->data['Item']) &$check_have_and_request &$check_price &$check_price_type &$sub_detail_valid) {
                $this->request->data['Item']['category_id'] = $this->request->data['Item']['sub_category_id'];
				if (isPluginEnabled('Sudopay')) {
					App::import('Model', 'Sudopay.SudopayPaymentGatewaysUser');
					$this->SudopayPaymentGatewaysUser = new SudopayPaymentGatewaysUser();
					$connected_gateways = $this->SudopayPaymentGatewaysUser->find('list', array(
						'conditions' => array(
							'SudopayPaymentGatewaysUser.user_id' => $this->Auth->user('id') ,
						) ,
						'fields' => array(
							'SudopayPaymentGatewaysUser.sudopay_payment_gateway_id',
						) ,
						'recursive' => -1,
					));
	                $this->request->data['Item']['is_active'] = empty($connected_gateways) ? 0 : 1 ;
				} else {
					$this->request->data['Item']['is_active'] = 1;
				}
                if (!Configure::read('item.item_fee')) {
                    $this->request->data['Item']['is_paid'] = 1;
                    $this->request->data['Item']['is_approved'] = (Configure::read('item.is_auto_approve')) ? 1 : 0;
                }
                // @todo "Language Filter"
                $user_id = !empty($this->request->data['Item']['user_id']) ? $this->request->data['Item']['user_id'] : $this->Auth->user('id');
                $userProfile = $this->Item->User->UserProfile->find('first', array(
                    'fields' => array(
                        'UserProfile.language_id'
                    ) ,
                    'conditions' => array(
                        'UserProfile.user_id' => $user_id
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($userProfile['UserProfile']['language_id'])) {
                    $this->request->data['Item']['language_id'] = $userProfile['UserProfile']['language_id'];
                }
				if(empty($this->request->data['Item']['is_have_definite_time']) && !empty($this->request->data['Item']['is_user_can_request'])){
					$this->request->data['Item']['is_people_can_book_my_time'] = 0;
					$this->request->data['Item']['is_sell_ticket'] = 0;
				}
                $this->Item->save($this->request->data);
				if (!empty($this->request->data['Item']['request_id']) && !empty($this->request->data['Item']['id'])) {
					$request_item_id = $this->Item->__updateItemRequest($this->request->data['Item']['request_id'], $this->request->data['Item']['id']);
				}
                if(empty($this->request->data['Item']['id'])) {
					$item_id = $this->Item->getLastInsertId();
				} else {
					$item_id = $this->request->data['Item']['id'];
				}
				$this->Session->write('last_insert_item_id', $item_id);
                // Attachment
				if(!empty($this->request->data['Attachment'])) {
					unset($this->request->data['Attachment']['filename']);
					foreach($this->request->data['Attachment'] As $key => $attach) {
						$data = array();
						$data['Attachment']['id'] = $key;
						$data['Attachment']['description'] = $attach['description'];
						$this->Item->Attachment->save($data);
					}
				}
                // saving in user
                $data = array();
                $data['User']['id'] = $_SESSION['Auth']['User']['id'];
                $data['User']['is_idle'] = 0;
                $data['User']['is_item_posted'] = 1;
                $this->Item->User->set($data);
                $this->Item->User->save($data);
				$item_min_price = 0;
				$item_custom_source = 0;
                if (!empty($this->request->data['Item']['is_have_definite_time']) && !empty($this->request->data['Item']['is_people_can_book_my_time'])){
					$parent_id = 0;
					if( !empty($this->request->data['CustomPricePerNight']['main_details'])) {
						$custom_data = array();
						$custom_data['CustomPricePerNight']['item_id'] = $item_id;
						$custom_data['CustomPricePerNight']['is_timing'] = $this->request->data['CustomPricePerNight']['main_details']['is_timing'];
						$custom_data['CustomPricePerNight']['min_hours'] = $this->request->data['CustomPricePerNight']['main_details']['min_hours'];
						$this->Item->CustomPricePerNight->create();
						$this->Item->CustomPricePerNight->save($custom_data);
						$parent_id = $this->Item->CustomPricePerNight->getLastInsertId();
					}					
					if (!empty($this->request->data['CustomPricePerNight']['price_detail']) && !empty($parent_id)) {
						unset($this->request->data['CustomPricePerNight']['price_detail'][0]);
						foreach($this->request->data['CustomPricePerNight']['price_detail'] As $custom_price_pre_night) {
							$custom_data_price = array();
							$itemprice = array();
							$custom_data_price['CustomPricePerNight']['item_id'] = $item_id;
							$custom_data_price['CustomPricePerNight']['parent_id'] = $parent_id;
							$custom_data_price['CustomPricePerNight']['name'] = $custom_price_pre_night['name'];
							$custom_data_price['CustomPricePerNight']['description'] = $custom_price_pre_night['description'];
							$recurring_day = '';
							if(!empty($custom_price_pre_night['repeat_days'])) {
								foreach($custom_price_pre_night['repeat_days'] As $key => $val) {
									if(!empty($recurring_day)) {
										$recurring_day .= ',';
									}
									$recurring_day .= $key;
								}
								if(count($recurring_day) > 0 && !empty($custom_price_pre_night['repeat_end_date']['year']) && !empty($custom_price_pre_night['repeat_end_date']['month']) && !empty($custom_price_pre_night['repeat_end_date']['day'])){
									$custom_data_price['CustomPricePerNight']['repeat_end_date'] = $custom_price_pre_night['repeat_end_date']['year'].'-'.$custom_price_pre_night['repeat_end_date']['month'].'-'.$custom_price_pre_night['repeat_end_date']['day'];
								}							
							}
							$custom_data_price['CustomPricePerNight']['repeat_days'] = $recurring_day;
							if(!empty($custom_price_pre_night['start_date']['day'])) { 
								$custom_data_price['CustomPricePerNight']['start_date'] = $custom_price_pre_night['start_date'];
							} else {
								$custom_data_price['CustomPricePerNight']['start_date'] = date('Y-m-d');
							}
							$custom_data_price['CustomPricePerNight']['end_date'] = $custom_price_pre_night['end_date'];						
							if(!empty($custom_price_pre_night['start_time']['hour']) && !empty($custom_price_pre_night['start_time']['min'])) {
								$start_hour = $custom_price_pre_night['start_time']['hour'];
								if(strtolower($custom_price_pre_night['start_time']['meridian']) == 'am' && $custom_price_pre_night['start_time']['hour'] == '12'){
									$start_hour = '00';
								} elseif(strtolower($custom_price_pre_night['start_time']['meridian']) == 'pm' && $custom_price_pre_night['start_time']['hour'] < 12){
									$start_hour = $custom_price_pre_night['start_time']['hour'] + 12;
								}
								$custom_data_price['CustomPricePerNight']['start_time'] = $start_hour.':'.$custom_price_pre_night['start_time']['min'];
							} else {
								$custom_data_price['CustomPricePerNight']['start_time'] = '00:00:01';
							}
							if(!empty($custom_price_pre_night['end_time']['hour']) && !empty($custom_price_pre_night['end_time']['min'])) {
								$end_hour = $custom_price_pre_night['end_time']['hour'];
								if(strtolower($custom_price_pre_night['end_time']['meridian']) == 'am' && $custom_price_pre_night['end_time']['hour'] == '12'){
									$end_hour = '00';
								} elseif(strtolower($custom_price_pre_night['end_time']['meridian']) == 'pm' && $custom_price_pre_night['end_time']['hour'] < 12){
									$end_hour = $custom_price_pre_night['end_time']['hour'] + 12;
								}
								$custom_data_price['CustomPricePerNight']['end_time'] = $end_hour.':'.$custom_price_pre_night['end_time']['min'];
							} else {
								$custom_data_price['CustomPricePerNight']['end_time'] = '23:59:59';
							}
							$custom_data_price['CustomPricePerNight']['price_per_hour'] = (!empty($custom_price_pre_night['price_per_hour'])) ? $custom_price_pre_night['price_per_hour'] : 0;
							$custom_data_price['CustomPricePerNight']['price_per_day'] = (!empty($custom_price_pre_night['price_per_day'])) ? $custom_price_pre_night['price_per_day'] : 0;
							$custom_data_price['CustomPricePerNight']['price_per_week'] = (!empty($custom_price_pre_night['price_per_week'])) ?  $custom_price_pre_night['price_per_week'] : 0;
							$custom_data_price['CustomPricePerNight']['price_per_month'] = (!empty($custom_price_pre_night['price_per_month'])) ?  $custom_price_pre_night['price_per_month'] : 0;
							$this->Item->CustomPricePerNight->create();
							$this->Item->CustomPricePerNight->save($custom_data_price);
							if($item_min_price <= 0) {
								if (!empty($custom_price_pre_night['price_per_hour']) && $custom_price_pre_night['price_per_hour'] > 0) {
									$item_min_price = $custom_price_pre_night['price_per_hour'];
									$item_custom_source = ConstCustomSource::Hour;
								} else if (!empty($custom_price_pre_night['price_per_day']) && $custom_price_pre_night['price_per_day'] > 0) {
									$item_min_price = $custom_price_pre_night['price_per_day'];
									$item_custom_source = ConstCustomSource::Day;
								} else if (!empty($custom_price_pre_night['price_per_week']) && $custom_price_pre_night['price_per_week'] > 0) {
									$item_min_price = $custom_price_pre_night['price_per_week'];
									$item_custom_source = ConstCustomSource::Week;
								} else if (!empty($custom_price_pre_night['price_per_month']) && $custom_price_pre_night['price_per_month'] > 0) {
									$item_min_price = $custom_price_pre_night['price_per_month'];
									$item_custom_source = ConstCustomSource::Month;
								}
							}
						}
						$custom_min_data['CustomPricePerNight']['id'] = $parent_id;
						$custom_min_data['CustomPricePerNight']['minimum_price'] = $item_min_price;
						$this->Item->CustomPricePerNight->save($custom_min_data);
					}
				}	
                if (!empty($this->request->data['Item']['is_have_definite_time']) && !empty($this->request->data['Item']['is_sell_ticket']) && !empty($this->request->data['CustomPricePerNight']['SellTicket'])) {				
                    $check_price_free = 0;
					$parent_ids = array();
					$sell_inc = 1;
					foreach($this->request->data['CustomPricePerNight']['SellTicket'] as $key => $custom_price_pre_night){
						$custom_data = array();
						$custom_data['CustomPricePerNight']['item_id'] = $item_id;
						$custom_data['CustomPricePerNight']['repeat_days'] = (!empty($custom_price_pre_night['recurring_day'])) ? implode(',', $custom_price_pre_night['recurring_day']) : '';
						if(!empty($custom_data['CustomPricePerNight']['repeat_days'])){
							$custom_data['CustomPricePerNight']['repeat_end_date'] = $custom_price_pre_night['repeat_end_date']['year'].'-'.$custom_price_pre_night['repeat_end_date']['month'].'-'.$custom_price_pre_night['repeat_end_date']['day'];
						}
						$custom_data['CustomPricePerNight']['start_date'] = $custom_price_pre_night['start_date']['year'].'-'.$custom_price_pre_night['start_date']['month'].'-'.$custom_price_pre_night['start_date']['day'];
						$custom_data['CustomPricePerNight']['end_date'] = $custom_price_pre_night['end_date']['year'].'-'.$custom_price_pre_night['end_date']['month'].'-'.$custom_price_pre_night['end_date']['day'];
						$custom_data['CustomPricePerNight']['is_tipped'] = (!empty($this->request->data['Item']['is_tipping_point'])) ? 0 : 1;
						if(isPluginEnabled('Seats') && !empty($custom_price_pre_night['is_seating_selection'])){
							$custom_data['CustomPricePerNight']['is_seating_selection'] = $custom_price_pre_night['is_seating_selection'];
							$custom_data['CustomPricePerNight']['hall_id'] = $custom_price_pre_night['hall_id'];
						}
						$this->Item->CustomPricePerNight->create();
						$this->Item->CustomPricePerNight->save($custom_data);
						$custom_price_per_night_id = $this->Item->CustomPricePerNight->getLastInsertId();
						$min_price = $total_available_count = 0;
						$is_unlimited = 1;
						unset($this->request->data['CustomPricePerType'][$key][0]);
						foreach($this->request->data['CustomPricePerType'][$key] as $custom_price_pre_type){											
							$custom_price_data = array();
							$price = !empty($custom_price_pre_type['price']) ? $custom_price_pre_type['price'] : 0.00;
							if ($min_price <= 0) {
								$min_price = $custom_price_pre_type['price'];
							}
							if ($item_min_price <= 0) {
								$item_min_price = $custom_price_pre_type['price'];
							}
							
							if(isPluginEnabled('Seats') && !empty($custom_price_pre_night['is_seating_selection'])){
								App::import('Model', 'Seats.Seat');
								$this->Seat = new Seat();
								App::import('Model', 'Seats.CustomPricePerTypesSeat');
								$this->CustomPricePerTypesSeat = new CustomPricePerTypesSeat();
								
								$seats = $this->Seat->find('all', array(
									'conditions' => array(
										'Seat.hall_id' => $custom_price_pre_night['hall_id'],
										'Seat.partition_id' => $custom_price_pre_type['partition_id'],
									),
									'order' => array(
										'Seat.id' => 'ASC',
									) ,                
									'recursive' => -1
								));	
								$total_avail_seats = $this->Seat->find('count', array(
									'conditions' => array(
										'Seat.hall_id' => $custom_price_pre_night['hall_id'],
										'Seat.partition_id' => $custom_price_pre_type['partition_id'],
										'Seat.seat_status_id' => array(ConstSeatStatus::Available, ConstSeatStatus::Blocked, ConstSeatStatus::Booked, ConstSeatStatus::WaitingForAcceptance),
									),                
									'recursive' => -1
								));
								$total_available_count = $total_available_count + $total_avail_seats;
								$custom_price_pre_type['max_number_of_quantity'] = $total_avail_seats;
								$custom_price_data['CustomPricePerType']['partition_id'] = $custom_price_pre_type['partition_id'];
							} else {
								if(!empty($custom_price_pre_type['max_number_of_quantity'])) {
									$total_available_count = $total_available_count + $custom_price_pre_type['max_number_of_quantity'];
								} else {
									$is_unlimited = 0;
								}
							}	
							$custom_price_data['CustomPricePerType']['item_id'] = $item_id;
							$custom_price_data['CustomPricePerType']['custom_price_per_night_id'] = $custom_price_per_night_id;
							$custom_price_data['CustomPricePerType']['name'] = $custom_price_pre_type['name'];
							$custom_price_data['CustomPricePerType']['description'] = $custom_price_pre_type['description'];
							$custom_price_data['CustomPricePerType']['price'] = $price;
							$custom_price_data['CustomPricePerType']['max_number_of_quantity'] = !empty($custom_price_pre_type['max_number_of_quantity']) ? $custom_price_pre_type['max_number_of_quantity'] : 0;
							$custom_price_data['CustomPricePerType']['is_advanced_enabled'] = 0;
							$start_type_hour = $custom_price_pre_type['start_time']['hour'];
							if(strtolower($custom_price_pre_type['start_time']['meridian']) == 'am' && ($custom_price_pre_type['start_time']['hour'] == '12' || $custom_price_pre_type['start_time']['hour'] == '') && $custom_price_pre_type['start_time']['min'] != ''){
								$start_type_hour = '00';
							} elseif(strtolower($custom_price_pre_type['start_time']['meridian']) == 'pm' && $custom_price_pre_type['start_time']['hour'] < 12){
								$start_type_hour = $custom_price_pre_type['start_time']['hour'] + 12;
							}
							$end_hour = $custom_price_pre_type['end_time']['hour'];
							if(strtolower($custom_price_pre_type['end_time']['meridian']) == 'am' && ($custom_price_pre_type['end_time']['hour'] == '12' || $custom_price_pre_type['end_time']['hour'] == '') && $custom_price_pre_type['end_time']['min'] != ''){
								$end_hour = '00';
							} elseif(strtolower($custom_price_pre_type['end_time']['meridian']) == 'pm' && $custom_price_pre_type['end_time']['hour'] < 12){
								$end_hour = $custom_price_pre_type['end_time']['hour'] + 12;
							}
							$custom_price_data['CustomPricePerType']['start_time'] = $start_type_hour.':'.$custom_price_pre_type['start_time']['min'];
							$custom_price_data['CustomPricePerType']['end_time'] = $end_hour.':'.$custom_price_pre_type['end_time']['min'];
							$this->Item->CustomPricePerType->create();
							$this->Item->CustomPricePerType->save($custom_price_data);	
							$custom_price_per_type_id = $this->Item->CustomPricePerType->getLastInsertId();
							if(isPluginEnabled('Seats') && !empty($custom_price_pre_night['is_seating_selection'])){
							// insert into CustomPricePerTypesSeat
								$stored = array('CustomPricePerTypesSeat' => array());
								$seats_data = array();
								foreach($seats as $seat){
									$tmp = array();
									$tmp['item_id'] = $item_id;
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
								$this->CustomPricePerTypesSeat->saveAll($stored['CustomPricePerTypesSeat']);
							}
						}
						$custom_min_data['CustomPricePerNight']['id'] = $custom_price_per_night_id;
						$custom_min_data['CustomPricePerNight']['minimum_price'] = $min_price;
						$this->Item->CustomPricePerNight->save($custom_min_data);
					}
                }								
				$min_data['Item']['id'] = $item_id;
				$min_data['Item']['minimum_price'] = $item_min_price;
				$min_data['Item']['is_free'] = (!empty($item_min_price)) ? 0 : 1;
				$min_data['Item']['custom_source_id'] = $item_custom_source;
				$min_data['Item']['is_completed'] = 1;
				$this->Item->save($min_data);
                //Save Dynamic form fields
                $this->loadModel('Items.Submission');
                if (!empty($this->request->data['Form'])) {
                    $this->request->data['Submission'] = $this->request->data['Form'];
                    $this->request->data['Submission']['item_id'] = $item_id;
                    $submission = $this->Submission->find('first', array(
                        'conditions' => array(
                            'Submission.item_id' => $item_id
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($submission)) {
                        $this->request->data['Submission']['id'] = $submission['Submission']['id'];
                    }
                    $this->Submission->submit($this->request->data);
                }
				Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
					'_trackEvent' => array(
						'category' => 'Item',
						'action' => 'ItemPosted',
						'label' => 'Step 2',
						'value' => '',
					) ,
					'_setCustomVar' => array(
						'ud' => $this->Auth->user('id') ,
						'rud' => $this->Auth->user('referred_by_user_id') ,
					)
				));
				// <-- For iPhone App code
				if ($this->RequestHandler->prefers('json')) {
					$response = Cms::dispatchEvent('Controller.Item.ItemAdd', $this, array(
						'message' => array("message" => sprintf(__l('%s added.') , Configure::read('item.alt_name_for_item_singular_caps')), "error" => 0, "item_id" => $item_id)));
				}else{
					$this->redirect(array(
						'controller' => 'items',
						'action' => 'update_redirect',
						'admin' => false
					));
				}
            } else {				
                if (empty($is_form_valid)) {
                    $this->Session->setFlash(sprintf(__l('%s could not be added. Please, upload at least one %s image.') , Configure::read('item.alt_name_for_item_singular_caps') , Configure::read('item.alt_name_for_item_singular_small')) , 'default', null, 'error');
					$message = array('message' => sprintf(__l('%s could not be added. Please, upload at least one %s image.') , Configure::read('item.alt_name_for_item_singular_caps') , Configure::read('item.alt_name_for_item_singular_small')), 'error' => 1);
                } elseif (empty($check_price)) {
					$this->Session->setFlash(sprintf(__l('%s could not be added. Pricing given date not valid.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					$message = array('message' => sprintf(__l('%s could not be added. Pricing given date not valid.') , Configure::read('item.alt_name_for_item_singular_caps')), 'error' => 1);
                } elseif (empty($check_have_and_request)) {
                    $this->Session->setFlash(sprintf(__l('%s could not be added. Please, enable either request or user to book.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					$message = array('message' => sprintf(__l('%s could not be added. Please, enable either request or user to book.') , Configure::read('item.alt_name_for_item_singular_caps')), 'error' => 1);
                } elseif (empty($check_price_type)) {
                    $this->Session->setFlash(sprintf(__l('%s could not be added. Please, enter the valid pricing type details.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					$message = array('message' => sprintf(__l('%s could not be added. Please, enter the valid pricing type details.') , Configure::read('item.alt_name_for_item_singular_caps')), 'error' => 1);
                } else {
                    $this->Session->setFlash(sprintf(__l('%s could not be added. Please, try again.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					$message = array('message' => sprintf(__l('%s could not be added. Please, try again.') , Configure::read('item.alt_name_for_item_singular_caps')), 'error' => 1);
                }
				// <-- For iPhone App code
				if ($this->RequestHandler->prefers('json')) {
					$response = Cms::dispatchEvent('Controller.Item.ItemAdd', $this, array(
						'message' => $message
					));
				}
            }
        } else {
			Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
				'_trackEvent' => array(
					'category' => 'Item',
					'action' => 'ItemPosted',
					'label' => 'Step 1',
					'value' => '',
				) ,
				'_setCustomVar' => array(
					'ud' => $this->Auth->user('id') ,
					'rud' => $this->Auth->user('referred_by_user_id') ,
				)
			));
		}
		if(!empty($this->request->data['Item']['id'])) {
			$attachments = $this->Item->Attachment->find('all', array(
				'conditions' => array(
					'Attachment.foreign_id' => $this->request->data['Item']['id'],
					'Attachment.class' => 'Item',
				) ,
				'recursive' => -1 ,
			));
			$attach = array();
			if(!empty($attachments)) {
				foreach($attachments As $attachment) {
					$attach['Attachment'][] = $attachment['Attachment'];
				}
				$this->request->data['Attachment'] = $attach['Attachment'];
			}
		}
		if(!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'request') {
			if (isPluginEnabled('Requests')) {
				$this->loadModel('Requests.Request');
				$request = $this->Request->find('first', array(
					'conditions' => array(
						'Request.id' => $this->request->params['pass'][1]
					),
					'recursive' => -1
				));
				if(!empty($request)) {
					$this->request->params['named']['category_id'] = $request['Request']['category_id'];
				}
			}
		}
		if(!empty($this->request->params['named']['item_id'])){
			$this->request->data = $this->Item->find('first', array(
				'conditions' => array(
					'Item.id' => $this->request->params['named']['item_id'],
				) ,
				'contain' => array(
					'Category',
					'Attachment'
				) ,
				'recursive' => 1
			));
			$this->request->params['named']['category_id'] = $this->request->data['Item']['category_id'];
			$this->request->data['Item']['item_type_id'] = 1;
			$itemUser = $this->Item->ItemUser->find('first', array(
				'conditions' => array(
					'ItemUser.id' => $this->request->params['named']['order_id']
				) ,
				'recursive' => 0
			));
			$this->request->data['CustomPricePerNight']['SellTicket'][1]['start_date'] = $itemUser['ItemUser']['from'];
			$this->request->data['CustomPricePerNight']['SellTicket'][1]['end_date'] = $itemUser['ItemUser']['to'];
			$this->request->data['CustomPricePerType'][1][1]['name'] = '';
			$this->request->data['Item']['price_type'] = 2;
			$this->request->data['Item']['is_have_definite_time'] = 1;
			$this->request->data['Item']['is_auto_approve'] = (!empty($this->request->data['Item']['is_auto_approve'])) ? 1 : 0;
			$this->request->data['Item']['is_buyer_as_fee_payer'] = (!empty($this->request->data['Item']['is_buyer_as_fee_payer'])) ? 1 : 0;
			$this->request->data['Item']['is_additional_fee_to_buyer'] = (!empty($this->request->data['Item']['is_additional_fee_to_buyer'])) ? 1 : 0;
		}
        if (!empty($this->request->params['named']['category_id'])) {
            $params_category = $this->Item->Category->find('first', array(
                'conditions' => array(
                    'Category.id' => $this->request->params['named']['category_id']
                ) ,
                'recursive' => -1
            ));
            if (!empty($params_category)) {
                if (!$params_category['Category']['parent_id']) {
                    $this->request->data['Item']['category_id'] = $params_category['Category']['id'];
                } else {
                    $this->request->data['Item']['sub_category_id'] = $params_category['Category']['id'];
                    $this->request->data['Item']['category_id'] = $params_category['Category']['parent_id'];
                }
            }
        }
        $sub_categories = array();
        $category_types = array();
        if (!empty($this->request->data['Item']['category_id'])) {
            $sub_categories = $this->Item->Category->find('list', array(
                'conditions' => array(
                    'Category.parent_id' => $this->request->data['Item']['category_id'],
					'Category.is_active' => 1
                ) ,
				'order' => array(
					'Category.name' => 'ASC',
				) ,
                'recursive' => -1
            ));
            if (!empty($this->request->data['Item']['sub_category_id'])) {
                $category_types = $this->Item->CategoryType->find('list', array(
                    'conditions' => array(
                        'CategoryType.category_id' => $this->request->data['Item']['sub_category_id']
                    ) ,
					'order' => array(
						'CategoryType.name' => 'ASC',
					) ,
                    'recursive' => -1
                ));
                $category = $this->Item->Category->find('first', array(
                    'conditions' => array(
                        'Category.id' => $this->request->data['Item']['sub_category_id']
                    ) ,
                    'recursive' => -1
                ));
                if (empty($category)) {
                    throw new NotFoundException(__l('Invalid request'));
                }
                $this->loadModel('Items.Form');
                $this->loadModel('Items.FormField');
                $this->loadModel('Items.Item');
                $categoryFormFields = $this->Form->buildSchema($category['Category']['id']);
                $this->loadModel('Items.FormFieldStep');
                $FormFieldSteps = $this->FormFieldStep->find('all', array(
                    'conditions' => array(
                        'FormFieldStep.category_id' => $category['Category']['id']
                    ) ,
                    'contain' => array(
                        'FormFieldGroup' => array(
                            'FormField' => array(
                                'conditions' => array(
                                    'FormField.is_active' => 1
                                ) ,
                                'order' => array(
                                    'FormField.order' => 'ASC'
                                )
                            ) ,
                            'order' => array(
                                'FormFieldGroup.order' => 'ASC'
                            )
                        )
                    ) ,
                    'order' => array(
                        'FormFieldStep.order' => 'ASC'
                    ) ,
                    'recursive' => 2
                ));
                $this->set('FormFieldSteps', $FormFieldSteps);
                $this->set('total_form_field_steps', count($FormFieldSteps));
                $this->set('categoryFormFields', $categoryFormFields);
                $this->set('category', $category);
                $this->set('model', 'Item');
                $this->loadModel('Country');
                $countries = $this->Country->find('list', array(
                    'fields' => array(
                        'Country.iso_alpha2',
                        'Country.name'
                    )
                ));
                $this->set(compact('countries'));
                if (empty($this->request->data['Form']['form_field_step'])) {
                    $this->request->data['Form']['form_field_step'] = 1;
                }
                if (!empty($this->request->data['Form']['next'])) {
                    $this->request->data['Form']['form_field_step'] = $this->request->data['Form']['form_field_step']+1;
                }
                // form field steps
                if (!empty($form_field_step)) {
                    $this->request->data['Form']['form_field_step'] = $form_field_step;
                    $this->request->data['Form']['step'] = 2;
                }
            }
        }
        $categories = $this->Item->Category->find('list', array(
            'conditions' => array(
                'Category.parent_id' => 0,
				'Category.is_active' => 1
            ) ,
			'order' => array(
				'Category.name' => 'ASC',
			) ,
            'recursive' => -1
        ));
		if(isPluginEnabled('Seats')){
			$this->loadModel('Hall');
			$halls = $this->Hall->find('list', array(
				'conditions' => array(
					'Hall.user_id' => $this->Auth->user('id'),
					'Hall.is_active' => 1
				) ,
				'order' => array(
					'Hall.name' => 'ASC',
				) ,
				'recursive' => -1
			));
			$this->set('halls', $halls);
		}
        $users = $this->Item->User->find('list');
        $this->set(compact('categories', 'users', 'sub_categories', 'category_types'));
		if (!empty($request) && !empty($request_id)) {
            $this->request->data['Item']['request_id'] = $request_id;
			$request = array();
			if(isPluginEnabled('Requests')){
				$request = $this->Item->ItemsRequest->Request->find('first', array(
					'conditions' => array(
						'Request.id' => $this->request->data['Item']['request_id'],
						'Request.user_id !=' => $this->Auth->user('id') ,
					) ,
					'fields' => array(
						'Request.latitude',
						'Request.longitude',
						'Request.title'
					) ,
					'recursive' => -1
				));
			}
            if (empty($request)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->request->data['Item']['request_latitude'] = $request['Request']['latitude'];
            $this->request->data['Item']['request_longitude'] = $request['Request']['longitude'];
            $this->set('request_name', $request['Request']['title']);
        }
		if (!empty($this->request->query['r'])) {
                $this->redirect(Router::url('/', true) . $this->request->query['r'].'/step:skip');
            }
    }
    public function edit($id = null)
    {
		$this->loadModel('Items.CustomPricePerType');
		$this->loadModel('Items.CustomPricePerNight');
		$custom_night_validate = $this->Item->CustomPricePerNight->validate;				
		$custom_type_validate = $this->Item->CustomPricePerType->validate;				
		$validation_error = array();
		
		
        $this->pageTitle = sprintf(__l('Edit %s') , Configure::read('item.alt_name_for_item_singular_caps'));
        if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$message = array("message" => __l('Invalid request'), "error" => 1);
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        if (!empty($this->request->data)) {
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['Item'] = $this->request->data;
				$this->request->data['State']['name'] = $this->request->data['Item']['state_name'];
				$this->request->data['City']['name'] = $this->request->data['Item']['city_name'];
			}		
			$item = $this->Item->find('first', array(
                'conditions' => array(
                    'Item.id' => $id,
                ) ,
                'recursive' => -1
            ));

			if (empty($this->request->data['Item']['user_id'])) {
                $this->request->data['Item']['user_id']= $item['Item']['user_id'];;
			 }
            //state and country looking
            if (!empty($this->request->data['Item']['country_id'])) {
                $this->request->data['Item']['country_id'] = $this->Item->Country->findCountryId($this->request->data['Item']['country_id']);
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Item']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Item->State->findOrSaveAndGetId($this->request->data['State']['name'], $this->request->data['Item']);
            }
            if (!empty($this->request->data['City']['name'])) {
                $this->request->data['Item']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Item->City->findOrSaveAndGetId($this->request->data['City']['name'], $this->request->data['Item']);
            }
			$item_min_price = 0;
			$item_custom_source = 0;
            $this->request->data['Item']['price_per_hour'] = (!empty($this->request->data['Item']['price_per_hour']) ? $this->request->data['Item']['price_per_hour'] : 0.00);
            $this->request->data['Item']['price_per_day'] = (!empty($this->request->data['Item']['price_per_day']) ? $this->request->data['Item']['price_per_day'] : 0.00);
            $this->request->data['Item']['price_per_week'] = (!empty($this->request->data['Item']['price_per_week']) ? $this->request->data['Item']['price_per_week'] : 0.00);
            $this->request->data['Item']['price_per_month'] = (!empty($this->request->data['Item']['price_per_month']) ? $this->request->data['Item']['price_per_month'] : 0.00);
			$this->request->data['Item']['minimum_price'] = $item_min_price;
            if (empty($this->request->data['Item']['min_number_of_ticket'])) {
                $this->request->data['Item']['is_tipping_point'] = 0;
                $this->request->data['Item']['min_number_of_ticket'] = 0;
            } else {
                $this->request->data['Item']['is_tipping_point'] = 1;
            }
            $check_have_and_request = 1;
            if (empty($this->request->data['Item']['is_have_definite_time']) && empty($this->request->data['Item']['is_user_can_request'])) {
                $check_have_and_request = 0;
            } else if (empty($this->request->data['Item']['is_have_definite_time']) && !empty($this->request->data['Item']['is_user_can_request'])) {
				$this->request->data['Item']['booking_type'] = 0;
				$this->request->data['Item']['is_people_can_book_my_time'] = 0;
			}
			/*******//////////////////////////////*///////////////
			if($this->request->data['Item']['booking_type'] != $this->request->data['Item']['price_type']) {
				$item_id = $this->request->data['Item']['id'];
				$this->Item->CustomPricePerNight->deleteAll(array(
								'CustomPricePerNight.item_id' => $item_id
				));
				$this->Item->CustomPricePerType->deleteAll(array(
								'CustomPricePerType.item_id' => $item_id
				));
				if($this->request->data['Item']['booking_type'] == 1) {
					unset($this->request->data['CustomPricePerNight']['main_details']['id']);
					unset($this->request->data['CustomPricePerNight']['price_detail'][0]);
					foreach($this->request->data['CustomPricePerNight']['price_detail'] as $key => $booking_val) {
						unset($this->request->data['CustomPricePerNight']['price_detail'][$key]['id']);
					}
				}
				if($this->request->data['Item']['booking_type'] == 2) {
					unset($this->request->data['CustomPricePerNight']['SellTicket'][0]);
					foreach($this->request->data['CustomPricePerNight']['SellTicket'] as $sell_key => $sell_book_val) {
						unset($this->request->data['CustomPricePerNight']['SellTicket'][$sell_key]['id']);
					}
					foreach($this->request->data['CustomPricePerType'] as $sell_key => $sell_book_val) {
						unset($this->request->data['CustomPricePerType'][$sell_key][$sell_key]['id']);
					}
				}
			}
			$check_price = 1;
            $check_price_type = 1;
            if (!empty($this->request->data['Item']['is_have_definite_time']) && (!empty($this->request->data['Item']['is_people_can_book_my_time']) || !empty($this->request->data['Item']['is_sell_ticket']))) {		
			    if (!empty($this->request->data['Item']['is_sell_ticket']) && !empty($this->request->data['CustomPricePerNight']['SellTicket'])) {
					unset($this->request->data['CustomPricePerNight']['SellTicket'][0]);
					foreach($this->request->data['CustomPricePerNight']['SellTicket'] as $key => $custom_price_pre_night){
						if(isset($custom_price_pre_night['id']) && !isset($custom_price_pre_night['start_date'])) {
							continue;
						}
						$custom_data_val = array();
						$custom_data_val['CustomPricePerNight']['repeat_end_date'] = !empty($custom_price_pre_night['repeat_end_date']) ? $custom_price_pre_night['repeat_end_date'] : '';
						$c_start_date = $custom_price_pre_night['start_date']['year'] . '-' . $custom_price_pre_night['start_date']['month'] . '-' . $custom_price_pre_night['start_date']['day'];
                        $c_end_date = $custom_price_pre_night['end_date']['year'] . '-' . $custom_price_pre_night['end_date']['month'] . '-' . $custom_price_pre_night['end_date']['day'];
						$type_start_date = $c_start_date;
						$type_end_date = $c_end_date;
						$this->Item->CustomPricePerNight->validate = $custom_night_validate ;
						unset($this->Item->CustomPricePerNight->validate['name']);
						if(isPluginEnabled('Seats') && !empty($custom_price_pre_night['is_seating_selection'])){
							$custom_data_val['hall_id'] = $custom_price_pre_night['hall_id'];
						}else{
							unset($this->Item->CustomPricePerNight->validate['hall_id']);
						}
						 if(empty($custom_price_pre_night['recurring_day']) || count($custom_price_pre_night['recurring_day']) <= 0){ 	
							unset($this->Item->CustomPricePerNight->validate['repeat_end_date']);
							unset($this->request->data['CustomPricePerNight']['SellTicket'][$key]['repeat_end_date']);
						 }
						$this->CustomPricePerNight->create();					
						$this->CustomPricePerNight->set($custom_data_val);
						$errors = $this->CustomPricePerNight->invalidFields(); 
						if(!empty($errors)){
							foreach($errors as $field => $error){
								$validation_error['SellTicket'][$key][$field] = $this->CustomPricePerNight->validationErrors[$field][0];
							}
							$check_price = false;
						}
						if(empty($custom_price_pre_night['start_date']['day'])){
							$validation_error['SellTicket'][$key]['start_date'] = __l
							('Required');
                            $check_price = false;
						}
						if(empty($custom_price_pre_night['end_date']['day'])){
							$validation_error['SellTicket'][$key]['end_date'] = __l
							('Required');
                            $check_price = false;
						}
						if(!empty($custom_price_pre_night['repeat_end_date']['day']) && !empty($custom_price_pre_night['repeat_end_date']['month']) && !empty($custom_price_pre_night['repeat_end_date']['year'])){
							$repeat_end_date = $custom_price_pre_night['repeat_end_date']['year'] . '-' . $custom_price_pre_night['repeat_end_date']['month'] . '-' . $custom_price_pre_night['repeat_end_date']['day'];
							$repeat_chk_end_dt = strtotime($repeat_end_date);
							if(!empty($custom_price_pre_night['recurring_day']) && count($custom_price_pre_night['recurring_day']) > 0 && !empty($repeat_chk_end_dt) && $repeat_chk_end_dt < strtotime(date('Y-m-d'))){
								$validation_error['SellTicket'][$key]['repeat_end_date'] = __l('Repeat end date should be greater than current date');
								$check_price = false;
							} elseif(!empty($custom_price_pre_night['recurring_day']) && count($custom_price_pre_night['recurring_day']) > 0 && !empty($repeat_chk_end_dt) &&  strtotime($type_start_date) >= $repeat_chk_end_dt){
								$validation_error['SellTicket'][$key]['repeat_end_date'] = __l('Repeat end date should be greater than start date');
								$check_price = false;
							}
						}
						if(!empty($this->request->data['CustomPricePerType'])){
							unset($this->request->data['CustomPricePerType'][$key][0]);
							foreach($this->request->data['CustomPricePerType'][$key] as $sub_key => $custom_price_pre_type){
								if(isPluginEnabled('Seats')){								
									App::import('Model', 'Seats.Partition');
									$this->Partition = new Partition();
									$partitions = $this->Partition->find('list', array(
										'conditions' => array(
											'Partition.hall_id' => $custom_price_pre_night['hall_id'],
											'Partition.is_active' => 1
										) ,
										'order' => array(
											'Partition.name' => 'ASC',
										) ,
										'recursive' => -1
									));
									$this->request->data['CustomPricePerType'][$key][$sub_key]['partitions'] = $partitions;
								} 
								if(isset($custom_price_pre_type['id']) && !isset($custom_price_pre_type['name']))
								{
									continue;
								}
								$custom_price_type_data = array();
								$custom_price_type_data['CustomPricePerType']['name'] = !empty($custom_price_pre_type['name']) ? $custom_price_pre_type['name'] : '';
								$custom_price_type_data['CustomPricePerType']['max_number_of_quantity'] = !empty($custom_price_pre_type['max_number_of_quantity']) ? $custom_price_pre_type['max_number_of_quantity'] : 0;
								$custom_price_type_data['CustomPricePerType']['price'] = !empty($custom_price_pre_type['price']) ? $custom_price_pre_type['price'] : 0.00; 
								$this->CustomPricePerType->validate = $custom_type_validate;			
								if(!isPluginEnabled('Seats') || empty($custom_price_pre_night['is_seating_selection'])){
									unset($this->Item->CustomPricePerType->validate['partition_id']);
								} else {
									$custom_price_type_data['CustomPricePerType']['partition_id'] = $custom_price_pre_type['partition_id'];
								}
								$this->CustomPricePerType->create();
								$this->CustomPricePerType->set($custom_price_type_data);
								$errors = $this->CustomPricePerType->invalidFields(); 
								 if (!empty($errors)){
									foreach($errors as $field => $error){
										$validation_error[$key][$sub_key][$field] = $this->CustomPricePerType->validationErrors[$field][0];
									}							
									$check_price_type = false;
								 }
								 if(empty($custom_price_pre_type['start_time']['hour']) && empty($custom_price_pre_type['start_time']['min']) && empty($custom_price_pre_type['start_time']['meridian'])){
									$validation_error[$key][$sub_key]['start_time'] = __l
									('Required');
									$check_price_type = false;
								}
								if(empty($custom_price_pre_type['end_time']['hour']) && empty($custom_price_pre_type['end_time']['min']) && empty($custom_price_pre_type['end_time']['meridian'])){
									$validation_error[$key][$sub_key]['end_time'] = __l
									('Required');
									$check_price_type = false;
								}
							}
							
						}
					}												
                }
            }
			// flexible form validates
			$main_detail_valid = true;
			$sub_detail_valid = true;
			$sub_detail_price_valid = true;			
			$is_form_valid = true;
			if (!empty($this->request->data['Item']['is_have_definite_time']) && (!empty($this->request->data['Item']['is_people_can_book_my_time']))) {			
				if(!empty($this->request->data['CustomPricePerNight']['main_details'])){
					unset($this->Item->CustomPricePerNight->validate['name']);
					unset($this->Item->CustomPricePerNight->validate['quantity']);
					unset($this->Item->CustomPricePerNight->validate['repeat_end_date']);
					unset($this->Item->CustomPricePerNight->validate['price_per_hour']);
					unset($this->Item->CustomPricePerNight->validate['price_per_day']);
					unset($this->Item->CustomPricePerNight->validate['price_per_week']);
					unset($this->Item->CustomPricePerNight->validate['price_per_month']);
					$custom_data_val['CustomPricePerNight']['min_hours'] = $this->request->data['CustomPricePerNight']['main_details']['min_hours'];
					$this->CustomPricePerNight->create();					
					$this->CustomPricePerNight->set($custom_data_val);
					$errors = $this->CustomPricePerNight->invalidFields(); 
					if(!empty($errors)){
						foreach($errors as $field => $error){
							$validation_error['main_details'][$field] = $this->CustomPricePerNight->validationErrors[$field][0];
						}
						$main_detail_valid = false;
					}
				} 
				if (!empty($this->request->data['CustomPricePerNight']['price_detail'])) {
					unset($this->request->data['CustomPricePerNight']['price_detail'][0]);
					unset($this->Item->CustomPricePerNight->validate['min_hours']);
					$tot_sub_qty = 0;
					foreach($this->request->data['CustomPricePerNight']['price_detail'] as $key => $custom_price_per_night){
							//flexible list - custompricepernight remove button - skip validation for delete record from database
						if(!empty($custom_price_per_night)){
							if(isset($custom_price_per_night['id']) && !isset($custom_price_per_night['name'])) {
								continue;
							}
						}
						$custom_price_data = array();
						if(!empty($custom_price_per_night['name'])){
							 $custom_price_data['CustomPricePerNight']['name'] = !empty($custom_price_per_night['name']) ? $custom_price_per_night['name'] : '';
							 $custom_price_data['CustomPricePerNight']['price_per_hour'] = !empty($custom_price_per_night['price_per_hour']) ? $custom_price_per_night['price_per_hour'] : 0.00;
							 $custom_price_data['CustomPricePerNight']['price_per_day'] = !empty($custom_price_per_night['price_per_day']) ? $custom_price_per_night['price_per_day'] : 0.00;
							 $custom_price_data['CustomPricePerNight']['price_per_week'] = !empty($custom_price_per_night['price_per_week']) ? $custom_price_per_night['price_per_week'] : 0.00;
							 $custom_price_data['CustomPricePerNight']['price_per_month'] = !empty($custom_price_per_night['price_per_month']) ? $custom_price_per_night['price_per_month'] : 0.00;
							 if(!empty($custom_price_per_night['repeat_days']) && count($custom_price_per_night['repeat_days']) > 0){
							 $custom_price_data['CustomPricePerNight']['repeat_end_date'] = !empty($custom_price_per_night['repeat_end_date']) ? $custom_price_per_night['repeat_end_date'] : '';
							 }
							 if($custom_price_per_night['type'] == 0){
								 if($custom_price_per_night['price_per_hour'] <= 0 && $custom_price_per_night['price_per_day'] <= 0 && $custom_price_per_night['price_per_week'] <= 0 && $custom_price_per_night['price_per_month'] <= 0) {
									$sub_detail_valid = false;
									$sub_detail_price_valid = false;							
									$validation_error['price_detail'][$key]['price_per_hour'] = __l('Any one price is Required');
								 }
							 }
							if(!empty($custom_price_per_night['repeat_days']) && count($custom_price_per_night['repeat_days']) <= 0){
								unset($this->Item->CustomPricePerNight->validate['repeat_end_date']);
								unset($this->request->data['CustomPricePerNight']['price_detail'][$key]['repeat_end_date']);
								unset($custom_price_data['CustomPricePerNight']['repeat_end_date']);
							}
							$this->CustomPricePerNight->validate = $custom_night_validate;						
							$this->CustomPricePerNight->create();
							$this->CustomPricePerNight->set($custom_price_data);
							$errors = $this->CustomPricePerNight->invalidFields(); 
							 if (!empty($errors)){
								foreach($errors as $field => $error){
									$validation_error['price_detail'][$key][$field] = $this->CustomPricePerNight->validationErrors[$field][0];
								}							
								$sub_detail_valid = false;
							 }
							 if(!empty($custom_price_per_night['start_date']['day']) && !empty($custom_price_per_night['end_date']['day'])){
								$c_start_date = $custom_price_per_night['start_date']['year'] . '-' . $custom_price_per_night['start_date']['month'] . '-' . $custom_price_per_night['start_date']['day'];
								$c_end_date = $custom_price_per_night['end_date']['year'] . '-' . $custom_price_per_night['end_date']['month'] . '-' . $custom_price_per_night['end_date']['day'];
								$type_start_date = $c_start_date;
								$type_end_date = $c_end_date;	
								if (strtotime($type_start_date) <= strtotime(date('Y-m-d'))) {
									$validation_error['price_detail'][$key]['start_date'] = __l
									('Start Date should be greater than current date');
									$sub_detail_valid = false;
								} elseif(strtotime($type_start_date) >= strtotime($type_end_date)){
									$validation_error['price_detail'][$key]['start_date'] = __l
									('Start Date should be less than End date');
									$sub_detail_valid = false;
								}
							}
							if(!empty($custom_price_per_night['repeat_end_date']['day']) && !empty($custom_price_per_night['repeat_end_date']['month']) && !empty($custom_price_per_night['repeat_end_date']['year'])){
								$repeat_end_date = $custom_price_per_night['repeat_end_date']['year'] . '-' . $custom_price_per_night['repeat_end_date']['month'] . '-' . $custom_price_per_night['repeat_end_date']['day'];
								$repeat_chk_end_dt = strtotime($repeat_end_date);
								if(!empty($repeat_chk_end_dt) && $repeat_chk_end_dt < strtotime(date('Y-m-d'))){
									$validation_error['price_detail'][$key]['repeat_end_date'] = __l('Repeat end date should be greater than current date');
									$sub_detail_valid = false;
								}
								if(!empty($custom_price_per_night['repeat_days']) && count($custom_price_per_night['repeat_days']) <= 0 && $repeat_chk_end_dt < strtotime(date('Y-m-d'))){
									$validation_error['SellTicket'][$key]['repeat_end_date'] = __l('Repeat end date should be greater than current date');
									$check_price = false;
								} elseif(!empty($custom_price_per_night['repeat_days']) && count($custom_price_per_night['repeat_days']) <= 0 && !empty($repeat_chk_end_dt) &&  strtotime($type_start_date) >= $repeat_chk_end_dt){
									$validation_error['SellTicket'][$key]['repeat_end_date'] = __l('Repeat end date should be greater than start date');
									$check_price = false;
								}
							}
						}
					}
				}
			}
			/// Flexible form validate ends						
			$this->Item->CustomPricePerNight->validationErrors = $validation_error;
			$this->Item->CustomPricePerType->validationErrors = $validation_error;
			$is_form_valid = true;		
            if ($is_form_valid &$this->Item->validates($this->request->data) &$check_have_and_request &$check_price &$check_price_type &$main_detail_valid &$sub_detail_valid) {
                $this->request->data['Item']['category_id'] = $this->request->data['Item']['sub_category_id'];
                if ($this->Item->save($this->request->data)) {
                    $item_id = $this->request->data['Item']['id'];
					// Attachment
					if(!empty($this->request->data['Attachment'])) {
						unset($this->request->data['Attachment']['filename']);
						foreach($this->request->data['Attachment'] As $key => $attach) {
							$data = array();
							$data['Attachment']['id'] = $key;
							$data['Attachment']['description'] = $attach['description'];
							$this->Item->Attachment->save($data);
						}
					}
					//flexible form
					if ($this->request->data['Item']['is_have_definite_time'] && !empty($this->request->data['Item']['is_people_can_book_my_time']) && $this->request->data['Item']['is_people_can_book_my_time'] && !empty($this->request->data['CustomPricePerNight']['main_details'])) {
						$custom_data = array();
						$custom_data['CustomPricePerNight']['item_id'] = $item_id;
						$custom_data['CustomPricePerNight']['parent_id'] = 0;
						$custom_data['CustomPricePerNight']['custom_source_id'] = 0;
						$custom_data['CustomPricePerNight']['is_timing'] = $this->request->data['CustomPricePerNight']['main_details']['is_timing'];
						$custom_data['CustomPricePerNight']['min_hours'] = $this->request->data['CustomPricePerNight']['main_details']['min_hours'];
						if(isset($this->request->data['CustomPricePerNight']['main_details']['id'])) {
							$custom_data['CustomPricePerNight']['id'] = $this->request->data['CustomPricePerNight']['main_details']['id'];
						}
						unset($this->Item->CustomPricePerNight->data['CustomPricePerNight']['name']);
						$this->Item->CustomPricePerNight->save($custom_data); 
					}
					if (!empty($this->request->data['Item']['is_have_definite_time']) && !empty($this->request->data['Item']['is_people_can_book_my_time']) && !empty($this->request->data['CustomPricePerNight']['price_detail'])) {
						unset($this->request->data['CustomPricePerNight']['price_detail'][0]);
						//to delete and unset the removed item in sublist
						foreach($this->request->data['CustomPricePerNight']['price_detail'] As $key => $custom_price_per_night) {
							if(!empty($custom_price_per_night['id']) && empty($custom_price_per_night['name'])) {
								$this->Item->CustomPricePerNight->delete($custom_price_per_night['id']);
								unset($this->request->data['CustomPricePerNight']['price_detail'][$key]);
								continue;
							}
						}
						foreach($this->request->data['CustomPricePerNight']['price_detail'] As $key => $custom_price_pre_night) {
							$custom_data_price = array();
							$itemprice = array();
							$custom_data_price['CustomPricePerNight']['item_id'] = $item_id;
							$custom_data_price['CustomPricePerNight']['name'] = $custom_price_pre_night['name'];
							$custom_data_price['CustomPricePerNight']['description'] = $custom_price_pre_night['description'];
							$recurring_day = '';
							if(!empty($custom_price_pre_night['repeat_days'])) {
								foreach($custom_price_pre_night['repeat_days'] As $key => $val) {
									if(!empty($recurring_day)) {
										$recurring_day .= ',';
									}
									$recurring_day .= $key;
								}
								if(count($recurring_day) > 0 && !empty($custom_price_pre_night['repeat_end_date']['year'])&& !empty($custom_price_pre_night['repeat_end_date']['month'])&& !empty($custom_price_pre_night['repeat_end_date']['day'])){
									$custom_data_price['CustomPricePerNight']['repeat_end_date'] = $custom_price_pre_night['repeat_end_date']['year'].'-'.$custom_price_pre_night['repeat_end_date']['month'].'-'.$custom_price_pre_night['repeat_end_date']['day'];
								}
							}
							$custom_data_price['CustomPricePerNight']['repeat_days'] = $recurring_day;	
							if(!empty($custom_price_pre_night['start_date']['day']) && !empty($custom_price_pre_night['start_date']['month'])) { 
								$custom_data_price['CustomPricePerNight']['start_date'] = $custom_price_pre_night['start_date']['year'].'-'.$custom_price_pre_night['start_date']['month'].'-'.$custom_price_pre_night['start_date']['day'];
							} else {
								$custom_data_price['CustomPricePerNight']['start_date'] = date('Y-m-d');
							}
							if(!empty($custom_price_pre_night['start_time']['hour']) && !empty($custom_price_pre_night['start_time']['min'])) {
							$start_hour = $custom_price_pre_night['start_time']['hour'];
							if(strtolower($custom_price_pre_night['start_time']['meridian']) == 'am' && $custom_price_pre_night['start_time']['hour'] == '12'){
								$start_hour = '00';
							} elseif(strtolower($custom_price_pre_night['start_time']['meridian']) == 'pm' && $custom_price_pre_night['start_time']['hour'] < 12){
								$start_hour = $custom_price_pre_night['start_time']['hour'] + 12;
							}
							$custom_data_price['CustomPricePerNight']['start_time'] = $start_hour.':'.$custom_price_pre_night['start_time']['min'];
						} else {
							$custom_data_price['CustomPricePerNight']['start_time'] = '00:00:01';
						}
						if(!empty($custom_price_pre_night['end_time']['hour']) && !empty($custom_price_pre_night['end_time']['min'])) {
							$end_hour = $custom_price_pre_night['end_time']['hour'];
							if(strtolower($custom_price_pre_night['end_time']['meridian']) == 'am' && $custom_price_pre_night['end_time']['hour'] == '12'){
								$end_hour = '00';
							} elseif(strtolower($custom_price_pre_night['end_time']['meridian']) == 'pm' && $custom_price_pre_night['end_time']['hour'] < 12){
								$end_hour = $custom_price_pre_night['end_time']['hour'] + 12;
							}
							$custom_data_price['CustomPricePerNight']['end_time'] = $end_hour.':'.$custom_price_pre_night['end_time']['min'];
						} else {
							$custom_data_price['CustomPricePerNight']['end_time'] = '23:59:59';
						}
							$custom_data_price['CustomPricePerNight']['end_date'] = $custom_price_pre_night['end_date'];
							$custom_data_price['CustomPricePerNight']['price_per_hour'] = (!empty($custom_price_pre_night['price_per_hour'])) ? $custom_price_pre_night['price_per_hour'] : 0;
							$custom_data_price['CustomPricePerNight']['price_per_day'] = (!empty($custom_price_pre_night['price_per_day'])) ? $custom_price_pre_night['price_per_day'] : 0;
							$custom_data_price['CustomPricePerNight']['price_per_week'] = (!empty($custom_price_pre_night['price_per_week'])) ?  $custom_price_pre_night['price_per_week'] : 0;
							$custom_data_price['CustomPricePerNight']['price_per_month'] = (!empty($custom_price_pre_night['price_per_month'])) ?  $custom_price_pre_night['price_per_month'] : 0;
							$custom_data_price['CustomPricePerNight']['custom_source_id'] = 0;
							//validates custompricepernight
							if(!empty($custom_price_pre_night['id'])){
								$custom_data_price['CustomPricePerNight']['id'] = $custom_price_pre_night['id'];
							} else {
								if(isset($this->request->data['CustomPricePerNight']['main_details']['id'])) {
									$custom_data_price['CustomPricePerNight']['parent_id'] = $this->request->data['CustomPricePerNight']['main_details']['id'];
								} else {
									$custom_data_price['CustomPricePerNight']['parent_id'] = $this->Item->CustomPricePerNight->getLastInsertId();
								}
								$this->Item->CustomPricePerNight->create();
								$this->Item->CustomPricePerNight->set($custom_data_price);								
							}
							$this->Item->CustomPricePerNight->save($custom_data_price);
							// minimum price
							if($item_min_price <= 0){
								if (!empty($custom_price_pre_night['price_per_hour']) && $custom_price_pre_night['price_per_hour'] > 0) {
									$item_min_price = $custom_price_pre_night['price_per_hour'];
									$item_custom_source = ConstCustomSource::Hour;
								} else if (!empty($custom_price_pre_night['price_per_day']) && $custom_price_pre_night['price_per_day'] > 0) {
									$item_min_price = $custom_price_pre_night['price_per_day'];
									$item_custom_source = ConstCustomSource::Day;
								} else if (!empty($custom_price_pre_night['price_per_week']) && $custom_price_pre_night['price_per_week'] > 0) {
									$item_min_price = $custom_price_pre_night['price_per_week'];
									$item_custom_source = ConstCustomSource::Week;
								} else if (!empty($custom_price_pre_night['price_per_month']) && $custom_price_pre_night['price_per_month'] > 0) {
									$item_min_price = $custom_price_pre_night['price_per_month'];
									$item_custom_source = ConstCustomSource::Month;
								}
							}
						}
						if(isset($this->request->data['CustomPricePerNight']['main_details']['id'])) {
							$custom_min_data['CustomPricePerNight']['id'] = $this->request->data['CustomPricePerNight']['main_details']['id'];
						}
						$custom_min_data['CustomPricePerNight']['minimum_price'] = $item_min_price;
						$this->Item->CustomPricePerNight->save($custom_min_data);
					}
					//Fixed form
					if (!empty($this->request->data['Item']['is_have_definite_time']) && !empty($this->request->data['Item']['is_sell_ticket']) && !empty($this->request->data['CustomPricePerNight']['SellTicket'])) {				
						$check_price_free = 0;
						$parent_ids = array();
						$sell_inc = 1;
						unset($this->request->data['CustomPricePerType'][0]);
						if(!empty($this->request->data['CustomPricePerNight']['SellTicket'])){
							//custompricepernight - fixed main detail - remove button - delete record from database
							foreach($this->request->data['CustomPricePerNight']['SellTicket'] as $key => $custom_price_pre_night) {
								if(!empty($custom_price_pre_night['id']) && empty($custom_price_pre_night['start_date'])) {
									$custom_price_type_ids = $this->Item->CustomPricePerType->find('all', array(
													'conditions' => array(
													'CustomPricePerType.custom_price_per_night_id' => $custom_price_pre_night['id']
													),
													'recursive' => -1
												));
									foreach($custom_price_type_ids as $typ_key => $custom_per_type_del)
									{
										$this->Item->CustomPricePerType->delete($custom_per_type_del['CustomPricePerType']['id']);
									}
									$this->Item->CustomPricePerNight->delete($custom_price_pre_night['id']);
									unset($this->request->data['CustomPricePerNight']['SellTicket'][$key]);
									continue;
								}
								else if(!empty($custom_price_pre_night['id']) && !empty($custom_price_pre_night['start_date'])) {
									$custom_type_check_id = $this->Item->CustomPricePerType->find('list', array(
													'conditions' => array(
													'CustomPricePerType.custom_price_per_night_id' => $custom_price_pre_night['id']
													),
													'fields' => array(
															'id',
															'id'
													),
													'recursive' => -1
												));
									foreach($this->request->data['CustomPricePerType'][$key] as $chk_key => $custom_type_chk_del){
										if(isset($custom_type_chk_del['id']) && in_array($custom_type_chk_del['id'],$custom_type_check_id)){
											unset($custom_type_check_id[$custom_type_chk_del['id']]);
										}
									}
									foreach($custom_type_check_id as $chk_key1 => $custom_type_chk_del1) {
										$this->Item->CustomPricePerType->delete($custom_type_chk_del1);
									}
								}
								//fixed list - sublist - delete
								foreach($this->request->data['CustomPricePerType'][$key] as $typ_key => $custom_per_type_del) {
									if(!empty($custom_per_type_del['id']) && empty($custom_per_type_del['name'])) {
										$this->Item->CustomPricePerType->delete($custom_per_type_del['id']);
										unset($this->request->data['CustomPricePerType'][$key][$typ_key]);
										continue;
									}
								}
							}

							foreach($this->request->data['CustomPricePerNight']['SellTicket'] as $key => $custom_price_pre_night) {
								$custom_data = array();								
								$custom_data['CustomPricePerNight']['item_id'] = $item_id;
								$custom_data['CustomPricePerNight']['repeat_days'] = (!empty($custom_price_pre_night['recurring_day'])) ? implode(',', $custom_price_pre_night['recurring_day']) : '';
								if(count($custom_price_pre_night['recurring_day']) > 0 && !empty($custom_price_pre_night['repeat_end_date']['year']) && !empty($custom_price_pre_night['repeat_end_date']['month']) && !empty($custom_price_pre_night['repeat_end_date']['day'])){
									$custom_data['CustomPricePerNight']['repeat_end_date'] = $custom_price_pre_night['repeat_end_date']['year'].'-'.$custom_price_pre_night['repeat_end_date']['month'].'-'.$custom_price_pre_night['repeat_end_date']['day'];
								}
								$custom_data['CustomPricePerNight']['start_date'] = $custom_price_pre_night['start_date']['year'].'-'.$custom_price_pre_night['start_date']['month'].'-'.$custom_price_pre_night['start_date']['day'];
								$custom_data['CustomPricePerNight']['end_date'] = $custom_price_pre_night['end_date']['year'].'-'.$custom_price_pre_night['end_date']['month'].'-'.$custom_price_pre_night['end_date']['day'];
								$custom_data['CustomPricePerNight']['is_tipped'] = (!empty($this->request->data['Item']['is_tipping_point'])) ? 0 : 1;
								$custom_data['CustomPricePerNight']['is_seating_selection'] = $custom_price_pre_night['is_seating_selection'];
								$custom_data['CustomPricePerNight']['hall_id'] = (!empty($custom_price_pre_night['hall_id'])) ? $custom_price_pre_night['hall_id'] : null;
								if(!isset($custom_price_pre_night['id'])){
									$this->Item->CustomPricePerNight->create();
								} else {
									$custom_data['CustomPricePerNight']['id'] = $custom_price_pre_night['id'];
									$custom_price_per_night_id = $custom_price_pre_night['id'];
								}
								$this->Item->CustomPricePerNight->save($custom_data);
								if(!isset($custom_price_pre_night['id'])){
									$custom_price_per_night_id = $this->Item->CustomPricePerNight->getLastInsertId();
								}
								$min_price = 0;
								$is_unlimited = 1;
								$total_available_count = 0;
								unset($this->request->data['CustomPricePerType'][$key][0]);
								foreach($this->request->data['CustomPricePerType'][$key] as $sub_key => $custom_price_pre_type) {
									unset($custom_price_pre_type[0]);
									$price = !empty($custom_price_pre_type['price']) ? $custom_price_pre_type['price'] : 0.00;
										if ($min_price <= 0) {
											$min_price = $custom_price_pre_type['price'];
										}
										if ($item_min_price <= 0) {
											$item_min_price = $custom_price_pre_type['price'];
										}
										if(!empty($custom_price_pre_type['max_number_of_quantity'])) {
											$total_available_count = $total_available_count + $custom_price_pre_type['max_number_of_quantity'];
										} else {
											$is_unlimited = 0;
										}
										$custom_price_data = array();
										$custom_price_data['CustomPricePerType']['item_id'] = $item_id;
										$custom_price_data['CustomPricePerType']['custom_price_per_night_id'] = $custom_price_per_night_id;
										$custom_price_data['CustomPricePerType']['name'] = $custom_price_pre_type['name'];
										$custom_price_data['CustomPricePerType']['description'] = $custom_price_pre_type['description'];
										$custom_price_data['CustomPricePerType']['price'] = $price;
										if(!empty($custom_price_pre_type['max_number_of_quantity'])){
											$custom_price_data['CustomPricePerType']['max_number_of_quantity'] = $custom_price_pre_type['max_number_of_quantity'];
										}
										$custom_price_data['CustomPricePerType']['is_advanced_enabled'] = 0;
										$start_type_hour = $custom_price_pre_type['start_time']['hour'];
										if(strtolower($custom_price_pre_type['start_time']['meridian']) == 'am' && ($custom_price_pre_type['start_time']['hour'] == '12' || $custom_price_pre_type['start_time']['hour'] == '') && $custom_price_pre_type['start_time']['min'] != ''){
											$start_type_hour = '00';
										} elseif(strtolower($custom_price_pre_type['start_time']['meridian']) == 'pm' && $custom_price_pre_type['start_time']['hour'] < 12){
											$start_type_hour = $custom_price_pre_type['start_time']['hour'] + 12;
										}
										$end_hour = $custom_price_pre_type['end_time']['hour'];
										if(strtolower($custom_price_pre_type['end_time']['meridian']) == 'am' && ($custom_price_pre_type['end_time']['hour'] == '12' || $custom_price_pre_type['end_time']['hour'] == '') && $custom_price_pre_type['end_time']['min'] != ''){
											$end_hour = '00';
										} elseif(strtolower($custom_price_pre_type['end_time']['meridian']) == 'pm' && $custom_price_pre_type['end_time']['hour'] < 12){
											$end_hour = $custom_price_pre_type['end_time']['hour'] + 12;
										}
										$custom_price_data['CustomPricePerType']['start_time'] = $start_type_hour.':'.$custom_price_pre_type['start_time']['min'];
										$custom_price_data['CustomPricePerType']['end_time'] = $end_hour.':'.$custom_price_pre_type['end_time']['min'];
										if(isset($custom_price_pre_type['partition_id'])) {
											$custom_price_data['CustomPricePerType']['partition_id'] = !(empty($custom_price_pre_type['partition_id'])) ? $custom_price_pre_type['partition_id'] : null;
										}
										if(!empty($custom_price_pre_type['id'])){
											$custom_price_data['CustomPricePerType']['id'] = $custom_price_pre_type['id'];
											$custom_price_per_type_id =$custom_price_pre_type['id'];
											/* TODO: variable temp_type declared for seat count reset */
											$temp_type = $this->Item->CustomPricePerType->read(null, $custom_price_pre_type['id']);
											$custom_price_data['CustomPricePerType']['available_seat_count'] = $temp_type['CustomPricePerType']['available_seat_count'];
											$custom_price_data['CustomPricePerType']['unavailable_seat_count'] = $temp_type['CustomPricePerType']['unavailable_seat_count'];
											$custom_price_data['CustomPricePerType']['no_seat_count'] = $temp_type['CustomPricePerType']['no_seat_count'];	
										} else {
											$this->Item->CustomPricePerType->create();
										}
										$this->Item->CustomPricePerType->save($custom_price_data);
										if(!isset($custom_price_pre_type['id'])){
											$custom_price_per_type_id = $this->Item->CustomPricePerType->getLastInsertId();
										}
									// check hall & partition changed and reset the values
									if(isPluginEnabled('Seats')){
										App::import('Model', 'Seats.Seat');
										$this->Seat = new Seat();
										App::import('Model', 'Seats.CustomPricePerTypesSeat');
										$this->CustomPricePerTypesSeat = new CustomPricePerTypesSeat();
												// check hall & partitin is changed 
											if((!empty($custom_price_pre_night['is_enable_seat_old_val']) && $custom_price_pre_night['is_seating_selection'] != $custom_price_pre_night['is_enable_seat_old_val']) || (!empty($custom_price_pre_night['hall_old_id']) && $custom_price_pre_night['hall_id'] != $custom_price_pre_night['hall_old_id']) || (!empty($custom_price_pre_type['partition_old_id']) && $custom_price_pre_type['partition_old_id'] != $custom_price_pre_type['partition_id'])){
												// if changed delete records
												$this->CustomPricePerTypesSeat->deleteAll(array('CustomPricePerTypesSeat.custom_price_per_type_id' => $custom_price_per_type_id), false);
											}
											if(!empty($custom_price_pre_type['partition_id']) && !empty($custom_price_pre_night['hall_id'])){
												$custom_price_per_types_seat = $this->CustomPricePerTypesSeat->find('all', array(
													'conditions' => array(
														'CustomPricePerTypesSeat.hall_id' => $custom_price_pre_night['hall_id'],
														'CustomPricePerTypesSeat.partition_id' => $custom_price_pre_type['partition_id'],
														'CustomPricePerTypesSeat.custom_price_per_type_id' => $custom_price_per_type_id,
													),             
													'recursive' => -1
												));
												// insert new records code if not available in custom_price_per_types_seats
												if(count($custom_price_per_types_seat) == 0){
													$seats = $this->Seat->find('all', array(
														'conditions' => array(
															'Seat.hall_id' => $custom_price_pre_night['hall_id'],
															'Seat.partition_id' => $custom_price_pre_type['partition_id'],
														),
														'order' => array(
															'Seat.id' => 'ASC',
														) ,                
														'recursive' => -1
													));
													$total_avail_seats = $this->Seat->find('count', array(
														'conditions' => array(
															'Seat.hall_id' => $custom_price_pre_night['hall_id'],
															'Seat.partition_id' => $custom_price_pre_type['partition_id'],
															'Seat.seat_status_id' => array(ConstSeatStatus::Available, ConstSeatStatus::Blocked, ConstSeatStatus::Booked, ConstSeatStatus::WaitingForAcceptance)
														),                
														'recursive' => -1
													));												
													$stored = array('CustomPricePerTypesSeat' => array());
													foreach($seats as $seat){
														$tmp = array();
														$tmp['item_id'] = $item_id;
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
													$this->CustomPricePerTypesSeat->saveAll($stored['CustomPricePerTypesSeat']);
													$custom_type_data['CustomPricePerType']['id'] = $custom_price_per_type_id;
													$custom_type_data['CustomPricePerType']['max_number_of_quantity'] = $total_avail_seats;
													$custom_type_data['CustomPricePerType']['partition_id'] = $custom_price_pre_type['partition_id'];
													$this->Item->CustomPricePerType->save($custom_type_data);
												
												}
											}
										
									}
								}
								$custom_min_data['CustomPricePerNight']['id'] = $custom_price_per_night_id;
								$custom_min_data['CustomPricePerNight']['minimum_price'] = $min_price;
								$this->Item->CustomPricePerNight->save($custom_min_data);
							}
					}
				
                }
					$min_data['Item']['id'] = $item_id;
                    $min_data['Item']['minimum_price'] = $item_min_price;
                    $min_data['Item']['custom_source_id'] = $item_custom_source;
                    $min_data['Item']['is_free'] = (!empty($item_min_price)) ? 0 : 1;
                    $this->Item->save($min_data);
					if ($this->RequestHandler->prefers('json')) {
						$message = array("message" => sprintf(__l('%s updated. ') , Configure::read('item.alt_name_for_item_singular_caps')), "error" => 0);
					}else{
						if ($this->Auth->user('role_id') == ConstUserTypes::Admin) {
							$this->redirect(array(
								'action' => 'index',
							));
						} else {
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'index',
								'type' => 'myitems'
							));
						}
					}
                } else {
					if ($this->RequestHandler->prefers('json')) {
						$message = array("message" => sprintf(__l('%s could not be updated. Please, try again.') , Configure::read('item.alt_name_for_item_singular_caps')) , "error" => 1);
					}else{
						$this->Session->setFlash(sprintf(__l('%s could not be updated. Please, try again.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					}
                }
            } else {
				if (empty($check_price)) {
					if ($this->RequestHandler->prefers('json')) {
						$message = array("message" => sprintf(__l('%s could not be updated. Pricing given date not valid.') , Configure::read('item.alt_name_for_item_singular_caps')) , "error" => 1);
					}else{
						$this->Session->setFlash(sprintf(__l('%s could not be updated. Pricing given date not valid.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					}                
                } elseif (empty($check_have_and_request)) {
					if ($this->RequestHandler->prefers('json')) {
						$message = array("message" => sprintf(__l('%s could not be updated. Please, enable either request or user to book.') , Configure::read('item.alt_name_for_item_singular_caps')) , "error" => 1);
					}else{
						$this->Session->setFlash(sprintf(__l('%s could not be updated. Please, enable either request or user to book.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					}
                } elseif (empty($check_price_type)) {
					if ($this->RequestHandler->prefers('json')) {
						$message = array("message" => sprintf(__l('%s could not be added. Please, enter the valid pricing type details.') , Configure::read('item.alt_name_for_item_singular_caps')) , "error" => 1);
					}else{
						$this->Session->setFlash(sprintf(__l('%s could not be added. Please, enter the valid pricing type details.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					}                    
                } else {
					if ($this->RequestHandler->prefers('json')) {
						$message = array("message" => sprintf(__l('%s could not be updated. Please, try again.') , Configure::read('item.alt_name_for_item_singular_caps')) , "error" => 1);
					}else{
						$this->Session->setFlash(sprintf(__l('%s could not be updated. Please, try again.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					}                    
                }
				if(!empty($this->request->data['Item']['id'])) {
					$attachments = $this->Item->Attachment->find('all', array(
						'conditions' => array(
							'Attachment.foreign_id' => $this->request->data['Item']['id'],
							'Attachment.class' => 'Item',
						) ,
						'recursive' => -1 ,
					));
					$attach = array();
					if(!empty($attachments)) {
						foreach($attachments As $attachment) {
							$attach['Attachment'][] = $attachment['Attachment'];
						}
						$this->request->data['Attachment'] = $attach['Attachment'];
					}
				}
            }
        } else {
            $this->request->data = $this->Item->find('first', array(
                'conditions' => array(
                    'Item.id' => $id,
                ) ,
                'contain' => array(
                    'Category',
                    'CustomPricePerType' => array(
						'conditions' => array(
							'CustomPricePerType.is_primary' => 1,
						) ,
						'order' => array(
							'CustomPricePerType.id' => 'ASC'
						) ,
					),
                    'Attachment' => array(
						'order' => array(
							'Attachment.id' => 'ASC'
						) ,
					),
					'User'
                ) ,
                'recursive' => 1
            ));
			$this->request->data['Item']['username'] = $this->request->data['User']['username'];
            $custom_price_per_nights = $this->Item->CustomPricePerNight->find('all', array(
                'conditions' => array(
                    'CustomPricePerNight.item_id' => $this->request->data['Item']['id'],
                    'is_custom' => 0,
                ) ,
				'contain' => array(
					'CustomPricePerType' => array(
						'order' => array(
							'CustomPricePerType.id' => 'ASC'
						)
					)
				),
				'order' => array(
					'CustomPricePerNight.id' => 'ASC'
				) ,
                'recursive' => 1
            ));			
            $price_type = '';
            if ($this->request->data['Item']['is_sell_ticket']) {
                $price_type = 'SellTicket';
                $this->request->data['Item']['price_type'] = 2;
                $this->request->data['Item']['booking_type'] = 2;
            } elseif ($this->request->data['Item']['is_people_can_book_my_time']) {
                $price_type = 'price_detail';
                $this->request->data['Item']['price_type'] = 1;
                $this->request->data['Item']['booking_type'] = 1;
            }
			if(isPluginEnabled('Seats')){
				App::import('Model', 'Seats.Hall');
				$this->Hall = new Hall();							
				$halls = $this->Hall->find('list', array(
					'conditions' => array(
						'Hall.user_id' => $this->Auth->user('id'),
						'Hall.is_active' => 1
					) ,
					'order' => array(
						'Hall.name' => 'ASC',
					) ,
					'recursive' => -1
				));
			}
			$i = 1;			
            if (!empty($custom_price_per_nights) && !empty($price_type)) {
                foreach($custom_price_per_nights As $key => $custom_price_per_night) {
					if($custom_price_per_night['CustomPricePerNight']['parent_id'] == 0 && $this->request->data['Item']['is_people_can_book_my_time']){
						$this->request->data['CustomPricePerNight']['main_details']['min_hours'] = $custom_price_per_night['CustomPricePerNight']['min_hours'];
						$this->request->data['CustomPricePerNight']['main_details']['is_timing'] = $custom_price_per_night['CustomPricePerNight']['is_timing'];
						$this->request->data['CustomPricePerNight']['main_details']['id'] = $custom_price_per_night['CustomPricePerNight']['id'];
					} else {					
						$start_date = !empty($custom_price_per_night['CustomPricePerNight']['start_date']) ? $custom_price_per_night['CustomPricePerNight']['start_date']: '0000-00-00';
						$custom_price_per_night['CustomPricePerNight']['start_date'] = $start_date . ' ' . $custom_price_per_night['CustomPricePerNight']['start_time'];
						$end_date = !empty($custom_price_per_night['CustomPricePerNight']['end_date']) ? $custom_price_per_night['CustomPricePerNight']['end_date']: '0000-00-00';
						$custom_price_per_night['CustomPricePerNight']['end_date'] = $end_date . ' ' . $custom_price_per_night['CustomPricePerNight']['end_time'];
						$repeat_days = array();
						if(!empty($custom_price_per_night['CustomPricePerNight']['repeat_days'])) {
							$a = $b = explode(',', $custom_price_per_night['CustomPricePerNight']['repeat_days']);
							$repeat_days = array_combine($a, $a);
						}
						$custom_price_per_night['CustomPricePerNight']['repeat_days'] = $repeat_days;
						$custom_price_per_night['CustomPricePerNight']['recurring_day'] = $repeat_days;
						$custom_price_per_night['CustomPricePerNight']['id'] = $custom_price_per_night['CustomPricePerNight']['id'];
						if($custom_price_per_night['CustomPricePerNight']['price_per_hour'] <= 0 && $custom_price_per_night['CustomPricePerNight']['price_per_day'] <= 0 && $custom_price_per_night['CustomPricePerNight']['price_per_week'] <=0 && $custom_price_per_night['CustomPricePerNight']['price_per_month'] <= 0){
							$custom_price_per_night['CustomPricePerNight']['type'] = 1;
						} else {
							$custom_price_per_night['CustomPricePerNight']['type'] = 0;
						}
							if(isPluginEnabled('Seats')){
								$custom_price_per_night['CustomPricePerNight']['is_enable_seat_old_val'] = $custom_price_per_night['CustomPricePerNight']['is_seating_selection'];
								$custom_price_per_night['CustomPricePerNight']['hall_old_id'] = $custom_price_per_night['CustomPricePerNight']['hall_id'];
								$custom_price_per_night['CustomPricePerNight']['halls'] = $halls;								
							}
						$this->request->data['CustomPricePerNight'][$price_type][$i] = $custom_price_per_night['CustomPricePerNight'];						
						if(!empty($custom_price_per_night['CustomPricePerType'])){
							$j = 1;
							foreach($custom_price_per_night['CustomPricePerType'] as $custom_price_per_type){
								if(isPluginEnabled('Seats')){
									$custom_price_per_type['partition_old_id'] = $custom_price_per_type['partition_id'];
									App::import('Model', 'Seats.Partition');
									$this->Partition = new Partition();
									$partitions = $this->Partition->find('list', array(
										'conditions' => array(
											'Partition.hall_id' => $custom_price_per_night['CustomPricePerNight']['hall_id'],
											'Partition.is_active' => 1
										) ,
										'order' => array(
											'Partition.name' => 'ASC',
										) ,
										'recursive' => -1
									));
									$custom_price_per_type['partitions'] = $partitions;
								}
								$this->request->data['CustomPricePerType'][$i][$j] = $custom_price_per_type;
								$j ++;
							}
						}
						$i++;
					}
                }
            }

			if(!empty($this->request->params['pass'][1])){
				$itemUser = $this->Item->ItemUser->find('first', array(
					'conditions' => array(
						'ItemUser.id' => $this->request->params['pass'][1]
					) ,
					'recursive' => 0
				));
				$this->request->data['CustomPricePerNight']['SellTicket'][$i]['start_date'] = $itemUser['ItemUser']['from'];
				$this->request->data['CustomPricePerNight']['SellTicket'][$i]['end_date'] = $itemUser['ItemUser']['to'];
				$this->request->data['Item']['price_type'] = 2;
				$this->request->data['Item']['is_have_definite_time'] = 1;
				$this->request->data['CustomPricePerType'][$i][1]['name'] = '';
			}
            $this->request->data['Item']['is_auto_approve'] = (!empty($this->request->data['Item']['is_auto_approve'])) ? 1 : 0;
            $this->request->data['Item']['is_buyer_as_fee_payer'] = (!empty($this->request->data['Item']['is_buyer_as_fee_payer'])) ? 1 : 0;
            $this->request->data['Item']['is_additional_fee_to_buyer'] = (!empty($this->request->data['Item']['is_additional_fee_to_buyer'])) ? 1 : 0;
            $this->request->data['Item']['sub_category_id'] = $this->request->data['Item']['category_id'];
            $check_category = $this->Item->Category->find('first', array(
                'conditions' => array(
                    'Category.id' => $this->request->data['Item']['sub_category_id']
                ) ,
                'recursive' => -1
            ));
            $this->request->data['Item']['category_id'] = $check_category['Category']['parent_id'];
        }
        if (!empty($this->request->params['named']['category_id'])) {
            $params_category = $this->Item->Category->find('first', array(
                'conditions' => array(
                    'Category.id' => $this->request->params['named']['category_id']
                ) ,
                'recursive' => -1
            ));
            if (!empty($params_category)) {
                if (!$params_category['Category']['parent_id']) {
                    $this->request->data['Item']['category_id'] = $params_category['Category']['id'];
                } else {
                    $this->request->data['Item']['sub_category_id'] = $params_category['Category']['id'];
                    $this->request->data['Item']['category_id'] = $params_category['Category']['parent_id'];
                }
            }
        }
        $sub_categories = array();
        $category_types = array();
        if (!empty($this->request->data['Item']['category_id'])) {
            $sub_categories = $this->Item->Category->find('list', array(
                'conditions' => array(
                    'Category.parent_id' => $this->request->data['Item']['category_id'],
					'Category.is_active' => 1
                ) ,
				'order' => array(
					'Category.name' => 'ASC',
				) ,
                'recursive' => -1
            ));
            if (!empty($this->request->data['Item']['sub_category_id'])) {
                $category_types = $this->Item->CategoryType->find('list', array(
                    'conditions' => array(
                        'CategoryType.category_id' => $this->request->data['Item']['sub_category_id']
                    ) ,
					'order' => array(
						'CategoryType.name' => 'ASC',
					) ,
                    'recursive' => -1
                ));
                $category = $this->Item->Category->find('first', array(
                    'conditions' => array(
                        'Category.id' => $this->request->data['Item']['sub_category_id']
                    ) ,
                    'recursive' => -1
                ));
                if (empty($category)) {
					if ($this->RequestHandler->prefers('json')) {
						$message = array("message" => __l('Invalid request'), "error" => 1);
					}else{
						throw new NotFoundException(__l('Invalid request'));
					}
                }
                $this->loadModel('Items.Form');
                $this->loadModel('Items.FormField');
                $this->loadModel('Items.Item');
                $categoryFormFields = $this->Form->buildSchema($category['Category']['id']);
                $this->loadModel('Items.FormFieldStep');
                $FormFieldSteps = $this->FormFieldStep->find('all', array(
                    'conditions' => array(
                        'FormFieldStep.category_id' => $category['Category']['id']
                    ) ,
                    'contain' => array(
                        'FormFieldGroup' => array(
                            'FormField' => array(
                                'conditions' => array(
                                    'FormField.is_active' => 1
                                ) ,
                                'order' => array(
                                    'FormField.order' => 'ASC'
                                )
                            ) ,
                            'order' => array(
                                'FormFieldGroup.order' => 'ASC'
                            )
                        )
                    ) ,
                    'order' => array(
                        'FormFieldStep.order' => 'ASC'
                    ) ,
                    'recursive' => 2
                ));
                $this->set('FormFieldSteps', $FormFieldSteps);
                $this->set('total_form_field_steps', count($FormFieldSteps));
                $this->set('categoryFormFields', $categoryFormFields);
                $this->set('category', $category);
                $this->set('model', 'Item');
                $this->loadModel('Country');
                $countries = $this->Country->find('list', array(
                    'fields' => array(
                        'Country.iso_alpha2',
                        'Country.name'
                    )
                ));
                $this->set(compact('countries'));
                if (empty($this->request->data['Form']['form_field_step'])) {
                    $this->request->data['Form']['form_field_step'] = 1;
                }
                if (!empty($this->request->data['Form']['next'])) {
                    $this->request->data['Form']['form_field_step'] = $this->request->data['Form']['form_field_step']+1;
                }
                // form field steps
                if (!empty($form_field_step)) {
                    $this->request->data['Form']['form_field_step'] = $form_field_step;
                    $this->request->data['Form']['step'] = 2;
                }
            }
        }
		
        $categories = $this->Item->Category->find('list', array(
            'conditions' => array(
                'Category.parent_id' => 0,
				'Category.is_active' => 1
            ) ,
			'order' => array(
				'Category.name' => 'ASC',
			) ,
            'recursive' => -1
        ));
        $users = $this->Item->User->find('list');
		$halls = array();
		if(isPluginEnabled('Seats')){
			App::import('Model', 'Seats.Hall');
			$this->Hall = new Hall();							
			$halls = $this->Hall->find('list', array(
				'conditions' => array(
					'Hall.user_id' => $this->Auth->user('id'),
					'Hall.is_active' => 1
				) ,
				'order' => array(
					'Hall.name' => 'ASC',
				) ,
				'recursive' => -1
			));
			$this->request->data['CustomPricePerNight']['halls'] = $halls;	
		}
        $this->set(compact('categories', 'users', 'sub_categories', 'category_types', 'halls'));
		
		if ($this->RequestHandler->prefers('json')) {
			$response = Cms::dispatchEvent('Controller.Item.Edit', $this, array(
				'message' => $message
			));
		}			
    }
    public function flashupload()
    {
        $this->Item->Attachment->Behaviors->attach('ImageUpload', Configure::read('item.file'));
        $this->XAjax->previewImage();
    }
    public function thumbnail()
    {
        $file_id = $this->request->params['pass'][1]; // show preview uploaded product image, session unique id
        $this->XAjax->thumbnail($file_id);
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
			if ($this->Item->delete($id)) {
				$this->set('iphone_response', array("message" => sprintf(__l('%s deleted') , Configure::read('item.alt_name_for_item_singular_caps')), "error" => 0));
				$this->Session->setFlash(sprintf(__l('%s deleted') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
				if (!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
						'action' => 'index'
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
    public function status_update($slug = null, $action = null)
    {
		$status = true;
        if (is_null($slug)) {
			if ($this->RequestHandler->prefers('json')) {
				$status = false;
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        $item = $this->Item->find('first', array(
                 'conditions' => array(
                        'Item.slug' => $slug
                  ) ,
                 'recursive' => -1,
        ));
        if(empty($item)){
            if ($this->RequestHandler->prefers('json')) {
				$status = false;
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		if($status){
            $active = 0;
            if($action == 'enable'){
                $active = 1;
            }
            $_item['Item']['id'] = $item['Item']['id'];
            $_item['Item']['is_active'] = $active;
			if ($this->Item->save($_item['Item'])) {
				$this->set('iphone_response', array("message" => sprintf(__l('Listing %s successfully') , $action), "error" => 0, "title" => $action));
				$this->Session->setFlash(__l('Status Updated Successfully') , 'default', null, 'success');
				if (!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
                                          'action' => 'index'
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
            Cms::dispatchEvent('Controller.Item.statusUpdate', $this, array());
		}
    }
    public function update_view_count()
    {
        if (!empty($_POST['ids'])) {
            $ids = explode(',', $_POST['ids']);
            $items = $this->Item->find('all', array(
                'conditions' => array(
                    'Item.id' => $ids
                ) ,
                'fields' => array(
                    'Item.id',
                    'Item.item_view_count'
                ) ,
                'recursive' => -1
            ));
            $json_arr = array();
            if (!empty($items)) {
                foreach($items as $item) {
					$item['Item']['item_view_count'] = !empty($item['Item']['item_view_count']) ? $item['Item']['item_view_count'] : 0;
                    $json_arr[$item['Item']['id']] = numbers_to_higher($item['Item']['item_view_count']);
                }
            }
            $this->view = 'Json';
            $this->set('json', $json_arr);
        }
    }
    function admin_manage_collections()
    {
        $this->pageTitle = __l('Manage Collections');
        if (isset($this->request->data)) {
            $items_data = $this->request->data;
            unset($items_data['Item']['r']);
            unset($items_data['Item']['more_action_id']);
            $item_list = array();
            foreach($items_data['Item'] as $key => $item) {
                if ($item['id'] == 1) {
                    $item_list[] = $key;
                }
            }
            $items = $this->Item->find('all', array(
                'conditions' => array(
                    'Item.id' => $item_list,
                ) ,
                'order' => array(
                    'Item.id' => 'desc'
                ) ,
                'recursive' => -1
            ));
            $this->set('items', $items);
            $this->set('item_list', implode(',', $item_list));
            if (isPluginEnabled('Collections')) {
                $collections = $this->Item->Collection->find('list', array(
                    'conditions' => array(
                        'Collection.is_active' => 1
                    ) ,
                    'recursive' => -1
                ));
                $this->set(compact('collections'));
            }
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index()
    {
        $conditions = array();
        $this->_redirectGET2Named(array(
            'q',
            'username',
        ));
        $this->pageTitle = Configure::read('item.alt_name_for_item_plural_caps');
        $this->set('active_items', $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_active = ' => 1,
                'Item.admin_suspend = ' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive_items', $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_active' => 0
            ) ,
            'recursive' => -1
        )));
        $this->set('suspended_items', $this->Item->find('count', array(
            'conditions' => array(
                'Item.admin_suspend = ' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_system_flagged = ' => 1,
                'Item.admin_suspend = ' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('user_flagged', $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_user_flagged = ' => 1,
                'Item.admin_suspend = ' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('featured', $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_featured = ' => 1
            ) ,
            'recursive' => -1
        )));
        $this->set('waiting_for_approval', $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_approved' => 0
            ) ,
            'recursive' => -1
        )));
        $this->set('total_items', $this->Item->find('count', array(
            'recursive' => -1
        )));
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'user-flag') {
            $conditions['Item.is_user_flagged'] = 1;
            $conditions['Item.admin_suspend'] = 0;
            $this->pageTitle.= ' - '. __l('User Flagged');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['Item.created'] = date('Y-m-d', strtotime('now'));
            $this->pageTitle.= __l(' - today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['Item.created >='] = date('Y-m-d', strtotime('now -7 days'));
            $this->pageTitle.= __l(' - in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['Item.created >='] = date('Y-m-d', strtotime('now -30 days'));
            $this->pageTitle.= __l(' - in this month');
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
            $conditions['AND']['OR'][]['Item.title LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $conditions['AND']['OR'][]['Item.description LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $conditions['AND']['OR'][]['User.username LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
            $this->request->data['Item']['q'] = $this->request->params['named']['q'];
        }
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['Item']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data['Item']['filter_id'])) {
            if ($this->request->data['Item']['filter_id'] == ConstMoreAction::Approved) {
                $conditions['Item.is_approved'] = 1;
                $this->pageTitle.= ' - '.__l('Approved');
            } else if ($this->request->data['Item']['filter_id'] == ConstMoreAction::Disapproved) {
                $conditions['Item.is_approved'] = 0;
                $this->pageTitle.= ' - '.__l('Waiting for Approval');
            } else if ($this->request->data['Item']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Item.is_active'] = 1;
                $conditions['Item.admin_suspend'] = 0;
                $this->pageTitle.= ' - '.__l('Enabled');
            } else if ($this->request->data['Item']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Item.is_active'] = 0;
                $this->pageTitle.= ' - '.__l('Disabled');
            } else if ($this->request->data['Item']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Item.admin_suspend'] = 1;
                $this->pageTitle.= ' - '.__l('Suspended');
            } else if ($this->request->data['Item']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Item.is_system_flagged'] = 1;
                $conditions['Item.admin_suspend'] = 0;
                $this->pageTitle.= ' - '.__l('Flagged');
            } else if ($this->request->data['Item']['filter_id'] == ConstMoreAction::UserFlagged) {
                $conditions['Item.item_flag_count !='] = 0;
                $conditions['Item.admin_suspend'] = 0;
                $this->pageTitle.= ' - '.__l('User Flagged');
            } else if ($this->request->data['Item']['filter_id'] == ConstMoreAction::Featured) {
                $conditions['Item.is_featured'] = 1;
                $this->pageTitle.= ' - '.__l('Featured');
            }
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'item_fee') {
            $conditions['Item.is_active'] = 1;
            $this->pageTitle.= ' - ' . Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('fee');
        }
        $this->Item->recursive = 1;
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'contain' => array(
                'User',
                'ItemFlag',
                'Attachment',
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
				'CustomPricePerNight' => array(
					'fields' => array(
						'CustomPricePerNight.is_seating_selection'
					)
				),
            ) ,
            'order' => array(
                'Item.id' => 'desc'
            )
        );
        $this->set('items', $this->paginate());
        $moreActions = $this->Item->moreActionitems;
        $users = $this->Item->User->find('list');
        $this->set(compact('moreActions', 'users'));
    }
    public function cluster_data()
    {
        $conditions = array();
        $conditions['Item.is_paid'] = 1;
        $conditions['Item.is_active'] = 1;
        $conditions['Item.is_approved'] = 1;
        $conditions['Item.admin_suspend'] = 0;
        $item_count = $this->Item->find('count', array(
            'conditions' => $conditions,
            'recursive' => -1,
        ));
        $results['Items'] = $this->Item->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'Item.id',
                'Item.latitude',
                'Item.longitude',
            ) ,
            'recursive' => -1,
        ));
        $results['Items']['Count'] = $item_count;
        $conditions1['Request.is_active'] = 1;
        $conditions1['Request.is_approved'] = 1;
        $conditions1['Request.admin_suspend'] = 0;
        $conditions1['Request.from >='] = date('Y-m-d');
        $request_count = "";
        if (isPluginEnabled('Requests')) {
            $request_count = $this->Item->ItemsRequest->Request->find('count', array(
                'conditions' => $conditions1,
                'recursive' => -1,
            ));
            $results['Requests'] = $this->Item->ItemsRequest->Request->find('all', array(
                'conditions' => $conditions1,
                'fields' => array(
                    'Request.id',
                    'Request.latitude',
                    'Request.longitude',
                ) ,
                'recursive' => -1,
            ));
        }
        $results['Requests']['Count'] = $request_count;
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.Item.ClusterData', $this, array(
				'data' => $results
			));
		}else{		
			$this->view = 'Json';
			$this->set('json', $results);
		}
    }
    public function map()
    {
        $this->pageTitle = __l('Map');
    }
    public function static_map($slug)
    {
        $this->pageTitle = __l('Map');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $contain = array(
            'User',
        );
        $item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.slug' => $slug
            ) ,
            'contain' => $contain,
            'recursive' => 2,
        ));
        $this->set(compact('item'));
    }
    public function admin_add()
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null)
    {
        $this->setAction('edit', $id);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$item_users = $this->Item->ItemUser->find('all', array(
            'conditions' => array(
                'ItemUser.item_id' => $id
            ) ,
            'recursive' => -1,
        ));
        if (!empty($item_users)) {
			$this->Session->setFlash(sprintf(__l('Some users have booked the this %s, so you cannot able to delete.') , Configure::read('item.alt_name_for_item_singular_small')) , 'default', null, 'error');
			$this->redirect(array(
				'action' => 'index'
			));
		} else {
			if ($this->Item->delete($id)) {
				$this->Session->setFlash(sprintf(__l('%s deleted') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
				$this->redirect(array(
					'action' => 'index'
				));
			} else {
				throw new NotFoundException(__l('Invalid request'));
			}
		}
    }
    public function item_calendar($year = null, $month = null, $item_id)
    {
        $data = array();
        if ($year == '' || $month == '') { // just use current yeear & month
            $year = date('Y');
            $monthInNumber = $month = date('m');
        }
        $flag = 0;
        $month_list = array(
            'january',
            'february',
            'march',
            'april',
            'may',
            'june',
            'july',
            'august',
            'september',
            'october',
            'november',
            'december'
        );
        for ($i = 0; $i < 12; $i++) {
            if (strtolower($month) == $month_list[$i]) {
                if (intval($year) != 0) {
                    $flag = 1;
                    $monthInNumber = $i+1;
                    break;
                }
            }
        }
        if ($flag == 0) {
            $year = date('Y');
            $month = date('F');
            $monthInNumber = date('m');
        }
        $this->set('year', $year);
        $this->set('month', $month);
        $conditions = array();
        $conditions['Item.id'] = $item_id;
        $data = $this->Item->getCalendarData($year, $monthInNumber, $item_id);
        $this->set('data', $data);
    }
    public function manage_item()
    {
        $r = $this->request->data['Item']['r'];		
        if (!empty($this->request->data['Item']['request_id']) && !empty($this->request->data['Item']['item'])) {
		$request_detail = $this->Item->ItemsRequest->Request->find('first', array(
			'conditions' => array(
				'Request.id' => $this->request->data['Item']['request_id']
			),
			'recursive' => -1
		));
		$item_details = $this->Item->find('first', array(
			'conditions' => array(
				'Item.id' => $this->request->data['Item']['item']
			),
			'contain' => array(
				'CustomPricePerNight' => array(
					'CustomPricePerType',
					'order' => array(
						'CustomPricePerNight.id' => 'ASC'
					),
				),
				
			),			
			'recursive' => 2
		));
		$customPricePerNight_data = array();
		$min_hours = 0;		
		foreach($item_details['CustomPricePerNight'] as $key => $item_detail){
			if(empty($customPricePerNight_data)){
				if($item_details['Item']['is_people_can_book_my_time']){
					if($item_detail['parent_id'] != 0){
						$customPricePerNight_data = $item_detail;
						break;
					}
				}
				if($item_detail['parent_id'] == 0){
					$min_hours = $item_detail['min_hours'];
					$customPricePerNight_id = $item_detail['id'];
				}
				if($item_details['Item']['is_sell_ticket']){
					foreach($item_detail['CustomPricePerType'] as $type_val){
						$customPricePerNight_data = $type_val;
						break;
					}
				}
			}
		}
		$request_data = array();		
		$request_data['ItemUser']['item_id'] = $this->request->data['Item']['item'];
		$request_data['ItemUser']['item_slug'] = $item_details['Item']['slug'];
		$request_data['ItemUser']['is_people_can_book_my_time'] = $item_details['Item']['is_people_can_book_my_time'];
		$request_data['ItemUser']['is_sell_ticket'] = $item_details['Item']['is_sell_ticket'];
		
		$split_start_date = explode('-', $request_detail['Request']['from']);		
		$request_data['ItemUser']['start_date']['year'] = $split_start_date[0];
		$request_data['ItemUser']['start_date']['month'] = $split_start_date[1];
		$request_data['ItemUser']['start_date']['day'] = $split_start_date[2];
		
		$split_end_date = explode('-', $request_detail['Request']['to']);
		$request_data['ItemUser']['end_date']['year'] = $split_end_date[0];
		$request_data['ItemUser']['end_date']['month'] = $split_end_date[1];
		$request_data['ItemUser']['end_date']['day'] = $split_end_date[2];
		$get_avaialbility = array();
		if(($item_details['Item']['is_people_can_book_my_time'] || $item_details['Item']['is_sell_ticket']) && $item_details['Item']['is_have_definite_time']){
			$split_start_time = explode(':', $customPricePerNight_data['start_time']);
			$request_data['ItemUser']['start_time']['hour'] = $split_start_time[0];
			$request_data['ItemUser']['start_time']['min'] = $split_start_time[1];
			$request_data['ItemUser']['start_time']['meridian'] = 'am';
			if($split_start_time[0] > 12){
				$request_data['ItemUser']['start_time']['hour'] = $split_start_time[0] - 12;
				$request_data['ItemUser']['start_time']['meridian'] = 'pm';
			}
			
			$split_end_time = explode(':', $customPricePerNight_data['end_time']);
			$request_data['ItemUser']['end_time']['hour'] = $split_end_time[0];
			$request_data['ItemUser']['end_time']['min'] = $split_end_time[1];
			$request_data['ItemUser']['end_time']['meridian'] = 'am';		
			if($split_end_time[0] > 12){
				$request_data['ItemUser']['end_time']['hour'] = $split_end_time[0] - 12;
				$request_data['ItemUser']['end_time']['meridian'] = 'pm';
			}			
			$get_avaialbility = $this->Item->check_availability($request_data);
		}
		if ($request_item_id = $this->Item->__updateItemRequest($this->request->data['Item']['request_id'], $this->request->data['Item']['item'])) {                
                $request = $this->Item->ItemsRequest->Request->find('first', array(
                    'conditions' => array(
                        'Request.id' => $this->request->data['Item']['request_id'],
                    ) ,
                    'recursive' => -1
                ));
                $item = $this->Item->find('first', array(
                    'conditions' => array(
                        'Item.id' => $this->request->data['Item']['item'],
                    ) ,
                    'recursive' => -1
                ));
				if(!empty($get_avaialbility)){
					$_data = array();
					$_data['ItemUser']['user_id'] = $request['Request']['user_id'];
					$_data['ItemUser']['from'] = $request['Request']['from'];
					$_data['ItemUser']['to'] = $request['Request']['to'];
					$_data['ItemUser']['item_id'] = $this->request->data['Item']['item'];
					$_data['ItemUser']['custom_price_per_night_id'] = $customPricePerNight_id;
					$_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::PaymentPending;
					$_data['ItemUser']['owner_user_id'] = $this->Auth->user('id');
					$days = getFromToDiff($request['Request']['from'], getToDate($request['Request']['to']));
					$_data['ItemUser']['top_code'] = $this->_uuid();
					$_data['ItemUser']['bottom_code'] = $this->_unum();
					$custom_night_id = '';
					$custom_type_id = '';
					if($item_details['Item']['is_people_can_book_my_time']){
						$price = $this->Item->ItemUser->getCustomPrice($request['Request']['from'], $get_avaialbility[0]['CustomPricePerNight']['start_time'], $request['Request']['to'], $get_avaialbility[0]['CustomPricePerNight']['end_time'], $item['Item']['id'], $get_avaialbility[0]['CustomPricePerNight']['id'], $min_hours);
						$_data['ItemUser']['price'] = $price;
						$custom_night_id = $get_avaialbility[0]['CustomPricePerNight']['id'];
					}
					if($item_details['Item']['is_sell_ticket']){
						$price = $get_avaialbility[0]['price'];
						$_data['ItemUser']['price'] = $price;
						$custom_type_id = $get_avaialbility[0]['id'];
					}
					$_data['ItemUser']['original_price'] = $_data['ItemUser']['price'];
					$_data['ItemUser']['booker_service_amount'] = ($_data['ItemUser']['price']) *(Configure::read('item.booking_service_fee') /100);
					$hosting_fee = ($_data['ItemUser']['price']) *(Configure::read('item.host_commission_amount') /100);
					if (!empty($item['Item']['is_buyer_as_fee_payer'])) {
						$_data['ItemUser']['booker_service_amount'] += $hosting_fee;
						$_data['ItemUser']['host_service_amount'] = 0;
					} else {
						$_data['ItemUser']['host_service_amount'] = $hosting_fee;
					}
					$_data['ItemUser']['additional_fee_amount'] = 0;
					$_data['ItemUser']['quantity'] = 1;
					if (!empty($item['Item']['is_additional_fee_to_buyer'])) {
						$this->request->data['ItemUser']['additional_fee_amount'] = $_data['ItemUser']['price'] * ($item['Item']['additional_fee_percentage'] /100);
					}
					$this->Item->ItemUser->save($_data, false);
					$order_id = $this->Item->ItemUser->getLastInsertId();
					
					// insert to custom_price_per_type_item_users				
					$_data['CustomPricePerTypeItemUser']['item_user_id'] = $order_id;
					$_data['CustomPricePerTypeItemUser']['custom_price_per_night_id'] = $custom_night_id;
					$_data['CustomPricePerTypeItemUser']['custom_price_per_type_id'] = $custom_type_id;
					$_data['CustomPricePerTypeItemUser']['number_of_quantity'] = 1;
					$_data['CustomPricePerTypeItemUser']['price'] = $price;
					$_data['CustomPricePerTypeItemUser']['total_price'] = $price * 1;  // price * quantity 
					$this->Item->ItemUser->CustomPricePerTypeItemUser->create();
					$this->Item->ItemUser->CustomPricePerTypeItemUser->save($_data);
					
					$this->Session->setFlash(sprintf(__l('%s mapped with request.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
					$this->Item->ItemsRequest->updateAll(array(
						'ItemsRequest.order_id' => $order_id
					) , array(
						'ItemsRequest.id' => $request_item_id
					));
					$subject = sprintf(__l('%s mapped for your request - %s'), Configure::read('item.alt_name_for_item_singular_caps'), $request['Request']['title']);
					$message = sprintf(__l('%s mapped %s for your request -  %s'), $this->Auth->user('username'), Configure::read('item.alt_name_for_item_singular_caps'), $request['Request']['title']);
					$message_id = $this->Item->ItemUser->Message->sendNotifications($this->Auth->user('id') , $subject, $message, $order_id, $is_review = 0, $this->request->data['Item']['item'], ConstItemUserStatus::BookingRequest);
					$this->redirect(array(
						'controller' => 'messages',
						'action' => 'activities',
						'order_id' => $order_id,
					));
				} else {
					$this->Session->setFlash(sprintf(__l('%s mapped with request, but there is no availability on the requested date.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
					$this->redirect(array(
						'controller' => 'requests',
						'action' => 'index'
					));
				}
                
            }
            $this->Session->setFlash(sprintf(__l('Selected %s already mapped with this request.') , Configure::read('item.alt_name_for_item_singular_small')) , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'requests',
                'action' => 'index'
            ));
        } else {
            $this->Session->setFlash(__l('Couldn\'t map to request') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'requests',
                'action' => 'index'
            ));
        }
    }
	public function update_redirect()
    {
		$this->autoRender = false;
		$item_id = $this->Session->read('last_insert_item_id');
		if(!empty($item_id)) {
			$this->Session->delete('last_insert_item_id');
			$item = $this->Item->find('first', array(
				'conditions' => array(
					'Item.id = ' => $item_id,
				) ,
				'recursive' => -1,
			));
			if (Configure::read('item.item_fee') && $this->Auth->user('role_id') != ConstUserTypes::Admin) {
				$this->Session->setFlash(sprintf(__l('%s has been added successfully and it will be list out after paying the listing fee') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
				$this->redirect(array(
					'controller' => 'items',
					'action' => 'item_pay_now',
					$item['Item']['id']
				));
			} else {
				$mail_template = 'New Item Activated';
				if (!empty($mail_template)) {
					App::import('Model', 'EmailTemplate');
					$this->EmailTemplate = new EmailTemplate();
					$template = $this->EmailTemplate->selectTemplate($mail_template);
					$emailFindReplace = array(
						'##USERNAME##' => $this->Auth->user('username') ,
						'##ITEM_NAME##' => $item['Item']['title'],
						'##ITEM_URL##' => Router::url(array(
							'controller' => 'items',
							'action' => 'view',
							$item['Item']['slug'],
							'admin' => false,
						) , true) ,
						'##SITE_NAME##' => Configure::read('site.name') ,
						'##SITE_URL##' => Router::url('/', true) ,
						'##FROM_EMAIL##' => ($template['from'] == '##FROM_EMAIL##') ? Configure::read('site.from_email') : $template['from'],
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
					);
					$email_message = sprintf(__l('Your %s has been activated') , Configure::read('item.alt_name_for_item_singular_small'));
					$message = strtr($template['email_text_content'], $emailFindReplace);
					$subject = strtr($template['subject'], $emailFindReplace);
					if (Configure::read('messages.is_send_internal_message')) {
						$message_id = $this->Item->Message->sendNotifications($this->Auth->user('id') , $subject, $message, 0, $is_review = 0, $item['Item']['id'], 0);
						if (Configure::read('messages.is_send_email_on_new_message')) {
							$content['subject'] = $email_message;
							$content['message'] = $email_message;
							if (!empty($host_email)) {
								$this->_sendAlertOnNewMessage($host_email, $content, $message_id, 'New Item Activated');
							}
						}
					}
				}
				if ($item['Item']['admin_suspend']) {
					$this->Session->setFlash(sprintf(__l('%s has been suspended, due to some bad words. Admin will unsuspend your %s') , Configure::read('item.alt_name_for_item_singular_caps') , Configure::read('item.alt_name_for_item_singular_small')) , 'default', null, 'error');
					$this->redirect(array(
						'controller' => 'users',
						'action' => 'dashboard',
						'admin' => false
					));
				} else {
					if (Configure::read('item.is_auto_approve')) {
						$this->Item->autofacebookpost($item['Item']['id']);
						$this->Session->setFlash(sprintf(__l('%s has been listed.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
						if (isPluginEnabled('SocialMarketing') && !empty($item['Item']['is_active'])) {
							Cms::dispatchEvent('Controller.SocialMarketing.redirectToShareUrl', $this, array(
								'data' => $item['Item']['id'],
								'publish_action' => 'add',
								'request' => false
							));
						} else {
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'view',
								$item['Item']['slug'],
								'admin' => false
							));
						}
					} else {
						if ($this->Auth->user('role_id') == ConstUserTypes::Admin) {
							$this->Session->setFlash(sprintf(__l('%s has been added.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'view',
								$item['Item']['slug'],
								'admin' => false
							));
						} else {
							$this->Session->setFlash(sprintf(__l('%s has been added but after admin approval it will list out in site') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
						}
						$this->redirect(array(
							'action' => 'index',
							'admin' => false
						));
					}
				}
			}
		}
	}
    public function update()
    {
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
                if ($actionid == ConstMoreAction::Inactive) {
                    $this->{$this->modelClass}->updateAll(array(
                        $this->modelClass . '.is_active' => 0
                    ) , array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked records has been disabled') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->{$this->modelClass}->updateAll(array(
                        $this->modelClass . '.is_active' => 1
                    ) , array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked records has been enabled') , 'default', null, 'success');
                } elseif ($actionid == ConstMoreAction::Delete) {
                    $this->{$this->modelClass}->deleteAll(array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked records has been deleted') , 'default', null, 'success');
                }
            }
        }
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(Router::url('/', true) . $r);
        } else {
            $this->redirect($r);
        }
    }
    public function my_items()
    {
        $this->pageTitle = Configure::read('item.alt_name_for_item_plural_caps');
        $conditions = array();
        if (!empty($this->_prefixId) && $is_city) {
            if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'user') {
                $conditions['Item.city_id'] = $this->_prefixId;
            }
        }
        $order = array(
            'Item.id' => 'desc'
        );
        // its called from item_users index
        $conditions['Item.user_id'] = $this->Auth->user('id');
		$conditions['Item.is_completed'] = true;
        $items = $this->Item->find('all', array(
            'conditions' => array(
                $conditions
            ) ,
            'contain' => array(
                'Attachment',
				'CustomPricePerNight' => array(					
					'order' => array(
						'CustomPricePerNight.id ASC'
					),
					'CustomPricePerType'
				)
            ) ,
            'fields' => array(
                'Item.id',
                'Item.created',
                'Item.modified',
                'Item.user_id',
                'Item.title',
                'Item.slug',
                'Item.description',
                'Item.unit',
                'Item.price_per_day',
                'Item.price_per_week',
                'Item.price_per_month',
                'Item.is_active',
                'Item.is_paid'
            ) ,
            'order' => $order,
            'recursive' => 2
        ));
        $this->set('items', $items);
        $this->render('lst_my_items');
    }
    public function update_price()
    {
        $price = 0;
        switch ($_POST['type']) {
            case 'night':
                $price = $this->Item->checkCustomPrice($_POST['from'], $_POST['to'], $_POST['item_id'], $_POST['guest'], true);
                break;

            case 'week':
                $price = $this->Item->checkCustomWeekPrice($_POST['from'], $_POST['to'], $_POST['item_id'], $_POST['guest'], true);
                break;

            case 'month':
                $price = $this->Item->checkCustomMonthPrice($_POST['from'], $_POST['to'], $_POST['item_id'], $_POST['guest'], true);
                break;
        }
        echo $price;
        exit;
    }
    public function sample_data()
    {
        set_time_limit(0);
        Configure::write('debug', 1);
        $dummyData = $this->Item->query('SELECT * FROM tmp_dummy_data');
        $title = array(
            'Beach house in ',
            'Rooms for rent in ',
            'Luxury beach house in ',
            'House near to beach in ',
            'Guest house in ',
            'Luxurious place to live in ',
            'Fully furnished house in ',
            'Luxurious apartment in ',
        );
        $video_url = array(
            'http://www.youtube.com/watch?v=EByBssKshGo',
            'http://www.youtube.com/watch?v=U00bgAkmwYM',
            'http://www.youtube.com/watch?v=90A67SIcNVw',
            'http://www.youtube.com/watch?v=xHFVCjK6aIw',
            'http://www.youtube.com/watch?v=EPuqIKYy7DI',
        );
        $img_dir = APP . 'media' . DS . 'images';
        $handle = opendir($img_dir);
        while (false !== ($readdir = readdir($handle))) {
            if ($readdir != '.' && $readdir != '..' && $readdir != 'Thumbs.db') {
                $image_path_arr[] = $readdir;
            }
        }
        $country_arr = array(
            43,
            254,
            253,
            113,
            14
        );
        $image = $country = 0;
        $escape_city = array();
        foreach($dummyData as $dummy) {
            if ($dummy['tmp_dummy_data']['id'] == 251 || $dummy['tmp_dummy_data']['id'] == 501 || $dummy['tmp_dummy_data']['id'] == 751 || $dummy['tmp_dummy_data']['id'] == 851) {
                $country++;
                $escape_city = array();
            }
            $tmp_country = $this->Item->query('SELECT * FROM tmp_countries WHERE id = ' . $country_arr[$country]);
            $escape_not_in_city = '';
            if (!empty($escape_city)) {
                $escape_not_in_city = ' AND id NOT IN (' . implode(',', $escape_city) . ')';
            }
            $tmp_city = $this->Item->query('SELECT * FROM tmp_cities WHERE country_id = ' . $country_arr[$country] . $escape_not_in_city . ' LIMIT 0, 1');
            $escape_city[] = $tmp_city[0]['tmp_cities']['id'];
            $tmp_state = $this->Item->query('SELECT * FROM tmp_states WHERE id = ' . $tmp_city[0]['tmp_cities']['state_id']);
            $_data['Item']['user_id'] = mt_rand(2, 10);
            $_data['Item']['city_id'] = $tmp_city[0]['tmp_cities']['id'];
            $_data['Item']['state_id'] = $tmp_city[0]['tmp_cities']['state_id'];
            $_data['Item']['country_id'] = $country_arr[$country];
            $_data['Item']['title'] = $title[mt_rand(0, 7) ] . $tmp_city[0]['tmp_cities']['name'];
            $_data['Item']['description'] = str_replace('.', '', $dummy['tmp_dummy_data']['description']);
            $_data['Item']['address'] = $dummy['tmp_dummy_data']['address'] . ', ' . $tmp_city[0]['tmp_cities']['name'] . ', ' . $tmp_state[0]['tmp_states']['name'] . ', ' . $tmp_country[0]['tmp_countries']['title'];
            $_data['Item']['phone'] = $dummy['tmp_dummy_data']['phone'];
            $_data['Item']['ip_id'] = 2;
            $price_arr = array(
                10,
                20,
                30,
                40,
                50,
                60,
                70,
                80,
                90,
                100,
                200,
                300,
                400,
                500,
                150,
                250,
                350,
                450
            );
            $_data['Item']['price_per_day'] = $price_arr[mt_rand(0, 17) ];
            $_data['Item']['price_per_week'] = ($_data['Item']['price_per_day']*7) -10;
            $_data['Item']['price_per_month'] = ($_data['Item']['price_per_day']*30) -10;
            if ($_data['Item']['accommodates'] > 3) {
                $_data['Item']['additional_guest'] = $_data['Item']['accommodates']-1;
                $guest_price_arr = array(
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10
                );
                $_data['Item']['additional_guest_price'] = $guest_price_arr[mt_rand(0, 9) ];
            }
            $_data['Item']['backup_phone'] = $dummy['tmp_dummy_data']['backup_phone'];
            $_data['Item']['house_rules'] = str_replace('.', '', $dummy['tmp_dummy_data']['house_rules']);
            $_data['Item']['house_manual'] = str_replace('.', '', $dummy['tmp_dummy_data']['house_manual']);
            $size_arr = array(
                10,
                20,
                30,
                40,
                50,
                60,
                70,
                80,
                90,
                100,
                200,
                300,
                400,
                500,
                150,
                250,
                350,
                450
            );
            $_data['Item']['item_size'] = $size_arr[mt_rand(0, 17) ];
            $_data['Item']['measurement'] = mt_rand(1, 2);
            $_data['Item']['minimum_nights'] = 1;
            $from_arr = array(
                '06:00:00',
                '06:15:00',
                '06:30:00',
                '06:45:00',
                '07:00:00',
                '07:15:00',
                '07:30:00',
                '07:45:00',
                '08:00:00',
                '08:15:00',
                '08:30:00',
                '08:45:00',
                '09:00:00'
            );
            $_data['Item']['from'] = $from_arr[mt_rand(0, 12) ];
            $from_arr = array(
                '18:00:00',
                '18:15:00',
                '18:30:00',
                '18:45:00',
                '19:00:00',
                '19:15:00',
                '19:30:00',
                '19:45:00',
                '20:00:00',
                '20:15:00',
                '20:30:00',
                '20:45:00',
                '21:00:00'
            );
            $_data['Item']['to'] = $to_arr[mt_rand(0, 12) ];
            $_data['Item']['latitude'] = $tmp_city[0]['tmp_cities']['latitude'];
            $_data['Item']['longitude'] = $tmp_city[0]['tmp_cities']['longitude'];
            $_data['Item']['zoom_level'] = 10;
            $_data['Item']['is_active'] = ($dummy['tmp_dummy_data']['id']%50 == 0) ? 0 : 1;
            $_data['Item']['is_approved'] = ($dummy['tmp_dummy_data']['id']%50 == 0) ? 0 : 1;
            $_data['Item']['is_featured'] = ($dummy['tmp_dummy_data']['id']%30 == 0) ? 1 : 0;
            $_data['Item']['is_show_in_homepage'] = ($dummy['tmp_dummy_data']['id']%220 == 0) ? 1 : 0;
            $_data['Item']['is_paid'] = ($dummy['tmp_dummy_data']['id']%30 == 0) ? 0 : 1;
            $_data['Item']['video_url'] = $video_url[mt_rand(0, 4) ];
            $_data['Item']['location_manual'] = str_replace('.', '', $dummy['tmp_dummy_data']['location_manual']);
            $_data['Item']['id'] = '';
            $this->Item->create();
            if ($this->Item->save($_data)) {
                $item_id = $this->Item->getLastInsertId();
                for ($i = 0; $i < 3; $i++) {
                    $img_url = $img_dir . DS . $image_path_arr[$image];
                    $image_size = getimagesize($img_url);
                    $filename = basename($image_path_arr[$image]);
                    $_attachment_data['Attachment']['filename']['type'] = $image_size['mime'];
                    $_attachment_data['Attachment']['filename']['name'] = $filename;
                    $_attachment_data['Attachment']['filename']['tmp_name'] = $img_url;
                    $_attachment_data['Attachment']['filename']['size'] = filesize($img_url);
                    $_attachment_data['Attachment']['filename']['error'] = 0;
                    $this->Item->Attachment->Behaviors->attach('ImageUpload', Configure::read('item.file'));
                    $this->Item->Attachment->isCopyUpload(true);
                    $this->Item->Attachment->set($_attachment_data);
                    $this->Item->Attachment->create();
                    $_attachment_data['Attachment']['filename'] = $_attachment_data['Attachment']['filename'];
                    $_attachment_data['Attachment']['class'] = 'Item';
                    $_attachment_data['Attachment']['description'] = str_replace('.jpeg', '', str_replace('.jpg', '', $filename));
                    $_attachment_data['Attachment']['width'] = $image_size[0];
                    $_attachment_data['Attachment']['height'] = $image_size[1];
                    $_attachment_data['Attachment']['foreign_id'] = $item_id;
                    $this->Item->Attachment->data = $_attachment_data['Attachment'];
                    $this->Item->Attachment->save($_attachment_data);
                    $this->Item->Attachment->Behaviors->detach('ImageUpload');
                    if ($image == 114) {
                        $image = 0;
                    } else {
                        $image++;
                    }
                    $_attachment_data = array();
                }
            }
            $_data = array();
        }
        exit;
    }
    public function initChart()
    {
        //# last days date settings
        $days = 3;
        $this->lastDaysStartDate = date('Y-m-d', strtotime("-$days days"));
        for ($i = $days; $i > 0; $i--) {
            $this->lastDays[] = array(
                'display' => date('D, M d', strtotime("-$i days")) ,
                'conditions' => array(
                    '#MODEL#.created >=' => date('Y-m-d 00:00:00', strtotime("-$i days")) ,
                    '#MODEL#.created <=' => date('Y-m-d 23:59:59', strtotime("-$i days"))
                )
            );
        }
        $this->lastDays[] = array(
            'display' => date('D, M d') ,
            'conditions' => array(
                '#MODEL#.created ' => date('Y-m-d', strtotime('now')) ,
            )
        );
    }
    public function admin_action_taken()
    {
        $pending_withdraw_count = "";
        if (isPluginEnabled('Withdrawals')) {
            App::import('Model', 'Withdrawals.UserCashWithdrawal');
            $this->UserCashWithdrawal = new UserCashWithdrawal();
            $pending_withdraw_count = $this->UserCashWithdrawal->find('count', array(
                'conditions' => array(
                    'UserCashWithdrawal.withdrawal_status_id' => ConstWithdrawalStatus::Pending
                ) ,
                'recursive' => -1
            ));
        }
        $this->set('pending_withdraw_count', $pending_withdraw_count);
        if (isPluginEnabled('Affiliates')) {
            App::import('Model', 'Affiliates.AffiliateCashWithdrawal');
            $this->AffiliateCashWithdrawal = new AffiliateCashWithdrawal();
            $afffiliate_pending_withdraw_count = $this->AffiliateCashWithdrawal->find('count', array(
                'conditions' => array(
                    'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Pending
                ) ,
                'recursive' => -1
            ));
            $this->set('afffiliate_pending_withdraw_count', $afffiliate_pending_withdraw_count);
        }
        $item_system_flagged_count = $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_system_flagged' => 1,
                'Item.admin_suspend' => 0
            ) ,
            'recursive' => -1
        ));
        $item_user_flagged_count = $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_user_flagged' => 1,
                'Item.admin_suspend' => 0,
            ) ,
            'recursive' => -1
        ));
        $this->set('item_system_flagged_count', $item_system_flagged_count);
        $this->set('item_user_flagged_count', $item_user_flagged_count);
        if (isPluginEnabled('Requests')) {
            App::import('Model', 'Requests.Request');
            $this->Request = new Request();
            $request_system_flagged_count = $this->Request->find('count', array(
                'conditions' => array(
                    'Request.is_system_flagged' => 1,
                    'Request.admin_suspend' => 0
                ) ,
                'recursive' => -1
            ));
            $request_user_flagged_count = $this->Request->find('count', array(
                'conditions' => array(
                    'Request.is_user_flagged' => 1,
                    'Request.admin_suspend' => 0
                ) ,
                'recursive' => -1
            ));
            $this->set('request_system_flagged_count', $request_system_flagged_count);
            $this->set('request_user_flagged_count', $request_user_flagged_count);
            $request_pending_for_approval_count = $this->Request->find('count', array(
                'conditions' => array(
                    'Request.is_active' => 0
                ) ,
                'recursive' => -1
            ));
            $this->set('request_pending_for_approval_count', $request_pending_for_approval_count);
        }
        $pending_for_approval_count = $this->Item->find('count', array(
            'conditions' => array(
                'Item.is_approved' => 0
            ) ,
            'recursive' => -1
        ));
        $this->set('pending_for_approval_count', $pending_for_approval_count);
    }
    public function item_pay_now($item_id = null)
    {
        $this->pageTitle = __l('Pay Now');
        App::import('Model', 'User');
        $this->User = new User();
        $gateway_options = array();
        if (!empty($this->request->data['Item']['id'])) {
            $item_id = $this->request->data['Item']['id'];
        }
        if ($this->RequestHandler->prefers('json') && ($this->request->is('post'))){
			//todo: swagger api call need to fix
            $this->request->data['Sudopay'] = $this->request->data;
            $this->request->data['Item']['payment_gateway_id'] = $this->request->data['payment_gateway_id'];
            $this->request->data['Item']['wallet'] = 'Pay Now';
            $this->request->data['Item']['id'] = $item_id;
		}
        if (!empty($this->request->data)) {
            $this->request->data['Item']['sudopay_gateway_id'] = 0;
            if ($this->request->data['Item']['payment_gateway_id'] != ConstPaymentGateways::Wallet && strpos($this->request->data['Item']['payment_gateway_id'], 'sp_') >= 0) {
                $this->request->data['Item']['sudopay_gateway_id'] = str_replace('sp_', '', $this->request->data['Item']['payment_gateway_id']);
                $this->request->data['Item']['payment_gateway_id'] = ConstPaymentGateways::SudoPay;
            }
        }
        if (is_null($item_id)) {
            if ($this->RequestHandler->prefers('json')){
                $this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
            }else{
            throw new NotFoundException(__l('Invalid request'));
            }
        }
        $total_amount = Configure::read('item.item_fee');
        $total_amount = round($total_amount, 2);
        $Item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.id = ' => $item_id,
            ) ,
            'contain' => array(
                'Attachment',
                'User',
                'Country' => array(
                    'fields' => array(
                        'Country.name',
                        'Country.iso_alpha2'
                    )
                ) ,
            ) ,
            'recursive' => 2,
        ));
        if (empty($Item) || (!empty($Item) && $Item['Item']['user_id'] != $this->Auth->user('id'))) {
                if ($this->RequestHandler->prefers('json')){
                    $this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
                    }else{
                    throw new NotFoundException(__l('Invalid request'));
                }
        }
        $this->pageTitle = sprintf(__l('Pay %s Fee') , Configure::read('item.alt_name_for_item_singular_caps')) . ' - ' . $Item['Item']['title'];
        if (!empty($this->request->data)) {
            $data['Item']['id'] = $Item['Item']['id'];
            $data['Item']['item_payment_gateway_id'] = $this->request->data['Item']['payment_gateway_id'];
            $data['Item']['item_sudopay_gateway_id'] = $this->request->data['Item']['sudopay_gateway_id'];
            $data['Item']['item_fee'] = $total_amount;
            $this->Item->save($data, false);
            if ($this->request->data['Item']['payment_gateway_id'] == ConstPaymentGateways::Wallet and isPluginEnabled('Wallet')) {
                $this->loadModel('Wallet.Wallet');
                $return = $this->Wallet->processPayToItem($this->Auth->user('id') , $total_amount, $item_id, ConstPaymentType::ItemListingFee);
                if (!$return) {
                    $this->Session->setFlash(__l('Your wallet has insufficient money') , 'default', null, 'error');
                    if ($this->RequestHandler->prefers('json')){
                        $this->set('iphone_response', array("message" => __l('Your wallet has insufficient money'), "error" => 1));
                        }else{
                    $this->redirect(array(
                        'controller' => 'items',
                        'action' => 'item_pay_now',
                        $this->request->data['Item']['id'],
                        'payment_gateway_id' => $this->request->data['Payment']['payment_gateway_id']
                    ));
                        }
                } else {
                    if (Configure::read('item.is_auto_approve')) {
                        $this->Session->setFlash(sprintf(__l('%s listing fee payment has done and %s has been listed successfully.') , Configure::read('item.alt_name_for_item_plural_caps') , Configure::read('item.alt_name_for_item_singular_small')) , 'default', null, 'success');
                        if ($this->RequestHandler->prefers('json')){
                            $this->set('iphone_response', array("message" => sprintf(__l('%s listing fee payment has done and %s has been listed successfully.') , Configure::read('item.alt_name_for_item_plural_caps') , Configure::read('item.alt_name_for_item_singular_small')), "error" => 0));
                            }else{
                        if (isPluginEnabled('SocialMarketing') && !empty($Item['Item']['is_active'])) {
                            Cms::dispatchEvent('Controller.SocialMarketing.redirectToShareUrl', $this, array(
                                'data' => $Item['Item']['id'],
                                'publish_action' => 'add',
                                'request' => false
                            ));
                        } else {
                            $this->redirect(array(
                                'controller' => 'items',
                                'action' => 'view',
                                $Item['Item']['slug'],
                                'admin' => false
                            ));
                        }
                            }
                    } else {
                        if ($this->Auth->user('role_id') == ConstUserTypes::Admin) {
                            $this->Session->setFlash(sprintf(__l('%s has been Listed.') , Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
                            if ($this->RequestHandler->prefers('json')){
                                $this->set('iphone_response', array("message" => sprintf(__l('%s listing fee payment has done and %s has been listed successfully.') , Configure::read('item.alt_name_for_item_plural_caps') , Configure::read('item.alt_name_for_item_singular_small')), "error" => 0));
                                }else{
                            $this->redirect(array(
                                'controller' => 'items',
                                'action' => 'view',
                                $Item['Item']['slug'],
                                'admin' => false
                            ));
                                }
                        } else {
                            $this->Session->setFlash(sprintf(__l('%s listing fee payment has done and %s will be listed after admin approve') , Configure::read('item.alt_name_for_item_plural_caps') , Configure::read('item.alt_name_for_item_singular_small')) , 'default', null, 'success');
                                $this->set('iphone_response', array("message" => sprintf(__l('%s listing fee payment has done and %s will be listed after admin approve') , Configure::read('item.alt_name_for_item_plural_caps') , Configure::read('item.alt_name_for_item_singular_small')), "error" => 0));
                        }
                        if (!$this->RequestHandler->prefers('json')){
                            $this->redirect(array(
                                'action' => 'index',
                                'admin' => false
                            ));
                        }
                    }
                }
            } elseif ($this->request->data['Item']['payment_gateway_id'] == ConstPaymentGateways::SudoPay) {
                $this->loadModel('Sudopay.Sudopay');
                $sudopay_gateway_settings = $this->Sudopay->GetSudoPayGatewaySettings();
                $this->set('sudopay_gateway_settings', $sudopay_gateway_settings);
                if ($sudopay_gateway_settings['is_payment_via_api'] == ConstBrandType::VisibleBranding) {
                    $sudopay_data = $this->Sudopay->getSudoPayPostData($Item['Item']['id'], ConstPaymentType::ItemListingFee);
                    $sudopay_data['merchant_id'] = $sudopay_gateway_settings['sudopay_merchant_id'];
                    $sudopay_data['website_id'] = $sudopay_gateway_settings['sudopay_website_id'];
                    $sudopay_data['secret_string'] = $sudopay_gateway_settings['sudopay_secret_string'];
                    $sudopay_data['action'] = 'capture';
					$sudopay_data['button_url'] = '\'' . '//d1fhd8b1ym2gwa.cloudfront.net/btn/sudopay_btn.js'. '\'';
					if(!empty($sudopay_gateway_settings['is_test_mode'])){
						$sudopay_data['button_url'] = '\'' . '//d1fhd8b1ym2gwa.cloudfront.net/btn/sandbox/sudopay_btn.js'. '\'';
					}
                    $this->set('sudopay_data', $sudopay_data);
                } else {
                    $this->request->data['Sudopay'] = !empty($this->request->data['Sudopay']) ? $this->request->data['Sudopay'] : '';
                    if ($this->RequestHandler->prefers('json')){
                        $call_back_url = $this->Sudopay->processPayment($Item['Item']['id'], ConstPaymentType::ItemListingFee, $this->request->data['Sudopay'], 'json');
                        if(empty($call_back_url['error'])){
                            if(!empty($call_back_url['success'])){
                                $return = $call_back_url;
                            }else{
                                $this->set('iphone_response', array("message" => $call_back_url, "error" => 0));
                            }
                        }else{
                            $this->set('iphone_response', array("message" => $return['error_message'] . ' ' . __l('Payment could not be completed.'), "error" => 1));
                            $return = $return;
                        }
                    }else{
                        $return = $this->Sudopay->processPayment($Item['Item']['id'], ConstPaymentType::ItemListingFee, $this->request->data['Sudopay']);
                    }
                    if (!empty($return['pending'])) {
                        $this->Session->setFlash($return['error_message'] . __l(' Once payment is received, it will be processed.') , 'default', null, 'success');
                        $this->set('iphone_response', array("message" => $return['error_message'] . __l(' Once payment is received, it will be processed.'), "error" => 0));
                    } elseif (!empty($return['success'])) {
                        $this->Item->processPayment($Item['Item']['id'], $this->request->data['Item']['amount'], ConstPaymentGateways::SudoPay, ConstPaymentType::ItemListingFee);
                        $this->Session->setFlash(sprintf(__l('You have paid %s fee successfully.') , Configure::read('item.alt_name_for_item_singular_small')) , 'default', null, 'success');
                        $this->set('iphone_response', array("message" => sprintf(__l('You have paid %s fee successfully.') , Configure::read('item.alt_name_for_item_singular_small')), "error" => 0));
                        if (!$this->RequestHandler->prefers('json')){
                        $this->redirect(array(
                            'controller' => 'items',
                            'action' => 'view',
                            $Item['Item']['slug']
                        ));
                        }
                    } elseif (!empty($return['error'])) {
                        $this->Session->setFlash($return['error_message'] . __l('Payment could not be completed.') , 'default', null, 'error');
                        $this->set('iphone_response', array("message" => $return['error_message'] . __l('Payment could not be completed.'), "error" => 1));
                        if (!$this->RequestHandler->prefers('json')){
                        $this->redirect(array(
                            'controller' => 'items',
                            'action' => 'item_pay_now',
                            $this->request->data['Item']['id'],
                            'payment_gateway_id' => $this->request->data['Payment']['payment_gateway_id']
                        ));
                       }
                    }
                }
            }
        } else {
            $this->request->data = $Item;
        }
        $this->set('Item', $Item);
        $this->set('total_amount', $total_amount);
        // <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
        Cms::dispatchEvent('Controller.Item.ItemPayNow', $this, array());
        }		
    }
    public function order($id = null, $type = 'item', $gateway = null)
    {
        App::import('Model', 'Items.Item');
        $this->Item = new Item();
        App::import('Model', 'User');
        $this->User = new User();
		$this->loadModel('Items.BuyerSubmission');
		$this->loadModel('Items.BuyerFormField');
        $gateway_options = array();
        //checking item booked on specic date
        if (!empty($this->request->params['named']['order_id']) && empty($this->request->params['named']['type'])) {
            $_SESSION['order_id'] = $this->request->params['named']['order_id'];
        }
        if (!empty($id) && !empty($type)) {
            Cms::dispatchEvent('Model.IntegratedGoogleAnalytics.trackEvent', $this, array(
                '_trackEvent' => array(
                    'category' => 'ItemUser',
                    'action' => 'Bookit',
                    'label' => 'Step 2',
                    'value' => '',
                ) ,
                '_setCustomVar' => array(
                    'pd' => $id,
                    'ud' => $this->Auth->user('id') ,
                    'rud' => $this->Auth->user('referred_by_user_id') ,
                )
            ));
        }
		if ($this->RequestHandler->prefers('json') && ($this->request->is('post'))){
			//todo: swagger api call need to fix
			$this->request->data['Sudopay'] = $this->request->data;
			$this->request->data['Item']['item_id'] = $this->request->data['item_id'];
			$this->request->data['Item']['order_id'] = $this->request->data['order_id'];
            //item user
            $this->request->data['ItemUser']['coupon_code'] = $this->request->data['coupon_code'];
            $this->request->data['ItemUser']['message'] = $this->request->data['comment'];
            $this->request->data['ItemUser']['payment_gateway_id'] = $this->request->data['payment_gateway_id'];
			unset($this->request->data['ItemUser']['item_id']);
			unset($this->request->data['ItemUser']['order_id']);
		}
		if(!empty($this->request->data)) {
			$itemUser = $this->Item->ItemUser->find('first', array(
                'conditions' => array(
                    'ItemUser.id' => $this->request->data['Item']['order_id']
                ) ,
                'contain' => array(
                    'User',
					'CustomPricePerTypeItemUser',
                    'CustomPricePerNight' => array(
						'CustomPricePerType'						
					),
					
                ) ,
                'recursive' => 2
            ));
			foreach($itemUser['CustomPricePerNight']['CustomPricePerType'] as $key=>$value) {
				if(!empty($itemUser['CustomPricePerTypeItemUser'][$key]['number_of_quantity']) && $value['max_number_of_quantity'] > 0) {
					$booked_quantity = $value['booked_quantity'] + $itemUser['CustomPricePerTypeItemUser'][$key]['number_of_quantity'];
					if($booked_quantity > $value['max_number_of_quantity']) {
						$this->Session->setFlash(__l('No availability') , 'default', null, 'error');
						$this->redirect(array(
							'controller' => 'item_users',
							'action' => 'index',
							'type' => 'mytours',
							'status' => 'payment_pending'
						));
					}
				}
			}

		}
        if (!empty($this->request->data) && !empty($this->request->data['ItemUser']['free'])) {		
			$item = $this->Item->find('first', array(
                        'conditions' => array(
                            'Item.id' => $this->request->data['Item']['item_id']
                        ) ,
                        'fields' => array(
                            'Item.is_auto_approve'                           
                        ) ,
                        'recursive' => -1
                    ));
            $this->Item->processPayment($this->request->data['Item']['item_id'], 0, ConstPaymentGateways::Wallet, ConstPaymentType::BookingAmount, $this->request->data['Item']['order_id']);
            $this->Session->setFlash(__l('Bookit successfully completed') , 'default', null, 'success');
			$message = array("message" =>__l('Bookit successfully completed'), "error" => 0);
			if (!$this->RequestHandler->prefers('json')) {
				if($item['Item']['is_auto_approve'] == 1) {
					$this->redirect(array(
						'controller' => 'item_users',
						'action' => 'index',
						'type' => 'mytours',
						'status' => 'in_progress'
					));
				} else {
					$this->redirect(array(
						'controller' => 'item_users',
						'action' => 'index',
						'type' => 'mytours',
						'status' => 'waiting_for_acceptance'
					));				
				}
			}
        }
        if (!empty($this->request->data)) {
            $id = $this->request->data['Item']['item_id'];
            if (!empty($id) && !empty($type)) {
                Cms::dispatchEvent('Model.IntegratedGoogleAnalytics.trackEvent', $this, array(
                    '_trackEvent' => array(
                        'category' => 'ItemUser',
                        'action' => 'Bookit',
                        'label' => 'Step 3',
                        'value' => '',
                    ) ,
                    '_setCustomVar' => array(
                        'pd' => $id,
                        'ud' => $this->Auth->user('id') ,
                        'rud' => $this->Auth->user('referred_by_user_id') ,
                    )
                ));
            }
            $is_error = 0;
            if (!empty($this->request->data['Item']['contact'])) {
                if (!$this->Auth->user('id')) {
                    $item = $this->Item->find('first', array(
                        'conditions' => array(
                            'Item.id' => $this->request->data['Item']['item_id']
                        ) ,
                        'fields' => array(
                            'Item.price_per_day',
                            'Item.slug',
                            'Item.slug',
                            'Item.id',
                            'Item.user_id'
                        ) ,
                        'recursive' => -1
                    ));
                    $valid = $this->process_user($item);
                    if (!$valid) {
                        $is_error = 1;
                        $error_message = __l('Oops, problems in registration, please try again or later');
                    }
                    $_data['ItemUser']['user_id'] = $this->Auth->user('id');
                }
                $_data['ItemUser']['id'] = $this->request->data['Item']['order_id'];
                $this->Item->ItemUser->save($_data, false);
                $itemUser = $this->Item->ItemUser->find('first', array(
                    'conditions' => array(
                        'ItemUser.id' => $this->request->data['Item']['order_id']
                    ) ,
                    'contain' => array(
                        'Item' => array(
                            'User'
                        ) ,
                    ) ,
                    'recursive' => 2
                ));
                $message_sender_user_id = $itemUser['Item']['user_id'];
                $host_email = $itemUser['Item']['User']['email'];
                $subject = 'Negotiation Conversation';
                $message = $this->request->data['ItemUser']['message'];
                $item_id = $itemUser['Item']['id'];
                $order_id = $this->request->data['Item']['order_id'];
                $message_id = $this->Item->ItemUser->Message->sendNotifications($message_sender_user_id, $subject, $message, $order_id, $is_review = 0, $item_id, ConstItemUserStatus::NegotiateConversation);
                if (Configure::read('messages.is_send_email_on_new_message')) {
                    $content['subject'] = $subject;
                    $content['message'] = $message;
                    if (!empty($host_email)) {
                        $this->Item->_sendAlertOnNewMessage($host_email, $content, $message_id, 'Booking Alert Mail');
                    }
                }
                $this->Session->setFlash(__l('Your request has been sent') , 'default', null, 'success');
				if(empty($message))
					$message = array("message" =>__l('Your request has been sent'), "error" => 0);
				if (!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
						'controller' => 'item_users',
						'action' => 'index',
						'type' => 'mytours',
						'status' => 'negotiation',
						'view' => 'list'
					));
				}
            } elseif (!empty($this->request->data['Item']['accept'])) {
                $this->request->data['ItemUser']['id'] = $this->request->data['Item']['order_id'];
                $this->request->data['ItemUser']['negotiation_discount'] = $this->request->data['Item']['negotiation_discount'];
                $this->request->data['ItemUser']['is_negotiated'] = $this->request->data['Item']['is_negotiated'];
                $this->Item->set($this->request->data);
                if ($this->Item->validates()) {
                    $this->Item->ItemUser->save($this->request->data['ItemUser'], false);
                    $this->Session->setFlash(sprintf(__l('You successfully confirmed the %s request') , Configure::read('item.alt_name_for_booker_singular_caps')) , 'default', null, 'success');
					if(empty($message))
						$message = array("message" => sprintf(__l('You successfully confirmed the %s request') , Configure::read('item.alt_name_for_booker_singular_caps')), "error" => 0);
					if (!$this->RequestHandler->prefers('json')) {
						$this->redirect(array(
							'controller' => 'item_users',
							'action' => 'index',
							'type' => 'myworks',
							'status' => 'waiting_for_acceptance'
						));
					}
                } else {
                    $this->Session->setFlash(__l('You request not processed successfully') , 'default', null, 'error');
					if(empty($message))
						$message = array("message" => __l('You request not processed successfully'), "error" => 1);					
					if (!$this->RequestHandler->prefers('json')) {
						$this->redirect(array(
							'controller' => 'items',
							'action' => 'order',
							$id,
							'order_id' => $this->request->data['Item']['order_id'],
							'type' => __l('accept') ,
						));
					}
                }
            } else {
				if (!empty($this->request->data['BuyerFormField'])) {
					$this->BuyerFormField->buildSchema($id);
					if (empty($this->request->data['BuyerFormField'])) {
						$this->request->data['BuyerFormField'] = array();
					}
					$this->request->data['ValidateBuyerFormField'] = $this->request->data['BuyerFormField'];
					$buyerFormFields = $this->BuyerFormField->find('list', array(
						'condition' => array(
							'BuyerFormField.item_id' => $this->request->data['Item']['item_id']
						) ,
						'fields' => array(
							'name',
							'type'
						)
					));
					if (!empty($this->request->data['BuyerFormField'])) {
						foreach($this->request->data['BuyerFormField'] as $tmpFormField => $value) {
							$field_type = $buyerFormFields[$tmpFormField];
						}
						$this->BuyerFormField->set($this->request->data['ValidateBuyerFormField']);
					}
					if ($this->BuyerFormField->validates()) {
						$this->request->data['BuyerSubmission'] = $this->request->data['BuyerFormField'];
						$this->request->data['BuyerSubmission']['item_id'] = $this->request->data['Item']['item_id'];
						$this->BuyerSubmission->submit($this->request->data);
					} else {
						$this->Session->setFlash(__l('Please enter the buyer details.') , 'default', null, 'error');
						if(empty($message))
							$message = array("message" =>__l('Please enter the buyer details.'), "error" => 1);												
						if (!$this->RequestHandler->prefers('json')) {
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'order',
								$this->request->data['Item']['item_id'],
								'order_id' => $this->request->data['Item']['order_id'],
							));
						}
					}
				}
				$this->request->data['ItemUser']['sudopay_gateway_id'] = 0;
				if ($this->request->data['ItemUser']['payment_gateway_id'] != ConstPaymentGateways::Wallet && strpos($this->request->data['ItemUser']['payment_gateway_id'], 'sp_') >= 0) {
					$this->request->data['ItemUser']['sudopay_gateway_id'] = str_replace('sp_', '', $this->request->data['ItemUser']['payment_gateway_id']);
					$this->request->data['ItemUser']['payment_gateway_id'] = ConstPaymentGateways::SudoPay;
				}
                if (!empty($this->request->data['Item']['payment_gateway_id'])) {
                    $this->request->data['Item']['payment_type_id'] = $this->request->data['Item']['payment_gateway_id'];
                }
                if (!empty($this->request->data['ItemUser']['message'])) {
                    $this->request->data['Item']['message'] = $this->request->data['ItemUser']['message'];
                }
            }
            $itemUser = $this->Item->ItemUser->find('first', array(
                'conditions' => array(
                    'ItemUser.id' => $this->request->data['Item']['order_id']
                ) ,
                'contain' => array(
                    'User',
                ) ,
                'recursive' => 0
            ));
            $item_id = $itemUser['ItemUser']['item_id'];
            $order_id = $this->request->data['Item']['order_id'];
            if (!empty($this->request->data['Item']['message'])) {
                $message_sender_user_id = $itemUser['ItemUser']['owner_user_id'];
                $subject = __l('Message from') . ' ' . Configure::read('item.alt_name_for_booker_singular_small');
                $messages = $this->request->data['Item']['message'];
                $message_id = $this->Item->ItemUser->Message->sendNotifications($message_sender_user_id, $subject, $messages, $order_id, $is_review = 0, $item_id, ConstItemUserStatus::BookerConversation);
            }
            $total_amount = $itemUser['ItemUser']['booker_service_amount'] + $itemUser['ItemUser']['additional_fee_amount'] + $itemUser['ItemUser']['original_price'] - $itemUser['ItemUser']['coupon_discount_amont'];
            if (!empty($this->request->data['ItemUser']['payment_gateway_id'])) {
                $_data = array();
                $_data['ItemUser']['id'] = $this->request->data['Item']['order_id'];
                $_data['ItemUser']['payment_gateway_id'] = $this->request->data['ItemUser']['payment_gateway_id'];
                $_data['ItemUser']['sudopay_gateway_id'] = $this->request->data['ItemUser']['sudopay_gateway_id'];
                $this->Item->ItemUser->save($_data);
                if (!empty($this->request->data['ItemUser']['payment_gateway_id']) && $this->request->data['ItemUser']['payment_gateway_id'] == ConstPaymentGateways::Wallet) {
                    $this->loadModel('Wallet.Wallet');
                    $return = $this->Wallet->processPayToItem($this->Auth->user('id') , $total_amount, $this->request->data['Item']['order_id'], ConstPaymentType::BookingAmount);
                    if (!$return) {
                        $this->Session->setFlash(__l('Your wallet has insufficient money') , 'default', null, 'error');
						if(empty($message))
							$message = array("message" =>__l('Your wallet has insufficient money'), "error" => 1);
						if (!$this->RequestHandler->prefers('json')) {
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'order',
								$itemUser['ItemUser']['item_id'],
								'order_id' => $this->request->data['Item']['order_id']
							));
						}
                    } else {
                        $this->Session->setFlash(__l('Payment successfully completed') , 'default', null, 'success');
						if(empty($message))
							$message = array("message" =>__l('Payment successfully completed'), "error" => 0);
						if (!$this->RequestHandler->prefers('json')) {
							$this->redirect(array(
								'controller' => 'item_users',
								'action' => 'index',
								'type' => 'mytours',
								'status' => 'waiting_for_acceptance'
							));
						}
                    }
                } elseif (!empty($this->request->data['ItemUser']['payment_gateway_id']) && $this->request->data['ItemUser']['payment_gateway_id'] == ConstPaymentGateways::SudoPay) {
                    $this->loadModel('Sudopay.Sudopay');
                    $sudopay_gateway_settings = $this->Sudopay->GetSudoPayGatewaySettings();
                    $this->set('sudopay_gateway_settings', $sudopay_gateway_settings);
                    if ($sudopay_gateway_settings['is_payment_via_api'] == ConstBrandType::VisibleBranding) {
                        $sudopay_data = $this->Sudopay->getSudoPayPostData($this->request->data['Item']['order_id'], ConstPaymentType::BookingAmount);
                        $sudopay_data['merchant_id'] = $sudopay_gateway_settings['sudopay_merchant_id'];
                        $sudopay_data['website_id'] = $sudopay_gateway_settings['sudopay_website_id'];
                        $sudopay_data['secret_string'] = $sudopay_gateway_settings['sudopay_secret_string'];
                        $sudopay_data['action'] = 'marketplace-auth';
						$sudopay_data['button_url'] = '\'' . '//d1fhd8b1ym2gwa.cloudfront.net/btn/sudopay_btn.js'. '\'';
						if(!empty($sudopay_gateway_settings['is_test_mode'])){
							$sudopay_data['button_url'] = '\'' . '//d1fhd8b1ym2gwa.cloudfront.net/btn/sandbox/sudopay_btn.js'. '\'';
						}
                        $this->set('sudopay_data', $sudopay_data);
                    } else {
                        $this->request->data['Sudopay'] = !empty($this->request->data['Sudopay']) ? $this->request->data['Sudopay'] : '';
                        if ($this->RequestHandler->prefers('json')){
                            $call_back_url = $this->Sudopay->processPayment($itemUser['ItemUser']['id'], ConstPaymentType::BookingAmount, $this->request->data['Sudopay'], 'json');
                            if(empty($call_back_url['error'])){
                                if(!empty($call_back_url['success'])){
                                    $return = $call_back_url;
                                }else{
                                    $this->set('iphone_response', array("message" => $call_back_url, "error" => 0));
                                }
                            }else{
                                $this->set('iphone_response', array("message" => $return['error_message'] . ' ' . __l('Payment could not be completed.'), "error" => 1));
                                $return = $return;
                            }
                        }else{
                            $return = $this->Sudopay->processPayment($itemUser['ItemUser']['id'], ConstPaymentType::BookingAmount, $this->request->data['Sudopay']);
                        }
                        if (!empty($return['pending'])) {
                            $this->Session->setFlash($return['error_message'] . ' '.__l('Once payment is received, it will be processed.') , 'default', null, 'success');
							if(empty($message))
                            $message = array("message" =>$return['error_message'] . ' '.__l('Once payment is received, it will be processed.'), "error" => 0);
							if (!$this->RequestHandler->prefers('json')) {
								$this->redirect(array(
									'controller' => 'item_users',
									'action' => 'index',
									'type' => 'mytours',
									'status' => 'payment_pending'
								));
							}
                        } elseif (!empty($return['success'])) {
                            $receiver_data = $this->Item->ItemUser->getReceiverdata($itemUser['ItemUser']['id'], ConstTransactionTypes::BookItem, $itemUser['User']['email']);
                            $this->Item->processPayment($itemUser['ItemUser']['item_id'], $receiver_data['amount']['0'], ConstPaymentGateways::SudoPay, ConstPaymentType::BookingAmount, $itemUser['ItemUser']['id']);
                            $this->Session->setFlash(__l('Payment successfully completed') , 'default', null, 'success');
							if(empty($message))
								$message = array("message" =>__l('Payment successfully completed'), "error" => 0);
							if (!$this->RequestHandler->prefers('json')) {
								$this->redirect(array(
									'controller' => 'item_users',
									'action' => 'index',
									'type' => 'mytours',
									'status' => 'waiting_for_acceptance'
								));
							}
                        } elseif (!empty($return['error'])) {
                            $this->Session->setFlash($return['error_message'] . ' ' . __l('Payment could not be completed.') , 'default', null, 'error');
							if(empty($message))
								$message = array("message" =>$return['error_message'] . ' ' . __l('Payment could not be completed.'), "error" => 1);
							if (!$this->RequestHandler->prefers('json')) {
								$this->redirect(array(
									'controller' => 'items',
									'action' => 'order',
									$itemUser['ItemUser']['item_id'],
									'order_id' => $this->request->data['Item']['order_id']
								));
							}
                        }
                    }
                }
            }
            if (!$this->Auth->user('id')) {
                $valid = $this->process_user($item);
                if (!$valid) {
                    $is_error = 1;
                    $error_message = __l('Oops, problems in registration, please try again or later');
                }
            }
        }
		// Todo: json api post below no need
			if (!empty($this->request->params['named']['is_ajax'])) {
				$this->layout = 'ajax';
			}
			if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'contact') {
				$this->pageTitle = __l('Pricing Negotiation');
			} else if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'accept') {
				$this->pageTitle = __l('Booking Request Confirm');
			} else if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'cancel') {
				$this->pageTitle = __l('Booking Cancel Process');
			} else {
				$this->pageTitle = __l('Book It');
			}
			if (is_null($id)) {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
			}
			$order_id = !empty($this->request->params['named']['order_id']) ? $this->request->params['named']['order_id'] : $this->request->data['Item']['order_id'];
			if ($type == 'item') {
					$contain = array(
						'Attachment' => array(
							'fields' => array(
								'Attachment.id',
								'Attachment.filename',
								'Attachment.dir',
								'Attachment.width',
								'Attachment.height'
							) ,
						) ,
						'ItemUser' => array(
							'conditions' => array(
								'ItemUser.id' => !empty($this->request->params['named']['order_id']) ? $this->request->params['named']['order_id'] : $this->request->data['Item']['order_id']
							) ,
							'CustomPricePerTypeItemUser' => array(
								'CustomPricePerNight',
								'CustomPricePerType',
							) ,
							'CustomPricePerNight' => array(
								'CustomPricePerType',
							) ,							
						) ,
						'Country' => array(
							'fields' => array(
								'Country.name',
								'Country.iso_alpha2'
							)
						) ,
						'User',
						'BuyerFormField',
					);
					if(isPluginEnabled('Seats')){
						$contain['ItemUser']['CustomPricePerTypesSeat'] = array(
							'Partition',
							'order' => array(
								'CustomPricePerTypesSeat.name' => 'ASC'
							) ,
						);
					}
					if (isPluginEnabled('ItemFavorites')) {
						$conditions_fav = array();
						if ($this->Auth->user()) {
							$conditions_fav['ItemFavorite.user_id'] = $this->Auth->user('id');
						}
						$contain['ItemFavorite'] = array(
							'conditions' => $conditions_fav,
							'fields' => array(
								'ItemFavorite.id',
								'ItemFavorite.user_id',
								'ItemFavorite.item_id',
							)
						);
					}
					$itemDetail = $this->Item->find('first', array(
						'conditions' => array(
							'Item.id' => $id
						) ,
						'contain' => $contain,
						'recursive' => 3
					));
					if(isPluginEnabled('Seats') && $itemDetail['ItemUser'][0]['is_seating_selection'] && empty($itemDetail['ItemUser'][0]['CustomPricePerTypesSeat'])){				
						$this->redirect(array(
							'controller' => 'seats',
							'action' => 'selection',
							$order_id
						));
					}
					if(isPluginEnabled('Seats') && $itemDetail['ItemUser'][0]['is_seating_selection'] && !empty($itemDetail['ItemUser'][0]['CustomPricePerTypesSeat'])) {
						$url = Router::url(array('controller' => 'item_users', 'action' => 'delete', $this->request->params['named']['order_id'], 'type' => 'booking_timeout'), true);
						$CustomPricePerTypesSeats = $itemDetail['ItemUser'][0]['CustomPricePerTypesSeat'];
						$block_date = $CustomPricePerTypesSeats[0]['booking_start_time'];
						$cur_time = strtotime(date('Y-m-d H:i:s'));
						$block_time = strtotime($block_date);
						$total = 1;
						if($block_time > $cur_time) {
							$diff = $block_time - $cur_time;
							$minutes =  date('i', ($diff));
							$secs =  date('s', ($diff));
							$total = ($minutes * 60) + $secs;
						} else {
							$this->redirect(array('controller' => 'item_users', 'action' => 'delete', $this->request->params['named']['order_id'], 'type' => 'booking_timeout'));							
						}
						$this->set(compact('total', 'url'));
					}
					if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'cancel') {
						if ($itemDetail['ItemUser'][0]['item_user_status_id'] == ConstItemUserStatus::Canceled || $itemDetail['ItemUser'][0]['item_user_status_id'] == ConstItemUserStatus::CanceledByAdmin) {
                            if ($this->RequestHandler->prefers('json')) {
                                $this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
                            }else{
                                throw new NotFoundException(__l('Invalid request'));
                            }
						}
						$refund_amount['booker_balance'] = $itemDetail['ItemUser'][0]['price'];
						$this->set('refund_amount', $refund_amount);
					}
					$this->pageTitle.= ' - ' . $itemDetail['Item']['title'];
			}
			if (empty($itemDetail)) {
				if ($this->RequestHandler->prefers('json')) {
						$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
			}
			if ((isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'accept') || empty($this->request->params['named']['type'])) {
				if (!empty($itemDetail) && $this->Auth->user('id') && $itemDetail['ItemUser'][0]['user_id'] != $this->Auth->user('id')) {
					if ($this->RequestHandler->prefers('json')) {
						$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
					}else{
						throw new NotFoundException(__l('Invalid request'));
					}
				}
			} else {
				if (!empty($itemDetail) && $this->Auth->user('id') && $itemDetail['ItemUser'][0]['owner_user_id'] != $this->Auth->user('id')) {	
					if ($this->RequestHandler->prefers('json')) {
						$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
					}else{
						throw new NotFoundException(__l('Invalid request'));
					}
				}
			}
			$user_info = $this->Item->User->find('first', array(
				'conditions' => array(
					'User.id' => $this->Auth->user('id')
				) ,
				'fields' => array(
					'User.id',
					'User.username',
					'User.available_wallet_amount',
				) ,
				'recursive' => -1
			));
			$this->set('itemDetail', $itemDetail);
			$this->set('user_info', $user_info);
			$this->request->data['Item']['type'] = $type;
			$this->request->data['Item']['item_id'] = $id;
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json')) {
            $details = array('itemDetail' => $message);
            if(!empty($this->request->params['named']['is_itemdetail'])){
                if($itemDetail['Item']['is_people_can_book_my_time']){
                App::import('Helper', 'AppHelper');
                $getPriceHelper = new AppHelper();
                $start = explode(' ', $itemDetail['ItemUser'][0]['from']);
                $end = explode(' ', $itemDetail['ItemUser'][0]['to']);
                $returns  = $getPriceHelper->getCustomPrice($start[0], $start[1], $end[0], $end[1], $itemDetail['Item']['id']);
                    if(!empty($returns)){
                        $itemDetail['ItemUser'][0]['custom_price_return'] = $getPriceHelper->getCustomPrice($start[0], $start[1], $end[0], $end[1], $itemDetail['Item']['id']);
                    }
                }
                $c_type = $itemDetail['ItemUser'][0]['CustomPricePerTypeItemUser'];
                if(!empty($itemDetail['ItemUser'][0]['CustomPricePerTypeItemUser'])){
                    for($k = 0; $k < count($c_type); $k++){
                        $prices = $itemDetail['ItemUser'][0]['CustomPricePerTypeItemUser'][$k];
                        $prices['CustomPricePerType']['name'];
                        $itemDetail['ItemUser'][0]['CustomPricePerTypeItemUser'][$k]['name'] = $prices['CustomPricePerType']['name'];
						 if($itemDetail['Item']['is_people_can_book_my_time']){
							$itemDetail['ItemUser'][0]['CustomPricePerTypeItemUser'][$k]['name'] = $prices['CustomPricePerNight']['name'];
						 }
					}
                }
                $details = array('itemDetail' => $itemDetail);
            }
			Cms::dispatchEvent('Controller.Item.Order', $this, $details);
		}
		
    }
    public function get_itemtime()
    {
        if (empty($this->request->params['named']['item_id'])) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$item_id = $this->request->params['named']['item_id'];
		$day_of_the_week = array('M' => 1, 'Tu' => 2, 'W' => 3, 'Th' => 4, 'F' => 5, 'Sa' => 6, 'Su' => 7);
		$current = date('Y-m-d');
		
		$fixed_contain = array(
			'Item' => array(
				'fields' => array(
				  'Item.id',
				  'Item.min_number_of_ticket',
				) ,
			),
			'CustomPricePerType'
		);
		if (isPluginEnabled('Seats')) {
            $fixed_contain[] = 'Hall';
			$fixed_contain['CustomPricePerType'] = 'Partition';
        }
		$item = $this->Item->CustomPricePerNight->find('all', array(
			'conditions' => array(
				'CustomPricePerNight.is_available' => 1,
				'CustomPricePerNight.parent_id' => 0,
				'CustomPricePerNight.item_id' => $item_id,
			) ,
            'contain' => $fixed_contain,
			'order' => array(
				'CustomPricePerNight.start_date' => 'ASC'
			) ,
			'recursive' => 2 ,
		));
		$avalibilites = array();
		$item_count = count($item);
		$is_needed = true;
		foreach($item As $customPricePerNight) {
				$repeat_end_date = $customPricePerNight['CustomPricePerNight']['repeat_end_date'];
				if (!empty($customPricePerNight['CustomPricePerNight']['repeat_days'])) {
					$repeat_days = array();
					$repeat_days_arr = explode(',', $customPricePerNight['CustomPricePerNight']['repeat_days']);
					$start = $customPricePerNight['CustomPricePerNight']['start_date'];
					$end = $repeat_end_date;
					$phpTime = strtotime($repeat_end_date);
					$new_end = date('Y-m-d', strtotime('next saturday', mktime(0, 0, -1, date('m', $phpTime) +1, 1, date('Y', $phpTime))));
					foreach($repeat_days_arr as $repeat_day) {
						$repeat_days[] = $day_of_the_week[$repeat_day];
					}
					$total_days = ceil((strtotime($end) - strtotime($start)) /(60*60*24));					
					$subCustomPricePerNights = $this->Item->CustomPricePerNight->find('all', array(
						'conditions' => array(
							'CustomPricePerNight.parent_id' => $customPricePerNight['CustomPricePerNight']['id'],
							'CustomPricePerNight.start_date >=' => $start ,
							'CustomPricePerNight.start_date <=' => $new_end,
						) ,
						'contain' => array(
							'CustomPricePerType'
						) ,
					));
					$subTmpCustomPricePerNights = array();
					$j = 0;
					foreach($subCustomPricePerNights as $subCustomPricePerNight) {
						$subTmpCustomPricePerNights[$j] = $subCustomPricePerNight['CustomPricePerNight']['start_date'];
						$j++;
					}
					for ($i = 0; $i <= $total_days; $i++) {
						$day = date('Y-m-d', strtotime($start . "+" . $i . " day"));
						$day_of_day = date('N', strtotime($day));
						$repeat_chk_st_dt = strtotime($day);
						$repeat_chk_end_dt = strtotime($repeat_end_date);
						if($repeat_chk_st_dt <= $repeat_chk_end_dt) {
							if($customPricePerNight['CustomPricePerNight']['start_date'] != $day) {
								if ($key = array_search($day, $subTmpCustomPricePerNights)) {
									$avalibilites[] = $subCustomPricePerNights[$key];
								} elseif (in_array($day_of_day, $repeat_days)) {
									if(strtotime($customPricePerNight['CustomPricePerNight']['start_date']) < strtotime($day)){									
										$diff_days = ceil((strtotime($customPricePerNight['CustomPricePerNight']['end_date']) - strtotime($customPricePerNight['CustomPricePerNight']['start_date'])) /(60*60*24));
										$end_date_check = date('Y-m-d', strtotime($day . "+" . $diff_days . " day"));
										$check_custom_price = $this->Item->CustomPricePerNight->find('first', array(
											'conditions' => array(
												'CustomPricePerNight.start_date' => $day ,
												'CustomPricePerNight.end_date' => $end_date_check ,
												'CustomPricePerNight.is_custom' => 1
											),
											'recursive' => 1
										));
										$data = array();
										if(!empty($check_custom_price)){
											$data['is_custom_available'] = 1;
											$data['CustomPricePerNight']['id'] = $check_custom_price['CustomPricePerNight']['id'];
										}
										$data['CustomPricePerNight']['parent_id'] = $customPricePerNight['CustomPricePerNight']['id'];
										$data['CustomPricePerNight']['item_id'] = $customPricePerNight['CustomPricePerNight']['item_id'];
										$data['CustomPricePerNight']['start_date'] = $day;
										$data['CustomPricePerNight']['start_time'] = $customPricePerNight['CustomPricePerNight']['start_time'];
										$data['CustomPricePerNight']['end_date'] = date('Y-m-d', strtotime($day . "+" . $diff_days . " day"));
										$data['CustomPricePerNight']['end_time'] = $customPricePerNight['CustomPricePerNight']['end_time'];
										$data['CustomPricePerNight']['is_available'] = 1;
										$data['CustomPricePerNight']['minimum_price'] = $customPricePerNight['CustomPricePerNight']['minimum_price'];
										$data['CustomPricePerNight']['is_tipped'] = $customPricePerNight['CustomPricePerNight']['is_tipped'];
										$data['CustomPricePerNight']['total_available_count'] = $customPricePerNight['CustomPricePerNight']['total_available_count'];
										$data['CustomPricePerNight']['total_booked_count'] = 0;
										$data['CustomPricePerNight']['repeat_days'] = $customPricePerNight['CustomPricePerNight']['repeat_days'];
										$data['CustomPricePerNight']['is_tipped'] = $customPricePerNight['CustomPricePerNight']['is_tipped'];
										if(isPluginEnabled('Seats') && !empty($customPricePerNight['Hall'])){
											
											$data['Hall'] = array(
												'name' => $customPricePerNight['Hall']['name']
											);
										}
										
										$avalibilites[] = $data;
										
									}
								}
							} else {
								$avalibilites[] = $customPricePerNight;
							}
						} else {
							break;
						}
					}
				} else {
					$avalibilites[] = $customPricePerNight;
				}
			}
			$key_ids = array();
		foreach ($avalibilites as $key => $part) {
			if(strtotime($part['CustomPricePerNight']['start_date']) <= strtotime($current)){
				$key_ids[] = $key;
			} else {
				$sort[$key] = strtotime($part['CustomPricePerNight']['start_date']);
			}
		}
		if(!empty($key_ids)){
			foreach($key_ids as $index){
				unset($avalibilites[$index]);
			}
		}
		if(!empty($avalibilites)){
			array_multisort($sort, SORT_ASC, $avalibilites);
			$avalibilites = array_slice($avalibilites, 0, 5);
		}
		$this->set('custom_price_per_nights', $avalibilites);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json')) {
			Cms::dispatchEvent('Controller.Item.GetItemTime', $this, array());
		}		
    }
    public function get_itemprices()
    {
		$custom_price_per_night_id = $this->request->params['named']['custom_price_per_night_id'];
		$params = explode('-', $this->request->params['named']['custom_price_per_night_id']);
		if(!empty($params) && count($params) > 1) {
			$custom_price_per_night_id = $params[0];
		}
		$fixed_contain = array(
			'Item',
			'CustomPricePerType'
		);
		if (isPluginEnabled('Seats')) {
            $fixed_contain[] = 'Hall';
			$fixed_contain['CustomPricePerType'] = 'Partition';
        }
		$custom_price_per_night = $this->Item->CustomPricePerNight->find('first', array(
            'conditions' => array(
                'CustomPricePerNight.id' => $custom_price_per_night_id,
            ) ,
            'contain' => $fixed_contain,
            'recursive' => 2,
			'order' => array(
				'CustomPricePerNight.start_date' => 'ASC'
			),
        ));
		if(!empty($params) && count($params) > 1) {
			$diff_days = ceil(strtotime($custom_price_per_night['CustomPricePerNight']['end_date']) - strtotime($custom_price_per_night['CustomPricePerNight']['start_date'])) /(60*60*24);
			$custom_price_per_night['CustomPricePerNight']['item_id'] = $custom_price_per_night['CustomPricePerNight']['item_id'];
			$custom_price_per_night['CustomPricePerNight']['parent_id'] = $custom_price_per_night['CustomPricePerNight']['id'];
			$new_start = date('Y-m-d', mktime(0, 0, 0, $params[2], $params[3], $params[1]));
			$custom_price_per_night['CustomPricePerNight']['start_date'] = $new_start;
			$custom_price_per_night['CustomPricePerNight']['start_time'] = $custom_price_per_night['CustomPricePerNight']['start_time'];
			$custom_price_per_night['CustomPricePerNight']['end_date'] = date('Y-m-d', strtotime($new_start . "+" . round($diff_days) . " day"));
			$custom_price_per_night['CustomPricePerNight']['end_time'] = $custom_price_per_night['CustomPricePerNight']['end_time'];
			$custom_price_per_night['CustomPricePerNight']['is_available'] = 1;
			$custom_price_per_night['CustomPricePerNight']['minimum_price'] = $custom_price_per_night['CustomPricePerNight']['minimum_price'];
			$custom_price_per_night['CustomPricePerNight']['is_tipped'] = $custom_price_per_night['CustomPricePerNight']['is_tipped'];
			$custom_price_per_night['CustomPricePerNight']['total_available_count'] = $custom_price_per_night['CustomPricePerNight']['total_available_count'];
			$custom_price_per_night['CustomPricePerNight']['total_booked_count'] = 0;
			$custom_price_per_night['CustomPricePerNight']['repeat_days'] = $custom_price_per_night['CustomPricePerNight']['repeat_days'];
			$custom_price_per_night['CustomPricePerNight']['is_tipped'] = $custom_price_per_night['CustomPricePerNight']['is_tipped'];
			$this->set('is_parent', 1);
		}
        $this->set('custom_price_per_night', $custom_price_per_night);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json')) {
			Cms::dispatchEvent('Controller.Item.GetItemPrices', $this, array());
		}
    }
    public function show_admin_control_panel()
    {
        $this->disableCache();
        if (!empty($this->request->params['named']['view_type']) && $this->request->params['named']['view_type'] == 'item') {
            $item = $this->Item->find('first', array(
                'conditions' => array(
                    'Item.id' => $this->request->params['named']['id']
                ) ,
                'recursive' => 0
            ));
            $this->set('item', $item);
        }
        $this->layout = 'ajax';
    }
    public function attachment_delete($id)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$attachment = $this->Item->Attachment->find('first', array(
			'conditions' => array(
				'Attachment.id >=' => $id,
			) ,
			'recursive' => -1
		));
		if(empty($attachment))
			throw new NotFoundException(__l('Invalid request'));
		$attachments = $this->Item->Attachment->find('all', array(
			'conditions' => array(
				'Attachment.foreign_id' => $attachment['Attachment']['foreign_id'],
				'Attachment.class' => 'Item',
			) ,
			'recursive' => -1
		));
		if(count($attachments) >= 1){
			if ($this->Item->Attachment->delete($id)) {
				 if ($this->RequestHandler->isAjax())
					echo "success";
				 else
					$this->redirect(array(
						'controller' => 'items',
						'action' => 'index'
					));
			}else{
				if ($this->RequestHandler->isAjax())
					echo "<span class='label label-danger'>Error</span>  ".__l('Unable to delet this image.');
				 else
					$this->redirect(array(
						'controller' => 'items',
						'action' => 'index'
					));
			}
		}else{
			if ($this->RequestHandler->isAjax())
				echo "<span class='label label-danger'>Error</span> ".__l('Must be need Minimum one image');
			 else
				$this->redirect(array(
					'controller' => 'items',
					'action' => 'index'
				));			
		}
		exit();
	} 
	public function admin_attachment_delete($id){
		$this->setAction('attachment_delete', $id);
	}
    public function partitions()
    {
		$viewObj = new View($this);
		App::import('Helper', 'Html');
        $html = new HtmlHelper($viewObj);
		$this->pageTitle = 'Partitions';
		if (empty($this->request->params['named']['slug']) || (!isPluginEnabled('Seats'))) {
            if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$hall = array();
		$partition = array();
		$item = $this->Item->find('first', array(
			'fields' => array(
				'Item.id',
				'Item.title',
				'Item.slug'
			),
			'conditions' => array(
				'Item.slug' => $this->request->params['named']['slug']
			),
			'recursive' => -1
		));		
		$contain = array(				
			'CustomPricePerNight' => array(
				'fields' => array(
						'CustomPricePerNight.id',
						'CustomPricePerNight.start_date',
						'CustomPricePerNight.end_date',
						'CustomPricePerNight.name',
						'CustomPricePerNight.description',
						'CustomPricePerNight.quantity',
						'CustomPricePerNight.is_seating_selection',
						'CustomPricePerNight.hall_id'
					),									
				'conditions' => array(
					'CustomPricePerNight.is_seating_selection' => true,						
				),
			),				
				
		);
		if (isPluginEnabled('Seats')){			
			$contain['Partition'] = array(
					'fields' => array(
						'Partition.id',
						'Partition.name',
						'Partition.slug'
					)
				);				
		}
		$conditions = array(
                'CustomPricePerType.item_id' => $item['Item']['id'],
				'CustomPricePerType.partition_id !=' => null,
            );
		if(!empty($this->request->data['Item']['partition_id'])){
			$conditions['CustomPricePerType.id'] = $this->request->data['Item']['partition_id'];
		}
		$this->paginate = array(
			'conditions' => $conditions,
			'fields' => array(
				'CustomPricePerType.id',
				'CustomPricePerType.name',
				'CustomPricePerType.price',
				'CustomPricePerType.start_time',
				'CustomPricePerType.end_time',
				'CustomPricePerType.max_number_of_quantity',
				'CustomPricePerType.booked_quantity',
				'CustomPricePerType.partition_id',
				'CustomPricePerType.available_seat_count',
				'CustomPricePerType.unavailable_seat_count',
				'CustomPricePerType.no_seat_count',
				'CustomPricePerType.blocked_count',
				'CustomPricePerType.waiting_for_acceptance_count',
				'CustomPricePerType.partition_id'
			),
			'contain' => $contain,
			'recursive' => 1
		);
		$this->set('item_partitions',$this->paginate('CustomPricePerType'));	
		$this->set('item',$item);
		$total_contain = array(				
			'CustomPricePerType' => array(
				'fields' => array(
						'CustomPricePerType.id',
						'CustomPricePerType.name',
						'CustomPricePerType.price',
						'CustomPricePerType.start_time',
						'CustomPricePerType.end_time'						
					),									
				'conditions' => array(
					'CustomPricePerType.partition_id !=' => null,
				)
			)				
		);		
		if (isPluginEnabled('Seats')){
			$total_contain['Hall'] = array(
				'fields' => array(
					'Hall.id',
					'Hall.name'
				)			
			);
			$total_contain['CustomPricePerType']['Partition'] = array(
					'fields' => array(
						'Partition.id',
						'Partition.name',
						'Partition.slug'
					)
				);	
		}
		$total_type = $this->Item->CustomPricePerNight->find('all', array(
					'conditions' => array(
						'CustomPricePerNight.item_id' => $item['Item']['id'],
						'CustomPricePerNight.hall_id !=' => null,
						'CustomPricePerNight.is_seating_selection' => true						
					),
					'fields' => array(
						'CustomPricePerNight.id',
						'CustomPricePerNight.start_date',
						'CustomPricePerNight.end_date',
						'CustomPricePerNight.name',
						'CustomPricePerNight.is_seating_selection',
						'CustomPricePerNight.hall_id'
					),
					'contain' => $total_contain,
					'recursive' => 2
				)
			);
		$partitions = array();
		foreach($total_type as $key => $value){
			if(count($value['CustomPricePerType']) > 0){
				$ctype = array();
				foreach($value['CustomPricePerType'] as $cppt){
					$ctype[$cppt['id']] = $cppt['Partition']['name'] . ' [ ' . $html->cTime($cppt['start_time'],false,false) . '-' . $html->cTime($cppt['end_time'],false,false) . ' ]';
				}
				$partitions[$value['Hall']['name']. ' [ ' . $html->cDate($value['CustomPricePerNight']['start_date'], false, false) . '-' . $html->cDate($value['CustomPricePerNight']['end_date'], false, false) . ' ]'] = $ctype;
			}
		}
		$this->set('partitions',$partitions);
		//Iphone app code
		if ($this->RequestHandler->prefers('json')) {
			Cms::dispatchEvent('Controller.Items.partition', $this, array());
		}
    }
	public function admin_partitions() {
		$this->setAction('partitions');
	}
}
?>