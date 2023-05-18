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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php if(!$this->widgetIdentity) { ?>
<div class="sesmember_browse_reviews_search sesbasic_bxs sesbasic_clearfix <?php echo $this->view_type == 'horizontal' ? 'sesmember_browse_review_search_horizontal' : 'sesmember_browse_review_search_vertical'; ?>">
<?php } ?>
<?php echo $this->form->render($this) ?>
<?php if(!$this->widgetIdentity) { ?>
	</div>
<?php } ?>
<script type="application/javascript">
  scriptJquery('#loadingimgsesmemberreview-wrapper').hide();
</script>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName();?>
<?php if($controllerName == 'review' && $actionName == 'browse'){ ?>
  <?php $identity = Engine_Api::_()->sesbasic()->getIdentityWidget('sesmember.browse-reviews','widget','sesmember_review_browse'); ?>
  <?php if($identity):?>
    <script type="application/javascript">
      scriptJquery(document).ready(function(){
	scriptJquery('#filter_form').submit(function(e){		
	  if(scriptJquery('.sesmember_review_listing').length > 0){
	    e.preventDefault();
	   // scriptJquery('#loadingimgsesmemberreview-wrapper').show();
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
	      scriptJquery('#sesmember_review_listing').html('');
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
<?php }else if($controllerName == 'profile' && $actionName == 'index'){?>
  <script type="application/javascript">
  scriptJquery(document).ready(function(){
    scriptJquery('#filter_form').submit(function(e){
      e.preventDefault();
      var error = false;
      searchParams = scriptJquery(this).serialize();
      //scriptJquery('#loadingimgsesmemberreview-wrapper').show();
     	<?php $identity = $this->widgetIdentity; ?>
			if(scriptJquery('.sesmember_review_listing').length > 0){
	    e.preventDefault();
	    //scriptJquery('#loadingimgsesmemberreview-wrapper').show();
	    loadMap_<?php echo $identity;?> = true;
	    is_search_<?php echo $identity; ?> = 1;
	    if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
	      scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $identity?>').css('display', 'block');
	      searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
	      scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
	      paggingNumber<?php echo $identity; ?>(1);
	    }else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
	      scriptJquery('#sesmember_review_listing').html('');
	      scriptJquery('#loading_image_<?php echo $identity; ?>').show();
	      searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
	      page<?php echo $identity; ?> = 1;
	      scriptJquery('#submit').html('<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />');
	      viewMore_<?php echo $identity; ?>();
	    }
	  }
	  return true;
			
      return true;
    });	
  });
  </script>
<?php } ?>
<script type="text/javascript">

  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('search_text', "<?php echo $this->url(array('module' =>'sesmember','controller' => 'index', 'action' => 'get-review'),'default',true); ?>", function(selecteditem) {
    })
  });

  scriptJquery('#loadingimgsesmemberreview-wrapper').hide();
 </script>
