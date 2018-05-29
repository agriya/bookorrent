<div class="well bot-shad no-pad no-round no-mar">
	<div class="container mob-no-pad tab-no-pad">
		<h3 class="dc space ver-mspace">
			<span class="small-icon show"><img src="img/gift.png" alt="[Image: <?php echo __l('Available Options'); ?>]" title="<?php echo __l('Available Options'); ?>"></span> 
			<span class="span8 bot-space no-mar inline sep-bot"><?php echo __l('Available Options'); ?></span> 
		</h3>
		<div class="block-space clearfix">
		  <ul class="unstyled clearfix category-list dc">
			<?php 
				if(!empty($categories)) {
					foreach ($categories As $category) {
			?>
			<li class="span5 inline ver-space no-mar mob-no-pad mob-clr mob-dc">
				<?php 
					$content = '<span class="show cir-medium img-circle sep sep-small sep-primary dc">' . $this->Html->showImage('CategoryIcon', $category['CategoryIcon'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($category['Category']['name'], false)), 'title' => $this->Html->cText($category['Category']['name'], false), 'class' => 'img-circle')) . '</span><span class="dc ver-mspace show ver-space text-18 graydarkc">' . $this->Html->cText(__l($category['Category']['name']), false) . '</span>';
					echo $this->Html->link($content, array('controller' => 'categories', 'action' => 'view', $category['Category']['slug'], 'admin' => false), array('title'=>$this->Html->cText($category['Category']['name'], false),'escape' => false, 'class' => 'show clearfix no-under')); 
				?>
			</li>
			<?php 
					
					}
				}
			?>
		  </ul>
		</div>
	</div>
</div>