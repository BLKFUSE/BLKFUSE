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

<div class="sescontest_hiw sesbasic_bxs sesbasic_clearfix">
	<div class="_container">
  	<h3 class="sescontest_weltitle"><?php echo $this->translate("How It Works");?></h3>
    <div class="_cont">
    	<div class="cont_row sesbasic_clearfix r_aln">
      	<div class="cont_colm">
        	<h3><?php echo $this->translate("Creating Contests") ?></h3>
          <p><?php echo $this->translate("Creating contests with us is very easy. Follow 3 steps to get started and challenge your audience to participate in your contest.") ?></p>
          <ul>
          	<li><?php echo $this->translate("Select Category & Media Type (Photo, Video, Music or Text)") ?></li>
            <li><?php echo $this->translate("Choose the Dates & Rules") ?></li>
            <li><?php echo $this->translate("Fill Contest Basic Details") ?></li>
            <li><?php echo $this->translate("Your Contest is ready to be Shared with the World!") ?></li>
          </ul>
        </div>
        <div class="img_colm">
        	<img src="application/modules/Sescontest/externals/images/hiw-1.png" alt="" />
        </div>
      </div>
      <div class="cont_row sesbasic_clearfix l_aln">
      	<div class="img_colm">
        	<img src="application/modules/Sescontest/externals/images/hiw-2.png" alt="" />
        </div>
        <div class="cont_colm">
        	<h3><?php echo $this->translate("Participating in Contests") ?></h3>
          <p><?php echo $this->translate("Submit your photos, videos, music and text to various Contests, share with your friends, family and with people on our community to receive feedback and Win exciting prizes.") ?></p>
          <ul>
          	<li><?php echo $this->translate("Click on Join Now button") ?></li>
            <li><?php echo $this->translate("Submit your entry to Participate") ?></li>
            <li><?php echo $this->translate("Share your entry and get maximum votes") ?></li>
            <li><?php echo $this->translate("Win contests.") ?></li>
          </ul>
        </div>
      </div>
    	<div class="cont_row sesbasic_clearfix r_aln">
      	<div class="cont_colm">
        	<h3><?php echo $this->translate("Following Contests") ?></h3>
          <p><?php echo $this->translate("Follow your favorite Contests and Get all updates of the contests you followed.") ?></p>
        </div>
        <div class="img_colm">
        	<img src="application/modules/Sescontest/externals/images/hiw-3.png" alt="" />
        </div>
      </div>
      <div class="cont_row sesbasic_clearfix l_aln">
      	<div class="img_colm">
        	<img src="application/modules/Sescontest/externals/images/hiw-4.png" alt="" />
        </div>
        <div class="cont_colm">
        	<h3><?php echo $this->translate("Voting and Sharing") ?></h3>
          <ul>
          	<li><?php echo $this->translate("Use your voting power to Vote on deserving entries and make them win the contests.") ?></li>
            <li><?php echo $this->translate("Share entries inside or outside this community on other social networking sites to gather more and more votes.") ?></li>
          </ul>
        </div>
      </div>
    	<div class="cont_row sesbasic_clearfix r_aln">
      	<div class="cont_colm">
        	<h3><?php echo $this->translate("Judging") ?></h3>
          <p><?php echo $this->translate("Users and members on our community will use their voting power to vote the submitted entries and maximum votes will determine the Winner.") ?></p>
        </div>
        <div class="img_colm">
        	<img src="application/modules/Sescontest/externals/images/hiw-5.png" alt="" />
        </div>
      </div>
      <div class="cont_row sesbasic_clearfix l_aln">
      	<div class="img_colm">
        	<img src="application/modules/Sescontest/externals/images/hiw-6.png" alt="" />
        </div>
        <div class="cont_colm">
        	<h3><?php echo $this->translate("Winners") ?></h3>
          <p><?php echo $this->translate("When the winners are decided they will be announced here on the site. All participants will be notified when winners are announced. Winners will also receive congratulations message and email from the Contest Owners.") ?></p>
        </div>
      </div>
    </div>
  </div>
</div>