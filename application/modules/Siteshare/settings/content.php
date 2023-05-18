<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: content.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
return array(
  array(
    'title' => 'Share Buttons',
    'description' => 'Displays share buttons to share the content / page.',
    'category' => 'Advanced Share Plugin',
    'type' => 'widget',
    'name' => 'siteshare.share-list-buttons',
    'defaultParams' => array(
      'title' => '',
      'numberOfButtons' => '5',
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'buttonLabel',
          array(
            'label' => 'Do you want to show label of share buttons?',
            'multiOptions' => array(
              'diff_label' => 'Show reverse text color and background color for icon and label',
              'same_label' => 'Show same text color and background color for icon and label',
              0 => 'No'
            ),
            'value' => 'same_label',
          )
        ),
        array(
          'Select',
          'columns',
          array(
            'label' => 'How many share buttons you want to show in each row? [Note: This setting will only work if you will show label of share buttons.]',
            'multiOptions' => array(
              '1' => '1 Button',
              '2' => '2 Buttons',
              '3' => '3 Buttons',
              '4' => '4 Buttons',
              '5' => '5 Buttons',
              '6' => '6 Buttons',
            ),
            'value' => '4',
          )
        ),
        array(
          'Text',
          'numberOfButtons',
          array(
            'description' => 'How many share buttons you want to showcase?',
            'value' => '5',
          )
        ),
        array(
          'Select',
          'statsCount',
          array(
            'label' => 'Do you want to show count of this page shared on different social sites?',
            'multiOptions' => array(
              2 => 'Show for all sites',
              1 => 'Show total only',
              0 => 'No'
            ),
            'value' => 0,
          )
        ),
        array(
          'Select',
          'round',
          array(
            'label' => 'Do you want to show round buttons for share options?',
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
            ),
            'value' => 0,
          )
        ),
        array(
          'Select',
          'moreButton',
          array(
            'label' => 'Do you want to show "More" button?',
            'multiOptions' => array(
              '1' => 'Yes',
              0 => 'No'
            ),
            'value' => '1',
          )
        ),
      )
    ),
  ),
  array(
    'title' => 'Share Action Buttons',
    'description' => 'Displays share action buttons to share the content / page.',
    'category' => 'Advanced Share Plugin',
    'type' => 'widget',
    'name' => 'siteshare.share-action-buttons',
    'defaultParams' => array(
      'title' => '',
      'numberOfButtons' => '5',
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Select',
          'layout',
          array(
            'label' => 'Select the action buttons design:',
            'multiOptions' => array(
              'border_count' => 'Show site color bottom border',
              'box_count' =>  'Show share count in box',
              'box_2_count' => 'Show site color box with border',
              'box_2_count sswob' => 'Show site color box without border',
            ),
            'value' => 'border_count',
          )
        ),
        array(
          'Select',
          'columns',
          array(
            'label' => 'How many action buttons you want to show in each row?',
            'multiOptions' => array(
              '1' => '1 Button',
              '2' => '2 Buttons',
              '3' => '3 Buttons',
              '4' => '4 Buttons',
              '5' => '5 Buttons',
              '6' => '6 Buttons',
              '7' => '7 Buttons',
              '8' => '8 Buttons',
            ),
            'value' => '4',
          )
        ),
        array(
          'Text',
          'numberOfButtons',
          array(
            'description' => 'How many share action buttons you want to showcase?',
            'value' => '5',
          )
        ),
        array(
          'Select',
          'moreButton',
          array(
            'label' => 'Do you want to show "More" button?',
            'multiOptions' => array(
              '1' => 'Yes',
              0 => 'No'
            ),
            'value' => '1',
          )
        ),
      )
    ),
  ),
  array(
    'title' => 'Sticky SideBar (Floating) Share Buttons',
    'description' => 'Display share buttons as floating sidebar to share the content / page.',
    'category' => 'Advanced Share Plugin',
    'type' => 'widget',
    'name' => 'siteshare.share-buttons',
    'defaultParams' => array(
      'title' => '',
      'numberOfButtons' => '5',
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Hidden',
          'title',
          array(
          )
        ),
        array(
          'select',
          'alignment',
          array(
            'label' => 'On which side you want to show the share options?',
            'multiOptions' => array(
              'left' => 'Left',
              'right' => 'Right'
            ),
            'value' => 'right',
          )
        ),
        array(
          'select',
          'buttonLabel',
          array(
            'label' => 'Do you want to show labels of share buttons?',
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
            ),
            'value' => 1,
          )
        ),
        array(
          'Select',
          'statsCount',
          array(
            'label' => 'Do you want to show count of this page shared on different social sites?',
            'multiOptions' => array(
              2 => 'Show for all sites',
              1 => 'Show total only',
              0 => 'No'
            ),
            'value' => 0,
          )
        ),
        array(
          'Text',
          'verticalAlignment',
          array(
            'label' => 'Set the vertical alignment of share buttons from here (Write the margin in %)',
            'value' => '20%',
          )
        ),
        array(
          'Text',
          'numberOfButtons',
          array(
            'label' => 'How many social bookmark options you want to show at sidebar?',
            'value' => '5',
          )
        ),
        array(
          'Select',
          'moreButton',
          array(
            'label' => 'Do you want to show "More" button?',
            'multiOptions' => array(
              '1' => 'Yes',
              0 => 'No'
            ),
            'value' => '1',
          )
        ),
      )
    ),
  ),
  array(
    'title' => 'Sticky SideBar (Floating) Share Action Buttons',
    'description' => 'Displays share action buttons as floating sidebar to share the content / page.',
    'category' => 'Advanced Share Plugin',
    'type' => 'widget',
    'name' => 'siteshare.share-action-side-buttons',
    'defaultParams' => array(
      'title' => '',
      'numberOfButtons' => '5',
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Hidden',
          'title',
          array(
          )
        ),
        array(
          'select',
          'alignment',
          array(
            'label' => 'On which side you want to show the share options?',
            'multiOptions' => array(
              'left' => 'Left',
              'right' => 'Right'
            ),
            'value' => 'left',
          )
        ),
        array(
          'Text',
          'verticalAlignment',
          array(
            'label' => 'Set the vertical alignment of share action buttons from header (Write the margin in %)',
            'value' => '20%',
          )
        ),
        array(
          'Text',
          'numberOfButtons',
          array(
            'label' => 'How many social bookmark options you want to show at sidebar?',
            'value' => '5',
          )
        ),
        array(
          'Select',
          'moreButton',
          array(
            'label' => 'Do you want to show "More" button?',
            'multiOptions' => array(
              '1' => 'Yes',
              0 => 'No'
            ),
            'value' => '1',
          )
        ),
      )
    ),
  ),
  array(
    'title' => 'Automatic Share Flying',
    'description' => 'Displays share buttons in a popup to share the content / page.',
    'category' => 'Advanced Share Plugin',
    'type' => 'widget',
    'name' => 'siteshare.share-buttons-popup',
    'defaultParams' => array(
      'heading' => 'Share This Page',
      'numberOfButtons' => '4',
      'alignment' => 'center'
    ),
    'autoEdit' => true,
    'adminForm' => array(
      'elements' => array(
        array(
          'Hidden',
          'title',
          array(
          )
        ),
        array(
          'select',
          'alignment',
          array(
            'label' => 'On which side you want to show the share options?',
            'multiOptions' => array(
              'center' => 'Center',
              'botttom_left' => 'Bottom Left',
              'botttom_center' => 'Bottom Center',
              'botttom_right' => 'Bottom Right'
            ),
            'value' => 'center',
          )
        ),
        array(
          'Select',
          'buttonLabel',
          array(
            'label' => 'Do you want to show labels of share buttons?',
            'multiOptions' => array(
              'diff_label' => 'Show reverse text color and background color for icon and label',
              'same_label' => 'Show same text color and background color for icon and label',
              0 => 'No'
            ),
            'value' => 'same_label',
          )
        ),
        array(
          'Text',
          'heading',
          array(
            'label' => 'Enter the heading which you want to show.',
            'value' => 'Share the Page',
          )
        ),
        array(
          'Text',
          'message',
          array(
            'label' => 'Enter the message which you want to show.',
            'value' => 'If you liked this page, please share it with your friends.',
          )
        ),
        array(
          'Text',
          'numberOfButtons',
          array(
            'label' => 'How many social bookmarks options you want to show at fly box?',
            'value' => '5',
          )
        ),
        array(
          'Select',
          'round',
          array(
            'label' => 'Do you want to show round buttons for share options?',
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
            ),
            'value' => 0,
          )
        ),
        array(
          'Select',
          'totalStats',
          array(
            'label' => 'Do you want to show total count of this page shared on different social sites in the heading?',
            'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
            ),
            'value' => 0,
          )
        ),
        array(
          'Select',
          'statsCount',
          array(
            'label' => 'Do you want to show count of this page shared on different social sites?',
            'multiOptions' => array(
              2 => 'Show for all sites',
              1 => 'Show total only',
              0 => 'No'
            ),
            'value' => 0,
          )
        ),
        array(
          'Select',
          'columns',
          array(
            'label' => 'How many social bookmark options you want to show in each row? (Note: This setting will not work if ‘No’ is selected for showing label of share buttons)',
            'multiOptions' => array(
              2 => '2',
              3 => '3',
              4 => '4'
            ),
            'value' => '2',
          )
        ),
        array(
          'Select',
          'moreButton',
          array(
            'label' => 'Do you want to show "More" button?',
            'multiOptions' => array(
              '1' => 'Yes',
              0 => 'No'
            ),
            'value' => '1',
          )
        ),
      )
    ),
  ),
);

