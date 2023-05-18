<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: _formModtitle.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

		echo '
		<div id="subcategory_backgroundimage"> </div>
		<div id="title-wrapper" class="form-wrapper">
		<div id="title-label" class="form-label">
		<label for="title" class="required">'. ($this->translate("Select Content")). '</label>
		</div>
		<div id="title-element" class="form-element">
		<select name="title" id="title" onchange="resourceData(this.value)">

		</select>
		</div>
		</div>';

		$title = $this->translate('Example Ad Title');
		$body = $this->translate('Example ad body text.');
		?>

		<script type="text/javascript">

			window.addEvent('domready', function() {

				// var is_type = $('cmd_ad_type').value; 
				var is_format = $('cmd_ad_format').value; 
				var urlfields = $$(".ads_url");

				
					urlfields.each(function(i, field) {
						i.parentNode.parentNode.style.display = 'none';
					});

				$('web_name').disabled = 'disabled';
				$('web_url-wrapper').style.display = 'none'; 
				$('web_icon-wrapper').style.display = 'none'; 
				$('web_name-wrapper').style.display = 'none'; 
			    $('title-wrapper').style.display = 'none';
			    if($('is_edit_content').value != 0){
			    	$('title-wrapper').style.display = 'block';
			    }

	});

	
	var subcontent = function( module_type )
	{
		if( $('validation_subtitle') ) {
			$('title-element').removeChild($('validation_subtitle'));
		}

		if( $('validation_title') ) {
			$('create_feature-element').removeChild($('validation_title'));
		}

		if($('validation_subtitle')) {
			$('validation_subtitle').innerHTML = '';
		}
		$('title-wrapper').style.display = 'none';
		if( module_type != 0 ) {
			$('title-wrapper').style.display = 'block';
			$('subcategory_backgroundimage').style.display = 'block';
			$('title').style.display = 'none';
			$('title-label').style.display = 'none';
			$('subcategory_backgroundimage').innerHTML = '<div class="form-wrapper"><div class="form-label">&nbsp;</div><div class="form-element"><img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Seaocore/externals/images/core/loading.gif" /></div></div>';

			en4.core.request.send(new Request.JSON({
				url : en4.core.baseUrl + 'sitead/display/contenttype?resource_type=' + module_type,
				data : {
					format : 'json'
				},
				onSuccess : function(responseJSON) {
					$('subcategory_backgroundimage').style.display = 'none';
					clear('title');

					var  subcatss = responseJSON.resource_string;
					if( subcatss == '' ) {
						$('title-label').style.display = 'inline-block';
						if($('validation_subtitle')) {					
							$('validation_subtitle').innerHTML ="<?php echo $this->string()->escapeJavascript($this->translate('You have not created any content of this type.')); ?>";
						}else {
							var div_cads_body = document.getElementById("title-element");
							var myElement = new Element("p");
							myElement.innerHTML = "<?php echo $this->string()->escapeJavascript($this->translate("You have not created any content of this type.")); ?>";
							myElement.addClass("error");
							myElement.id = "validation_subtitle";
							div_cads_body.appendChild(myElement);
						}
						resetcontent();
					}else {
						if($('validation_subtitle')) {
							$('validation_subtitle').innerHTML = '';
						}
						// $('subtitle_string').value = subcatss;
						addOption($('title')," ", '0');
						for (i=0; i< subcatss.length; i++) {
							addOption($('title'), subcatss[i]['title'], subcatss[i]['id']);
						}
					}
					
				}
			}));
		}else {
			$('title-wrapper').style.display = 'none';
			resetcontent();
		}
	};

	function resetcontent()
	{
		
		$('web_name').value = ''; // set responce label for form submit title.
		$('web_url').value = 'http://'; // set responce page url for form submit url.
		$('ad_name').innerHTML = '<a href="">' + '<?php echo $this->string()->escapeJavascript($title) ?>' + '</a>'; // set responce title for preview title.
		$('ad_icon').innerHTML = '<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" alt=" " />'; // set responce photo for preview photo.
		// $('resource_image').value = ''; // set responce for Hidden element - image id
		$('resource_type').value = ''; // set responce for Hidden element - resource_type
		$('resource_id').value = ''; // set responce for Hidden element - resource_id
		$$(".ads_url").each(function(i, field) {
			i.value = '';
		});
	}

	var resourceData = function( resource_id )
	{ 

			if( $('validation_subtitle') ) {
				$('title-element').removeChild($('validation_subtitle'));
			}

			if($('validation_subtitle')) {
				$('validation_subtitle').innerHTML = '';
			}
		// Condition: If selection from 'Select Content' drop down is empty then reset all values. 
		if( resource_id != 0 )
		{
			// When select any content from drop down then show loder image in preview side.
			$('ad_name').innerHTML='<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/loader.gif" alt="">';
			$('ad_icon').innerHTML = '<img src="<?php echo $this->layout()->staticBaseUrl ?>application/modules/Sitead/externals/images/blankImage.png" alt=" " />';
			$('ad_icon').style.display='block';
			var resource_type = $('create_feature').value;
			en4.core.request.send(new Request.JSON({     	
				url : en4.core.baseUrl + 'sitead/display/resourcecontent?resource_type=' + resource_type + '&resource_id=' + resource_id,
				data : {
					format : 'json'
				},
				onSuccess : function(responseJSON) {
					$('web_name').addClass('disabled_title');
					$('web_name').value = responseJSON.title; // set responce label for form submit title.
					$('web_url').value = responseJSON.page_url;// set responce page url for form submit url.
					$('ad_name').innerHTML = responseJSON.preview_title; // set responce title for preview title.
                    $('content_title').value = responseJSON.title;
					$('ad_icon').innerHTML = responseJSON.photo; // set responce photo for preview photo.
					//$('resource_image').value = responseJSON.photo; // set responce for Hidden element - image id
					$('resource_type').value = responseJSON.resource_type; // set responce for Hidden element - resource_type
					$('resource_id').value = responseJSON.id; // set responce for Hidden element - resource_id
					$('content_page').value = 1;
					$('imageenable').value = 1;
					$('photo_id_filepath').value = responseJSON.photo_id_filepath;
					$$(".ads_url").each(function(i, field) {
						i.value = responseJSON.page_url;
					});

					if( $('validation_web_name') ) {
						$('web_name-element').removeChild($('validation_web_name'));
					}
					if( $('validation_web_icon') ) {
						$('web_icon-element').removeChild($('validation_web_icon'));
					}
				}
			}));
		}else {
			resetcontent();
		}
	}

	function clear(ddName)
	{
		for (var i = (document.getElementById(ddName).options.length-1); i >= 0; i--) 
		{ 
			document.getElementById(ddName).options[ i ]=null; 
		} 
	}


	function addOption( selectbox, text, value )
	{
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;

		if(optn.text != '' && optn.value != '') {
			$('title').style.display = 'block';
			$('title-label').style.display = 'inline-block';
			selectbox.options.add(optn);
		} else {
			$('title').style.display = 'none';
			$('title-label').style.display = 'none';
			selectbox.options.add(optn);
		}
	}
</script>