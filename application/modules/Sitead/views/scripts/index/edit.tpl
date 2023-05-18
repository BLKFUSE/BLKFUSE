<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit.tpl  2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>
<?php
//GET API KEY
$apiKey = Engine_Api::_()->seaocore()->getGoogleMapApiKey();
$this->headScript()->appendFile("https://maps.googleapis.com/maps/api/js?libraries=places&key=$apiKey");
?>
<?php include_once APPLICATION_PATH . '/application/modules/Sitead/views/scripts/_partialCreate.tpl'; ?>
<?php if(!Engine_Api::_()->seaocore()->checkModuleNameAndNavigation()):?>
<?php if (count($this->navigation)): ?>
 <?php $this->navigation()->menu()->setContainer($this->navigation)->render();?>
<?php endif; ?>
<?php endif; ?> 
<?php
$this->headLink()
->appendStylesheet($this->layout()->staticBaseUrl . 'externals/calendar/styles.css');
?>
<script type="text/javascript">
  function cancel(){
   url='<?php echo $this->url(array('ad_id' => $this->userAds_id), 'sitead_userad', true) ?>';

   parent.window.location.href=url;
 }
</script>
<?php  if( !empty($this->is_customAs_enabled) && !empty($this->is_moduleAds_enabled) ) { ?>
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
<?php } ?>

<script type="text/javascript">

  var is_edit_image =  '<?php echo $this->is_photo_id; ?>';
  function contenttype(contentKey) {
  }

  window.addEvent('domready', function() {
    var ad_format = '<?php echo $this->ads_format ?>';
    var slides = '<?php echo $this->slides_count ?>';
    var checkSlide = <?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.slide.limit', 2) ?>;
    if(slides > checkSlide)
      slides = checkSlide; 
    $('campaign_id-wrapper').style.display = 'none';
    $('campaign_name').value =  $('campaign_id').getSelected()[0].innerHTML;
    $('continue_review').style.display = 'none';
    $('overlay-wrapper').style.display = 'none';
    if(ad_format == 'carousel') {
      for(i = 1; i <= 9; i++) {
        $('sitead_display_' + i).style.display = 'none';
        $('ads_' + i + '-enable').value = 0;
        $('overlay-wrapper').style.display = 'block';
      }
      if(checkSlide == 2)
        $('button_add').hide();
    }
    
    $('sitead_display_1').style.display = 'block';
    for (var i = 1; i <= slides; i++) {
     $('ads_' + i + '-enable').value = 1;
   }


   if($m("enable_end_date").checked)
    enableEndDate();

      // Hide Audience Heading if no Target Selected
      <?php if($this->showTargetingTitle == 0) : ?>
        $('ad_heading_targeting-wrapper').style.display = 'none';
      <?php endif ?>

      if($('is_edit_content').value != 0){
        var subcatss = '<?php echo $this->edit_sub_title; ?>' . split("::");
        addOption($('title')," ", '0');
        for (var i=0; i < subcatss.length;++i){
          var subcatsss = subcatss[i].split("_");
          addOption($('title'), subcatsss[0], subcatsss[1]);
        }
        $('title').value = $('resource_id').value;
        if( $('resource_type') && $('resource_id') ) {
          $('create_feature').value = $('resource_type').value;
          $('content_page').value = 1;
        }
      }
      profileFields('<?php echo  $this->profileSelect_id ?>');
      <?php if($this->noProfile) : ?>
       $('field_profile').hide();
     <?php endif; ?>


     $('continue_1').addEvent("click", function(e){
      var flage = true;
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
      if ($('imageenable').value == 0 ) {
        if($('Filedata_10').files.length == 0){
         if(!$('validation_Filedata_10')){
          var div_cads_url = $('Filedata_10').parentNode;
          var myElement = new Element("p");
          myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please choose a card image for ad.')); ?>';  
          myElement.addClass("error");
          myElement.id = "validation_Filedata_10";
          div_cads_url.appendChild(myElement);
        }
        validationFlage=1;
      }
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

     $('imageenable').value=1;
      // if( is_edit_image != 0 ) {
        $('ad_icon').innerHTML = ('<?php echo $this->itemPhoto($this->userAds, '', '' , array()); ?>');
        var i = 1;
        <?php foreach ($this->useradsinfo_array as  $useradsinfo): ?>
          if($('cmd_ad_format').value == 'video') {
           $('video_test').src = '<?php echo $this->video_location ?>';
         } else  {
           if(i <=  slides) {
             <?php if (empty($useradsinfo['type'])) { ?>
               $('ad_photo_'+ i).innerHTML = ('<?php echo $this->htmlImage($useradsinfo->getIconUrl()); ?>');
             <?php } ?>
             i++;
            } else { 
            $('ad_photo_10').innerHTML = ('<?php echo $this->htmlImage($useradsinfo->getIconUrl()); ?>');
            $('card_link').innerHTML = $('card_title').value;
            $('sitead_endcard').style.display = 'block';
           }
        }

      <?php endforeach ?>
     // } 

     $('ad_name').innerHTML ='<a href="javascript:void(0);">'+$('web_name').value+'</a>';

     var titlefields = $$(".ads_name");
     var num =1;
     titlefields.each(function(i, field) {
      var res = (i.id).split('-');
      var dec = res[0];
      if($(dec + '-enable').value == 1) {
        $('ad_title_' + num).innerHTML = '<a href="javascript:void(0);" >'+ i.value+'</a>';
      }
      num++;
    });

     var descfields = $$(".ads_desc");
     var num = 1; 
     descfields.each(function(i, field) {
      var res = (i.id).split('-');
      var dec = res[0];
      if($(dec + '-enable').value == 1) {
        $('ad_body_'+ num).innerHTML = i.value;
      }
      num++;
    });

     var ctafields = $$(".ads_cta");
     var num = 1; 
     ctafields.each(function(i, field) {
      var res = (i.id).split('-');
      var dec = res[0];
      if($(dec + '-enable').value == 1) {
        $('call_to_action_' + num).innerHTML = '<a href="javascript:void(0);" >' + i.value + '</a>';
        if(i.value != 0) {
          $('call_to_action_' + num).style.display = 'block';
        }
      }
      num++;
    });

     <?php if($this->ads_format == 'carousel') : ?>
       var overlayfields = $$('.ads_overlay');
       var num = 1; 
       overlayfields.each(function(i, field) {
        var res = (i.id).split('-');
        var dec = res[0];
        if($(dec + '-enable').value == 1) {
          $('ad_overlay_' + num).innerHTML = '<a href="javascript:void(0);" >' + i.value + '</a>';
          if(i.value != 0) {
            $('ad_overlay_' + num).style.display = 'block';
          }
        }
        num++;
      });
     <?php endif;?> 

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
       else
       { 
         $('ad_body_' + path).innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate("Description")) ?>';
       }
     }
   });

     $('enable_end_date').addEvent('click', function() {
       enableEndDate();
     });
   });


