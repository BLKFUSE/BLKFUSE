<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _formEditImage.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php if( $this->subject()->photo_id !== null ): ?>
  <?php
    $this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'externals/cropper/cropper.js');
    $this->headLink()
      ->appendStylesheet($this->layout()->staticBaseUrl . 'externals/cropper/cropper.css');
  ?>
  <div>
    <?php echo $this->itemPhoto($this->subject(), 'thumb.profile', "", array('id' => 'lassoImg')) ?>
  </div>
  <br />
  <div id="preview-thumbnail" class="preview-thumbnail">
    <?php echo $this->itemPhoto($this->subject(), 'thumb.icon', "", array('id' => 'previewimage')) ?>
  </div>
  <div id="thumbnail-controller" class="thumbnail-controller">
    <?php if ($this->subject()->getPhotoUrl())
    echo '<a href="javascript:void(0);" onclick="lassoStart();">'.$this->translate('Edit Profile Photo').'</a>';?>
  </div>
  <script type="text/javascript">
    var orginalThumbSrc;
    var originalSize;
    var loader = scriptJquery.crtEle('img',{ src: en4.core.staticBaseUrl + 'application/modules/Core/externals/images/loading.gif'});
    var lassoCrop;
    
    var lassoSetCoords = function(coords)
    {
      scriptJquery('#coordinates').val(coords.x1 + ':' + coords.y1 + ':' + coords.width + ':' + coords.height); 
    }

    var lassoStart = function()
    {
      if( !orginalThumbSrc ) orginalThumbSrc = scriptJquery('#previewimage').src;
      originalSize = scriptJquery("#lassoImg").offset();

      scriptJquery('#lassoImg').cropper({
        preview : ".preview-thumbnail",
        done: lassoSetCoords
      });

      scriptJquery('#previewimage').attr("src",scriptJquery('#lassoImg').attr("src"));

      scriptJquery('#thumbnail-controller').html('<a href="javascript:void(0);" onclick="lassoCancel();"><?php echo $this->translate('cancel');?></a>');
      scriptJquery('#coordinates').val(10 + ':' + 10 + ':' + 58+ ':' + 58);
    }

    var lassoEnd = function() {
      scriptJquery('thumbnail-controller').html("<div><img class='loading_icon' src='application/modules/Core/externals/images/loading.gif'/><?php echo $this->translate('Loading...');?></div>");
      lassoCrop.remove();
      scriptJquery('#EditPhoto').trigger("submit");
    }

    var lassoCancel = function() {
      scriptJquery('#preview-thumbnail').html('<img id="previewimage" src="'+orginalThumbSrc+'"/>');
      scriptJquery('#thumbnail-controller').html('<a href="javascript:void(0);" onclick="lassoStart();"><?php echo $this->translate('Edit Profile Photo');?></a>');
      scriptJquery('#coordinates').val("");
      //lassoCrop.remove();
    }
    
    var uploadSignupPhoto = function() {
      scriptJquery('#thumbnail-controller').html("<div><img class='loading_icon' src='application/modules/Core/externals/images/loading.gif'/><?php echo $this->translate('Loading...')?></div>");
      scriptJquery('#EditPhoto').trigger("submit");
      scriptJquery('#Filedata-wrapper').html("");
    }
  </script>
<?php else: ?>
	<div>
	  <?php echo $this->itemPhoto($this->subject(), 'thumb.profile', "", array('id' => 'lassoImg')); ?>
	</div>
<?php endif; ?>
