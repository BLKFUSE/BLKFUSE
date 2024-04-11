/* $Id:composer_photo.js  2017-01-12 00:00:00 SocialEngineSolutions $*/


(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.AdvancedactivityPhoto = function(options){

  this.__proto__ = new Composer.Plugin.Interface(options);

  this.name = 'photo'

  this.options = {
    title : 'Add Photo',
    lang : {},
    requestOptions : false,
    fancyUploadEnabled : true,
    fancyUploadOptions : {}
  }

  this.initialize = function(options) {
    this.elements = new Hash(this.elements);
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
    this.active = false
    if( this.interval ) $clear(this.interval);
    return this;
  }

  this.activate = function() {
    if( this.active ) return;
    this.__proto__.activate.call(this);

    this.makeMenu();
    this.makeBody();
    
    // Generate form
    var fullUrl = this.options.requestOptions.url;    
      scriptJquery(this.elements.body).html('<input type="file" accept="image/x-png,image/jpeg" onchange="readImageUrl(this)" multiple="multiple" id="file_multi" name="file_multi" style="display:none"><div class="advact_compose_photo_container sesbasic_custom_horizontal_scroll sesbasic_clearfix"><div id="advact_compose_photo_container_inner" class="sesbasic_clearfix"><div id="show_photo"></div><div id="dragandrophandler" class="advact_compose_photo_uploader" title="'+en4.core.language.translate("Choose a file to upload")+'"><i class="fa fa-plus"></i></div></div></div>');
//       scriptJquery(".sesbasic_custom_horizontal_scroll").mCustomScrollbar({
//         axis:"x",
//         theme:"light-3",
//         advanced:{autoExpandHorizontalScroll:true}
//       });
  }


  this.deactivate = function() {
    if( !this.active ) return;
    this.active = false;
    this.__proto__.detach.call(this);
    this.request = false;
  }

  this.doRequest = function() {}

  this.doProcessResponse = function(responseJSON) {}

  this.doImageLoaded = function() {}

  this.makeFormInputs = function() {}

};



})(); // END NAMESPACE
