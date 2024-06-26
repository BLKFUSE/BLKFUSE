<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: manage.tpl 9987 2013-03-20 00:58:10Z john $
 * @author	   John
 */
?>

<?php if( engine_count($this->paginator) > 0 ): ?>
  <ul class='groups_browse list_wrapper'>
    <?php foreach( $this->paginator as $group ): ?>
      <li>
        <div class="groups_photo">
          <?php echo $this->htmlLink($group->getHref(), $this->itemBackgroundPhoto($group, 'thumb.profile')) ?>
        </div>
        <div class="groups_options">
          <?php if( $group->isOwner($this->viewer()) ): ?>
            <?php echo $this->htmlLink(array('route' => 'group_specific', 'action' => 'edit', 'group_id' => $group->getIdentity()), $this->translate('Edit Group'), array(
              'class' => 'buttonlink icon_group_edit'
            )) ?>
            <?php echo $this->htmlLink(array('route' => 'group_specific', 'action' => 'delete', 'group_id' => $group->getIdentity(), 'format' => 'smoothbox'), $this->translate('Delete Group'), array(
                      'class' => 'buttonlink smoothbox icon_group_delete'
                    ));
            ?>
          <?php elseif( !$group->membership()->isMember($this->viewer(), null) ): ?>
            <?php echo $this->htmlLink(array('route' => 'group_extended', 'controller' => 'member', 'action' => 'join', 'group_id' => $group->getIdentity()), $this->translate('Join Group'), array(
              'class' => 'buttonlink smoothbox icon_group_join'
            )) ?>
          <?php elseif( $group->membership()->isMember($this->viewer(), true) && !$group->isOwner($this->viewer()) ): ?>
            <?php echo $this->htmlLink(array('route' => 'group_extended', 'controller' => 'member', 'action' => 'leave', 'group_id' => $group->getIdentity()), $this->translate('Leave Group'), array(
              'class' => 'buttonlink smoothbox icon_group_leave'
            )) ?>
          <?php endif; ?>
        </div>
        <div class="groups_info">
          <div class="groups_title">
            <h3><?php echo $this->htmlLink($group->getHref(), $group->getTitle()) ?></h3>
          </div>
          <div class="groups_members">
            <?php echo $this->translate(array('%s member', '%s members', $group->membership()->getMemberCount()),$this->locale()->toNumber($group->membership()->getMemberCount())) ?>
            <?php echo $this->translate('led by');?> <?php echo $this->htmlLink($group->getOwner()->getHref(), $group->getOwner()->getTitle()) ?>
            <div class="groups_members_rating"> 
             <?php echo $this->partial('_rating.tpl', 'core', array('item' => $group, 'param' => 'show', 'module' => 'group')); ?>
            </div>
            </div>
          <div class="groups_desc">
            <?php echo $this->viewMore($group->getDescription()) ?>
          </div>
          <?php echo $this->partial('_approved_tip.tpl', 'core', array('item' => $group)); ?>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
  <?php if( engine_count($this->paginator) > 1 ): ?>
    <div>
      <?php echo $this->paginationControl($this->paginator); ?>
    </div>
  <?php endif; ?>

<?php else: ?>
  <div class="tip">
    <span>
    <?php echo $this->translate('You have not joined any groups yet.') ?>
    <?php if( $this->canCreate): ?>
      <?php echo $this->translate('Why don\'t you %1$screate one%2$s?',
        '<a href="'.$this->url(array('action' => 'create'), 'group_general').'">', '</a>') ?>
    <?php endif; ?>
    </span>
  </div>
<?php endif; ?>


<script type="text/javascript">
  scriptJquery('.core_main_group').parent().addClass('active');
</script>
