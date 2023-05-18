<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php 
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/styles/lp-two.css');
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesdating/externals/styles/owl.carousel.min.css');
?>
<link href="//fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
<div class="sesdating_lp_two_main">
  <div class="sesdating_lp_main_inner">
    <div class="sesdating_lp_main sesbasic_bxs clearfix">
      <div class="sesdating_lp_two_header">
        <div class="sesdating_lp_two_header_inner">
         <?php if($this->show_logo):?>
         <div class="sesdating_lp_logo">
            <?php echo $this->content()->renderWidget("sesdating.menu-logo")?>
          </div>
          <?php endif; ?>
         <div class="sesdating_lp_main_menu">
           <?php if($this->show_mini):?>
           <?php echo $this->content()->renderWidget("sesdating.menu-mini")?>
           <?php endif; ?>
           <?php if($this->show_menu):?>
           <?php echo $this->content()->renderWidget("sesdating.menu-main")?>
           <?php endif; ?>
         </div>
        </div>
       </div>
      <div class="sesdating_cont_mid">
        <div class="sesdating_lp_two_form">
         <div class="sesdating_login">
          <h4><?php echo $this->translate("Serious dating with Sweet date.Your perfect match is just a click away."); ?></h4>
          <div class="sesdating_home_search clearfix">
            <div class="form-group">
              <label><?php echo $this->translate("Name"); ?></label>
              <input type="text" name="displayname" id="displayname" value="">
            </div>
            <div class="form-group">
             <label><?php echo $this->translate("Gender"); ?></label>
             <select name="gender" id="gender" alias="gender">
                <option value=""></option>
                <option value="2"><?php echo $this->translate("Male"); ?></option>
                <option value="3"><?php echo $this->translate("Female"); ?></option>
             </select>
          </div>
          <?php for($i=13;$i<=100;$i++) { ?>
            <?php $option .= '<option value="'.$i.'">'.$i.'</option>'; ?>
          <?php } ?>
          <div class="form-group">
             <label><?php echo $this->translate("Age"); ?></label>
             <select name="min" id="min" alias="min">
                <option value=""></option>
                <?php echo $option; ?>
             </select>
             <span><?php echo $this->translate("to"); ?></span>
             <select name="max" id="max" alias="max">
                <option value=""></option>
                <?php echo $option; ?>
             </select>
          </div>
          <div class="form-group">
            <button onclick="searchMembers();" type="submit"><?php echo $this->translate("Next"); ?></button>
          </div>
        </div>
        </div>
        <?php if(engine_count($this->newest_members) > 0) { ?>
          <div class="sesdating_form_footer sesbasic_bg">
            <h4 class="sesbasic_text_light"><?php echo $this->translate("Latest Registered Members"); ?></h4>
            <div class="form_members_carousel">
              <div class="members_carousel owl-carousel owl-theme">
                <?php foreach($this->newest_members as $newest_member) { ?>
                  <div class="item">
                      <a href="<?php echo $newest_member->getHref(); ?>"><?php echo $this->itemPhoto($newest_member, 'thumb.profile'); ?></a>
                  </div>
                <?php } ?>
              </div>
            </div>
          </div>
        <?php } ?>      
      </div>
      <div class="sesdating_lp_right">
        <div class="sesdating_lp_two_cont">
          <?php echo $this->translate("Are you waiting for dating?"); ?>
        </div>
        <div class="sesdating_lp_two_img">
          <img src="application/modules/Sesdating/externals/images/couple-img.png" alt="" />
        </div>
      </div>
     </div>
    </div>
  </div>
</div>
<script type='text/javascript' src="application/modules/Sesdating/externals/scripts/jquery.js"></script>
<script type='text/javascript' src='application/modules/Sesdating/externals/scripts/owl.carousel.js'></script>
<script type='text/javascript'>
scriptJquery(document).ready(function(){
	  scriptJquery(window).load(function () {
        scriptJquery("body").removeClass("sesdating_landing_page");
});
});
</script>
<script type='text/javascript'>
sesdtJqueryObject('.members_carousel').owlCarousel({
		loop:true,
		margin:20,
		nav:true,
		dots:false,
		items:3,
		responsive:{
				0:{
						items:3
				},
				600:{
						items:5
				},
        768:{
						items:6
				},
				1000:{
						items:3
				}
		}
});
	sesdtJqueryObject(".owl-prev").html('<i class="fa fa-arrow-circle-left"></i>');
	sesdtJqueryObject(".owl-next").html('<i class="fa fa-arrow-circle-right"></i>');
</script>
<script>
  function searchMembers() {
    var displayname = $('displayname').value;
    var gender = $('gender').value;
    var min = $('min').value;
    var max = $('max').value;
    window.location = "members?displayname="+displayname+"&1_1_5_alias_gender="+gender+"&1_1_6_alias_birthdate%5Bmin%5D="+min+"&1_1_6_alias_birthdate%5Bmax%5D="+max;
  }
</script>
