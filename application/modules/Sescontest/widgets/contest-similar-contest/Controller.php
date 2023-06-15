<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Widget_ContestSimilarContestController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    if (!Engine_Api::_()->core()->hasSubject('contest'))
      return $this->setNoRender();

    $this->view->viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
		
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('contest');

    //Set default title
    if (!$this->getElement()->getTitle())
      $this->getElement()->setTitle('Similar ' . ucwords(str_replace('sescontest_', '', $subject->getType())));

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '12');
    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $searchType = $params['search'];

    if ($searchType == 'tag') {
      //Get tags for this contest
      $tagMapsTable = Engine_Api::_()->getDbtable('tagMaps', 'core');
      //Get tags
      $tags = $tagMapsTable->select()
              ->from($tagMapsTable, 'tag_id')
              ->where('resource_type = ?', $subject->getType())
              ->where('resource_id = ?', $subject->getIdentity())
              ->query()
              ->fetchAll(Zend_Db::FETCH_COLUMN);
      //No tags
      if (empty($tags))
        return $this->setNoRender();
      $values['sameTagresource_id'] = $subject->getIdentity();
      $values['sameTagTag_id'] = $tags;
      $values['sameTag'] = 'sameTag';
    }
    else {
      $values['category_id'] = $subject->category_id;
      $values['sameCategory'] = 'sameCategory';
      $values['contest_id'] = $subject->getIdentity();
    }
    $values['limit'] = $params['limit_data'];
    $values['fetchAll'] = true;
    if (isset($params['order']))
      $values['order'] = $params['order'];
    $this->view->contests = Engine_Api::_()->getDbTable('contests', 'sescontest')->getContestSelect($values);
    if (engine_count($this->view->contests) <= 0)
      return $this->setNoRender();
  }

}
