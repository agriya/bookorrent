<div id="js-delete-<?php echo $this->Html->cInt($attachment['Attachment']['id'], false); ?>" class="item-image-innerblock sep img-rounded span4 space pr">	
	<?php echo $this->Form->input('Attachment.'.$attachment['Attachment']['id'].'.id', array('type' => 'hidden', 'value' => $attachment['Attachment']['id'])); ?>
	<div class="clearfix">
		<span class="js-delete-attach pa image-close" data-remove_part="js-delete-<?php echo $this->Html->cInt($attachment['Attachment']['id'], false); ?>" data-error="js-error-message-<?php echo $attachment['Attachment']['id']; ?>" data-url="<?php echo Router::url(array('controller'=> 'items', 'action' => 'attachment_delete', $attachment['Attachment']['id']), true); ?>">
			<i class="icon-remove-sign cur text-18 orangec"></i>
		</span>
	</div>
	<div class="space">
	<?php 
		echo $this->Html->showImage('Item', $attachment['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($attachment['Attachment']['filename'], false)), 'title' => $this->Html->cText($attachment['Attachment']['filename'] , false))); 
	?>
	</div>
	<div id="js-error-message-<?php echo $this->Html->cInt($attachment['Attachment']['id'], false); ?>" class="clearfix hor-space"></div>
	<?php echo $this->Form->input('Attachment.'. $attachment['Attachment']['id'] .'.description', array('label' => false, 'type' => 'text', 'placeholder' => __l('Caption'),'div' => 'input text input-small')); ?>
</div>