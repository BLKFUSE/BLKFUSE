<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
?>

<?php
$allParams = $this->allParams;
/* Include the common user-end field switching javascript */
echo $this->partial('_jsSwitch.tpl', 'fields', array(
    'topLevelId' => (int) $this->topLevelId,
    'topLevelValue' => (int) $this->topLevelValue
))
?>
<div class="edating_browse_search_form <?php if(engine_in_array('vertical', $allParams)) { ?> edating_vertical_view <?php } else { ?> edating_horizontal_view <?php } ?>">
    <?php echo $this->form->setAction($this->url(array(), 'edating_general', true))->render($this); ?>
</div>
<script type="text/javascript">
en4.core.runonce.add(function () {
  scriptJquery(window).on('onChangeFields', function () {
    var firstSep = scriptJquery('li.browse-separator-wrapper');
    var lastSep;
    var nextEl = firstSep;
    var allHidden = true;
    do {
        nextEl = nextEl.next();
        if (nextEl.hasClass('browse-separator-wrapper')) {
            lastSep = nextEl;
            nextEl = false;
        } else {
            allHidden = allHidden && (nextEl.css('display') == 'none');
        }
    } while (nextEl.length);
    if (lastSep.length) {
        lastSep.css('display', (allHidden ? 'none' : ''));
    }
  });
});
</script>
