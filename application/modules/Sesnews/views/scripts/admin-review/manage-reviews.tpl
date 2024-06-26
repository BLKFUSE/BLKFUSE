<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: manage-reviews.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
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
</script>

<script type="text/javascript">

  function multiDelete() {
    return confirm("<?php echo $this->translate('Are you sure you want to delete the selected reviews?');?>");
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

<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/dismiss_message.tpl';?>

<div class='clear sesbasic-form'>
  <div>
    <?php if( engine_count($this->subnavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subnavigation)->render(); ?>
      </div>
    <?php endif; ?>
    <div class="sesbasic-form-cont">
      <div class='settings sesbasic_admin_form'>
				<h3><?php echo $this->translate("Manage Reviews"); ?></h3>
				<p><?php echo $this->translate('This page lists all of the reviews your users have created. You can use this page to monitor these reviews and delete offensive material if necessary. Entering criteria into the filter fields will help you find specific review. Leaving the filter fields blank will show all the reviews on your social network. <br /> Below, you can also choose any number of reviews as Reviews of the Day. These reviews will be displyed randomly in the "Review of the Day" widget.'); ?></p>
				<br />

				<div class='admin_search sesbasic_search_form'>
				  <?php echo $this->formFilter->render($this) ?>
				</div>
				<br />

				<?php $counter = $this->paginator->getTotalItemCount(); ?> 
				<?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
				  <div class="sesbasic_search_reasult">
				    <?php echo $this->translate(array('%s review found.', '%s reviews found.', $counter), $this->locale()->toNumber($counter)) ?>
				  </div>
				  <form id='multidelete_form' method="post" action="<?php echo $this->url();?>" onSubmit="return multiDelete()">
				    <table class='admin_table'>
				      <thead>
				        <tr>
				          <th class='admin_table_short'><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
				          <th class='admin_table_short'><a href="javascript:void(0);" onclick="javascript:changeOrder('review_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
				          <th><a href="javascript:void(0);" onclick="javascript:changeOrder('title', 'ASC');"><?php echo $this->translate("Review Title") ?></a></th>
				          <th><?php echo $this->translate("Content Title") ?></th>
				          <th><a href="javascript:void(0);" onclick="javascript:changeOrder('owner_id', 'ASC');"><?php echo $this->translate("Owner") ?></a></th>
									<th><?php echo $this->translate("Featured") ?></th>
									<th><?php echo $this->translate("Verified") ?></th>
									<th><?php echo $this->translate("Of the Day") ?></th>
				          <th><?php echo $this->translate("Options") ?></th>
				        </tr>
				      </thead>
				      <tbody>
				        <?php foreach ($this->paginator as $item): ?>
				        <tr>
				          <td><input type='checkbox' class='checkbox' name='delete_<?php echo $item->review_id;?>' value="<?php echo $item->review_id; ?>" /></td>
				          <td><?php echo $item->review_id ?></td>
				          <td><?php echo $this->htmlLink($item->getHref(), $this->translate($item->getTitle()), array('title' => $item->getTitle(), 'target' => '_blank')) ?></td>
				          
				          <td>
				            <?php $contentItem = Engine_Api::_()->getItem('sesnews_news', $item->news_id); ?>
                    <?php if($contentItem) { ?>
				            <?php echo $this->htmlLink($contentItem->getHref(), $this->translate($contentItem->getTitle()), array('title' => $contentItem->getTitle(), 'target' => '_blank')) ?><?php } ?></td>
				          
				          <td><?php echo $this->htmlLink($item->getOwner()->getHref(), $this->translate($item->getOwner()->getTitle()), array('title' => $this->translate($item->getOwner()->getTitle()), 'target' => '_blank')) ?></td>
									<td class='admin_table_centered'>
										<?php echo ( $item->featured ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesnews', 'controller' => 'review', 'action' => 'featured', 'review_id' => $item->review_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Unmark as Featured'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesnews', 'controller' => 'review', 'action' => 'featured', 'review_id' => $item->review_id), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Mark Featured')))) ) ?>
									</td>
									<td class='admin_table_centered'>
										<?php echo ( $item->verified ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesnews', 'controller' => 'review', 'action' => 'verified', 'review_id' => $item->review_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Unmark as Verified'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesnews', 'controller' => 'review', 'action' => 'verified', 'review_id' => $item->review_id), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Mark Verified')))) ) ?>
									</td>
									<td class='admin_table_centered'>
										<?php if($item->oftheday == 1):?>  
											<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'admin-review', 'action' => 'oftheday', 'id' => $item->review_id, 'param' => 0), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Edit News Review of the Day'))), array('class' => 'smoothbox')); ?>
										<?php else: ?>
											<?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'admin-review', 'action' => 'oftheday', 'id' => $item->review_id, 'param' => 1), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Make News Review of the Day'))), array('class' => 'smoothbox')) ?>
										<?php endif; ?>
									</td>
				          <td>
				            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'admin-review', 'action' => 'view', 'id' => $item->review_id), $this->translate("View Details"), array('class' => 'smoothbox')) ?>
				            |
				            <?php echo $this->htmlLink($item->getHref(), $this->translate("View"), array('class' => '')); ?>
				            |
				            <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'admin-review', 'action' => 'delete-review', 'id' => $item->review_id), $this->translate("Delete"), array('class' => 'smoothbox')) ?>
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
				<?php else:?>
				  <div class="tip">
				    <span>
				      <?php echo $this->translate("There are no reviews created by your members yet.") ?>
				    </span>
				  </div>
				<?php endif; ?>
      </div>
    </div>
  </div>
</div>
