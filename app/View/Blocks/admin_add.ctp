<div class="blocks form">
  <?php echo $this->Form->create('Block', array('class' => 'form-horizontal space'));?>
    <fieldset>
      <div id="block-basic">
        <?php
          echo $this->Form->input('title', array('label' => __l('Title')));
          echo $this->Form->input('show_title', array('label' => __l('Show Title')));
          echo $this->Form->input('alias', array('class' => 'slug', 'rel' => __l('unique name for your block'),'label' => __l('Alias')));
          echo $this->Form->input('region_id', array('rel' => __l('if you are not sure, choose \'none\''),'label' => __l('Region')));
          echo $this->Form->input('body', array('label' => __l('Body')));
          echo $this->Form->input('class', array('label' => __l('Class')));
          echo $this->Form->input('element', array('label' => __l('Element')));
          echo $this->Form->input('status', array('label' => __l('Status')));
        ?>
      </div>
      <div class="form-actions">
        <?php echo $this->Form->submit(__l('Save'), array('class' => 'btn btn-large btn-primary textb text-16'));?>
        <?php echo $this->Html->link(__l('Cancel'), array('action' => 'index'), array('class' =>'btn js-bootstrap-tooltip')); ?>
      </div>
    </fieldset>
  <?php echo $this->Form->end(); ?>
</div>