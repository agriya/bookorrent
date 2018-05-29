<?php 
if(!empty($this->request->params['named']['view_type']) && $this->request->params['named']['view_type'] == 'item') {
	echo $this->element('admin_panel_item_view', array('controller' => 'items', 'action' => 'index', 'item' => $item), array('plugin' => 'Items'));
} else {
	echo $this->element('admin_panel_user_view');
}
?>