<?php ?>
<?php $orderDetails = $this->orderDetails; ?>
<?php $defaultCurrency = Engine_Api::_()->sesbasic()->defaultCurrency(); ?>
<?php echo $this->partial('index/left-bar.tpl', 'egifts', array("navigation" => $this->navigation)); ?>
  <div class="egifts_dashboard_content sesbm sesbasic_clearfix">
		<div class="egifts_dashboard_content_header sesbasic_clearfix">
			<h3>
				<?php echo $this->translate("Make Payment Request"); ?>
			</h3>
			<?php if ($this->thresholdAmount > 0) { ?>
				<div class="egifts_dashboard_threshold_amt">
					<?php echo $this->translate("Threshold for payout: "); ?> <b>
						<?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice($this->thresholdAmount, $defaultCurrency); ?>
					</b>
				</div>
			<?php } ?>
		</div>
		<?php if (!($this->userGateway)) { ?>
			<div class="tip">
				<span>
					<?php echo $this->translate('Payment details not submited yet %1$sClick Here%2$s to submit.', '<a href="' . $this->url(array('action' => 'account-details'), 'egifts_general', true) . '">', '</a>'); ?>
				</span>
			</div>
		<?php } ?>
		<div class="egifts_dashboard_stats_container">
			<div class="egifts_dashboard_stat">
				<span>
					<?php echo $this->translate("Total Orders"); ?>
				</span>
				<span><?php echo $orderDetails['totalOrder']; ?></span>
			</div>
			<div class="egifts_dashboard_stat">
				<span>
					<?php echo $this->translate("Total Amount"); ?>
				</span>
				<span><?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice($orderDetails['totalAmountSale'],$defaultCurrency); ?></span>
			</div>
			<div class="egifts_dashboard_stat">
				<span>
					<?php echo $this->translate("Total Commission"); ?>
				</span>
				<span>
					<?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice($this->adminTotalCommission, $defaultCurrency); ?>
				</span>
			</div>
			<div class="egifts_dashboard_stat">
				<span>
					<?php echo $this->translate("Total Remaining Amount"); ?>
				</span>
				<span><?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice($this->remainingAmount, $defaultCurrency); ?></span>
			</div>
		</div>
		<?php if($this->remainingAmount && $this->remainingAmount >= $this->thresholdAmount && ($this->userGateway) && count($this->isAlreadyRequests) == 0){ ?>
			<div class="egifts_request_payment_link clear">
				<a href="<?php echo $this->url(array('action' => 'payment-request'), 'egifts_general', true); ?>" class="openSmoothbox sesbasic_button"><i class="fa fa-money-bill"></i><span><?php echo $this->translate("Make Request For Payment."); ?></span></a>
			</div>
		<?php } ?>
		<?php if (isset($this->paymentRequests) && engine_count($this->paymentRequests) > 0): ?>
			<div class="egifts_dashboard_table sesbasic_bxs">
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
											<?php echo $this->htmlLink($this->url(array('action' => 'payment-request', 'id' => $item->userpayrequest_id), 'egifts_general', true), $this->translate(""), array('class' => 'openSmoothbox sesbasic_icon_edit', 'title' => $this->translate("Edit Request"))); ?>
											<?php echo $this->htmlLink($this->url(array('action' => 'delete-payment', 'id' => $item->userpayrequest_id), 'egifts_general', true), $this->translate(""), array('class' => 'openSmoothbox sesbasic_icon_delete', 'title' => $this->translate("Delete Request"))); ?>
										<?php } ?>
										<?php //echo $this->htmlLink($this->url(array('action' => 'detail-payment', 'id' => $item->userpayrequest_id), 'egifts_general', true), $this->translate(""), array('class' => 'openSmoothbox fa fa-eye', 'title' => $this->translate("View Details"))); ?>
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
