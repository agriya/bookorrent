<div class="regions form">
  <?php echo $this->Form->create('Region', array('class' => 'normal', 'action' => 'edit')); ?>
  <fieldset>
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('title', array('label' => __l('Title')));
    echo $this->Form->input('alias', array('class' => 'slug', 'label' => __l('Alias')));
    ?>
  </fieldset>
  <div class="form-actions">
    <?php echo $this->Form->submit(__l('Update')); ?>
    <div class="cancel-block">
    <?php echo $this->Html->link(__l('Cancel'), array('action' => 'index')); ?>
    </div>
  </div>
  <?php echo $this->Form->end(); ?>
</div>