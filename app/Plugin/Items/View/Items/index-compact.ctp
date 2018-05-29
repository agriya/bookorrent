<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="items index js-response">
<?php if(isset($this->request->params['named']['type']) && $this->request->params['named']['type']=='favorite' && isPluginEnabled('ItemFavorites')) : ?>
<h2><?php echo __l('Bookmarked') . ' ' . Configure::read('item.alt_name_for_item_plural_caps');?></h2>
<?php else: ?>
<h2><?php echo Configure::read('item.alt_name_for_item_plural_caps');?></h2>
<?php endif; ?>
<?php
	$view_count_url = Router::url(array(
		'controller' => 'items',
		'action' => 'update_view_count',
	), true);
?>
<ol class="list js-response js-view-count-update {'model':'item','url':'<?php echo $this->Html->cText($view_count_url, false); ?>'}" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($items)):

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
	<li class="<?php echo $class;?> clearfix">	
	<div class="">
	  <?php 
	    echo $this->Html->link($this->Html->showImage('Item', $item['Attachment'][0], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'city' => $item['Item']['slug'], 'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false), 'escape' => false));		  
	 ?>
	 </div>
	<div class="3">
		<p>
		<?php echo $this->Html->link($this->Html->cText($item['Item']['title']), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'],  'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false));	?>
		</p>
		<p><?php echo $this->Html->cText($item['Item']['description']);?></p>
		<dl class="list request-list clearfix">
		 <dt><?php echo __l('Status');?> </dt>
		 <dd><?php echo __l($status);?></dd>
		  <dt><?php echo __l('Price');?>Price </dt>
		 <dd><?php echo $this->Html->siteCurrencyFormat($item['Item']['price_per_day']);?></dd>
		 <dt><?php echo __l('Viewed');?> </dt>
		 <dd class="js-view-count-item-id js-view-count-item-id-<?php echo $this->Html->cInt($item['Item']['id'], false); ?> {'id':'<?php echo $item['Item']['id']; ?>'}"><?php echo $this->Html->cInt($item['Item']['item_view_count']);?></dd>
		</dl>
	</div>
<?php
if($this->Auth->user('id') == $item['Item']['user_id']): ?>
		<div class="actions"><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $item['Item']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $item['Item']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>

<?php endif; ?>

	</li>
<?php
    endforeach;
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
<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?>">
<?php
if (!empty($items)) {
	if(count($items)> 5){
    echo $this->element('paging_links');
	}
}
?>
</div>
</div>
