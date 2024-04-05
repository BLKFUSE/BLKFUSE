/* $Id:editComposer.js  2017-01-12 00:00:00 SocialEngineSolutions $*/

scriptJquery(document).on('click','#sesadvancedactivity_location_edit, .seloc_clk_edit',function(e){
  that = scriptJquery(this);
  if(scriptJquery(this).hasClass('.seloc_clk_edit'))
     that = scriptJquery('#sesadvancedactivity_location_edit');
   if(scriptJquery(this).hasClass('active')){
     scriptJquery(this).removeClass('active');
     scriptJquery('.sesact_post_location_container_edit').hide();
     return;
   }
   scriptJquery('.sesact_post_location_container_edit').show();
   scriptJquery(this).addClass('active');
});
scriptJquery(document).on('click','#sesadvancedactivity_tag_edit, .sestag_clk_edit',function(e){
  that = scriptJquery(this);
  if(scriptJquery(this).hasClass('.sestag_clk_edit'))
     that = scriptJquery('#sesadvancedactivity_tag_edit');
   if(scriptJquery(that).hasClass('active')){
     scriptJquery(that).removeClass('active');
     scriptJquery('.sesact_post_tag_cnt_edit').hide();
     return;
   }
   scriptJquery('.sesact_post_tag_cnt_edit').show();
   scriptJquery(that).addClass('active');
});


//Feelings Work
scriptJquery(document).on('click','#sesadvancedactivity_feelings_editspan',function(e){
  that = scriptJquery(this);
  if(scriptJquery(this).hasClass('.seloc_clk_edit'))
     that = scriptJquery('#sesadvancedactivity_feelings_editspan');
   if(scriptJquery(this).hasClass('active')){
     scriptJquery(this).removeClass('active');
     scriptJquery('.sesact_post_feelingcontent_containeredit').hide();
     scriptJquery('.sesact_post_feeling_container_edit').hide();
     return;
   }
  scriptJquery(this).addClass('active');
  scriptJquery('.sesact_post_feeling_container_edit').show();
  if(scriptJquery('#feelingactivityidedit').val() == '')
    scriptJquery('.sesact_post_feelingcontent_containeredit').show();
});

scriptJquery(document).on('click', '#feeling_activityedit', function(e){

  if(scriptJquery('#feelingactivityidedit').val() == '')
    scriptJquery('.sesact_post_feelingcontent_containeredit').show();
});

scriptJquery(document).on('keyup', '#feeling_activityedit', function(e){
  if (e.which == 8) {
    scriptJquery('#feelingactivityiconidedit').val() = '';
    scriptJquery('#feeling_elem_actedit').html('');
    scriptJquery('#feeling_activityedit').attr("placeholder", "How are you feeling?");
  }
});

function showFeelingContanieredit() {

  if(scriptJquery('#sesact_post_feeling_container_edit').css("display") == '' || scriptJquery('#sesact_post_feeling_container_edit').css("display") == 'table') {
    scriptJquery('#showFeelingContanieredit').removeClass('active');
    scriptJquery('#sesact_post_feeling_container_edit').hide();
  } else {
    scriptJquery('#showFeelingContanieredit').addClass('active');
    scriptJquery('#feeling_activity_remove_actedit').show();
    scriptJquery('#sesact_post_feeling_container_edit').show();
  }
}

function feelingactivityremoveactedit() {
  scriptJquery('#feeling_activity_remove_actedit').hide();
  scriptJquery('#feelingActTypeedit').html('');
  scriptJquery('#feelingActTypeedit').hide();
  scriptJquery('.sesfeelingactivity-ul').html('');
  if(scriptJquery('#feelingactivityidedit').val())
  scriptJquery('#feelingactivityidedit').val("");
  scriptJquery('#feeling_activityedit').val('');
  scriptJquery('#feelingactivityiconidedit').val("");
  scriptJquery('#feeling_elem_actedit').html('');
  
}


