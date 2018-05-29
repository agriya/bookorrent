<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<?php
Configure::write('highperformance.uids', $this->Auth->user('id'));
if (!empty($requests)) {
	foreach ($requests as $request) {
		foreach($request as $tmp_request) {
			Configure::write('highperformance.rids', Set::merge(Configure::read('highperformance.rids') , $tmp_request['Request']['id']));
		}
	}
}
?>
<?php
$hash = !empty($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : '';
$salt = !empty($this->request->params['pass'][1]) ? $this->request->params['pass'][1] : '';
if ($search_keyword) {
    $city = isset($search_keyword['named']['cityname']) ? $search_keyword['named']['cityname'] : '';
    $latitude = isset($search_keyword['named']['latitude']) ? $search_keyword['named']['latitude'] : '';
    $longitude = isset($search_keyword['named']['longitude']) ? $search_keyword['named']['longitude'] : '';
    $from = isset($search_keyword['named']['from']) ? $search_keyword['named']['from'] : '';
    $to = isset($search_keyword['named']['to']) ? $search_keyword['named']['to'] : '';
    $additional_guest = isset($search_keyword['named']['additional_guest']) ? $search_keyword['named']['additional_guest'] : '';
    $is_flexible = isset($search_keyword['named']['is_flexible']) ? $search_keyword['named']['is_flexible'] : '';
    $rangefrom = isset($search_keyword['named']['range_from']) ? $search_keyword['named']['range_from'] : '1';
    $rangeto = isset($search_keyword['named']['range_to']) ? $search_keyword['named']['range_to'] : '300+';
    $keyword = isset($search_keyword['named']['keyword']) ? $search_keyword['named']['keyword'] : '';
    $cityy = isset($search_keyword['named']['city']) ? $search_keyword['named']['city'] : 'all';
    //this->request->data['Request']=$search_keyword['named'];
    if (!empty($rangeto)) {
        $this->request->data['Request']['range_to'] = $rangeto;
    }
} else {
    $city = isset($this->request->params['named']['cityname']) ? $this->request->params['named']['cityname'] : '';
    $latitude = isset($this->request->params['named']['latitude']) ? $this->request->params['named']['latitude'] : '';
    $longitude = isset($this->request->params['named']['longitude']) ? $this->request->params['named']['longitude'] : '';
    $from = isset($this->request->params['named']['from']) ? $this->request->params['named']['from'] : '';
    $to = isset($this->request->params['named']['to']) ? $this->request->params['named']['to'] : '';
    $additional_guest = isset($this->request->params['named']['additional_guest']) ? $this->request->params['named']['additional_guest'] : '';
    $is_flexible = isset($this->request->params['named']['is_flexible']) ? $this->request->params['named']['is_flexible'] : '';
    $rangefrom = isset($this->request->params['named']['range_from']) ? $this->request->params['named']['range_from'] : '1';
    $rangeto = isset($this->request->params['named']['range_to']) ? $this->request->params['named']['range_to'] : '300+';
    $keyword = isset($this->request->params['named']['keyword']) ? $this->request->params['named']['keyword'] : '';
    $cityy = isset($this->request->params['named']['city']) ? $this->request->params['named']['city'] : 'all';
    if (!empty($rangeto)) {
        $this->request->data['Request']['range_to'] = $rangeto;
    }
}
if (isset($is_favorite)) {
    $class_name = '';
} else {
    $class_name = 'request-index-page';
}
?>
<?php if ($search == 'normal' && !isset($is_favorite)): ?>
				<?php echo $this->element('request_search', array('config' => 'sec', 'type' => 'search')); ?>
<?php endif; ?>
<?php if ($search == 'normal'): ?>
	<div class="js-response js-responses clearfix">
<?php endif; ?>
<section class="row no-mar">
	<div class="span24 no-mar pr <?php echo $class_name; ?>">
		
		<span class="js-search-lat {'cur_lat':'<?php echo $current_latitude; ?>','cur_lng':'<?php echo $current_longitude; ?>'}"></span>
		<?php 
		$fromAjax = FALSE;
		$widthClass= 'span21 span21-sm';
		if(isset($this->request->params['named']['from']) && $this->request->params['named']['from'] == 'ajax'):
			$fromAjax = TRUE;
			$widthClass= 'span21 span20-sm';
		 endif; ?>
	<?php if(empty($this->request->params['isAjax'])):?>
			<?php if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'favorite'): ?>
					<h2 class="ver-space top-mspace text-32 sep-bot"><?php echo __l('Liked Requests'); ?></h2>
				<?php else: ?>
				
				<div class="well space clearfix">
					<h2 class="ver-space pull-left orangec textb text-16"><?php echo __l('Requests'); ?></h2>
					</div>
				<?php endif; 				endif;
				?>
				<?php if (!isset($is_favorite)): ?>
				<?php $widthClass= 'span20 span20-sm'; ?>
		<aside class="haccordion pa mob-ps">
            <ul class="unstyled text-16">
			 <?php echo $this->Form->create('Request', array('id'=> 'KeywordsSearchForm','class' => 'check-form js-search-map js-ajax-search-form norma keywords no-mar','action'=>'index')); ?>
              <li class="sep-bot">
                <div class="graydarkc no-under" title="<?php echo __l('Refine'); ?>">
                  <div id="accordion1" class="accordion no-mar">
                    <div class="space clearfix"> 
					<span class="accordion-menu cur pull-left">
					<span class="width22 pull-left show dc"><i class="icon-map-marker cur text-20 no-pad"></i></span>
						<span class="hor-space left-mspace"><?php echo __l('Refine'); ?></span>
					</span>
                      <div class="accordion-list thumbnail no-round no-pad pull-right"> <a href="#collapseOne" data-parent="#accordion1" data-toggle="collapse" class="whitec hor-smspace accordion-toggle js-toggle-icon"><i class="icon-plus no-pad graylightc text-10"></i></a></div>
                    </div>
                  </div>
                  <div id="collapseOne" class="accordion-body collapse">
                    <div class="thumbnail no-bor no-shad no-round space cleafix">
					<span class="show">
					<div class="js-side-map">
						<div id="js-map-container"></div>
					</div>
					</span> 
					<a href="javascript:void(0);" class="show btn btn-large ver-smspace btn-primary textb text-16 map-button js-mapsearch-button" title="<?php echo __l('Update'); ?>"><?php echo __l('Update'); ?></a>
                      <div class="form-search ver-mspace cleafix">
                        <div class="input text"> <span class="span no-mar">
                          <?php echo $this->Form->input('Request.keyword', array('label' =>__l('Keyword'),'value'=>$keyword,'label'=>false,'div'=>false,'class'=>'span4 text-16')); ?>
                          </span> </div>
                        <a href="javascript:void(0);" class="pull-right mob-clr show btn btn-large textb text-16 js-submit-button" title="<?php echo __l('Search'); ?>"><i class="icon-search no-pad no-mar textb text-16"></i></a>
						</div>
                    </div>
                  </div>
                </div>
              </li>
			  <li class="sep-bot">
				<div class="graydarkc no-under" title="<?php echo __l('Categories'); ?>">
					<div id="accordion4" class="accordion no-mar">
						<div class="space clearfix"> <span class="accordion-menu cur pull-left"><span class="width22 pull-left show dc"><i class="icon-sitemap cur text-1818 no-pad"></i></span><span class="hor-space left-mspace"><?php echo __l('Categories'); ?></span></span>
							<div class="accordion-list thumbnail no-round no-pad pull-right"> <a href="#collapseFour" data-parent="#accordion4" data-toggle="collapse" class="whitec hor-smspace accordion-toggle js-category-toggle-icon js-toggle-icon"><i class="icon-plus no-pad graylightc text-10"></i></a></div>
						</div>
					</div>
					<div id="collapseFour" class="js-category-accordion-body accordion-body collapse">
					  <div class="thumbnail no-bor no-shad no-round space clearfix">
						<div class="bot-mspace">
						  <div class="graydarkerc text-14"> 
							<?php foreach($categories As $category) { 
									$sub_categories = $this->Html->getSubCategoriesList($category['Category']['id'], 'Request');
									$total_count = $sub_categories['total_count'];
									unset($sub_categories['total_count']);
									if(!empty($this->request->data['Request']['Category'])) {
										$sub_categories_keys = array_keys($sub_categories);
										$this->request->data['Request']['Category'][$category['Category']['id']] = array_intersect($this->request->data['Request']['Category'], $sub_categories_keys);
									}
									if(!empty($this->request->data['Request']['Category'][$category['Category']['id']]) && count($this->request->data['Request']['Category'][$category['Category']['id']]) > 0) {
										$this->request->data['Request']['parent_category_id'][$category['Category']['id']] = 1;
									}
							?>
								<h5 class="textb grayc top-smspace clearfix">
									<?php echo $this->Form->input('Request.parent_category_id.'.$category['Category']['id'], array('type' => 'checkbox', 'class' => 'js-filter-categroy', 'hiddenField' => false, 'div' => 'pull-left main-category input checkbox no-mar', 'data-category_id' => $category['Category']['id'], 'label' => $category['Category']['name'] . ' (' . $total_count . ')'));?>
									<span class="pull-right js-category-toggle" data-category_id="<?php echo $this->Html->cInt($category['Category']['id'], false); ?>"><i class="icon-chevron-down cur text-12"></i></span>
								</h5>
								<div class="js-sub-category-block-<?php echo $this->Html->cInt($category['Category']['id'], false); ?> hide">
								<?php 
									echo $this->Form->input('Request.Category.'.$category['Category']['id'], array('type'=>'select', 'options' => $sub_categories, 'multiple'=>'checkbox', 'class'=>'show left-mspace checkbox clearfix js-subcategory-lists-' . $category['Category']['id'], 'label' =>false));
								?>
								</div>
							<?php } ?>
						  </div>
						</div>
					  </div>
					</div>
				</div>
			</li>
               <li class="sep-bot">
                <div class="graydarkc no-under" title="<?php echo __l('Price Range'); ?>">
                  <div id="accordion5" class="accordion no-mar">
                    <div class="space clearfix"> <span class="accordion-menu cur pull-left"><span class="width22 pull-left show dc"><i class="icon-money cur text-18 no-pad"></i></span><span class="hor-space left-mspace"><?php echo __l('Price Range'); ?></span></span>
                      <div class="accordion-list thumbnail no-round no-pad pull-right"> <a href="#collapseFive" data-parent="#accordion5" data-toggle="collapse" class="whitec hor-smspace accordion-toggle js-toggle-icon"><i class="icon-plus no-pad graylightc text-10"></i></a></div>
                    </div>
                  </div>
                  <div id="collapseFive" class="accordion-body collapse">
                    <div class="thumbnail no-bor no-shad no-round space clearfix">
                      <div class="bot-mspace">
                        <div class="graydarkerc text-14">
						<div class="price-range-info-block dc"><span class="price-range tb"><?php echo __l('Price range ');?></span>
							<span class="js-rang-from"><?php echo $this->Html->cInt($rangefrom, false); ?></span><?php echo __l(' to '); ?><span class="js-rang-to"><?php echo $this->Html->cInt($rangeto, false); ?></span>
						</div>
						<div class="clearfix">
						  <?php echo $this->Form->input('Request.range_from', array('type'=>'hidden', 'id'=>'js-range_from', 'label' =>false)); ?>
						  <?php echo $this->Form->input('Request.range_to', array('type'=>'hidden', 'id'=>'js-range_to', 'label' =>false)); ?>
						  <?php echo $this->Form->input('Request.price_range', array('type'=>'select', 'data-slider_min' => 1, 'data-slider_max' => 301, 'id'=>'js-price-range', 'label' =>false, 'class' => 'js-uislider hide')); ?>
						</div>
						</div>
                      </div>
                    </div>
                  </div>
                </div>
              </li>			
									<?php
							echo $this->Form->input('cityName', array(
								'type' => 'hidden',
								'id' => 'city_index',
								'value' => $city
							));
							echo $this->Form->input('latitude', array(
								'type' => 'hidden',
								'value' => $latitude
							));
							echo $this->Form->input('longitude', array(
								'type' => 'hidden',
								'value' => $longitude
							));
							echo $this->Form->input('from', array(
								'type' => 'hidden',
								'value' => $from
							));
							echo $this->Form->input('to', array(
								'type' => 'hidden',
								'value' => $to
							));
							echo $this->Form->input('additional_guest', array(
								'type' => 'hidden',
								'value' => $additional_guest
							));
							echo $this->Form->input('type', array(
								'type' => 'hidden',
								'value' => 'search'
							));
							echo $this->Form->input('search', array(
								'type' => 'hidden',
								'value' => 'side'
							));
							echo $this->Form->input('ne_longitude', array(
								'type' => 'hidden',
								'id' => 'ne_longitude_index'
							));
							echo $this->Form->input('sw_longitude', array(
								'type' => 'hidden',
								'id' => 'sw_longitude_index'
							));
							echo $this->Form->input('sw_latitude', array(
								'type' => 'hidden',
								'id' => 'sw_latitude_index'
							));
							echo $this->Form->input('ne_latitude', array(
								'type' => 'hidden',
								'id' => 'ne_latitude_index'
							));
						?>
            <?php echo $this->Form->end(); ?>
			</ul>
			
          </aside>
		  <?php
		  endif;
						$view_count_url = Router::url(array(
							'controller' => 'requests',
							'action' => 'update_view_count',
						), true);
					?>	
		<section id="Items" class="<?php echo (isset($is_favorite))? 'span24':'span23' ?> mob-clr row pull-right bot-space">
		
				<?php
					if (!empty($requests)):
						$num = 1;
				?>		
					<ol class="unstyled prop-list prop-list-mob no-mar js-view-count-update top-space {'model':'request','url':'<?php echo $this->Html->cText($view_count_url, false); ?>'}" start="<?php echo $this->Paginator->counter(array('format' => '%start%')); ?>">
				<?php foreach($requests as $key => $requests_date):
				?>
								
						<?php
							$i = 0;
							foreach($requests_date as $request):
								$class = null;
								if ($i++%2 == 0) {
									$class = ' class="altrow"';
								}
						?>
			<li class="clearfix ver-space sep-bot js-map-request-num<?php echo $num; ?>  hor-smspace">
				 <div class="span dc"> <span class="label label-important textb show text-11 prop-count"><?php echo $num; ?></span>						
						<?php if(isPluginEnabled('RequestFavorites')) {
							if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))){?>
							<div class="alpruf-<?php echo $this->Html->cInt($request['Request']['id'], false);?> hide">
								<?php	echo $this->Html->link('<i class="icon-star no-pad text-18"></i>' , array('controller' => 'request_favorites', 'action' => 'delete', $request['Request']['slug']) , array('class' => 'js-no-pjax js-like un-like tb top-space  show',  'escape' => false, 'title' => __l('Unlike'))); ?>
							</div>	
							<div class="alprf-<?php echo $this->Html->cInt($request['Request']['id'], false);?> hide">
								<?php echo $this->Html->link('<i class="icon-star-empty grayc no-pad text-18"></i>' , array('controller' => 'request_favorites', 'action' => 'add', $request['Request']['slug']) , array('title' => __l('Like') , 'escape' => false, 'class' => 'js-no-pjax js-like tb like top-space show')); ?>
							</div>
							<div class="blprf-<?php echo $this->Html->cInt($request['Request']['id'], false);?> hide">
									<?php	echo $this->Html->link('<i class="icon-star-empty no-pad text-18"></i>' , array('controller' => 'users', 'action' => 'login', '?' => 'f='. $this->request->url) , array('title' => __l('Like') , 'escape' => false, 'class' => ' like tb top-space  show')); ?>
							</div>
						<?php }else{ ?>
							<span>
							<?php
								if ($this->Auth->sessionValid()){
									if (!empty($request['RequestFavorite'])){
										foreach($request['RequestFavorite'] as $favorite):
											if ($request['Request']['id'] == $favorite['request_id'] && $request['Request']['user_id'] != $this->Auth->user('id')):
												echo $this->Html->link('<i class="icon-star no-pad text-18"></i>' , array('controller' => 'request_favorites', 'action' => 'delete', $request['Request']['slug']) , array('class' => 'js-no-pjax js-like un-like tb top-space  show','escape' => false, 'title' => __l('Unlike')));
											endif;
										endforeach;
									}else{
										if ($request['Request']['user_id'] != $this->Auth->user('id')){
											echo $this->Html->link('<i class="grayc icon-star-empty no-pad text-18"></i>' , array('controller' => 'request_favorites', 'action' => 'add', $request['Request']['slug']) , array('title' => __l('Like') , 'escape' => false, 'class' => 'js-no-pjax js-like tb like top-space show'));
										}
									}
								}else{
									echo $this->Html->link('<i class="grayc icon-star-empty no-pad text-18"></i>' , array('controller' => 'users', 'action' => 'login', '?' => 'f='. $this->request->url) , array('title' => __l('Like') , 'escape' => false, 'class' => ' like tb top-space  show'));
								}
							?>
							</span>
							<?php } 
						} ?>
						<?php if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))):?>
				<div class="aloed-<?php echo $this->Html->cInt($request['Request']['id'], false); ?> hide">
					<div class="dropdown"> <a href="#" title="Edit" class="dropdown-toggle text-14 textb graylighterc no-shad" data-toggle="dropdown"><i class="icon-cog graylightc no-pad text-16"></i></a>
						<ul class="dropdown-menu dl arrow">
						  <li><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action'=>'edit', $request['Request']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit'),'escape'=>false));?></li>
						  <li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action'=>'delete', $request['Request']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete'),'escape'=>false));?> </li>
						</ul>
					</div>
					</div>
				<?php else:
					if ($request['Request']['user_id'] == $this->Auth->user('id')) : ?>
					<div class="dropdown"> <a href="#" title="Edit" class="dropdown-toggle text-14 textb graylighterc no-shad" data-toggle="dropdown"><i class="icon-cog graylightc no-pad text-16"></i></a>
						<ul class="dropdown-menu dl arrow">
						  <li><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action'=>'edit', $request['Request']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit'),'escape'=>false));?></li>
						  <li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action'=>'delete', $request['Request']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete'),'escape'=>false));?> </li>
						</ul>
					  </div>
				<?php endif; 
				endif;?>			
				 </div>
				 <?php $date = explode('-', $key); ?>
				 <span class="img-rounded sep date-block span cur no-under show no-pad dc graydarkc"> 
				 <span class="show well no-mar hor-space"><?php echo date('M', mktime(0, 0, 0, $date[1], $date[2], $date[0])); ?></span> <span class="show textb text-24"><?php echo date('d', mktime(0, 0, 0, $date[1], $date[2], $date[0])); ?></span> <span class="show sep-top"><?php echo date('D', mktime(0, 0, 0, $date[1], $date[2], $date[0])); ?></span> </span>
				 
				 <div class="<?php echo $widthClass; ?> pull-right no-mar mob-clr">
								<div class=" clearfix <?php echo (isset($is_favorite))? '':'sep-bot' ?>">
									<div class="span dc no-mar user-avatar">
										<?php
											$current_user_details = array(
												'username' => $request['User']['username'],
												'role_id' => $request['User']['role_id'],
												'id' => $request['User']['id'],
												'facebook_user_id' => $request['User']['facebook_user_id']
											);
											$current_user_details['UserAvatar'] = array(
												'id' => $request['User']['attachment_id']
											);
											echo $this->Html->getUserAvatar($current_user_details, 'medium_thumb', true);
										?>
									</div>
									<div class="clearfix">
										<div class="<?php echo (isset($is_favorite))? 'span10':'span' ?>">
											<h4 class="textb text-16 clearfix">
												<?php
													$lat = $request['Request']['latitude'];
													$lng = $request['Request']['longitude'];
													$id = $request['Request']['id'];
													echo $this->Html->link($this->Html->cText($request['Request']['title']) , array('controller' => 'requests', 'action' => 'view', $request['Request']['slug'], $hash, $salt, 'admin' => false) , array('id' => "js-map-side-$id", 'class' => "graydarkc js-map-data {'lat':'$lat','lng':'$lng'}", 'title' => $this->Html->cText($request['Request']['title'], false), 'escape' => false, 'class' => 'htruncate clearfix js-bootstrap-tooltip span6 graydarkc no-mar mob-clr', 'data-placement' => 'bottom'));
												?>
											</h4>
											<?php
												$flexible_class = '';
												if (isset($search_keyword['named']['is_flexible']) && $search_keyword['named']['is_flexible'] == 1) {
													if (!empty($exact_ids) && in_array($request['Request']['id'], $exact_ids)) {
											?><div class="clearfix top-space dc">
												<span class="label pull-left mob-inline"><?php echo __l('exact'); ?></span></div>
												
											<?php
													}
												}
											?>
										
										<span class=" graydarkc top-smspace show mob-dc mob-clr">
											<?php if (!empty($request['Country']['iso_alpha2'])): ?>
												<span class="flags mob-inline top-smspace flag-<?php echo strtolower($request['Country']['iso_alpha2']); ?>" title ="<?php echo $this->Html->cText($request['Country']['name'], false); ?>"><?php echo $this->Html->cText($request['Country']['name'], false); ?></span>
                                        	<?php endif; ?>
											<div class="htruncate js-bootstrap-tooltip no-mar span9" title="<?php echo $this->Html->cHtml($request['Request']['address'], false);?>"><?php echo $this->Html->cText($request['Request']['address'], false);?></div>
											
										</span>
										<div class="no-mar">
										<p class="htruncate js-bootstrap-tooltip span9  no-mar mob-dc" title="<?php echo $this->Html->cText($request['Request']['description'], false);?>"><?php echo $this->Html->cText($request['Request']['description'], false); ?>
											</p>
											</div>
									
									</div>
									<div class="pull-right mob-clr tab-clr">
									<?php if (isset($is_favorite)): ?>
									<div class="clearfix pull-left mob-clr top-mspace mob-inline">
											<dl class="list">
											  <dt class="pr hor-space  text-11"><?php echo __l('Views'); ?></dt>
											  <dd title="234" class="textb text-20 graydarkc pr hor-mspace js-view-count-request-id js-view-count-request-id-<?php echo $request['Request']['id']; ?> {'id':'<?php echo $request['Request']['id']; ?>'}"><?php echo numbers_to_higher($request['Request']['request_view_count']); ?></dd>
											</dl>
											<dl class="list">
											  <dt class="pr hor-space  text-11"><?php echo __l('Offered'); ?></dt>
											  <dd title="689" class="textb text-20 graydarkc pr hor-mspace"><?php echo $this->Html->cInt($request['Request']['item_count']); ?></dd>
											</dl>
											<dl class="list">
											  <dt class="pr hor-space  text-11"><?php echo __l('Days'); ?></dt>
											  <dd title="n/a" class="textb text-20 graydarkc pr hor-mspace"><?php echo $this->Html->cInt(getFromToDiff($request['Request']['from'], getToDate($request['Request']['to']))); ?></dd>
											</dl>
										  </div>
									<?php endif; ?>
										<div class="clearfix pull-left hor-space left-mspace sep-left mob-sep-none tab-no-mar mob-clr mob-dc">
											<?php
												$requested_date = $this->Html->cDate($request['Request']['from'], 'span', true) . ' - ' . $this->Html->cDate($request['Request']['to'], 'span', true);
											?>
											<p class="no-mar js-bootstrap-tooltip" title="<?php echo $this->Html->cText($requested_date, false); ?>"><?php echo $requested_date; ?></p>
											<dl class="dc list span mob-clr">
											<dt class="pr hor-mspace text-11"><?php echo __l('Price');?></dt>
											<dd class="textb text-24 graydarkc pr hor-mspace">
											<?php if (Configure::read('site.currency_symbol_place') == 'left'): ?>
												<?php echo Configure::read('site.currency').' '?>
											<?php endif; ?>
											<?php echo $this->Html->cCurrency($request['Request']['price']);?>
											<?php if (Configure::read('site.currency_symbol_place') == 'right'): ?>
												 <?php echo ' '.Configure::read('site.currency'); ?>
											<?php endif; ?>
										</dd>
										</div>
									</div>
									</div>
									</div>
									<?php if (!isset($is_favorite)): ?>
									<div class="clearfix mob-dc">
										  <div class="clearfix pull-left mob-clr top-mspace mob-inline">
											<dl class="list">
											  <dt class="pr hor-space  text-11"><?php echo __l('Views'); ?></dt>
											  <dd title="234" class="textb text-20 graydarkc pr hor-mspace js-view-count-request-id js-view-count-request-id-<?php echo $this->Html->cInt($request['Request']['id'], false); ?> {'id':'<?php echo $this->Html->cInt($request['Request']['id'], false); ?>'}"><?php echo numbers_to_higher($request['Request']['request_view_count']); ?></dd>
											</dl>
											<dl class="list">
											  <dt class="pr hor-space  text-11"><?php echo __l('Offered'); ?></dt>
											  <dd title="689" class="textb text-20 graydarkc pr hor-mspace"><?php echo $this->Html->cInt($request['Request']['item_count']); ?></dd>
											</dl>
											<dl class="list">
											  <dt class="pr hor-space  text-11"><?php echo __l('Days'); ?></dt>
											  <dd title="n/a" class="textb text-20 graydarkc pr hor-mspace"><?php echo $this->Html->cInt(getFromToDiff($request['Request']['from'], getToDate($request['Request']['to']))); ?></dd>
											</dl>
										  </div>
										  <div class="pull-right mob-clr right-mspace">
										  <?php if ($request['User']['id'] != $this->Auth->user('id')): ?>
												<?php if ($request['Request']['from'] >= date('Y-m-d') && $request['Request']['to'] >= date('Y-m-d')): ?>
													<?php echo $this->Html->link(__l('Make an offer') , array('controller' => 'items', 'action' => 'add', 'request', $request['Request']['id'], 'admin' => false) , array('title' => __l('Make an offer') , 'escape' => false, 'class' => 'show btn  top-mspace btn-large btn-primary text-18 textb')); ?>
												<?php endif; ?>
											<?php endif; ?>
										  </div>
										</div>
										<?php endif; ?>
									  </div>
			
					<?php $num++; endforeach; ?>
				
			
			<?php
					endforeach;
					?>
				</li></ol>
			<?php else:
			?>
			
			<ol class="clearfix unstyled">
				<li>
					<div class="space dc grayc">
						<p class="ver-mspace top-space text-16">
							<?php echo __l('No Requests available');?>
						</p>
					</div>
				</li>			
			</ol>
			<?php
				endif;
			?>
			<?php
				if (!empty($requests) && count($requests) > 10) { ?>
						<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?> paging clearfix space pull-right mob-clr"><?php echo $this->element('paging_links'); ?></div>
			<?php } ?>
		</section>
        </div>
      </section>
	<?php if ($search == 'normal'): ?>
		</div>
	<?php endif; ?>