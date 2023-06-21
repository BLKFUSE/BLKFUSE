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

<div class="egifts_list_popup">
  <form id="egiftpurchasegift" method="post" action="<?php echo $this->url(array('action' => 'purchasegift'),'egifts_general',true); ?>">
	<div class="egifts_list_popup_title">
  	<?php echo $this->translate('Choose Your gift');  ?>
  </div>
  <div class="egifts_list_popup_content sesbasic_bxs">
  	<p>Please select a gift</p>
    <ul class="egifts_list_popup_content_list">
        <input type="hidden" name="userid" value="<?php echo $this->userid; ?>">
    	<?php
     		$currency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency');
     		foreach ($this->giftlist as $giftlist) {
        $image_url='';
        if(isset($giftlist['icon_id']) && !empty($giftlist['icon_id']))
        {
        	$file = Engine_Api::_()->getItemTable('storage_file')->getFile($giftlist['icon_id'], null);
        	$image_url=$file->map();
        }
      ?>
      	<li class="egifts_list_popup_content_list_item">
        	<article>
          	<input type="checkbox" name="giftid[]" id="<?php echo $giftlist['gift_id'] ?>" value="<?php echo $giftlist['gift_id'] ?>" />
            <label for="<?php echo $giftlist['gift_id'] ?>">
              <div class="gift_img">
                <img src="<?php echo $image_url ?>" alt="" />
              </div>
              <div class="gift_title">
                <span><?php echo $giftlist['title'];  ?></span>
                (<?php echo $giftlist['price']." ".$currency;  ?>)
              </div>
              <span class="_checkmark sesbasic_bg"></span>
            </label>
          </article>
      	</li>
      <?php } ?>
    </ul>
    <div class="egifts_list_popup_content_option">
      <div class="_field"><textarea name="message" placeholder="<?php echo $this->translate('Type your message'); ?>"></textarea></div>
      <div class="_field">
      	<ul class="field_options">
        	<li><input type="radio" name="privacy" id="public" value="0"><label for="public"><b><?php echo $this->translate("Public"); ?></b> <span class="sesbasic_text_light"><?php echo $this->translate("Your name and message will be displayed to all"); ?></span></label></li>
          <li><input type="radio" name="privacy" id="private" value="1"><label for="private"><b><?php echo $this->translate("Private"); ?></b> <span class="sesbasic_text_light"><?php echo $this->translate("Your name and message will be visible to the recipient(s)"); ?></span></label></li>
        </ul>
      </div>
  	</div>
  </div>
  <div class="egifts_list_popup_footer">
    <button name="submit_check" id="submit_check" type="submit" style="display:none;"></button>
  	<button type="submit" id="submit"><?php echo $this->translate("Buy");?></button>
  </div>
 </form>
</div>
<script>
en4.core.runonce.add(function() {
  scriptJquery('#egiftpurchasegift').submit(function(e) {
    e.preventDefault();
    scriptJquery('#egiftpurchasegift input').each(function(index){
        if(scriptJquery('input[name="'+scriptJquery(this).attr('name')+'"]:checked').length > 0) {
          emptyFields = 1;
        } else {
          emptyFields = 0;
        }
    });
    if(emptyFields) {
      var formData = new FormData(this);
      scriptJquery.ajax({
            type:'POST',
            dataType:'html',
            url: scriptJquery('#egiftpurchasegift').attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(response){
              var data = scriptJquery.parseJSON(response);
              if(data.status)
                window.location.href = data.url;
              else 
                alert(en4.core.language.translate('Sorry! Somethings went wrong.'));
            },
            error: function(data){
              
            }
      });
    } else  {
      alert(en4.core.language.translate('Please Select any gift these are required.'));
    }
  });
});
</script>
