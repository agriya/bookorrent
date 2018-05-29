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
class Form extends AppModel
{
    public $name = 'Form';
    public $useTable = false;
    public $dependsOn = array();
    public function beforeValidate($options = array()) 
    {
        foreach($this->dependsOn as $field => $dependsOn) {
            $this->dependsOn($field, $dependsOn['field'], $dependsOn['value']);
        }
    }
    public function dependsOn($field, $dependsOn, $dependsValue) 
    {
        if (isset($this->data[$this->name][$dependsOn]) && $this->data[$this->name][$dependsOn] == $dependsValue) {
            return true;
        } else {
            unset($this->validate[$field]);
            unset($this->data[$this->name][$field]);
            return true;
        }
    }
    public function buildSchema($id) 
    {
        $notInput = array(
            'fieldset',
            'textonly'
        );
        App::import('Model', 'Items.Category');
        $this->Category = new Category;
        $cform = $this->Category->find('first', array(
            'conditions' => array(
                'Category.id' => $id
            ) ,
            'contain' => array(
                'FormField' => array(
                    'conditions' => array(
                        'FormField.is_active' => 1
                    ) ,
                    'order' => array(
                        'FormField.order'
                    ) ,
                )
            ) ,
            'recursive' => 2
        ));
        $schema = array();
        $validate = array();
        foreach($cform['FormField'] as $key => &$field) {
            if (!in_array($field['type'], $notInput)) {
                $schema[$field['name']] = array(
                    'type' => 'string',
                    'length' => !empty($field['length']) ? $field['length'] : '',
                    'null' => null,
                    'default' => !empty($field['default']) ? $field['default'] : ''
                );
                if ($field['type'] == 'video') {
                    $validate[$field['name']] = array(
                        'rule' => array(
                            '_validateVideoUrl',
                            $field['name']
                        ) ,
                        'message' => __l('Please enter a valid YouTube, Vimeo video URL, starting with http://') ,
                        'allowEmpty' => ($field['required'] ? false : true)
                    );
                } elseif ($field['type'] == 'thumbnail' || $field['type'] == 'url') {
                    $validate[$field['name']] = array(
                        'rule' => array(
                            'url',
                            true
                        ) ,
                        'message' => __l('Must be a valid URL, starting with http://') ,
                        'allowEmpty' => ($field['required'] ? false : true)
                    );
                } elseif (!empty($field['ValidationRule']) && ($field['type'] != 'multiselect' and $field['type'] != 'checkbox')) {
                    foreach($field['ValidationRule'] as $rule) {
                        $validate[$field['name']][$rule['rule']] = array(
                            'rule' => $rule['rule'],
                            'message' => $rule['message'],
                            'allowEmpty' => ($field['required'] ? false : true)
                        );
                    }
                } elseif ($field['required']) {
                    if ($field['type'] == 'date') {
                        $validate[$field['name']] = array(
                            'rule1' => array(
                                'rule' => 'date',
                                'message' => __l('Must be a valid date') ,
                                'allowEmpty' => false
                            )
                        );
                    } elseif ($field['type'] == 'datetime') {
                        $validate[$field['name']] = array(
                            'rule1' => array(
                                'rule' => 'datetime',
                                'message' => __l('Must be a valid date') ,
                                'allowEmpty' => false
                            )
                        );
                    } elseif ($field['type'] == 'file') {
                        $validate[$field['name']] = array();
                    } elseif ($field['type'] != 'multiselect' and $field['type'] != 'checkbox') {
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
                if (!empty($field['depends_on']) && !empty($field['depends_value'])) {
                    $dependsOn[$field['name']] = array(
                        'field' => $field['depends_on'],
                        'value' => $field['depends_value']
                    );
                }
                if ($field['type'] == 'multiselect') {
                    $cform['FormField'][$key]['type'] = 'select';
                    $cform['FormField'][$key]['multiple'] = 'multiple';
                }
                if (!empty($field['options'])) {
                    $field['options'] = str_replace(', ', ',', $field['options']);
                    $options = $this->explode_escaped(',', $field['options']);
                    $field['options'] = array_combine($options, $options);
                }
            }
        }
        $this->validate = $validate;
        $this->_schema = $schema;
        $this->dependsOn = (isset($dependsOn) ? $dependsOn : array());
        return $cform;
    }
    function deconstructDate($data, $type, $format) 
    {
        $useNewDate = (isset($data['year']) || isset($data['month']) || isset($data['day']) || isset($data['hour']) || isset($data['minute']));
        $dateFields = array(
            'Y' => 'year',
            'm' => 'month',
            'd' => 'day',
            'H' => 'hour',
            'i' => 'min',
            's' => 'sec'
        );
        $date = array();
        if (isset($data['hour']) && isset($data['meridian']) && $data['hour'] != 12 && 'pm' == $data['meridian']) {
            $data['hour'] = $data['hour']+12;
        }
        if (isset($data['hour']) && isset($data['meridian']) && $data['hour'] == 12 && 'am' == $data['meridian']) {
            $data['hour'] = '00';
        }
        foreach($dateFields as $key => $val) {
            if (in_array($val, array(
                'hour',
                'min',
                'sec'
            ))) {
                if (!isset($data[$val]) || $data[$val] === '0' || empty($data[$val])) {
                    $data[$val] = '00';
                } else {
                    $data[$val] = sprintf('%02d', $data[$val]);
                }
            }
            if (in_array($type, array(
                'datetime',
                'timestamp',
                'date'
            )) && !isset($data[$val]) || isset($data[$val]) && (empty($data[$val]) || $data[$val][0] === '-')) {
                return null;
            } elseif (isset($data[$val]) && !empty($data[$val])) {
                $date[$key] = $data[$val];
            }
        }
        $date = str_replace(array_keys($date) , array_values($date) , $format);
        if ($type == 'time' && $date == '00:00:00') {
            return null;
        }
        if ($useNewDate && (!empty($date))) {
            return $date;
        }
        return $data;
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
    function _validateVideoUrl($name = array()) 
    {
        App::import('Helper', 'Items.Embed');
        $this->Embed = new EmbedHelper();
        if (!(!empty($this->data[$this->name][key($name) ]) && $this->Embed->parseUrl($this->data[$this->name][key($name) ]))) {
            return false;
        }
        return true;
    }
}
?>