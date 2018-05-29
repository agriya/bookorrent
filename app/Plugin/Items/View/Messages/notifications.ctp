<?php  if (!empty($messages)): ?>
<div class="ver-space">
  <h3 class="ver-space textb"><?php echo __l('Activities');?></h3>
</div>

<div class="well bot-mspace">
  <ol class="unstyled left-space activities-list">
	<?php		
	 $span_size = 'span15';
	  if (!empty($messages)):
        foreach($messages as $message):
       // quick fix for host review message
			if (!empty($message['Message']['item_user_status_id']) && $message['Message']['item_user_status_id'] == ConstItemUserStatus::HostReviewed):
				continue;
			endif;
	?>
		<?php if(!empty($message['Message']['item_user_status_id'])):?>
			<!-- ORDER STATUS CHANGED -->
			<?php if(!empty($message['Message']['item_user_status_id']) && $message['Message']['item_user_status_id'] != ConstItemUserStatus::SenderNotification && $message['Message']['item_user_status_id'] != ConstItemUserStatus::BookerReviewed && $message['Message']['item_user_status_id'] != ConstItemUserStatus::HostReviewed && $message['Message']['item_user_status_id'] != ConstItemUserStatus::BookerConversation && $message['Message']['item_user_status_id'] != ConstItemUserStatus::PrivateConversation && $message['Message']['item_user_status_id'] != ConstItemUserStatus::BookingRequestConfirmed && $message['Message']['item_user_status_id'] != ConstItemUserStatus::BookingRequestConversation):?>								
				<?php
					$avatar_positioning_class = '';
					$avatar = array();
					// Avatar positioning //
						$avatar_positioning_class = "avatar_middle_container";
						$user_type_container_class = "activities_system_container";
						if($message['Message']['item_user_status_id'] == ConstItemUserStatus::CanceledByAdmin):
							$user_type_container_class = "activities_administrator_container";
							$avatar_positioning_class = "avatar_admin_container";
						endif;
					// Eop //
				
				?>
				<?php if($message['Message']['item_user_status_id'] != ConstItemUserStatus::WaitingforReview):?>
				<li class="bot-space <?php echo $message['ItemUserStatus']['slug'];?> activity-status clearfix <?php echo $user_type_container_class;?>">
					<div class="row no-mar"> 
						<span data-toggle="popover" data-placement="right" class="date-info blackc textb span3 ">
							<?php echo $this->Time->timeAgoInWords($message['Message']['created']);?>
						</span>
						<div class="thumbnail no-round activity-content offset1 space span mob-ps pr pull-right">
							<span class="clearfix pull-left">
								<span class="graydarkerc waitting-status dc span2 tab-clr hor-mspace text-11 textb bot-space graydarkerc">
									<?php echo $this->Html->cText(__l($message['ItemUserStatus']['name']), false);?>
								</span>
								<span class="clearfix show dc">
									<?php echo $this->Html->link('#'.$message['ItemUser']['id'], array('controller' => 'messages', 'action' => 'activities', 'order_id' => $message['ItemUser']['id']), array('class' => 'orangec', 'title' => __l('View activities'),'escape'=>false));?>
								</span>
							</span>
							<p class="<?php echo $span_size;?> span16-sm clearfix no-mar"><?php echo $this->Html->conversationDescription($message);?> </p>
						</div>
					</div>				
				</li>
				<?php endif;?>
			<?php endif;?>
			<!-- NEGOTIATE -->
			<?php if($message['Message']['item_user_status_id'] == ConstItemUserStatus::BookingRequestConversation):?>				
				<?php
					$avatar_positioning_class = '';
					$avatar = array();
					if($message['Message']['user_id'] == $message['ItemUser']['owner_user_id']): // if message is to seller, then, requester is buyer //
						$avatar_positioning_class = "avatar_right_container";
						$user_type_container_class = "activities_buyer_container";
						$avatar = $message['ItemUser']['User'];
						
					elseif($message['Message']['user_id'] == $message['ItemUser']['user_id']): // if message is to buyer, then, requester is seller //
						$avatar_positioning_class = "avatar_left_container";
						$user_type_container_class = "activities_seller_container";
						$avatar = $message['Item']['User'];						
					endif;
				?>
				
				<li class="bot-space <?php echo $user_type_container_class;?>">
				
			  <div class="row no-mar"> 
				<span data-toggle="popover" data-placement="right" class="date-info blackc textb span3 ">
					<?php echo $this->Time->timeAgoInWords($message['Message']['created']);?>
				</span>
				<div class="thumbnail no-round activity-content offset1 space span mob-ps pr pull-right">
					<span class="clearfix pull-left">
						<span class="negotiation-status dc span2 tab-clr hor-mspace text-11 textb bot-space graydarkerc">		<?php echo __l('Negotiation Conversation')?>
						</span>
						<span class="clearfix show dc">
							<?php echo $this->Html->link('#'.$message['ItemUser']['id'], array('controller' => 'messages', 'action' => 'activities', 'order_id' => $message['ItemUser']['id']), array('class' => 'orangec', 'title' => __l('View activities'),'escape'=>false));?>
						</span>
					</span>
				  <p class="<?php echo $span_size;?> span16-sm clearfix no-mar">
					<?php if($avatar_positioning_class == "avatar_left_container" || $avatar_positioning_class == "avatar_right_container"):?>
						<div class="<?php echo $avatar_positioning_class;?>">	
							<cite>
								<?php
									$current_user_details = array(
										'username' => !empty($avatar['User']['username']) ? $avatar['User']['username'] : '',
										'role_id' => !empty($avatar['User']['role_id']) ? $avatar['User']['role_id'] : '',
										'id' => !empty($avatar['User']['id']) ? $avatar['User']['id'] : '',
										'facebook_user_id' => !empty($avatar['User']['facebook_user_id']) ? $avatar['User']['facebook_user_id'] : ''
									);
									$current_user_details['UserAvatar'] = array('id' => !empty($avatar['User']['attachment_id']) ? $avatar['User']['attachment_id'] : '');
									echo $this->Html->getUserAvatarLink($current_user_details, 'micro_thumb');
								?>
							</cite>
							<?php if($message['ItemUser']['user_id'] == $message['Message']['user_id']){ ?>
							<span><?php
								echo $this->Html->cText($avatar['username'], false);?></span>
							<?php if(!empty($message['ItemUser']['negotiation_discount'])): ?>
								<span><?php echo __l('Offered discount') . ' ' . $this->Html->cFloat($message['ItemUser']['negotiation_discount']) . '%';?></span>
							<?php endif;
								}?>
						</div>
					<?php endif;?>
					<div class="clearfix">
					   <?php 
					   $this->loadHelper('Text');
						echo $this->Html->cText(nl2br($this->Text->autoLinkUrls($message['MessageContent']['message'])));
					?>
					<ul class="attachement-list">
					<?php
						if(isset($message['MessageContent']['Attachment']['0'])){
						$attachment = $message['MessageContent']['Attachment']['0'];
						if (!empty($message['MessageContent']['Attachment']['0'])) :
							echo "<li>".__l('Attached').': '.$this->Html->link($attachment['filename'] , array( 'controller' => 'messages', 'action' => 'download', $message['Message']['hash'], $attachment['id']))."</li>";
						endif;
						}
					?>
					</ul>
					</div>										
					</p>
				</div>
			  </div>				
				
		</li>
		<?php endif;?>
		<!-- PRIVATE NOTE -->
		<?php if($message['Message']['item_user_status_id'] == ConstItemUserStatus::PrivateConversation && $message['Message']['user_id'] == $this->Auth->user('id')):?>				
				<?php
					$avatar_positioning_class = "avatar_right_container";
					$user_type_container_class = "activities_buyer_container";
					$avatar = $message['User'];					
				?>
				
				<li class="bot-space <?php echo $user_type_container_class;?>">
				  <div class="row no-mar"> 
					<span data-toggle="popover" data-placement="right" class="date-info blackc textb span3 ">
						<?php echo $this->Time->timeAgoInWords($message['Message']['created']);?>
					</span>
					<div class="thumbnail no-round activity-content offset1 space span mob-ps pr pull-right">
						<span class="clearfix pull-left">
							<span class="conform-status dc span2 tab-clr hor-mspace text-11 textb bot-space graydarkerc">
							<?php echo __l('Private Note'); ?>
							</span>
							<span class="clearfix show dc">
							<?php echo $this->Html->link('#'.$message['ItemUser']['id'], array('controller' => 'messages', 'action' => 'activities', 'order_id' => $message['ItemUser']['id']), array('class' => 'orangec', 'title' => __l('View activities'),'escape'=>false));?>
							</span>
						</span>
					  <p class="<?php echo $span_size;?> span16-sm clearfix no-mar">
						<?php if($avatar_positioning_class == "avatar_left_container" || $avatar_positioning_class == "avatar_right_container"):?>
							<div class="<?php echo $avatar_positioning_class;?>">	
								<?php if(!empty($avatar['UserAvatar']['id'])):?>
								<cite>
									<?php 
										$current_user_details = array(
											'username' => $avatar['username'],
											'role_id' => $avatar['role_id'],
											'id' => $avatar['id'],
											'facebook_user_id' => $avatar['facebook_user_id']
										);
										$current_user_details['UserAvatar'] = array(
											'id' => $avatar['attachment_id']
										);
										echo $this->Html->getUserAvatarLink($current_user_details, 'micro_thumb');
									?>
								</cite>
								<?php endif;?>
								<span><?php echo $this->Html->cText($avatar['username'], false);?></span>
							</div>
						<?php endif;?>
						<div class="">
						   <?php echo $this->Html->cText($message['MessageContent']['message']);?>
						<ul class="attachement-list">
						<?php
							if(isset($message['MessageContent']['Attachment']['0'])){
							$attachment = $message['MessageContent']['Attachment']['0'];
							if (!empty($message['MessageContent']['Attachment']['0'])) :
								echo "<li>".__l('Attached').': '.$this->Html->link($attachment['filename'] , array( 'controller' => 'messages', 'action' => 'download', $message['Message']['hash'], $attachment['id']))."</li>";
							endif;
							}
						?>
						</ul>
						</div>
						</p>
					</div>
				  </div>				
		</li>
		<?php endif;?>
		<?php if($message['Message']['item_user_status_id'] == ConstItemUserStatus::BookingRequest):?>				
				<?php
					$avatar_positioning_class = "avatar_right_container";
					$user_type_container_class = "activities_buyer_container";
					$avatar = $message['User'];					
				?>
				
				<li class="bot-space <?php echo $user_type_container_class;?>">
				  <div class="row no-mar"> 
					<span data-toggle="popover" data-placement="right" class=" blackc textb span2 ">
						<?php echo $this->Time->timeAgoInWords($message['Message']['created']);?>
					</span>
					<div class="thumbnail no-round activity-content offset1 space span mob-ps pr pull-right">
						<span class="clearfix pull-left">
							<span class="negotiation-status dc span2 tab-clr hor-mspace text-11 textb bot-space graydarkerc">
								<?php echo __l('Private Note'); ?>
							</span>
							<span class="clearfix show dc">
								<?php echo $this->Html->link('#'.$message['ItemUser']['id'], array('controller' => 'messages', 'action' => 'activities', 'order_id' => $message['ItemUser']['id']), array('class' => 'orangec', 'title' => __l('View activities'),'escape'=>false));?>
							</span>
						</span>
					  <p class="<?php echo $span_size;?> span16-sm clearfix no-mar">
					<?php if($avatar_positioning_class == "avatar_left_container" || $avatar_positioning_class == "avatar_right_container"):?>
						<div class="<?php echo $avatar_positioning_class;?>">	
							<?php if(!empty($avatar['UserAvatar']['id'])):?>
							<cite>
								<?php
									$current_user_details = array(
										'username' => $avatar['username'],
										'role_id' => $avatar['role_id'],
										'id' => $avatar['id'],
										'facebook_user_id' => $avatar['facebook_user_id']
									);
									$current_user_details['UserAvatar'] = array(
										'id' => $avatar['attachment_id']
									);
									echo $this->Html->getUserAvatarLink($current_user_details, 'micro_thumb');
								?>
							</cite>
							<?php endif;?>
							<span><?php echo $this->Html->cText($avatar['username'], false);?></span>
						</div>
					<?php endif;?>
					<div class="">
					   <?php echo $this->Html->cText($message['MessageContent']['message']);?>
					<ul class="attachement-list">
					<?php
						if(isset($message['MessageContent']['Attachment']['0'])){
						$attachment = $message['MessageContent']['Attachment']['0'];
						if (!empty($message['MessageContent']['Attachment']['0'])) :
							echo "<li>".__l('Attached').': '.$this->Html->link($attachment['filename'] , array( 'controller' => 'messages', 'action' => 'download', $message['Message']['hash'], $attachment['id']))."</li>";
						endif;
						}
					?>
					</ul>
					</div>						</p>
					</div>
				  </div>				
		</li>
		<?php endif;?>
		<!-- MESSAGE FROM BOOKER -->
		<?php if($message['Message']['item_user_status_id'] == ConstItemUserStatus::BookerConversation):?>				
				<?php
					$avatar_positioning_class = '';
					$avatar = array();
					if($message['Message']['user_id'] == $message['ItemUser']['owner_user_id']): // if message is to seller, then, requester is buyer //
						$avatar_positioning_class = "avatar_right_container";
						$user_type_container_class = "activities_buyer_container";
						$avatar = $message['ItemUser']['User'];
						
					elseif($message['Message']['user_id'] == $message['ItemUser']['user_id']): // if message is to buyer, then, requester is seller //
						$avatar_positioning_class = "avatar_left_container";
						$user_type_container_class = "activities_seller_container";
						$avatar = $message['Item']['User'];						
					endif;			
				?>
				
				<li class="bot-space<?php echo $user_type_container_class;?>">
				  <div class="row no-mar"> 
					<span data-toggle="popover" data-placement="right" class="date-info blackc textb span3 ">
						<?php echo $this->Time->timeAgoInWords($message['Message']['created']);?>
					</span>
					<div class="thumbnail no-round activity-content offset1 space span mob-ps pr pull-right">
						<span class="clearfix pull-left">
							<span class="conform-status dc span2 tab-clr hor-mspace text-11 textb bot-space graydarkerc">
								<?php echo __l('From') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps'); ?>
							</span>
							<span class="clearfix show dc">
								<?php echo $this->Html->link('#'.$message['ItemUser']['id'], array('controller' => 'messages', 'action' => 'activities', 'order_id' => $message['ItemUser']['id']), array('class' => 'orangec', 'title' => __l('View activities'),'escape'=>false));?>
							</span>
						</span>
					  <p class="<?php echo $span_size;?>  span16-sm clearfix no-mar">
					<?php if($avatar_positioning_class == "avatar_left_container" || $avatar_positioning_class == "avatar_right_container"):?>
						<div class="<?php echo $avatar_positioning_class;?>">	
							<cite>
								<?php 
									$current_user_details = array(
										'username' => $avatar['username'],
										'role_id' => $avatar['role_id'],
										'id' => $avatar['id'],
										'facebook_user_id' => $avatar['facebook_user_id']
									);
									$current_user_details['UserAvatar'] = array(
										'id' => $avatar['attachment_id']
									);
									echo $this->Html->getUserAvatarLink($current_user_details, 'micro_thumb');
								?>
							</cite>
							<span><?php echo $this->Html->cText($avatar['username'], false);?></span>
						</div>
					<?php endif;?>
					<div class="">
					   <?php echo $this->Html->cText($message['MessageContent']['message']);?>
					<ul class="attachement-list">
					<?php
						if(isset($message['MessageContent']['Attachment']['0'])){
						$attachment = $message['MessageContent']['Attachment']['0'];
							if (!empty($message['MessageContent']['Attachment']['0'])) :
								echo "<li>".__l('Attached').': '.$this->Html->link($attachment['filename'] , array( 'controller' => 'messages', 'action' => 'download', $message['Message']['hash'], $attachment['id']))."</li>";
							endif;
						}
					?>
					</ul>
					</div>
					</p>
					</div>
				  </div>		
				</li>
		<?php endif; ?>		
		<?php else:?>
		<!-- NORMAL CONVERSATION -->
		<?php
			$avatar_positioning_class = '';
			$avatar = array();
			if($message['Message']['user_id'] == $message['ItemUser']['owner_user_id']): // if message is to seller, then, requester is buyer //
				$avatar_positioning_class = "avatar_right_container";
				$user_type_container_class = "activities_buyer_container";
				$avatar = $message['ItemUser']['User'];
				$status_name = __l('Mutual cancel request');
			elseif($message['Message']['user_id'] == $message['ItemUser']['user_id']): // if message is to buyer, then, requester is seller //
				$avatar_positioning_class = "avatar_left_container";
				$user_type_container_class = "activities_seller_container";
				$avatar = $message['Item']['User'];
				$status_name = __l('Mutual cancel request');
			endif;
		?>
		<li class="bot-space <?php echo $user_type_container_class;?>">
			  <div class="row no-mar"> 
				<span data-toggle="popover" data-placement="right" class="date-info blackc textb span3 ">
					<?php echo $this->Time->timeAgoInWords($message['Message']['created']);?>
				</span>
				<div class="thumbnail no-round activity-content offset1 space span mob-ps pr pull-right">
					<span class="clearfix pull-left">
						<span class="conform-status dc span2 tab-clr hor-mspace text-11 textb bot-space graydarkerc">
							<?php echo __l('Conversation'); ?>
						</span>
						<span class="clearfix show dc">
							<?php echo $this->Html->link('#'.$message['ItemUser']['id'], array('controller' => 'messages', 'action' => 'activities', 'order_id' => $message['ItemUser']['id']), array('class' => 'orangec', 'title' => __l('View activities'),'escape'=>false));?>
						</span>
					</span>
				  <p class="<?php echo $span_size;?>  span16-sm clearfix no-mar">
					<?php if($avatar_positioning_class == "avatar_left_container" || $avatar_positioning_class == "avatar_right_container"):?>
						<div class="<?php echo $avatar_positioning_class;?>">	
							<cite>
								<?php if(!empty($avatar['User']['role_id'])){
										$current_user_details = array(
											'username' => $avatar['User']['username'],
											'role_id' => $avatar['User']['role_id'],
											'id' => $avatar['User']['id'],
											'facebook_user_id' => $avatar['User']['facebook_user_id']
										);
										$current_user_details['UserAvatar'] = array(
											'id' => $avatar['User']['attachment_id']
										);
										echo $this->Html->getUserAvatarLink($current_user_details, 'micro_thumb');
									}
								?>
							</cite>
							<span><?php echo $this->Html->cText($avatar['username'], false);?></span>
						</div>
					<?php endif;?>
					<div class="">
					   <?php echo $this->Html->cText($message['MessageContent']['message']);?>
					<ul class="attachement-list">
					<?php	if(isset($message['MessageContent']['Attachment']['0'])){
							$attachment = $message['MessageContent']['Attachment']['0'];
							if (!empty($message['MessageContent']['Attachment']['0'])) :
								echo "<li>".__l('Attached').': '.$this->Html->link($attachment['filename'] , array( 'controller' => 'messages', 'action' => 'download', $message['Message']['hash'], $attachment['id']))."</li>";
							endif;
						}
					?>
					</ul>
					</div>
					</p>
				</div>
			  </div>		
		</li>
		<?php endif;?>
        <?php
        endforeach;
    endif;
    ?>
</ol>
</div>
<?php endif; ?>