<?php
	foreach($FormFieldSteps as $FormFieldStep) {
		if ($this->request->data['Form']['form_field_step'] != $FormFieldStep['FormFieldStep']['order']):
			continue;
		endif;
		foreach($FormFieldStep['FormFieldGroup'] as $key => $temp_FormFieldGroup) {
			if(isset($FormFieldGroup['FormField'][0]['name'])) {
				$_data = explode('.', $FormFieldGroup['FormField'][0]['name']);
			}
		}
		if ($FormFieldStep['FormFieldGroup']) { 
		foreach($FormFieldStep['FormFieldGroup'] as $temp_FormFieldGroup) { 	
			$FormFieldGroup['FormFieldGroup'] = $temp_FormFieldGroup;
			$FormFieldGroup['FormField'] = $temp_FormFieldGroup['FormField'];
?>
	<div class="clearfix">
			<h3 class="well space textb text-16"><?php echo __l($this->Html->cText($FormFieldGroup['FormFieldGroup']['name'], false)); ?></h3>
			<?php 
				if (!empty($FormFieldGroup['FormFieldGroup']['info'])) { 
			?>
			<div class="alert alert-info clearfix"> <?php echo $this->Html->cText($FormFieldGroup['FormFieldGroup']['info'], false);?> </div>
			<?php 
				}
				$is_heading = 0;
				foreach($FormFieldGroup['FormField'] as $key => $FormField) {
					if ($FormField['type'] == 'multiselect') {
						$FormFieldGroup['FormField'][$key]['type'] = 'select';
						$FormFieldGroup['FormField'][$key]['multiple'] = 'multiple';
					}
					$FormFieldGroup['FormField'][$key]['display'] = 1;
					$_data = explode('.', $FormField['name']);
					if ($FormField['name'] == 'country_id') {
						$FormFieldGroup['FormField'][$key]['options'] = $countries;
					}
					if ($FormField['name'] == 'Sell_Ticket') {
						$FormFieldGroup['FormField'][$key]['is_sell_ticket'] = 1;
						if(empty($is_heading)) {
							$FormFieldGroup['FormField'][$key]['is_heading_show'] = 1;
							$is_heading = 1;
						} else {
							$FormFieldGroup['FormField'][$key]['is_heading_show'] = 0;
						}
					}
					if ($FormField['name'] == 'People_Can_Book_My_Time') {
						$FormFieldGroup['FormField'][$key]['is_book_unit_of_my_time'] = 1;
						if(empty($is_heading)) {
							$FormFieldGroup['FormField'][$key]['is_heading_show'] = 1;
							$is_heading = 1;
						} else {
							$FormFieldGroup['FormField'][$key]['is_heading_show'] = 0;
						}
					}
				}
				echo $this->Cakeform->insert($FormFieldGroup, $model);
			?>
	</div>
<?php 
		}  
	}  
} 
?>