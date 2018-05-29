<div class="row-fluid ver-space js-cache-load-admin-user-activities">
 <?php $i=0; ?>
          <section class="span24 space" >
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#registration','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#a47ae2'}"><?php echo $user_reg_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph1c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_user_reg;?>"><?php echo $total_user_reg;?> </div>
								<div class="text-12 pull-right <?php if ($user_reg_data_per>0) {?> greenc <?php } else if($user_reg_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $user_reg_data_per;?>%</span>
									<?php if (!empty($user_reg_data_per)) {?>
										<i class="<?php if ($user_reg_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo __l('User Registration'); ?></div>

						</div>
					</div>
				 </div>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#userlogins','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#4986e7'}"><?php echo $user_log_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph2c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_user_login;?>"><?php echo $total_user_login;?> </div>
								<div class="text-12 pull-right <?php if ($user_log_data_per>0) {?> greenc <?php } else if($user_log_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $user_log_data_per;?>%</span>
									<?php if (!empty($user_log_data_per)) {?>
										<i class="<?php if ($user_log_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>

							</div> 
							<div class="span"><?php echo __l('User Logins'); ?></div>

						</div>
					</div>
				 </div>
				 <?php if (isPluginEnabled('SocialMarketing')) {?>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#userfollower','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#f691b2'}"><?php echo $user_follow_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph3c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_user_follow;?>"><?php echo $total_user_follow;?> </div>
								<div class="text-12 pull-right <?php if ($user_follow_data_per>0) {?> greenc <?php } else if($user_follow_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $user_follow_data_per;?>%</span>
									<?php if (!empty($user_follow_data_per)) {?>
										<i class="<?php if ($user_follow_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo __l('User Followers'); ?></div>
						</div>
					</div>
				 </div>
				 <?php }?>
				 <?php if (isPluginEnabled('Items')) { ?>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#items','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#cd74e6'}"><?php echo $items_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph4c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_items;?>"><?php echo $total_items;?> </div>
								<div class="text-12 pull-right <?php if ($items_data_per>0) {?> greenc <?php } else if($items_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $items_data_per;?>%</span>
									<?php if (!empty($items_data_per)) {?>
										<i class="<?php if ($items_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo Configure::read('item.alt_name_for_item_plural_caps'); ?></div>
						</div>
					</div>
				 </div>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#overview','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#FF4D4D'}"><?php echo $item_views_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph4c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_item_views;?>"><?php echo $total_item_views;?> </div>
								<div class="text-12 pull-right <?php if ($item_views_data_per>0) {?> greenc <?php } else if($item_views_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $item_views_data_per;?>%</span>
									<?php if (!empty($item_views_data_per)) {?>
										<i class="<?php if ($item_views_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Views'); ?></div>
						</div>
					</div>
				 </div>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#booking_line_chart','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#CCCC00'}"><?php echo $bookings_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph4c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_bookings;?>"><?php echo $total_bookings;?> </div>
								<div class="text-12 pull-right <?php if ($bookings_data_per>0) {?> greenc <?php } else if($bookings_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $bookings_data_per;?>%</span>
									<?php if (!empty($bookings_data_per)) {?>
										<i class="<?php if ($bookings_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo __l('Bookings'); ?></div>
						</div>
					</div>
				 </div>
				  <?php }?>
				 <?php if (isPluginEnabled('Items')) { ?>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#userfollower','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#ff7537'}"><?php echo $item_flag_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph5c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_item_flag;?>"><?php echo $total_item_flag;?> </div>
								<div class="text-12 pull-right <?php if ($item_flag_data_per>0) {?> greenc <?php } else if($item_flag_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $item_flag_data_per;?>%</span>
									<?php if (!empty($item_flag_data_per)) {?>
										<i class="<?php if ($item_flag_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Flags'); ?></div>
						</div>
					</div>
				 </div>
				  <?php }?>
				 <?php if (isPluginEnabled('ItemFavorites')) { ?>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#itemFavorites','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#d06b64'}"><?php echo $item_favourite_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph6c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_item_favourite;?>"><?php echo $total_item_favourite;?> </div>
								<div class="text-12 pull-right <?php if ($item_favourite_data_per>0) {?> greenc <?php } else if($item_favourite_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $item_favourite_data_per;?>%</span>
									<?php if (!empty($item_favourite_data_per)) {?>
										<i class="<?php if ($item_favourite_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Favorites'); ?></div>
						</div>
					</div>
				 </div>
				  <?php }?>
				 <?php if (isPluginEnabled('Requests')) { ?>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#requests','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#42d692'}"><?php echo $requests_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph7c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_requests;?>"><?php echo $total_requests;?> </div>
								<div class="text-12 pull-right <?php if ($requests_data_per>0) {?> greenc <?php } else if($requests_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $requests_data_per;?>%</span>
									<?php if (!empty($requests_data_per)) {?>
										<i class="<?php if ($requests_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo __l('Requests'); ?></div>
						</div>
					</div>
				 </div>
				  <?php }?>
				  <?php if (isPluginEnabled('RequestFavorites')) { ?>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#itemFavorites','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#cc3300'}"><?php echo $request_favorite_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph6c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_request_favorite;?>"><?php echo $total_request_favorite;?> </div>
								<div class="text-12 pull-right <?php if ($request_favorite_data_per>0) {?> greenc <?php } else if($request_favorite_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $request_favorite_data_per;?>%</span>
									<?php if (!empty($request_favorite_data_per)) {?>
										<i class="<?php if ($request_favorite_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo __l('Request Favorites'); ?></div>
						</div>
					</div>
				 </div>
				  <?php }?>
				 <?php if (isPluginEnabled('RequestFlags')) { ?>
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#requestFlags','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#16a765'}"><?php echo $request_flag_data;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph8c htruncate span10 js-bootstrap-tooltip" title="<?php echo !empty($total_request_flag)?$total_request_flag:'';?>"><?php echo !empty($total_request_flag)?$total_request_flag:'0';?></div>
								<div class="text-12 pull-right <?php if ($request_flag_data_per>0) {?> greenc <?php } else if($request_flag_data_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $request_flag_data_per;?>%</span>
									<?php if (!empty($request_flag_data_per)) {?>
										<i class="<?php if ($request_flag_data_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div> 
							<div class="span"><?php echo __l('Request Flags'); ?></div>
						</div>
					</div>
				 </div>
				  <?php }?>
				 
				 <div class="span8 sep <?php if($i%3==0) {?> no-mar <?php } ?>"> <?php $i++;?>
					<div class="pull-right space ">
					<?php if (isPluginEnabled('Insights')): ?>
						<?php echo $this->Html->link('<i class="icon-share-alt blackc"></i>', array('controller' => 'insights','action' => 'index','#revenue_line_chart','admin'=>true),array('escape'=> false));?>
					<?php endif; ?>
					</div>
					<div class="clearfix span23 mob-clr">
						<div class="span7 hidden-block">
							<span class="js-sparkline-chart {'colour':'#ffad46'}"><?php echo $revenue;?></span>
						</div>
						<div class="span17">
							<div class="span">
								<div class="text-24 pull-left graph12c htruncate span10 js-bootstrap-tooltip" title="<?php echo $total_revenue;?>"><?php echo $total_revenue;?></div>
								<div class="text-12 pull-right <?php if ($rev_per>0) {?> greenc <?php } else if($rev_per == 0) { ?> grayc <?php } else { ?> orangec <?php } ?>">
									<span class="text-16  pull-left"><?php echo $rev_per;?>%</span>
									<?php if (!empty($rev_per)) {?>
										<i class="<?php if ($rev_per>0) {?> icon-arrow-up  <?php } else { ?> icon-arrow-down <?php } ?> text-24  pull-left"></i>
									<?php } ?>
								</div>
							</div>
								<div class="span"><?php echo __l('Revenue').' ('.Configure::read('site.currency').')'; ?></div>
						</div>
					</div>
				 </div>
          </section>
        </div>