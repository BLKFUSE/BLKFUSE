<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

$arrayGallery = array();
if(Engine_Api::_()->getDbtable("modules", "core")->isModuleEnabled("sesdating") && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.pluginactivated')) {

    //New File System Code
    $banner_options = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png')));
    foreach( $files as $file ) {
      $banner_options[$file->storage_path] = $file->name;
    }

  $results = Engine_Api::_()->getDbtable('banners', 'sesdating')->getBanner(array('fetchAll' => true));
  if(is_countable($results) && engine_count($results) > 0 ) {
    foreach ($results as $gallery)
      $arrayGallery[$gallery['banner_id']] = $gallery['banner_name'];
  }
}
$moduleEnable = Engine_Api::_()->sesdating()->getModulesEnable();
$headScript = new Zend_View_Helper_HeadScript();
$headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'externals/ses-scripts/jscolor/jscolor.js');
$headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesbasic/externals/scripts/jquery.min.js');
$dating_theme_widget = array(
		array(
			'title' => 'SNS - Responsive Dating Theme - Custom Navigation Menu',
			'description' => "Displays the plugin name in the menu inside an attractive banner. The menu name is automatically taken from the plugin on which the widget is placed. Edit this widget to configure various settings.",
			'category' => 'SNS - Responsive Dating Theme',
			'type' => 'widget',
			'autoEdit' => false,
			'name' => 'sesdating.custom-navigation-menu',
      'adminForm' => array(
        'elements' => array(
          array(
            'Select',
            'backgroundimage',
            array(
              'label' => 'Choose the Background Image to be shown in this widget.',
              'multiOptions' => $banner_options,
              'value' => '',
            )
          ),
          array(
              'Text',
              'height',
              array(
                  'label' => 'Enter the height of this Banner (in pixels).',
                  'value' => 150,
                  'validators' => array(
                      array('Int', true),
                      array('GreaterThan', true, array(0)),
                  )
              ),
          ),
          array(
              'Select',
              'textalignment',
              array(
                  'label' => 'Title text alignment.',
                  'multiOptions' => array(
                      'center' => 'Center',
                      'left' => 'Left'
                  ),
                  'value' => 'center',
              )
          ),
        ),
      ),
    ),
    array(
        'title' => 'SNS - Responsive Dating Theme - Banner Slideshow',
        'description' => 'Displays banner slideshows as configured by you in the admin panel of this theme. Edit this widget to choose the slideshow to be shown and configure various settings.',
        'category' => 'SNS - Responsive Dating Theme',
        'type' => 'widget',
        'autoEdit' => true,
        'name' => 'sesdating.banner-slideshow',
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'banner_id',
                    array(
                        'label' => 'Choose the Banner to be shown in this widget.',
                        'multiOptions' => $arrayGallery,
                        'value' => 1,
                    )
                ),
                array(
                    'Select',
                    'full_width',
                    array(
                        'label' => 'Do you want to show this Banner in full width?',
                        'multiOptions' => array(
                            '1' => 'Yes',
                            '0' => 'No'
                        ),
                        'value' => 1,
                    )
                ),
                array(
                    'Text',
                    'height',
                    array(
                        'label' => 'Enter the height of this Banner (in pixels).',
                        'value' => 200,
                        'validators' => array(
                            array('Int', true),
                            array('GreaterThan', true, array(0)),
                        )
                    ),
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Responsive Dating Theme - Landing Page',
        'description' => 'This widget shows the Landing Page of Responsive Dating Theme.',
        'category' => 'SNS - Responsive Dating Theme',
        'type' => 'widget',
        'name' => 'sesdating.landing-page',
        'autoEdit' => false,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'backgroundimage',
                    array(
                    'label' => 'Choose the Background Image to be shown in this widget.',
                    'multiOptions' => $banner_options,
                    'value' => '',
                    )
                ),
                array(
                    'Text',
                    'heading',
                    array(
                    'label' => 'Enter Heading',
                    'value' => 'Find Your Soulmate Today',
                    )
                ),
                array(
                    'Text',
                    'description',
                    array(
                    'label' => 'Enter Description',
                    'value' => 'Get Ready for your Soulmate, Start Dating Today.',
                    )
                ),
            ),
        ),
    ),
    array(
			'title' => 'SNS - Responsive Dating Theme - Footer Menu',
			'description' => 'This widget shows the site-wide footer menu. You can edit its contents in your menu editor. You can upload icons for each menu item of Footer Menu from the "Manage Menu Icons" section of Responsive Dating Theme.',
			'category' => 'SNS - Responsive Dating Theme',
			'type' => 'widget',
			'name' => 'sesdating.menu-footer',
      'autoEdit' => false,
    ),
    array(
        'title' => 'SNS - Responsive Dating Theme - Login',
        'description' => 'This widget displays login form in a transparent block with an image in background of the page.',
        'category' => 'SNS - Responsive Dating Theme',
        'type' => 'widget',
        'name' => 'sesdating.login',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Radio',
                    'showlogo',
                    array(
                        'label' => 'Do you want to show logo?.',
                        'multiOptions' => array(
                        '1' => 'Yes',
                        '0' => 'No'

                        ),
                        'value' => 1,
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Responsive Dating Theme - Header',
        'description' => '',
        'category' => 'SNS - Responsive Dating Theme',
        'type' => 'widget',
        'name' => 'sesdating.header',
        'autoEdit' => false,
    ),
    array(
        'title' => 'SNS - Responsive Dating Theme - Landing Page Two Main',
        'description' => '',
        'category' => 'SNS - Responsive Dating Theme',
        'type' => 'widget',
        'name' => 'sesdating.lp-two-main',
        'autoEdit' => false,
    ),
    array(
        'title' => 'SNS - Responsive Dating Theme - Landing Page Two Counters',
        'description' => '',
        'category' => 'SNS - Responsive Dating Theme',
        'type' => 'widget',
        'name' => 'sesdating.lp-two-counters',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'heading',
                    array(
                        'label' => 'Enter Heading for this widget.',
                        'value' => 'It All Starts With A Date',
                    )
                ),
                array(
                    'Text',
                    'description',
                    array(
                        'label' => 'Enter Description for this widget.',
                        'value' => 'You Find Us, Finally, And You Are Already In Love. More Than 5.000.000 Around The World Already Shared The Same Experience And Uses Our System. Joining Us Today Just Got Easier!',
                    )
                ),
                array(
                    'Text',
                    'button1text',
                    array(
                        'label' => 'Enter Button - 1 Text.',
                        'value' => 'Join Us For Free',
                    )
                ),
                array(
                    'Text',
                    'button1link',
                    array(
                        'label' => 'Enter Button - 1 Link.',
                        'value' => 'signup',
                    )
                ),
                array(
                    'Text',
                    'button2text',
                    array(
                        'label' => 'Enter Button - 2 Text.',
                        'value' => 'Watch Video',
                    )
                ),
                array(
                    'Text',
                    'button2link',
                    array(
                        'label' => 'Enter Button - 2 Link.',
                        'value' => 'videos',
                    )
                ),
                array(
                    'Text',
                    'counter1value',
                    array(
                        'label' => 'Enter Counter 1 Value.',
                        'value' => '2278',
                    )
                ),
                array(
                    'Text',
                    'counter2value',
                    array(
                        'label' => 'Enter Counter 2 Value.',
                        'value' => '20000',
                    )
                ),
                array(
                    'Text',
                    'counter3value',
                    array(
                        'label' => 'Enter Counter 3 Value.',
                        'value' => '10000',
                    )
                ),
                array(
                    'Text',
                    'counter4value',
                    array(
                        'label' => 'Enter Counter 4 Value.',
                        'value' => '10000',
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Responsive Dating Theme - Landing Page Two Stories',
        'description' => '',
        'category' => 'SNS - Responsive Dating Theme',
        'type' => 'widget',
        'name' => 'sesdating.lp-two-stories',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'heading',
                    array(
                        'label' => 'Enter Heading for this widget.',
                        'value' => 'Sweet stories from our Lovers',
                    )
                ),
                array(
                    'Text',
                    'limit',
                    array(
                        'label' => 'Enter Limit for stories in this widget.',
                        'value' => '3',
                    )
                ),
                array(
                    'Select',
                    'popularitycriteria',
                    array(
                        'label' => 'Choose the popularity criteria in this widget.',
                        'multiOptions' => array(
                            'creation_date' => 'Recently Created',
                            'view_count' => 'View Count',
                            'like_count' => 'Most Liked',
                            'comment_count' => 'Most Commented',
                            'modified_date' => 'Recently Modified'
                        ),
                    )
                ),
            ),
        ),
    ),
    array(
        'title' => 'SNS - Responsive Dating Theme - Landing Page Two Members',
        'description' => '',
        'category' => 'SNS - Responsive Dating Theme',
        'type' => 'widget',
        'name' => 'sesdating.lp-two-members',
        'autoEdit' => false,
    ),
    array(
        'title' => 'SNS - Responsive Dating Theme - Landing Page Two Groups',
        'description' => '',
        'category' => 'SNS - Responsive Dating Theme',
        'type' => 'widget',
        'name' => 'sesdating.lp-two-groups',
        'autoEdit' => true,
        'adminForm' => array(
            'elements' => array(
                array(
                    'Text',
                    'heading',
                    array(
                        'label' => 'Enter Heading for this widget.',
                        'value' => 'Recently Active Groups',
                    )
                ),
                array(
                    'Text',
                    'limit',
                    array(
                        'label' => 'Enter Limit for stories in this widget.',
                        'value' => '4',
                    )
                ),
                array(
                    'Select',
                    'popularitycriteria',
                    array(
                        'label' => 'Choose the popularity criteria in this widget.',
                        'multiOptions' => array(
                            'creation_date' => 'Recently Created',
                            'view_count' => 'View Count',
                            'like_count' => 'Most Liked',
                            'comment_count' => 'Most Commented',
                            'modified_date' => 'Recently Modified'
                        ),
                    )
                ),
            ),
        ),
    ),
  );

return $dating_theme_widget;
