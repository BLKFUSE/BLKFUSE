<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: content.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
return array(
  array(
    'title' => 'Advanced Nested Comments',
    'description' => 'Shows the comments, replies, attachments in comments like photos, videos, emoticons & stickers as configured by you about an item.',
    'category' => 'SNS - Advanced Nested Comments with Attachments Plugin',
    'type' => 'widget',
    'name' => 'sesadvancedcomment.comments',
    'defaultParams' => array(
      'title' => 'Comments'
    ),
    'requirements' => array(
      'subject',
    ),
  ),
);