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
class CustomPricePerNight extends AppModel
{
    public $name = 'CustomPricePerNight';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Item' => array(
            'className' => 'Items.Item',
            'foreignKey' => 'item_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
	public $hasMany = array(
		'CustomPricePerType' => array(
            'className' => 'Items.CustomPricePerType',
            'foreignKey' => 'custom_price_per_night_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
		'ItemUser' => array(
            'className' => 'Items.ItemUser',
            'foreignKey' => 'custom_price_per_night_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
	);
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
		$this->_memcacheModels = array(
			'Item'
		);
        $this->validate = array(
            'item_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
			'name' => array(
				'rule' => 'notempty',
				'allowEmpty' => false,
				'message' => __l('Required')
			) ,
			'quantity' => array(
				'rule' => 'numeric',
				'allowEmpty' => true,
				'message' => __l('Must be a numeric')
			) ,
			'min_hours' => array(
				'rule' => array('naturalNumber', true),
				'allowEmpty' => true,
				'message' => __l('Must be greater than or equal zero')
			) ,
			'price_per_hour' => array(
				'rule3' => array(
					'rule' => array('maxLength', 15),
					'message' => __l('Maximum allowed length is 15')
				),
				'rule2' => array(
					'rule' => array(
						'decimal', 2
					),
					'allowEmpty' => true,
					'message' => __l('Maximum number of decimals allowed is 2')
				),
				'rule1' => array(
					'rule' =>  array('_validatePrice', 'price_per_hour'),
					'allowEmpty' => true,
					'message' => 'Must be greater than or equal zero'
				)
			),
			'price_per_day' => array(
				'rule3' => array(
					'rule' => array('maxLength', 15),
					'message' => __l('Maximum allowed length is 15')
				),
				'rule2' => array(
					'rule' => array(
						'decimal', 2
					),
					'allowEmpty' => true,
					'message' => __l('Maximum number of decimals allowed is 2')
				) ,
				'rule1' => array(
					'rule' =>  array('_validatePrice', 'price_per_day'),
					'allowEmpty' => true,
					'message' => 'Must be greater than or equal zero'
				)
			),
			'price_per_week' => array(
				'rule3' => array(
					'rule' => array('maxLength', 15),
					'message' => __l('Maximum allowed length is 15')
				),
				'rule2' => array(
					'rule' => array(
						'decimal', 2
					),
					'allowEmpty' => true,
					'message' => __l('Maximum number of decimals allowed is 2')
				) ,
				'rule1' => array(
					'rule' =>  array('_validatePrice', 'price_per_week'),
					'allowEmpty' => true,
					'message' => 'Must be greater than or equal zero'
				)
			),
			'price_per_month' => array(
				'rule3' => array(
					'rule' => array('maxLength', 15),
					'message' => __l('Maximum allowed length is 15')
				),
				'rule2' => array(
					'rule' => array(
						'decimal', 2
					),
					'allowEmpty' => true,
					'message' => __l('Maximum number of decimals allowed is 2')
				) ,
				'rule1' => array(
					'rule' =>  array('_validatePrice', 'price_per_month'),
					'allowEmpty' => true,
					'message' => 'Must be greater than or equal zero'
				)
			),
			'hall_id' => array(
                'rule' => 'notempty',
				'allowEmpty' => false,
				'message' => __l('Required')
            ) ,
			'repeat_end_date' => array(
                'rule' => 'notempty',
				'allowEmpty' => false,
				'message' => __l('Required')
            ) ,
        );
    }
	function _validatePrice($field1 = array() , $field2 = null)
    {
		if(empty($this->data[$this->name][$field2])){
			return true;
		}else if($this->data[$this->name][$field2] < 0){
			return false;
		}else if(is_numeric($this->data[$this->name][$field2])){
			return true;
		}else {
			return false;
		}
    }	
    function _getCalendarMontlyBooking($item_id, $month, $year)
    {
        $conditions = array();
        $conditions['CustomPricePerNight.item_id'] = $item_id;
        // checkin must be within the given month n year //
        $conditions['CustomPricePerNight.start_date <= '] = $year . '-' . $month . '-' . '31' . ' 00:00:00';
        $conditions['CustomPricePerNight.end_date >= '] = $year . '-' . $month . '-' . '01' . ' 00:00:00';
        // must be active status //
        //$conditions['CustomPricePerNight.is_available'] = 1;
        $custom_nights = $this->find('all', array(
            'conditions' => $conditions,
            'order' => array(
                'CustomPricePerNight.start_date' => 'ASC'
            ) ,
            'recursive' => -1
        ));
        return $custom_nights;
    }
}
?>
