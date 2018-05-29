<div class="itemUsers index js-response js-responses">
	<?php if(empty($this->request->params['isAjax'])) { ?>
	<div class="clearfix sep-bot">
		<h2 class="ver-space top-mspace text-32 pull-left"><?php echo __l('Booking');?></h2>
		<div class="jobs-inbox-option show-block  clearfix pull-right top-mspace top-space">
			<div class="dropdown top-mspace top-space"> 
				<a data-toggle="dropdown" class="dropdown-toggle text-14 textb graylighterc no-shad" title="<?php echo __l('Layout'); ?>" href="#"><i class="icon-list-alt no-pad text-16"></i> <?php echo __l('Layout'); ?></a>
				<ul class="dropdown-menu dl arrow">
					<li><?php echo $this->Html->link('<i class="icon-list"></i>'.__l('List'), array('controller'=> 'item_users', 'action'=>'index', 'type'=>'mytours', 'status' => $this->request->params['named']['status'], 'view' => 'list', 'admin' => false), array('title' => __l('List'), 'class' => 'list ','escape' => false));?></li>
					<li class="active"><?php echo $this->Html->link('<i class="icon-th"></i>'.__l('Grid'), array('controller'=> 'item_users', 'action'=>'index', 'type'=>'mytours', 'status' => $this->request->params['named']['status'], 'admin' => false), array('title' => __l('Grid'), 'class' => 'grid status_selected','escape' => false));?></li>
				</ul>
			</div>
		</div>	
	</div>
	<?php } else { ?>
	<div class="clearfix sep-bot">
		<h2 class="ver-space top-mspace text-32 pull-left"><?php echo __l('Booking');?></h2>
		<div class="jobs-inbox-option show-block  clearfix ">
			<div class="dropdown top-mspace top-space pull-right"> 
				<a data-toggle="dropdown" class="dropdown-toggle right-mspace text-14 textb graylighterc no-shad js-no-pjax" title="<?php echo __l('Layout'); ?>" href="#"><i class="icon-list-alt no-pad text-16"></i> <?php echo __l('Layout'); ?></a>
				<ul class="dropdown-menu dl arrow arrow-right right-mspace js-pagination">
					<li><?php echo $this->Html->link('<i class="icon-list"></i>'.__l('List'), array('controller'=> 'item_users', 'action'=>'index', 'type'=>'mytours', 'status' => $this->request->params['named']['status'], 'view' => 'list', 'admin' => false), array('title' => __l('List'), 'class' => 'list js-no-pjax','escape' => false));?></li>
					<li class="active"><?php echo $this->Html->link('<i class="icon-th"></i>'.__l('Grid'), array('controller'=> 'item_users', 'action'=>'index', 'type'=>'mytours', 'status' => $this->request->params['named']['status'], 'admin' => false), array('title' => __l('Grid'), 'class' => 'grid status_selected js-no-pjax','escape' => false));?></li>
				</ul>
			</div>
		</div>
	</div>
	<?php } ?>
	<section class="row ver-space bot-mspace clearfix">		
	<?php 
		$stat_class = (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'all') ? 'active' : null;
		$active_filter=(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'all') ? 'active-filter' : null;
		echo $this->Html->link('<dl class="dc list users '.$stat_class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc">'.__l('All').'</dt><dd title="'.$all_count.'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt((!empty($all_count) ? $all_count : '0'),false).'</dd></dl>',  array('controller'=>'item_users','action'=>'index','status' => 'all', 'type' => 'mytours', 'status' => 'all', 'view' => 'list', 'admin' => false), array('escape' => false, 'class' => 'js-filter-link js-no-pjax'));				
		$arr_count = count($moreActions);
		$i = 0;
		foreach($moreActions as $key => $value) {
			$counts = explode(":", $key);
			$class_name = $itemStatusClass[$value] ? $itemStatusClass[$value] :"";
			$stat_class = (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == $value) ? 'active' : null;
			$i++;
			$bclass = ($arr_count != $i)? ' sep-right' : '';
			echo $this->Html->link('<dl class="dc list users sep-left '.$stat_class .' mob-clr mob-sep-none'. $bclass.' "><dt class="pr hor-mspace text-11 grayc">'.$counts[0].'</dt><dd title="'.$counts[1].'" class="textb text-20 no-mar graydarkc pr hor-mspace ">'.$this->Html->cInt($counts[1], false).'</dd></dl>',  array('controller'=>'item_users','action'=>'index','status' => $value, 'type'=>'mytours', 'status' => $value,'view'=>'list','admin' => false), array('escape' => false, 'class' => 'js-filter-link js-no-pjax'));
			 } 
	?>		
	</section>
	<section class="row no-mar bot-space">
	<?php if (!empty($itemUsers)): ?>
		<div class="clearfix"><?php echo $this->element('paging_counter');?></div>
	<?php endif; ?>
		<div class="ver-space">
			<table class="js-response-actions table list no-round table-striped">
				<thead>
					<tr class="well no-mar no-pad">
						<th class="graydarkc sep-right" rowspan="2"><?php echo __l('Action'); ?></th>
						<th class="graydarkc sep-right sep-bot dc" colspan="3"><?php echo __l('Booking Details'); ?></th>
						<th class="graydarkc sep-right " rowspan="2" class="dl"><div class="item-title"><?php echo $this->Paginator->sort('Item.title', Configure::read('item.alt_name_for_item_singular_caps')); ?></div></th>
						<th class="graydarkc sep-right " rowspan="2"><?php echo $this->Paginator->sort('User.username',__l('Host')); ?></th>
						<th class="graydarkc sep-right " rowspan="2"><?php echo $this->Paginator->sort('price', __l('Gross') . ' ('. Configure::read('site.currency') . ')'); ?></th>
						<?php if(empty($this->request->params['named']['status']) || $this->request->params['named']['status']=='negotiation_requested' || $this->request->params['named']['status']=='negotiation_rejected' || $this->request->params['named']['status']=='negotiation_confirmed'): ?>
						<th class="graydarkc sep-right " rowspan="2"><?php echo $this->Paginator->sort('ItemUserStatus.name',__l('Current Status')); ?></th>
						<?php endif; ?>
						<th class="graydarkc" rowspan="2"><?php echo $this->Paginator->sort('created', __l('Booked On'));?></th>
					</tr>
					<tr class="well no-mar no-pad">
						<th class="graydarkc sep-right"><?php echo $this->Paginator->sort('from',__l('ID')); ?></th>
						<th class="graydarkc sep-right"><?php echo $this->Paginator->sort('from',__l('From')); ?></th>
						<th class="graydarkc sep-right"><?php echo $this->Paginator->sort('to',__l('To')); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php
					if (!empty($itemUsers)):
						$i = 0;
						foreach ($itemUsers as $itemUser):
				?>
					<tr>
						<td class="actions dc">			 
							<div class="dropdown"> 
								<a data-toggle="dropdown" class="dropdown-toggle text-14 textb graylighterc no-shad js-no-pjax" title="<?php echo __l('Action'); ?>" href="#"><i class="icon-cog graylightc no-pad text-16"></i></a>
								<ul class="dropdown-menu dl arrow">
								<?php 
										if(!empty($itemUser['ItemUser']['is_payment_cleared'])) { 
								?>
									<li><?php echo $this->Html->link('<i class="icon-print"></i>'.__l('Print Ticket'), array('controller' => 'item_user', 'action' => 'view', $itemUser['ItemUser']['id'], 'type'=>'print', 'admin' => false), array('class' => 'print-ticket dl js-no-pjax', 'target' => '_blank', 'title'=>__l('Print Ticket'), false, 'escape' => false)); ?></li>
								<?php 
										} 
										if ($itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforAcceptance ) {
								?>
									<li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Cancel'), array('controller' => 'items', 'action' => 'order', $itemUser['ItemUser']['item_id'] , 'order_id'=>$itemUser['ItemUser']['id'], 'type' => 'cancel', 'admin' => false),array('title' => __l('Cancel') ,'class'=>'js-delete delete','escape' => false));  ?></li>
								<?php 
										}  
										if($this->Auth->user('id') == $itemUser['ItemUser']['user_id'] && $itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforReview ) {
								?>
									<li><?php echo $this->Html->link('<i class=" icon-refresh"></i>'.__l('Review'), array('controller'=>'item_feedbacks','action'=>'add','item_order_id' => $itemUser['ItemUser']['id']), array('class' =>'review', 'title' => __l('Review'),'escape' => false)); ?></li>
								<?php 
										} 
								?>
									<li> <?php echo $this->Html->link('<i class="icon-eye-open"></i>'.__l('View activities'), array('controller' => 'messages', 'action' => 'activities',  'order_id' => $itemUser['ItemUser']['id']), array('class' =>'view-activities','escape' => false)); ?> </li>
								<?php 
									$note_url = Router::url(array(
										'controller' => 'messages',
										'action' => 'activities',
										'order_id' => $itemUser['ItemUser']['id'],
									) , true);
								?>
									<li><?php echo $this->Html->link('<i class="icon-bookmark"></i>'.__l('Private Note'), $note_url.'#Private_Note', array('class' => 'add-note',  'title' => __l('Private Note'),'escape' => false)); ?></li>
								<?php 
									if(($this->request->params['named']['status'] == 'booking_request_confirmed' && !empty($itemUser['ItemUser']['is_booking_request'])) || $this->request->params['named']['status'] == 'payment_pending'){ 
								?>
									<li><?php echo $this->Html->link('<i class="icon-save"></i>'.__l('Book It'), array('controller' => 'items', 'action' => 'order', $itemUser['ItemUser']['item_id'], 'order_id:' . $itemUser['ItemUser']['id'], 'admin' => false), array('class' => 'book-it', 'title' => __l('Book It'),'escape' => false)); ?></li>
								<?php 
									} 
								?>
								</ul>
							</div>
						</td>
						<td><?php echo $this->Html->cInt($itemUser['ItemUser']['id'], false);?></td>
						<td><?php echo $this->Html->cDateTime($itemUser['ItemUser']['from']);?></td>
						<td><?php echo $this->Html->cDateTime(getToDate($itemUser['ItemUser']['to']));?></td>
						<td class="dl status-data">
							<?php 
							echo $this->Html->link($this->Html->cText($itemUser['Item']['title'],false), array('controller'=> 'items', 'action' => 'view', $itemUser['Item']['slug']), array('title' => $this->Html->cText($itemUser['Item']['title'], false), 'class' => 'graydarkc textb', 'escape' => false));
							?>
							<span class="grayc top-smspace show mob-dc mob-clr">
							<?php 
								if(!empty($itemUser['Item']['Country']['iso_alpha2'])): 
							?>
								<span title="<?php echo $this->Html->cText($itemUser['Item']['Country']['name'], false); ?>" class="flags mob-inline top-smspace flag-<?php echo strtolower($itemUser['Item']['Country']['iso_alpha2']); ?>"><?php echo $itemUser['Item']['Country']['name']; ?></span>
							<?php 
								endif; 
							?>
							</span>
							<div title="" class="htruncate js-bootstrap-tooltip no-mar span6 bot-space" data-original-title="<?php echo $this->Html->cText($itemUser['Item']['address'], false);?>"><?php echo $this->Html->cText($itemUser['Item']['address']);?></div>
							<span>
							<?php
								if(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'all'):
									$class = '';
									if ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance) {
										$class = 'label label-pendingapproval';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Confirmed) {
										$class = 'label label-confirmed';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Rejected) {
										$class = 'label label-reject';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Canceled) {
										$class = 'label label-cancel';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::CanceledByAdmin) {
										$class = 'label label-cancelbyadmin';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforReview) {
										$class = 'label label-review';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Completed) {
										$class = 'label label-completed';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Expired) {
										$class = 'label';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::PaymentPending) {
										$class = 'label label-paymentpending';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::BookingRequest) {
										$class = 'label label-bookingrequest';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::BookingRequestConfirmed) {
										$class = 'label label-bookingrequestconfirmed';
									}
							?>
								<span class="<?php echo $class; ?>" title="<?php echo $this->Html->cText($itemUser['ItemUserStatus']['name'], false);?>"><?php echo $this->Html->cText($itemUser['ItemUserStatus']['name'], false);?></span>
							<?php
								endif;
							?>
							</span>
						</td>
						<td>
							<?php echo !empty($itemUser['Item']['User']['username']) ? $this->Html->link($this->Html->cText($itemUser['Item']['User']['username'], false), array('controller' => 'users', 'action' => 'view', $itemUser['Item']['User']['username'] ,'admin' => false), array('title'=>$this->Html->cText($itemUser['Item']['User']['username'],false),'escape' => false)) : ''; ?>
							<?php if(!empty($itemUser['ItemUser']['booker_private_note'])): ?>
							<span class="info" title="<?php echo $this->Html->cHtml($itemUser['ItemUser']['booker_private_note'], false); ?>">&nbsp;</span>
							<?php endif; ?>
						</td>
						<td class="dr">
							<?php 
							$booker_gross = ($itemUser['ItemUser']['original_price'] + $itemUser['ItemUser']['booker_service_amount'] + $itemUser['ItemUser']['additional_fee_amount']) - $itemUser['ItemUser']['coupon_discount_amont'];		
							if($booker_gross > 0){
								echo $this->Html->cCurrency($booker_gross);
							}else{
								echo __l('Free'); 
							}
							?>
						</td>
						<td><?php echo $this->Html->cDateTimeHighlight($itemUser['ItemUser']['created']);?></td>
					</tr>
				<?php
						endforeach;
					else:
				?>
					<tr>
						<td colspan="15"><div class="space dc grayc"><p class="ver-mspace top-space text-16 "><?php echo __l('No Bookings available');?></p></div></td>
					</tr>
				<?php
					endif;
				?>
				</tbody>
			</table>
		</div>
		<?php if (!empty($itemUsers)) { ?>
		<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?> clearfix space pull-right mob-clr dc">
			<?php echo $this->element('paging_links'); ?>
		</div>
		<?php } ?> 
	</section>
</div>