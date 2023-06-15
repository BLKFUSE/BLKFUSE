<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _showRssListGrid.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php  if(!$this->is_ajax): ?>
  <style>
    .displayFN{display:none !important;}
  </style>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?> 
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/styles.css'); ?> 
<?php endif;?>

<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)):?>
	<?php $randonNumber = $this->identityForWidget;?>
<?php else:?>
	<?php $randonNumber = $this->identity; ?>
<?php endif;?>

<?php if($this->profile_news == true):?>
  <?php $moduleName = 'sesnews';?>
<?php else:?>
  <?php $moduleName = 'sesnews';?>
<?php endif;?>

<?php $counter = 0;?>
<?php  if(isset($this->defaultOptions) && engine_count($this->defaultOptions) == 1): ?>
  <script type="application/javascript">
      en4.core.runonce.add(function() {
          scriptJquery('#tab-widget-sesnews-<?php echo $randonNumber; ?>').parent().css('display', 'none');
          scriptJquery('.sesnews_container_tabbed<?php echo $randonNumber; ?>').css('border', 'none');
      });
  </script>
<?php endif;?>

<span id="totalitemcountsesnews" style="display:none;"><?php echo $this->paginator->getTotalItemCount(); ?></span>
<?php if(!$this->is_ajax){ ?>
	<div class="sesbasic_view_type sesbasic_clearfix clear" style="display:<?php echo $this->bothViewEnable ? 'block' : 'none'; ?>;height:<?php echo $this->bothViewEnable ? '' : '0px'; ?>">
		<?php if(isset($this->show_item_count) && $this->show_item_count){ ?>
			<div class="sesbasic_clearfix sesbm sesnews_search_result" style="display:<?php !$this->is_ajax ? 'block' : 'none'; ?>" id="<?php echo !$this->is_ajax ? 'paginator_count_sesnews' : 'paginator_count_ajax_sesnews' ?>"><span id="total_item_count_sesnews" style="display:inline-block;"><?php echo $this->paginator->getTotalItemCount(); ?></span> <?php echo $this->paginator->getTotalItemCount() == 1 ?  $this->translate("rss found.") : $this->translate("rss found."); ?></div>
		<?php } ?>
		<div class="sesbasic_view_type_options sesbasic_view_type_options_<?php echo $randonNumber; ?>">
			<?php if(is_array($this->optionsEnable) && engine_in_array('list',$this->optionsEnable)){ ?>
				<a href="javascript:;" rel="list" id="sesnews_list_view_<?php echo $randonNumber; ?>" class="listicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'list') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('List View') : '' ; ?>"></a>
			<?php } ?>
			<?php if(is_array($this->optionsEnable) && engine_in_array('grid',$this->optionsEnable)){ ?>
				<a href="javascript:;" rel="grid" id="sesnews_grid_view_<?php echo $randonNumber; ?>" class="a-gridicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'grid') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('Grid View') : '' ; ?>"></a>
			<?php } ?>
		</div>
	</div>
<?php } ?>
<?php $locationArray = array();?>
<?php if(!$this->is_ajax){ ?>
  <div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">
    <ul class="sesnews_rss_listing sesbasic_clearfix clear <?php if($this->view_type == 'grid'):?>row<?php endif;?>" id="tabbed-widget_<?php echo $randonNumber; ?>" style="min-height:50px;">
<?php } ?>

<?php foreach( $this->paginator as $item ): ?>
  <?php $href = $item->getHref();?>
  <?php $photoPath = $item->getPhotoUrl();?>
  <?php if($this->view_type == 'grid'){ ?>
    <?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/rss/_gridView.tpl';?>
  <?php }else if($this->view_type == 'list'){ ?>
    <?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/rss/_listView.tpl';?>
  <?php } ?>
<?php endforeach; ?>

<?php  if(  $this->paginator->getTotalItemCount() == 0 &&  (empty($this->widgetType))){  ?>
  <?php if( isset($this->category) || isset($this->text) ):?>
    <div class="tip">
      <span>
        <?php echo $this->translate('Nobody has posted a news with that criteria.');?>
      </span>
    </div>
  <?php else:?>
    <div class="tip">
      <span>
      <?php echo $this->translate('Nobody has created a rss yet.');?>
      <?php if ($this->can_create && empty($this->type)):?>
        <?php echo $this->translate('Be the first to %1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesnews_general").'">', '</a>'); ?>
      <?php endif; ?>
      </span>
    </div>
  <?php endif; ?>
<?php }else if( $this->paginator->getTotalItemCount() == 0 && isset($this->tabbed_widget) && $this->tabbed_widget){?>
  <div class="tip">
    <span>
      <?php $errorTip = ucwords(str_replace('SP',' ',$this->defaultOpenTab)); ?>
      <?php echo $this->translate("There are currently no %s",$errorTip);?>
      <?php if (isset($this->can_create) && $this->can_create && empty($this->type)):?>
	<?php echo $this->translate('%1$spost%2$s one!', '<a href="'.$this->url(array('action' => 'create'), "sesnews_general").'">', '</a>'); ?>
      <?php endif; ?>
    </span>
  </div>
<?php } ?>
  
<?php if($this->loadOptionData == 'pagging' && (empty($this->show_limited_data) || $this->show_limited_data  == 'no')): ?>
  <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesnews"),array('identityWidget'=>$randonNumber)); ?>
<?php endif;?>
  
<?php if(!$this->is_ajax){ ?>
  </ul>
  <?php if($this->loadOptionData != 'pagging' && (empty($this->show_limited_data) || $this->show_limited_data  == 'no')):?>
    <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('<i class="fa fa-sync"></i>View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn')); ?> </div>
    <div class="sesbasic_view_more_loading sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" /> </div>
  <?php endif;?>
  </div>

  <script type="text/javascript">
    
    var valueTabData ;
    
		<?php if($this->loadOptionData == 'auto_load' && (empty($this->show_limited_data) || $this->show_limited_data  == 'no')){ ?>
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
      scriptJquery('#sesnews_grid_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sesnews_list_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display','none');
      scriptJquery('#loading_image_<?php echo $randonNumber; ?>').css('display','none');
      scriptJquery(this).addClass('active');
//       if (typeof(requestTab_<?php echo $randonNumber; ?>) != 'undefined') {
// 				requestTab_<?php echo $randonNumber; ?>.cancel();
//       }
//       if (typeof(requestViewMore_<?php echo $randonNumber; ?>) != 'undefined') {
// 				requestViewMore_<?php echo $randonNumber; ?>.cancel();
//       }
      requestTab_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
        dataType: 'html',
				method: 'post',
				'url': en4.core.baseUrl + "widget/index/mod/"+"<?php echo $moduleName;?>"+"/name/<?php echo $this->widgetName; ?>/openTab/" + defaultOpenTab,
				'data': {
					format: 'html',
					page: 1,
					type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
					params : <?php echo json_encode($this->params); ?>, 
					is_ajax : 1,
					searchParams: searchParams<?php echo $randonNumber; ?>,
					identity : '<?php echo $randonNumber; ?>',
				},
				success: function(responseHTML) {
					document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML = responseHTML;
					scriptJquery('#total_item_count_sesnews').html(scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').find('#totalitemcountsesnews').html());
          if(scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel') == 'grid') {
            scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').addClass('row');
          } else {
            scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').removeClass('row');
          }
					if(document.getElementById("loading_image_<?php echo $randonNumber; ?>"))
            document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
				}
      }));
    });
  </script>
