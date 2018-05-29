<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<?php if(empty($this->request->params['isAjax'])): ?>
<div class="items index js-response">
<?php endif; ?>
<h2 class="ver-space top-mspace text-32 sep-bot"><?php echo sprintf(__l('Assign a %s for'), Configure::read('item.alt_name_for_item_singular_small')) . ' "' . $this->Html->cText($request_name,false) . '"';?></h2>
<?php
echo $this->Form->create('Item', array('class' => 'normal','action'=>'manage_item', 'enctype' => 'multipart/form-data'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
echo $this->Form->input('request_id',array('type'=>'hidden'));
if (!empty($items)): ?>
<ol class="items-list1 clearfix unstyled " start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
$i = 0;
foreach ($items as $item):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' altrow ';
	}
	if($item['Item']['is_active']){
		$status='Active';
	}
	else
	{
		$status='Not Active';
	}
?>
	<li class="<?php echo $class;?> sep-bot ver-space  clearfix">	
		<div class="span1">
		  <?php
			  $options = array($item['Item']['id'] => '');
			echo $this->Form->input('Item.item', array ("div"=>"span input radio",'type' => 'radio', 'options' => $options, 'value' => $item['Item']['id'] . '#' . $item['Item']['id'])); 
			?>
		</div>
		<div class="span">
	 <?php 	echo $this->Html->link($this->Html->showImage('Item', $item['Attachment'][0], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'],  'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false));		  
	 ?> </div>
		<div class="span">
		<?php echo $this->Html->link($this->Text->truncate($item['Item']['title'],45,array('ending' => '...','exact' => false)), array('controller' => 'items', 'action' => 'view', $item['Item']['slug']),array('title' =>$item['Item']['title']));?>
			<?php if(in_array($item['Item']['id'], $available_list)) { ?>	
			<div class="label span no-mar"><?php echo __l('exact'); ?></div>
		<?php } ?>
		</div>
	</li>
<?php
    endforeach; ?>
    </ol>

    <div class="form-actions">
    <?php
        	echo $this->Form->submit(__l('Assign'), array('class' => 'btn btn-large btn-primary textb text-16','div'=>'pull-right submit')); ?>
	</div>
	<?php

else:
?>
<ol class="list clearfix js-response unstyled" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
				<li class="sep-bot">
					<div class="space dc grayc">
						<p class="ver-mspace top-space text-16">
							<?php echo sprintf(__l('No Matched %s available'), Configure::read('item.alt_name_for_item_plural_caps'));?>
						</p>
					</div>
				</li>		
	</ol>
<?php
endif;
		echo $this->Form->end();
?>


<?php if (!empty($items)) { ?>
<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?>">
<?php  echo $this->element('paging_links'); ?>	
</div>
<?php 
}
?>

<?php if(empty($this->request->params['isAjax'])): ?>
</div>
 <h4 class="dc"> <?php echo __l('Or'); ?> </h4>
<?php endif; ?>