<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Import.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sestutorial_Form_Admin_Import extends Engine_Form {

  public function init() {

    $this->setTitle('Upload CSV file only');

    $this->addElement('File', 'csvfile', array(
        'label' => '',
        'allowEmpty' => false,
        'required' => true,
    ));
    $this->csvfile->addValidator('Extension', false, 'csv');
    
    
//Level Work
		$levels = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll();
		foreach ($levels as $level) {
			$levels_prepared[$level->getIdentity()] = $level->getTitle();
			$levels_preparedVal[] = $level->getIdentity();
		}
		
    $this->addElement('Multiselect', 'memberlevels', array(
        'label' => 'Member Levels',
        'description' => 'Choose the Member Levels to which this Tutorial will be displayed. Hold down the CTRL key to select or de-select specific member levels.',
        'multiOptions' => $levels_prepared,
        'value' => $levels_preparedVal,
    ));

    //Make Network List
    $table = Engine_Api::_()->getDbtable('networks', 'network');
    $select = $table->select()
            ->from($table->info('name'), array('network_id', 'title'))
            ->order('title');
    $result = $table->fetchAll($select);
    foreach ($result as $value) {
      $networksOptions[$value->network_id] = $value->title;
      $networkvalue[] = $value->network_id;
    }
    $networkvalue = $networkvalue; //unserialize($networks);
    if (engine_count($networksOptions) > 0) {
      $this->addElement('Multiselect', 'networks', array(
          'label' => 'Networks',
          'description' => 'Choose the Networks to which this Tutorial will be displayed. Hold down the CTRL key to select or de-select specific networks.',
          'multiOptions' => $networksOptions,
          'value' => $networkvalue,
      ));
    }
    
    $topStructure = Engine_Api::_()->fields()->getFieldStructureTop('user');
    if (engine_count($topStructure) == 1 && $topStructure[0]->getChild()->type == 'profile_type') {
      $profileTypeField = $topStructure[0]->getChild();
      $options = $profileTypeField->getOptions();
      if (engine_count($options) > 1) {
        $options = $profileTypeField->getElementParams('user');
        unset($options['options']['order']);
        unset($options['options']['multiOptions']['']);
        $optionValues = array();
        foreach ($options['options']['multiOptions'] as $key => $option) {
          $optionValues[] = $key;
        }

        $this->addElement('multiselect', 'profile_types', array(
            'label' => 'Profile Types',
            'multiOptions' => $options['options']['multiOptions'],
            'description' => 'Choose the Profile Types to which this Tutorial will be displayed. Hold down the CTRL key to select or de-select specific profile types.',
            'value' => $optionValues
        ));
      } else if (engine_count($options) == 1) {
        $this->addElement('Hidden', 'profile_types', array(
            'value' => $options[0]->option_id
        ));
      }
    }

    $this->addElement('Button', 'submit', array(
        'label' => 'Import',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'onclick' => 'javascript:parent.Smoothbox.close()',
        'link' => true,
        'prependText' => ' or ',
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper',
        ),
    ));
  }
}
