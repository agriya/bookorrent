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
class ChartsController extends AppController
{
    public $name = 'Charts';
    public $lastDays;
    public $lastMonths;
    public $lastYears;
    public $lastWeeks;
    public $selectRanges;
    public $lastDaysStartDate;
    public $lastMonthsStartDate;
    public $lastYearsStartDate;
    public $lastWeeksStartDate;
    public $lastDaysPrev;
    public $lastWeeksPrev;
    public $lastMonthsPrev;
    public $lastYearsPrev;
    public function initChart() 
    {
        //# last days date settings
        $days = 6;
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
                '#MODEL#.created >=' => date('Y-m-d 00:00:00', strtotime("now")) ,
                '#MODEL#.created <=' => date('Y-m-d 23:59:59', strtotime("now"))
            )
        );
        $days = 13;
        for ($i = $days; $i >= 7; $i--) {
            $this->lastDaysPrev[] = array(
                'display' => date('M d, Y', strtotime("-$i days")) ,
                'conditions' => array(
                    '#MODEL#.created >=' => date('Y-m-d 00:00:00', strtotime("-$i days")) ,
                    '#MODEL#.created <=' => date('Y-m-d 23:59:59', strtotime("-$i days"))
                )
            );
        }
        //# last weeks date settings
        $timestamp_end = strtotime('last Saturday');
        $weeks = 3;
        $this->lastWeeksStartDate = date('Y-m-d', $timestamp_end-((($weeks*7) -1) *24*3600));
        for ($i = $weeks; $i > 0; $i--) {
            $start = $timestamp_end-((($i*7) -1) *24*3600);
            $end = $start+(6*24*3600);
            $this->lastWeeks[] = array(
                'display' => date('M d', $start) . ' - ' . date('M d', $end) ,
                'conditions' => array(
                    '#MODEL#.created >=' => date('Y-m-d', $start) ,
                    '#MODEL#.created <=' => date('Y-m-d', $end) ,
                )
            );
        }
        $this->lastWeeks[] = array(
            'display' => date('M d', $timestamp_end+24*3600) . ' - ' . date('M d') ,
            'conditions' => array(
                '#MODEL#.created >=' => date('Y-m-d', $timestamp_end+24*3600) ,
                '#MODEL#.created <=' => date('Y-m-d', strtotime('now'))
            )
        );
        $weeks = 7;
        for ($i = $weeks; $i > 3; $i--) {
            $start = $timestamp_end-((($i*7) -1) *24*3600);
            $end = $start+(6*24*3600);
            $this->lastWeeksPrev[] = array(
                'display' => date('M d', $start) . ' - ' . date('M d', $end) ,
                'conditions' => array(
                    '#MODEL#.created >=' => date('Y-m-d', $start) ,
                    '#MODEL#.created <=' => date('Y-m-d', $end)
                )
            );
        }
        //# last months date settings
        $months = 2;
        $this->lastMonthsStartDate = date('Y-m-01', strtotime("-$months months"));
        for ($i = $months; $i > 0; $i--) {
            $this->lastMonths[] = array(
                'display' => date('M, Y', strtotime("-$i months")) ,
                'conditions' => array(
                    '#MODEL#.created >=' => date('Y-m-01', strtotime("-$i months")) ,
                    '#MODEL#.created <=' => date('Y-m-t', strtotime("-$i months")) ,
                )
            );
        }
        $this->lastMonths[] = array(
            'display' => date('M, Y') ,
            'conditions' => array(
                '#MODEL#.created >=' => date('Y-m-01', strtotime('now')) ,
                '#MODEL#.created <=' => date('Y-m-t', strtotime('now')) ,
            )
        );
        $months = 5;
        for ($i = $months; $i > 2; $i--) {
            $this->lastMonthsPrev[] = array(
                'display' => date('M, Y', strtotime("-$i months")) ,
                'conditions' => array(
                    '#MODEL#.created >=' => date('Y-m-01', strtotime("-$i months")) ,
                    '#MODEL#.created <=' => date('Y-m-' . date('t', strtotime("-$i months")) , strtotime("-$i months"))
                )
            );
        }
        //# last years date settings
        $years = 2;
        $this->lastYearsStartDate = date('Y-01-01', strtotime("-$years years"));
        for ($i = $years; $i > 0; $i--) {
            $this->lastYears[] = array(
                'display' => date('Y', strtotime("-$i years")) ,
                'conditions' => array(
                    '#MODEL#.created >=' => date('Y-01-01', strtotime("-$i years")) ,
                    '#MODEL#.created <=' => date('Y-12-31', strtotime("-$i years")) ,
                )
            );
        }
        $this->lastYears[] = array(
            'display' => date('Y') ,
            'conditions' => array(
                '#MODEL#.created >=' => date('Y-01-01', strtotime('now')) ,
                '#MODEL#.created <=' => date('Y-12-31', strtotime('now')) ,
            )
        );
        $years = 5;
        for ($i = $years; $i > 2; $i--) {
            $this->lastYearsPrev[] = array(
                'display' => date('Y', strtotime("-$i years")) ,
                'conditions' => array(
                    '#MODEL#.created >=' => date('Y-01-01', strtotime("-$i years")) ,
                    '#MODEL#.created <=' => date('Y-12-' . date('t', strtotime("-$i years")) , strtotime("-$i years")) ,
                )
            );
        }
        $this->selectRanges = array(
            'lastDays' => __l('Last 7 days') ,
            'lastWeeks' => __l('Last 4 weeks') ,
            'lastMonths' => __l('Last 3 months') ,
            'lastYears' => __l('Last 3 years')
        );
    }
    public function admin_chart_stats() 
    {
    }
    public function admin_chart_metrics() 
    {
        $this->pageTitle = __l('Metrics');
    }
    public function admin_user_engagement() 
    {
        $idle_users = $this->User->find('count', array(
            'conditions' => array(
                'User.is_idle' => 1
            ) ,
            'recursive' => -1
        ));
        $posted_users = $this->User->find('count', array(
            'conditions' => array(
                'User.is_item_posted' => 1
            ) ,
            'recursive' => -1
        ));
        $requested_users = $this->User->find('count', array(
            'conditions' => array(
                'User.is_requested' => 1
            ) ,
            'recursive' => -1
        ));
        $booked_users = $this->User->find('count', array(
            'conditions' => array(
                'User.is_item_booked' => 1
            ) ,
            'recursive' => -1
        ));
        $total_users = $this->User->find('count', array(
            'recursive' => -1
        ));
        $this->set('total_users', $total_users);
        $this->set('idle_users', $idle_users);
        $this->set('posted_users', $posted_users);
        $this->set('requested_users', $requested_users);
        $this->set('booked_users', $booked_users);
    }
    public function admin_user_activities() 
    {
        App::import('Model', 'Items.ItemView');
        $this->ItemView = new ItemView();		
		App::import('Model', 'Items.ItemUser');
		$this->ItemUser = new ItemUser();
        App::import('Model', 'Transaction');
        $this->Transaction = new Transaction();
		App::import('Model', 'Items.Item');
		$this->Item = new Item();
		if (isset($this->request->params['named']['role_id'])) {
            $this->request->data['Chart']['role_id'] = $this->request->params['named']['role_id'];
        }
        if (isset($this->request->data['Chart']['is_ajax_load'])) {
            $this->request->params['named']['is_ajax_load'] = $this->request->data['Chart']['is_ajax_load'];
        }
        $this->initChart();
		App::import('Model', 'UserLogin');
		$this->UserLogin = new UserLogin();
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
        $role_id = ConstUserTypes::User;
        $this->request->data['Chart']['select_range_id'] = $select_var;
        $this->request->data['Chart']['role_id'] = $role_id;
        $_total_user_reg = $_total_user_login = $_total_user_follow = $_total_items = $_total_item_views = $_total_bookings = $_total_item_flag = $_total_item_favourites = $_total_requests = $_total_request_favorites = $_total_request_flag = $_transaction_data = $_total_transaction_data = 0;
        $_total_user_reg_prev = $_total_user_login_prev = $_total_user_follow_prev = $_total_items_prev = $_total_item_views_prev = $_total_bookings_prev = $_total_item_flag_prev = $_total_item_favourites_prev = $_total_requests_prev = $_total_request_favorites_prev = $_total_request_flag_prev = $_transaction_data_prev = $_total_transaction_data_prev = $_total_rev_transaction_data = $_total_rev_transaction_data_prev = $total_revenue = $rev_per = 0;
        $prev_select_var = $select_var . 'Prev';
        
        // User Registeration
        $common_conditions = array(
            'User.role_id' => $role_id
        );
        $model_datas['user_reg'] = array(
            'display' => __l('User Regsiteration') ,
            'conditions' => array()
        );
        $_user_reg_data = $this->_setLineData($select_var, $model_datas, 'User', 'User', $common_conditions);
        $_user_reg_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'User', 'User', $common_conditions);
        $sparklin_data = array();
        foreach($_user_reg_data as $display_name => $chart_data):
            $sparklin_data[] = $chart_data['0'];
            $_total_user_reg+= $chart_data['0'];
        endforeach;
        $_user_reg_data = implode(',', $sparklin_data);
        foreach($_user_reg_data_prev as $display_name => $chart_data):
            $_total_user_reg_prev+= $chart_data['0'];
        endforeach;
        // User Login
        $model_datas['user_login'] = array(
            'display' => __l('User Login') ,
            'conditions' => array()
        );
        $_user_log_data = $this->_setLineData($select_var, $model_datas, 'UserLogin', 'UserLogin');
        $_user_log_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'UserLogin', 'UserLogin');
        $sparklin_data = array();
        foreach($_user_log_data as $display_name => $chart_data):
            $sparklin_data[] = $chart_data['0'];
            $_total_user_login+= $chart_data['0'];
        endforeach;
        $_user_log_data = implode(',', $sparklin_data);
        foreach($_user_log_data_prev as $display_name => $chart_data):
            $_total_user_login_prev+= $chart_data['0'];
        endforeach;
        // User Follow
        if (isPluginEnabled('SocialMarketing')) {
            $model_datas['user-follow'] = array(
                'display' => __l('User Followers') ,
                'conditions' => array()
            );
            $_user_follow_data = $this->_setLineData($select_var, $model_datas, 'UserFollower', 'UserFollower');
            $_user_follow_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'UserFollower', 'UserFollower');
            $sparklin_data = array();
            foreach($_user_follow_data as $display_name => $chart_data):
                $sparklin_data[] = $chart_data['0'];
                $_total_user_follow+= $chart_data['0'];
            endforeach;
            $_user_follow_data = implode(',', $sparklin_data);
            foreach($_user_follow_data_prev as $display_name => $chart_data):
                $_total_user_follow_prev+= $chart_data['0'];
            endforeach;
            $this->set('user_follow_data', $_user_follow_data);
            $this->set('total_user_follow', $_total_user_follow);
            if (!empty($_total_user_follow_prev) && !empty($_total_user_follow)) {
                $user_follow_data_per = round((($_total_user_follow-$_total_user_follow_prev) *100) /$_total_user_follow_prev);
            } else if (empty($_total_user_follow_prev) && !empty($_total_user_follow)) {
                $user_follow_data_per = 100;
            } else {
                $user_follow_data_per = 0;
            }
            $this->set('user_follow_data_per', $user_follow_data_per);
        }
        
         // Items 
        if (isPluginEnabled('Items')) {
            $model_datas['items'] = array(
                'display' => Configure::read('item.alt_name_for_item_plural_caps') ,
                'conditions' => array()
            );
            $_items_data = $this->_setLineData($select_var, $model_datas, 'Item', 'Item');
            $_items_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'Item', 'Item');
            $sparklin_data = array();
            foreach($_items_data as $display_name => $chart_data):
                $sparklin_data[] = $chart_data['0'];
                $_total_items+= $chart_data['0'];
            endforeach;
            $_items_data = implode(',', $sparklin_data);
            foreach($_items_data_prev as $display_name => $chart_data):
                $_total_items_prev+= $chart_data['0'];
            endforeach;
            $this->set('items_data', $_items_data);
            $this->set('total_items', $_total_items);
            if (!empty($_total_items_prev) && !empty($_total_items)) {
                $items_data_per = round((($_total_items-$_total_items_prev) *100) /$_total_items_prev);
            } else if (empty($_total_items_prev) && !empty($_total_items)) {
                $items_data_per = 100;
            } else {
                $items_data_per = 0;
            }
            $this->set('items_data_per', $items_data_per);
			
			// Items Views
            $model_datas['itemViews'] = array(
                'display' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Views') ,
                'conditions' => array()
            );
            $_item_views_data = $this->_setLineData($select_var, $model_datas, 'ItemView', 'ItemView');
            $_item_views_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'ItemView', 'ItemView');
            $sparklin_data = array();
            foreach($_item_views_data as $display_name => $chart_data):
                $sparklin_data[] = $chart_data['0'];
                $_total_item_views+= $chart_data['0'];
            endforeach;
            $_item_views_data = implode(',', $sparklin_data);
            foreach($_item_views_data_prev as $display_name => $chart_data):
                $_total_item_views_prev+= $chart_data['0'];
            endforeach;
            $this->set('item_views_data', $_item_views_data);
            $this->set('total_item_views', $_total_item_views);
            if (!empty($_total_item_views_prev) && !empty($_total_item_views)) {
                $item_views_data_per = round((($_total_item_views-$_total_item_views_prev) *100) /$_total_item_views_prev);
            } else if (empty($_total_item_views_prev) && !empty($_total_item_views)) {
                $item_views_data_per = 100;
            } else {
                $item_views_data_per = 0;
            }
            $this->set('item_views_data_per', $item_views_data_per);

			// Bookings
            $model_datas['itemUsers'] = array(
                'display' => __l('Bookings') ,
                'conditions' => array()
            );
            $_bookings_data = $this->_setLineData($select_var, $model_datas, 'itemUsers', 'itemUsers');
            $_bookings_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'itemUsers', 'itemUsers');
            $sparklin_data = array();
            foreach($_bookings_data as $display_name => $chart_data):
                $sparklin_data[] = $chart_data['0'];
                $_total_bookings+= $chart_data['0'];
            endforeach;
            $_bookings_data = implode(',', $sparklin_data);
            foreach($_bookings_data_prev as $display_name => $chart_data):
                $_total_bookings_prev+= $chart_data['0'];
            endforeach;
            $this->set('bookings_data', $_bookings_data);
            $this->set('total_bookings', $_total_bookings);
            if (!empty($_total_bookings_prev) && !empty($_total_bookings)) {
                $bookings_data_per = round((($_total_bookings-$_total_bookings_prev) *100) /$_total_bookings_prev);
            } else if (empty($_total_bookings_prev) && !empty($_total_bookings)) {
                $bookings_data_per = 100;
            } else {
                $bookings_data_per = 0;
            }
            $this->set('bookings_data_per', $bookings_data_per);
        }
        // items flag
        if (isPluginEnabled('ItemFlags')) {
            $model_datas['item_flag'] = array(
                'display' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Flags') ,
                'conditions' => array()
            );
            $_item_flag_data = $this->_setLineData($select_var, $model_datas, 'ItemFlag', 'ItemFlag');
            $_item_flag_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'ItemFlag', 'ItemFlag');
            $sparklin_data = array();
            foreach($_item_flag_data as $display_name => $chart_data):
                $sparklin_data[] = $chart_data['0'];
                $_total_item_flag+= $chart_data['0'];
            endforeach;
            $_item_flag_data = implode(',', $sparklin_data);
            foreach($_item_flag_data_prev as $display_name => $chart_data):
                $_total_item_flag_prev+= $chart_data['0'];
            endforeach;
            $this->set('item_flag_data', $_item_flag_data);
            $this->set('total_item_flag', $_total_item_flag);
            if (!empty($_total_item_flag_prev) && !empty($_total_item_flag)) {
                $item_flag_data_per = round((($_total_item_flag-$_total_item_flag_prev) *100) /$_total_item_flag_prev);
            } else if (empty($_total_item_flag_prev) && !empty($_total_item_flag)) {
                $item_flag_data_per = 100;
            } else {
                $item_flag_data_per = 0;
            }
            $this->set('item_flag_data_per', $item_flag_data_per);
        }
        
        // items favourites
        if (isPluginEnabled('ItemFavorites')) {
            $model_datas['item_favorite'] = array(
                'display' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Favorites') ,
                'conditions' => array()
            );
            $_item_favourite_data = $this->_setLineData($select_var, $model_datas, 'ItemFavorite', 'ItemFavorite');
            $_item_favourite_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'ItemFavorite', 'ItemFavorite');
            $sparklin_data = array();
            foreach($_item_favourite_data as $display_name => $chart_data):
                $sparklin_data[] = $chart_data['0'];
                $_total_item_favourites+= $chart_data['0'];
            endforeach;
            $_item_favourite_data = implode(',', $sparklin_data);
            foreach($_item_favourite_data_prev as $display_name => $chart_data):
                $_total_item_favourites_prev+= $chart_data['0'];
            endforeach;
            $this->set('item_favourite_data', $_item_favourite_data);
            $this->set('total_item_favourite', $_total_item_favourites);
            if (!empty($_total_item_favourites_prev) && !empty($_total_item_favourites)) {
                $item_favourite_data_per = round((($_total_item_favourites-$_total_item_favourites_prev) *100) /$_total_item_favourites_prev);
            } else if (empty($_total_item_favourites_prev) && !empty($_total_item_favourites)) {
                $item_favourite_data_per = 100;
            } else {
                $item_favourite_data_per = 0;
            }
            $this->set('item_favourite_data_per', $item_favourite_data_per);
        }
        
		// Request favourites
        if (isPluginEnabled('RequestFavorites')) {
            $model_datas['request_favorite'] = array(
                'display' => __l('Request Favorites') ,
                'conditions' => array()
            );
            $_request_favorite_data = $this->_setLineData($select_var, $model_datas, 'RequestFavorites', 'RequestFavorites');
            $_request_favorite_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'RequestFavorites', 'RequestFavorites');
            $sparklin_data = array();
            foreach($_request_favorite_data as $display_name => $chart_data):
                $sparklin_data[] = $chart_data['0'];
                $_total_request_favorites+= $chart_data['0'];
            endforeach;
            $_request_favorite_data = implode(',', $sparklin_data);
            foreach($_request_favorite_data_prev as $display_name => $chart_data):
                $_total_request_favorites_prev+= $chart_data['0'];
            endforeach;
			$this->set('request_favorite_data', $_request_favorite_data);
            $this->set('total_request_favorite', $_total_request_favorites);
            if (!empty($_total_request_favorites_prev) && !empty($_total_request_favorites)) {
                $request_favorite_data_per = round((($_total_request_favorites-$_total_request_favorites_prev) *100) /$_total_request_favorites_prev);
            } else if (empty($_total_item_favorites_prev) && !empty($_total_item_favorites)) {
                $request_favorite_data_per = 100;
            } else {
                $request_favorite_data_per = 0;
            }
            $this->set('request_favorite_data_per', $request_favorite_data_per);
        }

        // Requests
        
        if (isPluginEnabled('Requests')) {
            $model_datas['requests'] = array(
                'display' => __l('Requests') ,
                'conditions' => array()
            );
            $_requests_data = $this->_setLineData($select_var, $model_datas, 'Request', 'Request');
            $_requests_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'Request', 'Request');
            $sparklin_data = array();
            foreach($_requests_data as $display_name => $chart_data):
                $sparklin_data[] = $chart_data['0'];
                $_total_requests+= $chart_data['0'];
            endforeach;
            $_requests_data = implode(',', $sparklin_data);
            foreach($_requests_data_prev as $display_name => $chart_data):
                $_total_requests_prev+= $chart_data['0'];
            endforeach;
            $this->set('requests_data', $_requests_data);
            $this->set('total_requests', $_total_requests);
            if (!empty($_total_requests_prev) && !empty($_total_requests)) {
                $requests_data_per = round((($_total_requests-$_total_requests_prev) *100) /$_total_requests_prev);
            } else if (empty($_total_requests_prev) && !empty($_total_requests)) {
                $requests_data_per = 100;
            } else {
                $requests_data_per = 0;
            }
            $this->set('requests_data_per', $requests_data_per);
        }
        
        // Request Flags
        if (isPluginEnabled('RequestFlags')) {
            $model_datas['request_flag'] = array(
                'display' => __l('Request Flags') ,
                'conditions' => array()
            );
            $_request_flag_data = $this->_setLineData($select_var, $model_datas, 'RequestFlag', 'RequestFlag');
            $_request_flag_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'RequestFlag', 'RequestFlag');
            $sparklin_data = array();
            foreach($_request_flag_data as $display_name => $chart_data):
                $sparklin_data[] = $chart_data['0'];
                $_total_request_flag+= $chart_data['0'];
            endforeach;
            $_request_flag_data = implode(',', $sparklin_data);
            foreach($_request_flag_data_prev as $display_name => $chart_data):
                $_total_request_flag_prev+= $chart_data['0'];
            endforeach;
            $this->set('request_flag_data', $_request_flag_data);
            $this->set('total_request_flag', $_total_request_flag);
            if (!empty($_total_request_flag_prev) && !empty($_total_request_flag)) {
                $request_flag_data_per = round((($_total_request_flag-$_total_request_flag_prev) *100) /$_total_request_flag_prev);
            } else if (empty($_total_request_flag_prev) && !empty($_total_request_flag)) {
                $request_flag_data_per = 100;
            } else {
                $request_flag_data_per = 0;
            }
            $this->set('request_flag_data_per', $request_flag_data_per);
        }
        
       // Revenue
        $sparklin_data = array();
        $conditions = array();
        $conditions['OR'][]['Transaction.transaction_type_id'] = ConstTransactionTypes::ItemListingFee;
        $conditions['OR'][]['Transaction.transaction_type_id'] = ConstTransactionTypes::SignupFee;
        $model_datas['transaction'] = array(
            'display' => __l('Transaction') ,
            'conditions' => array()
        );
        $_transaction_data = $this->_setLineData($select_var, $model_datas, 'Transaction', 'Transaction', $conditions);
        $_transaction_data_prev = $this->_setLineData($prev_select_var, $model_datas, 'Transaction', 'Transaction', $conditions);
        $return_field = 'amount';
        $common_conditions = array();
        $revenue = implode(',', $sparklin_data);
        $total_revenue = $_total_transaction_data+$_total_rev_transaction_data;
        $total_revenue_prev = $_total_transaction_data_prev+$_total_rev_transaction_data_prev;
        $this->set('user_reg_data', $_user_reg_data);
        $this->set('total_user_reg', $_total_user_reg);
        if (!empty($_total_user_reg_prev) && !empty($_total_user_reg)) {
            $user_reg_data_per = round((($_total_user_reg-$_total_user_reg_prev) *100) /$_total_user_reg_prev);
        } else if (empty($_total_user_reg_prev) && !empty($_total_user_reg)) {
            $user_reg_data_per = 100;
        } else {
            $user_reg_data_per = 0;
        }
        $this->set('user_reg_data_per', $user_reg_data_per);
        $this->set('user_log_data', $_user_log_data);
        $this->set('total_user_login', $_total_user_login);
        if (!empty($_total_user_login_prev) && !empty($_total_user_login)) {
            $user_log_data_per = round((($_total_user_login-$_total_user_login_prev) *100) /$_total_user_login_prev);
        } else if (empty($_total_user_login_prev) && !empty($_total_user_login)) {
            $user_log_data_per = 100;
        } else {
            $user_log_data_per = 0;
        }
        $this->set('user_log_data_per', $user_log_data_per);
        $this->set('revenue', $revenue);
        $this->set('total_revenue', $total_revenue);
        if (!empty($total_revenue_prev) && !empty($total_revenue)) {
            $rev_per = round((($total_revenue-$total_revenue_prev) *100) /$total_revenue_prev);
        } else if (empty($total_revenue_prev) && !empty($total_revenue)) {
            $rev_per = 100;
        } else {
            $rev_per = 0;
        }
        $this->set('rev_per', $rev_per);
    }
    protected function _setLineData($select_var, $model_datas, $models, $model = '', $common_conditions = array() , $return_field = '') 
    {
        if (is_array($models)) {
            foreach($models as $m) {
                $this->loadModel($m);
            }
        } else {
            $this->loadModel($models);
            $model = $models;
        }
        $_data = array();
        foreach($this->$select_var as $val) {
            foreach($model_datas as $model_data) {
                $new_conditions = array();
                foreach($val['conditions'] as $key => $v) {
                    $key = str_replace('#MODEL#', $model, $key);
                    $new_conditions[$key] = $v;
                }
                $new_conditions = array_merge($new_conditions, $model_data['conditions']);
                $new_conditions = array_merge($new_conditions, $common_conditions);
                if (isset($model_data['model'])) {
                    $modelClass = $model_data['model'];
                } else {
                    $modelClass = $model;
                }
                $_data[$val['display']][] = $this->{$modelClass}->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
            }
        }
        return $_data;
    }
}
?>