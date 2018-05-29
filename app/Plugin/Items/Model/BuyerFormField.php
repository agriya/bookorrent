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
class BuyerFormField extends AppModel
{
    public $name = 'BuyerFormField';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Item' => array(
            'className' => 'Items.Item',
            'foreignKey' => 'item_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
    );
    public $types = array(
        'text' => 'Single Line of Text',
        'textarea' => 'Multiple Lines of Text',
        'select' => 'Select Box',
        'checkbox' => 'Checkboxes',
        'radio' => 'Radio Buttons',
        'multiselect' => 'Multiple Option Select Box',
    );
    public $multiTypes = array(
        'checkbox',
        'radio',
        'select',
        'multiselect',
        'slider'
    );
    public function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->_permanentCacheAssociations = array(
            'Item',
        );
    }
	public function buildSchema($id) 
    {
        $notInput = array(
            'fieldset',
            'textonly'
        );
        App::import('Model', 'Items.Item');
        $this->Item = new Item;
        $cform = $this->Item->find('first', array(
            'conditions' => array(
                'Item.id' => $id
            ) ,
            'contain' => array(
                'BuyerFormField' => array(
                    'conditions' => array(
                        'BuyerFormField.is_active' => 1
                    ) ,
                 )
            ) ,
            'recursive' => 2
        ));
        $validate = array();
        foreach($cform['BuyerFormField'] as $key => &$field) {
            if (!in_array($field['type'], $notInput)) {
                if ($field['required']) {
                    if ($field['type'] != 'multiselect' and $field['type'] != 'checkbox') {
                        $validate[$field['name']] = array(
                            'rule' => 'notempty',
                            'allowEmpty' => false,
                            'message' => __l('Required') ,
                        );
                    } else {
                        $validate[$field['name']] = array(
                            'rule' => array(
                                '_multiSelectValidation',
                                $field['name']
                            ) ,
                            'message' => __l('Required') ,
                            'allowEmpty' => false
                        );
                    }
                }
                if ($field['type'] == 'multiselect') {
                    $cform['BuyerFormField'][$key]['type'] = 'select';
                    $cform['BuyerFormField'][$key]['multiple'] = 'multiple';
                }
                if (!empty($field['options'])) {
                    $field['options'] = str_replace(', ', ',', $field['options']);
                    $options = $this->explode_escaped(',', $field['options']);
                    $field['options'] = array_combine($options, $options);
                }
            }
        }
        $this->validate = $validate;
        return $cform;
    }
	function _multiSelectValidation($field1 = array() , $field2 = null) 
    {
        if (is_array($this->data[$this->name][$field2]) and count($this->data[$this->name][$field2]) != 0) {
            return true;
        } elseif (!is_array($this->data[$this->name][$field2]) and !empty($this->data[$this->name][$field2])) {
            return true;
        }
        return false;
    }
	function explode_escaped($delimiter, $string) 
    {
        $exploded = explode($delimiter, $string);
        $fixed = array();
        for ($k = 0, $l = count($exploded); $k < $l; ++$k) {
            if ($exploded[$k][strlen($exploded[$k]) -1] == '\\') {
                if ($k+1 >= $l) {
                    $fixed[] = trim($exploded[$k]);
                    break;
                }
                $exploded[$k][strlen($exploded[$k]) -1] = $delimiter;
                $exploded[$k].= $exploded[$k+1];
                array_splice($exploded, $k+1, 1);
                --$l;
                --$k;
            } else $fixed[] = trim($exploded[$k]);
        }
        return $fixed;
    }
}
?>