<?php ?>

<?php $defaultCurrency = Engine_Api::_()->sesbasic()->defaultCurrency(); ?>
<?php echo $this->partial('index/left-bar.tpl', 'sescredit', array("navigation" => $this->navigation)); ?>
  <div class="sescredit_dashboard_content sesbm sesbasic_clearfix">
		<div class="sescredit_dashboard_content_header sesbasic_clearfix">
			<h3>
				<?php echo $this->translate("Make Payment Request"); ?>
			</h3>
			<?php if ($this->thresholdAmount > 0) { ?>
				<div class="sescredit_dashboard_threshold_amt">
					<?php echo $this->translate("Threshold for payout: "); ?> <b>
						<?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice($this->thresholdAmount, $defaultCurrency); ?>
					</b>
				</div>
			<?php } ?>
		</div>
		<?php if (!($this->userGateway)) { ?>
			<div class="tip">
				<span>
					<?php echo $this->translate('Payment details not submited yet %1$sClick Here%2$s to submit.', '<a href="' . $this->url(array('action' => 'account-details'), 'sescredit_general', true) . '">', '</a>'); ?>
				</span>
			</div>
		<?php } ?>
		<div class="sescredit_dashboard_stats_container">
			<div class="sescredit_dashboard_stat">
				<span>
					<?php echo $this->translate("Total Credits"); ?>
				</span>
				<?php $credit = Engine_Api::_()->getDbTable('credits','sescredit')->getTotalCreditValue(array('point_type' => 'credit', 'type' => 'cashcredit_byowner'));?>
				<?php $debit = Engine_Api::_()->getDbTable('credits','sescredit')->getTotalCreditValue(array('point_type' => 'deduction', 'type' => 'cashcredit_byowner'));?>
				<?php $cashcredit_byowner_debit = Engine_Api::_()->getDbTable('credits','sescredit')->getTotalCreditValue(array('point_type' => 'cashcredit_byowner', 'type' => 'deduction'));?>
				<span><?php echo $credit ? $credit : 0; ?></span>
			</div>
			<div class="sescredit_dashboard_stat">
				<span>
					<?php echo $this->translate("Total Commission"); ?>
				</span>
				<span>
					<?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice($this->adminTotalCommission, $defaultCurrency); ?>
				</span>
			</div>
			<div class="sescredit_dashboard_stat">
				<span>
					<?php echo $this->translate("Credits remaining to payout"); ?>
				</span>
				<span>
					<?php echo $credit - ($debit + $cashcredit_byowner_debit); ?>
				</span>
			</div>
		</div>
		<?php $remainingPoints = $credit - ($debit + $cashcredit_byowner_debit);
		$remainingPoints = $remainingPoints / Engine_Api::_()->getApi('settings', 'core')->getSetting('sescredit.creditvalue', '1000');
		?>
		<?php if ($remainingPoints && $remainingPoints >= $this->thresholdAmount && engine_count($this->isAlreadyRequests) == 0) { ?>
			<div class="sescredit_request_payment_link clear">
				<a href="<?php echo $this->url(array('action' => 'payment-request'), 'sescredit_general', true); ?>" class="openSmoothbox sesbasic_button"><i class="fa fa-money-bill"></i><span><?php echo $this->translate("Make Request For Payment."); ?></span></a>
			</div>
		<?php } ?>
		<?php if (isset($this->paymentRequests) && engine_count($this->paymentRequests) > 0): ?>
			<div class="sescredit_dashboard_table sesbasic_bxs">
				<form method="post">
					<table>
						<thead>
							<tr>
								<th class="centerT">
									<?php echo $this->translate("Request Id"); ?>
								</th>
								<th>
									<?php echo $this->translate("Amount Requested") ?>
								</th>
								<th>
									<?php echo $this->translate("Requested Date") ?>
								</th>
								<th>
									<?php echo $this->translate("Release Amount") ?>
								</th>
								<th>
									<?php echo $this->translate("Request Message") ?>
								</th>
								<th>
									<?php echo $this->translate("Response Message") ?>
								</th>
								<th>
									<?php echo $this->translate("Release Date") ?>
								</th>
								<th>
									<?php echo $this->translate("Status") ?>
								</th>
								<th>
									<?php echo $this->translate("Options") ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach ($this->paymentRequests as $item): ?>
								<tr>
									<td data-label="<?php echo $this->translate("Request Id") ?>" class="centerT"><?php echo $item->userpayrequest_id; ?></td>
									<td data-label="<?php echo $this->translate("Amount Requested") ?>" class="centerT"><?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice($item->requested_amount, $defaultCurrency); ?></td>
									<td data-label="<?php echo $this->translate("Requested Date") ?>"><?php echo $item->creation_date; ?></td>
									<td data-label="<?php echo $this->translate("Release Amount") ?>" class="centerT"><?php echo $item->state != 'pending' ? Engine_Api::_()->sesbasic()->getCurrencyPrice($item->release_amount, $defaultCurrency) : "-"; ?>
									</td>
									<td data-label="<?php echo $this->translate("Request Message") ?>">
										<div class="_msg"><?php echo $item->user_message; ?></div>
									</td>
									<td data-label="<?php echo $this->translate("Response Message") ?>">
										<div class="_msg"><?php echo $item->admin_message; ?></div>
									</td>
									<td>
										<?php echo $item->release_date && (bool) strtotime($item->release_date) && $item->state != 'pending' ? Engine_Api::_()->sesbasic()->dateFormat($item->release_date) : '-'; ?>
									</td>
									<td data-label="<?php echo $this->translate("Status") ?>"><?php echo ucfirst($item->state); ?></td>
									<td class="table_options">
										<?php if ($item->state == 'pending') { ?>
											<?php echo $this->htmlLink($this->url(array('action' => 'payment-request', 'id' => $item->userpayrequest_id), 'sescredit_general', true), $this->translate(""), array('class' => 'openSmoothbox sesbasic_icon_edit', 'title' => $this->translate("Edit Request"))); ?>
											<?php echo $this->htmlLink($this->url(array('action' => 'delete-payment', 'id' => $item->userpayrequest_id), 'sescredit_general', true), $this->translate(""), array('class' => 'openSmoothbox sesbasic_icon_delete', 'title' => $this->translate("Delete Request"))); ?>
										<?php } ?>
										<?php //echo $this->htmlLink($this->url(array('action' => 'detail-payment', 'id' => $item->userpayrequest_id), 'sescredit_general', true), $this->translate(""), array('class' => 'openSmoothbox fa fa-eye', 'title' => $this->translate("View Details"))); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</form>
			</div>
		<?php else: ?>
			<div class="tip">
				<span>
					<?php echo $this->translate("You do not have any pending payment request yet.") ?>
				</span>
			</div>
		<?php endif; ?>
	</div>
</div>
</div>
</div>
</div>
</div>
