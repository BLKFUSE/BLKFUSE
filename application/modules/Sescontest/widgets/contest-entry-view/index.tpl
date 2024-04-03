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
<?php if(!$this->viewer()->getIdentity()):?><?php $levelId = 5;?><?php else:?><?php $levelId = $this->viewer()->level_id;?><?php endif;?>
<?php $voteType = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'allow_entry_vote');?>
<?php if ($voteType != 0 && (($voteType == 1 && $this->entry->owner_id != $this->viewer()->getIdentity()) || $voteType == 2)):?>
  <?php $canIntegrate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.vote.integrate', 0);?>
<?php else:?>
  <?php $canIntegrate = 0;?>
<?php endif;?>
<?php $contest_id = $this->contest->contest_id;?>
<?php $nextEntryId = Engine_Api::_()->getDbTable('participants', 'sescontest')->getNextEntryId($this->entry->participant_id,$contest_id);?>
<?php $previousEntryId = Engine_Api::_()->getDbTable('participants', 'sescontest')->getpreviousEntryId($this->entry->participant_id,$contest_id);?>
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

<!----photo entry view---->
<?php $owner = $this->entry->getOwner();?>
<div class="sescontest_entry_view_container sesbasic_clearfix sesbasic_bxs">
	<div class="sescontest_entry_view_top sesbasic_clearfix">
  	<div class="sescontest_entry_view_top_left">
      <?php if(isset($this->titleActive)):?><h1><?php echo $this->entry->title;?></h1><?php endif;?>
      <p><?php echo $this->translate('in');?>&nbsp;<a href="<?php echo $this->contest->getHref();?>"><?php echo $this->contest->getTitle();?></a></p>
    </div>
  </div>  
  <?php if(isset($this->uploadesContentActive)):?>
  <div class="sescontest_entry_view_media_container">
    <div class="sescontest_entry_view_nav_btns">
      <?php if($nextEntryId):?>
        <a class="_next sesbasic_animation" href="<?php echo Engine_Api::_()->getItem('participant', $nextEntryId)->getHref();?>" title="<?php echo $this->translate('Next Entry');?>"><i class="fa fa-angle-right"></i></a>
      <?php endif;?>
      <?php if($previousEntryId):?>
        <a class="_prev sesbasic_animation" href="<?php echo Engine_Api::_()->getItem('participant', $previousEntryId)->getHref();?>" title="<?php echo $this->translate('Previous Entry');?>"><i class="fa fa-angle-left"></i></a>
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
          <img src="<?php echo $this->entry->getPhotoUrl('thumb.main'); ?>" />
          <audio controls src="<?php echo $URL;?>" type="audio/mpeg"></audio>
        </div>
      </div>
     <?php elseif($contestType == 2):?>
      <div class="sescontest_entry_view_media sescontest_entry_view_media_photo">
      	<div class="sescontest_entry_view_media_photo_holder">
         <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum')){ ?>
        	<a href="javascript:;" onClick="openLightBoxForSesPlugins('<?php echo $this->entry->getHref(); ?>','<?php echo $this->entry->getPhotoUrl(); ?>')" title="<?php echo $this->translate('Open image in image lightbox viewer'); ?>" class="sescontest_entry_view_media_photo_expend"><i class="fa fa-expand"></i></a>
          <?php } ?>
        	<img src="<?php echo $this->entry->getPhotoUrl('thumb.main'); ?>" />
      	</div>
      </div>
     <?php else:?>
      <div class="sescontest_entry_view_media sesbasic_html_block entry_cont_txt">
       <?php echo $this->entry->description;?>
      </div>
    <?php endif;?>
    </div>
  <?php endif;?>
  <div class="sescontest_entry_view_info sesbasic_clearfix">
  	<div class="sescontest_entry_view_info_left">
      <?php if(isset($this->pPhotoActive)):?>
        <div class="sescontest_entry_view_owner_photo">
          <?php if($owner->photo_id):?>
            <a href="<?php echo $owner->getHref();?>"><img src="<?php echo Engine_Api::_()->storage()->get($owner->photo_id)->getPhotoUrl('thumb.icon'); ?>" alt=""></a>
          <?php else:?>
            <a href="<?php echo $owner->getHref();?>"><img src="application/modules/User/externals/images/nophoto_user_thumb_icon.png" alt=""></a>
          <?php endif;?>
        </div>
      <?php endif;?>
      <div class="sescontest_entry_view_owner_info">
      	<div class="sescontest_entry_view_owner_name">
          <?php if(isset($this->pNameActive)):?>
            <?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(), $owner->getTitle()) ?><span class="sescontest_entry_view_category_name"></span>
          <?php endif;?>
          <?php if(isset($this->submitDateActive)):?>
            <p class="sescontest_entry_view_srtat_date sesbasic_text_light"><?php echo $this->translate('on');?>&nbsp;<?php echo date('d-m-Y',strtotime($this->entry->creation_date));?>
          <?php endif;?>
          <?php if(isset($this->votestartenddateActive)):?>
           <?php $dateinfoParams['votingstarttime'] = true; ?>
           <?php $dateinfoParams['votingendtime'] = true; ?>
           <?php $dateinfoParams['timezone']  =  true; ?>
          |&nbsp;<?php echo $this->contestStartEndDates($this->contest, $dateinfoParams);?>
          <?php endif;?>
        </div>
      </div>
    </div>
    <?php if(isset($this->voteButtonActive)):?>
      <div class="floatR sescontest_entry_view_btns_right">
        <?php $entry = $this->entry;?>
        <span><?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_voteData.tpl';?></span>
        <span><a href="javascript:vois(0);" class="sesbasic_button sescontest_entry_view_option_btn" id="sescontest_entry_view_option_btn"><i class="fa fa-cog"></i></a></span>
      </div>
    <?php endif;?>
  </div>
  <div class="floatL sescontest_entry_view_stats_cout sescontest_entry_view_info_stats">
    <?php if(isset($this->voteActive) && (!$this->contest->resulttime || strtotime($this->contest->resulttime) <= time())):?>
      <div>
        <?php $voteCount =$this->entry->vote_count;?>
        <span><?php echo $this->entry->vote_count;?></span>
        <span class="sesbasic_text_light"><?php if($voteCount == 1):?><?php echo $this->translate('Vote');?><?php else:?><?php echo $this->translate('Votes');?><?php endif;?></span>
      </div>
    <?php endif;?>
    <?php if(isset($this->likeActive)):?>
      <div>
        <?php $likeCount =$this->entry->like_count;?>
        <span><?php echo $this->entry->like_count;?></span>
        <span class="sesbasic_text_light"><?php if($likeCount == 1):?><?php echo $this->translate('Like');?><?php else:?><?php echo $this->translate('Likes');?><?php endif;?></span>
      </div>
    <?php endif;?>
    <?php if(isset($this->commentActive)):?>
      <div>
        <?php $commentCount =$this->entry->comment_count;?>
        <span><?php echo $commentCount;?></span>
        <span class="sesbasic_text_light"><?php if($commentCount == 1):?><?php echo $this->translate('Comment');?><?php else:?><?php echo $this->translate('Comments');?><?php endif;?></span>
      </div>
    <?php endif;?>
    <?php if(isset($this->viewActive)):?>
      <div>
        <?php $viewCount =$this->entry->view_count;?>
        <span><?php echo $viewCount;?></span>
        <span class="sesbasic_text_light"><?php if($viewCount == 1):?><?php echo $this->translate('View');?><?php else:?><?php echo $this->translate('Views');?><?php endif;?></span>
      </div>
    <?php endif;?>
    <?php if(isset($this->favouriteActive)):?>
      <div>
        <?php $favouriteCount =$this->entry->favourite_count;?>
        <span><?php echo $favouriteCount;?></span>
        <span class="sesbasic_text_light"><?php if($favouriteCount == 1):?><?php echo $this->translate('Favourite');?><?php else:?><?php echo $this->translate('Favourites');?><?php endif;?></span>
      </div>
    <?php endif;?>
  </div>
  <div class="sescontest_entry_view_btns sesbasic_clearfix">
    <div class="floatR">
    	<div class="sescontest_view_social_btns">
        <?php if(isset($this->socialSharingActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.entry.allow.share', 1)):?>
          <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->entry, 'socialshare_enable_plusicon' => $this->params['socialshare_enable_plusicon'], 'socialshare_icon_limit' => $this->params['socialshare_icon_limit'])); ?>
        <?php endif;?>
        <?php if(isset($this->likeButtonActive) && $this->canComment):?>
          <?php $likeStatus = Engine_Api::_()->sescontest()->getLikeStatus($this->entry->participant_id,$this->entry->getType()); ?>
          <a href="javascript:;" data-type="like_entry_view" data-integrate = "<?php echo $canIntegrate;?>" data-url="<?php echo $this->entry->participant_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sescontest_entry_like_<?php echo $this->entry->participant_id ?> sescontest_likefavfollow <?php echo ($likeStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-thumbs-up"></i><span><?php echo $this->entry->like_count;?></span></a>
        <?php endif;?>
        <?php if(isset($this->favouriteButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.entry.allow.favourite', 1)):?>
          <?php $favouriteStatus = Engine_Api::_()->getDbTable('favourites', 'sescontest')->isFavourite(array('resource_id' => $this->entry->participant_id,'resource_type' => $this->entry->getType())); ?>
          <a href="javascript:;" data-type="favourite_entry_view" data-url="<?php echo $this->entry->participant_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sescontest_entry_favourite_<?php echo $this->entry->participant_id ?> sescontest_likefavfollow <?php echo ($favouriteStatus) ? 'button_active' : '' ; ?>"><i class="fa fa-heart"></i><span><?php echo $this->entry->favourite_count;?></span></a>
        <?php endif;?>
      </div>
    </div>
  </div>
  <div class="sescontest_entry_view_footer">
  	<div class="sescontest_entry_view_meta sesbasic_clearfix">
      <div>
        <?php if(isset($this->mediaTypeActive)):?>
          <span class="sesbasic_text_light"><?php echo $this->translate('Media Type:');?></span>
          <span>
            <?php if($contestType == 3):?>
              <a href="<?php echo $this->url(array('action' => 'video'),'sescontest_media',true);?>"><?php echo $this->translate('Video Contest');?></a>
            <?php elseif($contestType == 4):?>
              <a href="<?php echo $this->url(array('action' => 'audio'),'sescontest_media',true);?>"><?php echo $this->translate('Audio Contest');?></a>
            <?php elseif($contestType == 2):?>
              <a href="<?php echo $this->url(array('action' => 'photo'),'sescontest_media',true);?>"><?php echo $this->translate('Photo Contest');?></a>
            <?php else:?>
              <a href="<?php echo $this->url(array('action' => 'text'),'sescontest_media',true);?>"><?php echo $this->translate('Writing Contest');?></a>
            <?php endif;?>
            <?php endif;?>
          </span>
        </div>
        <?php if(isset($this->category) && isset($this->categoryActive)):?>
          <div>
            <span><?php echo $this->translate('Category:');?></span>
            <span><a href="<?php echo $this->category->getHref(); ?>" ><?php echo $this->category->category_name;?></a></span>
          </div>
        <?php endif;?>
      <div>
        <?php if (engine_count($this->entryTags )):?>
          <span class="sesbasic_text_light"><?php echo $this->translate('Tags:');?>&nbsp;</span>
          <span>
            <?php foreach ($this->entryTags as $tag):?>
              <?php if(empty($tag->getTag()->text)):?><?php continue;?><?php endif;?>
              <a href='javascript:;' onclick='javascript:tagAction(<?php echo $tag->getTag()->tag_id; ?>,"<?php echo $tag->getTag()->text; ?>");'>#<?php echo $tag->getTag()->text?></a>&nbsp;
            <?php endforeach; ?>
          </span>
        <?php endif; ?>
      </div>  
    </div>
    <?php if(isset($this->descriptionActive) && $contestType != 1):?>
      <div class="sescontest_entry_view_des sesbasic_html_block"><?php echo $this->entry->description;?></div>
    <?php endif;?>
  </div>
</div>
<?php if(isset($this->optionMenuActive)):?>
  <div class="sescontest_entry_view_options sescontest_options_dropdown" id="sescontest_entry_view_options">
  	<span class="sescontest_options_dropdown_arrow"></span>
    <div class="sescontest_options_dropdown_links">
      <ul>
        <?php if($this->viewer()->getIdentity()):?>
          <?php if($this->entry->authorization()->isAllowed($this->viewer(), 'editentry')):?>
            <li><a href="<?php echo $this->url(array('action' => 'edit', 'contest_id' => $contest_id, 'id' => $this->entry->participant_id),'sescontest_join_contest','true');?>" class="smoothbox buttonlink sesbasic_icon_edit"><?php echo $this->translate('Edit Entry');?></a></li>
            <?php if(time() < strtotime($this->contest->votingstarttime) && Engine_Api::_()->authorization()->isAllowed('participant', $this->viewer(), 'deleteentry')):?>
              <li><a href="<?php echo $this->url(array('action' => 'delete', 'contest_id' => $contest_id, 'id' => $this->entry->participant_id),'sescontest_join_contest','true');?>" class="smoothbox buttonlink sesbasic_icon_delete"><?php echo $this->translate('Delete Entry');?></a></li>
            <?php endif;?>
          <?php endif;?>
          <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.entry.allow.share', 1)):?>
            <li><a href="<?php echo $this->url(array("module" => "activity","controller" => "index","action" => "share", "type" => $this->entry->getType(), "id" => $this->entry->getIdentity(), "format" => "smoothbox"), 'default', true);?>" class="buttonlink sesbasic_icon_share smoothbox"><?php echo $this->translate('Share Entry');?></a></li>
          <?php endif;?>
          <?php if(($owner->user_id != $this->viewer()->getIdentity()) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.report', 1)):?>
            <li><a href="<?php echo $this->url(array("module" => "core","controller" => "report","action" => "create", 'subject' => $this->entry->getGuid()),'default', true);?>" class="buttonlink sesbasic_icon_report smoothbox"><?php echo $this->translate('Report Entry');?></a></li>
          <?php endif;?>
        <?php endif;?>
      </ul>
    </div>
  </div>
<?php endif;?>

<script type="text/javascript">
  function doResizeForButton(){
      if(scriptJquery(".sescontest_entry_view_option_btn").length == 0) return;
      var topPositionOfParentSpan =  scriptJquery(".sescontest_entry_view_option_btn").offset().top + 34;
      topPositionOfParentSpan = topPositionOfParentSpan+'px';
      var leftPositionOfParentSpan =  scriptJquery(".sescontest_entry_view_option_btn").offset().left - 96;
      leftPositionOfParentSpan = leftPositionOfParentSpan+'px';
      scriptJquery('.sescontest_entry_view_options').css('top',topPositionOfParentSpan);
      scriptJquery('.sescontest_entry_view_options').css('left',leftPositionOfParentSpan);
  }
  scriptJquery( window ).load(function() {
      doResizeForButton();
  });
  scriptJquery(window).resize(function(){
      doResizeForButton();
  });
  scriptJquery(document).on('click','#sescontest_entry_view_option_btn',function(event) {

      //event.stop();
      if(scriptJquery('#sescontest_entry_view_options').hasClass('show-options'))
          scriptJquery('#sescontest_entry_view_options').removeClass('show-options');
      else
          scriptJquery('#sescontest_entry_view_options').addClass('show-options');
      return false;
  });
  var tagAction = window.tagAction = function(tag,name){
    var url = "<?php echo $this->url(array('action' => 'entries'), 'sescontest_general', true) ?>?tag_id="+tag+'&tag_name='+name;
    window.location.href = url;
  }
</script>	
