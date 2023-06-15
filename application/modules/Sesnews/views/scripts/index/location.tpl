<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: location.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<div class="sesnews_edit_location_popup">
  <?php echo $this->form->render($this) ?>
</div>
<script type="text/javascript">
  document.getElementById('lat-wrapper').style.display = 'none';
  document.getElementById('lng-wrapper').style.display = 'none';
  scriptJquery('#mapcanvas-label').attr('id','map-canvas-list');
  scriptJquery('#map-canvas-list').css('height','200px');
  scriptJquery('#ses_location-label').attr('id','ses_location_data_list');
  scriptJquery('#ses_location_data_list').html('<?php echo $this->news->location; ?>');
  scriptJquery('#ses_location-wrapper').css('display','none');
  <?php if($this->type == 'news_location'){ ?>
    scriptJquery('#location-wrapper').hide();
    scriptJquery('#execute').hide();
    scriptJquery('#or_content').hide();
    scriptJquery('#location-form').find('div').find('div').find('h3').hide();
    scriptJquery('#cancel').replaceWith('<button name="cancel" id="cancel" type="button" href="javascript:void(0);" onclick="parent.Smoothbox.close();">'+en4.core.language.translate('Close')+'</button>');
  <?php } ?>
  initializeSesNewsMapList();
  scriptJquery( window ).load(function() {
    editSetMarkerOnMapListNews();
  });
</script>
