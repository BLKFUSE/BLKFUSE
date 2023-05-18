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

<?php $identity = $this->identity; ?>
<script type="application/javascript">
scriptJquery(document).ready(function() {
	var htmlElement<?php echo "_".$identity ?> = scriptJquery("body");
  htmlElement<?php echo "_".$identity ?>.addClass('sesvideo_slideshow');
});
var logoSesvideo<?php echo "_".$identity ?> = scriptJquery('.layout_core_menu_logo').html();
if(!logoSesvideo<?php echo "_".$identity ?> )
	logoSesvideo<?php echo "_".$identity ?> = scriptJquery('.header_logo').html();
<?php if($this->main_navigation): ?>
scriptJquery('#global_header').remove();
<?php endif; ?>
<?php if($this->main_navigation): ?>
scriptJquery('#global_wrapper').css('padding-top','0px');
<?php endif; ?>
<?php if($this->full_width): ?>
scriptJquery(document).ready(function() {
	var htmlElement<?php echo "_".$identity ?> = scriptJquery("body");
  htmlElement<?php echo "_".$identity ?>.addClass('sesvideo_slideshow_full_width');
});
<?php endif; ?>
</script>
<?php
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/slideshowstyle.css');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/slideshowmodernizr.js');
?>
<div class="sesvideo_slideshow_wrapper sesbasic_clearfix" style="height:<?php echo $this->height.'px'; ?>;">
	<div class="sesvideo_slideshow_container">
      <header class="cd-header sesbasic_bxs">
      <div id="cd-logo<?php echo "_".$identity ?>" class="cd-logo"></div>
    	<?php if($this->main_navigation && engine_count($this->navigation)): ?>
        <nav class="cd-primary-nav cd-primary-nav<?php echo "_".$identity ?>">
        	<a href="javascript:void(0);" class="cd-primary-nav-browse-btn">
          	<span><?php echo $this->translate("Browse"); ?></span>
          	<i class="fa fa-angle-down"></i>
          </a>
          <a href="javascript:void(0);" class="cd-primary-nav-mobile-browse-btn">
          	<i class="fa fa-bars"></i>
          </a>
        	<ul class="cd-primary-nav-dropdown">
        		<?php foreach( $this->navigation as $navigationMenu ): ?>
        			<li>
        				<?php if ($navigationMenu->action):  ?>
										<a class= "<?php echo $navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array('action' => $navigationMenu->action), $navigationMenu->route, true) : $navigationMenu->uri ?>'><?php echo $this->translate($navigationMenu->label); ?></a>									
                <?php else : ?>
                <?php $classArray = explode(' ', $navigationMenu->class); 
									if($classArray[1] == 'core_main_home' && $this->viewer->getIdentity() != 0):  ?>
										<a class= "<?php echo $navigationMenu->class ?>" href='<?php echo $this->url(array('action' => 'home'), $navigationMenu->route, true) ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
									<?php elseif($classArray[1] == 'core_main_invite' && $this->viewer->getIdentity() != 0): ?>
				            <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo $this->url(array('module' => 'invite'), $navigationMenu->route, true) ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
									<?php else: ?>
                  <a class= "<?php echo $navigationMenu->getClass() ?>" href='<?php echo $navigationMenu->getHref() ?>'><?php echo $this->translate($navigationMenu->label); ?></a>
                 <!-- <a class= "<?php echo $navigationMenu->class ?>" href='<?php echo empty($navigationMenu->uri) ? $this->url(array(), $navigationMenu->route, true) : $navigationMenu->uri ?>'><?php echo $this->translate($navigationMenu->label); ?></a>-->
                  <?php endif; ?>
                <?php endif; ?>
        			</li>
        		<?php endforeach; ?>
        	</ul>
        </nav>
      <?php endif; ?>
      <?php if($this->mini_navigation && engine_count($this->menumininavigation)): ?>
      	<div id='core_menu_mini_menu' class="cd-mini-menu">
          <?php
            $count = engine_count($this->menumininavigation);
            foreach( $this->menumininavigation->getPages() as $item ) $item->setOrder(--$count);
          ?>
          <ul>
            <?php if( $this->viewer->getIdentity()) :?>
            <li id='core_menu_mini_menu_update'>
              <span onclick="toggleUpdatesPulldown<?php echo "_".$identity ?>(event, this, '4');" style="display: inline-block;" class="updates_pulldown">
                <div class="pulldown_contents_wrapper">
                  <div class="pulldown_contents">
                    <ul class="notifications_menu" id="notifications_menu">
                      <div class="notifications_loading" id="notifications_loading">
                        <img src='<?php echo $this->layout()->staticBaseUrl ?>application/modules/Core/externals/images/loading.gif' style='float:left; margin-right: 5px;' />
                        <?php echo $this->translate("Loading ...") ?>
                      </div>
                    </ul>
                  </div>
                  <div class="pulldown_options">
                    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'activity', 'controller' => 'notifications'),
                       $this->translate('View All Updates'),
                       array('id' => 'notifications_viewall_link')) ?>
                    <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Mark All Read'), array(
                      'id' => 'notifications_markread_link',
                    )) ?>
                  </div>
                </div>
                <a href="javascript:void(0);" id="updates_toggle" <?php if( $this->notificationCount ):?> class="new_updates"<?php endif;?>><?php echo $this->translate(array('%s Update', '%s Updates', $this->notificationCount), $this->locale()->toNumber($this->notificationCount)) ?></a>
              </span>
            </li>
            <?php endif; ?>
            <?php foreach( $this->menumininavigation as $item ):  ?>
              <?php if(empty($this->viewer_id)): ?>
	              <?php if($item->class == 'menu_core_mini core_mini_friends' || $item->class == 'menu_core_mini core_mini_notification') continue; ?>
              <?php endif; ?>
              <?php if($item->class == 'updates_toggle new_updates menu_core_mini core_mini_update'){
                continue;
              } ?>
              <li>
	              <?php echo $this->htmlLink($item->getHref(), $this->translate($item->getLabel()), array_filter(array('class' => ( !empty($item->class) ? $item->class : null ),       'alt' => ( !empty($item->alt) ? $item->alt : null ), 'target' => ( !empty($item->target) ? $item->target : null )))); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
			<?php endif; ?>
    </header> 
    <section class="cd-hero sesbasic_bxs">
      <ul class="cd-hero-slider cd-hero-slider<?php echo "_".$identity ?> <?php echo $this->autoplay ? 'autoplay' : '' ; ?>" style="height:<?php echo $this->height.'px'; ?>;">
    <?php 
      $counter = 0;
      foreach($this->paginator as $itemdata){ ?>	
      <?php 
        $item = Engine_Api::_()->getItem('sesvideo_slide',$itemdata->slide_id); 
        ?>
    <?php if($item->file_type == 'mp4'){ ?>
        
        <li class="cd-bg-video <?php echo $counter == 0 ? 'selected' : '' ; ?> form-in-<?php echo $item->position_register_form == 'right' ? 'right' : 'left'; ?>">
          <?php if($item->show_register_form && Engine_Api::_()->user()->getViewer()->getIdentity() == 0){ ?>
            <div class="cd-half-width cd-signupform-container">
              <div class="cd-signupform">
                <?php echo $this->action("index", "signup", defined('sesquicksignup') ? "quicksignup" : "sesvideo", array('disableContent'=>true)); ?>
              </div>
            </div>
          <?php } ?>
          <?php 
          			$fullWidth = true;
            		if($item->show_register_form == 0){
                    	$fullWidth = true;
          			 }else{
                 		if(Engine_Api::_()->user()->getViewer()->getIdentity() == 0)
                    	$fullWidth = false;
                     else
                     	 $fullWidth = true;
           				} 
          ?>
          <div class="<?php echo $fullWidth ? 'cd-full-width' : 'cd-half-width' ; ?>">
            <?php if($item->title != '' || $item->description  != ''){ ?>	
              <?php if($item->title != ''){ ?>
                <h2 style="color:#<?php echo $item->title_button_color; ?>"><?php echo $this->translate($item->title); ?></h2>
              <?php } ?>
            <?php } ?>
            <?php if($item->description  != ''){ ?>
              <p style="color:#<?php echo $item->description_button_color; ?>"><?php echo $this->translate(nl2br($item->description)) ; ?></p>
            <?php } ?>
            <?php if($item->login_button && Engine_Api::_()->user()->getViewer()->getIdentity() == 0){ ?>
              <a href="<?php echo $this->layout()->staticBaseUrl; ?>login" class="cd-btn"  onMouseOver="this.style.backgroundColor='#<?php echo $item->login_button_mouseover_color; ?>'"   onMouseOut="this.style.backgroundColor='#<?php echo $item->login_button_color; ?>'" style="color:#<?php echo $item->login_button_text_color; ?>; background-color:#<?php echo $item->login_button_color; ?>"><?php echo $this->translate($item->login_button_text); ?></a>
            <?php } ?>
            <?php if($item->signup_button && Engine_Api::_()->user()->getViewer()->getIdentity() == 0){ ?>
              <a href="<?php echo $this->layout()->staticBaseUrl; ?>signup" class="cd-btn secondary"  onMouseOver="this.style.backgroundColor='#<?php echo $item->signup_button_mouseover_color; ?>'"   onMouseOut="this.style.backgroundColor='#<?php echo $item->signup_button_color; ?>'" style="color:#<?php echo $item->signup_button_text_color; ?>;background-color:#<?php echo $item->signup_button_color; ?>"><?php echo $this->translate($item->signup_button_text); ?></a>
            <?php } ?>
            <?php if($item->extra_button){ ?>
              <a href="<?php echo $item->extra_button_link ? $item->extra_button_link : 'javascript:void(0)'; ?>" class="cd-btn secondary"  onMouseOver="this.style.backgroundColor='#<?php echo $item->extra_button_mouseover_color; ?>'"   onMouseOut="this.style.backgroundColor='#<?php echo $item->extra_button_color; ?>'" style="color:#<?php echo $item->extra_button_text_color; ?>;background-color:#<?php echo $item->extra_button_color; ?>"><?php echo $this->translate($item->extra_button_text); ?></a>
            <?php } ?> 
			 <?php if($this->searchEnable){ ?>
             <div class="cd-slider-searchbox <?php echo ($this->paginator->getTotalItemCount() < 2 || !$this->thumbnail) ? '' : 'isnav' ?>" >
               <?php echo $this->content()->renderWidget('sesvideo.search'); ?>
             </div>
            <?php } ?>
          </div>
		  <div class="cd-bg-video">
          <video preload="metadata" class="cd-bg-video-wrapper" loop  autoplay muted>
            <source src="<?php echo $item->getFilePath('file_id'); ?>" type="video/<?php echo @end(explode('.',$item->getFilePath('file_id'))); ?>">
          </video>
		  </div>
        </li>
    <?php }else{ ?>
       <li class="<?php echo $counter == 0 ? 'selected' : '' ; ?> form-in-<?php echo $item->position_register_form == 'right' ? 'right' : 'left'; ?>" style="background-image:url(<?php echo $item->getFilePath('file_id') ?>);">
         
        </li>
   <?php } ?>
    <?php 
      $counter++;
        } ?>
      </ul> <!-- .cd-hero-slider -->
     <?php if($this->paginator->getTotalItemCount()>1 && $this->thumbnail){ ?>
      <div class="cd-slider-nav cd-slider-nav<?php echo "_".$identity ?>">
        <nav>
          <!--<span class="cd-marker item-1"></span>-->
          <ul>
          <?php $counter = 0; ?>
          <?php foreach($this->paginator as $item){ ?>
            <li class="<?php echo $counter == 0 ? 'selected' : ''; ?> ">
              <a href="javascript:;" style="background-image:url(<?php echo $item->getFilePath('thumb_icon'); ?>)"></a>
            </li>
          <?php $counter++;
              } ?>
          </ul>
        </nav> 
      </div> <!-- .cd-slider-nav -->
     <?php } ?>
    </section> 
	 </div>
