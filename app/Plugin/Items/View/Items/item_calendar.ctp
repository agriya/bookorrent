<div>
 <?php
echo $this->requestAction(array('controller' => 'items', 'action' => 'datafeed','method'=>'guest','startdate'=> mktime(0, 0, 0, date('n'), date('1'),date('Y')),'enddate'=>mktime(0, 0, 0, date('n'), date('t'),date('Y')),'item_id'=>$id,'year'=>date('Y'),'month'=>date('m')), array('return')); ?>
</div>