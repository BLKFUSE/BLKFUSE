<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjurymember
 * @package    Sescontestjurymember
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-02-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestjurymember/externals/styles/styles.css'); ?>

<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $viewerId = $viewer->getIdentity();?>
<?php $height = $this->params['height'];?>
<?php $width = $this->params['width'];?>
<ul class="sescontestjurymember_list sesbasic_bxs sesbasic_clearfix">
  <?php foreach($this->results as $user):?>
    <?php $user = Engine_Api::_()->getItem('user',$user->user_id);?>
  	<li>
      <article>
        <div class="item_thumb" style="height:<?php echo $height ?>px;width:<?php echo $width ?>px;">
          <?php echo $this->htmlLink($user->getHref(), $this->itemBackgroundPhoto($user, 'thumb.profile'), array('title' => $user->getTitle(), 'target' => '_blank')); ?>
        </div>
        <?php if(strlen($user->getTitle()) > $this->params['title_truncation']):?>
          <?php $title = mb_substr($user->getTitle(),0,$this->params['title_truncation']).'...';?>
        <?php else: ?>
          <?php $title = $user->getTitle();?>
        <?php endif; ?>
        <div class="item_info">
          <div class="item_title"><?php echo $this->htmlLink($user->getHref(), $title, array('title' => $user->getTitle(), 'target' => '_blank')); ?></div>
        </div>
      </article>  
  	</li>
  <?php endforeach;?>
</ul>
