<?php
	echo $this->requestAction(array('controller' => 'item_feedbacks', 'action' => 'add', 'item_order_id' => $order_id, 'view_type' => 'simple-feedback'), array('return'));
?>