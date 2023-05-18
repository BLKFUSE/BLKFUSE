<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: create.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<?php
//GET API KEY
$apiKey = Engine_Api::_()->seaocore()->getGoogleMapApiKey();
$this->headScript()->appendFile("https://maps.googleapis.com/maps/api/js?libraries=places&key=$apiKey");
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/_partialCreate.tpl'; ?>
<?php if($this->limitreached == 1 ) : ?>
<?php
$this->headLink()
        ->appendStylesheet($this->layout()->staticBaseUrl . 'externals/calendar/styles.css');
?>
<?php $ctaCategories = Engine_Api::_()->getDbtable('categories', 'sitead')->getCategoriesAssoc(); ?>

<style type="text/css">
#create_feature-wrapper,
#cads_url-wrapper
{
  overflow:visible;
  margin-top:20px;
}
#create_feature-wrapper .form-element a,
#cads_url-wrapper .form-element a
{
  margin-bottom:10px;
  float:left;
}
</style>

<script type="text/javascript">
// Function: Default when page refresh then 'page url options' should be on form. 
window.addEvent('domready', function() {
  var ad_format = '<?php echo $this->ads_format ?>';
  var checkSlide = <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.slide.limit', 2) ?>;
  $('sitead_display_1').style.display = 'block';
  $('overlay-wrapper').style.display = 'none';
  $('ads_1-enable').value = 1;
  if(ad_format == 'carousel') {
    $('ads_2-enable').value = 1;
    $('overlay-wrapper').style.display = 'block';
     if(checkSlide == 2)
    $('button_add').hide();
  } 

  $('continue_title').addEvent('click', function(){ 
   var flage = checkValidationCampaign();

   if(!flage)
    return flage;
  var tempid = $('temp_campaign_id').value;
  var tempname = $('temp_campaign_name').value;
  $('campaign_name').value = tempname;
  $('campaign_id').value = tempid;
  $('campaign_id-wrapper').style.display = 'none';
  $('campaigindiv').style.display='none';
  if($('cmd_ad_type').value == 'boost') { 
    $('feeddiv').style.display = 'block';
    return;
  };
  $('createaddiv').style.display='block';
});

$$('.profile-select').addEvent('click',function() {
  $$('.profile-select').removeClass('selected');
    this.addClass('selected');
});
  profileFields('<?php echo  $this->profileSelect_id ?>');
   <?php if($this->noProfile) : ?>
     $('field_profile').hide();
   <?php endif; ?>

  <?php if($this->showTargetingTitle == 0) : ?>
    $('ad_heading_targeting-wrapper').style.display = 'none';
  <?php endif ?>

  $('continue_review').addEvent("click", function(e){

    var flage = true;
    if($('cmd_ad_type').value != 'boost')
      var flage = checkValidation();
    if ($('cads_end_date-date').value == "" && !$m("enable_end_date").checked )
    {
      if(!$('validation_cads_end_date-element')){
        var div_cads_end_date = document.getElementById("cads_end_date-element");
        var myElement = new Element("p");
        myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("End Date not selected.")) ?>';
        myElement.addClass("error");
        myElement.id = "validation_cads_end_date-element";
        div_cads_end_date.appendChild(myElement);
      }
      flage=false;
    }

    if($('cmd_ad_format').value == 'carousel'){
      if($('show_card').checked) {
        if($('card_title').value == "") {
          if(!$('validation_card_title-element')){
            var div_card_title = document.getElementById("card_title-element");
            var myElement = new Element("p");
            myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("Please add Card display Link.")) ?>';
            myElement.addClass("error");
            myElement.id = "validation_card_title-element";
            div_card_title.appendChild(myElement);
          }
          flage=false;
        }

        if( !isUrl($('card_url').value)) {
         if(!$('validation_card_url-element')){
          var div_card_url = document.getElementById("card_url-element");
          var myElement = new Element("p");
          myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("Card Link URL must be a valid page")) ?>';
          myElement.addClass("error");
          myElement.id = "validation_card_url-element";
          div_card_url.appendChild(myElement);
        }
        flage=false;
      }

      if($('Filedata_10').files.length == 0) {
        if(!$('validation_Filedata_10')){
          var div_cads_url = $('Filedata_10').parentNode;
          var myElement = new Element("p");
          myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please choose a card image for ad.')); ?>';  
          myElement.addClass("error");
          myElement.id = "validation_Filedata_10";
          div_cads_url.appendChild(myElement);
        }
        flage=false;
      }
    }
  }
  if(!flage) {
    if($('cmd_ad_format').value == 'carousel') {
      alert('Please check all the required fields in enabled slides.');
    }
    return flage;
  }
  $('wholeform').submit();
});

  $('web_name').addEvent('keyup', function() {
    if($('validation_web_name')){
      document.getElementById("web_name-element").removeChild($('validation_web_name'));
    }
    if( this.value != '' ){
      body = this.value;
    }
    else{
      body =  '<?php echo $this->string()->escapeJavascript($this->translate("Ad Name")) ?>';
    }
    $('ad_name').innerHTML = body;
  });

  $('web_url').addEvent('keyup', function() {
    if($('validation_web_url')){
      document.getElementById("web_url-element").removeChild($('validation_web_url'));
    }
  });

  $$('.ads_url').addEvent('keyup', function(){
   var id = $(this).id;
   if($('validation_' + id)){
    $(this).parentNode.removeChild($('validation_' + id));
  }
});

  $$('.ads_name').addEvent('keyup', function(){
   var title = '';
   var id = $(this).id;
   if($('validation_' + id)){
    $(this).parentNode.removeChild($('validation_' + id));
  }
  var res = id.split('-');
  var num = res[0];
  var path = num.split('_')[1];
  if($(num + '-enable').value == 1) {
   if( $(this).value != '' ){
    title =$(this).value;
    var maxSizeTitle= <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.title', 25); ?>;
    if(title.length>maxSizeTitle)
      { title = title.substring(0,maxSizeTitle);
       $(this).value=title.substring(0,maxSizeTitle);
     }
     $('ad_title_' + path).innerHTML = '<a href="javascript:void(0);" >'+title +'</a>';
   }
   else
   { 
     $('ad_title_' + path).innerHTML = title = '<a href="javascript:void(0);" >'+'<?php echo $this->string()->escapeJavascript($this->translate("Ad Title")) ?>'+'</a>';
   }
 }
});

  $$('.ads_desc').addEvent('keyup', function(){
   var body = ''; 
   var id = $(this).id;
   if($('validation_' + id)){
    $(this).parentNode.removeChild($('validation_' + id));
  }
  var res = id.split('-');
  var num = res[0];
  var path = num.split('_')[1];
  if($(num + '-enable').value == 1) {
   if( $(this).value != '' ){
    body =$(this).value;
    var maxSize= <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.body', 135); ?>;
    if(body.length>maxSize)
      { body = body.substring(0,maxSize);
       $(this).value=body.substring(0,maxSize);
     }
     $('ad_body_' + path).innerHTML = body;
   }
   else { 
     $('ad_body_' + path).innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("Description")) ?>';
   }
 }
});
  if($m("enable_end_date").checked)
        enableEndDate();

  $('enable_end_date').addEvent('click', function() {
     enableEndDate();
  });

  function checkValidationCampaign() {
    var validationFlage=0;
    var adcampaign = document.getElementById("temp_campaign_id");
    var namewrapper = document.getElementById("temp_campaign_id-wrapper");
    var name = document.getElementById("temp_campaign_name");

    if (adcampaign.value == 0 && name.value=='')
    {
      if(!$('validation_temp_campaign_name')){
        var div_campaign_name = document.getElementById("temp_campaign_name-element");
        var myElement = new Element("p");
        myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("Please enter a campaign name.")) ?>';
        myElement.addClass("error");
        myElement.id = "validation_temp_campaign_name";
        div_campaign_name.appendChild(myElement);
      }
      validationFlage=1;
    }
    if(validationFlage==1){
      return false;
    }
    return true;
  }

  function checkValidation(){

    var validationFlage=0;
    var adcampaign = document.getElementById("campaign_id");
    var namewrapper = document.getElementById("campaign_name-wrapper");
    var name = document.getElementById("campaign_name");
    var titlefields = $$(".ads_name");
    var urlfields = $$(".ads_url");
    var descfields = $$(".ads_desc");
    var imgfields = $$(".ads_file");

  if (adcampaign.value == 0 && name.value=='') {
      if(!$('validation_campaign_name')){
        var div_campaign_name = document.getElementById("campaign_name-element");
        var myElement = new Element("p");
        myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("Please enter a campaign name.")) ?>';
        myElement.addClass("error");
        myElement.id = "validation_campaign_name";
        div_campaign_name.appendChild(myElement);
      }
      validationFlage=1;
    }

  if($('cmd_ad_type').value == 'content' || $('cmd_ad_type').value == 'page') {
    if($('create_feature').value == 0) {
    if(!$('validation_title')) {
      $('create_feature-label').style.display = 'inline-block';
      var div_cads_body = document.getElementById("create_feature-element");
      var myElement = new Element("p");
      myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Please select the content type you want to advertise.")); ?>";
      myElement.addClass("error");
      myElement.id = "validation_title";
      div_cads_body.appendChild(myElement);
      }
    }

    if($('title').value == 0) {
    if(!$('validation_subtitle')) {
      $('title-label').style.display = 'inline-block';
         var div_cads_body = document.getElementById("title-element");
         var myElement = new Element("p");
            myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Please select the content you want to advertise.")); ?>";
          myElement.addClass("error");
          myElement.id = "validation_subtitle";
          div_cads_body.appendChild(myElement);
      }
    }
  }

  if($('web_name').value == '') {
     if(!$('validation_web_name')){
      var div_cads_url = document.getElementById("web_name-element");
      var myElement = new Element("p");
      myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please enter a ad name.')); ?>';    myElement.addClass("error");
      myElement.id = "validation_web_name";
      div_cads_url.appendChild(myElement);
    }
    validationFlage=1;
  }

