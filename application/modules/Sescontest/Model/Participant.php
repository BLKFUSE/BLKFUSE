<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Participant.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Model_Participant extends Core_Model_Item_Abstract {

  protected $_owner_type = 'user';
  protected $_parent_type = 'contest';
  protected $_type = 'participant';
  protected $_searchTriggers = false;

  public function getParent($recurseType = null) {
    return Engine_Api::_()->getItem('contest', $this->contest_id);
  }

  public function getParentContest() {
    return $this->getParent();
  }

  public function _postInsert() {
    parent::_postInsert();
    // Create auth stuff
    $context = Engine_Api::_()->authorization()->context;
    $context->setAllowed($this, 'everyone', 'view', true);
    $context->setAllowed($this, 'registered', 'comment', true);
    $viewer = Engine_Api::_()->user()->getViewer();
  }

  public function getDescription($length = 255) {
    // @todo decide how we want to handle multibyte string functions
    $tmpBody = strip_tags($this->description);
    return ( Engine_String::strlen($tmpBody) > $length ? Engine_String::substr($tmpBody, 0, $length) . '...' : $tmpBody );
  }

  public function getTitle() {
    return $this->title;
  }

  /**
   * Gets an absolute URL to the page to view this item
   *
   * @return string
   */
  public function getHref($params = array()) {
    $params = array_merge(array(
        'route' => 'sescontest_entry_profile',
        'reset' => true,
        'contest_id' => Engine_Api::_()->getItem('contest', $this->contest_id)->custom_url,
        'id' => $this->getIdentity(),
            ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
                    ->assemble($params, $route, $reset);
  }

  public function setPhoto($photo, $photoType = 0) {
    if ($photoType != 2) {
      if ($photo instanceof Zend_Form_Element_File) {
        $file = $photo->getFileName();
      } else if ($photo instanceof Storage_Model_File) {
        $file = $photo->temporary();
        $fileName = $photo->name;
      } elseif (is_array($photo) && !empty($photo['tmp_name'])) {
        $file = $photo['tmp_name'];
        $name = $photo['name'];
      } elseif (is_string($photo) && file_exists($photo)) {
        $file = $photo;
      } else {
        throw new Sescontest_Model_Exception('Invalid argument passed to setPhoto: ' . print_r($photo, 1));
      }
      if (empty($name))
        $name = basename($file);
    } else {
      $fileName = time() . '_sescontest';
      $imageName = $photo;
      $photo = current(explode('?', $photo));
      $PhotoExtension = '.' . pathinfo($photo, PATHINFO_EXTENSION);
      if ($PhotoExtension == ".")
        $PhotoExtension = ".jpg";
      $filenameInsert = $fileName . $PhotoExtension;
      $fileName = $filenameInsert;
      $copySuccess = @copy($imageName, APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary/' . $filenameInsert);
      if ($copySuccess)
        $file = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary' . DIRECTORY_SEPARATOR . $filenameInsert;
      else
        return false;
      $name = basename($photo);
    }

    $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary';
    $params = array(
        'parent_type' => 'participant',
        'parent_id' => $this->getIdentity()
    );

    // Save
    $storage = Engine_Api::_()->storage();
    $core_settings = Engine_Api::_()->getApi('settings', 'core');
    $main_height = $core_settings->getSetting('sescontest.mainheight', 1600);
    $main_width = $core_settings->getSetting('sescontest.mainwidth', 1600);
    $normal_height = $core_settings->getSetting('sescontest.normalheight', 500);
    $normal_width = $core_settings->getSetting('sescontest.normalwidth', 500);

    // Resize image (main)
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize($main_width, $main_height)
            ->write($path . '/m_' . $name)
            ->destroy();

    // Resize image (profile)
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize($normal_width, $normal_height)
            ->write($path . '/p_' . $name)
            ->destroy();

    // Resize image (normal)
    $image = Engine_Image::factory();
    $image->open($file)
            ->resize($normal_width, $normal_height)
            ->write($path . '/in_' . $name)
            ->destroy();

    // Resize image (icon)
    $image = Engine_Image::factory();
    $image->open($file);

    $size = min($image->height, $image->width);
    $x = ($image->width - $size) / 2;
    $y = ($image->height - $size) / 2;

    $image->resample($x, $y, $size, $size, 48, 48)
            ->write($path . '/is_' . $name)
            ->destroy();

    // Store
    $iMain = $storage->create($path . '/m_' . $name, $params);
    $iProfile = $storage->create($path . '/p_' . $name, $params);
    $iIconNormal = $storage->create($path . '/in_' . $name, $params);
    $iSquare = $storage->create($path . '/is_' . $name, $params);

    $iMain->bridge($iProfile, 'thumb.profile');
    $iMain->bridge($iIconNormal, 'thumb.normal');
    $iMain->bridge($iSquare, 'thumb.icon');

    // Remove temp files
    @unlink($path . '/p_' . $name);
    @unlink($path . '/m_' . $name);
    @unlink($path . '/in_' . $name);
    @unlink($path . '/is_' . $name);

    // Update row
    if ($photoType == 1)
      $this->main_photo_id = $iMain->getIdentity();
    else
      $this->file_id = $iMain->getIdentity();
    $this->save();

    return $this;
  }

  public function getPhotoUrl($type = null) {
    $contestType = Engine_Api::_()->getItem('contest', $this->contest_id)->contest_type;

    if ($this->main_photo_id)
      $sourceId = $this->main_photo_id;
    elseif ($contestType == 3)
      $sourceId = $this->photo_id;
    else {
      $sourceId = $this->file_id;
    }

    if ($sourceId) {
      $file = Engine_Api::_()->getItemTable('storage_file')->getFile($sourceId, $type);
      if ($file)
        return $file->map();
      else {
        $file = Engine_Api::_()->getItemTable('storage_file')->getFile($sourceId, 'thumb.profile');
        if ($file)
          return $file->map();
      }
    }
    if ($contestType == 1)
      $type = 'textEntryPhoto';
    elseif ($contestType == 2)
      $type = 'photoEntryPhoto';
    elseif ($contestType == 3)
      $type = 'videoEntryPhoto';
    else
      $type = 'musicEntryPhoto';
    $defaultPhoto = Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->getItem('user', $this->owner_id), 'participant', $type);
    if (!$defaultPhoto) {
      $defaultPhoto = 'application/modules/Sescontest/externals/images/nophoto_contest_thumb_profile.png';
    }
    return $defaultPhoto;
  }

  public function getRichContent($view = false, $params = array(), $map = false, $autoplay = true) {

    if($this->media == 1 || $this->media == 2) {
      return null;
    }
    if($this->media == 4) {
      $photo = Engine_Api::_()->getItem("storage_file", $this->file_id);
      if($photo) {
        return '<div class="sescontest_feed_item_audio_container"><div class="sescontest_feed_item_audio_bg" style="background-image: url(' . $this->getPhotoUrl('thumb.main'). ');"></div><div class="sescontest_feed_item_audio_img"><img src="'. $this->getPhotoUrl('thumb.main'). '" /><audio controls><source src="'.$photo->map().'" type="audio/mp3"></audio></div></div>';
      }
    }

    $session = new Zend_Session_Namespace('mobile');
    $mobile = $session->mobile;
    if ($this->type == 'iframely') {
      $videoEmbedded = $this->code;
    }
    // if video type is youtube
    if ($this->type == 1) {
      $videoEmbedded = $this->compileYouTube($this->participant_id, $this->code, $view, $mobile, $map, $autoplay);
    }
    // if video type is vimeo
    if ($this->type == 2) {
      $videoEmbedded = $this->compileVimeo($this->participant_id, $this->code, $view, $mobile, $map, $autoplay);
    }
    // if video type is uploaded
    if ($this->type == 3) {

      //Video sell work
      $viewer = Engine_Api::_()->user()->getViewer();
      $storage_file = Engine_Api::_()->storage()->get($this->file_id, $this->getType());
      if ($storage_file) {
        $video_location = $storage_file->getHref();
        if ($storage_file->extension === 'flv') {
          $videoEmbedded = $this->compileFlowPlayer($video_location, $view, $map, $autoplay);
        } else {
          $videoEmbedded = $this->compileHTML5Media($video_location, $view, $map, $autoplay);
        }
      }
    }
    // if video type is dailymotion
    if ($this->type == 4) {
      $videoEmbedded = $this->compileDailymotion($this->participant_id, $this->code, $view, $mobile, $map, $autoplay);
    }
    // if video is redtube
    if ($this->type == 5) {
      $videoEmbedded = $this->compileRedTube($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is xvideos
    if ($this->type == 6) {
      $videoEmbedded = $this->compileXvideos($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is Xhamster
    if ($this->type == 7) {
      $videoEmbedded = $this->compileXhamster($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is Youjizz
    if ($this->type == 8) {
      $videoEmbedded = $this->compileYoujizz($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is Tnaflix
    if ($this->type == 9) {
      $videoEmbedded = $this->compileTnaflix($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is Slutload
    if ($this->type == 10) {
      $videoEmbedded = $this->compileSlutload($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is Youporn
    if ($this->type == 11) {
      $videoEmbedded = $this->compileYouporn($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is Pornhub
    if ($this->type == 12) {
      $videoEmbedded = $this->compilePornhub($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is IndianPornVideos
    if ($this->type == 13) {
      $videoEmbedded = $this->compileIndianPornVideos($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is Empflix
    if ($this->type == 14) {
      $videoEmbedded = $this->compileEmpflix($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is PornRabbit
    if ($this->type == 15) {
      $videoEmbedded = $this->compilePornRabbit($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is url
    if ($this->type == 16) {
      $videoEmbedded = $this->compileFromUrl($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is url
    if ($this->type == 17) {
      $videoEmbedded = $this->compileEmbedCode($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is break
    if ($this->type == 18) {
      $videoEmbedded = $this->compileBreak($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is commedy central
    if ($this->type == 20) {
      $videoEmbedded = $this->commedycentral($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is metacafe
    if ($this->type == 21) {
      $videoEmbedded = $this->metacafe($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is veehd
    if ($this->type == 22) {
      $videoEmbedded = $this->veoh($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is veehd
    if ($this->type == 23) {
      $videoEmbedded = $this->veehd($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is 4shared
    if ($this->type == 24) {
      $videoEmbedded = $this->shared4($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is youku
    if ($this->type == 25) {
      $videoEmbedded = $this->youku($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is myspace
    if ($this->type == 26) {
      $videoEmbedded = $this->myspace($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is stagevu
    if ($this->type == 27) {
      $videoEmbedded = $this->stagevu($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is rutube
    if ($this->type == 28) {
      $videoEmbedded = $this->rutube($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is videobash
    if ($this->type == 29) {
      $videoEmbedded = $this->videobash($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is spike
    if ($this->type == 30) {
      $videoEmbedded = $this->spike($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is spike
    if ($this->type == 31) {
      $videoEmbedded = $this->clipfish($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is godtube
    if ($this->type == 32) {
      $videoEmbedded = $this->godtube($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is godtube
    if ($this->type == 33) {
      $videoEmbedded = $this->nuvid($this->participant_id, $this->code, $view, $mobile, $map);
    }
    // if video is vid2c
    if ($this->type == 34) {
      $videoEmbedded = $this->vid2c($this->participant_id, $this->code, $view, $mobile, $map);
    }

    // if video is twitter
    if ($this->type == 106) {
      $videoEmbedded = $this->twitter($this->participant_id, $this->code, $view, $mobile, $map);
    }
    return $videoEmbedded;
  }

  public function twitter($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    $url = 'http://' . $_SERVER['HTTP_HOST']
            . Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
                'module' => 'sescontet',
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
            'src="' . $url . '"
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
                'module' => 'sescontest',
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
            'src="' . $url . '"
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
            'src="//www.porn.com/videos/embed/' . $code . '"
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
      return '//embed.redtube.com/player/?id=' . $code . '&wmode=opaque&style=redtube&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//embed.redtube.com/player/?id=' . $code . '&wmode=opaque&style=redtube&' . ($autoplay ? "&autoplay=1" : "") . '"
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

  public function compileEmbedCode($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return $code;
    $embedded = '
    <iframe
    title="Embed code video player"
    id="videoFrame' . $video_id . '"
    class="url_iframe' . ($view ? "_big" : "_small") . '"' .
            'src=" ' . $code . '"
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

  public function compileFromUrl($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="Url video player"
    id="videoFrame' . $video_id . '"
    class="url_iframe' . ($view ? "_big" : "_small") . '"' .
            'src=" ' . $code . '?wmode=opaque&' . ($autoplay ? "&autoplay=1" : "") . '"
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

  public function rutube($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//rutube.ru/play/embed/' . $code;
    $embedded = '
    <iframe
    title="Redtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//rutube.ru/play/embed/' . $code . '"
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

  public function spike($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//media.mtvnservices.com/embed/mgid:arc:video:spike.com:' . $code;
    $embedded = '
    <iframe
    title="spike video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//media.mtvnservices.com/embed/mgid:arc:video:spike.com:' . $code . '"
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

  public function vid2c($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.vid2c.com/embed/' . $code;
    $embedded = '
    <iframe
    title="vid2c video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.vid2c.com/embed/' . $code . '"
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

  public function nuvid($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//http://www.nuvid.com/embed/' . $code;
    $embedded = '
    <iframe
    title="nuvid video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//http://www.nuvid.com/embed/' . $code . '"
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

  public function clipfish($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.clipfish.de/embed/' . $code;
    $embedded = '
    <iframe
    title="clipfish video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//http://www.clipfish.de/embed/' . $code . '"
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

  public function godtube($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.godtube.com/embed/watch/' . $code;
    $embedded = '
    <iframe
    title="godtube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.godtube.com/embed/watch/' . $code . '"
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

  public function videobash($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.videobash.com/embed/' . $code;
    $embedded = '
    <iframe
    title="videobash video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.videobash.com/embed/' . $code . '"
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

  public function veoh($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.veoh.com/embed/' . $code;
    $embedded = '
    <iframe
    title="veoh video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.veoh.com/embed/' . $code . '"
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

  public function drtuber($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.drtuber.com/embed/' . $code;
    $embedded = '
    <iframe
    title="drtuber video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.drtuber.com/embed/' . $code . '"
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

  public function youku($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//player.youku.com/embed/' . $code;
    $embedded = '
    <iframe
    title="youku video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//player.youku.com/embed/' . $code . '"
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

  public function shared4($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.4shared.com/web/embed/file/' . $code;
    $embedded = '
    <iframe
    title="shared4 video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.4shared.com/web/embed/file/' . $code . '"
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

  public function veehd($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//veehd.com/embed?t=3&v=' . $code;
    $embedded = '
    <iframe
    title="veehd video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//veehd.com/embed?t=3&v=' . $code . '"
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

  public function stagevu($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//stagevu.com/embed?uid=' . $code;
    $embedded = '
    <iframe
    title="stagevu video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//stagevu.com/embed?uid=' . $code . '"
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

  public function myspace($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//media.myspace.com/play/video/' . $code;
    $embedded = '
    <iframe
    title="myspace video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//media.myspace.com/play/video/' . $code . '"
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

  public function metacafe($video_id, $code, $view, $mobile = false, $map = false) {
    $autoplay = !$mobile && $view;
    if ($map)
      return '//www.metacafe.com/embed/' . $code;
    $embedded = '
    <iframe
    title="metacafe video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.metacafe.com/embed/' . $code . '"
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
            'src="//media.mtvnservices.com/embed/mgid:arc:video:comedycentral.com: ' . $code . '"
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

  public function compileDailymotion($video_id, $code, $view, $mobile = false, $map = false, $autoPlayView = true) {
    $autoplay = !$mobile && $view;
    if (!$autoPlayView)
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

  public function compileYouTube($video_id, $code, $view, $mobile = false, $map = false, $autoPlayView = true) {
    $autoplay = !$mobile && $view;
    if (!$autoPlayView)
      $autoplay = $autoPlayView;
    if ($map)
      return '//www.youtube.com/embed/' . $code . '?wmode=opaque' . ($autoplay ? "&autoplay=1" : "");
    $embedded = '
    <iframe
    title="YouTube video player"
    id="videoFrame' . $video_id . '"
    class="youtube_iframe_ses youtube_iframe' . ($view ? "_big" : "_small") . '"' .
            'src="//www.youtube.com/embed/' . $code . '?enablejsapi=1&wmode=opaque' . ($autoplay ? "&autoplay=1" : "") . '"
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

  public function compileVimeo($video_id, $code, $view, $mobile = false, $map = false, $autoPlayView = true) {
    $autoplay = !$mobile && $view;
    if (!$autoPlayView)
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
    <div class='sescontest_feed_item_audio_container'><div class='sescontest_feed_item_audio_bg' style='background-image:url(" .$this->getPhotoUrl('thumb.main'). ");'></div><div class='sescontest_feed_item_audio_img'><video class='video_upload_small' id='video" . $this->participant_id . "' controls preload='auto' width='" . ($view ? "480" : "420") . "' height='" . ($view ? "386" : "326") . "'>
      <source type='video/mp4;' src=" . $location . ">
    </video></div></div>";
    return $embedded;
  }

  public function compileFlowPlayer($location, $view, $map = false, $autoplay = false) {
    if ($map)
      return;

    $flowplayer = Engine_Api::_()->sesbasic()->checkPluginVersion('core', '4.8.10') ? 'externals/flowplayer/flowplayer-3.2.18.swf' : 'externals/flowplayer/flowplayer-3.1.5.swf';

    $embedded = "
    <div id='videoFrame" . $this->participant_id . "' class='sescontest_feed_item_embed'></div>
    <script type='text/javascript'>
    en4.core.runonce.add(function(){\$('video_thumb_" . $this->participant_id . "').removeEvents('click').addEvent('click', function(){checkFunctionEmbed();flashembed('videoFrame$this->participant_id',{src: '" . Zend_Registry::get('StaticBaseUrl') . $flowplayer . "', width: " . ($view ? "480" : "420") . ", height: " . ($view ? "386" : "326") . ", wmode: 'opaque'},{config: {clip: {url: '$location',autoPlay: " . ($view ? "false" : "true") . ", duration: '$this->duration', autoBuffering: true},plugins: {controls: {background: '#000000',bufferColor: '#333333',progressColor: '#444444',buttonColor: '#444444',buttonOverColor: '#666666'}},canvas: {backgroundColor:'#000000'}}});})});
    </script>";

    return $embedded;
  }

  protected function _delete() {
    if ($this->_disableHooks)
      return;
    $db = Engine_Db_Table::getDefaultAdapter();
    $db->query("DELETE FROM engine4_sescontest_votes WHERE participant_id = " . $this->getIdentity());
    $db->query("DELETE FROM engine4_sescontest_recentlyviewitems WHERE resource_type = 'participant' && resource_id = " . $this->getIdentity());
    $db->query("DELETE FROM engine4_sescontest_favourites WHERE resource_type = 'participant' && resource_id = " . $this->getIdentity());
    parent::_delete();
  }

  /**
   * Gets a proxy object for the tags handler
   *
   * @return Engine_ProxyObject
   * */
  public function tags() {
    return new Engine_ProxyObject($this, Engine_Api::_()->getDbtable('tags', 'core'));
  }

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
   * Get a generic media type. Values:
   * entry
   *
   * @return string
   */
  public function getMediaType() {
    return "entry";
  }

}
