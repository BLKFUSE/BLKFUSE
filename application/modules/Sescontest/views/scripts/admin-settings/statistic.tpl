<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: statstics.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>

<div class='settings'>
  <form class="global_form">
    <div>
      <h3><?php echo $this->translate("Contests Statistics") ?> </h3>
      <p class="description">
        <?php echo $this->translate("Below are some valuable statistics for the Contests created on this site:"); ?>
      </p>
      <table class='admin_table' style="width: 50%;">
        <tbody>
          <tr>
            <td><strong class="bold"><?php echo "Total Contests:" ?></strong></td>
            <td><?php echo $this->totalcontest ? $this->totalcontest : 0; ?></td>
          </tr>
           <tr>
            <td><strong class="bold"><?php echo "Total Approved Contests:" ?></strong></td>
            <td><?php echo $this->totalapprovedcontest ? $this->totalapprovedcontest : 0; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Featured Contests:" ?></strong></td>
            <td><?php echo $this->totalcontestfeatured ? $this->totalcontestfeatured : 0; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Sponsored Contests:" ?></strong></td>
            <td><?php echo $this->totalContestsponsored ? $this->totalContestsponsored : 0; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Hot Contests:" ?></strong></td>
            <td><?php echo $this->totalContesthot ? $this->totalContesthot : 0; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Verified Contests:" ?></strong></td>
            <td><?php echo $this->totalcontestverified ? $this->totalcontestverified : 0; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Favourite Contests:" ?></strong></td>
            <td><?php echo $this->totalcontestfavourite ? $this->totalcontestfavourite : 0; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Comments:" ?></strong></td>
            <td><?php echo $this->totalcontestcomments ? $this->totalcontestcomments : 0; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Views:" ?></strong></td>
            <td><?php echo $this->totalcontestviews ? $this->totalcontestviews : 0; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Likes:" ?></strong></td>
            <td><?php echo $this->totalcontestlikes ? $this->totalcontestlikes : 0; ?></td>
          </tr> 
          <tr>
            <td><strong class="bold"><?php echo "Total Followers:" ?></strong></td>
            <td><?php echo $this->totalcontestfollowers ? $this->totalcontestfollowers : 0; ?></td>
          </tr> 
           <tr>
            <td><strong class="bold"><?php echo "Total Entries:" ?></strong></td>
            <td><?php echo $this->totalcontestentries ? $this->totalcontestentries : 0; ?></td>
          </tr> 
        </tbody>
      </table>
    </div>
  </form>
</div>