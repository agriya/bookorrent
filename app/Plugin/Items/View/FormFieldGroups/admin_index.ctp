<?php /* SVN: $Id: $ */ ?>
<div class="FormFieldGroups index js-response space">
  <ul class="breadcrumb">
    <li><?php echo $this->Html->link(Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Form and Groups'), array('controller' => 'categories', 'action' => 'index'), array('title' => Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Form and Groups')));?><span class="divider">&raquo;</span></li>
    <li class="active"><?php echo __l('Form Field Groups');?></li>
  </ul>
  <ul class="nav nav-tabs">
    <li>
    <?php echo $this->Html->link('<i class="icon-th-list blackc"></i>'.__l('List'), array('controller' => 'categories', 'action' => 'index'),array('class' => 'blackc', 'title' =>  __l('List'), 'data-target' => '#list_form', 'escape' => false));?>
    </li>
    <li class="active"><a class="blackc" href="#add_form"><i class="icon-th-list blackc"></i><?php echo __l('Form Field Groups List');?></a></li>
    <li><?php echo $this->Html->link('<i class="icon-plus-sign"></i>'.' '.__l('Add'),array('controller' => $this->request->params['controller'], 'action'=>'add', 'type_id' => $this->params['pass'][0]),array('class' => 'blackc', 'escape'=>false,'title' => __l('Add')));?></li>
  </ul>
  <div class="panel-container">
    <div id="add_form" class="tab-pane fade in active">

  <section class="space clearfix">
     <div class="pull-left hor-space"><?php echo $this->element('paging_counter'); ?></div>
  </section>
<section class="space clearfix">
<?php echo $this->Form->create('FormFieldGroup', array( 'url' => array('action' => 'sort'),'enctype' => 'multipart/form-data'));?>
 <section class="thumbnail no-pad no-mar">
  <div class="row-fluid">
    <ol class="unstyled no-pad no-mar clearfix">
      <?php
            $k=0;
        ?>
      <div class="js-sortable-group">
       <?php if (!empty($FormFieldGroups)):?>
       <?php foreach($FormFieldGroups as $FormFieldGroup) {?>
        <div class="hide">
              <?php echo $this->Form->hidden('FormFieldGroup.'. $k .'.id', array('value' => $FormFieldGroup['FormFieldGroup']['id']));
                    $k++;
                  ?>
        </div>


          <li class="sep-top active">
          <section class="dl cur containter-fluid accordion-toggle" data-toggle="collapse" data-target="<?php echo '.'.$FormFieldGroup['FormFieldGroup']['id'];?>">
          <div class="row-fluid">
           <div class="span1 dropdown top-space dc pull-left">
              <a href="#" title="<?php echo __l('Action'); ?>" data-toggle="dropdown" class="icon-cog blackc text-20 dropdown-toggle js-no-pjax"><span class="hide">Action</span></a>
                <ul class="unstyled dropdown-menu dl arrow clearfix">
                <li>
                <?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array( 'action'=>'edit', $FormFieldGroup['FormFieldGroup']['id'], 'type_id' => $this->params['pass'][0]), array('class' => 'js-edit','escape'=>false, 'title' => __l('Edit')));?>
               </li>
               <li>
               <?php if($FormFieldGroup['FormFieldGroup']['is_deletable']){
                 echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action'=>'delete', $FormFieldGroup['FormFieldGroup']['id']), array('class' => 'js-confirm ', 'escape'=>false,'title' => __l('Delete')));
              }?>
              </li>
              <?php echo $this->Layout->adminRowActions($FormFieldGroup['FormFieldGroup']['id']);  ?>
              </ul>
              </div>
            <div class="span20 dl top-space pull-left sep no-mar">
            <h5 class="hor-space"><?php echo $this->Html->cText($FormFieldGroup['FormFieldGroup']['name'].' ', false);?></h5>
            </div>

          </div>
          </section>
         </li>
        <?php }?>
        <?php else:?>
         <li>
        <div class="errorc space"><i class="icon-warning-sign errorc"></i> <?php echo __l('No Form Field Groups available');?></div>
        </li>
     </ol>
<?php
endif;?>
        </div>
        </div>
        </section>
</section>
<section class="clearfix hor-mspace bot-space">
<?php
if (!empty($FormFieldGroups)) : ?>
   <div class="pull-right"><?php  echo $this->element('paging_links'); ?></div>
<?php endif; ?>

  <?php echo $this->Form->end();?>
</section>
</div>
</div>
</div>
