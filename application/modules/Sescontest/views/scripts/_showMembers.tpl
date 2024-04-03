<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _showMembers.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $randonNumber = $this->widgetId;?>
<?php if(!$this->is_ajax){ ?>
    <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<?php } ?>

<?php if(!$this->is_ajax){ ?>
<div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">
	<?php if($this->params['placement_type'] == 'sidebar'):?>
  	<ul class="sescontest_members_listing_sidebar sesbasic_clearfix clear" id="tabbed-widget_<?php echo $randonNumber; ?>" style="min-height:50px;">
  <?php else:?>
  	<?php if($this->params['viewType'] == '1'):?>
  		<ul class="sescontest_contest_listing sesbasic_clearfix clear" id="tabbed-widget_<?php echo $randonNumber; ?>" style="min-height:50px;">
		<?php else:?>
    	<ul class="sescontest_members_clst_view sesbasic_clearfix clear" id="tabbed-widget_<?php echo $randonNumber; ?>" style="min-height:100px;">
    <?php endif;?>
  <?php endif;?>
<?php } ?>
	<?php if($this->params['placement_type'] == 'sidebar'):?>
    <?php foreach($this->paginator as $user):?>
      <li class="sescontest_members_sidebar_list_item sesbasic_clearfix">
      	<div class="_thumb" style="width:<?php echo $this->params['imagewidth'] ?>px;height:<?php echo $this->params['height'] ?>px;">
          <a href="<?php echo $user->getHref();?>"><span class="bg_item_photo" style="background-image:url(<?php echo $user->getPhotoUrl('thumb.notmal'); ?>);"></span></a>
        </div>
        <div class="_info sesbasic_clearfix">
          
          <?php if(isset($this->userNameActive)):?>
            <div class="_name"><a href="<?php echo $user->getHref();?>"><?php echo $user->getTitle();?></a></div>
          <?php endif;?>
          <?php if(isset($this->contestCountActive) || isset($this->entryCountActive) || isset($this->voteCountActive)):?>
            <div class="_stats sesbasic_clearfix">
              <?php if(isset($this->contestCountActive)):?>
                <span class="sesbasic_text_light" title="<?php echo $this->translate(array('Contest', 'Contests', $user->total_contest), $user->total_contest) ?>"><i class="fa fa-trophy"></i><span><?php echo $this->translate($this->locale()->toNumber( (int) $user->total_contest)) ?></span></span>
              <?php endif;?>
              <?php if(isset($this->entryCountActive)):?>
                <?php if(is_null($user->total_count)):?>
                  <?php $user->total_count = 0;?>
                <?php endif;?>
                <span class="sesbasic_text_light" title="<?php echo $this->translate(array('Entry', 'Entries', $user->total_count), $this->locale()->toNumber( (int) $user->total_count)) ?>"><i class="fa fa-sign-in-alt"></i><span><?php echo $this->translate(array('%s', '%s', $user->total_count), $this->locale()->toNumber( (int) $user->total_count)) ?></span>
                </span>
              <?php endif;?>
              <?php if(isset($this->voteCountActive)):?>
                <?php if(is_null($user->total_vote)):?><?php $user->total_vote = 0;?><?php endif;?>
                <span class="sesbasic_text_light" title="<?php echo $this->translate(array('Vote', 'Votes', $user->total_vote), $this->locale()->toNumber( (int) $user->total_vote)) ?>">
                  <i class="far fa-hand-point-up"></i><span><?php echo $this->translate(array('%s', '%s', $user->total_vote), $this->locale()->toNumber( (int) $user->total_vote)) ?></span>
                </span>
              <?php endif;?>
            </div>
          <?php endif;?>
          <?php if (isset($this->followActive) && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember')&& !empty(Engine_Api::_()->user()->getViewer()->getIdentity())): ?>
            <?php $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($user->user_id);?>
            <?php $followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;?>
            <?php $followText = ($FollowUser) ?  $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow')) : $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'));?>
            <div class="_followbtn">
              <a href="javascript:void(0);" data-url='<?php echo $user->getIdentity(); ?>' class="sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_<?php echo $user->getIdentity(); ?>"><span><?php echo $followText; ?></span></a>
            </div>
          <?php endif;?>
        </div>  
      </li>
    <?php endforeach;?>  
  <?php else:?>
  	<?php if($this->params['viewType'] == '1'):?>
      <?php $margin = ($this->params['height']) / 2; ?>
      <?php foreach($this->paginator as $user):?>
        <li class="sescontest_members_list_item" style="width:<?php echo $this->params['width'] ?>px;">
            <article class="sesbasic_clearfix">
              <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesusercoverphoto') && isset($this->coverActive)):?>
                <div class="_cover">
                   <span class="coverimg" <?php if(isset($user->coverphoto) && $user->coverphoto):?>style="background-image:url(<?php echo Engine_Api::_()->storage()->get($user->coverphoto, '')->getPhotoUrl();?>);<?php endif;?>"></span>
                   <?php if(isset($this->socialsharingActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.allow.share', 1)):?>
                    <div class="sescontest_list_btns sesbasic_animation">
                      <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $user, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
                    </div>
                   <?php endif;?>
                 </div>
              <?php endif;?>
              <div class="_info sesbasic_clearfix">
                <div class="_thumb" style="margin-top:-<?php echo $margin; ?>px;width:<?php echo $this->params['imagewidth'] ?>px;height:<?php echo $this->params['height'] ?>px;">
                  <a href="<?php echo $user->getHref();?>"><span class="bg_item_photo" style="background-image:url(<?php echo $user->getPhotoUrl('thumb.notmal'); ?>);"></span></a>
                </div>
                <?php if(isset($this->userNameActive)):?>
                  <div class="_name centerT"><a href="<?php echo $user->getHref();?>"><?php echo $user->getTitle();?></a></div>
                <?php endif;?>
                <div class="_stats">
                  <?php if(isset($this->contestCountActive)):?>
                    <div class="sesbasic_text_light" title="<?php echo $this->translate(array('Contest', 'Contests', $user->total_contest), $this->locale()->toNumber( (int) $user->total_contest)) ?>"><i class="fa fa-trophy"></i><span><?php echo $this->translate($this->locale()->toNumber( (int) $user->total_contest)) ?></span></div>
                  <?php endif;?>
                  <?php if(isset($this->entryCountActive)):?>
                    <?php if(is_null($user->total_count)):?>
                      <?php $user->total_count = 0;?>
                    <?php endif;?>
                    <div class="sesbasic_text_light" title="<?php echo $this->translate(array('Entry', 'Entries', $user->total_count), $this->locale()->toNumber( (int) $user->total_count)) ?>"><i class="fa fa-sign-in-alt"></i><span><?php echo $this->translate(array('%s', '%s', $user->total_count), $this->locale()->toNumber( (int) $user->total_count)) ?></span>
                    </div>
                  <?php endif;?>
                  <?php if(isset($this->voteCountActive)):?>
                    <?php if(is_null($user->total_vote)):?><?php $user->total_vote = 0;?><?php endif;?>
                    <div class="sesbasic_text_light" title="<?php echo $this->translate(array('Vote', 'Votes', $user->total_vote), $this->locale()->toNumber( (int) $user->total_vote)) ?>">
                      <i class="far fa-hand-point-up"></i><span><?php echo $this->translate(array('%s', '%s', $user->total_vote), $this->locale()->toNumber( (int) $user->total_vote)) ?></span>
                    </div>
                  <?php endif;?>
                </div>
                <?php if (isset($this->followActive) && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember')&& !empty(Engine_Api::_()->user()->getViewer()->getIdentity())): ?>
                  <?php $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($user->user_id);?>
                  <?php $followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;?>
                  <?php $followText = ($FollowUser) ?  $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow')) : $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'));?>
                  <div class="_followbtn">
                    <a href="javascript:void(0);" data-url='<?php echo $user->getIdentity(); ?>' class="sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_<?php echo $user->getIdentity(); ?>"><span><?php echo $followText; ?></span></a>
                  </div>
                <?php endif;?>
              </div>  
            </article>
          </li>
      <?php endforeach;?>
    <?php else:?>
      <?php 
      if(engine_count($this->paginator)){
          $counter = 0;
          $isOdd = true;
          foreach($this->paginator as $user): 
          if(!$counter){
            if(!empty($isChanged))
             echo "</li>";
            echo "<li class='odd'>";
            $isOdd = true;
          }
            if($counter%6 == 0 && $counter && $isOdd){
              $isOdd = false;
              echo "</li>";
              echo "<li class='even'>";     
            }
            
            if($counter%11 == 0 && $counter && !$isOdd){
              $isOdd = true;
              echo "</li>";
              echo "<li class='odd'>";  
            }
            $counter++;
            if($counter == 11){
              $isChanged = true;
              $counter = 0;
            }
        ?>
        <div class="sescontest_members_clst_view_item">
          <article class="sesbasic_clearfix">
            <div class="_thumb">
              <a href="<?php echo $user->getHref();?>"><span class="bg_item_photo" style="background-image:url(<?php echo $user->getPhotoUrl('thumb.profile'); ?>);"></span></a>
            </div>
          </article>
        </div>      
      <?php 
      endforeach;
        echo "</li>";
    	} ?> 
    <?php endif;?>
  <?php endif;?>
  <?php if($this->params['pagging'] == 'pagging' && $this->params['viewType'] != 2): ?>
    <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sescontest"),array('identityWidget'=>$randonNumber)); ?>
  <?php endif;?>
  <?php if(!$this->is_ajax){ ?>
    </ul>
    <?php if($this->params['pagging'] != 'pagging' && $this->params['viewType'] != 2):?>
      <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" >
      <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>">
        <i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span>
      </a>
      </div>  
      <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
    <?php endif;?>
  </div>
<script type="text/javascript">
    var searchParams<?php echo $randonNumber; ?> ;
    var requestTab_<?php echo $randonNumber; ?>;
    var valueTabData ;
    // globally define available tab array
		<?php if($this->params['pagging'] == 'auto_load' && $this->params['viewType'] != 2){ ?>
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
  </script>
  <?php } ?>
  
<?php if(isset($this->optionsListGrid['paggindData']) || isset($this->loadJs)){ ?>
<script type="text/javascript">
    var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->openTab;?>';
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
                'url': en4.core.baseUrl + "widget/index/mod/sescontest/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
                'data': {
                    format: 'html',
                    page: page<?php echo $randonNumber; ?>,    
                    params : params<?php echo $randonNumber; ?>, 
                    is_ajax : 1,
                    is_search:is_search_<?php echo $randonNumber;?>,
                    view_more:1,
                    searchParams:searchParams<?php echo $randonNumber; ?> ,
                    widget_id: '<?php echo $randonNumber;?>',
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
                'url': en4.core.baseUrl + "widget/index/mod/sescontest/id/<?php echo $this->widgetId; ?>/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
                'data': {
                    format: 'html',
                    page: pageNum,    
                    params :params<?php echo $randonNumber; ?> , 
                    is_ajax : 1,
                    searchParams:searchParams<?php echo $randonNumber; ?>,
                    widget_id: '<?php echo $randonNumber;?>',
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
<?php } ?>
