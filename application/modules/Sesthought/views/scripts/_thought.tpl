<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _thought.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<div class="sesthought_feed_thought">
  <?php if($this->thought->mediatype == 1 && !empty($this->thought->photo_id)) { ?>
    <div class="sesthought_img"><?php echo $this->itemPhoto($this->thought, 'thumb.main') ?></div>
  <?php } else if($this->thought->mediatype == 2 && $this->thought->code) { ?>
    <div class="sesthought_video"><?php echo $this->thought->code; ?></div>
  <?php } ?>
  <?php if(!empty($this->thought->thoughttitle)) { ?>
    <div class="sesthought_title">  
      <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
        <a href="<?php echo $this->thought->getHref(); ?>"><?php echo $this->thought->thoughttitle; ?></a>
      <?php } else { ?>
        <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $this->thought->getIdentity(); ?>' class="sessmoothbox" href="javascript:;"><?php echo $this->thought->thoughttitle; ?></a>
      <?php } ?>
    </div>
  <?php } ?>
  <p class="sesthought_thought">
    <?php echo nl2br($this->thought->title); ?>
  </p>
  <?php if($this->thought->source) { ?>
    <p class="sesbasic_text_light sesthought_thought_src">&mdash; <?php echo $this->thought->source; ?></p>
  <?php } ?>
  <p class="sesthought_thought_link">
    <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.show', 0)) { ?>
      <a href="<?php echo $this->thought->getHref(); ?>"><?php echo $this->translate('Read More'); ?></a>
    <?php } else { ?>
      <a data-url='sesthought/index/thought-popup/thought_id/<?php echo $this->thought->getIdentity(); ?>' class="sessmoothbox" href="javascript:;"><?php echo $this->translate('Read More'); ?></a>
    <?php } ?>
  </span>
</div>