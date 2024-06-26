<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>
<?php
  $this->headScript()
    
    ->appendFile($this->layout()->staticBaseUrl . 'externals/jQuery/jquery-ui.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/jQuery/imagesloaded.pkgd.js')
    ->appendFile($this->layout()->staticBaseUrl . 'externals/jQuery/jquery.drag-n-crop.js')
    
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Core/externals/scripts/coverphoto.js');
    
  $this->headLink()
    ->appendStylesheet($this->layout()->staticBaseUrl . 'externals/jQuery/jquery.drag-n-crop.css')
    ->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Core/externals/styles/coverphoto.css'); 
?>

<?php if (!empty($this->uploadDefaultCover)): ?>
    <div class="tip">
      <span>
          <?php echo $this->defaultCoverMessage; ?>
      </span>
    </div>
    <br />
<?php endif; ?>
<?php if (isset($this->group->level_id)) {
        $level_id = $this->group->level_id;
      } else {
        $level_id = 0;
      }
      $coverPhotoId = Engine_Api::_()->getApi("settings", "core")
        ->getSetting("groupcoverphoto.preview.level.id.$level_id"); ?>
    <div class="profile_cover_wrapper">
      <div class="profile_cover_photo_wrapper" id="group_cover_photo">
        <div class="shimmer_profile_cover_photo_wrapper"> 
        </div>            
      </div>
      <div class="profile_cover_head_section" id="group_main_photo">
        <div class="shimmer_profile_cover_head_inner">
          <div class="shimmer_profile_main_photo_wrapper">
          </div>
          <div class="shimmer_cover_photo_profile_information">
            <div class="shimmer_cover_photo_profile_status">
              <h2></h2>
              <p class="shimmer_cover_photo_stats"></p>
            </div>
            <div class="shimmer_coverphoto_navigation">
              <ul>
                <li>
                  <a href="javascript:void(0)" class=""></a>
                </li>
              </ul>
            </div>    
          </div>         
        </div>        
      </div>
    </div>

    <div class="clr"></div>
<?php if (isset($this->group->group_id)) {
        $group_id = $this->group->group_id;
      } else {
        $group_id = 0;
      } ?>

<script type="text/javascript">
  en4.core.runonce.add(function () {
      document.coverPhoto = new Coverphoto({
        block: scriptJquery('#group_cover_photo'),
        photoUrl: '<?php echo $this->url(array(
          'action' => 'get-cover-photo',
          'group_id' => $group_id,
          'photoType' => 'cover',
          'uploadDefaultCover' => $this->uploadDefaultCover,
          'level_id' => $this->level_id), 'group_coverphoto', true); ?>',
        buttons: '#cover_photo_options',
        positionUrl: '<?php echo $this->url(array(
          'action' => 'reset-cover-photo-position',
          'group_id' => $group_id,
          'uploadDefaultCover' => $this->uploadDefaultCover,
          'level_id' => $this->level_id), 'group_coverphoto', true); ?>',
        position: <?php echo Zend_Json_Encoder::encode(array('top' => 0, 'left' => 0)); ?>,
        uploadDefaultCover: '<?php echo $this->uploadDefaultCover; ?>',
      });

      document.mainPhoto = new Mainphoto({
        block: scriptJquery('#group_main_photo'),
        photoUrl: '<?php echo $this->url(array(
          'action' => 'get-main-photo',
          'group_id' => $group_id,
          'photoType' => 'profile',
          'uploadDefaultCover' => $this->uploadDefaultCover), 'group_coverphoto', true); ?>',
        buttons: '#cover_photo_options',
        positionUrl: '<?php echo $this->url(array(
          'action' => 'reset-position-cover-photo',
          'group_id' => $group_id), 'group_coverphoto', true); ?>',
        position:<?php echo Zend_Json_Encoder::encode(array('top' => 0, 'left' => 0)); ?>
      });
  });
  function showSmoothBox(url) {
    Smoothbox.open(url);
  }
  en4.core.runonce.add(function () {
    setTimeout("setTabInsideLayout()", 500);
  });

  function setTabInsideLayout() {
    var tab = scriptJquery('#global_content').find('div.layout_core_container_tabs');
    if (tab.length && tab.hasClass('generic_layout_container layout_core_container_tabs')) {
      tab.removeClass('generic_layout_container layout_core_container_tabs');
      tab.addClass('generic_layout_container layout_core_container_tabs profile_cover_photo_tabs');
    }
  }
</script>
