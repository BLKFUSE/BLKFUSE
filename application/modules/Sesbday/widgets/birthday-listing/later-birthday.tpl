<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: later-birthday.tpl  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbday/externals/styles/styles.css'); ?>


<div class="sesbday_listing sesbasic_bxs" id="sesbday_listing">
	<section class="sesbday_month_section">
  	<div class="sesbday_listing_head">
			<?php	
				echo $this->translate("Later in %s",date('F', strtotime($this->laterBirthday['date'])));
			?>
		</span>
		<span>
		<?php	
				$counter = 1;
				$countBirthday = engine_count($this->laterBirthday['data']);
				$namestr = "";
				$seprator = ", ";
				if($countBirthday == 2)
					$seprator = " and ";
				foreach($this->laterBirthday['data'] as $birthdayData)
				{
					$namestr .= $birthdayData->getTitle().$seprator;
					if($counter == 2)
					{
						break;
					}
					$counter++;
				}
				
				if($namestr){
						
						$namestr =  trim($namestr,', ');
						if($countBirthday == 2)
						$namestr =  substr($namestr,0,strlen-3);
						echo $namestr;
					}
					if($countBirthday > 2){ ?>
						<?php echo $this->translate(array('and %s other ', 'and %s others ', $countBirthday - 2), $this->locale()->toNumber($countBirthday - 2)); ?>
					<?php	
					}
							
		?>
		
		</span>
	</div>
	<?php if(engine_count($this->laterBirthday['data'])) { ?>
    <div class="sesbday_listing_content sesbday_listing_thumbs">
		<ul id="birthdays">
		<?php foreach($this->laterBirthday['data'] as $birthdayData) { 
			 
			if($birthdayData->getIdentity())
			{
		?>
			<li class="_item sesbasic_clearfix">
				<a href="<?php echo $birthdayData->getHref(); ?>"><span class="bg_item_photo" style="background-image:url(<?php echo $birthdayData->getPhotoUrl(); ?>); ?>"></span></a>
				 <p><?php echo $birthdayData->getTitle()." (".(int)date('m',strtotime($birthdayData['value']))."/".(int)date('d',strtotime($birthdayData['value'])).")";?></p>
			</li>
		<?php
			}
			} 
		?>
		</ul>
    </div>
	<?php } ?>
	
  </section>

</div>
