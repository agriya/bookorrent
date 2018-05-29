<div class="js-responses js-response">
	<div class="items index row no-mar">
		<?php 
		$filter_class = '';
		if(!empty($this->request->params['isAjax'])) {
			$filter_class = 'js-filter-link js-no-pjax';
		}
		if(empty($this->request->params['isAjax'])) { ?>
			<h2 class="ver-space sep-bot top-mspace text-32 sep-bot" ><?php echo __l('My') . ' ' . Configure::read('item.alt_name_for_item_plural_caps');?></h2>
		<?php } ?>
		<section class="row ver-space bot-mspace clearfix <?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?>">
			<div class="span20 pull-left">
				<?php $class=(empty($this->request->params['named']['status']) && !empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myitems')? 'active':NULL;?>
				<?php
				$link = array_merge(array('controller'=>'items','action'=>'index','type' => 'myitems'));
				echo $this->Html->link( '
					<dl class="dc sep-left sep-right list users '.$class .' mob-clr mob-sep-none ">					         	
						<dt class="pr hor-mspace text-11 grayc">'.__l('All').'</dt>
						<dd title="'.$all_count.'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($all_count, false).'</dd>                  	
					</dl>'
					,  $link, array('escape' => false, 'class' => $filter_class));
				?>
				<?php if (Configure::read('item.item_fee')): ?>
             	<?php $class=(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myitems' && !empty($this->request->params['named']['status'])&& $this->request->params['named']['status'] == 'pending')?'active':NULL;?>
				<?php
				$link = array_merge(array('controller'=>'items', 'action'=>'index', 'type' => 'myitems', 'status'=>'pending'));
				echo $this->Html->link( '
					<dl class="dc sep-left sep-right list users '.$class .' mob-clr mob-sep-none ">					         	
						<dt class="pr hor-mspace text-11 grayc">'. __l('Payment Pending').'</dt>
						<dd title="'.$pending_count.'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($pending_count, false).'</dd>                  	
					</dl>'
					,$link , array('escape' => false, 'class' => $filter_class));
				?>
				<?php endif; ?>
				<?php $class=(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myitems' && !empty($this->request->params['named']['status'])&& $this->request->params['named']['status'] == 'active')?'active':NULL;?>
				<?php
				$link = array_merge(array('controller'=>'items', 'action'=>'index', 'type' => 'myitems', 'status'=>'active'));
				echo $this->Html->link( '
					<dl class="dc sep-left sep-right list users '.$class .' mob-clr mob-sep-none ">					         	
						<dt class="pr hor-mspace text-11 grayc">'.__l('Enabled').'</dt>
						<dd title="'.$active_count.'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($active_count, false) .'</dd>                  	
					</dl>'
					,$link , array('escape' => false, 'class' => $filter_class));
				?>
				<?php $class=(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myitems' && !empty($this->request->params['named']['status'])&& $this->request->params['named']['status'] == 'inactive')?'active':NULL;?>
				<?php
				$link = array_merge(array('controller'=>'items', 'action'=>'index', 'type' => 'myitems', 'status'=>'inactive'));
				echo $this->Html->link( '
					<dl class="dc sep-left sep-right list users '.$class .' mob-clr mob-sep-none ">					         	
						<dt class="pr hor-mspace text-11 grayc">'.__l('Disabled').'</dt>
						<dd title="'.$inactive_count.'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($inactive_count, false).'</dd>                  	
					</dl>'
					, $link, array('escape' => false, 'class' => $filter_class));
				?>
				<?php $class=(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myitems' && !empty($this->request->params['named']['status'])&& $this->request->params['named']['status'] == 'waiting_for_approval')?'active':NULL;?>
				<?php
				$link = array_merge(array('controller'=>'items', 'action'=>'index', 'type' => 'myitems', 'status'=>'waiting_for_approval'));
				echo $this->Html->link( '
					<dl class="dc sep-left sep-right list users '.$class .' mob-clr mob-sep-none ">					         	
						<dt class="pr hor-mspace text-11 grayc">'.__l('Waiting for approval').'</dt>
						<dd title="'.$waiting_for_approval_count.'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($waiting_for_approval_count, false).'</dd>                  	
					</dl>'
					,$link , array('escape' => false, 'class' => $filter_class));
				?>
        	   <?php $class=(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'myitems' && !empty($this->request->params['named']['status'])&& $this->request->params['named']['status'] == 'imported')?'active':'';?>
		</div>
		<?php if(empty($this->request->params['isAjax'])) { ?>
		<div class="span4 pull-left no-mar">
        <?php
            $day1= date("D j", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
            $day2=date("D j", mktime(0, 0, 0, date("m"),date("d")-2,date("Y")));
            $day3=date("D j", mktime(0, 0, 0, date("m"),date("d")-3,date("Y")));
            $axis1=ceil($chart_data['max_count']/3);
            $axis2=ceil($chart_data['max_count']/3)*2;
            $axis3=ceil($chart_data['max_count']/3)*3;
            	 
				 $image_url='http://chart.apis.google.com/chart?chf=a,s,000000FA|bg,s,67676700&amp;chxl=0:|0|'.$day3.'|'.$day2.'|'.$day1.'|1:|0|'.$axis1.'|'.$axis2.'|'.$axis3.'&amp;chxs=0,676767,11.5,0,lt,676767&amp;chxtc=0,4&amp;chxt=x,y&amp;chs=200x100&amp;cht=lxy&amp;chco=0066E4,F47564&amp;chds=0,3,0,'.$axis3.',0,3,0,'.$axis3.'&amp;chd=t:1,2,3|'. $chart_data['ItemView'][3]['count'].','.$chart_data['ItemView'][2]['count'].','.$chart_data['ItemView'][1]['count'].'|1,2,3|'.$chart_data['ItemUser'][3]['count'].','.$chart_data['ItemUser'][2]['count'].','.$chart_data['ItemUser'][1]['count'].'&amp;chdl=Views|Bookings&amp;chdlp=b&amp;chls=2,4,1|1&amp;chma=5,5,5,25';
            echo $this->Html->image($image_url);
		?>
		</div>
		<?php } ?>
	</section>
	<section class="row no-mar bot-space">
	<?php 
		echo $this->Form->create('Item' , array('class' => 'normal','action' => 'update'));  
		echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
		$view_count_url = Router::url(array(
			'controller' => 'items',
			'action' => 'update_view_count',
		), true);
		if (!empty($items)):
	?>
		<table class="table my-item-pad ver-mspace js-view-count-update {'model':'item','url':'<?php echo $this->Html->cText($view_count_url, false); ?>'}">
		<?php
$i = 0;
foreach ($items as $item):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	$is_seating_enable = false;
	foreach ($item['CustomPricePerNight'] as $cppn){
		if(!empty($cppn['is_seating_selection'])){
			$is_seating_enable = true;
		}
	}
?>
	
	<tr class="js-even well no-mar no-pad">
		<th class="items-title items-title-section actions graydarkc sep-bot sep-right">
			<div class="clearfix items-info-block span6">
				<div class="span clearfix"><h3 class="dl htruncate span6"><?php echo $this->Html->link($this->Html->cText($item['Item']['title'],false), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'] ,'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false, 'class' => 'js-bootstrap-tooltip graydarkc')); ?></h3></div>
			</div>
			<div class="select-block table-select-block dl">
              	<?php if (empty($item['Item']['is_active'])): ?>
					<span class="label pull-left smspace mob-inline"> <?php echo __l('Disabled'); ?> </span>
				<?php endif; ?>
				<?php if(empty($item['Item']['is_approved'])): ?>
				    <span class="label label-pendingapproval pull-left smspace mob-inline" title="<?php	echo __l('Waiting for Approval'); ?>">	<?php	echo __l('Waiting for Approval'); ?></span>
				<?php endif; ?>
				<?php if (empty($item['Item']['is_paid']) && Configure::read('item.item_fee')): ?>
				    <span class="label label-paymentpending pull-left smspace mob-inline" title="<?php	echo __l('Payment Pending'); ?>">	<?php	echo __l('Payment Pending'); ?></span>
				<?php endif; ?>
				<?php if (!empty($item['Item']['is_featured'])): ?>
					<span class="label featured pull-left smspace mob-inline" title="<?php echo __l('Featured'); ?>">	<?php echo __l('Featured'); ?></span>
				<?php endif; ?>				
            </div>
		</th>
		<th colspan="3" class="graydarkc sep-bot dc sep-right"><?php echo __l('Booked'); ?></th>
		<th colspan="3" class="graydarkc sep-bot dc sep-right"><?php echo __l('Revenue');?></th>
		<th colspan="2" class="graydarkc sep-bot dc sep-right"><?php echo __l('Activities');?></th>
	</tr>
	
	<tr class="sub-title">
	<td rowspan="2" class="actions items-title dl graydarkc sep-bot ">
			<div class="clearfix items-action-block">
				<div class="pull-left hor-space hor-mspace">
					<div class="span1 clearfix"><div class="top-space"><?php echo $this->Form->input('Item.'.$item['Item']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$item['Item']['id'],  'class' => 'js-checkbox-list', 'label' => "")); ?></div></div>
					<div class="dropdown"> 
						<a data-toggle="dropdown" class="dropdown-toggle text-14 textb graylighterc no-shad js-no-pjax" title="<?php echo __l('Action'); ?>" href="#"><i class="icon-cog graylightc no-pad text-16"></i></a>
						<ul class="dropdown-menu dl arrow">
							<li><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action' => 'edit', $item['Item']['id']), array('class' => 'edit js-edit graydarkc', 'title' => __l('Edit'),'escape'=>false));?></li>
							<li><?php echo $this->Html->link('<i class=" icon-calendar"></i>'.__l('Calendar'), array('controller' => 'item_users', 'action' => 'index', 'type'=>'myworks', 'item_id' => $item['Item']['id'], 'admin' => false), array('title' => __l('Calendar'),'class' => 'calendar graydarkc','escape'=>false));?></li>
							<?php if (empty($item['Item']['is_paid']) && Configure::read('item.item_fee')): ?>
								<li><?php echo $this->Html->link('<i class="icon-money"></i>'. sprintf(__l('Pay %s Fee'), Configure::read('item.alt_name_for_item_singular_caps')), array('controller' => 'items', 'action' => 'item_pay_now', $item['Item']['id'], 'admin' => false), array('title' => sprintf(__l('Pay %s Fee'), Configure::read('item.alt_name_for_item_singular_caps')),'class' => 'item-fee graydarkc','escape'=>false, 'escape'=>false));?></li>
							<?php endif; ?>
							<li>
							<?php 
								if (empty($item['Item']['is_active'])) {
									echo $this->Html->link('<i class="icon-ok-circle"></i>'.__l('Enable'), array('controller' => 'items', 'action' => 'updateactions', $item['Item']['id'], 'active', 'admin' => false, '?r=' . $this->request->url), array('title' => __l('Enable'),'class' => 'enable graydarkc','escape'=>false));
								} elseif(!empty($item['Item']['is_active'])) {
									echo $this->Html->link('<i class="icon-remove-circle"></i>'.__l('Disable'), array('controller' => 'items', 'action' => 'updateactions', $item['Item']['id'], 'inactive', 'admin' => false, '?r=' . $this->request->url), array('title' => __l('Disable'),'class' => 'disable graydarkc','escape'=>false));
								} 
							?>
							</li>
							<?php if(isPluginEnabled('Coupons')) { ?>
							<li>
								<?php echo $this->Html->link('<i class="icon-tags"></i>'.__l('Coupons'), array('controller' => 'coupons', 'action' => 'index', $item['Item']['id'], 'admin' => false), array('title' => __l('Coupons'),'class' => 'graydarkc','escape'=>false)); ?>
							</li>
							<?php } ?>
							<li>
								<?php echo $this->Html->link('<i class="icon-cogs"></i>'.__l('Booking Settings'), array('controller' => 'buyer_form_fields', 'action' => 'index', $item['Item']['id'], 'admin' => false), array('title' => __l('Booking Settings'),'class' => 'graydarkc','escape'=>false)); ?>
							</li>
							<?php if (isPluginEnabled('Seats') && !empty($is_seating_enable)) { ?>
									<li>
										<?php echo $this->Html->link('<i class="icon-th"></i>'.__l('Partitions'), array('controller' => 'items', 'action' => 'partitions', 'slug' =>$item['Item']['slug'], 'admin' => false), array('title' => __l('Partitions'),'class' => 'graydarkc','escape'=>false)); ?>
									</li>
							<?php } ?>							
						</ul>
					</div>
				</div>
				<div class="pull-left">
					<?php
						$item['Attachment'][0] = !empty($item['Attachment'][0]) ? $item['Attachment'][0] : array();
						echo $this->Html->link($this->Html->showImage('Item', $item['Attachment'][0], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false, 'class' => 'prop-img'));
					?>
				</div>
			</div>
		</td>
		<th class="graydarkc sep-bot dc"><div class="js-pagination"><?php echo $this->Paginator->sort('sales_pending_count',__l('Pending'), array('class' => 'js-no-pjax'));?></div></th>
        <th class="graydarkc sep-bot dc"><div class="js-pagination"><?php echo $this->Paginator->sort('sales_pipeline_count',__l('Active'), array('class' => 'js-no-pjax'));?></div></th>
        <th class="graydarkc sep-bot dc"><div class="js-pagination"><?php echo $this->Paginator->sort('sales_completed_count',__l('Completed'), array('class' => 'js-no-pjax'));?></div></th>
		<th class="graydarkc sep-bot dc"><div class="js-pagination"><?php echo $this->Paginator->sort('sales_pipeline_amount',__l('Pipeline').' ('.Configure::read('site.currency').')', array('class' => 'js-no-pjax'));?></div></th>
        <th class="graydarkc sep-bot dc"><div class="js-pagination"><?php echo $this->Paginator->sort('sales_cleared_amount',__l('Cleared').' ('.Configure::read('site.currency').')', array('class' => 'js-no-pjax'));?></div></th>
        <th class="graydarkc sep-bot"><div class="js-pagination"><?php echo $this->Paginator->sort('sales_lost_amount',__l('Lost').' ('.Configure::read('site.currency').')', array('class' => 'js-no-pjax'));?></div></th>
		<th class="graydarkc sep-bot"><div class="js-pagination"><?php echo $this->Paginator->sort('item_view_count',__l('Views'), array('class' => 'js-no-pjax'));?></div></th>
		<th class="graydarkc sep-bot"><div class="js-pagination"><?php echo $this->Paginator->sort('item_feedback_count',__l('Feedback'), array('class' => 'js-no-pjax'));?></div></th>
	</tr>
	
	<tr <?php echo $class;?>>
		<td class="dc sep-bot"><span><?php echo $this->Html->cInt($item['Item']['sales_pending_count']);?></span></td>
		<td class="dc sep-bot"><?php echo $this->Html->cInt($item['Item']['sales_pipeline_count']);?></td>
		<td class="dc sep-bot"><?php echo $this->Html->cInt($item['Item']['sales_completed_count']);?></td>
		<td class="dr sep-bot"><span class="highlight-pipeline tb"><?php echo $this->Html->cCurrency($item['Item']['sales_pipeline_amount']);?></span></td>
		<td class="dr sep-bot"><span class="highlight-cleared tb"><?php echo  $this->Html->cCurrency($item['Item']['revenue']);?></span></td>
		<td class="dr sep-bot"><span class="highlight-lost tb"><?php echo $this->Html->cCurrency($item['Item']['sales_lost_amount']);?></span></td>
		<td class="dc sep-bot js-view-count-item-id js-view-count-item-id-<?php echo $this->Html->cInt($item['Item']['id'], false); ?> {'id':'<?php echo $this->Html->cInt($item['Item']['id'], false); ?>'}"><?php echo $this->Html->cInt($item['Item']['item_view_count']);?></td>
		<td class="dc sep-bot"><?php echo $this->Html->cInt($item['Item']['item_feedback_count']);?></td>
	</tr>
	<tr><td colspan="12" class="empty-list no-bor">&nbsp;</td></tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="15">
		<div class="sep-top ">
			<p class=" space dc grayc ver-mspace top-space text-16"><?php echo sprintf(__l('No %s available'), Configure::read('item.alt_name_for_item_plural_caps'));?></p>
		</div>
		</td>
	</tr>
<?php
endif;
?>
</table>


<?php
if (!empty($items)) :?>
	<div class="select-block  ver-mspace pull-left mob-clr dc span9">
	<div class="span top-mspace">
		 <span class="graydarkc">
		<?php echo __l('Select:'); ?>
		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-select-all hor-smspace grayc','title' => __l('All'))); ?>
		<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-select-none hor-smspace grayc','title' => __l('None'))); ?>
		</span>
	</div>
		<?php echo $this->Form->input('more_action_id', array('class' => 'span5 js-admin-index-autosubmit js-no-pjax', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
	</div>
<?php endif; ?>
<?php
if (!empty($items)) { ?>
	<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?> pagination pull-right no-mar mob-clr dc">
<?php echo $this->element('paging_links'); ?>
	</div>
<?php	}
?>
<?php
    echo $this->Form->end();
?>
</section>
</div>
</div>