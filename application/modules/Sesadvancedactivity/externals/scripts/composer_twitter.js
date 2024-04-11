/* $Id:composer_twitter.js  2017-01-12 00:00:00 SocialEngineSolutions $*/


(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;



Composer.Plugin.Twitter = function(options){

  this.__proto__ = new Composer.Plugin.Interface(options);

  this.name = 'twitter'
 
  this.options = {
    title : 'Publish this on Twitter',
    lang : {
        'Publish this on Twitter': 'Publish this on Twitter'
    },
    requestOptions : false,
  }

  this.initialize = function(options) {
    this.elements = new Hash(this.elements);
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,scriptJquery.extend(options,this.__proto__.options));
  }

  this.attach = function() {
    var openWindow = '';
     if(!this.options.status)
       openWindow = ' openWindowTwitter';
    this.elements.spanToggle = scriptJquery.crtEle('span', {
      'class' : 'composer_twitter_toggle sesadv_tooltip'+openWindow,
      'href'  : 'javascript:void(0);',
      'title' : this.options.lang['Publish this on Twitter'],
      'events' : {
        'click' : this.toggle.bind(this)
      }
    });

    this.elements.formCheckbox = scriptJquery.crtEle('input', {
      'id'    : 'compose-twitter-form-input',
      'class' : 'compose-form-input',
      'type'  : 'checkbox',
      'name'  : 'post_to_twitter',
      'style' : 'display:none;'
    });
    
    this.elements.formCheckbox.appendTo(this.elements.spanToggle);
    //this.elements.spanTooltip.inject(this.elements.spanToggle);
    this.elements.spanToggle.appendTo(scriptJquery('#compose-menu')).click((e) => {
      this.toggle();
    });

    return this;
  }

  this.detach = function() {
    this.__proto__.detach.call(this);
    this.active = false
    if( this.interval ) $clear(this.interval);
    return this;
  }

  this.toggle = function(event) {
    if(scriptJquery('.openWindowTwitter').length)
      return;

    if(scriptJquery(('#compose-twitter-form-input').is(":checked"))){
      scriptJquery(('#compose-twitter-form-input')).prop('checked', false);
    }else{
      scriptJquery(('#compose-twitter-form-input')).prop('checked', true);
    }
    if(scriptJquery('.composer_twitter_toggle').hasClass('composer_twitter_toggle_active')){
      scriptJquery('.composer_twitter_toggle').removeClass('composer_twitter_toggle_active');  
    }else{
      scriptJquery('.composer_twitter_toggle').addClass('composer_twitter_toggle_active');  
    }
    composeInstance.plugins['twitter'].active = true;
    setTimeout(function(){
      composeInstance.plugins['twitter'].active = false;
    }, 300);
  }
  this.initialize(options);
};



})(); // END NAMESPACE
