<ul class="breadcrumb top-mspace top-space" itemprop="breadcrumb">
          <li><?php echo $this->Html->link($this->Html->cText($orders['Item']['title']), array('controller' => 'items', 'action' => 'view', $orders['Item']['slug'], 'admin' => false), array('target' => '_blank', 'title' => $this->Html->cText($orders['Item']['title'], false),'escape' => false));?><span class="divider graydarkc">/</span></li>
          <li><a class="active blackc" href="javascript:void(0);" title="<?php echo __l('Activities'); ?>"> <?php echo __l('Activities'); ?></a></li>
        </ul>
<ol class="unstyled no-mar">
	<li class="clearfix">
<?php echo $this->element('booking_guideline', array('config' => 'sec')); ?>
<?php echo !empty($orders) ? $this->element('items-simple-view', array('slug' => $orders['Item']['slug'], 'order_id' => $orders['ItemUser']['id'], 'config' => 'sec')) : ''; ?>
            <div class="clearfix pull-left top-mspace mob-clr">
              <dl class="sep-right list ">
                <dt class="pr hor-mspace text-11"><?php echo __l('Made On');?></dt>
                <dd class="textb text-16  graydarkc pr hor-mspace" title="<?php echo $this->Html->cDateTimeHighlight($orders['ItemUser']['created'], false);?>"><?php echo $this->Html->cDateTimeHighlight($orders['ItemUser']['created']);?></dd>
              </dl>
              <dl class="sep-right list">
                <dt class="pr hor-mspace text-11"><?php echo __l('Completed?');?></dt>
                <dd class="textb text-16r graydarkc pr hor-mspace" title="<?php echo (!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Completed) ? __l('Yes'): __l('No');?>"><?php echo (!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Completed) ? __l('Yes'): __l('No');?></dd>
              </dl>
				<?php if($orders['ItemUser']['owner_user_id'] == $this->Auth->user('id')):?>
              <dl class="sep-right list">
                <dt class="pr hor-mspace text-11">
					<?php 
						if(!empty($orders['ItemUser']['negotiation_discount'])){
							echo __l('Discount'). ' ('.$orders['ItemUser']['negotiation_discount'].'%)';
						}
					
					?>
				</dt>
                <dd class="textb text-16  graydarkc pr hor-mspace" title="<?php if(!empty($orders['ItemUser']['negotiate_amount'])){ echo  $this->Html->siteCurrencyFormat($orders['ItemUser']['negotiate_amount'], false);}?>"><?php if(!empty($orders['ItemUser']['negotiate_amount'])) { echo $this->Html->siteCurrencyFormat($orders['ItemUser']['negotiate_amount']);}?></dd>
              </dl>
              <dl class="sep-right list">
                <dt class="pr hor-mspace text-11"><?php echo __l('Gross Amount');?></dt>
                <dd class="textb text-16 graydarkc pr hor-mspace" title="<?php echo $this->Html->siteCurrencyFormat($orders['ItemUser']['price'] - $orders['ItemUser']['host_service_amount'], false);?>"><?php echo $this->Html->siteCurrencyFormat($orders['ItemUser']['price'] - $orders['ItemUser']['host_service_amount']);?></dd>
              </dl>
			  <?php else: ?>
              <dl class="sep-right list">
                <dt class="pr hor-mspace text-11"><?php echo __l('Gross Amount');?></dt>
                <dd class="textb text-16 graydarkc pr hor-mspace" title="<?php echo $this->Html->siteCurrencyFormat(($orders['ItemUser']['price'] + $orders['ItemUser']['booker_service_amount'] + $orders['ItemUser']['additional_fee_amount']) - $orders['ItemUser']['coupon_discount_amont'], false);?>"><?php echo $this->Html->siteCurrencyFormat(($orders['ItemUser']['price'] + $orders['ItemUser']['booker_service_amount'] + $orders['ItemUser']['additional_fee_amount']) - $orders['ItemUser']['coupon_discount_amont'], false);?></dd>
              </dl>
			  <?php endif; ?>
              <dl class="sep-right list">
                <dt class="pr mob-clr hor-mspace text-11"><?php echo __l('Current Status');?></dt>
				
