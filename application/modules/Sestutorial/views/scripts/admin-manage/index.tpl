<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $baseURL = $this->layout()->staticBaseUrl; ?>
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
    return confirm("<?php echo $this->translate('Are you sure you want to delete the selected Tutorials?');?>");
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
  
  scriptJquery(document).ready(function() {
    scriptJquery('#menu_list').addClass('sortable');
    var SortablesInstance = scriptJquery('#menu_list').sortable({
      stop: function( event, ui ) {
        var ids = [];
        scriptJquery('#menu_list > tr').each(function(e) {
          var el = scriptJquery(this);
          ids.push(el.attr('id'));
        });
        // Send request
        var url = '<?php echo $this->url(array('action' => 'order-manage-tutorial')) ?>';
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
<?php include APPLICATION_PATH .  '/application/modules/Sestutorial/views/scripts/dismiss_message.tpl';?>
<h3><?php echo $this->translate("Add & Manage Tutorials") ?></h3>
<p><?php echo $this->translate('This page lists all the Tutorials you have created. You can use this page to monitor these tutorials and delete offensive material if necessary. Entering criteria into the filter fields will help you find specific tutorial. Leaving the filter fields blank will show all the tutorials on your social network.<br />Below, you can also enable or disable any Tutorial, view their rating and helpful statistics. Use the "Add New Tutorial" link to create and add new Tutorials. <br />To reorder the Tutorials, click on their names and drag them up or down.'); ?></p>
<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sestutorial', 'controller' => 'manage', 'action' => 'create'), $this->translate('Add New Tutorial'), array('class' => 'buttonlink sestutorial_icon_add')); ?>
<div class='admin_search sestutorial_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<?php $counter = $this->paginator->getTotalItemCount(); ?> 
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sestutorial_search_reasult">
    <?php echo $this->translate(array('%s tutorial found.', '%s tutorials found.', $counter), $this->locale()->toNumber($counter)) ?>
  </div>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
    <div class="admin_table_form">
      <table class='admin_table sestutorial_admin_manage_table'>
        <thead>
          <tr>
            <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
            <th class='admin_table_short'><a href="javascript:void(0);" onclick="javascript:changeOrder('tutorial_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate("Title") ?></a></th>
            <th><?php echo $this->translate("Helpful") ?></th>
            <th><?php echo $this->translate("Ratings") ?></th>
            <th class="admin_table_centered"><a href="javascript:void(0);" onclick="javascript:changeOrder('status', 'ASC');" title="Status"><?php echo $this->translate("Status") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');"><?php echo $this->translate("Creation Date") ?></a></th>
            <th><?php echo $this->translate("Options") ?></th>
          </tr>
        </thead>
        <tbody id='menu_list'>
          <?php foreach ($this->paginator as $item): ?>
          <?php
            $helpfulCountforYes = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->helpfulCount($item->tutorial_id, 1);
            $helpfulCountforNo = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->helpfulCount($item->tutorial_id, 2);
            $totalHelpful = $helpfulCountforYes + $helpfulCountforNo;
            if(!empty($totalHelpful)) {
              $percentageHelpful = ($helpfulCountforYes / ($totalHelpful))*100;
              if($percentageHelpful > 0) {
                $final_value = round($percentageHelpful);
              } else {
                $final_value = 0;
              }
            }
          ?>
          <tr class="item_label" id="managesearch_<?php echo $item->tutorial_id ?>">
            <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->tutorial_id;?>' value="<?php echo $item->tutorial_id; ?>" /></td>
            <td><?php echo $item->tutorial_id ?></td>
            <td><?php echo $this->htmlLink($item->getHref(), $this->translate(Engine_Api::_()->sestutorial()->textTruncation($item->getTitle(),36)), array('title' => $item->getTitle(), 'target' => '_blank')) ?></td>
            <td><?php echo !empty($final_value) ? $final_value.'%' : '---'; ?></td>
            <td>
              <?php if($item->rating): ?>
              <div class="sestutorial_rating_star">
                <?php if( $item->rating > 0 ): ?>
                <?php for( $x=1; $x<= $item->rating; $x++ ): ?>
                	<span class="sestutorial_rating_star_small fa fa-star"></span>
                <?php endfor; ?>
                <?php if((round($item->rating) - $item->rating) > 0): ?>
                	<span class="sestutorial_rating_star_small fa fa-star-half"></span>
                <?php endif; ?>
                <?php endif; ?>
              </div>
              <?php else: ?>
              	<div class="sestutorial_rating_star">
                	<?php for( $x=1; $x<= 5; $x++ ): ?>
                  	<span class="sestutorial_rating_star_small fa fa-star star-disabled"></span>
                	<?php endfor; ?>
                </div>
              <?php endif; ?>
            </td>
            <td class="admin_table_centered">
              <?php if($item->status == 1):?>
                <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sestutorial', 'controller' => 'manage', 'action' => 'status', 'id' => $item->tutorial_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/images/check.png', '', array('title'=> $this->translate('Disable')))) ?>
              <?php else: ?>
                <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sestutorial', 'controller' => 'manage', 'action' => 'status', 'id' => $item->tutorial_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sestutorial/externals/images/error.png', '', array('title'=> $this->translate('Enable')))) ?>
              <?php endif; ?>
            </td>
            <td><?php echo $item->creation_date ?></td>
            <td class="nowrap">
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sestutorial', 'controller' => 'manage', 'action' => 'edit', 'tutorial_id' => $item->tutorial_id), $this->translate("Edit"), array('class' => '')) ?>
              |
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sestutorial', 'controller' => 'manage', 'action' => 'view', 'id' => $item->tutorial_id), $this->translate("View Details"), array('class' => 'smoothbox')) ?>
              |
              <?php echo $this->htmlLink($item->getHref(), $this->translate("View"), array('target' => '_blank')); ?>
              |
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sestutorial', 'controller' => 'manage', 'action' => 'delete', 'id' => $item->tutorial_id), $this->translate("Delete"), array('class' => 'smoothbox')) ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
    <div class='buttons'>
      <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
    </div>
  </form>
  <div>
    <?php echo $this->paginationControl($this->paginator,null,null,$this->urlParams); ?>
  </div>
<?php else:?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no Tutorials created by you yet.") ?>
    </span>
  </div>
<?php endif; ?>
<script type="text/javascript">
  function showSubCategory(cat_id) {
    var url = en4.core.baseUrl + 'sestutorial/index/subcategory/category_id/' + cat_id;
    en4.core.request.send(scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {
      },
      success: function(responseHTML) {
        if (document.getElementById('subcat_id') && responseHTML) {
          if (document.getElementById('subcat_id-wrapper')) {
            document.getElementById('subcat_id-wrapper').style.display = "inline-block";
          }
          document.getElementById('subcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subcat_id-wrapper')) {
            document.getElementById('subcat_id-wrapper').style.display = "none";
            document.getElementById('subcat_id').innerHTML = '';
          }
        }
      }
    }));
  }
