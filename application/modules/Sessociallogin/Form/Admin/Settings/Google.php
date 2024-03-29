<?php



/**

 * SocialEngineSolutions

 *

 * @category   Application_Sessociallogin

 * @package    Sessociallogin

 * @copyright  Copyright 2015-2016 SocialEngineSolutions

 * @license    http://www.socialenginesolutions.com/license/

 * @version    $Id: Google.php 2017-07-04 00:00:00 SocialEngineSolutions $

 * @author     SocialEngineSolutions

 */



class Sessociallogin_Form_Admin_Settings_Google extends Engine_Form {



    public function init() {



        $description = $this->getTranslator()->translate(

        'Here, you can integrate SocialEngine to Google for allowing users to login into your website using their Google accounts. To do so, create an Application through the ');

        $moreinfo = $this->getTranslator()->translate('<a href="%1$s" target="_blank">Google Developers</a> page.<br />');

        $moreinfo1 = $this->getTranslator()->translate('More Info: <a href="%2$s" target="_blank">KB Article</a>');

        $description = vsprintf($description.$moreinfo.$moreinfo1, array('https://developers.google.com/places/web-service/get-api-key', 

        'https://socialnetworking.solutions/guidelines-social-login-google-api-key/',

        ));

        $this->loadDefaultDecorators();

        $this->getDecorator('Description')->setOption('escape', false);

    

        $settings = Engine_Api::_()->getApi('settings', 'core');

        $this->setTitle('Google Integration')

                ->setDescription($description);



        $this->addElement('Text', "sessociallogin_google_clientid", array(

            'label' => 'google client ID',

            'value' => $settings->getSetting('sessociallogin.google.clientid', ''),

            'required' => true,

            'allowEmpty' => false,

        ));

        $this->addElement('Text', "sessociallogin_google_clientsecret", array(

            'label' => 'google client secret',

            'value' => $settings->getSetting('sessociallogin.google.clientsecret', ''),

            'required' => true,

            'allowEmpty' => false,

        ));



        $this->addElement('Radio', "sessociallogin_google_enable", array(

            'label' => 'Enable Login',

            'description' => 'Do you want to enable login on your website through this provider?',

            'allowEmpty' => true,

            'required' => false,

            'multiOptions' => array(1 => 'Yes', '0' => 'No'),

            'onclick' => "showoption(this.value, 'form');",

            'value' => $settings->getSetting('sessociallogin.google.enable', 0),

        ));

        

         $this->addElement('Radio', "sessociallogin_google_quick_signup", array(

            'label' => 'Enable Quick Signup Via Google',

            'description' => 'Do you want to allow users to directly create their accounts on your website by enabling quick signup via Google on your website? If you choose Yes, then users will not have to enter any details in the signup form.',

            'allowEmpty' => true,

            'required' => false,

            'multiOptions' => array(1 => 'Yes', '0' => 'No'),

            'onclick' => 'showsignupoption(this.value, "from");',

            'value' => $settings->getSetting('sessociallogin.google.quick.signup', 0),

        ));

        $profileType = array();

        

        

        //$profileType = array();

        // Element: profile_type

        $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');

        if( engine_count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type' ) {

          $profileTypeField = $topStructure[0]->getChild();

          $options = $profileTypeField->getOptions();

          if( engine_count($options) ) {

            $options = $profileTypeField->getElementParams('user'); 

            foreach($options["options"]["multiOptions"] as $key => $optionp) { 

              if($optionp == '') {

                continue;

              }

              $profileType[$key] = $optionp;

            }

            //print_r($profileType);die;

            $profileType = $profileType; //$options["options"]["multiOptions"];

          }

        }

        

        if(engine_count($profileType)){

          //Assign profile type

          $this->addElement('Select', "sessociallogin_google_profile_type", array(

              'label' => 'Default Profile Type',

              'description' => 'Choose the Profile Type which will be assigned to the members creating using Quick Signup feature.',

              'allowEmpty' => true,

              'required' => false,

              'multiOptions' => $profileType,

              'value' => $settings->getSetting('sessociallogin.google.profile.type', 0),

          ));

        }        

        

        $public_level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel();

        //$member_levels = array();

        foreach( Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $row ) {

          if( $public_level->level_id != $row->level_id ) {

            $member_count = $row->getMembershipCount();

    

            if( null !== ($translate = $this->getTranslator()) ) {

              $title = $translate->translate($row->title);

            } else {

              $title = $row->title;

            }    

            $member_levels[$row->level_id] = $title;

          }

        }

        //Assign member level

        $this->addElement('Select', "sessociallogin_google_member_level", array(

            'label' => 'Default Member Level',

            'description' => 'Choose the Member Level which will be assigned to the members creating using Quick Signup feature.',

            'allowEmpty' => true,

            'required' => false,

            'multiOptions' => $member_levels,

            'value' => $settings->getSetting('sessociallogin.google.member.level', 4),

        ));

        //Redirect User

        $redirect[0] = "Redirect to Member Home Page";

        $redirect[1] = "Redirect to Member Profile Page";

        $redirect[2] = "Redirect to Edit Member Profile Page";

        $redirect[3] = "Redirect to the page from which member has signed up";

        $this->addElement('Select', "sessociallogin_google_redirect_user", array(

            'label' => 'Redirection of Member on Quick Signup',

            'description' => 'Choose from below where do you want to redirect members when their account is created using Quick Signup feature.',

            'allowEmpty' => true,

            'required' => false,

            'multiOptions' => $redirect,

            'value' => $settings->getSetting('sessociallogin.google.redirect.user', 0),

        ));

        $this->addElement('Button', 'submit', array(

            'label' => 'Save Changes',

            'type' => 'submit',

            'ignore' => true

        ));

    }



}

