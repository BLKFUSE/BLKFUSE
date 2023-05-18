<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<?php if( $this->form ): ?>
<div class="sesvideo_global_search">
  <?php echo $this->form->render($this) ?>
</div>
<?php endif ?>
<script type="text/javascript">
  
  function typevalue(value) {
    setCookie("sesvideo_commonsearch", value, 1);
  }
  en4.core.runonce.add(function() {
    setCookie("sesvideo_commonsearch", "", - 3600);
    AutocompleterRequestJSON('search', "<?php echo $this->url(array('module' => 'sesvideo', 'controller' => 'index', 'action' => 'search'), 'default', true) ?>", function(selecteditem) {
    })
  });
</script>
<script type="application/javascript">
    en4.core.runonce.add(function() {
    scriptJquery('#filter_form').submit(function(e){
      e.preventDefault();
        var searchType = scriptJquery('#type').val();
      if(searchType == 'video'){
        window.location.href = '<?php echo $_SERVER['REQUEST_URI']; ?>/browse?search='+scriptJquery('#search').val();
      }
      else if(searchType == 'sesvideo_chanel'){
        window.location.href = '<?php echo $_SERVER['REQUEST_URI']; ?>/channels?search='+scriptJquery('#search').val();
      }
      else if(searchType == 'sesvideo_artist'){
        window.location.href = '<?php echo $_SERVER['REQUEST_URI']; ?>/artists?title_name='+scriptJquery('#search').val();
      }
      else if(searchType == 'sesvideo_playlist'){
        window.location.href = '<?php echo $_SERVER['REQUEST_URI']; ?>/playlist?title_name='+scriptJquery('#search').val();
      }
    return true;
    }); 
});
</script>
