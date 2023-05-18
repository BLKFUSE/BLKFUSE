<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Attachments.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Model_Attachment extends Core_Model_Item_Abstract {
  protected $_searchTriggers = false;
  public function getHref($params = array()){
    $attachment_id = $this->attachment_id;
    $destination_url = $this->destination_url;
    $sescommunityad_id = $this->sescommunityad_id;

    $token = 'CDS'.$attachment_id.'CDS'.$sescommunityad_id;
    $crypted_token = urlencode(Engine_Api::_()->sescommunityads()->encrypt($token));
    unset($token);

    $params = array_merge(array(
      'route' => 'sescommunityads_redirect',
      'reset' => true,
      'redirect' => $crypted_token,
    ), $params);
    $route = $params['route'];
    $reset = $params['reset'];
    unset($params['route']);
    unset($params['reset']);
    return Zend_Controller_Front::getInstance()->getRouter()
      ->assemble($params, $route, $reset);
  }

}
