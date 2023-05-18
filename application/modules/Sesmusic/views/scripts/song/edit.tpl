<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: edit.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/styles.css'); ?>
<script type="text/javascript">
scriptJquery(document).ready(function() {
  <?php if(empty($this->albumsong->photo_id)): ?>
  if(document.getElementById('song_mainphoto_preview-wrapper'))
  document.getElementById('song_mainphoto_preview-wrapper').style.display = 'none';
  <?php endif; ?>
  <?php if(empty($this->albumsong->song_cover)): ?>
  if(document.getElementById('song_cover_preview-wrapper'))
  document.getElementById('song_cover_preview-wrapper').style.display = 'none';
  <?php endif; ?>
});

//Show choose image 
function showReadImage(input,id) {
  var url = input.value; 
  var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
  if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG')){
    var reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById(id+'-wrapper').style.display = 'block';
      $(id).setAttribute('src', e.target.result);
    }
    document.getElementById(id+'-wrapper').style.display = 'block';
    reader.readAsDataURL(input.files[0]);
  }
}
scriptJquery(document).ready(function() {
if(document.getElementById('category_id')) {
  if (document.getElementById('category_id').value == 0) {
    document.getElementById('subcat_id-wrapper').style.display = "none";
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
  }

  var cat_id = document.getElementById('category_id').value; 
  if (document.getElementById('subcat_id')) {
    var subcat = document.getElementById('subcat_id').value;
  }

  if(subcat == '') {
    document.getElementById('subcat_id-wrapper').style.display = "none";
  }

  if (subcat == 0) {
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
  }

  if (document.getElementById('subsubcat_id')) {
    var subsubcat = document.getElementById('subsubcat_id').value;
  }

  if (document.getElementById('module_type'))
    var module_type = document.getElementById('module_type').value;

  if (cat_id && module_type && !subcat) {
    var temp = window.setInterval(function() {
      ses_subcategory(cat_id, module_type);
      clearInterval(temp);
    }, 2000);
  }
  
  //Check Search Form Only
  var search =  0;
  if(document.getElementById('search_params')) {
    search =  1;
  }

  var e = document.getElementById("subcat_id").length; 
  if (e == 1 && search != 1) {
    document.getElementById('subcat_id-wrapper').style.display = "none";
  }

  var e = document.getElementById("subsubcat_id").length;
  if (e == 1 && search != 1) {
    document.getElementById('subsubcat_id-wrapper').style.display = "none";
  }

}
});


//Function for get sub category
function ses_subcategory(category_id, module) {

  temp = 1;
  if (category_id == 0) {
    if (document.getElementById('subcat_id-wrapper')) {
      document.getElementById('subcat_id-wrapper').style.display = "none";
      document.getElementById('subcat_id').innerHTML = '';
    }

    if (document.getElementById('subsubcat_id-wrapper')) {
      document.getElementById('subsubcat_id-wrapper').style.display = "none";
      document.getElementById('subsubcat_id').innerHTML = '';
    }
    return false;
  }

  var url = en4.core.baseUrl + 'sesmusic/index/subcategory/category_id/' + category_id + '/param/song';

  en4.core.request.send(scriptJquery.ajax({
    url: url,
    data: {
    },
    success: function(responseHTML) {

      if (document.getElementById('subcat_id') && responseHTML) {
        if (document.getElementById('subcat_id-wrapper')) {
          document.getElementById('subcat_id-wrapper').style.display = "block";
        }

        document.getElementById('subcat_id').innerHTML = responseHTML;
      } else {

        if (document.getElementById('subcat_id-wrapper')) {
          document.getElementById('subcat_id-wrapper').style.display = "none";
          document.getElementById('subcat_id').innerHTML = '';
        }

        if (document.getElementById('subsubcat_id-wrapper')) {
          document.getElementById('subsubcat_id-wrapper').style.display = "none";
          document.getElementById('subsubcat_id').innerHTML = '';
        }
      }
    }
  }));
}

//Function for get sub sub category
function sessubsubcat_category(category_id, module) {

  if (category_id == 0) {
    if (document.getElementById('subsubcat_id-wrapper')) {
      document.getElementById('subsubcat_id-wrapper').style.display = "none";
      document.getElementById('subsubcat_id').innerHTML = '';
    }
    return false;
  }

  var url = en4.core.baseUrl + 'sesmusic/index/subsubcategory/category_id/' + category_id + '/param/song';

  en4.core.request.send(scriptJquery.ajax({
    url: url,
    data: {
    },
    success: function(responseHTML) {
      if (document.getElementById('subsubcat_id') && responseHTML) {
        if (document.getElementById('subsubcat_id-wrapper'))
          document.getElementById('subsubcat_id-wrapper').style.display = "block";
        document.getElementById('subsubcat_id').innerHTML = responseHTML;
      } else {
        if (document.getElementById('subsubcat_id-wrapper')) {
          document.getElementById('subsubcat_id-wrapper').style.display = "none";
          document.getElementById('subsubcat_id').innerHTML = '';
        }
      }
    }
  }));
}
</script>
<div class="layout_middle">
	<div class="generic_layout_container layout_core_content">
    <div class="sesmusic_editsong_form">
      <?php echo $this->form->render(); ?>
    </div>
	</div>    
</div>
<script type="text/javascript">
  scriptJquery('.core_main_sesmusic').parent().addClass('active');
</script>
