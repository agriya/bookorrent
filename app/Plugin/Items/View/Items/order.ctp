<?php /* SVN: $Id: $ */ ?>
<?php
	if(!empty($this->request->params['isAjax'])):
		echo $this->element('flash_message', array('config' => 'sec'));
	endif;
?>
<div class = "clearfix sep-bot">
	<div class = "span11">
		<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='contact'):?>
			<h2 class="ver-space text-32" ><?php echo __l('Pricing Negotiation');?></h2>
		<?php elseif(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='accept'):?>
			<h2 class="ver-space text-32" ><?php echo __l('Booking Request Confirm');?></h2>
		<?php elseif(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='cancel'):?>
			<h2 class="ver-space text-32"><?php echo __l('Booking Cancel Process');?></h2>
		<?php elseif(!empty($this->request->params['named']['order_id'])):?>
			<h2 class="ver-space text-32"><?php echo __l('Book It');?></h2>
		<?php endif; ?>
	</div>
	<div class="span5 right-timer">
		<?php
			if(!empty($this->request->params['named']['order_id']) && empty($this->request->params['named']['type'])){
				if(isPluginEnabled('Seats') && $itemDetail['ItemUser'][0]['is_seating_selection'] && !empty($itemDetail['ItemUser'][0]['CustomPricePerTypesSeat'])) {			
					echo '<div class="clock seat-clock pull-right" data_url="'.$url.'" data_time="'.$total.'"></div>';
				}
			}
		?>
	</div>
	<div class="span1">
		<?php 
			if(!empty($this->request->params['named']['order_id']) && empty($this->request->params['named']['type'])){
				if(isPluginEnabled('Seats') && $itemDetail['ItemUser'][0]['is_seating_selection'] && !empty($itemDetail['ItemUser'][0]['CustomPricePerTypesSeat'])) { ?>
					<i class="icon-info-sign js-bootstrap-tooltip text-16" title="<?php echo __l('Time remaining to complete booking'); ?>"></i>
				<?php } ?>
		<?php } ?>
	</div>
</div>

