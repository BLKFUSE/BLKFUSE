<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: comment.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<p><?php echo $this->message ?></p>
<script type="text/javascript">
//<![CDATA[
parent.en4.activity.viewComments(<?php echo $this->action_id ?>);
parent.Smoothbox.close();
//]]>
</script>