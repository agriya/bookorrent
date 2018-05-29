<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="itemUserFeedbacks js-response index item-feedbacks-block">
 
 <?php if (!empty($itemUserFeedbacks)) { ?>
 <div class="space">
	<?php echo $this->element('paging_counter');?>
</div>
<?php } ?>
<ol class="unstyled  no-mar js-comment-responses clearfix" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($itemUserFeedbacks)):
$i = 0;
 $num=1;
foreach ($itemUserFeedbacks as $itemUserFeedback):
?>
	<li class="sep-bot clearfix ver-space">
    		<div class="span">
        		<?php
					$current_user_details = array(
						'username' => $itemUserFeedback['User']['username'],
						'role_id' => $itemUserFeedback['User']['role_id'],
						'id' => $itemUserFeedback['User']['id'],
						'facebook_user_id' => $itemUserFeedback['User']['facebook_user_id']
					);
					$current_user_details['UserAvatar'] = array(
						'id' => $itemUserFeedback['User']['attachment_id']
					);
					echo $this->Html->getUserAvatarLink($current_user_details, 'small_thumb');
				?>
            </div>
			<div class="span22" >
			
			<div class="clearfix">
				<h5 class="pull-left"><?php echo $this->Html->link($this->Html->cText($itemUserFeedback['User']['username']), array('controller'=> 'users', 'action' => 'view', $itemUserFeedback['User']['username']), array('title' => $this->Html->cText($itemUserFeedback['User']['username'],false), 'escape' => false));?></h5>
				<p class="pull-right"><?php echo __l('Reviewed on');?>  <?php echo $this->Time->timeAgoInWords($itemUserFeedback['ItemUserFeedback']['created']);?></p>
		
				</div>
				<?php
				if($itemUserFeedback['ItemUserFeedback']['is_satisfied']) 
					echo  '<i class="icon-thumbs-up-alt text-20"></i>'; 
				else
					echo '<i class="icon-thumbs-down-alt text-20"></i>';
				?>
        		<?php echo $this->Html->cText($itemUserFeedback['ItemUserFeedback']['feedback']);?>
    		</div>
	
	</li>
<?php
$num=$num+1;
    endforeach;
	else:
?>
	<li>
	<div class="space dc">
		<p class="ver-mspace top-space text-16"><?php echo __l('No Reviews available');?></p>
	</div>
	</li>
<?php
endif;
?>
</ol>
	<?php
if (!empty($itemUserFeedbacks)) { ?>
	<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?> clearfix space pull-right mob-clr dc">
<?php
		echo $this->element('paging_links'); ?>
	</div>
<?php	}
?>
</div>