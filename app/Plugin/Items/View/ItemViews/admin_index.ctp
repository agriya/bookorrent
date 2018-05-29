<div class="itemViews index js-response">
<?php if(empty($this->request->params['named']['view_type'])) : ?>
<ul class="breadcrumb top-mspace ver-space">
              <li><?php echo $this->Html->link(__l('Dashboard'), array('controller'=>'users','action'=>'stats'), array('class' => 'js-no-pjax', 'escape' => false));?> <span class="divider">/</span></li>
              <li class="active"><?php echo sprintf(__l('%s Views'), Configure::read('item.alt_name_for_item_singular_caps')); ?></li>
            </ul> 
<?php endif; ?>					
            <div class="tabbable ver-space <?php echo (empty($this->request->params['named']['view_type'])) ? "sep-top" : "";?> top-mspace">
                <div id="list" class="tab-pane active in no-mar">
<?php if(empty($this->request->params['named']['view_type'])) : ?>				
			<div class="clearfix dc">
					<?php echo $this->Form->create('ItemView', array('type' => 'get', 'class' => 'form-search bot-mspace big-input span', 'action'=>'index')); ?>
					<?php echo $this->Form->input('q', array('placeholder' => __l('Keyword'),'class'=>'span9 ver-mspace text-16','label' => false, 'maxlength' => '255')); ?>
					<?php echo $this->Form->submit(__l('Search'), array('class'=>'btn btn-large hor-mspace btn-primary textb text-16'));?>
					<?php echo $this->Form->end(); ?>
			</div>
<?php endif; ?>					
			<?php echo $this->element('paging_counter'); ?>
 
    <?php echo $this->Form->create('ItemView' , array('class' => 'normal clearfix','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
	<div class="ver-space">
                    <div id="active-users" class="tab-pane active in no-mar">
     <table class="table no-round table-striped">
	<thead>
	<tr class=" well no-mar no-pad">
            <?php if(empty($this->request->params['named']['view_type'])) : ?>
            <th class="dc graydarkc sep-right span2"><?php echo __l('Select'); ?></th>
            <?php endif; ?>
            <th class="dc graydarkc sep-right span2"><?php echo __l('Actions');?></th>
			<?php if(empty($this->request->params['named']['view_type'])) : ?>
            <th class="dl graydarkc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort( 'ItemView.title', Configure::read('item.alt_name_for_item_singular_caps'));?></div></th>
			<?php endif; ?>
            <th class="dl graydarkc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort( 'User.username',__l('Viewed By'));?></div></th>
            <th class="dl graydarkc sep-right span4"><div class="js-pagination"><?php echo $this->Paginator->sort('Ip.ip',__l('IP'));?></div></th>
           	<th class="dc graydarkc sep-right span2"><div class="js-pagination"><?php echo $this->Paginator->sort('created',__l('Viewed On'));?></div></th>
        </tr></thead>
        <?php
               if (!empty($itemViews)):
            $i = 0;
            foreach ($itemViews as $itemView):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <?php if(empty($this->request->params['named']['view_type'])) : ?>
                    <td class="dc"><?php echo $this->Form->input('ItemView.'.$itemView['ItemView']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$itemView['ItemView']['id'], 'label' => "", 'class' => 'js-checkbox-list')); ?></td>
                    <?php endif; ?>
                    <td class="dc"><span class="dropdown"> <span title="<?php echo __l('Actions');?>" data-toggle="dropdown" class="graydarkc left-space hor-smspace icon-cog text-18 cur dropdown-toggle"> <span class="hide">Action</span> </span>
                                <ul class="dropdown-menu arrow no-mar dl">
        			
        			<li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action' => 'delete', $itemView['ItemView']['id']), array('escape' => false,'class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
			   </ul>
   	  </span>
		</td>
					<?php if(empty($this->request->params['named']['view_type'])) : ?>
	                    <td class="dl"><div class="htruncate js-bootstrap-tooltip span6" title="<?php echo $this->Html->cText($itemView['Item']['title'], false);?>" ><?php echo $this->Html->link($this->Html->cText($itemView['Item']['title'],false), array('controller'=> 'items', 'action'=>'view', $itemView['Item']['slug'], 'admin' => false), array('escape' => false, 'class' => 'js-no-pjax', 'title' => $this->Html->cText($itemView['Item']['title'],false)));?></div>
                        </td>
					<?php endif; ?>
                    <td class="dl"><?php echo !empty($itemView['User']['username']) ? $this->Html->link($this->Html->cText($itemView['User']['username']), array('controller'=> 'users', 'action'=>'view', $itemView['User']['username'], 'admin' => false), array('escape' => false, 'class' => 'js-no-pjax', 'title' => $this->Html->cText($itemView['User']['username'],false))) : __l('Guest');?></td>
                    <td class="dl">
					     <?php if(!empty($itemView['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($itemView['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $itemView['Ip']['ip'], 'admin' => false), array('class' => 'js-no-pjax', 'target' => '_blank', 'title' => 'whois '.$itemView['Ip']['host'], 'escape' => false));
							?>
							<div>
							<?php
                            if(!empty($itemView['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo $this->Html->cText(strtolower($itemView['Ip']['Country']['iso_alpha2']), false); ?>" title ="<?php echo $this->Html->cText($itemView['Ip']['Country']['name'], false); ?>">
									<?php echo $this->Html->cText($itemView['Ip']['Country']['name'], false); ?>
								</span>
                                <?php
                            endif;
							 if(!empty($itemView['Ip']['City'])):
                            ?>
                             <p class="htruncate js-bootstrap-tooltip span2" title="<?php echo $this->Html->cText($itemView['Ip']['City']['name'], false);?>"><?php echo $this->Html->cText($itemView['Ip']['City']['name'], false); ?>    </p>
                            <?php endif; ?></div>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
					</td>
					<td class="dc"><?php echo $this->Html->cDateTimeHighlight($itemView['ItemView']['created']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="7"><div class="space dc grayc"><p class="ver-mspace top-space text-16 "><?php echo sprintf(__l('No %s Views available'), Configure::read('item.alt_name_for_item_singular_caps'));?></p></div></td>
            </tr>
            <?php
        endif;
        ?>
    </table>

    <?php
    if (!empty($itemViews)) :
        ?>
		<div class="admin-select-block ver-mspace pull-left mob-clr dc">
	   <?php if(empty($this->request->params['named']['view_type'])) : ?>
	   <div class="span top-mspace">
       <span class="graydarkc">
                <?php echo __l('Select:'); ?>
				</span>
				<?php echo $this->Html->link(__l('All'), '#', array('class' => 'hor-smspace grayc js-select js-no-pjax {"checked":"js-checkbox-list"}', 'title' => __l('All'))); ?>
				<?php echo $this->Html->link(__l('None'), '#', array('class' => 'hor-smspace grayc js-select js-no-pjax {"unchecked":"js-checkbox-list"}', 'title' => __l('None'))); ?>
				
             </div><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit js-no-pjax span5', 'div'=>false,'label' => false, 'empty' => __l('-- More actions --'))); ?>
            <?php endif; ?>
         </div>
          <div class="js-pagination pagination pull-right space no-mar mob-clr dc">
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
		