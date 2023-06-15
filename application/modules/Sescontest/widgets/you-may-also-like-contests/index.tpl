<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $viewerId = $viewer->getIdentity();?>
<?php $height = $this->params['height'];?>
<?php $width = $this->params['width'];?>
<?php $dateinfoParams['starttime'] = true; ?>
<?php $dateinfoParams['endtime']  =  true; ?>
<?php $dateinfoParams['timezone']  =  true; ?>
<?php if($this->params['viewType'] == 'listView'):?>
  <div class="sesbasic_sidebar_block sesbasic_bxs sesbasic_clearfix">
    <?php foreach($this->results as $contest):?>
      <?php if(isset($this->titleActive)):?>
        <?php if(strlen($contest->getTitle()) > $this->params['title_truncation']):?>
          <?php $title = mb_substr($contest->getTitle(),0,$this->params['title_truncation']).'...';?>
        <?php else: ?>
          <?php $title = $contest->getTitle();?>
        <?php endif; ?>
      <?php endif;?>
      <?php if (!empty($contest->category_id)):?>
        <?php $category = Engine_Api::_ ()->getDbtable('categories', 'sescontest')->find($contest->category_id)->current();?>
      <?php endif;?>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/similar-contest/_listView.tpl';?>
    <?php endforeach;?>
  </div>  
<?php else:?>
  <div class="row sesbasic_clearfix sesbasic_bxs">
    <?php foreach($this->results as $contest):?>
      <?php if(isset($this->titleActive)):?>
        <?php if(strlen($contest->getTitle()) > $this->params['title_truncation']):?>
            <?php $title = mb_substr($contest->getTitle(),0,$this->params['title_truncation']).'...';?>
        <?php else: ?>
          <?php $title = $contest->getTitle();?>
        <?php endif; ?>
      <?php endif;?>
      <?php if (!empty($contest->category_id)):?>
        <?php $category = Engine_Api::_ ()->getDbtable('categories', 'sescontest')->find($contest->category_id)->current();?>
      <?php endif;?>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/contest/_advgridView.tpl';?>
    <?php endforeach;?>
   </div>
<?php endif;?>