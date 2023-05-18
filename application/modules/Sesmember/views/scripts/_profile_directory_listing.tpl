<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _profile_directory_listing.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<ul class="iwr_members_listing">
  <?php if(engine_count($members) > 0) { ?>
  <?php foreach($members as $member) { ?>
    <li class="iwr_members_list_item">
      <article class="sesbasic_clearfix">
        <div class="_thumb">
        	<?php echo $this->htmlLink($member->getHref(), $this->itemPhoto($member, 'thumb.icon', $member->getTitle())) ?>
        </div>
        <div class="_cont"><div class="_title"><a href="<?php echo $member->getHref(); ?>"><?php echo $member->getTitle(); ?></a></div></div>
      </article>
    </li>
  <?php } ?>
  <?php } else { ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('There are no members.'); ?>
      </span>
    </div>
  <?php } ?> 
</ul>
