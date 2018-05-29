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
/**
 * CounterCacheHabtmBehavior - add counter cache support for HABTM relations
 *
 * Based on CounterCacheBehavior by Derick Ng aka dericknwq
 *
 * @see http://bakery.cakephp.org/articles/view/counter-cache-behavior-for-habtm-relations
 * @author Yuri Pimenov aka Danaki (http://blog.gate.lv)
 * @version 2009-05-28
 */
class AffiliateBehavior extends ModelBehavior
{
    function afterSave(Model $model, $created) 
    {
		$affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
		if (array_key_exists($model->name, $affiliate_model)) {
			if ($created) {
				$this->__createAffiliate($model);
			} else {
				$this->__updateAffiliate($model);
			}
		}
        return true;
    }
    function __createAffiliate(Model $model) 
    {
        App::import('Core', 'Cookie');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $cookie = new CookieComponent($collection);
		App::import('Model', 'Items.ItemUser');
        $this->ItemUser = new ItemUser();
        $itemUser = $this->ItemUser->find('first', array(
            'conditions' => array(
				'ItemUser.id' => $model->id,
			),
            'recursive' => -1
        ));
        $referrer = $cookie->read('referrer');
        $this->User = $this->__getparentClass('User');
        $affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
        if (((!empty($referrer['refer_id'])) || (!empty($itemUser['User']['referred_by_user_id']))) && ($model->name == 'User')) {
            if (empty($referrer['refer_id'])) {
                $referrer['refer_id'] = $itemUser['User']['referred_by_user_id'];
            }
            // update refer_id
            $data['User']['referred_by_user_id'] = $referrer['refer_id'];
            $data['User']['id'] = $model->id;
            $this->User->save($data);
            // referred count update
            $this->User->updateAll(array(
                'User.referred_by_user_count' => 'User.referred_by_user_count + ' . '1'
            ) , array(
                'User.id' => $referrer['refer_id']
            ));
            if ($this->__CheckAffiliateUSer($referrer['refer_id'])) {
                $this->AffiliateType = $this->__getparentClass('Affiliates.AffiliateType');
                $affiliateType = $this->AffiliateType->find('first', array(
                    'conditions' => array(
                        'AffiliateType.id' => $affiliate_model['User']
                    ) ,
                    'fields' => array(
                        'AffiliateType.id',
                        'AffiliateType.commission',
                        'AffiliateType.affiliate_commission_type_id'
                    ) ,
                    'recursive' => -1
                ));
                $affiliate_commision_amount = 0;
                if (!empty($affiliateType)) {
                    if (($affiliateType['AffiliateType']['affiliate_commission_type_id'] == ConstAffiliateCommissionType::Percentage)) {
                        $affiliate_commision_amount = 0.0; //($itemUser['ItemUser']['commission_amount'] * $affiliateType['AffiliateType']['commission']) / 100;
                        
                    } else {
                        $affiliate_commision_amount = $affiliateType['AffiliateType']['commission'];
                    }
                }
                // set affiliate data
                $affiliate['Affiliate']['class'] = 'User';
                $affiliate['Affiliate']['foreign_id'] = $model->id;
                $affiliate['Affiliate']['affiliate_type_id'] = $affiliate_model['User'];
                $affiliate['Affiliate']['affliate_user_id'] = $referrer['refer_id'];
                $affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::PipeLine;
                $affiliate['Affiliate']['commission_holding_start_date'] = date('Y-m-d');
                $affiliate['Affiliate']['commission_amount'] = $affiliate_commision_amount;
                $this->__saveAffiliate($affiliate);
                $cookie->delete('referrer');
            }
        } else if ($model->name == 'ItemUser') {
            $this->ItemUser = $this->__getparentClass('Items.ItemUser');
            if (empty($referrer['refer_id'])) {
                if (isset($itemUser['ItemUser']['user_id']) && !empty($itemUser['ItemUser']['user_id'])) {
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $itemUser['ItemUser']['user_id']
                        ) ,
                        'fields' => array(
                            'User.id',
                            'User.username',
                            'User.referred_by_user_id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($user['User']['referred_by_user_id'])) {
                        if (Configure::read('affiliate.commission_on_every_item_booking')) {
                            $referrer['refer_id'] = $user['User']['referred_by_user_id'];
                        } else {
                            $itemorders = $this->ItemUser->find('count', array(
                                'conditions' => array(
                                    'ItemUser.id <>' => $model->id,
                                    'ItemUser.user_id' => $itemUser['ItemUser']['user_id'],
                                    'ItemUser.referred_by_user_id' => $user['User']['referred_by_user_id'],
                                ) ,
								'recursive' => -1
                            ));
                            if ($itemorders < 1) $referrer['refer_id'] = $user['User']['referred_by_user_id'];
                        }
                    }
                }
            }
            if (!empty($referrer['refer_id']) && $this->__CheckAffiliateUSer($referrer['refer_id'])) {
                $this->AffiliateType = $this->__getparentClass('Affiliates.AffiliateType');
                $affiliateType = $this->AffiliateType->find('first', array(
                    'conditions' => array(
                        'AffiliateType.id' => $affiliate_model['ItemUser']
                    ) ,
                    'fields' => array(
                        'AffiliateType.id',
                        'AffiliateType.commission',
                        'AffiliateType.affiliate_commission_type_id'
                    ) ,
                    'recursive' => -1
                ));
                $affiliate_commision_amount = 0;
                $admin_commision_amount = 0;
                if (!empty($affiliateType)) {
                    if (($affiliateType['AffiliateType']['affiliate_commission_type_id'] == ConstAffiliateCommissionType::Percentage)) {
                        $affiliate_commision_amount = ($itemUser['ItemUser']['price']*$affiliateType['AffiliateType']['commission']) /100;
                    } else {
                        $affiliate_commision_amount = ($itemUser['ItemUser']['price']-$affiliateType['AffiliateType']['commission']);
                    }
                    $admin_commision_amount = $itemUser['ItemUser']['booker_service_amount']-$affiliate_commision_amount;
                }
                if (!empty($itemUser['ItemUser']['item_user_status_id'])) {
                    $data['ItemUser']['item_user_status_id'] = $itemUser['ItemUser']['item_user_status_id'];
                }
                $data['ItemUser']['referred_by_user_id'] = $referrer['refer_id'];
                $data['ItemUser']['admin_commission_amount'] = $admin_commision_amount;
                $data['ItemUser']['affiliate_commission_amount'] = $affiliate_commision_amount;
                $data['ItemUser']['id'] = $model->id;
                $this->ItemUser->save($data['ItemUser']);
                // set affiliate data
                $affiliate['Affiliate']['class'] = 'ItemUser';
                $affiliate['Affiliate']['foreign_id'] = $model->id;
                $affiliate['Affiliate']['affiliate_type_id'] = $affiliate_model['ItemUser'];
                $affiliate['Affiliate']['affliate_user_id'] = $referrer['refer_id'];
                $affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::Pending;
                $affiliate['Affiliate']['commission_amount'] = $affiliate_commision_amount;
                $this->__saveAffiliate($affiliate);
                $cookie->delete('referrer');
                $this->User->updateAll(array(
                    'User.referred_booking_count' => 'User.referred_booking_count + ' . '1'
                ) , array(
                    'User.id' => $referrer['refer_id']
                ));
                $this->User->updateAll(array(
                    'User.affiliate_refer_booking_count' => 'User.affiliate_refer_booking_count + ' . '1'
                ) , array(
                    'User.id' => $itemUser['ItemUser']['user_id']
                ));
                $conditions['Affiliate.class'] = 'ItemUser';
                $conditions['Affiliate.foreign_id'] = $model->id;
                $affliates = $this->__findAffiliate($conditions);
                $this->ItemUser->Item->updateAll(array(
                    'Item.referred_booking_count' => 'Item.referred_booking_count + ' . '1'
                ) , array(
                    'Item.id' => $affliates['ItemUser']['item_id']
                ));
            }
        }
    }
    function __updateAffiliate($model) 
    {
		App::import('Model', 'Items.ItemUser');
        $this->ItemUser = new ItemUser();
        $itemUser = $this->ItemUser->find('first', array(
            'conditions' => array(
				'ItemUser.id' => $model->id,
			),
            'recursive' => -1
        ));
        if ($model->name == 'ItemUser' && isset($itemUser['ItemUser']['item_user_status_id']) && (!empty($itemUser['ItemUser']['is_payment_cleared']) || $itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Completed || $itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Canceled || $itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Rejected || $itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Expired || $itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::CanceledByAdmin)) {
            $conditions['Affiliate.class'] = 'ItemUser';
            $conditions['Affiliate.foreign_id'] = $model->id;
            $affliates = $this->__findAffiliate($conditions);
            if (!empty($affliates['ItemUser']['referred_by_user_id'])) {
                $affiliate['Affiliate']['id'] = $affliates['Affiliate']['id'];
                if (!empty($itemUser['ItemUser']['is_payment_cleared']) || $itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Completed) {
					if($affliates['Affiliate']['affiliate_status_id']==ConstAffiliateStatus::Pending)
					{
						$affiliate['Affiliate']['commission_holding_start_date'] = date('Y-m-d');
						$affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::PipeLine;
					}
                } else {
                    $affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::Canceled;
                    $this->User = $this->__getparentClass('User');
                    $this->User->updateAll(array(
                        'User.total_commission_canceled_amount' => 'User.total_commission_canceled_amount + ' . $affliates['Affiliate']['commission_amount']
                    ) , array(
                        'User.id' => $affliates['Affiliate']['affliate_user_id']
                    ));
                }
                $this->__saveAffiliate($affiliate);
            }
        }
    }
    function __saveAffiliate($data) 
    {
        $this->Affiliate = $this->__getparentClass('Affiliates.Affiliate');
        if (!isset($data['Affiliate']['id'])) {
            $this->Affiliate->create();
            $this->Affiliate->AffiliateUser->updateAll(array(
                'AffiliateUser.total_commission_pending_amount' => 'AffiliateUser.total_commission_pending_amount + ' . $data['Affiliate']['commission_amount']
            ) , array(
                'AffiliateUser.id' => $data['Affiliate']['affliate_user_id']
            ));
        }
        $this->Affiliate->save($data,false);
    }
    function __findAffiliate($condition) 
    {
        $this->Affiliate = $this->__getparentClass('Affiliates.Affiliate');
        $affiliate = $this->Affiliate->find('first', array(
            'conditions' => $condition,
            'recursive' => -1
        ));
        return $affiliate;
    }
    function __CheckAffiliateUSer($refer_user_id) 
    {
        $this->User = $this->__getparentClass('User');
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $refer_user_id
            ) ,
            'recursive' => -1
        ));
        if (!empty($user) && ($user['User']['is_affiliate_user'])) {
            return true;
        }
        return false;
    }
    function __getparentClass($parentClass) 
    {
        App::import('Model', $parentClass);
        $parentClassArr = explode('.', $parentClass);
        if (sizeof($parentClassArr) > 1) {
            $parentClass = $parentClassArr[1];
        }
        return new $parentClass();
    }
}
?>