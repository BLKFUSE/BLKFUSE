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
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php if(!$this->widgetIdentity) { ?>
	<div class="sesnews_browse_reviews_search sesbasic_bxs sesbasic_clearfix <?php echo $this->view_type == 'horizontal' ? 'sesnews_browse_review_search_horizontal' : 'sesnews_browse_review_search_vertical'; ?>">
<?php } ?>
<?php echo $this->form->render($this) ?>
<?php if(!$this->widgetIdentity) { ?>
	</div>
<?php } ?>
<script type="application/javascript">
  scriptJquery('#loadingimgsesnewsreview-wrapper').hide();
</script>
<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName();?>
<?php if($controllerName == 'review' && $actionName == 'browse'){ ?>
  <?php $identity = Engine_Api::_()->sesbasic()->getIdentityWidget('sesnews.browse-reviews','widget','sesnews_review_browse'); ?>
	<?php if($identity):?>
		<script type="application/javascript">
			scriptJquery(document).ready(function(){
				scriptJquery('#filter_form_review').submit(function(e){	
					if(scriptJquery('.sesnews_review_listing').length > 0){
						e.preventDefault();
						scriptJquery('#loadingimgsesnewsreview-wrapper').show();
						is_search_<?php echo $identity; ?> = 1;
						if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
							scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $identity?>').css('display', 'block');
							isSearch = true;
							e.preventDefault();
							searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
							scriptJquery('#loadingimgsesnewsreview-wrapper').show();
							paggingNumber<?php echo $identity; ?>(1);
						}else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
							scriptJquery('#sesnews_review_listing').html('');
							scriptJquery('#loading_image_<?php echo $identity; ?>').show();
							isSearch = true;
							e.preventDefault();
							searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
							page<?php echo $identity; ?> = 1;
							scriptJquery('#loadingimgsesnewsreview-wrapper').show();
							viewMore_<?php echo $identity; ?>();
						}
					}
					return true;
				});	
			});
		</script>
	<?php endif;?>
<?php }else if($controllerName == 'index' && $actionName == 'view'){?>
	<script type="application/javascript">
		scriptJquery(document).ready(function(){
			scriptJquery('#filter_form_review').submit(function(e){
				e.preventDefault();
				var error = false;
				searchParams = scriptJquery(this).serialize();
				scriptJquery('#loadingimgsesnewsreview-wrapper').show();
				<?php $identity = $this->widgetIdentity; ?>
				if(scriptJquery('.sesnews_review_listing').length > 0){	
					e.preventDefault();
					scriptJquery('#loadingimgsesnewsreview-wrapper').show();
					is_search_<?php echo $identity; ?> = 1;
					if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
						scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $identity?>').css('display', 'block');
						searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
						scriptJquery('#loadingimgsesnewsreview-wrapper').show();
						paggingNumber<?php echo $identity; ?>(1);
					}else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
						scriptJquery('#sesnews_review_listing').html('');
						scriptJquery('#loading_image_<?php echo $identity; ?>').show();
						searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
						page<?php echo $identity; ?> = 1;
						scriptJquery('#loadingimgsesnewsreview-wrapper').show();
						viewMore_<?php echo $identity; ?>();
					}
				}
				return true;
			});	
	  });
	</script>
<?php } ?>
<script type="text/javascript">
	var Searchurl = "<?php echo $this->url(array('module' =>'sesnews','controller' => 'index', 'action' => 'get-review'),'default',true); ?>";
	en4.core.runonce.add(function() {
    AutocompleterRequestJSON('search_text', Searchurl, function(selecteditem) {
      //window.location.href = selecteditem.url;
    })
  });
	scriptJquery('#loadingimgsesnewsreview-wrapper').hide();
</script>
