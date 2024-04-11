<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Chat
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: index.tpl 9915 2013-02-15 01:30:19Z alex $
 * @author     John
 */
?>
<?php include APPLICATION_PATH .  '/application/modules/Chat/views/scripts/_adminHeader.tpl';?>
<p>
  <?php echo $this->translate('This page lists all the chat rooms created by you.') ?>
</p>
<p>
  <?php
  $settings = Engine_Api::_()->getApi('settings', 'core');
  if( $settings->getSetting('user.support.links', 0) == 1 ) {
    echo '     More info: <a href="https://community.socialengine.com/blogs/597/48/chat" target="_blank">See KB article</a>.';
  } 
?>		
</p>
<div class="mb-3">
  <?php echo $this->htmlLink(array('action' => 'create', 'reset' => false), $this->translate('Create Room'), array('class' => 'admin_chat_addroom smoothbox admin_create_btn')) ?>
</div>
<table class='admin_table admin_responsive_table'>
  <thead>
    <tr>
      <th>Title</th>
      <th class="admin_table_centered" style='width: 20%;'><?php echo $this->translate('Users In Room') ?></th>
      <th style='width: 3%;' class='admin_table_options'><?php echo $this->translate('Options') ?></th>
    </tr>
  </thead>
  <tbody>
    <?php if( engine_count($this->paginator) ): ?>
      <?php foreach( $this->paginator as $room ): ?>
        <tr>
          <td data-label="TITLE"  class='admin_table_bold'><?php echo $room->title ?></td>
          <td class="admin_table_centered" data-label="<?php echo $this->translate('Users In Room') ?>"><?php echo $room->user_count //'0 <= x <= infinity' ?></td>
          <td class='admin_table_options'>
            <?php echo $this->htmlLink(array('module'=>'chat','controller'=>'manage','id'=>$room->room_id,'action'=>'edit'),   
                                       $this->translate('edit'),
                                       array('class'=>'smoothbox')) ?>
            |
            <?php echo $this->htmlLink(array('module'=>'chat','controller'=>'manage','id'=>$room->room_id,'action'=>'delete'), 
                                       $this->translate('delete'),
                                       array('class'=>'smoothbox')) ?>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>

<br/>
<div>
  <?php echo $this->paginationControl($this->paginator); ?>
</div>
