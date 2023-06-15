<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<script type="text/javascript">

  function viewMore() {
    
    if (document.getElementById('view_more'))
    document.getElementById('view_more').style.display = "<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>"; 
      
    document.getElementById('view_more').style.display = 'none';
    document.getElementById('loading_image').style.display = '';
  
    var id = '<?php echo $this->contest_id; ?>';
    
    (scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sescontest/",
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

<?php if($this->listingType == 'list'):?>
  <?php if (empty($this->viewmore)): ?>
    <div class="sesbasic_sidebar_block sescontest_user_list" id="like_results">
  <?php endif; ?>
  
    <?php if (is_countable($this->paginator) && engine_count($this->paginator) > 0) : ?>
      <?php foreach ($this->paginator as $user): ?>
        <?php $contestCount = Engine_Api::_()->getDbTable('contests','sescontest')->countContests($user->user_id);?>
        <?php $participationCount = Engine_Api::_()->getDbTable('participants','sescontest')->getContestEntries('',$user->user_id);?>
        <div class="sescontest_user_list_item sesbasic_clearfix">
          <div class="sescontest_user_list_item_thumb" style="height:<?php echo is_numeric($this->params['height'])?$this->params['height'].'px':$this->params['height'];?>;width:<?php echo is_numeric($this->params['width'])?$this->params['width'].'px':$this->params['width'];?>;">
            <?php echo $this->htmlLink($user->getHref(), $this->itemBackgroundPhoto($user, 'thumb.profile'), array('title' => $user->getTitle(), 'target' => '_parent')); ?>
          </div>
          <div class="sescontest_user_list_item_info">
            <?php if($this->nameActive):?>
              <div class="sescontest_user_list_item_title">
                <?php echo $this->htmlLink($user->getHref(), $user->getTitle(), array('title' => $user->getTitle(), 'target' => '_parent')); ?>
              </div>
            <?php endif;?>
            <?php if($this->contestCountActive):?>
              <div class="sescontest_user_list_item_stat" title="<?php echo $this->translate('Created Contests Count: ').$contestCount;?>">
                <i class="fa fa-trophy"></i><span><?php echo $this->translate('%s Contests Created', $contestCount)?></span>
              </div>
            <?php endif;?>
            <?php if($this->participationCountActive):?>
              <div class="sescontest_user_list_item_stat" title="<?php echo $this->translate('Participating Contests Count: ').$participationCount;?>">
                <i class="fa fa-sign-in-alt"></i><span><?php echo $this->translate('%s Contests Participated', $participationCount)?></span>	
              </div>
            <?php endif;?>
          </div>
        </div>
      <?php endforeach; ?> 
    <?php endif;?>     
      
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
    <?php endif; ?> 
  <?php if (empty($this->viewmore)): ?> 
  </div>
  <?php endif; ?>
<?php else:?>
  <?php if (empty($this->viewmore)): ?>
    <div class="sescontest_user_grid_listing sesbasic_bxs sesbasic_clearfix" id="like_results">
  <?php endif; ?>
  
    <?php if (is_countable($this->paginator) && engine_count($this->paginator) > 0) : ?>
      <?php foreach ($this->paginator as $user): ?>
        <?php $contestCount = Engine_Api::_()->getDbTable('contests','sescontest')->countContests($user->user_id);?>
        <?php $participationCount = Engine_Api::_()->getDbTable('participants','sescontest')->getContestEntries('',$user->user_id);?>
        <div class="sescontest_user_grid_item sesbasic_clearfix" style="width:<?php echo is_numeric($this->params['width'])?$this->params['width'].'px':$this->params['width'];?>;">
          <article>
            <div class="_thumb" style="height:<?php echo is_numeric($this->params['height'])?$this->params['height'].'px':$this->params['height'];?>;">
              <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user, 'thumb.profile'), array('title' => $user->getTitle(), 'target' => '_parent')); ?>
            </div>
            <div class="_info">
              <?php if($this->nameActive):?>
                <div class="_title">
                  <?php echo $this->htmlLink($user->getHref(), $user->getTitle(), array('title' => $user->getTitle(), 'target' => '_parent')); ?>
                </div>
              <?php endif;?>
              <div class="_stats">
                <?php if($this->contestCountActive):?>
                  <span title="<?php echo $this->translate('Created Contests Count: ').$contestCount;?>">
                    <i class="fa fa-trophy"></i><span><?php echo $this->translate('%s Created Contests', $contestCount)?></span>
                  </span>
                <?php endif;?>
                <?php if($this->participationCountActive):?>
                  <span title="<?php echo $this->translate('Participating Contests Count: ').$participationCount;?>">
                    <i class="fa fa-sign-in-alt"></i><span><?php echo $this->translate('%s Participated Contests', $participationCount)?></span>
                  </span>
                <?php endif;?>
              </div>
            </div>
          </article>
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
    <?php endif; ?> 
  <?php if (empty($this->viewmore)): ?> 
  </div>
  <?php endif; ?>
<?php endif;?>
