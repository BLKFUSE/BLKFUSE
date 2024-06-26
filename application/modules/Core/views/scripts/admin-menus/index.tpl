<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9924 2013-02-16 02:16:02Z alex $
 * @author     John
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenu' => "core_admin_main_layout", 'childMenuItemName' => 'core_admin_main_layout_menus')); ?>

<script type="text/javascript">

  var SortablesInstance;

  scriptJquery(document).ready(function() {
    scriptJquery('.item_label').mouseover(showPreview).mouseout(showPreview);
  });

  var showPreview = function(event) {
    try {
      element = scriptJquery(event.target);
      element = element.parents('.admin_menus_item').find('.item_url');

      if(element.find('a').attr('href') && event.type == 'mouseover' && element.find('a').attr('href') != 'javascript:void(0)') {
        element.css('display', 'block');
      } else if( event.type == 'mouseout' ) {
        element.css('display', 'none');
      }
    } catch( e ) {
    }
  }


  window.addEventListener('load', function() {
    SortablesInstance = scriptJquery('#menu_list').sortable({
      helper: "clone",
      handle: '.item_label',
      stop: function( event, ui ) {
        reorder(event);
      }
    });
  });

 var reorder = function(e) {
     var menuitems = e.target.childNodes;
     var ordering = {};
     var i = 1;
     for (var menuitem in menuitems)
     {
       var child_id = menuitems[menuitem].id;

       if ((child_id != undefined) && (child_id.substr(0, 5) == 'admin'))
       {
         ordering[child_id] = i;
         i++;
       }
     }
    ordering['menu'] = '<?php echo $this->selectedMenu->name;?>';
    ordering['format'] = 'json';

    // Send request
    var url = '<?php echo $this->url(array('action' => 'order')) ?>';
    scriptJquery.ajax({
      url : url,
      dataType: 'json',
      method : 'POST',
      data : ordering,
      success : function(responseJSON) {
      }
    });
  }

  function ignoreDrag()
  {
    event.stopPropagation();
    return false;
  }

</script>
<div class="admin_common_top_section">
  <h2 class="page_heading"><?php echo $this->translate('Menu Editor') ?></h2>
  <p>
    <?php echo $this->translate('CORE_VIEWS_SCRIPTS_ADMINMENU_INDEX_DESCRIPTION') ?>
    <?php
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if( $settings->getSetting('user.support.links', 0) == 1 ) {
            echo 'More info: <a href="https://community.socialengine.com/blogs/597/66/menu-editor" target="_blank">See KB article</a>.';
      if( Zend_Controller_Front::getInstance()->getRequest()->getParam('name') == "core_social_sites" ) {
        echo '<br> For more info on how to change the social footer menu icons, read this <a href="https://community.socialengine.com/blogs/597/68/social-footer-menu-icons" target="_blank">KB article</a>';
      }
    } 
    ?>	
  </p>
</div>
<div class="admin_menus_filter">
  <form action="<?php echo $this->url() ?>" method="get">
    <b><?php echo $this->translate("Editing:") ?></b>
    <?php echo $this->formSelect('name', $this->selectedMenu->name, array('onchange' => 'scriptJquery(this).closest(\'form\').trigger("submit");'), $this->menuList) ?>
  </form>
</div>

<div class="admin_menus_options">
  <?php echo $this->htmlLink(array('reset' => false, 'action' => 'create', 'name' => $this->selectedMenu->name), $this->translate('Add Item'), array('class' => 'admin_link_btn admin_menus_additem smoothbox')) ?>
  <?php echo $this->htmlLink(array('reset' => false, 'action' => 'create-menu'), $this->translate('Add Menu'), array('class' => 'admin_link_btn admin_menus_addmenu smoothbox')) ?>
  <?php if( $this->selectedMenu->type == 'custom' ): ?>
    <?php echo $this->htmlLink(array('reset' => false, 'action' => 'delete-menu', 'name' => $this->selectedMenu->name), $this->translate('Delete Menu'), array('class' => 'admin_link_btn admin_menus_deletemenu smoothbox')) ?>
  <?php endif ?>
</div>
<ul class="admin_menus_items" id='menu_list'>
  <?php foreach( $this->menuItems as $menuItem ): ?>
    <li class="admin_menus_item<?php if( isset($menuItem->enabled) && !$menuItem->enabled ) echo ' disabled' ?>" id="admin_menus_item_<?php echo $menuItem->name ?>">
      <span class="item_wrapper">
        <span class="item_options">
          <?php echo $this->htmlLink(array('reset' => false, 'action' => 'edit', 'name' => $menuItem->name), $this->translate('edit'), array('class' => 'smoothbox')) ?>
          <?php if($this->name == "core_main" && empty($this->subMenuDisabled[$menuItem->name])){ ?>
          <?php if(!empty($this->subMenus[$menuItem->name]['count'])){ ?>
            | <?php echo $this->htmlLink($this->url(array('action' => 'index','module'=>"core",'controller'=>'menus'),'admin_default',true).'?name='.$this->subMenus[$menuItem->name]['name'], $this->translate('Submenu'), array('class' => '')) ?>
         <?php } else{?>
            | <?php echo $this->htmlLink(array('reset' => false, 'action' => 'submenu', 'name' => $this->subMenus[$menuItem->name]['name']), $this->translate('Add Submenu'), array('class' => 'smoothbox')) ?>
          <?php }
          }
           ?>
          <?php if( $menuItem->custom && strpos($menuItem->name, 'custom_') === 0 ): ?>
            | <?php echo $this->htmlLink(array('reset' => false, 'action' => 'delete', 'name' => $menuItem->name), $this->translate('delete'), array('class' => 'smoothbox')) ?>
          <?php endif; ?>
        </span>
        <span class="item_label">
          <?php echo $this->translate($menuItem->label) ?>
        </span>
        <span class="item_url">
          <?php
            $href = '';
            if( isset($menuItem->params['uri']) ) {
              echo $this->htmlLink($menuItem->params['uri'], $menuItem->params['uri']);
            } else if( !empty($menuItem->plugin) ) {
              echo '<a>(' . $this->translate('variable') . ')</a>';
            } else {
              echo $this->htmlLink($this->htmlLink()->url($menuItem->params), $this->htmlLink()->url($menuItem->params));
            }
          ?>
        </span>
      </span>
    </li>
  <?php endforeach; ?>
</ul>
<script type="application/javascript">
  scriptJquery('.core_admin_main_layout').parent().addClass('active');
  scriptJquery('.core_admin_main_layout_menus').addClass('active');
</script>
