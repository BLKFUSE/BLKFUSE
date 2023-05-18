<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeelingactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedactivity/views/scripts/dismiss_message.tpl';?>
<div class='sesbasic_admin_form'>
 <div>
    <?php if( engine_count($this->subnavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subnavigation)->render();?>
      </div>
    <?php endif; ?>
  </div>
</div>
<script type="text/javascript">

  var SortablesInstance;

  scriptJquery( window ).load(function() {
    var SortablesInstance = scriptJquery('#menu_list').sortable({
      stop: function( event, ui ) {
        var ids = [];
        scriptJquery('#menu_list > div').each(function(e) {
          var el = scriptJquery(this);
          ids.push(el.attr('id'));
        });
        // Send request
        var url = '<?php echo $this->url(array('action' => 'order-manage-feeling')) ?>';
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

</script>

<script type="text/javascript">
function multiDelete()
{
  return confirm("<?php echo $this->translate("Are you sure you want to delete the selected feelings category ?") ?>");
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
   <div>
      <div class="sesbasic_search_reasult">
        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesfeelingactivity', 'controller' => 'feeling', 'action' => 'create-feelingcategory'), $this->translate("<i class='fa fa fa-plus'></i> Create New Category"), array('class'=>'buttonlink create_new_feeling smoothbox')); ?>
      </div>
    </div>
<h3>Manage Categories for Feelings & Activities</h3><br />
<p>Here, you can create Categories and manage Feelings & Activities in them. The categories can be of List type or Module type.<br /><br />
The List type categories will be simple in which users will see only the activities and feelings entered by you in the manage section of each category.<br/><br />
The Module type categories will have activities as content from the selected modules from the manage section of each category. Example: you can create a category Watching and select Module Video. Now, when your users will try to add watching activity they will see a list of all videos on your website in autosuggest box.<br/><br />
Each category will have its own icon which will be shown in the status update box while adding a new feeling/activity.
</p><br />
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()"> 
  <?php endif; ?>
  <div>
    <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
        <div class="sesbasic_search_reasult">
          <?php echo $this->translate(array('%s category found.', '%s categories found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
        </div><?php endif; ?>
        <?php if(engine_count($this->paginator) > 0):?>
        	<div class="sesfeelingactivity_packs_listing" id='menu_list'>
          	<?php foreach ($this->paginator as $item) : ?>
             	<div class="sesfeelingactivity_packs_list" id="managefeelings_<?php echo $item->feeling_id ?>">
                <input type='hidden'  name='order[]' value='<?php echo $item->feeling_id; ?>'>
              	<div>
                  <div class="_icon">
                    <?php $photo = Engine_Api::_()->storage()->get($item->file_id, '');
                    if($photo) { 
                    
                    $photo = $photo->getPhotoUrl(); ?>
                  	<img style="width:32px;" alt="" src="<?php echo $photo; ?>" />
                  	<?php } else { ?>
                      <?php echo "---"; ?>
                  	<?php } ?>
                  </div>
                  <div class="_cont">
                  	<div class="_title">
                    	<?php echo $item->title ?>
                    </div>
                    <div class="_options">
                      <?php echo ($item->enabled ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesfeelingactivity', 'controller' => 'feeling', 'action' => 'enabled', 'feeling_id' => $this->feeling_id, 'id' => $item->feeling_id), '', array('title' => $this->translate('Disable'), 'class' => 'fa sesfeelingactivity_icon_enabled')) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesfeelingactivity', 'controller' => 'feeling', 'action' => 'enabled', 'feeling_id' => $this->feeling_id, 'id' => $item->feeling_id), '', array('title' => $this->translate('Enable'), 'class' => 'fa sesfeelingactivity_icon_disabled'))) ?>
                      |
                    	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesfeelingactivity', 'controller' => 'feeling', 'action' => 'create-feelingcategory', 'id' => $item->getIdentity()), $this->translate("Edit"), array('class' => 'smoothbox')) ?>
                      |
                      <?php if($item->type == 1) { ?>
                        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesfeelingactivity', 'controller' => 'feeling', 'action' => 'feelingicons', 'feeling_id' => $item->getIdentity(), 'type' => $item->type), $this->translate("Manage Lists"), array()); ?>
                      <?php } else { ?>
                        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesfeelingactivity', 'controller' => 'feeling', 'action' => 'feelingicons', 'feeling_id' => $item->getIdentity(), 'type' => $item->type), $this->translate("Manage Modules"), array()); ?>
                      <?php }?>
                      |
                      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesfeelingactivity', 'controller' => 'feeling', 'action' => 'delete-feelingcategory', 'id' => $item->getIdentity()),
                      $this->translate("Delete"),
                      array('class' => 'smoothbox')) ?>
                    </div>
                  </div>
                </div>
              </div>
             <?php endforeach; ?>
          </div>
        </div>
        <?php else:?>
          <div class="tip">
            <span>
              <?php echo "There are no feelings category created by you yet.";?>
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
