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
CmsNav::add('items', array(
     'title' => Configure::read('item.alt_name_for_item_singular_caps') ,
    'url' => array(
        'admin' => true,
        'controller' => 'items',
        'action' => 'admin_index',
    ) ,
    'data-bootstro-step' => '4',
    'data-bootstro-content' => sprintf(__l('To monitor the site and also to manage all %s & Collections in the site.'), Configure::read('item.alt_name_for_item_plural_small')),
    'icon-class' => 'building',
    'weight' => 30,
    'children' => array(
		'listing' => array(
            'title' => Configure::read('item.alt_name_for_item_plural_caps') ,
            'url' => '' ,
            'weight' => 10,
        ) , 
		'listings' => array(
            'title' => Configure::read('item.alt_name_for_item_plural_caps') ,
            'url' => array(
                'admin' => true,
                'controller' => 'items',
                'action' => 'admin_index',
            ) ,
            'weight' => 20,
        ) , 
		'Listing Bookings' => array(
            'title' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Bookings') ,
            'url' => array(
                'admin' => true,
                'controller' => 'item_users',
                'action' => 'index',
            ) ,
            'weight' => 30,
        ) ,
		'Post a listing' => array(
            'title' => __l('Post a') . ' ' . Configure::read('item.alt_name_for_item_singular_caps') ,
            'url' => array(
                'controller' => 'items',
                'action' => 'add',
            ) ,
            'weight' => 40,
        ) 
    )
));


CmsNav::add('activities', array(
    'title' => __l('Activities') ,
    'icon-class' => 'time',
    'weight' => 60,
    'children' => array(
		 'item' => array(
						'title' => Configure::read('item.alt_name_for_item_plural_caps') ,
						'url' => '',
						'weight' => 40,
				 ) ,
				'item_views' => array(
					'title' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Views') ,
					'url' => array(
						'admin' => true,
						'controller' => 'item_views',
						'action' => 'admin_index',
					) ,
					'weight' => 40,
				),
				'items' => array(
					'title' => __l('Search Logs') ,
					'url' => array(
						'admin' => true,
						'controller' => 'search_logs',
						'action' => 'admin_index',
					) ,
					'weight' => 70,
				) ,
				'feedback' => array(
						'title' => __l('Feedback') ,
						'url' => '',
						'weight' => 120,
				 ) ,
				'item_feedbacks' => array(
					'title' => __l('Feedback To Host') ,
					'url' => array(
						'admin' => true,
						'controller' => 'item_feedbacks',
						'action' => 'admin_index',
					) ,
					'weight' => 120,
				),
				'item_user_feedbacks' => array(
					'title' => __l('Feedback To') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps') ,
					'url' => array(
						'admin' => true,
						'controller' => 'item_user_feedbacks',
						'action' => 'admin_index',
					) ,
					'weight' => 130,
				),
				'Item' => array(
					'title' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Activities') ,
					'url' => array(
						'admin' => true,
						'controller' => 'messages',
						'action' => 'notifications',
						'type' => 'list',
					) ,
					'weight' => 70,
				) ,
			)
		));
