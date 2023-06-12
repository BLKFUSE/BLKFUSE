<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Ewebstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2020-03-20 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<?php if(!$this->isAjax){ ?>
<?php if($this->viewer()->getIdentity()){ ?>
	<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesstories/externals/scripts/core.js'); ?>
	<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesstories/externals/styles/styles.css'); ?>
	<?php   
	$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
	$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js')
	?>
	<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>
	
	<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
	<a href="sesstories/index/create" class="sessmoothbox" id="create-sesstories" style="display:none;"><?php echo $this->translate('Create Story'); ?></a>
<?php } ?>
<script type="application/javascript">
  //function to fetch stories
  setInterval(getSesStories,8000);
  function getSesStories() {
    scriptJquery.post(en4.core.baseUrl + 'widget/index/mod/ewebstories/name/all-stories',{is_ajax:true},function (response) {

      scriptJquery('.sesstories_allstories_<?php echo $this->identity;?>').trigger('destroy.owl.carousel');
      scriptJquery(".sesStories_content").html('<div class="sesstories_bxs sesstories_allstories sesstories_allstories_<?php echo $this->identity;?>"></div>');
      scriptJquery(".sesstories_allstories").html(response);
      sesowlJqueryObject('.sesstories_allstories').owlCarousel({
        loop:false,
        dots:false,
        nav:true,
        margin:10,
      <?php if($orientation = ($this->layout()->orientation == 'right-to-left')){ ?>
        rtl:true,
      <?php } ?>
      autoWidth:true,
      })
      sesowlJqueryObject(".owl-prev").html('<i class="fas fa-chevron-left"></i>');
      sesowlJqueryObject(".owl-next").html('<i class="fas fa-chevron-right"></i>');
    })
  }
</script>
<h3 class="sesstories_allstories_title"><?php echo $this->translate($this->title ? $this->title : "Stories"); ?> <a href="javascript:;" data-url="sesstories/index/archive" class="sessmoothbox"><?php echo $this->translate('Settings & Archive'); ?> <i class="fas fa-angle-right"></i></a></h3>
<div class="sesstories_bxs sesstories_allstories_container">
  <section class="sesStories_content">
    <div class="sesstories_bxs sesstories_allstories sesstories_allstories_<?php echo $this->identity;?>">
      <?php } ?>
      <?php if($this->viewer()->getIdentity()){ ?>
      <div class="sesstories_allstories_item">
        <div class="sesstories_allstories_item_img">
          <?php
            $image =  "";
            if(!empty($this->story['my_story']['story_content'])) {
              if(!empty($this->story['my_story']['story_content'][0]['photo'])){
                $image = $this->story['my_story']['story_content'][0]['photo'] ;
              }else{
                $image = $this->story['my_story']['story_content'][0]['media_url'] ;
              }
            }else{
              $image = $this->story['my_story']['user_image'];
            }
          ?>
          <img src="<?php echo $image; ?>" />
        </div>
        <div class="sesstories_allstories_item_cont">
          <div class="sesstories_allstories_item_thumb _add">
            <a href="javascript:;" class="create_sesstories"><i class="fas fa-plus"></i></a>
          </div>
          <div class="sesstories_allstories_item_name">
            <?php echo $this->viewer()->getTitle(); ?>
          </div>
          <a href="javascript:;" id="sesstory_id_<?php echo $this->viewer()->getIdentity(); ?>" rel="<?php echo $this->viewer()->getIdentity(); ?>" class="sesstories_allstories_item_link<?php if(empty($this->story['my_story']['story_content'])) { ?> create_sesstories <?php }else{ ?> open_sesstory <?php } ?>"></a>
        </div>
      </div>
      <?php } ?>
      <?php foreach($this->story['stories'] as $story) { ?>
      <?php
        $image =  "";
        if($story['story_content'][0]['is_video']) {
          if($story['story_content'][0]['photo']){
            $image = $story['story_content'][0]['photo'] ;
          }else{
            $image = $story['user_image'];
          }
        }else{
          $image = $story['story_content'][0]['media_url'];
        }
      ?>
      <div class="sesstories_allstories_item<?php echo $story["is_live"] ? ' _islive' : ''; ?>">
        <div class="sesstories_allstories_item_img">
          <img src="<?php echo $image ? $image : $story['user_image']; ?>" alt="" />
        </div>
        <span class="liveicon"><?php echo $this->translate("Live");?></span>
        <div class="sesstories_allstories_item_cont">
          <div class="sesstories_allstories_item_thumb">
            <img src="<?php echo $story['user_image']; ?>" />
          </div>
          <div class="sesstories_allstories_item_name">
            <?php echo $story['username']; ?>
          </div>
        </div>
        <?php 
          if(!isset($story["is_live"]) || empty($story["is_live"])){
            ?>
        <a href="javascript:;"  rel="<?php echo $story['user_id']; ?>" class="sesstories_allstories_item_link open_sesstory"></a>
        <?php }else{ ?>
           <a href="javascript:;" data-hostid="<?php echo $story['elivehost_id']; ?>" data-user="<?php echo $story['user_id']; ?>" data-action="<?php echo $story['activity_id']; ?>" data-story="" class="sesstories_allstories_item_link elivestreaming_data_a"></a>
        <?php } ?>
      </div>
      <?php } ?>
      <?php if(!$this->isAjax){ ?>
    </div>
  </section>
</div>
<?php } ?>
<?php if(!$this->isAjax){ ?>
<script type="text/javascript">
  sesowlJqueryObject(document).ready(function() {
    sesowlJqueryObject('.sesstories_allstories_<?php echo $this->identity;?>').owlCarousel({
      loop:false,
      dots:false,
      nav:true,
      margin:10,
    <?php if($orientation = ($this->layout()->orientation == 'right-to-left')){ ?>
      rtl:true,
    <?php } ?>
    autoWidth:true,
  })
    sesowlJqueryObject(".owl-prev").html('<i class="fas fa-chevron-left"></i>');
    sesowlJqueryObject(".owl-next").html('<i class="fas fa-chevron-right"></i>');
  });
</script>

<div class="sesstories_story_view_main">
  <div class="sesstories_story_view sesstories_bxs">
    <a href="javascript:;" class="sesstories_story_view_close_btn"><i></i></a>
    <div class="sesstories_story_view_bg">
      <img src="" class="sesstories_content_bg_image" alt="" />
    </div>
    <div class="sesstories_story_view_container">
      <div class="sesstories_story_slider">
        <div class="sesstories_story_item">
          <div class="sesstories_story_slider_loader">

          </div>
          <div class="sesstories_story_item_header">
            <div class="sesstories_story_item_header_thumb">
              <img class="sesstories_user_image" src="" alt="" />
            </div>
            <div class="sesstories_story_item_header_info">
              <div class="sesstories_name"></div>
              <div class="sesstories_time"></div>
            </div>
          </div>
          <div class="sesstories_story_item_content">
            <?php  //Photo Story  ?>
            <div class="sesstories_content"></div>
            <div>
              <div class="sesstories_story_item_caption"></div>
              <div class="sesstories_reaction_data"></div>
            </div>
            
            <!--Text Story-->
            <div class="sesstories_content_text" style="background-image:url(); display:none;">
              <div class="sesstories_content_text_inner">
                <div class="sesstories_contenttext" class="_txt"></div>
              </div>
            </div>
            <div>
              <div class="sesstories_reaction_data"></div>
            </div>
          </div>
          <div class="sesstories_story_item_footer">
            <div class="sesstories_story_item_reply_box">
             <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')){ ?>
              <div class="_reaction sesadvcmt_hoverbox_wrapper">
                <div class="sesadvcmt_hoverbox">
                  <?php $getReactions = Engine_Api::_()->getDbTable('reactions', 'sesadvancedcomment')->getReactions(array('userside' => 1, 'fetchAll' => 1)); ?>
                  <?php if(engine_count($getReactions) > 0): ?>
                    <?php foreach($getReactions as $getReaction): ?>
                    <span>
                  <span  data-text="<?php echo $this->translate($getReaction->title);?>"  data-type="<?php echo $getReaction->reaction_id; ?>" class="sesstoriescommentlike reaction_btn sesadvcmt_hoverbox_btn"><div class="reaction sesadvcmt_hoverbox_btn_icon"> <i class="react"  style="background-image:url(<?php echo Engine_Api::_()->sesadvancedcomment()->likeImage($getReaction->reaction_id);?>)"></i> </div></span>
                  <div class="text">
                    <div><?php echo $this->translate($getReaction->title); ?></div>
                  </div>
                </span>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>
                <a href="javascript:;"><i class="_icon sesstories_comment_d"></i></a>
              </div>
              <?php } ?>
              <div class="_input sesstories_message_cnt">
                <input class="sesstories_message_cnt_input" placeholder="Reply..." />
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="sesstories_story_slider_nav">
        <a href="" class="_prev sesstories_previous"><i class="fas fa-chevron-left"></i></a>
        <a href="" class="_next sesstories_next"><i class="fas fa-chevron-right"></i></a>
      </div>
        <div class="sesstories_reply_success_msg" style="display: none;">
          <i class="fas fa-check-circle"></i>
          <span><?php  echo $this->translate("Message sent successfully."); ?></span>
        </div>
    </div>
  </div>
</div>
<?php } ?>
<script>
  var currentDateTime = '<?php echo date("Y-m-d H:i:s"); ?>';
  var storiesData = <?php echo json_encode($this->story); ?>;
  var sesstories_webstoryviewtime = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesstories_storyviewtime',5)*2; ?>";
  var post_max_size_sesstory = <?php echo (int)( Engine_Api::_()->getApi('settings', 'core')->getSetting('sesstories_videouplimit',5)); ?>;
  <?php if($this->story_id){ ?>
     selectedStoryId = <?php echo $this->story_id; ?>;
     selectedStoryUserId = <?php echo $this->user_id; ?>;
    en4.core.runonce.add(function() {
      var isExist = scriptJquery("#sesstory_id_"+selectedStoryUserId);
      if(isExist.length){
        scriptJquery("#sesstory_id_"+selectedStoryUserId).trigger("click");
      }
    })
    <?php } ?>
</script>
<?php if($this->isAjax){ die; ?>


<?php } ?>
