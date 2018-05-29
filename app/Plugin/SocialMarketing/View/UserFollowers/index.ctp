<?php if (empty($this->request->params['isAjax'])) { ?>
<div class="span23">
  <div class="js-response">
  <div class="ver-space top-mspace  clearfix sep-bot">
	<h2 class="span text-32"><?php echo __l('Followers');?></h2>
	<?php echo $this->element('sidebar', array('config' => 'sec')); ?></div>
<?php } ?>
<?php
	if (!empty($userFollowers)) {?>
		<div class="space"> <?php echo $this->element('paging_counter');?> </div>
	<?php
	}
	?>
   <ol class="friends-list unstyled clearfix top-space">
		<?php
		if (!empty($userFollowers)) {
		foreach ($userFollowers as $userFollower) { 
		?>
			<li id="friend-<?php echo $this->Html->cInt($userFollower['UserFollower']['id'], false); ?>" class="dc span2 pull-left  list-row clearfix sep space">
			<span>
			<?php
				$current_user_details = array(
					'username' => $userFollower['FollowUser']['username'],
					'role_id' => $userFollower['FollowUser']['role_id'],
					'id' => $userFollower['FollowUser']['id'],
					'facebook_user_id' => $userFollower['FollowUser']['facebook_user_id'],
					'user_avatar_source_id' =>  $userFollower['FollowUser']['user_avatar_source_id'],
					'twitter_avatar_url' => $userFollower['FollowUser']['twitter_avatar_url']
				);
				echo $this->Html->getUserAvatar($current_user_details, 'medium_thumb', true);
			?>
        	<p class="meta-row author">
		        <span title="<?php echo $this->Html->cText($userFollower['FollowUser']['username'], false);?>"><?php echo $this->Html->link($this->Html->cText($userFollower['FollowUser']['username']), array('controller'=> 'users', 'action' => 'view', $userFollower['FollowUser']['username']), array('class' => 'grayc htruncate span2 no-mar', 'escape' => false));?></span>
		    </p>
			</span>
			</li>
		<?php
		}
		} else {
		?>
		<li>
			<div class="space dc grayc">
   	<p class="ver-mspace top-space text-16">
				<?php
					echo __l('No Followings available');
				?></p></div>
		</li>
		<?php
		}
		?>
	</ol>
	<?php
	if (!empty($userFollowers)) {?>
		<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?> paging clearfix space pull-right mob-clr"> <?php echo $this->element('paging_links'); ?> </div>
	<?php
	}
	?>
</div>
<?php if (empty($this->request->params['isAjax'])) { ?>
  </div>
</div>
<?php } ?>