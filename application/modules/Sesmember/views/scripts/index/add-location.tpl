<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: add-location.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?>
<div class="sesmember_edit_location_popup"><?php echo $this->form->render($this) ?></div>
<script type="application/javascript">

var input = document.getElementById('ses_location');
  var autocomplete =  new google.maps.places.Autocomplete(input)
  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    var place = autocomplete.getPlace();
    if (!place.geometry)
    return;
    document.getElementById('ses_lng').value = lngGetOpn = place.geometry.location.lng();
    document.getElementById('ses_lat').value = latGetOpn = place.geometry.location.lat();
		myLatlng = new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());

		map.panTo(myLatlng);
		getLocationData(place.geometry.location.lat(),place.geometry.location.lng());
   
	});
	


function getLocationData(lat, lng){
	 var geocoder = new google.maps.Geocoder(); 
	 var city = state = country = postalCode = '';
    geocoder.geocode({'latLng': new google.maps.LatLng(lat, lng)}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK && results.length) {
				scriptJquery('#ses_location').val(results[0].formatted_address);
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
			scriptJquery('#ses_location').val('');
			scriptJquery('#ses_zip').val('');
			scriptJquery('#ses_city').val('');
			scriptJquery('#ses_state').val('');
			scriptJquery('#ses_country').val('');
		}
  });	
}
</script>
