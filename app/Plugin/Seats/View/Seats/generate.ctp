<?php /* SVN: $Id: $ */ ?>
<div class="ver-space"></div><div class="ver-space"></div>
<!--Seat Section--->
<section class="responsive-table top-content-space">
	<div class="container">			
		<div class="dc clearfix">
			<div class="ver-space"></div>
			<?php 
				$position = (!empty($this->request->params['named']['partition_id']))?$position:'';
				$top_hide = ($position == ConstStagePosition::Bottom)?'hide':'';
					echo $this->Html->image('top-bor.png', array('alt' => 'Stage', 'class' => 'top-bar-img '. $top_hide));
					echo "<span class='dc clearfix textb top-screen $top_hide'> ". __l('Screen') .  " </span>";
			?>
			<div class="ver-space"></div>
		</div>
		<div class="ver-space"></div>
		<?php 
			$defaultwidth = "79em";
			if($cols <= 26){	
				$defaultwidth = ($cols + 1) * 2.9 . "em";
			}
		?>
		<div class="seat-grid" style="width: <?php echo $defaultwidth;?>; padding:0px 0px 55px 0px; overflow-x:scroll;">
			<div class="span24 seat-row-col" style="width :<?php echo (!empty($this->request->params['named']['partition_id']))? $this->request->params['named']['width']:'';?>em">
				<?php if($direction == ConstSeatArrangementDirection::LeftToRight){ ?>							
					<ul class="inline unstyled blank-row">
						<li class="col-deselector seat-all-toggle">ALL</li>
						<?php for($j = 1; $j <= $cols ; $j ++){ ?>
								<li class="col-deselector" act="<?php echo $j; ?>"><?php echo $j; ?></li>								
						<?php } ?>					  
					</ul>
					<?php 
					$order = 1;
					for($i = 1; $i <= $rows ; $i ++){ ?>
						<ul class="inline unstyled seat-row" id="row-<?php echo $i; ?>">
							<li class="row-selector" act="<?php echo $i; ?>"><?php echo $rowNames[$i-1]; ?></li>
							<?php 
								for($j = 1; $j <= $cols ; $j ++){ 
									$tmp = $i . "-" . $j;
									if($row_name_type == 3){
										$name = $order;
									}else{
										$name = $rowNames[$i-1] . "-" . $j;
									}
									if(!empty($this->request->data['Seat'][$tmp]['class'])) {
										if($this->request->data['Seat'][$tmp]['class'] == ConstSeatStatus::Available) {
											$class = 'seat-available';
										}else if($this->request->data['Seat'][$tmp]['class'] == ConstSeatStatus::Unavailable) {
											$class = 'unavailable';
										}else if($this->request->data['Seat'][$tmp]['class'] == ConstSeatStatus::NoSeat) {
											$class = 'no-seat';
										}
									}
									?>
									<li class="seat <?php echo (!empty($this->request->data['Seat'][$tmp]['class']))? $class:''; ?>" data-class="<?php echo (!empty($this->request->data['Seat'][$tmp]['class']))? $class:'';?>" id="<?php echo $tmp; ?>">
									<?php if(!empty($this->request->data['Seat'][$tmp]['id'])) { ?>
											<input type="hidden" value="<?php echo $this->request->data['Seat'][$tmp]['id'];?>" name="data[Seat][<?php echo $tmp; ?>][id]" />
									<?php } ?>	
											<input type="hidden" value="<?php echo (!empty($this->request->data['Seat'][$tmp]['order'])) ? $this->request->data['Seat'][$tmp]['order'] : $order; ?>" id="order-<?php echo $tmp; ?>" name="data[Seat][<?php echo $tmp; ?>][order]" />							
											<input type="hidden" value="<?php echo (!empty($this->request->data['Seat'][$tmp]['class'])) ? $this->request->data['Seat'][$tmp]['class'] : "1"; ?>" id="class-<?php echo $tmp; ?>" name="data[Seat][<?php echo $tmp; ?>][class]" />
											<input type="hidden" value="<?php echo (!empty($this->request->data['Seat'][$tmp]['name'])) ? $this->request->data['Seat'][$tmp]['name'] : $name; ?>" id="name-<?php echo $tmp; ?>" name="data[Seat][<?php echo $tmp; ?>][name]" /> 							
									</li>
						<?php 
									$order++;
							} 
						?>
						</ul>
					<?php } ?>					
				<?php } else if($direction == ConstSeatArrangementDirection::RightToLeft){ ?>
						<ul class="inline unstyled blank-row">
							<li class="col-selector seat-all-toggled" >ALL</li>
							<?php for($j = $cols; $j >= 1 ; $j --){ ?>
								<li class="col-selector" act="<?php echo $j; ?>"><?php echo $j; ?></li>								
							<?php } ?>					  
						</ul>					
				<?php 
						$order = 1;
						for($i = 1; $i <= $rows ; $i ++){ ?>
							<ul class="inline unstyled seat-row" id="row-<?php echo $i; ?>">
								<li class="row-selector" act="<?php echo $i; ?>"><?php echo $rowNames[$i-1]; ?></li>
							<?php 
									for($j = $cols; $j >= 1 ; $j --){ 
										$tmp = $i . "-" . $j;
										if($row_name_type == 3){
											$name = ($i-1) * $cols + $j;
										}else{
											$name = $rowNames[$i-1] . "-" . $j;
										}
										if(!empty($this->request->data['Seat'][$tmp]['class'])) {
											if($this->request->data['Seat'][$tmp]['class'] == ConstSeatStatus::Available) {
												$class = 'seat-available';
											}else if($this->request->data['Seat'][$tmp]['class'] == ConstSeatStatus::Unavailable) {
												$class = 'unavailable';
											}else if($this->request->data['Seat'][$tmp]['class'] == ConstSeatStatus::NoSeat) {
												$class = 'no-seat';
											}
										}
							?>
										<li class="seat  <?php echo  (!empty($this->request->data['Seat'][$tmp]['class']))? $class:''; ?> " data-class="<?php echo (!empty($this->request->data['Seat'][$tmp]['class']))? $class:'';?>" id="<?php echo $tmp; ?>">	
											<?php if(!empty($this->request->data['Seat'][$tmp]['id'])) { ?>
													<input type="hidden" value="<?php echo $this->request->data['Seat'][$tmp]['id'];?>" name="data[Seat][<?php echo $tmp; ?>][id]" />
											<?php } ?>
											<input type="hidden"  value="<?php echo (!empty($this->request->data['Seat'][$tmp]['order'])) ? $this->request->data['Seat'][$tmp]['order'] : $order; ?>" id="order-<?php echo $tmp; ?>" name="data[Seat][<?php echo $tmp; ?>][order]" />
											<input type="hidden" value="<?php echo (!empty($this->request->data['Seat'][$tmp]['class'])) ? $this->request->data['Seat'][$tmp]['class'] : "1"; ?>" id="class-<?php echo $tmp; ?>" name="data[Seat][<?php echo $tmp; ?>][class]" />
											<input type="hidden" value="<?php echo (!empty($this->request->data['Seat'][$tmp]['name'])) ? $this->request->data['Seat'][$tmp]['name'] : $name; ?>" id="name-<?php echo $tmp; ?>" name="data[Seat][<?php echo $tmp; ?>][name]" />
										</li>
							<?php 
										$order++;

									} 
							?>
							</ul>
				<?php 	} ?>					
				<?php } ?>

			</div>
		</div>
		<div class="clearfix available-products ui-datepicker-row-break">
			<ul class="dc ui-datepicker-row-break ver-mspace">
				<li class="cancel-block mspace ver-space"><span class="available"></span><a href="#" title="<?php echo __l('Available');?>"><?php echo __l('Available');?></a></li>
				<li class="cancel-block mspace ver-space"><span class="unavailable"></span><a href="#" title="<?php echo __l('Unavailable');?>"><?php echo __l('Unavailable');?></a></li>
				<li class="cancel-block mspace ver-space"><span class="blocked"></span><a href="#" title="<?php echo __l('Blocked');?>"><?php echo __l('Blocked');?></a></li>
				<li class="cancel-block mspace ver-space"><span class="booked"></span><a href="#" title="<?php echo __l('Booked');?>"><?php echo __l('Booked');?></a></li>
				<li class="cancel-block mspace ver-space"><span class="noseats"></span><a href="#" title="<?php echo __l('No Seats');?>"><?php echo __l('No Seats');?></a></li>
			</ul>
		</div>
		<div class="dc clearfix">
			<div class="ver-space"></div>
			<?php
			    $bottom_hide = ($position == ConstStagePosition::Top)?'hide':'';
				echo $this->Html->image('bottom-bor.png', array('alt' => 'Stage', 'class' => 'bottom-bar-img ' . $bottom_hide));
				echo "<span class='dc clearfix textb bottom-screen $bottom_hide'> ". __l('Screen') .  " </span>";
			?>
			<div class="ver-space"></div>
		</div>
	</div>
</section>
<!--/Seat Section--->
