<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Announcement
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: deleteselected.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenu' => "core_admin_main_manage", 'parentMenuItemName' => 'core_admin_main_manage_announcements', 'lastMenuItemName' => 'Delete the selected announcements')); ?>
<div class="settings">
  <div class='global_form'>
    <?php if ($this->ids):?>
    <form method="post">
      <div>
        <h3><?php echo $this->translate("Delete the selected announcements?") ?></h3>
        <p>
          <?php echo $this->translate("Are you sure that you want to delete the %d announcements? It will not be recoverable after being deleted.", $this->count) ?>
        </p>
        <br />
        <p>
          <input type="hidden" name="confirm" value='true'/>
          <input type="hidden" name="ids" value="<?php echo $this->ids?>"/>

          <button type='submit'><?php echo $this->translate("Delete") ?></button>
          <?php echo Zend_Registry::get('Zend_Translate')->_(' or ') ?>
          <a href='<?php echo $this->url(array('action' => 'index', 'id' => null)) ?>'>
          <?php echo $this->translate("cancel") ?></a>
        </p>
      </div>
    </form>
    <?php else: ?>
      <?php echo $this->translate("Please select an announcement to delete.") ?> <br/><br/>
      <a href="<?php echo $this->url(array('action' => 'index')) ?>" class="buttonlink icon_back">
        <?php echo $this->translate("Go Back") ?>
      </a>
    <?php endif;?>
  </div>
</div>
<script type="application/javascript">
  scriptJquery('.core_admin_main_manage').parent().addClass('active');
  scriptJquery('.core_admin_main_manage_announcements').addClass('active');
</script>
