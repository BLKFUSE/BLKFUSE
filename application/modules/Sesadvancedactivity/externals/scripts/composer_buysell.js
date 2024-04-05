/* $Id:composer_buysell.js  2017-01-12 00:00:00 SocialEngineSolutions $*/

(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.Buysell = function(options){

  this.__proto__ = new Composer.Plugin.Interface(options);

  this.name = 'buysell'

  this.options = {
    title : en4.core.language.translate("Sell Something"),
    lang : {},
    // Options for the link preview request
    requestOptions : {},
    debug : false
  }

  this.initialize = function(options) {
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,scriptJquery.extend(options,this.__proto__.options));
  }

  this.attach = function() {
    this.__proto__.attach.call(this);
    this.makeActivator();
    return this;
  }

  this.detach = function() {
    this.__proto__.detach.call(this);
    if( this.interval ) $clear(this.interval);
    return this;
  }

  this.activate = function() {
    if( this.active ) return;
    this.__proto__.activate.call(this);

    this.makeMenu();
    this.makeBody();
    
    var title = '<div class="sesact_sell_composer"><div class="sesact_sell_composer_title"><input type="text" id="buysell-title" placeholder="'+ en4.core.language.translate("What are you selling?")+'" name="buysell-title"><span id="buysell-title-count" class="sesbasic_text_light">100</span></div>';
    var wheretobuy = '<div class="sesact_sell_composer_title"><input type="text" id="buy-url" placeholder="'+ en4.core.language.translate("Where to Buy (URL Optional)")+'" name="buy-url"></div>';
    var price = '<div class="sesact_sell_composer_price"><span class="sesact_sell_composer_price_currency sesbasic_text_light">'+this.__proto__.options.currency+'</span><span class="sesact_sell_composer_price_input"><input type="text" id="buysell-price" placeholder="'+ en4.core.language.translate("Add price")+'" name="buysell-price"></span></div>';
    var location = '<div class="sesact_sell_composer_location"><i class="sesbasic_text_light fas fa-map-marker-alt"></i><span id="locValuesbuysell-element"></span><span id="buyselllocal"><input type="text" id="buysell-location" placeholder="'+ en4.core.language.translate("Add location (optional)")+'" name="buysell-location"><input type="hidden" name="activitybuyselllng" id="activitybuyselllng"><input type="hidden" name="activitybuyselllat" id="activitybuyselllat"></span></div>';
    var description = '<div class="sesact_sell_composer_des"><textarea id="buysell-description" placeholder="'+ en4.core.language.translate("Describe your item (optional)")+'" name="buysell-description"></textarea></div></div>';
    scriptJquery(this.elements.body).html(title+wheretobuy+price+location+description);
    if(this.__proto__.options.photoUpload){
     scriptJquery(this.elements.body).append('<input type="file" accept="image/x-png,image/jpeg" onchange="readImageUrlbuysell(this)" multiple="multiple" id="file_multi" name="file_multi" style="display:none"><div class="advact_compose_photo_container sesbasic_custom_horizontal_scroll sesbasic_clearfix"><div id="advact_compose_photo_container_inner" class="sesbasic_clearfix"><div id="show_photo"></div><div id="dragandrophandlersesbuysell" class="advact_compose_photo_uploader" title="'+ en4.core.language.translate("Choose a file to upload")+'"><i class="fa fa-plus"></i></div></div></div>');
// 				scriptJquery(".sesbasic_custom_horizontal_scroll").mCustomScrollbar({
// 					axis:"x",
// 					theme:"light-3",
// 					advanced:{autoExpandHorizontalScroll:true}
// 				});
    }
    var input = document.getElementById('buysell-location');
    var autocomplete = new google.maps.places.Autocomplete(input);
    google.maps.event.addListener(autocomplete, 'place_changed', function () {
      var place = autocomplete.getPlace();
      if (!place.geometry) {
        return;
      }
      scriptJquery('#locValuesbuysell-element').html('<span class="tag">'+scriptJquery('#buysell-location').val()+' <a href="javascript:void(0);" class="buysellloc_remove_act">x</a></span>');
      scriptJquery('#locValuesbuysell-element').show();
      scriptJquery('#buyselllocal').hide();
      document.getElementById('activitybuyselllng').value = place.geometry.location.lng();
      document.getElementById('activitybuyselllat').value = place.geometry.location.lat();
    });
   
    scriptJquery('#buysell-description').hashtags();
  }

  this.deactivate = function() {
    if( !this.active ) return;
    this.active = false;
    this.__proto__.detach.call(this);
    this.request = false;
  }
};
})(); // END NAMESPACE
function checkuploadfiletype(input,value){
  var url = input.value;
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  if (input.files && input.files[0] && (ext == "exe" || ext == '.mp3')) {
    scriptJquery('#fileupload-input-type').val('');
    return false;
  }
  if(input.files[0].size > value){
     en4.core.showError("<p>" + en4.core.language.translate("Upload smaller file.") + '</p><button onclick="Smoothbox.close()">'+ en4.core.language.translate("Close")+'</button>');
     scriptJquery('#fileupload-input-type').val('');
    return false;
  }
  var field = '<input type="hidden" name="attachment[type]" value="fileupload">';
  if(!scriptJquery('.fileupload-cnt').length)
    scriptJquery('#activity-form').append('<div style="display:none" class="fileupload-cnt">'+field+'</div>');
  else
    scriptJquery('.fileupload-cnt').html(field);
  var plugin = composeInstance.getPlugin('fileupload');
  plugin.ready();
}
