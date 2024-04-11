document.onkeydown = function(evt) {
	evt = evt || window.event;
	var isEscape = false;
	if ("key" in evt) {
		isEscape = (evt.key === "Escape" || evt.key === "Esc");
	} else {
		isEscape = (evt.keyCode === 27);
	}
	if (isEscape) {
		if(scriptJquery(".sesstories_story_view_main").css('display') == "block"){
			scriptJquery(".sesstories_story_view_close_btn").trigger("click");
		}
	}
};

function selectfeedbgimage(background_id) {
  scriptJquery(".sesstories_btn_submit").removeAttr('disabled');
  scriptJquery(".background_img").removeClass('_selected');
  scriptJquery("#background_"+background_id).addClass('_selected');
  scriptJquery('#background_id').val(background_id);

  // set image
  let imageUrl = scriptJquery("#background_"+background_id).find("img").attr("src");
  scriptJquery(".sestories_previewimg").css('background-image', 'url(' + imageUrl + ')');
}

function handleFileUploadsesstories(files)
{
	let isValid = false;
	for (var i = 0; i < files.length; i++)
	{
		if(scriptJquery('.multi_upload_sesstories').find(".filename")){
			scriptJquery('.multi_upload_sesstories').find(".filename").remove();
		}
		var url = files[i].name;
		var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
		if(ext == 'mp4' || ext == 'mpeg' || ext == 'mov' || ext == 'ogg' || ext == 'ogv' || ext == 'avi' || ext == 'flv' || ext == 'mpg' || ext == 'WMV'){
			//check upload limit
			var FileSize = files[i].size / 1024 / 1024; // in MB
			if(FileSize > post_max_size_sesstory){
				alert("The size of the file exceeds the limits of "+post_max_size_sesstory+"MB.");
				return;
			}else{
				isValid = true;
				scriptJquery('#story_type').val('imagevideo');
				var $source = scriptJquery('.sestories_previewvideo').find("source");
				$source[0].src = URL.createObjectURL(files[i]);
				$source.parent()[0].load();
				$source.parent()[0].play();
				scriptJquery('#sesstories_previewtext').html("");
				scriptJquery('#sesstories_add_bg_images').hide();
				scriptJquery('.sestories_previewimg').hide();
				scriptJquery('.sestories_previewvideo').show();
			}
		}
		scriptJquery('#story_type').val('imagevideo');
		if(scriptJquery('#story_type').val() == 'imagevideo' && (ext == "png" || ext == "jpeg" || ext == "jpg" ||  ext == 'gif' || ext == 'mp4' || ext == 'mpeg' || ext == 'mov' || ext == 'ogg' || ext == 'ogv' || ext == 'avi' || ext == 'flv' || ext == 'mpg' || ext == 'WMV' || ext == 'webp')){
			
			if(!isValid){
				isValid = true;
				let imageUrl = URL.createObjectURL(files[i]);
				scriptJquery(".sestories_previewimg").css('background-image', 'url(' + imageUrl + ')');
				scriptJquery('#sesstories_add_bg_images').hide();
				scriptJquery('.sestories_previewimg').show();
				scriptJquery('.sestories_previewvideo').hide();
			}
		}else{
			// scriptJquery(".sesstories_btn_submit").attr('disabled',true);
			files.value = "";
		}
	}

	if(isValid){
		scriptJquery(".sesstories_btn_submit").removeAttr('disabled');
		// scriptJquery(".multi_upload_sesstories").append('<span class="filename">'+url+'</span>');
		scriptJquery("#multi_upload_sesstories").css("border",'');

		scriptJquery(".sesstories_story_uploader_main").hide();
		scriptJquery(".sesstories_story_uploader_preview").show();
		scriptJquery(".stories_description").show();
		scriptJquery(".sesstories_add_bg_images").hide();
		scriptJquery('#sesstories_previewtext').html("");
		scriptJquery('#sesstories_description').val("");
		scriptJquery('.stories_footer').show();
	}
}

scriptJquery(document).on('click','.create_sesstories',function (e) {
	e.preventDefault();
	scriptJquery("#create-sesstories").trigger("click");
});

