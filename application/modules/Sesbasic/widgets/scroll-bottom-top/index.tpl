<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-28 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<a href="javascript:;" id="BackToTop" onclick="SESscrollTopAnimated(1000)" class="sesbasic_scrollup_button sesbasic_animation"><i class="fa fa-angle-up"></i></a>
<script>

	scriptJquery(window).scroll(function () {
		if (scriptJquery(this).scrollTop() > 175) {
		scriptJquery('#BackToTop').css({
			'visibility': 'visible'
		});

		scriptJquery("#BackToTop").fadeIn("slow", function () {});
		} else {
			scriptJquery("#BackToTop").fadeOut("slow", function () {});
		}
	});


	var stepTime = 20;
	var docBody = document.body;
	var focElem = document.documentElement;
	
	var scrollAnimationStep = function (initPos, stepAmount) {
		var newPos = initPos - stepAmount > 0 ? initPos - stepAmount : 0;
		docBody.scrollTop = focElem.scrollTop = newPos;
		newPos && setTimeout(function () {
				scrollAnimationStep(newPos, stepAmount);
		}, stepTime);
	}
	
	var SESscrollTopAnimated = function (speed) {
		var topOffset = docBody.scrollTop || focElem.scrollTop;
		var stepAmount = topOffset;
		speed && (stepAmount = (topOffset * stepTime)/speed);
		scrollAnimationStep(topOffset, stepAmount);
	}
</script>
