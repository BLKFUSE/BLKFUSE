<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: statistics.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/dismiss_message.tpl';?>

<div class='settings'>
  <form class="global_form">
    <div>
      <h3><?php echo $this->translate("News Statistics") ?> </h3>
      <p class="description">
        <?php echo $this->translate("Below are some valuable statistics for the News created on this site:"); ?>
      </p>
      <table class='admin_table' style="width: 50%;">
        <tbody>
          <tr>
            <td><strong class="bold"><?php echo "Total News:" ?><strong></td>
            <td><?php echo $this->totalnews; ?></td>
          </tr>
           <tr>
            <td><strong class="bold"><?php echo "Total Approved News:" ?><strong></td>
            <td><?php echo $this->totalapprovednews; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Featured News:" ?><strong></td>
            <td><?php echo $this->totalnewsfeatured; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Sponsored News:" ?><strong></td>
            <td><?php echo $this->totalnewsponsored; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total News Albums:" ?><strong></td>
            <td><?php echo $this->totalalbums; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total News Photos:" ?><strong></td>
            <td><?php echo $this->totalphotos; ?></td>
          </tr>
          <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesvideo')):?>
						<tr>
							<td><strong class="bold"><?php echo "Total News Videos:" ?><strong></td>
							<td><?php echo $this->totalvideos; ?></td>
						</tr>
          <?php endif;?>
          <tr>
            <td><strong class="bold"><?php echo "Total Reviews:" ?><strong></td>
            <td><?php echo $this->totalnewsrated; ?></td>
          </tr>  
          <tr>
            <td><strong class="bold"><?php echo "Total Verified News:" ?><strong></td>
            <td><?php echo $this->totalnewsverified; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Favourite News:" ?><strong></td>
            <td><?php echo $this->totalnewsfavourite; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Comments:" ?><strong></td>
            <td><?php echo $this->totalnewscomments; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Views:" ?><strong></td>
            <td><?php echo $this->totalnewsviews; ?></td>
          </tr>
          <tr>
            <td><strong class="bold"><?php echo "Total Likes:" ?><strong></td>
            <td><?php echo $this->totalnewslikes; ?></td>
          </tr>        
        </tbody>
      </table>
    </div>
  </form>
</div>
