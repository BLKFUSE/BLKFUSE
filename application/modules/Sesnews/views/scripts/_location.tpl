<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _location.tpl  2018-04-23 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php
$enableglocation = Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1);
$optionsenableglotion = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('optionsenableglotion','')); ?>
<?php $location = isset($_POST['location']) ? $_POST['location'] : (isset($this->news) && !empty($this->news->location) ? $this->news->location : '') ; ?>
<?php $venue_name = isset($_POST['venue_name']) ? $_POST['venue_name'] : (isset($this->itemlocation) && !empty($this->itemlocation->venue) ? $this->itemlocation->venue : '') ; ?>
<?php $lat = isset($_POST['lat']) ? $_POST['lat'] : (isset($this->itemlocation) && !empty($this->itemlocation->lat) ? $this->itemlocation->lat : '') ; ?>
<?php $lng = isset($_POST['lng']) ? $_POST['lng'] : (isset($this->itemlocation) && !empty($this->itemlocation->lng) ? $this->itemlocation->lng : '') ; ?>
<?php $address = isset($_POST['address']) ? $_POST['address'] : (isset($this->itemlocation) && !empty($this->itemlocation->address) ? $this->itemlocation->address : '') ; ?>
<?php $address2 = isset($_POST['address2']) ? $_POST['address2'] : (isset($this->itemlocation) && !empty($this->itemlocation->address2) ? $this->itemlocation->address2 : '') ; ?>
<?php $city = isset($_POST['city']) ? $_POST['city'] : (isset($this->itemlocation) && !empty($this->itemlocation->city) ? $this->itemlocation->city : '') ; ?>
<?php $state = isset($_POST['state']) ? $_POST['state'] : (isset($this->itemlocation) && !empty($this->itemlocation->state) ? $this->itemlocation->state : '') ; ?>
<?php $country = isset($_POST['country']) ? $_POST['country'] : (isset($this->itemlocation) && !empty($this->itemlocation->country) ? $this->itemlocation->country : '') ; ?>
<?php $zip = isset($_POST['zip']) ? $_POST['zip'] : (isset($this->itemlocation) && !empty($this->itemlocation->zip) ? $this->itemlocation->zip : '') ; ?>
<script>var checkinD = false;</script>
<div id="seslocation-wrapper" class="form-wrapper">
    <div id="location-label" class="form-label">
      <label for="locationSes"><?php echo $this->translate("Location"); ?></label>
    </div>
    <div id="location-element" class="form-element">
      <input type="text" name="location" id="locationSes" value="<?php echo $location; ?>" />
    </div>
	</div>
	<div id="sesnews_location_data-wrapper" class="sesnews_create_map_box form-wrapper" style="display:none;">
  	<div class="form-label"></div>
    <div class="form-element">  
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
        <div id="sesnews_location_map_data" class="sesnews_create_map_container sesbm" style="display:none;">
          <div id="sesnews_default_map" class="sesnews_create_blank_map centerT" style="display:none">
            <i class="fa fa-map-marker sesbasic_text_light"></i>
            <span class="sesbasic_text_light">No Map</span>
          </div>
          <div id="sesnews_location_map" class="sesnews_create_map" style="display:none"></div>
        </div>
      <?php } ?>
      <div class="sesnews_create_location_details sesbasic_bxs">
