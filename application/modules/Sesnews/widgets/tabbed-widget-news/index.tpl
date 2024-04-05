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

<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)):?>
	<?php $randonNumber = $this->identityForWidget;?>
<?php else:?>
	<?php $randonNumber = $this->identity;?>
<?php endif;?>

<?php if($this->profile_news == true):?>
  <?php $moduleName = 'sesnews';?>
<?php else:?>
  <?php $moduleName = 'sesnews';?>
<?php endif;?>



<?php if(!$this->is_ajax){ ?>
<!--Default Tabs-->
<?php if($this->tab_option == 'default'){ ?>
<div class="layout_core_container_tabs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
<div class="tabs_alt tabs_parent" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
<?php } ?>
<!--Advance Tabs-->
<?php if($this->tab_option == 'advance'){ ?>
<div class="sesbasic_tabs_container sesbasic_clearfix sesbasic_bxs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
  <div class="sesbasic_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
 <?php } ?>
<!--Filter Tabs-->
<?php if($this->tab_option == 'filter'){ ?>
<div class="sesbasic_filter_tabs_container sesbasic_clearfix sesbasic_bxs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
  <div class="sesbasic_filter_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
<?php } ?>
<!--Vertical Tabs-->
<?php if($this->tab_option == 'vertical'){ ?>
<div class="sesbasic_v_tabs_container sesbasic_clearfix sesbasic_bxs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
  <div class="sesbasic_v_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
<?php } ?>
    <ul id="tab-widget-sesnews-<?php echo $randonNumber; ?>">
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
    </div>
  <div class="sesbasic_tabs_content sesbasic_clearfix">
<?php } ?>

<?php include APPLICATION_PATH . '/application/modules/Sesnews/views/scripts/_showNewsListGrid.tpl'; ?>

<?php if(!$this->is_ajax){ ?>
    </div>
  </div>
<?php } ?>

<?php if(!$this->is_ajax):?>
  <script type="application/javascript"> 
    var availableTabs_<?php echo $randonNumber; ?>;
     
    <?php if(isset($defaultOptionArray)){ ?>
      availableTabs_<?php echo $randonNumber; ?> = <?php echo json_encode($defaultOptionArray); ?>;
    <?php  } ?>
    var defaultOpenTab ;
    function changeTabSes_<?php echo $randonNumber; ?>(valueTab){
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
      if(valueTab){
	if(document.getElementById("error-message_<?php echo $randonNumber;?>"))
	document.getElementById("error-message_<?php echo $randonNumber;?>").style.display = 'none';
	
	if(document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>'))
	document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML ='';
	
	scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
	document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = '<div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" /> </div>';
	
// 	if(typeof(requestTab_<?php echo $randonNumber; ?>) != 'undefined')
// 	requestTab_<?php echo $randonNumber; ?>.cancel();
// 	
// 	if(typeof(requestViewMore_<?php echo $randonNumber; ?>) != 'undefined')
// 	requestViewMore_<?php echo $randonNumber; ?>.cancel();
	
	defaultOpenTab = valueTab;
	requestTab_<?php echo $randonNumber; ?> = scriptJquery.ajax({
    dataType: 'html',
	  method: 'post',
	  'url': en4.core.baseUrl+"widget/index/mod/sesnews/name/<?php echo $this->widgetName; ?>/openTab/"+valueTab,
	  'data': {
	    format: 'html',  
	    params : params<?php echo $randonNumber; ?>, 
	    is_ajax : 1,
	    searchParams:searchParams<?php echo $randonNumber; ?> ,
	    identity : '<?php echo $randonNumber; ?>',
	    height:'<?php echo $this->height;?>',
      type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
    },
    success: function(responseHTML) {
      if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
      } else {
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
      }
	    scriptJquery('#map-data_<?php echo $randonNumber;?>').removeClass('checked');
	    
	    if(scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').length)
	    scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display','none');
	    else
	    scriptJquery('#loading_image_<?php echo $randonNumber; ?>').hide();
	      
	    scriptJquery('#error-message_<?php echo $randonNumber;?>').remove();
	    var check = true;
	    if(document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>'))
	    scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').html(responseHTML);
	    oldMapData_<?php echo $randonNumber; ?> = [];
	    if(document.getElementById('map-data_<?php echo $randonNumber;?>') && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber;?>').find('.active').attr('rel') == 'map'){
	      var mapData = scriptJquery.parseJSON(document.getElementById('map-data_<?php echo $randonNumber;?>').innerHTML);
	      if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
		oldMapData_<?php echo $randonNumber; ?> = [];
		newMapData_<?php echo $randonNumber ?> = mapData;
		loadMap_<?php echo $randonNumber ?> = true;
		scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, newMapData_<?php echo $randonNumber ?>);
		initialize_<?php echo $randonNumber?>();	
		mapFunction_<?php echo $randonNumber?>();
	      }
	      else {
		scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
		initialize_<?php echo $randonNumber?>();	
	      }
	    }
	    if(document.getElementById('sesnews_pinboard_view_<?php echo $randonNumber;?>') && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel') == 'pinboard') {
	      if(document.getElementById('sesnews_pinboard_view_<?php echo $randonNumber;?>'))
	      document.getElementById('sesnews_pinboard_view_<?php echo $randonNumber;?>').style.display = 'block';
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
