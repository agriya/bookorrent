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
class ItemFeedback extends AppModel
{
    public $name = 'ItemFeedback';
    public $actsAs = array(
        'BayesianAverageable' => array(
            'fields' => array(
                'itemId' => 'item_id',
                'rating' => 'is_satisfied',
                'ratingsCount' => 'item_feedback_count',
                'totalRatings' => 'positive_feedback_count',
                'meanRating' => 'mean_rating',
                'bayesianRating' => 'actual_rating',
            ) ,
            'itemModel' => 'Item',
            'cache' => array(
                'config' => null,
                'prefix' => 'BayesianAverage_',
                'calculationDuration' => 10,
            ) ,
        ) ,
        'Aggregatable'
    );
    public $hasMany = array(
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'ItemFeedback'
            ) ,
            'dependent' => true
        )
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Item' => array(
            'className' => 'Items.Item',
            'foreignKey' => 'item_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'ItemUser' => array(
            'className' => 'Items.ItemUser',
            'foreignKey' => 'item_user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
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
        $queryData['conditions']['ItemFeedback.is_auto_review !='] = 1;
        return parent::beforeFind($queryData);
    }
    function afterSave($created)
    {
        $ItemFeedback = $this->find('first', array(
            'conditions' => array(
                'ItemFeedback.id' => $this->id,
            ) ,
            'fields' => array(
                'ItemFeedback.item_id',
            ) ,
            'recursive' => -1
        ));
        $this->data['ItemFeedback']['item_id'] = !empty($this->data['ItemFeedback']['item_id']) ? $this->data['ItemFeedback']['item_id'] : $ItemFeedback['ItemFeedback']['item_id'];
        $this->_updateFeedbackCount($this->data['ItemFeedback']['item_id']);
        return true;
    }
    function beforeDelete($cascade = true)
    {
        $ItemFeedback = $this->find('first', array(
            'conditions' => array(
                'ItemFeedback.id' => $this->id,
            ) ,
            'fields' => array(
                'ItemFeedback.item_id',
            ) ,
            'recursive' => -1
        ));
        $this->data['ItemFeedback']['item_id'] = $ItemFeedback['ItemFeedback']['item_id'];
        return true;
    }
    function afterDelete()
    {
        $this->_updateFeedbackCount($this->data['ItemFeedback']['item_id']);
        return true;
    }
    function _updateFeedbackCount($Item_id)
    {
        $Item = $this->ItemUser->Item->find('first', array(
            'conditions' => array(
                'Item.id' => $Item_id
            ) ,
            'fields' => array(
                'Item.user_id',
            ) ,
            'recursive' => -1
        ));
        $user_id = $Item['Item']['user_id'];
        $item_ids = $this->ItemUser->Item->find('list', array(
            'conditions' => array(
                'Item.user_id' => $user_id
            ) ,
            'fields' => array(
                'Item.id',
                'Item.id',
            ) ,
            'recursive' => -1
        ));
        $ItemPossitive = $this->find('count', array(
            'conditions' => array(
                'item_id' => $Item_id,
                'is_satisfied' => 1
            ) ,
            'recursive' => -1
        ));
        $emptyData = $_data['Item']['id'] = $Item_id;
        $_data['Item']['positive_feedback_count'] = $ItemPossitive;
        $this->ItemUser->Item->save($_data);
        //update user table
        $UserPossitive = $this->find('count', array(
            'conditions' => array(
                'item_id' => $item_ids,
                'is_satisfied' => 1
            ) ,
            'recursive' => -1
        ));
        $UserItem = $this->find('count', array(
            'conditions' => array(
                'item_id' => $item_ids,
            ) ,
            'recursive' => -1
        ));
        $_data['User']['id'] = $user_id;
        $_data['User']['positive_feedback_count'] = $UserPossitive;
        $_data['User']['item_feedback_count'] = $UserItem;
        $this->ItemUser->Item->User->save($_data);
    }
    function _getFeedback($Item_order_id)
    {
        $get_feedback = $this->find('first', array(
            'conditions' => array(
                'ItemFeedback.Item_user_id' => $Item_order_id,
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