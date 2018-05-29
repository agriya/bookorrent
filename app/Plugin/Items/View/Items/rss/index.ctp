<?php /* SVN: $Id: index.ctp 12757 2010-07-09 15:01:40Z jayashree_028ac09 $ */ ?>
  <?php if(!empty($items)): ?>
      <?php
        foreach($items as $item):
          $item_image = '';
          if(!empty($item['Attachment'])):
		    $image_url = getImageUrl('Item',$item['Attachment'][0], array('full_url' => true, 'dimension' => 'big_thumb'));
			$item_image = '<img src="'.$image_url.'" alt="'. sprintf(__l('[Image: %s]'), $this->Html->cText($item['Item']['title'], false)) .'" title="'. $this->Html->cText($item['Item']['title'], false) .'">';
          endif;
          $item_image = (!empty($item_image)) ? '<p>'.$item_image.'</p>':'';

          echo $this->Rss->item(array() , array(
              'title' => $this->Html->cText($item['Item']['title'], false),
              'link' => array(
                'controller' => 'items',
                'action' => 'view',
                $item['Item']['slug']
              ) ,
              'description' => array(
				'value' => $item_image.'<p>'.$this->Html->cText($item['Item']['description']).'</p>',
				'cdata' => true,
				'convertEntities' => false,
			   )
            ));
        endforeach;
      ?>
  <?php endif; ?>