<?php 			$status = "";
				if(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::BookingRequest):
					$status = __l('Booking Request');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Completed):
					$status = __l('Completed');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance):
					$status = __l('Waiting for Acceptance');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Confirmed):
					$status = __l('Confirmed');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Rejected):
					$status = __l('Rejected');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Canceled):
					$status = __l('Canceled');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Expired):
					$status = __l('Expired');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::CanceledByAdmin):
					$status = __l('Canceled By Admin');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::PaymentPending):
					$status = __l('Payment Pending');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::HostReviewed):
					$status = __l('Work Reviewed');
				elseif(!empty($orders['ItemUser']['item_user_status_id']) &&  $orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::SenderNotification):
					$status = __l('Sender Notification');
				endif;	?>				
                <dd class="textb text-16  graydarkc pr hor-mspace" title="<?php echo $status; ?>">
					<?php echo $status; ?>				
				</dd>
              </dl>
			<?php if($orders['ItemUser']['owner_user_id'] == $this->Auth->user('id')):?>
              <dl class="list sep-right">
                <dt class="pr hor-mspace text-11"><?php echo Configure::read('item.alt_name_for_booker_singular_caps') . ' ' . __l('name');?></dt>
                <dd class="textb text-16 mob-inline graydarkc pr hor-mspace"><span class="htruncate width75 pull-left"><?php  echo $this->Html->link($orders['User']['username'], array('controller' => 'users', 'action' => 'view', $orders['User']['username'], 'admin' => false), array('title' => $orders['User']['username'],'class'=>'graydarkc js-bootstrap-tooltip'));?></span> <span class="pull-left show"> (<?php  echo $this->Html->link(__l('Contact') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps'), array('controller' => 'messages', 'action' => 'compose','type'=>'contact', 'to'=>$orders['User']['username'], 'admin' => false), array('title' => __l('Contact') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps'),'class'=>'graydarkc'));?>)</span></dd>
              </dl>			
			<?php elseif($orders['ItemUser']['user_id'] == $this->Auth->user('id')):?>
              <dl class="list sep-right">
                <dt class="pr hor-mspace text-11"><?php echo __l('Host name');?></dt>
                <dd class="textb text-16 mob-inline graydarkc pr hor-mspace"><span class="htruncate width75 pull-left"><?php  echo $this->Html->link($orders['Item']['User']['username'], array('controller' => 'users', 'action' => 'view', $orders['Item']['User']['username'], 'admin' => false), array('title' => $orders['Item']['User']['username'],'class'=>'graydarkc js-bootstrap-tooltip'));?></span> <span class="pull-left show "> (<?php  echo $this->Html->link(__l('Contact Host'), array('controller' => 'messages', 'action' => 'compose','type'=>'contact', 'to'=>$orders['Item']['User']['username'], 'admin' => false), array('title' => __l('Contact Host'),'class'=>'graydarkc'));?>)</span></dd>
              </dl>							
			<?php else:?>
              <dl class="list sep-right">
                <dt class="pr hor-mspace text-11"><?php echo  Configure::read('item.alt_name_for_booker_singular_caps') . ' ' . __l('name');?></dt>
                <dd class="textb text-16 mob-inline graydarkc pr hor-mspace"><span class="htruncate width75 pull-left"><?php echo !empty($orders['User']['username']) ? $this->Html->link($orders['User']['username'], array('controller' => 'users', 'action' => 'view', $orders['User']['username'], 'admin' => false),array('class'=>'graydarkc js-bootstrap-tooltip', 'title' => $orders['User']['username'])) : 'Guest'; ?><?php echo !empty($orders['User']['username']) ? ' (' . $this->Html->link(__l('Contact') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps'), array('controller' => 'messages', 'action' => 'compose', 'type' => 'contact', 'to'=>$orders['User']['username'], 'admin' => false), array('title' => __l('Contact') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps'),'class'=>'graydarkc')) . ')' : ''; ?></span> </dd>
              </dl>
              <dl class="list sep-right">
                <dt class="pr hor-mspace text-11"><?php echo __l('Host name');?></dt>
                <dd class="textb text-16 mob-inline graydarkc pr hor-mspace"><span class="htruncate width75 pull-left"><?php  echo $this->Html->link($orders['Item']['User']['username'], array('controller' => 'users', 'action' => 'view', $orders['Item']['User']['username'], 'admin' => false),array('class'=>'graydarkc js-bootstrap-tooltip', 'title' => $orders['Item']['User']['username']));?> (<?php  echo $this->Html->link(__l('Contact Host'), array('controller' => 'messages', 'action' => 'compose','type'=>'contact', 'to'=>$orders['Item']['User']['username'], 'admin' => false), array('title' => __l('Contact Host'),'class'=>'graydarkc'));?>)</span> </dd>
              </dl>			  				
			<?php endif;?>	
			<?php
				if(isPluginEnabled('Seats') && !empty($orders['CustomPricePerTypesSeat'])){
					$seat_no = '';
					$partition = '';
					foreach($orders['CustomPricePerTypesSeat'] as $key => $seat) {
						if($key == 0){
							$partition = $seat['Partition']['name'];
						}						
						if($key > 0){
							$seat_no .= ', '.$seat['name'];
						} else {
							$seat_no = $seat['name'];
						}
					}
			?>
				<dl class="list sep-right">
                <dt class="pr hor-mspace text-11"><?php echo __l('Partition');?></dt>
                <dd class="textb text-16 mob-inline graydarkc pr hor-mspace"><span class="htruncate pull-left">
				<?php echo $partition;?>
				</span> </dd>
              </dl>
				<dl class="list sep-right">
                <dt class="pr hor-mspace text-11"><?php echo __l('Seat No');?></dt>
                <dd class="textb text-16 mob-inline graydarkc pr hor-mspace"><span class="htruncate pull-left">
				<?php echo $seat_no;?>
				</span> </dd>
              </dl>
			<?php }?>
            </div>
