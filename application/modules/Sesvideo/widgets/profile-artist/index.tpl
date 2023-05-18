<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php
if(!$this->is_ajax){
if(isset($this->docActive)){
	$imageURL = $this->artists->getPhotoUrl();
	if(strpos($this->artists->getPhotoUrl(),'http') === false)
          	$imageURL = (!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" : "http://". $_SERVER['HTTP_HOST'].$this->artists->getPhotoUrl();
  $this->doctype('XHTML1_RDFA');
  $this->headMeta()->setProperty('og:title', strip_tags($this->artists->getTitle()));
  $this->headMeta()->setProperty('og:description', strip_tags($this->artists->getDescription()));
  $this->headMeta()->setProperty('og:image',$imageURL);
  $this->headMeta()->setProperty('twitter:title', strip_tags($this->artists->getTitle()));
  $this->headMeta()->setProperty('twitter:description', strip_tags($this->artists->getDescription()));
}
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/favourites.js'); ?>
<?php
//This is done to make these links more uniform with other viewscripts
$artist = $this->artists;
?>
<?php if($this->showRating): ?>
  <script type="text/javascript">    
    en4.core.runonce.add(function() {
      var pre_rate = '<?php echo $this->artists->rating;?>';
      <?php if($this->viewer_id == 0){ ?>
      rated = 0;
      <?php } elseif($this->allowShowRating == 1 && $this->allowRating == 0) { ?>
      var rated = 3;
      <?php } elseif($this->allowRateAgain == 0 && $this->rated) { ?>
      var rated = 1;
      <?php } elseif($this->canRate == 0 && $this->viewer_id != 0) { ?>
      var rated = 4;
      <?php } elseif(!$this->allowMine) { ?>
      var rated = 2;
      <?php } else { ?>
      var rated = '90';
      <?php } ?>
      var resource_id = '<?php echo $this->artists->artist_id;?>';
      var total_votes = '<?php echo $this->rating_count;?>';
      var viewer = '<?php echo $this->viewer_id;?>';
      new_text = '';
      var rating_over = window.rating_over = function(rating) {
        if( rated == 1 ) {
          document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('You already rated.');?>";
          return;
          //set_rating();
        }
        <?php if(!$this->canRate) { ?>
          else if(rated == 4){
               document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('You are not allowed to rate.');?>";
               return;
          }
        <?php } ?>
        <?php if(!$this->allowMine) { ?>
          else if(rated == 2){
               document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Rating on own album is not allowed.');?>";
               return;
          }
        <?php } ?>
        <?php if($this->allowShowRating == 1) { ?>
          else if(rated == 3){
               document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('You are not allowed to rate.');?>";
               return;
          }
        <?php } ?>
        else if( viewer == 0 ) {
          document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Please login to rate.');?>";
          return;
        } else {
          document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Click to rate.');?>";
          for(var x=1; x<=5; x++) {
            scriptJquery('#rate_'+x).removeAttr("class");
            if(x <= rating) {
              scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big');
            } else {
              scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big_disabled');
            }
          }
        }
      }
      var rating_out = window.rating_out = function() {
        if (new_text != ''){
          document.getElementById('rating_text').innerHTML = new_text;
        }
        else{
          document.getElementById('rating_text').innerHTML = " <?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";
        }
        if (pre_rate != 0){
          set_rating();
        }
        else {
          for(var x=1; x<=5; x++) {
            scriptJquery('#rate_'+x).removeAttr("class");
            scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big_disabled');
          }
        }
      }
      var set_rating = window.set_rating = function() {
        var rating = pre_rate;
        if (new_text != ''){
          document.getElementById('rating_text').innerHTML = new_text;
        }
        else{
          document.getElementById('rating_text').innerHTML = "<?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";
        }
        for(var x=1; x<=parseInt(rating); x++) {
          scriptJquery('#rate_'+x).removeAttr("class");
          scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big');
        }
        for(var x=parseInt(rating)+1; x<=5; x++) {
          scriptJquery('#rate_'+x).removeAttr("class");
          scriptJquery('#rate_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big_disabled');
        }
        var remainder = Math.round(rating)-rating;
        if (remainder <= 0.5 && remainder !=0){
          var last = parseInt(rating)+1;
          scriptJquery('#rate_'+x).removeAttr("class");
          scriptJquery('#rate_'+last).addClass( 'fas fa-star rating_star_big_generic rating_star_big_half');
        }
      }
      var rate = window.rate = function(rating) {
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Thanks for rating.');?>";
        <?php if($this->allowRateAgain == 0 && !$this->rated){ ?>
               for(var x=1; x<=5; x++) {
                  scriptJquery('#rate_'+x).attr('onclick', '');
                }
            <?php } ?>
        (scriptJquery.ajax({
          dataType: 'json',
          'format': 'json',
          'url' : '<?php echo $this->url(array('module' => 'sesvideo', 'controller' => 'index', 'action' => 'rate'), 'default', true) ?>',
          'data' : {
            'format' : 'json',
            'rating' : rating,
            'resource_id': resource_id,
            'resource_type':'<?php echo $this->rating_type; ?>'
          },
          success : function(responseJSON, responseText)
          {
            <?php if($this->allowRateAgain == 0 && !$this->rated){ ?>
                rated = 1;
            <?php } ?>
            total_votes = responseJSON[0].total;
            var rating_sum = responseJSON[0].rating_sum;
            pre_rate = rating_sum / total_votes;
            set_rating();
            document.getElementById('rating_text').innerHTML = responseJSON[0].total+" ratings";
            new_text = responseJSON[0].total+" ratings";
						showTooltip(10,10,'<i class="fa fa-star"></i><span>'+("Artist Rated successfully")+'</span>', 'sesbasic_rated_notification');
          }
        }));
      }
      set_rating();
    });
  </script>
<?php endif; ?>
<div class="sesvideo_item_view_wrapper clear">
  <div class="sesvideo_item_view_top sesbasic_clearfix sesbasic_bxs sesbm">
    <div class="sesvideo_item_view_artwork">
      <?php if($artist->artist_photo): ?>
      <?php $img_path = $artist->getPhotoUrl();
      $path = $img_path;  ?>
      <img src="<?php echo $path ?>">
      <?php endif; ?>
     </div>
    <div class="sesvideo_item_view_info">
      <div class="sesvideo_item_view_title">
        <?php echo $artist->name ?>
      </div>
      <?php if(!empty($this->information)): ?>
      <?php if(engine_in_array('favouriteCountAr', $this->informationArtist) || engine_in_array('ratingCountAr', $this->informationArtist)): ?>
        <div class="sesvideo_item_view_stats sesvideo_list_stats sesbasic_text_light sesbasic_clearfix"> 
          <?php if(engine_in_array('favouriteCountAr', $this->information)): ?>
          	<span title="<?php echo $this->translate(array('%s favorite', '%s favorites', $this->artists->favourite_count), $this->locale()->toNumber($this->artists->favourite_count)) ?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $this->locale()->toNumber($this->artists->favourite_count); ?></span>
          <?php endif; ?>
          <?php if(engine_in_array('ratingCountAr', $this->informationArtist) && $this->showRating): ?>
          	<span title=" <?php echo $this->translate(array('%s rating', '%s ratings', $this->artists->rating), $this->locale()->toNumber($this->artists->rating)); ?>"><i class="far fa-star"></i><?php echo $this->locale()->toNumber($this->artists->rating); ?></span>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <?php if(engine_in_array('description', $this->informationArtist)): ?>
        <p class="sesvideo_item_view_des">
          <?php echo $this->viewMore($this->artists->overview) ?>
        </p>
      <?php endif; ?>
      
      <?php if(engine_in_array('ratingStarsAr', $this->informationArtist) && $this->showRating && $this->canRate):  ?>
        <div id="album_rating" class="sesvideo_rating_star" onmouseout="rating_out();">
          <span id="rate_1" class="rating_star_big_generic fas fa-star" <?php  if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating ):?>onclick="rate(1);"<?php  endif; ?> onmouseover="rating_over(1);"></span>
          <span id="rate_2" class="rating_star_big_generic fas fa-star" <?php if ( $this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(2);"<?php endif; ?> onmouseover="rating_over(2);"></span>
          <span id="rate_3" class="rating_star_big_generic fas fa-star" <?php if ( $this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(3);"<?php endif; ?> onmouseover="rating_over(3);"></span>
          <span id="rate_4" class="rating_star_big_generic fas fa-star" <?php if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(4);"<?php endif; ?> onmouseover="rating_over(4);"></span>
          <span id="rate_5" class="rating_star_big_generic fas fa-star" <?php if ($this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(5);"<?php endif; ?> onmouseover="rating_over(5);"></span>
          <span id="rating_text" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
        </div>
      <?php endif; ?>
      <div class="sesvideo_options_buttons sesvideo_item_view_options">
		<?php
           		if(engine_in_array('socialShare', $this->informationArtist)){
          		$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->artists->getHref()); ?>
          		
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->artists, 'socialshare_icon_limit' => $this->socialshare_icon_limit, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon)); ?>

                <?php } ?>
         <?php if (engine_in_array('addFavouriteButtonAr', $this->informationArtist) && !empty($this->viewer_id)): ?>
         <?php if($this->artistlink && engine_in_array('favourite', $this->artistlink)): ?>
          <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>'sesvideo_artist','resource_id'=>$this->artists->getIdentity())); ?>
                        <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_sesvideo_artist <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $this->artists->getIdentity() ; ?>"><i class="fa fa-heart"></i><span><?php echo $this->artists->favourite_count; ?></span></a>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<?php } ?>
  <?php include APPLICATION_PATH . '/application/modules/Sesvideo/views/scripts/_showVideoListGrid.tpl'; ?>
<?php if(!$this->is_ajax){ ?>
</div>
<?php } ?>
