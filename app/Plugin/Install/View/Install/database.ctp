<div class="install form">
    <h2><?php echo $this->Html->cText($title_for_layout, false); ?></h2>
	<iframe frameborder="0" width="630px" height="80px" src="http://installer.dev.agriya.com/info5.html"></iframe>
			<?php
			Configure::write('debug', 0);
			echo $this->Form->create('Install', array('url' => array('controller' => 'install', 'action' => 'database'), 'class' => 'normal')); ?>
	<div class="content-block round-4">
		<div class="content-block round-4 ">
		  <p class="info">
			<?php  echo __l('Available plugins are ').implode(', ', $plugins);?>
		  </p>
		</div>
	  <?php 
			echo $this->Form->input('Install.datasource', array('type' => 'select', 'options' => array('Database/Mysql' => 'MySQL', 'Database/Postgres' =>'PostgreSQL')));
			echo $this->Form->input('Install.host', array('label' => __l('Host'), 'default' => 'localhost'));
			echo $this->Form->input('Install.login', array('label' => __l('User / Login'), 'default' => 'root'));
			echo $this->Form->input('Install.password', array('label' => __l('Password')));
			echo $this->Form->input('Install.database', array('label' => __l('Name'), 'default' => 'bookorrent'));
			echo $this->Form->input('Install.prefix', array('label' => __l('Prefix')));
			echo $this->Form->input('Install.port', array('label' => __l('Port (leave blank if unknown)'))); ?>
	</div>
	<div class="clearfix">
		<div class="grid_right">
				<?php echo $this->Form->submit(__l('Submit')); ?>
		</div>
	</div>

			<?php echo $this->Form->end(); ?>
</div>