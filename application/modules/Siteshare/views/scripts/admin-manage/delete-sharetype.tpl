<?php 
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: delete-sharetype.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

?> 

<form method="post" class="global_form_popup">
  <div>
    <h3><?php echo $this->translate('Delete Share Type?'); ?></h3>
    <p>
      <?php echo $this->translate('Are you sure that you want to delete this share type?'); ?>
    </p>
    <br />
    <p>
      <input type="hidden" name="confirm" value="<?php echo $this->sharetype_id ?>"/>
      <input type="hidden" name="sharetype_id" value="<?php echo $this->sharetype_id ?>"/>
      <button type='submit'><?php echo $this->translate('Delete'); ?></button>
      or <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate('cancel'); ?></a>
    </p>
  </div>
</form>
<?php if (@$this->closeSmoothbox): ?>
  <script type="text/javascript">
    TB_close();
  </script>
<?php endif; ?>