<?php /* SVN: $Id: view.ctp 4973 2010-05-15 13:14:27Z aravindan_111act10 $ */ ?>
<?php Configure::write('highperformance.uids', $user['User']['id']); ?>
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<div class="users view user-view-blocks clearfix js-user-view" data-user-id="<?php echo $this->Html->cInt($user['User']['id'], false); ?>">
 <h2 class="textb top-space top-mspace"><?php echo $this->Html->cText($user['User']['username']);?></h2>
        <p><?php echo __l('Joined on').' '.$this->Html->cDateTimeHighlight($user['User']['created']);?></p>
        <div class="row no-mar sep-bot bot-space">
          <div class="span no-mar pr tab-clr">
            <div class="big-thumb img-polaroid"> 
				<?php
						$current_user_details = array(
							'username' => $user['User']['username'],
							'role_id' =>  $user['User']['role_id'],
							'id' =>  $user['User']['id'],
							'facebook_user_id' =>  $user['User']['facebook_user_id'],
							'user_avatar_source_id' =>  $user['User']['user_avatar_source_id'],
							'twitter_avatar_url' => $user['User']['twitter_avatar_url']
						);						
					?>
	                <?php
    					echo $this->Html->getUserAvatar($current_user_details, 'small_big_thumb', true);
					?>
			</div>
          </div>
		  <div class="span7 tab-clr">
          <p class="htruncate-ml6 js-bootstrap-tooltip" title="<?php echo $this->Html->cText($user['UserProfile']['about_me'], false); ?>">
			<?php echo $this->Html->cText($user['UserProfile']['about_me']);?>
		  </p>
		</div>
			<?php if($this->Auth->sessionValid() && ($this->Auth->user('id') != $user['User']['id'])): ?>
				<div class="cancel-block pull-left">
				  <?php echo $this->Html->link(__l('Contact Me'), array('controller'=>'messages','action'=>'compose','type' => 'contact','to' => $user['User']['username']), array('title' => __l('Contact'), 'class' => 'btn btn-primary dc'));?>
				</div>
			<?php endif; ?>
			<?php
	if(isPluginEnabled('SocialMarketing')) {
		if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))) { ?>				
		<div class="alu-f-<?php echo $this->Html->cInt($user['User']['id'], false);?> pull-right hide"><?php //after login user follow ?>
			<span class="add-block pull-right ">
			<?php echo $this->Html->link(__l('Follow'), array('controller' => 'user_followers', 'action' => 'add', $user['User']['username']), array('class' => 'btn btn-primary add-friend', 'title' => __l('Follow')));
			?>
			</span>
		</div>
		<div class="alu-uf-<?php echo $this->Html->cInt($user['User']['id'], false);?> pull-right hide"> <?php //after login  user unfollow ?>
			<span class="add-block pull-right ">
			<?php 
				$user_follower_id = '';
				if(!empty($user['UserFollower'])) {
					$user_follower_id = $user['UserFollower'][0]['id'];
				}
				echo $this->Html->link(__l('Unfollow'), array('controller' => 'user_followers', 'action' => 'delete', $user_follower_id), array('class' => 'btn btn-primary remove-follow', 'title' => __l('Unfollow')));
			?>
			</span>
		</div>
		<div class="blu-f-<?php echo $this->Html->cInt($user['User']['id'], false);?> pull-right hide"><?php //after login user follow ?>
			<span class="add-block pull-right ">
			<?php echo $this->Html->link(__l('Follow'), array('controller' => 'users', 'action' => 'login/?f='.$this->request->url), array('class' => 'btn btn-primary add-friend', 'title' => __l('Follow')));
			?>
			</span>
		</div>
	<?php 
		} else if($this->Auth->user('id') != $user['User']['id']) {
			if($this->Auth->user('id')) {
				if($this->Auth->user('id') != $user['User']['id'] && empty($user['UserFollower'])) {
	?>
				<span class="add-block pull-right ">
				   <?php echo $this->Html->link(__l('Follow'), array('controller' => 'user_followers', 'action' => 'add', $user['User']['username']), array('class' => 'btn btn-primary add-friend', 'title' => __l('Follow')));
					?>
				</span>
			<?php
				} else {
			?>
				<span class="add-block pull-right ">
				   <?php echo $this->Html->link(__l('Unfollow'), array('controller' => 'user_followers', 'action' => 'delete', $user['UserFollower'][0]['id']), array('class' => 'btn btn-primary remove-follow', 'title' => __l('Unfollow')));
					?>
				</span>
	<?php
				}
			} else {
	?>
			<span class="add-block pull-right ">
			   <?php echo $this->Html->link(__l('Follow'), array('controller' => 'users', 'action' => 'login/?f='.$this->request->url), array('class' => 'btn btn-primary add-friend', 'title' => __l('Follow')));
				?>
			</span>
	<?php
		   }
		}
	}
	?>
	
	<?php echo $this->element('user-stats',array('config' => 'sec')); ?>
        </div>
   <div class="main-content top-space top-mspace pr">
          <section>
		  <div id="ajax-tab-container-user" class="ajax-tab-container-user">
            <ul id="myTab2" class="nav nav-tabs top-space top-mspace">
				<li>
					<?php echo $this->Html->link(Configure::read('item.alt_name_for_item_plural_caps'), array('controller' => 'items', 'action' => 'index', 'user' => $user['User']['id'], 'type'=>'user','view'=>'compact','from'=>'ajax','limit'=>'10'), array('title' => Configure::read('item.alt_name_for_item_plural_caps'), 'class' => 'js-no-pjax', 'data-target'=>'#ItemsList'));?>
				</li>
			  <?php if(isPluginEnabled('Requests')) : ?>
				<li><?php echo $this->Html->link(__l('Requests'), array('controller' => 'requests', 'action' => 'index', 'user_id' => $user['User']['id'], 'view' => 'compact','from'=>'ajax'), array('title' => __l('Request'), 'class' => 'js-no-pjax', 'data-target'=>'#Requests'));?></li>
			  <?php endif;?>
			   <li><?php echo $this->Html->link(__l('Recommendations'), array('controller' => 'user_comments', 'action' => 'index', $user['User']['username']), array('title' => __l('Recommendations'), 'class' => 'js-no-pjax', 'data-target'=>'#Recommendations'));?></li>
			  <?php //if(Configure::read('friend.is_enabled')){?>
            			<li><?php echo $this->Html->link(__l('Followings'), array('controller' => 'user_followers', 'action' => 'index', 'user' => $user['User']['id'], 'type' => 'user', 'view' => 'compact'), array('title' => __l('Followings'), 'class' => 'js-no-pjax', 'data-target'=>'#Friends'));?></li>
					<?php //} ?>
			<li><?php echo $this->Html->link(__l('Reviews'), array('controller' => 'item_user_feedbacks', 'action' => 'index','user_id' =>$user['User']['id'],'view'=>'compact'), array('title' => __l('Reviews'), 'class' => 'js-no-pjax', 'data-target'=>'#Reviews'));?></li>
            </ul>
            <div class="sep-right ver-space sep-left sep-bot tab-round tab-content" id="myTabContent2">
              <div class="tab-pane" id="ItemsList"></div>
              <div id="Requests" class="tab-pane" > </div>
              <div id="Recommendations" class="tab-pane" ></div>
              <div id="Friends" class="tab-pane" ></div>
              <div id="Reviews" class="tab-pane" ></div>
            </div>
			</div>
          </section>
        </div>
</div>
<?php if (Configure::read('widget.user_script')) { ?>
      <div class="dc clearfix ver-space">
      <?php echo Configure::read('widget.user_script'); ?>
      </div>
    <?php } ?>
