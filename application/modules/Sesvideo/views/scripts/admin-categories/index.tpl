<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php 
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/jQuery/odering.js'); 
?>
<style>
.error{
	color:#FF0000;
}
</style>
<?php include APPLICATION_PATH .  '/application/modules/Sesvideo/views/scripts/dismiss_message.tpl';?>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
      </div>
    <?php endif; ?>
    <div class="sesbasic-form-cont">
       <h3><?php echo $this->translate("Manage Categories") ?> </h3>
      <p class="description"><?php echo $this->translate('Video categories can be managed here. To create new categories, use "Add New Category" form below. Below, you can also choose Slug URL, Title, Description, Profile Type to be associated with the category, Icon and Thumbnail. You can also map Categories with the Profile Types, so that questions belonging to the mapped Profile Type will appear to users while creating / editing videos when they choose the associated Category.
<br /><br />To create 2nd-level categories and 3rd-level categories, choose respective 1st-level and 2nd-level category from "Parent Category" dropdown below. Choose this carefully as you will not be able to edit Parent Category later.<br /><br />To reorder the categories, click on their names or row and drag them up or down.'); ?></p>
      <div class="sesbasic-categories-add-form">
        <h4 class="bold">Add New Category</h4>
        <form id="addcategory" method="post" enctype="multipart/form-data">
          <div class="sesbasic-form-field" id="name-required">
            <div class="sesbasic-form-field-label">
              <label for="tag-name">Name</label>
            </div>
            <div class="sesbasic-form-field-element">
              <input name="category_name" autocomplete="off" id="tag-name" type="text"  size="40" >
              <p>The name is how it appears on your site.</p>
            </div>
          </div>
          <div class="sesbasic-form-field" id="slug-required">
            <div class="sesbasic-form-field-label">
              <label for="tag-slug">Slug</label>
            </div>
            <div class="sesbasic-form-field-element">
              <input name="slug" id="tag-slug" type="text" value="" size="40">
              <p id="error-msg" style="color:red"></p>
              <p>The "slug" is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</p>
            </div>
          </div>
          <div class="sesbasic-form-field">
            <div class="sesbasic-form-field-label">
              <label for="title-name">Title</label>
            </div>
            <div class="sesbasic-form-field-element">
              <input name="title" id="title-name" type="text" size="40">
              <p>Title will appear on the Category View Page of your site.</p>
            </div>
          </div>
          <div class="sesbasic-form-field">
            <div class="sesbasic-form-field-label">
              <label for="parent">Parent Category</label>
            </div>
            <div class="sesbasic-form-field-element">
              <select name="parent" id="parent" class="postform">
                <option value="-1">None</option>
               <?php foreach ($this->categories as $category): ?>
                <?php if($category->category_id == 0) : ?>
                <?php continue; ?>
                <?php endif; ?>
                  <option class="level-0" value="<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
                <?php 
                  $subcategory = Engine_Api::_()->getDbtable('categories', 'sesvideo')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $category->category_id));          
                  foreach ($subcategory as $sub_category):  
                ?>
                  <option class="level-1" value="<?php echo $sub_category->category_id; ?>">&nbsp;&nbsp;&nbsp;<?php echo $sub_category->category_name; ?></option>
              <?php 
                  endforeach;
                  endforeach; 
              ?>
              </select>
            </div>
          </div>
          <div class="sesbasic-form-field">
            <div class="sesbasic-form-field-label">
              <label for="parent">Map Profile Type</label>
            </div>
            <div class="sesbasic-form-field-element">
              <select name="profile_type" id="profile_type" class="postform">
               <?php foreach ($this->profiletypes as $key=>$profiletype): ?>
                  <option  value="<?php echo $key ; ?>"><?php echo $profiletype; ?></option>
                <?php 
                  endforeach; 
              ?>
              </select>
            </div>
          </div>
          <div class="sesbasic-form-field" id="show_level">
            <div class="sesbasic-form-field-label">
              <label for="parent">Member Levels</label>
            </div>
            <div class="sesbasic-form-field-element">
              <select name="member_levels[]" id="member_levels" class="postform" multiple="multiple">
               <?php foreach (Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll() as $level): ?>
                  <?php if($level == 'Public'):?><?php continue;?><?php endif;?>
                  <option  value="<?php echo $level->level_id ; ?>"><?php echo $level; ?></option>
               <?php endforeach;?>
              </select>
              <p>Hold down the CTRL key to select or de-select specific Member Levels.</p>
            </div>
          </div>
          <div class="sesbasic-form-field">
            <div class="sesbasic-form-field-label">
              <label for="tag-description">Description</label>
            </div>
            <div class="sesbasic-form-field-element">
              <textarea name="description" id="tag-description"></textarea>
              <p>Description will appear on the Category View Page of your site.</p>
            </div>
          </div>
          <div class="sesbasic-form-field">
            <div class="sesbasic-form-field-label">
              <label>Upload Icon</label><span style="font-size: 11px;"> [Recommended size is: 40px * 40px.]</span>
            </div>
            <div class="sesbasic-form-field-element">
              <input type="file" name="icon" id="chanel_cover" alt="Icon" onchange="readImageUrl(this,'cover_photo_preview')" />
              <span style="display:none" class="error" id="chanel_cover-msg"></span>
            </div>
          </div>
          <div class="form-wrapper" id="cover_photo_preview-wrapper" style="display: none;">
          	<div class="form-label" id="cover_photo_preview-label">&nbsp;</div>
            <div class="form-element" id="cover_photo_preview-element">
            	<input width="100" type="image" height="100" alt="Icon" src="" id="cover_photo_preview" name="cover_photo_preview">
            </div>
          </div>
          <div class="sesbasic-form-field">
            <div class="sesbasic-form-field-label">
              <label>Upload Thumbnail</label><span style="font-size: 11px;"> [Recommended size is: 500px * 300px.]</span>
            </div>
            <div class="sesbasic-form-field-element">
              <input type="file" name="thumbnail" id="chanel_thumbnail" alt="Thumbnail" onchange="readImageUrl(this,'thumbnail_photo_preview')" />
              <span style="display:none" class="error" id="chanel_thumbnail-msg"></span>
            </div>
          </div>
           <div class="form-wrapper" id="thumbnail_photo_preview-wrapper" style="display: none;">
          	<div class="form-label" id="thumbnail_photo_preview-label">&nbsp;</div>
            <div class="form-element" id="thumbnail_photo_preview-element">
            	<input width="100" type="image" height="100" alt="Thumbnail" src="" id="thumbnail_photo_preview" name="thumbnail_photo_preview">
            </div>
          </div>
          <div class="submit sesbasic-form-field">
            <button type="button" id="submitaddcategory" class="upload_image_button button">Add New Category</button>
          </div>
        </form>
        <div class="sesbasic-categories-add-form-overlay" id="add-category-overlay" style="display:none"></div>
      </div>
      <div class="sesbasic-categories-listing">
      	<div id="error-message-category-delete"></div>
        <form id="multimodify_form" method="post" onsubmit="return multiModify();">
          <table class='admin_table' style="width: 100%;">
            <thead>
              <tr>
                <th><input type="checkbox" onclick="selectAll()"  name="checkbox" /></th>
                <th><?php echo $this->translate("Icon") ?></th>
                <th><?php echo $this->translate("Name") ?></th>
                <th><?php echo $this->translate("Slug") ?></th>
                <th><?php echo $this->translate("Options") ?></th>
              </tr>
            </thead>
            <tbody>
              <?php //Category Work ?>
              <?php foreach ($this->categories as $category): ?>
              <?php if($category->category_id == 0) : ?>
              <?php continue; ?>
              <?php endif; ?>
              <tr id="categoryid-<?php echo $category->category_id; ?>" data-id="<?php echo $category->category_id; ?>">
                <td><input type="checkbox" class="checkbox check-column" name="delete_tag[]" value="<?php echo $category->category_id; ?>" /></td>
                <td><?php if($category->cat_icon): ?>
                  <img class="sesbasic-category-icon" src="<?php echo Engine_Api::_()->storage()->get($category->cat_icon)->getPhotoUrl('thumb.icon'); ?>" />
                  <?php else: ?>
                  <?php echo "---"; ?>
                  <?php endif; ?></td>
                <td><?php echo $category->category_name ?>
                <div class="hidden" style="display:none" id="inline_<?php echo $category->category_id; ?>">
                	<div class="parent">0</div>
                </div>
                </td>
                <td><?php  echo $category->slug ; ?></td>
                <td><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesvideo', 'controller' => 'categories', 'action' => 'edit-category', 'id' => $category->category_id), $this->translate('Edit'), array()) ?> | <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Delete'), array('class' => 'deleteCat','data-url'=>$category->category_id)); ?>
              </tr>
              <?php //Subcategory Work
                    $subcategory = Engine_Api::_()->getDbtable('categories', 'sesvideo')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $category->category_id));              foreach ($subcategory as $sub_category):  ?>
              <tr id="categoryid-<?php echo $sub_category->category_id; ?>" data-id="<?php echo $sub_category->category_id; ?>">
                <td><input type="checkbox"  class="checkbox check-column" name="delete_tag[]" value="<?php echo $sub_category->category_id; ?>" /></td>
                <td><?php if($sub_category->cat_icon): ?>
                  <img class="sesbasic-category-icon" src="<?php echo Engine_Api::_()->storage()->get($sub_category->cat_icon)->getPhotoUrl( 'thumb.icon'); ?>" />
                  <?php else: ?>
                  <?php echo "---"; ?>
                  <?php endif; ?></td>
                <td>-&nbsp;<?php echo $sub_category->category_name ?>
                <div class="hidden" style="display:none" id="inline_<?php echo $sub_category->category_id; ?>">
                	<div class="parent"><?php echo $sub_category->subcat_id; ?></div>
                </div>
                </td>
                <td><?php  echo $sub_category->slug ; ?></td>
                <td><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesvideo', 'controller' => 'categories', 'action' => 'edit-category', 'id' => $sub_category->category_id), $this->translate('Edit'), array()) ?> | <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Delete'), array('class' => 'deleteCat','data-url'=>$sub_category->category_id)) ?> 		</td>
              </tr>
              <?php //SubSubcategory Work
                    $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sesvideo')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $sub_category->category_id));
                    foreach ($subsubcategory as $subsub_category): ?>
              <tr id="categoryid-<?php echo $subsub_category->category_id; ?>" data-id="<?php echo $subsub_category->category_id; ?>">
                <td><input type="checkbox" class="checkbox check-column" name="delete_tag[]" value="<?php echo $subsub_category->category_id; ?>" /></td>
                <td><?php if($subsub_category->cat_icon): ?>
                  <img  class="sesbasic-category-icon"  src="<?php echo Engine_Api::_()->storage()->get($subsub_category->cat_icon)->getPhotoUrl( 'thumb.icon'); ?>" />
                  <?php else: ?>
                  <?php echo "---"; ?>
                  <?php endif; ?></td>
                <td>--&nbsp;<?php echo $subsub_category->category_name ?>
                <div class="hidden" style="display:none" id="inline_<?php echo $sub_category->category_id; ?>">
                	<div class="parent"><?php echo $subsub_category->subsubcat_id; ?></div>
                </div>
                </td>
                <td><?php  echo $subsub_category->slug ; ?></td>
                <td><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesvideo', 'controller' => 'categories', 'action' => 'edit-category', 'id' => $subsub_category->category_id), $this->translate('Edit'), array()) ?> | <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Delete'), array('class' => 'deleteCat','data-url'=>$subsub_category->category_id)) ?>
              </tr>
              <?php endforeach; ?>
              <?php endforeach; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
          <span class='buttons'>
           <button type="button" id="deletecategoryselected" class="upload_image_button button"><?php echo $this->translate("Delete Selected") ?></button>
          </span>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="application/javascript">
