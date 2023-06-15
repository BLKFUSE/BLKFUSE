<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: WinnerSearch.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_WinnerSearch extends Engine_Form {

    public function init() {
        $restapi = Zend_Controller_Front::getInstance()->getRequest()->getParam('restApi', null);
        if ($restapi == 'Sesapi') {
            $coreContentTable = Engine_Api::_()->getDbTable('content', 'core');
            $coreContentTableName = $coreContentTable->info('name');
            $corePagesTable = Engine_Api::_()->getDbTable('pages', 'core');
            $corePagesTableName = $corePagesTable->info('name');
            $select = $corePagesTable->select()
                    ->setIntegrityCheck(false)
                    ->from($corePagesTable, null)
                    ->where($coreContentTableName . '.name=?', 'sescontest.winner-browse-search')
                    ->joinLeft($coreContentTableName, $corePagesTableName . '.page_id = ' . $coreContentTableName . '.page_id', $coreContentTableName . '.content_id')
                    ->where($corePagesTableName . '.name = ?', 'sescontest_index_winner');
            $id = $select->query()->fetchColumn();
            $params = Engine_Api::_()->sescontest()->getWidgetParams($id);
        } else {
            $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
            $params = Engine_Api::_()->sescontest()->getWidgetParams($view->identity);
        }

        $show_criterias = $params['show_option'];
        $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $actionName = $request->getActionName();

        $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET');
        if ($restapi != 'Sesapi') {
            $this->setAction($view->url(array('module' => 'sescontest', 'controller' => 'index', 'action' => 'winner'), 'default', true));
        }
        $viewer = Engine_Api::_()->user()->getViewer();
        if (engine_in_array('searchEntryTitle', $show_criterias)) {
            $this->addElement('Text', 'search_entry', array(
                'label' => 'Search Entries'
            ));
        }

        if (!isset($_GET['contest_id']) && engine_in_array('searchContestTitle', $show_criterias)) {
            $this->addElement('Text', 'search', array(
                'label' => 'Search In Contest'
            ));
        }

        if (engine_in_array('browseBy', $show_criterias)) {
            switch ($params['default_search_type']) {
                case 'recentlySPcreated':
                    $default_search_type = 'creation_date';
                    break;
                case 'rankSPlow':
                    $default_search_type = 'rank DESC';
                    break;
                case 'rankSPhigh':
                    $default_search_type = 'rank ASC';
                    break;
                case 'mostSPviewed':
                    $default_search_type = 'view_count';
                    break;
                case 'mostSPliked':
                    $default_search_type = 'like_count';
                    break;
                case 'mostSPcommented':
                    $default_search_type = 'comment_count';
                    break;
                case 'mostSPfavourite':
                    $default_search_type = 'favourite_count';
                    break;
                case 'mostSPvoted':
                    $default_search_type = 'vote_count';
                    break;
            }
            ;
            $filterOptions = array();
            foreach ($params['search_type'] as $key => $widgetOption) {
                if ($actionName == 'entries' && ($widgetOption == 'rankSPlow' || $widgetOption == 'rankSPhigh'))
                    continue;
                if (is_numeric($key))
                    $columnValue = $widgetOption;
                else
                    $columnValue = $key;
                $value = str_replace(array('SP', ''), array(' ', ' '), $columnValue);
                switch ($columnValue) {
                    case 'recentlySPcreated':
                        $columnValue = 'creation_date';
                        break;
                    case 'rankSPlow':
                        $value = 'Rank High to Low';
                        $columnValue = 'low';
                        break;
                    case 'rankSPhigh':
                        $value = 'Rank Low to High';
                        $columnValue = 'high';
                        break;
                    case 'mostSPviewed':
                        $columnValue = 'view_count';
                        break;
                    case 'mostSPliked':
                        $columnValue = 'like_count';
                        break;
                    case 'mostSPcommented':
                        $columnValue = 'comment_count';
                        break;
                    case 'mostSPfavourite':
                        $columnValue = 'favourite_count';
                        break;
                    case 'mostSPvoted':
                        $columnValue = 'vote_count';
                        break;
                }
                $filterOptions[$columnValue] = ucwords($value);
            }
            $filterOptions = array('' => '') + $filterOptions;
            $this->addElement('Select', 'sort', array(
                'label' => 'Browse By',
                'multiOptions' => $filterOptions,
                'value' => $default_search_type,
            ));
        }

        if (engine_in_array('view', $show_criterias)) {
            $filterOptions = array();
            foreach ($params['criteria'] as $key => $viewOption) {
                if (!$viewerId && ($key == 1 || $key == 2 || $key == 3))
                    continue;
                if (is_numeric($key))
                    $columnValue = $viewOption;
                else
                    $columnValue = $key;
                switch ($columnValue) {
                    case '0':
                        $value = 'All Entries';
                        break;
                    case '1':
                        $value = 'Only My Friend\'s Entries';
                        break;
                    case '2':
                        $value = 'Only My Network Entries';
                        break;
                    case '3':
                        $value = 'Only My Entries';
                        break;
                    case 'week':
                        $value = 'This Week';
                        break;
                    case 'month':
                        $value = 'This Month';
                        break;
                }
                $filterOptions[$columnValue] = ucwords($value);
            }
            $this->addElement('Select', 'show', array(
                'label' => 'Show',
                'multiOptions' => $filterOptions,
            ));
        }

        if (engine_in_array('mediaType', $show_criterias)) {
            $this->addElement('Select', 'media_type', array(
                'label' => 'Media Type',
                'multiOptions' => array('' => '', '1' => 'Text', '2' => 'Photo', '3' => 'Video', '4' => 'Music'),
            ));
        }

        if ($actionName == 'winner' && engine_in_array('rank', $show_criterias)) {
            $this->addElement('Select', 'rank', array(
                'label' => 'Rank',
                'multiOptions' => array('' => '', '1' => '1st Rank', '2' => '2nd Rank', '3' => '3rd Rank', '4' => '4th Rank', '5' => '5th Rank'),
            ));
        }

        $this->addElement('Hidden', 'page', array(
            'order' => 100
        ));

        $this->addElement('Button', 'submit', array(
            'label' => 'Search',
            'type' => 'submit'
        ));
    }

}
