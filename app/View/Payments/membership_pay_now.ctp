<?php /* SVN: $Id: pay_now.ctp 1960 2010-05-21 14:46:46Z jayashree_028ac09 $ */ ?>
<div class="payments membership">
	<h2><?php echo __l('Pay Membership Fee');?></h2>
	 <?php echo $this->Form->create('Payment', array('url' => array('controller' => 'payments', 'action' => 'membership_pay_now', $user['User']['id'], $this->request->params['pass'][1]), 'class' => 'normal clearfix form-payment-panel form-horizontal'));
	 echo $this->Form->input('User.id',array('type'=>'hidden'));
	 ?>
	<dl class="payment-list round-5 clearfix"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Membership Fee');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->siteCurrencyFormat($total_amount);?></dd>
	</dl>
	<fieldset class="form-block">
		<legend><?php echo __l('Select Payment Type');?></legend>
		<?php echo $this->element('payment-get_gateways', array('model' => 'User', 'type' => 'is_enable_for_signup_fee','foreign_id' => $this->request->data['User']['id'], 'transaction_type' => ConstPaymentType::SignupFee, 'is_enable_wallet'=>0, 'cache' => array('config' => 'sec')));?>
	</fieldset>
	<?php echo $this->Form->end();?>
</div>