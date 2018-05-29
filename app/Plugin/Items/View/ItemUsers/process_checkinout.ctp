<!-- From/To date entry form -->
<div class="js-responses">
	<?php echo $this->Form->create('ItemUser', array('action' => 'process_fromto', 'class' => 'form-horizontal js-ajax-form-fromto'));//js-ajax-form?>
		<?php 
			if(!empty($this->request->params['named']['order_id'])):
				$order_id = $this->request->params['named']['order_id'];
			elseif(!empty($this->request->data['ItemUser']['order_id'])):
				$order_id = $this->request->data['ItemUser']['order_id'];
			endif;
			if(!empty($this->request->params['named']['p_action'])):
				$s_label = (($this->request->params['named']['p_action'] == 'check_out') ? __l('To') : __l('From'));
				$label = (($this->request->params['named']['p_action'] == 'check_out') ? __l('To Date') : __l('From Date'));
				$p_action = $this->request->params['named']['p_action'];
			elseif(!empty($this->request->data['ItemUser']['p_action'])):
				$s_label = (($this->request->data['ItemUser']['p_action']== 'check_out') ? __l('To') : __l('Check in'));
				$label = (($this->request->data['ItemUser']['p_action']== 'check_out') ? __l('Check out Date') : __l('Check in Date'));
				$p_action = $this->request->data['ItemUser']['p_action'];
			else:
				$label = __l('Select Date');
				$s_label = __l('Submit');
			endif;
		?>
		<div class="alert alert-info clearfix">
			<?php
				if ($this->request->params['named']['p_action'] == 'check_in'):
					echo sprintf(__l('This check in date is for your own tracking purpose. %s will always consider the check in time mentioned while booking for any transaction--including payment release.'), Configure::read('site.name'));
				else:
					echo sprintf(__l('This check out date is for your own tracking purpose. %s will always consider the check out time mentioned while booking for any transaction--including payment release.'), Configure::read('site.name'));
				endif;
			?>
		</div>
		<?php 
		echo $this->Form->input('via', array('type' => 'hidden'));
		echo $this->Form->input('private_note', array('type' => 'textarea', 'label' => __l('Private Note'))); ?>
		<?php echo $this->Form->input('p_action', array('type' => 'hidden', 'value' => $p_action)); ?>
		<?php echo $this->Form->input('ItemUser.order_id', array('value' => $order_id, 'type' => 'hidden')); ?>
		
		<div class="input select">
			<div class="js-datetimepicker">
				<div class="js-cake-date">
					<?php echo $this->Form->input('ItemUser.fromto', array('type' => 'datetime', 'orderYear' => 'asc', 'label' => $label,  'div' => false, 'empty' => __l('Please Select'),'minYear'=>date('Y'), 'maxYear'=>date('Y')+1)); ?>
				</div>
			</div>
		</div>		
		<div class="form-actions">
	<?php echo $this->Form->submit($s_label, array('class' => 'btn btn-large btn-primary textb text-16')); ?>
    </div>
	<?php echo $this->Form->end(); ?>
</div>