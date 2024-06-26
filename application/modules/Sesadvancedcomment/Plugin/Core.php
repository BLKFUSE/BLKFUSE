<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedcomment_Plugin_Core {
  
  public function onRenderLayoutDefault($event, $mode = null) {
		if( defined('_ENGINE_ADMIN_NEUTER') && _ENGINE_ADMIN_NEUTER ) return;
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.pluginactivated')) {
      $view = $event->getPayload();
      if( !($view instanceof Zend_View_Interface) ) {
        return;
      }
      $settings = Engine_Api::_()->getDbtable('settings', 'core');
      $request = Zend_Controller_Front::getInstance()->getRequest();
      $moduleName = $request->getModuleName();
      $actionName = $request->getActionName();
      $controllerName = $request->getControllerName();
      $viewer = Engine_Api::_()->user()->getViewer();
    // if($actionName == 'list'){
      // echo $moduleName.' || '.$actionName.' || '.$controllerName;die;
    // }
    
			if($viewer->getIdentity()) {
				$emojiContent = $view->partial('emojicontent.tpl','sesadvancedcomment',array());
				$search = array(
						'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
						'/[^\S ]+\</s',  // strip whitespaces before tags, except space
						'/(\s)+/s'       // shorten multiple whitespace sequences
				);
				$replace = array(
						'>',
						'<',
						'\\1'
				);
				$emojiContent = preg_replace($search, $replace, $emojiContent);
				
				if (substr($request->getPathInfo(), 1, 5) == "admin") { 
					return;
				}
				$script = "scriptJquery(document).ready(function() {
					scriptJquery(".json_encode($emojiContent.'<a href="javascript:;" class="exit_emoji_btn notclose" style="display:none;">').").appendTo('body');
				});";
				$view->headScript()->appendScript($script);
				
				if(defined('SESFEEDGIFENABLED')) {
					$gifContent = $view->partial('gifcontent.tpl','sesfeedgif',array());
					$search = array(
							'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
							'/[^\S ]+\</s',  // strip whitespaces before tags, except space
							'/(\s)+/s'       // shorten multiple whitespace sequences
					);
					$replace = array(
							'>',
							'<',
							'\\1'
					);
					$gifContent = preg_replace($search, $replace, $gifContent);
					
					if (substr($request->getPathInfo(), 1, 5) == "admin") { 
						return;
					}
					$script = "scriptJquery(document).ready(function() {
						scriptJquery('".$gifContent.'<a href="javascript:;" class="exit_gif_btn notclose" style="display:none;">'."').appendTo('body');
					});";
					$view->headScript()->appendScript($script);
				}

				//Feeling Work
				if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesemoji')) {
				
					$feeling_emojiContent = $view->partial('feeling_emojicontent_comments.tpl','sesemoji',array());
					$search = array(
							'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
							'/[^\S ]+\</s',  // strip whitespaces before tags, except space
							'/(\s)+/s'       // shorten multiple whitespace sequences
					);
					$replace = array(
							'>',
							'<',
							'\\1'
					);
					$feeling_emojiContent = preg_replace($search, $replace, $feeling_emojiContent);
					$script = "scriptJquery(document).ready(function() {
						scriptJquery('".$feeling_emojiContent.'<a href="javascript:;" class="feeling_exit_emoji_btn notclose" style="display:none;">'."').appendTo('body');
					});";
					$view->headScript()->appendScript($script);
	
					$getEmojis = Engine_Api::_()->getDbTable('emojis', 'sesemoji')->getEmojis(array('fetchAll' => 1));
					$enableemojis = Engine_Api::_()->authorization()->isAllowed('sesemoji', null, 'enableemojis'); 
					if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesemoji') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesemoji.enableemoji', 1) && $enableemojis && engine_count($getEmojis) > 0) {
						$script = "var sesemojiEnable = 1;";
					} else {
						$script = "var sesemojiEnable = 0;";
					}
					$view->headScript()->appendScript($script);
				}
				//Feeling Work End
      }
      
      //check album and video plugins enable
      $album = $video = 0;
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('album') || Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum')){
        $album = 1; 
      }
      $videoType = '';
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('video')){
        $video = 1; 
        $videoType = 'video'; 
      } else if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideo')){
          $video = 1; 
          $videoType = 'sesvideo'; 
      }
      $youtubeEnable = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.youtube.apikey', 0);
      if($youtubeEnable)
        $youtubeEnable = 1;
      else
        $youtubeEnable = 0;
      $enablesearch = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablesearch', 1);
      $script = "
        var AlbumModuleEnable = ".$album.";
        var videoModuleEnable = ".$video.";
        var youtubePlaylistEnable = '".$youtubeEnable."';
        var videoModuleName = '".$videoType."';
        var enablesearch = '".$enablesearch."';
        ";
      $view->headScript()->appendScript($script);
    }
  }
  
  public function onRenderLayoutDefaultSimple($event)
  {
    // Forward
    return $this->onRenderLayoutDefault($event, 'simple');
  }
  
  public function onRenderLayoutMobileDefault($event)
  {
    // Forward
    return $this->onRenderLayoutDefault($event);
  }
  
  public function onRenderLayoutMobileDefaultSimple($event)
  {
    // Forward
    return $this->onRenderLayoutDefault($event);
  }
}
