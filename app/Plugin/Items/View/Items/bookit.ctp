<?php  if($this->Auth->user('id') == $item['Item']['user_id']): ?>
	<?php $all_count=$item['Item']['sales_pending_count']+$item['Item']['sales_pipeline_count']; ?>
	<div class="items-middle-block items-middle-inner-block1 clearfix">
		<div class="dl well inbox-option dashboard-info span5 pull-left">
			<h5 class="textb bot-space"><?php echo __l('Bookings'); ?></h5>			
			<?php echo $this->Html->link('<span class="label smspace">'.__l('All').': '.$all_count.'</span>', array('controller' => 'item_users', 'action' => 'index', 'type'=>'myworks', 'item_id' => $item['Item']['id'],'status' => 'all', 'admin' => false), array('title' => __l('All'),'escape' => false,'class'=>'smspace no-pad'));?>
			<?php echo $this->Html->link('<span class="label label-warning smspace">'.__l('Waiting for acceptance:').' '.$this->Html->cInt($item['Item']['sales_pending_count'],false).'</span>', array('controller' => 'item_users', 'action' => 'index', 'type'=>'myworks', 'item_id' => $item['Item']['id'],'status' => 'waiting_for_acceptance', 'admin' => false), array('title' => __l('Waiting for acceptance'),'escape' => false,'class'=>'smspace no-pad'));?>
			<?php echo $this->Html->link('<span class="label label-success smspace">'.__l('Pipeline').': '.($this->Html->cInt($item['Item']['sales_pipeline_count'],false)).'</span>', array('controller' => 'item_users', 'action' => 'index', 'type'=>'myworks', 'item_id' => $item['Item']['id'],'status' => 'pipeline', 'admin' => false), array('title' => __l('Pipeline'),'escape' => false,'class'=>'smspace no-pad'));?>
		</div>
		<div class="bookit-all dl well verfied-info-block span5 pull-left">
			<div class="clearfix">
				<div class="show clearfix">
					<?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action'=>'edit', $item['Item']['id']), array('escape'=>false,'class' => 'edit js-edit no-pad pull-left span','title' => __l('Edit')));?>
				</div>
				<div class="show clearfix">
					<?php echo $this->Html->link('<i class="icon-calendar"></i>'.__l('Calendar'), array('controller' => 'item_users', 'action' => 'index', 'type'=>'myworks', 'item_id' => $item['Item']['id'], 'admin' => false), array('escape'=>false, 'title' => __l('Calendar'),'class' => 'calendar no-pad span'));?>
				</div>
			</div>
			<div class="clearfix text-10 show space">
				<i class="icon-question-sign"></i><?php echo __l('Manage bookings & pricing');?>
			</div>
			<div class="clearfix hor-space show">
				<h5><?php echo __l('Enable') . ' ' . Configure::read('item.alt_name_for_item_singular_caps'); ?></h5>
				<?php
					$url = Router::url(array(
						'controller' => 'items',
						'action' => 'view',
						$item['Item']['slug'],
						'admin' => false
					) , true);
					$this->request->data['Item']['is_active']= $item['Item']['is_active'];
					echo $this->Form->create('Item', array('class' => 'normal js-ajax-form option-form no-pad clearfix '));
					$options=array('1'=>__l('On'), '0'=>__l('Off'));
					$attributes=array('div'=>'js-radio-style',"class" => "js-activeinactive-updated  {'id': '". $item['Item']['id'] ."', 'url':'". $url ."'}", 'legend'=>false, 'value' => $item['Item']['is_active']);
					echo $this->Form->radio('is_active', $options, $attributes);
				?>
			<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<div class="dl well gird_right enable-list span5 pull-left">
			<?php
				$day1= date("D j", mktime(0, 0, 0, date("m"),date("d")-1,date("Y")));
                $day2=date("D j", mktime(0, 0, 0, date("m"),date("d")-2,date("Y")));
                $day3=date("D j", mktime(0, 0, 0, date("m"),date("d")-3,date("Y")));
                $axis1=ceil($chart_data['max_count']/3);
                $axis2=ceil($chart_data['max_count']/3)*2;
                $axis3=ceil($chart_data['max_count']/3)*3;
                $image_url='http://chart.apis.google.com/chart?chf=a,s,000000FA|bg,s,67676700&amp;chxl=0:|0|'.$day3.'|'.$day2.'|'.$day1.'|1:|0|'.$axis1.'|'.$axis2.'|'.$axis3.'&amp;chxs=0,676767,11.5,0,lt,676767&amp;chxtc=0,4&amp;chxt=x,y&amp;chs=200x125&amp;cht=lxy&amp;chco=0066E4,FF0285&amp;chds=0,3,0,'.$axis3.',0,3,0,'.$axis3.'&amp;chd=t:1,2,3|'. $chart_data['ItemView'][3]['count'].','.$chart_data['ItemView'][2]['count'].','.$chart_data['ItemView'][1]['count'].'|1,2,3|'.$chart_data['ItemUser'][3]['count'].','.$chart_data['ItemUser'][2]['count'].','.$chart_data['ItemUser'][1]['count'].'&amp;chdl=Views|Bookings&amp;chdlp=b&amp;chls=2,4,1|1&amp;chma=5,5,5,25';
                echo $this->Html->image($image_url); 
			?>		
		</div>
	</div>
	<?php  if(!empty($this->request->params['pass'][1]) &&  !empty($this->request->params['pass'][2]) && $distance_view) : ?>
	<div class="hovst-view-block pr page-information clearfix">
		<dl class="request-list1 host-view guest clearfix">
			<dt title ="<?php echo __l('Distance');?>"><?php echo __l('Distance (km)');?></dt>
			<dd class="dc"><?php echo $this->Html->cInt($this->Html->distance($this->request->params['named']['latitude'],$this->request->params['named']['longitude'],$item['Item']['latitude'],$item['Item']['longitude'],'K')); ?></dd>
		</dl>
		<div class="city-info ">
			<?php echo __l('from') . ' ' . $this->request->params['named']['cityname'];?>
		</div>
	</div>
	<?php endif; ?>
