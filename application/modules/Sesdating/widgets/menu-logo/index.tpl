<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php
$title = Engine_Api::_()->getApi('settings', 'core')->getSetting('core_general_site_title', $this->translate('_SITE_TITLE'));
$logo  = Engine_Api::_()->sesdating()->getFileUrl($this->logo);
$route = $this->viewer()->getIdentity()
             ? array('route'=>'user_general', 'action'=>'home')
             : array('route'=>'default');

echo ($logo)
     ? $this->htmlLink($route, $this->htmlImage($logo, array('alt'=>$title)))
     : $this->htmlLink($route, $title);

