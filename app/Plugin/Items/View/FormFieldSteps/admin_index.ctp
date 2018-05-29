<?php /* SVN: $Id: $ */ ?>
<div class="FormFieldSteps index js-response space">
  <ul class="breadcrumb">
    <li><?php echo $this->Html->link(__l('Category Form and Steps'), array('controller' => 'categories', 'action' => 'index'), array('title' => __l('Category Form and Steps')));?><span class="divider">&raquo;</span></li>
    <li class="active"><?php echo __l('Form Field Steps');?></li>
  </ul>
  <ul class="nav nav-tabs">
    <li>
    <?php echo $this->Html->link('<i class="icon-th-list blackc"></i>'.__l('List'), array('controller' => 'categories', 'action' => 'index'), array('class' => 'blackc', 'title' =>  __l('List'),'data-target'=>'#list_form', 'escape' => false));?>
    </li>
    <li class="active"><a class="blackc" href="#add_form"><i class="icon-th-list blackc"></i><?php echo __l('Form Field Steps List');?></a></li>
    <li><?php echo $this->Html->link('<i class="icon-plus-sign"></i>'.' '.__l('Add'),array('controller' => $this->request->params['controller'], 'action'=>'add', 'type_id' => $this->params['pass'][0]),array('class' => 'blackc', 'escape'=>false,'title' => __l('Add')));?></li>
  </ul>
  <div class="panel-container">
    <div id="add_form" class="tab-pane fade in active">

  <section class="space clearfix">
     <div class="pull-left hor-space"><?php echo $this->element('paging_counter'); ?></div>
  </section>
<section class="space clearfix">
<?php echo $this->Form->create('FormFieldStep', array( 'url' => array('action' => 'sort'),'enctype' => 'multipart/form-data'));?>
 <section class="thumbnail no-pad no-mar">
  <div class="row-fluid">
    <ol class="unstyled no-pad no-mar">
      <?php
            $k=0;
        ?>
      <div class="js-sortable-group">
       <?php if (!empty($FormFieldSteps)):?>
       <?php foreach($FormFieldSteps as $FormFieldStep) {?>
        <div class="hide">
              <?php echo $this->Form->hidden('FormFieldStep.'. $k .'.id', array('value' => $FormFieldStep['FormFieldStep']['id']));
                    $k++;
                  ?>
        </div>


          <li class="sep-top active">
          <section class="dl cur containter-fluid accordion-toggle" data-toggle="collapse" data-target="<?php echo '.'.$FormFieldStep['FormFieldStep']['id'];?>">
          <div class="row-fluid">
           <div class="span1 dropdown top-space dc pull-left">
              <a href="#" title="Actions" data-toggle="dropdown" class="icon-cog blackc text-20 dropdown-toggle js-no-pjax"><span class="hide">Action</span></a>
                <ul class="unstyled dropdown-menu dl arrow clearfix">
                <li>
                <?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array( 'action'=>'edit', $FormFieldStep['FormFieldStep']['id'], 'type_id' => $this->params['pass'][0]), array('class' => 'js-edit','escape'=>false, 'title' => __l('Edit')));?>
               </li>
               <li>
               <?php if($FormFieldStep['FormFieldStep']['is_deletable']){
                 echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action'=>'delete', $FormFieldStep['FormFieldStep']['id']), array('class' => 'js-confirm ', 'escape'=>false,'title' => __l('Delete')));
              }?>
              </li>
              <?php echo $this->Layout->adminRowActions($FormFieldStep['FormFieldStep']['id']);  ?>
              </ul>
              </div>
            <div class="span20 dl top-space pull-left sep no-mar">
            <h5 class="hor-space"><?php echo $this->Html->cText($FormFieldStep['FormFieldStep']['name'].' ', false);?></h5>
            </div>

          </div>
          </section>
         </li>
        <?php }?>
        <?php else:?>
         <li>
        <div class="errorc space"><i class="icon-warning-sign errorc"></i> <?php echo __l('No Form Field Steps available');?></div>
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
if (!empty($FormFieldSteps)) : ?>
   <div class="pull-right"><?php  echo $this->element('paging_links'); ?></div>
<?php endif; ?>

  <?php echo $this->Form->end();?>
</section>
</div>
</div>
</div>