</div>  
<?php //$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/slideshowmain.js'); ?>
<script type="application/javascript">
var autoPlayId<?php echo "_".$identity ?>,firstVideoSrc<?php echo "_".$identity ?>=false,isOnRegister<?php echo "_".$identity ?> = false,IsfinishVideoNext<?php echo "_".$identity ?> = false,clearIntvalSesVideoSlideshow1<?php echo "_".$identity ?>,clearIntvalSesVideoSlideshow2<?php echo "_".$identity ?>,autoPlayDelay<?php echo "_".$identity ?> = 5000;
jQuery(document).ready(function($){
	var slidesWrapper<?php echo "_".$identity ?> = document.getElementById('.cd-hero-slider<?php echo "_".$identity ?>');
	//check if a .cd-hero-slider exists in the DOM onended=""
	if ( slidesWrapper<?php echo "_".$identity ?>.length > 0 ) {
		var primaryNav<?php echo "_".$identity ?> = document.getElementById('.cd-primary-nav<?php echo "_".$identity ?>'),
			sliderNav<?php echo "_".$identity ?> = document.getElementById('.cd-slider-nav<?php echo "_".$identity ?>'),
			navigationMarker = document.getElementById('.cd-marker'),
			slidesNumber<?php echo "_".$identity ?> = slidesWrapper<?php echo "_".$identity ?>.children('li').length,
			visibleSlidePosition<?php echo "_".$identity ?> = 0;
		//upload videos (if not on mobile devices)
		uploadVideo<?php echo "_".$identity ?>(slidesWrapper<?php echo "_".$identity ?>);
		//autoplay slider
		if(!firstVideoSrc<?php echo "_".$identity ?>)
			setAutoplay<?php echo "_".$identity ?>(slidesWrapper<?php echo "_".$identity ?>, slidesNumber<?php echo "_".$identity ?>, autoPlayDelay<?php echo "_".$identity ?>);
		//on mobile - open/close primary navigation clicking/tapping the menu icon
		primaryNav<?php echo "_".$identity ?>.on('click', function(event){
			if($(event.target).is('.cd-primary-nav<?php echo "_".$identity ?>')) $(this).children('ul').toggleClass('is-visible');
		});
		//change visible slide
		sliderNav<?php echo "_".$identity ?>.on('click', 'li', function(event){
			event.preventDefault();
			var selectedItem = $(this);
			if(!selectedItem.hasClass('selected')) {
				// if it's not already selected
				var selectedPosition = selectedItem.index(),
					activePosition = slidesWrapper<?php echo "_".$identity ?>.find('li.selected').index();
				
				if( activePosition < selectedPosition) {
					nextSlide<?php echo "_".$identity ?>(slidesWrapper<?php echo "_".$identity ?>.find('.selected'), slidesWrapper<?php echo "_".$identity ?>, sliderNav<?php echo "_".$identity ?>, selectedPosition);
				} else {
					prevSlide<?php echo "_".$identity ?>(slidesWrapper<?php echo "_".$identity ?>.find('.selected'), slidesWrapper<?php echo "_".$identity ?>, sliderNav<?php echo "_".$identity ?>, selectedPosition);
				}
				//this is used for the autoplay
				visibleSlidePosition<?php echo "_".$identity ?> = selectedPosition;
				updateSliderNavigation<?php echo "_".$identity ?>(sliderNav<?php echo "_".$identity ?>, selectedPosition);
				updateNavigationMarker<?php echo "_".$identity ?>(navigationMarker, selectedPosition+1);
				//reset autoplay
				setAutoplay<?php echo "_".$identity ?>(slidesWrapper<?php echo "_".$identity ?>, slidesNumber<?php echo "_".$identity ?>, autoPlayDelay<?php echo "_".$identity ?>);
				if(slidesWrapper<?php echo "_".$identity ?>.find('li.selected').hasClass('cd-bg-video')){
					 clearInterval(autoPlayId<?php echo "_".$identity ?>);
					 clearInterval(clearIntvalSesVideoSlideshow1<?php echo "_".$identity ?>);
					 clearInterval(clearIntvalSesVideoSlideshow1<?php echo "_".$identity ?>);	
				}
			}else if(slidesWrapper<?php echo "_".$identity ?>.children().length == 1 && slidesWrapper<?php echo "_".$identity ?>.children().eq(0).hasClass('cd-bg-video')){
				 slidesWrapper<?php echo "_".$identity ?>.eq(0).find('.cd-bg-video-wrapper').find('video').attr('loop','loop');
				  slidesWrapper<?php echo "_".$identity ?>.eq(0).find('.cd-bg-video-wrapper').find('video').get(0).play();
			}
		});
	}
	function nextSlide<?php echo "_".$identity ?>(visibleSlide, container, pagination, n){
		visibleSlide.removeClass('selected from-left from-right').addClass('is-moving').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
			visibleSlide.removeClass('is-moving');
		});
		container.children('li').eq(n).addClass('selected from-right').prevAll().addClass('move-left');
		container.children('li').eq(n).find('.cd-signupform-container').show();
		checkVideo<?php echo "_".$identity ?>(visibleSlide, container, n);
	}
	function prevSlide<?php echo "_".$identity ?>(visibleSlide, container, pagination, n){
		visibleSlide.removeClass('selected from-left from-right').addClass('is-moving').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
			visibleSlide.removeClass('is-moving');
		});
		container.children('li').eq(n).addClass('selected from-left').removeClass('move-left').nextAll().removeClass('move-left');
		checkVideo<?php echo "_".$identity ?>(visibleSlide, container, n);
	}
	function updateSliderNavigation<?php echo "_".$identity ?>(pagination, n) {
		var navigationDot = pagination.find('.selected');
		navigationDot.removeClass('selected');
		pagination.find('li').eq(n).addClass('selected');
	}
	function setAutoplay<?php echo "_".$identity ?>(wrapper,length,delay){
	 if(wrapper.hasClass('autoplay') && !isOnRegister<?php echo "_".$identity ?> && !IsfinishVideoNext<?php echo "_".$identity ?>){
			clearInterval(autoPlayId<?php echo "_".$identity ?>);
			autoPlayId<?php echo "_".$identity ?> = window.setInterval(function(){autoplaySlider<?php echo "_".$identity ?>(length)}, delay);
	 }
	}
	function autoplaySlider<?php echo "_".$identity ?>(length) {
		if( visibleSlidePosition<?php echo "_".$identity ?> < length - 1) {
			nextSlide<?php echo "_".$identity ?>(slidesWrapper<?php echo "_".$identity ?>.find('.selected'), slidesWrapper<?php echo "_".$identity ?>, sliderNav<?php echo "_".$identity ?>, visibleSlidePosition<?php echo "_".$identity ?> + 1);
			visibleSlidePosition<?php echo "_".$identity ?> +=1;
		} else {
			prevSlide<?php echo "_".$identity ?>(slidesWrapper<?php echo "_".$identity ?>.find('.selected'), slidesWrapper<?php echo "_".$identity ?>, sliderNav<?php echo "_".$identity ?>, 0);
			visibleSlidePosition<?php echo "_".$identity ?> = 0;
		}
		updateNavigationMarker<?php echo "_".$identity ?>(navigationMarker, visibleSlidePosition<?php echo "_".$identity ?>+1);
		updateSliderNavigation<?php echo "_".$identity ?>(sliderNav<?php echo "_".$identity ?>, visibleSlidePosition<?php echo "_".$identity ?>);
	}
	function uploadVideo<?php echo "_".$identity ?>(container) {
		container.find('.cd-bg-video-wrapper').each(function(){
			var videoWrapper = $(this);
			if( videoWrapper.is(':visible') ) {
				// if visible - we are not on a mobile device 
				var	videoUrl = videoWrapper.data('video'),
				videoImage = videoWrapper.data('image');
					video = $('<video muted="muted" onended="finishVideoNext<?php echo "_".$identity ?>()" controls preload><source src="'+videoUrl+'" type="video/mp4" /></video><div class="cd-hero-slider-video-img" style="background-image:url('+videoImage+');"></div>');
				video.appendTo(videoWrapper);
				// play video if first slide
				if(videoWrapper.parent('.cd-bg-video.selected').length > 0) {
					 if(!Modernizr.touch){
							 scriptJquery(video).find('video').show();
							 video.get(0).play();
							 firstVideoSrc<?php echo "_".$identity ?> = true;
					 }else{
							scriptJquery(video).eq(0).hide();
							clearInterval(autoPlayId<?php echo "_".$identity ?>);
							autoPlayId<?php echo "_".$identity ?> = window.setInterval(function(){autoplaySlider<?php echo "_".$identity ?>(length)}, autoPlayDelay<?php echo "_".$identity ?>);
					 }
				};
			}
		});
	}
	function checkVideo<?php echo "_".$identity ?>(hiddenSlide, container, n) {
		//check if a video outside the viewport is playing - if yes, pause it
		var hiddenVideo = hiddenSlide.find('video');
		if( hiddenVideo.length > 0 ) hiddenVideo.get(0).pause();
		//check if the select slide contains a video element - if yes, play the video
		var visibleVideo = container.children('li').eq(n).find('video');
		if( visibleVideo.length > 0 ) { 
			clearInterval(autoPlayId<?php echo "_".$identity ?>);
			clearInterval(clearIntvalSesVideoSlideshow2<?php echo "_".$identity ?>);
			clearInterval(clearIntvalSesVideoSlideshow1<?php echo "_".$identity ?>);
			if(!Modernizr.touch){
					scriptJquery(visibleVideo).show();
					visibleVideo.get(0).play();
			 }else{
					scriptJquery(visibleVideo).hide();
					clearInterval(autoPlayId<?php echo "_".$identity ?>);
					autoPlayId<?php echo "_".$identity ?> = window.setInterval(function(){autoplaySlider<?php echo "_".$identity ?>(length)}, autoPlayDelay<?php echo "_".$identity ?>);
			}
		}else{
			IsfinishVideoNext<?php echo "_".$identity ?> = false;
		}
	}
	function updateNavigationMarker<?php echo "_".$identity ?>(marker, n) {
		marker.removeClassPrefix<?php echo "_".$identity ?>('item').addClass('item-'+n);
	}
	$.fn.removeClassPrefix<?php echo "_".$identity ?> = function(prefix) {
		//remove all classes starting with 'prefix'
	    this.each(function(i, el) {
	        var classes = el.className.split(" ").filter(function(c) {
	            return c.lastIndexOf(prefix, 0) !== 0;
	        });
	        el.className = $.trim(classes.join(" "));
	    });
	    return this;
	};
	
});
scriptJquery(document).ready(function(e){
	scriptJquery('#signup_account_form input,#signup_account_form input[type=email]').each(
				function(index){
						var input = scriptJquery(this);
						if(scriptJquery(this).closest('div').parent().css('display') != 'none' && scriptJquery(this).closest('div').parent().find('.form-label').find('label').first().length && scriptJquery(this).prop('type') != 'hidden' && scriptJquery(this).closest('div').parent().attr('class') != 'form-elements'){	
						  if(scriptJquery(this).prop('type') == 'email' || scriptJquery(this).prop('type') == 'text' || scriptJquery(this).prop('type') == 'password'){
								scriptJquery(this).attr('placeholder',scriptJquery(this).closest('div').parent().find('.form-label').find('label').html());
							}
						}
				}
			)	
});
	
