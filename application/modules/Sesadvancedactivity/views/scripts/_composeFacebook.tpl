<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _composeFacebook.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php
  $facebookTable = Engine_Api::_()->getDbtable('facebook', 'sesadvancedactivity');
  $facebookApi = $facebookTable->getApi();
  // Disabled
  $status = true;
  if( !$facebookApi ||
      'publish' != Engine_Api::_()->getApi('settings', 'core')->core_facebook_enable ) {
    return false;
  }
  // Not logged in
  if( !$facebookTable->isConnected() ) {
    $status = false;
  }
  // Not logged into correct facebook account
  if( !$facebookTable->checkConnection() ) {
    $status = false; 
  }

  // Add script
  $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/composer_facebook.js');
?>

<script type="text/javascript">
  en4.core.runonce.add(function() {
    composeInstance.addPlugin(new Composer.Plugin.SesadvancedactivityEvacebook({
      status:'<?php echo $status ?>',
      lang : {
        'Publish this on Facebook' : '<?php echo $this->string()->escapeJavascript($this->translate('Publish this on Facebook')); ?>'
      }
    }));
  });
 
 scriptJquery(document).on('click','.openWindowFacebook',function(e){
  authSesactmyWindow =  window.open('<?php echo Engine_Api::_()->getDbTable("facebook","sesadvancedactivity")->loginButton(); ?>','Facebook', "width=780,height=410,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0");  
 });
</script>
