<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: deletead.tpl  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<form method="post" class="global_form_popup">
  <div>
    <div>
      <h3><?php echo $this->translate("Delete Advertisement?") ?></h3>
      <p>
        <?php echo $this->translate("Are you sure you want to delete this ad?") ?>
      </p>
      <br />
      <p>
        <input type="hidden" name="confirm" value="<?php echo $this->userad_id ?>"/>
        <button type='submit'><?php echo $this->translate("Delete") ?></button>
        <button type='submit'
        onclick="parent.Smoothbox.close()"><?php echo $this->translate("Cancel") ?></button>
      </p>
    </div>
  </div>
</form>

<?php if (@$this->closeSmoothbox): ?>
  <script type="text/javascript">
    TB_close();
  </script>   
<?php endif; ?>