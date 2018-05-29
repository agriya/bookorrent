<div class="clearfix">
<?php 
if(!empty($available_bookings)){ ?>
<div class="top-space" id="availability_response">
			<!-- Bookit content from ajax -->
			<?php echo $this->Form->create('ItemUser', array('action' => 'add', 'class' => "normal form-horizontal no-mar")); 
			?>
			<table class="table table-striped table-bookit table-hover sep no-mar">
				<tr class="js-even well no-mar no-pad">
					<th class="graydarkc sep-bot dl sep-right span1"></th>
					<th class="graydarkc sep-bot dl sep-right span8"><?php echo __l('Name'); ?></th>
					<th class="graydarkc sep-bot dr sep-right span3"><?php echo __l('Price') . ' (' . Configure::read('site.currency') . ')'; ?></th>
				</tr>
<?php
	
	echo $this->Form->input('item_id',array('type'=>'hidden'));
	echo $this->Form->input('start_date.year',array('type'=>'hidden'));
	echo $this->Form->input('start_date.month',array('type'=>'hidden'));
	echo $this->Form->input('start_date.day',array('type'=>'hidden'));
	echo $this->Form->input('start_time.hour',array('type'=>'hidden'));
	echo $this->Form->input('start_time.min',array('type'=>'hidden'));
	echo $this->Form->input('start_time.meridian',array('type'=>'hidden'));
	echo $this->Form->input('end_date.year',array('type'=>'hidden'));
	echo $this->Form->input('end_date.month',array('type'=>'hidden'));
	echo $this->Form->input('end_date.day',array('type'=>'hidden'));
	echo $this->Form->input('end_time.hour',array('type'=>'hidden'));
	echo $this->Form->input('end_time.min',array('type'=>'hidden'));
	echo $this->Form->input('end_time.meridian',array('type'=>'hidden'));
	echo $this->Form->input('end_time.meridian',array('type'=>'hidden'));
	echo $this->Form->input('custom_price_per_night_id',array('type'=>'hidden'));
	
	foreach($available_bookings as $available_booking){
		$is_unlimited = false;
		if($available_booking['CustomPricePerNight']['total_available_count'] > 0) {
			$balance_quantity = $available_booking['CustomPricePerNight']['total_available_count'] - $available_booking['CustomPricePerNight']['total_booked_count'];
		} else if($available_booking['CustomPricePerNight']['total_available_count'] == 0){
			$is_unlimited = true;
		}
		$options = array();
		$options[0] = __l('Please Select');
		if(!$is_unlimited){
			for ($i = 1; $i <= $balance_quantity; $i++) {
				$options[$i] = $i;
			}
		}
		$price_title = '';
		if(!empty($available_booking['CustomPricePerNight']['price_per_hour']) && $available_booking['CustomPricePerNight']['price_per_hour'] > 0){
			$price_title = 'Price/hour: '. $this->Html->siteCurrencyFormat($available_booking['CustomPricePerNight']['price_per_hour'], false);
		}
		if(!empty($available_booking['CustomPricePerNight']['price_per_day']) && $available_booking['CustomPricePerNight']['price_per_day'] > 0){
			$price_title .= ' | Price/day: '. $this->Html->siteCurrencyFormat($available_booking['CustomPricePerNight']['price_per_day'], false);
		}
		if(!empty($available_booking['CustomPricePerNight']['price_per_week']) && $available_booking['CustomPricePerNight']['price_per_week'] > 0){
			$price_title .= ' | Price/week: '. $this->Html->siteCurrencyFormat($available_booking['CustomPricePerNight']['price_per_week'], false);
		}
		if(!empty($available_booking['CustomPricePerNight']['price_per_month']) && $available_booking['CustomPricePerNight']['price_per_month'] > 0){
			$price_title .= ' | Price/month: '. $this->Html->siteCurrencyFormat($available_booking['CustomPricePerNight']['price_per_month'], false);
		}
		$minimum_price = '';
		$info_icon = '<i class="icon-info-sign js-bootstrap-tooltip" title="'.$price_title.'"></i>';
		if(!empty($available_booking['CustomPricePerNight']['price_per_hour']) && $available_booking['CustomPricePerNight']['price_per_hour'] > 0) {
			$minimum_price = $this->Html->cCurrency($available_booking['CustomPricePerNight']['price_per_hour'], false);
		} else if(!empty($available_booking['CustomPricePerNight']['price_per_day']) && $available_booking['CustomPricePerNight']['price_per_day'] > 0) {
			$minimum_price = $this->Html->cCurrency($available_booking['CustomPricePerNight']['price_per_day'], false);
		} else if(!empty($available_booking['CustomPricePerNight']['price_per_week']) && $available_booking['CustomPricePerNight']['price_per_week'] > 0) {
			$minimum_price = $this->Html->cCurrency($available_booking['CustomPricePerNight']['price_per_week'], false);
		} else if(!empty($available_booking['CustomPricePerNight']['price_per_month']) && $available_booking['CustomPricePerNight']['price_per_month'] > 0) {
			$minimum_price = $this->Html->cCurrency($available_booking['CustomPricePerNight']['price_per_month'], false);
		} else {
			$minimum_price = __l('Free');
			$info_icon = '';
		}
?>		
				<tr>
					<td> <?php echo $this->Form->input('CustomPricePerNight.' . $available_booking['CustomPricePerNight']['id'], array('type' => 'checkbox', 'label' => '', 'class' => 'checkbox no-mar span1', 'hiddenField' => false, 'div' => false));
					?> </td>
					<td class="graydarkc sep-bot dl sep-right"><?php echo $this->Html->cText($available_booking['CustomPricePerNight']['name']);?> <i class="icon-info-sign js-bootstrap-tooltip" title="<?php echo $this->Html->cText($available_booking['CustomPricePerNight']['description'], false);?>"></i></td>
					<td class="graydarkc sep-bot sep-right dr"><?php echo $minimum_price.' '. $info_icon;?></td>
					<!-- <td class="graydarkc sep-bot dc sep-right">
					<?php 
						
						if(!$is_unlimited){
							echo $this->Form->input('CustomPricePerNight.' . $available_booking['CustomPricePerNight']['id'], array('type' => 'select', 'options' => $options, 'legend' => false, 'label' => false, 'div' => 'input required select no-mar', 'class' => 'span4 text-12')); 
						} else {
							echo $this->Form->input('CustomPricePerNight.' . $available_booking['CustomPricePerNight']['id'], array('placeholder' => __l('Enter Quantity'), 'div' => 'input required no-mar ', 'label' => false, 'class' =>'span4'));
						}
						?>
					</td> -->
				</tr>
<?php 
}  ?>
			</table>
			<div class="span span-block">
            	 <?php 
				  echo $this->Html->link(__l('Cancel'),array('controller' => 'items', 'action' => 'cancel', $custom_price_per_night_parent[0]['Item']['slug']),array( 'class'=>'js-no-pjax show dropdown-toggle btn btn-primary span top-mspace space textb backToBook text-14 cancl-btn'));				 
				  echo $this->Form->submit(__l('Book It'),array('name' => 'data[ItemUser][bookit]', 'class'=>'show btn btn-large btn-primary textb top-mspace bookit-btn')); 
				 ?>
               
			</div>            
			<?php echo $this->Form->end();?>
		</div>
<?php } else {
?>
	<div class="sep space text-14 clearfix dl mspace">
		
			<?php echo __l('There is no availability for Booking. Try some other date!');?> 
			<?php 
			echo $this->Html->link(__l('Back'),array('controller' => 'items', 'action' => 'Back', $custom_price_per_night_parent[0]['Item']['slug']),array('type'=> 'button', 'class'=>'js-no-pjax show dropdown-toggle btn btn-primary span top-mspace textb backToBook text-16 pull-right'));	
		?>
	</div>
<?php } ?>
</div>