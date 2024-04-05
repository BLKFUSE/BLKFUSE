<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: content.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

$seslocation = array();
if (Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('seslocation')) {
  $seslocation = array(
      'Radio',
      'locationEnable',
      array(
          'label' => 'Do you want to show content in this widget based on the Location chosen by users on your website? (This is dependent on the “Location Based Member & Content Display Plugin”. Also, make sure location is enabled and entered for the content in this plugin. If setting is enabled and the content does not have a location, then such content will not be shown in this widget.)
',
          'multiOptions' => array('1' => 'Yes', '0' => 'No'),
          'value' => 0
      ),
  );
}
$categories = $banners = array();
if (Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sescommunityads') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.pluginactivated',0)) {
    $categories = Engine_Api::_()->getDbTable('categories', 'sescommunityads')->getCategoriesAssoc();
    $categories = array('' => '') + $categories;

    $getBanner = Engine_Api::_()->getDbTable('banners', 'sescomadbanr')->getBanner(array('fetchAll' => 1));
    if(engine_count($getBanner)){
        foreach($getBanner as $banner){
            $banners[$banner->banner_id] = $banner->banner_name;
        }
    }

    //Rented Work
    $packages = Engine_Api::_()->getDbTable('packages','sescommunityads')->getPackage(array('enabled'=>1, 'param' => 2));
    $packageArray = array('' => '');
    foreach($packages as $package) {
        $packageArray[$package->package_id] = $package->title;
    }

    //New File System Code
    $banner_options = array('' => '');
    $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
    foreach( $files as $file ) {
      $banner_options[$file->storage_path] = $file->name;
    }
}
return array(
  array(
		'title' => 'SNS - Community Ads - Banner Widget Ads',
		'description' => 'This widget displays banner ads only in the left/right sidebar columns of your website.',
		'category' => 'SNS - Community Advertisements Plugin',
		'type' => 'widget',
		'name' => 'sescommunityads.banner-ads',
		'autoEdit' => true,
		'adminForm' => array(
            'elements' => array(
                array(
                    'Select',
                    'rented',
                    array(
                            'label' => 'Do you want to rent this space? If you choose to rent this space, then you will have to select the package in which ads created by using “Create Ad” button will be created. You can create package from the admin panel of this plugin.',
                            'value' => '0',
                            'multiOptions' => array('1'=>'Yes, rent this space.','0'=>'No, this widget will display ads normally.'),
                    )
                ),
                array(
                    'Select',
                    'package_id',
                    array(
                            'label' => 'Choose the package which will be associated with ad created from this space.',
                            'value' => '',
                            'multiOptions' => $packageArray,
                    )
                ),
                array(
                    'Select',
                    'defaultbanner',
                    array(
                    'label' => 'Choose the default banner which will be shown when the space of this widget is available for rent.',
                    'multiOptions' => $banner_options,
                    'value' => '',
                    )
                ),
                array(
                    'Select',
                    'bannerid',
                    array(
                            'label' => 'Choose banner size that you want to show in this widget.',
                            'value' => '',
                            'multiOptions' => $banners,
                    )
                ),
                $seslocation,
                array(
                    'Select',
                    'category',
                    array(
                            'label' => 'Choose category you want to display in this widget.',
                            'value' => '',
                            'multiOptions' => $categories,
                    )
                ),
                array(
                    'Select',
                    'featured_sponsored',
                    array(
                            'label' => 'Choose criteria from below to show in this widget.',
                            'value' => '3',
                            'multiOptions' => array('1'=>'Featured','2'=>'Sponsored','4'=>'Featured & Sponsored','3'=>'All ads'),
                    )
                ),
                array(
                    'Text',
                    'limit',
                    array(
                        'label' => 'Count (number of Advertisement to show)',
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
);
