/* $Id:composer_linkedin.js  2017-01-12 00:00:00 SocialEngineSolutions $*/

(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.SesadvancedactivityLikedin = function(options){
  this.__proto__ = new Composer.Plugin.Interface(options);

  this.name = 'facebook'
  this.options = {
    title : 'Publish this on Linkedin',
    lang : {
        'Publish this on Linkedin': 'Publish this on Linkedin'
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
     if(!this.options.status)
       openWindow = ' openWindowLinkedin';;
     this.elements.spanToggle = scriptJquery.crtEle('span', {
      'class' : 'composer_linkedin_toggle sesadv_tooltip'+openWindow,
      'href'  : 'javascript:void(0);',
      'title' : this.options.lang['Publish this on Linkedin'],
      'events' : {
        'click' : this.toggle.bind(this)
      }
    }).click((e) => {
      this.toggle.bind(this)
    });
    this.elements.formCheckbox = scriptJquery.crtEle('input', {
      'id'    : 'compose-linkedin-form-input',
      'class' : 'compose-form-input',
      'type'  : 'checkbox',
      'name'  : 'post_to_linkedin',
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
  }
  this.detach = function() {
    this.__proto__.detach.call(this);
    this.active = false
    if( this.interval ) $clear(this.interval);
    return this;
  }
  this.toggle = function(event) {
    if(scriptJquery('.openWindowLinkedin').length)
      return;
      if(scriptJquery(('#compose-linkedin-form-input').is(":checked"))){
        scriptJquery(('#compose-linkedin-form-input')).prop('checked', false);
      }else{
        scriptJquery(('#compose-linkedin-form-input')).prop('checked', true);
      }
    if(scriptJquery('.composer_linkedin_toggle').hasClass('composer_linkedin_toggle_active')){
      scriptJquery('.composer_linkedin_toggle').removeClass('composer_linkedin_toggle_active');  
    }else{
      scriptJquery('.composer_linkedin_toggle').addClass('composer_linkedin_toggle_active');  
    }
  }
  this.initialize(options);
};
})(); // END NAMESPACE