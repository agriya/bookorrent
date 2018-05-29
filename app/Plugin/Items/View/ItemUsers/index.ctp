<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="itemOrders index js-response  js-responses">
<h2 class="ver-space top-mspace text-32 sep-bot"><?php echo __l('Calendar');?></h2>
<section class="row ver-space bot-mspace clearfix">
		<?php
			$stat_class = (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'all') ? 'active' : null;
			$active_filter=(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'all') ? 'active-filter' : null;
		?>
		<?php echo $this->Html->link( '
					<dl class="dc list users '.$stat_class .' mob-clr mob-sep-none ">					         	
						<dt class="pr hor-mspace text-11 grayc">'.__l('All').'</dt>
						<dd title="'.$all_count.'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt((!empty($all_count) ? $all_count : '0'),false).'</dd>                  	
					</dl>'
					,  array('controller' => 'item_users', 'action' => 'index','type'=>'myworks', 'status' => 'all'), array('escape' => false, 'title' => __l('All')));
				?>
		<?php
			if (!empty($data_status_count)):

				foreach($data_status_count as $value => $data):
					$class_name = $itemStatusClass[$value] ? $itemStatusClass[$value] :"";
					$stat_class = (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == $value) ? 'active' : null;
			?>
          
	 		<?php echo	$this->Html->link( '
					<dl class="dc list users '.$stat_class .' mob-clr mob-sep-none ">					         	
						<dt class="pr hor-mspace text-11 grayc">'.$data['label'].'</dt>
						<dd title="'.$data['count'].'" class="textb text-20 no-mar graydarkc pr hor-mspace ">'.$this->Html->cInt($data['count'], false).'</dd></dl>'
						,  array('controller'=>'item_users', 'action'=>'index', 'type' => 'myworks', 'status' => $value,), array('escape' => false, 'title' => $data['label']));
				
			?>
			<?php
				endforeach;
			endif;
		?>
</section>

<?php  if(isset($this->request->params['named']['status']) && $this->request->params['named']['status']=='negotiation_requested'): ?>
 <div class="alert alert-info"><?php echo sprintf(__l('You can give whatever discount, but admin commission will be calculated on your %s cost!'), Configure::read('item.alt_name_for_item_singular_small')); ?></div>
<?php endif; ?>
<?php
if (!empty($itemUsers)):?>
	<div class="space"><?php echo $this->element('paging_counter');?></div>
<?php endif; ?>
<div class="alert alert-info"><?php echo __l('Order confirmation request will be expired automatically in ').(Configure::read('item.auto_expire')*24).__l(' hrs, please hurry to confirm.'); ?></div>
<?php $row_span_class='';
if(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'confirmed'){
	$row_span_class=' rowspan="2"';
}
?>
<table class="revenues-list po-list table table-striped">
<thead>
	<tr class="well no-mar no-pad">
		<th class="graydarkc sep-right" <?php echo $row_span_class;?>><?php echo __l('Action'); ?></th>
		<th class="graydarkc sep-right" <?php echo $row_span_class;?>><?php echo $this->Paginator->sort('from',__l('From')); ?></th>
		<th class="graydarkc sep-right" <?php echo $row_span_class;?>><?php echo $this->Paginator->sort('to', __l('To')); ?></th>
		<th class="graydarkc sep-right" <?php echo $row_span_class;?>><?php echo $this->Paginator->sort('Item.title', Configure::read('item.alt_name_for_item_singular_caps')); ?></th>
		<th class="graydarkc sep-right" <?php echo $row_span_class;?>><?php echo $this->Paginator->sort('User.username', Configure::read('item.alt_name_for_booker_singular_caps')); ?></th>
		<th class="graydarkc sep-right" <?php echo $row_span_class;?>><?php echo $this->Paginator->sort('id',__l('Booking ID #')); ?></th>
		<!-- @todo "Guest details" -->
		<th class="graydarkc sep-right" <?php echo $row_span_class;?>><?php echo $this->Paginator->sort( 'price',__l('Gross') . ' ('. Configure::read('site.currency') . ')'); ?></th>
		<?php if(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'confirmed'): ?>
			<th colspan="2" class="graydarkc sep-right"><?php echo $this->Paginator->sort(__l('Booking code')); ?></th>
		<?php endif; ?>	
		<th class="graydarkc sep-right" <?php echo $row_span_class;?>><?php echo __l('No of Days');?></th>
		<th class="graydarkc sep-right" <?php echo $row_span_class;?>><?php echo $this->Paginator->sort('created',__l('Booked Date'));?></th>
		<th class="graydarkc " <?php echo $row_span_class;?>><?php echo $this->Paginator->sort('host_private_note',__l('Private Note')); ?></th>
	</tr>
	<?php if(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'confirmed'): ?>
	<tr>		
		<th class="graydarkc sep-right sep-top" ><?php echo $this->Paginator->sort(__l('Top Code'),'top_code'); ?></th>
		<th class="graydarkc sep-right sep-top" ><?php echo $this->Paginator->sort(__l('Bottom Code'),'bottom_code'); ?></th>
	</tr>
	<?php endif; ?>
	</thead>
	<tbody>
