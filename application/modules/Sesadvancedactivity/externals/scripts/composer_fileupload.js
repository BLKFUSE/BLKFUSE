/* $Id:composer_fileupload.js  2017-01-12 00:00:00 SocialEngineSolutions $*/

(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;



Composer.Plugin.Fileupload = function(options){

  this.__proto__ = new Composer.Plugin.Interface(options);

  this.name = 'fileupload'

  this.options = {
    title : 'Add File',
    serverLimit : 0,
    lang : {},
    // Options for the link preview request
    requestOptions : {},
    debug : false
  },

  this.initialize = function(options) {
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,scriptJquery.extend(options,this.__proto__.options));
  },

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
    scriptJquery(this.elements.body).html('<input id="fileupload-input-type" type="file" name="fileupload" value="" onchange="checkuploadfiletype(this,'+this.options.sesrverLimitDigits+')"><span class="sesbasic_text_light">(Max size '+sesadvServerLimit+')</span>');    
  },

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
     en4.core.showError("<p>" + en4.core.language.translate("Upload smaller file.") + '</p><button onclick="Smoothbox.close()">'+en4.core.language.translate("Close")+'</button>');
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
