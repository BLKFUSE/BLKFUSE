<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _sitead-pages.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()
  ->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/style_carousel.css') 
  ->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.carousel.min.css')
  ->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.carousel.css')
  ->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.theme.default.css')
  ->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/style.css');
$this->headScript()
  ->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/jquery.min.js')
  ->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/owl.carousel.js')
  ->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/core.js'); 
?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<?php $this->carouselClass = 'categorizedAdCarousel'; ?>
<script type="text/javascript">
  window.addEvent('domready' , function() {
    var request = new Request.HTML({
      url : 'http://ip-api.com/json',
      data : {
      },
      evalScripts : true,
      onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
       obj = JSON.parse(responseHTML);
       window.console.log(obj);
       setCookie('latitude', obj.lat);
       setCookie('longitude', obj.lon);
     }
   });
    request.send();
  });
  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
</script> 

<script>
  en4.core.runonce.add(function() {
  var j_q = jq.noConflict();
  j_q(document).ready(function () {
    j_q('.categorizedAdCarousel').owlCarousel({
      loop: false,
      autoplay: false,
      touchdrag: true,
      responsiveClass: true,
      responsive: {
        0: {
          items: 1,
          nav: true
        },
        600: {
          items: 1,
          nav: false
        },
        1000: {
          items: 1,
          nav: true,
          loop: false,
          margin: 10,
        }
      },
      slideBy: 1,
      dots: true,
      navigation: true,
    })
  }
  );
});
</script>

<?php
$is_identity = $this->viewer()->getIdentity();
$adcancel_enable = Engine_Api::_()->getApi('settings', 'core')->getSetting('adcancel.enable', 1);
 if($this->siteads_array):
foreach ($this->siteads_array as $site_ad):
  $div_id = $this->identity . $site_ad['userad_id'];
  $encode_adId = Engine_Api::_()->sitead()->getDecodeToEncode('' . $site_ad['userad_id'] . '');
  if (!empty($site_ad['resource_type']) && !empty($site_ad['resource_id'])):
    $resource_url = Engine_Api::_()->sitead()->resourceUrl($site_ad['resource_type'], $site_ad['resource_id']);
endif;
?>
<?php Engine_Api::_()->sitead()->adViewCount($site_ad['userad_id'], $site_ad['campaign_id']); ?>
<?php //endif;     ?>

