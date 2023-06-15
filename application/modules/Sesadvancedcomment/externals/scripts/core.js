/* $Id:core.js 2017-01-19 00:00:00 SocialEngineSolutions $*/

scriptJquery(document).on('submit', '#sesadvancedcomment_contact_owner',function(e) {
  e.preventDefault();
  var formData = new FormData(this);
  var jqXHR=scriptJquery.ajax({
    url: en4.core.baseUrl +"sesadvancedcomment/index/contact",
    type: "POST",
    contentType:false,
    processData: false,
    data: formData,
    success: function(response){
      response = scriptJquery.parseJSON(response);
      if(response.status == 'true') {
			scriptJquery('#sessmoothbox_container').html("<div id='sesadvancedcomment_contact_message' class='sesadvancedcomment_contact_popup sesbasic_bxs'><div class='sesbasic_tip clearfix'><img src='application/modules/Sesadvancedcomment/externals/images/success.png' alt=''><span>"+en4.core.language.translate('Message sent successfully')+"</span></div></div>");
      	scriptJquery('#sesadvancedcomment_contact_message').fadeOut("slow", function(){setTimeout(function() {sessmoothboxclose();}, 3000);
      });
      }
    }
  });
  return false;
});
scriptJquery(document).on('keypress','.body',function(event) {
  scriptJquery(this).closest('form').css('position','relative');
  if(scriptJquery(this).closest('form').hasClass('sesadv_form_submitting'))
    return false;
  if (event.keyCode == 13 && !event.shiftKey) {
     var body = scriptJquery(this).closest('form').find('.body').val();  
     var file_id = scriptJquery(this).closest('form').find('.file_id').val();
     var action_id = scriptJquery(this).closest('form').find('.file').val();;
     var emoji_id = scriptJquery(this).closest('form').find('.select_emoji_id').val();
     if(((!body && (file_id == 0)) && emoji_id == 0))
      return false;
    scriptJquery(this).closest('form').trigger('submit');
    scriptJquery(this).closest('form').addClass('submitting');
    scriptJquery(this).closest('form').append('<div class="sesbasic_loading_cont_overlay" style="display:block;"></div>');
    return false;
   }
});
scriptJquery(document).on('click','.sescmt_media_more',function(){
  var elem = scriptJquery(this).parent().find('.sescmt_media_container');
  if(elem.hasClass('less')){
     elem.removeClass('less');
     elem.css('height','204px');
     scriptJquery(this).text('Show All');
  }else{
     elem.addClass('less');
     elem.css('height','auto'); 
     scriptJquery(this).text('Show Less');
  }
});
scriptJquery(document).on('click','.comment_btn_open',function(){
  var actionId = scriptJquery(this).attr('data-actionid');
  if(!actionId){
    actionId = scriptJquery(this).attr('data-subjectid');
    scriptJquery('#adv_comment_subject_btn_'+actionId).trigger('click'); 
  }else
    scriptJquery('#adv_comment_btn_'+actionId).trigger('click');  
  complitionRequestTrigger(); 
})
var isonCommentBox = isOnEditField = false;
var EditFieldValue = '';
function getDataMentionEditComment (that,data){
  if (scriptJquery(that).attr('data-mentions-input') === 'true') {  
       updateEditValComment(that, data);
  }
}
function updateEditValComment(that,data){
    EditFieldValue = data;
    scriptJquery(that).mentionsInput("update");  
}
var mentiondataarray = [];
scriptJquery(document).on('keyup','.body',function(e){ 
    var data = scriptJquery(this).val();
     EditFieldValue = data;
    var elem = scriptJquery(this).closest("form").find("button[type='submit']");
    if(data.length > 0){
      elem.removeClass("disabled");
    } else {
      if(!elem.hasClass("disabled")){
        elem.addClass("disabled");
      }
    }
});

