<?php

 /** 
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
?>
<?php 
	$staticBaseUrl = $this->layout()->staticBaseUrl;

	if($this->feeddesign == 2) {
		$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/imagesloaded.pkgd.js');
		$this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/styles/style_pinboard.css'); 
		$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/wookmark.min.js');
		$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/pinboardcomment.js');
		$randonNumber = 'pinFeed'; 
	}

  $enabledModuleNames = Engine_Api::_()->getDbTable('modules', 'core')->getEnabledModuleNames(); 
  $settings = Engine_Api::_()->getApi('settings', 'core');
  $levelAdapter = Engine_Api::_()->authorization()->getAdapter('levels');
  
  $this->headTranslate(array('More','Close','Permalink of this Post','Copy link of this feed:','Go to this feed','You won\'t see this post in Feed.',"Undo","Hide all from",'You won\'t see',"post in Feed.","Select","It is a long established fact that a reader will be distracted","If you find it offensive, please","file a report.", "Choose Feeling or activity...", "How are you feeling?", "ADD POST", "Schedule Post"));
?>

<?php if(engine_in_array('sesfeelingactivity',$enabledModuleNames)) { ?>
  <?php $getFeelings = Engine_Api::_()->getDbTable('feelings', 'sesfeelingactivity')->getFeelings(array('fetchAll' => 1, 'admin' => 0)); ?>
<?php } ?>
<?php if(engine_in_array('sesemoji',$enabledModuleNames)) { ?>
  <?php $getEmojis = Engine_Api::_()->getDbTable('emojis', 'sesemoji')->getEmojis(array('fetchAll' => 1)); ?>
<?php } ?>
<?php
  $this->headScript()
			->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.tooltip.js')
			->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/tooltip.js')
			->appendFile($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/core.js')
			->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/hashtag/autosize.min.js')
			->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/hashtag/hashtags.js')
			->appendFile($staticBaseUrl . 'externals/html5media/html5media.min.js')
			->appendFile($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/editComposer.js');
	
	//Tooltip
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/tooltip/jquery.tooltipster.js');
	$this->headLink()->appendStylesheet($staticBaseUrl . "application/modules/Sesbasic/externals/styles/tooltip/tooltipster.css");
	
	include APPLICATION_PATH .  '/application/modules/Sesadvancedcomment/views/scripts/_jsFiles.tpl';
	
  $this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/styles/styles.css');
  
  if(engine_in_array('sesvideo',$enabledModuleNames)) {
		$viewer = Engine_Api::_()->user()->getViewer();
		if ($viewer->getIdentity() == 0)
      $level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    else
      $level = $viewer;
		
    $type = Engine_Api::_()->authorization()->getPermission($level, 'sesbasic_video', 'videoviewer');
    if ($type == 1) {
      $this->headScript()->appendFile($staticBaseUrl
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe.min.js')
              ->appendFile($staticBaseUrl
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe-ui-default.min.js')
              ->appendFile($staticBaseUrl
                      . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimagevieweradvance.js');
      $this->headLink()->appendStylesheet($staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/photoswipe.css');
    } else {
      $loadImageViewerFile = $staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimageviewerbasic.js';
      $this->headScript()->appendFile($loadImageViewerFile);
      $this->headLink()->appendStylesheet($staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/medialightbox.css');
    }
	}
  
  if(engine_in_array('sesadvpoll',$enabledModuleNames)) {
    $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesadvpoll/externals/scripts/core.js'); 
  }
  
  if(engine_in_array('sesgrouppoll',$enabledModuleNames)) {
    $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesgrouppoll/externals/scripts/core.js'); 
  }
  
  if(engine_in_array('sespagepoll',$enabledModuleNames)) {
    $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sespagepoll/externals/scripts/core.js'); 
  }
  
  if(engine_in_array('sesbusinesspoll',$enabledModuleNames)) {
    $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbusinesspoll/externals/scripts/core.js'); 
  }
      
  //Web cam upload for profile photo
  if($settings->getSetting('sesadvancedactivity.profilephotoupload', 1) && engine_in_array('sesalbum',$enabledModuleNames)):
    $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/webcam.js'); 
  endif; 
  
  if(engine_in_array('sesemoji',$enabledModuleNames)) {
    $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesemoji/externals/scripts/emojiscontent.js'); 
  }
  
  $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/mo.min.js');
  $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/animation.js');
  
  if(defined('SESFEEDGIFENABLED')) {
    $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js');
  }
  
  if(engine_in_array('elivestreaming',$enabledModuleNames)) {
		$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Elivestreaming/externals/scripts/core.js');
  }
?>
<?php if(engine_in_array('sespymk',$enabledModuleNames)) { ?>
	<?php 
		$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
		$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js'); 
		$this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sespymk/externals/styles/styles.css'); 
	?>
<?php } ?>

<?php if(engine_in_array('sesgrouppoll',$enabledModuleNames) && Engine_Api::_()->core()->hasSubject('sesgroup_group')) { ?>
	<?php $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesgrouppoll/externals/scripts/core.js'); ?>
	<?php if(engine_in_array('poll',$enabledModuleNames)){ ?>
		<?php $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Poll/externals/scripts/core.js'); ?>
	<?php } ?>
<?php } ?>
<?php if(engine_in_array('sesvideo',$enabledModuleNames)) { ?>
	<?php
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js');
	?>
<?php } ?>
<?php if(engine_in_array('sesgroup',$enabledModuleNames) && Engine_Api::_()->core()->hasSubject('sesgroup_group')) { ?>
	<?php
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesgroup/externals/scripts/core.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesgroup/externals/scripts/activitySwitchGroup.js');
	?>
<?php } ?>
<?php if(engine_in_array('sespage',$enabledModuleNames) && Engine_Api::_()->core()->hasSubject('sespage_page')) { ?>
	<?php
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sespage/externals/scripts/core.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sespage/externals/scripts/activitySwitchPage.js');
	?>
<?php } ?>
<?php if(engine_in_array('sesbusiness',$enabledModuleNames) && Engine_Api::_()->core()->hasSubject('businesses')) { ?>
	<?php
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbusiness/externals/scripts/core.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbusiness/externals/scripts/activitySwitchBusiness.js');
	?>
<?php } ?>
<?php if(engine_in_array('estore',$enabledModuleNames) && Engine_Api::_()->core()->hasSubject('stores')) { ?>
	<?php
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Estore/externals/scripts/core.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Estore/externals/scripts/activitySwitchStore.js');
	?>
<?php } ?>

<script type="application/javascript">
	<?php if(!$this->feedOnly && $this->autoloadTimes > 0 && $this->scrollfeed ) { ?>
		var autoloadTimes = '<?php echo $this->autoloadTimes; ?>';
		var counterLoadTime = 0;
		scriptJquery( window ).load(function() {
			scriptJquery(window).scroll( function() {
				var containerId = '#activity-feed';
				if(typeof scriptJquery(containerId).offset() != 'undefined' && scriptJquery('#feed_viewmore_activityact').length > 0) {
					var heightOfContentDiv = scriptJquery(containerId).height();
					var fromtop = scriptJquery(this).scrollTop() + 300;
					if(fromtop > heightOfContentDiv - 100 && scriptJquery('#feed_viewmore_activityact').css('display') == 'block' && autoloadTimes > counterLoadTime){
						document.getElementById('feed_viewmore_activityact_link').click();
						counterLoadTime++;
					}
				}
			});
		});
  <?php } ?>
	function setFocus(){
		document.getElementById("activity_body").focus();
	}
	var sesAdvancedActivityGetFeeds = <?php echo $this->getUpdates ?>;
	var sesAdvancedActivityGetAction_id = <?php echo $this->action_id; ?>;
	if(!sesAdvancedActivityGetFeeds){
		en4.core.runonce.add(function() {
			var subject_guid = '<?php echo $this->subjectGuid ?>';
			scriptJquery('ul.sesadvancedactivity_filter_tabs li a:first').trigger("click");
		});
	}
	function activateFunctionalityOnFirstLoad() {
		var action_id = <?php echo $this->action_id; ?>;
		sesAdvancedActivityGetFeeds = true;

		if(!action_id) {
			scriptJquery(".sesact_feed_filters").show();
			if (scriptJquery('#activity-feed').find('li').length > 0)
				scriptJquery('.sesadv_noresult_tip').hide();
			else
				scriptJquery('.sesadv_noresult_tip').show();
		}else{
			if (!scriptJquery('#activity-feed').find('li').length > 0)
				scriptJquery(".no_content_activity_id").show();
		}
		scriptJquery(".sesadv_content_load_img").hide();
	}

	<?php if($this->feeddesign != 2) { ?>

		function feedUpdateFunction(){}
	<?php } ?>
</script>

<?php 
$viewer = $this->viewer();
$showwelcometab = $settings->getSetting('sesadvancedactivity.showwelcometab', 1);
$makelandingtab = $settings->getSetting('sesadvancedactivity.makelandingtab', 2);
$tabvisibility = $settings->getSetting('sesadvancedactivity.tabvisibility', 0);
$diff_days = $friendsCount = 0;
$numberofdays = $settings->getSetting('sesadvancedactivity.numberofdays', 3);
$numberoffriends = $settings->getSetting('sesadvancedactivity.numberoffriends', 3); 
if($viewer->getIdentity()) {
  if($tabvisibility == 2) {
    $signup_date = explode(' ', $viewer->creation_date);
    $finalSignupDate = date_create($signup_date[0]);
    $todayDate = date_create(date('Y-m-d'));
    $diff = date_diff($finalSignupDate,$todayDate); 
    $diff_days = $diff->d;
  } elseif($tabvisibility == 1) {
    $friendsCount = $this->viewer()->membership()->getMemberCount($this->viewer());
  }
}
$welcomeflag = 'false';
if($showwelcometab) {
  if($tabvisibility == 2 && $numberofdays > $diff_days) {
    $welcomeflag = 'true';
  } elseif($tabvisibility == 1 && $numberoffriends > $friendsCount) {
    $welcomeflag = 'true';
  } elseif($tabvisibility == 0) {
    $welcomeflag = 'true';
  }
}
?>
<script type="application/javascript">
	scriptJquery(document).ready(function() {
		carouselSesadvReaction();
	});

	var privacySetAct = false;
	var sespageContentSelected = "";
	<?php if( !$this->feedOnly && $this->action_id){ ?>
	scriptJquery(document).ready(function(e){
		scriptJquery('.tab_<?php echo $this->identity; ?>.tab_layout_sesadvancedactivity_feed').find('a').click();
	});
	<?php } ?>
</script>
<?php if( !$this->feedOnly && $this->isMemberHomePage): ?>
<div class="sesact_tabs_wrapper sesbasic_clearfix sesbasic_bxs">
  <ul id="sesadv_tabs_cnt" class="sesact_tabs sesbasic_clearfix">
    <?php if($showwelcometab): ?>
      <?php if($welcomeflag == 'true'): ?>
        <li data-url="1" class="sesadv_welcome_tab <?php if($makelandingtab == 2): ?> active <?php endif; ?>">
          <a href="javascript:;">
          <?php if($this->welcomeicon == 'icon'){ ?>
            <i class="far fa-smile" aria-hidden="true"></i>
          <?php }else if($this->welcomeicon){ ?>
            <i class="_icon"><img src="<?php echo Engine_Api::_()->sesadvancedactivity()->getFileUrl($this->welcomeicon); ?>" ></i>
         <?php } ?>
            <span><?php echo $this->translate($this->welcometabtext); ?></span>
          </a>
        </li>
      <?php endif; ?>
    <?php endif; ?>
    <li data-url="2" class="sesadv_update_tab <?php if(empty($showwelcometab) || $makelandingtab == 0): ?> active <?php endif; ?>">
      <a href="javascript:;">
        <?php if($this->welcomeicon == 'icon'){ ?>
            <i class="fa fa-globe" aria-hidden="true"></i>
          <?php }else if($this->whatsnewicon){ ?>
            <i class="_icon"><img src="<?php echo Engine_Api::_()->sesadvancedactivity()->getFileUrl($this->whatsnewicon); ?>" ></i>
         <?php } ?>
      	<span><?php echo $this->translate($this->whatsnewtext); ?></span>
        <span id="count_new_feed"></span>
      </a>
    </li>
  </ul>
</div>

<div id="sesadv_tab_1" class="sesadv_tabs_content" style="display:none;">
  <div class="sesbasic_loading_container sesadv_loading_img" style="height:100px;"  data-href="sesadvancedactivity/ajax/welcome/"></div>
</div>
<script type="application/javascript">
scriptJquery(document).ready(function(){
      if(scriptJquery('#sesadv_tabs_cnt').children().length == 1){
        scriptJquery('#sesadv_tabs_cnt').parent().remove(); 
      }
    });
</script>

<div id="sesadv_tab_2" class="sesadv_tabs_content" <?php if(!empty($showwelcometab) && $makelandingtab != 0): ?> style="display:none;"<?php endif; ?>>
<?php endif; ?>
  <?php $this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/styles/styles.css'); ?>
	<?php $this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesbasic/externals/styles/emoji.css'); ?>    
<?php $this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>


<?php $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.min.js'); ?>
<?php $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<?php $this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesbasic/externals/styles/mention/jquery.mentionsInput.css'); ?>    

 <?php $this->headScript()->appendFile($staticBaseUrl .'application/modules/Sesbasic/externals/scripts/mention/underscore-min.js'); ?>
  <?php $this->headScript()->appendFile($staticBaseUrl .'application/modules/Sesbasic/externals/scripts/mention/jquery.mentionsInput.js'); ?>
 
<?php if( (!empty($this->feedOnly) || !$this->endOfFeed ) &&
    (empty($this->getUpdate) && empty($this->checkUpdate)) ):
    
    $adsEnable = $settings->getSetting('sesadvancedactivity.adsenable', 0);
    ?>
  <script type="text/javascript">
  
  function defaultSettingsSesadv(){
      var activity_count = <?php echo sprintf('%d', $this->activityCount) ?>;
      var next_id = <?php echo sprintf('%d', $this->nextid) ?>;
      var subject_guid = '<?php echo $this->subjectGuid ?>';
      var endOfFeed = <?php echo ( $this->endOfFeed ? 'true' : 'false' ) ?>;
      var activityViewMore = window.activityViewMore = function(next_id, subject_guid) {
        //if( en4.core.request.isRequestActive() ) return;
        var hashTag = scriptJquery('#hashtagtextsesadv').val();
        var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';         
         if(typeof sesItemSubjectGuid != "undefined")
            var itemSubject = sesItemSubjectGuid;
          else
            var itemSubject = "";
        document.getElementById('feed_viewmore_activityact').style.display = 'none';
        document.getElementById('feed_loading').style.display = '';
        
        var adsIds = scriptJquery('.sescmads_ads_listing_item');
        var adsIdString = "";
        if(adsIds.length > 0){
           scriptJquery('.sescmads_ads_listing_item').each(function(index){
             adsIdString = scriptJquery(this).attr('rel')+ "," + adsIdString ;
           });
        }
        
          var request = scriptJquery.ajax({
            type:"POST",
          url : url+"?hashtag="+hashTag+'&isOnThisDayPage='+isOnThisDayPage+'&isMemberHomePage='+isMemberHomePage+'&subjectPage='+itemSubject,
          type: 'post',
          data : {
            format : 'html',
            'maxid' : next_id,
            'feedOnly' : true,
            'nolayout' : true,
            'getUpdates' : true,
            'subject' : subject_guid,
            'ads_ids': adsIdString,
            'contentCount':scriptJquery('#activity-feed').find("[id^='activity-item-']").length,
            'filterFeed':scriptJquery('.sesadvancedactivity_filter_tabs .active > a').attr('data-src'),
          },
          evalScripts : true,
          success : function( responseHTML) {
            scriptJquery("#activity-feed").append(responseHTML);
            en4.core.runonce.trigger();
            Smoothbox.bind(document.getElementById('activity-feed'));
            feedUpdateFunction();
            <?php if($adsEnable){ ?>
            displayGoogleAds();
            <?php  } ?>
            sesadvtooltip();
          }
        });
      }
      
      if( next_id > 0 && !endOfFeed ) {
        scriptJquery('#feed_viewmore_activityact').show();
        scriptJquery('#feed_loading').hide();
        if(scriptJquery('#feed_viewmore_activityact_link').length){
          scriptJquery('#feed_viewmore_activityact_link').off('click').click( function(event){
            activityViewMore(next_id, subject_guid);
          });
        }
      } else {
        
        scriptJquery('#feed_viewmore_activityact').hide();
        scriptJquery('#feed_loading').hide();
      }
      
   //   
  }
  <?php if($adsEnable){ ?>
  function displayGoogleAds(){
    try{
      scriptJquery('ins').each(function(){
          (adsbygoogle = window.adsbygoogle || []).push({});
      });
      if(scriptJquery('script[src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"]').length == 0){        
        var script = document.createElement('script');
        script.src = '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
        document.head.appendChild(script);  
      }
    }catch(e){
      //silence  
    }
  }
  <?php } ?>
    en4.core.runonce.add(function() {defaultSettingsSesadv();<?php if($adsEnable){ ?>displayGoogleAds();<?php } ?>});
    defaultSettingsSesadv();
  </script>
<?php endif; ?>

<?php if( !empty($this->feedOnly) && empty($this->checkUpdate)): // Simple feed only for AJAX
  echo $this->activityLoop($this->activity, array(
    'action_id' => $this->action_id,
    'communityadsIds' => $this->communityadsIds,
    'viewAllComments' => $this->viewAllComments,
    'viewAllLikes' => $this->viewAllLikes,
    'getUpdate' => $this->getUpdate,
    'ulInclude'=>!$this->getUpdates ? 0 : $this->feedOnly,
    'contentCount'=>$this->contentCount,
    'userphotoalign' => $this->userphotoalign,
    'filterFeed'=>$this->filterFeed,
    'isMemberHomePage' => $this->isMemberHomePage,
    'isOnThisDayPage' => $this->isOnThisDayPage,
    'enabledModuleNames' => $enabledModuleNames
  ));
  return; // Do no render the rest of the script in this mode
endif; ?>

<?php if( !empty($this->checkUpdate) ): // if this is for the live update
  if ($this->activityCount){ ?>
   <script type='text/javascript'>
          document.title = '(<?php echo $this->activityCount; ?>) ' + SesadvancedactivityUpdateHandler.title;
          SesadvancedactivityUpdateHandler.options.next_id = "<?php echo $this->firstid; ?>";
          <?php if($this->autoloadfeed){ ?>
            SesadvancedactivityUpdateHandler.getFeedUpdate("<?php echo $this->firstid; ?>");
            document.getElementById("feed-update").empty();
          <?php } ?>
          scriptJquery('#count_new_feed').html("<span><?php echo $this->activityCount; ?></span>");
        </script>
   <div class='tip' style="display:<?php echo ($this->autoloadfeed) ? 'none' : '' ?>">
          <span>
            <a href='javascript:void(0);' onclick='javascript:SesadvancedactivityUpdateHandler.getFeedUpdate("<?php echo $this->firstid ?>");document.getElementById("feed-update").empty();scriptJquery("#count_new_feed").html("");scriptJquery("#count_new_feed").hide();'>
              <?php echo $this->translate(array(
                  '%d new update is available - click this to show it.',
                  '%d new updates are available - click this to show them.',
                  $this->activityCount),
                $this->activityCount); ?>
            </a>
          </span>
        </div>
 <?php } 
  return; // Do no render the rest of the script in this mode
endif; ?>

<?php if( !empty($this->getUpdate) ): // if this is for the get live update ?>
<script type="text/javascript">
     SesadvancedactivityUpdateHandler.options.last_id = <?php echo sprintf('%d', $this->firstid) ?>;
   </script>
<?php endif; ?>
<style>
 #scheduled_post, #datetimepicker_edit{display:block !important;}
 </style>
<?php if($this->design == 2){ ?>
<style>
.sesact_post_container_wrapper:not(._sesadv_composer_active) #compose-container .jqueryHashtags{
  height:60px;
}
</style>

<?php } ?>
<?php if( $this->enableComposer && !$this->isOnThisDayPage): ?>
<script type="application/javascript">
var sesadvancedactivityDesign = '<?php echo $this->design; ?>';
var activitycommentreverseorder = <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('activity.commentreverseorder', 0); ?>;
var userphotoalign = '<?php echo $this->userphotoalign; ?>';
var enableStatusBoxHighlight = '<?php echo $this->enableStatusBoxHighlight; ?>';
var counterLoopComposerItem = counterLoopComposerItemDe4 = 1;
var composeInstance;
 en4.core.runonce.add(function () {
    try {
     composeInstance = new Composer('activity_body',{
        overText : true,
        allowEmptyWithoutAttachment : false,
        allowEmptyWithAttachment : true,
        hideSubmitOnBlur : false,
        submitElement : false,
        useContentEditable : true  ,
        menuElement : 'compose-menu',
        baseHref : '<?php echo $this->baseUrl() ?>',
        lang : {
          'Post Something...' : '<?php echo $this->string()->escapeJavascript($this->translate('Post Something...')) ?>'
        }
    });
     }catch(err){ console.log(err); }
      
      scriptJquery(document).on('submit','#activity-form',function(e) {
        if(typeof musicfeedupload != 'undefined' && musicfeedupload) {
          return;
        }
        if(scriptJquery(this).hasClass("_request-going")){
          return false;
        }
        var activatedPlugin = composeInstance.getActivePlugin();
        if(activatedPlugin)
         var pluginName = activatedPlugin.getName();
        else 
          var pluginName = '';

        if(scriptJquery('#image_id').length > 0 && scriptJquery('#image_id').val() != '' || scriptJquery('#reaction_id').val() != '' || scriptJquery('#tag_location').val() != '' || ( scriptJquery('#feeling_activity').length > 0 && scriptJquery('#feeling_activity').val() != '' && scriptJquery('#feelingactivityid').val() != '')) {
          //silence  
        }else if(pluginName != 'buysell' && pluginName != 'quote' && pluginName != 'wishe' && pluginName != 'prayer' && pluginName != 'thought' && pluginName != 'text' && pluginName != 'sespagepoll' && pluginName != 'sesbusinesspoll' && pluginName != 'sesgrouppoll'){
          if( composeInstance.pluginReady ) {
            if( !composeInstance.options.allowEmptyWithAttachment && composeInstance.getContent() == '' ) {
              scriptJquery('.sesact_post_box').addClass('_blank');
              e.preventDefault();
              return;
            }
          } else {
            if( !composeInstance.options.allowEmptyWithoutAttachment && composeInstance.getContent() == '' ) {
              e.preventDefault();
              scriptJquery('.sesact_post_box').addClass('_blank');
              return;
            }
          }
        }else if (pluginName == "sespagepoll"){
			var isValidPoll = checkValidationPagePoll();
			if(isValidPoll == false){
				e.preventDefault();
				return;
			}
		}else if(pluginName == "sesbusinesspoll"){
				var isValidPoll = checkValidationBusinessPoll();
			if(isValidPoll == false){
				e.preventDefault();
				return;
			}
		}
		else if(pluginName == "sesgrouppoll"){
				var isValidPoll = checkValidationGroupPoll();
			if(isValidPoll == false){
				e.preventDefault();
				return;
			}
		}
		else if(pluginName == 'buysell'){
          if(!scriptJquery('#buysell-title').val()){
              if(!scriptJquery('.buyselltitle').length) {
                var errorHTMlbuysell = '<div class="sesact_post_error buyselltitle"><?php echo $this->translate("Please enter the title of your product.");?></div>';
                scriptJquery('.sesact_sell_composer_title').append(errorHTMlbuysell);
                scriptJquery('#buysell-title').parent().addClass('_blank');
                scriptJquery('#buysell-title').css('border','1px solid red');
              }
              e.preventDefault();
              return;
          }
          if(scriptJquery('#buy-url').val() && !isUrl(scriptJquery('#buy-url').val())){
              if(!scriptJquery('.buyurl').length) {
                var errorHTMlbuyurl = '<div class="sesact_post_error buyselltitle"><?php echo $this->translate("Please enter valid url.");?></div>';
                scriptJquery('.sesact_sell_composer_title').append(errorHTMlbuyurl);
                scriptJquery('#buy-url').parent().addClass('_blank');
                scriptJquery('#buy-url').css('border','1px solid red');
              }
              e.preventDefault();
              return;
          }else if(!scriptJquery('#buysell-price').val()){
              if(!scriptJquery('.buysellprice').length) {
                var errorHTMlbuysell = '<div class="sesact_post_error buysellprice"><?php echo $this->translate("Please enter the price of your product.");?></div>';
                scriptJquery('.sesact_sell_composer_price').append(errorHTMlbuysell);
                scriptJquery('#buysell-price').parent().parent().addClass('_blank');
                scriptJquery('#buysell-price').css('border','1px solid red');
              }
              e.preventDefault();
              return;
          }
          
            var field = '<input type="hidden" name="attachment[type]" value="buysell">';
            if(!scriptJquery('.fileupload-cnt').length)
              scriptJquery('#activity-form').append('<div style="display:none" class="fileupload-cnt">'+field+'</div>');
            else
              scriptJquery('.fileupload-cnt').html(field);
              
        } else if(pluginName == 'quote') {
          if(!scriptJquery('#quote-description').val()){
            if(!scriptJquery('.quotedescription').length) {
              var errorHTMlquote = '<div class="sesact_post_error quotedescription"><?php echo $this->translate("Please enter the quote.");?></div>';
              scriptJquery('.sesact_quote_composer_title').append(errorHTMlquote);
              scriptJquery('#quote-description').parent().addClass('_blank');
              scriptJquery('#quote-description').css('border','1px solid red');
            }
            e.preventDefault();
            return;
          }
          //Video check if choose from media type
          if(scriptJquery("input[name='mediatype']:checked").val() == 2 && scriptJquery('#video').val() == '') {
            if(!scriptJquery('#video').val()) {
              var errorHTMlquote = '<div class="sesact_post_error quotedescription"><?php echo $this->translate("Please enter the video url.");?></div>';
              scriptJquery('.sesact_quote_composer_title').append(errorHTMlquote);
              scriptJquery('#video').parent().addClass('_blank');
              scriptJquery('#video').css('border','1px solid red');
            }
            e.preventDefault();
            return;
          }
        } else if(pluginName == 'wishe') {
          if(!scriptJquery('#wishe-description').val()){
            if(!scriptJquery('.wishedescription').length) {
              var errorHTMlwishe = '<div class="sesact_post_error wishedescription"><?php echo $this->translate("Please enter the wishe.");?></div>';
              scriptJquery('.sesact_wishe_composer_title').append(errorHTMlwishe);
              scriptJquery('#wishe-description').parent().addClass('_blank');
              scriptJquery('#wishe-description').css('border','1px solid red');
            }
            e.preventDefault();
            return;
          }
        } else if(pluginName == 'prayer') {
          if(!scriptJquery('#prayer-description').val()){
            if(!scriptJquery('.prayerdescription').length) {
              var errorHTMlprayer = '<div class="sesact_post_error prayerdescription"><?php echo $this->translate("Please enter the prayer.");?></div>';
              scriptJquery('.sesact_prayer_composer_title').append(errorHTMlprayer);
              scriptJquery('#prayer-description').parent().addClass('_blank');
              scriptJquery('#prayer-description').css('border','1px solid red');
            }
            e.preventDefault();
            return;
          }
        } else if(pluginName == 'thought') {
          if(!scriptJquery('#thought-description').val()){
            if(!scriptJquery('.thoughtdescription').length) {
              var errorHTMlthought = '<div class="sesact_post_error thoughtdescription"><?php echo $this->translate("Please enter the thought.");?></div>';
              scriptJquery('.sesact_thought_composer_title').append(errorHTMlthought);
              scriptJquery('#thought-description').parent().addClass('_blank');
              scriptJquery('#thought-description').css('border','1px solid red');
            }
            e.preventDefault();
            return;
          }
        }
        else if(pluginName == 'text') {
          if(!scriptJquery('#summernote').val()) {
            if(!scriptJquery('.quotedescription').length) {
              var errorHTMlquote = '<div class="sesact_post_error quotedescription"><?php echo $this->translate("Please enter the description.");?></div>';
              scriptJquery('.sesact_quote_composer_title').append(errorHTMlquote);
              scriptJquery('#summernote').parent().addClass('_blank');
              scriptJquery('#summernote').css('border','1px solid red');
            }
            e.preventDefault();
            return;
          }
        }
        scriptJquery('.sesact_post_box').removeClass('_blank');
      <?php if($this->submitWithAjax){ ?>
        e.preventDefault();
        var url = "<?php echo $this->url(array('module' => 'sesadvancedactivity', 'controller' => 'index', 'action' => 'post'), 'default', true) ?>";
        submitActivityFeedWithAjax(url,'<i class="fas fa-circle-notch fa-spin"></i>','<?php echo $this->translate("Share") ?>',this);
        return;
     <?php } ?>
      });
      
      if(scriptJquery('#hashtagtextsesadv').val() && typeof composeInstance != "undefined") {
        composeInstance.setContent('#'+scriptJquery('#hashtagtextsesadv').val()).trigger('keyup');
      }

      scriptJquery("#activity_body").css("height", "auto");
      
 });
 scriptJquery(document).on('keyup', '#buysell-title, #buysell-price, #buy-url', function() {
  if(!scriptJquery(this).val())
    return;
  scriptJquery(this).parent().removeClass('_blank');
  scriptJquery(this).parent().parent().removeClass('_blank');
  scriptJquery(this).css('border', '');
  scriptJquery(this).parent().find('.sesact_post_error').remove();

 });
</script>

  <?php if($this->enablestatusbox == 0) { ?>
    <?php $display = 'none'; ?>
  <?php } else if($this->enablestatusbox == 1 && $viewer && $this->subject()) { ?>
    <?php if($viewer->getIdentity() && ($viewer->getIdentity() == $this->subject()->getIdentity())) { ?>
      <?php $display = 'block'; ?>
    <?php } else { ?>
      <?php $display = 'none'; ?>
    <?php } ?>
  <?php } else if($this->enablestatusbox == 2) { ?>
    <?php $display = 'block'; ?>
  <?php } ?>
  <div class="sesact_post_container_wrapper sesbasic_clearfix sesbasic_bxs <?php if($this->design == 2){ ?>sesact_cd_p<?php } ?>">
	<div class="sesact_post_container_overlay"></div>
	<div class="sesact_post_container sesbasic_clearfix" style="display:<?php echo $display ?>;">
    <form enctype="multipart/form-data" method="post" action="<?php echo $this->url(array('module' => 'sesadvancedactivity', 'controller' => 'index', 'action' => 'post'), 'default', true) ?>" class="" id="activity-form">
      
    	<div class="sesact_post_box sesbasic_clearfix" id="sesact_post_box_status">
      	
        <div class="sesact_post_box_img" id="sesact_post_box_img">
        <?php 
        echo $this->htmlLink('javascript:;', $this->itemPhoto($this->viewer(), 'thumb.icon', $this->viewer()->getTitle()), array()) ?>
        </div>
       <?php if($this->design == 2){ ?>
        <div class="sesact_post_box_close" style="display:none;"><a class="fas fa-times sesact_post_box_close_a sesadv_tooltip" title="<?php echo $this->escape($this->translate('Close')) ?>" href="javascript:;"></a></div>
       <?php } ?>
        <textarea style="display:none;" id="activity_body" class="resetaftersubmit" cols="1" rows="1" name="body" placeholder="<?php echo $this->escape($this->translate($this->statusplacehoder)) ?>"></textarea>
        <input type="hidden" name="return_url" value="<?php echo $this->url() ?>" />
        <?php if( $this->viewer() && $this->subject() && !$this->viewer()->isSelf($this->subject())): ?>
          <input type="hidden" name="subject" value="<?php echo $this->subject()->getGuid() ?>" />
        <?php endif; ?>
        <input type="hidden" name="crosspostVal" id="crosspostVal"  class="resetaftersubmit" value="">
        <input type="hidden" name="reaction_id" class="resetaftersubmit" id="reaction_id" value="" />
        <?php if( $this->formToken ): ?>
          <input type="hidden" name="token" value="<?php echo $this->formToken ?>" />
        <?php endif ?>
         <input type="hidden" id="hashtagtextsesadv" name="hashtagtextsesadv" value="<?php echo isset($_GET['hashtag']) ? $_GET['hashtag'] : ''; ?>" />
        <input type="hidden" name="fancyalbumuploadfileids" class="resetaftersubmit" id="fancyalbumuploadfileids">
        <div class="sesact_post_error"><?php echo $this->translate("It seems, that the post is blank. Please write or attach something to share your post.");?></div>
         <div id="sesact_post_tags_sesadv" class="sesact_post_tags sesbasic_text_light" <?php if(defined('SESFEEDBGENABLED')) { ?> style="display:none;" <?php } ?>>
            <?php if(engine_in_array('sesfeelingactivity',$enabledModuleNames) && $settings->getSetting('sesfeelingactivity.enablefeeling', 1)): ?><span style="display:none;" id="feeling_elem_act">- </span><?php endif; ?> <span style="display:none;" id="dash_elem_act">-</span>	<?php if(engine_in_array('sespage',$enabledModuleNames)): ?><span style="display:none;" id="sespage_elem_act"></span><?php endif; ?><span id="tag_friend_cnt" style="display:none;"> with </span> <span id="location_elem_act" style="display:none;"></span>
          </div>
        <?php if(defined('SESFEEDBGENABLED') && $settings->getSetting('sesfeedbg.enablefeedbg', 1)) {  ?>
            <?php
            $sesfeedbg_enablefeedbg = false;
            $enablefeedbg = (array) $levelAdapter->getAllowed('sesadvactivity', $viewer, 'composeroptions');
            if(engine_in_array('enablefeedbg', $enablefeedbg)) {
              $sesfeedbg_enablefeedbg = true;
            }
            ?>
            <?php $sesfeedbg_limit_show = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sesadvactivity', 'sesfeedbg_max'); ?>
            <?php if($sesfeedbg_enablefeedbg) { ?>
              <?php 
              $getFeaturedBackgrounds = Engine_Api::_()->getDbTable('backgrounds', 'sesfeedbg')->getBackgrounds( array('admin' => 1, 'fetchAll' => 1, 'sesfeedbg_limit_show' => 5, 'featured' => 1) );
              $featured = $backgrounds = array();
              foreach($getFeaturedBackgrounds as $getFeaturedBackground) {
                $featured[] = $getFeaturedBackground->background_id;
              }
              //https://github.com/Vaibhav-Agarwal06/sedev/issues/378
              // if featured images are available show in first then rest of images are come according to member level.
              // featured + member_level
              if(engine_count($featured) > 5) {
                $sesfeedbg_limit_show = 5;
              }
              $getBackgrounds = Engine_Api::_()->getDbTable('backgrounds', 'sesfeedbg')->getBackgrounds( array('admin' => 1, 'fetchAll' => 1, 'sesfeedbg_limit_show' => $sesfeedbg_limit_show, 'featuredbgIds' => $featured)); 
              foreach($getBackgrounds as $getBackground) {
                $backgrounds[] = $getBackground->background_id;
              }
              if(engine_count($featured) > 0) {
                $backgrounds = array_merge($featured, $backgrounds);
              }
              ?>
              <?php if( engine_count( $backgrounds ) > 0 ) { ?>
                <div id="feedbg_main_continer" style="display:none;">
                  <a href="javascript:void(0);" id="hideshowfeedbgcont"><i onclick="hideshowfeedbgcont();" class="fa fa-angle-left"></i></a>
                  <ul id="feedbg_content">
                    <li>
                      <a class="feedbg_active" id="feedbg_image_defaultimage" href="javascript:void(0);" onclick="feedbgimage('defaultimage')"><img height="30px;" width="30px;" id="feed_bg_image_defaultimage" alt="" src="<?php echo 'application/modules/Sesfeedbg/externals/images/white.png'; ?>" /></a>
                    </li>
                    <?php foreach($backgrounds as $getBackground) {
                      $getBackground = Engine_Api::_()->getItem('sesfeedbg_background', $getBackground);
                    ?>
                      <?php if($getBackground->file_id) {
                        $photo = Engine_Api::_()->storage()->get($getBackground->file_id, '');
                        if($photo) {
                          $photo = $photo->getPhotoUrl(); ?>
                       <li>
                         <a id="feedbg_image_<?php echo $getBackground->background_id; ?>" href="javascript:void(0);" onclick="feedbgimage('<?php echo $getBackground->background_id; ?>', 'photo');setFocus();"><img height="30px;" width="30px;" id="feed_bg_image_<?php echo $getBackground->background_id; ?>" data-id="<?php echo $getBackground->background_id; ?>" alt="" src="<?php echo $photo; ?>" /></a>
                       </li>
                      <?php  }
                      }
                      ?>
                    <?php } ?>
  <!--                  <li class="_more">
                      <a href="#" class="sesadv_tooltip" title='<?php //echo $this->translate("More"); ?>'><i class="fa fa-th-large"></i></a>
                    </li>-->
                    <?php  ?>
                  </ul>
                  <input type="hidden" name="feedbgid" id="feedbgid" value="" class="resetaftersubmit">
                  <input type="hidden" name="feedbgid_isphoto" id="feedbgid_isphoto" value="1" class="resetaftersubmit">
                </div>
              <?php } ?>
            <?php } ?>
          <?php }  ?>
		<div id="sesadvancedactivity-menu" class="sesadvancedactivity-menu sesact_post_tools">
          <span class="sesadvancedactivity-menu-selector" id="sesadvancedactivity-menu-selector"></span>
          
        <?php if($this->design == 1) { ?>
          <?php if(engine_in_array('shedulepost',$this->composerOptions)){ ?>
            <?php $enableShedulepost = (array) $levelAdapter->getAllowed('sesadvactivity', $viewer, 'composeroptions'); ?>
            <?php if(engine_in_array('shedulepost', $enableShedulepost)) { ?>

              <?php $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/schedule/bootstrap.min.js'); ?>
              <?php $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/schedule/bootstrap-datetimepicker.min.js'); ?>
              <script type="text/javascript"> var enabledShedulepost = 1; </script>
              <div class="sesact_popup_overlay sesadvancedactivity_shedulepost_overlay" style="display:none;"></div>
              <div class="sesact_popup sesadvancedactivity_shedulepost_select sesbasic_bxs" style="display:none;">
                <div class="sesact_popup_header"><?php echo $this->translate("Schedule Post"); ?></div>
                <div class="sesact_popup_cont">
                  <b><?php echo $this->translate("Schedule Your Post"); ?></b>
                  <p><?php echo $this->translate("Select date and time on which you want to publish your post."); ?></p>
                  <div class="sesact_time_input_wrapper">
                    <div id="datetimepicker" class="input-append date sesact_time_input">
                      <input type="text" name="scheduled_post" id="scheduled_post" class="resetaftersubmit"></input>
                      <span class="add-on" title="Select Time"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                    </div>
                    <div class="sesact_error sesadvancedactivity_shedulepost_error"></div>
                  </div>
                </div>
                <div class="sesact_popup_btns">
                 <button type="submit" class="schedule_post_schedue"><?php echo $this->translate("Schedule"); ?></button>
                 <button class="close schedule_post_close"><?php echo $this->translate("Cancel"); ?></button>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
          <?php if(engine_in_array('tagUseses',$this->composerOptions)){ ?>
            <span class="sesact_post_tool_i tool_i_tag">
              <a href="javascript:;" id="sesadvancedactivity_tag" class="sesadv_tooltip" title="<?php echo $this->translate('Tag People'); ?>">&nbsp;</a>
            </span>
          <?php } ?>
          <?php if(engine_in_array('locationses',$this->composerOptions) && $settings->getSetting('enableglocation', 1)){ ?>
            <span class="sesact_post_tool_i tool_i_location">
              <a href="javascript:;" id="sesadvancedactivity_location" title="<?php echo $this->translate('Check In'); ?>" class="sesadv_tooltip">&nbsp;</a>
            </span>
          <?php } ?>
          <?php if(engine_in_array('smilesses',$this->composerOptions)){ ?>
          	<?php if(engine_in_array('sesadvancedcomment',$enabledModuleNames) && $settings->getSetting('sesadvancedcomment.pluginactivated')) { ?>
            	<span class="sesact_post_tool_i tool_i_sticker">
                <a href="javascript:;" class="sesadv_tooltip emoji_comment_select activity_emoji_content_a" title="<?php echo $this->translate('Stickers'); ?>">&nbsp;</a>
              </span>  
            <?php } else { ?>
            	<span class="sesact_post_tool_i tool_i_emoji">
                <a href="javascript:;" class="sesadv_tooltip emoji_comment_select activity_emoji_content_a" title="<?php echo $this->translate('Emoticons'); ?>">&nbsp;</a>
          		</span>
          	<?php } ?>
          <?php } ?>
          <?php if(engine_in_array('sesfeelingactivity',$enabledModuleNames) && $settings->getSetting('sesfeelingactivity.enablefeeling', 1)) { ?>
            <?php $enablefeeling = (array) $levelAdapter->getAllowed('sesadvactivity', $viewer, 'composeroptions'); ?>
            <?php if(engine_count($getFeelings) > 0 && engine_in_array('feelingssctivity',$this->composerOptions) && engine_in_array('feelingssctivity', $enablefeeling)): ?>
              <span class="sesact_post_tool_i tool_i_feelings" id="sesadvancedactivity_feelings">
                <a href="javascript:;" id="sesadvancedactivity_feelingsa" class="sesadv_tooltip" title="<?php echo $this->translate('Feeling/Activity'); ?>">&nbsp;</a>
              </span>
            <?php endif; ?>
          <?php } ?>
           
          <?php if(defined('SESFEEDGIFENABLED') && engine_in_array('sesfeedgif',$this->composerOptions)) { ?>
            <?php $enable = (array) $levelAdapter->getAllowed('sesadvactivity', $viewer, 'cmtattachement'); ?>
            <?php if(engine_in_array('gif', $enable)) { ?>
              <span class="sesact_post_tool_i tool_i_gif">
                <a href="javascript:;" class="sesadv_tooltip gif_comment_select activity_gif_content_a" title="<?php echo $this->translate('GIF'); ?>">&nbsp;</a>
              </span>
              <input type="hidden" name="image_id" class="resetaftersubmit" id="image_id" value="" />
            <?php } ?>
          <?php } ?>
        <?php } ?>
				<?php $enableattachement = (array) $levelAdapter->getAllowed('sesadvactivity', $viewer, 'cmtattachement'); ?>
				<?php $emojiEnable = Engine_Api::_()->authorization()->getPermission($viewer->level_id, 'sesemoji', 'enableemojis'); ?>
        <?php if($emojiEnable && engine_in_array('sesemoji',$enabledModuleNames)) { ?>
          <?php if(engine_count($getEmojis) > 0 && engine_in_array('emojisses',$this->composerOptions) && $settings->getSetting('sesemoji.enableemoji', 1) && (engine_in_array('emotions', $enableattachement) || engine_in_array('emojis', $enableattachement) )): ?>
            <span class="sesact_post_tool_i tool_i_feelings feeling_emoji_comment_select" id="sesadvancedactivity_feeling_emojis">
              <a href="javascript:;" id="sesadvancedactivity_feeling_emojisa" class="sesadv_tooltip" title="<?php echo $this->translate('Emojis'); ?>">&nbsp;</a>
            </span>
          <?php endif; ?>
        <?php } else { 
        if(engine_in_array('emotions', $enableattachement)): ?>
          <span class="sesact_post_tool_i tool_i_emoji">
            <a href="javascript:;" id="sesadvancedactivityemoji-statusbox"  class="sesadv_tooltip" title="<?php echo $this->translate('Emoticons'); ?>">&nbsp;</a>
            <div id="sesadvancedactivityemoji_statusbox" class="ses_emoji_container sesbasic_bxs">
              <div class="ses_emoji_container_arrow"></div>
              <div class="ses_emoji_container_inner sesbasic_clearfix">
                <div class="ses_emoji_holder">
                  <div class="sesbasic_loading_container" style="height:100%;"></div>
                </div>
              </div>
            </div>
          </span>
        <?php endif; } ?> 
        
        </div>
      </div>
      
      <div id="sescomposer-tray-container"></div>
      <div class="sesact_post_tag_container sesbasic_clearfix sesact_post_tag_cnt" style="display:none;">
        <span class="tag">With</span>
        <div class="sesact_post_tags_holder">
          <div id="toValues-element">
          </div>
        	<div class="sesact_post_tag_input">
          	<input type="text" class="resetaftersubmit" placeholder="<?php echo $this->translate('Who are you with?'); ?>" id="tag_friends_input" />
            <div id="toValues-wrapper" style="display:none">
            <input type="hidden" id="toValues" name="tag_friends" class="resetaftersubmit">
            </div>
          </div>
          <a href="javascript:;" class="cancelTagLink"><i class="fa fa-times"></i></a>
        </div>	
      </div>
      <div class="sesact_post_tag_container sesbasic_clearfix sesact_post_location_container" style="display:none;">
        <span class="tag">At</span>
        <div class="sesact_post_tags_holder">
          <div id="locValues-element"></div>
        	<div class="sesact_post_tag_input">
          	<input type="text" placeholder="<?php echo $this->translate('Where are you?'); ?>" name="tag_location" id="tag_location" class="resetaftersubmit"/>
            <input type="hidden" name="activitylng" id="activitylng" value="" class="resetaftersubmit">
            <input type="hidden" name="activitylat" id="activitylat" value="" class="resetaftersubmit">
          </div>
          <a href="javascript:;" class="cancelLink"><i class="fa fa-times"></i></a>
        </div>	
      </div>
      <div id="sesact_page_tags"></div>
       <div id="sesact_business_tags"></div>
        <div id="sesact_group_tags"></div>
      <?php // Feeling work ?>
      <?php if(engine_in_array('sesfeelingactivity',$enabledModuleNames)) { ?>
        <div id="sesact_post_feeling_container" class="sesact_post_tag_container sesbasic_clearfix sesact_post_feeling_container" style="display:none;">
          <span id="feelingActType" class="tag" style="display:none;"></span>
          <div class="sesact_post_tags_holder">
            <div id="feelingValues-element"></div>
            <div class="sesact_post_tag_input">
              <input autofocus autocomplete="off" type="text" placeholder="<?php echo $this->translate('Choose Feeling or activity...'); ?>" name="feeling_activity" id="feeling_activity" class="resetaftersubmit"/>
              
              <a onclick="feelingactivityremoveact();" style="display:none;" href="javascript:void(0);" class="feeling_activity_remove_act notclose" id="feeling_activity_remove_act" title="<?php echo $this->translate('Remove'); ?>">x</a>
              
              <input type="hidden" name="feelingactivityid" id="feelingactivityid" value="" class="resetaftersubmit">
              <input type="hidden" name="feelingactivityiconid" id="feelingactivityiconid" value="" class="resetaftersubmit">
              <input type="hidden" name="feelingactivity_resource_type" id="feelingactivity_resource_type" value="" class="resetaftersubmit">
              <input type="hidden" name="feelingactivity_custom" id="feelingactivity_custom" value="" class="resetaftersubmit">
              <input type="hidden" name="feelingactivity_customtext" id="feelingactivity_customtext" value="" class="resetaftersubmit">
              <input type="hidden" name="feelingactivity_type" id="feelingactivity_type" value="" class="resetaftersubmit">
            </div>
          </div>
          
          <div class="sesact_post_feelingautocompleter_container sesact_post_feelings_autosuggest" style="display:none;">
          	<div class="sesbasic_clearfix sesbasic_custom_scroll">
              <ul class="sesfeelingactivity-ul" id="showSearchResults"></ul>
            </div>	
          </div>
          
          <div class="sesact_post_feelingcontent_container sesact_post_feelings_autosuggest" style="display:none;">
          	<div class="sesbasic_clearfix sesbasic_custom_scroll">
              <ul id="all_feelings">
                <?php $feelings = Engine_Api::_()->getDbTable('feelings', 'sesfeelingactivity')->getFeelings(array('fetchAll' => 1, 'admin' => 0));  ?>
                <?php foreach($feelings as $feeling): ?>
                  <?php $photo = Engine_Api::_()->storage()->get($feeling->file_id, '');
                      if($photo) {
                      $photo = $photo->getPhotoUrl(); ?>
                  <li data-title="<?php echo $feeling->title; ?>" class="sesact_feelingactivitytype sesbasic_clearfix" data-rel="<?php echo $feeling->feeling_id; ?>" data-type="<?php echo $feeling->type; ?>">
                    <a href="javascript:void(0);" class="sesact_feelingactivitytypea">
                      <img id="sesactfeelingactivitytypeimg_<?php echo $feeling->feeling_id; ?>" title="<?php echo $feeling->title ?>" src="<?php echo $photo; ?>">
                      <span><?php echo $this->translate($feeling->title); ?></span>
                    </a>
                  </li>
                  <?php } ?>
                <?php endforeach; ?>
              </ul>
            </div>  
          </div>	
        </div>
        
      <?php } ?>
      <?php // Feeling work ?>
      
      <?php if($this->design == 2) { ?>
        <div class="sesact_post_media_options sesbasic_clearfix">
          <div id="sesact_post_media_options_before"></div>
          <?php if(engine_in_array('shedulepost',$this->composerOptions)){ ?>
            <?php $enableShedulepost = (array) $levelAdapter->getAllowed('sesadvactivity', $viewer, 'composeroptions'); ?>
            <?php if(engine_in_array('shedulepost', $enableShedulepost)) { ?>
              <?php $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/schedule/bootstrap.min.js'); ?>
              <?php $this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/schedule/bootstrap-datetimepicker.min.js'); ?>
              <script type="text/javascript"> var enabledShedulepost = 1; </script>
              <div class="sesact_popup_overlay sesadvancedactivity_shedulepost_overlay" style="display:none;"></div>
              <div class="sesact_popup sesadvancedactivity_shedulepost_select sesbasic_bxs" style="display:none;">
                <div class="sesact_popup_header"><?php echo $this->translate('Schedule Post'); ?></div>
                <div class="sesact_popup_cont">
                  <b><?php echo $this->translate("Schedule Your Post"); ?></b>
                  <p><?php echo $this->translate("Select date and time on which you want to publish your post."); ?></p>
                  <div class="sesact_time_input_wrapper">
                    <div id="datetimepicker" class="input-append date sesact_time_input">
                      <input type="text" name="scheduled_post" id="scheduled_post" class="resetaftersubmit"></input>
                      <span class="add-on sesadv_tooltip" title="View Calendar"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                    </div>
                    <div class="sesact_error sesadvancedactivity_shedulepost_error"></div>
                  </div>
                </div>
                <div class="sesact_popup_btns">
                 <button type="submit" class="schedule_post_schedue"><?php echo $this->translate('Schedule'); ?></button>
                 <button class="close schedule_post_close"><?php echo $this->translate('Cancel'); ?></button>
                </div>
              </div>
            <?php } ?>
          <?php } ?>
          <?php if(engine_in_array('tagUseses',$this->composerOptions)){ ?>
            <span class="sesact_post_media_options_icon tool_i_tag" style="display:none;">
              <a href="javascript:;" id="sesadvancedactivity_tag" class="sesadv_tooltip" title="<?php echo $this->translate('Tag People'); ?>"><span><?php echo $this->translate('Tag People'); ?></span></a>
            </span>
          <?php } ?>
          <?php if($this->isGoogleApiKeySaved){ ?>
            <?php if(engine_in_array('locationses',$this->composerOptions)){ ?>
                <?php $enable = (array) $levelAdapter->getAllowed('sesadvactivity', $viewer, 'composeroptions'); ?>
                <?php if(engine_in_array('locationses', $enable)) { ?>
                  <span class="sesact_post_media_options_icon tool_i_location" style="display:none;">
                    <a href="javascript:;" id="sesadvancedactivity_location" title="Check In" class="sesadv_tooltip"><span><?php echo $this->translate('Check In'); ?></span></a>
                  </span>
              <?php } ?>
            <?php } ?>
          <?php } ?>
          <?php if(engine_in_array('smilesses',$this->composerOptions)){ ?>
          	<?php if(engine_in_array('sesadvancedcomment',$enabledModuleNames) && $settings->getSetting('sesadvancedcomment.pluginactivated')) { ?>
              <?php if($settings->getSetting('sesadvancedcomment.enablestickers', 1) && engine_in_array('stickers',$this->composerOptions) && engine_in_array('stickers', $enableattachement)){ ?>
              	<span class="sesact_post_media_options_icon tool_i_sticker" style="display:none;">
                	<a href="javascript:;" class="sesadv_tooltip emoji_comment_select activity_emoji_content_a" title="<?php echo $this->translate('Stickers'); ?>"><span class="emoji_comment_select"><?php echo $this->translate('Stickers'); ?></span></a>
                </span>
              <?php } ?>
            <?php } else { ?>
              <span class="sesact_post_media_options_icon tool_i_emoji" style="display:none;">	
              	<a href="javascript:;" class="sesadv_tooltip emoji_comment_select activity_emoji_content_a" title="<?php echo $this->translate('Emoticons'); ?>"><span class="emoji_comment_select"><?php echo $this->translate('Emoticons'); ?></span></a>
            	</span>
            <?php } ?>            
          <?php } ?>
          
          <?php //Feeling Work ?>
          <?php if(engine_in_array('sesfeelingactivity',$enabledModuleNames) && $settings->getSetting('sesfeelingactivity.enablefeeling', 1)) { ?>
            <?php $enablefeeling = (array) $levelAdapter->getAllowed('sesadvactivity', $viewer, 'composeroptions'); ?>
            <?php if(engine_count($getFeelings) > 0 && engine_in_array('feelingssctivity',$this->composerOptions) && engine_in_array('feelingssctivity', $enablefeeling)): ?>
              <span class="sesact_post_media_options_icon tool_i_feelings" style="display:none;" id="sesadvancedactivity_feelings">
                <a id="sesadvancedactivity_feelingsa" href="javascript:;"  class="sesadv_tooltip" title="<?php echo $this->translate('Feeling/Activity'); ?>"><span class="sesadvancedactivity_feelingsspan"><?php echo $this->translate('Feeling/Activity'); ?></span></a>
              </span>
            <?php endif; ?>
          <?php } ?>
          <?php if(defined('SESFEEDGIFENABLED') && engine_in_array('sesfeedgif',$this->composerOptions)){ ?>
            <?php $enable = (array) $levelAdapter->getAllowed('sesadvactivity', $viewer, 'cmtattachement'); ?>
            <?php if(engine_in_array('gif', $enable) && engine_in_array('gif', $enableattachement)) { ?>
              <span class="sesact_post_media_options_icon tool_i_gif" style="display:none;">
                <a href="javascript:;" class="sesadv_tooltip gif_comment_select activity_gif_content_a" title="<?php echo $this->translate('GIF'); ?>"><span class="gif_comment_select"><?php echo $this->translate('GIF'); ?></span></a>
                <input type="hidden" name="image_id" class="resetaftersubmit" id="image_id" value="" />
              </span>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
       <?php $privacyFeed = $settings->getSetting('activity.view.privacy'); ?>
       <?php
        $privacyFeedHold = $settings->getSetting($this->viewer()->getIdentity().".activity.user.setting");
        ?>
  
      <div id="compose-menu" class="sesact_compose_menu">
        <input type="hidden" name="privacy" id="privacy"  value="<?php echo !empty($privacyFeedHold) ? $privacyFeedHold : $privacyFeed[0] ; ?>">
        <div class="sesact_compose_menu_btns notclose">
        
        	<div class="sesact_chooser sesact_content_pulldown_wrapper" style="display:none;">
          	<a href="javascript:void(0);" class="sesact_privacy_btn sesact_chooser_btn"><i class="_icon fa fa-users sesbasic_text_light"></i><span><?php echo $this->translate('Select Pages'); ?></span><i class="_arrow fa fa-caret-down"></i></a>
            <div class="sesact_content_pulldown" style="display:none;">
            	<ul class="sesact_content_pulldown_list">
              </ul>
            </div>
          </div>
          
          <?php if($this->allowprivacysetting){ ?>
            <div class="sesact_privacy_chooser sesact_chooser sesact_pulldown_wrapper">
              <a href="javascript:void(0);" class="sesact_privacy_btn sesact_chooser_btn"><i id="sesadv_privacy_icon"></i><span id="adv_pri_option"></span><i class="_arrow fa fa-caret-down"></i></a>
              <div class="sesact_pulldown">
                <div class="sesact_pulldown_cont isicon">
                  <ul class="adv_privacy_optn">
                   
                    <?php if(engine_in_array('everyone',$privacyFeed)){ ?>
                    <li data-src="everyone" class=""><a href="javascript:;"><i class="sesact_public"></i><span><?php echo $this->translate('Everyone'); ?></span></a></li>
                    <?php } ?>
                    <?php if(engine_in_array('networks',$privacyFeed)){ ?>
                    <li data-src="networks"><a href="javascript:;"><i class="sesact_network"></i><span><?php echo $this->translate('Friends & Networks'); ?></span></a></li>
                    <?php } ?>
                    <?php if(engine_in_array('friends',$privacyFeed)){ ?>
                    <li data-src="friends"><a href="javascript:;"><i class="sesact_friends"></i><span><?php echo $this->translate('Friends Only'); ?></span></a></li>
                    <?php } ?>
                    <?php if(engine_in_array('onlyme',$privacyFeed)){ ?>
                    <li data-src="onlyme"><a href="javascript:;"><i class="sesact_me"></i><span><?php echo $this->translate('Only Me'); ?></span></a></li>
                    <?php } ?>
                    <?php if($this->allownetworkprivacy){ ?>
                    <?php if(engine_count($this->usernetworks)){ ?>
                    <li class="_sep"></li>
                    <?php foreach($this->usernetworks as $usernetworks){ ?>
                      <li data-src="network_list" class="network sesadv_network" data-rel="<?php echo $usernetworks->getIdentity(); ?>"><a href="javascript:;"><i class="sesact_network"></i><span><?php echo $this->translate($usernetworks->getTitle()); ?></span></a></li>
                    <?php }
                    if(engine_count($this->usernetworks) > 1){
                     ?>
                    <li class="multiple mutiselect" data-rel="network-multi"><a href="javascript:;"><i class="sesact_network"></i><span><?php echo $this->translate('Multiple Networks'); ?></span></a></li>
                    <?php 
                      }
                    } ?>
                    <?php } ?>
                    <?php if($this->allowlistprivacy){ ?>
                    <?php if(engine_count($this->userlists)){ ?>
                    <li class="_sep"></li>
                    <?php foreach($this->userlists as $userlists){ ?>
                      <li data-src="members_list" class="lists sesadv_list" data-rel="<?php echo $userlists->getIdentity(); ?>"><a href="javascript:;"><i class="sesact_list"></i><span><?php echo $this->translate($userlists->getTitle()); ?></span></a></li>
                    <?php } 
                     if(engine_count($this->userlists) > 1){
                    ?>
                    <li class="multiple mutiselect" data-rel="lists-multi"><a href="javascript:;"><i class="sesact_list"></i><span><?php echo $this->translate('Multiptle Lists'); ?></span></a></li>
                    <?php 
                      }
                    } ?>
                    <?php } ?>
                  </ul>
                </div>													
              </div>
            </div>
          <?php } ?>
        	<button id="compose-submit" type="submit"><?php echo $this->translate("Share") ?></button>
        </div>
        <span class="composer_crosspost_toggle sesadv_tooltip" href="javascript:void(0);" title="<?php echo $this->translate('Crosspost');?>" style="display:none;"></span>
      </div>
  	</form>
  <?php //if($this->design == 2){ ?>
    <div class="sesact_popup_overlay sesact_confirmation_popup_overlay" style="display:none;"></div>
    <div class="sesact_popup sesact_confirmation_popup sesbasic_bxs" style="display:none;">
      <div class="sesact_popup_header"><?php echo $this->translate("Finish Your Post?"); ?></div>
      <div class="sesact_popup_cont"><?php echo $this->translate("If you leave now, your post won't be saved."); ?></div>
      <div class="sesact_popup_btns">
        <button id="discard_post"><?php echo $this->translate("Discard Post"); ?></button>
        <button id="goto_post"><?php echo $this->translate("Go to Post"); ?></button>
      </div>
    </div>
  <?php //} ?>
     <?php $this->headScript()->appendFile($staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>

    
    <?php
      $this->headScript()
        ->appendFile($staticBaseUrl . 'externals/mdetect/mdetect' . ( APPLICATION_ENV != 'development' ? '.min' : '' ) . '.js')
        ->appendFile($staticBaseUrl . 'application/modules/Sesadvancedactivity/externals/scripts/composer.js');
    ?>

     
    <?php foreach( $this->composePartials as $partial ): ?>
      <?php echo $this->partial($partial[0], $partial[1], array('isMemberHomePage' => $this->isMemberHomePage)) ?>
    <?php endforeach; ?>
    
  </div>
  </div>
<?php endif; ?>
<?php unset($levelAdapter); ?>
<script type="text/javascript">
    scriptJquery(document).on('click',':not(#sesadvancedactivityemoji-statusbox)',function(){
        if(scriptJquery("#sesadvancedactivityemoji-statusbox")){
          if(scriptJquery("#sesadvancedactivityemoji-statusbox").hasClass('active')){
            scriptJquery("#sesadvancedactivityemoji-statusbox").removeClass('active');
            scriptJquery("#sesadvancedactivityemoji_statusbox").hide();
          }
        }
      });
      scriptJquery(document).on('click','#sesadvancedactivity_tag, .cancelTagLink, .sestag_clk',function(e){
         scriptJquery('.sesact_post_tag_cnt').toggle();
         scriptJquery(this).toggleClass('active');
      });
      scriptJquery(document).on('click','#sesadvancedactivity_location, .cancelLink, .seloc_clk',function(e){
        that = scriptJquery(this);
        if(scriptJquery(this).hasClass('.seloc_clk'))
           that = scriptJquery('#sesadvancedactivity_location');
         if(scriptJquery(this).hasClass('active')){
           scriptJquery(this).removeClass('active');
           scriptJquery('.sesact_post_location_container').hide();
           return;
         }

         scriptJquery('.sesact_post_location_container').show();
         scriptJquery(this).addClass('active');
      });
      
      <?php if(defined('SESFEEDBGENABLED')) { ?>
        
        function hideshowfeedbgcont() {

          if(!scriptJquery('#feedbg_content').hasClass('sesfeedbg_feedbg_small_content')) {
            //document.getElementById('feedbg_content').style.display = 'none';
            scriptJquery('#feedbg_content').addClass('sesfeedbg_feedbg_small_content');
            scriptJquery('#hideshowfeedbgcont').html('<i onclick="hideshowfeedbgcont();" class="fa fa-angle-right right_img"></i>');
          } else {
            //document.getElementById('feedbg_content').style.display = 'block';
            scriptJquery('#feedbg_content').removeClass('sesfeedbg_feedbg_small_content');
            scriptJquery('#hideshowfeedbgcont').html('<i onclick="hideshowfeedbgcont();" class="fa fa-angle-left"></i>');
          }
        }
        
        function feedbgimage(feedbgid, type) {
          var feedbgidval = scriptJquery('#feedbgid').val();
          if(feedbgid == 'defaultimage') {
            scriptJquery('#activity-form').removeClass('feed_background_image');
            scriptJquery('.sesact_post_box').css("background-image","none");
            scriptJquery('#feedbgid').val(0);
            scriptJquery('#feedbgid_isphoto').val(0);
            scriptJquery("#feedbg_main_continer > ul > li > a").removeClass('feedbg_active');
            scriptJquery('#feedbg_image_'+feedbgid).addClass('feedbg_active');
            scriptJquery('#activity_body').focus();
          } else {
            if(feedbgidval)
              scriptJquery('#feedbg_image_'+feedbgidval).removeClass('feedbg_active');
            else
              scriptJquery('#feedbg_image_defaultimage').removeClass('feedbg_active');
              
            if(type == 'photo') {
              var imgSource = scriptJquery('#feed_bg_image_'+feedbgid).attr('src');
            } else if(type == 'video') {
              var imgSource = scriptJquery('#feed_bg_image_'+feedbgid).attr('data-src');
              
            }
            scriptJquery('#activity-form').addClass('feed_background_image');
            if(type == 'photo') {
              scriptJquery('#sesfeedbg_videoid').remove();
              scriptJquery('.sesact_post_box').css("background-image","url("+ imgSource +")");
            }
            scriptJquery('#feedbgid').val(feedbgid);
            scriptJquery('#feedbg_image_'+feedbgid).addClass('feedbg_active');
            scriptJquery('#feedbgid_isphoto').val(1);
          }
        }
      <?php } ?>
      
      //Feeling Work
      <?php if(engine_in_array('sesfeelingactivity',$enabledModuleNames)) { ?>
      
          //Click on Feeling/Activity text in status box
          scriptJquery(document).on('click','.sesadvancedactivity_feelingsspan',function(e) {
            scriptJquery(this).parent().parent().trigger('click');
            return;
          });
      
          scriptJquery(document).on('click','#sesadvancedactivity_feelings',function(e) {

            that = scriptJquery(this);
            if(scriptJquery(this).hasClass('.seloc_clk'))
              that = scriptJquery('#sesadvancedactivity_feelings');
            if(scriptJquery(this).hasClass('active')) {
              scriptJquery(this).removeClass('active');
              scriptJquery('.sesact_post_feeling_container').hide();
              scriptJquery('.sesact_post_feelingcontent_container').hide();
                return;
            }
            scriptJquery(this).addClass('active');
            scriptJquery('.sesact_post_feeling_container').show();
            if(scriptJquery('#feelingactivityid').val() == '') {
              scriptJquery('.sesact_post_feelingcontent_container').show();
            }
          });

          scriptJquery(document).click(function(e) {
            if(scriptJquery(e.target).attr('id') != 'sesadvancedactivity_feelingsa' && scriptJquery(e.target).attr('id') != 'feeling_activity' && scriptJquery(e.target).attr('class') != 'sesact_feelingactivitytype'  && scriptJquery(e.target).attr('class') != 'sesact_feelingactivitytypea' && scriptJquery(e.target).attr('id') != 'showFeelingContanier' && scriptJquery(e.target).attr('id') != 'feelingActType' && scriptJquery(e.target).parent().attr('class') != 'sesact_feelingactivitytypea' && scriptJquery(e.target).attr('class') != 'sesadvancedactivity_feelingsspan' && scriptJquery(e.target).attr('class') != 'mCSB_dragger_bar' && scriptJquery(e.target).attr('class') != 'mCSB_dragger' && scriptJquery(e.target).attr('class') != 'mCSB_1_dragger_vertical') {            
              if(scriptJquery('#sesact_post_feeling_container').css('display') == 'table') {
                scriptJquery('.sesact_post_feeling_container').hide();
                scriptJquery('.sesact_post_feelingcontent_container').hide();
                scriptJquery('#feelingActType').html('');
                scriptJquery('#feelingActType').hide();
                scriptJquery('#feeling_activity').attr("placeholder", en4.core.language.translate("Choose Feeling or activity..."));
                scriptJquery('.sesfeelingactivity-ul').html('');
                if(scriptJquery('#sesadvancedactivity_feelings').hasClass('active'))
                  scriptJquery('#sesadvancedactivity_feelings').removeClass('active');
                if(scriptJquery('#feelingactivityid').val())
                  document.getElementById('feelingactivityid').value = '';
                
              } 
            } else if(scriptJquery(e.target).attr('id') == 'feelingActType') {
              scriptJquery('#feelingActType').html('');
              scriptJquery('#feelingActType').hide();
              scriptJquery('#feeling_activity').attr("placeholder", en4.core.language.translate("Choose Feeling or activity..."));
              scriptJquery('.sesfeelingactivity-ul').html('');
              if(scriptJquery('#feelingactivityid').val())
                document.getElementById('feelingactivityid').value = '';
              if(scriptJquery('#feeling_activity').val())
                document.getElementById('feeling_activity').value = '';
              if(scriptJquery('#feelingactivityiconid').val())
                document.getElementById('feelingactivityiconid').value = '';
              scriptJquery('.sesact_post_feelingcontent_container').show();
              scriptJquery('#feeling_elem_act').html('');
            }
          });
          
          scriptJquery(document).on('click', '.sesact_feelingactivitytype', function(e){
      
            var feelingsactivity = scriptJquery(this);
            var feelingId = scriptJquery(this).attr('data-rel');
            var feelingType = scriptJquery(this).attr('data-type');
            var feelingTitle = scriptJquery(this).attr('data-title');
            scriptJquery('#feelingActType').show();
            scriptJquery('#feelingActType').html(feelingTitle);
            scriptJquery('#feeling_activity').attr("placeholder", en4.core.language.translate("How are you feeling?"));
            scriptJquery('#feeling_activity').trigger('focus');
            document.getElementById('feelingactivityid').value = feelingId;
            document.getElementById('feelingactivity_type').value = feelingType;
            scriptJquery('.sesact_post_feelingcontent_container').hide();
            
            //Autocomplete Feeling trigger
            scriptJquery('#feeling_activity').trigger('change').trigger('keyup').trigger('keydown');
            
            //Feed Background Image Work
            if(document.getElementById('feedbgid') && document.getElementById('feelingactivity_type').value == 2) {
              document.getElementById('hideshowfeedbgcont').style.display = 'none';
              scriptJquery('#feedbgid_isphoto').val(0);
              scriptJquery('.sesact_post_box').css('background-image', 'none');
              scriptJquery('#activity-form').removeClass('feed_background_image');
              scriptJquery('#feedbg_content').css('display','none');
            }
          });
          
          
          //Autosuggest Feeling Work
          scriptJquery(document).ready(function() {
            scriptJquery("#feeling_activity").keyup(function() {
              var search_string = scriptJquery("#feeling_activity").val();
              if(search_string == '') {
                search_string = 'default';
              }

              var autocompleteFeeling;
              postdata = {
                'text' : search_string, 
                'feeling_id': document.getElementById('feelingactivityid').value,
                'feeling_type': document.getElementById('feelingactivity_type').value,
              }
              
              if (autocompleteFeeling) {
                autocompleteFeeling.abort();
              }
              
              autocompleteFeeling = scriptJquery.post("<?php echo $this->url(array('module' => 'sesfeelingactivity', 'controller' => 'index', 'action' => 'getfeelingicons'), 'default', true) ?>",postdata,function(data) {
                var parseJson = JSON.parse( data );
                if(parseJson.status == 1 && parseJson.html) {
                  scriptJquery('.sesact_post_feelingautocompleter_container').show();
                  scriptJquery("#showSearchResults").html(parseJson.html);
                } else {
                
                  if(scriptJquery('#feeling_activity').val()) {
                    scriptJquery('.sesact_post_feelingautocompleter_container').show();

                    var html = '<li data-title="'+scriptJquery('#feeling_activity').val()+'" class="sesact_feelingactivitytypeli sesbasic_clearfix" data-rel=""><a href="javascript:void(0);" class="sesact_feelingactivitytypea"><img class="sesfeeling_feeling_icon" title="'+scriptJquery('#feeling_activity').val()+'" src="'+scriptJquery('#sesactfeelingactivitytypeimg_'+scriptJquery('#feelingactivityid').val()).attr('src')+'"><span>'+scriptJquery('#feeling_activity').val()+'</span></a></li>';
                    scriptJquery("#showSearchResults").html(html);
                  } else {
                    scriptJquery('.sesact_post_feelingautocompleter_container').show();
                    scriptJquery("#showSearchResults").html(html);
                  }
                }
              });
            });
          });

          scriptJquery(document).on('click', '.sesact_feelingactivitytypeli', function(e) {

            document.getElementById('feelingactivityiconid').value = scriptJquery(this).attr('data-rel');
            document.getElementById('feelingactivity_resource_type').value = scriptJquery(this).attr('data-type');
            
            if(!scriptJquery(this).attr('data-rel')) {
              document.getElementById('feelingactivity_custom').value = 1;
              document.getElementById('feelingactivity_customtext').value = scriptJquery('#feeling_activity').val();
            }

            if(scriptJquery(this).attr('data-icon')) {
              var finalFeeling = '-- ' + '<img class="sesfeeling_feeling_icon" title="'+scriptJquery(this).attr('data-title').toLowerCase()+'" src="'+scriptJquery(this).attr('data-icon')+'"><span>' + ' ' +  scriptJquery('#feelingActType').html().toLowerCase() + ' ' + '<a href="javascript:;" id="showFeelingContanier" class="" onclick="showFeelingContanier()">'+scriptJquery(this).attr('data-title').toLowerCase()+'</a>';
            } else {
              var finalFeeling = '-- ' + '<img class="sesfeeling_feeling_icon" title="'+scriptJquery(this).attr('data-title').toLowerCase()+'" src="'+scriptJquery(this).find('a').find('img').attr('src')+'"><span>' + ' ' +  scriptJquery('#feelingActType').html().toLowerCase() + ' ' + '<a href="javascript:;" id="showFeelingContanier" class="" onclick="showFeelingContanier()">'+scriptJquery(this).attr('data-title').toLowerCase()+'</a>';
            }
            
            scriptJquery('#sesact_post_tags_sesadv').css('display', 'block');
            scriptJquery('#feeling_activity').val(scriptJquery(this).attr('data-title').toLowerCase());
            scriptJquery('#feeling_elem_act').show();
            scriptJquery('#feeling_elem_act').html(finalFeeling);
            if(!sespageContentSelected)
            scriptJquery('#dash_elem_act').hide();
            scriptJquery('#sesact_post_feeling_container').hide();
          });
          //Autosuggest Feeling Work

            scriptJquery(document).on('click', '#feeling_activity', function(e) {

              if(scriptJquery('#feelingactivityid').val() == '')
                scriptJquery('.sesact_post_feelingcontent_container').show();
            });
            
            scriptJquery(document).on('keyup', '#feeling_activity', function(e) {
            
              socialShareSearch();

              if(!scriptJquery('#feeling_activity').val()) {
                if (e.which == 8) {
                  scriptJquery('#feelingActType').html('');
                  scriptJquery('#feelingActType').hide();
                  scriptJquery('.sesfeelingactivity-ul').html('');
                  if(scriptJquery('#feelingactivityid').val())
                    document.getElementById('feelingactivityid').value = '';
                  if(scriptJquery('#feelingactivityid').val() == '')
                    scriptJquery('.sesact_post_feelingcontent_container').show();
                  
                  var toValueSESFeedbg = scriptJquery('#toValues').val();
                  if(sesFeedBgEnabled && (toValueSESFeedbg.length == 0 && !scriptJquery('#feelingactivityid').val()) && !sespageContentSelected) {
                    scriptJquery('#sesact_post_tags_sesadv').css('display', 'none');
                  }
                  
                  //Feed Background Image Work
                  if(document.getElementById('feedbgid') && document.getElementById('feelingactivity_type').value == 2) {
                    var feedbgid = scriptJquery('#feedbgid').val();
                    document.getElementById('hideshowfeedbgcont').style.display = 'block';
                    scriptJquery('#feedbg_content').css('display','block');
                    var feedagainsrcurl = scriptJquery('#feed_bg_image_'+feedbgid).attr('src');
                    scriptJquery('.sesact_post_box').css("background-image","url("+ feedagainsrcurl +")");
                    scriptJquery('#feedbgid_isphoto').val(1);
                    scriptJquery('#feedbg_main_continer').css('display','block');
                    if(feedbgid) {
                      scriptJquery('#activity-form').addClass('feed_background_image');
                    }
                  }
                }
              }
            });
            
            //static search function
            function socialShareSearch() {

              // Declare variables
              var socialtitlesearch, socialtitlesearchfilter, allsocialshare_lists, allsocialshare_lists_li, allsocialshare_lists_p, i;
              
              socialtitlesearch = document.getElementById('feeling_activity');
              socialtitlesearchfilter = socialtitlesearch.value.toUpperCase();
              allsocialshare_lists = document.getElementById("all_feelings");
              allsocialshare_lists_li = allsocialshare_lists.getElementsByTagName('li');

              // Loop through all list items, and hide those who don't match the search query
              for (i = 0; i < allsocialshare_lists_li.length; i++) {
              
                allsocialshare_lists_a = allsocialshare_lists_li[i].getElementsByTagName("a")[0];


                if (allsocialshare_lists_a.innerHTML.toUpperCase().indexOf(socialtitlesearchfilter) > -1) {
                    allsocialshare_lists_li[i].style.display = "";
                } else {
                  //  allsocialshare_lists_li[i].style.display = "none";
                }
              }
            }
            
            scriptJquery(document).ready(function() {
              scriptJquery('#feeling_activity').keyup(function(e) {
                if (e.which == 8) {
                  document.getElementById('feelingactivityiconid').value = '';
                  document.getElementById('feelingactivity_custom').value = '';
                  document.getElementById('feelingactivity_customtext').value = '';
                  scriptJquery('#feeling_elem_act').html('');
                  //scriptJquery('#feeling_activity').attr("placeholder", "Choose Feeling or activity...");
                }
              });
            });

            function showFeelingContanier() {
            
              if(scriptJquery('#sesact_post_feeling_container').css("display") == 'table') {
                scriptJquery('#showFeelingContanier').removeClass('active');
                scriptJquery('#sesact_post_feeling_container').hide();
              } else {
                scriptJquery('#showFeelingContanier').addClass('active');
                scriptJquery('#feeling_activity_remove_act').show();
                scriptJquery('#sesact_post_feeling_container').show();
              }
            } 
            
            function feelingactivityremoveact() {
              scriptJquery('#feeling_activity_remove_act').hide();
              scriptJquery('#feelingActType').html('');
              scriptJquery('#feelingActType').hide();
              scriptJquery('.sesfeelingactivity-ul').html('');
              if(scriptJquery('#feelingactivityid').val())
                document.getElementById('feelingactivityid').value = '';
              scriptJquery('#feeling_activity').val('');
              document.getElementById('feelingactivityiconid').value = '';
              scriptJquery('#feeling_elem_act').html('');
              //Feed Background Image Work
              if(document.getElementById('feedbgid') && document.getElementById('feelingactivity_type').value == 2) {
                var feedbgid = scriptJquery('#feedbgid').val();
                document.getElementById('hideshowfeedbgcont').style.display = 'block';
                scriptJquery('#feedbg_content').css('display','block');
                var feedagainsrcurl = scriptJquery('#feed_bg_image_'+feedbgid).attr('src');
                scriptJquery('.sesact_post_box').css("background-image","url("+ feedagainsrcurl +")");
                scriptJquery('#feedbgid_isphoto').val(1);
                scriptJquery('#feedbg_main_continer').css('display','block');
                if(feedbgid) {
                  scriptJquery('#activity-form').addClass('feed_background_image');
                }
              }
              var toValueSESFeedbg = scriptJquery('#toValues').val();
              if(sesFeedBgEnabled && (toValueSESFeedbg.length == 0 && !scriptJquery('#feelingactivityid').val()) && !sespageContentSelected && !sespageContentSelected) {
                scriptJquery('#sesact_post_tags_sesadv').css('display', 'none');
              }
            }
          //Feeling Work End
          <?php } ?>
          
          <?php if(engine_in_array('sesemoji',$enabledModuleNames)) { ?>
          //Feeling Emojis Work
          <?php if(!$this->advcomment) { ?>
            var feeling_requestEmoji;
            scriptJquery('.feeling_emoji_comment_select').click(function() {
            
              scriptJquery('.feeling_emoji_content').removeClass('from_bottom');
              
              var topPositionOfParentDiv =  scriptJquery(this).offset().top + 35;
              topPositionOfParentDiv = topPositionOfParentDiv+'px';
              if(sesadvancedactivityDesign == 2) {
                var leftSub = 55;  
              } else
                var leftSub = 264;
                
              var leftPositionOfParentDiv =  scriptJquery(this).offset().left - leftSub;
              leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
              scriptJquery('._feeling_emoji_content').css('top',topPositionOfParentDiv);
              scriptJquery('._feeling_emoji_content').css('left',leftPositionOfParentDiv).css('z-index',99);
              scriptJquery('._feeling_emoji_content').show();
              var eTop = scriptJquery(this).offset().top; //get the offset top of the element
              var availableSpace = scriptJquery(document).height() - eTop;
              if(availableSpace < 400){
                  scriptJquery('.feeling_emoji_content').addClass('from_bottom');
              }
                if(scriptJquery(this).hasClass('active')){
                  scriptJquery(this).removeClass('active');
                  scriptJquery('.feeling_emoji_content').hide();
                  return false;
                }
                  scriptJquery(this).addClass('active');
                  scriptJquery('.feeling_emoji_content').show();
                  if(scriptJquery(this).hasClass('complete'))
                    return false;

                  var that = this;
                  var url = '<?php echo $this->url(array('module' => 'sesemoji', 'controller' => 'index', 'action' => 'feelingemoji'), 'default', true) ?>';
                  feeling_requestEmoji = scriptJquery.ajax({
                    url : url,
                    data : {
                      format : 'html',
                    },
                    evalScripts : true,
                    success : function(responseHTML) {
                      scriptJquery('.ses_feeling_emoji_holder').html(responseHTML);
                      scriptJquery(that).addClass('complete');
                      scriptJquery('.feeling_emoji_content').show();
                      scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
                        theme:"minimal-dark"
                      });
                    }
                  });
            });
          <?php } ?>
        <?php } ?>
        
        <?php //GIF Work ?>
        <?php if(defined('SESFEEDGIFENABLED') && !$this->advcomment) { ?>
          var requestGif;
          scriptJquery('.gif_comment_select').click(function() {
            clickGifContentContainer = this;
            scriptJquery('.gif_content').removeClass('from_bottom');
            var topPositionOfParentDiv =  scriptJquery(this).offset().top + 35;
            topPositionOfParentDiv = topPositionOfParentDiv+'px';
            if(sesadvancedactivityDesign == 2){
              var leftSub = 55;  
            } else
              var leftSub = 264;
              
            var leftPositionOfParentDiv =  scriptJquery(this).offset().left - leftSub;
            leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
            scriptJquery('._gif_content').css('top',topPositionOfParentDiv);
            scriptJquery('._gif_content').css('left',leftPositionOfParentDiv).css('z-index',99);
            scriptJquery('._gif_content').show();
            var eTop = scriptJquery(this).offset().top; //get the offset top of the element
            var availableSpace = scriptJquery(document).height() - eTop;
            if(availableSpace < 400){
              scriptJquery('.gif_content').addClass('from_bottom');
            }
            
            if(scriptJquery(this).hasClass('active')) {
              scriptJquery(this).removeClass('active');
              scriptJquery('.gif_content').hide();
              return false;
            }
            scriptJquery(this).addClass('active');
            scriptJquery('.gif_content').show();
            
            if(scriptJquery(this).hasClass('complete'))
              return false;
  
             var that = this;
             var url = '<?php echo $this->url(array('module' => 'sesfeedgif', 'controller' => 'index', 'action' => 'gif'), 'default', true) ?>';
             requestGif = scriptJquery.ajax({
              url : url,
              data : {
                format : 'html',
              },
              evalScripts : true,
              success : function(responseHTML) {
                scriptJquery('.gif_content').find('.ses_gif_container_inner').find('.ses_gif_holder').html(responseHTML);
                scriptJquery(that).addClass('complete');
                scriptJquery('._gif_content').show();
                scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
                  theme:"minimal-dark"
                });
                
              }
            });
          });

          var clickGifContentContainer;
          function activityGifFeedAttachment(that){
            var code = scriptJquery(that).parent().parent().attr('rel');
            var image = scriptJquery(that).attr('src');
            Object.entries(composeInstance.plugins).forEach(function([key,plugin]) {
                plugin.deactivate();
              scriptJquery('#compose-'+plugin.getName()+'-activator').parent().removeClass('active');
            });
            scriptJquery('#fancyalbumuploadfileids').val('');
            scriptJquery('.fileupload-cnt').html('');
            composeInstance.getTray().empty();
            scriptJquery('#compose-tray').show();
            scriptJquery('#compose-tray').html('<div class="sesact_composer_gif"><img src="'+image+'"><a class="remove_gif_image_feed notclose fas fa-times" href="javascript:;"></a></div>');
            scriptJquery('#image_id').val(code);
            scriptJquery('.gif_content').hide();  
            scriptJquery('.gif_comment_select').removeClass('active');
            
            //Feed Background Image Work
            if(document.getElementById('feedbgid') && scriptJquery('#image_id').val()) {
              document.getElementById('hideshowfeedbgcont').style.display = 'none';
              scriptJquery('#feedbgid_isphoto').val(0);
              scriptJquery('.sesact_post_box').css('background-image', 'none');
              scriptJquery('#activity-form').removeClass('feed_background_image');
              scriptJquery('#feedbg_content').css('display','none');
            }
          }
          scriptJquery(document).on('click','._sesadvgif_gif > img',function(e) {
            if(scriptJquery(clickGifContentContainer).hasClass('activity_gif_content_a')){
              activityGifFeedAttachment(this);  
            }else
              commentGifContainerSelect(this);
            scriptJquery('.exit_gif_btn').trigger('click');
          });
          
          function commentGifContainerSelect(that){
            var code = scriptJquery(that).parent().parent().attr('rel');
            var elem = scriptJquery(clickGifContentContainer).parent();
            var elemInput = elem.parent().find('span').eq(0).find('.select_gif_id') .val(code);
            elem.closest('form').trigger('submit');  
          }
          /*ACTIVITY FEED*/
          scriptJquery(document).on('click','.remove_gif_image_feed',function(){
            composeInstance.getTray().empty();
            scriptJquery('#image_id').val('');
            scriptJquery('#compose-tray').hide();
            
            //Feed Background Image Work
            if(document.getElementById('feedbgid') && scriptJquery('#image_id').val() == '') {
              var feedbgid = scriptJquery('#feedbgid').val();
              document.getElementById('hideshowfeedbgcont').style.display = 'block';
              scriptJquery('#feedbg_content').css('display','block');
              var feedagainsrcurl = scriptJquery('#feed_bg_image_'+feedbgid).attr('src');
              scriptJquery('.sesact_post_box').css("background-image","url("+ feedagainsrcurl +")");
              scriptJquery('#feedbgid_isphoto').val(1);
              scriptJquery('#feedbg_main_continer').css('display','block');
              if(feedbgid) {
                scriptJquery('#activity-form').addClass('feed_background_image');
              }
            }
          });
          var gifsearchAdvReq;

          var canPaginatePageNumber = 1;
          scriptJquery(document).on('keyup change','.search_sesgif',function(){
            var value = scriptJquery(this).val();
            if(!value){
              scriptJquery('.main_search_category_srn').show();
              scriptJquery('.main_search_cnt_srn').hide();
              return;
            }
            scriptJquery('.main_search_category_srn').hide();
            scriptJquery('.main_search_cnt_srn').show();
            if(typeof gifsearchAdvReq != 'undefined') {
              
              isGifRequestSend = false;
            }
            document.getElementById('main_search_cnt_srn').innerHTML = '<div class="sesgifsearch sesbasic_loading_container" style="height:100%;"></div>';
            canPaginatePageNumber = 1;
            searchGifContent();
          });

          var isGifRequestSend = false;
          function searchGifContent(valuepaginate, searchscroll) {
            
            var value = '';
            var search_sesgif = scriptJquery('.search_sesgif').val();
            
            if(isGifRequestSend == true)
              return;
              
              //console.log(searchscroll);
            
            if(typeof valuepaginate != 'undefined') {
              value = 1;
              document.getElementById('main_search_cnt_srn').innerHTML = document.getElementById('main_search_cnt_srn').innerHTML;
            }
            
            isGifRequestSend = true;
            gifsearchAdvReq = (scriptJquery.ajax({
              method: 'post',
              'url': en4.core.baseUrl + "sesfeedgif/index/search-gif/",
              'data': {
                format: 'html',
                  text: search_sesgif,
                  page: canPaginatePageNumber,
                  is_ajax: 1,
                  searchvalue: value,
              },
              success : function(responseHTML) {
                
                scriptJquery('.sesgifsearch').remove();
                scriptJquery('.sesgifsearchpaginate').remove();

                if(scriptJquery('.sesfeedgif_search_results').length == 0) {
                  scriptJquery('#main_search_cnt_srn').append(responseHTML);
                } else {
                  scriptJquery('.sesfeedgif_search_results').append(responseHTML);
                }
                scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
                  theme:"minimal-dark",
                  callbacks:{
                      whileScrolling:function() {
                        if(this.mcs.topPct == 90 && canPaginateExistingPhotos == '1' &&   scriptJquery('.sesgifsearchpaginate').length == 0) {
                        scriptJquery('.sesbasic_loading_container').css('position','absolute').css('width','100%').css('bottom','5px');
                          searchGifContent(1, 'searchscroll');
                        }
                      },
                  }
                });
            
//                 scriptJquery('.main_search_cnt_srn').slimscroll({
//                   height: 'auto',
//                   alwaysVisible :true,
//                   color :'#000',
//                   railOpacity :'0.5',
//                   disableFadeOut :true,
//                 });
// 
//                 scriptJquery('.main_search_cnt_srn').slimscroll().bind('slimscroll', function(event, pos) {
//                   if(canPaginateExistingPhotos == '1' && pos == 'bottom' && scriptJquery('.sesgifsearchpaginate').length == 0) {
//                     scriptJquery('.sesbasic_loading_container').css('position','absolute').css('width','100%').css('bottom','5px');
//                     searchGifContent(1);
//                   }
//                 });
                isGifRequestSend = false;
              }
            }))
          }
        <?php } ?>
        <?php //GIF Work ?>

      <?php if(!$this->advcomment){ ?>
      var requestEmoji;
      scriptJquery('.emoji_comment_select').click(function(){
        scriptJquery('.emoji_content').removeClass('from_bottom');
        var topPositionOfParentDiv =  scriptJquery(this).offset().top + 35;
        topPositionOfParentDiv = topPositionOfParentDiv+'px';
        if(sesadvancedactivityDesign == 2){
          var leftSub = 55;  
        }else
          var leftSub = 264;
        var leftPositionOfParentDiv =  scriptJquery(this).offset().left - leftSub;
        leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
        scriptJquery('._emoji_content').css('top',topPositionOfParentDiv);
        scriptJquery('._emoji_content').css('left',leftPositionOfParentDiv).css('z-index',99);
        scriptJquery('._emoji_content').show();
        var eTop = scriptJquery(this).offset().top; //get the offset top of the element
        var availableSpace = scriptJquery(document).height() - eTop;
        if(availableSpace < 400){
            scriptJquery('.emoji_content').addClass('from_bottom');
        }
          if(scriptJquery(this).hasClass('active')){
            scriptJquery(this).removeClass('active');
            scriptJquery('.emoji_content').hide();
            return false;
           }
            scriptJquery(this).addClass('active');
            scriptJquery('.emoji_content').show();
            if(scriptJquery(this).hasClass('complete'))
              return false;
             var that = this;
             var url = '<?php echo $this->url(array('module' => 'sesadvancedactivity', 'controller' => 'ajax', 'action' => 'emoji'), 'default', true) ?>';
             requestEmoji = scriptJquery.ajax({
              url : url,
              data : {
                format : 'html',
              },
              evalScripts : true,
              success : function(responseHTML) {
                scriptJquery('.ses_emoji_holder').html(responseHTML);
                scriptJquery(that).addClass('complete');
                scriptJquery('.emoji_content').show();
                scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
									theme:"minimal-dark"
								});
              }
            });
      });
      //emoji select in comment
      scriptJquery(document).click(function(e){
        if(scriptJquery(e.target).attr('id') == 'sesadvancedactivityemoji-edit-a')
          return;
        var container = scriptJquery('.ses_emoji_container');
        if ((!container.is(e.target) && container.has(e.target).length === 0)){
           scriptJquery('.ses_emoji_container').parent().find('a').removeClass('active');
           scriptJquery('.ses_emoji_container').hide();
        }
      });
      <?php } ?>
    </script>
