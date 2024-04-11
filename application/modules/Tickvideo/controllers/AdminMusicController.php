<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AdminMusicController.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Tickvideo_AdminMusicController extends Core_Controller_Action_Admin
{

    public function categoriesAction()
    {
        // Admin navigation Menus
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('tickvideo_admin_main', array(), 'tickvideo_admin_main_categories');
        $page = $this->_getParam('page', 1);
        // galleries's paginator
        $this->view->paginator = Engine_Api::_()->getDbtable('categories', 'tickvideo')->getPaginator(array(
            'orderby' => 'category_id',
        ));
        // if request is post check
        if ($this->getRequest()->isPost()) {
            // get adapter
            $db = Engine_Db_Table::getDefaultAdapter();
            // get values from request
            $values = $this->getRequest()->getPost();
            $table = Engine_Api::_()->getDbTable("musics",'tickvideo');
            foreach ($values as $key => $value) {
                if ($key == 'delete_' . $value) {
                    // delete gallery
                    Engine_Api::_()->getItem('tickvideo_category', $value)->delete();
                    foreach($table->fetchAll($table->select()->where("category_id =?",$value)) as $music){
                        Engine_Api::_()->getItem('tickvideo_music', $music->getIdentity())->delete();
                    }
                }
            }
        }
        $this->view->paginator->setItemCountPerPage(20);
        $this->view->paginator->setCurrentPageNumber($page);
    }

    public function createCategoryAction()
    {
        $this->_helper->layout->setLayout('admin-simple');
        // get ID in case of Edit Gallery
        $id = $this->_getParam('id', 0);
        // gallery form
        $this->view->form = $form = new Tickvideo_Form_Admin_Category();
        if ($id) {
            $form->setTitle("Edit Category Title");
            $form->submit->setLabel('Save Changes');
            $category = Engine_Api::_()->getItem('tickvideo_category', $id);
            // populate form in case of edit
            $form->populate($category->toArray());
        }
        // check for request type
        if ($this->getRequest()->isPost()) {
            // check for form validation
            if (!$form->isValid($this->getRequest()->getPost()))
                return;
            $db = Engine_Api::_()->getDbtable('categories', 'tickvideo')->getAdapter();
            $db->beginTransaction();
            try {
                $table = Engine_Api::_()->getDbtable('categories', 'tickvideo');
                // get values from form
                $values = $form->getValues();
                if (!$id)
                    $category = $table->createRow();
                $category->setFromArray($values);
                $category->save();
                $db->commit();
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
            // success message
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('Category created successfully.')
            ));
        }
    }

    public function deleteCategoryAction()
    {
        $this->_helper->layout->setLayout('admin-simple');
        // get delete form
        $this->view->form = $form = new Sesbasic_Form_Admin_Delete();
        $form->setTitle('Delete This Category?');
        $form->setDescription('Are you sure that you want to delete this Category ? It will not be recoverable after being deleted.');
        $form->submit->setLabel('Delete');
        $id = $this->_getParam('id');
        $this->view->item_id = $id;
        // Check post
        if ($this->getRequest()->isPost()) {
            $chanel = Engine_Api::_()->getItem('tickvideo_category', $id)->delete();
            $db = Engine_Db_Table::getDefaultAdapter();
            $table = Engine_Api::_()->getDbTable("musics",'tickvideo');
            foreach($table->fetchAll($table->select()->where("category_id =?",$id)) as $music){
                Engine_Api::_()->getItem('tickvideo_music', $music->getIdentity())->delete();
            }
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('Category Delete Successfully.')
            ));
        }
        // Output
        $this->renderScript('admin-music/delete-category.tpl');
    }

    public function manageAction()
    {
        if ($this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPost();
            foreach ($values as $key => $value) {
                if ($key == 'delete_' . $value) {
                    $slide = Engine_Api::_()->getItem('tickvideo_music', $value);
                    if(!$slide)
                        continue;
                    if ($slide->file_id) {
                        $item = Engine_Api::_()->getItem('storage_file', $slide->file_id);
                        if ($item->storage_path) {
                            @unlink($item->storage_path);
                            $item->remove();
                        }
                    }
                    if ($slide->photo_id) {
                        $item = Engine_Api::_()->getItem('storage_file', $slide->photo_id);
                        if ($item->storage_path) {
                            @unlink($item->storage_path);
                            $item->remove();
                        }
                    }
                    if($slide){
                        $category = Engine_Api::_()->getItem('tickvideo_category', $slide->category_id);
                        if($category){
                            $category->item_count = $category->item_count - 1;
                            $category->save();
                        }
                    }
                    $slide->delete();
                }
            }
        }
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('tickvideo_admin_main', array(), 'tickvideo_admin_main_categories');
        $this->view->category_id = $id = $this->_getParam('id');
        if (!$id)
            return;
        $category = Engine_Api::_()->getItem('tickvideo_category', $id);
        if($category){
            $this->view->category = $category;
        }
        $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('musics', 'tickvideo')->getPaginator(array('category_id'=>$id), 'show_all');
        $page = $this->_getParam('page', 1);
        $paginator->setItemCountPerPage(1000);
        $paginator->setCurrentPageNumber($page);
    }

    public function createMusicAction()
    {
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('tickvideo_admin_main', array(), 'tickvideo_admin_main_categories');
        $this->view->category_id = $id = $this->_getParam('id');
        $this->view->music_id = $music_id = $this->_getParam('music_id', false);
        if (!$id)
            return;
        $category = Engine_Api::_()->getItem('tickvideo_category',$id);
        $this->view->form = $form = new Tickvideo_Form_Admin_Createmusic();
        if ($music_id) {
            //$form->setTitle("Edit HTML5 Video Background");
            $form->setTitle("Edit Music");
            $form->setDescription("Below, edit the details for the Music.");
            $music = Engine_Api::_()->getItem('tickvideo_music', $music_id);
            $values = $music->toArray();

            $form->populate($values);
        }
        if ($this->getRequest()->isPost()) {
            if (!$form->isValid($this->getRequest()->getPost()))
                return;



            $db = Engine_Api::_()->getDbtable('musics', 'tickvideo')->getAdapter();
            $db->beginTransaction();
            try {
                $table = Engine_Api::_()->getDbtable('musics', 'tickvideo');
                $values = $form->getValues();

                if (!isset($music)) {
                    $music = $table->createRow();
                    $category->item_count = $category->item_count + 1;
                    $category->save();
                }
                $music->setFromArray($values);
                $music->save();
                $music->owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
                if (isset($_FILES['upload']['name']) && $_FILES['upload']['name'] != '') {
                    // Store video in temporary storage object for ffmpeg to handle
                    $storage = Engine_Api::_()->getItemTable('storage_file');
                    $filename = $storage->createFile($form->upload, array(
                        'parent_id' => $music->music_id,
                        'parent_type' => 'tickvideo_music',
                        'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
                    ));
                    // Remove temporary file
                    @unlink($_FILES['upload']['tmp_name']);
                    $music->file_id = $filename->file_id;

                    if($filename->service_id != 1){
                        $filepath = $filename->map();
                    }else {
                        $filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR .$filename->storage_path;
                    }

                    if(preg_match('/[^?#]+\.(?:wma|mp3|wav|mp4)/', strtolower($filepath))){
                        $ffmpegpath = Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path;
                        // execute ffmpeg form linux shell and grab duration from output
                        $result = shell_exec($ffmpegpath." -i ".$filepath.' 2>&1 | grep -o \'Duration: [0-9:.]*\'');
                        $duration = str_replace('Duration: ', '', $result); // 00:05:03.25

                        //get the duration in seconds
                        $timeArr = preg_split('/:/', str_replace('s', '', $duration));
                        $t =  (($timeArr[3])? $timeArr[3]*1 + $timeArr[2] * 60 + $timeArr[1] * 60 * 60 : $timeArr[2] + $timeArr[1] * 60);
                        $music->duration = (int) $t;
                    }

                }
                if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                    // Store video in temporary storage object for ffmpeg to handle
                    $storage = Engine_Api::_()->getItemTable('storage_file');
                    $thumbname = $storage->createFile($form->image, array(
                        'parent_id' => $music->music_id,
                        'parent_type' => 'tickvideo_music',
                        'user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(),
                    ));
                    // Remove temporary file
                    @unlink($_FILES['image']['tmp_name']);
                    $music->photo_id = $thumbname->file_id;
                }

                $music->category_id = $id;
                $music->save();
                $db->commit();

                    return $this->_helper->redirector->gotoRoute(array('module' => 'tickvideo', 'controller' => 'music', 'action' => 'manage', 'id' => $id), 'admin_default', true);
            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            }
        }
    }

    public function deleteMusicAction()
    {
        // In smoothbox
        $this->_helper->layout->setLayout('admin-simple');
        $id = $this->_getParam('id');
        $this->view->item_id = $id;
        // Check post
        if ($this->getRequest()->isPost()) {
            $slide = Engine_Api::_()->getItem('tickvideo_music', $id);
            $category = Engine_Api::_()->getItem('tickvideo_category',$slide->category_id);
            if ($slide->photo_id) {
                $item = Engine_Api::_()->getItem('storage_file', $slide->photo_id);
                if ($item->storage_path) {
                    @unlink($item->storage_path);
                    $item->remove();
                }
            }
            if ($slide->file_id) {
                $item = Engine_Api::_()->getItem('storage_file', $slide->file_id);
                if ($item->storage_path) {
                    @unlink($item->storage_path);
                    $item->remove();
                }
            }

            $slide->delete();
            $category->item_count = $category->item_count - 1;
            $category->save();
            $this->_forward('success', 'utility', 'core', array(
                'smoothboxClose' => 10,
                'parentRefresh' => 10,
                'messages' => array('Music Delete Successfully.')
            ));
        }
        // Output
        $this->renderScript('admin-music/delete-music.tpl');
    }

    public function enabledAction()
    {
        $id = $this->_getParam('category_id', 0);
        if (!empty($id)) {

                $item = Engine_Api::_()->getItem('tickvideo_category', $id);
            $item->status = !$item->status;
            $item->save();
        }
        $this->_redirect('admin/tickvideo/music/categories');

    }

    public function uploadMusicAction(){
        if(!empty($_FILES["Filedata"]["name"])) {
            $file = $_FILES["Filedata"];
            $filename = $file["name"];
            $tmp_name = $file["tmp_name"];
            $type = $file["type"];

            $name = explode(".", $filename);
            $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');

            if(engine_in_array($type,$accepted_types)) { //If it is Zipped/compressed File
              $okay = true;
            }

            $continue = strtolower($name[1]) == 'zip' ? true : false; //Checking the file Extension

            if(!$continue) {
                $this->view->status = false;
                $this->view->error =  Zend_Registry::get('Zend_Translate')->_("The file you are trying to upload is not a .zip file. Please try again.");
              return;
            }
            $this->view->category_id = $id = $this->_getParam('id');
            if (!$id)
                return;
            $category = Engine_Api::_()->getItem('tickvideo_category',$id);

            /* here it is really happening */
            $ran = $name[0]."-".time()."-".rand(1,time());
            $dir = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'temporary/';
            $targetdir = $dir.$ran;
            $targetzip = $dir.$ran.".zip";

            if(move_uploaded_file($tmp_name, $targetzip)) { 
              $zip = new ZipArchive();
              $x = $zip->open($targetzip);  // open the zip file to extract
              if ($x === true) {
                  $zip->extractTo($targetdir);
                  $zip->close();
                  @unlink($targetzip); 
                  chmod($targetdir, 0777);                  
                  $this->createMusicEntry($targetdir.DIRECTORY_SEPARATOR.$name[0],$category);
                  @unlink($targetdir);
                return;
              }
            } else {
                $this->view->status = false;
                $this->view->error =  Zend_Registry::get('Zend_Translate')->_("There was a problem with the upload. Please try again.");
                return;
            }
        }
        $this->view->status = false;
        $this->view->error =  Zend_Registry::get('Zend_Translate')->_("There was a problem with the upload. Please try again.");
    }
    public function createMusicEntry($targetdir,$category){
        if(!file_exists($targetdir.DIRECTORY_SEPARATOR.'details.csv')){
            $this->view->status = false;
            $this->view->error =  Zend_Registry::get('Zend_Translate')->_("The file you are trying to upload is not contain a details.csv file. Please try again.");
            return;
        }
        $dir = DIRECTORY_SEPARATOR . 'public'. DIRECTORY_SEPARATOR .'tickvideo_music'. DIRECTORY_SEPARATOR.'tick_music'. DIRECTORY_SEPARATOR;
        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        $storageType = Engine_Api::_()->getDbtable('serviceTypes', 'storage');
        $select = $storageType->select()->from($storageType->info("name"),array('servicetype_id'))->where("enabled = 1");
        $servicetypes = $storageType->fetchAll($select);
        $enabledServiceIds = array();
        foreach ($servicetypes as $key => $value) {
            $enabledServiceIds[] = $value->servicetype_id;
        }
        $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
        $file = fopen($targetdir.DIRECTORY_SEPARATOR.'details.csv', 'r');
        $db = Engine_Api::_()->getDbtable('musics', 'tickvideo')->getAdapter();
        $storage = Engine_Api::_()->getItemTable('storage_file');
        
        $db->beginTransaction();
        $table = Engine_Api::_()->getDbtable('musics', 'tickvideo');
        while (($result = fgetcsv($file)) !== false)
        {                   
            $values = array();
            list($values['title'],$values['description']) = $result;
            $musicPath = $targetdir.DIRECTORY_SEPARATOR.'musics'.DIRECTORY_SEPARATOR.$result[4];
            $musicThumb = $targetdir.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.$result[2];
            try {
                $music = $table->createRow();
                $category->item_count = $category->item_count + 1;
                $category->save();
                $music->setFromArray($values);
                $music->save();
                $music->owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();

                // Store video in temporary storage object for ffmpeg to handle
                if($result[5]== 0 && file_exists($musicPath)){
                    $filename = $storage->createFile($musicPath, array(
                        'parent_id' => $music->music_id,
                        'parent_type' => 'tickvideo_music',
                        'user_id' =>$viewerId,
                    ));
                   
                    $music->file_id = $filename->file_id;

                    if($filename->service_id == 2){
                        $filepath = $filename->map();
                    }else {
                        $filepath = APPLICATION_PATH . DIRECTORY_SEPARATOR .$filename->storage_path;
                    }
                    if(preg_match('/[^?#]+\.(?:wma|mp3|wav|mp4)/', strtolower($filepath))){
                        $ffmpegpath = Engine_Api::_()->getApi('settings', 'core')->video_ffmpeg_path;
                        // execute ffmpeg form linux shell and grab duration from output
                        $data = shell_exec($ffmpegpath." -i ".$filepath.' 2>&1 | grep -o \'Duration: [0-9:.]*\'');
                        $duration = str_replace('Duration: ', '', $data); // 00:05:03.25

                        //get the duration in seconds
                        $timeArr = preg_split('/:/', str_replace('s', '', $duration));
                        $t =  (($timeArr[3])? $timeArr[3]*1 + $timeArr[2] * 60 + $timeArr[1] * 60 * 60 : $timeArr[2] + $timeArr[1] * 60);
                        $music->duration = (int) $t;
                    }
                } elseif($result[5] != 0 && engine_in_array($result[5], $enabledServiceIds)) {
                    $storageFile = $storage->createRow();
                    $musicPath = $dir.'musics'. DIRECTORY_SEPARATOR.$result[4];
                    $info = array();
                    $extension = ltrim(strrchr($result[4], '.'), '.');
                    $info['mime_major'] = 'audio';
                    $info['mime_minor'] = $extension;
                    $info['hash'] = md5_file($result[4]);
                    $info['creation_date'] = date('Y-m-d H:i:s');
                    $info['modified_date'] = date('Y-m-d H:i:s');
                    $info['extension'] = $extension;
                    $info['storage_path'] = $musicPath;
                    $info['service_id'] = $result[5];
                    $info['name'] = $result[4];
                    $info['parent_id'] = $music->music_id;
                    $info['parent_type'] = 'tickvideo_music';
                    $info['user_id'] = $viewerId;
                    $storageFile->setFromArray($info);
                    $storageFile->save();
                    $music->duration = $result[6] ?? 0;
                    $music->file_id = $storageFile->file_id;
                    $music->save();
                }
                if ($result[3] == 0 && file_exists($musicThumb)) {
                    // Store video in temporary storage object for ffmpeg to handle
                    $storage = Engine_Api::_()->getItemTable('storage_file');
                    $thumbname = $storage->createFile($musicThumb, array(
                        'parent_id' => $music->music_id,
                        'parent_type' => 'tickvideo_music',
                        'user_id' => $viewerId,
                    ));
                    // Remove temporary file
                    $music->photo_id = $thumbname->file_id;
                } elseif($result[3] != 0 && engine_in_array($result[3], $enabledServiceIds)) {
                    $storageFile = $storage->createRow();
                    $musicThumb = $dir.'images'. DIRECTORY_SEPARATOR.$result[2];
                    $info = array();
                    $extension = ltrim(strrchr($result[2], '.'), '.');
                    $info['mime_major'] = 'image';
                    $info['mime_minor'] = $extension;
                    $info['hash'] = md5_file($result[2]);
                    $info['creation_date'] = date('Y-m-d H:i:s');
                    $info['modified_date'] = date('Y-m-d H:i:s');
                    $info['extension'] = $extension;
                    $info['storage_path'] = $musicThumb;
                    $info['service_id'] = $result[3];
                    $info['name'] = $result[2];
                    $info['parent_id'] = $music->music_id;
                    $info['parent_type'] = 'tickvideo_music';
                    $info['user_id'] = $viewerId;
                    $storageFile->setFromArray($info);
                    $storageFile->save();
                    $music->photo_id = $storageFile->file_id;
                    $music->save();
                }
                $music->category_id = $category->getIdentity();
                $music->save();
                $db->commit();
                $this->view->status = true;
            } catch (Exception $e) {
                $db->rollBack();
                $this->view->status = true;
                $this->view->error = $e->getMessage();
            }
        }
    }
    public function orderAction()
    {
        if (!$this->getRequest()->isPost())
            return;
        $slidesTable = Engine_Api::_()->getDbtable('slides', 'sesdbslide');
        $slides = $slidesTable->fetchAll($slidesTable->select());
        foreach ($slides as $slide) {
            $order = $this->getRequest()->getParam('slide_' . $slide->slide_id);
            if (!$order)
                $order = 999;
            $slide->order = $order;
            $slide->save();
        }
        return;
    }
    
    function faqAction(){
        $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('tickvideo_admin_main', array(), 'tickvideo_admin_main_categories');
        $this->view->id = $this->_getParam('id', 0);
    }
    function downloadTemplateAction()
    {
        $archive_file_name = APPLICATION_PATH_MOD . DIRECTORY_SEPARATOR . 'Tickvideo'.DIRECTORY_SEPARATOR.'externals'.DIRECTORY_SEPARATOR.'music.zip';
        
        //then send the headers to force download the zip file
        header("Content-type: application/zip"); 
        header("Content-Disposition: attachment; filename=music.zip"); 
        header("Pragma: no-cache"); 
        header("Expires: 0"); 
        readfile("$archive_file_name");
        exit;
    }

}
