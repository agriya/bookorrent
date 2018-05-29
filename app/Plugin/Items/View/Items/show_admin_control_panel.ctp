<?php
	echo $this->element('admin_panel_item_view', array('controller' => 'items', 'action' => 'index', 'item' =>$item), array('plugin' => 'Items'));
?>