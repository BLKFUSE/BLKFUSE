<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egames/externals/styles/styles.css'); ?>

<div class="sesbasic_bxs egames_view_container">
  <div class="egames_view_top">
    <div class="egames_view_top_bg">
      <span style="background-image: url(<?php echo $this->game->getPhotoUrl(); ?>);"></span>
    </div> 
    <div class="egames_view_top_content">
      <div class="egames_view_photo">
        <img src="<?php echo $this->game->getPhotoUrl(); ?>" alt="" />
      </div> 
      <div class="egames_view_title">
        <h1><?php echo $this->game->getTitle(); ?></h1>
        <?php if(Engine_Api::_()->authorization()->isAllowed($this->game, null, 'edit') || Engine_Api::_()->authorization()->isAllowed($this->game, null, 'delete')){ ?>
          <div class="egames_view_option">
            <a href="javascript:void(0);" class="sesbasic_pulldown_toggle">
              <i><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><g><circle cx="256" cy="256" r="64"></circle><circle cx="256" cy="448" r="64"></circle><circle cx="256" cy="64" r="64"></circle></g></g></g></svg></i>
            </a>
            <div class="sesbasic_pulldown_options">
              <ul>
                <?php if(Engine_Api::_()->authorization()->isAllowed($this->game, null, 'edit')){ ?>
                  <li><a href="<?php echo $this->url(array('action' => 'edit','game_id'=>$this->game->game_id), 'egames_specific'); ?>" class="sesbasic_icon_edit">Edit</a></li>
                <?php } ?>
                <?php if(Engine_Api::_()->authorization()->isAllowed($this->game, null, 'delete')){ ?>
                  <li><a href="<?php echo $this->url(array('action' => 'delete','game_id'=>$this->game->game_id), 'egames_specific'); ?>" class="sesbasic_icon_delete openSmoothbox">Delete</a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
        <?php } ?>
      </div>
      <?php if($this->game->category_id){ ?>
      <div class="egames_view_category">
        <a href="<?php echo $this->url(array('action' => 'browse'), 'egames_general'); ?>?category_id=<?php echo $this->game->category_id ?>"><?php echo Engine_Api::_()->getItem("egames_category",$this->game->category_id)->getTitle(); ?></a>
      </div>
      <?php } ?>
      <div class="egames_view_top_play_btn">
        <a href="javascript:;" class="sesbasic_animation play_game"><?php echo $this->translate("Play");?></a>
      </div>
    </div>

    <div id="iframe_content" style="display:none">
      <iframe src="<?php echo $this->game->url; ?>" style="height:600px;width:100%" ></iframe>
      <div class="egames_view_top_fullscreen" id="egames_view_top_fullscreen">
        <a href="javascript:;" id="fullScreen" title="<?php echo $this->translate("Full Screen")?>"><i class="fas fa-expand"></i></a>
      </div>
      <div class="egames_view_top_fullscreen" id="egames_view_top_smallscreen" style="display:none;">
        <a href="javascript:;" id="exit_fullScreen" title="<?php echo $this->translate("Exit Full Screen")?>"><i class="fas fa-compress"></i></a>
      </div>
    </div>
  
  </div>
  <div class="egames_view_main">
    <div class="egames_view_des">
      <?php echo $this->game->description; ?>
    </div>
  </div>
</div>

<script>
  scriptJquery("#fullScreen").click(function(e){
    scriptJquery("body").addClass("egames_fullscreen");
    scriptJquery(".egames_view_top_fullscreen").hide();
    scriptJquery(".egames_view_top_smallscreen").show();
  });
  scriptJquery("#exit_fullScreen").click(function(e){
    scriptJquery("body").removeClass("egames_fullscreen");
    scriptJquery(".egames_view_top_fullscreen").show();
    scriptJquery(".egames_view_top_smallscreen").hide();
  });
  scriptJquery(document).on("click",'.play_game',function(){
    scriptJquery(".egames_view_top_content").hide();
    scriptJquery(".egames_view_top_bg").hide();
    scriptJquery("#iframe_content").show();
    scriptJquery.post(en4.core.baseUrl+"egames/index/play",{game_id:"<?php echo $this->game->game_id;?>"},function(res) {

    })

  })
</script>
