<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $randonNumber = $this->widgetId;?>
<?php if(!$this->is_ajax){ ?>
<!--Default Tabs-->
<?php if($this->params['tabOption'] == 'default'){ ?>
  <div class="layout_core_container_tabs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
  	<div class="tabs_alt tabs_parent" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
<?php } ?>
<!--Advance Tabs-->
<?php  if($this->params['tabOption'] == 'advance'){ ?>
  <div class="sesbasic_tabs_container sesbasic_clearfix sesbasic_bxs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
    <div class="sesbasic_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
 <?php  } ?>
<?php if($this->params['tabOption'] == 'filter'){ ?>
  <div class="sesbasic_filter_tabs_container sesbasic_clearfix sesbasic_bxs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
    <div class="sesbasic_filter_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
<?php } ?>
<?php if($this->params['tabOption'] == 'vertical'){ ?>
  <div class="sesbasic_v_tabs_container sesbasic_clearfix sesbasic_bxs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
    <div class="sesbasic_v_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
<?php } ?>
<?php if($this->params['tabOption'] == 'select' && engine_count($this->defaultOptions) > 1){ ?>
  <div class="sesbasic_select_tabs_container sesbasic_clearfix sesbasic_bxs">
  	<div class="sesbasic_select_tabs">
<p>
      <span><?php echo $this->translate("Sort By: ") ?></span>  
      <span>
        <select onchange="changeTabSes_<?php echo $randonNumber; ?>(this.value)" id="selected_optn_<?php echo $randonNumber; ?>">
         <?php 
         $defaultOptionArray = array();
         foreach($this->defaultOptions as $key=>$valueOptions){ 
         $defaultOptionArray[] = $key;
       ?>
         <option value="<?php echo $key; ?>"><?php echo $this->translate(($valueOptions)); ?></option>
       <?php } ?>
       </select>
     </span>
   </p>
   <?php } else { ?>
<ul id="tab-widget-sescontest-<?php echo $randonNumber; ?>">
   <?php 
     $defaultOptionArray = array();
     foreach($this->defaultOptions as $key=>$valueOptions){ 
     $defaultOptionArray[] = $key;
   ?>
   <li <?php if($this->defaultOpenTab == $key){ ?> class="active"<?php } ?> id="sesTabContainer_<?php echo $randonNumber; ?>_<?php echo $key; ?>">
     <a href="javascript:;" data-src="<?php echo $key; ?>" onclick="changeTabSes_<?php echo $randonNumber; ?>('<?php echo $key; ?>')"><?php echo $this->translate(($valueOptions)); ?></a>
   </li>
   <?php } ?>
  </ul>
  	
   <?php } ?>
</div>
  <div class="sesbasic_tabs_content sesbasic_clearfix">
<?php  } ?>
<?php include APPLICATION_PATH . '/application/modules/Sescontest/views/scripts/_showContestListGrid.tpl'; ?>
<?php if(!$this->is_ajax){ ?>
    </div>
  </div>
<?php } ?>

<?php if(!$this->is_ajax):?>
  <script type="application/javascript"> 
    var availableTabs_<?php echo $randonNumber; ?>;
    var requestTab_<?php echo $randonNumber; ?>;
    <?php if(isset($defaultOptionArray)){ ?>
      availableTabs_<?php echo $randonNumber; ?> = <?php echo json_encode($defaultOptionArray); ?>;
    <?php  } ?>
    var defaultOpenTab ;
    function changeTabSes_<?php echo $randonNumber; ?>(valueTab){
      if(scriptJquery('#selected_optn_<?php echo $randonNumber; ?>').length == 0){
        if(scriptJquery("#sesTabContainer_<?php echo $randonNumber; ?>_"+valueTab).hasClass('active'))
        return;
        var id = '_<?php echo $randonNumber; ?>';
        var length = availableTabs_<?php echo $randonNumber; ?>.length;
        for (var i = 0; i < length; i++){
          if(availableTabs_<?php echo $randonNumber; ?>[i] == valueTab){
            scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).addClass('active');
            scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).addClass('sesbasic_tab_selected');
          }else{
            scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).removeClass('sesbasic_tab_selected');
            scriptJquery('#sesTabContainer'+id+'_'+availableTabs_<?php echo $randonNumber; ?>[i]).removeClass('active');
          }
        }
      }
      if(valueTab){
        if(document.getElementById("error-message_<?php echo $randonNumber;?>"))
        document.getElementById("error-message_<?php echo $randonNumber;?>").style.display = 'none';
	
        if(document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>'))
        document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML ='';
	
	scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
	document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = '<div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="width:100%;"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" /> </div>';
	
// 	if(typeof(requestTab_<?php echo $randonNumber; ?>) != 'undefined')
// 	requestTab_<?php echo $randonNumber; ?>.cancel();
// 	
// 	if(typeof(requestViewMore_<?php echo $randonNumber; ?>) != 'undefined')
// 	requestViewMore_<?php echo $randonNumber; ?>.cancel();
	
	defaultOpenTab = valueTab;
	requestTab_<?php echo $randonNumber; ?> = scriptJquery.ajax({
	  method: 'post',
	  'url': en4.core.baseUrl+"widget/index/mod/sescontest/name/<?php echo $this->widgetName; ?>/openTab/"+valueTab,
	  'data': {
	    format: 'html',  
	    params : params<?php echo $randonNumber; ?>, 
	    is_ajax : 1,
	    searchParams:searchParams<?php echo $randonNumber; ?> ,
	    identity : '<?php echo $randonNumber; ?>',
	    height:'<?php echo $this->height;?>',
        widget_id: '<?php echo $this->widgetId;?>',
      type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
    },
    success: function(responseHTML) {
      if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid' || 'advgrid') {
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
      } else {
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
      }
	    if(scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').length)
	    scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display','none');
	    else
	    scriptJquery('#loading_image_<?php echo $randonNumber; ?>').hide();
	      
	    scriptJquery('#error-message_<?php echo $randonNumber;?>').remove();
	    var check = true;
	    if(document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>'))
	    scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').html(responseHTML);
	    if(document.getElementById('sescontest_pinboard_view_<?php echo $randonNumber;?>') && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel') == 'pinboard') {
	      if(document.getElementById('sescontest_pinboard_view_<?php echo $randonNumber;?>'))
	      document.getElementById('sescontest_pinboard_view_<?php echo $randonNumber;?>').style.display = 'block';
	      pinboardLayout_<?php echo $randonNumber ?>('force','true');
	    }
	    scriptJquery('.sesbasic_view_more_loading_<?php echo $randonNumber;?>').hide();
	    if(typeof viewMoreHide_<?php echo $randonNumber; ?> == 'function')
	    viewMoreHide_<?php echo $randonNumber; ?>();
	  }
	});
	
	return false;			
      }
    }
  </script> 
<?php endif;?>
