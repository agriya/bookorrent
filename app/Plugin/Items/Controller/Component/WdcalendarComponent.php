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
class WdcalendarComponent extends Component
{
    var $controller;
    function updateDetailedCalendar($id, $item_id, $st, $et, $price, $price1, $price2, $price3, $status, $desc, $model, $color, $timezone,  $custom_source_id)
    {		
        App::import('Model', 'Items.Item');
        $this->Item = new Item();
        App::import('Model', 'Items.ItemUser');
        $this->ItemUser = new ItemUser();
        		
		
		if($model == 'ItemUser') {	
			$itemUserCount = $this->ItemUser->find('count', array(
				'conditions' => array(
					'ItemUser.item_id' => $item_id,
					'ItemUser.id' => $id,				  
					'ItemUser.item_user_status_id' => ConstItemUserStatus::WaitingforAcceptance
				) ,
				'recursive' => -1
			));
			if (empty($itemUserCount)) {
				$ret['IsSuccess'] = false;
				$ret['Msg'] = __l('Item already booked.So price could not be updated');
			} else {
				$data[$model]['item_user_status_id'] = $status;
				$this->ItemUser->updateStatus($id, $status);
			}
		}
		if($model == 'CustomPricePerNight') {				
			$customPrice = $this->Item->CustomPricePerNight->find('first', array(
				'conditions' => array(
					'CustomPricePerNight.item_id' => $item_id,
					'CustomPricePerNight.id' => $id	
				) ,
				'recursive' => -1
			));			
			$itemUserCount = $this->ItemUser->find('count', array(
				'conditions' => array(
					'ItemUser.item_id' => $customPrice['CustomPricePerNight']['item_id'],
					'ItemUser.item_user_status_id' => array (
						ConstItemUserStatus::Confirmed,
						ConstItemUserStatus::WaitingforReview,
						ConstItemUserStatus::Completed,
					),
				) ,
				'recursive' => -1
			));				
			if (!empty($itemUserCount)) {
				$ret['IsSuccess'] = false;
				$ret['Msg'] = __l('Item already booked.So price could not be updated');
			} else {
				$data[$model]['id'] = $id;				
				$data[$model]['price_per_hour'] = $price;
				$data[$model]['price_per_day'] = $price1;
				$data[$model]['price_per_week'] = $price2;
				$data[$model]['price_per_month'] = $price3;
                if (!$this->Item->CustomPricePerNight->save($data)) {
                    return $this->Item->CustomPricePerNight->validationErrors;
                }
				$this->Item->update_minimum_price($item_id);
                $ret['IsSuccess'] = true;
                $ret['Msg'] = __l('Updated successfully');
			}
		}
        return $ret;
    }
    function listCalendar($sd, $ed, $type = 'host', $view = 'month', $id = null, $viewObj = ' ')
    {
        App::import('Model', 'Items.Item');
        $this->Item = new Item();
		$html = $this->Item->getHtmlObj($viewObj);
        $ret = array();
        $ret['events'] = array();
        $ret['weeks'] = array();
        $ret['item'] = array();
        $ret['monthly'] = array();
        $ret['issort'] = true;
        $ret['start'] = $this->php2JsTime($sd);
        $ret['end'] = $this->php2JsTime($ed);
        $ret['currency_symbol'] = $GLOBALS['currencies'][Configure::read('site.currency_id') ]['Currency']['symbol'];
        $ret['error'] = null;
        try {
            $start = date('Y-m-d', $sd);
            $end = date('Y-m-d', $ed);
            $smonth = date('m', $sd);
            if ($type == 'host') {
                if ($view == 'week') {
                    $week_start = $start;
                    $wtime = explode('-', $start);
                    $smonth = date('m', mktime(0, 0, 0, $wtime[1], 1, $wtime[0]));
                    $start = date('Y-m-d', mktime(0, 0, 0, $wtime[1], 1, $wtime[0]));
                    $month_start = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2], $wtime[0]));
                } elseif ($view == 'day') {
                    $day_start = $start;
                    $dtime = explode('-', $start);
                    $smonth = date('m', mktime(0, 0, 0, $dtime[1], 1, $dtime[0]));
                    $start = date('Y-m-d', mktime(0, 0, 0, $dtime[1], 1, $dtime[0]));
                    $month_start = date('Y-m-d', mktime(0, 0, 0, $dtime[1], $dtime[2], $dtime[0]));
                } else {
                    //get time stamp
                    $indate = $start;
                    list($year, $month, $day) = explode('-', $indate);
                    $timestamp = mktime(0, 0, 0, $month, $day, $year);
                    if (Configure::read('item.weekstartson') == 'Monday') {
                        if (date('w', strtotime($start)) == 0) {
                            // quick fix for sunday
                            $cday = 6;
                        } else {
                            $cday = date('w', strtotime($start)) -1;
                        }
                    } elseif (Configure::read('item.weekstartson') == 'Saturday') {
                        $cday = (date('w', strtotime($start)) +1) %7;
                    } else {
                        $cday = date('w', strtotime($start));
                    }
                    $time = explode('-', $start);
                    //calendar start the of the week
                    $start = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]-$cday, $time[0]));
                    $month_start = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2], $time[0]));
                }
            }
            $emonth = date('m', $ed);
            $conditions = array();
            $conditions['AND']['ItemUser.item_user_status_id'] = array(
                ConstItemUserStatus::BookingRequest,
                ConstItemUserStatus::WaitingforAcceptance,
                ConstItemUserStatus::Confirmed,
                ConstItemUserStatus::PaymentPending,
            );
            if (!empty($id)) {
                $item_conditions['Item.id'] = explode(',', $id);
            } else {
               // $view = 'full';
                $item_conditions['Item.user_id'] = !empty($_SESSION['Auth']['User']['id']) ? $_SESSION['Auth']['User']['id'] : '';
            }
            $phpTime = strtotime($end);
            $new_end = date('Y-m-d', strtotime('next saturday', mktime(0, 0, -1, date('m', $phpTime) +1, 1, date('Y', $phpTime))));
            $items = $this->Item->find('all', array(
                'conditions' => $item_conditions,
                'fields' => array(
                    'Item.id',
                    'Item.title',
                    'Item.price_per_hour',
                    'Item.price_per_day',
                    'Item.price_per_week',
                    'Item.price_per_month',
                    'Item.is_sell_ticket',
					'Item.is_user_can_request',
                    'Item.is_people_can_book_my_time',
                    'Item.minimum_price',
                    'Item.is_have_definite_time',
                ) ,
                'contain' => array(
                    'ItemUser' => array(
                        'conditions' => array(
                            $conditions,
                            'ItemUser.from <=' => $new_end,
                            'ItemUser.to >=' => $start,
                        ) ,
						'CustomPricePerNight',
						'CustomPricePerTypeItemUser'=> array(
							'fields' => array(
								'CustomPricePerTypeItemUser.id',
								'CustomPricePerTypeItemUser.custom_price_per_night_id',
							) ,
						) ,
                        'User' => array(
                            'fields' => array(
                                'User.id',
                                'User.username',
                            ) ,
                        ) ,
                    ) ,
					'CustomPricePerNight' => array(						
						'order' => 'CustomPricePerNight.id ASC'
					),
                ) ,
                'recursive' => 3,
            ));
            if (!empty($items)) {
                foreach($items as $key => $item) {
                    $custom_price_night_conditions = array();
					$custom_price_night_conditions['CustomPricePerNight.item_id'] = $item['Item']['id'];
                    if ($item['Item']['is_people_can_book_my_time']) {
						$custom_price_night_conditions['OR'] = array(
																	array(
																		'CustomPricePerNight.start_date <=' => $new_end,
																		'CustomPricePerNight.end_date >=' => $start
																	),
																	array(
																		'CustomPricePerNight.start_date <=' => $new_end,
																		'CustomPricePerNight.end_date' => NULL
																	)
																);
                        //$custom_price_night_conditions['CustomPricePerNight.is_custom ='] = 1;                   
                        $custom_price_night_conditions['CustomPricePerNight.parent_id !='] = 0;                   
                    } elseif ($item['Item']['is_sell_ticket']) {
                    //    $custom_price_night_conditions['CustomPricePerNight.parent_id'] = 0;
                    }
                    $custom_price_per_nights = $this->Item->CustomPricePerNight->find('all', array(
                        'conditions' => $custom_price_night_conditions,
                        'contain' => array(
                            'CustomPricePerType'
                        ) ,
						'order' => array(
							'CustomPricePerNight.id ASC'
						),
						'recursive' => 1,
                    ));
                    $item['CustomPricePerNight'] = array();
                    foreach($custom_price_per_nights As $ckey => $custom_price_per_night) {
                        $item['CustomPricePerNight'][$ckey] = $custom_price_per_night['CustomPricePerNight'];
                        $item['CustomPricePerNight'][$ckey]['CustomPricePerType'] = $custom_price_per_night['CustomPricePerType'];
                    }
                    $pro_list_no = !empty($_SESSION['Item_Calender'][$item['Item']['id']]) ? 'I' . $_SESSION['Item_Calender'][$item['Item']['id']] . ': ' : '';
					for($cpn = 0; $cpn < count($item['CustomPricePerNight']); $cpn++) {
						$item_custom_types = array();
						if(!empty($item['CustomPricePerNight'][$cpn]['CustomPricePerType'])) {
							$i = 0;
							foreach($item['CustomPricePerNight'][$cpn]['CustomPricePerType'] As $custom_types) {
								$item_custom_types[$i] = array(
									'id' => $custom_types['id'],
									'name' => $custom_types['name'],
									'price' => $custom_types['price']
								);
								$i++;
							}
						}
						$temp_title = $pro_list_no . "P". ($cpn+1) . ' ' . $html->cText($item['Item']['title'],false) . ' ' . $html->cText($item['CustomPricePerNight'][$cpn]['name'],false);
						$ret['item'][] = array(
							'id' => $item['Item']['id'],
							'title' => $temp_title,
							'price_per_hour' => $item['CustomPricePerNight'][$cpn]['price_per_hour'],
							'price_per_day' => $item['CustomPricePerNight'][$cpn]['price_per_day'],
							'price_per_week' => $item['CustomPricePerNight'][$cpn]['price_per_week'],
							'price_per_month' => $item['CustomPricePerNight'][$cpn]['price_per_month'],
							'minimum_price' => $item['Item']['minimum_price'],
							'is_people_can_book_my_time' => $item['Item']['is_people_can_book_my_time'],
							'is_sell_ticket' => $item['Item']['is_sell_ticket'],
							'custom_types' => $item_custom_types,
							'is_have_definite_time' => $item['Item']['is_have_definite_time'],
							'CustomPricePerNight' => $item['CustomPricePerNight'][$cpn]
						);
					}
					$reserved = $not_available = $weekbooked = $boundary = $booked = $booked_status_data = $weekly = $event_available = array();
					
					if ($item['Item']['is_sell_ticket'] ) {
						if (!empty($item['ItemUser'])) {							
                            foreach($item['ItemUser'] as $itemUser) {							
                                if ((!empty($itemUser['user_id']) && $itemUser['item_user_status_id'] != ConstItemUserStatus::PaymentPending) || ($itemUser['item_user_status_id'] == ConstItemUserStatus::BookingRequest && !empty($itemUser['is_booking_request']))) {
									for($cpn = 0; $cpn < count($item['CustomPricePerNight']); $cpn++) {
										if($item['CustomPricePerNight'][$cpn]['id'] == $itemUser['custom_price_per_night_id']){
											$p_list_no = !empty($_SESSION['Item_Calender'][$itemUser['item_id']]) ? 'I' . $_SESSION['Item_Calender'][$itemUser['item_id']] . ': P'. ($cpn+1). ':' : '';
										}
									}
                                    $color = '#4CB050';
                                    $time = explode('-', $itemUser['from']);
                                    $time1 = explode('-', getToDate($itemUser['to']));
                                    if ($type == 'guest') {
                                        $start_split = explode('-', $start);
                                        $end_split = explode('-', $end);
                                        $startlimit = date('m', mktime(0, 0, 0, $start_split[1], $start_split[2], $start_split[0]));
                                        $endlimit = date('m', mktime(0, 0, 0, $end_split[1], $end_split[2], $end_split[0]));
                                        $from_limit = date('m', mktime(0, 0, 0, $time[1], $time[2], $time[0]));
                                        $to_limit = date('m', mktime(0, 0, 0, $time[1], $time[2], $time[0]));
                                        if ($from_limit < $startlimit) {
                                            $itemUser['from'] = date('Y-m-d', mktime(0, 0, 0, $startlimit, 1, $time[0]));
                                        }
                                    }
                                    $time = explode('-', $itemUser['from']);
                                    $days = getFromToDiff($itemUser['from'], getToDate($itemUser['to']));
                                    for ($j = 0; $j <= $days; $j++) {
                                        $reserved[$itemUser['custom_price_per_night_id']][] = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
                                        $booked[$itemUser['custom_price_per_night_id']][] = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
                                        $te_current_date = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
                                        switch ($itemUser['item_user_status_id']) {
                                            case 1:
                                                $booked_status_data[$itemUser['custom_price_per_night_id']][$te_current_date] = 'WaitingforAcceptance';
                                                break;

                                            case 2:
                                                $booked_status_data[$itemUser['custom_price_per_night_id']][$te_current_date] = 'Confirmed';
                                                break;
                                        }
                                    }
                                    $username = '';
                                    if ($itemUser['item_user_status_id'] == ConstItemUserStatus::Confirmed) {
                                        $color = '#8C65D8';
                                        $username = (!empty($itemUser['User']['username'])) ? __l('Booked by').' ' . ucfirst($itemUser['User']['username']) : __l('Booked').', ';
                                        $username.= " ".__l('at price').' ' . $itemUser['original_price'];
                                    } else if ($itemUser['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance) {
                                        $color = '#F1A640';
                                        $username = (!empty($itemUser['User']['username'])) ? __l('Book request by').' ' . ucfirst($itemUser['User']['username']) : __l('Book request').', ';
                                        $username.= ' '.__l('at price').' ' . $itemUser['original_price'];
                                    } else if ($itemUser['item_user_status_id'] == ConstItemUserStatus::BookingRequest) {
                                        $color = '#999999';
                                        $username = (!empty($itemUser['User']['username'])) ? __l('Booking request from').' ' . ucfirst($itemUser['User']['username']) : __l('Booking request');
                                        $itemUser['item_user_status_id'] = ConstItemUserStatus::BookingRequest;
                                    }
									if(empty($itemUser['to'])) {
										$from_to_date = $this->php2JsTime($this->mySql2PhpTime($itemUser['from']))."_".$this->mySql2PhpTime($itemUser['to']);
									} else {
										$from_to_date = $this->php2JsTime($this->mySql2PhpTime($itemUser['from']))."_".$this->php2JsTime($this->mySql2PhpTime($itemUser['to']));
									}

                                    if (strtotime($itemUser['from']) < time()) {										
                                        $itemUser['from'] = date('Y-m-d');
                                    }
									$availabity_custom_types['custom_types'] = array();									
                                    $ret['events'][] = array(
                                        $itemUser['id'],
                                        $username,
                                        $this->php2JsTime($this->mySql2PhpTime($itemUser['from'])) ,
                                        $this->php2JsTime($this->mySql2PhpTime($itemUser['to'])) ,
                                        1,
                                        0,
                                        0,
                                        $color,
                                        1,
                                        $itemUser['item_user_status_id'],
                                        $itemUser['item_id'],
                                        'booked',
                                        'ItemUser',
                                        $itemUser['original_price'],
                                        $p_list_no,
                                        $html->cText($item['Item']['title'],false),
										$item['Item']['is_sell_ticket'],
										json_encode($availabity_custom_types),
										'',
										$from_to_date
                                    );
                                }
                            }
                        }
                        $availabilities = $this->checkCustomNightAvailability($start, $end, $item['Item']['id']);
						$count = 0;
						foreach($availabilities As $cppn => $availability) {
							if($availability['CustomPricePerNight']['parent_id'] == 0) {
								$p_list_no = !empty($_SESSION['Item_Calender'][$availability['CustomPricePerNight']['item_id']]) ? 'I' . $_SESSION['Item_Calender'][$availability['CustomPricePerNight']['item_id']] . ': P'. ($count+1). ':' : '';
								$count++;
							}
							$time = explode('-', $availability['CustomPricePerNight']['start_date']);
							$time1 = explode('-', $availability['CustomPricePerNight']['end_date']);
							$days = (strtotime($availability['CustomPricePerNight']['end_date']) - strtotime($availability['CustomPricePerNight']['start_date'])) /(60*60*24);
							for ($j = 0; $j <= $days; $j++) {
								if(!empty($availability['CustomPricePerNight']['id'])) {
									$reserved[$availability['CustomPricePerNight']['id']][] = date('Y-m-d', mktime(0, 0, 0, $time[1],
								$time[2]+$j, $time[0]));
								}
								if ($availability['CustomPricePerNight']['is_available'] == 0) {
									$not_available[$availability['CustomPricePerNight']['id']][] = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
								}
							}
							if ($availability['CustomPricePerNight']['is_available'] == 1) {
								$color = '#4CB050';
								$caption = $availability['CustomPricePerNight']['minimum_price'];
							} else {
								$color = '#D96566';
								$caption = __l('Not Available');
							}
							
							$update_id = !empty($availability['CustomPricePerNight']['id']) ? $availability['CustomPricePerNight']['id'] : 0;
							$parent_id = !empty($availability['CustomPricePerNight']['parent_id']) ? $availability['CustomPricePerNight']['parent_id'] : 0;
							$status = 0;
							if ($availability['CustomPricePerNight']['is_available'] == 1) {
								$status = 99;
							}
							$availabity_custom_types['custom_types'] = array();
							if (!empty($availability['CustomPricePerType'])) {								
								$i = 0;
								foreach($availability['CustomPricePerType'] As $custom_types) {
									$availabity_custom_types['custom_types'][$i] = array(
										'id' => $custom_types['id'],
										'name' => $custom_types['name'],
										'price' => $custom_types['price'],
										'start_time'=> $custom_types['start_time'],
										'end_time'=> $custom_types['end_time']
									);
									$avail_start_time = $availability['CustomPricePerType'][$i]['start_time'];
									$avail_end_time = $availability['CustomPricePerType'][$i]['end_time'];
									$i++;
								}
							}
							/*if(!empty($availability['CustomPricePerType'][$cppn])) {
								$avail_start_time = $availability['CustomPricePerType'][$cppn]['start_time'];
								$avail_end_time = $availability['CustomPricePerType'][$cppn]['end_time'];
							}*/
							$from_to_date = $this->php2JsTime($this->mySql2PhpTime($availability['CustomPricePerNight']['start_date']))."_".$this->php2JsTime($this->mySql2PhpTime($availability['CustomPricePerNight']['end_date']));
							if($view == 'month') {
								$ret['events'][] = array(
									$update_id,
									$caption,
									$this->php2JsTime($this->mySql2PhpTime($availability['CustomPricePerNight']['start_date'] . ' ' . $avail_start_time)) ,
									$this->php2JsTime($this->mySql2PhpTime($availability['CustomPricePerNight']['end_date'] . ' ' . $avail_end_time)) ,
									1,
									0,
									0,
									$color,
									1,
									$status,
									$item['Item']['id'],
									'available',
									'CustomPricePerNight',
									$availability['CustomPricePerNight']['minimum_price'],
									$p_list_no,
									$html->cText($item['Item']['title'],false),
									$item['Item']['is_sell_ticket'],
									json_encode($availabity_custom_types),
									'',
									$from_to_date,
									$parent_id
								);
							}
						}
						
                    } elseif ($item['Item']['is_people_can_book_my_time']) {
                        // Waiting for acceptance & booking request information [case1]
                        if (!empty($item['ItemUser'])) {
                            foreach($item['ItemUser'] as $itemUser) {
                                if ((!empty($itemUser['user_id']) && $itemUser['item_user_status_id'] != ConstItemUserStatus::PaymentPending) || ($itemUser['item_user_status_id'] == ConstItemUserStatus::BookingRequest && !empty($itemUser['is_booking_request']))) {
									foreach($itemUser['CustomPricePerTypeItemUser'] as $typeItemUser){ 
										$color = '#4CB050';
										$time = explode('-', $itemUser['from']);
										$time1 = explode('-', getToDate($itemUser['to']));
										if ($type == 'guest') {
											$start_split = explode('-', $start);
											$end_split = explode('-', $end);
											$startlimit = date('m', mktime(0, 0, 0, $start_split[1], $start_split[2], $start_split[0]));
											$endlimit = date('m', mktime(0, 0, 0, $end_split[1], $end_split[2], $end_split[0]));
											$from_limit = date('m', mktime(0, 0, 0, $time[1], $time[2], $time[0]));
											$to_limit = date('m', mktime(0, 0, 0, $time[1], $time[2], $time[0]));
											if ($from_limit < $startlimit) {
												$itemUser['from'] = date('Y-m-d', mktime(0, 0, 0, $startlimit, 1, $time[0]));
											}
										}
										$time = explode('-', $itemUser['from']);
										$days = getFromToDiff($itemUser['from'], getToDate($itemUser['to']));
										for ($j = 0; $j <= $days; $j++) {
											$reserved[$typeItemUser['custom_price_per_night_id']][] = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
											$booked[$typeItemUser['custom_price_per_night_id']][] = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
											$te_current_date = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
											switch ($itemUser['item_user_status_id']) {
												case 1:
														$booked_status_data[$typeItemUser['custom_price_per_night_id']][$te_current_date] = 'WaitingforAcceptance';
														break;

												case 2:
														$booked_status_data[$typeItemUser['custom_price_per_night_id']][$te_current_date] = 'Confirmed';
														break;
											}
										}
										$username = '';
										if ($itemUser['item_user_status_id'] == ConstItemUserStatus::Confirmed) {
											$color = '#8C65D8';
											$username = (!empty($itemUser['User']['username'])) ? __l('Booked by').' ' . ucfirst($itemUser['User']['username']) : __l('Booked').', ';
											$username.= ' '.__l('at price').' ' . $itemUser['original_price'];
										} else if ($itemUser['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance) {
											$color = '#F1A640';
											$username = (!empty($itemUser['User']['username'])) ? __l('Book request by').' ' . ucfirst($itemUser['User']['username']) : __l('Book request').', ';
											$username.= ' '.__l('at price').' ' . $itemUser['original_price'];
										} else if ($itemUser['item_user_status_id'] == ConstItemUserStatus::BookingRequest) {
											$color = '#999999';
											$username = (!empty($itemUser['User']['username'])) ? __l('Booking request from').' ' . ucfirst($itemUser['User']['username']) : __l('Booking request');
											$itemUser['item_user_status_id'] = ConstItemUserStatus::BookingRequest;
										}
										if(empty($itemUser['to'])) {
											$from_to_date = $this->php2JsTime($this->mySql2PhpTime($itemUser['from']))."_".$itemUser['to'];
										} else {
											$from_to_date = $this->php2JsTime($this->mySql2PhpTime($itemUser['from']))."_".$this->php2JsTime($this->mySql2PhpTime($itemUser['to']));
										}										
										if (strtotime($itemUser['from']) < time()) {
											$itemUser['from'] = date('Y-m-d');
										}
										foreach($item['CustomPricePerNight'] as $cppn => $customPricePerNight) {
											$p_list_no = !empty($_SESSION['Item_Calender'][$customPricePerNight['item_id']]) ? 'I' . $_SESSION['Item_Calender'][$customPricePerNight['item_id']] . ': P'. ($cppn+1). ':' : '';
											$availabity_custom_types['custom_types'] = array();	
											if($typeItemUser['custom_price_per_night_id'] == $customPricePerNight['id']){
												$ret['events'][] = array(
													$itemUser['id'],
													$username,
													$this->php2JsTime($this->mySql2PhpTime($itemUser['from'])) ,
													$this->php2JsTime($this->mySql2PhpTime($itemUser['to'])) ,
													1,
													0,
													0,
													$color,
													1,
													$itemUser['item_user_status_id'],
													$itemUser['item_id'],
													'booked',
													'ItemUser',
													$itemUser['original_price'],
													$p_list_no,
													$html->cText($item['Item']['title'],false),
													$item['Item']['is_sell_ticket'],
													json_encode($availabity_custom_types),
													'',
													$from_to_date
												);
											}
										}
									} 
                                }
                            }
                        }
                        // custom price [case2]
                        $caption = '';
                        if (!empty($item['CustomPricePerNight'])) {
							foreach($item['CustomPricePerNight'] as $cppn => $customPricePerNight) {
								$p_list_no = !empty($_SESSION['Item_Calender'][$customPricePerNight['item_id']]) ? 'I' . $_SESSION['Item_Calender'][$customPricePerNight['item_id']] . ': P'. ($cppn+1). ':' : '';
								$time = explode('-', $customPricePerNight['start_date']);
								$cus_end_date = $customPricePerNight['end_date'];
								$time1 = explode('-', $customPricePerNight['end_date']);
								// End date not given in items
								if(empty($customPricePerNight['end_date'])){
									$time1 = explode('-', $new_end); 
									$cus_end_date = $new_end;
								}
								$days = (strtotime($cus_end_date) -strtotime($customPricePerNight['start_date'])) /(60*60*24);
								for ($j = 0; $j <= $days; $j++) {
									$reserved[$customPricePerNight['id']][] = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
									if ($customPricePerNight['is_available'] == 0) {
										$not_available[$customPricePerNight['id']][] = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
									}
								}
								if ($customPricePerNight['is_available'] == 1) {
									$color = '#4CB050';
									$caption = $customPricePerNight['price_per_day'];
								} else {
									$color = '#D96566';
									$caption = __l('Not available');
								}
								$status = $customPricePerNight['is_available'];
								if ($customPricePerNight['is_available'] == 1) {
									$status = 99;
								}
								if(empty($customPricePerNight['end_date'])) {
									$from_to_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerNight['start_date']))."_".$customPricePerNight['end_date'];
								} else {
									$from_to_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerNight['start_date']))."_".$this->php2JsTime($this->mySql2PhpTime($customPricePerNight['end_date']));
								}
								if (strtotime($customPricePerNight['start_date']) < time()) {
									$customPricePerNight['start_date'] = date('Y-m-d');
								} else {
									if ($customPricePerNight['is_available'] == 0) {
										$status = 0; // not available calendar status id only
									}
								}
								$repeat_end_date = $customPricePerNight['repeat_end_date'];
								if(!empty($customPricePerNight['repeat_days'])) {
									$day_of_the_week = array('M' => 1, 'Tu' => 2, 'W' => 3, 'Th' => 4, 'F' => 5, 'Sa' => 6, 'Su' => 7);
									$repeat_days = array();
									$repeat_days_arr = explode(',', $customPricePerNight['repeat_days']);
									foreach($repeat_days_arr as $repeat_day) {
										$repeat_days[] = $day_of_the_week[$repeat_day];
									}
									$current_date = date('Y-m-d');
									$start_date = $customPricePerNight['start_date'];
									$total_days = ceil((strtotime($repeat_end_date) - strtotime($start_date)) /(60*60*24));
									for($i = 0; $i <= $total_days; $i++) {
										$day = date('Y-m-d', strtotime($start_date . "+" . $i . " day"));
										$day_of_day = date('N', strtotime($day));
										if($day >= $current_date) {
											if (in_array($day_of_day, $repeat_days)) {
												$diff_days = ceil((strtotime($customPricePerNight['end_date']) - strtotime($customPricePerNight['start_date'])) /(60*60*24));
												$end_date = date('Y-m-d', strtotime($day . "+" . $diff_days . " day"));;
												$ret['events'][] = array(
													$customPricePerNight['id'],
													$caption,
													$this->php2JsTime($this->mySql2PhpTime($day . ' ' . $customPricePerNight['start_time'])) ,
													$this->php2JsTime($this->mySql2PhpTime($end_date . ' ' . $customPricePerNight['end_time'])) ,
													1,
													0,
													0,
													$color,
													1,
													$status,
													$customPricePerNight['item_id'],
													'available',
													'CustomPricePerNight',
													$customPricePerNight['price_per_day'],
													$p_list_no,
													$item['Item']['title'] .'(' . $customPricePerNight['name']. ')',
													$customPricePerNight['price_per_hour'],
													$customPricePerNight['price_per_week'],
													$customPricePerNight['price_per_month'],
													$from_to_date
												);
											}
										}
									}
								} else {
									$ret['events'][] = array(
										$customPricePerNight['id'],
										$caption,
										$this->php2JsTime($this->mySql2PhpTime($customPricePerNight['start_date'] . ' ' . $customPricePerNight['start_time'])) ,
										$this->php2JsTime($this->mySql2PhpTime($cus_end_date . ' ' . $customPricePerNight['end_time'])) ,
										1,
										0,
										0,
										$color,
										1,
										$status,
										$customPricePerNight['item_id'],
										'available',
										'CustomPricePerNight',
										$customPricePerNight['price_per_day'],
										$p_list_no,
										$item['Item']['title'] .'(' . $customPricePerNight['name']. ')',
										$customPricePerNight['price_per_hour'],
										$customPricePerNight['price_per_week'],
										$customPricePerNight['price_per_month'],
										$from_to_date
									);
								}
						    }
                        }
						$not_avilable_days = $this->checkAvalibities($item['Item']['id'], $start, $end);
						if(!empty($not_avilable_days)) {
							foreach($not_avilable_days As $not_avilable_day) {
								if (strtotime($not_avilable_day) >= time()) {                                     
									foreach($item['CustomPricePerNight'] as $cppn => $customPricePerNight) {
									if(empty($customPricePerNight['end_date'])) {
										$from_to_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerNight['start_date']))."_".$customPricePerNight['end_date'];
									} else {
										$from_to_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerNight['start_date']))."_".$this->php2JsTime($this->mySql2PhpTime($customPricePerNight['end_date']));
									}
									$booked[$customPricePerNight['id']] = (isset($booked[$customPricePerNight['id']])) ? $booked[$customPricePerNight['id']] : array();
										if (!in_array($start, $booked[$customPricePerNight['id']])) {
											$p_list_no = !empty($_SESSION['Item_Calender'][$item['Item']['id']]) ? 'I' . $_SESSION['Item_Calender'][$item['Item']['id']] . ':P'. ($cppn + 1) . ':' : '';
											$not_available[$customPricePerNight['id']][] = $not_avilable_day;
											$reserved[$customPricePerNight['id']][] = $not_avilable_day;
											$ret['events'][] = array(
												'',
												'Not Available',
												$this->php2JsTime($this->mySql2PhpTime($not_avilable_day)) ,
												$this->php2JsTime($this->mySql2PhpTime($not_avilable_day)) ,
												1,
												0,
												0,
												'#D96566',
												1,
												0,
												$item['Item']['id'],
												'available',
												'CustomPricePerNight',
												$customPricePerNight['price_per_day'],
												$p_list_no,
												$html->cText($item['Item']['title'],false) .'(' . $html->cText($customPricePerNight['name'],false). ')',
												$customPricePerNight['price_per_hour'],
												$customPricePerNight['price_per_week'],
												$customPricePerNight['price_per_month'],
												$from_to_date
											);
										}
									}
								}
							}
						}
						
					} elseif($item['Item']['is_user_can_request']){					
						if (!empty($item['ItemUser'])) {
                            foreach($item['ItemUser'] as $itemUser) {
                                if ((!empty($itemUser['user_id'])) && ($itemUser['item_user_status_id'] == ConstItemUserStatus::BookingRequest && !empty($itemUser['is_booking_request']))) {								
									$color = '#4CB050';
									$time = explode('-', $itemUser['from']);
									$time1 = explode('-', getToDate($itemUser['to']));
									if ($type == 'guest') {
										$start_split = explode('-', $start);
										$end_split = explode('-', $end);
										$startlimit = date('m', mktime(0, 0, 0, $start_split[1], $start_split[2], $start_split[0]));
										$endlimit = date('m', mktime(0, 0, 0, $end_split[1], $end_split[2], $end_split[0]));
										$from_limit = date('m', mktime(0, 0, 0, $time[1], $time[2], $time[0]));
										$to_limit = date('m', mktime(0, 0, 0, $time[1], $time[2], $time[0]));
										if ($from_limit < $startlimit) {
											$itemUser['from'] = date('Y-m-d', mktime(0, 0, 0, $startlimit, 1, $time[0]));
										}
									}
									$time = explode('-', $itemUser['from']);
									$days = getFromToDiff($itemUser['from'], getToDate($itemUser['to']));									
									$color = '#999999';
									$username = (!empty($itemUser['User']['username'])) ? __l('Booking request from').' ' . ucfirst($itemUser['User']['username']) : __l('Booking request');
									$itemUser['item_user_status_id'] = ConstItemUserStatus::BookingRequest;									
									if(empty($itemUser['to'])) {
										$from_to_date = $this->php2JsTime($this->mySql2PhpTime($itemUser['from']))."_".$itemUser['to'];
									} else {
										$from_to_date = $this->php2JsTime($this->mySql2PhpTime($itemUser['from']))."_".$this->php2JsTime($this->mySql2PhpTime($itemUser['to']));
									}										
									if (strtotime($itemUser['from']) < time()) {
										$itemUser['from'] = date('Y-m-d');
									}										
									$p_list_no = !empty($_SESSION['Item_Calender'][$itemUser['item_id']]) ? 'I' . $_SESSION['Item_Calender'][$itemUser['item_id']].': ' : '';
									$availabity_custom_types['custom_types'] = array();										
										$ret['events'][] = array(
											$itemUser['id'],
											$username,
											$this->php2JsTime($this->mySql2PhpTime($itemUser['from'])) ,
											$this->php2JsTime($this->mySql2PhpTime($itemUser['to'])) ,
											1,
											0,
											0,
											$color,
											1,
											$itemUser['item_user_status_id'],
											$itemUser['item_id'],
											'request',
											'ItemUser',
											$itemUser['original_price'],
											$p_list_no,
											$html->cText($item['Item']['title'],false),
											$item['Item']['is_sell_ticket'],										
											json_encode($availabity_custom_types),
											'',
											$from_to_date
										);
									
                                }
                            }
                        }
					} 
					// default price generation [case3]
					$time = explode('-', $start);
					$daily_start = $time[2];
					$p_list_no = !empty($_SESSION['Item_Calender'][$item['Item']['id']]) ? 'P' . $_SESSION['Item_Calender'][$item['Item']['id']] . ': ' : '';
										
					/*if ($view == 'day') {
						unset($ret['events']);
						$start = $day_start;
						
						foreach($item['CustomPricePerNight'] as $cppn => $CustomPricePerNight) {
							$p_list_no = !empty($_SESSION['Item_Calender'][$item['Item']['id']]) ? 'I' . $_SESSION['Item_Calender'][$item['Item']['id']] . ':P'. ($cppn + 1) . ':' : '';
							$from_to_date = $this->php2JsTime($this->mySql2PhpTime($CustomPricePerNight['start_date']))."_".$this->php2JsTime($this->mySql2PhpTime($CustomPricePerNight['end_date']));							
							
							//if (!in_array($start, $booked[$CustomPricePerNight['id']])) {
								$this->log("available");
								if (strtotime($start) < time()) {
									$start = date('Y-m-d');
								}
								$ret['events'][] = array(
									'',
									$item['Item']['price_per_day'],
									$this->php2JsTime($this->mySql2PhpTime($start)) ,
									$this->php2JsTime($this->mySql2PhpTime($end)) ,
									1,
									0,
									0,
									'#4CB050',
									1,
									1, //available
									$item['Item']['id'],
									'available',
									'CustomPricePerNight',
									$item['Item']['price_per_day'],
									$p_list_no,
									$html->cText($item['Item']['title'],false),
									$item['Item']['price_per_hour'],
									$item['Item']['price_per_week'],
									$item['Item']['price_per_month'],
									$from_to_date
								);
							//}
							if (in_array($start, $booked[$CustomPricePerNight['id']])) {
								$this->log("booked");
								$username = '';
								if ($itemUser['item_user_status_id'] == ConstItemUserStatus::Confirmed) {
									$color = '#8C65D8';
									$username = (!empty($itemUser['User']['username'])) ? __l('Booked by').' ' . ucfirst($itemUser['User']['username']) : __l('Booked').', ';
									$username.= ' '.__l('at price').' ' . $itemUser['price'];
								} else if ($itemUser['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance) {
									$color = '#F1A640';
									$username = (!empty($itemUser['User']['username'])) ? __l('Book request by').' ' . ucfirst($itemUser['User']['username']) : __l('Book request').', ';
									$username.= ' '.__l('at price').' ' . $itemUser['price'];
								} else if ($itemUser['item_user_status_id'] == ConstItemUserStatus::BookingRequest) {
									$color = '#999999';
									$username = (!empty($itemUser['User']['username'])) ? __l('Booking request from').' ' . ucfirst($itemUser['User']['username']) : __l('Booking request');
									$itemUser['item_user_status_id'] = ConstItemUserStatus::BookingRequest;
								}
								if (strtotime($start) < time()) {
									$start = date('Y-m-d');
								}
								$ret['events'][] = array(
									$CustomPricePerNight['id'],
									$username,
									$this->php2JsTime($this->mySql2PhpTime($start)) ,
									$this->php2JsTime($this->mySql2PhpTime($end)) ,
									1,
									0,
									0,
									$color,
									1,
									'',
									$CustomPricePerNight['item_id'],
									'booked',
									'CustomPricePerNight',
									$CustomPricePerNight['price_per_day'],
									$p_list_no,
									$html->cText($item['Item']['title'],false).'('.$html->cText($customPricePerWeek['name'],false).')',
									$customPricePerWeek['price_per_hour'],
									$customPricePerWeek['price_per_week'],
									$customPricePerWeek['price_per_month'],
									$from_to_date
								);
							}
						}
					} else {*/					
						$days = (strtotime($end) -strtotime($start)) /(60*60*24);
						if ($type == 'guest') {
							$limit = date('t', $sd);
						} else {
							$limit = 34;
							$ms_time = explode('-', $month_start);
							$totl_days_in_month = date("t", mktime(0, 0, 0, $ms_time[1], 1, $ms_time[0]));
							$week_day = date("N", mktime(0, 0, 0, $ms_time[1], 1, $ms_time[0]));
							$totl_weeks_in_month = ceil(($days+$week_day) /7);
							if ($totl_weeks_in_month == 6) {
								$limit = 34+7;
							}
						}
						$event_not_avaliable = array();
						foreach($item['CustomPricePerNight'] as $cppn => $customPricePerNight) {
							$temp_reserved = (!empty($reserved[$customPricePerNight['id']])) ? $reserved[$customPricePerNight['id']] : array();
							for ($i = 0; $i <= $limit; $i++) {
								$current_date = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$i, $time[0]));
								if ($current_date >= date('Y-m-d')) {
									if ($item['Item']['is_sell_ticket']) {	
										if(!in_array($current_date, $temp_reserved)) {
											if (empty($event_bstart_date)) {
												$event_bstart_date = $current_date;
											}
											if ($i == $limit) {
												$event_btime = explode('-', $current_date);
												$event_bend_date = date('Y-m-d', mktime(0, 0, 0, $event_btime[1], $event_btime[2], $event_btime[0]));
												$event_not_avaliable[$customPricePerNight['id']][] = array(
													'start_date' => $event_bstart_date,
													'end_date' => $event_bend_date
												);
												$event_bstart_date = '';
												$event_bend_date = '';
											}
										}
									} elseif ($item['Item']['is_people_can_book_my_time']) {
										if (!in_array($current_date, $temp_reserved)) {
											if (empty($bstart_date)) {
												$bstart_date = $current_date;
											}
											if ($i == $limit) {
												$btime = explode('-', $current_date);
												$bend_date = date('Y-m-d', mktime(0, 0, 0, $btime[1], $btime[2], $btime[0]));
												$boundary[$customPricePerNight['id']][] = array(
													'start_date' => $bstart_date,
													'end_date' => $bend_date
												);
												$bstart_date = '';
												$bend_date = '';
											}
										} else {
											if (!empty($bstart_date)) {
												$btime = explode('-', $current_date);
												$bend_date = date('Y-m-d', mktime(0, 0, 0, $btime[1], $btime[2]-1, $btime[0]));
												$boundary[$customPricePerNight['id']][] = array(
													'start_date' => $bstart_date,
													'end_date' => $bend_date
												);
												$bstart_date = '';
												$bend_date = '';
											}
										}
									}
								}
							}
						}
						
						// default price merging with array
						if ($item['Item']['is_people_can_book_my_time']) {
							foreach($item['CustomPricePerNight'] as $cppn => $customPricePerNight) {
								$p_list_no = !empty($_SESSION['Item_Calender'][$item['Item']['id']]) ? 'I' . $_SESSION['Item_Calender'][$item['Item']['id']] . ':P'. ($cppn + 1) . ':' : '';
								if(!empty($boundary[$customPricePerNight['id']])){
									foreach($boundary[$customPricePerNight['id']] as $bound) {
										// $days = (strtotime($end) -strtotime($start)) /(60*60*24);
										$is_add = true;
										$is_unavailable = false;
										if(strtotime($customPricePerNight['end_date']) < strtotime($bound['start_date'])){
											$is_add = false;
										}
										if(strtotime($customPricePerNight['start_date']) > strtotime($bound['start_date']) && (strtotime($customPricePerNight['start_date']) > strtotime($bound['end_date']))){
											 $is_add = false;
											 $is_unavailable = true;
										}
										if(empty($bound['end_date'])) {
											$from_to_date = $this->php2JsTime($this->mySql2PhpTime($bound['start_date']))."_".$bound['end_date'];
										} else {
											$from_to_date = $this->php2JsTime($this->mySql2PhpTime($bound['start_date']))."_".$this->php2JsTime($this->mySql2PhpTime($bound['end_date']));
										}										
										$default_color = '#4CB050';
										if($is_add){
											$ret['events'][] = array(
												0,
												$customPricePerNight['price_per_day'],
												$this->php2JsTime($this->mySql2PhpTime($bound['start_date'])) ,
												$this->php2JsTime($this->mySql2PhpTime($bound['end_date'])) ,
												1,
												0,
												0,
												$default_color,
												1,
												99, //available status id is 11 temprorarly
												$item['Item']['id'],
												'available',
												'CustomPricePerNight',
												$customPricePerNight['price_per_day'],
												$p_list_no,
												$html->cText($item['Item']['title'],false) . '('. $html->cText($customPricePerNight['name'],false) .')',
												$customPricePerNight['price_per_hour'],
												$customPricePerNight['price_per_week'],
												$customPricePerNight['price_per_month'],
												$from_to_date
											);
										}
										if($is_unavailable){
											$default_color = '#D96566';
											$ret['events'][] = array(
												0,
												$customPricePerNight['price_per_day'],
												$this->php2JsTime($this->mySql2PhpTime($bound['start_date'])) ,
												$this->php2JsTime($this->mySql2PhpTime($bound['end_date'])) ,
												1,
												0,
												0,
												$default_color,
												1,
												99, //available status id is 11 temprorarly
												$item['Item']['id'],
												'Not Available',
												'CustomPricePerNight',
												$customPricePerNight['price_per_day'],
												$p_list_no,
												$html->cText($item['Item']['title'],false). '('. $html->cText($customPricePerNight['name'],false) .')',
												$customPricePerNight['price_per_hour'],
												$customPricePerNight['price_per_week'],
												$customPricePerNight['price_per_month'],
												$from_to_date
											);											
										}
									}
								}
							}
						}
						
						//past date
						foreach($item['CustomPricePerNight'] as $cppn => $customPricePerNight) {
							$p_list_no = !empty($_SESSION['Item_Calender'][$item['Item']['id']]) ? 'I' . $_SESSION['Item_Calender'][$item['Item']['id']] . ':P'. ($cppn + 1) . ':' : '';
							$temp_reserved = (!empty($reserved[$customPricePerNight['id']])) ? $reserved[$customPricePerNight['id']] : array();
							if(empty($customPricePerNight['end_date'])) {
								$from_to_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerNight['start_date']))."_".$customPricePerNight['end_date'];
							} else {
								$from_to_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerNight['start_date']))."_".$this->php2JsTime($this->mySql2PhpTime($customPricePerNight['end_date']));
							}
							for ($i = 0; $i <= $limit; $i++) {
								$current_date = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$i, $time[0]));
								if ($current_date < date('Y-m-d')) {
									if ($item['Item']['is_sell_ticket']) {
										if(in_array($current_date, $temp_reserved)) {
											continue;
										}
									}
									$default_color = '#D96566';
									$ret['events'][] = array(
										0,
										$customPricePerNight['price_per_day'],
										$this->php2JsTime($this->mySql2PhpTime($current_date)) ,
										$this->php2JsTime($this->mySql2PhpTime($current_date)) ,
										1,
										0,
										0,
										$default_color,
										1,
										99, //available status id is 11 temprorarly
										$item['Item']['id'],
										'Not Available',
										'CustomPricePerNight',
										$customPricePerNight['price_per_day'],
										$p_list_no,
										$html->cText($item['Item']['title'],false). '('. $html->cText($customPricePerNight['name'],false) .')',
										$customPricePerNight['price_per_hour'],
										$customPricePerNight['price_per_week'],
										$customPricePerNight['price_per_month'],
										$from_to_date
									);
								}
							}
						}
					//}
					if ($item['Item']['is_people_can_book_my_time']) {
						$weekly_data = array();
						$customPricePerWeek_data = array();
						// weekly array generation
						foreach($item['CustomPricePerNight'] as $customPricePerWeek) {
							$time = explode('-', $customPricePerWeek['start_date']);
							$weekly_data[$customPricePerWeek['start_date']]['price_per_day'] = $customPricePerWeek['price_per_day'];
							$weekly_data[$customPricePerWeek['start_date']]['price_per_hour'] = $customPricePerWeek['price_per_hour'];
							$weekly_data[$customPricePerWeek['start_date']]['price_per_week'] = $customPricePerWeek['price_per_week'];
							$weekly_data[$customPricePerWeek['start_date']]['price_per_month'] = $customPricePerWeek['price_per_month'];
							$weekly_data[$customPricePerWeek['start_date']]['id'] = $customPricePerWeek['id'];
							if(empty($customPricePerWeek['end_date'])){
								$customPricePerWeek['end_date'] =  date('Y-m-d', strtotime(date("Y-m-d", strtotime($end)) . "saturday"));
							}
							$days = (strtotime($customPricePerWeek['end_date']) -strtotime($customPricePerWeek['start_date'])) /(60*60*24);
							for ($j = 0; $j <= $days; $j++) {
								$weekly[$customPricePerWeek['id']][] = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2]+$j, $time[0]));
							}
						}
						//get time stamp
						$indate = $start;
						list($year, $month, $day) = explode('-', $indate);
						$timestamp = mktime(0, 0, 0, $month, $day, $year);
						$time = explode('-', $start);
						$wstart = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2], $time[0]));
						/*if ($view == 'week') {
							$wstart = $week_start;
							//unset the whole array
							unset($ret['events']);
							$wtime = explode('-', $wstart);
							$wend = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+6, $wtime[0]));
							if (empty($item['Item']['price_per_week'])) {
								$default_weekly_price = $item['Item']['price_per_day']*7;
							} else {
								$default_weekly_price = $item['Item']['price_per_week'];
							}
							$booked_status = 0;
							// weekly special price is there
							if (in_array($wstart, $weekly[$customPricePerWeek['id']]) &in_array($wend, $weekly[$customPricePerWeek['id']])) {
								$wtime = explode('-', $wstart);
								for ($k = 0; $k <= 6; $k++) {
									$wcurrent = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+$k, $wtime[0]));
									if (in_array($wcurrent, $booked)) {
										$booked_status = 1;
									}
								}
								if ($booked_status == 1) {
									$from_to_date = $this->php2JsTime($this->mySql2PhpTime($wstart))."_".$this->php2JsTime($this->mySql2PhpTime($wend));
									if (strtotime($wstart) < time()) {
										$wstart = date('Y-m-d');
									}
									$ret['events'][] = array(
										'',
										'Booked',
										$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
										$this->php2JsTime($this->mySql2PhpTime($wend)) ,
										1,
										0,
										0,
										'#F8B7A0',
										1,
										'',
										'',
										'booked',
										'CustomPricePerNight',
										$default_weekly_price,
										$p_list_no,
										$html->cText($item['Item']['title'],false),
										$item['Item']['price_per_hour'],
										$item['Item']['price_per_day'],
										$item['Item']['price_per_month'],
										$from_to_date
									);
								} else {
									$from_to_date = $this->php2JsTime($this->mySql2PhpTime($wstart))."_".$this->php2JsTime($this->mySql2PhpTime($wend));
									if (strtotime($wstart) < time()) {
										$wstart = date('Y-m-d');
									}
									$ret['events'][] = array(
										$weekly_data[$wstart]['id'],
										$weekly_data[$wstart]['price_per_week'],
										$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
										$this->php2JsTime($this->mySql2PhpTime($wend)) ,
										1,
										0,
										0,
										'#4CB050',
										1,
										'',
										'',
										'Available',
										'CustomPricePerNight',
										$weekly_data[$wstart]['price_per_week'],
										$p_list_no,
										'',
										$weekly_data[$wstart]['price_per_hour'],
										$weekly_data[$wstart]['price_per_day'],
										$weekly_data[$wstart]['price_per_month'],
										$from_to_date
									);
								}
							} else {
								foreach($item['CustomPricePerNight'] as $cppn => $customPricePerWeek) {								
									$p_list_no = !empty($_SESSION['Item_Calender'][$item['Item']['id']]) ? 'I' . $_SESSION['Item_Calender'][$item['Item']['id']] . ':P'. ($cppn + 1) . ':' : '';
									$wtime = explode('-', $wstart);
									for ($k = 0; $k <= 6; $k++) {
										$wcurrent = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+$k, $wtime[0]));
										if (in_array($wcurrent, $booked)) {
											$booked_status = 1;
										}
									}
									$from_to_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerWeek['start_date']))."_".$this->php2JsTime($this->mySql2PhpTime($customPricePerWeek['end_date']));
									if ($booked_status == 1) {
										if (strtotime($wstart) < time()) {
											$wstart = date('Y-m-d');
										}
										$ret['events'][] = array(
											$customPricePerWeek['id'],
											$customPricePerWeek['price_per_day'],
											$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
											$this->php2JsTime($this->mySql2PhpTime($wend)) ,
											1,
											0,
											0,
											'#F8B7A0',
											1,
											'',
											$customPricePerWeek['item_id'],
											'booked',
											'CustomPricePerNight',
											$customPricePerWeek['price_per_day'],
											$p_list_no,
											$html->cText($item['Item']['title'],false) .'(' . $html->cText($customPricePerWeek['name'],false). ')',
											$customPricePerWeek['price_per_hour'],
											$customPricePerWeek['price_per_week'],
											$customPricePerWeek['price_per_month'],
											$from_to_date
										);
									} else {
										if (strtotime($wstart) < time()) {
											$wstart = date('Y-m-d');
										}
										$ret['events'][] = array(
											$customPricePerWeek['id'],
											$customPricePerWeek['price_per_day'],
											$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
											$this->php2JsTime($this->mySql2PhpTime($wend)) ,
											1,
											0,
											0,
											'#4CB050',
											1,
											'',
											$customPricePerWeek['item_id'],
											'Available',
											'CustomPricePerNight',
											$customPricePerWeek['price_per_day'],
											$p_list_no,
											$html->cText($item['Item']['title'],false) .'(' . $html->cText($customPricePerWeek['name'],false). ')',
											$customPricePerWeek['price_per_hour'],										
											$customPricePerWeek['price_per_week'],										
											$customPricePerWeek['price_per_month'],
											$from_to_date
										);
									}
								}
								$wstart = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+7, $wtime[0]));
							}
						} else {*/
							
							foreach($item['CustomPricePerNight'] as $cppn => $customPricePerWeek) {
								if(empty($customPricePerWeek['end_date'])) {
									$from_to_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerWeek['start_date']))."_".$customPricePerWeek['end_date'];
								} else {
									$from_to_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerWeek['start_date']))."_".$this->php2JsTime($this->mySql2PhpTime($customPricePerWeek['end_date']));
								}
								if(empty($customPricePerWeek['end_date'])){
									$customPricePerWeek['end_date'] =  date('Y-m-d', strtotime(date("Y-m-d", strtotime($end)) . "saturday"));
								}
								$p_list_no = !empty($_SESSION['Item_Calender'][$item['Item']['id']]) ? 'I' . $_SESSION['Item_Calender'][$item['Item']['id']] . ':P'. ($cppn + 1) . ':' : '';
								$not_avail = (isset($not_available[$customPricePerWeek['id']])) ? $not_available[$customPricePerWeek['id']] : array();
								$booked_stat = (isset($booked_status_data[$customPricePerWeek['id']])) ? $booked_status_data[$customPricePerWeek['id']] : array();
								$wstart = date('Y-m-d', mktime(0, 0, 0, $time[1], $time[2], $time[0]));			
								
								//calendar start the of the week
								for ($i = 0; $i <= 5; $i++) {
									$jj = $i+1;
									$wtime = explode('-', $wstart);
									$wend = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+6, $wtime[0]));
									if (empty($customPricePerWeek['price_per_week'])) {
										$default_weekly_price = $customPricePerWeek['price_per_day']*7;
									} else {
										$default_weekly_price = $customPricePerWeek['price_per_week'];
									}
									$booked_status = 0;
									// weekly special price is there
									if (in_array($wstart, $weekly[$customPricePerWeek['id']]) &in_array($wend, $weekly[$customPricePerWeek['id']])) {
										$wtime = explode('-', $wstart);
										$booked_status = 1;
										$count = 0;
										for ($k = 0; $k <= 6; $k++) {
											$wcurrent = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+$k, $wtime[0]));
											if (!in_array($wcurrent, $booked) || (!empty($booked_stat[$wcurrent]) && $booked_stat[$wcurrent] != 'Confirmed')) {
												$booked_status = 0;
												$count++;
											}
										}
										$past = 0;
										for ($k = 0; $k <= 6; $k++) {
											$wcurrent = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+$k, $wtime[0]));
											if (in_array($wcurrent, $not_avail) || (in_array($wcurrent, $booked) && !empty($booked_stat[$wcurrent]) && $booked_stat[$wcurrent] == 'Confirmed' && $count != 0)) {
												$booked_status = 2;
											}
										}
										if ($booked_status == 1) {
											$ret['weeks'][$i][] = array(
												'',
												'Booked',
												$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
												$this->php2JsTime($this->mySql2PhpTime($wend)) ,
												1,
												0,
												0,
												'#8D66D9',
												0,
												99,
												$item['Item']['id'],
												'booked',
												'CustomPricePerNight',
												$customPricePerWeek['price_per_week'],
												$p_list_no,
												$html->cText($item['Item']['title'],false),
												$item['Item']['id'],
												$customPricePerWeek['price_per_hour'],
												$customPricePerWeek['price_per_day'],
												$customPricePerWeek['price_per_month'],
												$from_to_date
												
											);
										} else if ($booked_status == 2) {
											$ret['weeks'][$i][] = array(
												'',
												$customPricePerWeek['price_per_week'],
												$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
												$this->php2JsTime($this->mySql2PhpTime($wend)) ,
												1,
												0,
												0,
												'#D96566',
												0,
												99,
												$item['Item']['id'],
												'Not Available',
												'CustomPricePerNight',
												$customPricePerWeek['price_per_week'],
												$p_list_no,
												$html->cText($item['Item']['title'],false),
												$item['Item']['id'],
												$customPricePerWeek['price_per_hour'],
												$customPricePerWeek['price_per_day'],
												$customPricePerWeek['price_per_month'],
												$from_to_date
											);
										} else {
											$is_Avail = true;
											$is_set_row = true;
											if( strtotime($customPricePerWeek['end_date']) < strtotime($wstart)){
												$is_Avail = false;
												$is_set_row = false;
											}else if(strtotime($customPricePerWeek['end_date']) < strtotime($wend)){
												$is_Avail = false;
											}
											if($is_Avail){	
												$ret['weeks'][$i][] = array(
													$customPricePerWeek['id'],
													$customPricePerWeek['price_per_week'],
													$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
													$this->php2JsTime($this->mySql2PhpTime($wend)) ,
													1,
													0,
													0,
													'#4CB050',
													1,
													99,
													$item['Item']['id'],
													'available',
													'CustomPricePerNight',
													$customPricePerWeek['price_per_week'],
													$p_list_no,
													$html->cText($item['Item']['title'],false) .'(' . $html->cText($customPricePerWeek['name'],false). ')',
													$item['Item']['id'],
													$customPricePerWeek['price_per_hour'],
													$customPricePerWeek['price_per_day'],
													$customPricePerWeek['price_per_month'],
													$from_to_date
												);
											}else{
												if($is_set_row){
													$ret['weeks'][$i][] = array(
														'',
														'Not Available',
														$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
														$this->php2JsTime($this->mySql2PhpTime($wend)) ,
														1,
														0,
														0,
														'#D96566',
														0,
														99,
														$item['Item']['id'],
														'Not Available',
														'CustomPricePerNight',
														$default_weekly_price,
														$p_list_no,
														$html->cText($item['Item']['title'],false) .'(' . $html->cText($customPricePerWeek['name'],false). ')',
														$item['Item']['id'],
														$customPricePerWeek['price_per_hour'],
														$customPricePerWeek['price_per_day'],
														$customPricePerWeek['price_per_month'],
														$from_to_date
													);
												}
											}
										}
									} else {
										$wtime = explode('-', $wstart);
										$booked_status = 1;
										$count = 0;
										$past = 0;
										for ($k = 0; $k <= 6; $k++) {
											$wcurrent = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+$k, $wtime[0]));
											if (!in_array($wcurrent, $booked) || (!empty($booked_stat[$wcurrent]) && $booked_stat[$wcurrent] != 'Confirmed')) {
												$booked_status = 0;
												$count++;
											}
											if ($wcurrent < date('Y-m-d')) {
												$past = 1;
											}
										}
										for ($k = 0; $k <= 6; $k++) {
											$wcurrent = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+$k, $wtime[0]));
											if (in_array($wcurrent, $not_avail) || (in_array($wcurrent, $booked) && !(empty($booked_stat[$wcurrent])) && $booked_stat[$wcurrent] == 'Confirmed' && $count != 0)) {
												$booked_status = 2;
											}
										}
										if ($booked_status == 1) {
											$ret['weeks'][$i][] = array(
												'',
												'Booked',
												$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
												$this->php2JsTime($this->mySql2PhpTime($wend)) ,
												1,
												0,
												0,
												'#8D66D9',
												0,
												99,
												$item['Item']['id'],
												'booked',
												'CustomPricePerNight',
												$default_weekly_price,
												$p_list_no,
												$html->cText($item['Item']['title'],false) .'(' . $html->cText($customPricePerWeek['name'],false). ')',
												$item['Item']['id'],
												$customPricePerWeek['price_per_hour'],
												$customPricePerWeek['price_per_day'],
												$customPricePerWeek['price_per_month'],
												$from_to_date
											);
										} else if ($booked_status == 2 || $past == 1) {
											$ret['weeks'][$i][] = array(
												'',
												'Not Available',
												$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
												$this->php2JsTime($this->mySql2PhpTime($wend)) ,
												1,
												0,
												0,
												'#D96566',
												0,
												99,
												$item['Item']['id'],
												'Not Available',
												'CustomPricePerNight',
												$default_weekly_price,
												$p_list_no,
												$html->cText($item['Item']['title'],false) .'(' . $html->cText($customPricePerWeek['name'],false). ')',
												$item['Item']['id'],
												$customPricePerWeek['price_per_hour'],
												$customPricePerWeek['price_per_day'],
												$customPricePerWeek['price_per_month'],
												$from_to_date
											);
										} else {
											$is_Avail = true;
											$is_set_row = true;
											if( strtotime($customPricePerWeek['end_date']) < strtotime($wstart)){
												$is_Avail = false;
												$is_set_row = false;
											}else if(strtotime($customPricePerWeek['end_date']) < strtotime($wend)){
												$is_Avail = false;
											}
											if($is_Avail){
												$ret['weeks'][$i][] = array(
													'',
													$default_weekly_price,
													$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
													$this->php2JsTime($this->mySql2PhpTime($wend)) ,
													1,
													0,
													0,
													'#4CB050',
													1,
													99,
													$item['Item']['id'],
													'available',
													'CustomPricePerNight',
													$default_weekly_price,
													$p_list_no,
													$html->cText($item['Item']['title'],false) . '('. $html->cText($customPricePerWeek['name'],false) .')',
													$item['Item']['id'],
													$customPricePerWeek['price_per_hour'],
													$customPricePerWeek['price_per_day'],
													$customPricePerWeek['price_per_month'],
													$from_to_date
												);
											}else{
												if($is_set_row){
													$ret['weeks'][$i][] = array(
														'',
														'Not Available',
														$this->php2JsTime($this->mySql2PhpTime($wstart)) ,
														$this->php2JsTime($this->mySql2PhpTime($wend)) ,
														1,
														0,
														0,
														'#D96566',
														0,
														99,
														$item['Item']['id'],
														'Not Available',
														'CustomPricePerNight',
														$default_weekly_price,
														$p_list_no,
														$html->cText($item['Item']['title'],false) .'(' . $html->cText($customPricePerWeek['name'],false). ')',
														$item['Item']['id'],
														$customPricePerWeek['price_per_hour'],
														$customPricePerWeek['price_per_day'],
														$customPricePerWeek['price_per_month'],
														$from_to_date
													);
												}
											}
										}
									}
									$wstart = date('Y-m-d', mktime(0, 0, 0, $wtime[1], $wtime[2]+7, $wtime[0]));
								}
								// $wstart need update 
							}
						//}
					}
					$default_monthly_price = $default_monthly_price_hour = $default_monthly_price_day = $default_monthly_price_week = '';
					$month_available = 1;
					$check_month = $customPricePerMonth_id = 0;
					foreach($item['CustomPricePerNight'] as $cppn => $customPricePerMonth) {
						$pro_list_no = !empty($_SESSION['Item_Calender'][$item['Item']['id']]) ? 'I' . $_SESSION['Item_Calender'][$item['Item']['id']] . ':P'. ($cppn + 1) . ':' : '';						
						$default_monthly_price = $customPricePerMonth['price_per_month'];
						$default_monthly_price_hour = $customPricePerMonth['price_per_hour'];
						$default_monthly_price_day = $customPricePerMonth['price_per_day'];
						$default_monthly_price_week = $customPricePerMonth['price_per_week'];
						$month_available = 1;
						$check_month = 1;
						$customPricePerMonth_id = $customPricePerMonth['id'];
						if (!empty($item['ItemUser'])) {
							$ret['monthly_color'] = 'color-red';
						} else {
							$ret['monthly_color'] = 'color-green';
						}
						$item_custom_types = array();
						if(!empty($item['CustomPricePerNight'][$cppn]['CustomPricePerType'])) {
							$i = 0;
							foreach($item['CustomPricePerNight'][$cppn]['CustomPricePerType'] As $custom_types) {
								$item_custom_types[$i] = array(
									'id' => $custom_types['id'],
									'name' => $custom_types['name'],
									'price' => $custom_types['price']
								);
								$i++;
							}
						}
						if($item['Item']['is_sell_ticket'] == 0) {
							$p_list_no = $pro_list_no . ' ' . $html->cText($item['Item']['title'],false) . " (" . $html->cText($customPricePerMonth['name'],false) . ")";
						}else {
							$p_list_no = $pro_list_no . ' ' . $html->cText($item['Item']['title'],false);
						}
						if($customPricePerMonth['end_date'] == '') {
							$end_date = $customPricePerMonth['end_date'];
						} else {
							$end_date = $this->php2JsTime($this->mySql2PhpTime($customPricePerMonth['end_date']));
						}
						if (empty($item['ItemUser'])) {
							$ret['monthly'][] = array(
								$item['Item']['id'],
								$p_list_no,
								$customPricePerMonth_id,
								$this->php2JsTime($this->mySql2PhpTime($customPricePerMonth['start_date'])),
								$end_date,
								$default_monthly_price,
								$month_available,
								$default_monthly_price_hour,
								$default_monthly_price_day,
								$default_monthly_price_week,
								$item['Item']['is_sell_ticket'],
								$item['Item']['is_have_definite_time'],
								json_encode($item_custom_types),
							);
						}
					}
					//get time stamp
					$time = explode('-', $end);
					$ret['monthly_name'] = date('F', mktime(0, 0, 0, $time[1], $time[2], $time[0]));
                }
            }
        }
        catch(Exception $e) {
            $ret['error'] = $e->getMessage();
        }
        $time = explode('-', $end);
        $calender_month = date('Y-m-d', mktime(0, 0, 0, $time[1], 1, $time[0]));
        $current_month = date('Y-m-d', mktime(0, 0, 0, date('m') , 1, date('Y')));
        /*if ($calender_month <= $current_month) {
			$ret['monthly'] = array();
		}*/
        $ret['IsSuccess'] = true;
        $ret['Msg'] = 'Successfully';
        return $ret;
    }
    function addAndUpdateEventCalendar($custom_price_per_night_id, $id, $sell_tickets, $is_available, $custom_source_id, $fromdt, $todt, $parent_id) 
	{
		App::import('Model', 'Items.Item');
        $this->Item = new Item();
		App::import('Model', 'Items.CustomPricePerNight');
        $this->CustomPricePerNight = new CustomPricePerNight();
        $start = date('Y-m-d', strtotime($fromdt));
		$end = date('Y-m-d', strtotime($todt));	
		
		$item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.id' => $id,
            ) ,
            'recursive' => -1
        ));
		$custom_price = $this->Item->CustomPricePerNight->find('first', array(
			'conditions' => array(
				'CustomPricePerNight.id' => $parent_id
			),
			'fields' => array(
				'CustomPricePerNight.is_tipped',
				'CustomPricePerNight.total_available_count',
				'CustomPricePerNight.repeat_days',
				'CustomPricePerNight.hall_id',
				'CustomPricePerNight.is_seating_selection',
				'CustomPricePerNight.minimum_price',
			),
			'recursive' => -1
		));		
        $ret = array();
		if (empty($custom_price_per_night_id)) {
			/**
			** CustomPricePerNight - insert new record
			*/
			$data = array();		
			$data['CustomPricePerNight']['item_id'] = $id;
			$data['CustomPricePerNight']['parent_id'] = $parent_id;
			$data['CustomPricePerNight']['start_date'] = $start;
			$data['CustomPricePerNight']['end_date'] = $end;
			$data['CustomPricePerNight']['is_available'] = 1;
			$data['CustomPricePerNight']['is_tipped'] = $custom_price['CustomPricePerNight']['is_tipped'];
			$data['CustomPricePerNight']['total_available_count'] = $custom_price['CustomPricePerNight']['total_available_count'];
			$data['CustomPricePerNight']['total_booked_count'] = 0;
			$data['CustomPricePerNight']['is_custom'] = 1;
			$data['CustomPricePerNight']['repeat_days'] = $custom_price['CustomPricePerNight']['repeat_days'];
			if(isPluginEnabled('Seats') && $custom_price['CustomPricePerNight']['is_seating_selection']){
				$data['CustomPricePerNight']['hall_id'] = $custom_price['CustomPricePerNight']['hall_id'];
				$data['CustomPricePerNight']['is_seating_selection'] = $custom_price['CustomPricePerNight']['is_seating_selection'];
			}
			$this->CustomPricePerNight->create();
			$this->CustomPricePerNight->save($data);
			$custom_price_per_night_id = $this->CustomPricePerNight->getLastInsertId();
			$minimum_price = 0;
			/**
			** CustomPricePerType - insert new record
			*/
			if(!empty($sell_tickets)) {
				foreach($sell_tickets As $sell_ticket) {
					$custom_type = $this->Item->CustomPricePerType->find('first', array(
						'conditions' => array(
							'CustomPricePerType.id' => $sell_ticket->name,
						),
						'recursive' => -1
					));
					if(!empty($custom_type)) {
						if($minimum_price == 0 || $minimum_price > $sell_ticket->value) {
							$minimum_price = $sell_ticket->value;
						}
						$_data = array();
						$_data['CustomPricePerType']['item_id'] = $id;
						$_data['CustomPricePerType']['custom_price_per_night_id'] = $custom_price_per_night_id;
						$_data['CustomPricePerType']['name'] = $custom_type['CustomPricePerType']['name'];
						$_data['CustomPricePerType']['description'] = $custom_type['CustomPricePerType']['description'];
						$_data['CustomPricePerType']['price'] = $sell_ticket->value;
						$_data['CustomPricePerType']['max_number_of_quantity'] = $custom_type['CustomPricePerType']['max_number_of_quantity'];
						$_data['CustomPricePerType']['min_number_per_order'] = $custom_type['CustomPricePerType']['min_number_per_order'];
						$_data['CustomPricePerType']['max_number_per_order'] = $custom_type['CustomPricePerType']['max_number_per_order'];
						$_data['CustomPricePerType']['is_advanced_enabled'] = 0;
						$_data['CustomPricePerType']['booked_quantity'] = 0;
						$_data['CustomPricePerType']['start_time'] = $custom_type['CustomPricePerType']['start_time'];
						$_data['CustomPricePerType']['end_time'] = $custom_type['CustomPricePerType']['end_time'];
						if(isPluginEnabled('Seats') && $custom_price['CustomPricePerNight']['is_seating_selection']){
							$_data['CustomPricePerType']['partition_id'] = $custom_type['CustomPricePerType']['partition_id'];
						}
						$this->Item->CustomPricePerType->create();
						$this->Item->CustomPricePerType->save($_data, false);
						$custom_price_per_type_id = $this->Item->CustomPricePerType->getLastInsertId();
						/**
						** CustomPricePerTypesSeat - insert new record
						*/
						 if(isPluginEnabled('Seats') && $custom_price['CustomPricePerNight']['is_seating_selection']){
							if(!empty($data['CustomPricePerNight']['is_seating_selection'])){
								$seats = $this->Item->CustomPricePerType->CustomPricePerTypesSeat->Seat->find('all', array(
									'conditions' => array(
										'Seat.hall_id' => $data['CustomPricePerNight']['hall_id'],
										'Seat.partition_id' => $_data['CustomPricePerType']['partition_id'],
									),
									'order' => array(
										'Seat.id' => 'ASC',
									) ,                
									'recursive' => -1
								));	
								$stored = array('CustomPricePerTypesSeat' => array());
								foreach($seats as $seat){
									$tmp = array();
									$tmp['item_id'] = $id;
									$tmp['custom_price_per_type_id'] = $custom_price_per_type_id;
									$tmp['seat_id'] = $seat['Seat']['id'];
									$tmp['hall_id'] = $seat['Seat']['hall_id'];
									$tmp['partition_id'] = $seat['Seat']['partition_id'];
									$tmp['name'] = $seat['Seat']['name'];
									$tmp['seat_status_id'] = $seat['Seat']['seat_status_id'];
									$tmp['position'] = $seat['Seat']['position'];
									$tmp['name'] = $seat['Seat']['name'];
									$stored['CustomPricePerTypesSeat'][] = $tmp;
								}
								$this->Item->CustomPricePerType->CustomPricePerTypesSeat->saveAll($stored['CustomPricePerTypesSeat']);								
							}
						}
					}
				}
				$u_data = array();
				$u_data['CustomPricePerNight']['id'] = $custom_price_per_night_id;
				$u_data['CustomPricePerNight']['minimum_price'] = $minimum_price;
				$this->CustomPricePerNight->save($u_data);
			}
			$ret['IsSuccess'] = true;
			$ret['Msg'] = 'add success';
		} else {
			$records = $this->CustomPricePerNight->find('first', array(
				'conditions' => array(
					'CustomPricePerNight.id' => $custom_price_per_night_id,
				),
				'recursive' => -1
			));
			if(!empty($records) && $records['CustomPricePerNight']['total_booked_count'] == 0) {
				$data = array();
				$data['CustomPricePerNight']['id'] = $records['CustomPricePerNight']['id'];
				//$data['CustomPricePerNight']['start_date'] = $start;
				//$data['CustomPricePerNight']['end_date'] = $end;
				//$data['CustomPricePerNight']['start_time'] = $start_time;
				//$data['CustomPricePerNight']['end_time'] = $end_time;
				$this->CustomPricePerNight->save($data);
				
				$minimum_price = 0;
				if(!empty($sell_tickets)) {
					foreach($sell_tickets As $sell_ticket) {
						$custom_type = $this->Item->CustomPricePerType->find('first', array(
							'conditions' => array(
								'CustomPricePerType.id' => $sell_ticket->name,
							),
							'recursive' => -1
						));
						if(!empty($custom_type)) {
							if($minimum_price == 0 || $minimum_price > $sell_ticket->value) {
								$minimum_price = $sell_ticket->value;
							}
							$_data = array();
							$_data['CustomPricePerType']['id'] = $custom_type['CustomPricePerType']['id'];
							$_data['CustomPricePerType']['price'] = $sell_ticket->value;
							$_data['CustomPricePerType']['parent_id'] = 0;
							$_data['CustomPricePerType']['is_custom'] = 1;
							$this->Item->CustomPricePerType->save($_data, false);
						}
					}
					$u_data = array();
					$u_data['CustomPricePerNight']['id'] = $custom_price_per_night_id;
					$u_data['CustomPricePerNight']['minimum_price'] = $minimum_price;
					$this->CustomPricePerNight->save($u_data);
					$custom_price_per_night = $this->CustomPricePerNight->find('all', array(
						'conditions' => array(
							'CustomPricePerNight.item_id' => $id
						),
						'order' => array(
							'CustomPricePerNight.id' => 'ASC'
						) ,
						'recursive' => -1
					));
					$min_price = $custom_price_per_night[0]['CustomPricePerNight']['minimum_price'];
					$item_arr = array();
					$item_arr['Item']['id'] = $id;
					$item_arr['Item']['minimum_price'] = $min_price;
					$this->Item->save($item_arr);
				}
				
				$ret['IsSuccess'] = true;
				$ret['Msg'] = __l('Updated successfully');
			}  else {
				$ret['IsSuccess'] = false;
				$ret['Msg'] = __l('Item already booked.So price could not be updated');
			}			
		}
		$ret['Data'] = rand();
        return $ret;
	}
    function addCalendar($st, $et, $price, $price1, $ade, $id, $is_available, $fromdt, $todt, $fromdt_time, $todt_time, $custom_source_id)
    {	
		if($custom_source_id == 3 || $custom_source_id == 4) {	
			$stime = explode(' ', $st);
			$sdate = explode('/', $stime[0]);
			$start = date('Y-m-d', mktime(0, 0, 0, $sdate[0], $sdate[1], $sdate[2]));
			//start time
			if (!empty($ade)) {
				$s_time = explode(':', $stime[1]);
				$start_time = date('H:i:s', mktime($s_time[0], $s_time[1], 0, 0, 0, 0));
			} else {
				$start_time = date('H:i:s', mktime(0, 0, 0, 0, 0, 0));
			}
			//end date
			$etime = explode(' ', $et);
			$edate = explode('/', $etime[0]);
			$end = date('Y-m-d', mktime(0, 0, 0, $edate[0], $edate[1], $edate[2]));
			//end time
			if (!empty($ade)) {
				$e_time = explode(':', $etime[1]);
				$end_time = date('H:i:s', mktime($e_time[0], $e_time[1], 0, 0, 0, 0));
			} else {
				$end_time = date('H:i:s', mktime(23, 59, 59, 0, 0, 0));
			}
			//from date
			$fdate = date('Y-m-d', strtotime($fromdt));
			// to date
			$tdate = date('Y-m-d', strtotime($todt));
		} else {
			//Start Date
			$fdate = $start = date('Y-m-d', strtotime($fromdt));
			//Start Time
			$s_time = explode(':', $fromdt_time);
			$start_time = date('H:i:s', mktime($s_time[0], $s_time[1], 0, 0, 0, 0));
			//End Date
			$tdate = $end = date('Y-m-d', strtotime($todt));
			//End Time
			if($todt_time == '00:00') {
				$end_time = date('H:i:s', mktime(23, 59, 59, 0, 0, 0));
			} else {
				$e_time = explode(':', $todt_time);
				$end_time = date('H:i:s', mktime($e_time[0], $e_time[1], 0, 0, 0, 0));
			}
		}
        App::import('Model', 'Items.Item');
        $this->Item = new Item();
        App::import('Model', 'Items.ItemUser');
        $this->ItemUser = new ItemUser();
        $item = $this->Item->find('first', array(
            'conditions' => array(
                'Item.id' => $id,
            ) ,
            'recursive' => -1
        ));
        $itemUserCount = $this->ItemUser->find('all', array(
            'conditions' => array(
                'ItemUser.item_id' => $id,
                'ItemUser.from <= ' => $tdate,
                'ItemUser.to >= ' => $fdate,
                'ItemUser.item_user_status_id' => array(
                    ConstItemUserStatus::Confirmed,
                    ConstItemUserStatus::WaitingforReview,
                    ConstItemUserStatus::Completed,
                )
            ) ,
            'recursive' => -1
        ));
        if (empty($itemUserCount)) {
            App::import('Model', 'Items.CustomPricePerNight');
            $this->CustomPricePerNight = new CustomPricePerNight();
			$conditions = array();
			$conditions['CustomPricePerNight.start_date <='] = $tdate;
			$conditions['CustomPricePerNight.end_date >='] = $fdate;
			$conditions['CustomPricePerNight.item_id'] = $id;
			$conditions['CustomPricePerNight.is_custom'] = 1;
			if ($custom_source_id != ConstCustomSource::Hour) {
				$conditions['CustomPricePerNight.custom_source_id !='] = ConstCustomSource::Hour;
			}
            $records = $this->CustomPricePerNight->find('all', array(
                'conditions' => $conditions,
                'recursive' => -1
            ));
            $ret = array();
            if (empty($records)) {
                $data['start_date'] = $start;
                $data['end_date'] = $end;
                $data['start_time'] = $start_time;
                $data['end_time'] = $end_time;
                $data['item_id'] = $id;
                $data['price_per_hour'] = $price;
                if ($ade) {
                    $data['price_per_day'] = $price1;
                } else {
                    $data['price_per_day'] = $item['Item']['price_per_day'];
                }
                $data['price_per_week'] = $item['Item']['price_per_week'];
                $data['price_per_month'] = $item['Item']['price_per_month'];
                $data['is_custom'] = 1;
                $data['custom_source_id'] = $custom_source_id;
                $data['is_available'] = $is_available;
                $data['repeat_days'] = 'M,Tu,W,Th,F,Sa,Su';
                $this->CustomPricePerNight->create();
                $this->CustomPricePerNight->save($data);
                $ret['IsSuccess'] = true;
                $ret['Msg'] = 'add success';
            } else {
                if (empty($price)) {
                    $price = $item['Item']['price_per_hour'];
                }
                if (empty($price1)) {
                    $price1 = $item['Item']['price_per_day'];
                }
                $price2 = $item['Item']['price_per_week'];
                $price3 = $item['Item']['price_per_month'];
                $this->getSplittedDate($start, $end, $start_time, $end_time, $id, $custom_source_id, $is_available, $price, $price1, $price2, $price3, $records);
                $ret['IsSuccess'] = true;
                $ret['Msg'] = __l('add success');
            }
        } else {
            $ret['IsSuccess'] = false;
            $ret['Msg'] = __l('Update failed');
        }
        $ret['Data'] = rand();
        return $ret;
    }
    function getDateIntervals($day, $type)
    {
        $phpTime = $this->js2PhpTime($day);
        switch ($type) {
            case 'month':
                $st = mktime(0, 0, 0, date('m', $phpTime) , 1, date('Y', $phpTime));
                $et = mktime(0, 0, -1, date('m', $phpTime) +1, 1, date('Y', $phpTime));
                $cnt = 50;
                break;

            case 'week':
                //suppose first day of a week is monday
                $monday = date('d', $phpTime) -date('N', $phpTime) +1;
                $st = mktime(0, 0, 0, date('m', $phpTime) , $monday, date('Y', $phpTime));
                $et = mktime(0, 0, -1, date('m', $phpTime) , $monday+7, date('Y', $phpTime));
                $cnt = 20;
                break;

            case 'day':
                $st = mktime(0, 0, 0, date('m', $phpTime) , date('d', $phpTime) , date('Y', $phpTime));
                $et = mktime(0, 0, -1, date('m', $phpTime) , date('d', $phpTime) +1, date('Y', $phpTime));
                $cnt = 5;
                break;
        }
        $return = array();
        $return['start_date'] = $st;
        $return['end_date'] = $et;
        return $return;
    }
    function js2PhpTime($jsdate)
    {
        if (preg_match('@(\d+)/(\d+)/(\d+)\s+(\d+):(\d+)@', $jsdate, $matches) == 1) {
            $ret = mktime($matches[4], $matches[5], 0, $matches[1], $matches[2], $matches[3]);
        } else if (preg_match('@(\d+)/(\d+)/(\d+)@', $jsdate, $matches) == 1) {
            $ret = mktime(0, 0, 0, $matches[1], $matches[2], $matches[3]);
        }
        return $ret;
    }
    function php2JsTime($phpDate)
    {
        return date('m/d/Y H:i', $phpDate);
    }
    function php2MySqlTime($phpDate)
    {
        return date('Y-m-d H:i:s', $phpDate);
    }
    function mySql2PhpTime($sqlDate)
    {
        $arr = date_parse($sqlDate);
        return mktime($arr['hour'], $arr['minute'], $arr['second'], $arr['month'], $arr['day'], $arr['year']);
    }
    function getSplittedDate($id, $item_id, $custom_source_id, $is_available, $price, $price1, $price2, $price3, $records)
    {
        App::import('Model', 'Items.CustomPricePerNight');
        $this->CustomPricePerNight = new CustomPricePerNight();
		/*if ($custom_source_id != ConstCustomSource::Hour) {
			$conditions = array();
			$conditions['CustomPricePerNight.start_date <='] = $to;
			$conditions['CustomPricePerNight.end_date >='] = $from;
			$conditions['CustomPricePerNight.item_id'] = $item_id;
			$conditions['CustomPricePerNight.is_custom'] = 1;
			$conditions['CustomPricePerNight.custom_source_id'] = ConstCustomSource::Hour;
			$customPricePerNights = $this->CustomPricePerNight->find('list', array(
				'conditions' => $conditions,
				'recursive' => -1
			));
			foreach($customPricePerNights as $custom_price_per_night_id) {
				$this->CustomPricePerNight->delete($custom_price_per_night_id);
			}
		}*/
      /*  $updated_date_arr = array();
		$fromDateTS = strtotime($from);
		$toDateTS = strtotime($to);*/
		foreach($records as $record) {
			$date_before_select = $date_after_select = $date_between_select = $data = array();
			/*if ($custom_source_id == ConstCustomSource::Hour) {
				$startDateTS = strtotime($record['CustomPricePerNight']['start_date'] . ' ' . $record['CustomPricePerNight']['start_time']);
				$endDateTS = strtotime($record['CustomPricePerNight']['end_date'] . ' ' . $record['CustomPricePerNight']['end_time']);
				$fromDateTS = strtotime($from . ' ' . $from_time);
				$toDateTS = strtotime($to . ' ' . $to_time);
				for ($currentDateTS = $startDateTS; $currentDateTS <= $endDateTS; $currentDateTS+= (60*30)) {
					if ($currentDateTS < $fromDateTS) {
						$date_before_select[] = date('H:i:s', $currentDateTS);
					} elseif ($currentDateTS > $toDateTS) {
						$date_after_select[] = date('H:i:s', $currentDateTS);
					} else {
						$date_between_select[] = date('H:i:s', $currentDateTS);
					}
					$updated_date_arr[] = date('H:i:s', $currentDateTS);
				}
			} else {
				$startDateTS = strtotime($record['CustomPricePerNight']['start_date']);
				$endDateTS = strtotime($record['CustomPricePerNight']['end_date']);
				for ($currentDateTS = $startDateTS; $currentDateTS <= $endDateTS; $currentDateTS+= (60*60*24)) {
					if ($currentDateTS < $fromDateTS) {
						$date_before_select[] = date('Y-m-d', $currentDateTS);
					} elseif ($currentDateTS > $toDateTS) {
						$date_after_select[] = date('Y-m-d', $currentDateTS);
					} else {
						$date_between_select[] = date('Y-m-d', $currentDateTS);
					}
					$updated_date_arr[] = date('Y-m-d', $currentDateTS);
				}
			}*/
			//$tmp_data = $record;
			/*unset($tmp_data['CustomPricePerNight']['id']);
			unset($tmp_data['CustomPricePerNight']['created']);
			unset($tmp_data['CustomPricePerNight']['modified']);*/
			$cnt = 0;	
			$data['CustomPricePerNight']['id'] = $id;		
			$data['CustomPricePerNight']['item_id'] = $item_id;
			$data['CustomPricePerNight']['price_per_hour'] = $price;
			$data['CustomPricePerNight']['price_per_day'] = $price1;
			$data['CustomPricePerNight']['price_per_week'] = $price2;
			$data['CustomPricePerNight']['price_per_month'] = $price3;
			$data['CustomPricePerNight']['is_available'] = $is_available;
			$data['CustomPricePerNight']['is_custom'] = 0;
			$data['CustomPricePerNight']['repeat_days'] = '';
			$data['CustomPricePerNight']['custom_source_id'] = $custom_source_id;
			//$this->CustomPricePerNight->create();
			$this->CustomPricePerNight->save($data);			
			/*if (!empty($date_before_select)) {
				$data = $tmp_data;
				if ($custom_source_id == ConstCustomSource::Hour) {
					$data['CustomPricePerNight']['start_date'] = $from;
					$data['CustomPricePerNight']['end_date'] = $to;
					$data['CustomPricePerNight']['start_time'] = $date_before_select[0];
					$data['CustomPricePerNight']['end_time'] = date('H:i:s', strtotime($from . ' ' . $date_before_select[count($date_before_select) -1] . ' +30 minutes'));
				} else {
					$data['CustomPricePerNight']['start_date'] = $date_before_select[0];
					$data['CustomPricePerNight']['end_date'] = $date_before_select[count($date_before_select) -1];
					$data['CustomPricePerNight']['start_time'] = '00:00:00';
					$data['CustomPricePerNight']['end_time'] = '23:59:59';
				}
				$this->CustomPricePerNight->create();
				$this->CustomPricePerNight->save($data);
			}
			if (!empty($date_between_select)) {
				$data = $tmp_data;
				$data['CustomPricePerNight']['price_per_hour'] = $price;
				$data['CustomPricePerNight']['price_per_day'] = $price1;
				$data['CustomPricePerNight']['price_per_week'] = $price2;
				$data['CustomPricePerNight']['price_per_month'] = $price3;
				if ($custom_source_id == ConstCustomSource::Hour) {
					$data['CustomPricePerNight']['start_date'] = $from;
					$data['CustomPricePerNight']['end_date'] = $to;
					$data['CustomPricePerNight']['start_time'] = $date_between_select[0];
					$data['CustomPricePerNight']['end_time'] = $date_between_select[count($date_between_select) -1];
				} else {
					$data['CustomPricePerNight']['start_date'] = $date_between_select[0];
					$data['CustomPricePerNight']['end_date'] = $date_between_select[count($date_between_select) -1];
					$data['CustomPricePerNight']['start_time'] = '00:00:00';
					$data['CustomPricePerNight']['end_time'] = '23:59:59';
				}
				$data['CustomPricePerNight']['is_available'] = $is_available;
				$this->CustomPricePerNight->create();
				$this->CustomPricePerNight->save($data);
			}
			if (!empty($date_after_select)) {
				$data = $tmp_data;
				if ($custom_source_id == ConstCustomSource::Hour) {
					$data['CustomPricePerNight']['start_date'] = $from;
					$data['CustomPricePerNight']['end_date'] = $to;
					$data['CustomPricePerNight']['start_time'] = date('H:i:s', strtotime($from . ' ' . $date_after_select[0] . ' -30 minutes'));
					$data['CustomPricePerNight']['end_time'] = $date_after_select[count($date_after_select) -1];
				} else {
					$data['CustomPricePerNight']['start_date'] = $date_after_select[0];
					$data['CustomPricePerNight']['end_date'] = $date_after_select[count($date_after_select) -1];
					$data['CustomPricePerNight']['start_time'] = '00:00:00';
					$data['CustomPricePerNight']['end_time'] = '23:59:59';
				}
				$this->CustomPricePerNight->create();
				$this->CustomPricePerNight->save($data);
			}
			$this->CustomPricePerNight->delete($record['CustomPricePerNight']['id']);*/
		}
		/*$i = 0;
		$missed_arr = 'before_missed_date';
		if ($custom_source_id == ConstCustomSource::Hour) {
			$fromDateTS = strtotime($from . ' ' . $from_time);
			$toDateTS = strtotime($to . ' ' . $to_time);
			for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS+= (60*30)) {
				$i++;
				$current_date = date('H:i:s', $currentDateTS);
				if (!in_array($current_date, $updated_date_arr)) {
					if ($i == 1) {
						${$missed_arr}[0] = date('H:i:s', $currentDateTS);
						${$missed_arr}[1] = date('H:i:s', $currentDateTS);
					} else {
						${$missed_arr}[1] = date('H:i:s', $currentDateTS);
					}
				} else {
					$missed_arr = 'after_missed_date';
					$i = 0;
				}
			}
		} else {
			for ($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS+= (60*60*24)) {
				$i++;
				$current_date = date('Y-m-d', $currentDateTS);
				if (!in_array($current_date, $updated_date_arr)) {
					if ($i == 1) {
						${$missed_arr}[0] = date('Y-m-d', $currentDateTS);
						${$missed_arr}[1] = date('Y-m-d', $currentDateTS);
					} else {
						${$missed_arr}[1] = date('Y-m-d', $currentDateTS);
					}
				} else {
					$missed_arr = 'after_missed_date';
					$i = 0;
				}
			}
		}
		if (!empty($before_missed_date)) {
			$data = array();
			$data['CustomPricePerNight']['item_id'] = $item_id;
			$data['CustomPricePerNight']['price_per_hour'] = $price;
			$data['CustomPricePerNight']['price_per_day'] = $price1;
			$data['CustomPricePerNight']['price_per_week'] = $price2;
			$data['CustomPricePerNight']['price_per_month'] = $price3;
			if ($custom_source_id == ConstCustomSource::Hour) {
				$data['CustomPricePerNight']['start_date'] = $from;
				$data['CustomPricePerNight']['end_date'] = $to;
				$data['CustomPricePerNight']['start_time'] = $before_missed_date[0];
				$data['CustomPricePerNight']['end_time'] = date('H:i:s', strtotime($from . ' ' . $before_missed_date[1] . ' +30 minutes'));
			} else {
				$data['CustomPricePerNight']['start_date'] = $before_missed_date[0];
				$data['CustomPricePerNight']['end_date'] = $before_missed_date[1];
				$data['CustomPricePerNight']['start_time'] = '00:00:00';
				$data['CustomPricePerNight']['end_time'] = '23:59:59';
			}
			$data['CustomPricePerNight']['is_available'] = $is_available;
			$data['CustomPricePerNight']['is_custom'] = 1;
			$data['CustomPricePerNight']['repeat_days'] = '';
			$data['CustomPricePerNight']['custom_source_id'] = $custom_source_id;
			$this->CustomPricePerNight->create();
			$this->CustomPricePerNight->save($data);
		}
		if (!empty($after_missed_date)) {
			$data = array();
			$data['CustomPricePerNight']['item_id'] = $item_id;
			$data['CustomPricePerNight']['price_per_hour'] = $price;
			$data['CustomPricePerNight']['price_per_day'] = $price1;
			$data['CustomPricePerNight']['price_per_week'] = $price2;
			$data['CustomPricePerNight']['price_per_month'] = $price3;
			if ($custom_source_id == ConstCustomSource::Hour) {
				$data['CustomPricePerNight']['start_date'] = $from;
				$data['CustomPricePerNight']['end_date'] = $to;
				$data['CustomPricePerNight']['start_time'] = date('H:i:s', strtotime($from . ' ' . $after_missed_date[0] . ' -30 minutes'));
				$data['CustomPricePerNight']['end_time'] = $after_missed_date[1];
			} else {
				$data['CustomPricePerNight']['start_date'] = $after_missed_date[0];
				$data['CustomPricePerNight']['end_date'] = $after_missed_date[1];
				$data['CustomPricePerNight']['start_time'] = '00:00:00';
				$data['CustomPricePerNight']['end_time'] = '23:59:59';
			}
			$data['CustomPricePerNight']['is_available'] = $is_available;
			$data['CustomPricePerNight']['is_custom'] = 1;
			$data['CustomPricePerNight']['repeat_days'] = '';
			$data['CustomPricePerNight']['custom_source_id'] = $custom_source_id;
			$this->CustomPricePerNight->create();
			$this->CustomPricePerNight->save($data);
		}*/
        return true;
    }
    public function getDateDiff($date1, $date2)
    {
        $diff = abs(strtotime($date2) -strtotime($date1));
        $years = floor($diff/(365*60*60*24));
        $months = floor(($diff-$years*365*60*60*24) /(30*60*60*24));
        $days = floor(($diff-$years*365*60*60*24-$months*30*60*60*24) /(60*60*24));
        $hours = floor(($diff-$years*365*60*60*24-$months*30*60*60*24-$days*60*60*24) /(60*60));
        $minuts = floor(($diff-$years*365*60*60*24-$months*30*60*60*24-$days*60*60*24-$hours*60*60) /60);
        $seconds = floor(($diff-$years*365*60*60*24-$months*30*60*60*24-$days*60*60*24-$hours*60*60-$minuts*60));
        // return array
        $return['year'] = $years;
        $return['month'] = $months;
        $return['day'] = $days;
        $return['hour'] = $hours;
        $return['minuts'] = $minuts;
        $return['second'] = $seconds;
        return $return;
    }
	public function checkAvalibities($item_id, $from, $to)
	{
		App::import('Model', 'Items.CustomPricePerNight');
        $this->CustomPricePerNight = new CustomPricePerNight();
		$day_of_the_week = array(
			'M' => 1,
			'Tu' => 2,
			'W' => 3,
			'Th' => 4,
			'F' => 5,
			'Sa' => 6,
			'Su' => 7
		);
		$not_avaliable = array();
		$total_days = (strtotime($to) - strtotime($from)) /(60*60*24);
		$custom_price_per_nights = $this->CustomPricePerNight->find('all', array(
			'conditions' => array(
				'CustomPricePerNight.item_id' => $item_id,
				'CustomPricePerNight.is_available' => 1,
				'CustomPricePerNight.parent_id !=' => 0,
				//'CustomPricePerNight.is_custom' => 0,
			) ,
			'recursive' => -1
		));
		if(!empty($custom_price_per_nights)) {
			$repeat_days = array();
			foreach($custom_price_per_nights As $custom_price_per_night) {
				$repeat_days_arr = explode(',', $custom_price_per_night['CustomPricePerNight']['repeat_days']);
				foreach($repeat_days_arr as $repeat_day) {
					if(isset($day_of_the_week[$repeat_day])) {
						$repeat_days[] = $day_of_the_week[$repeat_day];
					}
				}
			}
			for ($i = 0; $i <= $total_days; $i++) {
				$day = date('Y-m-d', strtotime($from . "+" . $i . " day"));
				$day_of_day = date('N', strtotime($day));
				if (!empty($repeat_days) && !in_array($day_of_day, $repeat_days)) {
					$not_avaliable[] = $day;
				}
			}
		}
		return $not_avaliable;
	}
	function checkCustomNightAvailability($calendar_start_date, $calendar_end_date, $item_id) {
		App::import('Model', 'Items.Item');
		$this->Item = new Item();
		$day_of_the_week = array('M' => 1, 'Tu' => 2, 'W' => 3, 'Th' => 4, 'F' => 5, 'Sa' => 6, 'Su' => 7);
		$start = date('Y-m-d', strtotime($calendar_start_date));
		$end = date('Y-m-d', strtotime(date("Y-m-d", strtotime($calendar_end_date)) . "saturday"));		
		$fixed_contain = array(
			'CustomPricePerType'
		);
		if (isPluginEnabled('Seats')) {
			$fixed_contain[] = 'Hall';
			$fixed_contain['CustomPricePerType'] = 'Partition';
		}
		
		$item = $this->Item->CustomPricePerNight->find('all', array(
			'conditions' => array(
				'CustomPricePerNight.start_date <=' => $end,
				'CustomPricePerNight.parent_id' => 0,
				'CustomPricePerNight.item_id' => $item_id,
			) ,
			'contain' => $fixed_contain,
			'order' => 'CustomPricePerNight.start_date ASC',
			'recursive' => 1,
		));		
		$avalibilites = array();
		foreach($item As $customPricePerNight) {
			$repeat_end_date = $customPricePerNight['CustomPricePerNight']['repeat_end_date'];
			if(!empty($customPricePerNight['CustomPricePerNight']['repeat_days'])) {
				$repeat_days = array();
				$repeat_days_arr = explode(',', $customPricePerNight['CustomPricePerNight']['repeat_days']);
				foreach($repeat_days_arr as $repeat_day) {
					$repeat_days[] = $day_of_the_week[$repeat_day];
				}
				$current_date = date('Y-m-d');
				$start_date = $customPricePerNight['CustomPricePerNight']['start_date'];
				$total_days = ceil((strtotime($repeat_end_date) - strtotime($start_date)) /(60*60*24));
				$subCustomPricePerNights = $this->Item->CustomPricePerNight->find('all', array(
					'conditions' => array(
						'CustomPricePerNight.parent_id' => $customPricePerNight['CustomPricePerNight']['id'],
						'CustomPricePerNight.is_custom' => 1
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
				for($i = 0; $i <= $total_days; $i++) {
					$day = date('Y-m-d', strtotime($start_date . "+" . $i . " day"));
					$day_of_day = date('N', strtotime($day));
					if($day > $current_date) {
						if($customPricePerNight['CustomPricePerNight']['start_date'] != $day){
							if ($key = array_search($day, $subTmpCustomPricePerNights)) {
								$avalibilites[] = $subCustomPricePerNights[$key - 1];
							} elseif (in_array($day_of_day, $repeat_days)) {
								$data = array();
								$data['CustomPricePerNight']['item_id'] = $customPricePerNight['CustomPricePerNight']['item_id'];
								$data['CustomPricePerNight']['parent_id'] = $customPricePerNight['CustomPricePerNight']['id'];
								$data['CustomPricePerNight']['start_date'] = $day;
								$diff_days = ceil((strtotime($customPricePerNight['CustomPricePerNight']['end_date']) - strtotime($customPricePerNight['CustomPricePerNight']['start_date'])) /(60*60*24));
								$data['CustomPricePerNight']['start_time'] = $customPricePerNight['CustomPricePerNight']['start_time'];
								$data['CustomPricePerNight']['end_date'] = date('Y-m-d', strtotime($day . "+" . $diff_days . " day"));;
								$data['CustomPricePerNight']['end_time'] = $customPricePerNight['CustomPricePerNight']['end_time'];
								$data['CustomPricePerNight']['is_available'] = 1;
								$data['CustomPricePerNight']['minimum_price'] = $customPricePerNight['CustomPricePerNight']['minimum_price'];
								$data['CustomPricePerNight']['is_tipped'] = $customPricePerNight['CustomPricePerNight']['is_tipped'];
								$data['CustomPricePerNight']['total_available_count'] = $customPricePerNight['CustomPricePerNight']['total_available_count'];
								$data['CustomPricePerNight']['total_booked_count'] = 0;
								$data['CustomPricePerNight']['repeat_days'] = $customPricePerNight['CustomPricePerNight']['repeat_days'];
								$data['CustomPricePerNight']['is_tipped'] = $customPricePerNight['CustomPricePerNight']['is_tipped'];
								$data['CustomPricePerType'] = $customPricePerNight['CustomPricePerType'];
								$avalibilites[] = $data;
							}
						}
						else {
							$avalibilites[] = $customPricePerNight;
						}
					}
				}
			}
			else {
				$avalibilites[] = $customPricePerNight;
			}
		}
		return $avalibilites;
	}
	
}
?>
