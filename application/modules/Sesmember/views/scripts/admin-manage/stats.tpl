<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: stats.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<div class="global_form_popup admin_member_stats sesmember_member_stats">
  <h3>Member Statistics</h3>
  <ul>
    <li class="sesmember_member_stats_photo">
      <?php echo $this->itemPhoto($this->user, 'thumb.profile', $this->user->getTitle()) ?>
    </li>
    <?php if( !empty($this->memberType) ): ?>
    <li>
      <?php echo $this->translate('Member Type:') ?>
      <?php // @todo implement link ?>
      <span><?php echo $this->translate($this->memberType) ?></span>
    </li>
    <?php endif; ?>
    <?php if( !empty($this->networks) && engine_count($this->networks) > 0 ): ?>
    <li>
      <?php echo $this->translate('Networks:') ?>
      <span><?php echo $this->fluentList($this->networks) ?></span>
    </li>
    <?php endif; ?>
    <li>
      <?php echo $this->translate('Profile Views:') ?>
      <span><?php echo $this->translate(array('%s view', '%s views', $this->user->view_count),$this->locale()->toNumber($this->user->view_count)) ?></span>
    </li>
    <li>
      <?php echo $this->translate('Friends:') ?>
      <span><?php echo $this->translate(array('%s friend', '%s friends', $this->user->membership()->getMemberCount($this->user)),$this->locale()->toNumber($this->user->membership()->getMemberCount($this->user))) ?></span>
    </li>
    <li>
      <?php echo $this->translate('Last Update:'); ?>
      <span><?php echo $this->timestamp($this->user->modified_date) ?></span>
    </li>
    <li>
      <?php echo $this->translate('Joined:') ?>
      <span><?php echo $this->timestamp($this->user->creation_date) ?></span>
    </li>
    <li>
      <?php echo $this->translate('Joined IP:') ?>
      <span>
        <?php if( _ENGINE_ADMIN_NEUTER ): ?>
          <?php echo $this->translate('(hidden)') ?>
        <?php else: ?>
          <?php
            if (isset($this->user->creation_ip)) {
              $ipObj = new Engine_IP($this->user->creation_ip);
              echo $ipObj->toString();
            }
          ?>
        <?php endif ?>
      </span>
    </li>    
    <li>
      <?php echo $this->translate('Last Login:') ?>
      <?php if ($this->user->lastlogin_date): ?>
      <span><?php echo $this->timestamp($this->user->lastlogin_date) ?></span>
      <?php else: ?>
      <span><?php echo $this->translate('Never') ?></span>
      <?php endif ?>
    </li>
    <li>
      <?php echo $this->translate('Last Login IP:') ?>
      <span>
        <?php if( _ENGINE_ADMIN_NEUTER ): ?>
          <?php echo $this->translate('(hidden)') ?>
        <?php elseif( $this->user->lastlogin_ip ): ?>
          <?php
            $ipObj = new Engine_IP($this->user->lastlogin_ip);
            echo $ipObj->toString();
          ?>
        <?php else: ?>
          <?php echo $this->translate('None') ?>
        <?php endif ?>
      </span>
    </li>    
  </ul>
  <br/>
  <button type="submit" onclick="parent.Smoothbox.close();return false;" name="close_button" value="Close">Close</button>
</div>
