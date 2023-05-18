<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Everification/views/scripts/dismiss_message.tpl';?>
<div>
  <div>
    <h3>Manage Documents</h3>
    <p><?php echo $this->translate("This page lists all the members who have submitted their documents for verified badge. If you need to search for a specific member, enter your search criteria in the fields below.<br />") ?></p>
    <br />
    
    <script type="text/javascript">
      var currentOrder = '<?php echo $this->order ?>';
      var currentOrderDirection = '<?php echo $this->order_direction ?>';
      var changeOrder = function(order, default_direction){
        // Just change direction
        if( order == currentOrder ) {
          $('order_direction').value = ( currentOrderDirection == 'ASC' ? 'DESC' : 'ASC' );
        } else {
          $('order').value = order;
          $('order_direction').value = default_direction;
        }
        $('filter_form').submit();
      }
      
      function multiModify()
      {
        var multimodify_form = $('multimodify_form');
        if (multimodify_form.submit_button.value == 'delete')
        {
          return confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete the selected document accounts?")) ?>');
        }
      }

      function selectAll()
      {
        var i;
        var multimodify_form = $('multimodify_form');
        var inputs = multimodify_form.elements;
        for (i = 1; i < inputs.length - 1; i++) {
          if (!inputs[i].disabled) {
            inputs[i].checked = inputs[0].checked;
          }
        }
      }
    </script>
    
    <div class='admin_search everification_search_form'>
      <?php echo $this->formFilter->render($this) ?>
    </div>
    <br />
    
    <div class='admin_results'>
      <div><?php echo $this->translate(array("%s entry found.", "%s entries found.", $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())); ?></div>
      <div><?php echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true, 'query' => $this->formValues)); ?></div>
    </div>
    
    <br />
    <?php if(count($this->paginator) > 0):?>
    <div class="admin_table_form">
    <form id='multimodify_form' method="post" action="<?php echo $this->url(array('action'=>'multi-modify'));?>" onSubmit="multiModify()">
      <table class='admin_table'>
        <thead>
          <tr> 
            <th><input onclick='selectAll();' type='checkbox' class='checkbox' /></th>
            <th style='width: 1%;'><?php echo $this->translate("ID") ?></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('displayname', 'ASC');"><?php echo $this->translate("Display Name") ?></a></th>
            <th><a href="javascript:void(0);" onclick="javascript:changeOrder('username', 'ASC');"><?php echo $this->translate("Username") ?></a></th>
            <th style='width: 1%;'><a href="javascript:void(0);" onclick="javascript:changeOrder('email', 'ASC');"><?php echo $this->translate("Email") ?></a></th>
            <th style='width: 1%;' class='admin_table_centered'><?php echo $this->translate("Status ") ?></th>
            <th style='width: 1%;' class='admin_table_options'><?php echo $this->translate("Option") ?></th>
          </tr>
        </thead>
        <tbody>
          <?php if( count($this->paginator) ): ?>
            <?php foreach( $this->paginator as $item ): ?>
              <?php $user = $this->item('user', $item->user_id); ?>
              <tr>
                <td><input type='checkbox' class='checkbox' name='modify_<?php echo $item->document_id; ?>' value="<?php echo $item->document_id; ?>" /></td>
                <td><?php echo $item->document_id ?></td>
                <td class='admin_table_bold'>
                  <?php echo $this->htmlLink($user->getHref(), $this->string()->truncate($user->getTitle(), 16), array('target' => '_blank'))?>
                </td>
                <td class='admin_table_user'><?php echo $this->htmlLink($this->item('user', $item->user_id)->getHref(), $this->item('user', $item->user_id)->username, array('target' => '_blank')) ?></td>
                <td class='admin_table_email'>
                  <?php if( !$this->hideEmails ): ?>
                    <a href='mailto:<?php echo $item->email ?>'><?php echo $item->email ?></a>
                  <?php else: ?>
                    (hidden)
                  <?php endif; ?>
                </td>

                <td class='admin_table_centered'>
                  <?php if($item->verified == '1') { ?>
                    <span class="everification_label_verified"><?php echo $this->translate("Verified"); ?></span>
                  <?php } else if($item->verified == '2') { ?>
                    <span class="everification_label_rejected"><?php echo $this->translate("Rejected"); ?></span>
                  <?php } else if($item->verified == '0') { ?>
                    <span class="everification_label_pending"><?php echo $this->translate("Pending"); ?></span>
                  <?php } ?>
                </td>
                <td class='admin_table_options everification_manage_options'>
                  <?php if(empty($item->verified)) { ?>
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'everification', 'controller' => 'manage', 'action' => 'verifieddocument', 'id' =>$item->document_id), $this->translate(''), array('class' => 'smoothbox fa everification_icon_verify', 'title' => 'Verify Document')) ?>
                    
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'everification', 'controller' => 'manage', 'action' => 'reject', 'id' =>$item->document_id), $this->translate(''), array('class' => 'smoothbox fa everification_icon_reject', 'title' => 'Reject Document')) ?>
                    
                    <?php if(!$user->enabled && !$user->approved) { ?>
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'everification', 'controller' => 'manage', 'action' => 'verifieddocument', 'id' =>$item->document_id, 'enable' => 1, 'user_id' => $item->user_id), $this->translate(''), array('class' => 'smoothbox fa everification_icon_approve', 'title' => 'Verify Document & Enable User')); ?>
                    <?php } ?>
                    
                    
                  <?php } else { ?>
                    <?php if(!$user->enabled && !$user->approved) { ?>
                      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'everification', 'controller' => 'manage', 'action' => 'verifieddocument', 'id' =>$item->document_id, 'enable' => 1, 'user_id' => $item->user_id), $this->translate(''), array('class' => 'smoothbox fa everification_icon_approve', 'title' => 'Document Verified & Enable User')) ?>
                    <?php } ?>
                  <?php } ?>
                  <?php if($item->verified == '1') { ?>
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'everification', 'controller' => 'manage', 'action' => 'reject', 'id' =>$item->document_id), $this->translate(''), array('class' => 'smoothbox fa everification_icon_reject', 'title' => 'Reject Document')) ?>
                  <?php } ?>
                  <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'everification', 'controller' => 'manage', 'action' => 'deletedocument', 'id' =>$item->document_id), $this->translate(''), array('class' => 'smoothbox fa everification_icon_delete', 'title' => 'Delete')) ?>
                  <?php if($item->file_id) { ?>
                  <?php //echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'everification', 'controller' => 'manage', 'action' => 'deletedocuments', 'id' =>$item->document_id), $this->translate(''), array('class' => 'smoothbox fa everification_icon_delete', 'title' => 'Delete Document Only')) ?>
                  <?php } ?>
                  <?php if($item->file_id) { ?>
                    <?php $storage = Engine_Api::_()->getItem('storage_file', $item->file_id); ?>
                    <?php if($storage) { ?>
                    <a target="_blank" href="<?php echo $storage->map(); ?>" class="fa everification_icon_view" title='<?php echo $this->translate("Preview") ?>'></a>
                    <?php } ?>
                  <?php } ?>
                  <?php if($item->note) { ?>
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'everification', 'controller' => 'manage', 'action' => 'note', 'id' =>$item->document_id), $this->translate(''), array('class' => 'smoothbox fa everification_icon_note', 'title' => 'Note')) ?>
                  <?php } ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
      <br />
      <div class='buttons'>
<!--        <button type='submit' name="submit_button" value="approve"><?php //echo $this->translate("Approve Selected") ?></button>
        <button type='submit' name="submit_button" value="reject"><?php //echo $this->translate("Reject Selected") ?></button>-->
        <button type='submit' name="submit_button" value="delete" style="float: right;"><?php echo $this->translate("Delete Selected") ?></button>
        
        <!--<button type='submit'><?php //echo $this->translate("Delete Selected") ?></button>-->
      </div>
    </form>
    </div>
    <?php else:?>
      <div class="tip">
        <span>
          <?php echo "There are no documents uploaded yet.";?>
        </span>
      </div>
    <?php endif;?>
  </div>
</div> 
