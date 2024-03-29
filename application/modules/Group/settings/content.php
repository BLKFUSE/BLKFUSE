<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: content.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
return array(
  array(
    'title' => 'Profile Groups',
    'description' => 'Displays a member\'s groups on their profile.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-groups',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Groups',
      'titleCount' => true,
    ),
    'requirements' => array(
      'subject' => 'user',
    ),
  ),
  array(
    'title' => 'Group Profile Discussions',
    'description' => 'Displays a group\'s discussions on its profile.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-discussions',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Discussions',
      'titleCount' => true,
    ),
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title' => 'Group Cover Photo',
    'description' => 'Displays a group\'s cover photo on its profile.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.cover-photo',
    'requirements' => array(
      'viewer',
    ),
  ),
  array(
    'title' => 'Group Profile Info',
    'description' => 'Displays a group\'s info (creation date, member count, leader, officers, etc) on its profile.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-info',
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title' => 'Group Profile Members',
    'description' => 'Displays a group\'s members on its profile.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-members',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Members',
      'titleCount' => true,
    ),
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title' => 'Group Profile Options',
    'description' => 'Displays a menu of actions (edit, report, join, invite, etc) that can be performed on a group on its profile.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-options',
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title' => 'Group Profile Photo',
    'description' => 'Displays a group\'s photo on its profile.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-photo',
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title' => 'Group Profile Photos',
    'description' => 'Displays a group\'s photos on its profile.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-photos',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Photos',
      'titleCount' => true,
    ),
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title' => 'Group Profile Status',
    'description' => 'Displays a group\'s title on its profile.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-status',
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title'=> 'Group Profile Events',
    'description' => 'Displays a group\'s events on its profile',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-events',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Events',
      'titleCount' => true,
    ),
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title'=> 'Group Profile Blogs',
    'description' => 'Displays a group\'s blogs on its profile',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-blogs',
    'isPaginated' => true,
    'defaultParams' => array(
        'title' => 'Blogs',
        'titleCount' => true,
    ),
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title'=> 'Group Profile Polls',
    'description' => 'Displays a group\'s polls on its profile',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-polls',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Polls',
      'titleCount' => true,
    ),
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title'=> 'Group Profile Videos',
    'description' => 'Displays a group\'s Videos on its profile',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.profile-videos',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Videos',
      'titleCount' => true,
    ),
    'requirements' => array(
      'subject' => 'group',
    ),
  ),
  array(
    'title' => 'Popular Groups',
    'description' => 'Displays a list of most viewed groups.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.list-popular-groups',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Popular Groups',
    ),
    'requirements' => array(
      'no-subject',
    ),
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'popularType',
          array(
            'label' => 'Popular Type',
            'multiOptions' => array(
              'creation_date' => 'Recently Created',
              'modified_date' => 'Recently Modified',
              'like_count' => 'Most Liked',
              'view_count' => 'Most Viewed',
              'comment_count' => 'Most Commented',
              'member_count' => 'Members',
            ),
            'value' => 'view_count',
          )
        ),
      )
    ),
  ),
  array(
    'title' => 'Recent Groups',
    'description' => 'Displays a list of recently created groups.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.list-recent-groups',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Recent Groups',
    ),
    'requirements' => array(
      'no-subject',
    ),
    'adminForm' => array(
      'elements' => array(
        array(
          'Radio',
          'recentType',
          array(
            'label' => 'Recent Type',
            'multiOptions' => array(
              'creation' => 'Creation Date',
              'modified' => 'Modified Date',
            ),
            'value' => 'creation',
          )
        ),
      )
    ),
  ),
  
  array(
    'title' => 'Group Browse Search',
    'description' => 'Displays a search form in the group browse page.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.browse-search',
    'requirements' => array(
      'no-subject',
    ),
  ),
  array(
    'title' => 'Group Browse Menu',
    'description' => 'Displays a menu in the group browse page.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.browse-menu',
    'requirements' => array(
      'no-subject',
    ),
  ),
  array(
    'title' => 'Group Browse Quick Menu',
    'description' => 'Displays a small menu in the group browse page.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.browse-menu-quick',
    'requirements' => array(
      'no-subject',
    ),
  ),
  array(
    'title' => 'Group Categories',
    'description' => 'Display a list of categories for groups.',
    'category' => 'Groups',
    'type' => 'widget',
    'name' => 'group.list-categories',
  ),
	array(
		'title' => 'Breadcrumb for Group View Page',
		'description' => 'Displays Breadcrumb for Group view page.',
		'category' => 'Groups',
		'type' => 'widget',
		'name' => 'group.breadcrumb',
  ),
) ?>
