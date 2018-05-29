<?php /* SVN: $Id: $ */ ?>
<ul class="breadcrumb top-mspace ver-space">
  <li><?php echo $this->Html->link( __l('Categories'), array('controller' => 'categories', 'action' => 'index', 'admin' => 'true'), array('escape' => false));?><span class="divider">/</span></li>
  <li class="active"><?php echo __l('Add Category'); ?></li>
</ul>
<div class="form sep-top">
<?php echo $this->Form->create('Category', array('class' => 'form-horizontal space', 'enctype' => 'multipart/form-data'));?>
	<fieldset>
	<?php
		echo $this->Form->input('parent_id', array('label' => __l('Parent Category'), 'type' => 'select', 'empty' => __l('Please Select'), 'class' => 'js-parent-category-select', 'options' => $parent_categories));
		echo $this->Form->input('name', array('label' => __l('Name')));
		echo $this->Form->input('description', array('type' => 'textarea', 'label' => __l('Description')));
	?>
	<div class="required"><?php
		echo $this->Form->input('Attachment.filename', array('type' => 'file','size' => '33', 'label' => __l('Upload Photo'),'class' => 'browse-field')); ?>
	</div>
	<div class="js-category-icon required"><?php
		echo $this->Form->input('CategoryIcon.filename', array('type' => 'file','size' => '33', 'label' => __l('Upload Icon'),'class' => 'browse-field')); ?>
	</div>
	<?php 
		echo $this->Form->input('is_active', array('label' => __l('Enable'))); 
	?>
	</fieldset>
	<div class="form-actions">
		 <?php echo $this->Form->submit(__l('Add'), array('class' => 'btn btn-large btn-primary textb text-16'));?>
		 <?php echo $this->Form->end();?>
	</div>
</div>
