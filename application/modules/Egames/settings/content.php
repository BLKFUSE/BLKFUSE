<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: content.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
return array(
	array(
    'title' => 'SNS - Games - Browse Games',
    'description' => 'This widget will display all the created games on the Browse Page. The Recommended page is the Games Browse Page.',
    'category' => 'SNS - Games',
    'type' => 'widget',
    'name' => 'egames.browse-games',
    'autoEdit' => false,
  ),
  array(
    'title' => 'SNS - Games - Browse Menus',
    'description' => 'This is the plugin navigation menu which you can place on every page of this plugin.',
    'category' => 'SNS - Games',
    'type' => 'widget',
    'name' => 'egames.browse-menu',
    'autoEdit' => false,
  ),
  array(
    'title' => 'SNS - Games - Browse Search',
    'description' => 'This widget displays the search form on the right sidebar of the page. You can place it on the Browse Games Page.',
    'category' => 'SNS - Games',
    'type' => 'widget',
    'name' => 'egames.browse-search',
    'autoEdit' => false,
  ),
	array(
    'title' => 'SNS - Games - Manage Games',
    'description' => 'This widget will be placed at the manage games page.',
    'category' => 'SNS - Games',
    'type' => 'widget',
    'name' => 'egames.manage-games',
    'autoEdit' => false,
  ),
	array(
    'title' => 'SNS - Games - Game View',
    'description' => 'This widget will be placed on the game view page.',
    'category' => 'SNS - Games',
    'type' => 'widget',
    'name' => 'egames.game-view',
    'autoEdit' => false,
  ),
	array(
    'title' => 'SNS - Games - Similar Games',
    'description' => 'This widget will display the similar games on the Games View Page.',
    'category' => 'SNS - Games',
    'type' => 'widget',
    'name' => 'egames.similar-games',
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
        'limit',
        array(
            'label' => 'Limit number of content.',
            'value' => 5,
            'validators' => array(
                array('Int', true),
                array('GreaterThan', true, array(0)),
            )
        )
        )
      ),
    ),
    'autoEdit' => true,
  ),
);
