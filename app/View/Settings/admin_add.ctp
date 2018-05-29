<?php
	echo $this->Form->create('Setting', array('class' => 'normal'));
	echo $this->Form->input('name', array('label' => __l('Name')));
	echo $this->Form->input('value', array('label' => __l('Value')));
	echo $this->Form->input('description', array('label' => __l('Description')));
	echo $this->Form->input('type', array('type' => 'select', 'options' => array('text' => 'text', 'textarea' => 'textarea', 'checkbox' => 'checkbox', 'radio' => 'radio', 'password' => 'password')));
	echo $this->Form->input('label', array('label' => __l('Label')));
	echo $this->Form->end(__l('Add'));
	echo $this->Html->link(__l('Cancel'), array('controller' => 'settings', 'action' => 'index'),array('title' => __l('Cancel')));
?>