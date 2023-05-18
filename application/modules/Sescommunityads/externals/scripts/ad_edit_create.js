var promotePageContent = false;
scriptJquery(document).on('click','.sescommunityads_promote_a',function(){
  var rel = scriptJquery(this).attr('rel');
  if(rel == "promote_page_cnt"){
    promotePageContent = true;
    rel = "promote_content_cnt";
  }else
    promotePageContent = false;
  scriptJquery('#promote_cnt').val(rel);
 (scriptJquery('.sescommunity_create_cnt[rel=2]').show());
 scriptJquery('.sescommunity_create_cnt[rel=1]').hide();
});
function hideAllType(){
  scriptJquery('.hideall').hide();
}
function adType(){
  return scriptJquery('#promote_cnt').val();
}
scriptJquery(document).on('change','#communityAds_campaign',function(){
  if(scriptJquery(this).val() == 0){
    scriptJquery('.sescommunityads_select_content_title').show();
  }  else{
    scriptJquery('.sescommunityads_select_content_title').hide();
  }
});
scriptJquery(document).on('keyup','#campaign_name',function(e){
  var text = scriptJquery(this).val();
  if(text){
      scriptJquery('#campaign_name').css('border','');
  }else{
      scriptJquery('#campaign_name').css('border','1px solid red');
  }
});
var isRequestSendBoostPost = false;

