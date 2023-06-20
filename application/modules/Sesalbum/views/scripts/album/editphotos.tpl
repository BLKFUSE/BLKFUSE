<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: editphotos.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                 .'application/modules/Sesalbum/externals/scripts/core.js'); ?> 
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesalbum/externals/styles/styles.css'); ?>
<?php
$randonNumber = 'sesPag';
?>
<?php if(!$this->is_ajax){ ?>
<div class="layout_middle">
  <div class="generic_layout_container sesalbum_sp_menu">
    <?php echo $this->content()->renderWidget('sesalbum.browse-menu'); ?> 
  </div>
<div class="generic_layout_container">
  <h3>
    <?php echo $this->htmlLink(Engine_Api::_()->sesalbum()->getHref($this->album->getIdentity()), $this->album->getTitle()) ?>
    (<?php echo $this->translate(array('%s photo', '%s photos', $this->album->count()),$this->locale()->toNumber($this->album->count())) ?>)
  </h3>
  
<?php if( $this->paginator->count() > 0 ): ?>
  <br />
  <?php //echo $this->paginationControl($this->paginator); ?>
<?php endif; ?>
<div class="sesalbum_manage_photos_wrapper sesbasic_clearfix sesbasic_bxs" id="scrollHeightDivSes_<?php echo $randonNumber; ?>">
<form action="<?php echo $this->escape($this->form->getAction()) ?>" name="editPhotos" method="<?php echo $this->escape($this->form->getMethod()) ?>">
  <?php echo $this->form->album_id; ?>
  <ul class='sesalbum_manage_photos' id="tabbed-widget_<?php echo $randonNumber; ?>">
<?php } ?>
    <?php foreach( $this->paginator as $photo ): ?>
      <li class="sesalbum_manage_photos_list" id="thumbs-photo-<?php echo $photo->photo_id ?>">
        <div class="sesbasic_clearfix sesbm">
          <div class="sesalbum_manage_photos_list_photo">
            <?php $url = $photo->getPhotoUrl('thumb.normalmain'); ?>
            <span style="background-image:url(<?php echo $url ?>);"></span>
          </div>
          <div class="sesalbum_manage_photos_list_info">
            <?php
              $key = $photo->getGuid();
              echo $this->form->getSubForm($key)->render($this);
            ?>
            <div class="sesalbums_editphotos_cover">
              <input type="radio" name="cover" id="album_photo_<?php echo $photo->getIdentity() ?>_cover" value="<?php echo $photo->getIdentity() ?>" <?php if( $this->album->photo_id == $photo->getIdentity() ): ?> checked="checked"<?php endif; ?> />
              <label for="album_photo_<?php echo $photo->getIdentity() ?>_cover"><?php echo $this->translate('Main Photo');?></label>
            </div>
            <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum_enable_location', 1)){ ?>
            <div class="sesalbum_manage_photos_list_location clear">
              <span>
                <i class="fas fa-map-marker-alt sesbasic_text_light"></i>
                <?php echo $this->htmlLink(Array('module'=> 'sesalbum', 'controller' => 'photo', 'action' => 'location', 'route' => 'sesalbum_extended', 'type' => 'album_photo','album_id' =>$photo->album_id, 'photo_id' => $photo->getIdentity()), $this->translate("Edit Location"), array('onclick'=>"return openURLinSmoothBox(this.href)")); ?></span>
              <div id="location_map_<?php echo $photo->getIdentity(); ?>" title="<?php echo $photo->location; ?>"><?php echo $photo->location; ?></div>
            </div>
            <?php } ?>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
<?php if(!$this->is_ajax){ ?>
  </ul>
  <div class="sesbasic_view_more sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn fa fa-repeat')); ?> </div>
  <div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
  <?php echo $this->form->submit->render(); ?>
  <?php echo $this->form->cancel->render(); ?>
</form>
  </div>
<?php if( $this->paginator->count() > 0 ): ?>
  <?php //echo $this->paginationControl($this->paginator); ?>
<?php endif; ?>
  </div>
</div>
</div>
<?php } ?>
<script type="text/javascript">
<?php if(!$this->is_ajax){ ?>
	window.ivnGetSetValue = function(obj,context){
  f = obj.form
  o = document.getElementById('ivnData')

  if(f.use_try.checked){
    /* controlled blow up if error occurs */
    try{
      remote_form = parent.document.forms["i"];
      ivnHandleRemoteFormExchange(f,o,context);
    } catch(e){ var ee = e.message || 0; console.log('Error: \n\n'+e+'\n'+ee); }
  }
  else{
    /* don't control the blow up if error occurs... let it bubble to the JS console */
    remote_form = parent.document.forms["i"];
    ivnHandleRemoteFormExchange(f,o,context);
  }
}
	// auto load function
	scriptJquery( window ).load(function() {
		 scriptJquery(window).scroll( function() {
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
<?php } ?>
	viewMoreHide_<?php echo $randonNumber; ?>();
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function viewMore_<?php echo $randonNumber; ?> (){
    var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
    (scriptJquery.ajax({
        dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "albums/editphotos/<?php echo $this->album_id; ?>",
      'data': {
        format: 'html',
        page: <?php echo $this->page + 1; ?>,    
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
      },
      success: function(responseHTML) {
         scriptJquery('#tabbed-widget_<?php echo $randonNumber; ?>').append(responseHTML);
				document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
      }
    }));
    return false;
  }
	/*end code for auto load*/
</script>
