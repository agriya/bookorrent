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
			'created' => '2016-01-21 12:18:10',
			'modified' => '2016-01-21 12:18:10',
			'name' => 'Available',
			'color_code' => '#8dca35'
		),
		array(
			'id' => '2',
			'created' => '2016-01-21 12:18:57',
			'modified' => '2016-01-21 12:18:57',
			'name' => 'Unavailable',
			'color_code' => '#bf4444'
		),
		array(
			'id' => '3',
			'created' => '2016-01-21 12:20:43',
			'modified' => '2016-01-21 12:20:43',
			'name' => 'Blocked',
			'color_code' => '#f49b00'
		),
		array(
			'id' => '4',
			'created' => '2016-01-21 12:21:10',
			'modified' => '2016-01-21 12:21:10',
			'name' => 'Booked',
			'color_code' => '#8b65d6'
		),
		array(
			'id' => '5',
			'created' => '2016-01-21 12:22:42',
			'modified' => '2016-01-21 12:22:42',
			'name' => 'NoSeat',
			'color_code' => '#999999'
		),
		array(
			'id' => '6',
			'created' => '2016-02-17 00:15:02',
			'modified' => '2016-02-17 00:15:02',
			'name' => 'WaitingForAcceptance',
			'color_code' => '#000000'
		),
	);

}