scriptJquery(document).on('click','.sescommunityads_select_page',function(e){
  var id = scriptJquery(this).data('rel');
  var html = scriptJquery(this).html();
  scriptJquery(this).html('<i class="far fa-circle-notch fa-spin"></i>');
  isRequestSendBoostPost = true;
  var $this = this;
  //get boost post data
  var request = scriptJquery.ajax({
    dataType: 'html',
    url : en4.core.baseUrl+'sescommunityads/index/get-page-post-feed/id/'+id,
    data : {
      format : 'html',
    },
    evalScripts : true,
    success : function(responseHTML) {
      scriptJquery($this).html(html);
      scriptJquery('.sescommunityads_page_ad').removeClass('sescommunityads_page_ad');
      scriptJquery('button[data-rel='+id+']').addClass('sescommunityads_page_ad');
      scriptJquery('.sescommunityads_ad_preview').hide();
      scriptJquery('.sescmads_ad_preview_boost_post').show();
      scriptJquery('.sescmads_ad_preview_boost_post').html(responseHTML);
      scriptJquery('.ad_targetting').show();
      scriptJquery('.ad_scheduling').show();
      scriptJquery('.sescmads_create_main_left').show();
      scriptJquery('.sescommunityads_right_preview').show();
      scriptJquery('.sescmads_create_preview_image').hide();
      scriptJquery('.sescmads_create_preview_video').hide();
      scriptJquery('.sescmads_create_preview_carousel').hide();
      scriptJquery('.sescomm_footer_cnt').show();
    }
  });
  
})
scriptJquery(document).on('click','.boost_post_sescomm',function(e){
  scriptJquery('.sesboost_post_active').html(scriptJquery('.sesboost_post_active').attr('unselected-rel'));
  scriptJquery('.sesboost_post_active').removeClass('sesboost_post_active');
  scriptJquery(this).addClass('sesboost_post_active');
  scriptJquery(this).html(scriptJquery(this).attr('selected-rel'));
  return;
});
function sesCommBoostPost(elem){
  var id = scriptJquery('.sesboost_post_active').data('rel');
  if(!id){
      alert('Please Choose a Post to Boost it.');
      return;
  }
  var html = scriptJquery(elem).html();
  scriptJquery(elem).html('<i class="far fa-circle-notch fa-spin"></i>');
  isRequestSendBoostPost = true;
  var $this = elem;
  //get boost post data
  var request = scriptJquery.ajax({
    dataType: 'html',
    url : en4.core.baseUrl+'sescommunityads/index/get-boost-post-feed/id/'+id,
    data : {
      format : 'html',
    },
    evalScripts : true,
    success : function(responseHTML) {
      scriptJquery($this).html(html);
      scriptJquery('.sescommunityads_ad_preview').hide();
      scriptJquery('.sescmads_ad_preview_boost_post').show();
      scriptJquery('.sescmads_ad_preview_boost_post').html(responseHTML);
      scriptJquery('.ad_targetting').show();
      scriptJquery('.sescmads_create_main_left').show();
      scriptJquery('.ad_scheduling').show();
      scriptJquery('.boost_post_cnt').hide();
      scriptJquery('.sescommunityads_right_preview').show();
      scriptJquery('.sescomm_footer_cnt').show();
      scriptJquery('.sescmads_create_preview_image').hide();
      scriptJquery('.sescmads_create_preview_video').hide();
      scriptJquery('.sescmads_create_preview_carousel').hide();
    }
  });
  
}
//Edit and Back
function sescomm_back_btn(val){
 var visibleDiv = scriptJquery('.sescommunity_create_cnt:visible').attr('rel');
 var campaign = scriptJquery('#communityAds_campaign').val() == 0 && (!scriptJquery('#campaign_name').val() || scriptJquery('#campaign_name').val() == "");
 var category = true;
 if(scriptJquery('#category_id').length){
    if((!scriptJquery('#category_id').val() || scriptJquery('#category_id').val() == 0) && scriptJquery('#category_id').hasClass('mandatory')){
        category = false;
    }
 }

 if(visibleDiv == 2 && val == 3 && (campaign || !category))
 {
   if(campaign)
    scriptJquery('#campaign_name').css('border','1px solid red');
   if(!category)
    scriptJquery('#category_id').css('border','1px solid red');
    return;
 }else{
    scriptJquery('#campaign_name').css('border','');
    scriptJquery('#category_id').css('border','');
 }
 scriptJquery('.select_sescomm_content').hide();
 scriptJquery('.sescomm_promote_cnt').hide();
 scriptJquery('.sescomm_promote_website').hide();
  if(val == 3 && adType() == "boost_post_cnt"){
    scriptJquery('.sescmads_create_main_left').hide();
    scriptJquery('.ad_targetting').hide();
    scriptJquery('.ad_scheduling').hide();
    scriptJquery('.sescomm_footer_cnt').hide();
    scriptJquery('.sescommunityads_right_preview').hide();
    scriptJquery('.sescmads_create_preview_image').hide();
    scriptJquery('.sescmads_create_preview_video').hide();
    scriptJquery('.sescmads_create_preview_carousel').hide();
  }
  if(scriptJquery('#add_card').length){
    scriptJquery('.sescommunity_preview_sub_image').hide();
    if(sescommunityIsEditForm == "false"){
      scriptJquery('.sescommunity_preview_sub_image').find('a').find('img').attr('src',blankImage);
      scriptJquery('#add_card').prop('checked', false);
    }
    scriptJquery('.sescommunity_sponsored').hide();
  }
  contentTitleValue = "";
  if(val == 2 && scriptJquery('.sesboost_post_active').length){
    if(selectedBoostPostId == 0){
      scriptJquery('.sesboost_post_active').html(scriptJquery('.sesboost_post_active').attr('unselected-rel'));
      scriptJquery('.sesboost_post_active').removeClass('sesboost_post_active');
    }
    scriptJquery('.ad_targetting').hide();
    scriptJquery('.sescmads_create_main_left').hide();
    scriptJquery('.ad_scheduling').hide();
    scriptJquery('.sescomm_footer_cnt').hide();
    scriptJquery('.sescommunityads_ad_preview').show();
    scriptJquery('.sescmads_ad_preview_boost_post').hide();
    scriptJquery('.sescommunityads_right_preview').hide();
    scriptJquery('.sescmads_create_preview_image').hide();
    scriptJquery('.sescmads_create_preview_video').hide();
    scriptJquery('.sescmads_create_preview_carousel').hide();
    scriptJquery('.boost_post_cnt').show();
  }else if(val == 2 && scriptJquery('.sescommunityads_page_ad').length){
    scriptJquery('.sescommunityads_page_ad').removeClass('sescommunityads_page_ad');
    scriptJquery('.ad_targetting').hide();
    scriptJquery('.sescmads_create_main_left').hide();
    scriptJquery('.ad_scheduling').hide();
    scriptJquery('.sescomm_footer_cnt').hide();
    scriptJquery('.sescommunityads_ad_preview').show();
    scriptJquery('.sescmads_ad_preview_boost_post').hide();
    scriptJquery('.sescommunityads_right_preview').hide();
    scriptJquery('.sescmads_create_preview_image').hide();
    scriptJquery('.sescmads_create_preview_video').hide();
    scriptJquery('.sescmads_create_preview_carousel').hide();
  }else{
    for(i=1;i<6;i++)
      scriptJquery('.sescommunity_create_cnt[rel='+i+']').hide();
    scriptJquery('.sescommunity_create_cnt[rel='+val+']').show();
    hideAllType();
    scriptJquery('._preview_url').hide();
    if(adType() == "promote_website_cnt"){
      scriptJquery('._preview_url').show();
      scriptJquery('.promote_content_cnt').show();
      scriptJquery('.sescomm_promote_cnt').show();
      scriptJquery('.sescommunity_preview_sub_image').show();
      scriptJquery('.sescommunity_sponsored').show();
      scriptJquery('.sescmads_create_main_left').show();
      scriptJquery('.sescmads_create_main_right').show();
      scriptJquery('.sescomm_footer_cnt').show();
       var value = scriptJquery('input[name=formate_type]:checked').attr('rel');
        if(value == "carousel_div"){
         scriptJquery('.sescmads_create_preview_carousel').show();
        }else if(value == "image_div"){
          scriptJquery('.sescmads_create_preview_image').show();
        }else if(value == "banner_div"){
          scriptJquery('.sescmads_create_preview_image').show();
        }
        else{
          scriptJquery('.sescmads_create_preview_video').show();
        }
    }
    if(adType() == "promote_content_cnt"){
      scriptJquery('.sescommunity_preview_sub_image').show();
      scriptJquery('.sescommunity_sponsored').show();
      scriptJquery('.sescmads_create_main_left').show();
      scriptJquery('.sescomm_promote_cnt').show();
      scriptJquery('.sescmads_create_main_right').show();
      scriptJquery('.sescomm_footer_cnt').show();
       var value = scriptJquery('input[name=formate_type]:checked').attr('rel');

        if(value == "carousel_div"){
         scriptJquery('.sescmads_create_preview_carousel').show();
        }else if(value == "image_div"){
          scriptJquery('.sescmads_create_preview_image').show();
        }else if(value == "banner_div"){
          scriptJquery('.sescmads_create_preview_image').show();
        }
        else{
          scriptJquery('.sescmads_create_preview_video').show();
        }
    }
    if(val == 3){
      if(adType() == "promote_content_cnt"){
        scriptJquery('.select_sescomm_content').show();
      }
      var valueElem = scriptJquery('#sescomm_resource_type').find('option[value=sespage_page]');
      scriptJquery('.'+scriptJquery('#promote_cnt').val()).show();
      if(pageContentAdded == true){
        if(valueElem.length)
          valueElem.remove();
        pageContentAdded = false;
      }
      var buttonLink = scriptJquery('.'+scriptJquery('#promote_cnt').val()).find('.tablinks').eq(0);
      if(buttonLink.length)
      buttonLink.trigger('click');

      if(promotePageContent == true){
        scriptJquery('#sescomm_resource_type').closest('.sescmads_create_campaign_field').hide();
        if(!valueElem.length){
            scriptJquery('#sescomm_resource_type').append('<option value="sespage_page">Page</option>');
            scriptJquery('#sescomm_resource_type').val('sespage_page');
            pageContentAdded = true;
        }
        scriptJquery('#sescomm_resource_type').val('sespage_page');
        scriptJquery('#sescomm_resource_type').trigger('change');
      }else{
        scriptJquery('#sescomm_resource_type').closest('.sescmads_create_campaign_field').show();
      }
      var relval = scriptJquery('input[name=formate_type]:checked').attr('rel');

      scriptJquery('.'+relval).show();

      if(relval == 'banner_div') {
        scriptJquery('.promote_website_cnt').hide();
        scriptJquery('.preview_header').hide();
        scriptJquery('._cont').hide();
        //Edit Banner Ads
        scriptJquery('.sescmads_create_preview_image').addClass('sescmads_create_preview_banner');
        var selectWidth = scriptJquery('select[name="banner_id"]').find(":selected").attr('data-width');
        var selectHeight = scriptJquery('select[name="banner_id"]').find(":selected").attr('data-height');
        scriptJquery('.sescomm_img').css("width", selectWidth).css('height', selectHeight);
        var bannerType = scriptJquery('input[name=banner_type]:checked').val();
        if(bannerType == 0) {
            scriptJquery('#banner_image').hide();
            scriptJquery('#banner_url').hide();
            scriptJquery('#banner_html_code').show();
        } else {
            scriptJquery('#banner_image').show();
            scriptJquery('#banner_url').show();
            scriptJquery('#banner_html_code').hide();
        }
      } else {
        //Check for website type ads create
        if(adType() == 'promote_website_cnt') {
            scriptJquery('.promote_website_cnt').show();
        }
        scriptJquery('.preview_header').show();
        scriptJquery('._cont').show();
        scriptJquery('.sescmads_create_preview_image').removeClass('sescmads_create_preview_banner');
        scriptJquery('.sescomm_img').css("width", '').css('height', '');
      }

      if(adType() == 'promote_content_cnt') {
        if(scriptJquery('#banner_select_option')) {
            scriptJquery('#banner_select_option').hide();
        }
      } else if(adType() == 'promote_website_cnt') {
        if(scriptJquery('#banner_select_option')) {
            scriptJquery('#banner_select_option').show();
        }
      }
   }
  }

}
var pageContentAdded = false;
scriptJquery(document).on('change','input[name=formate_type]',function(e){
    if(scriptJquery(this).closest('li').hasClass('active'))
      return;
    scriptJquery('.sescommerror').remove();
    var relval = scriptJquery(this).attr('rel');

    var currentType = scriptJquery(this).closest('li').parent().find('li.active').find('input').attr('rel');
    console.log(currentType);
    var items = scriptJquery('.'+currentType).find('input, textarea, select');
    for(i=0;i<items.length;i++){
      if(scriptJquery(items[i]).val()){
        if(!confirm('Changes that you made may not be saved.'))
          return;
        break;
      }
    }
    scriptJquery('#add_card').prop('checked', false);
    scriptJquery(this).closest('li').parent().find('li').removeClass('active');
    scriptJquery(this).closest('li').addClass('active');
    scriptJquery('.promote_content_cnt').find('.hideall').hide();
    scriptJquery('.'+relval).show();
    scriptJquery('#sescomm_video_div').hide();
    scriptJquery('._preview_content_img').find('img').hide();
    scriptJquery('#sescomm_video_div').attr('src','');
    if(relval == "carousel_div"){
      scriptJquery('.sescmads_create_preview_carousel').show();
      scriptJquery('.sescmads_create_preview_video').hide();
      scriptJquery('.sescmads_create_preview_image').hide();
      scriptJquery('._preview_content_img').find('img').show();
      scriptJquery('.sescommunity_preview_content').append(scriptJquery('.sescommunity_preview_content').find('.sescommunity_preview_cnt').clone());
    }else{
      if(relval == "video_div"){
        scriptJquery('.sescmads_create_preview_video').show();
        scriptJquery('.sescmads_create_preview_carousel').hide();
        scriptJquery('.sescmads_create_preview_image').hide();
        scriptJquery('#sescomm_video_div').show();
        scriptJquery('#sescomm_video_div').attr('src',scriptJquery('#sescomm_video_div').attr('data-original'));
      }else{ console.log('1');
        scriptJquery('.sescmads_create_preview_image').show();
        scriptJquery('.sescmads_create_preview_video').hide();
        scriptJquery('.sescmads_create_preview_carousel').hide();
        scriptJquery('._preview_content_img').find('img').show();
      }
      scriptJquery('.removecarousel').trigger('click');
      scriptJquery('.sescommunity_preview_content').find('.sescommunity_preview_cnt').eq(1).remove();
    }
    //Banner Ads Work
    if(relval == "banner_div") {
        if(scriptJquery('._cont')){
            scriptJquery('._cont').hide();
        }
    } else {
        if(scriptJquery('._cont')){
            scriptJquery('._cont').show();
        }
    }
    if(sescommunityIsEditForm == "false"){
      scriptJquery('.'+currentType).find('input, select, textarea').val('');
      scriptJquery('.'+currentType).find('input, select, textarea').trigger('change');
      //scriptJquery('.sescommunityads_carousel_title').html(scriptJquery('.sescommunityads_carousel_title').data('original'));
      scriptJquery('span._preview_title').html(scriptJquery('span._preview_title').data('original'));
      scriptJquery('span._preview_des').html(scriptJquery('span._preview_des').data('original'));
    }
});
scriptJquery(document).on('click','.sescustom_field_a',function(e){
  var valueField = scriptJquery(this).attr('rel');
  scriptJquery('.sescustom_active').removeClass('sescustom_active');
  scriptJquery(this).parent().addClass('sescustom_active');
  scriptJquery('.form-elements').find('.form-wrapper').hide();
  if(valueField != "sescommunity_network_targetting"){
    scriptJquery('.sesprofile_field_'+valueField).closest('.form-wrapper').show();
    scriptJquery('#sescustom_fields').show();
    scriptJquery('.sescommunity_network_targetting').hide();
  }else{
    scriptJquery('#sescustom_fields').hide();
    scriptJquery('.sescommunity_network_targetting').show();
  }
});
var runOnceScriptSescomm =  true;
var changeSescommFormOption = false;
function defaultRunSescommunityads(){
  scriptJquery('.sescustom_field_a').closest('ul').children().eq(0).find('a').trigger('click');
  if(runOnceScriptSescomm == true){
    changeSescommFormOption = true;
    scriptJquery(document).on('keyup','input, textarea',function(){
      if(scriptJquery('.ads_preview_page').css('display') == "block"){
       //changeSescommFormOption = false;
       if(scriptJquery(this).val() != ""){
          scriptJquery(this).parent().find('.sescommerror').remove();
       }else{
          //scriptJquery(this).parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
       }
      }
    });
    scriptJquery(document).on('change','input[type=file], select:not(.cat)',function(){
      if(scriptJquery(this).val() != ""){
          scriptJquery(this).parent().find('.sescommerror').remove();
       }else{
          if(!scriptJquery(this).parent().find('.sescommerror').length)
          scriptJquery(this).parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
       }
    });
    runOnceScriptSescomm = false;
  }
}
function getBoostPostData(value){
  //get boost post data
  var action_id = "";
  if(selectedBoostPostId != 0){
    action_id = 1;
  }
    if(!value)
        value = 0
  var request = scriptJquery.ajax({
    dataType: 'html',
    url : en4.core.baseUrl+'sescommunityads/index/get-boost-post-activity/selected/'+value+'/is_action_id/'+action_id,
    data : {
      format : 'html',
    },
    evalScripts : true,
    success : function(responseHTML) {
      scriptJquery('#sescmads_select_post_overlay').remove();
      scriptJquery('.sescmads_select_post').append(responseHTML);
      if(selectedBoostPostId != 0){
        scriptJquery('#sescommunityads_boost_feed_viewmore').hide();
      }
    }
  });
 
}
scriptJquery(document).on('click','.boost_post_sescomm',function(e){
  var actionid = scriptJquery(this).attr('data-rel');
  var request = scriptJquery.ajax({
    dataType: 'html',
    url : en4.core.baseUrl+'sescommunityads/index/get-activity',
    data : {
      format : 'html',
      action_id:actionid,
    },
    evalScripts : true,
    success : function(responseHTML) {
      //insert activity id in html preview div
      if(responseHTML === 0){
        alert('<?php echo $this->string()->escapeJavascript("Please select valid post to boost"); ?>');
      }else{
        scriptJquery('.sescommunityads_boostpost_popup_preview').html('<div class="sesact_feed sesbasic_bxs sesbasic_clearfix"><ul class="feed sesbasic_clearfix sesbasic_bxs sescommunityads_feed">'+responseHTML+"</ul><div class='sescommunityads_feed_preview_overlay'></div></div>");
        scriptJquery('.sescommunityads_feed').find('.sesadvcmt_comments').hide();
      }
    }
  });
  
});
function clickAElement(obj){
  scriptJquery('.sescomm_active').removeClass('sescomm_active');
  scriptJquery(obj).parent().addClass('sescomm_active');
  var rel = scriptJquery(obj).attr('data-rel');
  scriptJquery('.sescommunityads_choose').find('.sescomm_carousel_img').hide();
  scriptJquery('.sescommunityads_choose').find('.sescomm_carousel_img').eq(rel - 1).show();
  sesowlJqueryObject('.sescmads_create_carousel').trigger('to.owl.carousel', rel - 1);
}
scriptJquery(document).on('click','#sescomm_ad_car_li > li > a',function(){
  clickAElement(this);
});
scriptJquery(document).on('click','#add-media',function(e){
  var totalLi = scriptJquery('#sescomm_ad_car_li').children().length;
  if(totalLi === 9){
    scriptJquery(this).hide();
  }
  var length = totalLi+1;
  scriptJquery('#sescomm_ad_car_li').append('<li class=""><a href="javascript:;" data-rel="'+length+'">'+length+'</a></li>');
  scriptJquery('.sescommunityads_choose').append(scriptJquery('.sescommunityads_choose').find('.sescomm_carousel_img').eq(0).clone());
  var insertedDiv = scriptJquery('.sescommunityads_choose').find('.sescomm_carousel_img').length - 1;
  var lastDivElement = scriptJquery('.sescommunityads_choose').find('.sescomm_carousel_img').eq(insertedDiv);
  scriptJquery(lastDivElement).prepend('<div class="sescmads_create_carousel_item_remove"><a href="javascript:;" class="removecarousel">Remove</a></div>');
  lastDivElement.hide();
  lastDivElement.find('input').val('');
  //add element after given index
  // adds an item before the first item
 var html = scriptJquery('.sescmads_create_preview_carousel').find('.sescomm_carousel_default').find('.sescmads_create_preview_item_item')[0].outerHTML
  //redo work for add before.
  if(scriptJquery('#add_card:checked').length){
    sesowlJqueryObject('.sescmads_create_carousel')
      .trigger('add.owl.carousel', [html, insertedDiv])
      .trigger('refresh.owl.carousel');
  }else{
    sesowlJqueryObject('.sescmads_create_carousel')
      .trigger('add.owl.carousel', [sesowlJqueryObject(html)])
      .trigger('refresh.owl.carousel');
  }
});

