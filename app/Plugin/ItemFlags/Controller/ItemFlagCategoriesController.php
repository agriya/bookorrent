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
class ItemFlagCategoriesController extends AppController
{
    public $name = 'ItemFlagCategories';
    public function beforeFilter()
    {
        parent::beforeFilter();
    }
    public function admin_index()
    {
        $this->pageTitle = Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Flag Categories');
        $conditions = array();
        $this->set('active', $this->ItemFlagCategory->find('count', array(
            'conditions' => array(
                'ItemFlagCategory.is_active' => 1
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->ItemFlagCategory->find('count', array(
            'conditions' => array(
                'ItemFlagCategory.is_active' => 0
            ) ,
            'recursive' => -1
        )));
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['ItemFlagCategory.is_active'] = 1;
                $this->pageTitle.= ' - '.__l('Active');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['ItemFlagCategory.is_active'] = 0;
                $this->pageTitle.= ' - '.__l('Inactive');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'ItemFlagCategory.id' => 'desc'
            ) ,
            'recursive' => -1
        );
        $this->set('itemFlagCategories', $this->paginate());
        $filters = $this->ItemFlagCategory->isFilterOptions;
        $moreActions = $this->ItemFlagCategory->moreActions;
        $this->set(compact('moreActions', 'filters'));
    }
    public function admin_add()
    {
		$this->ItemFlagCategory->Behaviors->detach('I18n');	
        $this->pageTitle = __l('Add Flag Category');
        if (!empty($this->request->data)) {
            $this->ItemFlagCategory->create();
            if ($this->ItemFlagCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Flag Category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Flag Category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        // Quick Fix // initialize model to use in form helper
        $moreActions = $this->ItemFlagCategory->moreActions;
    }
    public function admin_edit($id = null)
    {
		$this->ItemFlagCategory->Behaviors->detach('I18n');	
        $this->pageTitle = __l('Edit Flag Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->ItemFlagCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Flag Category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Flag Category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->ItemFlagCategory->read(array('id', 'created', 'modified', 'name', 'name_es', 'is_active', 'item_flag_count'), $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['ItemFlagCategory']['name'];
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ItemFlagCategory->delete($id)) {
            $this->Session->setFlash(__l('Flag Category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function index()
    {
		$this->paginate = array(
            'conditions' => array(
                'ItemFlagCategory.is_active' => 1,
            ) ,
            'recursive' => 1
        );		
        $this->set('item_flag_categories', $this->paginate());
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
			Cms::dispatchEvent('Controller.ItemFlagCategory.Index', $this, array());
        }		
    }	
}
?>