<div class="item_guideline ver-space clearfix">
	<ul class="add-pg-block clearfix unstyled no-mar">
			  <li  class="mob-clr"><span class="btn btn-large mob-clr"><?php echo __l('Add') . ' ' . Configure::read('item.alt_name_for_item_singular_caps'); ?> </span> 	</li>
         <?php if (Configure::read('item.item_fee')) {
						$fee =Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Fee');
						$fee .=$this->Html->siteCurrencyFormat(Configure::read('item.item_fee'),false);
				?>

				<li  class="mob-clr"> <span class="btn btn-large offset1 mob-clr "><?php echo sprintf(__l('Pay %s Fee'), Configure::read('item.alt_name_for_item_singular_caps')).' ('.$fee.')'; ?> </span></li>
				<?php } ?>
				<?php if (!Configure::read('item.is_auto_approve')) { ?>
				<li class="listed mob-clr"> <span class="mob-clr"><?php echo sprintf(__l('Pending (Admin will approve your %s)'), Configure::read('item.alt_name_for_item_singular_small')); ?> </span></li>
				<?php } ?>
				<li  class="mob-clr"> <span class="btn btn-large offset1 mob-clr "><?php echo __l('Listed'); ?></span></li>
			</ul>
 </div>
  

