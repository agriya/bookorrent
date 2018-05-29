<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="items index">
<?php //echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($items)):

$i = 0;
foreach ($items as $item):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' altrow';
	}
	if($item['Item']['is_active']){
		$status='Active';
	}
	else
	{
		$status='Not Active';
	}

?>
	<li class=" clearfix <?php echo $class;?>">
	<div class="">
	  <?php 
	    echo $this->Html->link($this->Html->showImage('Item', $item['Attachment'][0], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'city' => $item['Item']['slug'], 'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false));		  
	 ?>
	 </div>
	<div class="">
	
		<?php if(isset($current_latitude) && isset($current_longitude)): ?>
		<p><?php echo number_format($this->Html->distance($current_latitude,$current_longitude,$item['Item']['latitude'],$item['Item']['longitude'],'k'),1).__l(' KM Away'); ?></p>
		<?php endif;?>
		<p><?php
			echo  $this->Html->siteCurrencyFormat($item['Item']['price_per_day']) ."/Night";
		?>
		</p>
		<?php 
			$current_user_details = array(
				'username' => $item['User']['username'],
				'role_id' => $item['User']['role_id'],
				'id' => $item['User']['id'],
				'facebook_user_id' => $item['User']['facebook_user_id']
			);
			$current_user_details['UserAvatar'] = array(
				'id' => $item['User']['attachment_id']
			);
			echo $this->Html->getUserAvatarLink($current_user_details, 'small_thumb');
		?>	
		</div>
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

<?php
if (!empty($items)) {
	if(count($items)> 5){
    echo $this->element('paging_links');
	}
}
?>
</div>
