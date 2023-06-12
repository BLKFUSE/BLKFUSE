/* $Id: composer_video.js 10258 2014-06-04 16:07:47Z lucas $ */ 
(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;
Composer.Plugin.Video = function(options){
  this.__proto__ = new Composer.Plugin.Interface(options);

  this.name = 'video';
  this.options = {
    title : 'Add Video',
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
    monitorDelay : 250
  };

  this.initialize = function(options) {
    this.elements = new Hash(this.elements);
    this.params = new Hash(this.params);
    this.__proto__.initialize.call(this,options);
  }

  this.attach = function() {
    this.__proto__.attach.call(this);
    this.makeActivator();
    return this;
  }
  this.detach = function() {
    this.__proto__.detach.call(this);
    return this;
  }

  this.activate = function() {
    if( this.active ) return;
    this.__proto__.activate.call(this);

    this.makeMenu();
    this.makeBody();
    // Generate body contents
    // Generate form
    this.elements.formInput = scriptJquery.crtEle('select', {
      'id' : 'compose-video-form-type',
      'class' : 'compose-form-input',
      'option' : 'test',
    }).change(this.updateVideoFields.bind(this)).appendTo(this.elements.body);
    var options = 0;	
    scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
      'value': 0,
    }).html(this._lang('Choose Source')));
    if (this.options.iframelyCheck == 1) {
      scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
        'value': 1,
      }).html(this._lang('External Sites')));
    }
    if (this.options.youtubeEnabled == 1 && this.options.youtubeCheck == 1) {
      scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
        'value': 1,
      }).html(this._lang('YouTube')));
    }
		if (this.options.vimeoCheck == 1) {
      scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
        'value': 2,
      }).html(this._lang('Vimeo')));
		}
		if (this.options.dailymotionCheck == 1) {
      scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
        'value': 4,
      }).html(this._lang('Dailymotion')));
		}
    
    
    if (this.options.fromURL == 1) {
      scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
        'value': 16,
      }).html(this._lang('From URL')));
		}
    if (this.options.embedcode == 1) {
      scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
        'value': 17,
      }).html(this._lang('From Embed Code')));
		}
    if (this.options.fbembedcode == 1) {
      scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
        'value': 105,
      }).html(this._lang('Facebook Embed Code')));
		}
    if (this.options.twitterembedcode == 1) {
      scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
        'value': 106,
      }).html(this._lang('Twitter Embed Code')));
		}
    if (this.options.streamableembedcode == 1) {
      scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
        'value': 107,
      }).html(this._lang('Streamable Embed Code')));
		}
    
    this.elements.formInput = scriptJquery.crtEle('input', {
      'id' : 'compose-video-form-input',
      'class' : 'compose-form-input',
      'type' : 'text',
      'style': 'display:none;'
    }).appendTo(this.elements.body);

    if(DetectMobileQuick() || DetectIpad()){
        if (this.options.allowed == 1 && this.options.type != 'message' && this.options.myComputerCheck == 1){
          scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
            'value': 3,
          }).html(this._lang('My Device')));
        }
        this.elements.previewDescription = scriptJquery.crtEle('div', {
          'id' : 'compose-video-upload',
          'class' : 'compose-video-upload',
          'style': 'display:none;'
        }).html(this._lang('To upload a video from your device, please use our <a href="'+en4.core.baseUrl+'videos/create/type/3">full     uploader</a>.')).appendTo(this.elements.body);
        if(this.options.advancedactvity == 1) 
        scriptJquery('#compose-video-upload').html('<input value="" type="file" name="video_upload" id="compose-video-upload-file">');
    }else{
      if (this.options.allowed == 1 && this.options.type != 'message' && this.options.myComputerCheck == 1){
        scriptJquery('#compose-video-form-type').append(scriptJquery.crtEle('option',{
          'value': 3,
        }).html(this._lang('My Computer')));
      }
      this.elements.previewDescription = scriptJquery.crtEle('div', {
        'id' : 'compose-video-upload',
        'class' : 'compose-video-upload',
        'style': 'display:none;'
      }).html(this._lang('To upload a video from your computer, please use our <a href="'+en4.core.baseUrl+'videos/create/type/3">full     uploader</a>.')).appendTo(this.elements.body);
     if(this.options.advancedactvity == 1)
      scriptJquery('#compose-video-upload').html('<input value="" type="file" name="video_upload" id="compose-video-upload-file">');
    }
    this.elements.formSubmit = scriptJquery.crtEle('button', {
      'id' : 'compose-video-form-submit',
      'class' : 'compose-form-submit',
      'style': 'display:none;',
    }).html(this._lang('Attach')).click(function(e) {
      e.preventDefault();
      this.doAttach();
    }.bind(this))
    .appendTo(this.elements.body);

    this.elements.formInput.focus();
  }
  this.deactivate = function() {
    // clean video out if not attached
    if (this.params.video_id)
      scriptJquery.ajax({
        url: en4.core.basePath + 'sesvideo/index/delete',
        data: {
          format: 'json',
          video_id: this.params.video_id
        }
      });
    if( !this.active ) return;
    this.__proto__.deactivate.call(this);
  }
  // Getting into the core stuff now
  this.doAttach = function(e) {
    var val = this.elements.formInput.val();
    if( !val && !scriptJquery('#compose-video-upload-file').val())
    {
      return;
    }
    if( !val.match(/^[a-zA-Z]{1,5}:\/\//) )
    {
      //val = 'http://' + val;
    }
    this.params.set('uri', val)
    // Input is empty, ignore attachment
    if( val == '' && !scriptJquery('#compose-video-upload-file').val()) {
      e.preventDefault();
      return;
    }
     var video_element = document.getElementById("compose-video-form-type");
    var type = video_element.value;
    var formData = new FormData();
    if(scriptJquery('#compose-video-upload-file').length){
      var filesAttach = scriptJquery('#compose-video-upload-file')[0].files[0];  
    }else{
      var filesAttach = "";  
    }
    formData.append('Filedata', filesAttach);
    formData.append('format', 'json');
    formData.append('uri', val);
    formData.append('type', type);
    formData.append('uploadwall', 1);
    if(type == 3)
      var url = this.options.requestOptions.uploadurl;
    else
      var url = this.options.requestOptions.url;
    // Send request to get attachment
    /*var options = $merge({
      'data' : {
        'format' : 'json',
        'uri' : val,
        'type': type,
        Filedata:formData,
      },
      'onComplete' : this.doProcessResponse.bind(this)
    }, this.options.requestOptions);
    */
    var that = this;
    scriptJquery.ajax({
					type:'POST',
					url: url,
					data:formData,
					cache:false,
					contentType: false,
					processData: false,
					success:function(data){
              that.doProcessResponse(data);
          },
					error: function(data){
						//silence
					}
			});
    
    // Inject loading
    this.makeLoading('empty');
    // Send request
   // this.request = new Request.JSON(options);
   // this.request;
  }
  this.doProcessResponse = function(responseJSON, responseText) {
    // Handle error
    if( ($type(responseJSON) != 'hash' && $type(responseJSON) != 'object') || $type(responseJSON.src) != 'string' || $type(parseInt(responseJSON.video_id)) != 'number' ) {
      //this.elements.body.empty();
      if( this.elements.loading ) this.elements.loading.remove();
      //this.makeaError(responseJSON.message, 'empty');
      this.makeError(responseJSON.message);
      //compose-video-error
      //ignore test
      this.elements.ignoreValidation = scriptJquery.crtEle('a', {
        'href' : this.params.uri,
      }).html(this.params.title)
      .click(function(e) {
        e.preventDefault();
        self.doAttach(this);
      })
      .appendTo(this.elements.previewTitle);
      return;
      //throw "unable to upload image";
    }
    var title = responseJSON.title || this.params.get('uri').replace('http://', '');
    this.params.set('title', responseJSON.title);
    this.params.set('description', responseJSON.description);
    this.params.set('photo_id', responseJSON.photo_id);
    this.params.set('video_id', responseJSON.video_id);
    
    if (responseJSON.src) {
      this.elements.preview = scriptJquery.crtEle('img', {
        'id' : 'compose-video-preview-image',
        'class' : 'compose-preview-image',
        'src' : responseJSON.src,
      }).load(this.doImageLoaded.bind(this));
    } else {
      this.doImageLoaded();
    }
  },
  this.doImageLoaded = function() {
    var self = this;
    if( this.elements.loading.length) this.elements.loading.remove();
    if( this.elements.preview ) {
      this.elements.preview.attr('width','');
      this.elements.preview.attr('height','');
      this.elements.preview.appendTo(this.elements.body);
    }

    this.elements.previewInfo = scriptJquery.crtEle('div', {
      'id' : 'compose-video-preview-info',
      'class' : 'compose-preview-info'
    }).appendTo(this.elements.body);

    this.elements.previewTitle = scriptJquery.crtEle('div', {
      'id' : 'compose-video-preview-title',
      'class' : 'compose-preview-title'
    }).appendTo(this.elements.previewInfo);

    this.elements.previewTitleLink = scriptJquery.crtEle('a', {
      'href' : this.params.uri,
    })
    .html(this.params.title)
    .click(function(e) {
        e.preventDefault();
        self.handleEditTitle(this);
    })
    .appendTo(this.elements.previewTitle);

    this.elements.previewDescription = scriptJquery.crtEle('div', {
      'id' : 'compose-video-preview-description',
      'class' : 'compose-preview-description',
    })
    .html(this.params.description)
    .click(function(e) {
      e.preventDefault();
      self.handleEditDescription(this);
    })
    .appendTo(this.elements.previewInfo);
    this.makeFormInputs();
  }

  this.makeFormInputs = function() {
    this.ready();
    this.__proto__.makeFormInputs.call(this,{
      'photo_id' : this.params.photo_id,
      'video_id' : this.params.video_id,
      'title' : this.params.title,
      'description' : this.params.description
    });
  }
  this.updateVideoFields = function(element) {
    var video_element = document.getElementById("compose-video-form-type");
    var url_element = document.getElementById("compose-video-form-input");
    var post_element = document.getElementById("compose-video-form-submit");
    var upload_element = document.getElementById("compose-video-upload");
    // clear url if input field on change
    scriptJquery('#compose-video-form-input').val("");
  // If video source is empty
    if (video_element.value == 0)
    {
      upload_element.style.display = "none";
      post_element.style.display = "none";
      url_element.style.display = "none";
    }
    // If video source is youtube or vimeo
    if (video_element.value == 1 || video_element.value == 2 || video_element.value == 4 || video_element.value == 16 || video_element.value == 17  || video_element.value == 105  || video_element.value == 106  || video_element.value == 107 )
    {
      upload_element.style.display = "none";
      post_element.style.display = "block";
      url_element.style.display = "block";
      url_element.focus();
    }
    // if video source is upload
    if (video_element.value == 3)
    {
      upload_element.style.display = "block";
      if(this.options.advancedactvity == 1) 
      post_element.style.display = "block";
    else
      post_element.style.display = "none";
      url_element.style.display = "none";
    }
  }
  this.handleEditTitle = function(element) {
    scriptJquery(element).css('display', 'none');
		console.log(scriptJquery(element));
    var input = scriptJquery.crtEle('input', {
      'type' : 'text',
      'value' : htmlspecialchars_decode(scriptJquery(element).html()),
    })
    .blur(function() {
          if(scriptJquery(input).val().trim() != '' ) {
            this.params.title = scriptJquery(input).val();
            scriptJquery(element).html(this.params.title);
            this.setFormInputValue('title', this.params.title);
          }
          scriptJquery(element).css('display', '');
          input.remove();
    }.bind(this))
    .insertAfter(scriptJquery(element), 'after');
    input.focus();
  }
  this.handleEditDescription = function(element) {
    scriptJquery(element).css('display', 'none');
    var input = scriptJquery.crtEle('textarea', {})
    .html(htmlspecialchars_decode(scriptJquery(element).html()))
    .blur(function() {
          if( scriptJquery(input).val().trim() != '' ) {
            this.params.description = scriptJquery(input).val();
            scriptJquery(element).html(this.params.description);
            this.setFormInputValue('description', this.params.description);
          }
          else{
            this.params.description = '';
            scriptJquery(element).html('');
            this.setFormInputValue('description', '');
          }
          scriptJquery(element).css('display', '');
          input.remove();
    }.bind(this))
    .insertAfter(scriptJquery(element), 'after');
    input.focus();
  }
  this.initialize(options);
}

})(); // END NAMESPACE
