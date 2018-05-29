<?php
if(!empty($request_id)):
    echo $this->requestAction(array('controller' => 'requests', 'action' => 'index'), array('type' => $type, 'request_id' => $request_id, 'view' => 'compact', 'return'));
else:
    echo $this->requestAction(array('controller' => 'requests', 'action' => 'index'), array('type' => $type, 'city_id' => $city_id, 'country_id' => $country_id, 'view' => 'compact', 'return'));
endif;
?>