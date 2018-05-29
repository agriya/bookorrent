<?php
	if(!empty($type) && $type == 'deliver'):
		echo $this->requestAction(array('controller' => 'messages', 'action' => 'compose', 'item_user_id' => $item_user_id, 'order' => 'deliver', 'view_type' => 'simple-deliver'), array('return'));
	else:
		echo __l('yet to come');
	endif;
?>