scriptJquery(document).on('submit','.submit_stories',function (e) {
	e.preventDefault();
// 	if(!scriptJquery("#file_multi_sesstories").val()){
// 		scriptJquery("#multi_upload_sesstories").css("border",'1px solid red');
// 		return false;
// 	}
	var formData = new FormData(this);
	if(scriptJquery('#file_multi_sesstories')[0].files[0]){
		var name = "attachmentVideo[0]";
		var url = scriptJquery("#file_multi_sesstories").val();
		var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
		if((ext == "png" || ext == "jpeg" || ext == "jpg" ||  ext == 'gif' || ext == 'webp')){
			name = "attachmentImage[0]";
		}
		formData.append(name, scriptJquery('#file_multi_sesstories')[0].files[0]);
	}
	scriptJquery(".submit_stories").append('<div class="sesstories_loading_image"></div>');
	formData.append('description', scriptJquery('#sesstories_description').val());

  formData.append('background_id', scriptJquery('#background_id').val());
  formData.append('story_type', scriptJquery('#story_type').val() ? scriptJquery('#story_type').val() : "text");

	var uploadURL = 'sesstories/index/create';
	scriptJquery(".sesstories_btn_submit").attr('disabled',true);
	scriptJquery('.sesstories_add_story_popup_content').append('<div class="sesbasic_loading_cont_overlay" style="display:block;"></div>')
	scriptJquery.ajax({
		url: uploadURL,
		type:'POST',
		data:formData,
		cache:false,
		contentType: false,
		processData: false,
		xhr:  function() {
			var xhrobj = scriptJquery.ajaxSettings.xhr();
			if (xhrobj.upload) {
				xhrobj.upload.addEventListener('progress', function(event) {
					var percent = 0;
					var position = event.loaded || event.position;
					var total = event.total;
					if (event.lengthComputable) {
						percent = Math.ceil(position / total * 100);
					}
					//Set progress
				}, false);
			}
			return xhrobj;
		},
		success: function(response){
			scriptJquery('.sesstories_add_story_popup_content').find('.sesbasic_loading_cont_overlay').remove();
			scriptJquery(".sesstories_btn_submit").attr('disabled',false);
			scriptJquery(".sesstories_loading_image").remove();
			response = scriptJquery.parseJSON(response);
			if (response.message) {
				// alert(response.message);
				scriptJquery("#success_sesstories_cnt").addClass('_show');
				scriptJquery("#success_stories_create").html(response.message);
				setTimeout(function () {
					sessmoothboxclose();
				},10);
        		getSesStories();
			}else{
				alert('Something went wrong, please try again later.');
			}
		}
	});
})
 
function readImageUrlsesstories(input) {
	handleFileUploadsesstories(input.files);
}
scriptJquery(document).on('click','#confirm_success_sesstories',function(e){
	scriptJquery("#success_sesstories_cnt").removeClass('_show');
});
scriptJquery(document).on('click','#cancel_discard_sesstories',function(e){
	scriptJquery("#discard_sesstories_cnt").removeClass('_show');
});
scriptJquery(document).on('click','.discard_sesstories_btn',function(e){
	scriptJquery("#discard_sesstories_cnt").addClass('_show');
});
scriptJquery(document).on('click','#confirm_discard_sesstories',function(e){
	scriptJquery("#discard_sesstories_cnt").removeClass('_show');
	scriptJquery(".sesstories_story_uploader_main").show();
	scriptJquery(".sesstories_story_uploader_preview").hide();
	scriptJquery(".stories_description").hide();
	scriptJquery(".sesstories_add_bg_images").hide();
    scriptJquery('#sesstories_add_bg_images').hide();
	scriptJquery('#sesstories_previewtext').html("");
	scriptJquery('#sesstories_description').val("");
	scriptJquery('.stories_footer').hide();
	scriptJquery('.sestories_previewimg').hide();
	scriptJquery('.sestories_previewvideo').hide();
    scriptJquery('#story_type').val('');
});

scriptJquery(document).on('click','.multi_upload_sesstories, .text_sesstories',function(e){
	scriptJquery('#sesstories_previewtext').html("");
  if(scriptJquery(this).attr('data-type') == 'text') {
	scriptJquery(".sesstories_story_uploader_main").hide();
	scriptJquery(".sesstories_story_uploader_preview").show();
	scriptJquery(".stories_description").show();
	scriptJquery(".sesstories_add_bg_images").show();
    scriptJquery('#sesstories_add_bg_images').show();
	scriptJquery('#sesstories_previewtext').html("");
	scriptJquery('#sesstories_description').val("");
	scriptJquery('.stories_footer').show();
	scriptJquery('.sestories_previewimg').show();
	scriptJquery('.sestories_previewvideo').hide();
	scriptJquery("#sesstories_add_bg_images").children().eq(1).trigger("click");


    scriptJquery('#story_type').val('text');
	if(scriptJquery("#sesstories_description").val())
		scriptJquery(".sesstories_btn_submit").removeAttr('disabled');
  } else if(scriptJquery(this).attr('data-type') == 'imagevideo') {
    
    document.getElementById('file_multi_sesstories').click();
  }
});
scriptJquery(document).on("keyup","#sesstories_description",function(e){
	// if(scriptJquery("#story_type").val() == "imagevideo"){
	// 	return;
	// }
	if(scriptJquery("#story_type").val() != "imagevideo"){
		scriptJquery('#sesstories_add_bg_images').show();
	}
	// if(!scriptJquery("#background_id").val()){
	// 	scriptJquery("#sesstories_add_bg_images").find("a").eq(0).trigger("click");
	// }
	scriptJquery(".sesstories_previewtext").html(scriptJquery(this).val());
	if(scriptJquery(this).val()){
		scriptJquery(".sesstories_btn_submit").removeAttr('disabled');
	}else{
		scriptJquery(".sesstories_btn_submit").attr('disabled',true);
	}
})
function timeSince(timeStamp) {
	var now = new Date(currentDateTime),
		secondsPast = (now.getTime() - timeStamp) / 1000;
	if (secondsPast < 60) {
		return parseInt(secondsPast) + 's';
	}
	if (secondsPast < 3600) {
		return parseInt(secondsPast / 60) + 'm';
	}
	if (secondsPast <= 86400) {
		return parseInt(secondsPast / 3600) + 'h';
	}
	if (secondsPast > 86400) {
		timeStamp = new Date(timeStamp);
		day = timeStamp.getDate();
		month = timeStamp.toDateString().match(/ [a-zA-Z]*/)[0].replace(" ", "");
		year = timeStamp.getFullYear() == now.getFullYear() ? "" : " " + timeStamp.getFullYear();
		return day + " " + month + year;
	}
}
function getIndex(data,id){
	const index = data.findIndex(p => p.user_id == id);
	return index;
}
function getStoryIndex(data,id){
	const index = data.findIndex(p => p.story_id == id);
	return index;
}
function getStories(rel,id) {
	var storyData;
	if(rel == id){
		//my story content
		storyData = storiesData.my_story
	}else{
		//user story content
		var index = getIndex(storiesData.stories,rel)
		storyData = storiesData.stories[index]
	}
	return storyData;
}

