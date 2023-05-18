<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2012-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: tellafriend.tpl SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<div class="siteshare_smoothbox_lightbox_overlay" style="display: block;"></div>
<div class="siteshare_smoothbox_lightbox_content_wrapper" style="display: block;">
  <?php if( !$this->sucess ): ?>
    <div class="siteshare_smoothbox_lightbox_content siteshare_socialshare_smoothbox" style="max-width: 680px; width:100%; margin-top: 20px;">
      <div class="siteshare_smoothbox_lightbox_content_html siteshare_sendemail">
        <div class="sharelinksblock">
          <a href="javacript:void()" class="siteshare_smoothbox_lightbox_close" onclick="window.close()">
            <i class="fa fa-close"></i>
          </a>
          <div class="share_heading share_sendemail_heading">
            <h3><?php echo $this->translate('Tell a Friend') ?></h3>
            <p>
              <b class="siteshare_ss_pagetitle"><?php echo $this->translate('Share this %s', $this->contentMedia); ?></b>
              <span><?php echo $this->absoluteUrl($this->contentUrl) ?></span>
            </p>
          </div>
          <div class="siteshare_form_sendemail_popup">
            <?php echo $this->form->render($this); ?>
          </div>

        </div>
      </div>
    </div>
  <?php else: ?>
    <div class='siteshare_sendemail_success'>
      <i class='fa fa-check'></i>
      <span><?php echo $this->translate('Message sent!') ?></span>
    </div>
    <script>
      setTimeout(function () {
        window.close();
      }, 2000);
    </script>
  <?php endif; ?>
</div>



