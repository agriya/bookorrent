<?php /* SVN: $Id: $ */ ?>
<?php
if(!empty($this->request->params['admin'])) {
?>
	<ul class="breadcrumb top-mspace ver-space sep-bot">
		<li>
			<?php 
				echo $this->Html->link( __l('Partitions'), array('controller'=>'partitions','action'=>'index', 'admin' => 'true'), array('escape' => false));
			?>
			<span class="divider">/</span>
		</li>
		<li class="active">
			<?php 
				echo __l('Add Partition'); 
			?>
		</li>
	</ul>
<?php } else {?>
	<h2 class="ver-space top-mspace text-32 sep-bot"><?php echo __l("Add Partition"); ?></h2>
<?php } ?>
<div class="form">
<?php 
	echo $this->Form->create('Partition', array('class' => 'form-horizontal space', 'id' => 'js-show-add-form-save'));
	echo $this->Form->input('result', array('type' => 'textarea', 'id' => 'js-result', 'div' => array('class' => 'hide')));
?>
<!--Section Affix--->
<section class="affix-top" data-spy="affix" data-offset-top="100">
<div class="container">
  <div class="row">
	<div class="span8">
		<div class="text-16 mspace"><?php echo __l("Area Dimension"); ?></div>
		<div class="input text clearfix">
			<div class="span4 no-mar add-form">
			  <?php echo $this->Form->input('no_of_rows', array('label' => false, 'class' => 'input-formc span3', "placeholder"=>__l("Rows"), 'id' => 'seatRows', 'div' => array('class' => 'input text hor-mspace'))); ?>
			</div>
			<div class="span4 no-mar add-form">
			  <?php echo $this->Form->input('no_of_columns', array('label' => false, 'class' => 'input-formc span3 no-mar', "placeholder"=>__l("Columns"), 'id' => 'seatCols', 'div' => array('class' => 'input text hor-mspace'))); ?>
			</div>
		</div>
		<?php 
			echo $this->Form->input('seating_name_type', array('label' => false, 'class' => 'select-category', 'id' => 'namingType', 'placeholder' => __l('Row Name'), 'empty' => __l('Please Select'), 'options' => $seating_name_types, 'div' => array('class' => 'input select left-mspace'))); 
			echo $this->Form->input('seating_direction', array('label' => false, 'class' => 'select-category', 'id' => 'direction', 'placeholder' => __l('Direction'), 'empty' => __l('Please Select'), 'options' => $seating_directions, 'div' => array('class' => 'input select left-mspace ver-space'))); 
		?>		
		<div class="submit pull-right">
			<?php 
				echo $this->Form->submit(__l('Generate'), array('class' => 'hor-mspace btn btn-primary textb text-16', 'type' => "button", 'id' => 'generateGrid'));
			?>
	  </div>
	</div>
	<div class="span8 hide" id="partitionForm">
	  <div class="text-16 mspace"><?php echo __l("Partitions"); ?></div>
	  <div class="ver-mspace">
		<?php echo $this->Form->input('name', array('label' => false, 'class' => 'input-formc partitions-form', "placeholder"=>__l("Partition Name"), 'div' => array('class' => 'input text mspace'))); ?>					
	  </div>
	  <div class="ver-mspace">
		<?php echo $this->Form->input('hall_id', array('label' => false, 'class' => 'select-category', 'empty' => __l('Please Select Hall'), 'div' => array('class' => 'input select left-mspace'))); ?>
	  </div>
	  <div class="ver-mspace">
		<?php echo $this->Form->input('stage_position', array('label' => false, 'class' => 'select-category', 'id' => 'stage_position', 'empty' => __l('Please Select Stage'), 'options' => $stage_positions, 'div' => array('class' => 'input select left-mspace'))); ?>
	  </div>
	  <div class="submit pull-right">
		<?php 
			echo $this->Form->submit(__l('Save'), array('class' => 'hor-mspace btn btn-primary textb text-16', 'id' => 'js-form-save-button'));
		?>
	  </div>
	</div>
	<div class="span8 hide seatOption">
	  <div class="text-16 mspace"><?php echo __l("Seat options"); ?></div>
	  <div class="left-mspace">
		<?php 
			echo $this->Form->input('seat_status_id', array('label' => false, 'class' => 'select-category', 'id' => 'seat-marker', 'empty' => __l('Mark seat as'), 'options' => $seat_status, 'div' => array('class' => 'input select'))); 
		?>
		<div class="submit pull-right">
			<?php 
				echo $this->Form->submit(__l('Ok'), array('class' => 'hor-mspace btn btn-primary textb text-16', 'type'=>'button', 'id'=>'mark_seats'));
			?>			
		</div>
	  </div>
	</div>
  </div>
</div>
</section>
<?php 
echo $this->Form->end();
echo $this->Form->create('Partition', array('class' => 'js-partition-temp-add-form form-horizontal space'));		  
?>
<div class="js-seat-generate-responses">
	<?php 
	if(!empty($this->request->data['Partition']['no_of_rows']) && !empty($this->request->data['Partition']['no_of_columns']) && !empty($this->request->data['Partition']['seating_direction']) && !empty($this->request->data['Partition']['seating_name_type'])){
		echo $this->requestAction(array('controller' => 'seats', 'action' => 'generate', 'rows' => $this->request->data['Partition']['no_of_rows'], 'cols' => $this->request->data['Partition']['no_of_columns'], 'direction' => $this->request->data['Partition']['seating_direction'], 'naming' => $this->request->data['Partition']['seating_name_type']), array('return'));
	}
	?>
</div>
<?php  echo $this->Form->end(); ?>
</div>