<script type="text/javascript">
scriptJquery('#discard_post').click(function(){
  hideStatusBoxSecond();
  scriptJquery('.sesact_confirmation_popup_overlay').hide();
  scriptJquery('.sesact_confirmation_popup').hide();
  scriptJquery('.sesact_post_media_options').removeClass('_sesadv_composer_active');
});
scriptJquery('#goto_post').click(function(){
scriptJquery('.sesact_confirmation_popup').hide();  
scriptJquery('.sesact_confirmation_popup_overlay').hide();
});
<?php if($this->allowprivacysetting){ ?>
  //set default privacy of logged-in user
  scriptJquery(document).ready(function(e){
    scriptJquery('.adv_privacy_optn > li[class!="_sep"]:first').find('a').trigger('click')
    privacySetAct = true;
  });
<?php  }else{ ?>
  var privacySetAct = true;
<?php } ?>
scriptJquery(document).on('click','.adv_privacy_optn li a',function(e){
e.preventDefault();
if(!scriptJquery(this).parent().hasClass('multiple')){
scriptJquery('.adv_privacy_optn > li').removeClass('active');
var text = scriptJquery(this).text();
<?php if(!$this->subject() || $this->subject() && ($this->subject()->getType() != "businesses" || $this->subject()->getType() != "sespage_page" || $this->subject()->getType() != "sesgroup_group" || $this->subject()->getType() != "stores")){ ?>
scriptJquery('.sesact_privacy_btn').attr('title',text);;
<?php } ?>
scriptJquery(this).parent().addClass('active');
scriptJquery('#adv_pri_option').html(text);
scriptJquery('#sesadv_privacy_icon').remove();
scriptJquery('<i id="sesadv_privacy_icon" class="'+scriptJquery(this).find('i').attr('class')+'"></i>').insertBefore('#adv_pri_option');

if(scriptJquery(this).parent().hasClass('sesadv_network'))
  scriptJquery('#privacy').val(scriptJquery(this).parent().attr('data-src')+'_'+scriptJquery(this).parent().attr('data-rel'));
else if(scriptJquery(this).parent().hasClass('sesadv_list'))
  scriptJquery('#privacy').val(scriptJquery(this).parent().attr('data-src')+'_'+scriptJquery(this).parent().attr('data-rel'));
else
scriptJquery('#privacy').val(scriptJquery(this).parent().attr('data-src'));
}
scriptJquery('.sesact_privacy_btn').parent().removeClass('sesact_pulldown_active');
});

