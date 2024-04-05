<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: rss-feed.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<rss version="2.0">
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html charset=utf-8" />
<channel>
  <title><?php echo $this->translate('Adanced News Rss Feed');?></title>
    <description><?php echo $this->translate('Rss Feeds');?></description>
    <?php foreach($this->news as $news):?>
    <item>
    	<?php 
      	$title = strip_tags($news->title);
        $title = str_replace("&rsquo;","'", $title); 
        $contentTitle = preg_replace("/&#?[a-z0-9]{2,8};/i","",$title );
       ?>
      <title><?php echo htmlspecialchars ($contentTitle);?></title>
      <link><?php echo "http://".$_SERVER['HTTP_HOST'].$this->url(array('action' => 'view','news_id'=>$news->custom_url), 'sesnews_entry_view');?></link>
      <?php 
      	$text = strip_tags($news->body);
        $text = str_replace("&rsquo;","'", $text); 
        $content = preg_replace("/&#?[a-z0-9]{2,8};/i","",$text );
       ?>
      <description><?php echo Engine_Api::_()->sesnews()->truncate(htmlspecialchars($content), 200,array('html' => true, 'ending' => '')) ?></description>
    </item>
  <?php endforeach;?>
</channel>
</rss>
