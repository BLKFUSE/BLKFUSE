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
<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)):?>
	<?php $randonNumber = $this->identityForWidget;?>
<?php else:?>
	<?php $randonNumber = $this->identity;?>
<?php endif;?>

<?php if(!$this->is_ajax){ ?>
  <div class="sesnews_small_tabs_container sesbasic_clearfix sesbasic_bxs">
    <div class="sesnews_small_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>> 
    	<ul id="tab-widget-sesnews-<?php echo $randonNumber; ?>" class="sesbasic_clearfix">
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

<?php include APPLICATION_PATH . '/application/modules/Sesnews/views/scripts/_sidebartabbedlist.tpl'; ?>

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
	
	if(document.getElementById('sidebar-tabbed-widget_<?php echo $randonNumber; ?>'))
	document.getElementById('sidebar-tabbed-widget_<?php echo $randonNumber; ?>').innerHTML ='';
	
	scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
	document.getElementById('sidebar-tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = '<div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="margin-top:30px;"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" /> </div>';
	
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
	    identity : '<?php echo $randonNumber; ?>',
	    height:'<?php echo $this->height;?>',
      type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
    },
    success: function(responseHTML) {
      if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
        scriptJquery('#sidebar-tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
      } else {
        scriptJquery('#sidebar-tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
      }
	    scriptJquery('#map-data_<?php echo $randonNumber;?>').removeClass('checked');
	    
	    if(scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').length)
	    scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display','none');
	    else
	    scriptJquery('#loading_image_<?php echo $randonNumber; ?>').hide();
	      
	    scriptJquery('#error-message_<?php echo $randonNumber;?>').remove();
	    var check = true;
	    if(document.getElementById('sidebar-tabbed-widget_<?php echo $randonNumber; ?>'))
	    document.getElementById('sidebar-tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = responseHTML;
	    oldMapData_<?php echo $randonNumber; ?> = [];
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
