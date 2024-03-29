<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedactivity/views/scripts/dismiss_message.tpl';?>
<script type="text/javascript">
function multiDelete()
{
  return confirm("<?php echo $this->translate("Are you sure you want to delete the selected categories ?") ?>");
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
<div class='sesbasic-form sesbasic-categories-form'>
 <div>
    <?php if( engine_count($this->subnavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subnavigation)->render();?>
      </div>
    <?php endif; ?>
    <div class='settings sesbasic-form-cont'>
      <p>Here, you can add categories for the stickers on your website. You can associate stickers with these categories from the “Sticker Packs” section. </p><br />
      <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
        <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()"> 
      <?php endif; ?>
        <div class="sesbasic_search_reasult">
          <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedcomment', 'controller' => 'emotion', 'action' => 'create-category'), $this->translate("Add New Category"), array('class'=>'sesbasic_button fa fa-plus smoothbox')); ?>
        </div>
        <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
          <div class="sesbasic_search_reasult">
            <?php echo $this->translate(array('%s category found.', '%s categories found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
          </div>
        <?php endif; ?>
        <?php if(engine_count($this->paginator) > 0):?>
        	<table class="admin_table sesadvcomment_sticker_list">
          	<thead>
            	<tr>
                <th>
                  <input onclick='selectAll();' type='checkbox' class='checkbox' />
                </th>
                <th>
                 <?php echo $this->translate("Title") ?>
                </th>
                <th class="admin_table_centered">
                 <?php echo $this->translate("Color") ?>
                </th>
                <th class="admin_table_centered">
                 <?php echo $this->translate("Photo") ?>
                </th>              
                <th>
                 <?php echo $this->translate("Options"); ?>
                </th>  
              </tr>
            </thead>  
          	<tbody>
              <?php foreach ($this->paginator as $item) : ?>
                <tr class="item_label" id="slide_<?php echo $item->getIdentity(); ?>">
                  <td>
                    <input type='checkbox' class='checkbox' name='delete_<?php echo $item->getIdentity();?>' value='<?php echo $item->getIdentity() ?>' />
                  </td>
                  
                  <td class="sesadvcomment_sticker_list_title">
                    <?php echo $item->title ?>
                  </td>
                  
                  <td class="admin_table_centered">
                    <span class="sesadvcomment_sticker_list_color" style="background-color:<?php echo $item->color; ?>"><?php echo $item->color; ?></span>
                  </td>
                  <td class="admin_table_centered sesadvcomment_sticker_list_img">
                  	<img alt="" src="<?php echo Engine_Api::_()->storage()->get($item->file_id, '')->getPhotoUrl(); ?>" />
                  </td>
                  <td>          
                  	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesadvancedcomment', 'controller' => 'emotion', 'action' => 'create-category', 'id' => $item->getIdentity()), $this->translate("Edit"), array('class' => 'smoothbox')) ?>
              |
              		<?php echo $this->htmlLink(
                    array('route' => 'admin_default', 'module' => 'sesadvancedcomment', 'controller' => 'emotion', 'action' => 'delete-category', 'id' => $item->getIdentity()),
                    $this->translate("Delete"),
                    array('class' => 'smoothbox')) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
          	</tbody>
        	</table>
          <br />
          <div class='buttons'>
            <button type='submit'><?php echo $this->translate('Delete Selected'); ?></button>
          </div>
        <?php else:?>
          <div class="tip">
            <span>
              <?php echo "There are no categories created by you yet.";?>
            </span>
          </div>
        <?php endif;?>
      </form>
      <br />
      <div>
        <?php echo $this->paginationControl($this->paginator); ?>
      </div>
    </div>
  </div>
</div>  