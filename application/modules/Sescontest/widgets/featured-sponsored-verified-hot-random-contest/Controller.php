<?php/** * SocialEngineSolutions * * @category   Application_Sescontest * @package    Sescontest * @copyright  Copyright 2017-2018 SocialEngineSolutions * @license    http://www.socialenginesolutions.com/license/ * @version    $Id: Controller.php  2017-12-01 00:00:00 SocialEngineSolutions $ * @author     SocialEngineSolutions */class Sescontest_Widget_FeaturedSponsoredVerifiedHotRandomContestController extends Engine_Content_Widget_Abstract {  public function indexAction() {    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);    $show_criterias = $params['show_criteria'];    foreach ($show_criterias as $show_criteria)      $this->view->{$show_criteria . 'Active'} = $show_criteria;    $sescontest_widget = Zend_Registry::isRegistered('sescontest_widget') ? Zend_Registry::get('sescontest_widget') : null;    if(empty($sescontest_widget)) {      return $this->setNoRender();    }    $value['criteria'] = $params['criteria'];    $value['info'] = 'random';    $value['order'] = $params['order'];    $value['order_content'] = $params['order_content'];    $value['fetchAll'] = true;    $value['limit'] = 3;    $this->view->contests = Engine_Api::_()->getDbTable('contests', 'sescontest')->getContestSelect($value);    if (count($this->view->contests) < 1)      $this->setNoRender();  }}