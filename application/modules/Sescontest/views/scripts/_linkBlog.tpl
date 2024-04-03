<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _linkBlog.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>
<script type="text/javascript">
  en4.core.runonce.add(function() {
   scriptJquery('<div class="sescontest_photo_update_popup sesbasic_bxs" id="sescontest_popup_existing_upload" style="display:none"><div class="sescontest_photo_update_popup_overlay"></div><div class="sescontest_photo_update_popup_container" id="sescontest_popup_container_existing"><div class="sescontest_photo_update_popup_header"><?php echo $this->translate("Select a Blog") ?><a class="fa fa-times" href="javascript:;" onclick="hideContentBlogUpload()" title="<?php echo $this->translate("Close") ?>"></a></div><div class="sescontest_photo_update_popup_content"><div id="sescontest_blog_existing_data"></div><div id="sescontest_profile_existing_img" style="display:none;text-align:center;"><img src="application/modules/Sesbasic/externals/images/loading.gif" alt="<?php echo $this->translate("Loading"); ?>" style="margin-top:10px;"  /></div></div></div></div>').appendTo('body');
    scriptJquery(document).on('click','#contestLinkBlog',function(e){
        scriptJquery('#contestLinkBlog').addClass('active');
        scriptJquery('#writeBlog').removeClass('active');
        var phrase = confirm('Your data will be overrite when you will select blog:');
        if(phrase == '')
          return;
        e.preventDefault();
        scriptJquery('#sescontest_popup_existing_upload').show();
        existingMyBlogsGet();
    });
    scriptJquery(document).on('click','#writeBlog',function(e){
      if(scriptJquery('#contestLinkBlog')) {
        scriptJquery('#contestLinkBlog').removeClass('active');
        scriptJquery('#writeBlog').addClass('active');
      }
      e.preventDefault();
    });
  });
      var canPaginatePageNumber = 1;
function existingMyBlogsGet(){
	scriptJquery('#sescontest_profile_existing_img').show();
	var URL = en4.core.baseUrl+'sescontest/join/existing-blogs/contest_id/'+"<?php echo $this->contest_id;?>";
	(scriptJquery.ajax({
      method: 'post',
      'url': URL ,
      'data': {
        format: 'html',
        page: canPaginatePageNumber,
        is_ajax: 1
      },
      success: function(responseHTML) {
        scriptJquery('#sescontest_blog_existing_data').append(responseHTML);
				
      	scriptJquery('#sescontest_blog_existing_data').slimscroll({
					 height: 'auto',
					 alwaysVisible :true,
					 color :'#000',
					 railOpacity :'0.5',
					 disableFadeOut :true,					 
					});
					scriptJquery('#sescontest_blog_existing_data').slimScroll().bind('slimscroll', function(event, pos){
					 if(canPaginateExistingPhotos == '1' && pos == 'bottom' && scriptJquery('#sescontest_profile_existing_img').css('display') != 'block'){
						 	scriptJquery('#sescontest_profile_existing_img').css('position','absolute').css('width','100%').css('bottom','5px');
							existingMyPhotosGet();
					 }
					});
					scriptJquery('#sescontest_profile_existing_img').hide();
		}
    }));	
}
  scriptJquery(document).on('click','a[id^="sescontest_profile_upload_existing_photos_"]',function(event){
	event.preventDefault();
	var id = scriptJquery(this).attr('id').match(/\d+/)[0];
	if(!id)
      return;
    var blogContent = scriptJquery('#sescontest_thumb_'+id).find('div').html();
    tinyMCE.get('contest_description').setContent(blogContent)
    hideContentBlogUpload();
});

  function hideContentBlogUpload(){
	canPaginatePageNumber = 1;
    scriptJquery('#sescontest_blog_existing_data').html('');
	scriptJquery('#sescontest_popup_existing_upload').hide();
}
</script>
