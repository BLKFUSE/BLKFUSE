<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Global.php  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Sitead_Form_Admin_Global extends Engine_Form {

    public function init() {
        $this
                ->setTitle('Global Settings')
                ->setDescription('These settings affect all members of your site.');

        // Adding Settings of "Site Ads".
        $this->addElement('Dummy', 'dummy_sitead_package', array('content' => '<h3>'.'Package Settings'.'</h3>'));

        $this->addElement('Radio', 'sitead_package_view', array(
            'label' => 'Package View',
            'description' => 'Select the view type of packages that will be shown while ad creation.',
            'multiOptions' => array(
                0 => 'Horizontal',
                1 => 'Vertical'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.package.view', 0),
        ));

        $packageInfoArray = array('price' => "Price", 'recurring' => 'Payment Cycle', 'targeting' => "Targeting", 'featured' => "Featured", 'sponsored' => "Sponsored", 'visiblity' => "Ad Visiblity", 'allowad' => 'Allow Ads', 'autoapprove' => 'Auto Approved Ads', 'format' => 'Ads Format', 'type' => 'Ads Type', 'youcanadvertise' => "You can advertise", 'description' => "Description");
        $this->addElement('MultiCheckbox', 'sitead_package_information', array(
            'label' => 'Package Information',
            'description' => 'Select the information options that you want to be available in package details.',
            'multiOptions' => $packageInfoArray,
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.package.information', array_keys($packageInfoArray)),
        ));

        $packageAdFormatArray = array('carousel' => 'Carousel Ads', 'image' => 'Image Ads', 'video' => 'Video Ads');
        $this->addElement('MultiCheckbox', 'sitead_package_adformat', array(
            'label' => 'Ads Format Information',
            'description' => 'Select the Ads Format options that you want to be available in package details. ',
            'multiOptions' => $packageAdFormatArray,
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.package.adformat', array_keys($packageAdFormatArray)),
        ));
        // Adding Settings of "Site Ads".
        $this->addElement('Dummy', 'dummy_sitead_title', array('content' => '<h3>'.'Community Ads Settings'.'</h3>'));

        // ADVERTISMENT TITLE LENGTH
        $this->addElement('Text', 'ad_char_title', array(
            'label' => 'Ads Title Length',
            'maxlength' => 3,
            'allowEmpty' => false,
            'required' => true,
            'description' => 'Enter the maximum character length of Ad titles.',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.title', 25),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
                new Engine_Filter_StringLength(array('max' => '3')),
            )
        ));
        // ADVERTISEMENT BODY LENGTH
        $this->addElement('Text', 'ad_char_body', array(
            'label' => 'Ads Body Length',
            'maxlength' => 3,
            'allowEmpty' => false,
            'required' => true,
            'description' => 'Enter the maximum character length of Ad bodies / descriptions.',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.body', 135),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
                new Engine_Filter_StringLength(array('max' => '3')),
            )
        ));

         $this->addElement('Text', 'ad_slide_limit', array(
            'label' => 'Slides in Carousel Ads',
            'maxlength' => 1,
            'allowEmpty' => false,
            'required' => true,
            'description' => 'Enter the maximum number of slides you want to create for Carousel Ads (Minimum slide value will be 2 and maximum slide value will be 9).',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.slide.limit', 5),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
                new Engine_Filter_StringLength(array('max' => '1')),
            )
        ));

        // Create ad link on the adboard page and other ad block widgets
        $this->addElement('Radio', 'adblock_create_link', array(
            'label' => 'Create an Ad Link for Visitors in Ad Blocks',
            'description' => 'Do you want to show "Create an Ad" links in Ad Blocks to non-logged-in visitors? (If a non-logged in visitor clicks on a "Create an Ad" link, he will be asked to login first.)',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('adblock.create.link', 1),
        ));

        // Hide Custom Ad Target URL
        $this->addElement('Radio', 'custom_ad_url', array(
            'label' => 'Ads Target URL',
            'description' => 'Do you want the domain of Ads Target URLs to be displayed in Ads? (Choosing "No" over here will hide the URL that comes below the Ad Title.)',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('custom.ad.url', 0),
        ));

        // Adding Settings of "Sponsored Story".
        $this->addElement('Dummy', 'dummy_general_title', array('content' => '<h3>'.'General Settings'.'</h3>'));

        $this->addElement('Radio', 'sitead_coreFeed_enable', array(
            'label' => 'Show Ads in Core Feed',
            'description' => 'Do you want to show Ads in Core Activity Feed?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.coreFeed.enable', 0),
        ));

        $this->addElement('Text', 'sitead_coreFeed_position', array(
            'label' => 'Ads Placement',
            'description' => 'After how many feeds you want to show Ads in core feed?',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sitead.coreFeed.position', 5),
        ));

        // ADVERTISING LINK
        $this->addElement('Radio', 'ad_show_menu', array(
            'label' => 'Advertising Link',
            'description' => 'Select the location of the link for Ad Board page.',
            'multiOptions' => array(
                3 => 'Main Navigation Menu',
                2 => 'Mini Navigation Menu',
                1 => 'Footer Menu',
                0 => 'Member Home Page Left side Navigation'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.show.menu', 3),
        ));
    
        // NUMBER OF ADVERTISMENT ON AD BOARD
        $this->addElement('Text', 'ad_board_limit', array(
            'label' => 'Ads in Ad Board',
            'maxlength' => 3,
            'allowEmpty' => false,
            'required' => true,
            'description' => 'Enter the maximum number of ads to be displayed on the Ad Board Page.',
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.board.limit', 25),
            'filters' => array(
                'StripTags',
                new Engine_Filter_Censor(),
                new Engine_Filter_StringLength(array('max' => '3')),
            )
        ));


        $this->addElement('Select', 'ad_statistics_limit', array(
            'label' => 'Duration for Statistics & Reports',
            'description' => 'Select the duration for which users will be able to see Statistics and Reports of their advertisements on your site. [Note: All the statistics & reports, before the selected duration, will be deleted automatically and will not be recoverable.]',
            'multiOptions' => array(1 => '1 Year', 2 => '2 Years', 3 => '3 Years', 4 => '4 Years', 5 => '5 Years'
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.statistics.limit', 3)
        ));

        // ad cancel on adboard and other ad blocks
        $this->addElement('Radio', 'adcancel_enable', array(
            'label' => 'Report an Ad',
            'description' => 'Do you want to allow members to cancel or report an ad? (If set no, the cross mark[X] will not appear on the ads.)',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('adcancel.enable', 1),
        ));


        // show "Ad Board" menu in "Advertising Main Navigation Menu
        $this->addElement('Radio', 'show_adboard', array(
            'label' => 'Visibilty of Ad Board link',
            'description' => 'Do you want to show "Ad Board" link in "Advertising Main Navigation Menu" bar ?',
            'multiOptions' => array(
                1 => 'Yes',
                0 => 'No',
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('show.adboard', 1),
        ));

        $this->addElement('Text', 'ad_saleteam_con', array(
            'label' => 'Contact Number(s) of Sales Team',
            'description' => 'Specify the contact number(s) for receiving queries regarding advertisements on your site. These numbers will be displayed on the "Contact Sales Team" page of the "Help & Learn More" section. (Separate multiple numbers by commas.)',
            'value' => Engine_Api::_()->getApi('settings', 'core')->ad_saleteam_con,
        ));

        $this->addElement('Text', 'ad_saleteam_email', array(
            'label' => 'Email Address(es) of Sales Team',
            'description' => 'Specify the email address(es) for receiving queries regarding advertisements on your site. These addresses will be displayed on the "Contact Sales Team" page of the "Help & Learn More" section. (Separate multiple addresses by commas.)',
            'validators' => array(
               'EmailAddress',
              ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->ad_saleteam_email,
        ));


        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $localeObject = Zend_Registry::get('Locale');
        $currencyCode = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD');
        $currencyName = Zend_Locale_Data::getContent($localeObject, 'nametocurrency', $currencyCode);
        // Element: currency
        $this->addElement('Dummy', 'currency', array(
            'label' => 'Currency',
            'description' => "<b>" . $currencyName . "</b> <br /> <a href='" . $view->url(array('module' => 'payment', 'controller' => 'settings'), 'admin_default', true) . "' target='_blank'>" . Zend_Registry::get('Zend_Translate')->_('edit currency') . "</a>",
        ));
        $this->getElement('currency')->getDecorator('Description')->setOptions(array('placement', 'APPEND', 'escape' => false));

        // Element: benefit
        $this->addElement('Radio', 'advertise_benefit', array(
            'label' => 'Payment Status for Ad Activation',
            'description' => "Do you want to activate advertisements immediately after payment just before the payment passes the gateways' fraud checks? This may take time lag from 20 minutes to 4 days, depending on the circumstances and the gateway. (Note: If you want to manually activate ads, then you can set this while creating an ad package.)",
            'multiOptions' => array(
                'all' => 'Activate advertisements immediately',
                'some' => 'Activate if member has an existing successful transaction, wait if this is their first',
                'none' => 'Wait until the gateway signals of payment completion delivers successfully',
            ),
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('advertise.benefit', 'all'),
        ));

        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
    }

}