<?php else: ?>

	<div class="js-responses">
		<ul id="myTab" class="nav nav-tabs span9 span9-sm top-space no-mar">
			<?php if(!empty($item['Item']['is_have_definite_time'])) { ?>
			<li class="active"> <a data-toggle="tab" href="#scheduled" title="<?php echo __l('Scheduled'); ?>"><?php echo __l('Scheduled'); ?></a> </li>
			<?php } ?>
			<?php if(!empty($item['Item']['is_user_can_request'])) { ?>
			<li class="<?php if(empty($item['Item']['is_have_definite_time'])) { ?> active <?php } ?>"> <a data-toggle="tab" title="<?php echo __l('Request'); ?>" href="#request"><?php echo __l('Request'); ?></a> </li>
			<?php } ?>
		</ul>
		<div class="span9 span9-sm clearfix no-mar sep-right sep-bot sep-left">
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane top-space <?php if(!empty($item['Item']['is_have_definite_time'])) { ?> active <?php } ?>" id="scheduled">
					<div class="my-tab-bookit">
				<?php 
					if(!empty($item['Item']['is_people_can_book_my_time'])) {
						echo $this->Form->create('ItemUser', array('action' => 'check_availability', 'class' => "normal form-horizontal no-mar js-search js-ajax-form {container:'js-availability_response',responsecontainer:'js-responses'}")); 
					} else { 
						echo $this->Form->create('ItemUser', array('action' => 'add', 'class' => "normal form-horizontal no-mar js-search")); 
					}
					echo $this->Form->input('item_id',array('type'=>'hidden'));
					echo $this->Form->input('item_slug',array('type'=>'hidden'));
					echo $this->Form->input('price',array('type'=>'hidden'));
					if(isset($this->request->params['named']['cityname'])){
						echo $this->Form->input('original_search_address',array('type'=>'hidden','value'=>$this->request->params['named']['cityname'])); 
					}
				?>				
					<?php if(!empty($item['Item']['is_sell_ticket'])) { ?>
						<div class="nav nav-tabs no-bor ver-smspace clearfix">
							<ul id="myTab2" class="row scheduled-tab unstyled no-mar pull-right">
								<li class="pull-left active"><?php echo $this->Html->link(__l('List'), array('controller' => 'items', 'action' => 'get_itemtime', 'item_id' => $item['Item']['id']), array('data-item_id' => $item['Item']['id'], 'title' => __l('List'), 'data-target'=>'#list-view', 'class' => 'js-no-pjax js-list-tab-view', 'data-toggle'=>'tab')); ?></li>
								<li class="pull-left hor-smspace">/</li>
								<li class="pull-left"><a class="no-under js-cal-view-click" href="#cal-view" data-toggle="tab" title="<?php echo __l('Calendar'); ?>"><?php echo __l('Calendar'); ?></a></li>
							</ul>
						</div>
						<div class="tab-content clearfix" id="datepicker">
							<div id="list-view" class="tab-pane fade js-bookit-list-block active in">
							</div>
							<div id="cal-view" class="tab-pane calendar no-mar bot-space">								
								<?php echo $this->element('host-calendar', array('type' => 'guest', 'ids' => $item['Item']['id'], 'config' => 'sec')); ?>
							</div>
						</div>
					<?php } elseif(!empty($item['Item']['is_people_can_book_my_time'])) { ?>
						<div class="js-check-request">
							<div class="js-datepicker input clearfix required no-pad">
								<div class="js-cake-date">
								<?php
									echo $this->Form->input('start_date', array('class' => 'span2', 'label' => __l('From Date'), 'type' => 'date', 'minYear' => date('Y'), 'maxYear' => date('Y') + 10, 'orderYear' => 'asc')); 
								?>
								</div>
							</div>
							<div class="js-time input clearfix required no-pad">
								<div class="js-cake-date">
								<?php
									echo $this->Form->input('start_time', array('class' => 'span2', 'label' => __l('Start Time'), 'type' => 'time')); 
								?>
								</div>
							</div>
							<div class="js-datepicker input clearfix required no-pad">
								<div class="js-cake-date">	
								<?php 
									echo $this->Form->input('end_date', array('class' => 'span2', 'label' => __l('To'), 'type' => 'date','minYear' => date('Y'), 'maxYear' => date('Y') + 10, 'orderYear' => 'asc'));
								?>
								</div>
							</div>
							<div class="js-time input clearfix required no-pad">
								<div class="js-cake-date">
								<?php
									echo $this->Form->input('end_time', array('class' => 'span2', 'label' => __l('To Time'), 'type' => 'time')); 
								?>
								</div>
							</div>
							<div class="dropdown mob-clr ver-mspace">							
								<?php echo $this->Form->submit(__l('Check Availability!'),array('name' => 'data[ItemUser][check_availability]', 'class'=>'show btn btn-large btn-primary textb no-mar')); ?>							
							</div>
						</div>
					<?php } ?>
					 <?php echo $this->Form->end();?>
					 </div>
					 <div class="availability_response" id="availability_response">
						<!-- Bookit content from ajax -->
						<div class="js-loader-div row hor-space hide">
							<?php echo $this->Html->image('throbber.gif', array('alt' => __l('[Image: Loader]'), 'class' => 'js-loader', 'width' => 25, 'height' => 25)); ?>
						<span class="loading"><?php echo __l('Loading....'); ?></span></div>
						<div class="js-availability_response" id="availability_response">
							
						</div>
					</div>
				</div>
				<div class="tab-pane graydarkc ver-space top-mspace <?php if(empty($item['Item']['is_have_definite_time'])) { ?> active <?php } ?>" id="request">
					<?php 
						echo $this->Form->create('ItemUser', array('action' => 'add', 'class' => "normal form-horizontal no-mar js-search")); 
						echo $this->Form->input('item_id',array('type'=>'hidden'));
						echo $this->Form->input('item_slug',array('type'=>'hidden'));
						echo $this->Form->input('price',array('type'=>'hidden'));
						if(isset($this->request->params['named']['cityname'])){
							echo $this->Form->input('original_search_address',array('type'=>'hidden','value'=>$this->request->params['named']['cityname'])); 
						}
					?>				
					<div class="js-datetimepicker input clearfix required no-pad">
						<div class="js-cake-date">
						<?php
							echo $this->Form->input('request_from', array('class' => 'span2', 'label' => __l('From'), 'type' => 'datetime', 'minYear' => date('Y'), 'maxYear' => date('Y') + 1, 'orderYear' => 'asc')); 
						?>
						</div>
					</div>
					<div class="js-datetimepicker input clearfix required no-pad">
						<div class="js-cake-date">	
						<?php 
							echo $this->Form->input('request_to', array('class' => 'span2', 'label' => __l('To'), 'type' => 'datetime','minYear' => date('Y'), 'maxYear' => date('Y') + 1, 'orderYear' => 'asc'));
						?>
						</div>
					</div>
						<?php					
						$js_request_login = ($this->Auth->sessionValid())?'':'js-request-login'; ?>
						<div class="input textarea no-mar bookit-msg pr">
						<?php
							echo $this->Form->input('message', array('class'=>'span8 mob-no-mar '.$js_request_login, 'div' => false, 'label' => __l('Message to Host'), 'type' => 'textarea')); 
						?>
							<div class="js-request-response banner-content-trans-bg no-pad hide item-view-link">
							<p class="clearfix">
							<?php $redirect_url = 'item/'.$item['Item']['slug'];?>
								<?php echo $this->Html->link(__l('Login'), Router::url(array('controller' => 'users', 'action' => 'login'),true).'?f='.$redirect_url, array('escape'=>false, 'class' => 'btn btn-primary whitec span' ,'title' => __l('Login')));?>
								<span class="span whitec"><?php echo __l('Or');?></span>
								<?php echo $this->Html->link(__l('Register'), Router::url(array('controller' => 'users', 'action' => 'register','type'=>'social', 'admin' => false),true).'?f='.$redirect_url, array('escape'=>false, 'class' => 'btn btn-primary whitec span','title' => __l('Register')));?>
							</p>
							<span class="whitec"><?php echo __l('for post request');?></span>
							</div>
						</div>
						<div class="span bot-space pull-right mob-clr">
							<div class="submit mob-no-mar">
								<?php echo $this->Form->submit(__l('Request'), array('div' => false, 'name' => 'data[ItemUser][request]', 'class' => 'js-request-submit show btn mspace btn-large btn-primary textb', 'disabled' => !($this->Auth->sessionValid())?'disabled':'')); ?>
							</div>
						</div>
						 <?php echo $this->Form->end();?>
				</div>
			</div>
		</div>	
	</div>	
 
 <?php endif;?>