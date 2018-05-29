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
				'item_flags' => array(
					'title' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Flags') ,
					'url' => array(
						'admin' => true,
						'controller' => 'item_flags',
						'action' => 'index',
					) ,
					'weight' => 50,
				),
			),
		));
