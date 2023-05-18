<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Content.php 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
return array(
    array(
        'title' => $view->translate('Advertise: Create an Ad'),
        'description' => $view->translate('This widget tempts users to advertise on your site. It contains a catchy phrase and a linked button to Create an Ad page.'),
        'category' => $view->translate('Advertisements, Community Ads & Marketing Campaigns Plugin'),
        'type' => 'widget',
        'name' => 'sitead.create-ad',
        'defaultParams' => array(
            'title' => $view->translate('Want more Customers?')
        ),
        'adminForm' => array(
            'elements' => array(
            ),
        ),
    ),
    array(
        'title' => $view->translate('User Advertising Navigation'),
        'description' => $view->translate('Display a navigation bar to users to browse through adboard and different advertising options.'),
        'category' => $view->translate('Advertisements, Community Ads & Marketing Campaigns Plugin'),
        'type' => 'widget',
        'name' => 'sitead.user-navigation',
        'adminForm' => array(
            'elements' => array(
            ),
        ),
    ),
    array(
        'title' => $view->translate('Advertise a content Widget'),
        'description' => $view->translate('This widget tempts users to advertise their content on your site. It contains a catchy phrase and a link to Create an Ad page. This widget should be placed on the main page of a content.'),
        'category' => $view->translate('Advertisements, Community Ads & Marketing Campaigns Plugin'),
        'type' => 'widget',
        'name' => 'sitead.getconnection-link',
        'adminForm' => array(
            'elements' => array(
            ),
        ),
    ),
    array(
        'title' => $view->translate('Display Advertisements'),
        'description' => $view->translate('Display advertisements on your site. Multiple settings available in the Edit Settings of this widget.'),
        'category' => $view->translate('Advertisements, Community Ads & Marketing Campaigns Plugin'),
        'type' => 'widget',
        'name' => 'sitead.ads',
        'autoEdit' => true,
        'defaultParams' => array(
            'loaded_by_ajax' => 1,
        ),
        'adminForm' => 'Sitead_Form_Admin_Widget_Ads'
    ),
        )
?>