<?php
	if(isset($this->request->data['ItemUser']['wallet']) && $this->request->data['ItemUser']['payment_gateway_id'] == ConstPaymentGateways::SudoPay && !empty($sudopay_gateway_settings) && $sudopay_gateway_settings['is_payment_via_api'] == ConstBrandType::VisibleBranding) {
		echo $this->element('sudopay_button', array('data' => $sudopay_data, 'cache' => array('config' => 'sec')), array('plugin' => 'Sudopay')); 
	} else {	
	
	$price = $itemDetail['ItemUser'][0]['original_price'];
	$service_fee = $itemDetail['ItemUser'][0]['booker_service_amount'];
	$additional_fee = $itemDetail['ItemUser'][0]['additional_fee_amount'];
	$coupon_discount = $itemDetail['ItemUser'][0]['coupon_discount_amont'];
	$seat_selection_amount = $itemDetail['ItemUser'][0]['seat_selection_amount'];
	$total = $service_fee + $additional_fee + $price - $coupon_discount;
?>
<section class="row ver-space no-mar">
	<?php echo $this->element('items-sidebar-view', array('slug' => $itemDetail['Item']['slug'], 'order_id' => $itemDetail['ItemUser'][0]['id'], 'config' => 'sec')); ?>
	<div class="span15 ver-space">
		<div class="clearfix bot-space bot-mspace">
			<h3 class="well space text-16 no-mar"><?php echo __l('Order Summary'); ?></h3>
			<ul class="unstyled no-mar">
				<?php if($itemDetail['Item']['is_people_can_book_my_time']) { 
					$start = explode(' ', $itemDetail['ItemUser'][0]['from']);
					$end = explode(' ', $itemDetail['ItemUser'][0]['to']);
					$min_hours = $itemDetail['ItemUser'][0]['CustomPricePerNight']['min_hours'];
						$difference = $this->Html->getDateDiffWithFormat($start[0] . ' ' . $start[1], $end[0] . ' ' . $end[1]);
						if(empty($difference)) {
							$difference = 0;
						}
				?>
					 <li class="top-space clearfix"> 
						<span class="hor-mspace text-12 textb"><?php echo $this->Html->cDateTime($start[0] . ' ' . $start[1]) . ' - ' . $this->Html->cDateTime($end[0] . ' ' . $end[1]); ?> <i class="icon-info-sign js-bootstrap-tooltip text-16" title="<?php echo __l('Different between the from and to date is') . ' ' . $difference; ?>"></i></span>
					</li> 
					<span>
				<?php } ?>
				<?php 
					$default_currency_id = Configure::read('site.currency_id');
					if (!empty($_COOKIE['CakeCookie']['user_currency'])) {
						$currency_id = $_COOKIE['CakeCookie']['user_currency'];
					}
					$display_default_currency = false;
					if (!empty($_COOKIE['CakeCookie']['user_currency']) && $default_currency_id!=$currency_id) {
						$display_default_currency = true;
					}
					if (!empty($itemDetail['ItemUser'][0]['CustomPricePerTypeItemUser'])) { 
						foreach($itemDetail['ItemUser'][0]['CustomPricePerTypeItemUser'] As $prices) {
				?>
				<li class="top-space clearfix"> 
					<?php 
						$c_price = (!empty($prices['price']) && $prices['price'] > 0) ? $prices['price'] : __l('Free'); 
						if($itemDetail['Item']['is_people_can_book_my_time']) {
							$date_diff = $this->Html->getOverAllPriceDetail($itemDetail['ItemUser'][0]['from'], $itemDetail['ItemUser'][0]['to'], $prices['CustomPricePerNight']);
							$price_split_up = '';
							if(!empty($date_diff['hour'])) {
								if($date_diff['hour'] < $min_hours && empty($date_diff['day']) && empty($date_diff['week']) && empty($date_diff['month'])) {
									$price_split_up = __l('Minimum Hours') . ": " . $min_hours . ' * ' . $this->Html->siteCurrencyFormat($prices['CustomPricePerNight']['price_per_hour'], false); 
							    } else {
									$price_split_up = __l('Hours') . ": " . $date_diff['hour'] . ' * ' . $this->Html->siteCurrencyFormat($prices['CustomPricePerNight']['price_per_hour'], false); 
								}
							}
							if(!empty($date_diff['day']) && !empty($date_diff['hour'])) {
								$price_split_up .= " | " . __l('Days') . ": " . $date_diff['day'] . ' * ' . $this->Html->siteCurrencyFormat($prices['CustomPricePerNight']['price_per_day'], false); 
							} else if(!empty($date_diff['day'])){
								$price_split_up .= __l('Days') . ": " . $date_diff['day'] . ' * ' . $this->Html->siteCurrencyFormat($prices['CustomPricePerNight']['price_per_day'], false); 
							}
							if(!empty($date_diff['week']) && (!empty($date_diff['day']) || !empty($date_diff['hour']))) {
								$price_split_up .= " | " . __l('Weeks') . ": " . $date_diff['week'] . ' * '. $this->Html->siteCurrencyFormat($prices['CustomPricePerNight']['price_per_week'], false); 
							} else if(!empty($date_diff['week'])){
								$price_split_up .= __l('Weeks') . ": " . $date_diff['week'] . ' * ' . $this->Html->siteCurrencyFormat($prices['CustomPricePerNight']['price_per_week'], false); 
							}
							if(!empty($date_diff['month']) && (!empty($date_diff['week']) || !empty($date_diff['day']) || !empty($date_diff['hour']))) {
								$price_split_up .= " | " . __l('Months') . ": " . $date_diff['month'] . ' * ' . $this->Html->siteCurrencyFormat($prices['CustomPricePerNight']['price_per_month'], false); 
							} else if(!empty($date_diff['month'])){
								$price_split_up .= __l('Months') . ": " . $date_diff['month'] . ' * ' . $this->Html->siteCurrencyFormat($prices['CustomPricePerNight']['price_per_month'], false); 
							}
						
					?>					
						<span class="pull-left dl hor-mspace text-12"><?php echo $this->Html->cText($prices['CustomPricePerNight']['name']) ; ?></span> <span><i class="icon-info-sign js-bootstrap-tooltip" title="<?php echo $price_split_up; ?>"></i></span>
					<?php } else if($itemDetail['Item']['is_sell_ticket']) { ?>
						<span class="pull-left dl hor-mspace text-12"><?php echo $this->Html->cText($prices['CustomPricePerType']['name']) . ' (' . $prices['number_of_quantity'] . ' * ' . $c_price . ')' ; ?></span>
						
					<?php } ?>
					<span class="pull-right textb hor-mspace"><?php echo $this->Html->siteCurrencyFormat($prices['total_price']); ?><span class="booking-price"><?php if($display_default_currency): ?>/ <?php echo $this->Html->siteDefaultCurrencyFormat($prices['total_price']);?><?php endif; ?></span></span><br/>
					
					<?php if(isPluginEnabled('Seats') && $itemDetail['ItemUser'][0]['is_seating_selection'] && !empty($itemDetail['ItemUser'][0]['CustomPricePerTypesSeat'])){
					?>
					<span class="pull-left dl hor-mspace text-12"> 
					<?php echo __l('Seats') .': '; 
					$seat_no = '';
					$partition = '';
					foreach($itemDetail['ItemUser'][0]['CustomPricePerTypesSeat'] as $key => $customPricePerTypesSeat) {
						if($key == 0){
							$partition = $customPricePerTypesSeat['Partition']['name'];
						}						
						if($key > 0){
							$seat_no .= ', '.$customPricePerTypesSeat['name'];
						} else {
							$seat_no = $customPricePerTypesSeat['name'];
						}
					
					}?>
					<?php echo $seat_no;?> <i class="icon-info-sign js-bootstrap-tooltip" title="<?php echo $partition.' '.__l('Partition');?>"></i>
					</span>
					<?php } ?>
					
				</li>
				<?php 
						}
					}
				?>
				<?php if ($additional_fee > 0) { ?>
					<li class="top-space clearfix">
						<span class="pull-left dl hor-mspace text-12"><?php echo __l('Additional Fee') . ' ' . $itemDetail['Item']['additional_fee_name'] . ' ('. $itemDetail['Item']['additional_fee_percentage'] .'%)'; ?></span>
						<span class="pull-right textb hor-mspace"><?php echo $this->Html->siteCurrencyFormat($additional_fee);?> <span class="booking-price"><?php if($display_default_currency): ?>/ <?php echo $this->Html->siteDefaultCurrencyFormat($additional_fee);?><?php endif; ?></span></span>
					</li>
				<?php } ?>
				<?php if ($service_fee > 0) { ?>
					<li class="top-space clearfix">
						<span class="pull-left dl hor-mspace text-12"><?php echo __l('Site Fee'); ?></span>
						<span class="pull-right textb hor-mspace"><?php echo $this->Html->siteCurrencyFormat($service_fee);?> <span class="booking-price"><?php if($display_default_currency): ?>/ <?php echo $this->Html->siteDefaultCurrencyFormat($service_fee);?><?php endif; ?></span></span>
					</li>
				<?php } ?>
				<?php if ($coupon_discount > 0) { ?>
					<li class="top-space clearfix">
						<span class="pull-left dl hor-mspace text-12"><?php echo __l('Discount'); ?></span>
						<span class="pull-right textb hor-mspace"><?php echo '- ' .$this->Html->siteCurrencyFormat($coupon_discount);?> <span class="booking-price"><?php if($display_default_currency): ?>/ <?php echo $this->Html->siteDefaultCurrencyFormat($service_fee);?><?php endif; ?></span></span>
					</li>
				<?php } ?>
				<?php if(isPluginEnabled('Seats') && $seat_selection_amount > 0 && Configure::read('seat.seat_selection_fee_payer') == 'User') {?>
					<li class="top-space clearfix">
						<span class="pull-left dl hor-mspace text-12"><?php echo __l('Seat Selection Fee'); ?></span>
						<span class="pull-right textb hor-mspace"><?php echo $this->Html->siteCurrencyFormat($seat_selection_amount);?> <span class="booking-price"><?php if($display_default_currency): ?>/ <?php echo $this->Html->siteDefaultCurrencyFormat($seat_selection_amount);?><?php endif; ?></span></span>
					</li>
				<?php } ?>
				<li class="clearfix sep-top sep-bot ver-space ver-mspace">
					<span class="pull-left dl hor-mspace text-12"><?php echo __l('Total'); ?></span>
					<span class="pull-right text-16 textb linkc hor-mspace"><?php echo $this->Html->siteCurrencyFormat($total);?> <?php if($display_default_currency): ?> <span class="booking-price"><?php if($display_default_currency): ?>/ <?php echo $this->Html->siteDefaultCurrencyFormat($total);?><?php endif; ?><?php endif; ?></span></span>
				</li>
			</ul>
		</div>
		<?php if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'contact') { ?>
			<div class="alert alert-info span14"><?php echo __l('Host may confirm booking with other guests while you still negotiate. So, make your negotiation short and genuine to avoid disappointments.'); ?></div>
		<?php } ?>
		<?php  if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'accept') { ?>
			<div class="alert alert-info span14"><?php echo sprintf(__l('You can give whatever discount, but admin commission will be calculated on your %s cost!'), Configure::read('item.alt_name_for_item_singular_small')); ?></div>
		<?php } ?>
		<?php if (!isset($this->request->params['named']['type']) && $this->Auth->sessionValid()) { ?>
			<?php if (isPluginEnabled('Coupons') && $coupon_discount == 0) {
				echo $this->Form->create('Coupon', array('url' => array('controller' => 'coupons', 'action'=> 'apply_coupon',$this->request->params['pass'][0], 'order_id'=>$this->request->params['named']['order_id']), 'class' => 'form-horizontal clearfix'));
			?>
			
				<div class="clearfix bot-space bot-mspace">
					<h3 class="well space text-16 no-mar"><?php echo __l('Coupons'); ?></h3>
					<?php echo $this->Form->input('ItemUser.coupon_code',array('label' => __l('Coupon Code'), 'div' =>'input text')); ?>
					<?php echo $this->Form->submit(__l('Apply'),array('class'=>'btn btn-large btn-primary textb text-16')); ?>
				</div>				
			<?php 
				echo $this->Form->end();
			} ?>
			<div class="clearfix bot-space bot-mspace">
				<h3 class="well space text-16 no-mar"><?php echo __l('Message to Host'); ?></h3>
				<div class="payments payments-order order js-responses js-main-order-block js-submit-target-block">
					<?php if (!isset($this->request->params['named']['type'])) { ?>
						<div class="alert alert-info"><?php echo __l('Your order confirmation request will be expired automatically in ').(Configure::read('item.auto_expire')*24).' '.__l('hrs when host not yet respond.'); ?></div>
						<?php echo $this->Form->input('ItemUser.message',array('label' => __l('Message to Host'),'div' =>'input textarea host-textarea')); ?>
						<?php 
							if (!empty($itemDetail['BuyerFormField'])) {  
								echo $this->Html->getBuyerFormFields($itemDetail['BuyerFormField']);
							}
						?>
					<?php } ?>					
				</div>
			</div>
		<?php } ?>
		<?php if (!$this->Auth->sessionValid()) { ?>
			<?php
				if (!empty($this->request->params['named']['order_id'])) {
					$_SESSION['order_id'] = $this->request->params['named']['order_id'];
				}
			?>
			<div class="dc clearfix">
				<?php echo $this->Html->link(__l('Already have an account?'), array('controller' => 'users', 'action' => 'login', 'admin' => false), array('title' => __l('Already have an account?'), 'class' => 'textb')); ?>
			</div>
			<div class="dc ver-space"><?php echo __l('(OR)'); ?></div>
			<div class="dc clearfix">
				<?php echo $this->Html->link(__l('Sign up for an account'), array('controller' => 'users', 'action' => 'register', 'admin' => false), array('title' => __l('Sign up for an account'), 'class' => 'textb')); ?>
			</div>
		<?php } ?>
	</div>