scriptJquery(document).on('click','.mutiselect',function(e){
if(scriptJquery(this).attr('data-rel') == 'network-multi')
var elem = 'sesadv_network';
else
var elem = 'sesadv_list';
var elemens = scriptJquery('.'+elem);
var html = '';
for(i=0;i<elemens.length;i++){
html += '<li><input class="checkbox" type="checkbox" value="'+scriptJquery(elemens[i]).attr('data-rel')+'">'+scriptJquery(elemens[i]).text()+'</li>';
}
en4.core.showError('<form id="'+elem+'_select" class="_privacyselectpopup"><p>Please select network to display post</p><ul class="sesbasic_clearfix">'+html+'</ul><div class="_privacyselectpopup_btns sesbasic_clearfix"><button type="submit">Save</button><button class="close" onclick="Smoothbox.close();return false;">Close</button></div></form>');
scriptJquery ('._privacyselectpopup').parent().parent().addClass('_privacyselectpopup_wrapper');
//pre populate
var valueElem = scriptJquery('#privacy').val();
if(valueElem && valueElem.indexOf('network_list_') > -1 && elem == 'sesadv_network'){
var exploidV =  valueElem.split(',');
for(i=0;i<exploidV.length;i++){
   var id = exploidV[i].replace('network_list_','');
   scriptJquery('.checkbox[value="'+id+'"]').prop('checked', true);
}
}else if(valueElem && valueElem.indexOf('member_list_') > -1 && elem == 'sesadv_list'){
var exploidV =  valueElem.split(',');
for(i=0;i<exploidV.length;i++){
   var id = exploidV[i].replace('member_list_','');
   scriptJquery('.checkbox[value="'+id+'"]').prop('checked', true);
}
}
});
scriptJquery(document).on('submit','#sesadv_list_select',function(e){
e.preventDefault();
var isChecked = false;
var sesadv_list_select = scriptJquery('#sesadv_list_select').find('[type="checkbox"]');
var valueL = '';
for(i=0;i<sesadv_list_select.length;i++){
if(!isChecked)
  scriptJquery('.adv_privacy_optn > li').removeClass('active');
if(scriptJquery(sesadv_list_select[i]).is(':checked')){
  isChecked = true;
  var el = scriptJquery(sesadv_list_select[i]).val();
  scriptJquery('.lists[data-rel="'+el+'"]').addClass('active');
  valueL = valueL+'member_list_'+el+',';
}
}
if(isChecked){
 scriptJquery('#privacy').val(valueL);
 scriptJquery('#adv_pri_option').html("<?php echo $this->translate('Multiple Lists'); ?>");
 scriptJquery('.sesact_privacy_btn').attr('title',"<?php echo $this->translate('Multiple Lists'); ?>");;
scriptJquery(this).find('.close').trigger('click');
}
scriptJquery('#sesadv_privacy_icon').removeAttr('class').addClass('sesact_list');
});
scriptJquery(document).on('submit','#sesadv_network_select',function(e){
e.preventDefault();
var isChecked = false;
var sesadv_network_select = scriptJquery('#sesadv_network_select').find('[type="checkbox"]');
var valueL = '';
for(i=0;i<sesadv_network_select.length;i++){
  if(!isChecked)
    scriptJquery('.adv_privacy_optn > li').removeClass('active');
  if(scriptJquery(sesadv_network_select[i]).is(':checked')){
    isChecked = true;
    var el = scriptJquery(sesadv_network_select[i]).val();
    scriptJquery('.network[data-rel="'+el+'"]').addClass('active');
    valueL = valueL+'network_list_'+el+',';
  }
}
if(isChecked){
 scriptJquery('#privacy').val(valueL);
 scriptJquery('#adv_pri_option').html('Multiple Network');
 scriptJquery('.sesact_privacy_btn').attr('title','Multiple Network');;
scriptJquery(this).find('.close').trigger('click');
}
scriptJquery('#sesadv_privacy_icon').removeAttr('class').addClass('sesact_network');
});
<?php if($settings->getSetting('enableglocation', 1)) { ?>
var input = document.getElementById('tag_location');
if(input){
  if(typeof google != "undefined"){
var autocomplete = new google.maps.places.Autocomplete(input);
  google.maps.event.addListener(autocomplete, 'place_changed', function () {
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }
    scriptJquery('#locValues-element').html('<span class="tag">'+scriptJquery('#tag_location').val()+' <a href="javascript:void(0);" class="loc_remove_act notclose">x</a></span>');
    scriptJquery('#dash_elem_act').show();
    scriptJquery('#location_elem_act').show();
    scriptJquery('#location_elem_act').html('at <a href="javascript:;" class="seloc_clk">'+scriptJquery('#tag_location').val()+'</a>');
    scriptJquery('#tag_location').hide();
    document.getElementById('activitylng').value = place.geometry.location.lng();
    document.getElementById('activitylat').value = place.geometry.location.lat();
  
    //Feed Background Image Work
    if(document.getElementById('feedbgid')) {
      scriptJquery('#sesact_post_tags_sesadv').css('display', 'block');
      scriptJquery('#feedbgid_isphoto').val(0);
      scriptJquery('.sesact_post_box').css('background-image', 'none');
      scriptJquery('#activity-form').removeClass('feed_background_image');
      scriptJquery('#feedbg_main_continer').css('display','none');
    }
});
}
}
<?php } ?>
scriptJquery(document).on('click','.loc_remove_act',function(e){
scriptJquery('#activitylng').val('');
scriptJquery('#activitylat').val('');
scriptJquery('#tag_location').val('');
scriptJquery('#locValues-element').html('');
scriptJquery('#tag_location').show();
scriptJquery('#location_elem_act').hide();
if(!scriptJquery('#toValues-element').children().length && !sespageContentSelected)
   scriptJquery('#dash_elem_act').hide();
   
var feedbgid = scriptJquery('#feedbgid').val();
var feedagainsrcurl = scriptJquery('#feed_bg_image_'+feedbgid).attr('src');
scriptJquery('.sesact_post_box').css("background-image","url("+ feedagainsrcurl +")");
scriptJquery('#feedbgid_isphoto').val(1);
scriptJquery('#feedbg_main_continer').css('display','block');
if(feedbgid) {
  scriptJquery('#activity-form').addClass('feed_background_image');
}
if(feedbgid == 0) {
  scriptJquery('#activity-form').removeClass('feed_background_image');
}
})    

