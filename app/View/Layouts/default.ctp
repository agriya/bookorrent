<!DOCTYPE html>
<html lang="<?php echo isset($_COOKIE['CakeCookie']['user_language']) ?  strtolower($_COOKIE['CakeCookie']['user_language']) : strtolower(Configure::read('site.language')); ?>">
  <head>
  <?php echo $this->Html->charset(), "\n";?>
  <title><?php echo $this->Html->cText(Configure::read('site.name'), false) . ' | ' . $title_for_layout; ?></title>
  <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
  <!--[if lt IE 9]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.6.1/html5shiv.js"></script>
  <![endif]-->
  <?php
    echo $this->Html->meta('icon'), "\n";
  ?>
  <?php
    if (!empty($meta_for_layout['keywords'])):
      echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n";
    endif;
  ?>
  <?php
    if (!empty($meta_for_layout['description'])):
      echo $this->Html->meta('description', $meta_for_layout['description']), "\n";
    endif;
  ?>
  <link rel="apple-touch-icon" href="<?php echo Router::url('/'); ?>apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?php echo Router::url('/'); ?>apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="114x114" href="<?php echo Router::url('/'); ?>apple-touch-icon-114x114.png" />
  <link rel="logo" type="images/svg" href="<?php echo Router::url('/'); ?>img/logo.svg"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--[if IE]>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <![endif]-->  
  <link href="//fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" media="all" />
  <link href="<?php echo Router::url(array('controller' => 'feeds', 'action' => 'index', 'ext' => 'rss'), true);?>" type="application/rss+xml" rel="alternate" title="RSS Feeds"/>
  <?php echo $this->fetch('seo_paging'); 
  $lang = isset($_COOKIE['CakeCookie']['user_language']) ?  $_COOKIE['CakeCookie']['user_language'] : Configure::read('site.language');
  echo $this->Html->css('default.cache.'.Configure::read('site.version'), null, array('inline' => true, "media" => "all")); ?>
  <!--[if IE 7]>
    <?php echo $this->Html->css('font-awesome-ie7.css', null, array('inline' => true, "media" => "all")); ?>
  <![endif]-->
  <?php
    $cms = $this->Layout->js();
	$js_inline = 'var cfg = ' . $this->Js->object($cms) . ';';
    $js_inline .= "document.documentElement.className = 'js';";
    $js_inline .= "(function() {";
    $js_inline .= "var js = document.createElement('script'); js.type = 'text/javascript'; js.async = true;";
    $js_inline .= "js.src = \"" . $this->Html->assetUrl('default.cache.'.Configure::read('site.version').'.'.$lang, array('pathPrefix' => JS_URL, 'ext' => '.js')) . "\";";
    $js_inline .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(js, s);";
    $js_inline .= "})();";
	echo $this->Javascript->codeBlock($js_inline, array('inline' => true));
    // For other than Facebook (facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)), wrap it in comments for XHTML validation...
    if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false || strpos(env('HTTP_USER_AGENT'), 'LinkedInBot')===false):
    echo '<!--', "\n";
    endif;
  ?>
  <meta content="<?php echo Configure::read('facebook.app_id');?>" property="og:app_id" />
  <meta content="<?php echo Configure::read('facebook.app_id');?>" property="fb:app_id" />
  <?php if (!empty($meta_for_layout['item_name'])) { ?>
    <meta property="og:title" content="<?php echo $meta_for_layout['item_name'];?>"/>
  <?php } ?>
<?php if (!empty($meta_for_layout['view_image'])) { ?>
<meta property="og:image" content="<?php echo $meta_for_layout['view_image'];?>"/>
<?php } else { ?>
<meta property="og:image" content="<?php echo Router::url('/', true) . 'img/logo.png';?>"/>
<?php } ?>
  <meta property="og:site_name" content="<?php echo $this->Html->cText(Configure::read('site.name'), false); ?>"/>
<?php if (Configure::read('facebook.fb_user_id')): ?>
  <meta property="fb:admins" content="<?php echo Configure::read('facebook.fb_user_id'); ?>"/>
<?php endif; ?>
  <?php
    if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false || strpos(env('HTTP_USER_AGENT'), 'LinkedInBot')===false):
    echo '-->', "\n";
    endif;
  ?>
  <?php
    echo $this->element('site_tracker', array('cache' => array('config' => 'sec')));
    $response = Cms::dispatchEvent('View.IntegratedGoogleAnalytics.pushScript', $this);
    echo !empty($response->data['content']) ? $response->data['content'] : '';
  ?>
  <?php echo $scripts_for_layout; ?>
