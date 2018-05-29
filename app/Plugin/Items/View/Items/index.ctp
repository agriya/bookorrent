<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<?php Configure::write('highperformance.uids', $this->Auth->user('id'));
if (!empty($items)) {
  foreach ($items as $item){
    Configure::write('highperformance.pids', Set::merge(Configure::read('highperformance.pids') , $item['Item']['id']));
  }
}?>
<?php 
$hash = !empty($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : '';
$salt = !empty($this->request->params['pass'][1]) ? $this->request->params['pass'][1] : '';
$allow = true;
if (isset($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'favorite' && isPluginEnabled('ItemFavorites')) || isset($near_by) || (isset($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'compact')):
 $allow = false;
endif;
if ((isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'user' && $this->request->params['named']['type'] != 'favorite' && !isset($near_by)) || empty($this->request->params['named'])) {
  if ($search_keyword) {
	$city = isset($search_keyword['named']['cityname']) ? $search_keyword['named']['cityname'] : '';
	$latitude = isset($search_keyword['named']['latitude']) ? $search_keyword['named']['latitude'] : '';
	$longitude = isset($search_keyword['named']['longitude']) ? $search_keyword['named']['longitude'] : '';
	$from = isset($search_keyword['named']['from']) ? $search_keyword['named']['from'] : '';
	$to = isset($search_keyword['named']['to']) ? $search_keyword['named']['to'] : '';
	$additional_guest = isset($search_keyword['named']['additional_guest']) ? $search_keyword['named']['additional_guest'] : '';
	$type = isset($search_keyword['named']['type']) ? $search_keyword['named']['type'] : '';
	$is_flexible = isset($search_keyword['named']['is_flexible']) ? $search_keyword['named']['is_flexible'] : '';
	$rangefrom = isset($search_keyword['named']['range_from']) ? $search_keyword['named']['range_from'] : '1';
	$rangeto = isset($search_keyword['named']['range_to']) ? $search_keyword['named']['range_to'] : '300+';
	$depositfrom = isset($search_keyword['named']['deposit_from']) ? $search_keyword['named']['deposit_from'] : '0';
	$depositto = isset($search_keyword['named']['deposit_to']) ? $search_keyword['named']['deposit_to'] : '300+';
	$keyword = isset($search_keyword['named']['keyword']) ? $search_keyword['named']['keyword'] : '';
	$cityy = isset($search_keyword['named']['city']) ? $search_keyword['named']['city'] : 'all';
	if (!empty($rangeto)) {
	  $this->request->data['Item']['range_to'] = $rangeto;
    }
  } else {
	$city = isset($this->request->params['named']['cityname']) ? $this->request->params['named']['cityname'] : '';
	$latitude = isset($this->request->params['named']['latitude']) ? $this->request->params['named']['latitude'] : '';
	$longitude = isset($this->request->params['named']['longitude']) ? $this->request->params['named']['longitude'] : '';
	$from = isset($this->request->params['named']['from']) ? $this->request->params['named']['from'] : '';
	$to = isset($this->request->params['named']['to']) ? $this->request->params['named']['to'] : '';
	$additional_guest = isset($this->request->params['named']['additional_guest']) ? $this->request->params['named']['additional_guest'] : '';
	$type = isset($this->request->params['named']['type']) ? $this->request->params['named']['type'] : '';
	$is_flexible = isset($this->request->params['named']['is_flexible']) ? $this->request->params['named']['is_flexible'] : '';
	$rangefrom = isset($this->request->params['named']['range_from']) ? $this->request->params['named']['range_from'] : '1';
	$rangeto = isset($this->request->params['named']['range_to']) ? $this->request->params['named']['range_to'] : '300+';
	$depositfrom = isset($this->request->params['named']['deposit_from']) ? $this->request->params['named']['deposit_from'] : '0';
	$depositto = isset($this->request->params['named']['deposit_to']) ? $this->request->params['named']['deposit_to'] : '300+';
	$keyword = isset($this->request->params['named']['keyword']) ? $this->request->params['named']['keyword'] : '';
	$cityy = isset($this->request->params['named']['city']) ? $this->request->params['named']['city'] : 'all';
	if (!empty($rangeto)) {
	$this->request->data['Item']['range_to'] = $rangeto;
	}
  }
  $network_level_arr = array(
	  '1' => 'st',
	  '2' => 'nd',
	  '3' => 'rd',
	  '4' => 'th',
  );
  for ($n = 1; $n <= Configure::read('item.network_level'); $n++) {
    $network_count = !empty($network_item_count[$n]) ? $network_item_count[$n] : 0;
    $networkLevels[$n] = $n . $network_level_arr[$n] . ' ' . __l('level') . ' (' . $network_count . ')';
  }
  if ($search == 'normal'):
    if(isset($this->request->params['named']['type']) && $this->request->params['named']['type']=='collection' && isPluginEnabled('Collections')):
	  echo $this->element('slider', array('config' => 'sec','items' => $items, 'collections' => $collections));
    elseif($allow):
	  echo $this->element('search', array('config' => 'sec','type'=>'search'));
    endif;
  endif;
  if((!empty($this->request->params['named']['latitude']) && !empty($this->request->params['named']['longitude'])) || (!empty($search_keyword['named']['latitude']) && !empty($search_keyword['named']['longitude']))) {
    if(empty($current_latitude)) {
	  $current_latitude=!empty($item['Item']['latitude'])?$item['Item']['latitude']:'';
	  $current_longitude=!empty($item['Item']['longitude'])?$item['Item']['longitude']:'';
    }
  }
} 
if($is_searching): ?>
  <section class="row no-mar">
	<div class="span24 no-mar pr <?php echo ($search == 'normal' || !empty($this->request->params['isAjax'])) ? 'js-responses  js-response' : ''; ?>">
	  <?php if($search == 'normal'): ?>
		<span class="js-search-lat {'cur_lat':'<?php echo $current_latitude; ?>','cur_lng':'<?php echo $current_longitude; ?>'}"></span>
	  <?php endif; ?>
	<div class="clearfix <?php if ($allow) { ?> items-index-page <?php } ?>">
	  <?php if ((!empty($search_keyword['named']['sw_latitude']))): ?>
		<div class="page-information"><?php echo __l('Narrow your search to street or at least city level to get better results.'); ?></div>
	  <?php endif;
	  if((isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'user' && $this->request->params['named']['type'] != 'related' && !isset($near_by) && $allow) || empty($this->request->params['named']) || (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'favorite' && isPluginEnabled('ItemFavorites'))) {
		if($allow): ?>
		  <div class="well space clearfix">
			<div class="js-toggle-show-block">
			  <h3 class="ver-space pull-left orangec textb text-16">
			    <a class="js-toggle-show {'container':'js-share-results'}" href="#"><?php echo __l('Share Results'); ?></a>
			  </h3>
			</div>
			<div class="pull-right graydarkerc top-space dropdown"><?php echo $this->Html->Cint($total_result); ?> <?php echo __l('results'); ?>
			  <?php 
			  $sortby = __l('Recent');
			  if((!empty($search_keyword['named']['latitude'])) ):
				$sortby = __l('Distance');
			  endif;
			  if(!empty($this->request->params['named']['sortby'])):
				if($this->request->params['named']['sortby'] == 'high') :
				  $sortby = __l('Price low to high');
				elseif($this->request->params['named']['sortby'] == 'low') :
				  $sortby = __l('Price high to low');
				else:
				  $sortby = ucfirst($this->request->params['named']['sortby']);
				endif;
			  endif;?>
			  <a href="#" data-toggle="dropdown" class="btn text-14 textb graylighterc no-shad hor-mspace dropdown-toggle" title="<?php echo $this->Html->cText($sortby, false); ?>">
				<span class="show right-space pull-left"><?php echo $this->Html->cText($sortby, false); ?></span>
				<span class="show pull-left"><i class="icon-caret-down no-pad no-mar"></i></span>
			  </a>
			  <ul class="dropdown-menu arrow arrow-right hor-mspace">
				<?php if((!empty($search_keyword['named']['latitude'])) ):
				  $class=((isset($this->request->params['named']['sortby'])&& $this->request->params['named']['sortby']=='distance') || !isset($this->request->params['named']['sortby']))?'active':''; ?>
				  <li  class="<?php echo $class; ?>"><?php echo $this->Html->link(__l('Distance'), array('controller' => 'items', 'action' => 'index',$hash,$salt,'sortby' =>'distance','admin' => false), array('title'=>$this->Html->cText('Distance',false),'escape' => false));	?>	</li>
				<?php endif;
				if(isPluginEnabled('ItemFavorites')) :
				  $class=(isset($this->request->params['named']['sortby'])&& $this->request->params['named']['sortby']=='favorites')?'active':''; ?>
				  <li class="<?php echo $class; ?>"><?php echo $this->Html->link(__l('Favorites'), array('controller' => 'items', 'action' => 'index',$hash,$salt,'sortby' =>'favorites',  'admin' => false), array('title'=>__l('Favorites'),'escape' => false));	?>	</li>
				<?php endif;
				$class=(isset($this->request->params['named']['sortby'])&& $this->request->params['named']['sortby']=='high')?'active':''; ?>
				<li class="<?php echo $class; ?>"><?php echo $this->Html->link(__l('Price low to high'), array('controller' => 'items', 'action' => 'index',$hash,$salt,'sortby' =>'high',  'admin' => false), array('title'=>__l('Price low to high'),'escape' => false));	?>	</li>
				<?php $class=(isset($this->request->params['named']['sortby'])&& $this->request->params['named']['sortby']=='low')?'active':''; ?>
				<li class="<?php echo $class; ?>"><?php echo $this->Html->link(__l('Price high to low'), array('controller' => 'items', 'action' => 'index',$hash,$salt,'sortby' =>'low', 'admin' => false), array('title'=>__l('Price high to low'),'escape' => false));	?>	</li>
				<?php $class=(isset($this->request->params['named']['sortby'])&& $this->request->params['named']['sortby']=='recent')?'active':''; ?>
				<li class="<?php echo $class; ?>"><?php echo $this->Html->link(__l('Recent'), array('controller' => 'items', 'action' => 'index',$hash,$salt,'sortby' =>'recent',  'admin' => false), array('title'=>__l('Recent'),'escape' => false));	?>	</li>
				<?php $class=(isset($this->request->params['named']['sortby'])&& $this->request->params['named']['sortby']=='reviews')?'active':''; ?>
				<li class="<?php echo $class; ?>"><?php echo $this->Html->link(__l('Reviews'), array('controller' => 'items', 'action' => 'index',$hash,$salt,'sortby' =>'reviews',  'admin' => false), array('title'=>__l('Reviews'),'escape' => false));	?>	</li>
				<?php $class=(isset($this->request->params['named']['sortby'])&& $this->request->params['named']['sortby']=='featured')?'active':''; ?>
				<li class="<?php echo $class; ?>"><?php echo $this->Html->link(__l('Featured'), array('controller' => 'items', 'action' => 'index',$hash,$salt,'sortby' =>'featured',  'admin' => false), array('title'=>__l('Featured'),'escape' => false));	?>	</li>
			  </ul>
			</div>
		  </div>
		  <div class="js-share-results hide clearfix">
			<div class="pr pull-left">
			  <?php if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'collection' && isPluginEnabled('Collections')) { 
				$slug = isset($this->request->params['named']['slug'])? $this->request->params['named']['slug']:$search_keyword['named']['slug'];
				$embed_code = Router::url('/',true).'collection/'.$slug;
			  } else {
				$embed_code = Router::url(array('controller'=>'items','action'=>'index',$hash,$salt), true);
			  }
			  echo $this->Form->input('share_url', array('class' => 'clipboard js-selectall', 'readonly' => 'readonly', 'label' => false, 'value' => $embed_code));?>
			  <span class="js-toggle-show {'container':'js-share-results'} share-close orangec pa"><i class="icon-remove-sign cur"></i></span>
			</div>
		  </div>
		<?php elseif(isset($this->request->params['named']['type']) && $this->request->params['named']['type']=='favorite' && isPluginEnabled('ItemFavorites')):
		  if(empty($this->request->params['isAjax'])):?>
			<h2 class="ver-space top-mspace text-32 sep-bot"><?php  echo __l('Liked') . ' ' . Configure::read('item.alt_name_for_item_plural_caps'); ?></h2>
		  <?php endif;
		endif;
	  } ?>
	  <?php if((isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'user' && $this->request->params['named']['type'] != 'related' && !isset($near_by) && $allow) || empty($this->request->params['named'])) { ?>
		<aside class="haccordion pa mob-ps">
		  <ul class="unstyled text-16">
			<?php echo $this->Form->create('Item', array('id'=> 'KeywordsSearchForm','class' => 'check-form js-search-map js-ajax-search-form norma keywords no-mar','action'=>'index')); ?>
			<li class="sep-bot">
			  <div class="graydarkc no-under" title="<?php echo __l('Refine'); ?>">
				<div id="accordion1" class="accordion no-mar">
				  <div class="space clearfix"><span class="accordion-menu cur pull-left"><span class="width22 pull-left show dc"><i class="icon-map-marker cur text-20 no-pad"></i></span><span class="hor-space left-mspace"><?php echo __l('Refine'); ?></span></span>
				    <div class="accordion-list thumbnail no-round no-pad pull-right"> <a href="#collapseOne" data-parent="#accordion1" data-toggle="collapse" class="whitec hor-smspace accordion-toggle js-toggle-icon"><i class="icon-plus no-pad graylightc text-10"></i></a></div>
				  </div>
			    </div>
			    <div id="collapseOne" class="accordion-body collapse">
			      <div class="thumbnail no-bor no-shad no-round space clearfix" id="CollectionCityNameSearch">
					  <div id="js-map-container"></div>
				    <a href="javascript:void(0);" class="show btn btn-large ver-smspace btn-primary textb text-16 map-button js-mapsearch-button" title="<?php echo __l('Update'); ?>"><?php echo __l('Update'); ?></a>
				    <div class="form-search ver-mspace  clearfix">
				      <div class="input text"> 
					    <span class="span no-mar">
					      <?php echo $this->Form->input('Item.keyword', array('placeholder' =>__l('Keyword'),'label'=>false,'div'=>false,'value'=>$keyword,'class'=>'span4 text-16')); ?>
					    </span>
				      </div>
				      <a href="javascript:void(0);" id="sicon" class="pull-right mob-clr show btn btn-large textb text-16 js-submit-button" title="<?php echo __l('Search');?>"><i class="icon-search no-pad no-mar textb text-16"></i></a>
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
									$sub_categories = $this->Html->getSubCategoriesList($category['Category']['id'], 'Item', $hash, $salt);
									$total_count = $sub_categories['total_count'];
									unset($sub_categories['total_count']);
									if(!empty($this->request->data['Item']['Category'])) {
										$sub_categories_keys = array_keys($sub_categories);
										$this->request->data['Item']['Category'][$category['Category']['id']] = array_intersect($this->request->data['Item']['Category'], $sub_categories_keys);
									}
									if(!empty($this->request->data['Item']['Category'][$category['Category']['id']]) && count($this->request->data['Item']['Category'][$category['Category']['id']]) > 0) {
										$this->request->data['Item']['parent_category_id'][$category['Category']['id']] = 1;
									}
							?>
								<h5 class="textb grayc top-smspace clearfix">
									<?php echo $this->Form->input('Item.parent_category_id.'.$category['Category']['id'], array('type' => 'checkbox', 'class' => 'js-filter-categroy', 'hiddenField' => false, 'div' => 'pull-left main-category input checkbox no-mar', 'data-category_id' => $category['Category']['id'], 'label' => $category['Category']['name'] . ' (' . $total_count . ')'));?>
									<span class="pull-right js-category-toggle" data-category_id="<?php echo $this->Html->cInt($category['Category']['id'], false); ?>"><i class="icon-chevron-down cur text-12"></i></span>
								</h5>
								<div class="js-sub-category-block-<?php echo $this->Html->cInt($category['Category']['id'], false); ?> hide">
								<?php 
									echo $this->Form->input('Item.Category.'.$category['Category']['id'], array('type'=>'select', 'options' => $sub_categories, 'multiple'=>'checkbox', 'class'=>'show left-mspace checkbox clearfix js-subcategory-lists-' . $category['Category']['id'], 'label' =>false));
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
				  <div class="space clearfix"> <span class="accordion-menu cur pull-left"><span class="width22 pull-left show dc"><i class="icon-money cur text-1818 no-pad"></i></span><span class="hor-space left-mspace"><?php echo $this->Html->cText(__l('Price Range'), false); ?></span></span>
					<div class="accordion-list thumbnail no-round no-pad pull-right"> <a href="#collapseFive" data-parent="#accordion5" data-toggle="collapse" class="whitec hor-smspace accordion-toggle js-toggle-icon"><i class="icon-plus no-pad graylightc text-10"></i></a></div>
				  </div>
				</div>
				<div id="collapseFive" class="accordion-body collapse">
				  <div class="thumbnail no-bor no-shad no-round space clearfix">
					<div class="bot-mspace">
					  <div class="graydarkerc text-14">
						<div class="price-range-info-block dc">
						  <span class="price-range tb"><?php echo __l('Price range ');?></span>
						  <span class="js-rang-from"><?php echo $rangefrom; ?></span><?php echo __l(' to '); ?><span class="js-rang-to"><?php echo $rangeto; ?></span>
						</div>
						<div class="clearfix">
						  <?php echo $this->Form->input('Item.range_from', array('type'=>'hidden', 'id'=>'js-range_from', 'label' =>false)); ?>
						  <?php echo $this->Form->input('Item.range_to', array('type'=>'hidden', 'id'=>'js-range_to', 'label' =>false)); ?>
						  <?php echo $this->Form->input('Item.price_range', array('type'=>'select', 'data-slider_min' => 1, 'data-slider_max' => 301, 'id'=>'js-price-range', 'label' =>false, 'class' => 'js-uislider hide')); ?>
						</div>
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			</li>
			<?php if (!empty($_SESSION['network_level']) || ($this->Auth->user('id') && !$this->Auth->user('is_facebook_friends_fetched'))): ?>
			  <li class="sep-bot">
				<div class="graydarkc no-under" title="<?php echo __l('Social Networks'); ?>">
				  <div id="accordion10" class="accordion no-mar">
					<div class="space clearfix"> <span class="accordion-menu cur pull-left"><span class="width22 pull-left show dc"><i class="icon-globe cur text-18 no-pad"></i></span><span class="hor-space left-mspace"><?php echo __l('Social Networks'); ?></span></span>
					  <div class="accordion-list thumbnail no-round no-pad pull-right"> <a href="#collapseTen" data-parent="#accordion10" data-toggle="collapse" class="whitec hor-smspace accordion-toggle js-toggle-icon"><i class="icon-plus no-pad graylightc text-10"></i></a></div>
					</div>
				  </div>
				  <div id="collapseTen" class="accordion-body collapse">
					<div class="thumbnail no-bor no-shad no-round space clearfix">
					  <div class="bot-mspace">
						<div class="graydarkerc text-14"> 
						  <?php if (!empty($_SESSION['network_level'])): ?>
							<?php echo $this->Form->input('Item.network_level', array('type' => 'select', 'multiple' => 'checkbox', 'id' => 'SocialNetworks', 'options' => $networkLevels, 'label' => false)); ?>
						  <?php elseif ($this->Auth->user('id') && !$this->Auth->user('is_facebook_friends_fetched')): ?>
							<div class="social-network-connect">
							  <?php echo $this->Html->link(__l('Connect with Facebook'), $fb_login_url, array('class' => 'facebook-connect-link', 'title' => __l('Connect with Facebook'))); ?>
							  <?php echo '<span>' . ' ' . __l('to filter by Social Network level') . '</span>'; ?>
							</div>
						  <?php endif; ?>
						</div>
					  </div>
					</div>
				  </div>
				</div>
			  </li>
			<?php endif; ?> 
			<li class="sep-bot">
			  <div class="graydarkc no-under" title="<?php echo __l('Languages Spoken'); ?>">
				<div id="accordion9" class="accordion no-mar">
				  <div class="space clearfix"> <span class="accordion-menu cur pull-left"><span class="width22 pull-left show dc"><i class="icon-group cur text-18 no-pad"></i></span><span class="hor-space left-mspace"><?php echo __l('Languages Spoken'); ?></span></span>
					<div class="accordion-list thumbnail no-round no-pad pull-right"> <a href="#collapseNine" data-parent="#accordion9" data-toggle="collapse" class="whitec hor-smspace accordion-toggle js-toggle-icon"><i class="icon-plus no-pad graylightc text-10"></i></a></div>
				  </div>
				</div>
				<div id="collapseNine" class="accordion-body collapse">
				  <div class="thumbnail no-bor no-shad no-round space clearfix">
					<div class="bot-mspace">
					  <div class="graydarkerc text-14"> 
						<?php echo $this->Form->input('Item.language', array('type'=>'select', 'multiple'=>'checkbox', 'class'=>'show top-mspace checkbox clearfix',  'label' =>false)); ?>
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			</li>
			<?php echo $this->Form->input('cityName', array('type' => 'hidden',	'id' => 'city_index', 'value' => $city));
			echo $this->Form->input('latitude', array('type' => 'hidden', 'value' => $latitude));
			echo $this->Form->input('longitude', array('type' => 'hidden', 'value' => $longitude));
			echo $this->Form->input('from', array('type' => 'hidden', 'value' => $from));
			echo $this->Form->input('to', array('type' => 'hidden', 'value' => $to));
			echo $this->Form->input('type', array('id' => 'type', 'type' => 'hidden', 'value' => !empty($search_keyword['named']['type']) ? $search_keyword['named']['type'] : !empty($this->request->params['named']['type']) ? $this->request->params['named']['type'] : ''));
			$type = !empty($search_keyword['named']['type']) ? $search_keyword['named']['type'] : !empty($this->request->params['named']['type']) ? $this->request->params['named']['type'] : '';
			if ($type == 'collection' && isPluginEnabled('Collections')) {
			  echo $this->Form->input('slug', array('type' => 'hidden', 'value' => $collections['Collection']['slug']));
			}
			echo $this->Form->input('city', array('type' => 'hidden', 'value' => $cityy));
			echo $this->Form->input('ne_longitude', array('type' => 'hidden', 'id' => 'ne_longitude_index'));
			echo $this->Form->input('sw_longitude', array('type' => 'hidden', 'id' => 'sw_longitude_index'));
			echo $this->Form->input('sw_latitude', array('type' => 'hidden', 'id' => 'sw_latitude_index'));
			echo $this->Form->input('ne_latitude', array('type' => 'hidden', 'id' => 'ne_latitude_index'));?>
			<div class="submit hide">
			  <?php echo $this->Form->submit(__l('Search'),array('div'=>false)); ?>
			</div>
			<?php echo $this->Form->end(); ?>
		  </ul>
		</aside>
	  <?php }
	  $sectionRight = ' span23 pull-right';
	  if((!empty($this->request->params['isAjax']))) {
		$sectionRight = ' span23 pull-right';
	  }
	  if(isset($this->request->params['named']['type']) && $this->request->params['named']['type']=='favorite')
		$sectionRight = '';?>
	  <section  id="Items" class="mob-clr  bot-space <?php echo $sectionRight; ?>">
		<?php $view_count_url = Router::url(array('controller' => 'items',	'action' => 'update_view_count'), true); ?>
		<ol class="unstyled prop-list-mob prop-list no-mar js-view-count-update {'model':'item','url':'<?php echo $this->Html->cText($view_count_url, false); ?>'}" start="<?php echo $this->Paginator->counter(array('format' => '%start%'));?>" >
		  <?php	if (!empty($items)):
			$i = 0;
			$num= $this->Paginator->counter(array('format' => '%start%'));
			foreach ($items as $item):
			  $class = null;
			  if ($i++ % 2 == 0) {
				$class = ' altrow';
			  }
			  if ($item['Item']['is_featured']) {
				$featured_class='featured';
			  } else {
				$featured_class='';
			  }	?>
			  <li class="clearfix ver-space sep-bot left-mspace mob-no-mar js-map-num <?php echo $this->Html->cText($num, false); ?>">
				<div class="span dc no-mar mob-no-pad"> <span class="label label-important textb show text-11 prop-count map_number "><?php echo $this->Html->cText($num, false); ?> </span> 
				  <?php if(isPluginEnabled('ItemFavorites')) :
					if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))):?>
					  <div class="alpuf-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide">
						<?php echo $this->Html->link('<i class="icon-star no-pad text-18"></i>', array('controller' => 'item_favorites', 'action'=>'delete', $item['Item']['slug']), array('escape' => false ,'class' => 'js-like js-no-pjax un-like top-space show no-under', 'title' => __l('Unlike'))); ?>
					  </div>
			 		  <div class="alpf-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide">
						<?php echo $this->Html->link('<i class="grayc icon-star-empty no-pad text-18"></i>', array('controller' => 'item_favorites', 'action' => 'add', $item['Item']['slug']), array('escape' => false ,'title' => __l('Like'),'escape' => false ,'class' =>'js-like js-no-pjax like top-space show grayc no-under')); ?>
					  </div>
			  		  <div class='blpf-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide'>
						<?php echo $this->Html->link('<i class="grayc icon-star-empty no-pad text-18"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f='. $this->request->url), array('title' => __l('Like'),'escape' => false ,'class' =>'like top-space show graylightc no-under')); ?>
					  </div>
					<?php else: ?>
					  <span>
						<?php if($this->Auth->sessionValid()):
						  if(!empty($item['ItemFavorite'])):
							foreach($item['ItemFavorite'] as $favorite):
							  if($item['Item']['id'] == $favorite['item_id'] && $item['Item']['user_id'] != $this->Auth->user('id')):
								echo $this->Html->link('<i class="icon-star no-pad text-18"></i>', array('controller' => 'item_favorites', 'action'=>'delete', $item['Item']['slug']), array('escape' => false ,'class' => 'js-like js-no-pjax un-like top-space show no-under', 'title' => __l('Unlike')));
							  endif;
							endforeach;
						  else:
							if( $item['Item']['user_id'] != $this->Auth->user('id')):
							  echo $this->Html->link('<i class="grayc icon-star-empty no-pad text-18"></i>', array('controller' => 'item_favorites', 'action' => 'add', $item['Item']['slug']), array('title' => __l('Like'),'escape' => false ,'class' =>'js-like js-no-pjax like top-space show grayc no-under'));
							endif;
						  endif;
						else:
						  echo $this->Html->link('<i class="grayc icon-star-empty no-pad text-18"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f='. $this->request->url), array('title' => __l('Like'),'escape' => false ,'class' =>'like top-space show graylightc no-under'));
						endif;?>
					  </span>
					<?php endif;
				  endif;
				  if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))):?>
					<div class="aloed-<?php echo $this->Html->cInt($item['Item']['id'], false); ?> hide">
					  <div class="dropdown">
					    <a href="#" title="Edit" class="dropdown-toggle text-14 textb graylighterc no-shad" data-toggle="dropdown"><i class="icon-cog graylightc no-pad text-16"></i></a>
						<ul class="dropdown-menu dl arrow">
						  <li><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action'=>'edit', $item['Item']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit'),'escape'=>false));?></li>
						  <li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action'=>'delete', $item['Item']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete'),'escape'=>false));?> </li>
						</ul>
					  </div>
					</div>
				  <?php else:
					if ($item['Item']['user_id'] == $this->Auth->user('id')) : ?>
					  <div class="dropdown">
					    <a href="#" title="<?php echo __l('Edit'); ?>" class="dropdown-toggle text-14 textb graylighterc no-shad" data-toggle="dropdown"><i class="icon-cog graylightc no-pad text-16"></i></a>
						<ul class="dropdown-menu dl arrow">
						  <li><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action'=>'edit', $item['Item']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit'),'escape'=>false));?></li>
						  <li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action'=>'delete', $item['Item']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete'),'escape'=>false));?> </li>
						</ul>
					  </div>
					<?php endif; 
				  endif;?>
				</div>
				<div class="span hor-smspace dc mob-no-mar">
				  <?php $item['Attachment'][0] = !empty($item['Attachment'][0]) ? $item['Attachment'][0] : array();
				  echo $this->Html->link($this->Html->showImage('Item', $item['Attachment'][0], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'],$hash, $salt,  'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false, 'class' => 'prop-img'));?>
				</div>
				<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'favorite')
					$spanClass = " span19 ";
				else
					$spanClass = " span18 ";
				?>
				<div class="<?php echo $spanClass; ?> pull-right no-mar mob-clr tab-clr">
				  <div class="clearfix left-mspace sep-bot">
					<div class="span7 no-mar">
					  <h4 class="textb text-16 ">
						<div class="htruncate bot-space clearfix span9 no-mar dl" data-placement="bottom">
						  <?php	
						  $lat = $item['Item']['latitude'];
						  $lng = $item['Item']['longitude'];
						  $id = $item['Item']['id'];
						  echo $this->Html->link($this->Html->cText($item['Item']['title'], false), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], $hash, $salt, 'admin' => false), array('id'=>"js-map-side-$id",'class'=>"js-bootstrap-tooltip graydarkc js-map-data {'lat':'$lat','lng':'$lng'}",'data-container'=>"body",'title'=>$this->Html->cText($item['Item']['title'], false),'escape' => false));?>
						</div>
					  </h4>
					  <div class="clearfix bot-space dc">
						<?php $flexible_class = '';
						if(isset($search_keyword['named']['is_flexible'])&& $search_keyword['named']['is_flexible'] ==1 && !empty($search_keyword['named']['latitude'])) {
						  if(!in_array($item['Item']['id'], $booked_item_ids) && in_array($item['Item']['id'], $exact_ids)) {?>
							<span class="label pull-left mob-inline right-mspace"><?php echo __l('exact'); ?></span> 
						  <?php	}
						}
						if ($item['Item']['is_featured']): ?>
						  <span class="label featured pull-left  mob-inline"> <?php echo __l('Featured'); ?></span>
						<?php endif; ?>
					  </div>
						<p class="htruncate js-bootstrap-tooltip no-mar span7 graydarkc" title="<?php echo $this->Html->cHtml($item['Item']['address'], false);?>">
						  <?php if(!empty($item['Country']['iso2'])): ?>
							<span class="flags flag-<?php echo $this->Html->cText(strtolower($item['Country']['iso2']), false); ?> mob-inline top-smspace" title="<?php echo $this->Html->cText($item['Country']['name'], false); ?>"><?php echo $this->Html->cText($item['Country']['name'], false); ?></span>
						  <?php endif; ?>
						  <?php echo $this->Html->cHtml($item['Item']['address'], false);?>
						</p>
					</div>
					<div class="pull-right span10 dr mob-clr mob-sep-none">
						<div class="pull-right sep-left mob-clr mob-sep-none">
						  <?php 
							$label = "&nbsp;";
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
						  ?>
						  <dl class="dc list span mob-clr">
							<dt class="pr hor-mspace text-11"><?php echo (!empty($price) && $price > 0) ? $label : '&nbsp;';?></dt>
							<dd class="textb text-24 graydarkc pr hor-mspace">
							  <?php if (!empty($price) && $price > 0) { ?>
							  <?php if (Configure::read('site.currency_symbol_place') == 'left'): ?>
								<?php echo Configure::read('site.currency').' '?>
							  <?php endif; ?>
							  <?php echo $this->Html->cCurrency($price);?>
							  <?php if (Configure::read('site.currency_symbol_place') == 'right'): ?>
								<?php echo ' '.Configure::read('site.currency'); ?>
							  <?php endif; ?>
							  <?php } else { ?>
								<?php
									if(!empty($item['Item']['is_have_definite_time'])) {
										echo __l('Free'); 
									} else {
										echo __l('Request'); 
									}
								?>
							  <?php }?>
							</dd>
						  </dl>
						</div>
						<?php if(!empty($item['CustomPricePerNight'])) { ?>
						<div class="pull-right span10 right-mspace">
							<?php 
								// 10 more needed to start
								$nights = '';
								$more_child = 1;
								if($item['Item']['is_have_definite_time'] && $item ['Item']['is_people_can_book_my_time']) {
								$parent_index = count($item['CustomPricePerNight']) - 1;
								$str_end_data = strtotime($item['CustomPricePerNight'][1]['end_date']);
									if(!$item['CustomPricePerNight'][$parent_index]['is_timing']){
										$nights .=	$this->Html->cDate($item['CustomPricePerNight'][1]['start_date'], 'span', true) . ' '. $this->Html->cTime($item['CustomPricePerNight'][1]['start_time'], 'span', true). ' - ';
										$is_timing_date = $this->Html->cDate($item['CustomPricePerNight'][1]['start_date'], 'span', true) . ' '. $this->Html->cTime($item['CustomPricePerNight'][1]['start_time'], 'span', true). ' - ';
										if(!empty($str_end_data)){
											$nights .= $this->Html->cDate($item['CustomPricePerNight'][1]['end_date'], 'span', true) . ' ';
											$is_timing_end_date = $this->Html->cDate($item['CustomPricePerNight'][1]['end_date'], 'span', true) . ' ';
											$is_timing_date = $is_timing_date . ' ' . $is_timing_end_date;
										}
										$nights .= $this->Html->cTime($item['CustomPricePerNight'][1]['end_time'], 'span' , true);
										$is_timing_end_time = $this->Html->cTime($item['CustomPricePerNight'][1]['end_time'], 'span', true);
										$date_and_time = $is_timing_date . ' ' . $is_timing_end_time;
									} else {
										$nights .= $this->Html->cDate($item['CustomPricePerNight'][1]['start_date'], 'span', true) ;
										$any_time_start_date = $this->Html->cDate($item['CustomPricePerNight'][1]['start_date'], 'span', true) ;
										if(!empty($str_end_data)){
											$nights .= ' - '.$this->Html->cDate($item['CustomPricePerNight'][1]['end_date'], 'span', true);
											$any_time_end_date = $this->Html->cDate($item['CustomPricePerNight'][1]['end_date'], 'span', true);
											$any_time_start_date = $any_time_start_date . ' ' . $any_time_end_date;
										}
										$nights .= $this->Html->cTime($item['CustomPricePerNight'][1]['start_time'], 'span', true)  . ' - ' . $this->Html->cTime($item['CustomPricePerNight'][1]['end_time'], 'span', true);
										$any_time_end_time = $this->Html->cTime($item['CustomPricePerNight'][1]['start_time'], 'span', true)  . ' - ' . $this->Html->cTime($item['CustomPricePerNight'][1]['end_time'], 'span', true);
										$date_and_time = $any_time_start_date . ' ' . $any_time_end_time;
									}									
									$more_child = $parent_index;
								} else {
									$date_and_time = $this->Html->cDate($item['CustomPricePerNight'][0]['start_date'], 'span', true) . ' '.$this->Html->cTime($item['CustomPricePerNight'][0]['CustomPricePerType'][0]['start_time'], 'span', true). ' - '. $this->Html->cDate($item['CustomPricePerNight'][0]['end_date'], 'span', true) . ' '. $this->Html->cTime($item['CustomPricePerNight'][0]['CustomPricePerType'][0]['end_time'], 'span', true);
									$nights .= $date_and_time;
									$more_child = count($item['CustomPricePerNight']) - 1;
								}
								if(!empty($item['CustomPricePerNight'][0]['repeat_days'])) {
									$repeat_days = explode(',', $item['CustomPricePerNight'][0]['repeat_days']);
									if(count($repeat_days) < 7) {
										$nights .= ' '.__l('on') . ' ' . implode(', ', $repeat_days);
									} else {
										$nights .= ' '.__l('on') . ' ' . __l('All days');
									}
								} else {
									$nights .= '';
								}
								$more_child_count = $more_child;
								if($more_child_count > 0) {
									$title = $nights;
								} else {
									$title = $nights;
								}
								$more = '';
								if($more_child_count > 0) { 
									$more = ' (+ '.$more_child_count.' ' . __l('more') . ')';
								} else { 
									$more = __l('more');
								}
							?>
							<p class="js-bootstrap-tooltip" title="<?php echo $this->Html->cText($title, false); ?>">
							<?php echo $title; ?></p>
							<div class="pull-right graydarkerc dropdown block-dropdown">
								<a href="<?php echo Router::url(array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'view_type' => 'price-view', 'admin' => false)); ?>" data-toggle="dropdown" data-trigger="#js-ratedetails-<?php echo $item['Item']['id'];?>", class="text-14 textb no-shad dropdown-toggle js-ratedetails js-no-pjax" title="<?php echo $this->Html->cText($more, false); ?>">
									<span class=""><?php echo $more; ?></span>
									<span class=""><i class="icon-caret-down no-pad no-mar"></i></span>
								</a>
									<div class="bot-mspace clearfix space dropdown-menu arrow arrow-right" id="js-ratedetails-<?php echo $item['Item']['id'];?>">
										  <!-- rate details content from ajax-->
									</div>						
							</div>
						</div>
						<?php } ?>
					</div>
				  </div>
				  <div class="clearfix left-mspace">
					<ul class="unstyled mob-inline medium-thumb mob-clr top-space clearfix pull-left">
						<?php
							$i = 0;
							for($i = 0; $i<6; $i++){
								if(!empty($item['User']['UserComment'][$i])) {
									if($i != 5) {
						?>
							<li class="pull-left">
								<?php echo $this->Html->getUserAvatar($item['User']['UserComment'][$i]['PostedUser'], 'medium_thumb', true, '', 'admin','','',false);?>
							</li>	
						<?php
									} else {
						?>
							<li class="pull-left sep dc">
								<?php echo  $this->Html->link(__l("More"), array('controller' => 'users', 'action' => 'view', $item['User']['username'], 'admin' => false, '#Recommendations'), array('target' => '_blank', 'class'=>'more show text-9', 'title' => __l("More"), 'escape' => false));
								?>
							</li>
						<?php
									}
								} else {
									?>
							<li class="pull-left sep"></li>
									<?php
								}
						}	
						?>
					</ul>
					<div class="clearfix pull-right top-mspace mob-clr">
					  <?php if((!empty($search_keyword['named']['latitude']) || isset($near_by)) && !empty($item[0]['distance'])){?>
						<dl class="dc mob-clr sep-right list">
						  <dt class="pr hor-mspace text-11"><?php echo __l('Distance');?> <?php echo __l('(km)');?></dt>
						  <dd class="textb text-16 no-mar graydarkc pr hor-mspace"><?php echo $this->Html->cInt($item[0]['distance']*1.60934 ); ?></dd>
						</dl>
					  <?php } ?>
					  <dl class="dc mob-clr sep-right list">
						<dt class="pr hor-mspace text-11" ><?php echo __l('Views');?></dt>
						<dd class="textb text-16 no-mar graydarkc pr hor-mspace js-view-count-item-id js-view-count-item-id-<?php echo $this->Html->cInt($item['Item']['id'], false); ?> {'id':'<?php echo $this->Html->cInt($item['Item']['id'], false); ?>'}"><?php echo numbers_to_higher($item['Item']['item_view_count']); ?></dd>
					  </dl>
					  <dl class="dc mob-clr sep-right list">
						<dt class="pr hor-smspace text-11" ><?php echo __l('Positive');?></dt>
						<dd  class="textb text-16 no-mar graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['positive_feedback_count']); ?></dd>
					  </dl>
					  <dl class="dc mob-clr sep-right list">
						<dt class="pr hor-mspace text-11"><?php echo __l('Negative');?></dt>
						<dd class="textb text-16 no-mar graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['item_feedback_count'] - $item['Item']['positive_feedback_count']); ?></dd>
					  </dl>
					  <dl class="dc mob-clr list">
						<dt class="pr mob-clr hor-mspace text-11"><?php echo __l('Success Rate');?></dt>
						<?php if(empty($item['Item']['item_feedback_count'])){ ?>
						  <dd  class="textb text-16 no-mar graydarkc pr hor-mspace"><?php echo __l('n/a');?></dd>
						<?php }else{ ?>
						  <dd class="textb text-16 no-mar graydarkc pr hor-mspace">
							<?php if(!empty($item['Item']['positive_feedback_count'])){
							  $positive = floor(($item['Item']['positive_feedback_count']/$item['Item']['item_feedback_count']) *100);
							  $negative = 100 - $positive;
							}else{
							  $positive = 0;
							  $negative = 100;
							}
							echo	$this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&amp;chf=bg,s,FFFFFF00', array('width'=>'35px','height'=>'35px','title' => $positive.'%'));?>
						  </dd>
						<?php } ?>
					  </dl>
					  <?php if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))){?>
						<dl class="dc mob-clr list sep-left">
						  <dt class="pr hor-mspace text-11" title="<?php echo __l('Network Level'); ?>"><?php echo __l('Network'); ?></dt>
						  <dd class="textb text-16 no-mar graydarkc pr hor-mspace blfbr-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide" title="<?php  echo __l('Connect with Facebook to find your friend level with host'); ?>"><?php  echo '?'; ?></dd>
						  <dd class="textb text-16 no-mar graydarkc pr hor-mspace alfbr-fb-e-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide" title="<?php  echo __l('Enable Facebook friends level display in social networks page'); ?>"><?php  echo '?'; ?></dd>
						  <dd class="textb text-16 no-mar graydarkc pr hor-mspace alfbr-fb-d-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide" title="<?php  echo __l('Host is not connected with Facebook'); ?>"><?php  echo '?'; ?></dd>
						  <dd class="textb text-16 no-mar graydarkc pr hor-mspace alfbr-fb-nl-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide" title="<?php  echo __l('Network Level'); ?>"><?php  echo !empty($network_level[$item['Item']['user_id']]) ? $network_level[$item['Item']['user_id']] : ''; ?></dd>
						  <dd class="textb text-16 no-mar graydarkc pr hor-mspace alfbr-fb-na-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide" title="<?php  echo __l('Not available'); ?>"><?php  echo __l('n/a'); ?></dd>
						</dl>
					  <?php }else{ ?>
						<?php if ($this->Auth->user('id') != $item['Item']['user_id']): ?>
						  <dl class="dc mob-clr sep-left list">
							<dt class="pr hor-mspace text-11" title="<?php echo __l('Network Level'); ?>"><?php echo __l('Network'); ?></dt>
							<?php if (!$this->Auth->user('is_facebook_friends_fetched')): ?>
							  <dd class="textb text-16 no-mar graydarkc pr hor-mspace" title="<?php  echo __l('Connect with Facebook to find your friend level with host');	?>"><?php  echo '?'; ?></dd>
							<?php elseif(!$this->Auth->user('is_show_facebook_friends')): ?>
							  <dd class="textb text-16 no-mar graydarkc pr hor-mspace" title="<?php  echo __l('Enable Facebook friends level display in social networks page'); ?>"><?php  echo '?'; ?></dd>
							<?php elseif(empty($item['User']['is_facebook_friends_fetched'])): ?>
							  <dd class="textb text-16 no-mar graydarkc pr hor-mspace" title="<?php  echo __l('Host is not connected with Facebook'); ?>"><?php  echo '?'; ?></dd>
							<?php elseif(!empty($network_level[$item['Item']['user_id']])): ?>
							  <dd class="textb text-16 no-mar graydarkc pr hor-mspace" title="<?php  echo __l('Network Level'); ?>"><?php  echo $this->Html->cInt($network_level[$item['Item']['user_id']], false); ?></dd>
							<?php else: ?>
							  <dd class="textb text-16 no-mar graydarkc pr hor-mspace" title="<?php  echo __l('Not Available'); ?>"><?php  echo __l('n/a'); ?></dd>
							<?php endif; ?>
						  </dl>
						<?php endif;
					  } ?>
					</div>
				  </div>
				</div>
			  </li>
			  <?php $num++;
			endforeach;?>
		  <?php else:?>
		  	<li <?php if(empty($this->request->params['isAjax']) && $search == 'normal') { ?>class="sep-top" <?php } ?>>
			  <div class="space dc grayc">
				<?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'search' && isPluginEnabled('Requests')): ?>
				  <p class="ver-mspace top-space text-16"><?php echo sprintf(__l('No %s available. You may %s on this address for others to respond.'), Configure::read('item.alt_name_for_item_plural_small'), $this->Html->link(__l('create a request'), array('controller' => 'requests', 'action' => 'add', $hash,$salt,'admin' => false), array('title'=>__l('create a request'))));?></p>
				<?php else: ?>
				  <p class="ver-mspace top-space text-16"> <?php echo sprintf(__l('No %s available'), Configure::read('item.alt_name_for_item_plural_small')); ?> </p>
				<?php endif; ?>
			  </div>
			</li>
		  <?php endif; ?>
		</ol>
		<?php if (!empty($items)) {?>
		  <div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?> paging clearfix space pull-right mob-clr"> <?php echo $this->element('paging_links'); ?> </div>
		<?php }?>	
	  </section>
	</div>
  </section>
<?php else:?>
  <div class="page-information alert"><?php echo __l('Please enter your search criteria'); ?></div>
<?php endif;?>
<?php if(empty($this->request->params['isAjax'])) { ?>
<?php if (Configure::read('widget.browse_script')) { ?>
  <div class="dc clearfix bot-space">
    <?php echo Configure::read('widget.browse_script'); ?>
  </div>
<?php } ?>
<?php } ?>