// Populate data
var maxRecipients = 50;
var to = {
id : false,
type : false,
guid : false,
title : false
};

function removeFromToValue(id) {    
  //check for edit form
  if(scriptJquery('#sessmoothbox_main').length){
    removeFromToValueEdit(id);
    return;
  }
    
  // code to change the values in the hidden field to have updated values
  // when recipients are removed.
  var toValues = document.getElementById('toValues').value;
  var toValueArray = toValues.split(",");
  var toValueIndex = "";

  var checkMulti = id.search(/,/);

  // check if we are removing multiple recipients
  if (checkMulti!=-1){
    var recipientsArray = id.split(",");
    for (var i = 0; i < recipientsArray.length; i++){
      removeToValue(recipientsArray[i], toValueArray);
    }
  }
  else{
    removeToValue(id, toValueArray);
  }
  scriptJquery('#tag_friends_input').prop("disabled",false);
  var firstElem = scriptJquery('#toValues-element > span').eq(0).text();
  var countElem = scriptJquery('#toValues-element').children().length;
  var html = '';

  if(!firstElem.trim()){
    scriptJquery('#tag_friend_cnt').html('');
    scriptJquery('#tag_friend_cnt').hide();
    if(!scriptJquery('#tag_location').val() && !sespageContentSelected)
    scriptJquery('#dash_elem_act').hide();
    return;
  }else if(countElem == 1){
    html = '<a href="javascript:;" class="sestag_clk">'+firstElem.replace('x','')+'</a>';
  }else if(countElem > 2){
    html = '<a href="javascript:;" class="sestag_clk">'+firstElem.replace('x','')+'</a>';
    html = html + ' and <a href="javascript:;" class="sestag_clk">'+(countElem-1)+' others</a>';
  }else{
    html = '<a href="javascript:;" class="sestag_clk">'+firstElem.replace('x','')+'</a>';
    html = html + ' and <a href="javascript:;" class="sestag_clk">'+scriptJquery('#toValues-element > span').eq(1).text().replace('x','')+'</a>';
}
  scriptJquery('#sesact_post_tags_sesadv').css('display', 'block');
  scriptJquery('#tag_friend_cnt').html('with '+html);
  scriptJquery('#tag_friend_cnt').show();
  scriptJquery('#dash_elem_act').show();
}

