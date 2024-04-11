<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesfbchat
 * @package    Sesfbchat
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-01-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '<?php echo $this->appId ? $this->appId : ""; ?>',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v2.11'
    });
  };
  (function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk/xfbml.customerchat.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
<div class="fb-customerchat" page_id="<?php echo $this->pageId; ?>" theme_color="#<?php  echo $this->theme_color; ?>" logged_in_greeting="<?php echo $this->login_text; ?>" logged_out_greeting="<?php echo $this->logout_text; ?>"></div>
