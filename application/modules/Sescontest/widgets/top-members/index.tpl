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

<?php 
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css');
?>
<?php $randonNumber = $this->widgetId;?>
<?php if(!$this->is_ajax){ ?>

  <?php if($this->params['placement_type'] == 'extended' && $this->params['viewType'] == '2'){ ?>
  	<div class="sescontest_members_clst_view_wrapper">
  		<h3 class="sescontest_weltitle"><span><?php echo $this->translate("Tap into a fast-growing community of millions of enthusiast participants of all levels from over the world!");?></span></h3>
      	<div class="sescontest_members_clst_view_tabs">
  <?php }else { ?>
    <!--Default Tabs-->
    <?php if($this->params['tabOption'] == 'default'){ ?>
      <div class="layout_core_container_tabs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
        <div class="tabs_alt tabs_parent" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
    <?php } ?>
    <!--Advance Tabs-->
    <?php if($this->params['tabOption'] == 'advance'){ ?>
      <div class="sesbasic_tabs_container sesbasic_clearfix sesbasic_bxs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
        <div class="sesbasic_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?> >
     <?php  } ?>
    <?php if($this->params['tabOption'] == 'filter'){ ?>
      <div class="sesbasic_filter_tabs_container sesbasic_clearfix sesbasic_bxs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?>>
        <div class="sesbasic_filter_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
    <?php } ?>
    <?php if($this->params['tabOption'] == 'vertical'){ ?>
      <div class="sesbasic_v_tabs_container sesbasic_clearfix sesbasic_bxs" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="border-width:0;" <?php } ?> >
        <div class="sesbasic_v_tabs sesbasic_clearfix" <?php if(engine_count($this->defaultOptions) ==1){ ?> style="display:none" <?php } ?>>
    <?php } ?>
  <?php } ?>

  
  <ul id="tab-widget-sescontest-<?php echo $randonNumber; ?>">
    <?php $defaultOptionArray = array();?>
    <?php foreach($this->defaultOptions as $key=>$valueOptions):?> 
    <?php $defaultOptionArray[] = $key;?>
     <li <?php if($this->defaultOpenTab == $key){ ?> class="active"<?php } ?> id="sesTabContainer_<?php echo $randonNumber; ?>_<?php echo $key; ?>">
       <a href="javascript:;" data-src="<?php echo $key; ?>" onclick="changeTabSes_<?php echo $randonNumber; ?>('<?php echo $key; ?>')"><?php echo $this->translate(($valueOptions)); ?></a>
     </li>
     <?php endforeach; ?>
    </ul>
  </div>
  <div class="sesbasic_tabs_content sesbasic_clearfix">
<?php  } ?>
<?php include APPLICATION_PATH . '/application/modules/Sescontest/views/scripts/_showMembers.tpl'; ?>
<?php if(!$this->is_ajax){ ?>
    </div>
  </div>
  <script type="application/javascript"> 
    var availableTabs_<?php echo $randonNumber; ?>;
    var requestTab_<?php echo $randonNumber; ?>;
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
	
//         if(typeof(requestTab_<?php echo $randonNumber; ?>) != 'undefined')
//         requestTab_<?php echo $randonNumber; ?>.cancel();
// 
//         if(typeof(requestViewMore_<?php echo $randonNumber; ?>) != 'undefined')
//         requestViewMore_<?php echo $randonNumber; ?>.cancel();
	
        defaultOpenTab = valueTab;
        requestTab_<?php echo $randonNumber; ?> = scriptJquery.ajax({
          method: 'post',
          'url': en4.core.baseUrl+"widget/index/mod/sescontest/name/<?php echo $this->widgetName; ?>/openTab/"+valueTab,
          'data': {
            format: 'html',  
            is_ajax : 1,
            height:'<?php echo $this->height;?>',
            widget_id:'<?php echo $randonNumber;?>',
            type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
          },
          success: function(responseHTML) {
            if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
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
            document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = responseHTML;
            scriptJquery('.sesbasic_view_more_loading_<?php echo $randonNumber;?>').hide();
            if(typeof viewMoreHide_<?php echo $randonNumber; ?> == 'function')
            viewMoreHide_<?php echo $randonNumber; ?>();
          }
        });
        
        return false;			
      }
    }
  </script> 
<?php } ?>
