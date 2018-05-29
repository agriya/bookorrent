<h2 class="ver-space clearfix sep-bot top-mspace text-32"><?php echo __l('How it Works'); ?></h2>
<div class="text-18 top-mspace top-space textb"><?php echo sprintf(__l('Book a %s'), Configure::read('item.alt_name_for_item_singular_caps')); ?></div>
<div class= "thumbnail clearfix space">
	<?php echo $this->element('svg', array('config' => 'sec')); ?>
</div>
<?php if(Configure::read('item.booking_service_fee')) { ?>
<div class="space bot-mspace" >
<span class="textb"><?php echo __l('Site Service Fee from') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps'); ?> :</span> <?php echo Configure::read('item.booking_service_fee').'%';?>
</div>
<?php } ?>
<?php if(Configure::read('item.host_commission_amount')) { ?>
<div class="space bot-mspace" >
<span class="textb"><?php echo __l('Site Service Fee from Host'); ?> :</span> <?php echo Configure::read('item.host_commission_amount').'%';?>
</div>
<?php } ?>