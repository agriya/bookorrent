<?php /* SVN: $Id: pay_now.ctp 1960 2010-05-21 14:46:46Z jayashree_028ac09 $ */ ?>
<?php 
$default_currency_id = Configure::read('site.currency_id');
if (!empty($_COOKIE['CakeCookie']['user_currency'])) {
	$currency_id = $_COOKIE['CakeCookie']['user_currency'];
}
$display_default_currency = false;
if (!empty($_COOKIE['CakeCookie']['user_currency']) && $default_currency_id!=$currency_id) {
	$display_default_currency=true;
}
$total = 0; 
	  $total  += $total_amount;
?>
<div class="payments order js-responses js-main-order-block">
	<div class="main-section">
		<h2 class="ver-space sep-bot top-mspace text-32"> <?php echo sprintf(__l('Pay %s Fee'), Configure::read('item.alt_name_for_item_singular_caps'));?></h2>
		<section class="row ver-space no-mar">
		<?php echo $this->element('items-sidebar-view', array('slug' => $Item['Item']['slug'], 'config' => 'sec')); ?>
		 <div class="span15 ver-space">
			<div class="clearfix bot-space bot-mspace">
			  <h3 class="well space text-16 no-mar"><?php  echo Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Fee Summary'); ?></h3>
			  <ul class="unstyled no-mar">
				<li class="top-space clearfix"> 
				  <span class="pull-left dl hor-mspace text-12"><?php echo Configure::read('item.alt_name_for_item_singular_caps') . ' ' . __l('Fee'); ?></span> 
				  <span class="pull-right textb hor-mspace">
					<?php echo $this->Html->siteCurrencyFormat($this->Html->cCurrency($total_amount,false)); ?>
				  </span>
				</li>
				<li class="clearfix sep-top sep-bot ver-space ver-mspace">
				<span class="pull-left dl hor-mspace text-12"><?php echo __l('Total'); ?></span>
				<span class="pull-right text-16 textb linkc hor-mspace"><?php echo $this->Html->siteCurrencyFormat($total);?> <?php if($display_default_currency): ?> <span class="booking-price"><?php if($display_default_currency): ?>/ <?php echo $this->Html->siteDefaultCurrencyFormat($total);?><?php endif; ?><?php endif; ?></span></span>
				</li>
			  </ul>
			</div>
		</div>
		</section>
		<div class="clearfix js-submit-target-block">
			<?php
			if(isset($this->request->data['Item']['wallet']) && $this->request->data['Item']['payment_gateway_id'] == ConstPaymentGateways::SudoPay && !empty($sudopay_gateway_settings) && $sudopay_gateway_settings['is_payment_via_api'] == ConstBrandType::VisibleBranding) {
				echo $this->element('sudopay_button', array('data' => $sudopay_data, 'cache' => array('config' => 'sec')), array('plugin' => 'Sudopay'));
			} else {
				echo $this->Form->create('Item', array('action' =>'item_pay_now', 'class' => 'js-submit-target clearfix'));
				echo $this->Form->input('Item.id'); 
			?>
			<div class="clearfix top-space bot-space bot-mspace">
				<h3 class="well space text-16 no-mar"><?php echo __l('Payment Type'); ?></h3>
				<div class="ver-mspace">
					<?php echo $this->element('payment-get_gateways', array('model' => 'Item', 'type' => 'is_enable_for_item_listing_fee', 'foreign_id' => $this->request->data['Item']['id'], 'transaction_type' => ConstPaymentType::ItemListingFee, 'is_enable_wallet' => 1,'cache' => array('config' => 'sec')));?>
				</div>
			</div>
			<?php 
				echo $this->Form->end();
			} 
			?>
		</div>
	</div>
</div>