<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: entry-view.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $contest = Engine_Api::_()->getItem('contest',$this->item->contest_id);?>
<?php $baseURL = $this->layout()->staticBaseUrl; ?>
<div class="sesbasic_view_stats_popup">
  <h3><?php echo $this->translate("View Details"); ?> </h3>
  <table>
    <tr>
      <?php $path = $this->item->getPhotoUrl();?>
      <td colspan="2"><a href="<?php echo $this->item->getHref(); ?>" target="_blank"><img src="<?php echo $path; ?>" style="height:75px; width:75px;"/></a></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Title') ?>:</td>
      <td><a href="<?php echo $this->item->getHref(); ?>" target="_blank"><?php echo  $this->item->title ; ?></a></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Contest') ?>:</td>
      <td><a href="<?php echo $contest->getHref(); ?>" target="_blank"><?php echo  $contest->title ; ?></a></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Owner') ?>:</td>
      <td><?php echo  $this->item->getOwner(); ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Votes') ?>:</td>
      <td><?php echo  $this->item->vote_count ; ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Winner Rank') ?>:</td>
      <td><?php echo  $this->item->rank ; ?></td>
    </tr>
  <?php if(strtotime($this->item->enddate) < strtotime(date('Y-m-d')) && $this->item->offtheday == 1){ 
                    Engine_Api::_()->getDbtable('participants', 'sescontest')->update(array(
                        'offtheday' => 0,'startdate' =>'',
                        'enddate' =>'',
                      ), array(
                        "participant_id = ?" => $this->item->participant_id,
                      ));
                      $itemofftheday = 0;
               }else
                $itemofftheday = $this->item->offtheday; ?>
    <tr>
      <td><?php echo $this->translate('Of the Day') ?>:</td>
      <td><?php  if($itemofftheday == 1){ ?>
        <img src="<?php echo $baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png'; ?>"/> <?php }else{ ?> 
        <img src="<?php echo $baseURL . 'application/modules/Sesbasic/externals/images/icons/error.png'; ?>" /> <?php } ?>
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