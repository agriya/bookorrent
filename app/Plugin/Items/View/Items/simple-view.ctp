<?php /* SVN: $Id: $ */ ?>
<?php 
	$user_avatar_grid = '';
	$user_avatar_inner_left_grid = '';
	$user_avatar_inner_right_grid = '';
	$city_list_grid = '';
	$price_info_right_grid = '';
	if (!empty($_SERVER['REDIRECT_URL']) && preg_match('/admin\/messages/s', $_SERVER['REDIRECT_URL'])) {
		$user_avatar_grid = '';
		$user_avatar_inner_left_grid = '';
		$user_avatar_inner_right_grid = '';
		$city_list_grid = '4';
		$price_info_right_grid = '2';
	}
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
?>
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<div class="ver-space sep-bot clearfix">
              <div class="span dc">
			  <?php echo $this->Html->link($this->Html->showImage('Item', (!empty($item['Attachment'][0])) ? $item['Attachment'][0] : array(), array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)), 'title' => $this->Html->cText($item['Item']['title'],false))), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('target' => '_blank', 'title' => $this->Html->cText($item['Item']['title'], false),'escape' => false)); ?>			  
			  </div>
              <div class="span20 right-mspace mob-clr tab-clr">
                <div class="clearfix hor-space sep-bot">
                  <div class="span10 bot-space">
                    <h4><?php echo $this->Html->link($this->Html->cText($item['Item']['title'], false), array('controller' => 'items', 'action' => 'view', $item['Item']['slug'], 'admin' => false), array('target' => '_blank', 'title' => $this->Html->cText($item['Item']['title'], false),'escape' => false, 'class' => 'textb text-16 graydarkc span3 no-mar js-bootstrap-tooltip htruncate' ));?>
					<?php if(isPluginEnabled('Seats') && !empty($item['CustomPricePerNight'][0]['Hall']['name'])){ ?>
						<span class="textb text-16 graydarkc"><?php echo '['.__l('Venue').' - '.$item['CustomPricePerNight'][0]['Hall']['name'].']';?>
						</span>
					<?php } ?>	
					</h4>
					<?php if(!empty($item['Country']['iso_alpha2'])): ?>
                    <span title="<?php echo $this->Html->cText($item['Item']['address'], false); ?>" class="graydarkc top-smspace show mob-clr mob-dc span9 js-bootstrap-tooltip htruncate"><span title="<?php echo $this->Html->cText($item['Country']['name'], false); ?>" class="flags flag-<?php echo strtolower($item['Country']['iso_alpha2']); ?> mob-inline top-smspace"></span><?php echo $this->Html->cText($item['Item']['address'], false); ?></span>
					<?php endif; ?>
                    <div class="clearfix mob-dc"><span><?php echo __l('Posted on'); ?></span> <span class="graydarkc"  title="<?php echo strftime(Configure::read('site.datetime.tooltip'), strtotime($item['Item']['created'])); ?>"> <?php echo  $this->Time->timeAgoInWords($item['Item']['created']);?></span> </div>
                  </div>
                  <div class="pull-right sep-left mob-clr mob-sep-none">
                    <dl class="dc list mob-clr">
                      <dt class="pr hor-mspace text-11"><?php echo (!empty($price) && $price > 0) ? $label : '';?></dt>
                      <dd class="textb text-24 graydarkc pr hor-mspace">
					  <?php if(!empty($price) && $price > 0) { 
							echo $this->Html->siteCurrencyFormat($price);
						  } else {
							echo __l('Free');
						}
					?>
					  </dd>
                    </dl>
                  </div>
                </div>
                <div class="clearfix hor-space">
                  <div class="span11 no-mar">
                        <div class="clearfix">
                        <div class="pull-left">
                          <dl class="list">
                            <dt class="pr hor-mspace text-11"><?php echo __l('From');?></dt>
                            <dd class="top-space  pr no-mar blackc" title="<?php echo $this->Html->cDateTime($itemUser['ItemUser']['from'], false);?>"> <?php echo $this->Html->cDateTime($itemUser['ItemUser']['from']);?></dd>
                          </dl>
                          </div>
                          <div class="pull-right">
                            <dl class="dc list ">
                              <dt class="pr hor-mspace text-11"><?php echo __l('To');?></dt>
                              <dd class="top-space  pr hor-mspace blackc" title="<?php echo $this->Html->cDateTime($itemUser['ItemUser']['to'], false);?>"> <?php echo $this->Html->cDateTime($itemUser['ItemUser']['to']);?></dd>
                            </dl>
                          </div>
                        </div>
						<?php
								$total_days = getFromToDiff($itemUser['ItemUser']['from'],getToDate($itemUser['ItemUser']['to']));
								$pixels = 0;
								if($total_days > 0) {
									$completed_days = (strtotime(date('Y-m-d')) - strtotime($itemUser['ItemUser']['from'])) /(60*60*24);
									if($completed_days == 0) {
										$completed_days = 1;
									} elseif($completed_days < 0) {
										$completed_days = 0;
									} elseif($completed_days > $total_days) {
										$completed_days = $total_days;	
									}
									$pixels = round(($completed_days/$total_days) * 100);
								}
                            ?>						
                        <div class="span clearfix left-mspace">
                          <div class="progress progress-info bot-mspace">
                            <div style="width:<?php echo $this->Html->cInt($pixels, false); ?>%;" class="bar"></div>
                          </div>
                        </div>
                      </div>
                  <div class="clearfix pull-right top-mspace mob-clr">
                    <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Views');?></dt>
                      <dd title="<?php echo $this->Html->cInt($item['Item']['item_view_count'], false); ?>" class="textb text-16  graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['item_view_count']); ?></dd>
                    </dl>
                    <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Positive');?></dt>
                      <dd title="<?php echo $this->Html->cInt($item['Item']['positive_feedback_count'], false); ?>	" class="textb text-16  graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['positive_feedback_count']); ?>	</dd>
                    </dl>
                    <dl class="sep-right list">
                      <dt class="pr hor-mspace text-11"><?php echo __l('Negative');?></dt>
                      <dd title="<?php echo $this->Html->cInt($item['Item']['item_feedback_count'] - $item['Item']['positive_feedback_count'], false); ?>	" class="textb text-16  graydarkc pr hor-mspace"><?php echo numbers_to_higher($item['Item']['item_feedback_count'] - $item['Item']['positive_feedback_count']); ?>	</dd>
                    </dl>
                    <dl class="list">
                      <dt class="pr mob-clr hor-mspace text-11"><?php echo __l('Success Rate');?></dt>
					  <?php if(empty($item['Item']['item_feedback_count'])): ?>
					  <dd class="textb text-16  graydarkc pr hor-mspace" title="<?php  echo __l('No Bookings available'); ?>"><?php  echo __l('n/a'); ?></dd>
					<?php else:?>
								 <dd class="textb text-16  graydarkc pr hor-mspace">
										<?php if(!empty($item['Item']['positive_feedback_count'])):
										$positive = floor(($item['Item']['positive_feedback_count']/$item['Item']['item_feedback_count']) *100);
										$negative = 100 - $positive;
										else:
										$positive = 0;
										$negative = 100;
										endif;
										
										echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&chf=bg,s,FFFFFF00', array('width'=>'40px','height'=>'40px','title' => $positive.'%'));  ?>
								</dd>
							<?php endif; ?>					  
                    </dl>
                  </div>
                </div>
              </div>
            </div>
