<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: albumsongplaylistartistdayofthe.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl.'externals/autocompleter/autocomplete.js'); ?>
<script type="text/javascript">
  
  function oftheday(value) {
    setMusicCookie("sesmusic_oftheday", value, 1);
  }
  
  function setMusicCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toGMTString();
    document.cookie = cname + "=" + cvalue + "; " + expires+"; path=/"; 
  } 
  
  en4.core.runonce.add(function() {
    setMusicCookie("sesmusic_oftheday", "", - 3600);    
    var album_id = getParams('page');
    document.getElementById('album_id-wrapper').style.display = 'none';
    AutocompleterRequestJSON('album_title', "<?php echo $this->url(array('module' => 'sesmusic', 'controller' => 'settings', 'action' => 'get-albums'), 'admin_default', true) ?>/album_id/" + album_id, function(selecteditem) {
      document.getElementById('album_id').value = selecteditem.id;
    });
  });
 
  function getParams(page) {
    
    var params;
    var regexp;  

    page = page.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    regexp = "[\\?&]" + page + "=([^&#]*)";
    regex = new RegExp(regexp);
    params = regex.exec(parent.window.location.href);

    if (params == null)
      return "";
    else
      return params[1];
  }
</script>
<div class="form-wrapper">
  <div class="form-label"></div>
  <div id="album_title-element" class="form-element">
    <?php echo "Enter the name of the content [chosen from above setting.]."; ?>
    <input type="text" style="width:300px;" class="text" value="" id="album_title" name="album_title">
  </div>
</div>
<script type="text/javascript">
  scriptJquery(``).insertBefore(scriptJquery('#starttime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
    
    timepicker: false,
   })
  );
  
  scriptJquery(``).insertBefore(scriptJquery('#endtime-date').attr("type","text").attr("autocomplete","off").attr("placeholder","Select a Date").datepicker({
      
      timepicker: false,
    })
  );

  scriptJquery('#starttime-hour').hide();
  scriptJquery('#starttime-minute').hide();
  scriptJquery('#starttime-ampm').hide();
  scriptJquery('#endtime-hour').hide();
  scriptJquery('#endtime-minute').hide();
  scriptJquery('#endtime-ampm').hide();
</script>
