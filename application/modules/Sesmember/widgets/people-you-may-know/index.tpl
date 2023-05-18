<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $widgetName = 'peopleyoumayknow';?>
<?php if($this->view_type == 'list'): ?>
  <ul class="sesbasic_sidebar_block sesmember_side_block sesbasic_bxs sesbasic_clearfix">
<?php else: ?>
  <ul class="sesmember_side_block sesbasic_bxs sesbasic_clearfix">
<?php endif; ?>
  <?php include APPLICATION_PATH . '/application/modules/Sesmember/views/scripts/_sidebarWidgetData.tpl'; ?>
</ul>
