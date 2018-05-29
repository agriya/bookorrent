<div class="space <?php echo $category['Category']['slug'];?>">
	<ul class="breadcrumb">
		<li><?php echo $this->Html->link(__l('Categories'), array('action' => 'index'), array('title' => __l('Categories')));?><span class="divider">&raquo;</span></li>
		<li><?php echo $this->Html->cText($category['Category']['name']);?><span class="divider">&raquo;</span></li>
		<li class="active"><?php echo __l('Preview');?></li>
	</ul>
	<ul class="nav nav-tabs">
		<li><?php echo $this->Html->link('<i class="icon-th-list blackc"></i>'.__l('Form Fields'), array('controller' => 'categories', 'action' => 'form_field_edit', $category['Category']['id']),array('class' => 'blackc js-no-pjax', 'title' =>  __l('Form Fields'), 'escape' => false));?></li>
		<li class="active"><a class="blackc" href="#preview"><i class="icon-eye-open"></i><?php echo __l('Preview');?></a></li>
	</ul>
	<?php
		if(!empty($FormFieldSteps)) {
			$total_span = 23.6;
			$current_step = $this->request->data['Form']['form_field_step'];
			$span_class = 'span' . floor($total_span/$total_form_field_steps);
			$step = 0;
	?>
	<div class="top-space ver-mspace clearfix pr">
		<div class="thumbnail dc">
			<div class="bot-space row pr step-block row show-grid dc">
			<?php
				foreach($FormFieldSteps as $FormFieldStep) {
					$FormFieldStep = $FormFieldStep['FormFieldStep'];
					$step++;
					$link_class = ($current_step == $step)?'successc blackc':'grayc';
			?>
				<span class="dc <?php echo $span_class; ?> top-space top-mspace">
					<span class="badge <?php echo $this->Html->cText(($current_step == $step)?'badge-module':'', false); ?> text-20"><?php echo $step; ?></span>
					<span class="show text-16 top-space top-mspace textb <?php echo $link_class;  ?>">
						<?php echo $this->Html->link($FormFieldStep['name'], array('controller' => 'categories', 'action' => 'preview', $category['Category']['id'], $FormFieldStep['order']), array('class' => $link_class)); ?>
					</span>
				</span>
			<?php 
				} 
			?>
			</div>
		</div>
	</div>
	<?php 
		} 
	?>
	<div id="preview" class="Category form space">
		<div class="clearfix">
		<?php
			echo $this->Form->create('Category', array('url' => array('controller' => 'categories', 'action'=> 'preview', $category['Category']['id'], $this->request->data['Form']['form_field_step']+1), 'class' => 'form-horizontal clearfix','enctype' => 'multipart/form-data'));
			foreach($FormFieldSteps as $FormFieldStep) {
				if ($this->request->data['Form']['form_field_step'] != $FormFieldStep['FormFieldStep']['order']):
					continue;
				endif;
				foreach($FormFieldStep['FormFieldGroup'] as $key => $temp_FormFieldGroup) {
					if(isset($FormFieldGroup['FormField'][0]['name'])) {
						$_data = explode('.', $FormFieldGroup['FormField'][0]['name']);
					}
				}
				echo $this->Form->input('user_id', array('type' => 'hidden'));
				if ($FormFieldStep['FormFieldGroup']) { 
				foreach($FormFieldStep['FormFieldGroup'] as $temp_FormFieldGroup) { 	
					$FormFieldGroup['FormFieldGroup'] = $temp_FormFieldGroup;
					$FormFieldGroup['FormField'] = $temp_FormFieldGroup['FormField'];
		?>
			<div class="ver-space">
				<div class="thumbnail">
					<div class="well"><h4 class="textb"><?php echo $this->Html->cText($FormFieldGroup['FormFieldGroup']['name'], false); ?></h4></div>
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
						echo $this->Cakeform->insert($FormFieldGroup);
					?>
				</div>
			</div>
		<?php 
				}  
			}  
		} 
		?>
			<div class="well form-actions ver-mspace">
				<div class="row">
					<?php if ($this->request->data['Form']['form_field_step'] != 1): ?>
					<div class="pull-left hor-space "><?php echo $this->Html->link(__l('Back'), array('controller' => 'categories', 'action' => 'preview', $category['Category']['id'], $this->request->data['Form']['form_field_step']-1), array('class' => 'btn')); ?></div>
					<?php endif; ?>
					<?php if ($this->request->data['Form']['form_field_step'] != $total_form_field_steps): ?>
					<div class="pull-left hor-space <?php echo  $category['Category']['slug']; ?>"><?php echo $this->Html->link(__l('Next'), array('controller' => 'categories', 'action' => 'preview', $category['Category']['id'], $this->request->data['Form']['form_field_step']+1), array('class' => 'btn btn-module')); ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php echo $this->Form->end();?>
		</div>
	</div>
</div>