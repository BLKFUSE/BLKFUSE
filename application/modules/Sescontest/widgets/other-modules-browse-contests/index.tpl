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

<?php if(empty($this->is_ajax) && $this->canCreate){ ?>
  <div class="sesbasic_profile_tabs_top sesbasic_clearfix">
     <?php
      echo $this->htmlLink(array(
        'route' => 'sescontest_general',
        'controller' => 'index',
        'action' => 'create',
        'resource_id' => $this->resource_id,
        'resource_type' => $this->resource_type,
        'widget_id' => $this->identity,
        ), $this->translate('Add New Contests'), array(
        'class' => 'sesbasic_button sesbasic_icon_add'
      ));
     ?>
   </div>
<?php } ?>
<?php include APPLICATION_PATH . '/application/modules/Sescontest/views/scripts/_showContestListGrid.tpl'; ?>