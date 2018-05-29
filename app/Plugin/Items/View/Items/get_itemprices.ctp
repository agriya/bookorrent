<?php 
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
if(!empty($end_time[0])){
	$e_time = date('h:i A', mktime($end_time[0], $end_time[1], $end_time[2], 0, 0, 0));	
}
echo $this->Form->input('ItemUser.custom_price_per_night_id', array('type'=>'hidden', 'value' => $custom_price_per_night['CustomPricePerNight']['id']));
if(isset($is_parent) && !empty($is_parent)) {
	echo $this->Form->input('ItemUser.parent_id', array('type'=>'hidden', 'value' => $custom_price_per_night['CustomPricePerNight']['parent_id']));
	echo $this->Form->input('ItemUser.start_date', array('type'=>'hidden', 'value' => $custom_price_per_night['CustomPricePerNight']['start_date']));
	echo $this->Form->input('ItemUser.end_date', array('type'=>'hidden', 'value' => $custom_price_per_night['CustomPricePerNight']['end_date']));
}
?>
<div class="space clearfix">
<div class="clearfix bot-mspace">
	<span class="img-rounded sep calendar-list hor-smspace pull-left graydarkc"> 
		<span class="show well no-mar hor-space"><?php echo $month; ?></span> 
		<span class="show textb text-24"> <?php echo $day_num; ?> </span> 
		<?php 
			if(!empty($custom_price_per_night['CustomPricePerNight']['total_available_count'])) { 
				$total_booked_percentage = ($custom_price_per_night['CustomPricePerNight']['total_booked_count'] * 100) / $custom_price_per_night['CustomPricePerNight']['total_available_count']; 
		?>
		<span class="progress show"> 
			<span class="bar" style="width: <?php echo $total_booked_percentage; ?>%;"></span> 
		</span> 
		<?php 
			}
		?>
		<span class="show sep-top top-smspace"><?php echo $day_char; ?></span> 
	</span> 
	<span class="timing-details span no-mar clearfix"> 
		<span class="pull-left dl grayc text-11"> 
			<span class="show ver-smspace clearfix"><?php echo date('M j, Y', mktime(0, 0, 0, $start_date[1], $start_date[2], $start_date[0])) . ' ' . __l('to') . ' ' . date('M j, Y', mktime(0, 0, 0, $end_date[1], $end_date[2], $end_date[0])); ?></span> 
			<?php if(!empty($s_time) || !empty($e_time)){ ?>
				<span class="show ver-smspace clearfix">
					<?php 
					if(!empty($s_time)){
						echo $s_time;
					}
					if(!empty($s_time) && !empty($e_time)){
						echo ' - ';
					}
					if(!empty($e_time)){
						echo $e_time;
					} ?>
				</span>					
			<?php } ?>			
			<span class="show ver-smspace">
			<?php 
			if(!empty($custom_price_per_night['Item']['min_number_of_ticket'])){
				echo '+'.($custom_price_per_night['Item']['min_number_of_ticket'] - $custom_price_per_night['CustomPricePerNight']['total_booked_count']).' '.__l('Needed'); 
			}
			?>
			</span> 
			<?php if(isPluginEnabled('Seats') && !empty($custom_price_per_night['Hall']['name'])) { ?>
				<span class="show ver-smspace">
					<?php echo __l('Venue') . ': ' . $custom_price_per_night['Hall']['name']; ?>
					</span>
			<?php } ?>
		</span> 
		<span class="pull-right ver-mspace ver-space dr"> 
			<span class="textb text-14">
				<?php 
				if(!empty($custom_price_per_night['CustomPricePerNight']['minimum_price'])) {
					echo $this->Html->siteCurrencyFormat($custom_price_per_night['CustomPricePerNight']['minimum_price']);
				} else {
					echo __l('Free');
				}
				?>
			</span> 
		</span> 
	</span>
