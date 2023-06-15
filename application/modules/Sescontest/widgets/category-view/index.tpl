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
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<?php $randonNumber = $this->widgetId; ?>
<?php $width = $this->params['width'];?>
<?php $height = $this->params['height'];?>
<?php if(!$this->is_ajax){ ?>
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<?php if(isset($this->category->thumbnail) && !empty($this->category->thumbnail)){ ?>
  <div class="sescontest_category_cover sesbasic_bxs sesbm sesbasic_bxs">
    <div class="sescontest_category_cover_inner">
    	<div class="sescontest_category_cover_img" style="background-image:url(<?php echo  Engine_Api::_()->storage()->get($this->category->thumbnail)->getPhotoUrl('thumb.thumb'); ?>);"></div>
      <div class="sescontest_category_cover_content">
        <div class="sescontest_category_cover_breadcrumb">
          <!--breadcrumb -->
          <a href="<?php echo $this->url(array('action' => 'browse'), "sescontest_category"); ?>"><?php echo $this->translate("Categories"); ?></a>&nbsp;&raquo;
          <?php if(isset($this->breadcrumb['category'][0]->category_id)){ ?>
             <?php if($this->breadcrumb['subcategory']) { ?>
              <a href="<?php echo $this->breadcrumb['category'][0]->getHref(); ?>"><?php echo $this->breadcrumb['category'][0]->category_name ?></a>
             <?php }else{ ?>
               <?php echo $this->breadcrumb['category'][0]->category_name ?>
             <?php } ?>
             <?php if($this->breadcrumb['subcategory']) echo "&nbsp;&raquo"; ?>
          <?php } ?>
          <?php if(isset($this->breadcrumb['subcategory'][0]->category_id)){ ?>
            <?php if($this->breadcrumb['subSubcategory']) { ?>
              <a href="<?php echo $this->breadcrumb['subcategory'][0]->getHref(); ?>"><?php echo $this->breadcrumb['subcategory'][0]->category_name ?></a>
            <?php }else{ ?>
              <?php echo $this->breadcrumb['subcategory'][0]->category_name ?>
            <?php } ?>
            <?php if($this->breadcrumb['subSubcategory']) echo "&nbsp;&raquo"; ?>
          <?php } ?>
          <?php if(isset($this->breadcrumb['subSubcategory'][0]->category_id)){ ?>
            <?php echo $this->breadcrumb['subSubcategory'][0]->category_name ?>
          <?php } ?>
        </div>
       
        <div class="sescontest_category_cover_blocks">
          <div class="sescontest_category_cover_block_img">
            <span style="background-image:url(<?php echo  Engine_Api::_()->storage()->get($this->category->thumbnail)->getPhotoUrl(''); ?>);"></span>
          </div>
          <div class="sescontest_category_cover_block_info">
            <?php if(isset($this->category->title) && !empty($this->category->title)): ?>
              <div class="sescontest_category_cover_title"> 
                <?php echo $this->category->title; ?>
              </div>
            <?php endif; ?>
            <?php if(isset($this->category->description) && !empty($this->category->description)): ?>
              <div class="sescontest_category_cover_des clear sesbasic_custom_scroll">
                <p><?php echo nl2br($this->category->description);?></p>
              </div>
            <?php endif; ?>
            <?php if(is_countable($this->paginatorc) && engine_count($this->paginatorc)){ ?>
              <div class="sescontest_category_cover_contests">
              	<div class="sescontest_category_cover_contests_head">
                 	<?php echo $this->params['pop_title']; ?>
                </div>
               	<?php foreach($this->paginatorc as $contestsCri){ ?>
                  <div class="sescontest_category_cover_item sesbasic_animation">
                    <a href="<?php echo $contestsCri->getHref(); ?>" data-src="<?php echo $contestsCri->getGuid(); ?>" class="_thumbimg">
                      <span class="bg_item_photo sesbasic_animation" style="background-image:url(<?php echo $contestsCri->getPhotoUrl('thumb.profile'); ?>);"></span>
                      <div class="_info sesbasic_animation">
                        <div class="_title"><?php echo $contestsCri->getTitle(); ?> </div>
                      </div>
                    </a>
                  </div>
              	<?php }  ?>
              </div>
            <?php	}  ?>
          </div>
        </div>
      </div>
    </div>
  </div>  
<?php } else { ?>
  <div class="sesvide_breadcrumb clear sesbasic_clearfix">
    <!--breadcrumb -->
    <a href="<?php echo $this->url(array('action' => 'browse'), "sescontest_category"); ?>"><?php echo $this->translate("Categories"); ?></a>&nbsp;&raquo;
    <?php if(isset($this->breadcrumb['category'][0]->category_id)){ ?>
       <?php if($this->breadcrumb['subcategory']) { ?>
        <a href="<?php echo $this->breadcrumb['category'][0]->getHref(); ?>"><?php echo $this->breadcrumb['category'][0]->category_name ?></a>
       <?php }else{ ?>
         <?php echo $this->breadcrumb['category'][0]->category_name ?>
       <?php } ?>
       <?php if($this->breadcrumb['subcategory']) echo "&nbsp;&raquo"; ?>
    <?php } ?>
    <?php if(isset($this->breadcrumb['subcategory'][0]->category_id)){ ?>
      <?php if($this->breadcrumb['subSubcategory']) { ?>
        <a href="<?php echo $this->breadcrumb['subcategory'][0]->getHref(); ?>"><?php echo $this->breadcrumb['subcategory'][0]->category_name ?></a>
      <?php }else{ ?>
        <?php echo $this->breadcrumb['subcategory'][0]->category_name ?>
      <?php } ?>
      <?php if($this->breadcrumb['subSubcategory']) echo "&nbsp;&raquo"; ?>
    <?php } ?>
    <?php if(isset($this->breadcrumb['subSubcategory'][0]->category_id)){ ?>
      <?php echo $this->breadcrumb['subSubcategory'][0]->category_name ?>
    <?php } ?>
  </div>
  <div class="sescontest_browse_cat_top sesbm">
    <?php if(isset($this->category->title) && !empty($this->category->title)): ?>
      <div class="sescontest_catview_title"> 
        <?php echo $this->category->title; ?>
      </div>
    <?php endif; ?>
    <?php if(isset($this->category->description) && !empty($this->category->description)): ?>
      <div class="sescontest_catview_des">
        <?php echo nl2br($this->category->description);?>
      </div>
    <?php endif; ?>
  </div>
  <?php if(is_countable($this->paginatorc) && engine_count($this->paginatorc)){ ?>
  	<div class="clearfix sescontest_category_top_contests sesbasic_bxs">
    	<div class="sescontest_catview_list_title clear sesbasic_clearfix">
      	<span class="_title"><?php echo $this->params['pop_title']; ?></span>
     	</div>
      <ul class="clear sesbasic_clearfix _iscatitem">
      	<?php foreach($this->paginatorc as $contest){ ?>
        	<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/contest/_advgridView.tpl';?>
        <?php }  ?>
      </ul>
    </div>
  <?php	}  ?>
<?php } ?>

<!-- category subcategory -->
<?php if($this->params['show_subcat'] == 1 && engine_count($this->innerCatData)>0){ ?>
	<div class="sescontest_catview_categories sesbasic_clearfix">
    <div class="sescontest_catview_list_title clear sesbasic_clearfix">
      <span class="_title"><?php echo $this->params['subcategory_title']; ?></span>
    </div>
    <div class="sesbasic_clearfix">
      <ul class="sescontest_category_grid_listing sesbasic_clearfix clear sesbasic_bxs">	
        <?php foreach( $this->innerCatData as $contest ): ?>
          <li class="sescontest_category_grid sesbm" style="height:<?php echo is_numeric($this->params['heightSubcat']) ? $this->params['heightSubcat'].'px' : $this->params['heightSubcat'] ?>;width:<?php echo is_numeric($this->params['widthSubcat']) ? $this->params['widthSubcat'].'px' : $this->params['widthSubcat'] ?>;">
            <a href="<?php echo $contest->getHref(); ?>">
              <div class="sescontest_category_grid_img">
                <?php if($contest->thumbnail != '' && !is_null($contest->thumbnail) && intval($contest->thumbnail)){ ?>
                  <span class="sesbasic_animation" style="background-image:url(<?php echo  Engine_Api::_()->storage()->get($contest->thumbnail)->getPhotoUrl('thumb.thumb'); ?>);"></span>
                <?php } ?>
              </div>
              <div class="sescontest_category_grid_overlay sesbasic_animation"></div>
              <div class="sescontest_category_grid_info">
                <div>
                  <div class="sescontest_category_grid_details">
                    <?php if(isset($this->iconSubcatActive) && $contest->cat_icon != '' && !is_null($contest->cat_icon) && intval($contest->cat_icon)){ ?>
                      <img src="<?php echo  Engine_Api::_()->storage()->get($contest->cat_icon)->getPhotoUrl('thumb.icon'); ?>" />
                    <?php } ?>
                    <?php if(isset($this->titleSubcatActive)){ ?>
                    <span><?php echo $contest->category_name; ?></span>
                    <?php } ?>
                    <?php if(isset($this->countContestsSubcatActive)){ ?>
                      <span class="sescontest_category_grid_stats"><?php echo $this->translate(array('%s contest', '%s contests', $contest->total_contests_categories), $this->locale()->toNumber($contest->total_contests_categories))?></span>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
     </div>
   </div>
<?php } ?>  
<div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">    
  <div class="sescontest_catview_list_title clear sesbasic_clearfix">
    <span class="_title"><?php echo $this->params['contest_title']; ?></span>
  </div>
   <ul class="sescontest_cat_contest_listing sesbasic_clearfix clear _iscatitem row" id="tabbed-widget_<?php echo $randonNumber; ?>">
<?php } ?>
    <?php $totalCount = $this->paginator->getCurrentItemCount();?>
    <?php foreach($this->paginator as $contest){  ?>
    <?php $title = $contest->getTitle();?>
     <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/contest/_advgridView.tpl';?>
    <?php } ?>   
    <?php  if(  $totalCount == 0){  ?>
      <div class="tip">
        <span>
        	<?php echo $this->translate("No contests in this  category."); ?>
          <?php if (!$this->can_edit):?>
                    <?php echo $this->translate('Be the first to %1$spost%2$s one in this category!', '<a href="'.$this->url(array('action' => 'create'), "sescontest_general").'">', '</a>'); ?>
          <?php endif; ?>
        </span>
      </div>
    <?php } ?>    
    <?php if($this->params['pagging'] == 'pagging'){ ?>
      <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sescontest"),array('identityWidget'=>$randonNumber)); ?>
    <?php } ?>
<?php if(!$this->is_ajax){ ?> 
 </ul>
 </div>
 <?php if($this->params['pagging'] != 'pagging'){ ?>  
   <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > 
  	<a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>   
  </div>
  <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> </div>  
  <?php } ?>
  <script type="application/javascript">
function paggingNumber<?php echo $randonNumber; ?>(pageNum){
	 scriptJquery('.overlay_<?php echo $randonNumber ?>').css('display','block');
	 var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
    (scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sescontest/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
      'data': {
        format: 'html',
        page: pageNum,    
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				widget_id : '<?php echo $randonNumber; ?>',
				widget_id: '<?php echo $this->widgetId;?>',
                category_id:'<?php echo $this->category_id;?>',
      },
      success: function(responseHTML) {
				scriptJquery('.overlay_<?php echo $randonNumber ?>').css('display','none');
        document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
				dynamicWidth();
      }
    }));
    return false;
}
</script>
  <?php } ?>
