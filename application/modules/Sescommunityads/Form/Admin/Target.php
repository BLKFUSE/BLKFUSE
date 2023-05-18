<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Target.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Form_Admin_Target extends Engine_Form {
  public function init() {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Targeting Settings')
            ->setDescription('From this section you are allowed to choose the below Targeting options depending upon the Profile field to whom you want to show Ads.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

    $profileTypes = Engine_Api::_()->sescommunityads()->getAllProfileTypes();
    foreach($profileTypes as $profileType){
        $this->addElement('Dummy', 'profile_field_'.$profileType->getIdentity(), array(
            'label' => 'Profile Type: '.$profileType->label,
        ));

        //get all profile types data
        $fields = Engine_Api::_()->getDbTable('maps','sescommunityads')->getProfileFields(array('profile_id'=>$profileType->getIdentity()));
        $birthday = false;
        if(engine_count($fields)){
          foreach($fields as $field){
            if($field->type == "birthdate"){
              $birthday = true;
            }
            $this->addElement('Checkbox',$field->field_id.'_'.$field->option_id,array(
              'label'=>$field->label.'('.$field->type.')',
              'description' =>'',
              'value'=>'',
            ));
          }
        }
        if($birthday){
            $this->addElement('Dummy', 'profile_field_birthday_'.$profileType->getIdentity(), array(
              'label' => 'Same day Birthday',
            ));
            $this->addElement('Checkbox',$profileType->getIdentity().'_birthday',array(
              'label'=>'Birthday',
              'description' =>'',
              'value'=>'',
            ));
        }
    }

      //Networks
      $networks = Engine_Api::_()->sescommunityads()->networks();
      if(engine_count($networks)){
         $this->addElement('Dummy', 'netowrk', array(
            'label' => 'Network',
						'class' => 'ng',
          ));
          $this->addElement('Checkbox','network_enable',array(
            'label'=>'Do you want enable network based filtering',
            'description' =>'',
            'value'=>'',
          ));
      }

			// Add submit button
			$this->addElement('Button', 'submit', array(
					'label' => 'Save Changes',
					'type' => 'submit',
					'ignore' => true
			));
  }

}
