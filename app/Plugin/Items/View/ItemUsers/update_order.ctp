<h2><?php echo __l('Ticket'); ?></h2>
<?php
		echo $this->element('items-simple-view', array('slug' => $itemUser['Item']['slug'], 'order_id' => $itemUser['ItemUser']['id'], 'config' => 'sec'));
	$show_fromto = array();
	if(($itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::Confirmed) && (date('Y-m-d') >= $itemUser['ItemUser']['from'])):
			if((($itemUser['Item']['from'] == '00:00:00') || (date('H:i:s') >= $itemUser['Item']['from']))):
				$show_fromto['show'] = 1;
				$show_fromto['value'] = __l('From');
				$show_fromto['action'] = 'check_in';
			endif;
		endif;
		if(empty($show_fromto) && ($itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforReview ) && (date('Y-m-d') >= $itemUser['ItemUser']['to']) && empty($itemUser['ItemUser']['is_host_checkout'])):
			if(($itemUser['Item']['to'] == '00:00:00') || (date('H:i:s') <= $itemUser['Item']['to'])):
				$show_fromto['show'] = 1;
				$show_fromto['value'] = __l('Check out');
				$show_fromto['action'] = 'check_out';
			endif;
		endif;
	?>
<?php if(!empty($show_fromto)): ?>
<div id="messages-activities">
<div class="js-response-actions status-link ui-tabs-block  clearfix">
<div class="js-tabs menu-tabs ui-tabs clearfix">
			<ul class="clearfix">			
			<?php if(!empty($show_fromto['show'])):?>
				<li  class="check-in"><?php echo $this->Html->link($show_fromto['value'], array('controller' => 'item_users', 'action' => 'process_fromto', 'order_id' => $itemUser['ItemUser']['id'], 'p_action' => $show_fromto['action'], 'via' => 'ticket'), array('title' => $show_fromto['value']));?></li>
			<?php endif;?>						
		</ul>			 		
</div>
</div>
</div> 
<?php endif; ?>
