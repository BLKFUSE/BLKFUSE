<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: award.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(!$this->is_ajax):?>
  <?php echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array('contest' => $this->contest));?>
  <div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
<?php endif;?>
<?php  echo $this->partial('dashboard/contest_expire.tpl', 'sescontest', array('contest' => $this->contest));?>
<div class="sesbasic_dashboard_form sescontest_db_award_form sesbasic_bxs">
  <?php echo $this->form->render() ?>
</div>
<?php if(!$this->is_ajax){ ?>
  </div>
</div>
</div>
<?php } ?>
 <?php $canJoin = 1;?>
<?php if($this->is_ajax) die; ?>
<?php $editAwardPermission = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.edit.award', 1);?>
<?php if(strtotime($this->contest->joinstarttime) < time()):?>
  <?php $canJoin = 0;?>
<?php endif;?>
<script type='text/javascript'>
  scriptJquery( window ).load(function() {
    if('<?php echo $canJoin;?>' == 0 || '<?php echo $editAwardPermission;?>' == 0) 
    scriptJquery('#award-wrapper').addClass('dashboard_award_disable');
    if('<?php echo $canJoin;?>' == 0 || ('<?php echo $this->contest->award2;?>' != '' && '<?php echo $editAwardPermission;?>' == 0))
      scriptJquery('#award2-wrapper').addClass('dashboard_award_disable');
    if('<?php echo $canJoin;?>' == 0 || ('<?php echo $this->contest->award3;?>' != '' && '<?php echo $editAwardPermission;?>' == 0))
      scriptJquery('#award3-wrapper').addClass('dashboard_award_disable');
    if('<?php echo $canJoin;?>' == 0 || ('<?php echo $this->contest->award4;?>' != '' && '<?php echo $editAwardPermission;?>' == 0))
      scriptJquery('#award4-wrapper').addClass('dashboard_award_disable');
    if('<?php echo $canJoin;?>' == 0 || ('<?php echo $this->contest->award5;?>' != '' && '<?php echo $editAwardPermission;?>' == 0))
      scriptJquery('#award5-wrapper').addClass('dashboard_award_disable');
    
  });
</script>

<style type="text/css">
  .dashboard_award_disable .form-element{position:relative;}
  .dashboard_award_disable .form-element:after{
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    HEIGHT: 100%;
    width: 100%;
    background-color: rgba(255, 255, 255, 0.4);
  }  
</style>
