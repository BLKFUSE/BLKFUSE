<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: review-user-rating.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<script type="text/javascript">

  function viewMore() {
    
    if (document.getElementById('view_more'))
    document.getElementById('view_more').style.display = "<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>"; 
      
    document.getElementById('view_more').style.display = 'none';
    document.getElementById('loading_image').style.display = '';
  
    var user_id = '<?php echo $this->user_id; ?>';
    var rating = '<?php echo $this->rating; ?>';
    (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + 'sesmember/index/review-user-rating/rating_id/' + rating+'/user_id/'+user_id,
      'data': {
        format: 'html',
        page: "<?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>",
        viewmore: 1        
      },
      success: function(responseHTML) {
        scriptJquery('#item_results').append(responseHTML);
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
         <?php echo $this->translate('') ?>
      <a class="fa fa-times" href="javascript:;" onclick='smoothboxclose();' title="<?php echo $this->translate('Close') ?>"></a>
    </div>
    <div class="sesbasic_items_listing_cont" id="item_results">
<?php endif; ?>

    <?php if (is_countable($this->paginator) && engine_count($this->paginator) > 0) : ?>
      <?php foreach ($this->paginator as $value): ?>
        <?php $user = Engine_Api::_()->getItem('user', $value->user_id); ?>
        <div class="item_list">
          <div class="item_list_thumb">
            <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.icon'), array('class'=>'ses_tooltip','id'=>'member_title_'.$value->getGuid(),'data-src'=>$value->getGuid(),'title' => $user->getTitle(), 'target' => '_parent')); ?>
          </div>
          <div class="item_list_info">
            <div class="item_list_title">
              <?php echo $this->htmlLink($user->getHref(), $user->getTitle(), array('class'=>'ses_tooltip','id'=>'member_title_'.$value->getGuid(),'data-src'=>$value->getGuid(),'title' => $user->getTitle(), 'target' => '_parent')); ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?> 
      <?php endif; ?>     
      
    <?php if (!empty($this->paginator) && $this->paginator->count() > 1 && empty($this->viewmore)): ?>
      <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
        <div class="sesbasic_load_btn" id="view_more" onclick="viewMore();" >
          <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a>
        </div>
        <div class="sesbasic_load_btn" id="loading_image" style="display: none;">
          <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span>
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
