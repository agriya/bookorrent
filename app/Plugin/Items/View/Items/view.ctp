<?php 
    $this->loadHelper('Embed');
    $lat =$item['Item']['latitude']; 
    $lng = $item['Item']['longitude'];
	$hash = !empty($this->request->params['pass'][1]) ? $this->request->params['pass'][1] : '';
	$salt = !empty($this->request->params['pass'][2]) ? $this->request->params['pass'][2] : '';
?>
<div class="clearfix js-item-view" data-item-id="<?php echo $this->Html->cInt($item['Item']['id'], false); ?>">
	<?php 
		$current_user_details = array(
			'username' => !empty($item['User']['username'])?$item['User']['username']:'',
			'role_id' => !empty($item['User']['role_id'])?$item['User']['role_id']:'',
			'id' => !empty($item['User']['id'])?$item['User']['id']:'',
			'facebook_user_id' => !empty($item['User']['facebook_user_id'])?$item['User']['facebook_user_id']:'',
			'user_avatar_source_id' =>  !empty($item['User']['user_avatar_source_id'])?$item['User']['user_avatar_source_id']:'',
			'twitter_avatar_url' => !empty($item['User']['twitter_avatar_url'])?$item['User']['twitter_avatar_url']:''
		);
		$current_user_details['UserAvatar'] = !empty($item['User']['attachment_id'])? array('id' => $item['User']['attachment_id']):array();
		$label = "";
		$price = 0;
		if($item['Item']['is_people_can_book_my_time'] == 1) {
			if(!empty($item['Item']['custom_source_id']) && $item['Item']['custom_source_id'] == ConstCustomSource::Hour) {
				$label = __l('Per Hour');
			} else if(!empty($item['Item']['custom_source_id']) && $item['Item']['custom_source_id'] == ConstCustomSource::Day) {
				$label = __l('Per Day');
			} else if(!empty($item['Item']['custom_source_id']) && $item['Item']['custom_source_id'] == ConstCustomSource::Week) {
				$label = __l('Per Week');
			} else if(!empty($item['Item']['custom_source_id']) && $item['Item']['custom_source_id'] == ConstCustomSource::Month) {
				$label = __l('Per Month');
			}
		} else if($item['Item']['is_sell_ticket'] == 1) {
			$label = __l('From');
		}	
			$price = $item['Item']['minimum_price'];
		$btn_label = __l('Book It');
		if(empty($item['Item']['is_have_definite_time'])) {
			$btn_label = __l('Request');
		}
	?>
	<div class="user-affix clearfix affix-top z-top hidden-sm" data-offset-top="400" data-spy="affix">
		<div class="affix-bg clearfix mspace">
			<div class="span11 no-mar">
				<div class="pull-left">
					<?php echo $this->Html->getUserAvatar($current_user_details, 'medium_thumb', true); ?>
				</div>
				<h2 class="graydarkc pull-left text-24 hor-space">
				  <?php echo $this->Html->link($this->Html->cText($item['Item']['title'], false), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('class'=>"graydarkc top-smspace show span3 htruncate js-bootstrap-tooltip", 'data-placement' => 'bottom', 'title' => $this->Html->cText($item['Item']['title'], false), 'escape' => false)); ?>
				</h2>
			</div>
			<div class="pull-right clearfix span12">
				<div class="pull-left right-space graydarkc">
					<?php if(!empty($item['Item']['is_have_definite_time'])) { ?>
					<?php if(!empty($price) && $price > 0) { ?>
					<span class="textb text-24 show pull-left top-smspace right-space"><?php echo $this->Html->siteCurrencyFormat($price);?></span> 
					<span class="text-11 show span top-space top-smspace"><?php echo $label ;?></span>
					<?php } else { ?>
					<span class="textb text-24 show pull-left top-smspace right-space"><?php echo  __l('Free'); ?></span> 
					<?php } ?>
					<?php } ?>
				</div>
				<div class="pull-left no-mar">
					<div class="pull-right right-space right-mspace">
						<?php if($this->Auth->user('id')==$item['Item']['user_id']): ?>
							<a href="#hostpanel" data-trigger="#hostpanel" class="show btn span3 btn-large btn-primary textb js-bookitaffix js-no-pjax" title="<?php echo __l('Host Panel'); ?>">  <?php echo __l('Host Panel'); ?></a>
						<?php else: ?>    
							<a href="#bookit" data-trigger="#bookit" data-calendar="<?php echo (!empty($item['Item']['is_sell_ticket']) ? 1 : 0); ?>" class="show btn span5 btn-large no-mar btn-primary textb js-bookitaffix js-no-pjax" title="<?php echo $btn_label; ?>">  <?php echo $btn_label; ?></a>
						<?php endif; ?>
					</div>
					<span class="small-screen pa hide"><i class="icon-remove-circle text-24 cur js-expand"></i></span>
				</div> 
			</div>
		</div>
	</div>
	<div class="container">
		<div class="top-content pr mob-ps">
			<div class="banner-content-trans-bg pa mspace mob-ps span11">
				<div class="clearfix bot-space">
					<?php 
						if(isPluginEnabled('ItemFavorites')) :
							if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))):
					?>
					<div class="alpuf-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide">
						<span class="dc">
							<?php echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'item_favorites', 'action'=>'delete', $item['Item']['slug'], 'type' => 'view'), array('escape' => false ,'class' => 'js-like un-like show span top-smspace js-no-pjax', 'title' => __l('Unlike'))); ?>
						</span>
					</div>
					<div class="alpf-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide">
						<span class="dc">
							<?php echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'item_favorites', 'action' => 'add', $item['Item']['slug'], 'type' => 'view'), array('escape' => false ,'title' => __l('Like'),'escape' => false ,'class' =>'js-like like show span top-smspace whitec no-under js-no-pjax')); ?>
						</span>
					</div>
					<div class='blpf-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide'>
						<span class="dc">
							<?php echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f='. $this->request->url), array('title' => __l('Like'),'escape' => false ,'class' =>'like show span top-smspace whitec no-under ')); ?>
						</span>
					</div>
					<?php 
							else: 
					?>
					<span class="dc">
					<?php
								if($this->Auth->sessionValid()):
									if(!empty($item['ItemFavorite'])):
										foreach($item['ItemFavorite'] as $favorite):
											if($item['Item']['id'] == $favorite['item_id'] && $item['Item']['user_id'] != $this->Auth->user('id')):
												echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'item_favorites', 'action'=>'delete', $item['Item']['slug'], 'type' => 'view'), array('escape' => false ,'class' => 'js-like un-like show span top-smspace no-under js-no-pjax', 'title' => __l('Unlike')));
											endif;
										endforeach;
									else:
										if( $item['Item']['user_id'] != $this->Auth->user('id')):
											echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'item_favorites', 'action' => 'add', $item['Item']['slug'], 'type' => 'view'), array('title' => __l('Like'),'escape' => false ,'class' =>'js-like js-no-pjax like show span top-smspace whitec no-under'));
										endif;
									endif;
								else:
									echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f='. $this->request->url), array('title' => __l('Like'),'escape' => false ,'class' =>'like show span top-smspace whitec no-under'));
								endif;
					?>
					</span>
					<?php 
							endif;
						endif; 
					?>
					<h2 class="pull-left right-mspace span8 text-24 htruncate whitec">
						<?php echo $this->Html->link($this->Html->cText($item['Item']['title'], false), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('class'=>"whitec", 'title'=>$this->Html->cText($item['Item']['title'], false),'escape' => false)); ?>
					</h2>
					<?php if($item['Item']['is_featured']):?>
					<span class="label featured pull-right mob-inline top-mspace"> <?php echo __l('Featured'); ?></span>
					<?php endif; ?>
				</div>
				<div class="clearfix">
					<?php 
						if(isPluginEnabled('ItemFlags')):
							if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))):
					?>
					<div class="alvfp-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide">
						<?php echo $this->Html->link('<i class="icon-flag pull-left text-18 no-mar right-space whitec"></i>', array('controller' => 'item_flags', 'action' => 'add', $item['Item']['id']), array('title' => __l('Flag this') . ' ' . Configure::read('item.alt_name_for_item_singular_small'),'escape' => false ,'class' =>'flag dr js-no-pjax', 'data-toggle' => 'modal', 'data-target' => '#js-ajax-modal')); ?>
					</div>
					<div class="blvfp-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide">
						<?php echo $this->Html->link('<i class="icon-flag pull-left text-18 no-mar right-space whitec"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f=item/' . $item['Item']['slug'], 'admin' => false), array( 'escape' => false,'title' => __l('Flag this') . ' ' . Configure::read('item.alt_name_for_item_singular_small'), 'class' => 'flag dr')); ?>
					</div>
					<?php 
							else: 
								if ($this->Auth->sessionValid()):
									if ($item['Item']['user_id'] != $this->Auth->user('id')):
										echo $this->Html->link('<i class="icon-flag pull-left text-18 no-mar right-space whitec"></i>', array('controller' => 'item_flags', 'action' => 'add', $item['Item']['id']), array('data-toggle' => 'modal', 'data-target' => '#js-ajax-modal','class'=>'js-no-pjax', 'escape' => false, 'title' => __l('Flag this') . ' ' . Configure::read('item.alt_name_for_item_singular_small')));
									endif;
								else :
									echo $this->Html->link('<i class="icon-flag pull-left text-18 no-mar right-space whitec"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f=item/' . $item['Item']['slug'], 'admin' => false), array('escape' => false,'title' => __l('Flag this') . ' ' . Configure::read('item.alt_name_for_item_singular_small'), 'class' => 'flag dr'));
								endif;
							endif;
						endif; 
					?>
					<?php if(!empty($item['Country']['iso_alpha2'])): ?>
					<span class="top-smspace flags flag-<?php echo $this->Html->cText(strtolower($item['Country']['iso_alpha2']), false); ?>" title ="<?php echo $item['Country']['name']; ?>"><?php echo $item['Country']['name']; ?></span>
					<?php endif; ?>
					<p class="htruncate clearfix js-bootstrap-tooltip span8 whitec no-mar dl" title="<?php echo $this->Html->cHtml($item['Item']['address'], false);?>"><?php echo $this->Html->cText($item['Item']['address']) ?></p>
					<p class="htruncate clearfix js-bootstrap-tooltip span8 whitec no-mar dl">
						<?php 
							echo $this->Html->link($this->Html->cText($item['Category']['ParentCategory']['name'], false), array('controller' => 'items', 'action' => 'index', 'category' => $item['Category']['ParentCategory']['slug'], 'admin' => false), array('class'=>"whitec", 'title'=>$this->Html->cText($item['Category']['ParentCategory']['name'], false),'escape' => false)); 
							echo ' / ';
							echo $this->Html->link($this->Html->cText($item['Category']['name'], false), array('controller' => 'items', 'action' => 'index', 'category' => $item['Category']['slug'], 'admin' => false), array('class'=>"whitec", 'title'=>$this->Html->cText($item['Category']['name'], false),'escape' => false)); 
						?>
					</p>
					<div class="clearfix pull-right">
						<?php 
							if(isset($share_url)){
								echo $this->Html->link('<i class="icon-share"></i>', $share_url, array('title'=>__l('Share'), 'escape' => false, 'class' => 'btn btn-small js-bootstrap-tooltip pull-right', 'target' => '_blank')); 
							}
						?>
					</div>
				</div>
			</div>
			<div class="banner-content-trans-bg pa mspace span7 dc z-top price-section mob-ps span-eight">
				<div class="row no-mar">
					<?php 
						if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))):
					?>
					<div class="al-php-<?php echo $this->Html->cInt($item['Item']['id'], false); ?> hide">
						<div class="pull-right dropdown mob-clr">
							<?php echo $this->Html->link(__l('Host Panel'), array('controller' => 'items', 'action' => 'bookit', $item['Item']['slug']), array('title' => __l('Host Panel'),'id'=>'hostpanel', "data-trigger"=>"#hostpanel_response",'class'=>"js-no-pjax show dropdown-toggle btn span6 top-mspace btn-large btn-primary text-18 textb",'data-toggle'=>'dropdown'));?>
							<ul class="span19 unstyled dropdown-menu no-mar arrow arrow-right book-it-drop">
								<li class="book-it-inner js-pending-list space no-mar clearfix">
									<div class="clearfix host-panel-block" id="hostpanel_response">
										<!-- Bookit content from ajax -->
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="al-pbi-<?php echo $this->Html->cInt($item['Item']['id'], false); ?> hide">
						<?php if(!empty($item['Item']['is_have_definite_time'])) { ?>
						<div class="whitec">
							<?php if(!empty($price) && $price > 0) { ?>
								<span class="textb text-24"><?php echo $this->Html->siteCurrencyFormat($price);?></span> 
								<span class="text-11"><?php echo $label;?> 
									<?php if($item['Item']['is_people_can_book_my_time'] == 1 && $item['CustomPricePerNight'][0]['min_hours'] > 0) { ?>
										<i class="icon-info-sign js-bootstrap-tooltip" title = "<?php echo __l('Min Hours').': '.$item['CustomPricePerNight'][0]['min_hours'];?>"></i>
									<?php } ?>
								</span>
							<?php } else { ?>
								<span class="textb text-24"><?php echo __l('Free'); ?></span> 
							<?php } ?>
						</div>
						<?php } ?>
						<div class="pull-right dropdown mob-clr">
							<?php echo $this->Html->link($btn_label, array('controller' => 'items', 'action' => 'bookit', $item['Item']['slug'],$hash,$salt), array("data-calendar" => (!empty($item['Item']['is_sell_ticket']) ? 1 : 0), "title" => $btn_label, "id" => "bookit", "data-trigger" => "#bookit_response", 'class'=>"js-no-pjax show dropdown-toggle btn span6 top-mspace btn-large btn-primary text-18 textb", "data-toggle" => "dropdown"));?>
							<ul class="unstyled dropdown-menu no-mar js-booking-block arrow arrow-right book-it-drop">
								<li class="book-it-inner js-pending-list space no-mar clearfix">
									<div class="" id="bookit_response">
										<!-- Bookit content from ajax -->
									</div>
								</li>
							</ul>
						</div>
					</div>
					<?php 
						else: 
							if($this->Auth->user('id')==$item['Item']['user_id']): 
					?>
					<div class="pull-right dropdown mob-clr">
						<?php echo $this->Html->link(__l('Host Panel'), array('controller' => 'items', 'action' => 'bookit', $item['Item']['slug']), array('title' => __l('Host Panel'),'id'=>'hostpanel', "data-trigger"=>"#hostpanel_response",'class'=>"js-no-pjax show dropdown-toggle btn span6 top-mspace btn-large btn-primary text-18 textb",'data-toggle'=>'dropdown'));?>
						<ul class="span19 unstyled dropdown-menu no-mar arrow arrow-right book-it-drop">
							<li class="book-it-inner js-pending-list space no-mar clearfix">
								<div class="clearfix host-panel-block" id="hostpanel_response">
									<!-- Bookit content from ajax -->
								</div>
							</li>
						</ul>
					</div>
					<?php 
							else:
					?>
					<?php if(!empty($item['Item']['is_have_definite_time'])) { 
					?>
					<div class="whitec">
						<?php if(!empty($price) && $price > 0) { ?>
							<span class="textb text-24"><?php echo $this->Html->siteCurrencyFormat($price);?></span> 
							<span class="text-11"><?php echo $label;?> 
								<?php if($item['Item']['is_people_can_book_my_time'] == 1 && $item['CustomPricePerNight'][0]['min_hours'] > 0) { ?>
									<i class="icon-info-sign js-bootstrap-tooltip" title = "<?php echo __l('Min Hours').': '.$item['CustomPricePerNight'][0]['min_hours'];?>"></i>
								<?php } ?>
							</span>
						<?php } else { ?>
							<span class="textb text-24"><?php echo __l('Free'); ?></span> 
						<?php } ?>
					</div>
					<?php } ?>
					<div class="pull-right dropdown mob-clr">
						<?php echo $this->Html->link($btn_label, array('controller' => 'items', 'action' => 'bookit', $item['Item']['slug'],$hash,$salt), array("data-calendar" => (!empty($item['Item']['is_sell_ticket']) ? 1 : 0), "title" => $btn_label, "id" => "bookit", "data-trigger" => "#bookit_response", "class" => "js-no-pjax show dropdown-toggle btn span6 top-mspace btn-large btn-primary text-18 textb", "data-toggle" => "dropdown")); ?>
						<ul class="unstyled dropdown-menu no-mar arrow arrow-right book-it-drop">
							<li class="book-it-inner row js-pending-list space no-mar clearfix">
								<div class="" id="bookit_response">
									<!-- Bookit content from ajax -->
								</div>
							</li>
						</ul>
					</div>
					<?php 
							endif; 
						endif;
					?>
				</div>
			</div>
			<?php if (!empty($item['Attachment'])) { ?>
				<div class="carousel slide pr no-mar" id="myCarousel">
					<div class="js-expand carousel-inner cur">
						<div class="expand-circle">
							<span class="show"><i class="icon-resize-full text-18 whitec no-pad cur show"></i></span>
							<p class="full-screen"><?php echo __l('Go full screen');?></p>
						</div>
						<?php
							$ci = 1;
							foreach($item['Attachment'] as $attachment):
						?>
							<div class="item <?php if($ci == 1) { ?> active <?php } ?>">
								<?php $lowResImage = getImageUrl('Item', $attachment, array('dimension' => 'normal_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false), 'full_url' => true)); ?>
								<?php $highResImage = getImageUrl('Item', $attachment, array('dimension' => 'original', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false), 'full_url' => true)); ?>
								<div class="fill bg-image" style="background:url(<?php echo $lowResImage; ?>) no-repeat center;background-size:cover;" data-high_res_image="<?php echo $highResImage; ?>"></div>
							</div>
						<?php
							$ci++;
							endforeach;
						?>
					</div>
					<div class="clearfix controls hide">
						<div class="carousel-indicators carousel-linked-nav carousel-thumbnails">
							<div class="thumb-box">
								<ol class="unstyled pr no-mar clearfix">
									<?php
										$ci = 1;
										foreach($item['Attachment'] as $attachment):
										if(empty($attachment['description'])) {
											$caption = $this->Html->cText($item['Item']['title'], false);
										} else {
											$caption = $this->Html->cText($attachment['description'], false);
										}
									?>
										<li class="cur<?php if($ci == 1) { ?> active <?php } ?>" data-slide-to="<?php echo $ci; ?>" data-target="#myCarousel"><?php echo $this->Html->showImage('Item', $attachment, array('dimension' => 'iphone_small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $caption, 'width' => 50, 'height' => 50)); ?></li>
									<?php
										$ci++;
										endforeach;
									?>
								</ol>
							</div>
						</div>
						<a data-slide="prev" href="#" class="left js-left-carousel carousel-control thumb-control">&lsaquo;</a>
						<span class="js-cover cur image-size-contain js-bootstrap-tooltip" title="<?php echo __l('Fit Screen/Show All'); ?>"> <i class="icon-resize-small js-icon-class whitec cur"></i> </span>
						<a data-slide="next" href="#" class="right js-right-carousel carousel-control thumb-control">&rsaquo;</a>
					</div>
					<a data-slide="prev" href="#myCarousel" class="left carousel-control">&lsaquo;</a>
					<a data-slide="next" href="#myCarousel" class="right carousel-control">&rsaquo;</a>
				</div>
			<?php } else { ?>
				<div class="carousel slide pr no-mar" id="myCarousel">
					<div class="carousel slide no-mar">
						<div class="carousel-inner">
							<div class="item active">
								<?php echo $this->Html->showImage('Item', array(), array('dimension' => 'very_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))); ?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="row no-mar">
				<?php
					$view_count_url = Router::url(array(
					'controller' => 'items',
					'action' => 'update_view_count',
					), true);
				?>
				<div class="big-thumb prop-owner pa img-polaroid mob-ps">
					<?php echo $this->Html->getUserAvatar($current_user_details, 'small_big_thumb', true); ?>
				</div>
				<div class="offset5 span tab-right mob-clr clearfix">
					<div class="top-mspace top-space clearfix js-view-count-update {'model':'item','url':'<?php echo $this->Html->cText($view_count_url, false); ?>'}">
						<?php if(!empty($this->request->params['named']['latitude']) && !empty($this->request->params['named']['longitude'])) { ?>
						<dl class="dc list">
							<dt class="pr hor-mspace text-11"><?php echo __l('Distance (km)'    );?></dt>
							<dd class="textb text-20 graydarkc pr hor-mspace"><?php echo $this->Html->cInt($this->Html->distance($this->request->params['named']['latitude'],$this->request->params['named']['longitude'],$item['Item']['latitude'],$item['Item']['longitude'],'K')); ?></dd>
						</dl>
						<?php } ?>
						<dl class="dc sep-right list">
							<dt class="pr hor-mspace text-11"><?php echo __l('Views');?></dt>
							<dd class="textb text-20 graydarkc pr hor-mspace js-view-count-item-id js-view-count-item-id-<?php echo $this->Html->cInt($item['Item']['id'], false); ?> {'id':'<?php echo $this->Html->cInt($item['Item']['id'], false); ?>'}"><?php echo numbers_to_higher($item['Item']['item_view_count']); ?></dd>
						</dl>
						<dl class="dc sep-right list">
							<dt class="pr hor-smspace text-11"><?php echo __l('Positive');?></dt>
							<dd class="textb text-20 graydarkc pr hor-mspace"> <?php  echo numbers_to_higher($item['Item']['positive_feedback_count']); ?></dd>
						</dl>
						<dl class="dc sep-right list">
							<dt class="pr hor-mspace text-11"><?php echo __l('Negative');?></dt>
							<dd class="textb text-20 graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['item_feedback_count'] - $item['Item']['positive_feedback_count']); ?></dd>
						</dl>
						<dl class="dc sep-right list">
							<dt class="pr hor-mspace text-11"><?php echo __l('Success Rate');?></dt>
							<?php if(empty($item['Item']['item_feedback_count'])): ?>
							<dd class="textb text-20 graydarkc pr hor-mspace" title="<?php  echo __l('No Bookings available'); ?>"><?php  echo __l('n/a'); ?></dd>
							<?php else:?>
							<dd class="textb text-20 graydarkc pr hor-mspace">
								<?php
									if(!empty($item['Item']['positive_feedback_count'])):
										$positive = floor(($item['Item']['positive_feedback_count']/$item['Item']['item_feedback_count']) *100);
										$negative = 100 - $positive;
									else:
										$positive = 0;
										$negative = 100;
									endif;
									echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&amp;chf=bg,s,FFFFFF00', array('width'=>'35px','height'=>'35px','title' => $positive.'%'));
								?>
							</dd>
							<?php endif; ?>
						</dl>
						<?php if ($this->Auth->user('id') != $item['Item']['user_id']): ?>
						<dl class="dc sep-right list">
							<dt class="pr hor-mspace text-11" ><?php echo __l('Network Level'); ?></dt>
							<?php if (!$this->Auth->user('is_facebook_friends_fetched')): ?>
							<dd class="textb text-20 graydarkc pr hor-mspace" title="<?php  echo __l('Connect with Facebook to find your friend level with host'); ?>"><?php  echo '?'; ?></dd>
							<?php elseif(!$this->Auth->user('is_show_facebook_friends')): ?>
							<dd class="textb text-20 graydarkc pr hor-mspace" title="<?php  echo __l('Enable Facebook friends level display in social networks page'); ?>"><?php  echo '?'; ?></dd>
							<?php elseif(empty($item['User']['is_facebook_friends_fetched'])): ?>
							<dd class="textb text-20 graydarkc pr hor-mspace" title="<?php  echo __l('Host is not connected with Facebook'); ?>"><?php  echo '?'; ?></dd>
							<?php elseif(!empty($network_level[$item['Item']['user_id']])): ?>
							<dd class="textb text-20 graydarkc pr hor-mspace" title="<?php  echo __l('Network Level'); ?>"><?php  echo $this->Html->cInt($network_level[$item['Item']['user_id']], false); ?></dd>
							<?php else: ?>
							<dd class="textb text-20 graydarkc pr hor-mspace" title="<?php  echo __l('Not Available'); ?>"><?php  echo __l('n/a'); ?></dd>
							<?php endif; ?>
						</dl>
						<?php endif; ?>
					</div>
				</div>
				<?php
					echo $this->element('popular-comment-users', array('user_name' => $item['User']['username'],'page' => 'view', 'config' => 'sec'));
				?>
			</div>
		</div> 
		<div class="main-content pr tooltip-size">
			<div id="ajax-tab-container-item" class="ajax-tab-container-item">
				<ul id="myTab2" class="nav nav-tabs top-space top-mspace">
					<li><a href="#description" data-toggle="tab" ><?php echo __l('Description'); ?></a> </li>
					<li>
						<?php    $hash = !empty($this->request->params['pass'][1]) ? $this->request->params['pass'][1] : '';
						$salt = !empty($this->request->params['pass'][2]) ? $this->request->params['pass'][2] : '';
						echo $this->Html->link(__l('Nearby') . ' ' . Configure::read('item.alt_name_for_item_plural_caps'),array('controller' => 'items', 'action' => 'index',$hash,$salt,'limit'=>5,'from'=>'ajax','item_id'=>$item['Item']['id'],'city_id'=>$item['City']['id'],'view'=>'compact', 'return'), array('title' => __l('Nearby ') . ' ' . Configure::read('item.alt_name_for_item_plural_caps'), 'class' => 'js-no-pjax', 'data-target'=>'#Nearby-Items','data-toggle'=>'tab')); ?>
					</li>
					<li><?php echo $this->Html->link(__l('Reviews'), array('controller' => 'items', 'action' => 'review_index','item_id' =>$item['Item']['id'],'type'=>'item','view'=>'compact'), array('title' => __l('Reviews'),'data-target'=>'#reviews', 'class' => 'js-no-pjax', 'data-toggle'=>'tab'));?></li>
					<li>
						<?php echo $this->Html->link(__l('Recommendations'), array('controller' => 'user_comments', 'action' => 'index', $item['User']['username']), array('title' => __l('Recommendations'), 'class' => 'js-no-pjax', 'data-target'=>'#recom','data-toggle'=>'tab'));?>
					</li>
					<li><?php echo $this->Html->link(sprintf(__l('Host\'s Other %s Reviews'), Configure::read('item.alt_name_for_item_singular_caps')), array('controller' => 'item_feedbacks', 'action' => 'index','user_id'=>$item['Item']['user_id'],'item_id' =>$item['Item']['id'],'type'=>'item','view'=>'compact'), array('title' => sprintf(__l('Host\'s Other %s Reviews'), Configure::read('item.alt_name_for_item_singular_caps')), 'class' => 'js-no-pjax', 'data-target'=>'#opr','data-toggle'=>'tab'));?></li>
					<?php if(Configure::read('friend.is_enabled')){?>
					<li>
						<?php echo $this->Html->link(__l('Followings'), array('controller' => 'user_followers', 'action' => 'index','user'=>$item['Item']['user_id'],'type'=>'user','view'=>'compact'), array('title' => __l('Followings'), 'class' => 'js-no-pjax', 'data-target'=>'#friends','data-toggle'=>'tab'));?>
					</li>
					<?php } ?>
					<?php if(!empty($item['Item']['video_url'])): ?>
						<li><?php echo $this->Html->link(__l('Video'),  '#videos', array('title' => __l('Video'), 'class' => 'js-no-pjax', 'data-toggle'=>'tab'));?></li>
					<?php endif; ?>
					<li><?php echo $this->Html->link(__l('Map'), array('controller' => 'items', 'action' => 'static_map', $item['Item']['slug']), array('title' => __l('Map'), 'class' => 'js-no-pjax', 'data-toggle'=>'tab', 'data-target'=>'#maps'));?></li>
				</ul>
				<div class="sep-right sep-left sep-bot tab-round tab-content" id="myTabContent2">
					<div class="tab-pane space active" id="description">
						<h3 class="well space textb text-16 no-mar"><?php echo __l('Description'); ?></h3>
						<p class="ver-mspace"><?php echo nl2br($this->Html->cText($item['Item']['description'], false));?></p>
						<?php if(!empty($item['Item']['is_have_definite_time'])) { ?>
						<div class="bot-mspace clearfix">
							<h3 class="well space textb text-16 no-mar"><?php echo __l('Rate Details'); ?></h3>
							
							<div class="space clearfix">
								<?php 
									$icon_date = 'icon-calendar';
									$icon_time = 'icon-time';
								if($item['Item']['is_people_can_book_my_time'] == 1) { ?>
								<?php if(count($custom_prices) > 0) { ?>
									<?php  $i = 1;
									foreach ($custom_prices as $custom_price){ ?>
										<?php if($i == 1 && $item['CustomPricePerNight'][0]['min_hours'] > 0){ ?>
											<div class="alert alert-inline alert-info dl">
												<p> <span class="textb"><?php echo __l('Min Hours') .': '. $item['CustomPricePerNight'][0]['min_hours']; ?></span>.</p> <p><?php echo __l('If user booking time is less than the minimum hours, then minimum hours will be taken as total booking time for calculating amount'); ?></p> 
											</div>
										<?php } ?>
										<div class="clearfix space sep no-shad">
												<h4 class="well space textb text-14 no-mar">
													<?php echo $this->Html->cText($custom_price['CustomPricePerNight']['name']);?> 
												</h4>
												
											<div class="space">
												<?php echo $this->Html->cText($custom_price['CustomPricePerNight']['description']);?>
											</div>
												<div class="space pull-left span8"> 
											<?php
												$str_end_date = strtotime($custom_price['CustomPricePerNight']['end_date']);
												if($item['CustomPricePerNight'][0]['is_timing'] == 0){
													echo '<i class="'. $icon_date.'"></i>' . $this->Html->cDate($custom_price['CustomPricePerNight']['start_date'], 'span', true).' '. $this->Html->cTime($custom_price['CustomPricePerNight']['start_time'], 'span', true). ' - ' ;
													if(!empty($str_end_date)){
														echo ' ' .$this->Html->cDate($custom_price['CustomPricePerNight']['end_date'], 'span', true);
													}
													if(!empty($custom_price['CustomPricePerNight']['end_time'])){
														echo ' ' . $this->Html->cTime($custom_price['CustomPricePerNight']['end_time'], 'span', true);
													}
												} else {
											?> 
													<p>
													<?php echo '<i class="'. $icon_date.'"></i>' . $this->Html->cDate($custom_price['CustomPricePerNight']['start_date'], 'span', true). ' - ';
													if(!empty($str_end_date)){
														echo $this->Html->cDate($custom_price['CustomPricePerNight']['end_date'],'span',true);
													} ?>
													</p>
													<p>
													<?php echo '<i class="'. $icon_time.'"></i>' . $this->Html->cTime($custom_price['CustomPricePerNight']['start_time'], 'span',true).' '.  $this->Html->cTime($custom_price['CustomPricePerNight']['end_time'], 'span', true);?>
													</p>
											<?php } ?> 
											</div>	
											<div class="space clearfix span13">
											<?php if(!empty($custom_price['CustomPricePerNight']['price_per_hour']) && $custom_price['CustomPricePerNight']['price_per_hour'] > 0) { ?>
											<dl class="dc sep-right list">
												<dt class="pr hor-mspace text-11"><?php echo __l('Per Hour');?></dt>
												<dd class="textb  pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($custom_price['CustomPricePerNight']['price_per_hour']);?></dd>
											</dl>
											<?php } ?>
											<?php if(!empty($custom_price['CustomPricePerNight']['price_per_day']) && $custom_price['CustomPricePerNight']['price_per_day'] > 0) { ?>
												<dl class="dc sep-right list">
													<dt class="pr hor-mspace text-11"><?php echo __l('Per Day');?></dt>
													<dd class="textb  pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($custom_price['CustomPricePerNight']['price_per_day']);?></dd>
												</dl>
											<?php } ?>
											<?php if(!empty($custom_price['CustomPricePerNight']['price_per_week']) && $custom_price['CustomPricePerNight']['price_per_week'] > 0) { ?>
												<dl class="dc sep-right list">
													<dt class="pr hor-mspace text-11"><?php echo __l('Per Week');?></dt>
													<dd class="textb  pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($custom_price['CustomPricePerNight']['price_per_week']);?></dd>
												</dl>
											<?php } ?>
											<?php if(!empty($custom_price['CustomPricePerNight']['price_per_month']) && $custom_price['CustomPricePerNight']['price_per_month'] > 0) { ?>
												<dl class="dc sep-right list">
													<dt class="pr hor-mspace text-11"><?php echo __l('Per Month');?></dt>
													<dd class="textb  pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($custom_price['CustomPricePerNight']['price_per_month']);?></dd>
												</dl>
											<?php	} ?>
											<?php if($custom_price['CustomPricePerNight']['price_per_hour'] <= 0 && $custom_price['CustomPricePerNight']['price_per_day'] <= 0 && $custom_price['CustomPricePerNight']['price_per_week'] <= 0 && $custom_price['CustomPricePerNight']['price_per_month'] <= 0) { ?>
												<dl class="dc list sep-right">
													<dt class="pr hor-mspace  text-11"><?php echo __l('Price');?></dt>
													<dd class="textb  pr hor-mspace"><?php echo __l('Free'); ?></dd>
												</dl>
											<?php	} ?>
											<?php if(!empty($custom_price['CustomPricePerNight']['repeat_days'])) { ?>
													<dl class="dc sep-right list span4">
														<dt class="pr hor-mspace text-11 bot-mspace"><?php echo __l('Repeat Days');?></dt>
														<dd class="textb  pr hor-mspace "><?php echo $this->Html->cText($custom_price['CustomPricePerNight']['repeat_days']);?></dd>
														<?php if(!empty($custom_price['CustomPricePerNight']['repeat_end_date'])) {?>
													<dt class="pr top-mspace bot-mspace text-11"><?php echo __l('Repeat Ends On');?></dt>
													<dd class="text-11 textb pr bot-mspace"><?php echo $this->Html->cDate($custom_price['CustomPricePerNight']['repeat_end_date']);?></dd>
													<?php } ?>
													</dl>
											<?php } ?>
											<?php if($item['Item']['is_additional_fee_to_buyer']) { ?>
													<dl class="dc sep-right list">
														<dt class="pr hor-mspace text-11"><?php echo __l('Additional Fee');?></dt>
														<dd class="textb  pr hor-mspace"><?php echo $this->Html->cFloat($item['Item']['additional_fee_percentage']);?></dd>
													</dl>
											<?php } ?>
											</div>
										</div>
								<?php $i++; } ?>	
								<?php } ?>
								<?php } ?>
								<?php if($item['Item']['is_sell_ticket'] == 1) {?>
								<?php if(count($custom_price_types) > 0) { ?>
								<?php  foreach($custom_price_types as $custom_price_main){ 
										$start_time = explode(':', $custom_price_main['CustomPricePerNight']['start_time']);
										$end_time = explode(':', $custom_price_main['CustomPricePerNight']['end_time']);
									$fixed_date = $this->Html->cDate($custom_price_main['CustomPricePerNight']['start_date'],'span', true)." - ".	$this->Html->cDate($custom_price_main['CustomPricePerNight']['end_date'], 'span', true);
								?>
										<div class="clearfix space sep no-shad">
											<div>											
												<h4 class="well space textb text-12 no-mar js-bootstrap-tooltip" title="<?php echo $this->Html->cText($fixed_date, false); ?>">
												<?php echo sprintf('<i class="'.$icon_date.'"></i> %s - %s', $this->Html->cDate($custom_price_main['CustomPricePerNight']['start_date'],'span', true), $this->Html->cDate($custom_price_main['CustomPricePerNight']['end_date'], 'span', true));?>
												<?php if(isPluginEnabled('Seats') && !empty($custom_price_main['Hall']['name'])){ ?>
													<span class="left-space"><?php echo '['.__l('Venue').' - '.$custom_price_main['Hall']['name'].']';?>
													</span>
												<?php } ?>
												</h4>
											</div>
											<div class="space clearfix pull-left">
											    <?php foreach($custom_price_main['CustomPricePerType'] as $custom_price_type){
														$type_start_time = explode(':', $custom_price_type['start_time']);
														$type_end_time = explode(':', $custom_price_type['end_time']);
														$type_s_time = date('h:i A', mktime($type_start_time[0], $type_start_time[1], $type_start_time[2], 0, 0, 0));
														$type_e_time = date('h:i A', mktime($type_end_time[0], $type_end_time[1], $type_end_time[2], 0, 0, 0));
												?>
													<dl class="dc sep-right list">
														<dt class="pr hor-mspace text-11">
															<?php echo $this->Html->cText($custom_price_type['name']);?>
															<?php if(!empty($custom_price_type['description'])){ ?>
															<span><i class="icon-info-sign js-bootstrap-tooltip" title="<?php echo $this->Html->cText($custom_price_type['description'], false);?>"></i></span>
															<?php } ?>
														</dt>
														<dd class="textb  pr hor-mspace"><?php echo $this->Html->siteCurrencyFormat($custom_price_type['price']);?></dd>
														<?php if(isPluginEnabled('Seats') && !empty($custom_price_type['Partition']['name'])){ ?>
														<dt class="top-space hor-mspace text-11"> <?php echo __l('Partition').' - '.$custom_price_type['Partition']['name'];?> </dt>
														<?php } ?>
														<dt class="top-space hor-mspace text-11"> <?php echo $type_s_time.' - '.$type_e_time;?> </dt>
													</dl>
												<?php } ?>
												<?php if($item['Item']['is_additional_fee_to_buyer']) { ?>
													<dl class="dc sep-right list">
														<dt class="pr hor-mspace text-11"><?php echo __l('Additional Fee');?></dt>
														<dd class="textb  pr hor-mspace"><?php echo $this->Html->cFloat($item['Item']['additional_fee_percentage']);?></dd>
													</dl>
												<?php } ?>
												<?php if(!empty($custom_price_main['CustomPricePerNight']['repeat_days'])) { ?>
												<dl class="dc list hor-space">
													<dt class="pr top-mspace bot-mspace text-11"><?php echo __l('Repeat Days');?></dt>
													<dd class="textb pr bot-mspace"><?php echo $this->Html->cText($custom_price_main['CustomPricePerNight']['repeat_days']);?></dd>
													<?php if(!empty($custom_price_main['CustomPricePerNight']['repeat_end_date'])) {?>
													<dt class="pr top-mspace bot-mspace text-11"><?php echo __l('Repeat Ends On');?></dt>
													<dd class="text-11 pr bot-mspace textb"><?php echo $this->Html->cDate($custom_price_main['CustomPricePerNight']['repeat_end_date']);?></dd>
													<?php } ?>
												</dl>
										<?php } ?>
											</div>
										</div>
										
								<?php } ?>
								<?php } ?>
								<?php } ?>
							</div>						
						</div>
						<?php }	?>
						<?php
							$is_mediafile = $is_urls = $is_otherdetails = 0;
							if(!empty($item['Submission']['SubmissionField'])) :								
								foreach($item['Submission']['SubmissionField'] as $submissionField):
									if(!empty($submissionField['type']) and empty($submissionField['FormField']['depends_on'])):
										if (!empty($submissionField['type']) && $submissionField['type'] == 'file') {
											$is_mediafile=1;
										} elseif (!empty($submissionField['type']) && $submissionField['type'] == 'url') {
											$is_urls=1;
										} else {
											$is_otherdetails=1;
										}
									endif;
								endforeach;
							endif;
						?>
						<?php if($is_mediafile != 0 || $is_urls != 0 ||  $is_otherdetails != 0) { ?> 
							<h2 class="space textb text-20 no-mar"><?php echo __l('Features'); ?></h2>
						<?php } ?>						
						<?php if(!empty($is_urls)) { ?>
						<div class="clearfix share-block top-space">
							<div class="clearfix">
								<div class="clearfix">
									<h5 class="pull-left textb clearfix"><?php echo sprintf(__l('This %s in other websites'), Configure::read('item.alt_name_for_item_singular_small')); ?></h5>
								</div>
								<div class="clearfix top-space">
									<ul class="clearfix row unstyled">
										<?php
											foreach($item['Submission']['SubmissionField'] as $submissionField):
												if (!empty($submissionField['type']) && $submissionField['FormField']['type'] == 'url'):
										?>
										<li class="span"><a href="<?php echo $this->Html->cText($submissionField['response'], false); ?>" target="_blank" class="website" title="<?php echo $this->Html->cText($submissionField['FormField']['label'], false); ?>"><?php echo $this->Html->cText($submissionField['FormField']['label'], false); ?></a></li>
										<?php
												endif;
											endforeach;
										?>
									</ul>
								</div>
							</div>
						</div>
						<?php } ?>
						<?php if(!empty($is_mediafile) && !empty($item['Submission']['SubmissionField'])): ?>
						<section class="clearfix ">
							<h4 class="page-header ver-space textb ver-mspace"><?php echo __l('Media and other files');?></h4>
							<?php
							$item_view_class = '';
							if (count($item['Submission']['SubmissionField']) >1) {
								$item_view_class = 'item-view-list';
							}
							?>
							<div class="<?php echo $item_view_class; ?> clearfix">
								<?php $j = 0; $class = ' class="altrow"';?>
								<?php
								foreach($item['Submission']['SubmissionField'] as $submissionField):
									if(empty($submissionField['FormField']['depends_on'])):
										$field_type = explode('_',$submissionField['form_field']);
										$div_class= '';
										$div_even = $j % 2;
										if($div_even == 0) {
											$div_class = 'grid_11 ';
										} else {
											$div_class = 'grid_right grid_11';
										}
								?>
								<div class="<?php echo $div_class;?>">
									<div class="description-info">
										<?php if (!empty($submissionField['type']) && $submissionField['type'] == 'file') {?>
										<div class="row  bot-mspace">
											<div class='span1 space'>
												<?php if(!empty($submissionField['SubmissionThumb']['mimetype']) && ($submissionField['SubmissionThumb']['mimetype'] == 'image/jpeg' || $submissionField['SubmissionThumb']['mimetype'] == 'image/png' || $submissionField['SubmissionThumb']['mimetype'] == 'image/jpg' || $submissionField['SubmissionThumb']['mimetype'] == 'image/gif')) {?>
													<?php echo $this->Html->showImage('SubmissionThumb', $submissionField['SubmissionThumb'], array('dimension' => 'micro_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'escape' => false));?>
												<?php } elseif (preg_match('/(\\.wmv|\\.flv|\\.avi)$/', $submissionField['SubmissionThumb']['filename'] )) { ?>
													<i class="icon-facetime-video text-32"></i>
												<?php } else { ?>
													<i class="icon-file text-32"></i>
												<?php }?>
											</div>
											<?php
												if(!empty($depends_on_fields[$submissionField['form_field']])) {
													$depends_array = $depends_on_fields[$submissionField['form_field']];
													foreach($depends_array  as $depends) {
														if($depends['type'] == 'text') {
											?>
											<div class ="top-space top-mspace pull-left span8">
												<div class="top-smspace">
													<?php echo $this->Html->link($this->Html->cText($depends['response'], false), array('controller' => 'items', 'action' => 'mediadownload',$item['Item']['slug'],$submissionField['id'],$submissionField['SubmissionThumb']['id']), array('class' => 'download js-tooltip', 'escape' => false,'title'=>__l("Download")." - ".$submissionField['SubmissionThumb']['filename']));?>
												</div>
											</div>
											<?php
														}
													}
												} else {
											?>
											<div class ="top-space top-mspace pull-left span8">
												<div class="top-smspace">
													<?php echo $this->Html->link($this->Html->cText($submissionField['SubmissionThumb']['filename'], false), array('controller' => 'items', 'action' => 'mediadownload',$item['Item']['slug'],$submissionField['id'],$submissionField['SubmissionThumb']['id']), array('class' => 'download js-tooltip', 'escape' => false,'title'=>__l("Download")." - ".$submissionField['SubmissionThumb']['filename']));?>
												</div>
											</div>
											<?php	
												}
											?>
											<div class="top-mspace htruncate pull-right"> </div>
										</div>
										<?php } ?>
									</div>
								</div>
								<?php
										$j++;
									endif;
								endforeach;
								?>
							</div>
						</section>
						<?php endif; ?>
						<?php if(!empty($is_otherdetails) && !empty($item['Submission']['SubmissionField'])): ?>
						<section class="clearfix">
							<h4 class="page-header ver-space textb ver-mspace"><?php echo __l("Other Details");?></h4>
							<?php
								$item_view_class = '';
								if (count($item['Submission']['SubmissionField']) >1) {
									$item_view_class = 'item-view-list';
								}
							?>
							<div class="<?php echo $item_view_class; ?> clearfix">
								<dl class="clearfix dl-horizontal">
									<?php $j = 0; $class = ' class="altrow"';?>
									<?php foreach($item['Submission']['SubmissionField'] as $submissionField):?>
									<?php if(empty($submissionField['FormField']['depends_on'])):?>
									<?php
										$field_type = explode('_',$submissionField['form_field']);
										$div_class= '';
										$div_even = $j % 2;
										if($div_even == 0) {
											$div_class = 'grid_11 ';
										} else {
											$div_class = 'grid_right grid_11';
										}
										$_form_field = '';
										$_form_field_info = '';
										if (!empty($submissionField['type']) && $submissionField['type'] != 'file' && $submissionField['type'] != 'url'):
											$_form_field = (!empty($submissionFieldDisplay[$submissionField['form_field']])) ? $this->Html->cText(Inflector::humanize(str_replace('##SITE_CURRENCY##', Configure::read('site.currency'), $submissionFieldDisplay[$submissionField['form_field']]))) : '';
											$_form_field_info = (!empty($submissionFieldDisplay[$submissionField['form_field']])) ? $this->Html->cText(Inflector::humanize(str_replace('##SITE_CURRENCY##', Configure::read('site.currency'), $submissionFieldDisplay[$submissionField['form_field']])), false) : '';
										endif;
									?>
									<dt class="dl" title="<?php echo $_form_field_info ;?>">
										<?php echo $_form_field;?>
									</dt>
									<dd class="description-info">
										<?php 
											if(!empty($submissionField['type']) && $submissionField['type'] != 'file' && $submissionField['type'] != 'url'){
												if (!empty($submissionField['type']) && $submissionField['type'] != 'thumbnail' && empty($submissionField['response'])) {
													echo __l('None specified');
												} else {
													if(!empty($submissionField['type']) && $submissionField['type'] == 'video') {
														if ($this->Embed->parseUrl($submissionField['response'])) {
															$this->Embed->setObjectAttrib('wmode','transparent');
															$this->Embed->setObjectParam('wmode', 'transparent');
															echo $this->Embed->getEmbedCode();
														}
													} elseif(!empty($submissionField['type']) && $submissionField['type'] == 'thumbnail') {
														if (empty($submissionField['ItemCloneThumb'])){
															echo __l('None specified');
														} else {
															$regex = '/(?<!href=["\'])http:\/\//';
															$regex1 = '/(?<!href=["\'])https:\/\//';
															$display_url = preg_replace($regex, '', $submissionField['response']);
															$display_url = preg_replace($regex1, '', $display_url);
										?>
										<div class="clone-block">
											<?php echo $this->Html->link($this->Html->showImage('ItemCloneThumb', $submissionField['ItemCloneThumb'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false), 'escape' => false)), $submissionField['response'], array('target'=>'_blank','escape' => false)); ?>
											<p><?php echo $this->Html->link($display_url,$submissionField['response'], array('target'=>'_blank','escape' => false));?></p>
										</div>
										<?php
														}
													} elseif (!empty($submissionField['type']) && $submissionField['type'] == 'date') {
														$convert_date = explode("\n", $submissionField['response']);
														if (count($convert_date) > 1):
															$dateval = $convert_date[2].'-'.$convert_date[0].'-'.$convert_date[1];
															echo $this->Html->cDate($dateval);
														endif;
													} elseif (!empty($submissionField['type']) && $submissionField['type'] == 'datetime') {
														$convert_date = explode("\n", $submissionField['response']);
														if (count($convert_date) > 5):
															$dateval = $convert_date[2].'-'.$convert_date[0].'-'.$convert_date[1].' '.$convert_date[3].':'.$convert_date[4].' '.$convert_date[5];
															echo $this->Html->cDateTime($dateval);
														endif;
													} elseif (!empty($submissionField['type']) && $submissionField['type'] == 'time') {
														$convert_date = explode("\n", $submissionField['response']);
														if (count($convert_date) > 1):
															$dateval = $convert_date[0].':'.$convert_date[1].' '.$convert_date[2];
															echo $this->Html->cTime($dateval);
														endif;
													} elseif (!empty($submissionField['type']) && $submissionField['type'] == 'checkbox' || $submissionField['type'] == 'multiselect') {
														$convert_val = explode("\n", $submissionField['response']);
														$textval = implode("<br/>", $convert_val);
														echo $this->Html->cHtml($textval);
													} elseif (!empty($submissionField['type']) && $submissionField['type'] == 'slider') {
														if (!empty($submissionFieldOption[$submissionField['form_field']])) {
															$option_val = explode(',', $submissionFieldOption[$submissionField['form_field']]);
										?>
										<div class="clearfix"> <span class="grid_left"><?php echo trim($option_val[0]); ?></span>
											<div class="ui-slider grid_left ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" role="application"> <span class="arrow" title="<?php echo $this->Html->cText($submissionField['response'], false); ?>%" style="left: <?php echo $this->Html->cText($submissionField['response'] - 5, false); ?>%;"></span> <span style="width: <?php echo $this->Html->cText($submissionField['response'], false); ?>%;" class="ui-slider-handle ui-state-default ui-corner-all" aria-valuetext="<?php echo $this->Html->cText($submissionField['response'], false); ?>" aria-valuenow="<?php echo $this->Html->cInt($submissionField['response'], false); ?>" aria-valuemax="99" aria-valuemin="0" aria-labelledby="undefined" role="slider" tabindex="0"  style="" title="<?php echo $this->Html->cText($submissionField['response'], false); ?>%"></span> </div>
											<span class="grid_left"><?php echo trim($option_val[1]); ?></span>
										</div>
										<?php
														}
													} elseif(!empty($submissionField['type']) && $submissionField['type'] == 'url') {
														$url_string = $submissionField['response'];
														$find_string   = 'http';
														$return = strpos($url_string, $find_string);
														if ($return === false) {
										?>
										<a href="http://<?php echo $submissionField['response']; ?>" target = "_blank" > <?php echo $submissionField['response'];?></a>
										<?php
														} else {
															echo $this->Html->link($submissionField['response'],$submissionField['response'], array('target'=>'_blank','escape' => false));
														}
													} else {
														echo $this->Html->cText($submissionField['response'], false);
													}
												}
											}
										?>
									</dd>
									<?php
										$j++;
										endif;
									endforeach;
									?>
								</dl>
							</div>
						</section>
						<?php endif; ?>
					</div>
					<div id="Nearby-Items" class="tab-pane tab-round"></div>
					<div id="reviews" class="tab-pane"></div>
					<div id="recom"  class="tab-pane"></div>
					<div id="opr" class="tab-pane"></div>
					<div id="friends" class="tab-pane"></div>
					<div id="videos" class="tab-pane">
					   	<?php if (!empty($item['Item']['video_url'])): ?>
						<div id="video-1" class="space dc">
							<?php
								if($this->Embed->parseUrl($item['Item']['video_url'])) {
									$this->Embed->setHeight('410px');
									$this->Embed->setWidth('647px');
									echo $this->Embed->getEmbedCode();
								}
							?>
						</div>
						<?php endif; ?>
					</div>
					<div id="maps" class="tab-pane"></div>
				</div>
			</div>
		</div>
	</div>
	<?php if (Configure::read('widget.item_script')) { ?>
	<div class="dc clearfix ver-space">
		<?php echo Configure::read('widget.item_script'); ?>
	</div>
	<?php } ?>
</div>
<div id="fb-root"></div>
<?php Configure::write('highperformance.pids', $item['Item']['id']); ?>