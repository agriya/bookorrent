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
class ItemUserFeedback extends AppModel
{
    public $name = 'ItemUserFeedback';
    public $actsAs = array(
        'Aggregatable'
    );
    public $hasMany = array(
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'ItemUserFeedback'
            ) ,
            'dependent' => true
        )
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'ItemUser' => array(
            'className' => 'Items.ItemUser',
            'foreignKey' => 'item_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Item' => array(
            'className' => 'Items.Item',
            'foreignKey' => 'item_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'host_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
		$this->_permanentCacheAssociations = array(
			'Item',
		);
        $this->validate = array(
            'feedback' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
        $this->moreActions = array(
            ConstMoreAction::Delete => __l('Delete') ,
        );
    }
    function beforeFind($queryData)
    {
        $queryData['conditions']['ItemUserFeedback.is_auto_review !='] = 1;
        return parent::beforeFind($queryData);
    }
    function afterSave($created)
    {
        $ItemUserFeedback = $this->find('first', array(
            'conditions' => array(
                'ItemUserFeedback.id' => $this->id,
            ) ,
            'fields' => array(
                'ItemUserFeedback.booker_user_id',
            ) ,
            'recursive' => -1
        ));
        $this->data['ItemUserFeedback']['booker_user_id'] = !empty($this->data['ItemUserFeedback']['booker_user_id']) ? $this->data['ItemUserFeedback']['booker_user_id'] : $ItemUserFeedback['ItemUserFeedback']['booker_user_id'];
        $this->_updateFeedbackCount($this->data['ItemUserFeedback']['booker_user_id']);
        return true;
    }
    function beforeDelete($cascade = true)
    {
        $ItemUserFeedback = $this->find('first', array(
            'conditions' => array(
                'ItemUserFeedback.id' => $this->id,
            ) ,
            'fields' => array(
                'ItemUserFeedback.item_user_id',
            ) ,
            'recursive' => -1
        ));
        $this->data['ItemUserFeedback']['item_user_id'] = $ItemUserFeedback['ItemUserFeedback']['item_user_id'];
        return true;
    }
    function afterDelete()
    {
        $this->_updateFeedbackCount($this->data['ItemUserFeedback']['booker_user_id']);
        return true;
    }
    function _updateFeedbackCount($booker_user_id)
    {
        $ItemPossitive = $this->find('count', array(
            'conditions' => array(
                'booker_user_id' => $booker_user_id,
                'is_satisfied' => 1
            ) ,
            'recursive' => -1
        ));
        $ItemFeedback = $this->find('count', array(
            'conditions' => array(
                'booker_user_id' => $booker_user_id,
            ) ,
            'recursive' => -1
        ));
        $_data['User']['id'] = $booker_user_id;
        $_data['User']['booker_positive_feedback_count'] = $ItemPossitive;
        $_data['User']['booker_item_user_count'] = $ItemFeedback;
        $this->ItemUser->User->updateAll(array(
            'User.booker_positive_feedback_count' => $ItemPossitive,
            'User.booker_item_user_count' => $ItemFeedback,
        ) , array(
            'User.id' => $booker_user_id
        ));
    }
    function _getFeedback($Item_order_id)
    {
        $get_feedback = $this->find('first', array(
            'conditions' => array(
                'ItemUserFeedback.Item_user_id' => $Item_order_id,
            ) ,
            'recursive' => -1
        ));
        if (!empty($get_feedback)) {
            return $get_feedback;
        } else {
            return '';
        }
    }
}
?>