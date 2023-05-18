<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: upcoming-birthday.tpl  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?> 
<?php if(engine_count($this->upComingBirthday['data'])){ ?>
 <section class="sesbday_upcbday_section">
  	<div class="sesbday_listing_head"><?php   echo  $this->translate("Upcoming Birthdays."); ?></div>
    <div class="sesbday_listing_content sesbday_listing_row">
    	<ul>
		<?php
			$currentUser = null;
			$previousUser = null;
			foreach($this->upComingBirthday['data'] as $comingBirthdayData) {
			$currentUser = date('d',strtotime($comingBirthdayData['value']));
		?>
		
		<?php if($previousUser != $currentUser)	{ ?>
			<li class="_date sesbasic_text_light"><?php echo date('l,F d,'.date('Y'),strtotime($comingBirthdayData['value'])); ?></li>
		<?php
		}
		
		$previousUser = $currentUser;
		?>	
		
      	<li class="sesbday_listing_item sesbasic_clearfix">
        	<div class="_thumb">
          	<a href="<?php echo $comingBirthdayData->getHref(); ?>"><span class="bg_item_photo" style="background-image:url(<?php echo $comingBirthdayData->getPhotoUrl(); ?>);?>"></span></a>
          </div>
          <div class="_cont">
          	<div class="_name"><a href="<?php echo $comingBirthdayData->getHref(); ?>"><?php echo $comingBirthdayData->getTitle(); ?></a></div>
				<div class="_date sesbasic_text_light"><?php   echo  $this->translate("Turning %s years old.",date('Y')-date('Y',strtotime($comingBirthdayData['value']))); ?></div>
          </div>	
        </li>
		<?php }?>
      
      </ul>
    </div>
  </section>
  <?php } ?>
