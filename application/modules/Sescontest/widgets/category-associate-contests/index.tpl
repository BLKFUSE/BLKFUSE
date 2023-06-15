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
<?php // Carousel Layout ?>
<?php 
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/slick/slick.js');
?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $viewerId = $viewer->getIdentity();?>
<?php $width = $this->params['width'];?>
<?php if(!is_numeric($width)):?>
  <?php $width = explode('px', $this->params['width'])[0];?>
<?php endif;?>
<?php  $randonNumber = $this->widgetId;?>
<?php if($this->params['view_type'] == 'carousel'):?>
    <?php if(!$this->is_ajax){ ?>
      <div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear sescontest_categories_contests_listing_container">
       <?php } ?>
        <div class="sescontest_category_contests sesbasic_bxs sesbasic_clearfix">
          <div class="sesbasic_loading_cont_overlay"></div>
      <?php foreach( $this->paginatorCategory as $category):?>
        <div class="_row sesbasic_clearfix sescontest_carousel_h_wrapper">
            <div class="_head sesbasic_clearfix">
              <span class="_catname floatL">
              	<a href="<?php echo $category->getHref();?>"><?php echo $category->category_name;?></a>
              	<?php if(isset($this->countContestActive)):?><span>(<?php echo $category->total_contest_categories;?>)</span><?php endif;?>  
              </span>
              <?php if(isset($this->seeAllActive)):?>
              <span class="_morebtn <?php echo $this->params['allignment_seeall'] == 'right' ?  'floatR' : 'floatL'; ?>"><a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general', true).'?category_id='.$category->category_id;?>" class="sesbasic_link_btn"><?php echo $this->translate('See All');?></a></span>
              <?php endif;?>
            </div>
            <div class="_list sescontest_contest_carousel slider sescontest_slick_slider" data-width="<?php echo $width ?>" rel="<?php echo $category->total_contest_categories;?>">
              <?php foreach($this->resultArray['contest_data'][$category->category_id]  as $contest):?>
                <div class="sescontest_advgrid_item sesbasic_clearfix _iscatitem">
                <article>
                    <div class="sescontest_advgrid_item_header">
                    <?php if(isset($this->titleActive) ){ ?>
                        <?php if(strlen($contest->getTitle()) > $this->params['title_truncation']):?>
                        <?php $title = mb_substr($contest->getTitle(),0,$this->params['title_truncation']).'...';?>
                        <?php else: ?>
                        <?php $title = $contest->getTitle();?>
                        <?php endif; ?>
                        <?php echo $this->htmlLink($contest->getHref(),$title) ?><?php if(isset($this->verifiedLabelActive)&& $contest->verified):?><i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i><?php endif;?>
                    <?php } ?>
                    </div>
                    <div class="sescontest_advgrid_item_thumb sescontest_list_thumb" style="height:<?php echo $this->params['height'] ?>px;">
                    <?php $href = $contest->getHref();?>
                    <a href="<?php echo $href; ?>" class="sescontest_advgrid_img">
                        <span class="sesbasic_animation" style="background-image:url(<?php echo $contest->getPhotoUrl('thumb.profile'); ?>);"></span>
                    </a>
                    <?php $owner = $contest->getOwner(); ?>
                    <div class="sescontest_advgrid_item_owner">
                    	<?php  if(isset($this->byActive)){ ?>
                        <span class="sescontest_advgrid_item_owner_img">
                        	<?php echo $this->htmlLink($owner->getHref(), $this->itemPhoto($owner, 'thumb.icon', $owner->getTitle()), array('title'=>$owner->getTitle())) ?>
                        </span>
                      <?php  } ?>
                      <?php  if((isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->followButtonActive)) && $contest->is_approved):?>
                    <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $contest->getHref()); ?>
                        <span class="useroption">
                            <a href="javascript:void(0);" class="fa fa-angle-down"></a>
                            <div class="sescontest_advgrid_item_btns">
                            <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
                            </div>
                        </span>
                        <?php endif; ?>
                        <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
                        <span class="itemcont">
                          <?php if(isset($this->byActive)){ ?>
                            <span class="ownername">
                                <?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?>
                            </span>
                          <?php  } ?>
                    		</span>      
                    </div>
                    <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
                        <div class="sescontest_list_labels sesbasic_animation">
                        <?php if(isset($this->featuredLabelActive) && $contest->featured){ ?>
                            <span class="sescontest_label_featured" title="<?php echo $this->translate('Featured');?>"><i class="fa fa-star"></i></span>
                        <?php } ?>
                        <?php if(isset($this->sponsoredLabelActive) && $contest->sponsored){ ?>
                            <span class="sescontest_label_sponsored" title="<?php echo $this->translate('Sponsored');?>"><i class="fa fa-star"></i></span>
                        <?php } ?>
                        <?php if(isset($this->hotLabelActive) && $contest->hot){ ?>
                            <span class="sescontest_label_hot" title="<?php echo $this->translate('Hot');?>"><i class="fa fa-star"></i></span>
                        <?php } ?>
                        </div>
                    <?php } ?>
                    <a href="<?php echo $href; ?>" class="_viewbtn sesbasic_link_btn sesbasic_animation"><?php echo $this->translate("View Contest");?></a>
                    </div>
                    <div class="sescontest_advgrid_item_footer">
                    <?php include APPLICATION_PATH . '/application/modules/Sescontest/views/scripts/_dataBar.tpl'; ?>
                    </div>
                </article>
                </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <?php if($this->paginatorCategory->getTotalItemCount() == 0 && !$this->is_ajax):  ?>
        <div id="sescontest_category_contests_<?php echo $randonNumber;?>" class="">
            <div id="error-message_<?php echo $randonNumber;?>">
                <div class="sesbasic_tip clearfix">
                    <img src="<?php echo Engine_Api::_()->sescontest()->getFileUrl(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest_contest_no_photo', 'application/modules/Sescontest/externals/images/contest-icon.png')); ?>" alt="" />
                    <span class="sesbasic_text_light">
                    <?php echo $this->translate('There are no results that match your search. Please try again.') ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endif;?>
    <?php if($this->params['pagging'] == 'pagging'){ ?>
        <?php echo $this->paginationControl($this->paginatorCategory, null, array("_pagging.tpl", "sescontest"),array('identityWidget'=>$randonNumber)); ?>
    <?php } ?>
    
    </div>	
    <?php if(!$this->is_ajax){ ?>
     </div>
     <?php if($this->params['pagging'] != 'pagging'){ ?>  
   <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > 
  	<a style="display:none;" href="javascript:void(0);" id="feed_viewmore_link_<?php echo $randonNumber; ?>" class="sesbasic_animation sesbasic_link_btn"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More'); ?></span></a>
  </div>
  <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> </div>  
  <?php } ?>
  <?php } ?>
