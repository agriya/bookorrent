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
		'item_favorites' => array(
			'title' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Favorites') ,
			'url' => array(
				'admin' => true,
				'controller' => 'item_favorites',
				'action' => 'index',
			) ,
			'weight' => 60,
		),
	),
));
$defaultModel = array(
    'Ip' => array(
		'hasMany' => array(
			'ItemFavorite' => array(
				'className' => 'ItemFavorites.ItemFavorite',
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
		)
	)
);
CmsHook::bindModel($defaultModel);