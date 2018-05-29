<div>
 <h2><?php echo __l('Availability'); ?></h2>
</div>
<?php
	echo $this->requestAction(array('controller' => 'items', 'action' => 'calendar','guest','ids'=>$item_id), array('return')); 
?>
 