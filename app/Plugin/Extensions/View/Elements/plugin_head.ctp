<div class="<?php echo 'offset' . ($depth+1); ?> space">
  <div class="clearfix">
   <div class="clearfix">
	   <div class="pull-left plug-img dc space">
		<?php if($key != 'Others' && $key != 'Payments') { ?>
		<?php if (in_array($key, array_keys($image_title_icons))): ?>
		  <?php echo $this->Html->image($image_title_icons[$key]. '.png'); ?>
		  <?php elseif(in_array($key, array_keys($image_plugin_icons))): ?>
		  <i class="icon-<?php echo $this->Html->cText($image_plugin_icons[$key], false); ?> text-46"></i>
		<?php endif; ?>
		<?php } else { ?>
			<?php if($key == 'Others') { ?>
			<i class="icon-folder-open text-46"></i>
			<?php } elseif ($key == 'Payments') { ?>
				<i class="icon-dollar text-46"></i>
			<?php } ?>
		<?php } ?>
	  </div>
	  <div class="span20 top-space">
		<h4><?php echo $key; ?></h4>
	  <?php if (in_array($key, array_keys($title_description))): ?>
		  <div class="grayc top-space">
			 <?php echo $this->Html->cText($title_description[$key]); ?>
		  </div>
	  <?php endif; ?>
   </div>
  </div>
  <div class="sep-bot bot-space"></div>
</div>
</div>