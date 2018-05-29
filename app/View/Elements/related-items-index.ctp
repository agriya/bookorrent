<?php
if(!empty($request_id)):
    echo $this->requestAction(array('controller' => 'items', 'action' => 'index'), array('type' => $type, 'item_id' => $request_id, 'view' => 'compact', 'return'));
else:
    echo $this->requestAction(array('controller' => 'items', 'action' => 'index','is_admin' => $is_admin, 'admin' => false), array( 'type' => $type, 'request_latitude' => $latitude, 'request_longitude' => $longitude, 'view' => 'compact', 'return'));
endif;
?>