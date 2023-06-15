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
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?> 
<?php if($this->can_create) { ?>
<?php echo $this->htmlLink(array('action' => 'create', 'route' => 'sesnews_generalrss', 'reset' => true), $this->translate('Add New RSS'), array('class' => 'button buttonlink sesbasic_button add_rss_btn floatR')); ?>
<?php } ?>
  <?php if( $this->paginator->getTotalItemCount() > 0 ): ?>
    <ul class="sesnews_rss_manage">
      <?php foreach( $this->paginator as $item ): ?>
        <li>
          <div class='sesnews_rss_manage_photo'>
            <?php echo $this->htmlLink($item->getHref(), $this->itemBackgroundPhoto($item, 'thumb.main')) ?>
             <div class="sesnews_rss_link">
               <a href="<?php echo $item->rss_link; ?>"><i class="fa fa-rss" aria-hidden="true"></i><?php echo $item->rss_link; ?></a>
             </div>
          </div>
          <?php if($this->can_edit || $this->can_delete) { ?>
            <div class='sesnews_rss_manage_options'>
            <a href="javascript:;" class="sesbasic_pulldown_toggle"><i class="fa fa-ellipsis-h"></i></a>
              <div class='sesbasic_pulldown_options'>
              <?php if($this->can_edit) { ?>
              <?php echo $this->htmlLink(array(
                'module' =>'sesnews',
                'controller' => 'rss',
                'action' => 'edit',
                'rss_id' => $item->getIdentity(),
                'route' => 'default',
              ), $this->translate('Edit Rss'), array(
                'class' => 'buttonlink icon_rss_edit',
              )) ?>
              <?php } ?>
              <?php if($this->can_delete) { ?>
                <?php
                echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'rss', 'action' => 'delete', 'rss_id' => $item->getIdentity(), 'format' => 'smoothbox'), $this->translate('Delete Rss'), array(
                  'class' => 'buttonlink smoothbox icon_rss_delete'
                ));
                ?>
              <?php } ?>
              <?php if($item->is_approved) { ?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'rss', 'action' => 'importnews', 'rss_id' => $item->getIdentity(), 'format' => 'smoothbox'), $this->translate('Import News'), array('class' => 'buttonlink smoothbox icon_import')); ?>
              <?php } ?>
            </div>
             </div>
          <?php } ?>
          <div class='sesnews_rss_manage_info'>
            <span class='sesnews_rss_manage_info_title'>
                <h3><?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?></h3>
            </span>
            <div class="sesnews_rss_stats">
            <div class="sesnews_stats_list sesbasic_text_dark">
            <span>
              <i class="far fa-clock"></i>
              <?php if($item->publish_date): ?>
                <?php echo date('M d, Y',strtotime($item->creation_date));?>
              <?php else: ?>
                <?php echo date('M d, Y',strtotime($item->creation_date));?>
              <?php endif; ?>
            </span>
            <span>
              <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>
            </span>
            <span>
              <?php echo $this->translate(array('%s news', '%s news', $item->news_count), $this->locale()->toNumber($item->news_count)) ?>
            </span>
            <span>
              <?php echo $this->translate(array('%s subscriber', '%s subscribers', $item->subscriber_count), $this->locale()->toNumber($item->subscriber_count)) ?>
            </span>
          </div>
          <?php if($item->category_id != '' && intval($item->category_id) && !is_null($item->category_id)):?> 
            <?php $categoryItem = Engine_Api::_()->getItem('sesnews_category', $item->category_id);?>
            <?php if($categoryItem):?>
              <div class="sesnews_stats_list sesbasic_text_dark">
                <span>
                <i class="far fa-folder-open" title="<?php echo $this->translate('Category'); ?>"></i> 
                <a href="<?php echo $categoryItem->getHref(); ?>"><?php echo $categoryItem->category_name; ?></a>
                </span>
              </div>
            <?php endif;?>
          <?php endif;?>
          </div>
          <div class="sesnews_rss_desc"><?php echo $item->body; ?></div>
          </div>
          <div>
         </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php elseif($this->search): ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('You do not have any rss entries that match your search criteria.');?>
      </span>
    </div>
  <?php else: ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('You do not have any rss entries.');?>
      </span>
    </div>
  <?php endif; ?>

  <?php echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => true,
    'query' => $this->formValues,
  )); ?>
