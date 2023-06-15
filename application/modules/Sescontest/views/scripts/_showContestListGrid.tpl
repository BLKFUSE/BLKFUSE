<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _showContestListGrid.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $randonNumber = $this->widgetId; ?>
<?php $widgetType = 'profile-contest';?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $viewerId = $viewer->getIdentity();?>
<?php if(!$this->is_ajax){ ?>
  <style>
  .displayFN{display:none !important;}
  </style>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
  <?php if(is_array($this->optionsEnable) && engine_in_array('pinboard',$this->optionsEnable)):?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sesbasic/externals/scripts/imagesloaded.pkgd.js');?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/wookmark.min.js');?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/pinboardcomment.js');?>
  <?php endif;?>
  <div id="browse-widget_<?php echo $randonNumber;?>" class="sesbasic_view_type_<?php echo $randonNumber;?> sesbasic_view_type sesbasic_clearfix clear">
<?php } ?>
    <?php if(isset($this->params['show_item_count']) && $this->params['show_item_count']){ ?>
        <div class="sesbasic_clearfix sesbm sescontest_search_result" style="display:<?php !$this->is_ajax ? 'block' : 'none'; ?>" id="<?php echo !$this->is_ajax ? 'paginator_count_sescontest' : 'paginator_count_ajax_sescontest_entry' ?>"><span id="total_item_count_sescontest_entry" style="display:inline-block;"><?php echo $this->paginator->getTotalItemCount(); ?></span> <?php echo $this->paginator->getTotalItemCount() == 1 ?  $this->translate("contest found.") : $this->translate("contests found."); ?></div>
    <?php } ?>
<?php if(!$this->is_ajax){ ?>
    <div class="sesbasic_view_type_options sesbasic_view_type_options_<?php echo $randonNumber; ?>">
      <?php if(is_array($this->optionsEnable) && engine_in_array('list',$this->optionsEnable)){ ?>
        <a href="javascript:;" rel="list" id="sescontest_list_view_<?php echo $randonNumber; ?>" class="listicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'list') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('List View') : '' ; ?>"></a>
      <?php } ?>
      <?php if(is_array($this->optionsEnable) && engine_in_array('grid',$this->optionsEnable)){ ?>
        <a href="javascript:;" rel="grid" id="sescontest_grid_view_<?php echo $randonNumber; ?>" class="gridicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'grid') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('Grid View') : '' ; ?>"></a>
      <?php } ?>
      <?php if(is_array($this->optionsEnable) && engine_in_array('advgrid',$this->optionsEnable)){ ?>
        <a href="javascript:;" rel="advgrid" id="sescontest_advgrid_view_<?php echo $randonNumber; ?>" class="a-gridicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'advgrid') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('Advanced Grid View') : '' ; ?>"></a>
      <?php } ?>
      <?php if(is_array($this->optionsEnable) && engine_in_array('pinboard',$this->optionsEnable)){ ?>
        <a href="javascript:;" rel="pinboard" id="sescontest_pinboard_view_<?php echo $randonNumber; ?>" class="boardicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'pinboard') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('Pinboard View') : '' ; ?>"></a>
      <?php } ?>
    </div>
  </div>
<?php } ?>
<?php if(!isset($this->bothViewEnable) && !$this->is_ajax){ ?>
  <script type="text/javascript">
      en4.core.runonce.add(function() {
          scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').addClass('displayFN');
          scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').parent().parent().css('border', '0px');
      });
  </script>
 <?php } ?>