scriptJquery(document).on('focus','.body',function(){ 
if(!scriptJquery(this).attr('id'))
  scriptJquery(this).attr('id',new Date().getTime());
  if(typeof sesadvancedactivitybigtext == 'undefined')
    sesadvancedactivitybigtext = false;
  isonCommentBox = true;
  var data = scriptJquery(this).val();
  
  if(!scriptJquery(this).val() || isOnEditField){ 
    if(!scriptJquery(this).val() )
      EditFieldValue = '';
    scriptJquery(this).mentionsInput({
        onDataRequest:function (mode, query, callback) {
         scriptJquery.getJSON('sesadvancedcomment/ajax/friends/query/'+query, function(responseData) {
          responseData = _.filter(responseData, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1 });
          callback.call(this, responseData);
        });
      },
      //defaultValue: EditFieldValue,
      onCaret: true
    });
  }
  if(data){
     getDataMentionEditComment(this,data);
  }
  
  if(!scriptJquery(this).parent().hasClass('typehead')){
    scriptJquery(this).hashtags();
    scriptJquery(this).focus();
  }
  autosize(scriptJquery(this));
});
var CommentLikesTooltips;
en4.core.runonce.add(function() {
  // Add hover event to get likes
  scriptJquery(document).on('mouseover','.comments_comment_likes', function(event) {
    var el = scriptJquery(event.target);
    if( !el.data('tip-loaded') ) {
      el.data('tip-loaded', true);
      el.data('tip:title', 'Loading...');
      el.data('tip:text', '');
      var id = el.get('id').match(/\d+/)[0];
      // Load the likes
      var url =  en4.core.baseUrl + 'sesadvancedcomment/index/get-likes';
      var req = scriptJquery.ajax({
        url : url,
        data : {
          format : 'json',
          //type : 'core_comment',
          action_id : el.getParent('li').getParent('li').getParent('li').get('id').match(/\d+/)[0],
          comment_id : id
        },
        success : function(responseJSON) {
          el.data('tip:title', responseJSON.body);
          el.data('tip:text', '');
          CommentLikesTooltips.elementEnter(event, el); // Force it to update the text
        }
      });
    }
  });
  // Add tooltips
  CommentLikesTooltips = new Tips(scriptJquery('.comments_comment_likes'), {
    fixed : true,
    className : 'comments_comment_likes_tips',
    offset : {
      'x' : 48,
      'y' : 16
    }
  });
  // Enable links in comments
  scriptJquery('.comments_body').enableLinks();
});
//reply comment
scriptJquery(document).on('click','.sesadvancedcommentreply',function(e){
  e.preventDefault();
  scriptJquery('.comment_reply_form').hide();
  let elem = scriptJquery(this).closest('.sesadvancedcomment_cnt_li').find('.comments_reply').find('.comment_reply_form');
  elem.show();  
  elem.find('.sesadvancedactivity-comment-form-reply').show();
  var body = elem.find('.sesadvancedactivity-comment-form-reply').find('.comment_form').find('.body');
  //var ownerInfo = scriptJquery.parseJSON(elem.find(".owner-info").html());
  var ownerInfo = scriptJquery.parseJSON(scriptJquery(this).parent().parent().parent().parent().find('.owner-info').html());
  body.focus();
  var data = "";
  body.mentionsInput('val', function(data) {
     data = data;
  });
  if(body.val().length){
    body.val(' ');
  }
  if(!body.val().length){
    scriptJquery(body).mentionsInput("addmention",ownerInfo); 
    body.val(body.val()+' ');
  }
  console.log(body.val());
  complitionRequestTrigger();
})
function sesadvancedcommentlike(action_id, comment_id,obj,page_id,type,sbjecttype,subjectid,guid) {
  var ajax = scriptJquery.ajax({
    url : en4.core.baseUrl + 'sesadvancedcomment/index/like',
    data : {
      format : 'json',
      action_id : action_id,
      page_id : page_id,
      comment_id : comment_id,
      subject : en4.core.subject.guid,
      guid:guid,
      sbjecttype:sbjecttype,
      subjectid:subjectid,
      type:type
    },
    'success' : function(responseHTML) {
      if( responseHTML ) {
        scriptJquery(obj).parent().parent().replaceWith(responseHTML.body);
        en4.core.runonce.trigger();
        complitionRequestTrigger();
      }
    }
  });    
}
function sesadvancedcommentunlike(action_id, comment_id,obj,page_id,type,sbjecttype,subjectid,guid) {
  var ajax = scriptJquery.ajax({
    url : en4.core.baseUrl + 'sesadvancedcomment/index/unlike',
    data : {
      format : 'json',
      page_id : page_id,
      action_id : action_id,
      comment_id : comment_id,
      subject : en4.core.subject.guid,
      sbjecttype:sbjecttype,
      guid:guid,
      subjectid:subjectid,
      type:type
    },
    'success' : function(responseHTML) {
      if(responseHTML){
        scriptJquery(obj).parent().parent().replaceWith(responseHTML.body);
        en4.core.runonce.trigger();
        complitionRequestTrigger();
      }
    }
  });
   ajax
}
//like feed action content
scriptJquery(document).on('click','.sesadvancedcommentunlike',function(){
  var obj = scriptJquery(this);
  var action_id = scriptJquery(this).attr('data-actionid');
  var comment_id = scriptJquery(this).attr('data-commentid');
  var type = scriptJquery(this).attr('data-type');
   var datatext = scriptJquery(this).attr('data-text');
  var likeWorkText = scriptJquery(this).attr('data-like');
  var unlikeWordText = scriptJquery(this).attr('data-unlike');
  
  //check for unlike
  scriptJquery(this).find('i').removeAttr('style');
  scriptJquery(this).find('span').html(likeWorkText);
  scriptJquery(this).removeClass('sesadvancedcommentunlike').removeClass('_reaction').addClass('sesadvancedcommentlike');
  scriptJquery(this).parent().addClass('feed_item_option_like').removeClass('feed_item_option_unlike');
  var ajax = scriptJquery.ajax({
    url : en4.core.baseUrl + 'sesadvancedcomment/index/unlike',
    data : {
      format : 'json',
      action_id : action_id,
      comment_id : comment_id,
      subject : en4.core.subject.guid,
       sbjecttype:scriptJquery(this).attr('data-sbjecttype'),
      subjectid:scriptJquery(this).attr('data-subjectid'),
      type:type
    },
    'success' : function(responseHTML) {
      if( responseHTML ) {
       var elemnt =  scriptJquery(obj).closest('.comment-feed').find('.sesadvcmt_comments').find('.comments_cnt_ul');
       if(elemnt.find('.sesadvcmt_comments_stats').length){
        elemnt = elemnt.find('.sesadvcmt_comments_stats');
        var getPreviousSearchComment = scriptJquery('.comment_stats_'+action_id).html();
        scriptJquery(elemnt).replaceWith(responseHTML.body);
        scriptJquery('.comment_stats_'+action_id).html(getPreviousSearchComment);
       }else
        scriptJquery(elemnt).prepend(responseHTML.body);
        en4.core.runonce.trigger();
        complitionRequestTrigger();
      }
    }
  });
   ajax
});
scriptJquery(document).on("mouseover",".sesadvcmt_hoverbox_wrapper", function(e){
  scriptJquery(this).removeClass("_close");
})
var previousSesadvcommLikeObj;
//unlike feed action content
scriptJquery(document).on('click','.sesadvancedcommentlike',function(){
  var obj = scriptJquery(this);
	previousSesadvcommLikeObj = obj.closest('.sesadvcmt_hoverbox_wrapper');
  var action_id = scriptJquery(this).attr('data-actionid');
  var guid = "";
   var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sespage_switcher_cnt').find('a').first();
   if(!guidItem.length)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesgroup_switcher_cnt').find('a').first();
  if(!guidItem.length)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesbusiness_switcher_cnt').find('a').first();
    if(!guidItem.length)
        var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .estore_switcher_cnt').find('a').first();
   if(guidItem)
    guid = guidItem.data('rel');
  var comment_id = scriptJquery(this).attr('data-commentid');
  var type = scriptJquery(this).attr('data-type');
  var datatext = scriptJquery(this).attr('data-text');
  var subject_id = scriptJquery(this).attr('data-subjectid');
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
    scriptJquery(elem).removeClass('sesadvancedcommentunlike').removeClass('_reaction').addClass('sesadvancedcommentlike');
    scriptJquery(elem).parent().addClass('feed_item_option_like').removeClass('feed_item_option_unlike');
  }else{
  //like  
    scriptJquery(elem).find('i').css('background-image', 'url(' + image + ')');
    scriptJquery(elem).find('span').html(datatext);
    scriptJquery(elem).removeClass('sesadvancedcommentlike').addClass('_reaction').addClass('sesadvancedcommentunlike');
    scriptJquery(elem).parent().addClass('feed_item_option_unlike').removeClass('feed_item_option_like');
  }

// 	var parentObject = previousSesadvcommLikeObj.parent().html();
// 	var parentElem = previousSesadvcommLikeObj.parent();
// 	previousSesadvcommLikeObj.parent().html('');
// 	parentElem.html(parentObject);
	
  var ajax = scriptJquery.ajax({
    url : en4.core.baseUrl + 'sesadvancedcomment/index/like',
    data : {
      format : 'json',
      action_id : action_id,
      comment_id : comment_id,
      subject : en4.core.subject.guid,
      guid : guid ,
       sbjecttype:scriptJquery(this).attr('data-sbjecttype'),
      subjectid:scriptJquery(this).attr('data-subjectid'),
      type:type
    },
    'success' : function(responseHTML) {
      if( responseHTML ) {
       var elemnt =  scriptJquery(obj).closest('.comment-feed').find('._sesadvcmt_comments').find('.comments_cnt_ul');      
       
       if(elemnt.find('.sesadvcmt_comments_stats').length){
        elemnt = elemnt.find('.sesadvcmt_comments_stats');
        if(!action_id)
          action_id = subject_id;
        var getPreviousSearchComment = scriptJquery('.comment_stats_'+action_id).html();
        scriptJquery(elemnt).replaceWith(responseHTML.body);
        scriptJquery('.comment_stats_'+action_id).html(getPreviousSearchComment);
       }else
        scriptJquery(elemnt).prepend(responseHTML.body);
        en4.core.runonce.trigger();
        complitionRequestTrigger();
      }
    }
  });    
  ajax
});
//cancel comment edit
scriptJquery(document).on('click','.sesadvancedcomment_cancel',function(e){
  e.preventDefault();
  var parentElem = scriptJquery(this).closest('.sesadvancedcomment_cnt_li');
  parentElem.find('.comment_edit').remove();
   parentElem.find('.comments_info').show();
   var topParentElement = parentElem.closest('.comments');
  topParentElement = topParentElement.find('.sesadvancedactivity-comment-form').show();
  complitionRequestTrigger();
});
//cancel comment reply edit
scriptJquery(document).on('click','.sesadvancedcomment_cancel_reply',function(e){
  e.preventDefault();
  var parentElem = scriptJquery(this).closest('li');
   parentElem.find('.comments_reply_info').show();
   parentElem.find('.comment_edit').remove();
   complitionRequestTrigger();
});
//cancel file upload image
scriptJquery(document).on('click','.cancel_upload_file',function(e){
  e.preventDefault();
  var id = scriptJquery(this).attr('data-url');
  var value =  scriptJquery(this).parent().parent().parent().find('.comment_form').find('.file_id').val().replace(id+'_album_photo','');
  scriptJquery(this).parent().parent().parent().find('.comment_form').find('.file_id').val(value);
  value = scriptJquery(this).parent().parent().parent().find('.comment_form').find('.file_id').val().replace(id+'_video','');
  scriptJquery(this).parent().parent().parent().find('.comment_form').find('.file_id').val(value);
  scriptJquery(this).parent().hide().remove('');
  complitionRequestTrigger();
})
function getEditCommentMentionData(obj){ 
  scriptJquery(obj).find('.body').mentionsInput('val', function(data) {
     submiteditcomment(obj,data);
  });  
}
//edit comment
scriptJquery(document).on('submit','.sesadvancedactivity-comment-form-edit',function(e){
 e.preventDefault();
 getEditCommentMentionData(this);
});
function submiteditcomment(that,data){
  if(scriptJquery(that).hasClass("submitting")){
    return false;
  }
  scriptJquery(that).addClass("submitting");

  var body = data; 
 var file_id = scriptJquery(that).find('.file_id').val();
 if((!body && file_id == 0))
  return false;

  var formData = new FormData(that);
  formData.append('bodymention', body);
  submitCommentFormAjax = scriptJquery.ajax({
      type:'POST',
      url: en4.core.baseUrl+'sesadvancedcomment/index/edit-comment/',
      data:formData,
      cache:false,
      contentType: false,
      processData: false,
      success:function(data){
        scriptJquery(that).removeClass('submitting');
        scriptJquery(that).find('.sesbasic_loading_cont_overlay').remove();
        try{
          var dataJson = scriptJquery.parseJSON(data);
          if(dataJson.status == 1){
            var parentElem =  scriptJquery(that).parent().parent();
            parentElem.find('.comments_info').find('.comments_body').html(dataJson.content);
            parentElem.find('.comments_info').show();
            parentElem.find('.comment_edit').remove();
            parentElem.closest('.comments').find('.sesadvancedactivity-comment-form').show();
            en4.core.runonce.trigger();
            complitionRequestTrigger();
          //silence
          }else{
            alert('Something went wrong, please try again later');	
          }
          
        }catch(err){
          //silence
        }
      },
      error: function(data){
        //silence
      }
  });   
}
function commentreplyedit(that,data){
  if(scriptJquery(that).hasClass("submitting")){
    return false;
  }
  scriptJquery(that).addClass("submitting");
  var body = data;  
 var file_id = scriptJquery(that).find('.file_id').val();
 if((!body && file_id == 0))
  return false;
  var formData = new FormData(that);
  formData.append('bodymention', body);
  submitCommentFormAjax = scriptJquery.ajax({
      type:'POST',
      url: en4.core.baseUrl+'sesadvancedcomment/index/edit-reply/',
      data:formData,
      cache:false,
      contentType: false,
      processData: false,
      success:function(data){
        scriptJquery(that).removeClass('submitting');
        scriptJquery(that).find('.sesbasic_loading_cont_overlay').remove();
        try{
          var dataJson = scriptJquery.parseJSON(data);
          if(dataJson.status == 1){
            var parentElem =  scriptJquery(that).parent().parent();
            parentElem.find('.comments_reply_info').find('.comments_reply_body').html(dataJson.content);
            parentElem.find('.comments_reply_info').show();
            parentElem.find('.comment_edit').remove();
            en4.core.runonce.trigger();
            complitionRequestTrigger();
          //silence
          }else{
            alert('Something went wrong, please try again later');	
          }
        }catch(err){
          //silence
        }
      },
      error: function(data){
        //silence
      }
  });   
}
function getCommentReplyEditMentionData(obj){ 
  scriptJquery(obj).find('.body').mentionsInput('val', function(data) {
     commentreplyedit(obj,data);
  });  
}
//edit comment reply
scriptJquery(document).on('submit','.sesadvancedactivity-comment-form-edit-reply',function(e){
 e.preventDefault();
 getCommentReplyEditMentionData(this);
});
function commentReply(that,data){
  if(scriptJquery(that).hasClass("submitting")){
    return false;
  }
  scriptJquery(that).addClass("submitting");
  
  var body = data;  
 var file_id = scriptJquery(that).find('.comment_form').find('.file_id').val();
 var emoji_id = scriptJquery(that).find('.select_emoji_id').val();
 var gif_id = scriptJquery(that).find('.select_gif_id').val();
 if(((!body && (file_id == 0)) && emoji_id == 0 && gif_id == 0))
  return false
  if(!scriptJquery(that).find('.select_file').val()){
    scriptJquery(that).find('.select_file').remove();
    executed = true;
  }
  var formData = new FormData(that);
  if(executed == true)
    scriptJquery(that).find('.file_comment_select').parent().append('<input type="file" name="Filedata" class="select_file" multiple="" value="0" style="display:none;">');
  formData.append('bodymention', body);
  //page
  var elem = scriptJquery(that).closest('.comment-feed').find('.feed_item_date ul').find('.sespage_switcher_cnt').find('.sespage_feed_change_option_a');
  if(elem.length){
    guid = elem.attr('data-subject');
    formData.append('guid', guid);
  }
  //group
  var elem = scriptJquery(that).closest('.comment-feed').find('.feed_item_date ul').find('.sesgroup_switcher_cnt').find('.sesgroup_feed_change_option_a');
  if(elem.length){
    guid = elem.attr('data-subject');
    formData.append('guid', guid);
  }
  //business
  var elem = scriptJquery(that).closest('.comment-feed').find('.feed_item_date ul').find('.sesbusiness_switcher_cnt').find('.sesbusiness_feed_change_option_a');
  if(elem.length){
    guid = elem.attr('data-subject');
    formData.append('guid', guid);
  }
    //store
    var elem = scriptJquery(that).closest('.comment-feed').find('.feed_item_date ul').find('.estore_switcher_cnt').find('.estore_feed_change_option_a');
    if(elem.length){
        guid = elem.attr('data-subject');
        formData.append('guid', guid);
    }
  submitCommentFormAjax = scriptJquery.ajax({
      type:'POST',
      url: en4.core.baseUrl+'sesadvancedcomment/index/reply/',
      data:formData,
      cache:false,
      contentType: false,
      processData: false,
      success:function(data){
        scriptJquery(that).removeClass('submitting');
        scriptJquery(that).find('.sesbasic_loading_cont_overlay').remove();
        try{
          var dataJson = scriptJquery.parseJSON(data);
          if(dataJson.status == 1){
            //scriptJquery(dataJson.content).insertBefore(scriptJquery(that).closest('.comments_reply').find('.comment_reply_form').find('.sesadvancedactivity-comment-form-reply'));
						scriptJquery(that).parent().parent().find('.comments_reply_cnt').append(dataJson.content);
            scriptJquery(that).find('._form_container').find('.comment_form').find('.body').val('');
            scriptJquery(that).find('._form_container').find('.comment_form').find('.body').css('height','auto');
            scriptJquery(that).find('._form_container').find('.comment_form').find('.body').parent().parent().find('div').eq(0).html('');
            var fileElem = scriptJquery(that).find('._form_container').find('.comment_form').find('._sesadvcmt_post_icons').find('span');
            fileElem.find('.select_file').val('');
            fileElem.find('.file_id').val('');
            fileElem.find('.select_emoji_id').val('');
            fileElem.find('.select_gif_id').val('');
            scriptJquery(that).find('._form_container').find('.uploaded_file').html('');
            scriptJquery(that).find('._form_container').find('.uploaded_file').hide();
            scriptJquery(that).find('._form_container').find('.upload_file_cnt').remove();
            en4.core.runonce.trigger();
            complitionRequestTrigger();
          //silence
          }else{
            alert('Something went wrong, please try again later');	
          }
        }catch(err){
          //silence
        }
      },
      error: function(data){
        //silence
      }
  });   
}
//create reply comment
scriptJquery(document).on('submit','.sesadvancedactivity-comment-form-reply',function(e){
 e.preventDefault();
 getCommentMentionData(this);
});
function getCommentMentionData(obj){ 
  scriptJquery(obj).find('.body').mentionsInput('val', function(data) {
     commentReply(obj,data);
  });  
}
//comment edit form
scriptJquery(document).on('click','.sesadvancedcomment_edit',function(e){
  e.preventDefault();  
  var parentElem = scriptJquery(this).closest('.sesadvancedcomment_cnt_li');
  var topParentElement = parentElem.closest('.comments');
  topParentElement = topParentElement.find('.sesadvancedactivity-comment-form').hide();
  parentElem.find('.comments_info').hide();
  var textBody = parentElem.find('.comments_info').find('.comments_body').find('.comments_body_actual').html();
  if(textBody != ""){
    textBody = textBody.split('<br>').join('');
  }
  //Feeling work
  EditFieldValue = textBody;
  
  
  isOnEditField = true;
  var datamention = parentElem.find('.comments_info').find('.comments_body').find('#data-mention').html();
  if(datamention){
    mentionsCollectionValEdit = JSON.parse(datamention);
  }
  var module = parentElem.find('.comments_info').find('.comments_body').find('.comments_body_actual').attr('rel');
  module = '<input type="hidden" name="modulecomment" value="'+module+'"><input type="hidden"  class="select_emoji_id" name="emoji_id" value="0">';
  var subject = parentElem.find('.comments_info').find('.comments_body').find('.comments_body_actual').attr('data-subject');
  var subjectid = parentElem.find('.comments_info').find('.comments_body').find('.comments_body_actual').attr('data-subjectid');
  var subjectInputs = '';
  if(subject){
    subjectInputs = '<input type="hidden" name="resource_type" value="'+subject+'"><input type="hidden" name="resource_id" value="'+subjectid+'">';  
  }
  var fileid,filesrc,image = '';
  var display = 'none';
  var comment_id = parentElem.attr('id').replace('comment-','');
  fileid = 0;
  files = '';
  filesLength = parentElem.find('.comments_info').find('.comments_body').find('.comment_image');
  if(filesLength.length){
    for(var i =0; i<filesLength.length;i++){
      if(fileid == 0)
        fileid = '';
     if(scriptJquery(filesLength[i]).attr('data-type') == 'album_photo'){
      fileid = fileid+scriptJquery(filesLength[i]).attr('data-fileid')+'_album_photo,';
      var videoBtn = '';
     }else{
      fileid = fileid+scriptJquery(filesLength[i]).attr('data-fileid')+'_video,';
      var videoBtn = '<a href="javascript:;" class="sescmt_play_btn fa fa-play"></a>';
     }
     filesrc = scriptJquery(filesLength[i]).find('img').attr('src');
     image = '<img src="'+filesrc+'"><a href="javascript:;" data-url="'+scriptJquery(filesLength[i]).attr('data-fileid')+'" class="cancel_upload_file fas fa-times" title="Cancel"></a>'+videoBtn;
     display = 'block';
     files = '<div class="uploaded_file" style="display:block;">'+image+'</div>'+files;
    }
  }
  videoLink = '';
  if(videoModuleEnable == 1 ){
     videoLink = '<span><a href="javascript:;" class="video_comment_select"></a></span>';
  }
  imageLink = '';
  if(AlbumModuleEnable == 1 ){
     imageLink = '<a href="javascript:;" class="file_comment_select"></a>';
  }
  sesfeelingEmojis = '';
  if(typeof sesemojiEnable != "undefined" && sesemojiEnable == 1) {
    sesfeelingEmojis = '<span class="sesact_post_tool_i tool_i_feelings"><a href="javascript:;" class="feeling_emoji_comment_select"></a></span>';
  }
  var d = new Date();
  var time = d.getTime();
  var html = '<div class="comment_edit _form_container sesbasic_clearfix"><form class="sesadvancedactivity-comment-form-edit" method="post"><div class="comment_form sesbasic_clearfix"><textarea class="body" name="body" id="'+time+'" cols="45" rows="1" placeholder="'+en4.core.language.translate("Write a comment...")+'">'+textBody+'</textarea><div class="_sesadvcmt_post_icons sesbasic_clearfix"><span>'+imageLink+'<input type="file" name="Filedata" class="select_file" multiple style="display:none;">'+module+subjectInputs+'<input type="hidden" name="file_id" class="file_id" value="'+fileid+'"><input type="hidden" class="file" name="comment_id" value="'+comment_id+'"></span>'+videoLink+'<span><a href="javascript:;" class="emoji_comment_select"></a></span>'+sesfeelingEmojis+'</div></div><div class="uploaded_file"  style="display:none;"></div><div class="upload_file_cnt">'+files+'</div><div class="sesadvcmt_btns" style="margin-top:0px;"><a href="javascript:;" class="sesadvancedcomment_cancel">cancel</a></div></form></div>';
  
  scriptJquery(html).insertBefore(parentElem.find('.comments_info'));
  parentElem.parent().find('.comment_edit').find('form').find('.comment_form').find('.body').trigger('focus');
  complitionRequestTrigger();
  scriptJquery('#'+time).val(textBody);
  scriptJquery('#'+time).trigger("focus");
});
//comment reply edit form
scriptJquery(document).on('click','.sesadvancedcomment_reply_edit',function(e){
  e.preventDefault();   
  var parent = scriptJquery(this).closest('.comments_reply_cnt');
  parent.find('.comment_edit').remove();
  parent.find('.comments_reply_info').show();
  var parentElem = scriptJquery(this).closest('.comments_reply_info');
   parentElem.find('.comments_reply').find('.comment_reply_form').find('.sesadvancedactivity-comment-form-reply').hide();
  parentElem.hide();
  var textBody = parentElem.find('.comments_reply_body').find('.comments_reply_body_actual').html();
  if(textBody != ""){
    textBody = textBody.split('<br>').join('');
  }
  //Feeling work
  EditFieldValue = textBody;
  
  
  isOnEditField = true;
  var datamention = parentElem.find('.comments_reply_body').find('#data-mention').html();
  if(datamention){
    mentionsCollectionValEdit = JSON.parse(datamention);
  }
  var module = parentElem.find('.comments_reply_body').find('.comments_reply_body_actual').attr('rel');
  module = '<input type="hidden" name="modulecomment" value="'+module+'"><input type="hidden" name="emoji_id" class="select_emoji_id" value="0">';
  var subject = parentElem.find('.comments_reply_body').find('.comments_reply_body_actual').attr('data-subject');
  var subjectid = parentElem.find('.comments_reply_body').find('.comments_reply_body_actual').attr('data-subjectid');
  var subjectInputs = '';
  if(subject){
    subjectInputs = '<input type="hidden" name="resource_type" value="'+subject+'"><input type="hidden" name="resource_id" value="'+subjectid+'">';  
  }
  var fileid,filesrc,image = '';
  var display = 'none';
  var comment_id = parentElem.closest('li').attr('id').replace('comment-','');
  fileid = 0;
  files = '';
  filesLength = parentElem.find('.comments_reply_body').find('.comment_reply_image');
  if(filesLength.length){
    for(var i =0; i<filesLength.length;i++){
      if(fileid == 0)
        fileid = '';
     if(scriptJquery(filesLength[i]).attr('data-type') == 'album_photo'){
      fileid = fileid+scriptJquery(filesLength[i]).attr('data-fileid')+'_album_photo,';
      var videoBtn = '';
     }else{
      fileid = fileid+scriptJquery(filesLength[i]).attr('data-fileid')+'_video,';
      var videoBtn = '<a href="javascript:;" class="play_upload_file fa fa-play"></a>';
     }
     filesrc = scriptJquery(filesLength[i]).find('img').attr('src');
     image = '<img src="'+filesrc+'"><a href="javascript:;" data-url="'+scriptJquery(filesLength[i]).attr('data-fileid')+'" class="cancel_upload_file fas fa-times" title="Cancel"></a>'+videoBtn;
     display = 'block';
     files = '<div class="uploaded_file" style="display:block;">'+image+'</div>'+files;
    }
  }
  videoLink = '';
  if(videoModuleEnable == 1 ){
     videoLink = '<span><a href="javascript:;" class="video_comment_select"></a></span>';
  }
  imageLink = '';
  if(AlbumModuleEnable == 1 ){
     imageLink = '<a href="javascript:;" class="file_comment_select"></a>';
  }
 
  //Feeling Work
  sesfeelingEmojis = '';
  if(typeof sesemojiEnable != "undefined" && sesemojiEnable == 1) {
    sesfeelingEmojis = '<span class="sesact_post_tool_i tool_i_feelings"><a href="javascript:;" class="feeling_emoji_comment_select"></a></span>';
  }
  
  var d = new Date();
  var time = d.getTime();
  var html = '<div class="comment_edit _form_container sesbasic_clearfix"><form class="sesadvancedactivity-comment-form-edit-reply" method="post"><div class="comment_form sesbasic_clearfix"><textarea class="body" id="'+time+'" name="body" cols="45" rows="1" placeholder="Write a reply...">'+textBody+'</textarea><div class="_sesadvcmt_post_icons sesbasic_clearfix"><span>'+imageLink+'<input type="file" name="Filedata" class="select_file" multiple style="display:none;">'+module+subjectInputs+'<input type="hidden" name="file_id" class="file_id" value="'+fileid+'"><input type="hidden" class="file" name="comment_id" value="'+comment_id+'"></span>'+videoLink+'<span><a href="javascript:;" class="emoji_comment_select"></a></span>'+sesfeelingEmojis+'</div></div><div class="uploaded_file" style="display:none;"></div><div class="upload_file_cnt">'+files+'</div><div class="sesadvcmt_btns" style="margin-top:0px;"><a href="javascript:;" class="sesadvancedcomment_cancel_reply">cancel</a></div></form></div>';  
  scriptJquery(html).insertBefore(parentElem);
  //var textArea = parentElem.parent().find('.comment_edit').find('form').find('.comment_form').find('.body').focus();
  //autosize(textArea);
  complitionRequestTrigger();
  scriptJquery('#'+time).val(textBody);
  scriptJquery('#'+time).trigger("focus");
});
//video in comment
var clickVideoAddBtn;
scriptJquery(document).on('click','.video_comment_select',function(e){
   clickVideoAddBtn = this;
   if(youtubePlaylistEnable == 1){
    var text = 'Paste a Youtube or Vimeo link here';  
   }else
    var text = 'Paste Vimeo link here';
   en4.core.showError('<div class="sescmt_add_video_popup"><div class="sescmt_add_video_popup_header">Add Video</div><div class="sescmt_add_video_popup_cont"><p><input type="text" value="" placeholder="'+text+'" id="sesadvvideo_txt"><img src="application/modules/Core/externals/images/loading.gif" style="display:none;" id="sesadvvideo_img"></p></div><div class="sescmt_add_video_popup_btm"><button type="button" id="sesadvbtnsubmit">Add</button><button onclick="Smoothbox.close()">Close</button></div></div>');
	 scriptJquery ('.sescmt_add_video_popup').parent().parent().addClass('sescmt_add_video_popup_wrapper sesbasic_bxs');
   scriptJquery('#sesadvvideo_txt').focus();
});
scriptJquery(document).on('click','#sesadvbtnsubmit',function(e){
  var value = scriptJquery('#sesadvvideo_txt').val();
  if(!value){
    scriptJquery('#sesadvvideo_txt').css('border','1px solid red');
    return false;
  }else{
    scriptJquery('#sesadvvideo_txt').css('border','');  
  }
  if(youtubePlaylistEnable == 1 && validYoutube(value))
    type = 1;  
  else if(validVimeo(value))
    type = 2;
  else{
    scriptJquery('#sesadvvideo_txt').css('border','1px solid red');
    return false;
  }
  
  scriptJquery('#sesadvbtnsubmit').prop('disabled',true);
  scriptJquery('#sesadvvideo_img').show();
  
   scriptJquery.ajax({
    method:"POST",
    url : en4.core.baseUrl + videoModuleName+'/index/compose-upload/format/json/c_type/wall',
    data : {
      format : 'json',
      uri:value,
      type:type
    },
    'success' : function(responseHTML) {
      console.log(responseHTML.status,'responseHTML.status')
      if(typeof responseHTML.status != 'undefined' && responseHTML.status){
         var videoid = responseHTML.video_id;
         var src = responseHTML.src;
         var form = scriptJquery(clickVideoAddBtn).closest('form');
         if(!form.find('.upload_file_cnt').length){
            var container = scriptJquery('<div class="upload_file_cnt"></div>').insertAfter(scriptJquery(form).find('.uploaded_file')); 
          }else
            var container = form.find('.upload_file_cnt');
          var uploadFile = scriptJquery('<div class="uploaded_file"></div>')
          var uploadImageLoader = scriptJquery('<img src="application/modules/Core/externals/images/loading.gif" class="_loading" />').appendTo(uploadFile);
          scriptJquery(uploadFile).appendTo(container);
          if(scriptJquery(form).find('.file_id').val() == 0)
            uploadFileId = '';
          else
            uploadFileId = scriptJquery(form).find('.file_id').val();
          scriptJquery(form).find('.file_id').val(uploadFileId+videoid+'_video'+',');
          scriptJquery(uploadFile).html('<img src="'+src+'"><a href="javascript:;" data-url="'+videoid+'" class="cancel_upload_file fas fa-times" title="Cancel"></a><a href="javascript:;" class="sescmt_play_btn fa fa-play"></a>');
          complitionRequestTrigger();
          Smoothbox.close();
      }else{
         scriptJquery('#sesadvvideo_txt').css('border','1px solid red');
      }
      scriptJquery('#sesadvbtnsubmit').prop('disabled',false);
      scriptJquery('#sesadvvideo_img').hide();
    }
  });
});
function validYoutube(myurl){
  var matches = myurl.match(/watch\?v=([a-zA-Z0-9\-_]+)/);
  if (matches || myurl.indexOf('youtu.be') > -1)
     return true;
  else
    return false;
}
function validVimeo(myurl){
  //var myurl = "https://vimeo.com/23374724";
  if (myurl.indexOf('https://vimeo.com') >= 0 ) { 
     return true;
  } else { 
      return false;
  };  
}
//click on reply reply
scriptJquery(document).on('click','.sesadvancedcommentreplyreply',function(e){
  e.preventDefault();
  scriptJquery('.comment_reply_form').hide();
  var parent = scriptJquery(this).closest('.comments_reply');
  let elem = parent.find('.comment_reply_form');
 
  elem.show();
  elem.find('.sesadvancedactivity-comment-form-reply').show();
  var body = elem.find('.sesadvancedactivity-comment-form-reply').find('.comment_form').find('.body');
  
  var ownerInfo = scriptJquery.parseJSON(scriptJquery(this).parent().parent().parent().parent().find('.owner-info').html());
  body.focus();
  var data = "";
  body.mentionsInput('val', function(data) {
     data = data;
  });  
  if(body.val().length){
    body.val(' ');
  }
  if(!body.val().length){
    scriptJquery(body).mentionsInput("addmention",ownerInfo); 
    body.val(body.val()+' ');
  }
  complitionRequestTrigger();

});
//view more comment
function sesadvancedcommentactivitycomment(action_id,page,obj,subjecttype){
  var type = scriptJquery(obj).closest('.comments_cnt_ul');
  if(type.length){
    type = type.find('.sesadvcmt_pulldown_wrapper').find('.sesadvcmt_pulldown').find('.sesadvcmt_pulldown_cont').find('.search_adv_comment').find('li > a.active').data('type');
  }else
    type = '';
  if(typeof subjecttype != 'undefined'){
    var url = en4.core.baseUrl + 'sesadvancedcomment/comment/list';
    viewcomment = 0;
  }
  else{
    var url = en4.core.baseUrl + 'sesadvancedcomment/index/viewcomment';
    viewcomment = 1;
  }
  scriptJquery.ajax({
      'url' : url,
      'data' : {
        'format' : 'html',
        'page' : page,
        'action_id':action_id,
        'id':action_id,
        'type':subjecttype,
        'searchtype':type,
        'viewcomment':viewcomment,
      },
      'success' : function(responseHTML) {
        if( responseHTML ) {
          try{
            var dataJson = scriptJquery.parseJSON(responseHTML);
            dataJson = dataJson.body;
          }catch(err){
             var dataJson = responseHTML;
          }
          var onbView = scriptJquery(obj).closest('.comment-feed').find('.comments').find('.comments_cnt_ul').find('.comment_view_more');
          var previousComments = scriptJquery(obj).closest('.comment-feed').find('.comments').find('.comments_cnt_ul > li').map((x,y)=>{if(scriptJquery(y).attr("id")){return scriptJquery(y).attr("id")}});
          dataJson = scriptJquery(dataJson);
          dataJson.find(".comments_cnt_ul > li#"+previousComments.toArray().join(", li#")+"").remove();
          dataJson = dataJson.find(".comments_cnt_ul").html();
          var elem = scriptJquery(obj).closest('.comment-feed').find('.comments').find('.comments_cnt_ul'); 
          if(typeof activitycommentreverseorder != "undefined" && activitycommentreverseorder){
            scriptJquery(dataJson).insertAfter(elem.find("li[id^='comment']:last"));
          } else {
            scriptJquery(dataJson).insertBefore(elem.find("li[id^='comment']:first"));
          }
          onbView.remove();
          en4.core.runonce.trigger();
          complitionRequestTrigger();
        }
      }
    })
  
}
//view more comment
function sesadvancedcommentactivitycommentreply(action_id,comment_id,page,obj,module,type){
  if(typeof type == 'undefined')
    var url = en4.core.baseUrl + 'sesadvancedcomment/index/viewcommentreply';
  else
    var url = en4.core.baseUrl + 'sesadvancedcomment/index/viewcommentreplysubject';
    scriptJquery.ajax({
      'url' : url,
      'data' : {
        'format' : 'html',
        'page' : page,
        'comment_id':comment_id,
        'action_id':action_id,
        'moduleN':module,
        'type':type,
      },
      'success' : function(responseHTML) {
        if( responseHTML ) {
          var dataJson = scriptJquery.parseJSON(responseHTML);
          var onbView = scriptJquery(obj).closest('.comment_reply_view_more');
          onbView.parent().prepend(dataJson.body);
          onbView.remove();
          en4.core.runonce.trigger();
          complitionRequestTrigger();
        }
      }
    })
  
}

