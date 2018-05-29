<?php
	echo $this->requestAction(array('controller' => 'sudopays', 'action' => 'payout_connections','user' => $user,'redirect_url' => $redirect_url, 'admin' => false), array('return')); 
?>