<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: content.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

return array(
  array(
    'title' => 'SNS - TikTok Clone - TikTok Profile Page Link',
    'description' => 'This widget shows the link of TikTok Profile Page. You can place this widget on Member Profile Page only.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.tikttok-profile-link',
  ),
  array(
    'title' => 'SNS - TikTok Clone - Sidebar Links',
    'description' => 'This widget shows the For You and Following buttons in sidebar. You can place this widget on Tiktok Explore page.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.sidebar-links',
  ),
	array(
    'title' => 'SNS - TikTok Clone - Video Feed',
    'description' => 'This widget will display recent videos feed.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.video-feed',
    'adminForm' => array(
      'elements' => array(
          array(
              'Text',
              'limit_data',
              array(
                  'label' => 'Count (Total number of data to show.)',
                  'value' => 15,
                  'validators' => array(
                      array('Int', true),
                      array('GreaterThan', true, array(0)),
                  )
              )
          ),
      )
    ),
  ),
  array(
    'title' => 'SNS - TikTok Clone - Profile Videos',
    'description' => 'This widget shows the users videos on their TikTok profile page. You can place this widget on TikTok profile page only.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.profile-videos',
  ),
  array(
    'title' => 'SNS - TikTok Clone - Profile Like Videos',
    'description' => 'This widget shows the users liked videos on their TikTok profile page. You can place this widget on TikTok profile page only.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.profile-like-videos',
  ),
  array(
    'title' => 'SNS - TikTok Clone - Tagged Videos',
    'description' => 'This widget will display tagged videos.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.tagged-videos',
  ),
  array(
    'title' => 'SNS - TikTok Clone - Browse Members',
    'description' => 'This widget shows the Followers and Followings on Tiktok member profile page. You can place this widget on Tiktok member profile page.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.browse-members',
    'adminForm' => array(
      'elements' => array(
          array(
              'Select',
              'type',
              array(
                  'label' => 'Select type.',
                  'multiOptions' =>
                  array(
                      '' => '',
                      'followings' => 'Following',
                      'followers' => 'Followers'
                  ),
              ),
          ),
      )
    ),
  ),
  array(
    'title' => 'SNS - TikTok Clone - Block Members',
    'description' => 'This widget shows the block members on Tiktok member profile page. You can place this widget on Tiktok member profile page.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.block-members',
  ),
  array(
    'title' => 'SNS - TikTok Clone - Suggested Members',
    'description' => 'This widget will display suggested members of your website at the sidebar columns (left/right). You can place this widget at any page. ',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.suggested-members',
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
          'limit',
          array(
              'label' => 'Enter limit of user that you want to show in this widgte.',
              'value' => 5,
          )
        ),
      )
    ),
  ),
  array(
    'title' => 'SNS - TikTok Clone - Member Profile User Info',
    'description' => 'This widget will display member profile of your website.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.member-profile-user-info',
  ),
  array(
    'title' => 'SNS - TikTok Clone - Tag View Info',
    'description' => 'This widget will display tag view page.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.tag-view-info',
  ),
  array(
    'title' => 'SNS - TikTok Clone - Popular Tags',
    'description' => 'This widget will display popular tags.',
    'category' => 'SNS - TikTok Clone',
    'type' => 'widget',
    'name' => 'eticktokclone.popular-tags',
  ),
);