//open url in smoothbox
scriptJquery(document).on('click','.sescommentsmoothbox',function(e){
  e.preventDefault();
  var url = scriptJquery(this).attr('href');
  sessmoothboxopen(this);
	parent.Smoothbox.close;
	return false;
})
//comment button click
scriptJquery(document).on('click','.sesadvanced_comment_btn',function(e){
  var commentCnt = scriptJquery(this).closest('.comment-feed').find('.comments');
  if(scriptJquery(this).hasClass('active')){
   // scriptJquery(this).removeClass('active');
   // commentCnt.hide();
   // return;
  }  
  scriptJquery(this).addClass('active');
  commentCnt.show();
  commentCnt.find('.advcomment_form').show();
  body = commentCnt.find('.advcomment_form').find('.comment_form').find('.body');
  body.focus();
  body.focus();
  complitionRequestTrigger();
  return;
});

function getMentionData(obj){ 
  scriptJquery(obj).find('.body').mentionsInput('val', function(data) {
     submitCommentForm(obj,data);
  });  
}
function submitCommentForm(that,data){
  var body = data;  
  if(scriptJquery(that).hasClass("submitting")){
    return false;
  }
  scriptJquery(that).addClass("submitting");
 var file_id = scriptJquery(that).find('.file_id').val();
 var action_id = scriptJquery(that).find('.file').val();;
 var emoji_id = scriptJquery(that).find('.select_emoji_id').val();
 var gif_id = scriptJquery(that).find('.select_gif_id').val();
 var attachment = scriptJquery(that).find('._compose-link-body').length;
 if(((!body && (file_id == 0)) && emoji_id == 0 && gif_id == 0 && attachment == 0))
  return false;
  var guid = "";
  var executed = false;
  if(!scriptJquery(that).closest(".advcomment_form").find('.select_file').val()){
    scriptJquery(that).closest(".advcomment_form").find('.select_file').remove();
    executed = true;
  }
  
   var formData = new FormData(that);
   if(executed == true)
    scriptJquery(that).find('.file_comment_select').parent().append('<input type="file" name="Filedata" class="select_file" multiple="" value="0" style="display:none;">');
  //page
  var elem = scriptJquery(that).closest('.comment-feed').find('.feed_item_date ul').find('.sespage_switcher_cnt').find('.sespage_feed_change_option_a');
  if(elem.length){
    guid = elem.attr('data-subject');
    formData.append('guid', guid);
  }
  //group
  var elem = scriptJquery(that).closest('.comment-feed').find('.feed_item_date ul').find('.sesgroup_switcher_cnt').find('.sesgroup_feed_change_option_a');
  if(elem.length){
    guid = elem.attr('data-subject');
    formData.append('guid', guid);    
  }
  //business
  var elem = scriptJquery(that).closest('.comment-feed').find('.feed_item_date ul').find('.sesbusiness_switcher_cnt').find('.sesbusiness_feed_change_option_a');
  if(elem.length){
    guid = elem.attr('data-subject');
    formData.append('guid', guid);
  }
    //store
    var elem = scriptJquery(that).closest('.comment-feed').find('.feed_item_date ul').find('.estore_switcher_cnt').find('.estore_feed_change_option_a');
    if(elem.length){
        guid = elem.attr('data-subject');
        formData.append('guid', guid);
    }
  formData.append('bodymention', body);
  submitCommentFormAjax = scriptJquery.ajax({
      type:'POST',
      url: en4.core.baseUrl+'sesadvancedcomment/index/comment/',
      data:formData,
      cache:false,
      contentType: false,
      processData: false,
      success:function(data){
        scriptJquery(that).removeClass('submitting');
        scriptJquery(that).find('.sesbasic_loading_cont_overlay').remove();
        try{
          var dataJson = scriptJquery.parseJSON(data);
          if(dataJson.status == 1){
            var elemS = scriptJquery(that).closest('.comment-feed').find('.comments').find('.comments_cnt_ul');
            var getPreviousSearchComment = scriptJquery('.comment_stats_'+action_id).html();
            if(elemS.find("li[id^='comment']").length){
              if(typeof activitycommentreverseorder != "undefined" && activitycommentreverseorder){
                scriptJquery(dataJson.content).insertBefore(elemS.find("li[id^='comment']:first"));
              } else {
                scriptJquery(dataJson.content).insertAfter(elemS.find("li[id^='comment']:last"));
              }
            } else {
              elemS.append(dataJson.content);
            }
            var elemC = scriptJquery(that).closest('.comment-feed').find('._comments').find('.comments_cnt_ul');
            if(elemC.find('.sesadvcmt_comments_stats').length){
              elemC.find('.sesadvcmt_comments_stats').replaceWith(dataJson.commentStats);
              var commentCount = elemC.find('.sesadvcmt_comments_stats').find('a.comment_btn_open').html();
            }else{
              elemC.prepend(dataJson.commentStats);
              var commentCount = elemC.find('.sesadvcmt_comments_stats').find('a.comment_btn_open').html();
            }

            scriptJquery('.comment_stats_'+action_id).html(getPreviousSearchComment).find('a.comment_btn_open').html(commentCount);
            scriptJquery(that).closest('.comment-feed').find('.comments').find('.sesadvancedactivity-comment-form').find('._form_container').find('.comment_form').find('.body').val('');
						scriptJquery(that).closest('.comment-feed').find('.comments').find('.sesadvancedactivity-comment-form').find('._form_container').find('.comment_form').find('.body').css('height','auto');
           var fileElem =  scriptJquery(that).closest('.comment-feed').find('.comments').find('.sesadvancedactivity-comment-form').find('._form_container').find('.comment_form').find('._sesadvcmt_post_icons').find('span');
           fileElem.find('.select_file').val('');
           fileElem.find('.select_emoji_id').val('');
           fileElem.find('.select_gif_id').val('');
           fileElem.find('.file_id').val('0');
           scriptJquery(that).closest('.comment-feed').find('.comments').find('.sesadvancedactivity-comment-form').find('._form_container').find('.link_preview').remove();
           scriptJquery(that).closest('.comment-feed').find('.comments').find('.sesadvancedactivity-comment-form').find('._form_container').find('.uploaded_file').html('');
            scriptJquery(that).closest('.comment-feed').find('.comments').find('.sesadvancedactivity-comment-form').find('._form_container').find('.upload_file_cnt').remove();
            en4.core.runonce.trigger();
            complitionRequestTrigger();
          //silence
          }else{
            alert('Something went wrong, please try again later');	
          }
        }catch(err){
          //silence
        }
      },
      error: function(data){
        //silence
      }
  });   
}
scriptJquery(document).on('submit','.sesadvancedactivity-comment-form',function(e){
 e.preventDefault();
 getMentionData(this);
});
//upload image in comment
scriptJquery(document).on('click','.file_comment_select',function(e){
  scriptJquery(this).closest(".advcomment_form").find('.select_file').trigger('click');
});
//input file change value
scriptJquery(document).on('change','.select_file',function(e){
  var files = this.files;
   for (var i = 0; i < files.length; i++) 
   {
			var url = files[i].name;
    	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
			if((ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'gif' || ext == 'GIF' || ext == "webp")){
				uploadImageOnServer(this,files[i]);
			}
   }
    scriptJquery(this).val('');
});

