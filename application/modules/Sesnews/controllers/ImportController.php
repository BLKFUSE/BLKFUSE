<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ImportController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesnews_ImportController extends Core_Controller_Action_Standard {

	public function init() {
		if( !$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'view')->isValid() ) return;
	}
	
  public function indexAction() {
    if( !$this->_helper->requireUser()->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'create')->isValid()) return;
    $this->view->form = $form = new Sesnews_Form_Import();
     // If not post or form not valid, return
    if( !$this->getRequest()->isPost() )
    return;
    if( !$form->isValid($this->getRequest()->getPost()) )
    return;
    if (empty($values['file_data'])) {
        $form->addError($this->view->translate("News XML File * Please complete this field - it is required."));
        return;
    }
    $file = $form->getElement ( 'file_data' );
    $path = $file->getDestination () . DIRECTORY_SEPARATOR . $file->getValue ();
    $xml = simplexml_load_file($path);
    if($_POST['import_type'] == 1) {
      $posts = array();
      if (empty($_FILES['file_data']['name'])) {
        $form->addError('Please select newsger xml file you want to import here.');
        return;
      }
      $xml =  $xml->entry;
      foreach( $xml as $row ) {
        $posts[] = array(
          "title"=>$row->title,
          "content"=>$row->content,
          "import_type" => 1,
        );
      }
      $this->importNews($posts);
    }elseif($_POST['import_type'] == 2) {
      $posts = array();
      if (empty($_FILES['file_data']['name'])) {
        $form->addError('Please select wordpress xml file you want to import here.');
        return;
      }
      foreach($xml->channel->item as $item) {
        $content = $item->children('http://purl.org/rss/1.0/modules/content/');
        $posts[] = array(
        "title"=>$item->title,
        "content"=>$content->encoded,
        "import_type" => 2,
        );
      }
      $this->importNews($posts);
    }elseif($_POST['import_type'] == 3) {
      if(empty($_POST['user_name'])) {
        $form->addError ('Username can not be empty.');
        return;
      }
      $i = 0;
      $posts = array();
      $finish = true;
      $counter = 1;
      $arrayCounter = 0;
      do {
        $fileUrl = "http://".$_POST['user_name'].".tumblr.com/api/read/json?start=".$i."&num=50";
        $content = @file_get_contents ( $fileUrl );
        $subContent  = preg_replace('#var tumblr_api_read = (.*);#','$1',$content);;
        $data = json_decode($subContent, true);
        foreach($data['posts'] as $item) {
          $posts[$arrayCounter] = array(
            "title"=>$item['regular-title'],
            "content"=>$item['regular-body'],
            "import_type" => 3,
          );
          $arrayCounter++;
        }
        $total = $data['posts-total'];
        $i = ($arrayCounter);
        if(ceil($total/50) <= $counter) {
          $finish = false;
        }
        ++$counter;
      }
      while($finish);
      $this->importNews($posts);
    }
    return $this->_helper->redirector->gotoRoute(array('action' => 'manage'),'sesnews_general',true);
  }
  public function importNews($posts) {
    foreach($posts as $post) {
      $title = $post['title'];
      if(!$title)
        continue;
      // Process
      $table = Engine_Api::_()->getDbtable('news', 'sesnews');
      $db = $table->getAdapter();
      $db->beginTransaction();
      try {
        // Create sesnews
        $viewer = Engine_Api::_()->user()->getViewer();
        $values = array();
        $sesnews = $table->createRow();
        $sesnews->ip_address = $_SERVER['REMOTE_ADDR'];
        $sesnews->title = htmlspecialchars_decode($title);
        $sesnews->body = $post['content'];
        $sesnews->owner_type = $viewer->getType();
        $sesnews->owner_id = $viewer->getIdentity();
        $sesnews->category_id = 0;
        $sesnews->subcat_id = 0;
        $sesnews->subsubcat_id = 0;
        $sesnews->is_approved = 1;
        $sesnews->seo_title = $title;
        if(isset($sesnews->package_id) && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') ){
          $package_id = Engine_Api::_()->getDbTable('packages','sesnewspackage')->getDefaultPackage();
          $sesnews->package_id = $package_id;
        }
        $sesnews->save();
        $news_id = $sesnews->news_id;
        $sesnews->custom_url = $news_id;
        $roleTable = Engine_Api::_()->getDbtable('roles', 'sesnews');
        $row = $roleTable->createRow();
        $row->news_id = $news_id;
        $row->user_id = $viewer->getIdentity();
        $row->save();
        // Auth
        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
        $values['auth_view'] = 'everyone';
        $values['auth_comment'] = 'everyone';
        $viewMax = array_search($values['auth_view'], $roles);
        $commentMax = array_search($values['auth_comment'], $roles);
        $videoMax = array_search(isset($values['auth_video']) ? $values['auth_video']: '', $roles);
        $musicMax = array_search(isset($values['auth_music']) ? $values['auth_music']: '', $roles);
        foreach( $roles as $i => $role ) {
          $auth->setAllowed($sesnews, $role, 'view', ($i <= $viewMax));
          $auth->setAllowed($sesnews, $role, 'comment', ($i <= $commentMax));
          $auth->setAllowed($sesnews, $role, 'video', ($i <= $videoMax));
          $auth->setAllowed($sesnews, $role, 'music', ($i <= $musicMax));
        }
        $sesnews->save();
        // Add activity only if sesnews is published
  //       if( $values['draft'] == 0 && $values['is_approved'] == 1 && (!$sesnews->publish_date || strtotime($sesnews->publish_date) <= time())) {
  //         $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $sesnews, 'sesnews_new');
  //         // make sure action exists before attaching the sesnews to the activity
  //         if( $action ) {
  //           Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $sesnews);
  //         }
  //         //Send notifications for subscribers
  //       	Engine_Api::_()->getDbtable('subscriptions', 'sesnews')->sendNotifications($sesnews);
  //       	$sesnews->is_publish = 1;
  //       	$sesnews->save();
  // 			}
        // Commit
        $db->commit();
      }
      catch( Exception $e ) {
        //silence and continue;
       //$db->rollBack();
      //throw $e;
      }
    }
  }
}
