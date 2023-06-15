<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Fields
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: _jsSwitch.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>

<script type="text/javascript">

scriptJquery().ready(function(){
  
  var topLevelId = '<?php echo sprintf('%d', (int) @$this->topLevelId) ?>';
  var topLevelValue = '<?php echo sprintf('%d', (int) @$this->topLevelValue) ?>';
  var elementCache = {};
  var fieldAjaxUrl = scriptJquery("#field_ajax_url").val();
  var fieldEnabledAjaxLoad = scriptJquery("#enable_ajax_load").val();
  function getFieldsElements(selector) {
    if( selector in elementCache || $type(elementCache[selector]) ) {
      return elementCache[selector];
    } else {
      return elementCache[selector] = scriptJquery(selector);
    }
  }
  
  function updateFieldValue(element, value) {
    if( element.prop('tagName').toLowerCase() == 'option' ) {
      element = element.parents('select:first');
    } else if(element.attr('type') == 'checkbox' || element.attr('type') == 'radio' ) {
      element.prop('checked', Boolean(value));
      return;
    }
    if (element.prop("tagName") == 'SELECT') {
      if (element.attr('multiple')) {
        element.find('option').each(function(subEl){
          subEl.prop('selected', false);
        });
      }
    }
    if( element ) {
      element.val(value);
    }
  }

  var changeFields = window.changeFields = function(element, force, isLoad,topLevelId) {
    element = scriptJquery(element);
    // We can call this without an argument to start with the top level fields
    if( !element.length) {
      scriptJquery('.parent_' + topLevelId+'[type=select]:selected,.parent_' + topLevelId+'[type=radio]:checked').each(function(element) {
        element = scriptJquery(this);
        let parent_field_id = element.attr('class').match(/option_([\d]+)/i)[1];
        changeFields(element, force, isLoad,parent_field_id);
      });
      return;
    }
    // If this cannot have dependents, skip
    if( !$type(element) || !$type(element.attr("onchange")) ) {
      return;
    }
    // Get the input and params
    var field_id = element.attr('class').match(/field_([\d]+)/i)[1];
    var parent_field_id = element.attr('class').match(/parent_([\d]+)/i)[1];
    var parent_option_id = element.attr('class').match(/option_([\d]+)/i)[1];

    //console.log(field_id, parent_field_id, parent_option_id);

    if( !field_id || !parent_option_id || !parent_field_id ) {
      return;
    }

    force = ( $type(force) ? force : false );
    var eleWrapper = element.closest(".form-wrapper");
    var childrens = eleWrapper.attr("data-childrens");
    if(childrens && fieldEnabledAjaxLoad){
      var guid = eleWrapper.attr("data-guid");
      childrens = JSON.parse(childrens);
      if(childrens.includes(parseInt(element.val())) && !element.hasClass("children_loaded")){
        loadChild({
          parent_field_id : parseInt(element.attr("data-field-id")),
          parent_option_id : parseInt(element.val()),
          guid : guid
        }).then(data=>{
          if(!element.hasClass("children_loaded")){
            element.addClass("children_loaded");
            scriptJquery(data).insertAfter(eleWrapper);
          }
        });
      }
    }
    
    // Now look and see
    // Check for multi values
    var option_id = [];
    var isRadio = true;
    if( $type(element.attr("name")) && element.attr("name").indexOf('[]') > 0 ) {
      if(element.attr("type") == 'checkbox' ) { // MultiCheckbox
        scriptJquery('.field_' + field_id).each(function(multiEl) {
          multiEl = scriptJquery(this);
          if( multiEl.prop('checked')) {
            option_id.push(multiEl.val());
          }
        });
      } else if( element.prop("tagName") == 'SELECT' && element.attr("multiple") ) { // Multiselect
        element.children().each(function(multiEl) {
          if(element.prop('selected')) {
            option_id.push(this.value);
          }
        });
      }
    } else if( element.attr("type") == 'radio' ) {
      if(element.prop('checked')) {
        option_id = [element[0].value];
      } else {
        isRadio = false;
      }
    } else {
      option_id = [element[0].value];
    }

    // Iterate over children
    scriptJquery('.parent_' + field_id).each(function(childElement) {
      childElement = scriptJquery(this);
      var childContainer;
      if(childElement.parents('form').hasClass('field_search_criteria')) {
        childContainer = (childElement.parents('li:first').parents('li:first').length || childElement.parents('li:first')) || (childElement.parents('li:first').parents('li:first'));
      }
      if(!childContainer ) {
         childContainer = childElement.parents('div.form-wrapper:first');
      }
      if( !childContainer ) {
        childContainer = childElement.parents('div.form-wrapper-heading:first');
      }
      if( !childContainer ) {
        childContainer = childElement.parents('li:first');
      }
      //console.log(option_id);
      //var childLabel = childContainer.getElement('label');
      var childOptions = childElement.attr('class').match(/option_([\d]+)/gi);
      for(var i = 0; i < childOptions.length; i++) {
        for(var j = 0; j < option_id.length; j++) {
          if(childOptions[i] == "option_" + option_id[j]) {
            var childOptionId = option_id[j];
            break;
          }
        }
      }

      //var childOptionId = childElement.get('class').match(/option_([\d]+)/i)[1];
      var childIsVisible = ( 'none' != childContainer.css('display') );
      var skipPropagation = false;
      //var childFieldId = childElement.get('class').match(/field_([\d]+)/i)[1];

      // Forcing hide
      var nextForce;
      if( force == 'hide' && !option_id.includes(childOptionId)) {
        if( !childElement.hasClass('field_toggle_nohide') ) {
          childContainer.css('display', 'none');
          if( !isLoad ) {
            updateFieldValue(childElement, null);
          }
        }
        nextForce = force;
      } else if( force == 'show' ) {
        childContainer.css('display', '');
        nextForce = force;
      } else if( !$type(option_id) == 'array' || !option_id.includes(childOptionId) ) {
        // Hide fields not tied to the current option (but propogate hiding)
        if( !childElement.hasClass('field_toggle_nohide') && isRadio) {
          childContainer.css('display', 'none');
          if( !isLoad ) {
            updateFieldValue(childElement, null);
          }
        }
        nextForce = 'hide';
        if( !childIsVisible ) {
          skipPropagation = true;
        }
      } else {
        // Otherwise show field and propogate (nothing, show?)
        childContainer.css('display', '');
        nextForce = undefined;
        //if( childIsVisible ) {
        //  skipPropagation = true;
        //}
      }

      if( !skipPropagation ) {
        changeFields(childElement, nextForce, isLoad);
      }
    });
    scriptJquery(window).trigger('onChangeFields');
  }
  function loadChild(params){
    return scriptJquery.ajax({
            url: fieldAjaxUrl,
            async: false,
            type: "GET",
            data : params,
          });
  } 
  changeFields(null, null, true,topLevelId);
});

</script>
