<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: location.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesalbum/externals/scripts/core.js'); ?> 
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css'); ?>
<div class="sesalbum_edit_location_popup">
  <?php echo $this->form->render($this) ?>
</div>
<script type="text/javascript">
$('lat-wrapper').style.display = 'none';
$('lng-wrapper').style.display = 'none';
scriptJquery('#mapcanvas-label').attr('id','map-canvas-list');
scriptJquery('#map-canvas-list').css('height','200px');
scriptJquery('#ses_location-label').attr('id','ses_location_data_list');
scriptJquery('#ses_location_data_list').html('<?php echo $this->photo->location; ?>');
// scriptJquery('#ses_location-wrapper').css('display','none');
// scriptJquery('#mapcanvas-wrapper').css('display','none');
<?php if($this->type == 'location'){ ?>
	scriptJquery('#location-wrapper').hide();
	scriptJquery('#execute').hide();
	scriptJquery('#or_content').hide();
	scriptJquery('#location-form').find('div').find('div').find('h3').hide();
	scriptJquery('#cancel').replaceWith('<button name="cancel" id="cancel" type="button" href="javascript:void(0);" onclick="parent.Smoothbox.close();">'+en4.core.language.translate('Close')+'</button>');
<?php } ?>
initializeSesAlbumMapList();
 scriptJquery( window ).load(function() {
	editSetMarkerOnMapList();
	});
// change parent window location data ...
$('execute').addEvent('click',function(){
	ivnGetSetValue();
});
	window.ivnGetSetValue = ivnGetSetValue = function(){ 
 		parent.document.getElementById("location_map_<?php echo $this->photo_id; ?>").innerHTML = document.getElementById('locationSesList').value; //remote_form.data.value;
	}
</script>