<?php if(!$this->is_ajax){ ?>
<div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">
  <div class="sescontest_contest_listing sesbasic_clearfix clear <?php if($this->view_type == 'grid' || $this->view_type == 'advgrid'):?>row<?php endif;?> <?php if($this->view_type == 'pinboard'):?>sesbasic_pinboard_<?php echo $randonNumber;?><?php endif;?>" id="tabbed-widget_<?php echo $randonNumber; ?>" style="min-height:50px;">
<?php } ?>
<?php foreach($this->paginator as $contest):?>
  <?php $viewer = Engine_Api::_()->user()->getViewer();?>
  <?php $dateinfoParams['starttime'] = true; ?>
  <?php $dateinfoParams['endtime']  =  true; ?>
  <?php $dateinfoParams['timezone']  =  true; ?>
  <?php if (!empty($contest->category_id)):?>
    <?php $category = Engine_Api::_ ()->getDbtable('categories', 'sescontest')->find($contest->category_id)->current();?>
  <?php endif;?> 
  <?php if(isset($this->widgetName) && $this->widgetName == 'manage-contests'):?>
   	<?php $height = $this->params['height'];?>
    <?php $width = $this->params['width'];?>
    <?php $viewTypeClass = "sescontest_list_type";?>
    <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/contest/_managelistView.tpl';?>
  <?php else:?>
   <?php if($this->view_type == 'grid'):?>
      <?php $height = $this->params['height_grid'];?>
      <?php $width = $this->params['width_grid'];?>
      <?php $viewTypeClass = "sescontest_list_type";?>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/contest/_gridView.tpl';?>
    <?php elseif($this->view_type == 'list'):?>
      <?php $height = $this->params['height'];?>
      <?php $width = $this->params['width'];?>
      <?php $viewTypeClass = "sescontest_list_type";?>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/contest/_listView.tpl';?>
    <?php elseif($this->view_type == 'advgrid'):?>
      <?php $height = $this->params['height_advgrid'];?>
      <?php $width = $this->params['width_advgrid'];?>
      <?php $viewTypeClass = "sescontest_list_type sesbasic_animation";?>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/contest/_advgridView.tpl';?>
    <?php elseif($this->view_type == 'pinboard'):?>
      <?php $pinboardWidth = $this->params['width_pinboard'];?>
      <?php $viewTypeClass = "sescontest_list_type";?>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/contest/_pinboardView.tpl';?>
    <?php endif;?>
  <?php endif;?>
  
<?php endforeach;?>
<?php  if(  $this->paginator->getTotalItemCount() == 0):  ?>
  <div id="browse-widget_<?php echo $randonNumber;?>" style="width:100%;">
    <div id="error-message_<?php echo $randonNumber;?>">
      <div class="sesbasic_tip clearfix">
        <img src="<?php echo Engine_Api::_()->sescontest()->getFileUrl(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest_contest_no_photo', 'application/modules/Sescontest/externals/images/contest-icon.png')); ?>" alt="" />
        <span class="sesbasic_text_light">
          <?php echo $this->translate('There are no results that match your search. Please try again.') ?>
        </span>
      </div>
    </div>
  </div>
  <script type="text/javascript">scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').css('display', 'none');</script>
<?php else:?>
  <script type="text/javascript">scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').css('display', 'block');</script>
<?php endif; ?>
<?php if($this->params['pagging'] == 'pagging' && (!isset($this->params['show_limited_data']) || $this->params['show_limited_data'] == 'no')): ?>
  <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sescontest"),array('identityWidget'=>$randonNumber)); ?>
