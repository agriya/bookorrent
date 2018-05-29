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
class CustomPricePerType extends AppModel
{
    public $name = 'CustomPricePerType';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Item' => array(
            'className' => 'Items.Item',
            'foreignKey' => 'item_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
		'CustomPricePerNight' => array(
            'className' => 'Items.CustomPricePerNight',
            'foreignKey' => 'custom_price_per_night_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
		$db = ConnectionManager::getDataSource($this->useDbConfig);
		if($db->config['datasource'] == 'Database/Mysql') {
			$this->aggregatingFields = array(
				'available_seat_count' => array(
					'mode' => 'real',
					'key' => 'custom_price_per_type_id',
					'foreignKey' => 'custom_price_per_type_id',
					'model' => 'Seats.CustomPricePerTypesSeat',
					'function' => 'COUNT(CustomPricePerTypesSeat.custom_price_per_type_id)',
					'conditions' => array(
						'CustomPricePerTypesSeat.seat_status_id' => array(
							ConstSeatStatus::Available
						)
					)
				) ,
				'unavailable_seat_count' => array(
					'mode' => 'real',
					'key' => 'custom_price_per_type_id',
					'foreignKey' => 'custom_price_per_type_id',
					'model' => 'Seats.CustomPricePerTypesSeat',
					'function' => 'COUNT(CustomPricePerTypesSeat.custom_price_per_type_id)',
					'conditions' => array(
						'CustomPricePerTypesSeat.seat_status_id' => array (
							ConstSeatStatus::Unavailable
						)
					)
				) ,
				'no_seat_count' => array(
					'mode' => 'real',
					'key' => 'custom_price_per_type_id',
					'foreignKey' => 'custom_price_per_type_id',
					'model' => 'Seats.CustomPricePerTypesSeat',
					'function' => 'COUNT(CustomPricePerTypesSeat.custom_price_per_type_id)',
					'conditions' => array(
						'CustomPricePerTypesSeat.seat_status_id' => array (
							ConstSeatStatus::NoSeat
						)
					)
				) ,				
				'blocked_count' => array(
					'mode' => 'real',
					'key' => 'custom_price_per_type_id',
					'foreignKey' => 'custom_price_per_type_id',
					'model' => 'Seats.CustomPricePerTypesSeat',
					'function' => 'COUNT(CustomPricePerTypesSeat.custom_price_per_type_id)',
					'conditions' => array(
						'CustomPricePerTypesSeat.seat_status_id' => array (
							ConstSeatStatus::Blocked
						)
					)
				) ,				
				'waiting_for_acceptance_count' => array(
					'mode' => 'real',
					'key' => 'custom_price_per_type_id',
					'foreignKey' => 'custom_price_per_type_id',
					'model' => 'Seats.CustomPricePerTypesSeat',
					'function' => 'COUNT(CustomPricePerTypesSeat.custom_price_per_type_id)',
					'conditions' => array(
						'CustomPricePerTypesSeat.seat_status_id' => array (
							ConstSeatStatus::WaitingForAcceptance
						)
					)
				) 
			);
		}
		$this->_memcacheModels = array(
			'Item'
		);
        $this->validate = array(
			'name' => array(
				'rule' => 'notempty',
				'allowEmpty' => false,
				'message' => __l('Required')
			),
			'max_number_of_quantity' => array(
				'rule' => 'numeric',
				'allowEmpty' => true,
				'message' => __l('Must be a numeric')
			),
			'price' => array(
				'rule2' => array(
					'rule' => array('maxLength', 15),
					'message' => __l('Maximum allowed length is 15')
				),
				'rule1' => array(
					'rule' => array(
						'decimal', 2
					),
					'allowEmpty' => true,
					'message' => __l('Maximum number of decimals allowed is 2')
				)
			),
			'partition_id' => array(
				'rule' => 'numeric',
				'allowEmpty' => false,
				'message' =>__l('Required')
			)
        );
    }
}
?>
