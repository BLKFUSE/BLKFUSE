<?php

class Sesbasic_Api_Menus extends Core_Api_Abstract
{
  private $orderByLabelMenus = array('core_admin_main_plugins');
  public function getNavigation($name, array $options = array(), $activeItem = null)
  {
    $pages = $this->getMenuParams($name, $options, $activeItem);
    $navigation = new Zend_Navigation();
    
    $navigation->addPages($pages,'test');
    
    return $navigation;
  }

  public function getMenuParams($name, array $options = array(), $activeItem = null)
  {
    $menu = $this->getMenu($name);
    $pages = array();

    foreach( $menu as $key => $row ) {
      // Check enabled
      if( isset($row->enabled) && !$row->enabled ) {
        continue;
      }

      // Plugin
      $page = null;
      $multi = false;
      if( !empty($row->plugin) ) {

        // Support overriding the method
        if( strpos($row->plugin, '::') !== false ) {
          list($pluginName, $method) = explode('::', $row->plugin);
        } else {
          $pluginName = $row->plugin;
          $method = 'onMenuInitialize_' . $this->_formatMenuName($row->name);
        }

        // Load the plugin
        try {
          $plugin = Engine_Api::_()->loadClass($pluginName);
        } catch( Exception $e ) {
          // Silence exceptions
          continue;
        }

        // Run plugin
        try {
          $result = $plugin->$method($row);
        } catch( Exception $e ) {
          // Silence exceptions
          continue;
        }

        if( $result === true ) {
          // Just generate normally
        } else if( $result === false ) {
          // Don't generate
          continue;
        } else if( is_array($result) ) {
          // We got either page params or multiple page params back
          // Single
          if( array_values($result) !== $result ) {
            $page = $result;
          }
          // Multi
          else
          {
            // We have to do this manually
            foreach( $result as $key => $value )
            {
              if( is_numeric($key) )
              {
                if( !empty($options) )
                {
                  $value = array_merge_recursive($value, $options);
                }
                if( !isset($result['label']) ) $result['label'] = $row->label;
                $pages[] = $value;
              }
            }
            continue;
          }
        } else if( $result instanceof Zend_Db_Table_Row_Abstract && $result->getTable() instanceof Core_Model_DbTable_MenuItems ) {
          // We got the row (or a different row?) back ...
          $row = $result;
        } else {
          // We got a weird data type back
          continue;
        }
      }

      // No page was made, use row
      if( null === $page ) {
        $page = (array) $row->params;
      }

      // Add label
      if( !isset($page['label']) ) {
        $page['label'] = $row->label;
      }

      // Add type for URI
      if (isset($page['uri']) && !isset($page['type'])) {
        $page['type'] = 'uri';
      }

      // Add custom options
      if( !empty($options) ) {
        $page = array_merge_recursive($page, $options);
      }

      // Standardize arguments
      if( !isset($page['reset_params']) ) {
        $page['reset_params'] = true;
      }

      // Set page as active, if necessary
      if( !isset($page['active']) && null !== $activeItem && $activeItem == $row->name ) {
        $page['active'] = true;
      }

      $page['class'] = ( !empty($page['class']) ? $page['class'] . ' ' : '' ) . 'menu_' . $name;
      $page['class'] .= " " . $row->name;

      // Get submenu
      if( $row->submenu )
      {
        $page['pages'] = $this->getMenuParams($row->submenu);
      }

      // Maintain menu item order
      $page['order'] = engine_in_array($name, $this->orderByLabelMenus) ? $key : $row->order;

      $pages[] = $page;
    }
    return $pages;
  }

  public function getMenu($name)
  {
    $orderBy = engine_in_array($name, $this->orderByLabelMenus) ? 'label' : 'order';
    // Get only enabled modules
    $enabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();

    // Get items
    $table = Engine_Api::_()->getDbtable('menuItems', 'sesbasic');
    $select = $table->select()
      ->where('menu = ?', $name)
      ->where('module IN(?)', $enabledModuleNames)
      ->order($orderBy);
    return $table->fetchAll($select);
  }

  protected function _formatMenuName($name)
  {
    $name = str_replace('_', ' ', $name);
    $name = ucwords($name);
    $name = str_replace(' ', '', $name);
    return $name;
  }
}
