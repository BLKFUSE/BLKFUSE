<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manage-packages.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Epaidcontent/externals/styles/style.css'); ?>
<div class="epaidcontent_manage_packages">
   <h2>
      <?php echo $this->translate("Manage Packages") ?>
   </h2>
   <p>
      <?php echo $this->translate("Browse and manage subscription plans.") ?>
   </p>
   <?php if(Engine_Api::_()->authorization()->isAllowed('epaidcontent', $this->viewer, 'create')) { ?>
   <div class="epaidcontent_icon_button">
      <?php echo $this->htmlLink(array('action' => 'createpackage', 'reset' => false), $this->translate('Create Package'), array('class' => ' epaidcontent_icon_plan_add sesbasic_icon_add')); ?>
   </div>
   <?php } ?>
</div>
<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
<div>
   <div>
      <?php echo $this->paginationControl($this->paginator, null, null, array(
         'pageAsQuery' => true,
         )); ?>
   </div>
</div>
<br />

<ul  class="epaidcontent_manage_plan">
  <?php foreach( $this->paginator as $item ): ?>
    <li class="epaidcontent_manage_plan_item">
      <div class="_id">
        <?php echo $item->package_id ?>
      </div>
      <div class="epaidcontent_manage_plan_item_cont">
        <div class="_header">
          <span><?php echo $item->title ?></span>
          <a title="<?php echo $this->translate('Edit Package'); ?>"  href='<?php echo $this->url(array('action' => 'editpackage', 'package_id' => $item->package_id)) ?>' class="epaidcontent_manage_plan_edit">
            <i class="fas fa-pen"></i><?php echo $this->translate( "") ?>
            </a>
        </div>
        <div class="_stats">
          <div class="price">
            <?php echo Engine_Api::_()->epaidcontent()->getCurrencyPrice($item->price,Engine_Api::_()->epaidcontent()->defaultCurrency()); ?>
          </div>
          <div class="billing_section">
              <span class="_label"><?php echo $this->translate("Billing") ?>:</span>
              <span class="_value"> <?php echo $item->getPackageDescription() ?></span>
          </div>
          <div class="billing_section">
              <span class="_label"><?php echo $this->translate("Enabled") ?>:</span>
              <span class="_value"><?php echo ( $item->enabled ? $this->translate('Yes') : $this->translate('No') ) ?></span>
          </div>
        </div>
        <div class="_des">
          <?php echo nl2br($item->description); ?>
        </div>
      </div>
    </li>
   <?php endforeach; ?>
</ul>
<?php else: ?>
<div class="tip">
   <span>
   <?php echo $this->translate("Currently, there are not package."); ?>
   </span>
</div>
<?php endif; ?>