<ul class="breadcrumb top-mspace ver-space">
  <li><?php echo $this->Html->link( __l('Countries'), array('controller'=>'countries','action'=>'index', 'admin' => 'true'), array('escape' => false));?><span class="divider">/</span></li>
  <li class="active"><?php echo __l('Add Country'); ?></li>
</ul>
<div class="countries form sep-top">
            <?php echo $this->Form->create('Country', array('class' => 'form-horizontal space','action'=>'add'));?>
		<?php
        		echo $this->Form->input('name', array('label' => __l('Name')));
        		echo $this->Form->input('fips_code', array('label' => __l('Fips Code')));
        		echo $this->Form->input('iso_alpha2', array('label' => __l('ISO Alpha2')));
        		echo $this->Form->input('iso_alpha3', array('label' => __l('ISO Alpha3')));
        		echo $this->Form->input('iso_numeric', array('label' => __l('ISO Numeric')));
        		echo $this->Form->input('capital', array('label' => __l('Capital')));
        		echo $this->Form->input('currency', array('label' => __l('Currency')));
        		echo $this->Form->input('currencyName', array('label' => __l('currency Name')));
        		echo $this->Form->input('population', array('label' => __l('Population'), 'info' => __l('Ex: 2001600')));
        		echo $this->Form->input('areainsqkm', array('label' => __l('Area in sqkm')));
        		echo $this->Form->input('continent', array('label' => __l('Continent')));
        		echo $this->Form->input('tld', array('label' => __l('Tld')));
				echo $this->Form->input('Phone', array('label' => __l('Phone')));
				echo $this->Form->input('postalCodeFormat', array('label' => __l('PostalCode Format')));
				echo $this->Form->input('postalCodeRegex', array('label' => __l('PostalCode Regex')));
				echo $this->Form->input('languages', array('label' => __l('Languages')));
				echo $this->Form->input('geonameId', array('label' => __l('GeonameId')));
				echo $this->Form->input('neighbours', array('label' => __l('Neighbours')));
				echo $this->Form->input('equivalentFipsCode', array('label' => __l('Equivalent FipsCode')));
        	?>
<div class="form-actions">
	<?php echo $this->Form->submit(__l('Add'), array('class' => 'btn btn-large btn-primary textb text-16')); ?>
</div>
<?php echo $this->Form->end(); ?>
</div>
