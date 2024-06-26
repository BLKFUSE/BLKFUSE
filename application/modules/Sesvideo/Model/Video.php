<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Video.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Model_Video extends Core_Model_Item_Abstract {

  protected $_parent_type = 'user';
  protected $_owner_type = 'user';
  protected $_parent_is_owner = true;
  protected $_type = 'video';
  public function getHref($params = array()) {
    $params = array_merge(array(
        'route' => 'sesvideo_view',
        'reset' => true,
        'user_id' => $this->owner_id,
        'video_id' => $this->video_id,
        'slug' => $this->getSlug(),
            ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
  }

  public function getFilePath() {
    $file = Engine_Api::_()->getItem('storage_file', $this->file_id);
    if ($file)
      return $file->map();
  }
  public function getTitle(){
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesemoji')) {
      return Engine_Api::_()->sesemoji()->DecodeEmoji($this->title);
    }
    return $this->title;
  }
  public function getDescription(){
    if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesemoji')) {
      return Engine_Api::_()->sesemoji()->DecodeEmoji($this->description);
    }
    return $this->description;
  }
  public function fields() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getApi('core', 'fields'));
  }

  public function getRichContent($view = false, $params = array(), $map = false,$autoplay = true) {
    $session = new Zend_Session_Namespace('mobile');
    $viewer = Engine_Api::_()->user()->getViewer();
    $mobile = $session->mobile;
    if ($this->type == 'iframely' || $this->type == 17 ) {
      $videoEmbedded = $this->code;
        if($map){
            preg_match('/src="([^"]+)"/', $this->code, $match);
            if(!empty($match[1]))
                return $match[1];
            else{
                return "";
            }
        }
    }
    // if video type is youtube
    if ($this->type == 1 || $this->type == 'youtube') {
      $videoEmbedded = $this->compileYouTube($this->video_id, $this->code, $view, $mobile, $map,$autoplay);
    }
    // if video type is vimeo
    if ($this->type == 2 || $this->type == 'vimeo') {
      $videoEmbedded = $this->compileVimeo($this->video_id, $this->code, $view, $mobile, $map,$autoplay);
    }
    // if video type is uploaded
    if ($this->type == 3 || $this->type == 'upload') {
      
      //Video sell work
      
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell') && $this->type == 3 && $this->price > 0 && $viewer->getIdentity() != $this->owner_id) {
        $sampleVideo = Engine_Api::_()->storage()->get($this->file_id, $this->getType());
        //$sampleVideo = Engine_Api::_()->getItem('sesvideo_samplevideo', $this->samplevideo_id);
        $storage_file = Engine_Api::_()->storage()->get($sampleVideo->file_id, $this->getType());
      }
      //Video sell work
      else {
        $storage_file = Engine_Api::_()->storage()->get($this->file_id, $this->getType());
      }

      if($storage_file){
        $video_location = $storage_file->getHref();
        if ($storage_file->extension === 'flv') {
          $videoEmbedded = $this->compileFlowPlayer($video_location, $view, $map,$autoplay);
        } else {
          $videoEmbedded = $this->compileHTML5Media($video_location, $view, $map,$autoplay);
        }
      }
    }
    // if video type is dailymotion
    if ($this->type == 4) {
      $videoEmbedded = $this->compileDailymotion($this->video_id, $this->code, $view, $mobile, $map,$autoplay);
    }
    // if video is redtube
    if ($this->type == 5) {
      $videoEmbedded = $this->compileRedTube($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is xvideos
    if ($this->type == 6) {
      $videoEmbedded = $this->compileXvideos($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is Xhamster
    if ($this->type == 7) {
      $videoEmbedded = $this->compileXhamster($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is Youjizz
    if ($this->type == 8) {
      $videoEmbedded = $this->compileYoujizz($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is Tnaflix
    if ($this->type == 9) {
      $videoEmbedded = $this->compileTnaflix($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is Slutload
    if ($this->type == 10) {
      $videoEmbedded = $this->compileSlutload($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is Youporn
    if ($this->type == 11) {
      $videoEmbedded = $this->compileYouporn($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is Pornhub
    if ($this->type == 12) {
      $videoEmbedded = $this->compilePornhub($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is IndianPornVideos
    if ($this->type == 13) {
      $videoEmbedded = $this->compileIndianPornVideos($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is Empflix
    if ($this->type == 14) {
      $videoEmbedded = $this->compileEmpflix($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is PornRabbit
    if ($this->type == 15) {
      $videoEmbedded = $this->compilePornRabbit($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is url
    if ($this->type == 16) {
      $videoEmbedded = $this->compileFromUrl($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is url
    // if ($this->type == 17) {
    //   $videoEmbedded = $this->compileEmbedCode($this->video_id, $this->code, $view, $mobile, $map);
    // }
    // if video is break
    if ($this->type == 18) {
      $videoEmbedded = $this->compileBreak($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is commedy central
    if ($this->type == 20) {
      $videoEmbedded = $this->commedycentral($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is metacafe
    if ($this->type == 21) {
      $videoEmbedded = $this->metacafe($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is veehd
    if ($this->type == 22) {
      $videoEmbedded = $this->veoh($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is veehd
    if ($this->type == 23) {
      $videoEmbedded = $this->veehd($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is 4shared
    if ($this->type == 24) {
      $videoEmbedded = $this->shared4($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is youku
    if ($this->type == 25) {
      $videoEmbedded = $this->youku($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is myspace
    if ($this->type == 26) {
      $videoEmbedded = $this->myspace($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is stagevu
    if ($this->type == 27) {
      $videoEmbedded = $this->stagevu($this->video_id, $this->code, $view, $mobile, $map);
    }
      // if video is rutube
    if ($this->type == 28) {
      $videoEmbedded = $this->rutube($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is videobash
    if ($this->type == 29) {
      $videoEmbedded = $this->videobash($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is spike
    if ($this->type == 30) {
      $videoEmbedded = $this->spike($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is spike
    if ($this->type == 31) {
      $videoEmbedded = $this->clipfish($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is godtube
    if ($this->type == 32) {
      $videoEmbedded = $this->godtube($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is godtube
    if ($this->type == 33) {
      $videoEmbedded = $this->nuvid($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is vid2c
    if ($this->type == 34) {
      $videoEmbedded = $this->vid2c($this->video_id, $this->code, $view, $mobile, $map);
    }
    
    // if video is drtuber
    if ($this->type == 35) {
      $videoEmbedded = $this->drtuber($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is porn
    if ($this->type == 100) {
      $videoEmbedded = $this->porn($this->video_id, $this->code, $view, $mobile, $map);
    }
    // if video is facebook
    if ($this->type == 105) {
      $videoEmbedded = $this->facebook($this->video_id, $this->code, $view, $mobile, $map);
    }
      // if video is twitter
    if ($this->type == 106) {
      $videoEmbedded = $this->twitter($this->video_id, $this->code, $view, $mobile, $map);
    }
    // $view == false means that this rich content is requested from the activity feed
    if ($view == false) {
      // prepare the duration
      $video_duration = "";
      if ($this->duration) {
        if ($this->duration >= 3600) {
          $duration = gmdate("H:i:s", $this->duration);
        } else {
          $duration = gmdate("i:s", $this->duration);
        }
        $duration = ltrim($duration, '0:');

        $video_duration = "<span class='sesvideo_length'>" . $duration . "</span>";
      }
       $watchLater = '';
      $watchLaterId = Engine_Api::_()->sesvideo()->getWatchLaterId($this->video_id);
       if(Engine_Api::_()->user()->getViewer()->getIdentity() != '0' && Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.watchlater', 1)){
            $watchLaterActive = engine_count($watchLaterId)  ? 'selectedWatchlater' : '';
            $watchLaterText = engine_count($watchLaterId)  ? Zend_Registry::get('Zend_Translate')->_('Remove from Watch Later')  : Zend_Registry::get('Zend_Translate')->_('Add to Watch Later');
           $watchLater =   '<a href="javascript:;" class="sesvideo_watch_later_btn sesvideo_watch_later '.$watchLaterActive.'" title = "'.$watchLaterText.'" data-url="'.$this->video_id.'"></a>';
       }
       $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
       $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->getHref());
        $buttons = '<div class="sesvideo_thumb_btns">';
        
        $buttons .= $view->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this, 'param' => 'feed'));
        
        if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 ) {
          $thistype = 'sesvideo_video';
          $getId = 'video_id';
          $canComment =  $this->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
          if($canComment) {
            $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($this->$getId,$this->getType()); 
            $likeText = ($LikeStatus) ? 'button_active' : '';
            $buttons  .= ' <a href="javascript:;" data-url="'. $this->$getId .'" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_sesvideo_video '.$likeText.'"> <i class="fa fa-thumbs-up"></i><span>'. $this->like_count.'</span></a>';
          } ;
          $favAllow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1);
          if($favAllow && isset($this->favourite_count)){ 
            $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>$thistype,'resource_id'=>$this->$getId)); 
            $favText = ($favStatus)  ? 'button_active' : '';
            $buttons .= '<a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_sesvideo_video '.$favText .'"  data-url="'.$this->$getId.'"><i class="fa fa-heart"></i><span>'. $this->favourite_count.'</span></a>';
          }
          if(Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->user()->getViewer()->level_id, 'video', 'addplayl_video')) {
              $buttons .= '<a href="javascript:;" onclick="opensmoothboxurl(\'' . $view->url(array('action' => 'add', 'module' => 'sesvideo', 'controller' => 'playlist', 'video_id' => $this->video_id), 'default', true) . '\');return false;" class="sesbasic_icon_btn sesvideo_add_playlist"  title="' . Zend_Registry::get('Zend_Translate')->_('Add To Playlist') . '" data-url="' . $this->video_id . '"><i class="fa fa-plus"></i></a>';
          }

        }
      $buttons .= ' </div>';
      $paidContent = '';
      
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && isset($this->package_id) && !empty($this->package_id) && $this->owner_id != $viewer->getIdentity()) {
      
        $package = Engine_Api::_()->getItem('epaidcontent_package', $this->package_id);
        
        $getViewerOrder = Engine_Api::_()->getDbTable('orders','epaidcontent')->getViewerOrder(array('owner_id' => $viewer->getIdentity(),'package_owner_id' => $package->user_id, 'noCondition' => 1));

        if((float) $getViewerOrder->total_amount < (float) $package->price) {
          $paidContent = '<div class="epaidcontent_attachment"> <div class="epaidcontent_attachment_subscription_box">
          <div class="epaidcontent_attachment_subscription_box_lock"><i class="fas fa-lock"></i></div>
          <span> Paid Content </span>' . '<a href="'.$view->url(array('action' => 'showpackage', 'package_id' => $this->package_id), 'epaidcontent_general', true).'">'. Zend_Registry::get('Zend_Translate')->_('Subscribe Package '). Engine_Api::_()->payment()->getCurrencyPrice($package->price, Engine_Api::_()->epaidcontent()->defaultCurrency()) .'</a></div>';
        }
      }
      
      // prepare the thumbnail
      $thumb = Zend_Registry::get('Zend_View')->itemPhoto($this, 'thumb.video.activity');
      $thumb = '<a class="sesvideo_thumb_img sesvideo_lightbox_open sesvideo_attachment_thumb_img" href="'.$this->getHref().'" data-url="video"><span style="background-image:url(' . $this->getPhotoUrl() . ');"></span></a><a class="sesvideo_play_btn fa fa-play-circle sesvideo_thumb_img sesvideo_lightbox_open" href="'.$this->getHref().'" data-url="video"><span style="background-image:url(' . $this->getPhotoUrl() . ');display:none"></span></a>'.$video_duration.$watchLater.$buttons;
      if (!$mobile && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.enable.lightbox', 1)) {
        $thumb = '<div class="sesvideo_thumb sesvideo_attachment_thumb sesvideo_play_btn_wrap sesvideo_activity_video sesbasic_bxs" id="video_thumb_' . $this->video_id . '" >' . $thumb  . '<a href="'.$this->getHref().'" data-url="video" class="sesvideo_feed_expend sesvideo_lightbox_open" data-click="openclick"><span style="background-image:url(' . $this->getPhotoUrl() . ');"></span><span >Open in Lightbox</span><i class="fas fa-expand"></i></a></div>';
      } else {
        $thumb = '<div class="sesvideo_thumb sesvideo_attachment_thumb sesvideo_play_btn_wrap sesvideo_activity_video sesbasic_bxs" id="video_thumb_' . $this->video_id . '" href="' . $this->getHref() . '">' . $thumb  . '</div>';
      }
      // prepare title and description
      if (!$mobile) {
        $popuplink ='<a href="'.$this->getHref().'" data-url="video" class="sesvideo_feed_expend sesvideo_lightbox_open" data-click="openclick"><span style="background-image:url(' . $this->getPhotoUrl() . ');"></span><span >Open in Lightbox</span><i class="fas fa-expand"></i></a>';
      }
      $title = "<a class='sesvideo_attachment_title' href='" . $this->getHref($params) . "'>$this->title</a>";
      $tmpBody = strip_tags($this->description);
      $description = "<div class='sesvideo_attachment_desc'>" . (Engine_String::strlen($tmpBody) > 255 ? Engine_String::substr($tmpBody, 0, 255) . '...' : $tmpBody) . "</div>";
      
      $videoEmbedded = $paidContent . $thumb . '<div id="video_object_' . $this->video_id . '" data-rel="'.$this->type.'" class="sesvideo_object">'.$videoEmbedded.'</div><div class="sesvideo_attachment_info">' . $popuplink . $title . $description . '</div>';
      
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && isset($this->package_id) && !empty($this->package_id) && $this->owner_id != $viewer->getIdentity()) {
        $package = Engine_Api::_()->getItem('epaidcontent_package', $this->package_id);
        $getViewerOrder = Engine_Api::_()->getDbTable('orders','epaidcontent')->getViewerOrder(array('owner_id' => $viewer->getIdentity(),'package_owner_id' => $package->user_id, 'noCondition' => 1));
        if((float) $getViewerOrder->total_amount < (float) $package->price) {
          $videoEmbedded .= '</div>';
        }
      }
    }
    
    
    return $videoEmbedded;
  }

  /**
   * Gets a url to the current video representing this item. Return null if none
   * set
   *
   * @param string The video type;
   * @return string The video photo url
   * */
  public function getPhotoUrl($type = null) {
    $defaultPhoto = Engine_Api::_()->sesvideo()->getFileUrl(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_video_default_image', 'application/modules/Sesvideo/externals/images/video.png'));
    $photo_id = $this->photo_id;
    if (!$photo_id && !$this->is_locked && !$this->adult)
      return $defaultPhoto;
    $viewer = Engine_Api::_()->user()->getViewer();
    if(!Engine_Api::_()->getApi('core', 'sesbasic')->checkAdultContent(array('module'=>'video')) && $this->adult && $viewer->getIdentity() != $this->owner_id){
       return Engine_Api::_()->sesvideo()->getFileUrl(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_video_default_adult', 'application/modules/Sesvideo/externals/images/sesvideo_adult.png')); 
    }
    $cookieData = isset($_COOKIE['sesvideo_lightbox_value']) ? (array) $_COOKIE['sesvideo_lightbox_value'] : array();
    
    $valid = false;
    if($cookieData && engine_in_array($this->video_id,@explode(',',$cookieData))){
      $valid = true;
    }
    
    if ($viewer->getIdentity() == 0)
      $level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    else
      $level = $viewer;
    $sesprofilelock_enable_module = is_string(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}')) ? unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}')) : Engine_Api::_()->getApi('settings', 'core')->getSetting('sesprofilelock.enable.modules', 'a:2:{i:0;s:8:"sesvideo";i:1;s:8:"sesalbum";}');;
    if (!$valid && $this->is_locked && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('sesprofilelock'))  && engine_in_array('sesvideo',$sesprofilelock_enable_module) && $viewer->getIdentity() != $this->owner_id)
      return Zend_Registry::get('StaticBaseUrl').'application/modules/Sesvideo/externals/images/locked-video.jpg';
    
//    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesvideo',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($this)) {
//      return 'application/modules/Epaidcontent/externals/images/paidcontent.png';
//    }
    
    //Video sell work
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideosell') && $this->type == 3 && $this->price > 0 && $viewer->getIdentity() != $this->owner_id) {
      $sampleVideo = Engine_Api::_()->getItem('sesvideo_samplevideo', $this->samplevideo_id);
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($sampleVideo->photo_id, $type);
      if (!$file)
        return $defaultPhoto;
    }
    //Video sell work
    else {
    $file = Engine_Api::_()->getItemTable('storage_file')->getFile($photo_id, $type);
    if (!$file)
      return $defaultPhoto;
    }
    return $file->map();
  }

  public function getEmbedCode(array $options = null) {
    $options = array_merge(array(
        'height' => '525',
        'width' => '525',
            ), (array) $options);

    $view = Zend_Registry::get('Zend_View');
    $url = 'http://' . $_SERVER['HTTP_HOST']
            . Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                'module' => 'sesvideo',
                'controller' => 'video',
                'action' => 'external',
                'video_id' => $this->getIdentity(),
                    ), 'default', true) . '?format=frame';
    return '<iframe '
            . 'src="' . $view->escape($url) . '" '
            . 'width="' . sprintf("%d", $options['width']) . '" '
            . 'height="' . sprintf("%d", $options['width']) . '" '
            . 'style="overflow:hidden;"'
            . '>'
            . '</iframe>';
  }
  public function twitter($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    $url = 'http://' . $_SERVER['HTTP_HOST']
            . Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                'module' => 'sesvideo',
                'controller' => 'video',
                'action' => 'outerurl',
                'video_id' => $this->getIdentity(),
                    ), 'default', true) . '?format=frame';
    if ($map)
      return $url;
    $embedded = '
    <iframe
    title="Twitter video player"
    id="videoFrame' . $video_id . '"
    class="titter_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="'.$url.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
          if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }
  public function facebook($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    $url = 'http://' . $_SERVER['HTTP_HOST']
            . Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                'module' => 'sesvideo',
                'controller' => 'video',
                'action' => 'outerurl',
                'video_id' => $this->getIdentity(),
                    ), 'default', true) . '?format=frame';
    if ($map)
      return $url;
    $embedded = '
    <iframe
    title="Facebook video player"
    id="videoFrame' . $video_id . '"
    class="facebook_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="'.$url.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
          if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }
  public function porn($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.porn.com/videos/embed/' . $code;
    $embedded = '
    <iframe
    title="Porn video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.porn.com/videos/embed/' . $code. '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
          if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }
  
  public function compileRedTube($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//embed.redtube.com/?id=' . $code . '&wmode=opaque&style=redtube&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//embed.redtube.com/?id=' . $code . '&wmode=opaque&style=redtube&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
          if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }
  public function compileEmbedCode($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return $code;
    $embedded = '
    <iframe
    title="Embed code video player"
    id="videoFrame' . $video_id . '"
    class="url_iframe' . ($view ? "_big" : "_small") . '"' .
    'src=" '.$code . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
            var height = parentSize.x / aspect;
              var width = parentSize.x;
              if(width == 0){
                 setTimeout(function(){ doResize(); }, 1000); 
              }
              var marginTop = 0;
              if(scriptJquery(".sesvideo_view_embed").find("iframe").length){
                if(scriptJquery(".sesvideo_view_embed").find("iframe").attr("src").indexOf("?") > 0){
                  var urlQuery = "&width="+width+"&height="+parseInt(height-marginTop);
                }else
                  var urlQuery = "?width="+width+"&height="+parseInt(height-marginTop);
                var srcAttr = scriptJquery(".sesvideo_view_embed").find("iframe").attr("src")+urlQuery;
                scriptJquery(".sesvideo_view_embed").find("iframe").attr("src",srcAttr);
              }
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }
  public function compileFromUrl($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return $code.'?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Url video player"
    id="videoFrame' . $video_id . '"
    class="url_iframe' . ($view ? "_big" : "_small") . '"' .
            'src=" '.$code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  
  }
  public function compileXvideos($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//flashservice.xvideos.com/embedframe/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//flashservice.xvideos.com/embedframe/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compileXhamster($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//xhamster.com/xembed.php?video=' . $code . '&wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//xhamster.com/xembed.php?video=' . $code . '&wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compileYoujizz($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.youjizz.com/videos/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.youjizz.com/videos/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compileTnaflix($video_id, $code, $view, $mobile = false, $map = true) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//player.tnaflix.com/video/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//player.tnaflix.com/video/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compileSlutload($video_id, $code, $view, $mobile = false, $map = true) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.slutload.com/embed_player/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.slutload.com/embed_player/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compileYouporn($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.youporn.com/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.youporn.com/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compilePornhub($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.pornhub.com/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.pornhub.com/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compileIndianPornVideos($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.indianpornvideos.com/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.indianpornvideos.com/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compileEmpflix($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//player.empflix.com/video/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//player.empflix.com/video/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }
    
    public function rutube  ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//rutube.ru/play/embed/' . $code;
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//rutube.ru/play/embed/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  
  public function spike ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//media.mtvnservices.com/embed/mgid:arc:video:spike.com:' . $code;
    $embedded = '
    <iframe
    title="spike video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//media.mtvnservices.com/embed/mgid:arc:video:spike.com:'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  public function vid2c ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.vid2c.com/embed/' . $code;
    $embedded = '
    <iframe
    title="vid2c video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.vid2c.com/embed/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  public function nuvid ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//http://www.nuvid.com/embed/' . $code;
    $embedded = '
    <iframe
    title="nuvid video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//http://www.nuvid.com/embed/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  public function clipfish  ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.clipfish.de/embed/' . $code;
    $embedded = '
    <iframe
    title="clipfish video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//http://www.clipfish.de/embed/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  
  public function godtube ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.godtube.com/embed/watch/' . $code;
    $embedded = '
    <iframe
    title="godtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.godtube.com/embed/watch/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  
   public function videobash  ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.videobash.com/embed/' . $code;
    $embedded = '
    <iframe
    title="videobash video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.videobash.com/embed/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  
   public function veoh ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.veoh.com/embed/' . $code;
    $embedded = '
    <iframe
    title="veoh video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.veoh.com/embed/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  
   public function drtuber  ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.drtuber.com/embed/' . $code;
    $embedded = '
    <iframe
    title="drtuber video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.drtuber.com/embed/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  
  
  public function youku($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//player.youku.com/embed/' . $code;
    $embedded = '
    <iframe
    title="youku video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//player.youku.com/embed/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  public function shared4($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.4shared.com/web/embed/file/' . $code;
    $embedded = '
    <iframe
    title="shared4 video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.4shared.com/web/embed/file/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  public function veehd ($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//veehd.com/embed?t=3&v=' . $code;
    $embedded = '
    <iframe
    title="veehd video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//veehd.com/embed?t=3&v='. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  public function stagevu($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//stagevu.com/embed?uid=' . $code;
    $embedded = '
    <iframe
    title="stagevu video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//stagevu.com/embed?uid='. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
   public function myspace($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//media.myspace.com/play/video/' . $code;
    $embedded = '
    <iframe
    title="myspace video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//media.myspace.com/play/video/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  public function metacafe($video_id, $code, $view, $mobile = false, $map = false){
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.metacafe.com/embed/' . $code;
    $embedded = '
    <iframe
    title="metacafe video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.metacafe.com/embed/'. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;   
  }
  public function commedycentral($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//media.mtvnservices.com/embed/mgid:arc:video:comedycentral.com:' . $code;
    $embedded = '
    <iframe
    title="commedycentral video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//media.mtvnservices.com/embed/mgid:arc:video:comedycentral.com: '. $code.'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }
    public function compileBreak($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.break.com/embed/' . $code . '?embed=1&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="compileBreak video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.break.com/embed/' . $code . '?embed=1&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compilePornRabbit($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.pornrabbit.com/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="PornRabbit video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.pornrabbit.com/embed/' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';
    return $embedded;
  }

  public function compileDailymotion($video_id, $code, $view, $mobile = false, $map = false,$autoPlayView = true) {
    $autoplay = !$mobile && $view;
    if(!$autoPlayView)
      $autoplay = $autoPlayView;
    if ($map)
      return '//www.dailymotion.com/embed/video/' . $code;
    $embedded = '
    <iframe
    title="Dailymotion video player"
    id="videoFrame' . $video_id . '"
    class="dailymotion_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.dailymotion.com/embed/video/' . $code . '"
    frameborder="0"
    allowfullscreen=""
    ' . ($autoplay ? "autoplay=1" : "") . '
    >
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame' . $video_id . '");
            if(typeof el == "undefined" || !el)
            return;
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';

    return $embedded;
  }

  public function compileYouTube($video_id, $code, $view, $mobile = false, $map = false,$autoPlayView = true) {
    $autoplay = !$mobile && $view;

    $embedded = '
    <iframe
    title="YouTube video player"
    id="videoFrame'.$videoId.'"
    class="youtube_iframe'.($view?"_big":"_small").'"'.
    'src="//www.youtube.com/embed/'.$code.'?wmode=opaque'.($autoplay?"&autoplay=1":"").'"
    frameborder="0"
    allowfullscreen=""
    scrolling="no">
    </iframe>
    <script type="text/javascript">
        en4.core.runonce.add(function() {
        var doResize = function() {
            var aspect = 16 / 9;
            var el = document.id("videoFrame'.$videoId.'");
            var parent = el.getParent();
            var parentSize = parent.getSize();
            el.set("width", parentSize.x);
            el.set("height", parentSize.x / aspect);
        }
        scriptJquery(window).on("resize", doResize);
        doResize();
        });
    </script>
    ';

    return $embedded;
  }
  public function compileVimeo($video_id, $code, $view, $mobile = false, $map = false,$autoPlayView = true) {
    $autoplay = !$mobile && $view;
    if(!$autoPlayView)
      $autoplay = $autoPlayView;
    if ($map)
      return '//player.vimeo.com/video/' . $code . '?api=1&title=0&amp;byline=0&amp;portrait=0&amp;wmode=opaque' . ($autoplay ? "&amp;autoplay=1" : "");
    $embedded = '
        <iframe
        title="Vimeo video player"
        id="videoFrame' . $video_id . '"
        class="vimeo_iframe' . ($view ? "_big" : "_small") . '"' .
            ' src="//player.vimeo.com/video/' . $code . '?api=1&title=0&amp;byline=0&amp;portrait=0&amp;wmode=opaque' . ($autoplay ? "&amp;autoplay=1" : "") . '"
        frameborder="0"
        allowfullscreen=""
        scrolling="no">
        </iframe>
        <script type="text/javascript">
          en4.core.runonce.add(function() {
            var doResize = function() {
              var aspect = 16 / 9;
              var el = document.id("videoFrame' . $video_id . '");
              if(typeof el == "undefined" || !el)
            return;
              var parent = el.getParent();
              var parentSize = parent.getSize();
              el.set("width", parentSize.x);
              el.set("height", parentSize.x / aspect);
            }
            scriptJquery(window).on("resize", doResize);
            doResize();
          });
        </script>
        ';
    return $embedded;
  }
  
  public function compileHTML5Media($location, $view, $map = false) {

    if ($map)
      return $location;
      
    $embedded = "
    <video class='video_upload_small' id='video".$this->video_id."' controls preload='auto' width='".($view?"480":"420")."' height='".($view?"386":"326")."'>
      <source type='video/mp4;' src=".$location.">
    </video>";
    return $embedded;
  }
  public function compileFlowPlayer($location, $view, $map = false,$autoplay = false) {
    if ($map)
      return;
      
    $flowplayer = Engine_Api::_()->sesbasic()->checkPluginVersion('core', '4.8.10') ? 'externals/flowplayer/flowplayer-3.2.18.swf' : 'externals/flowplayer/flowplayer-3.1.5.swf';
      
    $embedded = "
    <div id='videoFrame" . $this->video_id . "'></div>
    <script type='text/javascript'>
    en4.core.runonce.add(function(){\$('video_thumb_" . $this->video_id . "').removeEvents('click').addEvent('click', function(){checkFunctionEmbed();flashembed('videoFrame$this->video_id',{src: '" . Zend_Registry::get('StaticBaseUrl') . $flowplayer. "', width: " . ($view ? "480" : "420") . ", height: " . ($view ? "386" : "326") . ", wmode: 'opaque'},{config: {clip: {url: '$location',autoPlay: " . ($view ? "false" : "true") . ", duration: '$this->duration', autoBuffering: true},plugins: {controls: {background: '#000000',bufferColor: '#333333',progressColor: '#444444',buttonColor: '#444444',buttonOverColor: '#666666'}},canvas: {backgroundColor:'#000000'}}});})});
    </script>";

    return $embedded;
  }

  public function getKeywords($separator = ' ') {
    $keywords = array();
    foreach ($this->tags()->getTagMaps() as $tagmap) {
      $tag = $tagmap->getTag();
      if($tag)
      $keywords[] = $tag->getTitle();
    }

    if (null === $separator) {
      return $keywords;
    }

    return join($separator, $keywords);
  }

  // Interfaces

  /**
   * Gets a proxy object for the comment handler
   *
   * @return Engine_ProxyObject
   * */
  public function comments() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('comments', 'core'));
  }

  /**
   * Gets a proxy object for the like handler
   *
   * @return Engine_ProxyObject
   * */
  public function likes() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('likes', 'core'));
  }

  /**
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   * */
  public function tags() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('tags', 'core'));
  }

}
