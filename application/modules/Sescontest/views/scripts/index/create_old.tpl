<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>

<?php 
      if($this->resource_id && $this->resource_type){ ?> 
        <?php
          $tabid = "";
          if($this->widget_id)
            $tabid = "/tab/".$this->widget_id;
          echo $this->htmlLink($this->item->getHref().$tabid, $this->translate('Go Back'), array(
            'class' => 'sesbasic_button sesbasic_icon_add'
            ));
      ?>
        
<?php }
?>

<?php if($this->createLimit == 1):?>
  <?php if(!$this->typesmoothbox){ ?>
		<?php 
			$this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . "externals/selectize/css/normalize.css");
			$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/selectize/js/selectize.js'); 
		?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/html2canvas.js'); ?>
    <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
    

    <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/jquery.timepicker.css'); ?>
    <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/datepicker/bootstrap-datepicker.css'); ?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery1.11.js'); ?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/jquery.timepicker.js'); ?>
    <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/datepicker/bootstrap-datepicker.js'); ?>
  <?php }else{ ?>
    <script type="application/javascript">
      Sessmoothbox.css.push("<?php echo $this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'; ?>");
      Sessmoothbox.javascript.push("<?php echo $this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.min.js'; ?>");
    </script>
  <?php } ?>
  <?php if(engine_count($_POST) == 0 && Engine_Api::_()->getApi('settings','core')->getSetting('sescontest.category.selection', 1)):?>
      <div class="sescontest_create_step_container sesbasic_bxs sesbasic_clearfix">
        <h3><?php echo $this->translate('Create New Contest');?></h3>
        <p><?php echo $this->translate("It's easy to set up. Just choose a Contest category to get started.");?></p>
        <?php $iconType = Engine_Api::_()->getApi('settings','core')->getSetting('sescontest.category.icon');?>
        <?php if($iconType == 0):?>
          <?php $icon = 'colored_icon';?>
        <?php elseif($iconType == 1):?>
          <?php $icon = 'cat_icon';?>
        <?php elseif($iconType == 2):?>
          <?php $icon = 'thumbnail';?>
        <?php endif;?>
        <div class="sescontest_create_categories_listing">
          <?php foreach($this->categories as $category):?>
            <div class="sescontest_create_category">
              <section class="">
                <div class="_inner">
                  <div class="_step1">
                    <a href="javascript:;" class="sesbasic_linkinherit" onClick="selectCat(<?php echo $category->category_id;?>);return false;">
                      <?php if($category->$icon):?>
                        <i style="background-image:url(<?php echo  Engine_Api::_()->storage()->get($category->$icon)->getPhotoUrl();?>);"></i>
                      <?php else:?>
                        <i style="background-image:url(application/modules/Sescontest/externals/images/contest-icon-big.png);"></i>
                      <?php endif;?>
                      <span><?php echo $category->category_name;?></span>
                    </a>
                  </div>
                </div>
              </section>
            </div>   
          <?php endforeach;?>
        </div>
      </div>
    <?php endif; ?>
    <div class="sescontest_create_container">
        <?php echo $this->form->render();?>
        <div class="sescontest_join_loading sescontest_join_overlay">
          <div class="sescontest_join_overlay_cont">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>
            <span class="_text"><?php echo $this->translate('Creating Contest ...');?></span>
          </div>
        </div>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.guidelines', 1)):?>
          <div id="sescontest_create_tips" class="sescontest_create_tips">
            <div class="create_tips_top_sec">
              <h3>Tips</h3>
            </div>
            <div class="create_tips_bottom_sec">
              <div class="sesbasic_html_block">
                <?php echo Engine_Api::_()->getApi('settings','core')->getSetting('sescontest.message.guidelines', '');?>
              </div>
            </div>
          </div>
        <?php endif;?>
      </div>
   <div id="sescontest_share_content_html" style="display:block;">
    <?php echo $this->content()->renderWidget('sescontest.share-content',array('isPopup'=>$this->typesmoothbox)); ?>
   </div>   
    <script type="application/javascript">
      en4.core.runonce.add(function(){
         if(scriptJquery('.sescontest_create_step_container').length > 0){
           scriptJquery('.sescontest_create_container').hide();
           scriptJquery('.sescontest_create_step_container').show();
           scriptJquery('.sescontest_create_tips').hide();
         }else{
           scriptJquery('.sescontest_create_tips').show();
           scriptJquery('.sescontest_create_container').show();
           scriptJquery('.sescontest_create_step_container').hide();
         }
      });
      function selectCat(value){
        scriptJquery('.sescontest_create_tips').show();
       scriptJquery('#category_id').val(value);
       scriptJquery('.sescontest_create_container').show();
       scriptJquery('.sescontest_create_step_container').hide();
      }
      scriptJquery(function() {
        scriptJquery.fn.scrollBottom = function() {
             return scriptJquery(document).height() - this.scrollTop() - this.height();
        };
        var $el = scriptJquery('#sescontest_create_tips');
        var positionInitial = scriptJquery('#title').offsetTop;
        scriptJquery('<style>#sescontest_create_tips{top:'+positionInitial+'px;}</style>').appendTo(document.head);
        var $window = scriptJquery(window);

        $window.bind("scroll resize", function() {
          var positionInitialTitle = scriptJquery('#title-element').offsetTop;
          var position = $el.offset().top - $window.scrollTop();
          if($window.scrollTop() < positionInitial){
             $el.css('top',positionInitial);
          }else{
             $el.css('top',$window.scrollTop());
          }
        });
      });
    
    </script>

    <script type="text/javascript">
    //trim last -
    function removeLastMinus (myUrl)
    {
        if (myUrl.substring(myUrl.length-1) == "-")
        {
            myUrl = myUrl.substring(0, myUrl.length-1);
        }
        return myUrl;
    }
    var changeTitle = true;
    var validUrl = true;
      en4.core.runonce.add(function()
      {
        if(scriptJquery('#editor_type') && scriptJquery('#contest_type option:selected').val() == '1')
        scriptJquery('#editor_type-wrapper').show();
        else
        scriptJquery('#editor_type-wrapper').hide();

        if(scriptJquery('#sescontest_announcement_date') && scriptJquery('#vote_type').val() == '1')
        scriptJquery('#sescontest_announcement_date').show();
        else
        scriptJquery('#sescontest_announcement_date').hide();

            //auto fill custom url value
    scriptJquery("#title").keyup(function(){
            var Text = scriptJquery(this).val();
          if(!changeTitle)
                return;
            Text = Text.toLowerCase();
            Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
            Text = removeLastMinus(Text);
            scriptJquery("#custom_url").val(Text);        
    });
    scriptJquery("#title").blur(function(){
            if(scriptJquery(this).val()){
                    changeTitle = false;
            }
    });
    scriptJquery("#custom_url").blur(function(){
      validUrl = false;
      scriptJquery('#check_custom_url_availability').trigger('click');
    });
    //function ckeck url availability
    scriptJquery('#check_custom_url_availability').click(function(){
        var custom_url_value = scriptJquery('#custom_url').val();
        if(!custom_url_value)
            return;
        scriptJquery('#sescontest_custom_url_wrong').hide();
        scriptJquery('#sescontest_custom_url_correct').hide();
        scriptJquery('#sescontest_custom_url_loading').css('display','inline-block');
        scriptJquery.post('<?php echo $this->url(array('controller' => 'ajax','module'=>'sescontest', 'action' => 'custom-url-check'), 'default', true) ?>',{value:custom_url_value},function(response){
                    scriptJquery('#sescontest_custom_url_loading').hide();
                    response = scriptJquery.parseJSON(response);
                    if(response.error){
                        validUrl = false;
                        scriptJquery('#sescontest_custom_url_correct').hide();
                        scriptJquery('#sescontest_custom_url_wrong').css('display','inline-block');
                    }else{
                            validUrl = true;
                            scriptJquery('#custom_url').val(response.value);
                            scriptJquery('#sescontest_custom_url_wrong').hide();
                            scriptJquery('#sescontest_custom_url_correct').css('display','inline-block');
                    }
            });
    });
      //Tags
      scriptJquery('#tags').selectize({
        maxItems: 10,
        valueField: 'label',
        labelField: 'label',
        searchField: 'label',
        create: true,
        load: function(query, callback) {
          if (!query.length) return callback();
          scriptJquery.ajax({
            url: '<?php echo $this->url(array('controller' => 'tag', 'action' => 'suggest'), 'default', true) ?>',
            data: { value: query },
            success: function (transformed) {
              callback(transformed);
            },
            error: function () {
                callback([]);
            }
          });
        }
      });
    });
    
    //custom term and condition
    function customTermAndCondition(){
        if(scriptJquery("#is_custom_term_condition").is(':checked'))
        scriptJquery("#custom_term_condition-wrapper").show();  // checked
        else
        scriptJquery("#custom_term_condition-wrapper").hide();  // unchecked
    }
     en4.core.runonce.add(function()
      {
    scriptJquery('#is_custom_term_condition').bind('change', function () {
        customTermAndCondition();
    });
    customTermAndCondition();
    });
    </script>
    <?php 
    $defaultProfileFieldId = "0_0_$this->defaultProfileId";
    $profile_type = 2;
    ?>
    <?php echo $this->partial('_customFields.tpl', 'sesbasic', array()); ?>
    <script type="text/javascript">
      var defaultProfileFieldId = '<?php echo $defaultProfileFieldId ?>';
      var profile_type = '<?php echo $profile_type ?>';
      var previous_mapped_level = 0;

      function showFields(cat_value, cat_level,typed,isLoad) {
            var categoryId = getProfileType(formObj.find('#category_id-wrapper').find('#category_id-element').find('#category_id').val());
            var subcatId = getProfileType(formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').val());
            var subsubcatId = getProfileType(formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').val());
            var type = categoryId+','+subcatId+','+subsubcatId;
        if (cat_level == 1 || (previous_mapped_level >= cat_level && previous_mapped_level != 1) || (profile_type == null || profile_type == '' || profile_type == 0)) {
          profile_type = getProfileType(cat_value);
          if (profile_type == 0) {
            profile_type = '';
          } else {
            previous_mapped_level = cat_level;
          }
          document.getElementById(defaultProfileFieldId).value = profile_type;
          changeFields(document.getElementById(defaultProfileFieldId),null,isLoad,type);
        }
      }
      var getProfileType = function(category_id) {
        var mapping = <?php echo Zend_Json_Encoder::encode(Engine_Api::_()->getDbTable('categories', 'sescontest')->getMapping(array('category_id', 'profile_type'))); ?>;
              for (i = 0; i < mapping.length; i++) {	
            if (mapping[i].category_id == category_id)
            return mapping[i].profile_type;
            }
        return 0;
      }
      en4.core.runonce.add(function() {
        var defaultProfileId = '<?php echo '0_0_' . $this->defaultProfileId ?>' + '-wrapper';
         if ($type(document.getElementById(defaultProfileId)) && typeof document.getElementById(defaultProfileId) != 'undefined') {
          scriptJquery('#'+defaultProfileId).css('display', 'none');
        }
      });
      function showSubCategory(cat_id,selectedId) {
            var selected;
            if(selectedId != ''){
                var selected = selectedId;
            }
        var url = en4.core.baseUrl + 'sescontest/ajax/subcategory/category_id/' + cat_id;
        scriptJquery.ajax({
					method:'post',
          url: url,
          data: {
                    'selected':selected
          },
          success: function(responseHTML) {
            if (formObj.find('#subcat_id-wrapper').length && responseHTML) {
              formObj.find('#subcat_id-wrapper').show();
              formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').html(responseHTML);
            } else {
              if (formObj.find('#subcat_id-wrapper').length) {
                formObj.find('#subcat_id-wrapper').hide();
                formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').html( '<option value="0"></option>');
              }
            }
                  if (formObj.find('#subsubcat_id-wrapper').length) {
                formObj.find('#subsubcat_id-wrapper').hide();
                formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
              }
                    //showFields(cat_id,1);
          }
        }); 
      }
        function showSubSubCategory(cat_id,selectedId,isLoad) {
            var categoryId = getProfileType(document.getElementById('category_id').value);
            if(cat_id == 0){
                if (formObj.find('#subsubcat_id-wrapper').length) {
                formObj.find('#subsubcat_id-wrapper').hide();
                formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
                            document.getElementsByName("0_0_1")[0].value=categoryId;		
          }
                showFields(cat_id,1,categoryId);
                return false;
            }
            showFields(cat_id,1,categoryId);
            var selected;
            if(selectedId != ''){
                var selected = selectedId;
            }
        var url = en4.core.baseUrl + 'sescontest/ajax/subsubcategory/subcategory_id/' + cat_id;
        (scriptJquery.ajax({
					method:'post',
          url: url,
          data: {
                    'selected':selected
          },
          success: function(responseHTML) {
            if (formObj.find('#subsubcat_id-wrapper').length && responseHTML) {
              formObj.find('#subsubcat_id-wrapper').show();
              formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html(responseHTML);
            } else {
              if (formObj.find('#subsubcat_id-wrapper').length) {
                formObj.find('#subsubcat_id-wrapper').hide();
                formObj.find('#subsubcat_id-wrapper').find('#subsubcat_id-element').find('#subsubcat_id').html( '<option value="0"></option>');
              }
            }				
                }
        }));  
      }
        function showCustom(value,isLoad){
            var categoryId = getProfileType(formObj.find('#category_id-wrapper').find('#category_id-element').find('#category_id').val());
            var subcatId = getProfileType(formObj.find('#subcat_id-wrapper').find('#subcat_id-element').find('#subcat_id').val());
            var id = categoryId+','+subcatId;
                showFields(value,1,id,isLoad);
            if(value == 0)
                document.getElementsByName("0_0_1")[0].value=subcatId;	
                return false;
        }
        function showCustomOnLoad(value,isLoad){
         <?php if(isset($this->category_id) && $this->category_id != 0){ ?>
            var categoryId = getProfileType(<?php echo $this->category_id; ?>)+',';
            <?php if(isset($this->subcat_id) && $this->subcat_id != 0){ ?>
            var subcatId = getProfileType(<?php echo $this->subcat_id; ?>)+',';
            <?php  }else{ ?>
            var subcatId = '';
            <?php } ?>
            <?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
            var subsubcat_id = getProfileType(<?php echo $this->subsubcat_id; ?>)+',';
            <?php  }else{ ?>
            var subsubcat_id = '';
            <?php } ?>
            var id = (categoryId+subcatId+subsubcat_id).replace(/,+$/g,"");;
                showFields(value,1,id,isLoad);
            if(value == 0)
                document.getElementsByName("0_0_1")[0].value=subcatId;	
                return false;
            <?php }else{ ?>
                showFields(value,1,'',isLoad);
            <?php } ?>
        }
       en4.core.runonce.add(function(){
                formObj = scriptJquery('#sescontest_create_form').find('div').find('div').find('div');
                var sesdevelopment = 1;
                <?php if((isset($this->category_id) && $this->category_id != 0) || (isset($_GET['category_id']) && $_GET['category_id'] != 0)){ ?>
                        <?php if(isset($this->subcat_id)){$catId = $this->subcat_id;}else $catId = ''; ?>
                        showSubCategory('<?php echo isset($_GET['category_id'])? $_GET['category_id']:$this->category_id; ?>','<?php echo $catId; ?>','yes');
                 <?php  }else{ ?>
                    formObj.find('#subcat_id-wrapper').hide();
                 <?php } ?>
                 <?php if(isset($this->subsubcat_id) && $this->subsubcat_id != 0){ ?>
                    if (<?php echo isset($this->subcat_id) && intval($this->subcat_id) > 0 ? $this->subcat_id : 'sesdevelopment' ?> == 0) {
                     formObj.find('#subsubcat_id-wrapper').hide();
                    } else {
                        <?php if(isset($this->subsubcat_id)){$subsubcat_id = $this->subsubcat_id;}else $subsubcat_id = ''; ?>
                        showSubSubCategory('<?php echo $this->subcat_id; ?>','<?php echo $this->subsubcat_id; ?>','yes');
                    }
                 <?php }else{ ?>
                         formObj.find('#subsubcat_id-wrapper').hide();
                 <?php } ?>
                showCustomOnLoad('','no');
      });

    //drag drop photo upload
     en4.core.runonce.add(function()
      {
        if(scriptJquery('#dragandrophandlerbackground').hasClass('requiredClass')){
            scriptJquery('#dragandrophandlerbackground').parent().parent().find('#photouploader-label').find('label').addClass('required').removeClass('optional');	
        }
        document.getElementById('photouploader-wrapper').style.display = 'block';
        document.getElementById('contest_main_photo_preview-wrapper').style.display = 'none';
        document.getElementById('photo-wrapper').style.display = 'none';

    var obj = scriptJquery('#dragandrophandlerbackground');
    obj.click(function(e){
        scriptJquery('#photo').val('');
        scriptJquery('#contest_main_photo_preview').attr('src','');
      scriptJquery('#photo').trigger('click');
    });
    obj.on('dragenter', function (e) 
    {
        e.stopPropagation();
        e.preventDefault();
        scriptJquery (this).addClass("sesbd");
    });
    obj.on('dragover', function (e) 
    {
         e.stopPropagation();
         e.preventDefault();
    });
    obj.on('drop', function (e) 
    {
             scriptJquery (this).removeClass("sesbd");
             scriptJquery (this).addClass("sesbm");
         e.preventDefault();
         var files = e.originalEvent.dataTransfer;
         handleFileBackgroundUpload(files,'contest_main_photo_preview');
    });
    scriptJquery (document).on('dragenter', function (e) 
    {
        e.stopPropagation();
        e.preventDefault();
    });
    scriptJquery (document).on('dragover', function (e) 
    {
      e.stopPropagation();
      e.preventDefault();
    });
        scriptJquery (document).on('drop', function (e) 
        {
                e.stopPropagation();
                e.preventDefault();
        });
    });
    function handleFileBackgroundUpload(input,id) {
      var url = input.value; 
      if(typeof url == 'undefined')
        url = input.files[0]['name'];
      var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
      if (input.files && input.files[0] && (ext == "png" || ext == "jpeg" || ext == "jpg" || ext == 'PNG' || ext == 'JPEG' || ext == 'JPG' || ext == 'webp')){
        var reader = new FileReader();
        reader.onload = function (e) {
         // document.getElementById(id+'-wrapper').style.display = 'block';
          scriptJquery(id).attr('src', e.target.result);
          scriptJquery('#sescontest-image').css('background-image', 'url(' + e.target.result + ')');
        }
        document.getElementById('photouploader-element').style.display = 'none';
        document.getElementById('removeimage-wrapper').style.display = 'block';
        document.getElementById('removeimage1').style.display = 'inline-block';
        document.getElementById('contest_main_photo_preview').style.display = 'block';
        document.getElementById('contest_main_photo_preview-wrapper').style.display = 'block';
        reader.readAsDataURL(input.files[0]);
      }
    }
    function removeImage() {
        document.getElementById('photouploader-element').style.display = 'block';
        document.getElementById('removeimage-wrapper').style.display = 'none';
        document.getElementById('removeimage1').style.display = 'none';
        document.getElementById('contest_main_photo_preview').style.display = 'none';
        document.getElementById('contest_main_photo_preview-wrapper').style.display = 'none';
        document.getElementById('contest_main_photo_preview').src = '';
        document.getElementById('MAX_FILE_SIZE').value = '';
        document.getElementById('removeimage2').value = '';
        document.getElementById('photo').value = '';
        scriptJquery('#sescontest-image').css('background-image', 'url(' + e.target.result + ')');
        defaultPhoto();
    }
    function defaultPhoto(){
      <?php
            $fixDefaultPhoto = 'application/modules/Sescontest/externals/images/nophoto_contest_thumb_profile.png';
            $type = 'sescontest_contest_text_photo';
            $defaultPhoto = Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->getItem('user', $this->viewer()), 'sescontest_contest', $type);
      ?>
      var defaultPhoto1 = "<?php echo empty($defaultPhoto) ? $fixDefaultPhoto : $defaultPhoto; ?>";
      <?php 
        $type = 'sescontest_contest_photo_photo';
        $defaultPhoto2 = Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->getItem('user', $this->viewer()), 'sescontest_contest', $type);
      ?>
      var defaultPhoto2 = "<?php echo empty($defaultPhoto2) ? $fixDefaultPhoto : $defaultPhoto2; ?>";
      <?php 
        $type = 'sescontest_contest_video_photo';
        $defaultPhoto3 = Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->getItem('user', $this->viewer()), 'sescontest_contest', $type);
      ?>
      var defaultPhoto3 = "<?php echo empty($defaultPhoto3) ? $fixDefaultPhoto : $defaultPhoto3; ?>";
      <?php
        $type = 'sescontest_contest_music_photo';
        $defaultPhoto4 = Engine_Api::_()->authorization()->getPermission(Engine_Api::_()->getItem('user', $this->viewer()), 'sescontest_contest', $type);
      ?>
      var defaultPhoto4 = "<?php echo empty($defaultPhoto4) ? $fixDefaultPhoto : $defaultPhoto4; ?>";
      var currentTypeSelectedValue = 1;
      if(scriptJquery('#conteststyle-element').length){
        currentTypeSelectedValue = scriptJquery("input[name='conteststyle']:checked").val();
      }
      var url = defaultPhoto1;
      if(currentTypeSelectedValue == 1){
        url = defaultPhoto1;
      }else if(currentTypeSelectedValue == 2){
         url = defaultPhoto2;
      }else if(currentTypeSelectedValue == 3){
         url = defaultPhoto3;
      }else if(currentTypeSelectedValue == 4){
         url = defaultPhoto4;
      }
      scriptJquery('#sescontest-image').css('background-image', 'url(' + url + ')');
    }
    //validate form
    //Ajax error show before form submit
    var error = false;
    var objectError ;
    var counter = 0;
    function validateForm(){
            var errorPresent = false;
            counter = 0;
            scriptJquery('#sescontest_create_form input, #sescontest_create_form select,#sescontest_create_form checkbox,#sescontest_create_form textarea,#sescontest_create_form radio').each(
                    function(index){
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
                                    if(scriptJquery(this).css('display') == 'none'){
                                     var	content = tinymce.get(scriptJquery(this).attr('id')).getContent();
                                     if(!content)
                                        error= true;
                                     else
                                        error = false;
                                    }else	if(scriptJquery(this).val() === '' || scriptJquery(this).val() == null)
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
                                        if(scriptJquery('#photo').length && scriptJquery('#photo').val() === '' && scriptJquery('#photouploader-label').find('label').hasClass('required')){
                                                objectError = scriptJquery('#dragandrophandlerbackground');
                                                error = true;
                                        }
                                }
                                if(error)
                                    errorPresent = true;
                                error = false;
                            }
                    }
                );
                return errorPresent ;
    }
      en4.core.runonce.add(function() {
       scriptJquery('#sescontest_create_form').append('<button name="submit_share" style="display:none;" id="share_content_btn" type="submit">Save Changes</button>');
        defaultPhoto();
        scriptJquery(document).on('submit','#sescontest_create_form',function(e){
          if(isSubmitTrue == true){
            scriptJquery('#submit').attr('disabled',true);
            return true;
          }
          var validationFm = validateForm();
          if(validationFm) {
            alert('<?php echo $this->string()->escapeJavascript("Please fill the red mark fields"); ?>');
            if(typeof objectError != 'undefined'){
             var errorFirstObject = scriptJquery(objectError).parent().parent();
             <?php if(!$this->typesmoothbox){ ?>
              scriptJquery('html, body').animate({scrollTop: errorFirstObject.offset().top}, 2000);
             <?php }else{ ?>
              scriptJquery('#sescontest_create_form').animate({scrollTop: errorFirstObject.offset().top}, 2000);
             <?php } ?>
            }
            return false;	
          }
          else{
            var showErrorMessage = checkAllDateFields();
            if(showErrorMessage != ''){
              scriptJquery('#contest_error_time-wrapper').show();
              scriptJquery('#contest_error_time-element').text(showErrorMessage);
              var errorFirstObject = scriptJquery('.sescontest_choose_date');
              <?php if(!$this->typesmoothbox){ ?>
              scriptJquery('html, body').animate({scrollTop: errorFirstObject.offset().top}, 2000);
             <?php }else{ ?>
              scriptJquery('#sessmoothbox_container').animate({scrollTop: errorFirstObject.offset().top}, 2000);
             <?php } ?>
              return false;
            }else{
              scriptJquery('#contest_error_time-wrapper').hide();
            }
            if(!validUrl){
              objectError = scriptJquery('#custom_url');
              alert('<?php echo $this->string()->escapeJavascript("Invalid Custom URL"); ?>');
              if(typeof objectError != 'undefined'){
               var errorFirstObject = scriptJquery(objectError).parent().parent();
               <?php if(!$this->typesmoothbox){ ?>
              scriptJquery('html, body').animate({scrollTop: errorFirstObject.offset().top}, 2000);
             <?php }else{ ?>
              scriptJquery('#sessmoothbox_container').animate({scrollTop: errorFirstObject.offset().top}, 2000);
             <?php } ?>
              }
              return false;	
            }else{
              createImageOfContent();
              return false;
            }
          }			
        });
      });
      var isSubmitTrue = false;
      function createImageOfContent(){
        
        scriptJquery('.sescontest_join_loading').show();
        scriptJquery('.sescontest_create_form').addClass('_success');
        
        //set title
        scriptJquery('#sescontest-title').html(scriptJquery('#title').val());
        if(scriptJquery('#description').css('display') == 'none'){
           var	content = tinymce.get(scriptJquery('#description').attr('id')).getContent();
        }else
          var content = scriptJquery('#description').val();
        
        //set description
        scriptJquery('#sescontest-description').html(scriptJquery(content).text());
        //set date
        scriptJquery('#sescontest-date').html('on '+scriptJquery('#sescontest_start_date').val());
        //set media type
        scriptJquery('#sescontest-media-type').html(scriptJquery("#contest_type option:selected").text());
        var elem = scriptJquery('#content_share_img');
        if(elem.length ==0){
          scriptJquery('#sescontest_create_form').prepend('<input type="hidden" name="content_share_img" id="content_share_img">'); 
          scriptJquery('#sescontest_create_form').append('<button name="submit_share" style="display:none;" id="share_content_btn" type="submit">Save Changes</button>');
        }
        
        var c = document.getElementById('sescontest_share_content_html');
        scriptJquery('#submit').html('<?php echo $this->translate("Submitting Form ...") ; ?>');
        html2canvas(c,{
          onrendered:function(canvas){
            makeImageOfHTML(canvas.toDataURL());
          },
        });	  
      }
      
      function makeImageOfHTML(imageUrl){
         isSubmitTrue = true;
         scriptJquery('#content_share_img').val(imageUrl);
          setTimeout(function(){
            scriptJquery('#share_content_btn').unbind("submit").submit();
          },1000);    
      }
      function showEditorOption(value) {
        if(value == '1')
          scriptJquery('#editor_type-wrapper').show();
        else
          scriptJquery('#editor_type-wrapper').hide();
      }
      function showResultDate(value) {
        if(value == '1')
          scriptJquery('#sescontest_announcement_date').show();
        else
          scriptJquery('#sescontest_announcement_date').hide();
      }
    </script>

    <?php if($this->typesmoothbox) { ?>
      <script type="application/javascript">
        executetimesmoothboxTimeinterval = 200;
        executetimesmoothbox = true;
        en4.core.runonce.add(function() {
          tinymce.init({
            mode: "specific_textareas",
            plugins: "table,fullscreen,media,preview,paste,code,image,textcolor,jbimages,link",
            theme: "modern",
            menubar: false,
            statusbar: false,
            toolbar1:  "undo,redo,removeformat,pastetext,|,code,media,image,jbimages,link,fullscreen,preview",
            toolbar2: "fontselect,fontsizeselect,bold,italic,underline,strikethrough,forecolor,backcolor,|,alignleft,aligncenter,alignright,alignjustify,|,bullist,numlist,|,outdent,indent,blockquote",
            toolbar3: "",
            element_format: "html",
            height: "225px",
      content_css: "bbcode.css",
      entity_encoding: "raw",
      add_unload_trigger: "0",
      remove_linebreaks: false,
            convert_urls: false,
            language: "<?php echo $this->language; ?>",
            directionality: "<?php echo $this->direction; ?>",
            upload_url: "<?php echo $this->url(array('module' => 'sesbasic', 'controller' => 'index', 'action' => 'upload-image'), 'default', true); ?>",
            editor_selector: "tinymce"
          });
        });
        function showPreview(value) {
          if(value == 1)
          en4.core.showError('<a class="icon_close" onclick="parent.Smoothbox.close();"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 1")+'</p><img class="popup_img" src="./application/modules/Sescontest/externals/images/layout_1.jpg" alt="" />');
          else if(value == 2)
          en4.core.showError('<a class="icon_close" onclick="parent.Smoothbox.close();"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 2")+'</p><img src="./application/modules/Sescontest/externals/images/layout_2.jpg" alt="" />');
          else if(value == 3)
          en4.core.showError('<a class="icon_close" onclick="parent.Smoothbox.close();"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 3")+'</p><img src="./application/modules/Sescontest/externals/images/layout_3.jpg" alt="" />');
          else if(value == 4)
          en4.core.showError('<a class="icon_close" onclick="parent.Smoothbox.close();"><i class="fa fa-times"></i></a> <p class="popup_design_title">'+en4.core.language.translate("Design 4")+'</p><img src="./application/modules/Sescontest/externals/images/layout_4.jpg" alt="" />');
          return;
        }
      </script>	
    <?php die;} ?>
<?php else:?>
  <div class="sesbasic_tip clearfix sescontest_error">
    <img src="application/modules/Sescontest/externals/images/contest-icon-error.png" alt="">
    <span><?php echo $this->translate("You have reached the limit of contest creation. Please contact to the site administrator.");?></span>
  </div>
<?php endif;?>