function removeToValue(id, toValueArray){
for (var i = 0; i < toValueArray.length; i++){
  if (toValueArray[i]==id) toValueIndex =i;
}

toValueArray.splice(toValueIndex, 1);
scriptJquery('#toValues').val(toValueArray.join());

if(sesFeedBgEnabled && toValueArray.length == 0 && !scriptJquery('#feelingactivityid').val() && !sespageContentSelected)
  scriptJquery('#sesact_post_tags_sesadv').css('display', 'none');
}

<?php if($viewer->getIdentity()) { ?>
en4.core.runonce.add(function() {
   AutocompleterRequestJSON('tag_friends_input', "<?php echo $this->url(array('module' => 'sesadvancedactivity', 'controller' => 'index', 'action' => 'suggest'), 'default', true) ?>", function(selecteditem) {
     scriptJquery("#tag_friends_input").val("");
     if( scriptJquery('#toValues').val().split(',').length >= maxRecipients ){
        scriptJquery('#tag_friends_input').prop("disabled",true);
      }
      let totalVal = scriptJquery('#toValues').val() ? scriptJquery('#toValues').val().split(',') : [];
      if(totalVal.length > 0 && totalVal.indexOf(selecteditem.id.toString()) > -1){
        return;
      }
      scriptJquery("#toValues").val((totalVal.length > 0 ? scriptJquery('#toValues').val()+"," : "")+selecteditem.id);
      scriptJquery('#toValues-element').append('<span class="tag" id="tospan_'+selecteditem.label+'_'+selecteditem.id+'">'+selecteditem.label+'<a href="javascript:;" onclick="scriptJquery(this).parent().remove();removeFromToValue('+selecteditem.id+')">x</a></span>')
      var firstElem = scriptJquery('#toValues-element > span').eq(0).text();
      var countElem = scriptJquery('#toValues-element  > span').children().length;
      var html = '';
      if(countElem == 1){
        html = '<a href="javascript:;" class="sestag_clk">'+firstElem.replace('x','')+'</a>';
      }else if(countElem > 2){
        html = '<a href="javascript:;" class="sestag_clk">'+firstElem.replace('x','')+'</a>';
        html = html + ' and <a href="javascript:;"  class="sestag_clk">'+(countElem-1)+' others</a>';
      }else{
        html = '<a href="javascript:;" class="sestag_clk">'+firstElem.replace('x','')+'</a>';
        html = html + ' and <a href="javascript:;" class="sestag_clk">'+scriptJquery('#toValues-element > span').eq(1).text().replace('x','')+'</a>';
      }
      scriptJquery('#sesact_post_tags_sesadv').css('display', 'block');
      scriptJquery('#tag_friend_cnt').html('with '+html);
      scriptJquery('#tag_friend_cnt').show();
      scriptJquery('#dash_elem_act').show();
  });
});
<?php } ?>
</script>
<script type="application/javascript">
var isMemberHomePage = <?php echo !empty($this->isMemberHomePage) ? $this->isMemberHomePage : 0; ?>;
var isOnThisDayPage = <?php echo !empty($this->isOnThisDayPage) ? $this->isOnThisDayPage : 0; ?>;
         function  preventSubmitOnSocialNetworking(){
           if(scriptJquery('.composer_facebook_toggle_active').length)
            scriptJquery('.composer_facebook_toggle').click();
           if(scriptJquery('.composer_twitter_toggle_active').length)
            scriptJquery('.composer_twitter_toggle_active').click();  
          }
          scriptJquery(document).on('click','.schedule_post_schedue',function(e){
           e.preventDefault();
           var value = scriptJquery('#scheduled_post').val();
           if(scriptJquery('.sesadvancedactivity_shedulepost_error').css('display') == 'block' || !value){
            return;   
           }
           scriptJquery('.sesadvancedactivity_shedulepost_overlay').hide();
           scriptJquery('.sesadvancedactivity_shedulepost_select').hide();
           scriptJquery('.sesadvancedactivity_shedulepost').addClass('active');
           preventSubmitOnSocialNetworking();
          });
          scriptJquery(document).on('click','#sesadvancedactivity_shedulepost',function(e){
           e.preventDefault();
           scriptJquery('.sesadvancedactivity_shedulepost_overlay').show();
           scriptJquery('.sesadvancedactivity_shedulepost_select').show();
           scriptJquery(this).addClass('active');
           makeDateTimePicker();
           sesadvtooltip();
          });
          scriptJquery(document).on('click','.schedule_post_close',function(e){
              e.preventDefault();
            scriptJquery('.sesadvancedactivity_shedulepost_overlay').hide();
            scriptJquery('.sesadvancedactivity_shedulepost_select').hide();
            if(scriptJquery('.sesadvancedactivity_shedulepost_error').css('display') == 'block')
              scriptJquery('.sesadvancedactivity_shedulepost_error').html('').hide();
            scriptJquery('#scheduled_post').val('');
             scriptJquery('#sesadvancedactivity_shedulepost').removeClass('active');
             scriptJquery('.bootstrap-datetimepicker-widget').hide();
          });
          var schedule_post_datepicker;
          function makeDateTimePicker(){
            if(scriptJquery('.sesadvancedactivity_shedulepost_edit_overlay').length){
              var elem = 'scheduled_post_edit';
              var datepicker = 'datetimepicker_edit';
            }else{
              var elem = 'scheduled_post';
              var datepicker  = 'datetimepicker';
            }
            //if(!scriptJquery('#'+elem).val()){
              var now = new Date();
              now.setMinutes(now.getMinutes() + 10);
           // }
            schedule_post_datepicker = scriptJquery('#'+datepicker).datetimepicker({
            format: 'dd/MM/yyyy hh:mm:ss',
            maskInput: false,           // disables the text input mask
            pickDate: true,            // disables the date picker
            pickTime: true,            // disables de time picker
            pick12HourFormat: true,   // enables the 12-hour format time picker
            pickSeconds: true,         // disables seconds in the time picker
            startDate: now,      // set a minimum date
            endDate: Infinity          // set a maximum date
          });
          schedule_post_datepicker.on('changeDate', function(e) {
            var time = e.localDate.toString();
            var timeObj = new Date(time).getTime();
            //add 10 minutes
            var now = new Date();
            now.setMinutes(now.getMinutes() + 10);
            if(scriptJquery('.sesadvancedactivity_shedulepost_edit_overlay').length){
              var error = 'sesadvancedactivity_shedulepost_edit_error';
            }else{
              var error = 'sesadvancedactivity_shedulepost_error';
            }
            if(timeObj < now.getTime()){
              scriptJquery('.'+error).html("<?php echo $this->translate('choose time 10 minutes greater than current time.'); ?>").show();
              return false;
            }else{
             scriptJquery('.'+error).html('').hide();
            }
          });  
          }
          </script>      
     
