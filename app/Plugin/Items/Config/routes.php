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
$controllers = Cache::read('controllers_list', 'default');
if ($controllers === false) {
    $controllers = App::objects('controller');
    foreach($controllers as &$value) {
        $value = Inflector::underscore($value);
    }
    foreach($controllers as $value) {
        $controllers[] = Inflector::singularize($value);
    }
    array_push($controllers, 'admin');
    $controllers = implode('|', $controllers);
    Cache::write('controllers_list', $controllers);
}
CmsRouter::connect('/feeds', array(
    'controller' => 'items',
    'action' => 'index',
    'plugin' => 'items',
    'ext' => 'rss',
));
CmsRouter::connect('/', array(
    'controller' => 'items',
    'action' => 'search',
));
CmsRouter::connect('/items/guest', array(
    'controller' => 'items',
    'action' => 'datafeed'
) , array(
    'method' => 'guest',
    'startdate' => '[0-9\-]+',
    'enddate' => '[0-9\-]+',
    'item_id' => '[0-9\-]+',
    'year' => '[0-9\-]+',
    'month' => 'a-zA-Z]+',
));
CmsRouter::connect('/myitems', array(
    'controller' => 'items',
    'action' => 'index',
    'type' => 'myitems',
));
CmsRouter::connect('/map', array(
    'controller' => 'items',
    'action' => 'map',
));
CmsRouter::connect('/calendar', array(
    'controller' => 'item_users',
    'action' => 'index',
    'type' => 'myworks',
    'status' => 'waiting_for_acceptance',
));
CmsRouter::connect('/bookings', array(
    'controller' => 'item_users',
    'action' => 'index',
    'type' => 'mytours',
    'status' => 'in_progress',
));
CmsRouter::connect('/items/start', array(
    'controller' => 'categories',
    'action' => 'index',
));
CmsRouter::connect('/items/favorites', array(
    'controller' => 'items',
    'action' => 'index',
    'type' => 'favorite'
));
CmsRouter::connect('/:city/items', array(
    'controller' => 'items',
    'action' => 'index'
) , array(
    'city' => '(?!' . $controllers . ')[^\/]*'
));
CmsRouter::connect('/activity/:order_id', array(
    'controller' => 'messages',
    'action' => 'activities',
) , array(
    'order_id' => '[0-9]+'
));
CmsRouter::connect('/collection/:slug', array(
    'controller' => 'items',
    'action' => 'index',
	'type' => 'collection',
) , array(
    'slug' => '[^\/]*'
));
?>