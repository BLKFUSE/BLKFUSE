<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eweblivestreaming
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: content.php 2020-07-05  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

return array(
  array(
    'title' => 'SNS - Live Streaming in Website - User Go Live Widget',
    'description' => 'Placed this widget on anywhere on your website',
    'category' => 'SNS - Live Streaming in Website',
    'type' => 'widget',
    'name' => 'eweblivestreaming.create-live-streaming',
    'autoEdit' => false
  ),
  array(
    'title' => 'SNS - Live Streaming in Website - Live Members',
    'description' => 'Displays a search form in the blog gutter.',
    'category' => 'SNS - Live Streaming in Website',
    'type' => 'widget',
    'name' => 'eweblivestreaming.live-members',
    'adminForm' => array(
      'elements' => array(
        array(
          'Text',
          'limit',
          array(
            'label' => 'count (number of users to show).',
            'value' => '15',
            'validators' => array(
              array('Int', true),
              array('GreaterThan', true, array(0)),
            )
          )
        ),
        array(
          'Select',
          'live_icon',
          array(
            'label' => "Do you want to show live icon?",
            'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
            ),
          ),
          'value' => '1'
        ),
        
      ),
    ),
),
);