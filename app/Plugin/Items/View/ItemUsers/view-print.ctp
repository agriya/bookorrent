<?php /* SVN: $Id: $ */ ?>
<style type="text/css">
<!--
.style1 {font-size: 20px}
.style2 {
	font-size: 11px;
	font-weight: bold;
}
.style3 {font-size: 11px}
.style4 {font-size: 13px;
    font-weight: bold;
}
.style6 {
	font-size: 15px;
	font-weight: bold;
}
.style7 {font-size: 17px}
.style8 {font-size: 22px}
.style9 {
	font-size: 12px
}
.style10 {font-size: 22px; font-weight: bold; }
.style11 {font-size: 15px}
.style13 {
	font-size: 18px;
}
.style14 {
	font-size: 21px;
}
.link {
    text-decoration:none;
    color:#000;
    margin:0 0 0 15px;
}
-->
</style>
<div style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px;">
<table width="675px" align="center" cellpadding="0" cellspacing="0">
  <tr><td>
  <table style="border-bottom:2px solid #000000;" width="675px" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style="padding:35px 0 0 0;"><span class="style14"><?php echo __l('Ticket'); ?></span></td>
    <td style="padding:35px 0 0 0;"><span class="style13"><?php echo '#' . $itemUser['ItemUser']['top_code']; ?></span></td>
    <td><div align="right"><img src="../../img/logo.png" title="<?php echo Configure::read('site.name'); ?>" /></div>
    <div>
      <div style="padding-bottom:5px;" align="right"><?php echo Router::url('/',true);  ?></div>
    </div>    </td>
  </tr>
  </table>
<table  style="border-bottom:1px dashed #000000; padding:10px 0 15px 0;"width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="62%"><span style="color:#F47564;font-size:17px;font-weight:bold;"><?php echo $this->Html->cText($itemUser['Item']['title'],false);?></span>
		<?php if(isPluginEnabled('Seats') && !empty($itemUser['CustomPricePerNight']['Hall']['name'])){ ?>
			<span style="color:#F47564;font-size:17px;font-weight:bold;"><?php echo '['.__l('Venue').' - '.$itemUser['CustomPricePerNight']['Hall']['name'].' '.__l('Hall').']';?>
			</span>
		<?php } ?>
	</td>
	<td width="25%"><div align="right" class="style2"><strong><?php echo __l('Phone'); ?>:</strong></div></td>
    <td width="13%"><div align="right" class="style3"><?php echo $this->Html->cText($itemUser['Item']['User']['UserProfile']['phone']) ;?></div></td>
  </tr>
  <tr>
    <td><?php $url = Router::url('/',true); ?><p style="color:#b2b2b2; padding:5px 0 5px 25px; margin:0;" class="style3" title="<?php echo !empty($itemUser['Item']['Country']['name']) ? $itemUser['Item']['Country']['name'] : ''; ?> "><?php echo $this->Html->cText($itemUser['Item']['address']);?>
	</p></td>
    <td><div align="right" class="style3"><strong><?php echo __l('Backup phone:'); ?></strong></div></td>
    <td><div align="right" class="style3"><?php echo $this->Html->cText($itemUser['Item']['User']['UserProfile']['backup_phone']);?></div></td>
  </tr>
</table>
<?php 
	$start_date = split(' ', $itemUser['ItemUser']['from']);
	$start = split('-', $start_date[0]);
	$to_date = split(' ', $itemUser['ItemUser']['to']);
	$end = split('-', $to_date[0]);
?>
<table style="border-bottom:1px dashed #000000; padding:10px 0 15px 0;" width="485px" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td style="padding:0 0 10px 0;" width="35%"><div align="center"><span class="style7"><?php echo date('D, d M Y',mktime(0,0,0,$start[1],$start[2],$start[0])); ?></span></div></td>
    <td style="padding:0 0 10px 0;"width="28%"><div align="center"><span class="style4">&nbsp;</span></div></td>
    <td style="padding:0 0 10px 0;" width="37%"><div align="center"><span class="style7"><?php echo date('D, d M Y',mktime(0,0,0,$end[1],$end[2],$end[0])); ?></span></div></td>
  </tr>
  <tr>
    <td><div align="center"><span class="style4"><?php echo date('h:i a', strtotime($itemUser['ItemUser']['from'])); ?></span></div></td>
    <td>&nbsp;</td>
    <td><div align="center"><span class="style4"><?php echo date('h:i a', strtotime($itemUser['ItemUser']['to'])); ?></span></div></td>
  </tr>
