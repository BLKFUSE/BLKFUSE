<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Pages.php 9994 2013-03-25 23:18:04Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_Model_DbTable_Pages extends Engine_Db_Table implements Engine_Content_Storage_Interface
{
  protected $_rowClass = 'Core_Model_Page';

  public function loadMetaData(Engine_Content $contentAdapter, $name)
  {
    $select = $this->select()->where('name = ?', $name)->orWhere('page_id = ?', $name);
    $page = $this->fetchRow($select);

    if( !is_object($page) ) {
      // throw?
      return null;
    }

    return $page->toArray();
  }

  public function loadContent(Engine_Content $contentAdapter, $name)
  {
    if( is_array($name) ) {
      $name = join('_', $name);
    }
    if( !is_string($name) && !is_numeric($name) ) {
      throw new Exception('not string');
    }

    $select = $this->select()->where('name = ?', $name)->orWhere('page_id = ?', $name);
    $page = $this->fetchRow($select);

    if( !is_object($page) ) {
      // throw?
      return null;
    }

    // Get all content
    $contentTable = Engine_Api::_()->getDbtable('content', 'core');
    $select = $contentTable->select()
      ->where('page_id = ?', $page->page_id)
      ->order('order ASC')
      ;
    $content = $contentTable->fetchAll($select);

    // Create structure
    $structure = $this->prepareContentArea($content);

    // Create element (with structure)
    $element = new Engine_Content_Element_Container(array(
      'class' => 'layout_page_' . $page->name,
      'elements' => $structure
    ));

    return $element;
  }

  public function prepareContentArea($content, $current = null)
  {
    // Get parent content id
    $parent_content_id = null;
    if( null !== $current ) {
      $parent_content_id = $current->content_id;
    }
    
    // Get children
    $children = $content->getRowsMatching('parent_content_id', $parent_content_id);
    if( empty($children) && null === $parent_content_id ) {
      $children = $content->getRowsMatching('parent_content_id', 0);
    }

    // Get struct
    $struct = array();
    foreach( $children as $child ) {
      $elStruct = $this->createElementParams($child);
      if( $elStruct ) {
        $elStruct['elements'] = $this->prepareContentArea($content, $child);
        $struct[] = $elStruct;
      }
    }

    return $struct;
  }

  public function createElementParams($row)
  {
    $data = array(
      'identity' => $row->content_id,
      'type' => $row->type,
      'name' => $row->name,
      'order' => $row->order,
    );
    $params = (array) $row->params;
    if( isset($params['title']) ) {
      $data['title'] = $params['title'];
    }
    $data['params'] = $params;
    
    // Check mobile here?
    if( !empty($params['nomobile']) && 
        !empty($_SESSION['mobile']) &&
        !empty($_SESSION['mobile']['mobile']) ) {
      return false;
    }
    
    return $data;
  }

  public function deletePage(Core_Model_Page $page)
  {
    $contentTable = Engine_Api::_()->getDbtable('content', 'core');
    $contentTable->delete(array(
      'page_id = ?' => $page->page_id,
    ));
    
    $page->delete();

    return $this;
  }
  
  public function getWidgetizePage($params = array()) {

    $select = $this->select()
            ->from($this->info('name'), array('page_id', 'displayname'))
            ->where('name LIKE (?)', '%'.$params['name'].'%');
    return $this->fetchAll($select);
  }
}