CmsNav::add('masters', array(
    'title' => __l('Masters'),
    'weight' => 200,
    'children' => array(
		'Item' => array(
            'title' => Configure::read('item.alt_name_for_item_singular_caps') ,
            'url' => '',
            'weight' => 1003,
        ) ,
		'Item Flag Categories' => array(
            'title' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Flag Categories') ,
            'url' => array(
                'admin' => true,
                'controller' => 'item_flag_categories',
                'action' => 'index',
            ) ,
            'weight' => 1004,
        ) ,
    )
));			
CmsHook::setExceptionUrl(array(
	'items/index',
	'items/view',
	'items/calendar',
	'items/datafeed',
	'items/map',
	'items/cluster_data',
	'items/get_info',
	'items/pricefeed',
	'items/price',
	'items/review_index',
	'items/home',
	'items/search',
	'items/streetview',
	'items/flickr',
	'items/bookit',
	'items/static_map',
	'items/calendar_edit',
	'item_users/add',
	'item_feedbacks/index',
	'item_user_feedbacks/index',
	'items/item_calendar',
	'items/weather_data',
	'items/update_price',
	'items/update_view_count',
	'items/order',
	'categories/getsubcategories',
	'categories/index',
	'categories/simple_index',
	'categories/view',
	'items/get_itemtime',
	'items/get_itemprices',
));
$defaultModel = array(
    'User' => array(
		'hasMany' => array(			
			'Item' => array(
				'className' => 'Items.Item',
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
		   'ItemUser' => array(
				'className' => 'Items.ItemUser',
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
			'Message' => array(
				'className' => 'Items.Message',
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
		)
    ) ,
	'Page' => array(
		'belongsTo' => array(
		   'Category' => array(
				'className' => 'Items.Category',
				'foreignKey' => 'category_id',
				'conditions' => '',
				'fields' => '',
				'order' => '',
			),
		),
	) ,
	'UserProfile' => array(
		'hasAndBelongsToMany' => array(
		   'Habit' => array(
				'className' => 'Items.Habit',
				'joinTable' => 'habits_user_profiles',
				'foreignKey' => 'user_profile_id',
				'associationForeignKey' => 'habit_id',
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
		),
    ) ,
	'Collection' => array(
		'hasAndBelongsToMany' => array(
			'Item' => array(
				'className' => 'Items.Item',
				'joinTable' => 'collections_items',
				'foreignKey' => 'collection_id',
				'associationForeignKey' => 'item_id',
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
		),
    ) ,	
	'Transaction' => array(
		'belongsTo' => array(
		   'ItemUser' => array(
				'className' => 'Items.ItemUser',
				'foreignKey' => 'foreign_id',
				'conditions' => '',
				'fields' => '',
				'order' => '',
			),
			'Item' => array(
				'className' => 'Items.Item',
				'foreignKey' => 'foreign_id',
				'conditions' => '',
				'fields' => '',
				'order' => '',
			)
		),
    ) ,
	'City' => array(
		'hasMany' => array(
			'Item' => array(
				'className' => 'Items.Item',
				'foreignKey' => 'city_id',
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
		)
	),
	'Ip' => array(
		'hasMany' => array(
			'ItemFeedback' => array(
				'className' => 'Items.ItemFeedback',
				'foreignKey' => 'ip_id',
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
				'foreignKey' => 'ip_id',
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
			'SearchLog' => array(
				'className' => 'Items.SearchLog',
				'foreignKey' => 'ip_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		) ,
    ) ,
);
if (isPluginEnabled('Requests')) {
	$pluginModel = array(
        'Category' => array(
            'hasMany' => array(
                'Request' => array(
					'className' => 'Requests.Request',
					'foreignKey' => 'category_id',
					'dependent' => true,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => '',
					'counterCache' => true
				),
            ) ,
        ) ,
		'SubmissionField' => array(
			'hasOne' => array(
				'RequestCloneThumb' => array(
					'className' => 'Attachment',
					'foreignKey' => 'foreign_id',
					'dependent' => false,
					'conditions' => array(
						'RequestCloneThumb.class' => 'RequestCloneThumb',
					) ,
					'fields' => '',
					'order' => ''
				) ,
			),
		),
		'Request' => array(
			'hasOne' => array(
				'Submission' => array(
					'className' => 'Items.Submission',
					'foreignKey' => 'request_id',
					'dependent' => true,
					'conditions' => '',
					'fields' => '',
					'order' => ''
				) ,
			),
		)
	);
    $defaultModel = $defaultModel+$pluginModel;
}
if (isPluginEnabled('Coupons')) {
    $pluginModel = array(
        'Item' => array(
            'hasMany' => array(
                'Coupons' => array(
                    'className' => 'Coupons.Coupon',
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
                )
            ) ,
        ) ,
    );
    $defaultModel = $defaultModel+$pluginModel;
}
if (isPluginEnabled('Seats')) {
    $pluginModel = array(
		'ItemUser' => array(
			'hasMany' => array(			
				'CustomPricePerTypesSeat' => array(
					'className' => 'Seats.CustomPricePerTypesSeat',
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
				)
			)
		),
		'CustomPricePerNight' => array(
			'belongsTo' => array(			
				'Hall' => array(
					'className' => 'Seats.Hall',
					'foreignKey' => 'hall_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'counterCache' => array(
						'custom_price_per_night_count' => true
					),
					'counterScope' => array(
						'CustomPricePerNight.hall_id !=' => null
					)
				)
			)
		),	
		'CustomPricePerType' => array(
			'hasMany' => array(			
				'CustomPricePerTypesSeat' => array(
					'className' => 'Seats.CustomPricePerTypesSeat',
					'foreignKey' => 'custom_price_per_type_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => ''
				)
			),
			'belongsTo' => array(
				'Partition' => array(
					'className' => 'Seats.Partition',
					'foreignKey' => 'partition_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'counterCache' => array(
						'custom_price_per_type_count' => true
					),
					'counterScope' => array(
						'CustomPricePerType.partition_id !=' => null
					)
				),
			)
		),	
		'CustomPricePerTypeItemUser' => array(
			'hasMany' => array(			
				'CustomPricePerTypesSeat' => array(
					'className' => 'Seats.CustomPricePerTypesSeat',
					'foreignKey' => 'custom_price_per_type_item_user_id',
					'dependent' => false,
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'limit' => '',
					'offset' => '',
					'exclusive' => '',
					'finderQuery' => '',
					'counterQuery' => ''
				)
			)
		)
    );
    $defaultModel = $defaultModel+$pluginModel;	
}
CmsHook::bindModel($defaultModel);
$sitemap_conditions = array(
    'Item.admin_suspend' => 0,
	'Item.is_approved' => 1,
	'Item.is_active' => 1,
);
CmsHook::setSitemapModel(array(
    'Item' => array(
        'conditions' => $sitemap_conditions,
        'recursive' => 0
    )
));
?>