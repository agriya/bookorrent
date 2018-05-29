<?php /* SVN: $Id: $ */ ?>
<ul class="breadcrumb top-mspace ver-space">
  <li><?php echo $this->Html->link( __l('Categories'), array('controller' => 'categories', 'action' => 'index', 'admin' => 'true'), array('escape' => false));?><span class="divider">/</span></li>
  <li class="active"><?php echo __l('Edit Category'); ?></li>
</ul>
<div class="form sep-top">
<?php echo $this->Form->create('Category', array('class' => 'form-horizontal space', 'enctype' => 'multipart/form-data'));?>

	<fieldset>
	<?php
		echo $this->Form->input('id');
		if(!empty($this->request->data['Category']['parent_id'])) {
			echo $this->Form->input('parent_id', array('label' => __l('Parent Category'), 'type' => 'select', 'empty' => __l('Please Select'), 'options' => $parent_categories));
		}
		echo $this->Form->input('name', array('label' => __l('Name')));
		echo $this->Form->input('description', array('type' => 'textarea', 'label' => __l('Description')));
		echo $this->Form->input('Attachment.filename', array('type' => 'file','size' => '33', 'label' => __l('Upload Photo'),'class' =>'browse-field'));
		if(!empty($this->request->data['Attachment']['filename'])):
	?>
		<div class="offset4">
		   <span class="hor-smspace">
				<?php echo $this->Html->showImage('Category', $this->request->data['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['Category']['name'], false)), 'title' => $this->Html->cText($this->request->data['Category']['name'], false), 'escape' => false)); ?>
			</span>
		</div>
	<?php
		endif;
	?>
	<?php if(empty($this->request->data['Category']['parent_id'])) { ?>
		<div class="js-category-icon">
			<?php echo $this->Form->input('CategoryIcon.filename', array('type' => 'file','size' => '33', 'label' => __l('Upload Icon'),'class' => 'browse-field')); ?>
			<?php if(!empty($this->request->data['CategoryIcon']['filename'])): ?>
			<div class="offset4">
			   <span class="hor-smspace">
					<?php echo $this->Html->showImage('CategoryIcon', $this->request->data['CategoryIcon'], array('type' => 'png', 'dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['Category']['name'], false)), 'title' => $this->Html->cText($this->request->data['Category']['name'], false), 'escape' => false)); ?>
				</span>
			</div>
			<?php endif; ?>
		</div>
	<?php } ?>
	<?php 
		echo $this->Form->input('is_active', array('label' => __l('Enable')));
 	?>
    </fieldset>
	<div class="form-actions">
		 <?php echo $this->Form->submit(__l('Update'), array('class' => 'btn btn-large btn-primary textb text-16'));?>
		 <?php echo $this->Form->end();?>
	</div>
</div>
