<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: files.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedactivity/views/scripts/dismiss_message.tpl';?>
<script type="text/javascript">
function multiDelete()
{
  return confirm("<?php echo $this->translate("Are you sure you want to delete the selected stickers?") ?>");
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
<div class='sesbasic_admin_form'>
 <div>
    <?php if( engine_count($this->subnavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subnavigation)->render();?>
      </div>
    <?php endif; ?>
  </div>
</div>
<div class='sesbasic_admin_form'>
 <div>
    <?php if($this->subsubNavigation && engine_count($this->subsubNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subsubNavigation)->render();?>
      </div>
    <?php endif; ?>
  </div>
</div>
  
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()"> 
  <?php endif; ?>
  <div>
     <div class="sesbasic_search_reasult">
     	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedcomment', 'controller' => 'emotion', 'action' => 'gallery'), $this->translate("Back to Sticker Packs"), array('class'=>'sesbasic_button fa fa-long-arrow-alt-left')); ?>
      
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedcomment', 'controller' => 'emotion', 'action' => 'create-file','gallery_id'=>$this->gallery_id), $this->translate("Add Sticker"), array('class'=>'sesbasic_button fa fa-plus smoothbox')); ?>
     
     <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedcomment', 'controller' => 'emotion', 'action' => 'upload-zip-file','gallery_id'=>$this->gallery_id), $this->translate("Upload Stickers in Zip"), array('class'=>'sesbasic_button fa fa-plus smoothbox')); ?>
</div>
        <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s sticker found.', '%s stickers found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
  </div><?php endif; ?>
        <?php if(engine_count($this->paginator) > 0):?>
					<div class="sescmt_stickers_list">          
           <?php foreach ($this->paginator as $item) : 
           $itemTags = $item->tags()->getTagMaps();
           ?>
            <div class="sescmt_stickers_list_item" id="slide_<?php echo $item->getIdentity(); ?>">
            	<span class="sescmt_stickers_list_item_select"><input type='checkbox' class='checkbox' name='delete_<?php echo $item->getIdentity();?>' value='<?php echo $item->getIdentity() ?>' /></span>
              <div class="sescmt_stickers_list_item_img">
                <img alt="" src="<?php echo Engine_Api::_()->storage()->get($item->photo_id, '')->getPhotoUrl(); ?>" />
              </div>
              <div class="sescmt_stickers_list_item_options">          
                <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedcomment', 'controller' => 'emotion', 'action' => 'create-file', 'id' => $item->getIdentity(),'gallery_id'=>$this->gallery_id), $this->translate(""), array('title'=> $this->translate("Edit"), 'class' => 'smoothbox fa fa-edit')) ?>
                |
                <?php echo $this->htmlLink(
                    array('route' => 'admin_default', 'module' => 'sesadvancedcomment', 'controller' => 'emotion', 'action' => 'delete-file', 'id' => $item->getIdentity()), $this->translate(""), array('title'=> $this->translate("Delete"), 'class' => 'smoothbox fa fa-trash')) ?>
              </div>
             	<?php if (engine_count($itemTags)):?>  	
                <?php $finaltags = '';
                foreach ($itemTags as $tag): ?>
                  <?php $finaltags .= $tag->getTag()->text .', '; ?>
                <?php endforeach;
                $finaltags = trim($finaltags);
                $finaltags = rtrim($finaltags, ', '); ?>
                <div class="sescmt_stickers_list_item_tags" title="<?php echo $finaltags; ?>">
                	<?php echo $finaltags; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
          </div>
          <div class='buttons' style="margin-top:15px;">
          	<button type='submit'><?php echo $this->translate('Delete Selected'); ?></button>
        	</div
        ><?php else: ?>
          <div class="tip">
            <span>
              <?php echo "There are no files created by you yet.";?>
            </span>
          </div>
        <?php endif;?>
      </div>
  </form>
  <br />
  <div>
    <?php echo $this->paginationControl($this->paginator); ?>
  </div>
