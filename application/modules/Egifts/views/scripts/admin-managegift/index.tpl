<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Egifts/views/scripts/dismiss_message.tpl';?>
<?php
  $currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
  $base_url = $this->layout()->staticBaseUrl;
  
?>
<h3><?php echo $this->translate("Manage Gifts"); ?></h3>
<p><?php echo $this->translate("From here, you can browse all the gifts created by you. You can also create new by clicking on “Add New Gift” below. You can enable / disable gifts if you don’t like them to be sent or displayed on Browse Gifts Page. Click on Edit Link to edit details of the gift. Also you can delete them whenever required. Search any of the gifts by entering the searching criteria into the filter field."); ?></p>
<br>
<div class="sesbasic_search_reasult"> <?php echo $this->htmlLink(array("route" => 'admin_default', "module" => 'egifts', "controller" => 'managegift', "action" => 'create'), "Add New Gift", array('class' => "sesbasic_icon_add buttonlink smoothbox")); ?> </div>
<?php if(isset($this->paginator) && engine_count($this->paginator)>0){  ?>
  <div class="egift_admin_gift_list">
    <table style="width: 100%"  class="admin_table">
      <thead>
        <tr>
          <th><?php echo $this->translate('Id'); ?></th>
          <th><?php echo $this->translate('Title'); ?></th>
          <th><?php echo $this->translate('Price ('.$currency.')'); ?></th>
          <th class="admin_table_centered"><?php echo $this->translate('Icon'); ?></th>
          <th><?php echo $this->translate('Create Date'); ?></th>
          <th class="admin_table_centered"><?php echo $this->translate('Status'); ?></th>
          <th><?php echo $this->translate('Action'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php  foreach ($this->paginator as $paginator) {
             $image_url='';
             if(isset($paginator['icon_id']) && !empty($paginator['icon_id']))
             {
               $file = Engine_Api::_()->getItemTable('storage_file')->getFile($paginator['icon_id'], null);
               $image_url=$file->map();
             }
             $enble="application/modules/Sesbasic/externals/images/icons/check.png";
             if($paginator['status']==0)
             {
               $enble="application/modules/Sesbasic/externals/images/icons/error.png";
             }
           ?>
        <tr>
          <td><?php echo $paginator['gift_id']; ?></td>
          <td><?php echo $paginator['title']; ?></td>
          <td><?php echo $paginator['price']; ?></td>
          <td class="admin_table_centered"><img src="<?php echo $image_url; ?>" style="max-width: 60px;"></td>
          <td><?php echo $paginator['created_date']; ?></td>
          <td class="admin_table_centered"><a onclick="enabledisable('<?php echo $paginator['gift_id'];  ?>');" href="javascript:void(0);"><img src="<?php echo $enble; ?>"></a></td>
          <td>
            <a onclick="deletegift('<?php echo $paginator['gift_id'];  ?>');" href="javascript:void(0);"><?php echo $this->translate('Delete'); ?></a>
            |
            <?php echo $this->htmlLink(array("route" => 'admin_default', "module" => 'egifts', "controller" => 'managegift', "action" => 'edit',"gift_id"=> $paginator['gift_id']), "Edit", array('class' => "smoothbox")); ?>
          </td>
        </tr>
        <?php  }  ?>
      </tbody>
    </table>
  </div>
<?php } else { ?>
	<div class="tip"> <span>No Gift Found</span> </div>
<?php } ?>
<script>
      function  enabledisable(gift_id)
      {
        scriptJquery.ajax({
          'url': en4.core.baseUrl + 'admin/egifts/managegift/changestatusgift',
          method: "POST",
          data: {gift_id: gift_id},
          success: function (html) {
              location.reload();
          }
        });
      }
      function  deletegift(gift_id)
      {
        if(confirm("Are you sure ? Do you want to delete the gift?"))
        {
          scriptJquery.ajax({
              'url': en4.core.baseUrl + 'admin/egifts/managegift/deletegift',
              method: "POST",
              data: {gift_id: gift_id},
              success: function (html) {
                  location.reload();
              }
          });
        }
      }
  </script> 