</section>
<?php if ($this->Auth->sessionValid() && !isset($this->request->params['named']['type'])): 
echo $this->Form->create('Item', array('controller' => 'items', 'action' => 'order', 'id' => 'PaymentOrderForm', 'class' => 'js-submit-target'));
?>
<?php 
						echo $this->Form->input('item_id', array('type' => 'hidden'));
						if (!empty($this->request->params['named']['order_id'])) {
							echo $this->Form->input('order_id', array('type' => 'hidden', 'value' => $this->request->params['named']['order_id']));
						}
					?>
	<div class="clearfix bot-space bot-mspace">
		<?php if (!empty($total) && $total > 0) { ?>
			<h3 class="well space text-16 no-mar"><?php echo __l('Payment Type'); ?></h3>
			<div class="ver-mspace">
				<?php echo $this->element('payment-get_gateways', array('model' => 'ItemUser', 'type' => 'is_enable_for_book_a_item', 'foreign_id' => $this->request->params['named']['order_id'], 'transaction_type' => ConstPaymentType::BookingAmount, 'is_enable_wallet' => 1,'cache' => array('config' => 'sec')));?>
			</div>
		<?php } else { ?>
			<div class="submit-block form-payment-panel clearfix offset4 hor-space">
				<div class="submit"><?php echo $this->Form->submit(__l('Book It'), array('name' => 'data[ItemUser][free]', 'class' => 'js-no-pjax btn btn-large', 'div' => false)); ?></div>
			</div>
		<?php } ?>
	</div>
