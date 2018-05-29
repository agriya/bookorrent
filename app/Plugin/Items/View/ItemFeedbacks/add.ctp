<?php /* SVN: $Id: $ */ ?>
<div class="itemFeedbacks  form clearfix">

<div class="clearfix">
	

		<div class="top-space">
		<ol class="span24 unstyled prop-list-mob prop-list no-mar" >
		<li class="span24 clearfix ver-space sep-bot mob-no-mar js-map-num no-mar">
               
              
                <div class="span hor-mspace dc mob-no-mar">
				<?php echo $this->Html->showImage('Item', $itemInfo['Item']['Attachment'][0], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($itemInfo['Item']['title'], false)), 'title' => $this->Html->cText($itemInfo['Item']['title'],false)));?>
				</div>
                <div class="span20 pull-right no-mar mob-clr tab-clr">
                  <div class="clearfix left-mspace sep-bot">
                    <div class="span bot-space no-mar">
                      <h4 class="textb text-16">
						<?php echo $this->Html->link($this->Html->cText($itemInfo['Item']['title'],false), array('controller' => 'items', 'action' => 'view', $itemInfo['Item']['slug']), array('target' => '_blank', 'title' => $this->Html->cText($itemInfo['Item']['title'], false),'escape' => false, 'class' => 'graydarkc span9 js-bootstrap-tooltip htruncate'));?>
					  </h4>
                      <a href="#" class="graydarkc top-smspace show mob-clr dc" title="<?php echo $this->Html->cText($itemInfo['Item']['address'], false);?>">
					  <?php if(!empty($itemInfo['Country']['iso_alpha2'])): ?>
						<span class="flags flag-in mob-inline top-smspace" title="<?php echo $this->Html->cText($itemInfo['Country']['name'], false); ?>"><?php echo $itemInfo['Country']['name']; ?></span>
					  <?php endif; ?>
					  <?php echo $this->Html->cText($itemInfo['Item']['address'], false);?></a> 
					</div>
					<?php 
					$label = "";
					$price = 0;
					if($itemInfo['Item']['is_people_can_book_my_time'] == 1) {
						if(!empty($itemInfo['Item']['custom_source_id']) && $itemInfo['Item']['custom_source_id'] == ConstCustomSource::Hour) {
							$label = __l('Per Hour');
						} else if(!empty($itemInfo['Item']['custom_source_id']) && $itemInfo['Item']['custom_source_id'] == ConstCustomSource::Day) {
							$label = __l('Per Day');
						} else if(!empty($itemInfo['Item']['custom_source_id']) && $itemInfo['Item']['custom_source_id'] == ConstCustomSource::Week) {
							$label = __l('Per Week');
						} else if(!empty($itemInfo['Item']['custom_source_id']) && $itemInfo['Item']['custom_source_id'] == ConstCustomSource::Month) {
							$label = __l('Per Month');
						}
					} else if($itemInfo['Item']['is_sell_ticket'] == 1) {
						$label = __l('From');
					}	
						$price = $itemInfo['Item']['minimum_price']; 
					?>
                    <div class="pull-right sep-left mob-clr mob-sep-none">
                      <dl class="dc list span mob-clr">
                        <dt class="pr hor-mspace text-11"><?php echo $this->Html->cText($label, false);?></dt>
                        <dd class="textb text-24 graydarkc pr hor-mspace">
						<?php if (Configure::read('site.currency_symbol_place') == 'left'): ?>
							<?php echo Configure::read('site.currency').' '?>
						<?php endif; ?>
						<?php echo $this->Html->cCurrency($price);?>
						<?php if (Configure::read('site.currency_symbol_place') == 'right'): ?>
							 <?php echo ' '.Configure::read('site.currency'); ?>
						<?php endif; ?>
					</dd>
                      </dl>
                    </div>
                  </div>
                  <div class="clearfix left-mspace">
                    
                    <div class="clearfix pull-right top-mspace mob-clr">
					  
                      <dl class="dc mob-clr sep-right list">
                        <dt class="pr hor-mspace text-11" ><?php echo __l('Views');?></dt>
                        <dd class="textb text-16 no-mar graydarkc pr hor-mspace js-view-count-item-id js-view-count-item-id-<?php echo $this->Html->cText($itemInfo['Item']['id'], false); ?> {'id':'<?php echo $this->Html->cText($itemInfo['Item']['id'], false); ?>"><?php echo numbers_to_higher($itemInfo['Item']['item_view_count']); ?></dd>
                      </dl>
                      <dl class="dc mob-clr sep-right list">
                        <dt class="pr hor-smspace text-11" ><?php echo __l('Positive');?></dt>
                        <dd  class="textb text-16 no-mar graydarkc pr hor-mspace"><?php echo numbers_to_higher($itemInfo['Item']['positive_feedback_count']); ?></dd>
                      </dl>
                      <dl class="dc mob-clr sep-right list">
                        <dt class="pr hor-mspace text-11"><?php echo __l('Negative');?></dt>
                        <dd class="textb text-16 no-mar graydarkc pr hor-mspace"><?php echo numbers_to_higher($itemInfo['Item']['item_feedback_count'] - $itemInfo['Item']['positive_feedback_count']); ?></dd>
                      </dl>
                      <dl class="dc mob-clr sep-right list">
                        <dt class="pr mob-clr hor-mspace text-11"><?php echo __l('Success Rate');?></dt>
						<?php if(empty($itemInfo['Item']['item_feedback_count'])){ ?>
							<dd  class="textb text-16 no-mar graydarkc pr hor-mspace">n/a</dd>
						<?php }else{ ?>
						<dd class="textb text-16 no-mar graydarkc pr hor-mspace">
							<?php
								if(!empty($itemInfo['Item']['positive_feedback_count'])){
									$positive = floor(($itemInfo['Item']['positive_feedback_count']/$itemInfo['Item']['item_feedback_count']) *100);
									$negative = 100 - $positive;
								}else{
									$positive = 0;
									$negative = 100;
								}
								echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&amp;chf=bg,s,FFFFFF00', array('width'=>'35px','height'=>'35px','title' => $positive.'%'));
							?>
						</dd>
						<?php } ?>
						</dl>
					
					  
                    </div>
                  </div>
                </div>
              </li>
			  </ol>
			  </div>
			  </div>
		
