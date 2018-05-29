<div class="tabbable ver-space top-mspace">
	<h2 class="ver-space sep-bot top-mspace text-32 sep-bot" ><?php echo __l('Coupons') . ' - ' . $this->Html->cText($item['Item']['title'],false);?></h2>
	<div id="list" class="tab-pane active in no-mar">
		<div class="clearfix dc">			
			<div class="pull-right top-space mob-clr dc top-mspace">		 
				<?php echo $this->Html->link('<span class="ver-smspace"><i class="icon-plus-sign no-pad top-smspace"></i></span>', array('controller' => 'coupons', 'action' => 'add', $item['Item']['id']), array('escape' => false,'class' => 'add btn btn-primary textb text-18 whitec','title'=>__l('Add'))); ?>
			</div>
		</div>
		<?php echo $this->element('paging_counter');?>
		<?php echo $this->Form->create('Coupon' , array('class' => 'normal','action' => 'update')); ?>
		<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
		<div class="ver-space">
			<div id="active-users" class="tab-pane active in no-mar">
				<table class="table no-round table-striped">
					<thead>
						<tr class=" well no-mar no-pad">
							<th class="dc sep-right span2"><?php echo __l('Actions');?></th>
							<th class="sep-right dl"><?php echo $this->Paginator->sort('name', __l('Coupon Code'));?></th>
							<th class="sep-right dc"><?php echo $this->Paginator->sort('discount', __l('Discount') . ' (%)');?></th>
							<th class="sep-right dc"><?php echo $this->Paginator->sort('number_of_quantity', __l('Quantity'));?></th>
							<th class="sep-right dc"><?php echo $this->Paginator->sort('number_of_quantity_used', __l('Quantity Used'));?></th>
							<th class="sep-right dc"><?php echo $this->Paginator->sort('is_active', __l('Active?'));?></th>
						</tr>
					</thead>
					<tbody>
					<?php
						if (!empty($coupons)):
							$i = 0;
							foreach ($coupons as $coupon):
								$class = null;
								$active_class = '';
								if ($i++ % 2 == 0) :
								 $class = 'altrow';
								endif;
								if($coupon['Coupon']['is_active']):
									$status_class = 'js-checkbox-active';
								else:
									$active_class = 'disable';
									$status_class = 'js-checkbox-inactive';
								endif;
					?>
						<tr class="<?php echo $class.' '.$active_class;?>">
							<td class="dc">
								<span class="dropdown">
									<span title="<?php echo __l('Actions'); ?>" data-toggle="dropdown" class="graydarkc left-space hor-smspace icon-cog text-18 cur dropdown-toggle">
										<span class="hide"><?php echo __l('Action'); ?></span>
									</span>
									<ul class="dropdown-menu arrow no-mar dl">
										<li><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action' => 'edit', $coupon['Coupon']['id']), array('escape' => false,'class' => 'delete', 'title' => __l('Edit')));?></li>
										<li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action' => 'delete', $coupon['Coupon']['id']), array('escape' => false,'class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
									</ul>
								</span>
                            </td>
							<td class="dl"><?php echo $this->Html->cText($coupon['Coupon']['name']);?></td>
							<td class="dc"><?php echo $this->Html->cText($coupon['Coupon']['discount']);?></td>
							<td class="dc"><?php echo $this->Html->cInt($coupon['Coupon']['number_of_quantity']);?></td>
							<td class="dc"><?php echo $this->Html->cInt($coupon['Coupon']['number_of_quantity_used']);?></td>
							<td class="dc"><?php echo $this->Html->cBool($coupon['Coupon']['is_active']);?></td>
						</tr>
					<?php
							endforeach;
						else:
					?>
						<tr>
							<td colspan="8"><div class="space dc grayc"><p class="ver-mspace top-space text-16 "><?php echo __l('No coupons available');?></p></div></td>
						</tr>
					<?php
						endif;
					?>
					</tbody>
				</table>
				<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?> pagination pull-right no-mar mob-clr dc">
					<?php echo $this->element('paging_links'); ?>
				</div>
			</div>
			<div class="hide">
				<?php echo $this->Form->submit(__l('Submit'));  ?>
			</div>
			<?php
				echo $this->Form->end();
			?>
		</div>
	</div>
</div>