<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: view.tpl  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<link rel="stylesheet" href="externals/font-awesome/css/all.min.css" />
<?php $baseURL = $this->layout()->staticBaseUrl; ?>
<div class="sestutorial_view_details_popup">
  <h3><?php echo $this->translate("View Details"); ?> </h3>
  <table>
    <tr>
      <td><?php echo $this->translate('Title') ?>:</td>
      <td><?php if(!is_null($this->item->title) && $this->item->title != '') {?>
        <a href="<?php echo $this->item->getHref(); ?>" target="_blank"><?php echo  $this->item->title ; ?></a>
        <?php
        } else { 
        echo "-";
        } ?>
      </td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Owner') ?>:</td>
      <td><?php echo  $this->item->getOwner(); ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Ratings') ?>:</td>
      <td>
        <span class="sestutorial_rating_star">
          <?php if($this->item->rating): ?>
          <div class="sestutorial_text_light sestutorial_rating_star">
            <?php if( $this->item->rating > 0 ): ?>
            <?php for( $x=1; $x<= $this->item->rating; $x++ ): ?>
            <span class="sestutorial_rating_star_small fa fa-star"></span>
            <?php endfor; ?>
            <?php if((round($this->item->rating) - $this->item->rating) > 0): ?>
            <span class="sestutorial_rating_star_small fa fa-star-half"></span>
            <?php endif; ?>
            <?php endif; ?>
          </div>
          <?php else: ?>
            <?php for( $x=1; $x<= 5; $x++ ): ?>
              <span class="sestutorial_rating_star_small fa fa-star star-disabled"></span>
            <?php endfor; ?>
          <?php endif; ?>
        </span>
      </td>
    </tr>
    <?php
      $helpfulCountforYes = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->helpfulCount($this->item->tutorial_id, 1);
      $helpfulCountforNo = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->helpfulCount($this->item->tutorial_id, 2);
      $totalHelpful = $helpfulCountforYes + $helpfulCountforNo;
      $final_value = 0;
      if($helpfulCountforYes && $totalHelpful)
        $percentageHelpful = ($helpfulCountforYes / ($totalHelpful))*100;
      if($percentageHelpful > 0) {
        $final_value = round($percentageHelpful);
      } else {
        $final_value = 0;
      }
    ?>
    <tr>
      <td><?php echo $this->translate('Helpful') ?>:</td>
      <td><?php echo $final_value.'%' ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Comments') ?>:</td>
      <td><?php echo $this->item->comment_count ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Likes') ?>:</td>
      <td><?php echo $this->item->like_count ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Views') ?>:</td>
      <td><?php echo $this->locale()->toNumber($this->item->view_count) ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Date') ?>:</td>
      <td><?php echo $this->item->creation_date; ;?></td>
    </tr>
  </table>
  <br />
  <button onclick='javascript:parent.Smoothbox.close()'>
    <?php echo $this->translate("Close") ?>
  </button>
</div>
