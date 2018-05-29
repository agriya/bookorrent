<ul class="breadcrumb top-mspace ver-space">
  <li><?php echo $this->Html->link( Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Flag Categories'), array('controller'=>'item_flag_categories','action'=>'index', 'admin' => 'true'), array('escape' => false));?><span class="divider">/</span></li>
  <li class="active"><?php echo __l('Add Flag Category'); ?></li>
</ul>
<div class="itemsFlagCategories form sep-top">
<?php echo $this->Form->create('ItemFlagCategory', array('class' => 'form-horizontal space'));?>
	<fieldset>
    	<?php echo $this->Form->input('name', array('label' => __l('Name'))); 
			  echo $this->Form->input('name_es', array('label' => __l('Name (Spanish)')));
		?>
    	<?php echo $this->Form->input('is_active',array('label'=> __l('Enable'))); ?>
	</fieldset>
<div class="form-actions">
<?php echo $this->Form->submit(__l('Add'), array('class' => 'btn btn-large btn-primary textb text-16'));?>
</div>
<?php echo $this->Form->end();?>
</div>