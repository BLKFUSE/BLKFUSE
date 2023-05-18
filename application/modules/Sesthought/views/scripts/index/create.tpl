<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js');
?>
<script type="text/javascript">

  var checkingUrlMessage = '<?php echo $this->string()->escapeJavascript($this->translate('Checking URL...')) ?>';

  function showMediaType(value) {
  
    if(value == 1) {
      if(document.getElementById('photo-wrapper'))
        document.getElementById('photo-wrapper').style.display = 'block';
      if(document.getElementById('video-wrapper'))
        document.getElementById('video-wrapper').style.display = 'none';
      if(document.getElementById('video'))
        document.getElementById('video').value = '';
    } else if(value == 2) { 
      if(document.getElementById('photo-wrapper'))
        document.getElementById('photo-wrapper').style.display = 'none';
      if(document.getElementById('video-wrapper'))
        document.getElementById('video-wrapper').style.display = 'block';
    }
  }
  
  function iframelyurl() {
  
    var url_element = document.getElementById("video-element");
    var myElement = new Element("p");
    myElement.innerHTML = "test";
    myElement.addClass("description");
    myElement.id = "validation";
    myElement.style.display = "none";
    url_element.appendChild(myElement);
  
    var url = document.getElementById('video').value;
    if(url == '') {
      return false;
    }
    scriptJquery.ajax({
      dataType: 'json',
      'url' : '<?php echo $this->url(array('module' => 'sesthought', 'controller' => 'index', 'action' => 'get-iframely-information'), 'default', true) ?>',
      'data' : {
        'format': 'json',
        'uri' : url,
      },
      'onRequest' : function() {
        document.getElementById('validation').style.display = "block";
        document.getElementById('validation').innerHTML = checkingUrlMessage;
      },
      success : function(response) {
        if( response.valid ) {
          document.getElementById('validation').style.display = "block";
          document.getElementById('validation').innerHTML = "Your url is valid.";
        } else {
          document.getElementById('validation').style.display = "block";
          document.getElementById('validation').innerHTML = 'We could not find a video there - please check the URL and try again.';
        }
      }
    });
  }

  function showSubCategory(cat_id,selectedId) {
  
		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
    var url = en4.core.baseUrl + 'sesthought/category/subcategory/category_id/'+cat_id;
    scriptJquery.ajax({
      dataType: 'html',
      url: url,
      data: {
        'selected':selected
      },
      success: function(responseHTML) {
        if (formObj.find('#subcat_id-wrapper').length && responseHTML) {
          formObj.find('#subcat_id-wrapper').show();
          formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').html(responseHTML);
        } else {
          if (formObj.find('#subcat_id-wrapper').length) {
            formObj.find('#subcat_id-wrapper').hide();
            formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').html( '<option value="0"></option>');
          }
        }
			  if (formObj.find('#subsubcat_id-wrapper').length) {
          formObj.find('#subsubcat_id-wrapper').hide();
          formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
        }
      }
    }); 
  }
  
	function showSubSubCategory(cat_id,selectedId,isLoad) {

		if(cat_id == 0) {
			if (formObj.find('#subsubcat_id-wrapper').length) {
        formObj.find('#subsubcat_id-wrapper').hide();
        formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
      }
			return false;
		}

		var selected;
		if(selectedId != ''){
			var selected = selectedId;
		}
		
    var url = en4.core.baseUrl + 'sesthought/category/subsubcategory/subcategory_id/' + cat_id;
    (scriptJquery.ajax({
      dataType: 'html',
      url: url,
      data: {
				'selected':selected
      },
      success: function(responseHTML) {
        if (formObj.find('#subsubcat_id-wrapper').length && responseHTML) {
          formObj.find('#subsubcat_id-wrapper').show();
          formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html(responseHTML);
        } else {
          if (formObj.find('#subsubcat_id-wrapper').length) {
            formObj.find('#subsubcat_id-wrapper').hide();
            formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
          }
        }
			}
    }));  
  }

  en4.core.runonce.add(function() {
    showMediaType(1);
    formObj = scriptJquery('#sesthoughts_create').find('div').find('div').find('div');
    formObj.find('#subcat_id-wrapper').hide();
    formObj.find('#subsubcat_id-wrapper').hide();

    scriptJquery('#tags').selectize({
      maxItems: 10,
      valueField: 'label',
      labelField: 'label',
      searchField: 'label',
      create: true,
      load: function(query, callback) {
        if (!query.length) return callback();
        scriptJquery.ajax({
          url: '<?php echo $this->url(array('controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>',
          data: { value: query },
          success: function (transformed) {
            callback(transformed);
          },
          error: function () {
              callback([]);
          }
        });
      }
    });
  });
  
    
  function addThought(formObject) {
  
    var validationFmThought = validateThoughtForm();
    
    if(scriptJquery('#title').val() == '') {
      alert('<?php echo $this->string()->escapeJavascript("Please fill requried fields."); ?>');
      return false;
    }

    if(validationFmThought) {
      
      var input = scriptJquery(formObject);
      alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
      if(typeof objectError != 'undefined') {
        var errorFirstObject = scriptJquery(objectError).parent().parent();
          scriptJquery('html, body').animate({
          scrollTop: errorFirstObject.offset().top
        }, 2000);
      }

      return false;
    } else {
      submitThought(formObject);
    }
  }
  

  function submitThought(formObject) {
  
    scriptJquery('#sesthought_overlay').show();
    var formData = new FormData(formObject);
    formData.append('is_ajax', 1);
    scriptJquery.ajax({
      url: "sesthought/index/create/",
      type: "POST",
      contentType:false,
      processData: false,
      cache: false,
      data: formData,
      success: function(response) {
      
        var result = scriptJquery.parseJSON(response);
        if(result.status == 1) {
        
          scriptJquery('#sesthought_overlay').hide();
          scriptJquery('#sessmoothbox_container').html("<div id='sesthought_success_message' class='sesprofilefield_success_message sesthought_success_message'><i class='fa-check-circle-o'></i><span>You have successfully posted discussion.</span></div>");

          scriptJquery('#sesthought_success_message').fadeOut("slow", function(){
            setTimeout(function() {
              sessmoothboxclose();
              var url = '<?php echo $this->url(array('action' => 'manage'), 'sesthought_general', true); ?>';
              window.location.href = url;
              
            }, 1000);
          });
        }
      }
    });
  }
</script>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesthought/externals/styles/styles.css'); ?>
<div class="sesthought_create_popup sesbasic_bxs">
  <div class="sesbasic_loading_cont_overlay" id="sesthought_overlay"></div>
  <?php if(empty($this->is_ajax) ) { ?>
      <?php echo $this->form->render($this);?>
  <?php } ?>
</div>
<?php die; ?>
