<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php 
 $action = Engine_Api::_()->getDbtable('actions', 'advancedactivity')->getActionById($this->action);
     echo $this->advancedActivity($action, array('onlyPreview' => 1, 'feedSettings' => array('memberPhotoStyle' => 'left')));
     ?>