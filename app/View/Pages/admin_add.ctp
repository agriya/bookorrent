<ul class="breadcrumb top-mspace ver-space">
  <li><?php echo $this->Html->link( __l('Pages'), array('controller'=>'pages','action'=>'index', 'admin' => 'true'), array('escape' => false));?><span class="divider">/</span></li>
  <li class="active"><?php echo __l('Add Page'); ?></li>
</ul>
<?php
    if(!empty($page)):
        ?>
        <div class="js-tabs">
        <ul class="menu-tabs clearfix">
            <li><span><?php echo $this->Html->link(__l('Preview'), '#preview'); ?></span></li>
            <li><span><?php echo $this->Html->link(__l('Change'), '#add'); ?></span></li>
        </ul>
        <div id="preview">
            <div class="page">
                <h2><?php echo $this->Html->cText($page['Page']['title'], false); ?></h2>
                <div class="entry">
                   <?php echo $this->Html->cHtml($page['Page']['content'], false); ?>
                </div>
            </div>
        </div>
        <?php
    endif;
?>
<div id="add">
    <div class="pages form sep-top">
        <?php echo $this->Form->create('Page', array('class' => 'form-horizontal space'));?>
        <fieldset>
            <?php
                echo $this->Form->input('title', array('between' => '', 'label' =>__l('Page title')));
                echo $this->Form->input('title_es', array('between' => '', 'label' =>__l('Page title (Spanish)')));
                if(!empty($this->request->data['Page']['category_id'])) {
					echo $this->Form->hidden('category_id');
				}
            ?>
				<div class="required clearfix">
					<label class="pull-left" for="NodeBody"><?php echo __l('Body');?></label>
					<div class="input textarea bot-space span13">
						<?php echo $this->Form->input('content', array('class' => 'js-editor pull-left', 'label' => false, 'div' => false)); ?>
					</div>
				</div>
				<div class="required clearfix">
					<label class="pull-left" for="NodeBody"><?php echo __l('Body (Spanish)');?></label>
					<div class="input textarea bot-space span13">
						<?php echo $this->Form->input('content_es', array('class' => 'js-editor pull-left', 'label' => false, 'div' => false)); ?>
					</div>
				</div>
			<?php
                echo $this->Form->input('slug',array('label' => __l('Slug'),'info' => __l('When you create link for this page, url should be page/value of this field.')));
			?>
        </fieldset>
        <fieldset>
			<?php
				echo $this->Form->input('meta_keywords', array('label' =>__l('Meta Keywords')));
				echo $this->Form->input('meta_description', array('type' => 'textarea', 'label' =>__l('Meta Description')));
			?>
        </fieldset>
            <div class="form-actions">
            	<?php
					echo $this->Form->submit(__l('Add'), array('name' => 'data[Page][Add]', 'class' => 'btn btn-large btn-primary textb text-16 pull-right','div'=>false));
					echo $this->Form->submit(__l('Preview'), array('name' => 'data[Page][Preview]', 'class' => 'btn btn-large space btn-primary textb text-16 pull-right','div'=>false));
				?>
            </div>
            <?php echo $this->Form->end();  ?>
    </div>
</div>
<?php if(!empty($page)): ?>
	</div>
<?php endif; ?>