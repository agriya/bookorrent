<div class="js-responses js-response">
	<div class="itemUsers index js-request-responses">
	<?php
		$filter_class = '';
		if(!empty($this->request->params['isAjax'])) {
			$filter_class = 'js-filter-link js-no-pjax';
		}
	?>
	<?php if(empty($this->request->params['isAjax'])) { ?>
		<div class="clearfix sep-bot">
			<h2 class="ver-space top-mspace text-32 pull-left"><?php echo __l('Booking');?></h2>
			<div class="jobs-inbox-option show-block  clearfix pull-right top-mspace top-space">
				<div class="dropdown top-mspace top-space"> 
					<a data-toggle="dropdown" class="dropdown-toggle text-14 textb graylighterc no-shad js-no-pjax" title="<?php echo __l('Layout'); ?>" href="#"><i class="icon-list-alt no-pad text-16"></i> <?php echo __l('Layout'); ?></a>
					<ul class="dropdown-menu dl arrow">
						<li class="active"><?php echo $this->Html->link('<i class="icon-list"></i>'.__l('List'), array('controller'=> 'item_users', 'action'=>'index', 'type'=>'mytours', 'status' => $this->request->params['named']['status'], 'view' => 'list', 'admin' => false), array('title' => __l('List'), 'class' => 'list','escape' => false));?></li>
						<li><?php echo $this->Html->link('<i class="icon-th"></i>'.__l('Grid'), array('controller'=> 'item_users', 'action'=>'index', 'type'=>'mytours', 'status' => $this->request->params['named']['status'], 'admin' => false), array('title' => __l('Grid'), 'class' => 'grid status_selected','escape' => false));?></li>
					</ul>
				</div>
			</div>
		</div>
	<?php } ?>
		<section class="row ver-space bot-mspace clearfix <?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?>">
		<?php 
			$stat_class = (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'all') ? 'active' : null;
			$active_filter=(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'all') ? 'active-filter' : null;
			$link = array('controller' => 'item_users', 'action' => 'index', 'status' => 'all', 'type' => 'mytours', 'status' => 'all', 'admin' => false);
			echo $this->Html->link('<dl class="dc list users '.$stat_class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc">'.__l('All').'</dt><dd title="'.$all_count.'" class="textb text-20 no-mar graydarkc pr hor-mspace">'.$this->Html->cInt((!empty($all_count) ? $all_count : '0'),false).'</dd></dl>', $link, array('escape' => false, 'class' => $filter_class ));
			foreach($moreActions as $key => $value) {
				$counts = explode(":", $key);
				$class_name = $itemStatusClass[$value] ? $itemStatusClass[$value] :"";
				$stat_class = (!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == $value) ? 'active' : null;
				$link = array('controller'=>'item_users','action'=>'index','status' => $value, 'type'=>'mytours', 'status' => $value,'admin' => false);
				echo $this->Html->link('<dl class="dc list users '.$stat_class .' mob-clr mob-sep-none "><dt class="pr hor-mspace text-11 grayc">'.$counts[0].'</dt><dd title="'.$counts[1].'" class="textb text-20 no-mar graydarkc pr hor-mspace ">'.$this->Html->cInt($counts[1], false).'</dd></dl>', $link, array('escape' => false, 'class' => $filter_class));
			} 
		?>		
		</section>
		<ol class="js-response-actions unstyled prop-list-mob prop-list no-mar top-space item-list  prop-list-mob" start="<?php echo $this->Paginator->counter(array('format' => '%start%'));?>">
		<?php
			if (!empty($itemUsers)) {
				$i = 0;
				$num = $this->Paginator->counter(array('format' => '%start%'));
			foreach ($itemUsers as $itemUser) {
		?>
			<li class="ver-space sep-bot js-map-num<?php echo $num; ?> clearfix">
				<div class="pull-left hor-space hor-mspace">
					<span class="label label-important textb show text-11 prop-count bot-mspace"><?php echo $num; ?></span>
                    <div class="dropdown"> 
						<a data-toggle="dropdown" class="dropdown-toggle text-14 textb graylighterc no-shad js-no-pjax" title="<?php echo __l('Action'); ?>" href="#"><i class="icon-cog graylightc no-pad text-16"></i></a>
						<ul class="dropdown-menu dl arrow">
						<?php 
								if(!empty($itemUser['ItemUser']['is_payment_cleared'])) { 
						?>
							<li><?php echo $this->Html->link('<i class="icon-print"></i>'.__l('Print Ticket'), array('controller' => 'item_user', 'action' => 'view', $itemUser['ItemUser']['id'], 'type'=>'print', 'admin' => false), array('class' => 'print-ticket dl js-no-pjax', 'target' => '_blank', 'title'=>__l('Print Ticket'), false, 'escape' => false));  ?></li>
						<?php 
								} 
								if ($itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforAcceptance ) {
						?>
							<li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Cancel'), array('controller' => 'items', 'action' => 'order', $itemUser['ItemUser']['item_id'] , 'order_id'=>$itemUser['ItemUser']['id'], 'type' => 'cancel', 'admin' => false),array('title' => __l('Cancel') ,'class'=>'js-delete delete','escape' => false));  ?></li>
						<?php 
								} 
								if($this->Auth->user('id') == $itemUser['ItemUser']['user_id'] && $itemUser['ItemUserStatus']['id'] == ConstItemUserStatus::WaitingforReview ) {
						?>
							<li><?php echo $this->Html->link('<i class=" icon-refresh"></i>'.__l('Review'), array('controller'=>'item_feedbacks','action'=>'add','item_order_id' => $itemUser['ItemUser']['id']), array('class' =>'review', 'title' => __l('Review'),'escape' => false)); ?></li>
						<?php 
								} 
							?>
							<li><?php echo $this->Html->link('<i class="icon-eye-open"></i>'.__l('View activities'), array('controller' => 'messages', 'action' => 'activities',  'order_id' => $itemUser['ItemUser']['id']), array('class' =>'view-activities', 'title' => __l('View activities'), 'escape' => false)); ?></li>
						<?php 
							$note_url = Router::url(array(
								'controller' => 'messages',
								'action' => 'activities',
								'order_id' => $itemUser['ItemUser']['id'],
							) , true);
						?>
							<li><?php echo $this->Html->link('<i class="icon-bookmark"></i>'.__l('Private Note'), $note_url.'#Private_Note', array('class' => 'add-note',  'title' => __l('Private Note'),'escape' => false)); ?></li>
						<?php 
							if(($this->request->params['named']['status'] == 'booking_request_confirmed' && !empty($itemUser['ItemUser']['is_booking_request'])) || $this->request->params['named']['status'] == 'payment_pending'){ 
						?>
							<li><?php echo $this->Html->link('<i class="icon-save"></i>'.__l('Book It'), array('controller' => 'items', 'action' => 'order', $itemUser['ItemUser']['item_id'], 'order_id:' . $itemUser['ItemUser']['id'], 'admin' => false), array('class' => 'book-it', 'title' => __l('Book It'),'escape' => false)); ?></li>
						<?php 
							} 
						?>
						</ul>
					</div>
				</div>
                  <div class="span hor-mspace dc">
				  <?php
								$itemUser['Item']['Attachment'][0] = !empty($itemUser['Item']['Attachment'][0]) ? $itemUser['Item']['Attachment'][0] : array();
    							echo $this->Html->link($this->Html->showImage('Item', $itemUser['Item']['Attachment'][0], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($itemUser['Item']['title'], false)), 'title' => $this->Html->cText($itemUser['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $itemUser['Item']['slug'], 'admin' => false), array('title'=>$this->Html->cText($itemUser['Item']['title'], false), 'escape' => false));
    						?>
            				
				</div>
				<div class="pull-left">
					<div class="clearfix left-mspace sep-bot">
						<div class="span12 bot-space no-mar">
							<h4 class="textb text-16">
							<?php
								$lat = $itemUser['Item']['latitude'];
								$lng = $itemUser['Item']['longitude'];
								$id = $itemUser['Item']['id'];
								echo $this->Html->link($this->Html->cText($itemUser['Item']['title'], false), array('controller' => 'items', 'action' => 'view', $itemUser['Item']['slug'], 'admin' => false), array('id'=>"js-map-side-$id",'class'=>"js-bootstrap-tooltip graydarkc js-map-data {'lat':'$lat','lng':'$lng'}",'title'=>$this->Html->cText($itemUser['Item']['title'], false),'escape' => false));
							?>
							</h4>
							<?php
								if(!empty($this->request->params['named']['status']) && $this->request->params['named']['status'] == 'all'):
									$class = '';
									if ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforAcceptance) {
										$class = 'label label-pendingapproval';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Confirmed) {
										$class = 'label label-confirmed';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Rejected) {
										$class = 'label label-reject';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Canceled) {
										$class = 'label label-cancel';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::CanceledByAdmin) {
										$class = 'label label-cancelbyadmin';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::WaitingforReview) {
										$class = 'label label-review';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Completed) {
										$class = 'label label-completed';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::Expired) {
										$class = 'label';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::PaymentPending) {
										$class = 'label label-paymentpending';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::BookingRequest) {
										$class = 'label label-bookingrequest';
									} elseif ($itemUser['ItemUser']['item_user_status_id'] == ConstItemUserStatus::BookingRequestConfirmed) {
										$class = 'label label-bookingrequestconfirmed';
									}
							?>
							<span class="round-3 <?php echo $class; ?>"><?php echo $this->Html->cText($itemUser['ItemUserStatus']['name'],false); ?></span>
							<?php
								endif;
							?>
							<span class="graydarkc top-smspace show mob-clr dc">
							<?php 
								if(!empty($itemUser['Item']['Country']['iso_alpha2'])): 
							?>
								<span class="flags flag-<?php echo $this->Html->cText(strtolower($itemUser['Item']['Country']['iso_alpha2']), false); ?> mob-inline top-smspace" title ="<?php echo $this->Html->cText($itemUser['Item']['Country']['name'], false); ?>"><?php echo $this->Html->cText($itemUser['Item']['Country']['name'], false); ?></span>
							<?php 
								endif; 
							?>
							</span>
							<?php echo $this->Html->cText($itemUser['Item']['address']);?>
						</div>
						<div class="pull-right hor-space sep-left mob-clr mob-sep-none">
							<div class="clearfix">
								<dl class="dc list span mob-clr">
									<dt class="pr hor-mspace text-11">&nbsp;</dt>
									<dd class="textb text-24 graydarkc pr hor-mspace" title="five hundred dollar">
									<?php
										$booker_gross = ($itemUser['ItemUser']['original_price'] + $itemUser['ItemUser']['booker_service_amount'] + $itemUser['ItemUser']['additional_fee_amount']) - $itemUser['ItemUser']['coupon_discount_amont'];
										if($booker_gross > 0){
											if (Configure::read('site.currency_symbol_place') == 'left'):  
												echo Configure::read('site.currency') . ' ' . $this->Html->cCurrency($booker_gross);
											endif;  
											if (Configure::read('site.currency_symbol_place') == 'right'): 
												echo $this->Html->cCurrency($booker_gross) . ' ' . Configure::read('site.currency'); 
											endif; 
										}else{
											echo __l('Free'); 
										}
									?>
									</dd>
								</dl>
							</div>
						</div>
					</div>
                    <div class="clearfix left-mspace">
						<div class="span12 no-mar">
							<div class="span clearfix no-mar">
								<dl class="list span8 mob-clr">
									<dt class="pr hor-mspace text-11"><?php echo __l('From');?></dt>
									<dd  class="top-space  pr no-mar blackc"><?php echo $this->Html->cDate($itemUser['ItemUser']['from']);?></dd>
								</dl>
								<dl class="dc list span mob-clr">
									<dt class="pr hor-mspace text-11"><?php echo __l('To');?></dt>
									<dd  class="top-space  pr hor-mspace blackc"> <?php echo $this->Html->cDate(getToDate($itemUser['ItemUser']['to']));?></dd>
								</dl>
							</div>
							<div class="span clearfix no-mar">
							<?php
								$total_days = getFromToDiff($itemUser['ItemUser']['from'], getToDate($itemUser['ItemUser']['to']));
								$completed_days = (strtotime(date('Y-m-d')) - strtotime($itemUser['ItemUser']['from'])) /(60*60*24);
								if($completed_days == 0) {
									$completed_days = 1;
								} elseif($completed_days < 0) {
									$completed_days = 0;
								} elseif($completed_days > $total_days) {
									$completed_days = $total_days;	
								}
								$pixels = 0;
								if($total_days > 0) {
									$pixels = round(($completed_days/$total_days) * 100);
								}
                            ?> 
								<div class="progress progress-info bot-mspace">
									<div class="bar" style="width:<?php echo $pixels; ?>%;"></div>
								</div>
							</div>
						</div>
						<div class="clearfix pull-right top-mspace right-space mob-clr">
							<dl class="dc mob-clr sep-right list">
								<dt class="pr hor-smspace text-11"><?php echo __l('Booking ID #'); ?></dt>
								<dd title="<?php echo $this->Html->cInt($itemUser['ItemUser']['id'], false); ?>" class="textb text-16 no-mar graydarkc pr hor-space"><?php echo $this->Html->cInt($itemUser['ItemUser']['id'], false);?></dd>
							</dl>
							<dl class="dc mob-clr sep-right list">
								<dt class="pr hor-mspace text-11"> <?php echo __l('Host'); ?></dt>
								<dd  class="textb text-16 no-mar graydarkc pr hor-space">	<?php echo !empty($itemUser['Item']['User']['username']) ? $this->Html->link($this->Html->cText($itemUser['Item']['User']['username'], false), array('controller' => 'users', 'action' => 'view', $itemUser['Item']['User']['username'] ,'admin' => false), array('title'=>$this->Html->cText($itemUser['Item']['User']['username'],false),'escape' => false,'class'=>'graydarkc')) : ''; ?></dd>
							</dl>
							<dl class="dc mob-clr list">
								<dt class="pr hor-mspace text-11"><?php echo __l('Days');?></dt>
								<dd title="<?php echo getFromToDiff($itemUser['ItemUser']['from'], getToDate($itemUser['ItemUser']['to'])); ?>" class="textb text-16 no-mar graydarkc pr hor-space"><?php echo $this->Html->cInt(getFromToDiff($itemUser['ItemUser']['from'], getToDate($itemUser['ItemUser']['to']))); ?></dd>
							</dl>
						</div>
					</div>
					<?php if(!empty($itemUser['ItemUser']['booker_private_note'])): ?>
					<p class="text16"><span class="textb"><?php echo __l('Private Note'); ?>:</span><?php echo $this->Html->cText($itemUser['ItemUser']['booker_private_note']);?></p>
					<?php endif; ?>
				</div>		
			</li>
		<?php 
			$num++;
			}  
		?>
		</ol>
	<?php 
		} else { 
	?>
		<ol class="unstyled sep-top">
			<li>
				<div class="space dc grayc">
					<p class="ver-mspace top-space text-16"><?php echo __l('No Bookings available');?></p>
				</div>
			</li>	
		</ol>
	<?php 
		}
	if (!empty($itemUsers)) { 
	?>
		<div class="<?php echo (!empty($this->request->params['isAjax'])) ? " js-pagination" : "" ; ?> clearfix space pull-right mob-clr dc">
		<?php echo $this->element('paging_links'); ?>
		</div>
	<?php	
	}
	?>
	</div>
</div>