<?php elseif($this->params['view_type'] == 'grid'):?>
  <div class="row sesbasic_bxs sesbasic_clearfix sescontest_category_grid_layout">
    <?php foreach( $this->paginatorCategory as $category): ?>
  <div class="col-lg-<?php echo $this->gridblock; ?> col-md-6 col-sm-6 col-12">
    <div class="_item">
        <article class="sesbasic_animation">
          <header class="sesbasic_clearfix">
            <?php if(isset($this->seeAllActive)):?>
              <span class="_seeall"><a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general', true).'?category_id='.$category->category_id;?>"><?php echo $this->translate('See All');?></a></span>
            <?php endif;?>
            <span class="_catname">
            	<a href="<?php echo $category->getHref();?>">
                <?php if($category->colored_icon){
                      $str = Engine_Api::_()->getItem('storage_file',$category->colored_icon);
                      if($str){
                 ?>
                <img src="<?php echo $str->getPhotoUrl(); ?>" />
                <?php } } ?>
                <span><?php echo $category->category_name;?></span>
              </a>
             </span>
            <?php if(isset($this->categoryDescriptionActive)):?>
              <p><?php echo $this->string()->truncate($this->string()->stripTags($category->description), $this->params['grid_description_truncation']) ?></p>
            <?php endif;?>
          </header>
          <div class="_cont">
    <?php foreach($this->resultArray['contest_data'][$category->category_id] as $contest):?>
            <?php if(isset($this->titleActive)):?>
              <?php if(strlen($contest->getTitle()) > $this->params['title_truncation']):?>
                <?php $title = mb_substr($contest->getTitle(),0,$this->params['title_truncation']).'...';?>
              <?php else: ?>
                <?php $title = $contest->getTitle();?>
              <?php endif; ?>
              <div class="_contestlist">
                <a href="<?php echo $contest->getHref(); ?>">
                	<i class="fa fa-angle-right sesbasic_text_light"></i>
									<?php echo $this->itemPhoto($contest, 'thumb.icon', $contest->getTitle());?>
                  <span><?php echo $title; ?></span></a>
              </div>
            <?php endif;?>
           
    <?php endforeach; ?>
     </div>
        </article>
      </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php elseif($this->params['view_type'] == 'slideshow'):?>
  <div class="sescontest_category_contests sesbasic_bxs sesbasic_clearfix">
    <div class="sesbasic_loading_cont_overlay"></div>
      <?php foreach( $this->paginatorCategory as $category): ?>
        <div class="_row sesbasic_clearfix sescontest_carousel_h_wrapper">
          <div class="_head sesbasic_clearfix">
            <span class="_catname floatL">
            	<a href="<?php echo $category->getHref();?>"><?php echo $category->category_name;?></a>
            	<?php if(isset($this->countContestActive)):?><span>(<?php echo $category->total_contest_categories;?>)</span><?php endif;?>  
            </span>
            <?php if(isset($this->seeAllActive)):?>
              <span class="_morebtn <?php echo $this->params['allignment_seeall'] == "right" ?  "floatR" : "floatL"; ?>"><a href="<?php echo $this->url(array('action' => 'browse'),'sescontest_general', true).'?category_id='.$category->category_id;?>" class="sesbasic_link_btn"><?php echo $this->translate('See All');?></a></span>
            <?php endif;?>
          </div>
          <div class="_list sescontest_category_slideshow slider sescontest_slick_slider" data-width="<?php echo $width ?>" rel="<?php echo $category->total_contest_categories;?>">
      <?php foreach( $this->resultArray['contest_data'][$category->category_id] as $contest):?>
        <div class="sescontest_list_item sesbasic_clearfix">
          <article class="sesbasic_clearfix">
              <div class="sescontest_list_item_thumb sescontest_list_thumb" style="height:<?php echo $this->params['height'] ?>px;">
              <?php $href = $contest->getHref();$imageURL = $contest->getPhotoUrl('thumb.profile');?>
              <a href="<?php echo $href; ?>" class="sescontest_list_item_img"><span style="background-image:url(<?php echo $imageURL; ?>);"></span></a>
              <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)){ ?>
                <div class="sescontest_list_labels sesbasic_animation">
                  <?php if(isset($this->featuredLabelActive) && $contest->featured){ ?>
                    <span class="sescontest_label_featured" title="<?php echo $this->translate('Featured');?>"><i class="fa fa-star"></i></span>
                  <?php } ?>
                  <?php if(isset($this->sponsoredLabelActive) && $contest->sponsored){ ?>
                    <span class="sescontest_label_sponsored" title="<?php echo $this->translate('Sponsored');?>"><i class="fa fa-star"></i></span>
                  <?php } ?>
                  <?php if(isset($this->hotLabelActive) && $contest->hot){ ?>
                      <span class="sescontest_label_hot" title="<?php echo $this->translate('Hot');?>"><i class="fa fa-star"></i></span>
                  <?php } ?>
                </div>
              <?php } ?>
              <div class="sescontest_list_thumb_over">
                <a href="<?php echo $href; ?>"></a>
                <?php  if((isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive) || isset($this->followButtonActive)) && $contest->is_approved):?>
                <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $contest->getHref()); ?>
                  <div class="sescontest_list_btns">
                    <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataSharing.tpl';?>
                  </div>
                <?php endif; ?>
              </div>
              <?php  include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_mediaType.tpl';?>
            </div>
            <div class="sescontest_list_item_cont">
              <?php if(isset($this->titleActive) ): ?>
                <?php if(strlen($contest->getTitle()) > $this->params['title_truncation']):?>
                  <?php $title = mb_substr($contest->getTitle(),0,$this->params['title_truncation']).'...';?>
                <?php else: ?>
                  <?php $title = $contest->getTitle();?>
                <?php endif; ?>
                <div class="sescontest_list_item_title">
                  <?php echo $this->htmlLink($contest->getHref(),$contest->getTitle()) ?>
                  <?php if(isset($this->verifiedLabelActive) && $contest->verified): ?>
                  <i class="sescontest_label_verified fa fa-check-circle" title='<?php echo $this->translate("Verified");?>'></i>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
              <div class="sescontest_list_item_owner sesbasic_clearfix sesbasic_text_light">
                <?php $owner = $contest->getOwner(); ?>	
                <?php  if(isset($this->byActive)): ?>
                   <span><?php echo $this->translate('by');?>&nbsp;<?php echo $this->htmlLink($owner->getHref(),$owner->getTitle() ) ?></span>
                 <?php endif; ?>
              </div>
              <div class="sescontest_list_item_sep"></div>
              <?php if(isset($this->startenddateActive)):?>
                <div class="sescontest_list_item_date">
                  <i class="fa fa-calendar"></i>
                  <?php echo $this->contestStartEndDates($contest, $dateinfoParams);?>
                </div>
              <?php endif;?>
              <div class="sescontest_list_item_stats">
                <?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/_dataStatics.tpl';?>
              </div>
              <?php if(isset($this->descriptionActive)):?>
                <div class="sescontest_list_item_des">
                  <p><?php echo $this->string()->truncate($this->string()->stripTags($contest->description), $this->params['slideshow_description_truncation']) ?></p>
                </div>
              <?php endif;?>
              <div class="sescontest_list_item_total">
                <?php include APPLICATION_PATH . '/application/modules/Sescontest/views/scripts/_dataBar.tpl'; ?>
              </div>
            </div>
          </article>
        </div>
      <?php endforeach; ?>
      </div>
            </div>
      <?php endforeach; ?>
  </div>
