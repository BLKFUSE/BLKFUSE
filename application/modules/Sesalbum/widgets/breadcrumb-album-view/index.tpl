<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php
	if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')) {
		include APPLICATION_PATH .  '/application/modules/Sesadvancedcomment/views/scripts/_jsFiles.tpl';
	}
?>
<?php 
  $isValid = false;
  if(isset($this->album->resource_type) && $this->album->resource_type){
    $item = Engine_Api::_()->getItem($this->album->resource_type,$this->album->resource_id);
    if($item)
      $isValid = true;
  }
  $dontShow = true;
  if($isValid && !Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.other.modulealbums', 0))
    $dontShow = false;
   
?>
<div class="sesbasic_breadcrumb">
  <?php if($dontShow): ?>
    <a href="<?php echo $this->url(array('action' => 'home'), "sesalbum_general"); ?>"><?php echo $this->translate("Album Home"); ?></a>&nbsp;&raquo;
    <a href="<?php echo $this->url(array('action' => 'browse'), "sesalbum_general"); ?>"><?php echo $this->translate("Browse Albums"); ?></a>&nbsp;&raquo;
  <?php endif; ?>
  <?php if($this->album->getType() == 'album' && $isValid):?>
    <?php if($item): ?>
      <a href="<?php echo $item->getHref(); ?>"><?php echo $item->getTitle(); ?></a>&nbsp;&raquo;
    <?php endif; ?>
  <?php endif; ?>
  <?php echo $this->album->getTitle(); ?>
</div>
