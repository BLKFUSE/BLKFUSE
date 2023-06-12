<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _composeLinkedin.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php
  if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.linkedin.secret','') && !Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.linkedin.access','')) {
    return;
  }

  $likedinTable = Engine_Api::_()->getDbTable('linkedin', 'sesadvancedactivity');
  $linkedinApi = $likedinTable->getApi();
  // Disabled
  $status = true;
  if( !$linkedinApi || !$likedinTable->isConnected()) {
    return false;
  }
  
  // Not logged into correct linkedin account
  if(empty($_SESSION['linkedin_uid'])) {
    $status = false; 
  }

  // Add script
  $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/composer_linkedin.js');
?>

<script type="text/javascript">
  en4.core.runonce.add(function() {
    composeInstance.addPlugin(new Composer.Plugin.SesadvancedactivityLikedin({
      status:'<?php echo $status ?>',
      lang : {
        'Publish this on Linkedin' : '<?php echo $this->translate('Publish this on Linkedin') ?>'
      }
    }));
  });
 
 scriptJquery(document).on('click','.openWindowLinkedin',function(e){
  authSesactmyWindow =  window.open('<?php echo Engine_Api::_()->getDbTable("linkedin","sesadvancedactivity")->loginButton(); ?>','Linkedin', "width=780,height=410,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0");  
 });
</script>
