<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: left-bar.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/scripts/core.js'); 
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/dashboard.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesnews/externals/styles/styles.css'); ?>
<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.enable.package', 1) && isset($this->news->package_id) && $this->news->package_id ){ 
  $package = Engine_Api::_()->getItem('sesnewspackage_package', $this->news->package_id);
  $modulesEnable = json_decode($package->params,true);	
 } ?>
<div class="layout_middle">
  <div class="generic_layout_container sesnews_dashboard_main_nav">
	  <?php echo $this->content()->renderWidget('sesnews.browse-menu'); ?>
  </div>
</div>
<div class="layout_middle">
  <div class="sesnews_dashboard_menu_list">
    <div class="sesbasic_dashboard_container sesbasic_clearfix">
      <div class="sesnews_dashboard_top_section sesbasic_clearfix sesbm">
        <div class="sesbasic_dashboard_top_section_left">
          <div class="sesbasic_dashboard_top_section_item_photo"> <?php echo $this->htmlLink($this->news->getHref(), $this->itemPhoto($this->news, 'thumb.icon')) ?> </div>
          <div class="sesbasic_dashboard_top_section_item_title"> <?php echo $this->htmlLink($this->news->getHref(),$this->news->getTitle()); ?> </div>
        </div>
        <div class="sesnews_dashboard_top_section_btns">
          <a href="<?php echo $this->news->getHref(); ?>" class="sesbasic_link_btn"><?php echo $this->translate("View News"); ?></a>
          <?php if($this->news->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'delete')){ ?>
            <a href="<?php echo $this->url(array('news_id' => $this->news->news_id,'action'=>'delete'), 'sesnews_specific', true); ?>" class="sesbasic_link_btn smoothbox"><?php echo $this->translate("Delete News"); ?></a>
          <?php } ?>
        </div>
      </div>
    </div>
    <div class="sesbasic_dashboard_tabs sesnews_dashboard_tabs sesbasic_bxs">
      <ul class="sesbm">
        <li class="sesbm">
          <?php $manage_news = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'manage_news')); ?>
          <a href="#Manage" class="sesbasic_dashboard_nopropagate"> </a>
          <?php $edit_news = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'edit_news')); ?>
          <?php $edit_photo = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'edit_photo')); ?>
          <?php $news_roles = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'news_role')); ?>
          <?php $contact_information = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'contact_information')); ?>
          <?php $seo = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'seo')); ?>
          <?php $style = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'edit_style')); ?>
          <?php $editLocation = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'edit_location')); ?>
          <?php $fields = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'fields')); ?>
          <?php $upgrade = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'upgrade')); ?>
          <?php $mainphoto = Engine_Api::_()->getDbtable('dashboards', 'sesnews')->getDashboardsItems(array('type' => 'mainphoto')); ?>
          <ul class="sesbm" style="display:none">
            
            <?php if(!empty($edit_news) && $edit_news->enabled): ?>
            <li><a href="<?php echo $this->url(array('news_id' => $this->news->custom_url), 'sesnews_dashboard', true); ?>" class="dashboard_a_link" ><i class="fa  fa-edit"></i> <?php echo $this->translate($edit_news->title); ?></a></li>
            <?php endif; ?>
            
            <?php if((!empty($edit_photo) && $edit_photo->enabled && empty($modulesEnable) ) || ((isset($modulesEnable) && array_key_exists('modules',$modulesEnable) && engine_in_array('photo',$modulesEnable['modules'])))): ?>
            <li><a href="<?php echo $this->url(array('news_id' => $this->news->custom_url,'action'=>'edit-photo'), 'sesnews_dashboard', true); ?>" class="dashboard_a_link" ><i class="fa fa-image"></i> <?php echo $this->translate($edit_photo->title); ?></a></li>
            <?php endif; ?>
            
            <?php if(!empty($news_roles) && $news_roles->enabled): ?>
            <li><a href="<?php echo $this->url(array('news_id' => $this->news->custom_url,'action'=>'news-role'), 'sesnews_dashboard', true); ?>" class="dashboard_a_link" ><i class="fa fa-user-plus "></i> <?php echo $this->translate($news_roles->title); ?></a></li>
            <?php endif; ?>
            
            <?php if((!empty($fields) && $fields->enabled && empty($modulesEnable)) || (isset($modulesEnable) &&  isset($modulesEnable['custom_fields']) && $modulesEnable['custom_fields'] && $package->custom_fields_params != '[]')): ?>
            <li><a href="<?php echo $this->url(array('news_id' => $this->news->custom_url,'action'=>'fields'), 'sesnews_dashboard', true); ?>" class="dashboard_a_link" ><?php echo $this->translate($fields->title); ?></a></li>
            <?php endif; ?>
            <?php if((!empty($upgrade) && $upgrade->enabled && !empty($modulesEnable))): ?>
            <li><a href="<?php echo $this->url(array('news_id' => $this->news->custom_url,'action'=>'upgrade'), 'sesnews_dashboard', true); ?>" class="dashboard_a_link" ><i class="fa fa-refresh "></i> <?php echo $this->translate($upgrade->title); ?></a></li>
            <?php endif; ?>
            <?php if(!empty($contact_information) && $contact_information->enabled): ?>
            <li><a href="<?php echo $this->url(array('news_id' => $this->news->custom_url,'action'=>'contact-information'), 'sesnews_dashboard', true); ?>" class="sesbasic_dashboard_nopropagate_content dashboard_a_link"><i class="fa fa-envelope "></i> <?php echo $this->translate($contact_information->title); ?></a></li>
            <?php endif; ?>
            
            <?php if(!empty($seo) && $seo->enabled): ?>
            <li><a href="<?php echo $this->url(array('news_id' => $this->news->custom_url, 'action'=>'seo'), 'sesnews_dashboard', true); ?>" class="sesbasic_dashboard_nopropagate_content dashboard_a_link"><i class="fa fa-file-alt"></i> <?php echo $this->translate($seo->title); ?></a></li>
            <?php endif; ?>
            
            <?php if(@$style->enabled && Engine_Api::_()->authorization()->isAllowed('sesnews_news', Engine_Api::_()->user()->getViewer(), 'style')): ?>
            <li><a  href="<?php echo $this->url(array('news_id' => $this->news->custom_url, 'action'=>'style'), 'sesnews_dashboard', true); ?>" class="sesbasic_dashboard_nopropagate_content dashboard_a_link"><i class="fa fa-edit "></i> <?php echo $this->translate($style->title); ?></a></li>
            <?php endif; ?>
            
						<?php if(@$editLocation->enabled && !empty($this->news->location)): ?>
							<li><a  href="<?php echo $this->url(array('news_id' => $this->news->custom_url, 'action'=>'edit-location'), 'sesnews_dashboard', true); ?>" class="dashboard_a_link"><i class="fa fa-map-marker "></i> <?php echo $this->translate($editLocation->title); ?></a></li>
						<?php endif; ?>
            
            <?php if(@$mainphoto->enabled): ?>
            <li><a class="dashboard_a_link" href="<?php echo $this->url(array('news_id' => $this->news->custom_url, 'action'=>'mainphoto'), 'sesnews_dashboard', true); ?>" ><?php echo $this->translate($mainphoto->title); ?></a></li>
            <?php endif; ?>
            
          </ul>
        </li>
      </ul>
      <?php if(isset($this->news->cover_photo) && $this->news->cover_photo != 0 && $this->news->cover_photo != ''){ 
        $newsCover =	Engine_Api::_()->storage()->get($this->news->cover_photo, '')->getPhotoUrl(); 
      }else
        $newsCover =''; 
      ?>
      <div class="sesnews_dashboard_news_info sesbasic_clearfix">
        <?php if(isset($this->news->cover_photo) && $this->news->cover_photo != 0 && $this->news->cover_photo != ''){ ?>
          <div class="sesnews_dashboard_news_info_cover"> 
            <img src="<?php echo $newsCover; ?>" />
            <?php if($this->news->featured || $this->news->sponsored){ ?>
              <p class="sesnews_labels">
                <?php if($this->news->featured ){ ?>
                <span class="sesnews_label_featured"><?php echo $this->translate("FEATURED"); ?></span>
                <?php } ?>
                <?php if($this->news->sponsored ){ ?>
                <span class="sesnews_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
                <?php } ?>
              </p>
            <?php } ?>
            <?php if($this->news->verified ){ ?>
              <div class="sesnews_verified_label" title="<?php echo $this->translate("VERIFIED"); ?>"><i class="fa fa-check"></i>
              </div>
            <?php } ?>
            <div class="sesnews_dashboard_news_main_photo sesbm">
              <img src="<?php echo $this->news->getPhotoUrl(); ?>" /> 
            </div>
          </div>
        <?php } else { ?>
          <div class="sesnews_dashboard_news_photo sesbm">
            <div class="sesnews_deshboard_img_news">
              <img src="<?php echo $this->news->getPhotoUrl(); ?>" />
              <?php if($this->news->featured || $this->news->sponsored){ ?>
                <div class="sesnews_list_labels">
                  <?php if($this->news->featured ){ ?>
                  <p class="sesnews_label_featured"><?php echo $this->translate("FEATURED"); ?></p>
                  <?php } ?>
                  <?php if($this->news->sponsored ){ ?>
                  <p class="sesnews_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></p>
                  <?php } ?>
                </div>
              <?php } ?>
              <?php if($this->news->verified ){ ?>
                <div class="sesnews_verified_label" title="<?php echo $this->translate("VERIFIED"); ?>"><i class="fa fa-check"></i>
                </div>
              <?php } ?>
            </div>
          <div class="sesnews_dashboard_news_info_content sesbasic_clearfix sesbm">
            <div class="sesnews_dashboard_news_details">
              <div class="sesnews_dashboard_news_title">
                <a href="<?php echo $this->news->getHref(); ?>"><b><?php echo $this->news->getTitle(); ?></b></a>
              </div>
              <?php if($this->news->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews_enable_location', 1)):?>
                <?php $locationText = $this->translate('Location');?>
                <?php $locationvalue = $this->news->location;?>
                <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
                  <?php echo $location = "<div class=\"sesnews_list_stats sesnews_list_location\">
                    <span class=\"widthfull\">
                      <i class=\"fa fa-map-marker sesbasic_text_light\" title=\"$locationText\"></i>
                      <span title=\"$locationvalue\"><a href='".$this->url(array('resource_id' => $this->news->news_id,'resource_type'=>'sesnews_news','action'=>'get-direction'), 'sesbasic_get_direction', true)."' class=\"openSmoothbox\">".$this->news->location."</a></span>
                    </span>
                  </div>"; 
                ?>
              <?php } else { ?>
                <?php echo $location = "<div class=\"sesnews_list_stats sesnews_list_location\">
                    <span class=\"widthfull\">
                      <i class=\"fa fa-map-marker sesbasic_text_light\" title=\"$locationText\"></i>
                      <span title=\"$locationvalue\">".$this->news->location."</span>
                    </span>
                  </div>"; 
                ?>
              <?php } ?>
              <?php endif;?>
              <?php if($this->news->category_id){ 
                $category = Engine_Api::_()->getItem('sesnews_category', $this->news->category_id);
              ?>
                <?php if($category) { ?>
                  <div class="sesnews_list_stats">
                    <span><i class="far fa-folder-open sesbasic_text_light"></i> 
                    <a href="<?php echo $category->getHref(); ?>"><?php echo $category->getTitle(); ?></a> 
                    </span> 
                  </div>
                <?php } ?>
              <?php } ?>
              <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.enable.package', 1)) { ?>
                <div class="sesnews_list_stats sesnews_list_payment">
                  <span class="widthfull">
                  <i class="fa fa-credit-card-alt sesbasic_text_light" title="<?php echo '';?>"></i></span>
                  <?php echo $this-> content()->renderWidget('sesnewspackage.news-renew-button',array('sesnews_news'=>$this->news)); ?> 
                </div>            
              <?php } ?>        
            </div>
          </div>
        <?php }; ?>
        </div>
      </div>
      <?php echo $this->content()->renderWidget('sesnews.advance-share',array('dashboard'=>true)); ?> 
    </div>