//Autosuggest feeling work
scriptJquery(document).on('click', '.sesact_feelingactivitytypeliedit', function(e) {

  scriptJquery('#feelingactivityiconidedit').val(scriptJquery(this).attr('data-rel'));
  scriptJquery('#feelingactivity_resource_typeedit').val(scriptJquery(this).attr('data-type'))
  
  if(!scriptJquery(this).attr('data-rel')) {
    scriptJquery('#feelingactivity_customedit').val(1);
    scriptJquery('#feelingactivity_customtextedit').val(scriptJquery('#feeling_activityedit').val());
  }
  
  if(scriptJquery(this).attr('data-icon')) {
    var finalFeeling = '-- ' + '<img class="sesfeeling_feeling_icon" title="'+scriptJquery(this).attr('data-title')+'" src="'+scriptJquery(this).attr('data-icon')+'"><span>' + ' ' +  scriptJquery('#feelingActTypeedit').html().toLowerCase() + ' ' + '<a href="javascript:;" id="showFeelingContanieredit" class="" onclick="showFeelingContanieredit()">'+scriptJquery(this).attr('data-title')+'</a>';
  } else {
    var finalFeeling = '-- ' + '<img class="sesfeeling_feeling_icon" title="'+scriptJquery(this).attr('data-title')+'" src="'+scriptJquery(this).find('a').find('img').attr('src')+'"><span>' + ' ' +  scriptJquery('#feelingActTypeedit').html().toLowerCase() + ' ' + '<a href="javascript:;" id="showFeelingContanieredit" class="" onclick="showFeelingContanieredit()">'+scriptJquery(this).attr('data-title')+'</a>';
  }
  
  scriptJquery('#feeling_activityedit').val(scriptJquery(this).attr('data-title'));
  scriptJquery('#feeling_elem_actedit').show();
  scriptJquery('#feeling_elem_actedit').html(finalFeeling);
  scriptJquery('#dash_elem_act_edit').hide();
  scriptJquery('#sesact_post_feeling_container_edit').hide();
});
//Autosuggest feeling work


  
scriptJquery(document).on('click', '.sesact_feelingactivitytypeedit', function(e) {
  
  var feelingsactivity = scriptJquery(this);
  var feelingIdEdit = scriptJquery(this).attr('data-rel');
  var feelingTypeEdit = scriptJquery(this).attr('data-type');
  var feelingTitleEdit = scriptJquery(this).attr('data-title');
  scriptJquery('#feelingActTypeedit').show();
  scriptJquery('#feelingActTypeedit').html(feelingTitleEdit);
  scriptJquery('#feeling_activityedit').attr("placeholder", "How are you feeling?");
  
  document.getElementById('feelingactivityidedit').value = feelingIdEdit;
  
  document.getElementById('feelingactivitytypeedit').value = feelingTypeEdit;
  
  scriptJquery('.sesact_post_feelingcontent_containeredit').hide();
  
  scriptJquery('#feeling_activityedit').trigger('change').trigger('keyup').trigger('keydown');
  
//   contentAutocompletefeelingedit.setOptions({
//     'postData': {
//       'feeling_id': document.getElementById('feelingactivityidedit').value,
//       'feeling_type': document.getElementById('feelingactivitytypeedit').value,
//     }
//   });
});

//Feeling Emojis Work

