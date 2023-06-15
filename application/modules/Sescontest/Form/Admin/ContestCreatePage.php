<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ContestCreatePage.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Admin_ContestCreatePage extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Contest Create Page Visibility Settings')
            ->setDescription('Here, you can choose the settings to be shown / hidden on the Contest Create Page form. The hidden (disabled) settings will be shown on Edit Contest Page (Contest Dashboard).');

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('contest', $viewer, 'auth_view');
    $commentOptions = (array) Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('contest', $viewer, 'auth_comment');
    $availableLabels = array(
        'everyone' => 'Everyone',
        'registered' => 'All Registered Members',
        'owner_network' => 'Friends and Networks',
        'owner_member_member' => 'Friends of Friends',
        'owner_member' => 'Friends Only',
        'owner' => 'Just Me',
    );
    $viewOptions = array_intersect_key($availableLabels, array_flip($viewOptions));
    $commentOptions = array_intersect_key($availableLabels, array_flip($commentOptions));

    $this->addElement('Radio', 'sescontest_show_descriptioncreate', array(
        'label' => 'Show Contest Description',
        'description' => 'Do you want to show “Description” field on contest create page?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.descriptioncreate', 1),
    ));

    $this->addElement('Radio', 'sescontest_show_tagcreate', array(
        'label' => 'Show Tags Options',
        'description' => 'Do you want to show “Tags (keywords)” field on contest create page?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.tagcreate', 1),
    ));

    $this->addElement('Radio', 'sescontest_show_photocreate', array(
        'label' => 'Show Main Photo Of Contest',
        'description' => 'Do you want to show “Main Photo” field of contest on contest create page?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.photocreate', 1),
    ));

    $this->addElement('Radio', 'sescontest_show_1stprizecreate', array(
        'label' => 'Show 1st Prize Award',
        'description' => 'Do you want to show “1st Prize Award” field on contest create page?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.1stprizecreate', 1),
    ));

    $this->addElement('Radio', 'sescontest_show_rulescreate', array(
        'label' => 'Show Contest Rules',
        'description' => 'Do you want to show “Rules” field of contest on contest create page?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.rulescreate', 1),
    ));

    $this->addElement('Radio', 'sescontest_show_viewprivacycreate', array(
        'label' => 'Show View Privacy',
        'description' => 'Do you want to show “View Privacy” setting for contests on contest create page?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.viewprivacycreate', 1),
        'onclick' => 'showViewPrivacy(this.value)',
    ));

    $this->addElement('Select', 'sescontest_default_viewprivacy', array(
        'label' => 'Default View Privacy Option',
        'description' => ' Choose the default view privacy option, only the option chosen below will be able to view the contests on your site.',
        'multiOptions' => $viewOptions,
        'value' => $settings->getSetting('sescontest.default.viewprivacy'),
    ));

    $this->addElement('Radio', 'sescontest_show_commentprivacycreate', array(
        'label' => ' Show Comment Privacy',
        'description' => 'Do you want to show “Comment Privacy” setting for contests on contest create page?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.commentprivacycreate', 1),
        'onclick' => 'showCommentPrivacy(this.value)',
    ));

    $this->addElement('Select', 'sescontest_default_commentprivacy', array(
        'label' => 'Default Comment Privacy Option',
        'description' => ' Choose the default comment privacy option, only the option chosen below will be able to do comments on the contests on your site.',
        'multiOptions' => $commentOptions,
        'value' => $settings->getSetting('sescontest.default.commentprivacy'),
    ));

    $this->addElement('Radio', 'sescontest_show_votecreate', array(
        'label' => 'Display “Show Votes” Options',
        'description' => 'Do you want to show “Show Votes” setting for contests on contest create page? (If you choose No, then the "Show votes during Voting" will be selected by default.)',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.votecreate', 1),
    ));

    $this->addElement('Radio', 'sescontest_show_statuscreate', array(
        'label' => 'Show Status Option',
        'description' => 'Do you want to show “status” field for contests on contest create page? [With this option, users will be able to choose to Save their contests as Draft or Publish them. If you choose No, then the "Published" status will be selected by default.]',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.show.statuscreate', 1),
    ));

    // Add submit button
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}
