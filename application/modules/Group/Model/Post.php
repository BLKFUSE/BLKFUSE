<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_Model_Post extends Core_Model_Item_Abstract
{
  protected $_parent_type = 'group_topic';

  protected $_owner_type = 'user';
  
  protected $_searchTriggers = false;
  
  function getTitle() {
		return Engine_Api::_()->getItem('group_topic', $this->topic_id);
	}
  
  public function isSearchable()
  {
    $group = $this->getParentGroup();
    if( !($group instanceof Core_Model_Item_Abstract) ) {
      return false;
    }
    return $group->isSearchable();
  }
  
  public function getHref($params = array())
  {
    $params = array_merge(array(
      'route' => 'group_extended',
      'controller' => 'topic',
      'action' => 'view',
      'group_id' => $this->group_id,
      'topic_id' => $this->getParentTopic()->getIdentity(),
      'post_id' => $this->getIdentity(),
    ), $params);
    $route = @$params['route'];
    unset($params['route']);
    return Zend_Controller_Front::getInstance()->getRouter()->assemble($params, $route, true);
  }

  public function getDescription()
  {
    // strip HTML and BBcode
    $content = strip_tags($this->body);
    $content = preg_replace('|[[\/\!]*?[^\[\]]*?]|si', '', $content);
    return ( Engine_String::strlen($content) > 255 ? Engine_String::substr($content, 0, 255) . '...' : $content );
  }

  public function getPostIndex()
  {
    $table = $this->getTable();
    $select = new Zend_Db_Select($table->getAdapter());
    $select
      ->from($table->info('name'), new Zend_Db_Expr('COUNT(post_id) as count'))
      ->where('topic_id = ?', $this->topic_id)
      ->where('post_id < ?', $this->getIdentity())
      ->order('post_id ASC')
      ;

    $data = $select->query()->fetch();
    
    return (int) $data['count'];
  }

  public function getParentGroup()
  {
    return Engine_Api::_()->getItem('group', $this->group_id);
  }

  public function getParentTopic()
  {
    return Engine_Api::_()->getItem('group_topic', $this->topic_id);
  }

  public function getPoster()
  {
    return Engine_Api::_()->getItem('user', $this->user_id);
  }

  public function getAuthorizationItem()
  {
    return $this->getParent('group');
  }



  // Internal hooks

  protected function _insert()
  {
    if( $this->_disableHooks ) return;
    
    if( !$this->group_id ) {
      throw new Exception('Cannot create post without group_id');
    }
    
    if( !$this->topic_id ) {
      throw new Exception('Cannot create post without topic_id');
    }
    
    parent::_insert();
  }

  protected function _postInsert()
  {
    if( $this->_disableHooks ) return;
    
    // Update topic
    $table = Engine_Api::_()->getDbtable('topics', 'group');
    $select = $table->select()->where('topic_id = ?', $this->topic_id)->limit(1);
    $topic = $table->fetchRow($select);

    $topic->lastpost_id = $this->post_id;
    $topic->lastposter_id = $this->user_id;
    $topic->modified_date = date('Y-m-d H:i:s');
    $topic->post_count++;
    $topic->save();

    parent::_postInsert();
  }

  protected function _delete()
  {
    if( $this->_disableHooks ) return;
    
    // Update topic
    $table = Engine_Api::_()->getDbtable('topics', 'group');
    $select = $table->select()->where('topic_id = ?', $this->topic_id)->limit(1);
    $topic = $table->fetchRow($select);
    $topic->post_count--;

    if( $topic->post_count == 0 ) {
      $topic->disableHooks()->delete();
    } else {
      $topic->save();
    }

    parent::_delete();
  }
}
