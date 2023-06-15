<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: existing-songs.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>
<?php foreach( $this->paginator as $album ){
			$counterAlbum = 0;
?>
<div class="sescontest_update_music_row sesbasic_clearfix">
	<div id="sescontest_photo_content_<?php echo $album->album_id; ?>">
	<?php  
    $albumSongsTable = Engine_Api::_()->getDbtable('albumsongs', 'sesmusic');
    $select = $albumSongsTable->select()->where('album_id = ?', $album->getIdentity())->order('order ASC');
    $photos = Zend_Paginator::factory($select);
                  $photos->setItemCountPerPage($this->limit);
                  $photos->setCurrentPageNumber(1);
        if($photos->getTotalItemCount() > 0){
          foreach($photos as $photo){ ?>
          
          <?php  if($photo->track_id){
          $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
          $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid');
          $URL = "http://api.soundcloud.com/tracks/$photo->track_id/stream?consumer_key=$consumer_key";  
        }else{ 
          $URL = $photo->getFilePath();
        } ?>
          
          <?php if($counterAlbum == 0){ ?>
            <span class="sescontest_name"><?php echo $album->title; ?></span>
          <?php } ?>
            <div class="sescontest_thumb">
              <a href="javascript:void(0);" title="<?php echo $photo->title; ?>" id="sescontest_profile_upload_existing_photos_<?php echo $photo->albumsong_id; ?>" data-src="<?php echo $photo->photo_id; ?>" data-url="<?php echo $URL;?>" class="sescontest_thumb_img">
                <span class="bg_item_photo" style="background-image:url(<?php echo $photo->getPhotoUrl(); ?>);"></span>
              <p class="photo_tittle"><?php echo $photo->title; ?></p>
              </a>
              
            </div>
        <?php
            $counterAlbum++;
          } ?>
 </div>
 			<?php if($photos->count() != $photos->getCurrentPageNumber()){ ?>
        <div class="album_more_photos floatR clear">
          <a href="javascript:;" id="sescontest_existing_album_see_more_<?php echo $album->album_id; ?>" data-src="1">
            <?php echo $this->translate("See More"); ?> &raquo;
          </a>
        </div>
      <?php } ?>
      <div class="clear" style="text-align:center;display:none;" id="sescontest_existing_album_see_more_loading_<?php echo $album->album_id; ?>">
      	<img src="application/modules/Core/externals/images/loading.gif" alt="Loading"  />
      </div>
  <?php }  ?>
</div>
<?php } ?>
<?php  if($this->paginator->getTotalItemCount() == 0){  ?>
 <div class="tip">
    <span>
      <?php echo $this->translate('There is no album yet.') ?>
      <?php if($this->canCreate): ?>
        <?php echo $this->htmlLink(array('route' => 'sesmusic_general', 'action' => 'create'), $this->translate('Why don\'t you add some?')) ?>
      <?php endif; ?>
    </span>
  </div>
<?php } ?>
<script type="application/javascript">
canPaginateExistingPhotos = "<?php echo ($this->paginator->count() == 0 ? '0' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? '0' : '1' ))  ?>";
canPaginatePageNumber = "<?php echo $this->page + 1; ?>";
</script>
<?php die; ?>