<?php

class Egames_IndexController extends Sesapi_Controller_Action_Standard
{
  public function init(){
    // only show to member_level if authorized
    if( !$this->_helper->requireAuth()->setAuthParams('egames_game', null, 'view')->isValid() ) 
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
  }
  public function menuAction()
    {
        $menu_counter = 0;
        if($this->view->viewer()->getIdentity()){
            $menus = Engine_Api::_()->getApi('menus', 'core')->getNavigation('egames_main', array());
            foreach ($menus as $menu) {
                $class = end(explode(' ', $menu->class));					 
                $result_menu[$menu_counter]['label'] = $this->view->translate($menu->label);
                $result_menu[$menu_counter]['action'] = $class;
                $result_menu[$menu_counter]['isActive'] = $menu->active;
                $menu_counter++;
            }
        }else{
            $result_menu[$menu_counter]['label'] = $this->view->translate('Browse Games');
                $result_menu[$menu_counter]['action'] = 'egames_main_browse';
                $result_menu[$menu_counter]['isActive'] = true;
        }
        $result['menus'] = $result_menu;
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $result)));
    }
  public function getGamesAction(){
    $sesdata = array();
    $games_table = Engine_Api::_()->getDbtable('games', 'egames');
		
    $select = $games_table->select()
                    ->where('title  LIKE ? ', '%' . $this->_getParam('text') . '%')
										->where("search  =?", 1)
                    ->order('game_id ASC')->limit('10');
    $games = $games_table->fetchAll($select);
    foreach ($games as $game) {
      $game_icon_photo = $this->view->itemPhoto($game, 'thumb.icon');
      $sesdata[] = array(
          'id' => $game->game_id,
          'label' => $game->title,
          'photo' => $game_icon_photo
      );
    }
    return $this->_helper->json($sesdata);
  }
  public function browseAction()
  {
    $paginator = Engine_Api::_()->getDbTable("games",'egames')->getGamesPaginator($this->_getAllParams());
    $paginator->setItemCountPerPage($this->_getParam("limit",10));
    $paginator->setCurrentPageNumber( $this->_getParam("page",1));
    $result = $this->gameResult($paginator);
    
    if($this->_getParam('owner_id')) {
        
      $viewer = Engine_Api::_()->user()->getViewer();
      $menuoptions= array();
      $canEdit = Engine_Api::_()->authorization()->getPermission($viewer, 'egames_game', 'edit');
      $counter = 0;
      if($canEdit) {
        $menuoptions[$counter]['name'] = "edit";
        $menuoptions[$counter]['label'] = $this->view->translate("Edit");
        $counter++;
      }

      $canDelete = Engine_Api::_()->authorization()->getPermission($viewer, 'egames_game', 'delete');
      if($canDelete) {
        $menuoptions[$counter]['name'] = "delete";
        $menuoptions[$counter]['label'] = $this->view->translate("Delete");
      }
      $result['menus'] = $menuoptions;
    }

    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    if($result <= 0)
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=> $this->view->translate('No games created yet.'), 'result' => array()));
    else
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));
    
  }
  function gameResult($paginator) {

    $result = array();
    $counterLoop = 0;
    $viewer = Engine_Api::_()->user()->getViewer();

    foreach($paginator as $item) {

      $resource = $item->toArray();
      
      $resource['owner_title'] = Engine_Api::_()->getItem('user', $resource['owner_id'])->getTitle();
      $resource['resource_type'] = $item->getType();
      $resource['resource_id'] = $item->getIdentity();

      //Category name
      if(!empty($resource['category_id'])) {
        $category = Engine_Api::_()->getItem('egames_category', $resource['category_id']);
        $resource['category_name'] = $category->category_name;
        if(!empty($resource['subcat_id'])) {
            $category = Engine_Api::_()->getItem('egames_category', $resource['subcat_id']);
            $resource['subcategory_name'] = $category->category_name;
            if(!empty($resource['subsubcat_id'])) {
                $category = Engine_Api::_()->getItem('egames_category', $resource['subsubcat_id']);
                $resource['subsubcategory_name'] = $category->category_name;
            }
        }
      }

      // Check content like or not and get like count
        if($viewer->getIdentity() != 0) {
          $resource['is_content_like'] = Engine_Api::_()->sesapi()->contentLike($item);
          $resource['content_like_count'] = (int) Engine_Api::_()->sesapi()->getContentLikeCount($item);
        }
      
      
        $result['games'][$counterLoop] = $resource;
        $result['games'][$counterLoop]['user_images'] = $this->userImage($item->owner_id,"thumb.profile");    
        $result['games'][$counterLoop]['images'] = $this->getBaseUrl(true, $item->getPhotoUrl());
        $counterLoop++;
    }
    return $result;
  }
  public function deleteAction()
    {
        $viewer = Engine_Api::_()->user()->getViewer();
        $game = Engine_Api::_()->getItem('egames_game', $this->getRequest()->getParam('game_id'));
        if( !$this->_helper->requireAuth()->setAuthParams($game, null, 'delete')->isValid()) 
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));


        // In smoothbox
        
        $this->view->form = $form = new Egames_Form_Delete();

        if( !$game ) {
            $status = false;
            $error = Zend_Registry::get('Zend_Translate')->_("Game doesn't exist or not authorized to delete");
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=> $error, 'result' => array()));
        }

        if( !$this->getRequest()->isPost() ) {
            $status = false;
            $error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=> $error, 'result' => array()));
        }

        $db = $game->getTable()->getAdapter();
        $db->beginTransaction();

        try {
            $game->delete();

            $db->commit();
        } catch( Exception $e ) {
            $db->rollBack();
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
        }

        $message = Zend_Registry::get('Zend_Translate')->_('Your game has been deleted.');
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => $message));
    }

    public function editAction()
    {
        
        if( !$this->_helper->requireUser()->isValid() ) 
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));


        $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->game_id = $this->_getParam('game_id');
        $game = Engine_Api::_()->getItem('egames_game', $this->_getParam('game_id'));
        if( !Engine_Api::_()->core()->hasSubject('egames_game') ) {
            Engine_Api::_()->core()->setSubject($game);
        }

        if( !$this->_helper->requireSubject()->isValid() ) 
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));


        if( !$this->_helper->requireAuth()->setAuthParams($game, $viewer, 'edit')->isValid() ) {
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
        }
       
        // Prepare form
        $this->view->form = $form = new Egames_Form_Edit();

        // Populate form
        $form->populate($game->toArray());

        $auth = Engine_Api::_()->authorization()->context;
        
        $roles = array('owner', 'member', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
        

        foreach( $roles as $role ) {
            if ($form->auth_view){
                if( $auth->isAllowed($game, $role, 'view') ) {
                    $form->auth_view->setValue($role);
                }
            }

            if ($form->auth_comment){
                if( $auth->isAllowed($game, $role, 'comment') ) {
                    $form->auth_comment->setValue($role);
                }
            }
        }

        
        if($this->_getParam('getForm')) {
            $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
            $this->generateFormFields($formFields,array('resources_type'=>'egames_game'));
        }
        // Check post/form
        if( !$this->getRequest()->isPost() ) {
            return;
        }
        if( !$form->isValid($this->getRequest()->getPost()) ) {
            $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
            if(is_countable($validateFields) && engine_count($validateFields))
              $this->validateFormFields($validateFields);
          }


        // Process
        $db = Engine_Db_Table::getDefaultAdapter();
        $db->beginTransaction();

        try {
            $values = $form->getValues();


            if( empty($values['auth_view']) ) {
                $values['auth_view'] = 'everyone';
            }
            if( empty($values['auth_comment']) ) {
                $values['auth_comment'] = 'everyone';
            }

            $values['view_privacy'] = $values['auth_view'];

            $game->setFromArray($values);
            $game->modified_date = date('Y-m-d H:i:s');
            $game->save();

            // Add photo
            if( !empty($values['photo']) ) {
                $game->setPhoto($form->photo);
            }

            // Auth
            $viewMax = array_search($values['auth_view'], $roles);
            $commentMax = array_search($values['auth_comment'], $roles);

            foreach( $roles as $i => $role ) {
                $auth->setAllowed($game, $role, 'view', ($i <= $viewMax));
                $auth->setAllowed($game, $role, 'comment', ($i <= $commentMax));
            }
            $db->commit();

        }
        catch( Exception $e ) {
            $db->rollBack();
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
        }

        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('game_id' => $game->getIdentity(),'message' => $this->view->translate('Game edited successfully.'))));
    }

  public function createAction()
  {

    if( !$this->_helper->requireUser()->isValid() ) 
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    if( !$this->_helper->requireAuth()->setAuthParams('egames_game', null, 'create')->isValid()) 
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));


    // set up data needed to check quota
    $viewer = Engine_Api::_()->user()->getViewer();
    $values['owner_id'] = $viewer->getIdentity();
    $paginator = Engine_Api::_()->getItemTable('egames_game')->getGamesPaginator($values);
    

    $this->view->quota = $quota = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'egames_game', 'max_games');
    $this->view->current_count = $current_count = $paginator->getTotalItemCount();

    if (($current_count >= $quota) && !empty($quota)) {
        // return error message
        $message = $this->view->translate('You have already uploaded the maximum number of games allowed.');
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error' => '1', 'error_message' => $message, 'result' => array()));
      }

    // Prepare form
    $this->view->form = $form = new Egames_Form_Create();

    // Check if post and populate
    if($this->_getParam('getForm')) {
        $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
        $this->generateFormFields($formFields,array('resources_type'=>'egames_game'));
    }
    

    if( !$form->isValid($this->getRequest()->getPost()) ) {
        $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
        if(is_countable($validateFields) && engine_count($validateFields))
            $this->validateFormFields($validateFields);
    }

    // Process
    $table = Engine_Api::_()->getItemTable('egames_game');
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
        $viewer = Engine_Api::_()->user()->getViewer();
        $formValues = $form->getValues();
        if (empty($formValues['category_id']))
          $formValues['category_id'] = 0;
        if (empty($formValues['subsubcat_id']))
          $formValues['subsubcat_id'] = 0;
        if (empty($formValues['subcat_id']))
          $formValues['subcat_id'] = 0;
        if( empty($formValues['auth_view']) ) {
            $formValues['auth_view'] = 'everyone';
        }
        if( empty($formValues['auth_comment']) ) {
            $formValues['auth_comment'] = 'everyone';
        }
        $formValues['owner_id'] = $viewer->getIdentity();
        $values = array_merge($formValues, array(
            'view_privacy' => $formValues['auth_view'],
        ));
        $game = $table->createRow();
        $game->setFromArray($values);
        $game->save();
        if( !empty($values['photo']) ) {
            $game->setPhoto($form->photo);
        }
        // Auth
        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
        $viewMax = array_search($values['auth_view'], $roles);
        $commentMax = array_search($values['auth_comment'], $roles);
        foreach( $roles as $i => $role ) {
            $auth->setAllowed($game, $role, 'view', ($i <= $viewMax));
            $auth->setAllowed($game, $role, 'comment', ($i <= $commentMax));
        }
        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $game, 'egames_game_create', '', array('privacy' => null));
        // make sure action exists before attaching the game to the activity
        if( $action ) {
            Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $game);
        }
        // Commit
        $db->commit();
    } catch( Exception $e ) {
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('game_id' => $game->getIdentity(),'message' => $this->view->translate('Game created successfully.'))));
  }
  
  public function viewAction()
  {
    // Check permission
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->game = $game = Engine_Api::_()->getItem('egames_game', $this->_getParam('game_id'));
    if( $game ) {
        Engine_Api::_()->core()->setSubject($game);
    }
    

    if( !$this->_helper->requireSubject()->isValid() ) {
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    }
    if( !$this->_helper->requireAuth()->setAuthParams($game, $viewer, 'view')->isValid() ) {
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    }


    $gameTable = Engine_Api::_()->getItemTable('egames_game');

    if( !$game->isOwner($viewer) ) {
      $gameTable->update(array(
          'view_count' => new Zend_Db_Expr('view_count + 1'),
      ), array(
          'game_id = ?' => $game->getIdentity(),
      ));
    }

    $games = $game->toArray();
    if($viewer->getIdentity() != 0) {
        $games['is_content_like'] = Engine_Api::_()->sesapi()->contentLike($game);
        $games['content_like_count'] = (int) Engine_Api::_()->sesapi()->getContentLikeCount($game);
      }
    
      $result['game'] = $games; 
    
      if($viewer->getIdentity() > 0) {
        $result['game']['permission']['canEdit'] = $canEdit = $viewPermission = $game->authorization()->isAllowed($viewer, 'edit') ? true : false;
        $result['game']['permission']['canComment'] =  $game->authorization()->isAllowed($viewer, 'comment') ? true : false;
        $result['game']['permission']['canCreate'] = Engine_Api::_()->authorization()->getPermission($viewer, 'egames_game', 'create') ? true : false;
        $result['game']['permission']['can_delete'] = $canDelete  = $game->authorization()->isAllowed($viewer,'delete') ? true : false;
  
        $menuoptions= array();
        $counter = 0;
      
        if($canEdit) {
          $menuoptions[$counter]['name'] = "edit";
          $menuoptions[$counter]['label'] = $this->view->translate("Edit Game");
          $counter++;
        }
        if($canDelete){
          $menuoptions[$counter]['name'] = "delete";
          $menuoptions[$counter]['label'] = $this->view->translate("Delete Game");
          $counter++;
        }
        if (!$game->isOwner($viewer)) {
          $menuoptions[$counter]['name'] = "report";
          $menuoptions[$counter]['label'] = $this->view->translate("Report Game");
        }
        $result['menus'] = $menuoptions;
      }


      $result['game']["share"]["name"] = "share";
      $result['game']["share"]["label"] = $this->view->translate("Share");
      $photo = $this->getBaseUrl(false,$game->getPhotoUrl());
      if($photo)
        $result['game']["share"]["imageUrl"] = $photo;
      $result['game']["share"]["url"] = $this->getBaseUrl(false,$game->getHref());
      $result['game']["share"]["title"] = $game->getTitle();
      $result['game']["share"]["description"] = strip_tags($game->getDescription());
      $result['game']["share"]['urlParams'] = array(
          "type" => $game->getType(),
          "id" => $game->getIdentity()
      );
  
      if(is_null($result['game']["share"]["title"]))
        unset($result['game']["share"]["title"]);
  
      $images = Engine_Api::_()->sesapi()->getPhotoUrls($game,'',"");
      if(!engine_count($images))
        $images['main'] = $this->getBaseUrl(true, $game->getPhotoUrl());
  
      $result['game']['game_images'] = $images;
  
      $result['game']['user_images'] = $this->userImage($game->owner_id);
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),array()));
  }

  public function searchFormAction() {

    $filterOptions = (array)$this->_getParam('search_type', array('recentlySPcreated' => 'Recently Created','mostSPplayed' => 'Most Played','mostSPliked' => 'Most Liked', 'mostSPcommented' => 'Most Commented'));
    $this->view->view_type = $this-> _getParam('view_type', 'horizontal');
	$default_search_type = $this-> _getParam('default_search_type', 'mostSPliked');
		
	 $searchForm = $this->view->searchForm = new Egames_Form_Search(array('searchTitle' => $this->_getParam('search_title', 'yes'),'browseBy' => $this->_getParam('browse_by', 'yes'),'categoriesSearch' => $this->_getParam('categories', 'yes'),'searchFor'=>$search_for,'FriendsSearch'=>$this->_getParam('friend_show', 'yes'),'defaultSearchtype'=>$default_search_type));
	
     if($this->_getParam('search_type','1') !== null && $this->_getParam('browse_by', 'yes') == 'yes'){
		$arrayOptions = $filterOptions;
		$filterOptions = array();
		foreach ($arrayOptions as $key=>$filterOption) {
            $value = str_replace(array('SP',''), array(' ',' '), $filterOption);
            $filterOptions[str_replace("SP",'_',$key)] = ucwords($value);
        }
		$filterOptions = array(''=>'')+$filterOptions;
		$searchForm->sort->setMultiOptions($filterOptions);
		$searchForm->sort->setValue($default_search_type);
	 }
     $searchForm->removeElement("loading-img-egames");
    $searchForm->populate($this->_getAllParams());
    $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($searchForm,true);
    $this->generateFormFields($formFields);
  }

  function playAction(){
    $game_id = $this->_getParam('game_id');
    if(!$game_id){
      echo 0;die;
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $game = Engine_Api::_()->getItem('egames_game', $this->_getParam('game_id'));
    $gameTable = Engine_Api::_()->getItemTable('egames_game');
    if($game && !$game->isOwner($viewer) ) {
      $gameTable->update(array(
          'play_count' => new Zend_Db_Expr('play_count + 1'),
      ), array(
          'game_id = ?' => $game->getIdentity(),
      ));
    }
    echo 1;die;
  }

  //get album categories ajax based.
  public function subcategoryAction() {
    $category_id = $this->_getParam('category_id', null);
    if ($category_id) {
			$subcategory = Engine_Api::_()->getDbtable('categories', 'egames')->getModuleSubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';

      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '" >' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;
    die;
  }
	// get album subsubcategory ajax based
  public function subsubcategoryAction() {

    $category_id = $this->_getParam('subcategory_id', null);
    if ($category_id) {
      $subcategory = Engine_Api::_()->getDbtable('categories', 'egames')->getModuleSubsubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';
      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '">' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;die;
  }

}
