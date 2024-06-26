<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _sespage_content.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedcomment/views/scripts/_jsFiles.tpl'; ?>
<style>
.feed_item_date ul{width:100%;}
.sespage_switcher_active{background-color:red;}
</style>
<?php $action = $this->action;
if(!$action ||  !$this->viewer()->getIdentity()) return; ?>
<?php $isPageSubject = empty($this->isPageSubject) ? $this->viewer() : $this->isPageSubject; ?>
<?php 
    $module = Engine_Api::_()->getDbTable('actionTypes','sesadvancedactivity')->getActionType($action->type);
    $moduleName = $module->module;
    if($moduleName != "sespage" && $action->object_type != "sespage_page"){
      return;
    }
?>
<?php 
      $subjectPage = $this->subject();
      if($subjectPage && empty($this->isPageSubject)){
        if(Engine_Api::_()->getDbTable('pageroles','sespage')->toCheckUserPageRole($this->viewer()->getIdentity(),$subjectPage->getIdentity(),'manage_dashboard','delete')){
          $attributionType = Engine_Api::_()->getDbTable('postattributions','sespage')->getPagePostAttribution(array('page_id' => $subjectPage->getIdentity()));        
          $pageAttributionType = Engine_Api::_()->authorization()->isAllowed('sespage_page', $this->viewer(), 'page_attribution');
          $allowUserChoosePageAttribution = Engine_Api::_()->authorization()->isAllowed('sespage_page', $this->viewer(), 'page_attribution_allowuser');
          if (!$pageAttributionType || $attributionType == 0) {
            $isPageSubject = $this->viewer();
          }
          if($pageAttributionType && !$allowUserChoosePageAttribution) {
            $isPageSubject = $this->viewer();
          }
          if($pageAttributionType && $allowUserChoosePageAttribution && $attributionType == 1) {
             $isPageSubject = $subjectPage;
          }
        }
      }
?>
  <?php $actionIdentity = is_array($action) ? $action->getIdentity() : 0?>
<li class="sespage_switcher_cnt sesact_owner_selector sesact_owner_selector_c">
  <a href="javascript:;" class="sespage_feed_change_option_a _st" data-subject="<?php echo !empty($isPageSubject) ? $isPageSubject->getGuid() : $this->viewer()->getGuid(); ?>" data-actionid="<?php echo $action->getIdentity(); ?>" data-rel="<?php echo $isPageSubject->getGuid(); ?>" data-src="<?php echo $isPageSubject->getPhotoUrl(); ?>">
    <img class="sespage_elem_cnt" src="<?php echo $isPageSubject->getPhotoUrl(); ?>" />
    <i class="fa fa-caret-down sespage_elem_cnt"></i>
  </a>
  <a href="javascript:;" class="sespage_feed_change_option _lin" style="left:0; top:0; height:100%; width:100%; position:absolute;"></a>
</li>
<script type="application/javascript">
en4.core.runonce.add(function() {
    if(typeof changePageCommentUser == "function"){
      changePageCommentUser(<?php echo $actionIdentity ?>);
    }
});
  
</script>
