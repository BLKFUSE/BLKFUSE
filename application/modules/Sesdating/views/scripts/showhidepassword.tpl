<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: showhidepassword.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<div id="showhide_password" class="showhide_password ka">
<a class="fa fa-eye" id="show_password" href="javascript:void(0);" onclick="showhidepassword('show')" title='<?php echo $this->translate("Show Password"); ?>'></a>
<a class="fa fa-eye-slash" id="hide_password" href="javascript:void(0);" onclick="showhidepassword('hide')" style="display:none;" title='<?php echo $this->translate("Hide Password"); ?>'></a>
</div>
<script>

function showhidepassword(showhidepassword) {
	if(showhidepassword == 'show'){
		if(document.getElementById('show_password'))
			document.getElementById('show_password').style.display = 'none';
		if(document.getElementById('hide_password'))
			document.getElementById('hide_password').style.display = 'block';
		if(scriptJquery('#password'))
			scriptJquery('#password').attr('type', 'text');
		if(scriptJquery('#showhide_password'))
			scriptJquery('#showhide_password').addClass('m');
	} else if(showhidepassword == 'hide') {
		if(document.getElementById('show_password'))
			document.getElementById('show_password').style.display = 'block';
		if(document.getElementById('hide_password'))
			document.getElementById('hide_password').style.display = 'none';
		if(scriptJquery('#password'))
			scriptJquery('#password').attr('type', 'password');
		if(scriptJquery('#showhide_password'))
			scriptJquery('#showhide_password').removeClass('m');
	}
}
</script>
