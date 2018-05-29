<?php /* SVN: $Id: $ */ ?>
<div class="aclLinks form">
	<?php
		echo $this->Form->create('AclLink');
		echo $this->Form->input('id');
		echo $this->Form->input('name',array('label'=>__l('Name')));
		echo $this->Form->input('controller',array('label'=>__l('Controller')));
		echo $this->Form->input('action',array('label'=>__l('Action')));
		echo $this->Form->input('named_key',array('label'=>__l('Named Key')));
		echo $this->Form->input('named_value',array('label'=>__l('Named Value')));
		echo $this->Form->input('pass_value',array('label'=>__l('Pass Value'))); ?>
         <div class="form-actions">
	<?php echo $this->Form->submit(__l('Update')); ?>
          </div>
		<?php
		echo $this->Form->end();
    	?>
</div>