if ($('content_page').value == 0) {
  if($('web_icon').files.length == 0){
   if(!$('validation_web_icon')){
    var div_cads_url = $('web_icon').parentNode;
    var myElement = new Element("p");
    myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please choose a website icon for ad.')); ?>';  
    myElement.addClass("error");
    myElement.id = "validation_web_icon";
    div_cads_url.appendChild(myElement);
  }
  validationFlage=1;
 } else {
  if(validIcon)
    validationFlage=1;
 }
} 

if($('content_page').value == 0 && !isUrl($('web_url').value)){
 if(!$('validation_web_url')){
  var div_cads_url = document.getElementById("web_url-element");
  var myElement = new Element("p");
  myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Website URL must be a valid web page.')); ?>';    myElement.addClass("error");
  myElement.id = "validation_web_url";
  div_cads_url.appendChild(myElement);
}
validationFlage=1;
} 

urlfields.each(function(i, field) {
  if($('content_page').value == 0 && !isUrl(i.value)) {
    var res = (i.id).split('-');
    var num = res[0];
    if($(num + '-enable').value == 1) {
     if(!$('validation_' + i.id)){
      var div_cads_url = i.parentNode;
      var myElement = new Element("p");
      myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Destination URL must be a valid web page.')); ?>';    
      myElement.addClass("error");
      myElement.id = 'validation_' + i.id;
      div_cads_url.appendChild(myElement);
    }
    validationFlage=1;
  }
}
});

