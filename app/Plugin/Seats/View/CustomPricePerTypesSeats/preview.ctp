<?php /* SVN: $Id: $ */ ?>
<!-- BEGIN: TICKET BOOKING -->    
<div>
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
				echo __l('Preview'); 
			?>
		</li>
	</ul>
<?php
	} else {
?>
	<h2 class="ver-space top-mspace text-32 sep-bot"> <?php echo __l('Partition')." - ".$partition['Partition']['name']; ?> </h2>
<?php } ?>
	<div class="ver-space"></div>
	<div class="ver-space sep-bot clearfix">
              <div class="span dc">
			  <?php echo $this->Html->link($this->Html->showImage('Item', (!empty($item['Attachment'][0])) ? $item['Attachment'][0] : array(), array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'],false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('target' => '_blank', 'title' => $this->Html->cText($item['Item']['title'], false),'escape' => false)); ?>			  
			  </div>
              <div class="span20 right-mspace mob-clr tab-clr">
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
					  <?php if(!empty($CustomPricePerType['CustomPricePerType']['price']) && $CustomPricePerType['CustomPricePerType']['price'] > 0) { 
							echo $this->Html->siteCurrencyFormat($CustomPricePerType['CustomPricePerType']['price']);
						  } else {
							echo __l('Free');
						}
					?>
					  </dd>
                    </dl>
                  </div>
                </div>
                <div class="clearfix hor-space">
                  
                  <div class="clearfix pull-right top-mspace mob-clr">
					 <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Start Date');?></dt>
                      <dd title="<?php echo $this->Html->cDate($CustomPricePerType['CustomPricePerNight']['start_date'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo $this->Html->cDate($CustomPricePerType['CustomPricePerNight']['start_date']); ?></dd>
                    </dl>
					 <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('End Date');?></dt>
                      <dd title="<?php echo $this->Html->cDate($CustomPricePerType['CustomPricePerNight']['end_date'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo $this->Html->cDate($CustomPricePerType['CustomPricePerNight']['end_date']); ?></dd>
                    </dl>
					 <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Start Time');?></dt>
                      <dd title="<?php echo $this->Html->cTime($CustomPricePerType['CustomPricePerType']['start_time'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo $this->Html->cTime($CustomPricePerType['CustomPricePerType']['start_time']); ?></dd>
                    </dl>
					 <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('End Time');?></dt>
                      <dd title="<?php echo $this->Html->cTime($CustomPricePerType['CustomPricePerType']['end_time'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo $this->Html->cTime($CustomPricePerType['CustomPricePerType']['end_time']) ?></dd>
                    </dl>
                    <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Views');?></dt>
                      <dd title="<?php echo $this->Html->cInt($item['Item']['item_view_count'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['item_view_count']); ?></dd>
                    </dl>
                    <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Positive');?></dt>
                      <dd title="<?php echo $this->Html->cInt($item['Item']['positive_feedback_count'], false); ?>	" class="textb text-16  graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['positive_feedback_count']); ?>	</dd>
                    </dl>
                    <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Negative');?></dt>
                      <dd title="<?php echo $this->Html->cInt($item['Item']['item_feedback_count'] - $item['Item']['positive_feedback_count'], false); ?>	" class="textb text-16  graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['item_feedback_count'] - $item['Item']['positive_feedback_count']); ?>	</dd>
                    </dl>
                    <dl class="list">
                      <dt class="pr mob-clr hor-mspace text-11"><?php echo __l('Success Rate');?></dt>
					  <?php if(empty($item['Item']['item_feedback_count'])): ?>
					  <dd class="textb text-16  graydarkc pr hor-mspace" title="<?php  echo __l('No Bookings available'); ?>"><?php  echo __l('n/a'); ?></dd>
					<?php else:?>
								 <dd class="textb text-16  graydarkc pr hor-mspace">
										<?php if(!empty($item['Item']['positive_feedback_count'])):
										$positive = floor(($item['Item']['positive_feedback_count']/$item['Item']['item_feedback_count']) *100);
										$negative = 100 - $positive;
										else:
										$positive = 0;
										$negative = 100;
										endif;
										
										echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&chf=bg,s,FFFFFF00', array('width'=>'40px','height'=>'40px','title' => $positive.'%'));  ?>
								</dd>
							<?php endif; ?>					  
                    </dl>
                  </div>
                </div>
              </div>
            </div>
	<div class="row">
		<div class="span24 dc">
			<div class="ver-space"></div>
			<div class="row">
				<div class="span24 dc" style="width:750px;float:none;margin:auto;">			
				
				<div class="ver-space"></div>
					<?php 					
						if($partition['Partition']['stage_position'] == ConstStagePosition::Top) {
							echo $this->Html->image('top-bor.png', array('alt' => 'stage')); 
					?>
							<span class="dc clearfix textb"> <?php echo __l('Screen');?> </span>
					<?php } ?>
					<div class="ver-space"></div>
					<div class="ver-space"></div>
					<?php 
						$defaultwidth = "78%";
						if($partition['Partition']['no_of_columns'] < 20){	
							$defaultwidth = ($partition['Partition']['no_of_columns'] + 1) * 3.1 . "em";
						}
						$width = ($partition['Partition']['no_of_columns'] + 1) * 3.1 . "em";
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
						if($partition['Partition']['stage_position'] == ConstStagePosition::Bottom) {
					?>
							<span class="dc clearfix textb"> <?php echo __l('Screen');?> </span>			
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