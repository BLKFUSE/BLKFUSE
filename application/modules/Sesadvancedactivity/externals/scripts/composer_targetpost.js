/* $Id:composer_targetpost.js  2017-01-12 00:00:00 SocialEngineSolutions $*/

(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.Sesadvancedactivitytargetpost = function(options){

  this.__proto__ = new Composer.Plugin.Interface(options);
  this.name = 'targetpost'
  this.options = {
    title : 'Choose Preferred Audience',
    lang : {
        'Choose Preferred Audience': 'Choose Preferred Audience'
    },
    requestOptions : false
  }
  this.initialize = function(options) {
    this.elements = new Hash(this.elements);
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,scriptJquery.extend(options,this.__proto__.options));
  }
  this.attach = function() {
     var openWindow = '';
     
     this.elements.spanToggle = scriptJquery.crtEle('span', {
      'class' : 'composer_targetpost_toggle sesadv_tooltip',
      'href'  : 'javascript:void(0);',
      'title' : this.options.lang['Choose Preferred Audience'],
      'events' : {
        'click' : this.toggle.bind(this)
      }
    })
    this.elements.formCheckbox = scriptJquery.crtEle('input', {
      'id'    : 'compose-targetpost-form-input',
      'class' : 'compose-form-input',
      'type'  : 'checkbox',
      'name'  : 'post_to_targetpost',
      'style' : 'display:none;'
    });

    this.elements.formCheckbox.appendTo(this.elements.spanToggle)
    //this.elements.spanTooltip.inject(this.elements.spanToggle);
    this.elements.spanToggle.appendTo(scriptJquery('#compose-menu')).click((e) => {
      this.toggle();
    });;;
    //this.parent();
    //this.makeActivator();
    return this;
  }
  this.detach = function() {
    this.__proto__.detach.call(this);
    this.active = false
    if( this.interval ) $clear(this.interval);
    return this;
  }
  this.toggle = function(event) {
    //open target post popup
    openTargetPostPopup();
    composeInstance.plugins['targetpost'].active=true;
    setTimeout(function(){
      composeInstance.plugins['targetpost'].active=false;
    }, 300);
  }
  this.initialize(options);
};
})(); // END NAMESPACE