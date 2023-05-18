<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: location.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/scripts/core.js');?>
<script type="application/javascript">
  scriptJquery( window ).load(function() {
    document.getElementById('lng-wrapper').style.display = 'none';
    document.getElementById('lat-wrapper').style.display = 'none';
    mapLoad = false;
    initializeSesMemberMapList();
  });
</script>
