<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: location.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js');?>
<script type="application/javascript">
scriptJquery( window ).load(function() {
	document.getElementById('lng-wrapper').style.display = 'none';
	document.getElementById('lat-wrapper').style.display = 'none';
	mapLoad = false;
	initializeSesNewsMapList();
});
</script>