function imposeMaxLength(Object, MaxLen)
{ 
  return (Object.value.length <= MaxLen);
}

function removeImage(){
  $m('ad_photo').innerHTML='<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" />';
  $('imageName').value='';
  $('image').value='';
  $('imageenable').value=0;
  $("remove_image_link").style.display='none';
}


function nameTextLimt(thisName){
  var maxTitle=<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.char.title', 25) ?>;
  var text = thisName.value;
  title_count=maxTitle-text.length;
  if( $('profile_address_text') ) {
    if(title_count>=0)
    {
     $('profile_address_text').innerHTML = title_count;
   }
   else
   {
     $('profile_address_text').innerHTML="0";
   }
 }
}


</script> 

<div class="settings" id="createaddiv"  ">
  <div class="create-ad">
    <div class="create-ad-form">
      <?php echo $this->form ?>
      <div style="margin-left:20px;" > <button id="continue_1" style="float: left;"><?php echo $this->translate("Save") ?></button>
       <div style="margin-top: 5px;margin-left: 5px; float: left;" > <?php echo $this->translate(" or ") ?><a href="javascript:void(0);" onclick= "cancel()"><?php echo $this->translate("Cancel") ?></a> </div>
     </div>
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

 function checkValidation() {

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

if ($('content_page').value == 0 && $('imageenable').value == 0) {
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
  myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please enter website url.')); ?>';    myElement.addClass("error");
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

if ($('imageenable').value == 0 ) {
  imgfields.each(function(i, field) {
    var res = (i.id).split('_');
    var num = res[1];
    if($('ads_' + num +  '-enable').value == 1) {
      if(i.files.length == 0){   
       if(!$('validation_' + i.id)){
        var div_cads_url = i.parentNode;
        var myElement = new Element("p");
        myElement.innerHTML = '<?php echo $this->string()->escapeJavascript($this->translate('Please choose a image for your ad.')); ?>';    
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
}

if(validationFlage==1){
  return false;
}
return true;
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
    else{
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

function videoupload(event) {
 var id = event.target.id;
 if($('validation_' + id)){
  event.target.parentNode.removeChild($('validation_' + id));
}
var _validFileExtensions = ["mp4"]; 
var sFileName =  event.target.files[0].type;
var sFileSize =  event.target.files[0].size;
var maxFileSize = 1024 * 1024 * <?php echo (int) ini_get('upload_max_filesize') ?>;

for (var j = 0; j < _validFileExtensions.length; j++) {
  var sCurExtension = _validFileExtensions[j];
  if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
    fileValid = true;
  }
}
if (!fileValid) {
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
  var maxFileSize = 1024 * 1024 * <?php echo (int) ini_get('upload_max_filesize') ?>; // 1MB -> 1024 * 1024

        for (var j = 0; j < _validFileExtensions.length; j++) {
          var sCurExtension = _validFileExtensions[j];
          if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
            validIcon = false;
          }
        }
        if (!fileValid) {
          var div_image = document.getElementById("web_icon-element");
          var myElement = new Element("p");
          myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Sorry uploaded image extension  is invalid.")) ?>";
          myElement.addClass("error");
          myElement.id = "validation_web_icon";
          div_image.appendChild(myElement);
          return false;
        } 
        
        if(sFileSize > maxFileSize) {
          maxFileSize = maxFileSize / 1000 * 1024;
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

      function imageupload(event)
      { 

        var id = event.target.id;
        var num = id.split('_')[1];

        if($('ads_' + num + '-enable')) {
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
          maxFileSize = maxFileSize / 1000 * 1024;
          var div_image = event.target.parentNode;
          var myElement = new Element("p");
          myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("Sorry uploaded image size not more than ")) ?>";
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

    function isUrl(s) {
      var regexp = /(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/

      return regexp.test(s);
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