function showSubSubCategory(cat_id) {

    var url = en4.core.baseUrl + 'sestutorial/index/subsubcategory/subcategory_id/' + cat_id;

    en4.core.request.send(scriptJquery.ajax({
    method: 'post',
      dataType: 'html',
      url: url,
      data: {
      },
      success: function(responseHTML) {
        if (document.getElementById('subsubcat_id') && responseHTML) {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "inline-block";
          }
          document.getElementById('subsubcat_id').innerHTML = responseHTML;
        } else {
          if (document.getElementById('subsubcat_id-wrapper')) {
            document.getElementById('subsubcat_id-wrapper').style.display = "none";
            document.getElementById('subsubcat_id').innerHTML = '';
          }
        }
      }
    }));
  }
  scriptJquery(document).ready(function() {
    if (document.getElementById('category_id') && document.getElementById('category_id').value == 0)
     document.getElementById('subcat_id-wrapper').style.display = "none";   
		if (document.getElementById('subcat_id') && document.getElementById('subcat_id').value == 0)
     document.getElementById('subsubcat_id-wrapper').style.display = "none"; 
  });
</script>
<style type="text/css">
	div.sestutorial_search_form form{
		padding:10px;
	}
	div.sestutorial_search_form form > div {
		display:inline-block;
		float:none;
		margin:5px 10px 5px 0;
	}
	div.sestutorial_search_form form > div label{
		font-weight:normal;
	}
	div.sestutorial_search_form form > div input[type="text"],
	div.sestutorial_search_form form > div select{
		min-width:100px;
		padding:5px;
	}
</style>
