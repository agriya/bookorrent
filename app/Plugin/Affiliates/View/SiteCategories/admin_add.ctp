<?php /* SVN: $Id: $ */ ?>
<div class="siteCategories form">
<?php echo $this->Form->create('SiteCategory', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('name',array('label'=> __l('Name')));
		echo $this->Form->input('is_active', array('label'=> __l('Enable'), 'type'=> 'checkbox'));
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Add'));?>
</div>
