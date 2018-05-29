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
$min_hours = '';
if($item['Item']['is_have_definite_time'] == 1){
	$min_hours = (!empty($item['CustomPricePerNight'][0]['min_hours'])) ? $item['CustomPricePerNight'][0]['min_hours'] : 0;
}
?>
 <div class="span9 no-mar ver-space">
    <div class="well space clearfix">
	  <?php if(!empty($itemUser)) { ?>
	  <div class="bot-space">
		<ul class="unstyled no-mar">
			<li class="top-space clearfix"> 
				<span class="pull-left dl hor-mspace text-12"><?php echo __l('From'); ?></span> 
				<span class="pull-right textb hor-mspace"><?php echo !empty($itemUser['ItemUser']['from']) ? $this->Html->cDateTime($itemUser['ItemUser']['from']) : ''; ?></span>
			</li>
			<li class="top-space clearfix"> 
				<span class="pull-left dl hor-mspace text-12"><?php echo __l('To'); ?></span> 
				<span class="pull-right textb hor-mspace"><?php echo !empty($itemUser['ItemUser']['to']) ? $this->Html->cDateTime($itemUser['ItemUser']['to']) : ''; ?></span>
			</li>
			<?php if(!empty($min_hours)){ ?>
			<li class="top-space clearfix"> 
				<div class="alert alert-inherit alert-info clearfix"><span class="pull-left dl hor-mspace text-12"><?php echo __l('Min Hours'); ?></span> 
				<span class="pull-right textb hor-mspace"><?php echo $min_hours?></span></div>
			</li>
			<?php } ?>
		</ul>
	  </div>
	  <?php } ?>
	  <div class="order-list span no-mar dc">
		<?php echo $this->Html->link($this->Html->showImage('Item', !empty($item['Attachment'][0]) ? $item['Attachment'][0] : array(), array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'],  'admin' => false), array('title'=>$this->Html->cText($item['Item']['title'],false),'escape' => false, 'class' => 'show'));?>
	  </div>
	  <div class="span dc ver-mspace ver-space tab-clr mob-no-mar tab-no-mar">
		  <div class="mspace space span4 clearfix tab-clr mob-no-mar tab-no-mar">
			<div class="dc list fl-none">
			<span class="textb text-24 graydarkc hor-mspace tab-no-mar">
				<?php 
					if(!empty($price) && $price > 0) {
						echo $this->Html->siteCurrencyFormat($price);
					} else {
						if($item['Item']['is_have_definite_time'] == 1){
							echo __l('Free');
						}
					}
				?>
			</span>
			 <span class="show hor-mspace text-11 tab-no-mar"><?php echo (!empty($price) && $price > 0) ? $label : '&nbsp;';?></span>
			</div>
		  </div>
		</div>
	  <div class="span no-mar mob-clr tab-clr">
		<div class="clearfix span8 no-mar mob-clr mob-dc">
		  <h4 class="textb text-16 ver-space span8 no-mar htruncate">
			<?php 
			if(isPluginEnabled('ItemFavorites')) :
				if(isPluginEnabled('HighPerformance') && (Configure::read('HtmlCache.is_htmlcache_enabled') || Configure::read('cloudflare.is_cloudflare_enabled'))):
			?>
			<div class="alpuf-<?php echo $this->Html->cInt($item['Item']['id'], false);?> hide">
			  <span class="dc">
			  <?php echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'item_favorites', 'action'=>'delete', $item['Item']['slug'], 'type' => 'view'), array('escape' => false ,'class' => 'js-like un-like show span top-smspace js-no-pjax', 'title' => __l('Unlike'))); ?>
			  </span>
			</div>
			<div class="alpf-<?php echo $this->Html->cText($item['Item']['id'], false);?> hide">
			  <span class="dc">
			  <?php echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'item_favorites', 'action' => 'add', $item['Item']['slug'], 'type' => 'view'), array('escape' => false ,'title' => __l('Like'),'escape' => false ,'class' =>'js-like like show span top-smspace graylightc no-under js-no-pjax')); ?>
			  </span>
		    </div>
			<div class='blpf-<?php echo $this->Html->cText($item['Item']['id'], false);?> hide'>
			  <span class="dc">
			  <?php echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f='. $this->request->url), array('title' => __l('Like'),'escape' => false ,'class' =>'like show span top-smspace graylightc no-under ')); ?>
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
							echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'item_favorites', 'action' => 'add', $item['Item']['slug'], 'type' => 'view'), array('title' => __l('Like'),'escape' => false ,'class' =>'js-like js-no-pjax like show span top-smspace graylightc no-under'));
						endif;
					endif;
				else:
					echo $this->Html->link('<i class="icon-star text-20"></i>', array('controller' => 'users', 'action' => 'login', '?' => 'f='. $this->request->url), array('title' => __l('Like'),'escape' => false ,'class' =>'like show span top-smspace graylightc no-under'));
				endif;
			?>
			</span>
			<?php 
				endif;
			endif; 
			?>
			<?php 
				$lat = $item['Item']['latitude'];
				$lng = $item['Item']['longitude'];
				$id = $item['Item']['id'];
				echo $this->Html->link($this->Html->cText($item['Item']['title'], false), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('id'=>"js-map-side-$id",'class'=>"graydarkc dc js-map-data {'lat':'$lat','lng':'$lng'}",'title'=>$this->Html->cText($item['Item']['title'], false),'escape' => false));
			    $flexible_class = '';
			?>
			<?php if(isPluginEnabled('Seats') && !empty($item['CustomPricePerNight'][0]['Hall']['name'])){ ?>
				<span class="textb text-16 graydarkc"><?php echo '['.__l('Venue').' - '.$item['CustomPricePerNight'][0]['Hall']['name'].']';?>
				</span>
			<?php } ?>	
		  </h4>
		  <span class="show mob-clr span8 no-mar htruncate" title="<?php echo $this->Html->cHtml($item['Item']['address'], false); ?>">
			<?php 
				if(!empty($item['Country']['iso_alpha2'])): 
			?>
			<span class="flags flag-<?php echo $this->Html->cText(strtolower($item['Country']['iso_alpha2']), false); ?> mob-inline top-smspace" title ="<?php echo $item['Country']['name']; ?>"><?php echo $item['Country']['name']; ?></span>
			<?php 
				endif;
				echo $this->Html->cText($item['Item']['address']);
			?>
		  </span>
		  <?php if(!empty($item['CustomPricePerNight'])) { ?>
		  <span class="ver-space show mob-clr span8 no-mar">
		  <?php 
			// 10 more needed to start
			$nights = '';
			$more_child = 1;
			if($item['Item']['is_have_definite_time'] && $item ['Item']['is_people_can_book_my_time']) {
				$parent_index = count($item['CustomPricePerNight']) - 1;
				$str_end_data = strtotime($item['CustomPricePerNight'][1]['end_date']);
				if(!$item['CustomPricePerNight'][$parent_index]['is_timing']){
					echo '<p><i class="icon-calendar"></i>'. $this->Html->cDate($item['CustomPricePerNight'][1]['start_date']) . ' '. $this->Html->cTime($item['CustomPricePerNight'][1]['start_time']). ' - ';
					$is_timing_date = $this->Html->cDate($item['CustomPricePerNight'][1]['start_date']) . ' '. $this->Html->cTime($item['CustomPricePerNight'][1]['start_time']). ' - ';
					if(!empty($str_end_data)){
						echo $this->Html->cDate($item['CustomPricePerNight'][1]['end_date']) . ' ';
						$is_timing_end_date = $this->Html->cDate($item['CustomPricePerNight'][1]['end_date']) . ' ';
						$is_timing_date = $is_timing_date . ' ' . $is_timing_end_date;
					}
					echo  $this->Html->cTime($item['CustomPricePerNight'][1]['end_time']);
					$is_timing_end_time = $this->Html->cTime($item['CustomPricePerNight'][1]['end_time']);
					$date_and_time = $is_timing_date . ' ' . $is_timing_end_time;
					echo '</p>';
				} else {
					echo '<p><i class="icon-calendar"></i>'. $this->Html->cDate($item['CustomPricePerNight'][1]['start_date']) ;
					$any_time_start_date = $this->Html->cDate($item['CustomPricePerNight'][1]['start_date']) ;
					if(!empty($str_end_data)){
						echo ' - '.$this->Html->cDate($item['CustomPricePerNight'][1]['end_date']);
						$any_time_end_date = $this->Html->cDate($item['CustomPricePerNight'][1]['end_date']);
						$any_time_start_date = $any_time_start_date . ' ' . $any_time_end_date;
					}
					echo '</p><p><i class="icon-time"></i>'. $this->Html->cTime($item['CustomPricePerNight'][1]['start_time'])  . ' - ' . $this->Html->cTime($item['CustomPricePerNight'][1]['end_time']). '</p>';
					$any_time_end_time = $this->Html->cTime($item['CustomPricePerNight'][1]['start_time'])  . ' - ' . $this->Html->cTime($item['CustomPricePerNight'][1]['end_time']);
					$date_and_time = $any_time_start_date . ' ' . $any_time_end_time;
				}									
				$more_child = $parent_index;
			} else {
				$date_and_time = $this->Html->cDate($item['CustomPricePerNight'][0]['start_date']) . ' '.$this->Html->cTime($item['CustomPricePerNight'][0]['CustomPricePerType'][0]['start_time']). ' - '. $this->Html->cDate($item['CustomPricePerNight'][0]['end_date']) . ' '. $this->Html->cTime($item['CustomPricePerNight'][0]['CustomPricePerType'][0]['end_time']);
				echo  '<i class="icon-calendar"></i>'.$date_and_time;
				$more_child = count($item['CustomPricePerNight']) - 1;
			}
			if(!empty($item['CustomPricePerNight'][0]['repeat_days'])) {
				$repeat_days = explode(',', $item['CustomPricePerNight'][0]['repeat_days']);
				if(count($repeat_days) < 7) {
					$nights .= __l('on') . ' ' . implode(', ', $repeat_days);
				} else {
					$nights .= __l('on') . ' ' . __l('All days');
				}
			} else {
				$nights .= '';
			}
			$more_child_count = $more_child;
			if($more_child_count > 0) {
				$title = $nights . ' (+ '.$more_child_count.' ' . __l('more') . ')' ;
			} else {
				$title = $nights;
			}
			$title_tooltip = $date_and_time . ' ' . $title;
			?>
			<p class="js-bootstrap-tooltip" title="<?php echo $this->Html->cText($title_tooltip, false); ?>">
			<?php echo $title; ?></p>
		  </span>
		  <?php } ?>
		  <map class="inline dc" name="map" >
			  <?php 
				$q = $item['Item']['latitude'] . ',' . $item['Item']['longitude'] . ' (' . $item['Item']['address'] . ')';		  
			  ?>
				<a href="//maps.google.com/maps?q=<?php echo $q; ?>" target="_blank"><img src="//maps.google.com/maps/api/staticmap?markers=<?php echo $this->Html->cHtml($item['Item']['address'], false); ?>&amp;zoom=12&amp;size=330x175&amp;sensor=false" alt="[Image: <?php echo $item['Item']['address']; ?>]" title="<?php echo $item['Item']['address']; ?>"/>
			  </a>
			</map>
		</div>
	  </div>
	  <div class="span clearfix bot-mspace mob-dc">
		<?php echo $this->element('popular-comment-users', array('user_name' => $item['User']['username'],'page' => 'view', 'config' => 'sec')); ?>
	  </div>
	  <div class="pull-left span8 no-mar clearfix sep-top sep-bot mob-clr">
		<div class="clearfix ver-smspace">
          <?php if((!empty($search_keyword['named']['latitude']) || isset($near_by)) && !empty($item[0]['distance'])): ?>
		  <dl class="dc mob-clr sep-right list">
            <dt class="pr hor-mspace text-11" title ="<?php echo __l('Distance');?>"><?php echo __l('Distance');?><span class="dc"> <?php echo __l('(km)');?></span></dt>
            <dd class="textb text-16 hor-smspace  graydarkc pr hor-mspace"><?php echo $this->Html->cInt($item[0]['distance']*1.60934 ,false); ?></dd>
          </dl>
		  <?php endif; ?>
		  <dl class="dc mob-clr sep-right list">
            <dt class="pr hor-mspace text-11" title ="<?php echo __l('View');?>"><?php echo __l('Views');?></dt>
            <dd class="textb text-16 hor-smspace  graydarkc pr hor-mspace js-view-count-item-id js-view-count-item-id-<?php echo $this->Html->cInt($item['Item']['id'], false); ?> {'id':'<?php echo $this->Html->cInt($item['Item']['id'], false); ?>'}"><?php  echo numbers_to_higher($item['Item']['item_view_count']); ?></dd>
          </dl>
		  <dl class="dc mob-clr sep-right list">
            <dt class="pr hor-mspace text-11" title ="<?php echo __l('Positive');?>"><?php echo __l('Positive');?></span></dt>
            <dd class="textb text-16 hor-smspace  graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['positive_feedback_count']); ?></dd>
          </dl>
		  <dl class="dc mob-clr sep-right list">
            <dt class="pr hor-mspace text-11" title ="<?php echo __l('Negative');?>"><?php echo __l('Negative');?></span></dt>
            <dd class="textb text-16 hor-smspace  graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['item_feedback_count'] - $item['Item']['positive_feedback_count']); ?></dd>
          </dl>
		  <dl class="dc mob-clr list">
            <dt class="pr hor-mspace text-11" title ="<?php echo __l('Success Rate');?>"><?php echo __l('Success Rate');?></span></dt>
            <?php if(empty($item['Item']['item_feedback_count'])): ?>
			<dd class="textb text-16 hor-smspace  graydarkc pr hor-mspace" title="<?php  echo __l('No Bookings available'); ?>"><?php  echo __l('n/a'); ?></dd>
			<?php else:?>
			<dd class="textb text-16 hor-smspace  graydarkc pr hor-mspace">
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
		</div>
	  </div>
	</div>
  </div>