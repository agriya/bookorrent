<?php if($this->Auth->user('role_id') == ConstUserTypes::Admin): ?>
	<div class="accordion-admin-panel" id="js-admin-panel">
		<div class="clearfix js-admin-panel-head admin-panel-block">
			<div class="admin-panel-inner span3 pa accordion-heading no-mar no-bor clearfix box-head admin-panel-menu  mob-ps">
				<a data-toggle="collapse" data-parent="#accordion-admin-panel" href="#adminPanel" class="btn js-show-panel accordion-toggle span3 js-no-pjax blackc no-under clearfix"><i class="pull-right caret"></i><i class="icon-user"></i> <?php echo __l('Admin Panel'); ?></a>
			</div>
			<div class="accordion-body no-round no-bor collapse" id="adminPanel">
				<div id="ajax-tab-container-admin" class="accordion-inner thumbnail clearfix no-bor tab-container admin-panel-inner-block pr">
					<ul id="myTab2" class="nav nav-tabs tabs top-space top-mspace">
						<li class="tab"><?php echo $this->Html->link(__l('Action'), '#admin-action', array('class' => 'js-no-pjax span2', 'title'=>__l('Actions'), 'data-toggle'=>'tab', 'rel' => 'address:/admin_actions')); ?></li>
						<li class="tab"><?php echo $this->Html->link(Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Feedbacks'), array('controller' => 'item_feedbacks', 'action' => 'index', 'item_id' => $item['Item']['id'], 'simple_view' => 'user_view', 'admin' => true), array('title'=>__l('Item feedbacks'), 'class' => ' js-no-pjax','escape' => false,'data-target'=>'#Item_feedbacks','data-toggle'=>'tab')); ?></li>
						<?php if(isPluginEnabled('ItemFavorites')) :?>
						<li class="tab"><?php echo $this->Html->link(Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Favorites'), array('controller' => 'item_favorites', 'action' => 'index', 'item_id' => $item['Item']['id'], 'simple_view' => 'user_view', 'admin' => true), array('title'=>__l('Item favorites'), 'class' => ' js-no-pjax','escape' => false,'data-target'=>'#Item_favorites','data-toggle'=>'tab')); ?></li>
						<?php endif;?>
						<?php if(isPluginEnabled('ItemFlags')): ?>
						<li class="tab"><?php echo $this->Html->link(Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Flags'), array('controller' => 'item_flags', 'action' => 'index', 'item_id' => $item['Item']['id'], 'simple_view' => 'user_view', 'admin' => true), array('title'=>__l('Item flags'), 'class' => ' js-no-pjax','escape' => false,'data-target'=>'#Item_flags','data-toggle'=>'tab')); ?></li>
						<?php endif;?>
						<li class="tab"><?php echo $this->Html->link(__l('Bookings'), array('controller' => 'item_users', 'action' => 'index', 'item_id' => $item['Item']['id'], 'simple_view' => 'user_view', 'admin' => true), array('title'=>__l('Bookings'), 'class' => ' js-no-pjax','escape' => false,'data-target'=>'#Item_users','data-toggle'=>'tab')); ?></li>
						<li class="tab"><?php echo $this->Html->link(Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Views'), array('controller' => 'item_views', 'action' => 'index', 'item_id' => $item['Item']['id'], 'view_type' => 'user_view', 'admin' => true), array('title'=>__l('Item views'), 'class' => ' js-no-pjax','escape' => false,'data-target'=>'#Item_views','data-toggle'=>'tab')); ?></li>
					</ul>
					<div class="sep-right sep-left sep-bot tab-round tab-content" id="myTabContent2">
						<div class="tab-pane space "  id="admin-action">
							<ul class="action-link action-link-view clearfix unstyled">
								<?php if(empty($item['Item']['is_deleted'])):?>
									<li class="span4"><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action' => 'edit', $item['Item']['id'], 'admin' => true), array('escape' => false,'class' => 'graydarkc edit js-edit', 'title' => __l('Edit')));?></li>
									<li class="span4"><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action' => 'delete', $item['Item']['id'], 'admin' => true), array('escape' => false,'class' => 'graydarkc delete js-delete', 'title' => sprintf(__l('Disappear %s from user side'), Configure::read('item.alt_name_for_item_singular_small'))));?></li>
									<?php if($item['Item']['is_system_flagged']):?>
										<?php if($item['User']['is_active']):?>
											<li class="span4">	<?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Deactivate User'), array('controller' => 'users', 'action' => 'update_status', $item['User']['id'], 'status' => 'deactivate', 'admin' => true), array('escape' => false,'class' => 'graydarkc js-admin-update-item deactive-user', 'title' => __l('Deactivate user')));?></li>
										<?php else:?>
											<li class="span4"><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Activate User'), array('controller' => 'users', 'action' => 'update_status', $item['User']['id'], 'status' => 'activate', 'admin' => true), array('escape' => false,'class' => 'graydarkc js-admin-update-item active-user', 'title' => __l('Activate user')));?></li>
										<?php endif;?>
									<?php endif;?>
									<?php if($item['Item']['is_featured']):?>
										<li class="span4">	<?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Not Featured'), array('action' => 'update_status', $item['Item']['id'], 'featured' => 'deactivate', 'admin' => true), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-unfeatured not-featured', 'title' => __l('Not Featured')));?></li>
									<?php else:?>
										<li class="span4">	<?php echo $this->Html->link('<i class="icon-map-marker"></i>'.__l('Featured'), array('action' => 'update_status', $item['Item']['id'], 'featured' => 'activate', 'admin' => true), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-featured featured', 'title' => __l('Featured')));?></li>
									<?php endif;?>
									<?php if($item['Item']['is_system_flagged']):?>
										<li class="span4">	<?php echo $this->Html->link(__l('Clear system flag'), array('action' => 'update_status', $item['Item']['id'], 'flag' => 'deactivate', 'admin' => true), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-unflag clear-flag', 'title' => __l('Clear system flag')));?></li>
									<?php else:?>
										<li class="span4">	<?php echo $this->Html->link('<i class="icon-flag"></i>'.__l('Flag'), array('action' => 'update_status', $item['Item']['id'], 'flag' => 'active', 'admin' => true), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-flag flag', 'title' => __l('Flag')));?></li>
									<?php endif;?>
									<?php if($item['Item']['is_user_flagged']):?>
										<li class="span4">	<?php echo $this->Html->link('<i class="icon-flag"></i>'.__l('Clear user flag'), array('action' => 'update_status', $item['Item']['id'], 'user_flag' => 'deactivate', 'admin' => true), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-unflag clear-flag', 'title' => __l('Clear user flag')));?></li>
									<?php endif;?>
									<?php if($item['Item']['admin_suspend']):?>
										<li class="span4"><?php echo $this->Html->link('<i class="icon-repeat"></i>'.__l('Unsuspend'), array('action' => 'update_status', $item['Item']['id'], 'flag' => 'unsuspend', 'admin' => true), array('escape' => false,'class' => 'graydarkc js-admin-update-item  js-unsuspend unsuspend', 'title' => __l('Unsuspend')));?></li>
									<?php else:?>
										<li class="span4">	<?php echo $this->Html->link('<i class="icon-off"></i>'.__l('Suspend'), array('action' => 'update_status', $item['Item']['id'], 'flag' => 'suspend', 'admin' => true), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-suspend suspend', 'title' => __l('Suspend')));?></li>
									<?php endif;?>
								<?php else:?>
									<li class="span4"><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Permanent Delete'), array('action' => 'delete', $item['Item']['id'], 'admin' => true), array('escape' => false,'class' => 'graydarkc delete js-delete', 'title' => __l('Permanent Delete')));?></li>
								<?php endif; ?>
								<li class="span4"><?php echo $this->Html->link((($item['Item']['is_approved']) ? '<i class="icon-thumbs-down"></i>'.__l('Disapprove') : '<i class="icon-thumbs-up"></i>'.__l('Approve')), array('action' => 'update_status',  $item['Item']['id'], 'status' => (($item['Item']['is_approved']) ? 'disapproved' : 'approved'), 'admin' => true), array('title' => (($item['Item']['is_approved']) ? __l('Disapprove') : __l('Approve')), 'class' => (( $item['Item']['is_approved']) ? 'graydarkc js-admin-update-item js-pending pending' : 'graydarkc js-admin-update-item js-approve approve'), 'escape' => false)); ?></li>
							</ul>
						</div>
						<div id="Item_feedbacks"></div>
						<div id="Item_favorites"></div>
						<div id="Item_users"></div>
						<div id="Item_views"></div>
						<div id="Item_flags"></div>
					</div>	
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>