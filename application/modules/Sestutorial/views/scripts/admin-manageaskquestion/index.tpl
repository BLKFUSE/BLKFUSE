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
    return confirm("<?php echo $this->translate('Are you sure you want to delete the selected qustions?');?>");
  }
  function selectAll(){
    var i;
    var multidelete_form = document.getElementById('multidelete_form');
    var inputs = multidelete_form.elements;
    for (i = 1; i < inputs.length - 1; i++) {
      inputs[i].checked = inputs[0].checked;
    }
  }
</script>
<?php include APPLICATION_PATH .  '/application/modules/Sestutorial/views/scripts/dismiss_message.tpl';?>
<h3><?php echo $this->translate("Manage Requested Tutorials") ?></h3>
<p><?php echo $this->translate('This page lists all the Questions asked by the users of your website. Below, you can answer any question or make them Tutorials by clicking in the appropriate links in Options section.'); ?></p>
<div class='admin_search sestutorial_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<?php $counter = $this->paginator->getTotalItemCount(); ?> 
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sestutorial_search_reasult">
    <?php echo $this->translate(array('%s question found.', '%s questions found.', $counter), $this->locale()->toNumber($counter)) ?>
  </div>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
    <div class="admin_table_form">
      <table class='admin_table'>
        <thead>
          <tr>
            <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
            <th class='admin_table_short'><?php echo $this->translate("ID") ?></th>
            <th><?php echo $this->translate("Tutorial Title") ?></th>
            <th align="center"><?php echo $this->translate("User Name") ?></th>
            <th><?php echo $this->translate("Email") ?></th>
            <th><?php echo $this->translate("Creation Date") ?></th>
            <th><?php echo $this->translate("Options") ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($this->paginator as $item):  ?>
          <tr>
            <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->askquestion_id;?>' value="<?php echo $item->askquestion_id; ?>" /></td>
            <td><?php echo $item->askquestion_id ?></td>
            <td><?php echo $item->description; ?></td>
            <td class="admin_table_name"><?php echo $item->name; ?></td>
            <td><?php echo $item->email; ?></td>
            <td><?php echo $item->creation_date ?></td>
            <td class="nowrap">
              <?php if($item->tutorial_id) { ?>
                <?php $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $item->tutorial_id); ?>
              <a href="<?php echo $tutorial ? $tutorial->getHref() : 'javascript:;'; ?>">View Tutorial</a>
              <?php } else { ?>
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sestutorial', 'controller' => 'admin-manage', 'action' => 'create', 'askquestion_id' => $item->askquestion_id), $this->translate("Make Tutorial"), array('class' => '')) ?>
              <?php } ?>
              |
              <?php if($item->reply) { ?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sestutorial', 'controller' => 'admin-manageaskquestion', 'action' => 'answerquestion', 'askquestion_id' => $item->askquestion_id), $this->translate("Answered"), array('class' => 'smoothbox')) ?>
              <?php } else { ?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sestutorial', 'controller' => 'admin-manageaskquestion', 'action' => 'answerquestion', 'askquestion_id' => $item->askquestion_id), $this->translate("Answer"), array('class' => 'smoothbox')) ?>
              <?php } ?>
              |
              <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sestutorial', 'controller' => 'admin-manageaskquestion', 'action' => 'delete', 'id' => $item->askquestion_id), $this->translate("Delete"), array('class' => 'smoothbox')) ?>
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
      <?php echo $this->translate("There are no requested tutorials yet.") ?>
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
