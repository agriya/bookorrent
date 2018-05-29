<?php /* SVN: $Id: add.ctp 619 2009-07-14 13:25:33Z boopathi_23ag08 $ */ ?>
<div class="itemFlags form js-responses">
<h2 class="ver-space top-mspace text-32 sep-bot"><?php echo __l('Flag This') . " " . Configure::read('item.alt_name_for_item_singular_caps');?></h2>
<?php echo $this->Form->create('ItemFlag', array('class' => 'form-horizontal js-ajax-form flag-form'));?>
	<?php
		if($this->Auth->user('role_id') == ConstUserTypes::Admin):
           echo $this->Form->autocomplete('ItemFlag.username', array('class'=>'span6', 'label'=> __l('User'), 'acFieldKey' => 'ItemFlag.user_id', 'acFields' => array('User.username'), 'acSearchFieldNames' => array('User.username'), 'maxlength' => '100', 'acMultiple' => false));
        endif;
			 echo $this->Form->input('Item.id',array('type'=>'hidden'));
		echo $this->Form->input('item_flag_category_id', array('label' => __l('Category'),'class'=>'span6'));
		echo $this->Form->input('message', array('label' => __l('Message'),'class'=>'span6'));
    ?>
	<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Submit'),array('class'=>'btn btn-large btn-primary textb text-16'));?>
	</div>
    <?php echo $this->Form->end();?>
</div>