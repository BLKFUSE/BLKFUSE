<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Sescredit
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manage-payment-owner.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sescredit/views/scripts/dismiss_message.tpl';?>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <?php if(engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
      </div>
    <?php endif; ?>
    <div class="sesbasic-form-cont">
    <h3><?php echo $this->translate("Payments Made to the Credits Owners") ?></h3>
    <p><?php echo $this->translate('This page lists all of the payments made to the credits owners on your website. You can use this page to monitor these payments made. Entering criteria into the filter fields will help you find specific payment detail. Leaving the filter fields blank will show all the payments made to credits owners on your social network.'); ?></p>
    <br />
    <div class='admin_search sesbasic_search_form'>
      <?php echo $this->formFilter->render($this) ?>
    </div>
    <br />
    <?php $counter = $this->paginator->getTotalItemCount(); ?> 
    <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
      <div class="sesbasic_search_reasult">
        <?php echo $this->translate(array('%s Payment found.', '%s Payments found.', $counter), $this->locale()->toNumber($counter)) ?>
      </div>
      <form id='multidelete_form' method="post" action="<?php echo $this->url();?>">
        <div class="clear" style="overflow:auto;">
        <table class='admin_table'>
          <thead>
            <tr>
              <th><?php echo $this->translate("ID") ?></th>
              <th><?php echo $this->translate("Owner Name") ?></th>
              <th><?php echo $this->translate("Credit Points"); ?></th>
              <th><?php echo $this->translate("Requested Amount") ?></th>
              <th><?php echo $this->translate("Release Amount"); ?></th>
              <th><?php echo $this->translate("Released Date"); ?></th> 
              <th><?php echo $this->translate("Currency") ?></th>
              <th><?php echo $this->translate("Payment Request Date"); ?></th>
              <th><?php echo $this->translate("Options") ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($this->paginator as $item): ?>
              <?php $owner = Engine_Api::_()->getItem('user',$item->owner_id); 
              	if(!$owner)continue;
              ?>
            <tr>
              <td><?php echo $item->userpayrequest_id ?></td>
              <td><?php echo $item->getOwner(); ?></td>
              <td><?php echo $item->credit_point; ?></td>
              <td><?php echo round($item->requested_amount,2); ?></td>
              <td><?php echo round($item->release_amount,2); ?></td>
              <td><?php echo $item->release_date; ?></td>
              <td><?php echo $item->currency_symbol; ?></td>
              <td><?php echo $item->creation_date; ?></td>
              <td>
								<?php echo $this->htmlLink($this->url(array('action' => 'detail-payment', 'id' => $item->userpayrequest_id, 'owner_id' => $owner->getIdentity()), 'sescredit_general', true), $this->translate("View Details"), array('class' => 'smoothbox')); ?>
                <?php //echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescredit', 'controller' => 'settings', 'action' => 'view-paymentrequest', 'id' => $item->userpayrequest_id), $this->translate("View Details"), array('class' => 'smoothbox')) ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        </div>
      </form>
      <br/>
      <div>
        <?php echo $this->paginationControl($this->paginator); ?>
      </div>
    <?php else:?>
      <div class="joinfees">
        <span>
          <?php echo $this->translate("No payments have been made yet.") ?>
        </span>
      </div>
    <?php endif; ?>
    </div>
  </div>
</div>
