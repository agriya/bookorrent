<?php /* SVN: $Id: $ */ ?>
<div class="formFieldSteps form js-response-containter">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h2 id="js-modal-heading"><?php echo __l('Add New Step'); ?></h2>
  </div>
  <div class="clearfix main-section top-space">
      <?php
        $url = Router::url(array('controller' => 'categories', 'action' => 'form_field_edit', $this->request->data['FormFieldStep']['category_id']), true);
        echo $this->Form->create('FormFieldStep', array('class' => 'form-horizontal js-modal-form {"responsecontainer":"js-response-containter","redirect_url":"'.$url.'"}'));
      ?>
      <fieldset>
      <?php
        echo $this->Form->hidden('category_id');
        echo $this->Form->input('name', array('class'=>'span11','label' => __l('Name')));
        echo $this->Form->input('info', array('class'=>'span11','label' => __l('Info')));
        echo $this->Form->input('FormFieldStep.is_deletable', array('type' => 'hidden', 'value' => 1));
		?>
		<div class="required clearfix js-splash-info js-no-pjax-info hide">
        <label class="pull-left" for="BlogContent"><?php echo __l('Additional info');?></label>
        <div class="input textarea bot-space span16 no-mar">
          <?php echo $this->Form->input('FormFieldStep.additional_info', array('type' => 'textarea', 'class' => 'js-editor span23', 'label' => false, 'div' => false)); ?>
        </div>
		</div>
		<div class="js-editable-info">
		<?php echo $this->Form->input('FormFieldStep.is_editable', array('type' => 'checkbox', 'info' => '<i class="icon-info-sign"></i> '.__l('User can edit this step before \'Booking\''))); ?>
		</div>
      </fieldset>
      <div class="form-actions">
        <?php echo $this->Form->submit(__l('Add'));?>
      </div>
      <?php echo $this->Form->end();?>
    </div>
  </div>
</div>