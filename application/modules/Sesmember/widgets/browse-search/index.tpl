<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?>
<?php
  /* Include the common user-end field switching javascript */
  echo $this->partial('_jsSwitch.tpl', 'fields', array(
    'topLevelId' => (int) @$this->topLevelId,
    'topLevelValue' => (int) @$this->topLevelValue
  ))
?>
<script type="text/javascript">
  en4.core.runonce.add(function () {
    window.addEvent('onChangeFields', function () {
      var firstSep = $$('li.browse-separator-wrapper')[0];
      var lastSep;
      var nextEl = firstSep;
      var allHidden = true;
      do {
	nextEl = nextEl.getNext();
	if (nextEl.get('class') == 'browse-separator-wrapper') {
	  lastSep = nextEl;
	  nextEl = false;
	} else {
	  allHidden = allHidden && (nextEl.getStyle('display') == 'none');
	}
      } while (nextEl);
      if (lastSep) {
	lastSep.setStyle('display', (allHidden ? 'none' : ''));
      }
    });
  });
</script>
<style>
  .hideE {
    display:none !important;
  }
</style>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<div class="sesmember_browse_search sesbasic_bxs sesbasic_clearfix <?php echo $this->view_type=='horizontal' ? 'sesmember_browse_search_horizontal' : 'sesmember_browse_search_vertical'; ?>"><?php echo $this->form->render($this) ?></div>
<script type="application/javascript">
  scriptJquery('#loadingimgsesmember-wrapper').hide();

  function showHideOptions<?php echo $this->identity; ?>(display){
    var elem = scriptJquery('.sesmember_widget_advsearch_hide_<?php echo $this->identity; ?>');
    if(elem.length == 0){
      scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').hide();
      hideFieldSetting();	
      return;
    }
    for(var i = 0 ; i < elem.length ; i++){
      if(scriptJquery(elem[i]).parent().prop('tagName') == 'LI')
      {
      scriptJquery(elem[i]).parent().css('display',display);
      }
      else
      scriptJquery(elem[i]).parent().parent().css('display',display);
    }
    hideFieldSetting();
  }
  function hideFieldSetting(){
    var hideField = scriptJquery('.field_toggle');
    var type = "add";
    for(i=0;i<hideField.length;i++) {
      if(scriptJquery(hideField[i]).attr("id") == "profile_type")
        continue;
      if(!scriptJquery('#profile_type-label').closest("li").hasClass("hideE")) {
        if(scriptJquery(hideField[i]).closest('ul').hasClass('form-options-wrapper'))
          scriptJquery(hideField[i]).parent().parent().parent().addClass('hideE');
        else 
          scriptJquery(hideField[i]).closest('li').addClass('hideE');
      }else {
        type = "remove";
        if(scriptJquery(hideField[i]).closest('ul').hasClass('form-options-wrapper'))
          scriptJquery(hideField[i]).parent().parent().parent().removeClass('hideE');
        else 
          scriptJquery(hideField[i]).closest('li').removeClass('hideE');
      }
    }
    if(type == "remove")
      scriptJquery('#profile_type').closest('li').removeClass('hideE');
    else
      scriptJquery('#profile_type').closest('li').addClass('hideE');
  }
  function checkSetting<?php echo $this->identity; ?>(first){
    var hideShowOption = scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').hasClass('active');
    if(hideShowOption){
      showHideOptions<?php echo $this->identity; ?>('none');
      if(typeof first == 'undefined'){
	      scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').html("<i class='fa fa-plus-circle'></i><?php echo $this->translate('Show Advanced Settings') ?>");
      }
      scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').removeClass('active');
    }else{
      showHideOptions<?php echo $this->identity; ?>('inline-block');
      scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').html("<i class='fa fa-minus-circle'></i><?php echo $this->translate('Hide Advanced Settings') ?>");
      scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').addClass('active');
    }	
  }
  scriptJquery(document).on('click','#advanced_options_search_<?php echo $this->identity; ?>',function(e){
    checkSetting<?php echo $this->identity; ?>();
  });
  en4.core.runonce.add(function () {
    scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').html("<i class='fa fa-plus-circle'></i><?php echo $this->translate('Show Advanced Settings') ?>");
    checkSetting<?php echo $this->identity; ?>('true');	
  })
</script>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $moduleName = $request->getModuleName();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName();?>
<?php

$viewer = Engine_Api::_()->user()->getViewer();
$viewer_id = $viewer->getIdentity();

?>

