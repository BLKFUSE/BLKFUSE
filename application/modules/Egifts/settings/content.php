<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: content.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$banner_options[] = '';
$path = new DirectoryIterator(APPLICATION_PATH . '/public/admin/');
foreach ($path as $file) {
	if ($file->isDot() || !$file->isFile())
		continue;
	$base_name = basename($file->getFilename());
	if (!($pos = strrpos($base_name, '.')))
		continue;
	$extension = strtolower(ltrim(substr($base_name, $pos), '.'));
	if (!engine_in_array($extension, array('gif', 'jpg', 'jpeg', 'png')))
		continue;
	$banner_options['public/admin/' . $base_name] = $base_name;
}

return array(
  array(
    'title' => 'SNS - Virtual Gifts - My Orders',
    'description' => 'Displays all my purchased orders. This is only for My Orders Page.',
    'category' => 'SNS - Virtual Gifts Plugin',
    'type' => 'widget',
    'name' => 'egifts.my-orders',
  ),
  array(
        'title' => 'SNS - Virtual Gifts - Navigation Menu',
        'description' => 'This widget will display the main navigation menu of this plugin. You can place this widget at any page of this Plugin.',
        'category' => 'SNS - Virtual Gifts Plugin',
        'type' => 'widget',
        'name' => 'egifts.browse-menu',
        'requirements' => array(
            'no-subject',
        ),
  ),
  array(
    'title' => 'SNS - Virtual Gifts - Send Gift Button',
    'description' => 'From this widget, you can send gifts to the site member from their Profile Page. Place this widget at Member Profile Page.',
    'category' => 'SNS - Virtual Gifts Plugin',
    'type' => 'widget',
    'name' => 'egifts.send-button',
    'requirements' => array(
      'no-subject',
    ),
  ),
  array(
    'title' => 'SNS - Virtual Gifts - Browse Gifts',
    'description' => 'This widget will display all the gifts created by the admin at Browse Gifts Page. Place this widget at Browse Gifts Page.',
    'category' => 'SNS - Virtual Gifts Plugin',
    'type' => 'widget',
    'name' => 'egifts.browse-gifts',
    'requirements' => array(
      'no-subject',
    ),
    'adminForm' => array(
      'elements' => array(
          array(
              'MultiCheckbox',
              'show_criteria',
              array(
                  'label' => "Choose the options that you want to be displayed in this widget.",
                  'multiOptions' => array(
                      'title' => 'Gift Title',
                      'image' => 'Image',
                      'price' => 'Price',
                      'description' => 'Description',
                      'sendButton' => 'Send Button',
                      'likeButton' => 'Like Button',
                      'favoriteButton'=>'Favorite Button',
                  ),
                  'escape' => false,
              )
          ),
          array(
            'Text',
            'title_truncation',
                array(
                    'label' => 'Title truncation limit.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Text',
            'description_truncation',
                array(
                    'label' => 'Description truncation limit.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Text',
            'height',
                array(
                    'label' => 'Enter the height of the main photo block.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
                'Select',
                'search_type',
                array(
                    'label' => "Choose gift Display Criteria",
                    'multiOptions' => array(
                        'recentlySPcreated' => 'Recently Created',
                        'mostSPviewed' => 'Most Viewed',
                        'mostSPliked' => 'Most Liked',
                        'mostSPfavourite' => 'Most Favourite',
                    ),
                ),
            ),
          array(
            'Radio',
            'show_item_count',
              array(
                  'label' => 'Do you want to show gift count in this widget?',
                  'value' => 1,
                  'multiOptions'=>array(
                    '1'=> 'yes',
                    '0'=> 'No'
                  ),
              )
          ),
          array(
              'Text',
              'limit_data',
              array(
                  'label' => 'Count for the gifts. (number of gifts to show).',
                  'value' => 10,
              )
          ),
          array(
            'Radio',
            'pagging',
            array(
              'label' => "Do you want the gifts to be auto-loaded when users scroll down the page?",
              'multiOptions' => array(
                'auto_load' => 'Yes, Auto Load',
                'button' => 'No, show \'View more\' link.',
                'pagging' => 'No, show \'Pagination\'.'
              ),
              'value' => 'auto_load',
            )
          ),

      ),
    ),
    'autoEdit' => true,
  ),
  array(
    'title' => 'SNS - Virtual Gifts - Profile Gifts',
    'description' => 'This widget will display all the received gifts by member at his profile page. Place this widget at the Member profile Page.',
    'category' => 'SNS - Virtual Gifts Plugin',
    'type' => 'widget',
    'name' => 'egifts.profile-gifts',
    'requirements' => array(
      'no-subject',
    ),
    'adminForm' => array(
      'elements' => array(
          array(
              'MultiCheckbox',
              'show_criteria',
              array(
                  'label' => "Choose the options that you want to be displayed in this widget.",
                  'multiOptions' => array(
                      'title' => 'Gift Title',
                      'image' => 'Image',
                      'price' => 'Price',
                      'description' => 'Description',
                      'displayMsg' => 'Display Message',
                      'sendBy' => 'Send By',
                  ),
                  'escape' => false,
              )
          ),
          array(
            'Text',
            'title_truncation',
                array(
                    'label' => 'Title truncation limit.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Text',
            'description_truncation',
                array(
                    'label' => 'Description truncation limit.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Text',
            'height',
                array(
                    'label' => 'Enter the height of the main photo block.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Radio',
            'show_item_count',
              array(
                  'label' => 'Do you want to show gift count in this widget?',
                  'value' => 1,
                  'multiOptions'=>array(
                    '1'=> 'yes',
                    '0'=> 'No'
                  ),
              )
          ),
          array(
              'Text',
              'limit_data',
              array(
                  'label' => 'Count for the gifts. (number of gifts to show).',
                  'value' => 10,
              )
          ),
          array(
            'Radio',
            'pagging',
            array(
              'label' => "Do you want the gifts to be auto-loaded when users scroll down the page?",
              'multiOptions' => array(
                'auto_load' => 'Yes, Auto Load',
                'button' => 'No, show \'View more\' link.',
                'pagging' => 'No, show \'Pagination\'.'
              ),
              'value' => 'auto_load',
            )
          ),

      ),
    ),
    'autoEdit' => true,
  ),
  array(
    'title' => 'SNS - Virtual Gifts - Received Gifts',
    'description' => 'This widget will display all the received gifts by the other members. Place this widget at My Gifts Page.',
    'category' => 'SNS - Virtual Gifts Plugin',
    'type' => 'widget',
    'name' => 'egifts.received-gifts',
    'requirements' => array(
      'no-subject',
    ),
    'adminForm' => array(
      'elements' => array(
          array(
              'MultiCheckbox',
              'show_criteria',
              array(
                  'label' => "Choose the options that you want to be displayed in this widget.",
                  'multiOptions' => array(
                      'title' => 'Gift Title',
                      'image' => 'Image',
                      'price' => 'Price',
                      'description' => 'Description',
                      'displayMsg' => 'Display Message',
                      'sendBy' => 'Send By',
                  ),
                  'escape' => false,
              )
          ),
          array(
            'Text',
            'title_truncation',
                array(
                    'label' => 'Title truncation limit.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Text',
            'description_truncation',
                array(
                    'label' => 'Description truncation limit.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Text',
            'height',
                array(
                    'label' => 'Enter the height of the main photo block.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Radio',
            'show_item_count',
              array(
                  'label' => 'Do you want to show gift count in this widget?',
                  'value' => 1,
                  'multiOptions'=>array(
                    '1'=> 'yes',
                    '0'=> 'No'
                  ),
              )
          ),
          array(
              'Text',
              'limit_data',
              array(
                  'label' => 'Count for the gifts. (number of gifts to show).',
                  'value' => 10,
              )
          ),
          array(
            'Radio',
            'pagging',
            array(
              'label' => "Do you want the gifts to be auto-loaded when users scroll down the page?",
              'multiOptions' => array(
                'auto_load' => 'Yes, Auto Load',
                'button' => 'No, show \'View more\' link.',
                'pagging' => 'No, show \'Pagination\'.'
              ),
              'value' => 'auto_load',
            )
          ),

      ),
    ),
    'autoEdit' => true,
  ),
  array(
    'title' => 'SNS - Virtual Gifts - Sent Gifts',
    'description' => 'This widget will display all the sent gifts by the member to other members . Place this widget at My Gifts Page.',
    'category' => 'SNS - Virtual Gifts Plugin',
    'type' => 'widget',
    'name' => 'egifts.sent-gifts',
    'requirements' => array(
      'no-subject',
    ),
    'adminForm' => array(
      'elements' => array(
          array(
              'MultiCheckbox',
              'show_criteria',
              array(
                  'label' => "Choose the options that you want to be displayed in this widget.",
                  'multiOptions' => array(
                      'title' => 'Gift Title',
                      'image' => 'Image',
                      'price' => 'Price',
                      'description' => 'Description',
                      'sentTo' => 'Sent To',
                  ),
                  'escape' => false,
              )
          ),
          array(
            'Text',
            'title_truncation',
                array(
                    'label' => 'Title truncation limit.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Text',
            'description_truncation',
                array(
                    'label' => 'Description truncation limit.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Text',
            'height',
                array(
                    'label' => 'Enter the height of the main photo block.',
                    'value' => 150,
                    'validators' => array(
                    array('Int', true),
                    array('GreaterThan', true, array(0)),
                    )
                )
          ),
          array(
            'Radio',
            'show_item_count',
              array(
                  'label' => 'Do you want to show gift count in this widget?',
                  'value' => 1,
                  'multiOptions'=>array(
                    '1'=> 'yes',
                    '0'=> 'No'
                  ),
              )
          ),
          array(
              'Text',
              'limit_data',
              array(
                  'label' => 'Count for the gifts. (number of gifts to show).',
                  'value' => 10,
              )
          ),
          array(
            'Radio',
            'pagging',
            array(
              'label' => "Do you want the gifts to be auto-loaded when users scroll down the page?",
              'multiOptions' => array(
                'auto_load' => 'Yes, Auto Load',
                'button' => 'No, show \'View more\' link.',
                'pagging' => 'No, show \'Pagination\'.'
              ),
              'value' => 'auto_load',
            )
          ),

      ),
    ),
    'autoEdit' => true,
  ),
	array(
    'title' => 'SNS - Virtual Gifts - View Page Widget',
    'description' => 'This widget will display all the details for the gift at its profile page. Place this widget at the Gift View Page.',
    'category' => 'SNS - Virtual Gifts Plugin',
    'type' => 'widget',
    'name' => 'egifts.gift-view',
    'requirements' => array(
      'no-subject',
    ),
    'adminForm' => array(
      'elements' => array(
          array(
              'MultiCheckbox',
              'show_criteria',
              array(
                  'label' => "Choose the options that you want to be displayed in this widget.",
                  'multiOptions' => array(
                      'title' => 'Gift Title',
                      'image' => 'Image',
                      'price' => 'Price',
                      'description' => 'Description',
                      'sendButton' => 'Send Button',
                      'likeButton' => 'Like Button',
                      'favoriteButton'=>'Favorite Button',
                      'viewCount'=>'View Count',
                      'likeCount'=>'Like Count',
                      'favoriteCount'=>'Favorite Count'
                  ),
                  'escape' => false,
              )
          ),
      ),
    ),
    'autoEdit' => true,
  ),
	  array(
        'title' => 'SNS - Virtual Gifts - Recently Viewed Gifts',
        'description' => 'This widget will display recently viewed gifts on your website. You can place this widget at any page of this plugin.',
        'category' => 'SNS - Virtual Gifts Plugin',
        'type' => 'widget',
        'name' => 'egifts.recently-viewed-item',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'criteria',
                    array(
                        'label' => 'Display Criteria',
                        'multiOptions' =>
                        array(
                            'by_me' => 'Viewed by current member',
                            'by_myfriend' => 'Viewed by current logged-in member\'s friend',
                            'on_site' => 'Viewed by all members of website'
                        ),
                    ),
                ),
                array(
                  'MultiCheckbox',
                  'show_criteria',
                  array(
                      'label' => "Choose the options that you want to be displayed in this widget.",
                      'multiOptions' => array(
                          'title' => 'Gift Title',
                          'image' => 'Image',
                          'price' => 'Price',
                          'description' => 'Description',
                          'sendButton' => 'Send Button',
                          'likeButton' => 'Like Button',
                          'favoriteButton'=>'Favorite Button',
                      ),
                      'escape' => false,
                  )
                ),
                array(
                  'Text',
                  'height',
                      array(
                          'label' => 'Enter the height of the main photo block.',
                          'value' => 150,
                          'validators' => array(
                          array('Int', true),
                          array('GreaterThan', true, array(0)),
                          )
                      )
                ),
                array(
                  'Text',
                  'width',
                    array(
                        'label' => 'Enter the width of the main photo block.',
                        'value' => 150,
                        'validators' => array(
                        array('Int', true),
                        array('GreaterThan', true, array(0)),
                        )
                    )
                ),
                array(
                    'Text',
                    'title_truncation',
                        array(
                            'label' => 'Title truncation limit.',
                            'value' => 150,
                            'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                            )
                        )
                  ),
                  array(
                    'Text',
                    'description_truncation',
                        array(
                            'label' => 'Description truncation limit.',
                            'value' => 150,
                            'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                            )
                        )
                  ),
                array(
                    'Text',
                    'limit_data',
                    array(
                        'label' => 'Count for the gifts. (number of gifts to show).',
                        'value' => 10,
                    )
                ),
            ),
        ),
    ),
);
