<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _customFields.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<script type="text/javascript">
//en4.core.runonce.add(function() {
  
  var topLevelId = '<?php echo sprintf('%d', (int) @$this->topLevelId) ?>';
  var topLevelValue = '<?php echo sprintf('%d', (int) @$this->topLevelValue) ?>';
  var elementCache = {};

  function getFieldsElements(selector) {
    if( selector in elementCache || $type(elementCache[selector]) ) {
      return elementCache[selector];
    } else {
      return elementCache[selector] = $$(selector);
    }
  }
  
  function updateFieldValue(element, value) {
    if( element.get('tag') == 'option' ) {
      element = element.getParent('select');
    } else if( element.get('type') == 'checkbox' || element.get('type') == 'radio' ) {
      element.set('checked', Boolean(value));
      return;
    }
    if (element.get('tag') == 'select') {
      if (element.get('multiple')) {
        element.getElements('option').each(function(subEl){
          subEl.set('selected', false);
        });
      }
    }
    if( element ) {
      element.set('value', value);
    }
  }
	var valueNotChanged;
  var changeFields = window.changeFields = function(element, force, isLoad,valueNotChanged,resets) {
   /* var categoryId = getProfileType($('category_id').value);
    if(categoryId == 0 || !categoryId){
      var subcatId = 0;
      var subsubcatId = 0;
      var valueNotChanged = categoryId+','+subcatId+','+subsubcatId;
      console.log(categoryId,subcatId,subsubcatId,valueNotChanged);
    }*/
    element = $(element);
    // We can call this without an argument to start with the top level fields
    if( !$type(element) ) {
      getFieldsElements('.parent_' + topLevelId).each(function(element) {
        changeFields(element, force, isLoad);
      });
      return;
    }
    // If this cannot have dependents, skip
    if( !$type(element) || !$type(element.onchange) ) {
      return;
    }
    // Get the input and params
    var field_id = element.get('class').match(/field_([\d]+)/i)[1];
    var parent_field_id = element.get('class').match(/parent_([\d]+)/i)[1];
    var parent_option_id = element.get('class').match(/option_([\d]+)/i)[1];
    if( !field_id || !parent_option_id || !parent_field_id ) {
      return;
    }
    force = ( $type(force) ? force : false );
    // Now look and see
    // Check for multi values
    var option_id = [];
    if( element.name.indexOf('[]') > 0 ) {
      if( element.type == 'checkbox' ) { // MultiCheckbox
        getFieldsElements('.field_' + field_id).each(function(multiEl) {
          if( multiEl.checked ) {
            option_id.push(multiEl.value);
          }
        });
      } else if( element.get('tag') == 'select' && element.multiple ) { // Multiselect
        element.getChildren().each(function(multiEl) {
          if( multiEl.selected ) {
            option_id.push(multiEl.value);
          }
        });
      }
    } else if( element.type == 'radio' ) {
      if( element.checked ) {
        option_id = [element.value];
      }
    } else {
      option_id = [element.value];
    }
    var executed = false;
    // Iterate over children
   
    getFieldsElements('.parent_' + field_id).each(function(childElement) {
      var childContainer;
      if( childElement.getParent('form').get('class') == 'field_search_criteria' ) {
        childContainer = $try(function(){ return childElement.getParent('li').getParent('li'); });
      }
      if( !childContainer ) {
         childContainer = childElement.getParent('div.form-wrapper');
      }
      if( !childContainer ) {
        childContainer = childElement.getParent('div.form-wrapper-heading');
      }
      if( !childContainer ) {
        childContainer = childElement.getParent('li');
      }
      var childOptions = childElement.get('class').match(/option_([\d]+)/gi);
      for(var i = 0; i < childOptions.length; i++) {
        for(var j = 0; j < option_id.length; j++) {
          if(childOptions[i] == "option_" + option_id[j]) {
            var childOptionId = option_id[j];
            break;
          }
        }
      }
       
      var childIsVisible = ( 'none' != childContainer.getStyle('display') );
      var skipPropagation = false;
      // Forcing hide
   
			if(isLoad != 'yes'){
        
				if(typeof valueNotChanged == 'string' || typeof valueNotChanged == 'number'){
				if(typeof valueNotChanged == 'number')
					var valueId = [valueNotChanged];
				else
					var valueId = valueNotChanged.split(',');
					for(var i =0 ; i<valueId.length;i++){           
						if(scriptJquery(childElement).hasClass('option_'+valueId[i])){
              if(scriptJquery(childElement).parent().hasClass('form-wrapper-heading')){
                scriptJquery(childElement).parent().show();
              }else{
								  scriptJquery(childElement).closest('div').parent().css('display','block');
              }
              executed = true;
              updateFieldsProfileSES(childElement);
						  return;
						}	
					}
				}else if(scriptJquery(childElement).hasClass('option_'+valueNotChanged)){
					if(scriptJquery(childElement).hasClass('option_'+valueId[i])){
              if(scriptJquery(childElement).parent().hasClass('form-wrapper-heading'))
                scriptJquery(childElement).parent().show();
              else{
								scriptJquery(childElement).closest('div').parent().css('display','block');
              }
							executed = true;
              updateFieldsProfileSES(childElement);
              return;
						}	
					//return;
				}
			}
     
      if(scriptJquery(element).attr('id') == '0_0_1')
        updateFieldsProfileSES(childElement,resets);
       //if(!executed){
         
          if(scriptJquery(childElement).parent().hasClass('form-wrapper-heading')){
            scriptJquery(childElement).parent().hide();
          }else{
            scriptJquery(childElement).closest('div').parent().css('display','none');
          }
          
          updateFieldValue(childElement, null,valueNotChanged);	
               
         // return;
       //}
    });
    if(scriptJquery(element).attr('id') != '0_0_1'){
      updateFieldsProfileSES(element,resets);
    }
    window.fireEvent('onChangeFields');
  }
  //changeFields(null, null, 'yes');
 function updateFieldsProfileSES(childElement,resets){
   if(typeof resets != 'undefined')
     updateFieldValue(childElement,null);
     var field_id_child =  childElement.get('class').match(/field_([\d]+)/i)[1];
      if(typeof childElement.name != 'undefined' && childElement.name.indexOf('[]') > 0 ) {
        var checked = scriptJquery('.field_' + field_id_child).is(':checked');
        if( childElement.type == 'checkbox' ) {
         scriptJquery("input[name='"+scriptJquery(childElement).attr('name')+"']").each(function(index, element) {
           // MultiCheckbox        
           var checked = scriptJquery(this).is(':checked');
          if(scriptJquery('.parent_'+field_id_child).parent().hasClass('form-wrapper-heading')){
            if(!checked)
              scriptJquery('.parent_'+field_id_child).parent().hide();
            else
              scriptJquery('.option_'+scriptJquery(childElement).val()+'.parent_'+field_id_child).parent().show();
          }else{
              var elemClass =  scriptJquery(childElement).attr('class');
              if(typeof elemClass != 'undefined'){
                var fieldId = elemClass.match(/field_([\d]+)/i)[1];
                if(scriptJquery('.parent_'+fieldId).parent().hasClass('form-wrapper-heading')){
                  if(!checked)
                    scriptJquery('.parent_'+fieldId).parent().hide();
                  else
                    scriptJquery('.parent_'+fieldId).parent().show();
                }else{
                   if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val())){
                    scriptJquery('.option_'+scriptJquery(this).val()+'.parent_'+fieldId).closest('div').parent().each(function(){
                      scriptJquery(this).css('display','none');
                      updateFieldValue(scriptJquery(this)[0],null);
                      if(scriptJquery('.parent_'+field_id_child).length){
                        scriptJquery('.parent_'+field_id_child).each(function(){
                          updateFieldsProfileSES(scriptJquery(this)[0],resets);
                        })
                      }else
                        return;
                    })
                   }else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val())){
                    scriptJquery('.option_'+scriptJquery(this).val()+'.parent_'+fieldId).closest('div').parent().show();
                   }
                }
              }
          }
         });
        } else if( childElement.get('tag') == 'select' && childElement.multiple ) { // Multiselect
           scriptJquery(childElement).find("option").each(function(index, item) {
            var checked = scriptJquery(item).is(':selected');
            if(scriptJquery('.parent_'+field_id_child).parent().hasClass('form-wrapper-heading')){
               if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_0')){
                scriptJquery('.parent_'+field_id_child).parent().hide();
               }
              else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(childElement).val()))
                scriptJquery('.option_'+scriptJquery(childElement).val()+'.parent_'+field_id_child).parent().show();
            }else{
                var elemClass =  scriptJquery(childElement).attr('class');
                if(typeof elemClass != 'undefined'){
                  var fieldId = elemClass.match(/field_([\d]+)/i)[1];
                    if(scriptJquery('.parent_'+fieldId).parent().hasClass('form-wrapper-heading')){
                      if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_0')){
                        scriptJquery('.parent_'+fieldId).parent().hide();
                      }
                      else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val()))
                        scriptJquery('.parent_'+fieldId).parent().show();
                    }else{
                     if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val())){
                      scriptJquery('.option_'+scriptJquery(this).val()+'.parent_'+fieldId).closest('div').parent().each(function(){
                        scriptJquery(this).css('display','none');
                        updateFieldValue(scriptJquery(this)[0],null);
                        if(scriptJquery('.parent_'+field_id_child).length){
                          scriptJquery('.parent_'+field_id_child).each(function(){
                            updateFieldsProfileSES(scriptJquery(this)[0],resets);
                          })
                        }else
                          return;
                      })
                     }else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val())){
                      scriptJquery('.option_'+scriptJquery(this).val()+'.parent_'+fieldId).closest('div').parent().show();
                     }
                    }
                  }
                }
          });
        }
      } else if( childElement.type == 'radio' ) {
       scriptJquery('input[name='+scriptJquery(childElement).attr('name')+']').each(function(index, item){
        var checked = scriptJquery(item).is(':checked');
        if(scriptJquery('.parent_'+field_id_child).parent().hasClass('form-wrapper-heading')){
            if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_0'))
              scriptJquery('.parent_'+field_id_child).parent().hide();
            else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(childElement).val()))
              scriptJquery('.option_'+scriptJquery(childElement).val()+'.parent_'+field_id_child).parent().show();
          }else{
              var elemClass =  scriptJquery(childElement).attr('class');
              if(typeof elemClass != 'undefined'){
                var fieldId = elemClass.match(/field_([\d]+)/i)[1];
                if(scriptJquery('.parent_'+fieldId).parent().hasClass('form-wrapper-heading')){
                   if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_0'))
                    scriptJquery('.parent_'+fieldId).parent().hide();
                  else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val()))
                    scriptJquery('.parent_'+fieldId).parent().show();
                }else{
                  if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val())){
                    scriptJquery('.option_'+scriptJquery(this).val()+'.parent_'+fieldId).closest('div').parent().each(function(){
                      scriptJquery(this).css('display','none');
                     updateFieldValue(scriptJquery(this)[0],null);
                      if(scriptJquery('.parent_'+field_id_child).length){
                        scriptJquery('.parent_'+field_id_child).each(function(){
                          updateFieldsProfileSES(scriptJquery(this)[0],resets);
                        })
                      }else
                        return;
                    })
                   }else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val())){
                    scriptJquery('.option_'+scriptJquery(this).val()+'.parent_'+fieldId).closest('div').parent().show();
                   }
                }
              }
              
          }
         }); 
      }else if(childElement.type == 'select-one'){
        scriptJquery('#'+scriptJquery(childElement).attr('id')+' option').each(function() {
         
          var checked = scriptJquery(this).is(':selected');
          if(scriptJquery('.parent_'+field_id_child).parent().hasClass('form-wrapper-heading')){
            if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_0')){
              scriptJquery('.parent_'+field_id_child).parent().hide();
            }
            else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(childElement).val())){
              scriptJquery('.option_'+scriptJquery(childElement).val()+'.parent_'+field_id_child).parent().show();
            }
          }else{
              var elemClass =  scriptJquery(childElement).attr('class');
              if(typeof elemClass != 'undefined'){
                var fieldId = elemClass.match(/field_([\d]+)/i)[1];
                if(scriptJquery('.parent_'+fieldId).parent().hasClass('form-wrapper-heading')){
                  if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_0')){
                   scriptJquery('.parent_'+fieldId).parent().hide();
                  }
                 else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val()))
                    scriptJquery('.parent_'+fieldId).parent().show();
                }else{
                   if(!checked && scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val())){
                    scriptJquery('.option_'+scriptJquery(this).val()+'.parent_'+fieldId).closest('div').parent().each(function(){
                      scriptJquery(this).css('display','none');
                      updateFieldValue(scriptJquery(this)[0],null);
                      if(scriptJquery('.parent_'+field_id_child).length){
                        scriptJquery('.parent_'+field_id_child).each(function(){
                          updateFieldsProfileSES(scriptJquery(this)[0],resets);
                        })
                      }else
                        return;
                    })
                   }else if(scriptJquery('.parent_'+field_id_child).hasClass('option_'+scriptJquery(this).val())){
                    scriptJquery('.option_'+scriptJquery(this).val()+'.parent_'+fieldId).closest('div').parent().show();
                   }
                }                
              }
            }
        });
      }else{
         if(scriptJquery('.parent_'+field_id_child).parent().hasClass('form-wrapper-heading')){
            scriptJquery('.parent_'+field_id_child).parent().hide();
          }else{
              var elemClass =  scriptJquery('.parent_'+field_id_child).attr('class');
              if(typeof elemClass != 'undefined'){
                var fieldId = elemClass.match(/field_([\d]+)/i)[1];
                if(scriptJquery('.parent_'+fieldId).parent().hasClass('form-wrapper-heading')){
                  scriptJquery('.parent_'+fieldId).parent().hide();
                }else{
                  updateFieldValue(scriptJquery('.parent_'+fieldId)[0],null);
                  scriptJquery('.parent_'+fieldId).closest('div').parent().css('display','none');
                }
              }
               scriptJquery('.parent_'+field_id_child).val('');
              scriptJquery('.parent_'+field_id_child).closest('div').parent().css('display','none');
          }        
      }
    }
//});
</script>