<?php endif; 

?>
<div class="top-mspace sep-top">
	<?php if($this->Auth->sessionValid()) { ?>
		<?php  if(isset($this->request->params['named']['type'])):?>
			<?php if(isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'contact'): ?>
				<div class="clearfix form-actions connected-paypal-block"><?php echo $this->Form->submit(__l('Contact'), array('name' => 'data[Item][contact]','class'=>'btn btn-large btn-primary  pull-right','div'=>false));?></div>
			<?php elseif(isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'accept'): ?>
				<div class="clearfix form-actions connected-paypal-block"><?php echo $this->Form->submit(__l('Confirm'), array('name' => 'data[Item][accept]','class'=>'btn btn-large btn-primary  pull-right','div'=>false));?></div>
			<?php endif; ?>
		<?php endif; ?>
	<?php } ?>
	<?php if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'cancel'): ?>
		<div class="clearfix from-actions">
			<div class="form-actions"><?php 	echo $this->Html->link(__l('Submit'), array('controller' => 'item_users', 'action' => 'update_order', $itemDetail['ItemUser'][0]['id'], 'cancel', 'admin' => false), array('title' => __l('Submit'),'class' => 'js-cancel js-no-pjax pull-right cancel btn btn-large btn-primary textb text-16')); ?></div>
		</div>
	<?php endif; ?>
</div>
<?php echo $this->Form->end();
	}
?>