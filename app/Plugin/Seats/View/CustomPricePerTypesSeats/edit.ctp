<?php /* SVN: $Id: $ */ ?>
<div>
<?php 
	echo $this->Form->create('CustomPricePerTypesSeat', array('class' => 'form-horizontal space', 'id' => 'js-show-add-form-save'));
	echo $this->Form->input('partition_id', array('type' => 'hidden'));
	echo $this->Form->input('CustomPricePerTypesSeat.result', array('type' => 'textarea', 'id' => 'js-result', 'div' => array('class' => 'hide')));
?>	
<?php
	if(!empty($this->request->params['admin'])) {
?>
	<ul class="breadcrumb top-mspace ver-space sep-bot">
		<li class="active">
			<?php
				echo $this->Html->link(__l('Partitions'), array('controller'=>'items', 'action'=>'partitions', 'slug' => $item['Item']['slug']), array('class' => 'js-no-pjax', 'escape' => false));
			?>
			<span class="divider"> / </span>
		</li>
		<li>
			<?php 
				echo __l('Edit Partition'); 
			?>
		</li>
	</ul>
<?php
	} else {
?>
	<h2 class="ver-space top-mspace text-32 sep-bot"> <?php echo __l('Update Partition Seats Status')." - ".$partition['Partition']['name']; ?> </h2>
<?php } ?>
	<div class="ver-space sep-bot clearfix">
              <div class="span dc">
			  <?php echo $this->Html->link($this->Html->showImage('Item', (!empty($item['Attachment'][0])) ? $item['Attachment'][0] : array(), array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'],false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('target' => '_blank', 'title' => $this->Html->cText($item['Item']['title'], false),'escape' => false)); ?>			  
			  </div>
              <div class="span19 right-mspace mob-clr tab-clr">
                <div class="clearfix hor-space sep-bot">
                  <div class="span10 bot-space">
                    <h4><?php echo $this->Html->link($this->Html->cText($item['Item']['title'], false), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('target' => '_blank', 'title' => $this->Html->cText($item['Item']['title'], false),'escape' => false, 'class' => 'textb text-16 graydarkc span9 no-mar js-bootstrap-tooltip htruncate' ));?></h4>
					<?php if(!empty($item['Country']['iso_alpha2'])): ?>
                    <span title="<?php echo $this->Html->cText($item['Item']['address'], false); ?>" class="graydarkc top-smspace show mob-clr mob-dc span9 js-bootstrap-tooltip htruncate"><span title="<?php echo $this->Html->cText($item['Country']['name'], false); ?>" class="flags flag-<?php echo strtolower($item['Country']['iso_alpha2']); ?> mob-inline top-smspace"></span><?php echo $this->Html->cText($item['Item']['address'], false); ?></span>
					<?php endif; ?>
                    <div class="clearfix mob-dc"><span><?php echo __l('Posted on'); ?></span> <span class="graydarkc"  title="<?php echo strftime(Configure::read('site.datetime.tooltip'), strtotime($item['Item']['created'])); ?>"> <?php echo  $this->Time->timeAgoInWords($item['Item']['created']);?></span> </div>
                  </div>
                  <div class="pull-right sep-left mob-clr mob-sep-none">
                    <dl class="dc list mob-clr">                     
                      <dd class="textb text-24 graydarkc pr hor-mspace">
						<div class="ver-space"></div><div class="ver-space"></div>
					  <?php 
							echo $this->Form->submit(__l('Update'), array('class' => 'btn btn-primary btn-large img-rounded ver-space', 'div' => false));	
					?>
					  </dd>
                    </dl>
                  </div>
                </div>
                <div class="clearfix hor-space">
                  
                  <div class="clearfix top-mspace mob-clr">
					 <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Start Date');?></dt>
                      <dd title="<?php echo $this->Html->cDate($item['CustomPricePerNight'][0]['start_date'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo $this->Html->cDate($item['CustomPricePerNight'][0]['start_date']); ?></dd>
                    </dl>
					 <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('End Date');?></dt>
                      <dd title="<?php echo $this->Html->cDate($item['CustomPricePerNight'][0]['end_date'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo $this->Html->cDate($item['CustomPricePerNight'][0]['end_date']); ?></dd>
                    </dl>
					 <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Start Time');?></dt>
                      <dd title="<?php echo $this->Html->cTime($custom_price_per_type_seats['CustomPricePerType']['start_time'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo $this->Html->cTime($custom_price_per_type_seats['CustomPricePerType']['start_time']); ?></dd>
                    </dl>
					 <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('End Time');?></dt>
                      <dd title="<?php echo $this->Html->cTime($custom_price_per_type_seats['CustomPricePerType']['end_time'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo $this->Html->cTime($custom_price_per_type_seats['CustomPricePerType']['end_time']) ?></dd>
                    </dl>
					<dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Price');?></dt>
                      <dd title="<?php echo $this->Html->siteCurrencyFormat($custom_price_per_type_seats['CustomPricePerType']['price'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($custom_price_per_type_seats['CustomPricePerType']['price']); ?></dd>
                    </dl>
                  </div>
                </div>
              </div>
            </div>	
<?php 
	echo $this->Form->end();
?>			
</div>

<?php 
	echo $this->Form->create('Partition', array('class' => 'js-partition-temp-add-form form-horizontal'));
?>

<div class="hide seatOption span24">
  <div class="dl">
    <div class="span8">
		<?php 
			echo $this->Form->input('seat_status_id', array('label' => false, 'class' => 'select-category span8', 'id' => 'seat-marker', 'empty' => __l('Mark seat as'), 'options' => $seat_status, 'div' => array('class' => 'input select'))); 
		?>
	</div>
	<div class="span2 ver-space">
		<?php 
			echo $this->Form->submit(__l('Ok'), array('class' => 'hor-mspace btn btn-primary textb text-16 select-btn', 'type'=>'button', 'id'=>'mark_seats', 'div' => false));
		?>
	</div>
		
  </div>
</div>	
	
		  <div class="ver-space"></div><div class="ver-space"></div>
		  <div class="ver-space"></div><div class="ver-space"></div>
		  <!--Seat Section--->
          <section class="responsive-table top-content-space">
            <div class="container">			
				<div class="dc clearfix">
					<div class="ver-space"></div>
					<?php 
						if($partition['Partition']['stage_position'] == ConstStagePosition::Top){
							echo $this->Html->image('top-bor.png', array('alt' => 'Stage'));
							echo '<span class="dc clearfix textb">'. __l('Screen') .  '</span>';
						}
					?>									
					<div class="ver-space"></div>
				</div>
				<div class="ver-space"></div>
				<?php 
					$defaultwidth = "74%";
					if($partition['Partition']['no_of_columns'] < 6){	
						$defaultwidth = $partition['Partition']['no_of_columns'] * 5 . "%";
					}else if($partition['Partition']['no_of_columns'] < 10){	
						$defaultwidth = $partition['Partition']['no_of_columns'] * 4.4 . "%";
					}else if($partition['Partition']['no_of_columns'] < 20){						
						$defaultwidth = $partition['Partition']['no_of_columns'] * 4.3 . "%";
					}
					$width = ($partition['Partition']['no_of_columns'] + 1) * 33 + 17;					
				?>
				<div class="seat-grid" data-rows="<?php echo $partition['Partition']['no_of_rows']; ?>" data-cols="<?php echo $partition['Partition']['no_of_columns']; ?>" style="width: <?php echo $defaultwidth;?>; padding:0px 0px 55px 0px; overflow-x:scroll;">
				<div class="span24 seat-row-col" style="width :<?php echo $width;?>px">
					<?php if($partition['Partition']['seating_direction'] == ConstSeatArrangementDirection::LeftToRight){ ?>							
							<ul class="inline unstyled blank-row">
							  <li class="col-deselector seat-all-toggle">ALL</li>
							  <?php for($j = 1; $j <= $partition['Partition']['no_of_columns'] ; $j ++){ ?>
										<li class="col-deselector" act="<?php echo $j; ?>"><?php echo $j; ?></li>								
							 <?php } ?>					  
							</ul>
							<?php 
								$order = 1;
								for($i = 1; $i <= $partition['Partition']['no_of_rows'] ; $i ++){ ?>
									<ul class="inline unstyled seat-row" id="row-<?php echo $i; ?>">
										<li class="row-selector" act="<?php echo $i; ?>"><?php echo $rowNames[$i-1]; ?></li>
										<?php for($j = 1; $j <= $partition['Partition']['no_of_columns'] ; $j ++){ 
											$tmp = $i . "-" . $j;
											if($partition['Partition']['seating_name_type'] == 3){
												$name = $order;
											}else{
												$name = $rowNames[$i-1] . "-" . $j;
											}
											if(!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'])) {
												if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::Available) {
													$class = 'available';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::Unavailable) {
													$class = 'unavailable';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::Blocked) {
													$class = 'blocked';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::WaitingForAcceptance) {
													$class = 'waitingforacceptance';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::NoSeat) {
													$class = 'no-seat';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::Booked) {
													$class = 'booked';
												}
											}
										?>
										<li class="seat <?php echo (!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['class']))? $class:''; ?>" id="<?php echo $tmp; ?>" data-class="<?php echo (!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['class']))? $class:'';?>">										
											<?php if(!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['id'])) { ?>
												<input type="hidden" value="<?php echo $this->request->data['CustomPricePerTypesSeat'][$tmp]['id'];?>" name="data[CustomPricePerTypesSeat][<?php echo $tmp; ?>][id]" />
											<?php } ?>	
											<input type="hidden" value="<?php echo (!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'])) ? $this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] : "1"; ?>" id="class-<?php echo $tmp; ?>" name="data[CustomPricePerTypesSeat][<?php echo $tmp; ?>][class]" />
										</li>
								<?php 
										$order++;
									} ?>
								  </ul>
							<?php } ?>					
					<?php } else if($partition['Partition']['seating_direction'] == ConstSeatArrangementDirection::RightToLeft){ ?>
								<ul class="inline unstyled blank-row">
								  <li class="col-selector seat-all-toggled" >ALL</li>
								  <?php for($j = $partition['Partition']['no_of_columns']; $j >= 1 ; $j --){ ?>
											<li class="col-selector" act="<?php echo $j; ?>"><?php echo $j; ?></li>								
								 <?php } ?>					  
								</ul>					
								<?php 
									$order = 1;
									for($i = 1; $i <= $partition['Partition']['no_of_rows'] ; $i ++){ ?>
									<ul class="inline unstyled seat-row" id="row-<?php echo $i; ?>">
										<li class="row-selector" act="<?php echo $i; ?>"><?php echo $rowNames[$i-1]; ?></li>
									<?php for($j = $partition['Partition']['no_of_columns']; $j >= 1 ; $j --){ 
											$tmp = $i . "-" . $j;
											if($partition['Partition']['seating_name_type'] == 3){
												$name = ($i-1) * $partition['Partition']['no_of_columns'] + $j;
											}else{
												$name = $rowNames[$i-1] . "-" . $j;
											}
											if(!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'])) {
												if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::Available) {
													$class = 'available';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::Unavailable) {
													$class = 'unavailable';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::Blocked) {
													$class = 'blocked';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::WaitingForAcceptance) {
													$class = 'waitingforacceptance';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::NoSeat) {
													$class = 'no-seat';
												}else if($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] == ConstSeatStatus::Booked) {
													$class = 'booked';
												}
											}
									?>
											<li class="seat  <?php echo  (!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['class']))? $class:''; ?> " id="<?php echo $tmp; ?>" data-class="<?php echo (!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['class']))? $class:'';?>">	
												<?php if(!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['id'])) { ?>
													<input type="hidden" value="<?php echo $this->request->data['CustomPricePerTypesSeat'][$tmp]['id'];?>" name="data[CustomPricePerTypesSeat][<?php echo $tmp; ?>][id]" />
												<?php } ?>
												<input type="hidden" value="<?php echo (!empty($this->request->data['CustomPricePerTypesSeat'][$tmp]['class'])) ? $this->request->data['CustomPricePerTypesSeat'][$tmp]['class'] : "1"; ?>" id="class-<?php echo $tmp; ?>" name="data[CustomPricePerTypesSeat][<?php echo $tmp; ?>][class]" />
											</li>
									<?php 
											$order++;
										} ?>
									  </ul>
								<?php } ?>					
					<?php } ?>
					
				</div>
				</div>
				
				<div class="clearfix available-products ui-datepicker-row-break">
					<ul class="dc ui-datepicker-row-break ver-mspace">
					  <li class="cancel-block mspace ver-space"><span class="available"></span><a href="#" title="Available">Available</a></li>
					  <li class="cancel-block mspace ver-space"><span class="unavailable"></span><a href="#" title="Unavailable">Unavailable</a></li>
					  <li class="cancel-block mspace ver-space"><span class="blocked"></span><a href="#" title="Blocked">Blocked</a></li>
					  <li class="cancel-block mspace ver-space"><span class="waitingforacceptance"></span><a href="#" title="WaitingForAcceptance">Booking</a></li>
					  <li class="cancel-block mspace ver-space"><span class="booked"></span><a href="#" title="Booked">Booked</a></li>
					  <li class="cancel-block mspace ver-space"><span class="noseats"></span><a href="#" title="No seats">No-seat</a></li>
					  
					</ul>
				</div>
				
				<div class="dc clearfix">
					<div class="ver-space"></div>
					<?php 
						if($partition['Partition']['stage_position'] == ConstStagePosition::Bottom){
							echo '<span class="dc clearfix textb"> '. __l('Screen') .  ' </span>';
							echo $this->Html->image('bottom-bor.png', array('alt' => 'Stage')); 
						}
					?>
					<div class="ver-space"></div>
				</div>
			
            </div>
			
			  
          </section>
		  <!--/Seat Section--->
<?php echo $this->Form->end(); ?>		  