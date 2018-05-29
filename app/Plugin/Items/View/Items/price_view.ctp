<h3 class="well space textb text-14 no-mar dc"><?php echo __l('Rate Details'); ?></h3>
<div class="clearfix  sspace">
	<?php 
		$icon_date = 'icon-calendar';
		$icon_time = 'icon-time';
		if($item['Item']['is_people_can_book_my_time'] == 1) { 
			if(count($custom_prices) > 0) { 
				$i = 1;
				foreach ($custom_prices as $custom_price){
					if($i == 1 && $item['CustomPricePerNight'][0]['min_hours'] > 0){ ?>
						<div class="alert alert-inline alert-info dl sspace ver-mspace">
							<p class="hor-space"> <span class="textb"><?php echo __l('Min Hours') .': '. $item['CustomPricePerNight'][0]['min_hours']; ?></span>.</p> <p class="hor-space"><?php echo __l('If user booking time is less than the minimum hours, then minimum hours will be taken as total booking time for calculating amount'); ?></p> 
						</div>
				<?php } ?>
				
						<div class="clearfix space sep no-shad">
							<h4 class="well space textb text-14 no-mar dl">
								<span class="c"><?php echo $this->Html->cText($custom_price['CustomPricePerNight']['name']);?> </span> 
							</h4>
							<div class="space dl">
								<span class="c span12 htruncate no-mar">
									<?php echo $this->Html->cText($custom_price['CustomPricePerNight']['description']);?>
								</span>
							</div>
							<div class="space dl span6 no-mar"> 
								<?php								
									$str_end_date = strtotime($custom_price['CustomPricePerNight']['end_date']);
									if(!empty($str_end_date)){
										$flexible_date = $this->Html->cDate($custom_price['CustomPricePerNight']['start_date'], 'span', true).' '.$this->Html->cTime($custom_price['CustomPricePerNight']['start_time']).' - '.$this->Html->cDate($custom_price['CustomPricePerNight']['end_date'],'span',true).' '.$this->Html->cTime($custom_price['CustomPricePerNight']['end_time']);
									} else {
										$flexible_date = $this->Html->cDate($custom_price['CustomPricePerNight']['start_date'], 'span', true).' '.$this->Html->cTime($custom_price['CustomPricePerNight']['start_time']). '-' .$this->Html->cTime($custom_price['CustomPricePerNight']['end_time']);
									}
									if($item['CustomPricePerNight'][0]['is_timing'] == 0){
									?>
									<p class="js-bootstrap-tooltip" title="<?php echo $this->Html->cText($flexible_date, false); ?>">
										<?php
											echo '<i class="'. $icon_date.'"></i>' . $this->Html->cDate($custom_price['CustomPricePerNight']['start_date'], 'span', true).' '. $this->Html->cTime($custom_price['CustomPricePerNight']['start_time'], 'span', true). ' - ' ;
											if(!empty($str_end_date)){
												echo ' ' .$this->Html->cDate($custom_price['CustomPricePerNight']['end_date'], 'span', true);
											}
											if(!empty($custom_price['CustomPricePerNight']['end_time'])){
												echo ' ' . $this->Html->cTime($custom_price['CustomPricePerNight']['end_time'], 'span', true);
											}
										?>										
									</p>
									<?php
									} else {										
								?> 
										<p class="js-bootstrap-tooltip" title="<?php echo $this->Html->cText($flexible_date, false); ?>">
											<?php echo '<i class="'. $icon_date.'"></i>' . $this->Html->cDate($custom_price['CustomPricePerNight']['start_date'], 'span', true). ' - ';
											if(!empty($str_end_date)){
												echo $this->Html->cDate($custom_price['CustomPricePerNight']['end_date'],'span',true);
											} ?>
										</p>
										<p>
											<?php echo '<i class="'. $icon_time.'"></i>' . $this->Html->cTime($custom_price['CustomPricePerNight']['start_time'], 'span',true).' '.  $this->Html->cTime($custom_price['CustomPricePerNight']['end_time'], 'span', true);?>
										</p>
									<?php } ?> 
							</div>	
							<div class="space clearfix span9">
								<?php if(!empty($custom_price['CustomPricePerNight']['price_per_hour']) && $custom_price['CustomPricePerNight']['price_per_hour'] > 0) { ?>
									<dl class="dc sep-right list">
										<dt class="pr hor-mspace text-11"><?php echo __l('Per Hour');?></dt>
										<dd class="textb  pr hor-mspace"><span class="c cr" title=""><?php echo $this->Html->siteCurrencyFormat($custom_price['CustomPricePerNight']['price_per_hour']);?></span></dd>
									</dl>
								<?php } ?>
								<?php if(!empty($custom_price['CustomPricePerNight']['price_per_day']) && $custom_price['CustomPricePerNight']['price_per_day'] > 0) {
								?>
									<dl class="dc sep-right list">
										<dt class="pr hor-mspace text-11"><?php echo __l('Per Day');?></dt>
										<dd class="textb  pr hor-mspace"><span class="c cr" title=""><?php echo $this->Html->siteCurrencyFormat($custom_price['CustomPricePerNight']['price_per_day']);?></span></dd>
									</dl>
								<?php } ?>
								<?php if(!empty($custom_price['CustomPricePerNight']['price_per_week']) && $custom_price['CustomPricePerNight']['price_per_week'] > 0) { ?>
									<dl class="dc sep-right list">
										<dt class="pr hor-mspace text-11"><?php echo __l('Per Week');?></dt>
										<dd class="textb  pr hor-mspace"><span class="c cr" title=""><?php echo $this->Html->siteCurrencyFormat($custom_price['CustomPricePerNight']['price_per_week']);?></span></dd>
									</dl>
								<?php } ?>
								<?php if(!empty($custom_price['CustomPricePerNight']['price_per_month']) && $custom_price['CustomPricePerNight']['price_per_month'] > 0) { ?>
									<dl class="dc sep-right list">
										<dt class="pr hor-mspace text-11"><?php echo __l('Per Month');?></dt>
										<dd class="textb  pr hor-mspace"><span class="c cr" title=""><?php echo $this->Html->siteCurrencyFormat($custom_price['CustomPricePerNight']['price_per_month']);?></span></dd>
									</dl>
								<?php	} ?>
								<?php if($custom_price['CustomPricePerNight']['price_per_hour'] <= 0 && $custom_price['CustomPricePerNight']['price_per_day'] <= 0 && $custom_price['CustomPricePerNight']['price_per_week'] <= 0 && $custom_price['CustomPricePerNight']['price_per_month'] <= 0) { ?>
									<dl class="dc list sep-right">
										<dt class="pr hor-mspace  text-11"><?php echo __l('Price');?></dt>
										<dd class="textb  pr hor-mspace"><span class="c cr" title="Free"><?php echo __l('Free'); ?></span></dd>
									</dl>
								<?php	} ?>
								<?php if(!empty($custom_price['CustomPricePerNight']['repeat_days'])) { ?>
									<dl class="dc sep-right list span">
										<dt class="pr hor-mspace text-11 bot-mspace"><?php echo __l('Repeat Days');?></dt>
										<dd class="textb  pr hor-mspace"><span class="c cr" title=""><?php echo $this->Html->cText($custom_price['CustomPricePerNight']['repeat_days']);?></span></dd>
										<?php if(!empty($custom_price['CustomPricePerNight']['repeat_end_date'])) {?>
										<dt class="pr top-mspace bot-mspace text-11"><?php echo __l('Repeat Ends On');?></dt>
										<dd class="text-11 textb pr"><?php echo $this->Html->cDate($custom_price['CustomPricePerNight']['repeat_end_date']);?></dd>
										<?php } ?>
									</dl>
								<?php } ?>
								<?php if($item['Item']['is_additional_fee_to_buyer']) { ?>
									<dl class="dc sep-right list">
										<dt class="pr hor-mspace text-11"><?php echo __l('Additional Fee');?></dt>
										<dd class="textb  pr hor-mspace"><span class="c cr" title=""><?php echo $this->Html->cFloat($item['Item']['additional_fee_percentage']);?></span></dd>
									</dl>
								<?php } ?>
							</div>
						</div>
						<?php $i++; } ?>	
						<?php } ?>
						<?php } ?>
						<?php if($item['Item']['is_sell_ticket'] == 1) { ?>
						<?php if(count($custom_price_types) > 0) { ?>
						<?php  foreach($custom_price_types as $custom_price_main){
								$start_time = explode(':', $custom_price_main['CustomPricePerNight']['start_time']);
								$end_time = explode(':', $custom_price_main['CustomPricePerNight']['end_time']);
								$fixed_date = $this->Html->cDate($custom_price_main['CustomPricePerNight']['start_date'], 'span', true) . ' - '. $this->Html->cDate($custom_price_main['CustomPricePerNight']['end_date'], 'span', true);
						?>
						<div class="clearfix space sep no-shad top-mspace">
							<div>
								<h4 class="well space textb text-12 no-mar dl js-bootstrap-tooltip" title="<?php echo $this->Html->cText($fixed_date, false); ?>"><?php echo '<i class="'.$icon_date.'"></i>'. $this->Html->cDate($custom_price_main['CustomPricePerNight']['start_date'], 'span', true). ' - ' . $this->Html->cDate($custom_price_main['CustomPricePerNight']['end_date'], 'span', true);?>
								<?php if(isPluginEnabled('Seats') && !empty($custom_price_main['Hall']['name'])){ ?>
									<span class="left-space"><?php echo '['.__l('Venue').' - '.$custom_price_main['Hall']['name'].']';?>
									</span>
								<?php } ?>
								</h4>
							</div>
							<div class="space clearfix pull-left">
								<?php foreach($custom_price_main['CustomPricePerType'] as $custom_price_type){
									$type_start_time = explode(':', $custom_price_type['start_time']);
									$type_end_time = explode(':', $custom_price_type['end_time']);
									$type_s_time = date('h:i A', mktime($type_start_time[0], $type_start_time[1],$type_start_time[2], 0, 0, 0));
									$type_e_time = date('h:i A', mktime($type_end_time[0], $type_end_time[1], $type_end_time[2], 0, 0, 0));
								?>
								<dl class="dc sep-right list">
									<dt class="pr hor-mspace text-11">
										<span class="c"><?php echo $this->Html->cText($custom_price_type['name']);?></span>
										<?php if(!empty($custom_price_type['description'])){ ?>
										<span><i class="icon-info-sign js-bootstrap-tooltip"  data-original-title="<?php echo $this->Html->cText($custom_price_type['description'], false);?>"></i></span>
										<?php } ?>
									</dt>
									<dd class="textb  pr hor-mspace"><span class="c cr" title=""><?php echo $this->Html->siteCurrencyFormat($custom_price_type['price']);?></span></dd>
									<?php if(isPluginEnabled('Seats') && !empty($custom_price_type['Partition']['name'])){ ?>
									<dt class="top-space hor-mspace text-11"> <?php echo __l('Partition').' - '.$custom_price_type['Partition']['name'];?> </dt>
									<?php } ?>
									<dt class="top-space hor-mspace text-11"> <?php echo $type_s_time.' - '.$type_e_time;?> </dt>
								</dl>
								<?php } ?>
								<?php if($item['Item']['is_additional_fee_to_buyer']) { ?>
									<dl class="dc sep-right list">
										<dt class="pr top-mspace text-11"><?php echo __l('Additional Fee');?></dt>
										<dd class="textb  pr hor-mspace"><span class="c cr" title=""><?php echo $this->Html->cFloat($item['Item']['additional_fee_percentage']);?></span></dd>
									</dl>
								<?php } ?>
							</div>
							<div>
								<?php if(!empty($custom_price_main['CustomPricePerNight']['repeat_days'])) { ?>
									<dl class="dl list span5">
										<dt class="pr top-mspace text-11"><?php echo __l('Repeat Days');?></dt>
										<dd class="textb  pr bot-mspace"><span class="c cr"><?php echo $this->Html->cText($custom_price_main['CustomPricePerNight']['repeat_days']);?></span></dd>
										<?php if(!empty($custom_price_main['CustomPricePerNight']['repeat_end_date'])) {?>
										<dt class="pr top-mspace bot-mspace text-11"><?php echo __l('Repeat Ends On');?></dt>
										<dd class="text-11 pr bot-mspace"><?php echo $this->Html->cDate($custom_price_main['CustomPricePerNight']['repeat_end_date']);?></dd>
										<?php } ?>
									</dl>
								<?php } ?>
							</div>
						</div>
				<?php } ?>
		<?php } ?>
	<?php } ?>
</div>						
