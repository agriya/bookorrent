<ul class="breadcrumb top-mspace ver-space">
	<li><?php echo $this->Html->link( __l('Coupons'), array('controller'=>'coupons','action'=>'index', 'admin' => 'true'), array('escape' => false));?><span class="divider">/</span></li>
	<li class="active"><?php echo __l('Add Coupon'); ?></li>
</ul>
<div class="coupons form sep-top">
	<?php 
		echo $this->Form->create('Coupon', array('class' => 'form-horizontal space'));?>
		<div class='clearfix'>
			<?php
			echo $this->Form->autocomplete('Item.title', array('label'=>Configure::read('item.alt_name_for_item_singular_caps'), 'acFieldKey' => 'Coupon.item_id', 'acFields' => array('Item.title'), 'acSearchFieldNames' => array('Item.title'), 'maxlength' => '100', 'acMultiple' => false));
			?>
		</div>
		<?php echo $this->Form->input('name', array('label' => __l('Coupon Code')));
    	echo $this->Form->input('discount', array('label' => __l('Discount') . ' (%)'));
    	echo $this->Form->input('number_of_quantity', array('label' => __l('Quantity')));
    	echo $this->Form->input('is_active',array('label'=> __l('Enable'))); 
	?>
	<div class="form-actions">
		<?php echo $this->Form->submit(__l('Add'), array('class' => 'btn btn-large btn-primary textb text-16'));?>
	</div>
	<?php echo $this->Form->end();?>
</div>