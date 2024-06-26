<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Settings.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_Model_DbTable_Settings extends Engine_Db_Table
{
  // General

  /**
   * @var Zend_Cache_Core
   */
  protected $_cache;

  protected $_settings;

  public function init()
  {
    // Get cache
    $this->_cache = Zend_Registry::get('Zend_Cache');

    // Load settings
    $this->_loadSettings();
  }

  public function reloadSettings()
  {
    $this->_cache->remove('settings');
    $this->_settings = null;
    $this->_loadSettings();
  }



  // Magic

  public function __get($key)
  {
    return $this->getSetting($key);
  }

  public function __set($key, $value)
  {
    return $this->setSetting($key, $value);
  }

  public function __isset($key)
  {
    return $this->hasSetting($key);
  }

  public function __unset($key)
  {
    return $this->removeSetting($key);
  }



  // Accessors

  public function getSetting($key, $default = null)
  {
    $key = $this->_normalizeMagicProperty($key);
    $path = explode('.', $key);
    $final = array_pop($path);

    $current =& $this->_settings;
    if( !empty($path) ) {
      foreach( $path as $pathElement ) {
        if( !isset($current[$pathElement]) || !is_array($current[$pathElement]) ) {
          return $default;
        }
        $current =& $current[$pathElement];
      }
    }

    if( isset($current[$final]) ) {
      return $current[$final];
    } else {
      return $default;
    }
  }

  public function hasSetting($key)
  {
    return ( null !== $this->getSetting($key) );
  }

  public function setSetting($key, $value)
  {
    $key = $this->_normalizeMagicProperty($key);

    // Array mode
    if( is_array($value) ) {
      foreach( $value as $k => $v ) {
        $this->setSetting($key . '.' . $k, $v);
      }
    }

    // Scalar mode
    else {
      $path = explode('.', $key);
      $final = array_pop($path);

      $current =& $this->_settings;
      if( !empty($path) ) {
        foreach( $path as $pathElement ) {
          if( !isset($current[$pathElement]) || !is_array($current[$pathElement]) ) {
            $current[$pathElement] = array();
          }
          $current =& $current[$pathElement];
        }
      }

      // Delete
      if( isset($current[$final]) && null === $value ) {
        $this->delete(array(
          'name = ?' => $key,
        ));
        $this->delete(array(
          'name LIKE ?' => $key . '.%',
        ));
        unset($current[$final]);
      }
      // Update
      else if( isset($current[$final]) ) {
        // Only if not the same?
        if( $current[$final] !== $value ) {
          $this->update(array(
            'value' => $value,
          ), array(
            'name = ?' => $key,
          ));
        }
        // Reselect?
        $current[$final] = $this->find($key)->current()->value;
      }
      // Insert
      else {
        if (!$this->hasSetting($key)) {
          $this->insert(array(
            'name' => $key,
            'value' => $value,
          ));
        }
        // Reselect?
        $current[$final] = $this->find($key)->current()->value;
      }

      // Flush the cache
      $this->_cache->remove('settings');
    }

    return $this;
  }

  public function removeSetting($key)
  {
    return $this->setSetting($key, null);
  }



  // Flat Accessors

  public function getFlatSetting($key, $default = null, $flatChar = '_')
  {
    $value = $this->getSetting($key, $default);
    if( is_array($value) ) {
      $this->_flattenArray($value, $flatChar);
    }
    return $value;
  }

  public function setFlatSetting($key, $value, $flatChar = '_')
  {
    $newValue = array();
    foreach( $value as $k => $v ) {
      $this->_expandArray(explode($flatChar, $k), $v, $newValue);
    }
    $this->setSetting($key, $newValue);
  }



  // Utility

  protected function _loadSettings()
  {
    // Try to load from cache
    $data = $this->_cache->load('settings');
    if( $data && is_array($data) ) {
      $this->_settings = $this->_getSettings($data);
      return;
    }

    // Load from db
    $rows = $this->select()
      ->from($this, array('name', 'value'))
      ->query()
      ->fetchAll(Zend_Db::FETCH_NUM);

    $data = array();
    foreach( $rows as $row ) {
      $this->_expandArray(explode('.', $row[0]), $row[1], $data);
    }
    $this->_settings = $this->_getSettings($data);

    $this->_saveSettings();
  }

  protected function _saveSettings()
  {
    // Try to save to cache
    $this->_cache->save($this->_settings, 'settings');
  }

  protected function _normalizeMagicProperty($key)
  {
    return /*strtolower(*/str_replace('_', '.', $key)/*)*/;
  }

  protected function _expandArray(array $path, $value, array &$array)
  {
    $current =& $array;
    foreach( $path as $pathElement ) {
      if( !isset($current[$pathElement]) || !is_array($current[$pathElement]) ) {
        $current[$pathElement] = array();
      }
      $current =& $current[$pathElement];
    }
    $current = $value;
  }

  protected function _flattenArray(&$array, $char = '_')
  {
    do {
      $break = true;
      foreach( $array as $key => $value ) {
        if( is_array($value) ) {
          foreach( $value as $subkey => $subvalue ) {
            $newKey = $key . $char . $subkey;
            $array[$newKey] = $subvalue;
          }
          unset($array[$key]);
          $break = false;
        }
      }
    } while( !$break );
  }

  /**
   * JIT settings override
   *
   * @param array $settings
   * @return array
   */
  private function _getSettings($settings)
  {
    $file = APPLICATION_PATH . '/application/settings/override.php';
    if( file_exists($file) ) {
      $overrideSettings = require($file);

      foreach( $overrideSettings as $key => $value ) {
        $parts = explode('.', $key);

        if( engine_count($parts) < 2 || strlen($value) === 0 ) {
          continue;
        }

        if( isset($parts[2]) ) {
          $settings[$parts[0]][$parts[1]][$parts[2]] = $value;
        } else {
          $settings[$parts[0]][$parts[1]] = $value;
        }
      }

      foreach( $settings as $key => $values ) {
          if (!is_array($values)) {
              continue;
          }
        foreach( $values as $settingKey => $value ) {

          if( is_array($value) ) {

            foreach( $value as $subKey => $subValue ) {
              $checkKey = strtolower($key . '.' . $settingKey . '.' . $subKey);
              if( isset($overrideSettings[$checkKey]) ) {
                $settings[$key][$settingKey][$subKey] = $overrideSettings[$checkKey];
              }
            }

            continue;
          }

          $checkKey = strtolower($key . '.' . $settingKey);

          if( isset($overrideSettings[$checkKey]) ) {
            $settings[$key][$settingKey] = $overrideSettings[$checkKey];
          }
        }
      }
    }

    return $settings;
  }
  
  public function getDbSettings($key) {
    return $this->select()
							->from($this->info('name'), 'value')
							->where('name = ?', $key)
							->query()
							->fetchColumn();
  }
}
