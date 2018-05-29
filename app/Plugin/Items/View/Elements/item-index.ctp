<?php
	$limit = !empty($limit) ? $limit : '';
	if ($type == 'user'):
		echo $this->requestAction(array('controller' => 'items', 'action' => 'index'), array('user' =>$user_id,'type'=>'user','view'=>'compact', 'return'));
	elseif($type=='item'):
		if (empty($user_id) && empty($item_id)) {
			echo  $this->requestAction(array('controller' => 'items', 'action' => 'index'), array('item' => 'my_items', 'view' => 'compact', 'request_id' => $request_id, 'request_longitude' => $request_longitude, 'request_latitude' => $request_latitude, 'return'));
		} else {
			echo $this->requestAction(array('controller' => 'items', 'action' => 'index'), array('user_id' =>$user_id,'item_id'=>$item_id,'view'=>'compact', 'return'));
		}
	else:
		echo $this->requestAction(array('controller' => 'items', 'action' => 'index',$hash,$salt), array('limit'=>$limit,'item_id'=>$item_id,'city_id'=>$city_id,'view'=>'compact', 'return'));
	endif;
?>