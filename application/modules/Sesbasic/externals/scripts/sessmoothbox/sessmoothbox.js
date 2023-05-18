// prevent javascript error before the content has loaded
TB_WIDTH_SES = 0;
TB_HEIGHT_SES = 0;
var executetimesmoothbox = false;;
// add sessmoothbox to href elements that have a class of .sessmoothbox
scriptJquery(document).on('click','.sessmoothbox',function(event){
	event.preventDefault();
	sessmoothboxopen(this);
});
function sessmoothboxopen(obj) {  
  
	if(!scriptJquery('.sessmoothbox_main').length){
    scriptJquery.crtEle('div', {
      'id': 'sessmoothbox_overlay',
      'class': 'sessmoothbox_overlay'
    }).appendTo(document.body);
    
		if(scriptJquery(obj).hasClass('sessocial_icon_add_btn')) {
      
      scriptJquery.crtEle('div', {
        'id': 'sessmoothbox_main',
        'class': 'sessmoothbox_main sessocialshare_smoothbox_main'
      }).appendTo(document.body);
		} else {
      scriptJquery.crtEle('div', {
        'id': 'sessmoothbox_main',
        'class': 'sessmoothbox_main'
      }).appendTo(document.body);
		}
		scriptJquery("#sessmoothbox_main").html('<div class="sessmoothbox_container" id="sessmoothbox_container"><div class="sesbasic_loading_container"></div></div>');
    if(scriptJquery(obj).data('addclass')){
      scriptJquery('#sessmoothbox_container').addClass(scriptJquery(obj).data('addclass'));
    }
	 loaddefaultcontent();
	}
	// display the box for the elements href
	sessmoothboxshow(obj);
	return false;
}
//esc key close
scriptJquery(document).on('keyup', function (e) {
		if(scriptJquery('#'+e.target.id).prop('tagName') == 'INPUT' || scriptJquery('#'+e.target.id).prop('tagName') == 'TEXTAREA' || !scriptJquery('#sessmoothbox_container').length)
				return true;
		//ESC key close
		if (e.keyCode === 27) { 
			sessmoothboxclose();return false; 
		}
});
scriptJquery(document).on('click','.sessmoothbox_main',function(e){
  if (e.target !== this)
    return;
	sessmoothboxclose();
});
function loaddefaultcontent(){
	var htmlElement = document.getElementsByTagName("html")[0];
  htmlElement.style.overflow = 'hidden';
	scriptJquery("#sessmoothbox_container").css({
		left: ((scriptJquery(window).width() - 300 ) / 2) + 'px',
		top: ((scriptJquery(window).height() - 100 ) / 2) + 'px',
		display: "block"
	});	
}
var Sessmoothbox = {
		javascript : [],
		css : [],
}
// called when the user clicks on a sessmoothbox link
function sessmoothboxshow(obj){    
   if(obj){
		 	//initialize blank array value
			Sessmoothbox.javascript = Array();
			Sessmoothbox.css = [];
			var url = scriptJquery(obj).attr('href');
			if(url == 'javascript:;' || scriptJquery(obj).hasClass('open'))
				url = scriptJquery(obj).attr('data-url');
			var params = scriptJquery(obj).attr('rel');
			var requestSmoothbox = scriptJquery.ajax({
      dataType: 'html',
      url: url,
      method: 'get',
      data: {
        format: 'html',
				params:params,
				typesmoothbox:'sessmoothbox'
      },
      evalScripts: true,
      success: function(responseHTML) {
        if(scriptJquery(obj).hasClass('sessocial_icon_add_btn')) {
          responseHTML = responseHTML.replace('sessocialtitleofconent', document.title);
        }
				executeCssJavascriptFiles(responseHTML);
			}
    });
  }	
}
function sessmoothboxExecuteCode(responseHTML,prevWidth){
  if(typeof sessmoothboxcallbackBefore == 'function')
		sessmoothboxcallbackBefore(responseHTML);

	responseHTML = '<a title="'+en4.core.language.translate("Close")+'" class="sessmoothbox_close_btn fas fa-times" href="javascript:;" onclick="javascript:sessmoothboxclose();"></a>'+responseHTML;
	scriptJquery('#sessmoothbox_container').html(responseHTML);	
	//execute code at run once
	if(!executetimesmoothbox){
		executetimesmoothboxTimeinterval = 10;	
	}
	setTimeout(function(){en4.core.runonce.trigger(); }, executetimesmoothboxTimeinterval);
	resizesessmoothbox(prevWidth);
  if(scriptJquery('.sesbasic_custom_scroll').length)
    scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
        theme:"minimal-dark"
  });
}
function sessmoothboxclose(){
	scriptJquery('.sessmoothbox_main').remove();
	scriptJquery('#sessmoothbox_overlay').remove();
	var htmlElement = document.getElementsByTagName("html")[0];
	htmlElement.style.overflow = '';
	executetimesmoothbox = false;
  sessmoothboxcallback = function () {};
  sessmoothboxcallbackBefore = function () {};
  if(typeof sessmoothboxcallbackclose == 'function')
		sessmoothboxcallbackclose();
}
function resizesessmoothbox(prevWidth){
 var linkClose = '<a title="'+en4.core.language.translate("Close")+'" class="sessmoothbox_close_btn fas fa-times" href="javascript:;" onclick="javascript:sessmoothboxclose();"></a>';
 scriptJquery('#sessmoothbox_container').prepend(linkClose);
 var windowheight = scriptJquery(window).height();
 var objHeight =	scriptJquery('#sessmoothbox_container').height();
 var windowwidth= scriptJquery(window).width();
 var objWidth=	scriptJquery('#sessmoothbox_container').width();
 if(objHeight >= windowheight){
		var top = '10'; 
 }else if(objHeight <= windowheight){
		var top = (windowheight - objHeight)/2;		 
 }
 var width = scriptJquery('#sessmoothbox_container').find('div').first().width();
 var	setwidth= width /2 ;
 scriptJquery("#sessmoothbox_container").animate({
		top: top+'px',
		width: width+'px',
		left: (((scriptJquery(window).width() ) / 2) - setwidth) + 'px',
 },400,function() {
    if(typeof sessmoothboxcallback == 'function')
		  sessmoothboxcallback();
    // Animation complete.
  });
}
var successLoad;
function executeCssJavascriptFiles(responseHTML){
	var jsCount = Sessmoothbox.javascript.length;
	var cssCount = Sessmoothbox.css.length;
	//store the total file so we execute all required function after css and js load.
	var totalFiles = jsCount + cssCount;
	successLoad= 0;
	var isLoaded = 0;
	var prevWidth = scriptJquery('#sessmoothbox_container').width();
	if(jsCount == cssCount){
		isLoaded = 1;
		sessmoothboxExecuteCode(responseHTML,prevWidth);
	}
	//execute jsvascript files
	for(var i=0;i < jsCount;i++){
			Asset.javascript(Sessmoothbox.javascript[i], {
			onLoad: function(e) {
				successLoad++;
				if (successLoad === totalFiles){
				    isLoaded = 1;
					sessmoothboxExecuteCode(responseHTML,prevWidth);
				}
			}});
	}
		//execute css files
	for(var i=0;i < cssCount;i++){
			Asset.css(Sessmoothbox.css[i], {
			onLoad: function() {
				successLoad++;
				if (successLoad === totalFiles){
				    isLoaded = 1;
					sessmoothboxExecuteCode(responseHTML,prevWidth);
				}
			}});
	}
	if(!isLoaded){
	    sessmoothboxExecuteCode(responseHTML,prevWidth);
	}
}

function sessmoothboxDialoge(html) {  
	if(!scriptJquery('.sessmoothbox_main').length){
    scriptJquery.crtEle('div', {
      'id': 'sessmoothbox_overlay',
      'class': 'sessmoothbox_overlay'
    }).appendTo(document.body);
    
    scriptJquery.crtEle('div', {
      'id': 'sessmoothbox_main',
      'class': 'sessmoothbox_main'
    }).appendTo(document.body);

		document.getElementById("sessmoothbox_main").innerHTML = '<div class="sessmoothbox_container" id="sessmoothbox_container"><div class="sesbasic_loading_container"></div></div>';
    loaddefaultcontent();
    executeCssJavascriptFiles("<div class='sesbasic_smoothbox_view_number'><div class='_header'>Phone Number</div><div class='_cont'>" + html + "</div></div>");
	}
	// display the box for the elements href
	return false;
}
