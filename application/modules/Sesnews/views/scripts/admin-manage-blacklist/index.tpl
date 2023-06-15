<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $baseURL = $this->layout()->staticBaseUrl;?>

<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/dismiss_message.tpl';?>

<h3>Manage Blacklist Urls</h3>

<p><?php echo $this->translate("This Page lists all the blacklist urls that you have added. You can use this page to monitor all them. If you need to search for a specific blacklist urls, enter your search criteria in the fields below.") ?></p>
<br />

<div class='admin_search sesbasic_search_form'>
  <?php echo $this->formFilter->render($this) ?>
</div>

<br />

<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesnews', 'controller' => 'manage-blacklist', 'action' => 'add'), $this->translate('Add New Url'), array('class' => 'buttonlink smoothbox sesbasic_icon_add')) ?><br /><br />

<div class='admin_results'>
  <div>
    <?php echo $this->translate(array("%s url found.", "%s urls found.", $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())) ?>
  </div>
  <div>
    <?php echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true, 'query' => $this->formValues)); ?>
  </div>
</div>
<br />
<?php if(engine_count($this->paginator) > 0):?>
  <div class="admin_table_form">
    <form>
      <table class='admin_table'>
        <thead>
          <tr>
            <th style='width: 1%;'><?php echo $this->translate("ID") ?></th>
            <th><?php echo $this->translate("Url") ?></th>
            <th class='admin_table_centered'><?php echo $this->translate("Status") ?></th>
            <th class='admin_table_options'><?php echo $this->translate("Options") ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_countable($this->paginator) &&  engine_count($this->paginator)): ?>
            <?php foreach( $this->paginator as $item ): ?>
              <tr>
                <td><?php echo $item->url_id ?></td>
                <td class='admin_table_bold'>
                  <a href="<?php echo $item->name; ?>" target="_blank"><?php echo $item->name; ?></a>
                </td>
                <td class='admin_table_centered'>
                  <?php echo ( $item->enabled ? $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesnews', 'controller' => 'manage-blacklist', 'action' => 'enabled', 'id' => $item->url_id), $this->htmlImage($baseURL . 'application/modules/Sesbasic/externals/images/icons/check.png', '', array('title' => $this->translate('Disabled'))), array()) : $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesnews', 'controller' => 'manage-blacklist', 'action' => 'enabled', 'id' => $item->url_id), $this->htmlImage('application/modules/Sesbasic/externals/images/icons/error.png', '', array('title' => $this->translate('Enabled')))) ) ?>
                </td>
                <td class='admin_table_options'>
                  <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesnews', 'controller' => 'manage-blacklist', 'action' => 'edit', 'id' => $item->url_id), $this->translate('Edit'), array('class' => 'smoothbox')) ?>
                  |
                  <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesnews', 'controller' => 'manage-blacklist', 'action' => 'delete', 'id' => $item->url_id), $this->translate('Delete'), array('class' => 'smoothbox')); ?>
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
      <?php echo "There are no urls added yet.";?>
    </span>
  </div>
<?php endif;?>
