<?php /* SVN: $Id: $ */ ?>
<div class="itemFeedbacks index js-response">
<ul class="breadcrumb top-mspace ver-space">
              <li><?php echo $this->Html->link(__l('Dashboard'), array('controller'=>'users','action'=>'stats'), array('class' => 'js-no-pjax', 'escape' => false));?> <span class="divider">/</span></li>
              <li class="active"><?php echo __l('Feedback To') . ' ' . Configure::read('item.alt_name_for_booker_singular_caps'); ?></li>
            </ul> 
            <div class="tabbable ver-space sep-top top-mspace">
                <div id="list" class="tab-pane active in no-mar">
<div class="clearfix dc">
					<?php echo $this->Form->create('ItemUserFeedback', array('type' => 'get', 'class' => 'form-search bot-mspace big-input span', 'action'=>'index')); ?>
					<?php echo $this->Form->input('q', array('placeholder' => __l('Keyword'),'class'=>'span9 ver-mspace text-16','label' => false, 'maxlength' => '255')); ?>
					<?php echo $this->Form->submit(__l('Search'), array('class'=>'btn btn-large hor-mspace btn-primary textb text-16'));?>
					<?php echo $this->Form->end(); ?>
					
			</div>
			<?php echo $this->element('paging_counter'); ?>
   <?php echo $this->Form->create('ItemUserFeedback' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<div class="ver-space">
                    <div id="active-users" class="tab-pane active in no-mar">
     <table class="table no-round table-striped">
	<thead>
	<tr class=" well no-mar no-pad">
	    <th class="dc sep-right"><?php echo __l('Select');?></th>
        <?php if(empty($this->request->params['named']['simple_view'])) : ?>
            <th class="dc sep-right span2"><?php echo __l('Actions');?></th>
        <?php endif; ?>
        <th class="dc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort('created',__l('Created'));?></div></th>
        <th class="dl sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort( 'Item.title', Configure::read('item.alt_name_for_item_singular_caps'));?></div></th>
		<th class="dl sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort('User.username',__l('Host'));?></div></th>
        <th class="dl sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort('User.username', Configure::read('item.alt_name_for_booker_singular_caps'));?></div></th>
        <th class="dl sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort('feedback',__l('Feedback'));?></div></th>
        <th class="dl sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort('Ip.ip',__l('IP'));?></div></th>
		<th class="dc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort( 'is_satisfied',__l('Satisfied')); ?></div></th>
    </tr>
<?php
if (!empty($itemFeedbacks)):
?>
<tbody>
<?php 
$i = 0;
foreach ($itemFeedbacks as $itemFeedback):
	$class = null;
	if ($i++ % 2 == 0):
		$class = ' class="altrow"';
	endif;
	if($itemFeedback['ItemUserFeedback']['is_satisfied']):
		$status_class = 'js-checkbox-active';
	else:
		$status_class = 'js-checkbox-inactive';
	endif;
?>
	<tr<?php echo $class;?>>
        <?php if(empty($this->request->params['named']['simple_view'])) : ?>
		  <td class="dc"><?php echo $this->Form->input('ItemUserFeedback.'.$itemFeedback['ItemUserFeedback']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$itemFeedback['ItemUserFeedback']['id'], 'label' => "", 'class' => $status_class.' js-checkbox-list')); ?></td>
        <?php endif; ?>
		<td class="dc"><span class="dropdown"> <span title="<?php echo __l('Actions');?>" data-toggle="dropdown" class="graydarkc left-space hor-smspace icon-cog text-18 cur dropdown-toggle"> <span class="hide"><?php echo __l('Action'); ?></span> </span>
                                <ul class="dropdown-menu arrow no-mar dl">
        			<li><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action' => 'edit', $itemFeedback['ItemUserFeedback']['id']), array('escape' => false,'class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
        			<li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action' => 'delete', $itemFeedback['ItemUserFeedback']['id']), array('escape' => false,'class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
			   </ul>
   	  </span>
		</td>
		
		<td class="dc"><?php echo $this->Html->cDateTimeHighlight($itemFeedback['ItemUserFeedback']['created']);?></td>
		<td class="dl"><div class="htruncate js-bootstrap-tooltip span5" title="<?php echo $this->Html->cText($itemFeedback['Item']['title'], false);?>" ><?php echo $this->Html->link($this->Html->cText($itemFeedback['Item']['title'],false), array('controller'=> 'items', 'action'=>'view', $itemFeedback['Item']['slug'], 'admin' => false), array('class'=>'js-no-pjax','escape' => false));?></div></td>
		<td class="dl"><?php echo !empty($itemFeedback['Item']['User']['username'])?$this->Html->link($this->Html->cText($itemFeedback['Item']['User']['username']), array('controller'=> 'users', 'action'=>'view', $itemFeedback['Item']['User']['username'], 'admin' => false), array('class'=>'js-no-pjax', 'title' => $this->Html->cText($itemFeedback['Item']['User']['username'], false), 'escape' => false)):'';?></td>
		<td class="dl"><?php echo !empty($itemFeedback['ItemUser']['User']['username'])?$this->Html->link($this->Html->cText($itemFeedback['ItemUser']['User']['username']), array('controller'=> 'users', 'action'=>'view', $itemFeedback['ItemUser']['User']['username'], 'admin' => false), array('class'=>'js-no-pjax', 'title' => $this->Html->cText($itemFeedback['ItemUser']['User']['username'], false), 'escape' => false)):'';?></td>
		<td class="dl"><div class="htruncate span5 js-bootstrap-tooltip" title="<?php echo $this->Html->cText($itemFeedback['ItemUserFeedback']['feedback'], false); ?>"><?php echo $this->Html->cText($itemFeedback['ItemUserFeedback']['feedback']);?></div></td>
		<td class="dl">
		      <?php if(!empty($itemFeedback['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($itemFeedback['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $itemFeedback['Ip']['ip'], 'admin' => false), array('class'=>'js-no-pjax','target' => '_blank', 'title' => 'whois '.$itemFeedback['Ip']['host'], 'escape' => false));
							?>
							<div>
							<?php
                            if(!empty($itemFeedback['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo $this->Html->cText(strtolower($itemFeedback['Ip']['Country']['iso_alpha2']), false); ?>" title ="<?php echo $itemFeedback['Ip']['Country']['name']; ?>">
									<?php echo $this->Html->cText($itemFeedback['Ip']['Country']['name'], false); ?>
								</span>
                                <?php
                            endif;
							 if(!empty($itemFeedback['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $this->Html->cText($itemFeedback['Ip']['City']['name'], false); ?>    </span>
                            <?php endif; ?>
                            </div>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
		</td>
		<td class="dc"><?php echo $this->Html->cBool($itemFeedback['ItemUserFeedback']['is_satisfied']);?></td>
	</tr>
<?php
    endforeach;
?>
</tbody>
<?php 	
else:
?>
	<tr>
		<td colspan="9"><div class="space dc grayc"><p class="ver-mspace top-space text-16 "><?php echo __l('No Feedbacks available');?></p></div></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($itemFeedbacks)):
        ?>
          <div class="admin-select-block ver-mspace pull-left mob-clr dc"><div class="span top-mspace">
       <span class="graydarkc">
	   <?php if(empty($this->request->params['named']['simple_view'])) : ?>
                <?php echo __l('Select:'); ?>
				</span>
				<?php echo $this->Html->link(__l('All'), '#', array('class' => 'hor-smspace grayc js-select js-no-pjax {"checked":"js-checkbox-list"}', 'title' => __l('All'))); ?>
				<?php echo $this->Html->link(__l('None'), '#', array('class' => 'hor-smspace grayc js-select js-no-pjax {"unchecked":"js-checkbox-list"}', 'title' => __l('None'))); ?>
				
             </div><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit js-no-pjax span5', 'div'=>false,'label' => false, 'empty' => __l('-- More actions --'))); ?></span>
            <?php endif; ?>
         </div>
          <div class="js-pagination pagination pull-right no-mar mob-clr dc">
            <?php echo $this->element('paging_links'); ?>
        </div>
        </div>
        <div class="hide">
            <?php echo $this->Form->submit(__l('Submit'));  ?>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>