var feeling_requestEmojiA;
scriptJquery(document).on('click','#sesadvancedactivityfeeling_emoji-edit-a',function(){
  
  scriptJquery('.ses_emoji_container').removeClass('from_bottom');
  
  var topPositionOfParentDiv =  scriptJquery(this).offset().top + 35;
  topPositionOfParentDiv = topPositionOfParentDiv;
  var leftSub = 264;
  var leftPositionOfParentDiv =  scriptJquery(this).offset().left - leftSub;
  leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
  scriptJquery(this).parent().find('.ses_emoji_container').css('right',0);
  //scriptJquery(this).parent().find('.ses_emoji_container').css('top',topPositionOfParentDiv+'px');
  //scriptJquery(this).parent().find('.ses_emoji_container').css('left',leftPositionOfParentDiv).css('z-index',100);
  
  scriptJquery(this).parent().find('.ses_emoji_container').show();
  
  scriptJquery('#sesadvancedactivityfeeling_emoji_edit').show();

  if(scriptJquery(this).hasClass('active')) {
    scriptJquery(this).removeClass('active');
    scriptJquery('#sesadvancedactivityfeeling_emoji_edit').hide();
    return false;
  }
  
  scriptJquery(this).addClass('active');
  scriptJquery('#sesadvancedactivityfeeling_emoji_edit').show();
  
  if(scriptJquery(this).hasClass('complete'))
    return false;


  var that = this;
  
  var url = en4.core.baseUrl + 'sesemoji/index/feelingemoji/edit/true';
  
  feeling_requestEmojiA = scriptJquery.ajax({
    url : url,
    data : {
      format : 'html',
    },
    evalScripts : true,
    success : function( responseHTML) {
      
      scriptJquery('#sesadvancedactivityfeeling_emoji_edit').find('.ses_feeling_emoji_container_inner').find('.ses_feeling_emoji_holder').html(responseHTML);
      scriptJquery('#sesadvancedactivityfeeling_emoji_edit').show();
      scriptJquery(that).addClass('complete');
      initSesadvAnimation();
      sesadvtooltip();
      scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
          theme:"minimal-dark"
      });
    }
  });
});


scriptJquery(document).on('click','.select_feeling_emoji_advedit > img',function(e){
  
  var feeling_emoji_icon = scriptJquery(this).parent().parent().attr('data-icon');
  var html = scriptJquery('#edit_activity_body').val(); 
  if(html == '<br>')
    scriptJquery('#edit_activity_body').val('');
  scriptJquery('textarea#edit_activity_body').val(scriptJquery('textarea#edit_activity_body').val()+' '+feeling_emoji_icon);
  
  var data = scriptJquery('#edit_activity_body').val();
    EditFieldValue = data;

  scriptJquery('textarea#edit_activity_body').trigger('focus');
//  scriptJquery('#sesadvancedactivityfeeling_emoji-edit-a').trigger('click');
});

//Click on Emojis and scroll up and down contanier
scriptJquery(document).on('click','.emojis_clicka',function(e) {
  var emojiId = scriptJquery(this).attr('rel');
  scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar("scrollTo",scriptJquery('.sesbasic_custom_scroll').find('.mCSB_container').find('#sesbasic_custom_scrollul').find('#main_emiji_'+emojiId));          
});


//Feeling Work End

var requestEmojiA;
scriptJquery(document).on('click','#sesadvancedactivityemoji-edit-a',function(){
  
    scriptJquery(this).parent().find('.ses_emoji_container').removeClass('from_bottom');
    
    var parentElem = scriptJquery('#sessmoothbox_container');
    var parentLeft = parentElem.css('left').replace('px','');
    var parentTop = parentElem.css('top').replace('px','');

    var topPositionOfParentDiv =  scriptJquery(this).offset().top + 35;
    topPositionOfParentDiv = topPositionOfParentDiv;
    var leftSub = 264;
    var leftPositionOfParentDiv =  scriptJquery(this).offset().left - leftSub;
    leftPositionOfParentDiv = leftPositionOfParentDiv+'px';
    scriptJquery(this).parent().find('.ses_emoji_container').css('right',0);
    //scriptJquery(this).parent().find('.ses_emoji_container').css('top',topPositionOfParentDiv+'px');
    //scriptJquery(this).parent().find('.ses_emoji_container').css('left',leftPositionOfParentDiv).css('z-index',100);
    scriptJquery(this).parent().find('.ses_emoji_container').show();

    if(scriptJquery(this).hasClass('active')){
      scriptJquery(this).removeClass('active');
      scriptJquery('#sesadvancedactivityemoji_edit').hide();
      return false;
     }
      scriptJquery(this).addClass('active');
      scriptJquery('#sesadvancedactivityemoji_edit').show();
      if(scriptJquery(this).hasClass('complete'))
        return false;
      
       var that = this;
       var url = en4.core.baseUrl + 'sesadvancedactivity/ajax/emoji/edit/true';
       requestEmojiA = scriptJquery.ajax({
        url : url,
        data : {
          format : 'html',
        },
        evalScripts : true,
        success : function(responseHTML) {
          scriptJquery('#sesadvancedactivityemoji_edit').find('.ses_emoji_container_inner').find('.ses_emoji_holder').html(responseHTML);
          scriptJquery(that).addClass('complete');
          sesadvtooltip();
          initSesadvAnimation();
         scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
            theme:"minimal-dark"
         });
        }
      });
});

