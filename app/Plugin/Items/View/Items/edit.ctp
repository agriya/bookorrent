<div class="items items-add form js-responses">
	<div class="clearfix">
		<h2 class="ver-space text-32 sep-bot"> <?php echo __l('Edit your') . ' ' . Configure::read('item.alt_name_for_item_singular_small') . ' - ' . $this->Html->cText($this->request->data['Item']['title'],false) ;?> </h2>
		<div class="ver-space ver-mspace clearfix">
			<?php 
			echo $this->Form->create('Item', array('class' => 'form-horizontal form-request add-item check-form js-geo-submit {is_required:"true"} js-normal-fileupload', 'enctype' => 'multipart/form-data'));
			echo $this->Form->input('id');
			echo $this->Form->input('Item.latitude', array('id' => 'latitude', 'type' => 'hidden'));
			echo $this->Form->input('Item.longitude', array('id' => 'longitude', 'type' => 'hidden'));
			if($this->Auth->user('role_id') == ConstUserTypes::Admin): 
			?>
			<div class='clearfix'>
				<h3 class="well space textb text-16"><?php echo __l('Users'); ?></h3>
				<?php
				echo $this->Form->autocomplete('User.username', array('label'=> __l('Users'), 'acFieldKey' => 'Item.user_id', 'acFields' => array('User.username'), 'acSearchFieldNames' => array('User.username'), 'maxlength' => '100', 'acMultiple' => false));
				?>
			</div>
			<?php 
			endif; 
			?>
			<div class='clearfix'>
				<h3 class="well space textb text-16"><?php echo __l('Category'); ?></h3>
				<?php echo $this->Form->input('category_id', array('label' => __l('Category'), 'empty' => __l('Please Select'), 'class' => 'js-category-select')); ?>
				<div class="clearfix top-space top-mspace js-subcategory-responses" data-model="Item">
					<?php echo $this->Form->input('sub_category_id', array('label' => __l('Sub Category'), 'options' => $sub_categories, 'value' => !empty($this->request->data['Item']['sub_category_id']) ? $this->request->data['Item']['sub_category_id'] : '', 'empty' => __l('Please Select'), 'class' => 'js-subcategory-change js-subcategory-select')); ?>
				</div>
				<div class="clearfix top-space top-mspace js-category-type-responses">
					<?php 
						if(!empty($category_types)) {
							echo $this->Form->input('category_type_id', array('label' => __l('Category Type'), 'options' => $category_types, 'value' => !empty($this->request->data['Item']['category_type_id']) ? $this->request->data['Item']['category_type_id'] : '', 'empty' => __l('Please Select'))); 
						}
					?>
				</div>
			</div>
			<div class="clearfix top-space top-mspace js-formfields-responses">
				<?php if(!empty($this->request->data['Item']['sub_category_id'])) { ?>
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
									<h3 class="well space textb text-16"><?php echo $this->Html->cText(__l($FormFieldGroup['FormFieldGroup']['name']), false); ?></h3>
									<?php 
										if (!empty($FormFieldGroup['FormFieldGroup']['info'])) { 
									?>
									<div class="alert alert-info clearfix"> <?php echo $this->Html->cHtml($FormFieldGroup['FormFieldGroup']['info'], false);?> </div>
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
				<?php } ?>
			</div>
			<div class="form-actions">
				<div class="fileupload-buttonbar submit pull-right">
					<button class="btn btn-primary btn-large start js-upload-form-submit" type="button"><span><?php echo __l('Update'); ?></span></button>
				</div>
			</div>
			<?php echo $this->Form->end();?>
		</div>
	</div>
</div>