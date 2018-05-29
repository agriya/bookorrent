<?php
    echo $this->requestAction( array('controller' => 'items', 'action' => 'calendar', 'host', 'item_id' => !empty($item_id) ? $item_id : ''), array('return'));?>