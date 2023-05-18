<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: socail-share.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php if( empty($this->navigation) ): ?>
  <div class="tip"><span><?php echo $this->translate("Invalid request!") ?></span></div>
<?php else: ?>
  <?php
  if( $this->subject() ) :
    $contentTitle = $this->subject()->getTitle() ? : $this->translate('Share this %s', $this->contentMedia);
    $url = $this->subject()->getHref();
  else:
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $url = $request->getRequestUri();
    $contentTitle = 'Share this page';
  endif;
  ?>
  <?php
  echo $this->partial('_socialShare.tpl', 'siteshare', array(
    'contentTitle' => $contentTitle,
    'contentUrl' => $url,
    'navigation' => $this->navigation
  ));
  ?>
<?php endif; ?>