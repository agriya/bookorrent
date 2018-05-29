<p class="left-mspace no-mar">
<?php
echo $this->Paginator->counter(array(
'format' => __l('Page').' %page% '.__l('of').' %pages%, '.__l('showing').' %current% '.__l('records out of').' %count% '.__l('total, starting on record').' %start%', __l('ending on').' %end%'
));
?></p>

