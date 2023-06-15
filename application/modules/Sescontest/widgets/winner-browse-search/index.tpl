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

<?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
<?php $controllerName = $request->getControllerName();?>
<?php $actionName = $request->getActionName();?>
<?php if(!isset($_GET['contest_id']) && $actionName == 'entries'):?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<?php endif;?>

<div class="sesbasic_clearfix sesbasic_bxs sescontest_browse_search <?php echo $this->view_type=='horizontal' ? 'sescontest_browse_search_horizontal' : 'sescontest_browse_search_vertical'; ?>">
  <?php echo $this->form->render($this) ?>
</div>

<?php $class = '.sescontest_winners_list';?>
<?php if($actionName == 'winner'):?>
  <?php $pageName = 'sescontest_index_winner';?>
  <?php $widgetName = 'sescontest.winners-listing';?>
<?php elseif($actionName == 'entries'):?>
  <?php $pageName = 'sescontest_index_entries';?>
  <?php $widgetName = 'sescontest.browse-entries';?>
<?php endif;?>
<?php $identity = Engine_Api::_()->sesbasic()->getIdentityWidget($widgetName,'widget',$pageName); ?>

<script type="application/javascript">
  scriptJquery(document).ready(function(){
    scriptJquery('#filter_form').submit(function(e){
      e.preventDefault();
      if(scriptJquery('<?php echo $class;?>').length > 0){
        scriptJquery('#tabbed-widget_<?php echo $identity; ?>').html('');
        //scriptJquery('#loading_image_<?php echo $identity; ?>').show();
        scriptJquery('#loadingimgsescontest-wrapper').show();
        is_search_<?php echo $identity; ?> = 1;
        if(typeof paggingNumber<?php echo $identity; ?> == 'function'){
          isSearch = true;
          e.preventDefault();
          searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
          paggingNumber<?php echo $identity; ?>(1);
        }else if(typeof viewMore_<?php echo $identity; ?> == 'function'){
          isSearch = true;
          e.preventDefault();
          searchParams<?php echo $identity; ?> = scriptJquery(this).serialize();
          page<?php echo $identity; ?> = 1;
          viewMore_<?php echo $identity; ?>();
        }
      }
      return true;
    });	
  });
</script>

<?php if(!isset($_GET['contest_id']) && $actionName == 'entries'):?>
  <script type='text/javascript'>
    var Searchurl = "<?php echo $this->url(array('module' =>'sescontest','controller' => 'ajax', 'action' => 'get-contest'),'default',true); ?>";
    
    en4.core.runonce.add(function() {
      AutocompleterRequestJSON('search', Searchurl, function(selecteditem) {
        //window.location.href = selecteditem.url;
      });
    });
  </script>
<?php endif;?>
