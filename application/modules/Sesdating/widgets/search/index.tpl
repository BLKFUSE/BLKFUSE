<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php
  $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js');
?>
<div class="header_searchbox">
<!--  <form id="global_search_form" method="get">-->
    <input placeholder="<?php echo $this->translate("Search"); ?>" id="text_search" type="text" name="query" />
    <button onclick="javascript:showAllSearchResults();"><i class="fa fa-search"></i></button>
<!--  </form>-->
</div>
<script>
  function showAllSearchResults() {
    var text_search = document.getElementById('text_search').value;
    if(text_search) { 
      window.location.href= '<?php echo $this->url(array("controller" => "search"), "default", true); ?>' + "?query=" + document.getElementById('text_search').value;
    } else {
      return false;
    }
  }
  
  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('text_search', "<?php echo $this->url(array('module' => 'sesdating', 'controller' => 'index', 'action' => 'search'), 'default', true) ?>", function(selecteditem) {
      window.location.href = selecteditem.url;
    })
  });
  
  scriptJquery(document).ready(function() {
    scriptJquery('#text_search').keydown(function(e) {
      if (e.which === 13) {
        showAllSearchResults();
      }
    });
  });
</script>
