<div class="messages index message-compose-block js-responses">
<?php
if(!empty($this->request->params['isAjax'])):
		echo $this->element('flash_message', array('config' => 'sec'));
endif;
?>
<?php //echo '<pre>'; print_r($this->request->data); exit;?>
<?php echo $this->Form->create('Message', array('url' => array('controller' => 'messages', 'action' => 'simple_compose', 'admin' => false), 'class' => 'compose form-horizontal js-ajax-form', 'enctype' => 'multipart/form-data')); ?>
	<div class="compose-box clearfix" id="js-amount-negotiate-block">
		<?php
			echo $this->Form->input('to_user_id', array('type' => 'hidden'));
			echo $this->Form->input('to_username', array('type' => 'hidden'));
			echo $this->Form->input('type', array('type' => 'hidden'));
			if(!empty($this->request->data['Message']['item_id'])):
				echo $this->Form->input('item_id', array('type' => 'hidden'));
			endif;
			if(!empty($this->request->data['Message']['item_user_id'])):
				echo $this->Form->input('item_user_id', array('type' => 'hidden'));
			endif;
			if(isset($this->request->data['Message']['conversaction_type']) && !empty($this->request->data['Message']['conversaction_type'])):
				echo $this->Form->input('conversaction_type', array('type' => 'hidden'));
			endif;
		?>
		<?php if(isset($this->request->data['Message']['conversaction_type']) && $this->request->data['Message']['conversaction_type'] == 'private'): ?>
		<div class="alert alert-info">
			<?php echo __l('Your private note for your own reference.'); ?>
		</div>
		<?php endif; ?>
		<?php
        if ($itemOreder['Item']['user_id'] == $this->Auth->user('id') && $itemOreder['ItemUser']['item_user_status_id'] == ConstItemUserStatus::PaymentPending && !empty($itemOreder['ItemUser']['is_booking_request']) && (empty($this->request->data['Message']['conversaction_type']) || !empty($this->request->data['Message']['conversaction_type']) && $this->request->data['Message']['conversaction_type'] != 'private')): ?>
		<div class="page-information clearfix">
			<?php echo __l('Host commission will be calculated from original price; not from negotiated price.'); ?>
		</div>
		<?php endif; ?>
		<div class="input required message-lable-info dl span15">
			
			<?php
			$msg_label = __l('Message');
			if(isset($this->request->data['Message']['conversaction_type']) && $this->request->data['Message']['conversaction_type'] == 'private'){
					$msg_label = __l('Note');
				}			
			?>
			
			<?php echo $this->Form->input('message', array('type' => 'textarea', 'label' => $msg_label)); ?>
		</div>
		<?php
        if ($itemOreder['Item']['user_id'] == $this->Auth->user('id') && $itemOreder['ItemUser']['item_user_status_id'] == ConstItemUserStatus::PaymentPending && !empty($itemOreder['ItemUser']['is_booking_request']) && (empty($this->request->data['Message']['conversaction_type']) || !empty($this->request->data['Message']['conversaction_type']) && $this->request->data['Message']['conversaction_type'] != 'private')): ?>
			<?php echo $this->Form->input('amount', array('type' => 'text', 'label' => __l('Discount (in percentage)'), 'class' => 'js-negotiate-discount')); ?>
		<div class="input clearfix">
		   <label>
			<?php
				echo __l('Your Gross Amount');
			?>
		</label>
			<span class="textb">
		      <?php echo Configure::read('site.currency'); ?>
			<span class="js-gross-host-amount message-title {price:'<?php echo ($this->Html->siteWithCurrencyFormat($itemOreder['ItemUser']['price']+$itemOreder['ItemUser']['negotiate_amount'],false)); ?>', gross:'<?php echo $this->Html->siteWithCurrencyFormat($itemOreder['ItemUser']['price'],false); ?>', service_amount:'<?php echo $this->Html->siteWithCurrencyFormat($itemOreder['ItemUser']['host_service_amount'],false); ?>'}">
			<?php echo $this->Html->siteWithCurrencyFormat($itemOreder['ItemUser']['price']-$itemOreder['ItemUser']['host_service_amount'],false); ?></span>
        </span>
		</div>
		<?php
	    endif;
		?>
		<?php if($itemOreder['Item']['user_id'] == $this->Auth->user('id')) { ?>
			<div class="span7 well">
				<span class="span7 no-mar">
					<?php echo __l('To create event for this request ').$this->Html->link(__l('click here.'), array('controller' => 'items', 'action'=>'edit', $itemOreder['ItemUser']['item_id'], $itemOreder['ItemUser']['id']), array('escape'=>false,'title' => __l('click here.')));?>
				</span>
				<span class="clearfix span7 dc no-mar"> <?php echo __l('or'); ?> </span>
				<span class="span7 no-mar">
					<?php echo sprintf(__l('To create private %s for this request '), Configure::read('item.alt_name_for_item_singular_small')).$this->Html->link(__l('click here.'), array('controller' => 'items', 'action' => 'add', 'item_id' => $itemOreder['ItemUser']['item_id'], 'order_id' => $itemOreder['ItemUser']['id']), array('title' => __l('click here.'), 'escape' => false));?>
				</span>
			</div>
		<?php } ?>
		</div>
		<div class="clearfix">
			<div class="form-actions" >
				<?php 
				$btn = __l('Send');
				if(isset($this->request->data['Message']['conversaction_type']) && $this->request->data['Message']['conversaction_type'] == 'private'){
					$btn = __l('Update');
				}
				echo $this->Form->submit($btn, array('class' => 'btn btn-large btn-primary textb text-16 ','div'=>false)); ?>
			</div>
		</div>
	
<?php echo $this->Form->end(); ?>
</div>