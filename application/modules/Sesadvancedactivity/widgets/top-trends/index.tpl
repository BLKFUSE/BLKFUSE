<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<?php 
  $staticBaseUrl = $this->layout()->staticBaseUrl;
  $this->headScript()->appendFile($staticBaseUrl 
      . 'application/modules/Sesbasic/externals/scripts/jquery.tooltip.js');
  $this->headScript()->appendFile($staticBaseUrl 
      . 'application/modules/Sesbasic/externals/scripts/tooltip.js');
  $this->headScript()->appendFile($staticBaseUrl
      . 'application/modules/Sesadvancedactivity/externals/scripts/core.js');

  $this->headLink()->appendStylesheet($staticBaseUrl
      . 'application/modules/Sesadvancedactivity/externals/styles/styles.css');

?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/styles/styles.css'); ?>
<ul class="sesbasic_sidebar_block sesact_trends_block sesbasic_bxs sesbasic_clearfix">
  <?php foreach($this->trends as $trend){ ?>
    <?php if(!empty($trend->title)) { ?>
    <li class="sesbasic_clearfix">
	    <a href="hashtag?hashtag=<?php echo $trend->title; ?>">#<?php echo $trend->title; ?></a>
      <span class="sesbasic_text_light"><?php echo $this->translate(array('%s people talking about this.', '%s peoples talking about this.', $trend->total), $this->locale()->toNumber($trend->total))?></span>
    </li>
    <?php } ?>
  <?php } ?>
</ul>
