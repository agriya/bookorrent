<?php /* SVN: $Id: $ */ ?>
<?php
if(!empty($this->request->params['admin'])) {
?>
	<ul class="breadcrumb top-mspace ver-space sep-bot">
		<li>
			<?php 
				echo $this->Html->link( __l('Halls'), array('controller'=>'halls','action'=>'index', 'admin' => 'true'), array('escape' => false));
			?>
			<span class="divider">/</span>
		</li>
		<li class="active">
			<?php 
				echo __l('Edit Hall'); 
			?>
		</li>
	</ul>
<?php } else {?>
	<h2 class="ver-space top-mspace text-32 sep-bot"><?php echo __l('Edit Hall');?></h2>
<?php } ?>
<div class="halls form">
	<?php 
		echo $this->Form->create('Hall', array('class' => 'form-horizontal space'));
	?>
	<fieldset>
		<?php
			if($this->Auth->user('role_id') == ConstUserTypes::Admin){
		?>
				<div class='clearfix'>
				<?php
				echo $this->Form->autocomplete('User.username', array('label'=> __l('User'), 'acFieldKey' => 'Hall.user_id', 'acFields' => array('User.username'), 'acSearchFieldNames' => array('User.username'), 'maxlength' => '100', 'acMultiple' => false));
				?>
				</div>
		<?php 
			}
		?>		
		<?php
			echo $this->Form->input('id');
			echo $this->Form->input('name', array('label' => __l('Hall')));
			echo $this->Form->input('is_active', array('label' => __l('Enable')));
		?>
	</fieldset>
	<div class="form-actions">
		<?php 
			echo $this->Form->submit(__l('Update'), array('class' => 'btn btn-large btn-primary textb text-16'));
		?>
	</div>
	<?php 
		echo $this->Form->end();
	?>
</div>
