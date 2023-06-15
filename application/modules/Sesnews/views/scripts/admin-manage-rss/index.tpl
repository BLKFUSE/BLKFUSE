<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<script type="text/javascript">

  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction){
    // Just change direction
    if( order == currentOrder ) {
      document.getElementById('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } else {
      document.getElementById('order').value = order;
      document.getElementById('order_direction').value = default_direction;
    }
    scriptJquery('#filter_form').trigger('submit');
  }

  function multiDelete() {
    return confirm("<?php echo $this->translate('Are you sure you want to delete the selected rss entries?');?>");
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

<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/dismiss_message.tpl';?>
<h3><?php echo $this->translate('Manage RSS'); ?></h3>
<p>
  <?php echo $this->translate("This page lists all of the rss your users have posted. You can use this page to monitor these rss and delete offensive material if necessary. Entering criteria into the filter fields will help you find specific rss entries. Leaving the filter fields blank will show all the rss entries on your social network.") ?>
</p>
<br />
<div class='admin_search sesbasic_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>	
<br />	
<br />
<?php $counter = $this->paginator->getTotalItemCount(); ?>
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
	<div class="sesbasic_search_reasult">
		<?php echo $this->translate(array('%s rss found.', '%s rss found.', $counter), $this->locale()->toNumber($counter)) ?>
	</div>
<form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
<table class='admin_table'>
  <thead>
    <tr>
      <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
      <th class='admin_table_short'><a href="javascript:void(0);" onclick="javascript:changeOrder('rss_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
      <th><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate("Title") ?></a></th>
      <th><a href="javascript:void(0);" onclick="javascript:changeOrder('owner_id', 'ASC');"><?php echo $this->translate("Owner") ?></a></th>
      <th class="admin_table_centered" title='<?php echo $this->translate("Approved") ?>'><?php echo $this->translate("A") ?></th>
      <th class="admin_table_centered" title='<?php echo $this->translate("Cron") ?>'><?php echo $this->translate("Cron") ?></th>
      <th><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');"><?php echo $this->translate("Creation Date") ?></a></th>
      <th><?php echo $this->translate("Options") ?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($this->paginator as $item): ?>
      <tr>
        <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->getIdentity(); ?>' value="<?php echo $item->getIdentity(); ?>" /></td>
        <td><?php echo $item->getIdentity() ?></td>
				<td>
					<?php if(strlen($item->getTitle()) > 16):?>
						<?php $title = mb_substr($item->getTitle(),0,16).'...';?>
						<?php echo $this->htmlLink($item->getHref(),$title,array('title'=>$item->getTitle()));?>
					<?php else: ?>
						<?php echo $this->htmlLink($item->getHref(),$item->getTitle(),array('title'=>$item->getTitle())  ) ?>
					<?php endif;?>
				</td>
        <td><a href="<?php echo $item->getOwner()->getHref(); ?>"><?php echo $item->getOwner()->getTitle() ?></a></td>
        <td class="admin_table_centered">
          <?php if($item->is_approved == 1):?>
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'admin-manage-rss', 'action' => 'approved', 'id' => $item->rss_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Unmark as Approved')))) ?>
          <?php else: ?>
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'admin-manage-rss', 'action' => 'approved', 'id' => $item->rss_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Approved')))) ?>
          <?php endif; ?>
        </td> 
        <td class="admin_table_centered">
          <?php if($item->cron_enabled == 1):?>
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'admin-manage-rss', 'action' => 'cron', 'id' => $item->rss_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Disable Cron')))) ?>
          <?php else: ?>
            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'admin-manage-rss', 'action' => 'cron', 'id' => $item->rss_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Enable Cron')))) ?>
          <?php endif; ?>
        </td> 
        <td><?php echo $item->creation_date ?></td>
        <td>
          <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'admin-manage-rss', 'action' => 'delete', 'id' => $item->rss_id), $this->translate("delete"), array('class' => 'smoothbox')) ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<br />
<div class='buttons'>
  <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
</div>
</form>
<br/>
<div>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>
<?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no rss entries by your members yet.") ?>
    </span>
  </div>
<?php endif; ?>

<script>
 function showSubCategory(cat_id,selectedId) {
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sesnews/index/subcategory/category_id/' + cat_id;
    scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subcat_id') && responseHTML) {
          if (document.getElementById('subcat_id')) {
            document.getElementById('subcat_id-label').parentNode.style.display = "inline-block";
          }
          document.getElementById('subcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subcat_id')) {
            document.getElementById('subcat_id-label').parentNode.style.display = "none";
            document.getElementById('subcat_id').innerHTML = '';
          }
					 if (document.getElementById('subsubcat_id')) {
            document.getElementById('subsubcat_id-label').parentNode.style.display = "none";
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
    var url = en4.core.baseUrl + 'sesnews/index/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (document.getElementById('subsubcat_id')) {
            document.getElementById('subsubcat_id-label').parentNode.style.display = "inline-block";
          }
          document.getElementById('subsubcat_id').innerHTML = responseHTML;
					// get category id value 
        } else {
          if (document.getElementById('subsubcat_id')) {
            document.getElementById('subsubcat_id-label').parentNode.style.display = "none";
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
	  document.getElementById('subcat_id-label').parentNode.style.display = "none";
	 <?php } ?>
	 <?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
      showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>');
	 <?php }else{?>
	 		 document.getElementById('subsubcat_id-label').parentNode.style.display = "none";
	 <?php } ?>
</script>