scriptJquery(document).on('click','.select_emoji_advedit > img',function(e){
  var code = scriptJquery(this).parent().parent().attr('rel');
  var html = scriptJquery('#edit_activity_body').val();
  if(html == '<br>')
    scriptJquery('#edit_activity_body').val('');
  scriptJquery('#edit_activity_body').val( scriptJquery('#edit_activity_body').val()+' '+code);
  var data = scriptJquery('#edit_activity_body').val();
  EditFieldValue = data;
  scriptJquery('#sesadvancedactivityemoji-edit-a').trigger('click');
});




scriptJquery(document).on('click','.adv_privacy_optn_edit li a',function(e){
  e.preventDefault();
  if(!scriptJquery(this).parent().hasClass('multiple')){
    scriptJquery('.adv_privacy_optn_edit > li').removeClass('active');
    var text = scriptJquery(this).text();
    scriptJquery('.sesact_privacy_btn_edit').attr('title',text);;
    scriptJquery(this).parent().addClass('active');
    scriptJquery('#adv_pri_option_edit').html(text);
    scriptJquery('#sesadv_privacy_icon').remove();
    scriptJquery('<i id="sesadv_privacy_icon" class="'+scriptJquery(this).find('i').attr('class')+'"></i>').insertBefore('#adv_pri_option_edit');
    
    if(scriptJquery(this).parent().hasClass('sesadv_network_edit'))
      scriptJquery('#privacy_edit').val(scriptJquery(this).parent().attr('data-src')+'_'+scriptJquery(this).parent().attr('data-rel'));
    else if(scriptJquery(this).parent().hasClass('sesadv_list_edit'))
      scriptJquery('#privacy_edit').val(scriptJquery(this).parent().attr('data-src')+'_'+scriptJquery(this).parent().attr('data-rel'));
   else
    scriptJquery('#privacy_edit').val(scriptJquery(this).parent().attr('data-src'));
  }
  scriptJquery('.sesact_privacy_btn_edit').parent().removeClass('sesact_pulldown_active');
});
scriptJquery(document).on('click','.mutiselectedit',function(e){
  if(scriptJquery(this).attr('data-rel') == 'network-multi')
    var elem = 'sesadv_network_edit';
  else
    var elem = 'sesadv_list_edit';
  var elemens = scriptJquery('.'+elem);
  var html = '';
  for(i=0;i<elemens.length;i++){
    html += '<li><input class="checkbox" type="checkbox" value="'+scriptJquery(elemens[i]).attr('data-rel')+'">'+scriptJquery(elemens[i]).text()+'</li>';
  }
  en4.core.showError('<form id="'+elem+'_select" class="_privacyselectpopup"><p>Please select network to display post</p><ul class="sesbasic_clearfix">'+html+'</ul><div class="_privacyselectpopup_btns sesbasic_clearfix"><button type="submit">Save</button><button class="close" onclick="Smoothbox.close();return false;">Close</button></div></form>');  
  scriptJquery ('._privacyselectpopup').parent().parent().addClass('_privacyselectpopup_wrapper');
  //pre populate
  var valueElem = scriptJquery('#privacy_edit').val();
  if(valueElem && valueElem.indexOf('network_list_') > -1 && elem == 'sesadv_network_edit'){
    var exploidV =  valueElem.split(',');
    for(i=0;i<exploidV.length;i++){
       var id = exploidV[i].replace('network_list_','');
       scriptJquery('.checkbox[value="'+id+'"]').prop('checked', true);
    }
   }else if(valueElem && valueElem.indexOf('member_list_') > -1 && elem == 'sesadv_list_edit'){
    var exploidV =  valueElem.split(',');
    for(i=0;i<exploidV.length;i++){
       var id = exploidV[i].replace('member_list_','');
       scriptJquery('.checkbox[value="'+id+'"]').prop('checked', true);
    }
   }
});
scriptJquery(document).on('submit','#sesadv_list_edit_select',function(e){
  e.preventDefault();
  var isChecked = false;
   var sesadv_list_select = scriptJquery('#sesadv_list_edit_select').find('[type="checkbox"]');
   var valueL = '';
   for(i=0;i<sesadv_list_select.length;i++){
    if(!isChecked)
      scriptJquery('.adv_privacy_optn_edit > li').removeClass('active');
    if(scriptJquery(sesadv_list_select[i]).is(':checked')){
      isChecked = true;
      var el = scriptJquery(sesadv_list_select[i]).val();
      scriptJquery('.lists[data-rel="'+el+'"]').addClass('active');
      valueL = valueL+'member_list_'+el+',';
    }
   }
   if(isChecked){
     scriptJquery('#privacy_edit').val(valueL);
     scriptJquery('#adv_pri_option_edit').html(en4.core.translate("Multiple Lists"));
     scriptJquery('.sesact_privacy_btn_edit').attr('title',en4.core.translate("Multiple Lists"));
    scriptJquery(this).find('.close').trigger('click');
   }
   scriptJquery('#sesadv_privacy_icon_edit').removeAttr('class').addClass('sesact_list');
});
scriptJquery(document).on('submit','#sesadv_network_edit_select',function(e){
  e.preventDefault();
  var isChecked = false;
   var sesadv_network_select = scriptJquery('#sesadv_network_edit_select').find('[type="checkbox"]');
   var valueL = '';
   for(i=0;i<sesadv_network_select.length;i++){
    if(!isChecked)
      scriptJquery('.adv_privacy_optn_edit > li').removeClass('active');
    if(scriptJquery(sesadv_network_select[i]).is(':checked')){
      isChecked = true;
      var el = scriptJquery(sesadv_network_select[i]).val();
      scriptJquery('.network[data-rel="'+el+'"]').addClass('active');
      valueL = valueL+'network_list_'+el+',';
    }
   }
   if(isChecked){
     scriptJquery('#privacy_edit').val(valueL);
     scriptJquery('#adv_pri_option_edit').html('Multiple Network');
     scriptJquery('.sesact_privacy_btn_edit').attr('title','Multiple Network');;
    scriptJquery(this).find('.close').trigger('click');
   }
   scriptJquery('#sesadv_privacy_icon_edit').removeAttr('class').addClass('sesact_network');
});
 
