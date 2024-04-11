<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesstories/views/scripts/dismiss_message.tpl';?>
<script type="text/javascript">
  function multiDelete()
  {
    return confirm("<?php echo $this->translate("Are you sure you want to delete the selected stories?") ?>");
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
  function killProcess(story_id) {
    (scriptJquery.ajax({
      'format': 'json',
      'url' : '<?php echo $this->url(array('module' => 'sesstories', 'controller' => 'admin-manage', 'action' => 'kill'), 'default', true) ?>',
      'data' : {
      'format' : 'json',
      'story_id' : story_id
    }
                      ,
                      'onRequest' : function(){
     scriptJquery('input[type=radio]').attr('disabled', true);
  }
  ,
    success : function(responseJSON)
  {
    window.location.reload();
  }
  }
  ));
  }
</script>
<h3>
  <?php echo $this->translate("Manage Stories") ?>
</h3>
<p>
  <?php echo $this->translate('This page lists all of the stories your users have uploaded on your website. You can use this page to monitor these stories and delete offensive material if necessary. Entering criteria into the filter fields will help you find specific story. Leaving the filter fields blank will show all the stories on your social network.') ?>
</p>
<div class='admin_search sesbasic_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
<div class="sesbasic_search_reasult">
  <?php echo $this->translate(array('%s story found.', '%s stories found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
</div>
<form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
  <div class="admin_table_form" style="overflow:auto;">
    <table class='admin_table'>
      <thead>
        <tr>
          <th class='admin_table_short'>
            <input onclick='selectAll();' type='checkbox' class='checkbox' />
          </th>
          <th class='admin_table_short'>ID
          </th>
          <th>
            <?php echo $this->translate("Title") ?>
          </th>
          <th>
            <?php echo $this->translate("Type") ?>
          </th>
          <th>
            <?php echo $this->translate("Owner") ?>
          </th>
          <th>
            <?php echo $this->translate("Options") ?>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->paginator as $item): ?>
        <tr>
          <td>
            <input type='checkbox' class='checkbox' name='delete_<?php echo $item->story_id;?>' value='<?php echo $item->story_id ?>' />
          </td>
          <td>
            <?php echo $item->getIdentity() ?>
          </td>
          <td>
            <?php echo !empty($item->title) ? $item->title : '---'; ?>
          </td>
          <td>
            <?php if(empty($item->type)) { ?>
            <?php echo "Photo"; ?>
            <?php } else { ?>
            <?php echo "Video"; ?>
            <?php } ?>
          </td>
          <td>
            <?php echo $this->htmlLink($item->getHref(), $item->getOwner()); ?>
          </td>
          <td>
            <?php $file = Engine_Api::_()->getItemTable('storage_file')->getFile($item->file_id, ''); ?>
            <?php if(isset($file)) { ?>
            <?php $path = $file->map(); ?>
            <a href="<?php echo $path; ?>" target="_blank">
              <?php echo "View"; ?>
            </a> | 
            <?php }else{
                 // for live streaming.
                if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('elivestreaming')) {
                  $staticUserImage = 'application/modules/User/externals/images/nophoto_user_thumb_profile.png';
                  $user = Engine_Api::_()->getItem('user', $item->owner_id);
                  $elivehost = Engine_Api::_()->getDbtable('elivehosts', 'elivestreaming')->getHostId(array('story_id' => $item->story_id));
                  $userImage = $user->getPhotoUrl() ? $user->getPhotoUrl() : $staticUserImage;
                  if($elivehost->elivehost_id){ ?>
                    <a href="<?php echo $userImage; ?>" target="_blank"><?php echo "View"; ?></a> |<?php 
                  }else{ ?>
                    <a href="<?php echo $userImage; ?>" target="_blank"><?php echo "View"; ?></a> | <?php 
                  }
                }
              } ?>
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesstories', 'controller' => 'admin-manage', 'action' => 'delete', 'id' => $item->story_id),$this->translate("Delete"),array('class' => 'smoothbox')) ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class='buttons'>
    <button type='submit'>
      <?php echo $this->translate("Delete Selected") ?>
    </button>
  </div>
</form>
<div>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>
<?php else: ?>
<div class="tip">
  <span>
    <?php echo $this->translate("There are no stories uploaded by your members yet.") ?>
  </span>
</div>
<?php endif; ?>
