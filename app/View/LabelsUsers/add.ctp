<?php /* SVN: $Id: $ */ ?>
<div class="labelsUsers form">
<?php echo $this->Form->create('LabelsUser', array('class' => 'admin-form'));?>
	<fieldset>
	<?php
		echo $this->Form->input('label_id', array('label'=>__l('Label')));
		echo $this->Form->input('user_id', array('label'=>__l('User')));
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Add'));?>
</div>
