
	<li class="sep-bot clearfix ver-space" id="comment-<?php echo $this->Html->cInt($userComment['UserComment']['id'], false); ?>">
	<?php if ($userComment['PostedUser']['id'] == $this->Auth->user('id')) { ?>
			<div class="span pull-left">
				<span class="dropdown"> 
				<span class="graylightc icon-cog  left-space hor-smspace text-18 cur dropdown-toggle" data-toggle="dropdown" title="<?php echo __l('Actions');?>">  </span>
					<ul class="dropdown-menu arrow no-mar dl">
						<li>
						<?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('controller' => 'user_comments', 'action' => 'delete', $userComment['UserComment']['id']), array('class' => 'delete js-delete ', 'title' => __l('Delete'),'escape'=>false));?>
						</li>
					</ul>
				</span>
			</div>
		<?php } ?> 
	  <div class="span"><?php 
				$current_user_details = array(
					'username' => $userComment['PostedUser']['username'],
					'role_id' => $userComment['PostedUser']['role_id'],
					'id' => $userComment['PostedUser']['id'],
					'facebook_user_id' => $userComment['PostedUser']['facebook_user_id']
				);
				$current_user_details['UserAvatar'] = array(
					'id' => $userComment['PostedUser']['attachment_id']
				);
				echo $this->Html->getUserAvatarLink($current_user_details, 'small_thumb');
			?></div>
	  <div class="span21">
		<div class="clearfix">
		  <h5 class="pull-left"><?php echo $this->Html->link($userComment['PostedUser']['username'], array('controller' => 'users', 'action' => 'view', $userComment['PostedUser']['username']), array('escape' => false));?></h5>
		  <p class="pull-right"><?php echo __l('Recommended'); ?> <?php echo $this->Time->timeAgoInWords($userComment['UserComment']['created']) ;?></p>
		</div>
		 <?php echo $this->Html->cText(nl2br($userComment['UserComment']['comment']));?>
		 
	  </div>
	</li>