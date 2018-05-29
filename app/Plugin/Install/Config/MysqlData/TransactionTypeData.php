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
class TransactionTypeData {

	public $table = 'transaction_types';

	public $records = array(
		array(
			'id' => '1',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Membership Fee',
			'is_credit' => '',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Membership fee paid',
			'message_for_receiver' => '',
			'message_for_admin' => 'Membership fee paid by ##HOST##',
			'transaction_variables' => ''
		),
		array(
			'id' => '2',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Amount added to wallet',
			'is_credit' => '1',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Amount added to wallet',
			'message_for_receiver' => '',
			'message_for_admin' => '##USER## added amount to his wallet',
			'transaction_variables' => 'USER'
		),
		array(
			'id' => '3',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Item item fee',
			'is_credit' => '',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Item fee paid for item ##ITEM##',
			'message_for_receiver' => '',
			'message_for_admin' => '##HOST## paid item fee for item ##ITEM##',
			'transaction_variables' => 'ITEM, HOST'
		),
		array(
			'id' => '4',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Booked a item',
			'is_credit' => '',
			'is_credit_to_receiver' => '1',
			'is_credit_to_admin' => '1',
			'message' => 'Booked# ##ORDER_NO## a item ##ITEM## for ##ITEM_AMOUNT##',
			'message_for_receiver' => '##BOOKER## booked# ##ORDER_NO## item ##ITEM## for ##ITEM_AMOUNT##',
			'message_for_admin' => '##BOOKER## booked# ##ORDER_NO## a item ##ITEM## for ##ITEM_AMOUNT##',
			'transaction_variables' => 'BOOKER, ITEM, ITEM_AMOUNT, ORDER_NO'
		),
		array(
			'id' => '5',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Refund for expired booking',
			'is_credit' => '',
			'is_credit_to_receiver' => '1',
			'is_credit_to_admin' => '',
			'message' => 'Booking# ##ORDER_NO## expired for item ##ITEM##',
			'message_for_receiver' => 'Booking# ##ORDER_NO## expired for item ##ITEM##',
			'message_for_admin' => 'Booking# ##ORDER_NO## expired for item ##ITEM##',
			'transaction_variables' => 'BOOKER, ITEM, ORDER_NO'
		),
		array(
			'id' => '6',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Refund for rejected booking',
			'is_credit' => '',
			'is_credit_to_receiver' => '1',
			'is_credit_to_admin' => '',
			'message' => '##HOST## rejected booking# ##ORDER_NO## for item ##ITEM##',
			'message_for_receiver' => 'You have rejected booking# ##ORDER_NO## for item ##ITEM##',
			'message_for_admin' => '##HOST## rejected booking# ##ORDER_NO## for item ##ITEM##',
			'transaction_variables' => 'BOOKER, ITEM, ORDER_NO, HOST'
		),
		array(
			'id' => '7',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Refund for canceled booking',
			'is_credit' => '',
			'is_credit_to_receiver' => '1',
			'is_credit_to_admin' => '',
			'message' => 'Canceled booking# ##ORDER_NO## for item ##ITEM##',
			'message_for_receiver' => 'Canceled booking# ##ORDER_NO## for item ##ITEM##',
			'message_for_admin' => 'Canceled booking# ##ORDER_NO## for item ##ITEM##',
			'transaction_variables' => 'ITEM, ORDER_NO'
		),
		array(
			'id' => '8',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Refund for admin canceled booking',
			'is_credit' => '',
			'is_credit_to_receiver' => '1',
			'is_credit_to_admin' => '',
			'message' => 'Administrator canceled booking# ##ORDER_NO## for item ##ITEM##',
			'message_for_receiver' => 'Administrator canceled booking# ##ORDER_NO## for item ##ITEM##',
			'message_for_admin' => 'Canceled booking# ##ORDER_NO## for item ##ITEM##',
			'transaction_variables' => 'ITEM, ORDER_NO'
		),
		array(
			'id' => '9',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Host amount cleared',
			'is_credit' => '',
			'is_credit_to_receiver' => '1',
			'is_credit_to_admin' => '',
			'message' => 'Booking# ##ORDER_NO## amount cleared to ##HOST## for item ##ITEM##',
			'message_for_receiver' => 'Booking# ##ORDER_NO## amount cleared for item ##ITEM##',
			'message_for_admin' => 'Booking# ##ORDER_NO## amount cleared to ##HOST## for item ##ITEM##',
			'transaction_variables' => 'ITEM, ORDER_NO'
		),
		array(
			'id' => '10',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Cash withdrawal request',
			'is_credit' => '',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '',
			'message' => 'Cash withdrawal request made by you',
			'message_for_receiver' => '',
			'message_for_admin' => 'Cash withdrawal request made by ##USER##',
			'transaction_variables' => 'USER'
		),
		array(
			'id' => '11',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Cash withdrawal request approved',
			'is_credit' => '',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '',
			'message' => 'Your cash withdrawal request approved by Administrator',
			'message_for_receiver' => '',
			'message_for_admin' => 'You (Administrator) have approved ##HOST## cash withdrawal request',
			'transaction_variables' => 'HOST'
		),
		array(
			'id' => '12',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Cash withdrawal request rejected',
			'is_credit' => '1',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Amount refunded for rejected cash withdrawal request',
			'message_for_receiver' => '',
			'message_for_admin' => 'Amount refunded to ##USER## for rejected cash withdrawal request',
			'transaction_variables' => 'USER'
		),
		array(
			'id' => '13',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Cash withdrawal request paid',
			'is_credit' => '',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '',
			'message' => 'Cash withdraw request amount paid to you',
			'message_for_receiver' => '',
			'message_for_admin' => 'Cash withdraw request amount paid to ##USER##',
			'transaction_variables' => 'USER'
		),
		array(
			'id' => '14',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Cash withdrawal request failed',
			'is_credit' => '1',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Amount refunded for failed cash withdrawal request',
			'message_for_receiver' => '',
			'message_for_admin' => 'Amount refunded to ##USER## for failed cash withdrawal request',
			'transaction_variables' => 'USER'
		),
		array(
			'id' => '15',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Affiliate cash withdrawal request',
			'is_credit' => '',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Affiliate cash withdrawal request made by you',
			'message_for_receiver' => '',
			'message_for_admin' => 'Affiliate cash withdrawal request made by ##AFFILIATE_USER##',
			'transaction_variables' => 'AFFILIATE_USER'
		),
		array(
			'id' => '16',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Affiliate cash withdrawal request approved',
			'is_credit' => '',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Your affiliate cash withdrawal request approved by Administrator',
			'message_for_receiver' => '',
			'message_for_admin' => 'You (Administrator) have approved ##AFFILIATE_USER## cash withdrawal request',
			'transaction_variables' => 'AFFILIATE_USER'
		),
		array(
			'id' => '17',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Affiliate cash withdrawal request rejected',
			'is_credit' => '1',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Amount refunded for rejected affiliate cash withdrawal request',
			'message_for_receiver' => '',
			'message_for_admin' => 'Amount refunded to ##AFFILIATE_USER## for rejected affiliate cash withdrawal request',
			'transaction_variables' => 'AFFILIATE_USER'
		),
		array(
			'id' => '18',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Affiliate cash withdrawal request failed',
			'is_credit' => '1',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Amount refunded for failed affiliate cash withdrawal request',
			'message_for_receiver' => '',
			'message_for_admin' => 'Amount refunded to ##AFFILIATE_USER## for failed affiliate cash withdrawal request',
			'transaction_variables' => 'AFFILIATE_USER'
		),
		array(
			'id' => '19',
			'created' => '0000-00-00 00:00:00',
			'modified' => '0000-00-00 00:00:00',
			'name' => 'Affiliate cash withdrawal request paid',
			'is_credit' => '',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '',
			'message' => 'Affiliate cash withdraw request amount paid to you',
			'message_for_receiver' => '',
			'message_for_admin' => 'Affiliate cash withdraw request amount paid to ##AFFILIATE_USER##',
			'transaction_variables' => 'AFFILIATE_USER'
		),
		array(
			'id' => '20',
			'created' => '2010-09-17 11:12:37',
			'modified' => '2010-09-17 11:12:42',
			'name' => 'Admin add fund to wallet',
			'is_credit' => '1',
			'is_credit_to_receiver' => '1',
			'is_credit_to_admin' => '',
			'message' => 'Administrator added fund to your wallet',
			'message_for_receiver' => 'Administrator added fund to your wallet',
			'message_for_admin' => 'Added fund to ##USER## wallet',
			'transaction_variables' => '##USER##'
		),
		array(
			'id' => '21',
			'created' => '2010-09-17 11:13:20',
			'modified' => '2010-09-17 11:13:23',
			'name' => 'Admin deduct fund from wallet',
			'is_credit' => '',
			'is_credit_to_receiver' => '',
			'is_credit_to_admin' => '1',
			'message' => 'Administrator deducted fund from your wallet',
			'message_for_receiver' => 'Administrator deducted fund from your wallet',
			'message_for_admin' => 'Deducted fund from ##USER## wallet',
			'transaction_variables' => '##USER##'
		),
	);

}
