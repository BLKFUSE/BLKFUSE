<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/eticktokclone/externals/styles/styles.css'); ?>

<div class="eticktokclone_sidebar_links">
  <ul>
    <li class="eticktokclone-foryou active">
      <a href="javascript:;" onclick="setDataTick('foryou',this)">
        <i><svg class="_n" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24" width="18" height="18" style="display:none;"><path d="M19,24H5c-2.757,0-5-2.243-5-5V9.724c0-1.665,.824-3.215,2.204-4.145L9.203,.855c1.699-1.146,3.895-1.146,5.594,0l7,4.724c1.379,.93,2.203,2.479,2.203,4.145v9.276c0,2.757-2.243,5-5,5ZM12,1.997c-.584,0-1.168,.172-1.678,.517L3.322,7.237c-.828,.558-1.322,1.487-1.322,2.486v9.276c0,1.654,1.346,3,3,3h14c1.654,0,3-1.346,3-3V9.724c0-.999-.494-1.929-1.321-2.486L13.678,2.514c-.51-.345-1.094-.517-1.678-.517Z"/></svg><svg class="_a" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 24 24" width="18" height="18" style="display:none;"><path d="M19,24H5c-2.757,0-5-2.243-5-5V9.724c0-1.665,.824-3.215,2.204-4.145L9.203,.855c1.699-1.146,3.895-1.146,5.594,0l7,4.724c1.379,.93,2.203,2.479,2.203,4.145v9.276c0,2.757-2.243,5-5,5Z"/></svg></i>
        <span><?php echo $this->translate("For You");?></span>
      </a>
    </li>
    <li class="eticktokclone-member">
      <a href="javascript:;" onclick="setDataTick('member',this)">
        <i><svg class="_n" viewBox="0 0 24 24" width="18" height="18" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" style="display:none;"><path d="m7.5 13a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm0-7a2.5 2.5 0 1 0 2.5 2.5 2.5 2.5 0 0 0 -2.5-2.5zm7.5 17v-.5a7.5 7.5 0 0 0 -15 0v.5a1 1 0 0 0 2 0v-.5a5.5 5.5 0 0 1 11 0v.5a1 1 0 0 0 2 0zm9-5a7 7 0 0 0 -11.667-5.217 1 1 0 1 0 1.334 1.49 5 5 0 0 1 8.333 3.727 1 1 0 0 0 2 0zm-6.5-9a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm0-7a2.5 2.5 0 1 0 2.5 2.5 2.5 2.5 0 0 0 -2.5-2.5z"/></svg><svg class="_a" viewBox="0 0 24 24" width="18" height="18" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" style="display:none;"><path d="m7.5 13a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm6.5 11h-13a1 1 0 0 1 -1-1v-.5a7.5 7.5 0 0 1 15 0v.5a1 1 0 0 1 -1 1zm3.5-15a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm-1.421 2.021a6.825 6.825 0 0 0 -4.67 2.831 9.537 9.537 0 0 1 4.914 5.148h6.677a1 1 0 0 0 1-1v-.038a7.008 7.008 0 0 0 -7.921-6.941z"/></svg></i>
        <span><?php echo $this->translate("Following");?></span>
      </a>
    </li>
  </ul>
</div>

<script type="text/javascript">
  function setDataTick(type,obj){
    if(type == "member"){
      scriptJquery(".layout_eticktokclone_video_feed").hide();
      scriptJquery(".eticktokclone-member").addClass('active');
      scriptJquery(".eticktokclone-foryou").removeClass('active');
      scriptJquery(".layout_eticktokclone_browse_members").show();
      if(scriptJquery(".layout_eticktokclone_browse_members").find(".layout_eticktokclone_video_feed").find(".eticktokclone_videos_feed_container").find(".tip").length == 0){
        scriptJquery(".layout_eticktokclone_browse_members").find(".layout_eticktokclone_video_feed").show();
        if(scriptJquery(".layout_eticktokclone_browse_members").find(".eticktokclone_members_listing")){
          scriptJquery(".layout_eticktokclone_browse_members").find(".eticktokclone_members_listing").hide();
        }
      }else{
        scriptJquery(".layout_eticktokclone_browse_members").find(".layout_eticktokclone_video_feed").hide();
        if(scriptJquery(".layout_eticktokclone_browse_members").find(".eticktokclone_members_listing")){
          scriptJquery(".layout_eticktokclone_browse_members").find(".eticktokclone_members_listing").show();
        }
      }
      



    }else{
      scriptJquery(".eticktokclone-member").removeClass('active');
      scriptJquery(".eticktokclone-foryou").addClass('active');
      scriptJquery(".layout_eticktokclone_video_feed").show();
      scriptJquery(".layout_eticktokclone_browse_members").hide();
    }
  }
  scriptJquery(document).ready(function(){
    scriptJquery(".layout_eticktokclone_browse_members").hide();
  })
</script>
