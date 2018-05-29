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
class SeatStatusData {

	public $table = 'seat_statuses';

	public $records = array(
		array(
			'id' => '1',
			'created' => '2016-01-21 12:18:10.088',
			'modified' => '2016-01-21 12:18:10.088',
			'name' => 'Available',
			'color_code' => '#8dca35'
		),
		array(
			'id' => '2',
			'created' => '2016-01-21 12:18:56.744',
			'modified' => '2016-01-21 12:18:56.744',
			'name' => 'Unavailable',
			'color_code' => '#bf4444'
		),
		array(
			'id' => '3',
			'created' => '2016-01-21 12:20:42.65',
			'modified' => '2016-01-21 12:20:42.65',
			'name' => 'Blocked',
			'color_code' => '#f49b00'
		),
		array(
			'id' => '4',
			'created' => '2016-01-21 12:21:09.633',
			'modified' => '2016-01-21 12:21:09.633',
			'name' => 'Booked',
			'color_code' => '#8b65d6'
		),
		array(
			'id' => '5',
			'created' => '2016-01-21 12:22:41.751',
			'modified' => '2016-01-21 12:22:41.751',
			'name' => 'NoSeat',
			'color_code' => '#999999'
		),
		array(
			'id' => '6',
			'created' => '2016-02-17 04:13:40.061',
			'modified' => '2016-02-17 04:13:40.061',
			'name' => 'WaitingForAcceptance',
			'color_code' => '#000000'
		),
	);

}
