<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php $getUserInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($this->subject->user_id); ?>
<?php
  $href = $this->subject->getHref();
  $imageURL = $this->subject->getPhotoUrl('thumb.profile');
?>
<?php if($this->locationLatLng->lat) { ?>
<script type="text/javascript">
    var latLngSes;
    function initializeMapSes() {
        var latLngSes = new google.maps.LatLng(<?php echo $this->locationLatLng->lat; ?>,<?php echo $this->locationLatLng->lng; ?>);
        var myOptions = {
            zoom: 13,
            center: latLngSes,
            navigationControl: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
				
        var map = new google.maps.Map(document.getElementById("sesmember_map_container"), myOptions);
			

        var marker = new google.maps.Marker({
            position: latLngSes,
            map: map,
        });

				//trigger map resize on every call
       scriptJquery(document).on('click','ul#main_tabs li.tab_layout_sesmember_user_map',function (event) {
            google.maps.event.trigger(map, 'resize');
            map.setZoom(13);
            map.setCenter(latLngSes);
        });

        google.maps.event.addListener(map, 'click', function() {
            google.maps.event.trigger(map, 'resize');
            map.setZoom(13);
            map.setCenter(latLngSes);
        });
    }
</script>
<?php } ?>
<div class="sesmember_profile_map_container sesbasic_clearfix">
	<div class="sesmember_profile_map sesbasic_clearfix sesbd" id="sesmember_map_container" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;"></div>
        
	<div class="sesmember_profile_map_address_box sesbasic_bxs">
		<b><a href="<?php echo $this->url(array('resource_id' => $this->subject->user_id,'resource_type'=>'user','action'=>'get-direction'), 'sesbasic_get_direction', true); ?>" class="openSmoothbox"><?php echo $getUserInfoItem->location ?></a></b>
	</div>
</div>	
<script type="text/javascript">
var tabId_map = <?php echo $this->identity; ?>;
scriptJquery(document).ready(function() {
	tabContainerHrefSesbasic(tabId_map);	
});
<?php if($this->locationLatLng->lat) { ?>
    scriptJquery(document).ready(function() {
        initializeMapSes();
    });
<?php } ?>
</script>
