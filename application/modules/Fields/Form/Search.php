<?php

/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Fields
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Search.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Fields
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */
class Fields_Form_Search extends Engine_Form
{
  protected $_fieldType;
  protected $_topLevelId;
  protected $_topLevelValue;
  protected $_fieldElements = array();
  protected $_jQueryLoaded = false;

  public function __construct($options = array())
  {
    Fields_Form_Standard::enableForm($this);
    parent::__construct($options);
  }

  public function setType($type)
  {
    return $this->setFieldType($type);
  }

  public function setFieldType($type)
  {
    $this->_fieldType = $type;
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

  public function getFieldElements()
  {
    return $this->_fieldElements;
  }

  public function init()
  {
    $this->addDecorators(array(
      'FormElements',
      array(array('li' => 'HtmlTag'), array('tag' => 'ul')),
      array('HtmlTag', array('tag' => 'div', 'class' => 'field_search_criteria')),
      'Form',
    ));

    $this->setAttrib('class', 'field_search_criteria');

    // special helper code to get the display name element
    $this->generate();
  }

  public function generate()
  {
    // get the search structure
    $structure = Engine_Api::_()->getApi('core', 'fields')->getFieldsStructureSearch($this->_fieldType, $this->_topLevelId, $this->_topLevelValue);

    $globalOrderIndex = 100;
    $normalOrderIndex = 1000;

    // Start firing away
    foreach( $structure as $map ) {
      $field = $map->getChild();

      // Ignore fields not searchable (even though getFieldsStructureSearch should have skipped it already)
      if( !$field->search ) {
        continue;
      }

      $isGlobal = ( $map->field_id == 0
          || $field->search == 2
          || ($map->field_id == $this->_topLevelId && $map->option_id == $this->_topLevelValue) );

      // Get search key
      $uKey = $key = $map->getKey();
      $name = null;
      if( !empty($field->alias) ) {
        $name = sprintf('alias_%s', $field->alias);
      } else {
        $name = sprintf('field_%d', $field->field_id);
      }
      $key .= '_' . $name;

      // Get params
      $params = $field->getElementParams($this->_fieldType, array('required' => false));

      // Add attribs
      if( !@is_array($params['options']['attribs']) ) {
        $params['options']['attribs'] = array();
      }
      $showMulti = false;
      if(!empty($field->config['show_multi'])) {
          $showMulti = true;
          if(!$this->_jQueryLoaded){
              $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
              if($view) {
                  $baseURL = Zend_Registry::get('StaticBaseUrl');
                  $view->headLink()->appendStylesheet($baseURL."externals/selectize/css/normalize.css");
                  $headScript = new Zend_View_Helper_HeadScript();
                  //$headScript->prependFile($baseURL.'externals/jQuery/jquery.min.js');
                  $headScript->appendFile($baseURL.'externals/selectize/js/selectize.js');
              }
          }
      }

      $params['options']['attribs']['class'] =
        'field_toggle' . ' ' .
        'parent_' . $map->field_id . ' ' .
        'option_' . $map->option_id . ' ' .
        'field_'  . $map->child_id  . ' '.
        (!empty($showMulti) ? "show_multi_select" : "");

      if( $isGlobal ) {
        $params['options']['attribs']['class'] .= ' field_toggle_nohide';
      }
      $params['options']['attribs']['onchange'] = 'changeFields();';
      //$params['options']['attribs']['id'] = $map->getKey();

      // Remove some stuff
      unset($params['options']['required']);
      unset($params['options']['allowEmpty']);
      unset($params['options']['validators']);

      // Change decorators
      $params['options']['decorators'] = array(
        'ViewHelper',
        array('Label', array('tag' => 'span')),
        array('HtmlTag', array('tag' => 'li', 'style' => ( !$isGlobal ? 'display:none;' : '') ))
      );

      // Change order
      if( $isGlobal ) {
        $params['options']['order'] = $globalOrderIndex++;
      } else {
        $params['options']['order'] = $normalOrderIndex++;
      }

      // Get generic type
      $info = Engine_Api::_()->fields()->getFieldInfo($field->type);
      $genericType = null;
      if( !empty($info['base']) ) {
        $genericType = $info['base'];
      } else {
        $genericType = $field->type;
      }
      $params['type'] = $genericType; // For now
      
      // Hack birthdate->age
      if( $field->type == 'birthdate' ) {
          $params['type'] = 'Select';
        $params['options']['label'] = Zend_Registry::get('Zend_Translate')->translate('Age');
        $params['options']['disableTranslator'] = true;
        $multiOptions = array('' => ' ');
        $min_age = 13;
        if( isset($field->config['min_age']) ) {
          $min_age = $field->config['min_age'];
        }
        for( $i = $min_age; $i <= 100; $i++ ) {
          $multiOptions[$i] = $i;
        }
        $params['options']['multiOptions'] = $multiOptions;
      }

      // Populate country multiOptions
      if( $field->type == 'country' ) {
        $locale = Zend_Registry::get('Locale');
        $territories = Zend_Locale::getTranslationList('territory', $locale, 2);
        asort($territories);
        // fixes #1279
        $params['options']['multiOptions'] = array_merge(array(
          '' => '',
        ), $territories);
      }

      // Ignored fields (these are hard-coded)
      if( engine_in_array($field->type, array('profile_type', 'first_name', 'last_name')) ) {
        continue;
      }

      // Hacks
      switch( $genericType ) {
        // Ranges
        case 'date':
        case 'int':
        case 'integer':
        case 'float':
          // Use subform
          $subform = new Zend_Form_SubForm(array(
            'description' => $params['options']['label'],
            'order' => $params['options']['order'],
            'decorators' => array(
              'FormElements',
              array('Description', array('placement' => 'PREPEND', 'tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => 'browse-range-wrapper', 'style' => ( !$isGlobal ? 'display:none;' : '')))
            )
          ));
          Fields_Form_Standard::enableForm($subform);
          Engine_Form::enableForm($subform);
          unset($params['options']['label']);
          unset($params['options']['order']);
          $params['options']['decorators'] = array('ViewHelper');
          $minOptions = $maxOptions = $params;
          if( $field->type == 'birthdate' ) {
            unset($params['options']['multiOptions'][""]);
            $minOptions["options"]["multiOptions"] = array(""=>Zend_Registry::get('Zend_Translate')->translate("Min"))+$params['options']['multiOptions'];
            $maxOptions["options"]["multiOptions"] = array(""=>Zend_Registry::get('Zend_Translate')->translate("Max"))+$params['options']['multiOptions'];
          }
          $subform->addElement($params['type'], 'min', $minOptions["options"]);
          $subform->addElement($params['type'], 'max', $maxOptions["options"]);
          $this->addSubForm($subform, $key);

          break;

        // Select types
        case 'select':
        case 'radio':
        case 'multiselect':
        case 'multi_checkbox':
          // Ignore if there is only one/zero option?
          if( engine_count(@$params['options']['multiOptions']) <= 1 && isset($params['options']['multiOptions']['']) ) {
            continue 2;
          } else if( engine_count(@$params['options']['multiOptions']) <= 0 ) {
            continue 2;
          }
          if($showMulti){
              $params['type'] = "multiselect";
          }
          $this->addElement(Engine_Api::_()->fields()->inflectFieldType($params['type']), $key, $params['options']);
          break;

        // Checkbox
        case 'checkbox':
          $params['options']['uncheckedValue'] = null;
          $params['options']['decorators'] = array(
            'ViewHelper',
            array('Label', array('placement' => 'APPEND', 'tag' => 'label')),
              array('HtmlTag', array('tag' => 'li', 'class' => 'browse-range-wrapper', 'style' => ( !$isGlobal ? 'display:none;' : '')))
          );
          $this->addElement($params['type'], $key, $params['options']);
          break;

        // Normal
        default:
          $this->addElement($params['type'], $key, $params['options']);
          break;
      }
      
      $element = $this->$key;
      //$element = $this->getElement($key);
      $this->_fieldElements[$key] = $element;
    }


    // Add a separators?
    $this->addElement('Heading', 'separator1', array(
      //'label' => '------',
      'order' => $globalOrderIndex++,
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => 'span')),
        array('HtmlTag', array('tag' => 'li', 'class' => 'browse-separator-wrapper'))
      ),
    ));
    $this->addElement('Heading', 'separator2', array(
      //'label' => '------',
      'order' => $normalOrderIndex++,
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => 'span')),
        array('HtmlTag', array('tag' => 'li', 'class' => 'browse-separator-wrapper'))
      ),
    ));
  }
}
