<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Composer.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_Plugin_Composer extends Core_Plugin_Abstract
{
  public function onAttachLink($data)
  {
    try {
      $viewer = Engine_Api::_()->user()->getViewer();
      if( Engine_Api::_()->core()->hasSubject() ) {
        $subject = Engine_Api::_()->core()->getSubject();
        if( $subject->getType() != 'user' ) {
          $data['parent_type'] = $subject->getType();
          $data['parent_id'] = $subject->getIdentity();
        }
      }

      // Filter HTML
      $filter = new Zend_Filter();
      $filter->addFilter(new Engine_Filter_Censor());
      $filter->addFilter(new Engine_Filter_HtmlSpecialChars());
      if( !empty($data['title']) ) {
        $data['title'] = html_entity_decode($filter->filter($data['title']));
      }
      if( !empty($data['description']) ) {
        $data['description'] = html_entity_decode($filter->filter($data['description']));
      }
      $iframelyConfig = Engine_Api::_()->getApi('settings', 'core')->core_iframely;
      if( !empty($iframelyConfig['host']) && $iframelyConfig['host'] != 'socialengine' ) {
        $response = Engine_Iframely::factory($iframelyConfig)->get($data['uri']);
				$data['params']['iframely'] = $response ? json_encode($response) : array();
      }
      $link = Engine_Api::_()->getApi('links', 'core')->createLink($viewer, $data);
    } catch( Exception $e ) {
      throw $e;
    }
    return $link;
  }
}
