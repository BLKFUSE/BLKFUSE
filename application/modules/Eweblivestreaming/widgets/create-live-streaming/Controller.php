<?php

class Eweblivestreaming_Widget_CreateLiveStreamingController extends Engine_Content_Widget_Abstract {
  public function indexAction() {
    $this->view->viewer = Engine_Api::_()->user()->getViewer();
    
    if(!$this->view->viewer->getIdentity()){
      return $this->setNoRender();
    }

    $this->view->permissions = Engine_Api::_()->elivestreaming()->getPermission(false);
    if(!$this->view->permissions){
      return $this->setNoRender();
    }
  }
}