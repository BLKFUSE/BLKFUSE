<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ContestCreatePageSettings.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Form_Admin_ContestCreatePageSettings extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Contest Creation Settings')
            ->setDescription('Here, you can configure the settings which are related to the Contest Creation. The settings enabled or disabled will effect contest creation page and popup both.');

    $this->addElement('Radio', 'sescontest_open_smoothbox', array(
        'label' => 'Display Creation Form in Page or Popup?',
        'description' => 'Do you want to open the create contest form in \'Popup\' or redirect users to create contest \'Page\' when they click on the "Create New Contest" link available in the Main Navigation Menu of this plugin?',
        'multiOptions' => array(
            '1' => 'Yes, open form in popup',
            '0' => 'No, redirect users to create contest page',
        ),
        'value' => $settings->getSetting('sescontest.open.smoothbox', 0),
    ));

    $this->addElement('Radio', 'sescontest_enable_addcontestshortcut', array(
        'label' => 'Show “Create New Contest” Icon',
        'description' => 'Do you want to show “Create New Contest” icon in the bottom right side of all pages of this plugin?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sescontest.enable.addcontestshortcut', 1),
        'onclick' => 'showQuickOption(this.value)',
    ));

    $this->addElement('Radio', 'sescontest_icon_open_smoothbox', array(
        'label' => 'Redirect to Create Page or Popup',
        'description' => 'Do you want to open the create contest form in \'Popup\' or redirect users to create contest \'Page\' when they click on the "Create New Contest Icon" available in the bottom right side of all pages of this plugin?',
        'multiOptions' => array(
            '1' => 'Yes, open form in popup',
            '0' => 'No, redirect users to create contest page',
        ),
        'value' => $settings->getSetting('sescontest.icon.open.smoothbox', 0),
    ));

    $this->addElement('Radio', 'sescontest_category_selection', array(
        'label' => 'Choose Category Before Creating Contest',
        'description' => 'Do you want to show the "Category Selection Page" as the first page, when user creates a contest. If Yes, then user will be moved to Contest Create Form only after selecting the category. If No, then user will be directly moved to Contest Create Form. Note: This setting will work on the Contest Create Page only and not in the Contest Create Popup.',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sescontest.category.selection', 1),
        'onclick' => 'showCategoryIcon(this.value)',
    ));

    $this->addElement('Radio', 'sescontest_category_icon', array(
        'label' => 'Display Category Image or Icon',
        'description' => 'Choose from below what do you want to show with the Category name on Category Selection Page which comes before the contest create page. All the icons and thumbnail can be configured from the "Categories & Profile Fields" section of this plugin.',
        'multiOptions' => array(
            0 => 'Category Colored Icon',
            1 => 'Category Icon',
            2 => 'Category Thumbnail',
        ),
        'value' => $settings->getSetting('sescontest.category.icon', 1),
    ));

    $this->addElement('Select', 'sescontest_redirect', array(
        'label' => 'Redirection After Contest Creation',
        'description' => 'Choose from below where do you want to redirect users after a contest is successfully created.',
        'multiOptions' => array(
            '0' => 'On Contest Dashboard Page',
            '1' => 'On Contest Profile Page',
        ),
        'value' => $settings->getSetting('sescontest.redirect', 1),
    ));

    $this->addElement('Select', 'sescontest_autoopenpopup', array(
        'label' => 'Auto-Open Advanced Share Popup',
        'description' => 'Do you want the "Advanced Share Popup" to be auto-opened after the contest is created? [Note: This setting will only work if you have placed "Advanced Share Widget" on Contest Profile page or Contest Dashboard, wherever user is redirected just after contest creation.]',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.autoopenpopup', 1),
    ));

    $this->addElement('Radio', 'sescontest_edit_award', array(
        'label' => 'Edit Awards',
        'description' => 'Do you want to allow owners of the contests to edit Awards of their contests after creating the contests? (If yes, then owners will be able to edit all the awards only until the start date of Entry Submission.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No'
        ),
        'value' => $settings->getSetting('sescontest.edit.award', 1),
    ));

    $this->addElement('Radio', 'sescontest_edit_url', array(
        'label' => 'Edit Custom URL',
        'description' => 'Do you want to allow users to edit the custom URL of their contests after its creation from the contests dashboard?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.edit.url', 0),
    ));

    $this->addElement('Radio', 'sescontest_category_required', array(
        'label' => 'Make Contest Categories Mandatory',
        'description' => 'Do you want to make category field mandatory when users create or edit their contests?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.category.required', 0),
    ));

    $this->addElement('Radio', 'sescontest_enable_description', array(
        'label' => 'Enable Contest Description',
        'description' => 'Do you want to enable description for contests on your website?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.enable.description', 1),
        'onclick' => 'showContestDescription(this.value);',
    ));

    $this->addElement('Radio', 'sescontest_description_required', array(
        'label' => 'Make Contest Description Mandatory',
        'description' => 'Do you want to make description field mandatory when users create or edit their contests?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.description.required', 1),
    ));

    $this->addElement('Radio', 'sescontest_contestmainphoto', array(
        'label' => 'Make Contest Main Photo Mandatory',
        'description' => 'Do you want to make main photo field mandatory when users create or edit their contests?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.contestmainphoto', 1),
    ));

    $this->addElement('Radio', 'sescontest_contesttags', array(
        'label' => 'Enable Tags',
        'description' => 'Do you want to enable tags for the contests on your website?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.contesttags', 1),
    ));

    $this->addElement('Radio', 'sescontest_enable_timezone', array(
        'label' => 'Enable Timezone',
        'description' => 'Do you want to enable timezone for the contests on your website?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => $settings->getSetting('sescontest.enable.timezone', 1),
    ));
    $this->addElement('Radio', 'sescontest_search', array(
        'label' => 'Enable “People can search for this contest”',
        'description' => 'Do you want to enable “People can search for this contest” for the contests on your website?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sescontest.search', 1),
    ));
    $this->addElement('Radio', 'sescontest_editor_media_type', array(
        'label' => 'Choose Editor Type For Text Entries',
        'description' => 'In case of "Text" media type contests, do you want to allow owners of the contests to choose the editor for the entries to be submitted in their contests? (If you choose No, then you can choose the default editor for the entries.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sescontest.editor.media.type', 1),
        'onclick' => 'showDefaultEditor(this.value)',
    ));
    $this->addElement('Radio', 'sescontest_default_editor', array(
        'label' => 'Default Editor Type for Text Entries',
        'description' => 'Choose the default editor type for the text entries on your website.',
        'multiOptions' => array(
            1 => 'Rich WYSIWYG Editor',
            0 => 'Plain Editor',
        ),
        'value' => $settings->getSetting('sescontest.default.editor', 1),
    ));

    $this->addElement('Radio', 'sescontest_rules_required', array(
        'label' => 'Make Contest Rules Mandatory',
        'description' => 'Do you want to make contest rules field mandatory when users create or edit their contests?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sescontest.rules.required', 1),
    ));

    $this->addElement('Radio', 'sescontest_rules_editor', array(
        'label' => 'Contest Rules Editor',
        'description' => 'Choose the editor for the rules of contests on your website. (If you choose WYSIWYG editor, then it will come with limited  basic options.)',
        'multiOptions' => array(
            1 => 'WYSIWYG Editor',
            0 => 'Simple Textarea',
        ),
        'value' => $settings->getSetting('sescontest.rules.editor', 1),
    ));

    $this->addElement('Radio', 'sescontest_guidelines', array(
        'label' => 'Contest Creation Guidelines',
        'description' => 'Do you want to provide contest creators with some guidelines? If yes, then the box containing the guidelines will remain static on the top right of the page when user scroll down the form. (This setting will work only on Contest Create Page. The tip box will be static in the Advance Create Form.)',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sescontest.guidelines', 1),
        'onclick' => 'showGuideEditor(this.value)',
    ));

    $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr';

    $editorOptions = array(
        'html' => (bool) $allowed_html,
    );
    $editorOptions['plugins'] = array(
        'preview', 'code',
    );
    $this->addElement('TinyMce', 'sescontest_message_guidelines', array(
        'label' => 'Enter Guidelines',
        'class' => 'tinymce',
        'editorOptions' => $editorOptions,
        'value' => $settings->getSetting('sescontest.message.guidelines', ''),
    ));

    // Add submit button
    $this->addElement('Button', 'submit', array(
        'label' => 'Save Changes',
        'type' => 'submit',
        'ignore' => true
    ));
  }

}
