<?php
	if(!isset($type)) {
		$type = 'host';
	}
    echo $this->requestAction(array('controller' => 'items', 'action' => 'calendar', $type, 'ids' => $ids), array('return'));
?>