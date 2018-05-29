<?php /* SVN: $Id: $ */ ?>
<div class="ver-space bot-mspace sep-bot clearfix">
<h2 class="text-32 span"><?php echo __l("Manage Email Settings");?></h2>
<?php echo $this->element('sidebar', array('config' => 'sec'));	?>
</div>
<div class="userNotifications form">
<div class=" usernotification-block no-mar">
<?php echo $this->Form->create('UserNotification', array('action' => 'edit', 'class' => 'normal no-pad'));?>
	<fieldset class="no-pad">
	<?php
		if($this->Auth->user('role_id') == ConstUserTypes::Admin):
			echo $this->Form->input('id');
		endif;
	?>
 <table class="table table-striped table-hover">
	<thead class="sep-whitec sep-bot sep-medium">
	<tr class=" well bot no-pad js-even">
			<th class="dl graydarkc sep-right"><?php echo __l('Host');?></th>
			<th class="dl graydarkc sep-right"><?php echo __l('Booker');?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="dl"><?php echo $this->Form->input('is_new_item_order_host_notification', array('label' => sprintf(__l('Send notification when you receive a booking for your %s'), Configure::read('item.alt_name_for_item_singular_small'))));?></td>
			<td class="dl"><?php echo $this->Form->input('is_new_item_order_booker_notification', array('label' => __l('Send notification when you make an booking')));?></td>
		</tr>
		<tr>
			<td class="dl"><?php echo $this->Form->input('is_accept_item_order_host_notification', array('label' => __l('Send notification when you accept an booking')));?></td>
			<td class="dl"><?php echo $this->Form->input('is_accept_item_order_booker_notification', array('label' => sprintf(__l('Send notification when your %s booking was accepted'), Configure::read('item.alt_name_for_item_singular_small'))));?></td>
		</tr>
		<tr>
			<td class="dl"><?php echo $this->Form->input('is_admin_cancel_item_order_host_notification', array('label' => sprintf(__l('Send notification when your %s booking by a booker was canceled by admin'), Configure::read('item.alt_name_for_item_singular_small'))));?></td>
			<td class="dl"><?php echo $this->Form->input('is_admin_cancel_booker_notification', array('label' => sprintf(__l('Send notification when the %s booking made by you was canceled by admin'), Configure::read('item.alt_name_for_item_singular_small'))));?></td>
		</tr>
		<tr>
			<td class="dl"><?php echo $this->Form->input('is_complete_item_order_host_notification', array('label' => sprintf(__l('Send notification when your %s booked was reviewed by the booker'), Configure::read('item.alt_name_for_item_singular_small'))));?></td>
			<td class="dl"><?php echo $this->Form->input('is_complete_item_order_booker_notification', array('label' => __l('Send notification when you make an review for the book made'))); ?></td>
		</tr>
		<tr>
			<td class="dl"><?php echo $this->Form->input('is_expire_item_order_host_notification', array('label' => sprintf(__l('Send notification when your %s booked was expired on non-acceptance by you'), Configure::read('item.alt_name_for_item_singular_small'))));?></td>
			<td class="dl"><?php echo $this->Form->input('is_expire_item_order_booker_notification', array('label' => __l('Send notification when the booking made by you was expired on non-acceptance by the host')));?></td>
		</tr>
		<tr>
			<td class="dl"><?php echo $this->Form->input('is_cancel_item_order_host_notification', array('label' => sprintf(__l('Send notification when your %s booked was canceled by the booker'), Configure::read('item.alt_name_for_item_singular_small'))));?></td>
			<td class="dl"><?php echo $this->Form->input('is_cancel_item_order_booker_notification', array('label' => __l('Send notification when you cancel the booked you have made')));?></td>
		</tr>
		<tr>
			<td class="dl"><?php echo $this->Form->input('is_reject_item_order_host_notification', array('label' => __l('Send notification when you reject an booking')));?></td>
			<td class="dl"><?php echo $this->Form->input('is_reject_item_order_booker_notification', array('label' => __l('Send notification when your booking was rejected by the host')));?></td>
		</tr>
		<tr>
			<td class="dl"><?php echo $this->Form->input('is_review_item_order_booker_notification', array('label' => __l('Send notification when your booking was completed and waiting for your review')));?></td>			
			<td colspan ="2" class="dl"><?php echo $this->Form->input('is_contact_notification', array('label' => __l('Send notification when you have contacted by other users')));?></td>
		</tr>
		<tr>
		</tr></tbody>
	</table>
</fieldset>
<div class="form-actions">
<?php echo $this->Form->submit(__l('Update'), array('class' => 'btn btn-large btn-primary textb text-16', 'div' => array("class" =>'submit offset9')));?>
</div>
<?php echo $this->Form->end();?>
</div>
</div>