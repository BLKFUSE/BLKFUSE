<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: outerurl.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<style>
#global_content_simple{
	height:100% !important;
	width:100% !important;
}
.fb-xfbml-parse-ignore{display:none;}
.twitter-video-rendered{max-width:100% !important;margin:0px !important;}
blockquote.twitter-video{display:none;}
.paddingBtm{padding-bottom:0px !important;}
</style>
<?php if($this->video->type == 106){ ?>
<script type="application/javascript">
scriptJquery(window).load(function () {
	if(!parent.scriptJquery('#ses_media_lightbox_container_video').length){
		var height = parent.scriptJquery('#videoFrame<?php echo $this->video->getIdentity(); ?>').attr('height');    
    var el = parent.scriptJquery('#videoFrame<?php echo $this->video->getIdentity(); ?>');
    var parent123 = el.parent();
    var parentSize = parent123.height();    
		var elem = scriptJquery('#twitter-widget-0').find('div').first();
		elem.css('height',parentSize+'px');
		elem.addClass('paddingBtm');
    function doResizeFrameTwitter(){
        if(!parent.scriptJquery('#ses_media_lightbox_container_video').length){
          var height = parent.scriptJquery('#videoFrame<?php echo $this->video->getIdentity(); ?>').attr('height');    
          var el = parent.scriptJquery('#videoFrame<?php echo $this->video->getIdentity(); ?>');
          var parent123 = el.parent();
          var parentSize = parent123.height();    
          var elem = scriptJquery('#twitter-widget-0').find('div').first();
          elem.css('height',parentSize+'px');
          elem.addClass('paddingBtm');
        }
    }
    scriptJquery(window).on("resize", doResizeFrameTwitter);
	}else{
		if(!parent.scriptJquery('.sesvideo_view_embed').length)
			var totalElem = parent.scriptJquery('#sesvideo_lightbox_content').find('iframe');
		else
			var totalElem = parent.scriptJquery('.sesvideo_view_embed').find('iframe');
		var height = totalElem.height();
		var totalPla = scriptJquery('#twitter-widget-0');
		if(totalElem.length > 0){
				var elem = totalPla.find('div').first();
				elem.css('height',height+'px');
				elem.addClass('paddingBtm');
		}
	}	
});
</script>
<?php } ?>
<?php if($this->video->type == 105){ ?>
<script type="application/javascript">
scriptJquery(window).ready(function () {
var totalElem = parent.scriptJquery('#sesbasic_lightbox_content').find('iframe');
var height = totalElem.height();
scriptJquery('#global_content_simple').find('.fb-video').attr('data-height',height);
});
</script>
<?php } ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<?php echo $this->videoEmbedded ; ?>
