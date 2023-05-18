<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: view.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if (isset($this->adultContent)){ ?>
<?php $adultdefaultPhoto = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_album_default_adult', 'application/modules/Sesvideo/externals/images/sesalbum_adult.png'); ?>
<center><img src="<?php echo $adultdefaultPhoto; ?>" alt="" /></center>
<div style="color: red;text-align: center;padding-top: 20px;font-size:17px;"><?php echo $this->translate('This video is marked as adult, allow adult content to view this adult video.');?></div>
<?php } ?>