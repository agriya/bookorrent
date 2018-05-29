<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userAddWalletAmounts index">
<div>
<h2><?php echo __l('User Add Wallet Amounts');?></h2>
</div>
<?php echo $this->element('paging_counter');?>
<ol class="unstyled" start="<?php echo $paginator->counter(array(
  'format' => '%start%'
));?>">
<?php
if (!empty($userAddWalletAmounts)):
foreach ($userAddWalletAmounts as $userAddWalletAmount):
?>
  <li>
    <p><?php echo $this->Html->cInt($userAddWalletAmount['UserAddWalletAmount']['id']);?></p>
    <p><?php echo $this->Html->cDateTime($userAddWalletAmount['UserAddWalletAmount']['created']);?></p>
    <p><?php echo $this->Html->cDateTime($userAddWalletAmount['UserAddWalletAmount']['modified']);?></p>
    <p><?php echo $this->Html->link($this->Html->cText($userAddWalletAmount['User']['username']), array('controller'=> 'users', 'action' => 'view', $userAddWalletAmount['User']['username']), array('escape' => false));?></p>
    <p><?php echo $this->Html->cCurrency($userAddWalletAmount['UserAddWalletAmount']['amount']);?></p>
    <p><?php echo $this->Html->cText($userAddWalletAmount['UserAddWalletAmount']['paypal_pay_key']);?></p>
    <p><?php echo $this->Html->link($this->Html->cText($userAddWalletAmount['PaymentGateway']['name']), array('controller'=> 'payment_gateways', 'action' => 'view', $userAddWalletAmount['PaymentGateway']['id']), array('escape' => false));?></p>
    <p><?php echo $this->Html->cBool($userAddWalletAmount['UserAddWalletAmount']['is_success']);?></p>
    <div><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $userAddWalletAmount['UserAddWalletAmount']['id']), array('class' => 'js-edit', 'title' => '<i class="icon-edit"></i><span class="hide">'.__l('Edit').'</span>'));?><?php echo $this->Html->link('<i class="icon-remove"></i><span class="hide">'.__l('Delete').'</span>', array('action'=>'delete', $userAddWalletAmount['UserAddWalletAmount']['id']), array('class' => 'js-confirm', 'title' => __l('Delete')));?></div>
  </li>
<?php
  endforeach;
else:
?>
  <li>
    <div class="space dc grayc"><p class="ver-mspace top-space text-16 "><?php echo __l('No User Add Wallet Amounts available');?></p></div>
  </li>
<?php
endif;
?>
</ol>

<?php
if (!empty($userAddWalletAmounts)) {
  echo $this->element('paging_links');
}
?>
</div>