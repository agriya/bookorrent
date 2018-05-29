<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="itemFlags index js-response">
<?php if(empty($this->request->params['named']['simple_view'])) : ?>
<ul class="breadcrumb top-mspace ver-space">
              <li><?php echo $this->Html->link(__l('Dashboard'), array('controller'=>'users','action'=>'stats'), array('class' => 'js-no-pjax', 'escape' => false));?> <span class="divider">/</span></li>
              <li class="active"><?php echo sprintf(__l('%s Flags'), Configure::read('item.alt_name_for_item_singular_caps')); ?></li>
            </ul> 
<?php endif; ?>					
            <div class="tabbable ver-space <?php echo (empty($this->request->params['named']['simple_view'])) ? "sep-top" : "";?> top-mspace">
                <div id="list" class="tab-pane active in no-mar">
<?php if(empty($this->request->params['named']['simple_view'])) : ?>				
			<div class="clearfix dc">
					<?php echo $this->Form->create('ItemFlag', array('type' => 'get', 'class' => 'form-search bot-mspace big-input span', 'action'=>'index')); ?>
					<?php echo $this->Form->input('q', array('placeholder' => __l('Keyword'),'class'=>'span9 ver-mspace text-16','label' => false, 'maxlength' => '255')); ?>
					<?php echo $this->Form->submit(__l('Search'), array('class'=>'btn btn-large hor-mspace btn-primary textb text-16'));?>
					<?php echo $this->Form->end(); ?>
					
			</div>
<?php endif; ?>					
			<?php echo $this->element('paging_counter'); ?>
 
    <?php echo $this->Form->create('ItemFlag' , array('class' => 'normal clearfix','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
	<div class="ver-space">
                    <div id="active-users" class="tab-pane active in no-mar">
     <table class="table no-round table-striped">
	<thead>
	<tr class=" well no-mar no-pad">
       
            <?php if(empty($this->request->params['named']['simple_view'])) : ?>
                <th class="dc graydarkc sep-right span2"><?php echo __l('Select'); ?></th>
            <?php endif; ?>
            <th class="dc graydarkc sep-right span2"><?php echo __l('Actions');?></th>
            <th class="dl graydarkc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort('User.username',__l('Username'));?></div></th>
            <th class="dl graydarkc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort( 'Item.title', Configure::read('item.alt_name_for_item_singular_caps'));?></div></th>
            <th class="dl graydarkc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort( 'ItemFlagCategory.name',__l('Category'));?></div></th>
            <th class="dl graydarkc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort('message',__l('Message'));?></div></th>
            <th class="dl graydarkc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort('Ip.ip',__l('IP'));?></div></th>
            <th class="dc graydarkc sep-right"><div class="js-pagination"><?php echo $this->Paginator->sort( 'created',__l('Posted on'));?></div></th>
        </tr></thead>
        <?php
         if (!empty($itemFlags)):
            $i = 0;
            foreach ($itemFlags as $itemFlag):
                    $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <?php if(empty($this->request->params['named']['simple_view'])) : ?>
                        <td class="dc"><?php echo $this->Form->input('ItemFlag.'.$itemFlag['ItemFlag']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$itemFlag['ItemFlag']['id'], 'label' => '', 'class' => 'js-checkbox-list')); ?></td>
                    <?php endif; ?>
                   <td class="dc"><span class="dropdown"> <span title="<?php echo __l('Actions');?>" data-toggle="dropdown" class="graydarkc left-space hor-smspace icon-cog text-18 cur dropdown-toggle"> <span class="hide"><?php echo __l('Action');?></span> </span>
                                <ul class="dropdown-menu arrow no-mar dl">
        			<li><?php echo $this->Html->link('<i class="icon-edit"></i>'.__l('Edit'), array('action' => 'edit', $itemFlag['ItemFlag']['id']), array('escape' => false,'class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
        			<li><?php echo $this->Html->link('<i class="icon-remove"></i>'.__l('Delete'), array('action' => 'delete', $itemFlag['ItemFlag']['id']), array('escape' => false,'class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
			   </ul>
   	  </span>
		</td>
                    <td>
                        <?php echo $this->Html->link($this->Html->cText($itemFlag['User']['username']), array('controller'=> 'users', 'action'=>'view', $itemFlag['User']['username'], 'admin' => false), array( 'class' => 'js-no-pjax', 'escape' => false));?>
                    </td>
                    <td><div class="clearfix"><span class="pull-left right-mspace">
					<?php echo $this->Html->link($this->Html->showImage('Item', $itemFlag['Item']['Attachment']['0'], array('dimension' => 'small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($itemFlag['Item']['title'], false)), 'title' => $this->Html->cText($itemFlag['Item']['title'], false))), array('controller' => 'items', 'action' => 'view', $itemFlag['Item']['slug'], 'admin' => false), array('class' => 'js-no-pjax', 'escape' => false));?></span>
					<span class="htruncate  span4" >
                     <?php echo $this->Html->link($this->Html->cText($itemFlag['Item']['title']), array('controller'=> 'items', 'action'=>'view', $itemFlag['Item']['slug'], 'admin' => false), array('escape' => false, 'class' => 'js-no-pjax', 'title'=>$this->Html->cText($itemFlag['Item']['title'], false), 'class' => 'js-bootstrap-tooltip'));?>
                    </span></div></td>
                    <td><?php echo $this->Html->cText($itemFlag['ItemFlagCategory']['name']);?></td>
                    <td><div class="htruncate js-bootstrap-tooltip span5" title="<?php echo $this->Html->cText($itemFlag['ItemFlag']['message'], false); ?>"><?php echo $this->Html->cText($itemFlag['ItemFlag']['message']);?></div></td>
                    <td>
					      <?php if(!empty($itemFlag['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($itemFlag['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $itemFlag['Ip']['ip'], 'admin' => false), array('class' => 'js-no-pjax', 'target' => '_blank', 'title' => __l('whois').' '.$itemFlag['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($itemFlag['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo $this->Html->cText(strtolower($itemFlag['Ip']['Country']['iso_alpha2']), false); ?>" title ="<?php echo $this->Html->cText($itemFlag['Ip']['Country']['name'], false); ?>">
									<?php echo $this->Html->cText($itemFlag['Ip']['Country']['name'], false); ?>
								</span>
                                <?php
                            endif;
							 if(!empty($itemFlag['Ip']['City'])):
                            ?>
                            <span class="htruncate js-bootstrap-tooltip span2" title="<?php echo $this->Html->cText($itemFlag['Ip']['City']['name'], false);?>"><span> 	<?php echo $this->Html->cText($itemFlag['Ip']['City']['name'], false); ?>    </span></span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
					</td>
                    <td class="dc"><?php echo $this->Html->cDateTimeHighlight($itemFlag['ItemFlag']['created']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="9"><div class="space dc grayc"><p class="ver-mspace top-space text-16 "><?php echo sprintf(__l('No %s Flags available'), Configure::read('item.alt_name_for_item_singular_caps'));?></p></div></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
 
    <?php
    if (!empty($itemFlags)):
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