function addSourceToVideo(element, src, type) {
	var source = document.createElement('source');
	source.src = src;
	source.type = type;
	element.appendChild(source);
}
function seshoverStopPlay(type){
	if(type){
		sesStoriesHoverItem = true;
		if(sesStoriesvideo){
			sesStoriesvideo.pause();
		}
		if(scriptJquery('.sesstory_play_pause').find('i').hasClass("fa-pause")){
			scriptJquery('.sesstory_play_pause').find('i').addClass("fa-play");
			scriptJquery('.sesstory_play_pause').find('i').removeClass("fa-pause");
		}

	}else{
		if(sesStoriesvideo){
			sesStoriesvideo.play();
		}
		sesStoriesHoverItem = false;
		if(scriptJquery('.sesstory_play_pause').find('i').hasClass("fa-play")){
			scriptJquery('.sesstory_play_pause').find('i').removeClass("fa-play");
			scriptJquery('.sesstory_play_pause').find('i').addClass("fa-pause");
		}
	}
}
scriptJquery(document).on('click','.sesstories_option_elm',function (e) {
	e.preventDefault();
	seshoverStopPlay(true);
	var type = scriptJquery(this).attr('type');
	var id = scriptJquery(this).attr('rel');
	if(type == "delete"){
		openSmoothBoxInUrl(en4.core.baseUrl +"sesstories/index/delete/id/"+id);
	}else if(type == "report"){
		openSmoothBoxInUrl(en4.core.baseUrl +'report/create/route/default/subject/sesstories_story_'+id+'/format/smoothbox');
	}else if (type == "mute"){
		scriptJquery.post(en4.core.baseUrl +"sesstories/index/mute/story_id/"+id,{},function (res) {
			if(res){
				var storyData = storiesData
				//user story content
				var index = getIndex(storyData.stories,selectedStoryUserId)
				storyData.stories.splice(index, 1);
				storiesData = storyData;
				selectedStoryId = 0;
				if (storyData.stories.length >= index + 1 && index > -1){
					selectedStoryUserId = storyData.stories[index].user_id
					callNextStory();
				}else{
					scriptJquery(".sesstories_story_view_close_btn").trigger("click");
				}
				getSesStories();
			}
		})
	}else{
		seshoverStopPlay(false);

	}

})
function storyDeleted(){
	var storyData = storiesData
	//user story content
	var index = getStoryIndex(storyData.my_story.story_content,selectedStoryId)

	callNextStory();
	storyData.my_story.story_content.splice(index, 1);
	storiesData = storyData;
	getSesStories();
}
scriptJquery(document).on('click','.sesstory_play_mute',function (e) {
	e.preventDefault();
	if(scriptJquery(this).find('i').hasClass("fa-volume-up")){
		scriptJquery(this).find('i').addClass("fa-volume-mute");
		scriptJquery(this).find('i').removeClass("fa-volume-up");
		sesStoriesvideo.muted = true;
		sesstoriesVideoVolumeMute = true;
	}else{
		scriptJquery(this).find('i').removeClass("fa-volume-mute");
		scriptJquery(this).find('i').addClass("fa-volume-up");
		sesStoriesvideo.muted = false;
		sesstoriesVideoVolumeMute = false;
	}
})
scriptJquery(document).on('click','.sesstory_play_pause',function (e) {
	e.preventDefault();
	if(scriptJquery(this).find('i').hasClass("fa-pause")){
		stopTimerSesstories = true;
		// scriptJquery(this).find('i').addClass("fa-play");
		// scriptJquery(this).find('i').removeClass("fa-pause");
		seshoverStopPlay(true);
	}else{
		stopTimerSesstories = false;
		// scriptJquery(this).find('i').removeClass("fa-play");
		// scriptJquery(this).find('i').addClass("fa-pause");
		seshoverStopPlay(false);
	}
})
function markStoryViewed(data){
	let storyID = data.story_id;
	let viewer = en4.user.viewer.id;
	scriptJquery.post(en4.core.baseUrl + 'sesstories/index/view-story',{story_id:storyID, user_id: viewer},function(){

	})
}
var selectedStoryId;
var selectedStoryUserId;
var sesstoriesVideoVolumeMute = false;
var story_type;
function createSliders(rel,id,data,index = 0){
	if(typeof data == "undefined")
		data = getStories(rel,id);
	stopTimerSesstories = false;
	if(sesStoriesvideo){
		sesStoriesvideo.pause();
	}
	sesStoriesvideo = undefined;
	seshoverStopPlay(false);

	if(data){
		var total = data.story_content.length;
		var slides = "";
		for(i=0;i<total;i++){
			if(i == index){
				scriptJquery(".sesstories_name").html(data.username);
				scriptJquery(".sesstories_user_image").attr('src',data.user_image);
				//update options
				scriptJquery(".sesstories_story_item_header").find(".sesstories_controller").remove();
				var optionsData = "<span class='sesstory_play_pause'><i class='fas fa-pause'></i></span>";

				if(data.story_content[index].is_video) {
					if(!sesstoriesVideoVolumeMute)
						optionsData = optionsData + "<span class='sesstory_play_mute'><i class='fas fa-volume-up'></i></span>";
					else
						optionsData = optionsData + "<span class='sesstory_play_mute'><i class='fas fa-volume-mute'></i></span>";
				}

				scriptJquery(".sesstories_story_item_header").append("<div class='sesstories_controller'>"+optionsData+"</div>");
				if(data.story_content[index].options && data.story_content[index].options.length){
					var optionsData = "";
					data.story_content[index].options.forEach(item => {
						optionsData = optionsData +  "<li><a href='javascript:;' class='sesstories_option_elm' type='"+item.name+"' rel='"+data.story_content[index].story_id+"'>"+item.label+"</a></li>";
					})
					scriptJquery(".sesstories_story_item_header").find(".sesstories_option").remove();
					scriptJquery(".sesstories_story_item_header").append("<div class='sesstories_option'><a href='javascript:;' class='sesbasic_pulldown_toggle'><i class='fas fa-ellipsis-h'></i></a><ul class='sesstories_option_ul sesbasic_pulldown_options'>"+optionsData+"</ul></div>");
				}
				selectedStoryId = data.story_content[index].story_id;
				selectedStoryUserId = data.user_id;
        story_type = data.story_content[index].story_type;
				if(selectedStoryUserId == parseInt(en4.user.viewer.id) || !data.story_content[index].can_comment){
					scriptJquery(".sesstories_message_cnt").hide();
				}else{
					scriptJquery(".sesstories_message_cnt").show();
				}
				scriptJquery('.sesstories_story_item_caption').removeClass("_photo");
				scriptJquery('.sesstories_story_item_caption').removeClass("_video");
				scriptJquery('.sesstories_media_content').removeClass("_isphoto");
				scriptJquery('.sesstories_media_content').removeClass("_isvideo");
				
				if(data.story_content[index].is_video){
					scriptJquery('.sesstories_media_content').addClass("_isvideo");
					scriptJquery('.sesstories_story_item_caption').addClass("_video");
				} else if(story_type == 'imagevideo') {
					scriptJquery('.sesstories_story_item_caption').addClass("_photo");
					scriptJquery('.sesstories_media_content').addClass("_isphoto");
				}
				scriptJquery(".sesstories_time").html(timeSince(new Date(data.story_content[index].creation_date).getTime()));
				scriptJquery(".sesstories_story_item_caption").html(data.story_content[index].comment);
				scriptJquery(".sesstoriescommentlike").attr('data-storyid',data.story_content[index].story_id);
        if(!data.story_content[index].is_video) {
          //Text Story Work
          if(story_type == 'text') {
            scriptJquery('.sesstories_content_text').show();
            scriptJquery('.sesstories_content_text_reaction').show();
						scriptJquery('.sesstories_content').hide();
            scriptJquery(".sesstories_content_text").css("background-image", "url(" + data.story_content[index].media_url + ")");
            scriptJquery(".sesstories_contenttext").html(data.story_content[index].comment);
            scriptJquery(".sesstories_content_bg_image").attr('src',data.story_content[index].media_url);
          } else if(story_type == 'imagevideo') {
            scriptJquery('.sesstories_content_text').hide();
						scriptJquery('.sesstories_content_text_reaction').hide();
            scriptJquery('.sesstories_content').show();
            scriptJquery(".sesstories_content_bg_image").attr('src',data.story_content[index].media_url);
          }
        } else {
          if(story_type == 'text') {
            scriptJquery('.sesstories_content').hide();
            scriptJquery('.sesstories_content_text').show();
						scriptJquery('.sesstories_content_text_reaction').show();
            scriptJquery(".sesstories_content_text").css("background-image", "url(" + data.story_content[index].media_url + ")");
            scriptJquery(".sesstories_contenttext").html(data.story_content[index].comment);
            scriptJquery(".sesstories_content_bg_image").attr('src',data.story_content[index].media_url);
          } else if(story_type == 'imagevideo') {
            scriptJquery('.sesstories_content_text').hide();
						scriptJquery('.sesstories_content_text_reaction').hide();
            scriptJquery('.sesstories_content').show();
            scriptJquery(".sesstories_content_bg_image").attr('src',data.story_content[index].photo);
          }
        }
				markStoryViewed(data.story_content[index]);
				updateStoryReactionData(data.story_content[index]);
				//check next
				var isNext = callNextStory(true);
				if(isNext){
					scriptJquery(".sesstories_next").show();
				}else{
					scriptJquery(".sesstories_next").hide();
				}
				//check previous
				var isPrev = callPreviousStory(true);
				if(isPrev){
					scriptJquery(".sesstories_previous").show();
				}else{
					scriptJquery(".sesstories_previous").hide();
				}
				if(data.story_content[index].is_video){
					sesStoriesvideo = document.createElement('video');
					sesStoriesvideo.preload = true;
					sesStoriesvideo.controls = false;
					if(sesstoriesVideoVolumeMute){
						sesStoriesvideo.muted = true;
					}
					scriptJquery('.sesstories_content').html(sesStoriesvideo);
					addSourceToVideo(sesStoriesvideo, data.story_content[index].media_url, 'video/mp4');
					sesStoriesvideo.play();
					sesStoriesvideo.onended = function () {
						//call next data
						callNextStory();
					}
					sesStoriesvideo.onpause = function(){
						if(!sesStoriesHoverItem && sesStoriesvideo){
							var sliderBar = scriptJquery(".sesstories_story_slider_loader").children().eq(index);
							sliderBar.find("span").find('span').css("width","100%");
						}
					}
					sesStoriesvideo.ontimeupdate = function (event) {
						if(!sesStoriesvideo){
							return;
						}
						var currentTime = event.srcElement.currentTime;
						var duration = event.srcElement.duration;
						//update bar
						var sliderBar = scriptJquery(".sesstories_story_slider_loader").children().eq(index);
						var percentage = (currentTime * 100) / duration
						sliderBar.find("span").find('span').css("width",percentage+"%");
					}
				}else{
					scriptJquery('.sesstories_content').html('<img rel="'+index+'" onload="imageLoaded();" src="'+data.story_content[index].media_url+'" />');
				}
			}
			slides = slides+'<div><span><span style="width: '+(i < index ? 100 : 0)+'%"></span></span></div>';
		}
		scriptJquery('.sesstories_story_slider_loader').html(slides);
	}
}
var sesStoriesvideo;
var currentTimeForStories = 0;
var breakTimeSesstories = false;
var sesstoriesIntervalObj;
function runTimer() {
	if(sesStoriesHoverItem){
		return;
	}
	var index = scriptJquery(".sesstories_content").find('img').attr('rel');
	var sliderBar = scriptJquery(".sesstories_story_slider_loader").children().eq(parseInt(index));
	var percentage = (currentTimeForStories * 100) / parseInt(sesstories_webstoryviewtime);
	sliderBar.find("span").find('span').css("width",percentage+"%");
	if(percentage == "100"){
		callNextStory();
	}else{
		currentTimeForStories++;
		if(!sesstoriesIntervalObj)
			sesstoriesIntervalObj = setInterval(runTimer, 500);
	}
}
function imageLoaded() {
	breakTimeSesstories = false;
	runTimer();
}
function callPreviousStory(checkExist = false) {
	if(checkExist) {
		currentTimeForStories = 0
		breakTimeSesstories = true;
		if (sesstoriesIntervalObj) {
			clearInterval(sesstoriesIntervalObj);
			sesstoriesIntervalObj = undefined;
		}
	}
	var storyData = storiesData
	var valid = false
	if(storyData.my_story){
		//my story content
		if(storyData.my_story.user_id == selectedStoryUserId) {
			valid = true;
			var index = getStoryIndex(storyData.my_story.story_content,selectedStoryId);
			if(index > -1){
				if(index && index > index - 1){
					if(checkExist)
						return true;
					var storyData = storyData.my_story
					createSliders(0,0,storyData,index-1)
				}else{
					if(checkExist)
						return false;
					scriptJquery(".sesstories_content").html('');
					scriptJquery('.sesstories_story_view_close_btn').trigger("click");
				}
			}
		}
	}
	if(!valid){
		//user story content
		var index = getIndex(storyData.stories,selectedStoryUserId);
		var storyindex = getStoryIndex(storyData.stories[index].story_content,selectedStoryId);
		if( storyindex - 1 >= 0){
			if(checkExist)
				return true;
			var storyData = storyData.stories[index]
			createSliders(0,0,storyData,storyindex-1)
		}else if( index-1 >= 0){
			if(checkExist)
				return true;
			var storyData = storyData.stories[index-1]
			createSliders(0,0,storyData,0)
		}else if(storyData.my_story.story_content && storyData.my_story.story_content.length){
			if(checkExist)
				return true;
			var storyData = storyData.my_story
			createSliders(0,0,storyData,0)
		}else{
			if(checkExist)
				return false;
			scriptJquery(".sesstories_content").html('');
			scriptJquery('.sesstories_story_view_close_btn').trigger("click");
		}
	}
}
//hover on reactions
var sesStoriesHoverItem = false;
var stopTimerSesstories = false;
scriptJquery(function() {
	scriptJquery('.sesstories_story_item_reply_box').hover( function(){
			seshoverStopPlay(true);
		},
		function(){
			if(!stopTimerSesstories)
				seshoverStopPlay(false);
		});
});
function callNextStory(checkExist = false) {
	if(checkExist) {
		currentTimeForStories = 0
		breakTimeSesstories = true;
		if (sesstoriesIntervalObj) {
			clearInterval(sesstoriesIntervalObj);
			sesstoriesIntervalObj = undefined;
		}
	}
	var storyData = storiesData
	var valid = false
	if(storyData.my_story){
		//my story content
		if(storyData.my_story.user_id == selectedStoryUserId) {
			valid = true;
			var index = getStoryIndex(storyData.my_story.story_content,selectedStoryId);
			if(index > -1){
				var dataStory = storyData['my_story']['story_content'][index]
				if(storyData['my_story']['story_content'].length > index + 1){
					if(checkExist)
						return true;
					var storyData = storyData.my_story
					createSliders(0,0,storyData,index+1)
				}else if(storyData.stories && storyData.stories.length){
					if(checkExist)
						return true;
					createSliders(0,0,storyData.stories[0],0);
				}else{
					if(checkExist)
						return false;
					scriptJquery(".sesstories_content").html('');
					scriptJquery('.sesstories_story_view_close_btn').trigger("click");
				}
			}
		}
	}
	if(!valid){
		//user story content
		var index = getIndex(storyData.stories,selectedStoryUserId);
		var storyindex = getStoryIndex(storyData.stories[index].story_content,selectedStoryId)
		if(storyData.stories[index]['story_content'].length > storyindex + 1){
			if(checkExist)
				return true;
			var storyData = storyData.stories[index]
			createSliders(0,0,storyData,storyindex+1)
		}else if(storyData.stories.length > index+1){
			if(checkExist)
				return true;
			var storyData = storyData.stories[index+1]
			createSliders(0,0,storyData,0)
		}else{
			if(checkExist)
				return false;
			scriptJquery(".sesstories_content").html('');
			scriptJquery('.sesstories_story_view_close_btn').trigger("click");
		}
	}
}
scriptJquery(document).on('click','.sesstories_previous',function (e) {
	e.preventDefault();

})
scriptJquery(document).on('click','.sesstories_next',function (e) {
	e.preventDefault();
	callNextStory();
})

