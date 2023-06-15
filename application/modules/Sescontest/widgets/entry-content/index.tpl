<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?> 
<?php $contest_id = $this->contest->contest_id;?>
<?php $contestType = $this->contest->contest_type;?>
<?php if ($contestType == 3 && $this->entry->type == 3 && $this->entry->status == 1):?>
  <?php if (!empty($this->entry->file_id)) :?>
    <?php $storage_file = Engine_Api::_()->getItem('storage_file', $this->entry->file_id);?>
    <?php if($storage_file):?>
      <?php $video_location = $storage_file->map();?>
      <?php $video_extension = $storage_file->extension;?>
    <?php endif;?>
  <?php endif;?>
  <?php if($video_extension == 'flv' ):?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/flowplayer/flashembed-1.0.1.pack.js');?>
      <script type='text/javascript'>
        en4.core.runonce.add(function() {
          flashembed("video_embed", {
            src: "<?php echo $this->layout()->staticBaseUrl . $flowplayer; ?>",
            width: 480,
            height: 386,
            wmode: 'transparent'
          }, {
          config: {
            clip: {
              url: "<?php echo $video_location;?>",
              autoPlay: false,
              duration: "<?php echo $this->entry->duration ?>",
              autoBuffering: true
            },
            plugins: {
              controls: {
                background: '#000000',
                bufferColor: '#333333',
                progressColor: '#444444',
                buttonColor: '#444444',
                buttonOverColor: '#666666'
              }
            },
            canvas: {
              backgroundColor:'#000000'
            }
          }
        });
      });
    </script>
  <?php endif;?>
<?php endif;?>

<div class="sescontest_entry_view_container sesbasic_clearfix sesbasic_bxs">
	<div class="sescontest_entry_view_media_container">
  	<div class="sescontest_entry_view_nav_btns">
      <?php if($this->previous):?>
        <?php $previousEntryId = Engine_Api::_()->getDbTable('participants', 'sescontest')->getpreviousEntryId($this->entry->participant_id,$contest_id);?>
          <?php if($previousEntryId):?>
            <a class="_prev sesbasic_animation" href="<?php echo Engine_Api::_()->getItem('participant', $previousEntryId)->getHref();?>" title="<?php echo $this->translate('Previous Entry');?>"><i class="fa fa-angle-left"></i></a>
        <?php endif;?>
      <?php endif;?>
      <?php if($this->next):?>
        <?php $nextEntryId = Engine_Api::_()->getDbTable('participants', 'sescontest')->getNextEntryId($this->entry->participant_id,$contest_id);?>
        <?php if($nextEntryId):?>
      		<a href="<?php echo Engine_Api::_()->getItem('participant', $nextEntryId)->getHref();?>" title="<?php echo $this->translate('Next Entry');?>" class="_next sesbasic_animation"><i class="fa fa-angle-right"></i></a>
        <?php endif;?>
      <?php endif;?>
    </div> 
    <?php if($contestType == 3):?>
      <?php  $embedded = "";?>
      <?php if ($this->entry->status == 1) :?>
        <?php $embedded = $this->entry->getRichContent(true,array(),'','');?>
      <?php endif;?>
      <?php if ($this->entry->type == 3 && $this->entry->status == 1):?>
         <div id="sescontest_entry_embed" class="clear sesbasic_clearfix sescontest_entry_view_media_audio">
         	<div class="sescontest_entry_view_media_audio_bg" style="background-image: url(<?php echo $this->entry->getPhotoUrl('thumb.main'); ?>);"></div>
          <?php if ($video_extension !== 'flv'): ?>
          	<div class="sescontest_entry_view_media_audio_img">
          		<video id="video" poster="<?php echo $this->entry->getPhotoUrl(); ?>" controls controlsList="nodownload" preload="auto" width="480" height="386">
              	<source type='video/mp4' src="<?php echo $video_location ?>">
            	</video>
            </div>  
          <?php endif;?>
      	</div>
      <?php else: ?>
        <div class="sescontest_entry_view_media_video clear sesbasic_clearfix">
          <?php echo $embedded; ?>
        </div>
      <?php endif; ?>
    <?php elseif($contestType == 4):?>
      <?php  if($photo->track_id):?>
        <?php $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid');?>
        <?php $URL = "http://api.soundcloud.com/tracks/$photo->track_id/stream?consumer_key=$consumer_key";  ?>
      <?php else:?>
        <?php $file = Engine_Api::_()->getItem('storage_file', $this->entry->file_id);?>
        <?php if ($file):?>
          <?php $URL = $file->map();?>
        <?php endif;?>
      <?php endif; ?>
      <div class="sescontest_entry_view_media sescontest_entry_view_media_audio">
        <div class="sescontest_entry_view_media_audio_bg" style="background-image: url(<?php echo $this->entry->getPhotoUrl('thumb.main'); ?>);"></div>
        <div class="sescontest_entry_view_media_audio_img">
          <img  src="<?php echo $this->entry->getPhotoUrl('thumb.main'); ?>" />
          <audio controls src="<?php echo $URL;?>" type="audio/mpeg"></audio>
        </div>
      </div>
    <?php elseif($contestType == 1):?>
      <div class="sescontest_entry_view_media sesbasic_html_block entry_cont_txt">
       <?php echo $this->entry->description;?>
      </div>
    <?php else:?>
      <div class="sescontest_entry_view_media sescontest_entry_view_media_photo">
      	<div class="sescontest_entry_view_media_photo_holder">
        <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum')){ ?>
        	<a href="javascript:;" onClick="openLightBoxForSesPlugins('<?php echo $this->entry->getHref(); ?>','<?php echo $this->entry->getPhotoUrl(); ?>')" title="<?php echo $this->translate('Open image in image lightbox viewer'); ?>" class="sescontest_entry_view_media_photo_expend"><i class="fa fa-expand"></i></a>
          <?php } ?>
        	<img  src="<?php echo $this->entry->getPhotoUrl('thumb.main'); ?>" />
      	</div>
      </div>
    <?php endif;?>
  </div>
</div>
