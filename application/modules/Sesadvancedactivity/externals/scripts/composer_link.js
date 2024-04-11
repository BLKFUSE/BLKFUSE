/* $Id:composer_link.js  2017-01-12 00:00:00 SocialEngineSolutions $*/


(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.Link = function(options){
  this.__proto__ = new Composer.Plugin.Interface(options);

  //Extends : Composer.Plugin.Interface,
  this.name = 'link'
  this.options = {
    title : 'Add Link',
    lang : {'Add Link': 'Add Link'},
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
  },

  this.attach = function() {
    this.__proto__.attach.call(this);
    // this.parent();
    this.makeActivator();

    // Poll for links
    //this.interval = (function() {
    //  this.poll();
    //}).periodical(250, this);
    this.monitorLastContent = '';
    this.monitorLastMatch = '';
    this.monitorLastKeyPress = $time();
    // this.getComposer().addEvent('editorKeyPress', function() {
    //   this.monitorLastKeyPress = $time();
    // }.bind(this));
    

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
    }).html(this._lang('Attach')).appendTo(this.elements.body).click(function(e) {
      e.preventDefault();
      this.doAttach();
    }.bind(this));
    this.elements.formInput.focus();
  }

//   this.deactivate = function() {
//     if( !this.active ) return;
//     //this.parent();
//     this.active = false;
//     this.__proto__.detach.call(this);
//     this.request = false;
//   },

  this.deactivate = function() {
    if( !this.active ) return;
    this.__proto__.deactivate.call(this);
    
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
    var val = this.elements.formInput.val();
    if( !val ) {
      return;
    }
    if( !val.match(/^[a-zA-Z]{1,5}:\/\//) )
    {
      val = 'http://' + val;
    }
    this.params.set('uri', val)
    // Input is empty, ignore attachment
    if( val == '' ) {
      return;
    }

    // Send request to get attachment
    var options = scriptJquery.extend({
      'dataType': 'json',
      'method': 'post',
      'data' : {
        'format' : 'json',
        'uri' : val
      },
      'success' : this.doProcessResponse.bind(this)
    }, this.options.requestOptions);

    // Inject loading
    this.makeLoading('empty');

    // Send request
    scriptJquery.ajax(options);
  }

  this.doProcessResponse = function(responseJSON, responseText) {
    // Handle error
    if( $type(responseJSON) != 'object' ) {
      responseJSON = {
        'status' : false
      };
    }
    this.params.set('uri', responseJSON.url);

    // If google docs then just output Google Document for title and descripton
    var uristr = responseJSON.url;
    if (uristr.substr(0, 23) == 'https://docs.google.com') {
      var title = uristr;
      var description = 'Google Document';
    } else {
      var title = responseJSON.title || responseJSON.url;
      var description = responseJSON.description || responseJSON.title || responseJSON.url;
    }
       
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
    if(responseJSON.isGif){
      this.elements.body.empty();
      this.makeFormInputs();
      scriptJquery('#compose-link-menu').hide();
      scriptJquery('#compose-link-body').html('<div class="composer_link_gif_content_wrapper"><div class="composer_link_gif_content"><img src="'+responseJSON.gifImageUrl+'" data-original="'+responseJSON.gifUrl+'" data-still="'+responseJSON.gifImageUrl+'"><a href="javascript:;" class="link_play_activity notclose" title="'+en4.core.language.translate("PLAY")+'"></a><a href="javascript:;" class="link_cancel_activity"><i class="fas fa-times notclose" title="'+en4.core.language.translate("CANCEL")+'"></i></a></div></div>');
    }else if(responseJSON.isIframe){
       this.params.set('thumb', responseJSON.thumb);
       this.elements.body.empty();
       this.makeFormInputs();
       scriptJquery('#compose-link-menu').hide();
      scriptJquery('#compose-link-body').html('<div class="composer_link_video_content_wrapper"><div class="composer_link_gif_content composer_link_iframe_content">'+responseJSON.thumb+'<a href="javascript:;" class="link_cancel_activity"><i class="fas fa-times notclose" title="'+en4.core.language.translate("CANCEL")+'"></i></a></div><div class="composer_link_iframe_content_body"><div class="compose-preview-title"><a target="_blank" href="'+responseJSON.url+'">'+title+'</a></div><div class="compose-preview-description">'+description+'</div></div></div>');
    }else if( images.length > 0 ) {
      this.doLoadImages();
    } else {
      this.doShowPreview();
    }
  }


  
  // Image loading
  
  this.doLoadImages = function() {
    // Start image load timeout
    var interval = setTimeout(function() {
      // Debugging
      if( this.options.debug ) {
        console.log('Timeout reached');
      }
      //this.doShowPreview();
    }.bind(this),this.options.imageTimeout);

      
    // Load them images
    this.params.loadedImages = [];

    let imgs = []; 
    this.params.get('images').forEach(function(imgSrc){
      let img = scriptJquery.crtEle('img',{
        'src': imgSrc,
        'class' : 'compose-link-image'
      });
      imgs.push(img);
    });
    this.params.loadedImages = this.params.get('images');
    this.params.set('assets',imgs);
    this.doShowPreview();
  }


  // Preview generation
  
  this.doShowPreview = function() {
    var self = this;
    this.elements.body.empty();
    this.makeFormInputs();
    
    // Generate image thingy
    if( this.params.loadedImages.length > 0 ) {
      var tmp = new Array();
      this.elements.previewImages = scriptJquery.crtEle('div', {
        'id' : 'compose-link-preview-images',
        'class' : 'compose-preview-images'
      }).appendTo(this.elements.body);
      this.params.assets.forEach(function(element, index) {
        if( !$type(this.params.loadedImages[index]) ) return;
        element.addClass('compose-preview-image-invisible').appendTo(this.elements.previewImages);
        if(false ) {
          delete this.params.images[index];
          delete this.params.loadedImages[index];
          element.destroy();
        } else {
          element.removeClass('compose-preview-image-invisible').addClass('compose-preview-image-hidden');
          tmp.push(this.params.loadedImages[index]);
         // element.erase('height');
         // element.erase('width');
        }
      }.bind(this));

      this.params.loadedImages = tmp;

      if( this.params.loadedImages.length <= 0 ) {
        this.elements.previewImages.destroy();
      }
    }

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
      'events' : {
        'click' : function(e) {
          e.stop();
          self.handleEditTitle(this);
        }
      }
    }).html(this.params.title).appendTo(this.elements.previewTitle).click((e) => {
      e.preventDefault();
      self.handleEditTitle(e);
    });;

    this.elements.previewDescription = scriptJquery.crtEle('div', {
      'id' : 'compose-link-preview-description',
      'class' : 'compose-preview-description',
      'events' : {
        'click' : function(e) {
          e.stop();
          self.handleEditDescription(this);
        }
      }
    }).html(this.params.description).appendTo(this.elements.previewInfo).click((e) => {
      e.preventDefault();
      self.handleEditDescription(e);
    });

    // Generate image selector thingy
    if( this.params.loadedImages.length > 0 ) {
      this.elements.previewOptions = scriptJquery.crtEle('div', {
        'id' : 'compose-link-preview-options',
        'class' : 'compose-preview-options'
      }).appendTo(this.elements.previewInfo);

      if( this.params.loadedImages.length > 1 ) {
        this.elements.previewChoose = scriptJquery.crtEle('div', {
          'id' : 'compose-link-preview-options-choose',
          'class' : 'compose-preview-options-choose',
          'html' : '<span>' + this._lang('Choose Image:') + '</span>'
        }).html('<span>' + this._lang('Choose Image:') + '</span>').appendTo(this.elements.previewOptions);

        this.elements.previewPrevious = scriptJquery.crtEle('a', {
          'id' : 'compose-link-preview-options-previous',
          'class' : 'compose-preview-options-previous',
          'href' : 'javascript:void(0);',
          'html' : '&#171; ' + this._lang('Previous'),
          'events' : {
            'click' : this.doSelectImagePrevious.bind(this)
          }
        }).html('&#171; ' + this._lang('Previous')).appendTo(this.elements.previewChoose).click((e) => {
          this.doSelectImagePrevious()
        });

        this.elements.previewCount = scriptJquery.crtEle('span', {
          'id' : 'compose-link-preview-options-count',
          'class' : 'compose-preview-options-count'
        }).appendTo(this.elements.previewChoose);


        this.elements.previewPrevious = scriptJquery.crtEle('a', {
          'id' : 'compose-link-preview-options-next',
          'class' : 'compose-preview-options-next',
          'href' : 'javascript:void(0);',
          'html' : this._lang('Next') + ' &#187;',
          'events' : {
            'click' : this.doSelectImageNext()
          }
        }).html(this._lang('Next') + ' &#187;').appendTo(this.elements.previewChoose).click((e)=>{
          this.doSelectImageNext();
        });
      }

      this.elements.previewNoImage = scriptJquery.crtEle('div', {
        'id' : 'compose-link-preview-options-none',
        'class' : 'compose-preview-options-none'
      }).appendTo(this.elements.previewOptions);

      this.elements.previewNoImageInput = scriptJquery.crtEle('input', {
        'id' : 'compose-link-preview-options-none-input',
        'class' : 'compose-preview-options-none-input',
        'type' : 'checkbox',
        'events' : {
          'click' : this.doToggleNoImage.bind(this)
        }
      }).appendTo(this.elements.previewNoImage).change((e)=>{
        this.doToggleNoImage();
      });

      this.elements.previewNoImageLabel = scriptJquery.crtEle('label', {
        'for' : 'compose-link-preview-options-none-input',
        'html' : this._lang('Don\'t show an image'),
        'events' : {
          //'click' : this.doToggleNoImage.bind(this)
        }
      }).html(this._lang('Don\'t show an image')).appendTo(this.elements.previewNoImage);
      
      // Show first image
      this.setImageThumb(this.elements.previewImages.children().eq(0));
    }
  }

  this.checkImageValid = function(element) {
    var size = element.getSize();
    var sizeAlt = {x:element.get('width'),y:element.get('height')};
    var width = sizeAlt.x || size.x;
    var height = sizeAlt.y || size.y;
    var pixels = width * height;
    var aspect = width / height;
    
    // Debugging
    if( this.options.debug ) {
      console.log(element.get('src'), sizeAlt, size, width, height, pixels, aspect);
    }

    // Check aspect
    if( aspect > this.options.imageMaxAspect ) {
      // Debugging
      if( this.options.debug ) {
        console.log('Aspect greater than max - ', element.get('src'), aspect, this.options.imageMaxAspect);
      }
      return false;
    } else if( aspect < this.options.imageMinAspect ) {
      // Debugging
      if( this.options.debug ) {
        console.log('Aspect less than min - ', element.get('src'), aspect, this.options.imageMinAspect);
      }
      return false;
    }
    // Check min size
    if( width < this.options.imageMinSize ) {
      // Debugging
      if( this.options.debug ) {
        console.log('Width less than min - ', element.get('src'), width, this.options.imageMinSize);
      }
      return false;
    } else if( height < this.options.imageMinSize ) {
      // Debugging
      if( this.options.debug ) {
        console.log('Height less than min - ', element.get('src'), height, this.options.imageMinSize);
      }
      return false;
    }
    // Check max size
    if( width > this.options.imageMaxSize ) {
      // Debugging
      if( this.options.debug ) {
        console.log('Width greater than max - ', element.get('src'), width, this.options.imageMaxSize);
      }
      return false;
    } else if( height > this.options.imageMaxSize ) {
      // Debugging
      if( this.options.debug ) {
        console.log('Height greater than max - ', element.get('src'), height, this.options.imageMaxSize);
      }
      return false;
    }
    // Check  pixels
    if( pixels < this.options.imageMinPixels ) {
      // Debugging
      if( this.options.debug ) {
        console.log('Pixel count less than min - ', element.get('src'), pixels, this.options.imageMinPixels);
      }
      return false;
    } else if( pixels > this.options.imageMaxPixels ) {
      // Debugging
      if( this.options.debug ) {
        console.log('Pixel count greater than max - ', element.get('src'), pixels, this.options.imageMaxPixels);
      }
      return false;
    }

    return true;
  }

  this.doSelectImagePrevious = function() {
    let currentIndex = scriptJquery(this.elements.imageThumb).index();
    let totalIndex = scriptJquery(this.elements.imageThumb).parent().children().length;
    let next = null;
    if(currentIndex != 0){
       next = scriptJquery(this.elements.imageThumb).parent().children().eq(currentIndex-1);
    }else{
      next = scriptJquery(this.elements.imageThumb).parent().children().eq(totalIndex-1);
    }
    if( this.elements.imageThumb && next.length > 0 ) {
      this.setImageThumb(next);
    }
  }

  this.doSelectImageNext = function() {
    let currentIndex = scriptJquery(this.elements.imageThumb).index();
    let totalIndex = scriptJquery(this.elements.imageThumb).parent().children().length;
    let next = null;
    if(currentIndex < totalIndex-1){
       next = scriptJquery(this.elements.imageThumb).parent().children().eq(currentIndex+1);
    }else{
      next = scriptJquery(this.elements.imageThumb).parent().children().eq(0);
    }
    if( this.elements.imageThumb && next.length > 0 ) {
      this.setImageThumb(next);
    }
  }
  this.setFormInputValue = function(key,value){
    this.__proto__.setFormInputValue.call(this,key,value);
  }
  this.doToggleNoImage = function() {
    if( !scriptJquery("#compose-link-preview-options-none-input").is(':checked') ) {
      let elementA = scriptJquery(this.elements.imageThumb);
      this.params.thumb = elementA.attr("src");
      this.setFormInputValue('thumb', this.params.thumb);
      this.elements.previewImages.css('display', '');
      if( this.elements.previewChoose ) this.elements.previewChoose.css('display', '');
    } else {
      delete this.params.thumb;
      this.setFormInputValue('thumb', '');
      this.elements.previewImages.css('display', 'none');
      if( this.elements.previewChoose ) this.elements.previewChoose.css('display', 'none');
    }
  }

  this.setImageThumb = function(element) {
    // Hide old thumb
    if( this.elements.imageThumb ) {
      this.elements.imageThumb.addClass('compose-preview-image-hidden');
    }
    if( element ) {
      element.removeClass('compose-preview-image-hidden');
      let elementA = scriptJquery(element);
      this.elements.imageThumb = element;
      this.params.thumb = elementA.attr("src");
      this.setFormInputValue('thumb',elementA.attr("src"));
      if( this.elements.previewCount ) {
        var index = this.params.loadedImages.indexOf(elementA.attr("src"));
        //this.elements.previewCount.set('html', ' | ' + (index + 1) + ' of ' + this.params.loadedImages.length + ' | ');
	    if ( index < 0 ) { index = 0; }
        this.elements.previewCount.html(' | ' + this._lang('%d of %d', index + 1, this.params.loadedImages.length) + ' | ');
    }
    } else {
      this.elements.imageThumb = false;
      delete this.params.thumb;
    }
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

  this.handleEditTitle = function(elementData) {
    let element = scriptJquery(elementData.target)
    element.css('display', 'none');
    var input = scriptJquery.crtEle('input', {
      'type' : 'text',
      'value' : element.text().trim(),
    }).insertAfter(element).blur(function(e) {
      if( scriptJquery(e.target).val() != '' ) {
        this.params.title = scriptJquery(e.target).val();
        element.text(this.params.title);
        this.setFormInputValue('title', this.params.title);
      }
      element.css('display', '');
      input.remove();
    }.bind(this));
    input.focus();
  }

  this.handleEditDescription = function(elementData) {
    let element = scriptJquery(elementData.target)
    element.css('display', 'none');
    var input = scriptJquery.crtEle('textarea', {}).html(element.text().trim()).insertAfter(element).blur(function(e) {
      if( scriptJquery(e.target).val() != '' ) {
        this.params.description = scriptJquery(e.target).val();
        element.text(this.params.description);
        this.setFormInputValue('description', this.params.description);
      }
      element.css('display', '');
      input.remove();
    }.bind(this));
    input.focus();
  }
  this.initialize(options);
}
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

