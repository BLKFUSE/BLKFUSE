<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: renew.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<form method="post" class="global_form_popup">
  <div>
    <h3><?php echo $this->translate('Renew Advertisement?'); ?></h3>
    <p>
      <?php echo $this->translate('Are you sure that you want to renew this Advertisement?'); ?>
    </p>
    <br />
    <p>
      <input type="hidden" name="confirm" value="<?php echo $this->sitepage_id ?>"/>
      <button type='submit'><?php echo $this->translate('Renew'); ?></button>
      or <a href='javascript:void(0);' onclick='javascript:parent.Smoothbox.close()'><?php echo $this->translate('cancel'); ?></a>
    </p>
  </div>
</form>

<?php if (@$this->closeSmoothbox): ?>
  <script type="text/javascript">
    TB_close();
  </script> 
<?php endif; ?>