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
class PaymentsController extends AppController
{
    public $name = 'Payments';
    public $components = array(
        'Email',
    );
	public $permanentCacheAction = array(
		'public' => array(
			'membership_pay_now',
		) ,
    );
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'Payment.connect',
            'Payment.contact',
            'Payment.accept',
            'Payment.negotiation_discount',
            'ItemUser.message',
            'Payment.wallet',
            'Payment.normal',
            'Payment.is_agree_terms_conditions',
            'Payment.payment_gateway_id',
            'Payment.payment_type',
            'Payment.is_show_new_card',
            'Payment.standard_connect',
			'User.payment_gateway_id',
			'User.wallet',
			'User.normal',
			'User.payment_id',
            'User.gateway_method_id',
            'Sudopay'
        );
        parent::beforeFilter();
    }
    public function membership_pay_now($user_id = null, $hash = null)
    {
        $this->pageTitle = __l('Membership Fee');
        App::import('Model', 'User');
        $this->User = new User();
        $gateway_options = array();
        if ($this->RequestHandler->prefers('json') && ($this->request->is('post'))){
            $this->request->data['User'] = $this->request->data;
        }
        if (!empty($this->request->data['User']['id'])) {
            $user_id = $this->request->data['User']['id'];
        }
        if (is_null($user_id) or is_null($hash)) {
            if ($this->RequestHandler->prefers('json')){
            $this->set('iphone_response', array("message" => __l('Invalid request') , "error" => 1));
            }else{
            throw new NotFoundException(__l('Invalid request'));
            }
        }
        if ($this->User->isValidActivateHash($user_id, $hash)) {
			$user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $user_id,
                ) ,
                'recursive' => -1,
            ));
            if (empty($user)) {
                if ($this->RequestHandler->prefers('json')){
                    $this->set('iphone_response', array("message" => __l('Invalid request') , "error" => 1));
                }else{
                throw new NotFoundException(__l('Invalid request'));
                }
            }
            $this->pageTitle = __l('Pay Membership Fee') . ' - '. $user['User']['username'];
            $total_amount = Configure::read('user.signup_fee');
            $total_amount = round($total_amount, 2);
            if (!empty($this->request->data)) {
                $this->request->data['User']['sudopay_gateway_id'] = 0;
                if (strpos($this->request->data['User']['payment_gateway_id'], 'sp_') >= 0) {
                    $this->request->data['User']['sudopay_gateway_id'] = str_replace('sp_', '', $this->request->data['User']['payment_gateway_id']);
                    $this->request->data['User']['payment_gateway_id'] = ConstPaymentGateways::SudoPay;
                }
                if (empty($this->request->data['User']['payment_gateway_id'])) {
                    if ($this->RequestHandler->prefers('json')){
                        $this->set('iphone_response', array("message" => __l('Please select the payment type') , "error" => 1));
                    }else{
                    $this->Session->setFlash(__l('Please select the payment type') , 'default', null, 'error');
                    }
                } else {
					$_data = array();
					$_data['User']['id'] = $this->request->data['User']['id'];
					$_data['User']['payment_gateway_id'] = $this->request->data['User']['payment_gateway_id'];
					$_data['User']['sudopay_gateway_id'] = $this->request->data['User']['sudopay_gateway_id'];
					$this->User->save($_data);
					if ($this->request->data['User']['payment_gateway_id'] == ConstPaymentGateways::SudoPay) {
						$this->loadModel('Sudopay.Sudopay');
						$sudopay_gateway_settings = $this->Sudopay->GetSudoPayGatewaySettings();
						$this->set('sudopay_gateway_settings', $sudopay_gateway_settings);
						if ($sudopay_gateway_settings['is_payment_via_api'] == ConstBrandType::VisibleBranding) {
							$sudopay_data = $this->Sudopay->getSudoPayPostData($this->request->data['User']['id'], ConstPaymentType::SignupFee);
							$sudopay_data['merchant_id'] = $sudopay_gateway_settings['sudopay_merchant_id'];
							$sudopay_data['website_id'] = $sudopay_gateway_settings['sudopay_website_id'];
							$sudopay_data['secret_string'] = $sudopay_gateway_settings['sudopay_secret_string'];
							$sudopay_data['action'] = 'capture';
							$this->set('sudopay_data', $sudopay_data);
						} else {
							$this->request->data['Sudopay'] = !empty($this->request->data['Sudopay']) ? $this->request->data['Sudopay'] : '';
							$return = $this->Sudopay->processPayment($this->request->data['User']['id'], ConstPaymentType::SignupFee, $this->request->data['Sudopay']);
							if (!empty($return['pending'])) {
								$this->Session->setFlash($return['error_message'] . __l(' Once payment is received, it will be processed.') , 'default', null, 'success');
                                if ($this->RequestHandler->prefers('json')){
                                    $this->set('iphone_response', array("message" => $return['error_message'] . __l(' Once payment is received, it will be processed.') , "error" => 0));
                                }else{
								$this->redirect(Router::url('/', true));
                                }
							} elseif (!empty($return['success'])) {
								$this->Payment->processUserSignupPayment($this->request->data['User']['id'], ConstPaymentGateways::SudoPay);
								$this->Session->setFlash(__l('You have paid signup fee successfully') , 'default', null, 'success');
                                if ($this->RequestHandler->prefers('json')){
                                    $this->set('iphone_response', array("message" => __l('You have paid signup fee successfully') , "error" => 0));
                                }else{
								$this->redirect(Router::url('/', true));
                                }
							} elseif (!empty($return['error'])) {
								$this->Session->setFlash($return['error_message'] . '. ' . __l('Your payment could not be completed.') , 'default', null, 'error');
                                $this->set('iphone_response', array("message" => $return['error_message'] . '. ' . __l('Your payment could not be completed') , "error" => 1));
							}
						}
					}
				}
            } else {
                $this->request->data = $user;
            }
            $this->set('total_amount', $total_amount);
            $this->set('user', $user);
        } else {
            if ($this->RequestHandler->prefers('json')){
                $this->set('iphone_response', array("message" => __l('Invalid request') , "error" => 1));
            }else{
            throw new NotFoundException(__l('Invalid request'));
            }
        }
        if ($this->RequestHandler->prefers('json'))
		{
            Cms::dispatchEvent('Controller.Paymentmembership_pay_now', $this);
		}
    }
    //send welcome mail for new user
    public function _sendWelcomeMail($user_id, $user_email, $username)
    {
		App::import('Model', 'EmailTemplate');
		$this->EmailTemplate = new EmailTemplate();
        $email = $this->EmailTemplate->selectTemplate('Welcome Email');
        $emailFindReplace = array(
            '##USERNAME##' => $username,
            '##CONTACT_MAIL##' => Configure::read('site.contact_email') ,
            '##FROM_EMAIL##' => ($email['from'] == '##FROM_EMAIL##') ? Configure::read('site.from_email') : $email['from'],
        );
		$this->Payment->_sendEmail($email,$emailFindReplace,$user_email);
    }
    /** Method for getting sudopay gateways list for iPhone **/
    public function get_sudopay_gateways()
    {
            $sudoPayments['paymentGroup'] = array();
            $this->loadModel('SudopayPaymentGroup');
            $this->loadModel('SudopayPaymentGateway');
            $this->loadModel('Sudopay.SudopayPaymentGatewaysUser');
            $this->SudopayPaymentGatewaysUser = new SudopayPaymentGatewaysUser();
            
            if(!empty($this->request->params['named']['project_owner']) && $this->request->params['named']['project_owner'] > 0) {
                $user_id = $this->request->params['named']['project_owner'];
            } else {
                $user_id = $this->Auth->user('id');
            }
            
            $connected_gateways = $this->SudopayPaymentGatewaysUser->find('all', array(
                                                                                       'conditions' => array(
                                                                                                             'SudopayPaymentGatewaysUser.user_id' => $user_id ,
                                                                                                             ) ,
                                                                                       'recursive' => -1,
                                                                                       ));
            
            $user_connected_gateways = array();
            foreach($connected_gateways as $connected_gateway) {
                $user_connected_gateways[] = $connected_gateway['SudopayPaymentGatewaysUser']['sudopay_payment_gateway_id'];
            }
            
            if(!empty($this->request->params['named']['payment_for']) && ($this->request->params['named']['payment_for'] == 'membership_fee' || $this->request->params['named']['payment_for'] == 'wallet_fee' || $this->request->params['named']['payment_for'] == 'listing_fee')) {
                $sudoPay['paymentGateway'] = $this->SudopayPaymentGateway->find('all', array(
                                                                                             'recursive' => 0
                                                                                             ));
            } else {
                $sudoPay['paymentGateway'] = $this->SudopayPaymentGateway->find('all', array(
                                                                                             'conditions' => array(
                                                                                                                   'OR'=>array(
                                                                                                                               array(
                                                                                                                                     'SudopayPaymentGateway.is_marketplace_supported' => 0
                                                                                                                                     ),
                                                                                                                               array(
                                                                                                                                     'SudopayPaymentGateway.is_marketplace_supported' => 1,
                                                                                                                                     'SudopayPaymentGateway.sudopay_gateway_id' => $user_connected_gateways
                                                                                                                                     )
                                                                                                                               ),
                                                                                                                   ),
                                                                                             'recursive' => 0
                                                                                             ));
            }
            
            foreach($sudoPay['paymentGateway'] as $k => $v)
            {
                $group_ids[] = $v['SudopayPaymentGateway']['sudopay_payment_group_id'];
            }
            
            $sudoPayments['paymentGroup'] = $this->SudopayPaymentGroup->find('all',array(
                                                                                         'conditions'=>array(
                                                                                                             'SudopayPaymentGroup.id' => $group_ids,
                                                                                                             ),
                                                                                         'recursive' => -1,
                                                                                         ));
            
            $subcategory = array();
            foreach($sudoPayments['paymentGroup'] as $key => $main)
            {
                foreach($sudoPay['paymentGateway'] as $sudopayment)
                {
                    $out =unserialize($sudopayment['SudopayPaymentGateway']['sudopay_gateway_details']);
                    
                    if($sudopayment['SudopayPaymentGateway']['sudopay_payment_group_id'] == $main['SudopayPaymentGroup']['id'])
                    {
                        $subcategory['gateway_name'] = $sudopayment['SudopayPaymentGateway']['sudopay_gateway_name'];
                        $subcategory['gateway_id'] = $sudopayment['SudopayPaymentGateway']['sudopay_gateway_id'];
                        $subcategory['gateway_group_id'] = $sudopayment['SudopayPaymentGateway']['sudopay_payment_group_id'];
                        $subcategory['gateway_thumb_url'] = 'http:'.$out['thumb_url'];
                        $subcategory['id'] = $sudopayment['SudopayPaymentGateway']['id'];
                        $sudoPayments['paymentGroup'][$key]['SudopayPaymentGroup']['GatewayTypes'][] = $subcategory;
                   }
                }
            }
     
        if($this->request->params['named']['payment_for'] != 'wallet_fee' && ((!empty($this->request->params['named']['project_owner']) && $this->request->params['named']['project_owner'] > 0) || (!empty($this->request->params['named']['payment_for']) && ($this->request->params['named']['payment_for'] == 'listing_fee')))) {
            if (isPluginEnabled('Wallet')) {
                App::import('Model', 'User');
                $this->loadModel('User');
                $user = $this->User->find('first', array(
                                                         'conditions' => array(
                                                                               'User.id =' =>$this->request->params['named']['current_user'] ,
                                                                               ),
                                                         'fields' => array(
                                                                           'User.id',
                                                                           'User.available_wallet_amount',
                                                                           ) ,
                                                         'recursive' => -1,
                                                         ));
                
                $walletPayment = array();
                $walletPayment['SudopayPaymentGroup']['name'] = "Wallet";
                $walletPayment['SudopayPaymentGroup']['id'] = ConstPaymentGateways::Wallet;
                $walletPayment['SudopayPaymentGroup']['payment_gateway_id'] = ConstPaymentGateways::Wallet;
                $walletPayment['SudopayPaymentGroup']['available_wallet_amount'] = $user['User']['available_wallet_amount'];
                $walletPayment['SudopayPaymentGroup']['thumb_url'] = preg_replace('#^https?://#', '', (Cache::read('site_url_for_shell', 'long'))) .'img/wallet-icon.png';
                array_push($sudoPayments['paymentGroup'], $walletPayment);
            }
        }
        
        if ($this->RequestHandler->prefers('json')) 
        {
            $this->request->data = array('paymentGroup' => array_values($sudoPayments['paymentGroup']));
            Cms::dispatchEvent('Controller.Payment.get_sudopay_gateways', $this);
        }
    }
    public function get_gateways()
    {
		App::import('Model', 'User');
		$this->loadModel('User');
        $countries = $this->User->UserProfile->Country->find('list', array(
            'fields' => array(
                'Country.iso_alpha2',
                'Country.name'
            ) ,
            'order' => array(
                'Country.name' => 'ASC'
            ) ,
            'recursive' => -1,
        ));
        $user_profile = $this->User->UserProfile->find('first', array(
            'conditions' => array(
                'UserProfile.user_id' => $this->Auth->user('id') ,
            ) ,
            'contain' => array(
                'User',
                'City',
                'State',
				'Country'
            ) ,
            'recursive' => 0,
        ));
        if (!empty($this->request->params['named']['type'])) {
			$type = $this->request->params['named']['type'];
            $gateway_types = $this->Payment->getGatewayTypes($type);
        } else {
            $gateway_types = $this->Payment->getGatewayTypes();
        }
        if (isPluginEnabled('Sudopay') && !empty($gateway_types[ConstPaymentGateways::SudoPay])) {
            $this->request->data[$this->request->params['named']['model']]['payment_gateway_id'] = ConstPaymentGateways::SudoPay;
        } elseif (isPluginEnabled('Wallet') && !empty($gateway_types[ConstPaymentGateways::Wallet])) {
            $this->request->data[$this->request->params['named']['model']]['payment_gateway_id'] = ConstPaymentGateways::Wallet;
        }
		if (isPluginEnabled('Sudopay')) {
			$this->loadModel('Sudopay.Sudopay');
			$this->Sudopay = new Sudopay();
			$response = $this->Sudopay->GetSudoPayGatewaySettings();
			$this->set('response', $response);
		}
        $this->set('model', $this->request->params['named']['model']);
        $this->set('foreign_id', $this->request->params['named']['foreign_id']);
        $this->set('transaction_type', $this->request->params['named']['transaction_type']);
        $this->set(compact('countries', 'user_profile', 'gateway_types'));
		// <-- For iPhone App code
		if ($this->RequestHandler->prefers('json')) {
			$response = Cms::dispatchEvent('Controller.Payment.GetGateway', $this, array());
		}		
    }
}
?>