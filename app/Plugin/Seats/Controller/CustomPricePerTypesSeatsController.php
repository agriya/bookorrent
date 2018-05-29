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
class CustomPricePerTypesSeatsController extends AppController
{
    public $name = 'CustomPricePerTypesSeats';
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
			'CustomPricePerTypesSeat.seat_ids'		
        );
        parent::beforeFilter();
    }	
	/**
	 *
	 * @Description: Item mapped partition seats status update. [Note: CustomPricePerTypesSeat table status update]
	 *
	 */
    public function edit($id = null, $custom_type_id = null)
    {
		if (is_null($id) || is_null($custom_type_id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$partition = $this->CustomPricePerTypesSeat->Partition->find('first', array(
			'conditions' => array(
				'Partition.id' => $id
			),			
			'recursive' => -1
		));
		$this->pageTitle = "Update Partition Seats Status "."- ". $partition['Partition']['name'];
		if (empty($partition)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$custom_price_per_type_seats = $this->CustomPricePerTypesSeat->find('first', array(
			'fields' => array (
				'CustomPricePerTypesSeat.item_id'
			),
			'contain' => array(
				'CustomPricePerType' => array(
					'fields' => array (
						'CustomPricePerType.id',
						'CustomPricePerType.custom_price_per_night_id',
						'CustomPricePerType.price',
						'CustomPricePerType.start_time',
						'CustomPricePerType.end_time'
					)				
				)
			),
			'conditions' => array (
				'CustomPricePerTypesSeat.partition_id' => $partition['Partition']['id'],
				'CustomPricePerTypesSeat.custom_price_per_type_id' => $custom_type_id
			),				
			'recursive' => 0
		));			
		if (!empty($this->request->data)) {
			if($this->CustomPricePerTypesSeat->validates($this->request->data['CustomPricePerTypesSeat']) && !empty($this->request->data['CustomPricePerTypesSeat']['result'])){
				$data = json_decode($this->request->data['CustomPricePerTypesSeat']['result']);
				$this->request->data['CustomPricePerTypesSeat'] = array();
				foreach($data as $key =>  $value){	
					$temp = array(
					  "][" => ":",
					  "[" => "",
					  "]" => ""
					);
					$key = strtr($key, $temp);	
					$sep_data = explode(":", $key);
					if(isset($sep_data[0]) && $sep_data[0] == "dataCustomPricePerTypesSeat" && (in_array($sep_data[2], array('id', 'class')))){
						$this->request->data['CustomPricePerTypesSeat'][$sep_data[1]][$sep_data[2]] = $value;
					}					
				}				
				if (!empty($this->request->data['CustomPricePerTypesSeat'])){
					$seats = array('CustomPricePerTypesSeat' => array());
					foreach($this->request->data['CustomPricePerTypesSeat'] as $key => $value){
						$data = array();
						$row_col = explode("-", $key);
						$data['id'] = $value['id'];
						$data['seat_status_id'] = $value['class'];
						$seats['CustomPricePerTypesSeat'][] = $data;
					}
					if(!empty($seats['CustomPricePerTypesSeat'])){
						$this->CustomPricePerTypesSeat->saveAll($seats['CustomPricePerTypesSeat']);
					}
					$total_count = $this->CustomPricePerTypesSeat->find('count', array(
						'conditions' => array(
							'CustomPricePerTypesSeat.custom_price_per_type_id' => $custom_type_id,
							'CustomPricePerTypesSeat.partition_id' => $partition['Partition']['id'],
							'CustomPricePerTypesSeat.seat_status_id' => array(ConstSeatStatus::Available, ConstSeatStatus::Blocked, ConstSeatStatus::Booked, ConstSeatStatus::WaitingForAcceptance)
						)
					));
					if(!empty($total_count)) {
						$this->CustomPricePerTypesSeat->CustomPricePerType->updateAll(array(
							'CustomPricePerType.max_number_of_quantity' => $total_count
						), array(
							'CustomPricePerType.id' => $custom_price_per_type_seats['CustomPricePerType']['id']
						));
					}
					$this->Session->setFlash(__l('Seat status has been update') , 'default', null, 'success');
					$item = $this->CustomPricePerTypesSeat->find('first', array(
						'conditions' => array(
							'CustomPricePerTypesSeat.partition_id' => $partition['Partition']['id']
						),
						'contain' => array(
							'Item' => array(
								'fields' => array(
									'Item.id',
									'Item.title',
									'Item.slug'
								)
							)
						),
						'recursive' => 1
					));
					$this->redirect(array(
						'controller' => 'items',
						'action' => 'partitions',
						'slug' => $item['Item']['slug']
					));
				} else {
					$this->Session->setFlash(__l('Seat status could not be update. Please, try again.') , 'default', null, 'error');
				}
			}else {
				$this->Session->setFlash(__l('Seat status could not be update. Please, try again.') , 'default', null, 'error');
			}			
		
		}else{
			$seats = $this->CustomPricePerTypesSeat->find('all', array(
					'conditions' => array(
							'CustomPricePerTypesSeat.custom_price_per_type_id' => $custom_type_id,
							'CustomPricePerTypesSeat.partition_id' => $partition['Partition']['id']
					),
					'contain' => array(
						'Seat' => array(
							'fields' => array(
								'Seat.id',
								'Seat.row',
								'Seat.column'
							),								
						),
					),
					'order' => array(
						'CustomPricePerTypesSeat.position' => 'ASC'
					),
					'recursive' => 1 
				)		
			);
			$this->request->data['CustomPricePerTypesSeat']['partition_id']	= $partition['Partition']['id'];
			foreach($seats as $key => $value){
				$this->request->data['CustomPricePerTypesSeat'][$value['Seat']['row'].'-'.$value['Seat']['column']]['id'] = $value['CustomPricePerTypesSeat']['id'];
				$this->request->data['CustomPricePerTypesSeat'][$value['Seat']['row'].'-'.$value['Seat']['column']]['order'] = $value['CustomPricePerTypesSeat']['position'];
				$this->request->data['CustomPricePerTypesSeat'][$value['Seat']['row'].'-'.$value['Seat']['column']]['class'] = $value['CustomPricePerTypesSeat']['seat_status_id'];
				$this->request->data['CustomPricePerTypesSeat'][$value['Seat']['row'].'-'.$value['Seat']['column']]['name'] = $value['CustomPricePerTypesSeat']['name'];
			}				
		}			
		$item = $this->CustomPricePerTypesSeat->Item->find('first', array(
			'fields' => array(
				'Item.id',
				'Item.title',
				'Item.created',
				'Item.address',
				'Item.slug',
				'Item.item_view_count',
				'Item.positive_feedback_count',
				'Item.item_feedback_count',
				
			),
			'conditions' => array (
				'Item.id' => $custom_price_per_type_seats['CustomPricePerTypesSeat']['item_id'],					
			),
			'contain' => array(
				'Country' => array(
					'fields' => array(
						'Country.name',
						'Country.iso_alpha2',						
					),
				),
				'Attachment',
				'CustomPricePerNight' => array(
					'fields' => array(
						'CustomPricePerNight.id',
						'CustomPricePerNight.start_date',
						'CustomPricePerNight.end_date'
					),
					'conditions' => array(
						'CustomPricePerNight.id' => $custom_price_per_type_seats['CustomPricePerType']['custom_price_per_night_id']
					)
				)
			),
			'recursive' => 1
		));		
		$rowNames = $this->CustomPricePerTypesSeat->Seat->generate_row_name($partition['Partition']['no_of_rows'], $partition['Partition']['seating_name_type']);
		$seat_status = array(ConstSeatStatus::Available => "Available", ConstSeatStatus::Unavailable => "Unavailable", ConstSeatStatus::NoSeat => "NoSeat");		
		$this->set(compact('partition', 'rowNames', 'seat_status', 'custom_price_per_type_seats', 'item'));
    }
	/**
	 *
	 * @Description: Selected seats status changed as blocked.
	 *
	 */
	public function booking() {	
		if ($this->RequestHandler->prefers('json')) {
			$this->request->data['CustomPricePerTypesSeat'] = $this->request->data;
		}
		$ids = explode(',', $this->request->data['CustomPricePerTypesSeat']['seat_ids']);	
		$seats = $this->CustomPricePerTypesSeat->find('all', array(
				'conditions' => array(
					'id' => $ids
				),
				'recursive' => -1
			)
		);	
		$itemUser = $this->CustomPricePerTypesSeat->ItemUser->find('first', array(
				'conditions' => array(
						'ItemUser.id' => $this->request->data['CustomPricePerTypesSeat']['item_user_id']
				),
				'contain' => array(
					'CustomPricePerTypesSeat',
					'CustomPricePerTypeItemUser'
				),
				'recursive' => 2 
			)		
		);
		$item = $this->CustomPricePerTypesSeat->ItemUser->Item->find('first', array(
				'conditions' => array(
						'Item.id' => $this->request->data['CustomPricePerTypesSeat']['item_id']
				),
				'recursive' => -1 
			)		
		);
		if(empty($seats) || empty($itemUser) || empty($item) || $itemUser['ItemUser']['user_id'] != $this->Auth->user('id')){
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
		}
		$updateSeats = array('CustomPricePerTypesSeat' => array());
		$booking_Seats = array();
		$block_time = $this->request->data['CustomPricePerTypesSeat']['block_time'];
		$blocking_time = date('Y-m-d H:i:s', $block_time);		
		foreach($seats as $seat){
			if($seat['CustomPricePerTypesSeat']['seat_status_id'] == ConstSeatStatus::Available || $seat['CustomPricePerTypesSeat']['seat_status_id'] == ConstSeatStatus::Blocked){
				$temp = array();

				$temp['id'] = $seat['CustomPricePerTypesSeat']['id'];	
				$temp['seat_status_id'] = ConstSeatStatus::Blocked;	
				$temp['item_user_id'] = $this->request->data['CustomPricePerTypesSeat']['item_user_id'];
				$temp['custom_price_per_type_item_user_id'] = $itemUser['CustomPricePerTypeItemUser'][0]['id'];
				$temp['booking_start_time'] = $blocking_time;
				$temp['blocked_user_id'] = $this->Auth->user('id');
				$booking_Seats[] = $seat['CustomPricePerTypesSeat']['id'];
				$updateSeats['CustomPricePerTypesSeat'][] = $temp;
			}
		}
		// previous selected Seats status revert to Available
		if(!empty($itemUser['CustomPricePerTypesSeat'])){
			foreach($itemUser['CustomPricePerTypesSeat'] as $seat){
				if(!in_array($seat['id'], $booking_Seats)){
					if($seat['seat_status_id'] == ConstSeatStatus::Blocked){
						$temp = array();
						$temp['id'] = $seat['id'];	
						$temp['seat_status_id'] = ConstSeatStatus::Available;	
						$temp['item_user_id'] = null;
						$temp['custom_price_per_type_item_user_id'] = null;
						$temp['booking_start_time'] = null;
						$temp['blocked_user_id'] = null;
						$updateSeats['CustomPricePerTypesSeat'][] = $temp;						
					}
				}
			}
		}
		if(!empty($updateSeats['CustomPricePerTypesSeat'])){
			$this->CustomPricePerTypesSeat->saveAll($updateSeats['CustomPricePerTypesSeat']);
			$this->Session->delete('SeatBlockTime');
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Seats selected successfully'), "error" => 1));
			}else{
				$this->redirect(array(
					'controller' => 'items',
					'action' => 'order',
					$this->request->data['CustomPricePerTypesSeat']['item_id'],
					'order_id' => $this->request->data['CustomPricePerTypesSeat']['item_user_id']
				));	
			}
		}else{
			$this->Session->delete('SeatBlockTime');
			$this->redirect(array(
				'controller' => 'items',
				'action' => 'view',
				'slug' => $item['Item']['slug']				
			));	
		}
		if ($this->RequestHandler->prefers('json')) {
            Cms::dispatchEvent('Controller.CustomPricePerTypesSeats.SeatBooking', $this, array());
        }
	}
	
	public function preview($id= null, $slug = null) {
		$this->pageTitle = 'Partition Preview';
		if (is_null($slug) || is_null($id)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }		
		$partition = $this->CustomPricePerTypesSeat->Partition->find('first', array(
			'conditions' => array(
				'Partition.slug' => $slug
			),
			'recursive' => -1
		));
		if (empty($partition)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		$seats = $this->CustomPricePerTypesSeat->find('all', array(
				'conditions' => array(
						'CustomPricePerTypesSeat.custom_price_per_type_id' => $id,
						'CustomPricePerTypesSeat.partition_id' => $partition['Partition']['id']
				),
				'contain' => array(
					'Seat' => array(
						'fields' => array(
							'Seat.id',
							'Seat.row',
							'Seat.column'
						),								
					),
				),
				'order' => array(
					'CustomPricePerTypesSeat.position' => 'ASC'
				),
				'recursive' => 1 
			)		
		);
		if (empty($seats)) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }		
		$seat_map = "";
		$col = 1;
		$temp_row = $available_arr = $unavailable_arr = $blocked_arr = $booking_arr = $noseat_arr = $booked_arr = $selected_arr = $row_name = $temp_row_name = "";
		if($partition['Partition']['seating_name_type'] == ConstSeatNameType::Number){
			$row_name = implode(",", range(1, $partition['Partition']['no_of_rows']));
		}
		foreach($seats as $seat){
			$text = "";
			$row_col = explode('-', $seat['CustomPricePerTypesSeat']['name']);
			if($partition['Partition']['seating_name_type'] != ConstSeatNameType::Number){
				if($row_col[0] != $temp_row_name){
					// type other than number seat -> rowname-colname Ex: R-10
					$temp_row_name = $row_col[0];
					$row_name = (empty($row_name)) ? "'".$temp_row_name."'" : $row_name.", '".$temp_row_name."'";
				}
			}else{
				// type  number seat -> Seat Number Ex: 10
				$row_col[1] = $seat['CustomPricePerTypesSeat']['name'];
			}
			if($seat['CustomPricePerTypesSeat']['seat_status_id'] == ConstSeatStatus::Available){
				$text = "a[".$seat['CustomPricePerTypesSeat']['id'].",".$row_col[1]."]";
				$available_arr  = (empty($available_arr)) ? $seat['CustomPricePerTypesSeat']['id'] : $available_arr.", ".$seat['CustomPricePerTypesSeat']['id']; 
			}else if($seat['CustomPricePerTypesSeat']['seat_status_id'] == ConstSeatStatus::Unavailable){
				$text = "u[".$seat['CustomPricePerTypesSeat']['id'].",".$row_col[1]."]";
				$unavailable_arr  = (empty($unavailable_arr)) ? $seat['CustomPricePerTypesSeat']['id'] : $unavailable_arr.", ".$seat['CustomPricePerTypesSeat']['id']; 
			}else if($seat['CustomPricePerTypesSeat']['seat_status_id'] == ConstSeatStatus::Blocked){	
				$text = "l[".$seat['CustomPricePerTypesSeat']['id'].",".$row_col[1]."]";
				$blocked_arr  = (empty($blocked_arr)) ? $seat['CustomPricePerTypesSeat']['id'] : $blocked_arr.", ".$seat['CustomPricePerTypesSeat']['id'];
			}else if($seat['CustomPricePerTypesSeat']['seat_status_id'] == ConstSeatStatus::WaitingForAcceptance){
				$text = "w[".$seat['CustomPricePerTypesSeat']['id'].",".$row_col[1]."]";
				$booking_arr  = (empty($booking_arr)) ? $seat['CustomPricePerTypesSeat']['id'] : $booking_arr.", ".$seat['CustomPricePerTypesSeat']['id'];
			}else if($seat['CustomPricePerTypesSeat']['seat_status_id'] == ConstSeatStatus::Booked){
				$text = "b[".$seat['CustomPricePerTypesSeat']['id'].",".$row_col[1]."]";
				$booked_arr  = (empty($booked_arr)) ? $seat['CustomPricePerTypesSeat']['id'] : $booked_arr.", ".$seat['CustomPricePerTypesSeat']['id']; 
			}else if($seat['CustomPricePerTypesSeat']['seat_status_id'] == ConstSeatStatus::NoSeat){
				$text = "_";
				$noseat_arr  = (empty($noseat_arr)) ? $seat['CustomPricePerTypesSeat']['id'] : $noseat_arr.", ".$seat['CustomPricePerTypesSeat']['id']; 
			}
			$temp_row .=$text;
			$col++;					
			if($partition['Partition']['no_of_columns'] < $col){
				if(empty($seat_map)){
					$seat_map = "'".$temp_row."'";
				}else{
					$seat_map .= ",'".$temp_row."'";
				}
				$temp_row = "";
				$col = 1;
			}
			
		}	
		$custom_price_per_type_seats = $this->CustomPricePerTypesSeat->find('first', array(
			'fields' => array (
				'CustomPricePerTypesSeat.item_id'
			),
			'conditions' => array (
				'CustomPricePerTypesSeat.partition_id' => $partition['Partition']['id']
			),				
			'recursive' => -1
		));
		$contain = array(			
			'CustomPricePerNight' => array(
				'fields' => array(
					'CustomPricePerNight.start_date',
					'CustomPricePerNight.end_date'
				),				
			),
		);
		$CustomPricePerType = $this->CustomPricePerTypesSeat->CustomPricePerType->find('first', array(
			'fields' => array(
				'CustomPricePerType.price',
				'CustomPricePerType.start_time',
				'CustomPricePerType.end_time',
				
			),
			'conditions' => array (
					'CustomPricePerType.item_id' => $custom_price_per_type_seats['CustomPricePerTypesSeat']['item_id'],
					'CustomPricePerType.partition_id' => $partition['Partition']['id'],
			),	
			'contain' => $contain,
			'recursive' => 1
		));
		$item = $this->CustomPricePerTypesSeat->Item->find('first', array(
			'fields' => array(
				'Item.id',
				'Item.title',
				'Item.created',
				'Item.address',
				'Item.slug',
				'Item.item_view_count',
				'Item.positive_feedback_count',
				'Item.item_feedback_count',
				
			),
			'conditions' => array (
				'Item.id' => $custom_price_per_type_seats['CustomPricePerTypesSeat']['item_id'],					
			),
			'contain' => array(
				'Country' => array(
					'fields' => array(
						'Country.name',
						'Country.iso_alpha2',						
					),
				),
				'Attachment'
			),
			'recursive' => 1
		));		
		$this->set(compact('item','CustomPricePerType', 'partition', 'seat_map', 'available_arr', 'unavailable_arr', 'booked_arr', 'noseat_arr', 'row_name', 'selected_arr', 'blocked_arr', 'booking_arr'));
	}
	public function seat_selection(){		
		if(!empty($this->request->params['named']['message']) && $this->request->params['named']['message'] == 'payment-timeout') {
			$this->Session->setFlash(__l('Time expired.Please try again') , 'default', null, 'success');
		}
		if ($this->RequestHandler->prefers('json')) {
			$this->request->params['named']['order_id'] = $this->request->params['pass'][0];
		}
        if (empty($this->request->params['named']['order_id'])) {
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}
        }
		// update blocked to available if the blocked time exceeds			
		$blocked_seats = $this->CustomPricePerTypesSeat->find('all', array(
			'conditions' => array(
				'CustomPricePerTypesSeat.booking_start_time <' => date("Y-m-d H:i:s"),
				'CustomPricePerTypesSeat.seat_status_id' => ConstSeatStatus::Blocked
			),
			'recursive' => -1
		));				
		$updateSeats = array('CustomPricePerTypesSeat' => array());
		foreach($blocked_seats as $blocked_seat){			
			$temp = array();
				$temp['id'] = $blocked_seat['CustomPricePerTypesSeat']['id'];	
				$temp['seat_status_id'] = ConstSeatStatus::Available;	
				$temp['item_user_id'] = null;
				$temp['custom_price_per_type_item_user_id'] = null;
				$temp['booking_start_time'] = '';
				$temp['blocked_user_id'] = null;
				$updateSeats['CustomPricePerTypesSeat'][] = $temp;
		}
		if(!empty($updateSeats['CustomPricePerTypesSeat'])){
			$this->CustomPricePerTypesSeat->saveAll($updateSeats['CustomPricePerTypesSeat']);
		}	
		// end
		
		$contain = array(
				'CustomPricePerTypesSeat' =>array(
					'Seat' => array(
						'fields' => array(
							'Seat.id',
							'Seat.row',
							'Seat.column'
						),								
					),					
					'fields' => array(
						'CustomPricePerTypesSeat.id',
						'CustomPricePerTypesSeat.name',
						'CustomPricePerTypesSeat.booking_start_time'
					)
				),
 				'User' => array(
					'fields' => array(
						'User.id',
						'User.username'
					)
				),
				'Item' => array(
					'fields' => array(
						'Item.id',
						'Item.title'
					)
				),
				'CustomPricePerNight',
				'CustomPricePerTypeItemUser' => array(
					'CustomPricePerType' => array(
						'Partition' => array(
							'Hall' => array(
								'fields' => array(
									'Hall.id',
									'Hall.name'
								)
							)							
						),
						'CustomPricePerTypesSeat' => array(
							'Seat' => array(
								'fields' => array(
									'Seat.id',
									'Seat.row',
									'Seat.column'
								),								
							),
							'order' => array(
								'CustomPricePerTypesSeat.position' => 'ASC'
							)
						)
					),
				),
			);
		$itemUser = $this->CustomPricePerTypesSeat->ItemUser->find('first', array(
			'conditions' => array(
				'ItemUser.id' => $this->request->params['named']['order_id']
			),
			'contain' => $contain,
			'recursive' => 4
		));
		// if before login order create, then after login need to update user detail changes 
		if($itemUser['ItemUser']['user_id'] == null){
			$this->CustomPricePerTypesSeat->ItemUser->updateAll(array(
				'ItemUser.user_id' => $this->Auth->user('id')
			), array(
				'ItemUser.id' => $itemUser['ItemUser']['id']
			));
			$itemUser['ItemUser']['user_id'] = $this->Auth->user('id');
		}
		if(empty($itemUser) || empty($itemUser['CustomPricePerTypeItemUser'][0]['CustomPricePerType']['CustomPricePerTypesSeat']) || ($itemUser['ItemUser']['user_id'] != $this->Auth->user('id')) || $itemUser['ItemUser']['item_user_status_id'] != ConstItemUserStatus::PaymentPending){
			if ($this->RequestHandler->prefers('json')) {
				$this->set('iphone_response', array("message" => __l('Invalid request'), "error" => 1));
			}else{
				throw new NotFoundException(__l('Invalid request'));
			}			
		}else{
			$seats = $itemUser['CustomPricePerTypeItemUser'][0]['CustomPricePerType']['CustomPricePerTypesSeat'];
			$partition = $itemUser['CustomPricePerTypeItemUser'][0]['CustomPricePerType']['Partition'];
			$reserved_titcket = count($itemUser['CustomPricePerTypesSeat']);
			foreach($itemUser['CustomPricePerTypesSeat'] as $selected_seat){
				if(empty($selected_seats)){
					$selected_seats = $selected_seat['name'];
				}else{
					$selected_seats .= ', ' . $selected_seat['name'];
				}
			}
			$seat_map = "";
			$col = 1;
			$temp_row = $available_arr = $unavailable_arr = $blocked_arr = $booking_arr = $noseat_arr = $booked_arr = $selected_arr = $row_name = $temp_row_name = "";
			if($partition['seating_name_type'] == ConstSeatNameType::Number){
				$row_name = implode(",", range(1, $partition['no_of_rows']));
			}
			foreach($seats as $seat){
				$text = "";
				$row_col = explode('-', $seat['name']);
				if($partition['seating_name_type'] != ConstSeatNameType::Number){
					if( $row_col[0] != $temp_row_name){
						// type other than number seat -> rowname-colname Ex: R-10 OR IX-10
						$temp_row_name = $row_col[0];
						$row_name = (empty($row_name)) ? "'".$temp_row_name."'" : $row_name.", '".$temp_row_name."'";
					}
				}else{
					// type  number seat -> Seat Number Ex: 10
					$row_col[1] = $seat['name'];
				}
				if($seat['seat_status_id'] == ConstSeatStatus::Available){
					$text = "a[".$seat['id'].",".$row_col[1]."]";
					$available_arr  = (empty($available_arr)) ? $seat['id'] : $available_arr.", ".$seat['id']; 
				}else if($seat['seat_status_id'] == ConstSeatStatus::Unavailable){
					$text = "u[".$seat['id'].",".$row_col[1]."]";
					$unavailable_arr  = (empty($unavailable_arr)) ? $seat['id'] : $unavailable_arr.", ".$seat['id']; 
				}else if($seat['seat_status_id'] == ConstSeatStatus::Blocked){	
					if($seat['item_user_id'] == $itemUser['ItemUser']['id']){
						$text = "s[".$seat['id'].",".$row_col[1]."]";
						$selected_arr  = (empty($selected_arr)) ? $seat['id'] : $selected_arr.", ".$seat['id']; 
						$this->request->data['CustomPricePerTypesSeat']['seat_ids'] = (empty($this->request->data['CustomPricePerTypesSeat']['seat_ids'])) ? $seat['id'] : $this->request->data['CustomPricePerTypesSeat']['seat_ids'].",".$seat['id']; 
					}else{
						$text = "l[".$seat['id'].",".$row_col[1]."]";
						$blocked_arr  = (empty($blocked_arr)) ? $seat['id'] : $blocked_arr.", ".$seat['id'];
					}
				}else if($seat['seat_status_id'] == ConstSeatStatus::WaitingForAcceptance){	
					$text = "w[".$seat['id'].",".$row_col[1]."]";
					$booking_arr  = (empty($booking_arr)) ? $seat['id'] : $booking_arr.", ".$seat['id'];
				}else if($seat['seat_status_id'] == ConstSeatStatus::Booked){
					$text = "b[".$seat['id'].",".$row_col[1]."]";
					$booked_arr  = (empty($booked_arr)) ? $seat['id'] : $booked_arr.", ".$seat['id']; 
				}else if($seat['seat_status_id'] == ConstSeatStatus::NoSeat){
					$text = "_";
					$noseat_arr  = (empty($noseat_arr)) ? $seat['id'] : $noseat_arr.", ".$seat['id']; 
				}
				$temp_row .=$text;
				$col++;					
				if($partition['no_of_columns'] < $col){
					if(empty($seat_map)){
						$seat_map = "'".$temp_row."'";
					}else{
						$seat_map .= ",'".$temp_row."'";
					}
					$temp_row = "";
					$col = 1;
				}
			}
			$total = Configure::read('seat.maximum_seat_blocking_time') * 60;
			$url = Router::url(array('controller' => 'item_users', 'action' => 'delete', $itemUser['ItemUser']['id'], 'type' => 'booking_timeout'), true);
			if(!empty($itemUser['CustomPricePerTypesSeat'])) {
				$block_date = $itemUser['CustomPricePerTypesSeat'][0]['booking_start_time'];
				$cur_time = strtotime(date('Y-m-d H:i:s'));
				$block_time = strtotime($block_date);
				$this->Session->write('SeatBlockTime', $block_time);
				if($block_time > $cur_time) {
					$diff = $block_time - $cur_time;
					$minutes =  date('i', ($diff));
					$secs =  date('s', ($diff));
					$total = ($minutes * 60) + $secs;
				} else {
					$this->Session->delete('SeatBlockTime');
					$this->redirect(array('controller' => 'item_users', 'action' => 'delete', $itemUser['ItemUser']['id'], 'type' => 'booking_timeout'));
				}
			}else{
				if ($this->Session->read('SeatBlockTime') != null) {
					$block_time = $this->Session->read('SeatBlockTime');
					$cur_time = strtotime(date('Y-m-d H:i:s'));
					if($block_time > $cur_time) {
						$diff = $block_time - $cur_time;
						$minutes =  date('i', ($diff));
						$secs =  date('s', ($diff));
						$total = ($minutes * 60) + $secs;
					} else {
						$this->Session->delete('SeatBlockTime');
						$this->redirect(array('controller' => 'item_users', 'action' => 'delete', $itemUser['ItemUser']['id'], 'type' => 'booking_timeout'));
					}
				}else{
					// todo: 
					$max_block_min = Configure::read('seat.maximum_seat_blocking_time');
					$cur_time = strtotime(date('Y-m-d H:i:s'));
					$blocking_time = date('Y-m-d H:i:s', strtotime('+'.$max_block_min.' minutes', $cur_time));
					$this->Session->write('SeatBlockTime', strtotime($blocking_time));
				}	
			}
			$this->set(compact('reserved_titcket', 'partition', 'reserved_titcket', 'selected_seats', 'seat_map', 'available_arr', 'unavailable_arr', 'booked_arr', 'noseat_arr', 'row_name', 'itemUser', 'selected_arr', 'booking_arr', 'blocked_arr', 'url', 'total'));
		}
		if ($this->RequestHandler->prefers('json')) {
            Cms::dispatchEvent('Controller.CustomPricePerTypesSeats.SeatSelection', $this, array());
        }
	}
	public function admin_edit($id = null, $custom_type_id = null) {
		$this->setAction('edit', $id, $custom_type_id);
	}
	public function admin_preview($id= null, $slug = null) {
		$this->setAction('preview', $id, $slug);
	}
}
?>