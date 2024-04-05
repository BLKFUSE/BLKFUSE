<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<script type="text/javascript">
function multiDelete()
{
  return confirm("<?php echo $this->translate('Are you sure you want to delete the selected photo albums?');?>");
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
<?php  include APPLICATION_PATH .  '/application/modules/Egames/views/scripts/header.tpl'; ?>

<h3><?php echo $this->translate("Manage Games") ?></h3>
<p>This page lists all of the games your users have created. You can use this page to monitor these games & can delete offensive material if necessary. Entering the criteria into the filter fields will help you to find specific games. Leaving the fields blank will show all the photos on your social network.</p>
<?php
$settings = Engine_Api::_()->getApi('settings', 'core');?>	
<div class='admin_search sesbasic_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<?php $counter = $this->paginator->getTotalItemCount(); ?> 
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s game found.', '%s games found.', $counter), $this->locale()->toNumber($counter)) ?>
  </div>
<form id="multidelete_form" action="<?php echo $this->url();?>" onSubmit="return multiDelete()" method="POST">
  <table class='admin_table'>
    <thead>
      <tr>
        <th class='admin_table_short'><input onclick="selectAll()" type='checkbox' class='checkbox' /></th>
        <th class='admin_table_short'>ID</th>
        <th align="center"><?php echo $this->translate('Photos') ?></th>
        <th><?php echo $this->translate('Title') ?></th>
        <th><?php echo $this->translate('Owner') ?></th>
        
        <th><?php echo $this->translate('Options') ?></th>
      </tr>
    </thead>
    <tbody>
        <?php foreach ($this->paginator as $item):
        $user = Engine_Api::_()->getItem('user',$item->owner_id);
        if(!$user->getIdentity())
          continue;
        ?>
          <tr>
            <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->game_id;?>' value="<?php echo $item->game_id ?>"/></td>
            
            <td><?php echo $item->getIdentity() ?></td>
            <td class="admin_table_centered">
              <img src="<?php echo $item->getPhotoUrl(); ?>" height="50px" width="50px" />
            </td>
            <td><?php echo $this->htmlLink($item->getHref(), $item->getTitle()); ?></td> 
            <td><?php echo $this->htmlLink($user, $user->getOwner()); ?></td>
            
            
            <td>
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'egames', 'controller' => 'admin-manage', 'action' => 'view',  'id' => $item->game_id), $this->translate("View Details"), array('class' => 'smoothbox')) ?>
              |
              <a href="<?php echo $item->getHref(); ?>" target="_blank">
                <?php echo $this->translate('View') ?>
              </a>
              |
               <a href="<?php echo $this->url(array('game_id' => $item->getIdentity()), 'egames_specific') ?>" target="_blank">
                <?php echo $this->translate('Edit') ?>
              </a>
              |
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'egames', 'controller' => 'admin-manage', 'action' => 'delete', 'id' => $item->game_id), $this->translate("Delete"), array('class' => 'smoothbox')) ?>
            </td>
          </tr>
        <?php endforeach; ?>
    </tbody>
  </table>
  <div class='buttons'>
    <button type='submit'>
      <?php echo $this->translate('Delete Selected') ?>
    </button>
  </div>
</form>
<div>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no games posted by your members yet.") ?>
    </span>
  </div>
<?php endif; ?>
<script>
 function showSubCategory(cat_id,selectedId) {
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'egames/index/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
			method: 'post',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subcat_id') && responseHTML) {
          if (document.getElementById('subcat_id')) {
            document.getElementById('subcat_id').parentNode.style.display = "inline-block";
          }
          document.getElementById('subcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subcat_id')) {
            document.getElementById('subcat_id').parentNode.style.display = "none";
            document.getElementById('subcat_id').innerHTML = '';
          }
					 if (document.getElementById('subsubcat_id')) {
            document.getElementById('subsubcat_id').parentNode.style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '';
          }
        }
      }
    });
  }
	function showSubSubCategory(cat_id,selectedId) {
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'egames/index/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
			method: 'post',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (document.getElementById('subsubcat_id')) {
            document.getElementById('subsubcat_id').parentNode.style.display = "inline-block";
          }
          document.getElementById('subsubcat_id').innerHTML = responseHTML;
					// get category id value 
        } else {
          if (document.getElementById('subsubcat_id')) {
            document.getElementById('subsubcat_id').parentNode.style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '';
          }
        }
      }
    }));  
 }
	var sesdevelopment = 1;
	<?php if(isset($this->category_id) && $this->category_id != 0){ ?>
			<?php if(isset($this->subcat_id) && $this->subcat_id != 0){$catId = $this->subcat_id;}else $catId = ''; ?>
      showSubCategory('<?php echo $this->category_id ?>','<?php echo $catId; ?>');
   <?php  }else{?>
	  document.getElementById('subcat_id').parentNode.style.display = "none";
	 <?php } ?>
	 <?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
      showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>');
	 <?php }else{?>
	 		 document.getElementById('subsubcat_id').parentNode.style.display = "none";
	 <?php } ?>
</script>