function uploadImageOnServer(that,file){
  var form = scriptJquery(that).closest('form');
  if(!form.find('.upload_file_cnt').length){
    var container = scriptJquery('<div class="upload_file_cnt"></div>').insertAfter(scriptJquery(form).find('.uploaded_file')); 
  }else
    var container = form.find('.upload_file_cnt');
  container.find('.file_comment_select').remove();
  var uploadFile = scriptJquery('<div class="uploaded_file"></div>')
  var uploadImageLoader = scriptJquery('<img src="application/modules/Core/externals/images/loading.gif" class="_loading" />').appendTo(uploadFile);
  scriptJquery(uploadFile).appendTo(container);
  complitionRequestTrigger();
  var formData = new FormData(scriptJquery(that).closest('form').get(0));
  formData.append('Filedata', file);
  submitCommentFormAjax = scriptJquery.ajax({
      type:'POST',
      url: en4.core.baseUrl+'sesadvancedcomment/index/upload-file/',
      data:formData,
      cache:false,
      contentType: false,
      processData: false,
      success:function(data){
        var dataJson = data;
        try{
          var dataJson = scriptJquery.parseJSON(data);
          if(dataJson.status == 1){
            if(scriptJquery(form).find('.file_id').val() == 0)
              uploadFileId = '';
            else
              uploadFileId = scriptJquery(form).find('.file_id').val();
            scriptJquery(form).find('.file_id').val(uploadFileId+dataJson.photo_id+'_album_photo'+',');
            scriptJquery(uploadFile).html('<img src="'+dataJson.src+'"><a href="javascript:;" data-url="'+dataJson.photo_id+'" class="cancel_upload_file fas fa-times" title="Cancel"></a>');
            complitionRequestTrigger();
            container.find('.file_comment_select').remove();
            container.append(`<div class="file_comment_select advact_compose_photo_uploader" title="Choose a file to upload"><i class="fa fa-plus"></i></div>`);
              //silence
          }else{
            //scriptJquery(form).find('.file_id').val('');
            //scriptJquery(form).find('.uploaded_file').hide();
            scriptJquery(uploadFile).append('<a href="javascript:;" class="cancel_upload_file fas fa-times" title="Cancel"></a>');	
          }
        }catch(err){
          scriptJquery(uploadFile).append('<a href="javascript:;" class="cancel_upload_file fas fa-times" title="Cancel"></a>');	
          //silence
        }
      },
      error: function(data){
        scriptJquery(uploadFile).append('<a href="javascript:;" class="cancel_upload_file fas fa-times" title="Cancel"></a>');	
        //silence
      }
  }); 
  
}
//emoji select in comment
scriptJquery(document).click(function(e) {
  if(scriptJquery(e.target).hasClass('gif_comment_select') || scriptJquery(e.target).hasClass('emoji_comment_select') || scriptJquery(e.target).hasClass('feeling_emoji_comment_select')  || scriptJquery(e.target).attr('id') == 'sesadvancedactivityemoji-edit-a' || scriptJquery(e.target).attr('id') == "emotions_target" || scriptJquery(e.target).attr('id') == "sesadvancedactivity_feeling_emojis" || scriptJquery(e.target).attr('id') == 'sesadvancedactivity_feeling_emojisa')
    return;
  var container = scriptJquery('.ses_emoji_container').eq(0);
  if ((!container.is(e.target) && container.has(e.target).length === 0) && !scriptJquery(e.target).closest('.ses_emoji_container').length) {
     scriptJquery('.emoji_comment_select').removeClass('active');
     scriptJquery('.ses_emoji_container').hide();
  }
  if(scriptJquery(e.target).closest('.ses_emoji_container').length && !scriptJquery(e.target).hasClass("exit_gif_btn") && !scriptJquery(e.target).parent().hasClass('_sesadvgif_gif')){
   // scriptJquery('.ses_emoji_container').show();
  }
  //Feeling Plugin: Emojis Work
  var container = scriptJquery('.ses_feeling_emoji_container');
  if ((!container.is(e.target) && container.has(e.target).length === 0)) {
    scriptJquery('.feeling_emoji_comment_select').removeClass('active');
    scriptJquery('.ses_feeling_emoji_container').hide();
  }
  //Feeling Plugin: Emojis Work
  
});