scriptJquery(document).on('change','.add_comm_card',function(e){
  var checked = scriptJquery(this).is(":checked");
  var parent = scriptJquery(this).closest('.sescmads_create_fields');
  var moreElemData = scriptJquery('.sescmads_create_preview_carousel').find('.sescomm_carousel_default').find('.sescmads_create_preview_item_more')[0].outerHTML;
  var totalLi = scriptJquery('#sescomm_ad_car_li').children().length;
  var owlLi = scriptJquery('.sescmads_create_carousel').find('.owl-stage-outer').find('.owl-stage').find('.owl-item').length;
  if(checked == true){
      parent.find('.checkbox_val').show();
      sesowlJqueryObject('.sescmads_create_carousel')
        .trigger('add.owl.carousel', [sesowlJqueryObject(moreElemData)])
        .trigger('refresh.owl.carousel');
  }else if(owlLi > 2){
      sesowlJqueryObject('.sescmads_create_carousel').trigger( 'remove.owl.carousel', [totalLi] ).trigger('refresh.owl.carousel');
      parent.find('.checkbox_val').hide();
      scriptJquery('.checkbox_val').find('input').val('');
  }
})
scriptJquery(document).on('click','.removecarousel',function(){
  var index = scriptJquery('.sescommunityads_choose').find('.sescomm_carousel_img').index(scriptJquery(this).closest('.sescomm_carousel_img'));
  var liEment = scriptJquery('#sescomm_ad_car_li').children();
  var trigger = false;
  if(liEment.eq(index).hasClass('sescomm_active'))
    trigger = true;
  liEment.eq(index).remove();
  scriptJquery('.sescommunityads_choose').find('.sescomm_carousel_img').eq(index).remove();
  var liEmentNew = scriptJquery('#sescomm_ad_car_li').children();
  for(i=0;i<liEmentNew.length;i++){
    scriptJquery(liEmentNew[i]).find('a').attr('data-rel',i+1);
    scriptJquery(liEmentNew[i]).find('a').html(i+1);
  }
  if(trigger == true)
    clickAElement(scriptJquery('#sescomm_ad_car_li').children().eq(0).find('a'));
  if(scriptJquery('#sescomm_ad_car_li').children().length < 10)
      scriptJquery('#add-media').show();
  //remove element from carousel
  sesowlJqueryObject('.sescmads_create_carousel').trigger('remove.owl.carousel',index).trigger('refresh.owl.carousel');
});
scriptJquery(document).on('keyup','.sescommunityads_carousel_title_text',function(){
  if(scriptJquery(this).val())
    scriptJquery('.sescommunityads_carousel_title').html(scriptJquery(this).val());
  else
    scriptJquery('.sescommunityads_carousel_title').html(scriptJquery('.sescommunityads_carousel_title').data('original'));
});
function imagePreview(input){

  var className = "";
  if(scriptJquery(input).hasClass('more_image')){
    className = "more_image";
  }
  if(scriptJquery(input).hasClass('_website_main_img')){
    className = "_website_main_img";
  }
  if(scriptJquery(input).hasClass('video_image')){
      className = "video_image";
  }

  var value = scriptJquery('input[name=formate_type]:checked').attr('rel');

  var url = input.value;
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'gif' || ext == 'GIF')) {
      var reader = new FileReader();
      reader.onload = function (e) {

        //Banner Image Work
        if(value == 'banner_div') {
            var selectWidth = scriptJquery('select[name="banner_id"]').find(":selected").attr('data-width');
            var selectHeight = scriptJquery('select[name="banner_id"]').find(":selected").attr('data-height');
            var img = new Image();
            img.src = e.target.result;
            img.onload = function () {
                var width = this.width;
                var height = this.height;
                if (height > selectHeight && width > selectWidth) {
                    if(!scriptJquery(input).parent().find('.sescommerror').length){
                        scriptJquery(input).parent().append('<div class="sescommerror">'+en4.core.language.translate('You can choose image according to banner size.')+'</div>');
                        scriptJquery(input).val('');
                    }
                    isValidSescommForm = true;
                } else {
                    checkTypeAds('sescomm_img',e.target.result,className);
                }
            }
        } else {
            checkTypeAds('sescomm_img',e.target.result,className);
        }
      }
      reader.readAsDataURL(input.files[0]);
  }else{
      scriptJquery(input).val('');
      checkTypeAds('sescomm_img','',className);
  }
};
function findElementInOwlCarousel(){
  var index = scriptJquery('#sescomm_ad_car_li').children().index(scriptJquery('#sescomm_ad_car_li').find('.sescomm_active'));
  return scriptJquery('.sescmads_create_carousel').find('.owl-stage-outer').find('.owl-stage').find('.owl-item').eq(index).find('.sescmads_create_preview_item');
}

