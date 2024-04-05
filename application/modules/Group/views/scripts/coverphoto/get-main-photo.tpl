<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: get-main-photo.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Alex
 */
?>
<div class="profile_cover_head_inner">
  <div class="profile_main_photo_wrapper">
    <div class="profile_main_photo b_dark">
      <div class="item_photo">
        <?php if (empty($this->uploadDefaultCover)): ?>
          <div class="main_thumb_photo">
            <?php if($this->group->getPhotoUrl('thumb.profile')) : ?>
              <span style="background-image:url('<?php echo $this->group->getPhotoUrl('thumb.profile');?>'); text-align:left;" id="group_profile_photo"></span>
            <?php else : ?>
              <span class="bg_item_photo bg_thumb_profile bg_item_photo_group bg_item_nophoto" id="group_profile_photo"></span>
            <?php endif;?>
          </div>
        <?php else: ?>
          <div class="main_thumb_photo">
            <span class="bg_item_photo bg_thumb_profile bg_item_photo_group bg_item_nophoto" id="group_profile_photo"></span>
          </div>
        <?php endif; ?>
        <?php if (!empty($this->can_edit) && empty($this->uploadDefaultCover)) : ?>
          <div id="mainphoto_options" class="profile_cover_options
            <?php if (!empty($this->uploadDefaultCover)) : ?> profile_main_photo_options is_hidden
            <?php else: ?> profile_main_photo_options<?php endif; ?>">
            <ul class="edit-button">
              <li>
                <?php if (!empty($this->group->photo_id)) : ?>
                  <span class="profile_cover_btn">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                  </span>
                <?php else: ?>
                  <span class="profile_cover_btn">
                    <i class="fa fa-camera" aria-hidden="true"></i>
                  </span>
                <?php endif; ?>
                <ul class="profile_options_pulldown">
                  <li>
                    <a href='<?php echo $this->url(array(
                      'action' => 'upload-cover-photo',
                      'group_id' => $this->group->group_id,
                      'photoType' => 'profile'), 'group_coverphoto', true); ?>' class="profile_cover_icon_photo_upload smoothbox">
                      <?php echo $this->translate('Upload Photo'); ?>
                    </a>
                  </li>
                  <li>
                    <?php echo $this->htmlLink(
                      $this->url(array(
                        'action' => 'choose-from-albums',
                        'group_id' => $this->group->group_id,
                        'recent' => 1,
                        'photoType' => 'profile'
                      ), 'group_coverphoto', true),
                      $this->translate('Choose from Albums'),
                      array(' class' => 'profile_cover_icon_photo_view smoothbox')); ?>
                  </li>
                  <?php if (!empty($this->group->photo_id)) : ?>
                    <li>
                      <?php echo $this->htmlLink(
                        array('route' => 'group_coverphoto', 'action' => 'remove-cover-photo', 'group_id' => $this->group->group_id, 'photoType' => 'profile'),
                        $this->translate('Remove'),
                        array(' class' => 'smoothbox profile_cover_icon_photo_delete')); ?>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            </ul>
          </div>
        <?php endif; ?>        
      </div>
    </div>
  </div>
  <?php  $subject = Engine_Api::_()->core()->getSubject(); ?>
  <?php if (empty($this->uploadDefaultCover)): ?>
    <div class="cover_photo_profile_information">
      <div class="cover_photo_profile_status">
        <?php if($this->subject()) { ?>
          <h2>
            <?php echo $this->subject()->getTitle() ?>
          </h2>
        <?php } ?>
        <p class="cover_photo_stats">
          <span>
          <?php echo $this->translate(array('%s Member Joined', '%s Members Joined', $subject->member_count), $this->locale()->toNumber($subject->member_count)); ?></span> 
          </span> 
        </p>          
      </div>
      <?php if($this->viewer()->getIdentity()) { ?>
      <div class="coverphoto_navigation">
        <ul class="coverphoto_navigation_list">
          <?php
            $menu = array();
            $viewer = Engine_Api::_()->user()->getViewer();
           
            $allowJoinGroup = true;
            if( !$viewer->getIdentity() )
            {
              $allowJoinGroup = false;
            }
        
            $row = $subject->membership()->getRow($viewer);
        
            // Not yet associated at all
            if( null === $row )
            {
              if( $subject->membership()->isResourceApprovalRequired() ) {
                $menu[] =  array(
                  'label' => 'Request Membership',
                  'class' => 'smoothbox icon_group_join',
                  'route' => 'group_extended',
                  'params' => array(
                    'controller' => 'member',
                    'action' => 'request',
                    'group_id' => $subject->getIdentity(),
                  ),
                );
              } else {
                $menu[] =  array(
                  'label' => 'Join Group',
                  'class' => 'smoothbox icon_group_join',
                  'route' => 'group_extended',
                  'params' => array(
                    'controller' => 'member',
                    'action' => 'join',
                    'group_id' => $subject->getIdentity()
                  ),
                );
              }
            }
        
            // Full member
            // @todo consider owner
            else if( $row->active )
            {
              if( !$subject->isOwner($viewer) ) {
                $menu[] =  array(
                  'label' => 'Leave Group',
                  'class' => 'smoothbox icon_group_leave',
                  'route' => 'group_extended',
                  'params' => array(
                    'controller' => 'member',
                    'action' => 'leave',
                    'group_id' => $subject->getIdentity()
                  ),
                );
              }
            }
        
            else if( !$row->resource_approved && $row->user_approved )
            {
              $menu[] =  array(
                'label' => 'Cancel Membership Request',
                'class' => 'smoothbox icon_group_cancel',
                'route' => 'group_extended',
                'params' => array(
                  'controller' => 'member',
                  'action' => 'cancel',
                  'group_id' => $subject->getIdentity()
                ),
              );
            }
        
            else if( !$row->user_approved && $row->resource_approved )
            {
              $menu[] = array(
                  'label' => 'Accept Membership Request',
                  'class' => 'smoothbox icon_group_accept',
                  'route' => 'group_extended',
                  'params' => array(
                    'controller' => 'member',
                    'action' => 'accept',
                    'group_id' => $subject->getIdentity()
                  ),
              );
        
              $menu[] =  array(
                  'label' => 'Ignore Membership Request',
                  'class' => 'smoothbox icon_group_reject',
                  'route' => 'group_extended',
                  'params' => array(
                    'controller' => 'member',
                    'action' => 'reject',
                    'group_id' => $subject->getIdentity()
                  ),
              );
            }
        
            else
            {
              $allowJoinGroup = true;
            }
            if($allowJoinGroup){
          ?>
          <?php foreach($menu as $params){ ?>
            <li>
              <a href="<?php echo $this->url($params["params"],$params["route"],true) ?>" class="buttonlink <?php echo $params['class']; ?>">
                <span> <?php echo $this->translate($params["label"]); ?> </span>
              </a>
            </li> 
            <?php } ?>
          <?php } ?>
          <?php if($viewer->getIdentity()){ ?>
          <li>
            <a class="smoothbox" href="<?php echo $this->url(array('action' => 'share','module' => 'activity','format' => 'smoothbox','type'=>$subject->getType(),'id'=>$subject->getIdentity()),'default',true) ?>">
              <i class="fas fa-share-alt"></i>
              <span> <?php echo $this->translate('Share Group'); ?> </span>
            </a>
          </li>  
          <?php } ?>
          <li>
            <a href="javascript:void(0)" class="coverphoto_navigation_dropdown_btn" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-ellipsis-h"></i>
            </a>
            <ul class="dropdown-menu">
              <?php foreach( $this->groupNavigation as $link ): ?>
                <li>
                  <a class="<?php echo  'buttonlink' . ( $link->getClass() ? ' ' . $link->getClass() : '' ) . (!empty($link->get('icon')) ? $link->get('icon') : ''); ?>" href='<?php echo $link->getHref() ?>' aria-label="<?php echo $this->translate($link->getlabel()) ?>">
                  <span><?php echo $this->translate($link->getlabel()) ?></span>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </li>
        </ul>
      </div>
      <?php } ?>
    </div>
  <?php endif; ?>
</div>  
