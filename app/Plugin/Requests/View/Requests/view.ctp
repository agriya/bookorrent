<?php /* SVN: $Id: $ */ ?>
<?php Configure::write('highperformance.rids', $request['Request']['id']); ?>
<div class="requests view clearfix js-request-view" data-request-id="<?php echo $this->Html->cInt($request['Request']['id'], false); ?>">
	<div class="top-content pr">
		<div class="banner-content-trans-bg span10 pa mspace mob-ps">
			<div class="clearfix">
				<h2 class="pull-left right-mspace span9 whitec text-24">
					<?php if(isPluginEnabled('RequestFavorites')) :
					if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))):?>
					<div class="pull-left alpruf-<?php echo $this->Html->cInt($request['Request']['id'], false);?> hide">
						<?php echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'request_favorites', 'action'=>'delete', $request['Request']['slug'], 'type' => 'view'), array('escape' => false ,'class' => 'js-like un-like show span top-smspace js-no-pjax', 'title' => __l('Unlike'))); ?>
					</div>
					<div class="pull-left alprf-<?php echo $this->Html->cInt($request['Request']['id'], false);?> hide">
						<?php	echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'request_favorites', 'action' => 'add', $request['Request']['slug'], 'type' => 'view'), array('escape' => false ,'title' => __l('Like'),'escape' => false ,'class' =>'js-like like show span top-smspace whitec no-under js-no-pjax')); ?>
					</div>
					<div class='pull-left blprf-<?php echo $this->Html->cInt($request['Request']['id'], false);?> hide'>
						<?php	echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'users', 'action' => 'login'), array('title' => __l('Like'),'escape' => false ,'class' =>'like show span top-smspace whitec no-under ')); ?>
					</div>
					<?php else: ?>			
					<span>
					<?php
						if($this->Auth->sessionValid() && isPluginEnabled('RequestFavorites')):
							if(!empty($request['RequestFavorite'])):
								foreach($request['RequestFavorite'] as $favorite):
									if($request['Request']['id'] == $favorite['request_id'] && $favorite['user_id'] == $this->Auth->user('id')):
										if($this->Auth->user('id')!=$request['Request']['user_id']):
											 echo $this->Html->link('<i class="icon-star text-20 "></i>', array('controller' => 'request_favorites', 'action'=>'delete', $request['Request']['slug'], 'type' => 'view'), array('class' => 'js-no-pjax js-like un-like show span top-smspace no-under', 'title' => __l('Unlike'),'escape'=>false));
										endif;
									
									endif;
								endforeach;
							else:
							  if($this->Auth->user('id')!=$request['Request']['user_id']):
								echo $this->Html->link('<i class="icon-star whitec text-20"></i>', array('controller' => 'request_favorites', 'action' => 'add', $request['Request']['slug'], 'type' => 'view'), array('title' => __l('Like'),'escape' => false ,'class' =>'js-no-pjax js-like like show span top-smspace no-under '));
							  endif;
							endif;

						endif;
					?>
					</span>
					<?php endif;
					endif; 
					?>			
					<span class="htruncate js-bootstrap-tooltip span7 top-mspace" title="<?php echo $this->Html->cText($request['Request']['title'],false) ;?>"><?php echo $this->Html->cText($request['Request']['title']); ?></span>
				</h2>
			</div>
            <div class="clearfix top-smspace">
			<?php 
			if (isPluginEnabled('RequestFlags')):
				if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))):
		  ?>
				<div class="alvfp-<?php echo $this->Html->cText($request['Request']['id'], false);?> hide">
					<?php echo $this->Html->link('<i class="icon-flag pull-left text-18 no-mar right-space whitec"></i>', array('controller' => 'request_flags', 'action' => 'add', $this->Html->cInt($request['Request']['id']), false), array('data-toggle' => 'modal', 'data-target' => '#js-ajax-modal','title' => __l('Flag this request'),'escape' => false ,'class' =>'flag dr js-no-pjax js-thickbox')); ?>
				</div>
				<div class="blvfp-<?php echo $request['Request']['id'];?> hide">
					<?php echo $this->Html->link('<i class="icon-flag pull-left text-18 no-mar right-space whitec"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f=request/' . $request['Request']['slug'], 'admin' => false), array( 'escape' => false,'title' => __l('Flag this request'), 'class' => 'flag dr ')); ?>
				</div>
			
			<?php else: ?>
			<?php  if ($this->Auth->sessionValid()):
						if ($request['Request']['user_id'] != $this->Auth->user('id')):
							echo $this->Html->link('<i class="icon-flag pull-left text-18 no-mar right-space whitec"></i>', array('controller' => 'request_flags', 'action' => 'add', $request['Request']['id']), array('data-toggle' => 'modal', 'data-target' => '#js-ajax-modal','class'=>'js-no-pjax','id'=>'', 'escape' => false, 'title' => __l('Flag this request')));
						endif;
					else :
						echo $this->Html->link('<i class="icon-flag pull-left text-18 no-mar right-space whitec"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f=request/' . $request['Request']['slug'], 'admin' => false), array('escape' => false,'title' => __l('Flag this request'), 'class' => 'flag dr '));
					endif;
				endif;
			endif; ?>
			<span class="whitec show">
			<?php if(!empty($request['Country']['iso_alpha2'])): ?>
						<span class="flags flag-<?php echo $this->Html->cText(strtolower($request['Country']['iso_alpha2']), false); ?>" title ="<?php echo $this->Html->cText($request['Country']['name'], false); ?>"><?php echo $this->Html->cText($request['Country']['name'], false); ?></span>
				<?php endif; ?>
				
			<?php 
					if(empty($request['Request']['address'])) {
						$address	=	 $this->Html->cText($request['City']['name']);?>, <?php echo $this->Html->cText($request['State']['name']);?>,<?php echo $this->Html->cText($request['Country']['name']);
						$addressTitle = $this->Html->cText($request['City']['name'],false);?>, <?php echo $this->Html->cText($request['State']['name'],false);?>,<?php echo $this->Html->cText($request['Country']['name'],false);
					} else {
						$address		=	 $this->Html->cText($request['Request']['address']);
						$addressTitle	=	 $this->Html->cText($request['Request']['address'],false);
					}
				?>
				<p class="htruncate js-bootstrap-tooltip span5" title="<?php echo $this->Html->cText($addressTitle, false); ?>" ><?php echo $this->Html->cHtml($address, false); ?></p>
				</span>
				<div class="whitec clearfix pull-left">
				  <dl class="no-pad no-mar ">
					<dt class="pull-left textn no-mar"><?php echo __l('Posted'); ?></dt>
					<dd class="pull-left"><?php echo $this->Time->timeAgoInWords($request['Request']['created']); ?></dd>
				  </dl>
				</div>
			  <?php 
			  if(isset($share_url)){ ?>
			  <div class="whitec clearfix pull-right">
				<?php echo $this->Html->link('<i class="icon-share"></i>', $share_url, array('title'=>__l('Share'), 'escape' => false, 'class' => 'btn btn-small js-bootstrap-tooltip pull-left hor-smspace', 'target' => '_blank')); ?>
			</div>
				<?php 
			  }
			  ?>			 
			  
            </div>
          </div>
		  <div class="banner-content-trans-bg pa mspace dc z-top price-section mob-ps span6">
            <div class="row no-mar">
                <h2>
					<span class="textb text-24 whitec">
						<?php 
							if (Configure::read('site.currency_symbol_place') == 'left'): 
								echo Configure::read('site.currency').' ';
							endif;
							echo $this->Html->cCurrency($request['Request']['price']);
							if (Configure::read('site.currency_symbol_place') == 'right'):
								echo Configure::read('site.currency').' ';
							endif; 
						?>
					</span> 
					<?php $requested_date = $this->Html->cDate($request['Request']['from'], 'span', true) . ' - ' .$this->Html->cDate(getToDate($request['Request']['to']), 'span', true);?>
					<span class="text-11 whitec show js-bootstrap-tooltip" title="<?php echo $this->Html->cText($requested_date, false); ?>"><?php echo $requested_date;?></span>
				</h2>
					<div class="pull-right mob-clr">
						<?php if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))): ?>
						<?php if($request['User']['id']!=$this->Auth->user('id')): ?>
							<div class="al-mao-<?php echo $this->Html->cInt($request['Request']['id'], false);?> hide">
								<?php echo $this->Html->link(__l('Make an offer'), array('controller' => 'items', 'action' => 'add','request',$request['Request']['id'], 'admin' => false), array('title'=>__l('Make an offer'), 'escape' => false, 'class' => 'show btn span5 top-mspace btn-large btn-primary text-18 textb')); ?>
							</div>
						<?php endif; ?>
						<?php else: ?>
						<?php if($request['User']['id']!=$this->Auth->user('id')): ?>        	  
                    		<?php echo $this->Html->link(__l('Make an offer'), array('controller' => 'items', 'action' => 'add','request',$request['Request']['id'], 'admin' => false), array('title'=>__l('Make an offer'), 'escape' => false, 'class' => 'show btn span5 top-mspace btn-large btn-primary text-18 textb')); ?>
						<?php endif; ?>
						<?php endif; ?>
					</div>
            </div>
          </div>
		  <div class="pr no-mar"><?php if(!empty($request['Request']['city_id'])): ?>
			<?php $map_zoom_level = !empty($request['Request']['zoom_level']) ? $request['Request']['zoom_level'] : '10';?>
				<img src="<?php echo $this->Html->formGooglemap($request['Request'],'956x260'); ?>" width="956" height="200" />
			<?php endif; ?></div>
			 <div class="row no-mar">
            <div class="big-thumb prop-owner pa img-polaroid mob-ps">
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
									echo $this->Html->getUserAvatarLink($current_user_details, 'small_big_thumb');
								?>
			</div>
            <div class="offset5 span tab-right mob-clr clearfix">
              <div class="top-mspace top-space clearfix">
                <dl class="sep-right list">
                  <dt class="pr hor-mspace text-11"><?php echo __l('Views'); ?></dt>
                  <dd class="textb text-20 graydarkc pr hor-mspace" title=""><?php echo  $this->Html->cInt($request['Request']['request_view_count']); ?></dd>
                </dl>
                <dl class="sep-right list">
                  <dt class="pr hor-mspace text-11"><?php echo __l('Offered'); ?></dt>
                  <dd class="textb text-20 graydarkc pr hor-mspace" title=""><?php echo $this->Html->cInt($request['Request']['item_count']);?></dd>
                </dl>
                <dl class="list">
                  <dt class="pr hor-mspace text-11"><?php echo __l('Days'); ?></dt>
                  <dd class="textb text-20 graydarkc pr hor-mspace" title=""><?php echo  $this->Html->cInt(getFromToDiff($request['Request']['from'], getToDate($request['Request']['to']))); ?></dd>
                </dl>
              </div>
            </div>
          </div>
			
			
			</div>
		  <div class="main-content pr">
          <section>
		  <div id="ajax-tab-container-user" class="ajax-tab-container-user">
            <ul id="myTab2" class="nav nav-tabs tabs top-space top-mspace">
              <li class="tab"><a href="#description" class="js-no-pjax" data-toggle="tab"><?php echo __l('Description'); ?></a> </li>
				<?php if(!empty($request['Submission']['SubmissionField'])) { ?>             
			 <li><a href="#Request-Details" class="js-no-pjax" data-toggle="tab"><?php echo __l('Request Details'); ?></a></li>
				<?php } ?>
			  <li><?php echo $this->Html->link(__l('Related Requests'), array('controller' => 'requests', 'action' => 'index', 'type' => 'related', 'request_id' => $request['Request']['id'], 'view' => 'compact'), array('title' => __l('Related Requests'), 'class' => 'js-no-pjax', 'data-target'=>'#Related-Requests'));?></li>
              <li><?php echo $this->Html->link(__l('Other Requests'), array('controller' => 'requests', 'action' => 'index', 'user_id' => $request['User']['id'], 'type' => 'other', 'request_id' => $request['Request']['id'], 'view' => 'compact'), array('title' => __l('Other Requests by ').$request['User']['username'], 'class' => 'js-no-pjax', 'data-target'=>'#Other-Request'));?></li>
             
            </ul>
            <div class="sep-right sep-left sep-bot tab-round tab-content" id="myTabContent2">
              <div class="tab-pane space " id="description">
				<?php echo $this->Html->cText($request['Request']['description']);?>
			  </div>
			  <div class="tab-pane space " id="Request-Details">
				<div class="clearfix">
					<h3 class="well space textb text-16 no-mar"> <?php echo __l('Details'); ?></h3>							
				</div>			  
				<?php
					if(!empty($request['Submission']['SubmissionField'])) :
						$is_mediafile = $is_urls = $is_otherdetails = 0;
						foreach($request['Submission']['SubmissionField'] as $submissionField):
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
				<?php if(!empty($is_urls)) { ?>
				<div class="clearfix share-block top-space">
					<div class="clearfix">
						<div class="clearfix">
							<h5 class="pull-left textb clearfix"><?php echo sprintf(__l('This %s in other websites'), Configure::read('request.alt_name_for_request_singular_small')); ?></h5>
						</div>
						<div class="clearfix top-space">
							<ul class="clearfix row unstyled">
								<?php
									foreach($request['Submission']['SubmissionField'] as $submissionField):
										if (!empty($submissionField['type']) && $submissionField['FormField']['type'] == 'url'):
								?>
								<li class="span"><a href="<?php echo $this->Html->cText($submissionField['response'], false); ?>" target="_blank" class="website" title="<?php echo $this->Html->cText($submissionField['FormField']['label'], false); ?>"><?php echo $submissionField['FormField']['label']; ?></a></li>
								<?php
										endif;
									endforeach;
								?>
							</ul>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if(!empty($is_mediafile) && !empty($request['Submission']['SubmissionField'])): ?>
				<section class="clearfix ">
					<h4 class="page-header ver-space textb ver-mspace"><?php echo __l('Media and other files');?></h4>
					<?php
					$request_view_class = '';
					if (count($request['Submission']['SubmissionField']) >1) {
						$request_view_class = 'request-view-list';
					}
					?>
					<div class="<?php echo $request_view_class; ?> clearfix">
						<?php $j = 0; $class = ' class="altrow"';?>
						<?php
						foreach($request['Submission']['SubmissionField'] as $submissionField):
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
											<?php echo $this->Html->showImage('SubmissionThumb', $submissionField['SubmissionThumb'], array('dimension' => 'micro_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($request['Request']['title'], false)), 'escape' => false));?>
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
											<?php echo $this->Html->link($this->Html->cText($depends['response'], false), array('controller' => 'requests', 'action' => 'mediadownload',$request['Request']['slug'],$submissionField['id'],$submissionField['SubmissionThumb']['id']), array('class' => 'download js-tooltip', 'escape' => false,'title'=>__l("Download"." - ".$submissionField['SubmissionThumb']['filename'])));?>
										</div>
									</div>
									<?php
												}
											}
										} else {
									?>
									<div class ="top-space top-mspace pull-left span8">
										<div class="top-smspace">
											<?php echo $this->Html->link($this->Html->cText($submissionField['SubmissionThumb']['filename'], false), array('controller' => 'requests', 'action' => 'mediadownload',$request['Request']['slug'],$submissionField['id'],$submissionField['SubmissionThumb']['id']), array('class' => 'download js-tooltip', 'escape' => false,'title'=>__l("Download")." - ".$submissionField['SubmissionThumb']['filename']));?>
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
				<?php if(!empty($is_otherdetails) && !empty($request['Submission']['SubmissionField'])): ?>
				<section class="clearfix">
					<h4 class="page-header ver-space textb ver-mspace"><?php echo __l("Other Details");?></h4>
					<?php
						$request_view_class = '';
						if (count($request['Submission']['SubmissionField']) >1) {
							$request_view_class = 'request-view-list';
						}
					?>
					<div class="<?php echo $request_view_class; ?> clearfix">
						<dl class="clearfix dl-horizontal">
							<?php $j = 0; $class = ' class="altrow"';?>
							<?php foreach($request['Submission']['SubmissionField'] as $submissionField):?>
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
												if (empty($submissionField['RequestCloneThumb'])){
													echo __l('None specified');
												} else {
													$regex = '/(?<!href=["\'])http:\/\//';
													$regex1 = '/(?<!href=["\'])https:\/\//';
													$display_url = preg_replace($regex, '', $submissionField['response']);
													$display_url = preg_replace($regex1, '', $display_url);
								?>
								<div class="clone-block">
									<?php echo $this->Html->link($this->Html->showImage('RequestCloneThumb', $submissionField['RequestCloneThumb'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($request['Request']['title'], false)), 'title' => $this->Html->cText($request['Request']['title'], false), 'escape' => false)), $submissionField['response'], array('target'=>'_blank','escape' => false)); ?>
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
									<div class="ui-slider grid_left ui-slider-horizontal ui-widget ui-widget-content ui-corner-all" role="application"> <span class="arrow" title="<?php echo $submissionField['response']; ?>%" style="left: <?php echo $this->Html->cText($submissionField['response'] - 5, false); ?>%;"></span> <span style="width: <?php echo $this->Html->cText($submissionField['response'], false); ?>%;" class="ui-slider-handle ui-state-default ui-corner-all" aria-valuetext="<?php echo $this->Html->cText($submissionField['response'], false); ?>" aria-valuenow="<?php echo $this->Html->cText($submissionField['response'], false); ?>" aria-valuemax="99" aria-valuemin="0" aria-labelledby="undefined" role="slider" tabindex="0"  style="" title="<?php echo $submissionField['response']; ?>%"></span> </div>
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
								<a href="http://<?php echo $this->Html->cText($submissionField['response'], false); ?>" target = "_blank" > <?php echo $this->Html->cText($submissionField['response'], false);?></a>
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
			  <div class="tab-pane space " id="Related-Requests">
			  </div>
			  <div class="tab-pane space " id="Other-Request">
			  </div>
				</div>
			</div>
			</div>
			</div>			
