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

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php if(!empty($this->form)): ?>
<div class="sesmember_browse_toprated_search sesbasic_bxs sesbasic_clearfix <?php echo $this->view_type=='horizontal' ? 'sesmember_browse_toprated_search_horizontal' : 'sesmember_browse_toprated_search_vertical'; ?>"><?php echo $this->form->render($this) ?></div>
<?php endif; ?>
<script type="application/javascript">
  scriptJquery('#loadingimgsesmember-wrapper').hide();

  function showHideOptions<?php echo $this->identity; ?>(display){
    var elem = scriptJquery('.sesmember_widget_advsearch_hide_<?php echo $this->identity; ?>');
    if(elem.length == 0){
      scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').hide();	
      return;
    }
    for(var i = 0 ; i < elem.length ; i++){console.log(scriptJquery(elem[i]).parent().prop('tagName'));
      if(scriptJquery(elem[i]).parent().prop('tagName') == 'LI')
      {
      scriptJquery(elem[i]).parent().css('display',display);
      }
      else
      scriptJquery(elem[i]).parent().parent().css('display',display);
    }
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
      showHideOptions<?php echo $this->identity; ?>('block');
      scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').html("<i class='fa fa-minus-circle'></i><?php echo $this->translate('Hide Advanced Settings') ?>");
      scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').addClass('active');
    }	
  }
  scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').click(function(e){
    checkSetting<?php echo $this->identity; ?>();
  });
  scriptJquery(document).ready(function(e){
    scriptJquery('#advanced_options_search_<?php echo $this->identity; ?>').html("<i class='fa fa-plus-circle'></i><?php echo $this->translate('Show Advanced Settings') ?>");
    checkSetting<?php echo $this->identity; ?>('true');	
  })
</script>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName();?>

<?php  if($controllerName == 'index' && $actionName == 'top-members'){ ?>
<?php $identity = Engine_Api::_()->sesbasic()->getIdentityWidget('sesmember.top-rated-members','widget','sesmember_index_top-members'); ?>
  <?php if($identity):?>
    <script type="application/javascript">
      scriptJquery(document).ready(function(){
	scriptJquery('#filter_form').submit(function(e){
	  if(scriptJquery('.sesmember_member_rating_block').length > 0){
	    e.preventDefault();
	     if(typeof viewMore_<?php echo $identity; ?> == 'function'){
	      scriptJquery('.sesmember_member_rating_block').html('');
	      scriptJquery('#loading_image_<?php echo $identity; ?>').show();
	      searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
	      page<?php echo $identity; ?> = 1;
         scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
	      viewMore_<?php echo $identity; ?>();
	    }else if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
	      scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $identity?>').css('display', 'block');
	      e.preventDefault();
	      searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
         scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
	      paggingNumber<?php echo $identity; ?>(1);
	    }
	  }
	  return true;
	});	
      });
    </script>
    <?php endif;?>
<?php } ?>
<script type="text/javascript">
  var Searchurl = "<?php echo $this->url(array('module' =>'sesmember','controller' => 'index', 'action' => 'get-member'),'default',true); ?>";
  
  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('search_text', Searchurl, function(selecteditem) {
    })
  });

  scriptJquery(document).ready(function(){
    mapLoad = false;
    if(scriptJquery('#lat-wrapper').length > 0 || scriptJquery('#locationSesList').length > 0){
      scriptJquery('#lat-wrapper').css('display' , 'none');
      scriptJquery('#lng-wrapper').css('display' , 'none');
      initializeSesMemberMapList();
    }
  });
  scriptJquery('#loadingimgsesmember-wrapper').hide();
 </script>
