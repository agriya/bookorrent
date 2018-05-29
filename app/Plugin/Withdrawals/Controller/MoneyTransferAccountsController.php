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
class MoneyTransferAccountsController extends AppController
{
    public $name = 'MoneyTransferAccounts';
    public $permanentCacheAction = array(
        'user' => array(
            'index',
            'add',
        ) ,
    );
    public function index() 
    {
        if (!isPluginEnabled('Wallet') || !isPluginEnabled('Withdrawals')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('Money Transfer Accounts');
        $this->paginate = array(
            'conditions' => array(
                'MoneyTransferAccount.user_id' => $this->Auth->user('id') ,
            ) ,
            'order' => array(
                'MoneyTransferAccount.id' => 'desc'
            ) ,
            'recursive' => 0
        );
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.User.MoneyTransfer', $this, array());
        }			
        $this->set('moneyTransferAccounts', $this->paginate());
    }
    public function add() 
    {
		$message = array();
        if (!isPluginEnabled('Wallet') || !isPluginEnabled('Withdrawals')) {
			if ($this->RequestHandler->prefers('json')) {
				$message = array("message" => __l('Invalid request'), "error" => 1);
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        $this->MoneyTransferAccount->create();
        if (!empty($this->request->data)) {
            $userMoneyTransferAccountCount = $this->MoneyTransferAccount->find('count', array(
                'conditions' => array(
                    'MoneyTransferAccount.user_id' => $this->Auth->user('id') ,
                ) ,
                'recursive' => -1
            ));
            if (empty($userMoneyTransferAccountCount)) {
                $this->request->data['MoneyTransferAccount']['is_default'] = 1;
            };
            $this->request->data['MoneyTransferAccount']['user_id'] = $this->Auth->user('id');
			if ($this->RequestHandler->prefers('json')) {
				$this->request->data['MoneyTransferAccount'] = $this->request->data;
			}
            if ($this->MoneyTransferAccount->save($this->request->data)) {
                $this->Session->setFlash(__l('Money Transfer Account has been added'), 'default', null, 'success');
				if ($this->RequestHandler->prefers('json')) {
					$message = array("message" =>__l('Money Transfer Account has been added'), "error" => 0);
				}else{
					$ajax_url = Router::url(array(
						'controller' => 'money_transfer_accounts',
						'action' => 'index'
					));
					if ($this->request->params['isAjax'] == 1) {
						$success_msg = 'redirect*' . $ajax_url;
						echo $success_msg;
						exit;
					} else {
						$this->redirect($ajax_url);
					}
				}
            } else {
				if ($this->RequestHandler->prefers('json')) {
					$message = array("message" =>__l('Money Transfer Account could not be added. Please, try again.'), "error" => 1);
				}else{
					$this->Session->setFlash(__l('Money Transfer Account could not be added. Please, try again.'), 'default', null, 'error');
				}
            }
        }
        $moneyTransferAccounts = $this->MoneyTransferAccount->find('all', array(
            'conditions' => array(
                'MoneyTransferAccount.user_id' => $this->Auth->user('id') ,
            ) ,
            'fields' => array(
                'MoneyTransferAccount.payment_gateway_id' => 'MoneyTransferAccount.payment_gateway_id'
            ) ,
            'recursive' => -1
        ));
        $paymentGatewayIds = array();
        if (!empty($moneyTransferAccounts)) {
            foreach($moneyTransferAccounts as $moneyTransferAccount) {
                $paymentGatewayIds[] = $moneyTransferAccount['MoneyTransferAccount']['payment_gateway_id'];
            }
        }
        $conditions['Not']['PaymentGateway.id'] = $paymentGatewayIds;
        $conditions['PaymentGateway.is_mass_pay_enabled'] = 1;
        $paymentGateways = $this->MoneyTransferAccount->PaymentGateway->find('list', array(
            'conditions' => $conditions,
            'display_name' => array(
                'order ASC'
            ) ,
            'recursive' => -1
        ));
        $this->set('paymentGateways', $paymentGateways);
		// <-- For iPhone App code
        if ($this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.User.MoneyTransferAdd', $this, array('message' => $message));
        }		
    }
    public function delete($id = null) 
    {
		$status = true;
        if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$status = false;
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		if($status){
			if ($this->MoneyTransferAccount->delete($id)) {
				$this->set('iphone_response', array("message" => __l('Money Transfer Accounts deleted'), "error" => 0));
				$this->Session->setFlash(__l('Money Transfer Account deleted'), 'default', null, 'success');
				if (!$this->RequestHandler->prefers('json')) {
					$this->redirect(array(
						'action' => 'index'
					));
				}
			} else {
				if ($this->RequestHandler->prefers('json')) {
					$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
				}else{
					throw new NotFoundException(__l('Invalid request'));
				}
			}
		}
		if ($this->RequestHandler->prefers('json')) {
				Cms::dispatchEvent('Controller.Record.Delete', $this, array());
		}
    }
    public function update_status($id = null) 
    {
        if (is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
        $this->MoneyTransferAccount->updateAll(array(
            'MoneyTransferAccount.is_default' => 0
        ) , array(
            'MoneyTransferAccount.user_id' => $this->Auth->user('id') ,
        ));
        $this->MoneyTransferAccount->updateAll(array(
            'MoneyTransferAccount.is_default' => 1
        ) , array(
            'MoneyTransferAccount.id' => $id
        ));
		$this->set('iphone_response', array("message" => __l('Primary money transfer account has been updated'), "error" => 0));
        $this->Session->setFlash(__l('Primary money transfer account has been updated') , 'default', null, 'success');
		if ($this->RequestHandler->prefers('json')) {
				Cms::dispatchEvent('Controller.MoneyTransferAccount.UpdateStatus', $this, array());
		}else{
			$this->redirect(array(
				'controller' => 'money_transfer_accounts',
				'action' => 'index'
			));
		}
    }
}
?>