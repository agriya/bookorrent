<?php /* SVN: $Id: $ */ ?>
<!-- BEGIN: TICKET BOOKING -->    
<div class="container">		
	<h2 class="ver-space top-mspace text-32 sep-bot"> <?php echo __l('Partition')." - ".$partition['Partition']['name']; ?> </h2>
	<div class="ver-space"></div>
	
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
	var booked_arr = [];
	var noseat_arr = [<?php echo $noseat_arr;?>];
	var row_name = [<?php echo $row_name;?>];
	var selected_arr = [];
	var blocked_arr = [];
	var booking_arr = [];
</script>