titlefields.each(function(i, field) {
  var res = (i.id).split('-');
  var num = res[0];
  if($(num + '-enable').value == 1) {
    if(i.value == ''){ 
     if(!$('validation_' + i.id)){
      var div_cads_url = i.parentNode;
      var myElement = new Element("p");
      myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please enter a title for your ad.')); ?>';    
      myElement.addClass("error");
      myElement.id = 'validation_' + i.id;
      div_cads_url.appendChild(myElement);
    }
    validationFlage=1;
  }
}
});

descfields.each(function(i, field) {
  var res = (i.id).split('-');
  var num = res[0];
  if($(num + '-enable').value == 1) {
    if(i.value == ''){   
     if(!$('validation_' + i.id)){
      var div_cads_url = i.parentNode;
      var myElement = new Element("p");
      myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please enter description for your ad.')); ?>';    
      myElement.addClass("error");
      myElement.id = 'validation_' + i.id;
      div_cads_url.appendChild(myElement);
    }
    validationFlage=1;
  }
}
});

imgfields.each(function(i, field) {
  var res = (i.id).split('_');
  var num = res[1];
  if($('ads_' + num +  '-enable').value == 1) {
    if(i.files.length == 0){   
     if(!$('validation_' + i.id)){
      var div_cads_url = i.parentNode;
      var myElement = new Element("p");
      myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please choose a image for your ad.')); ?>';  
      <?php if($this->ads_format == 'video') { ?>
        myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please choose a video for your ad.')); ?>';
      <?php } ?>
      myElement.addClass("error");
      myElement.id = 'validation_' + i.id;
      div_cads_url.appendChild(myElement);
    }
    validationFlage=1;
  } else {
  if(fileValid)
    validationFlage=1;
 }
}
});