<script type="text/javascript">
var valueTabData ;
// globally define available tab array
	var availableTabs_<?php echo $randonNumber; ?>;
	var requestTab_<?php echo $randonNumber; ?>;
  availableTabs_<?php echo $randonNumber; ?> = <?php echo json_encode($this->defaultOptions); ?>;
<?php if($this->params['pagging'] == 'auto_load'){ ?>
		scriptJquery( window ).load(function() {
		 scriptJquery(window).scroll( function() {
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
<?php } ?>
var defaultOpenTab ;
  viewMoreHide_<?php echo $randonNumber; ?>();
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function viewMore_<?php echo $randonNumber; ?> (){
    var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
    (scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sescontest/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
      'data': {
        format: 'html',
        page: <?php echo $this->page + 1; ?>,    
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
                widget_id: '<?php echo $this->widgetId;?>',
                category_id:'<?php echo $this->category_id;?>',
      },
      success: function(responseHTML) {
        scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
				document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
				dynamicWidth();
      }
    }));
    return false;
  }
<?php if(!$this->is_ajax){ ?>
function dynamicWidth(){
	var objectClass = scriptJquery('.sescontest_cat_contest_list_info');
	for(i=0;i<objectClass.length;i++){
			scriptJquery(objectClass[i]).find('div').find('.sescontest_cat_contest_list_content').find('.sescontest_cat_contest_list_title').width(scriptJquery(objectClass[i]).width());
	}
}
dynamicWidth();
<?php } ?>
</script>
