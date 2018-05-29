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
class BuyerSubmission extends AppModel
{
    public $name = 'BuyerSubmission';
    public $validate = array(
    );
    public $belongsTo = array(
        'ItemUser' => array(
            'className' => 'Items.ItemUser',
            'foreignKey' => 'item_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'BuyerFormField' => array(
            'className' => 'Items.BuyerFormField',
            'foreignKey' => 'buyer_form_field_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
	public function submit($data) 
    {
		$form_fields = $this->BuyerFormField->find('all', array(
			'conditions' => array(
				'BuyerFormField.item_id' => $data['BuyerSubmission']['item_id'],
			),
			'recursive' => -1,
		));
		foreach($form_fields As $form_field) {
			$_data = array();
			if(!empty($data['BuyerSubmission'][$form_field['BuyerFormField']['name']])) {
				$_data['BuyerSubmission']['item_user_id'] = $data['Item']['order_id'];
				$_data['BuyerSubmission']['item_id'] = $data['BuyerSubmission']['item_id'];
				$_data['BuyerSubmission']['buyer_form_field_id'] = $form_field['BuyerFormField']['id'];
				$_data['BuyerSubmission']['form_field'] = $form_field['BuyerFormField']['name'];
				if($form_field['BuyerFormField']['type'] == 'checkbox' || $form_field['BuyerFormField']['type'] == 'multiselect') {
					$_data['BuyerSubmission']['response'] = implode(",", $data['BuyerSubmission'][$form_field['BuyerFormField']['name']]);
				} else {
					$_data['BuyerSubmission']['response'] = $data['BuyerSubmission'][$form_field['BuyerFormField']['name']];
				}
				$_data['BuyerSubmission']['type'] = $form_field['BuyerFormField']['type'];
				$this->deleteAll(array(
                    'BuyerSubmission.item_user_id' => $data['Item']['order_id']
                ));
				$this->create();
				$this->save($_data);	
			}
		}
	}
}
?>