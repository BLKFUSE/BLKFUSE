<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Album
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */
?>

<h2>
  <?php echo $this->translate('Member Level Settings') ?>
</h2>
<?php $flushData = Engine_Api::_()->album()->getFlushPhotoData(); ?>
<?php if($flushData >0){ ?>
  <div class="unmapped_warning">
    You have <span class="_num"><?php echo $flushData; ?></span> unmapped photos. <?php echo $this->htmlLink(array('module' => 'album', 'controller' => 'settings', 'action' => 'flush-photo'), $this->translate('Click here'), array('class' => 'smoothbox icon_photos_delete')); ?> to remove them.
  </div>
  <br />
<?php } ?>
<script type="text/javascript">
  var fetchLevelSettings = function(level_id) {
    var url = '<?php echo $this->url(array('id' => null)) ?>';
    window.location.href = url + '/index/id/' + level_id;
  }
</script>

<?php if( engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<div class='settings'>
  <?php echo $this->form->render($this) ?>
</div>