function changeHeightWidth(value) {
    var selectWidth = scriptJquery('select[name="banner_id"]').find(":selected").attr('data-width');
    var selectHeight = scriptJquery('select[name="banner_id"]').find(":selected").attr('data-height');
    scriptJquery('.sescomm_img').css("width", selectWidth).css('height', selectHeight);
}

function checkTypeAds(className,valueField,classMore){
    var typeAd = adType();

    if(typeAd == "promote_content_cnt" || typeAd == "promote_website_cnt"){
        var value = scriptJquery('input[name=formate_type]:checked').attr('rel');
        console.log(value);
        if(value == "carousel_div"){
          var elem = findElementInOwlCarousel();
          var parentElem = elem;
          if(typeAd == "promote_content_cnt"){
            scriptJquery('.sescmads_create_preview_carousel').find('.sescommunity_preview_sub_image').show();
            scriptJquery('.sescmads_create_preview_carousel').find('.sescommunity_sponsored').show();
            scriptJquery('.sescmads_create_preview_carousel').find('.sescommunity_preview_sub_image').find('a').find('image').attr('src',contentImageValue);
          }
          scriptJquery('.sescmads_create_preview_carousel').find('.preview_title').find('a').html(contentTitleValue);
        }else if(value == "video_div"){
          var parentElem = scriptJquery('.sescmads_create_preview_video');
        } else if(value == 'banner_div') {
            var parentElem = scriptJquery('.sescmads_create_preview_image');
            scriptJquery('.sescmads_create_preview_image').addClass('sescmads_create_preview_banner');
            var selectWidth = scriptJquery('select[name="banner_id"]').find(":selected").attr('data-width');
            var selectHeight = scriptJquery('select[name="banner_id"]').find(":selected").attr('data-height');
            scriptJquery('.sescomm_img').css("width", selectWidth).css('height', selectHeight);
        }
        else{
          var parentElem = scriptJquery('.sescmads_create_preview_image');
          scriptJquery('.sescmads_create_preview_image').removeClass('sescmads_create_preview_banner');
          scriptJquery('.sescomm_img').css("width", '').css('height', '');
        }
        if(typeAd == "promote_content_cnt"){
          scriptJquery(parentElem).find('.sescommunity_preview_sub_image').show();
          scriptJquery(parentElem).find('.sescommunity_sponsored').show();
          scriptJquery(parentElem).find('.sescommunity_preview_sub_image').find('a').find('image').attr('src',contentImageValue);
        }else if(typeAd == "promote_website_cnt"){
            contentTitleValue = scriptJquery('.website_title').val();
        }
        scriptJquery(parentElem).find('.preview_title').find('a').html(contentTitleValue);
        if(value == "carousel_div"){
          if(classMore == "_website_main_img"){
            scriptJquery('.sescommunity_sponsored').show();
            scriptJquery('.sescommunity_preview_sub_image').find('a').find('img').attr('src',valueField);
          }else if(classMore == "more_image"){
            var elem = scriptJquery('.sescmads_create_carousel').find('.owl-stage-outer').find('.owl-stage').find('.owl-item');
            var length = elem.length;
            var div = elem.eq(length - 1);
            div.find('._img').find('a').find('img').attr('src',valueField);
          }else if(className == "more_text"){
            var elem = scriptJquery('.sescmads_create_carousel').find('.owl-stage-outer').find('.owl-stage').find('.owl-item');
            var length = elem.length;
            var div = elem.eq(length - 1);
            div.find('._des').html(valueField);
          }else if(className != "sescomm_call_to_action" && className != "sescomm_img" && className != "sescomm_call_to_action_overlay")
            scriptJquery(parentElem).find('.'+className).html(valueField);
          else if(className == "sescomm_img"){
            if(valueField != ""){
              scriptJquery(parentElem).find('.'+className).find('a').find('img').attr('src',valueField);
            }else{
              scriptJquery(parentElem).find('.'+className).find('a').find('img').attr('src',blankImage);
            }
          }else if(className == "sescomm_call_to_action"){
            if(valueField != ""){
                var elem = scriptJquery('.sescmads_create_carousel').find('.owl-stage-outer').find('.owl-stage').find('.owl-item');
                elem.each(function(index){
                   if(scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).length){
                      scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).show();
                      scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).find('a').html(valueField);
                   }
                });
            }else{
                var elem = scriptJquery('.sescmads_create_carousel').find('.owl-stage-outer').find('.owl-stage').find('.owl-item');
                elem.each(function(index){
                   if(scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).length){
                      scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).hide();
                   }
                });
            }
          }else if(className == "sescomm_call_to_action_overlay"){
              if(valueField != ""){
                var elem = scriptJquery('.sescmads_create_carousel').find('.owl-stage-outer').find('.owl-stage').find('.owl-item');
                elem.each(function(index){
                   if(scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).length){
                      scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).show();
                      scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).html(valueField);
                   }
                });
            }else{
                var elem = scriptJquery('.sescmads_create_carousel').find('.owl-stage-outer').find('.owl-stage').find('.owl-item');
                elem.each(function(index){
                   if(scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).length){
                      scriptJquery(this).find('.sescmads_create_preview_item').find('.'+className).hide();
                   }
                });
            }
          }

        }else if(value == "image_div" || value == "video_div" || value == 'banner_div') {
            if(classMore == "video_image"){
                scriptJquery(parentElem).find('.'+className).attr('poster',valueField)
                return;
            }
          if(className != "sescomm_call_to_action" && className != "sescomm_img")
            scriptJquery(parentElem).find('.'+className).html(valueField);
          else if(classMore == "_website_main_img"){
            scriptJquery('.sescommunity_sponsored').show();
            scriptJquery('.sescommunity_preview_sub_image').find('a').find('img').attr('src',valueField);
          }
          else if(className == "sescomm_img" && (value == "image_div" || value == 'banner_div')){
            if(valueField != ""){
              scriptJquery(parentElem).find('.'+className).find('a').find('img').attr('src',valueField);
            }else{
              scriptJquery(parentElem).find('.'+className).find('a').find('img').attr('src',blankImage);
            }
          }else if(className == "sescomm_img" && value == "video_div"){
             scriptJquery(parentElem).find('.'+className).show();
            if(valueField != ""){
              scriptJquery(parentElem).find('.'+className).attr('src',valueField);
            }else{
              scriptJquery(parentElem).find('.'+className).attr('src',blankImage);
            }
          }else if (className == "sescomm_call_to_action"){
            if(valueField != ""){
                scriptJquery(parentElem).find('.'+className).show();
                scriptJquery(parentElem).find('.'+className).find('a').html(valueField);
            }else{
                scriptJquery(parentElem).find('.'+className).hide();
            }
          }
          if(value == 'banner_div') {
                scriptJquery('.promote_website_cnt').hide();
                scriptJquery('.preview_header ').hide();
          } else {
            //scriptJquery('.promote_website_cnt').show();
            //scriptJquery('.preview_header ').show();
          }
        }
    }else{

    }
}
scriptJquery(document).on('keyup','.sescommunity_content_text',function(){
  var className = scriptJquery(this).attr('class');
  className = className.replace('sescommunity_content_text ','').replace('required ','');
  className = className.trim();
  checkTypeAds(className,scriptJquery(this).val());
  if(scriptJquery(this).attr('id') == "website_url"){
    scriptJquery('._preview_url').show();
    var value = scriptJquery(this).val();
    if(value.indexOf('http://') > -1 || value.indexOf('https://') > -1){
      value = value.replace('http://','').replace('https://');
      var splial = value.split('/');
      scriptJquery('._preview_url').html(splial[0]);
    }else{
        scriptJquery('._preview_url').html('');
    }
  }else{
    scriptJquery('._preview_url').hide();
  }
});
scriptJquery(document).on('change','.sescomm_call_to_action',function(){
    var text = scriptJquery(this).find(':selected').text();
    if(scriptJquery(this).val()){
      checkTypeAds('sescomm_call_to_action',text)
    }else{
      checkTypeAds('sescomm_call_to_action','')
    }
});
scriptJquery(document).on('change','.sescomm_call_to_action_overlay',function(){
    var text = scriptJquery(this).find(':selected').text();
    if(scriptJquery(this).val()){
      checkTypeAds('sescomm_call_to_action_overlay',text);
    }else{
      checkTypeAds('sescomm_call_to_action_overlay','');
    }
});
function uploadVideoSescomm(obj){
  var $source = scriptJquery('#sescomm_video_div');
   var url = obj.value;
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  if (obj.files && obj.files[0] && (ext == "mp4")) {
    checkTypeAds('sescomm_img',URL.createObjectURL(obj.files[0]));
  }else{
    scriptJquery(obj).val('');
    checkTypeAds('sescomm_img','');
  }
};
scriptJquery(document).on('click','.ad_end_date',function(){
  var isChecked = scriptJquery('.ad_end_date:checked').length;
  if(isChecked == 1){
    scriptJquery('.sescomm_end_date_div').hide();
    scriptJquery('#sescomm_end_date').val('').attr('readonly','readonly');
    scriptJquery('#sescomm_end_time').val('').attr('readonly','readonly');
  }else{
    scriptJquery('.sescomm_end_date_div').show();
    scriptJquery('#sescomm_end_date').removeAttr('readonly');
    scriptJquery('#sescomm_end_time').removeAttr('readonly');
  }
});