<?php if($this->autoplay){ ?>
scriptJquery("#signup_account_form").mouseenter(function(e) {
	 clearInterval(autoPlayId<?php echo "_".$identity ?>);
	 clearInterval(clearIntvalSesVideoSlideshow1<?php echo "_".$identity ?>);
	 clearInterval(clearIntvalSesVideoSlideshow1<?php echo "_".$identity ?>);
	 isOnRegister<?php echo "_".$identity ?> = true;
}).mouseleave(function(e) {
   isOnRegister<?php echo "_".$identity ?> = false; 
	 if(IsfinishVideoNext<?php echo "_".$identity ?>){
	 	clearIntvalSesVideoSlideshow1<?php echo "_".$identity ?>=window.setTimeout(function(){finishVideoNext<?php echo "_".$identity ?>()}, 3000);
	 }else if(!scriptJquery('.cd-hero-slider<?php echo "_".$identity ?>').find('li.selected').hasClass('cd-bg-video')){
			clearIntvalSesVideoSlideshow1<?php echo "_".$identity ?>=window.setTimeout(function(){finishVideoNext<?php echo "_".$identity ?>()}, 3000);
		}
});
<?php } ?>
<?php if($this->logo): ?>
		scriptJquery('#cd-logo<?php echo "_".$identity ?>').html(logoSesvideo<?php echo "_".$identity ?>);
	<?php endif; ?>
