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
class CategoryTypesController extends AppController
{
    public $name = 'CategoryTypes';
    public function beforeFilter()
    {
        parent::beforeFilter();
    }
    public function admin_index($category_id = null)
    {
        $this->pageTitle = __l('Category Types');
        $conditions = array();
        if(is_null($category_id)) {
			$this->set('active', $this->CategoryType->find('count', array(
				'conditions' => array(
					'CategoryType.is_active' => 1
				) ,
				'recursive' => -1
			)));
			$this->set('inactive', $this->CategoryType->find('count', array(
				'conditions' => array(
					'CategoryType.is_active' => 0
				) ,
				'recursive' => -1
			)));
			if (!empty($this->request->params['named']['filter_id'])) {
				if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
					$conditions['CategoryType.is_active'] = 1;
					$this->pageTitle.= ' - '.__l('Active');
				} else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
					$conditions['CategoryType.is_active'] = 0;
					$this->pageTitle.= ' - '.__l('Inactive');
				}
			}
		}
		if(!is_null($category_id)) {
			$categtory = $this->CategoryType->Category->find('first', array(
				'conditions' => array(
					'Category.id' => $category_id,
				),
				'recursive' => -1
			));
			$this->pageTitle .= ' - ' . $categtory['Category']['name'];
			$this->set('category_id', $category_id);
			$conditions['CategoryType.category_id'] = $category_id;
		}
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'Category',
			),
            'order' => array(
                'CategoryType.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('category_types', $this->paginate());
        $filters = $this->CategoryType->isFilterOptions;
        $moreActions = $this->CategoryType->moreActions;
        $this->set(compact('moreActions', 'filters'));
    }
    public function admin_add($category_id)
    {
        $this->pageTitle = __l('Add Category Types');
		if (is_null($category_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $this->CategoryType->create();
            if ($this->CategoryType->save($this->request->data)) {
                $this->Session->setFlash(__l('Category Type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index',
					$this->request->data['CategoryType']['category_id'],
                ));
            } else {
                $this->Session->setFlash(__l('Category Type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
		$categtory = $this->CategoryType->Category->find('first', array(
			'conditions' => array(
				'Category.id' => $category_id,
			),
			'recursive' => -1
		));
		$this->pageTitle .= ' - ' . $categtory['Category']['name'];
		$this->set('category_id', $category_id);
        $moreActions = $this->CategoryType->moreActions;
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Category Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->CategoryType->save($this->request->data)) {
                $this->Session->setFlash(__l('Category Type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index',
					$this->request->data['CategoryType']['category_id'],
                ));
            } else {
                $this->Session->setFlash(__l('Category Type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->CategoryType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['CategoryType']['name'];
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$category_type = $this->CategoryType->find('first', array(
			'conditions' => array(
				'CategoryType.id' => $id,
			),
			'recursive' => -1
		));
		if (!empty($category_type)) {
			$category_id = $category_type['CategoryType']['category_id'];
		}
        if ($this->CategoryType->delete($id)) {
            $this->Session->setFlash(__l('Category Type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index',
				$category_id
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
	public function get_categorytypes($id) 
	{
		$message = array();
		if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$message = array("message" => __l('Invalid request'), "error" => 1);
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
			
		}
		$category_types = array();
		if($id != 0) {
			$category_types = $this->CategoryType->find('list', array(
				'conditions' => array(
					'CategoryType.category_id' => $id
				) ,
				'recursive' => -1
			));
			$message = $category_types;
		}
		$this->set(compact('category_types'));
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            Cms::dispatchEvent('Controller.CategoryTypes.GetCategoryTypes', $this, array(
				'message' => $message,
			));
        }		
	}
}
?>