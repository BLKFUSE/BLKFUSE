/* $Id:composer_facebook.js  2017-01-12 00:00:00 SocialEngineSolutions $*/

(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.SesadvancedactivityEvacebook = function(options){
  this.__proto__ = new Composer.Plugin.Interface(options);

  this.name = 'facebook',
  this.options = {
    title : 'Publish this on Facebook',
    lang : {
        'Publish this on Facebook': 'Publish this on Facebook'
    },
    requestOptions : false
  };
  this.initialize = function(options) {
    this.elements = new Hash(this.elements);
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,scriptJquery.extend(options,this.__proto__.options));
  }
  this.attach = function() {
    this.__proto__.attach.call(this);

     var openWindow = '';
     if(!this.options.status)
       openWindow = ' openWindowFacebook';;
     this.elements.spanToggle = scriptJquery.crtEle('span', {
      'class' : 'composer_facebook_toggle sesadv_tooltip'+openWindow,
      'href'  : 'javascript:void(0);',
      'title' : this.options.lang['Publish this on Facebook'],
      'events' : {
        'click' : this.toggle.bind(this)
      }
    });
    this.elements.formCheckbox = scriptJquery.crtEle('input', {
      'id'    : 'compose-facebook-form-input',
      'class' : 'compose-form-input',
      'type'  : 'checkbox',
      'name'  : 'post_to_facebook',
      'style' : 'display:none;'
    });
    /*this.elements.spanTooltip = scriptJquery.crtEle('span', {
      'for' : 'compose-facebook-form-input',
      'class' : 'sesadv_tooltip',
      'title' : this.options.lang['Publish this on Facebook']
    });*/
    this.elements.formCheckbox.appendTo(this.elements.spanToggle);
    //this.elements.spanTooltip.inject(this.elements.spanToggle);
    this.elements.spanToggle.appendTo(scriptJquery('#compose-menu'));
    //this.parent();
    //this.makeActivator();
    return this;
  },
  this.detach = function() {
    this.__proto__.detach.call(this);
    this.active = false
    if( this.interval ) $clear(this.interval);
    return this;
  }
  this.toggle = function(event) {
    if(scriptJquery('.openWindowFacebook').length)
      return;
      if(scriptJquery(('#compose-facebook-form-input').is(":checked"))){
        scriptJquery(('#compose-facebook-form-input')).prop('checked', false);
      }else{
        scriptJquery(('#compose-facebook-form-input')).prop('checked', true);
      }
    if(scriptJquery('.composer_facebook_toggle').hasClass('composer_facebook_toggle_active')){
      scriptJquery('.composer_facebook_toggle').removeClass('composer_facebook_toggle_active');  
    }else{
      scriptJquery('.composer_facebook_toggle').addClass('composer_facebook_toggle_active');  
    }
    composeInstance.plugins['facebook'].active=true;
    setTimeout(function(){
      composeInstance.plugins['facebook'].active=false;
    }, 300);
  }
};
})(); // END NAMESPACE