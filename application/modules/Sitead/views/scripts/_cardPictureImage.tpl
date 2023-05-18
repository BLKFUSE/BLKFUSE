<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _cardpictureimage.tpl  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
$isAdvActivity = Engine_Api::_()->sitead()->isModuleEnabled('advancedactivity');
$baseUrl = $this->layout()->staticBaseUrl;
$this->headLink()
->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Seaocore/externals/styles/style_carousel.css') 
->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.carousel.min.css')
->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.carousel.css')
->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/owl.theme.default.css')
->prependStylesheet($baseUrl . 'application/modules/Sitead/externals/styles/style.css');
 if($isAdvActivity)
  $this->headLink()->prependStylesheet($baseUrl . 'application/modules/Advancedactivity/externals/styles/style_advancedactivity.css');$this->headScript()
->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/jquery.min.js')
->appendFile($baseUrl . 'application/modules/Sitead/externals/scripts/owl.carousel.js'); 
?>
<?php $this->carouselClass = 'categorizedAdCarousel'; ?>

<?php 
$slides = 2;
if($this->is_edit)
  $slides = $this->slides_count;
  $slidesCount = Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.slide.limit', 2);
  if($slides > $slidesCount)
  $slides = $slidesCount; 
?>
<script>
  var owl = '';
  var j_q = jq.noConflict();
  j_q(document).ready(function () {
   owl =  j_q('.categorizedAdCarousel').owlCarousel({
    loop: false,
    autoplay: false,
    touchdrag: true,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
        nav: true
      },
      600: {
        items: 1,
        nav: false
      },
      1000: {
        items: 1,
        nav: true,
        loop: false,
        margin: 20,
      }
    },
    slideBy: 1,
    dots: true,
    navigation: true,
      //stagePadding: 50,
    })
 }
 );
</script>

