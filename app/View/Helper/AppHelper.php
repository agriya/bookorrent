<?php
/* SVN FILE: $Id: app_helper.php 195 2009-03-18 06:30:14Z rajesh_04ag02 $ */
/**
 * Short description for file.
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7904 $
 * @modifiedby    $LastChangedBy: mark_story $
 * @lastmodified  $Date: 2008-12-05 22:19:43 +0530 (Fri, 05 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
App::uses('Helper', 'View');
/**
 * This is a placeholder class.
 * Create the same file in app/app_helper.php
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake
 */
class AppHelper extends Helper
{
	public $helpers = array(
        'Html',
        'Form'
    );
	
	public function assetUrl($path, $options = array(), $cdn_path = '')
    {
		$assetURL = Cms::dispatchEvent('Helper.HighPerformance.getAssetUrl', $this->_View, array(
			'options' => $options,
			'assetURL' => '',
		));
		return parent::assetUrl($path, $options, $assetURL->data['assetURL']);
    }
	function getUserAvatar($user_details, $dimension = 'medium_thumb', $is_link = true, $anonymous = '', $from = '', $isAttachment = '', $from_model = '', $span_class = true)
    {
		$width = Configure::read('thumb_size.' . $dimension . '.width');
		$height = Configure::read('thumb_size.' . $dimension . '.height');
		if (!empty($from) && $from == 'layout') {
			$width = '16';
			$height = '16';
		}
        $tooltipClass = '';
		$title = '';
        if (!$is_link) {
            $tooltipClass = '';
			if (isset( $user_details['username'])) {
				$title = $this->cText($user_details['username'], false);
			}
			if (!empty($anonymous) && ($anonymous == 'anonymous')) {
				$title = 'Anonymous';
			}
        }
		if (!empty($from_model) && $from_model == 'modal') {
			$tooltipClass = '';
		}
		if (!empty($from) && $from == 'layout') {
			$tooltipClass = '';
		}
		if (!empty($anonymous) && ($anonymous == 'anonymous')) {
			$username = __l('Anonymous');
			$user_image = $this->showImage('Anonymous', '', array(
				'dimension' => $dimension,
				'class' => $tooltipClass,
				'alt' => sprintf(__l('[Image: %s]') , $this->cText($username, false)) ,
				'title' => (!$is_link) ? $this->cText($username, false) : '' ,
			) , null, null, false);
		} elseif (!empty($user_details['user_avatar_source_id']) && $user_details['user_avatar_source_id'] == ConstUserAvatarSource::Facebook) {
            $user_image = $this->getFacebookAvatar($user_details['facebook_user_id'], $height, $width, $user_details['username'], $is_link, $from);
        } elseif (!empty($user_details['user_avatar_source_id']) &&  $user_details['user_avatar_source_id'] == ConstUserAvatarSource::Twitter) {
            $user_image = $this->image($user_details['twitter_avatar_url'], array(
                'title' => $title ,
                'width' => $width,
                'height' => $height,
				'border' => 0,
				'class' => $tooltipClass
            ));
        } else {
			if (empty($user_details['UserAvatar'])) {
                if (!empty($user_details['id'])) {
                    App::uses('User', 'Model');
                    $this->User = new User();
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $user_details['id'],
                        ) ,
                        'contain' => array(
                            'UserAvatar'
                        ) ,
                        'recursive' => 0
                    ));
                    if (!empty($user['UserAvatar']['id'])) {
                        $user_details['UserAvatar'] = $user['UserAvatar'];
                    }
                }
            }
            $user_details['username'] = !empty($user_details['username']) ? $user_details['username'] : '';
			$user_image = $this->image(getImageUrl('UserAvatar', (!empty($user_details['UserAvatar'])) ? $user_details['UserAvatar'] : '', array(
				'dimension' => $dimension
			)), array(
                'width' => $width,
                'height' => $height,
				'class' => $tooltipClass,
				'alt' => sprintf(__l('[Image: %s]') , $this->cText($user_details['username'], false)) ,
				'title' => (!$is_link) ? $this->cText($user_details['username'], false) : '' ,
			));
        }
		$before_span = $after_span = '';
		if ($from != 'facebook') {
			$span_class = '';
			if ($dimension == 'micro_thumb' && $from != 'admin') {
				$span_class = ' span1';
			}
			$pr_class = 'pr';
			if(($this->request->params['controller'] == 'blogs' &&  !empty($this->request->params['named']['from']) && $this->request->params['named']['from'] == 'activity') || (!empty($this->request->params['named']['load_type']) && $this->request->params['named']['load_type'] == 'modal')) {
				$pr_class = '';
			}
			$class = '';
			if($span_class) {
				$class = "avtar-box pull-left pr mob-clr";
			}
			$before_span = '<span class="'.$pr_class.'"><span class=" user-img pull-left pr' .$class. '">';
			$after_span = '</span></span>';
		}
        $image = (!$is_link) ? $user_image : $this->link($user_image, array(
            'controller' => 'users',
            'action' => 'view',
            $user_details['username'],
            'admin' => false
        ) , array(
            'title' => $this->cText($user_details['username'], false) ,
            'class' => $tooltipClass.' show no-pad ',
            'escape' => false
        ));
		return $before_span . $image . $after_span;
    }
	 public function getFacebookAvatar($fbuser_id, $height = 35, $width = 35, $username = '', $is_link = '', $from = '')
    {
		$tooltipClass = '';
		$title = '';
        if (!$is_link) {
            $tooltipClass = '';
			$title = $username;
        }
		if(!empty($from) && $from == 'layout') {
			$tooltipClass = '';
		}
        return $this->image("http://graph.facebook.com/{$fbuser_id}/picture?type=normal&amp;width=$width&amp;height=$height", array(
                'width' => $width,
                'height' => $height,
				'border' => 0,
				'class' => $tooltipClass,
				'title' => $title
            ));
    }
    function getCurrUserInfo($id)
    {
        App::import('Model', 'User');
        $this->User = new User();
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id,
            ) ,
            'recursive' => -1
        ));
        return $user;
    }
    function getUserLink($user_details)
    {
        if ($user_details['role_id'] == ConstUserTypes::Admin || $user_details['role_id'] == ConstUserTypes::User) {
            return $this->link($this->cText($user_details['username'], false) , array(
                'controller' => 'users',
                'action' => 'view',
                $user_details['username'],
                'admin' => false
            ) , array(
                'title' => $this->cText($user_details['username'], false) ,
                'escape' => false,
				'class' => 'graydarkc'
            ));
        }
    }
    function CheckReview($host_id, $booker_id, $item_user_id)
    {
        App::import('Model', 'Items.ItemUserFeedback');
        $this->ItemUserFeedback = new ItemUserFeedback();
        $count = $this->ItemUserFeedback->find('count', array(
            'conditions' => array(
                'ItemUserFeedback.host_user_id' => $host_id,
                'ItemUserFeedback.booker_user_id' => $booker_id,
                'ItemUserFeedback.item_user_id' => $item_user_id,
            ) ,
            'recursive' => -1
        ));
        return $count;
    }
    function getUserAvatarLink($user_details, $dimension = 'medium_thumb', $is_link = true, $classes = "")
    {
        App::import('Model', 'Setting');
        $this->Setting = new Setting();
        if ($user_details['role_id'] == ConstUserTypes::Admin || $user_details['role_id'] == ConstUserTypes::User) {
            $user_image = '';
            if (!empty($user_details['facebook_user_id'])) {
                $width = $this->Setting->find('first', array(
                    'conditions' => array(
                        'Setting.name' => 'thumb_size.' . $dimension . '.width'
                    ) ,
                    'recursive' => -1
                ));
                $height = $this->Setting->find('first', array(
                    'conditions' => array(
                        'Setting.name' => 'thumb_size.' . $dimension . '.height'
                    ) ,
                    'recursive' => -1
                ));
                $user_image = $this->getFacebookAvatar($user_details['facebook_user_id'], $height['Setting']['value'], $width['Setting']['value']);
            } else {
                //get user image
				$user_image = $this->Html->Image(getImageUrl('UserAvatar', (!empty($user_details['UserAvatar'])) ? $user_details['UserAvatar'] : '', array(
                    'dimension' => $dimension,
                    'alt' => sprintf('[Image: %s]', $user_details['username']) ,
                    'title' => $user_details['username'],
					'full_url' => true,					
                )) . '?' . time(), array('class' => $classes));
            }
            //return image to user
            return (!$is_link) ? $user_image : $this->link($user_image, array(
                'controller' => 'users',
                'action' => 'view',
                $user_details['username'],
                'admin' => false
            ) , array(
                'title' => $this->cText($user_details['username'], false) ,
                'escape' => false
            ));
        }
    }
    function transactionDescription($transaction)
    {
        $transaction['ItemUser']['booker_service_amount'] = !empty($transaction['ItemUser']['booker_service_amount']) ? $transaction['ItemUser']['booker_service_amount'] : 0;
        $transaction['ItemUser']['host_service_amount'] = !empty($transaction['ItemUser']['host_service_amount']) ? $transaction['ItemUser']['host_service_amount'] : 0;
        $transaction['ItemUser']['price'] = !empty($transaction['ItemUser']['price']) ? $transaction['ItemUser']['price'] : 0;
		$user_link = $this->link($transaction['User']['username'], array(
			'controller' => 'users',
			'action' => 'view',
			$transaction['User']['username'],
			'admin' => false
		));
		$item_link = $host_link = $booker_link = $item_amount = $order_link = '';
		if ($transaction['Transaction']['class'] == 'ItemUser') {
			$item_link = $this->link($transaction['ItemUser']['Item']['title'], array(
                'controller' => 'items',
                'action' => 'view',
                $transaction['ItemUser']['Item']['slug'],
                'admin' => false
            ));
			$host_link = $this->link($transaction['ItemUser']['Item']['User']['username'], array(
                'controller' => 'users',
                'action' => 'view',
                $transaction['ItemUser']['Item']['User']['username'],
                'admin' => false
            ));
			$booker_link = $this->link($transaction['ItemUser']['User']['username'], array(
                'controller' => 'users',
                'action' => 'view',
                $transaction['ItemUser']['User']['username'],
                'admin' => false
            ));
			$order_link = $this->link($transaction['ItemUser']['id'], array(
                'controller' => 'messages',
                'action' => 'activities',
                'order_id' => $transaction['ItemUser']['id'],
                'admin' => false
            ));
			if (in_array($transaction['TransactionType']['id'], array(ConstTransactionTypes::RefundForCanceledBooking, ConstTransactionTypes::RefundForBookingCanceledByAdmin))) {
				$item_amount = $this->siteCurrencyFormat($transaction['ItemUser']['price'] + $transaction['ItemUser']['additional_fee_amount']);
			} else {
				$item_amount = $this->siteCurrencyFormat(($transaction['ItemUser']['price'] + $transaction['ItemUser']['booker_service_amount'] + $transaction['ItemUser']['additional_fee_amount']) - $transaction['ItemUser']['coupon_discount_amont']);
			}
		} elseif ($transaction['Transaction']['class'] == 'Item') {
			$item_link = $this->link($transaction['Item']['title'], array(
                'controller' => 'items',
                'action' => 'view',
                $transaction['Item']['slug'],
                'admin' => false
            ));
			if(!empty($transaction['Item']['User'])) {
				$host_link = $this->link($transaction['Item']['User']['username'], array(
					'controller' => 'users',
					'action' => 'view',
					$transaction['Item']['User']['username'],
					'admin' => false
				));
			}
		}
        $transactionReplace = array(
            '##USER##' => $user_link,
            '##AFFILIATE_USER##' => $user_link,
            '##BOOKER##' => $booker_link,
            '##HOST##' => $host_link,
            '##ITEM##' => $item_link,
            '##ORDER_NO##' => $order_link,
            '##ITEM_AMOUNT##' => $item_amount,
		);
		if (!empty($transaction['TransactionType']['message_for_receiver']) && $transaction['Transaction']['receiver_user_id'] == $_SESSION['Auth']['User']['id']) {
			return strtr(__l($transaction['TransactionType']['message_for_receiver']), $transactionReplace);
		} elseif ($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
			return strtr(__l($transaction['TransactionType']['message_for_admin']), $transactionReplace);
		} else {
			return strtr(__l($transaction['TransactionType']['message']), $transactionReplace);
		}
	}
        function conversationDescription($conversation, $classes = '')
        {
            $conversationReplace = array(
                '##BOOKER##' => !empty($conversation['ItemUser']) ? $this->link($conversation['ItemUser']['User']['username'], array(
                    'controller' => 'users',
                    'action' => 'view',
                    $conversation['ItemUser']['User']['username'],
                    'admin' => false
                ), array('class' => $classes)) : '',
                '##HOSTER##' => !empty($conversation['Item']['User']['username']) ? $this->link($conversation['Item']['User']['username'], array(
                    'controller' => 'users',
                    'action' => 'view',
                    $conversation['Item']['User']['username'],
                    'admin' => false
                 ), array('class' => $classes)) : '',
                '##SITE_NAME##' => Configure::read('site.name') ,
                '##CREATED_DATE##' => $this->cDateTime($conversation['ItemUser']['created']) ,
                '##ACCEPTED_DATE##' => $this->cDateTime($conversation['ItemUser']['created']) ,
                '##CLEARED_DATE##' => $this->cDateTime(date('Y-m-d H:i:s', strtotime('+1 days', strtotime($conversation['ItemUser']['from'])))) ,
                '##FROM_DATE##' => $this->cDateTime($conversation['ItemUser']['from']) ,
                '##CLEARED_DAYS##' => 1
            );
            return strtr(__l($conversation['ItemUserStatus']['description']), $conversationReplace);
        }
        public function formGooglemap($properydetails = array() , $size = '320x320')
        {
            if ((!(is_array($properydetails))) || empty($properydetails)) {
                return false;
            }
            $mapurl = '//maps.google.com/maps/api/staticmap?center=';
            $mapcenter[] = str_replace(' ', '+', $properydetails['latitude']) . ',' . $properydetails['longitude'];
            $mapcenter[] = 'zoom=' . (!empty($properydetails['zoom_level']) ? $properydetails['zoom_level'] : 8);
            $mapcenter[] = 'size=' . $size;
            $mapcenter[] = 'markers=color:pink|label:M|' . $properydetails['latitude'] . ',' . $properydetails['longitude'];
            $mapcenter[] = 'sensor=false';
            return $mapurl . implode('&amp;', $mapcenter);
        }
        function distance($lat1, $lon1, $lat2, $lon2, $unit)
        {
            $theta = $lon1-$lon2;
            $dist = sin(deg2rad($lat1)) *sin(deg2rad($lat2)) +cos(deg2rad($lat1)) *cos(deg2rad($lat2)) *cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist*60*1.1515;
            $unit = strtoupper($unit);
            if ($unit == "K") {
                return ($miles*1.609344);
            } else if ($unit == "N") {
                return ($miles*0.8684);
            } else {
                return $miles;
            }
        }
        function getLanguage()
        {
            $languageList = array();
			if(isPluginEnabled('Translation')) {
				/*App::uses('Translation.Translation', 'Model');
				$modelObj = new Translation();*/
				App::import('Model', 'Translation.Translation');
            $modelObj = new Translation();
				$languages = $modelObj->find('all', array(
					'fields' => array(
						'DISTINCT(Translation.language_id)',
						'Language.name',
						'Language.iso2'
					),
					'order' => array(
						'Language.name' => 'asc'
					),
					'recursive' => 0,
				));
				if (!empty($languages)) {
					foreach($languages as $language) {
						if (!empty($language['Language']['name'])) {
							$languageList[$language['Language']['iso2']] = $language['Language']['name'];
						}
					}
				}
			}
			return $languageList;
        }
        function displayPercentageRating($total_rating, $possitive_rating)
        {
            if (!$total_rating) {
                return __l('Not Rated Yet');
            } else {
                if ($possitive_rating) {
                    return floor(($possitive_rating/$total_rating) *100) . '% ' . __l('Positive');
                } else {
                    return '100% ' . __l('Negative');
                }
            }
        }
        function siteCurrencyFormat($amount, $wrap = 'span')
        {
            $_currencies = $GLOBALS['currencies'];
            $currency_code = Configure::read('site.currency_id');
            if (!empty($_COOKIE['CakeCookie']['user_currency'])) {
                $currency_code = $_COOKIE['CakeCookie']['user_currency'];
            }
            if ($_currencies[$currency_code]['Currency']['is_prefix_display_on_left']) {
                return $_currencies[$currency_code]['Currency']['prefix'] . $this->cCurrency($amount, $wrap);
            } else {
                return $this->cCurrency($amount, $wrap) . $_currencies[$currency_code]['Currency']['prefix'];
            }
        }
        function siteWithCurrencyFormat($amount, $wrap = 'span')
        {
            $_currencies = $GLOBALS['currencies'];
            $currency_code = Configure::read('site.currency_id');
            if (!empty($_COOKIE['CakeCookie']['user_currency'])) {
                $currency_code = $_COOKIE['CakeCookie']['user_currency'];
            }
            if ($_currencies[$currency_code]['Currency']['is_prefix_display_on_left']) {
                return $this->cCurrency($amount, $wrap);
            } else {
                return $this->cCurrency($amount, $wrap);
            }
        }
        function siteDefaultCurrencyFormat($amount, $wrap = 'span')
        {
            $_currencies = $GLOBALS['currencies'];
            $currency_code = Configure::read('site.currency_id');
            if ($_currencies[$currency_code]['Currency']['is_prefix_display_on_left']) {
                return $_currencies[$currency_code]['Currency']['prefix'] . $this->cDefaultCurrency($amount, $wrap);
            } else {
                return $this->cCurrency($amount, $wrap) . $_currencies[$currency_code]['Currency']['prefix'];
            }
        }
        function cCurrency($str, $wrap = 'span', $title = false)
        {
            $_precision = 2;
            $_currencies = $GLOBALS['currencies'];
			$is_higher_formatting = Configure::read('site.price.is_higher_form');    // to display the price in number formatting (ex:10000 = 10 K)
            $currency_code = Configure::read('site.currency_id');
            if (!empty($_COOKIE['CakeCookie']['user_currency'])) {
                $currency_code = $_COOKIE['CakeCookie']['user_currency'];
				if(!empty($_currencies[Configure::read('site.currency_id')]['CurrencyConversion'])){
					$str = round($str*$_currencies[Configure::read('site.currency_id')]['CurrencyConversion'][$currency_code], 2);
				}
            }
            $changed = (($r = floatval($str)) != $str);
            $rounded = (($rt = round($r, $_precision)) != $r);
            $r = $rt;
            if ($wrap) {
                if (!$title) {
					$Numbers_Words = new Numbers_Words();
                    $title = ucwords($Numbers_Words->toCurrency($r, 'en_US', $_currencies[$currency_code]['Currency']['code']));
                }
				if($is_higher_formatting == 1) {
					$r = '<' . $wrap . ' class="c' . $changed . ' cr' . $rounded . '" title="' . $title . '">' . $this->numbers_to_higher_formatted($r) . '</' . $wrap . '>';
				} else {
					$r = '<' . $wrap . ' class="c' . $changed . ' cr' . $rounded . '" title="' . $title . '">' . number_format($r, $_precision, $_currencies[$currency_code]['Currency']['dec_point'], $_currencies[$currency_code]['Currency']['thousands_sep']) . '</' . $wrap . '>';
				}
			}
            return $r;
        }
        function cDefaultCurrency($str, $wrap = 'span', $title = false)
        {
            $_precision = 2;
            $_currencies = $GLOBALS['currencies'];
            $currency_code = Configure::read('site.currency_id');
            $changed = (($r = floatval($str)) != $str);
            $rounded = (($rt = round($r, $_precision)) != $r);
            $r = $rt;
            if ($wrap) {
                if (!$title) {
                    $title = ucwords(Numbers_Words::toCurrency($r, 'en_US', $_currencies[$currency_code]['Currency']['code']));
                }
                $r = '<' . $wrap . ' class="c' . $changed . ' cr' . $rounded . '" title="' . $title . '">' . number_format($r, $_precision, $_currencies[$currency_code]['Currency']['dec_point'], $_currencies[$currency_code]['Currency']['thousands_sep']) . '</' . $wrap . '>';
            }
            return $r;
        }
        function getCurrencies()
        {
            $currencyList = array();
			if(isset($GLOBALS['currencies'])) {
				$currencies = $GLOBALS['currencies'];
				if (!empty($currencies)) {
					foreach($currencies as $currency) {
						$currencyList[$currency['Currency']['id']] = $currency['Currency']['code'];
					}
				}
			}
            return $currencyList;
        }
        function getUserUnReadMessages($user_id = null)
        {
            App::import('Model', 'Items.Message');
            $this->Message = new Message();
            $unread_count = $this->Message->find('count', array(
                'conditions' => array(
                    'Message.is_read' => '0',
                    'Message.user_id' => $user_id,
                    'Message.is_sender' => '0',
                    'Message.message_folder_id' => ConstMessageFolder::Inbox,
                    'MessageContent.is_system_flagged' => 0
                ) ,
                'recursive' => 0
            ));
            return $unread_count;
        }
		function getPaymentGatewayIsactive($gateway_name)
		{
			App::import('Model','PaymentGateway');
			$this->PaymentGateway = new PaymentGateway();
			$payment_gateway = $this->PaymentGateway->getPaymentGatewayIsactive($gateway_name);
			return $payment_gateway;
		}
		function getSubCategoriesList($category_id, $model, $hash = null, $salt = null) {
			App::import('Model', 'Items.Category');
			$this->Category = new Category();
			App::import('Model', 'Items.Item');
			$this->Item = new Item();
			$sub_categories = $this->Category->find('all', array(
				'conditions' => array(
					'Category.is_active' => 1,
					'Category.parent_id' => $category_id,
				),
				'order' => array(
					'Category.name' => 'ASC'
				),
				'recursive' => -1
			));
			$scategories = array();
			$total_count = 0;
			foreach($sub_categories As $sub_category) {
				if($model == 'Item') {
					$conditions['Item.admin_suspend'] = 0;
					$conditions['Item.is_approved'] = 1;
					$conditions['Item.is_available'] = 1;
					$conditions['Item.is_active'] = 1;
					$conditions['Item.is_paid'] = 1;
					$conditions['Item.category_id'] = $sub_category['Category']['id'];
					if (!empty($hash) && !empty($salt)) {
						$named_array = $this->Item->getSearchKeywords($hash, $salt);
						$search_keyword['named'] = array_merge($this->request->params['named'], $named_array);
						if (!empty($search_keyword['named']['sw_latitude'])) {
							$lon1 = round($search_keyword['named']['sw_longitude'], 6);
							$lon2 = round($search_keyword['named']['ne_longitude'], 6);
							$lat1 = round($search_keyword['named']['sw_latitude'], 6);
							$lat2 = round($search_keyword['named']['ne_latitude'], 6);
							$conditions['Item.latitude BETWEEN ? AND ?'] = array(
								$lat1,
								$lat2
							);
							$conditions['Item.longitude BETWEEN ? AND ?'] = array(
								$lon1,
								$lon2
							);
						} else if (!empty($search_keyword['named']['latitude']) && !empty($search_keyword['named']['longitude'])) {
							$current_latitude = !empty($search_keyword['named']['latitude']) ? round($search_keyword['named']['latitude'], 6) : '';
							$current_longitude = !empty($search_keyword['named']['longitude']) ? round($search_keyword['named']['longitude'], 6) : '';
							//distance based search
							$dist = Configure::read('site.distance_limit') /1.60934; // 10 kms
							$lon1 = $current_longitude-$dist/abs(cos(deg2rad($current_latitude)) *69);
							$lon2 = $current_longitude+$dist/abs(cos(deg2rad($current_latitude)) *69);
							$lat1 = $current_latitude-($dist/69);
							$lat2 = $current_latitude+($dist/69);
							$conditions['Item.latitude BETWEEN ? AND ?'] = array(
								$lat1,
								$lat2
							);
							$conditions['Item.longitude BETWEEN ? AND ?'] = array(
								$lon1,
								$lon2
							);
						}
					}
					$item_count = $this->Item->find('count', array(
						'conditions' => $conditions,
						'recursive' => -1
					));
					$total_count = $total_count + $item_count;
					$scategories[$sub_category['Category']['id']] = $sub_category['Category']['name'] . ' (' . $item_count . ')';
				} else if($model == 'Request') {
					$total_count = $total_count + $sub_category['Category']['request_count'];
					$scategories[$sub_category['Category']['id']] = $sub_category['Category']['name'] . ' (' . $sub_category['Category']['request_count'] . ')';
				}
			}
			$scategories['total_count'] = $total_count;
			return $scategories;
		}
		function getSubCategories($category_id)
		{
			App::import('Model', 'Items.Category');
			$this->Category = new Category();
			$sub_categories = $this->Category->find('all', array(
				'conditions' => array(
					'Category.is_active' => 1,
					'Category.parent_id' => $category_id,
				),
				'contain' => array(
					'Attachment',
				),
				'recursive' => 0
			));
			return $sub_categories;
		}
		function getMassPayIsactive($gateway_name)
		{
			App::import('Model','PaymentGateway');
			$this->PaymentGateway = new PaymentGateway();
			$massPay = $this->PaymentGateway->getMassPayIsactive($gateway_name);
			return $massPay;
		}		
		function getPluginChildren($plugin, $depth, $image_title_icons)
		{
			if (!empty($plugin['Children'])) {
				foreach($plugin['Children'] as $key => $subPlugin) {
					if (empty($subPlugin['name'])) {
						echo $this->_View->element('plugin_head', array('key' => $key, 'image_title_icons' => $image_title_icons, 'depth' => $depth, 'cache' => array('config' => 'sec')), array('plugin' => 'Extensions'));
					} else {
						echo $this->_View->element('plugin', array('pluginData' => $subPlugin, 'depth' => $depth, 'cache' => array('config' => 'sec')), array('plugin' => 'Extensions'));
					}
					if (!empty($subPlugin['Children'])) {
						$depth++;
						$this->getPluginChildren($subPlugin, $depth, $image_title_icons);
						$depth = 0;
					}
				}
			}
		}
		function getBgImage(){
			App::import('Model', 'Attachment');
			$this->Attachment = new Attachment();
			$attachment = $this->Attachment->find('first', array(
				'conditions' => array(
					'Attachment.class' => 'Setting'
				) ,
				'fields' => array(
					'Attachment.id',
					'Attachment.dir',
					'Attachment.foreign_id',
					'Attachment.filename',
					'Attachment.width',
					'Attachment.height',
				) ,
				'recursive' => -1
			));
		   return $attachment;
		}
		public function getUserNotification($user_id = null) 
		{
			App::import('Model', 'Items.Message');
			$this->Message = new Message();
			$conditions = array();
			App::import('Model', 'User');
			$this->User = new User();
			$user = $this->User->find('first', array(
				'conditions' => array(
					'User.id' => $user_id
				) ,
				'recursive' => -1
			));
			$conditions = array(
				'Message.is_sender' => 0,
				'Message.message_folder_id' => ConstMessageFolder::Inbox,
				'Message.is_deleted' => 0,
				'Message.is_archived' => 0,
				'MessageContent.admin_suspend' => 0,
				'Message.item_user_status_id !=' => 0,
			);
			$conditions['OR']['Message.user_id'] = $user_id;
			$ItemIds = $this->Message->Item->find('list', array(
				'conditions' => array(
					'Item.user_id' => $user_id,
					'Item.admin_suspend' => 0
				) ,
				'recursive' => -1,
				'fields' => array(
					'Item.id'
				)
			));
			if(!empty($ItemIds)) {
				$conditions['OR']['Message.item_id'] = $ItemIds;
			}
			if (!empty($user['User']['activity_message_id'])) {
				$conditions['Message.id >'] = $user['User']['activity_message_id'];
			}
			$notificationCount = $this->Message->find('count', array(
				'conditions' => $conditions,
				'recursive' => 0
			));
			return $notificationCount;
		}
		function checkCustomNightAvailability($calendar_start_date, $calendar_end_date, $item_id) {
			App::import('Model', 'Items.Item');
			$this->Item = new Item();
			$day_of_the_week = array('M' => 1, 'Tu' => 2, 'W' => 3, 'Th' => 4, 'F' => 5, 'Sa' => 6, 'Su' => 7);
			$start = date('Y-m-d', strtotime($calendar_start_date));
			$end = date('Y-m-d', strtotime($calendar_end_date));
			$phpTime = strtotime($end);
			$new_end = date('Y-m-d', strtotime('next saturday', mktime(0, 0, -1, date('m', $phpTime) +1, 1, date('Y', $phpTime))));
			$contain = array();
			$recursive = -1;
			if(isPluginEnabled('Seats')){
				$contain = array('Hall');
				$recursive = 0;
			}
			$item = $this->Item->CustomPricePerNight->find('all', array(
				'conditions' => array(
					//'CustomPricePerNight.start_date <=' => $new_end,
					//'CustomPricePerNight.end_date >=' => $start,
					'CustomPricePerNight.parent_id' => 0,
					'CustomPricePerNight.item_id' => $item_id,
				) ,
				'contain' => $contain,
				'recursive' => $recursive,
			));
			$avalibilites = array();
			foreach($item As $customPricePerNight) {
				$repeat_end_date = $customPricePerNight['CustomPricePerNight']['repeat_end_date'];
				if (!empty($customPricePerNight['CustomPricePerNight']['repeat_days'])) {
					$repeat_days = array();
					$repeat_days_arr = explode(',', $customPricePerNight['CustomPricePerNight']['repeat_days']);
					foreach($repeat_days_arr as $repeat_day) {
						$repeat_days[] = $day_of_the_week[$repeat_day];
					}
					$total_days = ceil((strtotime($end) - strtotime($start)) /(60*60*24));					
					$subCustomPricePerNights = $this->Item->CustomPricePerNight->find('all', array(
						'conditions' => array(
							'CustomPricePerNight.parent_id' => $customPricePerNight['CustomPricePerNight']['id'],
							'CustomPricePerNight.start_date >=' => $start ,
							'CustomPricePerNight.start_date <=' => $new_end,
						) ,
						'contain' => array(
							'CustomPricePerType'
						) ,
					));
					$subTmpCustomPricePerNights = array();
					$j = 1;
					foreach($subCustomPricePerNights as $subCustomPricePerNight) {
						$subTmpCustomPricePerNights[$j] = $subCustomPricePerNight['CustomPricePerNight']['start_date'];
						$j++;
					}
					for ($i = 0; $i <= $total_days; $i++) {
						$day = date('Y-m-d', strtotime($start . "+" . $i . " day"));
						$day_of_day = date('N', strtotime($day));
						$repeat_chk_st_dt = strtotime($day);
						$repeat_chk_end_dt = strtotime($repeat_end_date);
						if($repeat_chk_st_dt <= $repeat_chk_end_dt) {
							if($customPricePerNight['CustomPricePerNight']['start_date'] != $day) {
								if ($key = array_search($day, $subTmpCustomPricePerNights)) {
									$avalibilites[$day][] = $subCustomPricePerNights[$key-1];
								} elseif (in_array($day_of_day, $repeat_days)) {
									if(strtotime($customPricePerNight['CustomPricePerNight']['start_date']) < strtotime($day)){									
										$diff_days = ceil((strtotime($customPricePerNight['CustomPricePerNight']['end_date']) - strtotime($customPricePerNight['CustomPricePerNight']['start_date'])) /(60*60*24));
										$end_date_check = date('Y-m-d', strtotime($day . "+" . $diff_days . " day"));
										$check_custom_price = $this->Item->CustomPricePerNight->find('first', array(
											'conditions' => array(
												'CustomPricePerNight.start_date' => $day ,
												'CustomPricePerNight.end_date' => $end_date_check ,
												'CustomPricePerNight.is_custom' => 1
											),
											'recursive' => 1
										));
										$data = array();
										if(!empty($check_custom_price)){
											$data['is_custom_available'] = 1;
											$data['CustomPricePerNight']['id'] = $check_custom_price['CustomPricePerNight']['id'];
										}
										$data['CustomPricePerNight']['parent_id'] = $customPricePerNight['CustomPricePerNight']['id'];
										$data['CustomPricePerNight']['item_id'] = $customPricePerNight['CustomPricePerNight']['item_id'];
										$data['CustomPricePerNight']['start_date'] = $day;
										$data['CustomPricePerNight']['start_time'] = $customPricePerNight['CustomPricePerNight']['start_time'];
										$data['CustomPricePerNight']['end_date'] = date('Y-m-d', strtotime($day . "+" . $diff_days . " day"));
										$data['CustomPricePerNight']['end_time'] = $customPricePerNight['CustomPricePerNight']['end_time'];
										$data['CustomPricePerNight']['is_available'] = 1;
										$data['CustomPricePerNight']['minimum_price'] = $customPricePerNight['CustomPricePerNight']['minimum_price'];
										$data['CustomPricePerNight']['is_tipped'] = $customPricePerNight['CustomPricePerNight']['is_tipped'];
										$data['CustomPricePerNight']['total_available_count'] = $customPricePerNight['CustomPricePerNight']['total_available_count'];
										$data['CustomPricePerNight']['total_booked_count'] = 0;
										$data['CustomPricePerNight']['repeat_days'] = $customPricePerNight['CustomPricePerNight']['repeat_days'];
										$data['CustomPricePerNight']['is_tipped'] = $customPricePerNight['CustomPricePerNight']['is_tipped'];
										if(isPluginEnabled('Seats') && !empty($customPricePerNight['Hall'])){
											
											$data['Hall'] = array(
												'name' => $customPricePerNight['Hall']['name']
											);
										}
										
										$avalibilites[$day][] = $data;
										
									}
								}
							} else {
								$avalibilites[$day][] = $customPricePerNight;
							}
						} else {
							break;
						}
					}
				} else {
					$avalibilites[$customPricePerNight['CustomPricePerNight']['start_date']][] = $customPricePerNight;
				}
			}
			return $avalibilites;
		}
		function getUserInvitedFriendsRegisteredCount($id) 
		{
			App::import('Model', 'Subscription');
			$this->Subscription = new Subscription();
			$count = $this->Subscription->find('count', array(
				'conditions' => array(
					'Subscription.invite_user_id' => $id,
					'Subscription.user_id !=' => '',
				) ,
				'recursive' => -1
			));
			return $count;
		}		
		public function beforeLayout($layoutFile)
		{
			if ($this instanceof HtmlHelper && isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))) {
				$url = Router::url(array('controller' => 'high_performances', 'action' => 'update_content', 'ext'=>'css'),true);
				if (Configure::read('highperformance.pids') && $this->request->params['controller'] == 'items' && in_array($this->request->params['action'], array('index', 'discover'))) {
					$pids = implode(',', Configure::read('highperformance.pids'));
					Configure::write('highperformance.pids', '');
					echo $this->Html->css($url . '?pids=' . $pids, null, array('inline' => false, 'block' => 'highperformance'));
				} elseif(Configure::read('highperformance.pids') && $this->request->params['controller'] == 'items' &&			$this->request->params['action'] == 'view') {
					echo $this->Html->css($url . '?pids=' . Configure::read('highperformance.pids') . '&from=item_view', null, array('inline' => false, 'block' => 'highperformance'));
				} elseif(Configure::read('highperformance.rids')) {
					if(is_array(Configure::read('highperformance.rids'))){
						$rids = implode(',', Configure::read('highperformance.rids'));
					}else{
						$rids = Configure::read('highperformance.rids');
					}
					Configure::write('highperformance.rids', '');
					echo $this->Html->css($url . '?rids=' . $rids, null, array('inline' => false, 'block' => 'highperformance'));
				} elseif(Configure::read('highperformance.uids')) {
					echo $this->Html->css($url . '?uids=' . Configure::read('highperformance.uids'), null, array('inline' => false, 'block' => 'highperformance'));
				} elseif(!empty($_SESSION['Auth']['User']['id']) && $_SESSION['Auth']['User']['id'] == ConstUserIds::Admin && empty($this->request->params['prefix'])) {
					echo $this->Html->css($url . '?uids=' . $_SESSION['Auth']['User']['id'], null, array('inline' => false, 'block' => 'highperformance'));
				}
				parent::beforeLayout($layoutFile);
			}
		}
		public function displayActivities($message)
		{
			$activity_messages = "";
			$items_link = $this->link($message['Item']['title'], array('controller' => 'items', 'action' => 'view', $message['Item']['slug'], 'admin' => false), array('class' => 'notification_link linkc', 'title' => $message['Item']['title']));
			$host_user_link = $this->link($message['Item']['User']['username'], array('controller' => 'users', 'action' => 'view', $message['Item']['User']['username'],'admin' => false), array('class' => 'notification_link linkc', 'title' => $message['Item']['User']['username']));
			$travel_user_link = $this->link($message['ItemUser']['User']['username'], array('controller' => 'users', 'action' => 'view', $message['ItemUser']['User']['username'],'admin' => false), array('class' => 'notification_link linkc', 'title' => $message['ItemUser']['User']['username']));
			if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages =sprintf(__l("You have booked for %s"), $items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages =sprintf(__l("%s has booked for your item %s"), $travel_user_link,$items_link);	
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages = sprintf(__l("%s item %s has been booked by %s"), $host_user_link,$items_link,$travel_user_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::Confirmed) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("Your booking confirmed for %s"),$items_link);		
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("You have been confirmed the %s's booking for %s"), $travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages = sprintf(__l("%s has confirmed %s's booking for %s"), $host_user_link,$travel_user_link,$items_link);
					}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::Rejected) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("Your booking has been rejected for %s"), $items_link);				
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("You have been rejected the %s's booking for %s"),$travel_user_link,$items_link);					
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages = sprintf(__l("%s has rejected %s's booking for %s"),$host_user_link,$travel_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::Canceled) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("You are canceled the booking for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s canceled the booking for %s"),$travel_user_link,$items_link);					
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages = sprintf(__l("%s has canceled the booking of %s's %s"),$travel_user_link,$host_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::WaitingforReview) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("You were checked out your booked %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s has checked out for your %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages =sprintf(__l("%s has checked out booked for %s's %s"),$travel_user_link,$host_user_link,$items_link);
				}
			}  else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::Completed) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("Your booking has closed for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's booking has closed for %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages = sprintf(__l("%s's booking has closed for %s's %s"),$travel_user_link,$host_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::Expired) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("Your booking has expired for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's booking has expired for %s"),$travel_user_link,$items_link);					
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages = sprintf(__l("%s's booking has expired for %s's %s"),$travel_user_link,$host_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::CanceledByAdmin) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("You booking has canceled by admin for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's booking has canceled by admin for %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages = sprintf(__l("%s's booking has canceled by admin for %s's %s"),$travel_user_link,$host_user_link,$items_link);					
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::BookerConversation) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("You have sent conversation for %s"),$items_link);					
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's has sent conversation for %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages =sprintf(__l("%s's has sent conversation to %s for %s"), $travel_user_link,$host_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::PrivateConversation) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("You have sent private conversation for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's has sent private conversation for %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages =sprintf(__l("%s's has sent private conversation to %s for %s"), $travel_user_link,$host_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::BookingRequestConversation) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("You have sent booking conversation for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's has sent booking conversation for %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages =sprintf(__l("%s's has sent booking conversation to %s for %s"), $travel_user_link,$host_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::BookingRequest) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("You have request booking for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's has request booking for %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages =sprintf(__l("%s has request booking for %s's %s"), $travel_user_link,$host_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::BookerReviewed || $message['Message']['item_user_status_id'] == ConstItemUserStatus::HostReviewed) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("Your booked has reviewed for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's booked has reviewed for %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages =sprintf(__l("%s booked has reviewed for %s's %s"), $travel_user_link,$host_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::HostReviewed) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("Your booked has reviewed for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's booked has reviewed for %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages =sprintf(__l("%s's booked has reviewed for %s's %s"), $travel_user_link,$host_user_link,$items_link);
				}
			} else if ($message['Message']['item_user_status_id'] == ConstItemUserStatus::SenderNotification) {
				if ($message['ItemUser']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("Your booked notification received for %s"),$items_link);
				} elseif($message['Item']['user_id'] == $_SESSION['Auth']['User']['id']) {
					$activity_messages = sprintf(__l("%s's booked notification received for %s"),$travel_user_link,$items_link);
				}  elseif($_SESSION['Auth']['User']['id'] == ConstUserIds::Admin) {
					$activity_messages =sprintf(__l("%s's booked notification received for %s's %s"), $travel_user_link,$host_user_link,$items_link);
				}
			}			
			return $activity_messages;
		}
		public function getBuyerFormFields($form_fields) 
		{
			$out = '';
			foreach($form_fields As $field) {
				$required = '';
				if (!empty($field['required'])) {
					$required = "required validation:{'rule1':{'rule':'notempty','message':'Required'}}";
				}
				$options = array();
				if (!empty($field['type'])) {
					$class = '';
					if (!empty($field['name'])) {
						$field['name'] = 'BuyerFormField.' .  $field['name'];
					}
					switch ($field['type']) {
						case 'fieldset':
							if ($this->openFieldset == true) {
								$out.= '</fieldset>';
							}
							$out.= '<fieldset>';
							$this->openFieldset = true;
							if (!empty($field['name'])) {
								$out.= '<legend>' . Inflector::humanize($field['label']) . '</legend>';
								$out.= $this->Form->hidden('fs_' . $field['name'], array(
									'value' => $field['name']
								));
							}
							break;

						case 'textonly':
							$out = $this->Html->para('textonly', $field['label']);
							break;

						default:
							$options['type'] = $field['type'];
							$options['info'] = $field['info'];
							if (in_array($field['type'], array(
								'multiselect',
								'select',
								'checkbox',
								'radio'
							))) {
								if (!empty($field['options']) && !is_array($field['options'])) {
									$field['options'] = str_replace(', ', ',', $field['options']);
									$field['options'] = $this->explode_escaped(',', $field['options']);
								}
								if ($field['type'] == 'checkbox') {
									if (count($field['options']) > 1) {
										$options['type'] = 'select';
										$options['multiple'] = 'checkbox';
										$options['options'] = $field['options'];
									} else {
										$options['value'] = $field['name'];
									}
								} else {
									$options['options'] = $field['options'];
								}
								if ($field['type'] == 'select' && !empty($field['multiple']) && $field['multiple'] == 'multiple') {
									$options['multiple'] = 'multiple';
								} elseif ($field['type'] == 'select') {
									$options['empty'] = __l('Please Select');
								}
							}
							if (!empty($field['depends_on']) && !empty($field['depends_value'])) {
								$options['class'] = 'dependent';
								$options['dependsOn'] = $field['depends_on'];
								$options['dependsValue'] = $field['depends_value'];
							}
							$options['info'] = str_replace("##MULTIPLE_AMOUNT##", Configure::read('equity.amount_per_share') , $options['info']);
							$options['info'] = str_replace("##SITE_CURRENCY##", Configure::read('site.currency') , $options['info']);
							$field['label'] = str_replace("##SITE_CURRENCY##", Configure::read('site.currency') , $field['label']);
							if (!empty($field['label'])) {
								$options['label'] = __l($field['label']);
								if ($field['type'] == 'radio') {
									$options['legend'] = __l($field['label']);
								}
							}
							if ($field['type'] == 'text') {
								$options['div'] = 'input text' . ' ' . $required;
							} elseif ($field['type'] == 'textarea') {
								$options['div'] = 'input textarea' . ' ' . $required;
							} elseif ($field['type'] == 'select') {
								$options['div'] = 'input select' . ' ' . $required;
							} elseif ($field['type'] == 'checkbox') {
								$options['div'] = 'input checkbox' . ' ' . $required;
							} elseif ($field['type'] == 'multiselect') {
								$options['type'] = 'select';
								$options['multiple'] = 'multiple';
								$options['div'] = 'input select multi-select' . ' ' . $required;
							} elseif ($field['type'] == 'radio') {
								$options['div'] = true;
								$options['legend'] = false;
								$out.= '<div class="input radio clearfix ' . $required . '">';
								$out.= '<label for="' . $field['name'] . '">' . __l($field['label']) . '</label>';
							}
							$out.= $this->Form->input($field['name'], $options);
							if ($field['type'] == 'radio') {
								$out.= '</div>';
							}
							break;
					}
				}
			}
			return $out;
		}
		function explode_escaped($delimiter, $string) 
		{
			$exploded = explode($delimiter, $string);
			$fixed = array();
			for ($k = 0, $l = count($exploded); $k < $l; ++$k) {
				if ($exploded[$k][strlen($exploded[$k]) -1] == '\\') {
					if ($k+1 >= $l) {
						$fixed[] = trim($exploded[$k]);
						break;
					}
					$exploded[$k][strlen($exploded[$k]) -1] = $delimiter;
					$exploded[$k].= $exploded[$k+1];
					array_splice($exploded, $k+1, 1);
					--$l;
					--$k;
				} else $fixed[] = trim($exploded[$k]);
			}
			return $fixed;
		}
		/*function getCustomPrice($from, $from_time, $to, $to_time, $item_id, $custom_price_per_night_id, $min_hours)
		{
			if ($from > $to || $from < date('Y-m-d')) {
				return 0;
			}
			App::import('Model', 'Items.Item');
			$this->Item = new Item();
			$data = $this->Item->getCustomPriceData($from, $from_time, $to, $to_time, $item_id, $custom_price_per_night_id);
			foreach($data As $key => $default_array) {
				$start_datetime = $default_array['start_date'] . ' ' . $default_array['start_time'];
				$end_datetime = $default_array['end_date'] . ' ' . $default_array['end_time'];
				$default_date_diff = $this->getDateDiff($start_datetime, $end_datetime);
				if ($default_date_diff['minuts'] == 59 && $default_date_diff['second'] == 59) {
					$default_date_diff['hour'] = $default_date_diff['hour']+1;
					if ($default_date_diff['hour'] == 24) {
						$default_date_diff['day'] = $default_date_diff['day']+1;
						$default_date_diff['hour'] = 0;
						if ($default_date_diff['day'] == 30) {
							$default_date_diff['month'] = $default_date_diff['month']+1;
							$default_date_diff['day'] = 0;
						}
					}
				}
				$months = 0;
				$weeks = 0;
				$days = 0;
				$hours = 0;
				if ($default_date_diff['year'] > 0) {
					$months = floor($default_date_diff['year'] / 30);
				}
				if ($default_date_diff['month'] > 0) {
					$months = $months + $default_date_diff['month'];
				}
				if ($default_date_diff['day'] > 0) {
					if ($default_date_diff['day'] >= 7) {
						$weeks = floor($default_date_diff['day'] / 7);
						$days = ($default_date_diff['day'] % 7);
					} else {
						$days = $default_date_diff['day'];
					}
				}
				if ($default_date_diff['hour'] > 0 && $default_date_diff['hour'] >= $min_hours) {
					$hours = $default_date_diff['hour'];
				} elseif(($default_date_diff['hour'] <= 0 || $default_date_diff['hour'] < $min_hours) && $default_date_diff['year'] <= 0 && $weeks <= 0 && $default_date_diff['day'] <= 0 && $default_date_diff['month'] <= 0){
					$hours = $min_hours;
				}
				$data[$key]['months'] = $months;
				$data[$key]['weeks'] = $weeks;
				$data[$key]['days'] = $days;
				$data[$key]['hours'] = $hours;
			}
			return $data;
		} */
		public function getDateDiff($date1, $date2)
		{
			$diff = abs(strtotime($date2) - strtotime($date1)); 
			$years   = floor($diff / (365*60*60*24)); 
			$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
			$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
			$minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
			$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 
			// return array
			$return['year'] = $years;
			$return['month'] = $months;
			$return['day'] = $days;
			$return['hour'] = $hours;
			$return['minuts'] = $minuts;
			$return['second'] = $seconds;
			return $return;
		}
		public function getDateDiffWithFormat($date1, $date2)
		{
			$diff = abs(strtotime($date2) - strtotime($date1)); 
			$years   = floor($diff / (365*60*60*24)); 
			$months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
			$days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			$hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
			$minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
			$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 
			// return array
			$return['year'] = $years;
			$return['month'] = $months;
			$return['day'] = $days;
			$return['hour'] = $hours;
			$return['minuts'] = $minuts;
			$return['second'] = $seconds;
			if ($return['minuts'] > 0 || $return['second'] > 0) {
				$return['hour'] = $return['hour']+1;
				$return['minuts'] = 0;
				$return['second'] = 0;
			}
			if ($return['hour'] == 24) {
				$return['day'] = $return['day']+1;
				$return['hour'] = 0;
			}
			if ($return['day'] == 30) {
				$return['month'] = $return['month'] + 1;
				$return['day'] = 0;
			}
			$months = 0;
			$weeks = 0;
			$days = 0;
			$hours = 0;
			if ($return['year'] > 0) {
				$months = $return['year'] * 12;
			}
			if ($return['month'] > 0) {
				$months = $months + $return['month'];
			}
			if ($return['day'] > 0) {
				if ($return['day'] >= 7) {
					$weeks = floor($return['day'] / 7);
					$days = ($return['day'] % 7);
				} else {
					$days = $return['day'];
				}
			}
			if ($return['hour'] > 0) {
				$hours = $return['hour'];
			}
			$return_str = '';
			if ($months > 0) {
				$return_str .= $months;
				$return_str .= ($months > 1) ? ' months ' : ' month ';
			}
			if($weeks > 0) {
				if($days > 0 || $hours > 0) {
					$return_str .= $weeks;
				} else {
					if(!empty($return_str)) {
						$return_str .= 'and ' . $weeks;
					} else {
						$return_str .= $weeks;
						
					}
				}
				$return_str .= ($weeks > 1) ? ' weeks ' : ' week ';
			}
			if($days > 0) {
				if($hours > 0) {
					$return_str .= $days;
				} else {
					if(!empty($return_str)) {
						$return_str .= 'and ' . $days;
					} else {
						$return_str .= $days;
					}
				}
				$return_str .= ($days > 1) ? ' days ' : ' day ';
			}
			if($hours > 0) {				
				if(!empty($return_str)) {
					$return_str .= 'and ' . $hours;					
				} else {
					$return_str .= $hours;
				}
				$return_str .= ($hours > 1) ? ' hours ' : ' hour ';
			}
			return $return_str;
		}
		function getOverAllPriceDetail($start_date, $end_date, $prices) {
			App::import('Model', 'Items.ItemUser');
			$this->ItemUser = new ItemUser();
			$date_diff = $this->getDateDiff($start_date, $end_date);
			return $this->ItemUser->price_date_calcaultion($date_diff, $prices);
		}
		//number formatting in price display
		function numbers_to_higher_formatted($numbers)
		{
			$is_higher_formatting = Configure::read('site.price.is_higher_form');    // to display the price in number formatting (ex:10000 = 10 K)
			if ($numbers <= 999 || $is_higher_formatting  == 0) {
				return '<span title="' . $numbers . '">'.$numbers.'</span>';
			}
			$symbols = array(
				'',
				'K',
				'M',
				'G',
				'T',
				'P',
				'E',
				'Z',
				'Y'
			);
			$exp = floor(log($numbers) /log(1000));
			return sprintf('%.1f ' . $symbols[$exp], ($numbers ? ($numbers/pow(1000, floor($exp))) : 0));
		}
    }