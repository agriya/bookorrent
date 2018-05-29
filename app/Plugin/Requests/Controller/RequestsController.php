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
class RequestsController extends AppController
{
    public $name = 'Requests';
    public $components = array(
        //'OauthConsumer',
    );
	public $helpers = array(
        'Text',
		'Items.Cakeform'
    );
    public $permanentCacheAction = array(
		'user' => array(
			'add',
			'edit',
		) ,
		'public' => array(
			'index',
		) ,
        'is_view_count_update' => true
    );
    public function beforeFilter()
    {
		// form once submission check
		$this->Security->csrfCheck = true;
        if ($this->request->action == 'update_view_count') {
            $this->Security->validatePost = false;
        }
		$this->Security->disabledFields = array(
            'City.id',
            'State.id',
            'State.name',
            'City.name',
            'Request.country_id',
			'Request.zoom_level',
            'Request.latitude',
            'Request.longitude',
            'Request.sw_latitude',
            'Request.ne_longitude',
            'Request.sw_longitude',
            'Request.ne_latitude',
            'Request.post',
            'Request.steps',
            'Request.step1',
            'Request.step2',
            'Request.step3',
            'Request.step4',
            'FormField',
            'Request.sub_category_id',
            'Request.category_id',
            'Request.address1',
            'Request.address',
            'Request.description',
            'Request.title',
            'Request.category_type_id',
            'Request.parent_category_id',
            'Request.Category',
			'Request.user_id',
			'Request.range_from',
			'Request.range_to',
			'Request.price_range'
        );
        parent::beforeFilter();
    }
    public function index($hash_keyword = '', $salt = '')
    {
        $this->_redirectPOST2Named(array(
            'cityName',
            'latitude',
            'longitude',
            'sw_latitude',
            'ne_longitude',
            'sw_longitude',
            'ne_latitude',
            'from',
            'keyword',
            'to',
            'additional_guest',
            'type',
            'search',
            'is_flexible',
            'range_from',
            'range_to',
            'Category',
        ));
        $this->pageTitle = __l('Requests');
        $conditions = $search_keyword = array();
        $fields = $current_latitude = $current_longitude = '';
        $conditions['Request.is_approved'] = $conditions['Request.admin_suspend !='] = $conditions['Request.is_active'] = 1;
        if ($this->RequestHandler->isAjax() && !isset($this->request->params['named']['share']) && ((!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'lst_my_items') || empty($this->request->params['named']['type']))) {
            $this->set('search', 'map');
        }
        if (!empty($hash_keyword) && !empty($salt)) {
            $named_array = $this->Request->getSearchKeywords($hash_keyword, $salt);
            $search_keyword['named'] = array_merge($this->request->params['named'], $named_array);
            $this->request->params['named']['type'] = $search_keyword['named']['type'];
            $this->request->params['pass']['0'] = $this->request->params['named']['hash'] = $hash_keyword;
            $this->request->params['pass']['1'] = $this->request->params['named']['salt'] = $salt;
            $is_city = false;
        } elseif (!empty($this->request->params['named']['type'])) {
        } else {
            if (empty($this->request->params['named']['city'])) {
                $this->request->params['named']['city'] = 'all';
            }
            $query_string = '';
            if (!empty($this->request->params['named']['city'])) {
				App::import('Model', 'City');
				$this->City = new City();				
                $CityList = $this->City->find('first', array(
                    'conditions' => array(
                        'City.slug' => $this->request->params['named']['city'],
                    ) ,
                    'recursive' => -1
                ));
				$query_string = '/city:' . (!empty($CityList['City']['name'])) ? $CityList['City']['name'] : '';
				$query_string.= '/cityname:' . (!empty($CityList['City']['name'])) ? $CityList['City']['name'] : '';
				$query_string.= '/latitude:' . (!empty($CityList['City']['latitude'])) ? $CityList['City']['latitude'] : '';
				$query_string.= '/longitude:' . (!empty($CityList['City']['longitude'])) ? $CityList['City']['longitude'] : '';
                $query_string.= '/from:' . date('Y-m-d');
                $query_string.= '/to:' . date('Y-m-d');
                $query_string.= '/additional_guest:1';
                $query_string.= '/range_from:1';
                $query_string.= '/is_flexible:1';
                $query_string.= '/type:search';
                $searchkeyword['SearchKeyword']['keyword'] = $query_string;
				App::import('Model', 'Items.SearchKeyword');
				$this->SearchKeyword = new SearchKeyword();
                $this->SearchKeyword->save($searchkeyword, false);
                $keyword_id = $this->SearchKeyword->getLastInsertId();
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
                $this->request->params['pass']['0'] = dechex($keyword_id);
                $this->request->params['pass']['1'] = substr(dechex($salt) , 0, 2);
				$search_keyword['named']['cityname'] = (!empty($CityList['City']['name'])) ? $CityList['City']['name'] : '';
				$search_keyword['named']['latitude'] = (!empty($CityList['City']['latitude'])) ? $CityList['City']['latitude'] : '';
				$search_keyword['named']['longitude'] = (!empty($CityList['City']['longitude'])) ? $CityList['City']['longitude'] : '';
                $search_keyword['named']['from'] = date('Y-m-d');
                $search_keyword['named']['to'] = getToDate(date('Y-m-d'));
                $search_keyword['named']['additional_guest'] = 1;
                $search_keyword['named']['range_from'] = '1';
                $search_keyword['named']['is_flexible'] = 1; // default flexible
                $search_keyword['named']['type'] = 'search';
            }
        }
        if (!empty($search_keyword['named']['type']) && $search_keyword['named']['type'] == 'search') {
            $current_latitude = $search_keyword['named']['latitude'];
            $current_longitude = $search_keyword['named']['longitude'];
			
			$category = isset($search_keyword['named']['category']) ? $search_keyword['named']['category'] : '';
			if (!empty($category)) {
				$this->request->data['Request']['Category'] = explode(',', $category);
				if (count($this->request->data['Request']['Category']) > 0) {
					$conditions['Request.category_id'] = $this->request->data['Request']['Category'];
				}
			}
            $from = $search_keyword['named']['from'];
            $this->request->data['Request']['to'] = $to = $search_keyword['named']['to'];
            $this->request->data['Request']['from'] = $to_cdn = $to;
            if ($from < date('Y-m-d') || $to > $to || $to < date('Y-m-d')) {
                $this->Session->setFlash(__l('from/to date is invalid') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'requests',
                    'action' => 'index',
                ));
            }
            $exact_match = array();
            $exact_match['Request.is_approved'] = $exact_match['Request.is_active'] = $exact_match['Request.admin_suspend !='] = 1;
            $this->request->data['Request']['additional_guest'] = $additional_guest = !empty($search_keyword['named']['additional_guest']) ? $search_keyword['named']['additional_guest'] : 1;
			if (!empty($search_keyword['named']['range_from'])) {
	            $this->request->data['Request']['range_from'] = $conditions['Request.price >='] = $exact_match['Request.price >='] = $search_keyword['named']['range_from'];
			}
            if (!empty($search_keyword['named']['range_to']) && $search_keyword['named']['range_to'] != '300+') {
                $this->request->data['Request']['range_to'] = $conditions['Request.price <='] = $exact_match['Request.price <='] = $search_keyword['named']['range_to'];
            }
            if (!empty($search_keyword['named']['latitude']) && !empty($search_keyword['named']['longitude'])) {
                $db = ConnectionManager::getDataSource('default');	
				$fields = '3956 * 2 * ASIN(SQRT(  POWER(SIN((' . $db->startQuote . 'Request' . $db->endQuote . '.' . $db->startQuote . 'latitude' . $db->endQuote . ' - ' . $current_latitude . ') * pi()/180 / 2), 2) + COS(' . $db->startQuote . 'Request' . $db->endQuote . '.' . $db->startQuote . 'latitude' . $db->endQuote . ' * pi()/180) *  COS(' . $current_latitude . '* pi()/180) * POWER(SIN((' . $db->startQuote . 'Request' . $db->endQuote . '.' . $db->startQuote . 'longitude' . $db->endQuote . ' - ' . $current_longitude . ') * pi()/180 / 2), 2)  )) as distance';
            }
            if (!empty($search_keyword['named']['search']) && $search_keyword['named']['search'] == 'side' && !empty($search_keyword['named']['sw_longitude'])) {
                $lon1 = $search_keyword['named']['sw_longitude'];
                $lon2 = $search_keyword['named']['ne_longitude'];
                $lat1 = $search_keyword['named']['sw_latitude'];
                $lat2 = $search_keyword['named']['ne_latitude'];
                $conditions['Request.latitude BETWEEN ? AND ?'] = array(
                    $lat1,
                    $lat2
                );
                $conditions['Request.longitude BETWEEN ? AND ?'] = array(
                    $lon1,
                    $lon2
                );
            } else {
                if (!empty($search_keyword['named']['latitude']) && !empty($search_keyword['named']['longitude'])) {
                    //distance based search
                    $dist = Configure::read('site.distance_limit'); // 10 kms
                    $exact_dist = Configure::read('site.exact_distance_limit'); // 10 kms
                    $lon1 = $current_longitude-$dist/abs(cos(deg2rad($current_latitude)) *69);
                    $lon2 = $current_longitude+$dist/abs(cos(deg2rad($current_latitude)) *69);
                    $lat1 = $current_latitude-($dist/69);
                    $lat2 = $current_latitude+($dist/69);
                    //exact match finder
                    $exact_lon1 = $current_longitude-$exact_dist/abs(cos(deg2rad($current_latitude)) *69);
                    $exact_lon2 = $current_longitude+$exact_dist/abs(cos(deg2rad($current_latitude)) *69);
                    $exact_lat1 = $current_latitude-($exact_dist/69);
                    $exact_lat2 = $current_latitude+($exact_dist/69);
                    $conditions['Request.latitude BETWEEN ? AND ?'] = array(
                        $lat1,
                        $lat2
                    );
                    $conditions['Request.longitude BETWEEN ? AND ?'] = array(
                        $lon1,
                        $lon2
                    );
                    $exact_match['Request.latitude BETWEEN ? AND ?'] = array(
                        $exact_lat1,
                        $exact_lat2
                    );
                    $exact_match['Request.longitude BETWEEN ? AND ?'] = array(
                        $exact_lon1,
                        $exact_lon2
                    );
                }
            }
            if (!empty($search_keyword['named']['keyword'])) {
                $conditions['Request.title LIKE '] = '%' . $search_keyword['named']['keyword'] . '%';
            }
			$exact_match['Request.from >='] = $from;
			$exact_match['Request.to <='] = $to_cdn;
			if (!empty($additional_guest)) {
				$exact_match['Request.accommodates >='] = $additional_guest;
				$exact_match['Request.accommodates !='] = 0;
			}
            if ((isset($this->request->params['named']['is_flexible']) && !$this->request->params['named']['is_flexible']) || (isset($search_keyword['named']['is_flexible']) && !$search_keyword['named']['is_flexible'])) {
                $conditions['Request.from >='] = $from;
                $conditions['Request.to <='] = $to_cdn;
                if (!empty($additional_guest)) {
                    $conditions['Request.accommodates >='] = $additional_guest;
                    $conditions['Request.accommodates !='] = 0;
                }
            }
			$exact_ids = $this->Request->find('list', array(
				'conditions' => $exact_match,
				'fields' => array(
					'Request.id',
					'Request.id'
				),
				'recursive' => -1,
			));
			if (!empty($exact_ids)) {
				$this->set('exact_ids', array_unique($exact_ids));
			}
			$conditions['Request.from >= '] = date('Y-m-d');
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'favorite' && isPluginEnabled('RequestFavorites')) {
            if (!$this->Auth->user('id')) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $requestFavorites = $this->Request->RequestFavorite->find('all', array(
                'conditions' => array(
                    'RequestFavorite.user_id =' => $this->Auth->user('id')
                ) ,
                'fields' => array(
                    'RequestFavorite.request_id'
                ) ,
                'recursive' => -1,
            ));
            foreach($requestFavorites as $requestFavorite) {
                $request_id[] = $requestFavorite['RequestFavorite']['request_id'];
            }
            if (!empty($request_id)) {
                $conditions['Request.id'] = $request_id;
            } else {
                $conditions['Request.id'] = '';
            }
            $conditions['Request.user_id !='] = $this->Auth->user('id');
            $this->pageTitle = __l('Liked Requests');
            $this->set('is_favorite', 1);
			$user = $this->Request->User->find('first', array(
				'conditions' => array(
					'User.id' => $this->Auth->user('id')
				) ,
				'recursive' => -1,
			));
            $this->set('user', $user);
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myrequest') {
            $this->pageTitle = __l('My Requests');
            if (!$this->Auth->user('id')) {
                throw new NotFoundException(__l('Invalid request'));
            }
            unset($conditions['Request.is_approved']);
            $conditions['Request.user_id'] = $this->Auth->user('id');
            unset($conditions['Request.is_active']);
            $is_city = false;
            if (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'active') {
                $conditions['Request.is_active'] = 1;
                $conditions['Request.from >='] = date('Y-m-d');
            } elseif (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'inactive') {
                $conditions['Request.is_active'] = 0;
                $conditions['Request.from >='] = date('Y-m-d');
            } elseif (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'offered') {
                $conditions['Request.item_count >'] = 0;
                $conditions['Request.from >='] = date('Y-m-d');
            } elseif (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'past') {
                $conditions['Request.from <'] = date('Y-m-d');
            }
            $this->set('all_count', $this->Request->find('count', array(
                'conditions' => array(
                    'Request.user_id' => $this->Auth->user('id') ,
                ) ,
                'recursive' => -1
            )));
            $this->set('active_count', $this->Request->find('count', array(
                'conditions' => array(
                    'Request.user_id' => $this->Auth->user('id') ,
                    'Request.from >=' => date('Y-m-d') ,
                    'Request.is_active' => 1
                ) ,
                'recursive' => -1
            )));
            $this->set('inactive_count', $this->Request->find('count', array(
                'conditions' => array(
                    'Request.user_id' => $this->Auth->user('id') ,
                    'Request.from >=' => date('Y-m-d') ,
                    'Request.is_active' => 0
                ) ,
                'recursive' => -1
            )));
            $this->set('offered_count', $this->Request->find('count', array(
                'conditions' => array(
                    'Request.user_id' => $this->Auth->user('id') ,
                    'Request.from >=' => date('Y-m-d') ,
                    'Request.item_count >' => 0
                ) ,
                'recursive' => -1
            )));
            $this->set('past_count', $this->Request->find('count', array(
                'conditions' => array(
                    'Request.user_id' => $this->Auth->user('id') ,
                    'Request.from <' => date('Y-m-d') ,
                ) ,
                'recursive' => -1
            )));
        }
        if (!empty($this->request->params['named']['type'])) {
			if(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] != 'past'){
				$conditions['Request.from >= '] = date('Y-m-d');
			}
            if ($this->request->params['named']['type'] == 'other') {
				$conditions['Request.from >= '] = date('Y-m-d');
                if (!empty($this->request->params['named']['request_id'])) {
                    $conditions['Request.id !='] = $this->request->params['named']['request_id'];
                }
            } elseif ($this->request->params['named']['type'] == 'myrequest') {
                $conditions['Request.user_id'] = $this->Auth->user('id');
            } elseif ($this->request->params['named']['type'] == 'related') {
                if (!empty($this->request->params['named']['request_id'])) {
                    $request = $this->Request->find('first', array(
                        'conditions' => array(
                            'Request.id =' => $this->request->params['named']['request_id']
                        ) ,
                        'recursive' => -1,
                    ));
                    $conditions['Request.id != '] = $request['Request']['id'];
                    $conditions['Request.city_id'] = $request['Request']['city_id'];
                    $conditions['Request.country_id'] = $request['Request']['country_id'];
                } else {
                    $conditions['Request.city_id'] = $this->request->params['city_id'];
                    $conditions['Request.country_id'] = $this->request->params['country_id'];
                }
            }
        }
        if (empty($this->request->params['named']['type']) && empty($this->request->params['named']['user_id'])) {
            $conditions['Request.from >= '] = date('Y-m-d');
            if (!empty($this->_prefixId)) {
                if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'user') {
                    $conditions['Request.city_id'] = $this->_prefixId;
                }
            }
        }
        if (!empty($this->request->params['named']['user_id'])) {
            $conditions['Request.user_id'] = $this->request->params['named']['user_id'];
        }
        $conditions_fav = array();
        if ($this->Auth->user()) {
            $conditions_fav['RequestFavorite.user_id'] = $this->Auth->user('id');
        }
		$contain = array(
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug',
                    )
                ) ,
                'ItemsRequest' => array(
                    'Item' => array(
                        'conditions' => array(
                            'Item.is_paid' => 1,
                            'Item.is_active' => 1,
                            'Item.is_approved' => 1,
                        ) ,
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
							'Item.custom_source_id'
						),
                        'User' =>array(
							'fields' => array(
								'User.id',
								'User.username',
								'User.role_id',
								'User.facebook_user_id',
								'User.attachment_id'
							),
							'UserAvatar'
						),
                        'Attachment',
                        'Country' => array(
                            'fields' => array(
                                'Country.id',
                                'Country.iso_alpha2',
                                'Country.name',
                            )
                        ) ,
                    ) ,
					'fields' => array(
						'ItemsRequest.order_id',
						'ItemsRequest.request_id',
					),
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
                'User' => array(					
                    'fields' => array(
                        'User.id',
                        'User.username',
						'User.role_id',
                        'User.facebook_user_id',
						'User.attachment_id'
                    ),
					'UserAvatar'
                )
            );
		if(isPluginEnabled('RequestFavorites')) {
			$contain['RequestFavorite'] = array(
                    'conditions' => $conditions_fav,
                    'fields' => array(
                        'RequestFavorite.id',
                        'RequestFavorite.user_id',
                        'RequestFavorite.request_id',
                    )
                );
		}
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => $contain ,
            'fields' => array(
                'Request.id',
                'Request.created',
                'Request.title',
                'Request.slug',
                'Request.description',
                'Request.price',
                'Request.user_id',
                'Request.city_id',
                'Request.state_id',
                'Request.country_id',
                'Request.from',
                'Request.to',
                'Request.latitude',
                'Request.longitude',
                'Request.zoom_level',
                'Request.is_active',
                'Request.item_count',
                'Request.request_flag_count',
                'Request.request_view_count',
                'Request.request_favorite_count',
                'Request.is_approved',
                'Request.is_active',
                'Request.address',
                $fields
            ) ,
            'order' => array(
                'Request.from' => 'ASC',
            ) ,
            'recursive' => 3,
        );
        $requests = $this->paginate();
        if (empty($this->request->params['named']['view'])) {
            $newRequests = array();
            $fromDate = '';
            foreach($requests as $request) {
                if (empty($fromDate) || !empty($request['Request']['from'])) {
                    $fromDate = $request['Request']['from'];
                }
                $newRequests[$request['Request']['from']][] = $request;
            }
            $this->set('requests', $newRequests);
        } else {
            $this->set('requests', $requests);
        }
		$categories = $this->Request->Category->find('all', array(
            'conditions' => array(
                'Category.parent_id =' => 0,
				'Category.is_active =' => 1
            ) ,
			'order' => array(
				'Category.name' => 'ASC'
			),
            'recursive' => -1
        ));
        $this->set('categories', $categories);
        $requestCount = $this->Request->find('count', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));
        $this->set('total_result', $requestCount);
        $range_from = $range_to = array();
        for ($i = 1; $i <= 300; $i = $i+5) {
            $range_from[$i] = $range_to[$i] = $i;
        }
        $range_to['300+'] = $range_from['300+'] = '300+';
        $this->set('range_from', $range_from);
        $this->set('range_to', $range_to);
        $this->set('current_latitude', $current_latitude);
        $this->set('current_longitude', $current_longitude);
        $this->set('search_keyword', $search_keyword);
        if ($this->RequestHandler->isAjax() && env('HTTP_X_PJAX') != 'true') {
            $this->set('search', 'map');
        } else {
            $this->set('search', 'normal');
        }
        if (!isset($search_keyword['named']['range_to'])) {
            $this->request->data['Request']['range_to'] = '300+';
        }
        if ($this->Auth->user('id')) {
            $user = $this->Request->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->Auth->user('id')
                ) ,
                'recursive' => -1,
            ));
            $this->set('user', $user);
        }
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.Request.request', $this, array(
                'page' => 'search'
            ));
        }		
        if (!empty($this->request->params['named']['view'])) {
            if ($this->request->params['named']['view'] == 'compact') {
                $this->autoRender = false;
                $this->render('index_compact');
            }
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myrequest') {
            $moreActions = $this->Request->moreMyRequestsActions;
            $this->set(compact('moreActions'));
            $this->render('my_request');
        }
    }
    public function get_info($id)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $request = $this->Request->find('first', array(
            'conditions' => array(
                'Request.id = ' => $id
            ) ,
            'contain' => array(
                'Country' => array(
                    'fields' => array(
                        'Country.id',
                        'Country.name',
                        'Country.iso_alpha2',
                    )
                ) ,
            ) ,
            'recursive' => 0,
        ));
        $this->set('request', $request);
        $this->layout = 'ajax';
    }
    public function view($slug = null, $hash = null, $salt = null)
    {
        $this->pageTitle = __l('Request');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->set('distance_view', true);
        if (!empty($hash) && !empty($salt)) {
            $salt1 = hexdec($hash) +786;
            $salt1 = substr(dechex($salt1) , 0, 2);
            if ($salt1 != $salt) {
                $this->redirect(array(
                    'controller' => 'requests',
                    'action' => 'view',
                    $slug
                ));
            }
            $named_array = $this->Request->getSearchKeywords($hash, $salt);
            $this->request->params['named'] = array_merge($this->request->params['named'], $named_array);
            $is_city = false;
            if (empty($this->request->params['named']['cityname'])) {
                $this->set('distance_view', false);
            }
        }
        $conditions_fav = array();
        if ($this->Auth->user()) {
            $conditions_fav['RequestFavorite.user_id'] = $this->Auth->user('id');
        }
		$contain = array(
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug',
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
                'User'=> array(
					'UserAvatar'
				),
				'Submission' => array(
					'SubmissionField' => array(
						'RequestCloneThumb',
						'SubmissionThumb',
						'FormField'
					) ,
				),
            );
		if(isPluginEnabled('RequestFavorites')){
			$contain['RequestFavorite'] = array(
                    'conditions' => $conditions_fav,
                    'fields' => array(
                        'RequestFavorite.id',
                        'RequestFavorite.user_id',
                        'RequestFavorite.request_id',
                    )
                );
		}
        $request = $this->Request->find('first', array(
            'conditions' => array(
                'Request.slug = ' => $slug
            ) ,
            'contain' => $contain ,
            'recursive' => 3,
        ));
        if (empty($request) || (empty($request['Request']['is_active']) || !empty($request['Request']['admin_suspend']) || empty($request['Request']['is_approved'])) && ($this->Auth->user('id') != $request['Request']['user_id']) && $this->Auth->user('role_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $request['Request']['title'];
		if (isPluginEnabled('SocialMarketing')) {
			$url = Cms::dispatchEvent('Controller.SocialMarketing.getShareUrl', $this, array(
				'data' => $request['Request']['id'] ,
				'publish_action' => 'add',
				'request'=>true
			));
			$this->set('share_url', $url->data['social_url']);
		}
        //Log the request view
        $this->request->data['RequestView']['user_id'] = $this->Auth->user('id');
        $this->request->data['RequestView']['request_id'] = $request['Request']['id'];
        $this->request->data['RequestView']['ip_id'] = $this->Request->RequestView->toSaveIp();
        $this->Request->RequestView->create();
        $this->Request->RequestView->save($this->request->data);
        $this->set('request', $request);
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.Request.RequestView', $this, array(
                'request' => $request
            ));
        }		
		
    }
    public function add()
	{
		$this->pageTitle = __l('Post a Request');
		$sub_categories = array();
		$category_types = array();
		if (!empty($this->request->data)) {
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['Request'] = $this->request->data;
				$this->request->data['State']['name'] = $this->request->data['Request']['state_name'];
				$this->request->data['City']['name'] = $this->request->data['Request']['city_name'];
			}
			$request_filter = 0;
			$conditions = array();
			$this->request->data['Request']['user_id'] = !empty($this->request->data['Request']['user_id']) ? $this->request->data['Request']['user_id'] : $this->Auth->user('id');
			$this->request->data['Request']['is_active'] = 0;
			
			//state and country looking
			
			if (!empty($this->request->data['Request']['country_id'])) {
				$this->request->data['Request']['country_id'] = $this->Request->Country->findCountryId($this->request->data['Request']['country_id']);
				$conditions['Item.country_id'] = $this->request->data['Request']['country_id'];
			}
			if (!empty($this->request->data['State']['name'])) {
				$this->request->data['Request']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Request->State->findOrSaveAndGetId($this->request->data['State']['name'], $this->request->data['Request']);
			}
			if (!empty($this->request->data['City']['name'])) {
				$conditions['Item.city_id'] = $this->request->data['Request']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Request->City->findOrSaveAndGetId($this->request->data['City']['name'], $this->request->data['Request']);
			}
			$conditions['Item.is_paid'] = 1;
			$conditions['Item.is_active'] = 1;
			$conditions['Item.is_approved'] = 1;
			$this->Request->create();
			$this->Request->set($this->request->data);
			if ($this->Request->validates($this->request->data)) {
				if (!empty($this->request->data['Request']['sub_category_id'])) {
                    $conditions['Item.category_id'] = $this->request->data['Request']['sub_category_id'];
                }
				$request_count = false;
				if(isPluginEnabled('Items')){
					App::import('Model', 'Items.Item');
					$this->Item = new Item();
					$request_count = $this->Item->find('count', array(
						'conditions' => $conditions,
						'recursive' => -1,
					));
				}
                if ($request_count && $this->request->data['Request']['steps'] == 1) {
                    $request_filter = '1';
                    $this->set('request_filters', $request_filter);
                    $this->set('steps', 2);	
					$message = array('message' => __l('Related Items available'), 'error' => 0, 'steps' => 2, 'data' => $this->request->data['Request']);
                } else {
					$this->request->data['Request']['category_id'] = $this->request->data['Request']['sub_category_id'];
					$is_auto_approve = Configure::read('request.is_auto_approve');
                    $this->request->data['Request']['is_approved'] = empty($is_auto_approve) ? 1 : 0;
					$this->Request->save($this->request->data);
					$request_id = $this->Request->getLastInsertId();
					
					// saving in user
					$data = array();
					$data['User']['id'] = $_SESSION['Auth']['User']['id'];
					$data['User']['is_idle'] = 0;
					$data['User']['is_requested'] = 1;
					$this->Request->User->set($data);
					$this->Request->User->save($data);
					
					//Save Dynamic form fields
					$this->loadModel('Items.Submission');
					if (!empty($this->request->data['Form'])) {
						$this->request->data['Submission'] = $this->request->data['Form'];
						$this->request->data['Submission']['request_id'] = $request_id;
						$submission = $this->Submission->find('first', array(
							'conditions' => array(
								'Submission.request_id' => $request_id
							) ,
							'recursive' => -1
						));
						if (!empty($submission)) {
							$this->request->data['Submission']['id'] = $submission['Submission']['id'];
						}
						$this->Submission->submit($this->request->data);
					}
					
					if (!Configure::read('request.is_auto_approve')) {
						if ($this->RequestHandler->prefers('json')) {
							$message = array('message' =>__l('Request has been listed.'), 'error' => 0);
						}else{
							$this->Session->setFlash(__l('Request has been listed.') , 'default', null, 'success');
							if ($this->Auth->user('role_id') == ConstUserTypes::Admin) {
								$this->redirect(array(
									'action' => 'index',
								));
							} else {
								$this->redirect(array(
									'controller' => 'requests',
									'action' => 'index',
									'type' => 'myrequest',
									'admin' => false
								));
							}
						}
					} else {
						if ($this->RequestHandler->prefers('json')) {
							$message = array('message' =>__l('Request has been added and it will be listed after admin approve.'), 'error' => 0);
						}else{
							$this->Session->setFlash(__l('Request has been added and it will be listed after admin approve.') , 'default', null, 'success');
							if ($this->Auth->user('role_id') == ConstUserTypes::Admin) {
								$this->redirect(array(
									'action' => 'index',
								));
							} else {
								$this->redirect(array(
									'controller' => 'requests',
									'action' => 'index',
									'type' => 'myrequest',
									'admin' => false
								));
							}
						}
					}
				}
			}  else {
				$this->Session->setFlash(__l('Request could not be added. Please, try again.') , 'default', null, 'error');
				$this->set('steps', 1);
				$message = array('message' => __l('Request could not be added. Please, try again.'), 'error' => 1, 'steps' => 1);
			}
			if ($this->RequestHandler->prefers('json')) {
				Cms::dispatchEvent('Controller.Request.RequestAdd', $this, array(
					'message' => $message,
				));
			}			
		}
		if(!empty($this->request->data['Request']['category_id'])) {
			$sub_categories = $this->Request->Category->find('list', array(
				'conditions' => array(
					'Category.parent_id =' => $this->request->data['Request']['category_id'],
					'Category.is_active' => 1
				) ,
				'recursive' => -1
			));
			$category_types = $this->Request->CategoryType->find('list', array(
				'conditions' => array(
					'CategoryType.category_id' => $this->request->data['Request']['category_id']
				) ,
				'recursive' => -1
			));
			$category = $this->Request->Category->find('first', array(
				'conditions' => array(
					'Category.id' => $this->request->data['Request']['sub_category_id']
				) ,
				'recursive' => -1
			));
			if (empty($category)) {
				throw new NotFoundException(__l('Invalid request'));
			}
			$this->loadModel('Items.Form');
			$this->loadModel('Items.FormField');
			$categoryFormFields = $this->Form->buildSchema($category['Category']['id']);
			$this->loadModel('Items.FormFieldStep');
			$FormFieldSteps = $this->FormFieldStep->find('all', array(
				'conditions' => array(
					'FormFieldStep.category_id' => $category['Category']['id'],
					'FormFieldStep.is_show_in_request_form' => 1
				) ,
				'contain' => array(
					'FormFieldGroup' => array(
						'conditions' => array(
							'FormFieldGroup.is_show_in_request_form' => 1
						),
						'FormField' => array(
							'conditions' => array(
								'FormField.is_active' => 1,
								'FormField.is_show_in_request_form' => 1
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
			$this->set('model', 'Request');
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
				$this->request->data['Form']['form_field_step'] = $this->request->data['Form']['form_field_step'] + 1;
			}
			// form field steps
			if (!empty($form_field_step)) {
				$this->request->data['Form']['form_field_step'] = $form_field_step;
				$this->request->data['Form']['step'] = 2;
			}
		}
		$categories = $this->Request->Category->find('list', array(
			'conditions' => array(
				'Category.parent_id =' => 0,
				'Category.is_active =' => 1
			) ,
			'recursive' => -1
		));
		$this->set(compact('categories', 'sub_categories', 'category_types'));
		if (empty($this->request->data['Request']['from']) && empty($this->request->data['Request']['to'])) {
            $this->request->data['Request']['from'] = date('Y-m-d');
            $this->request->data['Request']['to'] = getToDate(date('Y-m-d'));
			$this->set('steps', 1);
        }
	}
    public function old_add($hash_keyword = '', $salt = '')
    {
        $this->pageTitle = __l('Post a Request');
        if (!empty($hash_keyword) && !empty($salt)) {
			$named_array = array();
			if(isPluginEnabled('Items')){
				App::import('Model', 'Items.Item');
				$this->Item = new Item();
				$named_array = $this->Item->getSearchKeywords($hash_keyword, $salt);
			}
            $this->request->params['named'] = array_merge($this->request->params['named'], $named_array);
            if (empty($this->request->data['Request'])) {
                $this->request->data['Request']['address'] = $named_array['cityname'];
                $this->request->data['Request']['latitude'] = $named_array['latitude'];
                $this->request->data['Request']['longitude'] = $named_array['longitude'];
            }
            if (!empty($this->request->data['Request']['step1'])) {
                $this->request->data['Request']['from'] = $named_array['from'];
                $this->request->data['Request']['to'] = $named_array['to'];
                $this->request->data['Request']['accommodates'] = $named_array['additional_guest'];
            }
            if (!empty($named_array['sw_latitude'])) {
                $this->set('is_country_search', 1);
            }
            $this->set('hash_keyword', $hash_keyword);
            $this->set('salt', $salt);
        }
        if (!empty($this->request->data['Request']['step1'])) {
            if (!empty($this->request->data['Request']['address'])) {
                $this->set('steps', 2);
				Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
					'_trackEvent' => array(
						'category' => 'Request',
						'action' => 'RequestPosted',
						'label' => 'Address',
						'value' => '',
					) ,
					'_setCustomVar' => array(
						'ud' => $this->Auth->user('id'),
						'rud' => $this->Auth->user('referred_by_user_id'),
					)
				));
            } else {
                $this->Request->validationErrors['address'] = __l('Please select proper address');
                $this->set('steps', 1);
            }
        } else if (!empty($this->request->data['Request']['step2'])) {
            $this->Request->set($this->request->data);
            if ($this->Request->validates($this->request->data)) {
                $this->set('steps', 3);
				Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
					'_trackEvent' => array(
						'category' => 'Request',
						'action' => 'RequestPosted',
						'label' => 'General',
						'value' => '',
					) ,
					'_setCustomVar' => array(
						'ud' => $this->Auth->user('id'),
						'rud' => $this->Auth->user('referred_by_user_id'),
					)
				));
            } else {
                $this->Session->setFlash(__l('Request could not be added. Please, try again') , 'default', null, 'error');
                $this->set('steps', 2);
            }
        } else if (!empty($this->request->data['Request']['step3'])) {
            if (empty($this->request->data['Request']['post']) && $this->Request->validates()) {
                $request_filter = 0;
                $conditions = array();
                if (!empty($this->request->data['City']['name'])) {
                    $this->request->data['Request']['city_id'] = $conditions['Item.city_id'] = $this->Request->City->findOrSaveAndGetId($this->request->data['City']['name'], $this->request->data['Request']);
                }
                if (!empty($this->request->data['State']['name'])) {
                    $this->request->data['Request']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Request->State->findOrSaveAndGetId($this->request->data['State']['name'], $this->request->data['Request']);
                }
                if (!empty($this->request->data['Request']['country_id'])) {
                    $this->request->data['Request']['country_id'] = $this->Request->Country->findCountryId($this->request->data['Request']['country_id']);
                    $conditions['Item.country_id'] = $this->request->data['Request']['country_id'];
                }
                $conditions['Item.is_paid'] = 1;
                $conditions['Item.is_active'] = 1;
                $conditions['Item.is_approved'] = 1;
				$request_count = false;
				if(isPluginEnabled('Items')){
					App::import('Model', 'Items.Item');
					$this->Item = new Item();
					$request_count = $this->Item->find('count', array(
						'conditions' => $conditions,
						'recursive' => -1,
					));
				}
                if ($request_count) {
                    $request_filter = '1';
                    $this->set('request_filters', $request_filter);
                    $this->set('steps', 4);
                } else {
					$is_auto_approve = Configure::read('request.is_auto_approve');
                    $this->request->data['Request']['is_approved'] = empty($is_auto_approve) ? 1 : 0;
                    $this->request->data['Request']['user_id'] = $this->Auth->user('id');
                    $this->Request->set($this->request->data);
                    $this->Request->City->set($this->request->data);
                    $this->Request->State->set($this->request->data);
                    if ($this->Request->validates() &$this->Request->City->validates() &$this->Request->State->validates()) {
                        $this->Request->save($this->request->data, false);
						// saving in user
						$data = array();
						$data['User']['id'] = $_SESSION['Auth']['User']['id'];
						$data['User']['is_idle'] = 0;
						$data['User']['is_requested'] = 1;
						$this->Request->User->set($data);
						$this->Request->User->save($data); 						
						Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
							'_trackEvent' => array(
								'category' => 'Request',
								'action' => 'RequestPosted',
								'label' => 'Requests',
								'value' => '',
							) ,
							'_setCustomVar' => array(
								'ud' => $this->Auth->user('id'),
								'rud' => $this->Auth->user('referred_by_user_id'),
							)
						));
						Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
							'_trackEvent' => array(
								'category' => 'User',
								'action' => 'Request Created',
								'label' => $this->Auth->user('username'),
								'value' => $this->Auth->user('id'),
							) ,
							'_setCustomVar' => array(
								'ud' => $this->Auth->user('id'),
								'rud' => $this->Auth->user('referred_by_user_id'),
							)
						));
						Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
							'_trackEvent' => array(
								'category' => 'Request',
								'action' => 'Created',
								'label' => $this->request->data['Request']['title'],
								'value' => $this->Request->getLastInsertId(),
							) ,
							'_setCustomVar' => array(
								'ud' => $this->Auth->user('id'),
								'rud' => $this->Auth->user('referred_by_user_id'),
							)
						));
                        if (Configure::read('request.is_auto_approve')) {
                            $this->Session->setFlash(__l('Request has been listed.') , 'default', null, 'success');
                            // update into social networking site
                           if (isPluginEnabled('SocialMarketing')) {
								Cms::dispatchEvent('Controller.SocialMarketing.redirectToShareUrl', $this, array(
									'data' => $this->Request->getLastInsertId() ,
									'publish_action' => 'add',
									'request'=>1
								));
							}
							else {
								$this->redirect(array(
									'controller' => 'requests',
									'action' => 'index',
									'type' => 'myrequest',
									'admin' => false
								));
							}
                        } else {
                            $this->Session->setFlash(__l('Request has been added and it will be listed after admin approve.') , 'default', null, 'success');
                            if ($this->Auth->user('role_id') == ConstUserTypes::Admin) {
                                $this->redirect(array(
                                    'action' => 'index',
                                ));
                            } else {
                                $this->redirect(array(
                                    'controller' => 'requests',
                                    'action' => 'index',
                                    'type' => 'myrequest',
                                    'admin' => false
                                ));
                            }
                        }
                    } else {
                        $this->Session->setFlash(__l('Request could not be added. Please, try again.') , 'default', null, 'error');
                        $this->set('steps', 3);
                    }
                }
            }
        } else if (!empty($this->request->data['Request']['step4'])) {
            //state and country looking
            if (!empty($this->request->data['City']['name'])) {
                $this->request->data['Request']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Request->City->findOrSaveAndGetId($this->request->data['City']['name'], $this->request->data['Request']);
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Request']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Request->State->findOrSaveAndGetId($this->request->data['State']['name'], $this->request->data['Request']);
            }
            if (!empty($this->request->data['Request']['country_id'])) {
                $this->request->data['Request']['country_id'] = $this->Request->Country->findCountryId($this->request->data['Request']['country_id']);
                $conditions['Item.country_id'] = $this->request->data['Request']['country_id'];
            }
            $this->Request->create();
            $is_auto_approve = Configure::read('request.is_auto_approve');
            $this->request->data['Request']['is_approved'] = empty($is_auto_approve) ? 1 : 0;
            $this->request->data['Request']['user_id'] = $this->Auth->user('id');
            $this->Request->set($this->request->data);
            $this->Request->City->set($this->request->data);
            $this->Request->State->set($this->request->data);
            if ($this->Request->validates() &$this->Request->City->validates() &$this->Request->State->validates()) {
                $this->Request->save($this->request->data, false);
				Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
					'_trackEvent' => array(
						'category' => 'Request',
						'action' => 'RequestPosted',
						'label' => 'Related Items',
						'value' => '',
					) ,
					'_setCustomVar' => array(
						'ud' => $this->Auth->user('id'),
						'rud' => $this->Auth->user('referred_by_user_id'),
					)
				));
				Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
					'_trackEvent' => array(
						'category' => 'User',
						'action' => 'Request Created',
						'label' => $this->Auth->user('username'),
						'value' => $this->Auth->user('id'),
					) ,
					'_setCustomVar' => array(
						'ud' => $this->Auth->user('id'),
						'rud' => $this->Auth->user('referred_by_user_id'),
					)
				));
				Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
					'_trackEvent' => array(
						'category' => 'Request',
						'action' => 'Created',
						'label' => $this->request->data['Request']['title'],
						'value' => $this->Request->getLastInsertId(),
					) ,
					'_setCustomVar' => array(
						'ud' => $this->Auth->user('id'),
						'rud' => $this->Auth->user('referred_by_user_id'),
					)
				));
                if (Configure::read('request.is_auto_approve') == 0) {
                    $this->Session->setFlash(__l('Request has been listed.') , 'default', null, 'success');
                    // update into social networking site
                   if (isPluginEnabled('SocialMarketing')) {
						Cms::dispatchEvent('Controller.SocialMarketing.redirectToShareUrl', $this, array(
							'data' => $this->Request->getLastInsertId() ,
							'publish_action' => 'add',
							'request'=>1
						));
					}
					else {
                        $this->redirect(array(
                            'controller' => 'requests',
                            'action' => 'index',
                            'type' => 'myrequest',
                            'admin' => false
                        ));
                    }
                } else {
                    $this->Session->setFlash(__l('Request has been added and it will be listed after admin approve.') , 'default', null, 'success');
                    if ($this->Auth->user('role_id') == ConstUserTypes::Admin) {
                        $this->redirect(array(
                            'action' => 'index',
                        ));
                    } else {
                        $this->redirect(array(
                            'controller' => 'requests',
                            'action' => 'index',
                            'type' => 'myrequest',
                            'admin' => false
                        ));
                    }
                }
            } else {
                $this->Session->setFlash(__l('Request could not be added. Please, try again.') , 'default', null, 'error');
                $this->set('steps', 4);
            }
        } else if (!empty($this->request->data['Request']['back_step_1'])) {
            $this->set('steps', 1);
        } else if (!empty($this->request->data['Request']['back_step_2'])) {
            $this->set('steps', 2);
        } else if (!empty($this->request->data['Request']['back_step_3'])) {
            $this->set('steps', 3);
        } else if (!empty($this->request->data['Request']['back_step_4'])) {
            $this->set('steps', 4);
        } else {
            $this->set('steps', 1);
        }
        if (!empty($this->request->data['Request']['address'])) {
            $search_keyword['named']['latitude'] = $this->request->data['Request']['latitude'];
            $search_keyword['named']['longitude'] = $this->request->data['Request']['longitude'];
            $search_keyword['named']['address'] = $this->request->data['Request']['address'];
            $this->set('search_keyword', $search_keyword);
        }
        $preferences = array();
        $preferences['500m'] = '500m';
        $preferences['1km'] = '1km';
        $preferences['2km'] = '2km';
        $preferences['3km'] = '3km';
        $preferences['5km'] = '5km';
        $preferences['10km'] = '10km';
        $preferences['50km'] = '50km';
        $accomadation = array();
        for ($i = 1; $i <= 20; $i++) {
            $accomadation[$i] = $i;
        }
        $morning = array();
        $noon = array();
        for ($i = 1; $i <= 12; $i++) {
            if ($i == 12) {
                $noon[] = $i . '.00 pm (noon)';
                $morning[] = $i . '.00 am (midnight)';
            } else {
                $morning[] = $i . '.00 am';
                $noon[] = $i . '.00 pm';
            }
        }
        $from = array_merge($morning, $noon);
        $this->set('from', $from);
        $this->set('accomadation', $accomadation);
        $users = $this->Request->User->find('list');
        $cities = $this->Request->City->find('list');
        $states = $this->Request->State->find('list');
        $countries = $this->Request->Country->find('list', array(
            'fields' => array(
                'Country.iso_alpha2',
                'Country.name'
            ) ,
            'order' => array(
                'Country.name' => 'ASC'
            ),
			'recursive' => -1,
        ));
        $this->set(compact('affiliates', 'users', 'cities', 'states', 'countries', 'preferences'));
        if (empty($this->request->data['Request']['from']) && empty($this->request->data['Request']['to'])) {
            $this->request->data['Request']['from'] = date('Y-m-d');
            $this->request->data['Request']['to'] = getToDate(date('Y-m-d'));
        }
    }
    public function edit($id = null)
    {
        $this->pageTitle = __l('Edit Request');
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
			//todo: swagger api call need to fix
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['Request'] = $this->request->data;
				$this->request->data['State']['name'] = $this->request->data['Request']['state_name'];
				$this->request->data['City']['name'] = $this->request->data['Request']['city_name'];
			}
            if (!empty($this->request->data['City']['name'])) {
                $this->request->data['Request']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Request->City->findOrSaveAndGetId($this->request->data['City']['name'], $this->request->data['Request']);
            }
            if (!empty($this->request->data['State']['name'])) {
                $this->request->data['Request']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->Request->State->findOrSaveAndGetId($this->request->data['State']['name'], $this->request->data['Request']);
            }
            $this->Request->set($this->request->data);
            $this->Request->City->set($this->request->data);
            $this->Request->State->set($this->request->data);
            if ($this->Request->validates() &$this->Request->City->validates() &$this->Request->State->validates()) {
                if ($this->Request->save($this->request->data, false)) {
                    $this->Session->setFlash(__l('Request has been updated') , 'default', null, 'success');
					$this->set('iphone_response', array("message" => __l('Request has been updated'), "error" => 1));
					if (!$this->RequestHandler->prefers('json')) {
						$this->redirect(array(
							'controller' => 'requests',
							'action' => 'index',
							'type' => 'myrequest'
						));
					}
                } else {
					$this->set('iphone_response', array("message" => __l('Request could not be updated. Please, try again.'), "error" => 1));
                    $this->Session->setFlash(__l('Request could not be updated. Please, try again.') , 'default', null, 'error');
                }
            } else {
				$this->set('iphone_response', array("message" => __l('Request could not be updated. Please, try again.'), "error" => 1));
                $this->Session->setFlash(__l('Request could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Request->read(null, $id);
            if (empty($this->request->data)) {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
            }
			$request = $this->request->data;
			$this->set('request',$request);
            $this->request->data['Request']['to'] = getToDate($this->request->data['Request']['to']);
        }
        $preferences = array();
        $preferences['500m'] = '500m';
        $preferences['1km'] = '1km';
        $preferences['2km'] = '2km';
        $preferences['3km'] = '3km';
        $preferences['5km'] = '5km';
        $preferences['10km'] = '10km';
        $preferences['50km'] = '50km';
        $accomadation = array();
        for ($i = 1; $i <= 20; $i++) {
            $accomadation[$i] = $i;
        }
        $morning = array();
        $noon = array();
        for ($i = 1; $i <= 12; $i++) {
            if ($i == 12) {
                $noon[] = $i . '.00 pm (noon)';
                $morning[] = $i . '.00 am (midnight)';
            } else {
                $morning[] = $i . '.00 am';
                $noon[] = $i . '.00 pm';
            }
        }
        $from = array_merge($morning, $noon);
        $this->set('from', $from);
        $this->set('accomadation', $accomadation);
        $users = $this->Request->User->find('list');
        $cities = $this->Request->City->find('list');
        $states = $this->Request->State->find('list');
        $countries = $this->Request->Country->find('list', array(
            'fields' => array(
                'Country.iso_alpha2',
                'Country.name'
            ) ,
            'order' => array(
                'Country.name' => 'ASC'
            ),
			'recursive' => -1,
        ));
        $this->set(compact('users', 'cities', 'states', 'countries', 'preferences'));
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && $this->request->is('post')) {
			$response = Cms::dispatchEvent('Controller.Request.Edit', $this, array());
		} else if ($this->RequestHandler->prefers('json') && $this->request->is('get')) {
				Cms::dispatchEvent('Controller.Request.GetEdit', $this, array(
					'request' => $request
				));
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
			if ($this->Request->delete($id)) {
				$this->set('iphone_response', array("message" =>__l('Request deleted'), "error" => 0));
				$this->Session->setFlash(__l('Request deleted') , 'default', null, 'success');
				if (!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
						'action' => 'index',
						'type' => 'myrequest'
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
    public function update_view_count()
    {
        if (!empty($_POST['ids'])) {
            $ids = explode(',', $_POST['ids']);
            $requests = $this->Request->find('all', array(
                'conditions' => array(
                    'Request.id' => $ids
                ) ,
                'fields' => array(
                    'Request.id',
                    'Request.request_view_count'
                ) ,
                'recursive' => -1
            ));
			$json_arr = array();
			if (!empty($requests)) {
				foreach($requests as $request) {
					$request['Request']['request_view_count'] = !empty($request['Request']['request_view_count']) ? $request['Request']['request_view_count'] : 0;
					$json_arr[$request['Request']['id']] = numbers_to_higher($request['Request']['request_view_count']);
				}
			}
            $this->view = 'Json';
            $this->set('json', $json_arr);
        }
    }
    public function admin_index()
    {
		$this->pageTitle = __l('Requests');
        $conditions = array();
        $this->_redirectGET2Named(array(
            'q',
            'username',
        ));
        $this->set('active_requests', $this->Request->find('count', array(
            'conditions' => array(
                'Request.is_active = ' => 1,
                'Request.admin_suspend = ' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('suspended_requests', $this->Request->find('count', array(
            'conditions' => array(
                'Request.admin_suspend = ' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('user_suspended_requests', $this->Request->find('count', array(
            'conditions' => array(
                'Request.is_active = ' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->Request->find('count', array(
            'conditions' => array(
                'Request.is_system_flagged = ' => 1,
                'Request.admin_suspend = ' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('user_flagged', $this->Request->find('count', array(
            'conditions' => array(
                'Request.is_user_flagged = ' => 1,
                'Request.admin_suspend = ' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('total_requests', $this->Request->find('count', array(
            'conditions' => array(
                'Request.admin_suspend = ' => 0,
            ) ,
            'recursive' => -1
        )));
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['Request']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data['Request']['filter_id'])) {
            if ($this->request->data['Request']['filter_id'] == ConstMoreAction::Approved) {
                $conditions['Request.is_approved'] = 1;
                $this->pageTitle.= ' - '.__l('Approved');
            } else if ($this->request->data['Request']['filter_id'] == ConstMoreAction::Disapproved) {
                $conditions['Request.is_approved'] = 0;
                $this->pageTitle.= ' - '.__l('Disapproved');
            } else if ($this->request->data['Request']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Request.is_active'] = 1;
                $conditions['Request.admin_suspend'] = 0;
                $this->pageTitle.= ' - '.__l('Active ');
            } else if ($this->request->data['Request']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Request.is_active'] = 0;
                $this->pageTitle.= ' - '.__l('User suspended ');
            } else if ($this->request->data['Request']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Request.admin_suspend'] = 1;
                $this->pageTitle.= ' - '.__l('Suspended ');
            } else if ($this->request->data['Request']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Request.is_system_flagged'] = 1;
                $conditions['Request.admin_suspend'] = 0;
                $this->pageTitle.= ' - '.__l('Flagged');
            } else if ($this->request->data['Request']['filter_id'] == ConstMoreAction::UserFlagged) {
                $conditions['Request.request_flag_count !='] = 0;
                $conditions['Request.admin_suspend'] = 0;
                $this->pageTitle.= ' - '.__l('User Flagged');
            }
            $this->request->params['named']['filter_id'] = $this->request->data['Request']['filter_id'];
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Request.is_active'] = 1;
                $conditions['Request.admin_suspend'] = 0;
                $this->pageTitle.= ' - '.__l('Active ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Request.is_active'] = 0;
                $this->pageTitle.= ' - '.__l('User suspended ');
            }
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['Request.created ='] = date('Y-m-d', strtotime('now'));
            $this->pageTitle.= ' - '.__l('today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['Request.created >='] = date('Y-m-d', strtotime('now -7 days'));
            $this->pageTitle.= ' - '.__l('in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['Request.created >='] = date('Y-m-d', strtotime('now -30 days'));
            $this->pageTitle.= ' - '.__l('in this month');
        }
        if (isset($this->request->params['named']['item_category_id'])) {
            $this->request->data['Request']['item_category_id'] = $this->request->params['named']['item_category_id'];
            $conditions['Request.item_category_id'] = $this->request->params['named']['item_category_id'];
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
			$conditions['AND']['OR'][]['Request.title LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['User.username LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
			$this->request->data['Request']['q'] = $this->request->params['named']['q'];
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'user-flag') {
            $conditions['Request.is_user_flagged'] = 1;
			$conditions['Request.admin_suspend'] = 0;
            $this->pageTitle.= ' - '.__l('User Flagged');
        }

        $this->Request->recursive = 2;
		$contain_flag = array();
		$contain = array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.is_active'
                    )
                ) ,
                'Country' => array(
                    'fields' => array(
                        'Country.name',
                        'Country.iso_alpha2'
                    )
                ) ,
				'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug',
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
            );		
        if(isPluginEnabled('RequestFlags')){
			$contain['RequestFlag']=array(
						'fields' => array(
							'RequestFlag.id',
							'RequestFlag.user_id',
							'RequestFlag.request_id'
						)
					);
		}
		$this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'Request.id',
                'Request.created',
                'Request.user_id',
                'Request.title',
                'Request.from',
                'Request.to',
                'Request.slug',
                'Request.price',
                'Request.is_approved',
                'Request.item_count',
                'Request.request_view_count',
                'Request.request_flag_count',
                'Request.is_active',
                'Request.address',
                'Request.admin_suspend',
                'Request.accommodates',
                'Request.detected_suspicious_words',
                'Request.is_system_flagged',
				'Request.is_user_flagged',
                'Request.request_favorite_count',
            ) ,
            'contain' => $contain ,
            'order' => array(
                'Request.id' => 'desc'
            )
        );
        if (isset($this->request->data['Request']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['Request']['q']
            ));
        }
        $moreActions = $this->Request->moreActions;
        $this->set(compact('moreActions'));
        $this->set('requests', $this->paginate());
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
        if ($this->Request->delete($id)) {
            $this->Session->setFlash(__l('Request deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
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
                    $this->Session->setFlash(__l('Checked request has been disabled') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->{$this->modelClass}->updateAll(array(
                        $this->modelClass . '.is_active' => 1
                    ) , array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked request has been enabled') , 'default', null, 'success');
                } elseif ($actionid == ConstMoreAction::Delete) {
                    $this->{$this->modelClass}->deleteAll(array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked request has been deleted') , 'default', null, 'success');
                }
            }
        }
        if (!$this->RequestHandler->isAjax()) {
            $this->redirect(Router::url('/', true) . $r);
        } else {
            $this->redirect($r);
        }
    }
    public function sample_data()
    {
        set_time_limit(0);
        Configure::write('debug', 1);
        $dummyData = $this->Request->query('SELECT * FROM tmp_dummy_data LIMIT 0, 1');
        $title = array(
            'Need beach house near ',
            'Need rooms for rent near ',
            'Need luxury beach house near ',
            'Need house near to beach near ',
            'Need guest house near ',
            'Need luxurious place to live near ',
            'Need fully furnished house near ',
            'Need luxurious apartment near ',
        );
        $country_arr = array(
            43,
            254,
            253,
            113,
            14
        );
        $image = $country = 0;
        $escape_city = array();
        $from = 0;
        foreach($dummyData as $dummy) {
            if ($dummy['tmp_dummy_data']['id'] == 101 || $dummy['tmp_dummy_data']['id'] == 201 || $dummy['tmp_dummy_data']['id'] == 301 || $dummy['tmp_dummy_data']['id'] == 401) {
                $country++;
                $escape_city = array();
            }
            $tmp_country = $this->Request->query('SELECT * FROM tmp_countries WHERE id = ' . $country_arr[$country]);
            $escape_not_in_city = '';
            if (!empty($escape_city)) {
                $escape_not_in_city = ' AND id NOT IN (' . implode(',', $escape_city) . ')';
            }
            $tmp_city = $this->Request->query('SELECT * FROM tmp_cities WHERE country_id = ' . $country_arr[$country] . $escape_not_in_city . ' LIMIT 0, 1');
            $escape_city[] = $tmp_city[0]['tmp_cities']['id'];
            $tmp_state = $this->Request->query('SELECT * FROM tmp_states WHERE id = ' . $tmp_city[0]['tmp_cities']['state_id']);
            $_data['Request']['user_id'] = mt_rand(2, 10);
            $_data['Request']['city_id'] = $tmp_city[0]['tmp_cities']['id'];
            $_data['Request']['state_id'] = $tmp_city[0]['tmp_cities']['state_id'];
            $_data['Request']['country_id'] = $country_arr[$country];
            $_data['Request']['title'] = $title[mt_rand(0, 7) ] . $tmp_city[0]['tmp_cities']['name'];
            $_data['Request']['description'] = str_replace('.', '', $dummy['tmp_dummy_data']['description']);
            $_data['Request']['accommodates'] = mt_rand(1, 16);
            $_data['Request']['address'] = $dummy['tmp_dummy_data']['address'] . ', ' . $tmp_city[0]['tmp_cities']['name'] . ', ' . $tmp_state[0]['tmp_states']['name'] . ', ' . $tmp_country[0]['tmp_countries']['title'];
            $_data['Request']['phone'] = $dummy['tmp_dummy_data']['phone'];
            $_data['Request']['ip_id'] = 2;
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
            $_data['Request']['price'] = $price_arr[mt_rand(0, 17) ];
            $prev_from = $last_from = !empty($last_from) ? $last_from : 'now';
            if ($last_from == $prev_from) {
                if ($from == 3) {
                    $from = 0;
                    $_data['Request']['from'] = date('Y-m-d', strtotime($last_from . ' +' . mt_rand(2, 10) . ' days'));
                    $last_from = $_data['Request']['from'];
                } else {
                    $_data['Request']['from'] = date('Y-m-d', strtotime($last_from));
                    $from++;
                }
            }
            $_data['Request']['to'] = date('Y-m-d', strtotime($_data['Request']['from'] . ' +' . mt_rand(2, 10) . ' days'));
            $_data['Request']['latitude'] = $tmp_city[0]['tmp_cities']['latitude'];
            $_data['Request']['longitude'] = $tmp_city[0]['tmp_cities']['longitude'];
            $_data['Request']['zoom_level'] = 10;
            $_data['Request']['is_active'] = ($dummy['tmp_dummy_data']['id']%50 == 0) ? 0 : 1;
            $_data['Request']['is_approved'] = ($dummy['tmp_dummy_data']['id']%50 == 0) ? 0 : 1;
            $_data['Request']['id'] = '';
            $this->Request->create();
            $this->Request->save($_data);
            $_data = array();
        }
        exit;
    }
	public function show_admin_control_panel()
	{
		$this->disableCache();
		if (!empty($this->request->params['named']['view_type']) && $this->request->params['named']['view_type'] == 'request') {
			$request = $this->Request->find('first', array(
				'conditions' => array(
					'Request.id' => $this->request->params['named']['id']
				) ,
				'recursive' => 0
			));
			$this->set('request', $request);
		}
		$this->layout = 'ajax';
	}
}
?>