if(validationFlage==1){
  return false;
}
return true;
  }
});

function backFeed(){
  $('campaigindiv').style.display='block';
  if($('cmd_ad_type').value == 'boost') { 
    $('feeddiv').style.display = 'none';
    return;
  };
}
</script>

<div id="campaigindiv">
  <?php echo $this->campform ?>
</div>
<?php if($this->ads_Type == 'boost') :?>
 <div id="feeddiv" style="display: none" >
    <?php include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/_boostActionsList.tpl'; ?>
    <div id="slct_type_next"> 
      <button id="back_ad_type" onclick="backFeed()"><?php echo $this->translate('Back'); ?></button>
      <button onclick="getBoostPostFeed()"><?php echo $this->translate('Next'); ?></button>
    </div>
  </div>
<?php endif; ?>
<div class="settings" id="createaddiv"  style="display: none;">
  <div class="create-ad">
    <div class="create-ad-form">
      <?php echo $this->form ?>
    </div>
    <div class="ad-preview" id="ad_preview" >
       <div class="ad_preview_wpr">
      <h3><?php echo $this->translate('Preview');
       ?></h3>
       <div id="ad_preview_in_wrp">
      <?php include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/_cardPictureImage.tpl'; ?>
    </div>
  </div>
    </div>
  </div>
</div>