<?php endif;?>
<?php if(!$this->is_ajax){ ?>
  </div>
  <?php if($this->params['pagging'] != 'pagging' && (!isset($this->params['show_limited_data']) || $this->params['show_limited_data'] == 'no')):?>
    <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" >
      <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
    </div>  
    <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
      <?php endif;?>
</div>
<script type="text/javascript">
    var defaultOpenTab = '<?php echo $this->defaultOpenTab; ?>';
    var searchParams<?php echo $randonNumber; ?> ;
    en4.core.runonce.add(function () {
      if(scriptJquery('.sescontest_browse_search').find('#filter_form').length) {
        var search = scriptJquery('.sescontest_browse_search').find('#filter_form');
        searchParams<?php echo $randonNumber; ?> = search.serialize();
      }
    });
    var requestTab_<?php echo $randonNumber; ?>;
    var valueTabData ;
    // globally define available tab array
		<?php if($this->params['pagging'] == 'auto_load' && (!isset($this->params['show_limited_data']) || $this->params['show_limited_data'] == 'no')){ ?>
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
    scriptJquery(document).on('click','.selectView_<?php echo $randonNumber; ?>',function(){
      if(scriptJquery(this).hasClass('active'))
      return;
      if(document.getElementById("view_more_<?php echo $randonNumber; ?>"))
      document.getElementById("view_more_<?php echo $randonNumber; ?>").style.display = 'none';
      document.getElementById("tabbed-widget_<?php echo $randonNumber; ?>").innerHTML = "<div class='clear sesbasic_loading_container'></div>";
      scriptJquery('#sescontest_grid_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sescontest_advgrid_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sescontest_list_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sescontest_pinboard_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display','none');
      scriptJquery('#loading_image_<?php echo $randonNumber; ?>').css('display','none');
      scriptJquery(this).addClass('active');
//       if (typeof(requestTab_<?php echo $randonNumber; ?>) != 'undefined') {
// 	    requestTab_<?php echo $randonNumber; ?>.cancel();
//       }
//       if (typeof(requestViewMore_<?php echo $randonNumber; ?>) != 'undefined') {
// 	    requestViewMore_<?php echo $randonNumber; ?>.cancel();
//       }
      requestTab_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
				method: 'post',
				'url': en4.core.baseUrl + "widget/index/mod/sescontest/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>/openTab/" + defaultOpenTab,
				'data': {
					format: 'html',
					page: 1,
					type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
					is_ajax : 1,
					searchParams: searchParams<?php echo $randonNumber; ?>,
					identity : '<?php echo $randonNumber; ?>',
          widget_id: '<?php echo $this->widgetId;?>',
          getParams:'<?php echo $this->getParams;?>',
          identityObject:'<?php echo isset($this->identityObject) ? $this->identityObject : "" ?>',
          resource_type: '<?php echo !empty($this->resource_type) ? $this->resource_type : "";?>',
          resource_id: '<?php echo !empty($this->resource_id) ? $this->resource_id : "";?>',
				},
				success: function(responseHTML) {
					document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = responseHTML;
          if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid' || 'advgrid') {
            scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
          } else {
            scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
          }
          var totalContest= scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').find("#paginator_count_ajax_sescontest_entry");
          scriptJquery('.sesbasic_view_type_<?php echo $randonNumber; ?>').find('#paginator_count_sescontest').html(totalContest.html());
          totalContest.remove();
					if(document.getElementById("loading_image_<?php echo $randonNumber; ?>"))
					document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
          pinboardLayout_<?php echo $randonNumber ?>('true');
				}
      }));
    });
  </script>
  <?php } ?>
  <?php if(!$this->is_ajax){ ?>
  <script type="application/javascript">
      var wookmark = undefined;
      //Code for Pinboard View
      var wookmark<?php echo $randonNumber ?>;
      function pinboardLayout_<?php echo $randonNumber ?>(force){
          if(scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel') != 'pinboard'){
              scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
              scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').css('height','');
              return;
          }
          scriptJquery('.new_image_pinboard_<?php echo $randonNumber; ?>').css('display','none');
          var imgLoad = imagesLoaded('#tabbed-widget_<?php echo $randonNumber; ?>');
          imgLoad.on('progress',function(instance,image){
              scriptJquery(image.img).parent().parent().parent().parent().parent().show();
              scriptJquery(image.img).parent().parent().parent().parent().parent().removeClass('new_image_pinboard_<?php echo $randonNumber; ?>');
              imageLoadedAll<?php echo $randonNumber ?>(force);
          });
      }
      function imageLoadedAll<?php echo $randonNumber ?>(force){
          scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
          scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard');
          if (typeof wookmark<?php echo $randonNumber ?> == 'undefined' || typeof force != 'undefined') {
              (function() {
                  function getWindowWidth() {
                      return Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
                  }				
                  wookmark<?php echo $randonNumber ?> = new Wookmark('.sesbasic_pinboard_<?php echo $randonNumber; ?>', {
                      itemWidth: <?php echo isset($this->params['width_pinboard']) ? str_replace(array('px','%'),array(''),$this->params['width_pinboard']) : '300'; ?>, // Optional min width of a grid item
                      outerOffset: 0, // Optional the distance from grid to parent
                      align:'left',
                      flexibleWidth: function () {
                          // Return a maximum width depending on the viewport
                          return getWindowWidth() < 1024 ? '100%' : '40%';
                      }
                  });
              })();
          } else {
              wookmark<?php echo $randonNumber ?>.initItems();
              wookmark<?php echo $randonNumber ?>.layout(true);
          }
      }
   scriptJquery(window).resize(function(e){
    pinboardLayout_<?php echo $randonNumber ?>('',true);
   });
      <?php if($this->view_type == 'pinboard'):?>
      en4.core.runonce.add(function () {
              pinboardLayout_<?php echo $randonNumber ?>();
          });
      <?php endif;?>
  </script>
<?php } ?>