//time
scriptJquery(document).ready(function(){
  if(scriptJquery('#sescomm_start_date').length){
    sescommDateFn();
  }
});
 var sescommsesselectedDate;
 var sescommsesFromEndDate;
 function sescommDateFn(){
   sescommsesselectedDate = new Date(scriptJquery('#sescomm_start_date').val());
    sesBasicAutoScroll('#sescomm_start_time').timepicker({
        'showDuration': true,
        'timeFormat': 'g:ia',
    }).on('changeTime',function(){
      var lastTwoDigit = sesBasicAutoScroll('#sescomm_end_time').val().slice('-2');
      var endDate = new Date(sesBasicAutoScroll('#sescomm_end_date').val()+' '+sesBasicAutoScroll('#sescomm_end_time').val().replace(lastTwoDigit,'')+':00 '+lastTwoDigit);
      var lastTwoDigitStart = sesBasicAutoScroll('#sescomm_start_time').val().slice('-2');
      var startDate = new Date(sesBasicAutoScroll('#sescomm_start_date').val()+' '+sesBasicAutoScroll('#sescomm_start_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);
      var error = sescommCheckDateTime(startDate,endDate);
    });
    sesBasicAutoScroll('#sescomm_end_time').timepicker({
        'showDuration': true,
        'timeFormat': 'g:ia'
    }).on('changeTime',function(){
      var lastTwoDigit = sesBasicAutoScroll('#sescomm_end_time').val().slice('-2');
      var endDate = new Date(sesBasicAutoScroll('#sescomm_end_date').val()+' '+sesBasicAutoScroll('#sescomm_end_time').val().replace(lastTwoDigit,'')+':00 '+lastTwoDigit);
      var lastTwoDigitStart = sesBasicAutoScroll('#sescomm_start_time').val().slice('-2');
      var startDate = new Date(sesBasicAutoScroll('#sescomm_start_date').val()+' '+sesBasicAutoScroll('#sescomm_start_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);
      var error = sescommCheckDateTime(startDate,endDate);
    });
    sesBasicAutoScroll('#sescomm_start_date').datepicker({
        format: 'm/d/yyyy',
        weekStart: 1,
        autoclose: true,
        startDate: sescommstartCalanderDate,
        endDate: sescommsesFromEndDate,
    }).on('changeDate', function(ev){
      sescommsesselectedDate = ev.date;
        var y = sescommsesselectedDate.getFullYear(), m = sescommsesselectedDate.getMonth(), d = sescommsesselectedDate.getDate();
        m = ('0'+ (m+1)).slice(-2);

        var date = m+'/'+d+'/'+y;
      //var end_date = sesBasicAutoScroll('#sescomm_end_date').data('DateTimePicker');
      sesBasicAutoScroll('#sescomm_end_date').datepicker( "option", "minDate", date)
      var lastTwoDigit = sesBasicAutoScroll('#sescomm_end_time').val().slice('-2');
      var endDate = new Date(sesBasicAutoScroll('#sescomm_end_date').val()+' '+sesBasicAutoScroll('#sescomm_end_time').val().replace(lastTwoDigit,'')+':00 '+lastTwoDigit);
      var lastTwoDigitStart = sesBasicAutoScroll('#sescomm_start_time').val().slice('-2');
      var startDate = new Date(sesBasicAutoScroll('#sescomm_start_date').val()+' '+sesBasicAutoScroll('#sescomm_start_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);
      var error = sescommCheckDateTime(startDate,endDate);
    });

    sesBasicAutoScroll('#sescomm_end_date').datepicker({
        format: 'm/d/yyyy',
        weekStart: 1,
        autoclose: true,
        startDate: sescommsesselectedDate,
    }).on('changeDate', function(ev){
      sescommsesFromEndDate = new Date(ev.date.valueOf());
      sescommsesFromEndDate.setDate(sescommsesFromEndDate.getDate(new Date(ev.date.valueOf())));
      var lastTwoDigit = sesBasicAutoScroll('#sescomm_end_time').val().slice('-2');
      var endDate = new Date(sesBasicAutoScroll('#sescomm_end_date').val()+' '+sesBasicAutoScroll('#sescomm_end_time').val().replace(lastTwoDigit,'')+':00 '+lastTwoDigit);
      var lastTwoDigitStart = sesBasicAutoScroll('#sescomm_start_time').val().slice('-2');
      var startDate = new Date(sesBasicAutoScroll('#sescomm_start_date').val()+' '+sesBasicAutoScroll('#sescomm_start_time').val().replace(lastTwoDigitStart,'')+':00 '+lastTwoDigitStart);
      var error = sescommCheckDateTime(startDate,endDate);
    });
  }
function sescommCheckDateTime(startdate,enddate){console.log(startdate,enddate);
  var errorMessage = '';
  var checkdate = true;


    var currentTime =  new Date();
    var format = 'YYYY/MM/DD HH:mm:ss';
    currentTime = moment(currentTime, format).tz(currentUserTimezone).format(format);
    currentTime =  new Date(currentTime);




  if(currentTime.valueOf() > startdate.valueOf() && sesBasicAutoScroll('#sescomm_start_date').val()){
    errorMessage = sescommStartPastDate;
  }else if(startdate.valueOf() >= enddate.valueOf() && sesBasicAutoScroll('#sescomm_start_date').val() && sesBasicAutoScroll('#sescomm_end_date').val()){
      errorMessage = sescommEndBeforeDate;
  }
  if(errorMessage != ""){
      scriptJquery('.shedulling_error').show();
      scriptJquery('.shedulling_error > span').html(errorMessage);
  }else{
      scriptJquery('.shedulling_error').hide();
      scriptJquery('.shedulling_error > span').html('');
  }
}
var isValidSescommForm = false;
//function to submit form
var submitFormAjaxReMSescommads;
function submitsescommunitycreate(){
  changeSescommFormOption = false;
  validateFunction();

  if(scriptJquery('#location_targetting').length) {
    if(scriptJquery('#location_sescomm').val() && scriptJquery('#sescomm_lat').val() && scriptJquery('#sescomm_lng').val()){
      if(!scriptJquery('#location_distance').val()) {
        isValidSescommForm = true;
        if(!scriptJquery('#location_distance').parent().find('.sescommerror').length)
          scriptJquery('#location_distance').parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
      }else{
        var id = scriptJquery('#location_distance').val();
        if(Math.floor(id) == id && scriptJquery.isNumeric(id)){}else{
          if(!scriptJquery('#location_distance').parent().find('.sescommerror').length)
            scriptJquery('#location_distance').parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
          isValidSescommForm = true;
        }
      }
    }
  }

  //Reverse Location Work
  if(scriptJquery('#location_reversetargetting').length){
    if(scriptJquery('#revselocation_sescomm').val() && scriptJquery('#revsesescomm_lat').val() && scriptJquery('#revsesescomm_lng').val()){
      if(!scriptJquery('#revselocation_distance').val()) {
        isValidSescommForm = true;
        if(!scriptJquery('#revselocation_distance').parent().find('.sescommerror').length)
          scriptJquery('#revselocation_distance').parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
      }else{
        var id = scriptJquery('#revselocation_distance').val();
        if(Math.floor(id) == id && scriptJquery.isNumeric(id)){}else{
          if(!scriptJquery('#revselocation_distance').parent().find('.sescommerror').length)
            scriptJquery('#revselocation_distance').parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
            isValidSescommForm = true;
        }
      }
    }
  }

  if(isValidSescommForm == true){
    //error in form return from here
      return;
  }

  //success, need to subit form at this point
  var promoteTypeValue = scriptJquery('#promote_cnt').val();
  var formDataType;
  if(promoteTypeValue == "promote_content_cnt" || promoteTypeValue == "promote_website_cnt"){
    formDataType = getPromoteContentFormData();
  }if(promoteTypeValue == "boost_post_cnt"){
    formDataType = getBoostData();
  }
  if(scriptJquery('#sescommunityad_id').val() != 0){
    formDataType.append('sescommunityad_id',scriptJquery('#sescommunityad_id').val());
  }
  if(scriptJquery('#category_id').length){
     if(scriptJquery('#category_id').val())
      formDataType.append('category_id',scriptJquery('#category_id').val());
     if(scriptJquery('#subcat_id').val())
      formDataType.append('subcat_id',scriptJquery('#subcat_id').val());
     if(scriptJquery('#subsubcat_id').val())
      formDataType.append('subsubcat_id',scriptJquery('#subsubcat_id').val());
  }

  if(scriptJquery('#sescustom_fields').length){
   //target data
   var targetData = scriptJquery('#sescustom_fields').serialize();
   formDataType.append('targetData',targetData);
  }
  if(promoteTypeValue == "promote_content_cnt"){
    formDataType.append('resource_id',scriptJquery('#sescomm_resource_id').val());
    formDataType.append('resource_type',scriptJquery('#sescomm_resource_type').val());
  }else if (promoteTypeValue == "promote_website_cnt"){
      formDataType.append('website_title',scriptJquery('#website_title').val());
      formDataType.append('website_url',scriptJquery('#website_url').val());
      formDataType.append('website_image',scriptJquery('#website_image')[0].files[0]);
  }
  //schedulling data
  var schedullingData = scriptJquery('#schedulling').serialize();
  formDataType.append('schedullingData',schedullingData);

    //Interest Based
    if(scriptJquery('#interest_targetting')) {
        var interestElem = scriptJquery('#interest_targetting');
        if(interestElem.length > 0) {
            var selectedInterest = new Array();
            scriptJquery('input[name=interest_enable]:checked').each(function() {
                selectedInterest.push(this.value);
            });
        //console.log(selectedInterest);
            formDataType.append('interests',selectedInterest);
        }
    }

  //location
  var locationElem = scriptJquery('#location_targetting');
  if(locationElem.length > 0){
    formDataType.append('location_type',scriptJquery('input[name=location_type]:checked').val());
    formDataType.append('location',scriptJquery('#location_sescomm').val());
    formDataType.append('lat',scriptJquery('#sescomm_lat').val());
    formDataType.append('lng',scriptJquery('#sescomm_lng').val());
  }

  if(scriptJquery('#location_reversetargetting')) {
    var locationElem = scriptJquery('#location_reversetargetting');
    if(locationElem.length > 0){
        formDataType.append('revselocation_type',scriptJquery('input[name=revselocation_type]:checked').val());
        formDataType.append('revselocation',scriptJquery('#revselocation_sescomm').val());
        formDataType.append('revselat',scriptJquery('#revsesescomm_lat').val());
        formDataType.append('revselng',scriptJquery('#revsesescomm_lng').val());
    }
  }

  formDataType.append('existingpackage',scriptJquery('#existingpackage').val());
  //campaign
  var campaign = scriptJquery('#campaign_frm').serialize();
  formDataType.append('campaign',campaign);
  formDataType.append('package_id',scriptJquery('#package_id').val());
  //send form submit request
  formDataType.append('ad_type', promoteTypeValue);
  if(scriptJquery('#networks').length)
    formDataType.append('networks',scriptJquery('#networks').val());
  if(typeof submitFormAjaxReMSescommads != 'undefined')
			submitFormAjaxReMSescommads.abort();
    scriptJquery('#sesbasic_loading_cont_overlay_submit').show();
		submitFormAjaxReMSescommads = scriptJquery.ajax({
        dataType: 'html',
				type:'POST',
				url: en4.core.baseUrl+'sescommunityads/index/create/',
				data:formDataType,
				cache:false,
				contentType: false,
				processData: false,
				success:function(data){
          scriptJquery('#sesbasic_loading_cont_overlay_submit').hide();
					try{
						var result  = scriptJquery.parseJSON(data);
            if(result.error == 1)
              alert(result.message);
            else
              window.location.href = result.url;
					}catch(err){
            alert(en4.core.language.translate("Something went wrong, please try again later."));
					}
				},
				error: function(data){
          scriptJquery('#sesbasic_loading_cont_overlay_submit').hide();
           alert(en4.core.language.translate("Something went wrong, please try again later."));
				}
		});
}
function getBoostData(){
  var form = new FormData();
  form.append('boost_post_id',scriptJquery('.sesboost_post_active').attr('data-rel'));
  return form;
}
function getPromoteContentFormData(){
    var value = scriptJquery('input[name=formate_type]:checked').attr('rel');
    if(value == "carousel_div"){
      var form = new FormData(scriptJquery('#carousel_form')[0]);
      form.append('uploadType','carousel');
    }else if(value == "image_div"){
      var form = new FormData(scriptJquery('#image_form')[0]);
      form.append('uploadType','image');
    }else if(value == "banner_div"){
      var form = new FormData(scriptJquery('#banner_form')[0]);
      form.append('uploadType','banner');
    }
    else{
      var form = new FormData(scriptJquery('#video_form')[0]);
      form.append('uploadType','video');
    }
    return form;
}
function validateFunction(){
  var promoteTypeValue = scriptJquery('#promote_cnt').val();

  //promote content type
  if(promoteTypeValue == "promote_content_cnt" || promoteTypeValue == "promote_website_cnt"){
    if(changeSescommFormOption == false)
    checkPromoteContent();
    changeSescommFormOption = true;
  }
}
//validate functions
function checkPromoteContent(){
  isValidSescommForm = false;
  var type = adType();
  var value = scriptJquery('input[name=formate_type]:checked').attr('rel');
  /*var valueField = scriptJquery('.'+value).find('.sescommunityads_carousel_title_text').val();
  if(!valueField){
    isValidSescommForm = true;
    if(!scriptJquery('.'+value).find('.sescommunityads_carousel_title_text').parent().find('.sescommerror').length)
      scriptJquery('.'+value).find('.sescommunityads_carousel_title_text').parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
  }else
      scriptJquery('.'+value).find('.sescommunityads_carousel_title_text').parent().find('.sescommerror').remove();*/

  if(type == "promote_content_cnt"){
    var sescomm_resource_type = scriptJquery('#sescomm_resource_type');
    var sescomm_resource_id = scriptJquery('#sescomm_resource_id');
    if(sescomm_resource_type.val() == ""){
        isValidSescommForm = true;
        if(!sescomm_resource_type.parent().find('.sescommerror').length)
          sescomm_resource_type.parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
        else
          sescomm_resource_type.parent().find('.sescommerror').remove();
    }
    if(sescomm_resource_id.val() == "" || sescomm_resource_id.val() == null){
        isValidSescommForm = true;
        if(!sescomm_resource_id.parent().find('.sescommerror').length)
            sescomm_resource_id.parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
        else
          sescomm_resource_id.parent().find('.sescommerror').remove();
    }
  }else{

      if(value != 'banner_div') {
        var websiteurl = scriptJquery('#website_url');
        var websitetitle = scriptJquery('#website_title');
        if(websiteurl.val() == ""){
                isValidSescommForm = true;
                if(!websiteurl.parent().find('.sescommerror').length)
                    websiteurl.parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
        }else if(!validateUrl(websiteurl.val())){
            isValidSescommForm = true;
            if(!websiteurl.parent().find('.sescommerror').length)
                websiteurl.parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');
        }else
            websiteurl.parent().find('.sescommerror').remove();

        if(websitetitle.val() == ""){
                isValidSescommForm = true;
                if(!websitetitle.parent().find('.sescommerror').length)
                    websitetitle.parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
        }else
            websitetitle.parent().find('.sescommerror').remove();
      }
  }
  if(value == "carousel_div"){
    var addtocard = scriptJquery('.carousel_div').find('.add_comm_card:checked');
    var imgValue = scriptJquery('.carousel_div').find('.more_image');
    var seemore = scriptJquery('.carousel_div').find('#see_more_url');
    var seemorelink = scriptJquery('.carousel_div').find('#see_more_display_link');

    if(addtocard.length){
       if(!imgValue.val() && (typeValue == "" || typeValue != adType())){
            isValidSescommForm = true;
            if(!imgValue.parent().find('.sescommerror').length)
                imgValue.parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
       }else{
        imgValue.parent().find('.sescommerror').remove();
       }
       if(seemore.val() == ""){
            isValidSescommForm = true;
            if(!seemore.parent().find('.sescommerror').length)
                seemore.parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');

       }else if(!validateUrl(seemore.val())){
         isValidSescommForm = true;
         if(!seemore.parent().find('.sescommerror').length)
            seemore.parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');

       }else{
          seemore.parent().find('.sescommerror').remove();
       }
       if(seemorelink.val() == ""){
            isValidSescommForm = true;
            if(!seemorelink.parent().find('.sescommerror').length)
                seemorelink.parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');

       }else{
          seemorelink.parent().find('.sescommerror').remove();
       }
    }
    /*var valueCallToAction = scriptJquery('.carousel_div').find('.sescomm_call_to_action').val();
    //var callToactionField = scriptJquery('.carousel_div').find('input[name=calltoaction_url]').val();
    if(valueCallToAction /*&& !validateUrl(callToactionField)){
      if(!scriptJquery('.carousel_div').find('.sescomm_call_to_action').parent().find('.sescommerror').length)
        scriptJquery('.carousel_div').find('.sescomm_call_to_action').parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');
    }else
      scriptJquery('.carousel_div').find('.sescomm_call_to_action').parent().find('.sescommerror').remove();*/
    var errorElementDivContainer = "";
    var counterErrorElement = false;

    var element = scriptJquery('.sescommunityads_choose').find('.sescomm_carousel_img');
    for(i=0;i<element.length;i++){
      var field = scriptJquery(element[i]).find('input');
      for(j=0;j<field.length;j++){
        var typeElem = scriptJquery(field[j]).prop('type');
        if(scriptJquery(field[j]).hasClass('required') && (typeElem != "file" ||  (typeElem == "file" && !scriptJquery(field[j]).hasClass('fromedit')))){
          var valueField = scriptJquery(field[j]).val();
          if(!valueField){
              if(!scriptJquery(scriptJquery(field[j])).parent().find('.sescommerror').length){
                scriptJquery(scriptJquery(field[j])).parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
              }
              isValidSescommForm = true;
          }else{
              scriptJquery(scriptJquery(field[j])).parent().find('.sescommerror').remove();
          }
          if(scriptJquery(field[j]).hasClass('url')){
             if(!validateUrl(valueField)){
               if(!scriptJquery(scriptJquery(field[j])).parent().find('.sescommerror').length){
                  scriptJquery(scriptJquery(field[j])).parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');
               }
              isValidSescommForm = true;
             }else{
               scriptJquery(scriptJquery(field[j])).parent().find('.sescommerror').remove();
             }
          }
        }
      }

      if(isValidSescommForm == true && counterErrorElement == false){
        errorElementDivContainer =  scriptJquery(element[i]);
        counterErrorElement = true;
      }
    }

    if(isValidSescommForm == true){
        var indexError  = scriptJquery('.sescommunityads_choose').find('.sescomm_carousel_img').index(errorElementDivContainer);
        scriptJquery('#sescomm_ad_car_li').children().eq(indexError).find('a').trigger('click');
    }
  }else if(value == "image_div"){
   /* var valueCallToAction = scriptJquery('.image_div').find('.sescomm_call_to_action').val();
   // var callToactionField = scriptJquery('.image_div').find('input[name=calltoaction_url]').val();
    if(valueCallToAction /*&& !validateUrl(callToactionField)){
      if(!scriptJquery('.image_div').find('.sescomm_call_to_action').parent().find('.sescommerror').length)
        scriptJquery('.image_div').find('.sescomm_call_to_action').parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');
    }else
      scriptJquery('.image_div').find('.sescomm_call_to_action').parent().find('.sescommerror').remove(); */
    var element = scriptJquery('.image_div').find('input.required');
    for(j=0;j<element.length;j++){
        var typeElem = scriptJquery(element[j]).prop('type');
        var valueField = scriptJquery(element[j]).val();
        if(!valueField && (typeElem != "file" ||  (typeElem != "file" ||  (typeElem == "file" && !scriptJquery(element[j]).hasClass('fromedit'))))){
            if(!scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').length){
              scriptJquery(scriptJquery(element[j])).parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
            }
            isValidSescommForm = true;
        }else{
            scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').remove();
        }
        if(scriptJquery(element[j]).hasClass('url')){
           if(!validateUrl(valueField)){
             if(!scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').length){
                scriptJquery(scriptJquery(element[j])).parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');
             }
            isValidSescommForm = true;
           }else{
             scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').remove();
           }
        }
    }
  }
  else if(value == "banner_div"){
    //var valueCallToAction = scriptJquery('.banner_div').find('.sescomm_banner_size').val();
   //var callToactionField = scriptJquery('.banner_div').find('input[name=calltoaction_url]').val();
//     if(valueCallToAction){
//       if(!scriptJquery('.banner_div').find('.sescomm_banner_size').parent().find('.sescommerror').length)
//         scriptJquery('.banner_div').find('.sescomm_banner_size').parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');
//     }else
//       scriptJquery('.banner_div').find('.sescomm_banner_size').parent().find('.sescommerror').remove();

    var element = scriptJquery('.banner_div').find('input.required');
    var banner_type = scriptJquery('input[name=banner_type]:checked').val();
    for(j=0;j<element.length;j++){
        var typeElem = scriptJquery(element[j]).prop('type');
        var valueField = scriptJquery(element[j]).val();

        if(!valueField && (typeElem != "file" ||  (typeElem != "file" ||  (typeElem == "file" && !scriptJquery(element[j]).hasClass('fromedit')))) ){ console.log(typeElem, 'banner');

            if(banner_type == 1 && typeElem != "file") {
                if(!scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').length){
                    scriptJquery(scriptJquery(element[j])).parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
                }
                isValidSescommForm = true;
            }
        } else {
            scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').remove();
        }

        if(banner_type == 1) {
            if(scriptJquery(element[j]).hasClass('url')){
                if(!validateUrl(valueField)){
                    if(!scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').length){
                        scriptJquery(scriptJquery(element[j])).parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');
                    }
                    isValidSescommForm = true;
                }else{
                    scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').remove();
                }
            }
        }
    }
  }
  else{
    /*var valueCallToAction = scriptJquery('.video_div').find('.sescomm_call_to_action').val();
    //var callToactionField = scriptJquery('.video_div').find('input[name=calltoaction_url]').val();
    if(valueCallToAction /*&& !validateUrl(callToactionField)){
      if(!scriptJquery('.video_div').find('.sescomm_call_to_action').parent().find('.sescommerror').length)
        scriptJquery('.video_div').find('.sescomm_call_to_action').parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');
    }else
      scriptJquery('.video_div').find('.sescomm_call_to_action').parent().find('.sescommerror').remove();*/
    var element = scriptJquery('.video_div').find('input.required');
    for(j=0;j<element.length;j++){
        var typeElem = scriptJquery(element[j]).prop('type');
        var valueField = scriptJquery(element[j]).val();
        if(!valueField && (typeElem != "file" ||  (typeElem != "file" ||  (typeElem == "file" && !scriptJquery(element[j]).hasClass('fromedit'))))){
            if(!scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').length){
              scriptJquery(scriptJquery(element[j])).parent().append('<div class="sescommerror">'+errorMessageSescomm+'</div>');
            }
            isValidSescommForm = true;
        }else{
            scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').remove();
        }
        if(scriptJquery(element[j]).hasClass('url')){
           if(!validateUrl(valueField)){
             if(!scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').length){
                scriptJquery(scriptJquery(element[j])).parent().append('<div class="sescommerror">'+invalidUrlerrorMessageSescomm+'</div>');
             }
            isValidSescommForm = true;
           }else{
             scriptJquery(scriptJquery(element[j])).parent().find('.sescommerror').remove();
           }
        }
    }
  }
}


function validateUrl(url){
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}
var errorMessageSescomm = "This is required field.";
var invalidUrlerrorMessageSescomm = "Please enter valid URL";




scriptJquery(document).on('click','input[name=camapign_delete]',function(){
  var value = scriptJquery(this).is(':checked');
  if(value == true){
    scriptJquery('#sescommunityads_campaigns').find('input[type=checkbox]').prop('checked',true);
  }else{
    scriptJquery('#sescommunityads_campaigns').find('input[type=checkbox]').prop('checked',false);
  }
});

scriptJquery(document).on('click','#sescommunityads_campaigns input[type=checkbox]',function(){
    var elements = scriptJquery('#sescommunityads_campaigns input[type=checkbox]');
    var valid = true;
    for(i=0;i<elements.length;i++){
      if(scriptJquery(elements[i]).is(':checked') == false){
        scriptJquery('input[name=camapign_delete]').prop('checked',false);
        valid = false;
      }
    }
    if(valid == true)
    scriptJquery('input[name=camapign_delete]').prop('checked',true);
});
  scriptJquery(document).on('change','#sescomm_resource_type',function(e){
    var type = scriptJquery(this).val();
    scriptJquery('#sescomm_resource_id').html('');
    if(type){
      scriptJquery.post('sescommunityads/index/module-data',{type:type,selected:selectedType},function(response){
        if(response != false){
          scriptJquery('#sescomm_resource_id').html(response);
        }else{
            scriptJquery('#sescomm_resource_id').html('<option value="">No content created by you yet.</option>');
        }
        if(selectedType){
          scriptJquery('#sescomm_resource_id').trigger('change');
        }
      });
    }
  });
  scriptJquery(document).on('change','#sescomm_resource_id',function(e){
     var text = scriptJquery('#sescomm_resource_id').find(':selected').text();
     var image = scriptJquery('#sescomm_resource_id').find(':selected').attr('data-src');
     if(typeof image == "undefined")
      image = blankImage;
     scriptJquery('.sescommunity_preview_sub_image').show();
     scriptJquery('.sescommunity_sponsored').show();
     scriptJquery('.sescommunity_preview_sub_image').find('a').find('img').attr('src',image);
     scriptJquery('.preview_title').find('a').html(text);
     contentTitleValue = text;
  });
scriptJquery(document).on('submit','#sescommunityads_campaign_frm',function(e){
    var elements = scriptJquery('#sescommunityads_campaigns input[type=checkbox]');
    var valid = false;
    for(i=0;i<elements.length;i++){
      if(scriptJquery(elements[i]).is(':checked') == true){
        valid = true;
      }
    }
    return valid;
});
scriptJquery(document).on('click','.sescomm_campaign_del',function(e){
  if(confirm(en4.core.language.translate('Are you sure want to delete the selected campaign, this action can not be undone?'))){
     scriptJquery('#sescommunityads_campaign_frm').trigger('submit');
  }
});
function createSesadvCarousel(){
  if(!scriptJquery('.sescmads_create_carousel').length)
    return;
  sesowlJqueryObject('.sescmads_create_carousel').owlCarousel({
    nav : true,
    loop:false,
    items:1,
  })
  sesowlJqueryObject(".owl-prev").html('<i class="fa fa-angle-left"></i>');
  sesowlJqueryObject(".owl-next").html('<i class="fa fa-angle-right"></i>');
}
function sescommMapList() {
  var input = document.getElementById('location_sescomm');
  var autocomplete = new google.maps.places.Autocomplete(input);
  google.maps.event.addListener(autocomplete, 'place_changed', function () {
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }
    document.getElementById('sescomm_lng').value = place.geometry.location.lng();
    document.getElementById('sescomm_lat').value = place.geometry.location.lat();
  });
}

function sescommRevseMapList() {
  var input = document.getElementById('revselocation_sescomm');
  var autocomplete = new google.maps.places.Autocomplete(input);
  google.maps.event.addListener(autocomplete, 'place_changed', function () {
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }
    document.getElementById('revsesescomm_lng').value = place.geometry.location.lng();
    document.getElementById('revsesescomm_lat').value = place.geometry.location.lat();
  });
}

function showBannerAds(value) {
    if(value == 1) {
        //scriptJquery('#banner_size').show();
        scriptJquery('#banner_image').show();
        scriptJquery('#banner_url').show();
        scriptJquery('#banner_html_code').hide();
    } else {
        //scriptJquery('#banner_size').hide();
        scriptJquery('#banner_image').hide();
        scriptJquery('#banner_url').hide();
        scriptJquery('#banner_html_code').show();
        scriptJquery('#image-banner').val('');
        scriptJquery('#destination_url').val('');
        checkTypeAds('sescomm_img','');
    }
}