<script type="text/javascript">

  function isUrl(s) {
    var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    return regexp.test(s);
  }

  var validIcon = true;
  var fileValid = true;
  function iconupload(event){
    $('ad_icon').innerHTML = '<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />'
    if($('validation_web_icon')){
      document.getElementById("web_icon-element").removeChild($('validation_web_icon'));
    }

    var _validFileExtensions = ["jpg", "jpeg", "png"]; 
    var sFileName =  event.target.files[0].type;
    var sFileSize =  event.target.files[0].size;
    var maxFileSize = 1024 * 1024 * <?php echo (int) ini_get('upload_max_filesize') ?>; // 1MB -> 1000 * 1024

        for (var j = 0; j < _validFileExtensions.length; j++) {
          var sCurExtension = _validFileExtensions[j];
          if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
            validIcon = false;
          }
        }
        if (validIcon) {
          var div_image = document.getElementById("web_icon-element");
          var myElement = new Element("p");
          myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Sorry uploaded image extension  is invalid.")) ?>";
          myElement.addClass("error");
          myElement.id = "validation_web_icon";
          div_image.appendChild(myElement);
          return false;
        } 
        
        if(sFileSize > maxFileSize) {
          maxFileSize = maxFileSize / (1000 * 1024);
          var div_image = document.getElementById("web_icon-element");
          var myElement = new Element("p");
          myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Sorry uploaded image size not more than accepted size ")) ?>" + maxFileSize + ' MB';
          myElement.addClass("error");
          myElement.id = "validation_web_icon";
          div_image.appendChild(myElement);
          validIcon = true;
          return false;
        }
        
        var reader = new FileReader();
        reader.onload = function()
        {
          var output = document.getElementById('ad_icon');
          output.innerHTML = "<img src=" + reader.result + ">"
        }
        reader.readAsDataURL(event.target.files[0]);
        return false;
      }

      function imageupload(event) { 
        var id = event.target.id;
        var num = id.split('_')[1];
        if($('ads_' + num + '-enable') || num == 10) {
          $('ad_photo_' + num).src = '<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />'
          if($('validation_' + id)){
            event.target.parentNode.removeChild($('validation_' + id));
          }

          var _validFileExtensions = ["jpg", "jpeg", "png"]; 
          var sFileName =  event.target.files[0].type;
          var sFileSize =  event.target.files[0].size;
          var maxFileSize = 1024 * 1024 * <?php echo (int) ini_get('upload_max_filesize') ?>; // 1MB -> 1024 * 1024

        for (var j = 0; j < _validFileExtensions.length; j++) {
          var sCurExtension = _validFileExtensions[j];
          if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
            fileValid = false;
          }
        }
        if (fileValid) {
          var div_image = event.target.parentNode;
          var myElement = new Element("p");
          myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Sorry uploaded image extension  is invalid.")) ?>";
          myElement.addClass("error");
          myElement.id = "validation_" + id;
          div_image.appendChild(myElement);
          return false;
        } 
        
        if(sFileSize > maxFileSize) {
          var div_image = event.target.parentNode;
          var myElement = new Element("p");
          myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Sorry uploaded image size not more than accepted size ")) ?>" + maxFileSize + ' MB';
          myElement.addClass("error");
          myElement.id = "validation_" + id;
          div_image.appendChild(myElement);
          fileValid = true;
          return false;
        } 
        
        var reader = new FileReader();
        reader.onload = function()
        {
          var output = document.getElementById('ad_photo_' + num);
          output.innerHTML = "<img src=" + reader.result + ">"
        }
        reader.readAsDataURL(event.target.files[0]);
      }
      return false;
    }

    function videoupload(event) {
     var id = event.target.id;
     if($('validation_' + id)){
      event.target.parentNode.removeChild($('validation_' + id));
    }
    var _validFileExtensions = ["mp4"]; 
    var sFileName =  event.target.files[0].type;
    var sFileSize =  event.target.files[0].size;
    var maxFileSize = 1024 * 1024 * <?php echo (int) ini_get('upload_max_filesize') ?>; // 1MB -> 1024 * 1024

    for (var j = 0; j < _validFileExtensions.length; j++) {
      var sCurExtension = _validFileExtensions[j];
      if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
        fileValid = false;
      }
    }
    if (fileValid) {
      var div_image = event.target.parentNode;
      var myElement = new Element("p");
      myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Sorry uploaded video extension  is invalid.")) ?>";
      myElement.addClass("error");
      myElement.id = "validation_" + id;
      div_image.appendChild(myElement);
      return false;
    } 

    if(sFileSize > maxFileSize) {
          maxFileSize = maxFileSize / (1024*1024);
          var div_image = event.target.parentNode;
          var myElement = new Element("p");
          myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Sorry uploaded video size not more than accepted size ")) ?>" + maxFileSize + ' MB';
          myElement.addClass("error");
          myElement.id = "validation_" + id;
          div_image.appendChild(myElement);
          fileValid = true;
          return false;
    } 
    var reader = new FileReader();
    reader.onload = function()
    {
      var output = document.getElementById('video_test');
      output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
    return false;
  }

  var calltoaction = function(event) {
    var id = event.target.id;
    var res = id.split('-');
    var num = res[0];
    var path = num.split('_')[1];
    if($(num + '-enable')) {
      var body = '';
      $('call_to_action_' + path).style.display = 'none';
      if(event.target.value != '0' ){
        body = event.target.value;
        $('call_to_action_' + path).style.display = 'block';
      }
      else {
        body =  '<?php echo $this->string()->escapeJavascript($this->translate("No Button")) ?>';
      }
      $('call_to_action_' + path).innerHTML = '<a href="javascript:void(0);" >' + body + '</a>';
    }      
  }

    var setOverlay = function(event) {
      var id = event.target.id;
      var res = id.split('-');
      var num = res[0];
      var path = num.split('_')[1];
      if($(num + '-enable')) {
        var body = '';
        $('ad_overlay_' + path).style.display = 'none';


        if(event.target.value != '0' ){
          body = event.target.value;
          $('ad_overlay_' + path).style.display = 'block';

        }
        else{
          body =  '<?php echo $this->string()->escapeJavascript($this->translate("No Overlay")) ?>';
        }
        $('ad_overlay_' + path).innerHTML =  body;
      }      
    }

