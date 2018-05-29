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
class Collection extends AppModel
{
    public $name = 'Collection';
    public $displayField = 'title';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'title'
            )
        ) ,
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public $hasOne = array(
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'Collection'
            ) ,
            'dependent' => true
        )
    );
    public $hasAndBelongsToMany = array(
        'Item' => array(
            'className' => 'Items.Item',
            'joinTable' => 'collections_items',
            'foreignKey' => 'collection_id',
            'associationForeignKey' => 'item_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
		$this->_memcacheModels = array(
			'Image'
		);
		$this->_permanentCacheAssociations = array(
			'Item'
		);
        $this->validate = array(
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'title' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'slug' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
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
    }
    function updateCount($collection_id, $item_id)
    {
        // @todo "Collection city count, country count update"
        $collection_count = $this->CollectionsItem->find('count', array(
            'conditions' => array(
                'CollectionsItem.item_id' => $item_id,
            ) ,
            'recursive' => -1
        ));
        $item_ids = $this->CollectionsItem->find('list', array(
            'conditions' => array(
                'CollectionsItem.collection_id' => $collection_id,
            ) ,
            'fields' => array(
                'CollectionsItem.id',
                'CollectionsItem.item_id',
            ) ,
            'recursive' => -1
        ));
        $item_count = $this->Item->find('count', array(
            'conditions' => array(
                'Item.id' => $item_ids,
                'Item.admin_suspend' => 0,
                'Item.is_active' => 1,
                'Item.is_approved' => 1,
                'Item.is_paid' => 1,
            ) ,
            'recursive' => -1
        ));
        $countries = $this->Item->find('all', array(
            'conditions' => array(
                'Item.id' => $item_ids,
            ) ,
            'fields' => array(
				'DISTINCT(Item.country_id)',
            ) ,
            'recursive' => -1
        ));
        $cities = $this->Item->find('all', array(
            'conditions' => array(
                'Item.id' => $item_ids,
            ) ,
			'fields' => array(
				'DISTINCT(Item.city_id)',
            ) ,
            'recursive' => -1
        ));
        //item count update in collection table
        $this->updateAll(array(
            'Collection.item_count' => $item_count,
            'Collection.country_count' => count($countries) ,
            'Collection.city_count' => count($cities)
        ) , array(
            'Collection.id' => $collection_id
        ));
        // in collection count update in item table
        $this->Item->updateAll(array(
            'Item.in_collection_count' => $collection_count
        ) , array(
            'Item.id' => $item_id
        ));
    }
}
?>