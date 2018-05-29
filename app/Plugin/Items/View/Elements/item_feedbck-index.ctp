<?php
if(empty($user_id)){
	$user_id =0;
}
    echo $this->requestAction(array('controller' => 'item_feedbacks', 'action' => 'index','user_id'=>$user_id,'item_id' =>$item_id,'type'=>'item','view'=>'compact'), array('return'));
?>