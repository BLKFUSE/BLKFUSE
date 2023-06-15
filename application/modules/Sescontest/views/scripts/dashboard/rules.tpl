<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: rules.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(!$this->is_ajax):?>
  <?php echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array('contest' => $this->contest));?>
  <div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
<?php endif;?>
<?php  echo $this->partial('dashboard/contest_expire.tpl', 'sescontest', array('contest' => $this->contest));?>
<div class="sesbasic_dashboard_form sescontest_db_rule_form">
  <?php echo $this->form->render() ?>
</div>
<?php if(!$this->is_ajax){ ?>
  </div>
</div>
</div>
<?php } ?>
<?php if($this->is_ajax) die; ?>