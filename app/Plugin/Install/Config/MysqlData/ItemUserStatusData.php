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
class ItemUserStatusData {

	public $table = 'item_user_statuses';

	public $records = array(
		array(
			'id' => '1',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Booking Request',
			'item_user_count' => '0',
			'slug' => 'booking-request',
			'description' => 'Request for booking.'
		),
		array(
			'id' => '2',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Booking Request Confirmed',
			'item_user_count' => '0',
			'slug' => 'booking-request-confirmed',
			'description' => 'Requested booking confirmed.'
		),
		array(
			'id' => '3',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Payment Pending',
			'item_user_count' => '0',
			'slug' => 'payment-pending',
			'description' => 'Booking is in payment pending status.'
		),
		array(
			'id' => '4',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Waiting For Acceptance',
			'item_user_count' => '0',
			'slug' => 'waiting-for-acceptance',
			'description' => 'Booking was made by the ##BOOKER## on ##CREATED_DATE##. Waiting for Host ##HOSTER## to accept the order.'
		),
		array(
			'id' => '5',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Rejected',
			'item_user_count' => '0',
			'slug' => 'rejected',
			'description' => 'Booking was rejected by the ##HOSTER##. Booking amount has been refunded.'
		),
		array(
			'id' => '6',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Canceled',
			'item_user_count' => '0',
			'slug' => 'canceled',
			'description' => 'Booking was canceled by ##BOOKER##. Booking amount has been refunded based on cancellation policies.'
		),
		array(
			'id' => '7',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Canceled By Admin',
			'item_user_count' => '0',
			'slug' => 'canceled-by-admin',
			'description' => 'Booking was canceled by Administrator. Booking amount has been refunded based on cancellation policies.'
		),
		array(
			'id' => '8',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Expired',
			'item_user_count' => '0',
			'slug' => 'expired',
			'description' => 'Booking was expired due to non acceptance by the host ##HOSTER##. Booking amount has been refunded.'
		),
		array(
			'id' => '9',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Confirmed',
			'item_user_count' => '0',
			'slug' => 'confirmed',
			'description' => 'Booking was accepted by ##HOSTER## on ##ACCEPTED_DATE##.'
		),
		array(
			'id' => '10',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Waiting for Review',
			'item_user_count' => '0',
			'slug' => 'waiting-for-review',
			'description' => '##BOOKER## has checked out.'
		),
		array(
			'id' => '11',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Booker Reviewed',
			'item_user_count' => '0',
			'slug' => 'booker-reviewed',
			'description' => 'Booker reviewed.'
		),
		array(
			'id' => '12',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Host Reviewed',
			'item_user_count' => '0',
			'slug' => 'host-reviewed',
			'description' => 'Host reviewed.'
		),
		array(
			'id' => '13',
			'created' => '2011-04-28 18:06:17',
			'modified' => '2011-04-28 18:06:19',
			'name' => 'Completed',
			'item_user_count' => '0',
			'slug' => 'completed',
			'description' => 'Order completed.'
		),
	);

}
