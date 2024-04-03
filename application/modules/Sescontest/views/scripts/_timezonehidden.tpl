<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _timezone.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<select id="contest_timezone_jq" name="timezone">
  <option value="<?php echo !empty($this->contest) ? $this->contest->timezone : $this->viewer()->timezone; ?>">UserTimezone</option>
</select>
<script type="application/javascript">
  scriptJquery('#contest_timezone_jq').hide();
</script>
<div class="form-wrapper sescontest_create_timezone">
  <div class="form-label">
     <label class="optional">&nbsp;</label>
  </div>
  <div class="form-element">
    <span><b><?php echo $this->translate('Timezone'); ?>:</b></span>
    <?php echo !empty($this->contest) ? $this->contest->timezone : $this->viewer()->timezone; ?>
    <div>
    <span><?php echo $this->translate('<a href="%s"><b>Click here</b></a> to modify your timezone.',$this->baseUrl().'/members/settings/general'); ?></span>
  </div>
  </div>
  
</div>
