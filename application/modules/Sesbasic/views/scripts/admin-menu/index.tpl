<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-07-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php include APPLICATION_PATH .  '/application/modules/'.ucfirst($this->moduleName).'/views/scripts/dismiss_message.tpl';?>

<?php if($this->moduleName == 'sesdating') { ?>
  <div class='tabs'>
    <ul class="navigation">
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesdating', 'controller' => 'manage', 'action' => 'header-settings'), $this->translate('Header Settings')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesdating', 'controller' => 'manage', 'action' => 'index'), $this->translate('Main Menu Icons')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesdating', 'controller' => 'manage', 'action' => 'mini-menu-icons'), $this->translate('Mini Menu icons')) ?>
      </li>
      <li class="active">
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesdating', 'controller' => 'menu'), $this->translate('Mini Menu')) ?>
      </li>
    </ul>
  </div>
<?php } ?>
<?php if($this->moduleName == 'sesatoz') { ?>
  <div class='tabs'>
    <ul class="navigation">
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesatoz', 'controller' => 'manage', 'action' => 'header-settings'), $this->translate('Header Settings')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesatoz', 'controller' => 'manage', 'action' => 'index'), $this->translate('Main Menu Icons')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesatoz', 'controller' => 'manage', 'action' => 'mini-menu-icons'), $this->translate('Mini Menu icons')) ?>
      </li>
      <li class="active">
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesatoz', 'controller' => 'menu'), $this->translate('Mini Menu')) ?>
      </li>
    </ul>
  </div>
<?php } ?>
<?php if($this->moduleName == 'sesmaterial') { ?>
  <div class='tabs'>
    <ul class="navigation">
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmaterial', 'controller' => 'manage', 'action' => 'header-template'), $this->translate('Header Settings')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmaterial', 'controller' => 'settings', 'action' => 'manage-search'), $this->translate('Manage Modules for Search')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmaterial', 'controller' => 'manage', 'action' => 'index'), $this->translate('Main Menu Icons')) ?>
      </li>
      <li class="active">
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmaterial', 'controller' => 'menu'), $this->translate('Mini Menu')) ?>
      </li>
    </ul>
  </div>
<?php } ?>
<?php if($this->moduleName == 'sesariana') { ?>
  <div class='tabs'>
    <ul class="navigation">
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesariana', 'controller' => 'manage', 'action' => 'header-settings'), $this->translate('Header Settings')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesariana', 'controller' => 'manage', 'action' => 'index'), $this->translate('Main Menu Icons')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesariana', 'controller' => 'manage', 'action' => 'mini-menu-icons'), $this->translate('Mini Menu icons')) ?>
      </li>
      <li class="active">
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesariana', 'controller' => 'menu'), $this->translate('Mini Menu')) ?>
      </li>
    </ul>
  </div>
<?php } ?>
<?php if($this->moduleName == 'sesytube') { ?>
  <div class='tabs'>
    <ul class="navigation">
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesytube', 'controller' => 'manage', 'action' => 'header-settings'), $this->translate('Header Settings')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesytube', 'controller' => 'manage', 'action' => 'index'), $this->translate('Main Menu Icons')) ?>
      </li>
      <li>
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesytube', 'controller' => 'manage', 'action' => 'mini-menu-icons'), $this->translate('Mini Menu icons')) ?>
      </li>
      <li class="active">
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesytube', 'controller' => 'menu'), $this->translate('Mini Menu')) ?>
      </li>
    </ul>
  </div>
<?php } ?>
<?php if($this->moduleName == 'sessportz') { ?>
<div class='tabs'>
  <ul class="navigation">
    <li>
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sessportz', 'controller' => 'manage', 'action' => 'header-template'), $this->translate('Header Settings')) ?>
    </li>
    <li>
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sessportz', 'controller' => 'settings', 'action' => 'manage-search'), $this->translate('Manage Modules for Search')) ?>
    </li>
    <li>
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sessportz', 'controller' => 'manage', 'action' => 'index'), $this->translate('Main Menu Icons')) ?>
    </li>
    <li class="active">
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sessportz', 'controller' => 'menu'), $this->translate('Mini Menu')) ?>
    </li>
  </ul>
</div>
<?php } ?>
<?php if($this->moduleName == 'sesadvancedheader') { ?>
<div class='tabs'>
  <ul class="navigation">
    <li>
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedheader', 'controller' => 'manage', 'action' => 'header-settings'), $this->translate('Header Settings')) ?>
    </li>
     <li>
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedheader', 'controller' => 'settings', 'action' => 'manage-search'), $this->translate('Manage Module for Search')) ?>
    </li>
    <li>
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedheader', 'controller' => 'manage', 'action' => 'index'), $this->translate('Main Menu Icons')) ?>
    </li>
    <li class="active">
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedheader', 'controller' => 'menu'), $this->translate('Mini Menu')) ?>
    </li>
  </ul>
</div>
<?php } ?>
<h3>
  <?php echo $this->translate('Mini Menu Editor') ?>
</h3>
<p>Here, you can edit the mini menu for this theme. The new menu item you are creating here, will also be created under the SocialEngine's core Mini Menu.<br />
If there is any menu item which is in SE's core Mini Menu, but not in this theme, then please click on the "Sink Menu" link below to sink and update the mini menu of this theme with the SE menu.<br />
You can also drag and drop menu items below to reorder them.</p><br />

<script type="text/javascript">

  var SortablesInstance;

  scriptJquery(document).ready(function() {
    scriptJquery('.item_label').mouseover(showPreview).mouseout(showPreview);
  });

  var showPreview = function(event) {
    try {
      element = scriptJquery(event.target);
      element = element.parents('.admin_menus_item').find('.item_url');
      if(event.type == 'mouseover' ) {
        element.css('display', 'block');
      } else if( event.type == 'mouseout' ) {
        element.css('display', 'none');
      }
    } catch( e ) {
      //alert(e);
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
<div class="admin_menus_options">
  <?php echo $this->htmlLink(array('reset' => false, 'action' => 'create', 'name' => @$this->selectedMenu->name), $this->translate('Add Item'), array('class' => 'buttonlink sesbasic_icon_add smoothbox')) ?>
  <?php if( @$this->selectedMenu->type == 'custom' ): ?>
    <?php echo $this->htmlLink(array('reset' => false, 'action' => 'delete-menu', 'name' => $this->selectedMenu->name), $this->translate('Delete Menu'), array('class' => 'buttonlink sesbasic_icon_delete smoothbox')) ?>
  <?php endif ?>
  <?php echo $this->htmlLink(array('reset' => false, 'action' => 'sink-menu', 'name' => @$this->selectedMenu->name), $this->translate('Sink Menu'), array('class' => 'buttonlink sesbasic_icon_sink smoothbox')) ?>
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
