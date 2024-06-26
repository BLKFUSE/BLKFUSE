<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Category.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontest_Model_Category extends Core_Model_Category {
    protected $_searchTriggers = false;
    //Get category title
    public function getTitle() {
        if (!$this)
            return 'Deleted Category';
        return $this->category_name;
    }

    //Get category table name
    public function getTable() {
        if (is_null($this->_table)) {
            $this->_table = Engine_Api::_()->getDbtable('categories', 'sescontest');
        }
        return $this->_table;
    }

    //Category href
    public function getHref($params = array()) {
        if (!$this)
            return 'javascript:;';
        if ($this->slug == '')
            return;
        $params = array_merge(array(
            'route' => 'sescontest_category_view',
            'reset' => true,
            'category_id' => $this->slug,
                ), $params);
        $route = $params['route'];
        $reset = $params['reset'];
        unset($params['route']);
        unset($params['reset']);
        return Zend_Controller_Front::getInstance()->getRouter()->assemble($params, $route, $reset);
    }

    public function getBrowseCategoryHref($params = array()) {

        $params = array_merge(array(
            'route' => 'sescontest_general',
            'action' => 'browse',
            'reset' => true,
                ), $params);
        $route = $params['route'];
        $reset = $params['reset'];
        unset($params['route']);
        unset($params['reset']);
        return Zend_Controller_Front::getInstance()->getRouter()->assemble($params, $route, $reset);
    }

    public function getPhotoUrl($type = NULL) {
        if (!$this)
            return 'application/modules/Sescontest/externals/images/nophoto_contest_thumb_profile.png';
        $thumbnail = $this->thumbnail;
        if ($thumbnail) {
            $file = Engine_Api::_()->getItemTable('storage_file')->getFile($this->thumbnail, $type);
            if ($file)
                return $file->map();
        }
        return 'application/modules/Sescontest/externals/images/nophoto_contest_thumb_profile.png';
    }

    public function isOwner($owner) {

        if ($owner instanceof Core_Model_Item_Abstract) {
            return ( $this->getIdentity() == $owner->getIdentity() && $this->getType() == $owner->getType() );
        } else if (is_array($owner) && engine_count($owner) === 2) {
            return ( $this->getIdentity() == $owner[1] && $this->getType() == $owner[0] );
        } else if (is_numeric($owner)) {
            return ( $owner == $this->getIdentity() );
        }

        return false;
    }

    public function getOwner($recurseType = NULL) {
        return $this;
    }

}
