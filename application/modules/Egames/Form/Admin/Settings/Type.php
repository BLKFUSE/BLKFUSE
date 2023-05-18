<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Type.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesalbum_Form_Admin_Settings_Type extends Engine_Form {

  public function init() {

    $this->setMethod('POST')
            ->setAttrib('class', 'global_form_smoothbox');

    $this->addElement('Text', 'label', array(
        'label' => 'Profile Type Label',
        'required' => true,
        'allowEmpty' => false,
    ));

    //Get list of Member Types
    $db = Engine_Db_Table::getDefaultAdapter();
    $member_type_result = $db->select('option_id, label')
            ->from('engine4_album_fields_options')
            ->where('field_id = ?', 1)
            ->query()
            ->fetchAll();
    $member_type_count = engine_count($member_type_result);
    $member_type_array = array('null' => 'No, Create Blank Profile Type');
    for ($i = 0; $i < $member_type_count; $i++) {
      $member_type_array[$member_type_result[$i]['option_id']] = $member_type_result[$i]['label'];
    }
    
		$this->addElement('Select', 'duplicate', array(
        'label' => 'Duplicate Existing Profile Type?',
        'required' => true,
        'allowEmpty' => false,
        'multiOptions' => $member_type_array,
    ));
		
    $this->addElement('Button', 'execute', array(
        'label' => 'Add Profile Type',
        'ignore' => true,
        'decorators' => array('ViewHelper'),
        'type' => 'submit'
    ));

    $this->addElement('Cancel', 'cancel', array(
        'prependText' => ' or ',
        'label' => 'cancel',
        'link' => true,
        'href' => '',
        'onclick' => 'parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        ),
    ));

    $this->addDisplayGroup(array(
        'execute',
        'cancel'
            ), 'buttons');
  }

}
