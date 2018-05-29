<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<div class="top-content pr space request">
<h2 class="space"><?php echo !empty($collections['Collection']['title']) ? $this->Html->cText($collections['Collection']['title'], false) : ''; ?></h2>
		<?php
	$view_count_url = Router::url(array(
		'controller' => 'items',
		'action' => 'update_view_count',
	), true);
?> <?php if (!empty($items)): ?>
        <div id="myCarousel" class="carousel slide pr no-mar js-view-count-update {'model':'item','url':'<?php echo $this->Html->cText($view_count_url, false); ?>'}">
          <div class="carousel slide no-mar">
            <div class="carousel-inner">
			<?php 
			$i = 1;
			foreach($items As $item) { ?>
			<?php if(isset($item['Attachment'][0])): ?>
              <div class="item <?php if($i == 1) { ?> active <?php } ?>"><?php echo $this->Html->showImage('Item', $item['Attachment'][0], array('dimension' => 'very_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))); ?>
			  <div class="carousel-caption">
               <div class="span10">
				 <div class="clearfix bot-space">
					  <h2 class="span right-mspace span8 htruncate">
							<?php
								$current_user_details = array(
									'username' => $item['User']['username'],
									'role_id' => $item['User']['role_id'],
									'id' => $item['User']['id'],
									'facebook_user_id' => $item['User']['facebook_user_id']
								);
								$current_user_details['UserAvatar'] = array(
									'id' => $item['User']['attachment_id']
								);
								echo $this->Html->getUserAvatarLink($current_user_details, 'small_thumb');
							?>
				<?php echo $this->Html->link($this->Html->cText($item['Item']['title'],false), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false),array('title'=>$this->Html->cText($item['Item']['title'], false),'class'=> 'graydarkc')); ?> </h2>
				</div>
          <div class="clearfix">
		  <?php if(!empty($item['Country']['iso_alpha2'])): ?>
						<span class="flags flag-<?php echo $this->Html->cText(strtolower($item['Country']['iso_alpha2']), false); ?>" title ="<?php echo $item['Country']['name']; ?>"><?php echo $item['Country']['name']; ?></span>
				<?php endif; ?>
            			<p class="span7 htruncate graydarkc"><?php echo $this->Html->cText($item['Item']['address']);?>
		  </div>
		  </div>
		  <div class="clearfix pull-right span7">
            <div class="top-mspace top-space clearfix">
						<dl class="dc sep-right list">
							<dt class="pr hor-mspace text-11" title ="<?php echo __l('Views');?>"><?php echo __l('Views');?></dt>
							<dd class="dtextb text-20 graydarkc pr hor-mspace js-view-count-item-id dc js-view-count-item-id-<?php echo $item['Item']['id']; ?> {'id':'<?php echo $this->Html->cInt($item['Item']['id'], false); ?>'}"><?php  echo numbers_to_higher($item['Item']['item_view_count']); ?></dd>
						</dl>
						<dl class="dc sep-right list">
							<dt class="pr hor-mspace text-11" title ="<?php echo __l('Positive');?>"><?php echo __l('Positive');?></dt>
							<dd class="dtextb text-20 graydarkc pr hor-mspace"><?php  echo $this->Html->cInt($item['Item']['positive_feedback_count']); ?></dd>
						</dl>
						<dl class="dc sep-right list">
							<dt class="pr hor-mspace text-11" title ="<?php echo __l('Negative');?>"><?php echo __l('Negative');?></dt>
							<dd  class="dtextb text-20 graydarkc pr hor-mspace"><?php  echo $this->Html->cInt($item['Item']['item_feedback_count'] - $item['Item']['positive_feedback_count']); ?></dd>
						</dl>
						<dl class="dc list">
    						<dt class="pr hor-mspace text-11" title ="<?php echo __l('Success Rate');?>"><?php echo __l('Success Rate');?></dt>
							<?php if($item['Item']['item_feedback_count'] == 0): ?>
								<dd class="dtextb text-20 graydarkc pr hor-mspace" title="<?php  echo __l('No Bookings available'); ?>"><?php  echo __l('n/a'); ?></dd>
							<?php else:?>
							<dd class="dtextb text-20 graydarkc pr hor-mspace">
                               <?php
										if(!empty($item['Item']['positive_feedback_count'])):
										$positive = floor(($item['Item']['positive_feedback_count']/$item['Item']['item_feedback_count']) *100);
										$negative = 100 - $positive;
										else:
										$positive = 0;
										$negative = 100;
										endif;
										
										echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&amp;chf=bg,s,FFFFFF00', array('width' => '30px', 'height' => '30px', 'class' => 'js-skip-gallery', 'title' => $positive.'%')); ?>
							</dd>
							<?php endif; ?>
    					</dl>
					</div>
				
					</div>
				
			  </div>
			  </div>
              <?php endif; 
				$i++;
			  } ?> 
            </div>
            <a class="left carousel-control" href="#myCarousel" data-slide="prev">‹</a>
			<a class="right carousel-control" href="#myCarousel" data-slide="next">›</a> </div>
        </div>
		<?php endif; ?>
        <div class="row no-mar pull-right">
          <div class="span tab-right mob-clr clearfix">
            <div class="top-mspace top-space clearfix">
              <dl class="dc sep-right list">
                <dt class="pr hor-mspace text-11"><?php echo Configure::read('item.alt_name_for_item_plural_caps');?></dt>
                <dd title="<?php echo !empty($item_count) ? $this->Html->cInt($item_count, false) : 0; ?>" class="textb text-20 graydarkc pr hor-mspace"> <?php echo !empty($item_count) ? $this->Html->cInt($item_count, false) : 0; ?></dd>
              </dl>
              <dl class="dc sep-right list">
                <dt class="pr hor-mspace text-11"><?php echo __l('Cities');?></dt>
                <dd title="<?php echo !empty($collections['Collection']['city_count']) ? $this->Html->cInt($collections['Collection']['city_count'], false) : 0; ?>" class="textb text-20 graydarkc pr hor-mspace"><?php echo !empty($collections['Collection']['city_count']) ? $this->Html->cInt($collections['Collection']['city_count'], false) : 0; ?></dd>
              </dl>
              <dl class="dc list">
                <dt class="pr hor-smspace text-11"><?php echo __l('Countries');?></dt>
                <dd title="<?php echo !empty($collections['Collection']['country_count']) ? $this->Html->cInt($collections['Collection']['country_count'], false) : 0; ?>" class="textb text-20 graydarkc pr hor-mspace"><?php echo !empty($collections['Collection']['country_count']) ? $this->Html->cInt($collections['Collection']['country_count'], false) : 0; ?></dd>
              </dl>
            </div>
          </div>
        </div>
		<div class="row no-mar ">
			<h3><?php echo __l("Description"); ?> </h3>
			<p> <?php echo $this->Html->cHtml($collections['Collection']['description']);?></p>
		</div>
      </div>

  <div id="fb-root"></div>
  <script type="text/javascript">
	  window.fbAsyncInit = function() {
		FB.init({appId: '<?php echo Configure::read('facebook.app_id');?>', status: true, cookie: true,
				 xfbml: true});
	  };
	  (function() {
		var e = document.createElement('script'); e.async = true;
		e.src = document.location.protocol +
		  '//connect.facebook.net/en_US/all.js';
		document.getElementById('fb-root').appendChild(e);
	  }());

	</script>