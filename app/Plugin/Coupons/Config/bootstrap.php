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
 CmsHook::setExceptionUrl(array(
	'collections/index',
 ));
CmsNav::add('items', array(
	'title' => Configure::read('item.alt_name_for_item_plural_caps') ,
    'url' => '' ,
    'weight' => 30,
    'children' => array(
        'listing' => array(
            'title' => Configure::read('item.alt_name_for_item_plural_caps') ,
            'url' => '' ,
            'weight' => 10,
        ) , 
		'Coupons' => array(
            'title' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Coupons') ,
            'url' => array(
                'controller' => 'coupons',
                'action' => 'index',
            ) ,
            'weight' => 35,
        )
    ) ,
));