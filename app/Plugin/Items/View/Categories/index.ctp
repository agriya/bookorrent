<div class="categories index">
	<h2 class="ver-space sep-bot top-mspace text-32 sep-bot" ><?php echo __l('Post') . ' ' . Configure::read('item.alt_name_for_item_singular_caps');?></h2>
	<div class="container">
		<?php if(!empty($categories)) { ?>
			<?php foreach($categories As $category) { ?>
		<div class="clearfix sep-bot">
			<div class="span6 top-space no-mar">
				<div class="sep">
					<?php echo $this->Html->showImage('Category', $category['Attachment'], array('dimension' => 'category_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($category['Category']['name'], false)), 'title' => $this->Html->cText($category['Category']['name'], false))); ?>
					<div class="dc clearfix hor-space top-mspace"><?php echo $this->Html->link($category['Category']['name'], array('controller' => 'items', 'action' => 'add','category_id' => $category['Category']['id']), array('title' => $category['Category']['name'], 'class' => 'js-bootstrap-tooltip htruncate btn btn-large span4 btn-primary textb'));?></div>
					<p class="dc smspace clearfix"><span class="js-bootstrap-tooltip htruncate-ml2 span5" title="<?php echo $this->Html->cText($category['Category']['description'], false); ?>"><?php echo $this->Html->cText($category['Category']['description'], false); ?></span></p>
				</div>
			</div>
			<?php $sub_categories = $this->Html->getSubCategories($category['Category']['id']); ?>
			<div class="span18 no-mar pull-right">
				<?php if(!empty($sub_categories)) { ?>
					<?php foreach($sub_categories As $sub_category) { ?>
						<div class="span5 mspace">
							<div class="sep">
								<?php echo $this->Html->showImage('Category', $sub_category['Attachment'], array('dimension' => 'sub_category_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($sub_category['Category']['name'], false)), 'title' => $this->Html->cText($sub_category['Category']['name'], false))); ?>
								<div class="dc clearfix hor-space top-mspace"><?php echo $this->Html->link($sub_category['Category']['name'], array('controller' => 'items', 'action' => 'add','category_id' => $sub_category['Category']['id']), array('title' => $sub_category['Category']['name'], 'class' => 'js-bootstrap-tooltip htruncate btn btn-large btn-primary span3 textb'));?></div>
								<p class="dc smspace clearfix"><span class="js-bootstrap-tooltip htruncate-ml2 span4" title="<?php echo $this->Html->cText($sub_category['Category']['description'], false); ?>"><?php echo $this->Html->cText($sub_category['Category']['description'], false); ?></span></p>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
			<?php } ?>
		<?php } ?>
	</div>
</div>