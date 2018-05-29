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
class MobileAppEventHandler extends Object implements CakeEventListener
{
	public $item_fields = array(
				'Item.id',
				'Item.created',
				'Item.modified',
				'Item.user_id',
				'Item.city_id',
				'Item.state_id',
				'Item.title',
				'Item.slug',
				'Item.description',
				'Item.street_view',
				'Item.accommodates',
				'Item.address',
				'Item.unit',
				'Item.phone',
				'Item.price_per_hour',
				'Item.price_per_day',
				'Item.price_per_week',
				'Item.price_per_month',
				'Item.additional_guest',
				'Item.latitude',
				'Item.longitude',
				'Item.zoom_level',
				'Item.item_view_count',
				'Item.item_favorite_count',
				'Item.item_feedback_count',
				'Item.positive_feedback_count',
				'Item.item_view_count',
				'Item.is_system_flagged',
				'Item.is_active',
				'Item.is_paid',
				'Item.is_featured',
				'Item.is_people_can_book_my_time',
				'Item.is_sell_ticket',
				'Item.minimum_price'
		);	
	public $image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => "",
				'title' => "",
				'type' => 'jpg',
				'full_url' => true
		);
	public $user_fields = array(
                    'User.username',
                    'User.id',
					'User.role_id',
					'User.attachment_id',
                    'User.is_facebook_register',
                    'User.facebook_user_id',
                    'User.twitter_avatar_url'
                );		
    /**
     * implementedEvents
     *
     * @return array
     */
    public function implementedEvents()
    {
        return array(
            'Controller.Item.handleApp' => array(
                'callable' => '_handleApp',
            ) ,
            'Controller.Item.item' => array(
                'callable' => 'onItemListing',
            ) ,
            'Controller.Item.view' => array(
                'callable' => 'onItemView',
            ) ,
            'Controller.ItemFeedback.Index' => array(
                'callable' => 'onItemFeedback',
            ) ,
            'Controller.ItemFeedback.Add' => array(
                'callable' => 'onItemFeedbackAdd',
            ) ,
            'Controller.ItemUserFeedback.Index' => array(
                'callable' => 'onItemUserFeedback',
            ) ,
            'Controller.ItemUserFeedback.Add' => array(
                'callable' => 'onItemFeedbackAdd',
            ) ,
			'Controller.ItemUser.item' => array(
                'callable' => 'onItemUserItem',
            ) ,			
            'Controller.ItemUser.UpdateOrder' => array(
                'callable' => 'onUpdateOrder',
            ) ,
			'Controller.User.user' => array(
                'callable' => 'onUserView',
            ) ,
			'Controller.User.validate_user' => array(
                'callable' => 'validate_user',
            ) ,
            'Controller.User.login' => array(
              'callable' => 'onUserLoginError',
             ) ,
            'Controller.UserPaymentProfile.item' => array(
                'callable' => 'onUserPaymentProfileItem',
            ) ,
            'Controller.User.ForgetPassword' => array(
                'callable' => 'onUserForgetPassword',
            ) ,
            'Controller.User.ChangePassword' => array(
                'callable' => 'onUserChangePassword',
            ) ,
            'Controller.User.Logout' => array(
                'callable' => 'onUserLogout',
            ),
            'Controller.Request.request' => array(
                'callable' => 'onRequestListing',
            ) ,			
            'Controller.UserComments.index' => array(
                'callable' => 'onUserCommendLisitng',
            ) ,			
            'Controller.UserComments.Add' => array(
                'callable' => 'onUserCommendAdd',
            ) ,
            'Controller.Items.categories' => array(
                'callable' => 'onItemCategories',
            ) ,
            'Controller.Items.subcategories' => array(
                'callable' => 'onItemSubCategories',
            ) ,
            'Controller.Items.message' => array(
                'callable' => 'onItemMessage',
            ) ,
			'Controller.Items.partition' => array(
                'callable' => 'onItemPartition',
            ) ,
            'Controller.Item.statusUpdate' => array(
                'callable' => 'onItemstatusUpdate',
            ) ,
            'Controller.Message.MessageView' => array(
                'callable' => 'onMessageView',
            ),
            'Controller.Message.Notification' => array(
                'callable' => 'onMessageNotification',
            ),
            'Controller.User.UserFollower' => array(
                'callable' => 'onUserFollower',
            ),
            'Controller.User.UserFollowerAdd' => array(
                'callable' => 'onUserFollowerAdd',
            ),
            'Controller.User.UserFollowerDelete' => array(
                'callable' => 'onUserFollowerDelete',
            ),
            'Controller.Transaction.transaction' => array(
                'callable' => 'onUserTransaction',
            ),
            'Controller.User.Withdrawals' => array(
                'callable' => 'onUserWithdrawals',
            ),
			'Controller.User.WithdrawalsAdd' => array(
                'callable' => 'onUserWithdrawalsAdd',
            ),
            'Controller.User.MoneyTransfer' => array(
                'callable' => 'onUserMoneyTransfer',
            ),
            'Controller.MoneyTransferAccount.UpdateStatus' => array(
                'callable' => 'onMoneyTransferAccountUpdateStatus',
            ),
            'Controller.User.Affiliate' => array(
                'callable' => 'onUserAffiliate',
            ),
            'Controller.Affiliate.AffiliateRequest' => array(
                'callable' => 'onAffiliateRequest',
            ),
            'Controller.Affiliate.CashWithdrawal' => array(
                'callable' => 'onAffiliateCashWithdrawal',
            ),
            'Controller.Affiliate.CashWithdrawalAdd' => array(
                'callable' => 'onAffiliateCashWithdrawalAdd',
            ),
            'Controller.Item.Collection' => array(
                'callable' => 'onItemCollection',
            ),
            'Controller.Request.RequestView' => array(
                'callable' => 'onRequestView',
            ),
            'Controller.User.ContactUs' => array(
                'callable' => 'onUserContactUs',
            ),
            'Controller.User.Notification' => array(
                'callable' => 'onUserNotification',
            ),
            'Controller.User.NotificationUpdate' => array(
                'callable' => 'onUserNotificationUpdate',
            ),
            'Controller.User.UserProfile' => array(
                'callable' => 'onUserProfile',
            ),
            'Controller.User.UserProfileUpdate' => array(
                'callable' => 'onUserProfileUpdate',
            ),
            'Controller.Item.GetFormFields' => array(
                'callable' => 'onRequestGetFormFields',
            ),
            'Controller.Request.GetFormFields' => array(
                'callable' => 'onRequestGetFormFields',
            ),
            'Controller.CategoryTypes.GetCategoryTypes' => array(
                'callable' => 'onGetCategoryTypes',
            ),
            'Controller.Request.RequestAdd' => array(
                'callable' => 'onRequestAdd',
            ),
            'Controller.Item.ItemAddSimple' => array(
                'callable' => 'onItemAdd',
            ),
            'Controller.Item.ItemAdd' => array(
                'callable' => 'onItemAdd',
            ),
            'Controller.Item.Edit' => array(
                'callable' => 'onItemAdd',
            ),			
            'Controller.Item.ItemAddAttachment' => array(
                'callable' => 'onItemAddAttachment',
            ),
            'Controller.Item.ItemPayNow' => array(
                'callable' => 'onItemPayNow',
            ), 
			'Controller.CustomPricePerTypesSeats.SeatSelection' => array(
                'callable' => 'onSeatSelection',
            ),
			'Controller.CustomPricePerTypesSeats.SeatBooking' => array(
                'callable' => 'onSeatBooking',
            ),
            'Controller.User.MoneyTransferAdd' => array(
                'callable' => 'onMoneyTransferAdd',
            ),
            'Controller.Page.StaticPage' => array(
                'callable' => 'onStaticPage',
            ),
            'Controller.Messages.ClearActivities' => array(
                'callable' => 'onMessagesClearActivities',
            ),
			'Controller.Messages.MessageStar' => array(
                'callable' => 'onMessagesMessageStar',
            ),
            'Controller.Message.activity' => array(
                'callable' => 'onMessageActivity',
            ),
            'Controller.ItemFavorities.Add' => array(
                'callable' => 'onItemFavoritiesAdd',
            ),
            'Controller.ItemFavorities.Delete' => array(
                'callable' => 'onItemFavoritiesAdd',
            ),
            'Controller.RequestFavorities.Add' => array(
                'callable' => 'onRequestFavoritiesAdd',
            ),
            'Controller.RequestFavorities.Delete' => array(
                'callable' => 'onRequestFavoritiesAdd',
            ),
            'Controller.ItemFlag.Add' => array(
                'callable' => 'onRequestFavoritiesAdd',
            ),
            'Controller.RequestFlag.Add' => array(
                'callable' => 'onResponseStatus',
            ),
            'Controller.User.Dashboard' => array(
                'callable' => 'onUserDashboard',
            ),
            'Controller.User.HostingPanel' => array(
                'callable' => 'onUserHostingPanel',
            ),
			 'Controller.User.Facepile' => array(
                'callable' => 'onUserFacepile',
            ),
			'Controller.User.FollowFriends' => array(
                'callable' => 'onUserFollowFriends',
            ),
            'Controller.City.Index' => array(
                'callable' => 'onCityIndex',
            ),
            'Controller.Country.Index' => array(
                'callable' => 'onCountryIndex',
			),
            'Controller.State.Index' => array(
                'callable' => 'onStateIndex',
			),
            'Controller.Language.Index' => array(
                'callable' => 'onLanguageIndex',
			),
            'Controller.UserEducation.Index' => array(
                'callable' => 'onUserEducationIndex',
			),
            'Controller.UserEmployment.Index' => array(
                'callable' => 'onUserEmploymentIndex',
            ),
            'Controller.UserIncomeRange.Index' => array(
                'callable' => 'onUserIncomeRangeIndex',
            ),
            'Controller.UserRelationship.Index' => array(
                'callable' => 'onUserRelationshipIndex',
            ),
            'Controller.Habit.Index' => array(
                'callable' => 'onHabitIndex',
            ),
            'Controller.Currency.Index' => array(
                'callable' => 'onCurrencyIndex',
            ),
            'Controller.ItemFlagCategory.Index' => array(
                'callable' => 'onItemFlagCategoryIndex',
            ),
            'Controller.RequestFlagCategory.Index' => array(
                'callable' => 'onRequestFlagCategoryIndex',
            ),
            'Controller.SecurityQuestion.Index' => array(
                'callable' => 'onSecurityQuestionIndex',
            ),
            'Controller.Update.UpdateActions' => array(
                'callable' => 'onUpdateActions',
            ),
            'Controller.Item.ClusterData' => array(
                'callable' => 'onItemClusterData',
            ),
            'Controller.Coupon.Index' => array(
                'callable' => 'onCouponIndex',
            ),
            'Controller.Coupon.Add' => array(
                'callable' => 'onCouponAdd',
            ),
            'Controller.Coupon.Edit' => array(
                'callable' => 'onCouponEdit',
            ),
            'Controller.BuyerFormField.Index' => array(
                'callable' => 'onBuyerFormFieldIndex',
            ),
            'Controller.BuyerFormField.Add' => array(
                'callable' => 'onBuyerFormFieldAdd',
            ),
            'Controller.BuyerFormField.Edit' => array(
                'callable' => 'onBuyerFormFieldEdit',
            ),
            'Controller.Message.PrivateBetaPost' => array(
                'callable' => 'onMessagePrivateBetaPost',
            ),
            'Controller.Message.PrivateBetaGet' => array(
                'callable' => 'onMessagePrivateBetaGet',
            ),
            'Controller.Request.Edit' => array(
                'callable' => 'onRequestEdit',
            ),
			'Controller.Request.GetEdit' => array(
                'callable' => 'onRequestGetEdit',
            ),
            'Controller.Sudopay.PayoutConnections' => array(
                'callable' => 'onSudopayPayoutConnections',
            ),
            'Controller.Sudopay.AddAccount' => array(
                'callable' => 'onSudopayAddAccount',
            ),
            'Controller.Sudopay.DeleteAccount' => array(
                'callable' => 'onSudopayDeleteAccount',
            ),
            'Controller.Item.BookIt' => array(
                'callable' => 'onItemBookIt',
            ),
            'Controller.ItemUser.Add' => array(
                'callable' => 'onItemUserAdd',
            ),
            'Controller.Item.GetItemTime' => array(
                'callable' => 'onItemGetItemTime',
            ),
            'Controller.Item.calendar' => array(
                'callable' => 'onItemCalendar',
            ),
            'Controller.Item.GetItemPrices' => array(
                'callable' => 'onItemGetItemPrices',
            ),
			'Controller.Item.GetItemInfo' => array(
                'callable' => 'onItemGetItemInfo',
            ),
            'Controller.Item.Order' => array(
                'callable' => 'onItemOrder',
            ),
            'Controller.Message.Compose' => array(
                'callable' => 'onMessageCompose',
            ),
            'Controller.User.Register' => array(
                'callable' => 'onUserRegister',
            ),
            'Controller.Wallet.AddToWallet' => array(
                'callable' => 'onWalletAddToWallet',
            ),
            'Controller.Payment.GetGateway' => array(
                'callable' => 'onPaymentGetGateway',
            ),
            'Controller.Payment.get_sudopay_gateways' => array(
                'callable' => 'onSudopayGateways',
            ),
            'Controller.Record.Delete' => array(
                'callable' => 'onDelete',
            ),
			'Controller.ItemUser.Delete' => array(
                'callable' => 'onItemUserDelete',
            ),
            'Controller.ItemUser.View' => array(
                'callable' => 'onItemUserView',
            ),
			'Controller.ItemUser.CheckAvail' => array(
                'callable' => 'onItemUserCheckAvail',
            ),
            'Controller.ItemUser.UpdateItem' => array(
                'callable' => 'onResponseStatus',
            ),
            'Controller.ItemUser.TrackOrder' => array(
                'callable' => 'onItemUserTrackOrder',
            ),
            'Controller.ItemUser.Manage' => array(
                'callable' => 'onItemUserManage',
            ),
            'Controller.Item.unfollow' => array(
                'callable' => 'onItemUnfollow',
            ),
			'Controller.Hall.Listing' => array(
                'callable' => 'onHallListing',
            ),
			'Controller.Hall.Delete' => array(
                'callable' => 'onHallDelete',
            ),
			'Controller.Hall.HallAdd' => array(
                'callable' => 'onHallAdd',
            ),
			'Controller.Hall.GetEdit' => array(
                'callable' => 'onHallGetEdit',
            ),
			'Controller.Hall.Edit' => array(
                'callable' => 'onHallEdit',
            ),
            'Controller.User.socialLogin' => array(
               'callable' => 'validate_user',
            ),
            'Controller.Paymentmembership_pay_now' => array(
                'callable' => 'onPaymentMembershipPayNow',
            ),
        );
    }
    public function onUserLoginError($event)
    {
        $obj = $event->subject();
        $obj->view = 'Json';
        $obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
    }
    public function onPaymentMembershipPayNow($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
	public function onResponseStatus($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
    public function onItemstatusUpdate($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
	public function onItemUserManage($event)
	{
        $obj = $event->subject();
		$order = $obj->viewVars['order'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array('order' => $order) : $obj->viewVars['iphone_response']);
	}
    public function onItemUnfollow($event)
	{
        $obj = $event->subject();
		$data = $event->data['data'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $data : $obj->viewVars['iphone_response']);
    }
	public function onItemUserTrackOrder($event)
	{
        $obj = $event->subject();
		$itemOrder = $obj->viewVars['itemOrder'];
		$relatedMessages = $obj->viewVars['relatedMessages'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array('itemOrder' => $itemOrder, 'relatedMessages' => $relatedMessages) : $obj->viewVars['iphone_response']);
	}	
	public function onItemUserView($event)
	{
        $obj = $event->subject();
		$itemUser = $obj->viewVars['itemUser'];
		if(!empty($itemUser)){
			if(!empty($itemUser['Item']['Attachment'])){
				$image_options['alt'] = $image_options['title'] = $itemUser['Item']['title'];
				$itemUser['Item']['iphone_small_thumb'] = getImageUrl('Item', $itemUser['Item']['Attachment'][0], $image_options);	
				unset($itemUser['Item']['Attachment']);			
			}
			foreach($itemUser['Item']['User'] as $field => $value){
				if(!in_array("User.".$field, $this->user_fields)){
					unset($itemUser['Item']['User'][$field]);
				}				
			}
			foreach($itemUser['User'] as $field => $value){
				if(!in_array("User.".$field, $this->user_fields)){
					unset($itemUser['User'][$field]);
				}				
			}
		}
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $itemUser : $obj->viewVars['iphone_response']);
	}	
	public function onItemUserCheckAvail($event) {
		$obj = $event->subject();
        $available_bookings = $obj->viewVars['available_bookings'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $available_bookings : $obj->viewVars['iphone_response']);
	}
	public function onDelete($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}	
	public function onItemUserDelete($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
	public function onSeatSelection($event)
	{
        $obj = $event->subject();
		$itemUser = $obj->viewVars['itemUser'];
		$reserved_titcket = $obj->viewVars['reserved_titcket'];
		$partition = $obj->viewVars['partition'];
		$selected_seats = $obj->viewVars['selected_seats'];
		$seat_map = $obj->viewVars['seat_map'];
		$available_arr = $obj->viewVars['available_arr'];
		$unavailable_arr = $obj->viewVars['unavailable_arr'];
		$booked_arr = $obj->viewVars['booked_arr'];
		$noseat_arr = $obj->viewVars['noseat_arr'];
		$row_name = $obj->viewVars['row_name'];
		$selected_arr = $obj->viewVars['selected_arr'];
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $itemUser : $obj->viewVars['iphone_response'],
				'reserved_titcket' => (!empty($reserved_titcket)) ? $reserved_titcket : array(),
				'partition' => (!empty($partition)) ? $partition : array(),
				'selected_seats' => (!empty($selected_seats)) ? $selected_seats : array(),
				'seat_map' => (!empty($seat_map)) ? $seat_map : array(),
				'available_arr' => (!empty($available_arr)) ? $available_arr : array(),
				'unavailable_arr' => (!empty($unavailable_arr)) ? $unavailable_arr : array(),
				'booked_arr' => (!empty($booked_arr)) ? $booked_arr : array(),
				'noseat_arr' => (!empty($noseat_arr)) ? $noseat_arr : array(),
				'row_name' => (!empty($row_name)) ? $row_name : array(),
				'selected_arr' => (!empty($selected_arr)) ? $selected_arr : array(),
		);
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}
	public function onSeatBooking($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
	public function onMoneyTransferAccountUpdateStatus($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}	
	public function onPaymentGetGateway($event)
	{
        $obj = $event->subject();
		$model = $obj->viewVars['model'];
		$foreign_id = $obj->viewVars['foreign_id'];
		$transaction_type = $obj->viewVars['transaction_type'];
		$gateway_types = $obj->viewVars['gateway_types'];
		$response = $obj->viewVars['response'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array('model' => $model, 'foreign_id' => $foreign_id, 'transaction_type' => $transaction_type, 'gateway_types' => $gateway_types, 'response' => $response) : $obj->viewVars['iphone_response']);
	}
    public function onSudopayGateways($event)
    {
        $obj = $event->subject();
        $response = array();
        $response = $obj->request->data;
        $obj->view = 'Json';
        $obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
    }	
	public function onWalletAddToWallet($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
    public function onItemPayNow($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
	public function onUserRegister($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}	
	public function onMessageCompose($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}	
	public function onItemOrder($event)
	{
        $obj = $event->subject();
        $itemDetail = array();
        if($event->data['itemDetail']){
		$itemDetail = $event->data['itemDetail'];
        }
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $itemDetail : $obj->viewVars['iphone_response']);
	}	
	public function onItemGetItemPrices($event)
	{
        $obj = $event->subject();
		$custom_price_per_night = $obj->viewVars['custom_price_per_night'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $custom_price_per_night : $obj->viewVars['iphone_response']);
	}
	public function onItemGetItemInfo($event)
	{
        $obj = $event->subject();
		$item = $obj->viewVars['item'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $item : $obj->viewVars['iphone_response']);
	}	
	public function onItemCalendar($event)
	{
        $obj = $event->subject();
		$id = $obj->viewVars['id'];
		$type = $obj->viewVars['type'];
		$guest_lists = $month = $year = array();
		if($type == 'guest'){
			$month = $obj->viewVars['month'];
			$year = $obj->viewVars['year'];
		}else if($type == 'guest_list'){
			$guest_lists = $obj->viewVars['guest_lists'];
		}
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array('id' => $id, 'type' => $type, 'guest_lists' => $guest_lists,'month' => $month,'year' => $year,'guest_lists' => $guest_lists) : $obj->viewVars['iphone_response']);
	}	
	public function onItemGetItemTime($event)
	{
        $obj = $event->subject();
		$custom_price_per_nights = $obj->viewVars['custom_price_per_nights'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $custom_price_per_nights : $obj->viewVars['iphone_response']);
	}	
	public function onItemUserAdd($event)
	{
        $obj = $event->subject();
		$message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onItemBookIt($event)
	{
        $obj = $event->subject();
		$itemUser = $obj->viewVars['ItemUser'];
		$chart_data = $obj->viewVars['chart_data'];
		$item = $obj->viewVars['item'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array('itemUser' => $itemUser, 'item' => $item) : $obj->viewVars['iphone_response']);
	}	
	public function onSudopayDeleteAccount($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}	
	public function onSudopayAddAccount($event)
	{
        $obj = $event->subject();
		$account = $event->data['account'];
        $res = array('message' => __l('Invalid Request'), 'error' => 1);
        if(!empty($account['gateways']['gateway_callback_url'])){
           $res = array('message' => str_replace(" ", "+", $account['gateways']['gateway_callback_url']), 'error' => 0);
        }
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $res : $obj->viewVars['iphone_response']);
	}	
	public function onSudopayPayoutConnections($event)
	{
        $obj = $event->subject();
		$user = $obj->viewVars['user'];
		$connected_gateways = $obj->viewVars['connected_gateways'];
		$supported_gateways = $obj->viewVars['supported_gateways'];
		$obj->view = 'Json';
		if(!empty($user)){
			foreach($user['User'] as $field => $value){
				if(!in_array("User.".$field, $this->user_fields)){
					unset($user['User'][$field]);
				}				
			}
		}
        if (!empty($supported_gateways)){
            for ($i = 0; $i < count($supported_gateways); $i++) {
             $gateway_details = unserialize($supported_gateways[$i]['SudopayPaymentGateway']['sudopay_gateway_details']);
             $supported_gateways[$i]['SudopayPaymentGateway']['thumb_url'] = $gateway_details['thumb_url'];
             $supported_gateways[$i]['SudopayPaymentGateway']['connect_instruction'] = strip_tags($gateway_details['connect_instruction']);
             if(in_array($supported_gateways[$i]['SudopayPaymentGateway']['sudopay_gateway_id'], $connected_gateways)) {
                 $supported_gateways[$i]['SudopayPaymentGateway']['is_connected'] = 1;
             } else {
                 $supported_gateways[$i]['SudopayPaymentGateway']['is_connected'] = 0;
             }
           }
        }
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array('user' => $user['User'],'connected_gateways' => $connected_gateways, 'supported_gateways' => $supported_gateways) : $obj->viewVars['iphone_response']);
	}	
	public function onItemFeedbackAdd($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}	
	public function onRequestEdit($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
	public function onRequestGetEdit($event)
	{
	    $obj = $event->subject();
		$request = $obj->viewVars['request'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $request : $obj->viewVars['iphone_response']);
	}	
	public function onMessagePrivateBetaGet($event)
	{
        $obj = $event->subject();
		$message = $obj->viewVars['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onMessagePrivateBetaPost($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}	
	public function onMessageActivity($event)
	{
        $obj = $event->subject();
        $orders = $obj->viewVars['orders'];
		if(!empty($orders) && !empty($orders['Item']['Attachment'])){
			$image_options['alt'] = $image_options['title'] = $orders['Item']['title'];
			$orders['Item']['iphone_small_thumb'] = getImageUrl('Item', $orders['Item']['Attachment'][0], $image_options);	
			unset($orders['Item']['Attachment']);			
		}
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $orders : $obj->viewVars['iphone_response']);
	}	
	public function onBuyerFormFieldIndex($event)
	{
        $obj = $event->subject();
        $item = $obj->viewVars['item'];
		$buyer_form_fields = $obj->viewVars['buyer_form_fields'];
		if(!empty($item)){
			foreach($item['Item'] as $field => $field_value){						
				if(!in_array("Item.".$field, $this->item_fields)){
					unset($item['Item'][$field]);
				}
			}	
		}
		if(!empty($buyer_form_fields)){
			foreach($buyer_form_fields as $field => $field_value){	
				unset($buyer_form_fields[$field]['Item']);
			}	
		}			
		$response = array('item' => $item['Item'],'buyer_form_fields' => $buyer_form_fields);
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}	
	public function onBuyerFormFieldAdd($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}	
	public function onBuyerFormFieldEdit($event)
	{
        $obj = $event->subject();
		$buyer_form_field = (empty($obj->viewVars['buyer_form_field']))? array(): $obj->viewVars['buyer_form_field'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $buyer_form_field : $obj->viewVars['iphone_response']);
	}	
	public function onCouponIndex($event)
	{
        $obj = $event->subject();
		$coupons = $obj->viewVars['coupons'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $coupons : $obj->viewVars['iphone_response']);
	}	
	public function onCouponAdd($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}	
	public function onCouponEdit($event)
	{
        $obj = $event->subject();
		$coupon = (empty($obj->viewVars['coupon']))? array(): $obj->viewVars['coupon'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $coupon : $obj->viewVars['iphone_response']);
	}	
	public function onItemClusterData($event)
	{
        $obj = $event->subject();
        $data = $event->data['data'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $data : $obj->viewVars['iphone_response']);
	}	
	public function onUpdateActions($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onSecurityQuestionIndex($event)
	{
        $obj = $event->subject();
		$security_question = $obj->SecurityQuestion->find('all', array(
			'conditions' => $obj->paginate['conditions'],
			'order' => array(
				'SecurityQuestion.name' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array('question_list' => $security_question) : $obj->viewVars['iphone_response']);
	}	
	public function onRequestFlagCategoryIndex($event)
	{
        $obj = $event->subject();
		$request_flag_categories = $obj->RequestFlagCategory->find('list', array(
			'conditions' => $obj->paginate['conditions'],
			'fields' => array(
					'RequestFlagCategory.id',
					'RequestFlagCategory.name'
				),
			'order' => array(
				'RequestFlagCategory.name' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $request_flag_categories : $obj->viewVars['iphone_response']);
	}	
	public function onItemFlagCategoryIndex($event)
	{
        $obj = $event->subject();
		$item_flag_categories = $obj->ItemFlagCategory->find('list', array(
			'conditions' => $obj->paginate['conditions'],
			'fields' => array(
					'ItemFlagCategory.id',
					'ItemFlagCategory.name'
				),
			'order' => array(
				'ItemFlagCategory.name' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $item_flag_categories : $obj->viewVars['iphone_response']);
	}	
	public function onCurrencyIndex($event)
	{
        $obj = $event->subject();
		$currency = $obj->Currency->find('all', array(
			'conditions' => $obj->paginate['conditions'],
			'order' => array(
				'Currency.name' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $currency : $obj->viewVars['iphone_response']);
	}	
	public function onHabitIndex($event)
	{
        $obj = $event->subject();
		$habit = $obj->Habit->find('list', array(
			'conditions' => $obj->paginate['conditions'],
			'fields' => array(
					'Habit.id',
					'Habit.name'
				),
			'order' => array(
				'Habit.name' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $habit : $obj->viewVars['iphone_response']);
	}	
	public function onUserRelationshipIndex($event)
	{
        $obj = $event->subject();
		$userRelationship = $obj->UserRelationship->find('list', array(
			'conditions' => $obj->paginate['conditions'],
			'fields' => array(
					'UserRelationship.id',
					'UserRelationship.relationship'
				),
			'order' => array(
				'UserRelationship.relationship' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $userRelationship : $obj->viewVars['iphone_response']);
	}	
	public function onUserIncomeRangeIndex($event)
	{
        $obj = $event->subject();
		$userIncomeRange = $obj->UserIncomeRange->find('list', array(
			'conditions' => $obj->paginate['conditions'],
			'fields' => array(
					'UserIncomeRange.id',
					'UserIncomeRange.income'
				),			
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $userIncomeRange : $obj->viewVars['iphone_response']);
	}	
	public function onUserEmploymentIndex($event)
	{
        $obj = $event->subject();
		$userEmployment = $obj->UserEmployment->find('list', array(
			'conditions' => $obj->paginate['conditions'],
			'fields' => array(
					'UserEmployment.id',
					'UserEmployment.employment'
				),
			'order' => array(
				'UserEmployment.employment' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $userEmployment : $obj->viewVars['iphone_response']);
	}	
	public function onUserEducationIndex($event)
	{
        $obj = $event->subject();
		$userEducations = $obj->UserEducation->find('list', array(
			'conditions' => $obj->paginate['conditions'],
			'fields' => array(
					'UserEducation.id',
					'UserEducation.education'
				),
			'order' => array(
				'UserEducation.education' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $userEducations : $obj->viewVars['iphone_response']);
	}	
	public function onLanguageIndex($event)
	{
        $obj = $event->subject();
		$states = $obj->Language->find('all', array(
			'conditions' => $obj->paginate['conditions'],
			'order' => array(
				'Language.name' => 'ASC',
			) ,
			'recursive' => -1
		));
        $response = array(
                          'data' => (empty($obj->viewVars['iphone_response'])) ? $states : $obj->viewVars['iphone_response'],
                          );

		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}	
	public function onStateIndex($event)
	{
        $obj = $event->subject();
		$states = $obj->State->find('all', array(
			'conditions' => $obj->paginate['conditions'],
			'order' => array(
				'State.name' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $states : $obj->viewVars['iphone_response']);
	}	
	public function onCountryIndex($event)
	{
        $obj = $event->subject();
		$countries = $obj->Country->find('all', array(
			'order' => array(
				'Country.name' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $countries : $obj->viewVars['iphone_response']);
	}	
	public function onCityIndex($event)
	{
        $obj = $event->subject();
		$cities = $obj->City->find('all', array(
			'conditions' => $obj->paginate['conditions'],
			'order' => array(
				'City.name' => 'ASC',
			) ,
			'recursive' => -1
		));
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $cities : $obj->viewVars['iphone_response']);
	}	
	public function onUserDashboard($event)
	{
        $obj = $event->subject();
		$result = array();
        $result['host_moreActions'] = $obj->viewVars['host_moreActions'];
		$result['host_all_count'] = $obj->viewVars['host_all_count'];
		$result['moreActions'] = $obj->viewVars['moreActions'];
		$result['all_count'] = $obj->viewVars['all_count'];
		$result['total_purchased'] = $obj->viewVars['total_purchased'];
		$result['user'] = $obj->viewVars['user'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $result : $obj->viewVars['iphone_response']);
	}	
	public function onUserHostingPanel($event)
	{
        $obj = $event->subject();
		$result = array();
		$result['periods'] = $obj->viewVars['periods'];
		$result['models'] = $obj->viewVars['models'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $result : $obj->viewVars['iphone_response']);
	}
	public function onUserFacepile($event)
	{
        $obj = $event->subject();
		$result = array();
		$result['users'] = $obj->viewVars['users'];
		$result['totalUserCount'] = $obj->viewVars['totalUserCount'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $result : $obj->viewVars['iphone_response']);
	}
	public function onUserFollowFriends($event)
	{
        $obj = $event->subject();
		if(!empty($obj->viewVars['followFriends'])) {
			$followFriends = $obj->viewVars['followFriends'];	
		} else {
			$followFriends = $event->data['message'];
		}
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $followFriends : $obj->viewVars['iphone_response']);
	}
	public function onRequestFavoritiesAdd($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onItemFavoritiesAdd($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	public function onHallListing($event)
	{
		$obj = $event->subject();
        $halls = $obj->paginate();	
		$total_counts = $obj->Hall->find('count', array(
			'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		$user_fields = array(
			'User.username',
			'User.id',
			'User.role_id'
		);	
		foreach($halls as $key => $value){
			foreach($halls[$key]['User'] as $field => $field_value){
				if(!in_array("User.".$field, $user_fields)){
					unset($halls[$key]['User'][$field]);
				}
			}
		}				
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		$response = array(
			'data' => (empty($obj->viewVars['iphone_response'])) ? $halls : $obj->viewVars['iphone_response'],
			'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);	
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}
	public function onHallDelete($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
	public function onHallAdd($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
	public function onHallEdit($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array() : $obj->viewVars['iphone_response']);
	}
	public function onHallGetEdit($event)
	{
	    $obj = $event->subject();
		$hall = $obj->viewVars['hall'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $hall : $obj->viewVars['iphone_response']);
	}
	public function onMessagesClearActivities($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	public function onMessagesMessageStar($event)
	{
        $obj = $event->subject();
		$obj->view = 'Json';
		$obj->set('json', $obj->viewVars['iphone_response']);
	}	
	public function onUserCommendAdd($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onMessageView($event)
	{
        $obj = $event->subject();
        $message = $obj->viewVars['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onStaticPage($event)
	{
        $obj = $event->subject();
        $page = $obj->viewVars['page'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $page : $obj->viewVars['iphone_response']);
	}	
	public function onMoneyTransferAdd($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onItemAddAttachment($event)
	{
        $obj = $event->subject();
		$attachment = $obj->viewVars['attachment'];
		$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => "",
				'title' => "",
				'type' => 'jpg',
				'full_url' => true
		);
		$iphone_big_thumb = getImageUrl('Item', $attachment['Attachment'], $image_options);
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? array('iphone_small_thumb' => $iphone_big_thumb) : $obj->viewVars['iphone_response']);
	}	
	public function onItemAdd($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onRequestAdd($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onGetCategoryTypes($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onRequestGetFormFields($event)
	{
        $obj = $event->subject();
		$response = array();
        $response['Form'] = $event->data['Form'];
		$response['category'] = $event->data['category'];
		$response['FormFieldSteps'] = $event->data['FormFieldSteps'];
		$response['total_form_field_steps'] = $event->data['total_form_field_steps'];
		$response['FormFieldSteps'] = $event->data['FormFieldSteps'];
		$response['total_form_field_steps'] = $event->data['total_form_field_steps'];
		$response['categoryFormFields'] = $event->data['categoryFormFields'];
		$response['countries'] = $event->data['countries'];		
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}	
	public function onUserLogout($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	
	public function onUserChangePassword($event)
	{
		$controller->Security->enabled = false;
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	public function onUserForgetPassword($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	public function onUserContactUs($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onAffiliateRequest($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
	public function onUserNotification($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	public function onUserNotificationUpdate($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	public function onUserProfile($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	public function onUserProfileUpdate($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	public function _handleApp($event)
	{
		$controller = $event->subject();
        $controller->Security->enabled = false;
        $controller->loadModel('User');
        if ((!empty($_POST['data']) || (!empty($_GET['data']))) && in_array($controller->request->params['action'], array(
            'validate_user'
        ))) {
            if (!empty($_GET['data'])) {
                $_POST['data'] = $_GET['data'];
            }
            if (!empty($_POST['data'])) {
                foreach($_POST['data'] as $controller => $values) {
                    $controller->request->data[Inflector::camelize(Inflector::singularize($controller)) ] = $values;
                }
            }
        }
		if (!empty($_GET['username']) && $controller->request->params['action'] != 'validate_user') {
			$controller->request->data['User'][Configure::read('user.using_to_login') ] = trim($_GET['username']);
			$user = $controller->User->find('first', array(
				'conditions' => array(
					'User.mobile_app_hash' => $_GET['passwd']
				) ,
				'fields' => array(
					'User.password',
				) ,
				'recursive' => -1
			));
			if (empty($user)) {
				$controller->set('iphone_response', array(
					'error' => 1,
                    'checkauth' => YES,
					'message' => sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login'))
				));
            } else {
				//need to fix
				//$controller->request->data['User']['passwd'] = 'agriya';
				//$controller->request->data['User']['password'] = crypt($controller->request->data['User']['passwd'], $user['User']['password']);
				$controller->request->data['User']['password'] = $user['User']['password'];
				if (!$controller->Auth->login()) {				
					$controller->set('iphone_response', array(
						'error' => 1,
                        'checkauth' => YES,
						'message' => sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login'))
					));
				}
                // To do list for lat and lng updation
				/*if ($controller->Auth->user('id') && !empty($_GET['latitude']) && !empty($_GET['longtitude'])) {
					$controller->update_iphone_user($_GET['latitude'], $_GET['longtitude'], $controller->Auth->user('id'));
				}*/
			}
		}
    }
    function update_iphone_user($latitude, $longitude, $user_id)
    {
        App::uses('User', 'Model');
		$obj->User = new User();
        $obj->User->updateAll(array(
            'User.iphone_latitude' => $latitude,
            'User.iphone_longitude' => $longitude,
            'User.iphone_last_access' => "'" . date("Y-m-d H:i:s") . "'"
        ) , array(
            'User.id' => $user_id
        ));
    }
    public function onItemListing($event)
    {
        $obj = $event->subject();
        $page = $event->data['page'];
        $languages = $event->data['languages'];
        $items = $obj->paginate();
		$total_items = $obj->Item->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));
		if(isset($obj->viewVars['collections'])){
			$collections = $obj->viewVars['collections'];
		}
		for ($i = 0; $i < count($items); $i++) {
			$items[$i]['TmpUser'] = $items[$i]['User'];
			unset($items[$i]['User']);
			$items[$i]['User'] = array(
				'id' => $items[$i]['TmpUser']['id'],
			);
			unset($items[$i]['TmpUser']);
			//views
			$items[$i]['Item']['views'] = $items[$i]['Item']['item_view_count'];
			$this->saveiPhoneAppThumb($items[$i]['Attachment']);
			$image_options = array(
				'dimension' => 'iphone_big_thumb',
				'class' => '',
				'alt' => $items[$i]['Item']['title'],
				'title' => $items[$i]['Item']['title'],
				'type' => 'jpg',
				'full_url' => true
			);
			$iphone_big_thumb = getImageUrl('Item', $items[$i]['Attachment'][0], $image_options);
			$items[$i]['Item']['iphone_big_thumb'] = $iphone_big_thumb;
			$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => $items[$i]['Item']['title'],
				'title' => $items[$i]['Item']['title'],
				'type' => 'jpg',
				'full_url' => true
			);
			$iphone_small_thumb = getImageUrl('Item', $items[$i]['Attachment'][0], $image_options);
			$items[$i]['Item']['iphone_small_thumb'] = $iphone_small_thumb;
			unset($items[$i]['Attachment']);
            
            //Favourite
            if(!empty($items[$i]['ItemFavorite']))
            {
                $follw_status = 0;
            }
            else if(empty($items[$i]['ItemFavorite']))
            {
                $follw_status = 1;
            }
            if($items[$i]['Item']['user_id']==$obj->Auth->user('id'))
            {
                $own_project=1;
            }
            else
            {
                $own_project=0;
            }
            if($obj->Auth->user('id'))
            {
                $follow_session_status=1;
            } 
            else
            {
                $follow_session_status=0;
            }
            $items[$i]['Item']['item_favorite_status'] = $follw_status;
            $items[$i]['Item']['own_item_flag'] = $own_project;
            $items[$i]['Item']['item_favorite_session'] = $follow_session_status;
		}
		$no_of_pages = ceil($total_items/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		$response = array(
				'collection' => (!empty($collections)) ? $collections : array(),
				'data' => (empty($obj->viewVars['iphone_response'])) ? $items : $obj->viewVars['iphone_response'],
				'_languages' => (!empty($languages)) ? $languages : array(),
                '_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
    }
    public function onItemView($event)
    {
        $obj = $event->subject();
        $item = $obj->viewVars['item'];
        $this->saveiPhoneAppThumb($item['Attachment']);
		$image_options = array(
			'dimension' => 'iphone_big_thumb',
			'class' => '',
			'alt' => $item['Item']['title'],
			'title' => $item['Item']['title'],
			'type' => 'jpg',
			'full_url' => true
		);
		$iphone_big_thumb = getImageUrl('Item', $item['Attachment'][0], $image_options);
		$item['Item']['iphone_big_thumb'] = $iphone_big_thumb;
		$image_options = array(
			'dimension' => 'iphone_small_thumb',
			'class' => '',
			'alt' => $item['Item']['title'],
			'title' => $item['Item']['title'],
			'type' => 'jpg',
			'full_url' => true
		);
		$iphone_small_thumb = getImageUrl('Item', $item['Attachment'][0], $image_options);
		$item['Item']['iphone_small_thumb'] = $iphone_small_thumb;
		unset($item['Attachment']);
		$tmp_user = array();
		$tmp_user['id'] = $item['User']['id'];
		$tmp_user['role_id'] = $item['User']['role_id'];
		$tmp_user['username'] = $item['User']['username'];
		$tmp_user['is_facebook_friends_fetched'] = $item['User']['is_facebook_friends_fetched'];
		$tmp_user['twitter_avatar_url'] = $item['User']['twitter_avatar_url'];
		$tmp_user['user_avatar_source_id'] = $item['User']['user_avatar_source_id'];
		$tmp_user['attachment_id'] = $item['User']['attachment_id'];
		$tmp_user['facebook_user_id'] = $item['User']['facebook_user_id'];
		$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => $item['User']['username'],
				'title' => $item['User']['username'],
				'type' => 'jpg',
				'full_url' => true
			);
		$tmp_user['iphone_small_thumb'] = getImageUrl('UserAvatar', array('id' => $item['User']['attachment_id']), $image_options);		
		$item['User'] = $tmp_user;		
		$like_status = "";
		$like_link = "";
		if($obj->Auth->user('id') && $item['Item']['user_id'] != $obj->Auth->user('id')){
			$like_status = "like";
			$like_link = Router::url(array('controller' => 'item_favorites', 'action' => 'add', $item['Item']['slug'], 'type' => 'view') , true);	
			if(!empty($item['ItemFavorite'])){
				foreach($item['ItemFavorite'] as $favorite):
					if($obj->Auth->user('id') == $favorite['user_id'] ):
						$like_status = "unlike";
						$like_link = Router::url( array('controller' => 'item_favorites', 'action'=>'delete', $item['Item']['slug'], 'type' => 'view') , true);						
					endif;
				endforeach;
			}
		}
		unset($item['ItemFavorite']);
		
		
		$item['ItemFavorite']['like_status'] = $like_status;
		$item['ItemFavorite']['like_link'] = $like_link;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $item : $obj->viewVars['iphone_response']);
    }
	public function onItemPartition($event)
	{
		$obj = $event->subject();
        $partitions = $obj->viewVars['partitions'];	
		$item = $obj->viewVars['item'];
		$total_counts = $obj->Item->CustomPricePerType->find('count', array(
			'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		$response = array(
			'item' => (empty($obj->viewVars['iphone_response'])) ? $item : $obj->viewVars['iphone_response'],
			'data' => (empty($obj->viewVars['iphone_response'])) ? $partitions : $obj->viewVars['iphone_response'],
			'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);	
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}
	public function onItemUserItem($event)
    {
        $obj = $event->subject();
        $response = $event->data['data'];
        $itemUsers = $obj->paginate();
		$total_items = $obj->ItemUser->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));
		for ($i = 0; $i < count($itemUsers ); $i++) {
			$this->saveiPhoneAppThumb($itemUsers[$i]['Item']['Attachment']);
			$image_options = array(
				'dimension' => 'iphone_big_thumb',
				'class' => '',
				'alt' => $itemUsers[$i]['Item']['title'],
				'title' => $itemUsers[$i]['Item']['title'],
				'type' => 'jpg',
				'full_url' => true
			);
			$iphone_big_thumb = getImageUrl('Item', $itemUsers[$i]['Item']['Attachment'][0], $image_options);
			$itemUsers[$i]['Item']['iphone_big_thumb'] = $iphone_big_thumb;
			$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => $itemUsers[$i]['Item']['title'],
				'title' => $itemUsers[$i]['Item']['title'],
				'type' => 'jpg',
				'full_url' => true
			);
			$iphone_small_thumb = getImageUrl('Item', $itemUsers[$i]['Item']['Attachment'][0], $image_options);
			unset($itemUsers[$i]['Item']['Attachment']);
			$itemUsers[$i]['Item']['iphone_small_thumb'] = $iphone_small_thumb;
			$host_gross = ($itemUsers[$i]['ItemUser']['price'] + $itemUsers[$i]['ItemUser']['booker_service_amount'] + $itemUsers[$i]['ItemUser']['additional_fee_amount']) - $itemUsers[$i]['ItemUser']['coupon_discount_amont'];
			$itemUsers[$i]['ItemUser']['gross'] = $host_gross;
			$days = intval(getFromToDiff($itemUsers[$i]['ItemUser']['from'], getToDate($itemUsers[$i]['ItemUser']['to'])));
			$itemUsers[$i]['ItemUser']['days'] = $days;
			$itemUsers[$i]['ItemUser']['gross'] = $host_gross;
			$itemUsers[$i]['Item']['iphone_small_thumb'] = $iphone_small_thumb;
			$itemUsers[$i]['Item']['User']['UserAvatar'] = !empty($itemUsers[$i]['Item']['User']['UserAvatar']) ? $itemUsers[$i]['Item']['User']['UserAvatar'] : array();
			$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => $itemUsers[$i]['Item']['User']['username'],
				'title' => $itemUsers[$i]['Item']['User']['username'],
				'type' => 'jpg',
				'full_url' => true
			);
			$iphone_small_thumb = getImageUrl('UserAvatar', $itemUsers[$i]['Item']['User']['UserAvatar'], $image_options);
			unset($itemUsers[$i]['Item']['User']['UserAvatar']);
			$itemUsers[$i]['Item']['User']['iphone_small_thumb'] = $iphone_small_thumb;
			 $itemUsers[$i]['User']['iphone_small_thumb'] = getImageUrl('UserAvatar', $itemUsers[$i]['User']['UserAvatar'], $image_options);
			 unset($itemUsers[$i]['User']['UserAvatar']);
		}
		$no_of_pages = ceil($total_items/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $itemUsers : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
    }
    public function validate_user($event)
    {
        $obj = $event->subject();
		//todo: swagger api issue
        if(!isset($event->data['data']['User'])){
		$obj->request->data['User'] = $event->data['data'];
        }
        if ((Configure::read('user.using_to_login') == 'email') && isset($obj->request->data['User']['username'])) {
            $obj->request->data['User']['email'] = $obj->request->data['User']['username'];
            unset($obj->request->data['User']['username']);
        }
        $obj->request->data['User'][Configure::read('user.using_to_login')] = trim($obj->request->data['User'][Configure::read('user.using_to_login')]);
		if (!empty($obj->request->data['User'][Configure::read('user.using_to_login')])) {
			$user = $obj->User->find('first', array(
				'conditions' => array(
					'User.username' => $obj->request->data['User'][Configure::read('user.using_to_login')]
				) ,
				'recursive' => 1
			));
            if (!empty($obj->request->data['Social'])) {
	        $obj->request->data['User']['password'] = $user['User']['password'];
            }else{
            $obj->request->data['User']['password'] = crypt($obj->request->data['User']['passwd'], $user['User']['password']);
            }
		}
        if ($obj->Auth->login()) {
            $mobile_app_hash = md5($obj->_unum() . $obj->request->data['User'][Configure::read('user.using_to_login') ] . $obj->request->data['User']['password'] . Configure::read('Security.salt'));
            $obj->User->updateAll(array(
                'User.mobile_app_hash' => '\'' . $mobile_app_hash . '\'',
                'User.mobile_app_time_modified' => '\'' . date('Y-m-d h:i:s') . '\'',
            ) , array(
                'User.id' => $obj->Auth->user('id')
            ));
            if (!empty($obj->request->data['User']['devicetoken'])) {
                //temporary hide for APNSDevice Table Enrtries
                //$obj->User->ApnsDevice->findOrSave_apns_device($obj->Auth->user('id') , $obj->request->data['User']);
            }
            if (!empty($_GET['latitude']) && !empty($_GET['longtitude'])) {
                $this->update_iphone_user($_GET['latitude'], $_GET['longtitude'], $obj->Auth->user('id'));
            }
            if (!empty($obj->request->data['Social']) || !empty($obj->request->data['User']['twitter_user_id']) || !empty($obj->request->data['User']['facebook_user_id'])) {
              if (!empty($obj->request->data['User']['twitter_user_id'])) { // Twitter modified registration: password  -> twitter user id and salt //
                  $iphone_small_thumb = $user['User']['twitter_avatar_url'];
              }else if(!empty($obj->request->data['User']['facebook_user_id'])){
                  $iphone_small_thumb = $user['User']['facebook_avatar_url'];
              }else{
                  $iphone_small_thumb = $user['User'][strtolower($obj->request->data['Social']['provider']) . '_avatar_url'];
              }
              $obj->request->data['User']['iphone_small_thumb'] = $iphone_small_thumb;
            }else{
                if($user['UserAvatar']['filename']){
                $image_options = array(
                                       'dimension' => 'iphone_medium_thumb',
                                       'class' => '',
                                       'alt' => $user['User']['username'],
                                       'title' => $user['User']['username'],
                                       'type' => 'jpg',
                                       'full_url' => true
                                       );
                $this->saveiPhoneAppThumb($user['UserAvatar'], 'User');
                $iphone_small_thumb = getImageUrl('User', $user['UserAvatar'], $image_options);
                 $obj->request->data['User']['iphone_small_thumb'] = $iphone_small_thumb;
                }else{
                 $obj->request->data['User']['iphone_small_thumb'] = $iphone_small_thumb;
                }
              }
            $fee_valid = 1;
            if(Configure::read('user.signup_fee') && $obj->Auth->user('id') && $obj->Auth->user('role_id') != ConstUserTypes::Admin) {
                if (empty($user['User']['is_paid'])) {
                    $fee_valid = 0;
                    $resonse = array(
                                     "message" => __l('You can login now, but you can able to access all features after paying signup fee.'),
                                     "error" => 0,
                                     "flag" => 1,
                                     "user_id" => $obj->Auth->user('id'),
                                     "activate_hash" => md5($obj->Auth->user('id') . '-' . Configure::read('Security.salt')),
                                     "membership_fee" => Configure::read('user.signup_fee')
                                    );
                }
            }
            if($fee_valid){
                $resonse = array(
                                 'error' => 0,
                                 'message' => __l('Success') ,
                                 'hash_token' => $mobile_app_hash,
                                 'username' => $obj->request->data['User'][Configure::read('user.using_to_login')],
                                 'user_id' => $obj->Auth->user('id'),
                                 'address' => ucwords($user['UserProfile']['address']),
                                 'iphone_big_thumb' => $obj->request->data['User']['iphone_small_thumb']
                                 );
            }
        } else {
            $resonse = array(
                'error' => 1,
                'checkauth' => YES,
                'message' => sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login'))
            );
        }
        if ($obj->RequestHandler->prefers('json')) {
            $obj->view = 'Json';
            $obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $resonse : $obj->viewVars['iphone_response']);
        }
    }
	public function onUpdateOrder($event)
    {
        $obj = $event->subject();
        $processed_order = !empty($event->data['processed_order'])? $event->data['processed_order'] : array();
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $processed_order : $obj->viewVars['iphone_response']);
    }
    public function onUserPaymentProfileItem($event)
    {
        $obj = $event->subject();
        $response = $event->data['data'];
		$obj->view = 'Json';
        $obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $obj->paginate() : $obj->viewVars['iphone_response']);
    }
    public function onRequestListing($event)
    {
        $obj = $event->subject();
        $page = $event->data['page'];	
		//$obj->paginate->recursive = 3;
        $requests = $obj->paginate();
		$total_items = $obj->Request->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));
		for ($i = 0; $i < count($requests); $i++) {
			$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => $requests[$i]['User']['username'],
				'title' => $requests[$i]['User']['username'],
				'type' => 'jpg',
				'full_url' => true
			);
			$iphone_small_thumb = getImageUrl('UserAvatar', $requests[$i]['User']['UserAvatar'], $image_options);
			$requests[$i]['User']['iphone_small_thumb'] = $iphone_small_thumb;
			unset($requests[$i]['User']['UserAvatar']);
			if(!empty($requests[$i]['ItemsRequest'])){
				foreach($requests[$i]['ItemsRequest'] as $key => $value){
					$this->saveiPhoneAppThumb($requests[$i]['ItemsRequest'][$key]['Item']['Attachment'][0]);
					$image_options = array(
						'dimension' => 'iphone_big_thumb',
						'class' => '',
						'alt' => $requests[$i]['ItemsRequest'][$key]['Item']['title'],
						'title' => $requests[$i]['ItemsRequest'][$key]['Item']['title'],
						'type' => 'jpg',
						'full_url' => true
					);
					$iphone_big_thumb = getImageUrl('Request', $requests[$i]['ItemsRequest'][$key]['Item']['Attachment'][0], $image_options);
					$requests[$i]['ItemsRequest'][$key]['Item']['iphone_big_thumb'] = $iphone_big_thumb;
					$image_options = array(
						'dimension' => 'iphone_small_thumb',
						'class' => '',
						'alt' => $requests[$i]['ItemsRequest'][$key]['Item']['title'],
						'title' => $requests[$i]['ItemsRequest'][$key]['Item']['title'],
						'type' => 'jpg',
						'full_url' => true
					);
					$iphone_small_thumb = getImageUrl('Request', $requests[$i]['ItemsRequest'][$key]['Item']['Attachment'][0], $image_options);
					$requests[$i]['ItemsRequest'][$key]['Item']['iphone_small_thumb'] = $iphone_small_thumb;
					unset($requests[$i]['ItemsRequest'][$key]['Item']['Attachment']);
				}
			}
		}
		$no_of_pages = ceil($total_items/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $requests : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);
        $obj->view = 'Json';
		$obj->set('json', $response);
    }
	public function saveiPhoneAppThumb($attachments, $model = 'Item')
    {
        $options[] = array(
            'dimension' => 'iphone_big_thumb',
            'class' => '',
            'alt' => '',
            'title' => '',
            'type' => 'jpg',
			'full_url' => true
        );
        $options[] = array(
            'dimension' => 'iphone_small_thumb',
            'class' => '',
            'alt' => '',
            'title' => '',
            'type' => 'jpg',
			'full_url' => true
        );
        $options[] = array(
           'dimension' => 'iphone_medium_thumb',
           'class' => '',
           'alt' => '',
           'title' => '',
           'type' => 'jpg',
           'full_url' => true
                           );
        $options[] = array(
           'dimension' => 'iphone_normal_thumb',
           'class' => '',
           'alt' => '',
           'title' => '',
           'type' => 'jpg',
           'full_url' => true
        );
        $attachment = $attachments;
        foreach($options as $option) {
			if(!empty($attachment['id'])) {
				$destination = APP . 'webroot' . DS . 'img' . DS . $option['dimension'] . DS . $model . DS . $attachment['id'] . '.' . md5(Configure::read('Security.salt') . $model . $attachment['id'] . $option['type'] . $option['dimension'] . Configure::read('site.name')) . '.' . $option['type'];
				if (!file_exists($destination) && !empty($attachment['id'])) {
					$url = getImageUrl($model, $attachment, $option);
					getimagesize($url);
				}
			}
        }
    }
    function conversationMessageContentDescription($conversation)
    {
        $conversationReplace = array(
             '##BOOKER##' => $conversation['ItemUser']['User']['username'],
             '##HOSTER##' => $conversation['Item']['User']['username'],
             '##SITE_NAME##' => Configure::read('site.name') ,
             '##CREATED_DATE##' => date('d/m/y', strtotime($conversation['ItemUser']['created'])) ,
             '##ACCEPTED_DATE##' => date('d/m/y', strtotime($conversation['ItemUser']['created'])) ,
             '##CLEARED_DATE##' => date('d/m/y', strtotime(date('Y-m-d H:i:s', strtotime('+1 days', strtotime($conversation['ItemUser']['from']))))) ,
             '##FROM_DATE##' => date('d/m/y', strtotime($conversation['ItemUser']['from'])) ,
             '##CLEARED_DAYS##' => 1
            );
        return strtr($conversation['ItemUserStatus']['description'], $conversationReplace);
    }
    public function onUserCommendLisitng($event)
    {
        $obj = $event->subject();
        $userComments = $obj->paginate();		
		$total_counts = $obj->UserComment->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));
		for ($i = 0; $i < count($userComments); $i++) {
			$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => $userComments[$i]['PostedUser']['username'],
				'title' => $userComments[$i]['PostedUser']['username'],
				'type' => 'jpg',
				'full_url' => true
			);
			$tmp_user = array();
			$tmp_user['id'] = $userComments[$i]['PostedUser']['id'];
			$tmp_user['role_id'] = $userComments[$i]['PostedUser']['role_id'];
			$tmp_user['username'] = $userComments[$i]['PostedUser']['username'];
			$tmp_user['is_facebook_friends_fetched'] = $userComments[$i]['PostedUser']['is_facebook_friends_fetched'];
			$tmp_user['twitter_avatar_url'] = $userComments[$i]['PostedUser']['twitter_avatar_url'];
			$tmp_user['user_avatar_source_id'] = $userComments[$i]['PostedUser']['user_avatar_source_id'];
			$tmp_user['attachment_id'] = $userComments[$i]['PostedUser']['attachment_id'];
			$tmp_user['facebook_user_id'] = $userComments[$i]['PostedUser']['facebook_user_id'];			
			$tmp_user['iphone_small_thumb'] = getImageUrl('UserAvatar', array('id' => $userComments[$i]['PostedUser']['attachment_id']), $image_options);				
			$userComments[$i]['PostedUser'] = $tmp_user;
		}
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $userComments : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
    }	
	public function onItemCategories($event)
    {
        $obj = $event->subject();
        $categories = $obj->viewVars['categories'];

		for ($i = 0; $i < count($categories); $i++) {
			$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => $categories[$i]['Category']['name'],
				'title' => $categories[$i]['Category']['name'],
				'type' => 'jpg',
				'full_url' => true
			);
			$categories[$i]['Category']['iphone_small_thumb'] = getImageUrl('Attachment', $categories[$i]['Attachment'], $image_options);
			unset($categories[$i]['Attachment']);
		}
		$no_of_pages = ceil(count($categories)/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $categories : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
    }	
	public function onItemSubCategories($event)
    {
        $obj = $event->subject();
        $categories = $obj->viewVars['categories'];
		//$id = $obj->viewVars['id'];
		$id = $event->data['id'];
		if($id != 0) {
			$categories = $obj->Category->find('all', array(
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
		$no_of_pages = ceil(count($categories)/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $categories : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
    }
    public function onItemMessage($event)
    {
        $obj = $event->subject();
        $messages = $obj->paginate();				
		$total_counts = $obj->Message->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));		
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		for ($i = 0; $i < count($messages); $i++) {
            $messages[$i]['Message']['created'] = date('d/m/y', strtotime($messages[$i]['Message']['created']));
			$messages[$i]['ItemUserStatus']['description'] = $this->conversationMessageContentDescription($messages[$i]);
            $messages[$i]['MessageContent']['message'] = strip_tags($messages[$i]['MessageContent']['message']);
            $image_options = array(
                                   'dimension' => 'iphone_medium_thumb',
                                   'class' => '',
                                   'alt' => $user['User']['username'],
                                   'title' => $user['User']['username'],
                                   'type' => 'jpg',
                                   'full_url' => true
                                   );
            $tmp_otheruser['iphone_small_thumb'] = getImageUrl('UserAvatar', $messages[$i]['OtherUser']['UserAvatar'], $image_options);
            $messages[$i]['OtherUser']['iphone_small_thumb'] = $tmp_otheruser['iphone_small_thumb'];
            $this->saveiPhoneAppThumb($messages[$i]['OtherUser']['UserAvatar']);
            unset($messages[$i]['OtherUser']['UserAvatar']);
            $tmp_user['iphone_small_thumb'] = getImageUrl('UserAvatar', $messages[$i]['User']['UserAvatar'], $image_options);
            $messages[$i]['User']['iphone_small_thumb'] = $tmp_user['iphone_small_thumb'];
            $this->saveiPhoneAppThumb($messages[$i]['User']['UserAvatar']);
            unset($messages[$i]['User']['UserAvatar']);
		}
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $messages : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
    }
    public function onUserView($event)
    {
        $obj = $event->subject();
        $user = $obj->viewVars['user'];
		
		$tmp_user = array();
		$tmp_user['id'] = $user['User']['id'];
        $tmp_user['created'] = date('d/m/y', strtotime($user['User']['created']));
        $tmp_user['modified'] = date('d/m/y', strtotime($user['User']['modified']));
		$tmp_user['role_id'] = $user['User']['role_id'];
		$tmp_user['username'] = $user['User']['username'];
		$tmp_user['is_facebook_friends_fetched'] = $user['User']['is_facebook_friends_fetched'];
		$tmp_user['twitter_avatar_url'] = $user['User']['twitter_avatar_url'];
		$tmp_user['user_avatar_source_id'] = $user['User']['user_avatar_source_id'];
		$tmp_user['attachment_id'] = $user['User']['attachment_id'];
		$tmp_user['facebook_user_id'] = $user['User']['facebook_user_id'];		
		$tmp_user['item_count'] = $user['User']['item_count'];
		$tmp_user['positive_feedback_count'] = $user['User']['positive_feedback_count'];
		$tmp_user['item_feedback_count'] = $user['User']['item_feedback_count'];
		$tmp_user['request_count'] = $user['User']['request_count'];
		$tmp_user['booker_positive_feedback_count'] = $user['User']['booker_positive_feedback_count'];
		$tmp_user['booker_item_user_count'] = $user['User']['booker_item_user_count'];
		$image_options = array(
				'dimension' => 'iphone_normal_thumb',
				'class' => '',
				'alt' => $user['User']['username'],
				'title' => $user['User']['username'],
				'type' => 'jpg',
				'full_url' => true
			);
		$tmp_user['iphone_small_thumb'] = getImageUrl('UserAvatar', $user['UserAvatar'], $image_options);
		unset($user['User']);
		$user['User'] = $tmp_user;
        $this->saveiPhoneAppThumb($user['UserAvatar']);	
		unset($user['UserAvatar']);		
		$follow_status = "";
		$follow_link = "";
        $follow_id = "";
		if($user['User']['id'] != $obj->Auth->user('id')){
			$follow_status = "Follow";
			$follow_link = Router::url(array('controller' => 'user_followers', 'action' => 'add', $user['User']['username']) , true);	
			if(!empty($user['UserFollower'])){				
						$follow_status = "Unfollow";
                        $follow_id = $user['UserFollower'][0]['id'];
						$follow_link = Router::url(array('controller' => 'user_followers', 'action' => 'delete', $user['UserFollower'][0]['id']), true);						
			}
		}
		unset($user['UserFollower']);
		$user['UserFollower'] = array(
				'follow_status' => $follow_status,
				'follow_link' => $follow_link,
                'follow_id' => $follow_id
			);
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $user : $obj->viewVars['iphone_response']);
    }	
    public function onUserFollower($event)
    {
        $obj = $event->subject();
        $user_followers = $obj->paginate();				
		$total_counts = $obj->UserFollower->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		if(!empty($user_followers)){
			foreach($user_followers as $key => $value){
			$image_options = array(
					'dimension' => 'iphone_small_thumb',
					'class' => '',
					'alt' => $user_followers[$key]['User']['username'],
					'title' => $user_followers[$key]['User']['username'],
					'type' => 'jpg',
					'full_url' => true
				);
			$user_followers[$key]['User']['iphone_small_thumb'] = getImageUrl('UserAvatar', $user_followers[$key]['User']['UserAvatar'], $image_options);
			unset($user_followers[$key]['User']['UserAvatar']);
			$image_options = array(
					'dimension' => 'iphone_small_thumb',
					'class' => '',
					'alt' => $user_followers[$key]['FollowUser']['username'],
					'title' => $user_followers[$key]['FollowUser']['username'],
					'type' => 'jpg',
					'full_url' => true
				);
			$user_followers[$key]['FollowUser']['iphone_small_thumb'] = getImageUrl('UserAvatar', $user_followers[$key]['FollowUser']['UserAvatar'], $image_options);
			unset($user_followers[$key]['FollowUser']['UserAvatar']);
			}
		}
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $user_followers : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
    }
	public function onUserFollowerAdd($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	public function onUserFollowerDelete($event)
	{
        $obj = $event->subject();
        $message = $event->data['message'];
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}	
    public function onMessageNotification($event)
    {
        $obj = $event->subject();
        $notifications = $obj->paginate();	
		$total_counts = $obj->Message->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));			
		

		if(!empty($notifications)){
			foreach($notifications as $key => $value){
				$image_options = array(
						'dimension' => 'iphone_small_thumb',
						'class' => '',
						'alt' => $notifications[$key]['User']['username'],
						'title' => $notifications[$key]['User']['username'],
						'type' => 'jpg',
						'full_url' => true
					);
				$notifications[$key]['User']['iphone_small_thumb'] = getImageUrl('UserAvatar', $notifications[$key]['User']['UserAvatar'], $image_options);
				unset($notifications[$key]['User']['UserAvatar']);
				$image_options = array(
						'dimension' => 'iphone_small_thumb',
						'class' => '',
						'alt' => $notifications[$key]['OtherUser']['username'],
						'title' => $notifications[$key]['OtherUser']['username'],
						'type' => 'jpg',
						'full_url' => true
					);
				$notifications[$key]['OtherUser']['iphone_small_thumb'] = getImageUrl('UserAvatar', $notifications[$key]['OtherUser']['UserAvatar'], $image_options);
				unset($notifications[$key]['OtherUser']['UserAvatar']);						
				foreach($notifications[$key]['Item'] as $field => $field_value){					
					if($field != "User" && $field != "Attachment" &&  !in_array("Item.".$field, $this->item_fields)){
						unset($notifications[$key]['Item'][$field]);
					}else if($field == "Attachment" && !empty($notifications[$key]['Item']['Attachment'])){
						$image_options['alt'] = $image_options['title'] = $notifications[$key]['Item']['title'];
						$notifications[$key]['Item']['iphone_small_thumb'] = getImageUrl('Item', $notifications[$key]['Item']['Attachment'][0], $image_options);	
						unset($notifications[$key]['Item']['Attachment']);						
					}
				}
				$user_fields = array(
                    'User.username',
                    'User.id',
					'User.role_id',
					'User.attachment_id',
                    'User.is_facebook_register',
                    'User.facebook_user_id',
                    'User.twitter_avatar_url'
                );		
				foreach($notifications[$key]['Item']['User'] as $field => $field_value){
					if($field != "UserAvatar" &&  !in_array("User.".$field, $user_fields)){
						unset($notifications[$key]['Item']['User'][$field]);
					}else if($field == "UserAvatar" && !empty($notifications[$key]['Item']['User']['UserAvatar'])){
						$image_options['alt'] = $image_options['title'] = $notifications[$key]['Item']['User']['username'];
						$notifications[$key]['Item']['User']['iphone_small_thumb'] = getImageUrl('UserAvatar', $notifications[$key]['Item']['User']['UserAvatar'], $image_options);
						unset($notifications[$key]['Item']['User']['UserAvatar']);
					}
				}
				foreach($notifications[$key]['ItemUser']['User'] as $field => $field_value){
					if($field != "UserAvatar" &&  !in_array("User.".$field, $user_fields)){
						unset($notifications[$key]['ItemUser']['User'][$field]);
					}else if($field == "UserAvatar" && !empty($notifications[$key]['ItemUser']['User']['UserAvatar'])){
						$image_options['alt'] = $image_options['title'] = $notifications[$key]['ItemUser']['User']['username'];
						$notifications[$key]['ItemUser']['User']['iphone_small_thumb'] = getImageUrl('UserAvatar', $notifications[$key]['ItemUser']['User']['UserAvatar'], $image_options);
						unset($notifications[$key]['ItemUser']['User']['UserAvatar']);
					}
				}				
			}
		}
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $notifications : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
    }
    public function onUserTransaction($event)
    {
        $obj = $event->subject();
        $transactions = $obj->paginate();	
		$total_counts = $obj->Transaction->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $transactions : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}
    public function onUserWithdrawals($event)
    {
        $obj = $event->subject();
        $userCashWithdrawal = $obj->paginate();	
		$total_counts = $obj->UserCashWithdrawal->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $userCashWithdrawal : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}	
	public function onUserWithdrawalsAdd($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
    public function onUserMoneyTransfer($event)
    {
        $obj = $event->subject();
        $moneyTransferAccounts = $obj->paginate();	
		$total_counts = $obj->MoneyTransferAccount->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		$user_fields = array(
                    'User.username',
                    'User.id',
					'User.role_id',
					'User.attachment_id',
                    'User.is_facebook_register',
                    'User.facebook_user_id',
                    'User.twitter_avatar_url'
                );	
		foreach($moneyTransferAccounts as $key => $value){
			foreach($moneyTransferAccounts[$key]['User'] as $field => $field_value){
				if(!in_array("User.".$field, $user_fields)){
					unset($moneyTransferAccounts[$key]['User'][$field]);
				}
			}
		}				
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $moneyTransferAccounts : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}
    public function onUserAffiliate($event)
    {
        $obj = $event->subject();
        $affiliates = $obj->paginate();	
		$total_counts = $obj->Affiliate->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $affiliates : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}	
	public function onAffiliateCashWithdrawal($event)
    {
        $obj = $event->subject();
        $affiliateCashWithdrawals = $obj->paginate();	
		$total_counts = $obj->AffiliateCashWithdrawal->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $affiliateCashWithdrawals : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}
	public function onAffiliateCashWithdrawalAdd($event)
	{
        $obj = $event->subject();
        $message = $event->data;
		$obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $message : $obj->viewVars['iphone_response']);
	}
	
	public function onItemCollection($event)
    {
        $obj = $event->subject();
        $collections = $obj->paginate();	
		$total_counts = $obj->Collection->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => "",
				'title' => "",
				'type' => 'jpg',
				'full_url' => true
		);					
		foreach($collections as $key => $value){
			if(!empty($collections[$key]['Attachment'])){
				$image_options['alt'] = $image_options['title'] = $collections[$key]['Collection']['title'];
				$collections[$key]['Collection']['iphone_small_thumb'] = getImageUrl('Item', $collections[$key]['Attachment'], $image_options);
				unset($collections[$key]['Attachment']);
			}else{
				 $collections[$key]['Collection']['iphone_small_thumb'] = "";
			}
		  foreach($collections[$key]['Item'] as $pos => $item){
			  foreach($collections[$key]['Item'][$pos] as $field => $data){
				if($field != "CollectionsItem" && $field != "Attachment" &&  !in_array("Item.".$field, $this->item_fields)){
					unset($collections[$key]['Item'][$pos][$field]);
				}else if($field == "Attachment" && !empty($collections[$key]['Item'][$pos]['Attachment'])){
					$image_options['alt'] = $image_options['title'] = $collections[$key]['Item'][$pos]['title'];
					$collections[$key]['Item'][$pos]['iphone_small_thumb'] = getImageUrl('Item', $collections[$key]['Item'][$pos]['Attachment'][0], $image_options);	
					unset($collections[$key]['Item'][$pos]['Attachment']);						
				}
			  }
		  }
		}
		
		
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $collections : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}	
	public function onRequestView($event)
    {
        $obj = $event->subject();
		$request = $obj->viewVars['request'];
		
		$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => "",
				'title' => "",
				'type' => 'jpg',
				'full_url' => true
		);		
		$user_fields = array(
                    'User.username',
                    'User.id',
					'User.role_id',
					'User.attachment_id',
                    'User.is_facebook_register',
                    'User.facebook_user_id',
                    'User.twitter_avatar_url'
                );	
		foreach($request['User'] as $field => $item){
			if($field != "UserAvatar"  &&  !in_array("User.".$field, $user_fields)){
				unset($request['User'][$field]);
			}else if($field == "UserAvatar" && !empty($request['User']['UserAvatar'])){
				$image_options['alt'] = $image_options['title'] = $request['Request']['title'];
				$request['User']['iphone_small_thumb'] = getImageUrl('UserAvatar', $request['User']['UserAvatar'], $image_options);	
				unset($request['User']['UserAvatar']);						
			}
		}
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $request : $obj->viewVars['iphone_response'],
				'_meta_data' =>  array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}	
	public function onItemFeedback($event)
    {
        $obj = $event->subject();
        $itemFeedbacks = $obj->paginate();	
		$total_counts = $obj->ItemFeedback->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 0
        ));	
		$image_options = array(
				'dimension' => 'iphone_small_thumb',
				'class' => '',
				'alt' => "",
				'title' => "",
				'type' => 'jpg',
				'full_url' => true
		);		
		$user_fields = array(
                    'User.username',
                    'User.id',
					'User.role_id',
					'User.attachment_id',
                    'User.is_facebook_register',
                    'User.facebook_user_id',
                    'User.twitter_avatar_url'
                );					
		foreach($itemFeedbacks as $pos => $itemFeedback){
			foreach($itemFeedback['Item'] as $field => $value){
				if($field != "Attachment"  &&  !in_array("Item.".$field, $this->item_fields)){
					unset($itemFeedbacks[$pos]['Item'][$field]);
				}else if($field == "Attachment" && !empty($itemFeedback['Item']['Attachment'])){
					$image_options['alt'] = $image_options['title'] = $itemFeedbacks[$pos]['Item']['title'];
					$itemFeedbacks[$pos]['Item']['iphone_small_thumb'] = getImageUrl('Item', $itemFeedbacks[$pos]['Item']['Attachment'][0], $image_options);	
					unset($itemFeedbacks[$pos]['Item']['Attachment']);						
				}				
			}
			foreach($itemFeedback['ItemUser']['User'] as $field => $value){
				if($field != "UserAvatar"  &&  !in_array("User.".$field, $user_fields)){
					unset($itemFeedbacks[$pos]['ItemUser']['User'][$field]);
				}else if($field == "UserAvatar" && !empty($itemFeedbacks[$pos]['ItemUser']['User']['UserAvatar'])){
					$image_options['alt'] = $image_options['title'] = $itemFeedbacks[$pos]['ItemUser']['User']['username'];
					$itemFeedbacks[$pos]['ItemUser']['User']['iphone_small_thumb'] = getImageUrl('Item', $itemFeedbacks[$pos]['ItemUser']['User']['UserAvatar'], $image_options);	
					unset($itemFeedbacks[$pos]['ItemUser']['User']['UserAvatar']);						
				}				
			}
			$temp_attachment = array();
			if(!empty($itemFeedback['Attachment'])){
				foreach($itemFeedback['Attachment'] as $posi => $attach){
					$image_options['alt'] = $image_options['title'] = $itemFeedbacks[$pos]['ItemFeedback']['feedback'];
					$temp_attachment[] = getImageUrl('Attachment', $itemFeedback['Attachment'][$posi], $image_options);	
				}
				$itemFeedback['Attachment'] = $temp_attachment;
			}
		}
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $itemFeedbacks : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}
	public function onItemUserFeedback($event)
    {
        $obj = $event->subject();
        $itemUserFeedbacks = $obj->paginate();	
		$total_counts = $obj->ItemUserFeedback->find('count', array(
            'conditions' => $obj->paginate['conditions'],
            'recursive' => 2
        ));	
		$no_of_pages = ceil($total_counts/20);
		$current_page = !empty($obj->request->params['named']['page']) ? $obj->request->params['named']['page'] : 1;
		
		$response = array(
				'data' => (empty($obj->viewVars['iphone_response'])) ? $itemUserFeedbacks : $obj->viewVars['iphone_response'],
				'_meta_data' => (empty($obj->viewVars['iphone_response'])) ? array('total_pages' => $no_of_pages, 'current_page' => $current_page) : array()
		);		
        $obj->view = 'Json';
		$obj->set('json', (empty($obj->viewVars['iphone_response'])) ? $response : $obj->viewVars['iphone_response']);
	}	
}
?>