var requestEmojiA;
scriptJquery(document).on('click','#sesadvancedactivityemoji-statusbox',function(){
  scriptJquery("#emoji_close").hide();
  scriptJquery("#sticker_close").hide();
    var topPositionOfParentDiv =  scriptJquery(this).offset().top + 35;
    topPositionOfParentDiv = topPositionOfParentDiv;
    var leftSub = 264;
    var leftPositionOfParentDiv =  scriptJquery(this).offset().left - leftSub;
    leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
    scriptJquery(this).parent().find('.ses_emoji_container').css('right',0);
    scriptJquery(this).parent().find('.ses_emoji_container').show();

    if(scriptJquery(this).hasClass('active')){
      scriptJquery(this).removeClass('active');
      scriptJquery('#sesadvancedactivityemoji_statusbox').hide();
      return false;
     }
      scriptJquery(this).addClass('active');
      
      scriptJquery('#sesadvancedactivityemoji_statusbox').show();
      if(scriptJquery(this).hasClass('complete'))
        return false;

       var that = this;
       var url = en4.core.baseUrl + 'sesadvancedactivity/ajax/emoji/';
       requestEmojiA = scriptJquery.ajax({
        url : url,
        data : {
          format : 'html',
        },
        evalScripts : true,
        success : function( responseHTML) {
          scriptJquery('#sesadvancedactivityemoji_statusbox').find('.ses_emoji_container_inner').find('.ses_emoji_holder').html(responseHTML);
          scriptJquery(that).addClass('complete');
          scriptJquery('#sesadvancedactivityemoji_statusbox').show();
         scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
            theme:"minimal-dark"
         });
        }
      });
});

scriptJquery(document).on('click','a.emoji_comment_select',function(){
  scriptJquery("#emoji_close").hide();
  scriptJquery('.emoji_comment_select').removeClass('active');
  scriptJquery('.feeling_emoji_comment_select').removeClass('active');
  scriptJquery('.ses_feeling_emoji_container').hide();
  scriptJquery("#sticker_close").show();
  clickEmojiContentContainer = this;
  scriptJquery('.emoji_content').removeClass('from_bottom');
  var topPositionOfParentDiv =  scriptJquery(this).offset().top + 35;
	topPositionOfParentDiv = topPositionOfParentDiv;
  if(scriptJquery(this).hasClass('sesadv_outer_emoji')){
    var leftSub = 265;  
  }else if(scriptJquery(this).hasClass('activity_emoji_content_a') && typeof sesadvancedactivityDesign != 'undefined' && sesadvancedactivityDesign == 2){
    var leftSub = 55;  
    var left = (scriptJquery(this).width()+leftSub)/3;    
    scriptJquery('._emoji_content ').find(".ses_emoji_container_arrow").css('left',left);
  }else{
    var leftSub = 264;
    scriptJquery('._emoji_content').find(".ses_emoji_container_arrow").css('left','');
  }
	var leftPositionOfParentDiv =  scriptJquery(this).offset().left - leftSub;
	leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
  if(scriptJquery('#ses_media_lightbox_container').length || scriptJquery('#ses_media_lightbox_container_video').length)
    topPositionOfParentDiv = topPositionOfParentDiv + offsetY;

	scriptJquery('._emoji_content').css('top',topPositionOfParentDiv+'px');
	scriptJquery('._emoji_content').css('left',leftPositionOfParentDiv).css('z-index',100);
  scriptJquery('._emoji_content').show();
  var eTop = scriptJquery(this).offset().top; //get the offset top of the element
  var availableSpace = scriptJquery(document).height() - eTop;
  if(availableSpace < 400 && !scriptJquery('#ses_media_lightbox_container').length){
      scriptJquery('.emoji_content').addClass('from_bottom');
  }
  if(scriptJquery(this).hasClass('active')){
    scriptJquery(this).removeClass('active');
    scriptJquery('.emoji_content').hide();
    complitionRequestTrigger();
    return;
   }
    scriptJquery(this).addClass('active');
    scriptJquery('.emoji_content').show();
    scriptJquery("#sesadvancedactivityemoji_statusbox").hide();
    complitionRequestTrigger();

    if(!scriptJquery('.ses_emoji_holder').find('.empty_cnt').length)
      return;
     var that = this;
     var url = en4.core.baseUrl+'sesadvancedcomment/index/emoji/',
     requestComentEmoji = scriptJquery.ajax({
      url : url,
      data : {
        format : 'html',
      },
      evalScripts : true,
      success : function( responseHTML) {
        scriptJquery("#emoji_close").hide();
        scriptJquery('.emoji_comment_select').removeClass('active');
        scriptJquery('.feeling_emoji_comment_select').removeClass('active');
        scriptJquery('.ses_feeling_emoji_container').hide();

        scriptJquery('.emoji_content').find('.ses_emoji_container_inner').find('.ses_emoji_holder').html(responseHTML);
        scriptJquery(that).addClass('complete');
        scriptJquery('._emoji_content').show();
        complitionRequestTrigger();
				scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
					theme:"minimal-dark"
				});
        scriptJquery('._emoji_content').find(".ses_emoji_search_container").show();
        if(enablesearch == 0){
          scriptJquery('._emoji_content').find(".ses_emoji_search_bar").hide();
        }
      }
    });
});
scriptJquery(document).on('click','.select_comment_emoji_adv > img',function(e){
  var code = scriptJquery(this).parent().parent().attr('rel');
  var form = scriptJquery(this).closest('form');
  if(!scriptJquery(form).find('.comment_form').length){
    var html = form.find('.body').html();
    form.find('.body').val(html+' '+code);
  }else{
    var html = form.find('.comment_form').find('.body').val();
    form.find('.comment_form').find('.body').val(html+' '+code);
  }
  var aEmoji = scriptJquery(this).closest('.emoji_content').first().parent().find('a.emoji_comment_select').trigger('click');
  complitionRequestTrigger();
});

//GIF Work
scriptJquery(document).on('click','a.gif_comment_select',function() {
  scriptJquery("#sticker_close").hide();
  scriptJquery(".emoji_comment_select").removeClass("active");
  scriptJquery('.feeling_emoji_comment_select').removeClass('active');
  scriptJquery('.ses_feeling_emoji_container').hide();
  scriptJquery("#emoji_close").show();
  clickGifContentContainer = this;
  scriptJquery('.gif_content').removeClass('from_bottom');
  var topPositionOfParentDiv =  scriptJquery(this).offset().top + 35;
  topPositionOfParentDiv = topPositionOfParentDiv;
  if(scriptJquery(this).hasClass('activity_gif_content_a') && typeof sesadvancedactivityDesign != 'undefined' && sesadvancedactivityDesign == 2){
    var leftSub = 55;  
  }else
    var leftSub = 264;
  
    var leftPositionOfParentDiv =  scriptJquery(this).offset().left - leftSub;
    if(scriptJquery(this).hasClass('activity_gif_content_a')){
      var left = (scriptJquery(this).width()+leftSub)/3;    
      scriptJquery('._gif_content').find(".ses_emoji_container_arrow").css('left',left);
    } else {
      scriptJquery('._gif_content').find(".ses_emoji_container_arrow").css('left','');
    }
    leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
    if(scriptJquery('#ses_media_lightbox_container').length || scriptJquery('#ses_media_lightbox_container_video').length)
      topPositionOfParentDiv = topPositionOfParentDiv + offsetY;
    scriptJquery('._gif_content').css('top',topPositionOfParentDiv+'px');
    scriptJquery('._gif_content').css('left',leftPositionOfParentDiv).css('z-index',100);
    scriptJquery('._gif_content').show();

    var eTop = scriptJquery(this).offset().top; //get the offset top of the element
    var availableSpace = scriptJquery(document).height() - eTop;
    if(availableSpace < 400 && !scriptJquery('#ses_media_lightbox_container').length){
      scriptJquery('.gif_content').addClass('from_bottom');
    }

    if(scriptJquery(this).hasClass('active')){
      scriptJquery(this).removeClass('active');
      scriptJquery('.gif_content').hide();
      complitionRequestTrigger();
      return;
    }
    
    scriptJquery(this).addClass('active');
    scriptJquery('.gif_content').show();
    complitionRequestTrigger();

    if(!scriptJquery('.ses_gif_holder').find('.empty_cnt').length)
      return;

    var that = this;
    var url = en4.core.baseUrl+'sesfeedgif/index/gif/',
    requestComentGif = scriptJquery.ajax({
      url : url,
      data : {
        format : 'html',
      },
      evalScripts : true,
      success : function(responseHTML) {
        scriptJquery("#sticker_close").hide();
        scriptJquery(".emoji_comment_select").removeClass("active");
        scriptJquery('.feeling_emoji_comment_select').removeClass('active');
        scriptJquery('.ses_feeling_emoji_container').hide();

        scriptJquery('.gif_content').find('.ses_gif_container_inner').find('.ses_gif_holder').html(responseHTML);
        scriptJquery(that).addClass('complete');
        scriptJquery('._gif_content').show();
        complitionRequestTrigger();
        scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
          theme:"minimal-dark"
        });
      }
    });
});

