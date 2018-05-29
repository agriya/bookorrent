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
class PaymentGatewayData {

	public $table = 'payment_gateways';

	public $records = array(
		array(
			'id' => '1',
			'created' => '2010-05-10 10:43:02',
			'modified' => '2013-12-05 08:26:07',
			'name' => 'ZazPay',
			'display_name' => 'ZazPay',
			'description' => 'Payment through ZazPay',
			'gateway_fees' => '2.90',
			'transaction_count' => '',
			'payment_gateway_setting_count' => '1',
			'is_mass_pay_enabled' => '1',
			'is_test_mode' => '1',
			'is_active' => '1'
		),
		array(
			'id' => '2',
			'created' => '2010-05-10 10:43:02',
			'modified' => '2012-06-13 09:38:06',
			'name' => 'Wallet',
			'display_name' => 'Wallet',
			'description' => 'Wallet option',
			'gateway_fees' => '',
			'transaction_count' => '0',
			'payment_gateway_setting_count' => '0',
			'is_mass_pay_enabled' => '',
			'is_test_mode' => '1',
			'is_active' => '1'
		),
	);

}
