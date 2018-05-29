<?php if ($this->request->params['action'] != 'show_header') { ?>
	<div id="js-head-menu" class="header-right   clearfix hide">
<?php } ?>
		<div class="nav-collapse clearfix">
              <ul class="nav pull-right no-mar">
                <li class="dropdown">
				<a data-toggle="dropdown" class="dropdown-toggle clearfix mob-sep-none cur js-no-pjax" href="#" title="<?php echo  __l('Hosting');?>"> <span class="top-mspace show clearfix no-under dc"> <span class="span no-mar top-space"><i class="icon-home top-space text-24"></i></span><span class="show ver-space text-16 textb span no-mar"><span class="hor-smspace"><?php echo  __l('Hosting');?></span><span class="caret"></span></span></span></a>
                  <ul class="dropdown-menu">                  
                    <li><a  tabindex="-1" class="list-space " title="<?php echo __l('Post a') . ' ' . Configure::read('item.alt_name_for_item_singular_caps'); ?>" href="<?php if (Configure::read('item.item_fee')==0){ echo Router::url(array('controller' => 'items', 'action' => 'add/step:skip')); } else{ echo Router::url(array('controller' => 'items', 'action' => 'add'));} ?>"><span><span class="list-item tb"><?php echo __l('Post a') . ' ' . Configure::read('item.alt_name_for_item_singular_caps'); ?></span></span></a></li>                     
                   <?php if($this->Auth->sessionValid()):?>
						<li><?php echo $this->Html->link(__l('My') . ' ' . Configure::read('item.alt_name_for_item_plural_caps'), array('controller' => 'items', 'action' => 'index', 'type'=>'myitems','admin' => false), array('title' => __l('My') . ' ' . Configure::read('item.alt_name_for_item_plural_caps')));?></li>
						<li><?php echo $this->Html->link(__l('Calendar'), array('controller' => 'item_users', 'action' => 'index', 'type'=>'myworks', 'status' => 'waiting_for_acceptance','admin' => false), array('title' => __l('Calendar')));?></li>
						<?php if(isPluginEnabled("Seats")){  ?>
							<li><?php echo $this->Html->link(__l('My Halls'), array('controller' => 'halls', 'action' => 'index','admin' => false), array('title' => __l('My Halls')));?></li>
							<li><?php echo $this->Html->link(__l('My Partitions'), array('controller' => 'partitions', 'action' => 'index','admin' => false), array('title' => __l('My Partitions')));?></li>						
						<?php } ?>						
				   <?php endif; ?>
				    <?php if(isPluginEnabled("Requests")) : ?>
                    <li class="divider"></li>
                    <li><?php echo $this->Html->link(__l('Requests'), array('controller' => 'requests', 'action' => 'index','admin' => false), array('title' => __l('Requests')));?></li>
					 <?php endif; ?>
					 <?php if($this->Auth->sessionValid()):?>
						<?php if(isPluginEnabled('RequestFavorites') && isPluginEnabled('Requests')) : ?>
							<li><?php echo $this->Html->link(__l('Liked Requests'), array('controller' => 'requests', 'action' => 'index', 'type'=>'favorite','admin' => false), array('title' => __l('Liked Requests')));?></li>
						<?php endif;?>					
					 <?php endif; ?>
                  </ul>
                </li>
                <li class="dropdown">
				<a data-toggle="dropdown" class="dropdown-toggle cur clearfix mob-sep-none js-no-pjax" href="#" title="<?php echo  __l('Booking');?>"><span class="top-mspace show clearfix no-under dc"><span class="span no-mar top-space"><i class="icon-ticket top-space text-24"></i></span><span class="show ver-space text-16 textb span no-mar"><span class="hor-smspace"><?php echo  __l('Booking');?></span><span class="caret"></span></span></span></a>
                  <ul class="dropdown-menu">
				  <?php if(isPluginEnabled("Requests")) : ?><li><a class="list-space " title="<?php echo __l('Post a Request'); ?>" href="<?php echo Router::url(array('controller' => 'requests', 'action' => 'add'),false); ?>"><span><span class="post-request tb"><?php echo __l('Post a Request'); ?></span></span></a></li>	<?php endif; ?>				   
					  <?php if($this->Auth->sessionValid()):?>						
						<?php if(isPluginEnabled('Requests')):?>
						<li><?php echo $this->Html->link(__l('My Requests'), array('controller' => 'requests', 'action' => 'index', 'type' => 'myrequest', 'status' => 'active', 'admin' => false), array('title' => __l('My Requests')));?></li>
						<li class="divider"></li>
						<?php endif;?>													
						<li><?php echo $this->Html->link(__l('Bookings'), array('controller' => 'item_users', 'action' => 'index', 'type'=>'mytours','status' => 'in_progress', 'admin' => false), array('title' => __l('Bookings')));?></li>
						<?php if(isPluginEnabled('ItemFavorites')) : ?>
						<li ><?php echo $this->Html->link(__l('Liked') . ' ' . Configure::read('item.alt_name_for_item_plural_caps'), array('controller' => 'items', 'action' => 'index', 'type'=>'favorite','admin' => false), array('title' => __l('Liked') . ' ' . Configure::read('item.alt_name_for_item_plural_caps')));?></li>
						<?php endif;?>
					<?php endif; ?>
                    <li class="divider"></li>
                    <li><?php echo $this->Html->link(Configure::read('item.alt_name_for_item_plural_caps'), array('controller' => 'items', 'action' => 'index','admin' => false), array('title' => Configure::read('item.alt_name_for_item_plural_caps')));?></li>
                  </ul>
                </li>
				 <?php if(!$this->Auth->sessionValid()):?>
					<li id="js-before-login-head-menu" class="hide"><?php echo $this->Html->link('<span class="top-mspace show ver-space text-16 textb clearfix dc">'.__l('Login').'</span>', array('controller' => 'users', 'action' => 'login'), array('escape'=>false, 'class' => 'clearfix mob-sep-none cur' ,'title' => __l('Login')));?></li>
					<li id="js-before-register-head-menu" class="hide"><?php echo $this->Html->link('<span class="top-mspace show ver-space text-16 textb clearfix dc">'.__l('Register').'</span>', array('controller' => 'users', 'action' => 'register','type'=>'social', 'admin' => false), array('escape'=>false, 'class' => 'clearfix mob-sep-none cur','title' => __l('Register')));?></li>
				<?php elseif($this->request->params['action'] == 'show_header'): ?>
					<?php
					$countContent = '';
					$message_count = $this->Html->getUserUnReadMessages($this->Auth->user('id'));
					$message_count = !empty($message_count) ? $message_count : '';
					?>
					<?php if(!empty($message_count)) { 
						$countContent = '<span class="label label-success pa mob-ps">'.$message_count.'</span>';
					 }  ?>
					<?php $activiy_url = Router::url(array(
						'controller' => 'messages',
						'action' => 'notifications',
						'type' => 'compact'
						), true); ?>
					<li class="dropdown">
						<a class="js-notification js-no-pjax" data-target="#" data-toggle="dropdown" href="<?php echo $activiy_url; ?>">
							<span class="in-count top-space show pr">
								<i class="icon-globe hor-smspace text-24"></i>
								<span class="label label-success pa mob-ps">
									<?php echo $this->Html->getUserNotification($this->Auth->user('id'));?>
								</span>
							</span>
						</a>
						<div class="dropdown-menu arrow js-notification-list clearfix span16">
							<div class="dc"><?php echo $this->Html->image('ajax-circle-loader.gif', array('alt' => __l('[Image: Loader]') ,'width' => 16, 'height' => 11)); ?></div>
						</div>
					</li> 
				    <li><?php echo $this->Html->link('<span class="in-count top-space show pr"><i class="icon-envelope hor-smspace text-24"></i>'.$countContent.'</span>', array('controller' => 'messages', 'action' => 'index'), array('escape'=>false, 'class' => '', 'title' => __l('Inbox'))); ?>				   </li>
					<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle mob-sep-none js-no-pjax" href="#" title="">
						<span class="show ver-space top-mspace dl user-img-block">
							<?php
							$current_user_details = array(
								'username' => $this->Auth->user('username'),
								'role_id' =>  $this->Auth->user('role_id'),
								'id' =>  $this->Auth->user('id'),
								'facebook_user_id' =>  $this->Auth->user('facebook_user_id'),
								'user_avatar_source_id' =>  $this->Auth->user('user_avatar_source_id'),
								'twitter_avatar_url' =>$this->Auth->user('twitter_avatar_url')
							);
								echo $this->Html->getUserAvatar($current_user_details, 'small_thumb', false);
							?><span class="caret"></span>
						</span>
					</a>					
					  <ul class="dropdown-menu ">
						  <li><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard'), array('title' => __l('Dashboard')));?></li>
						 <li><?php echo $this->Html->link(__l('Settings'), array('controller' => 'user_profiles', 'action' => 'edit'), array('escape'=>false,'title' => __l('Settings')));?></li>
						 <?php /*<li><?php echo $this->Html->link(__l('Your public profile'), array('controller' => 'users', 'action' => 'view', $this->Auth->user('username')), array('title' => __l('Your public profile')));</li><?php */?>
                         <?php if(isPluginEnabled('SocialMarketing')):?>
							<li><?php echo $this->Html->link(__l('Find Friends'), array('controller' => 'social_marketings', 'action' => 'import_friends', 'type' => 'facebook'), array('escape'=>false,'title' => __l('Find Friends'))); ?></li>
						<?php endif;?>
						<?php if(isPluginEnabled('LaunchModes') && Configure::read('site.launch_mode') == "Private Beta"):?>
							<li ><?php echo $this->Html->link(__l('Invite Friends'), array('controller' => 'subscriptions', 'action' => 'invite_friends'), array('title' => __l('Invite Friends'), 'escape' => false)); ?></li>
						<?php endif;?>
						<li class="divider"></li>
						<?php if($this->Auth->sessionValid()){ ?>
							<li><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout'), 'class' => 'js-no-pjax'));?></li>
						<?php } ?>

					  </ul>
					</li>
				<?php endif; ?>
				<?php
					$currencies = $this->Html->getCurrencies();
					if(!empty($currencies)) {
					$selectedCurr = isset($_COOKIE['CakeCookie']['user_currency']) ? $currencies[$_COOKIE['CakeCookie']['user_currency']] : $currencies[Configure::read('site.currency_id')];
				?>
                <li class="dropdown dropdown-sm"><a data-toggle="dropdown" class="dropdown-toggle mob-sep-none" href="#" title="<?php echo $selectedCurr; ?>"><span class="show ver-space top-mspace text-16 textb dc"><span class="hor-smspace"><?php echo $selectedCurr; ?></span><span class="caret"></span></span></a>
                  <?php if(Configure::read('user.is_allow_user_to_switch_currency') && !empty($currencies)) : ?>
				  <ul class="dropdown-menu">
					<?php
						   foreach($currencies AS $key => $currency)
						   { 
								echo '<li>';
									echo $this->Html->link($currency, '#', array('title' => $currency, 'class'=>"js-currency-change" , 'data-currency_id' => $key, 'data-f' => $this->request->url));
								echo '</li>';
						   }
					?>
                  </ul>  
				  <?php endif; ?>
                </li>
				<?php } ?>
				<?php 
				if(isPluginEnabled('Translation')) :
					$languages = $this->Html->getLanguage();
					$selectedLan = isset($_COOKIE['CakeCookie']['user_language']) ?  $_COOKIE['CakeCookie']['user_language'] : Configure::read('site.language');
					if(count($languages) > 1) {
				?>
                <li class="dropdown dropdown-sm"><a data-toggle="dropdown" class="dropdown-toggle mob-sep-none" href="#" title="<?php echo $selectedLan; ?>"><span class="show ver-space top-smspace text-16 textb dc"><span class="hor-smspace"><?php echo $selectedLan; ?></span><span class="caret"></span></span> </a>
					<?php  if(Configure::read('user.is_allow_user_to_switch_language') && !empty($languages)) : ?>
				 <ul class="dropdown-menu">
					 <?php
						foreach($languages AS $key => $language)
						   { 
								echo '<li>';
									echo $this->Html->link($language, '#', array('title' => $language, 'class'=>"js-lang-change" , 'data-lang_id' => $key, 'data-f' => $this->request->url));
								echo '</li>';
						   }
					?>
                  </ul>
				<?php endif; ?>
                </li>
				<?php } 
					endif; 
				?>
				<li>
					<form class="form-search bot-mspace mob-searchform dc">
						<div class="input text top-smspace">
							<label for="search-text" class="hide"><?php echo __l('Search'); ?></label>
							<input type="search" id="search-text" class="span18" placeholder="<?php echo __l('Search'); ?>">
						</div>
						<div class="submit top-smspace">
						<input type="submit" class="btn" title="Search" value="Search">
						</div>
					</form>
					<a class="trigger-search" title=<?php echo __l("Search"); ?> href="#" id="js-trigger-search"><span class="show top-space top-mspace"><i class="icon-search text-20 no-pad"></i></span></a>
				</li>
              </ul>
            </div>
  <?php
	if ($this->request->params['action'] != 'show_header') {
		$script_url = Router::url(array(
			'controller' => 'users',
			'action' => 'show_header',
			'ext' => 'js',
			'admin' => false
		) , true) . '?u=' . $this->Auth->user('id');
		$js_inline = "(function() {";
		$js_inline .= "var js = document.createElement('script'); js.type = 'text/javascript'; js.async = true;";
		$js_inline .= "js.src = \"" . $script_url . "\";";
		$js_inline .= "var s = document.getElementById('js-head-menu'); s.parentNode.insertBefore(js, s);";
		$js_inline .= "})();";
?>
<script type="text/javascript">
//<![CDATA[
function getCookie (c_name) {var c_value = document.cookie;var c_start = c_value.indexOf(" " + c_name + "=");if (c_start == -1) {c_start = c_value.indexOf(c_name + "=");}if (c_start == -1) {c_value = null;} else {c_start = c_value.indexOf("=", c_start) + 1;var c_end = c_value.indexOf(";", c_start);if (c_end == -1) {c_end = c_value.length;}c_value = unescape(c_value.substring(c_start,c_end));}return c_value;}if (getCookie('_gz')) {<?php echo $js_inline; ?>} else {document.getElementById('js-head-menu').className = '';document.getElementById('js-before-login-head-menu').className = '';document.getElementById('js-before-register-head-menu').className = '';}
//]]>
</script>
<?php
	}
?>
<?php if ($this->request->params['action'] != 'show_header') { ?>
	</div>
<?php } ?>