<?php
	if (env('HTTP_X_PJAX') != 'true') {
		echo $this->fetch('highperformance');
	}
?>
  <!--[if IE]><?php echo $this->Javascript->link('libs/excanvas.js', true); ?><![endif]-->
</head>
<body>
	<!--[if lt IE 9]>
		<script> document.getElementsByTagName('body')[0].className = "lt-ie"; </script>
	<![endif]--> 
	<div id="<?php echo $this->Html->getUniquePageId();?>" class="content clearfix">
	<div class="wrapper">
		<?php if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))) { ?>
			<div class="alab hide">
				<div class="clearfix admin-wrapper pr">
					<ul class="pull-left unstyled clearfix  mob-clr">
					<li class="text-16">
						<?php echo $this->Html->link(($this->Html->cText(Configure::read('site.name'), false).' '.'<span class="sfont">Admin</span>'), array('controller' => 'users', 'action' => 'stats', 'admin' => true), array('escape' => false, 'class' => 'js-no-pjax  mob-clr mob-dc', 'title' => (Configure::read('site.name').' '.'Admin')));?>
					</li>
					</ul>
					<ul class="pull-right right-mspace unstyled clearfix mob-clr">
						<li class="top-mspace logout"><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users' , 'action' => 'logout', 'admin' => true), array( 'class' => 'js-no-pjax   mob-clr mob-dc', 'title' => __l('Logout'))); ?></li>
					</ul>
					<p class="logged-info  dc text-11 ver-smspace  mob-clr"><?php echo __l('You are logged in as Admin'); ?></p>
					<div class="container con-height clearfix pr z-top">
						<div class="js-alab hide"></div>
					</div>
				</div>
			</div>
		<?php } else { ?>
			<?php if($this->Auth->sessionValid() && $this->Auth->user('role_id') == ConstUserTypes::Admin): ?>
				<div class="clearfix admin-wrapper pr">
					<ul class="pull-left unstyled clearfix  mob-clr">
					<li class="text-16">
						<?php echo $this->Html->link(($this->Html->cText(Configure::read('site.name'), false).' '.'<span class="sfont">Admin</span>'), array('controller' => 'users', 'action' => 'stats', 'admin' => true), array('escape' => false, 'class' => 'js-no-pjax  mob-clr mob-dc', 'title' => (Configure::read('site.name').' '.'Admin')));?>
					</li>
					</ul>
					<ul class="pull-right right-mspace unstyled clearfix  mob-clr">
						<li class="top-mspace logout"><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users' , 'action' => 'logout', 'admin' => true), array( 'class' => 'js-no-pjax  mob-clr mob-dc', 'title' => __l('Logout'))); ?></li>
					</ul>
					<p class="logged-info  dc text-11 ver-smspace"><?php echo __l('You are logged in as Admin'); ?></p>
					<div class="container con-height clearfix pr">
						<div class="js-alab alap">
						<?php 
							if ($this->request->params['controller']=='items' && $this->request->params['action']=='view') {
								echo $this->element('admin_panel_item_view', array('controller' => 'items', 'action' => 'index', 'item' => $item));  
							} else if ($this->request->params['controller']=='requests' && $this->request->params['action']=='view'){
								echo $this->element('admin_panel_request_view', array('controller' => 'requests', 'action' => 'index', 'request' => $request));
							} else if ($this->request->params['controller']=='users' && $this->request->params['action']=='view'){
								echo $this->element('admin_panel_user_view');
							} 
						?>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php } ?>
		<?php if($this->request->params['action']!='calendar_edit' ): ?>
			<header id="header" itemscope itemtype="http://schema.org/Organization">
				<div class="js-header-menu navbar no-mar ps <?php if(empty($this->request->url)) { ?> site-header site-menu z-top <?php } ?>">
					<div class="navbar-inner no-round mob-no-pad">
						<div class="js-header-top container pr z-top">
							<h1 class="pull-left ver-space top-smspace"><?php echo $this->Html->link($this->Html->image('logo.png', array('itemprop' => 'logo','alt'=> sprintf('[Image: %s]', $this->Html->cText(Configure::read('site.name'),false)))), '/',array('escape'=>false,'title'=>$this->Html->cText(Configure::read('site.name'),false),'itemprop'=>'url'));?></h1>
							<a data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar mspace collapsed"> <i class="icon-align-justify icon-24 no-pad whitec"></i></a>
							<?php echo $this->element('header', array('config' => 'sec')); ?>
						</div>
					</div>
					<div id="nav-search" class="hidden-phone">
						<div class="search-input-container">
							<?php 
								echo $this->Form->create('Item', array('class' => 'form-search bot-mspace js-search pr', 'action'=>'index', 'enctype' => 'multipart/form-data'));
								echo $this->Form->input('Item.latitude', array('id' => 'sh_latitude', 'type' => 'hidden'));
								echo $this->Form->input('Item.longitude', array('id' => 'sh_longitude', 'type' => 'hidden'));
								echo $this->Form->input('Item.ne_latitude', array('id' => 'sh_ne_latitude', 'type' => 'hidden'));
								echo $this->Form->input('Item.ne_longitude', array('id' => 'sh_ne_longitude', 'type' => 'hidden'));
								echo $this->Form->input('Item.sw_latitude', array('id' => 'sh_sw_latitude', 'type' => 'hidden'));
								echo $this->Form->input('Item.sw_longitude', array('id' => 'sh_sw_longitude', 'type' => 'hidden'));
								echo $this->Form->input('Item.address', array('id' => 'sh_js-street_id', 'type' => 'hidden'));
								echo $this->Form->input('Item.city_name', array('id' => 'sh_CityName', 'type' => 'hidden'));
								echo $this->Form->input('Item.state_name', array('id' => 'sh_StateName', 'type' => 'hidden'));
								echo $this->Form->input('Item.country_iso2', array('id' => 'sh_js-country_id', 'type' => 'hidden'));
								echo $this->Form->input('Item.type', array( 'value' =>'search', 'type' => 'hidden'));
							?>
							<div class="pr auto-complete js-autocomplete-gmap input text no-mar">
							  <?php echo $this->Form->input('Item.cityName', array('type' => 'search', 'id' => 'ItemCityNameAddressSearch', 'class' => 'js-geo-autocomplete span12', 'placeholder' => __l('Where?'), 'label' =>false, 'div' => 'input text right-space')); ?>
							</div>
							<div class="submit left-space">
								<?php echo $this->Form->submit(__l('Search'), array('value'=>__l('Search'),'id' => 'js-sub', 'class' => 'btn btn-large btn-success no-mar' ,'disabled' => 'disabled', 'div'=>false));?>
							</div>
							<?php echo $this->Form->end(); ?>
						</div>
					</div>
				</div>
			</header>
		<?php endif; ?>
		<?php
			//lazy loading image
			$lazy_allowed=true;
			//Lazy load image not allowed cases
			if($this->request->params['controller'].'/'.$this->request->params['action'] =='items/view' || $this->request->params['controller'].'/'.$this->request->params['action'] =='items/search' || $this->request->params['controller'].'/'.$this->request->params['action'] =='transactions/index' || (isset($this->request->params['named']['type']) && $this->request->params['named']['type']=='collection')):
				$lazy_allowed=false;
			endif;
			if(($this->request->params['controller'].'/'.$this->request->params['action'] ==='categories/view') || ($this->request->params['controller'].'/'.$this->request->params['action'] ==='items/search') || ($this->request->params['controller'].'/'.$this->request->params['action'] ==='items/view'))
				$homeClass	=	' bot-space ';
			else
				$homeClass	=	' container ';
		?>
			<section id="pjax-body">
				<?php 
				if (env('HTTP_X_PJAX') == 'true') {
					echo $this->fetch('highperformance'); 
				}
				?>
				<?php echo $this->Layout->sessionFlash(); ?>
				<section id="main" class="clearfix bot-space <?php echo $this->Html->getUniquePageId();?> bot-mspaces <?php echo $homeClass; if($lazy_allowed): ?>js-lazyload<?php endif; ?>">
						<?php echo $content_for_layout;?>
				</section>
			</section>
			<div class="footer-push"></div>
		</div>
		<?php if($this->request->params['action'] != 'calendar_edit'): ?>
		<footer id="footer" class="hor-space" itemscope itemtype="http://schema.org/WPFooter">
			<?php if (Configure::read('widget.footer_script')) { ?>
				  <div class="dc clearfix bot-space">
				  <?php echo Configure::read('widget.footer_script'); ?>
				  </div>
			<?php } ?>
			<div class="sep-top sep-medium ">
			<div class="container clearfix top-space">
			<div class="span18 clearfix">
				<ul class="unstyled clearfix top-space pull-left mob-clr">
					<li class="span no-mar"><?php echo $this->Html->link(__l('Terms & Conditions'), array('controller' => 'pages', 'action' => 'view', 'term-and-conditions', 'admin' => false), array('title' => __l('Terms & Conditions')));?></li>
					<li class="span"><?php echo $this->Html->link(__l('Privacy Policy'), array('controller' => 'pages', 'action' => 'view', 'privacy_policy', 'admin' => false), array('title' => __l('Privacy Policy')));?></li>
					<li class="span"><?php echo $this->Html->link( __l('How it Works'), array('controller' => 'pages', 'action' => 'how_it_works', 'admin' => false), array('title' => __l('How it Works'), 'escape' => false));?></li>
					<li class="span"><?php echo $this->Html->link(__l('Acceptable Use Policy'), array('controller' => 'pages', 'action' => 'view', 'aup', 'admin' => false), array('title' => __l('Acceptable Use Policy')));?> </li>
					<li class="span"><?php echo $this->Html->link(__l('Contact Us'), array('controller' => 'contacts', 'action' => 'add', 'admin' => false), array('title' => __l('Contact Us'), 'class' => 'js-no-pjax'));?></li>
					<li class="span"><?php echo $this->Html->link(__l('Map'), array('controller' => 'items', 'action' => 'map', 'admin' => false), array('title' => __l('Map')));?></li>
					<?php if(isPluginEnabled('Collections')) : ?>
						<li class="span"><?php echo $this->Html->link(__l('Collections'), array('controller' => 'collections', 'action' => 'index', 'admin' => false), array('title' => __l('Collections')));?></li>
					<?php endif;?>
				</ul>
				<div class="clearfix top-space graydarkc pull-left">
					<p class="span no-mar" itemprop="copyrightYear">&copy; <?php echo date('Y');?> <?php echo $this->Html->link($this->Html->cText(Configure::read('site.name'), false), '/', array('title' => $this->Html->cText(Configure::read('site.name'),false),'itemprop'=>'copyrightHolder', 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
					<p class="clearfix span">
						<span class="pull-left"><a href="http://bookorrent.dev.agriya.com" title="<?php echo __l('Powered by BookorRent'); ?>" target="_blank" class="powered pull-left"><?php echo __l('Powered by BookorRent'); ?></a>,</span>
						<span class="pull-left"><?php echo __l('Made in'); ?></span><?php echo $this->Html->link(__l('Agriya Web Development'), 'http://www.agriya.com/', array('target' => '_blank', 'title' => __l('Agriya Web Development'), 'class' => 'company pull-left'));?>  <span class="pull-left"><?php echo Configure::read('site.version');?></span></p>
					<p  id="cssilize" class="span"><?php echo $this->Html->link(__l('CSSilized by CSSilize, PSD to XHTML Conversion'), 'http://www.cssilize.com/', array('target' => '_blank', 'title' => __l('CSSilized by CSSilize, PSD to XHTML Conversion'), 'class' => ' cssilize'));?></p>			  
				</div>
			</div>  
				<ul class="unstyled clearfix pull-right mob-clr">
					<li class="grayc pull-left"><a class="grayc" href="<?php echo Configure::read('facebook.site_facebook_url'); ?>" title="<?php echo __l('Follow me on facebook'); ?>" target="_blank"><i class="icon-facebook-sign text-32"></i></a> </li>
					<li class="grayc pull-left"><a class="grayc" href="<?php echo Configure::read('twitter.site_twitter_url'); ?>" title="<?php echo __l('Follow me on twitter'); ?>" target="_blank"> <i class=" icon-twitter-sign text-32"></i></a> </li>					
				</ul>				  
			</div>
			</div>
		  </footer>
		<?php endif; ?>
	</div>
	<!-- for modal -->
	<div class="modal hide fade" id="js-ajax-modal">
	<div class="modal-body"></div>
	<div class="modal-footer"><a href="#" class="btn js-no-pjax" data-dismiss="modal"><?php echo __l('Close'); ?></a></div>
	<!-- for modal -->
</div>
	<?php // echo $cakeDebug?>
	</body>
</html>