<?php
if (!empty($itemUsers)):

$i = 0;
foreach ($itemUsers as $itemUser):
?>
	<tr>
		<td class="actions">
                    <div class="dropdown dc"> 
						<a data-toggle="dropdown" class="dropdown-toggle text-14 textb graylighterc no-shad" title="<?php echo __l('Edit');?>" href="#"><i class="icon-cog no-pad text-16"></i></a>
					  <ul class="dropdown-menu dl arrow">
									<?php
								if($itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforAcceptance) { ?>
									<li><?php echo $this->Html->link('<i class="icon-check"></i>' . __l('Confirm'), array('controller' => 'item_users', 'action' => 'update_order', $itemUser['ItemUser']['id'],  'accept', 'admin' => false, '?r=' . $this->request->url), array('class' => 'confirm js-delete', 'escape' => false, 'title' => __l('Confirm'))); ?>
									</li>
									<li><?php 
									echo $this->Html->link('<i class="icon-remove-sign"></i>' . __l('Reject'), array('controller' => 'item_users', 'action' => 'update_order', $itemUser['ItemUser']['id'],  'reject', 'admin' => false, '?r=' . $this->request->url), array('class' => 'cancel js-delete','escape'=>false, 'title' => __l('Reject'))); ?> 
									</li><?php
								}
								if ($this->Auth->user('id') == $itemUser['ItemUser']['owner_user_id'] && ($itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforReview || $itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::Completed) && empty($itemUser['ItemUser']['is_host_reviewed'])) {?>
									<li><?php 
									echo $this->Html->link('<i class="icon-sun"></i>' . __l('Review'), array('controller'=>'item_user_feedbacks','action'=>'add','item_order_id' => $itemUser['ItemUser']['id']), array('class' =>'review','escape'=>false, 'title' => __l('Review'))); ?> 
									</li><?php
								}
								?>
									<li><?php 
							echo $this->Html->link('<i class="icon-zoom-in"></i>' . __l('View activities'), array('controller' => 'messages', 'action' => 'activities',  'order_id' => $itemUser['ItemUser']['id']), array('class' => 'view-activities','escape'=>false));
							$note_url = Router::url(array(
							'controller' => 'messages',
							'action' => 'activities',
							'order_id' => $itemUser['ItemUser']['id'],
						) , true); ?> 
						</li>
						<li><?php 
						echo $this->Html->link('<i class="icon-pencil"></i>' . __l('Private Note'), $note_url.'#Private_Note', array('class' =>'add-note','escape'=>false, 'title' => __l('Private Note')));?>
						</li>
						<li><?php 
						if ($itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::BookingRequest) {
							echo $this->Html->link('<i class="icon-mail-reply-all"></i>' . __l('Respond'), array('controller' => 'messages', 'action' => 'activities', 'order_id' => $itemUser['ItemUser']['id'], 'admin' => false), array('class' => 'respond', 'title' => __l('Respond'),'escape'=>false)); ?> 
									</li><?php
						}
						?>
								</ul> 
					</div>
	
		</td>
		<td><?php echo $this->Html->cDate($itemUser['ItemUser']['from']);?></td>
		<td><?php echo $this->Html->cDate(getToDate($itemUser['ItemUser']['to']));?></td>
		<td class="dl status-data">
			<span class="span4 htruncate js-bootstrap-tooltip" data-original-title="<?php echo $this->Html->cText($itemUser['Item']['title'],false); ?>">
				<?php echo $this->Html->link($this->Html->cText($itemUser['Item']['title'] . "&nbsp",false), array('controller'=> 'items', 'action' => 'view', $itemUser['Item']['slug']), array('title' => $this->Html->cText($itemUser['Item']['title'], false), 'escape' => false));?>
			</span>
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
			<span class="<?php echo $class; ?>">
		    	   <?php echo $this->Html->cText($itemUser['ItemUserStatus']['name'],false); ?>
			</span>
			<?php if (!empty($itemUser['ItemUser']['is_booking_request'])): ?>
				<span class="hor-smspace label label-negotiate"><?php echo __l('Negotiation'); ?></span>
			<?php endif; ?>
			<?php endif; ?>
		</td>
		<td><?php echo $this->Html->link($this->Html->cText($itemUser['User']['username'], false), array('controller' => 'users', 'action' => 'view', $itemUser['User']['username'] ,'admin' => false), array('title'=>$this->Html->cText($itemUser['User']['username'],false),'escape' => false)); ?>
		</td>
		<td><?php echo $this->Html->cInt($itemUser['ItemUser']['id'], false);?></td>
		<td><?php 
			$booker_gross = $itemUser['ItemUser']['original_price'] - $itemUser['ItemUser']['host_service_amount'];
			if(!empty($itemUser['ItemUser']['additional_fee_amount'])){
				$booker_gross += $itemUser['ItemUser']['additional_fee_amount'];
			}
			echo $this->Html->cCurrency($booker_gross);
		?></td>
		<?php if(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'confirmed'): ?>
			<td><?php echo $this->Html->cText($itemUser['ItemUser']['top_code']);?></td>
			<td><?php echo $this->Html->cText($itemUser['ItemUser']['bottom_code']);?></td>
		<?php endif; ?>
		<td><?php echo $this->Html->cInt(getFromToDiff($itemUser['ItemUser']['from'],getToDate($itemUser['ItemUser']['to'])));?></td>
		<td><?php echo $this->Html->cDateTimeHighlight($itemUser['ItemUser']['created']);?></td>
		<td>
			<span class="span4 htruncate js-bootstrap-tooltip" data-original-title="<?php echo $this->Html->cText($itemUser['ItemUser']['host_private_note'],false); ?>">
				<?php echo $this->Html->cText($itemUser['ItemUser']['host_private_note']);?>
			</span>
		</td>
	</tr>
