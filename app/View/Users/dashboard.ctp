<?php /* SVN: $Id: view.ctp 4973 2010-05-15 13:14:27Z aravindan_111act10 $ */ ?>
<h2 class="ver-space top-mspace text-32"><?php echo __l('Dashboard') ?></h2>
<div class="row no-mar">
    <div class="row ver-space bot-mspace">
	  <div class="clearfix span bot-mspace tab-no-pad">
	    <div class="thumb img-polaroid pull-left">
	    	<?php $current_user_details = array(
			'username' => $this->Auth->user('username'),
			'role_id' =>  $this->Auth->user('role_id'),
			'id' =>  $this->Auth->user('id'),
			'facebook_user_id' =>  $this->Auth->user('facebook_user_id'),
			'user_avatar_source_id' =>  $this->Auth->user('user_avatar_source_id'),
			'twitter_avatar_url' =>$this->Auth->user('twitter_avatar_url')
			);
			echo $this->Html->getUserAvatar($current_user_details, 'small_big_thumb', true); ?>
	  </div>
	  <div class="pull-left text-14 hor-space span6 no-mar">
	    <p class="graydarkc text-24 textb mob-text-24 bot-mspace show"><?php echo $this->Html->getUserLink($user['User']); ?></p>
		<?php if($this->Auth->sessionValid() && isPluginEnabled('Wallet')): ?>
		<dl class="top-mspace clearfix">
		  <dt class="bot-space inline textn no-mar"><?php echo __l('Balance') ?></dt>
		  <dd class="bot-space inline graydarkc textb">
			<?php 
			  $balance = $this->Html->getCurrUserInfo($this->Auth->user('id'));
			  echo $this->Html->siteCurrencyFormat($balance['User']['available_wallet_amount']);
			?>
		  </dd>
		</dl>
		<?php endif; ?>
		<?php if (isPluginEnabled('Wallet')) { ?>
		  <?php echo $this->Html->link(__l('Add Amount to Wallet'), array('controller' => 'wallets', 'action' => 'add_to_wallet', 'admin' => false), array('title' => __l('Add Amount to Wallet'),'class'=>'btn btn-large btn-primary text-14 textb pull-right top-smspace'));?>
		<?php  } ?>
	  </div>
	  </div>
	  <div class="clearfix mob-clr pull-left left-space tab-clr">
		<div class="span tab-clr hor-space sep-right">
		  <h3 class="textb text-24"><?php echo __l('Hosting'); ?></h3>
		  <dl class="top-mspace clearfix mob-dc">
			<dt class="bot-space inline textn no-mar"><?php echo Configure::read('item.alt_name_for_item_plural_caps') . ' ' . __l('posted') ?></dt>
			<dd class="bot-space inline graydarkc textb"><?php echo (!empty($all_post_listing_count) ? $this->Html->cInt($all_post_listing_count) : '0') ?></dd>
		  </dl>
		  <div class="clearfix">
			<dl class="sep-left sep-right list">
			  <dt class="pr hor-mspace text-11"><?php echo __l('Positive') ?></dt>
			  <dd title="234" class="textb text-20 graydarkc pr hor-mspace"><?php echo numbers_to_higher($user['User']['positive_feedback_count']); ?></dd>
			</dl>
			<dl class="sep-right list">
			  <dt class="pr hor-mspace text-11"><?php echo __l('Negative') ?></dt>
			  <dd title="689" class="textb text-20 graydarkc pr hor-mspace"><?php echo numbers_to_higher($user['User']['item_feedback_count'] - $user['User']['positive_feedback_count']); ?></dd>
			</dl>
			<dl class="list">
			  <dt class="pr hor-mspace text-11"><?php echo __l('Success Rate') ?></dt>
			  <?php if(($user['User']['item_feedback_count']) == 0): ?>
				<dd class="textb text-20 no-mar graydarkc left-space pr hor-mspace" title="<?php echo __l('n/a') ; ?>"><?php echo __l('n/a') ; ?></dd>
				<?php else: ?>
				<dd class="textb text-20 no-mar graydarkc pr hor-mspace"><span class="stats-val">
				<?php	if(!empty($user['User']['positive_feedback_count'])):
					$positive = floor(($user['User']['positive_feedback_count']/$user['User']['item_feedback_count']) *100);
					$negative = 100 - $positive;
					else:
						$positive = 0;
						$negative = 100;
					endif;
					
					echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&amp;chf=bg,s,FFFFFF00', array('width'=>'35px','height'=>'35px','title' => $positive.'%')); ?></span>
				</dd>
				<?php endif;?> 
			</dl>
		  </div>
		</div>
		<div class="span tab-clr left-space">
		  <h3 class="textb text-24"><?php echo __l('Booking') ?></h3>
		  <dl class="clearfix top-mspace dc">
			<dt class="bot-space inline no-mar textn"><?php echo __l('Requests Posted') ?></dt>
			<dd class="bot-space graydarkc textb inline"><?php echo (!empty($user['User']['request_count']) ? $this->Html->cInt($user['User']['request_count']) : '0') ?></dd>
		  </dl>
		  <div class="clearfix">
			<dl class="dc sep-left sep-right list">
			  <dt class="pr hor-mspace text-11"><?php echo __l('Positive') ?></dt>
			  <dd title="234" class="textb text-20 graydarkc pr hor-mspace"><?php echo numbers_to_higher($user['User']['booker_positive_feedback_count']); ?></dd>
			</dl>
			<dl class="sep-right list">
			  <dt class="pr hor-mspace text-11"><?php echo __l('Negative') ?></dt>
			  <dd title="689" class="textb text-20 graydarkc pr hor-mspace"><?php echo numbers_to_higher($user['User']['booker_item_user_count'] - $user['User']['booker_positive_feedback_count']); ?></dd>
			</dl>
			<dl class="list">
			  <dt class="pr hor-mspace text-11"><?php echo __l('Success Rate') ?></dt>
			  <?php if(($user['User']['booker_item_user_count']) == 0): ?>
		<dd class="textb text-20 no-mar graydarkc left-space pr hor-mspace" title="<?php echo __l('n/a') ; ?>"><?php echo __l('n/a') ; ?></dd>
	   <?php else: ?>
	   <dd class="textb text-20 no-mar graydarkc pr hor-mspace"><span class="stats-val">
		<?php if(!empty($user['User']['booker_positive_feedback_count'])):
					$positive = floor(($user['User']['booker_positive_feedback_count']/$user['User']['booker_item_user_count']) *100);
					$negative = 100 - $positive;
				else:
					$positive = 0;
					$negative = 100;
				endif;
					echo $this->Html->image('http://chart.googleapis.com/chart?cht=p&amp;chd=t:'.$positive.','.$negative.'&amp;chs=50x50&amp;chco=8DCA35|F47564&amp;chf=bg,s,FFFFFF00', array('width'=>'35px','height'=>'35px','title' => $positive.'%')); ?>
			</span>
		</dd>
		<?php endif; ?>
			</dl>
		  </div>
		</div>
	  </div>
	  <?php echo $this->element('sidebar', array('config' => 'sec'));?>
	</div>
  <?php echo $this->element('dashboard_tabs', array('config' => 'sec')); ?>
</div>