var clickGifContentContainer;
function activityGifFeedAttachment(that){
  var code = scriptJquery(that).parent().parent().attr('rel');
  var image = scriptJquery(that).attr('src');
  Object.entries(composeInstance.plugins).forEach(function([key,plugin]) {
    plugin.deactivate();
    scriptJquery('#compose-'+plugin.getName()+'-activator').parent().removeClass('active');
  });
  scriptJquery('#fancyalbumuploadfileids').val('');
  scriptJquery('.fileupload-cnt').html('');
  composeInstance.getTray().empty();
  scriptJquery('#compose-tray').show();
  scriptJquery('#compose-tray').html('<div class="sesact_composer_gif"><img src="'+image+'"><a class="remove_gif_image_feed notclose fas fa-times" href="javascript:;"></a></div>');
  scriptJquery('#image_id').val(code);
  scriptJquery('.gif_content').hide();  
  scriptJquery('.gif_comment_select').removeClass('active');
  
  //Feed Background Image Work
  if(document.getElementById('feedbgid') && scriptJquery('#image_id').val()) {
    document.getElementById('hideshowfeedbgcont').style.display = 'none';
    scriptJquery('#feedbgid_isphoto').val(0);
    scriptJquery('.sesact_post_box').css('background-image', 'none');
    scriptJquery('#activity-form').removeClass('feed_background_image');
    scriptJquery('#feedbg_content').css('display','none');
  }
}
scriptJquery(document).on('click','._sesadvgif_gif > img',function(e){
  if(scriptJquery(clickGifContentContainer).hasClass('activity_gif_content_a')){
    activityGifFeedAttachment(this);  
  }else
    commentGifContainerSelect(this);
  scriptJquery('.exit_gif_btn').trigger('click');
});

function commentGifContainerSelect(that){
  var code = scriptJquery(that).parent().parent().attr('rel');
  var elem = scriptJquery(clickGifContentContainer).parent();
  var elemInput = elem.parent().find('span').eq(0).find('.select_gif_id').val(code);
  elem.closest('form').trigger('submit');
}

/*ACTIVITY FEED*/
scriptJquery(document).on('click','.remove_gif_image_feed',function(){
  composeInstance.getTray().empty();
  scriptJquery('#image_id').val('');
  scriptJquery('#compose-tray').hide();
  
  //Feed Background Image Work
  if(document.getElementById('feedbgid') && scriptJquery('#image_id').val() == '') {
    var feedbgid = scriptJquery('#feedbgid').val();
    document.getElementById('hideshowfeedbgcont').style.display = 'block';
    scriptJquery('#feedbg_content').css('display','block');
    var feedagainsrcurl = scriptJquery('#feed_bg_image_'+feedbgid).attr('src');
    scriptJquery('.sesact_post_box').css("background-image","url("+ feedagainsrcurl +")");
    scriptJquery('#feedbgid_isphoto').val(1);
    scriptJquery('#feedbg_main_continer').css('display','block');
    if(feedbgid) {
      scriptJquery('#activity-form').addClass('feed_background_image');
    }
  }
});
var gifsearchAdvReq;

var canPaginatePageNumber = 1;
scriptJquery(document).on('keyup change','.search_sesgif',function(){
  var value = scriptJquery(this).val();
  if(!value){
    scriptJquery('.main_search_category_srn').show();
    scriptJquery('.main_search_cnt_srn').hide();
    return;
  }
  scriptJquery('.main_search_category_srn').hide();
  scriptJquery('.main_search_cnt_srn').show();
  if(typeof gifsearchAdvReq != 'undefined') {
    
    isGifRequestSend = false;
  }
  document.getElementById('main_search_cnt_srn').innerHTML = '<div class="sesgifsearch sesbasic_loading_container" style="height:100%;"></div>';
  canPaginatePageNumber = 1;
  searchGifContent();
});

var isGifRequestSend = false;
function searchGifContent(valuepaginate) {
  
  var value = '';
  var search_sesgif = scriptJquery('.search_sesgif').val();
  
  if(isGifRequestSend == true)
    return;
  
  if(typeof valuepaginate != 'undefined') {
    value = 1;
    document.getElementById('main_search_cnt_srn').innerHTML = document.getElementById('main_search_cnt_srn').innerHTML + '<div class="sesgifsearchpaginate sesbasic_loading_container" style="height:100%;"></div>';
  }
  
  isGifRequestSend = true;
  gifsearchAdvReq = (scriptJquery.ajax({
    method: 'post',
    'url': en4.core.baseUrl + "sesfeedgif/index/search-gif/",
    'data': {
      format: 'html',
        text: search_sesgif,
        page: canPaginatePageNumber,
        is_ajax: 1,
        searchvalue: value,
    },
    success: function( responseHTML) {
      
      scriptJquery('.sesgifsearch').remove();
      scriptJquery('.sesgifsearchpaginate').remove();
      
      if(scriptJquery('.sesfeedgif_search_results').length == 0)
      scriptJquery('#main_search_cnt_srn').append(responseHTML);
      else 
        scriptJquery('.sesfeedgif_search_results').append(responseHTML);
      scriptJquery('.main_search_cnt_srn').slimscroll({
        height: 'auto',
        alwaysVisible :true,
        color :'#000',
        railOpacity :'0.5',
        disableFadeOut :true,
      });
            
      scriptJquery('.main_search_cnt_srn').slimscroll().bind('slimscroll', function(event, pos) {
        if(canPaginateExistingPhotos == '1' && pos == 'bottom' && scriptJquery('.sesgifsearchpaginate').length == 0) {
          scriptJquery('.sesbasic_loading_container').css('position','absolute').css('width','100%').css('bottom','5px');
          searchGifContent(1);
        }
      });
      isGifRequestSend = false;
    }
  }))
}
//GIF Work End


//Feeling Plugin: Emojis Work
scriptJquery(document).on('click','.feeling_emoji_comment_select',function(){
  scriptJquery("#sticker_close").hide();
  scriptJquery("#emoji_close").hide();
  scriptJquery(".gif_comment_select").removeClass("active");
  scriptJquery(".emoji_comment_select").removeClass("active");

  clickFeelingEmojiContentContainer = this;
  scriptJquery('.feeling_emoji_content').removeClass('from_bottom');
  var topPositionOfParentDiv =  scriptJquery(this).offset().top + 35;
  topPositionOfParentDiv = topPositionOfParentDiv;

  if(scriptJquery(this).hasClass('feeling_activity_emoji_content_a') && typeof sesadvancedactivityDesign != 'undefined' && sesadvancedactivityDesign == 2) {
    var leftSub = 55;  
  } else
    var leftSub = 264;
    
  var leftPositionOfParentDiv =  scriptJquery(this).offset().left - leftSub;
  leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
  
  if(scriptJquery('#ses_media_lightbox_container').length || scriptJquery('#ses_media_lightbox_container_video').length)
    topPositionOfParentDiv = topPositionOfParentDiv + offsetY;

  scriptJquery('._feeling_emoji_content').css('top',topPositionOfParentDiv+'px');
  scriptJquery('._feeling_emoji_content').css('left',leftPositionOfParentDiv).css('z-index',100);
  scriptJquery('._feeling_emoji_content').show();
  var eTop = scriptJquery(this).offset().top; //get the offset top of the element
  var availableSpace = scriptJquery(document).height() - eTop;
  
  if(availableSpace < 400 && !scriptJquery('#ses_media_lightbox_container').length){
      scriptJquery('.feeling_emoji_content').addClass('from_bottom');
  }
  
  if(scriptJquery(this).hasClass('active')) {
    scriptJquery(this).removeClass('active');
    scriptJquery('.feeling_emoji_content').hide();
    complitionRequestTrigger();
    return false;
  }
  scriptJquery(this).addClass('active');
  scriptJquery('.feeling_emoji_content').show();

  complitionRequestTrigger();
  
  if(!scriptJquery('.ses_feeling_emoji_holder').find('.empty_cnt').length)
    return;
  

  var that = this;
  var url = en4.core.baseUrl+'sesemoji/index/feelingemojicomment/',
  feeling_requestEmoji = scriptJquery.ajax({
    url : url,
    data : {
      format : 'html',
    },
    evalScripts : true,
    success : function( responseHTML) {
      scriptJquery("#sticker_close").hide();
      scriptJquery("#emoji_close").hide();
      scriptJquery(".gif_comment_select").removeClass("active");
      scriptJquery(".emoji_comment_select").removeClass("active");

      scriptJquery('.ses_feeling_emoji_holder').html(responseHTML);
      scriptJquery(that).addClass('complete');
      scriptJquery('.feeling_emoji_content').show();
      complitionRequestTrigger();
      scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
        theme:"minimal-dark"
      });
    }
  });
});
//Feeling Plugin: Emojis Work


