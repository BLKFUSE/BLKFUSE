<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Standard.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesapi_Form_Standard extends Engine_Form
{
  /* Custom */

  protected $_item;

  protected $_processedValues = array();

  protected $_topLevelId;

  protected $_topLevelValue;

  protected $_isCreation = false;
  
  protected $_hasPrivacy = false;
  
  protected $_privacyValues = array();

  
  // Add custom element paths?
  public function __construct($options = null)
  {
    Engine_FOrm::enableForm($this);
    self::enableForm($this);

    parent::__construct($options);
  }

  public static function enableForm(Zend_Form $form)
  {
    $form
      ->addPrefixPath('Fields_Form_Element', APPLICATION_PATH . '/application/modules/Fields/Form/Element', 'element');
  }
  

  /* General */

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
    foreach( $struct as $fskey => $map )
    {
      //remove 2nd level fields from array
      if($map->field_id != 1)
        continue;
      $field = $map->getChild();
 
      // Skip fields hidden on signup
      if( isset($field->show) && !$field->show && $this->_isCreation ) {
        continue;
      }
      
      // Add field and load options if necessary
      $params = $field->getElementParams($this->getItem());

      //$key = 'field_' . $field->field_id;
      $key = $map->getKey();
      
      // If value set in processed values, set in element
      if( !empty($this->_processedValues[$field->field_id]) )
      {
        $params['options']['value'] = $this->_processedValues[$field->field_id];
      }

      if( !@is_array($params['options']['attribs']) ) {
        $params['options']['attribs'] = array();
      }
      
      // Heading
      if( $params['type'] == 'Heading' )
      {
        $params['options']['value'] = Zend_Registry::get('Zend_Translate')->_($params['options']['label']);
        unset($params['options']['label']);
      }
      
      // Order
      // @todo this might cause problems, however it will prevent multiple orders causing elements to not show up
      $params['options']['order'] = $orderIndex++;
      
      $inflectedType = Engine_Api::_()->fields()->inflectFieldType($params['type']);
      
      unset($params['options']['alias']);
      unset($params['options']['publish']);
      
      $this->addElement($inflectedType, $key, $params['options']);
      
      $element = $this->getElement($key);
      
      if( method_exists($element, 'setFieldMeta') ) {
        $element->setFieldMeta($field);
      }
     
      // Set attributes for hiding/showing fields using javscript
      $classes = 'field_container field_'.$map->child_id.' option_'.$map->option_id.' parent_'.$map->field_id;
      $element->setAttrib('class', $classes);
      
      
      if( $field->canHaveDependents() ) {
        $element->setAttrib('onchange', 'changeFields(this)');
      }

      // Set custom error message
      if( $field->error ) {
        $element->addErrorMessage($field->error);
      }

      if( $field->isHeading() ) {
        $element->removeDecorator('Label')
          ->removeDecorator('HtmlTag')
          ->getDecorator('HtmlTag2')->setOption('class', 'form-wrapper-heading');
      }
    }

    $this->addElement('Button', 'submit', array(
      'label' => 'Save',
      'type' => 'submit',
      'order' => 10000,
    ));
  }

  public function saveValues()
  {
    // An id must be set to save data (duh)
    if( is_null($this->getItem()) )
    {
      throw new Exception("Cannot save data when no identity has been specified");
    }

    // Iterate over values
    $values = Engine_Api::_()->fields()->getFieldsValues($this->getItem());
  
    $fVals = $this->getValues();
    
    if( $this->_elementsBelongTo ) {
      if( isset($fVals[$this->_elementsBelongTo]) ) {
        $fVals = $fVals[$this->_elementsBelongTo];
      }
    }

    // Fire pre save hook
    Engine_Hooks_Dispatcher::getInstance()->callEvent('onFieldsValuesSaveBefore', array(
      'item' => $this->getItem(),
      'oldValues' => $values,
      'modifiedValues' => $fVals
    ));

    $privacyOptions = Fields_Api_Core::getFieldPrivacyOptions();

    foreach( $fVals as $key => $value ) {
      $parts = explode('_', $key);
      if( engine_count($parts) != 3 ) continue;
      list($parent_id, $option_id, $field_id) = $parts;

      // Whoops no headings
      if( $this->getElement($key) instanceof Engine_Form_Element_Heading ) {
        continue;
      }

      // Array mode
      if( is_array($value) || $this->getElement($key)->isArray() ) {
        
        // Lookup
        $valueRows = $values->getRowsMatching(array(
          'field_id' => $field_id,
          'item_id' => $this->getItem()->getIdentity()
        ));

        // Delete all
        $prevPrivacy = null;
        foreach( $valueRows as $valueRow ) {
          if( !empty($valueRow->privacy) ) {
            $prevPrivacy = $valueRow->privacy;
          }
          $valueRow->delete();
        }
        
        if( $this->_hasPrivacy && 
            !empty($this->_privacyValues[$key]) &&
            !empty($privacyOptions[$this->_privacyValues[$key]]) ) {
          $prevPrivacy = $this->_privacyValues[$key];
        }
        
        // Insert all
        $indexIndex = 0;
        if( is_array($value) || !empty($value) ) {
          foreach( (array) $value as $singleValue ) {
            $valueRow = $values->createRow();
            $valueRow->field_id = $field_id;
            $valueRow->item_id = $this->getItem()->getIdentity();
            $valueRow->index = $indexIndex++;
            $valueRow->value = $singleValue;
            if( $this->_hasPrivacy && $prevPrivacy ) {
              $valueRow->privacy = $prevPrivacy;
            }
            $valueRow->save();
          }
        } else {
          $valueRow = $values->createRow();
          $valueRow->field_id = $field_id;
          $valueRow->item_id = $this->getItem()->getIdentity();
          $valueRow->index = 0;
          $valueRow->value = '';
          if( $this->_hasPrivacy && $prevPrivacy ) {
            $valueRow->privacy = $prevPrivacy;
          }
          $valueRow->save();
        }
      }

      // Scalar mode
      else {
        // Lookup
        $valueRow = $values->getRowMatching(array(
          'field_id' => $field_id,
          'item_id' => $this->getItem()->getIdentity(),
          'index' => 0
        ));
        
        // Remove value row if empty
//        if( empty($value) ) {
//          if( $valueRow ) {
//            $valueRow->delete();
//          }
//          continue;
//        }

        // Create if missing
        $isNew = false;
        if( !$valueRow ) {
          $isNew = true;
          $valueRow = $values->createRow();
          $valueRow->field_id = $field_id;
          $valueRow->item_id = $this->getItem()->getIdentity();
        }
        
        $valueRow->value = htmlspecialchars($value);
        if( $this->_hasPrivacy && 
            !empty($this->_privacyValues[$key]) &&
            !empty($privacyOptions[$this->_privacyValues[$key]]) ) {
          $valueRow->privacy = $this->_privacyValues[$key];
        }
        $valueRow->save();

        /*
        // Insert activity if being changed (and publish is enabled)
        if( !$isNew ) {
          $field = Engine_Api::_()->fields()->getFieldsMeta($this->getItem())->getRowMatching('field_id', $field_id);
          if( is_object($field) && !empty($field->publish) ) {
            $helper = new Fields_View_Helper_FieldValueLoop();
            $helper->view = Zend_Registry::get('Zend_View');
            $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');

            if( $field->type )
            $actionTable->addActivity($this->getItem(), $this->getItem(), 'fields_change_generic', null, array(
              'label' => $field->label,
              'value' => $helper->getFieldValueString($field, $valueRow, $this->getItem()), //$value,
            ));
          }
        }
         * 
         */
      }
    }
    
    // Update search table
    Engine_Api::_()->getApi('core', 'fields')->updateSearch($this->getItem(), $values);
    
    // Fire on save hook
    Engine_Hooks_Dispatcher::getInstance()->callEvent('onFieldsValuesSave', array(
      'item' => $this->getItem(),
      'values' => $values
    ));

    // Regenerate form
    $this->generate();
  }

  protected function _setFieldValues($values)
  {
    // Iterate over elements and apply the values
    foreach( $this->getElements() as $key => $element ) {
      if( engine_count(explode('_', $key)) == 3 ) {
        list($parent_id, $option_id, $field_id) = explode('_', $key);
        if( isset($values[$field_id]) ) {
          $element->setValue($values[$field_id]);
        }
      }
    }
  }


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
