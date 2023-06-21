<?php ?>

<?php $defaultCurrency = Engine_Api::_()->sesbasic()->defaultCurrency(); ?>
<?php echo $this->partial('index/left-bar.tpl', 'egifts', array("navigation" => $this->navigation)); ?>
					<div class="egifts_dashboard_content sesbm sesbasic_clearfix">
						<div class="egifts_dashboard_content_header sesbasic_clearfix">	
							<h3><?php echo $this->translate("Payments Received"); ?></h3>
							<p><?php echo $this->translate('Here, you are viewing the details of payments received from the website.') ?></p>
						</div>
						<?php if( isset($this->paymentRequests) && engine_count($this->paymentRequests) > 0): ?>
							<div class="egifts_dashboard_table sesbasic_bxs">
								<form method="post" >
									<table>
										<thead>
											<tr>
												<th><?php echo $this->translate("Requested Amount") ?></th>
												<th><?php echo $this->translate("Released Amount") ?></th>
												<th><?php echo $this->translate("Released Date") ?></th>
												<th><?php echo $this->translate("Response Message") ?></th>
												<th><?php echo $this->translate("Status") ?></th>
												<th><?php echo $this->translate("Options") ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($this->paymentRequests as $item): ?>
												<tr>
													<td  data-label="<?php echo $this->translate("Requested Amount") ?>" class="centerT"><?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice($item->requested_amount,$defaultCurrency); ?></td>
													<td  data-label="<?php echo $this->translate("Released Amount") ?>" class="centerT"><?php echo Engine_Api::_()->sesbasic()->getCurrencyPrice($item->release_amount	,$defaultCurrency); ?></td>
													
													<td data-label="<?php echo $this->translate("Released Date") ?>"><?php echo $item->release_date ? ($item->release_date) :  '-'; ?></td>
													<td data-label="<?php echo $this->translate("Response Message") ?>">
														<div class="_msg"><?php echo $this->string()->truncate(empty($item->admin_message	) ? '-' : $item->admin_message, 30) ?></div>
													</td>
													<td data-label="<?php echo $this->translate("Status") ?>" ><?php echo ucfirst($item->state); ?></td>
													<td class="table_options">
														<?php echo $this->htmlLink($this->url(array('action' => 'detail-payment', 'id' => $item->userpayrequest_id), 'egifts_general', true), $this->translate(""), array('title' => $this->translate("View Details"), 'class' => 'openSmoothbox sesbasic_icon_view')); ?>
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
									<?php echo $this->translate("No transactions have been made yet.") ?>
								</span>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
