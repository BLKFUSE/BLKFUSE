<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: content.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

return array(
  array(
    'title' => 'SNS - Dating  - Already Viewed Member',
    'description' => 'This widget display already viewed members.',
    'category' => 'SNS - Dating',
    'type' => 'widget',
    'name' => 'edating.already-viewed',
    'isPaginated' => true,
  ),
  array(
    'title' => 'SNS - Dating  - Mutual Likes',
    'description' => 'This widget display mutual like.',
    'category' => 'SNS - Dating',
    'type' => 'widget',
    'name' => 'edating.mutual-likes',
    'isPaginated' => true,
  ),
  array(
    'title' => 'SNS - Dating  - Who Like Me',
    'description' => 'This widget display all members who like me.',
    'category' => 'SNS - Dating',
    'type' => 'widget',
    'name' => 'edating.who-like-me',
    'isPaginated' => true,
  ),
  array(
    'title' => 'SNS - Dating  - My Likes',
    'description' => 'This widget display all like member by me.',
    'category' => 'SNS - Dating',
    'type' => 'widget',
    'name' => 'edating.my-likes',
    'isPaginated' => true,
  ),
  array(
    'title' => 'SNS - Dating  - Dating Members',
    'description' => 'This widget display all member.',
    'category' => 'SNS - Dating',
    'type' => 'widget',
    'name' => 'edating.browse-users',
		'adminForm' => array(
		  'elements' => array (
        array(
          'Select',
          'showinfo',
          array(
          'label' => "Do you want to member information?",
          'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
          ),
          'value' => '1',
          ),
        ),
        array(
          'Select',
          'cancelbutton',
          array(
          'label' => "Do you want to show cancel button?",
          'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
          ),
          'value' => '1',
          ),
        ),
        array(
            'Text',
            'limit_data',
            array(
                'label' => 'Count (number of members to show)',
                'value' => 10,
                'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                ),
            )
        ),
		  ),
		),
  ),
  array(
    'title' => 'SNS - Member Browse Search',
    'description' => 'Displays a search form in the meet people page.',
    'category' => 'SNS - Dating',
    'type' => 'widget',
    'name' => 'edating.browse-search',
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'viewType',
          array(
              'label' => "View Type",
              'multiOptions' => array(
                  'horizontal' => 'Horizontal',
                  'vertical' => 'Vertical',
              ),
              'value' => 'horizontal',
          ),
        ),
      ),
    ),
  ),
	array (
		'title' => 'SNS - Dating Browse Menu',
		'description' => 'Dating Browse Menu',
		'category' => 'SNS - Dating',
		'type' => 'widget',
		'name' => 'edating.browse-menu',
	),
);
