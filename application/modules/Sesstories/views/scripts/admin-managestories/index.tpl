<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesstories/views/scripts/dismiss_message.tpl';?>
<script type="text/javascript">

  var SortablesInstance;

  scriptJquery(document).ready(function() {
    scriptJquery('#menu_list').addClass('sortable');
    var SortablesInstance = scriptJquery('#menu_list').sortable({
      stop: function( event, ui ) {
        var ids = [];
        scriptJquery('#menu_list > li').each(function(e) {
          var el = scriptJquery(this);
          ids.push(el.attr('id'));
        });
        // Send request
        var url = '<?php echo $this->url(array('action' => 'order')) ?>';
        scriptJquery.ajax({
            url : url,
            dataType : 'json',
            method:"post",
            data : {
                format : 'json',
                order : ids
            }
        });
      }
    });
  });

  
 var reorder = function(e) {
     var menuitems = e.parentNode.childNodes;
     var ordering = {};
     var i = 1;
     for (var menuitem in menuitems)
     {
       var child_id = menuitems[menuitem].id;

       if ((child_id != undefined))
       {
         ordering[child_id] = i;
         i++;
       }
     }
    ordering['format'] = 'json';

    // Send request
    var url = '<?php echo $this->url(array('action' => 'order')) ?>';
    var request = scriptJquery.ajax({
      'url' : url,
      'method' : 'POST',
      'data' : ordering,
      success : function(responseJSON) {
      }
    });

    
  }
</script>

<script type="text/javascript">
function multiDelete()
{
  return confirm("<?php echo $this->translate("Are you sure you want to delete the selected backgrounds?") ?>");
}
  function selectAll() {
    var i;
    var multidelete_form = document.getElementById('multidelete_form');
    var inputs = multidelete_form.elements;
    for (i = 1; i < inputs.length; i++) {
      if (!inputs[i].disabled) {
        inputs[i].checked = inputs[0].checked;
      }
    }
  }
</script>
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()"> 
<?php endif; ?>
  <div>
    <h3><?php echo "Manage Background Images for Text Stories"; ?></h3>
    <p><?php echo $this->translate("This page lists all the background images uploaded by you. You can add new background images individually or in zip folder for multiple images. To reorder the background images, click on and drag them up or down.<br />") ?></p>
    <br />
    <div>
    <div class="sesbasic_search_reasult">
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesstories', 'controller' => 'managestories', 'action' => 'create'), $this->translate("<i style='vertical-align:middle' class='fa fa-plus'></i> Upload Image"), array('class'=>'sesbasic_button smoothbox')); ?>
      
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesstories', 'controller' => 'managestories', 'action' => 'upload-zip-file'), $this->translate("<i style='vertical-align:middle' class='fa fa-plus'></i> Upload Zipped Folder"), array('class'=>'sesbasic_button smoothbox')); ?>
    </div>
  </div>
  <?php if(engine_count($this->paginator) > 0):?>
    <div class="sesstories_select_all">
      <input onclick="selectAll()" type='checkbox' class='checkbox'> Select All
    </div>
  <?php endif; ?>
  <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
    <div class="sesbasic_search_reasult">
      <?php echo $this->translate(array('%s background image found.', '%s background images found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
    </div>
  <?php endif; ?>
  <?php if(engine_count($this->paginator) > 0):?>
    <div class="clear">
      <ul class="sesstories_packs_list" id='menu_list'>
        <?php foreach ($this->paginator as $item) : ?>
          <li class="item_label" id="managebackgrounds_<?php echo $item->background_id ?>">
          	<div class="sesstories_packs_item">
              <div class="sesstories_packs_list_input">
                <input type='checkbox' class='checkbox' name='delete_<?php echo $item->background_id;?>' value='<?php echo $item->background_id ?>' />
              </div>
              <div class="sesstories_packs_list_options">
                <?php echo ($item->enabled ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesstories', 'controller' => 'managestories', 'action' => 'enabled', 'background_id' => $this->background_id, 'id' => $item->background_id), '', array('title' => $this->translate('Disable'), 'class' => 'fa sesstories_icon_enabled')) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesstories', 'controller' => 'managestories', 'action' => 'enabled', 'background_id' => $this->background_id, 'id' => $item->background_id), '', array('title' => $this->translate('Enable'), 'class' => 'fa sesstories_icon_disabled'))) ?>&nbsp;
                
                <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesstories', 'controller' => 'managestories', 'action' => 'create', 'id'=>$item->background_id), '', array('class' => 'smoothbox fa fa-edit', 'title' => $this->translate('Edit'))) ?>&nbsp;

                <?php echo $this->htmlLink(
                array('route' => 'admin_default', 'module' => 'sesstories', 'controller' => 'managestories', 'action' => 'delete', 'id' => $item->background_id), '', array('class' => 'smoothbox fa sesstories_icon_delete', 'title' => $this->translate('Delete'))) ?>
              </div>
              <div class="sesstories_packs_list_img">
                <?php $photo = Engine_Api::_()->storage()->get($item->file_id, ''); ?>
                <?php if($photo) { ?>
                  <?php $photo = $photo->map(); ?>
                  <img alt="" src="<?php echo $photo; ?>" />
                <?php } ?>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class='buttons'>
        <button type='submit'><?php echo $this->translate('Delete Selected'); ?></button>
      </div>
    </div>
  <?php else:?>
    <div class="tip">
      <span>
	<?php echo "There are no images added by you.";?>
      </span>
    </div>
  <?php endif;?>
  </div>
<br />
</form>
<br />
<div>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>
