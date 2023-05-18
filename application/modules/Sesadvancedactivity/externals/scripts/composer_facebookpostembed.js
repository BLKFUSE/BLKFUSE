/* $Id:composer_link.js  2017-01-12 00:00:00 SocialEngineSolutions $*/


(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.Sesadvancedactivityfacebookpostembed = function(options){
  this.__proto__ = new Composer.Plugin.Interface(options);
  this.name = 'sesadvancedactivityfacebookpostembed',
  this.options = {
    title : 'Add FB Embed Post',
    lang : {},
    // Options for the link preview request
    requestOptions : {},
    // Various image filtering options
    imageMaxAspect : ( 10 / 3 ),
    imageMinAspect : ( 3 / 10 ),
    imageMinSize : 48,
    imageMaxSize : 5000,
    imageMinPixels : 2304,
    imageMaxPixels : 1000000,
    imageTimeout : 5000,
    // Delay to detect links in input
    monitorDelay : 600,
    debug : false
  }

  this.initialize = function(options) {
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,scriptJquery.extend(options,this.__proto__.options));
  }

  this.attach = function() {
    this.__proto__.attach.call(this);
    this.makeActivator();

    // Poll for links
    //this.interval = (function() {
    //  this.poll();
    //}).periodical(250, this);
    this.monitorLastContent = '';
    this.monitorLastMatch = '';
    this.monitorLastKeyPress = $time();
    this.getComposer().addEvent('editorKeyPress', function() {
      this.monitorLastKeyPress = $time();
    }.bind(this));
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
    
    // Generate body contents
    // Generate form
    this.elements.formInput = scriptJquery.crtEle('input', {
      'id' : 'compose-link-form-input',
      'class' : 'compose-form-input',
      'type' : 'text'
    }).appendTo(this.elements.body);

    this.elements.formSubmit = scriptJquery.crtEle('button', {
      'id' : 'compose-link-form-submit',
      'class' : 'compose-form-submit',
    }).html(this._lang('Attach')).appendTo(this.elements.body).click((e) => {
      e.preventDefault();
      this.doAttach();
    });

    this.elements.formInput.focus();
  }

  this.deactivate = function() {
    if( !this.active ) return;
    this.active = false;
    this.__proto__.detach.call(this);
    this.request = false;
  }

  this.poll = function() {
    // Active plugin, ignore
    if( this.getComposer().hasActivePlugin() ) return;
    // Recent key press, ignore
    if( $time() < this.monitorLastKeyPress + this.options.monitorDelay ) return;
    // Get content and look for links
    var content = this.getComposer().getContent();
    // Same as last body
    if( content == this.monitorLastContent ) return;
    this.monitorLastContent = content;
    // Check for match
    var m = content.match(/http:\/\/([-\w\.]+)+(:\d+)?(\/([-#:\w/_\.]*(\?\S+)?)?)?/);
    if( $type(m) && $type(m[0]) && this.monitorLastMatch != m[0] )
    {
      this.monitorLastMatch = m[0];
      this.activate();
      this.elements.formInput.value = this.monitorLastMatch;
      this.doAttach();
    }
  }


  // Getting into the core stuff now

  this.doAttach = function() {
    var val = scriptJquery(this.elements.formInput).val();
    if( !val ) {
      return;
    }
//     if( !val.match(/^[a-zA-Z]{1,5}:\/\//) )
//     {
//       val = val;
//     }
    this.params.set('uri', val)
    // Input is empty, ignore attachment
    if( val == '' ) {
      //e.stop();
      return;
    }

    console.log(val,this.elements.formInput);

    // Send request to get attachment
    var options = scriptJquery.extend({
      'dataType': 'json',
      'method': 'post',
      'data' : {
        'format' : 'json',
        'uri' : val
      },
      'success' : this.doProcessResponse.bind(this)
    }, this.__proto__.options.requestOptions);

    // Inject loading
    this.makeLoading('empty');

    // Send request
    scriptJquery.ajax(options);
  }

  this.doProcessResponse = function(responseJSON, responseText) {
    responseJSON.isIframe = true
    // Handle error
    if( $type(responseJSON) != 'object' ) {
      responseJSON = {
        'status' : false
      };
    }
    this.params.set('uri', responseJSON.url);

    // If google docs then just output Google Document for title and descripton
    var uristr = responseJSON.url;

    var title = responseJSON.url;
    var description = '';

    var images = responseJSON.images || [];
    if(responseJSON.gifUrl)
      title = responseJSON.gifImageUrl;
    this.params.set('title', title);
    this.params.set('description', description);
    this.params.set('images', images);
    this.params.set('loadedImages', []);
    this.params.set('thumb', '');
    this.params.set('isGif', responseJSON.isGif);
    this.params.set('gifUrl',responseJSON.gifUrl);
    this.params.set('isIframe',responseJSON.isIframe);
    this.params.set('gifImageUrl',responseJSON.gifImageUrl);
    console.log(responseJSON.isIframe,' val')
    if(responseJSON.isIframe) {
      console.log("aksjhdaskjdh");
       this.params.set('thumb', responseJSON.thumb);
       this.elements.body.empty();
       this.makeFormInputs();
       scriptJquery('.compose-menu').hide();
      scriptJquery('.compose-body').html('<div class="composer_link_video_content_wrapper"><div class="composer_link_gif_content composer_link_iframe_content"><a href="javascript:;" class="link_cancel_activity"><i class="fas fa-times notclose" title="'+en4.core.language.translate("CANCEL")+'"></i></a></div><div class="composer_link_iframe_content_body"><div class="compose-preview-title">'+title+'</div><div class="compose-preview-description">'+description+'</div></div></div>');
    }else if( images.length > 0 ) {
      this.doLoadImages();
    } else {
      this.doShowPreview();
    }
  }

  // Image loading
  
  this.doLoadImages = function() {
    // Start image load timeout
    var interval = (function() {
      // Debugging
      if( this.options.debug ) {
        console.log('Timeout reached');
      }
      this.doShowPreview();
    }).delay(this.options.imageTimeout, this);
      
    // Load them images
    this.params.loadedImages = [];

    this.params.set('assets', new Asset.images(this.params.get('images'), {
      'properties' : {
        'class' : 'compose-link-image'
      },
      'onProgress' : function(counter, index) {
        this.params.loadedImages[index] = this.params.images[index];
        // Debugging
        if( this.options.debug ) {
          console.log('Loaded - ', this.params.images[index]);
        }
      }.bind(this),
      'onError' : function(counter, index) {
        delete this.params.images[index];
      }.bind(this),
      'onComplete' : function() {
        $clear(interval);
        this.doShowPreview();
      }.bind(this)
    }));
  }


  // Preview generation
  
  this.doShowPreview = function() {
    var self = this;
    this.elements.body.empty();
    this.makeFormInputs();

    this.elements.previewInfo = scriptJquery.crtEle('div', {
      'id' : 'compose-link-preview-info',
      'class' : 'compose-preview-info'
    }).appendTo(this.elements.body);
    
    // Generate title and description
    this.elements.previewTitle = scriptJquery.crtEle('div', {
      'id' : 'compose-link-preview-title',
      'class' : 'compose-preview-title'
    }).appendTo(this.elements.previewInfo);

    this.elements.previewTitleLink = scriptJquery.crtEle('a', {
      'href' : this.params.uri,
      'html' : this.params.title,
      'events' : {
        'click' : function(e) {
          e.stop();
          self.handleEditTitle(this);
        }
      }
    }).appendTo(this.elements.previewTitle).click((e) => {
          e.stop();
          self.handleEditTitle(this);
    });

    this.elements.previewDescription = scriptJquery.crtEle('div', {
      'id' : 'compose-link-preview-description',
      'class' : 'compose-preview-description',
      'html' : this.params.description,
      'events' : {
        'click' : function(e) {
          e.stop();
          self.handleEditDescription(this);
        }
      }
    }).appendTo(this.elements.previewInfo).click((e) => {
      e.stop();
      self.handleEditDescription(this);
    } );

  }

  this.makeFormInputs = function() {
    this.ready();
    this.__proto__.makeFormInputs.call(this,{
      'uri' : this.params.uri,
      'title' : this.params.title,
      'description' : this.params.description,
      'thumb' : this.params.thumb,
      'isGif' : this.params.isGif,
      'isIframe':this.params.isIframe,
      'gifUrl' : this.params.gifUrl,
    });
  }

  this.handleEditTitle = function(element) {
    element.css('display', 'none');
    var input = scriptJquery.crtEle('input', {
      'type' : 'text',
      'value' : element.get('text').trim(),
      'events' : {
        'blur' : function() {
          if( input.value.trim() != '' ) {
            this.params.title = input.value;
            element.set('text', this.params.title);
            this.setFormInputValue('title', this.params.title);
          }
          element.css('display', '');
          input.destroy();
        }.bind(this)
      }
    }).inject(element, 'after');
    input.focus();
  }

  this.handleEditDescription = function(element) {
    element.css('display', 'none');
    var input = scriptJquery.crtEle('textarea', {
      'html' : element.get('text').trim(),
      'events' : {
        'blur' : function() {
          if( input.value.trim() != '' ) {
            this.params.description = input.value;
            element.set('text', this.params.description);
            this.setFormInputValue('description', this.params.description);
          }
          element.css('display', '');
          input.destroy();
        }.bind(this)
      }
    }).inject(element, 'after');
    input.focus();
  }
  this.initialize();
};
})(); // END NAMESPACE
scriptJquery(document).on('click','.link_play_activity',function(e){
  scriptJquery('.link_play_activity').show();
  //loop over all item and hide
  scriptJquery('.composer_link_gif_content').each(function(i, obj) {
    scriptJquery(obj).find('img').attr('src',scriptJquery(obj).find('img').attr('data-still'));
  });
  scriptJquery(this).closest('.composer_link_gif_content').find('img').attr('src',scriptJquery(this).closest('.composer_link_gif_content').find('img').attr('data-original'));
  scriptJquery(this).hide(); 
  if(!scriptJquery(this).closest('.feed_attachment_core_link').length)
  scriptJquery('.compose-link-menu').hide(); 
});
scriptJquery(document).on('click','.composer_link_gif_content > img',function(){
  scriptJquery(this).closest('.composer_link_gif_content').find('.link_play_activity').show();
  scriptJquery(this).closest('.composer_link_gif_content').find('img').attr('src',scriptJquery(this).closest('.composer_link_gif_content').find('img').attr('data-still'));
});
scriptJquery(document).on('click','.link_cancel_activity',function(){
  Object.entries(composeInstance.plugins).forEach(function([key,plugin]) {
      plugin.deactivate();
      scriptJquery('#fancyalbumuploadfileids').val('');
   });
   composeInstance.getTray().empty(); 
})

