<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: send-gift.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<div class="egifts_list_popup egifts_send_gift_popup">
	<div class="egifts_list_popup_title">
  	<?php echo $this->translate('Send Gift');  ?>
  </div>
  <form id="egiftpurchasegift" method="post" action="<?php echo $this->url(array('action' => 'purchasegift'),'egifts_general',true); ?>">
  <div class="egifts_list_popup_content sesbasic_bxs">
    <div class="egifts_list_popup_content_option">
    	<div class="_field">
      	<p class="_label"><?php echo $this->translate("Please select a member for sending gift"); ?></p>
        <input type="hidden" name="giftid" id="<?php echo $this->giftid; ?>" value="<?php echo $this->giftid; ?>" />
        <input type="text" id="search" placeholder="Search Member" value="" />
        <div class="_memberslist clear" id="user_results">
        </div>
      </div>
     	<div class="_field">
      	<textarea name="message" placeholder="<?php echo $this->translate('Type your message'); ?>"></textarea>
     	</div>
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
          else if(data.message) 
            alert(en4.core.language.translate(data.message));
          else
            alert(en4.core.language.translate('Sorry! Somethings went wrong.'));
        },
        error: function(data){
          
        }
      });
    });
  });

  var Searchurl = "<?php echo $this->url(array('action' => 'get-user'),'egifts_general',true); ?>";
  en4.core.runonce.add(function() {
    AutocompleterRequestJSON('search', Searchurl, function(selecteditem) {
      var shareItem = selecteditem;
      scriptJquery('#user_results').html("");
      var shareItemmyElementPrivate = '<div class="selected_member sesbasic_lbg"><input type="hidden" required name="userid" class="selectmember" value="'+shareItem.id+'" /><div class="_thumb">'+shareItem.image+'</div><div class="_name">'+shareItem.label+'</div></div>';
      scriptJquery('#user_results').append(shareItemmyElementPrivate);
      scriptJquery('#search').val('');
      scriptJquery('#user_results').children().find('.Pitts').removeClass('highlist');
    });
  });
</script>
