<?php 
	if(!empty($category_types)) {
		if($this->request->params['named']['model'] == 'Item') {
			echo $this->Form->input('Item.category_type_id', array('label' => __l('Category Type'), 'options' => $category_types, 'empty' => __l('Please Select'))); 
		} elseif($this->request->params['named']['model'] == 'Request') {
			echo $this->Form->input('Request.category_type_id', array('label' => __l('Category Type'), 'options' => $category_types, 'empty' => __l('Please Select'))); 
		}
	}
?>