</table>
<table style="padding:20px 0 10px 0;"width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td width="19%" style="vertical-align:top;"><div align="left"> <?php
	  $itemUser['Item']['Attachment'][0] = !empty($itemUser['Item']['Attachment'][0]) ? $itemUser['Item']['Attachment'][0] : array();
	  echo $this->Html->showImage('Item', $itemUser['Item']['Attachment'][0], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($itemUser['Item']['title'], false)), 'title' => $this->Html->cText($itemUser['Item']['title'], false)));
	 ?></div></td>
    <td width="9%" style="vertical-align:middle;">
	<?php
		$current_user_details = array(
			'username' => $itemUser['Item']['User']['username'],
			'role_id' => $itemUser['Item']['User']['role_id'],
			'id' => $itemUser['Item']['User']['id'],
			'facebook_user_id' => $itemUser['Item']['User']['facebook_user_id']
		);
		$current_user_details['UserAvatar'] = array(
			'id' => $itemUser['Item']['User']['attachment_id']
		);
		
	 ?>
    	<p><strong><?php echo __l('Host'); ?></strong></p>
		<?php echo $this->Html->getUserAvatarLink($current_user_details, 'small_thumb'); ?>
        <?php echo $this->Html->cText($itemUser['Item']['User']['username'], false); ?></td>

    <td width="62%"><div align="right"><?php if(!empty($itemUser['Item']['address'])): ?>
				<?php $map_zoom_level = !empty($itemUser['Item']['map_zoom_level']) ? $itemUser['Item']['zoom_level'] : '10';?>
						<img src="<?php echo $this->Html->formGooglemap($itemUser['Item'],'284x224'); ?>" width="300" height="150"/>
				<?php endif; ?></div></td>
  </tr>
