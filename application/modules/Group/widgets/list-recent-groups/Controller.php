<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_Widget_ListRecentGroupsController extends Engine_Content_Widget_Abstract
{
    public function indexAction()
    {
        // Should we consider creation or modified recent?
        $recentType = $this->_getParam('recentType', 'creation');
        if( !engine_in_array($recentType, array('creation', 'modified')) ) {
            $recentType = 'creation';
        }
        $this->view->recentType = $recentType;
        $this->view->recentCol = $recentCol = $recentType . '_date';
        $params = array('search' => true);

        // Get paginator
        $table = Engine_Api::_()->getItemTable('group');
        $select = $table->getItemsSelect($params);

        if( $recentType == 'creation' ) {
            // using primary should be much faster, so use that for creation
            $select->order('group_id DESC');
        } else {
            $select->order($recentCol . ' DESC');
        }

        $select = Engine_Api::_()->network()->getNetworkSelect($table->info('name'), $select, 'user_id');

        $this->view->paginator = $paginator = Zend_Paginator::factory($select);

        // Set item count per page and current page number
        $paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 5));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));

        // Hide if nothing to show
        if( $paginator->getTotalItemCount() <= 0 ) {
            return $this->setNoRender();
        }
    }
}
