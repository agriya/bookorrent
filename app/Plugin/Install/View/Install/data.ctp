<div class="install">
    <h2><?php echo $this->Html->cText($title_for_layout, false); ?></h2>
	<div class="content-block round-4">
		<?php
			echo $this->Html->link(__l('Click here to build your database'), array(
				'controller' => 'install',
				'action' => 'data',
				'run' => 1,
			));
		?>
	</div>
</div>