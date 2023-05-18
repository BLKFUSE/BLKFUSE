function sescommMapSearch() {
  var input = document.getElementById('locationSesList');
  var autocomplete = new google.maps.places.Autocomplete(input);
  google.maps.event.addListener(autocomplete, 'place_changed', function () {
    var place = autocomplete.getPlace();
    if (!place.geometry) {
      return;
    }
    document.getElementById('lngSesList').value = place.geometry.location.lng();
    document.getElementById('latSesList').value = place.geometry.location.lat();
  });
}
scriptJquery(document).on('click','.sescomm_hide_ad',function(e){ alert('121');
  scriptJquery(this).closest('.sescmads_ads_item_img').hide();
  if(scriptJquery('.sescmads_bannerad_display')) {
    scriptJquery('.sescmads_bannerad_display').hide();
    scriptJquery(this).closest('.sescmads_bannerad_item').find('.sescmads_hidden_ad').show();
  }
  scriptJquery(this).closest('.sescmads_ads_listing_item').find('.sescmads_hidden_ad').show();
});

scriptJquery(document).on('change','input[name="ad-option-spam"]',function(e){
  var value = scriptJquery(this).val();
  scriptJquery(this).closest('._ad_hidden_options').find('.sescomm_other').hide();
  if(value == "Other"){
    scriptJquery(this).closest('._ad_hidden_options').find('.sescomm_other').show();
    return;
  }
  sendRequestReport(value,'',this);
});
scriptJquery(document).on('click','.sescomm_report_other_smt',function(){
  if(scriptJquery(this).hasClass('active'))
    return;
  scriptJquery(this).addClass('active');
  var value = 'Other';
  var text = scriptJquery(this).closest('.sescmads_ads_item_img').find('.sescomm_other').find('textarea').val();
  sendRequestReport(value,text,this);
})
function sendRequestReport(value,text,elem){
   scriptJquery(elem).closest('.sescmads_ads_item_img').hide();
   scriptJquery(elem).closest('.sescmads_ads_listing_item').find('.sescmads_hidden_ad').hide();
   scriptJquery(elem).closest('.sescmads_ads_listing_item').find('.sescmads_hidden_ad').hide();
   scriptJquery(elem).closest('.sescmads_ads_listing_item').find('.sescomm_report_success').show();
   scriptJquery(elem).closest('.sescmads_ads_listing_item').find('.sescomm_report_success').find('.loading_img').show();
   scriptJquery(elem).closest('.sescmads_ads_listing_item').find('.sescomm_report_success').find('.success_message').hide();
   var sescommunityad_id = scriptJquery(elem).closest('.sescmads_ads_listing_item').attr('rel');
   scriptJquery.post("sescommunityads/ajax/report",{value:value,text:text,sescommunityad_id:sescommunityad_id},function(re){
      scriptJquery(elem).closest('.sescmads_ads_listing_item').find('.sescomm_report_success').find('.loading_img').hide();
      scriptJquery(elem).closest('.sescmads_ads_listing_item').find('.sescomm_report_success').find('.success_message').show();
   });
}
scriptJquery(document).on('click','.sescomm_undo_ad',function(){
  scriptJquery(this).closest('.sescmads_ads_listing_item').find('.sescmads_ads_item_img').show();
  scriptJquery(this).closest('.sescmads_ads_listing_item').find('.sescmads_hidden_ad').hide();
  if(scriptJquery('.sescmads_bannerad_display')) {
    scriptJquery('.sescmads_bannerad_display').show();
    scriptJquery(this).closest('.sescmads_bannerad_item').find('.sescmads_hidden_ad').hide();
  }
});
scriptJquery(document).on('click','.sescomm_useful_ad',function(e){
  var selected = scriptJquery(this).hasClass('active');
  var selectedText = scriptJquery(this).attr('data-selected');
  var unselectedText = scriptJquery(this).attr('data-unselected');
  if(selected == true){
    scriptJquery(this).html(selectedText);
    scriptJquery(this).removeClass('active');
  }else{
    scriptJquery(this).html(unselectedText);
    scriptJquery(this).addClass('active');
  }
  var sescommunityad_id = scriptJquery(this).closest('.sescmads_ads_listing_item').attr('rel');
  scriptJquery.post("sescommunityads/ajax/useful",{sescommunityad_id:sescommunityad_id},function(re){
  });
});
function displayCommunityadsCarousel(){
  if(!scriptJquery('.sescmads_display_ad_carousel').length)
    return;
  var elem = sesowlJqueryObject('.sescmads_display_ad_carousel');
  elem.each(function(index){
    if(!sesowlJqueryObject(this).hasClass('.sescmads_display_ad_carousel_add')){
      sesowlJqueryObject(this).owlCarousel({
        nav : true,
        loop:false,
        items:1,
        navText: ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
      })
      scriptJquery(this).addClass('sescmads_display_ad_carousel_add');
    }
  });
}
