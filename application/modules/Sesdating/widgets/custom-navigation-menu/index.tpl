<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/styles/styles.css'); ?>
<div class="sesdating_banner_container sesbasic_bxs" style="height:<?php echo $this->height; ?>px;">
	<div class="sesdating_banner_inner" style="height:<?php echo $this->height; ?>px;background-image:url(<?php echo Engine_Api::_()->sesdating()->getFileUrl($this->backgroundimage); ?>);">
  	<div id="sesdating_custom_navigation_banner" <?php if($this->textalignment == 'center'): ?> class="_txtcenter" <?php endif; ?>></div>
  </div>  
 </div>
<?php
	$request = Zend_Controller_Front::getInstance()->getRequest();
	$moduleName = $request->getModuleName();
	
?>
<script type="application/javascript">
scriptJquery(document).ready(function(e){
    var bowsermenulength = scriptJquery('.layout_<?php echo $moduleName; ?>_browse_menu').find('.tabs').find('ul').length;
      if(bowsermenulength == 0){
        scriptJquery('.layout_<?php echo $moduleName; ?>_browse_menu').hide();
    }
    if(scriptJquery('.layout_<?php echo $moduleName; ?>_browse_menu').length) {
    
      scriptJquery ("body").addClass('sesdating_menubanner');

      var navigationMenu = scriptJquery('.layout_<?php echo $moduleName; ?>_browse_menu').find('.headline');
      var finalNavigationTitle = "<h2>"+navigationMenu.find('h2').html()+"</h2>";
      scriptJquery('#sesdating_custom_navigation_banner').html(finalNavigationTitle);
      navigationMenu.find('h2').remove();
      var height = scriptJquery(".layout_page_header").height();
      if(document.getElementById("global_wrapper")) {
        scriptJquery("#global_wrapper").css("margin-top", height+"px");
      }
    } else {
      scriptJquery('.layout_sesdating_custom_navigation_menu').hide();
    }
});
</script>
