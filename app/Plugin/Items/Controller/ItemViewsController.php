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
class ItemViewsController extends AppController
{
    public $name = 'ItemViews';
    public function admin_index()
    {
        $this->_redirectGET2Named(array(
            'item_id',
            'q'
        ));
        $this->pageTitle = sprintf(__l('%s Views'), Configure::read('item.alt_name_for_item_singular_caps'));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['ItemView.created ='] = date('Y-m-d', strtotime('now'));
            $this->pageTitle.= __l(' - today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['ItemView.created >='] = date('Y-m-d', strtotime('now -7 days'));
            $this->pageTitle.= __l(' - in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['ItemView.created >='] = date('Y-m-d', strtotime('now -30 days'));
            $this->pageTitle.= __l(' - in this month');
        }
        if (!empty($this->request->params['named']['item'])) {
            $item = $this->ItemView->Item->find('first', array(
                'conditions' => array(
                    'Item.slug' => $this->request->params['named']['item']
                ) ,
                'fields' => array(
                    'Item.id',
                    'Item.title'
                ) ,
                'recursive' => -1
            ));
            $conditions['ItemView.item_id'] = $item['Item']['id'];
            $this->pageTitle.= ' - ' . $item['Item']['title'];
        }
        if (isset($this->request->params['named']['q'])) {
            $conditions['AND']['OR'][]['Item.title LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $conditions['AND']['OR'][]['User.username LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
			$this->request->data['ItemView']['q'] = $this->request->params['named']['q'];
        }
        if (!empty($this->request->params['named']['item_id'])) {
            $conditions['ItemView.item_id'] = $this->request->params['named']['item_id'];
            $item_name = $this->ItemView->Item->find('first', array(
                'conditions' => array(
                    'Item.id' => $this->request->params['named']['item_id'],
                ) ,
                'fields' => array(
                    'Item.title',
                ) ,
                'recursive' => -1,
            ));
            $this->pageTitle.= sprintf(__l(' - %s') , $item_name['Item']['title']);
        }
        $this->ItemView->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'Item',
                'Ip' => array(
                        'City' => array(
                            'fields' => array(
                                'City.name',
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.name',
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.name',
                                'Country.iso_alpha2',
                            )
                        ) ,
                        'Timezone' => array(
                            'fields' => array(
                                'Timezone.name',
                            )
                        ) ,
                        'fields' => array(
                            'Ip.ip',
                            'Ip.latitude',
                            'Ip.longitude',
                            'Ip.host',
                        )
                    ) ,
            ) ,
            'order' => array(
                'ItemView.id' => 'desc'
            ) ,
        );
        $this->set('itemViews', $this->paginate());
        $moreActions = $this->ItemView->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ItemView->delete($id)) {
            $this->Session->setFlash(sprintf(__l('%s View deleted'), Configure::read('item.alt_name_for_item_singular_caps')), 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>