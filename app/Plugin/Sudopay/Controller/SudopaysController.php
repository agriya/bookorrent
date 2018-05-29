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
class SudopaysController extends AppController
{
    public function beforeFilter()
    {
        if (in_array($this->request->action, array(
            'success_payment',
            'cancel_payment',
            'process_payment',
            'process_ipn',
			'update_account',
        ))) {
            $this->Security->validatePost = false;
        }
        parent::beforeFilter();
    }
	public function payout_connections()
    {
        //Quick fix to redirect success into app pages
        if(!empty($this->request->params['named']['is_iphone'])){
        $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
        $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
        $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
        if( $iPod || $iPhone || $iPad)
        {
            ob_start();
            header('location: bookorrent.com://payment/Payment connected Successfully');
            exit;
        }
        }
		$this->pageTitle = __l('Payout Preferences');
        $s = $this->Sudopay->getSudoPayObject();
        $this->loadModel('Sudopay.SudopayPaymentGateway');
        $supported_gateways = $this->SudopayPaymentGateway->find('all', array(
            'conditions' => array(
                'SudopayPaymentGateway.is_marketplace_supported' => 1
            ) ,
            'recursive' => -1,
        ));
        $connected_gateways = array();
        App::import('Model', 'Sudopay.SudopayPaymentGatewaysUser');
        $this->SudopayPaymentGatewaysUser = new SudopayPaymentGatewaysUser();
        $connected_gateways = $this->SudopayPaymentGatewaysUser->find('list', array(
            'conditions' => array(
                'SudopayPaymentGatewaysUser.user_id' => $this->Auth->user('id') ,
            ) ,
            'fields' => array(
                'SudopayPaymentGatewaysUser.sudopay_payment_gateway_id',
            ) ,
            'recursive' => -1,
        ));
		$user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id') ,
            ) ,
            'recursive' => -1,
        ));
        $this->set('user', $user);
        $this->set('connected_gateways', $connected_gateways);
        $this->set('supported_gateways', $supported_gateways);
		$request = !empty($this->request->params['named']['request'])?$this->request->params['named']['request']:'';
		$this->set('request', $request);
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.Sudopay.PayoutConnections', $this, array());
		}			
    }

    public function add_account($gateway_id, $user_id, $from='')
    {
        App::import('Model', 'Sudopay.SudopayPaymentGateway');
        $this->SudopayPaymentGateway = new SudopayPaymentGateway();
        $SudopayPaymentGateway = $this->SudopayPaymentGateway->find('first', array(
            'conditions' => array(
                'SudopayPaymentGateway.sudopay_gateway_id' => $gateway_id,
            ) ,
            'recursive' => -1
        ));
        App::import('Model', 'User');
        $this->User = new User();
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
            ) ,
            'recursive' => -1
        ));
		if($from == 'payout_connection'){
            if ($this->RequestHandler->prefers('json')){
            $return_url = Router::url(array(
                'controller' => 'sudopays',
                'action' => 'payout_connections',
                'is_iphone' => 1
                ) , true);
            }else{
			$return_url = Router::url(array(
				'controller' => 'sudopays',
				'action' => 'payout_connections'
			) , true);
            }
		} else {
			$return_url = Router::url(array(
				'controller' => 'items',
				'action' => 'add'
			) , true);
		}
        $post = array(
            'gateway_id' => $gateway_id,
            'notify_url' => Cache::read('site_url_for_shell', 'long') . 'sudopays/update_account/' . $gateway_id . '/' . $user['User']['id'],
            'return_url' => $return_url
        );
       
        if (!empty($user['User']['sudopay_receiver_account_id'])) {
            $post['receiver'] = $user['User']['sudopay_receiver_account_id'];
        }
        $post['name'] = $user['User']['username'];
        $post['email'] = $user['User']['email'];
        $s = $this->Sudopay->getSudoPayObject();
        $create_account = $s->callCreateReceiverAccount($post);
        if (!empty($create_account['error']['message'])) {
            $this->Session->setFlash($create_account['error']['message'], 'default', null, 'error');
            $this->set('iphone_response', array("message" => $create_account['error']['message'], "error" => 1));
			if (!$this->RequestHandler->prefers('json')) {
				if($from == 'item_add'){
					$this->redirect(array(
						'controller' => 'items',
						'action' => 'add',
						'error' =>'1'
					));			
				} else {
					$this->redirect(array(
						'controller' => 'sudopays',
						'action' => 'payout_connections',
						'error' =>'1'
					));	
				}
			}
        }        
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.Sudopay.AddAccount', $this, array('account' => $create_account));
		}else{
			header('location: ' . $create_account['gateways']['gateway_callback_url']);
			exit;
		}		
    }
	public function delete_account($gateway_id, $user_id, $from)
    {
		App::import('Model', 'Items.Item');
        $this->Item = new Item();
		App::import('Model', 'Items.ItemUser');
        $this->ItemUser = new ItemUser();
		$item=$this->Item->find('first', array(
            'conditions' => array(
                'Item.user_id' => $user_id,
				'Item.is_active' => 1,
            ) ,
            'recursive' => -1
        ));
		$user=$this->User->find('first', array(
			'conditions' => array(
				'User.id' => $user_id,
			) ,
			'recursive' => -1
        ));
		$connected_gateways=$this->Sudopay->GetUserConnectedGateways($user_id);
		// Need to revise this
		  $conditions= array();
		  $conditions['ItemUser.owner_user_id']=$user_id;
		  $conditions['ItemUser.sudopay_gateway_id']=$gateway_id;
		  $conditions['Not']['ItemUser.item_user_status_id'] = array(
            ConstItemUserStatus::Canceled,
            ConstItemUserStatus::Rejected,
            ConstItemUserStatus::Expired,
            ConstItemUserStatus::CanceledByAdmin,
			ConstItemUserStatus::Completed,
			);
		  $itemUser=$this->ItemUser->find('first', array(
              'conditions' => $conditions,
               'recursive' => -1
          ));
		  if(empty($itemUser)){  // check for itemUser which are in penidng payment status which uses this payment gateway
			App::import('Model', 'Sudopay.SudopayPaymentGatewaysUser');
			$this->SudopayPaymentGatewaysUser = new SudopayPaymentGatewaysUser();
			$SudopayPaymentGatewaysUser = $this->SudopayPaymentGatewaysUser->find('first', array(
				'conditions' => array(
					'SudopayPaymentGatewaysUser.sudopay_payment_gateway_id' => $gateway_id,
					'SudopayPaymentGatewaysUser.user_id' => $user_id,
				) ,
				'recursive' => -1
			));
			// From Account delete from sudopay
			/*$receiver_id=$user['User']['sudopay_receiver_account_id'];
			$s = $this->Sudopay->getSudoPayObject();
			$response = $s->callDisconnectGateway($gateway_id,$receiver_id);*/
			if(empty($item) || (!empty($item) && (count($connected_gateways)>'1'))){ // Check for active item
				if ($this->SudopayPaymentGatewaysUser->delete($SudopayPaymentGatewaysUser['SudopayPaymentGatewaysUser']['id'])) {
					$this->Session->setFlash(__l('You have successfully disconnected') , 'default', null, 'success');
					$this->set('iphone_response', array("message" => __l('You have successfully disconnected'), "error" => 0));
				}
			} else {
				$this->set('iphone_response', array("message" => __l('Sorry you have active item in your item listing. So you can\'t disconnect this payment gateway.'), "error" => 1));
				$this->Session->setFlash(__l('Sorry you have active item in your item listing. So you can\'t disconnect this payment gateway.') , 'default', null, 'error');
			}
		} else {
			$this->set('iphone_response', array("message" => __l('Sorry you have some booked listing which using this payment gateway. So you can\'t disconnect this payment gateway.'), "error" => 1));
			$this->Session->setFlash(__l('Sorry you have some booked listing which using this payment gateway. So you can\'t disconnect this payment gateway.') , 'default', null, 'error');
		} 
		if (!$this->RequestHandler->prefers('json')) {
			if($from == 'item_add'){
				$this->redirect(array(
					'controller' => 'items',
					'action' => 'add',
				));			
			} else {
				$this->redirect(array(
					'controller' => 'sudopays',
					'action' => 'payout_connections',
				));	
			}
			$this->autoRender = false;
		}
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json') && !empty($_GET['key'])) {
			$response = Cms::dispatchEvent('Controller.Sudopay.DeleteAccount', $this, array());
		}		
    }
    public function update_account($gateway_id, $user_id)
    {
        if (empty($gateway_id) || empty($user_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $s = $this->Sudopay->getSudoPayObject();
        if ($s->isValidIPNPost($_POST) && empty($_POST['error_code'])) {
            $this->loadModel('User');
            $data = array();
            $data['id'] = $user_id;
            $data['sudopay_receiver_account_id'] = $_POST['id'];
            $this->User->save($data);
            App::import('Model', 'Sudopay.SudopayPaymentGatewaysUser');
            $this->SudopayPaymentGatewaysUser = new SudopayPaymentGatewaysUser();
            $sudopayUser = $this->SudopayPaymentGatewaysUser->find('first', array(
                'conditions' => array(
                    'SudopayPaymentGatewaysUser.user_id' => $user_id,
                    'SudopayPaymentGatewaysUser.sudopay_payment_gateway_id' => $_POST['gateway_id'],
                ) ,
                'recursive' => -1
            ));
            if (empty($sudopayUser)) {
                $data = array();
                $data['user_id'] = $user_id;
                $data['sudopay_payment_gateway_id'] = $_POST['gateway_id'];
                $this->SudopayPaymentGatewaysUser->create();
                $this->SudopayPaymentGatewaysUser->save($data);
            }
			$this->Session->setFlash(__l('You have successfully connected') , 'default', null, 'success');
        }
        $this->autoRender = false;
    }
    public function process_payment($foreign_id, $transaction_type)
    {
        $return = $this->Sudopay->processPayment($foreign_id, $transaction_type);
        if (!empty($return)) {
            return $return;
        }
        $this->autoRender = false;
    }
    public function process_ipn($foreign_id, $transaction_type)
    {
		$this->Sudopay->_saveIPNLog();
        $s = $this->Sudopay->getSudoPayObject();
        if ($s->isValidIPNPost($_POST)) {
            $this->_processPayment($foreign_id, $transaction_type, $_POST);
        }
        if (!$this->RequestHandler->prefers('json')) {
            $response = Cms::dispatchEvent('Controller.Sudopays.ProcessIPN', $this, array());
		}
        $this->autoRender = false;
    }
    private function _processPayment($foreign_id, $transaction_type, $post)
    {
		$redirect = '';
        $s = $this->Sudopay->getSudoPayObject();
        switch ($transaction_type) {
            case ConstPaymentType::BookingAmount:
                App::import('Model', 'Items.ItemUser');
                $this->ItemUser = new ItemUser();
                $_data = array();
                $_data['ItemUser']['id'] = $foreign_id;
                $_data['ItemUser']['sudopay_payment_id'] = $post['id'];
                $_data['ItemUser']['sudopay_pay_key'] = $post['paykey'];
                $this->ItemUser->save($_data);
                $itemUser = $this->ItemUser->find('first', array(
                    'conditions' => array(
                        'ItemUser.id' => $foreign_id
                    ) ,
                    'contain' => array(
                        'Item',
                    ) ,
                    'recursive' => 2
                ));
                if (!empty($post['status']) && in_array($post['status'], array('Authorized', 'Captured')) && $itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::PaymentPending) {
					$this->ItemUser->Item->processPayment($itemUser['Item']['id'], $itemUser['ItemUser']['price'], ConstPaymentGateways::SudoPay, ConstPaymentType::BookingAmount, $itemUser['ItemUser']['id']);
                } elseif (!empty($post['status']) && in_array($post['status'], array('Voided', 'Refunded', 'Canceled')) && !in_array($itemUser['ItemUser']['item_user_status_id'], array(
                    ConstItemUserStatus::Expired,
                    ConstItemUserStatus::Rejected,
                    ConstItemUserStatus::Canceled,
                    ConstItemUserStatus::CanceledByAdmin
                ))) {
                    $this->ItemUser->updateStatus($foreign_id, ConstItemUserStatus::Canceled, ConstPaymentGateways::SudoPay);
                }
                $this->Session->setFlash(sprintf(__l('You have successfully %s') , $itemUser['Item']['title']) , 'default', null, 'success');
                $this->set('iphone_response', array("message" =>sprintf(__l('You have successfully %s') , $itemUser['Item']['title']) , "error" => 0));

                if (isPluginEnabled('SocialMarketing')) {
                    $redirect = Router::url(array(
                        'controller' => 'social_marketings',
                        'action' => 'publish',
                        $foreign_id,
                        'type' => 'facebook',
                        'publish_action' => 'fund',
                    ) , true);
                } else {
                    $redirect = Router::url(array(
                        'controller' => 'items',
                        'action' => 'view',
                        $itemUser['Item']['slug']
                    ) , true);
                }
                break;
				
            case ConstPaymentType::ItemListingFee:
                App::import('Model', 'Items.Item');
                $this->Item = new Item();
                $_data = array();
                $_data['Item']['id'] = $foreign_id;
                $_data['Item']['item_sudopay_payment_id'] = $post['id'];
                $_data['Item']['item_sudopay_pay_key'] = $post['paykey'];
                $this->Item->save($_data);
                $item = $this->Item->find('first', array(
                    'conditions' => array(
                        'Item.id' => $foreign_id
                    ) ,
                    'recursive' => 0
                ));
                if(empty($item['Item']['is_paid'])) {
					if (!empty($post['status']) && $post['status'] == 'Captured') {
						$total_amount = Configure::read('item.item_fee');
						$this->Item->processPayment($foreign_id, $total_amount, ConstPaymentGateways::SudoPay, ConstPaymentType::ItemListingFee);
						$redirect = Router::url(array(
							'controller' => items,
							'action' => 'view',
							'item_id' => $item['Item']['slug'],
						) , true);
						$this->Sudopay->_savePaidLog($foreign_id, $post, 'Item');
					}
                }
                if (empty($redirect)) {
                    $redirect = Router::url(array(
						'controller' => items,
						'action' => 'view',
						'item_id' => $item['Item']['slug'],
					) , true);
                }
                break;
            case ConstPaymentType::AddAmountToWallet:
                if (isPluginEnabled('Wallet')) {
                    $this->loadModel('Wallet.Wallet');
                    $this->loadModel('User');
                    $_data = array();
                    $_data['UserAddWalletAmount']['id'] = $foreign_id;
                    $_data['UserAddWalletAmount']['sudopay_payment_id'] = $post['id'];
                    $_data['UserAddWalletAmount']['sudopay_pay_key'] = $post['paykey'];
                    $this->User->UserAddWalletAmount->save($_data);
                    $userAddWalletAmount = $this->User->UserAddWalletAmount->find('first', array(
                        'conditions' => array(
                            'UserAddWalletAmount.id' => $foreign_id
                        ) ,
                        'contain' => array(
                            'User'
                        ) ,
                        'recursive' => 1,
                    ));
                    if (empty($userAddWalletAmount)) {
                        if ($this->RequestHandler->prefers('json')) {
				            $this->set('iphone_response', array("message" =>__l('Invalid request') , "error" => 1));
						}else{
							throw new NotFoundException(__l('Invalid request'));
						}
                    }
					if(empty($userAddWalletAmount['UserAddWalletAmount']['is_success'])) {
						if (!empty($post['status']) && $post['status'] == 'Captured') {
							if ($this->Wallet->processAddtoWallet($foreign_id, ConstPaymentGateways::SudoPay)) {
								$this->Session->setFlash(__l('Amount added to wallet') , 'default', null, 'success');
                                $this->set('iphone_response', array("message" =>__l('Amount added to wallet') , "error" => 0));
								$this->Sudopay->_savePaidLog($foreign_id, $post, 'UserAddWalletAmount');
							} else {
								$this->Session->setFlash(__l('Amount could not be added to wallet') , 'default', null, 'error');
                                $this->set('iphone_response', array("message" =>__l('Amount could not be added to wallet') , "error" => 1));
							}
						} else {
							$this->Session->setFlash(__l('Amount could not be added to wallet') , 'default', null, 'error');
                            $this->set('iphone_response', array("message" =>__l('Amount could not be added to wallet') , "error" => 1));
						}
					}
                }
                $redirect = Router::url(array(
                    'controller' => 'users',
                    'action' => 'dashboard',
                    'admin' => false,
                ) , true);
                break;

            case ConstPaymentType::SignupFee:
                $this->loadModel('Payment');
                $this->loadModel('User');
                $_data = array();
                $_data['User']['id'] = $foreign_id;
                $_data['User']['sudopay_payment_id'] = $post['id'];
                $_data['User']['sudopay_pay_key'] = $post['paykey'];
                $this->User->save($_data);
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $foreign_id,
                    ) ,
                    'recursive' => -1,
                ));
                if (!empty($post['status']) && $post['status'] == 'Captured') {
                    App::import('Model', 'Payment');
                    $this->Payment = new Payment();
                    if ($this->Payment->processUserSignupPayment($foreign_id, ConstPaymentGateways::SudoPay)) {
                        if (empty($user['User']['is_openid_register']) && empty($user['User']['is_linkedin_register']) && empty($user['User']['is_google_register']) && empty($user['User']['is_googleplus_register']) && empty($user['User']['is_yahoo_register']) && empty($user['User']['is_facebook_register']) && empty($user['User']['is_twitter_register'])) {
                            if (empty($user['User']['is_email_confirmed']) && Configure::read('user.is_admin_activate_after_register') && Configure::read('user.is_email_verification_for_register')) {
                                $this->Session->setFlash(__l('You have paid membership fee successfully. Once you verified your email and administrator approved your account will be activated.') , 'default', null, 'success');
                                $this->set('iphone_response', array("message" =>__l('You have paid membership fee successfully. Once you verified your email and administrator approved your account will be activated.') , "error" => 0));
                            } else if (Configure::read('user.is_admin_activate_after_register')) {
                                $this->Session->setFlash(__l('You have paid membership fee successfully, will be activated once administrator approved') , 'default', null, 'success');
                                $this->set('iphone_response', array("message" =>__l('You have paid membership fee successfully, will be activated once administrator approved') , "error" => 0));
                            } else if (empty($user['User']['is_email_confirmed']) && Configure::read('user.is_email_verification_for_register')) {
                                $this->Session->setFlash(sprintf(__l('You have paid membership fee successfully. Now you can login with your %s after verified your email') , Configure::read('user.using_to_login')) , 'default', null, 'success');
                                $this->set('iphone_response', array("message" =>sprintf(__l('You have paid membership fee successfully. Now you can login with your %s after verified your email') , Configure::read('user.using_to_login')) , "error" => 0));
                            } else {
                                $this->Session->setFlash(sprintf(__l('You have paid membership fee successfully. Now you can login with your %s') , Configure::read('user.using_to_login')) , 'default', null, 'success');
                                $this->set('iphone_response', array("message" =>sprintf(__l('You have paid membership fee successfully. Now you can login with your %s') , Configure::read('user.using_to_login')) , "error" => 0));
                            }
                            $this->Auth->logout();
                        } else {
                            if (Configure::read('user.is_admin_activate_after_register')) {
                                $this->Session->setFlash(__l('You have paid membership fee successfully, will be activated once administrator approved') , 'default', null, 'success');
                                $this->set('iphone_response', array("message" =>__l('You have paid membership fee successfully, will be activated once administrator approved') , "error" => 0));
                            } else {
                                $this->Session->setFlash(__l('You have paid membership fee successfully.') , 'default', null, 'success');
                                $this->set('iphone_response', array("message" =>__l('You have paid membership fee successfully.') , "error" => 0));
                            }
                        }
                        $this->Sudopay->_savePaidLog($foreign_id, $response_data, 'User');
                    }
                }
                $redirect = Router::url(array(
                    'controller' => 'users',
                    'action' => 'login',
                    'admin' => false
                ) , true);
                break;
            }
            if ($this->RequestHandler->prefers('json')) {
                return '';
            }
            return $redirect;
        }
        public function success_payment($foreign_id, $transaction_type)
        {
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
            if( $iPod || $iPhone || $iPad)
            {
                ob_start();
                header('location: bookorrent.com://payment/Payment successfully completed');
                exit;
            }elseif(($transaction_type == ConstPaymentType::AddAmountToWallet) && $this->RequestHandler->prefers('json'))
            {
                $this->set('iphone_response', array("message" =>__l('Payment successfully completed') , "error" => 0));
                $response = Cms::dispatchEvent('Controller.Sudopays.SuccessPayment', $this, array());
            }else{
            	$this->Session->setFlash(__l('Payment successfully completed') , 'default', null, 'success');
                $redirect = $this->_getRedirectUrl($foreign_id, $transaction_type);
                $this->redirect($redirect);
            }
        }
        public function cancel_payment($foreign_id, $transaction_type)
        {
            $iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
            $iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
            $iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
            if( $iPod || $iPhone || $iPad)
            {
                ob_start();
                header('location: bookorrent.com://payment/Payment Failed. Please, try again');
                exit;
            }
            elseif(($transaction_type == ConstPaymentType::Wallet) && $this->RequestHandler->prefers('json'))
            {
                $this->set('iphone_response', array("message" =>__l('Payment Failed. Please, try again') , "error" => 1));
                $response = Cms::dispatchEvent('Controller.Sudopays.CancelPayment', $this, array());
                
            }else{
              	$this->Session->setFlash(__l('Payment Failed. Please, try again') , 'default', null, 'error');
                $redirect = $this->_getRedirectUrl($foreign_id, $transaction_type);
                $this->redirect($redirect);
            }
        }
        private function _getRedirectUrl($foreign_id, $transaction_type)
        {
            switch ($transaction_type) {
                case ConstPaymentType::BookingAmount:
					$redirect = Router::url(array(
						'controller' => 'item_users',
						'action' => 'index',
						'type' => 'mytours',
						'status' => 'waiting_for_acceptance'
					) , true);
                    break;
	
                case ConstPaymentType::ItemListingFee:
                    App::import('Model', 'Items.Item');
					$this->Item = new Item();
					$item = $this->Item->find('first', array(
						'conditions' => array(
							'Item.id' => $foreign_id
						) ,
						'recursive' => 0
					));
					$redirect = Router::url(array(
						'controller' => 'items',
						'action' => 'view',
						$item['Item']['slug'],
					) , true);
                    break;

                case ConstPaymentType::AddAmountToWallet:
                    $redirect = Router::url(array(
                        'controller' => 'wallets',
                        'action' => 'add_to_wallet'
                    ) , true);
                    break;

                case ConstPaymentType::SignupFee:
                    $redirect = Router::url(array(
                        'controller' => 'users',
                        'action' => 'register',
                    ) , true);
                    break;

                default:
                    $redirect = Router::url('/');
                    break;
            }
            return $redirect;
        }
        public function admin_sudopay_admin_info()
        {
            $this->loadModel('Sudopay.SudopayPaymentGateway');
            $this->loadModel('Sudopay.Sudopay');
            $response = $this->Sudopay->GetSudoPayGatewaySettings();
            $this->set('gateway_settings', $response);
            $supported_gateways = $this->SudopayPaymentGateway->find('all', array(
                'recursive' => -1,
            ));
            $used_gateway_actions = array(
                'Marketplace-Auth',
                'Marketplace-Auth-Capture',
                'Marketplace-Void',
                'Capture'
            );
            $this->set(compact('supported_gateways', 'used_gateway_actions'));
        }
        public function confirmation($foreign_id, $transaction_type)
        {
            $this->pageTitle = __l('Payment Confirmation');
            $redirect = $this->_getRedirectUrl($foreign_id, $transaction_type);
            if ($transaction_type == ConstPaymentType::ItemListingFee) {
                App::uses('Items.Item', 'Model');
                $obj = new Item();
                $Data = $obj->find('first', array(
                    'conditions' => array(
                        'Item.id' => $foreign_id,
                    ) ,
                    'contain' => array(
                        'User',
                    ) ,
                    'recursive' => 0
                ));
                $sudopay_token = $Data['Item']['item_sudopay_token'];
                $sudopay_revised_amount = $Data['Item']['item_sudopay_revised_amount'];
                $receiver_data = $obj->getReceiverdata($foreign_id, $transaction_type, $Data['User']['email']);
                $amount = $receiver_data['amount']['0'];
            } elseif ($transaction_type == ConstPaymentType::SignupFee) {
                App::uses('User', 'Model');
                $obj = new User();
                $Data = $obj->find('first', array(
                    'conditions' => array(
                        'User.id' => $foreign_id,
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email'
                    ) ,
                    'recursive' => -1
                ));
                $sudopay_token = $Data['User']['sudopay_token'];
                $sudopay_revised_amount = $Data['User']['sudopay_revised_amount'];
                $amount = Configure::read('user.signup_fee');
            } elseif ($transaction_type == ConstPaymentType::Wallet) {
                App::import('Model', 'Wallet.UserAddWalletAmount');
                $obj = new UserAddWalletAmount();
                $Data = $obj->find('first', array(
                    'conditions' => array(
                        'UserAddWalletAmount.id' => $foreign_id,
                    ) ,
                    'contain' => array(
                        'User',
                    ) ,
                    'recursive' => 0
                ));
                $sudopay_token = $Data['UserAddWalletAmount']['sudopay_token'];
                $sudopay_revised_amount = $Data['UserAddWalletAmount']['sudopay_revised_amount'];
                $amount = $Data['UserAddWalletAmount']['amount'];
            }
            if (!empty($this->request->data) && !empty($this->request->data['Sudopay']['confirm'])) {
                $s = $this->Sudopay->GetSudoPayObject();
                $post_data = array();
                $post_data['confirmation_token'] = $sudopay_token;
                $response = $s->callCaptureConfirm($post_data);
                if (empty($response['error']['code'])) {
                    if (!empty($response['status']) && $response['status'] == 'Pending') {
                        $return['pending'] = 1;
                    } elseif (!empty($response['status']) && $response['status'] == 'Captured') {
                        $return['success'] = 1;
                    } elseif (!empty($response['gateway_callback_url'])) {
                        header('location: ' . $response['gateway_callback_url']);
                        exit;
                    }
                } else {
                    $return['error'] = 1;
                    $return['error_message'] = $response['error']['message'];
                }
                if (!empty($return['success'])) {
                    if ($transaction_type == ConstPaymentType::ItemListingFee) {
                        $obj->processPayment($foreign_id, $amount, ConstPaymentGateways::SudoPay, ConstPaymentType::ItemListingFee);
                        $this->Session->setFlash(sprintf(__l('You have paid %s fee successfully.'), Configure::read('item.alt_name_for_item_singular_small')) , 'default', null, 'success');
                        $this->set('iphone_response', array("message" =>sprintf(__l('You have paid %s fee successfully.'), Configure::read('item.alt_name_for_item_singular_small')) , "error" => 0));
                    } elseif ($transaction_type == ConstPaymentType::SignupFee) {
                        App::import('Model', 'Payment');
                        $obj = new Payment();
                        $obj->processUserSignupPayment($foreign_id, ConstPaymentGateways::SudoPay);
                        $this->Session->setFlash(__l('You have paid signup fee successfully') , 'default', null, 'success');
                        $this->set('iphone_response', array("message" =>__l('You have paid membership fee successfully.') , "error" => 0));
                    } elseif ($transaction_type == ConstPaymentType::Wallet) {
                        App::import('Model', 'Wallet.Wallet');
						$obj = new Wallet();
						$obj->processAddtoWallet($foreign_id, ConstPaymentGateways::SudoPay);
                        $this->Session->setFlash(__l('Amount added to wallet') , 'default', null, 'success');
                        $this->set('iphone_response', array("message" =>__l('Amount added to wallet') , "error" => 0));
                    }
                } elseif (!empty($return['error'])) {
                    $return['error_message'].= '. ';
                    $this->Session->setFlash($return['error_message'] . __l('Your payment could not be completed.') , 'default', null, 'error');
                    $this->set('iphone_response', array("message" =>$return['error_message'] . __l('Your payment could not be completed.') , "error" => 1));
                } elseif (!empty($return['pending'])) {
                    $this->Session->setFlash($return['error_message'] . __l(' Once payment is received, it will be processed.') , 'default', null, 'success');
                    $this->set('iphone_response', array("message" =>$return['error_message'] . __l(' Once payment is received, it will be processed.') , "error" => 1));
                }
                if (!$this->RequestHandler->prefers('json')) {
                    $this->redirect($redirect);
                }
            }
            $this->set(compact('amount', 'foreign_id', 'transaction_type', 'redirect', 'sudopay_revised_amount'));
        }
        public function admin_synchronize()
		{
			$this->loadModel('PaymentGateway');
			$sudopay_gateway = $this->PaymentGateway->find('first', array(
				'conditions' => array(
					'PaymentGateway.id' => ConstPaymentGateways::SudoPay
				) ,
				'recursive' => -1
			));
			$s = $this->Sudopay->GetSudoPayObject();
			$currentPlan = $s->callPlan();
			if (!empty($currentPlan['error']['message'])) {
				if ($currentPlan['error']['code'] == 4) {
					if ($sudopay_gateway['PaymentGateway']['is_test_mode'] == 1) {
						$this->PaymentGateway->PaymentGatewaySetting->updateAll(array(
							'PaymentGatewaySetting.test_mode_value' => ConstBrandType::VisibleBranding,
						) , array(
							'PaymentGatewaySetting.payment_gateway_id' => ConstPaymentGateways::SudoPay,
							'PaymentGatewaySetting.name' => 'is_payment_via_api'
						));
					} else {
						$this->PaymentGateway->PaymentGatewaySetting->updateAll(array(
							'PaymentGatewaySetting.live_mode_value' => ConstBrandType::VisibleBranding,
						) , array(
							'PaymentGatewaySetting.payment_gateway_id' => ConstPaymentGateways::SudoPay,
							'PaymentGatewaySetting.name' => 'is_payment_via_api'
						));
					}
				}
				$this->Session->setFlash($currentPlan['error']['message'], 'default', null, 'error');
				$this->redirect(array(
					'controller' => 'payment_gateways',
					'action' => 'edit',
					ConstPaymentGateways::SudoPay,
					'admin' => true
				));
			}
			if ($currentPlan['brand'] == 'Transparent Branding') {
				$plan = ConstBrandType::TransparentBranding;
			} elseif ($currentPlan['brand'] == 'SudoPay Branding') {
				$plan = ConstBrandType::VisibleBranding;
			} elseif ($currentPlan['brand'] == 'Any Branding') {
				$plan = ConstBrandType::AnyBranding;
			}
			if ($sudopay_gateway['PaymentGateway']['is_test_mode'] == 1) {
				$this->PaymentGateway->PaymentGatewaySetting->updateAll(array(
					'PaymentGatewaySetting.test_mode_value' => $plan,
				) , array(
					'PaymentGatewaySetting.payment_gateway_id' => ConstPaymentGateways::SudoPay,
					'PaymentGatewaySetting.name' => 'is_payment_via_api'
				));
			} else {
				$this->PaymentGateway->PaymentGatewaySetting->updateAll(array(
					'PaymentGatewaySetting.live_mode_value' => $plan,
				) , array(
					'PaymentGatewaySetting.payment_gateway_id' => ConstPaymentGateways::SudoPay,
					'PaymentGatewaySetting.name' => 'is_payment_via_api'
				));
			}
			if ($sudopay_gateway['PaymentGateway']['is_test_mode'] == 1) {
				$this->PaymentGateway->PaymentGatewaySetting->updateAll(array(
					'PaymentGatewaySetting.test_mode_value' => "'" . $currentPlan['name'] . "'"
				) , array(
					'PaymentGatewaySetting.payment_gateway_id' => ConstPaymentGateways::SudoPay,
					'PaymentGatewaySetting.name' => 'sudopay_subscription_plan'
				));
			} else {
				// For live mode
				$this->PaymentGateway->PaymentGatewaySetting->updateAll(array(
					'PaymentGatewaySetting.live_mode_value' => "'" . $currentPlan['name'] . "'"
				) , array(
					'PaymentGatewaySetting.payment_gateway_id' => ConstPaymentGateways::SudoPay,
					'PaymentGatewaySetting.name' => 'sudopay_subscription_plan'
				));
			}
			$gateway_response = $s->callGateways();
			if (!empty($gateway_response['error']['message'])) {
				$this->Session->setFlash($gateway_response['error']['message'], 'default', null, 'error');
				$this->redirect(array(
					'controller' => 'payment_gateways',
					'action' => 'edit',
					ConstPaymentGateways::SudoPay,
					'admin' => true
				));
			}
			$this->loadModel('Sudopay.SudopayPaymentGateway');
			$this->loadModel('Sudopay.SudopayPaymentGroup');
			$this->SudopayPaymentGroup->deleteAll(array(
				'1 = 1'
			));
			$this->SudopayPaymentGateway->deleteAll(array(
				'1 = 1'
			));
			foreach($gateway_response['gateways'] as $gateway_group) {
				$group_data = array();
				$group_data['sudopay_group_id'] = $gateway_group['id'];
				$group_data['name'] = $gateway_group['name'];
				$group_data['thumb_url'] = $gateway_group['thumb_url'];
				$this->SudopayPaymentGroup->create();
				$this->SudopayPaymentGroup->save($group_data);
				$group_id = $this->SudopayPaymentGroup->id;
				foreach($gateway_group['gateways'] as $gateway) {
					$_data = array();
					$supported_actions = $gateway['supported_features'][0]['actions'];
					$_data['is_marketplace_supported'] = 0;
					if (in_array('Marketplace-Auth', $supported_actions)) {
						$_data['is_marketplace_supported'] = 1;
					}
					$_data['sudopay_gateway_id'] = $gateway['id'];
					$_data['sudopay_gateway_details'] = serialize($gateway);
					$_data['sudopay_gateway_name'] = $gateway['display_name'];
					$_data['sudopay_payment_group_id'] = $group_id;
					$this->SudopayPaymentGateway->create();
					$this->SudopayPaymentGateway->save($_data);
				}
			}
			$this->Session->setFlash( __l('ZazPay Payment Gateways have been synchronized'), 'default', null, 'success');
			$this->redirect(array(
				'controller' => 'payment_gateways',
				'action' => 'edit',
				ConstPaymentGateways::SudoPay,
				'admin' => true
			));
		}	
}
?>