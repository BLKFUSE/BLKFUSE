<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manage-tips.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/style.css'); ?>
<div class="eusertip_manage_tips">
   <h2>
      <?php echo $this->translate("Manage Tips") ?>
   </h2>
   <?php if(Engine_Api::_()->authorization()->isAllowed('eusertip', $this->viewer, 'create')) { ?>
   <div class="eusertip_icon_button">
      <?php echo $this->htmlLink(array('action' => 'createtip', 'reset' => false), $this->translate('Create Tip'), array('class' => ' eusertip_icon_plan_add sesbasic_icon_add')); ?>
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

<ul  class="eusertip_manage_plan">
  <?php foreach( $this->paginator as $item ): ?>
    <li class="eusertip_manage_plan_item">
      <div class="_id">
        <?php echo $item->tip_id ?>
      </div>
      <div class="eusertip_manage_plan_item_cont">
        <div class="_header">
          <span><?php echo $item->title ?></span>
          <a title="<?php echo $this->translate('Edit Tip'); ?>"  href='<?php echo $this->url(array('action' => 'edittip', 'tip_id' => $item->tip_id)) ?>' class="eusertip_manage_plan_edit">
            <i class="fas fa-pen"></i><?php echo $this->translate( "") ?>
            </a>
        </div>
        <div class="_stats">
          <div class="price">
            <?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($item->price,Engine_Api::_()->eusertip()->defaultCurrency()); ?>
          </div>
          <div class="billing_section">
            <span class="_label"><?php echo $this->translate("Billing") ?>:</span>
            <span class="_value"> <?php echo $item->getTipDescription() ?></span>
          </div>
          <div class="billing_section">
            <span class="_label"><?php echo $this->translate("Enabled") ?>:</span>
            <span class="_value"><?php echo ( $item->enabled ? $this->translate('Yes') : $this->translate('No') ) ?></span>
          </div>
        </div>
      </div>
    </li>
   <?php endforeach; ?>
</ul>
<?php else: ?>
<div class="tip">
   <span>
   <?php echo $this->translate("Currently, there are not tip."); ?>
   </span>
</div>
<?php endif; ?>
