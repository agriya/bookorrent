<ul id="calander-block" class="js-custom-night-block unstyled scheduled-list">
<?php
	if (!empty($custom_price_per_nights)) {
		$k = 0;
		foreach ($custom_price_per_nights As $custom_price_per_night) {
			$start_time = explode(':', $custom_price_per_night['CustomPricePerNight']['start_time']);
			$end_time = explode(':', $custom_price_per_night['CustomPricePerNight']['end_time']);
			$start_date = explode('-', $custom_price_per_night['CustomPricePerNight']['start_date']);
			$end_date = explode('-', $custom_price_per_night['CustomPricePerNight']['end_date']);
			$month = date('M', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			$day_char = date('D', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			$day_num = date('d', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0]));
			if(!empty($start_time[0])){
				$s_time = date('h:i A', mktime($start_time[0], $start_time[1], $start_time[2], 0, 0, 0));
			}
			if(!empty($end_time[0])) {
				$e_time = date('h:i A', mktime($end_time[0], $end_time[1], $end_time[2], 0, 0, 0));
			}
?>
	<li class="space row no-mar sep-top sep-bot">
		<?php
			$option_value = '';
			$option_value .= '<span class="img-rounded sep calendar-list hor-smspace pull-left graydarkc dc">
				<span class="show well no-mar hor-space">'. $month . '</span>
				<span class="show textb text-24"> '.  $day_num . ' </span>';
			if(!empty($custom_price_per_night['CustomPricePerNight']['total_available_count'])) { 
				$total_booked_percentage = ($custom_price_per_night['CustomPricePerNight']['total_booked_count'] * 100) / $custom_price_per_night['CustomPricePerNight']['total_available_count'];	
				$option_value .= '<span class="progress show"> <span class="bar" style="width: '. $total_booked_percentage .'%;"></span> </span>';
			} 
			$option_value .= '<span class="show sep-top">' . $day_char . '</span>
			</span>
			<span class="timing-details span no-mar clearfix"> 
				<span class="pull-left dl grayc text-11"> 
					<span class="show ver-smspace clearfix">' . date('M j, Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0])) . ' ' . __l('to') . ' ' . date('M j, Y', mktime(0, 0, 0, $end_date[1], $end_date[2], $end_date[0])) .'</span>' ;
				if(isPluginEnabled('Seats') && !empty($custom_price_per_night['Hall']['name'])) { 
					$option_value .='<span class="show ver-smspace">';
					$option_value .= __l('Venue') . ': ' . $custom_price_per_night['Hall']['name'];
					$option_value .= '</span>';
				}
			$option_value .= '	</span> 
				<span class="pull-right ver-mspace ver-space dr"> 
					<span class="textb text-14">';
			if(!empty($custom_price_per_night['CustomPricePerNight']['minimum_price'])) {
				$option_value .= $this->Html->siteCurrencyFormat($custom_price_per_night['CustomPricePerNight']['minimum_price']);
			} else {
				$option_value .= __l('Free');
			}
			$option_value .= '</span> 
				</span> 
			</span>';
			if(!empty($custom_price_per_night['CustomPricePerNight']['id'])) {
				$options = array($custom_price_per_night['CustomPricePerNight']['id'] => $option_value);
			} else {
				$options = array($custom_price_per_night['CustomPricePerNight']['parent_id'] .'-'. $custom_price_per_night['CustomPricePerNight']['start_date'] => $option_value);
			}
			echo $this->Form->input('ItemUser.custom_price_per_night_id', array('id' => 'ListCustomPricePerNightId', 'legend' => false, 'label'=> true, 'type' => 'radio', 'options' => $options, 'hiddenField' => false, 'div' => 'input radio no-mar', 'class' => 'show ver-mspace js-select-custom-price-per-night')); 
			
		?>
	</li>
<?php 
	$k++;
	if($k == 3) {
		break;
	}
		}
	} else {?>
		<li class="space row no-mar sep-top sep-bot"><?php echo __l('No Availabilities');?></li>
	<?php }
?>
</ul>
<?php if(count($custom_price_per_nights) > 3) { ?>
	<a class="more-link blackc sfont no-under js-calendar-tab cur" title="<?php echo __l('More') ?>"><i class="icon-double-angle-down"></i><span><?php echo __l('More') ?></span></a>
<?php } ?>