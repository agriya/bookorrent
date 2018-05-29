<?php /* SVN: $Id: $ */ ?>
<div class="requests form">
<?php echo $this->Form->create('Request', array('class' => 'form-horizontal form-request add-item check-form'));?>
	<?php if (empty($this->request->params['prefix'])): ?>
	 	<h2  class="ver-space top-mspace text-32 sep-bot"><?php echo __l('Edit Request');?></h2>
	<?php endif; ?>
<fieldset>
	<h3 class="well space textb text-16 bot-mspace"><?php echo __l('Address'); ?></h3>
         <div class="mapblock-info clearfix pr">
						 <div class="address-left-block span15">
							<div class="clearfix address-input-block">
            	<?php
    				echo $this->Form->input('address', array('label' => __l('Address'), 'id' => 'RequestAddressSearch'));
					echo $this->Form->input('zoom_level', array('type' => 'hidden', 'id' => "zoomlevel"));
    			?>
    			</div>
          <div id="mapblock" class="pa">
        		<div id="mapframe">
        			<div id="mapwindow"></div>
        		</div>
        	</div>
    	</div>
    	</div>
</fieldset>
<fieldset>
		<h3 class="well space textb text-16 bot-mspace"><?php echo __l('General'); ?></h3>
    	<div class="clearfix date-time-block">
    		<div class="input date-time clearfix">
    			<div class="js-datetime">
				<div class="js-cake-date">
    				<?php echo $this->Form->input('from', array('orderYear' => 'asc', 'maxYear' => date('Y') + 10, 'minYear' => date('Y')-1, 'div' => false, 'empty' => __l('Please Select'), 'label' => __l('From'))); ?>
    			</div>
    			</div>
    		</div>
    		<div class="input date-time end-date-time-block clearfix">
    			<div class="js-datetime">
				<div class="js-cake-date">
    				<?php echo $this->Form->input('to', array('orderYear' => 'asc', 'maxYear' => date('Y') + 10, 'minYear' => date('Y')-1, 'div' => false, 'empty' => __l('Please Select'), 'label' => __l('To'))); ?>
    			</div>
    			</div>
    		</div>
    	</div>
		<?php 
			$currency_code = Configure::read('site.currency_id');
			Configure::write('site.currency', $GLOBALS['currencies'][$currency_code]['Currency']['symbol']);
			echo $this->Form->input('id',array('type'=>'hidden'));
			echo $this->Form->input('price',array('label'=>__l('Price').'('.configure::read('site.currency').')'));
		?>
	<?php
		echo $this->Form->input('title',array('label' => __l('Title')));
		echo $this->Form->input('description',array('label' => __l('Description')));
		?>
	</fieldset>
	<div class="form-actions">
	<?php echo $this->Form->submit(__l('Update'), array('class' => 'btn btn-large btn-primary textb text-16','div'=>'pull-right submit'));?>
	</div>
	<?php echo $this->Form->end();?>
</div>
