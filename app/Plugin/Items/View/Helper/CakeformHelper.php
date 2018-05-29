<?php
class CakeformHelper extends AppHelper
{
    public $helpers = array(
        'Html',
        'Form'
    );
    /**
     * used in generating form fieldsets
     *
     * @access public
     */
    public $openFieldset = false;
    /**
     * Generates form HTMl
     *
     * @param array $formData
     *
     * @return string Form Html
     * @access public
     */
    function insert($formData, $model = null) 
    {
		$recurring_day = array('M' => 'M', 'Tu' => 'Tu', 'W' => 'W', 'Th' => 'Th', 'F' => 'F', 'Sa' => 'Sa', 'Su' => 'Su');
		$out = '';
        if (isset($formData['FormField'])) {
            foreach($formData['FormField'] as $field) {
                if (empty($field['is_dynamic_field'])) {
                    if($field['name'] == 'State.name' || $field['name'] == 'City.name' || $field['name'] == 'Attachment.filename') {
						$field['name'] = $field['name'];
					} else {
						$field['name'] = $model . '.' . $field['name'];
					}
                } else {
					$field['name'] = 'Form.' . $field['name'];
                }
                if (!empty($field['display']) && empty($field['is_sell_ticket']) && empty($field['is_book_unit_of_my_time'])) {
                    if ($field['type'] == 'url' || $field['type'] == 'video') {
                        $field['type'] = 'text';
                        $field['class'] = 'js-remove-error';
                    }
                    $out.= $this->_field($field);
                    if (!empty($field['Attachment'])) {
                        $out.= "<div class='hor-space row offset5'><div class='pull-left space'>" . $this->Html->cText($field['Attachment']['filename']) . '</div><div class="space pull-left"><p class="delete-block"><i class="icon-remove"></i> ' . $this->Html->link(__l('Delete') , array(
                            'action' => 'delete_attachment',
                            $this->request->data['Item']['id'],
                            $this->request->data['Item']['category_id'],
                            $field['Attachment']['id'],
                            $field['Attachment']['foreign_id'],
                            $this->request->params['action'],
                            $this->request->data['Item']['form_field_step'],
                            'admin' => false
                        ) , array(
                            'class' => 'js-confirm delete blackc',
                            'escape' => false
                        )) . '</p></div></div>';
                    }
                } else {
					$out.= '<div class="clearfix ver-space item-price-block offset4 js-item-price-block hide">';
					if(!empty($field['is_heading_show'])) {
						$out.= '<h3 class="well space textb text-16 span19 bot-mspace">' . __l('Choose booking type') .'</h3>';
					}
					$out.= $this->Form->hidden('Item.booking_type');
					if (!empty($field['is_book_unit_of_my_time'])) {
						$out.= '<div class="span19 ver-mspace prop-addlist space sep img-rounded">';
						$price_options = array('1' => __l('Flexible dates with fixed or variable (say, per hour) price.'));
						$out.= $this->Form->input('Item.price_type', array('type' => 'radio', 'info' => __l('You\'ll need to enter available days (not dates) and timings.'), 'options' => $price_options, 'div' => 'input radio no-mar', 'class' => 'js-item-price-type', 'hiddenField' => false));
						$out.='<div class="alert alert-inline alert-info hide js-price-info-0">
								<p>'.__l('Only one price is displayed when users are browsing listings.').' </p>
								<p>'.__l('Set the most commonly purchased price as the first.').'</p>
							</div>';
						$out.= '<div class="js-book-unit-price-type span17 hide book-unit-of-mytime-block preview-form-field-form offset1 ver-space">';
						$out.= $this->Form->hidden('Item.is_people_can_book_my_time', array('value' => '1', 'class' => 'js-is-people-can-book-my-time'));
						$out .= '<h4 class="text-14 bot-space bot-mspace sep-bot">'. __l('When is this available') .'?</h4>';
						if(!empty($this->request->data['CustomPricePerNight']['main_details']['id'])){
							$out.= $this->Form->hidden('CustomPricePerNight.main_details.id');
						}
						$booking_options = array('0' => __l('Any Time'));
						$out.= $this->Form->input('CustomPricePerNight.main_details.is_timing', array('type' => 'radio', 'info' => __l('User is able to book anytime within the given timings'), 'options' => $booking_options, 'div' => 'input radio no-mar required', 'class' => 'js-item-book-type', 'hiddenField' => false));
						$booking_options = array('1' => __l('Specific Time'));
						$out.= $this->Form->input('CustomPricePerNight.main_details.is_timing', array('type' => 'radio', 'info' => __l('User is able to book everyday only within the given timings'), 'options' => $booking_options, 'div' => 'input radio no-mar required', 'class' => 'js-item-book-type', 'hiddenField' => false));
						$out.= $this->Form->input('CustomPricePerNight.main_details.min_hours', array(
							  'label' => __l('Min Hours'),
							  'class' => 'js-no-pjax',
							  'div' => 'input no-mar'
							 ));
						$out.= '<div class="js-clone ver-space price-block-list clearfix">';
						$out.= '<h4 class="text-14 bot-space bot-mspace sep-bot clearfix">' . __l('How much does it cost?') . '</h4>';
						$out.= '<div class="clearfix add-block pull-right cur hide"><span class ="js-add-more js-no-pjax btn pull-right clone-add "><i class=" cur icon-plus"></i><span class="ver-smspace">' . __l('Add more'). '</span></span></div>';
						$out.= '<div class="website-block js-field-list sell-ticket-block span16 pr pull-right clearfix top-space hide">';
						$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-clone-counter">0 </span>';
						$out.= '<div class="clearfix"> </div>';
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.name', array(
													'label' => __l('Name'),
													'class' => 'js-price-name-input js-no-pjax',
													'div' => 'input text required'
												));
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.description', array(
													'label' => __l('Description'),
													'type' => 'textarea',
													'class' => 'js-price-desc-input js-no-pjax'
												));
						$from_date_options = array();
						$from_date_options['type'] = 'date';
						$from_date_options['div'] = 'clearfix';
						$from_date_options['div'] = 'input text required';
						$from_date_options['orderYear'] = 'asc';
						$from_date_options['minYear'] = date('Y') -10;
						$from_date_options['maxYear'] = date('Y') +10;
						$from_date_options['label'] = __l('Starts at');
						$from_date_options['empty'] = true;
						// For datetime picker need to add class js-datetimepicker
						$out.= '<div class="js-d-picker input no-pad marl-25 starts-left clearfix datetimepicker-block"><div class="js-cake-date">';
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.start_date', $from_date_options);
						$out.= '</div></div>';
						$from_date_options['label'] = __l('Ends');
						$out.= '<div class="js-d-picker input no-pad marl-25 clearfix datetimepicker-block"><div class="js-cake-date">';
					    $out.= $this->Form->input('CustomPricePerNight.price_detail.0.end_date', $from_date_options);
						$out.= '</div></div>';
						$from_time_options = array();
						$from_time_options['type'] = 'time';
						$from_time_options['div'] = 'clearfix';
						$from_time_options['div'] = 'input text required';
						$from_time_options['orderYear'] = 'asc';
						$from_time_options['minYear'] = date('Y') -10;
						$from_time_options['maxYear'] = date('Y') +10;
						$from_time_options['label'] = __l('Starts at');
						$from_time_options['empty'] = true;
						// For datetime picker need to add class js-datetimepicker						
						$out.= '<div class="js-t-picker input no-pad   marl-25 starts-left clearfix datetimepicker-block"><div class="js-cake-date">';
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.start_time', $from_time_options);
						$out.= '</div></div>';
						$from_time_options['label'] = __l('Ends');
						$out.= '<div class="js-t-picker input no-pad marl-25 clearfix datetimepicker-block"><div class="js-cake-date">';
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.end_time', $from_time_options);
						$out.= '</div></div>';
						$list_options = array(__l('Paid'), __l('Free'));
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.type', array(
						'label' => __l('Type'),
						'options' => $list_options,
						'type' => 'select',
						'class' => 'js-price-type-input js-no-pjax'
						));
						
						$out.= '<div class="clearfix pricin_details">';
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.price_per_hour', array(
						'label' => __l('Per Hour') . ' (' . Configure::read('site.currency') . ')',
						'class' => 'js-ticket-price-input js-no-pjax'
						));
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.price_per_day', array(
						'label' => __l('Per Day') . ' (' . Configure::read('site.currency') . ')',
						'class' => 'js-ticket-price-input js-no-pjax'
						));
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.price_per_week', array(
						'label' => __l('Per Week') . ' (' . Configure::read('site.currency') . ')',
						'class' => 'js-ticket-price-input js-no-pjax'
						));
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.price_per_month', array(
						'label' => __l('Per Month') . ' (' . Configure::read('site.currency') . ')',
						'class' => 'js-ticket-price-input js-no-pjax'
						));
						$out.= '</div>';
						
						$out.= '<div class="clearfix checkbox span11 days-list mspace repeat-day-checkbox js-repeat-days-div">';
						$out.= '<label>' . __l('And repeats on days') . '</label>';
						foreach($recurring_day As $key => $val) {
						$options = array($key => $val);
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.repeat_days.'.$val, array('type' => 'checkbox', 'label' => $val, 'value' => $val, 'class' => 'checkbox no-mar js-repeat-checkbox-flex', 'hiddenField' => false));
						}
						$out.= '</div>';
						$from_date_options['label'] = __l('Repeat Ends On');
						$out.= '<div class="js-d-picker input no-pad  clearfix required datetimepicker-block js-repeat-end-flex hide"><div class="js-cake-date">';
						$out.= $this->Form->input('CustomPricePerNight.price_detail.0.repeat_end_date', $from_date_options);
						$out.= '</div></div>';
						$out.= '</div>';
						if (empty($this->request->data['CustomPricePerNight']['price_detail'])) {
							$out.= '<div class="clearfix add-block pull-right cur"><span class ="js-add-more js-no-pjax btn pull-right clone-add "><i class=" cur icon-plus"></i><span class="ver-smspace">' . __l('Add more'). '</span></span></div>';
							$out.= '<div class="website-block js-field-list sell-ticket-block span16 pr pull-right clearfix sell-ticket-clone top-space js-new-clone-1">';
							$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-clone-counter">1 </span>';
							$out.= '<div class="clearfix"> </div>';
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.name', array(
													'label' => __l('Name'),
													'class' => 'js-price-name-input js-no-pjax',
													'div' => 'input text required'
												));
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.description', array(
														'label' => __l('Description'),
														'type' => 'textarea',
														'class' => 'js-price-desc-input js-no-pjax'
													));
							
							$from_date_options = array();
							$from_date_options['type'] = 'date';
							$from_date_options['div'] = 'clearfix';
							$from_date_options['div'] = 'input text';
							$from_date_options['orderYear'] = 'asc';
							$from_date_options['minYear'] = date('Y') -10;
							$from_date_options['maxYear'] = date('Y') +10;
							$from_date_options['label'] = __l('Starts at');
							$from_date_options['empty'] = true;
							// For datetime picker need to add class js-datetimepicker
							$out.= '<div class="js-datepicker input no-pad  marl-25 starts-left clearfix datetimepicker-block"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.start_date', $from_date_options);
							$out.= '</div></div>';
							$from_date_options['label'] = __l('Ends');
							$out.= '<div class="js-datepicker input no-pad  marl-25 clearfix datetimepicker-block"><div class="js-cake-date">';
						    $out.= $this->Form->input('CustomPricePerNight.price_detail.1.end_date', $from_date_options);
							$out.= '</div></div>';
							$from_time_options = array();
							$from_time_options['type'] = 'time';
							$from_time_options['div'] = 'clearfix';
							$from_time_options['div'] = 'input text';
							$from_time_options['orderYear'] = 'asc';
							$from_time_options['minYear'] = date('Y') -10;
							$from_time_options['maxYear'] = date('Y') +10;
							$from_time_options['label'] = __l('Starts at');							
							$from_time_options['empty'] = true;
							// For datetime picker need to add class js-datetimepicker						
							$out.= '<div class="js-time input no-pad  marl-25 starts-left clearfix datetimepicker-block"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.start_time', $from_time_options);
							$out.= '</div></div>';
							$from_time_options['label'] = __l('Ends');
							$out.= '<div class="js-time input no-pad clearfix marl-25 datetimepicker-block"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.end_time', $from_time_options);
							$out.= '</div></div>';
							$list_options = array(__l('Paid'), __l('Free'));
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.type', array(
							'label' => __l('Type'),
							'options' => $list_options,
							'type' => 'select',
							'class' => 'js-price-type-input js-no-pjax'
							));
							
							$out.= '<div class="clearfix pricin_details">';
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.price_per_hour', array(
							'label' => __l('Per Hour') . ' (' . Configure::read('site.currency') . ')',
							'class' => 'js-ticket-price-input js-no-pjax'
							));
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.price_per_day', array(
							'label' => __l('Per Day') . ' (' . Configure::read('site.currency') . ')',
							'class' => 'js-ticket-price-input js-no-pjax'
							));
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.price_per_week', array(
							'label' => __l('Per Week') . ' (' . Configure::read('site.currency') . ')',
							'class' => 'js-ticket-price-input js-no-pjax'
							));
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.price_per_month', array(
							'label' => __l('Per Month') . ' (' . Configure::read('site.currency') . ')',
							'class' => 'js-ticket-price-input js-no-pjax'
							));
							$out.= '</div>';
							
							$out.= '<div class="clearfix checkbox span11 days-list mspace repeat-day-checkbox js-repeat-days-div">';
							$out.= '<label>' . __l('And repeats on days') . '</label>';
							foreach($recurring_day As $key => $val) {
							$options = array($key => $val);							
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.repeat_days.'.$val, array('type' => 'checkbox', 'label' => $val, 'value' => $val, 'class' => 'checkbox no-mar js-repeat-checkbox-flex', 'hiddenField' => false));
							}
							$out.= '</div>';
							$from_date_options['label'] = __l('Repeat Ends On');
							$out.= '<div class="js-datepicker input no-pad  clearfix required datetimepicker-block js-repeat-end-flex hide"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerNight.price_detail.1.repeat_end_date', $from_date_options);
							$out.= '</div></div>';
							$out.= '</div>';
							
						} else {
							$i = 1;							
							foreach($this->request->data['CustomPricePerNight']['price_detail'] as $key => $custom_price_pre_night) {
							if(!empty($custom_price_pre_night)){
								if(!empty($custom_price_pre_night['id'])) {
									$out.= $this->Form->hidden('CustomPricePerNight.price_detail.' . $key . '.id');
								}
								$repeat_end_class = ' hide';
								if(!empty($custom_price_pre_night['repeat_days']) && count($custom_price_pre_night['repeat_days'] > 0)){
										$repeat_end_class = '';
								}
								if ($i > 1) {
									$out.= '<div class="js-field-list top-space sell-ticket-block clearfix span16 pull-right sell-ticket-clone js-new-clone-' . $i . '">';
									$out.= '<span class="span2 js-website-remove clone-remove pull-right btn pull-right"><i class="cur icon-remove"></i><span class="ver-smspace">' . __l('Remove'). '</span></span>';
								} else {
									$out.= '<div class="clearfix add-block pull-right cur"><span class ="js-add-more js-no-pjax btn pull-right clone-add "><i class="cur icon-plus"></i><span class="ver-smspace">' . __l('Add more'). '</span></span></div>';
									$out.= '<div class="js-field-list top-space sell-ticket-block clearfix span16 pull-right sell-ticket-clone js-new-clone-' . $i . '">';
								}
								$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-clone-counter">' . $i . '</span>';
								$out.= '<div class="clearfix"></div>';
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.name', array(
									'label' => __l('Name'),
									'class' => 'js-price-name-input js-no-pjax',
									'div' => 'input text required'
								));
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.description', array(
									'label' => __l('Description'),
									'type' => 'textarea',
									'class' => 'js-price-desc-input js-no-pjax'
								));								
								$from_date_options = array();
								$from_date_options['type'] = 'date';
								$from_date_options['div'] = 'clearfix';
								$from_date_options['div'] = 'input text required';
								$from_date_options['orderYear'] = 'asc';
								$from_date_options['minYear'] = date('Y') -10;
								$from_date_options['maxYear'] = date('Y') +10;
								$from_date_options['label'] = __l('Starts at');
								$from_date_options['empty'] = true;
								// For datetime picker need to add class js-datetimepicker
								$out.= '<div class="js-datepicker input no-pad  marl-25 starts-left clearfix datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.start_date', $from_date_options);
								$out.= '</div></div>';
								$from_date_options['label'] = __l('Ends');
								$out.= '<div class="js-datepicker input no-pad  marl-25 clearfix datetimepicker-block"><div class="js-cake-date">';
							    $out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.end_date', $from_date_options);
								$out.= '</div></div>';
								$from_time_options = array();
								$from_time_options['type'] = 'time';
								$from_time_options['div'] = 'clearfix';
								$from_time_options['div'] = 'input text';
								$from_time_options['orderYear'] = 'asc';
								$from_time_options['minYear'] = date('Y') -10;
								$from_time_options['maxYear'] = date('Y') +10;
								$from_time_options['label'] = __l('Starts at');
								$from_time_options['empty'] = true;
								// For datetime picker need to add class js-datetimepicker						
								$out.= '<div class="js-time input no-pad  marl-25 starts-left clearfix datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.start_time', $from_time_options);
								$out.= '</div></div>';
								$from_time_options['label'] = __l('Ends');
								$out.= '<div class="js-time input no-pad  clearfix datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.end_time', $from_time_options);
								$out.= '</div></div>';
								$list_options = array(__l('Paid'), __l('Free'));
								$hide_class = '';
								if(!empty($custom_price_pre_night['type']) && $custom_price_pre_night['type'] == 1){
									$hide_class = 'hide';
								}
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.type', array(
								'label' => __l('Type'),
								'options' => $list_options,
								'type' => 'select',
								'class' => 'js-price-type-input js-no-pjax'
								));
								
								$out.= '<div class="clearfix pricin_details '.$hide_class.'">';
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.price_per_hour', array(
								'label' => __l('Per Hour') . ' (' . Configure::read('site.currency') . ')',
								'class' => 'js-ticket-price-input js-no-pjax'
								));
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.price_per_day', array(
								'label' => __l('Per Day') . ' (' . Configure::read('site.currency') . ')',
								'class' => 'js-ticket-price-input js-no-pjax'
								));
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.price_per_week', array(
								'label' => __l('Per Week') . ' (' . Configure::read('site.currency') . ')',
								'class' => 'js-ticket-price-input js-no-pjax'
								));
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $key . '.price_per_month', array(
								'label' => __l('Per Month') . ' (' . Configure::read('site.currency') . ')',
								'class' => 'js-ticket-price-input js-no-pjax'
								));
								$out.= '</div>';
								
								$out.= '<div class="clearfix checkbox span11 days-list  mspace repeat-day-checkbox js-repeat-days-div">';
								$out.= '<label>' . __l('And repeats on days') . '</label>';
								foreach($recurring_day As $key => $val) {
									$options = array($key => $val);
									$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $i . '.repeat_days.'.$val, array('type' => 'checkbox', 'label' => $val, 'value' => $val, 'class' => 'checkbox no-mar js-repeat-checkbox-flex', 'hiddenField' => false));
								}
								$out.= '</div>';
								$from_date_options['label'] = __l('Repeat Ends On');
								$out.= '<div class="js-datepicker input no-pad  clearfix required datetimepicker-block js-repeat-end-flex'.$repeat_end_class.'"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerNight.price_detail.' . $i . '.repeat_end_date', $from_date_options);
								$out.= '</div></div>';							
								$out.= '</div>';
								$i++;
								}
							}
						}
						$out.= '</div>';
						$out.= '</div>';
						$out.= '</div>';
					}
					if (!empty($field['is_sell_ticket'])) {
						$out.= '<div class="span19 ver-mspace prop-addlist space sep img-rounded">';
						$price_options = array('2' => __l('Fixed available dates with fixed price. can have different ticket type with different pricing.'));	
						$out.= $this->Form->input('Item.price_type', array('type' => 'radio', 'info' => __l('You\'ll need to enter each dates-range.'), 'options' => $price_options, 'div' => 'input radio no-mar', 'class' => 'js-item-price-type', 'hiddenField' => false));
						$out.='<div class="alert alert-inline alert-info hide js-price-info-1">
								<p>'.__l('Only one price is displayed when users are browsing listings.').' </p>
								<p>'.__l('Set the most commonly purchased price at the top.').'</p>
							</div>';
						$out.= '<div class="js-sell-ticket-price-type span17 hide sell-ticket-block preview-form-field-form offset1 ver-space">';
						$out.= $this->Form->hidden('Item.is_sell_ticket', array('value' => 0, 'class' => 'js-is-sell-ticket'));
						$out.= '<div class="js-clone clearfix">';
							$out.= '<div class="website-block js-field-list span16 pr pull-right sell-ticket-block clearfix top-space hide">';
							$out.= '<h4 class="text-14 bot-space bot-mspace sep-bot">' . __l('When is this happening?') . '</h4>';
							$from_date_options = array();
							$from_date_options['type'] = 'date';
							$from_date_options['div'] = 'clearfix';
							$from_date_options['div'] = 'input text required';
							$from_date_options['orderYear'] = 'asc';
							$from_date_options['minYear'] = date('Y') -10;
							$from_date_options['maxYear'] = date('Y') +10;
							$from_date_options['label'] = __l('Starts at');
							$from_date_options['empty'] = true;
							// For datetime picker need to add class js-datetimepicker
							$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-clone-counter">0 </span>';
							$out.= '<div class="js-d-picker input  no-pad pull-left clearfix required datetimepicker-block"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerNight.SellTicket.0.start_date', $from_date_options);
							$out.= '</div></div>';
							$from_date_options['label'] = __l('Ends');
							$out.= '<div class="js-d-picker input no-pad  clearfix required datetimepicker-block "><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerNight.SellTicket.0.end_date', $from_date_options);
							$out.= '</div></div>';							
							$recurring_day_options = array();
							$recurring_day_options['type'] = 'select';
							$recurring_day_options['multiple'] = 'checkbox';
							$recurring_day_options['options'] = $recurring_day;
							$recurring_day_options['label'] = __l('And repeats on days');
							$recurring_day_options['class'] = 'checkbox no-mar js-repeat-checkbox';
							$recurring_day_options['div'] = 'clearfix checkbox span11 days-list mspace repeat-day-checkbox js-repeat-days';
							$out.= $this->Form->input('CustomPricePerNight.SellTicket.0.recurring_day', $recurring_day_options);
							$from_date_options['label'] = __l('Repeat Ends On');
							$out.= '<div class="js-d-picker input no-pad  clearfix required datetimepicker-block js-repeat-end hide"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerNight.SellTicket.0.repeat_end_date', $from_date_options);
							$out.= '</div></div>';							
							if(isPluginEnabled('Seats')){
								$seat_options['type'] = 'checkbox';
								$seat_options['options'] = array('0' => __l('Enable Seat Booking ?'));
								$seat_options['label'] = __l('Enable Seat Booking ?');
								$seat_options['class'] = 'checkbox no-mar js-seat-enabled';
								$seat_options['div'] = 'clearfix checkbox span11 days-list mspace seating-selection-checkbox';
								$out.= $this->Form->input('CustomPricePerNight.SellTicket.0.is_seating_selection', $seat_options);
								$out.=$this->Form->input('CustomPricePerNight.SellTicket.0.hall_id', array('label' => __l('Halls'), 'empty' => __l('Please Select'), 'class' => 'js-hall-select no-mar','div' => 'input select clearfix mspace js-hall-div hide'));
							}
							$out.= '<div class="js-clone-sub ver-space cost-block-list clearfix">';
							$out.= '<div class="website-block js-field-list-sub sell-ticket-block span16 pull-right clearfix top-space hide">';
							$out.= '<h4 class="text-14 bot-space bot-mspace sep-bot clearfix">' . __l('How much does it cost?') . '</h4>';
							$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-sub-clone-counter">0 </span>';
							$out.= $this->Form->input('CustomPricePerType.0.0.name', array(
								'label' => __l('Name'),
								'class' => 'js-price-name-input js-no-pjax',
								'div' => 'input text required'
							));
							$out.= $this->Form->input('CustomPricePerType.0.0.description', array(
							'label' => __l('Description'),
							'type' => 'textarea',
							'class' => 'js-price-desc-input js-no-pjax'
							));
							$out.= $this->Form->input('CustomPricePerType.0.0.price', array(
							'label' => __l('Price') . ' (' . Configure::read('site.currency') . ')',
							'class' => 'js-ticket-price-input js-no-pjax',
							'info' => __l('Leave blank for free')
							));
							$out.= $this->Form->input('CustomPricePerType.0.0.max_number_of_quantity', array(
							'label' => __l('Quantity'),
							'class' => 'js-ticket-price-input js-no-pjax',
							'info' => __l('Leave blank for unlimited Quantity'),
							'div' => 'input text js-qty-div'
							));							
							if(isPluginEnabled('Seats')){									
								$out.=$this->Form->input('CustomPricePerType.0.0.partition_id', array('label' => __l('Partitions'), 'empty' => __l('Please Select'), 'class' => 'js-partition-select','div' => 'input select clearfix mspace js-partition-div hide'));
							}
							$from_time_options = array();
							$from_time_options['type'] = 'time';
							$from_time_options['div'] = 'clearfix';
							$from_time_options['div'] = 'input text required';
							$from_time_options['orderYear'] = 'asc';
							$from_time_options['minYear'] = date('Y') -10;
							$from_time_options['maxYear'] = date('Y') +10;
							$from_time_options['label'] = __l('Starts at');
							$from_time_options['empty'] = true;
							// For datetime picker need to add class js-datetimepicker						
							$out.= '<div class="js-t-picker input no-pad marl-25 starts-left clearfix required datetimepicker-block"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerType.0.0.start_time', $from_time_options);
							$out.= '</div></div>';
							$from_time_options['label'] = __l('Ends');
							$out.= '<div class="js-t-picker input no-pad marl-25 starts-left clearfix required datetimepicker-block"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerType.0.0.end_time', $from_time_options);
							$out.= '</div></div>';
							$out.= '</div>';
							$out.= '<div class="clearfix add-block pull-right cur"><span class ="js-add-more-sub js-no-pjax btn pull-right clone-add " data-clone_sub="js-clone-sub" data-field_list_sub="js-field-list-sub"><i class="cur icon-plus"></i><span class="ver-smspace">' . __l('Add more'). '</span></span></div>';
							$out.= '<div class="website-block js-field-list-sub span16 pull-right sell-ticket-block clearfix sell-ticket-clone top-space js-new-clone-sub-1">';
							$out.= '<h4 class="text-14 bot-space bot-mspace sep-bot clearfix">' . __l('How much does it cost?') . '</h4>';
							$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-sub-clone-counter">1 </span>';
							$out.= $this->Form->input('CustomPricePerType.0.1.name', array(
								'label' => __l('Name'),
								'class' => 'js-price-name-input js-no-pjax',
								'div' => 'input text required'
							));
							$out.= $this->Form->input('CustomPricePerType.0.1.description', array(
							'label' => __l('Description'),
							'type' => 'textarea',
							'class' => 'js-price-desc-input js-no-pjax'
							));
							$out.= $this->Form->input('CustomPricePerType.0.1.price', array(
							'label' => __l('Price') . ' (' . Configure::read('site.currency') . ')',
							'class' => 'js-ticket-price-input js-no-pjax',
							'info' => __l('Leave blank for free')
							));
							$out.= $this->Form->input('CustomPricePerType.0.1.max_number_of_quantity', array(
							'label' => __l('Quantity'),
							'class' => 'js-ticket-price-input js-no-pjax',
							'info' => __l('Leave blank for unlimited Quantity'),
							'div' => 'input text js-qty-div'
							));							
							if(isPluginEnabled('Seats')){									
								$out.=$this->Form->input('CustomPricePerType.0.1.partition_id', array('label' => __l('Partitions'), 'empty' => __l('Please Select'), 'class' => 'js-partition-select','div' => 'input select clearfix mspace js-partition-div hide'));
							}
							$from_time_options = array();
							$from_time_options['type'] = 'time';
							$from_time_options['div'] = 'clearfix';
							$from_time_options['div'] = 'input text required';
							$from_time_options['orderYear'] = 'asc';
							$from_time_options['minYear'] = date('Y') -10;
							$from_time_options['maxYear'] = date('Y') +10;
							$from_time_options['label'] = __l('Starts at');
							$from_time_options['empty'] = true;
							// For datetime picker need to add class js-datetimepicker						
							$out.= '<div class="js-t-picker input no-pad  marl-25 starts-left clearfix required datetimepicker-block"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerType.0.1.start_time', $from_time_options);
							$out.= '</div></div>';
							$from_time_options['label'] = __l('Ends');
							$out.= '<div class="js-t-picker input no-pad marl-25 clearfix required datetimepicker-block"><div class="js-cake-date">';
							$out.= $this->Form->input('CustomPricePerType.0.1.end_time', $from_time_options);
							$out.= '</div></div>';
							$out.= '</div>';
							$out.= '</div>';
							$out.= '</div>';
							
							if (empty($this->request->data['CustomPricePerNight']['SellTicket'])) {
								$out.= '<div class="clearfix add-block pull-right cur"><span class ="js-add-more js-no-pjax btn pull-right clone-add "><i class=" cur icon-plus"></i><span class="ver-smspace">' . __l('Add more'). '</span></span></div>';
								$out.= '<div class="website-block js-field-list span16 pr pull-right sell-ticket-block clearfix top-space js-new-clone-1">';
								$out.= '<h4 class="text-14 bot-space bot-mspace sep-bot">' . __l('When is this happening?') . '</h4>';
								$from_date_options = array();
								$from_date_options['type'] = 'date';
								$from_date_options['div'] = 'clearfix';
								$from_date_options['div'] = 'input text required';
								$from_date_options['orderYear'] = 'asc';
								$from_date_options['minYear'] = date('Y') -10;
								$from_date_options['maxYear'] = date('Y') +10;
								$from_date_options['label'] = __l('Starts at');
								$from_date_options['empty'] = true;
								// For datetime picker need to add class js-datetimepicker
								$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-sub-clone-counter">1 </span>';
								$out.= '<div class="js-datepicker input no-pad pull-left  clearfix required datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerNight.SellTicket.1.start_date', $from_date_options);
								$out.= '</div></div>';
								$from_date_options['label'] = __l('Ends');
								$out.= '<div class="js-datepicker input no-pad  clearfix required datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerNight.SellTicket.1.end_date', $from_date_options);
								$out.= '</div></div>';
								$recurring_day_options = array();
								$recurring_day_options['type'] = 'select';
								$recurring_day_options['multiple'] = 'checkbox';
								$recurring_day_options['options'] = $recurring_day;
								$recurring_day_options['label'] = __l('And repeats on days');
								$recurring_day_options['class'] = 'checkbox no-mar js-repeat-checkbox';
								$recurring_day_options['div'] = 'clearfix checkbox span11 days-list mspace repeat-day-checkbox js-repeat-days';
								$out.= $this->Form->input('CustomPricePerNight.SellTicket.1.recurring_day', $recurring_day_options);
								$from_date_options['label'] = __l('Repeat Ends On');
									$out.= '<div class="js-datepicker input no-pad  clearfix required datetimepicker-block js-repeat-end hide"><div class="js-cake-date">';
									$out.= $this->Form->input('CustomPricePerNight.SellTicket.1.repeat_end_date', $from_date_options);
									$out.= '</div></div>';
								if(isPluginEnabled('Seats')){
									$seat_options['type'] = 'checkbox';
									$seat_options['options'] = array('0' => __l('Enable Seat Booking ?'));
									$seat_options['label'] = __l('Enable Seat Booking ?');
									$seat_options['class'] = 'checkbox no-mar js-seat-enabled';
									$seat_options['div'] = 'clearfix checkbox span11 days-list mspace seating-selection-checkbox';									
									$out.= $this->Form->input('CustomPricePerNight.SellTicket.1.is_seating_selection', $seat_options);
									$out.=$this->Form->input('CustomPricePerNight.SellTicket.1.hall_id', array('label' => __l('Halls'), 'empty' => __l('Please Select'), 'class' => 'js-hall-select no-mar','div' => 'input select clearfix mspace js-hall-div hide'));
								}
								$out.= '<div class="js-clone-sub ver-space cost-block-list clearfix">';
								$out.= '<div class="website-block js-field-list-sub sell-ticket-block span16 pull-right clearfix top-space hide">';
								$out.= '<h4 class="text-14 bot-space bot-mspace sep-bot clearfix">' . __l('How much does it cost?') . '</h4>';
								$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-sub-clone-counter">0 </span>';
								$out.= $this->Form->input('CustomPricePerType.1.0.name', array(
									'label' => __l('Name'),
									'class' => 'js-price-name-input js-no-pjax',
									'div' => 'input text required'
								));
								$out.= $this->Form->input('CustomPricePerType.1.0.description', array(
								'label' => __l('Description'),
								'type' => 'textarea',
								'class' => 'js-price-desc-input js-no-pjax'
								));
								$out.= $this->Form->input('CustomPricePerType.1.0.price', array(
								'label' => __l('Price') . ' (' . Configure::read('site.currency') . ')',
								'class' => 'js-ticket-price-input js-no-pjax',
								'info' => __l('Leave blank for free')
								));
								$out.= $this->Form->input('CustomPricePerType.1.0.max_number_of_quantity', array(
								'label' => __l('Quantity'),
								'class' => 'js-ticket-price-input js-no-pjax',
								'info' => __l('Leave blank for unlimited Quantity'),
								'div' => 'input text js-qty-div'
								));							
								if(isPluginEnabled('Seats')){									
									$out.=$this->Form->input('CustomPricePerType.1.0.partition_id', array('label' => __l('Partitions'), 'empty' => __l('Please Select'), 'class' => 'js-partition-select','div' => 'input select clearfix mspace js-partition-div hide'));
								}
								$from_time_options = array();
								$from_time_options['type'] = 'time';
								$from_time_options['div'] = 'clearfix';
								$from_time_options['div'] = 'input text required';
								$from_time_options['orderYear'] = 'asc';
								$from_time_options['minYear'] = date('Y') -10;
								$from_time_options['maxYear'] = date('Y') +10;
								$from_time_options['label'] = __l('Starts at');
								$from_time_options['empty'] = true;
								// For datetime picker need to add class js-datetimepicker						
								$out.= '<div class="js-t-picker input no-pad marl-25 starts-left clearfix required datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerType.1.0.start_time', $from_time_options);
								$out.= '</div></div>';
								$from_time_options['label'] = __l('Ends');
								$out.= '<div class="js-t-picker input no-pad marl-25 clearfix required datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerType.1.0.end_time', $from_time_options);
								$out.= '</div></div>';
								$out.= '</div>';
								$out.= '<div class="clearfix add-block pull-right cur"><span class ="js-add-more-sub js-no-pjax btn pull-right clone-add " data-clone_sub="js-clone-sub" data-field_list_sub="js-field-list-sub"><i class="cur icon-plus"></i><span class="ver-smspace">' . __l('Add more'). '</span></span></div>';
								$out.= '<div class="website-block js-field-list-sub span16 pull-right sell-ticket-block clearfix sell-ticket-clone top-space js-new-clone-sub-1">';								
								$out.= '<h4 class="text-14 bot-space bot-mspace sep-bot clearfix">' . __l('How much does it cost?') . '</h4>';
								$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-sub-clone-counter">1 </span>';
								$out.= $this->Form->input('CustomPricePerType.1.1.name', array(
									'label' => __l('Name'),
									'class' => 'js-price-name-input js-no-pjax',
									'div' => 'input text required'
								));
								$out.= $this->Form->input('CustomPricePerType.1.1.description', array(
								'label' => __l('Description'),
								'type' => 'textarea',
								'class' => 'js-price-desc-input js-no-pjax'
								));
								$out.= $this->Form->input('CustomPricePerType.1.1.price', array(
								'label' => __l('Price') . ' (' . Configure::read('site.currency') . ')',
								'class' => 'js-ticket-price-input js-no-pjax',
								'info' => __l('Leave blank for free')
								));
								$out.= $this->Form->input('CustomPricePerType.1.1.max_number_of_quantity', array(
								'label' => __l('Quantity'),
								'class' => 'js-ticket-price-input js-no-pjax',
								'info' => __l('Leave blank for unlimited Quantity'),
								'div' => 'input text js-qty-div'
								));
								if(isPluginEnabled('Seats')){
									$out.=$this->Form->input('CustomPricePerType.1.1.partition_id', array('label' => __l('Partitions'), 'empty' => __l('Please Select'), 'class' => 'js-partition-select','div' => 'input select clearfix mspace js-partition-div hide'));
								}
								$from_time_options = array();
								$from_time_options['type'] = 'time';
								$from_time_options['div'] = 'clearfix';
								$from_time_options['div'] = 'input text required';
								$from_time_options['orderYear'] = 'asc';
								$from_time_options['minYear'] = date('Y') -10;
								$from_time_options['maxYear'] = date('Y') +10;
								$from_time_options['label'] = __l('Starts at');
								$from_time_options['empty'] = true;
								// For datetime picker need to add class js-datetimepicker						
								$out.= '<div class="js-timepicker input no-pad starts-left clearfix required datetimepicker-block marl-25"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerType.1.1.start_time', $from_time_options);
								$out.= '</div></div>';
								$from_time_options['label'] = __l('Ends');
								$out.= '<div class="js-timepicker input no-pad  clearfix required datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerType.1.1.end_time', $from_time_options);
								$out.= '</div></div>';
								$out.= '</div>';
								
								$out.= '</div>';
								$out.= '</div>';
							} else {
							$i = 1;
							unset($this->request->data['CustomPricePerNight']['SellTicket'][0]);
							foreach($this->request->data['CustomPricePerNight']['SellTicket'] as $key => $custom_price_pre_night) {
								if(!empty($custom_price_pre_night['id'])){
									$out.= $this->Form->hidden('CustomPricePerNight.SellTicket.' . $key . '.id');
									$out.= $this->Form->hidden('CustomPricePerNight.SellTicket.' . $key . '.is_enable_seat_old_val');
									$out.= $this->Form->hidden('CustomPricePerNight.SellTicket.' . $key . '.hall_old_id');									
								}
								if ($i > 1) {
									$out.= '<div class="js-field-list top-space sell-ticket-block span16 pr pull-right clearfix sell-ticket-clone  js-new-clone-' . $i . '">';
									$out.= '<span class="span2 js-website-remove btn pull-right clone-remove pull-right"><i class="cur icon-remove"></i><span class="ver-smspace">' . __l('Remove'). '</span></span>';
								} else {
									$out.= '<div class="clearfix add-block pull-right cur"><span class ="js-add-more js-no-pjax btn pull-right clone-add "><i class=" cur icon-plus"></i><span class="ver-smspace">' . __l('Add more'). '</span></span></div>';
									$out.= '<div class="js-field-list top-space span16 pull-right sell-ticket-block clearfix sell-ticket-clone js-new-clone-' . $i . '">';
								}
								$out.= '<h4 class="text-14 bot-space bot-mspace sep-bot">' . __l('When is this happening?') . '</h4>';
								$from_date_options = array();
								$from_date_options['type'] = 'date';
								$from_date_options['div'] = 'clearfix';
								$from_date_options['div'] = 'input text required';
								$from_date_options['orderYear'] = 'asc';
								$from_date_options['minYear'] = date('Y') -10;
								$from_date_options['maxYear'] = date('Y') +10;
								$from_date_options['label'] = __l('Starts at');
								$from_date_options['empty'] = true;
								// For datetime picker need to add class js-datetimepicker
								$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-clone-counter">' . $i . '</span>';
								$out.= '<div class="js-datepicker input no-pad marl-25 starts-left clearfix required datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerNight.SellTicket.' . $key . '.start_date', $from_date_options);
								$out.= '</div></div>';
								$from_date_options['label'] = __l('Ends');
								$out.= '<div class="js-datepicker input no-pad  marl-25 clearfix required datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerNight.SellTicket.' . $key . '.end_date', $from_date_options);
								$out.= '</div></div>';
								$recurring_end_class = ' hide';
								if(!empty($custom_price_pre_night['recurring_day']) && count($custom_price_pre_night['recurring_day'] > 0)){
									$recurring_end_class = '';
								}
								$recurring_day_options = array();
								$recurring_day_options['type'] = 'select';
								$recurring_day_options['multiple'] = 'checkbox';
								$recurring_day_options['options'] = $recurring_day;
								$recurring_day_options['label'] = __l('And repeats on days');
								$recurring_day_options['class'] = 'checkbox no-mar js-repeat-checkbox';
								$recurring_day_options['div'] = 'clearfix checkbox span11 days-list mspace repeat-day-checkbox js-repeat-days';
								$out.= $this->Form->input('CustomPricePerNight.SellTicket.' . $key . '.recurring_day', $recurring_day_options);
								$from_date_options['label'] = __l('Repeat Ends On');
								$out.= '<div class="js-datepicker input no-pad marl-25 starts-left clearfix required datetimepicker-block js-repeat-end'.$recurring_end_class.'"><div class="js-cake-date hide">';
								$out.= $this->Form->input('CustomPricePerNight.SellTicket.' . $key . '.repeat_end_date', $from_date_options);
								$out.= '</div></div>';
								if(isPluginEnabled('Seats')){
									$seat_options['type'] = 'checkbox';
									$seat_options['options'] = array('0' => __l('Enable Seat Booking ?'));
									$seat_options['label'] = __l('Enable Seat Booking ?');
									$seat_options['class'] = 'checkbox no-mar js-seat-enabled';
									$seat_options['div'] = 'clearfix checkbox span11 days-list mspace seating-selection-checkbox';
									$out.= $this->Form->input('CustomPricePerNight.SellTicket.' . $key . '.is_seating_selection', $seat_options);
									$hall_class = 'input select clearfix mspace no-mar js-hall-div hide';
									$partition_class = 'input select clearfix mspace js-partition-div hide';
									$qty_class = 'input text js-qty-div';
									if(!empty($custom_price_pre_night['is_seating_selection'])){
										$hall_class = 'input select clearfix mspace js-hall-div';
										$partition_class = 'input select clearfix mspace no-mar js-partition-div';
										$qty_class = 'input text js-qty-div hide';
									}									
									$out.=$this->Form->input('CustomPricePerNight.SellTicket.' . $key . '.hall_id', array('label' => __l('Halls'), 'empty' => __l('Please Select'), 'class' => 'js-hall-select no-mar','div' => $hall_class));
								}
								$out.= '<div class="js-clone-sub ver-space cost-block-list clearfix">';
								$out.= '<div class="website-block js-field-list-sub sell-ticket-block span16 pull-right clearfix top-space hide">';
								$out.= '<h4 class="text-14 bot-space bot-mspace sep-bot clearfix">' . __l('How much does it cost?') . '</h4>';
								$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-sub-clone-counter">0 </span>';
								$out.= $this->Form->input('CustomPricePerType.' . $key . '.0.name', array(
									'label' => __l('Name'),
									'class' => 'js-price-name-input js-no-pjax',
									'div' => 'input text required'
								));
								$out.= $this->Form->input('CustomPricePerType.' . $key . '.0.description', array(
								'label' => __l('Description'),
								'type' => 'textarea',
								'class' => 'js-price-desc-input js-no-pjax'
								));
								$out.= $this->Form->input('CustomPricePerType.' . $key . '.0.price', array(
								'label' => __l('Price') . ' (' . Configure::read('site.currency') . ')',
								'class' => 'js-ticket-price-input js-no-pjax',
								'info' => __l('Leave blank for free')
								));
								$out.= $this->Form->input('CustomPricePerType.' . $key . '.0.max_number_of_quantity', array(
								'label' => __l('Quantity'),
								'class' => 'js-ticket-price-input js-no-pjax',
								'info' => __l('Leave blank for unlimited Quantity'),
								'div' => $qty_class
								));
								if(isPluginEnabled('Seats')){
									$partitions = array(__l('Please select'));
									if(!empty($this->request->data['CustomPricePerType'][$key][1]['partitions'])){
										$partitions = $this->request->data['CustomPricePerType'][$key][1]['partitions'];
									}
									$out.=$this->Form->input('CustomPricePerType.' . $key . '.0.partition_id', array('label' => __l('Partitions'), 'empty' => __l('Please Select'), 'class' => 'js-partition-select','div' => $partition_class, 'options' => $partitions));
								}
								$from_time_options = array();
								$from_time_options['type'] = 'time';
								$from_time_options['div'] = 'clearfix';
								$from_time_options['div'] = 'input text required';
								$from_time_options['orderYear'] = 'asc';
								$from_time_options['minYear'] = date('Y') -10;
								$from_time_options['maxYear'] = date('Y') +10;
								$from_time_options['label'] = __l('Starts at');
								$from_time_options['empty'] = true;
								// For datetime picker need to add class js-datetimepicker						
								$out.= '<div class="js-t-picker input no-pad   marl-25 starts-left clearfix required datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerType.' . $key . '.0.start_time', $from_time_options);
								$out.= '</div></div>';
								$from_time_options['label'] = __l('Ends');
								$out.= '<div class="js-t-picker input no-pad marl-25 clearfix required datetimepicker-block"><div class="js-cake-date">';
								$out.= $this->Form->input('CustomPricePerType.' . $key . '.0.end_time', $from_time_options);
								$out.= '</div></div>';
								$out.= '</div>';
								$j = 1;
								unset($this->request->data['CustomPricePerType'][$key][0]);
								foreach($this->request->data['CustomPricePerType'][$key] as $sub_key => $custom_price_pre_type) {
									if(!empty($custom_price_pre_type['id'])) {
										$out.= $this->Form->hidden('CustomPricePerType.' . $key . '.' . $sub_key . '.id');
										$out.= $this->Form->hidden('CustomPricePerType.' . $key . '.' . $sub_key . '.partition_old_id');
									}
									if ($j > 1) {
										$out.= '<div class="js-field-list-sub top-space sell-ticket-block clearfix span16 pull-right sell-ticket-clone js-new-clone-sub-' . $j . '">';
									$out.= '<span class="span2 js-website-remove-sub clone-remove pull-right btn pull-right"><i class="cur icon-remove"></i><span class="ver-smspace">' . __l('Remove'). '</span></span>';
									} else {
										$out.= '<div class="clearfix add-block pull-right cur"><span class ="js-add-more-sub js-no-pjax btn pull-right clone-add " data-clone_sub="js-clone-sub" data-field_list_sub="js-field-list-sub"><i class="cur icon-plus"></i><span class="ver-smspace">' . __l('Add more'). '</span></span></div>';
										$out.= '<div class="js-field-list-sub top-space sell-ticket-block clearfix span16 pull-right sell-ticket-clone js-new-clone-sub-' . $j . '">';
									}
									$out.= '<div class="clearfix"></div>';
									$out.= '<span class="label label-important textb show text-11 prop-count pull-left right-mspace js-sub-clone-counter">' . $j . '</span>';
									$out.= $this->Form->input('CustomPricePerType.' . $key . '.' . $sub_key . '.name', array(
										'label' => __l('Name'),
										'class' => 'js-price-name-input js-no-pjax',
										'div' => 'input text required'
									));
									$out.= $this->Form->input('CustomPricePerType.' . $key . '.' . $sub_key . '.description', array(
									'label' => __l('Description'),
									'type' => 'textarea',
									'class' => 'js-price-desc-input js-no-pjax'
									));
									$out.= $this->Form->input('CustomPricePerType.' . $key . '.' . $sub_key . '.price', array(
									'label' => __l('Price') . ' (' . Configure::read('site.currency') . ')',
									'class' => 'js-ticket-price-input js-no-pjax',
									'info' => __l('Leave blank for free')
									));
									$out.= $this->Form->input('CustomPricePerType.' . $key . '.' . $sub_key . '.max_number_of_quantity', array(
									'label' => __l('Quantity'),
									'class' => 'js-ticket-price-input js-no-pjax',
									'info' => __l('Leave blank for unlimited Quantity'),
									'div' => $qty_class
									));
									if(isPluginEnabled('Seats')){
										if(!empty($custom_price_pre_type['partitions'])){
											$partitions = $custom_price_pre_type['partitions'];
										}
										$out.=$this->Form->input('CustomPricePerType.' . $key . '.' . $sub_key . '.partition_id', array('label' => __l('Partitions'), 'empty' => __l('Please Select'), 'class' => 'js-partition-select','div' => $partition_class, 'options' => $partitions));
									}
									$from_time_options = array();
									$from_time_options['type'] = 'time';
									$from_time_options['div'] = 'clearfix';
									$from_time_options['div'] = 'input text required';
									$from_time_options['orderYear'] = 'asc';
									$from_time_options['minYear'] = date('Y') -10;
									$from_time_options['maxYear'] = date('Y') +10;
									$from_time_options['label'] = __l('Starts at');
									$from_time_options['empty'] = true;
									// For datetime picker need to add class js-datetimepicker						
									$out.= '<div class="js-timepicker input no-pad starts-left clearfix required datetimepicker-block marl-25"><div class="js-cake-date">';
									$out.= $this->Form->input('CustomPricePerType.' . $key . '.' . $sub_key . '.start_time', $from_time_options);
									$out.= '</div></div>';
									$from_time_options['label'] = __l('Ends');
									$out.= '<div class="js-timepicker input no-pad  clearfix required datetimepicker-block"><div class="js-cake-date">';
									$out.= $this->Form->input('CustomPricePerType.' .  $key . '.' . $sub_key . '.end_time', $from_time_options);
									$out.= '</div></div>';
									$out.= '</div>';
									$j++;
								}
							$out.= '</div>';
							$out.= '</div>';
							$i++;
							}
							}
					    $out.= '</div>';
					    $out.= '</div>';
					    $out.= '</div>';
					}
						$out.= '</div>';
					}
            }
            if ($this->openFieldset == true) {
                $out.= '</fieldset>';
            }
        }
        return $this->output($out);
    }
    /**
     * Generates appropriate html per field
     *
     * @param array $field Field to process
     * @parram array $custom_options Custom $this->Forminput options for field
     *
     * @return string field html
     * @access public
     */
    function _field($field, $custom_options = array()) 
    {
		$field['label'] = __l($field['label']);
		$field['info'] = __l($field['info']);
		$required = '';
        if ($field['required'] == 1) {
            $required = 'required';
        }
		$agent_class = 'hide';
		$ua = $_SERVER["HTTP_USER_AGENT"];      // Get user-agent of browser
		$safariorchrome = strpos($ua, 'Safari') ? true : false;     // Browser is either Safari or Chrome (since Chrome User-Agent includes the word 'Safari')
		$chrome = strpos($ua, 'Chrome') ? true : false;             // Browser is Chrome
		if($safariorchrome == true AND $chrome == false){ $agent_class = ''; }
        $options = array();
        $out = '';
        if (!empty($field['type'])) {
            $class = '';
            if (empty($this->request->data['UserProfile']['address']) and empty($this->request->data['Item']['address'])) {
                $class = 'hide';
            }
            if (!empty($field['name'])) {
                if ($field['name'] == 'Item.name') {
                    $options['class'] = "js-preview-keyup js-no-pjax {'display':'js-name'}";
                }
                if ($field['name'] == 'Item.needed_amount') {
                    $options['info'] = sprintf(__l('Minimum Amount: %s%s <br/> Maximum Amount: %s') , Configure::read('site.currency') , $this->Html->cCurrency(Configure::read('Item.minimum_amount')) , Configure::read('site.currency') . $this->Html->cCurrency(Configure::read('Item.maximum_amount')));
                }
                if ($field['name'] == 'country_id' || $field['name'] == 'State.name') {
                    $options['class'] = 'location-input';
                }
                if ($field['name'] == 'Form.address' || $field['name'] == 'Item.address' || $field['name'] == 'Request.address') {
                    $out.= '<div class="profile-block clearfix"><div class="mapblock-info mapblock-info1 pr"><div class="clearfix address-input-block required pull-left">';
                    $options['class'] = 'js-preview-address-change';
                    $options['id'] = 'ItemAddressSearch';
                }
				if ($field['name'] == 'Item.min_number_of_ticket') {
					$out.= '<div class="js-min-no-ticket hide">';
				}
                if ($field['name'] == 'Attachment.filename') {
					$allowedExt = implode(', ', Configure::read('photo.file.allowedExt'));
					$out.= '<div class="clearfix ver-space bot-mspace"><div class="alert alert-info">';
					$message = '';
					if (!Configure::read('item.is_enable_edit_item_image')):
						$message .= sprintf(__l('Shared %s images cannot be delete once uploaded. So please be sure about %s images before complete this step.'), Configure::read('item.alt_name_for_item_singular_small'), Configure::read('item.alt_name_for_item_singular_small')) . ' ';
					endif;
					$message .= '<p>' . __l('The maximum file size for uploads is 8 MB per file.') . '</p>';
					$message .= '<p>' . __l('File types that can be uploaded are: jpg, gif, png, bmp.') . '</p>';
					$message .= '<p>' . __l('You can "Browse or Drag and Drop" one, multiple, or an entire Folder into this area to upload your image into the site. (some browser restriction apply)') . '</p>';
					$out.= $message;
					$out.= '</div>';
					$out.= '<div class="picture">';
					$out.= '<div class="js-attachment-files cur dragdrop dc space"><span class="show space text-46">'. __l('Drop files here') . '</span><span class="show text-16">('. __l('or click') . ')</span></div>';
					$out.= '<div class="input file required">';
					$out.= '<span class="fileinput-button '.$agent_class.'">';
					$success_url = Router::url(array('controller' => 'items', 'action' => 'update_redirect'), true);
					if(!empty($this->request->data['Item']['id']) && !empty($this->request->data['Item']['slug'])) {
						$success_url = Router::url(array('controller' => 'items', 'action' => 'view', $this->request->data['Item']['slug']), true);
					}
					$out.= $this->Form->input('Attachment.filename. ', array('type' => 'file', 'label' => false, 'div' => false, 'class' => 'fileUpload', 'multiple' => 'multiple', 'data-allowed-extensions' => $allowedExt, 'data-maximum-number-of-photos' => Configure::read('item.max_upload_photo'), 'data-success-url' => $success_url));
					$out.= '</span>';
					$out.= '</div>';
					$out.= '<div class="time-desc datepicker-container clearfix">
	<table role="presentation" class="table table-striped">
	  <tbody class="files"></tbody></table>';
	  $out.= '<div class="js-sortable-attachments js-image-block item-image-block">';
		if(!empty($this->request->data['Attachment'])){
			for($p = 0; $p < count($this->request->data['Attachment']); $p++) {	  
				if(!empty($this->request->data['Attachment']) && !empty($this->request->data['Attachment'][$p]['filename'])) {
				$out.= '<div id="js-delete-'.$this->request->data['Attachment'][$p]['id'].'" class="item-image-innerblock sep img-rounded span4 space pr">'. $this->Form->input('Attachment.'.$this->request->data['Attachment'][$p]['id'].'.id', array('type' => 'hidden', 'value' => $this->request->data['Attachment'][$p]['id'])) .'
					<div class="clearfix">
						<span class="js-delete-attach pa image-close" data-remove_part="js-delete-'.$this->request->data['Attachment'][$p]['id'].'" data-error="js-error-message-'.$this->request->data['Attachment'][$p]['id'].'" data-url="'.Router::url(array('controller'=> 'items', 'action' => 'attachment_delete', $this->request->data['Attachment'][$p]['id']), true).'">
							<i class="icon-remove-sign cur text-18 orangec"></i>
						</span>
					</div>
					<div class="space">'.$this->Html->showImage('Item', $this->request->data['Attachment'][$p], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['Attachment'][$p]['filename'], false)), 'title' => $this->Html->cText($this->request->data['Attachment'][$p]['filename'] , false))) .'</div>
					<div id="js-error-message-'.$this->request->data['Attachment'][$p]['id'].'" class="clearfix hor-space"></div>'.
					$this->Form->input('Attachment.'.$this->request->data['Attachment'][$p]['id'].'.description', array('label' => false, 'type' => 'text', 'placeholder' => 'Caption', 'value' => $this->request->data['Attachment'][$p]['description'])) . '</div>';
				}	  
			}
		}
	$out.= <<<EOT
	</div>
	<!-- The template to display files available for upload -->
	<script id="template-upload" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-upload fade">
			<td>
				<span class="preview"></span>
			</td>
			<td>
				<p class="name">{%=file.name%}</p>
				{% if (file.error) { %}
					<div><span class="label label-danger">Error</span> {%=file.error%}</div>
				{% } %}
			</td>
			<td>
				<p class="size">{%=o.formatFileSize(file.size)%}</p>
				{% if (!o.files.error) { %}
					<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="bar bar-success" style="width:0%;"></div></div>
				{% } %}
			</td>
			<td>
				{% if (!o.files.error && !i && !o.options.autoUpload) { %}
					<button class="btn btn-primary start hide">
						<span>Start</span>
					</button>
				{% } %}
			</td>
		</tr>
	{% } %}
	</script>
	<!-- The template to display files available for download -->
	<script id="template-download" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-download fade">
			<td>
				<span class="preview">
					{% if (file.thumbnailUrl) { %}
						<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
					{% } %}
				</span>
			</td>
			<td>
				<p class="name">
					{% if (file.url) { %}
						<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
					{% } else { %}
						<span>{%=file.name%}</span>
					{% } %}
				</p>
				{% if (file.error) { %}
					<div><span class="label label-danger">Error</span> {%=file.error%}</div>
				{% } %}
			</td>
			<td>
				<span class="size">{%=o.formatFileSize(file.size)%}</span>
			</td>
			<td>
				{% if (file.deleteUrl) { %}
					<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
						<i class="glyphicon glyphicon-trash"></i>
						<span>Delete</span>
					</button>
				{% } %}
			</td>
		</tr>
	{% } %}
	</script>
</div>
EOT;
					$out.= '</div>';
					$out.= '</div>';
                }
                if ($field['name'] == 'Form.address1' || $field['name'] == 'Item.address1' || $field['name'] == 'Request.address1') {
                    $out.= '<div id="js-geo-fail-address-fill-block" class="' . $class . '"><div class="clearfix"><div class="map-address-left-block address-input-block">';
                    $options['class'] = 'js-preview-address-change';
                    $options['id'] = 'js-street_id';
                    $out.= '</div>';
                }
                if ($field['name'] == 'Item.description') {
                    $options['class'] = 'span16 descblock js-post-item-description';
                    $options['rows'] = false;
                    $options['cols'] = false;
                }
                if ($field['name'] == 'Item.country_id' || $field['name'] == 'Form.country_id' || $field['name'] == 'Request.country_id') {
					$options['id'] = 'js-country_id';
                }
				if ($field['name'] == 'Item.is_additional_fee_to_buyer' || $field['name'] == 'Form.is_additional_fee_to_buyer' || $field['name'] == 'Request.is_additional_fee_to_buyer') {
					$options['id'] = 'js-additional-fee-to-buyer';
                }
                if (!empty($field['class'])) {
                    $options['class'] = $field['class'];
                }
                if ($field['name'] == 'Item.feed_url') {
                    $options['class'] = 'js-remove-error';
                }
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
                        'select',
                        'checkbox',
                        'radio'
                    ))) {
                        if (!empty($field['options']) && !is_array($field['options'])) {
                            $field['options'] = str_replace(', ', ',', $field['options']);
							if(!empty($field['is_deletable'])) {
								$a = $b = $this->explode_escaped(',', $field['options']);
								$field['options'] = array_combine($a, $b);
							} else {
								$field['options'] = $this->explode_escaped(',', $field['options']);
							}
                        }
                        if ($field['type'] == 'checkbox') {
                            if (count($field['options']) > 1) {
                                $options['type'] = 'select';
                                $options['multiple'] = 'checkbox';
                                $options['options'] = $field['options'];
								$options['div'] = 'input checkbox pull-left hor-smspace no-mar span20';
								$options['class'] = 'checkbox span5 no-mar';
                            } else {
								if($field['name'] == 'Item.is_user_can_request' || $field['name'] == 'Item.is_have_definite_time') {
									$options['value'] = 0;
								} else {
									$options['value'] = $field['name'];
								}
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
                        $options['label'] = $field['label'];
                        if ($field['type'] == 'radio') {
                            $options['legend'] = $field['label'];
                        }
                    }
                    if ($field['type'] == 'file') {
                        if ($field['name'] != 'Attachment.filename') {
                            $options['class'] = (!empty($options['class'])) ? $options['class'] : '';
                            $options['class'].= " {'UmimeType':'*', 'Uallowedsize':'5','UallowedMaxFiles':'1'}";
                        }
                    }
                    if ($field['type'] == 'radio') {
                        $options['div'] = true;
                        $options['legend'] = false;
                        $options['multiple'] = 'radio';
                    }
                    if ($field['type'] == 'slider') {
                        for ($num = 1; $num <= 100; $num++) {
                            $num_array[$num] = $num;
                        }
                        $options['div'] = 'input select slider-input-select-block clearfix' . ' ' . $required;
                        $options['options'] = $num_array;
                        $options['type'] = 'select';
                        $options['class'] = 'js-uislider';
                        $options['label'] = false;
                        $i = 0;
                        if (!empty($field['options'])) {
                            foreach($field['options'] as $value) {
                                if ($i == 0) {
                                    $options['before'] = '<div class="clearfix"><span class="grid_left uislider-inner">' . $value . '</span>';
                                } else {
                                    $options['after'] = '<span class="grid_left uislider-right">' . $value . '</span></div>';
                                }
                                $i++;
                            }
                        }
                        $out.= $this->Html->div('label-block slider-label ' . $required, $field['label']);
                    }
                    if ($field['type'] == 'date') {
                        $options['div'] = $required;
                        $options['orderYear'] = 'asc';
                        $options['minYear'] = date('Y') -10;
                        $options['maxYear'] = date('Y') +10;
                    }
                    if ($field['type'] == 'datetime') {
                        $options['div'] = 'clearfix';
                        $options['div'] = 'input text ' . ' ' . $required;
                        $options['orderYear'] = 'asc';
                        $options['minYear'] = date('Y') -10;
                        $options['maxYear'] = date('Y') +10;
                    }
                    if ($field['type'] == 'time') {
                        $options['div'] = 'clearfix';
                        $options['div'] = 'input text js-time' . ' ' . $required;
                        $options['orderYear'] = 'asc';
                        $options['timeFormat'] = 12;
                        $options['type'] = 'time';
                    }
                    if ($field['type'] == 'color') {
                        $options['div'] = 'input text clearfix' . ' ' . $required;
                        $options['class'] = 'js-colorpick';
                        if (!empty($field['info'])) {
                            $info = $field['info'] . ' <br>'.__l('Comma separated RGB hex code. You can use color picker.');
                        } else {
                            $info = __l('Comma separated RGB hex code. You can use color picker.');
                        }
                        $options['info'] = $info;
                        $options['type'] = 'text';
                    }
                    if ($field['type'] == 'thumbnail') {
                        $options['div'] = 'clearfix';
                        $options['div'] = 'input text' . ' ' . $required;
                    }
                    if (!empty($field['default']) && empty($this->data['Form'][$field['name']])) {
                        $options['value'] = $field['default'];
                    }
                    if ($field['type'] == 'text') {
                        $options['div'] = 'clearfix';
                        $options['div'] = 'input text' . ' ' . $required;
                    }
                    if ($field['type'] == 'textarea') {
                        $options['div'] = 'clearfix';
                        $options['div'] = 'input textarea' . ' ' . $required;
                    }
                    if ($field['type'] == 'select') {
                        $options['div'] = 'clearfix';
                        $options['div'] = 'input select' . ' ' . $required;
                        if (!empty($field['multiple']) && $field['multiple'] == 'multiple') {
                            $options['div'].= ' multi-select';
                        }
                    }
                    $options = Set::merge($custom_options, $options);
                    if ($field['type'] == 'date' || $field['type'] == 'datetime' || $field['type'] == 'time') {
                        if ($field['name'] == 'Item.item_end_date') {
                            $date_display = date('Y-m-d', strtotime('+' . Configure::read('maximum_item_expiry_day') . ' days'));
                        } else {
                            $date_display = date('Y-m-d');
                        }
                        if ($field['type'] == 'datetime') {
                            $out.= '<div class="input js-datetimepicker clearfix ' . $required . '"><div class="js-cake-date">';
                        } elseif($field['type'] == 'time') {
                            $out.= '<div class="input js-time clearfix ' . $required . '"><div class="js-cake-date">';
                        } else {
                            $out.= '<div class="input js-datetime clearfix ' . $required . '"><div class="js-cake-date">';
                        }
                    }
                    if ($field['type'] == 'radio') {
                        $out.= '<div class="input select radio-block clearfix">';
                        $out.= '<label class="label-block pull-left ' . $required . '" for="' . $field['name'] . '">' . $field['label'] . '</label>';
                    }
                    if ($field['name'] == 'Item.short_description') {
                        $options['class'] = 'js-preview-keyup js-no-pjax js-description-count {"display":"js-short-description","field":"js-short-description-count","count":"' . Configure::read('Item.item_short_description_length') . '"}';
                        $options['info'] = $field['info'] . ' ' . '<span class="character-info">' . __l('You have') . ' ' . '<span id="js-short-description-count"></span>' . ' ' . __l('characters left') . '</span>';
                    }
                    if (!empty($field['name']) && $field['name'] == 'Item.description') {
                        $options['label'] = __l('Description');
                        $options['info'] = false;
                    }
                    if (!empty($field['name']) && $field['name'] == 'Item.needed_amount') {
                        $options['label'] = __l('Needed amount') . ' (' . Configure::read('site.currency') . ')';
                    }
					if (!empty($field['name']) && $field['name'] == 'Item.title') {
                        $options['label'] = __l('Name');
                    }
					if (!empty($field['name']) && $field['name'] == 'Item.address') {
                        $options['label'] = __l('Address');
                    }
					if ($field['type'] == 'checkbox') {
						if (count($field['options']) > 1) {
							$out .= '<div class="amenities-list"><div class="clearfix"><div class="pull-left span4 span4-sm no-mar ver-space dr mob-dl"><span class="hor-space show">' . $field['label']  . '</span></div>';
							$options['label'] = false;
						}
					}
                    if ($field['name'] != 'Attachment.filename') {
						$out.= $this->Form->input($field['name'], $options);
					}
					if ($field['type'] == 'checkbox') {
						if (count($field['options']) > 1) {
							$out.= '</div></div>';
						}
					}
                    if (!empty($field['name']) && $field['name'] == 'Item.description') {
                        $out.= '<span class="info grayc"><i class="grayc icon-info-sign"></i> ' . __l('Entered description will display in view page') . '</span>';
                    }
                    if ($field['type'] == 'date' || $field['type'] == 'datetime' || $field['type'] == 'time') {
                        $out.= '</div></div>';
                    }
                    if ($field['type'] == 'radio') {
                        $out.= '</div>';
                    }
                    if (!empty($field['name']) && $field['name'] == 'City.name') {
						// todo: this is not correct fix, need to fix it [admin_preview cond]
						if($this->request->action != 'admin_preview'){
							$out.= '</div></div></div><div class="pull-right js-side-map-div ' . $class . '"><h5>' . __l('Point Your Location') . '</h5><div class="js-side-map"><div id="js-map-container"></div><span>' . __l('Point the exact location in map by dragging marker') . '</span></div></div><div id="mapblock"><div id="mapframe"><div id="mapwindow"></div></div></div></div></div>';
						}
                    }
					if (!empty($field['name']) && ($field['name'] == 'Item.is_additional_fee_to_buyer' || $field['name'] == 'Form.is_additional_fee_to_buyer' || $field['name'] == 'Request.is_additional_fee_to_buyer')) {
						$out.= '<div class="js-additional-fee-block hide">';
						$out.= $this->Form->input('Item.additional_fee_name', array('type' => 'text'));
						$out.= $this->Form->input('Item.additional_fee_percentage', array('type' => 'text'));
						$out.= '</div>';
					}
					if ($field['name'] == 'Item.min_number_of_ticket') {
						$out.= '</div>';
					}
                    break;
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
}
?>