scriptJquery(document).on('click','.sesstories_previous',function (e) {
	e.preventDefault();
	callPreviousStory();
})
scriptJquery(document).on("click",'.sessmoothbox',function (e) {
	if(scriptJquery(".sesstories_story_view_main").css('display') == "block"){
		seshoverStopPlay(true);
	}
})
function sessmoothboxcallbackclose() {
	seshoverStopPlay(false);
}
function updateStoryReactionData(data){
	if(data.reactionData && data.reactionData.length){
		if(data.like.image) {
			scriptJquery(".sesstories_comment_d").removeClass("_icon");
			scriptJquery(".sesstories_comment_d").css('background-image', 'url(' + data.like.image + ')');
		}
		var reactionImages = "";
		data.reactionData.forEach(item => {
			reactionImages =  reactionImages + '<span class="comments_likes_reactions"><a title="'+item['title']+'" href="javascript:;" class="sessmoothbox" data-url="sesadvancedactivity/ajax/likes/type//id/'+selectedStoryId+'/resource_type/sesstories_story/item_id/'+selectedStoryId+'/format/smoothbox"><i style="background-image:url('+item['imageUrl']+');"></i></a></span>';
		});
		var finalData = '<div class="comments_stats_likes">'+reactionImages+
			'<a href="javascript:;" class="sessmoothbox" data-url="sesadvancedcomment/ajax/likes/type//id/'+selectedStoryId+'/resource_type/sesstories_story/item_id/'+selectedStoryId+'/format/smoothbox">  '+data.reactionUserData+'</a>'+
			'</div>';
		scriptJquery(".sesstories_reaction_data").html(finalData);
	}else{
		scriptJquery(".sesstories_reaction_data").html('');
		scriptJquery(".sesstories_comment_d").css('background-image','url()');
		scriptJquery(".sesstories_comment_d").addClass("_icon");
	}
}
scriptJquery(document).on('click','.open_sesstory',function (e) {
	breakTimeSesstories=false;
	currentTimeForStories = 0;
	var rel = parseInt(scriptJquery(this).attr('rel'));
	var id = en4.user.viewer.id;
	var storyData;
	storyData = getStories();
	//make sliders sesstories_story_slider_loader
	if(typeof selectedStoryId != "undefined"){
		var storyData = storiesData
		var valid = false
		if(storyData.my_story){
			//my story content
			if(storyData.my_story.user_id == selectedStoryUserId) {
				var index = getStoryIndex(storyData.my_story.story_content,selectedStoryId);
				if(index > -1){
					valid = true;
					var storyData = storyData.my_story
					createSliders(0,0,storyData,index)
					scriptJquery(".sesstories_story_view_main").show();
				}
			}
		}
		if(!valid){
			//user story content
			if(storyData.stories) {
				var index = getIndex(storyData.stories, selectedStoryUserId);
				var storyindex = getStoryIndex(storyData.stories[index].story_content, selectedStoryId);
				if (storyindex > -1) {
					storyData = storyData.stories[index]
					var storyData = storyData.my_story
					createSliders(0, 0, storyData, storyindex)
					scriptJquery(".sesstories_story_view_main").show();
				} else {
					scriptJquery(".sesstories_content").html('');
					scriptJquery('.sesstories_story_view_close_btn').trigger("click");
					alert("Story you are looking does not exists.");
				}
			}else{
				scriptJquery(".sesstories_content").html('');
				scriptJquery('.sesstories_story_view_close_btn').trigger("click");
				alert("Story you are looking does not exists.");
			}
		}
	}else{
		createSliders(rel,id);
		scriptJquery(".sesstories_story_view_main").show();
	}
})
scriptJquery(document).on('click','.sesstories_story_view_close_btn',function (e) {
	e.preventDefault();
	breakTimeSesstories=true;
	if(sesstoriesIntervalObj){
		clearInterval(sesstoriesIntervalObj);
		sesstoriesIntervalObj = undefined;
	}
	selectedStoryId = undefined;
	seshoverStopPlay(true);
	scriptJquery(".sesstories_story_view_main").hide();
})

