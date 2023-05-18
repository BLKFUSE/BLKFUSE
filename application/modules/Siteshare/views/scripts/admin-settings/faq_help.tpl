<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: faq_help.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<script type="text/javascript">
  function faq_show(id) {
    if($(id).style.display == 'block') {
      $(id).style.display = 'none';
    } else {
      $(id).style.display = 'block';
    }
  }
</script>

<div class="admin_siteshare_files_wrapper">
  <ul class="admin_siteshare_files siteshare_faq">  

    <li>

      <a href="javascript:void(0);" onClick="faq_show('faq_1');"><?php echo $this->translate("How can I add Social Bookmarks?"); ?></a>
      <div class='faq' style='display: none;' id='faq_1'>
        <?php echo $this->translate("After the Plugin installation, follow the below stated steps:- <br />
        1. Open “Advanced Share ” plugin in your Admin Panel.  <br />
        2. Go to ‘Manage Sharing Outside Community”  <br />
        3. Click on “Add Item”  <br />
        4. Mention your desired website’s URL, Label and Icon and click on “Create Menu Item”.  <br />
        5. By following this, you’ll get the option of sharing the content of your website to desired website.<br />
        <b>Social bookmarking URL format:- </b> <br />
               https://www.example.com/?url=CONTENT_URI&title=CONTENT_TITLE&description=CONTENT_DESCRIPTION&media=CONTENT_MEDIA <br />
        Where  CONTENT_URI, CONTENT_TITLE, CONTENT_DESCRIPTION and CONTENT_MEDIA will dynamically change to the correspondent sharing content/item.<br />
      "); ?> 
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_2');"><?php echo $this->translate("How can I get more sharing options within my Website?"); ?></a>
      <div class='faq' style='display: none;' id='faq_2'>
        <?php echo $this->translate("Follow the below steps to add more sharing options within your Website:<br />
        1. Open the plugin in your Admin Panel.  <br />
        2. Go to “Manage Sharing Within Community” and Click on “Add a share type”.  <br />
        3. Fill the form & Click on “Submit”.  <br />
        4. You will get your desired module as a sharing Type."); ?> 
      </div>
    </li>
    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_3');"><?php echo $this->translate("How do I get the share options on any desired page?"); ?></a>
      <div class='faq' style='display: none;' id='faq_3'>
        <?php echo $this->translate("Follow the below steps to get share options on any desired page:<br />
        1. After plugin configuration go to “Layout Editor”<br />
        2. Open the page on which you want share options. Now add any of the widget among “Share Buttons, Share Action Buttons or Automatic Share Flyins” depending upon your choice in your Page Block Placement area.<br />
        3. Customize the settings of the placed widget according to your needs.<br />
        4. Save Block settings & then click on “Save Changes” for the Layout Editor page and it’s done.<br />
        "); ?> 
      </div>
    </li>

    <li>
      <a href="javascript:void(0);" onClick="faq_show('faq_4');"><?php echo $this->translate("The setting for how many share options to be displayed in a row is not working according to the entered number. Is there any other setting related to this?"); ?></a>
      <div class='faq' style='display: none;' id='faq_4'>
        <?php echo $this->translate("No, there is no other setting for this functionality but you need to check the below points for this:<br />
        1. Firstly, the labelled layout should be enabled for this setting to work in case of “Share Buttons and Automatic Share Flyins” widgets whereas in case of “Share Action Buttons” widget, it will work for all layouts of the buttons.<br />
        2. Secondly, this setting depends on the width of the column where the widget is placed. So, you need to enter the number keeping in mind the space available."); ?> 
      </div>
    </li>

  </ul>
</div>
			