//like member
scriptJquery(document).on('click','ul.like_main_cnt_reaction li > a',function(){
    var relAttr = scriptJquery(this).attr('data-rel');
    var typeData = scriptJquery(this).attr('data-type');
    scriptJquery('.like_main_cnt_reaction > li').removeClass('active');
    scriptJquery(this).parent().addClass('active');
    scriptJquery('.sesact_mlist_popup_cont > .container_like_contnent_main').hide();
    var elem = scriptJquery('#container_like_contnent_'+relAttr);
    elem.show();
    if(typeData == 'comment')
      var typeData = 'sesadvancedcomment';
    else
      var typeData = 'sesadvancedactivity';
    if(elem.find('ul').find('.nocontent').length){
      var url = en4.core.baseUrl+typeData+'/ajax/likes/';
      complitionRequestTrigger();
     var requestComentEmojiContent = scriptJquery.ajax({
      url : url,
      data : {
        format : 'html',
        id: elem.find('ul').find('.nocontent').attr('data-id'),
        resource_type: elem.find('ul').find('.nocontent').attr('data-resourcetype'),
        typeSelected: elem.find('ul').find('.nocontent').attr('data-typeselected'),
        item_id : elem.find('ul').find('.nocontent').attr('data-itemid'),
        page: 1,   
        type:relAttr, 
        is_ajax_content : 1,
      },
      evalScripts : true,
      success : function( responseHTML) {
        scriptJquery(elem.find('ul')).html(responseHTML);
        en4.core.runonce.trigger();
        complitionRequestTrigger();
      }
    });
        
    }
    
});
function complitionRequestTrigger(){
	if(typeof feedUpdateFunction == "function")
	 feedUpdateFunction();
  scriptJquery(window).trigger('resize');
  //page
  var elem = scriptJquery('.sespage_feed_change_option_a');
  for(i=0;i<elem.length;i++){
    var imageItem = scriptJquery(elem[i]).attr('data-src');
    scriptJquery(elem[i]).closest('.comment-feed').find('.comment_usr_img').find('img').attr('src',imageItem);  
  }
  //group
  var elem = scriptJquery('.sesgroup_feed_change_option_a');
  for(i=0;i<elem.length;i++){
    var imageItem = scriptJquery(elem[i]).attr('data-src');
    scriptJquery(elem[i]).closest('.comment-feed').find('.comment_usr_img').find('img').attr('src',imageItem);  
  }
  //business
  var elem = scriptJquery('.sesbusiness_feed_change_option_a');
  for(i=0;i<elem.length;i++){
    var imageItem = scriptJquery(elem[i]).attr('data-src');
    scriptJquery(elem[i]).closest('.comment-feed').find('.comment_usr_img').find('img').attr('src',imageItem);  
  }
    //store
    var elem = scriptJquery('.estore_feed_change_option_a');
    for(i=0;i<elem.length;i++){
        var imageItem = scriptJquery(elem[i]).attr('data-src');
        scriptJquery(elem[i]).closest('.comment-feed').find('.comment_usr_img').find('img').attr('src',imageItem);
    }

};
/*Emotion Sticker*/
scriptJquery(document).on('click','.sesadv_emotion_btn_clk',function(e){
  var index = scriptJquery(this).parent().index();
  //For enable search work
  if(enablesearch == 0) {
    index = index -1;
  }
  var emojiCnt = scriptJquery('.ses_emoji_holder');
  emojiCnt.find('.emoji_content').hide();
  emojiCnt.find('.emoji_content').eq(index).show();
  var isComplete = scriptJquery(this).hasClass('complete')
  if(isComplete)
  return;
  var id = scriptJquery(this).attr('data-galleryid');
  var that = this;
  var emoji = scriptJquery.ajax({
    type:'POST',
    url: 'sesadvancedcomment/ajax/emoji-content/gallery_id/'+id,
    cache:false,
    contentType: false,
    processData: false,
    success:function(responseHTML){
      scriptJquery(that).addClass('complete');
      emojiCnt.find('.emoji_content').eq(index).html(responseHTML);
      scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
          theme:"minimal-dark"
      });
    },
   error: function(data){
     //silence
    },
  });
});
var clickEmojiContentContainer;
function activityFeedAttachment(that){
  var code = scriptJquery(that).parent().parent().attr('rel');
  var image = scriptJquery(that).attr('src');
  Object.entries(composeInstance.plugins).forEach(function([key,plugin]) {
    plugin.deactivate();
    scriptJquery('#compose-'+plugin.getName()+'-activator').parent().removeClass('active');
  });
  scriptJquery('#fancyalbumuploadfileids').val('');
  scriptJquery('.fileupload-cnt').html('');
  composeInstance.getTray().empty();
  scriptJquery('#compose-tray').show();
  scriptJquery('#compose-tray').html('<div class="sesact_composer_sticker"><img src="'+image+'"><a class="remove_reaction_image_feed notclose fas fa-times" href="javascript:;"></a></div>');
  scriptJquery('#reaction_id').val(code);
  scriptJquery('.emoji_content').hide();  
  scriptJquery('.emoji_comment_select').removeClass('active');
  
  //Feed Background Image Work
  if(document.getElementById('feedbgid') && scriptJquery('#reaction_id').val()) {
    
    //scriptJquery('#sesact_post_tags_sesadv').css('display', 'block');
    document.getElementById('hideshowfeedbgcont').style.display = 'none';
    scriptJquery('#feedbgid_isphoto').val(0);
    //scriptJquery('#feedbgid').val(0);
    scriptJquery('.sesact_post_box').css('background-image', 'none');
    scriptJquery('#activity-form').removeClass('feed_background_image');
    scriptJquery('#feedbg_content').css('display','none');
  }
  
  
}
scriptJquery(document).on('click','._simemoji_reaction > img',function(e){
  if(scriptJquery(clickEmojiContentContainer).hasClass('activity_emoji_content_a')){
    activityFeedAttachment(this);  
  }else
    commentContainerSelect(this);
  scriptJquery('.exit_emoji_btn').trigger('click');
});
function commentContainerSelect(that){
  var code = scriptJquery(that).parent().parent().attr('rel');
  var elem = scriptJquery(clickEmojiContentContainer).parent();
  var elemInput = elem.parent().find('span').eq(0).find('.select_emoji_id') .val(code);
  elem.closest('form').trigger('submit');  
}
/*ACTIVITY FEED*/
scriptJquery(document).on('click','.remove_reaction_image_feed',function(){
  composeInstance.getTray().empty();
  scriptJquery('#reaction_id').val('');
  scriptJquery('#compose-tray').hide();
  
  //Feed Background Image Work
  if(document.getElementById('feedbgid') && scriptJquery('#reaction_id').val() == '') {
    var feedbgid = scriptJquery('#feedbgid').val();
    document.getElementById('hideshowfeedbgcont').style.display = 'block';
    scriptJquery('#feedbg_content').css('display','block');
    var feedagainsrcurl = scriptJquery('#feed_bg_image_'+feedbgid).attr('src');
    scriptJquery('.sesact_post_box').css("background-image","url("+ feedagainsrcurl +")");
    scriptJquery('#feedbgid_isphoto').val(1);
    scriptJquery('#feedbg_main_continer').css('display','block');
    if(feedbgid) {
      scriptJquery('#activity-form').addClass('feed_background_image');
    }
  }
});
var reactionsearchAdvReq;
scriptJquery(document).on('keyup change','.search_reaction_adv',function(){
   var value = scriptJquery(this).val();
   if(!value){
      scriptJquery('.main_search_category_srn').show();
      scriptJquery('.main_search_cnt_srn').hide();
      return;  
   }
    scriptJquery('.main_search_category_srn').hide();
    scriptJquery('.main_search_cnt_srn').show();

     reactionsearchAdvReq = (scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + "sesadvancedcomment/ajax/search-reaction/",
      'data': {
        format: 'html',
        text: value,
      },
      success: function( responseHTML) {
        scriptJquery('.main_search_cnt_srn').html(responseHTML);
        scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
          theme:"minimal-dark"
        });
      }
    }))
});
scriptJquery(document).on('click','.sesadv_reaction_cat',function(){
  var title = scriptJquery(this).data('title');
  scriptJquery('.search_reaction_adv').val(title);
  scriptJquery('.main_search_cnt_srn').html('')
  scriptJquery('.search_reaction_adv').trigger('change');
});
scriptJquery(document).on('click','.sesadv_reaction_remove_emoji, .sesadv_reaction_add_emoji',function(e){
  var add = scriptJquery(this).data('add');
  var remove = scriptJquery(this).data('remove');
  var gallery = scriptJquery(this).data('gallery');
  var title = scriptJquery(this).data('title');
  var src = scriptJquery(this).data('src');
  var index = scriptJquery(this).closest('._emoji_cnt').index() + 2;
  scriptJquery(this).prop("disabled", true);
  if(scriptJquery(this).hasClass('sesadv_reaction_remove_emoji')){
    var action = 'remove';
    scriptJquery('.sesadv_reaction_remove_emoji_'+gallery).html(add);
    scriptJquery('.sesadv_reaction_remove_emoji_'+gallery).removeClass('sesadv_reaction_remove_emoji').removeClass('sesadv_reaction_remove_emoji+'+gallery).addClass('sesadv_reaction_add_emoji').addClass('sesadv_reaction_add_emoji_'+gallery);
  }else{
    var action = 'add';
    scriptJquery('.sesadv_reaction_add_emoji_'+gallery).html(remove);
    scriptJquery('.sesadv_reaction_add_emoji_'+gallery).addClass('sesadv_reaction_remove_emoji').addClass('sesadv_reaction_remove_emoji_'+gallery).removeClass('sesadv_reaction_add_emoji').removeClass('sesadv_reaction_add_emoji_'+gallery);  
  }
  var that = this;
  reactionsearchAdvReq = (scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + "sesadvancedcomment/ajax/action-reaction/",
      'data': {
        format: 'html',
        gallery_id : gallery,
        actionD: action,
      },
      success: function(responseHTML) {
          scriptJquery(that).prop("disabled", false);
         if(action == 'add'){
          var content = '<a data-galleryid="'+gallery+'" class="_headbtn sesadv_tooltip sesadv_emotion_btn_clk" title="'+title+'"><img src="'+src+'" alt="'+title+'"></a>';
          scriptJquery(".ses_emoji_tabs").data('owlCarousel').addItem(content);
          scriptJquery(".ses_emoji_holder").append("<div style='display:none;position:relative;height:100%;' class='emoji_content'><div class='sesbasic_loading_container _emoji_cnt' style='height:100%;'></div></div>");
          sesadvtooltip();
          scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
            theme:"minimal-dark"
          });
        }else{
           scriptJquery(".ses_emoji_tabs").data('owlCarousel').removeItem(index);
           scriptJquery(".ses_emoji_holder > .emoji_content").eq(index).remove();
        }
      }
    }))
});
scriptJquery(document).on('click','.sesact_reaction_preview_btn',function(){
  var gallery = scriptJquery(this).data('gallery');
  scriptJquery('#sesact_reaction_gallery_cnt').hide();
  scriptJquery('.sesact_reaction_gallery_preview_cnt').show();
  if(scriptJquery('#sesact_reaction_preview_cnt_'+gallery).length){
     scriptJquery('#sesact_reaction_preview_cnt_'+gallery).show();
     return;
  }
  scriptJquery('.sesact_reaction_gallery_preview_cnt').append('<div class="sesbasic_loading_container _emoji_cnt sesact_reaction_gallery_preview_cnt_" id="sesact_reaction_preview_cnt_'+gallery+'" style="height:100%;"></div>');
  var reactionpreviewReq = (scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + "sesadvancedcomment/ajax/preview-reaction",
      'data': {
        format: 'html',
        gallery_id : gallery,
      },
      success: function(responseHTML) {
         scriptJquery('#sesact_reaction_preview_cnt_'+gallery).html(responseHTML);
				 scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
          	theme:"minimal-dark"
   			 });
      }
    }));
});
scriptJquery(document).on('click','.sesact_back_store',function(){
  scriptJquery('#sesact_reaction_gallery_cnt').show();
  scriptJquery('.sesact_reaction_gallery_preview_cnt').hide();
  scriptJquery('.sesact_reaction_gallery_preview_cnt > .sesact_reaction_gallery_preview_cnt_').hide();
});
scriptJquery(document).on('click','.sesadvcnt_reset_emoji',function(){
  scriptJquery('.search_reaction_adv').val('').trigger('change');  
});

function carouselSesadvReaction(){
  sesowlJqueryObject(".ses_emoji_tabs").owlCarousel({
        items : 6,
        itemsDesktop : [1199, 6],
        itemsDesktopSmall : [979, 6],
        itemsTablet : [768, 6],
        itemsMobile : [479, 6],
    nav : true,
    dots : false,
    loop: false,
    afterAction: function(){
      if ( this.itemsAmount > this.visibleItems.length ) {
        scriptJquery('.owl-next').show();
        scriptJquery('.owl-prev').show();
        scriptJquery('.owl-next').show('');
        scriptJquery('.owl-prev').show('');
        if ( this.currentItem == 0 ) {
          scriptJquery('.owl-prev').hide();
        }
        if ( this.currentItem == this.maximumItem ) {
          scriptJquery('.owl-next').hide('');
        }
      } else {
        scriptJquery('.owl-next').hide();
        scriptJquery('.owl-prev').hide();
      }
    },
  });  
}
/*FILTERING OPTIONS*/
scriptJquery(document).on('click','.search_adv_comment_a',function(e){
  if(scriptJquery(this).hasClass('active'))
    return;
  scriptJquery(this).closest('.search_adv_comment').find('li a').removeClass('active');
  scriptJquery(this).closest('.sesadvcmt_pulldown_wrapper').find('.search_advcomment_txt').find('span').text(scriptJquery(this).text());
  scriptJquery(this).addClass('active');
  var action_id =   scriptJquery(this).closest('.sesadvcmt_pulldown_wrapper').data('actionid');
  var ulObj = scriptJquery(this).closest('.comments_cnt_ul');
  var type = scriptJquery(this).data('type');
  if(ulObj.find('.sesadvcmt_comments_stats').length){
    ulObj.children().not(':first').remove();
    ulObj.append('<li style="position:relative" class="sesbasic_loading_container_li"><div class="sesbasic_loading_container" style="display:block;"></div></li>');
  }else{
    ulObj.html('<li style="position:relative"  class="sesbasic_loading_container_li"><div class="sesbasic_loading_container" style="display:block;"></div></li>');
  }

  sesadvancedcommentsearchaction(action_id,1,this,type,ulObj,scriptJquery(this).data('subjectype'));
});


