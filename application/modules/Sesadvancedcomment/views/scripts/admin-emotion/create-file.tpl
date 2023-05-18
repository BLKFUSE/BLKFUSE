<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: create-file.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/ses-scripts/jscolor/jscolor.js');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.min.js');
?>
<script>
hashSign = '#';
</script>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>

<script type="text/javascript">
var contentAutocomplete =  'tags';
  
  en4.core.runonce.add(function() {
    var cache = {};
    scriptJquery('#tags').autocomplete({
      source: function (request, response) { 
        if(cache[request.term]){
           response(cache[request.term]);
        } else {
          scriptJquery.ajax({
            url: '<?php echo $this->url(array('controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>',
            data: { text: request.term },
            success: function (transformed) {
              response(transformed);
              cache[request.term] = transformed;
            },
            error: function () {
                response([]);
            }
          });
        }
      },
      select: function(event, ui) { 
      },
    });
  });
  
</script>
<div class='settings sesbasic_popup_form'>
  <?php echo $this->form->render($this); ?>
</div>
