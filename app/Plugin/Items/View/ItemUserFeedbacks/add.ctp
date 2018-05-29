<div class="itemFeedbacks  form clearfix">

		<ol class="span24 unstyled prop-list-mob prop-list no-mar" >
		<li class="span24 clearfix ver-space sep-bot mob-no-mar js-map-num no-mar">
               
              
                <div class="span hor-mspace dc mob-no-mar">
				<?php echo $this->Html->showImage('Item', $itemInfo['Item']['Attachment'][0], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($itemInfo['Item']['title'], false)), 'title' => $this->Html->cText($itemInfo['Item']['title'],false)));?>
				</div>
                <div class="span20 pull-right no-mar mob-clr tab-clr">
                  <div class="clearfix left-mspace sep-bot">
                    <div class="span bot-space no-mar">
                      <h4 class="textb text-16">
        		<?php 
					$current_user_details = array(
						'username' => $itemInfo['Item']['User']['username'],
						'role_id' => $itemInfo['Item']['User']['role_id'],
						'id' => $itemInfo['Item']['User']['id'],
						'facebook_user_id' => $itemInfo['Item']['User']['facebook_user_id']
					);
					$current_user_details['UserAvatar'] = array(
						'id' => $itemInfo['Item']['User']['attachment_id']
					);
					//echo $this->Html->getUserAvatarLink($current_user_details, 'small_thumb');
				$attachment = array('id'=>$itemInfo['Item']['User']['attachment_id']);
			?>					  
						<?php echo $this->Html->link($this->Html->cText($itemInfo['Item']['title'],false), array('controller' => 'items', 'action' => 'view', $itemInfo['Item']['slug']), array('target' => '_blank', 'title' => $this->Html->cText($itemInfo['Item']['title'], false),'escape' => false, 'class' => 'js-bootstrap-tooltip htruncate span11'));?>
					  </h4>
                      <a href="#" class="graydarkc top-smspace mob-clr htruncate span8 js-bootstrap-tooltip" title="<?php echo $this->Html->cText($itemInfo['Item']['address'], false);?>">
					  <?php if(!empty($itemInfo['Item']['Country']['iso_alpha2'])): ?>
						<span class="flags flag-<?php echo strtolower($itemInfo['Item']['Country']['iso_alpha2']); ?> mob-inline top-smspace" title="<?php echo $this->Html->cText($itemInfo['Item']['Country']['name'], false); ?>"> <?php echo $this->Html->cText($itemInfo['Item']['Country']['name'], false); ?></span>
					  <?php endif; ?>
					  <?php echo $this->Html->cText($itemInfo['Item']['address'], false);?></a> 				  
					</div>
					<?php 
					$label = "";
					$price = 0;
					if($itemInfo['Item']['is_people_can_book_my_time'] == 1) {
						if(!empty($itemInfo['Item']['price_per_hour'])) {
							$label = __l('Per Hour');
							$price = $itemInfo['Item']['price_per_hour'];
						} else if(!empty($itemInfo['Item']['price_per_day'])) {
							$label = __l('Per Day');
							$price = $itemInfo['Item']['price_per_day'];
						} else if(!empty($itemInfo['Item']['price_per_week'])) {
							$label = __l('Per Week');
							$price = $itemInfo['Item']['price_per_week'];
						} else if(!empty($itemInfo['Item']['price_per_month'])) {
							$label = __l('Per Month');
							$price = $itemInfo['Item']['price_per_month'];
						}
					} else if($itemInfo['Item']['is_sell_ticket'] == 1) {
						$label = __l('From');
						$price = $itemInfo['Item']['minimum_price'];
					} 
					?>
                    <div class="pull-right sep-left mob-clr mob-sep-none">
                      <dl class="dc list span mob-clr">
                        <dt class="pr hor-mspace text-11"><?php echo $label;?></dt>
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
                        <dd class="textb text-16 no-mar graydarkc pr hor-mspace js-view-count-item-id js-view-count-item-id-<?php echo $itemInfo['Item']['id']; ?> {'id':'<?php echo $itemInfo['Item']['id']; ?>"><?php echo numbers_to_higher($itemInfo['Item']['item_view_count']); ?></dd>
                      </dl>
                      <dl class="dc mob-clr sep-right list">
                        <dt class="pr hor-smspace text-11" ><?php echo __l('Positive');?></dt>
                        <dd  class="textb text-16 no-mar graydarkc pr hor-mspace"><?php echo numbers_to_higher($itemInfo['Item']['positive_feedback_count']); ?></dd>
                      </dl>
                      <dl class="dc mob-clr sep-right list">
                        <dt class="pr hor-mspace text-11"><?php echo __l('Negative');?></dt>
                        <dd class="textb text-16 no-mar graydarkc pr hor-mspace"><?php echo numbers_to_higher($itemInfo['Item']['item_feedback_count'] - $itemInfo['Item']['positive_feedback_count']); ?></dd>
                      </dl>
          			  <dl class="dc mob-clr list">
						<dt class="pr mob-clr hor-mspace text-11"><?php echo __l('Success Rate');?></dt>
						<?php if(empty($itemInfo['Item']['item_feedback_count'])){ ?>
						  <dd  class="textb text-16 no-mar graydarkc pr hor-mspace">n/a</dd>
						<?php }else{ ?>
						  <dd class="textb text-16 no-mar graydarkc pr hor-mspace">
							<?php if(!empty($itemInfo['Item']['positive_feedback_count'])){
							  $positive = floor(($itemInfo['Item']['positive_feedback_count']/$itemInfo['Item']['item_feedback_count']) *100);
							  $negative = 100 - $positive;
							}else{
							  $positive = 0;
							  $negative = 100;
							}
							echo	$this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&amp;chf=bg,s,FFFFFF00', array('width'=>'35px','height'=>'35px','title' => $positive.'%'));?>
						  </dd>
						<?php } ?>
					  </dl>				  
                    </div>
                  </div>
                </div>
              </li>
			  </ol>
