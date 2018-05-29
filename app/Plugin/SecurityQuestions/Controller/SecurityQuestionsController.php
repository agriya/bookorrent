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
class SecurityQuestionsController extends AppController
{
    public $name = 'SecurityQuestions';
    public function admin_index()
    {
		$this->pageTitle = __l('Security Questions');
        $this->paginate = array();
		$this->set('approved', $this->SecurityQuestion->find('count', array(
            'conditions' => array(
                'SecurityQuestion.is_active' => 1
            ) ,
            'recursive' => -1
        )));
		$this->set('pending', $this->SecurityQuestion->find('count', array(
            'conditions' => array(
                'SecurityQuestion.is_active' => 0
            ) ,
            'recursive' => -1
        )));
		if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['SecurityQuestion']['filter_id'] = $this->request->params['named']['filter_id'];
        }
		$conditions = array();
		if (!empty($this->request->data['SecurityQuestion']['filter_id'])) {
			if ($this->request->data['SecurityQuestion']['filter_id'] == ConstMoreAction::Active) {
                $conditions['SecurityQuestion.is_active'] = 1;
                $this->pageTitle.= ' - ' . __l('Active');
            }
			else if ($this->request->data['SecurityQuestion']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['SecurityQuestion.is_active'] = 0;
                $this->pageTitle.= ' - ' . __l('Inactive');
            }
		}
		$this->paginate=array('conditions' => $conditions);
        $questions = $this->paginate();
        $this->set('questions', $questions);
        $moreActions = $this->SecurityQuestion->moreActions;
        $this->set('moreActions',$moreActions);
    }
    public function admin_add()
    {
		$this->SecurityQuestion->Behaviors->detach('I18n');
        $this->pageTitle = __l('Add Question');
		if (!empty($this->request->data)) {
			 $this->SecurityQuestion->set($this->request->data);
             $this->SecurityQuestion->create();
			 if ($this->SecurityQuestion->save($this->request->data)) {
			 	$this->Session->setFlash(__l('Security Questions has been added'), 'default', null, 'success');
			 } else {
			 	$this->Session->setFlash(__l('Security Question could not be added. Please, try again.'), 'default', null, 'error');
			 }
			$this->redirect(array(
				'controller' => 'security_questions',
				'action' => 'index',
				'admin' => true
			));
		}
    }
    public function admin_edit($id = null)
    {
		$this->SecurityQuestion->Behaviors->detach('I18n');
        $this->pageTitle = __l('Edit Question');
        if (!empty($this->request->data)) {
			$this->SecurityQuestion->set($this->request->data);
			$this->SecurityQuestion->create();
			if( $this->SecurityQuestion->save($this->request->data)) {
				$this->Session->setFlash(__l('Security Question has been updated'), 'default', null, 'success');
			}	else {
				$this->Session->setFlash(__l('Security Question could not be added. Please, try again.'), 'default', null, 'error');
			}
			$this->redirect(array(
				'controller' => 'security_questions',
				'action' => 'index',
				'admin' => true
			));
		} else {
			$this->request->data = $this->SecurityQuestion->find('first', array(
				'conditions' => array(
					'SecurityQuestion.id = ' => $id,
				) ,
				'fields' => array(
					'SecurityQuestion.id',
					'SecurityQuestion.created',
					'SecurityQuestion.modified',
					'SecurityQuestion.name',
					'SecurityQuestion.name_es',
					'SecurityQuestion.slug',
					'SecurityQuestion.is_active'
				),
				'recursive' => -1
			));
        }
    }
    public function index()
    {
		$this->paginate = array(
            'conditions' => array(
                'SecurityQuestion.is_active' => 1,
            ) ,
            'recursive' => 1
        );		
        $this->set('security_questions', $this->paginate());
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
			Cms::dispatchEvent('Controller.SecurityQuestion.Index', $this, array());
        }		
    }
}
?>