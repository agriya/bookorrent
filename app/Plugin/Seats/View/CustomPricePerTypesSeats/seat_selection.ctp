<?php /* SVN: $Id: $ */ ?>
<!-- BEGIN: TICKET BOOKING -->    
<div class="container">
	<div class = "clearfix sep-bot">
		<div class = "span11">
			<h2 class="ver-space top-mspace text-32"> <?php echo __l('Seat Selection'); ?> </h2>
		</div>
		<div class="span5 right-timer">
			<div class="clock seat-clock" data_url="<?php echo $url; ?>" data_time="<?php echo $total;?>"></div>
		</div>
		<div class="span1">
			<i class="icon-info-sign js-bootstrap-tooltip text-16" title="<?php echo __l('Time remaining to complete booking'); ?>"></i>
		</div>
	</div>
	<div class="ver-space"></div>
	<div class="row">
		<div class="pull-left">
			<span class="label dc span9 ver-space no-round"><span class="dc span9 ver-space no-round text-14 whitec textb"> <?php echo  $this->Html->cText($itemUser['Item']['title']); ?> </span></span>
		</div>
		<div class="pull-right span15 no-mar no-pad seatselect-title">
			<ul class="inline unstyled btn btn-default pad-tb no-btn-default">
				<li class="span5 ver-space no-bor dc no-mar"><span class="text-14 textb dc "> <?php echo  $this->Html->cText($itemUser['CustomPricePerTypeItemUser'][0]['CustomPricePerType']['name']); ?> </span></li>
				<li class="span5 ver-space no-bor dc no-mar"><span class="text-14 textb dc "> <?php echo $this->Html->cDate($itemUser['CustomPricePerNight']['start_date']); ?> </span></li>
				<li class="span5 ver-space no-bor dc no-mar"><span class="text-14 textb dc "> <?php echo $this->Html->cTime($itemUser['CustomPricePerTypeItemUser'][0]['CustomPricePerType']['start_time']) .' - '. $this->Html->cTime($itemUser['CustomPricePerTypeItemUser'][0]['CustomPricePerType']['end_time']); ?> </span></li>
			</ul>
		</div>
	</div>
	
	<div class="ver-space"></div>
	<div class="row">
		<div class="span24 dc">
			<div class="ticket-info">
			
				<div class="span8 ver-space">
					<ul class="unstyled">
						<li class="text-14 span6 htruncate"><span class="span3 dr"><?php echo __l('Hall');?> :</span><span id="ticket_class" class="span2 dl grayc"><?php echo $partition['Hall']['name']; ?> </span></li>
						<li class="text-14 span6 htruncate"><span class="span3 dr"><?php echo __l('Partition');?> :</span><span id="ticket_class" class="span2 dl grayc"><?php echo $partition['name']; ?> </span></li>
					</ul>
				</div>
				
				<div class="span8 ver-space">
					<ul class="unstyled">
						<li class="text-14 dr span6"> <?php echo __l('Ticket Cost');?> :<span id="ticket_cost" class="grayc hor-space"><?php echo $itemUser['CustomPricePerTypeItemUser'][0]['total_price']; ?></span></li>
						<li class="text-14 dr span6"> <?php echo __l('Ticket Amount');?> :<span id="total" class="grayc hor-space"><?php echo $itemUser['CustomPricePerTypeItemUser'][0]['price']; ?></span></li>
					</ul> 
				</div>
				
				<div class="dc span7 ver-space">
					<ul class="unstyled">
						<li class="text-14 dr span6"> <?php echo __l('Requested seats');?> :<span id="js-req_seats" class="grayc hor-space"><?php echo $itemUser['CustomPricePerTypeItemUser'][0]['number_of_quantity']; ?></span></li>
						<li class="text-14 dr span6"> <?php echo __l('Selected seats');?> :<span id="sel_seats" class="grayc hor-space"><?php echo $reserved_titcket; ?></span></li>
					</ul>
				</div>
			</div>
			<div class="ver-space"></div>
			<div class="row">
				<div class="span24 dc" style="width:80%;float:none;margin:auto;">
					<?php
						$cur_time = strtotime(date('Y-m-d H:i:s'));
						if($this->Session->read('SeatBlockTime') != null){
							$cur_time = $this->Session->read('SeatBlockTime');
						}
						echo $this->Form->create('CustomPricePerTypesSeat', array('class' => 'form-horizontal' ,'id'=>'seatPayment', 'action'=>'booking'));
							echo $this->Form->input('item_id', array('type' => 'hidden', 'id'=>'itemId', 'value' => $itemUser['Item']['id']));
							echo $this->Form->input('item_user_id', array('type' => 'hidden','id'=>'itemUserId', 'value' => $itemUser['ItemUser']['id']));
							echo $this->Form->input('seat_ids', array('type' => 'hidden', 'id'=>'save_sel_seats'));
							echo $this->Form->input('block_time', array('type' => 'hidden', 'id'=>'save_sel_seats', 'value' => $cur_time));
							echo $this->Form->submit(__l('Proceed to Pay'), array('class' => 'btn btn-primary img-rounded payOrder', 'disabled'=>(($itemUser['CustomPricePerTypeItemUser'][0]['number_of_quantity'] > $reserved_titcket) ? "disabled" : "")));
							echo $this->Html->link(__l('Cancel Booking'), array('controller' => 'item_users', 'action' => 'delete', $itemUser['ItemUser']['id'], 'type' => 'cancel_booking'), array('type'=>'button', 'class' => 'btn btn-default img-rounded hor-mspace'));
							
					?>
					
					<?php echo $this->Form->end(); ?>
					<div class="ver-space"></div>
					<?php 
						if($partition['stage_position'] == ConstStagePosition::Top) {
							echo $this->Html->image('top-bor.png', array('alt' => __l('stage'))); 
					?>
							<span class="dc clearfix textb"> <?php echo __l('Screen');?> </span>
					<?php } ?>
					<div class="ver-space"></div>
					<div class="ver-space"></div>
					<?php 
						$defaultwidth = "78%";
						if($partition['no_of_columns'] < 20){	
							$defaultwidth = ($partition['no_of_columns'] + 1) * 3.1 . "em";
						}
						$width = ($partition['no_of_columns'] + 1) * 3.1 . "em";
					?>
					<div class="seat-grid" style="width:<?php echo $defaultwidth;?>; padding:0px 0px 20px 0px; overflow-x:scroll;">
						<div style="width: <?php echo $width; ?>">
							<div id="seat-map" class="dc"> </div>
						</div>
					</div>
					<div class="ver-space"></div>
					<div id="legend" class="dc clearfix"></div>
					
					<div class="ver-space"></div>
					<div class="ver-space"></div>
					<?php 
						if($partition['stage_position'] == ConstStagePosition::Bottom) {
					?>
							<span class="dc clearfix textb">  <?php echo __l('Screen');?> </span>			
					<?php 	echo $this->Html->image('bottom-bor.png', array('alt' => 'stage')); 
						}
					?>
					<div class="ver-space"></div>
				</div>
			</div>			
			<div class="ver-space"></div>
		</div>
	</div>
</div>
<!-- END: TICKET BOOKING -->
<script>
	var seat_map = [<?php echo $seat_map;?>];
	var available_arr = [<?php echo $available_arr;?>];
	var unavailable_arr = [<?php echo $unavailable_arr;?>];
	var booked_arr = [<?php echo $booked_arr;?>];
	var noseat_arr = [<?php echo $noseat_arr;?>];
	var row_name = [<?php echo $row_name;?>];
	var selected_arr = [<?php echo $selected_arr;?>];
	var blocked_arr = [<?php echo $blocked_arr;?>];
	var booking_arr = [<?php echo $booking_arr;?>];
</script>