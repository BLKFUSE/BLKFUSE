<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: all-likes.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<script type="text/javascript">

  function loadMore() {
    
    if (document.getElementById('load_more'))
      document.getElementById('load_more').style.display = "<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>";
      
    document.getElementById('load_more').style.display = 'none';
    document.getElementById('underloading_image').style.display = '';
  
    var id = '<?php echo $this->id; ?>';
    var type = '<?php echo $this->type; ?>';
    var showUsers = '<?php echo $this->showUsers; ?>';
    
    en4.core.request.send(scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + 'sesmusic/index/all-likes/id/' + id + '/type/' + type + '/showUsers/' + showUsers,
      'data': {
        format: 'html',
        page: "<?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>",
        viewmore: 1        
      },
      success: function(responseHTML) {
        scriptJquery('#results_data').append(responseHTML);
        scriptJquery('#load_more').remove();
        document.getElementById('underloading_image').style.display = 'none';
      }
    }));
    return false;
  }
</script>

<?php if (empty($this->viewmore)): ?>
  <div class="sesbasic_items_listing_popup">
    <div class="sesbasic_items_listing_header">
      <?php if($this->showUsers == 'all'): ?>
        <?php if($this->type == 'sesmusic_album'): ?>
          <?php echo $this->translate('Members Who Like This Music Album') ?>
        <?php else: ?>
          <?php echo $this->translate('Members Who Liked This Song') ?>
        <?php endif; ?>
        
      <?php else: ?>
        <?php echo $this->translate('Friends Likes') ?>
      <?php endif; ?>
      <a class="fa fa-close" href="javascript:;" onclick='smoothboxclose();' title="<?php echo $this->translate('Close') ?>"></a>
    </div>
    <div class="sesbasic_items_listing_cont" id="results_data">
    <?php endif; ?>

    <?php if (is_countable($this->paginator) && engine_count($this->paginator) > 0) : ?>
      <?php foreach ($this->paginator as $value): ?>
        <?php $user = Engine_Api::_()->getItem('user', $value->poster_id); ?>
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
    <?php else : ?>
      <div class="tip">
        <span>
          <?php echo $this->translate('There are no members yet.'); ?>
        </span>
      </div>
    <?php endif; ?>      
    <?php if (!empty($this->paginator) && $this->paginator->count() > 1): ?>
      <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
        <div id="load_more" class="sesbasic_view_more sesbasic_load_btn" onclick="loadMore();" >
					<a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" ><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a>
        </div>
        <div id="underloading_image" class="sesbasic_view_more_loading" style="display: none;">
         <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
      </div>
    </div>
  <?php endif; ?>
<?php endif; ?>
<script type="text/javascript">
  function smoothboxclose() {
    parent.Smoothbox.close();
  }
</script>
