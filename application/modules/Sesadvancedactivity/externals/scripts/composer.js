/* $Id:composer.js  2017-01-12 00:00:00 SocialEngineSolutions $*/

var sessctWidth = 0;
var sesActDesignOne = false;
(function() { // START NAMESPACE
// var $ = 'id' in document ? document.id : window.$;

 

Composer = function(element, options){


  this.elements = {};

  this.plugins = {};

  this.options = {
    lang : {},
    overText : true,
    allowEmptyWithoutAttachment : false,
    allowEmptyWithAttachment : true,
    hideSubmitOnBlur : false,
    submitElement : false,
    useContentEditable : true
  };

  this.initialize = function(element, options) {
    this.options = scriptJquery.extend(this.options,options);
    this.elements = new Hash(this.elements);
    this.plugins = new Hash(this.plugins);
    
    this.elements.textarea = scriptJquery("#"+element);
    this.elements.textarea.data('Composer');

    this.attach();
    this.getTray();
    this.getMenu();

    this.pluginReady = false;

    this.getForm().on('submit', function(e) {
      var activatedPlugin = this.getActivePlugin();
      if(activatedPlugin)
        var pluginName = activatedPlugin.getName();
      else
        var pluginName = '';

     //feeling work
      if(pluginName != 'buysell' && pluginName != 'quote' && pluginName != 'prayer' && pluginName != 'wishe' && pluginName != 'thought' && pluginName != 'text' && !scriptJquery('#image_id').val() && !scriptJquery('#reaction_id').val() && !scriptJquery('#tag_location').val() && !scriptJquery('#feeling_activity').val() && !scriptJquery('#feedbgid').val()){
        
        if(typeof musicfeedupload != 'undefined' && musicfeedupload) {
          return;
        }
        if( this.pluginReady ) {
          if( !this.options.allowEmptyWithAttachment && this.getContent() == '' ) {
            e.stop();
             scriptJquery('.sesact_post_box').addClass('_blank');

             //scriptJquery('#activity-form').removeClass('feed_background_image');
             scriptJquery('.sesact_post_box').css('background-image', 'none');
            return;
          }
        } else {
          if( !this.options.allowEmptyWithoutAttachment && this.getContent() == '' ) {
            e.stop();
             scriptJquery('.sesact_post_box').addClass('_blank');

             //scriptJquery('#activity-form').removeClass('feed_background_image');
             scriptJquery('.sesact_post_box').css('background-image', 'none');
            return;
          }
        }

         scriptJquery('.sesact_post_box').removeClass('_blank');
      }
      //Feed Background Image Work
//       if(scriptJquery('#feedbgid').val()) {
//         scriptJquery('.sesact_post_box').css('background-image', 'none');
//         scriptJquery('#activity-form').removeClass('feed_background_image');
//         scriptJquery('#feedbg_main_continer').css('style', 'none');
//       }
      this.saveContent();
    }.bind(this));
  };
  this.updateComposer= function(e){
    scriptJquery("#activity_body").mentionsInput("update");
  };
  this.getMenu = function() {
    if( !$type(this.elements.menu) ) {
      
      try {
        this.elements.menu = scriptJquery("#"+this.options.menuElement);
      } catch(err){  console.log(err); }
      if( !$type(this.elements.menu) ) {
        this.elements.menu = scriptJquery.crtEle('div',{
          'id' : 'compose-menu',
          'class' : 'compose-menu'
        }).insertAfter(this.getForm());
      }
    }
    return this.elements.menu;
  };

  

  this.getTray = function() {
    if( !$type(this.elements.tray) ) {
      try {
        this.elements.tray = scriptJquery(this.options.trayElement);
      } catch(err){  console.log(err); }

      if( !$type(this.elements.tray) || !this.elements.tray.length ) {
        this.elements.tray =  scriptJquery.crtEle('div',{
          'id' : 'compose-tray',
          'class' : 'compose-tray',
          
        }).insertAfter('#sescomposer-tray-container').hide();
      }
    }
    return this.elements.tray;
  }

  this.getInputArea = function() {    
    if( !$type(this.elements.inputarea) ) {
      var form = this.elements.textarea.closest('form');
      this.elements.inputarea = scriptJquery.crtEle('div', {
        'class':'fileupload-cnt',
      }).css({
          'display' : 'none',
      }).appendTo(form);
    }
    return this.elements.inputarea;
  };

  this.getForm = function() {
    return this.elements.textarea.closest('form');
  }

 this.c= function(e){
   scriptJquery("#activity_body").mentionsInput("update");
 };

  // Editor

  this.attach = function() {

    // Modify textarea
    this.elements.textarea.addClass('compose-textarea').css('display', 'none');

    // Create container
    this.elements.container = scriptJquery.crtEle('div', {
      'id' : 'compose-container',
      'class' : 'compose-container',
      
    });
    this.elements.textarea.wrap(this.elements.container);


    // Create body
    var supportsContentEditable = this._supportsContentEditable();

    if( supportsContentEditable ) {
      this.elements.body = scriptJquery.crtEle('div', {
        'class' : 'compose-content',
        'styles' : {
          'display' : 'block'
        },
        'events' : {
          'keypress' : function(event) {
            if( event.key == 'a' && event.control ) {
              // FF only
              // if( Browser.Engine.gecko ) {
              //   fix_gecko_select_all_contenteditable_bug(this, event);
              // }
            }
          }
        }
      }).insertBefore(this.elements.textarea);
    } else {
      this.elements.body = this.elements.textarea;
    }

    // Attach blur event
    var self = this;
    this.elements.body.on('blur', function(e) {
      var curVal;
      if( supportsContentEditable ) {
        curVal = scriptJquery(this).html().replace(/\s/, '').replace(/<[^<>]+?>/ig, '');
      } else {
        curVal = scriptJquery(this).html().replace(/\s/, '').replace(/<[^<>]+?>/ig, '')
      }
      if( '' == curVal ) {
          if( supportsContentEditable ) {
            scriptJquery(this).html('<br />');
          } else {
            scriptJquery(this).html('');
          }
        
        if( self.options.hideSubmitOnBlur ) {
          (function() {
            if( !self.hasActivePlugin() ) {
              self.getMenu().css('display', 'none');
            }
          }).delay(250);
        }
      }
    });

    if( self.options.hideSubmitOnBlur ) {
      this.getMenu().css('display', 'none');
      this.elements.body.addEvent('focus', function(e) {
        self.getMenu().css('display', '');
      });
    }

    if( supportsContentEditable ) {
      this.elements.body.contentEditable = true;
      this.elements.body.designMode = 'On';

      ['MouseUp', 'MouseDown', 'ContextMenu', 'Click', 'Dblclick', 'KeyPress', 'KeyUp', 'KeyDown','Paste'].each(function(eventName) {
        var method = (this['editor' + eventName] || function(){}).bind(this);
        this.elements.body.addEvent(eventName.toLowerCase(), method);
      }.bind(this));

      this.setContent(this.elements.textarea.value);

      this.selection = new Composer.Selection(this.elements.body);
    } else {
      this.elements.textarea.css('display', '');
    }

    if( this.options.overText && supportsContentEditable ) {
      new Composer.OverText(this.elements.body, $merge({
        textOverride : this._lang('Post Something...'),
        poll : true,
        isPlainText : !supportsContentEditable,
        positionOptions: {
          position: ( en4.orientation == 'rtl' ? 'upperRight' : 'upperLeft' ),
          edge: ( en4.orientation == 'rtl' ? 'upperRight' : 'upperLeft' ),
          offset: {
            x: ( en4.orientation == 'rtl' ? -4 : 4 ),
            y: 2
          }
        }
      }, this.options.overTextOptions));
    }
    
    if(typeof enabledShedulepost != "undefined" && enabledShedulepost){
      this.elements.schedule = scriptJquery.crtEle('span', {
        'class' : 'composer_schedulepost_toggle sesadv_tooltip',
        'href'  : 'javascript:void(0);',
        'id' : 'sesadvancedactivity_shedulepost',
        'title' : this._lang('schedule post'),
      });
      this.elements.schedule.appendTo(scriptJquery('#compose-menu'));
    }

    //this.fireEvent('attach', this);


       isonCommentBox = false;
       if(!scriptJquery('#activity_body').attr('id'))
        scriptJquery('#activity_body').attr('id',new Date().getTime());

       var data = scriptJquery('#activity_body').val();
       //var data = composeInstance.getContent();

      if(!scriptJquery('#activity_body').val() || isOnEditField || scriptJquery('#hashtagtextsesadv').val()){
      //if(!composeInstance.getContent() || isOnEditField || scriptJquery('#hashtagtextsesadv').val()){
        if(!scriptJquery('#activity_body').val() )
        //if(!composeInstance.getContent() )
          EditFieldValue = '';
        scriptJquery('#activity_body').mentionsInput({
            onDataRequest:function (mode, query, callback) {
             scriptJquery.getJSON('sesadvancedactivity/ajax/friends/query/'+query, function(responseData) {
              responseData = _.filter(responseData, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1 });
              callback.call('#activity_body', responseData);
            });
          },
          //defaultValue: EditFieldValue,
          onCaret: true
        });
      }

      if(data){
         getDataMentionEdit('#activity_body',data);
      }

      if(!scriptJquery('#activity_body').parent().hasClass('typehead')){
        scriptJquery('#activity_body').hashtags();
      }
      setTimeout(function(){ scriptJquery('#activity_body').mentionsInput("update"); }, 1000);

      try{
        scriptJquery(document).on("click",".ses_emoji_container_inner .emoji_contents  ul > li > a",this.updateComposer.bind(this));
      }catch(err){
        console.log(err);
      }
    
  };
  this.detach = function() {
    this.saveContent();
    this.textarea.css('display', '').removeClass('compose-textarea').insertBefore(this.container);
    this.container.dispose();
    //this.fireEvent('detach', this);
    return this;
  };

  this.focus = function(){
    // needs the delay to get focus working
    (function(){
      this.elements.body.focus();
      //this.fireEvent('focus', this);
    }).bind(this).delay(10);
    return this;
  };



  // Content

  this.getContent = function(){
    return scriptJquery(this.elements.textarea).val();
  };

  this.setContent = function(newContent) {
    //scriptJquery('#activity_body_emojis').val(newContent);
    scriptJquery(this.elements.textarea).val(newContent);
    return this;
  };


  this.saveContent = function(){
    if( this._supportsContentEditable() ) {
      scriptJquery(this.elements.textarea).val( this.getContent());
    }
    return this;
  };

  this.cleanup = function(html) {
    // @todo
    return html
      .replace(/<(br|p|div)[^<>]*?>/ig, "\r\n")
      .replace(/<[^<>]+?>/ig, ' ')
      .replace(/(\r\n?|\n){3,}/ig, "\n\n")
      .trim();
  };



  // Plugins

  this.addPlugin = function(plugin) {
    var key = plugin.getName();
    this.plugins.set(key, plugin);
    plugin.setComposer(this);
    return this;
  };

  this.addPlugins = function(plugins) {
    plugins.each(function(plugin) {
      this.addPlugin(plugin);
    }.bind(this));
  };

  this.getPlugin = function(name) {
    return this.plugins.get(name);
  };

  this.activate = function(name) {
    this.deactivate();
    this.getMenu().css();
    this.plugins.get(name).activate();
  };

  this.deactivate = function() {
    
    Object.entries(this.plugins).forEach(function([key,plugin]) {
      plugin.deactivate();
      scriptJquery('#compose-'+plugin.getName()+'-activator').parent().removeClass('active');
    });
    scriptJquery('#fancyalbumuploadfileids').val('');
    scriptJquery('#reaction_id').val('');
    scriptJquery('.fileupload-cnt').html('');
    this.getTray().empty();
    var textAreal ='activity_body';
    var className = 'highlighter';
    if(sesadvancedactivitybigtext) {
      var textlength = scriptJquery('#'+textAreal).val().length;
      if(textlength <= sesAdvancedactivitytextlimit) {
        scriptJquery('.'+className).css("fontSize", sesAdvancedactivityfonttextsize);
        scriptJquery('#'+textAreal).css("fontSize", sesAdvancedactivityfonttextsize);
      } else {
        scriptJquery('.'+className).css("fontSize", '');
        scriptJquery('#'+textAreal).css("fontSize", '');
      }
    }
  };

  this.signalPluginReady = function(state) {
    this.pluginReady = state;
  };
  this.getActivePlugin = function() {

    var activeplugin = false;
    Object.entries(this.plugins).forEach(function([key,plugin]) {
      if(plugin.active)
        activeplugin = plugin;
    });
    return activeplugin;
  };
  this.hasActivePlugin = function() {
    var active = false;
    Object.entries(this.plugins).forEach(function([key,plugin]) {
      active = active || plugin.active;
    });
    return active;
  };



  // Key events

  this.editorMouseUp = function(e){
    //this.fireEvent('editorMouseUp', e);
  };

  this.editorMouseDown = function(e){
    //this.fireEvent('editorMouseDown', e);
  };

  this.editorContextMenu = function(e){
    //this.fireEvent('editorContextMenu', e);
  };

  this.editorClick = function(e){
    // make images selectable and draggable in Safari
    // if (Browser.Engine.webkit){
    //   var el = e.target;
    //   if (el.get('tag') == 'img'){
    //     this.selection.selectNode(el);
    //   }
    // }

    //this.fireEvent('editorClick', e);
  };

  this.editorDoubleClick = function(e){
    //this.fireEvent('editorDoubleClick', e);
  };

  this.editorKeyPress = function(e){
    this.keyListener(e);
    //this.fireEvent('editorKeyPress', e);
  };

  this.editorKeyUp = function(e){
    //this.fireEvent('editorKeyUp', e);
      setTimeout(function () {
        linkDetection();
      }, 0);
			var str = this.getContent();
			//scriptJquery(this).parent().parent().find(".highlighter").css("width",$(this).css("width"));
			str = str.replace(/\n/g, '<br>');
			if(!str.match(/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?#([a-zA-Z0-9]+)/g) && !str.match(/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?@([a-zA-Z0-9]+)/g) && !str.match(/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?#([\u0600-\u06FF]+)/g) && !str.match(/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?@([\u0600-\u06FF]+)/g)) {
        if(!str.match(/#(([a-zA-Z0-9]+)|([\u0600-\u06FF]+))#/g)) { //arabic support
					str = str.replace(/#(([a-zA-Z0-9]+)|([\u0600-\u06FF]+))/g,'<span class="hashtag">#$1</span>');
				}else{
					str = str.replace(/#(([a-zA-Z0-9]+)|([\u0600-\u06FF]+))#(([a-zA-Z0-9]+)|([\u0600-\u06FF]+))/g,'<span class="hashtag">#$1</span>');
				}
				if(!str.match(/@(([a-zA-Z0-9]+)|([\u0600-\u06FF]+))@/g)) {
					//str = str.replace(/@(([a-zA-Z0-9]+)|([\u0600-\u06FF]+))/g,'<span class="hashtag">@$1</span>');
				}else{
					//str = str.replace(/@(([a-zA-Z0-9]+)|([\u0600-\u06FF]+))@(([a-zA-Z0-9]+)|([\u0600-\u06FF]+))/g,'<span class="hashtag">@$1</span>');
				}
			}
			this.setContent(str);

  }
  this.editorPaste = function(e) {
    //this.fireEvent('editorPaste', e);
    setTimeout(function () {
      linkDetection();
    }, 0);
  };
  this.editorKeyDown = function(e){
    //this.fireEvent('editorKeyDown', e);
  };

  this.keyListener = function(e){

  };
  this._lang = function() {
    try {
      if( arguments.length < 1 ) {
        return '';
      }

      var string = arguments[0];
      if( $type(this.options.lang) && $type(this.options.lang[string]) ) {
        string = this.options.lang[string];
      }

      if( arguments.length <= 1 ) {
        return string;
      }

      var args = new Array();
      for( var i = 1, l = arguments.length; i < l; i++ ) {
        args.push(arguments[i]);
      }

      return string.vsprintf(args);
    } catch( e ) {
      alert(e);
    }
  },

  this._supportsContentEditable = function() {
    return false;
  }
  this.initialize(element,options);
};



Composer.Selection = function(win){
  this.initialize = function(win){
    this.win = win;
  }

  this.getSelection = function(){
    //this.win.focus();
    return window.getSelection();
  }

  this.getRange = function(){
    var s = this.getSelection();

    if (!s) return null;

    try {
      return s.rangeCount > 0 ? s.getRangeAt(0) : (s.createRange ? s.createRange() : null);
    } catch(e) {
      // IE bug when used in frameset
      return document.body.createTextRange();
    }
  }

  this.setRange = function(range){
    if (range.select){
      try{
        (function(){
          range.select();
        });
      } catch(err){ console.log(err); }
    } else {
      var s = this.getSelection();
      if (s.addRange){
        s.removeAllRanges();
        s.addRange(range);
      }
    }
  }
  this.selectNode = function(node, collapse){
    var r = this.getRange();
    var s = this.getSelection();
    if (r.moveToElementText){
      try{
        (function(){
          r.moveToElementText(node);
          r.select();
        });
      } catch(err){ console.log(err); }
    } else if (s.addRange){
      collapse ? r.selectNodeContents(node) : r.selectNode(node);
      s.removeAllRanges();
      s.addRange(r);
    } else {
      s.setBaseAndExtent(node, 0, node, 1);
    }

    return node;
  }

  this.isCollapsed = function(){
    var r = this.getRange();
    if (r.item) return false;
    return r.boundingWidth == 0 || this.getSelection().isCollapsed;
  }

  this.collapse = function(toStart){
    var r = this.getRange();
    var s = this.getSelection();
    if (r.select){
      r.collapse(toStart);
      r.select();
    } else {
      toStart ? s.collapseToStart() : s.collapseToEnd();
    }
  }
  this.getContent = function(){
    var r = this.getRange();
    var body = scriptJquery.crtEle('body',{});
    if (this.isCollapsed()) return '';
    if (r.cloneContents){
      body.appendChild(r.cloneContents());
    } else if ($defined(r.item) || $defined(r.htmlText)){
      body.html(r.item ? r.item(0).outerHTML : r.htmlText);
    } else {
      body.html(r.toString());
    }
    var content = body.html();
    return content;
  }
  this.getText = function(){
    var r = this.getRange();
    var s = this.getSelection();
    return this.isCollapsed() ? '' : r.text || s.toString();
  }
  this.getNode = function(){
    var r = this.getRange();
    if (!Browser.Engine.trident){
      var el = null;
      if (r){
        el = r.commonAncestorContainer;
        // Handle selection a image or other control like element such as anchors
        if (!r.collapsed)
          if (r.startContainer == r.endContainer)
            if (r.startOffset - r.endOffset < 2)
              if (r.startContainer.hasChildNodes())
                el = r.startContainer.childNodes[r.startOffset];

        while ($type(el) != 'element') el = el.parentNode;
      }
      return scriptJquery(el);
    }
    return scriptJquery(r.item ? r.item(0) : r.parent());
  }
  this.insertContent = function(content){
    var r = this.getRange();
    if (r.insertNode){
      r.deleteContents();
      r.insertNode(r.createContextualFragment(content));
    } else {
      // Handle text and control range
      (r.pasteHTML) ? r.pasteHTML(content) : r.item(0).outerHTML = content;
    }
  }
  this.initialize(win);
};


class ComposerOverText extends OverText{
  //Extends : OverText,
  constructor(element, options){
    super(element, options);
  }
  test() {
    if( !$type(this.options.isPlainText) || !this.options.isPlainText) {
      return !this.element.html().replace(/\s+/, '').replace(/<br.*?>/, '');
    } else {
      return this.parent();
    }
  }
  hide(suppressFocus, force){
    if (this.text && (this.text.is(":visible") && (!this.element.prop('disabled') || force))){
      this.text.hide();
      //this.fireEvent('textHide', [this.text, this.element]);
      try {
        this.element.trigger('focus');
        this.element.focus();
      } catch(e){} //IE barfs if you call focus on hidden elements

      this.pollingPaused = true;
    }
    return this;
  }
}



Composer.Plugin = {};

Composer.Plugin.Interface = function(options){

this.name = 'interface';
  this.active = false;
  this.composer = false;
  this.options = {
    loadingImage : en4.core.staticBaseUrl + 'application/modules/Core/externals/images/loading.gif'
  };
  this.elements = {};
  this.persistentElements = ['activator', 'loadingImage','aActivator','sactivator'];
  this.params = {};
  this.initialize = function(options) {
    this.params = new Hash();
    this.elements = new Hash();
    this.reset();
    this.options = scriptJquery.extend(this.options,options);
  }
  this.getName = function() {
    return this.name;
  }
  this.setComposer = function(composer) {
    this.composer = composer;
    this.attach();
    return this;
  }
  this.getComposer = function() {
    if( !this.composer ) throw "No composer defined";
    return this.composer;
  }
  this.attach = function() {
    this.reset();
  }
  this.detach = function() {
    this.reset();
    if( this.elements.activator ) {
      this.elements.activator.remove();
      this.elements.erase('menu');
    }
  }
  this.reset = function() {
    Object.entries(this.elements).forEach(function([key,element]) {
      if(!this.persistentElements.includes(key)) {
        //console.log(element,"element");
        // if(scriptJquery(element).length)
        //   scriptJquery(element).remove();
        this.elements.erase(key);
      }
    }.bind(this));
    this.params = new Hash();
    this.elements = new Hash();
  }

  this.activate = function() {
    var textAreal ='activity_body';
    var className = 'highlighter';
    if(sesadvancedactivitybigtext) {
      scriptJquery('.'+className).css("fontSize", '');
      scriptJquery('#'+textAreal).css("fontSize", '');
    }
    if( this.active ) return;

    //Feed Background image work
    if(document.getElementById('feedbgid') && document.getElementById('feedbgid').value != 0) {
      scriptJquery('#feedbgid_isphoto').val(0);
      scriptJquery('#feedbgid').val(0);
      scriptJquery('.sesact_post_box').css('background-image', 'none');
      scriptJquery('#activity-form').removeClass('feed_background_image');
      scriptJquery('#feedbg_main_continer').css('display','none');
      scriptJquery('#hideshowfeedbgcont').css('display','none');
      scriptJquery('#feedbg_content').css('display','none');
    }
    //Feed Background image work

    this.getComposer().getTray().empty();
    scriptJquery('#fancyalbumuploadfileids').val('');
    scriptJquery('#reaction_id').val('');
    scriptJquery('.fileupload-cnt').html('');
    Object.entries(composeInstance.plugins).forEach(function([key,plugin]) {
      plugin.active = false;
      scriptJquery('#compose-'+plugin.getName()+'-activator').parent().removeClass('active');
    });
    scriptJquery('#compose-'+this.getName()+'-activator').parent().addClass('active');
    this.active = true;
    this.reset();
    this.getComposer().getTray().css('display', '');
    if(this.getName() == 'sesevent')
      this.getComposer().getTray().css('display', 'none');
      //this.getComposer().getMenu().css('display', 'none');
     //var submitButtonEl = $(this.getComposer().options.submitElement);
    //    if( submitButtonEl ) {
   //      submitButtonEl.css('display', 'none');
  //    }

    this.getComposer().getMenu().css('border', 'none');

    this.getComposer().getMenu().find('.compose-activator').each(function(e) {
      scriptJquery(this).css('display', 'none');
    });

    switch($type(this.options.loadingImage)) {
      case 'object':
        break;
      case 'string':
        this.elements.loadingImage = scriptJquery.crtEle('img', {
          'id' : 'compose-' + this.getName() + '-loading-image',
          'class' : 'compose-loading-image',
          'src' : this.options.loadingImage
        });
        break;
      default:
        this.elements.loadingImage = scriptJquery.crtEle('img', {
          'id' : 'compose-' + this.getName() + '-loading-image',
          'class' : 'compose-loading-image',
          'src' : 'application/modules/Sesbasic/externals/images/loading.gif',
        });
        break;
    }

  }

  this.deactivate = function() {
    if( !this.active ) return;
    this.active = false;

    this.reset();
    this.getComposer().getTray().css('display', 'none');
    this.getComposer().getMenu().css('display', '');
    var submitButtonEl = scriptJquery(this.getComposer().options.submitElement);
    if( submitButtonEl.length) {
      submitButtonEl.css('display', '');
    }
    this.getComposer().getMenu().find('.compose-activator').each(function(e) {
      scriptJquery(this).css('display', '');
    });

    this.getComposer().getMenu().attr('style', '');
    this.getComposer().signalPluginReady(false);
    scriptJquery('#fancyalbumuploadfileids').val('');
    scriptJquery('#reaction_id').val('');
    scriptJquery('.fileupload-cnt').html('');

    //Feed Background Image Work
    if(document.getElementById('feedbgid')) {
      document.getElementById('hideshowfeedbgcont').style.display = 'block';
      scriptJquery('#feedbg_content').css('display','block');
      scriptJquery('#feedbg_main_continer').css('display','block');
    }
    scriptJquery('#compose-menu').next().html('');
  }

  this.ready = function() {
    this.getComposer().signalPluginReady(true);
    this.getComposer().getMenu().css('display', '');

    var submitEl = document.getElementById(this.getComposer().options.submitElement);
    if( submitEl ) {
      submitEl.style.display = "";
    }
  },


  // Utility

  this.makeActivator = function() {
    if( !this.elements.activator ) {
      var moreTab = false;
      var spanInsertBefore = 'sesact_post_media_options_before';
      if(sesadvancedactivityDesign == 1){
        
        
        if(!sesActDesignOne){
          var content = '';
          if(scriptJquery('#feedbg_main_continer').length > 0) {
            content = scriptJquery('#feedbg_main_continer')[0].outerHTML;
            scriptJquery('#feedbg_main_continer').remove();
            scriptJquery(content).insertAfter(scriptJquery("#sesact_post_box_status"));
          }
          let newcontent = scriptJquery("#sesadvancedactivity-menu")[0].outerHTML;
          scriptJquery("#sesadvancedactivity-menu").remove();
          scriptJquery(newcontent).insertBefore(scriptJquery("#sescomposer-tray-container"));
        }
        sesActDesignOne = true;
        this.elements.activator = scriptJquery.crtEle('span', {
          'class': 'sesact_post_tool_i tool_i_'+this.getName(),
        });

        // this.elements.activator.append(scriptJquery('#sesadvancedactivity-menu')).click((e) => {
        //   this.activate(this);
        // });
        
        scriptJquery('#sesadvancedactivity-menu').append(this.elements.activator);
        if(this.getName() == 'album') {
          this.elements.aActivator = scriptJquery.crtEle('a', {
            'id' : 'compose-' + this.getName() + '-activator',
            'class' : 'sesadv_tooltip sesalbum_popup_sesadv ',
            'href' : 'javascript:;',
            'data-url' : 'sesalbum/index/create/params/anfwallalbum/ispopup/'+isOpenPopup,
            'title' : this._lang(this.options.title),
          }).appendTo(this.elements.activator);
        } else {
          this.elements.aActivator = scriptJquery.crtEle('a', {
            'id' : 'compose-' + this.getName() + '-activator',
            'class' : 'sesadv_tooltip',
            'href' : 'javascript:;',
            'title' : this._lang(this.options.title),
          }).appendTo(this.elements.activator).click((e) => {
            this.activate(this);
          });
        }
        if(!scriptJquery("#sesact_post_box_status").find("#composer-close-design1").length){
          var closeComposer = scriptJquery.crtEle('a', {
            'id' : 'composer-close-design1',
            'class' : 'sesadv_tooltip',
            'href' : 'javascript:;',
            'style' : 'display:none;',
            'title' : this._lang("close"),
          });
          scriptJquery("#sesact_post_box_status").append(closeComposer).click((e) => {
            this.closeOption(this)
          });;
        }
       // var content = '';
       // var content = scriptJquery("#sesadvancedactivity-menu")[0].outerHTML;
       // scriptJquery("#sesadvancedactivity-menu").remove();
        //scriptJquery(content).insertAfter(scriptJquery("#sesact_post_box_status"));
       // content = '';
        
      }else if(sesadvancedactivityDesign == 2){
        var displayCI  = 'block';
        if(counterLoopComposerItem == 4) {
          var html = scriptJquery('<span class="sesact_post_media_options_icon tool_i_more"><a href="javascript:void(0);" title="More" class="sesadv_tooltip"><i></i></a></span>').insertBefore(scriptJquery('#sesact_post_media_options_before'));
        }
        if(counterLoopComposerItem > 3)
           displayCI = 'none';
        counterLoopComposerItem++;
        this.elements.activator = scriptJquery.crtEle('span', {
          'html' :  '',
          'style': 'display:'+displayCI,
          'class': 'sesact_post_media_options_icon tool_i_'+this.getName(),
        });

        //Album Work
        if(this.getName() == 'album') {
          this.elements.aActivator = scriptJquery.crtEle('a', {
            'id' : 'compose-' + this.getName() + '-activator',
            'class' : 'sesadv_tooltip sesalbum_popup_sesadv',
            'href' : 'javascript:;',
            'data-url' : 'sesalbum/index/create/params/anfwallalbum/ispopup/'+isOpenPopup,
            'title' : this._lang(this.options.title),
          }).appendTo(this.elements.activator);
        } else if(this.getName() == 'sesblog') {
          this.elements.aActivator = scriptJquery.crtEle('a', {
            'id' : 'compose-' + this.getName() + '-activator',
             'class' : 'sesadv_tooltip sessmoothbox',
             'href' : 'javascript:;',
             'data-url' : 'sesblog/index/create/',
             'title' : this._lang(this.options.title),
          }).appendTo(this.elements.activator);
        } else if(this.getName() == 'sescustomlistingreview') {
          this.elements.aActivator = scriptJquery.crtEle('a', {
            'id' : 'compose-' + this.getName() + '-activator',
            'class' : 'sesadv_tooltip sessmoothbox',
            'href' : 'javascript:;',
            'data-url' : 'sescustomlisting/review/post',
            'title' : this._lang(this.options.title),
          }).appendTo(this.elements.activator);
        } else {
          try{
            this.elements.aActivator = scriptJquery.crtEle('a', {
                'id' : 'compose-' + this.getName() + '-activator',
                'class' : 'sesadv_tooltip',
                'href' : 'javascript:;',
                'title' : this._lang(this.options.title),
            }).appendTo(this.elements.activator).click((e) => {
              this.activate(this);
            });
          }catch(err){
            console.log(err);
          }
        }
        
        this.elements.sactivator = scriptJquery.crtEle('span', {}).html(this._lang(this.options.title)).appendTo(this.elements.aActivator);
      
        this.elements.activator.insertBefore(scriptJquery('#sesact_post_media_options_before'));
      }
    }
     scriptJqueryTooltip('.sesadv_tooltip').powerTip({
      smartPlacement: true
     });
  };

  this.makeMenu = function() {
    if( !this.elements.menu ) {
      var tray = this.getComposer().getTray();

      this.elements.menu = scriptJquery.crtEle('div', {
        'id' : 'compose-' + this.getName() + '-menu',
        'class' : 'compose-menu'
      }).appendTo(tray);

      this.elements.menuTitle = scriptJquery.crtEle('span', {
				'class' : 'compose-menu-head',
      }).html(this._lang(this.options.title)).appendTo(this.elements.menu);

      this.elements.menuClose = scriptJquery.crtEle('a', {
				'class' : 'compose-menu-close fas fa-times',
        'href' : 'javascript:void(0);',
        'title' : this._lang('cancel'),
      }).appendTo(this.elements.menuTitle).click(function(e) {
        //e.stop();
        this.getComposer().deactivate();
      }.bind(this));

      this.elements.menuTitle.append('');

    }
  }
  this.closeOption = function(){   
    if(scriptJquery("#feedbg_main_continer").length){
      feedbgimage('defaultimage');
    }
    scriptJquery("#feedbg_main_continer").hide();
    scriptJquery("#composer-close-design1").hide();
  }
  this.makeBody = function() {
    if( !this.elements.body ) {
      var tray = this.getComposer().getTray();
      this.elements.body = scriptJquery.crtEle('div', {
        'id' : 'compose-' + this.getName() + '-body',
        'class' : 'compose-body'
      }).appendTo(tray);
    }
  }

  this.makeLoading = function(action) {
    if( !this.elements.loading ) {
      if( action == 'empty' ) {
        this.elements.body.empty();
      } else if( action == 'hide' ) {
        this.elements.body.getChildren().each(function(element){ element.css('display', 'none')});
      } else if( action == 'invisible' ) {
        this.elements.body.getChildren().each(function(element){ element.css('height', '0px').css('visibility', 'hidden')});
      }

      this.elements.loading = scriptJquery.crtEle('div', {
        'id' : 'compose-' + this.getName() + '-loading',
        'class' : 'compose-loading'
      }).appendTo(this.elements.body);
      var image = this.elements.loadingImage || (scriptJquery.crtEle('img', {
        'id' : 'compose-' + this.getName() + '-loading-image',
        'class' : 'compose-loading-image'
      }));
      image.appendTo(this.elements.loading);
      scriptJquery.crtEle('span', {
        'html' : this._lang('Loading...')
      }).html(this._lang('Loading...')).appendTo(this.elements.loading);
    }
  }

  this.makeError = function(message, action) {
    if( !$type(action) ) action = 'empty';
    message = message || 'An error has occurred';
    message = this._lang(message);
    this.elements.error = scriptJquery.crtEle('div', {
      'id' : 'compose-' + this.getName() + '-error',
      'class' : 'compose-error',
      'html' : message
    }).html(message).appendTo(this.elements.body);
  }
  this.makeFormInputs = function(data) {
    
    this.ready();
    this.getComposer().getInputArea().empty();
    var name = this.getName();
    if(name == 'link')
      name  = 'sesadvancedactivitylink';
    data.type = name;
    Object.entries(data).forEach(function([key,value]) {
      this.setFormInputValue(key, value);
    }.bind(this));

  }

  this.setFormInputValue = function(key, value) {
    var elName = 'attachmentForm' + key.replace(/\b[a-z]/g, function(match){
      return match.toUpperCase();
    });
    if( !this.elements.has(elName) ) {
      this.elements.set(elName,scriptJquery.crtEle('input', {
        'type' : 'hidden',
        'name' : 'attachment[' + key + ']',
        'value' : value || ''
      }).appendTo(this.getComposer().getInputArea()));
    }
    this.elements.get(elName).val(value);
  }
  this._lang = function() {
    try {
      if( arguments.length < 1 ) {
        return '';
      }
      var string = arguments[0];
      if( $type(this.options.lang) && $type(this.options.lang[string]) ) {
        string = this.options.lang[string];
      }
      if( arguments.length <= 1 ) {
        return string;
      }
      var args = new Array();
      for( var i = 1, l = arguments.length; i < l; i++ ) {
        args.push(arguments[i]);
      }
      return string.vsprintf(args);
    } catch( e ) {
      alert(e);
    }
  }
  this.initialize(options);
};
})(); // END NAMESPACE
scriptJquery(document).on('click',function(e){

//   if(enableStatusBoxHighlight == 0){
//     //return;
//   }
  var container = scriptJquery('.sesact_post_container');
  var smoothbox = scriptJquery('.sessmoothbox_main');
  var smoothboxIcon = scriptJquery('.sessmoothbox_overlay');
  var smoothboxSE = scriptJquery('#TB_window');
  var smoothboxSEOverlay = scriptJquery('#TB_overlay');
   var notclose = scriptJquery('.notclose');
  if(scriptJquery(e.target).hasClass('notclose') || scriptJquery(e.target).closest('.ui-autocomplete').length > 0 || scriptJquery(e.target).hasClass("compose-form-submit") || scriptJquery(e.target).parent().hasClass('tag') || smoothbox.has(e.target).length || smoothbox.is(e.target) || smoothboxIcon.has(e.target).length || smoothboxIcon.is(e.target) || notclose.has(e.target).length || notclose.is(e.target) || scriptJquery(e.target).hasClass('sessmoothbox_close_btn') || scriptJquery(e.target).hasClass('sessmoothbox_main') || smoothboxSE.has(e.target).length || smoothboxSE.is(e.target) || smoothboxSEOverlay.has(e.target).length || smoothboxSEOverlay.is(e.target) || scriptJquery(e.target).attr('id') == 'TB_overlay' ||  scriptJquery(e.target).hasClass('close') || scriptJquery('.pac-container').has(e.target).length || scriptJquery('.pac-container').is(e.target) || scriptJquery(e.target).attr('id') == 'TB_window' || scriptJquery(e.target).prop("tagName") == 'BODY'){
    return;
  }

  if(scriptJquery(e.target).attr('id') == 'discard_post' || scriptJquery(e.target).attr('id') == 'goto_post'){
    return;
  }

  //Feed Background Image Work
  if(scriptJquery(e.target).hasClass('fa fa-angle-right') || scriptJquery(e.target).hasClass('fa fa-angle-left')){
    return;
  }
  if(scriptJquery("#composer-close-design1").is(e.target)){
    return;
  }
  if ((!container.is(e.target)
      && container.has(e.target).length === 0) || scriptJquery(e.target).hasClass('sesact_post_box_close_a'))
  {
    if(scriptJquery('._sesadv_composer_active').length){
      checkComposerAdv();
    }
  } else {
    if(sesadvancedactivityDesign != 2){
      scriptJquery("#composer-close-design1").show();
      scriptJquery('#feedbg_main_continer').show();
      return;
    }
    scriptJquery('.sesact_post_container_wrapper').addClass('_sesadv_composer_active');
    scriptJquery(".sesact_post_box_close").show();
    scriptJquery('.sesact_post_media_options').addClass('sesact_post_media_options_active');
    scriptJquery(".sesact_post_media_options span:gt(3)").show();
    scriptJquery(".sesact_post_media_options").children().eq(3).hide();

    // Feed bg work
    if(document.getElementById('feedbg_main_continer'))
      scriptJquery('#feedbg_main_continer').css('display','block');
    if(activityBodyHeight){
      scriptJquery('#activity_body').height(activityBodyHeight);
    }
    if(document.getElementById('sesadvancedactivity_feeling_emojis'))
      scriptJquery('#sesadvancedactivity_feeling_emojis').css('display','block');

      scriptJquery('.sesact_post_media_options').show();
      scriptJquery('#compose-menu').show();

  }
});
var activityBodyHeight = 0;
function hideStatusBoxSecond() {

  //return;
  scriptJquery('.sesact_post_container_wrapper').removeClass('_sesadv_composer_active');
  scriptJquery('.sesact_post_media_options').removeClass('sesact_post_media_options_active');
  scriptJquery(".sesact_post_media_options span:gt(4)").hide();
  scriptJquery(".sesact_post_media_options").children().eq(3).show();
  scriptJquery(".sesact_post_media_options").children().eq(2).find("span").show();
  scriptJquery(".sesact_post_box_close").hide();
  scriptJquery('.sesadvancedactivity_shedulepost_overlay').hide();
  //resetComposerBoxStatus();

  //Feed Background Image Work
  if(document.getElementById('feedbgid')) {
    if(document.getElementById('feedbg_main_continer'))
    document.getElementById('feedbg_main_continer').style.display = 'none';
    scriptJquery('.sesact_post_box').css('background-image', 'none');
    scriptJquery('#activity-form').removeClass('feed_background_image');
  }
  if(document.getElementById('sesadvancedactivity_feeling_emojis'))
    scriptJquery('#sesadvancedactivity_feeling_emojis').css('display','none');

  //activityBodyHeight = scriptJquery('#activity_body').height();
  // resize the status box
  scriptJquery('#activity_body').css('height','auto');

  scriptJquery('.sesact_post_box').removeClass('_blank');
}


function getConfirmation() {
  if(!scriptJquery('#activity_body').val())
    return;
//   if(!scriptJquery('#activity_body').length)
//     return undefined;
  var retVal = confirm("Are you sure to discard this post ?");
  if( retVal == true ) {
    resetComposerBoxStatus();
    Object.entries(composeInstance.plugins).forEach(function([key,plugin]) {
      plugin.deactivate();
      scriptJquery('#compose-menu').hide();
      scriptJquery('#compose-'+plugin.getName()+'-activator').parent().removeClass('active');
    });
  }
}



scriptJquery(document).on('paste','#activity_body',function(){
   setTimeout(function () {
      linkDetection();
    }, 20);
});

scriptJquery(document).on('keyup','#activity_body',function(e) {
    if(e.keyCode != '32')
      return;
    setTimeout(function () {
      linkDetection();
    }, 20);
});
function updateEditVal(that,data){
    EditFieldValue = data;
    scriptJquery(that).mentionsInput("update");
}
var mentiondataarray = [];
scriptJquery(document).on('keyup','#activity_body',function(){
    var data = scriptJquery(this).val();
     EditFieldValue = data;
     //scriptJquery(this).mentionsInput("update");
});
function getDataMentionEdit (that,data){
  if (scriptJquery(that).attr('data-mentions-input') === 'true') {
       updateEditVal(that, data);
  }
}
var isOnEditField = isonCommentBox = false;
scriptJquery(document).on('focus','#activity_body',function(){
   isonCommentBox = false;
   if(!scriptJquery(this).attr('id'))
    scriptJquery(this).attr('id',new Date().getTime());
   var data = scriptJquery(this).val();
  if(!scriptJquery(this).val() || isOnEditField){
    if(!scriptJquery(this).val() )
      EditFieldValue = '';
    scriptJquery(this).mentionsInput({
        onDataRequest:function (mode, query, callback) {
         scriptJquery.getJSON('sesadvancedactivity/ajax/friends/query/'+query, function(responseData) {
          responseData = _.filter(responseData, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1 });
          callback.call(this, responseData);
        });
      },
      //defaultValue: EditFieldValue,
      onCaret: true
    });
  }

  if(data){
     getDataMentionEdit(this,data);
  }

  if(!scriptJquery(this).parent().hasClass('typehead')){
    scriptJquery(this).hashtags();
    scriptJquery(this).focus();
  }
  autosize(scriptJquery(this));
});
function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
scriptJquery(document).on('keydown','#activity_body',function(){
   if(scriptJquery(this).val() != '')
    scriptJquery('.sesact_post_box').removeClass('_blank');

});
scriptJquery(window).bind('beforeunload',function(){
 if(!scriptJquery('#activity_body').length)
    return undefined;
 var url      = window.location.href;
 if(url.indexOf('hashtag?hashtag=') >= 0){
  //if('#'+getUrlVars()["hashtag"] == scriptJquery('#activity_body').val()){
  if('#'+getUrlVars()["hashtag"] == composeInstance.getContent()){
    return undefined;
  }
 }
 var activatedPlugin = composeInstance.getActivePlugin();
 if(activatedPlugin)
  var pluginName = activatedPlugin.getName();
 else
  var pluginName = '';
  //if((pluginName &&  pluginName != 'sesevent') || scriptJquery('#activity_body').val() || scriptJquery('#toValues').val() || scriptJquery('#tag_location').val()){
  if((pluginName &&  pluginName != 'sesevent' && pluginName != 'photo') || composeInstance.getContent() || scriptJquery('#toValues').val() || scriptJquery('#tag_location').val()){
    return false;
  }else{
    return undefined;
  }
});
function checkComposerAdv(){
  var activatedPlugin = composeInstance.getActivePlugin();
  if(activatedPlugin)
   var pluginName = activatedPlugin.getName();
  else
    var pluginName = '';

  hideStatusBoxSecond();
  return;
  if((pluginName &&  pluginName != 'sesevent') || scriptJquery('#activity_body').val() || scriptJquery('#toValues').val() || scriptJquery('#tag_location').val()){
  //if((pluginName &&  pluginName != 'sesevent') || composeInstance.getContent() || scriptJquery('#toValues').val() || scriptJquery('#tag_location').val()){
    scriptJquery('.sesact_confirmation_popup').show();
    scriptJquery('.sesact_confirmation_popup_overlay').show();
  }else{
      hideStatusBoxSecond();
  }
}
function linkDetection(){
  var html = scriptJquery('#activity_body').val();
  //var html = composeInstance.getContent();
    if(!html || !scriptJquery('#compose-link-activator').length || scriptJquery('#compose-tray').html())
      return false;
    var mystrings = [];
    var valid = false;
    var url = '';
    valid = this.checkUrl(html);
    if(!valid)
      return;
   var pluginlink = composeInstance.getPlugin('link');
   pluginlink.activate();
   //check for youtube video url
   var matches = valid.match(/watch\?v=([a-zA-Z0-9\-_]+)/);
   if (matches)
   {
     if(valid.indexOf('?') < 0)
      valid = valid+'?youtubevideo=1';
     else
      valid = valid+'&youtubevideo=1';
   }else if(parseVimeo(valid)){
     if(valid.indexOf('?') < 0)
      valid = valid+'?vimeovideo=1';
     else
      valid = valid+'&vimeovideo=1';
   }else if(valid.indexOf('https://soundcloud.com') >= 0){
      if(valid.indexOf('?') < 0)
        valid = valid+'?soundcloud=1';
      else
        valid = valid+'&soundcloud=1';
   }
   scriptJquery(pluginlink.elements.formInput).val(valid);
   pluginlink.doAttach();
   pluginlink.active = true;
   scriptJquery('#compose-link-form-submit').trigger('click');
}
function parseVimeo(str) {
    // embed & link: http://vimeo.com/86164897
    var re = /\/\/(?:www\.)?vimeo.com\/([0-9a-z\-_]+)/i;
    var matches = re.exec(str);
    return matches && matches[1];
}
function checkUrl(str){
   var geturl = /(((https?:\/\/)|(www\.))[^\s]+)/g;
   if(str.match(geturl)){
    var length =   str.match(geturl).length
    var urls =   str.match(geturl)

    if(length)
      return urls[0];
   }
    return '';
  }
