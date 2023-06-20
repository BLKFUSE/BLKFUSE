
(function() {
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.Album = function(options){

  this.__proto__ = new Composer.Plugin.Interface(options);

  this.name = 'album'

  this.options = {
    title : 'Add Album',
    lang : {},
    requestOptions : false,
  }

  this.initialize = function(options) {
    this.elements = new Hash(this.elements);
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,options);
  }

  this.attach = function() {
    this.__proto__.activate.call(this);
    this.makeActivator();
    return this;
  }

  this.detach = function() {
    this.__proto__.deactivate.call(this);
    return this;
  }
};
})();