<?php echo $this->Form->create('ItemFeedback', array('class' => 'form-horizontal','enctype' => 'multipart/form-data'));?>

	<div class="top-space sep-bot massage-view-block clearfix">
	<h3 class="well space textb text-16 no-mar"><?php echo sprintf(__l('Review this %s and host'), Configure::read('item.alt_name_for_item_singular_small'));?></h3>
	<?php
		echo $this->Form->input('item_id',array('type'=>'hidden','value' => $message['item_id']));
		echo $this->Form->input('item_user_user_id',array('type'=>'hidden','value' => $message['item_user_user_id']));
		echo $this->Form->input('user_id',array('type'=>'hidden','value' => $this->Auth->user('id')));
		echo $this->Form->input('item_order_id',array('type'=>'hidden','value' => $message['item_order_id']));
		echo $this->Form->input('item_user_id',array('type'=>'hidden','value' => $message['item_user_id']));
		echo $this->Form->input('item_order_user_email',array('type'=>'hidden','value' => $message['item_seller_email']));
		?>
		<div class="clearfix bot-space">
			<dl class="dl-horizontal">
				<dt class="dr bot-space"><?php echo __l('From:'); ?></dt>
					<dd class="bot-space"><?php echo $this->Html->cDateTime($itemInfo['ItemUser']['from']);?></dd>
				<dt class="dr"><?php echo __l('To:'); ?></dt>
					<dd><?php echo $this->Html->cDateTime(getToDate($itemInfo['ItemUser']['to']));?></dd>
			</dl>
		</div>
	</div>
	<?php
		if (!empty($message['attachment'])) :
			?>
			<h4><?php echo count($message['attachment']).' '. __l('attachments');?></h4>
			<ul>
			<?php
			foreach($message['attachment'] as $attachment) :
		?>
			<li>
			<span class="attachement"><?php echo $this->Html->cText($attachment['filename'], false); ?></span>
			<span><?php echo bytes_to_higher($attachment['filesize']); ?></span>
			<span><?php echo $this->Html->link(__l('Download') , array( 'controller' => 'messages', 'action' => 'download', $message['message_hash'], $attachment['id'])); ?></span>
			</li>
		<?php
			endforeach;
		?>
		</ul>
		<?php
		endif;
		?> 		
		<fieldset>
		
		 
    <div class="padd-center clearfix">
	
	<div class="items-download-block">
        <div class="clearfix">
		<div class="pull-left span4 span4-sm no-mar dr mob-dl">
			<span class="space show top-mspace"><?php echo __l('Are you satisfied in the bookings?');?></span>
			</div>
		<div class="pull-left no-mar bot-space span20 span20-sm">
			<div class="input radio radio-active-style no-mar top-space">
		<?php
			echo $this->Form->input('is_satisfied',array('label' => __l('Satisfied'),'div'=>'input radio no-mar ', 'type'=>'radio','legend'=>false,'options'=>array('1'=>__l('Yes'),'0'=>__l('No')),'class' => '' ));
		?>
		</div>
		</div>
        </div>
		<div class="js-negative-block <?php echo ($this->request->data['ItemFeedback']['is_satisfied'] == 0) ? '' : 'hide'; ?>">
			<p class="negative-block-info"><?php echo __l('Please give your host a chance to improve his work before submitting a negative review. ').' '.$this->Html->link(__l('Contact Your Seller'), array('controller'=>'messages','action'=>'compose','type' => 'contact','to' => $message['item_seller_username'],'item_order_id' => $message['item_order_id'], 'review' => '1'), array('title' => __l('Contact Your Seller')));?></p>
		</div>
		<?php
			echo $this->Form->input('feedback',array('label' => __l('Review')));
		?>
	</div>
  
    </div>
	
	</fieldset>
	<div class="alert alert-info"><?php echo sprintf(__l('Optional: Upload the photos and videos you have taken in/about this %s. This will help other future guests.'), Configure::read('item.alt_name_for_item_singular_small')); ?></div>
