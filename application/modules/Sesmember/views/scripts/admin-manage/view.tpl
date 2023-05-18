<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: view.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $baseURL = $this->layout()->staticBaseUrl; ?>
<div class="sesbasic_view_stats_popup">
  <h3>Statics of <?php echo $this->item->title;  ?> </h3>
  <table>
    <tr>
      <td><?php echo $this->translate('Title') ?>:</td>
      <td><?php if(!is_null($this->item->title) && $this->item->title != '') {
        echo  $this->item->title ;
        } else { 
        echo "-";
        } ?>
      </td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Pros') ?>:</td>
      <td><?php if(!is_null($this->item->pros) && $this->item->pros != '') {
        echo  $this->item->pros ;
        } else { 
        echo "-";
        } ?>
      </td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Cons') ?>:</td>
      <td><?php if(!is_null($this->item->cons) && $this->item->cons != '') {
        echo  $this->item->cons ;
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
      <td>Rating:</td>
      <td class="sesmember_view_details_rating">
        <div class="sesbasic_rating_star">
          <?php $ratingCount = $this->item->rating;?>
          <?php for($i=0; $i<5; $i++){?>
            <?php if($i < $ratingCount):?>
              <span id="" class="fa fa-star"></span>
            <?php else:?>
              <span id="" class="fa fa fa-star-o star-disable"></span>
            <?php endif;?>
          <?php }?>
        </div>
        <?php $reviewParameters = Engine_Api::_()->getDbtable('parametervalues', 'sesmember')->getParameters(array('content_id'=>$this->item->getIdentity(),'user_id'=>$this->item->owner_id)); ?>
        <?php if(engine_count($reviewParameters)>0){ ?>
            <?php foreach($reviewParameters as $reviewP){ ?>
            <div class="sesbasic_clearfix">
              <div class="sesmember_rating_parameter_label"><?php echo $reviewP['title']; ?></div>
              <div class="sesbasic_rating_parameter sesbasic_rating_parameter_small">
              <?php $ratingCount = $reviewP['rating'];?>
              <?php for($i=0; $i<5; $i++){?>
                <?php if($i < $ratingCount):?>
                  <span id="" class="sesbasic-rating-parameter-unit"></span>
                <?php else:?>
                  <span id="" class="sesbasic-rating-parameter-unit sesbasic-rating-parameter-unit-disable"></span>
                <?php endif;?>
              <?php }?>
              </div>
            </div>
          <?php } ?>
        <?php } ?>
    	</td>
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
