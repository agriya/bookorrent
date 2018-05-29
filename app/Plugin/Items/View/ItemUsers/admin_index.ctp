<?php /* SVN: $Id: $ */ ?>
<div class="itemUsers index js-response">
<?php $url= array(
                'controller' => 'item_users',
                'action' => 'index',
                );?>
    <?php
		$all = '';
		foreach($itemUserStatuses as $id => $itemUserStatus):
        	$all += $itemUserStatusesCount[$id];
    	endforeach;
	?>


<?php if(empty($this->request->params['isAjax'])) :?>	
<div class="round-5 item-chart-block clearfix">
    <ul class="item-chart clearfix unstyled">
		<li class="new-booking">
			<div class="payment-block-left">
				<div class="payment-block-right">
					<?php $url['filter_id'] = ConstItemUserStatus::PaymentPending;?>
					<ul class="unstyled">
						<li><span class="payment-pending"><?php echo $this->Html->link(sprintf("%s", __l('Payment Pending') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::PaymentPending]), $url, array('class' => 'all-item-user','title' => __l('Payment Pending')));?></span></li>
					</ul>
				</div>
			</div>
			<span class="new-booking"><span><?php echo __l('New booking'); ?></span></span>
		</li>
		<li class="pending-approval">
			<div class="rejected-block">
				<ul class="unstyled">
					<?php $url['filter_id'] = ConstItemUserStatus::Rejected;?>
					<li><span class="rejected"><?php echo $this->Html->link(sprintf("%s", __l('Rejected') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::Rejected]), $url, array('class' => 'all-item-user','title' => __l('Rejected')));?></span></li>
					<?php $url['filter_id'] = ConstItemUserStatus::Canceled;?>
					<li class="canceled"><span class="canceled"><?php echo $this->Html->link(sprintf("%s", __l('Canceled') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::Canceled]), $url, array('class' => 'all-item-user','title' => __l('Canceled')));?></span></li>
				</ul>
			</div>
			<?php $url['filter_id'] = ConstItemUserStatus::WaitingforAcceptance;?>
			<span class="pending-approval"><?php echo $this->Html->link(sprintf("%s", __l('Pending Approval') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::WaitingforAcceptance]), $url, array('class' => 'all-item-user','title' => __l('Pending Approval')));?></span>
			<div class="expired-block">
				<ul class="unstyled">
					<?php $url['filter_id'] = ConstItemUserStatus::Expired;?>
					<li><span class="expired"><?php echo $this->Html->link(sprintf("%s", __l('Expired') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::Expired]), $url, array('class' => 'all-item-user','title' => __l('Expired')));?></span>
					<span class="chart-info js-bootstrap-tooltip" title="<?php echo sprintf(__l('Order confirmation request will be expired automatically in %s hrs '), Configure::read("item.auto_expire") * 24);?>"><?php echo __l('Info');?></span>
					</li>
					<?php $url['filter_id'] = ConstItemUserStatus::CanceledByAdmin;?>
					<li class="canceled-by-admin"><span class="canceled-by-admin"><?php echo $this->Html->link(sprintf("%s", __l('Canceled By Admin') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::CanceledByAdmin]), $url, array('class' => 'all-item-user','title' => __l('Canceled By Admin')));?></span></li>
				</ul>
			</div>
		</li>
		<li class="confirmed">
			<div class="confirmed-top-block">&nbsp;</div>
			<?php $url['filter_id'] = ConstItemUserStatus::Confirmed;?>
			<span class="confirmed"><?php echo $this->Html->link(sprintf("%s", __l('Confirmed') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::Confirmed]), $url, array('class' => 'all-item-user','title' => __l('Confirmed')));?></span>
			<div class="confirmed-bottom-block">&nbsp;</div>
		</li>
		<li class="cleared">
			<div class="booker-review-block">
				<ul class="unstyled">
					<?php $url['filter_id'] = ConstItemUserStatus::WaitingforReview;?>
					<li><div class="booker-arrow">&nbsp;</div><span class="booker-review"><?php echo $this->Html->link(sprintf("%s", Configure::read('item.alt_name_for_booker_singular_caps') . ' ' . __l('review') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::WaitingforReview]), $url, array('class' => 'all-item-user','title' => Configure::read('item.alt_name_for_booker_singular_caps') . ' ' . __l('review')));?></span></li>
				</ul>
			</div>
			<div class="host-review-block">
				<ul class="unstyled">
					<?php $url['filter_id'] = ConstItemUserStatus::HostReviewed;?>
					<li><span class="host-review"><?php echo $this->Html->link(sprintf("%s", __l('Host review') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::HostReviewed]), $url, array('class' => 'all-item-user','title' => __l('Host review')));?></span></li>
				</ul>
			</div>
		</li>
		<?php $url['filter_id'] = ConstItemUserStatus::Completed;?>
		<li class="completed"><span class="completed"><?php echo $this->Html->link(sprintf("%s", __l('Completed') . ': ' . $itemUserStatusesCount[ConstItemUserStatus::Completed]), $url, array('class' => 'all-item-user','title' => __l('Completed')));?></span></li>
	</ul>
