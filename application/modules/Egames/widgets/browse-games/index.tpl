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
  <div class="egames_listing" id="egames_listing">
<?php } ?>


  <?php foreach($this->paginator as $game){ ?>
    <div class="egames_listing_item">
      <article>
        <div class="egames_listing_item_thumb">
          <a href="<?php echo $game->getHref(); ?>"><img src="<?php echo $game->getPhotoUrl(); ?>" alt="<?php echo $game->getTitle(); ?>" /></a>
        </div>
        <div class="egames_listing_item_info">
          <div class="_title">
            <a href="<?php echo $game->getHref(); ?>"><?php echo $game->getTitle(); ?></a>
          </div>
        </div>
      </article>
    </div>
    <?php } ?>

<?php if(!engine_count($this->paginator)){?>
    <?php if($this->search){ ?>
      <div class="tip">
        <span>
          <?php echo $this->translate('There are no results that match your search criteria.') ?>
        </span>
      </div>
    <?php }else{ ?>
      <div class="tip">
        <span>
          <?php echo $this->translate('Nobody has created a game yet.'); ?>
          <?php if( $this->canCreate ): ?>
            <?php echo $this->translate('Be the first to %1$swrite%2$s one!', '<a href="'.$this->url(array('action' => 'create'), 'egames_general').'">', '</a>'); ?>
          <?php endif; ?>
        </span>
      </div>
    <?php } ?>
<?php } ?>
<?php if(empty($this->viewmore)){ ?>
      
  </div>
  <div class="clr" id="loadmore_list_my"></div>
  <div class="sesbasic_view_more_my sesbasic_load_btn" id="view_more_my" onclick="loadMoreGames();" style="display: block;">
    <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" ><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a>
  </div>
  <div class="sesbasic_view_more_my_loading sesbasic_load_btn" id="loading_image_my" style="display: none;">
    <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span>
  </div>
</div>
<?php } ?>

<script type="text/javascript">

<?php if(empty($this->viewmore)){ ?>
var searchParamsGames = "<?php echo !empty($this->searchData) ? $this->searchData : ''; ?>";
<?php } ?>
var pageGame = '<?php echo $this->page + 1; ?>';
en4.core.runonce.add(function() {
    viewMoreHide_();
  });
  function viewMoreHide_() {
    if (document.getElementById('view_moremy'))
      document.getElementById('view_moremy').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function loadMoreGames(isRemove) {
    if (document.getElementById('view_moremy'))
      document.getElementById('view_moremy').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";

    if(document.getElementById('view_more_my'))
      document.getElementById('view_more_my').style.display = 'none';
    
    
    if(document.getElementById('loading_image_my'))
     document.getElementById('loading_image_my').style.display = '';

    if(isRemove){
      document.getElementById('egames_listing').innerHTML = "";
    }

    en4.core.request.send(scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + 'widget/index/mod/egames/name/browse-games',
      'data': {
        format: 'html',
        page:pageGame,
        searchParamsGames:searchParamsGames,
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
