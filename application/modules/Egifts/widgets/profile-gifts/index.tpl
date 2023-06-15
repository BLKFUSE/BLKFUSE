<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php @$randonNumber = @$randonNumber ? @$randonNumber : $this->widgetId; ?>
<?php if(!$this->is_ajax){ ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/styles.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/scripts/core.js'); ?>

<div class="egifts_listing_container egifts_browse_listing sesbasic_bxs">
  <?php } ?>
  <?php if($this->params['show_item_count']){ ?>
  <div class="sesbasic_clearfix sesbm egift_search_result egifts_browse_listing_count" style="display:<?php !$this->is_ajax ? 'block' : 'none'; ?>" id="<?php echo !$this->is_ajax ? 'paginator_count_egift' : 'paginator_count_ajax_egift_entry' ?>"><span id="total_item_count_egift_entry" style="display:inline-block;"><?php echo $this->paginator->getTotalItemCount(); ?></span> <?php echo $this->paginator->getTotalItemCount() == 1 ?  $this->translate("Gift found.") : $this->translate("Gifts found."); ?></div>
  <?php } ?>
  <?php if(!$this->is_ajax){ ?>
  <div class="egifts_listing" id="egift-browse-widget_<?php echo $randonNumber; ?>">
    <?php } ?>
    <div class="row">
      <?php foreach($this->paginator as $order): ?>
      <?php $item = Engine_Api::_()->getItem('egifts_gift', $order->gift_id); ?>
      <?php if(isset($this->params['title_truncation'])):?>
      <?php $titleLimit = $this->params['title_truncation'];?>
      <?php endif;?>
      <?php if(strlen($item->getTitle()) > $titleLimit):?>
      <?php $title = mb_substr($item->getTitle(),0,$titleLimit).'...';?>
      <?php else:?>
      <?php $title = $item->getTitle();?>
      <?php endif; ?>
      <?php if(isset($this->params['description_truncation'])):?>
      <?php $descriptionLimit = $this->params['description_truncation'];?>
      <?php endif;?>
      <?php if(strlen($item->getTitle()) > $descriptionLimit):?>
      <?php $description = mb_substr($item->description,0,$descriptionLimit).'...';?>
      <?php else:?>
      <?php $description = $item->description;?>
      <?php endif; ?>
      <div class="col-lg-4 col-md-6 col-sm-6 col-12">
        <div class="egifts_listing_item">
          <article class="sesbasic_bg sesbasic_animation">
            <?php if(isset($this->imageActive)){ ?>
            <div class="egifts_listing_item_thumb" style="height:<?php echo is_numeric($this->params['height']) ? $this->params['height'].'px' : $$this->params['height']; ?>;"> <a href="<?php echo $item->getHref(); ?>"><img src="<?php echo $item->getPhotoUrl(); ?>" alt="" /></a> </div>
            <?php } ?>
            <div class="egifts_listing_item_info">
              <?php if(isset($this->titleActive)){ ?>
              <div class="egifts_listing_item_title"><a href="<?php echo $item->getHref(); ?>"><?php echo $title; ?></a></div>
              <?php } ?>
              <?php if(isset($this->priceActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('egifts.enable.price', 1)){ ?>
              <div class="egifts_listing_item_price"> <span class="_price sesbasic_text_hl"><?php echo Engine_Api::_()->egifts()->getCurrencyPrice($item->price); ?></span> </div>
              <?php } ?>
              <?php if(isset($this->descriptionActive)): ?>
              <div class="egifts_listing_item_des"> <?php echo $description; ?> </div>
              <?php endif; ?>
              <?php if(isset($this->displayMsgActive) || isset($this->sendByActive)): ?>
              <div class="egifts_listing_item_user_info">
                <?php if(isset($this->sendByActive) && (($order->is_private == 1  && $this->receipent == 1) || $order->is_private == 0)): ?>
                <?php $sender = Engine_Api::_()->getItem('user', $order->sender_id); ?>
                <div class="egifts_listing_item_user sesbasic_text_light"> from <?php echo $sender; ?> </div>
                <?php endif; ?>
                <?php if(isset($this->displayMsgActive) && (($order->is_private == 1  && $this->receipent == 1) || $order->is_private == 0)): ?>
                <div class="egifts_listing_item_msg"> <?php echo $order->message; ?> </div>
                <?php endif; ?>
              </div>
              <?php endif; ?>
            </div>
          </article>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if($this->params['pagging'] == 'pagging'): ?>
    <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "egifts"),array('identityWidget'=>$randonNumber)); ?>
    <?php endif; ?>
    <?php if(!$this->is_ajax){ ?>
  </div>
</div>
<?php if($this->params['pagging'] != 'pagging'): ?>
<div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><span><?php echo $this->translate('View More');?></span></a> </div>
<div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
<?php endif;?>
<?php } ?>
<script type="text/javascript">
  var defaultOpenTab = '<?php echo $this->defaultOpenTab; ?>';
  var requestViewMore_<?php echo $randonNumber; ?>;
  var params<?php echo $randonNumber; ?> = <?php echo json_encode($this->params); ?>;
  var identity<?php echo $randonNumber; ?>  = '<?php echo $randonNumber; ?>';
  var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
  var searchParams<?php echo $randonNumber; ?> ;
  var is_search_<?php echo $randonNumber;?> = 0;
 <?php if($this->params['pagging'] == 'auto_load'){ ?>
    scriptJquery( window ).load(function() {
      scriptJquery(window).scroll( function() {
        var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#egift-browse-widget_<?php echo $randonNumber; ?>').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
          document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
        }
      });
    });
  <?php } ?>

  <?php if($this->params['pagging'] != 'pagging'){ ?>
    viewMoreHide_<?php echo $randonNumber; ?>();  
    function viewMoreHide_<?php echo $randonNumber; ?>() {
      if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
    }
    function viewMore_<?php echo $randonNumber; ?> (){
      scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
      if(!isSearch)
        scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
      var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';  
      requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
        method: 'post',
        dataType: 'html',
        url: en4.core.baseUrl + "widget/index/mod/egifts/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
        data: {
          format: 'html',
          page: page<?php echo $randonNumber; ?>,    
          params : params<?php echo $randonNumber; ?>, 
          is_ajax : 1,
          is_search:is_search_<?php echo $randonNumber;?>,
          view_more:1,
          identity : '<?php echo $randonNumber; ?>',
          searchParams:searchParams<?php echo $randonNumber; ?> ,
          widget_id: '<?php echo $this->widgetId;?>',
        },
        success: function(responseHTML) { 
          if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
            scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
          if(document.getElementById('loadingimgegifts-wrapper'))
            scriptJquery('#loadingimgegifts-wrapper').hide();
          if(!isSearch) {
            scriptJquery('#egift-browse-widget_<?php echo $randonNumber; ?>').append(responseHTML);
          }
          else { 
            scriptJquery('#egift-browse-widget_<?php echo $randonNumber; ?>').append(responseHTML);
            isSearch = false;
          }
          var totalegifts = scriptJquery('#egift-browse-widget_<?php echo $randonNumber; ?>').find("#paginator_count_ajax_egift_entry"); 
          scriptJquery('.sesbasic_view_type_<?php echo $randonNumber; ?>').find('#paginator_count_egift').html(totalegifts.html());
          totalegifts.remove();
          document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
        }
      });
      return false;
    }
  <?php }else{ ?>
    function paggingNumber<?php echo $randonNumber; ?>(pageNum){
      scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
      var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
      requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
        method: 'post',
        dataType: 'html',
        url: en4.core.baseUrl + "widget/index/mod/egifts/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
        data: {
          format: 'html',
          page: pageNum,    
          params :params<?php echo $randonNumber; ?> , 
          is_ajax : 1,
          searchParams:searchParams<?php echo $randonNumber; ?>,
          widget_id: '<?php echo $this->widgetId; ?>',
        },
        success: function(responseHTML) {
          if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
            scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
          if(document.getElementById('loadingimgegifts-wrapper'))
            scriptJquery('#loadingimgegifts-wrapper').hide();
          scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
          document.getElementById('egift-browse-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
          var totalegifts= scriptJquery('#egift-browse-widget_<?php echo $randonNumber; ?>').find("#paginator_count_ajax_egift_entry"); 
          scriptJquery('.sesbasic_view_type_<?php echo $randonNumber; ?>').find('#paginator_count_egift').html(totalegifts.html());
          totalegifts.remove();
          scriptJquery('html, body').animate({
              scrollTop: scriptJquery("#egift-browse-widget_<?php echo $randonNumber; ?>").offset().top
          }, 500);
        }
      }));
      return false;
    }
  <?php } ?>
   var isSearch = false;
</script> 
