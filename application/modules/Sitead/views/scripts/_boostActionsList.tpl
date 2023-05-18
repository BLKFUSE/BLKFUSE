<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<input type="hidden" name="action_id" id="action_id">
<?php if(count($this->actions)) : ?>
  <ul class="feed show_full_box_image feed_sections_left ">
<?php foreach ($this->actions as $action) : ?>
  <li class="activity-item">
      <?php $item = $itemPhoto = (isset($action->getTypeInfo()->is_object_thumb) && !empty($action->getTypeInfo()->is_object_thumb)) ? $action->getObject() : $action->getSubject();
      $itemPhoto = (isset($action->getTypeInfo()->is_object_thumb) && $action->getTypeInfo()->is_object_thumb === 2) ? $action->getObject()->getParent() : $itemPhoto;
      ?>
          <div class="aaf_feed_top_section aaf_feed_section_left">
          <div class='feed_item_photo aaf_feed_thumb'> <?php
            echo $this->htmlLink($itemPhoto->getHref(), $this->itemPhoto($itemPhoto, 'thumb.icon', $itemPhoto->getTitle()), array('class' => 'sea_add_tooltip_link', 'rel' => $itemPhoto->getType() . ' ' . $itemPhoto->getIdentity())
            )
            ?></div>


<div class="aaf_feed_top_section_title">
<div class="<?php echo ( empty($action->getTypeInfo()->is_generated) ? 'feed_item_posted' : 'feed_item_generated' ) ?>">
              <?php
              /* Start Working group feed. */
              $groupedFeeds = null;
              if( $action->type == 'friends' ) {
                $subject_guid = $action->getSubject()->getGuid();
                $total_guid = $action->type . '_' . $subject_guid;
              } elseif( $action->type == 'tagged' ) {
                foreach( $action->getAttachments() as $attachment ) {
                  $object_guid = $attachment->item->getGuid();
                  $Subject_guid = $action->getSubject()->getGuid();
                  $total_guid = $action->type . '_' . $object_guid . '_' . $Subject_guid;
                }
              } else {
                $getObj = $action->getObject();
                if(null === $getObj)
                  continue;
                $subject_guid = $getObj->getGuid();
                $total_guid = $action->type . '_' . $subject_guid;
              }
              if( !isset($grouped_actions[$total_guid]) && isset($this->groupedFeeds[$total_guid]) ) {
                $groupedFeeds = $this->groupedFeeds[$total_guid];
              }
              /* End Working group feed. */
              echo $this->getContent($action, false, $groupedFeeds, array('similarActivities' => $this->similarActivities));
              ?>
            </div>

            <div class="aaf_feed_top_footer">
              <?php
              $icon_type = 'activity_icon_' . $action->type;
              list($attachment) = $action->getAttachments();
              if( is_object($attachment) && $action->attachment_count > 0 && $attachment->item ):
                $icon_type .= ' item_icon_' . $attachment->item->getType() . ' ';
              endif;
              ?>
              <?php if( is_array($action->params) && isset($action->params['checkin']) && !empty($action->params['checkin']) ): ?>
                <?php if( isset($action->params['checkin']['type']) && $action->params['checkin']['type'] == 'Page' ): ?>
                  <?php $icon_type = "item_icon_sitepage"; ?>
                <?php elseif( isset($action->params['checkin']['type']) && $action->params['checkin']['type'] == 'Business' ): ?>
                  <?php $icon_type = "item_icon_sitebusiness"; ?>
                <?php elseif( isset($action->params['checkin']['type']) && $action->params['checkin']['type'] == 'Group' ): ?>
                  <?php $icon_type = "item_icon_sitegroup"; ?>
                <?php elseif( isset($action->params['checkin']['type']) && $action->params['checkin']['type'] == 'Store' ): ?>
                  <?php $icon_type = "item_icon_sitestore"; ?>
                <?php else: ?>
                  <?php $icon_type = "item_icon_sitetagcheckin"; ?>
                <?php endif; ?>
              <?php endif; ?>
              <i class="feed_item_date feed_item_icon <?php echo $icon_type ?>"></i>
              <span class="notranslate feed_item_time"><?php echo $this->timestamp($action->getTimeValue()) ?></span>
            </div>
          </div>
          <div id="continue_<?php echo $action->action_id?>" class="boost-feed-list"><button class="boost-post" onclick="javascript:selectActivity('<?php echo $action->action_id ?>');"><?php echo $this->translate("Boost Post ")?></button> <button class="unboost-post" style="display: none;"> <?php echo $this->translate("Selected Boost Post ")?></button></div> 
          <div  class="unboost-post" style="display: none;"><button><?php echo $this->translate("Selected Boost Post ")?></button> </div> 
          </div>
             
           </li>
              <?php endforeach ?>
            </ul>
              <?php else : ?>
                <div class="tip">
              <span>
                <?php
                echo $this->translate("There are no ad activity right now for Boost. Please create some activity first." );
                ?>
              </span>
            </div>
            <?php endif ?> 

              <script type="text/javascript">
                function selectActivity(val) {
                  $('action_id').value = val;
                }

                $$('.boost-post').addEvent('click', function() { 
                    $$('.unboost-post').each(function(i, field) {
                      i.style.display = 'none';
                    });
                    $$('.boost-post').each(function(i, field) {
                      i.style.display = 'block';
                    });
                    this.style.display = 'none';
                    this.nextSibling.nextSibling.style.display = 'block';
                });

              </script>
