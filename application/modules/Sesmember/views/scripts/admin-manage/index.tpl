<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesmember/views/scripts/dismiss_message.tpl';?>

<p>
  <?php echo $this->translate("The members of your social network are listed here. If you need to search for a specific member, enter your search criteria in the fields below.") ?>
</p>
<br />
<?php $baseURL = $this->layout()->staticBaseUrl;?>
<script type="text/javascript">
  var currentOrder = '<?php echo $this->order ?>';
  var currentOrderDirection = '<?php echo $this->order_direction ?>';
  var changeOrder = function(order, default_direction){
    // Just change direction
    if( order == currentOrder ) {
      document.getElementById('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
    } else {
      document.getElementById('order').value = order;
      document.getElementById('order_direction').value = default_direction;
    }
    scriptJquery('#filter_form').trigger('submit');
  }
</script>

<div class='admin_search sesbasic_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>
<br />

<div class='admin_results'>
  <div>
    <?php $count = $this->paginator->getTotalItemCount() ?>
    <?php echo $this->translate(array("%s member found.", "%s members found.", $count),
        $this->locale()->toNumber($count)) ?>
  </div>
  <div>
    <?php echo $this->paginationControl($this->paginator, null, null, array(
      'pageAsQuery' => true,
      'query' => $this->formValues,
      //'params' => $this->formValues,
    )); ?>
  </div>
</div>

<br />
<?php if(engine_count($this->paginator) > 0):?>
<div class="admin_table_form">
<form>
  <table class='admin_table'>
    <thead>
      <tr>
        <th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('user_id', 'DESC');"><?php echo $this->translate("ID") ?></a></th>
        <th><a href="javascript:void(0);" onclick="javascript:changeOrder('displayname', 'ASC');"><?php echo $this->translate("Display Name") ?></a></th>
        <th><a href="javascript:void(0);" onclick="javascript:changeOrder('username', 'ASC');"><?php echo $this->translate("Username") ?></a></th>
        <th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('email', 'ASC');"><?php echo $this->translate("Email") ?></a></th>
        <th style='width: 1%;' class='admin_table_centered'><a href="javascript:void(0);" onclick="javascript:changeOrder('level_id', 'ASC');"><?php echo $this->translate("User Level") ?></a></th>
        <th style='width: 1%;' class='admin_table_centered'><?php echo $this->translate("Approved") ?></th>
        <th style='width: 1%;' class='admin_table_centered'><?php echo $this->translate("Verified") ?></th>
        <th style='width: 1%;' class='admin_table_centered'><?php echo $this->translate("VIP") ?></th>
        <th style='width: 1%;' class='admin_table_centered'><?php echo $this->translate("Featured") ?></th>
        <th style='width: 1%;' class='admin_table_centered'><?php echo $this->translate("Sponsored") ?></th>
        <th style='width: 1%;' class='admin_table_centered'><?php echo $this->translate("Of the Day") ?></th>
        <th style='width: 1%;' class='admin_table_options'><?php echo $this->translate("Options") ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
        <?php foreach( $this->paginator as $item ):
          $user = $this->item('user', $item->user_id);
          ?>
          <tr>
            <td><?php echo $item->user_id ?></td>
            <td class='admin_table_bold'>
              <?php echo $this->htmlLink($user->getHref(),
                  $this->string()->truncate($user->getTitle(), 16),
                  array('target' => '_blank'))?>
            </td>
            <td class='admin_table_user'><?php echo $this->htmlLink($this->item('user', $item->user_id)->getHref(), $this->item('user', $item->user_id)->username, array('target' => '_blank')) ?></td>
            <td class='admin_table_email'>
              <?php if( !$this->hideEmails ): ?>
                <a href='mailto:<?php echo $item->email ?>'><?php echo $item->email ?></a>
              <?php else: ?>
                (hidden)
              <?php endif; ?>
            </td>
            <td class="admin_table_centered nowrap">
              <a href="<?php echo $this->url(array('module'=>'authorization','controller'=>'level', 'action' => 'edit', 'id' => $item->level_id)) ?>">
                 <?php $level = Engine_Api::_()->getItem('authorization_level', $item->level_id);
                 if($level) { ?>
                 <?php echo $this->translate($level->getTitle()); } else { ?>
                 <?php echo "---"; ?>
                 <?php } ?>
              </a>
            </td>
            <td class='admin_table_centered'>
              <?php echo ( $item->enabled ? $this->translate('Yes') : $this->translate('No') ) ?>
            </td>
            <td class='admin_table_centered'>
              <?php echo ( $item->user_verified ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmember', 'controller' => 'manage', 'action' => 'user-verified', 'user_id' => $item->user_id, 'userinfo_id' => $item->userinfo_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Unmark as Verified'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmember', 'controller' => 'manage', 'action' => 'user-verified', 'user_id' => $item->user_id, 'userinfo_id' => $item->userinfo_id), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Mark Verified')))) ) ?>
            </td>
            <td class='admin_table_centered'>
              <?php echo ( $item->vip ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmember', 'controller' => 'manage', 'action' => 'vip', 'user_id' => $item->user_id, 'userinfo_id' => $item->userinfo_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Unmark as VIP'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmember', 'controller' => 'manage', 'action' => 'vip', 'user_id' => $item->user_id, 'userinfo_id' => $item->userinfo_id), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Mark VIP')))) ) ?>
            </td>
            <td class='admin_table_centered'>
              <?php echo ( $item->featured ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmember', 'controller' => 'manage', 'action' => 'featured', 'user_id' => $item->user_id, 'userinfo_id' => $item->userinfo_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Unmark as Featured'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmember', 'controller' => 'manage', 'action' => 'featured', 'user_id' => $item->user_id, 'userinfo_id' => $item->userinfo_id), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Mark Featured')))) ) ?>
            </td>
            <td class='admin_table_centered'>
              <?php echo ( $item->sponsored ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmember', 'controller' => 'manage', 'action' => 'sponsored', 'user_id' => $item->user_id, 'userinfo_id' => $item->userinfo_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Unmark as Sponsored'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesmember', 'controller' => 'manage', 'action' => 'sponsored', 'user_id' => $item->user_id, 'userinfo_id' => $item->userinfo_id), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Mark Sponsored')))) ) ?>
            </td>
            <td class='admin_table_centered'>
              <?php if($item->offtheday == 1):?>  
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmember', 'controller' => 'admin-manage', 'action' => 'oftheday', 'id' => $item->user_id, 'type' => 'sesmember_team', 'param' => 0, 'userinfo_id' => $item->userinfo_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title'=> $this->translate('Edit Team Member of the Day'))), array('class' => 'smoothbox')); ?>
              <?php else: ?>
                <?php echo $this->htmlLink(array('route' => 'default', 'module' => 'sesmember', 'controller' => 'admin-manage', 'action' => 'oftheday', 'id' => $item->user_id, 'type' => 'sesmember_team', 'param' => 1, 'userinfo_id' => $item->userinfo_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/error.png', '', array('title'=> $this->translate('Make Team Member of the Day'))), array('class' => 'smoothbox')) ?>
              <?php endif; ?>
            </td>
            <td class='admin_table_options'>
              <a class='smoothbox' href='<?php echo $this->url(array('action' => 'stats', 'id' => $item->user_id));?>'>
                <?php echo $this->translate("stats") ?>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  <br />
</form>
</div>
<?php else:?>
<div class="tip">
  <span>
    <?php echo "There are no members in your search criteria.";?>
  </span>
</div>
<?php endif;?>