<?php echo $this->Form->create('ItemUserFeedback', array('class' => 'form-horizontal space'));?>

	<div class="top-space massage-view-block clearfix">
	<?php
		echo $this->Form->input('item_id',array('type'=>'hidden','value' => $message['item_id']));
		echo $this->Form->input('item_user_user_id',array('type'=>'hidden','value' => $message['item_user_user_id']));
		echo $this->Form->input('booker_user_id',array('type'=>'hidden','value' => $message['item_user_user_id']));
		echo $this->Form->input('host_user_id',array('type'=>'hidden','value' => $this->Auth->user('id')));
		echo $this->Form->input('item_order_id',array('type'=>'hidden','value' => $message['item_order_id']));
		echo $this->Form->input('item_user_id',array('type'=>'hidden','value' => $message['item_user_id']));
		echo $this->Form->input('item_order_user_email',array('type'=>'hidden','value' => $message['item_booker_email']));
		?>
	<div class="massage-head top-space clearfix">
	<h3 class="well space textb text-16">
		<?php	//echo 'Message from '.$this->Html->link($this->Html->cText($message['item_username'],false), array('controller'=> 'users', 'action' => 'view', $message['item_username']), array('title' => $this->Html->cText($message['item_username'],false),'escape' => false));?>
		<?php echo __l('Review and rate this') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps');?>
    </h3>
	<div>
		<?php 		
			$from_date = strtotime($itemInfo['ItemUser']['from']);
			$to_date = strtotime($itemInfo['ItemUser']['to']);
			if (strtotime($itemInfo['ItemUser']['from']) > 0) {
				$from_time = date('h:i a',strtotime($itemInfo['ItemUser']['from']));
			} else {
				$from_time = '';
			}
			if (strtotime($itemInfo['ItemUser']['to']) > 0) {
				$to_time = date('h:i a',strtotime($itemInfo['ItemUser']['to']));
			} else {
				$to_time = '';
			}

		?>
		<div class="offset5 clearfix bot-space">
			<dl class="dc span mob-clr">
                  <dt class="pr hor-mspace text-12 textb">
    	   <?php
				$current_user_details = array(
					'username' => $booker['User']['username'],
					'role_id' => $booker['User']['role_id'],
					'id' => $booker['User']['id'],
					'facebook_user_id' => $booker['User']['facebook_user_id']
				);
				$current_user_details['UserAvatar'] = array(
					'id' => $booker['User']['attachment_id']
				);
				echo $this->Html->getUserAvatarLink($current_user_details, 'small_thumb');
			?>
			</dt>
			<dd class="text-12 top-space graydarkc pr hor-mspace">
    	   <?php echo $this->Html->link($this->Html->cText($booker['User']['username'],false), array('controller' => 'users', 'action' => 'view', $booker['User']['username']), array('escape' => false));?>
    	   </dd>
		   </dl>
			<dl class="dc span mob-clr">
              <dd class="text-12 top-space graydarkc pr hor-mspace">
        		<?php echo date('D, d M Y', $from_date); ?>
			</dd>
			<dt class="pr hor-mspace textn top-space text-12">								
        		<?php echo $from_time; ?>
    	   </dt>
		   </dl>
			<dl class="dc span mob-clr offset2">						
				<dd class="text-12 top-space graydarkc pr hor-mspace" >
            	<?php echo date('D, d M Y', $to_date); ?>
				</dd>
				<dt class="pr hor-mspace text-12 textn top-space" >
        		<?php echo $to_time; ?>				
    		</dt>
		   </dl>
		</div>
	</div>
    <?php
		//$replace = array('##REVIEW##' => '', '##NEWORDER##' => '');
		//$message_content =  strtr($message['message'],$replace);
	?>
	<?php
		if (!empty($message['attachment'])) :
			?>
			<h4><?php echo count($message['attachment']).' '. __l('attachments');?></h4>
			<ul>
			<?php
			foreach($message['attachment'] as $attachment) :
		?>
			<li>
			<span class="attachement"><?php echo $attachment['filename']; ?></span>
			<span><?php echo bytes_to_higher($attachment['filesize']); ?></span>
			<span><?php echo $this->Html->link(__l('Download') , array( 'controller' => 'messages', 'action' => 'download', $message['message_hash'], $attachment['id'])); ?></span>
			</li>
		<?php
			endforeach;
		?>
		</ul>
		<?php
		endif;
		?> 		</div>
		<fieldset>
	<div class="items-download-block">
        <div class="clearfix">
		<h3 class="well space textb text-16"> <?php echo sprintf(__l('Are you satisfied this %s?'), Configure::read('item.alt_name_for_booker_singular_small'));?></h3>
		<div class="radio-active-style">
		<?php
			echo $this->Form->input('is_satisfied',array('label' => __l('Satisfied'),'div'=>'input radio feedback-block ', 'type'=>'radio','legend'=>false,'options'=>array('1'=>__l('Yes'),'0'=>__l('No')),'class' => '' ));
		?>
		</div>
        </div>
		<div class="js-negative-block <?php echo ($this->request->data['ItemUserFeedback']['is_satisfied'] == 0) ? '' : 'hide'; ?>">
			<p class="negative-block-info"><?php echo __l('Please give your host a chance to improve his work before submitting a negative review. ').' '.$this->Html->link(__l('Contact Your Seller'), array('controller'=>'messages','action'=>'compose','type' => 'contact','to' => $message['item_seller_username'],'item_order_id' => $message['item_order_id'], 'review' => '1'), array('title' => __l('Contact Your Seller')));?></p>
		</div>
		<?php
			echo $this->Form->input('feedback',array('label' => __l('Review')));
		?>
	</div>  
	</fieldset>

		<div class="form-actions">
<?php echo $this->Form->submit(__l('Submit'), array('class' => 'btn btn-large btn-primary textb text-16'));?>
</div>
</div>
<?php echo $this->Form->end();?>

</div>