<fieldset>
<h3 class="well space textb text-16 no-mar"><?php echo __l('Photos');?></h3>

						
						<div class="padd-center">
							   				
							<div class="picture">
								<ol class=" upload-list clearfix unstyled">
									<?php	for($i = 0; $i<Configure::read('itemfeedbacks.max_upload_photo'); $i++):  ?>
										
										<li class="dc clearfix inline">
											<?php echo $this->Form->file('Attachment.0.'.$i, array('label' => true, 'div' => true)); ?>
											<div class="js-overlabel img-caption">
												<?php  echo $this->Form->input('Attachment.'.$i.'.description', array('type' => 'text', 'label' => false, 'placeholder' => __l('Caption'))); ?>
											</div>											
										</li>
									<?php
									endfor;
									?>
								</ol>
									
							</div>
						</div>
			</fieldset>
			<fieldset>
	
	<h3 class="well space textb text-16 no-mar"><?php echo __l('Video'); ?></h3>
							
						<div class="padd-center">
							 				
								<?php echo $this->Form->input('video_url', array('label' => __l('Video URL'))); ?>
						</div>
						
					</fieldset>
		<div class="form-actions">
<?php echo $this->Form->submit(__l('Submit'),array('class'=>'btn btn-large btn-primary textb text-16'));?>

<?php echo $this->Form->end();?>

</div>