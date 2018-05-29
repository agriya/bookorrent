<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="js-response js-responses">
 
<ol class="unstyled no-mar prop-list-mob prop-list clearfix" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($itemFeedbacks)):
$i = 0;
 $num=1;
foreach ($itemFeedbacks as $itemFeedback):
	$icon = ($itemFeedback['ItemFeedback']['is_satisfied']) ? ' <i class="icon-thumbs-up-alt text-16 greenc"></i>' : ' <i class="icon-thumbs-down-alt text-16 orangec"></i>';
?>
	<li class="clearfix ver-space sep-bot left-mspace mob-no-mar">
	 <?php if(isset($this->request->params['named']['user_id'])): ?>
		<div class="span dc no-mar mob-no-pad"><span class="label label-important textb show text-11 prop-count map_number"><?php echo $num; ?></span></div>
		<div class="span">
    	  <?php
			echo $this->Html->link($this->Html->showImage('Item', $itemFeedback['Item']['Attachment'][0], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($itemFeedback['Item']['title'], false)), 'title' => $this->Html->cText($itemFeedback['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $itemFeedback['Item']['slug'],  'admin' => false), array('title'=>$this->Html->cText($itemFeedback['Item']['title'],false),'escape' => false));
    	 ?>
	 </div>
	 <?php endif; ?>
		<div class="2">
    		<div class="span hor-space">
        		<?php
					$current_user_details = array(
						'username' => $itemFeedback['ItemUser']['User']['username'],
						'role_id' => $itemFeedback['ItemUser']['User']['role_id'],
						'id' => $itemFeedback['ItemUser']['User']['id'],
						'facebook_user_id' => $itemFeedback['ItemUser']['User']['facebook_user_id']
					);
					$current_user_details['UserAvatar'] = array(
						'id' => $itemFeedback['ItemUser']['User']['attachment_id']
					);
					echo $this->Html->getUserAvatarLink($current_user_details, 'small_thumb');
				?>
            </div>
			
        	<div class="clearfix"> 
			<?php if(isset($this->request->params['named']['user_id'])): ?>
				<h5 class="no-pad pull-left">
				<?php 
				echo $this->Html->link($this->Html->cText($itemFeedback['Item']['title'], false), array('controller' => 'items', 'action' => 'view', $itemFeedback['Item']['slug'], 'admin' => false), array('title'=>$this->Html->cText($itemFeedback['Item']['title'], false),'escape' => false)); ?>
				</h5>
			<?php else: ?>
        		<h5 class="no-pad pull-left"><?php echo $this->Html->link($this->Html->cText($itemFeedback['ItemUser']['User']['username']), array('controller'=> 'users', 'action' => 'view', $itemFeedback['ItemUser']['User']['username']), array('title' => $this->Html->cText($itemFeedback['ItemUser']['User']['username'],false), 'escape' => false));?></h5>
		<?php endif; ?>
			
				<div class="pull-right right-mspace clearfix">
        				 <?php echo __l('Reviewed ');?><?php echo $this->Time->timeAgoInWords($itemFeedback['ItemFeedback']['created']);?>
				</div>
			<div class="span18 top-space">
        		 <p class="left-space"><?php echo $icon; ?><?php echo $this->Html->cText($itemFeedback['ItemFeedback']['feedback']);?></p>
			</div>
    		</div>
		</div>
	</li>
<?php
$num=$num+1;
    endforeach; ?>
	</ol>
<?php 	else:
?>
	<ol class="unstyled">
	<li>
	<div class="space dc grayc">
		<p class="ver-mspace top-space text-16"><?php echo __l('No Reviews available');?></p>
	</div>
	</li>
	</ol>
<?php
endif;
?>

	<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?> clearfix space pull-right mob-clr dc">
		<?php
		if (!empty($itemFeedbacks)) {
			
			echo $this->element('paging_links');
			}
		?>
	</div>
</div>