function getBoostPostFeed() {
  var actionId = $('action_id').value;
  if(actionId == '' || actionId == 'undefined') {
    alert("Select a activity first which you want to boost");
  return;
}
   en4.core.request.send(new Request.HTML({
          'url' : en4.core.baseUrl+'sitead/index/get-boost-post-feed',
          'data' : {
            format : 'html',
            'id': actionId,
          },
          onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
            //$('campaign_id-wrapper').style.display = 'none';
            $('feeddiv').style.display = 'none';
            $('sitead_adinfo').style.display = 'none';
            $('ad_preview_in_wrp').set('html', responseHTML);
            $('ad_preview_in_wrp').addClass('ad_preview_in_wrp boost');
            if($('sitead_display_1'))
              $('sitead_display_1').style.display = 'none';
            if($('sitead_display_2'))
              $('sitead_display_2').style.display = 'none';
            $('ad_heading_design-wrapper').style.display = 'none';
            $('ad_heading_top-wrapper').hide();
            $('createaddiv').style.display = 'block';
            $('resource_id').value = $('action_id').value;
            $('resource_type').value = 'boost';
        }
        }));
}
</script>



<script type="text/javascript">
  window.addEvent('domready', function() {
        
        if(document.getElementById('location') && (('<?php echo !Engine_Api::_()->getApi('settings', 'core')->getSetting('seaocore.locationspecific', 0);?>') || ('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('seaocore.locationspecific', 0);?>' && '<?php echo !Engine_Api::_()->getApi('settings', 'core')->getSetting('seaocore.locationspecificcontent', 0); ?>'))){
          var autocompleteSECreateLocation = new google.maps.places.Autocomplete(document.getElementById('location'));
          <?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/location.tpl'; ?>
        }
    });
</script>
<?php else : ?>
  <?php if(!Engine_Api::_()->seaocore()->checkModuleNameAndNavigation()):?>
         <?php if (count($this->navigation)): ?>
            <?php
             $this->navigation()->menu()->setContainer($this->navigation)->render();
          ?>
      <?php endif; ?>
<?php endif; ?> 
 <div class="tip">
              <span>
                <?php
                echo $this->translate("You have reached maximum no of Ads create in this package. Please click %1shere%2s to contact sales team for advertising.", "<a href= '". $this->url(array('page_id' => 4), 'sitead_help', true)."' >", "</a>");
                ?>
              </span>
            </div> 
<?php endif; ?>