<div class="coupons form ">
	<h2 class="ver-space sep-bot top-mspace text-32 sep-bot" ><?php echo __l('Add Field') . ' - ' . $this->Html->cText($item['Item']['title'], false);?></h2>
	<?php 
		echo $this->Form->create('BuyerFormField', array('class' => 'form-horizontal space'));
		echo $this->Form->input('item_id', array('type' => 'hidden', 'value' => $item['Item']['id']));
    	echo $this->Form->input('label', array('label' => __l('Label')));
    	echo $this->Form->input('display_text', array('label' => __l('Display Text')));
		echo $this->Form->input('type', array('label'=> __l('Type'),'class' => 'js-buyer-field-type'));
	?>	
	<div class="js-buyer-options-show hide">
		<?php echo $this->Form->input('options', array('type' => 'text', 'info' => __l('Comma separated. To include comma, escape it with \ (e.g., Option with \,)'))); ?>
	</div>
	<?php
    	echo $this->Form->input('info', array('label' => __l('Info')));
    	echo $this->Form->input('required', array('label' => __l('Required')));
    	echo $this->Form->input('is_active',array('label'=> __l('Enable'))); 
	?>
	<div class="form-actions">
		<?php echo $this->Form->submit(__l('Add'), array('class' => 'btn btn-large btn-primary textb text-16'));?>
	</div>
	<?php echo $this->Form->end(); ?>
</div>