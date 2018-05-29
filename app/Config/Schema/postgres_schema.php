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
class AppSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	public $acl_link_statuses = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $acl_links = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'controller' => array('type' => 'string', 'null' => true, 'default' => null),
		'action' => array('type' => 'string', 'null' => true, 'default' => null),
		'named_key' => array('type' => 'string', 'null' => true, 'default' => null),
		'named_value' => array('type' => 'string', 'null' => true, 'default' => null),
		'pass_value' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $acl_links_roles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'role_id' => array('type' => 'integer', 'null' => true),
		'acl_link_id' => array('type' => 'integer', 'null' => true),
		'acl_link_status_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'acl_links_roles_acl_link_id_idx' => array('unique' => false, 'column' => 'acl_link_id'),
			'acl_links_roles_acl_link_status_id_idx' => array('unique' => false, 'column' => 'acl_link_status_id'),
			'acl_links_roles_role_id_idx' => array('unique' => false, 'column' => 'role_id')
		),
		'tableParameters' => array()
	);
	public $affiliate_cash_withdrawal_statuses = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $affiliate_cash_withdrawals = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'affiliate_cash_withdrawal_status_id' => array('type' => 'integer', 'null' => true),
		'amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'commission_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'payment_gateway_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'affiliate_cash_withdrawals_payment_gateway_id_idx' => array('unique' => false, 'column' => 'payment_gateway_id'),
			'affiliate_cash_withdrawals_user_id_idx' => array('unique' => false, 'column' => 'user_id'),
			'iliate_cash_withdrawals_affiliate_cash_withdrawal_status_id_idx' => array('unique' => false, 'column' => 'affiliate_cash_withdrawal_status_id')
		),
		'tableParameters' => array()
	);
	public $affiliate_commission_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'description' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $affiliate_requests = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'site_name' => array('type' => 'string', 'null' => true, 'default' => null),
		'site_description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'site_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'site_category_id' => array('type' => 'integer', 'null' => true),
		'why_do_you_want_affiliate' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'is_web_site_marketing' => array('type' => 'boolean', 'null' => true),
		'is_search_engine_marketing' => array('type' => 'boolean', 'null' => true),
		'is_email_marketing' => array('type' => 'boolean', 'null' => true),
		'special_promotional_method' => array('type' => 'string', 'null' => true, 'default' => null),
		'special_promotional_description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'is_approved' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'affiliate_requests_site_category_id_idx' => array('unique' => false, 'column' => 'site_category_id'),
			'affiliate_requests_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $affiliate_statuses = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'date', 'null' => true),
		'modified' => array('type' => 'date', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $affiliate_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'model_name' => array('type' => 'string', 'null' => true, 'default' => null),
		'commission' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'affiliate_commission_type_id' => array('type' => 'integer', 'null' => true),
		'is_active' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'affiliate_types_affiliate_commission_type_id_idx' => array('unique' => false, 'column' => 'affiliate_commission_type_id'),
			'affiliate_types_model_name_idx' => array('unique' => false, 'column' => 'model_name')
		),
		'tableParameters' => array()
	);
	public $affiliates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'class' => array('type' => 'string', 'null' => true, 'default' => null),
		'foreign_id' => array('type' => 'integer', 'null' => true),
		'affiliate_type_id' => array('type' => 'integer', 'null' => true),
		'affliate_user_id' => array('type' => 'integer', 'null' => true),
		'affiliate_status_id' => array('type' => 'integer', 'null' => true),
		'commission_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'commission_holding_start_date' => array('type' => 'date', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'affiliates_affiliate_status_id_idx' => array('unique' => false, 'column' => 'affiliate_status_id'),
			'affiliates_affiliate_type_id_idx' => array('unique' => false, 'column' => 'affiliate_type_id'),
			'affiliates_affliate_user_id_idx' => array('unique' => false, 'column' => 'affliate_user_id'),
			'affiliates_class_idx' => array('unique' => false, 'column' => 'class'),
			'affiliates_foreign_id_idx' => array('unique' => false, 'column' => 'foreign_id')
		),
		'tableParameters' => array()
	);
	public $attachments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'class' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'foreign_id' => array('type' => 'integer', 'null' => true),
		'filename' => array('type' => 'string', 'null' => true, 'default' => null),
		'dir' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'mimetype' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'filesize' => array('type' => 'integer', 'null' => true),
		'height' => array('type' => 'integer', 'null' => true),
		'width' => array('type' => 'integer', 'null' => true),
		'thumb' => array('type' => 'boolean', 'null' => true),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'amazon_s3_thumb_url' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'amazon_s3_original_url' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'attachments_class_idx' => array('unique' => false, 'column' => 'class'),
			'attachments_foreign_id_idx' => array('unique' => false, 'column' => 'foreign_id')
		),
		'tableParameters' => array()
	);
	public $banned_ips = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'address' => array('type' => 'string', 'null' => true, 'default' => null),
		'range' => array('type' => 'string', 'null' => true, 'default' => null),
		'referer_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'reason' => array('type' => 'string', 'null' => true, 'default' => null),
		'redirect' => array('type' => 'string', 'null' => true, 'default' => null),
		'thetime' => array('type' => 'integer', 'null' => true),
		'timespan' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'banned_ips_address_idx' => array('unique' => false, 'column' => 'address'),
			'banned_ips_range_idx' => array('unique' => false, 'column' => 'range')
		),
		'tableParameters' => array()
	);
	public $buyer_form_fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => true),
		'display_text' => array('type' => 'string', 'null' => true, 'default' => null),
		'label' => array('type' => 'string', 'null' => true, 'default' => null),
		'type' => array('type' => 'string', 'null' => true, 'length' => 45),
		'info' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'required' => array('type' => 'boolean', 'null' => true),
		'options' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'is_active' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'buyer_form_fields_item_id_idx' => array('unique' => false, 'column' => 'item_id')
		),
		'tableParameters' => array()
	);
	public $buyer_submissions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'item_user_id' => array('type' => 'integer', 'null' => true),
		'buyer_form_field_id' => array('type' => 'integer', 'null' => true),
		'form_field' => array('type' => 'string', 'null' => true),
		'response' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'type' => array('type' => 'string', 'null' => true, 'length' => 50),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'buyer_submissions_buyer_form_field_id_idx' => array('unique' => false, 'column' => 'buyer_form_field_id'),
			'buyer_submissions_item_user_id_idx' => array('unique' => false, 'column' => 'item_user_id')
		),
		'tableParameters' => array()
	);
	public $cake_sessions = array(
		'id' => array('type' => 'string', 'null' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => true),
		'data' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'expires' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'cake_sessions_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'name' => array('type' => 'string', 'null' => true),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'slug' => array('type' => 'string', 'null' => true),
		'item_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'request_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'category_type_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'categories_parent_id_idx' => array('unique' => false, 'column' => 'parent_id')
		),
		'tableParameters' => array()
	);
	public $category_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'category_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => true),
		'is_active' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'category_types_category_id_idx' => array('unique' => false, 'column' => 'category_id')
		),
		'tableParameters' => array()
	);
	public $cities = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'country_id' => array('type' => 'integer', 'null' => true),
		'state_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'latitude' => array('type' => 'float', 'null' => true),
		'longitude' => array('type' => 'float', 'null' => true),
		'timezone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'dma_id' => array('type' => 'integer', 'null' => true),
		'county' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 25),
		'code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 4),
		'is_approved' => array('type' => 'boolean', 'null' => true),
		'item_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'request_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'cities_country_id_idx' => array('unique' => false, 'column' => 'country_id'),
			'cities_dma_id_idx' => array('unique' => false, 'column' => 'dma_id'),
			'cities_slug_idx' => array('unique' => false, 'column' => 'slug'),
			'cities_state_id_idx' => array('unique' => false, 'column' => 'state_id')
		),
		'tableParameters' => array()
	);
	public $collections = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'title' => array('type' => 'string', 'null' => true, 'default' => null),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 265),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'item_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'city_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'country_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_active' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'collections_slug_idx' => array('unique' => false, 'column' => 'slug'),
			'collections_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $collections_items = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'collection_id' => array('type' => 'integer', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'display_order' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'collections_items_collection_id_idx' => array('unique' => false, 'column' => 'collection_id'),
			'collections_items_item_id_idx' => array('unique' => false, 'column' => 'item_id')
		),
		'tableParameters' => array()
	);
	public $contacts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'email' => array('type' => 'string', 'null' => true, 'default' => null),
		'subject' => array('type' => 'string', 'null' => true, 'default' => null),
		'message' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'telephone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'contacts_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'contacts_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $countries = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'iso_alpha2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2),
		'iso_alpha3' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 3),
		'iso_numeric' => array('type' => 'integer', 'null' => true),
		'fips_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 3),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200),
		'capital' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200),
		'areainsqkm' => array('type' => 'float', 'null' => true),
		'population' => array('type' => 'integer', 'null' => true),
		'continent' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2),
		'tld' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 3),
		'currency' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 3),
		'currencyname' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'postalcodeformat' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20),
		'postalcoderegex' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20),
		'languages' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200),
		'geonameid' => array('type' => 'integer', 'null' => true),
		'neighbours' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 20),
		'equivalentfipscode' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $coupons = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => true),
		'discount' => array('type' => 'float', 'null' => true),
		'number_of_quantity' => array('type' => 'integer', 'null' => true),
		'number_of_quantity_used' => array('type' => 'integer', 'null' => true),
		'is_active' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'coupons_item_id_idx' => array('unique' => false, 'column' => 'item_id')
		),
		'tableParameters' => array()
	);
	public $currencies = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'symbol' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_enabled' => array('type' => 'boolean', 'null' => true),
		'prefix' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'is_prefix_display_on_left' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'suffix' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'decimals' => array('type' => 'integer', 'null' => true, 'default' => '2'),
		'dec_point' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2),
		'thousands_sep' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 2),
		'locale' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'format_string' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'grouping_algorithm_callback' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_use_graphic_symbol' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'currencies_name_idx' => array('unique' => false, 'column' => 'name')
		),
		'tableParameters' => array()
	);
	public $currency_conversion_histories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'currency_conversion_id' => array('type' => 'integer', 'null' => true),
		'rate_before_change' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'rate' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'currency_conversion_histories_currency_conversion_id_idx' => array('unique' => false, 'column' => 'currency_conversion_id')
		),
		'tableParameters' => array()
	);
	public $currency_conversions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'currency_id' => array('type' => 'integer', 'null' => true),
		'converted_currency_id' => array('type' => 'integer', 'null' => true),
		'rate' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'currency_conversions_converted_currency_id_idx' => array('unique' => false, 'column' => 'converted_currency_id'),
			'currency_conversions_currency_id_idx' => array('unique' => false, 'column' => 'currency_id')
		),
		'tableParameters' => array()
	);
	public $custom_price_per_nights = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'start_date' => array('type' => 'date', 'null' => true),
		'start_time' => array('type' => 'time', 'null' => true),
		'end_date' => array('type' => 'date', 'null' => true),
		'end_time' => array('type' => 'time', 'null' => true),
		'price_per_hour' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'price_per_day' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'price_per_week' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'price_per_month' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'repeat_days' => array('type' => 'string', 'null' => true),
		'is_available' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_custom' => array('type' => 'boolean', 'null' => true),
		'custom_source_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'minimum_price' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'total_available_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'total_booked_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_tipped' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_timing' => array('type' => 'boolean', 'null' => true),
		'name' => array('type' => 'string', 'null' => true),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'quantity' => array('type' => 'integer', 'null' => true),
		'min_hours' => array('type' => 'integer', 'null' => true),
		'is_seating_selection' => array('type' => 'boolean', 'null' => true),
		'hall_id' => array('type' => 'integer', 'null' => true),
		'repeat_end_date' => array('type' => 'date', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'custom_price_per_nights_end_date_idx' => array('unique' => false, 'column' => 'end_date'),
			'custom_price_per_nights_hall_id' => array('unique' => false, 'column' => 'hall_id'),
			'custom_price_per_nights_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'custom_price_per_nights_start_date_idx' => array('unique' => false, 'column' => 'start_date')
		),
		'tableParameters' => array()
	);
	public $custom_price_per_type_item_users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'item_user_id' => array('type' => 'integer', 'null' => true),
		'custom_price_per_type_id' => array('type' => 'integer', 'null' => true),
		'number_of_quantity' => array('type' => 'integer', 'null' => true),
		'price' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'total_price' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'custom_price_per_night_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'custom_price_per_type_item_users_1_idx' => array('unique' => false, 'column' => array('item_user_id', 'custom_price_per_type_id'))
		),
		'tableParameters' => array()
	);
	public $custom_price_per_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'custom_price_per_night_id' => array('type' => 'integer', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'name' => array('type' => 'string', 'null' => true),
		'price' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'max_number_of_quantity' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'min_number_per_order' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'max_number_per_order' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_advanced_enabled' => array('type' => 'boolean', 'null' => true),
		'booked_quantity' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_primary' => array('type' => 'boolean', 'null' => true),
		'is_custom' => array('type' => 'boolean', 'null' => true),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'start_time' => array('type' => 'time', 'null' => true),
		'end_time' => array('type' => 'time', 'null' => true),
		'partition_id' => array('type' => 'integer', 'null' => true),
		'available_seat_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'unavailable_seat_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'no_seat_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'blocked_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'waiting_for_acceptance_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'custom_price_per_types_1_idx' => array('unique' => false, 'column' => array('item_id', 'custom_price_per_night_id'))
		),
		'tableParameters' => array()
	);
	public $custom_price_per_types_seats = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'hall_id' => array('type' => 'integer', 'null' => true),
		'partition_id' => array('type' => 'integer', 'null' => true),
		'seat_id' => array('type' => 'integer', 'null' => true),
		'custom_price_per_type_id' => array('type' => 'integer', 'null' => true),
		'seat_status_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'booking_start_time' => array('type' => 'datetime', 'null' => true),
		'blocked_user_id' => array('type' => 'integer', 'null' => true),
		'custom_price_per_type_item_user_id' => array('type' => 'integer', 'null' => true),
		'item_user_id' => array('type' => 'integer', 'null' => true),
		'position' => array('type' => 'integer', 'null' => false),
		'name' => array('type' => 'string', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'custom_price_per_types_seats_blocked_user_id' => array('unique' => false, 'column' => 'blocked_user_id'),
			'custom_price_per_types_seats_custom_price_per_type_id' => array('unique' => false, 'column' => 'custom_price_per_type_id'),
			'custom_price_per_types_seats_custom_price_per_type_item_user_id' => array('unique' => false, 'column' => 'custom_price_per_type_item_user_id'),
			'custom_price_per_types_seats_hall_id' => array('unique' => false, 'column' => 'hall_id'),
			'custom_price_per_types_seats_item_id' => array('unique' => false, 'column' => 'item_id'),
			'custom_price_per_types_seats_item_user_id' => array('unique' => false, 'column' => 'item_user_id'),
			'custom_price_per_types_seats_partition_id' => array('unique' => false, 'column' => 'partition_id'),
			'custom_price_per_types_seats_seat_id' => array('unique' => false, 'column' => 'seat_id'),
			'custom_price_per_types_seats_seat_status_id' => array('unique' => false, 'column' => 'seat_status_id')
		),
		'tableParameters' => array()
	);
	public $email_templates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'from' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 500),
		'reply_to' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 500),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'subject' => array('type' => 'string', 'null' => true, 'default' => null),
		'email_content' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'email_text_content' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'email_variables' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 1000),
		'is_html' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'email_templates_name_idx' => array('unique' => false, 'column' => 'name')
		),
		'tableParameters' => array()
	);
	public $form_field_groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true),
		'slug' => array('type' => 'string', 'null' => true),
		'category_id' => array('type' => 'integer', 'null' => true),
		'form_field_step_id' => array('type' => 'integer', 'null' => true),
		'info' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'order' => array('type' => 'integer', 'null' => true),
		'class' => array('type' => 'string', 'null' => true),
		'is_deletable' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_editable' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_show_in_request_form' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'form_field_groups_category_id_idx' => array('unique' => false, 'column' => 'category_id'),
			'form_field_groups_form_field_step_id_idx' => array('unique' => false, 'column' => 'form_field_step_id'),
			'form_field_groups_slug_idx' => array('unique' => false, 'column' => 'slug')
		),
		'tableParameters' => array()
	);
	public $form_field_steps = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true),
		'slug' => array('type' => 'string', 'null' => true),
		'category_id' => array('type' => 'integer', 'null' => true),
		'info' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'order' => array('type' => 'integer', 'null' => true),
		'is_deletable' => array('type' => 'boolean', 'null' => true),
		'is_splash' => array('type' => 'boolean', 'null' => true),
		'additional_info' => array('type' => 'string', 'null' => true),
		'is_payment_step' => array('type' => 'boolean', 'null' => true),
		'is_editable' => array('type' => 'boolean', 'null' => true),
		'is_payout_step' => array('type' => 'boolean', 'null' => true),
		'is_show_in_request_form' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'form_field_steps_category_id_idx' => array('unique' => false, 'column' => 'category_id'),
			'form_field_steps_slug_idx' => array('unique' => false, 'column' => 'slug')
		),
		'tableParameters' => array()
	);
	public $form_fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true),
		'display_text' => array('type' => 'string', 'null' => true, 'default' => null),
		'label' => array('type' => 'string', 'null' => true, 'default' => null),
		'type' => array('type' => 'string', 'null' => true, 'length' => 45),
		'info' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'category_id' => array('type' => 'integer', 'null' => true),
		'required' => array('type' => 'boolean', 'null' => true),
		'depends_on' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'depends_value' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'order' => array('type' => 'integer', 'null' => true),
		'options' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'form_field_group_id' => array('type' => 'integer', 'null' => true),
		'is_deletable' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_dynamic_field' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_active' => array('type' => 'boolean', 'null' => true),
		'is_show_display_text_field' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_editable' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_show_in_search' => array('type' => 'boolean', 'null' => true),
		'is_show_in_request_form' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'form_fields_category_id_idx' => array('unique' => false, 'column' => 'category_id'),
			'form_fields_form_field_group_id_idx' => array('unique' => false, 'column' => 'form_field_group_id')
		),
		'tableParameters' => array()
	);
	public $genders = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $habits = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'name_es' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $habits_user_profiles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'habit_id' => array('type' => 'integer', 'null' => true),
		'user_profile_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'habits_user_profiles_habit_id_idx' => array('unique' => false, 'column' => 'habit_id'),
			'habits_user_profiles_user_profile_id_idx' => array('unique' => false, 'column' => 'user_profile_id')
		),
		'tableParameters' => array()
	);
	public $halls = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => false),
		'slug' => array('type' => 'string', 'null' => false),
		'partition_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'seat_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'is_active' => array('type' => 'boolean', 'null' => false),
		'custom_price_per_night_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'halls_user_id' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $ips = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'ip' => array('type' => 'string', 'null' => true, 'default' => null),
		'host' => array('type' => 'string', 'null' => true, 'default' => null),
		'city_id' => array('type' => 'integer', 'null' => true),
		'state_id' => array('type' => 'integer', 'null' => true),
		'country_id' => array('type' => 'integer', 'null' => true),
		'timezone_id' => array('type' => 'integer', 'null' => true),
		'latitude' => array('type' => 'float', 'null' => true),
		'longitude' => array('type' => 'float', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'ips_city_id_idx' => array('unique' => false, 'column' => 'city_id'),
			'ips_country_id_idx' => array('unique' => false, 'column' => 'country_id'),
			'ips_state_id_idx' => array('unique' => false, 'column' => 'state_id'),
			'ips_timezone_id_idx' => array('unique' => false, 'column' => 'timezone_id')
		),
		'tableParameters' => array()
	);
	public $item_favorites = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'item_favorites_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'item_favorites_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'item_favorites_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $item_feedbacks = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'item_user_id' => array('type' => 'integer', 'null' => true),
		'feedback' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'admin_comments' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'is_satisfied' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_auto_review' => array('type' => 'boolean', 'null' => true),
		'video_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'item_feedbacks_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'item_feedbacks_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'item_feedbacks_item_user_id_idx' => array('unique' => false, 'column' => 'item_user_id')
		),
		'tableParameters' => array()
	);
	public $item_flag_categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
		'item_flag_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'name_es' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'item_flag_categories_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $item_flags = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'item_flag_category_id' => array('type' => 'integer', 'null' => true),
		'message' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'item_flags_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'item_flags_item_flag_category_id_idx' => array('unique' => false, 'column' => 'item_flag_category_id'),
			'item_flags_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'item_flags_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $item_ratings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'rating' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'item_ratings_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'item_ratings_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'item_ratings_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $item_user_feedbacks = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'host_user_id' => array('type' => 'integer', 'null' => true),
		'booker_user_id' => array('type' => 'integer', 'null' => true),
		'item_user_id' => array('type' => 'integer', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'feedback' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'admin_comments' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'is_satisfied' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_auto_review' => array('type' => 'boolean', 'null' => true),
		'video_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'item_user_feedbacks_booker_user_id_idx' => array('unique' => false, 'column' => 'booker_user_id'),
			'item_user_feedbacks_host_user_id_idx' => array('unique' => false, 'column' => 'host_user_id'),
			'item_user_feedbacks_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'item_user_feedbacks_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'item_user_feedbacks_item_user_id_idx' => array('unique' => false, 'column' => 'item_user_id')
		),
		'tableParameters' => array()
	);
	public $item_user_statuses = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'item_user_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 265),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'item_user_statuses_slug_idx' => array('unique' => false, 'column' => 'slug')
		),
		'tableParameters' => array()
	);
	public $item_users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'item_user_status_id' => array('type' => 'integer', 'null' => true),
		'owner_user_id' => array('type' => 'integer', 'null' => true),
		'custom_price_per_night_id' => array('type' => 'integer', 'null' => true),
		'coupon_id' => array('type' => 'integer', 'null' => true),
		'guests' => array('type' => 'integer', 'null' => true),
		'price' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'quantity' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'original_price' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'reason_for_cancellation' => array('type' => 'string', 'null' => true, 'default' => null),
		'affiliate_commission_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'booker_service_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'host_service_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'original_search_address' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 300),
		'additional_fee_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'coupon_discount_amont' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'payment_gateway_id' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'from' => array('type' => 'datetime', 'null' => true),
		'to' => array('type' => 'datetime', 'null' => true),
		'referred_by_user_id' => array('type' => 'integer', 'null' => true),
		'is_host_reviewed' => array('type' => 'boolean', 'null' => true),
		'accepted_date' => array('type' => 'date', 'null' => true),
		'top_code' => array('type' => 'string', 'null' => true, 'default' => null),
		'bottom_code' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_payment_cleared' => array('type' => 'boolean', 'null' => true),
		'is_booking_request' => array('type' => 'boolean', 'null' => true),
		'message' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'is_booking_requested' => array('type' => 'boolean', 'null' => true),
		'message_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sudopay_gateway_id' => array('type' => 'integer', 'null' => true),
		'sudopay_payment_id' => array('type' => 'integer', 'null' => true),
		'sudopay_pay_key' => array('type' => 'string', 'null' => true, 'default' => null),
		'host_private_note' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'booker_private_note' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'is_seating_selection' => array('type' => 'boolean', 'null' => true),
		'seat_selection_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'item_users_1_idx' => array('unique' => false, 'column' => array('sudopay_gateway_id', 'sudopay_pay_key')),
			'item_users_coupon_id' => array('unique' => false, 'column' => 'coupon_id'),
			'item_users_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'item_users_item_user_status_id_idx' => array('unique' => false, 'column' => 'item_user_status_id'),
			'item_users_owner_user_id_idx' => array('unique' => false, 'column' => 'owner_user_id'),
			'item_users_payment_gateway_id_idx' => array('unique' => false, 'column' => 'payment_gateway_id'),
			'item_users_referred_by_user_id_idx' => array('unique' => false, 'column' => 'referred_by_user_id'),
			'item_users_sudopay_payment_id_idx' => array('unique' => false, 'column' => 'sudopay_payment_id'),
			'item_users_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $item_views = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'item_views_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'item_views_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'item_views_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $items = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'city_id' => array('type' => 'integer', 'null' => true),
		'state_id' => array('type' => 'integer', 'null' => true),
		'country_id' => array('type' => 'integer', 'null' => true),
		'language_id' => array('type' => 'integer', 'null' => true),
		'category_id' => array('type' => 'integer', 'null' => true),
		'category_type_id' => array('type' => 'integer', 'null' => true),
		'item_type_id' => array('type' => 'integer', 'null' => true),
		'title' => array('type' => 'string', 'null' => true, 'default' => null),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 265),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'street_view' => array('type' => 'integer', 'null' => true),
		'accommodates' => array('type' => 'integer', 'null' => true),
		'address' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 300),
		'address1' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 300),
		'unit' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 256),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'item_flag_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'price_per_hour' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'price_per_day' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'price_per_week' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'price_per_month' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'additional_guest' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'additional_guest_price' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'backup_phone' => array('type' => 'string', 'null' => true, 'default' => null),
		'measurement' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 256),
		'verified_date' => array('type' => 'datetime', 'null' => true),
		'latitude' => array('type' => 'float', 'null' => true),
		'longitude' => array('type' => 'float', 'null' => true),
		'zoom_level' => array('type' => 'integer', 'null' => true),
		'actual_rating' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'mean_rating' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'detected_suspicious_words' => array('type' => 'string', 'null' => true, 'default' => null),
		'price_currency' => array('type' => 'float', 'null' => true),
		'custom_price_per_night_count' => array('type' => 'integer', 'null' => true),
		'item_user_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sales_cleared_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'sales_cleared_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sales_pending_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sales_pipeline_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'sales_pipeline_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sales_completed_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sales_rejected_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sales_canceled_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sales_expired_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sales_lost_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'sales_lost_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'item_view_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'request_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'revenue' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'item_favorite_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'positive_feedback_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'item_feedback_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'referred_booking_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'negotiation_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'in_collection_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'admin_suspend' => array('type' => 'boolean', 'null' => true),
		'is_system_flagged' => array('type' => 'boolean', 'null' => true),
		'is_user_flagged' => array('type' => 'boolean', 'null' => true),
		'is_featured' => array('type' => 'boolean', 'null' => true),
		'is_active' => array('type' => 'boolean', 'null' => true),
		'is_approved' => array('type' => 'boolean', 'null' => true),
		'is_available' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_paid' => array('type' => 'boolean', 'null' => true),
		'video_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'item_payment_gateway_id' => array('type' => 'integer', 'null' => true),
		'item_sudopay_payment_id' => array('type' => 'integer', 'null' => true),
		'item_sudopay_pay_key' => array('type' => 'string', 'null' => true, 'default' => null),
		'item_sudopay_gateway_id' => array('type' => 'integer', 'null' => true),
		'item_sudopay_revised_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'item_sudopay_token' => array('type' => 'string', 'null' => true, 'default' => null),
		'item_fee' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'is_have_definite_time' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_user_can_request' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_free' => array('type' => 'boolean', 'null' => true),
		'is_auto_approve' => array('type' => 'boolean', 'null' => true),
		'is_tipping_point' => array('type' => 'boolean', 'null' => true),
		'min_number_of_ticket' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_buyer_as_fee_payer' => array('type' => 'boolean', 'null' => true),
		'is_additional_fee_to_buyer' => array('type' => 'boolean', 'null' => true),
		'additional_fee_name' => array('type' => 'string', 'null' => true, 'default' => null),
		'additional_fee_percentage' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'is_people_can_book_my_time' => array('type' => 'boolean', 'null' => true),
		'is_sell_ticket' => array('type' => 'boolean', 'null' => true),
		'minimum_price' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'is_completed' => array('type' => 'boolean', 'null' => true),
		'custom_source_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'items_city_id_idx' => array('unique' => false, 'column' => 'city_id'),
			'items_country_id_idx' => array('unique' => false, 'column' => 'country_id'),
			'items_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'items_item_payment_gateway_id_idx' => array('unique' => false, 'column' => 'item_payment_gateway_id'),
			'items_item_sudopay_gateway_id_idx' => array('unique' => false, 'column' => 'item_sudopay_gateway_id'),
			'items_item_sudopay_pay_key_idx' => array('unique' => false, 'column' => 'item_sudopay_pay_key'),
			'items_item_sudopay_payment_id_idx' => array('unique' => false, 'column' => 'item_sudopay_payment_id'),
			'items_language_id_idx' => array('unique' => false, 'column' => 'language_id'),
			'items_slug_idx' => array('unique' => false, 'column' => 'slug'),
			'items_state_id_idx' => array('unique' => false, 'column' => 'state_id'),
			'items_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $items_requests = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'request_id' => array('type' => 'integer', 'null' => true),
		'order_id' => array('type' => 'integer', 'null' => true),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'items_requests_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'items_requests_order_id_idx' => array('unique' => false, 'column' => 'order_id'),
			'items_requests_request_id_idx' => array('unique' => false, 'column' => 'request_id')
		),
		'tableParameters' => array()
	);
	public $languages = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'iso2' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5),
		'iso3' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 5),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'languages_name_idx' => array('unique' => false, 'column' => 'name')
		),
		'tableParameters' => array()
	);
	public $message_contents = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'subject' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'message' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'admin_suspend' => array('type' => 'boolean', 'null' => true),
		'is_system_flagged' => array('type' => 'boolean', 'null' => true),
		'detected_suspicious_words' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $messages = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'other_user_id' => array('type' => 'integer', 'null' => true),
		'parent_message_id' => array('type' => 'integer', 'null' => true),
		'message_content_id' => array('type' => 'integer', 'null' => true),
		'message_folder_id' => array('type' => 'integer', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'item_user_id' => array('type' => 'integer', 'null' => true),
		'is_sender' => array('type' => 'boolean', 'null' => true),
		'is_starred' => array('type' => 'boolean', 'null' => true),
		'is_read' => array('type' => 'boolean', 'null' => true),
		'is_deleted' => array('type' => 'boolean', 'null' => true),
		'is_archived' => array('type' => 'boolean', 'null' => true),
		'is_review' => array('type' => 'boolean', 'null' => true),
		'is_communication' => array('type' => 'boolean', 'null' => true),
		'hash' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'size' => array('type' => 'integer', 'null' => true),
		'item_user_status_id' => array('type' => 'integer', 'null' => true),
		'request_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'messages_item_id_idx' => array('unique' => false, 'column' => 'item_id'),
			'messages_item_user_id_idx' => array('unique' => false, 'column' => 'item_user_id'),
			'messages_item_user_status_id_idx' => array('unique' => false, 'column' => 'item_user_status_id'),
			'messages_message_content_id_idx' => array('unique' => false, 'column' => 'message_content_id'),
			'messages_message_folder_id_idx' => array('unique' => false, 'column' => 'message_folder_id'),
			'messages_other_user_id_idx' => array('unique' => false, 'column' => 'other_user_id'),
			'messages_parent_message_id_idx' => array('unique' => false, 'column' => 'parent_message_id'),
			'messages_request_id_idx' => array('unique' => false, 'column' => 'request_id'),
			'messages_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $money_transfer_accounts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'payment_gateway_id' => array('type' => 'integer', 'null' => true),
		'account' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'is_default' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'money_transfer_accounts_payment_gateway_id_idx' => array('unique' => false, 'column' => 'payment_gateway_id'),
			'money_transfer_accounts_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $pages = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true),
		'category_id' => array('type' => 'integer', 'null' => true),
		'title' => array('type' => 'string', 'null' => true, 'default' => null),
		'content' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'template' => array('type' => 'string', 'null' => true, 'default' => null),
		'draft' => array('type' => 'boolean', 'null' => true),
		'lft' => array('type' => 'integer', 'null' => true),
		'rght' => array('type' => 'integer', 'null' => true),
		'level' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'meta_keywords' => array('type' => 'string', 'null' => true, 'default' => null),
		'meta_description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'url' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_default' => array('type' => 'boolean', 'null' => true),
		'title_es' => array('type' => 'string', 'null' => true, 'default' => null),
		'content_es' => array('type' => 'text', 'null' => true, 'default' => null, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'pages_category_id' => array('unique' => false, 'column' => 'category_id'),
			'pages_parent_id_idx' => array('unique' => false, 'column' => 'parent_id'),
			'pages_slug_idx' => array('unique' => false, 'column' => 'slug'),
			'pages_title_idx' => array('unique' => false, 'column' => 'title')
		),
		'tableParameters' => array()
	);
	public $partitions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'hall_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => false),
		'slug' => array('type' => 'string', 'null' => false),
		'no_of_rows' => array('type' => 'integer', 'null' => false),
		'no_of_columns' => array('type' => 'integer', 'null' => false),
		'seat_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'stage_position' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'seating_direction' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'seating_name_type' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'is_active' => array('type' => 'boolean', 'null' => false),
		'custom_price_per_type_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'partitions_hall_id' => array('unique' => false, 'column' => 'hall_id'),
			'partitions_user_id' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $payment_gateway_settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'payment_gateway_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 256),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 8),
		'options' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'test_mode_value' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'live_mode_value' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'payment_gateway_settings_payment_gateway_id_idx' => array('unique' => false, 'column' => 'payment_gateway_id')
		),
		'tableParameters' => array()
	);
	public $payment_gateways = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'display_name' => array('type' => 'string', 'null' => true, 'default' => null),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'gateway_fees' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'transaction_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'payment_gateway_setting_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_mass_pay_enabled' => array('type' => 'boolean', 'null' => true),
		'is_test_mode' => array('type' => 'boolean', 'null' => true),
		'is_active' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $persistent_logins = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'series' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
		'token' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
		'expires' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'persistent_logins_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'persistent_logins_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $private_addresses = array(
		'address_prefix' => array('type' => 'string', 'null' => false, 'length' => 11),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'address_prefix')
		),
		'tableParameters' => array()
	);
	public $request_favorites = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'request_id' => array('type' => 'integer', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'request_favorites_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'request_favorites_request_id_idx' => array('unique' => false, 'column' => 'request_id'),
			'request_favorites_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $request_flag_categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'date', 'null' => true),
		'modified' => array('type' => 'date', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
		'request_flag_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'name_es' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'request_flag_categories_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $request_flags = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'request_id' => array('type' => 'integer', 'null' => true),
		'request_flag_category_id' => array('type' => 'integer', 'null' => true),
		'message' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'request_flags_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'request_flags_request_flag_category_id_idx' => array('unique' => false, 'column' => 'request_flag_category_id'),
			'request_flags_request_id_idx' => array('unique' => false, 'column' => 'request_id'),
			'request_flags_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $request_views = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'request_id' => array('type' => 'integer', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'request_views_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'request_views_request_id_idx' => array('unique' => false, 'column' => 'request_id'),
			'request_views_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $requests = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'city_id' => array('type' => 'integer', 'null' => true),
		'state_id' => array('type' => 'integer', 'null' => true),
		'country_id' => array('type' => 'integer', 'null' => true),
		'currency_id' => array('type' => 'integer', 'null' => true),
		'category_id' => array('type' => 'integer', 'null' => true),
		'category_type_id' => array('type' => 'integer', 'null' => true),
		'title' => array('type' => 'string', 'null' => true, 'default' => null),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'address' => array('type' => 'string', 'null' => true, 'default' => null),
		'accommodates' => array('type' => 'integer', 'null' => true),
		'latitude' => array('type' => 'float', 'null' => true),
		'longitude' => array('type' => 'float', 'null' => true),
		'zoom_level' => array('type' => 'integer', 'null' => true, 'default' => '5'),
		'from' => array('type' => 'date', 'null' => true),
		'to' => array('type' => 'date', 'null' => true),
		'price' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_alert' => array('type' => 'boolean', 'null' => true),
		'is_user_flagged' => array('type' => 'boolean', 'null' => true),
		'admin_suspend' => array('type' => 'boolean', 'null' => true),
		'detected_suspicious_words' => array('type' => 'string', 'null' => true, 'default' => null),
		'item_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'request_flag_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'request_view_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'request_favorite_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_approved' => array('type' => 'boolean', 'null' => true),
		'is_system_flagged' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'requests_category_id' => array('unique' => false, 'column' => 'category_id'),
			'requests_category_type_id' => array('unique' => false, 'column' => 'category_type_id'),
			'requests_city_id_idx' => array('unique' => false, 'column' => 'city_id'),
			'requests_country_id_idx' => array('unique' => false, 'column' => 'country_id'),
			'requests_currency_id_idx' => array('unique' => false, 'column' => 'currency_id'),
			'requests_slug_idx' => array('unique' => false, 'column' => 'slug'),
			'requests_state_id_idx' => array('unique' => false, 'column' => 'state_id'),
			'requests_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $revisions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 15),
		'node_id' => array('type' => 'integer', 'null' => true),
		'content' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'revision_number' => array('type' => 'integer', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'created' => array('type' => 'datetime', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'revisions_node_id_idx' => array('unique' => false, 'column' => 'node_id'),
			'revisions_revision_number_idx' => array('unique' => false, 'column' => 'revision_number'),
			'revisions_type_idx' => array('unique' => false, 'column' => 'type'),
			'revisions_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $roles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'roles_name_idx' => array('unique' => false, 'column' => 'name')
		),
		'tableParameters' => array()
	);
	public $search_keywords = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'keyword' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'search_log_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $search_logs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'search_keyword_id' => array('type' => 'integer', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'type' => array('type' => 'integer', 'null' => true, 'default' => '6'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'search_logs_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'search_logs_search_keyword_id_idx' => array('unique' => false, 'column' => 'search_keyword_id'),
			'search_logs_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $seat_statuses = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => false),
		'color_code' => array('type' => 'string', 'null' => false, 'length' => 50),
		'indexes' => array(
			
		),
		'tableParameters' => array()
	);
	public $seats = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'hall_id' => array('type' => 'integer', 'null' => true),
		'partition_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => false),
		'row' => array('type' => 'integer', 'null' => false),
		'column' => array('type' => 'integer', 'null' => false),
		'seat_status_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
		'position' => array('type' => 'integer', 'null' => false),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'seats_hall_id' => array('unique' => false, 'column' => 'hall_id'),
			'seats_partition_id' => array('unique' => false, 'column' => 'partition_id')
		),
		'tableParameters' => array()
	);
	public $security_questions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'name_es' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'security_questions_name_idx' => array('unique' => false, 'column' => 'name'),
			'security_questions_slug_idx' => array('unique' => false, 'column' => 'slug')
		),
		'tableParameters' => array()
	);
	public $setting_categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'plugin_name' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'setting_categories_name_idx' => array('unique' => false, 'column' => 'name'),
			'setting_categories_parent_id_idx' => array('unique' => false, 'column' => 'parent_id')
		),
		'tableParameters' => array()
	);
	public $settings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'setting_category_id' => array('type' => 'integer', 'null' => true),
		'setting_category_parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'value' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 8),
		'options' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'label' => array('type' => 'string', 'null' => true, 'default' => null),
		'order' => array('type' => 'integer', 'null' => true),
		'plugin_name' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'settings_name_idx' => array('unique' => false, 'column' => 'name'),
			'settings_setting_category_id_idx' => array('unique' => false, 'column' => 'setting_category_id'),
			'settings_setting_category_parent_id_idx' => array('unique' => false, 'column' => 'setting_category_parent_id')
		),
		'tableParameters' => array()
	);
	public $site_categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'slug' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 265),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'site_categories_slug_idx' => array('unique' => false, 'column' => 'slug')
		),
		'tableParameters' => array()
	);
	public $social_contact_details = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
		'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
		'facebook_user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
		'twitter_user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
		'googleplus_user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
		'angellist_user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
		'linkedin_user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 150),
		'social_contact_count' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'social_contact_details_angellist_user_id_idx' => array('unique' => false, 'column' => 'angellist_user_id'),
			'social_contact_details_facebook_user_id_idx' => array('unique' => false, 'column' => 'facebook_user_id'),
			'social_contact_details_googleplus_user_id_idx' => array('unique' => false, 'column' => 'googleplus_user_id'),
			'social_contact_details_linkedin_user_id_idx' => array('unique' => false, 'column' => 'linkedin_user_id'),
			'social_contact_details_twitter_user_id_idx' => array('unique' => false, 'column' => 'twitter_user_id')
		),
		'tableParameters' => array()
	);
	public $social_contacts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'social_source_id' => array('type' => 'integer', 'null' => true),
		'social_contact_detail_id' => array('type' => 'integer', 'null' => true),
		'social_user_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'social_contacts_social_contact_detail_id_idx' => array('unique' => false, 'column' => 'social_contact_detail_id'),
			'social_contacts_social_source_id_idx' => array('unique' => false, 'column' => 'social_source_id'),
			'social_contacts_social_user_id_idx' => array('unique' => false, 'column' => 'social_user_id'),
			'social_contacts_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $states = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'country_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 45),
		'code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 8),
		'adm1code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 4),
		'is_approved' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'states_country_id_idx' => array('unique' => false, 'column' => 'country_id')
		),
		'tableParameters' => array()
	);
	public $submission_fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'submission_id' => array('type' => 'integer', 'null' => true),
		'form_field_id' => array('type' => 'integer', 'null' => true),
		'form_field' => array('type' => 'string', 'null' => true),
		'response' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'type' => array('type' => 'string', 'null' => true, 'length' => 50),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'submission_fields_form_field_id_idx' => array('unique' => false, 'column' => 'form_field_id'),
			'submission_fields_submission_id_idx' => array('unique' => false, 'column' => 'submission_id')
		),
		'tableParameters' => array()
	);
	public $submissions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'category_id' => array('type' => 'integer', 'null' => true),
		'item_id' => array('type' => 'integer', 'null' => true),
		'request_id' => array('type' => 'integer', 'null' => true),
		'ip' => array('type' => 'integer', 'null' => true),
		'email' => array('type' => 'string', 'null' => true),
		'page' => array('type' => 'string', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'submissions_category_id_idx' => array('unique' => false, 'column' => 'category_id'),
			'submissions_item_id_idx' => array('unique' => false, 'column' => 'item_id')
		),
		'tableParameters' => array()
	);
	public $subscriptions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'email' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'is_subscribed' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'unsubscribed_on' => array('type' => 'date', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'invite_hash' => array('type' => 'string', 'null' => true, 'default' => null),
		'site_state_id' => array('type' => 'integer', 'null' => true),
		'is_sent_private_beta_mail' => array('type' => 'boolean', 'null' => true),
		'is_social_like' => array('type' => 'boolean', 'null' => true),
		'is_invite' => array('type' => 'boolean', 'null' => true),
		'invite_user_id' => array('type' => 'integer', 'null' => true),
		'is_email_verified' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'subscriptions_email_idx' => array('unique' => false, 'column' => 'email'),
			'subscriptions_invite_user_id_idx' => array('unique' => false, 'column' => 'invite_user_id'),
			'subscriptions_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'subscriptions_site_state_id_idx' => array('unique' => false, 'column' => 'site_state_id'),
			'subscriptions_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $sudopay_ipn_logs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'ip' => array('type' => 'integer', 'null' => true),
		'post_variable' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $sudopay_payment_gateways = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'sudopay_gateway_name' => array('type' => 'string', 'null' => true, 'default' => null),
		'sudopay_gateway_id' => array('type' => 'integer', 'null' => true),
		'sudopay_payment_group_id' => array('type' => 'integer', 'null' => true),
		'sudopay_gateway_details' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'is_marketplace_supported' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'sudopay_payment_gateways_sudopay_gateway_id_idx' => array('unique' => false, 'column' => 'sudopay_gateway_id'),
			'sudopay_payment_gateways_sudopay_payment_group_id_idx' => array('unique' => false, 'column' => 'sudopay_payment_group_id')
		),
		'tableParameters' => array()
	);
	public $sudopay_payment_gateways_users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'sudopay_payment_gateway_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'sudopay_payment_gateways_users_sudopay_payment_gateway_id_idx' => array('unique' => false, 'column' => 'sudopay_payment_gateway_id'),
			'sudopay_payment_gateways_users_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $sudopay_payment_groups = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'sudopay_group_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200),
		'thumb_url' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'sudopay_payment_groups_sudopay_group_id_idx' => array('unique' => false, 'column' => 'sudopay_group_id')
		),
		'tableParameters' => array()
	);
	public $sudopay_transaction_logs = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'payment_id' => array('type' => 'integer', 'null' => true),
		'class' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
		'foreign_id' => array('type' => 'integer', 'null' => true),
		'sudopay_pay_key' => array('type' => 'string', 'null' => true, 'default' => null),
		'merchant_id' => array('type' => 'integer', 'null' => true),
		'gateway_id' => array('type' => 'integer', 'null' => true),
		'gateway_name' => array('type' => 'string', 'null' => true, 'default' => null),
		'status' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
		'payment_type' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
		'buyer_id' => array('type' => 'integer', 'null' => true),
		'buyer_email' => array('type' => 'string', 'null' => true, 'default' => null),
		'buyer_address' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'sudopay_transaction_logs_buyer_id_idx' => array('unique' => false, 'column' => 'buyer_id'),
			'sudopay_transaction_logs_class_idx' => array('unique' => false, 'column' => 'class'),
			'sudopay_transaction_logs_foreign_id_idx' => array('unique' => false, 'column' => 'foreign_id'),
			'sudopay_transaction_logs_gateway_id_idx' => array('unique' => false, 'column' => 'gateway_id'),
			'sudopay_transaction_logs_merchant_id_idx' => array('unique' => false, 'column' => 'merchant_id'),
			'sudopay_transaction_logs_payment_id_idx' => array('unique' => false, 'column' => 'payment_id')
		),
		'tableParameters' => array()
	);
	public $timezones = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'gmt_offset' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 10),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $transaction_types = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_credit' => array('type' => 'boolean', 'null' => true),
		'is_credit_to_receiver' => array('type' => 'boolean', 'null' => true),
		'is_credit_to_admin' => array('type' => 'boolean', 'null' => true),
		'message' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'message_for_receiver' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'message_for_admin' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'transaction_variables' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $transactions = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'receiver_user_id' => array('type' => 'integer', 'null' => true),
		'foreign_id' => array('type' => 'integer', 'null' => true),
		'class' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 25),
		'transaction_type_id' => array('type' => 'integer', 'null' => true),
		'amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'payment_gateway_id' => array('type' => 'integer', 'null' => true),
		'gateway_fees' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'transactions_class_idx' => array('unique' => false, 'column' => 'class'),
			'transactions_foreign_id_idx' => array('unique' => false, 'column' => 'foreign_id'),
			'transactions_payment_gateway_id_idx' => array('unique' => false, 'column' => 'payment_gateway_id'),
			'transactions_receiver_user_id_idx' => array('unique' => false, 'column' => 'receiver_user_id'),
			'transactions_transaction_type_id_idx' => array('unique' => false, 'column' => 'transaction_type_id'),
			'transactions_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $translations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'language_id' => array('type' => 'integer', 'null' => true),
		'name' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'lang_text' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'is_translated' => array('type' => 'boolean', 'null' => true),
		'is_google_translate' => array('type' => 'boolean', 'null' => true),
		'is_verified' => array('type' => 'boolean', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $user_add_wallet_amounts = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'payment_gateway_id' => array('type' => 'integer', 'null' => true),
		'is_success' => array('type' => 'boolean', 'null' => true),
		'user_paypal_connection_id' => array('type' => 'integer', 'null' => true),
		'description' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'sudopay_gateway_id' => array('type' => 'integer', 'null' => true),
		'sudopay_payment_id' => array('type' => 'integer', 'null' => true),
		'sudopay_pay_key' => array('type' => 'string', 'null' => true, 'default' => null),
		'sudopay_revised_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'sudopay_token' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_add_wallet_amounts_1_idx' => array('unique' => false, 'column' => array('sudopay_gateway_id', 'sudopay_pay_key')),
			'user_add_wallet_amounts_payment_gateway_id_idx' => array('unique' => false, 'column' => 'payment_gateway_id'),
			'user_add_wallet_amounts_sudopay_payment_id_idx' => array('unique' => false, 'column' => 'sudopay_payment_id'),
			'user_add_wallet_amounts_user_id_idx' => array('unique' => false, 'column' => 'user_id'),
			'user_add_wallet_amounts_user_paypal_connection_id_idx' => array('unique' => false, 'column' => 'user_paypal_connection_id')
		),
		'tableParameters' => array()
	);
	public $user_cash_withdrawals = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'withdrawal_status_id' => array('type' => 'integer', 'null' => true),
		'amount' => array('type' => 'float', 'null' => true),
		'remark' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_cash_withdrawals_user_id_idx' => array('unique' => false, 'column' => 'user_id'),
			'user_cash_withdrawals_withdrawal_status_id_idx' => array('unique' => false, 'column' => 'withdrawal_status_id')
		),
		'tableParameters' => array()
	);
	public $user_comments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'posted_user_id' => array('type' => 'integer', 'null' => true),
		'comment' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_comments_posted_user_id_idx' => array('unique' => false, 'column' => 'posted_user_id'),
			'user_comments_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $user_educations = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'education' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'education_es' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $user_employments = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'employment' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'employment_es' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $user_facebook_friends = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'facebook_friend_id' => array('type' => 'integer', 'null' => true),
		'facebook_friend_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_facebook_friends_facebook_friend_id_idx' => array('unique' => false, 'column' => 'facebook_friend_id'),
			'user_facebook_friends_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $user_followers = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'followed_user_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_followers_followed_user_id_idx' => array('unique' => false, 'column' => 'followed_user_id'),
			'user_followers_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $user_income_ranges = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'income' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $user_logins = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'persistent_login_id' => array('type' => 'integer', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'user_agent' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 500),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_logins_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'user_logins_persistent_login_id_idx' => array('unique' => false, 'column' => 'persistent_login_id'),
			'user_logins_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $user_notifications = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'is_new_item_order_host_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_new_item_order_booker_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_accept_item_order_booker_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_accept_item_order_host_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_reject_item_order_booker_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_reject_item_order_host_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_cancel_item_order_host_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_cancel_item_order_booker_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_review_item_order_booker_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_review_item_order_host_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_payment_cleared_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_complete_item_order_host_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_complete_item_order_booker_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_expire_item_order_host_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_expire_item_order_booker_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_admin_cancel_item_order_host_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_admin_cancel_booker_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_contact_notification' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_notifications_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $user_openids = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'openid' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_openids_user_id_idx' => array('unique' => false, 'column' => 'user_id')
		),
		'tableParameters' => array()
	);
	public $user_profiles = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'language_id' => array('type' => 'integer', 'null' => true),
		'first_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'last_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'middle_name' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'gender_id' => array('type' => 'integer', 'null' => true),
		'dob' => array('type' => 'date', 'null' => true),
		'about_me' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'user_education_id' => array('type' => 'integer', 'null' => true),
		'user_employment_id' => array('type' => 'integer', 'null' => true),
		'user_income_range_id' => array('type' => 'integer', 'null' => true),
		'user_relationship_id' => array('type' => 'integer', 'null' => true),
		'address' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 500),
		'city_id' => array('type' => 'integer', 'null' => true),
		'state_id' => array('type' => 'integer', 'null' => true),
		'country_id' => array('type' => 'integer', 'null' => true),
		'zip_code' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
		'backup_phone' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 250),
		'message_page_size' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'message_signature' => array('type' => 'text', 'null' => true, 'length' => 1073741824),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_profiles_city_id_idx' => array('unique' => false, 'column' => 'city_id'),
			'user_profiles_country_id_idx' => array('unique' => false, 'column' => 'country_id'),
			'user_profiles_gender_id_idx' => array('unique' => false, 'column' => 'gender_id'),
			'user_profiles_language_id_idx' => array('unique' => false, 'column' => 'language_id'),
			'user_profiles_state_id_idx' => array('unique' => false, 'column' => 'state_id'),
			'user_profiles_user_education_id_idx' => array('unique' => false, 'column' => 'user_education_id'),
			'user_profiles_user_employment_id_idx' => array('unique' => false, 'column' => 'user_employment_id'),
			'user_profiles_user_id_idx' => array('unique' => false, 'column' => 'user_id'),
			'user_profiles_user_income_range_id_idx' => array('unique' => false, 'column' => 'user_income_range_id'),
			'user_profiles_user_relationship_id_idx' => array('unique' => false, 'column' => 'user_relationship_id')
		),
		'tableParameters' => array()
	);
	public $user_relationships = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'relationship' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_active' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'relationship_es' => array('type' => 'string', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
	public $user_views = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'user_id' => array('type' => 'integer', 'null' => true),
		'viewing_user_id' => array('type' => 'integer', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'user_views_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'user_views_user_id_idx' => array('unique' => false, 'column' => 'user_id'),
			'user_views_viewing_user_id_idx' => array('unique' => false, 'column' => 'viewing_user_id')
		),
		'tableParameters' => array()
	);
	public $users = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'role_id' => array('type' => 'integer', 'null' => true),
		'attachment_id' => array('type' => 'integer', 'null' => true, 'default' => '1'),
		'username' => array('type' => 'string', 'null' => true, 'default' => null),
		'email' => array('type' => 'string', 'null' => true, 'default' => null),
		'password' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 100),
		'available_wallet_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'blocked_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'cleared_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'total_amount_withdrawn' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'total_withdraw_request_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'total_amount_deposited' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'item_feedback_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_expired_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_canceled_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_rejected_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_completed_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_review_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_confirmed_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_waiting_for_acceptance_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_booking_request_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_payment_cleared_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_total_booked_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_total_lost_booked_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'host_total_earned_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'host_total_lost_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'host_total_pipeline_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'host_total_site_revenue' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'booking_expired_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_rejected_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_canceled_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_review_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_completed_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_confirmed_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_payment_pending_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_waiting_for_acceptance_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booker_booking_request_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_payment_cleared_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booker_positive_feedback_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booker_item_user_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_total_booked_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_total_booked_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'booking_total_lost_booked_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'booking_total_site_revenue' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'positive_feedback_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'item_favorite_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'item_user_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'request_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'item_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'item_pending_approval_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'item_inactive_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'referred_by_user_id' => array('type' => 'integer', 'null' => true),
		'referred_by_user_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'referred_booking_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'affiliate_refer_booking_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'user_referred_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'total_commission_pending_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'total_commission_canceled_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'total_commission_completed_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'commission_line_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'commission_withdraw_request_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'commission_paid_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'user_login_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'user_view_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'user_friend_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'user_comment_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'cookie_hash' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 50),
		'cookie_time_modified' => array('type' => 'datetime', 'null' => true),
		'is_agree_terms_conditions' => array('type' => 'boolean', 'null' => true),
		'is_active' => array('type' => 'boolean', 'null' => true),
		'is_email_confirmed' => array('type' => 'boolean', 'null' => true),
		'is_affiliate_user' => array('type' => 'boolean', 'null' => true),
		'ip_id' => array('type' => 'integer', 'null' => true),
		'last_login_ip_id' => array('type' => 'integer', 'null' => true),
		'last_logged_in_time' => array('type' => 'datetime', 'null' => true),
		'user_facebook_friend_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_show_facebook_friends' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_facebook_friends_fetched' => array('type' => 'boolean', 'null' => true),
		'network_fb_user_id' => array('type' => 'integer', 'null' => true),
		'last_facebook_friend_fetched_date' => array('type' => 'date', 'null' => true),
		'is_paid' => array('type' => 'boolean', 'null' => true),
		'security_question_id' => array('type' => 'integer', 'null' => true),
		'security_answer' => array('type' => 'string', 'null' => true, 'default' => null),
		'is_facebook_register' => array('type' => 'boolean', 'null' => true),
		'is_twitter_register' => array('type' => 'boolean', 'null' => true),
		'is_google_register' => array('type' => 'boolean', 'null' => true),
		'is_googleplus_register' => array('type' => 'boolean', 'null' => true),
		'is_yahoo_register' => array('type' => 'boolean', 'null' => true),
		'is_linkedin_register' => array('type' => 'boolean', 'null' => true),
		'is_openid_register' => array('type' => 'boolean', 'null' => true),
		'facebook_user_id' => array('type' => 'integer', 'null' => true),
		'facebook_access_token' => array('type' => 'string', 'null' => true, 'default' => null),
		'twitter_user_id' => array('type' => 'integer', 'null' => true),
		'twitter_access_token' => array('type' => 'string', 'null' => true, 'default' => null),
		'twitter_access_key' => array('type' => 'integer', 'null' => true),
		'google_user_id' => array('type' => 'string', 'null' => true, 'default' => null),
		'google_access_token' => array('type' => 'string', 'null' => true, 'default' => null),
		'googleplus_user_id' => array('type' => 'string', 'null' => true, 'default' => null),
		'yahoo_user_id' => array('type' => 'string', 'null' => true, 'default' => null),
		'yahoo_access_token' => array('type' => 'string', 'null' => true, 'default' => null),
		'linkedin_user_id' => array('type' => 'string', 'null' => true, 'default' => null),
		'linkedin_access_token' => array('type' => 'string', 'null' => true, 'default' => null),
		'openid_user_id' => array('type' => 'string', 'null' => true, 'default' => null, 'length' => 200),
		'twitter_avatar_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'google_avatar_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'googleplus_avatar_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'linkedin_avatar_url' => array('type' => 'string', 'null' => true, 'default' => null),
		'facebook_friends_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'twitter_followers_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'google_contacts_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'googleplus_contacts_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'yahoo_contacts_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'linkedin_contacts_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'user_openid_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_skipped_fb' => array('type' => 'boolean', 'null' => true),
		'is_skipped_twitter' => array('type' => 'boolean', 'null' => true),
		'is_skipped_google' => array('type' => 'boolean', 'null' => true),
		'is_skipped_yahoo' => array('type' => 'boolean', 'null' => true),
		'is_skipped_linkedin' => array('type' => 'boolean', 'null' => true),
		'is_facebook_connected' => array('type' => 'boolean', 'null' => true),
		'is_twitter_connected' => array('type' => 'boolean', 'null' => true),
		'is_google_connected' => array('type' => 'boolean', 'null' => true),
		'is_googleplus_connected' => array('type' => 'boolean', 'null' => true),
		'is_yahoo_connected' => array('type' => 'boolean', 'null' => true),
		'is_linkedin_connected' => array('type' => 'boolean', 'null' => true),
		'site_state_id' => array('type' => 'integer', 'null' => true),
		'user_avatar_source_id' => array('type' => 'integer', 'null' => true),
		'mobile_app_hash' => array('type' => 'string', 'null' => true, 'default' => null),
		'mobile_app_time_modified' => array('type' => 'datetime', 'null' => true),
		'is_idle' => array('type' => 'boolean', 'null' => true, 'default' => true),
		'is_item_posted' => array('type' => 'boolean', 'null' => true),
		'is_requested' => array('type' => 'boolean', 'null' => true),
		'is_item_booked' => array('type' => 'boolean', 'null' => true),
		'invite_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'activity_message_id' => array('type' => 'integer', 'null' => true),
		'payment_gateway_id' => array('type' => 'integer', 'null' => true),
		'sudopay_pay_key' => array('type' => 'string', 'null' => true, 'default' => null),
		'sudopay_gateway_id' => array('type' => 'integer', 'null' => true),
		'sudopay_payment_id' => array('type' => 'integer', 'null' => true),
		'sudopay_revised_amount' => array('type' => 'float', 'null' => true, 'default' => '0.00'),
		'sudopay_token' => array('type' => 'string', 'null' => true, 'default' => null),
		'pwd_reset_token' => array('type' => 'string', 'null' => true),
		'pwd_reset_requested_date' => array('type' => 'datetime', 'null' => true),
		'sudopay_receiver_account_id' => array('type' => 'integer', 'null' => true),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id'),
			'users_1_idx' => array('unique' => false, 'column' => array('payment_gateway_id', 'sudopay_pay_key', 'sudopay_gateway_id')),
			'users_activity_message_id_idx' => array('unique' => false, 'column' => 'activity_message_id'),
			'users_attachment_id_idx' => array('unique' => false, 'column' => 'attachment_id'),
			'users_email_idx' => array('unique' => false, 'column' => 'email'),
			'users_facebook_user_id_idx' => array('unique' => false, 'column' => 'facebook_user_id'),
			'users_google_user_id_idx' => array('unique' => false, 'column' => 'google_user_id'),
			'users_googleplus_user_id_idx' => array('unique' => false, 'column' => 'googleplus_user_id'),
			'users_ip_id_idx' => array('unique' => false, 'column' => 'ip_id'),
			'users_last_login_ip_id_idx' => array('unique' => false, 'column' => 'last_login_ip_id'),
			'users_linkedin_user_id_idx' => array('unique' => false, 'column' => 'linkedin_user_id'),
			'users_network_fb_user_id_idx' => array('unique' => false, 'column' => 'network_fb_user_id'),
			'users_openid_user_id_idx' => array('unique' => false, 'column' => 'openid_user_id'),
			'users_referred_by_user_id_idx' => array('unique' => false, 'column' => 'referred_by_user_id'),
			'users_role_id_idx' => array('unique' => false, 'column' => 'role_id'),
			'users_security_question_id_idx' => array('unique' => false, 'column' => 'security_question_id'),
			'users_site_state_id_idx' => array('unique' => false, 'column' => 'site_state_id'),
			'users_sudopay_gateway_id_idx' => array('unique' => false, 'column' => 'sudopay_gateway_id'),
			'users_sudopay_payment_id_idx' => array('unique' => false, 'column' => 'sudopay_payment_id'),
			'users_twitter_user_id_idx' => array('unique' => false, 'column' => 'twitter_user_id'),
			'users_user_avatar_source_id_idx' => array('unique' => false, 'column' => 'user_avatar_source_id'),
			'users_username_idx' => array('unique' => false, 'column' => 'username'),
			'users_yahoo_user_id_idx' => array('unique' => false, 'column' => 'yahoo_user_id')
		),
		'tableParameters' => array()
	);
	public $withdrawal_statuses = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 11, 'key' => 'primary'),
		'created' => array('type' => 'datetime', 'null' => true),
		'modified' => array('type' => 'datetime', 'null' => true),
		'name' => array('type' => 'string', 'null' => true, 'default' => null),
		'user_cash_withdrawal_count' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'indexes' => array(
			'PRIMARY' => array('unique' => true, 'column' => 'id')
		),
		'tableParameters' => array()
	);
}