//view more comment
function sesadvancedcommentsearchaction(action_id,page,obj,type,ulObj,subjectType){
  var viewcomment = 0;
  if(typeof subjectType != 'undefined'){
    var url = en4.core.baseUrl + 'sesadvancedcomment/comment/list';
  } else {
    var url = en4.core.baseUrl + 'sesadvancedcomment/index/viewcomment';
    viewcomment = 1;
  }
  scriptJquery.ajax({
      'url' : url,
      'data' : {
        'format' : 'html',
        'page' : page,
        'action_id':action_id,
        'id':action_id,
        'type': subjectType,
        'searchtype':type,
      },
      success : function( responseHTML) {
        if( responseHTML ) {
          try{
            var dataJson = scriptJquery.parseJSON(responseHTML);
            dataJson = dataJson.body;
          }catch(err){
             var dataJson = responseHTML;
          }
          ulObj.find('.sesbasic_loading_container_li').remove();
          //dataJson = scriptJquery(dataJson);
          //dataJson = dataJson.find(".comments_cnt_ul").html();
          ulObj.append(dataJson);
          en4.core.runonce.trigger();
          complitionRequestTrigger();
          if(viewcomment){
            ulObj.find(".search_advcomment_txt span").html(`<b>Sort By:</b> ${scriptJquery(obj).html()} `);
          }
        }
      }
    })
  
}

function removePreview(commentde_id,comment_id, type) {
  (scriptJquery.ajax({
    method: 'post',
    'url': en4.core.baseUrl + 'sesadvancedcomment/index/removepreview',
    'data': {
      format: 'html',
      comment_id: commentde_id,
      type: type,
      
    },
    success: function( responseHTML) {
      //if(document.getElementById('remove_previewli_'+ comment_id))
        scriptJquery('#remove_previewli_'+ comment_id).remove();
      //if(document.getElementById('remove_preview_'+ comment_id))
        scriptJquery('#remove_preview_'+ comment_id).remove();
      //if(document.getElementById('commentpreview_'+ comment_id))
        scriptJquery('#commentpreview_'+ comment_id).remove();
    }
  }));
  return false;
}

function showhidecommentsreply(comment_id, action_id) {
  if(document.getElementById('comments_reply_'+comment_id+'_'+action_id).style.display == 'block') {
    
    if(document.getElementById('comments_reply_'+comment_id+'_'+action_id))
      document.getElementById('comments_reply_'+comment_id+'_'+action_id).style.display = 'none';
    
    if(document.getElementById('comments_reply_reply_'+comment_id+'_'+action_id))
      document.getElementById('comments_reply_reply_'+comment_id+'_'+action_id).style.display = 'none';
    
    if(document.getElementById('comments_reply_body_'+comment_id))
      document.getElementById('comments_reply_body_'+comment_id).style.display = 'none';
    
    if(document.getElementById('comments_body_'+comment_id))
      document.getElementById('comments_body_'+comment_id).style.display = 'none';
    
    if(scriptJquery('#hideshow_'+comment_id+'_'+action_id))
      scriptJquery('#hideshow_'+comment_id+'_'+action_id).removeClass('fa fa-minus').addClass('far fa-plus-square');
  } else {
    
    if(document.getElementById('comments_reply_'+comment_id+'_'+action_id))
      document.getElementById('comments_reply_'+comment_id+'_'+action_id).style.display = 'block';
    
    if(document.getElementById('comments_reply_reply_'+comment_id+'_'+action_id))
      document.getElementById('comments_reply_reply_'+comment_id+'_'+action_id).style.display = 'block';
    
    if(document.getElementById('comments_reply_body_'+comment_id))
      document.getElementById('comments_reply_body_'+comment_id).style.display = 'block';
    
    if(document.getElementById('comments_body_'+comment_id))
      document.getElementById('comments_body_'+comment_id).style.display = 'block';
    
    if(scriptJquery('#hideshow_'+comment_id+'_'+action_id))
      scriptJquery('#hideshow_'+comment_id+'_'+action_id).removeClass('far fa-plus-square').addClass('fa fa-minus');
  }
}

scriptJquery(document).on('click','.sesadv_upvote_btn',function(){
  if(scriptJquery(this).hasClass('_disabled'))
    return;
  if(scriptJquery(this).closest('.advcomnt_feed_votebtn').hasClass('active'))
    return;
  scriptJquery(this).closest('.advcomnt_feed_votebtn').addClass('active');
  var itemguid  = scriptJquery(this).data('itemguid');
  var that = this;
  //var userguid  = scriptJquery(this).data('userguid');
  var guid = "";
   var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sespage_switcher_cnt').find('a').first();
   if(!guidItem.length)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesgroup_switcher_cnt').find('a').first();
   if(!guidItem.length)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesbusiness_switcher_cnt').find('a').first();
    if(!guidItem.length)
        var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .estore_switcher_cnt').find('a').first();
   if(guidItem)
    guid = guidItem.data('rel');
  var url  = en4.core.baseUrl + 'sesadvancedcomment/index/voteup';
  scriptJquery.ajax({
      'url' : url,
      'data' : {
        'format' : 'html',
        'itemguid' : itemguid,
        'userguid':guid,
        'type':'upvote',
      },
      success : function( responseHTML) {
        if( responseHTML ) {
          scriptJquery(that).closest('.advcomnt_feed_votebtn').replaceWith(responseHTML);
        }
        scriptJquery(that).closest('.advcomnt_feed_votebtn').removeClass('active');
      }
    })  
});
scriptJquery(document).on('click','.sesadv_downvote_btn',function(){
  if(scriptJquery(this).hasClass('_disabled'))
    return;
  if(scriptJquery(this).closest('.advcomnt_feed_votebtn').hasClass('active'))
    return;
  scriptJquery(this).closest('.advcomnt_feed_votebtn').addClass('active');
  var itemguid  = scriptJquery(this).data('itemguid');
  var that = this;
  //var userguid  = scriptJquery(this).data('userguid');
  var guid = "";
   var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sespage_switcher_cnt').find('a').first();
   if(!guidItem.length)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesgroup_switcher_cnt').find('a').first();
   if(!guidItem.length)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesbusiness_switcher_cnt').find('a').first();
    if(!guidItem.length)
        var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .estore_switcher_cnt').find('a').first();
   if(guidItem)
    guid = guidItem.data('rel');
  var url  = en4.core.baseUrl + 'sesadvancedcomment/index/voteup';
  scriptJquery.ajax({
      'url' : url,
      'data' : {
        'format' : 'html',
        'itemguid' : itemguid,
        'userguid':guid,
        'type':'downvote',
      },
      success : function( responseHTML) {
        if( responseHTML ) {
          scriptJquery(that).closest('.advcomnt_feed_votebtn').replaceWith(responseHTML);
        }
        scriptJquery(that).closest('.advcomnt_feed_votebtn').removeClass('active');
      }
    })  
})
//like comment
scriptJquery(document).on('click','.sesadvancedcommentcommentlike',function(){
  var obj = scriptJquery(this);
	previousSesadvcommLikeObj = obj.closest('.sesadvcmt_hoverbox_wrapper');
  var action_id = scriptJquery(this).attr('data-actionid');
  //var guid = scriptJquery(this).attr('data-guid');
  var guid = "";
   var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sespage_switcher_cnt').find('a').first();
   if(!guidItem)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesgroup_switcher_cnt').find('a').first();
   if(!guidItem.length)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesbusiness_switcher_cnt').find('a').first();
    if(!guidItem.length)
        var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .estore_switcher_cnt').find('a').first();
   if(guidItem.length)
    guid = guidItem.data('rel');
  var comment_id = scriptJquery(this).attr('data-commentid');
  var type = scriptJquery(this).attr('data-type');
  var datatext = scriptJquery(this).attr('data-text');
  var subject_id = scriptJquery(this).attr('data-subjectid');
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
    scriptJquery(elem).removeClass('sesadvancedcommentcommentunlike').removeClass('_reaction').addClass('sesadvancedcommentcommentlike');
    scriptJquery(elem).parent().addClass('feed_item_option_like').removeClass('feed_item_option_unlike');
  }else{
  //like  
    scriptJquery(elem).find('i').css('background-image', 'url(' + image + ')');
    scriptJquery(elem).find('span').html(datatext);
    scriptJquery(elem).removeClass('sesadvancedcommentcommentlike').addClass('_reaction').addClass('sesadvancedcommentcommentunlike');
    scriptJquery(elem).parent().addClass('feed_item_option_unlike').removeClass('feed_item_option_like');
  }

// 	var parentObject = previousSesadvcommLikeObj.parent().html();
// 	var parentElem = previousSesadvcommLikeObj.parent();
// 	previousSesadvcommLikeObj.parent().html('');
// 	parentElem.html(parentObject);
	  var ajax = scriptJquery.ajax({
    url : en4.core.baseUrl + 'sesadvancedcomment/index/like',
    data : {
      format : 'json',
      action_id : action_id,
      comment_id : comment_id,
      subject : en4.core.subject.guid,
      guid : guid ,
       sbjecttype:scriptJquery(this).attr('data-sbjecttype'),
      subjectid:scriptJquery(this).attr('data-subjectid'),
      type:type
    },
    'success' : function(responseHTML) {
      if( responseHTML ) {
        scriptJquery(obj).closest(".comments_info").find(".comments_likes_total").eq(0).remove();
        scriptJquery(obj).closest('.comments_date').replaceWith(responseHTML.body);
        en4.core.runonce.trigger();
        complitionRequestTrigger();
      }
    }
  });    
});
//like feed action content
scriptJquery(document).on('click','.sesadvancedcommentcommentunlike',function(){
  var obj = scriptJquery(this);
  var action_id = scriptJquery(this).attr('data-actionid');
  var comment_id = scriptJquery(this).attr('data-commentid');
  var type = scriptJquery(this).attr('data-type');
   var datatext = scriptJquery(this).attr('data-text');
  var likeWorkText = scriptJquery(this).attr('data-like');
  var unlikeWordText = scriptJquery(this).attr('data-unlike');
  
  var guid = "";
   var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sespage_switcher_cnt').find('a').first();
   if(!guidItem.length)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesgroup_switcher_cnt').find('a').first();
   if(!guidItem.length)
    var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .sesbusiness_switcher_cnt').find('a').first();
    if(!guidItem.length)
        var guidItem = scriptJquery(this).closest('.comment-feed').find('.feed_item_date > ul > .estore_switcher_cnt').find('a').first();
   if(guidItem)
    guid = guidItem.data('rel');
  //check for unlike
  scriptJquery(this).find('i').removeAttr('style');
  scriptJquery(this).find('span').html(likeWorkText);
  scriptJquery(this).removeClass('sesadvancedcommentcommentunlike').removeClass('_reaction').addClass('sesadvancedcommentcommentlike');
  scriptJquery(this).parent().addClass('feed_item_option_like').removeClass('feed_item_option_unlike');
  var ajax = scriptJquery.ajax({
    url : en4.core.baseUrl + 'sesadvancedcomment/index/unlike',
    data : {
      format : 'json',
      action_id : action_id,
      comment_id : comment_id,
      subject : en4.core.subject.guid,
      guid:guid,
       sbjecttype:scriptJquery(this).attr('data-sbjecttype'),
      subjectid:scriptJquery(this).attr('data-subjectid'),
      type:type
    },
    'success' : function(responseHTML) {
      if( responseHTML ) {
        scriptJquery(obj).closest('.comments_date').replaceWith(responseHTML.body);
        en4.core.runonce.trigger();
        complitionRequestTrigger();
      }
    }
  });
});
function setCommentFocus(comment_id)
{
  document.getElementById("comment"+comment_id).focus(); 
}

scriptJquery(document).on("click",".body",function(){
  if(!scriptJquery(this).is(":focus")){
    scriptJquery(this).focus();
  }
});
