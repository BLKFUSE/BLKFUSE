scriptJquery(document).on('click','.sesthought_likefavfollow',function(){
	sesthought_likefavourite_data(this,'sesthought_likefavfollow');
});

scriptJquery(document).on('submit', '#sesthoughts_create', function(e) {
  e.preventDefault();
  addThought(this);
});

//Ajax error show before form submit
var error = false;
var objectError ;
var counter = 0;
function validateThoughtForm() {
  
  var errorPresent = false; 
  scriptJquery('.sesthought_create_form input, .sesthought_create_form select, .sesthought_create_form checkbox, .sesthought_create_form textarea, .sesthought_create_form radio').each(
    function(index){
      var input = scriptJquery(this);
      if(scriptJquery(this).closest('div').parent().css('display') != 'none' && scriptJquery(this).closest('div').parent().find('.form-label').find('label').first().hasClass('required') && scriptJquery(this).prop('type') != 'hidden' && scriptJquery(this).closest('div').parent().attr('class') != 'form-elements'){	
      if(scriptJquery(this).prop('type') == 'checkbox'){
        value = '';
        if(scriptJquery('input[name="'+scriptJquery(this).attr('name')+'"]:checked').length > 0) { 
          value = 1;
        };
        if(value == '')
        error = true;
        else
        error = false;
      }
      else if(scriptJquery(this).prop('type') == 'select-multiple'){
        if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
        error = true;
        else
        error = false;
      }
      else if(scriptJquery(this).prop('type') == 'select-one' || scriptJquery(this).prop('type') == 'select' ){
        if(scriptJquery(this).val() === '')
        error = true;
        else
        error = false;
      }
      else if(scriptJquery(this).prop('type') == 'radio'){
        if(scriptJquery("input[name='"+scriptJquery(this).attr('name').replace('[]','')+"']:checked").val() === '')
        error = true;
        else
        error = false;
      }
      else if(scriptJquery(this).prop('type') == 'textarea' && scriptJquery(this).prop('id') == 'body'){
        if(tinyMCE.get('body').getContent() === '' || tinyMCE.get('body').getContent() == null)
        error = true;
        else
        error = false;
      }
      else if(scriptJquery(this).prop('type') == 'textarea') {
        if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
        error = true;
        else
        error = false;
      }
      else{
        if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
        error = true;
        else
        error = false;
      }
      if(error)
        errorPresent = true;
        error = false;
      }
    }
  );
  return errorPresent ;
}


//common function for like comment ajax
function sesthought_likefavourite_data(element) {
    if (!scriptJquery(element).attr('data-type'))
		return;
    var clickType = scriptJquery(element).attr('data-type');
    var functionName;
    var itemType;
    var contentId;
    var classType;
    var canIntegrate = 0;
    if(clickType == 'like_entry_view') {
      canIntegrate = scriptJquery(element).attr('data-integrate');
      functionName = 'like';
      itemType = 'sesthought_participant';
      var contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sesthought_entry_like_'+contentId;
      if(scriptJquery(elementId).hasClass('button_active')) {
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())-1);
      }
      else {
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())+1);
      }
    }

    else if(clickType == 'like_view') {
      functionName = 'like';
      itemType = 'sesthought_thought';
      var contentId = scriptJquery(element).attr('data-url');
      var elementId = '.sesthought_like_'+contentId;
      if(scriptJquery(elementId).hasClass('button_active')) {
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())-1);
      }
      else {
        scriptJquery(elementId).find('span').html(parseInt(scriptJquery(elementId).find('span').html())+1);
      }
    }


    if (!scriptJquery(element).attr('data-url'))
      return;
      
    if (scriptJquery(element).hasClass('button_active')) {
      scriptJquery(element).removeClass('button_active');
    } else
      scriptJquery(element).addClass('button_active');
    
    (scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + 'sesthought/index/' + functionName,
      'data': {
            format: 'html',
            id: contentId,
            type: itemType,
            integration:canIntegrate,
      },
      success: function(responseHTML) {
        var response = jQuery.parseJSON(responseHTML);
        if (response.error)
          alert(en4.core.language.translate('Something went wrong,please try again later'));
        else {
                scriptJquery(elementId).find('span').html(response.count);
                if (response.condition == 'reduced') {
                  scriptJquery(elementId).removeClass('button_active');
                } 
                else {
                  scriptJquery (elementId).addClass('button_active');
                }
        }
              if(canIntegrate == 1 && response.vote_status) {
                scriptJquery('#sesthought_vote_button_'+contentId).html('<i class="fa fa-hand-o-up"></i><span>Voted</span>');
                scriptJquery('#sesthought_vote_button_'+contentId).addClass('disable');
              }
        return true;
      }
    }));
}
