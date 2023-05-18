<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<h2>
  <?php echo $this->translate('Advanced Share Plugin') ?>
</h2>

<?php if( count($this->navigation) ): ?>
  <div class='tabs seaocore_admin_tabs clr'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<h3>
  <?php echo $this->translate("Manage Sharing Within Community"); ?>
</h3>

<p>
  <?php
  $deleteDisabled = array('timeline', 'email', 'message', 'user');
  echo $this->translate('Below, you can choose and edit the share types. Drag and drop items to change their sequence.');
  ?>
</p>
<br />
<div>
  <a href="<?php echo $this->url(array('action' => 'add-sharetype')) ?>" class="buttonlink siteshare_icon_add" title="<?php echo $this->translate('Add a Share Type'); ?>"><?php echo $this->translate('Add a Share Type'); ?></a>
</div>
<br />
<div class="siteshare_admin_order_list">
  <div class="list_head">
    <div style="width:20%">
      <?php echo $this->translate("Title"); ?>
    </div>
    <div style="width:20%">
      <?php echo $this->translate("Share Allow"); ?>
    </div>
    <div style="width:20%">
      <?php echo $this->translate("Notification Allow"); ?>
    </div>	  
    <div style="width:20%" class="admin_table_centered">
      <?php echo $this->translate("Enabled"); ?>
    </div>
    <div style="width:10%">
      <?php echo $this->translate("Options"); ?>
    </div>
  </div>
  <form id='saveorder_form' method='post' action='<?php echo $this->url(array('action' => 'update-order')) ?>'>
    <input type='hidden'  name='order' id="order" value=''/>
    <input type='hidden'  name='item_type' id="item_type" value='siteshare_sharetype'/>
    <div id='order-element'>
      <ul>
        <?php foreach( $this->sharetypes as $item ) : ?>
          <li>
            <input type='hidden'  name='order[]' value='<?php echo $item->sharetype_id; ?>'>
            <div style="width:20%;" class='admin_table_bold'>
              <?php echo $item->title; ?>
            </div>
            <div style="width:20%;" class='admin_table_bold'>
              <?php echo $item->share_allow; ?>
            </div>
            <div style="width:20%;" class='admin_table_bold'>
              <?php echo $item->notification_allow; ?>
            </div>
            <div style="width:20%;" class='admin_table_centered'>
              <?php
              echo ( $item->enabled ? $this->htmlLink(array('route' => 'admin_default', 'module' =>
                  'siteshare', 'controller' => 'manage', 'action' => 'enable-sharetype', 'sharetype_id' =>
                  $item->getIdentity()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Siteshare/externals/images/enabled1.gif', '', array('title' => $this->translate('Disable Share Type'))), array()) :
                $this->htmlLink(array('route' => 'admin_default', 'module' => 'siteshare', 'controller' => 'manage',
                  'action' => 'enable-sharetype', 'sharetype_id' => $item->getIdentity()), $this->htmlImage($this->layout()->staticBaseUrl . 'application/modules/Siteshare/externals/images/enabled0.gif', '', array('title' =>
                    $this->translate('Enable Share type')))) )
              ?>
            </div>
            <div style="width:10%;">
              <a href='<?php echo $this->url(array('action' => 'edit-sharetype', 'module_name' => $item->module_name, 'sharetype_id' => $item->sharetype_id)) ?>'>
                <?php echo $this->translate("Edit") ?>
              </a>
              <?php if( !in_array($item->type, $deleteDisabled) ) : ?>
                | <a href='<?php echo $this->url(array('action' => 'delete-sharetype', 'type' => $item->type, 'sharetype_id' => $item->sharetype_id)) ?>' class="smoothbox">
                  <?php echo $this->translate("Delete") ?>
                </a>
              <?php endif; ?>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </form>
  <br />
  <button onClick="javascript:saveOrder(true);" type='submit'>
    <?php echo $this->translate("Save Order") ?>
  </button>
</div>
<script type="text/javascript">

  var saveFlag = false;
  var origOrder;
  var changeOptionsFlag = false;

  function saveOrder(value) {
    saveFlag = value;
    var finalOrder = [];
    var li = $('order-element').getElementsByTagName('li');
    for (i = 1; i <= li.length; i++)
      finalOrder.push(li[i]);
    $("order").value = finalOrder;

    $('saveorder_form').submit();
  }
  window.addEvent('domready', function () {
    // We autogenerate a list on the fly
    var initList = [];
    var li = $('order-element').getElementsByTagName('li');
    for (i = 1; i <= li.length; i++)
      initList.push(li[i]);
    origOrder = initList;
    var temp_array = $('order-element').getElementsByTagName('ul');
    temp_array.innerHTML = initList;
    new Sortables(temp_array);
  });

  window.onbeforeunload = function (event) {
    var finalOrder = [];
    var li = $('order-element').getElementsByTagName('li');
    for (i = 1; i <= li.length; i++) {
      finalOrder.push(li[i]);
    }

    for (i = 0; i <= li.length; i++) {
      if (finalOrder[i] != origOrder[i])
      {
        changeOptionsFlag = true;
        break;
      }
    }

    if (changeOptionsFlag == true && !saveFlag) {
      var answer = confirm("<?php echo $this->string()->escapeJavascript($this->translate("A change in the order of the tabs has been detected. If you click Cancel, all unsaved changes will be lost. Click OK to save change and proceed.")); ?>");
      if (answer) {
        $('order').value = finalOrder;
        $('saveorder_form').submit();

      }
    }
  }
</script>