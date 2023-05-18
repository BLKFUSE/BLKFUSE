/* $Id: composer_event.js 9930 2013-02-18 21:02:11Z jung $ */
(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.Elivestreaming = function(options){

  this.__proto__ = new Composer.Plugin.Interface(options);
 
  this.name = 'elivestreaming'

  this.options = {
    title : 'Live Video',
		url:'',
    lang : {}
  }

  this.initialize = function(options) {
    this.elements = new Hash(this.elements);
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,scriptJquery.extend(options,this.__proto__.options));
  }

  this.attach = function() {
    this.__proto__.attach.call(this);
    //this.parent();
    this.makeActivator();
	scriptJquery('#compose-elivestreaming-activator').addClass('elivestreaming_a').attr('href','javascript:;');
    return this;
  }

  this.detach = function() {
    this.__proto__.detach.call(this);
    //this.parent();
    return this;
  }

  this.activate = function() {
    if( this.active ) return;
    this.__proto__.activate.call(this);
    //this.parent();
		// this.getComposer().getMenu().getElements('.compose-activator').each(function(element) {
    //   element.setStyle('display', '');
    // });
  }
	
  this.deactivate = function() {
      if (!this.active)
        return;
        this.__proto__.detach.call(this);
      //this.parent();
  }

};



})(); // END NAMESPACE
