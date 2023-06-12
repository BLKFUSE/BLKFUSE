<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: edit-location.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<div class="headline">
  <h2>
    <?php if ($this->viewer->isSelf($this->user)):?>
      <?php echo $this->translate('Edit My Profile');?>
    <?php else:?>
      <?php echo $this->translate('%1$s\'s Profile', $this->htmlLink($this->user->getHref(), $this->user->getTitle()));?>
    <?php endif;?>
  </h2>
  <div class="tabs">
    <?php
      // Render the menu
      echo $this->navigation()
        ->menu()
        ->setContainer($this->navigation)
        ->render();
    ?>
  </div>
</div>
<?php 
$enableglocation = Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', '0');
$optionsenableglotion = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('optionsenableglotion','')); 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?>
<div class="sesmember_edit_location_form sesbasic_bxs sesbasic_clearfix"><?php echo $this->form->render($this) ?></div>
<script type="application/javascript">
en4.core.runonce.add(function() {
  <?php if(!empty($optionsenableglotion) && empty($enableglocation)) { ?>
    <?php if(!engine_in_array('country', $optionsenableglotion)) { ?>
      scriptJquery('#ses_country-wrapper').hide();
    <?php } ?>
    <?php if(!engine_in_array('state', $optionsenableglotion)) { ?>
      scriptJquery('#ses_state-wrapper').hide();
    <?php } ?>
    <?php if(!engine_in_array('city', $optionsenableglotion)) { ?>
      scriptJquery('#ses_city-wrapper').hide();
    <?php } ?>  
    <?php if(!engine_in_array('zip', $optionsenableglotion)) { ?>
      scriptJquery('#ses_zip-wrapper').hide();
    <?php } ?>
    <?php if(!engine_in_array('lat', $optionsenableglotion)) { ?>
      scriptJquery('#ses_lat-wrapper').hide();
    <?php } ?>
    <?php if(!engine_in_array('lng', $optionsenableglotion)) { ?>
      scriptJquery('#ses_lng-wrapper').hide();
    <?php } ?>
  <?php } ?>
});

var input = document.getElementById('ses_edit_location');
  var autocomplete =  new google.maps.places.Autocomplete(input)
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    var place = autocomplete.getPlace();
    if (!place.geometry)
    return;
    document.getElementById('ses_lng').value = lngGetOpn = place.geometry.location.lng();
    document.getElementById('ses_lat').value = latGetOpn = place.geometry.location.lat();
		myLatlng = new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
		marker.setPosition(myLatlng);
		map.panTo(myLatlng);
		getLocationData(place.geometry.location.lat(),place.geometry.location.lng());
   
	});
	
	 var latlng = new google.maps.LatLng(<?php echo $this->locationLatLng->lat; ?>,<?php echo $this->locationLatLng->lng; ?>);
	 map = new google.maps.Map(document.getElementById('locationSesEdit'), {
						zoom: 14,
						center: latlng,
						mapTypeId: google.maps.MapTypeId.ROADMAP
				 });
	var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        zoom:25,
        title: 'Current Location',
        draggable:true,
});

google.maps.event.addListener(marker,'drag',function(event) {
    document.getElementById('ses_lat').value = event.latLng.lat();
    document.getElementById('ses_lng').value = event.latLng.lng();
});
var lngGetOpn = '<?php echo $this->locationLatLng->lng; ?>';
var latGetOpn = '<?php echo $this->locationLatLng->lat; ?>';
google.maps.event.addListener(marker,'dragend',function(event) 
  {
    document.getElementById('ses_lat').value = latGetOpn =  event.latLng.lat();
    document.getElementById('ses_lng').value = lngGetOpn = event.latLng.lng();
		myLatlng = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
		getLocationData(event.latLng.lat(),event.latLng.lng());
});
var myLatlng = new google.maps.LatLng(<?php echo $this->locationLatLng->lat; ?>,<?php echo $this->locationLatLng->lng; ?>);
google.maps.event.addListener(marker, 'click', function() {
    var infowindow = new google.maps.InfoWindow({
        content: 'Latitude: ' + latGetOpn + '<br>Longitude: ' + lngGetOpn
      });
    infowindow.open(map,marker);
});
function getLocationData(lat, lng){
	 var geocoder = new google.maps.Geocoder(); 
	 var city = state = country = postalCode = '';
    geocoder.geocode({'latLng': new google.maps.LatLng(lat, lng)}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK && results.length) {
				//scriptJquery('#ses_edit_location').val(results[0].formatted_address);
			if (results[0]) {
				if(typeof results[0].address_components != 'undefined'){
					for(var i=0; i<results[0].address_components.length; i++) {
						if(results[0].address_components[i].types[0] == 'postal_code') {
	var postalCode = results[0].address_components[i].long_name;
}
					}
				}
			}
			if (results[1]) {
				var indice=0;
				for (var j=0; j<results.length; j++)
				{
						if (results[j].types[0]=='locality')
						{
								indice=j;
								break;
						}
				}
				if(typeof results[j].address_components != 'undefined'){
					for (var i=0; i<results[j].address_components.length; i++) {
						if (results[j].address_components[i].types[0] == "locality") {
							//this is the object you are looking for
							city = results[j].address_components[i].long_name;
						}
						if (results[j].address_components[i].types[0] == "administrative_area_level_1") {
							//this is the object you are looking for
							state = results[j].address_components[i].long_name;
						}
						if (results[j].address_components[i].types[0] == "country") {
							//this is the object you are looking for
							country = results[j].address_components[i].long_name;
						}
					}
				}
				if(postalCode)
					scriptJquery('#ses_zip').val(postalCode);
				else
					scriptJquery('#ses_zip').val('');
				if(city)
					scriptJquery('#ses_city').val(city);
				else
					scriptJquery('#ses_city').val('');
				if(state)
				 scriptJquery('#ses_state').val(state);
				else
					scriptJquery('#ses_state').val('');
				if(country)
				 scriptJquery('#ses_country').val(country);
				else
					scriptJquery('#ses_country').val('');
			}
		} else{
			scriptJquery('#ses_edit_location').val('');
			scriptJquery('#ses_zip').val('');
			scriptJquery('#ses_city').val('');
			scriptJquery('#ses_state').val('');
			scriptJquery('#ses_country').val('');
		}
  });	
}
</script>
