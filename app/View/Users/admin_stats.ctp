<?php $itemCount = count(CmsNav::items()); ?>
<div class="row-fluid ver-space ver-mspace">
<article class="span18 accordion tab-clr">
  <section class="no-pad top-mspace bot-mspace">
    <div id="dashboard-accordion" class="accordion">
      <?php echo $this->element('admin-charts-stats'); ?>
    </div>
  </section>
</article>
<aside class="span6 tab-clr">

	<section class="no-pad top-mspace bot-mspace">
		<div class="no-mar no-bor clearfix well no-mar space">
			<h5 class="pull-left textb graydarkc text-14"><i class="icon-time hor-smspace text-16"></i><span><?php echo __l('Timings'); ?></span></h5>
		</div>
		<section class="space left-mspace">
			<ul class="unstyled">
				<li><i class="icon-angle-right "></i><?php echo __l('Current time:').' '.$this->Html->cDateTime(strftime(Configure::read('site.datetime.format'))); ?></li>
				<li><i class="icon-angle-right "></i><?php echo __l('Last login:').' '.$this->Html->cDateTime($this->Auth->user('last_logged_in_time')); ?></li>
			</ul>
		</section>
	</section>
  <section class="thumbnail no-pad top-mspace bootstro" data-bootstro-step="<?php echo $itemCount + 2; ?>" data-bootstro-content="<?php echo sprintf(__l("It list the actions that admin need to take. Action such as users/%s waiting for approval, cancel the %s/ clear the %s flag of flagged %s, withdraw request waiting for approval and also affiliate withdraw request."), Configure::read('item.alt_name_for_item_plural_small'), Configure::read('item.alt_name_for_item_singular_small'), Configure::read('item.alt_name_for_item_singular_small'), Configure::read('item.alt_name_for_item_plural_small'));?>" data-bootstro-placement='left' data-bootstro-title="<?php echo __l('Actions to be taken'); ?>">
    <div class="js-cache-load  js-cache-load-action-taken {'data_url':'admin/items/action_taken', 'data_load':'js-cache-load-action-taken'}">
      <?php echo $this->element('item-admin_action_taken', array(), array('plugin' => 'Items')); ?>
    </div>
  </section> 
  <section class="thumbnail no-pad top-mspace">
    <div class="js-cache-load js-cache-load-recent-users {'data_url':'admin/users/recent_users', 'data_load':'js-cache-load-recent-users'}">
      <?php echo $this->element('users-admin_recent_users'); ?>
    </div>
  </section>
  <section class="no-pad top-mspace">
    <div class="no-mar no-bor clearfix well no-mar space">
      <h5 class="pull-left textb graydarkc text-14"><i class="icon-user hor-smspace text-16"></i><?php echo $this->Html->cText(Configure::read('site.name'), false); ?></h5>
    </div>
    <section class="space left-mspace">
	<div class="ver-mspace textb graydarkc"><?php echo __l('Version').' ' ?>  <?php echo Configure::read('site.version'); ?></div>
      <ul class="unstyled bot-mspace">
        <li><i class="icon-angle-right "></i><?php echo $this->Html->link(__l('Product Support'), 'http://customers.agriya.com/', array('class' => 'js-no-pjax', 'target' => '_blank', 'title' => __l('Product Support'))); ?> </li>
        <li><i class="icon-angle-right "></i> <?php echo $this->Html->link(__l('Product Manual'), 'http://dev1products.dev.agriya.com/doku.php?id=bookorrent' ,array('class' => 'js-no-pjax', 'target' => '_blank','title' => __l('Product Manual'))); ?> </li>
        <li><i class="icon-angle-right "></i><?php echo $this->Html->link(__l('Cssilize'), 'http://www.cssilize.com/', array('class' => 'js-no-pjax', 'target' => '_blank', 'title' => __l('Cssilize'))); ?> <small><?php echo __l('PSD to XHTML Conversion and').' ' . $this->Html->cText(Configure::read('site.name'), false) . ' '.__l('theming'); ?></small> </li>
        <li><i class="icon-angle-right "></i> <?php echo $this->Html->link(__l('Agriya Blog'), 'http://blogs.agriya.com/' ,array('class' => 'js-no-pjax', 'target' => '_blank','title' => __l('Agriya Blog'))); ?><small> <?php echo __l('Follow Agriya news');?></small> </li>
		<li class="grayc"><a href="#" class="btn btn-primary js-live-tour js-no-pjax"><?php echo __l('Live Tour'); ?></a> </li>
      </ul>
    </section>
  </section>
</aside>
</div>