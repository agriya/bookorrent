<?php /* SVN: $Id: $ */ ?>
<div class="itemUsers form">
<?php 
echo $this->Form->create('ItemUser', array('class' => 'normal js-ajax-form')); ?>
	<fieldset>
	 <?php
	  echo $this->Form->input('item_id',array('type'=>'hidden'));
	  echo $this->Form->input('item_slug',array('type'=>'hidden'));
	  echo $this->Form->input('price',array('type'=>'hidden'));
	  echo $this->Form->input('from',array('type'=>'date', 'orderYear' => 'asc'));
	  echo $this->Form->input('to',array('type'=>'date', 'orderYear' => 'asc')); 
	  ?>
	</fieldset>
<?php echo $this->Form->end(__l('Book It')); ?></div>

