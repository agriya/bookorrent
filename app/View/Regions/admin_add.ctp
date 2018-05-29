<div class="regions form">
  <?php echo $this->Form->create('Region', array('class' => 'normal')); ?>
  <fieldset>
    <?php
    echo $this->Form->input('title', array('label' => __l('Title')));
    echo $this->Form->input('alias', array('class' => 'slug', 'label' => __l('Alias')));
    ?>
  </fieldset>
  <div class="form-actions">
    <?php echo $this->Form->submit(__l('Save')); ?>
    <div class="cancel-block">
    <?php echo $this->Html->link(__l('Cancel'), array('action' => 'index')); ?>
    </div>
  </div>
  <?php echo $this->Form->end(); ?>
</div>