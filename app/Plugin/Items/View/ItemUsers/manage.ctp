<?php 
$is_show = 0;
if($order['ItemUser']['user_id'] == $this->Auth->user('id')):				// Booker Checky //
elseif($order['ItemUser']['owner_user_id'] == $this->Auth->user('id')):	// Host Checky //
	if($order['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforReview || $order['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Completed):
		$is_show = 1;
	endif;
endif;
?>
<?php if(!empty($is_show)):?>

		<div class="alert alert-info"> <!--  js-dispute-container hide -->
			<?php
				echo "<p>".sprintf(__l('If you have a disagreement or argument about your booking or not satisfied about the item and looking for claim your amount or require any other support based on below show cases, you can open a dispute.<br/>Note: Your posted dispute will be monitored by administrator and favor for the %s/host will made by administrator alone.'), Configure::read('item.alt_name_for_booker_singular_small'))."</p>";
			?>
		</div>
<?php endif; ?>
<?php echo $this->element('item_user-dispute-add', array('order_id' => $order['ItemUser']['id'], 'config' => 'sec'));?>