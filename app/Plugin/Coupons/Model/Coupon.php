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
class Coupon extends AppModel
{
    public $name = 'Coupon';
    public $displayField = 'name';
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
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
		$this->_permanentCacheAssociations = array(
			'Item'
		);
        $this->validate = array(
            'item_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'name' => array(
				'rule5' => array(
					'rule' => array('maxLength', 20),
					'allowEmpty' => false,
					'message' => __l('Maximum length is 20')
				),
				'rule4' => array(
					'rule' => array('minLength', 4),
					'allowEmpty' => false,
					'message' => __l('Minimum length is 4')
				),
                'rule3' => array(
                    'rule' => '_isUnique',
                    'message' => __l('Coupon code already exists')
                ) ,
                'rule2' => array(
                    'rule' => 'alphaNumeric',
                    'message' => __l('Only use letters and numbers')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'discount' => array(
				'rule2' => array(
					'rule' => array(
						'decimal', 2
					),
					'message' => __l('Should be a decimal number and digits after decimal should be within 6 length')
				) ,
				'rule1' => array(
					'rule' => 'notempty',
					'allowEmpty' => false,
					'message' => __l('Required')
				)
            ) ,
            'number_of_quantity' => array(
                'rule2' => array(
					'rule' => array(
						'comparison', '>', 0
					),
					'message' => __l('Should be greate then zero')
				) ,
				'rule1' => array(
					'rule' => 'naturalNumber',
					'allowEmpty' => false,
					'message' => __l('Required')
				)
            ) 
        );
        $this->moreActions = array(
			ConstMoreAction::Active => __l('Enable') ,
            ConstMoreAction::Inactive => __l('Disable') ,
            ConstMoreAction::Delete => __l('Delete') ,
        );
        $this->moreActionsItem = array(
            ConstMoreAction::Delete => __l('Delete') ,
        );
		$this->isFilterOptions = array(
			ConstMoreAction::Active => __l('Enable'),
            ConstMoreAction::Inactive => __l('Disable') 
        );
    }
	function _isUnique($field1 = array())
    {
		$conditions['Coupon.name'] = $this->data[$this->name]['name'];
		$conditions['Coupon.item_id'] = $this->data[$this->name]['item_id'];
		if (!empty($this->data[$this->name]['id'])) {
			$conditions['Coupon.id != '] = $this->data[$this->name]['id'];
		}
        $coupon = $this->find('count', array(
            'conditions' => $conditions,
            'recursive' => -1
        ));
        if (empty($coupon)) {
            return true;
        }
        return false;
    }
}
?>