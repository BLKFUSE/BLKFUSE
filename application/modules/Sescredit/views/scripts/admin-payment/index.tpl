<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Sescredit
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sescredit/views/scripts/dismiss_message.tpl';?>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
      </div>
    <?php endif; ?>
		<div class="sesbasic-form-cont">
			<?php $defaultCurrency = Engine_Api::_()->payment()->defaultCurrency(); ?>
			<h3><?php echo $this->translate("Manage Payment Requests") ?></h3>
			<p><?php echo $this->translate('This page lists all of the payment requests your member have made for cash credits. You can use this page to monitor these requests and take appropriate action for each. Entering criteria into the filter fields will help you find specific payment request. Leaving the filter fields blank will show all the payment requests on your social network.<br>Below, you can approve / reject a payment request and see payment details.'); ?></p>
			<br />
			<div class='admin_search sesbasic_search_form'>
				<?php echo $this->formFilter->render($this) ?>
			</div>
			<br />
			<?php $counter = $this->paginator->getTotalItemCount(); ?> 
			<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
				<div class="sesbasic_search_reasult">
					<?php echo $this->translate(array('%s Payment Request Found.', '%s Payment Requests Found.', $counter), $this->locale()->toNumber($counter)) ?>
				</div>
				<form method="post" >
					<div class="clear" style="overflow: auto;"> 
					<table class='admin_table' style="width:100%;">
						<thead>
							<tr>
								<th><?php echo $this->translate("ID"); ?></th>
								<th><?php echo $this->translate("Owner Name"); ?></th>
								<th><?php echo $this->translate("Credit Points") ?></th>
								<th title="Requested Amount"><?php echo $this->translate("R.Amount") ?></th>
								<th title="Requested Date"><?php echo $this->translate("R.Date") ?></th>
								<th><?php echo $this->translate("Status") ?></th>
								<th><?php echo $this->translate("Options") ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($this->paginator as $item): ?>
								<?php $user = Engine_Api::_()->getItem('user', $item->owner_id); 
									if(!$user)
										continue;
								?>
								<tr>
									<td><?php echo $item->userpayrequest_id; ?></td>
									<td><?php echo $this->htmlLink($user->getHref(), $this->translate(Engine_Api::_()->sesbasic()->textTruncation($user->getTitle(),16)), array('title' => $user->getTitle(), 'target' => '_blank')); ?></td>
									<td><?php echo $item->credit_point; ?></td>
									<td><?php echo Engine_Api::_()->payment()->getCurrencyPrice($item->requested_amount,$defaultCurrency); ?></td>
									<td><?php echo Engine_Api::_()->sesbasic()->dateFormat($item->creation_date	); ?></td> 
									<td><?php echo ucfirst($item->state); ?></td>
									<td>
										<?php if ($item->state == 'pending'){ ?>
											<?php echo $this->htmlLink($this->url(array('module' => 'sescredit', 'controller' => 'payment','owner_id' => $user->getIdentity(),'action'=>'approve','id'=>$item->userpayrequest_id), 'admin_default', true), $this->translate("Approve"), array('class' => 'smoothbox')); ?> |
											<?php echo $this->htmlLink($this->url(array('module' => 'sescredit', 'controller' => 'payment','action' => 'cancel', 'id' => $item->userpayrequest_id, 'owner_id' => $user->getIdentity()), 'admin_default', true), $this->translate("Reject"), array('class' => 'smoothbox')); ?> |
										<?php } ?>
										<?php //echo $this->htmlLink($this->url(array('action' => 'payment-requests', 'owner_id' => $user->getIdentity()), 'sescredit_general', true), $this->translate("Edit"), array('class' => '','target'=>'_blank')); ?>
										<?php echo $this->htmlLink($this->url(array('action' => 'detail-payment', 'id' => $item->userpayrequest_id, 'owner_id' => $user->getIdentity()), 'sescredit_general', true), $this->translate("Details"), array('class' => 'smoothbox')); ?>
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
				<div class="tip">
					<span>
						<?php echo $this->translate("There are no payment requests.") ?>
					</span>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
