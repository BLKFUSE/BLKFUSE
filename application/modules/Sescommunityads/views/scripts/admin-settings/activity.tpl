<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: activity.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>

<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>


<script type="application/javascript">
<?php if(empty($this->activity)){ ?>
  scriptJquery('.form-elements').find('div').remove();
  scriptJquery('.form-elements').html('<div class="tip"><span><?php echo $this->translate("Advanced Activity not installed on your website."); ?></span></div>');
<?php } ?>
</script>