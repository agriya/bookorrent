<div class="halls index">
<?php
	if(!empty($this->request->params['admin'])) {
?>
	<ul class="breadcrumb top-mspace ver-space sep-bot">
		<li class = "active">
			<?php 
				echo $this->Html->link(__l('Dashboard'), array('controller'=>'users','action'=>'stats'), array('class' => 'js-no-pjax', 'escape' => false));
			?>
			<span class="divider"> / </span>
		</li>
		<li class = "active">
			<?php 
				 echo $this->Html->link(__l('Listings'), array('controller'=>'items', 'action'=>'admin_index'), array('class' => 'js-no-pjax', 'escape' => false));
			?>
			<span class="divider"> / </span>
		</li>
		<li>
			<?php 
				echo __l('Partition'); 
			?>
		</li>
	</ul>
<?php
	} else {
?>
	<h2 class="breadcrumb top-mspace ver-space sep-bot bot-mspace">
		<?php echo __l('Partition')." - ".$item['Item']['title'];?>
	</h2> 
<?php } ?>
			<div class="clearfix dc">
					<?php echo $this->Form->create('Item', array('class' => 'form-search bot-mspace big-input span', 'url'=>array('action' => 'partitions', 'slug' => $item['Item']['slug']))); ?>
					<?php echo $this->Form->input('partition_id', array('class'=>'span9 ver-mspace text-16','label' => false, 'multiple' => true)); ?>
					<?php echo $this->Form->submit(__l('Search'), array('class'=>'btn btn-large hor-mspace btn-primary textb text-16'));?>
					<?php echo $this->Form->end(); ?>
			</div>
<?php  echo $this->element('paging_counter');?>
<div class="ver-space">
	<div id="active-users" class="tab-pane active in no-mar">
		<table class="table no-round table-striped">
		<thead>
			<tr class="well no-mar no-pad">
				<th class="graydarkc sep-right dc" rowspan="2"><?php echo __l('Actions');?></th>
				<th class="graydarkc sep-right dc" rowspan="2"><?php echo $this->Paginator->sort('CustomPricePerNight.start_date', __l('Start'));?></th>
				<th class="graydarkc sep-right dc" rowspan="2"><?php echo $this->Paginator->sort('CustomPricePerType.start_time', __l('Time'));?></th>
				<th class="graydarkc sep-right dl" rowspan="2"><?php echo $this->Paginator->sort('CustomPricePerType.name', __l('Name'));?></th>
				<th class="graydarkc sep-right dl" rowspan="2"><?php echo $this->Paginator->sort('Partition.name', __l('Partition'));?></th>
				<th class="graydarkc sep-right dr" rowspan="2"><?php echo $this->Paginator->sort('CustomPricePerType.price',__l('Price'));?></th>				
				<th class="graydarkc sep-right sep-bot dc" colspan="5"><?php echo __l('Seats');?></th>				
			</tr>
			<tr class=" well no-mar no-pad">
				<th class="graydarkc sep-right dc"><?php echo $this->Paginator->sort('CustomPricePerType.available_seat_count', __l('Available'));?></th>				
				<th class="graydarkc sep-right dc"><?php echo $this->Paginator->sort('CustomPricePerType.unavailable_seat_count', __l('Unavailable'));?></th>				
				<th class="graydarkc sep-right dc"><?php echo $this->Paginator->sort('CustomPricePerType.blocked_count', __l('Blocked'));?></th>				
				<th class="graydarkc sep-right dc"><?php echo $this->Paginator->sort('CustomPricePerType.waiting_for_acceptance_count', __l('Waiting For Acceptance'));?></th>
				<th class="graydarkc sep-right dc"><?php echo $this->Paginator->sort('CustomPricePerType.booked_quantity', __l('Booked'));?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (!empty($item_partitions)){
				$i = 0;
				foreach ($item_partitions as $partition){									
					$class = '';
					$active_class = '';
			?>
					<tr class="<?php echo $class.' '.$active_class;?>">						
						<td class="dc">
							<span class="dropdown"> 
								<span title="<?php echo __l('Actions');?>" data-toggle="dropdown" class="graydarkc left-space hor-smspace icon-cog text-18 cur dropdown-toggle"> 
									<span class="hide"><?php echo __l('Actions');?></span> 
								</span>
								<ul class="dropdown-menu arrow no-mar dl">
									<li>
										<?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('controller'=>'custom_price_per_types_seats','action' => 'edit', $partition['Partition']['id'], $partition['CustomPricePerType']['id']), array('escape' => false, 'title' => __l('Edit')));?>
									</li>
									<li>
										<?php echo $this->Html->link('<i class="icon-th"></i>'.__l('Preview'), array('controller'=>'custom_price_per_types_seats', 'action' => 'preview', $partition['CustomPricePerType']['id'], $partition['Partition']['slug']), array('escape' => false,'class' => 'js-no-pjax', 'title' => __l('Preview')));?>
									</li>
								</ul>
							</span>
						</td>
						<td class="dc"><?php echo $this->Html->cDate($partition['CustomPricePerNight']['start_date']);?></td>
						<td class="dc"><?php echo $this->Html->cTime($partition['CustomPricePerType']['start_time']) .' - '. $this->Html->cTime($partition['CustomPricePerType']['end_time']);?></td>
						<td class="dl"><?php echo $this->Html->cText($partition['CustomPricePerType']['name']);?></td>
						<td class="dl"><?php echo $this->Html->cText($partition['Partition']['name']);?></td>
						<td class="dr"><?php echo $this->Html->cCurrency($partition['CustomPricePerType']['price']);?></td>
						<td class="dc"><?php echo $this->Html->cInt($partition['CustomPricePerType']['available_seat_count']);?></td>						
						<td class="dc"><?php echo $this->Html->cInt($partition['CustomPricePerType']['unavailable_seat_count']);?></td>
						<td class="dc"><?php echo $this->Html->cInt($partition['CustomPricePerType']['blocked_count']);?></td>
						<td class="dc"><?php echo $this->Html->cInt($partition['CustomPricePerType']['waiting_for_acceptance_count']);?></td>
						<td class="dc"><?php echo $this->Html->cInt($partition['CustomPricePerType']['booked_quantity']);?></td>
					</tr>
			<?php
				}
			}else{
			?>
				<tr>
					<td colspan="7">
						<div class="space dc">
							<p class="ver-mspace grayc top-space text-16 "><?php echo __l('No Partitions available');?></p>
						</div>
					</td>
				</tr>
			<?php
			}
			?>
		</tbody>
		</table>		
		<div class="js-pagination pagination pull-right no-mar mob-clr dc">  <?php echo $this->element('paging_links'); ?> </div>
	</div>
</div>
</div>