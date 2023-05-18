<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sesthought/views/scripts/dismiss_message.tpl';?>
<?php 
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/jQuery/jquery-ui.js');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/jQuery/odering.js'); 
?>
<style>
.error {
	color:#FF0000;
}
</style>

<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <div class="sesbasic-form-cont" style="padding-top:15px;">
      <h3><?php echo "Manage Categories"; ?></h3>
      <p class="description"> Thought categories can be managed here. To create new categories, use "Add New Category" form below. Below, you can also choose Title, Description and Icon. <br /><br />To create 2nd-level categories and 3rd-level categories, choose respective 1st-level and 2nd-level category from "Parent Category" dropdown below. Choose this carefully as you will not be able to edit Parent Category later.<br /><br />To reorder the categories, click on their names or row and drag them up or down.</p>
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
          <div class="sesbasic-form-field" id="slug-required" style="display:none;">
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
                  $subcategory = Engine_Api::_()->getDbtable('categories', 'sesthought')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $category->category_id));          foreach ($subcategory as $sub_category):  
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
                <th><?php echo $this->translate("Options") ?></th>
              </tr>
            </thead>
            <tbody>
              <?php //Category Work ?>
              <?php foreach ($this->categories as $category): ?>
              <tr id="categoryid-<?php echo $category->category_id; ?>" data-id="<?php echo $category->category_id; ?>">
                <td><input type="checkbox" class="checkbox check-column" name="delete_tag[]" value="<?php echo $category->category_id; ?>" /></td>
                <td><?php if($category->cat_icon): ?>
                  <?php $cat_icon = Engine_Api::_()->storage()->get($category->cat_icon); ?>
                  <?php if($cat_icon) { ?>
                    <img class="sesbasic-category-icon" src="<?php echo Engine_Api::_()->storage()->get($category->cat_icon)->getPhotoUrl('thumb.icon'); ?>" />
                  <?php } else { ?>
                    <?php echo "---"; ?>
                  <?php } ?>
                  <?php else: ?>
                  <?php echo "---"; ?>
                  <?php endif; ?></td>
                <td><?php echo $category->category_name ?>
                <div class="hidden" style="display:none" id="inline_<?php echo $category->category_id; ?>">
                	<div class="parent">0</div>
                </div>
                </td>
                <td><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesthought', 'controller' => 'categories', 'action' => 'edit-category', 'id' => $category->category_id), $this->translate('Edit'), array()) ?> 
                <?php if($category->category_id != 0){ ?>
                | <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Delete'), array('class' => 'deleteCat','data-url'=>$category->category_id)); ?>
                <?php } ?>
              </tr>
              	<?php if($category->category_id == 0): ?>
                    <?php continue; ?>
                    <?php endif; ?>
              <?php //Subcategory Work
                    $subcategory = Engine_Api::_()->getDbtable('categories', 'sesthought')->getModuleSubcategory(array('column_name' => "*", 'category_id' => $category->category_id));              foreach ($subcategory as $sub_category):  ?>
              <tr id="categoryid-<?php echo $sub_category->category_id; ?>" data-id="<?php echo $sub_category->category_id; ?>">
                <td><input type="checkbox"  class="checkbox check-column" name="delete_tag[]" value="<?php echo $sub_category->category_id; ?>" /></td>
                <td><?php if($sub_category->cat_icon): ?>
                  <?php $cat_icon = Engine_Api::_()->storage()->get($sub_category->cat_icon); ?>
                  <?php if($cat_icon) { ?>
                    <img class="sesbasic-category-icon" src="<?php echo Engine_Api::_()->storage()->get($sub_category->cat_icon)->getPhotoUrl( 'thumb.icon'); ?>" />
                  <?php } else { ?>
                    <?php echo "---"; ?>
                  <?php } ?>
                  <?php else: ?>
                  <?php echo "---"; ?>
                  <?php endif; ?></td>
                <td>-&nbsp;<?php echo $sub_category->category_name ?>
                <div class="hidden" style="display:none" id="inline_<?php echo $sub_category->category_id; ?>">
                	<div class="parent"><?php echo $sub_category->subcat_id; ?></div>
                </div>
                </td>
                <td><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesthought', 'controller' => 'categories', 'action' => 'edit-category', 'id' => $sub_category->category_id), $this->translate('Edit'), array()) ?> | <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Delete'), array('class' => 'deleteCat','data-url'=>$sub_category->category_id)) ?> 		</td>
              </tr>
              <?php 
                		//SubSubcategory Work
                    $subsubcategory = Engine_Api::_()->getDbtable('categories', 'sesthought')->getModuleSubsubcategory(array('column_name' => "*", 'category_id' => $sub_category->category_id));
                    foreach ($subsubcategory as $subsub_category): ?>
              <tr id="categoryid-<?php echo $subsub_category->category_id; ?>" data-id="<?php echo $subsub_category->category_id; ?>">
                <td><input type="checkbox" class="checkbox check-column" name="delete_tag[]" value="<?php echo $subsub_category->category_id; ?>" /></td>
                <td><?php if($subsub_category->cat_icon): ?>
                  <?php $cat_icon = Engine_Api::_()->storage()->get($subsub_category->cat_icon); ?>
                  <?php if($cat_icon) { ?>
                  <img  class="sesbasic-category-icon"  src="<?php echo Engine_Api::_()->storage()->get($subsub_category->cat_icon)->getPhotoUrl( 'thumb.icon'); ?>" />
                  <?php } else { ?>
                    <?php echo "---"; ?>
                  <?php } ?>
                  <?php else: ?>
                  <?php echo "---"; ?>
                  <?php endif; ?></td>
                <td>--&nbsp;<?php echo $subsub_category->category_name ?>
                <div class="hidden" style="display:none" id="inline_<?php echo $sub_category->category_id; ?>">
                	<div class="parent"><?php echo $subsub_category->subsubcat_id; ?></div>
                </div>
                </td>
                <td><?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesthought', 'controller' => 'categories', 'action' => 'edit-category', 'id' => $subsub_category->category_id), $this->translate('Edit'), array()) ?> | <?php echo $this->htmlLink('javascript:void(0);', $this->translate('Delete'), array('class' => 'deleteCat','data-url'=>$subsub_category->category_id)) ?>
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
ajaxurl = en4.core.baseUrl+"admin/sesthought/categories/change-order";
function readImageUrl(input,id) {
    var url = input.value;
    var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
		if(id == 'cover_photo_preview')
		 var idMsg = 'chanel_cover';
		else if(id == 'colored_icon_preview')
			var idMsg = 'colored_icon';
		else
			var idMsg = 'chanel_thumbnail';
    if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG')){
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
					scriptJquery('#name-required').css('background-color','#ffebe8');
					scriptJquery('#tag-name').css('border','1px solid red');
					error = true;
			}else{
				scriptJquery('#name-required').css('background-color','');
				scriptJquery('#tag-name').css('border','');
			}
			if(!slugFieldRequired){
				scriptJquery('#slug-required').css('background-color','#ffebe8');
					scriptJquery('#tag-slug').css('border','1px solid red');
					 scriptJquery('html, body').animate({
            scrollTop: scriptJquery('#addcategory').offset().top },
            1000
       		 );
					error = true;
			}else{
				scriptJquery('#slug-required').css('background-color','');
				scriptJquery('#tag-slug').css('border','');
			}
			if(error){
				scriptJquery('html, body').animate({
            scrollTop: scriptJquery('#addcategory').offset().top },
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
											scriptJquery('#slug-required').css('background-color','#ffebe8');
											scriptJquery('#tag-slug').css('border','1px solid red');
											 scriptJquery('html, body').animate({
												scrollTop: scriptJquery('#addcategory').offset().top },
												1000
											 );
										return false;
								}else{
									scriptJquery('#error-msg').html('');
									scriptJquery('#slug-required').css('background-color','');
									scriptJquery('#tag-slug').css('border','');
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
									scrollTop: scriptJquery(scrollUpTo).offset().top },
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
								scriptJquery('#categoryid-'+scriptJquery(this).val()).css('background-color','#ffebe8');
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
												scrollTop: scriptJquery('#addcategory').offset().top },
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
			scriptJquery('#categoryid-'+id).css('background-color','#ffebe8');
			var selectedCategory=[id]
			scriptJquery.post(window.location.href,{data:selectedCategory,selectDeleted:'true'},function(response){
			response = scriptJquery.parseJSON(response); 
				if(response.ids){
					var ids = response.ids;
					if(response.diff_ids.length>0){
						scriptJquery('#error-message-category-delete').html("Red mark category can't delete.You need to delete lower category of that category first.<br></br>");
						scriptJquery('#error-message-category-delete').css('color','red');
						 scrollToError = true;
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
									scrollTop: scriptJquery('#addcategory').offset().top },
									1000
						);
					}
				}
		});
	}
});
</script>