<?php if(empty($this->subjectGuid) && !$this->isOnThisDayPage){ ?>

<?php if($this->isMemberHomePage){
echo $this->partial(
'_homesuggestions.tpl',
'sesadvancedactivity',
array()
);
}
?>
<?php echo $this->partial(
'_homefeedtabs.tpl',
'sesadvancedactivity',
array('identity'=>$this->identity,'lists'=>$this->lists)
);
?>
<?php }else if(!$this->isOnThisDayPage && $this->subject() && ($this->subject()->getType() == 'group' || $this->subject()->getType() == 'user' || $this->subject()->getType() == 'businesses' || $this->subject()->getType() == 'sespage_page' ||  $this->subject()->getType() == 'sesgroup_group' ||  $this->subject()->getType() == 'stores' ||  $this->subject()->getType() == 'sesevent_event' ||  $this->subject()->getType() == 'classroom')){
echo $this->partial(
'_subjectfeedtabs.tpl',
'sesadvancedactivity',
array('identity'=>$this->identity,'lists'=>$this->lists)
);
}else{ ?>
<div class="sesact_feed_filters sesbasic_clearfix sesbasic_bxs sesbm displayN" style="display: none">
  <ul class="sesadvancedactivity_filter_tabs sesbasic_clearfix">
    <li class="sesadvancedactivity_filter_tabsli sesadv_active_tabs"><a href="javascript:;" class="sesadv_tooltip" data-src="all">
        <span></span></a></li>
  </ul>
</div>
<script type="application/javascript">
  var filterResultrequest;
  scriptJquery(document).on('click','ul.sesadvancedactivity_filter_tabs li a',function(e){
//    if(scriptJquery(this).parent().hasClass('active') || scriptJquery(this).hasClass('viewmore'))
//     return false;
    if(scriptJquery(this).hasClass('viewmore'))
      return false;

    scriptJquery('.sesadvancedactivity_filter_img').show();
    scriptJquery('.sesadvancedactivity_filter_tabsli').removeClass('active sesadv_active_tabs');
    scriptJquery(this).parent().addClass('active sesadv_active_tabs');
    var filterFeed = scriptJquery(this).attr('data-src');

    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';
    var hashTag = scriptJquery('#hashtagtextsesadv').val();
    var adsIds = scriptJquery('.sescmads_ads_listing_item');
    var adsIdString = "";
    if(adsIds.length > 0){
      scriptJquery('.sescmads_ads_listing_item').each(function(index){
        if(typeof dataFeedItem == "undefined")
          adsIdString = scriptJquery(this).attr('rel')+ "," + adsIdString ;
      });
    }
    filterResultrequest = scriptJquery.ajax({
      type: "POST",
      url : url+"?hashtag="+hashTag+'&isOnThisDayPage='+isOnThisDayPage+'&isMemberHomePage='+isMemberHomePage,
      data : {
        format : 'html',
        'filterFeed' : filterFeed,
        'feedOnly' : true,
        'action_id':sesAdvancedActivityGetAction_id,
        'getUpdates':1,
        'nolayout' : true,
        'ads_ids': adsIdString,
        'subject' : en4.core.subject.guid,
      },
      evalScripts : true,
      success : function(responseHTML) {

        if(!sesAdvancedActivityGetFeeds){
          scriptJquery('#activity-feed').append(responseHTML);
        }else{
          scriptJquery('#activity-feed').html(responseHTML);
        }
        
        if(scriptJquery('#activity-feed').find('li').length > 0)
          scriptJquery('.sesadv_noresult_tip').hide();
        else
          scriptJquery('.sesadv_noresult_tip').show();
        //initialize feed autoload counter
        counterLoadTime = 0;
        sesadvtooltip();
        initSesadvAnimation();
        Smoothbox.bind(document.getElementById('activity-feed'));
        scriptJquery('.sesadvancedactivity_filter_img').hide();
        activateFunctionalityOnFirstLoad();
      }
    });
  });
</script>
<style>
  .displayN{
    display: none !important;
  }
</style>
<?php
}
 ?>

