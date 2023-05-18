<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesblog
 * @package    Sesblog
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _location_direction.tpl 2016-07-23 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
  <a href="javascript:;" onclick="openURLinSmoothBox('<?php echo $this->url(array("module"=> "sesvideo", "controller" => "index", "action" => "location",  "video_id" => $item->getIdentity(),'type'=>'video_location'),'default',true); ?>');return false;"><?php echo $item->location; ?></a>
<?php } else { ?>
  <?php echo $item->location;?>
<?php } ?>