</table>
<table style="border-bottom:1px dashed #000000;" width="485px" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr><td>&nbsp;</td></tr></table>
<table style="padding-bottom:15px;" width="100%" border="0" cellspacing="0" cellpadding="0">
  <?php if(!empty($itemUser['CustomPricePerTypeItemUser'])): ?>
  <tr>
	<td><h2 style="font-weight:normal; margin-bottom:0;"><span class="style8"><?php echo __l('Ticket Type'); ?></span></h2></td>
  </tr>
  <tr>
	<td>
		<table width="485px" style="border:1px solid #000000; border-collapse: collapse;" border="0" align="center" cellpadding="5" cellspacing="5">
			<thead>
				<tr>
					<th style="border:1px solid #000000;" width="80%" align="left"><?php echo __l('Type'); ?></th>
					<th style="border:1px solid #000000;" width="20%" align="center"><?php echo __l('Quantity'); ?></th>
					<?php if(isPluginEnabled('Seats') && !empty($seats)){?>
						<th style="border:1px solid #000000;" width="20%" align="center"><?php echo __l('Partition'); ?></th>
						<th style="border:1px solid #000000;" width="20%" align="center"><?php echo __l('Seat(s)'); ?></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
		<?php foreach($itemUser['CustomPricePerTypeItemUser'] As $custom_types) { ?>
				<tr>
					<td style="border:1px solid #000000;" width="60%" align="left"><?php echo $this->Html->cText($custom_types['CustomPricePerType']['name'], false); ?></td>
					<td style="border:1px solid #000000;" width="20%" align="center"><?php echo $this->Html->cInt($custom_types['number_of_quantity'], false); ?></td>
					<?php if(isPluginEnabled('Seats') && !empty($seats)){
						$seat_no = '';
						$partition = '';
						foreach($seats as $key => $seat) {
							if($key == 0){
								$partition = $seat['Partition']['name'];
							}						
							if($key > 0){
								$seat_no .= ', '.$seat['name'];
							} else {
								$seat_no = $seat['name'];
							}
						}
					?>
					<td style="border:1px solid #000000;" width="20%" align="center">
						<?php echo $partition;?>
					</td>
					<td style="border:1px solid #000000;" width="20%" align="center">
						<?php echo $seat_no;?>
					</td>
					<?php } ?>
				</tr>
		<?php } ?>
			</tbody>
		</table>
	</td>
  </tr>
  <?php endif; ?>
</table>
<table width="485px" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr><td>&nbsp;</td></tr></table>
<?php if(!empty($itemUser['Item']['house_manual'])): ?>
<table style="padding-bottom:15px;border-top:1px dashed #000000;" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td><h2 class="style10" style="font-weight:normal; margin-bottom:0;"><?php echo __l('House Manual'); ?></h2>
    <p class="style9" style="margin:3px 0 0 30px; line-height:18px"><?php echo  $this->Html->cText($itemUser['Item']['house_manual'],false); ?></p></td>
  </tr>
</table>
  <?php endif; ?>
  <?php if(!empty($itemUser['Item']['location_manual'])): ?>
<table style="padding-bottom:15px;border-top:1px dashed #000000;" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td><h2 class="style10" style="font-weight:normal; margin-bottom:0;"><?php echo __l('Location Manual'); ?></h2>
    <p class="style9" style="margin:3px 0 0 30px; line-height:18px"><?php echo  $this->Html->cText($itemUser['Item']['location_manual'],false); ?></p></td>
  </tr>
</table>
  <?php endif; ?>
<table style="border-bottom:2px solid #000;border-top:2px solid #000;  padding-bottom:15px; position:relative;" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  </tr>
  <tr>
    <td width="100%" style="padding:0 0 0 45px;"><h3 class="style11"><?php echo configure::read('site.name'), __l(' Notes'); ?></h3></td>    
  </tr>
  <tr>
    <td width="100%" style="padding:0 0 5px 45px;" class="style9"><?php echo __l('To track your booking visit activities page '), Router::url(array(
							'controller' => 'messages',
							'action' => 'activities',
							'order_id' => $itemUser['ItemUser']['id'],
							'admin' => false
						) , true); ?></td>    
  </tr>
  <tr>
    <td width="100%" style="padding:0 0 5px 45px;"  class="style9"><?php echo sprintf(__l('After the end date, be sure to give feedback about the %s in '), Configure::read('item.alt_name_for_item_singular_small')),configure::read('site.name'); ?></td>    
  </tr>  
</table>
<table style="border-bottom:2px solid #000000; margin:0px 0 0 0; padding:15px 0 15px 0;" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="19%" style="padding:0px;margin:0px; vertical-align:top;">
    <h3 style="padding:0 0 7px 0;margin:0px;"><?php echo __l('Host copy'); ?></h3>
    <p style="margin:10px 0 0 0;padding:0px;"><?php echo __l('Ticket'); ?>
    <?php echo '#' . $itemUser['ItemUser']['top_code']; ?></p>
    </td>
    <td width="25%" style="padding:0px;margin:0px;vertical-align:top;"><div align="center"><span class="style7"><?php echo date('D, d M Y',mktime(0,0,0,$start[1],$start[2],$start[0])); ?></span></div>
    <div align="center" style="margin:10px 0 0 0;"><span class="style4"><?php echo date('h:i a', strtotime($itemUser['ItemUser']['from'])); ?></span></div>
    </td>
    <td width="14%" style="padding:0px;margin:0px;vertical-align:top;">
    <div align="center">
    <h3 style="padding:4px 0 7px;margin:0px; font-size:12px">&nbsp;</h3>
    </div>
    </td>
    <td width="26%" style="padding:0px;margin:0px; vertical-align:top;">
    <div align="center"><span class="style7"><?php echo date('D, d M Y',mktime(0,0,0,$end[1],$end[2],$end[0])); ?></span></div>
    <div align="center" style="margin:10px 0 0 0;"><span class="style4"><?php echo date('h:i a', strtotime($itemUser['ItemUser']['to'])); ?></span></div>
    </td>
    <td width="16%" style="padding:0px;margin:0px; vertical-align:top;"><div align="center"><?php
				if(Configure::read('barcode.is_barcode_enabled') == 1) {
					$barcode_width = Configure::read('barcode.width');
					$barcode_height = Configure::read('barcode.height');
					if(Configure::read('barcode.symbology') == 'qr') {
					  $qr_site_url = Router::url(array(
							'controller' => 'item_users',
							'action' => 'check_qr',
							$itemUser['ItemUser']['top_code'],
							$itemUser['ItemUser']['bottom_code'],
							'admin' => false
						) , true);
					  ?>
					   <img src="http://chart.apis.google.com/chart?cht=qr&chs=<?php echo $barcode_width; ?>x<?php echo $barcode_height; ?>&chl=<?php echo $qr_site_url; ?>" alt = "[Image: Item qr code]"/>
			<?php 
					} 
				}
			?></div>
			<div align="center"><strong> <?php echo  $this->Html->cText($itemUser['ItemUser']['bottom_code'], false); ?></strong></div>
            </td>
  </tr>

</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><p>&copy;<?php echo date('Y'); ?> <a href="<?php echo Router::url('/'); ?>" title="<?php echo Configure::read('site.name'); ?>"><?php echo configure::read('site.name'); ?></a><?php echo '. '.__l('All rights reserved.'); ?></p>
      </td>
  </tr>
</table>

</td>
</tr>
</table>
</div>