scriptJquery(document).on('click','.sesstoriescommentlike',function(){
	var obj = scriptJquery(this);
	previousSesadvcommLikeObj = obj.closest('.sesadvcmt_hoverbox_wrapper');
	var story_id = selectedStoryId;
	//var guid = scriptJquery(this).attr('data-guid');

	var type = scriptJquery(this).attr('data-type');
	var datatext = scriptJquery(this).attr('data-text');
	//check for like
	var isLikeElem = false;
	if(scriptJquery(this).hasClass('reaction_btn')){
		var image = scriptJquery(this).find('.reaction').find('i').css('background-image');
		image = image.replace('url(','').replace(')','').replace(/\"/gi, "");
		var elem = scriptJquery(this).parent().parent().parent().find('a');
		isLikeElem = true;
	}else{
		var image = scriptJquery(this).parent().find('.sesadvcmt_hoverbox').find('span').first().find('.reaction_btn').find('.reaction').find('i').css('background-image');
		image = image.replace('url(','').replace(')','').replace(/\"/gi, "");
		var elem = scriptJquery(this);
		isLikeElem = false
	}

	var likeWorkText = scriptJquery(elem).attr('data-like');
	var unlikeWordText = scriptJquery(elem).attr('data-unlike');

	//unlike
	if(scriptJquery(elem).hasClass('_reaction') && !isLikeElem){
		scriptJquery(elem).find('i').removeAttr('style');
		scriptJquery(elem).find('span').html(unlikeWordText);
		scriptJquery(elem).removeClass('sesstoriescommentunlike').removeClass('_reaction').addClass('sesstoriescommentlike');
		scriptJquery(elem).parent().addClass('feed_item_option_like').removeClass('feed_item_option_unlike');
	}else{
		//like
		scriptJquery(elem).find('i').css('background-image', 'url(' + image + ')');
		scriptJquery(elem).find('span').html(datatext);
		scriptJquery(elem).removeClass('sesstoriescommentlike').addClass('_reaction').addClass('sesstoriescommentunlike');
		scriptJquery(elem).parent().addClass('feed_item_option_unlike').removeClass('feed_item_option_like');
	}
	scriptJquery(".sesstories_comment_d").removeClass('_icon');
	var ajax = scriptJquery.ajax({
		url : en4.core.baseUrl + 'sesstories/index/like',
		data : {
			format : 'json',
			story_id : story_id,
			type:type
		},
		'onComplete' : function(responseHTML) {
			if( responseHTML ) {
				changeDataReaction(responseHTML);
			}
		}
	});
	
});
//like feed action content
scriptJquery(document).on('click','.sesstoriescommentunlike',function(){
	var obj = scriptJquery(this);
	var story_id = selectedStoryId;
	var type = scriptJquery(this).attr('data-type');
	var datatext = scriptJquery(this).attr('data-text');
	var likeWorkText = scriptJquery(this).attr('data-like');
	var unlikeWordText = scriptJquery(this).attr('data-unlike');

	//check for unlike
	scriptJquery(this).find('i').removeAttr('style');
	scriptJquery(this).find('span').html(likeWorkText);
	scriptJquery(this).removeClass('sesstoriescommentunlike').removeClass('_reaction').addClass('sesstoriescommentlike');
	scriptJquery(this).parent().addClass('feed_item_option_like').removeClass('feed_item_option_unlike');
	scriptJquery(".sesstories_comment_d").addClass('_icon');
	var ajax = scriptJquery.ajax({
		url : en4.core.baseUrl + 'sesstories/index/unlike',
		data : {
			format : 'json',
			story_id : story_id,
			type:type
		},
		'onComplete' : function(responseHTML) {
			if( responseHTML ) {
				changeDataReaction(responseHTML);

			}
		}
	});
	
});
function changeDataReaction(responseHTML){
	var valid = true;
	var storyData = storiesData
	var data;
	if(storyData.my_story){
		//my story content
		if(storyData.my_story.user_id == selectedStoryUserId) {
			valid = true;
			var index = getStoryIndex(storyData.my_story.story_content,selectedStoryId);
			if(index > -1){
				storyData['my_story']['story_content'][index]['reactionData'] = responseHTML.reactionData['reactionData'];
				storyData['my_story']['story_content'][index]['is_like'] = responseHTML.reactionData['is_like'];
				storyData['my_story']['story_content'][index]['like'] = responseHTML.reactionData['like'];
				storyData['my_story']['story_content'][index]['reactionUserData'] = responseHTML.reactionData['reactionUserData'];
				data = storyData['my_story']['story_content'][index]
			}
		}
	}
	if(!valid){
		//user story content
		var index = getIndex(storyData.stories,selectedStoryUserId)
		var storyindex = getStoryIndex(storyData.stories[index].story_content,selectedStoryId)
		storyData.stories[index]['story_content'][storyindex]['reactionData'] = responseHTML.reactionData['reactionData'];
		storyData.stories[index]['story_content'][storyindex]['is_like'] = responseHTML.reactionData['is_like'];
		storyData.stories[index]['story_content'][storyindex]['like'] = responseHTML.reactionData['like'];
		storyData.stories[index]['story_content'][storyindex]['reactionUserData'] = responseHTML.reactionData['reactionUserData'];
		data = storyData.stories[index]['story_content'][storyindex]
	}
	storiesData = storyData;
	updateStoryReactionData(data)
}
scriptJquery(document).on('click','#sesstories_setting_cnt > li',function (e) {
	var index = scriptJquery(this).index();
	scriptJquery("._active").removeClass("_active");
	scriptJquery(this).find("a").addClass('_active');
	scriptJquery('.sesstories_archive_popup_cont').children().hide();
	scriptJquery('.sesstories_archive_popup_cont').children().eq(index).show();
});

scriptJquery(document).on('click','.sestrories_highlight',function (e) {
	var id = scriptJquery(this).attr('rel');
	if(scriptJquery(this).hasClass('_active')){
		scriptJquery(this).removeClass('_active')
	}else{
		scriptJquery(this).addClass('_active')
	}
	scriptJquery.post(en4.core.baseUrl + 'sesstories/index/highlight',{story_id:id},function (e) {
		getSesStories();
	})
})

scriptJquery(document).on('click','.sesstories_unmute',function (e) {
	var id = scriptJquery(this).attr('rel');
	scriptJquery(this).closest('li').remove();
	scriptJquery.post(en4.core.baseUrl + 'sesstories/index/unmute',{mute_id:id},function (e) {
		getSesStories();
	})
});
scriptJquery(document).on('submit','#sesstories_form_create',function (e) {
	e.preventDefault();
	scriptJquery.post(en4.core.baseUrl + 'sesstories/index/save-form',{story_privacy:scriptJquery("input[name='story_privacy']:checked").val()
		,story_comment:scriptJquery("input[name='story_comment']:checked").val()},function (response) {
		getSesStories();
	})
});
scriptJquery(document).on('keypress','.sesstories_message_cnt_input',function (e) {
	if (e.which == 13) {
		var value = scriptJquery(this).val();
		if(value){
			scriptJquery(this).val("");
			scriptJquery('.sesstories_reply_success_msg').show();
			setTimeout(function () {
				scriptJquery('.sesstories_reply_success_msg').hide();
			},2000)
			scriptJquery.post(en4.core.baseUrl + 'sesstories/index/message',{data:value,owner_id:selectedStoryUserId},function (response) {

			})
		}
	}
});
