<div class="js-response">
  <?php
    echo $this->Html->scriptBlock('base = "' . $this->base. '";');
    echo $this->Form->create('FormField');
    echo $this->Form->hidden('id');
    echo $this->Form->hidden('is_dynamic_field');
    echo $this->Form->hidden('label');
	echo $this->Form->hidden('category_id');
    echo $this->Form->input('depends_on', array('type' => 'select', 'options' => $dependson_fields, 'empty' => __l('Please Select'), 'info' => __l('If this field is only required based on the value of another field, enter the name of that field here, and the required value of that field below')));
    echo $this->Form->input('depends_value', array('label' => __l('Depends Value')));
  ?>
  <div class="form-actions">
    <?php echo $this->Form->submit(__l('Update')); ?>
  </div>
  <?php echo $this->Form->end(); ?>
</div>