<?php } ?>
  
<?php if(isset($this->optionsListGrid['paggindData']) || isset($this->loadJs)){ ?>
	<script type="text/javascript">
		var defaultOpenTab = '<?php echo $this->defaultOpenTab; ?>';
		var params<?php echo $randonNumber; ?> = <?php echo json_encode($this->params); ?>;
		var identity<?php echo $randonNumber; ?>  = '<?php echo $randonNumber; ?>';
		var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
		var searchParams<?php echo $randonNumber; ?> ;
		var is_search_<?php echo $randonNumber;?> = 0;
		var isSearch = true;
		<?php if($this->loadOptionData != 'pagging'){ ?>
      en4.core.runonce.add(function() {
				viewMoreHide_<?php echo $randonNumber; ?>();
			});
			function viewMoreHide_<?php echo $randonNumber; ?>() {
				if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
				document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
			}
			function viewMore_<?php echo $randonNumber; ?> () {
				scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
				scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
				var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';

//         if(typeof requestViewMore_<?php echo $randonNumber; ?>  != "undefined"){
//           requestViewMore_<?php echo $randonNumber; ?>.cancel();
//         }
				requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
          dataType: 'html',
					method: 'post',
					'url': en4.core.baseUrl + "widget/index/mod/"+"<?php echo $moduleName;?>"+"/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
					'data': {
						format: 'html',
						page: page<?php echo $randonNumber; ?>,    
						params : params<?php echo $randonNumber; ?>, 
						is_ajax : 1,
						is_search:is_search_<?php echo $randonNumber;?>,
						view_more:1,
						searchParams:searchParams<?php echo $randonNumber; ?> ,
						identity : '<?php echo $randonNumber; ?>',
						type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
						identityObject:'<?php echo isset($this->identityObject) ? $this->identityObject : "" ?>'
					},
					success: function(responseHTML) {
						if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
              scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
						if(document.getElementById('loadingimgsesnews-wrapper'))
              scriptJquery('#loadingimgsesnews-wrapper').hide();
            if(scriptJquery('#loading_image_<?php echo $randonNumber; ?>'))
            scriptJquery('#loading_image_<?php echo $randonNumber; ?>').hide(); 
						if(!isSearch) {
							scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
						}
						else {
							scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
							isSearch = false;
						}
						scriptJquery('#total_item_count_sesnews').html(scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').find('#totalitemcountsesnews').html());
					}
				});
				
				return false;
			}
		<?php }else{ ?>
			function paggingNumber<?php echo $randonNumber; ?>(pageNum){
				scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
				var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
//         if(typeof requestViewMore_<?php echo $randonNumber; ?>  != "undefined"){
//             requestViewMore_<?php echo $randonNumber; ?>.cancel();
//         }
				requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
          dataType: 'html',
					method: 'post',
					'url': en4.core.baseUrl + "widget/index/mod/"+"<?php echo $moduleName;?>"+"/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
					'data': {
						format: 'html',
						page: pageNum,    
						params :params<?php echo $randonNumber; ?> , 
						is_ajax : 1,
						searchParams:searchParams<?php echo $randonNumber; ?>  ,
						identity : identity<?php echo $randonNumber; ?>,
						type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
					},
					success: function(responseHTML) {
						if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
						scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
						if(document.getElementById('loadingimgsesnews-wrapper'))
						scriptJquery('#loadingimgsesnews-wrapper').hide();
            if(scriptJquery('#loading_image_<?php echo $randonNumber; ?>'))
            scriptJquery('#loading_image_<?php echo $randonNumber; ?>').hide(); 
						scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
						document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
						if(isSearch){
							isSearch = false;
						}
						scriptJquery('#total_item_count_sesnews').html(scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').find('#totalitemcountsesnews').html());
					}
				}));
				
				return false;
			}
		<?php } ?>
	</script>
<?php } ?>
