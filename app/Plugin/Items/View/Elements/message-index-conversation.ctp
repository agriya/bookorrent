<?php
	echo $this->requestAction(array('controller' => 'messages', 'action' => 'index', 'order_id' => $order_id, 'admin' => false, 'span_size' => $span_size), array('return'));
?>