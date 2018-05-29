<ul class="breadcrumb top-mspace ver-space">
	<li><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'stats'), array('class' => 'js-no-pjax', 'escape' => false));?> <span class="divider">/</span></li>
	<li class="active"><?php echo __l('Listing Coupons'); ?></li>
</ul> 
<div class="tabbable ver-space sep-top top-mspace">
	<div id="list" class="tab-pane active in no-mar">
		<div class="clearfix">
			<?php 
			$class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Active) ? 'active' : null;
			echo $this->Html->link('<dl class="dc list users '. $class .' mob-clr mob-sep-none"><dt class="pr hor-mspace text-11 grayc"  title="'. __l('Enabled') .'">'. __l('Enabled') .'</dt><dd title="'. $this->Html->cInt($active,false) .'" class="textb text-20 no-mar graydarkc pr hor-mspace">'. $this->Html->cInt($active ,false) .'</dd></dl>', array('controller' => 'coupons', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('escape' => false, 'class' => 'no-under show pull-left mob-clr bot-space bot-mspace cur'));
			$class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) ? 'active' : null;
			echo $this->Html->link('<dl class="dc list users '. $class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'. __l('Disabled') .'">'. __l('Disabled') .'</dt><dd title="'. $this->Html->cInt($inactive,false) .'" class="textb text-20 no-mar graydarkc pr hor-mspace">'. $this->Html->cInt($inactive ,false) .'</dd></dl>', array('controller' => 'coupons', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('escape' => false, 'class' => 'no-under show pull-left mob-clr bot-space bot-mspace cur'));
			$class = (empty($this->request->params['named']['filter_id'])) ? 'active' : null;
			echo $this->Html->link('<dl class="dc list users '. $class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'. __l('Total') .'">'. __l('Total') .'</dt><dd title="'. $this->Html->cInt($active + $inactive,false) .'" class="textb text-20 no-mar graydarkc pr hor-mspace">'. $this->Html->cInt($active + $inactive,false) .'</dd></dl>', array('controller'=> 'coupons', 'action' => 'index'), array('escape' => false, 'class' => 'no-under show pull-left mob-clr bot-space bot-mspace cur'));		
			?>
		</div>
		<div class="clearfix dc">			
			<div class="pull-right top-space mob-clr dc top-mspace">		 
				<?php echo $this->Html->link('<span class="ver-smspace"><i class="icon-plus-sign no-pad top-smspace"></i></span>', array('controller' => 'coupons', 'action' => 'add'), array('escape' => false,'class' => 'add btn btn-primary textb text-18 whitec','title'=>__l('Add'))); ?>
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
							<th class="dc sep-right span2"><?php echo __l('Select'); ?></th>
							<th class="dc sep-right span2"><?php echo __l('Actions');?></th>
							<th class="sep-right dl"><?php echo $this->Paginator->sort('item.title', Configure::read('item.alt_name_for_item_singular_caps'));?></th>
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
							<td class="dc"><?php echo $this->Form->input('Coupon.'.$coupon['Coupon']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$coupon['Coupon']['id'], 'label' => "", 'class' => $status_class.' js-checkbox-list')); ?></td>
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
							<td class="dl"><?php echo $this->Html->link($this->Html->cText($coupon['Item']['title'], false), array('controller' => 'items', 'action' => 'view', $coupon['Item']['slug'],  'admin' => false), array('title'=>$this->Html->cText($coupon['Item']['title'],false),'escape' => false));?></td>
							<td class="dl"><?php echo $this->Html->cText($coupon['Coupon']['name']);?></td>
							<td class="dc"><?php echo $this->Html->cFloat($coupon['Coupon']['discount']);?></td>
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
				<?php
					if (!empty($coupons)):
				?>
				<div class="admin-select-block ver-mspace pull-left mob-clr dc">
					<div class="span top-mspace">
						<span class="graydarkc"><?php echo __l('Select:'); ?></span>
						<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-select js-no-pjax {"checked":"js-checkbox-list"} hor-smspace grayc','title' => __l('All'))); ?>
						<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-select js-no-pjax {"unchecked":"js-checkbox-list"} hor-smspace grayc','title' => __l('None'))); ?>
						<?php echo $this->Html->link(__l('Enable'), '#', array('class' => 'js-select js-no-pjax {"checked":"js-checkbox-active","unchecked":"js-checkbox-inactive"} hor-smspace grayc','title' => __l('Enable'))); ?>    
						<?php echo $this->Html->link(__l('Disable'), '#', array('class' => 'js-select js-no-pjax {"checked":"js-checkbox-inactive","unchecked":"js-checkbox-active"} hor-smspace grayc','title' => __l('Disable'))); ?>
					</div>
					<?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit js-no-pjax span5', 'div'=>false,'label' => false, 'empty' => __l('-- More actions --'))); ?></span>
				</div>
				<?php 
					endif; 
				?>
				<div class="js-pagination pagination pull-right no-mar mob-clr dc">
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