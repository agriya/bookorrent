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
class CustomPricePerTypesSeat extends AppModel
{
    public $name = 'CustomPricePerTypesSeat';
	public $actsAs = array(
        'Aggregatable'
    );
	public $displayField = 'name';
	public $belongsTo = array(
        'Hall' => array(
            'className' => 'Seats.Hall',
            'foreignKey' => 'hall_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ),
        'Partition' => array(
            'className' => 'Seats.Partition',
            'foreignKey' => 'partition_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false
        ) ,
        'Seat' => array(
            'className' => 'Seats.Seat',
            'foreignKey' => 'seat_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false,
            'counterScope' => ''
        ),
        'Item' => array(
            'className' => 'Items.Item',
            'foreignKey' => 'item_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false,
            'counterScope' => ''
        ),
		'CustomPricePerType' => array(
            'className' => 'Items.CustomPricePerType',
            'foreignKey' => 'custom_price_per_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
		'CustomPricePerTypeItemUser' => array(
            'className' => 'Items.CustomPricePerTypeItemUser',
            'foreignKey' => 'custom_price_per_type_item_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ),
        'ItemUser' => array(
            'className' => 'Items.ItemUser',
            'foreignKey' => 'item_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => false,
            'counterScope' => ''
        )
		
		
    );	
    //$validate set in __construct for multi-language support
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'hall_id' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'partition_id' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
		$this->_permanentCacheAssociations = array(
			'Item'
		);		
		
    }
	public function generate_row_name($rows, $type){
		$rowName = Array();
		switch($type){
		  case 1:
			for ($row = 0; $row < $rows; $row++) {		
				$rowName[]=$this->generateAlphabet($row);
			}
			break;
		  case 2:
			for ($row = 1; $row <= $rows; $row++) {		
				$rowName[]=$this->romanic_number($row);
			}
			break;
		  case 3:
			for ($row = 1; $row <= $rows; $row++) {		
				$rowName[]=$row;
			}
			break;
		}
		return $rowName;
	}
	function generateAlphabet($na) {
		$sa = "";
		while ($na >= 0) {
			$sa = chr($na % 26 + 65) . $sa;
			$na = floor($na / 26) - 1;
		}
		return $sa;
	}
	function romanic_number($integer, $upcase = true) 
	{ 
		$table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
		$return = ''; 
		while($integer > 0) 
		{ 
			foreach($table as $rom=>$arb) 
			{ 
				if($integer >= $arb) 
				{ 
					$integer -= $arb; 
					$return .= $rom; 
					break; 
				} 
			} 
		}
		return $return; 
	} 	
}
?>