</li>
</ol>
<?php $show_fromto = array();?>
<?php echo $this->element('message-index-conversation', array('order_id' => $orders['ItemUser']['id'], 'config' => 'sec', 'span_size' => (!empty($this->request->params['prefix'])) ?  "span23" : "span16")); ?>
<?php if(!empty($orders['BuyerSubmission'])) { ?>
<section class="row no-mar bot-space">
  <div class="ver-space">
	<h3 class="ver-space textb"><?php echo __l('Buyer Details'); ?></h3>
  </div>
  <?php foreach($orders['BuyerSubmission'] As $buyer_submission) { ?>
	<?php if($buyer_submission['type'] == 'text' || $buyer_submission['type'] == 'textarea') { ?>
	<dl class="clearfix bot-mspace">
	  <dt class="textb span4 pull-left"><?php echo $this->Html->cHtml($buyer_submission['BuyerFormField']['display_text'], false); ?></dt>
	  <dd class="textn pull-left"><?php echo $this->Html->cText($buyer_submission['response'], false); ?></dd>
	</dl>
	<?php } else if($buyer_submission['type'] == 'select' || $buyer_submission['type'] == 'radio' || $buyer_submission['type'] == 'checkbox' || $buyer_submission['type'] == 'multiselect') { 
		$response = '';
		if($buyer_submission['type'] == 'select' || $buyer_submission['type'] == 'radio') {
			$options = $this->Html->explode_escaped(',', $buyer_submission['BuyerFormField']['options']);
			$response = $options[$buyer_submission['response']];
		} else if($buyer_submission['type'] == 'checkbox' || $buyer_submission['type'] == 'multiselect') {
			$options = $this->Html->explode_escaped(',', $buyer_submission['BuyerFormField']['options']);
			$response_arrays = explode(',', $buyer_submission['response']);
			foreach($response_arrays As $response_array) {
				if(!empty($response)) {
					$response .= ', ';
				}
				$response .= $options[$response_array];
			}
		}
	?>
		<dl class="clearfix bot-mspace">
		  <dt class="textb span4 pull-left"><?php echo $this->Html->cHtml($buyer_submission['BuyerFormField']['display_text'], false); ?></dt>
		  <dd class="textn pull-left"><?php echo $this->Html->cText($response, false); ?></dd>
		</dl>
	<?php } ?>
  <?php } ?>
</section>
<?php } ?>
<section class="row no-mar bot-space">
  <div class="ver-space">
	<h3 class="ver-space textb"><?php echo __l('Response and actions'); ?></h3>
  </div>		
  <?php if ($orders['ItemUserStatus']['id'] != ConstItemUserStatus::CanceledByAdmin && $orders['ItemUserStatus']['id'] != ConstItemUserStatus::Canceled && $orders['ItemUserStatus']['id'] != ConstItemUserStatus::Rejected && $orders['ItemUserStatus']['id'] != ConstItemUserStatus::Expired): ?>
    <?php if((empty($this->request->params['named']['type']) && $orders['ItemUserStatus']['id'] != ConstItemUserStatus::Completed) || ($orders['ItemUserStatus']['id'] == ConstItemUserStatus::Completed )):?>
      <a name="review_your_work"></a>
      <a name="complete_your_work"></a>
      <a name="deliver_your_work"></a>
      <div class="js-response-actions status-link ui-tabs-block bot-mspace clearfix">
	    <?php $is_show_manage_bar = 1;
		if (empty($orders['ItemUser']['is_under_dispute'])): // check item order have any dispute post or not
	      if($orders['ItemUser']['owner_user_id'] == $this->Auth->user('id')): // Seller
	        if ($orders['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforAcceptance):
	          echo $this->Html->link('<i class="icon-ok-sign"></i>'.__l('Confirm'), array('controller' => 'item_users', 'action' => 'update_order', $orders['ItemUser']['id'], 'accept', 'admin' => false, '?r=' . $this->request->url), array('class'=>'confirm js-delete right-mspace js-bootstrap-tooltip','title' => __l('Confirm'), 'escape' => false));
	          echo $this->Html->link('<i class="icon-remove-sign"></i>'.__l('Reject'), array('controller' => 'item_users', 'action' => 'update_order', $orders['ItemUser']['id'], 'reject', 'admin' => false, '?r=' . $this->request->url), array('class'=>'cancel js-delete right-mspace js-bootstrap-tooltip','title' => __l('Reject'), 'escape' => false));
	        endif;
			if ($this->Auth->user('id') == $orders['ItemUser']['owner_user_id'] && ($orders['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforReview ||  $orders['ItemUserStatus']['id'] == ConstItemUserStatus::Completed) && empty($orders['ItemUser']['is_host_reviewed'])):
			  echo $this->Html->link('<i class="icon-refresh"></i>'.__l('Review'), array('controller'=>'item_user_feedbacks','action'=>'add','item_order_id' => $orders['ItemUser']['id']), array('class' =>'review dl right-mspace js-bootstrap-tooltip', 'title' => __l('Review'), 'escape' => false));
			endif;
		  else:	// Buyer 
			if ($orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance):
			  echo $this->Html->link('<i class="icon-remove"></i>'.__l('Cancel'), array('controller' => 'items', 'action' => 'order', $orders['ItemUser']['item_id'] , 'order_id'=>$orders['ItemUser']['id'], 'type' => __l('cancel'), 'admin' => false),array('title' => 'Cancel' ,'class' =>'delete mspace js-bootstrap-tooltip', 'escape' => false));
			endif;
			if($orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::PaymentPending && $orders['ItemUser']['user_id']==$this->Auth->User('id')):
			  echo $this->Html->link( '<i class="icon-bookmark"></i>'.__l('Book It'), array('controller' => 'items', 'action' => 'order', $orders['Item']['id'], 'order_id:' . $orders['ItemUser']['id'], 'admin' => false), array('class' => 'complete-booking js-no-pjax js-delete mspace js-bootstrap-tooltip','title' => __l('Book It'), 'escape' => false));
			endif;
			if($this->Auth->user('id')==$orders['ItemUser']['user_id'] && ($orders['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforReview)):
			  echo $this->Html->link('<i class="icon-refresh"></i>'.__l('Review'), array('controller'=>'item_feedbacks','action'=>'add','item_order_id' => $orders['ItemUser']['id']), array('class' =>'review dl mspace js-bootstrap-tooltip', 'title' => __l('Review'), 'escape' => false));
			endif;
		  endif;
		else:
		endif; ?>
      </div>
    <?php endif;
  endif; ?>
  <?php if(empty($this->request->params['named']['type']) ):?>
    <div class="js-dispute-container ui-tabs-block clearfix tab-container" id="ajax-tab-container-item">
      <ul class="nav nav-tabs no-mar tabs">
		<?php if ($orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::BookingRequest && !empty($orders['ItemUser']['is_booking_request'])) { ?>
		  <li class="negotiation tab active"><?php echo $this->Html->link('<i class="icon-home"></i>' .__l('Request'), array('controller' => 'messages', 'action' => 'simple_compose', 'order_id' => $orders['ItemUser']['id']), array('title' => __l('Request'), 'data-target'=> "#negotiation", 'data-toggle'=>'tab' ,'escape' => false,'class'=>"js-no-pjax"));?></li>
		<?php } ?>
		<li class="private-note tab"><?php echo $this->Html->link('<i class="icon-file-text-alt"></i>' . __l('Private Note'), array('controller' => 'messages', 'action' => 'simple_compose', 'order_id' => $orders['ItemUser']['id'], 'conversaction_type'=> 'private'), array('title' => __l('Private Note'), 'data-target'=> "#private-note", 'data-toggle'=>'tab','escape' => false,'class'=>"js-no-pjax"));?></li>
      </ul>
      <div class="sep-right sep-left space bot-mspace sep-bot tab-round ">
	    <?php if ($orders['ItemUser']['item_user_status_id'] == ConstItemUserStatus::BookingRequest && !empty($orders['ItemUser']['is_booking_request'])) { ?>
	      <div id="negotiation" class="tab-pane "></div>
	    <?php } ?>
        <div id="private-note" class="tab-pane "></div>
      </div>
	</div>
  <?php endif;?>
</section>