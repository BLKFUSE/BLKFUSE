<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9747 2012-07-26 02:08:08Z john $
 * @author		 John
 */
?>
<?php if( $this->canPost || $this->paginator->count() > 1 ): ?>
  <div>
    <?php if( $this->canPost ): ?>
      <?php echo $this->htmlLink(array(
        'route' => 'group_extended',
        'controller' => 'topic',
        'action' => 'create',
        'subject' => $this->subject()->getGuid(),
      ), $this->translate('Post New Topic'), array(
        'class' => 'buttonlink icon_group_photo_new'
      )) ?>
    <?php endif;?>
    <?php if( $this->paginator->count() > 1 ): ?>
      <?php echo $this->htmlLink(array(
        'route' => 'group_extended',
        'controller' => 'topic',
        'action' => 'index',
        'subject' => $this->subject()->getGuid(),
      ), $this->translate("View all %s Topics", $this->paginator->getTotalItemCount()), array(
        'class' => 'buttonlink icon_viewmore'
      )) ?>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php $empty = true; if( $this->paginator->getTotalItemCount() > 0 ): ?>
  <div class="group_discussions_list">
    <ul class="group_discussions">
      <?php foreach( $this->paginator as $topic ):
        if( empty($topic->lastposter_id) ) {
          continue;
        }
        $lastpost = $topic->getLastPost();
        if( !$lastpost ) {
          continue;
        }
        $lastposter = $topic->getLastPoster();
        $empty = false;
        ?>
        <li>
          <div class="group_discussions_thumb">
            <?php echo $this->htmlLink($topic->getOwner()->getHref(), $this->itemBackgroundPhoto($topic->getOwner(), 'thumb.icon')) ?>
          </div>
          <?php if( $lastpost && $lastposter ): ?>
          <div class="group_discussions_lastreply">
            <?php echo $this->htmlLink($lastposter->getHref(), $this->itemBackgroundPhoto($lastposter, 'thumb.icon')) ?>
            <div class="group_discussions_lastreply_info">
              <?php echo $this->htmlLink($lastpost->getHref(), $this->translate('Last Post')) ?> <?php echo $this->translate('by');?> <?php echo $lastposter->__toString() ?>
              <br />
              <?php echo $this->timestamp(strtotime($topic->modified_date), array('tag' => 'div', 'class' => 'group_discussions_lastreply_info_date')) ?>
            </div>
          </div>
          <?php endif; ?>
          <div class="group_discussions_info">
            <h3>
              <?php if( $topic->sticky ): ?>📌<?php endif; ?>
              <?php echo $this->htmlLink($topic->getHref(), $topic->getTitle()) ?>
            </h3>
            <div class="group_discussions_stats">
              <span><?php echo $this->htmlLink($topic->getOwner()->getHref(), $topic->getOwner()->getTitle()); ?></span>
              <span class="sep">-</span>
              <span><?php echo $this->timestamp($topic->creation_date) ?></span>
              <span class="sep">-</span>
              <span>
                <?php echo $this->locale()->toNumber($topic->post_count - 1) ?>
                <?php echo $this->translate(array('reply', 'replies', $topic->post_count - 1)) ?>
              </span>
            </div>
            <div class="group_discussions_blurb">
              <?php echo $this->viewMore(strip_tags($topic->getDescription()), 255, 1027, 511, false); ?>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

<?php endif;?>
<?php if( $empty ): ?>
  <div class="tip">
    <span>
      <?php echo $this->translate('No topics have been posted in this group yet.');?>
    </span>
  </div>
<?php endif; ?>
