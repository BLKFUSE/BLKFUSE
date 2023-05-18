<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Standard.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Form_Standard extends Engine_Form
{
  /* Custom */

  protected $_item;
  
  protected $_target;

  protected $_processedValues = array();

  protected $_topLevelId;

  protected $_topLevelValue;

  protected $_isCreation = false;
  
  protected $_hasPrivacy = false;
  
  protected $_privacyValues = array();
  protected $_removeValue = false;
  
  // Add custom element paths?
  public function __construct($options = null)
  {
    Engine_FOrm::enableForm($this);
    self::enableForm($this);
    $this->setAttrib('id','sescustom_fields');
    parent::__construct($options);
  }

  public static function enableForm(Zend_Form $form)
  {
    $form
      ->addPrefixPath('Fields_Form_Element', APPLICATION_PATH . '/application/modules/Fields/Form/Element', 'element');
  }
  

  /* General */
  public function getRemoveValues()
  {
    return $this->_removeValue;
  }

  public function setRemoveValues($removeValues)
  {
    $this->_removeValue = $removeValues;
    return $this;
  }
  
  public function getTarget()
  {
    return $this->_target;
  }

  public function setTarget($target)
  {
    $this->_target = $target;
    return $this;
  }
  
  public function getItem()
  {
    return $this->_item;
  }

  public function setItem(Core_Model_Item_Abstract $item)
  {
    $this->_item = $item;
    return $this;
  }

  public function setTopLevelId($id)
  {
    $this->_topLevelId = $id;
    return $this;
  }

  public function getTopLevelId()
  {
    return $this->_topLevelId;
  }

  public function setTopLevelValue($val)
  {
    $this->_topLevelValue = $val;
    return $this;
  }

  public function getTopLevelValue()
  {
    return $this->_topLevelValue;
  }

  public function setIsCreation($flag = true)
  {
    $this->_isCreation = (bool) $flag;
    return $this;
  }

  public function setProcessedValues($values)
  {
    $this->_processedValues = $values;
    $this->_setFieldValues($values);
    return $this;
  }

  public function getProcessedValues()
  {
    return $this->_processedValues;
  }
  
  public function setHasPrivacy($flag = false)
  {
    $this->_hasPrivacy = (bool) $flag;
    return $this;
  }
  
  public function setPrivacyValues($privacyValues)
  {
    $this->_privacyValues = (array) $privacyValues;
    return $this;
  }

  public function getFieldMeta()
  {
    return Engine_Api::_()->fields()->getFieldsMeta($this->getItem());
  }

  public function getFieldStructure()
  {
    // Let's allow fallback for no profile type (for now at least)
    if( !$this->_topLevelId || !$this->_topLevelValue ) {
      $this->_topLevelId = null;
      $this->_topLevelValue = null;
    }
    return Engine_Api::_()->fields()->getFieldsStructureFull($this->getItem(), $this->_topLevelId, $this->_topLevelValue);
  }

  // Main
  public function generate()
  {
    $struct = $this->getFieldStructure();

    $orderIndex = 0;
    $targetFields= Engine_Api::_()->sescommunityads()->getTargetAds(array('fieldsArray'=>1));
    
    foreach( $struct as $fskey => $map )
    {
      $field = $map->getChild();
      
      // Skip fields hidden on signup
      if( isset($field->show) && !$field->show && $this->_isCreation ) {
        continue;
      }
      
      // Add field and load options if necessary
      $params = $field->getElementParams($this->getItem());
      
      $key = $map->getKey();
      $parts = explode('_', $key);
      $profileType = $parts[1];
      if($profileType && !array_key_exists($field->field_id.'_'.$profileType,$targetFields))
          continue;
      
      $key = $parts[2].'_'.$parts[1];
      if( !@is_array($params['options']['attribs']) ) {
        $params['options']['attribs'] = array();
      }
      if(!$this->_target)
        unset($params['options']['value']);
      else{
        if(array_key_exists($key,$this->_target)){
          $value = $this->_target[$key];
          if(strpos($value,'||') === false){}else{
            $value = explode('||',$value);
          }
          $params['options']['value'] = $value;
        }
      }
      // Heading
      if( $params['type'] == 'Heading' )
      {
        continue;
      }
      // Order
      // @todo this might cause problems, however it will prevent multiple orders causing elements to not show up
      $inflectedType = Engine_Api::_()->fields()->inflectFieldType($params['type']);
      unset($params['options']['alias']);
      unset($params['options']['publish']);
      if($inflectedType == "Birthdate"){
         // continue;  
         $params['options']['order'] = $orderIndex++;           
         $subform = new Zend_Form_SubForm(array(
            'description' => $params['options']['label'],
            'order' => $params['options']['order'],
            'decorators' => array(
              'FormElements',
              array('Description', array('placement' => 'PREPEND', 'tag' => 'span')),
              array('HtmlTag', array('tag' => 'div', 'class' => 'form-wrapper', 'style' => 'display:none;'))
            )
          ));
          $params['type'] = 'Select';
          $params['options']['label'] = Zend_Registry::get('Zend_Translate')->translate('Age');
          $params['options']['disableTranslator'] = true;
          $multiOptions = array('' => ' ');
          $min_age = 13;
          if( isset($field->config['min_age']) )
            $min_age = $field->config['min_age'];
          for( $i = $min_age; $i <= 100; $i++ ) 
            $multiOptions[$i] = $i;
          $params['options']['multiOptions'] = $multiOptions;
          Fields_Form_Standard::enableForm($subform);
          Engine_Form::enableForm($subform);
          unset($params['options']['label']);
          unset($params['options']['order']);
          $params['options']['decorators'] = array('ViewHelper');
          $params['options']['class'] = 'sesprofile_field_'.$profileType;
          if(array_key_exists($key,$this->_target)){
            $value = $this->_target[$key];
            if(strpos($value,'||') === false){}else{
              list($first,$second) = explode('||',$value);
            }
          }
          $params['options']['value'] = $first;
          $subform->addElement($params['type'], 'min', $params['options']);
          $params['options']['value'] = $second;
          $subform->addElement($params['type'], 'max', $params['options']);
          $this->addSubForm($subform, $key);
          
          if(array_key_exists($profileType.'_birthday',$targetFields)){
            $birthdayValue = 0;
            if(array_key_exists($profileType.'_birthday',$this->_target)){
              $value = $this->_target[$profileType.'_birthday'];
              if($value){
                  $birthdayValue = 1;
              }
            }
             //Add current date birthday element in form.
             $this->addElement('Checkbox', $profileType.'_birthday', array(
              'label' => 'Target people having their birthday on current date.',
              'description' => 'Birthday',
              'value'=>$birthdayValue,
              'class'=>'sesprofile_field_'.$profileType,
              'order' => $orderIndex++,
             ));
          }
         continue;
      }else{
        if(array_key_exists($profileType.'_birthday',$targetFields) && empty($birthday{$profileType})){
          $birthday[$profileType] = 1;
          $birthdayValue = 0;
          if(array_key_exists($profileType.'_birthday',$this->_target)){
            $value = $this->_target[$profileType.'_birthday'];
            if($value){
                $birthdayValue = 1;
            }
          }
           //Add current date birthday element in form.
           $this->addElement('Checkbox', $profileType.'_birthday', array(
            'label' => 'Target people having their birthday on current date.',
            'description' => 'Birthday',
            'checked'=>'checked',
            //'value' =>$birthdayValue,
            'class'=>'sesprofile_field_'.$profileType,
            'order' => $orderIndex++,
           ));
        }
        $params['options']['order'] = $orderIndex++;
        $this->addElement($inflectedType, $key, $params['options']);
      }
      
      $element = $this->getElement($key);

      if( method_exists($element, 'setFieldMeta') ) {
        $element->setFieldMeta($field);
      }
      
      // Set attributes for hiding/showing fields using javscript
      $classes = 'field_container field_'.$map->child_id.' option_'.$map->option_id.' parent_'.$map->field_id .' sesprofile_field_'.$profileType;
      $element->setAttrib('class', $classes);
     
      if( $field->canHaveDependents() ) {
       continue;
      }

      // Set custom error message
      if( $field->error ) {
        $element->addErrorMessage($field->error);
      }

      if( $element->getDecorator('Description') ) {
        $element->getDecorator('Description')
          ->setOption('placement', 'append');
      }      
      
    }    
  }

  public function saveValues()
  {}

  protected function _setFieldValues($values)
  {}


  /* These are hacks to existing form methods */
  
  public function init()
  {
    $this->generate();
  }

  public function isValid($data)
  {
    if (!is_array($data)) {
      require_once 'Zend/Form/Exception.php';
      throw new Zend_Form_Exception(__CLASS__ . '::' . __METHOD__ . ' expects an array');
    }
    $translator = $this->getTranslator();
    $valid      = true;

    if ($this->isArray()) {
      $data = $this->_dissolveArrayValue($data, $this->getElementsBelongTo());
    }

    // Changing this part
    $structure = $this->getFieldStructure();
    $selected = array();
    if( !empty($this->_topLevelId) ) $selected[$this->_topLevelId] = $this->_topLevelValue;

    foreach ($this->getElements() as $key => $element) {
      $element->setTranslator($translator);

      $parts = explode('_', $key);
      if( engine_count($parts) !== 3 ) {
        continue;
      }
      
      list($parent_id, $option_id, $field_id) = $parts;
      //if( !is_numeric($field_id) ) continue;

      $fieldObject = $structure[$key];
      
      // All top level fields are always shown
      if( !empty($parent_id) ) {
        
        $parent_field_id = $parent_id;
        $option_id = $option_id;
        
        // Field has already been stored, or parent does not have option
        // specified, <del>or field is a heading</del>
        if( isset($selected[$field_id]) || empty($selected[$parent_field_id]) /* || !isset($data[$key])*/ ) {
          $element->setIgnore(true);
          continue;
        }

        // Parent option doesn't match
        if( is_scalar($selected[$parent_field_id]) && $selected[$parent_field_id] != $option_id ) {
          $element->setIgnore(true);
          continue;
        } else if( is_array($selected[$parent_field_id]) && !engine_in_array($option_id, $selected[$parent_field_id]) ) {
          $element->setIgnore(true);
          continue;
        }
      }

      // This field is being used
      if( isset($data[$key]) )
      {
        $selected[$field_id] = $data[$key];
      }

      if( $element instanceof Engine_Form_Element_Heading )
      {
        $element->setIgnore(true);
      }
      else if( !isset($data[$key]) )
      {
        $valid = $element->isValid(null, $data) && $valid;
      }
      else
      {
        $valid = $element->isValid($data[$key], $data) && $valid;
      }
    }
    $this->_processedValues = $selected;
    // Done changing

    foreach ($this->getSubForms() as $key => $form) {
      $form->setTranslator($translator);
      if (isset($data[$key])) {
        $valid = $form->isValid($data[$key]) && $valid;
      } else {
        $valid = $form->isValid($data) && $valid;
      }
    }

    $this->_errorsExist = !$valid;
    return $valid;
  }
}
