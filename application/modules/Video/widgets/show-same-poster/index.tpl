<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Video
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>

<ul class="generic_list_widget generic_list_widget_large_photo">
  <?php foreach( $this->paginator as $item ): ?>
    <li>
      <div class="photo">
        <?php echo $this->htmlLink($item->getHref(), $this->itemBackgroundPhoto($item, 'thumb.normal'), array('class' => 'thumb')) ?>
      </div>
      <div class="info">
        <div class="title">
          <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
        </div>
        <div class="stats">
          <?php echo $this->timestamp($item->creation_date) ?>
        </div>
        <div class="owner">
          <?php
            $owner = $item->getOwner();
            echo $this->translate('Posted by %1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
          ?>
        </div>
      </div>
    </li>
  <?php endforeach; ?>
</ul>
