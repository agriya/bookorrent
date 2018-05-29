<h2><?php echo __l('Ticket'); ?></h2>
<div class="clearfix">
	<dl class="clearfix">
		<dt class="span3 dr hor-space"><?php echo __l('Name'); ?></dt>
		<dd class="span no-mar">
			<?php echo $this->Html->cText($itemUser['Item']['title'], false);?>
			<span class="show"><?php echo $this->Html->cText($itemUser['Item']['address']);?></span>
		</dd>
	</dl>
	<dl class="clearfix">
		<dt class="span3 dr hor-space"><?php echo __l('Ticket'); ?></dt>
		<dd><?php echo '#' . $itemUser['ItemUser']['top_code']; ?></dd>
	</dl>
	<dl class="clearfix">
		<dt class="span3 dr hor-space"><?php echo __l('From'); ?></dt>
		<dd><?php echo $this->Html->cDateTime($itemUser['ItemUser']['from']);?></dd>
	</dl>
	<dl class="clearfix">
		<dt class="span3 dr hor-space"><?php echo __l('To'); ?></dt>
		<dd><?php echo $this->Html->cDateTime($itemUser['ItemUser']['to']);?></dd>
	</dl>
	<?php if(!empty($itemUser['CustomPricePerTypeItemUser'])): ?>
	<dl class="clearfix">
		<dt class="span3 dr hor-space"><?php echo __l('Ticket Type'); ?></dt>
		<dd class="span no-mar">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="dl"><?php echo __l('Type'); ?></th>
						<th class="dc"><?php echo __l('Quantity'); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($itemUser['CustomPricePerTypeItemUser'] As $custom_types) { ?>
					<tr>
						<td class="dl sep-top"><?php echo $this->Html->cText($custom_types['CustomPricePerType']['name'], false); ?></td>
						<td class="dc sep-top"><?php echo $this->Html->cInt($custom_types['number_of_quantity'], false); ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</dd>
	</dl>
	<?php endif; ?>
</div>