</div>
<?php endif; ?>
<div class="page-count-block clearfix">
	

<div class="">
<?php if(empty($this->request->params['named']['simple_view'])) : ?>
	<?php echo $this->Form->create('ItemUser', array('type' => 'get', 'class' => 'form-search bot-mspace big-input span', 'action' => 'index')); ?>
		<?php echo $this->Form->input('q', array('placeholder' => __l('Keyword'),'label'=>false, 'maxlength' => '255')); ?>
		<?php echo $this->Form->submit(__l('Search'),array('class'=>'btn btn-large hor-mspace btn-primary textb text-16'));?>
	<?php echo $this->Form->end(); ?>
<?php endif; ?>
</div>

</div>
<p class="left-mspace no-mar">
	<?php echo $this->element('paging_counter'); ?>
	</p>
<?php   
	echo $this->Form->create('ItemUser' , array('class' => 'normal','action' => 'update'));
	echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); 
?>
<div class="overflow-block">
<table class="table table-striped table-hove">
<thead>
    <tr class="well no-mar no-pad" >
	<?php if(empty($this->request->params['named']['simple_view'])) : ?>
        <th class="graydarkc sep-right span2"><?php echo __l('Actions');?></th>
		<?php endif; ?>
        <th class="graydarkc sep-right dc"><div class="js-pagination"><?php echo $this->Paginator->sort('created',__l('Created'));?></div></th>
        <th class="graydarkc sep-right dc"><div class="js-pagination"><?php echo $this->Paginator->sort( 'id',__l('Booking ID #'));?></div></th>
		<th class="graydarkc sep-right dl"><div class="js-pagination"><?php echo $this->Paginator->sort( 'Item.title',Configure::read('item.alt_name_for_item_singular_caps'));?></div></th>
        <th class="graydarkc sep-right dl"><div class="js-pagination"><?php echo $this->Paginator->sort( 'User.username',__l('Host'));?></div></th>
		<th class="graydarkc sep-right dl"><div class="js-pagination"><?php echo $this->Paginator->sort( 'User.username',Configure::read('item.alt_name_for_booker_singular_caps'));?></div></th>
        <th class="graydarkc sep-right dr"><div class="js-pagination"><?php echo __l('Paid Amount to host') . ' (' . Configure::read('site.currency') . ')';?></div></th>
        <th class="graydarkc sep-right dr"><div class="js-pagination"><?php echo __l('Amount') . ' (' . Configure::read('site.currency') . ')';?></div></th>
        <th class="graydarkc sep-right dr"><div class="js-pagination"><?php echo __l('Commission Amount') . ' (' . Configure::read('site.currency') . ')';?></div></th>
		<th class="graydarkc sep-right dl"><div class="js-pagination"><?php echo __l('Original Search Address');?></div></th>

    </tr>
</thead>
<tbody>
<?php
if (!empty($itemUsers)):

