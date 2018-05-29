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
class AclLinksController extends AclAppController
{
    public $name = 'AclLinks';
    public $components = array(
        'Acl.AclGenerate'
    );
    public function admin_index() 
    {
        $this->pageTitle = __l('ACL Action');
        $this->set('title_for_layout', __l('ACL Action'));
        $this->paginate = array(
            'order' => array(
                'AclLink.id' => 'DESC'
            ) ,
            'recursive' => -1
        );
        $this->set('aclLinks', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add ACL Action');
        if (!empty($this->request->data)) {
            $acl_link = $this->AclLink->find('first', array(
                'conditions' => array(
                    'AclLink.controller' => $this->request->data['AclLink']['controller'],
                    'AclLink.action' => $this->request->data['AclLink']['action'],
                ) ,
                'recursive' => -1
            ));
            if (empty($acl_link)) {
                if ($this->AclLink->save($this->request->data)) {
                    $this->Session->setFlash(__l('ACL Action has been added') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash(__l('ACL Action could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('ACL Action already exists'), 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit ACL Action');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->AclLink->save($this->request->data)) {
                $this->Session->setFlash(__l('ACL Action has been updated'), 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('ACL Action could not be updated. Please, try again.'), 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->AclLink->find('first', array(
                'conditions' => array(
                    'AclLink.id' => $id
                ) ,
                'recursive' => -1
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['AclLink']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->AclLink->delete($id)) {
            $this->Session->setFlash(__l('ACL Action deleted'),'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_generate() 
    {
        $controllerPaths = $this->AclGenerate->listControllers();
        $flag = 0;
        foreach($controllerPaths AS $controllerName => $controllerPath) {
            $methods = $this->AclGenerate->listActions($controllerName, $controllerPath);
            $acl_link = array();
            if (!empty($methods)) {
                foreach($methods AS $method) {
                    $acl_link = $this->AclLink->find('first', array(
                        'conditions' => array(
                            'AclLink.controller' => $controllerName,
                            'AclLink.action' => $method,
                        ) ,
                        'recursive' => -1
                    ));
                    $data = array();
                    if (empty($acl_link)) {
                        $data['AclLink']['name'] = Inflector::humanize($method);
                        $data['AclLink']['controller'] = $controllerName;
                        $data['AclLink']['action'] = $method;
                        $this->AclLink->create();
                        $this->AclLink->save($data);
                        $flag = 1;
                    }
                }
            }
        }
        if ($flag) {
            $this->Session->setFlash(__l('ACL Actions generated successfully'), 'default', null, 'success');
        } else {
            $this->Session->setFlash(__l('ACL Actions already generated.'), 'default', null, 'error');
        }
        $this->redirect(array(
            'action' => 'index'
        ));
    }
}
?>