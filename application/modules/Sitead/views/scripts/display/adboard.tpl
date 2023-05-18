<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: adboard.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->

<!--Div for plugin message for demo site-->
<div id="demo_msg_sitead"></div>
<div id="advertisment_content">
  <div class="cadcomp_vad_header">
    <h3><?php echo $this->translate("Ad Board") ?></h3>

    <?php
    $viewer_id = $this->viewer()->getIdentity();
    ?>

    <?php if (Engine_Api::_()->sitead()->enableCreateLink()) : ?>
      <div class="cmad_hr_link">
        <?php $create_ad_url = $this->url(array(), 'sitead_listpackage', true); ?>
        <a href="<?php echo $create_ad_url; ?>"><?php echo $this->translate("Create an Ad"); ?> 
          <i class="fa fa-angle-right" aria-hidden="true"></i></a>
      </div>
    <?php endif; ?>
  </div>
  <div class="caab_ad">
    <?php if (empty($this->noResult)) { ?>
      <?php
      $this->identity = '999999999999';
      include APPLICATION_PATH . '/application/modules/Sitead/views/scripts/_adsDisplay.tpl';
    } else {
      ?>

      <div class='tip'>
        <span style="float:none;">
          <?php echo $this->translate('No advertisements have been created yet.'); ?>
          <?php if (Engine_Api::_()->sitead()->enableCreateLink()): ?>
            <?php echo $this->translate(' Be the first to %1$screate an ad%2$s.', '<a href="' . $this->url(array(), 'sitead_listpackage', true) . '">', '</a>'); ?>
          <?php endif; ?>
        </span>
      </div>
    <?php } ?>
    <div style="clear:both"></div>
  </div>	
</div>

<!-- <script type="text/javascript">
  
  $.get("https://ipinfo.io/json", function (response) {
   window.console.log(response);
}, "jsonp");
</script> -->

