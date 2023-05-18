<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: message.tpl 2015-07-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php
  $to = Zend_Controller_Front::getInstance()->getRequest()->getParam('to', 0);
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl."externals/selectize/css/normalize.css");
  $headScript = new Zend_View_Helper_HeadScript();
  $headScript->appendFile($this->layout()->staticBaseUrl.'externals/selectize/js/selectize.js');
?>
<script type="text/javascript">
  var maxRecipients = <?php echo sprintf("%d", $this->maxRecipients) ?> || 10;
  en4.core.runonce.add(function() {
    scriptJquery('#to').selectize({
      maxItems: maxRecipients,
      valueField: 'id',
      labelField: 'label',
      searchField: 'label',
      //create: true,
      load: function(query, callback) {
          if (!query.length) return callback();
          scriptJquery.ajax({
            url: '<?php echo $this->url(array('module' => 'user', 'controller' => 'friends', 'action' => 'suggest','message' => true), 'default', true) ?>',
            data: { 
              value: query 
            },
            success: function (transformed) {
              callback(transformed);
            },
            error: function () {
                callback([]);
            }
          });
      }
    });
  });
</script>
<div class="sesbsic_popup_form">
  <?php echo $this->form->render($this) ?>
</div>
