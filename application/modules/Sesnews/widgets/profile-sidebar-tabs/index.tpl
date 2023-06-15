<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?>
<?php
  $base_url = $this->layout()->staticBaseUrl;
   
?>
<div class="sesnews_sidebar_tabs sesnews_profile_tabs sesbasic_bxs"></div>

<script type="application/javascript">
if (matchMedia('only screen and (min-width: 767px)').matches) {
	scriptJquery(document).ready(function(){
	var tabs = scriptJquery('.layout_core_container_tabs').find('.tabs_alt').get(0).outerHTML;
	scriptJquery('.layout_core_container_tabs').find('.tabs_alt').remove();
	scriptJquery('.sesnews_sidebar_tabs').html(tabs);
	//scriptJquery('.sesnews_sidebar_tabs').find('.tabs_alt', '.tabs_parent' ).removeClass();
});
scriptJquery(document).on('click','ul#main_tabs li > a',function(){
	if(scriptJquery(this).parent().hasClass('more_tab'))
	  return;
	var index = scriptJquery(this).parent().index() + 1;
	var divLength = scriptJquery('.layout_core_container_tabs > div');
	for(i=0;i<divLength.length;i++){
		scriptJquery(divLength[i]).hide();
	}
	scriptJquery('.layout_core_container_tabs').children().eq(index).show();
});
scriptJquery(document).on('click','.tab_pulldown_contents ul li',function(){
 var totalLi = scriptJquery('ul#main_tabs > li').length;
 var index = scriptJquery(this).index();
 var divLength = scriptJquery('.layout_core_container_tabs > div');
	for(i=0;i<divLength.length;i++){
		scriptJquery(divLength[i]).hide();
	}
 scriptJquery('.layout_core_container_tabs').children().eq(index+totalLi).show();
});
}
</script>
