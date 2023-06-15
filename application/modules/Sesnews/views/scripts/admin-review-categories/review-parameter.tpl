<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: review-parameter.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>


<div class='clear'>
  <div class='settings global_form_popup sesnews_review_parameter_popup'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>

<script type="application/javascript">
var alreadyaddedParameter = scriptJquery('.sesnews_review_added_parameter');
if(alreadyaddedParameter.length > 0){
	for(var i=0;i<alreadyaddedParameter.length;i++){
		var id = scriptJquery(alreadyaddedParameter[i]).attr('id').replace('sesnews_review_','');
		scriptJquery(alreadyaddedParameter[i]).parent().append('<a href="javascript:;" data-url="'+id+'" class="removeAlreadyAddedElem fa fa-trash">Remove</a>');
	}
}
scriptJquery(document).on('click','.removeAlreadyAddedElem',function(e){
	var id = scriptJquery(this).attr('data-url');
	var val = scriptJquery('#deletedIds').val();
	if(val)
		var oldVal = val+',';
	else
		var oldVal = '';
	scriptJquery('#deletedIds').val(oldVal+id);
	scriptJquery(this).parent().parent().remove();
});
scriptJquery(document).on('click','#addmoreelem',function(e){
	scriptJquery('<div><input type="text" name="parameters[]" value="" class="reviewparameter"><a href="javascript:;" class="removeAddedElem fa fa-trash">Remove</a></div>').insertBefore(this);
});
scriptJquery(document).on('click','.removeAddedElem',function(e){
	scriptJquery(this).parent().remove();
});
</script>
