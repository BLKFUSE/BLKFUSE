<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php if(empty($this->viewmore)){ ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egames/externals/styles/styles.css'); ?>

<div class="sesbasic_bxs">
  <div class="egames_manage_listing" id="egames_listing">
<?php } ?>

<?php 
  if(engine_count($this->paginator)){
foreach($this->paginator as $game){ ?>
    <div class="egames_manage_listing_item">
      <div class="egames_manage_listing_item_thumb">
        <a href="<?php echo $game->getHref(); ?>"><img src="<?php echo $game->getPhotoUrl(); ?>" alt="<?php echo $game->getTitle(); ?>" /></a>
      </div>
      <div class="egames_manage_listing_item_info">
        <div class="_title"><a href="<?php echo $game->getHref(); ?>"><?php echo $game->getTitle(); ?></a></div>
        <div class="_stats">
          <div><i class="far fa-thumbs-up"></i><span><?php echo $this->translate(array('%s like', '%s likes', $game->like_count), $this->locale()->toNumber($game->like_count))?></span></div>
          <div><i class="far fa-comment"></i><span><?php echo $this->translate(array('%s comment', '%s comments', $game->comment_count), $this->locale()->toNumber($game->comment_count))?></span></div>
          <div><i class="far fa-play-circle"></i><span><?php echo $this->translate(array('%s play', '%s played', $game->play_count), $this->locale()->toNumber($game->play_count))?></span></div>
        </div>
      </div>
              <?php if(Engine_Api::_()->authorization()->isAllowed($game, null, 'edit') || Engine_Api::_()->authorization()->isAllowed($game, null, 'delete')){ ?>

      <div class="egames_manage_listing_item_options">
        <a href="javascript:void(0);" class="sesbasic_pulldown_toggle">
          <i><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><g><circle cx="256" cy="256" r="64"></circle><circle cx="256" cy="448" r="64"></circle><circle cx="256" cy="64" r="64"></circle></g></g></g></svg></i>
        </a>
        <div class="sesbasic_pulldown_options">
          <ul>
          <?php if(Engine_Api::_()->authorization()->isAllowed($game, null, 'edit')){ ?>
            <li><a href="<?php echo $this->url(array('action' => 'edit','game_id'=>$game->game_id), 'egames_specific'); ?>" class="sesbasic_icon_edit">Edit</a></li>
            <?php } ?>
                <?php if(Engine_Api::_()->authorization()->isAllowed($game, null, 'delete')){ ?>
            <li><a href="<?php echo $this->url(array('action' => 'delete','game_id'=>$game->game_id), 'egames_specific'); ?>" class="sesbasic_icon_delete openSmoothbox">Delete</a></li>
           <?php } ?>
          </ul>
        </div>


      </div>
<?php } ?>
    </div>
<?php } ?>
<?php }else{ ?>
<div class="tip">
        <span>
          <?php echo $this->translate('No game created yet.'); ?>
          <?php if( $this->canCreate ): ?>
            <?php echo $this->translate(' %1$sCreate here%2$s to create.', '<a href="'.$this->url(array('action' => 'create'), 'egames_general').'">', '</a>'); ?>
          <?php endif; ?>
        </span>
      </div>
<?php } ?>
<?php if(empty($this->viewmore)){ ?>
  </div>
  <div class="clr" id="loadmore_list_my"></div>
      <div class="sesbasic_view_more_my sesbasic_load_btn" id="view_more_my" onclick="loadMoreGames();" style="display: none;">
        <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" ><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a>
      </div>
      <div class="sesbasic_view_more_my_loading sesbasic_load_btn" id="loading_image_my" style="display: none;">
        <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span>
      </div>
</div>
<?php } ?>

<script type="text/javascript">


var pageGame = '<?php echo $this->page + 1; ?>';
en4.core.runonce.add(function() {
    viewMoreHide_();
  });
  function viewMoreHide_() {
    if (document.getElementById('view_moremy'))
      document.getElementById('view_moremy').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function loadMoreGames() {
    if (document.getElementById('view_moremy'))
      document.getElementById('view_moremy').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";

    if(document.getElementById('view_more_my'))
      document.getElementById('view_more_my').style.display = 'none';
    
    
    if(document.getElementById('loading_image_my'))
     document.getElementById('loading_image_my').style.display = '';  

    en4.core.request.send(scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + 'widget/index/mod/egames/name/manage-games',
      'data': {
        format: 'html',
        page:pageGame,
        viewmore:1
      },
      success: function(responseHTML) {
        scriptJquery("#loadingimgegames-wrapper").hide();
        scriptJquery('#egames_listing').append(responseHTML);
        viewMoreHide_();
        if(document.getElementById('loading_image_my'))
          document.getElementById('loading_image_my').style.display = 'none';
      }
    }));
    return false;
  }
</script>
