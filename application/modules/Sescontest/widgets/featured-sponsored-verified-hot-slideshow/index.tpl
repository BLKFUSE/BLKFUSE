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
<?php
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
?>
<div class="sescontest_hero_slideshow_container sesbasic_bxs <?php if($this->params['isfullwidth']){ ?> _isfullwidth<?php } ?>" style="height:<?php echo is_numeric($this->params['height']) ? $this->params['height'].'px' : $this->params['height']; ?>;">
  <section>
    <div class="sescontest_hero_slideshow_inner sesbasic_clearfix sescontest_hero_slideshow sescontest_hero_slideshow_inner_<?php echo $this->identity;?>">
      <?php foreach($this->contests as $contest):?>
        <?php $participate = Engine_Api::_()->getDbTable('participants', 'sescontest')->hasParticipate($this->viewer()->getIdentity(), $contest->contest_id);?>
        <?php $viewer = Engine_Api::_()->user()->getViewer();?>
        <?php if($viewer->getIdentity()):?>
          <?php $oldTz = date_default_timezone_get();?>
        <?php endif;?>
        <?php $endtime = strtotime($contest->endtime);?>
        <?php if($viewer->getIdentity()):?>
          <?php date_default_timezone_set($viewer->timezone);?>
        <?php endif;?>
        <?php $endtime = strtotime(date('Y-m-d H:i:s',$endtime));?>
        <?php $currentTime = time();?>
        <?php $diff=($endtime-$currentTime);?>
        <?php $temp = $diff/86400;?>
        <?php $dd = floor($temp); $temp = 24*($temp-$dd);?>
        <?php $hh = floor($temp); $temp = 60*($temp-$hh);?>
        <?php $mm = floor($temp); $temp = 60*($temp-$mm); ?>
        <?php $ss = floor($temp);?>
        <?php if($viewer->getIdentity()):?>
          <?php date_default_timezone_set($oldTz);?>
        <?php endif;?>
<?php $currentTime = strtotime(date('Y-m-d H:i:s'));?>
      <div class="sescontest_hero_slideshow_item item">
        <div class="_img" style="height:<?php echo is_numeric($this->params['height']) ? $this->params['height'].'px' : $this->params['height']; ?>;"> <span style="background-image:url(<?php echo $contest->getPhotoUrl(); ?>);"></span> </div>
        <div class="_overlay"></div>
        <div class="_cont">
          <div class="_continner">
            <article>
              <?php if(isset($this->titleActive)):?>
              <?php if(strlen($contest->getTitle()) > $this->params['title_truncation']):?>
              <?php $title = mb_substr($contest->getTitle(),0,$this->params['title_truncation']).'...';?>
              <?php else: ?>
              <?php $title = $contest->getTitle();?>
              <?php endif; ?>
              <h2><a href="<?php echo $contest->getHref();?>"><?php echo $title;?></a></h2>
              <?php endif;?>
              <?php if(isset($this->descriptionActive)):?>
              <p class="_des"><?php echo $this->string()->truncate($this->string()->stripTags($contest->description), $this->params['description_truncation']) ?></p>
              <?php endif;?>
              <?php if(strtotime($contest->endtime) > time()):?>
              <?php if($dd > 0):?>
              <div  class="_time sesbasic_clearfix"> <span class="_count"><?php echo $dd ?></span> <span class="_text"><?php echo $this->translate('Days left');?></span> </div>
              <?php else:?>
              <div class="_countdown _time"> <span class="_count">
                <div class="finish-message" style="display: none;"><?php echo $this->translate("Contest has Ended.");?></div>
                <div class="countdown-contest">
                  <div style="display: none;"><?php echo str_replace('timestamp','timestamp sescontest-timestamp-update ',$this->timestamp($contest->endtime)); ?></div>
                  <?php if($dd > 0):?>
                  <div>
                    <p> <span class='day'><?php echo $dd;?></span><span><?php echo $this->translate("d")?></span> </p>
                  </div>
                  <?php endif;?>
                  <div>
                    <p> <span class='hour'><?php echo $hh;?></span><span><?php echo $this->translate("h")?></span> </p>
                  </div>
                  <div>
                    <p> <span class='minute'><?php echo $mm;?></span><span><?php echo $this->translate("m")?></span> </p>
                  </div>
                  <div>
                    <p> <span class='second'><?php echo $ss;?></span><span><?php echo $this->translate("s")?></span> </p>
                  </div>
                </div>
                </span> <span class="_text"><?php echo $this->translate('Time left');?></span> </div>
              <?php endif;?>
              <?php endif;?>
              <?php if(isset($this->joinButtonActive) && isset($participate['can_join']) && isset($participate['show_button'])):?>
                <div class="clear _btn"> <a href="<?php echo $contest->getHref();?>" class="sesbasic_animation">
                   <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjoinfees') && $contest->entry_fees > 0 && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestjoinfees.allow.entryfees', 1)){ ?>
                      <?php echo $this->translate('Join Contest').'<br>'.Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($contest->entry_fees);;?></a>
                    <?php }else{ ?>
                     <?php echo $this->translate('Join Contest');?></a>
                    <?php } ?>
                </a> </div>
              <?php endif;?>
            </article>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </section>
</div>
<script type="text/javascript">
sesowlJqueryObject('.sescontest_hero_slideshow_inner_<?php echo $this->identity;?>').owlCarousel({
	nav : true,
	loop:true,
	items:1,
	autoplay:<?php echo $this->params['autoplay'] ?>,
  autoplayTimeout:<?php echo $this->params['speed'] ?>,
})
sesowlJqueryObject(".owl-prev").html('<i class="fa fa-angle-left"></i>');
sesowlJqueryObject(".owl-next").html('<i class="fa fa-angle-right"></i>');
	<?php if($this->params['isfullwidth']){ ?>
		scriptJquery(document).ready(function(){
			var htmlElement = scriptJquery("body");
			htmlElement.addClass('sescontest_slideshow_full');
		});
	<?php } ?>
</script>
<style type="text/css">
<?php if($this->params['navigation'] == 'nextprev'){?>
	.sescontest_hero_slideshow_inner_<?php echo $this->identity;?> .owl-dots{
		display:none;
	}
	.sescontest_hero_slideshow_inner_<?php echo $this->identity;?> .owl-nav > div{
		display:block !important;
	}
<?php } else{ ?>
	.sescontest_hero_slideshow_inner_<?php echo $this->identity;?> .owl-nav{
		display:none;
	}
<?php } ?>

</style>