<?php
    endforeach;
    
else:
?>
<tr>
	<td colspan="14">
		<div class="space dc grayc">
			<p class="ver-mspace top-space text-16 "><?php echo __l('No Bookings available');?></p>
		</div>
	</td>
</tr>
<?php
endif;
?> 
</tbody>
</table>

<?php
if (!empty($itemUsers)) { ?>
<div class= "clearfix">
	<div class="clearfix space pull-right mob-clr dc">
<?php
		echo $this->element('paging_links'); ?>
	</div>
</div>
<?php	}
?>
<div class="my-item clearfix">
<div class="alert alert-info js-responses-update">
<?php echo sprintf(__l('In the calendar, you can override your %s prices and also confirm bookings.'), Configure::read('item.alt_name_for_item_plural_small'));?>
<?php echo '<br/>' . sprintf(__l('If you want to view %s wise calendar, visit'), Configure::read('item.alt_name_for_item_singular_small')) . ' ' . $this->Html->link(__l('My') . ' ' . Configure::read('item.alt_name_for_item_plural_caps'), array('controller' => 'items', 'action' => 'index', 'type' => 'myitems'), array('title' => __l('My') . ' ' . Configure::read('item.alt_name_for_item_plural_caps')));?>
</div>
<div class="my-item-inner-block clearfix">
    <div class="span8 clearfix">
         <?php   echo $this->element('items-index_lst_my_items', array('item_id' => isset($this->request->params['named']['item_id']) ? $this->request->params['named']['item_id'] : '')); ?>
        <div class="clearfix">
            <div class="items-middle-block no-pad clearfix">
                <h3 class="well space textb text-16"><?php echo __l('Legends');?></h3>
				<ul class="clearfix unstyled">
					<li class="top-mspace"><span class="label label-confirmed"><?php echo __l('Available');?></span></li>
					<li class="top-mspace"><span class="label label-danger"><?php echo __l('Not Available');?></span></li>
					<li class="top-mspace"><span class="label label-pendingapproval"><?php echo __l('Waiting for Acceptance');?></span></li>
					<li class="top-mspace"><span class="label label-completed"><?php echo __l('Booking Confirmed');?></span></li>
					<li class="top-mspace"><span class="label label-negotiate"><?php echo __l('Booking Requested');?></span></li>
				</ul>
            </div>
        </div>
    </div>
    <div class="span15 clearfix">
    <?php   echo $this->element('items-calendar', array('item_id' => isset($this->request->params['named']['item_id']) ? $this->request->params['named']['item_id'] : '', 'config' => 'sec')); ?>
    </div>
    </div>
</div>
</div>