<div class="container pr bot-space">
  <div class="banner-content-trans-bg category-title dc pa mob-ps span10">
    <h2 class="whitec textb text-24 top-space top-mspace"><?php echo $this->Html->cText($category['Category']['name'], false); ?></h2>
    <p class="whitec space"><?php echo $this->Html->cText($category['Category']['description'], false); ?></p>			  
    <div class="text-18 textb blackc mspace">
		<?php echo $this->Html->link(__l('Browse'), array('controller' => 'items', 'action' => 'index','category_id' => $category['Category']['id']), array('title' => __l('Browse'), 'class' => 'textb mspace linkc')); ?>
		<span class="whitec"><?php echo __l('Or'); ?></span>
		<?php echo $this->Html->link(__l('Post') . ' ' . Configure::read('item.alt_name_for_item_singular_caps'), array('controller' => 'items', 'action' => 'add','category_id' => $category['Category']['id']), array('title' => __l('Post') . ' ' . Configure::read('item.alt_name_for_item_singular_caps'), 'class' => 'btn btn-large btn-primary text-16 textn mspace')); ?>
	</div>
  </div>
  <?php echo $this->Html->showImage('Category', $category['Attachment'], array('dimension' => 'category_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($category['Category']['name'], false)), 'title' => $this->Html->cText($category['Category']['name'], false))); ?>
</div>
<section class="mob-dc">
	<?php 
	if(!empty($sub_categories)) {
		$i = 1;
		foreach($sub_categories As $sub_category) { 
	?>
  <div class="clearfix block-space <?php if($i % 2 == 0) { ?>well no-mar no-round <?php } ?>">
	<div class="container bot-space">
		<h3 class="sep-bot bot-mspace bot-space span24 htruncate"><?php echo $this->Html->cText($sub_category['Category']['name'], false); ?></h3>			
		<div class="<?php if($i % 2 == 0) { ?>pull-left bot-smspace<?php } else { ?>pull-right<?php } ?> mob-clr">
		  <?php echo $this->Html->showImage('Category', $sub_category['Attachment'], array('dimension' => 'sub_category_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($sub_category['Category']['name'], false)), 'title' => $this->Html->cText($sub_category['Category']['name'], false))); ?>
		</div>
		<div class="span19 span17-sm  ver-mspace <?php if($i % 2 == 0) { ?> left-space pull-right mob-clr <?php } else { ?> right-space<?php } ?>">
			<p class="text-14 bot-space"><?php echo $this->Html->cText($sub_category['Category']['description'], false); ?></p>
			<?php echo $this->Html->link(__l('Browse'), array('controller' => 'items', 'action' => 'index','category_id' => $sub_category['Category']['id']), array('title' => __l('Browse'), 'class' => 'textb')); ?> 
			<span class="hor-space"><?php echo __l('Or'); ?></span>
			<?php echo $this->Html->link(__l('Post') . ' ' . Configure::read('item.alt_name_for_item_singular_caps'), array('controller' => 'items', 'action' => 'add','category_id' => $sub_category['Category']['id']), array('title' => __l('Post') . ' ' . Configure::read('item.alt_name_for_item_singular_caps'), 'class' => 'btn btn-sep')); ?>
		</div>
	</div>
  </div>
	<?php
		$i++;
		}
	}
	?>
</section>