<!--        <div id="venue_name-wrapper" class="sesnews_create_location_field _full">
          <input type="text" name="venue_name" class="location_value" id="venue_name" value="<?php echo $venue_name; ?>" placeholder="<?php echo $this->translate("Venue Name"); ?>" />
        </div>
        <div id="address-wrapper" class="sesnews_create_location_field ">
          <input type="text" name="address" class="location_value" id="address" value="<?php echo $address; ?>" placeholder="<?php echo $this->translate("Address"); ?>" />
        </div>
        <div id="address2-wrapper" class="sesnews_create_location_field">
          <input type="text" name="address2" class="location_value" id="address2" value="<?php echo $address2; ?>" placeholder="<?php echo $this->translate("Address 2"); ?>" />
        </div>-->
        <div id="city-wrapper" class="sesnews_create_location_field">
          <input type="text" name="city" class="location_value" id="city" value="<?php echo $city; ?>" placeholder="<?php echo $this->translate("City"); ?>" />
        </div>
        <div id="state-wrapper" class="sesnews_create_location_field">
          <input type="text" name="state" class="location_value" id="state" value="<?php echo $state; ?>" placeholder="<?php echo $this->translate("State"); ?>" />
        </div>
        <div id="zip-wrapper" class="sesnews_create_location_field">
          <input type="text" name="zip" class="location_value" id="zip" value="<?php echo $zip; ?>" placeholder="<?php echo $this->translate("Zip"); ?>" />
        </div>
        <?php if($this->countrySelect != ''){ ?>
          <div id="country-wrapper" class="sesnews_create_location_field">
            <select name="country" class="location_value" id="country">
              <?php echo $this->countrySelect; ?>
            </select>
          </div>
        <?php } ?>
        <!-- Lat lng wrapper -->
        <div id="sesnewslat-wrapper" class="sesnews_create_location_field">
          <input type="text" name="lat" class="location_value" id="latSes" value="<?php echo $lat; ?>" placeholder="<?php echo $this->translate("Latitude"); ?>" />
        </div>
        <div id="sesnewslng-wrapper" class="sesnews_create_location_field">
          <input type="text" name="lng" class="location_value" id="lngSes" value="<?php echo $lng; ?>" placeholder="<?php echo $this->translate("Longitude"); ?>" />
        </div>
      </div>
      <div id="location_options" class="clear _links">
        <a id="sesnews_enter_address" href="javascript:;" class="form-link" style="display:none;"><i class="fa fa-map-marker"></i><?php echo $this->translate("Enter Address"); ?></a>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
          <a id="sesnews_reset_location" style="display:none" href="javascript:;" class="form-link"><i class="fa fa-redo-alt"></i><?php echo $this->translate("Reset Location"); ?></a>
        <?php } ?>
      </div>
    </div>
  </div>


