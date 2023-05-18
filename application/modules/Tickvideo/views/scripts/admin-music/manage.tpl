<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manage.tpl 2020-11-03 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Tickvideo/views/scripts/dismiss_message.tpl';?>

<script type="text/javascript">
function multiDelete()
{
  return confirm("<?php echo $this->translate("Are you sure you want to delete the selected musics ?") ?>");
}
function selectAll()
{
  var i;
  var multidelete_form = document.getElementById('multidelete_form');
  var inputs = multidelete_form.elements;
  for (i = 1; i < inputs.length - 1; i++) {
    inputs[i].checked = inputs[0].checked;
  }
}
</script>
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()"> 
  <?php endif; ?>
  <div>
  	 <div class="sesbasic_search_reasult">
    	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'categories'), $this->translate("Back to Manage Categories"), array('class'=>'sesbasic_icon_back buttonlink')); ?>
      </div>
        <h3><?php echo "Manage Music: ".( $this->category ? $this->category->category_name : ''); ?></h3>
        <p>This page lists all of the music you have uploaded for this Music Category. Here, you can manage the music by editing or deleting them and also can play them.<br/>If you want to upload music in bulk, then you have to first download the Template folder from below. Also you can upload the music via that folder by following the instruction given at Import Music Page. </p>
        <br />
        <div>
         <div class="sesbasic_search_reasult"><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'create-music','id'=>$this->category_id), $this->translate("Upload New Music"), array('class'=>'buttonlink sesbasic_icon_add')); ?>

          <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller'=>'music','action'=>'faq','id'=>$this->category_id), $this->translate("Import Music"), array('class'=>'buttonlink tickvideo_icon_import')); ?>

          <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller'=>'music','action'=>'download-template'), $this->translate("Download Template Folder"), array('class'=>'buttonlink tickvideo_icon_download')); ?>
        </div>
</div>
        <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s Music found.', '%s Music found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
  </div><?php endif; ?>
        <?php if(engine_count($this->paginator) > 0):?>
        	<div class="sesbasic_manage_table">
          	<div class="sesbasic_manage_table_head" style="width:100%;">
              <div style="width:5%">
                <input onclick='selectAll();' type='checkbox' class='checkbox' />
              </div>
              <div style="width:5%">
                <?php echo "Id";?>
              </div>
              <div style="width:20%">
               <?php echo $this->translate("Title") ?>
              </div>
              <div style="width:20%" class="admin_table_centered">
               <?php echo $this->translate("Image") ?>
              </div>
                <div style="width:30%" class="admin_table_centered">
                    <?php echo $this->translate("Audio") ?>
                </div>
              <div style="width:20%">
               <?php echo $this->translate("Options"); ?>
              </div>  
            </div>
          	<ul class="sesbasic_manage_table_list" id='menu_list' style="width:100%;">
            <?php foreach ($this->paginator as $item) : ?>
              <li class="item_label" id="slide_<?php echo $item->music_id ?>">
                <div style="width:5%;">
                  <input type='checkbox' class='checkbox' name='delete_<?php echo $item->music_id;?>' value='<?php echo $item->music_id ?>' />
                </div>
                <div style="width:5%;">
                  <?php echo $item->music_id; ?>
                </div>
                <div style="width:20%;">
                  <?php echo $item->title ?>
                </div>
                <div style="width:20%;">
                  <?php if($item->photo_id && $storage = Engine_Api::_()->storage()->get($item->photo_id, '')): ?>
	                  <img height="100px;" width="100px;" style="margin:auto;display:block;" alt="" src="<?php echo $storage->getPhotoUrl(); ?>" />
                  <?php endif; ?>
                </div>
                <div style="width:30%;" class="admin_table_centered">
                  <?php if($item->file_id && $storage = Engine_Api::_()->storage()->get($item->file_id, '')): ?>
                    <audio controls>
                        <source src="<?php echo $storage->map(); ?>" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                  <?php endif; ?>
                </div>  
                <div style="width:20%;">          
                  <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'create-music', 'music_id' => $item->music_id,'id'=>$this->category_id), $this->translate("Edit"), array()) ?>
            |
            <?php echo $this->htmlLink(
                array('route' => 'admin_default', 'module' => 'tickvideo', 'controller' => 'music', 'action' => 'delete-music', 'id' => $item->music_id),
                $this->translate("Delete"),
                array('class' => 'smoothbox')) ?>
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
              <?php echo "There are no musics added by you yet.";?>
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
