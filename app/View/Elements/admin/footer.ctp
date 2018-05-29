  <footer id="footer" class="sep-top sep-medium hor-space" itemscope itemtype="http://schema.org/WPFooter">
    <div class="container-fluid clearfix top-space">
      <div class="clearfix top-space graydarkc">
        <p class="span" itemprop="copyrightYear">&copy; <?php echo date('Y');?> <?php echo $this->Html->link($this->Html->cText(Configure::read('site.name'), false), '/', array('title' => Configure::read('site.name'),'class' => 'site-name', 'escape' => false, "itemprop"=>"copyrightHolder"));?>. <?php echo __l('All rights reserved');?>. </p>		
        <p class="clearfix span"><span class="pull-left"><a href="http://bookorrent.dev.agriya.com" title="<?php echo __l('Powered by BookorRent'); ?>" target="_blank" class="powered pull-left"><?php echo __l('Powered by BookorRent'); ?></a>,</span> <span class="pull-left"><?php echo __l('Made in'); ?></span> <?php echo $this->Html->link(__l('Agriya Web Development'), 'http://www.agriya.com/', array('target' => '_blank', 'title' => __l('Agriya Web Development'), 'class' => 'company pull-left js-no-pjax'));?></p>
        <p id="cssilize"><?php echo $this->Html->link(__l('CSSilized by CSSilize, PSD to XHTML Conversion'), 'http://www.cssilize.com/', array('target' => '_blank', 'title' => __l('CSSilized by CSSilize, PSD to XHTML Conversion'), 'class' => 'cssilize js-no-pjax'));?></p>
      </div>
    </div>
  </footer>