<?php endif;?>
<script type="text/javascript">
    <?php if($this->loadOptionData == 'auto_load'){ ?>
        scriptJquery( window ).load(function() {
        scriptJquery (window).scroll( function() {
            var containerId = '#scrollHeightDivSes_<?php echo $randonNumber;?>';
            if(typeof scriptJquery(containerId).offset() != 'undefined' && scriptJquery('#view_more_<?php echo $randonNumber; ?>').length > 0) {
                var hT = scriptJquery('#view_more_<?php echo $randonNumber; ?>').offset().top,
                hH = scriptJquery('#view_more_<?php echo $randonNumber; ?>').outerHeight(),
                wH = scriptJquery(window).height(),
                wS = scriptJquery(this).scrollTop();
                if ((wS + 30) > (hT + hH - wH) && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block') {
                    document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
                }
            }
        });
    <?php } ?>
    function paggingNumber<?php echo $randonNumber; ?>(pageNum){
        scriptJquery ('.overlay_<?php echo $randonNumber ?>').css('display','block');
        (scriptJquery.ajax({
            method: 'post',
            'url': en4.core.baseUrl + "widget/index/mod/sescontest/name/<?php echo $this->widgetName; ?>",
            'data': {
                format: 'html',
                page: pageNum,    
                params :'<?php echo json_encode($this->params); ?>', 
                is_ajax : 1,
                widget_id: '<?php echo $randonNumber; ?>',
            },
            success: function(responseHTML) {
                scriptJquery ('.overlay_<?php echo $randonNumber ?>').css('display','none');
                document.getElementById('scrollHeightDivSes_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
                makeSlidesObject();
            }
        }));
        return false;
    }

    var defaultOpenTab ;
    viewMoreHide_<?php echo $randonNumber; ?>();
    function viewMoreHide_<?php echo $randonNumber; ?>() {
        if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
        document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginatorCategory->count() == 0 ? 'none' : ($this->paginatorCategory->count() == $this->paginatorCategory->getCurrentPageNumber() ? 'none' : '' )) ?>";
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
                is_ajax : 1,
                widget_id: '<?php echo $randonNumber; ?>',
            },
            success: function(responseHTML) {
                scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').append(responseHTML);
                document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
                makeSlidesObject();
            }
        }));
        return false;
    }
</script>