</div>
<?php 
	$total_sold_cnt = 0;
	$total_cnt = count($custom_price_per_night['CustomPricePerType']);
	if (!empty($custom_price_per_night['CustomPricePerType'])){ 
?>
<table class="table table-striped table-bookit table-hover sep no-mar span24">
	<thead>
		<tr>
			<th class="graydarkerc span18 dc"><?php echo __l('Ticket'); ?></th>
			<th class="graydarkerc span4 dc"><?php echo __l('Partition'); ?></th>
			<th class="graydarkerc dc"><?php echo __l('Qty'); ?></th>
		</tr>
	</thead>
	<tbody>
<?php
	$inc = 1;
	foreach ($custom_price_per_night['CustomPricePerType'] As $custom_price_per_type) {
		if(!empty($custom_price_per_type['partition_id'])) {
			if(isset($is_parent)){
				$balance_quantity = $custom_price_per_type['available_seat_count'] + $custom_price_per_type['booked_quantity']+$custom_price_per_type['blocked_count']+$custom_price_per_type['waiting_for_acceptance_count'];
			} else {
				$balance_quantity = $custom_price_per_type['available_seat_count'];
			}
		} else {
			if(isset($is_parent) || $custom_price_per_type['max_number_of_quantity'] == 0){
				$balance_quantity = 50;
			} else if(!empty($custom_price_per_type['max_number_of_quantity']) && $custom_price_per_type['max_number_of_quantity'] > 0) {
				$balance_quantity = $custom_price_per_type['max_number_of_quantity'] - $custom_price_per_type['booked_quantity'];
			}
		}
		$options = array();
		$options[0] = __l('Qty');
		for ($i = 1; $i <= $balance_quantity; $i++) {
			$options[$i] = $i;
		}
		$disabled = '';
		if(isPluginEnabled('Seats') && !empty($custom_price_per_night['CustomPricePerNight']['is_seating_selection'])){ 
			if($inc == 1){
				$checked = 'checked';
				$disabled = '';
			} else {
				$checked = '';
				$disabled = 'disabled';
			}
		}
	?>
		<tr>
			<td class="span12">
				<span class="graydarkerc">
					<span class="js-bootstrap-tooltip" title="<?php echo $this->Html->cText($custom_price_per_type['name'], false); ?>">
			<?php if(isPluginEnabled('Seats') && !empty($custom_price_per_night['CustomPricePerNight']['is_seating_selection'])){ 
					$booking_option = array($this->Html->cText($this->Text->truncate($custom_price_per_type['name'], 15)));
						echo $this->Form->input('ItemUser.custom_price_per_type.' . $custom_price_per_type['id'], array('legend' => false, 'label'=> true, 'type' => 'radio', 'options' => $booking_option, 'hiddenField' => false, 'div' => 'input radio no-mar', 'class' => 'show ver-mspace js-select-custom-price-per-type', 'checked' => $checked));
						echo $this->Form->input('ItemUser.is_seating_selection', array('type'=>'hidden', 'value' => $custom_price_per_night['CustomPricePerNight']['is_seating_selection']));
				} else {
					echo $this->Html->cText($this->Text->truncate($custom_price_per_type['name'], 15)); 
				} ?>
					</span>
					
				</span>
				<div>
					<span class="graylightc"><?php echo $this->Html->cTime($custom_price_per_type['start_time']).' - '.$this->Html->cTime($custom_price_per_type['end_time']); ?></span>
				</div>
				<div class="sfont no-mar">
					<?php if(!empty($custom_price_per_type['min_number_per_order'])) { ?>
					<span><span class="textb"><?php echo __l('Min'); ?>:</span><span><?php echo $this->Html->cInt($custom_price_per_type['min_number_per_order'], false); ?></span></span>
					<?php } ?>
					<?php if(!empty($custom_price_per_type['max_number_per_order'])) { ?>
					<span class="smspace"><span class="textb"><?php echo __l('Max'); ?>:</span><span><?php echo $this->Html->cInt($custom_price_per_type['max_number_per_order'], false); ?></span></span>
					<?php } ?>
				</div>				
			</td>
			
			<td class="dl">
				<?php if(isPluginEnabled('Seats') && !empty($custom_price_per_type['Partition']['name'])){
					echo $custom_price_per_type['Partition']['name']; 
				} ?>
				<span class="graylightc">(<?php echo (!empty($custom_price_per_type['price']) && $custom_price_per_type['price'] > 0) ? $this->Html->siteCurrencyFormat($custom_price_per_type['price']) : __l('Free'); ?>)</span>
			</td>
			
			<td class="dc span6">
			<?php if($balance_quantity == 0){ 
				$total_sold_cnt ++;
			?>
				<div class="textb ver-space orangec ver-smspace"><?php echo __l('Sold Out');?></div>
			<?php }  else { ?>
				<?php echo $this->Form->input('ItemUser.custom_price_per_type.' . $custom_price_per_type['id'], array('type' => 'select', 'options' => $options, 'legend' => false, 'label' => false, 'div' => 'input select no-mar', 'class' => 'span2 text-12', 'disabled' => $disabled)); ?>
			<?php } ?>
			</td>
		</tr>
<?php
		$inc++;
	}
?>
	</tbody>
</table>
<?php } ?>
<div class="top-mspace">
	<div class="pull-left bot-mspace marl-top">
		<?php echo $this->Html->link(__l('Back'), array('controller' => 'items', 'action' => 'get_itemtime', 'item_id' => $custom_price_per_night['CustomPricePerNight']['item_id']), array('data-item_id' => $custom_price_per_night['CustomPricePerNight']['item_id'], 'title' => __l('Back'), 'class' => 'js-no-pjax js-list-back-tab btn btn-default btn-large textb'));?>
	</div>
	<?php if($total_sold_cnt < $total_cnt){ ?>
	<div class="submit pull-right top-mspace">
		<?php echo $this->Form->submit(__l('Book It'),array('name' => 'data[ItemUser][bookit]', 'div' => false, 'class'=>'show btn btn-large btn-primary textb')); ?>
	</div>
	<?php } ?>
</div>
</div>