<script type="application/javascript">
scriptJquery(document).ready(function(){
	var totalLinks = scriptJquery('.dashboard_a_link');
	for(var i =0;i < totalLinks.length ; i++){
			var data_url = scriptJquery(totalLinks[i]).attr('href');
			var linkurl = window.location.href ;
			if(linkurl.indexOf(data_url) > 0){
					scriptJquery(totalLinks[i]).parent().addClass('active');
					scriptJquery(totalLinks[i]).parent().parent().parent().find('a.sesbasic_dashboard_nopropagate').trigger('click');
			}
	}
});

var sendParamInSearch = '';
scriptJquery(document).on('click','.sesbasic_dashboard_nopropagate, .sesbasic_dashboard_nopropagate_content',function(e){
	e.preventDefault();
	//ajax request
	if(scriptJquery(this).hasClass('sesbasic_dashboard_nopropagate_content')){
			if(!scriptJquery(this).parent().hasClass('active'))
				getDataThroughAjax(scriptJquery(this).attr('href'));
		  scriptJquery(".sesbasic_dashboard_tabs > ul li").each(function() {
				scriptJquery(this).removeClass('active');
			});
			scriptJquery('.sesbasic_dashboard_tabs > ul > li ul > li').each(function() {
					scriptJquery(this).removeClass('active');
			});			
			scriptJquery(this).parent().addClass('active');
			scriptJquery(this).parent().parent().parent().addClass('active');
	}	
});
var ajaxRequest;
//get data through ajax
function getDataThroughAjax(url){
	if(!url)
		return;
	history.pushState(null, null, url);
// 	if(typeof ajaxRequest != 'undefined')
// 		ajaxRequest.cancel();
	scriptJquery('.sesnews_dashboard_content').html('<div class="sesbasic_loading_container"></div>');
	ajaxRequest = scriptJquery.ajax({
    dataType: 'html',
      method: 'post',
      url : url,
      data : {
        format : 'html',
				is_ajax:true,
				dataAjax : sendParamInSearch,
				is_ajax_content:true,
      },
      success: function(response) {
				scriptJquery('.sesnews_dashboard_content').html(response);
				if(typeof executeAfterLoad == 'function'){
					executeAfterLoad();
				}
				if(scriptJquery('#loadingimgsesnews-wrapper').length)
					scriptJquery('#loadingimgsesnews-wrapper').hide();
			}
    });
    ajaxRequest;
}
scriptJquery(".sesbasic_dashboard_tabs > ul li a").each(function() {
	var c = scriptJquery(this).attr("href");
	scriptJquery(this).click(function() {
		if(scriptJquery(this).hasClass('sesbasic_dashboard_nopropagate')){
			if(scriptJquery(this).parent().find('ul').is(":visible")){
				scriptJquery(this).parent().find('ul').slideUp()
			}else{
					scriptJquery(".sesbasic_dashboard_tabs ul ul").each(function() {
							scriptJquery(this).slideUp();
					});
					scriptJquery(this).parent().find('ul').slideDown()
			}
					return false
			}	
	})
});
var error = false;
var objectError ;
var counter = 0;
var customAlert;
function validateForm(){
		var errorPresent = false;
		if(scriptJquery('#sesnews_ajax_form_submit').length>0)
			var submitFormVal= 'sesnews_ajax_form_submit';
		else
			return false;
		objectError;
		scriptJquery('#'+submitFormVal+' input, #'+submitFormVal+' select,#'+submitFormVal+' checkbox,#'+submitFormVal+' textarea,#'+submitFormVal+' radio').each(
				function(index){
						customAlert = false;
						var input = scriptJquery(this);
						if(scriptJquery(this).closest('div').parent().css('display') != 'none' && scriptJquery(this).closest('div').parent().find('.form-label').find('label').first().hasClass('required') && scriptJquery(this).prop('type') != 'hidden' && scriptJquery(this).closest('div').parent().attr('class') != 'form-elements'){	
						  if(scriptJquery(this).prop('type') == 'checkbox'){
								value = '';
								if(scriptJquery('input[name="'+scriptJquery(this).attr('name')+'"]:checked').length > 0) { 
										value = 1;
								};
								if(value == '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'select-multiple'){
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'select-one' || scriptJquery(this).prop('type') == 'select' ){
								if(scriptJquery(this).val() === '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'radio'){
								if(scriptJquery("input[name='"+scriptJquery(this).attr('name').replace('[]','')+"']:checked").val() === '')
									error = true;
								else
									error = false;
							}else if(scriptJquery(this).prop('type') == 'textarea'){
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
							}else{
								if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
									error = true;
								else
									error = false;
							}
							if(error){
							 if(counter == 0){
							 	objectError = this;
							 }
								counter++
							}else{
							}
							if(error)
								errorPresent = true;
							error = false;
						}
				}
			);	
			return errorPresent ;
}
var ajaxDeleteRequest;
scriptJquery(document).on('click','.sesnews_ajax_delete',function(e){
	e.preventDefault();
	var object = scriptJquery(this);
	var url = object.attr('href');
// 	if(typeof ajaxDeleteRequest != 'undefined')
// 			ajaxDeleteRequest.cancel();
	if(confirm("Are you sure want to delete?")){
		 scriptJquery.ajax({
      method: 'post',
      url : url,
      data : {
        format : 'html',
				is_ajax:true,
      },
      success: function(response) {
				if(response)
					scriptJquery(object).parent().parent().remove();
				else
					alert('Something went wrong,please try again later');
			}
    });
	}
});
  var submitFormAjax;
  scriptJquery(document).on('submit','#sesnews_ajax_form_submit',function(e){
    e.preventDefault();
    //validate form
    var validation = validateForm();
    //if error comes show alert message and exit.
    if(validation)
    {
    if(!customAlert){
    alert('<?php echo $this->string()->escapeJavascript("Please complete the red mark fields"); ?>');

    }
    if(typeof objectError != 'undefined'){
    var errorFirstObject = scriptJquery(objectError).parent().parent();
    scriptJquery('html, body').animate({
    scrollTop: errorFirstObject.offset().top
    }, 2000);
    }
    return false;	
    }else{
    if(!scriptJquery('#sesdashboard_overlay_content').length)
    scriptJquery('#sesnews_ajax_form_submit').before('<div class="sesbasic_loading_cont_overlay" id="sesdashboard_overlay_content"></div>');
    else
    scriptJquery('#sesdashboard_overlay_content').show();
    //submit form 
    var form = scriptJquery('#sesnews_ajax_form_submit');
    var formData = new FormData(this);
    formData.append('is_ajax', 1);
    submitFormAjax = scriptJquery.ajax({
    type:'POST',
    url: scriptJquery(this).attr('action'),
    data:formData,
    cache:false,
    contentType: false,
    processData: false,
    success:function(data){
    scriptJquery('#sesdashboard_overlay_content').hide();

    var dataJson = data;
    try{
    var dataJson = JSON.parse(data);
    }catch(err){
    //silence
    }
    if(dataJson.redirect){
    scriptJquery('#'+dataJson.redirect).trigger('click');
    return;
    }else{
    if(data){
    scriptJquery('.sesnews_dashboard_content').html(data);
    }else{
    alert('Something went wrong,please try again later');	
    }
    }
    },
    error: function(data){
    //silence
    }
    });
    }
  });
</script>
