<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/styles.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<?php if( $this->form ): ?>
<div class="sesmusic_global_search">
  <?php echo $this->form->render($this) ?>
</div>
<?php endif ?>
<script type="text/javascript">
  
  function typevalue(value) {    
    setMusicCookie("sesmusic_commonsearch", value, 1);
  }
  
  en4.core.runonce.add(function() {
    setMusicCookie("sesmusic_commonsearch", "", - 3600);
    AutocompleterRequestJSON('title', "<?php echo $this->url(array('module' => 'sesmusic', 'controller' => 'index', 'action' => 'search'), 'default', true) ?>", function(selecteditem) {
      window.location.href = selecteditem.url;
    });
  });
</script>
