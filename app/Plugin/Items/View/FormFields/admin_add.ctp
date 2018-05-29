<div class="formFields form js-response-containter">
  <div class="modal-header">
    <button type="button" class="close js-no-pjax" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h2 id="js-modal-heading"><?php echo __l('Add New Field'); ?></h2>
  </div>
  <div class="clearfix main-section top-space">
    <?php
      $url = Router::url(array('controller' => 'categories', 'action' => 'form_field_edit', $this->request->data['FormField']['category_id']), true);
      echo $this->Html->scriptBlock('base = "' . $this->base. '";');
      echo $this->Form->create('FormField' ,array('class' => 'form-horizontal js-modal-form {"responsecontainer":"js-response-containter", "redirect_url":"'.$url.'"}'));
      echo $this->Form->hidden('category_id');
      echo $this->Form->hidden('form_field_group_id');
      echo $this->Form->input('label',array('label' => __l('Label')));
      echo $this->Form->input('display_text',array('label' => __l('Display Text')));
      echo $this->Form->input('type', array('class' => 'js-field-type','label' => __l('Type')));
    ?>
    <div class="js-options-show hide">
      <?php echo $this->Form->input('options', array('type' => 'text', 'info' => __l('Comma separated. To include comma, escape it with \ (e.g., Option with \,)'))); ?>
    </div>
    <?php
      echo $this->Form->input('info',array('label' => __l('Info')));
      echo $this->Form->input('is_active', array('label' => __l('Active?')));
      echo $this->Form->input('required',array('label' => __l('Required')));
	  echo $this->Form->input('FormField.is_editable', array('type' => 'checkbox', 'info' => __l('User can edit this field in \'Open for Funding\' status?')));
    ?>
    <div class="clearfix">
      <?php echo $this->Form->submit(__l('Submit')); ?>
    </div>
    <?php echo $this->Form->end(); ?>
  </div>
</div>