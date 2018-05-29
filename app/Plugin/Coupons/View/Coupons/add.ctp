<div class="coupons form">
	<h2 class="ver-space sep-bot top-mspace text-32 sep-bot" ><?php echo __l('Add Coupon') . ' - ' . $this->Html->cText($item['Item']['title'],false);?></h2>
	<?php 
		echo $this->Form->create('Coupon', array('class' => 'form-horizontal space'));
		echo $this->Form->input('item_id', array('type' => 'hidden', 'value' => $item['Item']['id']));
    	echo $this->Form->input('name', array('label' => __l('Coupon Code')));
    	echo $this->Form->input('discount', array('label' => __l('Discount') . ' (%)'));
    	echo $this->Form->input('number_of_quantity', array('label' => __l('Quantity')));
    	echo $this->Form->input('is_active',array('label'=> __l('Enable'))); 
	?>
	<div class="form-actions">
		<?php echo $this->Form->submit(__l('Add'), array('class' => 'btn btn-large btn-primary textb text-16'));?>
	</div>
	<?php echo $this->Form->end();?>
</div>