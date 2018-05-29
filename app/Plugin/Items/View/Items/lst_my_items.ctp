<div class="js-response" id="js-update-block-submit">
        <div class="items-middle-block side-item clearfix">
      
        <h3 class="well space textb text-16 no-mar"><?php echo Configure::read('item.alt_name_for_item_plural_caps');?></h3>
          <div class="items-middle-inner-block">
            <?php echo $this->Form->create('ItemUser', array('class' => 'normal', 'action'=>'index'));
            echo $this->Form->input('ItemUser.type',array('type'=>'hidden','value'=>'myworks'));
            echo $this->Form->input('ItemUser.status',array('type'=>'hidden','value'=>'waiting_for_acceptance'));
            ?>
            <ol class="items-list myitems-list unstyled no-mar">
            <?php
            if (!empty($items)): ?>
            <?php
            $i = 0;
            foreach ($items as $item):
            	$class = null;
            	if ($i++ % 2 == 0) {
            		$class = ' altrow ';
            	}
            	if($item['Item']['is_active']) {
            		$status='Active';
            	}
            	else
            	{
            		$status='Not Active';
            	}
		$_SESSION['Item_Calender'][$item['Item']['id']] = $i;
    ?>
    	<li class=" sep-bot ver-space<?php echo $class;?> clearfix">
    	<div class="items-left-block pull-left">
    	  <?php
    		$options = array($item['Item']['id'] => '');
			if(!empty($this->request->params['named']['item_id']))
			{
    			$checked = in_array($item['Item']['id'], explode(',', $this->request->params['named']['item_id'])) ? 'checked' : '';
			}
			else
			{
				$checked = 'checked';
			}
    		echo $this->Form->input('Item.' .$i. '.item', array('type' => 'checkbox', 'class' => 'js-checkbox-list', 'checked' => $checked, 'legend' => false, 'label' => "",  'value' => $item['Item']['id'], 'div' => false, 'options' => $options));
			$item['Attachment'][0] = !empty($item['Attachment'][0]) ? $item['Attachment'][0] : array();
            echo $this->Html->link($this->Html->showImage('Item', $item['Attachment'][0], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false));
    	 ?>
    	 </div>
    	 <div class="items-right-block span6 pull-left">
    		<h4 class="items-title">
    		<?php echo sprintf('I%s: ',$i),$this->Html->link($this->Html->cText($item['Item']['title']), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'],  'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false));	?>
    		</h4>
           </div>
    	</li>
    <?php
    endforeach; ?>
    <?php
else:
?>
	<li>
		<div class="space dc grayc">
			<p class="ver-mspace top-space text-16 "><?php echo sprintf(__l('No %s available'), Configure::read('item.alt_name_for_item_plural_caps'));?></p>
		</div>
	</li>
<?php
endif;
?>
</ol>
<?php
    if (!empty($items)) { ?>
   <div class="select-block select-block">
            <?php echo __l('Select:'); ?>
            <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-select-all','title' => __l('All'))); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-select-none','title' => __l('None'))); ?>
 		  
        </div>
        <div class="clearfix save-filter-block top-space">
			<div class="clearfix pull-right">
          <?php
             echo $this->Form->submit(__l('Filter'),array('class'=>'js-filter-button btn btn-primary btn-large textb'));
            ?></div>
        </div>
         <?php } ?>
    	<?php echo $this->Form->end(); ?>

 
    </div>
    </div>
</div>
