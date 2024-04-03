
scriptJquery(document).on('click','.sestutorial_like_sestutorial_tutorial',function(){
  like_data_sestutorial(this,'like','sestutorial_tutorial','sestutorial','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Tutorial Liked successfully"))+'</span>','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Tutorial Un-Liked successfully"))+'</span>','sesbasic_liked_notification', '');
});

//common function for like comment ajax
function like_data_sestutorial(element, functionName, itemType, moduleName, notificationType, classType) {
	if (!scriptJquery (element).attr('data-url'))
		return;
	var id = scriptJquery (element).attr('data-url');
	if (scriptJquery (element).hasClass('button_active')) {
		scriptJquery (element).removeClass('button_active');
	} else
		scriptJquery (element).addClass('button_active');
	(scriptJquery.ajax({
      dataType: 'html',
		method: 'post',
		'url': en4.core.baseUrl + 'sestutorial/index/' + functionName,
		'data': {
			format: 'html',
				id: scriptJquery (element).attr('data-url'),
        type: itemType,
		},
    success: function (responseHTML) {
			var response = jQuery.parseJSON(responseHTML);
			if (response.error)
				alert(en4.core.language.translate('Something went wrong,please try again later'));
			else {
				if(scriptJquery(element).hasClass('sestutorial_albumlike')){
					var elementCount = 	element;
				} 
				else if(scriptJquery(element).hasClass('sestutorial_photolike')){
					var elementCount = 	element;
				}
				else {
					var elementCount = '.sestutorial_like_sestutorial_tutorial_'+id;
				}
				scriptJquery (elementCount).find('span').html(response.count);
				if (response.condition == 'reduced') {
					if(classType == 'sestutorial_like_sestutorial_tutorial_view') {
						scriptJquery('.sestutorial_like_sestutorial_tutorial_view').html('<i class="fa fa-thumbs-up"></i><span>'+en4.core.language.translate("Like")+'</span>');
					}
					else {
						scriptJquery (elementCount).removeClass('button_active');
					}
				} 
				else {
					if(classType == 'sestutorial_like_sestutorial_tutorial_view') {
						scriptJquery('.sestutorial_like_sestutorial_tutorial_view').html('<i class="fa fa-thumbs-down"></i><span>'+en4.core.language.translate("UnLike")+'</span>');
					}
					else {
						scriptJquery (elementCount).addClass('button_active');
					}
				}
			}
			return true;
		}
	}));
}
