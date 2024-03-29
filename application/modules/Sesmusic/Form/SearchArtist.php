<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: SearchPlaylist.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Form_SearchArtist extends Engine_Form {
  protected $_fromApi;
  public function getFromApi(){
    return $this->_fromApi;  
  }
  public function setFromApi($fromApi){
    $this->_fromApi = $fromApi;
    return $this;  
  }
  public function init() {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $front = Zend_Controller_Front::getInstance();
    $module = $front->getRequest()->getModuleName();
    $controller = $front->getRequest()->getControllerName();
    $action = $front->getRequest()->getActionName();
    if(!$this->_fromApi){
      $content_table = Engine_Api::_()->getDbtable('content', 'core');
      $params = $content_table->select()
              ->from($content_table->info('name'), array('params'))
              ->where('name = ?', 'sesmusic.artist-browse-search')
              ->query()
              ->fetchColumn();
      $params = Zend_Json_Decoder::decode($params);
  
      $this->setAttribs(array(
                  'id' => 'filter_form',
                  'class' => 'global_form_box',
              ))
              ->setMethod('GET');
  
      if ($module == 'sesmusic' && $controller == 'artist' && $action == 'browse') {
        $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
      } else {
        $this->setAction($view->url(array('module' => 'sesmusic', 'controller' => 'artist', 'action' => 'browse'), 'default', true));
      }
    }else{
      $params['searchOptionsType'] = array("searchBox",'show');
    }
    parent::init();

    if (!empty($params['searchOptionsType']) && engine_in_array('searchBox', $params['searchOptionsType'])) {
      $this->addElement('Text', 'title_name', array(
          'label' => 'Search Artist',
          'placeholder' => 'Enter Artist Name',
      ));
    }

    if (!empty($params['searchOptionsType']) && engine_in_array('show', $params['searchOptionsType'])) {
      $this->addElement('Select', 'popularity', array(
          'label' => 'List By',
          'multiOptions' => array(
              '' => 'Select Popularity',
              'rating' => 'Most Rated',
              'favourite_count' => 'Most Favorite',
          ),
      ));
    }

    //Element: execute
    $this->addElement('Button', 'execute', array(
        'label' => 'Search',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));
  }

}
