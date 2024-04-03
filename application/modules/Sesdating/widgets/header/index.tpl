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
<div class="header_top clearfix">
	<div class="header_top_container clearfix">
    <?php if($this->show_menu): ?>	
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.header.design', 2) == 2 && (Engine_Api::_()->sesdating()->getContantValueXML('sesdating_sidepanel_effect')) == 2){ ?>
        <div class="sidebar_panel_menu_btn <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.sidepanel.showhide', 1) == 1): ?>disabledmenu<?php endif;?>">
          <a href="javascript:void(0);" id="sidebar_panel_menu_btn"><i></i></a>
        </div> 
      <?php } else if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.header.design', 2) == 2 && (Engine_Api::_()->sesdating()->getContantValueXML('sesdating_sidepanel_effect')) == 1){ ?>
        <div class="sidebar_panel_menu_btn">
          <a href="javascript:void(0);" id="sidebar_panel_menu_btn"><i></i></a>
        </div> 
      <?php } ?>
    <?php endif; ?> 
    <?php if($this->show_logo):?>
      <div class="header_logo">
        <?php echo $this->content()->renderWidget('sesdating.menu-logo'); ?>
      </div>
     
    <?php endif; ?>
    <?php if($this->show_search):?>
      <a href="javascript:void(0);" class="mobile_search_toggle_btn fa fa-search" id="mobile_search_toggle"></a>
    <?php endif; ?>
    <div class="minimenu_search_box" id="minimenu_search_box">
      <?php if($this->show_search):?>
        <?php
              if(defined('sesadvancedsearch')){
                echo $this->content()->renderWidget("advancedsearch.search");
        }else{
        echo $this->content()->renderWidget("sesdating.search");
        }
        ?>
      <?php endif; ?>  
    </div>
    <?php if($this->show_mini):?>
      <div class="header_minimenu">
        <?php echo $this->content()->renderWidget("core.menu-mini"); ?>
       
      </div>
    <?php endif; ?>
  </div>
</div>
<?php if($this->show_menu):?>
	<div class="header_main_menu clearfix" id="sesdating_main_menu">
    <div class="header_main_menu_container">
      <?php if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesmenu')) { ?>
        <?php echo $this->content()->renderWidget("sesmenu.main-menu"); ?>
      <?php } else { ?>
        <?php echo $this->content()->renderWidget("sesdating.menu-main"); ?>
      <?php } ?>
    </div>
	</div>
<?php endif; ?>
<script>
scriptJquery(document).ready(function(){
    scriptJquery(".show_panel").click(function(){
        scriptJquery("body").addClass("header_sidepanel_open");
    });
		scriptJquery(".close_menu").click(function(){
        scriptJquery("body").removeClass("header_sidepanel_open");
    });
});
</script>
<script>
	scriptJquery(document).ready(function(e){
	var height = scriptJquery('.layout_page_header').height();
		if(document.getElementById('global_wrapper')) {
	    scriptJquery('#global_wrapper').css('margin-top', height+"px");
	  }
	});
</script>
<script type="text/javascript">
  scriptJquery(document).on('click','#mobile_search_toggle',function(){
    if(scriptJquery (this).hasClass('active')){
     scriptJquery (this).removeClass('active');
     scriptJquery ('.minimenu_search_box').removeClass('open_search');
    }else{
     scriptJquery (this).addClass('active');
     scriptJquery ('.minimenu_search_box').addClass('open_search');
    }
 });
    	
<?php if($this->show_menu && (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.header.design', 2) == 2)){ ?>
	scriptJquery ("body").addClass('sidebar-panel-enable');
<?php } ?>

//Clear cache when admin choose Always show up from drop down.
scriptJquery(document).ready(function(e){
  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.header.design', 2) == 2 && (Engine_Api::_()->sesdating()->getContantValueXML('sesdating_sidepanel_effect')) == 2 && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.sidepanel.showhide', 1) == 1){ ?>
    setCookiePannel('sesdating','1','30');
  <?php } ?>
});

 // Main Menu Toggle
 // scriptJquery(document).ready(function(){
 //      scriptJquery(".dating_mobile_nav_toggle").click(function () {
 //       if(scriptJquery("#dating_mobile_nav").hasClass('show-nav')){
 //         scriptJquery("#dating_mobile_nav").removeClass('show-nav')
 //       }
 //       else{
 //         scriptJquery("#dating_mobile_nav").addClass('show-nav');
 //       }
 //     });
 //   });


</script>

