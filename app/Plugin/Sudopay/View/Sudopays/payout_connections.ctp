<?php /* SVN: $Id: $ */ ?>
<div class="sudopays index">
<?php if(empty($this->request->params['named'])):?>
	<div class="ver-space clearfix sep-bot top-mspace ">
		<h2 class="text-32 span">
			<div class="span smspace"><?php echo __l('Payout Preferences'); ?></div></h2>
			<?php echo $this->element('sidebar', array('cache' => array('config' => 'sec', 'key' => $this->Auth->user('id')))); ?>
	</div>
<?php endif; ?>
<section class="container">
<div class="clearfix">
<h3><?php echo __l('Payment Options / Payout Methods');?></h3>
<p><?php echo __l('When you receive a payment, we call that payment to you a \"payout\". Our secure payment system supports below payout methods, which can be setup here.');?></p>
<p><?php echo __l('Note that buyers will be provided with the payment options, based on below setup only.');?></p>
</div>
<div class="row bot-space thumbnail no-mar">

<?php
if (!empty($supported_gateways)):
foreach ($supported_gateways as $gateways):
$gateway_details = unserialize($gateways['SudopayPaymentGateway']['sudopay_gateway_details']);
?>
 <div class="pull-left ver-space ver-mspace clearfix">
    <div class="span5  pull-left top-space">
		<?php echo $this->Html->image($gateway_details['thumb_url']); ?>
	</div>
	<div class="span13 thumbnail pull-left space">
	<span class="textb pull-left">
	<?php echo $this->Html->cText($gateways['SudopayPaymentGateway']['sudopay_gateway_name']);?></span>
	<?php if(!empty($gateway_details['connect_instruction'])) {?>
	<span class="grayc pull-left">
	<?php echo $this->Html->cText($gateway_details['connect_instruction']);?></span>
	<?php } ?>
	</div>
    <div class="span5">
		<?php 
			$from = empty($this->request->params['named'])?'payout_connection':'item_add';
			if(in_array($gateways['SudopayPaymentGateway']['sudopay_gateway_id'], $connected_gateways)) { ?>
				<?php echo $this->Html->link('<i class="icon-ok"></i>'.__l('Connected'), array('controller' => 'sudopays', 'action' => 'delete_account', $gateways['SudopayPaymentGateway']['sudopay_gateway_id'], $user['User']['id'], $from), array('class' => 'btn  span3 ver-mspace js-sudopay-disconnect js-bootstrap-tooltip','escape'=>false, 'title'=> __l('Disconnect')));
			} else {
				$class = '';
				if($this->Auth->user('role_id') != ConstUserTypes::Admin){ $class=' span5'; }
				echo $this->Html->link(sprintf(__l('Connect my %s account'),$gateways['SudopayPaymentGateway']['sudopay_gateway_name']), array('controller' => 'sudopays', 'action' => 'add_account', $gateways['SudopayPaymentGateway']['sudopay_gateway_id'], $user['User']['id'], $from), array('class' => 'btn btn-primary ver-mspace text-16'));
			}
		?>
	</div>
	</div>
<?php
  endforeach; ?>
<?php if(!empty($this->request->params['named'])):?>
  <div class="pull-right span3">
		<?php if(!empty($request))
		{
			echo $this->Html->link(__l('Skip') . ' >>', Router::url(array('controller' => 'items', 'action' => 'add', 'request_id' => $request),true).'?r='.$this->request->params['named']['redirect_url'], array('class' => 'blackc','title'=>__l('Skip'))); 
		} else {
			echo $this->Html->link(__l('Skip') . ' >>', Router::url(array('controller' => 'items', 'action' => 'add'),true).'?r='.$this->request->params['named']['redirect_url'], array('class' => 'blackc','title'=>__l('Skip'))); 
		}?>
		<?php if(empty($connected_gateways)): ?>
		  <i class="icon-info-sign js-bootstrap-tooltip" title="<?php echo sprintf(__l('If you skip, %s will be saved in disable mode. You should update payout settings in your accounts page to enable it.'),Configure::read('item.alt_name_for_item_singular_caps'));?>"></i>
		<?php endif; ?>
	  </div>
<?php endif; ?>
<?php
else:
?>

<div>
    <span colspan="6" class="errorc space"><i class="icon-warning-sign errorc"></i> <?php echo __l('No Gateways available');?></span>
  </div>
<?php
endif;
?>
  </div>

</section>
</div>
