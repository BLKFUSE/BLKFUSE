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

<?php if( $this->form ): ?>
  <div class="sesbasic_browse_search">
    <?php echo $this->form->render($this) ?>
  </div>
<?php endif; ?>

<script type="text/javascript">
var title_name = document.getElementById("title_name");
title_name.addEventListener("keydown", function (e) {
    if (e.keyCode === 13) {  //checks whether the pressed key is "Enter"
        this.form.submit();
    }
});
</script>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<script type="text/javascript">
  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('title_name', "<?php echo $this->url(array('module' => 'sesvideo', 'controller' => 'index', 'action' => 'search', 'actonType' => 'browse', 'sesvideo_commonsearch' => 'sesvideo_artist'), 'default', true) ?>", function(selecteditem) {
    window.location.href = selecteditem.url;
    })
  });
</script>
