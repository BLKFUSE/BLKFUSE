<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Menus.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Plugin_Menus
{
  public function setMenu($row)
  {

    $title = $url = $description = $photoUrl = $guid = '';
    // Check subject
    $view = Zend_Registry::get('Zend_View');
    if( !Engine_Api::_()->core()->hasSubject() ) {
      $request = Zend_Controller_Front::getInstance()->getRequest();
      $url = $request->getRequestUri();
    } else {
      $subject = Engine_Api::_()->core()->getSubject();
      $url = $subject->getHref();
      $title = urlencode($subject->getTitle());
      $description = urlencode($subject->getDescription());
      $guid = $subject->getGuid();
      $photoUrl = urlencode($view->serverUrl($subject->getPhotoUrl()));
    }
    $params = $row->params;

    $uri = str_replace(
      array('CONTENT_URI', 'CONTENT_TITLE', 'CONTENT_DESCRIPTION', 'CONTENT_MEDIA', 'CONTENT_GUID'), array(
      urlencode($view->serverUrl($url)),
      $title,
      $description,
      $photoUrl,
      $guid,
      ), $params['uri']
    );
    $baseUrl = $view->baseUrl();
    if( $row->name == 'siteshare_social_link_mail' && $baseUrl && $baseUrl != '/' ) {
      $uri = $baseUrl . $uri;
    }
    $params['data-class'] = !empty($params['class']) ? $params['class'] : '';
   // unset($params['class']);
    return array_merge($params, array(
      'label' => $row->label,
      'uri' => $uri,
      'target' => '_blank',
      'data-url' => $view->serverUrl($url),
      'data-service' => str_replace('siteshare_social_link_', '', $row->name),
    ));
  }

}
