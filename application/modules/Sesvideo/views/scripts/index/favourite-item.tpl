<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: favourite-item.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<script type="text/javascript">
  function viewMore() {
    if (document.getElementById('view_more'))
    document.getElementById('view_more').style.display = "<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>"; 
    document.getElementById('view_more').style.display = 'none';
    document.getElementById('loading_image').style.display = '';
    var id = '<?php echo $this->item_id; ?>';
    (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + 'sesvideo/index/favourite-item/item_id/<?php echo $this->item_id ?>/item_type/<?php echo $this->item_type; ?>',
      'data': {
        format: 'html',
        page: "<?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>",
        viewmore: 1        
      },
      success: function(responseHTML) {
        scriptJquery('#like_results').append(responseHTML);
        scriptJquery('#view_more').remove();
        document.getElementById('loading_image').style.display = 'none';
      }
    }));
    return false;
  }
</script>
<?php if (empty($this->viewmore)): ?>
  <div class="sesbasic_items_listing_popup">
    <div class="sesbasic_items_listing_header">
         <?php echo urldecode($this->title); ?>
      <a class="fa fa-times" href="javascript:;" onclick='smoothboxclose();' title="<?php echo $this->translate('Close') ?>"></a>
    </div>
    <div class="sesbasic_items_listing_cont" id="like_results">
<?php endif; ?>
    <?php if (is_countable($this->paginator) && engine_count($this->paginator) > 0) : ?>
      <?php foreach ($this->paginator as $value): ?>
        <?php $user = Engine_Api::_()->getItem('user', $value->user_id); ?>
        <div class="item_list">
          <div class="item_list_thumb">
            <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'), array('title' => $user->getTitle(), 'target' => '_parent')); ?>
          </div>
          <div class="item_list_info">
            <div class="item_list_title">
              <?php echo $this->htmlLink($user->getHref(), $user->getTitle(), array('title' => $user->getTitle(), 'target' => '_parent')); ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?> 
      <?php endif; ?>     
    <?php if (!empty($this->paginator) && $this->paginator->count() > 1 && empty($this->viewmore)): ?>
      <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
        <div class="sesbasic_view_more sesbasic_load_btn" id="view_more" onclick="viewMore();" >
          <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => 'feed_viewmore_link', 'class' => 'sesbasic_animation sesbasic_link_btn fa fa-sync')); ?>
        </div>
        <div class="sesbasic_view_more_loading" id="loading_image" style="display: none;">
         <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span>
          <?php echo $this->translate("Loading ...") ?>
        </div>
  <?php endif; ?>
     </div>
    </div>
<?php endif; ?>
<script type="text/javascript">
  function smoothboxclose() {
    parent.Smoothbox.close();
  }
</script>
