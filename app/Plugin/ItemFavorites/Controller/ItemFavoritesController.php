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
class ItemFavoritesController extends AppController
{
    public $name = 'ItemFavorites';
    public $components = array(
        'OauthConsumer'
    );
    // Add Favourites and update in facebook and twitter if user is logged in using FB Connect or Twitter Connect //
    public function add($slug = null)
    {
        $item = $this->ItemFavorite->Item->find('first', array(
            'conditions' => array(
                'Item.slug' => $slug,
                'Item.user_id != ' => $this->Auth->user('id')
            ) ,
            'recursive' => -1
        ));
        if (empty($item)) {
			if ($this->RequestHandler->prefers('json')) {
				$message = array("message" => __l('Invalid request'), "error" => 1);
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        $chkFavorites = $this->ItemFavorite->find('first', array(
            'conditions' => array(
                'user_id' => $this->Auth->user('id') ,
                'item_id' => $item['Item']['id']
            ) ,
            'recursive' => -1
        ));
        if (empty($chkFavorites)) {
            $this->request->data['ItemFavorite']['item_id'] = $item['Item']['id'];
            $this->request->data['ItemFavorite']['user_id'] = $this->Auth->user('id');
            $this->request->data['ItemFavorite']['ip_id'] = $this->ItemFavorite->toSaveIp();
            if (!empty($this->request->data)) {
                $this->ItemFavorite->create();
                if ($this->ItemFavorite->save($this->request->data, false)) {
					$favorite_id = $this->ItemFavorite->id;
					Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
						'_trackEvent' => array(
							'category' => 'User',
							'action' => 'Favorited ',
							'label' => $this->Auth->user('username'),
							'value' => '',
						) ,
						'_setCustomVar' => array(
							'ud' => $this->Auth->user('id'),
							'rud' => $this->Auth->user('referred_by_user_id'),
						)
					));
					Cms::dispatchEvent('Controller.IntegratedGoogleAnalytics.trackEvent', $this, array(
						'_trackEvent' => array(
							'category' => 'ItemFavorite',
							'action' => 'Favorited',
							'label' => $item['Item']['id'],
							'value' => '',
						) ,
						'_setCustomVar' => array(
							'pd' => $item['Item']['id'],
							'ud' => $this->Auth->user('id'),
							'rud' => $this->Auth->user('referred_by_user_id'),
						)
					));
                    // Update Social Networking//
                    $item = $this->ItemFavorite->Item->find('first', array(
                        'conditions' => array(
                            'Item.id = ' => $this->request->data['ItemFavorite']['item_id'],
                        ) ,
                        'fields' => array(
                            'Item.id',
                            'Item.title',
                            'Item.slug',
                            'Item.user_id',
                            'Item.description',
                            'Item.item_view_count',
                            'Item.item_feedback_count',
                            'Item.item_favorite_count',
                            'Item.is_active',
                        ) ,
                        'contain' => array(
                            'Attachment' => array(
                                'fields' => array(
                                    'Attachment.id',
                                    'Attachment.filename',
                                    'Attachment.dir',
                                    'Attachment.width',
                                    'Attachment.height'
                                )
                            ) ,
                        ) ,
                        'recursive' => 2,
                    ));
					$response = '';
					$response = Cms::dispatchEvent('Controller.SocialMarketing.getShareUrl', $this, array(
						'data' => $favorite_id,
						'publish_action' => 'follow'
					));
					$social_url = !empty($response->data['social_url']) ? $response->data['social_url'] : '';
                    $url = Router::url(array(
                        'controller' => 'items',
                        'action' => 'view',
                        $item['Item']['slug'],
                    ) , true);                   
                    if ($this->RequestHandler->prefers('json')) {
							$message = array("message" => sprintf(__l('%s has been added to your Favorites'), Configure::read('item.alt_name_for_item_singular_caps')), "error" => 0, 'url' => Router::url(array('controller' => 'item_favorites', 'action'=>"delete", $item['Item']['slug']),true ), "title" =>  __l('Unlike'));
					}else{	
						if ($this->RequestHandler->isAjax()) {
							$class = "js-like js-no-pjax un-like top-space show no-under";
							$url = array('controller' => 'item_favorites', 'action'=>"delete", $item['Item']['slug']);
							if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'view'){
								$class ="js-like js-no-pjax like show span top-smspace no-under";
								$url = array('controller' => 'item_favorites', 'action'=>"delete", $item['Item']['slug'], 'type' => 'view');
							}						
							$this->set('class', $class);
							$this->set('url', $url);						
							$this->set('is_starred_class', "icon-star no-pad text-18");
							$this->set('title', __l('Unlike'));							
							$this->render('star');                     
						}else{
							$this->Session->setFlash(sprintf(__l('%s has been added to your Favorites'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
							Cms::dispatchEvent('Controller.SocialMarketing.redirectToShareUrl', $this, array(
								'data' => $favorite_id,
								'publish_action' => 'follow'
							));
							$this->redirect(array(
								'controller' => 'items',
								'action' => 'view',
								$item['Item']['slug']
							));
						}
					}
                } else {
                    $this->Session->setFlash(sprintf(__l('%s Favorite could not be added. Please, try again.'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
					if ($this->RequestHandler->prefers('json')) {
						$message = array("message" => sprintf(__l('%s Favorite could not be added. Please, try again.'), Configure::read('item.alt_name_for_item_singular_caps')), "error" => 1);
					}else{
						$this->redirect(array(
							'controller' => 'items',
							'action' => 'view',
							$slug
						));
					}
                }
            }
        } else {
			if ($this->RequestHandler->prefers('json')) {
				$message = array("message" => __l('Invalid request'), "error" => 1);
			}else{
				$this->Session->setFlash(sprintf(__l('%s already added has Favorite'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'error');
				$this->redirect(array(
					'controller' => 'items',
					'action' => 'view',
					$slug
				));
			}
        }
		if ($this->RequestHandler->prefers('json')) {
			$response = Cms::dispatchEvent('Controller.ItemFavorities.Add', $this, array(
				'message' => $message
			));
		}		
    }
    public function delete($slug = null)
    {
        $item = $this->ItemFavorite->Item->find('first', array(
            'conditions' => array(
                'Item.slug = ' => $slug
            ) ,
            'recursive' => -1
        ));
        if (empty($item)) {
            if ($this->RequestHandler->prefers('json')) {
				$message = array("message" => __l('Invalid request'), "error" => 1);
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        $chkFavorites = $this->ItemFavorite->find('first', array(
            'conditions' => array(
                'ItemFavorite.user_id' => $this->Auth->user('id') ,
                'ItemFavorite.item_id' => $item['Item']['id']
            ) ,
            'recursive' => -1
        ));
        if (!empty($chkFavorites['ItemFavorite']['id'])) {
            $id = $chkFavorites['ItemFavorite']['id'];
            if ($this->ItemFavorite->delete($id)) {
				if ($this->RequestHandler->prefers('json')) {
					$url = Router::url(array('controller' => 'item_favorites', 'action'=>'add', $item['Item']['slug']), true);
					$message = array("message" => sprintf(__l('%s removed from favorites'), Configure::read('item.alt_name_for_item_singular_caps')), "error" => 0, 'url' => $url, "title" =>  __l('Like'));
				}else{
					if ($this->RequestHandler->isAjax()) {
						$class = "js-like js-no-pjax un-like top-space show no-unde";
						$url = array('controller' => 'item_favorites', 'action'=>'add', $item['Item']['slug']);
						if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'view'){
							$class ="js-like js-no-pjax like show span top-smspace graylightc no-under";
							$url = array('controller' => 'item_favorites', 'action'=>'add', $item['Item']['slug'], 'type' => 'view');
						}								
						$this->set('class', $class);
						$this->set('url', $url);
						$this->set('is_starred_class', "grayc icon-star-empty no-pad text-18");
						$this->set('title', __l('Like'));	
						$this->render('star');                   
					}else{
						$this->Session->setFlash(sprintf(__l('%s removed from favorites'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
						$this->redirect(array(
							'controller' => 'items',
							'action' => 'view',
							$item['Item']['slug']
						));
					}
				}
            } else {
                if ($this->RequestHandler->prefers('json')) {
					$message = array("message" => __l('Invalid request'), "error" => 1);
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
            }
        } else {			
            if ($this->RequestHandler->prefers('json')) {
				$message = array("message" => __l('Invalid request'), "error" => 1);
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		if ($this->RequestHandler->prefers('json')) {
			$response = Cms::dispatchEvent('Controller.ItemFavorities.Delete', $this, array(
				'message' => $message
			));
		}
    }
    public function admin_index()
    {
        $this->pageTitle = Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Favorites');
        $this->_redirectGET2Named(array(
            'q',
            'username',
        ));
        $conditions = array();
        if (!empty($this->request->params['named']['username']) || !empty($this->request->params['named']['user_id'])) {
            $userConditions = !empty($this->request->params['named']['username']) ? array(
                'User.username' => $this->request->params['named']['username']
            ) : array(
                'User.id' => $this->request->params['named']['user_id']
            );
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => $userConditions,
                'fields' => array(
                    'User.id',
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['User.id'] = $this->request->data[$this->modelClass]['user_id'] = $user['User']['id'];
            $this->pageTitle.= ' - ' . $user['User']['username'];
        }
        if (!empty($this->request->params['named']['item']) || !empty($this->request->params['named']['item_id'])) {
            $itemConditions = !empty($this->request->params['named']['item']) ? array(
                'Item.slug' => $this->request->params['named']['item']
            ) : array(
                'Item.id' => $this->request->params['named']['item_id']
            );
            $item = $this->{$this->modelClass}->Item->find('first', array(
                'conditions' => $itemConditions,
                'fields' => array(
                    'Item.id',
                    'Item.title'
                ) ,
                'recursive' => -1
            ));
            if (empty($item)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Item.id'] = $this->request->data[$this->modelClass]['item_id'] = $item['Item']['id'];
            $this->pageTitle.= ' - ' . $item['Item']['title'];
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['ItemFavorite.created ='] = date('Y-m-d', strtotime('now'));
            $this->pageTitle.= ' - '.__l('today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['ItemFavorite.created >='] = date('Y-m-d', strtotime('now -7 days'));
            $this->pageTitle.= ' - '.__l('in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['ItemFavorite.created >='] = date('Y-m-d', strtotime('now -30 days'));
            $this->pageTitle.= ' - '.__l('in this month');
        }
        if (!empty($this->request->params['named']['item'])) {
            $conditions['Item.slug'] = $this->request->params['named']['item'];
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['ItemFavorite']['q'] = $this->request->params['named']['q'];
			$conditions['AND']['OR'][]['Item.title LIKE'] = '%' . $this->request->params['named']['q'] . '%';
			$conditions['AND']['OR'][]['User.username LIKE'] = '%' . $this->request->params['named']['q'] . '%';
            $this->pageTitle.= sprintf(' - '.__l('Search - %s') , $this->request->params['named']['q']);
        }
        $this->ItemFavorite->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
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
                'User' => array(
                    'UserAvatar',
                ),
                'Item',
            ) ,
            'order' => array(
                'ItemFavorite.id' => 'desc'
            )
        );
        $moreActions = $this->ItemFavorite->moreActions;
        $this->set(compact('moreActions'));
        $this->set('itemFavorites', $this->paginate());
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ItemFavorite->delete($id)) {
            $this->Session->setFlash(sprintf(__l('%s favorite deleted successfully'), Configure::read('item.alt_name_for_item_singular_caps')) , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>