<?php if ($this->updateSettings && !$this->action_id && !$this->isOnThisDayPage): // wrap this code around a php if statement to check if there is live feed update turned on ?>
  <script type="text/javascript">
    var SesadvancedactivityUpdateHandler;
    en4.core.runonce.add(function() {
      try {
          SesadvancedactivityUpdateHandler = new SesadvancedactivityUpdateHandler({
            'baseUrl' : en4.core.baseUrl,
            'basePath' : en4.core.basePath,
            'identity' : 4,
            'delay' : <?php echo $this->updateSettings;?>,
            'last_id': <?php echo sprintf('%d', $this->firstid) ?>,
            'subject_guid' : '<?php echo $this->subjectGuid ?>'
          });
          setTimeout("SesadvancedactivityUpdateHandler.start()",1250);
          //activityUpdateHandler.start();
          window._SesadvancedactivityUpdateHandler = SesadvancedactivityUpdateHandler;
      } catch( e ) {
        //if( $type(console) )
      }
      // if(scriptJquery('#activity-feed').children().length && <?php echo (int)$this->getUpdates; ?> == 1)
      //  scriptJquery('.sesadv_noresult_tip').hide();
      // else
      //  scriptJquery('.sesadv_noresult_tip').show();
    });
  </script>
<?php endif;?>