<script type="text/javascript">
    var defaultOpenTab = '<?php echo $this->defaultOpenTab; ?>';
    var requestViewMore_<?php echo $randonNumber; ?>;
    var params<?php echo $randonNumber; ?> = <?php echo json_encode($this->params); ?>;
    var identity<?php echo $randonNumber; ?>  = '<?php echo $randonNumber; ?>';
    var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
    var searchParams<?php echo $randonNumber; ?> ;
    var is_search_<?php echo $randonNumber;?> = 0;
    <?php if($this->params['pagging'] != 'pagging'){ ?>
        en4.core.runonce.add(function () {
        viewMoreHide_<?php echo $randonNumber; ?>();
        });
        function viewMoreHide_<?php echo $randonNumber; ?>() {
            if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
            document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
        }
        function viewMore_<?php echo $randonNumber; ?> (){
            scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
            scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
            var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
//             if(typeof requestViewMore_<?php echo $randonNumber; ?> != "undefined"){
//                 requestViewMore_<?php echo $randonNumber; ?>.cancel();
//             }
            requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
                method: 'post',
                'url': en4.core.baseUrl + "widget/index/mod/sescontest/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
                'data': {
                    format: 'html',
                    page: page<?php echo $randonNumber; ?>,    
                    params : params<?php echo $randonNumber; ?>, 
                    is_ajax : 1,
                    is_search:is_search_<?php echo $randonNumber;?>,
                    view_more:1,
                    searchParams:searchParams<?php echo $randonNumber; ?> ,
                    widget_id: '<?php echo $this->widgetId;?>',
                    type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
                    identityObject:'<?php echo isset($this->identityObject) ? $this->identityObject : "" ?>',
                    getParams:'<?php echo $this->getParams;?>',
                    resource_type: '<?php echo !empty($this->resource_type) ? $this->resource_type : "";?>',
                    resource_id: '<?php echo !empty($this->resource_id) ? $this->resource_id : "";?>',
                },
                success: function(responseHTML) {
                    if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
                    scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
                    if($('loadingimgsescontest-wrapper'))
                    scriptJquery('#loadingimgsescontest-wrapper').hide();
                    scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
                    var totalContest= scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').find("#paginator_count_ajax_sescontest_entry");
                    scriptJquery('.sesbasic_view_type_<?php echo $randonNumber; ?>').find('#paginator_count_sescontest').html(totalContest.html());
                    totalContest.remove();
                    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
                    pinboardLayout_<?php echo $randonNumber ?>('true');
                }
            });
            
            return false;
        }
    <?php }else{ ?>
        function paggingNumber<?php echo $randonNumber; ?>(pageNum){
            scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
            var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
//             if(typeof requestViewMore_<?php echo $randonNumber; ?> != "undefined"){
//                 requestViewMore_<?php echo $randonNumber; ?>.cancel();
//             }
            requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
                method: 'post',
                'url': en4.core.baseUrl + "widget/index/mod/sescontest/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
                'data': {
                    format: 'html',
                    page: pageNum,    
                    params :params<?php echo $randonNumber; ?> , 
                    is_ajax : 1,
                    searchParams:searchParams<?php echo $randonNumber; ?>,
                    widget_id: '<?php echo $this->widgetId;?>',
                    type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
                    getParams:'<?php echo $this->getParams;?>',
                    resource_type: '<?php echo !empty($this->resource_type) ? $this->resource_type : "";?>',
                    resource_id: '<?php echo !empty($this->resource_id) ? $this->resource_id : "";?>',
                },
                success: function(responseHTML) {
                    if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
                    scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
                    if($('loadingimgsescontest-wrapper'))
                    scriptJquery('#loadingimgsescontest-wrapper').hide();
                    scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
                    document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
                    pinboardLayout_<?php echo $randonNumber ?>('true');
                    scriptJquery('html, body').animate({
                        scrollTop: scriptJquery("#scrollHeightDivSes_<?php echo $randonNumber; ?>").offset().top
                    }, 500);
                }
            }));
            
            return false;
        }
    <?php } ?>
</script>

