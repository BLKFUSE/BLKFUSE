<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sharetype.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Model_Sharetype extends Core_Model_Item_Abstract
{

  protected $_searchTriggers = false;
  protected $_modifiedTriggers = false;

  public function save()
  {
    return parent::save();
  }
}
