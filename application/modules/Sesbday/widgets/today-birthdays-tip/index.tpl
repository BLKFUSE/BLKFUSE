<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbday/externals/styles/styles.css'); ?>

<div class="sesbday_bday_tip sesbasic_bxs">
	<a href="<?php echo "sesbday/index/popup"; ?>" class="sessmoothbox">
  	<i></i>
    <div>
    <span><?php	
				
				$counter = 1;
				$countBirthday = engine_count($this->users['data']);
				$namestr = "";
				$seprator = " , ";
				if($countBirthday == 2)
					$seprator = " and ";
				foreach($this->users['data'] as $users)
				{
					$namestr .= $users->getTitle()." ".$seprator;
					if($counter == 2)
					{
						break;
					}
					$counter++;
				}
				
					if($namestr){
						$namestr =  trim($namestr,' ,');
						if($countBirthday == 2)
						$namestr =  substr($namestr,0,strlen-3);
						echo $namestr;
				?>
				</span>
				<?php
					}
					if($countBirthday > 2){ ?>
						<?php echo $this->translate(array('and %s other ', 'and %s others ', $countBirthday - 2), $this->locale()->toNumber($countBirthday - 2));?></span><?php echo $this->translate('birthday is today'); ?></span>
					<?php	
					}else {
						echo $this->translate('birthday is today');
					}
								
		?>
    </div>
  </a>
</div>
