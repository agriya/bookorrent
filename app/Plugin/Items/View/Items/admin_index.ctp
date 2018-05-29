<?php /* SVN: $Id: $ */ ?>
<div class="items index js-response">
	<ul class="breadcrumb top-mspace ver-space">
		<li><?php echo $this->Html->link(__l('Dashboard'), array('controller'=>'users','action'=>'stats'), array('class' => 'js-no-pjax', 'escape' => false));?> <span class="divider">/</span></li>
        <li class="active"><?php echo Configure::read('item.alt_name_for_item_plural_caps'); ?></li>
	</ul> 
    <div class="tabbable ver-space sep-top top-mspace">
		<div id="list" class="tab-pane active in no-mar">
			<div class="clearfix">
			<?php 
				$class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Disapproved) ? 'active' : null;
				echo $this->Html->link('<dl class="dc list users '.$class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'.ucwords(__l('Waiting for Approval')).'">'.ucwords(__l('Waiting for Approval')).'</dt><dd title="'.$this->Html->cInt($waiting_for_approval ,false).'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($waiting_for_approval ,false).'</dd></dl>', array('controller'=>'items','action'=>'index','filter_id' => ConstMoreAction::Disapproved), array('escape' => false,'class'=>'no-under show pull-left mob-clr bot-space bot-mspace cur'));
				
				$class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Active) ? 'active' : null;
				echo $this->Html->link('<dl class="dc list users '.$class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'.__l('Enabled').'">'.__l('Enabled').'</dt><dd title="'.$this->Html->cInt($active_items ,false).'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($active_items ,false).'</dd></dl>', array('controller'=>'items','action'=>'index','filter_id' => ConstMoreAction::Active), array('escape' => false,'class'=>'no-under show pull-left mob-clr bot-space bot-mspace cur'));
				
				$class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) ? 'active' : null;
				echo $this->Html->link('<dl class="dc list users '.$class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'.__l('Disabled').'">'.__l('Disabled').'</dt><dd title="'.$this->Html->cInt($inactive_items ,false).'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($inactive_items ,false).'</dd></dl>', array('controller'=>'items','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('escape' => false,'class'=>'no-under show pull-left mob-clr bot-space bot-mspace cur'));
				
				$class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) ? 'active' : null;
				echo $this->Html->link('<dl class="dc list users '.$class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'.__l('Admin Suspended').'">'.__l('Admin Suspended').'</dt><dd title="'.$this->Html->cInt($suspended_items ,false).'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($suspended_items ,false).'</dd></dl>', array('controller'=>'items','action'=>'index','filter_id' => ConstMoreAction::Suspend), array('escape' => false,'class'=>'no-under show pull-left mob-clr bot-space bot-mspace cur'));
				
				$class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) ? 'active' : null;
				echo $this->Html->link('<dl class="dc list users '.$class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'.__l('System Flagged').'">'.__l('System Flagged').'</dt><dd title="'.$this->Html->cInt($system_flagged ,false).'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($system_flagged ,false).'</dd></dl>', array('controller'=>'items','action'=>'index','filter_id' => ConstMoreAction::Flagged), array('escape' => false,'class'=>'no-under show pull-left mob-clr bot-space bot-mspace cur'));
				
				$class = (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'user-flag') ? 'active' : null;
				echo $this->Html->link('<dl class="dc list users '.$class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'.__l('User Flaged').'">'.__l('User Flaged').'</dt><dd title="'.$this->Html->cInt($user_flagged ,false).'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($user_flagged ,false).'</dd></dl>', array('controller'=>'items','action'=>'index','type' => 'user-flag'), array('escape' => false,'class'=>'no-under show pull-left mob-clr bot-space bot-mspace cur'));
				
				$class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstMoreAction::Featured) ? 'active' : null;
				echo $this->Html->link('<dl class="dc list users '.$class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'.__l('Featured').'">'.__l('Featured').'</dt><dd title="'.$this->Html->cInt($featured ,false).'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($featured ,false).'</dd></dl>', array('controller'=>'items','action'=>'index','filter_id' => ConstMoreAction::Featured), array('escape' => false,'class'=>'no-under show pull-left mob-clr bot-space bot-mspace cur'));
				
				$class = (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'total') ? 'active' : null;
				echo $this->Html->link('<dl class="dc list users '.$class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc"  title="'.__l('Total').'">'.__l('Total').'</dt><dd title="'.$this->Html->cInt($total_items ,false).'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt($total_items ,false).'</dd></dl>', array('controller'=>'items','action'=>'index','type' => 'total'), array('escape' => false,'class'=>'no-under show pull-left mob-clr bot-space bot-mspace cur'));
			?>
            </div>
			<div class="clearfix dc">
			<?php 
				echo $this->Form->create('Item', array('type' => 'get', 'class' => 'form-search bot-mspace big-input span', 'action'=>'index'));
				echo $this->Form->input('q', array('placeholder' => __l('Keyword'),'class'=>'span9 ver-mspace text-16','label' => false, 'maxlength' => '255'));
				echo $this->Form->submit(__l('Search'), array('class'=>'btn btn-large hor-mspace btn-primary textb text-16'));
				echo $this->Form->end(); 
			?>		
			</div>
			<?php echo $this->element('paging_counter'); ?>
			<div class="ver-space">
				<div id="active-users" class="tab-pane active in no-mar">
				<?php 
					echo $this->Form->create('Item' , array('class' => 'normal','action' => 'update'));  
					echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); 
				?>
					<div class="overflow-block">
					<?php
						$view_count_url = Router::url(array(
							'controller' => 'items',
							'action' => 'update_view_count',
						), true);
					?>
						<table id="js-expand-table"  class="table list no-round js-view-count-update {'model':'item','url':'<?php echo $this->Html->cInt($view_count_url, false); ?>'}" >
							<thead>
								<tr class=" well no-mar no-pad js-even">
									<th class="dc graydarkc sep-right" rowspan="2"><?php echo __l('Select');?></th>
									<th class="dl graydarkc sep-right" rowspan="2"><div class="js-pagination span6"><?php echo $this->Paginator->sort('title',__l('Title'));?></div></th>
									<th class="dl graydarkc sep-right" rowspan="2"><div class="js-pagination span6"><?php echo $this->Paginator->sort('address',__l('Address'));?></div></th>
									<th class="dl graydarkc sep-right" rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort('User.username',__l('Host'));?></div></th>	
									<th class="dc graydarkc sep-right sep-bot" colspan="3"><?php echo  __l('Bookings');  ?></th>
									<th class="dr graydarkc sep-right" rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort( 'revenue',__l('Revenue') . ' (' . Configure::read('site.currency') . ')');?></div></th>
									<th rowspan="2" class="dc graydarkc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort( 'is_approved',__l('Approved?')); ?></div></th>
								</tr>
								<tr class="js-even well no-mar no-pad">
									<th class="dc graydarkc sep-right"><?php echo  __l('Waiting for Acceptance'); ?></th>
									<th class="dc graydarkc sep-right"><?php echo  __l('Pipeline'); ?></th>
									<th class="dc graydarkc sep-right"><?php echo  __l('Successful'); ?></th>
								</tr>
							</thead>
							<tbody>
							<?php
								if (!empty($items)):
									$i = 0;
									foreach ($items as $item):
										$class = null;
										$active_class = '';
										if ($i++ % 2 == 0):
											$class = 'altrow';
										endif;
										if ($item['Item']['is_active']):
											$status_class = 'js-checkbox-active';
										else:
											$active_class = 'disable';
											$status_class = 'js-checkbox-inactive';
										endif;
										if ($item['Item']['is_approved']):
											$status_class = 'js-checkbox-active';
										else:
											$active_class = 'disable';
											$status_class = 'js-checkbox-inactive';
										endif;
										if($item['Item']['is_featured']):
											$status_class.= ' js-checkbox-featured';
										else:
											$status_class.= ' js-checkbox-notfeatured';
										endif;
										if($item['Item']['admin_suspend']):
											$status_class.= ' js-checkbox-suspended';
										else:
											$status_class.= ' js-checkbox-unsuspended';
										endif;
										if($item['Item']['is_system_flagged']):
											$status_class.= ' js-checkbox-flagged';
										else:
											$status_class.= ' js-checkbox-unflagged';
										endif;
										if(!empty($item['ItemFlag'])):
											$status_class.= ' js-checkbox-user-reported';
										else:
											$status_class.= ' js-checkbox-unreported';
										endif;
										if($item['User']['is_active']):
											$status_class.= ' js-checkbox-activeusers';
										else:
											$status_class.= ' js-checkbox-deactiveusers';
										endif;
										$is_seating_enable = false;										
										foreach ($item['CustomPricePerNight'] as $cppn){
											if(!empty($cppn['is_seating_selection'])){
												$is_seating_enable = true;
											}
										}
							?>
								<tr class="<?php echo $class.' '.$active_class;?> expand-row js-odd">
									<td class="<?php echo $class;?> select">
									<?php echo $this->Form->input('Item.'.$item['Item']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$item['Item']['id'], 'label' => "", 'before' => '<span class="show pull-left hor-smspace"><i class="icon-caret-down"></i></span>', 'class' => $status_class.' js-checkbox-list')); ?>
									</td>
									<td class="dl">
										<div class="clearfix">
										<?php
											$item['Attachment'][0] = !empty($item['Attachment'][0]) ? $item['Attachment'][0] : array();
											echo $this->Html->link($this->Html->showImage('Item', $item['Attachment'][0], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'],  'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false), 'class' => 'pull-left', 'escape' => false));
										?>
										<div class="htruncate js-bootstrap-tooltip span4 hor-space" title="<?php echo $this->Html->cText($item['Item']['title'],false);?>"><?php echo $this->Html->cText($item['Item']['title'],false);?></div>
										</div>
										
										<?php if($item['Item']['admin_suspend']): ?>
										<div class="clearfix">
											<span class="label suspended" title="<?php echo __l('Admin Suspended'); ?>"><?php echo __l('Admin Suspended'); ?></span>
										</div>
										<?php	
											endif;
											if($item['Item']['is_system_flagged']): 
										?>
										<div class="clearfix">
											<span class="label label-warning " title="<?php echo __l('System Flagged'); ?>"><?php echo __l('System Flagged'); ?></span>
										</div>
										<?php 
											endif;
											if($item['Item']['is_featured']==1): 
										?>
										<div class="clearfix">
											<span class="featured label" title="<?php echo __l('Featured'); ?>"><?php echo __l('Featured'); ?></span>
										</div>
										<?php
											endif;
											$user_flagged_count = count($item['ItemFlag']);
											if($user_flagged_count >0) {
										?>
										<div class ="user-flagged ">
											<?php echo $this->Html->link(__l('User Flagged') . ' (' . $user_flagged_count . ')', array('controller'=> 'item_flags', 'action' => 'index', 'item'=>$item['Item']['slug'], 'admin' => true), array('escape' => false,'class'=>'label label-important user-flagged','title'=>__l('User Flagged') . ' (' . $user_flagged_count . ')'));?>
										</div>
										<?php } ?> 
									</td>
									<td class="dl">
									<?php if(!empty($item['Country']['iso_alpha2'])): ?>
										<span class="flags flag-<?php echo $this->Html->cText(strtolower($item['Country']['iso_alpha2']), false); ?>" title ="<?php echo $this->Html->cText($item['Country']['name'], false); ?>"><?php echo $this->Html->cText($item['Country']['name'], false); ?></span>
									<?php endif; ?>
										<div class="htruncate js-bootstrap-tooltip span4" title="<?php echo $this->Html->cText($item['Item']['address'], false);?>"><?php echo $this->Html->cText($item['Item']['address']);?></div>
									</td>
									<td class="dl"><?php echo $this->Html->cText($item['User']['username']);?></td>
									<td class="dc"><?php echo $this->Html->cInt($item['Item']['sales_pending_count']);?></td>
									<td class="dc"><?php echo $this->Html->cInt($item['Item']['sales_pipeline_count']);?></td>
									<td class="dc"><?php echo $this->Html->cInt($item['Item']['sales_cleared_count']);?></td>
									<td class="dr site-amount"><?php echo $this->Html->cCurrency($item['Item']['revenue']);?></td>
									<td class="dc"><?php echo $this->Html->cBool($item['Item']['is_approved']);?></td>	
								</tr>
								<tr class="hide sep-bot sep-medium">
									<td colspan="9">
										<div class="top-space clearfix">
										<div class="clearfix activities-block">
											<div class="pull-right dropdown"> <a data-toggle="dropdown" class="dropdown-toggle btn btn-large text-14 textb graylighterc no-shad" title="Edit" href="#"><i class="icon-cog graydarkc  no-pad text-16"></i> <span class="caret"></span></a>
												<ul class="dropdown-menu arrow arrow-right">
												<?php if(empty($item['Item']['is_deleted'])):?>
													<li><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action' => 'edit', $item['Item']['id']), array('escape' => false,'class' => 'graydarkc edit js-edit', 'title' => __l('Edit')));?></li>
													<li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action' => 'delete', $item['Item']['id']), array('escape' => false,'class' => 'graydarkc delete js-delete', 'title' => sprintf(__l('Disappear %s from user side'), Configure::read('item.alt_name_for_item_singular_small'))));?></li>
													<?php if($item['Item']['is_system_flagged']):?>
														<?php if($item['User']['is_active']):?>
													<li><?php echo $this->Html->link('<i class="icon-remove-sign"></i>'.__l('Deactivate User'), array('controller' => 'users', 'action' => 'admin_update_status', $item['User']['id'], 'status' => 'deactivate'), array('escape' => false,'class' => 'graydarkc js-admin-update-item deactive-user', 'title' => __l('Deactivate user')));?></li>
														<?php else:?>
													<li><?php echo $this->Html->link('<i class="icon-ok-sign"></i>'.__l('Activate User'), array('controller' => 'users', 'action' => 'admin_update_status', $item['User']['id'], 'status' => 'activate'), array('escape' => false,'class' => 'graydarkc js-admin-update-item active-user', 'title' => __l('Activate user')));?></li>
														<?php endif;?>
													<?php endif;?>
													<?php if($item['Item']['is_featured']):?>
													<li><?php echo $this->Html->link('<i class="icon-remove-circle"></i>'.__l('Not Featured'), array('action' => 'admin_update_status', $item['Item']['id'], 'featured' => 'deactivate'), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-unfeatured not-featured', 'title' => __l('Not Featured')));?></li>
													<?php else:?>
													<li><?php echo $this->Html->link('<i class="icon-map-marker"></i>'.__l('Featured'), array('action' => 'admin_update_status', $item['Item']['id'], 'featured' => 'activate'), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-featured featured', 'title' => __l('Featured')));?></li>
													<?php endif;?>
													<?php if($item['Item']['is_system_flagged']):?>
													<li><?php echo $this->Html->link('<i class="icon-remove-circle"></i>'.__l('Clear system flag'), array('action' => 'admin_update_status', $item['Item']['id'], 'flag' => 'deactivate'), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-unflag clear-flag', 'title' => __l('Clear system flag')));?></li>
													<?php else:?>
													<li><?php echo $this->Html->link('<i class="icon-flag"></i>'.__l('Flag'), array('action' => 'admin_update_status', $item['Item']['id'], 'flag' => 'active'), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-flag flag', 'title' => __l('Flag')));?></li>
													<?php endif;?>
													<?php if($item['Item']['is_user_flagged']):?>
													<li><?php echo $this->Html->link('<i class="icon-flag"></i>'.__l('Clear user flag'), array('action' => 'admin_update_status', $item['Item']['id'], 'user_flag' => 'deactivate'), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-unflag clear-flag', 'title' => __l('Clear user flag')));?></li>
													<?php endif;?>
													<?php if($item['Item']['admin_suspend']):?>
													<li><?php echo $this->Html->link('<i class="icon-repeat"></i>'.__l('Unsuspend'), array('action' => 'admin_update_status', $item['Item']['id'], 'flag' => 'unsuspend'), array('escape' => false,'class' => 'graydarkc js-admin-update-item  js-unsuspend unsuspend', 'title' => __l('Unsuspend')));?></li>
													<?php else:?>
													<li><?php echo $this->Html->link('<i class="icon-off"></i>'.__l('Suspend'), array('action' => 'admin_update_status', $item['Item']['id'], 'flag' => 'suspend'), array('escape' => false,'class' => 'graydarkc js-admin-update-item js-suspend suspend', 'title' => __l('Suspend')));?></li>
													<?php endif;?>
												<?php else:?>
													<li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Permanent Delete'), array('action' => 'delete', $item['Item']['id']), array('escape' => false,'class' => 'graydarkc delete js-delete', 'title' => __l('Permanent Delete')));?></li>
												<?php endif; ?>
													<li><?php echo $this->Html->link((($item['Item']['is_approved']) ? '<i class="icon-thumbs-down"></i>'.__l('Disapprove') : '<i class="icon-thumbs-up"></i>'.__l('Approve')), array('action' => 'admin_update_status',  $item['Item']['id'], 'status' => (($item['Item']['is_approved']) ? 'disapproved' : 'approved')), array('title' => (($item['Item']['is_approved']) ? __l('Disapprove') : __l('Approve')), 'class' => (( $item['Item']['is_approved']) ? 'graydarkc js-admin-update-item js-pending pending' : 'graydarkc js-admin-update-item js-approve approve'), 'escape' => false)); ?></li>
													<?php if(isPluginEnabled('Seats') && !empty($is_seating_enable)):?>
													<li>
														<?php echo $this->Html->link('<i class="icon-screenshot"></i>'.__l('Partitions'), array('controller' => 'items', 'action' => 'partitions', 'slug' =>$item['Item']['slug'], 'admin' => true), array('title' => __l('Partitions'),'class' => 'graydarkc','escape'=>false)); ?>
													</li>
													<?php endif; ?>
												</ul>
											</div>
											<ul id="myTab3" class="nav nav-tabs top-smspace">
												<li class="active"><a href="#As-Price-<?php echo $this->Html->cInt($item['Item']['id'], false); ?>" data-toggle="tab"><?php echo __l('Overview');?></a> </li>                                
											</ul>
											<div class="sep-right sep-left sep-bot tab-round tab-content" id="myTabContent3">
												<div class="tab-pane space active" id="As-Price-<?php echo $this->Html->cInt($item['Item']['id'], false); ?>">
													<div class="row no-mar">
															<div class="span10">
																<h3 class="well space textb text-16 no-mar"><?php echo __l('Price'); ?></h3>
																<div class="clearfix ver-space bot-mspace">
																	<?php if(!empty($item['Item']['is_sell_ticket'])) { ?>
																	<dl class="list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('From'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($item['Item']['minimum_price']);?></dd>										
																	</dl>
																	<?php } else { ?>
																	<dl class="list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Per Hour'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($item['Item']['price_per_hour']);?></dd>										
																	</dl>
																	<dl class=" sep-left list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Per Day'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($item['Item']['price_per_day']);?></dd>										
																	</dl>
																	<dl class=" sep-left list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Per Week'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($item['Item']['price_per_week']);?></dd>
																	</dl>
																	<dl class="sep-left list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Per Month'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($item['Item']['price_per_month']);?></dd>
																	</dl>
																	<?php } ?>
																</div>
															</div>
															<div class="span10 hor-space">
																<h3 class="well space textb text-16 no-mar"><?php echo __l('Booking'); ?> </h3>
																<div class="clearfix ver-space bot-mspace">
																	<dl class="sep-right list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Waiting for Acceptance'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->cInt($item['Item']['sales_pending_count']);?></dd>										
																	</dl>
																	<dl class="sep-right list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Pipeline'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->cInt($item['Item']['sales_pipeline_count']);?></dd>
																	</dl>
																	<dl class="sep-right list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Lost'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->cInt($item['Item']['sales_lost_count']);?></dd>
																	</dl>
																	<dl class="list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Lost'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($item['Item']['sales_cleared_count']);?></dd>                                        
																	</dl>
																</div>
															</div>
													</div>
													<div class="row no-mar top-space">
															<div class="span10">
																<h3 class="well space textb text-16 no-mar"><?php echo __l('Revenue'); ?></h3>
																<div class="clearfix ver-space bot-mspace">
																	<dl class="sep-right list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Cleared'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($item['Item']['sales_cleared_amount']);?></dd>
																	</dl>
																	<dl class="sep-right list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Lost'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($item['Item']['sales_lost_amount']);?></dd>
																	</dl>
																	<dl class="list">
																		<dt class="pr hor-mspace text-11"><?php echo __l('Pipeline'); ?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($item['Item']['sales_pipeline_amount']);?></dd>                                        
																	</dl>
																</div>
															</div>
															<div class="span10 hor-space">
																<h3 class="well space textb text-16 no-mar"><?php echo __l('Reviews'); ?> </h3>
																<div class="clearfix ver-space bot-mspace">
																	<dl class="list sep-right">
																		<dt class="pr hor-mspace text-11" title ="<?php echo __l('Positive');?>"><?php echo __l('Positive');?></dt>
																		<dd class="textb text-16 graydarkc pr hor-mspace">
																			<?php echo !empty($item['Item']['positive_feedback_count'])?numbers_to_higher($item['Item']['positive_feedback_count']):'0'; ?>
																		</dd>
																	</dl>
																	<dl class=" list sep-right">
																		<dt class="pr hor-mspace text-11" title ="<?php echo __l('Negative');?>"><?php echo __l('Negative');?></dt>
																		<dd  class="textb text-16 graydarkc pr hor-mspace">
																			<?php echo !empty($item['Item']['positive_feedback_count'])?numbers_to_higher($item['Item']['item_feedback_count'] - $item['Item']['positive_feedback_count']):'0'; ?>
																		</dd>
																	</dl>
																	<dl class="list">
																		<dt class="pr hor-mspace text-11" title ="<?php echo __l('Success Rate');?>"><?php echo __l('Success Rate');?></dt>
																		<?php if(empty($item['Item']['item_feedback_count'])): ?>
																		<dd class="textb text-16 graydarkc pr hor-mspace" title="<?php  echo __l('No Bookings available'); ?>"><?php  echo __l('n/a'); ?></dd>
																		<?php else:?>
																		<dd class="textb text-16 graydarkc pr hor-mspace">
																		<?php
																		if(!empty($item['Item']['positive_feedback_count'])):
																			$positive = floor(($item['Item']['positive_feedback_count']/$item['Item']['item_feedback_count']) *100);
																			$negative = 100 - $positive;
																		else:
																			$positive = 0;
																			$negative = 100;
																		endif;
																		echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&amp;chf=bg,s,FFFFFF00', array('width'=>'50px','height'=>'50px','title' => $positive.'%'));
																		?>
																		</dd>
																		<?php endif; ?>
																	</dl>	
																</div>
															</div>
													</div>
												</div>							  
											</div>
										</div>
										<div class="clearfix details-block no-mar pull-right">
											<div class="thumb pull-left hor-space bot-mspace">
											<?php echo $this->Html->link($this->Html->showImage('Item', (!empty($item['Attachment'][0]) ? $item['Attachment'][0] : ''), array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'class' => 'img-polaroid', 'title' => $this->Html->cText($item['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false, 'class' => 'show'));?>
											</div>
											<div class="pull-left hor-space ver-mspace span-24 "> 
												<?php echo $this->Html->link($this->Html->cText($item['Item']['title'],false), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false, 'class' => 'span6 htruncate graydarkc text-24 textb mob-text-24  bot-mspace show'));?>
												<dl class=" clearfix ver-mspace dl-horizontal">		  
													<dt class=""><?php echo __l('Added On')?>  </dt>
													<dd class="graydarkc"><?php echo $this->Html->cDateTimeHighlight($item['Item']['created']); ?> </dd>
												</dl>
												<dl class=" clearfix ver-mspace dl-horizontal">
													<dt class=""><?php echo __l('Views'); ?> </dt>
													<dd class="graydarkc"><?php echo $this->Html->cInt($item['Item']['item_view_count']);?></dd>
												</dl>
												<?php if(isPluginEnabled('ItemFavorites')) : ?>
												<dl class=" clearfix ver-mspace dl-horizontal">
													<dt class=""><?php echo __l('Favorites');?></dt>
													<dd class="graydarkc"><?php echo $this->Html->cInt($item['Item']['item_favorite_count']);?></dd>
												</dl>
												<?php endif;?>
												<?php if(isPluginEnabled('PrpertyFlags')) : ?>
												<dl class=" clearfix ver-mspace dl-horizontal">
													<dt class=""><?php echo __l('Flags');?></dt>
													<dd class="graydarkc"><?php echo $this->Html->cInt($item['Item']['item_flag_count']);?></dd>				
												</dl>
												<?php endif;?>
												<dl class=" clearfix ver-mspace dl-horizontal">
													<dt class=""><?php echo __l('IP');?></dt>
													<dd class="graydarkc">
													<?php if(!empty($item['Ip']['ip'])): ?>
														<?php echo $this->Html->link($item['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $item['Ip']['ip'], 'admin' => false), array('class' => 'js-no-pjax span3 htruncate', 'target' => '_blank', 'title' => 'whois '.$item['Ip']['host'], 'escape' => false)); ?>
														<p>
													<?php
														if(!empty($item['Ip']['Country'])):
													?>
															<span class="flags flag-<?php echo $this->Html->cText(strtolower($item['Ip']['Country']['iso_alpha2']), false); ?>" title ="<?php echo $this->Html->cText($item['Ip']['Country']['name'], false); ?>">
															<?php echo $this->Html->cText($item['Ip']['Country']['name'], false); ?>
															</span>
													<?php
														endif;
														if(!empty($item['Ip']['City'])):
													?>
															<span><?php echo $this->Html->cText($item['Ip']['City']['name'], false); ?></span>
													<?php endif; ?>
														</p>
													<?php else: ?>
													<?php echo __l('N/A'); ?>
													<?php endif; ?>
													</dd>
												</dl>
												<dl class=" clearfix ver-mspace dl-horizontal">
													<dt class=""><?php echo Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Fee Paid'); ?></dt>
													<dd class="graydarkc"><?php if($item['Item']['is_paid']) { echo __l('Yes'); } else { echo __l('No');}?></dd>							  
												</dl>		  
											</div>
										</div>
										</div>
									</td>
								</tr>
							<?php
									endforeach;
								else:
							?>
								<tr class="js-odd">
									<td colspan="51"><div class="space dc grayc"><p class="ver-mspace top-space text-16 "><?php echo sprintf(__l('No %s available'), Configure::read('item.alt_name_for_item_plural_caps'));?></p></div></td>
								</tr>
							<?php
								endif;
							?>
							</tbody>
						</table>
						<?php
							if (!empty($items)) :
						?>
						<div class="admin-select-block ver-mspace pull-left mob-clr dc">
							<div class="span top-mspace">
								<span class="graydarkc"><?php echo __l('Select:'); ?></span>
								<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-select js-no-pjax {"checked":"js-checkbox-list"} hor-smspace grayc','title' => __l('All'))); ?>
								<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-select js-no-pjax {"unchecked":"js-checkbox-list"} hor-smspace grayc','title' => __l('None'))); ?>
								<?php echo $this->Html->link(__l('Admin Suspended'), '#', array('class' => 'js-select js-no-pjax	{"checked":"js-checkbox-suspended","unchecked":"js-checkbox-unsuspended"} hor-smspace grayc', 'title' => __l('Admin Suspended'))); ?>
								<?php echo $this->Html->link(__l('Featured'), '#', array('class' => 'js-select js-no-pjax {"checked":"js-checkbox-featured","unchecked":"js-checkbox-notfeatured"} hor-smspace grayc', 'title' => __l('Featured'))); ?>
								<?php echo $this->Html->link(__l('Flagged'), '#', array('class' => 'js-select js-no-pjax {"checked":"js-checkbox-flagged","unchecked":"js-checkbox-unflagged"} hor-smspace grayc', 'title' => __l('Flagged'))); ?>
								<?php echo $this->Html->link(__l('Unflagged'), '#', array('class' => 'js-select js-no-pjax {"checked":"js-checkbox-unflagged","unchecked":"js-checkbox-flagged"} hor-smspace grayc', 'title' => __l('Unflagged'))); ?>    
							</div>
							<?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit js-no-pjax span5', 'label' => false, 'div'=>false, 'empty' => __l('-- More actions --'))); ?>
						</div>
						<div class="js-pagination pagination pull-right no-mar mob-clr dc">
							<?php echo $this->element('paging_links'); ?>
						</div>
						<div class="hide">
							<?php echo $this->Form->submit(__l('Submit'));  ?>
						</div>
					<?php
						endif;
						echo $this->Form->end();
					?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>