$i = 0;
foreach ($itemUsers as $itemUser):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
<?php if(empty($this->request->params['named']['simple_view'])) : ?>
		<td class="actions dc">
			<span class="dropdown dc"> 
				<span title="<?php echo __l('Actions');?>" data-toggle="dropdown" class="graydarkc left-space hor-smspace icon-cog text-18 cur dropdown-toggle"> 
					<span class="hide"><?php echo __l('Actions'); ?></span> 
				</span>
				<ul class="dropdown-menu arrow no-mar dl">
					<?php if($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance):?>
						<li><?php echo $this->Html->link('<i class="icon-undo"></i>'.__l('Cancel and refund'), array('action' => 'delete', $itemUser['ItemUser']['id']), array('class' => 'delete js-delete', 'title' => __l('Cancel and refund'),'escape'=>false));?></li>
						<?php endif;?>
						<li>
						<?php echo $this->Html->link('<i class="icon-eye-open"></i>'.__l('View activities'), array('controller' => 'messages', 'action' => 'activities', 'type' => 'admin_order_view', 'order_id' => $itemUser['ItemUser']['id']), array('class' => 'view', 'title' => __l('View activities'),'escape'=>false));?>
						</li>
				</ul>
			 </span>
		</td>
		<?php endif; ?>
		<td class="dc"><?php echo $this->Html->cDateTime($itemUser['ItemUser']['created']);?></td>
		<td class="dc"><?php echo $this->Html->cInt($itemUser['ItemUser']['id'], false);?></td>
		<td class="dl items-title-info">
			<?php
				
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
            <div class="show js-bootstrap-tooltip span5 htruncate" data-original-title="<?php echo $this->Html->cText($itemUser['Item']['title'],false); ?>" ><?php echo $this->Html->link($this->Html->cText($itemUser['Item']['title'],false), array('controller'=> 'items', 'action'=>'view', $itemUser['Item']['slug'], 'admin' => false), array('escape' => false));?>
			</div>
						<span class="<?php echo $class; ?>" title="<?php echo $this->Html->cText($itemUser['ItemUserStatus']['name'], false);?>"><?php echo $this->Html->cText($itemUser['ItemUserStatus']['name'], false);?></span>
		</td>
		<td class="dl">
		<?php if(!empty($itemUser['Item']['User']['username'])):
          echo $this->Html->link($this->Html->cText($itemUser['Item']['User']['username']), array('controller'=> 'users', 'action'=>'view', $itemUser['Item']['User']['username'], 'admin' => false), array('escape' => false));
          else:
          echo 'Guest';
          endif;?>
          </td>
		<td class="dl">
		<?php if(!empty($itemUser['User']['username'])):
          echo $this->Html->link($this->Html->cText($itemUser['User']['username']), array('controller'=> 'users', 'action'=>'view', $itemUser['User']['username'], 'admin' => false), array('escape' => false));
          else:
          echo 'Guest';
          endif;?>
          </td>
		<td class="dr"><?php echo ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Confirmed)?$this->Html->cCurrency($itemUser['ItemUser']['price'] - $itemUser['ItemUser']['host_service_amount']):'-';?></td>
		<td class="dr"><?php echo $this->Html->cCurrency($itemUser['ItemUser']['price']);?></td>
		<td class="dr site-amount"><?php echo $this->Html->cCurrency($itemUser['ItemUser']['host_service_amount']);?></td>
		<td class="dl"><?php echo $this->Html->cText($itemUser['ItemUser']['original_search_address']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="13"><div class="space dc grayc"><p class="ver-mspace top-space text-16 "><?php echo sprintf(__l('No %s bookings available'), Configure::read('item.alt_name_for_item_singular_caps'));?></p></div></td>
	</tr>
<?php
endif;
?>
</tbody>
</table>
</div>
<?php
if (!empty($itemUsers)):
?>
        <div class="js-pagination clearfix space pull-right mob-clr dc">
            <?php echo $this->element('paging_links'); ?>
        </div>
        <div class="hide">
            <?php echo $this->Form->submit(__l('Submit'));  ?>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>