<script type="application/javascript">
  en4.core.runonce.add(function() {
    <?php if(!$enableglocation && !empty($optionsenableglotion)) { ?>
      <?php if(!engine_in_array('country', $optionsenableglotion)) { ?>
        scriptJquery('#country-wrapper').hide();
      <?php } ?>
      <?php if(!engine_in_array('state', $optionsenableglotion)) { ?>
        scriptJquery('#state-wrapper').hide();
      <?php } ?>
      <?php if(!engine_in_array('city', $optionsenableglotion)) { ?>
        scriptJquery('#city-wrapper').hide();
      <?php } ?>
      <?php if(!engine_in_array('zip', $optionsenableglotion)) { ?>
        scriptJquery('#zip-wrapper').hide();
      <?php } ?>
      <?php if(!engine_in_array('lat', $optionsenableglotion)) { ?>
        scriptJquery('#sesnewslat-wrapper').hide();
      <?php } else { ?>
        scriptJquery('#sesnewslat-wrapper').show();
      <?php } ?>
      <?php if(!engine_in_array('lng', $optionsenableglotion)) { ?>
        scriptJquery('#sesnewslng-wrapper').hide();
      <?php } else { ?>
        scriptJquery('#sesnewslng-wrapper').show();
      <?php } ?>
    <?php } ?>
  
    if(typeof locationcreatedata == 'undefined'){
      locationcreatedata = true;
      scriptJquery(document).on('click','#sesnews_enter_address',function(){
        scriptJquery('#seslocation-wrapper').show();
        scriptJquery('#sesnews_location_data-wrapper').show();
        scriptJquery('#sesnews_enter_address').hide();
        scriptJquery('#sesnews_reset_location').show();
        var lat = scriptJquery('#latSes').val();
        var lng = scriptJquery('#lngSes').val();
        if(lat && lng ){
          scriptJquery('#sesnews_location_map_data').show();
          scriptJquery('#sesnews_location_map').show();
          scriptJquery('#sesnews_default_map').hide();
          createNewsLoadMap();
          scriptJquery('#sesnews_location_map_data').hide();
        }else{
          scriptJquery('#sesnews_location_map_data').show();
          scriptJquery('#sesnews_location_map').hide();
          scriptJquery('#sesnews_default_map').show();
          scriptJquery('#sesnews_location_map_data').hide();
        }
      });
      scriptJquery(document).on('click','#sesnews_reset_location',function(){
        var confirmAc = confirm('Are you sure that you want to reset this location? It will not be recoverable after being deleted.');
        if(confirmAc == true){
          scriptJquery('#sesnews_location_map_data').hide();
          scriptJquery('#seslocation-wrapper').show();
          scriptJquery('#online_news-wrapper').hide();
          scriptJquery('#sesnews_location_data-wrapper').hide();
          scriptJquery('#sesnews_reset_location').hide();
          scriptJquery('.location_value').val('');
          scriptJquery('#locationSes').val('');
          scriptJquery('#lngSes').val('');
          scriptJquery('#latSes').val('');
        }
        return false;
      });
    }
    <?php if($location != '' && ($venue_name != '' || $city != '' || $state != '' || $country != '' || $zip != '' || $address != '' || $address2 != '')){ ?>
      checkinD = true;
      scriptJquery('#sesnews_enter_address').trigger('click');
    <?php } ?>
    mapLoad_news = false;
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
      initializeSesNewsMap();
    <?php } else { ?>
      scriptJquery('#sesnews_location_data-wrapper').show();
      scriptJquery('#venue_name-wrapper').hide();
      scriptJquery('#address-wrapper').hide();
      scriptJquery('#address2-wrapper').hide();
    <?php } ?>
  });
  function createNewsLoadMap() {
    var lat = scriptJquery('#latSes').val();
    var lng = scriptJquery('#lngSes').val();
    if(lat && lng && scriptJquery('#sesnews_location_map_data').css('display') == 'none'){
      scriptJquery('#sesnews_enter_address').trigger('click');
    }
	if(lat && lng && scriptJquery('#sesnews_location_map_data').css('display') == 'block'){
      scriptJquery('#sesnews_location_map').show();
      scriptJquery('#sesnews_default_map').hide();
      var myLatlng = new google.maps.LatLng(lat, lng);
      var myOptions = {
       zoom: 17,
       center: myLatlng,
       mapTypeId: google.maps.MapTypeId.ROADMAP
      }
      var mapLocationCreate = new google.maps.Map(document.getElementById("sesnews_location_map"), myOptions);
      var marker = new google.maps.Marker({
        position: myLatlng,
        map: mapLocationCreate,
      });
      google.maps.event.addListener(mapLocationCreate, 'click', function() {
        google.maps.event.trigger(mapLocationCreate, 'resize');
        mapLocationCreate.setZoom(17);
        mapLocationCreate.setCenter(myLatlng);
      });
      if(checkinD){
        checkinD = false;
        return ;
      }
	  var geocoder = new google.maps.Geocoder(); 
      geocoder.geocode({'latLng': new google.maps.LatLng(lat, lng)}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK && results.length) {
          if (results[0]) {
            for(var i=0; i<results[0].address_components.length; i++){
              if(results[0].address_components[i].types[0] == 'postal_code') {
	var postalCode = results[0].address_components[i].long_name;
}
             }
           }					
            if (results[1]) {
             var indice=0;
             for (var j=0; j<results.length; j++){
               if (results[j].types[0]=='locality'){
                 indice=j;
                 break;
               }
             }
              var city = state = country = "";
              if(typeof results[indice] != "undefines"){
                for (var i=0; i<results[indice].address_components.length; i++){
                  if (results[indice].address_components[i].types[0] == "locality") {
                    //this is the object you are looking for
                    city = results[indice].address_components[i].long_name;
                  }
                  if (results[indice].address_components[i].types[0] == "administrative_area_level_1") {
                    //this is the object you are looking for
                    state = results[indice].address_components[i].long_name;
                  }
                  if (results[indice].address_components[i].types[0] == "country") {
                    //this is the object you are looking for
                    country = results[indice].address_components[i].long_name;
                  }
                }
              }
             if(postalCode != "")
                  scriptJquery('#zip').val(postalCode);
              else
                  scriptJquery('#zip').val('');
              if(city != "")
                 scriptJquery('#city').val(city);
              else
                 scriptJquery('#city').val('');
              if(state != "")
                scriptJquery('#state').val(state);
              else
                scriptJquery('#state').val('');
              if(country != "")
                scriptJquery('#country').val(country);
              else
                scriptJquery('#country').val('');
			}
		  } 
       });		
	}
 }
</script>
