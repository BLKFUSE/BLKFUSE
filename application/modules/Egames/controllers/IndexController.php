<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: IndexController.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_IndexController extends Core_Controller_Action_Standard
{
  public function init(){
    // only show to member_level if authorized
    if( !$this->_helper->requireAuth()->setAuthParams('egames_game', null, 'view')->isValid() ) return;
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
    
    // Render
    $this->_helper->content
        ->setNoRender()
        ->setEnabled()
    ;
    
  }

  public function deleteAction()
    {
        $viewer = Engine_Api::_()->user()->getViewer();
        $game = Engine_Api::_()->getItem('egames_game', $this->getRequest()->getParam('game_id'));
        if( !$this->_helper->requireAuth()->setAuthParams($game, null, 'delete')->isValid()) return;

        // In smoothbox
        $this->_helper->layout->setLayout('default-simple');
        
        $this->view->form = $form = new Egames_Form_Delete();

        if( !$game ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_("Game doesn't exist or not authorized to delete");
            return;
        }

        if( !$this->getRequest()->isPost() ) {
            $this->view->status = false;
            $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
            return;
        }

        $db = $game->getTable()->getAdapter();
        $db->beginTransaction();

        try {
            $game->delete();

            $db->commit();
        } catch( Exception $e ) {
            $db->rollBack();
            throw $e;
        }

        $this->view->status = true;
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('Your game has been deleted.');
        return $this->_forward('success' ,'utility', 'core', array(
            'parentRedirect' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'manage'), 'egames_general', true),
            'messages' => Array($this->view->message)
        ));
    }

    public function editAction()
    {
        
        if( !$this->_helper->requireUser()->isValid() ) return;

        $viewer = Engine_Api::_()->user()->getViewer();
        $this->view->game_id = $this->_getParam('game_id');
        $game = Engine_Api::_()->getItem('egames_game', $this->_getParam('game_id'));
        if( !Engine_Api::_()->core()->hasSubject('egames_game') ) {
            Engine_Api::_()->core()->setSubject($game);
        }

        if( !$this->_helper->requireSubject()->isValid() ) return;

        if( !$this->_helper->requireAuth()->setAuthParams($game, $viewer, 'edit')->isValid() ) {
            return;
        }
        // Render
        $this->_helper->content
        //->setNoRender()
        ->setEnabled()
        ;
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

        

        // Check post/form
        if( !$this->getRequest()->isPost() ) {
            return;
        }
        if( !$form->isValid($this->getRequest()->getPost()) ) {
            return;
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
            throw $e;
        }

        return $this->_helper->redirector->gotoRoute(array('game_id' => $game->game_id,"slug"=>Engine_String::slug($game->title, 64)), 'egames_profile', true);
      }

  public function createAction()
  {

    if( !$this->_helper->requireUser()->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams('egames_game', null, 'create')->isValid()) return;

    // Render
    $this->_helper->content
        //->setNoRender()
        ->setEnabled()
    ;
    
    // set up data needed to check quota
    $viewer = Engine_Api::_()->user()->getViewer();
    $values['owner_id'] = $viewer->getIdentity();
    $paginator = Engine_Api::_()->getItemTable('egames_game')->getGamesPaginator($values);
    

    $this->view->quota = $quota = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'egames_game', 'max_games');
    $this->view->current_count = $paginator->getTotalItemCount();

    // Prepare form
    $this->view->form = $form = new Egames_Form_Create();

    // If not post or form not valid, return
    if( !$this->getRequest()->isPost() ) {
        return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
        return;
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
        return $this->exceptionWrapper($e, $form, $db);
    }
    return $this->_helper->redirector->gotoRoute(array('game_id' => $game->game_id,"slug"=>Engine_String::slug($game->title, 64)), 'egames_profile', true);
  }
  public function manageAction()
  {
    
    // Render
    $this->_helper->content
        ->setNoRender()
        ->setEnabled()
    ;

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
        return;
    }
    if( !$this->_helper->requireAuth()->setAuthParams($game, $viewer, 'view')->isValid() ) {
        return;
    }


    $gameTable = Engine_Api::_()->getItemTable('egames_game');

    if( !$game->isOwner($viewer) ) {
      $gameTable->update(array(
          'view_count' => new Zend_Db_Expr('view_count + 1'),
      ), array(
          'game_id = ?' => $game->getIdentity(),
      ));
    }

    
    // Render
    $this->_helper->content
        ->setNoRender()
        ->setEnabled()
    ;

    

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
