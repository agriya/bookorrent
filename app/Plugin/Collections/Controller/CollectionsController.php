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
class CollectionsController extends AppController
{
    public $name = 'Collections';
	public $permanentCacheAction = array(
		'public' => array(
			'index',
		) ,
    );
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'Attachment',
        );
        parent::beforeFilter();
    }
    public function index()
    {
        $this->pageTitle = __l('Collections');
        $this->Collection->recursive = 0;
        $conditions = array();
        $conditions['Collection.is_active'] = 1;
        $conditions['Collection.item_count >'] = 0;
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'contain' => array(
                'CollectionsItem',
                'Attachment',
                'Item' => array(
                    'Attachment'
                ) ,
            ) ,
            'order' => array(
                'Collection.id' => 'desc'
            )
        );
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.Item.Collection', $this, array());
        }		
        $this->set('collections', $this->paginate());
    }
    public function collage()
    {
        // @todo "Collage Script"

    }
    public function admin_index()
    {
        $this->pageTitle = __l('Collections');
        $conditions = array();
        $this->set('active', $this->Collection->find('count', array(
            'conditions' => array(
                'Collection.is_active' => 1
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Collection->find('count', array(
            'conditions' => array(
                'Collection.is_active' => 0
            ) ,
            'recursive' => -1
        )));
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Collection.is_active'] = 1;
                $this->pageTitle.= ' - '.__l('Active');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Collection.is_active'] = 0;
                $this->pageTitle.= ' - '.__l('Inactive');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Collection.id' => 'desc'
            ) ,
            'recursive' => -1
        );
        $this->set('collections', $this->paginate());
        $moreActions = $this->Collection->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Collection');
        $this->Collection->Behaviors->attach('ImageUpload', Configure::read('image.file'));
        if (!empty($this->request->data)) {
            $this->Collection->create();
            $this->request->data['Attachment']['class'] = 'Collection';
            $ini_upload_error = 1;
            if ($this->request->data['Attachment']['filename']['error'] == 1) {
                $ini_upload_error = 0;
                $this->request->data['Attachment']['class'] = 'Collection';
            }
            if ($this->Collection->save($this->request->data)) {
                if ($ini_upload_error && !empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['foreign_id'] = $this->Collection->getLastInsertId();
                    $this->request->data['Attachment']['class'] = 'Collection';
                    $this->Collection->Attachment->create();
                    $this->Collection->Attachment->save($this->request->data);
                }
                $this->Session->setFlash(__l('Collection has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Collection could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $items = $this->Collection->Item->find('list');
        $users = $this->Collection->User->find('list');
        $this->set(compact('items', 'users'));
    }
    public function admin_add_collection()
    {
        if (!empty($this->request->data)) {
			if(!empty($this->request->data['Collection']['Collection'])){
            $item_ids = explode(',', $this->request->data['Collection']['item_list']);
            foreach($item_ids as $id) {
                foreach($this->request->data['Collection']['Collection'] as $collection) {
                    $collection_count = $this->Collection->CollectionsItem->find('count', array(
                        'conditions' => array(
                            'CollectionsItem.item_id = ' => $id,
                            'CollectionsItem.collection_id = ' => $collection,
                        ) ,
                        'recursive' => -1,
                    ));
                    if ($collection_count == 0) {
                        $data = array();
                        $data['CollectionsItem']['collection_id'] = $collection;
                        $data['CollectionsItem']['item_id'] = $id;
                        $this->Collection->CollectionsItem->create();
                        $this->Collection->CollectionsItem->save($data, false);
                        $this->Collection->CollectionsItem->updateAll(array(
                            'CollectionsItem.display_order' => $this->Collection->CollectionsItem->getLastInsertId()
                        ) , array(
                            'CollectionsItem.id' => $this->Collection->CollectionsItem->getLastInsertId()
                        ));
                        $this->Collection->updateCount($collection, $id);
                    }
                }
            }
            //update collection count and item count
            $this->Session->setFlash(sprintf(__l('%s mapped with collections successfully'), Configure::read('item.alt_name_for_item_plural_caps')) , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'items',
                'action' => 'index'
            ));
		} else{
			$this->Session->setFlash(__l('Collection cannot be empty please select collection and try again') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'items',
                'action' => 'index'
            ));
		}
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Collection');
        $this->Collection->Behaviors->attach('ImageUpload', Configure::read('image.file'));
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $this->request->data['Attachment']['class'] = 'Collection';
            $ini_upload_error = 1;
            if ($this->request->data['Attachment']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            if (!empty($this->request->data['CollectionsItem'])) {
                foreach($this->request->data['CollectionsItem'] as $key => $val) {
                    $this->Collection->CollectionsItem->updateAll(array(
                        'CollectionsItem.display_order' => $val['display_order'],
                    ) , array(
                        'CollectionsItem.item_id' => $key,
                        'CollectionsItem.collection_id' => $this->request->data['Collection']['id'],
                    ));
                }
                unset($this->request->data['CollectionsItem']);
            }
            // save collections mapped proties
            //first delete all the mapped items for this collections
            if (!empty($this->request->data['Item'])) {
                foreach($this->request->data['Item'] as $key => $val) {
                    if ($val['id'] == 1) {
                        $this->Collection->CollectionsItem->deleteAll(array(
                            'CollectionsItem.collection_id' => $this->request->data['Collection']['id'],
                            'CollectionsItem.item_id' => $key
                        ));
                    }
                    $this->Collection->updateCount($this->request->data['Collection']['id'], $key);
                    unset($this->request->data['Item']);
                }
            }
            // @todo "Collage Script"
            if ($this->Collection->save($this->request->data)) {
                if ($ini_upload_error && !empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['foreign_id'] = $this->request->data['Collection']['id'];
                    $this->request->data['Attachment']['class'] = 'Collection';
                    $attachment = $this->Collection->Attachment->find('first', array(
						'fields' => 'Attachment.id',
						'conditions' => array(
							'Attachment.foreign_id' => $this->request->data['Collection']['id'],
							'Attachment.class' => 'Collection'
						) ,
						'recursive' => -1	
					));
					if(!empty($attachment['Attachment']['id'])){
						$this->request->data['Attachment']['id'] = $attachment['Attachment']['id'];
					}
					if (empty($this->request->data['Attachment']['id'])) {
                        $this->Collection->Attachment->create();
                    }
                    $this->Collection->Attachment->save($this->request->data);
                }
                $this->Session->setFlash(__l('Collection has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Collection could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Collection->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['Collection']['title'];
        $users = $this->Collection->User->find('list');
        $ids = $this->Collection->CollectionsItem->find('list', array(
            'conditions' => array(
                'CollectionsItem.collection_id' => $this->request->data['Collection']['id']
            ) ,
            'fields' => array(
                'CollectionsItem.id',
                'CollectionsItem.item_id'
            ) ,
			'recursive' => -1,
        ));
        $items = $this->Collection->Item->find('all', array(
            'conditions' => array(
                'Item.id' => $ids
            ) ,
            'contain' => array(
                'User',
                'Country',
                'Attachment',
            ) ,
            'recursive' => 2
        ));
        $i = 0;
        foreach($items as $item) {
            $collection = $this->Collection->CollectionsItem->find('first', array(
                'conditions' => array(
                    'CollectionsItem.item_id = ' => $item['Item']['id'],
                    'CollectionsItem.collection_id = ' => $this->request->data['Collection']['id']
                ) ,
                'fields' => array(
                    'CollectionsItem.display_order',
                ) ,
                'recursive' => -1,
            ));
            $items[$i]['Item']['display_order'] = $collection['CollectionsItem']['display_order'];
            $i++;
        }
        //Sorting code start here
        // compare function
		function cmpi($a, $b)
		{
		    if ($a['Item']['display_order'] == $b['Item']['display_order']) {
		        return 0;
		    }
		    return ($a['Item']['display_order'] < $b['Item']['display_order']) ? -1 : 1;
		}
        // do the array sorting
        usort($items, 'cmpi');
        //sorting code ends here
        $this->set('items', $items);
        $moreActions = $this->Collection->moreActionsItem;
        $this->set(compact('moreActions'));
        //$items = $this->Collection->Item->find('list');
        $this->set(compact('users', 'moreActions'));
    }
    public function admin_delete_item($item_id = null, $id = null)
    {
        if (is_null($id) || is_null($item_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Collection->CollectionsItem->deleteAll(array(
            'CollectionsItem.collection_id' => $id,
            'CollectionsItem.item_id' => $item_id
        ))) {
            $this->Collection->updateCount($id, $item_id);
            $this->Session->setFlash(sprintf(__l('%s deleted'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'edit',
                $id
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Collection->delete($id)) {
            $this->Session->setFlash(__l('Collection deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>