<?php /* SVN: $Id: $ */ ?>
<div class="itemFeedbacks">
<ol class="unstyled  no-mar  clearfix">
<?php
$i=0;
if (!empty($itemFeedbacks)): ?>
<?php foreach ($itemFeedbacks as $itemFeedback): ?>
 <?php foreach ($itemFeedback['Attachment'] as $Feedback): $i++; ?>
<li class="clearfix ver-space sep-bot left-mspace mob-no-mar">
  <?php
    	  echo $this->Html->showImage('ItemFeedback', $Feedback, array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText(($Feedback['description'])?$Feedback['description']:$itemFeedback['Item']['title'], false)), 'title' => $this->Html->cText(($Feedback['description'])?$Feedback['description']:$itemFeedback['Item']['title'], false)));
    ?>
</li>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if($i==0): ?>
<li>
    <div class="space dc grayc">
	<p class="ver-mspace top-space text-16"><?php echo __l('No Guest photos available'); ?></p>
</div></li>
<?php endif; ?>
</ol>

</div>
