<div id="map-2" class="ui-corner-right">
	<?php if(!empty($item['Item']['address'])): ?>
		<?php $map_zoom_level = !empty($item['Item']['map_zoom_level']) ? $item['Item']['zoom_level'] : '10';?>
		<a href="//maps.google.com/maps?q=<?php echo $item['Item']['latitude']; ?>,<?php echo $item['Item']['longitude']; ?>&amp;z=<?php echo $map_zoom_level; ?>" class="show space" target="_blank">
		<img src="<?php echo $this->Html->formGooglemap($item['Item'],'648x402'); ?>" width="950" height="402" />
		</a>
	<?php endif; ?>
</div>