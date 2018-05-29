<?php /* SVN: $Id: $ */ ?>
<div class="Roles form">
	<?php 
		echo $this->Form->create('Role');
		echo $this->Form->input('id');
		echo $this->Form->input('name',array('label'=>__l('Named Value')));
		echo $this->Form->end(__l('Update'));
	?>
</div>