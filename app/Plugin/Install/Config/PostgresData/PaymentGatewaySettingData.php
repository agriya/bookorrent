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
class PaymentGatewaySettingData {

	public $table = 'payment_gateway_settings';

	public $records = array(
		array(
			'id' => '1',
			'created' => '2013-07-26 21:44:57',
			'modified' => '2013-07-26 21:44:59',
			'payment_gateway_id' => '1',
			'name' => 'sudopay_subscription_plan',
			'type' => 'text',
			'options' => '',
			'test_mode_value' => '',
			'live_mode_value' => '',
			'description' => 'Subscription plan name'
		),
		array(
			'id' => '2',
			'created' => '2013-07-22 17:09:03',
			'modified' => '2013-07-22 17:09:05',
			'payment_gateway_id' => '1',
			'name' => 'sudopay_api_key',
			'type' => 'text',
			'options' => '',
			'test_mode_value' => '',
			'live_mode_value' => '',
			'description' => ''
		),
		array(
			'id' => '3',
			'created' => '2013-05-31 13:38:29',
			'modified' => '2013-05-31 13:38:29',
			'payment_gateway_id' => '1',
			'name' => 'sudopay_secret_string',
			'type' => 'text',
			'options' => '',
			'test_mode_value' => '',
			'live_mode_value' => '',
			'description' => ''
		),
		array(
			'id' => '4',
			'created' => '2013-05-31 13:38:29',
			'modified' => '2013-05-31 13:38:29',
			'payment_gateway_id' => '1',
			'name' => 'sudopay_merchant_id',
			'type' => 'text',
			'options' => '',
			'test_mode_value' => '',
			'live_mode_value' => '',
			'description' => ''
		),
		array(
			'id' => '5',
			'created' => '2013-05-31 13:38:29',
			'modified' => '2013-05-31 13:38:29',
			'payment_gateway_id' => '1',
			'name' => 'sudopay_website_id',
			'type' => 'text',
			'options' => '',
			'test_mode_value' => '',
			'live_mode_value' => '',
			'description' => ''
		),
		array(
			'id' => '6',
			'created' => '2013-07-22 17:20:49',
			'modified' => '2013-07-22 17:20:51',
			'payment_gateway_id' => '1',
			'name' => 'is_payment_via_api',
			'type' => 'checkbox',
			'options' => '',
			'test_mode_value' => '',
			'live_mode_value' => '',
			'description' => 'Enable/Disable the current payment option'
		),
		array(
			'id' => '7',
			'created' => '1970-01-01 00:00:00',
			'modified' => '1970-01-01 00:00:00',
			'payment_gateway_id' => '1',
			'name' => 'is_enable_for_signup_fee',
			'type' => 'checkbox',
			'options' => '',
			'test_mode_value' => '1',
			'live_mode_value' => '',
			'description' => 'Enable/disable the current payment options for membership fee'
		),
		array(
			'id' => '8',
			'created' => '2010-07-15 12:21:33',
			'modified' => '2010-07-15 12:21:33',
			'payment_gateway_id' => '1',
			'name' => 'is_enable_for_add_to_wallet',
			'type' => 'checkbox',
			'options' => '',
			'test_mode_value' => '1',
			'live_mode_value' => '',
			'description' => 'Enable/Disable the current payment option for wallet add.'
		),
		array(
			'id' => '9',
			'created' => '2010-07-15 12:21:33',
			'modified' => '2010-07-15 12:21:33',
			'payment_gateway_id' => '1',
			'name' => 'is_enable_for_item_listing_fee',
			'type' => 'checkbox',
			'options' => '',
			'test_mode_value' => '1',
			'live_mode_value' => '',
			'description' => 'Enable/Disable the current payment option for item fee.'
		),
		array(
			'id' => '10',
			'created' => '2010-07-15 12:21:33',
			'modified' => '2010-07-15 12:21:33',
			'payment_gateway_id' => '1',
			'name' => 'is_enable_for_book_a_item',
			'type' => 'checkbox',
			'options' => '',
			'test_mode_value' => '1',
			'live_mode_value' => '',
			'description' => 'Enable/Disable the current payment option for book item.'
		),
		array(
			'id' => '11',
			'created' => '2010-07-15 12:21:33',
			'modified' => '2010-07-15 12:21:33',
			'payment_gateway_id' => '2',
			'name' => 'is_enable_for_item_listing_fee',
			'type' => 'checkbox',
			'options' => '',
			'test_mode_value' => '1',
			'live_mode_value' => '1',
			'description' => 'Enable/Disable the current payment option for item fee.'
		),
		array(
			'id' => '12',
			'created' => '2010-07-15 12:21:33',
			'modified' => '2010-07-15 12:21:33',
			'payment_gateway_id' => '2',
			'name' => 'is_enable_for_book_a_item',
			'type' => 'checkbox',
			'options' => '',
			'test_mode_value' => '1',
			'live_mode_value' => '1',
			'description' => 'Enable/Disable the current payment option for book item.'
		),
	);

}
