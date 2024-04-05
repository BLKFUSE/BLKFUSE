<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Core.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_Plugin_Core extends Zend_Controller_Plugin_Abstract  {

  public function routeShutdown(Zend_Controller_Request_Abstract $request) { 
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      $moduleName = $request->getModuleName();
   
    $controllerName = $request->getControllerName();
    $actionName = $request->getActionName();
    if((COURSESENABLED == 1) && (($moduleName == 'core' && $controllerName != 'comment' && $controllerName != 'widget') || ($moduleName == 'egifts'))) {
      $settings = Engine_Api::_()->getApi('settings', 'core');
      $arrayOfManifestUrl[] = $settings->getSetting('classroom.singular.manifest', 'course').'/';
      $replacedPath = trim(str_replace($arrayOfManifestUrl,'',$request->getPathInfo()),'/');
      $exploded = (explode('/',$replacedPath));
      $urlData = Engine_Api::_()->sesbasic()->checkBannedWord($exploded[0],"",$routeType = 1);
      if($urlData && $urlData->resource_type == 'egifts') {
        $request->setModuleName('egifts');
        $request->setControllerName('profile');
        $request->setActionName('index');
        $request->setParams(array('id' => $exploded[0]));
      }
    }
  }

  public function onRenderLayoutDefaultSimple($event) {
    return $this->onRenderLayoutDefault($event, 'simple');
  }
  public function onRenderLayoutDefault($event) {
    
    if( defined('_ENGINE_ADMIN_NEUTER') && _ENGINE_ADMIN_NEUTER ) return;
    
    $settings =  Engine_Api::_()->getApi('settings', 'core');
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $viewer = Engine_Api::_()->user()->getViewer();
    $request = Zend_Controller_Front::getInstance()->getRequest();  //echo "<pre>"; print_r($request);die;
    $moduleName = $request->getModuleName();
    $actionName = $request->getActionName();
    $controllerName = $request->getControllerName();
    $headScript = new Zend_View_Helper_HeadScript();
    $script = '';
    $headScript->appendFile(Zend_Registry::get('StaticBaseUrl') . 'application/modules/Courses/externals/scripts/core.js');
    $checkWelcomeCourse = $settings->getSetting('egifts.check.welcome', 2);
    $checkWelcomeEnable = $settings->getSetting('egifts.enable.welcome', 1);
    $checkWelcomeCourse = (($checkWelcomeCourse == 1 && $viewer->getIdentity() == 0) ? true : (($checkWelcomeCourse == 0 && $viewer->getIdentity() != 0) ? true : (($checkWelcomeCourse == 2) ? true : false)));
    if (!$checkWelcomeEnable)
      $checkWelcomeCourse = false;
    if ($actionName == 'welcome' && $controllerName == 'index' && $moduleName == 'egifts') {
      $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
      if (!$checkWelcomeCourse)
        $redirector->gotoRoute(array('module' => 'egifts', 'controller' => 'index', 'action' => 'home'), 'egifts_general', false);
      else if ($checkWelcomeEnable == 2)
        $redirector->gotoRoute(array('module' => 'egifts', 'controller' => 'index', 'action' => 'browse'), 'egifts_general', false);
      else if ($checkWelcomeEnable == 3)
        $redirector->gotoRoute(array('module' => 'egifts', 'controller' => 'category', 'action' => 'browse'), 'egifts_category', false);
    }
    $cartviewPage = 0;
    if($moduleName =='egifts' && $actionName == 'checkout' && $controllerName == 'cart'):
      $cartviewPage = 1;
    else:
      $cartviewPage = 0;
    endif; 
    $script .= "var cartviewPage = ".$cartviewPage.";";
    if ($moduleName == 'egifts' && $actionName == 'index' && $controllerName == 'profile') {
      $bagroundImageId = Engine_Api::_()->core()->getSubject('egifts')->photo_id;
      if ($bagroundImageId != 0 && $bagroundImageId != '') {
        $backgroundImage = Engine_Api::_()->storage()->get($bagroundImageId, '')->getPhotoUrl();
      }
      if (isset($backgroundImage)) {
//         $script .= "scriptJquery(document).ready(function() {document.getElementById('global_wrapper').style.backgroundImage = \"url('" . $backgroundImage . "')\";});";
      }
      $view->headLink()->appendStylesheet(Zend_Registry::get('StaticBaseUrl')
              . 'application/modules/Courses/externals/styles/styles.css');
    }
   
    $cartTotal = "0";
		if($settings->getSetting('egifts.pluginactivated')) {
		$totalCourse = Engine_Api::_()->egifts()->cartTotalPrice();
			if($totalCourse['cartCoursesCount']){
					$cartTotal = ($totalCourse['cartCoursesCount']);
			}
		}
    $singlecart = Engine_Api::_()->getApi('settings', 'core')->getSetting('site.enble.singlecart', 0); // this setting is using from sesbasic plugin
    $html = '<span class="cart_value egifts_cart_count">'.$cartTotal.'</span>';
		if($settings->getSetting('egifts.cartviewtype',1)== '1') {
			$script .= "scriptJquery(document).ready(function(){
				scriptJquery('.egifts_add_cart_dropdown').append('".$html."');";
		}
		elseif($settings->getSetting('egifts.cartviewtype', '2') == 2) {
			$script .= "scriptJquery(document).ready(function(){
				scriptJquery('.egifts_add_cart_dropdown').append('".$html."');";
    }else if($settings->getSetting('egifts.cartviewtype', '3') == '3'){
			$script .= "scriptJquery(document).ready(function(){
			scriptJquery('.egifts_add_cart_dropdown').append('".$html."');";
		}
		$script .= "
		var valueCart = scriptJquery('.egifts_cart_count').html();
		if(parseInt(valueCart) <=0 || !valueCart){
			scriptJquery('.egifts_cart_count').hide();
		}});";
		if($settings->getSetting('egifts.cartdropdown',1) && !$singlecart){
			$script .= "scriptJquery(document).on('click','.egifts_add_cart_dropdown',function(e){
				e.preventDefault();
				if(scriptJquery(this).hasClass('active')){
						scriptJquery('.egifts_cart_dropdown').hide();
						scriptJquery('.egifts_add_cart_dropdown').removeClass('active');
						return;
				}
				scriptJquery('.egifts_add_cart_dropdown').addClass('active');
				if(!scriptJquery(this).parent().find('.egifts_cart_dropdown').length){
						scriptJquery(this).parent().append('<div class=\"egifts_cart_dropdown sesbasic_header_pulldown sesbasic_clearfix sesbasic_bxs sesbasic_cart_pulldown\"><div class=\"sesbasic_header_pulldown_inner\"><div class=\"sesbasic_header_pulldown_loading\"><img src=\"application/modules/Core/externals/images/loading.gif\" alt=\"Loading\" /></div></div></div>');
				}
				scriptJquery('.egifts_cart_dropdown').show();
				scriptJquery.post('egifts/cart/view',{cart_page:cartviewPage},function(res){
						scriptJquery('.egifts_cart_dropdown').html(res);
				});
			});";
			$script .= "
				scriptJquery(document).click(function(e){
				var elem = scriptJquery('.egifts_cart_dropdown').parent();
				if(!elem.has(e.target).length){
					 scriptJquery('.egifts_cart_dropdown').hide();
					 scriptJquery('.egifts_add_cart_dropdown').removeClass('active');
				}
			});";
		}
    if(!$settings->getSetting('eclassroom.enable.course', 1)) { 
      $openPopup = 0;
      $script .= "var isOpenCoursePopup = '" . $openPopup . "';var showAddnewCourseIconShortCut = " . $settings->getSetting('egifts.enable.addegiftshortcut', 1) . ";";
      // Check sesalbum plugin is enabled for lightbox
      if (Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesalbum')) {
        $script .= "var sesAlbumEnabled = 1;";
      } else {
        $script .= "var sesAlbumEnabled = 0;";
      }
      if(($moduleName == 'egifts') && ($controllerName != "index" || $actionName != "create") && ($controllerName != "dashboard" || $actionName != "edit") && $viewer->getIdentity() != 0 && Engine_Api::_()->authorization()->isAllowed('egifts', $viewer, 'create')) {
        $script .= 'scriptJquery(document).ready(function() {
          if(scriptJquery("body").attr("id").search("egifts") > -1 && typeof showAddnewCourseIconShortCut != "undefined" && showAddnewCourseIconShortCut && typeof isOpenCoursePopup != "undefined" && isOpenCoursePopup == 1){
            scriptJquery("<a class=\'sesbasic_create_button egifts_quick_create_button sessmoothbox sesbasic_animation\' href=\'' . $view->url(array('action' => 'create'), 'egifts_general') . '\' title=\'Add New Courses\'><i class=\'fa fa-plus\'></i></a>").appendTo("body");
          }
          else if(scriptJquery("body").attr("id").search("egifts") > -1 && typeof showAddnewCourseIconShortCut != "undefined" && showAddnewCourseIconShortCut){
            scriptJquery("<a class=\'sesbasic_create_button egifts_quick_create_button sesbasic_animation\' href=\'' . $view->url(array('action' => 'create'), 'egifts_general') . '\' title=\'Add New Courses\'><i class=\'fa fa-plus\'></i></a>").appendTo("body");
          }
        });';
      }
      $script .= "var egiftsURL = '" . $settings->getSetting('egifts.plural.manifest', 'egifts') . "';";
      $script .= "var courseURL = '" . $settings->getSetting('egifts.singular.manifest', 'course') . "';";
    }
    $view->headScript()->appendScript($script);
  }
  protected function insertCourseLikeCommentDetails($payload, $type) {
    if(!empty($payload->resource_type) && $payload->resource_type == 'egifts' && $type == "core_like") {
      $table = Engine_Api::_()->getDbTable('likes', 'sesbasic');
      $db = $table->getAdapter();
      $db->beginTransaction();
      try {
        $item = $table->createRow();
        $item->resource_type = 'egifts';
        $item->resource_id = $payload->resource_id;
        $item->poster_type = "user";
        $item->poster_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $item->creation_date = date('Y-m-d H:i:s');
        $item->save();
        $db->commit();
      } catch (Exception $e) {}
    }
    if (!empty($payload->poster_type) && $payload->poster_type == "egifts"){
      $table = Engine_Api::_()->getDbTable('activitycomments', 'egifts');
      $db = $table->getAdapter();
      $db->beginTransaction();
      try {
        $itemTable = Engine_Api::_()->getDbTable('activitycomments', 'egifts');
        $item = $itemTable->createRow();
        $item->type = $type;
        $item->item_id = !empty($payload->like_id) ? $payload->like_id : $payload->getIdentity();
        $item->course_id = $payload->poster_id;
        $item->course_type = "egifts";
        $item->user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $item->user_type = 'user';
        $item->save();
        $db->commit();
      } catch (Exception $e){throw $e;}
    }
  }

  public function onActivityCommentCreateAfter($event){
    $payload = $event->getPayload();
    $this->insertCourseLikeCommentDetails($payload, 'activity_comment');
  }

  public function onActivityLikeCreateAfter($event){
    $payload = $event->getPayload();
    $this->insertCourseLikeCommentDetails($payload, 'activity_like');
  }

  public function onCoreLikeCreateAfter($event){
    $payload = $event->getPayload();
    $this->insertCourseLikeCommentDetails($payload, 'core_like');
  }
  protected function deleteLikeComment($payload, $type){
    if ($payload) {
      if (!empty($payload->poster_type) && @$payload->poster_type == "egifts") {
        $table = Engine_Api::_()->getDbTable('activitycomments', 'egifts');
        $select = $table->select()->where('item_id =?', $payload->getIdentity())->where('type =?', $type);
        $result = $table->fetchRow($select);
        if ($result)
          $result->delete();
      }
    }
  }
  public function onCoreCommentDeleteAfter($event) {
    $payload = $event->getPayload();
    $this->deleteLikeComment($payload, 'core_comment');
  }

  public function onActivityCommentDeleteAfter($event) {
    $payload = $event->getPayload();
    $this->deleteLikeComment($payload, 'activity_comment');
  }

  public function onActivityLikeDeleteAfter($event) {
    $payload = $event->getPayload();
    $this->deleteLikeComment($payload, 'activity_like');
  }

  public function onCoreLikeDeleteAfter($event) {
    $payload = $event->getPayload();
    if ($payload)
      $this->deleteLikeComment($payload, 'core_comment');
  }

  public function onCoreLikeDeleteBefore($event) {
    $payload = $event->getPayload();
    if ($payload) {
      if (!empty($payload->resource_type ) && @$payload->resource_type == 'egifts') {
        $table = Engine_Api::_()->getDbTable('likes', 'sesbasic');
        $select = $table->select()->where('resource_id =?', $payload->resource_id)->where('resource_type =?', 'egifts')->where('poster_id =?',Engine_Api::_()->user()->getViewer()->getIdentity());
        $result = $table->fetchRow($select);
        if ($result)
          $result->delete();
      }
    }
  }

  public function onCommentCreateAfter($event) {
    $payload = $event->getPayload();
    $this->insertCourseLikeCommentDetails($payload, $payload->getType());
  }

  public function multiPost($payload, $viewer) {
    $res_type = $payload->object_type;
    $res_id = $payload->object_id;
    $main_action_id = $payload->getIdentity();
    //check course enable scroll posting
    $viewer_id = $viewer->getIdentity();
    $course = Engine_Api::_()->getItem('egifts', $res_id);

    $db = Engine_Db_Table::getDefaultAdapter();
    $table = Engine_Api::_()->getDbTable('actions', 'sesadvancedactivity');
    foreach ($_POST['multicourse'] as $courseGuid) {
      $course = Engine_Api::_()->getItemByGuid($courseGuid);
      $course_id = $course->getIdentity();
      if (!$course)
        continue;
      $courseOwner = $course->getOwner();
      if (!$courseOwner)
        continue;
      $courseOwnerId = $courseOwner->getIdentity();

      $select = "SELECT * FROM `engine4_activity_actions` WHERE action_id = " . $main_action_id;
      $action_id = $this->createRowCustom($db, $main_action_id, $select, 'engine4_activity_actions', 'action_id');
      if (!$action_id)
        continue;

      $action = Engine_Api::_()->getItem('sesadvancedactivity_action', $action_id);
      $action->subject_id = $viewer->getIdentity();
      $action->object_id = $course_id;
      $action->save();
      $detail_id = Engine_Api::_()->getDbTable('details', 'sesadvancedactivity')->isRowExists($action->getIdentity());
      if($detail_id) {
        $detailAction = Engine_Api::_()->getItem('sesadvancedactivity_detail',$detail_id);
        $detailAction->sesresource_type = '';
        $detailAction->sesresource_id = '';
        $detailAction->save();
      }

      $select = "SELECT * FROM `engine4_sesbasic_locations` WHERE resource_type = 'activity_action' AND resource_id = " . $main_action_id;
      $this->createRowCustom($db, $main_action_id, $select, 'engine4_sesbasic_locations', 'location_id');

      $table->resetActivityBindings($action);

      $select = "INSERT INTO engine4_activity_attachments (action_id,type,id,mode) SELECT '" . $action_id . "',type,id,mode FROM `engine4_activity_attachments` WHERE action_id = " . $main_action_id;
      $db->query($select);

      $select = "SELECT * FROM `engine4_sesadvancedactivity_buysells` WHERE action_id = " . $main_action_id;
      $buysell_id = $this->createRowCustom($db, $main_action_id, $select, 'engine4_sesadvancedactivity_buysells', 'buysell_id');
      $buysell = Engine_Api::_()->getItem('sesadvancedactivity_buysell', $buysell_id);
      if ($buysell) {
        $buysell->action_id = $action_id;
        $buysell->save();
      }
      $select = "INSERT INTO engine4_sesadvancedactivity_hashtags (action_id,title) SELECT '" . $action_id . "',title FROM `engine4_sesadvancedactivity_hashtags` WHERE action_id = " . $main_action_id;
      $db->query($select);

      $select = "INSERT INTO engine4_sesadvancedactivity_tagusers (action_id,user_id) SELECT '" . $action_id . "',user_id FROM `engine4_sesadvancedactivity_tagusers` WHERE action_id = " . $main_action_id;
      $db->query($select);

      $select = "INSERT INTO engine4_sesadvancedactivity_tagitems (action_id,resource_id,resource_type,user_id) SELECT '" . $action_id . "','resource_id','resource_type','user_id' FROM `engine4_sesadvancedactivity_tagitems` WHERE action_id = " . $main_action_id;
      $db->query($select);

      $select = "SELECT * FROM `engine4_sesadvancedactivity_targetpost` WHERE action_id = " . $main_action_id;
      $action_id = $this->createRowCustom($db, $main_action_id, $select, 'engine4_sesadvancedactivity_targetpost', 'targetpost_id');

      $action->save();
    }
  }

  public function onActivitySubmittedAfter($event) { 
    if (!Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesadvancedactivity'))
      return true;
    $payload = $event->getPayload();
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!empty($_POST['multicourse'])) {
      $this->multiPost($payload, $viewer);
    }

//     //this is only for status post.
//     if($payload->type == 'post') {
//       $course = Engine_Api::_()->getItem('egifts', $payload->object_id);
//       $action = Engine_Api::_()->getDbTable('actions', 'activity')->addActivity($viewer, $course, 'egifts_course_postedpost', null);
//     }
  }

  protected function createRowCustom($db, $main_action_id, $select, $tablename, $columnName,$action_id = 0) {
    $db->query("CREATE TEMPORARY TABLE tmptable_" . $main_action_id . " " . $select);
    $db->query("UPDATE tmptable_" . $main_action_id . " SET " . $columnName . " = NULL;");
    if($action_id && $columnName == "detail_id"){
      $db->query("UPDATE tmptable_" . $main_action_id . " SET action_id = ".$action_id.";");
    }
    $db->query("INSERT INTO " . $tablename . " SELECT * FROM tmptable_" . $main_action_id . ";");
    $insertId = $db->lastInsertId();
    $db->query("DROP TEMPORARY TABLE IF EXISTS tmptable_" . $main_action_id . ";");
    return $insertId;
  }

  public function getAdminNotifications($event) {
    // Awaiting approval
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('egifts.pluginactivated'))
      return;
    $courseTable = Engine_Api::_()->getItemTable('egifts');
    $select = new Zend_Db_Select($courseTable->getAdapter());
    $select->from($courseTable->info('name'), 'COUNT(course_id) as count')->where('is_approved = ?', 0);
    $data = $select->query()->fetch();
    if (empty($data['count'])) {
    return;
    }
    $translate = Zend_Registry::get('Zend_Translate');
    $message = vsprintf($translate->translate(array(
                'There is <a href="%s">%d new course</a> awaiting your approval.',
                'There are <a href="%s">%d new egifts</a> awaiting your approval.',
                $data['count']
            )), array(
        Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'egifts', 'controller' => 'manage'), 'admin_default', true) . '?is_approved=0',
        $data['count'],
    ));
    $event->addResponse($message);
  }
  function onUserDeleteBefore($event){
    $payload = $event->getPayload();
    if ($payload instanceof User_Model_User) {
      $user_id = $payload->getIdentity();
      $coursetable = Engine_Api::_()->getDbTable('egifts','egifts');
      $classroomtable = Engine_Api::_()->getDbTable('classrooms','eclassroom');
      $select = $classroomtable->select()->where('owner_id =?',$user_id);
      $classrooms = $classroomtable->fetchAll($select);
      foreach($classrooms as $item){
        $classroom = Engine_Api::_()->getItem('classroom', $item->classroom_id);
        if(!empty($classroom))
            Engine_Api::_()->egifts()->deleteClassroom($classroom);
      }
      $select = $coursetable->select()->where('owner_id =?',$user_id);
      $items = $coursetable->fetchAll($select);
      foreach($items as $item){
        $course = Engine_Api::_()->getItem('egifts', $item->course_id);
        if(!empty($course))
            Engine_Api::_()->egifts()->deleteCourse($course);
      }
    }
  }
  public function onUserLoginAfter($event)
  { 
    $payload = $event->getPayload(); 
    if( !($payload instanceof User_Model_User) ) {
      return;
    }
    $phpSessionId = session_id();
    $table = Engine_Api::_()->getDbTable('carts', 'egifts');
    $select = $table->select();
    $select->where('phpsessionid =?', $phpSessionId);
    $cart =  $table->fetchRow($select);
    $select = $table->select();
    $select->where('owner_id =?', $payload->getIdentity());
    $loggedInUsercart =  $table->fetchRow($select);
    if($cart){
       if($loggedInUsercart){
           $cartegiftsTable = Engine_Api::_()->getDbTable('cartegifts','egifts');
           $cartegiftsTable->update(array('cart_id' => $loggedInUsercart->cart_id), array('cart_id' => $cart->cart_id));
        } else {
          $table->update(array('owner_id' => $payload->getIdentity()), array('phpsessionid =?' => $phpSessionId));
        }
    }
  }
}