ajaxurl = en4.core.baseUrl+"admin/sesvideo/categories/change-order";
function readImageUrl(input,id) {
    var url = input.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
		if(id == 'cover_photo_preview')
		 var idMsg = 'chanel_cover';
		else
			var idMsg = 'chanel_thumbnail';
    if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'gif' || ext == 'GIF')) {
        var reader = new FileReader();
        reader.onload = function (e) {
					 scriptJquery('#'+id+'-wrapper').show();
           scriptJquery('#'+id).attr('src', e.target.result);
        }
				scriptJquery('#'+id+'-wrapper').show();
				scriptJquery('#'+idMsg+'-msg').hide();
        reader.readAsDataURL(input.files[0]);
    }else{
				 scriptJquery('#'+id+'-wrapper').hide();
				 scriptJquery('#'+idMsg+'-msg').show();
				 scriptJquery('#'+idMsg+'-msg').html("<br><?php echo $this->translate('Please select png,jpeg,jpg image only.'); ?>");
         scriptJquery('#'+idMsg).val('');
		}
  }
scriptJquery (document).ready(function (e) {
    scriptJquery ('#addcategory').on('submit',(function(e) {
			var error = false;
			var nameFieldRequired = scriptJquery('#tag-name').val();
			var slugFieldRequired = scriptJquery('#tag-slug').val();
			if(!nameFieldRequired){
					scriptJquery('#name-required').addClass('category_field_error');
					error = true;
			}else{
				scriptJquery('#name-required').removeClass('category_field_error');
			}
			if(!slugFieldRequired){
				scriptJquery('#slug-required').addClass('category_field_error');
					 scriptJquery('html, body').animate({
            scrollTop: scriptJquery('#addcategory').position().top },
            1000
       		 );
					error = true;
			}else{
				scriptJquery('#slug-required').removeClass('category_field_error');
			}
			if(error){
				scriptJquery('html, body').animate({
            scrollTop: scriptJquery('#addcategory').position().top },
            1000
       		 );
				return false;
			}
				scriptJquery('#add-category-overlay').css('display','block');
        e.preventDefault();
				var form = scriptJquery('#addcategory');
        var formData = new FormData(this);
				formData.append('is_ajax', 1);
        scriptJquery .ajax({
            type:'POST',
            url: scriptJquery(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
								scriptJquery('#cover_photo_preview-wrapper').css('display','none');
								scriptJquery('#thumbnail_photo_preview-wrapper').css('display','none');
								scriptJquery('#add-category-overlay').css('display','none');
								data = scriptJquery.parseJSON(data); 
								if(data.slugError){
											scriptJquery('#error-msg').html('Unavailable');
											scriptJquery('#slug-required').addClass('category_field_error');
											 scriptJquery('html, body').animate({
												scrollTop: scriptJquery('#addcategory').position().top },
												1000
											 );
										return false;
								}else{
									scriptJquery('#error-msg').html('');
									scriptJquery('#slug-required').removeClass('category_field_error');
								}
                parent = scriptJquery('#parent').val();
								if ( parent > 0 && scriptJquery('#categoryid-' + parent ).length > 0 ){ // If the parent exists on this page, insert it below. Else insert it at the top of the list.
								var scrollUpTo= '#categoryid-' + parent;
									scriptJquery( '.admin_table #categoryid-' + parent ).after( data.tableData ); // As the parent exists, Insert the version with - - - prefixed
								}else{
									var scrollUpTo = '#multimodify_form';
									scriptJquery( '.admin_table' ).prepend( data.tableData ); // As the parent is not visible, Insert the version with Parent - Child - ThisTerm					
								}
								if ( scriptJquery('#parent') ) {
									// Create an indent for the Parent field
									indent = data.seprator;
									if(indent != 3)
										form.find( 'select#parent option:selected' ).after( '<option value="' + data.id + '">' + indent + data.name + '</option>' );
								}
								scriptJquery('html, body').animate({
									scrollTop: scriptJquery(scrollUpTo).position().top },
									1000
								 );
								scriptJquery('#addcategory')[0].reset();
            },
            error: function(data){
            	//silence
						}
        });
    }));
		scriptJquery("#submitaddcategory").on("click", function() {
       scriptJquery("#addcategory").submit();
    });
});
scriptJquery("#tag-name").keyup(function(){
		var Text = scriptJquery(this).val();
		Text = Text.toLowerCase();
		Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
		scriptJquery("#tag-slug").val(Text);        
});
function selectAll()
{
  var i;
  var multimodify_form = document.getElementById('multimodify_form');
  var inputs = multimodify_form.elements;
  for (i = 1; i < inputs.length - 1; i++) {
    if (!inputs[i].disabled) {
      inputs[i].checked = inputs[0].checked;
    }
  }
}
scriptJquery("#deletecategoryselected").click(function(){
		var n = scriptJquery(".checkbox:checked").length;
   if(n>0){
	  var confirmDelete = confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete the selected categories?")) ?>');
		if(confirmDelete){
				var selectedCategory = new Array();
        if (n > 0){
            scriptJquery(".checkbox:checked").each(function(){
								scriptJquery('#categoryid-'+scriptJquery(this).val()).addClass('category_delete_error');
                selectedCategory.push(scriptJquery(this).val());
            });
						var scrollToError = false;
        		scriptJquery.post(window.location.href,{data:selectedCategory,selectDeleted:'true'},function(response){
						  response = scriptJquery.parseJSON(response); 
							var ids = response.ids;
							if(response.diff_ids.length>0){
									scriptJquery('#error-message-category-delete').html("Red mark category can't delete.You need to delete lower category of that category first.<br></br>");
									scriptJquery('#error-message-category-delete').css('color','red');
									 scrollToError = true;
							}else{
								scriptJquery('#error-message-category-delete').html("");
									scriptJquery('#error-message-category-delete').css('color','');
							}
							scriptJquery('#multimodify_form')[0].reset();
							if(response.ids){
								//error-message-category-delete;
								for(var i =0;i<=ids.length;i++){
									scriptJquery('select#parent option[value="' + ids[i] + '"]').remove();
									scriptJquery('#categoryid-'+ids[i]).fadeOut("normal", function() {
											scriptJquery(this).remove();
									});
								}
							}
							if(scrollToError){
								scriptJquery('html, body').animate({
												scrollTop: scriptJquery('#addcategory').position().top },
												1000
								);
							}
						});
						return false;
				}
		}
	 }
});
scriptJquery(document).on('click','.deleteCat',function(){
	var id = scriptJquery(this).attr('data-url');
	var confirmDelete = confirm('<?php echo $this->string()->escapeJavascript($this->translate("Are you sure you want to delete the selected category?")) ?>');
	if(confirmDelete){
			scriptJquery('#categoryid-'+id).addClass('category_delete_error');
			var selectedCategory=[id];
			var scrollToError = false;
			scriptJquery.post(window.location.href,{data:selectedCategory,selectDeleted:'true'},function(response){
			response = scriptJquery.parseJSON(response); 
				if(response.ids){
					var scrollToError = false;
					var ids = response.ids;
					if(response.diff_ids.length>0){
						scriptJquery('#error-message-category-delete').html("Red mark category can't delete.You need to delete lower category of that category first.<br></br>");
						scriptJquery('#error-message-category-delete').css('color','red');
					}else{
						scriptJquery('#error-message-category-delete').html("");
							scriptJquery('#error-message-category-delete').css('color','');
					}
					for(var i =0;i<=ids.length;i++){
						scriptJquery('select#parent option[value="' + ids[i] + '"]').remove();
						scriptJquery('#categoryid-'+ids[i]).fadeOut("normal", function() {
								scriptJquery(this).remove();
						});
					}
					if(scrollToError){
						scriptJquery('html, body').animate({
									scrollTop: scriptJquery('#addcategory').position().top },
									1000
						);
					}
				}
		});
	}
});
</script>