function tagLocationWorkEdit(){
    if(!scriptJquery('#tag_location_edit').val())
      return;
     scriptJquery('#locValuesEdit-element').html('<span class="tag">'+scriptJquery('#tag_location_edit').val()+' <a href="javascript:void(0);" class="loc_remove_act_edit">x</a></span>');
      scriptJquery('#dash_elem_act_edit').show();
      scriptJquery('#location_elem_act_edit').show();
      scriptJquery('#location_elem_act_edit').html('at <a href="javascript:;" class="seloc_clk_edit">'+scriptJquery('#tag_location_edit').val()+'</a>');
      scriptJquery('#tag_location_edit').hide();  
  }
  
    
  scriptJquery(document).on('click','.loc_remove_act_edit',function(e){
    scriptJquery('#activitylngEdit').val('');
    scriptJquery('#activitylatEdit').val('');
    scriptJquery('#tag_location_edit').val('');
    scriptJquery('#locValuesEdit-element').html('');
    scriptJquery('#tag_location_edit').show();
    scriptJquery('#location_elem_act_edit').hide();
    if(!scriptJquery('#toValuesEdit-element').children().length)
       scriptJquery('#dash_elem_act_edit').hide();
  })    
// Populate data
  var maxRecipientsEdit = 50;
  
 function getMentionDataEdit(that,dataBody){
    var data = scriptJquery('#edit_activity_body').val();
    var data_status = scriptJquery(that).attr('data-status');

    if(scriptJquery('#buysell-title-edit').length) {
      if(!scriptJquery('#buysell-title-edit').val())
        return false;
      else if(!scriptJquery('#buysell-price-edit').val())
        return false;
    } 
    //Feeling Work
    else if(!data && data_status == 1 && !scriptJquery('#tag_location_edit').val() && !scriptJquery('#feeling_activityedit').val())
      return false;
    
    data = scriptJquery(that).serialize()+'&bodyText='+dataBody;
    var url  = en4.core.baseUrl + 'sesadvancedactivity/index/edit-feed-post/userphotoalign/'+userphotoalign;
    scriptJquery(that).find('#compose-submit').attr('disabled',true);
    if(url.indexOf('&') <= 0)
      url = url+'?';
    url = url+'is_ajax=true';
    var that = that;
    scriptJquery(that).find('#compose-submit').html(savingtextActivityPost);
    //scriptJquery('#dots-animation-posting').show();
    //dotsAnimationWhenPostingInterval = setInterval (function() { dotsAnimationWhenPostingFn(sharingPostText)}, 600);
    sesadvancedactivityfeedactive2  = scriptJquery.ajax({
        url : url,
        data:data,
        method:"POST",
        success : function( responseHTML){
          try{
            var parseJson = scriptJquery.parseJSON(responseHTML);
            if(parseJson.status){
              scriptJquery('#activity-item-'+parseJson.last_id).replaceWith(parseJson.feed);
              
              scriptJquery('#activity-item-'+parseJson.last_id).fadeOut("slow", function(){
                 scriptJquery('#activity-item-'+parseJson.last_id).replaceWith(parseJson.feed);
                 scriptJquery('#activity-item-'+parseJson.last_id).fadeIn("slow");
                 sesadvtooltip();
                 initSesadvAnimation();
                 
              });
              
              sessmoothboxclose();           
            }else{
               en4.core.showError("<p>" + en4.core.language.translate("An error occured. Please try again after some time.") + '</p><button onclick="Smoothbox.close()">Close</button>');
            }
          }catch(e){
            
          }
          scriptJquery(that).find('#compose-submit').html(savingtextActivityPostOriginal);
          scriptJquery(that).find('#compose-submit').removeAttr('disabled');
          
        },
        onError: function(){
          en4.core.showError("<p>" + en4.core.language.translate("An error occured. Please try again after some time.") + '</p><button onclick="Smoothbox.close()">Close</button>');
        },
      });
  }
  //submit form
  scriptJquery(document).on('submit','.edit-activity-form',function(e){
    e.preventDefault(); 
    var that = this;
    scriptJquery('textarea#edit_activity_body').mentionsInput('val', function(data) {
       getMentionDataEdit(that,data);
    });
  });
  scriptJquery(document).on('click','.composer_targetpost_edit_toggle',function(e){
     openTargetPostPopupEdit(); 
  });
  scriptJquery(document).on('focus','#edit_activity_body',function(){ 
if(!scriptJquery(this).attr('id'))
  scriptJquery(this).attr('id',new Date().getTime());
  
  isonCommentBox = true;
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
scriptJquery(document).on('keyup','#edit_activity_body',function(){ 
    var data = scriptJquery(this).val();
     EditFieldValue = data;
});
