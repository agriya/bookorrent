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
class ItemsCronComponent extends Component
{
    public function main()
    {
        App::import('Model', 'Items.Item');
        $this->Item = new Item();
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Model', 'Transaction');
        $this->Transaction = new Transaction();
        $this->update_for_waitingforreview();
        $this->auto_expire();
        $this->auto_expire_payment_pending_booking();
        $this->Item->_updateCityItemCount();
        $this->_updateTippedStatus();
        $this->_updateCustomNights();
        $this->_deleteIncompleteItems();
        // @todo "Auto review"
    }
    public function daily()
    {
		$this->currency_conversion(Configure::read('site.is_auto_currency_updation'));
    }
    public function currency_conversion($is_update = 0)
    {
        if (!empty($is_update)) {
            App::import('Model', 'Currency');
            $this->Currency = new Currency();
            $this->Currency->rate_conversion();
        }
    }
    public function _updateCustomNights($conditions = array())
	{
		$custom_price_per_nights = $this->Item->CustomPricePerNight->find('all', array(
            'conditions' => array(
                'CustomPricePerNight.start_date <' => date('Y-m-d') ,
                'CustomPricePerNight.is_available' => 1 ,
                'CustomPricePerNight.repeat_days' => '' ,
				'Item.is_sell_ticket' => 1,
            ) ,
            'recursive' => 0
        ));
		if (!empty($custom_price_per_nights)) {
			foreach($custom_price_per_nights as $custom_price_per_night) {
				if(strtotime($custom_price_per_night['CustomPricePerNight']['start_date'] . ' ' . $custom_price_per_night['CustomPricePerNight']['start_time']) < strtotime('now')) {
					$_data = array();
					$_data['CustomPricePerNight']['id'] = $custom_price_per_night['CustomPricePerNight']['id'];
					$_data['CustomPricePerNight']['is_available'] = 0;
					$this->Item->CustomPricePerNight->save($_data, false);
					$custom_price_per_night_count = $this->Item->CustomPricePerNight->find('count', array(
						'conditions' => array(
							'CustomPricePerNight.item_id' => $custom_price_per_night['CustomPricePerNight']['item_id'] ,
							'CustomPricePerNight.is_available' => 1 ,
						) ,
						'recursive' => -1
					));
					if($custom_price_per_night_count == 0) {
						$_item_data = array();
						$_item_data['Item']['id'] = $custom_price_per_night['CustomPricePerNight']['item_id'];
						$_item_data['Item']['is_available'] = 0;
						$this->Item->save($_item_data, false);
					}
				}
			}
		}
	}
    public function update_for_waitingforreview($conditions = array())
    {
        $itemUsers = $this->Item->ItemUser->find('all', array(
            'conditions' => array(
                'ItemUser.to <=' => date('Y-m-d H:i:s'),
				'ItemUser.item_user_status_id' => ConstItemUserStatus::Confirmed,
				'ItemUser.is_payment_cleared' => 1,
            ) ,
            'recursive' => -1
        ));
		if (!empty($itemUsers)) {
			foreach($itemUsers as $itemUser) {
				$this->Item->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::WaitingforReview);
			}
		}
    }
    public function auto_expire_payment_pending_booking()
    {
        $itemUsers = $this->Item->ItemUser->find('all', array(
            'conditions' => array(
                'ItemUser.item_user_status_id' => ConstItemUserStatus::PaymentPending,
                'ItemUser.from <' => date('Y-m-d H:i:s')
            ) ,
            'recursive' => -1,
        ));
		if (!empty($itemUsers)) {
			foreach($itemUsers as $itemUser) {
				$_data = array();
				$_data['ItemUser']['id'] = $itemUser['ItemUser']['id'];
				$_data['ItemUser']['item_user_status_id'] = ConstItemUserStatus::Expired;
				$this->Item->ItemUser->save($_data, false);
			}
		}
    }
    public function auto_expire()
    {
        $itemUsers = $this->Item->ItemUser->find('all', array(
            'conditions' => array(
                'ItemUser.item_user_status_id' => ConstItemUserStatus::WaitingforAcceptance,
                'ItemUser.created <=' => date('Y-m-d H:i:s', strtotime('now - ' . Configure::read('item.auto_expire') . ' days'))
            ) ,
            'recursive' => -1
        ));
		if (!empty($itemUsers)) {
			foreach($itemUsers as $itemUser) {
				$this->Item->ItemUser->updateStatus($itemUser['ItemUser']['id'], ConstItemUserStatus::Expired);
			}
		}
    }
	public function _updateTippedStatus() 
	{
		App::import('Model', 'Items.CustomPricePerNight');
        $this->CustomPricePerNight = new CustomPricePerNight();
		$custom_price_per_nights = $this->CustomPricePerNight->find('all', array(
            'conditions' => array(
                'CustomPricePerNight.is_tipped' => 0,
                'CustomPricePerNight.end_date <' => date('Y-m-d', strtotime('now'))
            ) ,
			'contain' => array(
				'ItemUser',
			),
            'recursive' => 1
        ));
		if (!empty($custom_price_per_nights)) {
			foreach ($custom_price_per_nights As $custom_price_per_night) {
				if (!empty($custom_price_per_night['ItemUser'])) {
					foreach($custom_price_per_night['ItemUser'] as $itemUser) {
						if ($itemUser['item_user_status_id'] == ConstItemUserStatus::Confirmed || $itemUser['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance) {
							$this->Item->ItemUser->updateStatus($itemUser['id'], ConstItemUserStatus::Expired);
						}
					}
				}
			}
		}
	}
	public function _deleteIncompleteItems()
	{
		$items = $this->Item->find('list', array(
            'conditions' => array(
                'Item.is_completed' => 0,
            ) ,
			'fields' => array(
				'Item.id',
				'Item.id'
			),
            'recursive' => -1,
        ));
		if(!empty($items)){
			foreach($items as $item){
				$this->Item->delete($item);
			}
		}
	}
}