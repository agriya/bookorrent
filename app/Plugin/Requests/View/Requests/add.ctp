<div class="requests form">
	<div class="clearfix">
		<h2 class="ver-space top-mspace text-32 sep-bot"> <?php echo __l('Post a Request');?> </h2>
		<div class="js-response">
		<?php 
			$form_class = '';
				if(!empty($request_filters)):
					$form_class = '';
					if(!empty($this->request->data)):
					$admin = 0;
						// @todo "What goodies I can provide (guest)"
						if(!empty($this->request->params['admin'])){
                            $admin = 1;
                        }
						echo $this->element('related-items-index', array('config' => 'sec', 'type' => 'related','is_admin'=>$admin, 'latitude' => $this->request->data['Request']['latitude'], 'longitude' => $this->request->data['Request']['longitude']));
					endif;
				endif;
		?>
		</div>
		<?php 
			echo $this->Form->create('Request', array('class' => 'form-horizontal form-request add-request check-form js-geo-submit {is_required:"true"}', 'enctype' => 'multipart/form-data'));
			echo $this->Form->input('Request.steps', array('type' => 'hidden', 'value' => $steps));
			echo $this->Form->input('Request.latitude', array('id' => 'latitude', 'type' => 'hidden'));
			echo $this->Form->input('Request.longitude', array('id' => 'longitude', 'type' => 'hidden'));
			if($this->Auth->user('role_id') == ConstUserTypes::Admin): 
		?>
			<div class='clearfix'>
				<h3 class="well space textb text-16"><?php echo __l('Users'); ?></h3>
				<?php
				echo $this->Form->autocomplete('Request.username', array('label'=> __l('Users'), 'acFieldKey' => 'Request.user_id', 'acFields' => array('User.username'), 'acSearchFieldNames' => array('User.username'), 'maxlength' => '100', 'acMultiple' => false));
				?>
			</div>
		<?php 
			endif; 
		?>
		<div class="form-block">
			<div class="<?php if ($steps == 2){ ?> hide <?php }?>">
				<div class='clearfix'>
					<h3 class="well space textb text-16"><?php echo __l('Category'); ?></h3>
				<div class="clearfix top-space top-mspace">
					<?php echo $this->Form->input('category_id', array('label' => __l('Category'), 'empty' => __l('Please Select'), 'class' => 'js-category-select')); ?>
				</div>
				<div class="clearfix top-space top-mspace js-subcategory-responses" data-model="Request">
					<?php echo $this->Form->input('sub_category_id', array('label' => __l('Sub Category'), 'options' => $sub_categories, 'value' => !empty($this->request->data['Request']['sub_category_id']) ? $this->request->data['Request']['sub_category_id'] : '', 'empty' => __l('Please Select'), 'class' => 'js-subcategory-change js-subcategory-select')); ?>
				</div>
				<div class="clearfix top-space top-mspace js-category-type-responses">
					<?php 
						if(!empty($category_types)) {
							echo $this->Form->input('category_type_id', array('label' => __l('Category Type'), 'options' => $category_types, 'value' => !empty($this->request->data['Item']['category_type_id']) ? $this->request->data['Item']['category_type_id'] : '', 'empty' => __l('Please Select'))); 
						}
					?>
				</div>
				<div class="clearfix date-time-block">
					<div class="input date-time clearfix">
						<div class="js-datetime">
						<div class="js-cake-date">
							<?php echo $this->Form->input('from', array('orderYear' => 'asc', 'maxYear' => date('Y') + 10, 'minYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'),'label' => __l('From'))); ?>
						</div>
						</div>
					</div>
					<div class="input date-time end-date-time-block clearfix">
						<div class="js-datetime">
						<div class="js-cake-date">
							<?php echo $this->Form->input('to', array('orderYear' => 'asc', 'maxYear' => date('Y') + 10, 'minYear' => date('Y'), 'div' => false, 'empty' => __l('Please Select'), 'label' => __l('To'))); ?>
						</div>
						</div>
					</div>
				</div>
				<?php 
					$currency_code = Configure::read('site.currency_id');
					Configure::write('site.currency', $GLOBALS['currencies'][$currency_code]['Currency']['symbol']);
					echo $this->Form->input('price', array('label' => __l('Price') . ' (' . configure::read('site.currency') . ')'));
				?>
				</div>
				<div class="clearfix top-space top-mspace js-formfields-responses">
					<?php if(!empty($this->request->data['Request']['sub_category_id'])) { ?>
						<div class="space <?php echo $category['Category']['slug'];?>">
							<div id="preview" class="Category form space">
								<div class="clearfix">
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
									<div class="ver-space">
										<div class="thumbnail">
											<h4 class="ver-space bot-mspace sep-bot"><?php echo $this->Html->cText($FormFieldGroup['FormFieldGroup']['name'], false); ?></h4>
											<?php 
												if (!empty($FormFieldGroup['FormFieldGroup']['info'])) { 
											?>
											<div class="alert alert-info clearfix"> <?php echo $this->Html->cText($FormFieldGroup['FormFieldGroup']['info'], false);?> </div>
											<?php 
												} 
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
													}
													if ($FormField['name'] == 'People_Can_Book_My_Time') {
														$FormFieldGroup['FormField'][$key]['is_book_unit_of_my_time'] = 1;
													}
												}
												echo $this->Cakeform->insert($FormFieldGroup, $model);
											?>
										</div>
									</div>
								<?php 
										}  
									}  
								} 
								?>
								</div>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php if($steps == 2 && !empty($request_filters)) {  ?>
			<div class="dc space textb"><?php echo __l('(OR)'); ?></div>
			<div class="alert alert-info clearfix"><?php echo sprintf(__l('If the above related %s does not match your exact request . You can click "Post" below to create a new one'), Configure::read('item.alt_name_for_item_singular_small')); ?></div>
			<?php } ?>
			<div class="form-actions">
				<?php echo $this->Form->submit(__l('Post'), array('class' => 'btn btn-large btn-primary textb text-16', 'div'=>'pull-right submit')); ?>
			</div>
			<?php echo $this->Form->end();?>
		</div>
	</div>
</div>