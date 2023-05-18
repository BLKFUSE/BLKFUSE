<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php

 if(isset($this->docActive)){
	$imageURL = $this->subject->getPhotoUrl();
	if(strpos($this->subject->getPhotoUrl(),'http') === false)
          	$imageURL = (!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" : "http://". $_SERVER['HTTP_HOST'].$this->subject->getPhotoUrl();
  $this->doctype('XHTML1_RDFA');
  $this->headMeta()->setProperty('og:title', strip_tags($this->subject->getTitle()));
  $this->headMeta()->setProperty('og:description', strip_tags($this->subject->getDescription()));
  $this->headMeta()->setProperty('og:image',$imageURL);
  $this->headMeta()->setProperty('twitter:title', strip_tags($this->subject->getTitle()));
  $this->headMeta()->setProperty('twitter:description', strip_tags($this->subject->getDescription()));
}
?>
<?php if (isset($this->adultContent)){ ?>
<?php $adultdefaultPhoto = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_album_default_adult', 'application/modules/Sesalbum/externals/images/sesalbum_adult.png'); ?>
<center><img src="<?php echo $adultdefaultPhoto; ?>" alt="" /></center>
<div style="color: red;text-align: center;padding-top: 20px;font-size:17px;"><?php echo $this->translate('This channel is marked as adult, allow adult content to view this adult channel.');?></div>
<?php } ?>