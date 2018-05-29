<?php
  $typeOptions['value'] = $field['options'];
  $typeOptions['type'] = 'text' ;
  $hide_class = '';
  if (!in_array($field['type'], $multiTypes)) {
    $hide_class = 'hide';
  }
  $row_options = $this->Form->hidden('FormField.' . $key . '.type', array('value' => $field['type'])) . $this->Form->hidden('FormField.' . $key . '.options', array('value' => ''));
  $typeOptions['info'] =__l('Comma separated. To include comma, escape it with \ (e.g., Option with \,)');
  if (!empty($field['is_dynamic_field'])) {
    $row_options =  '<div class="grid_left select-field-block">' . $this->Form->input('FormField.' . $key . '.type', array('label' => false, 'value' => $field['type'],'class' => 'js-field-type-edit')) . '</div>' . '<div class="options-field-block ' . $hide_class.' ">' . $this->Form->input('FormField.' . $key . '.options', $typeOptions) . '</div>';
  } else {
    $row_options = '<div class="grid_left select-field-block">' . $field['type'] . '</div>';
  }
  if (!empty($field['is_deletable'])) {
    $row_delete = $this->Html->link('<i class="icon-remove"></i> ' . __l('Remove'), array('controller' => 'form_fields', 'action' => 'delete', $field['id']), array('class' => 'js-no-pjax js-form-field-delete show', 'title' => __l('Remove'), 'escape' => false));
  } else {
    $row_delete = '<span class="label label-info js-tooltip" title="' . __l('Core, cannot be deleted') . '">' . __l('Core') . '</span>';
  }
  $row_active = '-';
  if (!empty($field['is_deletable'])) {
    $row_active = $this->Form->input('FormField.' . $key . '.is_active', array('label' => '', 'checked' => ($field['is_active'] ? 'checked' : '')));
  }
  $row_display_text ='-';
  if($field['is_show_display_text_field']){
  $row_display_text =  $this->Form->input('FormField.' . $key . '.display_text', array('label' => false, 'value' => $field['display_text']));
  }
  $row = array(
    $this->Form->hidden('FormField.' . $key . '.id', array('value' => $field['id'])) .
    $this->Form->hidden('FormField.' . $key . '.catrgory_id', array('value' => $field['category_id'])) .
    $this->Form->hidden('FormField.' . $key . '.is_dynamic_field', array('value' => $field['is_dynamic_field'])) .
    $this->Form->hidden('FormField.' . $key . '.is_deletable', array('value' => $field['is_deletable'])) .
    $this->Form->input('FormField.' . $key . '.label', array('label' => false, 'value' => $field['label'])),
    $row_display_text,
    $row_options,
    $this->Form->input('FormField.' . $key . '.info', array('label' => false, 'value' => $field['info'], 'maxlength' => '1000')),
    $this->Form->input('FormField.' . $key . '.required', array('label' => '', 'checked' => ($field['required']?'checked':''))),
	$this->Form->input('FormField.' . $key . '.is_editable', array('label' => '', 'checked' => ($field['is_editable']?'checked':''))),
    $row_active,
    $row_delete . $this->Html->link(' <i class="icon-edit"></i> ' . __l('Depends On'), array('controller' => 'form_fields', 'action' => 'edit', $field['id']), array('data-toggle' => 'modal', 'data-target' => '#js-edit-ajax-modal', 'class' => 'js-no-pjax show' ,'title' => __l('Depends On'), 'escape' => false))
  );
  echo $this->Html->tableCells($row, array('class' => 'ui-state-default'),array('class' => 'ui-state-default'));
?>

<div class="modal hide fade" id="js-edit-ajax-modal">
  <div class="modal-header">
  <button type="button" class="close js-no-pjax" data-dismiss="modal" aria-hidden="true">&times;</button>
  <h2><?php echo __l('Depends On'); ?></h2>
  </div>
  <div class="modal-body"></div>
  <div class="modal-footer"> <a href="#" class="btn js-no-pjax" data-dismiss="modal"><?php echo __l('Close'); ?></a> </div>
</div>
