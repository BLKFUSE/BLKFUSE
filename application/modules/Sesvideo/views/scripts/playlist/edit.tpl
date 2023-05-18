<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<?php $videos = $this->playlist->getVideos(); ?>
<div class='sesvideo_playlist_popup'>
<?php echo $this->form->render($this) ?>
<div style="display:none;">
  <?php if (!empty($videos)): ?>
    <ul id="sesvideo_playlist">
      <?php foreach ($videos as $video): 
      	$videoMain = Engine_Api::_()->getItem('video', $video->file_id); 
      ?>
      <li id="song_item_<?php echo $video->playlistvideo_id ?>" class="file file-success">
        <a href="javascript:void(0)" class="video_action_remove file-remove"><?php echo $this->translate('Remove') ?></a>
        <span class="file-name">
          <?php echo $videoMain->getTitle() ?>
        </span>
      </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
</div>
<script type="text/javascript">
  en4.core.runonce.add(function(){
    
    document.getElementById('demo-status').style.display = 'none';

    //IMPORT SONGS INTO FORM
    if (scriptJquery('#sesvideo_playlist li.file').length) {
      scriptJquery('#sesvideo_playlist li.file').inject(document.getElementById('demo-list'));

      scriptJquery('#demo-list').show()
    }
    
    //REMOVE/DELETE SONG FROM PLAYLIST
    scriptJquery(document).on('click','a.video_action_remove',function(event) {
      var video_id  = $(this).getParent('li').id.split(/_/);
          video_id  = video_id[ video_id.length-1 ];
      
      scriptJquery(this).parent('li').remove();
      scriptJquery.ajax({
        dataType: 'json',
        url: '<?php echo $this->url(array('module'=> 'sesvideo' ,'controller'=>'playlist','action'=>'delete-playlistvideo'), 'default') ?>',
        data: {
          'format': 'json',
          'playlistvideo_id': video_id,
          'playlist_id': <?php echo $this->playlist->playlist_id ?>
        }
      });
      return false;
    });
});
</script>
