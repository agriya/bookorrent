<?php
	echo $this->requestAction(array('controller' => 'categories', 'action' => 'getsubcategories', $parent_id, 'type' => $type), array('return'));	
?>