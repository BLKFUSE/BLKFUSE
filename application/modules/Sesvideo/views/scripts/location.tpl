<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: location.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js');?>
<script type="application/javascript">
scriptJquery( window ).load(function() {
	document.getElementById('lng-wrapper').style.display = 'none';
	document.getElementById('lat-wrapper').style.display = 'none';
	mapLoad = false;
	initializeSesVideoMapList();
});
</script>
