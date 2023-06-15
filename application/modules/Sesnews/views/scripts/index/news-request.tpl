<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: news-request.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/dashboard.css'); ?>

<div class="headline">
  <h2>
    <?php echo $this->translate('Sesnews');?>
  </h2>
  <div class="tabs">
    <?php
      // Render the menu
      echo $this->navigation()
        ->menu()
        ->setContainer($this->navigation)
        ->render();
    ?>
  </div>
</div>

<h3>Manage News Requests</h3>
<div id="sesevent_manage_order_content">
<div class="sesbasic_dashboard_search_result"><?php echo $this->paginator->getTotalItemCount().' request(s) found.'; ?></div>
<?php if($this->paginator->getTotalItemCount() > 0): ?>
<div class="sesbasic_dashboard_table sesbasic_bxs">
  <form id='multidelete_form' method="post">
    <table>
      <thead>
        <tr>
          <th><?php echo $this->translate("Event Name") ?></th>
          <th><?php echo $this->translate("Event Owner") ?></th>
          <th><?php echo $this->translate("Approved") ?></th>
          <th><?php echo $this->translate("Options") ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->paginator as $item): ?>
        <tr>
          <?php $event = Engine_Api::_()->getItem("sesevent_event", $item->event_id); ?>	
          <td>
	    <a href="<?php echo $event->getHref(); ?>"><?php echo $event->getTitle(); ?></a>
          </td>
          <td>
	    <?php $user = Engine_Api::_()->getItem('user',$event->user_id) ?>
	    <a href="<?php echo $user->getHref(); ?>"><?php echo $user->getTitle(); ?></a>
          </td>
           <td>
	    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'index', 'action' => 'approved', 'event_id' => $item->event_id, 'news_id' => $item->news_id), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Mark Approve')))) ?>
          </td>
          <td class="table_options">
	    <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesnews', 'controller' => 'index', 'action' => 'reject-request', 'event_id' => $item->event_id, 'news_id' => $item->news_id), $this->translate('Reject Request')) ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
   </form>
</div>
<?php else: ?>
<div class="tip">
  <span>
    <?php echo $this->translate("No request yet.") ?>
  </span>
</div>
<?php endif; ?>
</div>
