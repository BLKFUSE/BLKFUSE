<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedactivity/views/scripts/dismiss_message.tpl';

?>
<div class="settings sesbasic_admin_form sesact_global_setting">
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<div class="sesbasic_waiting_msg_box" style="display:none;">
	<div class="sesbasic_waiting_msg_box_cont">
    <?php echo $this->translate("Please wait.. It might take some time to activate plugin."); ?>
    <i></i>
  </div>
</div>
<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.pluginactivated',0)){ ?>
	<script type="application/javascript">
  	scriptJquery(document).on('submit','.global_form',function(e){
			scriptJquery('.sesbasic_waiting_msg_box').show();
		});
  </script>
<?php }else{ ?>
<script type="text/javascript"> 
     scriptJquery('#sesadvancedactivity_composeroptions-element').find('ul').attr('id','composer_options');
   var SortablesInstance;
 
scriptJquery( window ).load(function() {
       /*SortablesInstance = new Sortables('composer_options', {
       clone: true,
       constrain: false,
       handle: '.item_label',
       onComplete: function(e) {
         reorder(e);
       }
     });*/
   });
 
  var reorder = function(e) {
      var menuitems = e.parentNode.childNodes;
      var ordering = {};
      var i = 1;
      for (var menuitem in menuitems)
      {
        var child_id = menuitems[menuitem].id;
 
        if ((child_id != undefined))
        {
          ordering[child_id] = i;
          i++;
        }
      }
   }
</script>
<script type="application/javascript">
scriptJquery(document).ready(function(e){
  var elem = scriptJquery('#sesadvancedactivity_composeroptions-element').find('ul').children();
  for(i=0;i<elem.length;i++){
    var value = scriptJquery(elem[i]).find('input').val();
    var label = scriptJquery(elem[i]).find('label').html();
    var html = label.split('|||');
    var splitZero = html[0];
    var splitOne = html[1];
    //scriptJquery(elem[i]).find('label').html(splitOne);
    //scriptJquery(elem[i]).append('<input type="text" name="composer-text['+value+']" value="'+splitZero+'">');  
  }  
})
function linkedin(value){
  if(value == 1){
    document.getElementById('sesadvancedactivity_linkedin_access-wrapper').style.display = 'block';		
    document.getElementById('sesadvancedactivity_linkedin_secret-wrapper').style.display = 'block';		
  }else{
    if(document.getElementById('sesadvancedactivity_linkedin_secret-wrapper')){
      document.getElementById('sesadvancedactivity_linkedin_secret-wrapper').style.display = 'none';		
      document.getElementById('sesadvancedactivity_linkedin_access-wrapper').style.display = 'none';
    }
  }
}
linkedin(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.linkedin.enable', 0); ?>);


ads(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.adsenable', 0); ?>);

repeatAds(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.adsrepeatenable', 0); ?>);
function hideShowPrivacySettings(value){
  if(value == 1){
      document.getElementById('sesadvancedactivity_allowlistprivacy-wrapper').style.display = 'block';	
		}else{
			document.getElementById('sesadvancedactivity_allowlistprivacy-wrapper').style.display = 'none';
		}
}
var valueSelectedPrivacy = "<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.allowprivacysetting', 1); ?>";
hideShowPrivacySettings(valueSelectedPrivacy);

function ads(value) {
  if(!document.getElementById('sesadvancedactivity_adcampaignid')){
    document.getElementById('sesadvancedactivity_adsenable-wrapper').style.display = 'none';
    document.getElementById('sesadvancedactivity_adsrepeatenable-wrapper').style.display = 'none';
    repeatAds(0);	
    return false;
  }
  if(value == 1){
    document.getElementById('sesadvancedactivity_adcampaignid-wrapper').style.display = 'block';
    document.getElementById('sesadvancedactivity_adsrepeatenable-wrapper').style.display = 'block';	
    repeatAds(1);
  }else{
    document.getElementById('sesadvancedactivity_adcampaignid-wrapper').style.display = 'none';
    document.getElementById('sesadvancedactivity_adsrepeatenable-wrapper').style.display = 'none';
    repeatAds(0);	
  }
  
}


function repeatAds(value){
  if(value == 1){
    document.getElementById('sesadvancedactivity_adsrepeattimes-wrapper').style.display = 'block';		
  }else{
    document.getElementById('sesadvancedactivity_adsrepeattimes-wrapper').style.display = 'none';		
  }
}


function showLanguage(value){
  if(value == 1){
    document.getElementById('sesadvancedactivity_language-wrapper').style.display = 'block';		
  }else{
    document.getElementById('sesadvancedactivity_language-wrapper').style.display = 'none';		
  }
}
showLanguage(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.translate', 0); ?>);
showBigText(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.bigtext', 1); ?>);
peopleymk(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.peopleymk', 1); ?>);
showcompletepost(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.showcompletepost', 0); ?>);


function showcompletepost(value) {
  if(value == 1) {
    if(document.getElementById('sesadvancedactivity_characterlimit-wrapper'))
      document.getElementById('sesadvancedactivity_characterlimit-wrapper').style.display = 'none';
  } else {
    if(document.getElementById('sesadvancedactivity_characterlimit-wrapper'))
      document.getElementById('sesadvancedactivity_characterlimit-wrapper').style.display = 'block';
  }
}

function showBigText(value) {
  if(value == 1) {
    if(document.getElementById('sesadvancedactivity_fonttextsize-wrapper'))
      document.getElementById('sesadvancedactivity_fonttextsize-wrapper').style.display = 'block';
    if(document.getElementById('sesadvancedactivity_textlimit-wrapper'))
      document.getElementById('sesadvancedactivity_textlimit-wrapper').style.display = 'block';
  } else {
    if(document.getElementById('sesadvancedactivity_fonttextsize-wrapper'))
      document.getElementById('sesadvancedactivity_fonttextsize-wrapper').style.display = 'none';
    if(document.getElementById('sesadvancedactivity_textlimit-wrapper'))
      document.getElementById('sesadvancedactivity_textlimit-wrapper').style.display = 'none';
  }
}

function peopleymk(value) {
  if(value == 1) {
    if(document.getElementById('sesadvancedactivity_peopleymkrepeattimes-wrapper'))
      document.getElementById('sesadvancedactivity_peopleymkrepeattimes-wrapper').style.display = 'block';
    if(document.getElementById('sesadvancedactivity_pymkrepeatenable-wrapper'))
      document.getElementById('sesadvancedactivity_pymkrepeatenable-wrapper').style.display = 'block';
  } else {
    if(document.getElementById('sesadvancedactivity_peopleymkrepeattimes-wrapper'))
      document.getElementById('sesadvancedactivity_peopleymkrepeattimes-wrapper').style.display = 'none';
    if(document.getElementById('sesadvancedactivity_pymkrepeatenable-wrapper'))
      document.getElementById('sesadvancedactivity_pymkrepeatenable-wrapper').style.display = 'none';
  }
}

</script>
<?php } ?>