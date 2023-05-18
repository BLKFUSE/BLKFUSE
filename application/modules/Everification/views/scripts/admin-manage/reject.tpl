<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: reject.tpl 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<form method="post" class="global_form_popup">
  <div>
    <h3><?php echo $this->translate("Reject Document?") ?></h3>
    <p>
      <?php echo $this->translate("Are you sure you want to reject this document?") ?>
    </p>
    <div class="form-wrapper">
    	<div class="form-label"><label>Note</label></div>
    	<div class="form-element"><textarea rows="4" cols="50" name="note"></textarea></div>
    </div>	
    <div class="form-wrapper">
      <input type="hidden" name="confirm"/>
      <button type='submit'><?php echo $this->translate("Reject") ?></button>
      or <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'>cancel</a>
    </div>
  </div>
</form>
<style type="text/css">
.form-label label{
	font-weight: bold;
	margin-bottom: 5px;
	display: block;
}
</style>
