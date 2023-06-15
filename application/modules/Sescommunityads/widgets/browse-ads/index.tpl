<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/core.js'); ?>
<?php $randonNumber = $this->widgetId; ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>
<?php 
  $baseURL = $this->layout()->staticBaseUrl;
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js'); 
?>
<?php if(empty($this->is_ajax)){ ?>
<div class="sescmads_browse_ads_listing sesbasic_bxs">
	<ul class="sescmads_ads_listing" id="sescomm_widget_<?php echo $randonNumber; ?>">
<?php } ?>
<?php foreach($this->paginator as $ad){ ?>
  <?php if($ad->type == "promote_content_cnt" || $ad->type == "promote_website_cnt"){ 
          if($ad->user_id != $this->viewer()->getIdentity()){
            $adsItem = Engine_Api::_()->getItem('sescommunityads',$ad->getIdentity());
            $adsItem->views_count++;
            $adsItem->save();
            
            $campaign = Engine_Api::_()->getItem('sescommunityads_campaign',$adsItem->campaign_id);
            $campaign->views_count++;
            $campaign->save();
            
            //insert in view table
            Engine_Api::_()->getDbTable('viewstats','sescommunityads')->insertrow($adsItem,$this->viewer());
            //insert campaign stats
            Engine_Api::_()->getDbTable('campaignstats','sescommunityads')->insertrow($adsItem,$this->viewer(),'view');
          } 
  ?>
  <?php include('application/modules/Sescommunityads/views/scripts/widget-data/_promoteContent.php'); ?>
  <?php } ?>
<?php } ?>

<?php if(!$this->paginator->count()){ ?>
    <li>
      <div class="tip">
        <span><?php echo $this->translate('There are no results that match your search. Please try again.'); ?></span>
      </div>
    </li>
<?php  } ?>
<?php if($this->loadType == 'pagging'): ?>
      <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sescommunityads"),array('identityWidget'=>$randonNumber)); ?>
    <?php endif;?>
<?php if(empty($this->is_ajax)){ ?>
	</ul>
  <?php if($this->loadType != 'pagging'){ ?>
      <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" >
        <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
      </div>  
      <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
  <?php } ?>
</div>
<?php  } ?>
<script type="text/javascript">
  displayCommunityadsCarousel();
  <?php if(empty($this->is_ajax)){ ?>
    <?php if($this->loadType == 'auto_load'){ ?>
      scriptJquery( window ).load(function() {
        scriptJquery(window).scroll( function() {
          var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#sescomm_widget_<?php echo $randonNumber; ?>').offset().top;
          var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
          if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
            document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
          }
        });
      });
    <?php } ?>
  <?php } ?>
  var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
  var is_search_<?php echo $randonNumber;?> = false;
  var searchParams<?php echo $randonNumber; ?>;
  <?php if($this->loadType != 'pagging'){ ?>
    viewMoreHide_<?php echo $randonNumber; ?>();	
    function viewMoreHide_<?php echo $randonNumber; ?>() {
      if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
    }
    function viewMore_<?php echo $randonNumber; ?> (){
      scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
      scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
      var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';  
      requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
        dataType: 'html',
        method: 'post',
        'url': en4.core.baseUrl + "widget/index/mod/sescommunityads/identityWidget/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>/",
        'data': {
          format: 'html',
          page: page<?php echo $randonNumber; ?>,
          is_ajax : 1,
          is_search:is_search_<?php echo $randonNumber;?>,
          view_more:1,
          identityWidget:'<?php echo $randonNumber;?>',
          searchParams:searchParams<?php echo $randonNumber; ?> ,
          pagging:'<?php echo $this->loadType;?>',
          category: '<?php echo !empty($this->category) ? $this->category : "";?>',
          locationEnable : '<?php echo $this->locationEnable; ?>',
          limit:'<?php echo $this->limit; ?>',
          featured_sponsored:<?php echo $this->featured_sponsored; ?>
        },
        success: function(responseHTML) {

          if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
            scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
          
          scriptJquery('#loadingimgsescommunityads-wrapper').hide();
          scriptJquery('#sescomm_widget_<?php echo $randonNumber; ?>').append(responseHTML);
          
          document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
          displayCommunityadsCarousel();
        }
      });
      
      return false;
    }
  <?php }else{ ?>
    function paggingNumber<?php echo $randonNumber; ?>(pageNum){
      scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
      var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
      requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
        dataType: 'html',
        method: 'post',
        'url': en4.core.baseUrl + "widget/index/mod/sescommunityads/identityWidget/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>",
        'data': {
          page: pageNum,
          is_ajax : 1,
          is_search:is_search_<?php echo $randonNumber;?>,
          view_more:1,
          identityWidget:'<?php echo $randonNumber;?>',
          searchParams:searchParams<?php echo $randonNumber; ?> ,
          pagging:'<?php echo $this->loadType;?>',
          category: '<?php echo !empty($this->category) ? $this->category : "";?>',
          locationEnable : '<?php echo $this->locationEnable; ?>',
          limit:'<?php echo $this->limit; ?>',
          featured_sponsored:<?php echo $this->featured_sponsored; ?>
        },
        success: function(responseHTML) {
          if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
            scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
          scriptJquery('#loadingimgsescommunityads-wrapper').hide();
          scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');          
          scriptJquery('#sescomm_widget_<?php echo $randonNumber; ?>').html(responseHTML);
          displayCommunityadsCarousel();
          //scriptJquery('html, body').animate({
          //    scrollTop: scriptJquery('#sescomm_widget_<?php echo $randonNumber; ?>').offset().top
          //}, 500);
        }
      }));
      
      return false;
    }
  <?php } ?>
  
</script>
<?php if(!empty($this->is_ajax)) die;