<?php if($this->autoplay){ ?>
	function finishVideoNext<?php echo "_".$identity ?>(){
		if(isOnRegister<?php echo "_".$identity ?>){
				IsfinishVideoNext<?php echo "_".$identity ?> = true;
				clearIntvalSesVideoSlideshow2<?php echo "_".$identity ?> = window.setTimeout(function(){finishVideoNext<?php echo "_".$identity ?>()}, 5000);
				return;
		}
		IsfinishVideoNext<?php echo "_".$identity ?> = false;
		var indexSelectedVal = scriptJquery('.cd-slider-nav<?php echo "_".$identity ?>').find('li.selected').index();
		var totalLi = scriptJquery('.cd-slider-nav<?php echo "_".$identity ?>').find('nav').find('ul').children('li');
		if(indexSelectedVal >= totalLi.length - 1 ){
			indexSelectedVal = 0;
		}else
			indexSelectedVal++;
		scriptJquery(totalLi).eq(indexSelectedVal).trigger('click');
	}
<?php }else{ ?>
function finishVideoNext<?php echo "_".$identity ?>(){
	return true;	
}
<?php } ?>
 var toggleUpdatesPulldown<?php echo "_".$identity ?> = function(event, element, user_id) {
    if( element.className=='updates_pulldown' ) {
      element.className= 'updates_pulldown_active';
      showNotifications<?php echo "_".$identity ?>();
    } else {
      element.className='updates_pulldown';
    }
  }

  var showNotifications<?php echo "_".$identity ?> = function() {
    if(scriptJquery("#pulldown_message").length && scriptJquery("#pulldown_message")[0].style.display == 'block')
      document.getElementById("pulldown_message").style.display = 'none';
    en4.activity.updateNotifications();
    scriptJquery.ajax({
      url: en4.core.baseUrl + 'activity/notifications/pulldown',
      data:{
        format : 'html',
        page : 1
      },
      method:'post',
      dataType: 'html',
      success: function (responseHTML) {
        if( responseHTML ) {
          // hide loading icon
          if(scriptJquery('#notifications_loading').length) 
            scriptJquery('#notifications_loading').css('display', 'none');

          if(event.target.id != 'remove_notification_update') {

            scriptJquery('#notifications_menu').html(responseHTML);
            scriptJquery('#notifications_menu').on('click', function(event) {
              
              event.preventDefault(); //Prevents the browser from following the link.
              
              var current_link = scriptJquery(event.target);
              var notification_li = current_link.parents('li');
              
              // if this is true, then the user clicked on the li element itself
              if( notification_li.attr('id') == 'core_menu_mini_menu_update' ) {
                notification_li = current_link;
              }

              var forward_link;
              if( current_link.attr('href') ) {
                forward_link = current_link.attr('href');
              }else if(current_link.hasClass("notification_subject_icon")){
                forward_link = current_link.parents("a").attr('href');
              } else{
                forward_link = current_link.find('a:last-child').attr('href');
                
              }
              if( notification_li.hasClass('notifications_unread')){
                notification_li.removeClass('notifications_unread');
                scriptJquery.ajax({
                  url: en4.core.baseUrl + 'activity/notifications/markread',
                  data: {
                    format     : 'json',
                    actionid : notification_li.val()
                  },
                  method:'post',
                  dataType: 'json',
                  success: function (response) {
                    window.location = forward_link;
                  },
                  error: function (err) {
                    console.log(err);
                  }
                });
              } else {
                window.location = forward_link;
              }
            });
          
          }
        } else {
          scriptJquery('#notifications_loading').html('<?php echo $this->string()->escapeJavascript($this->translate("You have no new updates."));?>');
          console.log(scriptJquery('#notifications_menu').length);
          if(scriptJquery('#notifications_menu').length == 1) {
            scriptJquery('#notifications_menu').html('<div class="notifications_loading" id="notifications_loading">You have no new updates.</div>');
            scriptJquery('#update_count').removeClass('minimenu_update_count_bubble_active').html('0');
          }
        }
      },
      error: function () {
         
      }
    });
  };
</script>
