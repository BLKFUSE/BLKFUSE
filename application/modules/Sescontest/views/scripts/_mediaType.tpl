<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _mediaType.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(isset($this->mediaTypeActive)):?>
  <?php if($contest->contest_type == 3):?>
  <a href="<?php echo $this->url(array('action' => 'video'),'sescontest_media',true);?>"><span class="sescontest_list_type"><i class="fas fa-video" title="<?php echo $this->translate('Video Contest');?>"></i></span></a>
  <?php elseif($contest->contest_type == 4):?>
  <a href="<?php echo $this->url(array('action' => 'audio'),'sescontest_media',true);?>"><span class="sescontest_list_type"><i class="fas fa-music" title="<?php echo $this->translate('Audio Contest');?>"></i></span></a>
  <?php elseif($contest->contest_type == 2):?>
  <a href="<?php echo $this->url(array('action' => 'photo'),'sescontest_media',true);?>"><span class="sescontest_list_type"><i class="far fa-image" title="<?php echo $this->translate('Photo Contest');?>"></i></span></a>
  <?php else:?>
  <a href="<?php echo $this->url(array('action' => 'text'),'sescontest_media',true);?>"><span class="sescontest_list_type"><i class="far fa-file-alt" title="<?php echo $this->translate('Writing Contest');?>"></i></span></a>
  <?php endif;?>
<?php endif;?>