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
class CategoriesController extends AppController
{
    public $name = 'Categories';
    public $helpers = array(
        'Items.Cakeform'
    );
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'Category',
            'FormField',
            'Attachment',
        );
        parent::beforeFilter();
    }
	public function index()
	{
		$this->pageTitle = __l('Post') . ' ' . Configure::read('item.alt_name_for_item_singular_caps');
		$categories = $this->Category->find('all', array(
            'conditions' => array(
                'Category.is_active' => 1,
				'Category.parent_id' => 0
            ) ,
			'contain' => array(
				'Attachment'
			) ,
            'recursive' => 0
        ));
		$this->set('categories', $categories);
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.Items.categories', $this, array(
				'categories' => $categories
			));
        }		
		
	}
	public function admin_index()
    {
        $this->pageTitle = __l('Categories');
        $conditions = array();
        $this->set('active', $this->Category->find('count', array(
            'conditions' => array(
                'Category.is_active' => 1
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Category->find('count', array(
            'conditions' => array(
                'Category.is_active' => 0
            ) ,
            'recursive' => -1
        )));
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Category.is_active'] = 1;
                $this->pageTitle.= ' - '.__l('Active');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Category.is_active'] = 0;
                $this->pageTitle.= ' -'.__l('Inactive');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'contain' => array(
				'ParentCategory',
				'Page'
			),
            'order' => array(
                'Category.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('categories', $this->paginate());
        $moreActions = $this->Category->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add Category');
		$this->Category->CategoryIcon->Behaviors->attach('ImageUpload', Configure::read('categoryicon.file'));
        if (!empty($this->request->data)) {
			$ini_upload_error = 1;
			if (!empty($this->request->data['Attachment']['filename']['name'])) {
				$this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
			} else {
				$this->request->data['Attachment']['filename']['error'] = __l('Required');
			}
			if (!empty($this->request->data['Attachment']['filename']['name'])) {
				$this->Category->Attachment->set($this->request->data);
			}
			if (!empty($this->request->data['Attachment']['filename']['error'])) {
				$ini_upload_error = 0;
			}
			if (!empty($this->request->data['CategoryIcon']['filename']['name'])) {
                $this->request->data['CategoryIcon']['filename']['type'] = get_mime($this->request->data['CategoryIcon']['filename']['tmp_name']);
            } else {
				$this->request->data['CategoryIcon']['filename']['error'] = __l('Required');
			}
			if (!empty($this->request->data['CategoryIcon']['filename']['name'])) {
				$this->Category->CategoryIcon->set($this->request->data);
			}
			$ini_iconupload_error = 1;
			if (empty($this->request->data['Category']['parent_id']) && !empty($this->request->data['CategoryIcon']['filename']['error'])) {
				$ini_iconupload_error = 0;
			}
            $this->Category->create();
			$this->request->data['Category']['parent_id'] = (!empty($this->request->data['Category']['parent_id'])) ? $this->request->data['Category']['parent_id'] : 0;
            if ($this->Category->validates() &$ini_upload_error &$ini_iconupload_error) {
				$this->Category->save($this->request->data);
				$category_id = $this->Category->getLastInsertId();
				if (!empty($this->request->data['Attachment']['filename']['name'])) {
					$this->Category->Attachment->create();
					$this->request->data['Attachment']['class'] = 'Category';
					$this->request->data['Attachment']['foreign_id'] = $category_id;
					$this->Category->Attachment->save($this->request->data['Attachment']);
					$this->Category->Attachment->Behaviors->detach('ImageUpload');
				}
				if (!empty($this->request->data['CategoryIcon']['filename']['name'])) {	
					$this->Category->Attachment->Behaviors->attach('ImageUpload');
					$this->Category->Attachment->create();
					$this->request->data['CategoryIcon']['class'] = 'CategoryIcon';
					$this->request->data['CategoryIcon']['foreign_id'] = $category_id;
					$this->Category->Attachment->save($this->request->data['CategoryIcon']);
				}
				if(!empty($this->request->data['Category']['parent_id'])) {
					$this->loadModel('Items.FormFieldStep');
					$form_field_steps = $this->FormFieldStep->find('all', array(
						'conditions' => array(
							'FormFieldStep.category_id' => 0
						) ,
						'contain' => array(
							'FormFieldGroup' => array(
								'FormField' => array(
									'order' => array(
										'FormField.order' => 'ASC'
									)
								) ,
								'order' => array(
									'FormFieldGroup.order' => 'ASC'
								)
							)
						) ,
						'order' => array(
							'FormFieldStep.order' => 'ASC'
						) ,
						'recursive' => 2
					));
					if(!empty($form_field_steps)) {
						foreach($form_field_steps As $form_field_step) {
							$form_field_step_data = array();
							unset($form_field_step['FormFieldStep']['id']);
							unset($form_field_step['FormFieldStep']['created']);
							unset($form_field_step['FormFieldStep']['modified']);
							$form_field_step_data['FormFieldStep'] = $form_field_step['FormFieldStep'];
							$form_field_step_data['FormFieldStep']['category_id'] = $category_id;
							$this->FormFieldStep->create();
							$this->FormFieldStep->save($form_field_step_data);
							$form_field_step_id = $this->FormFieldStep->getLastInsertId();
							if(!empty($form_field_step['FormFieldGroup'])) {
								foreach($form_field_step['FormFieldGroup'] As $form_field_group) {
									$form_field_group_data = array();
									unset($form_field_group['id']);
									unset($form_field_group['created']);
									unset($form_field_group['modified']);
									$form_field_group_data['FormFieldGroup'] = $form_field_group;
									$form_field_group_data['FormFieldGroup']['category_id'] = $category_id;
									$form_field_group_data['FormFieldGroup']['form_field_step_id'] = $form_field_step_id;
									unset($form_field_group_data['FormFieldGroup']['FormField']);
									$this->FormFieldStep->FormFieldGroup->create();
									$this->FormFieldStep->FormFieldGroup->save($form_field_group_data);
									$form_field_group_id = $this->FormFieldStep->FormFieldGroup->getLastInsertId();
									if(!empty($form_field_group['FormField'])) {
										foreach($form_field_group['FormField'] As $form_field) {
											$form_field_data = array();
											unset($form_field['id']);
											unset($form_field['created']);
											unset($form_field['modified']);
											$form_field_data['FormField'] = $form_field;
											$form_field_data['FormField']['category_id'] = $category_id;
											$form_field_data['FormField']['form_field_group_id'] = $form_field_group_id;
											$this->FormFieldStep->FormFieldGroup->FormField->create();
											$this->FormFieldStep->FormFieldGroup->FormField->save($form_field_data);								
										}
									}
								}
							}
						}
					}
				}
                $this->Session->setFlash(__l('Category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
				if (empty($ini_upload_error)) {
					$this->Category->Attachment->validationErrors['filename'] = $this->request->data['Attachment']['filename']['error'];
					$this->Session->setFlash(__l('Category could not be created. Please Upload image.') , 'default', null, 'error');
				} if (empty($ini_iconupload_error)) {
					$this->Category->CategoryIcon->validationErrors['filename'] = $this->request->data['CategoryIcon']['filename']['error'];
					$this->Session->setFlash(__l('Category could not be created. Please Upload icon image.') , 'default', null, 'error');
				} else {
					$this->Session->setFlash(__l('Category could not be added. Please, try again.') , 'default', null, 'error');
				}
            }
        }
		$parent_categories = $this->Category->find('list', array(
			'conditions' => array(
				'Category.parent_id' => 0,
			),
			'recursive' => -1
		));
		$this->set('parent_categories', $parent_categories);
        // Quick Fix // initialize model to use in form helper
        $moreActions = $this->Category->moreActions;
    }
    public function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$this->Category->CategoryIcon->Behaviors->attach('ImageUpload', Configure::read('categoryicon.file'));
        if (!empty($this->request->data)) {
			$category = $this->Category->find('first', array(
				'conditions' => array(
					'Category.id' => $id
				) ,
				'contain' => array(
					'Attachment',
					'CategoryIcon',
				) ,
				'recursive' => 1
			));
			$ini_upload_error = 1;
			if (!empty($category)) {
				$this->request->data['Category']['id'] = $category['Category']['id'];
				if (!empty($category['Attachment']['id'])) {
					$this->request->data['Attachment']['id'] = $category['Attachment']['id'];
				}
				if (!empty($category['CategoryIcon']['id'])) {
					$this->request->data['CategoryIcon']['id'] = $category['CategoryIcon']['id'];
				}
			}
			if (!empty($this->request->data['Attachment']['filename']['tmp_name'])) {
				$this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
				$this->Category->Attachment->set($this->request->data);
				if (!empty($this->request->data['Attachment']['filename']['error'])) {
					$ini_upload_error = 0;
				}
				$attachment = $this->request->data['Attachment'];
				unset($this->request->data['Attachment']);
			}
			if (!empty($this->request->data['CategoryIcon']['filename']['name'])) {
                $this->request->data['CategoryIcon']['filename']['type'] = get_mime($this->request->data['CategoryIcon']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['CategoryIcon']['filename']['name']) && empty($this->request->data['CategoryIcon']['id'])) {
                $this->Category->CategoryIcon->set($this->request->data);
            }
            $this->request->data['Category']['parent_id'] = (!empty($this->request->data['Category']['parent_id'])) ? $this->request->data['Category']['parent_id'] : 0;
			if ($ini_upload_error) {
				 $this->Category->save($this->request->data);
				 if (!empty($attachment)) {
					$this->request->data['Attachment'] = $attachment;
					if (!empty($this->request->data['Attachment']['filename']['tmp_name'])) {
						$this->Category->Attachment->create();
						$this->request->data['Attachment']['class'] = 'Category';
						$this->request->data['Attachment']['foreign_id'] = $category['Category']['id'];
						$this->Category->Attachment->save($this->request->data['Attachment']);
						$this->Category->Attachment->Behaviors->detach('ImageUpload');
					}
				}
				if (!empty($this->request->data['CategoryIcon']['filename']['name'])) {
					$this->Category->Attachment->Behaviors->attach('ImageUpload');
					$this->Category->Attachment->create();
					$this->request->data['CategoryIcon']['class'] = 'CategoryIcon';
					$this->request->data['CategoryIcon']['foreign_id'] = $category['Category']['id'];
					$this->Category->Attachment->save($this->request->data['CategoryIcon']);
				}
                $this->Session->setFlash(__l('Category has been updated') , 'default', null, 'success');
				$this->redirect(array(
                    'action' => 'index'
                ));
            } else {
				if (!empty($this->request->data['Attachment']['filename']['error'])) {
					if($this->request->data['Attachment']['filename']['error'] == "Required") {
						$this->Category->Attachment->validationErrors['filename'] = $this->request->data['Attachment']['filename']['error'];
					} else {
						$this->Category->Attachment->validationErrors['filename'] = sprintf(__l('Uploaded file is too big, only files less than %s permitted') , ini_get('upload_max_filesize'));
					}
                }
                $this->Session->setFlash(__l('Category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Category->find('first', array(
                'conditions' => array(
                    'Category.id' => $id
                ) ,
                'contain' => array(
                    'Attachment',
					'CategoryIcon',
                ) ,
                'recursive' => 0
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['Category']['name'];
		$parent_categories = $this->Category->find('list', array(
			'conditions' => array(
				'Category.parent_id' => 0,
			),
			'recursive' => -1
		));
		$this->set('parent_categories', $parent_categories);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Category->delete($id)) {
            $this->Session->setFlash(__l('Category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
	public function admin_form_field_edit($id = null) {
		App::import('Model', 'Items.Category');
        $this->Category = new Category();
        $category = $this->Category->find('first', array(
            'conditions' => array(
                'Category.id' => $id
            ) ,
            'recursive' => -1
        ));
        if (empty($category)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->set('category', $category);
        $this->pageTitle = $category['Category']['name'] . ' - ' . __l('Form Fields');
        $this->disableCache();
        if (!$id && empty($this->request->data)) {
            $this->Session->setFlash(__l('Invalid Category'), 'default', null, 'error');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        if (!empty($this->request->data)) {
            $error = 0;
            if (!empty($this->request->data['FormField'])) {
                $error_str = '';
                foreach($this->request->data['FormField'] as $formFields) {
                    if (!empty($formFields['FormField']['is_dynamic_field'])) {
                        $multiSelectArray = $this->Category->FormField->multiTypes;
                        if (in_array($formFields['type'], $multiSelectArray)) {
                            if (empty($formFields['options'])) {
                                $error = 1;
                            } else if ($formFields['type'] == 'slider') {
                                $options_val = explode(',', $formFields['options']);
                                if (count($options_val) != 2) {
                                    $error = 1;
                                    $error_str = 'slider';
                                }
                            }
                        }
                    }
                }
            }
            if (!$error) {
                if ($this->Category->save($this->request->data['Category'])) {
                    if (!empty($this->request->data['FormField'])) {
                        foreach($this->request->data['FormField'] as $formField) {
                            if (!empty($formField['options'])) {
                                $formField['options'] = rtrim($formField['options'], ",");
                            }
                            $_data = array();
                            $_data['FormField'] = $formField;
                            $this->Category->FormField->save($_data);
                        }
                    }
                    $this->Session->setFlash(__l('Category has been updated'), 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'form_field_edit',
                        $id
                    ));
                } else {
                    $this->Session->setFlash(__l('Category could not be updated. Please, try again.'), 'default', null, 'error');
                }
            } else if (empty($error_str)) {
                $this->Session->setFlash(__l('Category could not be saved. Please enter all option values needed.'), 'default', null, 'error');
            } else if (!empty($error_str) == 'slider') {
                $this->Session->setFlash(__l('Category could not be saved. Please enter exactly 2 options for slider control.'), 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Category->find('first', array(
                'conditions' => array(
                    'Category.id' => $id
                ) ,
                'contain' => array(
                    'FormField'
                ) ,
                'recursive' => 0
            ));
            $this->request->data['Category']['id'] = $id;
        }
        if (!empty($this->request->data['Category']['id'])) {
            $id = $this->request->data['Category']['id'];
        }
        $this->loadModel('Items.FormFieldStep');
        $FormFieldSteps = $this->FormFieldStep->find('all', array(
            'conditions' => array(
                'FormFieldStep.category_id' => $id
            ) ,
            'contain' => array(
                'FormFieldGroup' => array(
                    'FormField' => array(
                        'order' => array(
                            'FormField.order' => 'ASC'
                        )
                    ) ,
                    'order' => array(
                        'FormFieldGroup.order' => 'ASC'
                    )
                )
            ) ,
            'order' => array(
                'FormFieldStep.order' => 'ASC'
            ) ,
            'recursive' => 2
        ));
        $multiTypes = $this->Category->FormField->multiTypes;
        $types = $this->Category->FormField->types;
        $this->set(compact('types', 'multiTypes', 'FormFieldSteps'));
	}
	public function admin_preview($id = null, $form_field_step = null) 
    {
		if (is_null($id)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$category = $this->Category->find('first', array(
			'conditions' => array(
				'Category.id' => $id
			) ,
			'recursive' => -1
		));
		if (empty($category)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$this->set('category', $category);
		$this->loadModel('Items.Form');
		$this->loadModel('Items.FormField');
		$this->loadModel('Items.Item');
		unset($this->Form->validate);
		unset($this->FormField->validate);
		unset($this->Item->validate);
		$categoryFormFields = $this->Form->buildSchema($category['Category']['id']);
		$this->loadModel('Items.FormFieldStep');
		$FormFieldSteps = $this->FormFieldStep->find('all', array(
			'conditions' => array(
				'FormFieldStep.category_id' => $category['Category']['id']
			) ,
			'contain' => array(
				'FormFieldGroup' => array(
					'FormField' => array(
						'conditions' => array(
							'FormField.is_active' => 1
						) ,
						'order' => array(
							'FormField.order' => 'ASC'
						)
					) ,
					'order' => array(
						'FormFieldGroup.order' => 'ASC'
					)
				)
			) ,
			'order' => array(
				'FormFieldStep.order' => 'ASC'
			) ,
			'recursive' => 2
		));
		$this->set('FormFieldSteps', $FormFieldSteps);
		$this->set('total_form_field_steps', count($FormFieldSteps));
		$this->set('categoryFormFields', $categoryFormFields);
		$this->set('category', $category);
		$this->loadModel('Country');
		$countries = $this->Country->find('list', array(
			'fields' => array(
				'Country.iso_alpha2',
				'Country.name'
			)
		));
		$this->set(compact('countries'));
		$this->pageTitle = $category['Category']['name'] . ' - ' . __l('Preview');
		if (empty($this->request->data['Form']['form_field_step'])) {
			$this->request->data['Form']['form_field_step'] = 1;
		}
		if (!empty($this->request->data['Form']['next'])) {
			$this->request->data['Form']['form_field_step'] = $this->request->data['Form']['form_field_step'] + 1;
        }
        // form field steps
		if (!empty($form_field_step)) {
            $this->request->data['Form']['form_field_step'] = $form_field_step;
			$this->request->data['Form']['step'] = 2;
		}
	}
	public function getsubcategories($id) 
	{
		if (is_null($id)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$categories = array();
		if($id != 0) {
			$categories = $this->Category->find('list', array(
				'conditions' => array(
					'Category.parent_id' => $id,
					'Category.is_active' => 1
				) ,
				'order' => array(
					'Category.name' => 'ASC',
				) ,
				'recursive' => -1
			));
		}
		$this->set(compact('categories'));
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.Items.subcategories', $this, array(
				'categories' => $categories,
				'id' => $id
			));
        }		
	}
	public function getformfields($id) 
	{
		if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$category = $this->Category->find('first', array(
			'conditions' => array(
				'Category.id' => $id
			) ,
			'recursive' => -1
		));
		if (empty($category)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$this->set('category', $category);
		$this->loadModel('Items.Form');
		$this->loadModel('Items.FormField');
		$this->loadModel('Items.Item');
		unset($this->Form->validate);
		unset($this->FormField->validate);
		$categoryFormFields = $this->Form->buildSchema($category['Category']['id']);
		$this->loadModel('Items.FormFieldStep');
		$formstep_conditions = array();
		$formgroup_conditions = array();
		$formfield_conditions = array();
		$formstep_conditions['FormFieldStep.category_id'] = $category['Category']['id'];
		$formfield_conditions['FormField.is_active'] = 1;
		if($this->request->params['named']['model'] == 'Request') {
			$formfield_conditions['FormField.is_show_in_request_form'] = 1;
			$formgroup_conditions['FormFieldGroup.is_show_in_request_form'] = 1;
			$formstep_conditions['FormFieldGroup.is_show_in_request_form'] = 1;
		}
		$FormFieldSteps = $this->FormFieldStep->find('all', array(
			'conditions' => array(
				'FormFieldStep.category_id' => $category['Category']['id']
			) ,
			'contain' => array(
				'FormFieldGroup' => array(
					'conditions' => $formgroup_conditions,
					'FormField' => array(
						'conditions' => $formfield_conditions,
						'order' => array(
							'FormField.order' => 'ASC'
						)
					) ,
					'order' => array(
						'FormFieldGroup.order' => 'ASC'
					)
				)
			) ,
			'order' => array(
				'FormFieldStep.order' => 'ASC'
			) ,
			'recursive' => 2
		));
		$this->set('FormFieldSteps', $FormFieldSteps);
		$this->set('total_form_field_steps', count($FormFieldSteps));
		$this->set('categoryFormFields', $categoryFormFields);
		$this->set('category', $category);
		$this->set('model', $this->request->params['named']['model']);
		$this->loadModel('Country');
		$countries = $this->Country->find('list', array(
			'fields' => array(
				'Country.iso_alpha2',
				'Country.name'
			)
		));
		$this->set(compact('countries'));
		if (empty($this->request->data['Form']['form_field_step'])) {
			$this->request->data['Form']['form_field_step'] = 1;
		}
		if (!empty($this->request->data['Form']['next'])) {
			$this->request->data['Form']['form_field_step'] = $this->request->data['Form']['form_field_step'] + 1;
        }
        // form field steps
		if (!empty($form_field_step)) {
            $this->request->data['Form']['form_field_step'] = $form_field_step;
			$this->request->data['Form']['step'] = 2;
		}		
		if(isPluginEnabled('Seats')){
			$this->loadModel('Hall');
			$halls = $this->Hall->find('list', array(
				'conditions' => array(
					'Hall.user_id =' => $this->Auth->user('id'),
					'Hall.is_active' => 1
				) ,
				'order' => array(
					'Hall.name' => 'ASC',
				) ,
				'recursive' => -1
			));
			$this->set('halls', $halls);
		}
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
			$name = 'Controller.'.$this->request->params['named']['model'].'.GetFormFields';
            $response = Cms::dispatchEvent($name, $this, array(
				'Form' => $this->request->data['Form'],
				'category' => $category,
				'FormFieldSteps' => $FormFieldSteps,
				'total_form_field_steps' => count($FormFieldSteps),
				'categoryFormFields' => $categoryFormFields,
				'countries' => $countries				
			));
        }		
	}
	public function simple_index() {
		$categories = $this->Category->find('all', array(
			'conditions' => array(
				'Category.parent_id' => 0,
				'Category.is_active' => 1
			) ,
			'contain' => array(
				'CategoryIcon',
			) ,
			'recursive' => 0
		));
		$this->set('categories', $categories);
	}
	public function view($slug = null) {
		$this->pageTitle = __l('Category');
		if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$category = $this->Category->find('first', array(
            'conditions' => array(
                'Category.slug' => $slug,
				'Category.is_active' => 1
            ) ,
            'contain' => array(
				'Attachment',
				'CategoryIcon',
			),
            'recursive' => 1,
        ));
		if(empty($category)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$this->pageTitle .= ' - ' . $category['Category']['name'];
		$sub_categories = $this->Category->find('all', array(
            'conditions' => array(
                'Category.parent_id' => $category['Category']['id'],
				'Category.is_active' => 1
            ) ,
            'contain' => array(
				'Attachment',
			),
            'recursive' => 1,
        ));
		$this->set('category', $category);
		$this->set('sub_categories', $sub_categories);
	}
}
?>