<?php if(($controllerName == 'index' && $actionName == 'all-results') || ($controllerName == 'index' && $actionName == 'browse') || ($controllerName == 'index' && $actionName == 'profiletype') || ($controllerName == 'index' && $actionName == 'pinborad-view-members') || ($moduleName == 'sesblog' && $controllerName == 'index' && $actionName == 'contributors')) { ?>
<?php if($actionName == 'all-results'):?>
<?php $pageName = 'advancedsearch_index_sesmember';?>
<?php elseif($actionName == 'pinborad-view-members'):?>
    <?php $pageName = 'sesmember_index_pinborad-view-members';?>
  <?php elseif($actionName == 'contributors'): ?>
    <?php $pageName = 'sesblog_index_contributors';?>
  <?php elseif($actionName == 'profiletype'): ?>
    <?php
      $homepage_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('homepage_id', 0);
      $pageName = "sesmember_index_$homepage_id";
    ?>
  <?php else:?>
    <?php $pageName = 'sesmember_index_browse';?>
  <?php endif;?>
  <?php $identity = Engine_Api::_()->sesbasic()->getIdentityWidget('sesmember.browse-members','widget',$pageName); ?>
  <?php if($identity):?>
    <script type="application/javascript">
        en4.core.runonce.add(function () {
	scriptJquery(document).on('submit','#filter_form',function(e){
	  //if(scriptJquery('.user_all_members').length > 0){
	    e.preventDefault();
	    loadMap_<?php echo $identity;?> = true;
	    is_search_<?php echo $identity; ?> = 1;
	    if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
	      scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $identity?>').css('display', 'block');
	      isSearch = true;
	      e.preventDefault();
	      searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
	      scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
	      paggingNumber<?php echo $identity; ?>(1);
	    }else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
	      scriptJquery('#browse-widget_<?php echo $identity; ?>').html('');
	      scriptJquery('#loading_image_<?php echo $identity; ?>').show();
	      isSearch = true;
	      e.preventDefault();
	      searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
	      page<?php echo $identity; ?> = 1;
        scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
	      viewMore_<?php echo $identity; ?>();
	    }
	 // }
	  return true;
	});	
      });
    </script>
  <?php endif;?>
<?php }else if($controllerName == 'index' && $actionName == 'nearest-member'){ ?>
	<?php $identity = Engine_Api::_()->sesbasic()->getIdentityWidget('sesmember.browse-members','widget','sesmember_index_nearest-member'); ?>
  <?php if($identity):?>
    <script type="application/javascript">
        en4.core.runonce.add(function () {
	scriptJquery('#filter_form').submit(function(e){
	  if(scriptJquery('.user_all_members').length > 0){
	    e.preventDefault();
	    loadMap_<?php echo $identity;?> = true;
	    is_search_<?php echo $identity; ?> = 1;
	    if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
	      scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $identity?>').css('display', 'block');
	      isSearch = true;
	      e.preventDefault();
	      searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
	      scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
	      paggingNumber<?php echo $identity; ?>(1);
	    }else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
	      scriptJquery('#browse-widget_<?php echo $identity; ?>').html('');
	      scriptJquery('#loading_image_<?php echo $identity; ?>').show();
	      isSearch = true;
	      e.preventDefault();
	      searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
	      page<?php echo $identity; ?> = 1;
	      scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
	      viewMore_<?php echo $identity; ?>();
	    }
	  }
	  return true;
	});	
      });
    </script>
  <?php endif;?>

<?php }else if($controllerName == 'index' && $actionName == 'locations'){?>
  <script type="application/javascript">
  scriptJquery(document).ready(function(){
    scriptJquery('#filter_form').submit(function(e){
      e.preventDefault();
      var error = false;
      scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
      e.preventDefault();
      searchParams = scriptJquery(this).serialize();
      scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
      callNewMarkersAjax();
      return true;
    });	
  });
  </script>
<?php } ?>
<script type="text/javascript">

  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('search_text', "<?php echo $this->url(array('module' =>'sesmember','controller' => 'index', 'action' => 'get-member'),'default',true); ?>", function(selecteditem) {
    })
  });

  en4.core.runonce.add(function () {
    mapLoad = false;
    if(scriptJquery('#lat-wrapper').length > 0 || scriptJquery('#locationSesList').length > 0){
      scriptJquery('#lat-wrapper').css('display' , 'none');
      scriptJquery('#lng-wrapper').css('display' , 'none');
      initializeSesMemberMapList();
    }
    scriptJquery('#loadingimgsesmember-wrapper').hide();
  });

  en4.core.runonce.add(function () {
var options = scriptJquery('#profile_type option');
var optionLength = options.size();
if(optionLength == 2) {
scriptJquery('#filter_form').find('#profile_type').parent().hide();
var value = scriptJquery('#filter_form').find('#profile_type option:eq(1)').attr('value');
scriptJquery('#filter_form').find('#profile_type').val(value);
changeFields('profile_type');
}
});
 </script>
