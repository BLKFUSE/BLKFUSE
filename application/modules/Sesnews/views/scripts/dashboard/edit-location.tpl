<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit-location.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php //echo $this->partial('dashboard/left-bar.tpl', 'sesnews', array('news' => $this->news));	?>
<?php if(!$this->is_ajax) {
  echo $this->partial('dashboard/left-bar.tpl', 'sesnews', array('news' => $this->news));	
?>
<div class="sesnews_dashboard_content sesbm sesbasic_clearfix">
<?php }  ?>
	
  <div class="sesnews_edit_location_form sesbasic_dashboard_form sesbm sesbasic_clearfix sesbasic_bxs"><?php echo $this->form->render($this) ?></div>
  
<?php if(!$this->is_ajax) { ?>
	</div>
  </div>
<?php } ?>

<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
<script type="application/javascript">

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
				scriptJquery('#ses_edit_location').val(results[0].formatted_address);
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
en4.core.runonce.add(function() { 
  scriptJquery('#mapcanvasdiv-wrapper').hide();
});
</script>
<?php } else { ?>
<?php $optionsenableglotion = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('optionsenableglotion','')); ?>
<script type="application/javascript">
  en4.core.runonce.add(function() {
      scriptJquery('#mapcanvasdiv-wrapper').hide();
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
      <?php } else { ?>
        scriptJquery('#ses_lat-wrapper').show();
      <?php } ?>
      <?php if(!engine_in_array('lng', $optionsenableglotion)) { ?>
        scriptJquery('#ses_lng-wrapper').hide();
      <?php } else { ?>
        scriptJquery('#ses_lng-wrapper').show();
      <?php } ?>
  });
</script>
<?php } ?>
