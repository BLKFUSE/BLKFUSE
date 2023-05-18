<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: photos.tpl 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Edating/externals/styles/styles.css'); ?>
<h3><?php echo $this->translate("Manage Photos"); ?></h3>
<?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
  <div class="edating_dating_photos_wrapper">
    <?php foreach ($this->paginator as $item): $i++; ?>
      <div class="edating_dating_photos">
        <div class="edating_dating_photos_innerpart <?php if ($item->is_main) echo "user_main_photo"; ?>">
          <img src="<?php echo $item->getPhotoUrl(); ?>" />
          <div class="edating_option_btn">
            <?php if (!$item->is_main) { ?>
              <?php echo $this->htmlLink(array('route' => 'edating_general', 'action' => 'makemainphoto', 'id' => $item->photo_id), $this->translate("Main"),array('class' => 'smoothbox main-btn fa-file-image buttonlink')) ?>
              &nbsp; | &nbsp;
            <?php } else { ?> 
              <i class="fas fa-file-image"></i>
              <?php echo $this->translate("Main Photo");  ?>
              &nbsp; | &nbsp;
            <?php } ?>
            <?php echo $this->htmlLink(array('route' => 'edating_general', 'action' => 'deletephoto', 'id' => $item->photo_id),$this->translate("Delete"),array('class' => 'smoothbox delete-btn buttonlink fa-trash')) ?>
          </div>
        </div>
      </div>
      <?php if ($i==5){$i=0; echo "<div class='clr'></div>";} ?> 
      <?php endforeach; ?>
  </div>
      <div class='clr'></div>
      <?php echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true)); ?>
      <?php else : ?>
      <div>
        <p><?php echo $this->translate("There are no photos, add it below"); ?></p>
      </div>
<?php endif; ?>
<div class="edating_photos_form">
  <?php echo $this->form->render($this); ?>
</div>
<script type="text/javascript">
  en4.core.runonce.add(function() {
    new Uploader('#upload_file', {
      uploadLinkClass : 'buttonlink icon_photos_new',
      uploadLinkTitle : '<?php echo $this->translate("Add Photos");?>',
      uploadLinkDesc : '<?php echo $this->translate("Click \"Add Photos\" to select one or more photos from your computer."
        . " After you have selected the photos, they will begin to upload right away. "
        . "When your upload is finished, click the button below your photo list to save them to your album.");?>'
    });
  });
</script>