<div class="cmd-weapper">
  <div id= "cmad_ad_cancel_<?php echo $div_id; ?>" class="cmadrem" style="display: none;">
    <div class="cmadrem_con">
      <div class="report-top">
        <h3><?php echo $site_ad['web_name']; ?></h3>
        <span class="cancel">
          <?php echo '<a class="" title="' . $this->translate('Cancel reporting this ad') . '" href="javascript:void(0);" onclick="adReportUndo(' . $div_id . ', \'cmad\');">' . $this->translate('<i class="fa fa-times-circle" aria-hidden="true"></i>') . '</a>'; ?>
        </span>
      </div>
      <div class="cmadrem_rl">
        <span><?php echo $this->translate("Do you want to report this? Why didn't you like it?"); ?></span>
      
      <form>
        <?php $ads_id = $encode_adId; ?>
        <div><input type="radio" name="adAction" value="0" onclick="adReportSave('Misleading', '<?php echo $ads_id; ?>', <?php echo $div_id; ?>, 'cmad')"/><?php echo $this->translate('Misleading'); ?></div>
        <div><input type="radio" name="adAction" value="1" onclick="adReportSave('Offensive', '<?php echo $ads_id; ?>', <?php echo $div_id; ?>, 'cmad')"/><?php echo $this->translate('Offensive'); ?></div>
        <div><input type="radio" name="adAction" value="2" onclick="adReportSave('Inappropriate', '<?php echo $ads_id; ?>', <?php echo $div_id; ?>, 'cmad')"/><?php echo $this->translate('Inappropriate'); ?></div>
        <div><input type="radio" name="adAction" value="3" onclick="adReportSave('Licensed Material', '<?php echo $ads_id; ?>', <?php echo $div_id; ?>, 'cmad')"/><?php echo $this->translate('Licensed Material'); ?></div>
        <div><input type="radio" name="adAction" value="4" onclick="otherAdReportCannel(4, '<?php echo $div_id; ?>', 'cmad')" id="cmad_other_<?php echo $div_id; ?>"/><?php echo $this->translate('Other'); ?></div>

        <div>
          <textarea name="cmad_other_text_<?php echo $div_id; ?>" onclick="this.value = ''" onblur="if (this.value == '')
          this.value = '<?php echo $this->string()->escapeJavascript($this->translate('Specify your reason here..')) ?>';"  id="cmad_other_text_<?php echo $div_id; ?>" style="display:none;" /><?php echo $this->translate('Specify your reason here..') ?></textarea>
        </div>

        <div>
          <?php echo '<a href="javascript:void(0);" onclick="adReportSave(\'Other\', \'' . $ads_id . '\', ' . $div_id . ', \'cmad\')" id="cmad_other_button_' . $div_id . '"  style="display:none" class="cmadrem_button">' . $this->translate('Report') . '</a>'; ?>
        </div>
      </form>
    </div>

    </div>  
  </div>
  <div class="cmd-info">
    <span class="add-oner">
       <div id="ad_icon">
        <?php
        // Title if has existence on site then "_blank" not work else work.
        if (!empty($site_ad['resource_type']) && !empty($site_ad['resource_id'])) {
          $set_target = '';
        } else {
          $set_target = 'target="_blank"';
        }
        ?>
      <a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>"  <?php echo $set_target ?>><?php echo $this->itemPhoto($site_ad, '', '') ?></a>
       </div>
    </span>
    <span class="add-title"> 
    <?php echo '<a href="' . $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) . '" ' . $set_target . ' >' . ucfirst($site_ad['web_name']) . "</a>";
    ?>
      <span class="sponsored-tag">
            <?php if($site_ad['sponsored']) {
                   echo 'Sponsored';
                 }
                   elseif ($site_ad['featured']) {
                     echo 'Featured';
                   } 
            ?>
      </span>
    </span>

    <?php  if (!empty($is_identity) && !empty($adcancel_enable)) { ?>
    <span class="feed-gut-opt">
      <i class="fa fa-ellipsis-v" aria-hidden="true" onclick="adReport('<?php echo $div_id; ?>', 'cmad');">
    </i></span>
  <?php } ?>
    
  </div>

  <?php 
  $i = 0;
  if($site_ad['cmd_ad_format'] == 'image') { 
    foreach ($this->siteadsinfo_array as $siteadsinfo) {
      if($siteadsinfo['userad_id'] == $site_ad['userad_id']) { 
          $encode_adId = Engine_Api::_()->sitead()->getDecodeToEncode($i. 'DESs' . $site_ad['userad_id'] . '');
        ?>
        <a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>"  <?php echo $set_target ?>><?php echo $this->htmlImage($siteadsinfo->getIconUrl()); ?></a>
        <div class="discription"> 
          <div class ="dis_bottom_wrp">
          <div class="cmd-info">
            <div class="add-heading">
              <?php 
              if (!empty($this->hideCustomUrl)) {
              if (!empty($site_ad['resource_type']) && !empty($site_ad['resource_id'])) {
                 if (!empty($resource_url['status'])) {
                   echo '<a href="' . $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) . '">' . $resource_url['title'] . "</a>";
                    } else {
                      echo $resource_url['title'];
                     }
                   } else { ?>
                      <a title="<?php echo $siteadsinfo['cads_url'] ?>" href="<?php echo $this->url(array('adId' => '$encode_adId'), 'sitead_adredirect', true) ?>" target="_blank" ><?php echo $this->translate(Engine_Api::_()->sitead()->truncation(Engine_Api::_()->sitead()->adSubTitle($siteadsinfo['cads_url']), 25)) ?></a>
                       <?php }
                     } ?>  
                  </div>
            <span class="add-title"><?php
          // Title if has existence on site then "_blank" not work else work.
            if (!empty($site_ad['resource_type']) && !empty($site_ad['resource_id'])) {
              $set_target = '';
            } else {
              $set_target = 'target="_blank"';
            }
            echo '<a href="' . $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) . '" ' . $set_target . ' >' . ucfirst($siteadsinfo['cads_title']) . "</a>";
            ?></span>
            <p><a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>"  <?php echo $set_target ?>><?php echo $siteadsinfo['cads_body'] ?></a></p>
          </div>
           <div class="call-to-action">
            <?php
            if($siteadsinfo['cta_button'] != 0 || $siteadsinfo['cta_button'] != '0') { ?>
              <a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>" <?php echo $set_target ?>> <?php echo $siteadsinfo['cta_button']; ?> </a>
            <?php }?>
          </div>
            </div>
            <a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>" <?php echo $set_target ?>> </a>
          </div>
        <?php }
      }
    } ?>

    <?php 
    $i = 0;
    if($site_ad['cmd_ad_format'] ==  'carousel') { ?>
      <div class="owl-carousel owl-theme <?php echo $this->carouselClass ?>">
        <?php foreach ($this->siteadsinfo_array as $siteadsinfo) {
          if($siteadsinfo['userad_id'] == $site_ad['userad_id']) {
           $encode_adId = Engine_Api::_()->sitead()->getDecodeToEncode($i. 'DESs' . $site_ad['userad_id'] . '');
           ?>
            <div class="sitead_owl">
              <?php if($siteadsinfo['overlay'] != 0 || !empty($siteadsinfo['overlay'])) { ?>
            <span class="ad_overlay"> <?php echo $siteadsinfo['overlay']; ?> </span>
        <?php } ?>
              <a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>"  <?php echo $set_target ?>><?php echo $this->htmlImage($siteadsinfo->getIconUrl()); ?></a>
              <div class="discription"> 
                <div class="dis_bottom_wrp">
                <div class="cmd-info">
                  <div class="add-heading">
              <?php 
              if (!empty($this->hideCustomUrl)) {
              if (!empty($site_ad['resource_type']) && !empty($site_ad['resource_id'])) {
                 if (!empty($resource_url['status'])) {
                   echo '<a href="' . $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) . '">' . $resource_url['title'] . "</a>";
                    } else {
                      echo $resource_url['title'];
                     }
                   } else { ?>
                      <a title="<?php echo $siteadsinfo['cads_url'] ?>" href="<?php echo $this->url(array('adId' => '$encode_adId'), 'sitead_adredirect', true) ?>" target="_blank" ><?php echo $this->translate(Engine_Api::_()->sitead()->truncation(Engine_Api::_()->sitead()->adSubTitle($siteadsinfo['cads_url']), 25)) ?></a>
                       <?php }
                     } ?>  
                  </div>
                  <span class="add-title"><?php
          // Title if has existence on site then "_blank" not work else work.
                  if (!empty($site_ad['resource_type']) && !empty($site_ad['resource_id'])) {
                    $set_target = '';
                  } else {
                    $set_target = 'target="_blank"';
                  }
                  echo '<a href="' . $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) . '" ' . $set_target . ' >' . ucfirst($siteadsinfo['cads_title']) . "</a>";
                  ?></span>
                  <p><a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>"  <?php echo $set_target ?>><?php echo $siteadsinfo['cads_body'] ?></a></p>
                </div>
          <div class="call-to-action">
          <?php
          if($siteadsinfo['cta_button'] != 0 || !empty($siteadsinfo['cta_button'])) { ?>
            <a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>" <?php echo $set_target ?>> <?php echo $siteadsinfo['cta_button']; ?> </a>
          <?php }?>  
          </div>
                  
                </div>
                  <a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>" <?php echo $set_target ?>> </a>
                </div>
              </div>
          <?php 
             $i++;
        }
        }?>
        </div>
      <?php } ?>

      <?php 
      $i = 0;
      if($site_ad['cmd_ad_format'] ==  'video') {
        foreach ($this->siteadsinfo_array as $siteadsinfo) {
          if($siteadsinfo['userad_id'] == $site_ad['userad_id']) {
            $encode_adId = Engine_Api::_()->sitead()->getDecodeToEncode($i. 'DESs' . $site_ad['userad_id'] . '');
           ?>
             <?php
              if (!empty($siteadsinfo->file_id)) {
          $storage_file = Engine_Api::_()->getItem('storage_file', $siteadsinfo->file_id);
          if ($storage_file) {
           $video_location = $storage_file->map();
         }
       }
             ?>
          <div class="video-ad-wrapper">
            <video id="video" controls  preload="auto">
                    <source type='video/mp4;' src="<?php echo $video_location ?>">
            </video>
          </div>
            <div class="discription"> 
              <div class="dis_bottom_wrp">
              <div class="cmd-info">
                <div class="add-heading">
              <?php 
              if (!empty($this->hideCustomUrl)) {
              if (!empty($site_ad['resource_type']) && !empty($site_ad['resource_id'])) {
                 if (!empty($resource_url['status'])) {
                   echo '<a href="' . $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) . '">' . $resource_url['title'] . "</a>";
                    } else {
                      echo $resource_url['title'];
                     }
                   } else { ?>
                      <a title="<?php echo $siteadsinfo['cads_url'] ?>" href="<?php echo $this->url(array('adId' => '$encode_adId'), 'sitead_adredirect', true) ?>" target="_blank" ><?php echo $this->translate(Engine_Api::_()->sitead()->truncation(Engine_Api::_()->sitead()->adSubTitle($siteadsinfo['cads_url']), 25)) ?></a>
                       <?php }
                     } ?>  
                  </div>
                <span class="add-title"><?php
                        // Title if has existence on site then "_blank" not work else work.
                if (!empty($site_ad['resource_type']) && !empty($site_ad['resource_id'])) {
                  $set_target = '';
                } else {
                  $set_target = 'target="_blank"';
                }
                echo '<a href="' . $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) . '" ' . $set_target . ' >' . ucfirst($siteadsinfo['cads_title']) . "</a>";
                ?></span>
                <p><a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>"  <?php echo $set_target ?>><?php echo $siteadsinfo['cads_body'] ?></a></p>
              </div>
        <div class="call-to-action">
          <?php
          if($siteadsinfo['cta_button'] != 0 || $siteadsinfo['cta_button'] != '0') { ?>
            <a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>" <?php echo $set_target ?>> <?php echo $siteadsinfo['cta_button']; ?> </a>
          <?php }?>
          </div>
        </div>
            <a href="<?php echo $this->url(array('adId' => $encode_adId), 'sitead_adredirect', true) ?>" <?php echo $set_target ?>> </a>
              </div>
            <?php }
          }
        }?>
      </div>
    <?php endforeach; ?>
<?php endif; ?>
