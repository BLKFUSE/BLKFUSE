<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: contest-expire.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if(strtotime($this->contest->endtime) < time()){ ?>
<div class="tip"> <span> <?php echo $this->translate("Contest has been expired.") ?> </span> </div>
<?php } ?>
