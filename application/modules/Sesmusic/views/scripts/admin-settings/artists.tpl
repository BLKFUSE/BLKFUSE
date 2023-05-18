<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: artists.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $baseURL = $this->layout()->staticBaseUrl; ?>
<script type="text/javascript"> 
  
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
            data : {
                format : 'json',
                order : ids
            }
        });
      }
    });
  });
 
function selectAll()
{
  var i;
  var multidelete_form = document.getElementById('multidelete_form');
  var inputs = multidelete_form.elements;
  for (i = 1; i < inputs.length - 1; i++) {
    inputs[i].checked = inputs[0].checked;
  }
}
  
  function multiDelete(){
    return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete selected artist?")) ?>');
  }
</script>

<?php include APPLICATION_PATH .  '/application/modules/Sesmusic/views/scripts/dismiss_message.tpl';?>

<div class='clear'>
  <div class="sesmusic_manage_artist_form settings">
    <form id='multidelete_form' method="post" action="<?php echo $this->url(array('action' => 'multi-delete-artists'));?>" onSubmit="return multiDelete()">
      <div>
        <h3><?php echo "Manage Artists"; ?></h3>
        <p><?php echo 'Here, you can add artists to your website by using the "Add New Artist" button below. You can also add about Artist and photo for any artists. <br /> Below, you can also choose any number of artists as Artist of the Day. These artists will be displyed randomly in the "Album / Song / Artist of the Day" widget.'; ?> </p>
        <br />
        <div>
          <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmusic', 'controller' => 'settings', 'action' => 'add-artist'), $this->translate('Add a New Artist'), array('class' => 'buttonlink smoothbox sesbasic_icon_add')); ?>
        </div><br />
        <?php if(engine_count($this->paginator) > 0):?>
          <div class="sesmusic_manage_artist_form_head">
            <div style="width:5%">
              <input onclick="selectAll()" type='checkbox' class='checkbox'>
            </div>
            <div style="width:5%">
              <?php echo "Id";?>
            </div>
            <div style="width:20%">
              <?php echo "Name";?>
            </div>
            <div style="width:15%" class="">
              <?php echo "Of the Day";?>
            </div>
            <div style="width:15%" class="">
              <?php echo "Sponsored";?>
            </div>
            <div style="width:15%" class="">
              <?php echo "Featured";?>
            </div>
            <div style="width:25%" class="">
              <?php echo "Options";?>
            </div>  
          </div>
          <ul class="sesmusic_manage_artist_form_list" id='menu_list'>
            <?php foreach ($this->paginator as $item) : ?>
              <li class="item_label" id="artists_<?php echo $item->artist_id ?>">
                <input type='hidden'  name='order[]' value='<?php echo $item->artist_id; ?>'>
                <div style="width:5%;">
                  <input name='delete_<?php echo $item->artist_id ?>_<?php echo $item->artist_id ?>' type='checkbox' class='checkbox' value="<?php echo $item->artist_id ?>_<?php echo $item->artist_id ?>"/>
                </div>
                <div style="width:5%;">
                  <?php echo $item->artist_id; ?>
                </div>
                <div style="width:20%;">
                  <?php echo $item->name ?>
                </div>
                <div style="width:15%;">
                  <?php if($item->offtheday == 1):?>  
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmusic', 'controller' => 'admin-manage', 'action' => 'oftheday', 'id' => $item->artist_id, 'type' => 'sesmusic_artist', 'param' => 0), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Edit Artist of the Day'))), array('class' => 'smoothbox')); ?>
                  <?php else: ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmusic', 'controller' => 'admin-manage', 'action' => 'oftheday', 'id' => $item->artist_id, 'type' => 'sesmusic_artist', 'param' => 1), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Make Artist of the Day'))), array('class' => 'smoothbox')) ?>
                  <?php endif; ?>
                </div>
                <div style="width:15%;">
                  <?php if($item->sponsored == 1):?>  
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmusic', 'controller' => 'admin-manage', 'action' => 'sponsored-artist', 'id' => $item->artist_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unmark as Sponsored')))) ?>
                  <?php else: ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmusic', 'controller' => 'admin-manage', 'action' => 'sponsored-artist', 'id' => $item->artist_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Sponsored')))) ?>
                  <?php endif; ?>
                </div>    
                <div style="width:15%;">
                  <?php if($item->featured == 1):?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmusic', 'controller' => 'admin-manage', 'action' => 'featured-artist', 'id' => $item->artist_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unmark as Featured')))) ?>
                  <?php else: ?>
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmusic', 'controller' => 'admin-manage', 'action' => 'featured-artist', 'id' => $item->artist_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Featured')))) ?>
                  <?php endif; ?>
                </div>                       
                <div style="width:25%;">          
                  <?php echo $this->htmlLink(
                    array('route' => 'default', 'module' => 'sesmusic', 'controller' => 'admin-settings', 'action' => 'edit-artist', 'artist_id' => $item->artist_id), $this->translate("Edit"), array('class' => 'smoothbox')) ?> |
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmusic', 'controller' => 'admin-settings', 'action' => 'delete-artist', 'artist_id' => $item->artist_id), $this->translate("Delete"), array('class' => 'smoothbox')) ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
          <div class='buttons'>
            <button type='submit'><?php echo $this->translate('Delete Selected'); ?></button>
          </div>
        <?php else:?>
          <div class="tip">
            <span>
              <?php echo "You have not added any artists yet.";?>
            </span>
          </div>
        <?php endif;?>
      </div>
    </form>
  </div>
</div>
