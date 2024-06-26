<?php
/**
 * SocialEngine
 *
 * @category   Application_Widget
 * @package    Branding
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     Charlotte
 */
return array(
  'package' => array(
    'type' => 'widget',
    'name' => 'branding',
    'version' => '6.5.1',
    'revision' => '$Revision: 9747 $',
    'path' => 'application/widgets/branding',
    'repository' => 'socialengine.com',
    'title' => 'SocialEngine Branding',
    'description' => 'Displays a "powered by SocialEngine" link.',
    'author' => 'SocialEngine Core',
    'directories' => array(
      'application/widgets/branding',
    ),
  ),

  // Backwards compatibility
  'type' => 'widget',
  'name' => 'branding',
  'version' => '6.5.1',
  'revision' => '$Revision: 9747 $',
  'title' => 'SocialEngine Branding',
  'description' => 'Displays a "powered by SocialEngine" link.',
  'author' => 'SocialEngine Core',
  'category' => 'Widgets',
  'autoEdit' => true,
  'adminForm' => array(
    'elements' => array(
      array(
        'Select',
        'showVersion',
        array(
          'label' => 'Do you want to show SE version installed on this site?',
          'multiOptions' => array(
            0 => "No",
            1 => "Yes",
          ),
        )
      ),
    ),
  ),
);
