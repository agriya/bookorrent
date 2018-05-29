<ul class="breadcrumb top-mspace ver-space">
  <li><?php echo $this->Html->link(Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Flags'), array('controller'=>'item_flags','action'=>'index', 'admin' => 'true'), array('escape' => false));?><span class="divider">/</span></li>
  <li class="active"><?php echo sprintf(__l('Edit %s Flag'), Configure::read('item.alt_name_for_item_singular_caps')); ?></li>
</ul>
<div class="itemFlags itemFlags-form form sep-top">
    	<?php echo $this->Form->create('ItemFlag', array('class' => 'form-horizontal space'));?>
       <fieldset class="form-block round-5">
<?php
				echo $this->Form->input('id');
				echo $this->Form->autocomplete('ItemFlag.title', array('label'=> __l('Item'), 'acFieldKey' => 'ItemFlag.item_id', 'acFields' => array('Item.title'), 'acSearchFieldNames' => array('Item.title'), 'maxlength' => '100', 'acMultiple' => false));
				echo $this->Form->input('item_flag_category_id');
				echo $this->Form->input('message',array('label'=> __l('Message')));
?>
    	</fieldset>
	<div class="form-actions">
	<?php echo $this->Form->submit(__l('Update'), array('class' => 'btn btn-large btn-primary textb text-16'));?>
	</div>
	<?php echo $this->Form->end();?>

</div>