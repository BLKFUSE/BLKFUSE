<?php  ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/styles/styles.css'); ?>
<div class="sescredit_dashboard_popup sesbasic_bxs">
  <?php if(!$this->errorMessage){ ?>
  	<?php echo $this->form->render() ?>
  <?php }else{ ?>
  	<div class="tip">
      <span>
        <?php echo $this->translate($this->message) ?>
      </span>
  	</div>
  <?php } ?>
</div>