<script type="text/javascript">

  window.addEvent('load', function(){
    var title_count;
    var body_count;

    function nameTitle(thisValue){
      if($('validation_name')){
        document.getElementById("name-element").removeChild($('validation_name'));
      }

      if( thisValue.value != '' ){
        title = thisValue.value;
        var maxSizeTitle= <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.title', 25); ?>;
        if(title.length>maxSizeTitle)
          { title = title.substring(0,maxSizeTitle);
            thisValue.value=title.substring(0,maxSizeTitle);
          }
          $('ad_title').innerHTML = '<a href="javascript:void(0);" >'+title +'</a>';
        }
        else
        { 
         $('ad_title').innerHTML = title = '<a href="javascript:void(0);" >'+'<?php echo $this->string()->escapeJavascript($this->translate("Ad Title")) ?>'+'</a>';
       }
     }

     if($('cmd_ad_format').value == 'carousel') {
       $('card_title').addEvent('keyup', function() {
        if($('validation_card_title-element')){
          document.getElementById("card_title-element").removeChild($('validation_card_title-element'));
        }
        if( this.value != '' ){
          title = this.value;
          $('card_link').innerHTML = title;
        }
        else
        { 
         $('card_link').innerHTML = title = '<?php echo $this->string()->escapeJavascript($this->translate("Card display link ")) ?>';
       }
     });

       $('card_url').addEvent('keyup', function() {
        if($('validation_card_url-element')){
          document.getElementById("card_url-element").removeChild($('validation_card_url-element'));
        }
      });
     }
   });

  var showCard;
    showCard = function(event) {
      var length = $$('.owl-item').length;
      $('imageenable').value = 0;
      var isChecked = event.target.checked;
      $('sitead_endcard').style.display = 'none';
      if(isChecked)
        $('sitead_endcard').style.display = 'block';
          if(isChecked) {
            owl.trigger('add.owl.carousel', [card]);
            owl.trigger('refresh.owl.carousel');
          } 
          else {
            owl.trigger('remove.owl.carousel', [length-1]).trigger('refresh.owl.carousel');
          }
    }

  var showCarouselSlides = function(index) {
    var checkSlide = <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.slide.limit', 2) ?>;
    var i = 1;
    for(i = 1; i <= checkSlide; i++) {
      $('sitead_display_' + i).hide();         
    }
    $('slides_counters').getElements('.slides_counter_button').removeClass('active');
    $('sitead_display_' + index).show();
    $('slides_counter_button_' + index).addClass('active');
  };
  
  var addIndex = <?php echo $slides ?>;
  function valuechange(n, type) {
   
    var i = 1;
    var isChecked = $('show_card').checked;
    var checkSlide = <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.slide.limit', 2) ?>;
    addIndex += n;
    if (addIndex > checkSlide) {addIndex = checkSlide;}    
      if (addIndex < 2) {addIndex = 2;}
           $('button_minus').style.display = 'inline-block';
           $('button_add').style.display = 'inline-block'

        switch(addIndex) {
          case 2:
          $('button_minus').style.display = 'none';
          break;

          case checkSlide:
          $('button_add').style.display = 'none';
          break;

          default:
          $('button_minus').style.display = 'inline-block';
          $('button_add').style.display = 'inline-block';

        }
        $('slides_counters').getElements('.slides_counter_button').removeClass('active').hide();
        for(i = 1; i <= checkSlide; i++) {
          $('ads_' + i + '-enable').value = 0;          
        }
        showCarouselSlides(addIndex);
        for(i = 1; i <= addIndex; i++) {
          $('ads_' + i + '-enable').value = 1;
          $('slides_counters').getElements('.slides_counter_button')[i-1].setStyle('display', 'inline-block');
        }
        res = slide.replace(/Dummy/g, addIndex);
        if(type != 'remove') {
           if(isChecked)
               owl.trigger('add.owl.carousel', [res, addIndex-1]);
             else
              owl.trigger('add.owl.carousel', [res]);
              owl.trigger('refresh.owl.carousel');
          }
           else {
            owl.trigger('remove.owl.carousel', [addIndex]).trigger('refresh.owl.carousel');
          }
      }
    </script>

    <?php 
    $adname =  $this->string()->escapeJavascript($this->translate("Ad Name"));
    $adtitle = $this->string()->escapeJavascript($this->translate("Ad Title"));
    $adbody =  $this->string()->escapeJavascript($this->translate("Description"));
    $cardtitle =  $this->string()->escapeJavascript($this->translate("See More At"));
    $carddesc =  $this->string()->escapeJavascript($this->translate("Card display link"));
    ?>

    <?php if($this->ads_format == 'image' ): ?>
     <div class="cmd-weapper">
      <div class="cmd-info">
        <span class="add-oner">
          <div id="ad_icon">
            <img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />
          </div>
        </span>
        <span class="add-title" id="ad_name"><?php echo $adname; ?>
        <span class="sponsored-tag">
          <?php if($this->enabledSponsored) {
           echo 'Sponsored';
         }
         elseif ($this->enabledFeatured) {
           echo 'Featured';
         } 
         ?>
       </span>
     </span>
     </div>
     <div id="ad_photo_1">
      <img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png"> 
    </div>
    <div class="discription"> 
      <div class="cmd-info">
        <span class="add-title" id='ad_title_1'><?php echo $adtitle; ?></span>
      </div>
      <div class="call-to-action" id='call_to_action_1' style="display: none"> <a href="#">No Button</a></div>
      <p id='ad_body_1'><?php echo $adbody; ?></p>
    </div>
  </div>
  <?php elseif($this->ads_format == 'video') : ?>
    <div class="cmd-weapper">
      <div class="cmd-info">
        <span class="add-oner">
          <div id="ad_icon">
            <img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />
          </div>
        </span>
        <span class="add-title" id="ad_name"><?php echo $adname; ?>
        <span class="sponsored-tag">
          <?php if($this->enabledSponsored) {
           echo 'Sponsored';
         }
         elseif ($this->isFeatured) {
           echo 'Featured';
         } 
         ?>
       </span>
       </span>
     </div>
     <div class="video-ad-wrapper">
      <video  controls id="video_test">
        <source   src="movie.mp4" type="video/mp4">
          Your browser does not support HTML5 video.
        </video>
      </div>
      <div class="discription"> 
        <div class="cmd-info">
          <span class="add-title" id='ad_title_1'><?php echo $adtitle; ?></span>
        </div>
        <div class="call-to-action" id='call_to_action_1' style="display: none"> <a href="#">No Button</a></div>
        <p id='ad_body_1'><?php echo $adbody; ?></p>
      </div>
    </div>

    <?php else : ?>
      <div class="cmd-weapper">
        <div class="cmd-info">
          <span class="add-oner">
           <div id="ad_icon">
            <img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />
          </div>
        </span>
        <span class="add-title" id="ad_name"><?php echo $adname; ?>
        <span class="sponsored-tag"><?php if($this->enabledSponsored) {
         echo 'Sponsored';
       }
       elseif ($this->isFeatured) {
         echo 'Featured';
       } 
       ?></span>
       </span>
     </div>
     <div class="owl-carousel owl-theme <?php echo $this->carouselClass ?>">
      <?php for($i =1; $i<=$slides;  $i++ ) { ?>
        <div class ="sitead_owl">
          <span class="ad_overlay" id="ad_overlay_<?php echo $i?>" style="display: none">No Overlay</span>
          <div id='ad_photo_<?php echo $i ?>'>
           <img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />
         </div>
         <div class="discription"> 
          <div class="cmd-info">
            <span class="add-title" id='ad_title_<?php echo $i ?>'><?php echo $adtitle; ?></span>
          </div>
          <div class="call-to-action" id='call_to_action_<?php echo $i ?>' style="display: none"> <a href="#">No Button</a></div>
          <p id='ad_body_<?php echo $i ?>'><?php echo $adbody; ?></p>
        </div>
      </div>
    <?php } ?>
    <?php if($this->isCarouselCard) : ?>
      <div class = "sitead_owl">
       <div id='ad_photo_10'>
         <img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />
       </div>
       <div class="discription"> 
        <div class="cmd-info">
          <span class="add-title"><?php echo $cardtitle; ?></span>
        </div>
        <p id='card_link'><?php echo $carddesc ?></p>
      </div>
    </div>
  <?php endif; ?>
</div>
</div>
<?php endif; ?>
<script type="text/javascript">
  var slide = '<div class ="sitead_owl">';
  slide += '<span class="ad_overlay" id="ad_overlay_Dummy" style="display: none">No Overlay</span>';
  slide += '<div id="ad_photo_Dummy">';
  slide += '<img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />';
  slide += '</div>';
  slide += '<div class="discription">';
  slide += '<div class="cmd-info">';
  slide += '<span class="add-title" id="ad_title_Dummy"><?php echo $adtitle; ?></span>';
  slide += '</div>';
  slide += '<div class="call-to-action" id="call_to_action_Dummy" style="display: none"> <a href="#">No Button</a></div>';
  slide += '<p id="ad_body_Dummy"><?php echo $adbody; ?></p>';
  slide += '</div>';
  slide +='</div>';

  var card = '<div class = "sitead_owl">';
      card += '<div id="ad_photo_10">';
      card += '<img  src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />';
      card += '</div>';
      card += '<div class="discription">';
      card += '<div class="cmd-info">';
      card += '<span class="add-title"><?php  echo $cardtitle; ?></span>';
      card += '</div>';
      card += '<p id="card_link"><?php echo $carddesc ?></p>';
      card += '</div>';
      card += '</div>';
</script>




