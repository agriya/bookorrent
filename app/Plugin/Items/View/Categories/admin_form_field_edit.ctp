<div class="space">
  <ul class="breadcrumb">
    <li><?php echo $this->Html->link(__l('Categories'), array('action' => 'index'), array('title' => __l('Categories')));?><span class="divider">&raquo;</span></li>
    <li><?php echo $this->Html->cText($category['Category']['name']);?><span class="divider">&raquo;</span></li>
    <li class="active"><?php echo __l('Form Fields');?></li>
  </ul>
  <ul class="nav nav-tabs">
    <li class="active"><a class="blackc" href="#add_form"><i class="icon-th-list blackc"></i><?php echo __l('Form Fields');?></a></li>
    <li><?php echo $this->Html->link('<i class="icon-eye-open"></i>'.__l('Preview'), array('controller' => 'categories', 'action' => 'admin_preview', $this->request->data['Category']['id']), array('class' => 'blackc js-no-pjax', 'title' =>  __l('Preview'), 'escape' => false));?></li>
  </ul>
  <div class="panel-container">
    <div id="add_form" class="tab-pane fade in active">
      <div class="top-mspace hor-space">
        <p class="alert alert-warning"><?php echo sprintf(__l('Warning! please edit with caution. Changes in the form fields affect the existing %s also.'), Configure::read('item.alt_name_for_item_singular_small'));?></p>
        <p class="alert alert-info"><?php echo sprintf(__l('Label is the text that appears in the form for %s. Display Text is the text that appears in %s view page. e.g., If Label is "Explain About Your %s", Display will be "About %s" or so.'), Configure::read('item.alt_name_for_item_plural_caps'), Configure::read('item.alt_name_for_item_singular_small'), Configure::read('item.alt_name_for_item_singular_caps'), Configure::read('item.alt_name_for_item_singular_caps'));?></p>
      </div>
      <div class="pull-right hor-space"><?php echo $this->Html->link('<i class="icon-plus-sign"></i>' . ' ' . __l('Add Step'), array('controller' => 'form_field_steps', 'action' => 'add', 'type_id' => $this->request->data['Category']['id']), array('data-toggle' => 'modal', 'data-target' => '#js-ajax-modal', 'class' => 'blackc js-no-pjax', 'escape' => false, 'title' => __l('Add Step'))); ?></div>
      <div class="row-fluid ver-space"> <?php echo $this->Form->create('Category', array('action' => 'form_field_edit', 'class' => 'space small-form-field-form')); ?> <?php echo $this->Form->hidden('id'); ?>
        <section class="thumbnail no-pad no-mar">
          <div class="row-fluid">
            <?php $j = $k = $n = 0; ?>
            <div class="js-sortable-step">
              <?php foreach($FormFieldSteps as $FormFieldStep) { ?>
                <ol class="unstyled no-pad no-mar">
                  <li class="sep-top active">
                    <div class="js">
                      <div class="hide">
                      <?php
                        echo $this->Form->hidden('FormFieldStep.'. $n .'.id', array('value' => $FormFieldStep['FormFieldStep']['id']));
                        $n++;
                      ?>
                      </div>
                    </div>
                    <section class="dl cur containter-fluid accordion-toggle" data-toggle="collapse" data-target="<?php echo '.form-field-step-' .  $FormFieldStep['FormFieldStep']['id'];?>">
                      <div class="row-fluid">
                        <div class="span1 top-space dc pull-left"><i class="icon-move top-mspace text-16"></i></div>
                        <div class="span20 dl top-space pull-left sep no-mar">
                          <h5 class="hor-space"><?php echo $this->Html->cText($FormFieldStep['FormFieldStep']['name']);?><span class="sfont grayc"><?php echo !empty($FormFieldStep['FormFieldStep']['info']) ? ' - ' . $this->Html->cText($FormFieldStep['FormFieldStep']['info']) : ''; ?></span></h5>
                        </div>
                        <div class="span3 dr pull-let">
                          <div class="dropdown pull-right top-smspace"> <a title="settings" class="btn btn-small text-16" data-toggle="dropdown" href="#"><i class="icon-cog"></i><span class="hide">Settings</span><i class="caret top-mspace"></i></a>
                          <ul class="unstyled dropdown-menu arrow arrow-right dl clearfix">
                            <?php if (!empty($FormFieldStep['FormFieldStep']['is_deletable'])) { ?>
                              <li><?php echo '<span>' . $this->Html->link('<i class="icon-remove pull-left"></i> '.__l('Delete'), array('controller'=>'form_field_steps','action' => 'delete', 'type_id' => $this->request->data['Category']['id'], $FormFieldStep['FormFieldStep']['id']), array('class' => 'js-confirm blackc', 'escape'=>false,'title' => __l('Delete'))) . '</span>'; ?></li>
                            <?php } ?>
							 <?php if (empty($FormFieldStep['FormFieldStep']['is_splash']) && (empty($FormFieldStep['FormFieldStep']['is_payment_step'])) && (empty($FormFieldStep['FormFieldStep']['is_payout_step']))) { ?>
                            <li class="hor-space"><span class="show hor-space accordion-toggle" data-toggle="expand" data-target="<?php echo '.form-field-step-' . $FormFieldStep['FormFieldStep']['id'];?>"><i class="icon-resize-vertical cur"></i><?php echo __l('Expand/Collapse');?></span></li>
                            <li><?php echo '<span>' . $this->Html->link('<i class="icon-plus-sign pull-left cur"></i> '.__l('Add New Group'), array('controller' => 'form_field_groups', 'action'=>'add', 'type_id' => $this->request->data['Category']['id'], 'step_id' => $FormFieldStep['FormFieldStep']['id']),array('data-toggle' => 'modal', 'data-target' => '#js-ajax-modal', 'class' => 'no-under blackc js-no-pjax', 'id' => 'addFieldLink', 'escape' => false, 'title' => __l('Add New Group'))) . '</span>';?></li>
							<?php } ?>
                            <li><?php echo '<span>' . $this->Html->link('<i class="icon-edit pull-left cur"></i> '.__l('Edit Step'), array('controller' => 'form_field_steps', 'action'=>'edit', $FormFieldStep['FormFieldStep']['id'],$this->request->data['Category']['id']),array('data-toggle' => 'modal', 'data-target' => '#js-ajax-modal', 'class' => 'no-under blackc js-no-pjax', 'id' => 'addFieldLink', 'escape' => false, 'title' => __l('Edit Step'))) . '</span>';?></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </section>
                  <section class="collapse <?php echo 'form-field-step-' . $FormFieldStep['FormFieldStep']['id'];?> com-bg over-hide">
                    <div class="accordion-inner">
					<?php if(empty($FormFieldStep['FormFieldStep']['is_splash'])) { ?>
                      <div class="clearfix">
                        <div class="pull-right hor-space"><?php echo $this->Html->link('<i class="icon-plus-sign"></i>' . ' ' . __l('Add Group'), array('controller' => 'form_field_groups', 'action' => 'add', 'type_id' => $this->request->data['Category']['id'], 'step_id' => $FormFieldStep['FormFieldStep']['id']), array('data-toggle' => 'modal', 'data-target' => '#js-ajax-modal', 'class' => 'blackc js-no-pjax', 'escape' => false, 'title' => __l('Add Group'))); ?></div>
                      </div>
                      <div class="row-fluid bot-space">
                        <section class="thumbnail no-pad no-mar">
                          <?php if(empty($FormFieldStep['FormFieldGroup'])) { ?>
                            <div class="alert alert-error no-mar"><?php echo __l('No Groups Added.'); ?></div>
                          <?php } ?>
                          <div class="row-fluid">
                            <div class="js-sortable-group">
                              <?php foreach($FormFieldStep['FormFieldGroup'] as $temp_FormFieldGroup) { ?>
                                <?php
                                  $FormFieldGroup['FormFieldGroup'] = $temp_FormFieldGroup;
                                  $FormFieldGroup['FormField'] = $temp_FormFieldGroup['FormField'];
                                ?>
                                <ol class="unstyled no-pad no-mar">
                                  <li class="sep-top active">
                                    <div class="js">
                                      <div class="hide"><?php
                                        echo $this->Form->hidden('FormFieldGroup.'. $k .'.id', array('value' => $FormFieldGroup['FormFieldGroup']['id']));
                                        $k++;
                                      ?>
                                      </div>
                                    </div>
                                    <section class="dl cur containter-fluid accordion-toggle" data-toggle="collapse" data-target="<?php echo '.form-field-group-' . $FormFieldGroup['FormFieldGroup']['id'];?>">
                                      <div class="row-fluid">
                                        <div class="span1 top-space dc pull-left"><i class="icon-move top-mspace text-16"></i></div>
                                          <div class="span20 dl top-space pull-left sep no-mar">
                                            <h5 class="hor-space"><?php echo $this->Html->cText($FormFieldGroup['FormFieldGroup']['name']);?><span class="sfont grayc"><?php echo !empty($FormFieldGroup['FormFieldGroup']['info']) ? ' - ' . $this->Html->cText($FormFieldGroup['FormFieldGroup']['info']) : ''; ?></span></h5>
                                          </div>
                                          <div class="span3 dr pull-let">
                                            <div class="dropdown pull-right top-smspace">
                                              <a title="settings" class="btn btn-small text-16" data-toggle="dropdown" href="#"><i class="icon-cog"></i><span class="hide">Settings</span><i class="caret top-mspace"></i></a>
                                              <ul class="unstyled dropdown-menu arrow arrow-right dl clearfix">
                                                <?php if (!empty($FormFieldGroup['FormFieldGroup']['is_deletable'])) { ?>
                                                  <li><?php echo '<span>' . $this->Html->link('<i class="icon-remove pull-left"></i> '.__l('Delete'), array('controller'=>'form_field_groups','action' => 'delete', $FormFieldGroup['FormFieldGroup']['id']), array('class' => 'js-confirm blackc', 'escape'=>false,'title' => __l('Delete'))) . '</span>'; ?></li>
                                                <?php } ?>
                                                <li class="hor-space"><span class="show hor-space accordion-toggle" data-toggle="expand" data-target="<?php echo '.form-field-group-' . $FormFieldGroup['FormFieldGroup']['id'];?>"><i class="icon-resize-vertical cur"></i><?php echo __l('Expand/Collapse');?></span></li>
                                                <li><?php echo '<span>' . $this->Html->link('<i class="icon-plus-sign pull-left cur"></i> '.__l('Add New Field'), array('controller' => 'form_fields', 'action'=>'add', $this->request->data['Category']['id'],'group_id' => $FormFieldGroup['FormFieldGroup']['id']),array('data-toggle' => 'modal', 'data-target' => '#js-ajax-modal', 'class' => 'no-under blackc js-no-pjax', 'id' => 'addFieldLink', 'escape' => false, 'title' => __l('Add New Field'))) . '</span>';?></li>
                                                <li><?php echo '<span>' . $this->Html->link('<i class="icon-edit pull-left cur"></i> '.__l('Edit Group'), array('controller' => 'form_field_groups', 'action'=>'edit', $FormFieldGroup['FormFieldGroup']['id']),array('data-toggle' => 'modal', 'data-target' => '#js-ajax-modal', 'class' => 'no-under blackc js-no-pjax', 'id' => 'addFieldLink', 'escape' => false, 'title' => __l('Edit Group'))) . '</span>';?></li>
                                              </ul>
                                            </div>
                                          </div>
                                        </div>
                                      </section>
                                      <section class="collapse <?php echo 'form-field-group-' . $FormFieldGroup['FormFieldGroup']['id'];?> com-bg over-hide">
                                        <div class="accordion-inner js-sortable">
                                          <?php if (!empty($FormFieldGroup['FormField'])) { ?>
                                            <table class="table table-bordered table-striped table-condensed no-mar">
                                              <thead>
                                                <?php echo $this->Html->tableHeaders(array(__l('Label'), __l('Display Text'), __l('Type'), __l('Info'), __l('Required'), __l('Editable'), __l('Active'),''));?>
                                              </thead>
                                              <tbody>
                                                <?php
                                                  if (!empty($FormFieldGroup['FormField'])) {
                                                    $i = 1;
                                                    foreach($FormFieldGroup['FormField'] as $key => $field) {
                                                      echo $this->element('form_field_row', array('key' => $j, 'field' => $field, 'multiTypes' => $multiTypes, 'cache' => array('config' => 'sec')));
                                                      $j++;
                                                    }
                                                  } else {
                                                    echo __l('No Fields available');
                                                  }
                                                ?>
                                              </tbody>
                                            </table>
                                          <?php } ?>
                                        </div>
                                      </section>
                                    </li>
                                  </ol>
                                <?php } ?>
                              </div>
                            </div>
                          </section>
                        </div>
         			<?php } else { ?>
					<div class="row-fluid bot-space">
                        <section class="thumbnail no-pad no-mar">
                          <?php if(!empty($FormFieldStep['FormFieldStep']['additional_info'])) { ?>
						  <div class="alert alert-success no-mar"><?php echo $this->Html->cText($FormFieldStep['FormFieldStep']['additional_info']); ?></div>
						  <?php } ?>
						</section>
						</div>
					<?php } ?>
					</div>  
                    </section>
                  </li>
                </ol>
              <?php } ?>
            </div>
          </div>
        </section>
		<?php if(!empty($FormFieldStep)): ?>
        <div class="form-actions ver-space <?php echo  $category['Category']['slug']; ?>"> <?php echo $this->Form->submit(__l('Submit'), array('class' => 'btn btn-module'));?> </div>
		<?php endif; ?>
        <?php echo $this->Form->end();?>
        <div class="modal hide fade" id="js-ajax-modal">
          <div class="modal-body">
			<?php
				echo $this->Html->image('throbber.gif', array('alt' => __l('[Image: Loading]'), 'title' => __l('Loading')));
			    echo __l('Loading...');
			?>
		  </div>
          <div class="modal-footer"> <a href="#" class="btn js-no-pjax" data-dismiss="modal"><?php echo __l('Close'); ?></a> </div>
        </div>
      </div>
    </div>
  </div>
</div>