<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: GetCommentContent.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesadvancedcomment_View_Helper_GetCommentContent
{
  public function getCommentContent($content = null, array $data = array())
  {
   // $content = $this->stringsToURLStrings($content);
    //change content for emojies
    $emoji = Engine_Api::_()->getApi('emoji','sesbasic')->getEmojisArray();
    $content = str_replace(array_keys($emoji),array_values($emoji),$content);
   
    //usage
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity'))
    $content =  $this->gethashtags($content);
    $content = $this->getMentionTags($content);
    //$content = $this->bodyLink($content);
    //Emojis Work
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesemoji') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesemoji.pluginactivated')) {
      $bodyEmojis = explode(' ', $content);
      require_once 'application/modules/Sesemoji/controllers/lib/php/autoload.php';
      $client = new Client(new Ruleset());
      $client->imagePathPNG = 'application/modules/Sesemoji/externals/images/emoji/';
      foreach($bodyEmojis as $bodyEmoji) {
        $getEmojiFileId = Engine_Api::_()->getDbTable('emojiicons', 'sesemoji')->getEmojiFileId(array('emoji_encodecode' => $bodyEmoji, 'column' => 'file_id'));
        $getEmojiTitle = Engine_Api::_()->getDbTable('emojiicons', 'sesemoji')->getEmojiFileId(array('emoji_encodecode' => $bodyEmoji, 'column' => 'title'));
        if($getEmojiFileId) {
          $emojisCode = Engine_Api::_()->sesemoji()->DecodeEmoji($bodyEmoji);
          $emojisCode = $client->toImage($emojisCode);
          $content = str_replace($bodyEmoji, $emojisCode, $content);
//           $emojiURL = Engine_Api::_()->storage()->get($getEmojiFileId, '')->getPhotoUrl();
//           $content = str_replace($bodyEmoji,'<img title="'.$getEmojiTitle.'" style="height:24px;width:24px;" src="'.$emojiURL.'">', $content);
        } else {
          //Emoji Share Work
          $emojisCode = Engine_Api::_()->sesemoji()->DecodeEmoji($bodyEmoji);
          $emojisCode = $client->toImage($emojisCode);
          //$emojisCode = Engine_Api::_()->sesemoji()->DecodeEmoji($bodyEmoji);
          $content = str_replace($bodyEmoji, $emojisCode, $content);
        }
      }
    }
    //Emojis Work End

    return ($content);
  }
  protected function stringsToURLStrings($stringBody){
    $pattern = '@(http(s)?://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
    return preg_replace($pattern, '<a href="http$2://$3" target="_blank">$0</a>', $stringBody);
  }
  function gethashtags($content)
  {
    preg_match_all("/#([\p{Pc}\p{N}\p{L}\p{Mn}]+)/u", $content, $matches);
    $searchword = $replaceWord = array();
    foreach($matches[0] as $value){
      if(!engine_in_array($value,$searchword)){
        $searchword[]=$value;
        $replaceWord[] = '<a target="_blank" href="hashtag?hashtag='.str_replace('#','',$value).'">'.$value.'</a>';
      }
    }
    $content = str_replace($searchword,$replaceWord,$content);
    return $content;
  }
  function getMentionTags($content){
    if(is_array($content))
      $contentMention = $content[1];
    else
      $contentMention = $content;
    preg_match_all('/(^|\s)(@\w+)/', $contentMention, $result);
    foreach($result[2] as $value){
        $user_id = str_replace('@_user_','',$value);
        if(intval($user_id)>0){
          $user = Engine_Api::_()->getItem('user',$user_id);
          if(!$user || !$user->getIdentity())
           continue;
        }else{
          $itemArray = explode('_',$user_id);
          $resource_id = $itemArray[count($itemArray) - 1];
          unset($itemArray[count($itemArray) - 1]);
          $resource_type = implode('_',$itemArray);
            try {
                $user = Engine_Api::_()->getItem($resource_type, $resource_id);
            }catch (Exception $e){
                continue;
            }
          if(!$user || !$user->getIdentity())
            continue;
        }
        $contentMention = str_replace($value,'<a href="'.$user->getHref().'" data-src="'.$user->getGuid().'" class="ses_tooltip">'.$user->getTitle().'</a>',$contentMention);
    }

    if(is_array($content))
      $content[1] = $contentMention;
    else
      $content = $contentMention;

    return $content;
  }
/*  public function bodyLink($content){
      $regex = '/(https|http)?\:\/\/[^\" ]+/i';
      return preg_replace($regex,'<a target="_blank" href="$0">$0</a>', $content);
  } */
}
