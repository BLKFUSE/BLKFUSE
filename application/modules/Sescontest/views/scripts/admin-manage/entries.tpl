<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: entries.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
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
    return confirm("<?php echo $this->translate('Are you sure you want to delete the selected entries?');?>");
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
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
<h3><?php echo $this->translate("Manage Contest Entries") ?></h3>
<p><?php echo $this->translate('This page lists all of the contest entries your users have submitted. You can use this page to monitor these contest entries and delete offensive material if necessary. Entering criteria into the filter fields will help you find specific contest entries. Leaving the filter fields blank will show all the contests on your social network. <br /> Below, you can also choose any number of contest entries as Entries of the Day.'); ?></p>
<br />
<div class='admin_search sesbasic_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<br />
<?php $counter = $this->paginator->getTotalItemCount(); ?> 
<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
  <div class="sesbasic_search_reasult">
    <?php echo $this->translate(array('%s entry found.', '%s entries found.', $counter), $this->locale()->toNumber($counter)) ?>
  </div>
  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
    <div class="admin_table_form">
      <table class='admin_table'>
        <thead>
          <tr>
            <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
            <th class='admin_table_short'><a href="javascript:void(0);" onclick="javascript:changeOrder('participant_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate("Title") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('user_id', 'ASC');"><?php echo $this->translate("Owner") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('contest_title', 'ASC');"><?php echo $this->translate("Contest") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('vote_count', 'ASC');"><?php echo $this->translate("Votes") ?></a></th>
            <th class="admin_table_centered"><a href="javascript:void(0);" onclick="javascript:changeOrder('offtheday', 'ASC');" title="Of the Day"><?php echo $this->translate("OTD") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('creation_date', 'ASC');"><?php echo $this->translate("Creation Date") ?></a></th>
            <th><?php echo $this->translate("Options") ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($this->paginator as $item): ?>
          <?php $contest = Engine_Api::_()->getItem('contest',$item->contest_id);?>
          <tr>
            <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->participant_id;?>' value="<?php echo $item->participant_id; ?>" /></td>
            <td><?php echo $item->participant_id ?></td>
            <td><?php echo $this->htmlLink($item->getHref(), $this->translate(Engine_Api::_()->sesbasic()->textTruncation($item->getTitle(),16)), array('title' => $item->getTitle(), 'target' => '_blank')) ?></td>
            <td><?php echo $this->htmlLink($item->getOwner()->getHref(), $this->translate(Engine_Api::_()->sesbasic()->textTruncation($item->getOwner()->getTitle(),16)), array('title' => $this->translate($item->getOwner()->getTitle()), 'target' => '_blank')) ?></td>
            <td><?php echo $this->htmlLink($contest->getHref(), $this->translate(Engine_Api::_()->sesbasic()->textTruncation($contest->getTitle(),16)), array('title' => $contest->getTitle(), 'target' => '_blank')) ?></td>
            <td><?php echo $item->vote_count; ?></td>
            <td class="admin_table_centered">
              <?php if(strtotime($item->enddate) < strtotime(date('Y-m-d')) && $item->offtheday == 1):?>
                <?php Engine_Api::_()->getDbtable('participants', 'sescontest')->update(array('offtheday' => 0,'startdate' =>'',
                          'enddate' =>''), array("participant_id = ?" => $item->participant_id));?>
                <?php $itemofftheday = 0;?>
              <?php else:?>
                <?php $itemofftheday = $item->offtheday; ?>
              <?php endif;?>
              <?php if($itemofftheday == 1):?>  
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'oftheday', 'id' => $item->participant_id, 'type' => 'participant', 'param' => 0), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Edit Entry of the Day'))), array('class' => 'smoothbox')); ?>
              <?php else: ?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sescontest', 'controller' => 'admin-manage', 'action' => 'oftheday', 'id' => $item->participant_id, 'type' => 'participant', 'param' => 1), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Make Entry of the Day'))), array('class' => 'smoothbox')) ?>
              <?php endif; ?>
            </td>
            <td><?php echo $item->creation_date ?></td>
            <td>
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescontest', 'controller' => 'manage', 'action' => 'entry-view', 'id' => $item->participant_id), $this->translate("View Details"), array('class' => 'smoothbox')) ?>
              |
              <?php echo $this->htmlLink($item->getHref(), $this->translate("View"), array('target' => '_blank')); ?>
              |
              <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescontest', 'controller' => 'manage', 'action' => 'delete', 'type' => 'participant','id' => $item->participant_id), $this->translate("Delete"), array('class' => 'smoothbox')) ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
    <br />
    <div class='buttons'>
      <button type='submit'><?php echo $this->translate("Delete Selected") ?></button>
    </div>
  </form>
  <br/>
  <div>
    <?php echo $this->paginationControl($this->paginator,null,null,$this->urlParams); ?>
  </div>
<?php else:?>
  <div class="tip">
    <span>
      <?php echo $this->translate("There are no entries created by your members yet.") ?>
    </span>
  </div>
<?php endif; ?>
<script type="text/javascript">
  function showSubCategory(cat_id) {
    var url = en4.core.baseUrl + 'sescontest/index/subcategory/category_id/' + cat_id;
    en4.core.request.send(scriptJquery.ajax({
			method: 'post',
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

    var url = en4.core.baseUrl + 'sescontest/index/subsubcategory/subcategory_id/' + cat_id;

    en4.core.request.send(scriptJquery.ajax({
			method: 'post',
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
