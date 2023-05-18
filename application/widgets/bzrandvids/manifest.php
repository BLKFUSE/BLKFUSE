<?php 
/**
 * SocialEngine
 *
 * @category   Application_Widget
 * @package    BryZar Random Videos
 * @copyright  Copyright 2018 - 2019 BryZar
 * @license    https://www.bryzar.com/terms
 * @author     data66, BryZar/ScriptTechs
 * 
 */

return array (
  'package' => 
  array (
    'type' => 'widget',
    'name' => 'bzrandvids',
    'version' => '5.0.0',
    'sku' => 'bzrandomvideos',
    'path' => 'application/widgets/bzrandvids',
    'title' => 'BryZar Random Videos',
    'description' => 'Random videos by Bryzar hosting and web services.',
    'author' => '<a href="https://www.bryzar.com" target="_blank">BryZar Web Services</a>',
    'actions' => 
    array (
      0 => 'install',
      1 => 'upgrade',
      2 => 'refresh',
      3 => 'remove',
    ),
    'dependencies' => array(
            array(
                'type' => 'module',
                'name' => 'core',
                'minVersion' => '4.10.5',
            ),
        ),      
    'directories' => 
    array (
      0 => 'application/widgets/bzrandvids',
    ),
    'files' => array(
            'application/languages/en/bzrandvids.csv',
        ),     
  ),
  'type' => 'widget',
  'name' => 'bzrandvids',
  'version' => '5.0.0',
  'title' => 'BryZar Random Videos',
  'description' => 'Random videos by <a href="https://www.bryzar.com" target="_blank">BryZar</a> hosting and web services.',
  'category' => 'Widgets',
  'adminForm' =>
    array(
        'elements' =>
        array(
            array(
                'Radio',
                'bzAlign',
                array(
                    'label' => 'Vertical or Horizontal?',
                    'multiOptions' => array(
                        1 => 'Vertical',
                        0 => 'Horizontal',
                    )
                )
            ),
            array(
                'Text',
                'bzVidView',
                array(
                    'label' => 'Number to show',
                    'description' => 'Enter the amount of videos you want to show (default: 4)',
                )
            ),
            array(
                'Text',
                'bzDesVlength',
                array(
                    'label' => 'Video text length',
                    'description' => 'Enter the character length of the video text to show (default: 300)',
                )
            ),
            array(
                'Text',
                'bzVidCat',
                array(
                    'label' => 'Video Category Number to show',
                    'description' => 'Enter the category number of the videos you want to show. Leave blank for all.',
                )
            ),
        )
    ),    
); ?>