<?php if( $this->post_failed == 1 ): ?>
  <div class="tip">
    <span>
      <?php $url = $this->url(array('module' => 'user', 'controller' => 'settings', 'action' => 'privacy'), 'default', true) ?>
      <?php echo $this->translate('The post was not added to the feed. Please check your %1$sprivacy settings%2$s.', '<a href="'.$url.'">', '</a>') ?>
    </span>
  </div>
<?php endif; ?>

<?php // If requesting a single action and it does not exist, show error ?>
<?php if( !$this->activity ): ?>
  <?php if( $this->action_id ): ?>
    <span style="display: none" class="no_content_activity_id">
      <h2><?php echo $this->translate("Activity Item Not Found") ?></h2>
      <p>
        <?php echo $this->translate("The page you have attempted to access could not be found.") ?>
      </p>
    </span>
  <?php endif; ?>
<?php endif; ?>
<?php if(!$this->action_id): ?>
  <div class="sesadv_content_load_img sesbasic_loading_container">
  </div>
<?php endif; ?>
<div class="sesadv_tip sesact_tip_box sesadv_noresult_tip" style="display:<?php echo !sprintf('%d', $this->activityCount) && $this->getUpdates ? 'block' : 'none'; ?>;">
<?php if(!$this->isOnThisDayPage){ ?>
  <span>
    <?php echo $this->translate("Nothing has been posted here yet - be the first!") ?>
  </span>
 <?php }else{ ?>
 <span>
    <?php echo $this->translate('No memories for you on this day.') ?>
  </span>
 <?php } ?>
</div>
<div id="feed-update"></div>
<?php echo $this->activityLoop($this->activity, array(
  'action_id' => $this->action_id,
  'communityadsIds' => $this->communityadsIds,
  'viewAllComments' => $this->viewAllComments,
  'viewAllLikes' => $this->viewAllLikes,
  'getUpdate' => $this->getUpdate,
  'getUpdates' => $this->getUpdates,
  'isOnThisDayPage'=>$this->isOnThisDayPage,
  'isMemberHomePage' => $this->isMemberHomePage,
  'userphotoalign' => $this->userphotoalign,
  'filterFeed'=>$this->filterFeed,
  'feeddesign'=>$this->feeddesign,
  'enabledModuleNames' => $enabledModuleNames
)) ?>
<?php if(!$this->isOnThisDayPage): ?>
<div class="sesact_view_more sesadv_tip sesact_tip_box" id="feed_viewmore_activityact" style="display: none;">
	<a href="javascript:void(0);" id="feed_viewmore_activityact_link" class="sesbasic_animation sesbasic_linkinherit"><i class="fa fa-sync"></i><span><?php echo $this->translate('View More');?></span></a>
</div>
<div class="sesadv_tip sesact_tip_box" id="feed_loading" style="display: none;">
  <span><i class="fas fa-circle-notch fa-spin"></i></span>
</div>
<?php if( !$this->feedOnly && $this->isMemberHomePage && !$this->isOnThisDayPage): ?>
</div>
<?php endif; ?>
<div class="sesadv_tip sesact_tip_box" id="feed_no_more_feed" style="display:none;">
	<span>No more post</span>
</div>
<script type="application/javascript">

  scriptJquery(document).ready(function() {
    var welcomeactive = scriptJquery('#sesadv_tabs_cnt li.active');
    if(scriptJquery(welcomeactive).attr('data-url') == 1) {
      scriptJquery(welcomeactive).find('a').trigger('click');
    }
  });

  scriptJquery(document).on('click','#sesadv_tabs_cnt li a',function(e) {
    var id = scriptJquery(this).parent().attr('data-url');
    var instid = scriptJquery(this).parent().parent().attr('data-url');

    if(instid == 4) return;

    scriptJquery('.sesadv_tabs_content').hide();


    scriptJquery('#sesadv_tabs_cnt > li').removeClass('active');
    scriptJquery(this).parent().addClass('active');
    scriptJquery('#sesadv_tab_'+id).show();

    if(id == 1 || id == 3) {
      scriptJquery('#feed_no_more_feed').addClass('dNone');
    }else
      scriptJquery('#feed_no_more_feed').removeClass('dNone');
    if(id == 3) return;
    if(scriptJquery('#sesadv_tab_'+id).find('.sesadv_loading_img').length){
      var url = en4.core.baseUrl+scriptJquery('#sesadv_tab_'+id).find('.sesadv_loading_img').attr('data-href');
      //get content

      requestsent = (scriptJquery.ajax({
      method: 'post',
      'url': url,
      'data': {
        format: 'html'
      },
      success : function(responseHTML) {
       scriptJquery('#sesadv_tab_'+id).html(responseHTML);
      }
    }));
    }
  });

</script>
<?php endif; ?>

<script type="application/javascript">
if(typeof initSesadvAnimation != "undefined")
initSesadvAnimation();
</script>

<?php if($this->isOnThisDayPage){ ?>
<div class="sesact_feed_thanks_block centerT">
	<img src="application/modules/Sesadvancedactivity/externals/images/thanks.png"alt="" />
  <span><?php echo $this->translate("Thanks for coming!"); ?></span>
</div>
<?php } ?>

<?php if($this->feeddesign == 2){  ?>
	<script type="application/javascript">
		var wookmark = undefined;
		var isactivityloadedfirst= true;
	 //Code for Pinboard View
		var wookmark<?php echo $randonNumber ?>;
		function pinboardLayoutFeed_<?php echo $randonNumber ?>(force){
			if(isactivityloadedfirst == true){
				scriptJquery('#activity-feed').append('<li id="sesact_feed_loading" style="margin-bottom:20px;"><div class="sesbasic_loading_container" style="height:100px;"></div></li>')
			}
			//scriptJquery('.new_image_pinboard').css('display','none');
			var imgLoad = imagesLoaded('._sesactpinimg');
			var imgleangth = imgLoad.images.length;
			if(imgleangth > 0){
				var counter = 1; 
				imgLoad.on('progress',function(instance,image){
					scriptJquery(image.img).removeClass('_sesactpinimg');
					scriptJquery(image.img).closest('.sesact_pinfeed_hidden').removeClass('sesact_pinfeed_hidden');
					imageLoadedAll<?php echo $randonNumber ?>();
					if(counter == 1){
						//scriptJquery('.sesact_pinfeed_hidden').removeClass('sesact_pinfeed_hidden');
						//scriptJquery('._sesactpinimg').removeClass('._sesactpinimg');
					}
					if(counter == imgleangth){
						scriptJquery('#sesact_feed_loading').remove();
					}
					counter = counter +1;
				});
			}else{
				scriptJquery('.sesact_pinfeed_hidden').removeClass('sesact_pinfeed_hidden');
				scriptJquery('._sesactpinimg').removeClass('._sesactpinimg');
				imageLoadedAll<?php echo $randonNumber ?>();
				scriptJquery('#sesact_feed_loading').remove();
			}
		}
		function imageLoadedAll<?php echo $randonNumber ?>(force){
		 scriptJquery('#activity-feed').addClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
		 if (typeof wookmark<?php echo $randonNumber ?> == 'undefined') {
				(function() {
					function getWindowWidth() {
						return Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
					}				
					wookmark<?php echo $randonNumber ?> = new Wookmark('.sesbasic_pinboard_<?php echo $randonNumber; ?>', {
						itemWidth: <?php echo isset($this->sesact_pinboard_width) ? str_replace(array('px','%'),array(''),$this->sesact_pinboard_width) : '300'; ?>, // Optional min width of a grid item
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
  function feedUpdateFunction(){
    setTimeout(function(){pinboardLayoutFeed_<?php echo $randonNumber ?>();},200);
  }
	scriptJquery(document).ready(function(){
		pinboardLayoutFeed_<?php echo $randonNumber ?>();
	});
	scriptJquery(document).click(function(){
		pinboardLayoutFeed_<?php echo $randonNumber ?>();
	});
	scriptJquery(document).bind("paste", function(e){
		pinboardLayoutFeed_<?php echo $randonNumber ?>();
	});
	scriptJquery(document).on('click','.tab_layout_activity_feed',function (event) {
		pinboardLayoutFeed_<?php echo $randonNumber ?>();
	});
	scriptJquery('#activity-feed').one("DOMSubtreeModified",function(){
		// do something after the div content has changed
	 imageLoadedAll<?php echo $randonNumber ?>();
	});
	</script>
<?php } ?>
<script type="application/javascript">

scriptJquery(document).ready(function(e){
  if(typeof complitionRequestTrigger == 'function'){
    complitionRequestTrigger();  
  }  
})

scriptJquery('.selectedTabClick').click(function(e){
  var rel = scriptJquery(this).data('rel');
  if(rel != 'all'){
    document.getElementById('compose-'+rel+'-activator').click();  
    if(rel == "photo"){
      document.getElementById('dragandrophandler').click();  
    }
  }  
})
</script>
<?php 
  unset($enabledModuleNames);
  unset($settings);
 ?>
