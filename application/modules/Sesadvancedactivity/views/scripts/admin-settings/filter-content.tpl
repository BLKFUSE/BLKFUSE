<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: filter-content.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedactivity/views/scripts/dismiss_message.tpl';?>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
      </div>
    <?php endif; ?>
    <div class='settings sesbasic-form-cont'>
      <h3>Manage Filters</h3>
      <p>In this page you can manage various filters for displaying feeds on the member home page of your website. Here, in addition to the default filters, you can also create new filter of different modules.To create a new filter click on "Create New Filter" link. You can also enable, disable or edit any module.<br />To reorder the filters, click on their row and drag them up or down.</p><br />
      <div class="sesbasic_search_reasult"><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedactivity', 'controller' => 'settings', 'action' => 'create'), $this->translate("Create New Filter"), array('class'=>'buttonlink sesbasic_icon_add smoothbox')); ?>
      </div>
        <div class='clear'>
        <?php if(engine_count($this->paginator) > 0):?>
          <form method="post" action="">
            <div class="sesbasic_manage_table">
              <div class="sesbasic_manage_table_head" style="width:100%;">
                <div style="width:25%">
                  <?php echo "Module";?>
                </div>
                <div style="width:25%">
                <?php echo $this->translate("Title") ?>
                </div>
                <div style="width:25%" class="admin_table_centered">
                <?php echo $this->translate("Status") ?>
                </div>
                
                <div style="width:25%">
                <?php echo $this->translate("Options") ?>
                </div>
              </div>
              <ul class="sesbasic_manage_table_list" id='menu_list' style="width:100%;">
              <?php $notinclude = array('all', 'my_networks', 'my_friends', 'posts', 'saved_feeds', 'post_self_buysell', 'post_self_file', 'scheduled_post', "share"); ?>
              <?php foreach ($this->paginator as $item) : ?>
                <?php if(!engine_in_array($item->filtertype, $notinclude) && !Engine_Api::_()->sesbasic()->isModuleEnable($item->filtertype)) continue; ?>
                <li class="item_label" id="filter_<?php echo $item->getIdentity(); ?>">
                <input type="hidden" name="order[]" value="<?php echo $item->getIdentity(); ?>">
                  <div style="width:25%;">
                    <?php echo ucfirst($item->module); ?>
                  </div>
                  <div style="width:25%;">
                    <?php echo $item->title; ?>
                  </div>
                  <div style="width:25%;" class="admin_table_centered">
                    <?php echo ( $item->active ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedactivity', 'controller' => 'settings', 'action' => 'enabled', 'id' => $item->getIdentity()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Disable'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedactivity', 'controller' => 'settings', 'action' => 'enabled', 'id' => $item->getIdentity()), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Enable')))) ) ?>
                  </div>  
                  <div style="width:25%;">          
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedactivity', 'controller' => 'settings', 'action' => 'create','id'=>$item->getIdentity()), $this->translate("Edit"), array('class'=>'smoothbox'));
                    if($item->is_delete){
                    ?>
              |
              <?php echo $this->htmlLink(
                  array('route' => 'admin_default', 'module' => 'sesadvancedactivity', 'controller' => 'settings', 'action' => 'delete', 'id' => $item->getIdentity()),
                  $this->translate("Delete"),
                  array('class' => 'smoothbox'));
                  }
                  ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
              <div class='buttons'>
              <button type='submit'><?php echo $this->translate('Save Order'); ?></button>
            </div>
            </div>          
          </form>
        <?php endif;?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript"> 

var SortablesInstance = scriptJquery('#menu_list').sortable({
  stop: function( event, ui ) {
    var ids = [];
    scriptJquery('#menu_list > li').each(function(e) {
      var el = scriptJquery(this);

    });
  }
});


 
</script>
