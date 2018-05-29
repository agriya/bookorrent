<?php
	$order_id = !empty($order_id) ? $order_id : '';
	echo $this->requestAction(array('controller' => 'items', 'action' => 'view', $slug, 'view_type' => 'sidebar-view', 'order_id' => $order_id, 'admin' => false), array('return'));
?>
