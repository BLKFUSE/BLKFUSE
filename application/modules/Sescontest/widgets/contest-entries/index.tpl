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

<?php $randonNumber = $this->widgetId; ?>
  <?php if(!$this->is_ajax){ ?>
    <div id="contest-entry-widget_<?php echo $randonNumber;?>" class="sesbasic_view_type_<?php echo $randonNumber;?> sesbasic_clearfix clear" style="display:<?php echo $this->bothViewEnable ? 'block' : 'none'; ?>;height:<?php echo $this->bothViewEnable ? '' : '0px'; ?>">
      <?php if(isset($this->params['show_item_count']) && $this->params['show_item_count']){ ?>
          <div class="sesbasic_clearfix sesbm sescontest_entry_search_result" style="display:<?php !$this->is_ajax ? 'block' : 'none'; ?>" id="<?php echo !$this->is_ajax ? 'paginator_count_sescontest_entry' : 'paginator_count_ajax_sescontest_entry' ?>"><span id="total_item_count_sescontest_entry" style="display:inline-block;"><?php echo $this->paginator->getTotalItemCount(); ?></span> <?php echo $this->paginator->getTotalItemCount() == 1 ?  $this->translate("entry found.") : $this->translate("entries found."); ?></div>
      <?php } ?>
      <div class="sesbasic_view_type_options sesbasic_view_type_options_<?php echo $randonNumber; ?>">
        <?php if(is_array($this->optionsEnable) && engine_in_array('list',$this->optionsEnable)){ ?>
          <a href="javascript:;" rel="list" id="sescontest_entry_list_view_<?php echo $randonNumber; ?>" class="listicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'list') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('List View') : '' ; ?>"></a>
        <?php } ?>
        <?php if(is_array($this->optionsEnable) && engine_in_array('grid',$this->optionsEnable)){ ?>
          <a href="javascript:;" rel="grid" id="sescontest_entry_grid_view_<?php echo $randonNumber; ?>" class="a-gridicon selectView_<?php echo $randonNumber; ?> <?php if($this->view_type == 'grid') { echo 'active'; } ?>" title="<?php echo ((isset($this->htmlTitle) && !empty($this->htmlTitle)) || (empty($this->htmlTitle) && !isset($this->htmlTitle)) ) ? $this->translate('Grid View') : '' ; ?>"></a>
        <?php } ?>
      </div>
      <div class="sescontest_entries_top sesbasic_clearfix">
        <div class="sescontest_entries_filters floarR">
         <?php echo $this->content()->renderWidget('sescontest.entry-search',array('widget_id'=>$randonNumber)); ?>
        </div>
      </div>
    </div>
  <?php } ?>
  <?php if(!$this->is_ajax){ ?>
  <div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">
    <div class="sescontest_winners_listing sesbasic_clearfix clear <?php if($this->view_type == 'grid'):?>row<?php endif;?>" id="tabbed-widget_<?php echo $randonNumber; ?>" style="min-height:50px;">
  <?php } ?>
  <?php foreach($this->paginator as $entry):?>
    <?php $contest = Engine_Api::_()->getItem('contest', $entry->contest_id);?>
    <?php $canComment = Engine_Api::_()->authorization()->isAllowed('participant', $this->viewer(), 'comment');?>
    <?php $likeStatus = Engine_Api::_()->sescontest()->getLikeStatus($entry->participant_id,$entry->getType()); ?>
    <?php $favouriteStatus = Engine_Api::_()->getDbTable('favourites', 'sescontest')->isFavourite(array('resource_id' => $entry->participant_id,'resource_type' => $entry->getType())); ?>
    <?php $owner = $entry->getOwner();?>
    <?php if($this->view_type == 'grid'):?>
      <?php $height = $this->params['height_grid'];?>
      <?php $width = $this->params['width_grid'];?>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/winners/_gridView.tpl';?>
    <?php elseif($this->view_type == 'list'):?>
      <?php $height = $this->params['height_list'];?>
      <?php $width = $this->params['width_list'];?>
      <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/winners/_listView.tpl';?>
    <?php endif;?>
  <?php endforeach;?>
	<?php if($this->params['pagging'] == 'pagging'): ?>
		<?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sescontest"),array('identityWidget'=>$randonNumber)); ?>
	<?php endif;?>
  <?php  if($this->paginator->getTotalItemCount() == 0):  ?>
    <div id="contest-entry-widget_<?php echo $randonNumber;?>" style="width:100%;">
      <div id="error-message_<?php echo $randonNumber;?>">
        <div class="sesbasic_tip clearfix">
          <img src="<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('contest_no_photo', 'application/modules/Sescontest/externals/images/contest-icon.png'); ?>" alt="" />
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
  <?php if(!$this->is_ajax){ ?>
    </div>
    <?php if($this->params['pagging'] != 'pagging'):?>
      <div class="sesbasic_view_more" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'buttonlink icon_viewmore')); ?> </div>
      <div class="sesbasic_view_more_loading sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <img src="<?php echo $this->layout()->staticBaseUrl; ?>application/modules/Sesbasic/externals/images/loading.gif" /> </div>
    <?php endif;?>
  </div>
  
  <script type="text/javascript">
    var searchParams<?php echo $randonNumber; ?> ;
    var requestTab_<?php echo $randonNumber; ?>;
    var valueTabData ;
    // globally define available tab array
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
    scriptJquery(document).on('click','.selectView_<?php echo $randonNumber; ?>',function(){
      if(scriptJquery(this).hasClass('active'))
      return;
      if(document.getElementById("view_more_<?php echo $randonNumber; ?>"))
      document.getElementById("view_more_<?php echo $randonNumber; ?>").style.display = 'none';
      document.getElementById("tabbed-widget_<?php echo $randonNumber; ?>").innerHTML = "<div class='clear sesbasic_loading_container'></div>";
      scriptJquery('#sescontest_entry_grid_view_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sescontest_entry_list_view_<?php echo $randonNumber; ?>').removeClass('active');
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
        'url': en4.core.baseUrl + "widget/index/mod/sescontest/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>",
        'data': {
          format: 'html',
          page: 1,
          type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
          is_ajax : 1,
          searchParams: searchParams<?php echo $randonNumber; ?>,
          identity : '<?php echo $randonNumber; ?>',
          widget_id: '<?php echo $this->widgetId;?>',
          contest_id:'<?php echo $this->contest_id;?>',
        },
        success: function(responseHTML) {
          scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').html(responseHTML);
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

<script type="text/javascript">
  var requestViewMore_<?php echo $randonNumber; ?>;
  var params<?php echo $randonNumber; ?> = <?php echo json_encode($this->params); ?>;
  var identity<?php echo $randonNumber; ?>  = '<?php echo $randonNumber; ?>';
  var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
  var searchParams<?php echo $randonNumber; ?> ;
  var is_search_<?php echo $randonNumber;?> = 0;
  <?php if($this->params['pagging'] != 'pagging'){ ?>
    viewMoreHide_<?php echo $randonNumber; ?>();	
    function viewMoreHide_<?php echo $randonNumber; ?>() {
        if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
    }
    function viewMore_<?php echo $randonNumber; ?> (){
      scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
      scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
      requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
        method: 'post',
        'url': en4.core.baseUrl + "widget/index/mod/sescontest/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>",
        'data': {
          format: 'html',
          page: page<?php echo $randonNumber; ?>,    
          params : params<?php echo $randonNumber; ?>, 
          is_ajax : 1,
          is_search:is_search_<?php echo $randonNumber;?>,
          view_more:1,
          searchParams:searchParams<?php echo $randonNumber; ?> ,
          widget_id: '<?php echo $this->widgetId;?>',
          contest_id:'<?php echo $this->contest_id;?>',
          type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
          identityObject:'<?php echo isset($this->identityObject) ? $this->identityObject : "" ?>'
        },
        success: function(responseHTML) {
          if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
          scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
          if($('loadingimgsescontest-wrapper'))
          scriptJquery('#loadingimgsescontest-wrapper').hide();
          scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
          document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
        }
      });
      
      return false;
    }
  <?php }else{ ?>
    function paggingNumber<?php echo $randonNumber; ?>(pageNum){
      scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
      requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
          method: 'post',
          'url': en4.core.baseUrl + "widget/index/mod/sescontest/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>",
        'data': {
          format: 'html',
          page: pageNum,    
          params :params<?php echo $randonNumber; ?> , 
          is_ajax : 1,
          //searchParams:searchPidentityarams<?php echo $randonNumber; ?>,
          widget_id: '<?php echo $this->widgetId;?>',
          contest_id:'<?php echo $this->contest_id;?>',
          type:scriptJquery('.selectView_<?php echo $randonNumber; ?>.active').attr('rel'),
        },
        success: function(responseHTML) {
          if(document.getElementById('loading_images_browse_<?php echo $randonNumber; ?>'))
          scriptJquery('#loading_images_browse_<?php echo $randonNumber; ?>').remove();
          if($('loadingimgsescontest-wrapper'))
          scriptJquery('#loadingimgsescontest-wrapper').hide();
          scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
          document.getElementById('tabbed-widget_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
        }
      }));
      
      return false;
    }
  <?php } ?>
</script>

