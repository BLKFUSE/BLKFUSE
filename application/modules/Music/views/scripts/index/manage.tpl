<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Music
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: manage.tpl 9987 2013-03-20 00:58:10Z john $
 * @author     Steve
 */
?>

<script type="text/javascript">
  //<![CDATA[
    en4.core.runonce.add(function() {
      scriptJquery('#sort, #show').on('change', function(){
        scriptJquery(this).parents('form').trigger("submit");
      });
    });
  //]]>
</script>


<?php if( 0 == engine_count($this->paginator) ): ?>

  <div class="tip">
    <span>
      <?php echo $this->translate('There is no music uploaded yet.') ?>
      <?php if( $this->canCreate ): ?>
        <?php echo $this->htmlLink(array(
          'route' => 'music_general',
          'action' => 'create'
        ), $this->translate('Why don\'t you add some?')) ?>
      <?php endif; ?>
    </span>
  </div>

<?php else: ?>

  <ul class="music_browse">
    <?php foreach ($this->paginator as $playlist): ?>
      <li id="music_playlist_item_<?php echo $playlist->getIdentity() ?>">
        <div class="music_browse_options">
          <?php if ($playlist->isDeletable() || $playlist->isEditable()): ?>
            <ul>
              <?php if ($playlist->isEditable()): ?>
                <li>
                  <?php echo $this->htmlLink($playlist->getHref(array('route' => 'music_playlist_specific', 'action' => 'edit')),
                    $this->translate('Edit Playlist'),
                    array('class'=>'buttonlink icon_music_edit'
                    )) ?>
                </li>
              <?php endif; ?>
              <?php if( $playlist->isDeletable() ): ?>
                <li>
                  <?php
                      echo $this->htmlLink(array('route' => 'default', 'module' => 'music', 'controller' => 'playlist', 'action' => 'delete', 'playlist_id' => $playlist->getIdentity(), 'format' => 'smoothbox'), $this->translate('Delete Playlist'), array(
                        'class' => 'buttonlink smoothbox icon_music_delete'
                      ));
                  ?>
                </li>
              <?php endif; ?>
              <?php if( $playlist->getOwner()->isSelf( Engine_Api::_()->user()->getViewer() ) ): ?>
                <li>
                  <?php echo $this->htmlLink($playlist->getHref(array('route' => 'music_playlist_specific', 'action' => 'set-profile')),
                    $this->translate($playlist->profile ? 'Disable Profile Playlist' : 'Play on my Profile'),
                    array(
                      'class' => 'buttonlink music_set_profile_playlist ' . ( $playlist->profile ? 'icon_music_disableonprofile' : 'icon_music_playonprofile' )
                    )
                  ) ?>
                </li>
              <?php endif; ?>
            </ul>
          <?php endif; ?>
        </div>
        <div class="music_browse_info">
          <div class="music_browse_info_title">
            <h3><?php echo $this->htmlLink($playlist->getHref(), $playlist->getTitle()) ?></h3>
          </div>
          <div class="music_browse_info_date">
            <?php echo $this->translate('Created %s by ', $this->timestamp($playlist->creation_date)) ?>
            <?php echo $this->htmlLink($playlist->getOwner(), $playlist->getOwner()->getTitle()) ?>
            -
            <?php echo $this->htmlLink($playlist->getHref(),  $this->translate(array('%s comment', '%s comments', $playlist->getCommentCount()), $this->locale()->toNumber($playlist->getCommentCount()))) ?>
            
            <?php echo $this->partial('_rating.tpl', 'core', array('item' => $playlist, 'param' => 'show', 'module' => 'music')); ?>
          </div>
          <div class="music_browse_info_desc">
            <?php echo $playlist->description ?>
          </div>
          <?php echo $this->partial('_approved_tip.tpl', 'core', array('item' => $playlist)); ?>
          <?php echo $this->partial('_Player.tpl', array('playlist' => $playlist, 'hideStats' => true)) ?>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>

  <?php echo $this->paginationControl($this->paginator, null, null, array(
    'pageAsQuery' => true,
    'query' => $this->formValues,
    //'params' => $this->formValues,
  )); ?>

<?php endif; ?>


<script type="text/javascript">
  scriptJquery('.core_main_music').parent().addClass('active');
</script>
