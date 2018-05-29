<?php 
	if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'search') {  
		echo $this->Form->input('Item.category_id', array('type'=>'select', 'options' => $categories, 'multiple'=>'checkbox', 'class'=>'show top-mspace checkbox clearfix', 'label' =>false));
	} else {
		if($this->request->params['named']['model'] == 'Item') {
			echo $this->Form->input('Item.sub_category_id', array('label' => __l('Sub Category'), 'options' => $categories, 'empty' => __l('Please Select'), 'class' => 'js-subcategory-change js-subcategory-select', 'div' => 'input select required')); 
		} elseif($this->request->params['named']['model'] == 'Request') {
			echo $this->Form->input('Request.sub_category_id', array('label' => __l('Sub Category'), 'options' => $categories, 'empty' => __l('Please Select'), 'class' => 'js-subcategory-change js-subcategory-select')); 
		}
	}
?>
